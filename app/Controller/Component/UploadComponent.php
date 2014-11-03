<?php

class UploadComponent extends Object {

    /**
     *	Private Vars
     */

    var $_file;
    var $_filepath;
    var $_destination;
    var $_name;
    var $_short;
    var $_rules;
    var $_allowed;

    /**
     *	Public Vars
     */
    var $errors;

    function initialize (&$controller) {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
    }
    function beforeRender (&$controller) {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
    }
    function shutdown (&$controller) {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
    }
    function startup (&$controller) {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
    }
    function beforeRedirect(){
    }




    /**
     * upload
     * - handle uploads of any type
     *		@ file - a file (file to upload) $_FILES[FILE_NAME]
     *		@ path - string (where to upload to)
     *		@ name [optional] - override saved filename
     *		@ rules [optional] - how to handle file types
     *			- rules['type'] = string ('resize','resizemin','resizecrop','crop')
     *			- rules['size'] = array (x, y) or single number
     *			- rules['output'] = string ('gif','png','jpg')
     *			- rules['quality'] = integer (quality of output image)
     *		@ allowed [optional] - allowed filetypes
     *			- defaults to 'jpg','jpeg','gif','png'
     *	ex:
     * 	$upload = new upload($_FILES['MyFile'], 'uploads');
     *
     */

    function upload ($file, $destination, $name = NULL, $rules = NULL, $allowed = NULL) {

        $this->result = false;
        $this->error = false;

        // -- save parameters
        $this->_file = $file;
        $this->_destination = $destination;
        if (!is_null($rules)) $this->_rules = $rules;

        if (!is_null($allowed)) { $this->_allowed = $allowed; } else { $this->_allowed = array('jpg','jpeg','gif','png'); }

        // -- hack dir if / not provided
        if (substr($this->_destination,-1) != '/') {
            $this->_destination .= '/';
        }

        // -- check that FILE array is even set
        if (isset($file) && is_array($file) && !$this->upload_error($file['error'])) {

            // -- cool, now set some variables
            $fileName = ($name == NULL) ? $this->uniqueName($destination . $file['name']) : $destination . $name;
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];
            $fileError = $file['error'];

            // -- update name
            $this->_name = $fileName;

            // -- error if not correct extension
            if(!in_array($this->ext($fileName),$this->_allowed)){
                $this->error("File type not allowed.");
            } else {

                // -- it's been uploaded with php
                if (is_uploaded_file($fileTmp)) {

                    // -- how are we handling this file
                    if ($rules == NULL) {
                        // -- where to put the file?
                        $output = $fileName;
                        // -- just upload it
                        if (move_uploaded_file($fileTmp, $output)) {
                            chmod($output, 0644);
                            $this->result = basename($this->_name);
                        } else {
                            $this->error("Could not move '$fileName' to '$destination'");
                        }
                    } else {
                        // -- gd lib check
                        if (function_exists("imagecreatefromjpeg")) {
                            if (!isset($rules['output'])) $rules['output'] = NULL;
                            if (!isset($rules['quality'])) $rules['quality'] = NULL;
                            // -- handle it based on rules
                            if (isset($rules['type']) && isset($rules['size'])) {
                                $this->image($this->_file, $rules['type'], $rules['size'], $rules['output'], $rules['quality']);
                            } else {
                                $this->error("Invalid \"rules\" parameter");
                            }
                        } else {
                            $this->error("GD library is not installed");
                        }
                    }
                } else {
                    $this->error("Possible file upload attack on '$fileName'");
                }
            }

        } else {
            $this->error("Possible file upload attack");
        }

    }

    // -- return the extension of a file
    function ext ($file) {
        $ext = trim(substr($file,strrpos($file,".")+1,strlen($file)));
        return $ext;
    }

    // -- add a message to stack (for outside checking)
    function error ($message) {
        if (!is_array($this->errors)) $this->errors = array();
        array_push($this->errors, $message);
    }

    function image ($file, $type, $size, $output = NULL, $quality = NULL) {

        if (is_null($type)) $type = 'resize';
        if (is_null($size)) $size = 100;
        if (is_null($output)) $output = 'jpg';
        if (is_null($quality)) $quality = 100;

        // -- format variables
        $type = strtolower($type);
        $output = strtolower($output);
        if (is_array($size)) {
            $maxW = intval($size[0]);
            $maxH = intval($size[1]);
        } else {
            $maxScale = intval($size);
        }

        // -- check sizes
        if (isset($maxScale)) {
            if (!$maxScale) {
                $this->error("Max scale must be set");
            }
        } else {
            if (!$maxW || !$maxH) {
                $this->error("Size width and height must be set");
                return;
            }
            if ($type == 'resize') {
                $this->error("Provide only one number for size");
            }
        }

        // -- check output
        if ($output != 'jpg' && $output != 'png' && $output != 'gif') {
            $this->error("Cannot output file as " . strtoupper($output));
        }

        if (is_numeric($quality)) {
            $quality = intval($quality);
            if ($quality > 100 || $quality < 1) {
                $quality = 100;
            }
        } else {
            $quality = 100;
        }

        // -- get some information about the file
        $uploadSize = getimagesize($file['tmp_name']);
        $uploadWidth  = $uploadSize[0];
        $uploadHeight = $uploadSize[1];
        $uploadType = $uploadSize[2];

        if ($uploadType != 1 && $uploadType != 2 && $uploadType != 3) {
            $this->error ("File type must be GIF, PNG, or JPG to resize");
        }

        switch ($uploadType) {
            case 1: $srcImg = imagecreatefromgif($file['tmp_name']); break;
            case 2: $srcImg = imagecreatefromjpeg($file['tmp_name']); break;
            case 3: $srcImg = imagecreatefrompng($file['tmp_name']); break;
            default: $this->error ("File type must be GIF, PNG, or JPG to resize");
        }

        switch ($type) {

            case 'resize':
                # Maintains the aspect ration of the image and makes sure that it fits
                # within the maxW and maxH (thus some side will be smaller)
                // -- determine new size
                if ($uploadWidth > $maxScale || $uploadHeight > $maxScale) {
                    if ($uploadWidth > $uploadHeight) {
                        $newX = $maxScale;
                        $newY = ($uploadHeight*$newX)/$uploadWidth;
                    } else if ($uploadWidth < $uploadHeight) {
                        $newY = $maxScale;
                        $newX = ($newY*$uploadWidth)/$uploadHeight;
                    } else if ($uploadWidth == $uploadHeight) {
                        $newX = $newY = $maxScale;
                    }
                } else {
                    $newX = $uploadWidth;
                    $newY = $uploadHeight;
                }

                $dstImg = imagecreatetruecolor($newX, $newY);
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);

                break;

            case 'resizemin':
                # Maintains aspect ratio but resizes the image so that once
                # one side meets its maxW or maxH condition, it stays at that size
                # (thus one side will be larger)
                #get ratios
                $ratioX = $maxW / $uploadWidth;
                $ratioY = $maxH / $uploadHeight;

                #figure out new dimensions
                if (($uploadWidth == $maxW) && ($uploadHeight == $maxH)) {
                    $newX = $uploadWidth;
                    $newY = $uploadHeight;
                } else if (($ratioX * $uploadHeight) > $maxH) {
                    $newX = $maxW;
                    $newY = ceil($ratioX * $uploadHeight);
                } else {
                    $newX = ceil($ratioY * $uploadWidth);
                    $newY = $maxH;
                }

                $dstImg = imagecreatetruecolor($newX,$newY);
                imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newX, $newY, $uploadWidth, $uploadHeight);

                break;

            case 'resizecrop':
                // -- resize to max, then crop to center
                $ratioX = $maxW / $uploadWidth;
                $ratioY = $maxH / $uploadHeight;

                if ($ratioX < $ratioY) {
                    $newX = round(($uploadWidth - ($maxW / $ratioY))/2);
                    $newY = 0;
                    $uploadWidth = round($maxW / $ratioY);
                    $uploadHeight = $uploadHeight;
                } else {
                    $newX = 0;
                    $newY = round(($uploadHeight - ($maxH / $ratioX))/2);
                    $uploadWidth = $uploadWidth;
                    $uploadHeight = round($maxH / $ratioX);
                }

                $dstImg = imagecreatetruecolor($maxW, $maxH);
                imagecopyresampled($dstImg, $srcImg, 0, 0, $newX, $newY, $maxW, $maxH, $uploadWidth, $uploadHeight);

                break;

            case 'crop':
                // -- a straight centered crop
                $startY = ($uploadHeight - $maxH)/2;
                $startX = ($uploadWidth - $maxW)/2;

                $dstImg = imageCreateTrueColor($maxW, $maxH);
                ImageCopyResampled($dstImg, $srcImg, 0, 0, $startX, $startY, $maxW, $maxH, $maxW, $maxH);

                break;

            default: $this->error ("Resize function \"$type\" does not exist");
        }

        // -- try to write
        switch ($output) {
            case 'jpg':
                $write = imagejpeg($dstImg, $this->_name, $quality);
                break;
            case 'png':
                $write = imagepng($dstImg, $this->_name . ".png", $quality);
                break;
            case 'gif':
                $write = imagegif($dstImg, $this->_name . ".gif", $quality);
                break;
        }

        // -- clean up
        imagedestroy($dstImg);

        if ($write) {
            $this->result = basename($this->_name);
        } else {
            $this->error("Could not write " . $this->_name . " to " . $this->_destination);
        }
    }

    // http://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
    private function guidv4($data)
    {
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function uniqueName ($file) {
        $parts = pathinfo($file);
        $dir = $parts['dirname'];
        $ext = $parts['extension'];
        $name = $this->guidv4(openssl_random_pseudo_bytes(16));
        return $dir.'/'.$name.'.'.$ext;
    }

    function upload_error ($errorobj) {
        $error = false;
        switch ($errorobj) {
            case UPLOAD_ERR_OK: break;
            case UPLOAD_ERR_INI_SIZE: $error = "The uploaded file exceeds the upload_max_filesize directive (".ini_get("upload_max_filesize").") in php.ini."; break;
            case UPLOAD_ERR_FORM_SIZE: $error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."; break;
            case UPLOAD_ERR_PARTIAL: $error = "The uploaded file was only partially uploaded."; break;
            case UPLOAD_ERR_NO_FILE: $error = "No file was uploaded."; break;
            case UPLOAD_ERR_NO_TMP_DIR: $error = "Missing a temporary folder."; break;
            case UPLOAD_ERR_CANT_WRITE: $error = "Failed to write file to disk"; break;
            default: $error = "Unknown File Error";
        }
        return ($error);
    }

}
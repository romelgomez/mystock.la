$(document).ready(function(){
    (function( products, $, undefined ) {

		var action = {};


        /*
         @Name              -> lastResponseData
         @visibility        -> Private
         @Type              -> Method
         @Description       -> maintains the latest data received by the server
         @parameters        -> NULL
         @returns           -> Object
         */
        var lastResponseData = {};

        /*
         @Name              -> parseUrl
         @visibility        -> Private
         @Type              -> Method
         @Description       -> parse the URL
         @parameters        -> NULL
         @returns           -> Object
         @implemented by    ->
         */
        var parseUrl = function () {

            var pathname 	= $(location).attr('href');
            var url 		= $.url(pathname);
            var segments	= url.attr('fragment');
            var userId   	= url.segment(2);

            var url_obj         	= {};
            url_obj.search      	= '';
            url_obj.page        	= '';
            url_obj['order-by']    	= '';
            url_obj['user-id']     	= userId;


            if(segments != ''){
                var split_segments = url.attr('fragment').split('/');
                if(split_segments.length){
                    $(split_segments).each(function(index,parameter){

                        if(parameter.indexOf("search-") !== -1){
                            var search_string = utility.stringReplace(parameter,'search-','');

                            /* La cadena search_string se manipula en el siguiente orden.
                             *
                             * 1) se reemplaza los caracteres especiales
                             * 2) se elimina los espacios en blancos ante y después de la cadena
                             * 3) se reemplaza los espacios en blancos largos por uno solo.
                             *
                             ********************************************************************/
                            url_obj.search = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');

                        }
                        if(parameter.indexOf("page-") !== -1){
                            url_obj.page = parseInt(utility.stringReplace(parameter,'page-',''));
                        }


                        if(parameter == 'highest-price'){
                            url_obj['order-by'] = "highest-price";
                        }
                        if(parameter == 'lowest-price'){
                            url_obj['order-by'] = "lowest-price";
                        }
                        if(parameter == 'latest'){
                            url_obj['order-by'] = "latest";
                        }
                        if(parameter == 'oldest'){
                            url_obj['order-by'] = 'oldest';
                        }
                        if(parameter == 'higher-availability'){
                            url_obj['order-by'] = 'higher-availability';
                        }
                        if(parameter == 'lower-availability'){
                            url_obj['order-by'] = 'lower-availability';
                        }


                    });
                }
            }

            return url_obj;
        };


        /*
         @Name              -> get
         @visibility        -> Private
         @Type              -> Method
         @Description       -> get data for display the products
         @parameters        -> type => if it is for products (published, drafts or in stock)
         @returns           -> Object
         @implemented by    -> main;
         */
        var get = function(type){


        };

        /*
         @Name              -> main
         @visibility        -> Public
         @Type              -> Method
         @Description       -> Where everything begins
         @parameters        -> NULL
         @returns           -> NULL
         */
        products.main = function() {

            var href 	= $(location).attr('href');
            var url 	= $.url(href);
            var action  = url.segment(1);

            // product to view

            switch (action) {
                case 'publish':
                    // new publication

                    break;
                case 'view':
                    // view publication

                    break;
                case 'published':
                    // publications published

                    break;
                case 'drafts':
                    // developing publications

                    break;
                case 'stock':
                    // publications active

//                    stock-products
//                    get-published
//                    get-drafts

                    break;
                default:
                    window.location = "/";
            }

        };

    }( window.products = window.products || {}, jQuery ));

});

/*

	stock
		 parseUrl
		 currentOrder
		 prepareProduct
		 orderBy
			stock-products
		 pagination
			stock-products
		 search
			stock-products
		 info
		 preparePublications
		 process
 		 get
			stock-products


	borradores
		 setLastResponseInfo
		 parseUrl
		 prepareProduct
		 orderBy
			get-drafts
		 pagination
 			get-drafts
		 search
 			get-drafts
		 info
		 edit
		 deleteProduct
 			delete
		 preparePublications
		 get
 			get-drafts

	publicados
		 setLastResponseInfo
		 parseUrl
		 prepareProduct
		 orderBy
			get-published
		 pagination
			get-published
		 search
			get-published
		 info
		 pause
			pause
		 activate
			 activate
		 edit
		 deleteProduct
			 delete
		 preparePublications
		 get
			 get-published

	publicar
		 initRedactor
		 discard
			 discard
		 elapsedTime
		 saveDraft
 			save_draft
		 newProduct
 			add_new
		 pause
 			pause
		 activate
 			activate
		 _delete
 			delete
		 fileUpload
			 disable_this_imagen
			 image_add


*/

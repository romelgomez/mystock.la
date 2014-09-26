<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">

            <div class="row">
                <div class="col-xs-12">

                    description


                </div>
            </div>



        </div>
    </div>
</div>


<?php

    $scripts = array();

    array_push($scripts,'/resources/app/js/base.index.js');

    echo $this->Html->script($scripts,false);

?>
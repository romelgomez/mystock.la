$(document).ready(function() {

    (function( banners, $, undefined ) {

//        //Private Property
//        var isHot = true;
//
//        //Public Property
//        skillet.ingredient = "Bacon Strips";
//
//
        //Private Method
        function changeBanner() {

            $("#change-banner").on('click',function(event){
                event.preventDefault();
                $('#change-banner-modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
//                    validate.removeValidationStates('UserForm');
                });
            });

        }

        //Public Method
        banners.main = function() {
            changeBanner();


        };

    }( window.banners = window.banners || {}, jQuery ));


    banners.main();

});
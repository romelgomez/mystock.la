$(document).ready(function(){

    /* Descripción: filtra las opciones del menu principal al tiempo que usuario escribe en el campo.
     ***********************************************************************************************/

    var search = $("#search");

    search.on('keyup',function(){
        var value = $.trim($(this).val().toLowerCase()).toLowerCase();
        matches(value);
    });
    if($.trim(search.val())){
        matches($.trim(search.val()).toLowerCase());
    }


    var masterLink = $(".master-link");

    if(masterLink.length){
        masterLink.each(function(){

            $(this).mouseover(function(){
                var show_this = $(this).attr("accordion-id");

                var accordionMenu = $("#accordion-menu").find(".in");

                accordionMenu.each(function(){
                    var hide_this =  $(this).attr("id");
                    if(hide_this != show_this){
                        $('#'+hide_this).collapse('hide');
                    }
                });

                if(accordionMenu.length == 0){
                    $('#'+show_this).collapse('show');
                }

            });

        });
    }


});

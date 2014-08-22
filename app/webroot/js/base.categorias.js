



$(document).ready(function(){

    (function( categories, $) {

        /*
         Private Method
         Descripción:  para añadir una nueva categoría
         */
        var newCategory = function(){
            $(".new_category").on('click',function(event){
                event.preventDefault();
                $('#new_category_modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                    validate.removeValidationStates('CategoryAddForm');
                });
            });

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/new_category",
                "data":{},
                "form":{
                    "id":"CategoryAddForm",
                    "inputs":[
                        {'id':'CategoryName', 'name':'name'}
                    ]
                },
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        // Si la sesión ha expirado
                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        var alert = $("#CategoryAddForm");

                        if(response['save']){
                            alert.find(".alert-success").fadeIn();
                            setTimeout(function(){ $("#CategoryAddForm").find(".alert-success").fadeOut(); },7000);
                            validate.removeValidationStates('CategoryAddForm');
                        }else{
                            alert.find(".alert-danger").fadeIn();
                            setTimeout(function(){ $("#CategoryAddForm").find(".alert-danger").fadeOut()},7000);
                        }

                        if(response['countCategories']){
                            $('#no-tree').css({"display":"none"});
                            $('#tree').fadeIn();

                            var treeData = response['categories'];
                            replaceWholeTree(treeData)
                        }

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            // validación:
            var validateObj = {
                "submitHandler": function(){
                    ajax.request(request_parameters);
                },
                "rules":{
                    "CategoryName":{
                        "required":true,
                        "maxlength":50
                    }
                },
                "messages":{
                    "CategoryName":{
                        "required":"El campo nombre es obligatorio.",
                        "maxlength":"El nombre de la categoría no debe tener mas de 50 caracteres."
                    }
                }
            };

            validate.form("CategoryAddForm",validateObj);
        };


        /*
         Private Property
         Descripción:  El árbol en el DOM
        */
        var treeElement;


        var replaceWholeTree = function(treeData){
            treeElement.tree('loadData', treeData);
        };

        /*
         Private Method
         Descripción:  Inicializa el árbol
        */
        var setTree = function(treeData){
            var options = {
                dragAndDrop: true,
                selectable: true,
                autoEscape: false,
                autoOpen: true,
                data: treeData
            };

            treeElement.tree(options);
        };


        //Public Method
        categories.init = function(){

            treeElement = $('#display_tree');

            var treeData = $.parseJSON($('#json_tree').html());

            if(treeData != null){
                $('#tree').fadeIn();
                setTree(treeData);
            }else{
                $('#no-tree').fadeIn();
            }

            newCategory();
        };

    }( window.categories = window.categories || {}, jQuery ));

    categories.init();
});



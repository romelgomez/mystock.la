



$(document).ready(function(){

    (function( categories, $) {

        /*
         Private Property
         Descripción:  El árbol en el DOM
         */
        var treeElement;


        /*
         Private Method
         Type event
         Descripción:  evento que enciende al selecionar una categoría
        */
        var treeSelect = function(){
            treeElement.bind(
                'tree.select',
                function(event) {

                    // console.log(node);

                    //  EDIT
                    $("#EditCategoryId").attr({"value":event['node']['id']});
                    $("#EditCategoryName").attr({"value":event['node']['name']});

                    //  Delete
                    $("#DelectCategoryId").attr({"value":event['node']['id']});
                    $("#delect-category-name").html(event['node']['name']);

                    if(event['node']['children'].length > 0){
                        $("#DelectCategoryBranch").parents(".form-group").show();
                    }else{
                        $("#DelectCategoryBranch").parents(".form-group").hide();
                    }

                    // Habilita los botones.
                    $("#admin_category").find("button").each(function(k,element){
                        $(element).removeClass("disabled");
                    });

                }
            );
        };

        /*
         Private Method
         Descripción:  para borrar el nombre de una categoría
        */
        var delectCategory = function(){
            $("#delect_category").on('click',function(event){
                event.preventDefault();
                if(!$(this).hasClass("disabled")){
                    // Activamos el modal
                    $('#delect_category_modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                        validate.removeValidationStates('CategoryDelectForm');
                    });
                }
            });

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/delect_category",
                "data":{},
                "form":{
                    "id":"CategoryDelectForm",
                    "inputs":[
                        {'id':'DelectCategoryId', 'name':'id'},
                        {'id':'DelectCategoryBranch', 'name':'theWholeBranch'}
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

                        var alert = $("#CategoryDelectForm");

                        if(response['status']){

                            if(response['countCategories']){
                                var treeData = response['categories'];
                                replaceWholeTree(treeData);
                            }else{
                                $('#tree').css({"display":"none"});
                                $('#no-tree').show();
                            }

                            validate.removeValidationStates('CategoryDelectForm');
                            $('#delect_category_modal').modal('hide');
                        }else{
                            alert.find(".alert-danger").fadeIn();
                            setTimeout(function(){ $("#CategoryDelectForm").find(".alert-danger").fadeOut()},7000);
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
                }
            };

            validate.form("CategoryDelectForm",validateObj);
        };


        /*
         Private Method
         Descripción:  para cambiar el nombre de una categoría
        */
        var editCategoryName = function(){
            $("#edit_category_name").on('click',function(event){
                event.preventDefault();
                if(!$(this).hasClass("disabled")){
                    // Activamos el modal
                    $('#edit_category_name_modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hide.bs.modal',function(){
                        validate.removeValidationStates('CategoryEditForm');
                    });
                }
            });

            var request_parameters = {
                "requestType":"form",
                "type":"post",
                "url":"/edit_category_name",
                "data":{},
                "form":{
                    "id":"CategoryEditForm",
                    "inputs":[
                        {'id':'EditCategoryId', 'name':'id'},
                        {'id':'EditCategoryName', 'name':'name'}
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

                        var alert = $("#CategoryEditForm");

                        if(response['save']){
                            $("#EditCategoryName").attr({"value":response['Category']['name']});

                            if(response['countCategories']){
                                var treeData = response['categories'];
                                replaceWholeTree(treeData);
                            }

                            validate.removeValidationStates('CategoryAddForm');
                            $('#edit_category_name_modal').modal('hide');
                        }else{
                            alert.find(".alert-danger").fadeIn();
                            setTimeout(function(){ $("#CategoryEditForm").find(".alert-danger").fadeOut()},7000);
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
                    "EditCategoryName":{
                        "required":true,
                        "maxlength":20
                    }
                },
                "messages":{
                    "EditCategoryName":{
                        "required":"El campo nombre es obligatorio.",
                        "maxlength":"El nombre de la categoría no debe tener mas de 20 caracteres."
                    }
                }
            };

            validate.form("CategoryEditForm",validateObj);

        };


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
            treeSelect(); // event
            editCategoryName();
            delectCategory();
        };

    }( window.categories = window.categories || {}, jQuery ));

    categories.init();
});



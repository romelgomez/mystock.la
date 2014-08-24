



$(document).ready(function(){

    (function( categories, $) {

        /*
         Private Property
         Descripción:  El árbol en el DOM
         */
        var treeElement;


        /*
         Private Method
         Descripción:  usado por el evento tree.move, o la función treeMove
        */
        var moveTo = function(moved_node,target_node,position){
            var request = {};

            var a = parseInt(moved_node['lft']);
            var b = parseInt(target_node['lft']);

            if(a < b){
                console.log('bajar');
                request['move_to'] = 'moveDown';
                request['min']      = parseInt(moved_node['rght'])+1;
                request['max']      = parseInt(target_node['rght']);
                return request;
            }
            if(a > b){
                console.log('subir');
                request['move_to'] = 'moveUp';
                if(position == "before"){
                    request['min'] = parseInt(target_node['lft']);
                    request['max'] = parseInt(moved_node['lft'])-1;
                }
                if(position == "inside"){
                    request['min'] = parseInt(target_node['lft'])+1;
                    request['max'] = parseInt(moved_node['lft'])-1;
                }
                if(position == "after"){
                    request['min'] = parseInt(target_node['rght'])+1;
                    request['max'] = parseInt(moved_node['lft'])-1;
                }
                return request;
            }
        };


        var move_to = function(moved_node,target_node,position){
            var request = {};

            var a = parseInt(moved_node['lft']);
            var b = parseInt(target_node['lft']);

            if(a < b){
                //console.log('bajar');
                request.move_to = 'moveDown';
                request.min = parseInt(moved_node['rght'])+1;
                request.max = parseInt(target_node['rght']);
                return request;
            }
            if(a > b){
                //console.log('subir');
                request.move_to = 'moveUp';
                if(position == "before"){
                    request.min = parseInt(target_node['lft']);
                    request.max = parseInt(moved_node['lft'])-1;
                }
                if(position == "inside"){
                    request.min = parseInt(target_node['lft'])+1;
                    request.max = parseInt(moved_node['lft'])-1;
                }
                if(position == "after"){
                    request.min = parseInt(target_node['rght'])+1;
                    request.max = parseInt(moved_node['lft'])-1;
                }
                return request;
            }
        };


        /*
         Private Method
         Type event
         Descripción:  evento que enciende al mover una categoría
         */
        var treeMove = function(){

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/edit_category_position",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }
//

//                        var data = obj['categories'];
//                        set_tree(tree,data);

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            treeElement.bind(
                'tree.move',
                function(event) {

                    var moved_node 			= event.move_info.moved_node;
                    var target_node 		= event.move_info.target_node;
                    var position			= event.move_info.position;
                    var previous_parent		= event.move_info.previous_parent;

                    /* DEBUG */
                    /*
                     console.log('previous_parent',previous_parent);		//-> Determina cual fue su anterior padre si existe, si no tubo padre es nulo.
                     console.log('moved_node',moved_node);				//-> Objeto movido.
                     console.log('target_node',target_node);				//-> sobre, luego o internamente sobre este objeto.
                     console.log('position',position);					//-> posición: sobre, luego, internamente.
                     console.log(' ');
                     */

                    var request_this = {};

                    if(position =="before"){
                        if(moved_node.parent_id == null){
                            //console.log('solo mover');
                            console.log(move_to(moved_node,target_node,position));

                            request_this 			= move_to(moved_node,target_node,position);
                            request_this.id			= moved_node.id;
                            request_this.parent_id  = moved_node.parent_id;
                            request_this.type		= 'only_move';

                        }else{
                            //console.log('set_parent_and_move');

                            request_this.new_parent_id			= null;
                            request_this.moved_node_id			= moved_node.id;
                            request_this.target_node_id			= target_node.id;
                            request_this.position				= position;
                            request_this.type					= 'set_parent_and_move';

                        }
                    }
                    if(position =="after"){
                        if(moved_node.parent_id == target_node.parent_id){
                            //console.log('solo mover');

                            request_this 			= move_to(moved_node,target_node,position);
                            request_this.id			= moved_node.id;
                            request_this.parent_id  = moved_node.parent_id;
                            request_this.type		= 'only_move';

                        }else{
                            //console.log('set_parent_and_move');

                            request_this.new_parent_id			= target_node.parent_id;
                            request_this.moved_node_id			= moved_node.id;
                            request_this.target_node_id			= target_node.id;
                            request_this.position				= position;
                            request_this.type					= 'set_parent_and_move';

                        }
                    }
                    if(position =="inside"){
                        if(moved_node.parent_id == target_node.id){
                            //console.log('solo mover');

                            request_this 			= move_to(moved_node,target_node,position);
                            request_this.id			= moved_node.id;
                            request_this.parent_id  = moved_node.parent_id;
                            request_this.type		= 'only_move';

                        }else{
                            //console.log('set_parent_and_move');

                            request_this.new_parent_id			= target_node.id;
                            request_this.moved_node_id			= moved_node.id;
                            request_this.target_node_id			= target_node.id;
                            request_this.position				= position;
                            request_this.type					= 'set_parent_and_move';

                        }
                    }

                    request_parameters['data'] = request_this;
                    ajax.request(request_parameters);

                }
            );
        };


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
            treeMove(); // event
        };

    }( window.categories = window.categories || {}, jQuery ));

    categories.init();
});






$(document).ready(function(){

    (function( product, $) {

        //Private Method
        var parseUrl = function(){
            /*
             * Descripción: destinada a procesar la url
             * retorna un objeto.
             *******************************************/

            // Posibles urls
            // /publicar
            // /editar/18
            // /borrador/18

            var url = $.url();
            var segments = url.segment();

            var url_obj = {};
            url_obj.action = segments[0];
            url_obj.id     = segments[1] || false;

            return url_obj;

        };

        /*
         Private Method
         Descripción: para re-construir y observar eventos del menú.
         Parámetros:
         menu: JSON OBJECT
         */
        var setMenu = function(obj){

            var menu = $('#menu');
            var add_content = $('#add-content');

            var ul = '';

            var newWidth                    = 0;
            var parent_obj_width_dimension  = 0;
            var child_obj_width_dimension   = 0;
            var scroll_value                = 0;

            if(obj['children']){

                // los elementos que estén + 1 - cuento los menús - el por defecto y los que estén desplegados - más el que será próximamente insertado en el dom es decir (+1) y así calcular el nuevo ancho
                newWidth = ((menu.children().length)+1)*251;
                menu.css({"width":newWidth+'px'});

                //var scroll_value = $('menu-box').scrollLeft	- la idea es si la caja interna sobrepasa el tamaño al insertar un nuevo menu dependiente
                // el scroll automáticamente se actualiza pero no es visible el sub-menú por lo tanto hay que auto rodar hasta el final
                parent_obj_width_dimension 	= menu.parent().width();
                child_obj_width_dimension	= menu.width();

                if(child_obj_width_dimension > parent_obj_width_dimension){
                    scroll_value = child_obj_width_dimension - parent_obj_width_dimension;
                    $('#menu-box').scrollLeft(scroll_value);
                }



                var tmp1							= base.randomNumber(1,9999);
                var tmp2 							= base.randomNumber(10000,20000);
                var random_category_id_container	= 'dependent-options-'+base.randomNumber(tmp1,tmp2);


                ul += '<div class="ulMenu" id="'+random_category_id_container+'">';
                $.each(obj['categories'],function(k,v){
                    ul +=  '<div id="category-id-'+k+'" class="liMenu"> '+v+'</div>'
                });
                ul += '</div>';

                menu.append(ul);

                // se observa este nuevo elemento - el nuevo sub-menú para ser procesado
                observeTheCategories(random_category_id_container);

                $('#ProductCategoryId').attr({"value":null});

                add_content.attr({"disabled":"disabled"}); //  se inhabilita el botón para proseguir con la carga del nuevo product
                // add .disabled
                if(!add_content.hasClass("disabled")){
                    add_content.addClass("disabled");
                }

            }else{

                newWidth = ((menu.children().length)*251)+301;
                menu.css({"width":newWidth+'px'});

                // var scroll_value = $('menu-box').scrollLeft - la idea es si la caja interna sobrepasa el tamaño al insertar un nuevo menu dependiente
                // el scroll automáticamente se actualiza pero no es visible el sub-menú por lo tanto hay que auto rodar hasta el final
                parent_obj_width_dimension 	= menu.parent().width();
                child_obj_width_dimension	= menu.width();

                if(child_obj_width_dimension > parent_obj_width_dimension){
                    scroll_value = child_obj_width_dimension - parent_obj_width_dimension;
                    $('#menu-box').scrollLeft(scroll_value);
                }

                ul = '<div id="category-selected" class="category-selected"><div style="margin-top: 50px;" ><div style="text-align: center;"><span class="category-selected-text">Categoría seleccionada!</span> <br><img src="/img/ok.png" alt="Gracias" /></div></div></div>';
                menu.append(ul);

                $('#ProductCategoryId').attr({"value":obj['category_id_selected']});

                add_content.attr({"disabled":false});
                // remove css class .disabled
                if(add_content.hasClass("disabled")){
                    add_content.removeClass("disabled");
                }

            }

            var category_element = $('#category-id-'+obj['id']);

            // establecer estilos - del actual contenedor-menu - se verifica si existe un elemento con la clase css li-selected - si existe se le remueve - se procede a agregarle la clase css  li-selected a la categoría seleccionada
            $(category_element).parent().children().each(function(){
                if($(this).hasClass('li-selected')){
                    $(this).removeClass('li-selected');
                }
            });
            $(category_element).addClass('li-selected');

        };


        //
        /*
         Private Method
         Descripción: remueve los vecinos a la derecha, acomodando el espacio para recibir e insertar la respuesta del servidor acerca de si hay o no mas categorías dependientes.
         Parámetros:
            element_id
         */

        var clear = function(element_id){
            var category_id_container	= $("#"+element_id).parent().attr('id');
            $('#'+category_id_container).nextAll().each(function(){
                $(this).remove();
            });

        };

        /*
         Private Method
         Descripción: para construir y observar el path: electrónicos>laptops>14"  cada sección representa una previa selección en el menú, la idea es regresar a un estado anterior del menú rápidamente sin tener que observar todas las categorías.
         Parámetros:
         path: JSON OBJECT
         */
        var setPath =  function(path){
            var h = '';

            $.each(path,function(k,category){

                if((k+1)==$(path).length){
                    h += '<li id="'+'path-category-id-'+category['id']+'" class="active">'+category['name']+'</li>';
                }else{
                    h += '<li id="'+'path-category-id-'+category['id']+'"><span style="color: #08C;">'+category['name']+'</span></li>';
                }
            });

            var pathElement = $('#path');

            pathElement.html(h);
            $('#path2').html(h);

            pathElement.children().each(function(){
                $(this).click(function(event){
                    event.preventDefault();

                    var path_category_id            = $(this).attr('id'); //  path-category-id-142
                    var category_id                 = str_replace(path_category_id,'path-category-id-','');	// 142

                    var element_id = 'category-id-'+category_id;
                    clear(element_id);// category-id-142

                    getChildCategories(category_id);

                })
            });
        };

        /*
         Private Method
         Descripción: Administrar la transición, luego de seleccionar la categoría, al editar la categoría.
         */
        var transition = function(){
            // Cuando se va editar hay que acomodar el menú de selección de categorías para que en caso de que el usuario quiera editar la categoría pueda observar el menú tal cual lo dejo.
            if($('#ProductCategoryId').val()){
//        update_all_menu();

                //console.log('se debe empezar a migrar UpdateAllMenu.buildMenu(id) de categories.js');

                $('#category-selector').css({"display":"none"});
                $('#add-product').css({"display":'block'});
            }else{
                $('#category-selector').css({"display": "block"});
                $('add-product').css({"display": "none"});
            }

            $('#add-content').click(function(event){
                event.preventDefault();
                $('#category-selector').css({"display":"none"});
                $('#add-product').css({"display":"inherit"});
            });

            $('#edit-category').click(function(event){
                event.preventDefault();

                $('#category-selector').css({"display":"block"});
                $('#add-product').css({"display": "none"});

                var menu = $('#menu');

                var parent_obj_width_dimension 	= menu.parent().width();
                var child_obj_width_dimension	= menu.width();

                if(child_obj_width_dimension > parent_obj_width_dimension){
                    var scroll_value = child_obj_width_dimension - parent_obj_width_dimension;
                    $('#menu-box').scrollLeft(scroll_value);
                }

            });
        };



        /*
         Private Method
         Descripción: núcleo del menú principal, determina si la categoría suministrada tiene descendientes, si los tiene construye un nuevo bloque, si no tiene se supone que es la categoría seleccionada.
         Parámetros:
         category_id: int, el id de la categoría
        */
        var getChildCategories = function(category_id){

            var request_parameters = {
                "requestType":"custom",
                "type":"post",
                "url":"/get_category_child_elements",
                "data":{},
                "callbacks":{
                    "beforeSend":function(){},
                    "success":function(response){
                        $('#debug').text(JSON.stringify(response));

                        if(response['expired_session']){
                            window.location = "/entrar";
                        }

                        setMenu(response);
                        setPath(response['path']);

                    },
                    "error":function(){},
                    "complete":function(response){}
                }
            };

            request_parameters['data']['category_id'] = category_id;

            ajax.request(request_parameters);


        };

        //Private Method
        var observeTheCategories = function(category_id_container){

            if(!category_id_container){
                category_id_container = 'default-options';
            }

            // Id del nodo que contiene las categorías
            $('#'+category_id_container).children().each(function(){
                $(this).click(function(event){
                    event.preventDefault();

                    var element_id  = $(this).attr('id');                           // category-id-142
                    var category_id = str_replace(element_id,'category-id-','');    // 142

                    clear(element_id);// category-id-142
                    getChildCategories(category_id);

                });
            });
        };


        //Public Method
        product.init = function(){
            observeTheCategories();
            transition();
        };


    }( window.product = window.product || {}, jQuery ));


    product.init();


//    if($('#default-options')){
//        observer_category_container();
//        transition();
//    }
//
//    if($('#product_thumbnails').find("a").length){
//        /* inhabilitar miniaturas del producto
//         *****************************************/
//        disable_thumbnails();
//
//        /* Visualizar en mejor resolución una miniatura habilitada del producto
//         ************************************************************************/
//        better_visualizing();
//    }
//
//
//    discard();
//
//    save_draft(false);
//
//    validate.form ("ProductAddForm",new_product_validate_obj);
//
//
//    activate();
//
//    pause();
//
//    _delete();
//
//    $('#ProductBody').redactor();


});


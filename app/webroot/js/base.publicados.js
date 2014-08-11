/*

 registros a mostrar = 10; segun esta cantidad cierto comportamiento es observado.

 solo 1 registro
 * paginacion 				inavilitada
 * ordenar por precio		inavilitada
 * busqueda					inavilitada

 entre 1 y 10 registros
 * paginacion 				inavilitada - Segun la cantidad de registros que se muestra en una primera vez.
 * ordenar por precio		avilitado
 * busqueda					inavilitada

 mas de 10 registros
 * paginacion 				avilitado - Segun la cantidad de registros que se muestra en una primera vez.
 * ordenar por precio		avilitado
 * busqueda					avilitado

 */

/*
 * Type: función
 * Descripción: funcion destinada a ordenar la publicaciones, segun la preferencia del usuario.
 * Parametros: null
 *************************************************************************************************************************************************************/
var order_by =  function(){

    var order_by_obj = {
        "type":"post",
        "url":"/products_published",
        "data":{
            "custom":{}
        },
        "console_log":true,
        "callbacks":{
            "complete":function(response){

                var a	= response.responseText;
                var obj = $.parseJSON(a);
                if(obj.expired_session){
                    window.location = "/entrar";
                }

                if(obj.result){
                    if(obj.data){
                        // hay publicaciones
                        _var = {};
                        _var = new set_vars(obj);
                        prepare_publications();
                    }else{
                        // no hay publicaciones.
                        $("#no-products").css({"display":"inherit"});
                    }
                }else{
                    // hay un error en la solicitud.
                    window.location = "/cuenta";
                }


            }
        }
    };


    var order_by = {
        "higher-price":"mayor_precio",
        "lower-price":"menor_precio",
        "recent":"recientes",
        "oldest":"antiguos",
        "higher-availability":"mayor_disponibilidad",
        "lower-availability":"menor_disponibilidad"
    };

    if(_var.count > 1){

        $.each(order_by,function(id,order_by){

            var element = $("#"+id);

            element.off('click');
            element.on('click',function(event){
                event.preventDefault();

                var url_obj =  parse.url();

                console.log(url_obj)
                var request_this 				= {};

                if(url_obj.search != ''){
                    // se solicita buscar algo.
                    request_this.search	= url_obj.search;

                    var url = str_replace(obj.search,' ','_');
                    window.location = "#buscar_"+url+"/"+order_by;

                }else{
                    window.location = "#"+order_by;
                }

                request_this.order_by			= order_by;
                order_by_obj.data.custom		= request_this;
                new Request(order_by_obj);

            });

        });

        $("#order-by").css({"display":""});

    }else{
        $("#order-by").css({"display":"none"});
    }

};


/*
 * Type: función
 * Descripción:   se establece la informacion de la cantidad de registros existentes
 * Parametros: 
 *************************************************************************************************************************************************************/
var info = function(){

//    console.log(_var);

    /*

     Algoritmo para obtener el resultado esperado con la data disponible:
     Cálculo Imaginario

     count 	:	el total de registros
     current :	los que actualmente son observados.

     1  - 10 de 35	----------- current: 10		1	2	3	4	6	5	7	9	8	10
     11 - 20 de 35   ----------- current: 10		11	12	13	14	15	16	17	18	19	20
     21 - 30 de 35	----------- current: 10		21	22	23	24	25	26	27	28	29	30
     31 - 35 de 35	----------- current: 5		31	32	33	34	35


     1 - 10 de 35

     count: 35
     current: 10
     data: Array[10]
     nextPage: true
     page: 1
     pageCount: 4
     prevPage: false

     if page < pageCount

     De 		=	page*current-10+1 	= 1*10-10+1 = 1
     Hasta	=	page*current					= 10



     11 - 20 de 35

     count: 35
     current: 10
     data: Array[10]
     nextPage: true
     page: 2
     pageCount: 4
     prevPage: true


     if page < pageCount

     De 		=	page*current-10+1 =	2*10-10+1 	= 11
     Hasta	=	page*current					=	20


     21 - 30 de 35

     count: 35
     current: 10
     data: Array[10]
     nextPage: true
     page: 3
     pageCount: 4
     prevPage: true

     if page < pageCount

     De 		=	page*current-10+1 = 3*10-10+1	= 21
     Hasta	=	page*current					= 30


     31 - 35 de 35

     count: 35
     current: 5
     data: Array[5]
     nextPage: false
     page: 4
     pageCount: 4
     prevPage: true

     if page ==  pageCount

     De 		=	count-current+1	= 31
     Hasta	=	count			= 35

     ********************************************************************

     1 - 5 de 5

     count: 5
     current: 5
     data: Array[5]
     nextPage: false
     page: 1
     pageCount: 1
     prevPage: false

     if page ==  pageCount

     De 		=	count-current+1	= 1
     Hasta	=	count			= 5

     */

    if(_var.count > 0){
        if(_var.count == 1){
            info = '1 publicación';
        }else{

            var de = '';
            var hasta = '';

            if(_var.page == _var.pageCount){
                de 		= _var.count-_var.current+1;
                hasta	= _var.count;
            }

            if(_var.page < _var.pageCount){
                de 		= (_var.page*_var.current)-10+1;
                hasta	= _var.page*_var.current;
            }

            var info = '<b>'+de+'</b> - <b>'+hasta+'</b> de <b>'+_var.count+'</b>';

        }
    }else{
        info = '0 publicaciónes';
    }

    // se establece la informacion de la cantidad de registros existentes
    $("#pagination-info").find("span").html(info);

};



/*
 * Type: función
 * Descripción: encargada de administrar la paginación de los resultados.
 * Parametros: null
 *************************************************************************************************************************************************************/
var pagination = function(){

    var pagination_obj = {
        "type":"post",
        "url":"/products_published",
        "data":{
            "custom":{}
        },
        "console_log":true,
        "callbacks":{
            "complete":function(response){

                var a = response.responseText;
                var obj = $.parseJSON(a);
                if(obj.expired_session){
                    window.location = "/entrar";
                }

                if(obj.result){
                    if(obj.data){
                        // hay publicaciones
                        _var = {};
                        _var = new set_vars(obj);
                        prepare_publications();
                    }else{
                        // no hay publicaciones.
                        $("#no-products").css({"display":"inherit"});
                    }
                }else{
                    // hay un error en la solicitud.
                    window.location = "/cuenta";
                }


            }
        }
    };

//console.log(_var);

    if(_var.pageCount > 1){

        // si exite una pagina anterior y si la pagina anterior no es la 0
        if(_var.prevPage && (_var.page-1) != 0){

            var prevPage = $("#prev-page");

            prevPage.attr({"disabled":false}).removeClass('disabled');
            prevPage.off('click');
            prevPage.on('click', function(){

                var url_obj =  parse.url();
                var prev_page = _var.page-1; // tambien puede tomar el valor de: url_obj.page
                var request_this = {};

                // PAGE
                request_this.page = prev_page;

                var url     = '';
                var new_url = '';

                if(url_obj.order_by != ""){
                    if(url_obj.search != ""){
                        url = str_replace(obj.search,' ','_');
                        new_url = "#buscar_"+url+"/"+url_obj.order_by+"/pagina_"+prev_page;
                        //SEARCH
                        request_this.search = url_obj.search;
                    }else{
                        new_url = "#"+url_obj.order_by+"/pagina_"+prev_page;
                    }
                    // ORDER
                    request_this.order_by = url_obj.order_by;
                }else{
                    if(url_obj.search != ""){
                        url = str_replace(obj.search,' ','_');
                        new_url = "#buscar_"+url+"/pagina_"+prev_page;
                        //SEARCH
                        request_this.search = url_obj.search;
                    }else{
                        new_url = "#pagina_"+prev_page;
                    }
                }

                window.location = new_url;
                pagination_obj.data.custom = request_this;
                new Request(pagination_obj);

                // al establecer la solicitud ajax un aviso de cargando deber ser colocado.

            });

        }else{
            $("#prev-page").attr({"disabled":true}).addClass('disabled');
        }

        // si existe una siguiente pagina
        if(_var.nextPage){

            var nextPage = $("#next-page");

            nextPage.attr({"disabled":false}).removeClass('disabled');
            nextPage.off('click');
            nextPage.on('click', function(){

                var url_obj =  parse.url();

                var next_page = _var.page+1; // tambien puede tomar el valor de: url_obj.page
                var request_this = {};

                // PAGE
                request_this.page = next_page;

                var url     = '';
                var new_url = '';

                if(url_obj.order_by != ""){
                    if(url_obj.search != ""){
                        url = str_replace(obj.search,' ','_');
                        new_url = "#buscar_"+url+"/"+url_obj.order_by+"/pagina_"+next_page;
                        //SEARCH
                        request_this.search = url_obj.search;
                    }else{
                        new_url = "#"+url_obj.order_by+"/pagina_"+next_page;
                    }
                    // ORDER
                    request_this.order_by = url_obj.order_by;
                }else{
                    if(url_obj.search != ""){
                        url = str_replace(obj.search,' ','_');
                        new_url = "#buscar_"+url+"/pagina_"+next_page;
                        //SEARCH
                        request_this.search = url_obj.search;
                    }else{
                        new_url = "#pagina_"+next_page;
                    }
                }

                window.location = new_url;
                pagination_obj.data.custom = request_this;
                new Request(pagination_obj);

            });
        }else{
            $("#next-page").attr({"disabled":true}).addClass('disabled');
        }

        $("#pagination").css({"display":""});
    }else{
        $("#pagination").css({"display":"none"});
    }

};


/*
 * Type: función
 * Descripción: función destinada a pausar una publicación activa
 * Parametros:  null  
 *************************************************************************************************************************************************************/
var pause = function(){

    var pause_obj = {
        "type":"post",
        "url":"/pause",
        "data":{
            "custom":{}
        },
        "console_log":true,
        "callbacks":{
            "complete":function(response){

                var a = response.responseText;
//                console.log(a);

                var obj = $.parseJSON(a);
                if(obj.expired_session){
                    window.location = "/entrar";
                }

                if(obj.result){
                    $("#product-"+obj.id+' .pause').css({
                        "display": 'none'
                    });
                    $("#product-"+obj.id+' .activate').css({
                        "display": 'inline'
                    });

                    var status = '<span class="label label-warning paused-status">pausado</span>';
                    $("#product-"+obj.id+' .active-status').replaceWith(status);

                }else{
                    window.location = "/";
                }

            }
        }
    };


    var elements = $("#published").find(".pause");

    if(elements.length){
        $(elements).each(function(){
            $(this).off('click');
            $(this).on('click',function(){
                var pure_json_obj   = $(this).parents("div.media").children().last().html();
                var obj             = $.parseJSON(clean_obj(pure_json_obj));
                var request_this = {};
                request_this.id  = obj.product.id;
                pause_obj.data.custom = request_this;
                new Request(pause_obj);
            });

        });
    }

};

/*
 * Type: función
 * Descripción: función destinada a activar una publicación pausada
 * Parametros:  null  
 *************************************************************************************************************************************************************/
var activate = function(){

    var pause_obj = {
        "type":"post",
        "url":"/activate",
        "data":{
            "custom":{}
        },
        "console_log":false,
        "callbacks":{
            "complete":function(response){

                var a = response.responseText;
                var obj = $.parseJSON(a);
                if(obj.expired_session){
                    window.location = "/entrar";
                }

                if(obj.result){
                    $("#product-"+obj.id+' .pause').css({
                        "display": "inline"
                    });
                    $("#product-"+obj.id+' .activate').css({
                        "display": "none"
                    });
                    var status = '<span class="label label-success active-status">publicado</span>';
                    $("#product-"+obj.id+' .paused-status').replaceWith(status);
                }else{
                    window.location = "/";
                }
            }
        }
    };

    var elements = $("#published").find(".activate");

    if(elements.length){
        $(elements).each(function(){
            $(this).off('click');
            $(this).on('click',function(){
                var pure_json_obj   = $(this).parents("div.media").children().last().html();
                var obj             = $.parseJSON(clean_obj(pure_json_obj));
                var request_this = {};
                request_this.id  = obj.product.id;
                pause_obj.data.custom = request_this;
                new Request(pause_obj);
            });
        });
    }

};

/*
 * Type: función
 * Descripción: función que procesa la solicitud de editar una publicación,  la razón de crear una función y no un simple link que es más simple, es por la maquetación o bootstrap, como es un grupo de botones pegados, al colocar un link <a></a> se descuadra. por lo tanto es requerido usar una función.
 * Parametros:  null
 *************************************************************************************************************************************************************/
var edit = function(){

    var elements = $("#published").find(".edit");

    if(elements.length){
        $(elements).each(function(){
            $(this).off('click');
            $(this).on('click',function(){

                var pure_json_obj   = $(this).parents("div.media").children().last().html();
                var obj             = $.parseJSON(clean_obj(pure_json_obj));

                // edit link
                window.location = '/editar/'+obj.product.id;

            });
        });
    }
};

/*
 * Type: función
 * Descrición: funcion que procesa la solicitud de borrar una publicación    
 * Parametros: null
 *************************************************************************************************************************************************************/
var _delete =  function(){

    var delete_obj = {
        "type":"post",
        "url":"/delete",
        "data":{
            "custom":{}
        },
        "console_log":true,
        "callbacks":{
            "complete":function(response){

                var a = response.responseText;
                var obj = $.parseJSON(a);

                console.log(obj);

                if(obj.expired_session){
                    window.location = "/entrar";
                }

                //  delete_status
                if(obj.result){

                    // Exito al eliminar la publicación
                    $("#successful-elimination").fadeIn();
                    setTimeout(function(){ $("#successful-elimination").fadeOut(); }, 7000);

                    // Ocultamos lentamente la publicación y luego removemos el elemento del dom.
                    $("#product-"+obj.id).fadeOut('slow',function(){
                        $(this).remove();

                        if(obj.data){
                            // hay publicaciones

                            var url_obj =  parse.url();

                            /* START Esta logica permita resolver la url, ya que puede sufrir cambios debido a la eliminacion de publicaciones. un caso es cuando se borran todas las publicaciones de la pagina 2,
                             * el sistema automaticamente muestra las publicaciones de la pagina 1, hay es cuando esta logica entra en accion, resolviendola a como debe ser segun la data mostrada.
                             ***********************************************************************************************************************************************************************************/

                            // esta definido  order_by
                            if(url_obj.order_by != ''){
                                if(url_obj.page != ''){
                                    if(obj.info.pageCount == 1){
                                        window.location = "#"+obj.win_order_by;
                                    }else{
                                        window.location = "#"+obj.win_order_by+'/pagina_'+obj.info.page;
                                    }
                                }else{
                                    window.location = "#"+obj.win_order_by;
                                }
                            }else{
                                if(url_obj.page != ''){
                                    if(obj.info.pageCount == 1){
                                        window.location = "#";
                                    }else{
                                        window.location = "#pagina_"+obj.info.page;
                                    }
                                }
                            }

                            // END


                            if(obj.info.pageCount == 1){
                                $("#prev-page").off('click');
                                $("#next-page").off('click');
                                $("#pagination").css({"display":"none"});
                            }

                            _var = {};
                            _var = new set_vars(obj);
                            prepare_publications('after');
                        }else{
                            window.location = "#";

                            //se oculta el contenedor de los filtros
                            $("#information-panel").css({"display":"none"});

                            // no hay publicaciones.
                            $("#no-products").css({"display":"inherit"});
                        }

                    });



                }else{
                    window.location = "/";
                }

            }
        }
    };

    var elements = $("#published").find(".delete");

    if(elements.length){
        $(elements).each(function(){

            $(this).off('click');
            $(this).on('click',function(){
                var pure_json_obj = $(this).parents("div.media").children().last().html();
                var obj 			= $.parseJSON(clean_obj(pure_json_obj));
                $("#delete_product").attr({"product_id":obj.product.id});

                $('#delete_product_modal').modal({"backdrop":true,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
                });
            });

            var element = $("#delete_product");

            element.off('click');
            element.on('click',function(){
                $('#delete_product_modal').modal('hide');

                var request_this = {};

                var url_obj =  parse.url();
                if(url_obj.page != ''){
                    request_this.page	= url_obj.page;
                }
                if(url_obj.order_by != ''){
                    request_this.order_by	= url_obj.order_by;
                }

                request_this.id  		= parseInt($(this).attr("product_id"));
                request_this.session 	= false;
                request_this.paginate 	= true;
                delete_obj.data.custom = request_this;
                new Request(delete_obj);

            });

        });
    }

};

/*
 * Type: función
 * Descrición: funcion que se encarga de dar formato html a las publicaciones registradas.    
 * Parametros: 
 * 	obj: objeto
 * Retorna: una cadena.
 *************************************************************************************************************************************************************/
var prepare_product = function(obj){

    var id 			= obj.product.id;
    var title 		= obj.product.title;
    var subtitle 	= obj.product.subtitle;
    var price 		= obj.product.price;
    var published 	= obj.product.created;

    var slug 		= 	str_replace(title.toLowerCase().trim(),' ','_');
    var link 		= 	'/producto/'+id+'/'+slug+'.html';

    var image		= 'img/products/'+obj.imagen.thumbnails.small.name;

    var status = '';
    var status_button = '';

    if(obj.product.status){
        status = '<span class="label label-success active-status">publicado</span>';
        status_button = '<button class="btn pause"><i class="icon-stop"></i> Pausar</button>'+
            '<button class="btn activate" style="display:none;"><i class="icon-stop"></i> Activar</button>';
    }else{
        status = '<span class="label label-warning paused-status">pausado</span>';
        status_button = '<button class="btn pause" style="display:none;"><i class="icon-stop"></i> Pausar</button>'+
            '<button class="btn activate" "><i class="icon-stop"></i> Activar</button>';
    }

    var quantity = obj.product.quantity;
    var _quantity = '';

    if(quantity == 0){
        _quantity = '<span class="badge">0</span>';
    }
    else if(quantity>= 1 && quantity<=5){
        _quantity = '<span class="badge badge-important">'+quantity+'</span>';
    }
    else if(quantity>=6 && quantity<=10){
        _quantity = '<span class="badge badge-warning">'+quantity+'</span>';
    }
    else if(quantity>10){
        _quantity = '<span class="badge badge-success">'+quantity+'</span>';
    }
    // END


    // se arma una publicación
    return      '<div id="product-'+id+'"  class="media" >'+
                    '<a class="pull-left thumbnail" href="'+link+'">'+
                        '<img src="'+image+'"  style="width: 200px; ">'+
                    '</a>'+
                    '<div class="media-body">'+
                        '<h4 class="media-heading"><input type="checkbox" style="margin-top: 0;"> <a href="'+link+'" >'+title+'</a></h4>'+
                        '<h5 class="muted" >'+subtitle+'</h5>'+

                        '<div style="margin-bottom: 10px;">'+
                            '<div class="btn-group">'+
                                '<button class="btn edit"><i class="icon-edit"></i> Editar</button>'+
                                 status_button+
                                '<button class="btn btn-danger delete" ><i class="icon-remove-sign"></i> Eliminar</button>'+
                            '</div>'+
                        '</div>'+
                        '<div>'+
                            '<i class="icon-tag"></i> Precio: '+price+' BsF.<br>'+
                            '<i class="icon-off "></i> Estatus: '+status+'<br>'+
                            '<i class="icon-th"></i> Cantidad displonible: '+_quantity+'<br>'+
                            '<i class="icon-calendar"></i> Publicado: '+published+
                        '</div>'+
                    '</div>'+
                    '<div style="display:none;"><!--'+JSON.stringify(obj)+'--></div>'+
                '</div>';

};


/*
 * Type: función
 * Descrición: 
 * Parametros: 
 *************************************************************************************************************************************************************/
var information_panel = function(){

    order_by();
    pagination();
    search();
    info();

    //se muestra el contenedor de los filtros
    $("#information-panel").css({"display":""});

};




var prepare_publications = function(){

    if(_var.data.length > 0){

        // se establece la variable que almacenara las publicaciones
        var products	= '';

        $.each(_var.data,function(index){

            // se prepara las publicaciones
            products += prepare_product(this);

            /* START  ha finalizado el bucle - este código se ejecuta una sola vez
             *************************************************************************/
            if(_var.current==(index+1)){

                information_panel();

                // se establece las publicaciones en el dom
                $('#published').html(products);

                /* se llama a los observadores de eventos para procesar solicitudes relacionadas.
                 *************************************************************************************/
                pause();
                activate();
                edit();
                _delete();


            }
            // END

        });

    }

};

// TODO ELIMINAR
/*
 * Type: clase.
 * Descripción: destinada a hacer disponible a todas las funciones variables información que contiene la data básica.
 * Parámetros: obj
 *************************************************************************************************************************************************************/
var set_vars = function(obj){
    this.page           = obj.info.page;
    this.current        = parseInt(obj.info.current);
    this.count          = parseInt(obj.info.count);   // cantidad de publicaciones o registros
    this.prevPage       = obj.info.prevPage;
    this.nextPage       = obj.info.nextPage;
    this.pageCount      = obj.info.pageCount;
    this.data           = obj.data;
};






/*
 * Type: función.
 * Descripción: destinada a procesar una búsqueda sobre los registros o publicaciones.
 *************************************************************************************************************************************************************/
var search = function(){

    var new_search_obj = {
        "type":"post",
        "url":"/products_published",
        "data":{
            "custom":{}
        },
        "console_log":true,
        "callbacks":{
            "complete":function(response){

                var a	= response.responseText;
                var obj = $.parseJSON(a);
                // console.log(obj);

                if(obj.expired_session){
                    window.location = "/entrar";
                }

                if(obj.result){

                    // se establece la url
                    var url = str_replace(obj.search,' ','_');
                    window.location = "#buscar_"+url;

                    _var = {};
                    _var = new set_vars(obj);

                    order_by();
                    pagination();
                    info();

                    // si hay productos publicados.
                    if(obj.data.length > 0){

                        prepare_publications('after');

                        // se oculta el mensaje que informa la no existencias de publicaciones
                        $("#no-products-for-this-search").css({"display":"none"});

                        // se establece la información de la cantidad de registros encontrados.

                        var count = '';
                        if(obj.info.count > 1){
                            count = obj.info.count+' registros encontrados'
                        }else{
                            count = obj.info.count+' registro encontrado'
                        }

                        $("#product-quantity-for-this-search").html(count);

                        // se establece la información de lo que se busca
                        $("#for-this-search").html(obj.search);

                        // se muestra la información acerca de la busqueda
                        $("#products-for-this-search").css({"display":"inherit"});

                        // se muestran las publicaciones
                        $("#published").css({"display":"inherit"});

                    }else{

                        // se oculta el mensaje que informa que hay publicaciones
                        $("#products-for-this-search").css({"display":"none"});

                        // se muestra el mensaje que indica que no hay publicaciones
                        $("#no-for-this-search").html(obj.search);
                        $("#no-products-for-this-search").css({"display":"inherit"});

                        // se oculta las publicaciones
                        $("#published").css({"display":"none"});

                    }

                }else{
                    // hay un error en la solicitud.
                    window.location = "/cuenta";
                }

            }
        }
    };

    // validación:
    var new_search_validate_obj = {
        "submitHandler": function(){

            var request_this                = {};

            var search_string                   = $("#search").val();
            request_this.search             = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');
            new_search_obj.data.custom      = request_this;

            new Request(new_search_obj);

        },
        "rules":{
            "search":{
                "required":true,
                "maxlength":100,
                "noSpecialChars":true
            }
        },
        "messages":{
            "search":{
                "required":"Es preciso definir el campo para proceder con la búsqueda.",
                "maxlength":"Hay un límite de 100 caracteres.",
                "noSpecialChars":"No esta permitido usar caracteres especiales."
            }
        }
    };

    new validate_this_form("SearchPublicationsForm",new_search_validate_obj);
    $("#searching").css({"display":""});


};

var parse = {
  'url':function(){

      /*
       * Type: function
       * Descripción: destinada a procesar la url
       * retorna un objeto.
       *******************************************/

      // Posibles urls
      // /publicados
      // /publicados#pagina_1
      // /publicados#mayor_precio/pagina_1
      // /publicados#buscar_las_mejores/mayor_precio/pagina_1

      var pathname = $(location).attr('href');
      var url = $.url(pathname);
      var segments = url.attr('fragment');

      var url_obj         = {};
      url_obj.search      = "";
      url_obj.page        = "";
      url_obj.order_by    = "";

      if(segments != ""){
          var split_segments = url.attr('fragment').split('/');
          if(split_segments.length){
              $(split_segments).each(function(index,parameter){

                  if(parameter.indexOf("buscar_") !== -1){
                      var search_string = str_replace(parameter,'buscar_','');

                      /* La cadena search_string se manipula en el siguiente orden.
                       *
                       * 1) se reemplaza los caracteres especiales
                       * 2) se elimina los espacios en blancos ante y después de la cadena
                       * 3) se reemplaza los espacios en blancos largos por uno solo.
                       *
                       ********************************************************************/
                      url_obj.search = search_string.replace(/[^a-zA-Z0-9]/g,' ').trim().replace(/\s{2,}/g, ' ');

                      //console.log(url_obj.search);

                  }
                  if(parameter.indexOf("pagina_") !== -1){
                      url_obj.page = parseInt(str_replace(parameter,'pagina_',''));
                  }
                  if(parameter == "mayor_precio"){
                      url_obj.order_by = "mayor_precio";
                  }
                  if(parameter == "menor_precio"){
                      url_obj.order_by = "menor_precio";
                  }
                  if(parameter == "recientes"){
                      url_obj.order_by = "recientes";
                  }
                  if(parameter == "antiguos"){
                      url_obj.order_by = "antiguos";
                  }
                  if(parameter == "mayor_disponibilidad"){
                      url_obj.order_by = "mayor_disponibilidad";
                  }
                  if(parameter == "menor_disponibilidad"){
                      url_obj.order_by = "menor_disponibilidad";
                  }


              });
          }
      }

      return url_obj;
  }
};

var products = {
    'response':{},
    'init':function(){

        var products = {
            "type":"post",
            "url":"/products_published",
            "data":{
                "custom": parse.url()
            },
            "console_log":false,
            "callbacks":{
                "complete":function(response){


                    var obj = $.parseJSON(response.responseText);
                    products.response = obj;

                    if(products.response.expired_session){
                        window.location = "/entrar";
                    }

                    if(products.response.result){

                        if(obj.total_products.length == 0){
                            // no hay publicaciones.
                            $("#no-products").css({"display":""});
                        }

                        if(obj.data.length > 0){
                            prepare_publications();
                        }

                        if(obj.data.length == 0 && obj.total_products > 0){
                            window.location = "/publicados";
                        }


                    }else{
                        // hay un error en la solicitud.
                        window.location = "/cuenta";
                    }
                }
            }
        };

        new Request(products);


    }
};

$(document).ready(function(){

    var products_published_obj = {
        "type":"post",
        "url":"/products_published",
        "data":{
            "custom": parse.url()
        },
        "console_log":false,
        "callbacks":{
            "complete":function(response){

                // hacer algo que almacene el resultado de la respuesta, y algo que al recivir un parametro como 'expired_session',

                var obj = $.parseJSON(response.responseText);
//                console.log(obj);

                if(obj.expired_session){
                    window.location = "/entrar";
                }

                if(obj.result){

                    if(obj.total_products.length == 0){
                        // no hay publicaciones.
                        $("#no-products").css({"display":""});
                    }

                    _var = {};
                    _var = new set_vars(obj);

                    if(obj.data.length > 0){
                        prepare_publications();
                    }

                    if(obj.data.length == 0 && obj.total_products > 0){
                        window.location = "/publicados";
                    }


                }else{
                    // hay un error en la solicitud.
                    window.location = "/cuenta";
                }
            }
        }
    };

    new Request(products_published_obj);

});
// TODO NEW CODE

//  Javascript Namespace Declaration: http://stackoverflow.com/questions/881515/javascript-namespace-declaration
//  http://appendto.com/2010/10/how-good-c-habits-can-encourage-bad-javascript-habits-part-1/
//
//(function( skillet, $, undefined ) {
//    //Private Property
//    var isHot = true;
//
//    //Public Property
//    skillet.ingredient = "Bacon Strips";
//
//    //Public Method
//    skillet.fry = function() {
//        var oliveOil;
//
//        addItem( "\t\n Butter \n\t" );
//        addItem( oliveOil );
//        console.log( "Frying " + skillet.ingredient );
//    };
//
//    //Private Method
//    function addItem( item ) {
//        if ( item !== undefined ) {
//            console.log( "Adding " + $.trim(item) );
//        }
//    }
//}( window.skillet = window.skillet || {}, jQuery ));


// AjaxRequest
(function( ajax, $, undefined ) {

    //Private Method
    function getFormData(parameters){
        /*
         obj = {};

         var input_ids = [{"id":"a","name":"a"},{"id":"b","name":"b"},{"id":"c","name":"c"}];

         $.each(input_ids,function(index,input){
         obj[input.name] = $('#'+input.id).val();
         });

         console.log(obj)
         */

        var data = {};
        $.each(parameters.data.form.inputs,function(index,input){
            data[input.name] = $('#'+input.id).val();
        });
        return data;
    }

    //Private Method
    function request(parameters){
        var ajax_request_parameters = {
            "type": parameters.type,
            "url": parameters.url,
            "contentType": "application/json; charset=UTF-8",
            "dataType": 'json',
            "data": JSON.stringify(parameters.data),
            "global": false,
            "beforeSend":function(){
                parameters.callbacks.beforeSend();
            },
            "success":function(response){
                parameters.callbacks.success(response);
            },
            "error":function(response){
                parameters.callbacks.error(response);
            },
            "complete":function(response){
                parameters.callbacks.complete(response);
            }
        };

        $.ajax(ajax_request_parameters);
    }

    //Public Method
    ajax.request = function(parameters){
        if(parameters !== undefined){
            if(parameters.requestType == 'form'){
                parameters.data = getFormData(parameters);
                request(parameters);
            }
            if(parameters.requestType == "custom"){
                request(parameters);
            }
        }
    };

}( window.ajax = window.ajax || {}, jQuery ));

// FormValidation
(function( validate, $) {

    //Public Property
    validate.validatorObject = {};

    // Public Method
    validate.form = function(formId,options){

        options.errorPlacement = function(error, element){
            $(element).parents('.control-group').find(".help-inline").fadeIn().html($(error).html());
        };

        options.success = function(label){
        };

        options.highlight = function(element){
            $(validation_states).each(function(k2,state){
                if($(element).parents('.control-group').hasClass(state)){
                    $(element).parents('.control-group').removeClass(state);
                }
            });
            $(element).parents('.control-group').addClass('warning');
        };

        options.unhighlight = function(element){
            $(validation_states).each(function(k2,state){
                if($(element).parents('.control-group').hasClass(state)){
                    $(element).parents('.control-group').removeClass(state);
                }
            });
            $(element).parents('.control-group').addClass('success');
        };

        validate.validatorObject = $("#"+formId).validate(options);

    };


}( window.validate = window.validate || {}, jQuery ));

// TODO DELETE THIS CODE AFTER REFACTOR ALL CODE


/*

 Validation States
 Se popula en span.help-inline - ya que varia segun el campo.
 warning	->  Antes y Depues de la solicitud ajax, Antes: si es requerido, si es correcto. Despues: si es requerido, si es correcto. Antes: validadción en el cliente, Depues: Validación en el servidor.
 info	->  no es usado en los formularios porque puede ser confundido con success
 Se muestra - ya que varia segun el formulario - Estos estados se activan luego de una solicitud al servidor.
 success	->  Al guardar correctamente.
 $("#"+form_id).parent(".modal-body").children(".alert-success");
 error	->  Al intentar guardar y alla ocurrido un error inesperado en el lado del servidor.
 $("#"+form_id).parent(".modal-body").children(".alert-error")1;

 */
var validation_states = ['warning','info','success','error'];

/*
 * Type: function
 * Descripción: Esta logica se asegura de recetear el formulario a su estado original.
 * Parametros:
 * 	form_id: el id del formulario.
 * */
var reset_form = function(form_id){
    $('#'+form_id)[0].reset();
    var inputs = $("#"+form_id+" input");
    $(inputs).each(function(k,element){
        $(validation_states).each(function(k2,state){
            var element = $("#"+element.id);
            if(element.parents('.control-group').hasClass(state)){
                element.parents('.control-group').removeClass(state);
                element.parents('.control-group').find(".help-inline").fadeOut();
            }
        });
    });
};

/*
 * Type: class
 * Descrición: Usada para procesar la solicitud del usuario, llamar la solicitud ajax
 * Parametros:
 * 	config_obj: objeto con todos los datos requeridos para procesar la solicitud
 * */
var Request = function(config_obj){

    var obj = {};

    if(config_obj.data.form){
        obj = {};
        $.each(config_obj.data.form.inputs,function(k,db_field){
            obj[k] = $('#'+db_field.id).val();
        });
        new AjaxRequest(config_obj,obj);
    }
    if(config_obj.data.custom){
        obj = config_obj.data.custom;
        new AjaxRequest(config_obj,obj);
    }

};








/*
 * Type: class
 * Descrición: Usada para ejecutar la solicitud ajax, las funciones o retrollamadas que se ejecuntan durante al solicitud ajax son referenciadas desde config_obj o el objeto de configuración.
 * Parametros:
 * 	config_obj: un objeto con los datos de configurados para procesar la solicitud, pero no el objeto que sera enviado en la solicitud.
 *  obj: el objeto que sera enviado en la solicitud.
 *
 * */
var AjaxRequest = function(config_obj,obj){

    var set_obj = {};

    if(config_obj.console_log){

        set_obj ={
            type: config_obj.type,
            url: config_obj.url,
            data: obj,
            global: false,
            complete: function(response){
                $('#debug').text(response.responseText);
                config_obj.callbacks.complete(response);
            }
        };

        $.ajax(set_obj);
    }else{

        set_obj ={
            type: config_obj.type,
            url: config_obj.url,
            data: obj,
            global: false,
            complete: function(response){
                config_obj.callbacks.complete(response);
            }
        };

        $.ajax(set_obj);
    }
};


var matches = function(value){
    if(value === ''){
        $("#accordion-menu").css({"display":"inherit"});
        $("#search_results").css({"display":"none"});
    }else{
        var accordion_menu = $("#accordion-menu");
        accordion_menu.css({"display":"none"});

        var lis = '';
        accordion_menu.find("a.link").each(function(k,element){
            var classs	=	$(element).attr("class");
            var title	=	$(element).attr("title");
            var href	=	$(element).attr("href");

            var i_title 	=	$.trim(title).toLowerCase();
            if(i_title.search(value)  >= 0){
                lis += '<li><div class="menu-item"><a href="'+href+'" class="'+classs+'"  title="'+title+'"  ">'+title+'</a></div></li>';
            }
        });

        // insertar los li en el dom
        var search_results = $("#search_results");
        search_results.find('ul').html(lis);

        // mostramos el menu
        search_results.css({"display":"inherit"});
    }
};

var display_status_request = function(form_id,parent_id,obj){
    if(obj.validates){
        if(obj.save){
            $("#"+parent_id+" .alert-success").fadeIn();
            setTimeout(function(){ $("#"+parent_id+" .alert-success").fadeOut(); }, 7000);
            return true;
        }else{
            $("#"+parent_id+" .alert-error").fadeIn();
            setTimeout(function(){ $("#"+parent_id+" .alert-error").fadeOut(); }, 7000);
            return false;
        }
    }else{
        // para manejar los errores que dispara el servidor, en caso de que la validación en el servidor sea mas granulada y en la vista no sea ha procurado prevenir tal error.
        $.each(obj.fields,function(element_id,msn){

            var element = $("#"+element_id);

            $(validation_states).each(function(k2,state){
                if(element.parents('.control-group').hasClass(state)){
                    element.parents('.control-group').removeClass(state);
                }
            });
            element.parents(".control-group").addClass("warning");
            element.parents('.control-group').find(".help-inline").fadeIn().html(msn);
        });
        return false;
    }
};

var validate_this_form = function(forn_id,options){

    options.errorPlacement = function(error, element){
        $(element).parents('.control-group').find(".help-inline").fadeIn().html($(error).html());
    };

    options.success = function(label){
    };

    options.highlight = function(element){
        $(validation_states).each(function(k2,state){
            if($(element).parents('.control-group').hasClass(state)){
                $(element).parents('.control-group').removeClass(state);
            }
        });
        $(element).parents('.control-group').addClass('warning');
    };

    options.unhighlight = function(element){
        $(validation_states).each(function(k2,state){
            if($(element).parents('.control-group').hasClass(state)){
                $(element).parents('.control-group').removeClass(state);
            }
        });
        $(element).parents('.control-group').addClass('success');
    };

    this._validate = $("#"+forn_id).validate(options);

};

var capitaliseFirstLetter = function(string){ return string.charAt(0).toUpperCase() + string.slice(1); }

var str_replace = function(string, change_this, for_this) {
    return string.split(change_this).join(for_this);
};

var random_number = function(inferior,superior){
    var numPosibilidades = superior - inferior;
    var aleat = Math.random() * numPosibilidades;
    aleat = Math.round(aleat);
    return parseInt(inferior) + aleat;
};

var clean_obj = function(data){
    var face_1 = str_replace(data,'<!--','');
    return str_replace(face_1,'-->','');
};

$.validator.addMethod("noSpecialChars", function(value, element) {
    return this.optional(element) || /^[a-z0-9\_\x20]+$/i.test(value);
}, "Username must contain only letters, numbers, or underscore.");

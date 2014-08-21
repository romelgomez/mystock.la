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
        $.each(parameters['form']['inputs'],function(index,input){
            data[input['name']] = $('#'+input['id']).val();
        });
        return data;
    }

    //Private Method
    function request(parameters){
        var ajax_request_parameters = {
            "type": parameters['type'],
            "url": parameters['url'],
            "contentType": "application/json; charset=UTF-8",
            "dataType": 'json',
            "data": JSON.stringify(parameters['data']),
            "global": false,
            "beforeSend":function(){
                parameters['callbacks']['beforeSend']();
            },
            "success":function(response){
                parameters['callbacks']['success'](response);
            },
            "error":function(response){
                parameters['callbacks']['error'](response);
            },
            "complete":function(response){
                parameters['callbacks']['complete'](response);
            }
        };

        $.ajax(ajax_request_parameters);
    }

    //Public Method
    ajax.request = function(parameters){
        if(parameters !== undefined){
            if(parameters['requestType'] == 'form'){
                parameters['data'] = getFormData(parameters);
                request(parameters);
            }
            if(parameters['requestType'] == "custom"){
                request(parameters);
            }
        }
    };

}( window.ajax = window.ajax || {}, jQuery ));


//<form role="form" id="SearchPublicationsForm">
//    <div class="form-group" style="margin-bottom: 0;">
//        <div class="input-group">
//            <input type="text" class="form-control" id="search" name="search" placeholder="Eje: Laptops">
//                <span class="input-group-btn">
//                    <button class="btn btn-default" type="submit">Buscar</button>
//                </span>
//            </div>
//        </div>
//    </form>

//<div class="form-group has-error has-feedback">
//    <label class="control-label" for="inputError2">Input with error</label>
//    <input type="text" class="form-control" id="inputError2">
//        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
//    </div>


// FormValidation
(function( validate, $) {

    //Private Property
    var validationStates = ['has-success','has-warning','has-error'];

    //Public Property
    validate.validatorObject = {};

    // Public Method
    validate.inlineForm = function(formId,options){

        options.errorPlacement = function(error, element){};

        options.success = function(label){};

        options.highlight = function(element){
            $(validationStates).each(function(k2,state){
                if($(element).parents('.form-group').hasClass(state)){
                    $(element).parents('.form-group').removeClass(state);
                }
            });
            $(element).parents('.form-group').addClass('has-warning');
        };

        options.unhighlight = function(element){
            $(validationStates).each(function(k2,state){
                if($(element).parents('.form-group').hasClass(state)){
                    $(element).parents('.form-group').removeClass(state);
                }
            });
            $(element).parents('.form-group').addClass('has-success');
        };

        validate.validatorObject = $("#"+formId).validate(options);

    };

    // Public Method
    validate.form = function(formId,options){

        options.errorPlacement = function(error, element){
            $(element).parents('.form-group').find(".help-block").fadeIn().html($(error).html());
        };

        options.success = function(label){
        };

        options.highlight = function(element){
            $(validationStates).each(function(k2,state){
                if($(element).parents('.form-group').hasClass(state)){
                    $(element).parents('.form-group').removeClass(state);
                }
            });
            $(element).parents('.form-group').addClass('has-warning');
        };

        options.unhighlight = function(element){
            $(validationStates).each(function(k2,state){
                if($(element).parents('.form-group').hasClass(state)){
                    $(element).parents('.form-group').removeClass(state);
                }
            });
            $(element).parents('.form-group').addClass('has-success');
        };

        validate.validatorObject = $("#"+formId).validate(options);

    };


    /*
     Public Method
     Descripción: Esta lógica se asegura de de resetear y remover los estados de validación del formulario
     Parámetros:
     form_id: el id del formulario.
    */
    validate.removeValidationStates = function(formId){
        var form = $('#'+formId);
        form[0].reset();
        var inputs = form.find('input');
        inputs.each(function(inputKey,_input_){
            var input = $(_input_);
            $(validationStates).each(function(stateKey,state){
                if(input.parents('.form-group').hasClass(state)){
                    input.parents('.form-group').removeClass(state);
                    input.parents('.form-group').find(".help-block").fadeOut();
                }
            });
        });
    };



}( window.validate = window.validate || {}, jQuery ));

(function( base, $, undefined ) {

    base.randomNumber = function(inferior,superior){
        var numPosibilidades = superior - inferior;
        var aleatory = Math.random() * numPosibilidades;
        aleatory = Math.round(aleatory);
        return parseInt(inferior) + aleatory;
    };

}( window.base = window.base || {}, jQuery ));


// TODO DELETE THIS CODE AFTER REFACTOR ALL CODE






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


var capitaliseFirstLetter = function(string){ return string.charAt(0).toUpperCase() + string.slice(1); }

var str_replace = function(string, change_this, for_this) {
    return string.split(change_this).join(for_this);
};

var clean_obj = function(data){
    var face_1 = str_replace(data,'<!--','');
    return str_replace(face_1,'-->','');
};

$.validator.addMethod("noSpecialChars", function(value, element) {
    return this.optional(element) || /^[a-z0-9\_\x20]+$/i.test(value);
}, "Username must contain only letters, numbers, or underscore.");

//(function( security, $, undefined ) {
//
//    //Private Method
//    function encrypt() {
//
//        var notification;
//
//        var request_parameters = {
//            "requestType":"custom",
//            "type":"post",
//            "url":"/encrypt",
//            "data":{},
//            "callbacks":{
//                "beforeSend":function(){
//                    notification = ajax.notification("beforeSend");
//                },
//                "success":function(response){
//                        $('#debug').text(JSON.stringify(response));
//                },
//                "error":function(){
//                    ajax.notification("error",notification);
//                },
//                "complete":function(){
//                    ajax.notification("complete",notification);
//                }
//            }
//        };
//
//        $("#encrypt").click(function(){
//            ajax.request(request_parameters);
//        })
//
//
//    }
//
//    //Public Method
//    security.main = function() {
//
//        encrypt()
//
//    };
//
//}( window.security = window.security || {}, jQuery ));
//
//security.main();


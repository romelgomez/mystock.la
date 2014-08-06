$(document).ready(function(){

	/* Descripción: fitra las opciones del menu principal al tiempo que usuario escribe en el campo.
	 ***********************************************************************************************/
	$("#search").on('keyup',function(event){
		var value = $.trim($(this).val().toLowerCase()).toLowerCase();
		matches(value);
	});
	if($.trim($("#search").val())){
		matches($.trim($("#search").val()).toLowerCase());
	}

	/* Descripción: deplega la categorias generales al posicionar el puntero del mouse sobre un departamento del menu principal.	 
	 ***************************************************************************************************************************/
	/*
	if($(".master-link")){
		$(".master-link").click(function(event){
			var hide_this = $("#accordion-menu .in").attr("id");
			
			
			
			if(hide_this){
				console.log(hide_this);
				if(hide_this != show_this){
					$('#'+hide_this).collapse('toggle').on('hidden', function () {
						$('#'+show_this).collapse('toggle');
					});
				}
			}else{
				$('#'+show_this).collapse('toggle');	
			}
			
		});	
	}
	*/
	
	
	if($(".master-link").length){
		$(".master-link").each(function(){
			
			$(this).mouseover(function(event){
				var show_this = $(this).attr("accordion-id");

				$("#accordion-menu .in").each(function(){
					var hide_this =  $(this).attr("id");
					if(hide_this != show_this){
						$('#'+hide_this).collapse('hide');
					}
				})			
				
				if($("#accordion-menu .in").length == 0){
					$('#'+show_this).collapse('show');
				}
							
			});
			
		});
	}
	
	
});

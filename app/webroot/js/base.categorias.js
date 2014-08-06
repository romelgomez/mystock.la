	/*	Cofiguración base.
	 ***********************************************/
	var tree = $('#display_tree');

	var set_tree = function(tree,data){
		options = {
			dragAndDrop: true,
			selectable: true,
			autoEscape: false,
			autoOpen: true 
		}
		options.data = data,
		tree.tree(options);	
	}
	var data = $.parseJSON($('#json_tree').html());
	
	if(data != null){
		$('#tree').fadeIn();
		set_tree(tree,data);
	}else{
		$('#no-tree').fadeIn();
	}
	
	

	/*	Modificar la posición de las cartegorias.
	 ***********************************************/
	/* 
		url		/edit_category_position
	*/
	var edit_position_obj = {
		"type":"post",
		"url":"/edit_category_position",
		"data":{
			"custon":{}
		},
		"console_log":true,
		"callbacks":{
			"complete":function(response){
				
				var a = response.responseText;
				//console.log(a);
				var obj = $.parseJSON(a);
				
				if(obj.expired_session){
					window.location = "/cuenta";
				}
				
				var data = obj.categories;
				set_tree(tree,data);
				
			}
		}
	}
	
	tree.bind(
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
			console.log('position',position);					//-> posicion: sobre, luego, internamente. 
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
			
			edit_position_obj.data.custon = request_this;
			new Request(edit_position_obj);
			
		}
	);
	
	var move_to = function(moved_node,target_node,position){
		var request = {};
	
		var a = parseInt(moved_node.lft);
		var b = parseInt(target_node.lft);
										
		if(a < b){
			//console.log('bajar');
			request.move_to = 'moveDown';
			request.min = parseInt(moved_node.rght)+1; 	
			request.max = parseInt(target_node.rght);
			return request;			
		}
		if(a > b){
			//console.log('subir');
			request.move_to = 'moveUp';	
			if(position == "before"){
				request.min = parseInt(target_node.lft);
				request.max = parseInt(moved_node.lft)-1;
			}
			if(position == "inside"){
				request.min = parseInt(target_node.lft)+1;
				request.max = parseInt(moved_node.lft)-1;				
			}
			if(position == "after"){
				request.min = parseInt(target_node.rght)+1;
				request.max = parseInt(moved_node.lft)-1;				
			}
			return request;
		}
	}	
	
	
	/*	Disponer de los datos de la categoria selecionada para editar (nombre) o borrar el nodo.
	 ***********************************************/
	/* 
		element_id	#category_selected
	*/		
	tree.bind(
		'tree.select',
		function(event) {
			var node = event.node;
			
			// console.log(node);

            //  EDIT
			$("#EditCategoryId").attr({"value":node.id})
			$("#EditCategoryName").attr({"value":node.name})
	
			//  Delect
			$("#DelectCategoryId").attr({"value":node.id})
			$("#delect-category-name").html(node.name)
			
			if(node.children.length){
				//console.log(node.children.length);	
				$("#DelectCategoryBranch").fadeIn();
			}else{
				$("#DelectCategoryBranch").fadeOut();
			}

            // Abilita los botones.
			$("#admin_category a").each(function(k,element){
				$(element).removeClass("disabled");
			});
		
		}
	);
	


/*	Nueva Categoria
 ****************************/
/* 

	link 		#new_category
	modal 		#new_category_modal
	form_id		#CategoryAddForm
	url			/new_category
	
	inputs
	* CategoryName
*/


// modal
$(".new_category").on('click',function(event){
	event.preventDefault();
	$('#new_category_modal').modal({"backdrop":false,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
		reset_form('CategoryAddForm');
		new_category._validate.resetForm();
	});
});


var new_category_obj = {
	"type":"post",
		"url":"/new_category",
		"data":{
			"form":{
				"id":"CategoryAddForm",
				"inputs":{
					"name":{
						"id":"CategoryName"
					}
				}
			}
		},
	"console_log":true,
	"callbacks":{
		"complete":function(response){
			var a	= response.responseText;
			var obj = $.parseJSON(a);
			
			if(obj.expired_session){
				window.location = "/cuenta";
			}			
			var status = display_status_request("CategoryAddForm","new_category_modal",obj);	
			if(status){
				new_category._validate.resetForm();
				reset_form("CategoryAddForm");
				if(obj.length){
					$('#no-tree').css({"display":"none"});
					$('#tree').fadeIn();
					var data = obj.categories;		
					set_tree(tree,data);
				}
			}
		
		}
	}
}

// validación:
var new_category_validate_obj = {
	"submitHandler": function(form) {
		new Request(new_category_obj);
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
			"maxlength":"El nombre de la categoria no debe tener mas de 50 caracteres."
		}
	}
}

var new_category = new validate_this_form("CategoryAddForm",new_category_validate_obj);


/*	Editar el nombre de la cartegoria.
 ***********************************************/
/* 
	link 		#edit_category_name
	modal 		#edit_category_name_modal
	form_id		#CategoryEditForm
	url			/edit_category_name
	
	inputs
	* EditCategoryId
	* EditCategoryName
	
*/

$("#edit_category_name").on('click',function(event){
	event.preventDefault();	
	if(!$(this).hasClass("disabled")){
		// Activamos el modal
		$('#edit_category_name_modal').modal({"backdrop":true,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
			edit_category_name._validate.resetForm();
			reset_form("CategoryEditForm");
		});
	}	
});

var edit_category_name_obj = {
	"type":"post",
		"url":"/edit_category_name",
		"data":{
			"form":{
				"id":"CategoryEditForm",
				"inputs":{
					"id":{
						"id":"EditCategoryId"
					},
					"name":{
						"id":"EditCategoryName"
					}
				}
			}
		},
	"console_log":true,
	"callbacks":{
		"complete":function(response){
			
			var a = response.responseText;
			//console.log(a);
			
			var obj = $.parseJSON(a);
			if(obj.expired_session){
				window.location = "/cuenta";
			}
			
			if(obj.save){
							
				$("#EditCategoryName").attr({"value":obj.Category.name});
				
				if(obj.length){
					var data = obj.categories;		
					set_tree(tree,data);
				}
				
				edit_category_name._validate.resetForm();
				reset_form("CategoryEditForm");
				
				$('#edit_category_name_modal').modal('hide');
			}
			
			
			
		}
	}
}

// validación:
var edit_category_name_validate_obj = {
	"submitHandler": function(form) {
		new Request(edit_category_name_obj);
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
			"maxlength":"El nombre de la categoria no debe tener mas de 20 caracteres."
		}
	}
}

var edit_category_name = new validate_this_form("CategoryEditForm",edit_category_name_validate_obj);

/*	Borrar cartegorias.
 ***********************************************/
/* 

	link 		#delect_category
	modal 		#delect_category_modal
	form_id		#CategoryDelectForm
	url			/delect_category

	inputs
	* DelectCategoryId
	* DelectCategoryBranch  //-> Existira en caso de que la categoria tenga hijos, si es verdadero se eliminan recusivamente.
	
	info
	* delect-category-name

*/

$("#delect_category").on('click',function(event){
	event.preventDefault();
	if(!$(this).hasClass("disabled")){
		// Activamos el modal
		$('#delect_category_modal').modal({"backdrop":true,"keyboard":true,"show":true,"remote":false}).on('hidden',function(){
			
			$("#DelectCategoryBranch").removeClass("active");
			
		});
	}
});

var delect_category_obj = {
	"type":"post",
	"url":"/delect_category",
	"data":{
		"custon":{}
	},
	"console_log":true,
	"callbacks":{
		"complete":function(response){
			
			var a = response.responseText;
			var obj = $.parseJSON(a);
			
			if(obj.expired_session){
				window.location = "/cuenta";
			}
			
			if(obj.length){
				var data = obj.categories;		
				set_tree(tree,data);
			}else{
				$('#tree').css({"display":"none"});
				$('#no-tree').fadeIn();
			}
			
			$("#admin_category a").each(function(k,element){
				$(element).addClass("disabled");
			});
			
			$('#delect_category_modal').modal('hide');
			
		}
	}
}

// validación:
var delect_category_validate_obj = {
	"submitHandler": function(form) {
		delect_category_obj.data.custon.id 				= $("#DelectCategoryId").val();

		if($("#DelectCategoryBranch").hasClass("active")){
			delect_category_obj.data.custon.theWholeBranch = 1;
		}else{
			delect_category_obj.data.custon.theWholeBranch = 0;
		}
		
		new Request(delect_category_obj);
	}
}

var delect_category = new validate_this_form("CategoryDelectForm",delect_category_validate_obj);


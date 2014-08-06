<?php
class Category extends AppModel {

	public $displayField = 'name';
	public $actsAs = array('Tree','Containable');

	// label ha sido sustituido por name, para mayor compativilidad y mejor manejor con la libreria jqTree.

	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'El campo nombre es obligatorio.'
			)
		)
	);

	var $hasMany = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'category_id',
		)
	);	
	
}
?>

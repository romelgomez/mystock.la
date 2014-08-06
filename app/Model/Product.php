<?php
class Product extends AppModel{
	
	public $displayField = 'title';
	public $actsAs = array('Containable');

	public $validate = array(
		'category_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'error',
			)
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Indica un titulo.',
			)
		),
		'body' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Describe el producto',
			)
		),
		'price' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Indica el precio.',
			),
			'numeric' => array(
				'rule'    => 'numeric',
				'message' => 'Solo numeros.'
			)
		),
		'quantity' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Falta',
			),
			'numeric' => array(
				'rule'    => 'numeric',
				'message' => 'Solo numeros.'
			)
		),
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'product_id',
			'conditions' =>array('Image.parent_id'=>null,'Image.status'=>1)
		)
	);

}
?>

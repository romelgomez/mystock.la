<?php
class Product extends AppModel{

    public $displayField = 'title';
    public $actsAs = array('Containable');

    public $validate = array(
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
                'message' => 'Only numbers'
            )
        ),
        'quantity' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Falta',
            ),
            'numeric' => array(
                'rule'    => 'numeric',
                'message' => 'Only numbers'
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
        )
    );

    public $hasMany = array(
        'Image' => array(
            'className' => 'Image',
            'foreignKey' => 'product_id',
            'conditions' =>array('Image.size'=>'small','Image.deleted'=>0)
        )
    );

}
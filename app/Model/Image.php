<?php
class Image extends AppModel {

    public $displayField = 'name';
    public $actsAs = array('Containable');

    public $belongsTo = array(
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'product_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
<?php
class User extends AppModel {

	public $displayField = 'name';
	public $actsAs = array('Containable');



		/* Validar - en el servidor "s" y en la vista "v".
		 * 
		 * -- name --
		 * 		
		 * v[+] y 	s[+]	- el nombre es un campo requerido
		 * v[+] y 	s[+] 	- el nombre solo debe contener caracteres alfabeticos 
		 * v[+]	y	s[+]	- el nombre no debe tener mas de 20 caracteres.
		 * v[+]	y	s[+]	- el nombre debe tener minimo 3 caracteres.
		 * 
		 * -- email --
		 * 
		 * v[+] y 	s[+]	- el correo es un campo requerido
		 * v[+] y 	s[+]	- el correo este correcto
		 * v[+]	y	s[+]	- el correo no esta registrado con anterioridad
		 * v[+]	y	s[+]	- el correo no debe tener mas de 30 caracteres. 
		 * 
		 * -- password --
		 * 
		 * v[+] y 	s[+]	- la clave es un campo requerido.
		 * v[+]	y 	s[]		- la clave y el nombre no deben ser iguales.
		 * v[+]	y 	s[]		- la clave y el correo no deben ser iguales.
		 * v[+]	y 			- en el cliente, la clave tenga un minino de 7 caracteres y un maximo de 21 , UTF-8. En el servidor siempre sera 40 caracteres independientemente de los que introdusca el usuario.
		 * 
		 * ***************************************************************************/

/*

	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'El campo nombre es obligatorio.',
			),
			'lettersOnly' => array(
				'rule' => '/^[a-z]+$/i',
				'message' => 'El nombre debe tener solo caracteres alfabeticos.',
			),
			'maxLength' => array(
				'rule'    => array('minLength', 3),
				'message' => 'El nombre debe tener almenos 3 caracteres.',
			),
			'maxLength' => array(
				'rule'    => array('maxLength', 20),
				'message' => 'El nombre no debe tener mas de 20 caracteres.',
			)
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'El campo correo es obligatorio.'
			),
			'email' => array(
				'rule'    => array('email'),
				'message' => 'Debe proporcionar un correo valido.'
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Ya esta registrado. Intente recuperar la cuenta.'
			),
			'maxLength' => array(
				'rule'    => array('maxLength', 30),
				'message' => 'El correo no debe tener mas de 30 caracteres.',
			)
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'El campo contraceña es obligatorio.',
			)
		)
	);

	*/

	/*
	public  $hasMany = array(
				'Product' => array(
					'className' => 'Product',
					'foreignKey' => 'user_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
				)
			);
	*/

}
?>


<?php
class Card extends AppModel
{
	public $name = 'Card';

	//public $hasOne = 'Client';
	
	public $belongsTo = 'Client';
	
	public $validate = array(
	    'number' => array(
	        'CreditCard' => array(
	            'rule' => array('cc', array('visa', 'maestro', 'mc', 'amex', 'switch', 'electron'), true, null),
	            'message' => 'The credit card number you supplied was invalid.'
	        ),
	    ),
		/*'name' => array(
			'NameOnCard' => array(
					'required' => true,
					'message' => 'Name is required.'
			),
		),*/
		'security_code' => array(
			'CVV2' => array(
					'rule' => 'numeric',
					'message' => 'CVV must be numbers'
			),
			'MinLength' => array(
					'rule' => array('minLength', 3),
					'message' => 'CVV min length must be 3'
			),
			'MaxLength' => array(
					'rule' => array('maxLength', 3),
					'message' => 'CVV max length must be 3'
			),
		)
	);

}

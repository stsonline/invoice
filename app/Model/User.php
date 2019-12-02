<?php
class User extends AppModel {
	
	var $name = 'User';
	
	var $hasOne = 'Client';
	
	
	public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            ),
        	'rule'=>'isUnique',
        	'message'=>'This username is already taken! Please try another.'
        ),
        'password' => array(
            'required' => array(
            	'rule'    => array('minLength', '6'),
            	'message' => 'Minimum 6 characters long'
            )
        ),

    );
	
	public function beforeSave($options = array()) 
	{
		if (isset($this->data[$this->alias]['password'])) 
		{
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
}
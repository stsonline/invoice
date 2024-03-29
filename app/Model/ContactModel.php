<?php
class Contact extends AppModel {
 
    public $name = 'Contact';
 
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'message' => 'You have not entered your name.'
        ),
        'email' => array(
            'rule' => 'email',
            'message' => 'You have entered an invalid e-mail address.'
        ),
        'message' => array(
            'rule' => 'notEmpty',
            'message' => 'You did not enter a message.'
        )
    );
}
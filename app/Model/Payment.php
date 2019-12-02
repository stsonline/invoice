<?php
class Payment extends AppModel
{	
	public $name = 'Payment';
	
	public $belongsTo = array('Invoice','Client');
}
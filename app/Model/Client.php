<?php
class Client extends AppModel
{
	public $name = 'Client';
	
	public $helpers = array('Html', 'Form');

	public $hasMany = array('Invoice', 'RecurringInvoice', 'Card', 'Project');
	
	public $belongsTo = 'User';
		
	public $validate = array(
			//'email' => 'isUnique'
	);
}
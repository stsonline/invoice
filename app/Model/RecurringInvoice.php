<?php
class RecurringInvoice extends AppModel
{
	public $name = 'RecurringInvoice';

	public $helpers = array('Html', 'Form');
	
	public $belongsTo = array('Client','Project');

	public $hasMany = array('RecurringLineItem');

}
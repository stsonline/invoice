<?php
class Project extends AppModel
{
	public $name = 'Project';

	public $helpers = array('Html', 'Form');

	public $belongsTo = 'Client';
	
	public $hasMany = array('Invoice','RecurringInvoice');


}
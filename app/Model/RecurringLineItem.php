<?php
class RecurringLineItem extends Model
{
	public $name = 'RecurringLineItem';
	
	public $belongsTo = array('RecurringInvoice');
}
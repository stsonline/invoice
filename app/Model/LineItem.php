<?php
class LineItem extends Model
{
	public $name = 'LineItem';
	
	public $belongsTo = array('Invoice');
}
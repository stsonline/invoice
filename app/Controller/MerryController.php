<?php
class MerryController extends AppController 
{
	public $name = 'Merry';
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'MerryLayout';
	}
	
	public function index()
	{
		
	}
	
}
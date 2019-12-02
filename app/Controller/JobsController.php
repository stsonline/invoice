<?php
class JobsController extends AppController 
{
	public $name = 'Jobs';

	//No Linked Model
	public $uses = array('Job');
	
	//Uses IndexLayout
	public $layout = 'IndexLayout';
	
	public function index()
	{
		//Create and populate array($job_items) with database entries
		$job_items = $this->Job->find('all');
		$this->set('job_items',$job_items);
	}
}
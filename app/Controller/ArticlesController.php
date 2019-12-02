<?php
class ArticlesController extends AppController 
{
	public $name = 'Articles';

	//No linked Model
	public $uses = array();
	
	//Uses IndexLayout
	public $layout = 'IndexLayout';
	
	public function index()
	{
		//Create and populate array($job_items) with database entries
		$article_items = $this->Article->find('all');
		$this->set('article_items',$article_items);
	}
	
}
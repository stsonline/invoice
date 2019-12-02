<?php
class IndexController extends AppController
{
	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Index';

	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array('Showcase', "Post");

	//public $layout = 'LandingPageLayout';

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'LandingPageLayout';
	}

	public function index()
	{
		$this->set('posts', $this->Post->find('all', array("conditions" => array("Post.created <=" => date("Y-m-d H:i:s")), "order" => array("Post.created" => "DESC"))));
	}

}

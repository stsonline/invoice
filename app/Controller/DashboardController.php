<?php
App::uses('AdminLayoutController', 'Controller');

class DashboardController extends AdminLayoutController
{
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
	}

	public function index()
	{
		

	}
}

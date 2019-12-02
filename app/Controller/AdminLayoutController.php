<?php

class AdminLayoutController extends AppController
{
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
		
		$this->layout = 'AdminLayout';
		
		
		$title = $this->action == 'index' ? $this->name : ucfirst($this->action);
		
		$title = $title == 'Index' ? 'Home' : $title;
		
		$title = str_replace('_', ' ', $title);
		
		$this->set('title_for_layout',$title.' - Icing CMS');
		
	}
	
	public function isAuthorized($user)
	{
		// All registered users can add
		return parent::isAuthorized($user);
	}
}

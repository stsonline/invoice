<?php

App::uses('AdminLayoutController', 'Controller');

class ProjectsController extends AdminLayoutController {
		
	public $helpers = array('Html', 'Form','Paginator');
	
	public $uses = array('Invoice','Settings', 'Client', 'User','Project');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
	}

	public function isAuthorized($user)
	{
		// All registered users can add
		return parent::isAuthorized($user);
	}

	public function index()
	{
		$this->set('title_for_layout','Projects');
		$this->layout = 'InvoiceHistory';
		
		$user = $this->Session->read('Auth.User');
		
		if($user['Client']['id'] !== null)
		{
			$projects = $this->Project->find('all',array('conditions'=>array('Project.client_id'=>$user['Client']['id'])));		
				
			$this->set('projects',$projects);
		}
		else
		{
			$this->redirect(array('controller' => 'index','action' => 'index'));
		}
	}

	public function view($project_id = null)
	{
		$this->set('title_for_layout','Projects');
		$this->layout = 'InvoiceHistory';
		
		$user = $this->Session->read('Auth.User');
		
		if($user['Client']['id'] !== null)
		{
			$project = $this->Project->find('first',array('conditions'=>array('Project.id'=>$project_id)));
			
			$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
				
			foreach($project['Invoice'] as $key => $invoice)
			{
				if($invoice['paid'] == 1)
				{
					$project['Invoice'][$key]['pay_link'] = '(Paid)';
				}
				else
				{
					$project['Invoice'][$key]['pay_link'] = '<a href="'.$this->base.'/invoices/pay/'.$invoice['id'].'/'.sha1($invoice['id'].$secret_phrase).'" target="_blank">Pay now</a>';
				}
			}
			$this->set('project',$project);
		}
		else
		{
			$this->redirect(array('controller' => 'index','action' => 'index'));
		}
	}

}

<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('AdminLayoutController', 'Controller');

class ClientsController extends AdminLayoutController {
	
	public $name = 'Client';
			
	public $helpers = array('Html', 'Form','Paginator');
	
	public  $components = array('Security');

	public $paginate = array( 
			'limit' => 25,
			'order' => array(
					'balance' => 'DESC'
			),
			'recursive' => -1,
	);

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
		$this->Client->virtualFields['balance'] = 'SUM(Invoice.total - Invoice.credit)';

		$this->paginate = array(
			//'conditions' => array('Client.id' => "`Invoice`.`client_id`"),
		    'joins' => array(
		        array(
		            'alias' => 'Invoice',
		            'table' => 'invoices',
		            'type' => 'LEFT',
		            'conditions' => '`Client`.`id` = `Invoice`.`client_id`'
		        )
		    ),
		    'group' => 'Client.id',
		    'order' => array(
					'balance' => 'DESC'
			),
		);

		

		$clientlist = array();
		if(isset($_GET['search']))
		{

			$clientlist = $this->paginate("Client", array("OR" => 
				array('Client.id LIKE' => "%".$_GET['search']."%",
					'Client.firstname LIKE' => "%".$_GET['search']."%",
					'Client.lastname LIKE' => "%".$_GET['search']."%",
					'Client.email LIKE' => "%".$_GET['search']."%",
					'Client.organisation LIKE' => "%".$_GET['search']."%"
					)
			));
		}
		else
		{
			$clientlist = $this->paginate("Client");
		}


		/*foreach($clientlist as $i=>$client)
		{
			$clientlist[$i]['Client']['balance'] = $i;
		}*/
		//print_r($clientlist);die;
		$this->set('clients',$clientlist);
	}
	
	public function view($cl_id=null)
	{	
		$this->set('a_client',$this->Client->find('first',array('conditions' => array('Client.id' => $cl_id))));
	}
	
	function mailtest()
	{
		$headers = 'From: webmaster@wagelender.net';
		$m1 = mail('sts.richardmarsden@gmail.com', 'Test email using PHP', 'This is a test email message', $headers, '-fwebmaster@wagelender.net');

		$to = 'sts.richardmarsden@gmail.com';
		$subject = 'Test email using PHP';
		$message = 'This is a test email message';
		$headers = 'From: webmaster@wagelender.net' . "\r\n" .
		           'Reply-To: webmaster@wagelender.net' . "\r\n" .
		           'X-Mailer: PHP/' . phpversion();
		$m2 = mail($to, $subject, $message, $headers, '-fwebmaster@wagelender.net');
		$this->Session->setFlash('Now check your mail 1:'.$m1.' 2:'.$m2);
		$this->redirect(array('action' => 'index'));
	}
	
	function add()
	{
		if ($this->request->is('post'))
		{
			if($this->Client->isUnique(array('Client.email'=>$this->data['Client']['email'])))
			{
				//there is data posted
				$this->Client->create();

				if ($this->Client->save($this->request->data))
				{
					$this->Session->setFlash('New Client has been saved.');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Session->setFlash('Unable to add Client.');
				}
			}
			else
			{
				$this->Session->setFlash('Unable to add new Client. Name already exists in database');
			}
		}

	}

	public function edit($id = null)
	{
		$this->Client->id = $id;

		if ($this->request->is('get'))
		{
			$this->request->data = $this->Client->read();
		}
		else
		{
			if($this->Client->isUnique(array('Client.name'=>$this->data['Client']['name'])))
			{
				if ($this->Client->save($this->request->data))
				{
					$this->Session->setFlash('Client record has been updated.');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Session->setFlash('Error - Unable to update Client record.');
				}
			}
			else
			{				
				$this->Session->setFlash('Unable to save Client. Name already exists in database');
			}
		}
	}

	public function onscreen($msg)
	{
		if(isset($msg))
		{
			//$message=unserialize($msg);
			$message = $this->params->params['pass'];
			$this->set('message',$message);
		}
		else
		{
			//$this->Session->setFlash('Sorry,problem with message');
		}
	
	}	
	public function view_layout($Client_id)
	{
		if(!empty($Client_id))
		{
			$Client = $this->Client->find('first',array('conditions' => array('Client.id' => $Client_id)));
			
			if(isset($Client['Client']['name']))
			{
				$this->layoutPath = 'Emails/html';
				$filename = ROOT.'/app/View/Layouts/Emails/html/'.$Client['Client']['name'].'.ctp';
				if( file_exists($filename) ) //if layout file exists for Client name
				{
					$this->layout = $Client['Client']['name'];
				}
				else
				{
					$this->layout = 'default';
				}
			}
		}
		
	}	
}

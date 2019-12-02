<?php

App::uses('AdminLayoutController', 'Controller');

class SystemController extends AdminLayoutController {
	
	public $uses = array('Newsletter', 'Job', 'Role', 'User', 'Client', 'Payment', 'Invoice');
	//public  $components = array('Security');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
	}

	public function index()
	{
		$this->redirect('settings');
	}
	
	public function newsletters($id = null, $operation = null)
	{
		$fieldlist = array
		(
			'display'=>array('id', 'title', 'seo_name', 'disabled'),
			'edit'=>array('seo_name', 'title',  'intro', 'content', 'disabled'),
		);
		
		$this->modelEditor( 'Newsletter', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function roles($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'name'),
				'edit'=>array('name'),
		);
	
		$this->modelEditor( 'Role', $id , $operation, $fieldlist, $debug=false);
	}
	
	
	public function jobs($id = null, $operation = null)
	{
		$fieldlist = array
		(
			'display'=>array('id', 'title'),
			'edit'=>array('title', 'description'),
		);
		
		$this->modelEditor( 'Job', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function routes($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'seo_name', 'route_config', 'disabled'),
				'edit'=>array('seo_name', 'route_config', 'disabled'),
		);
	
		$this->modelEditor( 'Route', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function invoices($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'client_id', 'project_id','payment_id', 'pdf', 'total','credit', 'sent', 'sends'),
				'edit'=>array('project_id', 'pdf', 'total','credit', 'paid','allow_cc','allow_paypal','allow_bacs'),
		);
	
		$this->modelEditor( 'Invoice', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function clients($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'firstname', 'lastname', 'email', 'organisation', 'user_id'),
				'edit'=>array('firstname', 'lastname', 'address1', 'address2', 'city', 'state', 'zipcode', 'country',  'email', 'phone', 'organisation', 'org_alias', 'default_display_currency', 'charge_vat'),
		);
			
		$this->modelEditor( 'Client', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function projects($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'client_id', 'name', 'description'),
				'edit'=>array('id', 'client_id', 'name', 'description'),
		);
	
		$this->modelEditor( 'Project', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function currencys($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'code', 'base_exchange_rate', 'symbol'),
				'edit'=>array('id', 'code', 'base_exchange_rate', 'symbol'),
		);
	
		$this->modelEditor( 'Currency', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function users($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'username', 'role_id'),
				'edit'=>array('username', 'password', 'role_id'),
		);
		$this->set('roles', $this->Role->find('list')); //set roles into view, as model editor won't be able to find them
		$this->modelEditor( 'User', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function payments($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'client_id', 'invoice_id', 'amount', 'type','status', 'date'),
				'edit'=>array('client_id', 'invoice_id', 'amount', 'type', 'processor_response'),
		);
		$this->set('invoices', $this->Invoice->find('list')); //set payments into view, as model editor won't be able to find them
		$this->set('clients', $this->Client->find('list')); //set payments into view, as model editor won't be able to find them
		$this->modelEditor( 'Payment', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function showcases($id = null, $operation = null)
	{
		$fieldlist = array
		(
			'display'=>array('id', 'title', 'seo_name', 'disabled', 'featured'),
			'edit'=>array('seo_name', 'title',  'subtitle', 'description', 'description2', 'description3', 'image_url', 'pdf_url', 'disabled', 'featured'),
		);
		
		$this->modelEditor( 'Showcase', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function pages($id = null, $operation = null)
	{
		$fieldlist = array
		(
				'display'=>array('id', 'title', 'seo_name', 'disabled', 'featured'),
				'edit'=>array('seo_name', 'title',  'metas', 'body', 'disabled', 'featured'),
		);
	
		$this->modelEditor( 'Page', $id , $operation, $fieldlist, $debug=false);
	}
	
	public function testimonials($id = null, $operation = null)
	{
		$fieldlist = array
		(
			'display'=>array('id', 'text', 'from', 'disabled', 'featured'),
			'edit'=>array('text', 'from', 'disabled', 'featured'),
		);
		
		$this->modelEditor( 'Testimonial', $id , $operation, $fieldlist, $debug=false);
	}
	
	
	public function settings()
	{
		if ($this->request->is('post'))
		{
			
			
			
			foreach($this->request->data['Setting']['key'] as $key=>$value)
			{
				$setting = $this->Setting->find('first', array(
			        'conditions' => array('Setting.key' => $key)
			    ));
				
				$setting['Setting']['value'] = $value;
				$this->Setting->save($setting);
			}
			
			
//			$this->Setting->save($settings);
			
			return true;
		}
	}
	public function sitemaps()
	{
		if ($this->request->is('post'))
		{
			switch($this->request->data['operation'])
			{
				case 'generate':
					$this->set('sitemapGeneration',$this->generateSitemap());
					break;
				case 'submit':
					$this->set('sitemapSubmission',$this->submitSitemap());
					break;
			}
			
			
		}
	}
	
	
	
		
}

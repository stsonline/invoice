<?php
App::uses('AdminLayoutController', 'Controller');
class InvoicesController extends AdminLayoutController 
{		
	//public $components = array('Security');
	
	public $uses = array('Invoice','Payment','Response','Settings','paid_before', 'Card', 'Client', 'User','Project','InvoicePayment', 'RecurringInvoice', 'LineItem', 'RecurringLineItem', 'Currency');
	
	public $helpers = array('Html','Form');

	public $paginate = array(
			'Invoice' => array(
					'limit' => 25,
					'recursive' => 0,
					'order' => array(
							'Invoice.paid' => 'asc',
							'Invoice.schedule_for' => 'desc',
							'Invoice.id' => 'desc'
					)
			),
			'RecurringInvoice' => array(
					'limit' => 25,
					'recursive' => 0,
					'order' => array(
							'RecurringInvoice.id' => 'desc'
					)
			)
	);
	
	private $monthName = array("","January","February","March","April","May","June","July","August","September","October","November","December");
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow('pay','no_invoice_id','payment_process','payment_success','payment_fail','paid_before','paypal_ipn_listener','epdq_listener','epdq','worldpay','worldpay_listener','process_recurring','process_invoices');//clients can access to make payments & crontab can curl to certain actions.
		
		$email_types = array(
				'None',
				'Invoice',
				'Reminder',
				'Final Demand',
				'Already Paid',
				'Recurring Payment Agreed',
				'Service Suspension'
		);
		$this->set('email_types',$email_types);
	}
		
	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}
	
	public function view($inv_id=null)
	{
		$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));
		if( empty($an_invoice))
		{
			$this->redirect(array('action' => 'no_invoice_id'));
		}
		$this->set('invoice',$an_invoice);
	
		$this->layout = 'ajax';
	}
	
	public function index($client_id=null)
	{
		$invoicelist = $this->Invoice->find('all',array('conditions' =>array('Invoice.client_id' => $client_id), 'order'=>'Invoice.id desc'));
		
		$client = $this->Invoice->Client->find('first',array('conditions' => array('Client.id' => $client_id)));
		
		$this->set('client',$client);
		$this->set('invoices', $invoicelist);
	}
	
	public function list_all()
	{
		$invoicelist = array();
		if(isset($_GET['search']))
		{

			$invoicelist = $this->paginate("Invoice", array("OR" => 
				array('Invoice.id LIKE' => "%".$_GET['search']."%",
					'Client.id LIKE' => "%".$_GET['search']."%",
					'Client.firstname LIKE' => "%".$_GET['search']."%",
					'Client.lastname LIKE' => "%".$_GET['search']."%",
					'Client.organisation LIKE' => "%".$_GET['search']."%"
					)
			));
		}
		else
		{
			$invoicelist = $this->paginate('Invoice');
		}
		$storedMonth = 0;
		foreach($invoicelist as $key=>$invoice)
		{
			$created = $invoice['Invoice']['schedule_for'];
			$dtime = DateTime::createFromFormat("Y-m-d G:i:s", $created);
			$month = date('n', $dtime->getTimestamp());
			$invoicelist[$key]['Invoice']['new_month'] = 0;
			if ($month != $storedMonth || !isset($storedMonth))
			{
				$storedMonth = $month;
				$invoicelist[$key]['Invoice']['new_month'] = 1;
			}
		}
		$this->set('invoices', $invoicelist);
		$this->set('unpaid_total',$this->getUnpaidInvoiceTotal());
	}

	public function list_unpaid()
	{
		$this->paginate = array(
			'Invoice' => array(
					'conditions' =>array('Invoice.paid' => 0),
					'limit' => 25,
					'recursive' => 0,
					'order' => array(
							'Invoice.schedule_for' => 'desc',
							'Invoice.id' => 'desc'
					)
			)
		);
		
		$invoicelist = $this->paginate('Invoice');
		$storedMonth = 0;
		foreach($invoicelist as $key=>$invoice)
		{
			$created = $invoice['Invoice']['schedule_for'];
			$dtime = DateTime::createFromFormat("Y-m-d G:i:s", $created);
			$month = date('n', $dtime->getTimestamp());
			$invoicelist[$key]['Invoice']['new_month'] = 0;
			if ($month != $storedMonth || !isset($storedMonth))
			{
				$storedMonth = $month;
				$invoicelist[$key]['Invoice']['new_month'] = 1;
			}
		}
		$this->set('invoices', $invoicelist);
		$this->set('unpaid_total',$this->getUnpaidInvoiceTotal());
	}

	public function data_table_all()
	{
		$invoicelist = $this->Invoice->find('all');
		$this->set('invoices', $invoicelist);
	
		$this->layout='datatable';
	}
	public function add($client_id=null)
	{
		if ($this->request->is('post'))
		{
			$new_invoice = $this->request->data;	//'InvoiceUpload' is the name of the form
			
			if($new_invoice['Invoice']['client_id'] == 'new') {
				// Create a new client
				$new_client_user_id = null;
				
				if($new_invoice['Client']['create_user']) {
					// Create a new user
					$new_user = $this->User->create();
					$now = date("Y-m-d H:i:s");
					
					$new_user['User']['username'] = $new_invoice['Client']['email'];
					$new_user['User']['password'] = $new_invoice['User']['password'];
					$new_user['User']['role_id'] = 4;
					$new_user['User']['created'] = $now;
					$new_user['User']['modified'] = $now;
										
					if($this->User->save($new_user)) {
						$new_client_user_id = $this->User->id;
					}
					else {
						$error_message = null;
						foreach($this->User->validationErrors as $errors) {
							$error_message = $errors[0];
							break;
						}
						if(!isset($error_message)) {
							$error_message = "Error adding user. Please try again.";
						}
						$this->Session->setFlash($error_message);
						$this->redirect(array('action' => 'add'));
					}
				}
				
				$new_client = $this->Client->create();
				
				$new_client['Client'] = $new_invoice['Client'];
				$new_client['Client']['user_id'] = $new_client_user_id;
				
				if($this->Client->save($new_client)) {
					$new_invoice['Invoice']['client_id'] = $this->Client->id;
				}
				else {
					$this->Session->setFlash("Error adding client. Please try again.");
					$this->redirect(array('action' => 'add'));
				}
			}
			
			if($new_invoice['Invoice']['recurring']) {
				$issueWithDate = false;
				$day = intval($new_invoice['Invoice']['day']);
				$month = isset($new_invoice['Invoice']['month']) ? intval($new_invoice['Invoice']['month']) : null;
				
				if($new_invoice['Invoice']['type'] == 0 && ($day < 1 || $day > 28)) {
					$issueWithDate = true;
				}
				
				if(isset($month) && $new_invoice['Invoice']['type'] == 1 && ($month < 1 || $month > 12 || !$this->validateDate(date("Y").'-'.$month.'-'.$day,"Y-n-j"))) {
					$issueWithDate = true;
				}
				
				if($issueWithDate) {
					$this->Session->setFlash('Error with dates. Please try again.');
					$this->redirect(array('action' => 'add'));
				}
				
				$start_date = explode('/',$new_invoice['Invoice']['start_date']);
				if(count($start_date) < 3 || !checkdate($start_date[1],$start_date[0],$start_date[2])) {
					$start_date = null;
				} else {
					$start_date = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
				}
				
				$period = "";
				
				switch($new_invoice['Invoice']['type']) {
					case 0:
						// Monthly
						$date = $this->ordinal($day)." of the month.";
						$new_invoice['Invoice']['month'] = null;
						$period = $new_invoice['Invoice']['period_monthly'];
						break;
					case 1:
						// Yearly
						$date = $this->ordinal($day)." of ".$this->monthName[$month]." every year";
						$period = $new_invoice['Invoice']['period_yearly'];
						break;
				}
				
				$an_invoice = $this->RecurringInvoice->create();
				
				$an_invoice['RecurringInvoice'] = $new_invoice['Invoice'];
				$an_invoice['RecurringInvoice']['created'] = date('Y-m-d H:i:s');
				$an_invoice['RecurringInvoice']['project_id'] = $new_invoice['Invoice']['project'] == 'none' ? null : $new_invoice['Invoice']['project'];
				$an_invoice['RecurringInvoice']['period'] = $period;
				$an_invoice['RecurringInvoice']['start_date'] = $start_date;
				
				foreach($new_invoice['LineItem'] as $line_item)
				{
					$an_invoice['RecurringLineItem'][] = $line_item;
				}
				
				$this->RecurringInvoice->saveAll($an_invoice);
				$this->Session->setFlash('New Recurring Invoice has been saved. Invoicing will occur on the '.$date);
				$this->redirect(array('action' => 'index',$new_invoice['Invoice']['client_id']));
			}
			else {
				if($new_invoice['Invoice']['pdf_source'] == 'upload')
				{
					$an_invoice = $this->Invoice->create();
						
					$an_invoice['Invoice'] = $new_invoice['Invoice'];
					$an_invoice['Invoice']['pdf'] = $new_invoice['Invoice']['pdf']['name'];
					$an_invoice['Invoice']['created'] = date('Y-m-d H:i:s');
					$an_invoice['Invoice']['project_id'] = $new_invoice['Invoice']['project'] == 'none' ? null : $new_invoice['Invoice']['project'];
				
					$schedule_for = explode('/',$an_invoice['Invoice']['schedule_for']);
					$an_invoice['Invoice']['schedule_for'] = date("Y-m-d 00:00:00",strtotime($schedule_for[2].'-'.$schedule_for[1].'-'.$schedule_for[0]));
				
					if ($this->Invoice->saveAll($an_invoice))
					{
						//Invoice name becomes 'STS' followed by the invoice number, the old name is only retained for information
						//Doesn't have to be done this way, the name could be retained and accessed via the invoice record
						//(but need to be careful about duplicate pdf name)
				
						//could mask the name  'STS' .$this->mask_id( $this->Invoice->id).'.pdf' for aesthetic reasons
						//then unmask when accessing invoice record
						$my_path = WWW_ROOT . 'files' . DS . 'pdf_invoices' . DS . 'STS' . $this->Invoice->id.'.pdf';
						//move pdf file from 'old name.pdf' to 'new name.pdf' on the server
						$test = move_uploaded_file($new_invoice['Invoice']['pdf']['tmp_name'], $my_path );
				
						$this->Invoice->save($an_invoice);
						$this->Session->setFlash('New Invoice has been saved.');
						$this->redirect(array('action' => 'index',$new_invoice['Invoice']['client_id']));
					}
					else
					{
						$this->Session->setFlash('Unable to add Invoice.');
					}
				}
				else if($new_invoice['Invoice']['pdf_source'] == 'build')
				{
					$an_invoice = $this->Invoice->create();
						
					$an_invoice['Invoice'] = $new_invoice['Invoice'];
					$an_invoice['Invoice']['created'] = date('Y-m-d H:i:s');
					$an_invoice['Invoice']['project_id'] = $new_invoice['Invoice']['project'] == 'none' ? null : $new_invoice['Invoice']['project'];
					$an_invoice['Invoice']['pdf'] = '';
				
					$schedule_for = explode('/',$an_invoice['Invoice']['schedule_for']);
					if(count($schedule_for) < 3)
					{
						$schedule_for[0] = date("d");
						$schedule_for[1] = date("m");
						$schedule_for[2] = date("Y");
					}
					$an_invoice['Invoice']['schedule_for'] = date("Y-m-d 00:00:00",strtotime($schedule_for[2].'-'.$schedule_for[1].'-'.$schedule_for[0]));
				
					foreach($new_invoice['LineItem'] as $line_item)
					{
						$date = explode('/',$line_item['date']);
						if(count($date) < 3)
						{
							$date[0] = date("d");
							$date[1] = date("m");
							$date[2] = date("Y");
						}
						$line_item['date'] = $date[2].'-'.$date[1].'-'.$date[0];
							
						$an_invoice['LineItem'][] = $line_item;
					}
				
					if ($this->Invoice->saveAll($an_invoice))
					{
						$invoice_id = $this->Invoice->id;
						$an_invoice['Invoice']['id'] = $invoice_id;
						$an_invoice['Invoice']['pdf'] = 'STS'.$invoice_id.'.pdf';
							
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							
						$pdf->setCreator(PDF_CREATOR);
						$pdf->SetAuthor(PDF_AUTHOR);
						$pdf->SetTitle('STS Online Invoice');
						$pdf->SetSubject('Invoice #'.$invoice_id);
						$pdf->SetKeywords('STS,Online,Invoice');
							
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							
						$pdf->SetAutoPageBreak(false);
							
						$pdf->SetPrintHeader(false);
						$pdf->SetPrintFooter(false);
							
						$pdf->AddPage();
							
						$invoice_client = $this->Client->findById($an_invoice['Invoice']['client_id']);
							
						$view = new View($this,false);
						$view->viewPath = 'Invoice';
						$view->layout = false;
						$view->set('invoice',$an_invoice);
						$view->set('client',$invoice_client['Client']);
						$view->set('currencies',$this->currencies);
						$view->set('settings',$this->_settings);
							
						$invoice_as_html = $view->render('invoice');
							
						$pdf->writeHTML($invoice_as_html, true, false, true, false, '');
							
						$pdf->Output(WWW_ROOT . 'files' . DS . 'pdf_invoices' . DS . $an_invoice['Invoice']['pdf'],'F');
							
						$this->Invoice->save($an_invoice);
						$this->Session->setFlash('New Invoice has been saved.');
						$this->redirect(array('action' => 'index',$new_invoice['Invoice']['client_id']));
					}
					else
					{
						$this->Session->setFlash('Unable to add Invoice.');
					}
				
				}
				else
				{
					$this->Session->setFlash('Unable to add Invoice.');
				}
			}
			
		}
		
		if(isset($client_id))
		{
			$client = $this->Invoice->Client->find('first',array('conditions' => array('Client.id' => $client_id)));
		}
		else
		{
			$client = false;
		}
		
		$all_clients = $this->Client->find('all', array('order'=>"organisation"));
		
		$clientlist = array();
		$clientdata = array();
		
		/*$clientlist['new'] = 'New client';
		$clientdata['new']['tax'] = false;
		$clientdata['new']['exchange_rate'] = $this->currencies[$this->_settings['system.base_currency']]['base_exchange_rate'];
		$clientdata['new']['symbol'] = $this->currencies[$this->_settings['system.base_currency']]['symbol'];*/
		
		foreach($all_clients as $a_client)
		{
			$clientlist[$a_client['Client']['id']] = $a_client['Client']['organisation'].' ('.$a_client['Client']['firstname'].' '.$a_client['Client']['lastname'].')';
			$clientdata[$a_client['Client']['id']]['tax'] = $a_client['Client']['charge_vat'] == 1 ? true : false;
			$clientdata[$a_client['Client']['id']]['exchange_rate'] = $this->currencies[$a_client['Client']['default_display_currency']]['base_exchange_rate'];
			$clientdata[$a_client['Client']['id']]['symbol'] = $this->currencies[$a_client['Client']['default_display_currency']]['symbol'];
		}
			
		$projects = $this->Project->find('all',array('conditions'=>array('Project.client_id' => $client['Client']['id'])));
		
		$base_currency = $this->currencies[$this->_settings['system.base_currency']];
		
		$currencieslist = array();
		
		foreach($this->currencies as $code => $currency) {
			$currencieslist[$code] = $code;
		}
		
		$days = array(1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th', 5 => '5th', 6 => '6th', 7 => '7th', 8 => '8th', 9 => '9th', 10 => '10th', 11 => '11th', 12 => '12th', 13 => '13th', 14 => '14th', 15 => '15th', 16 => '16th', 17 => '17th', 18 => '18th', 19 => '19th', 20 => '20th', 21 => '21st', 22 => '22nd', 23 => '23rd', 24 => '24th', 25 => '25th', 26 => '26th', 27 => '27th', 28 => '28th');
		$months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
		
		$this->set('days',$days);
		$this->set('months',$months);
		$this->set('client',$client);
		$this->set('clientlist',$clientlist);
		$this->set('clientdata',$clientdata);
		$this->set('base_currency',$base_currency);
		$this->set('currencieslist',$currencieslist);
		$this->set('tax_amount',$this->_settings['system.vat']);
		$this->set('projects',$projects);
	}
	
	public function delete($invoice_id = null)
	{
		$user = $this->Session->read('Auth.User');
		$is_admin = $user['role_id'] == 2 || $user['role_id'] == 3 ? true : false;
		
		if ($is_admin) {
			$invoice = $this->Invoice->findById($invoice_id);
				
			if (!empty($invoice)) {
				if ($this->Invoice->delete($invoice_id)) {
					$this->Session->setFlash("Invoice #$invoice_id deleted.");
				} else {
					$this->Session->setFlash("Error deleting invoice.");
				}
			} else {
				$this->Session->setFlash("No invoice found.");
			}
		}
		$this->redirect($this->referer());
	}
	
	public function mark_paid($invoice_id = null) {
	
		$user = $this->Session->read('Auth.User');
		$role_id = isset($user) ? $user['role_id'] : 1; //role 1 is user
		
		if($role_id == 2 || $role_id == 3) {
			$invoice = $this->Invoice->findById($invoice_id);
			
			if(!empty($invoice)) {
				$balance = round(floatval($invoice['Invoice']['total'])-floatval($invoice['Invoice']['credit']),2);
			
				//create a payment receipt
				$a_payment = $this->Payment->create();
				$a_payment['Payment']['invoice_id'] = $invoice['Invoice']['id'];
				$a_payment['Payment']['type'] = 'manual';
				$a_payment['Payment']['amount'] = $balance;
				$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
				$a_payment['Payment']['status'] = 'marked paid';
				$a_payment['Payment']['processor_response'] = 'Manual payment from invoices manager, logged in user was '.$user['username'];
				if($this->Payment->save($a_payment)) {
					//update invoice data, and save
					$invoice['Invoice']['credit'] = floatval($invoice['Invoice']['credit']) + $balance;
					$invoice['Invoice']['paid'] = true;
					if($this->Invoice->save($invoice)) {
						$this->Session->setFlash("Invoice #".$invoice['Invoice']['id']." marked as paid.");
					}
					else {
						$this->Session->setFlash("Error saving invoice.");
					}
				}
				else {
					$this->Session->setFlash("Error saving payment.");
				}
			}
			else {
				$this->Session->setFlash("No invoice found.");
			}
		}		
		$this->redirect($this->referer());
	}
	
	public function recurring_status($id = null,$status = 1) {
		$invoice = $this->RecurringInvoice->findById($id);
		$status = intval($status);
		
		if(!empty($invoice)) {
			if($status == 1) {
				$invoice['RecurringInvoice']['active'] = 1;
			}
			else {
				$invoice['RecurringInvoice']['active'] = 0;
			}
			
			$this->RecurringInvoice->save($invoice);
			$this->Session->setFlash('Recurring Invoice #'.$invoice['RecurringInvoice']['id'].' updated.');
		}
		else {
			$this->Session->setFlash('Error updating recurring invoice.');
		}
		$this->redirect($this->referer());
	}
	
	public function recurring($id = null) {
		if($this->request->is('post')) {
			$invoice = $this->RecurringInvoice->findById($id);
			
			if(!empty($invoice)) {
				
				$recurring_invoice['RecurringInvoice'] = $this->request->data['Invoice'];
				
				$issueWithDate = false;
				$day = intval($recurring_invoice['RecurringInvoice']['day']);
				$month = isset($recurring_invoice['RecurringInvoice']['month']) ? intval($recurring_invoice['RecurringInvoice']['month']) : null;
				
				if($recurring_invoice['RecurringInvoice']['type'] == 0 && ($day < 1 || $day > 28)) {
					$issueWithDate = true;
				}
				
				if(isset($month) && $recurring_invoice['RecurringInvoice']['type'] == 1 && ($month < 1 || $month > 12 || !$this->validateDate(date("Y").'-'.$month.'-'.$day,"Y-n-j"))) {
					$issueWithDate = true;
				}
				
				if($issueWithDate) {
					$this->Session->setFlash('Error with dates. Please try again.');
					$this->redirect(array('controller' => 'invoices', 'action' => 'recurring', $id));
				}
				
				$period = "";
				
				switch($recurring_invoice['RecurringInvoice']['type']) {
					case 0:
						// Monthly
						$date = $this->ordinal($day)." of the month.";
						$recurring_invoice['RecurringInvoice']['month'] = null;
						$period = $recurring_invoice['RecurringInvoice']['period_monthly'];
						break;
					case 1:
						// Yearly
						$date = $this->ordinal($day)." of ".$this->monthName[$month]." every year";
						$period = $recurring_invoice['RecurringInvoice']['period_yearly'];
						break;
				}
				
				$recurring_invoice['RecurringInvoice']['period'] = $period;
				
				$recurring_invoice['RecurringLineItem'] = $this->request->data['LineItem'];
				$recurring_invoice['RecurringInvoice']['id'] = $id;
				foreach($recurring_invoice['RecurringLineItem'] as $key => $line_item) {
					$recurring_invoice['RecurringLineItem'][$key]['recurring_invoice_id'] = $id;
				}
				
				$this->RecurringLineItem->deleteAll(array('RecurringLineItem.recurring_invoice_id' => $id));
				
				if($this->RecurringInvoice->saveAll($recurring_invoice)) {
					$this->Session->setFlash('Recurring invoice has been updated.');
				}
				else {
					$this->Session->setFlash('Error updating invoice.');
				}
			}
			else {
				$this->Session->setFlash('Could not find invoice.');
			}
			
		}
		
		$invoice = $this->RecurringInvoice->findById($id);
		
		if(!empty($invoice)) {
			$client['Client'] = $invoice['Client'];
			
			$clientdata[$client['Client']['id']]['tax'] = $client['Client']['charge_vat'] == 1 ? true : false;
			$clientdata[$client['Client']['id']]['exchange_rate'] = $this->currencies[$client['Client']['default_display_currency']]['base_exchange_rate'];
			$clientdata[$client['Client']['id']]['symbol'] = $this->currencies[$client['Client']['default_display_currency']]['symbol'];
		}
		else {
			$this->Session->setFlash("No recurring invoice found");
			$this->redirect(array('controller' => 'invoices','action' => 'list_recurring'));
		}
		
		$projects = $this->Project->find('all',array('conditions'=>array('Project.client_id' => $client['Client']['id'])));
		
		$base_currency = $this->currencies[$this->_settings['system.base_currency']];
		
		$days = array(1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th', 5 => '5th', 6 => '6th', 7 => '7th', 8 => '8th', 9 => '9th', 10 => '10th', 11 => '11th', 12 => '12th', 13 => '13th', 14 => '14th', 15 => '15th', 16 => '16th', 17 => '17th', 18 => '18th', 19 => '19th', 20 => '20th', 21 => '21st', 22 => '22nd', 23 => '23rd', 24 => '24th', 25 => '25th', 26 => '26th', 27 => '27th', 28 => '28th', 29 => '29th', 30 => '30th', 31 => '31st');
		$months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
		
		$this->set('invoice',$invoice);
		$this->set('days',$days);
		$this->set('months',$months);
		$this->set('client',$client);
		$this->set('clientdata',$clientdata);
		$this->set('base_currency',$base_currency);
		$this->set('tax_amount',$this->_settings['system.vat']);
		$this->set('projects',$projects);
	}
	
	public function delete_recurring($id = null) {
		$recurring_invoice = $this->RecurringInvoice->findById($id);
		
		$user = $this->Session->read('Auth.User');
		$role_id = isset($user) ? $user['role_id'] : 1; //role 1 is user
		
		$return = array('controller' => 'invoices', 'action' => 'list_recurring');
		
		if(!empty($recurring_invoice)) {
			if($role_id == 2 || $role_id == 3) {
				if($this->RecurringInvoice->delete($id)) {
					$message = "Recurring Invoice #$id deleted.";
				}
				else {
					$message = "Error deleting Recurring Invoice.";
				}
			}
			else {
				$message = "You do not have permission to delete this Recurring Invoice.";
			}
		}
		else {
			$message = "Unable to find Recurring Invoice.";
		}
		
		$this->Session->setFlash($message);
		$this->redirect($return);
	}
	
	/**
	 * Create Invoices from RecurringInvoices, will also send emails based on value of send_recurring_invoices_on_creation
	 * @param string $sendit must be 'sendit' or this will exit
	 */
	public function process_recurring($sendit = null) {
		if($sendit != "sendit") {
			exit;
		}
		
		$now = new DateTime();
		$day = $now->format("j");
		$month = $now->format("n");
		
		$recurring_invoices = $this->RecurringInvoice->find('all',array(
				'conditions' => array(
						'active' => 1,
						array("OR" => array(
								'RecurringInvoice.start_date <' => $now->format('Y-m-d H:i:s'),
								'RecurringInvoice.start_date' => null
						)),
						array("OR" => array(
								array("type" => 0, 'day' => $day),
								array("type" => 1, 'day' => $day, 'month' => $month)
						))
				)
		));
		
		$new_invoices = array();
		
		if(!empty($recurring_invoices)) {
			foreach($recurring_invoices as $recurring_invoice) {
				// Make sure we haven't created this invoice already
				$existing_invoice = $this->Invoice->find('first',array(
						'conditions' => array(
								'recurring_invoice_id' => $recurring_invoice['RecurringInvoice']['id'],
								'schedule_for LIKE' => $now->format('Y-m-d')."%",
						)
				));
				
				if(empty($existing_invoice)) {					
					$an_invoice = $this->Invoice->create();
					
					$an_invoice['Invoice']['client_id'] = $recurring_invoice['RecurringInvoice']['client_id'];
					$an_invoice['Invoice']['recurring_invoice_id'] = $recurring_invoice['RecurringInvoice']['id'];
					$an_invoice['Invoice']['project_id'] = $recurring_invoice['RecurringInvoice']['project_id'];
					$an_invoice['Invoice']['subtotal'] = $recurring_invoice['RecurringInvoice']['subtotal'];
					$an_invoice['Invoice']['vat'] = $recurring_invoice['RecurringInvoice']['vat'];
					$an_invoice['Invoice']['total'] = $recurring_invoice['RecurringInvoice']['total'];
					$an_invoice['Invoice']['terms'] = $recurring_invoice['RecurringInvoice']['terms'];
					$an_invoice['Invoice']['schedule_for'] = $now->format('Y-m-d H:i:s');
					$an_invoice['Invoice']['terms'] = $recurring_invoice['RecurringInvoice']['terms'];
					$an_invoice['Invoice']['terms'] = $recurring_invoice['RecurringInvoice']['terms'];
					$an_invoice['Invoice']['created'] = $now->format('Y-m-d H:i:s');
					$an_invoice['Invoice']['allow_cc'] = $recurring_invoice['RecurringInvoice']['allow_cc'];
					$an_invoice['Invoice']['allow_paypal'] = $recurring_invoice['RecurringInvoice']['allow_paypal'];
					$an_invoice['Invoice']['allow_bacs'] = $recurring_invoice['RecurringInvoice']['allow_bacs'];
					$an_invoice['Invoice']['default_to_recurring_payment'] = $recurring_invoice['RecurringInvoice']['default_to_recurring_payment'];
					$an_invoice['Invoice']['pdf'] = '';
					$an_invoice['Invoice']['period'] = $this->get_period($recurring_invoice['RecurringInvoice']['type'],$recurring_invoice['RecurringInvoice']['period']);
					
					foreach($recurring_invoice['RecurringLineItem'] as $line_item)
					{
						$line_item['date'] = $now->format('Y-m-d');
						$line_item['completed'] = "YES";
							
						$an_invoice['LineItem'][] = $line_item;
					}
					
					$message_no = 1;
						
					if($recurring_invoice['RecurringInvoice']['recurring_payment'] && !empty($recurring_invoice['RecurringInvoice']['futurepay_id'])) {
						$message_no = 5;
					}
					
					$invoice_credit = 0.00;
					
					if($recurring_invoice['RecurringInvoice']['overpayment_balance'] > 0) {
						if($recurring_invoice['RecurringInvoice']['overpayment_balance'] >= $an_invoice['Invoice']['total']) {
							// Balance will cover the invoice
							$invoice_credit = $an_invoice['Invoice']['credit'] = $an_invoice['Invoice']['total'];
							$an_invoice['Invoice']['paid'] = true;
							
							$message_no = 4;
							
							$recurring_invoice['RecurringInvoice']['overpayment_balance'] = $recurring_invoice['RecurringInvoice']['overpayment_balance'] - $an_invoice['Invoice']['total'];
						} else {
							// Balance won't cover the invoice - this is a edge case of an edge case and a human will probably need to get involved here.
							$invoice_credit = $an_invoice['Invoice']['credit'] = $recurring_invoice['RecurringInvoice']['overpayment_balance'];
							$recurring_invoice['RecurringInvoice']['overpayment_balance'] = 0.00;
						}
												
						$this->RecurringInvoice->save($recurring_invoice);
					}
					
					if ($this->Invoice->saveAll($an_invoice))
					{
						$invoice_id = $this->Invoice->id;
						
						if($invoice_credit > 0.00) {
							//create a payment receipt
							$a_payment = $this->Payment->create();
							$a_payment['Payment']['invoice_id'] = $invoice_id;
							$a_payment['Payment']['type'] = 'manual';
							$a_payment['Payment']['amount'] = $invoice_credit;
							$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
							$a_payment['Payment']['status'] = 'marked paid';
							$a_payment['Payment']['processor_response'] = 'Payment drawn from Recurring Invoice overpayment balance.';
							$this->Payment->save($a_payment);
						}
						
						$an_invoice['Invoice']['id'] = $invoice_id;
						$an_invoice['Invoice']['pdf'] = 'STS'.$invoice_id.'.pdf';
							
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							
						$pdf->setCreator(PDF_CREATOR);
						$pdf->SetAuthor(PDF_AUTHOR);
						$pdf->SetTitle('STS Online Invoice');
						$pdf->SetSubject('Invoice #'.$invoice_id);
						$pdf->SetKeywords('STS,Online,Invoice');
							
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							
						$pdf->SetAutoPageBreak(false);
							
						$pdf->SetPrintHeader(false);
						$pdf->SetPrintFooter(false);
							
						$pdf->AddPage();
							
						$invoice_client = $this->Client->findById($an_invoice['Invoice']['client_id']);
							
						$view = new View($this,false);
						$view->viewPath = 'Invoice';
						$view->layout = false;
						$view->set('invoice',$an_invoice);
						$view->set('client',$invoice_client['Client']);
						$view->set('currencies',$this->currencies);
						$view->set('settings',$this->_settings);
							
						$invoice_as_html = $view->render('invoice');
							
						$pdf->writeHTML($invoice_as_html, true, false, true, false, '');
							
						$pdf->Output(WWW_ROOT . 'files' . DS . 'pdf_invoices' . DS . $an_invoice['Invoice']['pdf'],'F');
							
						if($this->Invoice->save($an_invoice)) {
							echo date("Y-m-d H:m:s")." - Invoice #$invoice_id created from Recurring Invoice #".$recurring_invoice['RecurringInvoice']['id'].".\n";
							$new_invoices[] = array('id' => $invoice_id, 'message' => $message_no);
						}
						else {
							echo date("Y-m-d H:m:s")." - Error updated #$invoice_id during PDF creation.\n";
						}
						
					}
					else
					{
						echo date("Y-m-d H:m:s")." - Unable to add Invoice for Recurring Invoice #".$recurring_invoice['RecurringInvoice']['id']."\n";
					}
					
				}
				else {
					echo date("Y-m-d H:m:s")." - Recurring Invoice #".$recurring_invoice['RecurringInvoice']['id']." already has invoice: #".$existing_invoice['Invoice']['id'],".\n";
				}
			}
			
			$send_invoices = $this->_settings['send_recurring_invoices_on_creation'] == 'true';
						
			foreach($new_invoices as $new_invoice) {
				if($send_invoices /* Automatic invoice emailing is on */ || $new_invoice['message'] == 4 /* The invoice is covered by the overpayment balance */ || $new_invoice['message'] == 5 /* This invoice has a recurring payment agreement */) {
					try {
						$send = $this->send_invoice($new_invoice['id'],$new_invoice['message']);
						echo date("Y-m-d H:m:s")." - $send.\n";
					}
					catch (Exception $e) {
						echo date("Y-m-d H:m:s")." - ".$e->getMessage().".\n";
					}
				}
			}
			
			
		}
		else {
			echo date("Y-m-d H:m:s")." - No invoices to create.\n";
		}		
		exit;
	}
	
	public function list_recurring() {
		$invoicelist = $this->paginate('RecurringInvoice');
		foreach($invoicelist as $key => $invoice) {			
			switch($invoice['RecurringInvoice']['type']) {
				case 0:
					$type_string = 'Monthly';
					$date = $this->ordinal($invoice['RecurringInvoice']['day']);
					$period = "Month";
					break;
				case 1:
					$type_string = 'Yearly';
					$date = $this->ordinal($invoice['RecurringInvoice']['day']).' '.$this->monthName[$invoice['RecurringInvoice']['month']];
					$period = "Year";
					break;
				default:
					$period = $date = $type_string = '';
			}
			
			if($period != "") {
				switch($invoice['RecurringInvoice']['period']) {
					case '-1':
						$period = "Last ".$period;
						break;
					case '0':
						$period = "This ".$period;
						break;
					case '1':
						$period = "Next ".$period;
						break;
					default:
						$period = "";
				}
			}
			
			$invoicelist[$key]['RecurringInvoice']['type_string'] = $type_string;
			$invoicelist[$key]['RecurringInvoice']['date'] = $date;
			$invoicelist[$key]['RecurringInvoice']['period_string'] = $period;
		}
		
		$this->set('recurring_invoices', $invoicelist);
	}
	
	public function choose_reminder($invoice_id=null)
	{
		//send email with invoice and payment link
		$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$invoice_id))));
		$this->set('invoice',$an_invoice);
	}
	
	public function send($invoice_id=null,$message_no=1)
	{
		try {
			$send = $this->send_invoice($invoice_id,$message_no);
			$this->Session->setFlash($send);
		}
		catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
		}
		$this->redirect($this->referer());
	}
	
	public function set_auto_contact($id = null,$status = 1) {
		$invoice = $this->Invoice->findById($id);
		$status = intval($status);
		
		if(!empty($invoice)) {
			if($status == 1) {
				$invoice['Invoice']['allow_auto_contact'] = 1;
			}
			else {
				$invoice['Invoice']['allow_auto_contact'] = 0;
			}
			
			$this->Invoice->save($invoice);
			$this->Session->setFlash('Invoice #'.$invoice['Invoice']['id'].' updated.');
		}
		else {
			$this->Session->setFlash('Error updating recurring invoice.');
		}
		$this->redirect($this->referer());
	}
	
	public function send_unsent($sendit = null)
	{
		if ($sendit != "sendit") {
			exit;
		}
		
		$date = new DateTime();
		
		$first_round_invoices = $this->Invoice->find('all',array('conditions' => array('Invoice.schedule_for <' => $date->format('Y-m-d H:i:s'), 'Invoice.sends' => 0, 'Invoice.paid' => false)));
			
		$count = 0;
		$error = 0;
		
		if (!empty($first_round_invoices)) {
			foreach ($first_round_invoices as $invoice) {
				try {
					$send = $this->send_invoice($invoice['Invoice']['id'],1);
					$count++;
				}
				catch (Exception $e) {
					$error++;
				}
			}
			
			if ($error > 0) {
				$message = "$count invoices sent, but $error failed.";
			} else {
				$message = "$count invoices successfully sent!";
			}
		} else {
			$message = 'No unsent invoices found.';
		}
		
		$this->Session->setFlash($message);
		$this->redirect($this->referer());
	}
	
	public function process_invoices($sendit = null)
	{
		if ($sendit != "sendit") {
			exit;
		}
		
		if ($this->_settings['use_automated_invoice_sending'] == 'true') {
			$date = new DateTime();
			$one_day = new DateInterval("P1D");
			$two_days = new DateInterval("P2D");
			$seven_days = new DateInterval("P7D");
			
			echo date("Y-m-d H:m:s")." - Checking for unsent invoices...\n";
			
			$first_round_invoices = $this->Invoice->find('all',array('conditions' => array('Invoice.schedule_for <' => $date->format('Y-m-d H:i:s'), 'Invoice.sends' => 0, 'Invoice.paid' => false, 'Invoice.allow_auto_contact' => true)));
			
			if (!empty($first_round_invoices)) {
				foreach ($first_round_invoices as $invoice) {
					try {
						$send = $this->send_invoice($invoice['Invoice']['id'],1);
						echo date("Y-m-d H:m:s")." - $send.\n";
					}
					catch (Exception $e) {
						echo date("Y-m-d H:m:s")." - Error: ".$e->getMessage().".\n";
					}
				}
			} else {
				echo date("Y-m-d H:m:s")." - No invoices due for first send found.\n";
			}
			
			echo date("Y-m-d H:m:s")." - Checking for invoices that need a reminder...\n";
			
			$date->sub($one_day);
			$date->sub($seven_days);
			
			$second_round_invoices = $this->Invoice->find('all',array('conditions' => array('Invoice.schedule_for <' => $date->format('Y-m-d H:i:s'), 'Invoice.sent <' => $date->format('Y-m-d 23:59:59'), 'Invoice.last_send_type' => 1, 'Invoice.paid' => false, 'Invoice.allow_auto_contact' => true)));
			
			if (!empty($second_round_invoices)) {
				foreach ($second_round_invoices as $invoice) {
					try {
						$send = $this->send_invoice($invoice['Invoice']['id'],2);
						echo date("Y-m-d H:m:s")." - $send.\n";
					}
					catch (Exception $e) {
						echo date("Y-m-d H:m:s")." - Error: ".$e->getMessage().".\n";
					}
				}
			} else {
				echo date("Y-m-d H:m:s")." - No invoices due for reminder found.\n";
			}
			
			echo date("Y-m-d H:m:s")." - Checking for invoices that need a final demand...\n";
			
			$date->sub($seven_days);
				
			$third_round_invoices = $this->Invoice->find('all',array('conditions' => array('Invoice.schedule_for <' => $date->format('Y-m-d H:i:s'), 'Invoice.sent <' => $date->format('Y-m-d 23:59:59'), 'Invoice.last_send_type' => 2, 'Invoice.paid' => false, 'Invoice.allow_auto_contact' => true)));
				
			if (!empty($third_round_invoices)) {
				foreach ($third_round_invoices as $invoice) {
					$message_type = isset($invoice['Invoice']['recurring_invoice_id']) ? 6 : 3;
					try {
						$send = $this->send_invoice($invoice['Invoice']['id'],$message_type);
						echo date("Y-m-d H:m:s")." - $send.\n";
					}
					catch (Exception $e) {
						echo date("Y-m-d H:m:s")." - Error: ".$e->getMessage().".\n";
					}
				}
			} else {
				echo date("Y-m-d H:m:s")." - No invoices due for final demand found.\n";
			}
			
			echo date("Y-m-d H:m:s")." - Checking for unpaid final demand invoices...\n";
			
			$date->sub($two_days);
			
			$unpaid_final_demand_invoices = $this->Invoice->find('all',array('conditions' => array('Invoice.schedule_for <' => $date->format('Y-m-d H:i:s'), 'Invoice.sent <' => $date->format('Y-m-d 23:59:59'), 'Invoice.last_send_type' => array(3,6), 'Invoice.paid' => false, 'Invoice.alert' => false)));
			
			if (!empty($unpaid_final_demand_invoices)) {
				foreach ($unpaid_final_demand_invoices as $invoice) {
					try {
						$send = $this->alert_accounts($invoice['Invoice']['id']);
						echo date("Y-m-d H:m:s")." - $send.\n";
					}
					catch (Exception $e) {
						echo date("Y-m-d H:m:s")." - Error: ".$e->getMessage().".\n";
					}
				}
			} else {
				echo date("Y-m-d H:m:s")." - No unpaid final demand invoices found. Phew!\n";
			}
			
		} else {
			echo date("Y-m-d H:m:s")." - Automated invoice sending disabled.\n";
		}
		
		exit;
	}
	
	private function alert_accounts($invoice_id=null)
	{
		$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$invoice_id))));
		
		if (!empty($an_invoice['Invoice'])) {
			$email = new CakeEmail('sinclairNew');	
			$email->setHeaders(	array('MIME-Version' => '1.0','Content-Type' => 'text/html; charset=iso-8859-1'));
			$this->layoutPath = 'Emails/html';
			$email->template('default', 'STS_layout');
			$accounts_manager = $this->_settings['invoice.inquiries_name'];
			$accounts_emails = array($this->_settings['invoice.inquiries_email']);
			$attach_file = getcwd(). DS . 'files' . DS . 'pdf_invoices' . DS .'STS'.$an_invoice['Invoice']['id'].'.pdf';
			$message = '<p>Dear '.$accounts_manager.'</p>
			<p>The automated invoice emails function has flagged the invoice no. STS'.$an_invoice['Invoice']['id'].' for '.$an_invoice['Client']['firstname'].' '.$an_invoice['Client']['lastname'].', as being unpaid after a final demand has been sent. 48 hours have passed since the client was sent a final demand and the invoice is not marked as paid.</p>
			<p>Please take manual action to ensure this invoice is paid.</p>
			<p>Yours Sincerely, <br /></p>
			<p>Accounts Team</p>
			<br>
			<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
				<p style="font-size: 0.9em; color: #b4b4b4;"><a href="http://stsonline.uk.com">stsonline.uk.com</a></p>
				<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 513 046</p>
				<p style="font-size: 0.9em; color: #b4b4b4;">STS-Online Ltd</p>
				<p style="font-size: 0.9em; color: #b4b4b4;">8a Dunraven Place</p>
				<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
				<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
				<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
			</div>
			';
			
			$email->subject("URGENT: STS Invoice, no.".$an_invoice['Invoice']['id']." unpaid after final demand!");			
			$email->to($accounts_emails);
			$email->emailFormat('html');
			$email->attachments($attach_file);
			
			try {
				if ($email->send($message)) {
					$an_invoice['Invoice']['alert'] = true;
					if($this->Invoice->save($an_invoice)) {
						return 'mail sent: Final Demand Unpaid: Invoice no.STS'.$an_invoice['Invoice']['id'].' to '.implode(', ',$accounts_emails);
					}
					else {
						throw new Exception("Error saving invoice.");
					}
				} else {
					throw new Exception("Email send failed.");
				}
			} catch (Exception $e) {
				throw $e;
			}
		} else {
			throw new Exception("Invoice not found");
		}
	}

	/**
	 * Sends invoice to client.
	 * @param string $invoice_id ID of invoice
	 * @param number $message_no 1 = new invoice, 2 = overdue invoice, 3 = final demand.
	 * @throws Exception Throws Exception class exceptions when something goes wrong. Use a try/catch block!
	 * @return string Summary of send, for display to user.
	 */
	private function send_invoice($invoice_id=null,$message_no=1)
	{
		//send email with invoice and payment link
		$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$invoice_id))));
		
		if(! empty($an_invoice['Invoice']))
		{
			$email = new CakeEmail('sinclairNew');

			$message = '';
			
			// Add the content headers
			$email->setHeaders(	array('MIME-Version' => '1.0','Content-Type' => 'text/html; charset=iso-8859-1'));
				
			$this->layoutPath = 'Emails/html';
				
			$email->template('default', 'STS_layout');
			
			if($message_no == 6) {
				$message .=  '<p style="font-size: 150%; color: red; font-weight: bold;">IMPORTANT NOTICE: Your service will be suspended</p>';
			}
			//Check client details and set email
			$message .=  '
						<p>Dear '.$an_invoice['Client']['firstname'].',</p>
						';
			//send with a different subject line depending on the 'number of reminders'
				
			$reminder_no = $an_invoice['Invoice']['sends']+1;
				
		
			$reminder_tag='';//reminder tag is tagged to end of subject line
				
			switch($message_no)
			{
				case 1:
					$message .=  '
			
						<p>Please find attached your latest invoice no. <span style="color: #479CCF;">STS'.$an_invoice['Invoice']['id'].'</span>, which is now due for payment.</p>
								';
					break;
				case 2:
					$reminder_tag = ' - Polite reminder, this invoice is now overdue';
					$message .=  '
		
						<p>Please find attached invoice no. <span style="color: #479CCF;">STS'.$an_invoice['Invoice']['id'].'</span>, which is now <span style="color:red; font-weight: bold;">overdue</span> for payment.</p>
								';
					break;
				case 3:
					$reminder_tag = ' - Final Demand';
					$message .=  '
			
						<p>Please find attached invoice no. <span style="color: #479CCF;">STS'.$an_invoice['Invoice']['id'].'</span>, which is now overdue for payment.</p>
				
						<p style="color:red">This is a final demand, failure to pay will result in legal proceedings	</p>
								';
					break;
				case 4:
					$reminder_tag = ' Successfully Paid';
					$message .=  '
		
						<p>Please find attached invoice no. <span style="color: #479CCF;">STS'.$an_invoice['Invoice']['id'].'</span>, which has already been paid for via recurring payment.</p>
					
						<p>This invoice has already been paid, there is no action required.</p>
								';
					break;
				case 5:
					$reminder_tag = ' - Recurring Payment Agreed';
					$message .=  '
				
					<p>Please find attached invoice no. <span style="color: #479CCF;">STS'.$an_invoice['Invoice']['id'].'</span>, which you have already agreed to pay via recurring payment.</p>
		
					<p>This invoice will be paid for by your recurring payment agreement and there is no action required, provided you do not cancel your agreement. The link below is included in case you need to make a manual payment.</p>
							';
					break;
				case 6:
					$reminder_tag = ' - Service Suspension';
					$message .=  '
			
						<p>Please find attached your outstanding invoice no. <span style="color: #479CCF;">STS'.$an_invoice['Invoice']['id'].'</span>.</p>
				
						<p>This account is now overdue and it is important you bring this payment up to date immediately or <span style="font-weight: bold; color: red;">your service will be suspended</span>. If you are unable to make this payment at this time please contact STS Online as soon as possible. As your service provider and online partner we will do everything we can to assist you. If this service is no longer required, it would also be appreciated if you could communicate this to us, so we are able to manage this on your behalf. STS Online accepts no liability whatsoever for any loss caused by the suspension of this service and we will keep any materials or data relating to your service available for reconnection for 30 days before deletion. If you require any additional information about service suspension or your account with us please do not hesitate to contact us.</p>
								';
					break;
				default:
					$message .=  '
			
						<p>Please find attached your latest invoice no. STS'.$an_invoice['Invoice']['id'].', which is now due for payment.</p>
								';
					break;
			}
			$email->subject("STS Invoice, no.".$an_invoice['Invoice']['id'].$reminder_tag);
				
			// Build array of client email addresses
			$email_to = $this->decodeAddresses($an_invoice['Client']['email']);
				
			//override	with admin email while testing
			/*if( $this->_settings['application.mode'] == 'development' )
			 $email_to = $this->_settings['admin_email'];*/
				
			$email->to($email_to);
		
			$email->emailFormat('html'); //see below for converting html to text
				
			$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		
			if($message_no != 4) {
				$message .=  '	<p>You can pay easily and securely through our website, just follow the link below, or alternatively call our customer support during office hours and make a payment over the phone.</p>
				<p><br />
					<a href="'.Router::url('/', true).'invoices/pay/'.$invoice_id.'/'.sha1($invoice_id.$secret_phrase).'" style="color: #fff; width: 130px; display: block; margin: 0 auto; font-size: 12px; font-weight: 700; padding: 12px 30px; text-align: center; background: #479ccf; letter-spacing: 1px; text-decoration: none; text-transform: uppercase; border: 2px solid #479ccf; text-shadow: 1px 2px 0px rgba(0,0,0,.3);"><span class="button_example">Pay online now</span></a>
					<br />
				</p>';
			}
		
			$message .= '<p>Thanks for your business</p>
			<p>Yours Sincerely, <br /></p>
			<p>Accounts Team</p>
			<br>
			<div style="font-size: 0.9em; color: #b4b4b4;">
				<p><a href="https://STSOnline.uk.com">STSOnline.uk.com</a><br>01656 513 046<br> STS Online Ltd<br>8a Dunraven Place<br>Bridgend<br>CF31 1JD<br>United Kingdom</p>
			</div>
			';
		
			//set up tracking image
			//Set the route to the /responses folder from the base directory
			$full_base_url = $full_base_url =  Router::url('/', true);
			$responses_url = 'responses';
				
			$r_home_url = $full_base_url.$responses_url;
				
			//construct line to call a tracking pixel from responses/image . Include in message as src="{pix}"
			$opened_params = '/s_image?iv='.$an_invoice['Invoice']['id'].'&rem='.$reminder_no;
				
			$tracking_pix = $r_home_url.$opened_params;//$home_url.$secret;
		
			$message .= '<img src="'.$tracking_pix.'" height="1" width="1" alt="" border="0" />';
		
			//*******************
			//path is not correct
			$attach_file = getcwd(). DS . 'files' . DS . 'pdf_invoices' . DS .'STS'.$an_invoice['Invoice']['id'].'.pdf';
				
			$email->attachments($attach_file);
		
			try
			{

				if(	$email->send($message) ) //send is empty, message is set in viewVars $html and $text
				{
					$sends = $reminder_no;
							
					$an_invoice['Invoice']['sends'] = $sends;
					$an_invoice['Invoice']['last_send_type'] = $message_no;
					$an_invoice['Invoice']['sent'] = date('Y-m-d H:i:s');
					if($this->Invoice->save($an_invoice)) {
						//create a response entry for the reminder
						$a_response = $this->Response->create();
						$a_response['Response']['invoice_id'] = $an_invoice['Invoice']['id'];
						$a_response['Response']['reminder_no']=$reminder_no;
						$a_response['Response']['created'] = date('Y-m-d H:i:s');
						$a_response['Response']['modified'] = date('Y-m-d H:i:s');
						if($this->Response->save($a_response)) {
							return 'mail sent: Invoice no.STS'.$an_invoice['Invoice']['id'].' to '.implode(', ',$this->decodeAddresses($an_invoice['Client']['email']));
						}
						else {
							throw new Exception("Error saving response.");
						}	
					}
					else {
						throw new Exception("Error saving invoice.");
					}
				}
				else {
					throw new Exception("Email send failed.");
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
		}
		else
		{
			throw new Exception("Invoice not found");
		}
	}
	
	function getpaylink($id)
	{

		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		$url = Router::url('/', true).'invoices/pay/'.$id.'/'.sha1($id.$secret_phrase);
		return $url;
	}
	public function payment_process($inv_id=null, $hash=null, $paypal=null)
	{

		$this->layout = 'IndexLayout';
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		
		if ($this->request->is('post') &&  isset($inv_id) && isset($hash))
		{
			$inv_hash = sha1($inv_id.$secret_phrase);
			if($inv_hash==$hash)
			{
			
				$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));
				
				/*
				 * if ! logged in 
				 * 		if create account checkbox true
				 * 			create user
				 * 			update client model to link user
				 * 			send autoresponder email for create account
				 * 		endif
				 * else
				 * 		pull stored card
				 * endif
				 */
					$create_account = isset($this->request->data['account-create']) && $this->request->data['account-create']=='on' ? true :false;
					$client_id = $an_invoice['Client']['id'];
					$payment_method = isset($this->request->data['payment-method']) ? $this->request->data['payment-method'] : false;
					
					if($create_account)
					{
						//must create a new account
						$username = isset($this->request->data['account-email']) ? $this->request->data['account-email'] : false;//CakeBaseException('Account email not set when creating account');				
						$password = isset($this->request->data['account-password1']) ? $this->request->data['account-password1'] : false;//CakeBaseException('Account password not set when creating account');
						
						$this->User->create();
						$this->User->set(array
							(
									'username' => $username,
									'password' => $password,
									'role_id' => '4',
							)
						);
						$result = $this->User->save($this->data);
						if(!$result)
						{
							SessionComponent::setFlash('There was a problem creating your account because it is already in use, please use a different email and try again.');
							$this->redirect($this->getpaylink($an_invoice['Invoice']['id']));
						}
						
						//link new account to a client object
						$created_user_id = $this->User->id;
						$client = $this->Client->find('first', array('conditions'=> array(array('Client.id'=>$client_id))));
						$client['Client']['user_id'] = $created_user_id;
						$this->Client->save($client);
						
						//client notif
						$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for registering an account.</p>
								
								<p>For you records, your username is '.$username.' and your password is '.$password.'</p>
										
								<p>You will be asked to login using these details each time you pay an invoice, in order to access your stored account and payment records.</p>
											
								<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Admin Team</p>
								<p style="font-size: 0.7em;">
								<a href="http://stsonline.uk.com">stsonline.uk.com</a><br />
								+44 1656 513 046 <br />
								STS-Online Ltd <br />
								8a Dunraven Place, <br />
								Bridgend, <br />
								CF31 1JD, UK			
								</p>
								';
						$result = $this->sendEmail('User account created with STS', $message, $username);
					}
					
					// Redirect to PayPal if necssary.
					if($payment_method == 'paypal' && $an_invoice['Invoice']['allow_paypal'] == '1') {
						// Store data in session so we can create form for PayPal
						$paypal_data = $this->request->data;
						$paypal_data['inv_id'] = $inv_id;
						$paypal_data['hash'] = $hash;
											
						$rate = $this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate'];
						$balance = round(((floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit'])) / $rate), 2);
						$total = round(((floatval($an_invoice['Invoice']['total'])) / $rate), 2);
						$total = number_format((float)$total, 2, '.', '');
						$paypal_data['invoice_amount'] = $total;
						
						$paypal_data['currency'] = $an_invoice['Client']['default_display_currency'];
									
						$this->Session->write('Paypal.data', $paypal_data);
						
						$this->redirect(array('controller' => 'invoices', 'action' => 'paypal'));
					}
					else if($payment_method == 'bank' && $an_invoice['Invoice']['allow_bacs'] == '1')
					{
						$payment_type = 'bacs transfer';
						
						//get either invoice remaingin total, or partial amount depending on user selection
						$amount = isset($this->request->data['partial-amount'])&& isset($this->request->data['payment-amount']) && $this->request->data['payment-amount']=='manual' ? $this->request->data['partial-amount'] : floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']);
							
						//manual amount payments must be converted from the display currency into GBP for the processor
						if(isset($this->request->data['partial-amount'])&& isset($this->request->data['payment-amount']) && $this->request->data['payment-amount']=='manual')
						{
							$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
							$amount = round($amount * $conv_rate,2);
						}
						
						$payment_status = true;
						$orig_response = array('BACS transfer to be confirmed');
						
						
					}
					else if($payment_method == 'card' && $an_invoice['Invoice']['allow_cc'] == '1')
					{
						$payment_type = 'credit card';

						if($this->getPaymentProcessor() == '_barclaycard' && $this->_settings['barclaycard.method'] == 'hosted') {
							//get either invoice remaingin total, or partial amount depending on user selection
							$amount = isset($this->request->data['partial-amount'])&& isset($this->request->data['payment-amount']) && $this->request->data['payment-amount']=='manual' ? $this->request->data['partial-amount'] : floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']);
							
							//manual amount payments must be converted from the display currency into GBP for the processor
							if(isset($this->request->data['partial-amount'])&& isset($this->request->data['payment-amount']) && $this->request->data['payment-amount']=='manual')
							{
								$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
								$amount = round($amount * $conv_rate,2);
							}
							
							// Store data in session so we can create form for Barclaycard
							$epdq_data['PSPID'] = $this->_barclaycard->getOption('pspid');
							$epdq_data['ORDERID'] = uniqid();
							$epdq_data['AMOUNT'] = intval($amount * 100);
							$epdq_data['CURRENCY'] = 'GBP';
							$epdq_data['LANGUAGE'] = $an_invoice['Client']['default_display_currency'] == 'USD' ? 'en_US' : 'en_GB';
							$epdq_data['CN'] = $this->request->data['customer-name'];
							$epdq_data['EMAIL'] = $this->request->data['email'];
							$epdq_data['OWNERADDRESS'] = $this->request->data['address1'];
							$epdq_data['OWNERZIP'] = $this->request->data['zipcode'];
							$epdq_data['OWNERTOWN'] = $this->request->data['city'];
							$epdq_data['OWNERCTY'] = $this->request->data['state'];
							$epdq_data['ACCEPTURL'] = Router::url('/', true).'invoices/payment_processed/'.$inv_id.'/'.$inv_hash;
							$epdq_data['DECLINEURL'] = Router::url('/', true).'invoices/epdq_failed/'.$inv_id.'/'.$inv_hash;
							$epdq_data['EXCEPTIONURL'] = Router::url('/', true).'invoices/epdq_failed/'.$inv_id.'/'.$inv_hash;
							$epdq_data['CANCELURL'] = Router::url('/', true).'invoices/pay/'.$inv_id.'/'.$inv_hash;
							$epdq_data['OPERATION'] = 'SAL';
							$epdq_data['SHASIGN'] = $this->_barclaycard->generateShaHash($epdq_data);
							
							$this->InvoicePayment->create();
							$invoice_payment['InvoicePayment'] = array('invoice_id' => $inv_id,'payment_unique_id' => $epdq_data['ORDERID']);
							$this->InvoicePayment->save($invoice_payment);
								
							$this->Session->write('ePDQ.data', $epdq_data);
						
							$this->redirect(array('controller' => 'invoices', 'action' => 'epdq'));
						}
						else if($this->getPaymentProcessor() == '_worldpay') {
							$recurring_payment = isset($this->request->data['recurring_payment']) && $this->request->data['recurring_payment'] == 1;
							
							$amount = isset($this->request->data['partial-amount'])&& isset($this->request->data['payment-amount']) && $this->request->data['payment-amount']=='manual' && !$recurring_payment ? $this->request->data['partial-amount'] : floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']);
							
							//manual amount payments must be converted from the display currency into GBP for the processor
							if(isset($this->request->data['partial-amount']) && isset($this->request->data['payment-amount']) && $this->request->data['payment-amount']=='manual')
							{
								$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
								$amount = round($amount * $conv_rate,2);
							}
							
							$cart_id_number = uniqid();
							// Store data in session so we can create form for WorldPay
							$worldpay_data['instId'] = '1044322';
							$worldpay_data['cartId'] = "0.".$cart_id_number;
							$worldpay_data['amount'] = $amount;
							$worldpay_data['currency'] = 'GBP';
							$worldpay_data['address1'] = $this->request->data['address1'];
							$worldpay_data['town'] = $this->request->data['city'];
							$worldpay_data['region'] = $this->request->data['state'];
							$worldpay_data['postcode'] = $this->request->data['zipcode'];
							$worldpay_data['country'] = $an_invoice['Client']['country'];
							$worldpay_data['desc'] = "STS Online invoice STS".$inv_id;
							$worldpay_data['name'] = $this->request->data['customer-name'];
							$worldpay_data['email'] = $this->request->data['email'];
							$worldpay_data['hideCurrency'] = null;
							$worldpay_data['signature'] = md5('$T5WorldPayTransactionHash:'.$worldpay_data['instId'].':'.$worldpay_data['cartId'].':'.$worldpay_data['amount'].':'.$worldpay_data['currency']);
							
							if($recurring_payment) {
								$date_time = explode(' ',$an_invoice['Invoice']['schedule_for']);
								$date = explode('-',$date_time[0]);
								$month = intval($date[1]) + 1;
								$start_date = $date[0].'-'.sprintf("%02d",$month).'-'.$date[2];
								
								$recurring_invoice = $this->RecurringInvoice->findById($an_invoice['Invoice']['recurring_invoice_id']);
								
								$intervalUnit = 3;
								
								switch($recurring_invoice['RecurringInvoice']['type']) {
									case 0:
										//Monthy
										$intervalUnit = 3;
										break;
									case 1:
										//Yearly
										$intervalUnit = 4;
										break;
								}
								
								$worldpay_data['futurePayType'] = "regular";
								$worldpay_data['startDate'] = $start_date;
								$worldpay_data['intervalUnit'] = $intervalUnit;
								$worldpay_data['intervalMult'] = 1;
								$worldpay_data['normalAmount'] = $amount;
								$worldpay_data['option'] = 1;
								// The following fields need to be overwritten/recalculated
								$worldpay_data['cartId'] = $an_invoice['Invoice']['recurring_invoice_id'].'.'.$cart_id_number;
								$worldpay_data['signature'] = md5('$T5WorldPayTransactionHash:'.$worldpay_data['instId'].':'.$worldpay_data['cartId'].':'.$worldpay_data['amount'].':'.$worldpay_data['currency']);
							}
							
							$this->InvoicePayment->create();
							$invoice_payment['InvoicePayment'] = array('invoice_id' => $inv_id,'payment_unique_id' => $worldpay_data['cartId']);
							$this->InvoicePayment->save($invoice_payment);
							
							$this->Session->write('WorldPay.data', $worldpay_data);
							
							$this->redirect(array('controller' => 'invoices', 'action' => 'worldpay'));
						}
						else
						{
							//add card if theyre adding new card
							$add_new_card = isset($this->request->data['card-select']) && $this->request->data['card-select'] == 'new-card' ? true : false;
							if($add_new_card)
							{
								$default_card = isset($this->request->data['card-default']) && $this->request->data['card-default'] == 'on' ? true : false;
								if($default_card)
								{
									//update all other cards for this client to be non-default
									$this->Card->updateAll(
											array('Card.default' => false),
											array('Card.client_id' => $client_id)
									);
								}
							
								//get and validate fields
								$type = isset($this->request->data['card-type']) ? $this->request->data['card-type'] : false;
								$name = isset($this->request->data['card-name']) ? $this->request->data['card-name'] : false;
								$number = isset($this->request->data['card-number']) ? $this->request->data['card-number'] : false;
								$last_four = $number ? substr($number, 12, 4) : false;
								$start_month = isset($this->request->data['card-start-month']) ? $this->request->data['card-start-month'] : false;
								$start_year = isset($this->request->data['card-start-year']) ? $this->request->data['card-start-year'] : false;
								$expiry_month = isset($this->request->data['card-exp-month']) ? $this->request->data['card-exp-month'] : false;
								$expiry_year = isset($this->request->data['card-exp-year']) ? $this->request->data['card-exp-year'] : false;
								$security_code = isset($this->request->data['cvv-code']) ? $this->request->data['cvv-code'] : false;
								$issue_number = isset($this->request->data['card-issue']) ? $this->request->data['card-issue'] : false;
							
								$this->Card->set(array
										(
												'client_id' => $client_id,
												'default' => $default_card,
												'type' => $type,
												'name' => $name,
												'number' => $number,
												'last_four' => $last_four,
												'start_month' => $start_month,
												'start_year' => $start_year,
												'expiry_month' => $expiry_month,
												'expiry_year' => $expiry_year,
												'security_code' => $security_code,
												'issue_number' => $issue_number,
										)
								);
							
								if(!$this->Card->validates())
								{
									//card not valid, redirect with err
									$errors = $this->Card->invalidFields();
									SessionComponent::setFlash('There was a problem validating your card details, please check them and try again.');
									$this->redirect($this->getpaylink($an_invoice['Invoice']['id']));
								}
							
							
								//encrypt where nec. into vars and store to db without revalidating, as encrypted data will fail validation
								//encryption algo is mcrypt($config_salt.$shared_secret, $data)
							
								$this->Card->create();
								$this->Card->set(array
										(
												'client_id' => $client_id,
												'default' => $default_card,
												'type' => $type,
												'name' => $this->encrypt($name,$this->key,$this->iv,$this->bit_check),
												'number' => $this->encrypt($number,$this->key,$this->iv,$this->bit_check),
												'last_four' => $last_four,
												'start_month' => $start_month,
												'start_year' => $start_year,
												'expiry_month' => $expiry_month,
												'expiry_year' => $expiry_year,
												'security_code' => $this->encrypt($security_code,$this->key,$this->iv,$this->bit_check),
												'issue_number' => $issue_number,
										)
								);
								$result = $this->Card->save($this->data, array('validate' => false));
							}
								
							$card_info = $this->request->data;
								
							//detect if we're using stored card, or passed in details this time
							$user = $this->Session->read('Auth.User');
							if(isset($user) && !$add_new_card)
							{
								//fetch and decrypt stored card
								$card_id = isset($this->request->data['card-select']) ? $this->request->data['card-select'] : false;
								$card = $this->Card->find('first', array('conditions'=> array(array('Card.id'=>$card_id))));
							
								$card_number = $this->decrypt($card['Card']['number'],$this->key,$this->iv,$this->bit_check);
								$card_name = $this->decrypt($card['Card']['name'],$this->key,$this->iv,$this->bit_check);
								$security_code = $this->decrypt($card['Card']['security_code'],$this->key,$this->iv,$this->bit_check);
							
								//load into $card_info array in standard format that cc processor knows about
								$card_info['card-number'] = $card_number;
								$card_info['card-name'] = $card_name;
								$card_info['cvv-code'] = $security_code;
								$card_info['card-type'] = $card['Card']['type'];
								$card_info['card-start-month'] = $card['Card']['start_month'];
								$card_info['card-start-year'] = $card['Card']['start_year'];
								$card_info['card-exp-month'] = $card['Card']['expiry_month'];
								$card_info['card-exp-year'] = $card['Card']['expiry_year'];
								$card_info['card-issue'] = $card['Card']['issue_number'];
							
								//update default if nec.
								$default_card = isset($this->request->data['card-default']) && $this->request->data['card-default'] == 'on' ? true : false;
								if($default_card)
								{
									//update all other cards for this client to be non-default
									$this->Card->updateAll(
											array('Card.default' => false),
											array('Card.client_id' => $client_id)
									);
									//set this card to be default
									$this->Card->updateAll(
											array('Card.default' => true),
											array('Card.id' => $card_id)
									);
								}
							}
								
							//get either invoice remaingin total, or partial amount depending on user selection
							$amount = isset($card_info['partial-amount'])&& isset($card_info['payment-amount']) && $card_info['payment-amount']=='manual' ? $card_info['partial-amount'] : floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']);
								
							//manual amount payments must be converted from the display currency into GBP for the processor
							if(isset($card_info['partial-amount'])&& isset($card_info['payment-amount']) && $card_info['payment-amount']=='manual')
							{
								$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
								$amount = round($amount * $conv_rate,2);
							}
							
							$payment_processor = $this->getPaymentProcessor();
								
							list($payment_status, $orig_response) = $this->$payment_processor->processPayment($card_info, $amount);
						}
						
					} 
					else
					{
						$this->Session->setFlash('Invalid payment method.');
												
						$this->redirect(array('controller' => 'invoices', 'action' => 'pay', $inv_id, $inv_hash));
					}
					
					$this->Session->delete('payment_status');
					$this->Session->delete('orig_response');
						
					$this->Session->write('payment_status', $payment_status);
					$this->Session->write('orig_response', $orig_response);
					
					$this->set('invoice',$an_invoice);
					
					if($payment_type != 'bacs transfer')
					{
						//save payment attempt
						$a_payment = $this->Payment->create();
						$a_payment['Payment']['invoice_id'] = $an_invoice['Invoice']['id'];
						$a_payment['Payment']['client_id'] = $an_invoice['Invoice']['client_id'];
						$a_payment['Payment']['type'] = $payment_type;
						$a_payment['Payment']['amount'] = $amount;
						$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
						$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
						$a_payment['Payment']['processor_response'] = json_encode($orig_response);
						$this->Payment->save($a_payment);
					}
					
					if($payment_status)
					{
						$bacs_full = false;
						
						//payment succeeded
						if($payment_type != 'bacs transfer')
						{	
							//credit amount to invoice account
							$an_invoice['Invoice']['credit'] = floatval($an_invoice['Invoice']['credit']) + $amount;
								
							//mark invoice as paid if  total credit > invoice total
							if(floatval($an_invoice['Invoice']['total']) <= floatval($an_invoice['Invoice']['credit']))
							{
								$an_invoice['Invoice']['paid'] = 1;
							}
								
							$this->Invoice->save($an_invoice);
						}	
						else
						{
							if($amount >= floatval($an_invoice['Invoice']['credit']))
							{
								$bacs_full = true;
							}
						}
						
						// Build array of client email addresses
						$addresses = $this->decodeAddresses($an_invoice['Client']['email']);
							
						//full or partial payment of invoice account?
						if($an_invoice['Invoice']['paid'] == 1  || $bacs_full)
						{
							if($payment_type == 'bacs transfer')
							{
								$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Marked as Paid';
								
								//admin notif for full payment of invoices
								$message = js_beautify(json_encode(array($an_invoice)));
								$this->sendAdminEmail('Invoice '.$an_invoice['Invoice']['id'].' Marked as Paid - Please Verify', $message);
							}
							else
							{
								$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Successfully Paid';
								
								//admin notif for full payment of invoices
								$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
								$this->sendAdminEmail($subject, $message);
							}
											
							//client notif
							$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for settling invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
					
							if($payment_type == 'credit card')
							{
								$message .=	'<p>Payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
							}
							else if($payment_type == 'bacs transfer')
							{
								$message .=	'<p>Thank you for paying the amount for the invoice via BACS transfer and marking this invoice as paid. A member of the STS Accounts team will verify your payment and complete the invoice for you.</p>';
							}
						
							$message .=	'<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<p style="font-size: 0.7em;">
								<a href="http://stsonline.uk.com">stsonline.uk.com</a><br />
								+44 1656 513 046 <br />
								STS-Online Ltd <br />
								8a Dunraven Place, <br />
								Bridgend, <br />
								CF31 1JD, UK
								</p>
								';
							$result = $this->sendEmail($subject, $message, $addresses);
							
						}else{
							
							if($payment_type == 'bacs transfer')
							{
								$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Marked as Partially Paid';
							
								//admin notif for full payment of invoices
								$message = js_beautify(json_encode(array($an_invoice)));
								$this->sendAdminEmail('Invoice '.$an_invoice['Invoice']['id'].' Marked as Partially Paid - Please Verify', $message);
							}
							else
							{
								$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Partially Paid';
							
								
							}
																					
							$curr_sym = $this->currencies[$an_invoice['Client']['default_display_currency']]['symbol'];
							$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
							$remaining_balance = round( ((floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']))) / $conv_rate, 2);
							$invoice_url = Router::url('/', true).'invoices/pay/'.$an_invoice['Invoice']['id'].'/'.sha1($an_invoice['Invoice']['id'].$secret_phrase);
					
							$this->set('remaining_balance',$remaining_balance);
							$this->set('curr_sym',$curr_sym);
							$this->set('invoice_url',$invoice_url);
							
							//client notif
							$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for your partial payment towards invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
					
							if($payment_type == 'credit card')
							{
								$message .=	'<p>Partial payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
							}
							else if($payment_type == 'bacs transfer')
							{
								$message .=	'<p>Thank you for your payment towards the invoice via BACS transfer. A member of the STS Accounts team will verify your payment and update the invoice for you.</p>';
							}
						
							$message .=	'<p>The balance remaining to be paid on this invoice is '.$curr_sym.$remaining_balance.'</p>
								<p>You can pay easily and securely through our website, just click the link below, or alternatively you can call our customer support during office hours and make a payment over the phone.</p>
								<p><br />
									<a href="'.$invoice_url.'"><span class="button_example">Pay online now</span></a>
									<br />
								</p>
						
								<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
									<p style="font-size: 0.9em; color: #b4b4b4;"><a href="http://stsonline.uk.com">stsonline.uk.com</a></p>
									<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 513 046</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">STS-Online Ltd</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">8a Dunraven Place</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
								</div>
								';
							$result = $this->sendEmail($subject, $message, $addresses);
							
						}
						
						$email_addresses = json_decode($an_invoice['Client']['email'],true);
						if(!is_null($email_addresses))
						{
							if(count($email_addresses) > 1)
							{
								$last_address = ' and '.array_pop($email_addresses);
						
								$emails = implode(', ',$email_addresses);
								$emails .= $last_address;
							}
							else
							{
								$emails = $email_addresses[0];
							}
						}
						else
						{
							@$emails = $invoice['Client']['email'];
						}
						
						$this->set('emails',$emails);
					
					}else
					{
						//payment failed
						$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
						$inv_id = $an_invoice['Invoice']['id'];
						$inv_hash = sha1($inv_id.$secret_phrase);
							
						$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
						$this->sendAdminEmail('Invoice '.$an_invoice['Invoice']['id'].' Payment Attempt Failed', $message);
					
							
						$this->redirect('pay/'.$inv_id.'/'.$inv_hash);
					}
	
				$this->layout = 'IndexLayout';
			}
			
		}
	}
	public function pay($inv_id=null, $hash=null)
	{
		$user = $this->Session->read('Auth.User');
		$this->set('title_for_layout','Pay Invoice Online - Secure Payment Processing Software By STS');
		$this->layout = 'IndexLayout';
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		
		if(isset($inv_id) && isset($hash))
		{
			$inv_hash = sha1($inv_id.$secret_phrase);
			//die(print_r(array($inv_hash, $inv_id, $secret_phrase)));
			if($inv_hash==$hash)
			{
				$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));
				if( empty($an_invoice))
				{
					$this->redirect(array('action' => 'no_invoice_id'));
				}
				if($an_invoice['Invoice']['paid'] == 1)
				{
					$this->redirect(array('action' => 'paid_before', $inv_id));
				}
				
				
				$client_id = $an_invoice['Client']['id'];
				$client = $this->Client->find('first', array('conditions'=> array(array('Client.id'=>$client_id))));
				$cards = $this->Card->find('all', array('conditions'=> array(array('Client.id'=>$client_id)), 'order'=>array('Card.default DESC')));
				
				


				if(($client['User']['id']==!null /*|| $client['User']['id']!='0'*/) && !isset($user))
				{
					//client has a user account, and is not logged in
					//set this url as the return dest and force login
					SessionComponent::setFlash('Your account has a login associated with it, please login now to continue.');
					$this->Auth->redirectUrl('https://' . env('SERVER_NAME') . $this->here);
					$this->redirect('/users/login');
				}

				//die(var_dump($client));
				/*if($user['id'] != $client['User']['id'])
				{
					//un authorized access with a stolen link
					SessionComponent::setFlash('Unauthorized access!');
					$this->redirect('http://'.env('SERVER_NAME').$this->webroot);
				}*/


								
				$email_addresses = json_decode($an_invoice['Client']['email'],true);
				if(!is_null($email_addresses))
				{
					$an_invoice['Client']['email'] = $email_addresses[0]; // Just display first email address to keep things clean.
				}
							
				$an_invoice['Invoice']['viewed_email'] = true;
				$an_invoice['Invoice']['viewed_email_timestamp'] = date('Y-m-d H:i:s');
				$this->Invoice->save($an_invoice);
				$this->set('user', $user);
				$this->set('client', $client);
				$this->set('cards', $cards);
				$this->set('invoice',$an_invoice);
				$this->set('sort_code', $this->_settings['bacs.sort_code']);
				$this->set('account_number',$this->_settings['bacs.account_number']);
			}
		}
		
		
	}
	
	public function paypal($inv_id=null, $hash=null)
	{
		$paypal_data = $this->Session->read('Paypal.data');
		$paypal_data['paying_amount'] = $paypal_data['payment-amount'] == 'manual' ? round($paypal_data['partial-amount'],2) : round($paypal_data['invoice_amount'],2);
		$paypal_data['payment_url'] = 'https://'.env('SERVER_NAME').$this->base.'/invoices/pay/'.$paypal_data['inv_id'].'/'.$paypal_data['hash'];
		$paypal_data['return_url'] = 'https://'.env('SERVER_NAME').$this->base.'/invoices/paypal_process/'.$paypal_data['inv_id'].'/'.$paypal_data['hash'];
		$paypal_data['notify_url'] = 'https://'.env('SERVER_NAME').$this->base.'/invoices/paypal_ipn_listener';
	
		$paypal_email = $this->_settings['paypal.email_address'];
		$paypal_url = $this->_settings['paypal.mode'] == 'test' ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
			
		$this->layout = 'ajax';
	
		$this->set('paypal_data',$paypal_data);
		$this->set('paypal_url',$paypal_url);
		$this->set('paypal_email',$paypal_email);
	}
	
	public function epdq($inv_id=null, $hash=null)
	{
		$epdq_data = $this->Session->read('ePDQ.data');
		
		$url = $this->_settings['barclaycard.mode'] == 'test' ? 'https://mdepayments.epdq.co.uk/ncol/test/orderstandard.asp' : 'https://payments.epdq.co.uk/ncol/prod/orderstandard.asp';
			
		$this->layout = 'ajax';
		
		$this->set('epdq_data',$epdq_data);
		$this->set('url',$url);
	}
	
	public function worldpay($inv_id=null, $hash=null)
	{
		$worldpay_data = $this->Session->read('WorldPay.data');
	
		$url = "https://secure.worldpay.com/wcc/purchase";
		
		if($this->_settings['worldpay.mode'] == 'test') {
			$worldpay_data['testMode'] = 100;
			$url = "https://secure-test.worldpay.com/wcc/purchase";
		}
			
		$this->layout = 'ajax';
	
		$this->set('worldpay_data',$worldpay_data);
		$this->set('url',$url);
	}
	
	public function worldpay_listener()
	{
		$debugging = false;
		$log = "Nothing happened.\n";
	
		try
		{
			if($this->request->is('post'))
			{
				$data = $this->request->data;
				
				if(isset($data['callbackPW']) && $data['callbackPW'] == $this->_settings['worldpay.callback_pw'] && isset($data['cartId']) && isset($data['amount']) && isset($data['transStatus']))
				{
						
					switch($data['transStatus']) {
						case 'Y': $payment_status = true; break;
						case 'C': $payment_status = false; break;
						case 'N': $payment_status = false; break;
						default: $payment_status = false;
					}
					
					$worldpay_cart_id = explode('.',$data['cartId']);
					$reccuring_invoice_id = $worldpay_cart_id[0];
					$cart_id = $worldpay_cart_id[1];
					
					if($reccuring_invoice_id > 0 && isset($data['futurePayId'])) {
						// Recurring payment

						$recurring_invoice = $this->RecurringInvoice->findById($reccuring_invoice_id);
						if(empty($recurring_invoice)) {
							throw new Exception("Can't find recurring invoice #$reccuring_invoice_id.\n");
						} 
						
						// Get the oldest invoice based on this Recurring Invoice ID.
						$an_invoice = $this->Invoice->find('first',array('conditions' => array('recurring_invoice_id' => $reccuring_invoice_id, 'paid' => false), 'order' => array('Invoice.schedule_for')));
						
						if(!empty($an_invoice)) {
							//save payment attempt
							$a_payment = $this->Payment->create();
							$a_payment['Payment']['invoice_id'] = $an_invoice['Invoice']['id'];
							$a_payment['Payment']['unique_id'] = $cart_id;
							$a_payment['Payment']['type'] = 'credit card';
							$a_payment['Payment']['amount'] = $data['amount'];
							$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
							$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
							$a_payment['Payment']['processor_response'] = json_encode($data);
							$this->Payment->save($a_payment);
								
							$payment_type = 'credit card';
							$amount = $data['amount'];
							$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
							
							if($payment_status)	{
								//credit amount to invoice account
								$an_invoice['Invoice']['credit'] = floatval($an_invoice['Invoice']['credit']) + $amount;
							
								//mark invoice as paid if  total credit > invoice total
								if(floatval($an_invoice['Invoice']['total']) <= floatval($an_invoice['Invoice']['credit']))
								{
									$an_invoice['Invoice']['paid'] = 1;
								}
							
								$this->Invoice->save($an_invoice);
							
								// Build array of client email addresses
								$addresses = $this->decodeAddresses($an_invoice['Client']['email']);
							
								//full or partial payment of invoice account?
								if($an_invoice['Invoice']['paid'] == 1) {
									$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Successfully Paid';
							
									//admin notif for full payment of invoices
									$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
									$this->sendAdminEmail($subject, $message);
							
									//client notif
									$message = '
									<p>Dear '.$an_invoice['Client']['firstname'].',</p>
									<p>Thanks for settling invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
							
									$message .=	'<p>Payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
							
									$message .=	'<p>Thanks for your business!</p>
									<p>Yours Sincerely, <br /></p>
									<p>Accounts Team</p>
									<p style="font-size: 0.7em;">
									www.stsonline.uk.com <br />
									+44 1656 513 046 <br />
									STS-Online Ltd <br />
									8a Dunraven Place, <br />
									Bridgend, <br />
									CF31 1JD, UK
									</p>
									';
									$result = $this->sendEmail($subject, $message, $addresses);
							
								} else {
							
									$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Partially Paid';
							
									$curr_sym = $this->currencies[$an_invoice['Client']['default_display_currency']]['symbol'];
									$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
									$remaining_balance = round( ((floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']))) / $conv_rate, 2);
									$invoice_url = Router::url('/', true).'invoices/pay/'.$an_invoice['Invoice']['id'].'/'.sha1($an_invoice['Invoice']['id'].$secret_phrase);
							
									//client notif
									$message = '
									<p>Dear '.$an_invoice['Client']['firstname'].',</p>
									<p>Thanks for your partial payment towards invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
							
									if($payment_type == 'credit card')
									{
										$message .=	'<p>Partial payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
									}
									else if($payment_type == 'bacs transfer')
									{
										$message .=	'<p>Thank you for your payment towards the invoice via BACS transfer. A member of the STS Accounts team will verify your payment and update the invoice for you.</p>';
									}
							
									$message .=	'<p>The balance remaining to be paid on this invoice is '.$curr_sym.$remaining_balance.'</p>
									<p>You can pay easily and securely through our website, just click the link below, or alternatively you can call our customer support during office hours and make a payment over the phone.</p>
									<p><br />
										<a href="'.$invoice_url.'"><span class="button_example">Pay online now</span></a>
										<br />
									</p>
								
									<p>Thanks for your business!</p>
									<p>Yours Sincerely, <br /></p>
									<p>Accounts Team</p>
									<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
										<p style="font-size: 0.9em; color: #b4b4b4;">www.stsonline.uk.com</p>
										<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 513 046</p>
										<p style="font-size: 0.9em; color: #b4b4b4;">STS-Online Ltd</p>
										<p style="font-size: 0.9em; color: #b4b4b4;">8a Dunraven Place</p>
										<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
										<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
										<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
									</div>
									';
									$result = $this->sendEmail($subject, $message, $addresses);
							
								}
							
								$log = "Payment accepted.\n";
							
							} else {
								$log = "Payment declined.\n";
								
								$a_payment = $this->Payment->create();
								$a_payment['Payment']['invoice_id'] = $an_invoice['Invoice']['id'];
								$a_payment['Payment']['unique_id'] = $cart_id;
								$a_payment['Payment']['type'] = 'credit card';
								$a_payment['Payment']['amount'] = $data['amount'];
								$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
								$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
								$a_payment['Payment']['processor_response'] = json_encode($data);
								$this->Payment->save($a_payment);
							}
						} else {
							// We couldn't find an invoice for this payment! We need to store this as an overpayment on the recurring invoice.
							//save payment attempt
							$a_payment = $this->Payment->create();
							$a_payment['Payment']['invoice_id'] = null;
							$a_payment['Payment']['unique_id'] = $cart_id;
							$a_payment['Payment']['type'] = 'credit card';
							$a_payment['Payment']['amount'] = $data['amount'];
							$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
							$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
							$a_payment['Payment']['processor_response'] = json_encode($data);
							$a_payment['Payment']['notes'] = "Overpayment for Recurring Invoice ".$recurring_invoice['RecurringInvoice']['id'];
							$this->Payment->save($a_payment);
							
							$payment_type = 'credit card';
							$amount = $data['amount'];
							$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
							
							if($payment_status)	{
								if(is_null($recurring_invoice['RecurringInvoice']['futurepay_id'])) {
									$recurring_invoice['RecurringInvoice']['futurepay_id'] = $data['futurePayId'];
									$recurring_invoice['RecurringInvoice']['recurring_payment'] = true;
								}
								
								$recurring_invoice['RecurringInvoice']['overpayment_balance'] = $recurring_invoice['RecurringInvoice']['overpayment_balance'] + $amount;
								
								$this->RecurringInvoice->save($recurring_invoice);
								
								$addresses = $this->decodeAddresses($recurring_invoice['Client']['email']);
								
								$subject = 'Payment Received';
								
								//admin notif for full payment of invoices
								$message = js_beautify(json_encode(array($recurring_invoice, $a_payment)));
								$this->sendAdminEmail($subject, $message);
								
								//client notif
								$message = "
								<p>Dear ".$recurring_invoice['Client']['firstname'].",</p>
								<p>Thanks for your recurring payment. Your invoice hasn't been issued at this time, but when it is issued, this payment will be automatically applied.</p>";
								
								$message .=	'<p>Payment for the amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
								
								$message .=	'<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<p style="font-size: 0.7em;">
								www.stsonline.uk.com <br />
								+44 1656 513 046<br />
								STS-Online Ltd <br />
								8a Dunraven Place, <br />
								Bridgend, <br />
								CF31 1JD, UK
								</p>
								';
								$result = $this->sendEmail($subject, $message, $addresses);
								
								$log = "(Overpayment) Payment accepted.\n";
							} else {
								$log = "(Overpayment) Payment declined.\n";
							}
						}
						
						
					} else {
						// Regular payment						
						$invoice_payment = $this->InvoicePayment->findByPaymentUniqueId($data['cartId']);
						
						if(!isset($invoice_payment['InvoicePayment']['invoice_id'])) {
							throw new Exception("Can't find invoice id.\n");
						}
						
						$an_invoice = $this->Invoice->findById($invoice_payment['InvoicePayment']['invoice_id']);
						
						//save payment attempt
						$a_payment = $this->Payment->create();
						$a_payment['Payment']['invoice_id'] = $invoice_payment['InvoicePayment']['invoice_id'];
						$a_payment['Payment']['unique_id'] = $cart_id;
						$a_payment['Payment']['type'] = 'credit card';
						$a_payment['Payment']['amount'] = $data['amount'];
						$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
						$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
						$a_payment['Payment']['processor_response'] = json_encode($data);
						$this->Payment->save($a_payment);
						
						$payment_type = 'credit card';
						$amount = $data['amount'];
						$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
						
						if($payment_status)
						{
							//credit amount to invoice account
							$an_invoice['Invoice']['credit'] = floatval($an_invoice['Invoice']['credit']) + $amount;
								
							//mark invoice as paid if  total credit > invoice total
							if(floatval($an_invoice['Invoice']['total']) <= floatval($an_invoice['Invoice']['credit']))
							{
								$an_invoice['Invoice']['paid'] = 1;
							}
								
							$this->Invoice->save($an_invoice);
								
							// Build array of client email addresses
							$addresses = $this->decodeAddresses($an_invoice['Client']['email']);
								
							//full or partial payment of invoice account?
							if($an_invoice['Invoice']['paid'] == 1)
							{
								$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Successfully Paid';
						
								//admin notif for full payment of invoices
								$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
								$this->sendAdminEmail($subject, $message);
						
								//client notif
								$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for settling invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
						
								$message .=	'<p>Payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
						
								$message .=	'<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<p style="font-size: 0.7em;">
								www.stsonline.uk.com <br />
								+44 1656 513 046 <br />
								STS-Online Ltd <br />
								8a Dunraven Place, <br />
								Bridgend, <br />
								CF31 1JD, UK
								</p>
								';
								$result = $this->sendEmail($subject, $message, $addresses);
						
							} else {
						
								$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Partially Paid';
						
								$curr_sym = $this->currencies[$an_invoice['Client']['default_display_currency']]['symbol'];
								$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
								$remaining_balance = round( ((floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']))) / $conv_rate, 2);
								$invoice_url = Router::url('/', true).'invoices/pay/'.$an_invoice['Invoice']['id'].'/'.sha1($an_invoice['Invoice']['id'].$secret_phrase);
						
								//client notif
								$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for your partial payment towards invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
						
								if($payment_type == 'credit card')
								{
									$message .=	'<p>Partial payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
								}
								else if($payment_type == 'bacs transfer')
								{
									$message .=	'<p>Thank you for your payment towards the invoice via BACS transfer. A member of the STS Accounts team will verify your payment and update the invoice for you.</p>';
								}
						
								$message .=	'<p>The balance remaining to be paid on this invoice is '.$curr_sym.$remaining_balance.'</p>
								<p>You can pay easily and securely through our website, just click the link below, or alternatively you can call our customer support during office hours and make a payment over the phone.</p>
								<p><br />
									<a href="'.$invoice_url.'"><span class="button_example">Pay online now</span></a>
									<br />
								</p>
						
								<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
									<p style="font-size: 0.9em; color: #b4b4b4;">www.stsonline.uk.com</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 513 046</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">STS-Online Ltd</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">8a Dunraven Place</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
								</div>
								';
								$result = $this->sendEmail($subject, $message, $addresses);
						
							}
								
							$log = "Payment accepted.\n";
								
						}
						else
						{
							$log = "Payment declined.\n";
						}
					}
						
				} elseif(isset($data['futurePayId']) && isset($data['futurePayStatusChange'])) {
					// This is a change to a future pay agreement, so we need to update the RecurringInvoice
					$recurring_invoice = $this->RecurringInvoice->find('first',array('conditions' => array('RecurringInvoice.futurepay_id' => $data['futurePayId'])));
					
					if(!empty($recurring_invoice)) {
						$recurring_invoice['RecurringInvoice']['futurepay_id'] = '';
						$recurring_invoice['RecurringInvoice']['recurring_payment'] = false;
						
						if($this->RecurringInvoice->save($recurring_invoice)) {
							$log = "FuturePay update changed RecurringInvoice #".$recurring_invoice['RecurringInvoice']['id'].". FuturePayID: ".$data['futurePayId'].", Update: ".$data['futurePayStatusChange'].".\n";
						} else {
							$log = "FuturePay update failed to save RecurringInvoice #".$recurring_invoice['RecurringInvoice']['id'].". FuturePayID: ".$data['futurePayId'].", Update: ".$data['futurePayStatusChange'].".\n";
						}
					} else {
						$log = "FuturePay update failed to find RecurringInvoice. FuturePayID: ".$data['futurePayId'].", Update: ".$data['futurePayStatusChange'].".\n";
					}
				} else {
					$log = "Fields missing.\n";
				}
			}
			else
			{
				$log = "Not a POST.\n";
			}
		}
		catch(Exception $e)
		{
			$log = $e->getMessage()."\n";
		}
	
		file_put_contents(LOGS.'/worldpay.log', date("Y-m-d H:i:s")." - ".$_SERVER['REMOTE_ADDR']." - ".$log, FILE_APPEND);
		exit;
	}
	
	/**
	 * For payment success on hosted page payment processor transaction.
	 */
	public function payment_processed($inv_id=null, $hash=null)
	{
		$this->set('title_for_layout','Payment');
		$this->layout = 'IndexLayout';
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		if(isset($inv_id) && isset($hash))
		{
			$inv_hash = sha1($inv_id.$secret_phrase);
			if($inv_hash == $hash)
			{
				$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));
				
				$this->set('invoice',$an_invoice);
			}
			else
			{
				$this->redirect('/');
			}
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	/**
	 * For payment failure or error on hosted page payment processor transaction.
	 */
	public function epdq_failed($inv_id=null, $hash=null)
	{
		$this->Session->setFlash('There was an issue with your payment, please check your details and try again.');
		
		$this->redirect(array('controller' => 'invoices', 'action' => 'pay', $inv_id, $inv_hash));
	}
	
	public function epdq_listener()
	{
		$debugging = false;
		$log = "Nothing happened.\n";
		
		try
		{
			if($this->request->is('post'))
			{
				$data = $this->request->data;
					
				if($this->_barclaycard->checkShaOutHash($data) && isset($data['orderID']) && isset($data['amount']) && isset($data['STATUS']))
				{
					$invoice_payment = $this->InvoicePayment->findByPaymentUniqueId($data['orderID']);
			
					if(!isset($invoice_payment['InvoicePayment']['invoice_id'])) {
						throw new Exception("Can't find invoice id.\n");
					}
			
					$an_invoice = $this->Invoice->findById($invoice_payment['InvoicePayment']['invoice_id']);
			
					switch($data['STATUS']) {
						case 2: $payment_status = false; break;
						case 5: $payment_status = true; break;
						case 9: $payment_status = true; break;
						case 93: $payment_status = false; break;
						default: $payment_status = false;
					}
					
					//save payment attempt 
					$a_payment = $this->Payment->create();
					$a_payment['Payment']['invoice_id'] = $invoice_payment['InvoicePayment']['invoice_id'];
					$a_payment['Payment']['unique_id'] = $data['orderID'];
					$a_payment['Payment']['client_id'] = $an_invoice['Invoice']['client_id'];
					$a_payment['Payment']['type'] = 'credit card';
					$a_payment['Payment']['amount'] = $data['amount'];
					$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
					$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
					$a_payment['Payment']['processor_response'] = json_encode($data);
					$this->Payment->save($a_payment);
						
					$payment_type = 'credit card';
					$amount = $data['amount'];
					$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
			
					if($payment_status)
					{
						//credit amount to invoice account
						$an_invoice['Invoice']['credit'] = floatval($an_invoice['Invoice']['credit']) + $amount;
							
						//mark invoice as paid if  total credit > invoice total
						if(floatval($an_invoice['Invoice']['total']) <= floatval($an_invoice['Invoice']['credit']))
						{
							$an_invoice['Invoice']['paid'] = 1;
						}
							
						$this->Invoice->save($an_invoice);
							
						// Build array of client email addresses
						$addresses = $this->decodeAddresses($an_invoice['Client']['email']);
			
						//full or partial payment of invoice account?
						if($an_invoice['Invoice']['paid'] == 1)
						{
							$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Successfully Paid';
								
							//admin notif for full payment of invoices
							$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
							$this->sendAdminEmail($subject, $message);
								
							//client notif
							$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for settling invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
								
							$message .=	'<p>Payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
			
							$message .=	'<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<p style="font-size: 0.7em;">
								www.stsonline.uk.com <br />
								+44 1656 513 046 <br />
								STS-Online Ltd <br />
								8a Dunraven Place, <br />
								Bridgend, <br />
								CF31 1JD, UK
								</p>
								';
							$result = $this->sendEmail($subject, $message, $addresses);
								
						}else{
								
							$subject = 'Invoice '.$an_invoice['Invoice']['id'].' Partially Paid';
			
							$curr_sym = $this->currencies[$an_invoice['Client']['default_display_currency']]['symbol'];
							$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
							$remaining_balance = round( ((floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']))) / $conv_rate, 2);
							$invoice_url = Router::url('/', true).'invoices/pay/'.$an_invoice['Invoice']['id'].'/'.sha1($an_invoice['Invoice']['id'].$secret_phrase);
			
							//client notif
							$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for your partial payment towards invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>';
								
							if($payment_type == 'credit card')
							{
								$message .=	'<p>Partial payment for the invoice amount has been processed from your card in GBP (if billed in other currencies, your bank will automatically convert the payment into GBP), and will show up on your card statement as "STS Online Services Ltd".</p>';
							}
							else if($payment_type == 'bacs transfer')
							{
								$message .=	'<p>Thank you for your payment towards the invoice via BACS transfer. A member of the STS Accounts team will verify your payment and update the invoice for you.</p>';
							}
			
							$message .=	'<p>The balance remaining to be paid on this invoice is '.$curr_sym.$remaining_balance.'</p>
								<p>You can pay easily and securely through our website, just click the link below, or alternatively you can call our customer support during office hours and make a payment over the phone.</p>
								<p><br />
									<a href="'.$invoice_url.'"><span class="button_example">Pay online now</span></a>
									<br />
								</p>
			
								<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
									<p style="font-size: 0.9em; color: #b4b4b4;">www.stsonline.uk.com</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 513 046</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">STS-Online Ltd</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">8a Dunraven Place</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
								</div>
								';
							$result = $this->sendEmail($subject, $message, $addresses);
								
						}
			
						$log = "Payment accepted.\n";
			
					}
					else
					{
						$log = "Payment declined.\n";
					}
					
				}
				else
				{
					$log = "Hashes don't match.\n";
				}
			}
			else
			{
				$log = "Not a POST.\n";
			}
		}
		catch(Exception $e)
		{
			$log = $e->getMessage()."\n";
		}
		
		file_put_contents(LOGS.'/epdq.log', date("Y-m-d H:i:s")." - ".$_SERVER['REMOTE_ADDR']." - ".$log, FILE_APPEND);
		exit;
	}
			
	public function paypal_process($inv_id=null, $hash=null)
	{
		$this->set('title_for_layout','PayPal Payment');
		$this->layout = 'IndexLayout';
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		if(isset($inv_id) && isset($hash))
		{
			$inv_hash = sha1($inv_id.$secret_phrase);
			if($inv_hash == $hash)
			{
				$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));
				
				$this->set('invoice',$an_invoice);
			}
			else
			{
				$this->redirect('/');
			}
		}
		else
		{
			$this->redirect('/');
		}
	}
	
	public function paypal_ipn_listener()
	{		
		$dumpfile = fopen('/var/www/stsonline.uk.com/new/ipn.log','a');
		fwrite($dumpfile,'REQUEST: '.json_encode($this->request->data));
		fclose($dumpfile);
		
		$this->layout = 'ajax';
		
		// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
		// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
		// paypal.mode in settings can be:
		// live - live
		// debug - live with debugging
		// test - sandbox with debugging
		
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		
		if($this->_settings['paypal.mode'] == 'test') {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			$paypal_debug = true;
		} else if($this->_settings['paypal.mode'] == 'debug') {		
			$paypal_debug = true;
		}
		
		define("PAYPAL_LOG_FILE", "./ipn.log");
		
		// Read POST data
		// reading posted data directly from $_POST causes serialization
		// issues with array data in POST. Reading raw POST data from input stream instead.
		/*$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}*/
		
		// Read POST data the CakePHP way.
		$myPost = $this->request->data;

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		
		// Post IPN data back to PayPal to validate the IPN data is genuine
		// Without this step anyone can fake IPN data
				
		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			return FALSE;
		}
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		
		if($paypal_debug) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
		
		// CONFIG: Optional proxy configuration
		//curl_setopt($ch, CURLOPT_PROXY, $proxy);
		//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
		// of the certificate as shown below. Ensure the file is readable by the webserver.
		// This is mandatory for some environments.
		
		//$cert = __DIR__ . "./cacert.pem";
		//curl_setopt($ch, CURLOPT_CAINFO, $cert);
		
		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) // cURL error
		{
			if($paypal_debug) {
				error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, PAYPAL_LOG_FILE);
			}
			curl_close($ch);
			exit;
		
		} else {
			// Log the entire HTTP response if debug is switched on.
			if($paypal_debug) {
				error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, PAYPAL_LOG_FILE);
				error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, PAYPAL_LOG_FILE);
		
				// Split response headers and payload
				list($headers, $res) = explode("\r\n\r\n", $res, 2);
			}
			curl_close($ch);
		}
		
		// Inspect IPN validation result and act accordingly
		
		if (strcmp ($res, "VERIFIED") == 0) {
			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment and mark item as paid.
		
			// assign posted variables to local variables
			//$item_name = $_POST['item_name'];
			//$item_number = $_POST['item_number'];
			//$payment_status = $_POST['payment_status'];
			//$payment_amount = $_POST['mc_gross'];
			//$payment_currency = $_POST['mc_currency'];
			//$txn_id = $_POST['txn_id'];
			//$receiver_email = $_POST['receiver_email'];
			//$payer_email = $_POST['payer_email'];
			
			// Pull relevant invoice. PayPal item_number field ought to be the invoice id.
			$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$myPost['item_number']))));

			if($myPost['payment_status'] == 'Completed')
			{
				$payment_status = true;
			}
			else
			{
				$payment_status = false;
			}
			
			if($myPost['mc_currency'] != 'GBP')
			{
				$amount = $myPost['mc_gross'] * $this->currencies[$myPost['mc_currency']]['base_exchange_rate'];
			}
			else
			{
				$amount = $myPost['mc_gross'];
			}
			
			
			//save payment attempt
			$a_payment = $this->Payment->create();
			$a_payment['Payment']['invoice_id'] = $an_invoice['Invoice']['id'];
			$a_payment['Payment']['client_id'] = $an_invoice['Invoice']['client_id'];
			$a_payment['Payment']['type'] = 'PayPal';
			$a_payment['Payment']['amount'] = $amount;
			$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
			$a_payment['Payment']['status'] = $payment_status ? 'paid' : 'declined';
			$a_payment['Payment']['processor_response'] = json_encode($myPost);
			$this->Payment->save($a_payment);
			
			if($payment_status)
			{
				//payment succeeded
					
				//credit amount to invoice account
				$an_invoice['Invoice']['credit'] = floatval($an_invoice['Invoice']['credit']) + $amount;
					
				//mark invoice as paid if  total credit > invoice total
				if(floatval($an_invoice['Invoice']['total']) <= floatval($an_invoice['Invoice']['credit']))
				{
					$an_invoice['Invoice']['paid'] = 1;
				}
					
				$this->Invoice->save($an_invoice);
				
				// Build array of client email addresses
				$addresses = $this->decodeAddresses($an_invoice['Client']['email']);
				
				//full or partial payment of invoice account?
				if($an_invoice['Invoice']['paid'] == 1)
				{
					//admin notif for full payment of invoices
					$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
					$this->sendAdminEmail('Invoice '.$an_invoice['Invoice']['id'].' Successfully Paid', $message);
						
					//client notif
					$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for settling invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>
			
								<p>Payment for the invoice amount has been received from PayPal.</p>
			
								<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<p style="font-size: 0.7em;">
								www.stsonline.uk.com <br />
								+44 1656 513 046 <br />
								STS-Online Ltd <br />
								8a Dunraven Place, <br />
								Bridgend, <br />
								CF31 1JD, UK
								</p>
								';
					
					// Send to all client emails
					$result = $this->sendEmail('Invoice '.$an_invoice['Invoice']['id'].' Successfully Paid', $message, $addresses);

					
				}else{
					//admin notif for partial payment of invoices
					$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
					$this->sendAdminEmail('Invoice '.$an_invoice['Invoice']['id'].' Partially Paid', $message);
						
					$curr_sym = $this->currencies[$an_invoice['Client']['default_display_currency']]['symbol'];
					$conv_rate = floatval($this->currencies[$an_invoice['Client']['default_display_currency']]['base_exchange_rate']);
					$remaining_balance = round( ((floatval($an_invoice['Invoice']['total']) - floatval($an_invoice['Invoice']['credit']))) / $conv_rate, 2);
					$invoice_url = Router::url('/', true).'invoices/pay/'.$an_invoice['Invoice']['id'].'/'.sha1($an_invoice['Invoice']['id'].$secret_phrase);
						
					$this->set('remaining_balance',$remaining_balance);
					$this->set('curr_sym',$curr_sym);
					$this->set('invoice_url',$invoice_url);
						
					//client notif
					$message = '
								<p>Dear '.$an_invoice['Client']['firstname'].',</p>
								<p>Thanks for your partial payment towards invoice no. STS'.$an_invoice['Invoice']['id'].'.</p>
			
								<p>Partial payment for the invoice amount has been received from PayPak.</p>
			
								<p>The balance remaining to be paid on this invoice is '.$curr_sym.$remaining_balance.'</p>
								<p>You can pay easily and securely through our website, just click the link below, or alternatively you can call our customer support during office hours and make a payment over the phone.</p>
								<p><br />
									<a href="'.$invoice_url.'"><span class="button_example">Pay online now</span></a>
									<br />
								</p>
			
								<p>Thanks for your business!</p>
								<p>Yours Sincerely, <br /></p>
								<p>Accounts Team</p>
								<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
									<p style="font-size: 0.9em; color: #b4b4b4;">www.stsonline.uk.com</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 513 046</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">STS-Online Ltd</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">8a Dunraven Place</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
									<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
								</div>
								';
					// Send to all client emails
					$result = $this->sendEmail('Invoice '.$an_invoice['Invoice']['id'].' Partially Paid', $message, $addresses);
				}
					
			}else
			{
				//payment failed
				$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
				$inv_id = $an_invoice['Invoice']['id'];
				$inv_hash = sha1($inv_id.$secret_phrase);
					
				$message = js_beautify(json_encode(array($an_invoice, $a_payment)));
				$this->sendAdminEmail('Invoice '.$an_invoice['Invoice']['id'].' Payment Attempt Failed', $message);
			}
			
			
			if($paypal_debug) {
				error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, PAYPAL_LOG_FILE);
			}
		} else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
			// Add business logic here which deals with invalid IPN messages
			if($paypal_debug) {
				error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, PAYPAL_LOG_FILE);
			}
		}
	}
	
	public function history()
	{
		$this->set('title_for_layout','Invoice History');
		$this->layout = 'InvoiceHistory';
		
		$user = $this->Session->read('Auth.User');
		
		if($user['Client']['id'] !== null)
		{
			$invoices = $this->Invoice->find('all',array('conditions'=>array('Invoice.client_id'=>$user['Client']['id'])));
			$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
			
			foreach($invoices as $key => $invoice)
			{
				if($invoice['Invoice']['paid'] == 1)
				{
					$invoices[$key]['Invoice']['pay_link'] = '(Paid)';
				}
				else
				{
					$invoices[$key]['Invoice']['pay_link'] = '<a href="'.$this->base.'/invoices/pay/'.$invoice['Invoice']['id'].'/'.sha1($invoice['Invoice']['id'].$secret_phrase).'" target="_blank">Pay now</a>';
				}
			}
			
			
			$this->set('invoices',$invoices);
		}
		else
		{
			$this->redirect(array('controller' => 'index','action' => 'index'));
		}
		
	}

	public function payment_success($inv_id=null, $code=null)
	{
		//some kind of id check here!!
		if($code!='sts123456')
		{
			$this->redirect(array('action' => 'no_invoice_id'));
		}
		$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));
		if( empty($an_invoice))
		{
			$this->redirect(array('action' => 'no_invoice_id'));
		}
		//mark invoice as paid
		$an_invoice['Invoice']['paid'] = 1;
		$this->Invoice->save($an_invoice);
		$a_payment = $this->Payment->create();
		$a_payment['Payment']['invoice_id'] = $an_invoice['Invoice']['id'];
		$a_payment['Payment']['client_id'] = $an_invoice['Invoice']['client_id'];
		$a_payment['Payment']['type'] = 'credit card';
		$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
		$this->Payment->save($a_payment);
		
		$this->set('invoice',$an_invoice);
		$this->layout = 'ajax';
		
	}
	
	public function payment_fail($inv_id=null)
	{
		$an_invoice = $this->Invoice->find('first', array('conditions'=> array(array('Invoice.id'=>$inv_id))));	
		if( empty($an_invoice))
		{
			$this->redirect(array('action' => 'no_invoice_id'));
		}
		//mark invoice as paid
		$this->set('invoice',$an_invoice);	
		$this->layout = 'ajax';
	}
	public function paid_before($inv_id=null)
	{
		$this->set('number', $inv_id);
		$this->layout = 'IndexLayout';
	}
	public function no_invoice_id()
	{
		//redirect to stsltd.com
		$this->redirect('http://www.google.co.uk');
	}
	public function mask_id($number=null)
	{
		//get the time in seconds from 1970
		//take digits 1 to 4 (0 is first),add invoice id to end 
		$a = strtotime('now');
		$invstart = substr($a,1,4).$number;
		$this->unmask_id( $invstart );
	}
	public function unmask_id($inv_id=null)
	{
		if( $inv_id != null )
		{
			$id = substr($inv_id,4);
			$stop = true;
			return substr($inv_id,4);
		}
	}
	
	private function getUnpaidInvoiceTotal() {
		$query = "SELECT SUM(total) - SUM(credit) AS total
				FROM invoices
				WHERE paid = 0;";
		
		$result = ConnectionManager::getDataSource('default')->fetchAll($query);
		
		if(is_null($result[0][0]['total'])) {
			return 0.00;
		}
		
		return $result[0][0]['total'];
	}
	
	private function decodeAddresses($addresses)
	{
		if($this->isJson($addresses))
		{
			$emails = json_decode($addresses);
			// protection in case json_decode() returns a string.
			if(!is_array($emails))
			{
				$emails[0] = $emails;
			}
		}
		else
		{
			$emails[0] = $addresses;
		}
		return $emails;
	}
	private function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	private function getPaymentProcessor() {
		$setting = $this->_settings['invoice.payment_processor'];
		
		$processors = array('_barclaycard','_paypoint','_worldpay');
		
		if(in_array($setting, $processors)) {
			return $setting;
		} else {
			return '_worldpay';
		}
	}
	
	private function validateDate($date, $format = 'Y-m-d H:i:s')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	private function ordinal($number) {
	    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
	    if ((($number % 100) >= 11) && (($number%100) <= 13))
	        return $number. 'th';
	    else
	        return $number. $ends[$number % 10];
	}
	
	private function get_period($type = null,$period = "") {
		$now = new DateTime();
		
		$periods = array(-1,0,1);
		
		if(!in_array($period,$periods)) {
			return "";
		}
		
		switch($type) {
			case 0:
				// Monthly
				$format = "F Y";
				$modify = "month";
				break;
			case 1:
				// Yearly
				$format = "Y";
				$modify = "year";
				break;
			default:
				return "";
		}
		
		switch($period) {
			case -1:
				$now->modify("last day of -1 $modify");
				break;
			case 0:
				// No modify call required, case included for readability
				break;
			case 1:
				$now->modify("last day of +1 $modify");
				break;
		}
		
		return $now->format($format);
	}
	
	public function pay_partial($invoice_id = null)
	{
		if ($this->request->is('post'))
		{
			// need to edit amount
			$user = $this->Session->read('Auth.User');
			$role_id = isset($user) ? $user['role_id'] : 1; //role 1 is user
			
			if($role_id == 2 || $role_id == 3) {
				$id = $this->request->data['Invoice']['id'];
				$pay_amount = $this->request->data['Invoice']['pay_amount'];
				$invoice = $this->Invoice->findById($id);
				
				if(!empty($invoice)) {
					if(is_numeric($pay_amount))
					{
						$pay_amount = round(floatval($pay_amount),2);
						$balance = round(floatval($invoice['Invoice']['total'])-floatval($invoice['Invoice']['credit']),2);
						if($balance >= $pay_amount)
						{
							//create a payment receipt
							$a_payment = $this->Payment->create();
							$a_payment['Payment']['invoice_id'] = $invoice['Invoice']['id'];
							$a_payment['Payment']['type'] = 'manual';
							$a_payment['Payment']['amount'] = $pay_amount;
							$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
							$a_payment['Payment']['status'] = 'marked paid';
							$a_payment['Payment']['processor_response'] = 'Manual payment from invoices manager, logged in user was '.$user['username'].", amount paid was ".$pay_amount;
							if($this->Payment->save($a_payment)) {
								//update invoice data, and save
								$invoice['Invoice']['credit'] = floatval($invoice['Invoice']['credit']) + $pay_amount;
								$invoice['Invoice']['paid'] = ($balance == $pay_amount);
								if($this->Invoice->save($invoice)) {
									$this->Session->setFlash("Invoice #".$invoice['Invoice']['id'].(($balance == $pay_amount) ? " marked as paid." : " has been partially paid"));
								}
								else {
									$this->Session->setFlash("Error saving invoice.");
								}
							}
							else {
								$this->Session->setFlash("Error saving payment.");
							}
						}
						else {
							$this->Session->setFlash("Error amount entered is more than needs to be paid");
						}
					}
					else {
						$this->Session->setFlash("Not a valid pay amount");
					}
				}
				else {
					$this->Session->setFlash("No invoice found.");
				}
			}
			$this->redirect(array('action' => 'list_all'));
		}
		else 
		{
			$user = $this->Session->read('Auth.User');
			$role_id = isset($user) ? $user['role_id'] : 1; //role 1 is user
			
			if($role_id == 2 || $role_id == 3) {
				if($invoice_id!=null)
				{
					$invoice = $this->Invoice->findById($invoice_id);
				}
				$this->set("invoice_id", ((!empty($invoice)) ? $invoice_id : ""));
				$query = "Select id FROM invoices WHERE paid=0;";
				$all_ids_raw = $this->Invoice->query($query);
				$all_ids = array();
				foreach($all_ids_raw as $ids)
				{
					$all_ids[$ids['invoices']['id']] = $ids['invoices']['id'];
				}
				$this->set("all_ids", $all_ids);
			}
			else
			{
				$this->redirect($this->referer());
			}
		}
	}
}
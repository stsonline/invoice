<?php
class AjaxController extends AppController 
{

	var $name = "Ajax";
	var $components = array('RequestHandler',/*'Security'*/);
	var $layout = 'ajax';  // uses an empty layout
	var $autoRender=false; // renders nothing by default
	var $uses = array('Invoice', 'Payment');
	
	public function beforeFilter()
	{	
		//$this->Security->unlockedActions = array('controller'=>'ajax','action'=>'show_pdf');
		$this->Auth->allow('index','show_pdf');//clients can access to make payments
		parent::beforeFilter();
	}
	public function isAuthorized($user)
	{	
		return parent::isAuthorized($user);
	}
	// Default action
	function index() 
	{
		$this->redirect("../");
	}

	function functionCall() 
	{
		$server_action = $_POST['sa'];
		$invoice_id = $_POST['inv'];
		switch($server_action)
		{
			case 'paylink':
				echo $this->getpaylink($invoice_id); 
				break;
			case 'manualpayment':
				echo $this->manualpayment($invoice_id); 
				break;
			default:
				echo "response from server, AjaxController\n action: ".$server_action."\n id:".$invoice_id;
				break;
		}
		exit;
	}
	
	function manualpayment($invoice_id)
	{
		$invoice = $this->Invoice->findById($invoice_id);
		
		$balance = round(floatval($invoice['Invoice']['total'])-floatval($invoice['Invoice']['credit']),2);
		$user = $this->Session->read('Auth.User');
		
		//create a payment receipt
		$a_payment = $this->Payment->create();
		$a_payment['Payment']['invoice_id'] = $invoice['Invoice']['id'];
		$a_payment['Payment']['type'] = 'manual';
		$a_payment['Payment']['amount'] = $balance;
		$a_payment['Payment']['date'] = date('Y-m-d H:i:s');
		$a_payment['Payment']['status'] = 'marked paid';
		$a_payment['Payment']['processor_response'] = 'Manual payment from invoices manager, logged in user was '.$user['username'];
		$this->Payment->save($a_payment);
		
		//update invoice data, and save
		$invoice['Invoice']['credit'] = floatval($invoice['Invoice']['credit']) + $balance;
		$invoice['Invoice']['paid'] = true;
		$this->Invoice->save($invoice);
		
		
		return '{"success":true}';
	}
	
	function getpaylink($id)
	{
	
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		$url = Router::url('/', true).'invoices/pay/'.$id.'/'.sha1($id.$secret_phrase);
		return $url;
	}

	// Sample action that does return JSON
	function JsonCall() 
	{
		$this->respond(array('first'=>'Hello', second=>'World'), true);
	}

	// The magic is here. When not called, no view is rendered.
	// When called it renders message.ctp,
	// content set to $message, or json_encode($message), respectively
	function respond($message=null, $json=false) 
	{
		if ($message!=null) 
		{
			if ($json==true) 
			{
				$this->RequestHandler->setContent('json', 'application/json');
				$message=json_encode($message);
			}
			$this->set('message', $message);
		}
		$this->render('message');
	}

	// You will need this to disable debug output appended to the view by CakePHP.
	// Otherwise it will mess up your returned messages.
/*	function  __construct() 
	{
		parent::__construct();
		Configure::write('debug', 0); // disable debug output in AJAX responses!
	}
	*/
}
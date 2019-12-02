<?php
App::uses('AdminLayoutController', 'Controller');

class ResponsesController extends AdminLayoutController {
		
	public $components = array('Security');
	
	public $uses = array('Response','Invoice');
	
	public $helpers = array('Html','Form','Paginator');
	
	public $paginate = array(
			
			'limit' => 25,
			'recursive' => 0,			
			'order' => array(
					'Response.created' => 'desc'
			)
	);
	
	public function beforeFilter()
	{
		$this->Auth->allow( 's_image');//email clicks have access
		parent::beforeFilter();
	}
	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}
	
	public function index($invoice_id=null)
	{
		$responselist = $this->paginate('Response',array('Response.invoice_id' => $invoice_id));
		
		$inv = $this->Response->Invoice->find('first',array('conditions' => array('Invoice.id' => $invoice_id)));
		
		$this->set('invoice',$inv);
		$this->set('responses',$responselist);
		
		//$this->layout='default';
	}
/*	
	public function s_payment()
	{
		$client=$_GET['clt'];		
		$inv = $_GET['inv'];
				
		$a_response = $this->Response->find('first',array('conditions' => array('Response.client_id' => $client, 'Response.invoice_id' => $inv)));
	
		if(isset($a_response['Invoice']))//if the invoice exists
		{		
			//if the user hasn't previously responded create and add to the table
			if( isset($a_response['Response']) && ($a_response['Response']['follow'] == 0))
			{
					$a_response['Response']['follow'] = 1;
					$a_response['Response']['modified'] = date('Y-m-d H:i:s');
					$this->Response->save($a_response);
					//add 1 to total follows
					$a_campaign = $this->Response->Campaign->find('first',array('conditions' => array('id' => $camp)));
					$a_campaign['Campaign']['follow']++;
					$this->Response->Campaign->save($a_campaign['Campaign']);
			}
			$this->redirect("http://dev.sinclairtechnologysolutions.com/email_invoices/payments/pay/".$inv['Invoice']['id']);  //redirect to payment page
		}
		$this->redirect('http://dev.sinclairtechnologysolutions.com/email_invoices');
	}
*/
	public function s_image()
	{
		//src="http://dev.sinclairtechnologysolutions.com/email_invoices/responses/s_image?iv=76767&rem=1"
		$inv_no=$_GET['iv'];
		$reminder_no = $_GET['rem'];
		$a_response = $this->Response->find('first',array('conditions' => array('Response.invoice_id' => $inv_no,'Response.reminder_no'=>$reminder_no)));

		//if the user is in database mark as viewed and send image
		if( isset($a_response['Response']) && ($a_response['Response']['view'] == 0))
		{
			//user has viewed
			$a_response['Response']['view'] = 1;
			$a_response['Response']['modified'] = date('Y-m-d H:i:s');
			$this->Response->save($a_response);

		}
		$imageFile = 'img/cleardot.gif';//could just use default.jpg, change to png  one pixel

		$file_parts = pathinfo('cleardot.gif');

		switch($file_parts['extension'])
		{
			case "jpg":
				header('Content-type: image/jpeg');
				$im = imagecreatefromjpeg($imageFile);
				imagejpeg($im);
				break;
			case "gif":
				header('Content-type: image/gif');
				$im = imagecreatefromgif($imageFile);
				imagegif($im);
				break;
			case "png":
				header('Content-type: image/png');
				$im = imagecreatefrompng($imageFile);
				imagepng($im);
				break;
		}

		exit;
	}

}
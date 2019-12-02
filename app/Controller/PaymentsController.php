<?php
App::uses('AdminLayoutController', 'Controller');

class PaymentsController extends AdminLayoutController 
{
		
	public $components = array('Security');
	
	public $uses = array('Payment','Client','Invoice');
	
	public $helpers = array('Html','Form');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow('payment_setamount','getpaylink');
	}
	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}
	
	public function view_details($invoice_id=null)
	{
		$a_payment = $this->Payment->find('first', array('conditions'=> array(array('Payment.invoice_id'=>$invoice_id))));
		$allpayments = $this->Payment->find('all', array('conditions'=> array(array('Payment.invoice_id'=>$invoice_id)), 'order'=>'Payment.id desc'));
		
		$this->set('payment',$a_payment);
		$this->set('allpayments',$allpayments);
	}
	
	public function view_client($client_id=null)
	{
		$this->set('payments',$this->Payment->find('all', array('conditions'=> array(array('Payment.client_id'=>$client_id)))));

		$this->set('client',$this->Client->find('first', array('conditions'=> array(array('Client.id'=>$client_id)))));
	}
	public function index()
	{
		$this->set('payments',$this->Payment->find('all'));
	}
	
	//---------------Unfinished--------------------
	//
	//
	public function payment_setamount($client_id=null)
	{
		$a_client = $this->Client->findById($client_id);
		
		if( ! empty($a_client))
		{
			if($this->request->is('post'))
			{
				//get form data calculate
				
				$new_payment = $this->request->data['Payment'];	
					
				$a_payment['Payment']['client_id'] = $new_payment['client_id'];				
				$a_payment['Payment']['amount'] = $new_payment['amount'];
				$a_payment['Payment']['card'] = $new_payment['card'];
				$a_payment['Payment']['invoice_id'] = null;
				$a_payment['Payment']['created'] = date('Y-m-d H:i:s');
					
				if ($this->Payment->save($a_payment))
				{
					$this->Session->setFlash('Payment has been saved.');
					$this->redirect(array('action' => 'thankyou',$client_id, $payment_id));
				}
				else
				{
					$this->Session->setFlash('Unable to add Invoice.');
				}
					
			}
			
		}
		
		$this->set('client',$a_client);
		$this->layout = 'ajax';
	}
	//----------------------------------------------------

}
<?php
class ContactController extends AppController 
{
 	public $layout = 'IndexLayout';
    public $components = array('Email');
    public $helpers = array('Form');
	public $uses = array('Quote', 'ServiceItem', 'Feedback');
    
    public $name = 'Contact';
 
    public function beforeFilter()
    {
    	parent::beforeFilter();
    	$this->layout = 'PageLayout';
    }
    
    public function send() 
    {
        /*if(!empty($this->data)) 
        {
           		//admin notif form submission
           		$message = js_beautify(json_encode($this->data));
           		$this->sendAdminEmail('Form Submission', $message);
           		
				$this->set('smtp_errors', $smtp_errors = $this->Email->smtpError);
				
				die(json_encode(array('success'=>true, 'smtp_errors'=>$smtp_errors)));
        }else
        {
        	$this->redirect('/contact');
        }*/
    }
 
    function index() 
    {
    	if(!empty($this->data))
    	{
    		//admin notif form submission
    		$message = js_beautify(json_encode($this->data));
    		$this->sendAdminEmail('Form Submission', $message);
    		 
    		$this->set('smtp_errors', $smtp_errors = $this->Email->smtpError);
    	
    		//die(json_encode(array('success'=>true, 'smtp_errors'=>$smtp_errors)));
    		$this->redirect('/contact/success');
    	}else
    	{
    		$this->redirect('/get-in-touch');
    	}
    }
    
    function success(){}
    
    function feedback() {
    	$data = $this->request->data;
    	
    	if(!empty($data)) {

    		$this->Feedback->create();
    		$data['Feedback']['created'] = date('Y-m-d H:i:s');
    		$this->Feedback->save($data);
    
    		$message = js_beautify(json_encode($data));
    		$this->sendAdminEmail('Feedback Form Submission: ', $message);
    		 
    		$this->set('smtp_errors', $smtp_errors = $this->Email->smtpError);
    		 
    		//die(json_encode(array('success'=>true, 'smtp_errors'=>$smtp_errors)));
    		$this->redirect('/contact/success');
    	} 
    }
    
    function quote() {
    	$data = $this->request->data;
   		
    	if(!empty($data)) {
/*var_dump($data);die;
    		foreach($data['quote']['items'] as $type => $value) {
    			if(is_array($value)) {
    				foreach($value as $key => $val) {
    					$item = $this->ServiceItem->findById($val);
    					
    					$data['quote']['items'][$type][$key] = $item['ServiceItem']['name'];
    				}
    			} else {
    				$item = $this->ServiceItem->findById($value);
    				
    				$data['quote']['items'][$type] = $item['ServiceItem']['name'];
    			}
    		}*/
    		
    		$this->Quote->create();
    		$quote['Quote']['type'] = $data['quote']['type'];
    		$quote['Quote']['items'] = json_encode($data['quote']['items']);
    		$quote['Quote']['name'] = $data['quote']['name'];
    		$quote['Quote']['email'] = $data['quote']['email'];
    		/*$quote['Quote']['company'] = $data['quote']['company'];
    		$quote['Quote']['number'] = $data['quote']['number'];*/
    		$quote['Quote']['message'] = $data['quote']['message'];
    		$quote['Quote']['amount'] = $data['quote']['amount'];
    		$quote['Quote']['ip'] = $data['quote']['ip'] = $this->request->clientIp();
    		$quote['Quote']['user_agent'] = $data['quote']['user_agent'] = $this->request->header('User-Agent');
    		$this->Quote->save($quote);
    		
    		$message = js_beautify(json_encode($data));
    		$this->sendAdminEmail('Quote Form Submission: ', $message);
    		 
    		$this->set('smtp_errors', $smtp_errors = $this->Email->smtpError);
    		 
    		//die(json_encode(array('success'=>true, 'smtp_errors'=>$smtp_errors)));
    		$this->redirect('/contact/success');
    	} else {
    		$this->redirect('/get-in-touch');
    	}
    }
}


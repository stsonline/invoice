<?php
/**
 * PayPoint JSON Api wrapper class
 *   
 * @author contact@sinclairtechnologysolutions.com
 *
 */
class PaypointAPI
{
	
	protected $options = array
	(
		'apiUrl'=>"",
		'installation_id'=>'',
		'secpay_user'=>'',
		'secpay_password'=>'',
		'secpay_mode'=>'live',//test|live
		'ReqMode'    => "",
		'proxyUrl' => "",
		'proxyPort' => "",
		'proxyType' => CURLPROXY_SOCKS5 //'CURLPROXY_SOCKS4', 4 ; 'CURLPROXY_HTTP', 0 ; 'CURLPROXY_SOCKS5', 5
	);
	
	protected static $instance = null;
	
	
    /********************
     * API functions	*
     * ******************
     */
	public function preAuthPayment($card_info, $amount)
    {
    	$json = '{}';
    	$result = $this->start($card_info, $amount, $mode='pre_auth');
    	
    	$json = json_encode($result);
    	
    	$auth_success = $result['intStatus'] == 1 ? true: false;
    	$cv2_success = isset($result['intCV2']) && $result['intCV2'] == 1 ? true: false;
    	$avs_success = isset($result['intAVS']) && $result['intAVS'] == 1 ? true: false;
    	$fraud_score = isset($result['fltFraudScore']) ? $result['fltFraudScore']  : 0;
    	
    	return array($auth_success,$cv2_success, $avs_success, $fraud_score, $json);
    }
    
	public function processPayment($card_info, $amount)
    {
    	return $this->start_secpay($card_info, $amount);
    }
	

	/********************
     * Class management functions	*
     * ******************
     */
	public static function getInstance($AutoCreate = false)
    {
        if ($AutoCreate===true && !self::$instance) {
            self::init();
        }
        return self::$instance;
    }
    
	public static function init()
    {
        return self::setInstance(new self());
    }

    public static function setInstance($instance)
    {
        return self::$instance = $instance;
    }
	
    /**
     * Override all options with $Options array
     * @param unknown_type $Options
     */
    public function setOptions($Options)
    {
        $this->options = array_merge($this->options,$Options);
    }


    /**
     * 
     * Return instance options array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set an option for the library
     * 
     * @param string $Name
     * @param mixed $Value
     * @throws Exception
     * @return void
     */  
    public function setOption($Name, $Value)
    {
        if (!isset($this->options[$Name])) {
            throw $this->newException('Unknown option: ' . $Name);
        }
        $this->options[$Name] = $Value;
    }

    /**
     * Get an option from the library
     *
     * @param string $Name
     * @throws Exception
     * @return mixed
     */
    public function getOption($Name)
    {
        if (!isset($this->options[$Name])) {
            throw new CakeException('Unknown option: ' . $Name);
        }
        return $this->options[$Name];
    }
    
	
	/********************
     * Util functions	*
     * ******************
     */
	
    public function start_secpay($card_info, $amount, $mode='process')
    {
    try {
    		$test_mode = $this->getOption('secpay_mode')=='test'? true :false;	//if in test mode transaction will always succeed
    	
    		$test_status = $test_mode ? "test_status=true," : "";
    		
    		$merchant_user_id = $this->getOption('secpay_user');
    		$merchant_vpn_password = $this->getOption('secpay_password');
    		
    		$trans_id = uniqid();
    		$cardname = $card_info['card-name'];
    		$cardnumber = $card_info['card-number'];
    		$cardexp = $card_info['card-exp-month'].substr($card_info['card-exp-year'],2);
    		$cardissue = $card_info['card-issue'];
    		$cardstart = $card_info['card-start-month'].substr($card_info['card-start-year'],2);
    		$cardaddress =  'name='.$card_info['customer-name'].
    						',company='.$card_info['company-name'].
    						',addr_1='.$card_info['address1'].
    						',addr_1='.$card_info['address2'].
				    		',city='.$card_info['city'].
				    		',state='.$card_info['state'].
				    		',post_code='.$card_info['zipcode'].
				    		',country='.$card_info['country'].
				    		',tel=='.$card_info['phone'].
				    		',email='.$card_info['email'];
    		$orderinfostring = 'Payment for Invoice';
    		$cardcvv = $card_info['cvv-code'];
    		$cardtype = $card_info['card-type'];
    		
		   include("xmlrpc-3.0.0.beta/lib/xmlrpc.inc");
		   /*
		   Declare the method, validateCardFull, of the SECVPN object to be used via XML RPC.
		   Other methods like this one can be added to handle other methods of the SECVPN object.
		   */
		   $f=new xmlrpcmsg('SECVPN.validateCardFull');
		   /*
		   Add the test parameters in the order specified
		   by the SECVPN.validateCardFull() method
		   */
		   $f->addParam(new xmlrpcval($merchant_user_id, "string")) ;		// Test MerchantId
		   $f->addParam(new xmlrpcval($merchant_vpn_password, "string")) ;		// VPN password
		   $f->addParam(new xmlrpcval($trans_id, "string")) ;		// merchants transaction id
		   $f->addParam(new xmlrpcval($_SERVER['REMOTE_ADDR'], "string")) ;	// The ip of the original caller
		   $f->addParam(new xmlrpcval($cardname, "string")) ;	// Card Holders Name
		   $f->addParam(new xmlrpcval($cardnumber, "string")) ;	// Card number
		   $f->addParam(new xmlrpcval($amount, "string")) ;		// Amount
		   $f->addParam(new xmlrpcval($cardexp, "string")) ;		// Expiry Date
		   $f->addParam(new xmlrpcval($cardissue, "string")) ;			// Issue (Switch/Solo only)
		   $f->addParam(new xmlrpcval($cardstart, "string")) ;			// Start Date
		   $f->addParam(new xmlrpcval($orderinfostring, "string")) ;			// Order Item String
		   $f->addParam(new xmlrpcval($cardaddress, "string")) ;			// Shipping Address
		   $f->addParam(new xmlrpcval($cardaddress, "string")) ;			// Billing Address
		   $f->addParam(new xmlrpcval($test_status."dups=false,cv2=$cardcvv", "string")) ;	// Options String
		   //$f->addParam(new xmlrpcval($test_status."dups=false,card_type=$cardtype,cv2=$cardcvv", "string")) ;	// Options String
		   /*
		   Create the XMLRPC client, using the server 'make_call', on the host 'www.secpay.com', via the https port '443'
		   */
		   $c=new xmlrpc_client("/secxmlrpc/make_call", "www.secpay.com", 443);
		   /*
		   Debugging is enabled for testing purposes
		   */
		   $c->setDebug(0);
		   $r=$c->send($f,20,"https");
		   if (!$r) {
		      throw new CakeException('Payment processing encountered a fatal error');
		   }
		   $v=$r->value();
		   /*
		   Display response or fault information
		   */
		   if (!$r->faultCode()) {
		      $v->scalarval()."<BR>";
		      htmlentities($r->serialize())."</PRE><HR>\n";
		   }
		   else {
		      
		      throw new CakeException('Payment processing encountered a fatal error. '."Code: ".$r->faultCode()." Reason '".$r->faultString());
		   }
		   $out = simplexml_load_string($r->payload);
		   $out = $out->params->param->value->string;
		   $out = substr($out,0,1)=='?' ? substr($out,1) : $out;
		   parse_str($out, $out_arr);
		   
		   switch($out_arr['code'])
		   {
			   	case 'N': //not authd
			   		return array(false, $out_arr);
			   		break;
		   		case 'A': //accepted
		   			return array(true, $out_arr);
		   			break;
		   			
		   		default: 
	   				return array(false, $out_arr);
	   				break;
		   }
		   
		   echo '<pre>';
		   print_r($out_arr);
		   echo '</pre>';
		   exit;
		   
		}catch(Exception $e)
		{
			
			$error_emails_list = 'contact@stsonline.uk.com';
		    $error_emails_list = explode(';', $error_emails_list);
		    
			foreach($error_emails_list as $support_email)
		    {
		    	//$result = $this->_email->send_mail($support_email, 'noreply@stsonline.uk.com', $subject, $message);
		    }
			
			
			die( 'ERROR: '.  $e->getMessage(). "\n\n". "Support email: contact@stsonline.uk.com");
			
		}
    	
    }
    
	
	public function start($card_info, $amount, $mode='process')
	{
		
		try {
		    $paypoint_installation_id = $this->getOption('installation_id');
		    
		    switch($card_info['Loan']['card_type'])
		    {
		    	case 'Visa': $card_type = 'VISA';
		    	case 'Mastercard': $card_type = 'MC';
		    	case 'VisaElectron': $card_type = 'UKE';
		    	case 'Solo': $card_type = 'SOLO';
		    	case 'Mastro': $card_type = 'MAESTRO';
		    } 
		    
			$responseComplete['strCartID'] = $_SERVER['HTTP_HOST'];
			$responseComplete['strDesc'] = $_SERVER['HTTP_HOST'];
			$responseComplete['intInstID'] =$paypoint_installation_id;//'253869';
			$responseComplete['fltAmount'] = $amount;
			
			$responseComplete['strCardNumber'] = $card_info['Loan']['card_number'];
			
			$responseComplete['strCardHolder'] = $card_info['Loan']['card_name'];
			$responseComplete['strPostcode'] = $card_info['User']['AppPostCode'];
			$responseComplete['strEmail'] = $card_info['User']['AppEmail'];
			
			$responseComplete['strAddress'] = $card_info['User']['AppHouseNumber'] . $card_info['User']['AppStreet'];
			$responseComplete['strCity'] = $card_info['User']['AppTown'];
			$responseComplete['strState'] = $card_info['User']['AppCounty'];
			
			$responseComplete['intCV2'] = $card_info['Loan']['card_cvv2'];
			//$responseComplete['strStartDate'] = '0111';//need to fix this to actuall pull in this data
			$responseComplete['strExpiryDate'] = $card_info['Loan']['card_expiry_month'].$card_info['Loan']['card_expiry_year'];
			$responseComplete['strCardType'] = $card_type;
			$responseComplete['strCountry'] =  'GB';
			$responseComplete['fltAPIVersion'] = '1.3';
			$responseComplete['strTransType'] = 'PAYMENT';
			$responseComplete['intTestMode'] =  '0';
			
			$auth_mode = $mode == "pre_auth" ? '2' : '1';
			$responseComplete['intAuthMode'] = $auth_mode;
		
			$responseComplete['strCurrency'] = 'GBP';
			
			$curlPostArgs = http_build_query($responseComplete);
			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_POST,1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPostArgs);
		    curl_setopt($ch, CURLOPT_URL,'https://secure.metacharge.com/mcpe/corporate');
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		    
		    $result = curl_exec($ch); 
		
		    if (curl_errno($ch)) {
		        $result = "ERROR:Curl error in transmission";
		    } else {
		        curl_close($ch);
		    }
			    
		    //reverse http_build_query and place resulting array in $result
		    parse_str($result, $result);
			
		    return $result;
			
		}catch(Exception $e)
		{
			
			$error_emails_list = 'test@sinclairtechnologysolutions.com';
		    $error_emails_list = explode(';', $error_emails_list);
		    
		    Registry::get('view_mail')->assign('error', '<p>'.$e->getMessage().'</p><p>'. debug_print_backtrace().'</p>');
		    Registry::get('view_mail')->assign('subject', Registry::get('settings.Company.company_name') .": Critical error occurred with payment processor");
		    
			foreach($error_emails_list as $support_email)
		    {
		    	$result = $this->_email->send_mail($support_email, 'noreply@thelendingplatform.com', $subject, $message);
		    }
			
			
			die( 'ERROR: '.  $e->getMessage(). "\n\n". "Support email: contact@thelendingplatform.com");
			
		}
	}
	

		
}




<?php
/**
 * Barclaycard ePDQ API class
 *   
 * @author andrew.cornelius@stsonline.uk.com
 *
 */
class BarclaycardAPI
{
	
	private $live_url = 'https://payments.epdq.co.uk/ncol/prod/orderdirect.asp';
	private $test_url = 'https://mdepayments.epdq.co.uk/ncol/test/orderdirect.asp';
	
	private $sha_in_key = 'stssha1keyforverification';
	private $sha_in_test = 'stssha1keyforverificationtest';
	
	private $sha_out_key = 'stssha1outforverification';
	private $sha_out_test = 'stssha1outfortesting';
	
	protected $options = array
	(
		'apiUrl'=>"",
		'installation_id'=>'',
		'pspid'=>'',
		'userid'=>'',
		'pswd'=>'',
		'test_mode'=>false,
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
    
	public function processPayment($card_info, $amount)
    {
    	$url = $this->getOption('test_mode') ? $this->test_url : $this->live_url;
    	
    	$postfields['PSPID'] = $this->getOption('pspid');
    	$postfields['ORDERID'] = uniqid();
    	$postfields['USERID'] = $this->getOption('userid');
    	$postfields['PSWD'] = $this->getOption('pswd');
    	$postfields['AMOUNT'] = intval($amount * 100);
    	$postfields['CURRENCY'] = 'GBP';
    	$postfields['CARDNO'] = $card_info['card-number'];
    	$postfields['ED'] = $card_info['card-exp-month'].$card_info['card-exp-year'][2].$card_info['card-exp-year'][3];
    	$postfields['CN'] = $card_info['customer-name'];
    	$postfields['EMAIL'] = $card_info['email'];
    	$postfields['CVC'] = $card_info['cvv-code'];
    	$postfields['OWNERADDRESS'] = $card_info['address1'];
    	$postfields['OWNERZIP'] = $card_info['zipcode'];
    	$postfields['OWNERTOWN'] = $card_info['city'];
    	$postfields['OWNERCTY'] = $card_info['state'];
    	$postfields['OPERATION'] = 'SAL';
    	$postfields['WITHROOT'] = 'Y';
    	$postfields['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
    	$postfields['RTIMEOUT'] = 90;   	
    	$postfields['SHASIGN'] = $this->generateShaHash($postfields);
    	
    	$query = http_build_query($postfields);
    	
    	$curl = curl_init($url);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_POST, true);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 100);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
    	
    	if(curl_errno($curl))
    	{
    		echo "curl error";
    		exit;
    	}
    	else
    	{
    		$response_string = curl_exec($curl);
    		$info = curl_getinfo($curl) ;
    		curl_close($curl);
    	}
    	
    	$response = simplexml_load_string($response_string);
    	
    	die("No response handling at present.");
    	
    	// Need to handle the request response here.
    	
    	exit;
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
        
    /**
     * Generates a SHA-1 hash to compare against transation data.
     * @param array $hashfields Post fields data for Barclaycard transaction.
     * @return string SHA1 hash of data;
     */
	public function generateShaHash($hashfields) {
		ksort($hashfields);
		
		$hashstring = '';
		
		$salt = $this->getOption('test_mode') ? $this->sha_in_test : $this->sha_in_key;
		
		foreach($hashfields as $key => $value) {
			$hashstring .= $key.'='.$value.$salt;
		}
		
		return strtoupper(sha1($hashstring));
	}
	
	/**
	 * Checks SHA-1 hash of fields against hash received.
	 * @param array $hashfields Response from Barclaycard
	 * @return boolean true if hash received matches hash generated, false otherwise. 
	 */
	public function checkShaOutHash($hashfields) {
		if(isset($hashfields['SHASIGN'])) {
			$received_hash = $hashfields['SHASIGN'];
			unset($hashfields['SHASIGN']);
			
			$generated_hash = $this->generateShaOutHash($hashfields);
			
			return $received_hash == $generated_hash;
		}
		return false;
	}
	
	private function generateShaOutHash($postfields) {
		foreach($postfields as $key => $value) {
			$hashfields[strtoupper($key)] = $value;
		}
		
		ksort($hashfields);
	
		$hashstring = '';
	
		$salt = $this->getOption('test_mode') ? $this->sha_out_test : $this->sha_out_key;
	
		foreach($hashfields as $key => $value) {
			if($value != "") {
				$hashstring .= strtoupper($key).'='.$value.$salt;
			}
		}
	
		return strtoupper(sha1($hashstring));
	}
    
		
}




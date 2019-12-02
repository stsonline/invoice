<?php
class InvoiceController extends AppController 
{
	// TODO Delete me
	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Invoice';
	
	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	
	public $layout = false;
	
	public function beforeFilter()
	{
	}
	
	public function index()
	{
	}
	
	public function invoice()
	{
		$this->set('organisation','Totality Commerce LLC');
		$this->set('invoice',unserialize('a:2:{s:7:"Invoice";a:14:{s:5:"total";s:0:"";s:6:"credit";s:4:"0.00";s:4:"paid";s:1:"0";s:5:"sends";s:1:"0";s:12:"viewed_email";s:1:"0";s:22:"viewed_email_timestamp";s:17:"CURRENT_TIMESTAMP";s:8:"allow_cc";s:1:"0";s:12:"allow_paypal";s:1:"0";s:10:"allow_bacs";s:1:"1";s:9:"client_id";s:2:"18";s:7:"created";s:19:"2014-12-11 09:39:03";s:10:"project_id";N;s:2:"id";s:4:"1234";s:3:"pdf";s:11:"STS1234.pdf";}s:8:"LineItem";a:2:{i:0;a:2:{s:4:"desc";s:4:"Test";s:6:"amount";s:3:"100";}i:1;a:2:{s:4:"desc";s:6:"Test 2";s:6:"amount";s:3:"210";}}}'));
	}
	
}
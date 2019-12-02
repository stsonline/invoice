<?php
App::uses('AdminLayoutController', 'Controller');
class ExportController extends AdminLayoutController
{
	
	public $uses = array('Client','Invoice');
	public function beforeFilter() 
	{		
		
		
	}

	public function inv()
	{
	
	
		
	}
	
	
	public function index()
	{
	
	
	
		isset($this->params['url']['key']) ? : exit;
		
		$this->Invoice->recursive = 1;
		$invoices = $this->Invoice->find("all");
		
		//Type,Customer Reference,Date,Customer Name,Reference,Ledger Account,Details,Net,VAT Rate,VAT,Total,Analysis Type 1,Analysis Type 2,Analysis Type 3
		$str = "Type,Customer Reference,Date,Customer Name,Reference,Ledger Account,Details,Net,VAT Rate,VAT,Total,Analysis Type 1,Analysis Type 2,Analysis Type 3\n";
		
		foreach($invoices as $invoice)
		{
			$str .= "Invoice,"; //type Invoice,
			$str .= "Cust-".$invoice['Invoice']['client_id'].",";
			$date = strtotime( $invoice['Invoice']['created'] );
			$date = date( 'd/m/Y', $date );
			$str .= $date.",";
			$str .= $invoice['Client']['firstname']." ".$invoice['Client']['lastname'].",";
			$str .= "Inv".$invoice['Invoice']['id'].",";
			$str .= ","; //ledger account

			$lineItems = array();
			foreach($invoice['LineItem'] as $lineItem) {
				$line['desc'] = $lineItem['desc'];
				$line['support_level'] = $lineItem['support_level'];
				$line['amount'] = $lineItem['amount'];

				$lineItems[] = implode(' - ', $line);
			}

			$str .= implode(' -- ', $lineItems).",";		
			$str .= $invoice['Invoice']['subtotal'].","; //net
			$str .= "Standard,"; //vat type
			$str .= $invoice['Invoice']['vat'].","; //vat
			$str .= $invoice['Invoice']['total'].","; //subtotal
			$str .= "\"\","; //anal1
			$str .= "\"\","; //anal2
			$str .= "\"\","; //anal3
			
			
			//var_dump($invoice);
			$str.="\n";
		
		}
			
		echo file_put_contents( WWW_ROOT."export1/invoices.csv",$str);
		//echo $str;
		exit;
	
		isset($this->params['url']['key']) ? : exit;
		
		$clients = $this->Client->find("all");
		
		//Reference,Company Name,Currency,Credit Limit,Main Address Type,Main Address Line 1,Main Address Line 2,Main Address Town,Main Address County,Main Address Post Code,Main Address Country,Main Contact Name,Main Contact Phone,Main Contact Type,Main Contact Mobile,Main Contact Email,Main Contact Fax,Address 2 Type,Address 2 Line 1,Address 2 Line 2,Address 2 Town,Address 2 County,Address 2 Post Code,Address 2 Country,VAT Number,Ledger Account,Payment Terms,Notes,Bank Account Name,Bank Account Sort Code,Bank Account Number,Bank Account IBAN,Bank Account BIC

		$str = "Reference,Company Name,Currency,Credit Limit,Main Address Type,Main Address Line 1,Main Address Line 2,Main Address Town,Main Address County,Main Address Post Code,Main Address Country,Main Contact Name,Main Contact Phone,Main Contact Type,Main Contact Mobile,Main Contact Email,Main Contact Fax,Address 2 Type,Address 2 Line 1,Address 2 Line 2,Address 2 Town,Address 2 County,Address 2 Post Code,Address 2 Country,VAT Number,Ledger Account,Payment Terms,Notes,Bank Account Name,Bank Account Sort Code,Bank Account Number,Bank Account IBAN,Bank Account BIC\n";

		foreach($clients as $client)
		{
			//var_dump($client);
			//echo "<BR><BR>";
			$str .= "Cust-".$client['Client']['id'].",";
			$str .= $client['Client']['organisation'].",";
			$str .= $client['Client']['default_display_currency'].",";
			$str .= "0,"; //credit limit
			$str .= $client['Client']['address1'].",";
			$str .= $client['Client']['address2'].",";
			$str .= $client['Client']['city'].",";
			$str .= $client['Client']['state'].",";
			$str .= $client['Client']['zipcode'].",";
			$str .= $client['Client']['country'].",";
			$str .= $client['Client']['address1'].",";
			$str .= $client['Client']['firstname']." ".$client['Client']['address1'].",";
			$str .= $client['Client']['phone'].",";
			$str .= $client['Client']['address1'].",";
			$str .= "Sales,"; //type is Purchasing or Sales.
			$str .= ","; //mobile
			$str .= $client['Client']['email'].",";
			$str .= ","; //fax
			$str .= ","; //address2 type
			$str .= ","; //address2
			$str .= ","; //address2
			$str .= ","; //address2 town
			$str .= ","; //address2 county
			$str .= ","; //address2 postcode
			$str .= ","; //address2 country
			$str .= ","; //VAT number
			$str .= ","; //ledger
			$str .= ","; //payment terms
			$str .= ","; //notes
			$str .= ","; //bank account name
			$str .= ","; //sort code
			$str .= ","; //bank number
			$str .= ","; //IBAN
			$str .= ","; //BIC
			
			
			$str.="\n";
		
		}
		echo( WWW_ROOT."export1/customers.csv");
		
		echo file_put_contents( WWW_ROOT."export1/customers.csv",$str);
		exit;
		
	}
	
}
<?php
class ApiController extends AppController 
{
	public $name = 'Api';

	public $uses = array('Invoice','Payment','Response','Settings','paid_before', 'Card', 'Client', 'User','Project');
	
	public $layout = 'AjaxLayout';
	
	public function invoice_notifications()
	{		
		$invoices = $this->Invoice->find('all',array('conditions' => array('paid' => '0','schedule_for <' => date('Y-m-d H:i:s'))));
		
		$found = count($invoices);
		
		$sent = 0;
		
		if(!empty($invoices))
		{
			foreach($invoices as $invoice)
			{
				// Sent out notification for this new invoice.
				if($invoice['Invoice']['sends'] == 0)
				{
					$sent = $sent + $this->send_notification($invoice,1);
				}
				// Send a reminder a day before the invoice is due.
				else if($invoice['Invoice']['sends'] == 1 && (strtotime($invoice['Invoice']['schedule_for']) + (($invoice['Invoice']['terms'] - 1) * 86400)) < time())
				{
					$sent = $sent + $this->send_notification($invoice,2);
				}
				// Send a reminder alerting the client that their invoice is overdue after terms x 2 days.
				else if($invoice['Invoice']['sends'] == 2 && (strtotime($invoice['Invoice']['schedule_for']) + ($invoice['Invoice']['terms'] * 86400 * 2)) < time())
				{
					$sent = $sent + $this->send_notification($invoice,3);
				}
				// Send a final demand for the overdue invoice terms x 3 days.
				else if($invoice['Invoice']['sends'] == 3 && (strtotime($invoice['Invoice']['schedule_for']) + ($invoice['Invoice']['terms'] * 86400 * 3)) < time())
				{
					$sent = $sent + $this->send_notification($invoice,4);
				}
				// Sends the admin an email alerting them that an invoice has passed invoice.admin_notification_days_after_final_demand number of days since sending a final demand without payment, manual action required.
				else if($invoice['Invoice']['sends'] == 4 && (strtotime($invoice['Invoice']['schedule_for']) + ($invoice['Invoice']['terms'] * 86400 * (3 + intval($this->_settings['invoice.admin_notification_days_after_final_demand'])))) < time())
				{
					$sent = $sent + $this->past_final($invoice);
				}
			}
		}
		else
		{
			die(date("Y-m-d H:i:s").": No invoices found.\n");
		}
		die(date("Y-m-d H:i:s").": $found invoices found, $sent emails sent.\n");
		
	}
	
	private function send_notification($invoice,$message_no)
	{
		$email = new CakeEmail('sinclairNew');
			
		// Add the content headers
		$email->setHeaders(	array('MIME-Version' => '1.0','Content-Type' => 'text/html; charset=iso-8859-1'));
			
		$this->layoutPath = 'Emails/html';
			
		$email->template('default', 'STS_layout');
		//Check client details and set email
		$message =  '
						<p>Dear '.$invoice['Client']['firstname'].',</p>
						';
		//send with a different subject line depending on the 'number of reminders'
			
		$reminder_no = $invoice['Invoice']['sends']+1;
			
		$reminder_tag='';//reminder tag is tagged to end of subject line
			
		$invoice_id = $invoice['Invoice']['id'];
		
		switch($message_no)
		{
			case 1:
				$message .=  '
			
					<p>Please find attached your latest invoice no. <span style="color: #3b7ecf;">STS'.$invoice['Invoice']['id'].'</span>, which is now due for payment.</p>
								';
				break;
			case 2:
				$reminder_tag = ' - Reminder';
				$message .=  '
	
					<p>This is a reminder that your latest invoice, no. <span style="color: #3b7ecf;">STS'.$invoice['Invoice']['id'].'</span>, is due for payment. Please find the invoice attached.</p>
							';
				break;
			case 3:
				$reminder_tag = ' - Polite reminder, this invoice is now overdue';
				$message .=  '
		
						<p>Please find attached invoice no. <span style="color: #3b7ecf;">STS'.$invoice['Invoice']['id'].'</span>, which is now <span style="color:red; font-weight: bold;">overdue</span> for payment.</p>
								';
				break;
			case 4:
				$reminder_tag = ' - Final Demand';
				$message .=  '
			
						<p>Please find attached invoice no. <span style="color: #3b7ecf;">STS'.$invoice['Invoice']['id'].'</span>, which is now overdue for payment.</p>
				
						<p style="color:red">This is a final demand, failure to pay will result in legal proceedings	</p>
								';
				break;
			default:
				$message .=  '
			
						<p>Please find attached your latest invoice no. STS'.$invoice['Invoice']['id'].', which is now due for payment.</p>
								';
				break;
		}
		$email->subject("STS Invoice, no.".$invoice['Invoice']['id'].$reminder_tag);
			
		// Build array of client email addresses
		$email_to = $this->decodeAddresses($invoice['Client']['email']);
			
		//override	with admin email while testing
		/*if( $this->_settings['application.mode'] == 'development' )
		 $email_to = $this->_settings['admin_email'];*/
			
		$email->to($email_to);
		$email->bcc($this->_settings['invoice.inquiries_email']);
		
		$email->emailFormat('html'); //see below for converting html to text
			
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		
	
		$message .=  '	<p>You can pay easily and securely through our website, just follow the link below, or alternatively you can call our customer support during office hours and make a payment over the phone.</p>
		<p><br />
			<a href="'.Router::url('/', true).'invoices/pay/'.$invoice_id.'/'.sha1($invoice_id.$secret_phrase).'" style="background: #3b7ecf; padding: 10px 20px; border-radius: 3px; -webkit-border-radius: 4px; -moz-border-radius: 4px; box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.1); -webkit-box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.1); -moz-box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.1); color: #fff; text-align: center; text-decoration: none; display: block; width: 100px; margin: 0 auto;"><span class="button_example">Pay online now</span></a>
			<br />
		</p>
	
		<p>Thanks for your business</p>
		<p>Yours Sincerely, <br /></p>
		<p>Accounts Team</p>
		<br>
		<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
			<p style="font-size: 0.9em; color: #b4b4b4;">www.stsonline.uk.com</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 714 462</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">STS Commercial Ltd</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">24 Dunraven Place</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
		</div>
		';
					
		//set up tracking image
		//Set the route to the /responses folder from the base directory
		$full_base_url = $full_base_url =  Router::url('/', true);
		$responses_url = 'responses';
			
		$r_home_url = $full_base_url.$responses_url;
			
		//construct line to call a tracking pixel from responses/image . Include in message as src="{pix}"
		$opened_params = '/s_image?iv='.$invoice['Invoice']['id'].'&rem='.$reminder_no;

		$tracking_pix = $r_home_url.$opened_params;//$home_url.$secret;

		$message .= '<img src="'.$tracking_pix.'" height="1" width="1" alt="" border="0" />';

		//*******************
		//path is not correct
		$attach_file = getcwd(). DS . 'files' . DS . 'pdf_invoices' . DS .'STS'.$invoice['Invoice']['id'].'.pdf';
					
		$email->attachments($attach_file);

		try
		{

			if(	$email->send($message) ) //send is empty, message is set in viewVars $html and $text
			{
					
				$sends = $reminder_no;
					
				$invoice['Invoice']['sends'] = $sends;
				$invoice['Invoice']['sent'] = date('Y-m-d H:i:s');
				$result = $this->Invoice->save($invoice);
					
				//create a response entry for the reminder
				$a_response = $this->Response->create();
				$a_response['Response']['invoice_id'] = $invoice['Invoice']['id'];
				$a_response['Response']['reminder_no']=$reminder_no;
				$a_response['Response']['created'] = date('Y-m-d H:i:s');
				$a_response['Response']['modified'] = date('Y-m-d H:i:s');
				$r = $this->Response->save($a_response);
				
				return 1;
			}
		}
		catch(Exception $e)
		{
			return 0;
		}
	}
	
	private function past_final($invoice)
	{
		$email = new CakeEmail('sinclairNew');
			
		// Add the content headers
		$email->setHeaders(	array('MIME-Version' => '1.0','Content-Type' => 'text/html; charset=iso-8859-1'));
			
		$this->layoutPath = 'Emails/html';
			
		$email->template('default', 'STS_layout');
		//Check client details and set email
		$message =  '
						<p>Dear Admin,</p>
						';
		//send with a different subject line depending on the 'number of reminders'
			
		$invoice_id = $invoice['Invoice']['id'];
		
		$email->subject("URGENT: STS Invoice, no.".$invoice['Invoice']['id']." Past Final Demand");
			
		$email->to($this->_settings['invoice.inquiries_email']);
		
		$email->emailFormat('html'); //see below for converting html to text
			
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		
		
		$message .=  '<p>Invoice no. STS'.$invoice['Invoice']['id'].' has been sent out a final demand and this has not been paid.</p>
		<p>Please take manual action as appropirate.</p>
		
		<p>Yours Sincerely, <br /></p>
		<p>Accounts Team</p>
		<br>
		<div style=" border-top: 1px dashed #bebebe; width: 640px; margin-top: 30px;">
			<p style="font-size: 0.9em; color: #b4b4b4;">www.stsonline.uk.com</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">+44 1656 714 462</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">STS Commercial Ltd</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">24 Dunraven Place</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">Bridgend</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">CF31 1JD</p>
			<p style="font-size: 0.9em; color: #b4b4b4;">UK</p>
		</div>
		';
					
		//*******************
		//path is not correct
		$attach_file = getcwd(). DS . 'files' . DS . 'pdf_invoices' . DS .'STS'.$invoice['Invoice']['id'].'.pdf';
			
		$email->attachments($attach_file);
		
		try
		{
		
			if(	$email->send($message) ) //send is empty, message is set in viewVars $html and $text
			{			
				$invoice['Invoice']['sends'] = 5;
				$result = $this->Invoice->save($invoice);
				
				return 1;
			}
		}
		catch(Exception $e)
		{
			return 0;
		}
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

}
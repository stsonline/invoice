<?php
class ServicesController extends AppController
{

	public $uses = array('ServiceItem');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'PageLayout';
	}

	public function web_design()
	{
		$this->layout = 'WebLayout';

		$service = 'web_design';
		$serviceItemTypes = array('backend','features','options','extras');
		
		$quoteItems = $this->getServiceItems($service, $serviceItemTypes);
		
		$this->set('quoteItems',$quoteItems);
		$this->set('typeNames', array('backend' => '1. Website Type <span>(pick one)</span>', 'features' => '2. Features I Want <span>(tick required)</span>', 'options' => 'Options', 'extras' => '3. Related Services'));
	}
	public function web_design_step_2()
	{
		$data = $this->request->data;
		 
		if(!empty($data)) {
		
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
			}
			
			$hidden_input_html = "";
			$hidden_input_html.=(isset($data['quote']['items']['backend'])?"<input type='hidden' name='data[quote][items][backend]' value='".$data['quote']['items']['backend']."'>":"");
			$hidden_input_html.=(isset($data['quote']['amount'])?"<input type='hidden' name='data[quote][amount]' value='".$data['quote']['amount']."'>":"");

			$quote_summary['backend'] = isset($data['quote']['items']['backend'])?$data['quote']['items']['backend']:"";
			$quote_summary['amount'] = isset($data['quote']['amount'])?$data['quote']['amount']:"";
			if(isset($data['quote']['items']['features']))
			{
				foreach($data['quote']['items']['features'] as $key => $feature)
				{
					$hidden_input_html.="<input type='hidden' name='data[quote][items][features][".$key."]' value='".$feature."'>";
					$quote_summary['items']['features'][$key]  = $feature;
				}
			}
			if(isset($data['quote']['items']['extras']))
			{
				foreach($data['quote']['items']['extras'] as $key => $extra)
				{
					$hidden_input_html.="<input type='hidden' name='data[quote][items][extras][".$key."]' value='".$extra."'>";
					$quote_summary['items']['extras'][$key]  = $extra;
				}
			}
			/*
			 $this->Quote->create();
			 $quote['Quote']['type'] = $data['quote']['type'];
			 $quote['Quote']['items'] = json_encode($data['quote']['items']);
			 $quote['Quote']['name'] = $data['quote']['name'];
			 $quote['Quote']['email'] = $data['quote']['email'];
			 $quote['Quote']['company'] = $data['quote']['company'];
			 $quote['Quote']['number'] = $data['quote']['number'];
			 $quote['Quote']['message'] = $data['quote']['message'];
			 $quote['Quote']['amount'] = $data['quote']['amount'];
			 $this->Quote->save($quote);
		
			 $message = js_beautify(json_encode($data));
			 $this->sendAdminEmail('Quote Form Submission: ', $message);
			  
			 $this->set('smtp_errors', $smtp_errors = $this->Email->smtpError);
			  
			 //die(json_encode(array('success'=>true, 'smtp_errors'=>$smtp_errors)));
			 $this->redirect('/contact/success');
			 */

			$this->set('quote_info', $hidden_input_html);
			$this->set('quote_summary', $quote_summary);
		} else {
			$this->redirect('/get-in-touch');
		}
	}
	
	public function ecommerce(){}
	public function cs_cart(){}
	public function jshop(){}
	public function magento(){}
	public function hosting(){}
	public function support(){}
	public function search_marketing(){}
	public function app_development(){}
	public function social_media(){}
	public function lead_generation(){}
	public function cyber_security(){}
	public function right_move_api() {}
	public function zoopla_api() {}
	public function free_ssl() {}


	private function getServiceItems($service, $types) {
		$serviceItems = array();
		
		foreach($types as $type) {
			foreach($this->ServiceItem->find('all',array('conditions' => array('service' => $service,'enabled'=>true, 'type' => $type))) as $item) {
				$serviceItems[$type][] = $item['ServiceItem'];
			}
		}
		
		return $serviceItems;
	}

	public function website_review()
	{
		
	}

}
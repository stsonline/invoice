<?php
class ShowcaseController extends AppController 
{

	/**
	 * Controller name
	 *
	 * @var string
	 */
	public $name = 'Showcase';
	
	/**
	 * This controller does not use a model
	 *
	 * @var array
	 */
	public $uses = array('Showcase', 'ShowcaseImage');
	
	public $layout = 'IndexLayout';
	
	public function index($name = null)
	{
		$list = true;
		
		if($name !== null) {
			$showcase = $this->Showcase->find('first',array(
					'conditions' => array('Showcase.seo_name' => $name)));
			if(!empty($showcase)) {
				$list = false;
				
				$all_showcases = $this->Showcase->find('all', array('conditions'=>array('Showcase.disabled !=' => '1', 'Showcase.seo_name NOT'=>$name), 'order' => array('Showcase.id')));
					
				$random = rand(1, sizeof($all_showcases));
				$rand['seo_name'] = $all_showcases[$random-1]['Showcase']['seo_name'];
				$rand['title'] = $all_showcases[$random-1]['Showcase']['title'];
				$rand['image_url'] = $all_showcases[$random-1]['Showcase']['image_url'];
				
				
				$this->set('rand',$rand);
				$this->set('showcase_item',$showcase);
			} else {
				$this->redirect(array('controller' => 'showcase', 'action' => 'index'));
			}
			
		}
		
		$this->set('list',$list);
	}
	
	public function view($name)
	{
		$all_showcases = $this->Showcase->find('all', array('conditions'=>array('Showcase.disabled' => NULL, 'Showcase.seo_name NOT'=>$name), 'order' => array('Showcase.id')));
		
		//print_r($all_showcases);exit;
		$rand = rand(1, sizeof($all_showcases));
		$rand_id = $all_showcases[$rand-1]['Showcase']['id'];
		$rand_title = $all_showcases[$rand-1]['Showcase']['title'];
		
		$showcase_item = $this->Showcase->findById($id);
		$this->set('showcase_item',$showcase_item);
		$this->set('rand_id',$rand_id);
		$this->set('rand_title',$rand_title);
	}
}
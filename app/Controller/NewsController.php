<?php
class NewsController extends AppController
{

	public $name = 'News';

	//No linked Model
	public $uses = array('Newsletter');

	// Uses IndexLayout
	public $layout = 'IndexLayout';

	public function index($name = null){
		$list = true;

		if($name !== null) {
			$name = substr($name,11);

			$news = $this->Newsletter->find('first',array(
					'conditions' => array(
							'Newsletter.date <' => date("Y-m-d H:i:s",time()),
							'Newsletter.seo_name' => $name)));
			if(!empty($news)) {
				$list = false;
			} else {
				$this->redirect(array('controller' => 'news', 'action' => 'index'));
			}
		}

		if($list === true) {
			$news = $this->Newsletter->find('all',array(
					'conditions' => array(
							'Newsletter.date <' => date("Y-m-d H:i:s",time()),
							'disabled' => false
							),
					'order' => array('Newsletter.date DESC')));
		}

		$this->set('list',$list);
		$this->set('news',$news);
	}



}

<?php
class PagesController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'PageLayout';
	}

	public function bnp_paribas_hackathon(){}
	public function careers(){}
	public function complaints(){}
	public function get_in_touch(){}
	public function our_work(){}
	public function who_we_are(){}
	public function miskinit_merge(){}
	public function what_we_do(){}
	public function bridgend_webdesign(){}
	public function cardiff_webdesign(){}

}

<?php
class OfferController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'OfferLayout';
	}

	public function index(){}

}
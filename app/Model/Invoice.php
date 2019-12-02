<?php
class Invoice extends AppModel
{
	public $name = 'Invoice';

	public $helpers = array('Html', 'Form');

	public $belongsTo = array('Client','Project');

	public $hasMany = array('LineItem');
	
	//Validation rules
	//invoice file must be pdf
	//invoice filename must be unique (original filename)
	//total must be a decimal number , 2 dp
	
	/*public $validate = array(
								'pdf' => array(	'pdf_rule1'=>array('rule' => array('extension',array('pdf')),
																	'message' => 'Invoice - File must be pdf format'											
										  						  ),
												'pdf_rule2'=>array('rule' => array('uniquePDFFile', 'pdf'),
																	'message' => 'Invoice - Filename must be unique'
																  ),
											  ),
								'total' => array('total_rule1'=>array(
																'rule'=>'notEmpty',
										   						'message'=>'Total must not be empty' 
																),
												'total_rule2'=>array(
																'rule' => array('decimal', 2),
																'message'=>'Total must be decimal value, 2 decimal places'
																)
										),

							);*/
	
	public function uniquePDFFile ($pdf) 
	{
		$count = $this->find('count', array('conditions' => array('pdf' => $pdf)));
		return $count == 0;
	}

}
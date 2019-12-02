<?php
App::uses('AdminLayoutController', 'Controller');
class ActionsController extends AdminLayoutController
{

	var $helpers = array('Html', 'Form');

	public $uses = array('Role');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow('permitted');//allowed to check if action is permitted
	}
	
	

	public function isAuthorized($user)
	{
		if($this->action=='permitted')
				return true;
		return parent::isAuthorized($user);
	}

	public function permitted( $controller_name, $action_name, $role_id )
	{

		//look for action in database
		$an_action = $this->Action->find('first',array(	'conditions' =>
				array(	'controller_name' => $controller_name ,
						'action_name' => $action_name,
						'role_id' => $role_id	)));

		/*if(count($an_action)==0)//if the action isn't in the database create it and allow it
		{
			$this->Action->create();
			$this->Action->controller_name = $controller_name;
			$this->Action->action_name = $action_name;
			$this->Action->role_id = $role_id;
			$this->Action->allowed = true;
			$this->Action->secure = 0;
			if( $this->Action->save($this->Action) )
				return true;
			return false;
		}*/
		return $an_action['Action'];//['allowed'];
	}

	public function change_permission($action_id = null)
	{
		$an_action = $this->Action->find('first',array('conditions' =>array('Action.id' => $action_id)));
		if(isset($an_action))
		{
			$an_action['Action']['allowed'] = ! $an_action['Action']['allowed'];
			if($this->Action->save($an_action)) {
				$this->Session->setFlash('Action permission changed.');
			} else {
				$this->Session->setFlash('Error, change not completed.');
			}
		} else {
			$this->Session->setFlash('Error, action id not set');
		}
		
		$this->redirect(array('action' => 'index'));
	}

	public function index()
	{
		$actions = $this->Action->find('all',array('order' => array('Action.role_id', 'Action.controller_name', 'Action.action_name')) );
		$this->set('roles', $this->Role->find('list'));
		$this->set('actions', $actions);
	}

	public function is_secure($controller_name, $action_name, $role_id)
	{
		//look for action in database
		$an_action = $this->Action->find('first',array(	'conditions' =>
				array(	'controller_name' => $controller_name ,
						'action_name' => $action_name,
						'role_id' => $role_id	)));
		return $an_action['Action']['secure'];
	}
	
	public function change_secure($action_id = null)
	{
		$an_action = $this->Action->find('first',array('conditions' =>array('Action.id' => $action_id)));
		if(isset($an_action))
		{
			$an_action['Action']['secure'] = ! $an_action['Action']['secure'];
			if($this->Action->save($an_action)) {
				$this->Session->setFlash('Action secure status changed.');
			} else {
				$this->Session->setFlash('Error, change not completed.');
			}
		} else {
			$this->Session->setFlash('Error, action id not set');
		}
	
		$this->redirect(array('action' => 'index'));
	}
}
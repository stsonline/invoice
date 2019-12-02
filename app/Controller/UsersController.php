<?php
App::uses('AdminLayoutController', 'Controller');
class UsersController extends AdminLayoutController
{
	
	var $helpers = array('Html', 'Form');
	
	public function beforeFilter() 
	{		
		parent::beforeFilter();
		$this->Auth->deny();
		$this->Auth->allow('login','logout');
		$this->layout = 'IndexLayout';
		
	}
	public function isAuthorized($user)
	{
		return parent::isAuthorized($user);
	}
	
	public function register()
	{
		
		if ($this->request->is('post'))
		{
			//registered users are added to the 'User' group
				
			$this->request->data['User']['role_id'] = '1';
			if($this->User->isUnique( array('User.username' => $this->data['User']['username'] )) )
			{
				if ($this->User->save($this->request->data))
				{
					$this->Session->setFlash('You are now a member');
					//log new user in
					$this->Auth->login($this->request->data['User']);
					$this->redirect(array('controller'=>'clients','action' => 'index'));
				}
				else
				{
					$this->Session->setFlash('Unable to create membership');
				}
			}
			else 
				$this->Session->setFlash('Unable to create membership');
		}
	}

	public function login() 
	{
		$this->layout = 'UsersLayout';
		if ($this->request->is('post')) 
		{
			if ($this->Auth->login()) 
			{
				$user = $this->Session->read('Auth.User'); //get the logged in user
				
				//dispatch an event when someone logs in
//				$this->getEventManager()->dispatch(new CakeEvent('Controller.User.login', $this, 
//																array('user' => $user) ) );
				$this->redirect($this->Auth->redirect());
			} 
			else 
			{
				SessionComponent::setFlash(__('Invalid username or password, try again'));
			}
		}
	}

	public function logout()
	{
		$this->layout = 'UsersLayout';
		$user = $this->Session->read('Auth.User'); //get the logged in user
		//dispatch an event when someone logs in
		$this->getEventManager()->dispatch(new CakeEvent('Controller.User.logout', $this,
				array('user' => $user) ) );
		
		//$this->Session->setFlash('Cheerio!');
		SessionComponent::setFlash('You have been logged out.');
		$this->redirect($this->Auth->logout());
	}
	
	public function index() 
	{	
		$users = $this->User->find('all');			
		$this->set('users', $users);
	}

	public function view($id = null) 
	{
		$this->User->id = $id;
		if (!$this->User->exists()) 
		{
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

    function add() 
    {
    	if (!empty($this->data))
    	{
    		if($this->User->isUnique( array('User.username' => $this->data['User']['username'] )) )
    		{
    			$this->User->create();

    			$this->request->data['User']['role_id'] = '1';

    			if ($this->User->save($this->data))
    			{
    				$this->Session->setFlash(__('The User has been saved', true));
    				$this->redirect(array('action'=>'index'));
    			}
    			else
    			{
    				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
    			}
    		}
    		else
    		{
    			$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
    		}
    	}

    }

    function edit($id = null) 
    {
        if (!$id && empty($this->data)) 
        {
            $this->Session->setFlash(__('Invalid User', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) 
        {
            if ($this->User->save($this->data)) 
            {
                $this->Session->setFlash(__('The User has been saved', true));
                $this->redirect(array('action'=>'index'));
            } 
            else 
            {
                $this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) 
        {
            $this->data = $this->User->read(null, $id);
        }

    }

    function delete($id = null) 
    {
        	if (!$id) 
        	{
            	$this->Session->setFlash(__('Invalid id for User', true));
            	$this->redirect(array('action'=>'index'));
        	}
        	if ($this->User->del($id)) 
        	{
            	$this->Session->setFlash(__('User deleted', true));
            	$this->redirect(array('action'=>'index'));
        	}
    }
}
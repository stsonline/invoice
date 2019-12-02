<?php
class Role extends AppModel {

	public $name = 'Role';

	public $hasMany = 'Action';
	//public $hasMany = 'User';
}
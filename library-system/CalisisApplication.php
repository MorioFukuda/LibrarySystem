<?php

class CalisisApplication extends Application
{
	protected $login_action = array('account', 'signin');

	public function getRootDir()
	{
		return dirname(__FILE__);
	}

	protected function registerRoutes()
	{
		return array(
			'/user'
				=> array('controller' => 'user', 'action' => 'index'),
			'/user/:action'
				=> array('controller' => 'user'),
		);
	}

	protected function configure()
	{
		$this->db_manager->connect('master', array(
			'dsn' => 'mysql:dbname=calisis;host=localhost',
			'user' => 'calisis',
			'password' => 'kr9zgzne',
		));
	}
}

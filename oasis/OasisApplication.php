<?php

class OasisApplication extends Application
{
	protected $login_action = array('user', 'signin');

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
			'dsn' => 'mysql:dbname=oasis;host=localhost',
			'user' => 'oasis',
			'password' => 'kr9zgzne',
		));
	}
}

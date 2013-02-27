<?php

class StaffCalisisApplication extends Application
{
	protected $login_action = array('admin', 'signin');

	public function getRootDir()
	{
		return dirname(__FILE__);
	}

	protected function registerRoutes()
	{
		return array(
			'/book'
				=> array('controller' => 'book', 'action' => 'index'),
			'/book/:action'
				=> array('controller' => 'book'),
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

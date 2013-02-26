<?php

class StaffCalisisApplication extends Application
{
	protected $login_action = array('user', 'signin');

	public function getRootDir()
	{
		return dirname(__FILE__);
	}

	protected function registerRoutes()
	{
		return array(
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

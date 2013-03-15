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
			'/'
				=> array('controller' => 'book', 'action' => 'search'),
			'/book'
				=> array('controller' => 'book', 'action' => 'search'),
			'/book/:action'
				=> array('controller' => 'book'),
		);
	}

	protected function configure()
	{
		require dirname(__FILE__) . '/config/database.php';

		$this->db_manager->connect('master', $databaseConfig);
	}
}

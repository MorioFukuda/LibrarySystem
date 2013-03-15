<?php

class StaffOasisApplication extends Application
{
	protected $login_action = array('account', 'signin');

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
			'/book/confirmDelete/:bookId'
				=> array('controller' => 'book', 'action' => 'confirmDelete'),
			'/book/edit/:bookId'
				=> array('controller' => 'book', 'action' => 'edit'),
			'/book/getBookData/:isbn'
				=> array('controller' => 'book', 'action' => 'getBookData'),
			'/book/inputShelf/:bookId'
				=> array('controller' => 'book', 'action' => 'inputShelf'),
			'/book/:action'
				=> array('controller' => 'book'),
			'/account/:action'
				=> array('controller' => 'account'),
		);
	}

	protected function configure()
	{
		require dirname(__FILE__) . '/../oasis/config/database.php';

		$this->db_manager->connect('master', $databaseConfig);
	}
}

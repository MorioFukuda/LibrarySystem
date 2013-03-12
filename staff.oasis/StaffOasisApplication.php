<?php

class StaffOasisApplication extends Application
{
	protected $login_action = array('admin', 'signin');

	public function getRootDir()
	{
		return dirname(__FILE__);
	}

	protected function registerRoutes()
	{
		return array(
			'/'
				=> array('controller' => 'book', 'action' => 'index'),
			'/book'
				=> array('controller' => 'book', 'action' => 'index'),
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

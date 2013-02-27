<?php

class BookController extends Controller
{
	public function indexAction()
	{
		return $this->render(array(
			'isbn' => '',
			'title' => '',
			'shelfId' => '',
			'amazonUrl' => '',
			'_token' => $this->generateCsrfToken('book/index'),
		));
	}

	public function saveAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect('/book/index');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('book/index', $token)){
			return $this->redirect('/book/index');
		}

		$isbn = $this->request->getPost('isbn');
		$title = $this->request->getPost('title');
		$shelfId = $this->request->getPost('shelf_id');
		$amazonUrl = $this->request->getPost('amazon_url');
		$bookImage = $this->request->getFiles('book_image');

		list($result, $errors) = $this->db_manager->get('Book')->insert($isbn, $title, $shelfId, $amazonUrl, $bookImage);

		return $this->render(array(
			'isbn' => $isbn,
			'title' => $title,
			'shelfId' => $shelfId,
			'amazonUrl' => $amazonUrl,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('book/index')
		), 'index');
	}
}

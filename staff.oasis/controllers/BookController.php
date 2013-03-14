<?php

class BookController extends Controller
{
	public function indexAction()
	{
		return $this->render(array(
		));
	}

	public function inputAction()
	{
		return $this->render(array(
			'isbn' => '',
			'title' => '',
			'author' => '',
			'amazonUrl' => '',
			'shelfName' => '',
			'imageUrl' => '',
			'_token' => $this->generateCsrfToken('book/input')
		), 'input');
	}

	public function saveAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect('/book/input');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('book/input', $token)){
			return $this->redirect('/book/input');
		}

		$isbn = $this->request->getPost('isbn');
		$title = $this->request->getPost('title');
		$author = $this->request->getPost('author');
		$amazonUrl = $this->request->getPost('amazon_url');
		$shelfName = $this->request->getPost('shelf_name');
		$imageUrl = $this->request->getFiles('image_url');

		$variables = array(
			'isbn' => $isbn,
			'title' => $title,
			'author' => $author,
			'amazonUrl' => $amazonUrl,
			'shelfName' => $shelfName,
			'imageUrl' => $imageUrl,
			'errors' => array(),
			'_token' => $this->generateCsrfToken('book/input')
		);

		if($this->db_manager->get('Book')->hasEmpty($isbn, $title, $author, $amazonUrl, $shelfName, $imageUrl)){
			$variables['errors'][] = '未入力の項目があります。';
			return $this->render($variables, 'input');
		}

		if(!empty($isbn) && !$this->db_manager->get('Book')->validateIsbn($isbn)){
			$variables['errors'][] = '正しくないISBN番号です。';
			return $this->render($variables, 'input');
		}

		$isbn = $this->db_manager->get('Book')->convertToIsbn10($isbn);

		list($result, $shelfId) = $this->db_manager->get('Book')->validateShelfName($shelfName);
		if($result === false){
			$variables['errors'][] = '正しくない棚番号です。';
			return $this->render($variables, 'input');
		}

		if(!$this->db_manager->get('Book')->validateUrl($amazonUrl)){
			$variables['errors'][] = 'AmazonのURLが正しくないURLです。';
			return $this->render($variables, 'input');
		}

		if(!$this->db_manager->get('Book')->validateUrl($imageUrl)){
			$variables['errors'][] = '画像URLが正しくないURLです。';
			return $this->render($variables, 'input');
		}

		$this->db_manager->get('Book')->insert($isbn, $title, $author, $amazonUrl, $shelfId, $imageUrl);

		return $this->render(array(), 'completeSave');
	}



	public function inputByIsbnAction()
	{
		return $this->render(array(
			'shelfName' => '',
			'_token' => $this->generateCsrfToken('book/inputByIsbn')
		), 'inputByIsbn');
	}

	public function saveByIsbnAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect('book/inputByIsbn');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('book/inputByIsbn', $token)){
			return $this->redirect('/book/inputByIsbn');
		}

		$bookDataList = $this->request->getPost('book_list');
		$shelfName = $this->request->getPost('shelf_name');

		$variables = array(
				'error' => '',
				'bookDataList' => $bookDataList,
				'shelfName' => $shelfName,
				'_token' => $this->generateCsrfToken('book/inputByIsbn')
		);

		if($this->db_manager->get('Book')->hasEmpty($bookDataList, $shelfName)){
			$variables['error'] = '未入力の項目があります。';
			return $this->render($variables, 'inputByIsbn');
		}
		
		list($result, $shelfId) = $this->db_manager->get('Book')->validateShelfName($shelfName);
		if($result === false){
			$variables['error'] = '棚番号が正しくありません。';
			return $this->render($variables, 'inputByIsbn');
		}	

		foreach($bookDataList as $bookData){
			$isbn = $bookData['isbn'];
			$title = $bookData['title'];
			$author = $bookData['author'];
			$amazonUrl = $bookData['amazon_url'];
			$imageUrl = $bookData['image_url'];
			$this->db_manager->get('Book')->insert($isbn, $title, $author, $amazonUrl, $shelfId, $imageUrl);
		}

		return $this->render(array(
			'bookDataList' => $bookDataList
		), 'saveByIsbn');
	}


	public function getBookDataAction($params)
	{
		$this->response->setHttpHeader('Content-Type', 'text/javascript; charset=utf-8');

		$isbn = $params['isbn'];

		if(!$this->db_manager->get('Book')->validateIsbn($isbn)){
			return $this->render(array(
				'result' => json_encode(array('result' => false, 'error' => '不正なISBN番号です。'))
			), 'getBookData', 'ajax');
		}

		list($result, $error, $bookData) = $this->db_manager->get('Book')->getBookData($isbn);
		if($result === false){
			return $this->render(array(
				'result' => json_encode(array('result' => false, 'error' => $error))
			), 'getBookData', 'ajax');
		}

		$bookDataJson = json_encode($bookData);

		return $this->render(array(
			'result' => $bookDataJson,
		), 'getBookData', 'ajax');
	}

	public function confirmDeleteAction($params)
	{
		$bookId = $params['bookId'];

		$bookData = $this->db_manager->get('Book')->fetchById($bookId);

		if($bookData === false){
			return $this->render(array(), 'notFound');
		}

		if($this->db_manager->get('Book')->isDeleted($bookId)){
			return $this->render(array(
				'bookData' => $bookData,
				'error' => '指定された書籍は既に削除済みです。',
			), 'confirmDelete');
		}

		if(!$this->db_manager->get('Book')->isAvailable($bookId)){
			return $this->render(array(
				'bookData' => $bookData,
				'error' => '指定された書籍は貸出中のため削除できません。',
			), 'confirmDelete');
		}

		return $this->render(array(
			'bookData' => $bookData,
			'_token' => $this->generateCsrfToken('book/confirmDelete'),
		), 'confirmDelete');
	}

	public function deleteAction()
	{
		if(!$this->request->isPost()){
			return $this->render(array(),'error');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('book/confirmDelete', $token)){
			return $this->render(array(),'error');
		}

		$bookId = $this->request->getPost('book_id');
		if($this->db_manager->get('Book')->hasEmpty($bookId)){
			return $this->render(array(),'error');
		}

		$bookData = $this->db_manager->get('Book')->fetchById($bookId);

		if(
			$bookData === false ||
			$this->db_manager->get('Book')->isDeleted($bookId) ||
			!$this->db_manager->get('Book')->isAvailable($bookId)
		){
			return $this->redirect('/book/confirmDelete/' . $bookId);
		}

		$this->db_manager->get('Book')->delete($bookId);

		return $this->render(array(), 'completeDelete');
	}

	public function inputShelfAction($params)
	{
		$bookId = $params['bookId'];

		$bookData = $this->db_manager->get('Book')->fetchById($bookId);

		$shelfName = $bookData['shelf_name'];
		if(isset($this->request->getPost['shelf_name'])){
			$shelfName = $this->request->getPost['shelf_name'];
		}

		if($bookData === false || $this->db_manager->get('Book')->isDeleted($bookId)){
			return $this->render(array(), 'notFound');
		}

		return $this->render(array(
			'bookData' => $bookData,
			'shelfName' => $shelfName,
			'_token' => $this->generateCsrfToken('book/inputShelf'),
		), 'inputShelf', 'modal');
	}

	public function setShelfAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect('/book/inputShelf');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('book/inputShelf', $token)){
			return $this->render(array(),'error');
		}

		$bookId = $this->request->getPost('book_id');
		if($this->db_manager->get('Book')->hasEmpty($bookId)){
			return $this->render(array(),'error');
		}

		$bookData = $this->db_manager->get('Book')->fetchById($bookId);

		if($bookData === false || $this->db_manager->get('Book')->isDeleted($bookId)){
			return $this->render(array(), 'notFound');
		}		

		$shelfName = $this->request->getPost('shelf_name');
		if($this->db_manager->get('Book')->hasEmpty($shelfName)){
			return $this->render(array(
				'bookData' => $bookData,
				'error' => '棚番号を入力してください。',
				'shelfName' => $bookData['shelf_name'],
				'_token' => $this->generateCsrfToken('book/inputShelf'),
			), 'inputShelf');
		}

		list($result, $shelfId) = $this->db_manager->get('Book')->validateShelfName($shelfName);
		if($result === false){
			return $this->render(array(
				'bookData' => $bookData,
				'error' => '正しくない棚番号です。',
				'shelfName' => $shelfName,
				'_token' => $this->generateCsrfToken('book/inputShelf'),
			), 'inputShelf');
		}

		$this->db_manager->get('Book')->updateShelfId($bookId, $shelfId);

		return $this->render(array(), 'completeSetShelf');
	}

	public function searchAction()
	{
		// POSTメソッドの場合
		if($this->request->isPost()){
			$query = $this->request->getPost('query');
			$condition = $this->request->getPost('condition');
			$limit = $this->request->getPost('limit');

			if($this->db_manager->get('Book')->hasEmpty($query, $condition, $limit)){
				return $this->render(array(
					'query' => '',
				), 'search');
			}

			if($this->db_manager->get('Book')->hasEmpty($condition)){
				$condition = 'AND';
			}

			return $this->redirect('/book/search?query=' . $query .'&condition=' . $condition . '&offset=0&limit=' . $limit);
		}

		// GETメソッドの場合
		$query = $this->request->getGet('query');
		$condition = $this->request->getGet('condition');
		$offset = $this->request->getGet('offset');
		$limit = $this->request->getGet('limit');

		if($this->db_manager->get('Book')->hasEmpty($query, $condition)){
			return $this->render(array(
				'query' => '',
			), 'search');
		}

		$condition = $this->db_manager->get('Book')->validateCondition($condition);
		$offset = $this->db_manager->get('Book')->validateOffset($offset);
		$limit = $this->db_manager->get('Book')->validateLimit($limit);

		list($bookDataList, $resultNum) = $this->db_manager->get('Book')->search($query, $condition, $offset, $limit);

		return $this->render(array(
			'query' => $query,
			'condition' => $condition,
			'offset' => $offset,
			'limit' => $limit,
			'bookDataList' => $bookDataList,
			'resultNum' => $resultNum,
		), 'search');
	}

	public function getBookListAction()
	{
		$this->response->setHttpHeader('Content-Type', 'text/javascript; charset=utf-8');

		$query = $this->request->getGet('query');
		if(empty($query)){
			return $this->render(array(
				'result' => json_encode(array('result' => false, 'error' => '該当する書籍はありません。'))
			), 'getBookList', 'ajax');
		}

		list($bookDataList, $resultNum) = $this->db_manager->get('Book')->search($query, 'AND', 0, 0, false);
		if(empty($bookDataList)){
			return $this->render(array(
				'result' => json_encode(array('result' => false, 'error' => '該当する書籍はありません。'))
			), 'getBookList', 'ajax');
		}

		$bookDataJson = json_encode(array('result' => true, 'bookList' => $bookDataList));

		return $this->render(array(
			'result' => $bookDataJson,
		), 'getBookList', 'ajax');
	}

	public function inputShelfMultiAction()
	{
		return $this->render(array(
			'shelfName' => '',
			'_token' => $this->generateCsrfToken('book/inputShelfMulti'),
		), 'inputShelfMulti');
	}

	public function setShelfMultiAction()
	{
		if(!$this->request->isPost()){
			return $this->redirect('/book/inputShelfMulti');
		}

		$token = $this->request->getPost('_token');
		if(!$this->checkCsrfToken('book/inputShelfMulti', $token)){
			return $this->render(array(),'error');
		}

		$bookIdList = $this->request->getPost('book_id');
		$shelfName = $this->request->getPost('shelf_name');

		$variables = array(
			'error' => '',
			'shelfName' => $shelfName,
			'_token' => $this->generateCsrfToken('book/inputShelfMulti'),
		);

		if(count($bookIdList) === 0){
			$variables['error'] = '書籍を指定してください。';
			return $this->render($variables, 'inputShelfMulti');
		}

		if($this->db_manager->get('Book')->hasEmpty($shelfName)){
			$variables['error'] = '棚番号を入力してください。';
			return $this->render($variables, 'inputShelfMulti');
		}

		if(!$this->db_manager->get('Book')->validateIdList($bookIdList)){
			$variables['error'] = '不正な書籍IDです。';
			return $this->render($variables, 'inputShelfMulti');
		}

		list($result, $shelfId) = $this->db_manager->get('Book')->validateShelfName($shelfName);
		if($result === false){
			$variables['error'] = '正しくない棚番号です。';
			return $this->render($variables, 'inputShelfMulti');
		}

		$bookDataList = array();

		foreach($bookIdList as $bookId){
			$this->db_manager->get('Book')->updateShelfId($bookId, $shelfId);
			$bookDataList[] = $this->db_manager->get('Book')->fetchById($bookId);
		}

		return $this->render(array(
			'bookDataList' => $bookDataList,
			'shelfName' => $shelfName
		), 'completeSetShelfMulti');
	}

}

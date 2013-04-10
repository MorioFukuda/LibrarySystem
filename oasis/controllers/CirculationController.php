<?php

class CirculationController extends Controller
{
	public function borrowAction()
	{
		$variables = array();
		return $this->render($variables, 'borrowInput');
	}

	public function getBookDataAction($params)
	{
		$this->response->setHttpHeader('Content-Type', 'text/javascript; charset=utf-8');
		$variables = array();
		
		$isbn = $params['isbn'];

		if(!$this->db_manager->get('Book')->validateIsbn($isbn)){
			$result = json_encode(array('result' => false, 'error' => '不正なISBN番号です。'));
			$variables['result'] = $result;

			return $this->render($variables, 'getBookData', 'ajax');
		}

		$record = $this->db_manager->get('Book')->fetchByIsbn($isbn);
		if(count($record) === 0){
			$result = json_encode(array('result' => false, 'error' => '指定されたISBNに該当する書籍は登録されていません。'));
			$variables['result'] = $result;

			return $this->render($variables, 'getBookData', 'ajax');	
		}

		if($this->db_manager->get('Book')->isDeletedByIsbn($isbn)){
			$result = json_encode(array('result' => false, 'error' => '指定されたISBNに該当する書籍は削除済みです。'));
			$variables['result'] = $result;

			return $this->render($variables, 'getBookData', 'ajax');	
		}

		if(!$this->db_manager->get('Book')->isAvailableByIsbn($isbn)){
			$result = json_encode(array('result' => false, 'error' => '指定されたISBNに該当する書籍は貸出中です。'));
			$variables['result'] = $result;

			return $this->render($variables, 'getBookData', 'ajax');				
		}

		$bookData = $this->db_manager->get('Circulation')->getBookData($isbn);

		$bookDataJson = json_encode($bookData);
		$variables['result'] = $bookDataJson;

		return $this->render($variables, 'getBookData', 'ajax');
	}
}

<?php

class BookController extends Controller
{
	public function searchAction()
	{
		if($this->request->isPost()){
			return $this->redirect('/book/search');
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
}

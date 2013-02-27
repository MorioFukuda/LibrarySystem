<?php

class BookRepository extends BaseRepository
{
	public function insert($isbn, $title, $shelfId, $amazonUrl, $bookImage)
	{

		$errors = array();
		
		if(empty($isbn)) $errors[] = 'ISBNを入力してください。';
		if(empty($title)) $errors[] = '書籍名を入力してください。';
		if(empty($shelfId)) $errors[] = '棚番号を入力してください。';
		if(empty($amazonUrl)) $errors[] = 'Amazon URLを入力してください。';

		if($this->validateIsbn($isbn) !== true && count($errors) === 0){
			$errors[] = $this->validateIsbn($isbn);
		}

		if($this->validateShelfId($shelfId) !== true && count($errors) === 0){
			$errors[] = $this->validateShelfId($shelfId);
		}

		if(!preg_match('/(https?:\/\/)([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $amazonUrl) && count($errors) === 0){
			$errors[] = '正しくないURLです。';
		}

		$bookId = '';

		if(!empty($bookImage['type'])){
			if($this->validateImageFile($bookImage) !== true && count($errors) === 0){
				$errors[] = $this->validateImageFile($bookImage);
			}else{
				list($width, $height, $type) = getimagesize($bookImage['tmp_name']);

				$type = str_replace(array(1, 2, 3), array('.gif', '.jpg', '.png'), $type);

				$dir = dirname(__FILE__) . '/../web/media/';
				$fileName = hash_file('sha256', $bookImage['tmp_name']);
				$path = $dir . $fileName . $type;

				if(!file_exists($path)){
					move_uploaded_file($bookImage['tmp_name'], $path);
					// book_imageテーブルに、path,type,width,heightを格納し、idを$bookIdに格納
				}else{
					// 画像に対応したbook_imageテーブルのidを探す。
				}
			}
		}

		if(count($errors) === 0){
			return array(true, $errors);
		}

		return array(false, $errors);
	}

	public function validateShelfId($shelfId)
	{
		if(!preg_match('/^[0-9a-zA-Z]+/', $shelfId)){
			return '棚番号は半角英数で入力してください。';
		}

		//TODO : shelfテーブルに存在するかのチェック

		return true;
	}

	public function validateImageFile($bookImage)
	{
		if($bookImage['error'] !== 0){
			return 'アップロードに失敗しました。';
		}

		if($bookImage['type'] !== 'image/gif' && $bookImage['type'] !== 'image/jpeg' && $bookImage['type'] !== 'image/png'){
			return '画像形式は、GIF、JEPG、PNGのいずれかにしてください。';
		}

		if($bookImage['size'] >= 1000*1000){
			return '画像のサイズは1MB以下にしてください。';
		}

		return true;
	}
}

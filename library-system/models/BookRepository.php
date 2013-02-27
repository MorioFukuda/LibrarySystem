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

		$bookImageId = '';

		// 画像がアップロードされている場合
		if(!empty($bookImage['type'])){
			if($this->validateImageFile($bookImage) !== true && count($errors) === 0){
				$errors[] = $this->validateImageFile($bookImage);
			}else{
				list($width, $height, $type) = getimagesize($bookImage['tmp_name']);

				$type = str_replace(array(1, 2, 3), array('.gif', '.jpg', '.png'), $type);

				$dir = dirname(__FILE__) . '/../web/media/';
				$fileName = hash_file('sha256', $bookImage['tmp_name']) . $type;
				$path = $dir . $fileName;

				if(!file_exists($path)){
					// 同じ画像が存在しなければ、画像を保存し、book_imageテーブルにパスとかを格納
					move_uploaded_file($bookImage['tmp_name'], $path);
					
					$sql = "
						INSERT INTO book_image(name, width, height)
						VALUES(:name, :width, :height)
					";
					
					$this->execute($sql, array(
						':name' => $fileName,
						':width' => $width,
						':height' => $height,
					));

					$imageId = $this->getInsertId();
				}else{
					// 同じ画像があれば、画像に対応したbook_imageテーブルのidを探す。
					$sql = "SELECT id FROM book_image WHERE name = :name";
					$row = $this->fetch($sql, array(':name' => $fileName));
					$imageId = $row['id'];
				}
			}
		}

		if(count($errors) === 0){
			$sql = "
				INSERT INTO book(isbn, title, shelf_id, image_id, amazon_url)
				VALUES(:isbn, :title, :shelf_id, :image_id, :amazon_url)
			";
			$stmt = $this->execute($sql, array(
				':isbn' => $isbn,
				':title' => $title,
				':shelf_id' => $shelfId,
				':image_id' => $imageId,
				':amazon_url' => $amazonUrl,
			));
			return array(true, $errors);
		}

		return array(false, $errors);
	}

	public function validateShelfId($shelfId)
	{
		if(!preg_match('/^[0-9a-zA-Z-]+/', $shelfId)){
			return '棚番号は半角英数とハイフンで入力してください。';
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

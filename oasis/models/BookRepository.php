<?php

class BookRepository extends BaseRepository
{

//-----------------------------------------------------
// バリデーション
//-----------------------------------------------------

	public function validateIsbn($isbn)
	{
		if(!preg_match("/^[0-9X]+$/", $isbn)){
			return false;
		}
		if(strlen($isbn) !== 10 && strlen($isbn) !== 13){
			return false;
		}

		return true;
	}

	public function validateShelfName($shelfName)
	{
		if(!preg_match('/^[0-9a-zA-Z-]+/', $shelfName)){
			return array(false, null);
		}

		list($result, $shelfId) = $this->fetchShelfIdByShelfName($shelfName);

		if($result === false){
			return array(false, null);
		}

		return array(true, $shelfId);
	}

	public function validateUrl($url)
	{
		if(preg_match('/(https?:\/\/)([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $url)){
			return true;
		}

		return false;
	}

	public function validateImageFile($imagePath)
	{
		if(!file_exists($imagePath)){
			return '画像の取得に失敗しました。';
		}

		$type = exif_imagetype($imagePath);
		if($type !== IMAGETYPE_GIF && $type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG){
			return '画像形式は、GIF、JEPG、PNGのいずれかにしてください。';
		}

		if(filesize($imagePath) >= 1000 * 1000){
			return '画像のサイズは1MB以下にしてください。';
		}

		return true;
	}

	public function validateCondition($condition)
	{
		if($condition !== 'AND' || $condition !== 'OR'){
			return 'AND';
		}

		return $condition;
	}

	public function validateOffset($offset)
	{
		$offset = (int)$offset;
		if(!is_int($offset)){
			return 0;
		}

		return $offset;
	}

	public function validateLimit($limit)
	{
		$limit = (int)$limit;
		if(!is_int($limit)){
			return 20;
		}

		return $limit;
	}

//-----------------------------------------------------
// PDO
//-----------------------------------------------------

	public function insert($isbn, $title, $author, $amazonUrl, $shelfId, $imageUrl)
	{
		$sql = "
			INSERT INTO book(isbn, title, author, amazon_url, shelf_id, image_url)
			VALUES(:isbn, :title, :author, :amazon_url, :shelf_id, :image_url)
		";
		$this->execute($sql, array(
			':isbn' => $isbn,
			':title' => $title,
			':author' => $author,
			':amazon_url' => $amazonUrl,
			':shelf_id' => $shelfId,
			':image_url' => $imageUrl,
		));
	}

	public function fetchShelfIdByShelfName($shelfName)
	{
		$sql = "
			SELECT * FROM shelf WHERE shelf_name = :shelf_name LIMIT 1
		";
		$row = $this->fetch($sql, array(':shelf_name' => $shelfName));

		if($row === false){	// 該当するレコードがない場合
			return array(false, null);
		}

		return array(true, $row['id']);
	}

	public function fetchById($bookId)
	{
		$sql = "
			SELECT
				b.id, isbn, title, author, amazon_url, shelf_name, image_url, status, deleted
			FROM
				book as b
			INNER JOIN shelf AS s ON shelf_id = s.id
			WHERE
				b.id = :book_id
		";
		return $this->fetch($sql, array(':book_id' => $bookId));
	}

	public function isDeleted($bookId)
	{
		$sql = "
			SELECT * FROM book WHERE id = :book_id LIMIT 1
		";
		$row = $this->fetch($sql, array(':book_id' => $bookId));

		if($row['deleted'] === '1'){
			return true;
		}

		return false;
	}

	public function isAvailable($bookId)
	{
		$sql = "
			SELECT * FROM book WHERE id = :book_id LIMIT 1
		";
		$row = $this->fetch($sql, array(':book_id' => $bookId));

		if($row['status'] === '1'){
			return true;
		}

		return false;
	}

	public function delete($bookId)
	{
		$sql = "
			UPDATE book SET deleted = 1 WHERE id = :book_id
		";
		$this->execute($sql, array(':book_id' => $bookId));
	}

	public function updateShelfId($bookId, $shelfId)
	{
		$sql = "
			UPDATE book SET shelf_id = :shelf_id WHERE id = :book_id
		";
		$this->execute($sql, array(':book_id' => $bookId, ':shelf_id' => $shelfId));
	}

	public function search($query, $condition, $offset, $limitNum, $isPaginate = true)
	{
		$query = str_replace('　', ' ', $query);
		$words = preg_split('/[ ]+/', $query);
		$variables = array();		

		$sql = "
			FROM
				book as b
			INNER JOIN shelf AS s ON shelf_id = s.id
			WHERE
				b.deleted = 0 AND (
		";

		foreach($words as $i => $word){
			// WHERE文の最初の部分にはAND、ORをつけない
			if($i !== 0) $sql .= $condition;
			$sql .= "
				CONCAT(isbn, title, author, shelf_name) LIKE :{$i} 
			";
			$variables[':' . $i] = "%{$word}%";
		}
		$sql .= ") ORDER BY b.id DESC ";

		// 検索結果のレコード数を取得するためのSQL
		$count = "
			SELECT COUNT(b.id) as count
		";
		$row = $this->fetch($count . $sql, $variables);

		// ページングした検索結果を取得するためのSQL
		$select = "
			SELECT
				b.id, isbn, title, author, amazon_url, shelf_name, image_url, status, deleted
		";

		$limit = "";

		if($isPaginate){
			$limit = "LIMIT {$offset}, {$limitNum}";
		}

		$bookDataList = $this->fetchAll($select . $sql . $limit, $variables);

		return array($bookDataList, $row['count']);
	}

//-----------------------------------------------------
// その他のメソッド
//-----------------------------------------------------

	public function convertToIsbn10($isbn)
	{
		//ISBN-13だった場合、ISBN-10へ変換する
		if(strlen($isbn) === 13){
			$isbn = substr($isbn, 3, 9);
			$checkDigit = 0;
			for($i = 10; $i>1; $i--){
				$checkDigit += (int)substr($isbn, 10-$i, 1) * $i;
			}
			$checkDigit = 11 - $checkDigit % 11;
			if($checkDigit === 10) $checkDigit = 'X';
			if($checkDigit === 11) $checkDigit = 0;
			return $isbn . $checkDigit;
		}
		return $isbn;
	}

	public function rfc3986_urlencode($str) {
		return str_replace('%7E', '~', rawurlencode($str));
	}

	public function getBookData($isbn)
	{
		// ISBNを10桁に変換する。
		$isbn = $this->convertToIsbn10($isbn);

		//アクセスキーID、シークレットアクセスキー、トラッキングID
		require dirname(__FILE__) . '/config.php';

		//必要なパラメータ
		$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
		$params = array();
		$params['Service'] = 'AWSECommerceService';
		$params['AWSAccessKeyId'] = $access_key_id;
		$params['AssociateTag'] = $associate_id;
		$params['Version'] = '2012-04-26';
		$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
		$params['Operation'] = 'ItemLookup';
		$params['ItemId'] = $isbn;
		$params['ResponseGroup'] = 'Images,Small';
		 
		//パラメータをキーでソート
		ksort($params); $string = '';
		foreach ($params as $k => $v) {
			$string .= '&' . $this->rfc3986_urlencode($k) . '=' . $this->rfc3986_urlencode($v);
		}

		//最初の&を取り除く
		$string = substr($string, 1);
			
		// 署名を作成
		$parsed_url = parse_url($baseurl);
		$str2sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$string}";
		$signature = base64_encode(hash_hmac('sha256', $str2sign, $secret_access_key, true));
			 
		// URL を作成
		$url = $baseurl . '?' . $string . '&Signature=' . $this->rfc3986_urlencode($signature);

		// XMLのレスポンス
		$response = file_get_contents($url);


		$errorCount = 1;
		while($response === false && $errorCount <= 3){
			sleep($errorCount);
			$response = file_get_contents($url);
			$errorCount++;
		}

		// 接続に失敗した場合は、ここでエラーを返す
		if($response === false){
			$error = 'AmazonAPIの接続に失敗しました。サーバーの時刻の設定を見直してください。';
			return array(false, $error, null);
		}

		// XMLをパース
		$parsed_xml = simplexml_load_string($response);

		// 該当するISBNが無い等API側のエラーが出た場合はここで返す。
		if(isset($parsed_xml->Items->Request->Errors->Error->Message)){
			$error = (string)$parsed_xml->Items->Request->Errors->Error->Message;
			return array(false, $error, null);
		}

		$bookData = array();
		$bookData['result'] = true;
		$bookData['title'] = (string)$parsed_xml->Items->Item->ItemAttributes->Title;
		$bookData['imageUrl'] = (string)$parsed_xml->Items->Item->LargeImage->URL;
		$bookData['amazonUrl'] = (string)$parsed_xml->Items->Item->DetailPageURL;

		$authorList = array();
		if(isset($parsed_xml->Items->Item->ItemAttributes->Author)){
			foreach($parsed_xml->Items->Item->ItemAttributes->Author as $author){
				$authorList[] = (string)$author;
			}
		}
		if(isset($parsed_xml->Items->Item->ItemAttributes->Creator)){
			foreach($parsed_xml->Items->Item->ItemAttributes->Creator as $creator){
				$authorList[] = (string)$creator;
			}
		}
		$bookData['author'] = implode(', ', $authorList);

		return array(true, null, $bookData);
	}

	public function saveImage($imagePath)
	{
		$imageId = '';

		list($width, $height, $type) = getimagesize($imagePath);

		$type = str_replace(array(1, 2, 3), array('.gif', '.jpg', '.png'), $type);

		$dir = dirname(__FILE__) . '/../web/media/';
		$fileName = hash_file('sha256', $imagePath) . $type;
		$path = $dir . $fileName;

		if(!file_exists($path)){	// 同じ画像が存在しなければ、画像を保存し、book_imageテーブルにパスとかを格納
			// $bookImagePathには、アップロードした場合はローカルのパスが、ISBNから入力した場合はURLが格納されている
			if(strpos($imagePath, 'http://') === false){
				move_uploaded_file($imagePath, $path);
			}else{
				$imageData = file_get_contents($imagePath);
				file_put_contents($path, $imageData);
			}

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
		}else{	// 同じ画像があれば、画像に対応したbook_imageテーブルのidを探す。
			$sql = "SELECT id FROM book_image WHERE name = :name";
			$row = $this->fetch($sql, array(':name' => $fileName));
			$imageId = $row['id'];
		}

		return $imageId;
	}
}

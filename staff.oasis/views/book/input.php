<h2>手動入力</h2>

<?php if(isset($errors) && count($errors)>0): ?>
<?php foreach($errors as $error): ?>
<?php echo $this->h($error) ?><br />
<?php endforeach; ?>
<?php endif; ?>

<form action="<?php echo $base_url ?>/book/save" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	ISBN<input type="text" name="isbn" value="<?php echo $this->h($isbn) ?>" /><br />
	書籍名<input type="text" name="title" value="<?php echo $this->h($title) ?>" /><br />
	著者名<input type="text" name="author" value="<?php echo $this->h($author) ?>" /><br />
	棚番号<input type="text" name="shelf_name" value="<?php echo $this->h($shelfName) ?>" /><br />
	画像URL<input type="text" name="amazon_url" value="<?php echo $this->h($imageUrl) ?>" /><br />
	<input type="file" name="book_image" /><br />
	<input type="submit" value="登録" />
</form>


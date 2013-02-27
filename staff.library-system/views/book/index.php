<h1>Book Controller Index</h1>

<h2>手動入力</h2>

<?php if(isset($errors) && count($errors)>0): ?>
<?php foreach($errors as $error): ?>
<?php echo $this->h($error) ?><br />
<?php endforeach; ?>
<?php endif; ?>

<form action="<?php echo $base_url ?>/book/save" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" /><br />
	ISBN<input type="text" name="isbn" value="<?php echo$this->h($isbn) ?>" /><br />
	書籍名<input type="text" name="title" value="<?php echo$this->h($title) ?>" /><br />
	棚番号<input type="text" name="shelf_id" value="<?php echo$this->h($shelfId) ?>" /><br />
	Amazon URL<input type="text" name="amazon_url" value="<?php echo $this->h($amazonUrl) ?>" /><br />
	<input type="file" name="book_image" /><br />
	<input type="submit" value="登録" />
</form>

<?php var_dump($dir) ?>

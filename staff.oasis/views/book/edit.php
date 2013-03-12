<h2>書籍情報編集</h2>

<?php if(isset($errors) && count($errors)>0): ?>
<?php foreach($errors as $error): ?>
<?php echo $this->h($error) ?><br />
<?php endforeach; ?>
<?php endif; ?>

<form action="<?php echo $base_url ?>/book/update" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	書籍名<input type="text" name="title" value="<?php echo $this->h($title) ?>" /><br />
	棚番号<input type="text" name="shelf_name" value="<?php echo $this->h($shelfName) ?>" /><br />
	Amazon URL<input type="text" name="amazon_url" value="<?php echo $this->h($amazonUrl) ?>" /><br />
	<input type="file" name="book_image" /><br />
	画像 URL<input type="text" name="image_url" value="<?php echo $this->h($imageUrl) ?>" /><br />
	<input type="submit" value="登録" />
</form>

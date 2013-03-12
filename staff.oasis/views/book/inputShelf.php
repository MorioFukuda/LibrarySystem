<h2>棚番号変更</h2>
<?php if(isset($error) && !empty($error)): ?>
<?php echo $this->h($error) ?>
<?php endif; ?>
<ul>
<li>書籍名：<a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></li>
<li>著者：<?php echo $this->h($bookData['author']) ?></li>
<li><img src="<?php echo $this->h($bookData['image_url'])?>" />
</ul>

<form action="<?php echo $base_url ?>/book/setShelf" method="post">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	<input type="hidden" name="book_id" value="<?php echo $this->h($bookData['id']) ?>" />
	<input type="text" name ="shelf_name" value="<?php echo $this->h($shelfName) ?>" />
	<input type="submit" value="変更" />
</form>


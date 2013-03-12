<h2>書籍情報削除</h2>

<?php if(isset($error) && !empty($error)): ?>
<?php echo $this->h($error) ?>
<?php endif; ?>
<ul>
<li>書籍名：<a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></li>
<li>著者：<?php echo $this->h($bookData['author']) ?></li>
<li>棚：<?php echo $this->h($bookData['shelf_name']) ?></li>
<li><img src="<?php echo $this->h($bookData['image_url'])?>" />
</ul>

<?php if(!isset($error)): ?>
<form action="<?php echo $base_url ?>/book/delete" method="post">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	<input type="hidden" name="book_id" value="<?php echo $this->h($bookData['id']) ?>" />
	<input type="submit" value="削除" />
</form>
<?php endif; ?>


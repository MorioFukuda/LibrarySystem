<h1 class="modal"><img src="/img/common/icon/mini_move.png">棚を変更変更</h1>
<?php if(isset($error) && !empty($error)): ?>
<?php echo $this->h($error) ?>
<?php endif; ?>

<div class="book_detail">
<img src="<?php echo $this->h($bookData['image_url'])?>" />
<dl>
	<dt>著者</dt>
	<dd><?php echo $this->h($bookData['author']) ?></dd>
	<dt>書籍名</dt>
	<dd><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></dd>
</dl>
</div>

<form action="<?php echo $base_url ?>/book/setShelf" method="post">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	<input type="hidden" name="book_id" value="<?php echo $this->h($bookData['id']) ?>" />
	<input type="text" name ="shelf_name" value="<?php echo $this->h($shelfName) ?>" />
	<input type="submit" value="変更" />
</form>


<?php $this->setLayoutVar('title', '以下の本を移動します。')?>

<img src="<?php echo $this->h($bookData['image_url'])?>" class="book_image" />
<div class="book_detail">
	<?php if(isset($error) && !empty($error)): ?>
	<span class="-btn -error-">エラー：<?php echo $this->h($error) ?></span>
	<?php endif; ?>
	<dl>
		<dt>著者</dt>
		<dd><?php echo $this->h($bookData['author']) ?></dd>
		<dt>書籍名</dt>
		<dd><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></dd>
	</dl>

<form action="<?php echo $base_url ?>/book/setShelf" method="post">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	<input type="hidden" name="book_id" value="<?php echo $this->h($bookData['id']) ?>" />
	<input type="text" name ="shelf_name" value="<?php echo $this->h($shelfName) ?>" />
	<input type="submit" value="変更" class="-btn -info-"/>
</form>

</div>

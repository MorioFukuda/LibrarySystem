<h1>以下の本を登録しました。</h1>
<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
	<div class="book_thumbnail">
		<span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span><br />
		<span class="book_author"><?php echo $this->h($bookData['author']) ?></span><br />
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image"/><br />
	</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

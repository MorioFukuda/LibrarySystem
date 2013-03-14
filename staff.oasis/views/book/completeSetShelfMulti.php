<h2>以下の書籍の棚番号を<?php echo $this->h($shelfName) ?>に変更しました。</h2>

<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
	<div class="book_thumbnail">
		<span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span><br />
		<span class="book_author"><?php echo $this->h($bookData['author']) ?></span><br />
		<span class="shelf_name"><?php echo $this->h($bookData['shelf_name']) ?></span><br />
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image"/><br />
	</div>
<?php endforeach; ?>
<?php endif; ?>

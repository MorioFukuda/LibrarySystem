<?php $this->setLayoutVar('title', '以下の本を移動しました。') ?>

<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
	<div class="book_thumbnail effect8">
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image" onclick="TINY.box.show({image: '<?php echo $this->h($bookData['image_url']) ?>', maskid: 'tinymask', boxid: 'frameless', animate: false, })"/>
		<div class="book_detail">
			<dl>
				<dt>収録棚<dt>
				<dd><span class="shelf_name"><?php echo $this->h($shelfName) ?></span></dd>
				<dt>著者</dt>
				<dd><span class="book_author"><?php echo $this->h($bookData['author']) ?></span></dd>
				<dt>タイトル</dt>
				<dd><span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span></dd>
			</dl>
		</div>
		<div class="clearfix"></div>
	</div>
<?php endforeach; ?>
<?php endif; ?>

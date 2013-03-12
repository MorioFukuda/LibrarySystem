<?php if(!empty($error)): ?>
<?php echo $this->h($error) ?>
<hr>
<?php endif;?>
<input type="text" name="isbn" value="" class="isbn -col1"/><br />
<img src="<?php echo $base_url ?>/img/common/loading.gif" alt="loadgin..." id="loading" />

<div id="book_list">
<?php $counter = 0; ?>
<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
	<div class="book_thumbnail">
		<span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span><br />
		<span class="book_author"><?php echo $this->h($bookData['author']) ?></span><br />
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image"/><br />
		<input type="button" class="delete" name="<?php echo $counter ?>" value="削除">
	</div>
<?php $counter++; ?>
<?php endforeach; ?>
<?php endif; ?>
</div>

<form action="<?php echo $base_url ?>/book/saveByIsbn" method="post" id="book_data">
<?php $counter = 0; ?>
<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
<input type="hidden" name="book_list[<?php echo $counter ?>][isbn]" value="<?php echo $this->h($bookData['isbn']) ?>">
<input type="hidden" name="book_list[<?php echo $counter ?>][title]" value="<?php echo $this->h($bookData['title']) ?>">
<input type="hidden" name="book_list[<?php echo $counter ?>][author]" value="<?php echo $this->h($bookData['author']) ?>">
<input type="hidden" name="book_list[<?php echo $counter ?>][image_url]" value="<?php echo $this->h($bookData['image_url']) ?>">
<input type="hidden" name="book_list[<?php echo $counter ?>][amazon_url]" value="<?php echo $this->h($bookData['amazon_url']) ?>">
<?php $counter++; ?>
<?php endforeach; ?>
	<input type="hidden" value="<?php echo $counter ?>" id="counter" />
<?php else: ?>
	<input type="hidden" value="0" id="counter" />
<?php endif; ?>
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	棚番号<input type="text" name="shelf_name" value="<?php echo $this->h($shelfName) ?>" /><br />
	<input type="submit" value="登録" id="book_submit" class="-btn" />
</form>


<?php $this->setLayoutVar('title', '本を登録する') ?>

<form action="<?php echo $base_url ?>/book/saveByIsbn" method="post" id="book_data" class="effect8">
ISBN<input type="text" name="isbn" value="" class="isbn"/>
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
	棚番号<input type="text" name="shelf_name" value="<?php echo $this->h($shelfName) ?>" />
	<input type="submit" value="登録" id="book_submit" class="-btn" />
<?php if(!empty($error)): ?>
<span class="-btn -error-">エラー：<?php echo $this->h($error) ?></span>
<?php endif;?>
</form>


<div id="book_list">
<?php $counter = 0; ?>
<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
	<div class="book_thumbnail effect8">
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image" onclick="TINY.box.show({image: '<?php echo $this->h($bookData['image_url']) ?>', maskid: 'tinymask', boxid: 'frameless', animate: false, })"/>
		<div class="book_detail">
			<dl>
				<dt>収録棚<dt>
				<dd><span class="shelf_name"><?php echo (isset($bookData['shelf_name'])) ? $this->h($bookData['shelf_name']) : "ー"; ?></span></dd>
				<dt>著者</dt>
				<dd><span class="book_author"><?php echo $this->h($bookData['author']) ?></span></dd>
				<dt>タイトル</dt>
				<dd><span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span></dd>
			</dl>
		</div>
		<input type="button" class="delete" name="<?php echo $counter ?>" value="削除">
		<div class="clearfix"></div>
	</div>
<?php $counter++; ?>
<?php endforeach; ?>
<?php endif; ?>
</div>


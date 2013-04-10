<?php $this->setLayoutVar('title', '本を登録する') ?>

<form action="<?php echo $base_url ?>/book/saveByIsbn" method="post" id="book_data">

<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />

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

<?php if(!empty($error)): ?>
<div id="form_oneline" class="effect8">
	エラー：<?php echo $this->h($error) ?>
</div>
<?php endif;?>

<div id="form_oneline" class="effect8">
	ISBN<input type="text" name="isbn" value="" class="isbn"/>
</div>

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
<div id="form_oneline" class="effect8">
	棚番号
	<select type="select" name="shelf_name1">
		<option <?php if($shelfName1 === 'A') echo 'selected' ?>>A</option>
		<option <?php if($shelfName1 === 'B') echo 'selected' ?>>B</option>
		<option <?php if($shelfName1 === 'C') echo 'selected' ?>>C</option>
		<option <?php if($shelfName1 === 'D') echo 'selected' ?>>D</option>
		<option <?php if($shelfName1 === 'e') echo 'selected' ?>>e</option>
		<option <?php if($shelfName1 === 'F') echo 'selected' ?>>F</option>
	</select>
	0
	<select type="select" name="shelf_name2">
		<option <?php if($shelfName2 === '0') echo 'selected' ?>>0</option>
		<option <?php if($shelfName2 === '1') echo 'selected' ?>>1</option>
		<option <?php if($shelfName2 === '2') echo 'selected' ?>>2</option>
		<option <?php if($shelfName2 === '3') echo 'selected' ?>>3</option>
		<option <?php if($shelfName2 === '4') echo 'selected' ?>>4</option>
		<option <?php if($shelfName2 === '5') echo 'selected' ?>>5</option>
		<option <?php if($shelfName2 === '6') echo 'selected' ?>>6</option>
		<option <?php if($shelfName2 === '7') echo 'selected' ?>>7</option>
		<option <?php if($shelfName2 === '8') echo 'selected' ?>>8</option>
		<option <?php if($shelfName2 === '9') echo 'selected' ?>>9</option>
	</select>
	<select type="select" name="shelf_name3">
		<option <?php if($shelfName3 === '0') echo 'selected' ?>>0</option>
		<option <?php if($shelfName3 === '1') echo 'selected' ?>>1</option>
		<option <?php if($shelfName3 === '2') echo 'selected' ?>>2</option>
		<option <?php if($shelfName3 === '3') echo 'selected' ?>>3</option>
		<option <?php if($shelfName3 === '4') echo 'selected' ?>>4</option>
		<option <?php if($shelfName3 === '5') echo 'selected' ?>>5</option>
		<option <?php if($shelfName3 === '6') echo 'selected' ?>>6</option>
		<option <?php if($shelfName3 === '7') echo 'selected' ?>>7</option>
	</select>
	<input type="submit" value="登録" id="book_submit" class="-btn" />
</div>

</form>

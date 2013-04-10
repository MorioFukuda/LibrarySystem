<?php $this->setLayoutVar('title', '本を移動する') ?>

<div id="move">
<?php if(isset($error) && !empty($error)): ?>
<div class="effect8 error_message">
	エラー：<?php echo $this->h($error) ?>
</div>
<?php endif; ?>
<input type="text" value="棚番号、書籍名、著者名、etc..." id="search_box" />
<br />
<select size="7" multiple id="candidate_list" class="book_list">
</select>
<br />
<input type="button" value="↓追加" id="add_list" />
<input type="button" value="削除↑" id="remove_list" />
<br />
<select size="7" multiple id="book_list" class="book_list">
<?php if(isset($bookDataList)): ?>
	<?php foreach($bookDataList as $bookData): ?>
		<option value="<?php echo $bookData['id'] ?>">【<?php echo $bookData['shelf_name']; ?>】<?php echo $bookData['title'] ?></option>
	<?php endforeach; ?>
<?php endif; ?>
</select>
<form action="<?php echo $base_url ?>/book/setShelfMulti" method="post">
<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
<?php if(isset($bookDataList)): ?>
	<?php foreach($bookDataList as $bookData): ?>
	<input type="hidden" name="book_id[]" value="<?php echo $bookData['id']?>" />
	<?php endforeach; ?>
<?php endif; ?>
<br />
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
	<br />
<input type="submit" value="変更" class="-btn"/>
</form>
</div>

<?php $this->setLayoutVar('title', '本を探す') ?>

<div class="box effect8">
	<div class="search_box">
		<form action="<?php echo $base_url ?>/book/search" method="post" class="-form">
		<input type="text" name="query" value="<?php echo $this->h($query) ?>" class="_big -form-field" /><br />
		AND:<input type="radio" name="condition" value="AND" <?php if(empty($condition) ||$condition === 'AND') echo 'checked' ?>  class="_big -form-field" />
		OR:<input type="radio" name="condition" value="OR" <?php if($condition === 'OR') echo 'checked' ?>  class="_big -form-field" />
		<select type="select" name="limit" class="_big -form-field">
			<option <?php if($limit === 10) echo 'selected' ?>>10</option>
			<option <?php if($limit === 20) echo 'selected' ?>>20</option>
			<option <?php if($limit === 50) echo 'selected' ?>>50</option>
			<option <?php if($limit === 100) echo 'selected' ?>>100</option>
		</select>
		<input type="submit" value="　探す　" class="_big -form-field -btn" />
		</form>
	</div>
</div>
<?php if(isset($bookDataList) && empty($bookDataList)): ?>
ごめんなさい、お探しの本は見つかりませんでした。
<?php elseif(isset($bookDataList)): ?>
<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $i => $bookData): ?>

	<div class="book_thumbnail effect8">
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image"/>
		<div class="book_detail">
			<dl>
				<dt>
					収録棚
				<dt>
				<dd>
					<span class="shelf_name"><?php echo $this->h($bookData['shelf_name'] )?></span>
				</dd>

				<dt>
					著者
				</dt>
				<dd>
					<span class="book_author"><?php echo $this->h($bookData['author']) ?></span>
				</dd>

				<dt>
					タイトル
				</dt>
				<dd>
				<span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span>
				</dd>
			</dl>
			<div class="icon_list">
				<span class="-btn -primary-" onclick="TINY.box.show({iframe: '<?php echo $base_url?>/book/inputShelf/<?php echo $this->h($bookData['id']) ?>', width: 700, height: 400, maskid: 'tinymask', boxid: 'frameless', animate: false, })">棚を移動</span>
				<span class="-btn -error-" onclick="TINY.box.show({iframe: '<?php echo $base_url?>/book/confirmDelete/<?php echo $this->h($bookData['id']) ?>', width: 700, height: 400, maskid: 'tinymask', boxid: 'frameless', animate: false, })">削除</span>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>

<?php endforeach; ?>
<?php endif; ?>
<div class="clearfix"></div>
<?php for($i=0; $i<=$resultNum; $i+=$limit): ?>
<?php echo "<a href=\"{$base_url}/book/search?query={$query}&condition={$condition}&offset={$i}&limit={$limit}\">" ?><?php echo floor($i/10)+1 ?></a>
<?php endfor; ?>
<?php endif; ?>

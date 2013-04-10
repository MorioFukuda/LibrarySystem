<?php $this->setLayoutVar('title', '本を探す') ?>

<div class="box effect8">
	<div class="search_box">
		<form action="<?php echo $base_url ?>/book/search" method="get" class="-form">
		<input type="text" name="query" value="<?php echo $this->h($query) ?>" class="_big -form-field" /><br />
		AND:<input type="radio" name="condition" value="AND" <?php if(empty($condition) ||$condition === 'AND') echo 'checked' ?>  class="_big -form-field" />
		OR:<input type="radio" name="condition" value="OR" <?php if($condition === 'OR') echo 'checked' ?>  class="_big -form-field" />
		<input type="hidden" name="offset" value="0">
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
<h2 class="sorry"><img src="/img/common/icon/sorry.png">ごめんなさい、お探しの本は見つかりませんでした。</h2>
<?php elseif(isset($bookDataList)): ?>
<?php if(isset($bookDataList)): ?>
<?php foreach($bookDataList as $i => $bookData): ?>

	<div class="book_thumbnail effect8">
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image" onclick="TINY.box.show({image: '<?php echo $this->h($bookData['image_url']) ?>', maskid: 'tinymask', boxid: 'frameless', animate: false, })"/>
		<div class="book_detail">
			<dl>
				<dt>収録棚<dt>
				<dd><span class="shelf_name"><?php echo $this->h($bookData['shelf_name'] )?></span></dd>
				<dt>著者</dt>
				<dd><span class="book_author"><?php echo (!empty($bookData['author'])) ? $this->h($bookData['author']) : 'ー' ?></span></dd>
				<dt>タイトル</dt>
				<dd><span class="book_title"><a href="<?php echo $this->h($bookData['amazon_url']) ?>" target="_blank"><?php echo $this->h($bookData['title']) ?></a></span></dd>
			</dl>
			<div class="icon_list">
				<span class="-btn -primary-" onclick="TINY.box.show({iframe: '<?php echo $base_url?>/book/inputShelf/<?php echo $this->h($bookData['id']) ?>', width: 700, height: 320, maskid: 'tinymask', boxid: 'frameless', animate: false, })">棚を移動</span>
				<?php if($bookData['status'] === '1'): ?>
					<span class="-btn -error-" onclick="TINY.box.show({iframe: '<?php echo $base_url?>/book/confirmDelete/<?php echo $this->h($bookData['id']) ?>', width: 700, height: 320, maskid: 'tinymask', boxid: 'frameless', animate: false, })">削除</span>
				<?php endif; ?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>

<?php endforeach; ?>
<?php endif; ?>
<div class="clearfix"></div>

<div id="pager">
<table><tbody><tr>
<?php
for($i=0; $i<$resultNum; $i+=$limit){
	echo "<td>";
	echo "<a href=\"{$base_url}/book/search?query={$query}&condition={$condition}&offset={$i}&limit={$limit}\">";
	echo "<img src=\"/img/common/icon/pager-o-normal.png\"><br />";
	echo (floor($i/$limit) + 1) . "</a>";
	echo "</td>";
}
?>
<td><img src="/img/common/icon/pager-asis.png"><br />　</td>
</tr></tbody></table>
</div>
<?php endif; ?>

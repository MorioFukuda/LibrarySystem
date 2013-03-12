<form action="<?php echo $base_url ?>/book/search" method="post">
<input type="text" name="query" value="<?php echo $this->h($query) ?>" /><br />
AND:<input type="radio" name="condition" value="AND" <?php if(empty($condition) ||$condition === 'AND') echo 'checked' ?> />
OR:<input type="radio" name="condition" value="OR" <?php if($condition === 'OR') echo 'checked' ?> />
<select type="select" name="limit">
	<option>10</option>
	<option>20</option>
	<option>50</option>
	<option>100</option>
</select>
<input type="submit" value="検索" />
</form>
<?php if(isset($bookDataList) && empty($bookDataList)): ?>
ごめんなさい、お探しの本は見つかりませんでした。
<?php elseif(isset($bookDataList)): ?>
<?php foreach($bookDataList as $bookData): ?>
	<div class="book_thumbnail">
		<span class="book_title"><?php echo $this->h($bookData['title']) ?></span><br />
		<span class="book_author"><?php echo $this->h($bookData['author']) ?></span><br />
		<span class="book_shelf"><a href="<?php echo $this->h($base_url) . '/book/inputShelf/' . $this->h($bookData['id']) ?>"><?php echo $this->h($bookData['shelf_name'] )?></a></span><br />
		<span class="book_shelf"><a href="<?php echo $this->h($base_url) . '/book/confirmDelete/' . $this->h($bookData['id']) ?>">削除</a></span><br />
		<img src="<?php echo $this->h($bookData['image_url']) ?>" class="book_image"/><br />
	</div>
<?php endforeach; ?>
<?php for($i=0; $i<=$resultNum; $i+=$limit): ?>
<?php echo "<a href=\"{$base_url}/book/search?query={$query}&condition={$condition}&offset={$i}&limit={$limit}\">" ?><?php echo floor($i/10)+1 ?></a>
<?php endfor; ?>
<?php endif; ?>

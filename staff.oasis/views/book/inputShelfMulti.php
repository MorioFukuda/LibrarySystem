<?php $this->setLayoutVar('title', '本を移動する') ?>

<div id="move">
<?php if(isset($error) && !empty($error)): ?>
<span class="-btn -error-">エラー：<?php echo $this->h($error) ?></span><br />
<?php endif; ?>
<input type="text" value="" id="search_box" />
<br />
<select size="7" multiple id="candidate_list">
</select>
<br />
<input type="button" value="↓追加" id="add_list" />
<input type="button" value="削除↑" id="remove_list" />
<br />
<select size="7" multiple id="book_list">
</select>
<form action="<?php echo $base_url ?>/book/setShelfMulti" method="post">
<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
棚番号<input type="text" name="shelf_name" value="<?php echo $this->h($shelfName) ?>" /><br />
<input type="submit" value="変更" class="-btn"/>
</form>
</div>

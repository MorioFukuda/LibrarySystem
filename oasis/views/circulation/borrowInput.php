<?php $this->setLayoutVar('title', '本を借りる') ?>

<form action="<?php echo $base_url ?>/circulation/borrowSave" method="post">
<input type="text" name="isbn">
</form>

<?php $this->setLayoutVar('title', 'ログイン') ?>

<form action="<?php echo $base_url ?>/account/authenticate" method="post" id="login_form" class="effect8">
<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
<?php if(isset($error) && !empty($error)): ?>
<span class="-btn -error-">エラー：<?php echo $this->h($error) ?></span><br />
<?php endif;?>
<input type="password" name="password">
<input type="submit" value="ログイン">
</form>

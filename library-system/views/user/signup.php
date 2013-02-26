<?php $this->setLayoutVar('title', 'アカウント登録') ?>

<h2>アカウント登録</h2>

<?php if(isset($errors) && count($errors)>0): ?>
<?php foreach($errors as $error): ?>
<?php echo $this->h($error) ?><br />
<?php endforeach; ?>
<?php endif; ?>

<form action="<?php echo $base_url; ?>/user/preregist" method="post">
	<input type="hidden" name="_token" value="<?php echo $this->h($_token) ?>" />
	ニックネーム<input type="text" name="name" value="<?php echo $this->h($name) ?>" /><br />
	メールアドレス<input type="text" name="email" value="<?php echo $this->h($email) ?>" /><br />
	パスワード<input type="password" name="password" value="<?php echo $this->h($password) ?>" /><br />
	<input type="submit" value="登録" />
</form>

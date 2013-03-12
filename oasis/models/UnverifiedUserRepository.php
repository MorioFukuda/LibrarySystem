<?php

class UnverifiedUserRepository extends BaseRepository
{
	public function insert($name, $email, $password)
	{

		$errors = array();

		if(empty($name)) $errors[] = 'ニックネームを入力してください。';
		if(empty($email)) $errors[] = 'メールアドレスを入力してください。';
		if(empty($password)) $errors[] = 'パスワードを入力してください。';

		if(!preg_match("/^[a-zA-Z0-9\.\-\_]+@[a-zA-Z0-9\.\-\_]+$/", $email) && count($errors) === 0){
			$errors[] = '不正なメールアドレスです。';
		}
		
		if(!preg_match('/^\w{3,20}$/', $name) && count($errors) === 0){
			$errors[] = 'ニックネームは半角英数字及びアンダースコアを3〜20文字以内で入力してください。';
		}

		if(!preg_match('/^\w{4,30}$/', $password) && count($errors) === 0){
			$errors[] = 'パスワードは半角英数字及びアンダースコアを4〜30文字以内で入力してください。';
		}

		if(!$this->isUniqueEmail($email) && count($errors) === 0){
			$errors[] = '既に登録されているメールアドレスです。';
		}

		if(!$this->isUniqueName($name) && count($errors) === 0){
			$errors[] = '既に登録されているニックネームです。';
		}

		if(count($errors) === 0){
			$salt = $this->createRandomString(30);
			$hashedPassword = $this->hashPassword($password, $salt);
			$token = $this->createRandomString(50);

			$sql = "
				INSERT INTO unverified_user(email, hashed_password, name, token, salt)
				VALUES(:email, :hashed_password, :name, :token, :salt)
			";

			$stmt = $this->execute($sql, array(
				':email' => $email,
				':hashed_password' => $hashedPassword,
				':name' => $name,
				':token' => $token,
				':salt' => $salt,
			));

			return array($errors, $stmt, $token);
		}

		return array($errors, false, null);
	}

	public function isUniqueEmail($email)
	{
		$sql = "SELECT COUNT(*) as count FROM user WHERE email = :email";
		$row = $this->fetch($sql, array(':email' => $email));

		if($row['count'] === '0'){
			return true;
		}

		return false;
	}

	public function isUniqueName($name)
	{
		$sql = "SELECT COUNT(*) as count FROM user WHERE name = :name";
		$row = $this->fetch($sql, array(':name' => $name));

		if($row['count'] === '0'){
			return true;
		}

		return false;
		
	}

	public function fetchByEmail($email)
	{
		$sql = "
			SELECT * FROM unverified_user
			WHERE email = :email
			ORDER BY created_at DESC
			LIMIT 1
		";

		return $this->fetch($sql, array(':email' => $email));
	}

	public function deleteByEmail($email)
	{
		$sql = "
			DELETE FROM unverified_user
			WHERE email = :email
		";

		return $this->execute($sql, array('email' => $email));
	}
}

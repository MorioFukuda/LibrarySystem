<?php

class UserRepository extends BaseRepository
{
	public function insert($name, $email, $hashedPassword, $salt)
	{
		if(!isset($name, $email, $hashedPassword, $salt)) return false;

		$sql = "
			INSERT INTO user(name, email, hashed_password, salt)
			VALUES(:name, :email, :hashed_password, :salt)
		";

		$stmt = $this->execute($sql, array(
			':name' => $name,
			':email' => $email,
			':hashed_password' => $hashedPassword,
			':salt' => $salt,
		));
	}

	public function fetchByEmail($email)
	{
		$sql = "
			SELECT * FROM user
			WHERE email = :email
			LIMIT 1
		";

		return $this->fetch($sql, array(':email' => $email));
	}
}

<?php

class BaseRepository extends DbRepository
{
	public function createRandomString($length)
	{
		$charList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$randomString = '';

		for($i = 0; $i < $length; $i++){
			$randomString .= $charList[mt_rand(0, strlen($charList) - 1)];
		}

		return $randomString;
	}

	public function hashPassword($password, $salt)
	{
		$hashedPassword = $password . $salt;

		for($i = 0; $i < 1000; $i++){
			$hashedPassword = hash('sha256', $hashedPassword . $salt);
		}

		return $hashedPassword;
	}

	public function hasEmpty()
	{
		$args = func_get_args();

		foreach($args as $arg){
			if(empty($arg)) return true;
		}

		return false;
	}

}

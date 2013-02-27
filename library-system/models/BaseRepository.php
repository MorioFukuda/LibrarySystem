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

	public function validateIsbn($isbn)
	{

		if(!preg_match("/^[0-9]+$/", $isbn)){
			return 'ISBNは、ハイフンを除いた半角数字で入力してください。';
		}

		if(strlen($isbn) !== 10 && strlen($isbn) !== 13){
			return '正しくないISBN番号です。';
		}

		return true;
	}

}

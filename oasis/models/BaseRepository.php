<?php

class BaseRepository extends DbRepository
{
	public function convertToIsbn10($isbn)
	{
		//ISBN-13だった場合、ISBN-10へ変換する
		if(strlen($isbn) === 13){
			$isbn = substr($isbn, 3, 9);
			$checkDigit = 0;
			for($i = 10; $i>1; $i--){
				$checkDigit += (int)substr($isbn, 10-$i, 1) * $i;
			}
			$checkDigit = 11 - $checkDigit % 11;
			if($checkDigit === 10) $checkDigit = 'X';
			if($checkDigit === 11) $checkDigit = 0;
			return $isbn . $checkDigit;
		}
		return $isbn;
	}

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

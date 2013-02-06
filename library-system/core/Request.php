<?php

class Request
{

	public function isPost()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			return true;
		}

		return false;
	}

	public function getGet($name, $default = null)
	{
		if(isset($_GET[$name]){
			return $_GET[$name];
		}

		return $default;
	}

	public function getPost($name, $default = null)
	{
		if(isset($_POST[$name]){
			return $_POST[$name];
		}

		return $default;
	}

	public function getHost()
	{
		if(!empty($_SERVER['HTTP_HOST'])){
			return $_SERVER['HTTP_HOST'];
		}

		return $_SERVER['SERVER_NAME'];
	}

	public function isSsl()
	{
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
			return true;
		}

		return false;
	}

	public function getRequestUri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public function getBaseUrl()
	{
		// http://www.biuuu.com/index.php?p=222&q=biuuu結果：
		// $_SERVER["REQUEST_URI"] = "/index.php?p=222&q=biuuu"
		// $_SERVER["SCRIPT_NAME"] = "/index.php"

		$script_name = $_SERVER['SCRIPT_NAME'];

		$request_uri = $this->getRequestUri();

		// strpos(この文字列の中から, この文字列)が最初に現れる場所を返す。（1文字目は0）
		if(0 === strpos($request_uri, $script_name)){
			return $script_name;
		}else if(0 === strpos($request_uri, dirname($script_name))){
			return rtrim(dirname($script_name), '/');
		}

		return '';
	}

	public function getPathInfo()
	{
		$base_url = $this->getBaseUrl();
		$request_uri = $this->getRequestUri();

		// string substr ( string $string , int $start [, int $length ] )
		//  文字列 string の、start で指定された位置から length バイト分の文字列を返します。 
		if(false !== ($pos = strpos($request_uri, '?')){
			$request_uri = substr($request_uri, 0, $pos);
		}

		$path_info = (string)substr($request_uri, $strlen($base_url));

		return $path_info;
	}
}

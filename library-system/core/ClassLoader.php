<?php

class ClassLoader
{

	protected $dirs;

	public function register()
	{
		// クラスを呼び出した際にそのクラスがPHP上に読み込まれていない場合に、
		// このクラスのloadClassメソッドが実行される。
		spl_autoload_register(array($this, 'loadClass'));
	}

	public function registerDir($dir)
	{
		$this->dirs[] = $dir;
	}

	public function loadClass($class)
	{
		foreach($this->dirs as $dir){
			$file = $dir . '/' . $class . '.php';
			if(is_readable($file)){
				require $file;
				return;
			}
		}
	}
}

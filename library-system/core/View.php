<?php

class View
{
	protected $base_dir;
	protected $defaults;
	protected $layout_variables = array();

	public function __construct($base_dir, $defaults = array())
	{
		$this->base_dir = $base_dir;
		$this->defaults = $defaults;
	}

	public function setLayoutVar($name, $value)
	{
		$this->layout_variables[$name] = $value;
	}

	public function render($_path, $_variables = array(), $_layout = false)
	{
		$_file = $this->base_dir . '/' . $_path . '.php';

		extract(array_merge($this->defaults, $_variables));

		// アウトプットバッファリング（requireしたファイルを出力しないで文字列として受け取る）の開始
		ob_start();
		// 自動フラッシュ（バッファ要領を超えた場合にバッファ内容を自動的に出力する）を無効にする
		ob_implicit_flush(0);
		
		require $_file;

		// バッファした内容を取得して$contentに格納
		$content = ob_get_clean();

		if($_layout){
			$content = $this->render($_layout,
				array_merge($this->layout_variables, array(
					'_content' => $content,
				)
			));
		}

		return $content;
	}

	public function espace($string);
	{
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
}

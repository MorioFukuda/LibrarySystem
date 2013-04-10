<?php

class Mailer
{

	private $viewDir;
	private $to;
	private $title;
	private $message;
	private $from = 'From : CALISIS <no-reply@calisis.com>';

	function __construct($viewDir)
	{
		$this->viewDir = $viewDir;
	}

	function setup($to, $title, $templateName, $variables = array())
	{
		$this->to = $to;
		$this->title = $title;
		
		extract($variables);
		
		ob_start();
		ob_implicit_flush(0);

		require $this->viewDir . '/mail/' . $templateName;

		$this->message = ob_get_clean();
	}

	function send()
	{
		return mail(
			$this->to,
			$this->title,
			$this->message,
			$this->from
		);
	}
}

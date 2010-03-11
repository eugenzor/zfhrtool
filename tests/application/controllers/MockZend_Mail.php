<?php

class Zend_Mail {
	static $bodyHtml, $bodyText;

	function __call($name, $params){
		return $this;
	}
	

	public function setBodyHtml($html)
	{
		self::$bodyHtml = $html;
		return $this;
	}
	
	public function setBodyText($text)
	{
		self::$bodyText = $text;
		return $this;
	}
}

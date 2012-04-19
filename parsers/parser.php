<?php
abstract class parser
{
	protected $_url;
	protected $_params;
	
	public function __construct($url, $params)
	{
		$this->_url = $url;
		$this->_params = $params;
	}
	
	public abstract function getContents();
}
?>
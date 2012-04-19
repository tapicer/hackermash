<?php
require_once 'parsers/rssparser.php';
require_once 'libs/phpQuery.php';

class ycombinatorparser extends rssparser
{
	public function getContents()
	{
		$contents = parent::getContents();
		
		$new_contents = array();
		
		foreach ($contents as $item)
		{
			unset($item['description']);
			
			$matchFilter = false;
			
			foreach ($this->_params as $filter)
			{
				if (preg_match("/$filter/i", $item['title']))
				{
					$matchFilter = true;
					break;
				}
			}
			
			if (!$matchFilter) continue;
			
			$new_contents[] = $item;
		}
		
		return $new_contents;
	}
}
?>
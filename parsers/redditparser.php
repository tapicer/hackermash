<?php
require_once 'parsers/rssparser.php';

class redditparser extends rssparser
{
	public function getContents()
	{
		$contents = parent::getContents();
		
		$new_contents = array();
		
		foreach ($contents as $item)
		{
			preg_match_all('/<a href="([^"]+)">[^<]+<\/a>/i', $item['description'], $matches);
			
			$item['url'] = $matches[1][1];
			
			if (!preg_match('/https?:\/\/[^\/]*(reddit.com|imgur.com)\//i', $item['url']))
			{
				$new_contents[] = $item;
			}
		}
		
		return $new_contents;
	}
}
?>
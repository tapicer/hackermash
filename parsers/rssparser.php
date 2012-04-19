<?php
require_once 'parsers/parser.php';

class rssparser extends parser
{
	public function getContents()
	{
		require_once 'libs/simplepie.inc';
		
		$feed = new SimplePie();
		$feed->enable_cache(false);
		$feed->set_feed_url($this->_url);
		$feed->init();
		
		$ret = array();
		foreach ($feed->get_items() as $item)
		{
			$ret[] = array(
					'url' => $item->get_link(),
					'title' => $item->get_title(),
					'description' => $item->get_description(),
				);
		}
		
		return $ret;
	}
}
?>
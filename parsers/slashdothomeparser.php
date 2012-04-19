<?php
require_once 'parsers/parser.php';
require_once 'libs/phpQuery.php';

class slashdothomeparser extends parser
{
	public function getContents()
	{
		$contents = array();
		if ($html = @file_get_contents($this->_url))
		{
			phpQuery::newDocumentHTML($html, 'UTF-8');
			
			foreach (pq('.body a') as $link)
			{
				$href = $link->getAttribute('href');
				if (strpos($href, '//') === 0)
				{
					$href = 'http:' . $href;
				}
				if ($link->getAttribute('rel') != 'nofollow')
				{
					$contents[] = array(
							'url' => $href,
							'title' => $link->textContent,
						);
				}
			}
		}
		return $contents;
	}
}
?>
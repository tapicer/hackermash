<?php
require_once 'parsers/parser.php';
require_once 'libs/phpQuery.php';

class slashdottagparser extends parser
{
	public function getContents()
	{
		$contents = array();
		$max = 5;
		if ($html = @file_get_contents($this->_url))
		{
			phpQuery::newDocumentHTML($html, 'UTF-8');
			foreach (pq('.data tr a') as $link)
			{
				$href = $link->getAttribute('href');
				if (strpos($href, '//') === 0)
				{
					$href = 'http:' . $href;
				}
				if (strpos($href, '/story/') === false) continue;
				if ($html = @file_get_contents($href))
				{
					phpQuery::newDocumentHTML($html, 'UTF-8');
					foreach (pq('.body a') as $link)
					{
						if ($link->getAttribute('rel') != 'nofollow')
						{
							$contents[] = array(
									'url' => $link->getAttribute('href'),
									'title' => $link->textContent,
								);
						}
					}
					$max--;
					if ($max == 0) break;
				}
			}
		}
		return $contents;
	}
}
?>
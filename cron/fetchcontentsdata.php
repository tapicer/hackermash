<?php
require_once 'entities/content.php';
require_once 'libs/phpQuery.php';
require_once 'etc/utils.php';

set_time_limit(0);
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('nop');

$contents = content::listBy(array('fetched' => false), 'random()');

$req_context = stream_context_create(array(
										'http' => array( 
											'method' => 'GET',
											'user_agent' => 'HackerMashBot',
										)
									));

$tags = tag::listAll();

foreach ($contents as $content)
{
	//echo "Fetching {$content->url}<br/>";
	file_put_contents('log.txt', date('Y-m-d H:i:s') . ': fetching ' . $content->url . "\n", FILE_APPEND | LOCK_EX);
	
	$url = rep_escaped_frag_ajax($content->url);
	
	if (preg_match('#^https?://plus\.google\.com/#i', $url) ||
		preg_match('#^https?://(www\.)?hackermash\.com/#i', $url) ||
		preg_match('/\.(jpe?g|png|gif|bmp)$/i', $url))
	{
		$html = false;
	}
	else
	{
		$html = @file_get_contents($url, false, $req_context);
	}
	
	if ($html)
	{
		try
		{
			if ($encoding = mb_detect_encoding($html))
			{
				$html = mb_convert_encoding($html, 'HTML-ENTITIES', $encoding);
			}
			phpQuery::newDocumentHTML($html, 'UTF-8');
			pq('script')->remove();
			if (preg_match('#^https?://(www\.)?youtube\.com/#i', $content->url))
			{
				pq('#default-language-box')->remove();
			}
			
			//title
			$h1Node = pq('h1');
			$h1 = null;
			if (count($h1Node) == 1)
			{
				$content->title = trim(pqfirst($h1Node)->textContent);
				
				$h2Node = pq('h2');
				$h2 = null;
				if (count($h2Node) == 1)
				{
					$h2 = trim(pqfirst($h2Node)->textContent);
					
					if ($h2)
					{
						if ($content->title)
						{
							$content->title .= ' - ' . $h2;
						}
						else
						{
							$content->title = $h2;
						}
					}
				}
			}
			
			if (!$content->title || strlen($content->title) < 20)
			{
				$titleNode = pq('head > title');
				$title = null;
				if (count($titleNode) == 1)
				{
					$content->title = trim(pqfirst($titleNode)->textContent);
				}
			}
			
			$content->title = clean_spaces($content->title);
			
			$content->title = strip_tags($content->title);
			
			//description
			$maxLength = 1000;
			$content->description = '';
			foreach (pq('p') as $p)
			{
				$txt = str_replace(array("\n", "\r"), '', clean_spaces($p->textContent));
				if (strtolower($txt) == strtolower($content->title) || strlen($txt) < 200) continue;
				$content->description .=  $txt . "\n";
				if (strlen($content->description) >= $maxLength) break;
			}
			
			if (strlen($content->description) < $maxLength)
			{
				foreach (pq('div') as $d)
				{
					$txt = str_replace(array("\n", "\r"), '', clean_spaces($d->textContent));
					if (strtolower($txt) == strtolower($content->title) || strlen($txt) < 200 || children_uni($d)) continue;
					$content->description .= $txt . "\n";
					if (strlen($content->description) >= $maxLength) break;
				}
			}
			
			$content->description = clean_spaces($content->description);
			
			$content->description = strip_tags($content->description);
			
			if (strlen($content->description) > $maxLength)
			{
				$content->description = trim(substr($content->description, 0, $maxLength)) . '...';
			}
			
			//fetched
			$content->fetched = true;
			
			//ignored
			$content->ignored = empty($content->title) || empty($content->description);
			
			//slug
			$slug = slugify($content->title);
			
			if (is_numeric($slug))
			{
				$content->ignored = true;
			}
			else
			{
				$slug_orig = $slug;
				
				$i = 0;
				while (content::getBy(array('slug' => $slug)))
				{
					$i++;
					$slug = $slug_orig . '-' . $i;
				}
			}
			
			$content->slug = $slug;

			$content->lastseen = date('Y-m-d H:i:s');
			
			$content->save();
			
			$content->processTags($tags);
		}
		catch (Exception $e)
		{
			$content->fetched = true;
			$content->ignored = true;
			$content->title = '';
			$content->description = '';
			//$content->slug = '';
			$content->save();
		}
	}
	else
	{
		$content->fetched = true;
		$content->ignored = true;
		$content->save();
	}
}

function pqfirst($elems)
{
	foreach ($elems as $elem) return $elem;
	return null;
}

function clean_spaces($text)
{
	return preg_replace('/ +/', ' ', trim($text));
}

function children_uni($node)
{
	$quant = 0;
	if ($node->childNodes->length == 1 && substr_count(clean_spaces($node->textContent), ' ') <= 2)
	{
		return true;
	}
	foreach (pq('*', $node) as $child)
	{
		$txt = clean_spaces($child->textContent);
		if ($txt && substr_count($txt, ' ') <= 2)
		{
			$quant++;
		}
		if ($quant == 3) return true;
	}
	return false;
}

function rep_escaped_frag_ajax($url)
{
	$pos = strpos($url, '#!');
	if ($pos !== false)
	{
		$url = substr($url, 0, $pos) . '?_escaped_fragment_=' . str_replace('&', '%26', substr($url, $pos + 2));
	}
	return $url;
}
?>

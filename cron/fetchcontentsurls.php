<?php
require_once 'entities/source.php';
require_once 'entities/content.php';
require_once 'etc/utils.php';

set_time_limit(0);
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('nop');

$sources = source::listAll();

$contents_to_save = array();

foreach ($sources as $source)
{
	$parser = $source->parser . 'parser';
	require_once "parsers/$parser.php";
	$parser = new $parser($source->url, json_decode($source->parser_params));
	foreach ($parser->getContents() as $cont)
	{
		$cont['url'] = clean_url($cont['url']);
		if (!($content = content::getBy(array('url' => $cont['url']))))
		{
			$content = new content();			
			$content->url = $cont['url'];
			$content->fetched = false;
			$content->ignored = false;
			$content->categoryid = $source->categoryid;
		}
		$contents_to_save[] = $content;
	}
}

shuffle($contents_to_save);

$time = time();

foreach ($contents_to_save as $content)
{
	$content->lastseen = date('Y-m-d H:i:s', $time);
	$time -= rand(15, 45);
	$content->save();
}
?>
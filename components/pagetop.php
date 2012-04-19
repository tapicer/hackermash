<?php
$title = 'HackerMash: news about coding and related stuff for hackers';
if (isset($extraTitle))
{
	$title = $extraTitle . " - " . $title; 
}
if (isset($customTitle))
{
	$title = $customTitle;
}
$description = 'HackerMash is a mashup of categorized news about coding and related stuff found around the web in different relevant sources.';
$subTitleDescription = $description;
if (isset($customDescription))
{
	$description = $customDescription;
	if (strlen($description) > 150)
	{
		$description = substr($description, 0, 150) . '...';
	}
}
$keywords = 'hacker, news, programming, coding, technology, science, linux, android, gaming'
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?=str_replace('"', '&quot;', $title)?></title>
	<link rel="stylesheet" type="text/css" href="/static/css/reset-min.css" />
	<link rel="stylesheet" type="text/css" href="/static/css/main.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="<?=str_replace('"', '&quot;', $description)?>" /> 
	<meta name="keywords" content="<?=str_replace('"', '&quot;', $keywords)?>" />
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-24459925-1']);
		_gaq.push(['_trackPageview']);
		_gaq.push(['_trackPageLoadTime']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head>
<body>
	<div id="wrap">
		<div id="socialBox">
			<div class="socialItem">
				<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?=conf::$SiteURL?>" data-count="vertical"></a>
			</div>
			<div class="socialItem" style="width:51px;">
				<iframe src="http://www.facebook.com/plugins/like.php?app_id=225577620810383&amp;href=<?=urlencode(conf::$SiteURL)?>&amp;send=false&amp;layout=box_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:65px;" allowTransparency="true"></iframe>
			</div>
			<div class="socialItem last">
				<g:plusone size="tall" href="<?=conf::$SiteURL?>"></g:plusone>
			</div>
		</div>
		<div id="head">
			<h1><a href="/">HackerMash</a></h1>
			<p class="sub_title_desc"><?=$subTitleDescription?></p>
			<div class="menu">
				<ul>
					<?php $current = isset($rulesCaptures['category']) ? $rulesCaptures['category'] : 'all'; ?>
					<li<?=($current == 'all' ? ' class="current"' : '')?>><a href="/">All</a></li>
					<?php foreach ($categories as $category): ?>
					<li<?=($current == $category->id ? ' class="current"' : '')?>><a href="/<?=$category->id?>"><?=$category->name?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<div id="body">
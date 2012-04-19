<?php
require_once 'entities/content.php';
require_once 'entities/tag.php';

//prepare paging data
$page = 1;
$contentsPerPage = 10;
$hasNextPage = false;
if (isset($rulesCaptures['page']))
{
	$page = intval($rulesCaptures['page']);
}

//get data
if (isset($rulesCaptures['tag']))
{
	$tag = tag::getBy(array('name' => $rulesCaptures['tag']));
	if (!$tag)
	{
		$tag = tag::getBy(array('name' => str_replace('-', ' ', $rulesCaptures['tag'])));
	}
	if ($tag)
	{
		$extraTitle = $tag->name;
		$contents = content::listByTagId($tag->id, $contentsPerPage + 1, ($page - 1) * $contentsPerPage);
	}
	else
	{
		$contents = array();
	}
}
else
{
	$filters = array('fetched' => true, 'ignored' => false);
	if (isset($rulesCaptures['category']))
	{
		$filters['categoryid'] = $rulesCaptures['category'];
		$extraTitle = $rulesCaptures['category'];
	}
	$contents = content::listBy($filters, array('lastseen' => 'desc'), $contentsPerPage + 1, ($page - 1) * $contentsPerPage);
}
if (count($contents) == $contentsPerPage + 1)
{
	$hasNextPage = true;
	array_pop($contents);
}
$short_description_length = 200;
foreach ($contents as $content)
{
	$content->fillExtraData();
	$content->short_description = str_replace("\n", ' ', $content->description);
	if (strlen($content->short_description) > $short_description_length)
	{
		$content->short_description = trim(substr($content->short_description, 0, $short_description_length)) . '...';
	}
}

//build paging
$showPrevLink = $page > 1;
$showNextLink = $hasNextPage;
$link = '';
if (isset($rulesCaptures['category']))
{
	$link = '/' . $rulesCaptures['category'];
}
if (isset($rulesCaptures['tag']))
{
	$link = '/tag/' . $rulesCaptures['tag'];
}
if ($showPrevLink)
{
	$prevLink = $link;
	if ($page > 2)
	{
		$prevLink = $link . '/' . ($page - 1);
	}
	else if (strlen($prevLink) == 0)
	{
		$prevLink = '/';
	}
}
if ($showNextLink)
{
	$nextLink = $link . '/'. ($page + 1);
}
?>

<?php require 'components/pagetop.php'; ?>

<div class="contents">
	<?php foreach ($contents as $content): ?>
	<div class="content">
		<div class="cat">
			<a href="/<?=$content->categoryid?>"><?=$content->categoryid?></a>
		</div>
		<div class="lnk">
			<h2><a href="/<?=$content->categoryid?>/<?=$content->slug?>"><?=$content->title?></a></h2>
			<div class="desc"><?=$content->short_description?> <a href="/<?=$content->categoryid?>/<?=$content->slug?>">[more]</a></div>
			<div class="sub">last seen <?=$content->seenago?> ago - <a href="<?=$content->url?>">go to link</a> - seen at <b><?=$content->domain?></b></div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="paging">
		<?php if ($showPrevLink): ?>
			<a href="<?=$prevLink?>">Previous page</a>
		<?php endif; ?>
		<?php if ($showPrevLink && $showNextLink): ?>
			|
		<?php endif; ?>
		<?php if ($showNextLink): ?>
			<a href="<?=$nextLink?>">Next page</a>
		<?php endif; ?>
	</div>
</div>

<?php require 'components/pagebottom.php'; ?>
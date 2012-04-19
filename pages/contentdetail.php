<?php
require_once 'entities/content.php';
require_once 'entities/content_tag.php';
require_once 'entities/tag.php';

$content = content::getBy(array('slug' => $rulesCaptures['slug']));
if (!$content)
{
	header('location: /');
	exit;
}
$content->fillExtraData();
$customTitle = $content->title;
$customDescription = str_replace("\n", ' ', $content->description);
$contentDescParts = explode("\n", $content->description);
$currentUrl = conf::$SiteURL . '/' . $content->categoryid . '/' . $content->slug;
$tags = content_tag::listTagNamesByContentId($content->id);
?>

<?php require 'components/pagetop.php'; ?>

<h2 class="big"><a href="<?=$content->url?>"><?=$content->title?></a></h2>

<div class="content_data">
	<div>last seen <?=$content->seenago?> ago - <a href="<?=$content->url?>">go to link</a> - seen at <b><?=$content->domain?></b></div>
	<?php if ($tags): ?>
		<div style="padding-top:5px;">
			tags:
			<?php $first = true; ?>
			<?php foreach ($tags as $tag): ?><?php if (!$first): ?>, <?php else: $first = false; endif; ?><a href="/tag/<?=str_replace(' ', '-', $tag)?>"><?=$tag?></a><?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php foreach ($contentDescParts as $descPart): ?>
<p><?=$descPart?></p>
<?php endforeach; ?>

<div id="disqus_thread"></div>
<script type="text/javascript">
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://hackermash.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>

<div class="social">
	<div class="tit">Share this article</div>
	<div class="socialItem">
		<a href="http://twitter.com/share" class="twitter-share-button" data-url="<?=$currentUrl?>" data-count="vertical"></a>
	</div>
	<div class="socialItem" style="width:51px;">
		<iframe src="http://www.facebook.com/plugins/like.php?app_id=225577620810383&amp;href=<?=urlencode($currentUrl)?>&amp;send=false&amp;layout=box_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:65px;" allowTransparency="true"></iframe>
	</div>
	<div class="socialItem last">
		<g:plusone size="tall" href="<?=$currentUrl?>"></g:plusone>
	</div>
</div>

<?php require 'components/pagebottom.php'; ?>
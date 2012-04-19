<?php
require_once 'entities/entity.php';
require_once 'entities/tag.php';
require_once 'entities/content_tag.php';
require_once 'etc/utils.php';

class content extends entity
{
	public $id;
	public $categoryid;
	public $url;
	public $fetched;
	public $title;
	public $description;
	public $ignored;
	public $lastseen;
	public $slug;
	public $comments;
	
	public function fillExtraData()
	{
		$now = time();
		$this->seenago = format_duration($now - DateTime::createFromFormat('Y-m-d H:i:s', $this->lastseen)->getTimestamp());
		$url_info = parse_url($this->url);
		$this->domain = $url_info['host'];
	}
	
	public function processTags($tags = null)
	{
		if ($tags === null)
		{
			$tags = tag::listAll();
		}
		$text = $this->title . ' ' . $this->description;
		foreach ($tags as $tag)
		{
			$t = preg_quote($tag->name);
			if (preg_match("/\b{$t}\b/i", $text))
			{
				$content_tag = new content_tag();
				$content_tag->contentid = $this->id;
				$content_tag->tagid = $tag->id;
				$content_tag->save();
			}
		}
	}
	
	public static function listByTagId($tagid, $limit, $offset)
	{
		$rows = self::query(
			"select
				c.*
			from
				content_tag ct
				inner join content c on ct.contentid = c.id
			where
				ct.tagid = ? and
				c.fetched and
				not c.ignored
			order by
				c.lastseen desc
			limit $limit offset $offset
			", array($tagid));
		$entities = array();
		foreach ($rows as $row)
		{
			$entities[] = self::rowToEntity($row, 'content');
		}
		return $entities;
	}
}
?>
<?php
require_once 'entities/entity.php';

class content_tag extends entity
{
	public $contentid;
	public $tagid;
	
	public static function listTagNamesByContentId($contentid)
	{
		return self::query('select t.name from content_tag ct inner join tag t on ct.tagid = t.id where ct.contentid = ? order by t.name', array($contentid), PDO::FETCH_COLUMN);
	}
}
?>
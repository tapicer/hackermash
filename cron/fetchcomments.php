<?php
set_time_limit(0);
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('nop');

require_once 'entities/content.php';

$contents = content::listBy(array('fetched' => true), 'random()');

foreach ($contents as $content)
{
	$url = 'http://www.hackermash.com/' . $content->categoryid . '/' . $content->slug;
	$threads_data =  @json_decode(@file_get_contents('http://disqus.com/api/3.0/threads/list.json?api_key=n4MFYfPZtXbWVfjeDdDHelzRkB1qGkYTb05jt0Bj2mvZzatxGWzRdlF8v6gtc8mV&forum=hackermash&thread=link:' . $url));
	$thread_id = @current($threads_data->response)->id;
	
	$comments = array();
	
	if ($thread_id)
	{
		$posts_data =	 @json_decode(@file_get_contents('http://disqus.com/api/3.0/posts/list.json?api_key=n4MFYfPZtXbWVfjeDdDHelzRkB1qGkYTb05jt0Bj2mvZzatxGWzRdlF8v6gtc8mV&thread=' . $thread_id));
		
		foreach ($posts_data->response as $post)
		{
			$comments[] = 'Comment by ' . $post->author->name . ':<br/>' . $post->raw_message;
		}
	}
	
	$comments = implode('<br/>', $comments);
	$content->comments = $comments;
	$content->save();
	
	sleep(5);
}
?>
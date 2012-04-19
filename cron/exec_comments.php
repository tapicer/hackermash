<?php
require_once '../conf.php';
if (intval(exec('ps aux | grep exec_comments\.php | grep -v grep | wc -l')) >= 3) exit;
set_time_limit(0);
file_get_contents(conf::$SiteURL . '/fetchcomments', false, stream_context_create(
	array(
		'http' => array(
			'method' => 'GET',
			'timeout' => 60 * 60, //1 hour
		)
	)));
?>
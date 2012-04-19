<?php
require_once '../conf.php';
if (intval(exec('ps aux | grep exec\.php | grep -v grep | wc -l')) >= 3) exit;
set_time_limit(0);
echo file_get_contents(conf::$SiteURL . '/fetchcontents', false, stream_context_create(
	array(
		'http' => array(
			'method' => 'GET',
			'timeout' => 60 * 60, //1 hour
		)
	)));
?>
<?php
set_time_limit(0);
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('nop');

require_once 'entities/content.php';

$init = microtime(true);
$date = date('Y-m-d H:i:s');
$contents_count = content::count();

DBConn::get()->beginTransaction();

require_once 'cron/fetchcontentsurls.php';
require_once 'cron/fetchcontentsdata.php';

DBConn::get()->commit();

$time = microtime(true) - $init;
$contents_count = content::count() - $contents_count;

file_put_contents('log.txt',
					$date . "\n" .
					'Time secs.: ' . $time . "\n" . 
					'Time mins.: ' . ($time / 60) . "\n" .
					'Contents: ' . $contents_count . "\n",
					FILE_APPEND | LOCK_EX);
?>
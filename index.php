<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL | E_STRICT);
ini_set('include_path', '.');
ini_set('mbstring.detect_order', 'auto');
ini_set('mbstring.language', 'neutral');
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.http_input', 'UTF-8');
ini_set('mbstring.http_output', 'pass');
ini_set('mbstring.script_encoding', 'UTF-8');

set_error_handler(
	function($errno, $errstr, $errfile, $errline)
	{
		if ($errno & error_reporting())
		{
    		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		}
	});

set_exception_handler(
	function($ex)
	{
		if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1')
		{
			require_once 'libs/libmail.php';
			$m = new Mail();
			$m->From('error@hackermash.com');
			$m->To('***');
			$m->Subject('Error HackerMash');	
			$m->Body((string)$ex);
			$m->Send();
			die('Error');
		}
		echo '<pre>' . $ex;
		exit;
	});

/*
	<VirtualHost *:80>
		DocumentRoot "/var/www/hackermash"
		ServerName hackermash.com
		php_flag mbstring.encoding_translation on
		php_admin_value mbstring.func_overload 6
		RewriteEngine On
		RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
		RewriteRule . /index.php [L]
	</VirtualHost>
*/

require_once 'conf.php';
require_once 'entities/category.php';

$rules = array(
		'#^/$#'                              => 'pages/contents.php',
		'#^/(?<page>\d+)$#'                  => 'pages/contents.php',
		'#^/fetchcontents$#'                 => 'cron/fetchcontents.php',
		'#^/fetchcontentsurls$#'             => 'cron/fetchcontentsurls.php',
		'#^/fetchcontentsdata$#'             => 'cron/fetchcontentsdata.php',
		'#^/fetchcomments$#'                 => 'cron/fetchcomments.php',
		'#^/allcontents$#'                   => 'pages/allcontents.php',
		'#^/tag/(?<tag>.+)/(?<page>\d+)$#'   => 'pages/contents.php',
		'#^/tag/(?<tag>.+)$#'                => 'pages/contents.php',
	);

$categories = category::listBy(array(), 'position');
foreach ($categories as $category)
{
	$rules['#^/(?<category>' . $category->id . ')/(?<page>\d+)$#'] = 'pages/contents.php';
	$rules['#^/(?<category>' . $category->id . ')$#']              = 'pages/contents.php';
	$rules['#^/(?<category>' . $category->id . ')/(?<slug>.*)$#']  = 'pages/contentdetail.php';
}

$rulesCaptures = array();
foreach ($rules as $rule => $page)
{
	if (preg_match($rule, $_SERVER['SCRIPT_URL'], $matches))
	{
		foreach ($matches as $key => $val)
		{
			if (is_string($key))
			{
				$rulesCaptures[$key] = $val;
			}
		}
		require_once $page;
		exit;
	}
}

header('HTTP/1.1 404 Not Found');
require_once 'pages/notfound.php';
?>
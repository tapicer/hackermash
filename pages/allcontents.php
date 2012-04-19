<?php
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('nop');

require_once 'entities/content.php';
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>

<h1>Ok</h1>

<table border="1" cellpadding="3" cellspacing="0">
	<thead>
		<tr>
			<th>Url</th>
			<th>Title</th>
			<th>Description</th>
			<th>Slug</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$contents = content::listBy(array('fetched' => true, 'ignored' => false));
		foreach ($contents as $content)
		{
			echo '<tr>';
			echo "<td><a href=\"$content->url\">$content->url</a></td>";
			echo "<td>$content->title</td>";
			echo "<td>$content->description</td>";
			echo "<td>$content->slug</td>";
			echo '</tr>';
		}
		?>
	</tbody>
</table>

<h1>Ignored</h1>

<table border="1" cellpadding="3" cellspacing="0">
	<thead>
		<tr>
			<th>Url</th>
			<th>Title</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$contents = content::listBy(array('fetched' => true, 'ignored' => true));
		foreach ($contents as $content)
		{
			echo '<tr>';
			echo "<td><a href=\"$content->url\">$content->url</a></td>";
			echo "<td>$content->title</td>";
			echo "<td>$content->description</td>";
			echo '</tr>';
		}
		?>
	</tbody>
</table>
</body>
</html>
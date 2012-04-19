<?php
function slugify($str, $sep = '-')
{
	$str = trim($str);
	$str = str_replace(
					array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'),
					array('a', 'e', 'i', 'o', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'n'),
					$str);
	$str = strtolower($str);
	$str = preg_replace('/[^a-z0-9 ]/', '', $str);
	$str = preg_replace('/\s+/', ' ', $str);
	$str = str_replace(' ', $sep, $str);
	return $str;
}

function format_duration($seconds)
{
    $periods = array
			    (
			        'year' => 31556926,
			        'month' => 2629743,
			        'week' => 604800,
			        'day' => 86400,
			        'hour' => 3600,
			        'minute' => 60,
			        //'second' => 1
			    );

    $durations = array();

    foreach ($periods as $period => $seconds_in_period)
    {
        if ($seconds >= $seconds_in_period)
        {
        	$dur = floor($seconds / $seconds_in_period);
            $durations[] = $dur . ' ' . $period . ($dur > 1 ? 's' : '');
            $seconds -= $dur * $seconds_in_period;
        }
    }
    
    if (empty($durations))
    {
    	$durations[] = '0 seconds';
    }
    
    return implode(', ', $durations);
}

function clean_url($url)
{
	$parts = parse_url($url);
	
	if (isset($parts['query']))
	{
		parse_str(urldecode($parts['query']), $qs);
		foreach ($qs as $k => $v)
		{
			if (strpos($k, 'utm_') === 0)
			{
				unset($qs[$k]);
			}
		}
		$parts['query'] = http_build_query($qs);
	}
	
	if (!isset($parts['path']))
	{
		$parts['path'] = '/';
	}
	
	return http_build_url($parts);
}
?>
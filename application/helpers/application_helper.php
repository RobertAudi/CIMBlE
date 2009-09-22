<?php

/**
 * Checks if the string is actually an int
 * Usefull when retrieving an id or a page number from the URI
 *
 * @param string $string 
 * @return bool
 **/
function is_valid_number($string)
{
	return preg_match('/^[0-9]+$/',$string) === 1;
}

/**
 * Converts an array to breadcrumbs
 * The array must contain one array per level.
 * Each of level Array must contain exactly two elements:
 * - first element: key: 'title', value: [The title]. ie: 'title' => 'Posts'
 * - second element: key: 'url', value: [path]. ie: 'url' => 'admin/posts/index'
 *
 * @param array $array 
 * @param string $seperator
 * @param int $length
 * @return string
 **/
function to_breadcrumb($array, $seperator = '&rarr;', $length = 20)
{
	$breadcrumbs = '';
	$i = 0;
	foreach ($array as $level) {
		// Don't display a seperator before the first breadcrumb element
		$temp_seperator = ($i == 0) ? '' : $seperator;
		$breadcrumbs .=  ' ' . $temp_seperator . ' <a href="' . site_url($level['url']) . '" title="' . $level['title'] . '">' . truncate_string($level['title'],$length) . '</a>';
		$i++;
	}
	return $breadcrumbs;
}

/**
 * Truncate a string and append "..." to it if it is too long
 *
 * @param string $string
 * @param int $length 
 * @return string
 **/
function truncate_string($string = "",$length = 0) {
	$length = $length - 3;
    if(strlen($string) > $length) {
        return (substr($string,0,$length) . "..."); 
    } 
    else {
        return $string;
    }
}
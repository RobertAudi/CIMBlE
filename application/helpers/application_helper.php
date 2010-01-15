<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	TODO - Create a Validation Library with all the is_valid_ functions
*/

/**
 * Formats the date passed as argument
 *
 * @param string $date
 * @return string
 */
function format_date($date, $format = 'long')
{
	switch ($format) {
		case 'long':
			return date('D jS M Y - H:i', strtotime($date));
		case 'short':
			return date('d/m/y - H:i', strtotime($date));
		case 'short_us':
			return date('m/d/y - H:i', strtotime($date));
		default:
			return date('D jS M Y - H:i', strtotime($date));
	}
	
} // End of format_date

// ------------------------------------------------------------------------

/**
 * Checks if the value is actually an int. The value can be either
 * an int or a string. Only positive integers are considered valid
 * Usefull when retrieving an id or a page number from the URI
 *
 * @param string|int $value
 * @return bool
 **/
function is_valid_number($value)
{
	return (bool)preg_match('/^[1-9]+[0-9]*$/',$value);
} // End of is_valid_number

/* End of file application_helper.php */
/* Location: ./application/helpers/application_helper.php */

// ------------------------------------------------------------------------

/**
 * Check if the provided string is a valid slug
 *
 * @param string $slug
 * @return bool
 **/
function is_valid_slug($slug)
{
	return (bool)preg_match('/^[0-9]*[a-z]+[0-9]*(-?[a-z0-9]+)*$/', $slug);
} // End of is_valid_slug

// ------------------------------------------------------------------------

/**
 * Check if the provided string is valid while not being a valid id (int).
 * This method is used to check if an image name is valid
 * and doesn't contain illegal characters or is not an id
 *
 * @param string $string
 * @return bool
 */
function is_valid_string($string)
{
	/*
		TODO Improve that shit
	*/
	return (bool)preg_match('/^[0-9]*[a-z\'!:#_\. -]+[a-z0-9\'!:#_\. -]*$/i', $string);
} // End of id_valid_string

// ------------------------------------------------------------------------

/**
 * Check if a string is a valid "ci url" of 
 * the form "controller/action/parameter1/parameter2/"
 * The trailing slash is optional
 *
 * @param string $ci_url : the "ci url"
 * @return bool
 */
function is_valid_ci_url($ci_url)
{
	return (bool)preg_match('/^(?:[0-9]*[a-z]+(?:\-[a-z0-9]+)?[a-z0-9-]*[a-z0-9]\/?)+(?:[0-9]+\/?)*$/', $ci_url);
} // End of is_valid_ci_url

// ------------------------------------------------------------------------

/**
 * Check if the string provided is a valid breadcrumbs string
 *
 * @param string $string : The breadcrumbs string.
 * @return bool
 */
function is_valid_breadcrumbs_string($string)
{
	if (is_string($string) === FALSE)
	{
		log_message('error','The "is_valid_breadcrumbs_string" function takes a string as parameter. However you passed a ' . gettype($string) . ' instead...');
		return FALSE;
	}
	return (bool)preg_match('/^[a-z0-9_](-?[a-z0-9_]+)*$/', $string);
} // End of is_valid_breadcrumbs_string

// ------------------------------------------------------------------------

/**
 * Checks if an action is valid. Here actions are really actions' parameters
 * This method is mainly used with the confirm action present in the admin controllers
 *
 * @todo do something...
 * @param string $action 
 * @return bool
 **/
function is_valid_action($action)
{
	$valid_actions = array(
		'delete',
		'publish',
		'unpublish'
	);
	if (in_array($action, $valid_actions))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
} // End of is_valid_action

// ------------------------------------------------------------------------

/**
 * Make a string "URI-safe". This function is a replacement for CodeIgniter's url_title
 *
 * @param string $string
 * @return string
 */
function uri_safe($string)
{
	if (!is_string($string))
	{
		log_message('error','application_helper.php:103 : The string supplied is not valid (not a string).');
		return FALSE;
	}
	
	$trimmed = strtolower(trim($string, " \t\n\r\0\x0B-_."));
	return preg_replace('/[ _\.-]+/', '-', $trimmed);
} // End of uri_safe

// ------------------------------------------------------------------------

/**
 * Converts a string into a regular expression pattern
 *
 * @param string $string : the string to convert into a regex
 * @param bool $start : if set to true, the '^' anchor will be inserted at the beginning of the pattern
 * @return string : the regex
 */
function to_regex($string, $start = TRUE)
{
	$regex  = ($start === TRUE) ? '/^' : '/';
	$regex .= preg_replace('/(\/)+/', '\/', $string);
	$regex .= '/';
	return $regex;
} // End of to_regex

// ------------------------------------------------------------------------

/**
 * Truncate a string and append "..." to it if it is too long
 *
 * @param string $string
 * @param int $length 
 * @return string
 **/
function truncate_string($string = "", $length = 0)
{
	$length = $length - 3;
	if (strlen($string) > $length)
	{
		return (substr($string, 0, $length) . "..."); 
	}
	else
	{
		return $string;
	}
} // End of truncate_string

// ------------------------------------------------------------------------

/**
 * Remove the trailing slash of a uri if it is present
 *
 * @param string $string : The URI
 * @return void
 */
function strip_trailing_slash($string)
{
	if ($string[strlen($string) - 1] === '/')
		return substr_replace($string, '', -1, 1);
	return $string;
} // End of strip_trailing_slash

/* End of file application_helper.php */
/* Location: ./application/helpers/application_helper.php */
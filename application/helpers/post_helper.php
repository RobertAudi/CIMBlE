<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formats the date passed as argument
 *
 * @param string $date
 * @return string
 */
function post_date($date)
{
	return date('D jS Y - H:i', strtotime($date));
} // End of post_date

/**
 * Generate an exerpt if there is an hr tag in the post
 * Same effect as the <!--more--> tag in Wordpress
 *
 * @param string $string 
 * @return string
 */
function read_more($string)
{
	$pattern = '/\<hr \/\>/';
	if (preg_match($pattern,$string)) {
		$pieces = explode("<hr />", $string);
		$string = $pieces[0];
		return $string;
	} else {
		return $string;
	}
} // End of read_more

/**
 * Checks if post is active
 * Converts 1 to TRUE and 0 to FALSE
 * All the other values return FALSE
 *
 * @param int $num 
 * @return bool
 */
function is_active($num)
{
	if ($num == 1)
	{
		return TRUE;
	}
	elseif ($num == 0)
	{
		return FALSE;
	}
	else
	{
		return FALSE;
	}
} // End of is_active

/**
 * Translates the active state of a post in english
 *
 * @param int $active 
 * @return string
 **/
function is_active_in_english($active)
{
	if ($active == 1)
	{
		$active_in_english = 'Active';
	} elseif ($active == 0) {
		$active_in_english = 'Inactive';
	}
	
	return $active_in_english;
} // End of is_active_in_english

/**
 * Create a temporary array of posts out of a bigger array
 * so that the correct posts are displayed on each page when
 * using CodeIgniter's pagination class
 *
 * @todo move this method to the application_helper
 * @param array $posts 
 * @param int $number_of_posts 
 * @param int $number_of_posts_per_page 
 * @param string $offset 
 * @return array
 **/
function paginate($posts, $number_of_posts, $number_of_posts_per_page, $offset)
{
	if (!is_valid_number($offset))
	{
		if (empty($offset))
		{
			$first_message_to_display = 0;
		}
		else
		{
			return NULL;
		}
	}
	else
	{
		$first_message_to_display = $offset;
	}
	
	// if posts is empty, array_slice will return an error, so we will just return the unchanged posts variable in that case.
	if(empty($posts) || !isset($posts))
		return $posts;
	
	$page_posts = array_slice($posts, $first_message_to_display, $number_of_posts_per_page);
	
	return $page_posts;
} // End of paginate

/**
 * Gets the page number the user is on
 *
 * @todo move this method to the application_helper
 * @param int $offset
 * @param int $number_of_posts_per_page 
 * @return int
 **/
function get_page_number($offset, $number_of_posts_per_page)
{
	if (!is_valid_number($offset))
	{
		if (empty($offset))
		{
			return 1;
		}
		else
		{
			return NULL;
		}
	}
	else
	{
		return ($offset / $number_of_posts_per_page) + 1;
	}
} // End of get_page_number

/**
 * Display the Edit link to edit each post
 *
 * @param int $post_id 
 * @param string $seperator 
 * @return stirng
 **/
function display_admin_controls($post_id, $seperator = '|')
{
	return ($seperator . ' <a href="' . site_url('admin/posts/edit/' . $post_id) . '">Edit</a>');
} // End of display_admin_controls

/**
 * Repopulates a form in case the form validation fails
 * $original is the value that the field initially had. ie: $post->title
 * $modified is the value that the field has if it has
 *   been modified before the validation fails. ie: $this->input->post('title)
 *
 * @param string $original 
 * @param string $modified 
 * @return string
 **/
function repopulate($original, $modified = NULL)
{
	if (isset($modified) && !empty($modified))
	{
		return $modified;
	}
	else
	{
		return $original;
	}
} // End of repopulate

/* End of file post_helper.php */
/* Location: ./application/helpers/post_helper.php */
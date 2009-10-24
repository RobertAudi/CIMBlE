<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seperate the comments by status (Ham and Spam)
 *
 * @param array $comments
 * @return array
 **/
function categorize($comments)
{
	$ham = array();
	$spam = array();
	
	foreach ($comments as $comment) {
		if ($comment->is_spam == 1)
		{
			array_push($spam, $comment);
		}
		elseif ($comment->spam == 0)
		{
			array_push($hpam, $comment);
		}
	}
	
	return array('ham' => $ham, 'spam' => $spam);
} // End of categorize

/* End of file comment_helper.php */
/* Location: ./application/helpers/comment_helper.php */
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Converts a user level from an int to a user readable "label" (string)
 *
 * @param int $user_level
 * @return string
 **/
function user_level_in_english($user_level)
{
	switch ($user_level) {
		case 1:
			$user_level = 'Administrator';
			break;
		case 100:
			$user_level = 'Editor';
			break;
	}
	return $user_level;
} // End of user_level_in_english

/* End of file user_helper.php */
/* Location: ./application/helpers/user_helper.php */
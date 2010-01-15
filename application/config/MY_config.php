<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// you can comment out this line if you have set a default timezone in your php.ini
date_default_timezone_set('Europe/Paris');

// values for the MY_Controller class variables
$config['class_variables'] = array(
	'login_page'      => 'user/login',
	'main_view'       =>       'main',
	'main_admin_view' => 'admin/main'
);

// a set of valid actions checked by the confirm action in the posts admin controller
$config['valid_actions'] = array(
	'delete',
	'publish',
	'unpublish'
);

// the maximum number of reply "levels". Users won't be
// able to reply to a comment that has the maximum depth
$config['max_comment_depth'] = 3;

/*
|--------------------------------------------------------------------------
| Application Constants
|--------------------------------------------------------------------------
*/

define('BLOG_TITLE', 'Blog');

/* End of file MY_config.php */
/* Location: ./system/application/config/MY_config.php */
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| Azbraz Breadcrumbs Library Config File
| -------------------------------------------------------------------
*/

/*
| The max length of a segment. Longer segment will be sent to
| the truncate_string() function in the applcation_helper.
*/
$config['azbraz_max_segment_length'] = 20;

/*
| The seperator that will appear between each segment
*/
$config['azbraz_seperator'] = '&rarr;';

/*
| -------------------------------------------------------------------
| Segments
| -------------------------------------------------------------------
| I adopted the convention of starting the name of admin segments
| with an underscore '_'.
| So 'galleries' is the galleries public segment and '_galleries'
| is the galleries admin segment.
*/
$config['azbraz_segments'] = array(
	'_dashboard' => array(
		'title' => 'Dashboard',
		'url' => 'admin',
	),
// --Posts-----------------------------------------------------------------
	'_posts' => array(
		'title' => 'Posts',
		'url' => 'admin/posts'
	),
	'_add_post' => array(
		'title' => 'New Post',
		'url' => 'admin/posts/add'
	),
	'_drafts' => array(
		'title' => 'Drafts',
		'url' => 'admin/posts/drafts'
	),
	'blog' => array(
		'title' => 'Blog',
		'url' => 'posts'
	),
// -Comments---------------------------------------------------------------
	'_comments' => array(
		'title' => 'Comments',
		'url' => 'admin/comments'
	),
// --Users-----------------------------------------------------------------
	'_users' => array(
		'title' => 'Users',
		'url' => 'admin/users'
	),
	'_add_user'  => array(
		'title' => 'New User',
		'url' => 'admin/users/add'
	)
);

/*
| -------------------------------------------------------------------
| Default Segment
| -------------------------------------------------------------------
| The default segment must be the key of one of the segments
| specified above.
| It should also correspond to the default controller...
*/
$config['default_segment'] = 'blog';

/* End of file breadcrumbs.php */
/* Location: ./application/config/breadcrumbs.php */
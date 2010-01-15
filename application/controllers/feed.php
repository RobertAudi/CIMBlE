<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends Controller
{
	/**
	 * The Constructor!
	 **/
	public function __construct()
	{
		parent::Controller();
		
		$this->load->model('post_model');
		$this->load->helper('xml');
	}
	
// ------------------------------------------------------------------------
	
	public function index()
	{
		$data['encoding'        ] = 'utf-8';
		$data['feed_name'       ] = 'example.com';
		$data['feed_url'        ] = 'http://www.example.com/';
		$data['page_description'] = 'CIMBlE RSS feed test';
		$data['page_language'   ] = 'en-us';
		$data['creator_email'   ] = 'admin at admin dot com';
		
		$posts = $this->post_model->get_posts(10);
		$data['posts'] = $posts['list'];
				
		header("Content-Type: application/rss+xml");
		$this->load->view('feed/rss', $data);
	} // End of index
	
} // End of Feed controller

/* End of file feed.php */
/* Location: ./application/controllers/feed.php */
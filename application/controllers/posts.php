<?php

class Posts extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model('post_model');
	}
	
	/**
	 * View all the posts
	 */
	function index()
	{
		$data['view_file'] = 'posts/index';
		
		$posts = $this->post_model->get_posts();
		$posts = $posts['list'];
		
		/* ---------- */
		/* Pagination */
		/* ---------- */
		
		// config for the pagination of the content (posts)
		$data['posts_count'] = count($posts);
		$data['posts_per_page'] = 3;
		$data['offset'] = $this->uri->segment(3);
		
		// If the offset is invalid or NULL (in which case the user goes back to the first page anyway)
		// the user is sent back to the first page and a feedback message is displayed
		if ((!is_valid_number($data['offset']) || !array_key_exists($data['offset'],$posts)) && !empty($data['offset']))
		{	
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('posts/index/0');
		}
		
		$this->load->library('pagination');
		
		// config for the pagination links
		$config['base_url'] = site_url('/posts/index');
		$config['total_rows'] = $data['posts_count'];
		$config['per_page'] = $data['posts_per_page'];
		$config['num_links'] = 4;
		
		// initialize pagination with the configuration array above
		$this->pagination->initialize($config);
		
		// Create pagination links and store them in the data array
		$data['pagination_links'] = $this->pagination->create_links();
		
		// Dynamically generate the posts pagination everytime the user clicks on a pagination link
		$data['posts'] = paginate($posts, $data['posts_count'], $data['posts_per_page'], $data['offset']);
		
		// Generate the dynamic breadcrumbs
		$data['section_name'] = array(
									array(
										'title' => 'Blog',
										'url' => 'posts/index'
									),
									array(
										'title' => 'page ' . get_page_number($data['offset'],$data['posts_per_page']),
										'url' => 'posts/index/' . $data['offset']
									)
								);
		
		// Load the layout and pass to it the data array
		$this->load->view('layout', $data);
	}
	
	/**
	 * View a single post
	 */
	function view($post_id)
	{
		$post = $this->post_model->get_post($post_id);
		$data['post'] = $post;
		
		if ($data['post'] === NULL)
		{
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect(site_url());
		}
		
		$data['view_file'] = 'posts/view';
		$data['section_name'] = array(
									array(
										'title' => 'Blog',
										'url' => 'posts/index'
									),
									array(
										'title' => $post->title,
										'url' => 'posts/view/' . $post_id
									)
								);
		
		$this->load->view('layout', $data);
	}
}
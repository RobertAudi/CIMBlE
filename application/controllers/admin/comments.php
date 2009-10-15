<?php

class Comments extends Controller
{
	// constructor
	public function __construct()
	{
		parent::Controller();
		
		// load the comment model
		$this->load->model('comment_model');
		
		// load the post_helper so that the pagination works fine -- temporary
		$this->load->helper('post_helper');
	}
	
	public function index()
	{
		// get all the comments from the database
		$comments = $this->comment_model->get_comments();
		
		// if there are no comments, we load a different view so that we don't get an error
		if ($comments['count'] == 0)
		{
			$data['comments']  = 'No comments have been posted yet...';
			$data['view_file'] = 'comments/no-comments';
			$this->load->view('admin/admin', $data);
		}
		else
		{
			$data['view_file'] = 'admin/comments/index';
			
			// isolate the comments count and the comments list in seperate variables
			$data['comments_count'] = $comments['count'];
			$data['comments'] = $comments['list'];
			
			/* -------------- */
			/* - Pagination - */
			/* -------------- */
			
			/*
				TODO - Fix the fucking pagination!
			*/
			
			// configuration of the comments pagination
			$data['comments_per_page'] = 10;
			$data['offset'] = $this->uri->segment(4);
			
			// If the offset is invalid or NULL (in which case the user goes back to the first page anyway)
			// the user is sent back to the first page and a feedback message is displayed
			if ((!is_valid_number($data['offset']) || !array_key_exists($data['offset'],$data['comments'])) && !empty($data['offset']))
			{
				$this->session->set_flashdata('notice','Invalid Request');
				redirect('admin/comments/index/0');
			}
			
			// load the pagination library
			$this->load->library('pagination');
			
			// configuration of the pagination links
			$config['base_url']       = site_url('/admin/comments/index');
			$config['total_rows']     = $data['comments_count'];
			$config['per_page']       = $data['comments_per_page'];
			$config['num_links']      = 10;
			$config['uri_segment']    = 4;
			$config['full_tag_open']  = '<div class="pagination_links"';
			$config['full_tag_close'] = '</div>';
			
			// initialize pagination with the configuration array above
			$this->pagination->initialize($config);
			
			// create pagination links and store them in an array
			$data['pagination_links'] = $this->pagination->create_links();
			
			// dynamically generate the posts pagination everytime the user clicks on a pagination link
			$data['comments'] = paginate($comments['list'], $data['comments_count'], $data['comments_per_page'], $data['offset']);
			
			// Generate the dynamic breadcrumbs
			$data['section_name'] = array(
										array(
											'title' => 'Dashboard',
											'url' => 'admin'
										),
										array(
											'title' => 'Comments',
											'url' => 'admin/comments/index'
										)
									);
			
			// the page number segment of the breadcrumbs will only appear if there is at least two pages
			if($comments['count'] > $config['per_page'])
			{
				array_push($data['section_name'], 	array(
														'title' => 'page' . get_page_number($data['offset'], $data['comments_per_page']),
														'url' => 'admin/comments/index/' . $data['offset']
													)
				);
			}
			
			$this->load->view('admin/admin', $data);
		}
	} // End of index

} // End of Comments controller

/* End of file comments.php */
/* Location: ./application/controllers/admin/comments.php */
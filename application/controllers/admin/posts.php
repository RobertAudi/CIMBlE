<?php

class Posts extends Controller
{
	// constructor
	public function __construct()
	{
		parent::Controller();
		
		// load the post model
		$this->load->model('post_model');
		
		// load necessary helpers
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('post_helper');
	}
	
	public function index()
	{
		// the parameter 'all' is used to also display inactive posts in the posts section of the dashboard
		$posts = $this->post_model->get_posts('all');
		
		// if there are no posts we don't want to load the regular posts view file or we'll get an error
		$data['view_file'] = ($posts['count'] > 0) ? 'admin/posts/index' : 'posts/no-posts';
		
		// seperate the postslist...
		$data['posts'] = $posts['list'];
		// ...and the posts count. Take a look at the get_posts method in the post_model if you're confused.
		$data['posts_count'] = $posts['count'];
		
		/* -------------- */
		/* - Pagination - */
		/* -------------- */
		
		// config for the pagination of the content (posts)
		$data['posts_per_page'] = 10;
		$data['offset'] = $this->uri->segment(4);
		
		// If the offset is invalid or NULL (in which case the user goes back to the first page anyway)
		// the user is sent back to the first page and a feedback message is displayed
		if ((!is_valid_number($data['offset']) || !array_key_exists($data['offset'],$data['posts'])) && !empty($data['offset']))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts/index/0');
		}
		
		// load the pagination library
		$this->load->library('pagination');
		
		// config for the pagination links
		$config['base_url']       = site_url('/admin/posts/index');
		$config['total_rows']     = $data['posts_count'];
		$config['per_page']       = $data['posts_per_page'];
		$config['num_links']      = 10;
		$config['uri_segment']    = 4;
		$config['full_tag_open']  = '<div class="pagination-links">';
		$config['full_tag_close'] = '</div>';
		
		// initialize pagination with the configuration array above
		$this->pagination->initialize($config);
		
		// Create pagination links and store them in the data array
		$data['pagination_links'] = $this->pagination->create_links();
		
		// Dynamically generate the posts pagination everytime the user clicks on a pagination link
		$data['posts'] = paginate($posts['list'], $data['posts_count'], $data['posts_per_page'], $data['offset']);
		
		// Generate the dynamic breadcrumbs
		$data['section_name'] = 	array(
										array(
											'title' => 'Dashboard',
											'url' => 'admin'
										),
										array(
											'title' => 'Posts',
											'url' => 'admin/posts/index'
										)
									);
		
		// the page number segment of the breadcrumbs will only appear if there is at least two pages
		if ($posts['count'] > $config['per_page'])
		{
			array_push($data['section_name'],	array(
													'title' => 'page ' . get_page_number($data['offset'],$data['posts_per_page']),
													'url' => 'admin/posts/index/' . $data['offset']
												)
			);
		}
		
		$this->load->view('admin/admin', $data);
	} // End of index
	
	public function add()
	{
		if ($this->form_validation->run('admin/posts/add') == FALSE)
		{
			$view_data['view_file'] = 'admin/posts/add';
			$view_data['section_name'] = 	array(
												array(
													'title' => 'Dashboard',
													'url' => 'admin'
												),
												array(
													'title' => 'Posts',
													'url' => 'admin/posts/index'
												),
												array(
													'title' => 'New Post',
													'url' => 'admin/posts/add'
												)
											);
		
			$this->load->view('admin/admin',$view_data);
		}
		// if there is either a post title or a post body, insert a new post in the database
		else
		{
			// retrieve the user info, more specifically, the user id - see $data[user_id]
			$user = $this->azauth->get_user('user_id');
			
			// save the post attributes in a temporary array
			$data['title'] = $this->input->post('title');
			$data['body'] = $this->input->post('body');
			$data['user_id'] = $user['user_id'];
			$data['active'] = $this->input->post('active') == 'active' ? 1 : 0;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			
			// Create the new post
			$this->post_model->new_post($data);
			
			// redirect the user to the Posts section in the Dashboard
			redirect('admin/posts/index');
		}
	} // End of add
	
	public function edit($post_id)
	{
		// Check if the post id is valid
		if (!is_valid_number($post_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts/index');
		}
		
		if ($this->form_validation->run('admin/posts/edit') == FALSE)
		{
			$view_data['view_file'] = 'admin/posts/edit';
			$view_data['section_name'] =	array(
												array(
													'title' => 'Dashboard',
													'url' => 'admin'
												),
												array(
													'title' => 'Posts',
													'url' => 'admin/posts/index'
												),
												array(
													'title' => 'Edit Post',
													'url' => 'admin/posts/edit/' . $post_id
												)
											);
			
			$view_data['post'] = $this->post_model->get_post($post_id, 0);
			
			if ($view_data['post'] === NULL)
			{
				$this->session->set_flashdata('notice','Invalid Request!');
				redirect('admin/posts/index');
			}
			
			$this->load->view('admin/admin',$view_data);
		}
		
		// if the user clicked on the Delete button, delete the post...
		elseif ($this->input->post('delete'))
		{
			redirect('admin/posts/confirm/' . $post_id);
		}
		
		// ...else, if he clicked on the Save Button, Update the post
		elseif ($this->input->post('update'))
		{
			// save the post attributes in a temporary array
			$data['title'] = $this->input->post('title');
			$data['body'] = $this->input->post('body');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$data['active'] = $this->input->post('active') == 'active' ? 1 : 0;
		
			// Update the post
			$this->post_model->update_post($post_id,$data);
		
			// redirect the user to the Posts section in the Dashboard
			redirect('admin/posts/index');
		}
		else
		{
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/posts/index');
		}
	} // End of edit
	
	public function delete($post_id)
	{
		// Check if the post id is valid
		if (!is_valid_number($post_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts/index');
		}
		$this->post_model->delete_post($post_id);
		$this->session->set_flashdata('notice','Post deleted successfully!');
		redirect('admin/posts/index');
	} // End of delete
	
	public function confirm($post_id)
	{
		// Check if the post id is valid
		if (!is_valid_number($post_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts/index');
		}
		
		$data['view_file'] = 'admin/posts/confirm';
		$data['question'] = 'Are you sure you want to delete the following post?';
		$data['post'] = $this->post_model->get_post($post_id,0);
		
		$this->load->view('admin/admin', $data);
	} // End of confirm
	
	public function view($post_id)
	{
		$data['post'] = $this->post_model->get_post($post_id, 0);
		$data['post_id'] = $post_id;
		
		// if the post is inactive, let the user know
		if ($data['post']->active == 0)
			$data['notice'] = 'This is post is inactive! regular users can\'t view it.';
		
		// if the model did not retrieve any post, inform the user and redirect him to the main page
		if ($data['post'] === NULL)
		{
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/posts');
		}
		
		// load the comment model
		$this->load->model('comment_model');
		
		// get comments
		$comments = $this->comment_model->get_comments($post_id);
		
		// if there are no comments (yet)
		if (empty($comments))
		{
			// display a message instead of the comments list
			$data['comments_view_file'] = 'admin/comments/no-comments';
			$data['comments'] = 'No comments have been posted yet';
		}
		else
		{
			$data['comments_view_file'] = 'admin/comments/view';
			$data['comments'] = $comments['list'];
		}
		
		// pass the posts view file path to the view
		$data['view_file'] = 'admin/posts/view';
		
		// used for the breadcrumbs, I don't like the syntax $data['post']->title, it's ugly and confusing
		$post = $data['post'];
		
		// and set up the breadcrumbs
		$data['section_name'] = array(
									array(
										'title' => 'Dashboard',
										'url' => 'admin/index'
									),
									array(
										'title' => 'Posts',
										'url' => 'admin/posts/index'
									),
									array(
										'title' => $post->title,
										'url' => 'admin/posts/view/' . $post_id
									)
								);
		
		$this->load->view('admin/admin', $data);
	} // End of view
	
} // End of Posts controller

/* End of file posts.php */
/* Location: ./application/controllers/admin/posts.php */
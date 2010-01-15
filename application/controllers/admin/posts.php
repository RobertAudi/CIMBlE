<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends MY_Controller
{
	/**
	 * The Constructor!
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load the post model
		$this->load->model('post_model');
		
		// load necessary helpers
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('post_helper');
	}
	
// ------------------------------------------------------------------------
	
	/**
	 * The default landing page. Show a list of all the posts, including the inactive ones.
	 */
	public function index()
	{
		if ($this->session->userdata('confirmation'))
			$this->session->unset_userdata('confirmation');
		
		// the parameter 'all' is used to also display inactive posts in the posts section of the dashboard
		$posts = $this->post_model->get_posts('all');
		
		if ($posts['count'] == 0)
		{
			// if there are no posts we don't want to load the regular posts view file or we'll get an error
			$data['view_file'] = 'posts/no-posts';
		}
		else
		{
			$data['posts'      ] = $posts['list' ];
			$data['posts_count'] = $posts['count'];
			
			// ------------------------------------------------------------------------
			// Pagination
			// ------------------------------------------------------------------------
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
			
			$this->load->library('pagination');
			
			$config['base_url'      ] = site_url('/admin/posts/index');
			$config['total_rows'    ] = $posts['count'];
			$config['per_page'      ] = $data['posts_per_page'];
			$config['num_links'     ] = 10;
			$config['uri_segment'   ] = 4;
			$config['full_tag_open' ] = '<div class="pagination-links">';
			$config['full_tag_close'] = '</div>';
			
			$this->pagination->initialize($config);
			
			$data['pagination_links'] = $this->pagination->create_links();
			
			// Dynamically generate the posts pagination everytime the user clicks on a pagination link
			$data['posts'] = paginate($posts['list'], $data['posts_count'], $data['posts_per_page'], $data['offset']);
			// ------------------------------------------------------------------------
			
			// ------------------------------------------------------------------------
			// Breadcrumbs
			// ------------------------------------------------------------------------
			
			// the page number segment of the breadcrumbs will only appear if there is at least two pages
			if ($posts['count'] > $config['per_page'])
			{
				$_seg_title  = 'page ' . get_page_number($data['offset'],$data['posts_per_page']);
				$_seg_url    = 'admin/posts/index/' . $data['offset'];
				$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
			}
			else
			{
				$breadcrumbs = '';
			}
			$data['breadcrumbs'] = $this->azbraz->generate($breadcrumbs);
			// ------------------------------------------------------------------------
			
			$data['view_file'] = 'admin/posts/index';
		}
		
		$this->load->view($this->main_admin_view, $data);
	} // End of index
	
// ------------------------------------------------------------------------
	
	/**
	 * Drafts - All the inactive posts will here, for now at least
	 */
	public function drafts()
	{
		$drafts = $this->post_model->get_posts('drafts');
		
		if ($drafts['count'] == 0)
		{
			$this->session->set_flashdata('notice','You don\'t have any drafts...\n<a href="' . site_url('admin/posts/add') . '">Create a new post</a>');
			redirect('admin/posts');
		}
		else
		{
			$data['posts'      ] = $drafts['list' ];
			$data['posts_count'] = $drafts['count'];
			
			
			// ------------------------------------------------------------------------
			// FIXME - CODING HORROR!!!! NOT DRY AT ALL!
			// ------------------------------------------------------------------------
			
			// ------------------------------------------------------------------------
			// Pagination
			// ------------------------------------------------------------------------
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
			
			$this->load->library('pagination');
			
			$config['base_url'      ] = site_url('/admin/posts/drafts');
			$config['total_rows'    ] = $drafts['count'];
			$config['per_page'      ] = $data['posts_per_page'];
			$config['num_links'     ] = 10;
			$config['uri_segment'   ] = 4;
			$config['full_tag_open' ] = '<div class="pagination-links">';
			$config['full_tag_close'] = '</div>';
			
			$this->pagination->initialize($config);
			
			$data['pagination_links'] = $this->pagination->create_links();
			
			// Dynamically generate the posts pagination everytime the user clicks on a pagination link
			$data['posts'] = paginate($drafts['list'], $data['posts_count'], $data['posts_per_page'], $data['offset']);
			// ------------------------------------------------------------------------
			
			// ------------------------------------------------------------------------
			// Breadcrumbs
			// ------------------------------------------------------------------------
			
			$data['breadcrumbs'] = $this->azbraz->generate();
			
			// ------------------------------------------------------------------------
			
			$data['view_file'] = 'admin/posts/index';
			$this->load->view($this->main_admin_view, $data);
		}
	} // End of drafts
	
// ------------------------------------------------------------------------
	
	/**
	 * Add a new blog post
	 */
	public function add()
	{
		if ($this->form_validation->run('admin/posts/add') == FALSE)
		{
			$view_data['view_file'] = 'admin/posts/add';
			$view_data['breadcrumbs'] = $this->azbraz->generate();
			
			$this->load->view($this->main_admin_view, $view_data);
		}
		else
		{
			$user = $this->azauth->get_user('user_id');
			
			$data['title'     ] = $this->input->post('title');
			$data['body'      ] = $this->input->post('body');
			$data['user_id'   ] = $user['user_id'];
			$data['active'    ] = $this->input->post('active') == 'active' ? 1 : 0;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			
			$this->post_model->new_post($data);
			redirect('admin/posts');
		}
	} // End of add
	
// ------------------------------------------------------------------------
	
	/**
	 * Edit a blog post
	 *
	 * @param int $post_id
	 */
	public function edit($post_id)
	{
		if (!is_valid_number($post_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts');
		}
		
		if ($this->form_validation->run('admin/posts/edit') == FALSE)
		{
			// ------------------------------------------------------------------------
			// Breadcrumbs
			// ------------------------------------------------------------------------
			$_seg_title  = 'Editing Post#' . $post_id;
			$_seg_url    = 'admin/posts/edit/' . $post_id;
			$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
			
			$view_data['breadcrumbs'] = $this->azbraz->generate($breadcrumbs);
			// ------------------------------------------------------------------------
			
			$view_data['post'] = $this->post_model->get_post($post_id, 'all');
			if ($view_data['post'] === NULL)
			{
				$this->session->set_flashdata('notice','Invalid Request!');
				redirect('admin/posts');
			}
			
			$view_data['view_file'] = 'admin/posts/edit';
			$this->load->view($this->main_admin_view, $view_data);
		}
		elseif ($this->input->post('delete'))
		{
			redirect('admin/posts/confirm/delete/' . $post_id);
		}
		elseif ($this->input->post('update'))
		{
			$data['title'     ] = $this->input->post('title');
			$data['body'      ] = $this->input->post('body');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$data['active'    ] = $this->input->post('active') == 'active' ? 1 : 0;
			
			$this->post_model->update_post($post_id,$data);
			redirect('admin/posts');
		}
		else
		{
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/posts');
		}
	} // End of edit
	
// ------------------------------------------------------------------------
	
	/**
	 * Delete a blog post
	 *
	 * @param int $post_id
	 */
	public function delete($post_id)
	{
		if ((bool)$this->session->userdata('confirmation') !== TRUE)
		{
			log_message('error','Posts Admin Controller::delete() : You can\'t access the delete action directly, you first need to pass by the confirmation action!');
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/posts');
		}
		
		if (!is_valid_number($post_id))
		{
			log_message('error','Posts Admin Controller::delete() : The post id you passed is not a valid number!');
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts');
		}
		$this->post_model->delete_post($post_id);
		$this->session->set_flashdata('notice','Post deleted successfully!');
		$this->session->unset_userdata('confirmation');
		redirect('admin/posts');
	} // End of delete
	
// ------------------------------------------------------------------------
	
	/**
	 * Confirm a critical change. The can be the deletion of a post for instance.
	 *
	 * @param string $action : the "critical change"
	 * @param int $post_id
	 */
	public function confirm($action = NULL, $post_id = NULL)
	{
		// Check if the post id is valid
		if (empty($action) || empty($post_id) || !is_valid_number($post_id) || !is_valid_action($action) || $this->post_model->get_post($post_id, 'all') === NULL)
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/posts');
		}
		
		$data['question'] = 'Are you sure you want to ' . $action . ' the following post?';
		$data['post']     = $this->post_model->get_post($post_id, 'all');
		$data['action']   = $action;
		
		$this->session->set_userdata(array('confirmation' => TRUE));
		
		// ------------------------------------------------------------------------
		// Breadcrumbs
		// ------------------------------------------------------------------------
		$_seg_title  = ucfirst($action) . ' Post#' . $post_id . ': Confirmation';
		$_seg_url    = 'admin/posts/confirm/' . $action . '/' . $post_id;
		$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
		
		$data['breadcrumbs'] = $this->azbraz->generate($breadcrumbs);
		// ------------------------------------------------------------------------
		
		$data['view_file'] = 'admin/posts/confirm';
		$this->load->view($this->main_admin_view, $data);
	} // End of confirm
	
// ------------------------------------------------------------------------
	
	/**
	 * View a blog post
	 *
	 * @param int $post_id
	 */
	public function view($post_id)
	{
		$data['post'] = $this->post_model->get_post($post_id, 'all');
		if ($data['post'] === NULL)
		{
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/posts');
		}
		
		if ($data['post']->active == 0)
			$data['notice'] = 'This is post is inactive! regular users can\'t view it.';
		
		// ------------------------------------------------------------------------
		// Comments
		// ------------------------------------------------------------------------
		$this->load->model('comment_model');
		$comments = $this->comment_model->get_comments($data['post']->id);
		
		$this->firephp->fb($comments); // DEBUG <-
		
		if (empty($comments))
		{
			$data['comments_view_file'] = 'admin/comments/no-comments';
			$data['comments'] = 'No comments have been posted yet';
		}
		else
		{
			$data['comments_view_file'] = 'admin/comments/view';
			$data['comments'] = $comments['list'];
		}
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		// Breadcrumbs
		// ------------------------------------------------------------------------
		$_seg_title  = $data['post']->title;
		$_seg_url    = 'admin/posts/view/' . $data['post']->id;
		$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
		
		$data['breadcrumbs'] = $this->azbraz->generate($breadcrumbs);
		// ------------------------------------------------------------------------
		
		$data['view_file'] = 'admin/posts/view';
		$this->load->view($this->main_admin_view, $data);
	} // End of view
	
// ------------------------------------------------------------------------
	
	/**
	 * Publish a post
	 *
	 * @access public
	 * @param int $post_id
	 * @see _toggle_status()
	 */
	public function publish($post_id)
	{
		$this->_toggle_status($post_id, 'inactive');
	} // End of publish
	
	// ------------------------------------------------------------------------
	
	/**
	 * Unpublish a post
	 *
	 * @access public
	 * @param int $post_id
	 * @see _toggle_status()
	 */
	public function unpublish($post_id)
	{
		$this->_toggle_status($post_id, 'active');
	} // End of unpublish
	
	// ------------------------------------------------------------------------
	
	/**
	 * Change the status of the post from Inactive to Active and vice versa.
	 *
	 * @todo - right now the user is first sent to the confirmation page. I need to add some javascript so that he gets an alert instead of being redirected! The redirect should still be available if the user disabled javascript.
	 * @param int $post_id
	 * @access private
	 */
	private function _toggle_status($post_id, $status)
	{
		if ((bool)$this->session->userdata('confirmation') !== TRUE)
		{
			log_message('error','You can\'t access the _toggle_status action directly, you first need to pass by the confirmation action!');
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/posts');
		}
		
		if ($status != 'active' && $status != 'inactive')
		{
			log_message('error','Posts Admin Controller:330 : You supplied an invalid status to the _toggle_status action!');
			$this->session->set_flashdata('notice','Invalid Request!');
			$this->session->unset_userdata('confirmation');
			redirect('admin/posts');
		}
		
		if ($this->post_model->toggle_status($post_id, $status) === FALSE)
		{
			$this->session->set_flashdata('notice','Invalid Request');
			$this->session->unset_userdata('confirmation');
			redirect('admin/posts');
		}
		else
		{
			if ($status === 'active')
			{
				$this->session->set_flashdata('notice','The post was unpublished successfully and moved to the Drafts section.');
			}
			elseif ($status === 'inactive')
			{
				$this->session->set_flashdata('notice','The post was published successfully!');
			}
			$this->session->unset_userdata('confirmation');
			redirect('admin/posts');
		}
	} // End of publish
	
} // End of Posts controller

/* End of file posts.php */
/* Location: ./application/controllers/admin/posts.php */
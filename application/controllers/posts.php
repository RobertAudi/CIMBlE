<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends MY_Controller
{
	/**
	 * The Constructor!
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('post_model');
		$this->load->helper('markdown_helper');
		$this->load->helper('post_helper');
	}
	
// ------------------------------------------------------------------------
	
	/**
	 * The main page of the blog, show the most recent blog psots
	 */
	public function index()
	{
		if ($this->session->userdata('confirmation'))
			$this->session->unset_userdata('confirmation');
		
		$posts = $this->post_model->get_posts();
		
		if ($posts['count'] ==  0)
		{
			// if there are no posts we don't want to load the regular posts view file or we'll get an error
			$data['view_file'] = 'posts/no-posts';
		}
		else
		{
			$data['posts'] = $posts['list'];
			
			// ------------------------------------------------------------------------
			// Pagination
			// ------------------------------------------------------------------------
			// config for the pagination of the content (posts)
			$data['posts_per_page'] = 3;
			$offset = $this->uri->segment(3);
			$data['offset'] = ((bool)$offset === FALSE) ? '' : $offset;
			
			// If the offset is invalid or NULL (in which case the user goes back to the first page anyway)
			// the user is sent back to the first page and a feedback message is displayed
			if ((!is_valid_number($data['offset']) || !array_key_exists($data['offset'],$posts['list'])) && !empty($data['offset']))
			{
				$this->session->set_flashdata('notice','Invalid Request');
				redirect('posts/index/0');
			}
			
			$this->load->library('pagination');
			
			$config['base_url'      ] = site_url('/posts/index');
			$config['total_rows'    ] = $posts['count'];
			$config['per_page'      ] = $data['posts_per_page'];
			$config['num_links'     ] = 10;
			$config['uri_segment'   ] = 3;
			$config['full_tag_open' ] = '<div class="pagination-links">';
			$config['full_tag_close'] = '</div>';
			
			$this->pagination->initialize($config);
			
			$data['pagination_links'] = $this->pagination->create_links();
			
			// Dynamically generate the posts pagination everytime the user clicks on a pagination link
			$data['posts'] = paginate($posts['list'], $posts['count'], $data['posts_per_page'], $data['offset']);
			// ------------------------------------------------------------------------
			
			// ------------------------------------------------------------------------
			// Breadcrumbs
			// ------------------------------------------------------------------------
			
			// the page number segment of the breadcrumbs will only appear if there is at least two pages
			if ($posts['count'] > $config['per_page'])
			{
				$_seg_title  = 'page ' . get_page_number($data['offset'],$data['posts_per_page']);
				$_seg_url    = 'posts/index/' . $data['offset'];
				$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
			}
			else
			{
				$breadcrumbs = '';
			}
			
			$data['breadcrumbs'] = $this->azbraz->generate($breadcrumbs);
			// ------------------------------------------------------------------------
			
			$data['view_file'] = 'posts/index';
		}
		
		$this->load->view($this->main_view, $data);
		
	} // End of index
	
// ------------------------------------------------------------------------
	
	/**
	 * View a blog post
	 *
	 * @param int $post_id
	 */
	public function view($post_id, $action = '', $comment_id = 0)
	{
		$data['post'   ] = $this->post_model->get_post($post_id);
		$data['post_id'] = $post_id;
		
		if ($data['post'] === NULL)
		{
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('posts/index');
		}
		
		// ------------------------------------------------------------------------
		// Comments
		// ------------------------------------------------------------------------
		$this->load->model('comment_model');
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		// we check validation before we get the comments so that if a visitor submitted a comment it appears right away
		if ($this->form_validation->run('posts/comments') == TRUE)
		{
			// Purify and format the comment body
			$this->load->plugin('htmlpurifier_pi.php');
			$body = purify(markdown($this->input->post('body')));
			
			$comment_data['author_name'   ] = $this->input->post('name');
			$comment_data['author_email'  ] = $this->input->post('email');
			$comment_data['author_website'] = $this->input->post('website');
			$comment_data['body'          ] = $body;
			$comment_data['parent_id'     ] = $this->input->post('reply_to');
			
			// ------------------------------------------------------------------------
			// Akismet Verification
			// ------------------------------------------------------------------------
			$this->load->library('azakis');
			
			if (!$this->azakis->is_spam($comment_data))
			{
				$comment_data['is_spam']    = 0;
				$data['notice'] = 'Thanks for posting a comment!';
			}
			else
			{
				// if the comment is spam, we still want it to add it as spam to
				// the database temporarily just in case Akismet made a mistake
				$comment_data['is_spam'] = 1;
				// TODO - I might want to modify that message...make it less obvious that the comment was marked as spam...
				$data['notice'] = 'Thanks for posting a comment! It will in the post\'s comments list as soon as a moderator approves it.';
			}
			// ------------------------------------------------------------------------
			
			$comment_data['post_id'   ] = $post_id;
			$comment_data['created_at'] = date('Y-m-d H:i:s');
			$this->comment_model->add_comment($comment_data);
		}
		
		$comments = $this->comment_model->get_comments($post_id, 'formatted');
		if (empty($comments))
		{
			$data['comments_view_file'] = 'comments/no-comments';
			$data['comments'] = 'Post the first comment!';
		}
		else
		{
			$data['comments_view_file'] = 'comments/index';
			$data['comments'] = $comments['list'];
			$data['max_comment_depth'] = $this->config->item('max_comment_depth');
		}
		
		$data['comments_form'] = 'comments/add';
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		// Breadcrumbs
		// ------------------------------------------------------------------------
		$_seg_title  = $data['post']->title;
		$_seg_url    = 'posts/view/' . $post_id;
		$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
		
		$data['breadcrumbs'] = $this->azbraz->generate($breadcrumbs);
		// ------------------------------------------------------------------------
		
		$data['view_file'] = 'posts/view';
		$this->load->view($this->main_view, $data);
	} // End of view
	
} // End of Posts controller

/* End of file posts.php */
/* Location: ./application/controller/posts.php */
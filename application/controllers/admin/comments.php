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
	
	public function index($status = NULL)
	{
		// by default we want to display the ham only
		if ($status !== 'ham' && $status !== 'spam')
		{
			if (!empty($status))
				$data['notice'] = 'Invalid Request';
			$status = 'ham';
		}
		
		// get either the ham or the spam depending on the location of the user
		if ($status === 'ham' || $status === 'spam')
		{
			$data['section_title'] = ucfirst($status);
			$comments = $this->comment_model->get_comments('',$status);
		}
		
		// if there are no comments, we load a different view so that we don't get an error
		if ($comments['count'] == 0)
		{
			$data['comments']  = 'No ' . $status . ' comments have been posted yet...';
			$data['view_file'] = 'comments/no-comments';
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
			
			// configuration of the comments pagination
			$data['comments_per_page'] = 10;
			$data['offset'] = $this->uri->segment(5);
			
			// If the offset is invalid or NULL (in which case the user goes back to the first page anyway)
			// the user is sent back to the first page and a feedback message is displayed
			if ((!is_valid_number($data['offset']) || !array_key_exists($data['offset'],$data['comments'])) && !empty($data['offset']))
			{
				$this->session->set_flashdata('notice','Invalid Request');
				redirect('admin/comments/index/' . $status . '/0');
			}
			
			// load the pagination library
			$this->load->library('pagination');
			
			// configuration of the pagination links
			$config['base_url']       = site_url('/admin/comments/index/' . $status);
			$config['total_rows']     = $data['comments_count'];
			$config['per_page']       = $data['comments_per_page'];
			$config['num_links']      = 10;
			$config['uri_segment']    = 5;
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
										),
										array(
											'title' => ucfirst($status),
											'url' => 'admin/comments/' . $status
										)
									);
			
			// the page number segment of the breadcrumbs will only appear if there is at least two pages
			if($comments['count'] > $config['per_page'])
			{
				array_push($data['section_name'], 	array(
														'title' => 'page ' . get_page_number($data['offset'], $data['comments_per_page']),
														'url' => 'admin/comments/index/' . $data['offset']
													)
				);
			}
		}
		
		$this->load->view('admin/admin', $data);
		
	} // End of index
	
	public function submit_ham($comment_id)
	{
		if (!is_valid_number($comment_id))
		{
			log_message('error','Could not Submit ham, invalid comment id');
			$this->session->set_flashdata('notice','Could not Submit ham. Try again later please.');
			redirect('admin/comments/index/spam');
		}
		
		$this->comment_model->submit_ham($comment_id);
		$this->session->set_flashdata('notice','Ham submitted!');
		redirect('admin/comments/index/spam');
	} // End of submit_ham
	
	public function submit_spam($comment_id)
	{
		if (!is_valid_number($comment_id))
		{
			log_message('error','Could not Submit ham, invalid comment id');
			$this->session->set_flashdata('notice','Could not Submit spam. Try again later please.');
			redirect('admin/comments/index/ham');
		}
		
		$this->comment_model->submit_spam($comment_id);
		$this->session->set_flashdata('notice','Spam submitted!');
		redirect('admin/comments/index/ham');
	} // End of submit_spam
	
	public function delete($comment_id)
	{
		if (!is_valid_number($comment_id))
		{
			log_message('error','Could not delete comment, invalid comment id');
			$this->session->set_flashdata('notice','Could not delete comment. Try again later please.');
			redirect('admin/comments');
		}
		
		$this->comment_model->delete($comment_id);
		$this->session->set_flashdata('notice','Comment deleted!');
		redirect('admin/comments');
	} // End of delete
	
	public function confirm($comment_id)
	{
		if (!is_valid_number($comment_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/comments');
		}
		
		$data['view_file'] = 'admin/comments/confirm';
		$data['question'] = 'Are you sure you want to delete the following comment?';
		$data['comment'] = $this->comment_model->get_comment($comment_id);
		
		$this->load->view('admin/admin', $data);
	} // End of confirm
	
} // End of Comments controller

/* End of file comments.php */
/* Location: ./application/controllers/admin/comments.php */
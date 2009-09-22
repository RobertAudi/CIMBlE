<?php

class Posts extends Controller
{
	function __construct()
	{
		parent::Controller();
		
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->model('post_model');
	}
	
	function index()
	{
		$data['view_file'] = 'admin/posts/index';
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
		
		$data['posts'] = $this->post_model->get_posts(1);
		$this->load->view('admin/layout', $data);
	}
	
	/**
	 * Add a new post
	 **/
	function add()
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
		
			$this->load->view('admin/layout',$view_data);
		}
		// if there is either a post title or a post body, insert a new post in the database
		else
		{
			// retrieve the user info, more specifically, the user id - see $data[user_id]
			$user = $this->auth->get_user();
			
			// save the post attributes in a temporary array
			$data['title'] = $this->input->post('title');
			$data['body'] = $this->input->post('body');
			$data['user_id'] = $user['id'];
			$data['active'] = $this->input->post('active') == 'active' ? 1 : 0;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			
			// Create the new post
			$this->post_model->new_post($data);
			
			// redirect the user to the Posts section in the Dashboard
			redirect('admin/posts/index');
		}
	}
	
	/**
	 * Edit or Delete a post
	 */
	function edit()
	{
		$post_id = $this->uri->segment(4);
		
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
													'url' => 'admin/posts/edit/'.$post_id
												)
											);
			
			$view_data['post'] = $this->post_model->get_post($post_id, 0);
			
			if ($view_data['post'] === NULL)
			{
				$this->session->set_flashdata('notice','Invalid Request!');
				redirect(site_url('admin/posts/index'));
			}
			
			$this->load->view('admin/layout',$view_data);
		}
		
		// if the user clicked on the Delete button, delete the post...
		elseif ($this->input->post('delete'))
		{
			$this->post_model->delete_post($post_id);
			$this->session->set_flashdata('notice','Post deleted successfully!');
			redirect('admin/posts/index');
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
	}
}
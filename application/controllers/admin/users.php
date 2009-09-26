<?php

class Users extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model('user_model');
	}
	
	function index()
	{
		$data['view_file'] = 'admin/users/index';
		$data['section_name'] = array(
									array(
										'title' => 'Dashboard',
										'url' => 'admin'
									),
									array(
										'title' => 'Users',
										'url' => 'admin/users/index'
									)
								);
		
		$data['users'] = $this->user_model->get_users();
		$this->load->view('admin/layout', $data);
	}
	
	function delete($user_id)
	{
		if (!is_valid_number($user_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/users/index');
		}
		
		$this->user_model->delete_user($user_id);
		$this->session->set_flashdata('notice','User Deleted');
		redirect('admin/users/index');
	}
	
	function add()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		if ($this->form_validation->run('admin/user/add') == FALSE)
		{
			$data['view_file'] = 'admin/users/add';
			$data['section_name'] = array(
										array(
											'title' => 'Dashboard',
											'url' => 'admin'
										),
										array(
											'title' => 'Users',
											'url' => 'admin/users/index'
										),
										array(
											'title' => 'New User',
											'url' => 'admin/users/add'
										)
									);
			$this->load->view('admin/layout', $data);
		}
		else
		{
			$user['username'] = $this->input->post('username');
			$user['email'] = $this->input->post('email');
			$user['password'] = $this->input->post('password');
			$user['level'] = $this->input->post('level');
			
			$this->user_model->add_user($user);
			redirect('admin/users');
		}
	}
}
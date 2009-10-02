<?php

class Users extends Controller
{
	// constructor
	public function __construct()
	{
		parent::Controller();
		
		// load the user model
		$this->load->model('user_model');
	}
	
	// php4 compatibility
	public function Users()
	{
		self::__construct();
	}
	
	public function index()
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
		$this->load->view('admin/admin', $data);
	} // End of index
	
	public function add()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		if ($this->form_validation->run('admin/users/add') == FALSE)
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
			$this->load->view('admin/admin', $data);
		}
		else
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$email    = $this->input->post('email');
			
			$this->user_model->add_user($username, $password, $email);
			redirect('admin/users');
		}
	} // End of add
	
	public function delete($user_id)
	{
		if (!is_valid_number($user_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/users/index');
		}
		
		$this->user_model->delete_user($user_id);
		$this->session->set_flashdata('notice','User Deleted');
		redirect('admin/users/index');
	} // End of delete

} // End of Users controller

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */
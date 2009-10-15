<?php

class User extends Controller
{
	// constructor
	public function __construct()
	{
		parent::Controller();
		
		// load the user model
		$this->load->model('user_model');
		
		// load necessarry libraries and helpers
		$this->load->library('form_validation');
		$this->load->helper('form');
	}
	
	public function index()
	{
		$this->azauth->logged_in() ? redirect('admin/dashboard/index') : redirect('user/login');
	} // End of index
	
	public function login()
	{
		$data['view_file'] = 'user/login';
		
		// if validation fails
		if ($this->form_validation->run('user/login') == FALSE)
		{
			// send the user to the login form
			$this->load->view('main',$data);
		}
		else
		{
			// retrieve the username and password from the form
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			// try to log the user in
			if ($this->azauth->login($username,$password))
			{
				// if login succeeded, send the user to the dashboard
				redirect('admin/dashboard/index');
			}
			else
			{
				// if the login failed, send feedback to the user...
				$this->session->set_flashdata('notice','Wrong Login/Password combination');
				// ...and send the user back to the login form
				$this->load->view('main',$data);
			}
		}
	} // End of login
	
	public function logout()
	{
		$this->azauth->logout();
		redirect('posts/index');
	} // End of logout
	
	// NOTE: This method is for the developement environment only
	public function add()
	{
		$username = 'admin' ;
		$password = 'admin' ;
		$email    = 'admin@test.com' ;
		
		if ($this->user_model->add_user($username, $password, $email))
		{
			die('user added');
		}
		else
		{
			die('error');
		}
		
	} // End of add
	
} // End of User controller
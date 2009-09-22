<?php

class User extends Controller
{
	function __construct()
	{
		parent::Controller();
		
		$this->load->library('form_validation');
		$this->load->helper('form');
	}
	
	function index()
	{
		$this->auth->logged_in() ? redirect('admin/welcome/index') : redirect('user/login');
	}
	
	function login()
	{
		$data['view_file'] = 'user/login';
		
		if ($this->form_validation->run('user/login') == FALSE)
		{
			$this->load->view('layout',$data);
		}
		else
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			
			if ($this->auth->login($email,$password))
			{
				redirect('admin/welcome/index');
			}
			else
			{
				$this->session->set_flashdata('notice','Wrong Login/Password combination');
				$this->load->view('layout',$data);
			}
		}
	}
	
	function logout()
	{
		$this->auth->logout();
		redirect(site_url());
	}
	
	
	/**
	 * Add a user - method available for testing purposes only
	 **/
	function signup()
	{
		$user['email']    = 'admin@admin.com';
		$user['password'] = 'admin';
		$user['username'] = 'admin';
		$this->auth->signup($user);
		redirect('admin');
	}
}
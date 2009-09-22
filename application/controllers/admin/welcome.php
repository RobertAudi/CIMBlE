<?php

class Welcome extends Controller
{
	function __construct()
	{
		parent::Controller();
	}
	
	function index()
	{
		$data['view_file'] = 'admin/dashboard';
		$data['section_name'] = 	array(
										array(
											'title' => 'Dashboard',
											'url' => 'admin'
										)
									);
		$this->load->view('admin/layout', $data);
	}
}
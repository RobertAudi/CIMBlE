<?php

class Dashboard extends Controller
{
	// constructor
	public function __construct()
	{
		parent::Controller();
	}
	
	public function index()
	{
		$data['view_file'] = 'admin/dashboard';
		$data['section_name'] = 	array(
										array(
											'title' => 'Dashboard',
											'url' => 'admin'
										)
									);
		$this->load->view('admin/admin', $data);
	} // End of index

} // End of Dashboard controller
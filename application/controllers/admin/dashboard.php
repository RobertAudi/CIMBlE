<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	// constructor
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data['breadcrumbs'] = $this->azbraz->generate();
		$data['view_file'] = 'admin/dashboard';
		$this->load->view($this->main_admin_view, $data);
	} // End of index

} // End of Dashboard controller

/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */
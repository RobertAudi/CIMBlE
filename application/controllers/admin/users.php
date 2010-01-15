<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller
{
	/**
	 * The Constructor!
	 */
	public function __construct()
	{
		parent::__construct();
		
		// load the user model
		$this->load->model('user_model');
	}
	
// ------------------------------------------------------------------------
	
	/**
	 * List all the users
	 */
	public function index()
	{
		$data['breadcrumbs'] = $this->azbraz->generate();
		$data['view_file'] = 'admin/users/index';
		$data['users'] = $this->user_model->get_users();
		$this->load->view($this->main_admin_view, $data);
	} // End of index
	
// ------------------------------------------------------------------------
	
	/**
	 * Add a new user
	 */
	public function add()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		if ($this->form_validation->run('admin/users/add') == FALSE)
		{
			$data['breadcrumbs'] = $this->azbraz->generate();
			$data['view_file'] = 'admin/users/add';
			$this->load->view($this->main_admin_view, $data);
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
	
// ------------------------------------------------------------------------
	
	/**
	 * Delete a user
	 *
	 * @param int $user_id
	 */
	public function delete($user_id)
	{
		if ((bool)$this->session->userdata('confirmation') !== TRUE)
		{
			log_message('error','Uers Admin Controller : You can\'t access the delete action directly, you first need to pass by the confirmation action!');
			$this->session->set_flashdata('notice','Invalid Request!');
			redirect('admin/users');
		}
		
		if (!is_valid_number($user_id))
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/users');
		}
		
		$this->user_model->delete_user($user_id);
		$this->session->set_flashdata('notice','User Deleted');
		$this->session->unset_userdata('confirmation');
		redirect('admin/users');
	} // End of delete
	
// ------------------------------------------------------------------------
	
	/**
	 * Confirm a critical change. This can be the deletion of a user for instance.
	 *
	 * @param string $action
	 * @param int $user_id
	 */
	public function confirm($action = NULL, $user_id = NULL)
	{
		// Check if the user is valid
		if (empty($action) || empty($user_id) || !is_valid_number($user_id) || !is_valid_action($action) || $this->user_model->get_user($user_id) === NULL)
		{
			$this->session->set_flashdata('notice','Invalid Request');
			redirect('admin/users/index');
		}
		
		$data['question']  = 'Are you sure you want to delete the following user?';
		$data['user']      =  $this->user_model->get_user($user_id);
		$data['action']    = $action;
		
		$this->session->set_userdata(array('confirmation' => TRUE));
		
		// ------------------------------------------------------------------------
		// Breadcrums
		// ------------------------------------------------------------------------
		$_seg_title  = ucfirst($action) . ' User#' . $user_id . ': Confirmation';
		$_seg_url    = 'admin/users/confirm/' . $action . '/' . $user_id;
		$breadcrumbs = $this->azbraz->new_segment($_seg_title, $_seg_url);
		
		$data['breadcrumbs'] = $this->azbraz->generate();
		// ------------------------------------------------------------------------
		
		$data['view_file'] = 'admin/users/confirm';
		$this->load->view($this->main_admin_view, $data);
	} // End of confirm

} // End of Users controller

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */
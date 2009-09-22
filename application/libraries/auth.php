<?php

class Auth
{
	private $_ci;
	private $_user;
	
	function __construct()
	{
		$this->_ci = get_instance();
		$this->_ci->load->library('session');
		$this->_get_session();
	}
	
	/**
	 * Checks if a user is logged in
	 *
	 * @return bool
	 */
	function logged_in()
	{
		return $this->_user['logged_in'];
	}
	
	/**
	 * Gets the user info
	 *
	 * @return array
	 */
	function get_user()
	{
		return $this->_user;
	}
	
	/**
	 * Creates a new user in the database
	 *
	 * @param array $user 
	 * @return array
	 */
	function signup($user = NULL)
	{
		if (empty($user)) return FALSE;
		
		$encrypted = $this->_hash($user['password'], $user['email']);
		
		$clear_password = $user['password'];
		
		$user['password'] = $encrypted['hashed'];
		$user['salt'] = $encrypted['salt'];
		
		$this->_ci->db->insert('users', $user);
		
		$this->login($user['email'], $clear_password);
		
		return TRUE;
	}
	
	/**
	 * Login the user
	 *
	 * @param string $email 
	 * @param string $password 
	 * @return bool
	 */
	function login($email, $password)
	{
		$this->_ci->db->where('email', $email);
		$query = $this->_ci->db->get('users', 1);
		
		if ($query->num_rows() == 0) {
			return FALSE;
		}
		$user = $query->row_array();
		
		$hashed = $this->_hash($password, $email, $user['salt']);
		
		if ($user['password'] === $hashed['hashed']) {
			$this->_set_session($user);
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Logs out the user, clears the session
	 *
	 * @return void
	 */
	function logout()
	{
		$this->_clear_session();
	}
	
	/**
	 * Sends an email with a link to reset the user password securely
	 *
	 * @param string $email 
	 * @return bool
	 */
	function reset_password($email)
	{
		$this->_ci->db->where('email', $email);
		$query = $this->_ci->db->get('users', 1);
		
		if ($query->num_rows == 0) {
			return FALSE;
		}
		
		$user = $query->row_array();
		
		$hash = substr(sha1($email.rand()), 0, 10);
		
		$data = array(
					'reset_password_hash' => $hash
				);
		$this->_ci->db->where('id', $user['id']);
		$query = $this->_ci->db->update('users', $data);
		
		$this->_ci->load->library('email');
		
		// $config = 	array(
		// 							'protocol' => 'smtp',
		// 							'smt_host' => 'smtp.gmail.com',
		// 							'smtp_user' => 'youjiii@gmail.com',
		// 							'smtp_pass' => 'lala',
		// 							'smtp_port' => '576',
		// 							'smtp_timeout' => 5
		// 						);
		// 			$this->_ci->email->initialize($config);
		
		$this->_ci->email->to($user['email']);
		$this->_ci->email->from('no-reply@my-blog.com', 'Aziz Ligh');
		$this->_ci->email->subject('User Account Password Reset');
		
		$reset_url = site_url('account/reset/'.$hash);
		
		$message = "Hi {$user['username']},\n\nIt looks like you forgot your password!\nYou can reset it here:\n\n{$reset_url}";
		$this->_ci->email->message($message);
		
		$this->_ci->email->send();
		
		return TRUE;
	}
	
	/**
	 * Sets a new user password in the database
	 *
	 * @param int $user_id 
	 * @param string $password 
	 * @return bool
	 */
	function set_password($user_id, $password)
	{
		$this->_ci->db->where('id', $user_id);
		$query = $this->_ci->db->get('users', 1);
		
		if ($query->num_rows == 0) {
			return FALSE;
		}
		
		$user = $query->row_array();
		
		$new_password = $this->_hash($password, $user['email']);
		
		$data = array(
					'password' => $new_password['hashed'],
					'salt' => $new_password['salt']
				);
		$this->_ci->db->where('id', $user['id']);
		$this->_ci->db->update('users', $data);
		
		return TRUE;
	}
	
	// =====================
	// = Private Functions =
	// =====================
	
	/**
	 * Hash a plain text password
	 *
	 * @param string $password 
	 * @param string $email 
	 * @param string $salt 
	 * @return array
	 */
	private function _hash($password, $email, $salt = NULL)
	{
		// retrieve the encryption key from config/config.php
		$encryption_key = $this->_ci->config->item('encryption_key');
		
		// if it's a login we don't want to create a new salt
		if ($salt === NULL) $salt = sha1(uniqid(rand(), TRUE));
		
		// generate the hashed password
		$hashed = sha1($password.$email.$encryption_key.$salt);
		
		return array('hashed' => $hashed, 'salt' => $salt);
	}
	
	/**
	 * Sets a new session
	 *
	 * @param array $user 
	 * @return void
	 */
	private function _set_session($user)
	{
		$data['id'] = $user['id'];
		$data['email'] = $user['email'];
		$data['username'] = $user['username'];
		$data['logged_in'] = TRUE;
		
		$this->_ci->session->set_userdata('auth', $data);
		$this->_get_session();
	}
	
	/**
	 * Gets the current session
	 *
	 * @return bool
	 */
	private function _get_session()
	{
		$user = $this->_ci->session->userdata('auth');
		
		if ($user === FALSE || (isset($user['logged_in']) && $user['logged_in'] !== '1')) {
			$user = array(
							'id' => 0,
							'logged_in' => FALSE
					);
			$this->_user = $user;
			return FALSE;
		} else {
			$this->_user = $user;
			return TRUE;
		}
	}
	
	/**
	 * Clears the current session
	 *
	 * @return void
	 */
	private function _clear_session()
	{
		$this->_ci->session->unset_userdata('auth');
		$this->_get_session();
	}
}
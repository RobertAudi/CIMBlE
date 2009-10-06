<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	// USER LOGIN
	'user/login' => array(
		// username
		array(
			'field' => 'username',
			'label' => 'Username',
			'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
		),
		// password
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
		)
	),
	
	// NEW POST
	'admin/posts/add' => array(
		// title
		array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'trim|required|min_length[1]|max_length[255]|xss_clean'
		),
		// body
		array(
			'field' => 'body',
			'label' => 'Content',
			'rules' => 'required|prep_for_form'
		)
	),
	
	// EDIT POST
	'admin/posts/edit' => array(
		// title
		array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'trim|required|min_length[1]|max_length[255]|xss_clean'
		),
		// body
		array(
			'field' => 'body',
			'label' => 'Content',
			'rules' => 'required|prep_for_form'
		)
	),
	
	// NEW USER
	'admin/users/add' => array(
		// username - TODO: add a callback to verify that the username is unique
		array(
			'field' => 'username',
			'label' => 'Username',
			'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
		),
		// email - TODO: add a callback to verify that the email is unique
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email|max_length[55]|matches[email_confirmation]|xss_clean'
		),
		// email confirmation
		array(
			'field' => 'email_confirmation',
			'label' => 'Email Confirmation',
			'rules' => 'trim|required|valid_email|max_length[55]|matches[email]|xss_clean'
		),
		// password
		array(
			'field' => 'password',
			'label' => 'Password',
			'rules' => 'trim|required|min_length[4]|max_length[40]|matches[password_confirmation]|xss_clean'
		),
		// password confirmation
		array(
			'field' => 'password_confirmation',
			'label' => 'Password Confirmation',
			'rules' => 'trim|required|min_length[4]|max_length[40]|matches[password]|xss_clean'
		)
	),
	
	// NEW COMMENT
	'posts/comments' => array(
		// name
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => 'trim|required|min_length[3]|max_length[50]|xss_clean'
		),
		// email
		array(
			'field' => 'email',
			'label' => 'Email',
			'rules' => 'trim|required|valid_email|min_length[8]|max_length[100]|xss_clean'
		),
		// website
		array(
			'field' => 'website',
			'label' => 'Website',
			'rules' => 'trim|min_length[5]|max_length[100]|xss_clean'
		),
		// comment body
		array(
			'field' => 'body',
			'label' => 'Comment',
			'rules' => 'required|prep_for_form'
		),
	)
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */
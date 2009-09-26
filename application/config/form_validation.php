<?php

$config = array(
				// User Login
				'user/login' => array(
					array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|valid_email|xss_clean'
					),
					array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
					)
				),
				
				// Edit Post
				'admin/posts/edit' => array(
					array(
						'field' => 'title',
						'label' => 'Title',
						'rules' => 'trim|required|min_length[1]|max_length[255]|xss_clean'
					),
					array(
						'field' => 'body',
						'label' => 'Content',
						'rules' => 'required|prep_for_form'
					)
				),
				
				// New Post
				'admin/posts/add' => array(
					array(
						'field' => 'title',
						'label' => 'Title',
						'rules' => 'trim|required|min_length[1]|max_length[255]|xss_clean'
					),
					array(
						'field' => 'body',
						'label' => 'Content',
						'rules' => 'required|prep_for_form'
					)
				),
				
				// New User
				'admin/user/add' => array(
					array(
						'field' => 'username',
						'label' => 'Username',
						'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean'
					),
					// TODO: add a callback to verify that the email is unique
					array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|valid_email|matches[email_confirmation]|xss_clean'
					),
					array(
						'field' => 'email_confirmation',
						'label' => 'Email Confirmation',
						'rules' => 'trim|required|valid_email|matches[email]|xss_clean'
					),
					array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|min_length[4]|max_length[40]|matches[password_confirmation]|xss_clean'
					),
					array(
						'field' => 'password_confirmation',
						'label' => 'Password Confirmation',
						'rules' => 'trim|required|min_length[4]|max_length[40]|matches[password]|xss_clean'
					)
				)
);
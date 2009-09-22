<?php

$config = array(
				'user/login' => array(
					array(
						'field' => 'email',
						'label' => 'Email',
						'rules' => 'trim|required|valid_email|xss_clean'
					),
					array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'trim|required|min_length[4]|max_length[15]|xss_clean'
					)
				),
				
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
				)
);
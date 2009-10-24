<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title><?php echo BLOG_TITLE ?></title>
	</head>
	<body>
		<div id="debug_bar">
			<?php if ($this->azauth->logged_in()): ?>
				<p><a href="<?php echo site_url('user/logout'); ?>">Log out</a></p>
				<p><a href="<?php echo site_url('admin/dashboard/index'); ?>">Dashboard</a></p>
			<?php else: ?>
				<p><a href="<?php echo site_url('user/login'); ?>">Log in</a></p>
			<?php endif ?>
			<p><a href="<?php echo site_url('user/add'); ?>">Add User</a></p>
		</div>
		
		<div id="header">
			<!-- Blog Title -->
			<h1 class="blog-title"><a href="<?php echo site_url(); ?>"><?php echo BLOG_TITLE; ?></a></h1>
		</div> <!--END OF header-->
		
		<div id="main-content">
			<!-- Breadcrubs -->
			<p class="breadcrumbs"><?php if(isset($section_name)) echo to_breadcrumb($section_name, '&rsaquo;'); ?></p>
			
			<!-- The Flash -->
			<?php if ($this->session->flashdata('notice')): ?>
				<p class="notice"><?php echo $this->session->flashdata('notice'); ?></p>
			<?php elseif(isset($notice) && !empty($notice)): ?>
				<p class="notice"><?php echo $notice; ?></p>
			<?php endif; ?>
			
			<!-- Dynamic view file -->
			<?php $this->load->view($view_file); ?>
		</div> <!--END OF main-content-->
	</body>
</html>
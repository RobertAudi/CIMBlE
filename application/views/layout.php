<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		
		<title><?php echo BLOG_TITLE ?></title>
		
		<?php echo link_tag(site_url('../public/css/main.css')); ?>
	</head>
	<body>
		<div id="header">
			<!--
				FIXME: Those links below are here for the developement environement only.
			-->
			<p> 
				<?php if ($this->auth->logged_in()): ?>
					<a href="<?php echo site_url('user/logout'); ?>">Logout</a> | <a href="<?php echo site_url('admin'); ?>">Dashboard</a>
				<?php else: ?>
					<a href="<?php echo site_url('user/login'); ?>">Log in</a>
				<?php endif; ?>
			</p>
			<h1 class="blog-title"><a href="<?php echo site_url(); ?>"><?php echo BLOG_TITLE ?></a></h1>
		</div> <!--END OF header-->
		
		<div id="main-content">
			<p class="breadcrumbs"><?php if(isset($section_name)) echo to_breadcrumb($section_name, '&rsaquo;'); ?></p>
			
			<!-- The Flash -->
			<?php if ($this->session->flashdata('notice')): ?>
				<p class="notice"><?php echo $this->session->flashdata('notice'); ?></p>
			<?php endif; ?>
			
			<?php $this->load->view($view_file); ?>
		</div> <!--END OF main-content-->
		
	</body>
</html>
<?php $this->load->view('common/head'); ?>
	<body>
		<div id="auth-bar">
			<?php if ($this->azauth->logged_in()): ?>
				<p><a href="<?php echo site_url('user/logout'); ?>">Log out</a></p>
				<p><a href="<?php echo site_url('admin/dashboard/index'); ?>">Dashboard</a></p>
			<?php else: ?>
				<p><a href="<?php echo site_url('user/login'); ?>">Log in</a></p>
			<?php endif ?>
		</div> <!--END OF auth-bar-->
		
		<div id="header">
			<!-- Blog Title -->
			<h1 class="blog-title"><a href="<?php echo site_url(); ?>"><?php echo BLOG_TITLE; ?></a></h1>
		</div> <!--END OF header-->
		
		<div id="main-content">
			<p class="breadcrumbs"><?php if(isset($breadcrumbs)) echo $breadcrumbs; ?></p>
			
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
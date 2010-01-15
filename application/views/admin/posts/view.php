<div class="post">
	<h2 class="post-title"><a href="<?php echo site_url('posts/view/'.$post->id); ?>"><?php echo $post->title; ?></a></h2>
	<div class="post-content">
		<?php echo $post->body; ?>
	</div> <!--END OF post-content-->
	<p class="post-meta">Published on <?php echo format_date($post->created_at); ?> by <?php echo $post->username; ?></p>
	
	<!--
	TODO - I want to put that in the top nav bar instead...
	-->
	<?php if ($this->azauth->logged_in()): ?>
		<?php $status_action = ((bool)$post->active === TRUE) ? 'unpublish' : 'publish'; ?>
		<p class="admin-meta"><a href="<?php echo site_url() . '/admin/posts/edit/' . $post->id; ?>">Edit</a>, <a href="<?php echo site_url('admin/posts/confirm/' . $status_action . '/' . $post->id); ?>"><?php echo ucfirst($status_action); ?></a></p>
	<?php endif ?>
	
</div> <!--END OF post-->

<h3>Comments</h3>
<?php $this->load->view($comments_view_file, $comments); ?>
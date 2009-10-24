<div class="post">
	<h2 class="post-title"><a href="<?php echo site_url('posts/view/'.$post->id); ?>"><?php echo $post->title; ?></a></h2>
	<div class="post-content">
		<?php echo $post->body; ?>
	</div> <!--END OF post-content-->
	<p class="post-meta">Published on <?php echo format_date($post->created_at); ?> by <?php echo $post->username; ?></p>
</div> <!--END OF post-->

<h3>Comments</h3>
<?php $this->load->view($comments_view_file, $comments); ?>
<?php $this->load->view($comments_form, $post_id); ?>
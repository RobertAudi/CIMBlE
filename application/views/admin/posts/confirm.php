<div>
	<h3><?php echo $question; ?></h3>
	<ul>
		<li><strong>Title:</strong> <?php echo $post->title; ?></li>
		<li><strong>Author:</strong> <?php echo $post->username; ?></li>
		<li><strong>Submission date:</strong> <?php echo format_date($post->created_at); ?></li>
	</ul>
	<p><?php echo $post->body; ?></p>
</div>
<p><a href="<?php echo site_url('admin/posts/delete/' . $post->id) ?>">Delete!</a></p>
<p><a href="<?php echo site_url('admin/posts') ?>">Cancel!</a></p>
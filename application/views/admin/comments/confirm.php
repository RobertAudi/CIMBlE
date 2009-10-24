<div>
	<h3><?php echo $question; ?></h3>
	<ul>
		<li><strong>Reply to:</strong> <?php echo $comment->title; ?></li>
		<li><strong>Author:</strong> <?php echo $comment->author_name; ?></li>
		<li><strong>Author's Website:</strong> <?php echo $comment->author_website; ?></li>
		<li><strong>Submission date:</strong> <?php echo format_date($comment->created_at); ?></li>
	</ul>
	<p><?php echo $comment->body; ?></p>
</div>
<p><a href="<?php echo site_url('admin/comments/delete/' . $comment->id) ?>">Delete!</a></p>
<p><a href="<?php echo site_url('admin/comments') ?>">Cancel!</a></p>
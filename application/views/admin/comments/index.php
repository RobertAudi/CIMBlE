<ul>
	<li><a href="<?php echo site_url('admin/comments/ham'); ?>">Ham</a></li>
	<li><a href="<?php echo site_url('admin/comments/spam'); ?>">Spam</a></li>
</ul>

<div>
	<h2><?php echo $section_title; ?></h2>
	<p>Number of comments: <?php echo $comments_count; ?></p>
	<table>
		<tr>
			<th>Author</th>
			<th>Comment</th>
			<th>Post</th>
			<th>Date Posted</th>
			<th>is_spam</th>
		</tr>
		<?php foreach ($comments as $comment): ?>
			<tr>
				<td><?php echo $comment->author_name; ?></td>
				<td><?php echo truncate_string($comment->body, 40); ?></td>
				<td><a href="<?php echo site_url('posts/view/' . $comment->post_id) ?>"><?php echo truncate_string($comment->title, 30); ?></a></td>
				<td><?php echo format_date($comment->created_at); ?></td>
				<td><?php echo $comment->is_spam; ?></td>
				<td><?php echo ((int)$comment->is_spam === 1) ? '<a href="' . site_url('admin/comments/submit_ham/' . $comment->id) . '">Ham</a>' : '<a href="' . site_url('admin/comments/submit_spam/' . $comment->id) . '">Spam</a>'; ?></td>
				<td><a href="<?php echo site_url('admin/comments/confirm/' . $comment->id) ?>">Delete</a></td>
				<!--
					TODO replace the above with the following: serialize the object and store it in the session then in the confirm page unserialize it and use it
				-->
			</tr>
		<?php endforeach ?>
	</table>
</div>

<?php echo $pagination_links; ?>
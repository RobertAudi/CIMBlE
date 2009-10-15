<div>
	<p>Number of comments: <?php echo $comments_count; ?></p>
	<table>
		<tr>
			<th>Author</th>
			<th>Comment</th>
			<th>Post</th>
		</tr>
		<?php foreach ($comments as $comment): ?>
			<tr>
				<td><?php echo $comment->author_name; ?></td>
				<td><?php echo truncate_string($comment->body, 40); ?></td>
				<td><a href="<?php echo site_url('posts/view/' . $comment->post_id) ?>"><?php echo truncate_string($comment->title, 30); ?></a></td>
			</tr>
		<?php endforeach ?>
	</table>
</div>

<?php echo $pagination_links; ?>
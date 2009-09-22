<p><a href="<?php echo site_url('admin/posts/add'); ?>">New Post</a></p>

<table>
	<tr>
		<th>Title</th>
		<th>Author</th>
		<th>Creation Date</th>
		<th>Last Modified</th>
		<th>Active?</th>
	</tr>
	<?php foreach ($posts as $post): ?>
		<tr>
			<td><a href="<?php echo site_url('admin/posts/edit/'.$post->id); ?>"><?php echo $post->title; ?></a></td>
			<td><?php echo $post->username ?></td>
			<td><?php echo $post->created_at; ?></td>
			<td><?php echo $post->updated_at; ?></td>
			<td><?php echo is_active_in_english($post->active); ?></td>
			<td><a href="<?php echo site_url('admin/posts/edit/'.$post->id); ?>">Edit</a></td>
		</tr>
	<?php endforeach; ?>
</table>
<?php foreach ($users as $user): ?>
	<table>
		<tr>
			<th>Username</th>
			<th>Email</th>
		</tr>
		<tr>
			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->email; ?></td>
			<td><a href="<?php echo site_url('admin/users/delete/'.$user->id) ?>">Delete</a></td>
		</tr>
	</table>
<?php endforeach ?>
<p><a href="<?php echo site_url('admin/users/add') ?>">Add User</a></p>
<table>
	<tr>
		<th>Username</th>
		<th>Email</th>
		<th>Level</th>
	</tr>
<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user->username; ?></td>
		<td><?php echo $user->email; ?></td>
		<td><?php echo user_level_in_english($user->level); ?></td>
		<td><a href="<?php echo site_url('admin/users/delete/'.$user->id) ?>">Delete</a></td>
	</tr>
<?php endforeach ?>
</table>
<div>
	<h3><?php echo $question; ?></h3>
	<ul>
		<li><strong>Username:</strong> <?php echo $user->username; ?></li>
		<li><strong>Email:</strong> <?php echo $user->email; ?></li>
	</ul>
</div>
<p><a href="<?php echo site_url('admin/users/delete/' . $user->id) ?>">Delete!</a></p>
<p><a href="<?php echo site_url('admin/users') ?>">Cancel!</a></p>
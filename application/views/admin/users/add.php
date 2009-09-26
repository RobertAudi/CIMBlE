<!-- Validation Errors -->
<?php if (validation_errors()): ?>
	<ul class="errors">
		<?php echo validation_errors('<li>','</li>'); ?>
	</ul>
<?php endif ?>

<?php echo form_open(current_url()); ?>
	<?php echo form_fieldset('New User'); ?>
		<div>
			<?php echo form_label('Username','username'); ?>
			<?php echo form_input('username',''); ?>
		</div>
		
		<div>
			<?php echo form_label('Email','email'); ?>
			<?php echo form_input('email',''); ?>
		</div>
		
		<div>
			<?php echo form_label('Email Confirmation','email_confirmation'); ?>
			<?php echo form_input('email_confirmation',''); ?>
		</div>
		
		<div>
			<?php echo form_label('Password','password'); ?>
			<?php echo form_password('password',''); ?>
		</div>
		
		<div>
			<?php echo form_label('Password Confirmation','password_confirmation'); ?>
			<?php echo form_password('password_confirmation',''); ?>
		</div>
		
		<div>
			<?php echo form_label('User Level','level'); ?>
			<?php
			$levels = 	array(
							'1'  => 'Administrator',
							'100' => 'Editor',
						);
			echo form_dropdown('level', $levels, '100');
			?>
		</div>
		
		<?php echo form_submit('submit','Add'); ?>
	</fieldset>
</form>
<!-- Validation Errors -->
<?php if (validation_errors()): ?>
	<ul class="errors">
		<?php echo validation_errors('<li>','</li>'); ?>
	</ul>
<?php endif ?>

<?php echo form_open(site_url('user/login')); ?>
	<?php echo form_fieldset('Login to your account'); ?>
		<div>
		<?php echo form_label('Email:','email'); ?>
		<?php echo form_input('email',''); ?>
		</div>
		
		<div>
			<?php echo form_label('Password:','password'); ?>
			<?php echo form_password('password',''); ?>
		</div>
		
		<?php echo form_submit('submit','Login'); ?>
	</fieldset>
</form>
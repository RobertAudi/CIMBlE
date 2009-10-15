<!-- Validation Errors -->
<?php if (validation_errors()): ?>
	<ul class="errors">
		<?php echo validation_errors('<li>','</li>'); ?>
	</ul>
<?php endif; ?>

<?php echo form_open(site_url('posts/view/'.$post_id)); ?>
	<?php echo form_fieldset('Leave a comment'); ?>
		
		<div>
		<?php echo form_label('Name','name'); ?>
		<?php echo form_input('name', set_value('name')); ?>
		</div>
		
		<div>
		<?php echo form_label('Email','email'); ?>
		<?php echo form_input('email', set_value('email')); ?>
		</div>
		
		<div>
		<?php echo form_label('Website','website'); ?>
		<?php echo form_input('website', set_value('website')); ?>
		</div>
		
		<div>
		<?php echo form_textarea('body', ''); ?>
		</div>
		
		<p><?php echo form_submit('submit', 'Submit'); ?></p>
	</fieldset>
</form>
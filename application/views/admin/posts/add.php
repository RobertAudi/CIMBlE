<!-- Validation Errors -->
<?php if (validation_errors()): ?>
	<ul class="errors">
		<?php echo validation_errors('<li>','</li>'); ?>
	</ul>
<?php endif ?>

<?php echo form_open(current_url()); ?>
	<?php echo form_fieldset('New Post'); ?>
		<div>
		<?php echo form_label('Title','title'); ?>
		<?php echo form_input('title', set_value('title')); ?>
		</div>
		
		<div>
		<?php echo form_label('Content','body'); ?>
		<?php echo form_textarea('body', set_value('body')); ?>
		</div>
		
		<div>
		<?php $attributes['class'] = 'inline'; ?>
		<?php echo form_label('Active','active',$attributes); ?>
		<?php echo form_checkbox('active','active',FALSE); ?>
		</div>
		
		<p><?php echo form_submit('submit', 'Post'); ?></p>
	</fieldset>
</form>
<!-- Validation Errors -->
<?php if (validation_errors()): ?>
	<ul class="errors">
		<?php echo validation_errors('<li>','</li>'); ?>
	</ul>
<?php endif ?>

<?php echo form_open(current_url()); ?>
	<?php echo form_fieldset('Edit Post'); ?>
		<div>
		<?php echo form_label('Title','title'); ?>
		<?php echo form_input('title',repopulate($post->title,$this->input->post('title'))); ?>
		</div>
		
		<div>
		<?php echo form_label('Content','body'); ?>
		<?php echo form_textarea('body',repopulate($post->body,$this->input->post('body'))); ?>
		</div>
		
		<div>
		<?php $attributes['class'] = 'inline'; ?>
		<?php echo form_label('Active','active',$attributes); ?>
		<?php echo form_checkbox('active','active',repopulate(is_active($post->active),$this->input->post('active'))); ?>
		</div>
		
		<p><?php echo form_submit('delete', 'Delete'); ?><?php echo form_submit('update', 'Update'); ?></p>
	</fieldset>
</form>
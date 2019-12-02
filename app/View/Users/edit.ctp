<div class="admin-table">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
		echo $this->Form->input('role_id');
	?>
	</fieldset>
<?php echo $this->Form->end(array('label'=>'Submit','class'=>'dark-btn cursor-pointer')); ?>
</div>

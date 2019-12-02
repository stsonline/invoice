<!-- app/View/Users/add.ctp -->
<!-- adds a new user -->
<div class="large-container">
	<div class="users form">
	<?php echo $this->Form->create('User'); ?>
	    <fieldset>
	        <legend><?php echo __('Add User'); ?></legend>
	        <?php 
	        	echo $this->Form->input('username');
	        	echo $this->Form->input('password');
	    	?>
	    </fieldset>
	<?php echo $this->Form->end(array('label'=>'Submit','class'=>'dark-btn cursor-pointer')); ?>
	</div>
</div>	
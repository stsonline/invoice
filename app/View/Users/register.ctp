<div class="sts-intro">
	<h1>Login</h1>
</div>
<div class="large-container">	
	<div class="registration-form">
	<?php echo $this->Session->flash('auth'); ?>
	<?php echo $this->Form->create('User'); ?>
	    <fieldset>
	        <legend><?php echo __('Please enter your username and password'); ?></legend>
	        <?php echo $this->Form->input('username');
	        echo $this->Form->input('password');
	    ?>
	    </fieldset>
	<?php echo $this->Form->end(__('Login')); ?>
	</div>
</div>	

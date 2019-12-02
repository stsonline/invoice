<?php
	$this->set('title_for_layout', 'Team 8: Icing CMS');
	$this->set('description_for_layout', 'Welcome back to Team 8. Please login to view your profile, update your projects, and manage your invoices.');
?>

<div class="page-content large-container">
	<?php echo $this->Session->flash('auth'); ?>
	<div class="sts-intro">
		<h1><strong>Team 8</strong></h1>
		<h2>Account Login</h2>
	</div>
	<div class="user-login-form">
		<?php echo $this->Form->create('User', array('data-ajax' => "false","class"=>'')); ?>
		    <fieldset>
		        <?php
		        	echo $this->Form->input('username',array("class"=>""));
		        	echo $this->Form->input('password');
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(array('label'=>'Sign In','class'=>'dark-btn cursor-pointer')); ?>
	</div>
</div>

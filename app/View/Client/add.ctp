<!-- app/View/Clients/add.ctp -->
<!-- adds a new user -->

<div class="sts-intro admin-intro">
	<h1>Add Client</h1>
</div>

<div class="admin-form">
<?php echo $this->Form->create('Client'); ?>
    <fieldset>
        <?php 
           
        	echo $this->Form->input('firstname');
        	echo $this->Form->input('lastname');
        	echo $this->Form->input('email');
        	echo $this->Form->input('organisation');
        ?>
    </fieldset>
<?php echo $this->Form->end(array('label'=>'Submit','class'=>'dark-btn cursor-pointer')); ?>
</div>
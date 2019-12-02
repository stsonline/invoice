<div class="sts-intro admin-intro">
	<h1>Client</h1>
</div>     
<p>
	<?php echo  'Id: ' . $a_client['Client']['id'] ?>
</p>
<p>
	<?php echo $a_client['Client']['firstname'] . ' ' . $a_client['Client']['lastname']; ?>
</p>
<p>
	<?php echo $a_client['Client']['organisation']; ?>
</p>
<p>
	<?php echo $a_client['Client']['email']; ?>
</p>
<p>
	<?php echo $this->Html->link('Invoices', array('controller' => 'invoices', 'action' => 'index',$a_client['Client']['id'])); ?>
</p>

<?php unset($a_client); ?>
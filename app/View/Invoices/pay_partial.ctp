<!-- app/View/Invoices/add.ctp -->
<!-- adds a new invoice -->
<div class="sts-intro admin-intro">
	<h1>Partially Pay Invoice</h1>
</div>
<div class="invoices form">
<?php echo $this->Form->create('Invoice'); ?>
<div id="client-details">	
	<?php echo $this->Form->input('Invoice.id', array("options" => $all_ids,'selected' => $invoice_id)); ?>
	<?php echo $this->Form->input('Invoice.pay_amount'); ?>
	<?php echo $this->Form->end(array('label'=>'Submit','class'=>'dark-btn cursor-pointer')); ?>
</div>
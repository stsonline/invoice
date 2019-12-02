<?php 
	echo $this->Html->css('paginator');
	echo $this->Html->script('Drophelp/index');
?>

<div class="sts-intro admin-intro">
	<h1>Payments</h1>
</div>

<div id="client-details">	
	<h2>Client</h2>
	<ul>
	<?php  
		echo "<li>".$client['Client']['id'].", ".$client['Client']['firstname']." ".$client['Client']['lastname']."</li><li>".$client['Client']['email']."</li><li>".$client['Client']['organisation']."</li>";
	?>
	</ul>
</div>
<div class="admin-table">
	<table>
	    <tr>
	        <th>Id</th>
			 <th>Invoice Id</th>
			<th class="decimal">Total</th>
			<th>Type</th>
	        <th>Date</th>
	    </tr>
	    <?php   
	    foreach ($payments as $payment)
		{?>
	    <tr>
	        <td>
	        	<?php echo $payment['Payment']['id']; ?>
	        </td>
	        <td>
	        	<?php echo $this->Html->link('Invoice Id - '.$payment['Payment']['invoice_id'], array('controller' => 'invoices', 'action' => 'view',$payment['Payment']['invoice_id'])); ?>
	        </td>
	        <td class="decimal">
	        	<?php echo $payment['Invoice']['total']; ?>
	        </td>
	        <td>
	        	<?php echo $payment['Payment']['type']; ?>
	        </td>
	        <td>
	        	<?php echo $payment['Payment']['date']; ?>
	        </td>
	     </tr>
	  	 <?php   
		 }
		 ?>
	</table>
</div>
<?php unset($payment); ?>
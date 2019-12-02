<div class="sts-intro admin-intro">      
	<h1>All Payments</h1>
</div>
<div class="admin-table">
	<table>
	    <tr>
	        <th>Id</th>
	        <th>Client Id</th>
	        <th>Invoice Id</th>
	        <th class="decimal">Total</th>
	    	<th>Type</th>
	        <th>Date</th>
	    </tr>
	    <?php   
	    foreach ($payments as $payment)
		{
		?>
	    <tr>
	        <td>
	        	<?php echo $payment['Payment']['id']; ?>
	        </td>
	        <td>
	        	<?php echo $this->Html->link('Client Id - '.$payment['Payment']['client_id'], array('controller' => 'clients', 'action' => 'view',$payment['Payment']['client_id'])); ?>
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
<div class="sts-intro admin-intro">
	<h1>Recurring Invoices</h1>
</div>       
<div class="page-nav">
	<?php 
		echo  $this->Paginator->first(' <<' ).
		$this->Paginator->prev(' Previous ' ).
		$this->Paginator->next(' Next ').
		$this->Paginator->last('>> ');
	?>
</div>
<div class="admin-table">	
	<table>
	    <tr>
	        <th>Id</th>
	        <th>Client</th>
	        <th>Frequency</th>
	        <th>Invoicing Date</th>
	        <th>Invoice Period</th>
	        <th title="Invoices won't be created before this date.">Start Date</th>
	        <th class='decimal'>Total</th>
	        <th>Status</th>
	        <th></th>
	        <th></th>
		</tr>
	    <?php   
	    foreach ($recurring_invoices as $invoice)
		{?>
	    <tr>
	        <td>
	        	<?php echo $invoice['RecurringInvoice']['id']; ?>
	        </td>
	        <td title="<?php echo $invoice['Client']['firstname']." ".$invoice['Client']['lastname'];?>">
	        	<?php 
	         		echo $this->Html->link($invoice['Client']['id'].' '.$invoice['Client']['organisation'], array('controller' => 'clients', 'action' => 'view', $invoice['Client']['id']));
	        	?>
	        </td>
	        <td><?php echo $invoice['RecurringInvoice']['type_string']; ?></td>
	        <td><?php echo $invoice['RecurringInvoice']['date']; ?></td>
	        <td><?php echo $invoice['RecurringInvoice']['period_string']; ?></td>
	        <td><?php echo isset($invoice['RecurringInvoice']['start_date']) ? date("jS M Y",strtotime($invoice['RecurringInvoice']['start_date'])) : '' ; ?></td>
	        <td class='decimal'>
	        	<?php echo $invoice['RecurringInvoice']['total']; ?>
	        </td>   
	        <td><?php 
	        $opposite_status = $invoice['RecurringInvoice']['active'] ? 0 : 1;
	        $status_string = $invoice['RecurringInvoice']['active'] ? "Active" : "Disabled";
	        echo $this->Html->link($status_string, array('controller' => 'invoices', 'action' => 'recurring_status', $invoice['RecurringInvoice']['id'], $opposite_status));
	        ?></td>
	        <td><?php echo $this->Html->link('View/Edit', array('controller' => 'invoices', 'action' => 'recurring', $invoice['RecurringInvoice']['id'])); ?></td>
	        <td><?php echo $this->Html->link('Delete', array('controller' => 'invoices', 'action' => 'delete_recurring', $invoice['RecurringInvoice']['id']),array('class' => 'confirm-delete', 'data-client' => $invoice['Client']['organisation'], 'data-id' => $invoice['RecurringInvoice']['id'])); ?></td>
	     </tr>
	     <?php 
		 } ?>   
	</table>
</div>
<div class="page-nav">
	<?php 
		echo  $this->Paginator->first(' <<' ).
		$this->Paginator->prev(' Previous ' ).
		$this->Paginator->next(' Next ').
		$this->Paginator->last('>> ');
	?>
</div>
<script>
$(document).ready(function() {
	$('.confirm-delete').click(function(event) {
		event.preventDefault();
		var client = $(this).attr("data-client");
		var id = $(this).attr("data-id");
		var del = confirm("Do you want to delete the Recurring Invoice " + id + " for the client " + client + "?");
		if(del) {
			window.location = "<?php echo $this->webroot.'invoices/delete_recurring/' ?>" + id;
		}
	});
});
</script>
<?php unset($invoice); ?>
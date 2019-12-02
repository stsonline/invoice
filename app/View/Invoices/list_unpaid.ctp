<div class="sts-intro admin-intro">
	<h1>Unpaid Invoices</h1> 
	<h4 style="text-align:left;">Unpaid balance: &pound;<?php echo number_format($unpaid_total, 2); ?></h4>
	<!--<p style="text-align: center;"><?php echo $this->Html->link("Send Unsent Invoices", array('controller' => 'invoices', 'action' => 'send_unsent', "sendit")); ?></p>-->
	
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
	<table class=invoice-table>
	    <tr>
	        <th><?php echo $this->Paginator->sort('Invoice.id','Invoice Id'); ?></th>
	        <th><?php echo $this->Paginator->sort('Client.lastname','Client'); ?></th>
  			<th>Invoice Date</th>
	        <th>Paid</th>
			<th class="decimal" title="Total invoice amount"><?php echo $this->Paginator->sort('Invoice.total','Total'); ?></th>
			<th class="decimal" title="Amount currently paid towards invoice"><?php echo $this->Paginator->sort('Invoice.credit','Credit'); ?></th>
			<th class="decimal" title="Amount left to pay">Balance</th>
			<th title="Number of emails sent for this invoice">Emails Sent</th>
			<th title="Last time an email was sent regarding this invoice.">Last Contact</th>
			<th title="Send email for this invoice">NotificationActions</th>
			<th>InvoiceActions</th>
		</tr>
	
	    <?php   
	    foreach ($invoices as $invoice)
		{
		if($invoice['Invoice']['new_month']!=0)
		{ ?>
	    <tr class="invoice-new-month-row">
		<?php } 
		else 
		{ ?>
	    <tr>
		<?php } ?>
	        <td>
	        	<?php echo $invoice['Invoice']['id']; ?>
	        </td>
	        <td title="<?php echo $invoice['Client']['firstname']." ".$invoice['Client']['lastname'];?>">
	        	<?php 
	         		echo $this->Html->link($invoice['Client']['id'].' '.$invoice['Client']['organisation']. " ". $invoice['Client']['firstname']." ".$invoice['Client']['lastname'], array('controller' => 'invoices', 'action' => 'index', $invoice['Client']['id']));
	        	?>
	        </td>
	        <td>
	        	<?php echo date("jS M Y",strtotime($invoice['Invoice']['schedule_for']));?>
	        </td>
	        <td class="<?php echo $invoice['Invoice']['paid'] ? 'invoice-paid' : 'invoice-unpaid'; ?>">
	        	<?php if($invoice['Invoice']['paid']==1) { ?>
	        		<i style="color:green;" class="fa fa-check"></i>
	        	<?php }else{ ?>	
	        		<i style="color:red;" class="fa fa-times"></i>
        		<?php } ?>	
	        </td>
	        <td class='decimal'>
	        	<?php echo $invoice['Invoice']['total']; ?>
	        </td>
	        <td class='decimal'>
	        	<?php echo $invoice['Invoice']['credit']; ?>
	        </td>
	        <td class='decimal<?php echo (" invoice-".($invoice['Invoice']['credit']==0?"red":($invoice['Invoice']['credit']==$invoice['Invoice']['total']?"green":"blue")))?>'>
	        	<?php echo number_format($invoice['Invoice']['total']-$invoice['Invoice']['credit'],2); ?>
	        </td>
	        <td>
	        	<?php echo $this->Html->link($invoice['Invoice']['sends'], array('controller' => 'responses', 'action' => 'index', $invoice['Invoice']['id']));?>
	        </td>
	        <td<?php if($invoice['Invoice']['alert'] && !$invoice['Invoice']['paid']) { echo ' class="invoice-alert"'; } ?>>
	        	<?php
	        	 if(isset($invoice['Invoice']['last_send_type'])) {
	        	 	echo '('.$email_types[$invoice['Invoice']['last_send_type']].') ';
	        	 }
	        	 echo strtotime($invoice['Invoice']['sent']) > 0 ? date("g:i a, j/n/Y",strtotime($invoice['Invoice']['sent'])) : "Never";
	        	 ?>
	        </td>
	        <td>
	        	<?php if($invoice['Invoice']['paid']==0){?>
	        		<a class="remind-invoice" reminder-level="1" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Invoice" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/1"><i style="color:green;" class="fa fa-file-text"></i> Invoice</a></li><br />
	        		<a class="remind-invoice" reminder-level="2" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Reminder" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/2"><i style="color: orange;" class="fa fa-file-text"></i> Remind &nbsp;</a></li><br>
	        		<a class="remind-invoice" reminder-level="6" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Service Suspension" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/6"><i style="color: orange;" class="fa fa-file-text"></i> Suspend &nbsp;</a></li><br>
	        		<a class="remind-invoice" reminder-level="3" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Final Demand" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/3"><i style="color: red;" class="fa fa-file-text"></i> Final &nbsp;</a></li>
	        		<br>
				<?php }?>
					
					<?php
	        		if($invoice['Invoice']['paid']==0)  {
	        			$opposite_status = $invoice['Invoice']['allow_auto_contact'] ? 0 : 1;
	        			if($invoice['Invoice']['allow_auto_contact']) {
	        				?>
	        				<a class="notify-invoice" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" href="<?php echo $this->webroot?>invoices/set_auto_contact/<?php echo $invoice['Invoice']['id']; ?>/<?php echo $opposite_status;?>"><i style="color: green;" class="fa fa-clock-o"></i> AutoNotify On</a></li>
	        			<?php }else { ?>
	        				<a class="notify-invoice" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" href="<?php echo $this->webroot?>invoices/set_auto_contact/<?php echo $invoice['Invoice']['id']; ?>/<?php echo $opposite_status;?>"><i style="color: red;" class="fa fa-clock-o"></i> AutoNotify Off</a></li>
	        			<?php } 
	        		}	
	        		?>
	        </td>
	        
	        <td>
	        	<?php 

	        		$secret_phrase = $settings['application.secret_phrase_sha1_hash'];
					$url = Router::url('/', true).'invoices/pay/'.$invoice['Invoice']['id'].'/'.sha1($invoice['Invoice']['id'].$secret_phrase);
	        	?>
	        	<a target="_blank" class="invoice-link" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" data-sends="<?php echo $invoice['Invoice']['sends']; ?>" title="Copy payment link" href="<?php echo $url?>"><i class="fa fa-link"></i> Copy Link</a>
	        	<br>
	        	<a title="Payment history" href="<?php echo $this->webroot?>payments/view_details/<?php echo $invoice['Invoice']['id']; ?>"><i class="fa fa-history"></i> Payments</a>
	        	<br>
	        	<?php if($invoice['Invoice']['paid']==0){?>
	        	<a class="pay-partial" data-invoice="<?php echo $invoice['Invoice']['id']; ?>"  title="Mark Invoice Partiallly Paid" href="<?php echo $this->webroot?>invoices/pay_partial/<?php echo $invoice['Invoice']['id'];?>"><i style="color:orange;" class="fa fa-check"></i> Pay Partial</a>
	        	<br>
	        	<a class="mark-as-paid" data-invoice="<?php echo $invoice['Invoice']['id']; ?>"  title="Mark Invoice Paid" href="<?php echo $this->webroot?>invoices/mark_paid/<?php echo $invoice['Invoice']['id'];?>"><i style="color:green;" class="fa fa-check"></i> Mark Paid</a>
	        	<br>
	        	<?php }?>

	        	<a class="delete-invoice" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" data-sends="<?php echo $invoice['Invoice']['sends']; ?>" title="Delete Invoice" href="<?php echo $this->webroot?>invoices/delete/<?php echo $invoice['Invoice']['id'];?>"><i style="color:red;" class="fa fa-minus-circle"></i> Delete</a>
	        	<br>
	        	<?php $pdf_path = DS . 'files' . DS . 'pdf_invoices' . DS .'STS'./*$invoice['Client']['org_alias'].*/$invoice['Invoice']['id'].'.pdf'; ?>
	        	<a target="_blank" href="<?php echo $pdf_path?>"><i style="color: red;" class="fa fa-file-pdf-o"></i> View PDF</a>

	        	
	        </td>    
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
		$('.mark-as-paid').click(function(event) {
			event.preventDefault();
			var id = $(this).attr("data-invoice");
			var mark = confirm("Do you want to manually mark Invoice #" + id + " as paid?");
			if(mark) {
				window.location = "<?php echo $this->webroot.'invoices/mark_paid/' ?>" + id;
			}
		});

		$('.delete-invoice').click(function(event) {
			event.preventDefault();
			var id = $(this).attr("data-invoice");
			var sends = $(this).attr("data-sends");
			var mark = confirm("Are you sure you want to delete Invoice #" + id + "?");
			if(mark) {
				if(sends > 0) {
					var checkId = prompt("Please enter the ID of the Invoice you want to delete","");
					if(checkId == id) {
						window.location = "<?php echo $this->webroot.'invoices/delete/' ?>" + id;
					} else if(checkId != null) {
						alert("Invoice ID incorrect. Please ensure you enter the correct Invoice ID.");
					}
				} else {
					window.location = "<?php echo $this->webroot.'invoices/delete/' ?>" + id;
				}
			}
		});

		$('.remind-invoice').click(function(event) {
			event.preventDefault();
			var id = $(this).attr("data-invoice");
			var reminderlevel = $(this).attr("reminder-level");
			var sends = $(this).attr("data-sends");
			var mark = confirm("Are you sure you want to send notification about Invoice #" + id + "?");
			if(mark) {
				if(sends > 0) {
					var checkId = prompt("Please enter the ID of the Invoice you want to send","");
					if(checkId == id) {
						window.location = "<?php echo $this->webroot.'invoices/send/' ?>" + id + "/" +reminderlevel;
					} else if(checkId != null) {
						alert("Invoice ID incorrect. Please ensure you enter the correct Invoice ID.");
					}
				} else {
					window.location = "<?php echo $this->webroot.'invoices/send/' ?>" + id + "/" +reminderlevel;
				}
			}
		});
	});
</script>
<?php unset($invoice); ?>
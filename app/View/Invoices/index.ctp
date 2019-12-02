<?php 
	echo $this->Html->script('Drophelp/index');
?>

<div class="sts-intro admin-intro">
	<h1>Invoices</h1>
</div>
       
<div id="client-details">	
	<h2>Client</h2>
	<ul>
	<?php  
		echo "<li>".$client['Client']['id'].", "
			.$client['Client']['firstname']." "
		.$client['Client']['lastname']."</li><li>"
		.substr($client['Client']['email'],0,50)."</li><li>".
		$client['Client']['organisation']."</li>";
	?>
	</ul>
</div>

<div id="menu" class="admin-margin">
<?php echo $this->Html->link('Raise New Invoice', array('controller' => 'invoices', 'action' => 'add',$client['Client']['id']), array('class'=>'dark-btn')); ?>
</div>
<div class="admin-table">
	<table>
	    <tr>
	        <th>ID</th>
	        <th>Invoice Date</th>
			<th>Total</th>
	        <th>Credit</th>
	        <th>Balance</th>
	        <th>Paid?</th>
			<th>Viewed Email?</th>
			<th>Notifications</th>
			<th>Notify</th>
			<th>Actions</th>
	    </tr>
	
	    <?php   
	    $sum_totals=0;
	    $sum_credits=0;
	    $sum_balances=0;
	    foreach ($invoices as $invoice)
		{?>
	    <tr>
	        <td>
	        	<?php echo $invoice['Invoice']['id']; ?>
	        </td>
	        <td>
	        	<?php echo $invoice['Invoice']['created']; ?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['total'], 2);
	        		$sum_totals += $invoice['Invoice']['total']; 
	        	 ?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['credit'], 2); 
	        		$sum_credits += $invoice['Invoice']['credit'];
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['total'] - $invoice['Invoice']['credit'] , 2); 
	        		
	        	?>
	        </td>
	        <td>
	        	<?php if($invoice['Invoice']['paid']==1) { ?>
	        		<i style="color:green;" class="fa fa-check"></i>
	        	<?php }else{ ?>	
	        		<i style="color:red;" class="fa fa-times"></i>
        		<?php } ?>	
	        </td>
	                
	        
	        <td title="Last viewed: <?php echo $invoice['Invoice']['viewed_email_timestamp'];	?>" >
	        	<?php if($invoice['Invoice']['viewed_email']==1) { ?>
	        		<i style="color:green;" class="fa fa-check"></i><?php echo $invoice['Invoice']['viewed_email_timestamp']; ?>
	        	<?php }else{ ?>	
	        		<i style="" class="fa fa-times"></i>
        		<?php } ?>
	        </td>
	        <td<?php if($invoice['Invoice']['alert'] && !$invoice['Invoice']['paid']) { echo ' class="invoice-alert"'; } ?>>
	        	<?php if($invoice['Invoice']['sends'] > 0){
	        		echo $invoice['Invoice']['sends'];
        		?> Sends <br>
        		<?php }?>
	        	<a href="<?php echo $this->webroot;?>responses/index/<?php echo $invoice['Invoice']['id'];?>">
	        	<?php
	        	 if(isset($invoice['Invoice']['last_send_type'])) {
	        	 	echo '('.$email_types[$invoice['Invoice']['last_send_type']].') ';
	        	 }
	        	 echo strtotime($invoice['Invoice']['sent']) > 0 ? date("g:i a, j/n/Y",strtotime($invoice['Invoice']['sent'])) : "Never";
	        	 ?>
	        	</a>
	        </td>
	        
	        <td>
	        	<?php if($invoice['Invoice']['paid']==0){?>
	        		<a class="remind-invoice" reminder-level="1" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Invoice" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/1"><i style="color:green;" class="fa fa-file-text"></i> Invoice</a></li><br />
	        		<a class="remind-invoice" reminder-level="2" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Reminder" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/2"><i style="color: orange;" class="fa fa-file-text"></i> Remind &nbsp;</a></li><br>
	        		<a class="remind-invoice" reminder-level="6" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Service Suspension" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/6"><i style="color: orange;" class="fa fa-file-text"></i> Suspend &nbsp;</a></li><br>
	        		<a class="remind-invoice" reminder-level="3" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Send Final Demand" href="<?php echo $this->webroot?>invoices/send/<?php echo $invoice['Invoice']['id']; ?>/3"><i style="color: red;" class="fa fa-file-text"></i> Final &nbsp;</a></li>
				<?php }?>
	        	
	        	
	        </td>
	        <td>
	        	<?php 

	        		$secret_phrase = $settings['application.secret_phrase_sha1_hash'];
					$url = Router::url('/', true).'invoices/pay/'.$invoice['Invoice']['id'].'/'.sha1($invoice['Invoice']['id'].$secret_phrase);
	        	?>
	        	<a target="_blank" class="invoice-link copy-payment-link" data-invoice-url="<?php echo $url?>" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" data-sends="<?php echo $invoice['Invoice']['sends']; ?>" title="Copy payment link" href="#<?php //echo $url?>"><i class="fa fa-link"></i> Copy Link</a>
	        	<br>
	        	<a title="Payment history" href="<?php echo $this->webroot?>payments/view_details/<?php echo $invoice['Invoice']['id']; ?>"><i class="fa fa-history"></i> Payments</a>
	        	<br>
		    	
		    	<?php if($invoice['Invoice']['paid']==0){?>
		    	<a class="mark-as-paid" data-invoice="<?php echo $invoice['Invoice']['id']; ?>" title="Mark paid" href="<?php echo $this->webroot?>invoices/mark_paid/<?php echo $invoice['Invoice']['id']; ?>"><i style="color:green;" class="fa fa-check"></i> Mark Paid</a>
				<br>
			<?php }?>

				<?php $pdf_path = DS . 'files' . DS . 'pdf_invoices' . DS .'STS'./*$invoice['Client']['org_alias'].*/$invoice['Invoice']['id'].'.pdf'; ?>
	        	<a target="_blank" href="<?php echo $pdf_path?>"><i style="color: red;" class="fa fa-file-pdf-o"></i> View PDF</a>
	        </td> 
	     </tr>
	     <?php 
		 } ?>  
		    
		 <tr>
	        <td></td>
			<th>total</th>
	        <th>credit</th>
	        <th>balance</th>
	        <td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
	    </tr>
	   	 <tr>
	        <td></td>
			<td class="decimal">
	        	<?php 
	        		echo number_format($sum_totals, 2); 
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($sum_credits, 2); 
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        	    $sum_balances = $sum_totals - $sum_credits;
	        		echo number_format($sum_balances, 2); 
	        	?>
	        </td>
	        <td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
	    </tr>  
	</table>	
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

		$('.copy-payment-link').click(function(event) {
			event.preventDefault();
			var id = $(this).attr("data-invoice");
			var url = $(this).attr("data-invoice-url");
			var mark = window.prompt("Copy this payment link to your clipboard, and open in a PRIVATE SESSION (in chrome CMD+SHIFT+N)", url);
			if(mark) {
				//window.location = url;
			}
		});
	});
</script>

<?php unset($invoice); ?>
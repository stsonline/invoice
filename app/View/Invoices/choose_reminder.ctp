<div class="sts-intro admin-intro">
	<h1>Send Reminder</h1>
</div>

<div id="client-details">	
	<h2>Client</h2>
	<ul>
	<?php  
		echo "<li>".$invoice['Client']['id'].", ".$invoice['Client']['firstname']." ".$invoice['Client']['lastname']."</li><li>".substr($invoice['Client']['email'],0,50)."</li><li>".$invoice['Client']['organisation']."</li>";
	?>
	</ul>
</div>

<div class="admin-table">
	<table>
	    <tr>
	        <th>Id</th>
			<th>total</th>
	        <th>credit</th>
	        <th>balance</th>
	        <th>paid</th>
			<th>sends</th>
			<th>viewed_email</th>
			<th>view invoice pdf</th>
			<th>email</th>
			<th>Payment Details</th>
	    </tr>
	
	    <tr>
	        <td>
	        	<?php echo $invoice['Invoice']['id']; ?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['total'], 2);
	
	        	 ?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['credit'], 2); 
	
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['total'] - $invoice['Invoice']['credit'] , 2); 
	        		
	        	?>
	        </td>
	        <td>
	        	<?php echo $invoice['Invoice']['paid']; ?>
	        </td>
	                
	        <td <?php if($invoice['Invoice']['sends']>2) 
	        			echo ' class="final-reminder" ';?> title="Last sent:  <?php echo $invoice['Invoice']['sent'];  ?>">
	        	<?php echo $this->Html->link($invoice['Invoice']['sends'], array('controller' => 'responses', 'action' => 'index', $invoice['Invoice']['id']));?>
	        </td>
	        <td title="Last viewed: <?php echo $invoice['Invoice']['viewed_email_timestamp'];	?>" >
	        	<?php echo $invoice['Invoice']['viewed_email'];	?>
	        </td>
	        <td>
	        	<?php
	        		$pdf_path = DS . 'files' . DS . 'pdf_invoices' . DS .'STS'./*$invoice['Client']['org_alias'].*/$invoice['Invoice']['id'].'.pdf';
	        		echo $this->Html->link($invoice['Invoice']['pdf'], $pdf_path);
	        	?>
	        </td>
	        <td>
	        	<?php
	        		if($invoice['Invoice']['paid']==0)
	        		{ ?>
	        			<ul>
	        			<li class="reminder-choice"> 
	        			<?php 
	        				echo $this->Html->link('Send Invoice', array('controller' => 'invoices', 'action' => 'send', $invoice['Invoice']['id'],1));
	        			?>
	        			</li>
	        			<li class="reminder-choice">
	        			<?php
	        				echo $this->Html->link('Send Reminder', array('controller' => 'invoices', 'action' => 'send', $invoice['Invoice']['id'],2));
	        			?>
						</li>
						<li class="reminder-choice">
	        			<?php 
	        				echo $this->Html->link('Send Service Suspension', array('controller' => 'invoices', 'action' => 'send', $invoice['Invoice']['id'],6));
	        			?>
						</li>
	        			<li class="reminder-choice">
	        			<?php 
	        				echo $this->Html->link('Send Final Demand', array('controller' => 'invoices', 'action' => 'send', $invoice['Invoice']['id'],3));
	        			?>
						</li>
	        			</ul>
	        		<?php 
	        		} 
	        		?>
	        </td>
	
	     </tr>
	 
	</table>
	
</div>
<?php unset($invoice); ?>
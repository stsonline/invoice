<?php 

	echo $this->Html->script('DataTable/invoice_datatable');  //webroot/js/DataTable/datatable.js
	
?>
  
<div class="sts-intro admin-intro">
	<h1>Invoices</h1>
</div> 

<div class="admin-table">
	<table>
	<thead>
	    <tr>
	        <th>Id</th>
	        <th>Client Id</th>
	        <th>Name</th>
			<th>total</th>
	        <th>credit</th>
	        <th>balance</th>
	        <th>paid</th>
	        <th>last sent</th>
			<th>sends</th>
			<th>pdf</th>
			<th>Methods</th>
	    </tr>
	</thead>
	<tbody>
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
	        	<?php echo $invoice['Invoice']['client_id']; ?>
	        </td>
	        <td>
	        	<?php echo $invoice['Client']['firstname']." ".$invoice['Client']['lastname'];?>
	        </td>       
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['total'], 2, '.', '');
	        		$sum_totals += $invoice['Invoice']['total']; 
	        	 ?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['credit'], 2, '.', ''); 
	        		$sum_credits += $invoice['Invoice']['credit'];
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($invoice['Invoice']['total'] - $invoice['Invoice']['credit'] , 2, '.', '');       		
	        	?>
	        </td>
	        <td>
	        	<?php echo $invoice['Invoice']['paid']; ?>
	        </td>
	        <td>
	        	<?php if($invoice['Invoice']['sends'] > 0)
	        			echo  date('d-n-y',strtotime($invoice['Invoice']['sent'])); 
	        	?>
	        </td>
	        <td>
	        	<?php  
	        	echo  $invoice['Invoice']['sends'];
	        	?>
	        </td>
	        <td>
	        	<?php
	        		$pdf_path = DS . 'files' . DS . 'pdf_invoices' . DS .'STS'./*$invoice['Client']['org_alias'].*/$invoice['Invoice']['id'].'.pdf';
	        		echo  $invoice['Invoice']['pdf'];
	        	?>
	        </td>
	        <td>
	        	<?php
	        	if($invoice['Invoice']['allow_cc'] == '1' && $invoice['Invoice']['allow_paypal'] == '1' && $invoice['Invoice']['allow_bacs'] == '1')
	        	{
	        		echo '*';
	        	}
	        	else
	        	{
	        		if($invoice['Invoice']['allow_cc'] == '1') { echo 'Credit Card<br/>'; }
	        		if($invoice['Invoice']['allow_paypal'] == '1') { echo 'PayPal<br/>'; }
	        		if($invoice['Invoice']['allow_bacs'] == '1') { echo 'BACS<br/>'; }
	        	}
	        	?>
	        </td>
	     </tr>
	     <?php 
		 } ?>  
		  </tbody>
		  <tfoot>   
		 <tr>
	        <td></td>
	        <td></td>
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
	        <td></td>
	        <td></td>
			<td class="decimal">
	        	<?php 
	        		echo number_format($sum_totals, 2, '.', ','); 
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        		echo number_format($sum_credits, 2, '.', ','); 
	        	?>
	        </td>
	        <td class="decimal">
	        	<?php 
	        	    $sum_balances = $sum_totals - $sum_credits;
	        		echo number_format($sum_balances, 2, '.', ','); 
	        	?>
	        </td>
	        <td></td>
	        <td></td>
			<td></td>
			<td></td>
			<td></td>
	    </tr> 
	    </tfoot>
	</table>
</div>	
	
<script>
var WEBROOT = "<?php echo $this->webroot?>";
</script>
<?php unset($invoice); ?>


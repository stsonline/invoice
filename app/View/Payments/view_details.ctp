
<div id="page">
       
<h1>Payment Details</h1>



<h2>Payments on Invoice <?php if(isset($payment['Payment']['invoice_id'])){echo $payment['Payment']['invoice_id'];}?></h2>

<table>
    <tr>
        <th>Id</th>
		<th class="credit">Amount</th>
		<th>Type</th>
        <th>Date</th>
        <th>Status</th>
        <th>Processor Response</th>
    </tr>
	<?php foreach($allpayments as $payment){?>
    <tr>
        <td>
        	<?php echo $payment['Payment']['id']; ?>
        </td>
        <td class="decimal">
        	<?php echo $payment['Payment']['amount']; ?>
        </td>
        <td>
        	<?php echo $payment['Payment']['type']; ?>
        </td>
        <td>
        	<?php echo $payment['Payment']['date']; ?>
        </td>
        
        <td>
        	<?php echo $payment['Payment']['status']; ?>
        </td>
        
         <td>
        	<p style="line-height: 100%; padding: 0; margin: 0; max-width: 1000px; overflow: hidden;"><?php echo $payment['Payment']['processor_response']; ?></p>
        </td>
     </tr>
     <?php }?>
  
</table>
	
	<pre><?php //print_r($allpayments)?></pre>
	
</div>
<?php unset($payment); ?>
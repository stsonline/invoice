<?php 
	//echo $this->Html->css('paginator');
		
	//echo $this->Html->css('application');
	
	echo $this->Html->script('DataTable/invoice_datatable');  //webroot/js/DataTable/datatable.js
	
	//echo $this->Html->css("http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css");
	
	echo $this->Html->css('tablehighlight');
	echo $this->Html->css("TableTools");
?>

<h1 class="invoice-history-title"><?php echo $project['Project']['name']; ?></h1>
<p><?php echo $project['Project']['description']; ?></p>
<table id="invoice-history">
	<thead>
		<tr>
			<th>Invoice No.</th>
			<th>Invoice PDF</th>
			<th>Invoice Total</th>
			<th>Balance Remaining</th>
			<th>Issue Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($project['Invoice'] as $invoice) { ?>
		<tr>
			<td>#<?php echo $invoice['id']; ?></td>
			<td><?php echo '<a href="'.$this->base.'/files/pdf_invoices/STS'.$invoice['id'].'.pdf" target="_blank">STS'.$invoice['id'].'.pdf</a>'; ?></td>
			<td><?php echo $currencies[$project['Client']['default_display_currency']]['symbol'].$invoice['total']; ?></td>
			<td><?php $balance = number_format($invoice['total'] - $invoice['credit'],2);echo $currencies[$project['Client']['default_display_currency']]['symbol'].$balance.'<span class="float-right">'.$invoice['pay_link'].'</span>'; ?></td>
			<td><?php echo date("jS M, Y",strtotime($invoice['created'])); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script>
var WEBROOT = "<?php echo $this->webroot?>";
$(document).ready(function() {
	$('#invoice-history').dataTable();
});
</script>
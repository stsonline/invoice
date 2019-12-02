<?php 
	//echo $this->Html->css('paginator');
		
	//echo $this->Html->css('application');
	
	echo $this->Html->script('DataTable/invoice_datatable');  //webroot/js/DataTable/datatable.js
	
	//echo $this->Html->css("http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css");
	
	echo $this->Html->css('tablehighlight');
	echo $this->Html->css("TableTools");
	echo $this->Html->css("invoices");
?>

<h1 class="invoice-history-title">Invoice History</h1>
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
		<?php foreach($invoices as $invoice) { ?>
		<tr>
			<td class="invoice-no">#<?php echo $invoice['Invoice']['id']; ?></td>
			<td class="invoice-pdf"><?php echo '<a href="'.$this->base.'/files/pdf_invoices/STS'.$invoice['Invoice']['id'].'.pdf" target="_blank">STS'.$invoice['Invoice']['id'].'.pdf</a>'; ?></td>
			<td class="invoice-total"><?php echo $currencies[$invoice['Client']['default_display_currency']]['symbol'].$invoice['Invoice']['total']; ?></td>
			<td><?php $balance = number_format($invoice['Invoice']['total'] - $invoice['Invoice']['credit'],2);echo $currencies[$invoice['Client']['default_display_currency']]['symbol'].$balance.'<span class="float-right">'.$invoice['Invoice']['pay_link'].'</span>'; ?></td>
			<td class="invoice-date"><?php echo date("jS M, Y",strtotime($invoice['Invoice']['created'])); ?></td>
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
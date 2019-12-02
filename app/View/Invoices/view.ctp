<div id="page">

	<div id="invoice-summary">
		<h1>Invoice No.<?php echo $invoice['Invoice']['id']?></h1>

		<h2>Total amount , &pound;<?php echo $invoice['Invoice']['total']?></h2>

		<h2>Client</h2>
		<p>
			<?php  
				echo $invoice['Client']['id'].", ".$invoice['Client']['firstname']." ".$invoice['Client']['lastname'].", "
					.$invoice['Client']['email'].", ".$invoice['Client']['organisation'];
			?>
		</p>
	</div>
	
	<div id="pdf-link">
		<?php
			$pdf_path = DS . 'files' . DS . 'pdf_invoices' . DS .'STS'.$invoice['Invoice']['id'].'.pdf';
			echo $this->Html->link('Display Invoice', $pdf_path);
		?>
	</div>
		
</div>
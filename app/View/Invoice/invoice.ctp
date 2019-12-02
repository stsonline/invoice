<table>
	<tr>
		<td><h1 style="text-align: left; background-color: #002BA1; color: #FFFFFF; margin: 0 10px; font-size: 20px; font-family:arial,sans-serif;">&nbsp;&nbsp;STS Commercial Ltd</h1></td>
		<td><h2 style="text-align: right; background-color: #002BA1; color: #FFFFFF; margin: 0 10px; font-size: 20px; font-family: 'times new roman',serif;">INVOICE&nbsp;&nbsp;</h2></td>
	</tr>
</table>
<table style="text-align: left; font-family:arial,sans-serif;">
	<tr>
		<td>8a Dunraven Place</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Bridgend</td>
		<td></td>
		<td style="text-align: right; border-right: 1px solid black;">INVOICE NUMBER</td>
		<td><?php echo 'STS'.$invoice['Invoice']['id']; ?></td>
	</tr>
	<tr>
		<td>M Glam</td>
		<td></td>
		<td style="text-align: right; border-right: 1px solid black;">INVOICE DATE</td>
		<td><?php echo date("d/m/Y",strtotime($invoice['Invoice']['schedule_for'])); ?></td>
	</tr>
	<tr>
		<td>CF31 1JD</td>
		<td></td>
		<td style="text-align: right; border-right: 1px solid black;">INVOICE PERIOD</td>
		<td><?php echo $invoice['Invoice']['period']; ?></td>
	</tr>
	<tr>
		<td><!--V.A.T No: GB174200244--></td>

		<?php 
		//commercials vat number
		//<td>V.A.T No: GB174200244</td>
		//<td>V.A.T No: GB231755320</td>
		?>
		<td></td>
		<td style="text-align: right; border-right: 1px solid black;">TERMS</td>
		<td>Net <?php echo $invoice['Invoice']['terms']; ?></td>
	</tr>
</table>
<p style="text-align: left; font-family:arial,sans-serif;"><span style="font-weight: bold; font-size: 8px;">SUPPLIED TO:</span>
<br/><?php echo $client['organisation']; ?></p>
<table>
	<tr>
		<td>V.A.T</td>
		<td></td>
		<td style="border: 1px solid black; text-align: center;"><?php echo $client['country'] == 'GB' ? number_format($settings['system.vat'] * 100,2) : number_format(0.00,2); ?>&#37;</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
</table>
<table style="border-collapse: collapse; font-family:arial,sans-serif; font-size: 10px;">
	<thead>
		<tr style="background-color: #DDD; font-weight: bold; font-size: 8px;">
			<td style="text-align: center; padding: 5px; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black;" colspan="3">DATE</td>
			<td style="text-align: center; padding: 5px; border-top: 1px solid black; border-bottom: 1px solid black;" colspan="3">COMPLETED</td>
			<td style="text-align: center; padding: 5px; border-top: 1px solid black; border-bottom: 1px solid black;" colspan="8">DESCRIPTION</td>
			<td style="text-align: center; padding: 5px; border-top: 1px solid black; border-bottom: 1px solid black;" colspan="3">SUPPORT LEVEL</td>
			<td style="text-align: center; padding: 5px; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black;" colspan="3">AMOUNT</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach($invoice['LineItem'] as $lineitem) { ?>
		<tr style="border-left: 1px solid black; border-right: 1px solid black;">
			<td style="border-right: 1px solid black; text-align: center; border-left: 1px solid black; padding: 5px;" colspan="3"><?php echo date("d/m/Y",strtotime($lineitem['date'])); ?></td>
			<td style="border-right: 1px solid black; text-align: center;" colspan="3"><?php echo $lineitem['completed']; ?></td>
			<td style="border-right: 1px solid black;" colspan="8"><?php echo $lineitem['desc']; ?></td>
			<td style="border-right: 1px solid black; text-align: center;" colspan="3"><?php echo $lineitem['support_level']; ?></td>
			<td style="border-right: 1px solid black; text-align: right;" colspan="3"><?php echo $currencies[$client['default_display_currency']]['symbol'].number_format(($lineitem['amount'] / $currencies[$client['default_display_currency']]['base_exchange_rate']),2); ?></td>
		</tr>
		<?php } ?>
		<tr style="border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black;">
			<td style="border-top: 1px solid black;" colspan="14"></td>
			<td style="border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black; padding: 5px;" colspan="3">SUBTOTAL</td>
			<td style="border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;padding: 5px; text-align: right;" colspan="3"><span style="float: left;"></span><?php echo $currencies[$client['default_display_currency']]['symbol'].number_format(($invoice['Invoice']['subtotal'] / $currencies[$client['default_display_currency']]['base_exchange_rate']),2); ?></td>
		</tr>
		<tr style="border-left: 1px solid black; border-right: 1px solid black;">
			<td colspan="14"></td>
			<td style="border-left: 1px solid black; border-bottom: 1px solid black; padding: 5px;" colspan="3">V.A.T</td>
			<td style="border-left: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; padding: 5px; text-align: right;" colspan="3"><span style="float: left;"></span><?php echo $currencies[$client['default_display_currency']]['symbol'].number_format(($invoice['Invoice']['vat'] / $currencies[$client['default_display_currency']]['base_exchange_rate']),2); ?></td>
		</tr>
		<tr style="border-top: 1px solid black;">
			<td colspan="17"></td>
			<td style="border-left: 1px solid black; border-right: 1px solid black; text-align: right; padding: 5px; font-size" colspan="3"><span style="float: left;"></span><?php echo $currencies[$client['default_display_currency']]['symbol'].number_format(($invoice['Invoice']['total'] / $currencies[$client['default_display_currency']]['base_exchange_rate']),2); ?></td>
		</tr>
		<tr>
			<td colspan="17"></td>
			<td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center; padding: 5px; font-size: 8px;" colspan="3">PAY THIS</td>
		</tr>
		<tr>
			<td colspan="17"></td>
			<td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center; padding: 5px; font-size: 8px;" colspan="3">AMOUNT</td>
		</tr>
	</tbody>
</table>
<div style="font-family:arial,sans-serif; position: relative; bottom: 60px; font-size: 8px;">
	<table style="width: 80%;">
		<tr style="font-weight: bold;">
			<td style="width: 40%; padding: 5px;">DIRECT ALL INQUIRIES TO:</td>
			<td style="width: 20%; padding: 5px;"></td>
			<td style="width: 40%; padding: 5px;">PLEASE MAKE BANK TRANSFERS TO:</td>
		</tr>
		<tr>
			<td style="width: 40%; padding: 5px;">Name: <?php echo $settings['invoice.inquiries_name']; ?></td>
			<td style="width: 20%; padding: 5px;"></td>
			<td style="width: 40%; padding: 5px;">SORT NUMBER: <?php echo $settings['bacs.sort_code']; ?></td>
		</tr>
		<tr>
			<td style="width: 40%; padding: 5px;">Email: <?php echo $settings['invoice.inquiries_email']; ?></td>
			<td style="width: 20%; padding: 5px;"></td>
			<td style="width: 40%; padding: 5px;">A/C NUMBER: <?php echo $settings['bacs.account_number']; ?></td>
		</tr>
	</table>
</div>
<p style="text-align: center; font-weight: bold; font-family:arial,sans-serif; font-style: italic; font-size: 10px;">THANK YOU FOR YOUR BUSINESS!</p>

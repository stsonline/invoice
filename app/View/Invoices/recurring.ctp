<!-- app/View/Invoices/add.ctp -->
<!-- adds a new invoice -->
<div class="sts-intro admin-intro">
	<h1>Recurring Invoice</h1>
</div>
<div class="invoices form">
<?php echo $this->Form->create('Invoice', array('enctype' => 'multipart/form-data')); ?>
<div id="client-details">	
	<?php if(is_array($client)) { ?>
	<ul>
	<?php  
		echo "<li>".$client['Client']['id'].", ".$client['Client']['firstname']." ".$client['Client']['lastname']."</li><li>".$client['Client']['email']."</li><li>".$client['Client']['organisation']."</li>";
		echo $this->Form->input('client_id', array('type' => 'hidden','default'=>$client['Client']['id']));
	?>
	</ul>
	<?php } ?>
</div>
    <fieldset>
        <legend><?php echo __('Invoice'); ?></legend>
        <?php 
        	$projectlist = array('none' => 'No project');
        	if(is_array($client))
        	{
        		foreach($projects as $project)
        		{
        			$projectlist[$project['Project']['id']] = $project['Project']['name'];
        		}
        	}
        ?>
        <div id="recurring-invoice-fields">
        <?php
	        echo $this->Form->input('type', array('type' => 'select','label' => 'Recurrence','options' => array('0' => 'Monthly', '1' => 'Yearly'),'default' => $invoice['RecurringInvoice']['type']));
	        echo $this->Form->input('day', array('type' => 'select','label' => 'Day of Month to Invoice','options' => $days,'default' => $invoice['RecurringInvoice']['day']));
	        echo $this->Form->input('start_date', array('value' => date("d/m/Y",strtotime($invoice['RecurringInvoice']['start_date']))));
	    ?>
			<div id="monthly-fields">
				<?php echo $this->Form->input('period_monthly',array('type' => 'select','label'=>'Invoice Period','options' => array(-1 => 'Last Month', 0 => 'This Month', 1 => 'Next Month'), 'default' => $invoice['RecurringInvoice']['period'])); ?>
        	</div>
			<div id="yearly-fields" style="display: none;">
				<div id="month-of-year" class="input select">
					<label for="InvoiceMonth">Month of Year to Invoice</label>
					<select id="InvoiceMonth" name="data[Invoice][month]">
					<?php foreach($months as $key => $month) { ?>
						<option value="<?php echo $key; ?>" <?php if(isset($invoice['RecurringInvoice']['month']) && $invoice['RecurringInvoice']['month'] == $key) { ?>selected="selected"<?php } ?>><?php echo $month; ?></option>
					<?php } ?>
					</select>
				</div>
			<?php echo $this->Form->input('period_yearly',array('type' => 'select','label'=>'Invoice Period','options' => array(-1 => 'Last Year', 0 => 'This Year', 1 => 'Next Year'), 'default' => $invoice['RecurringInvoice']['period'])); ?>
			</div>
        </div>
        <?php
        	echo $this->Form->input('terms',array('type' => 'select','label'=>'Payment Terms','options' => array('7' => 'Net 7', '14' => 'Net 14'),'default' => $invoice['RecurringInvoice']['terms']));
        	echo $this->Form->input('project',array('type' => 'select','label'=>'Project','options' => $projectlist,'default' => $invoice['RecurringInvoice']['project_id']));
        ?>
      	<div id="build" class="admin-table">
			<table class="invoice-table">
				<thead>
					<tr>
						<td>Line item description</td>
						<td>Support Level</td>
						<td title="Line item amount (decimal)">Amount (<?php echo $currencies[$settings['system.base_currency']]['symbol']; ?>)</td>
						<td title="Line item amount in the client's currency. This is what they will see on their invoice.">Amount (<span id="client-currency"><?php echo $currencies[$settings['system.base_currency']]['symbol']; ?></span>)</td>
						<td title="Charge VAT on this item (at 20%)">VAT</td>
						<td></td>
					</tr>
				</thead>
				<tbody id="line-items">
				<?php 
					$i = 0;
					foreach($invoice['RecurringLineItem'] as $key => $line_item) {
					$i = $key;
				?>
					<tr id="line-item-<?php echo $key; ?>">
						<td><input id="line-item-desc-<?php echo $key; ?>" type="text" name="data[LineItem][<?php echo $key; ?>][desc]" class="line-item-desc" value="<?php echo $line_item['desc'] ?>"></td>
						<td><input id="line-item-support-<?php echo $key; ?>" type="text" name="data[LineItem][<?php echo $key; ?>][support_level]" class="line-item-support" value="<?php echo $line_item['support_level'] ?>"></td>
						<td><input id="line-item-amount-<?php echo $key; ?>" type="number" step="any" class="line-item-amount" name="data[LineItem][<?php echo $key; ?>][amount]" value="<?php echo $line_item['amount'] ?>"></td>
						<td><input id="line-item-client-amount-<?php echo $key; ?>" type="number" step="any" class="line-item-client-amount" name="data[LineItem][<?php echo $key; ?>][client_amount]" value="<?php echo $line_item['amount'] ?>"></td>
						<td><label for="line-item-tax-<?php echo $key; ?>"><input id="line-item-tax-<?php echo $key; ?>" class="charge-tax" type="checkbox" name="data[LineItem][<?php echo $key; ?>][charge_vat]" <?php if($line_item['charge_vat']) { ?>checked="checked"<?php } ?>></label></td>
						<td><a id="delete-<?php echo $key; ?>" class="delete-item dark-btn">delete</a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<a id="add-item" class="dark-btn admin-margin">Add a new item</a>
      	</div>
      	<div id="subtotal-container"><?php echo $this->Form->input('subtotal', array('label'=>'Subtotal')); ?><div id="client-subtotal" class="invoice-total"></div></div>
      	<div id="vat-container"><?php echo $this->Form->input('vat', array('label'=>'V.A.T')); ?><div id="client-vat" class="invoice-total"></div></div>
      	<div id="total-container"><?php echo $this->Form->input('total', array('label'=>'Invoice Total')); ?><div id="client-total" class="invoice-total"></div></div>
		<script>
			var lineitem_id = <?php echo count($invoice['RecurringLineItem']); ?>;
			var clientdata = <?php echo json_encode($clientdata); ?>;
			var applyTaxByDefault = false;
			var taxAmount = <?php echo $tax_amount; ?>;
			var baseCurrency = <?php echo json_encode($base_currency); ?>;
			var currencies = <?php echo json_encode($currencies); ?>
					
			$(document).ready(function() {
				$('#line-item-date-0').datepicker({dateFormat: 'dd/mm/yy'});

				$('#InvoiceStartDate').datepicker({dateFormat: 'dd/mm/yy'});
				
				$('#InvoiceScheduleFor').datepicker({dateFormat: 'dd/mm/yy'});

				updateTotalFromBaseCurrency();

				if($('#InvoiceType').val() == 1) {
					$('#monthly-fields').hide(0);
					$('#yearly-fields').show(0);
				}
				else {
					$('#monthly-fields').show(0);
					$('#yearly-fields').hide(0);
				}
				
				$('#ClientCreateUser').change(function() {
					if($(this).is(':checked')) {
						$('#new-user').show(0);
					}
					else {
						$('#new-user').hide(0);
					}
				});

				$('#InvoiceRecurring').change(function() {
					if($(this).is(':checked')) {
						$('#recurring-invoice-fields').show(0);
						$('#single-invoice-fields').hide(0);
						$('#pdf-source').hide(0);
						$('#recurring-payment').show(0);
					}
					else {
						$('#recurring-invoice-fields').hide(0);
						$('#single-invoice-fields').show(0);
						$('#pdf-source').show(0);
						$('#recurring-payment').hide(0);
					}
					handleLineItemsOnInvoiceType();
				});

				$('#InvoiceType').change(function() {
					if($(this).val() == 1) {
						$('#monthly-fields').hide(0);
						$('#yearly-fields').show(0);
					}
					else {
						$('#monthly-fields').show(0);
						$('#yearly-fields').hide(0);
					}
				});

				$('#InvoicePdfSource').change(function() {
					var source = $(this).val();

					if(source == 'upload')
					{
						$('#upload').show(0);
						$('#build').hide(0);
					}
					else
					{
						$('#upload').hide(0);
						$('#build').show(0);
					}
				});

				$('#InvoiceClientId').change(function() {
					if($(this).val() == 'new') {
						$('#new-client').show(0);
					}
					else {
						applyTaxByDefault = clientdata[$(this).val()]['tax'];

						if(applyTaxByDefault) {
							$('.charge-tax').prop('checked', true);
						}
						else {
							$('.charge-tax').prop('checked', false);
						}

						$('#new-client').hide(0);
					}
					
					updateTotalFromBaseCurrency();
				});

				$('#ClientChargeVat').change(function() {
					if($(this).is(':checked')) {
						applyTaxByDefault = true;
						$('.charge-tax').prop('checked', true);
					}
					else {
						applyTaxByDefault = false;
						$('.charge-tax').prop('checked', false);
					}

					updateTotalFromBaseCurrency();
				});

				$('#ClientDefaultDisplayCurrency').change(function() {
					var newCurrency = $(this).val();

					clientdata['new']['exchange_rate'] = currencies[newCurrency]['base_exchange_rate'];
					clientdata['new']['symbol'] = currencies[newCurrency]['symbol'];

					updateTotalFromBaseCurrency();
				});

				$('#InvoiceFreight,#InvoiceVat,#InvoiceSubtotal,#InvoiceTotal,.charge-tax').change(function() {
					updateTotalFromBaseCurrency();
				});

				$('#add-item').click(function() {
					$('#line-items').append('<tr id="line-item-' + lineitem_id + '"><td><input id="line-item-desc-' + lineitem_id + '" type="text" name="data[LineItem][' + lineitem_id + '][desc]" class="line-item-desc"></td><td><input id="line-item-support-' + lineitem_id + '" type="text" name="data[LineItem][' + lineitem_id + '][support_level]" class="line-item-support"></td><td><input id="line-item-amount-' + lineitem_id + '" class="line-item-amount" type="number" step="any"  name="data[LineItem][' + lineitem_id + '][amount]"></td><td><input id="line-item-client-amount-' + lineitem_id + '" class="line-item-client-amount" type="number" step="any"  name="data[LineItem][' + lineitem_id + '][client_amount]"></td><td><label for="line-item-tax-' + lineitem_id + '"><input id="line-item-tax-' + lineitem_id + '" type="checkbox" class="charge-tax" name="data[LineItem][' + lineitem_id + '][charge_vat]"></label></td><td><a id="delete-' + lineitem_id + '" class="delete-item dark-btn">delete</a></td></tr>');

					if(applyTaxByDefault) {
						$('#line-item-tax-' + lineitem_id).prop('checked', true);
					}
					
					$('#line-item-date-' + lineitem_id).datepicker({dateFormat: 'dd/mm/yy'});
					lineitem_id++;

					$('.delete-item').click(function() {
						var this_id = $(this).attr('id');
						this_id = this_id.split('-');

						$('#line-item-' + this_id[1]).remove();

						updateTotalFromBaseCurrency();
					});

					$('.line-item-amount,.charge-tax').unbind('change').change(function() {
						updateTotalFromBaseCurrency();
					});

					$('.line-item-client-amount').unbind('change').change(function() {
						updateTotalFromDisplayCurrency();
					});

					handleLineItemsOnInvoiceType();
				});

				$('.line-item-amount').change(function() {
					updateTotalFromBaseCurrency();
				});

				$('.line-item-client-amount').change(function() {
					updateTotalFromDisplayCurrency();
				});

			});

			function updateTotalFromBaseCurrency()
			{
				var subtotal = 0.00;
				var tax = 0.00;
				
				var client_id = $('#InvoiceClientId').val();

				if(typeof(clientdata[client_id]) !== "undefined")
				{
					var currency_symbol = clientdata[client_id]['symbol'];
					var exchange_rate = clientdata[client_id]['exchange_rate'];
				}
				else
				{
					var currency_symbol = baseCurrency['symbol'];
					var exchange_rate = baseCurrency['base_exchange_rate'];
				}
				
				$('#client-currency').html(currency_symbol);
				
				$('.line-item-amount').each(function(index) {
					var lineitem_value = $(this).val();
					if(lineitem_value == '')
					{
						lineitem_value = 0.00;
					}
					else
					{
						lineitem_value = parseFloat(lineitem_value);
					}

					subtotal = subtotal + lineitem_value;

					var this_id = $(this).attr('id');
					this_id = this_id.split('-');

					var client_amount = (lineitem_value / exchange_rate).toFixed(2);

					$('#line-item-client-amount-' + this_id[3]).val(client_amount);

					var html_id = $(this).attr('id');
					var number_id = html_id.split('-').pop();

					var applyTaxForThisItem = $('#line-item-tax-' + number_id).is(':checked');

					if(applyTaxForThisItem) {
						tax = tax + (lineitem_value * taxAmount);
					}
				});

				$('#InvoiceSubtotal').val(subtotal.toFixed(2));

				var clientSubtotal = (subtotal / exchange_rate).toFixed(2);
				
				$('#client-subtotal').html(currency_symbol + clientSubtotal.toString());

				$('#InvoiceVat').val(tax.toFixed(2));

				var clientTax = (tax / exchange_rate).toFixed(2);
				
				$('#client-vat').html(currency_symbol + clientTax.toString());
				
				total = subtotal + tax;
				
				$('#InvoiceTotal').val(total.toFixed(2));

				var clientTotal = (total / exchange_rate).toFixed(2);
				
				$('#client-total').html(currency_symbol + clientTotal.toString());
			}

			function updateTotalFromDisplayCurrency()
			{
				var subtotal = 0.00;

				var client_id = $('#InvoiceClientId').val();

				if(typeof(clientdata[client_id]) !== "undefined")
				{
					var currency_symbol = clientdata[client_id]['symbol'];
					var exchange_rate = clientdata[client_id]['exchange_rate'];
				}
				else
				{
					var currency_symbol = baseCurrency['symbol'];
					var exchange_rate = baseCurrency['base_exchange_rate'];
				}
				
				$('#client-currency').html(currency_symbol);
				
				$('.line-item-client-amount').each(function(index) {
					var lineitem_value = $(this).val();
					if(lineitem_value == '')
					{
						lineitem_value = 0.00;
					}
					else
					{
						lineitem_value = parseFloat(lineitem_value);
					}

					lineitem_value = (lineitem_value * exchange_rate).toFixed(2);

					subtotal = subtotal + lineitem_value;

					var this_id = $(this).attr('id');
					this_id = this_id.split('-');

					$('#line-item-amount-' + this_id[4]).val(lineitem_value);
				});

				updateTotalFromBaseCurrency()
			}

			function handleLineItemsOnInvoiceType() {
				if($('#InvoiceRecurring').is(':checked')) {
					$('.hide-for-recurring').hide(0);
				}
				else {
					$('.hide-for-recurring').show(0);
				}
			}
		</script>
        <p>Payment Methods allowed:</p>
        <?php 
        	echo $this->Form->input('allow_cc', array('label'=>'Credit Card','type' => 'checkbox','checked'=>'checked'));
        	echo $this->Form->input('allow_paypal', array('label'=>'PayPal','type' => 'checkbox','checked'=>'checked'));
        	echo $this->Form->input('allow_bacs', array('label'=>'Bank Transfer','type' => 'checkbox','checked'=>'checked'));
        ?>
    </fieldset>
	<?php echo $this->Form->end(array('label'=>'Submit','class'=>'dark-btn cursor-pointer')); ?>
</div>
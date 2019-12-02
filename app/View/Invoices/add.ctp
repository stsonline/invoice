<!-- app/View/Invoices/add.ctp -->
<!-- adds a new invoice -->
<div class="sts-intro admin-intro">
	<h1>New Invoice</h1>
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
	<?php } else { ?>
	<ul>
	<?php  
		echo $this->Form->input('client_id', array('type' => 'select','options'=>$clientlist,'empty' => '(Choose client)'));
	?>
	</ul>
	<?php } ?>
</div>
<?php /*if(!is_array($client)) { ?>
<fieldset id="new-client" style="display: none;">
	<legend><?php echo __('Add Client'); ?></legend>
	<div>
	<?php 
	echo $this->Form->input('Client.firstname');
	echo $this->Form->input('Client.lastname');
	echo $this->Form->input('Client.email');
	echo $this->Form->input('Client.organisation',array('label' => 'Organisation Name'));
	echo $this->Form->input('Client.org_alias',array('label' => 'Organisation Alias'));
	echo $this->Form->input('Client.default_display_currency',array('label' => 'Currency', 'type' => 'select', 'options' => $currencieslist,'default' => $settings['system.base_currency']));
	echo $this->Form->input('Client.create_user',array('label' => 'Create User Account', 'type' => 'checkbox'));
	?>
	<div id="new-user" style="display: none;">
		<div class="input password required">
			<label for="UserPassword">Password</label>
			<input id="UserPassword" type="password" name="data[User][password]" value="">
		</div>
	</div>
	<?php 
	echo $this->Form->input('Client.charge_vat');
	echo $this->Form->input('Client.address1',array('label' => 'Address'));
	echo $this->Form->input('Client.address2',array('label' => 'Address 2'));
	echo $this->Form->input('Client.city');
	echo $this->Form->input('Client.state',array('label' => 'County/State'));
	echo $this->Form->input('Client.zipcode',array('label' => 'Postcode/Zip code'));
	echo $this->Form->input('Client.country');
	echo $this->Form->input('Client.phone',array('type' => 'text', 'label' => 'Phone Number'));
	?>
	</div>
</fieldset>
<?php }*/?>
    <fieldset>
        <legend><?php echo __('Add Invoice'); ?></legend>
        <div id="type">
		<?php 
			echo $this->Form->input('recurring',array('type'=>'checkbox','label'=>'Create recurring invoice'));
		?>
		</div>
        <?php 
        	$projectlist = array('none' => 'No project');
        	if(is_array($client))
        	{
        		foreach($projects as $project)
        		{
        			$projectlist[$project['Project']['id']] = $project['Project']['name'];
        		}
        	}
        	
        	echo $this->Form->input('payment_id', array('type' => 'hidden','default'=>0));
        	echo $this->Form->input('paid', array('type' => 'hidden','default'=>0));
        	echo $this->Form->input('sent', array('type' => 'hidden','default'=>0));
        	echo $this->Form->input('sends', array('type' => 'hidden','default'=>0));
        	//invoice content file in pdf
        ?>
        <div id="single-invoice-fields">
        <?php
          	echo $this->Form->input('schedule_for', array('type' => 'text','label' => 'Schedule Invoice For Date'));
        	echo $this->Form->input('period', array('type' => 'text','label' => 'Invoice Period'));
        ?>
        </div>
        <div id="recurring-invoice-fields" style="display: none;">
        <?php
	        echo $this->Form->input('type', array('type' => 'select','label' => 'Recurrence','options' => array('0' => 'Monthly', '1' => 'Yearly')));
	        echo $this->Form->input('day', array('type' => 'select','label' => 'Day of Month to Invoice','options' => $days));
	        echo $this->Form->input('start_date');
        ?>
        	<div id="monthly-fields">
				<?php echo $this->Form->input('period_monthly',array('type' => 'select','label'=>'Invoice Period','options' => array(-1 => 'Last Month', 0 => 'This Month', 1 => 'Next Month'))); ?>
        	</div>
			<div id="yearly-fields" style="display: none;">
				<div id="month-of-year" class="input select">
					<label for="InvoiceMonth">Month of Year to Invoice</label>
					<select id="InvoiceMonth" name="data[Invoice][month]">
					<?php foreach($months as $key => $month) { ?>
						<option value="<?php echo $key; ?>"><?php echo $month; ?></option>
					<?php } ?>
					</select>
				</div>
			<?php echo $this->Form->input('period_yearly',array('type' => 'select','label'=>'Invoice Period','options' => array(-1 => 'Last Year', 0 => 'This Year', 1 => 'Next Year'))); ?>
			</div>
			
        </div>
        <?php
        	echo $this->Form->input('terms',array('type' => 'select','label'=>'Payment Terms','options' => array('0' => 'Net 0', '7' => 'Net 7', '14' => 'Net 14'), 'default' => '7'));
        	echo $this->Form->input('project',array('type' => 'select','label'=>'Project','options' => $projectlist));
        ?>
        <div id="pdf-source">
        <?php
        	echo $this->Form->input('pdf_source', array('type' => 'select','label' => 'Invoice Source', 'options'=>array('build' => 'New', 'upload' => 'Upload')));
        ?>
        <div id="upload" style="display: none;">
        <?php 
        	echo $this->Form->input('pdf',array('type'=>'file','label'=>'Invoice as pdf file'));
       	?>
      	</div>
      	</div>
      	<div id="build" class="admin-table">
			<table class="invoice-table">
				<thead>
					<tr>
						<td class="hide-for-recurring">Date</td>
						<td class="hide-for-recurring">Completed</td>
						<td>Line item description</td>
						<td>Support Level</td>
						<td title="Line item amount (decimal)">Amount (<?php echo $currencies[$settings['system.base_currency']]['symbol']; ?>)</td>
						<td title="Line item amount in the client's currency. This is what they will see on their invoice.">Amount (<span id="client-currency"><?php echo $currencies[$settings['system.base_currency']]['symbol']; ?></span>)</td>
						<td title="Charge VAT on this item (at 20%)">VAT</td>
						<td></td>
					</tr>
				</thead>
				<tbody id="line-items">
					<tr id="line-item-0">
						<td class="hide-for-recurring"><input id="line-item-date-0" type="text" name="data[LineItem][0][date]" class="line-item-date"></td>
						<td class="hide-for-recurring">
							<select id="line-item-completed-0" name="data[LineItem][0][completed]" class="line-item-completed">
								<option value="YES">Yes</option>
								<option value="NO">No</option>
							</select>
						</td>
						<td><input id="line-item-desc-0" type="text" name="data[LineItem][0][desc]" class="line-item-desc"></td>
						<td><input id="line-item-support-0" type="text" name="data[LineItem][0][support_level]" class="line-item-support"></td>
						<td><input id="line-item-amount-0" type="number" step="any" class="line-item-amount" name="data[LineItem][0][amount]"></td>
						<td><input id="line-item-client-amount-0" type="number" step="any" class="line-item-client-amount" name="data[LineItem][0][client_amount]"></td>
						<td><label for="line-item-tax-0"><input id="line-item-tax-0" class="charge-tax" type="checkbox" name="data[LineItem][0][charge_vat]" value="1"></label></td>
						<td><a id="delete-0" class="delete-item dark-btn">delete</a></td>
					</tr>
				</tbody>
			</table>
			<a id="add-item" class="dark-btn admin-margin">Add a new item</a>
      	</div>
      	<div id="subtotal-container"><?php echo $this->Form->input('subtotal', array('label'=>'Subtotal')); ?><div id="client-subtotal" class="invoice-total"></div></div>
      	<div id="vat-container"><?php echo $this->Form->input('vat', array('label'=>'V.A.T')); ?><div id="client-vat" class="invoice-total"></div></div>
      	<div id="total-container"><?php echo $this->Form->input('total', array('label'=>'Invoice Total')); ?><div id="client-total" class="invoice-total"></div></div>
		<script>
			var lineitem_id = 1;
			var clientdata = <?php echo json_encode($clientdata); ?>;
			var applyTaxByDefault = false;
			var taxAmount = <?php echo $tax_amount; ?>;
			var baseCurrency = <?php echo json_encode($base_currency); ?>;
			var currencies = <?php echo json_encode($currencies); ?>
					
			$(document).ready(function() {
				$('#line-item-date-0').datepicker({dateFormat: 'dd/mm/yy'});

				$('#InvoiceStartDate').datepicker({dateFormat: 'dd/mm/yy'});
				
				$('#InvoiceScheduleFor').datepicker({dateFormat: 'dd/mm/yy'});

				//handy defaults
				$('#InvoiceScheduleFor').val($.datepicker.formatDate('dd/mm/yy', new Date()));
				$('#line-item-date-0').val($.datepicker.formatDate('dd/mm/yy', new Date()));
				var d = new Date();
			    d.setDate(1);
			    d.setMonth(d.getMonth());
				$('#InvoicePeriod').val($.datepicker.formatDate('MM yy', d));
				
				$('#ClientCreateUser').change(function() {
					if($(this).is(':checked')) {
						$('#new-user').show(0);
						$('#UserPassword').attr('required', 'true');
					}
					else {
						$('#new-user').hide(0);
						$('#UserPassword').removeAttr('required');
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
					$('#line-items').append('<tr id="line-item-' + lineitem_id + '"><td class="hide-for-recurring"><input id="line-item-date-' + lineitem_id + '" type="text" name="data[LineItem][' + lineitem_id + '][date]" class="line-item-date"></td><td class="hide-for-recurring"><select id="line-item-completed-' + lineitem_id + '" name="data[LineItem][' + lineitem_id + '][completed]" class="line-item-completed"><option value="YES">Yes</option><option value="NO">No</option></select></td><td><input id="line-item-desc-' + lineitem_id + '" type="text" name="data[LineItem][' + lineitem_id + '][desc]" class="line-item-desc"></td><td><input id="line-item-support-' + lineitem_id + '" type="text" name="data[LineItem][' + lineitem_id + '][support_level]" class="line-item-support"></td><td><input id="line-item-amount-' + lineitem_id + '" class="line-item-amount" type="number" step="any"  name="data[LineItem][' + lineitem_id + '][amount]"></td><td><input id="line-item-client-amount-' + lineitem_id + '" class="line-item-client-amount" type="number" step="any"  name="data[LineItem][' + lineitem_id + '][client_amount]"></td><td><label for="line-item-tax-' + lineitem_id + '"><input id="line-item-tax-' + lineitem_id + '" type="checkbox" class="charge-tax" name="data[LineItem][' + lineitem_id + '][charge_vat]" value="1"></label></td><td><a id="delete-' + lineitem_id + '" class="delete-item dark-btn">delete</a></td></tr>');

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

				$('#InvoiceAddForm').submit(function(event) {
					var valid = validateFormDates();
					
					if(!valid) {
						event.preventDefault();
						alert("Please check all fields are correct");
					}
				});
			});

			function validateFormDates() {
				var recurringInvoice = $('#InvoiceRecurring').is(':checked');
				var dates = [];

				if(recurringInvoice) {
					dates.push($('#InvoiceStartDate').val());
				} else {
					dates.push($('#InvoiceScheduleFor').val());
					$('.line-item-date').each(function(index) {
						dates.push($(this).val());
					});
				}

				var datesValid = true;
				
				$.each(dates, function(index, value) {

					var splitDate = value.split('/');

					if(checkDate(splitDate[0],splitDate[1],splitDate[2])==false)
					{
						datesValid = false;
					}
				});

				return datesValid;
			}

			function checkDate(d,m,y)
			{
			   try { 
			      var dt = new Date(y,m-1,d,0,0,0,0);

			      var day = dt.getDate();
			      var month = dt.getMonth() + 1; // JS Date counts months from 0, but not days or years.
			      var year  = dt.getFullYear();

			      if ( month == m && year == y && day == d )
			         return true; 
			      else
			         return false;
			   }
			   catch(e) {
			      return false;
			   }
			}
			
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
		<?php if(is_array($client)) { ?>
		<script>
		$(document).ready(function() {
			applyTaxByDefault = clientdata[$('#InvoiceClientId').val()]['tax'];
		
			if(applyTaxByDefault) {
				$('.charge-tax').prop('checked', true);
			}
		});
		</script>
		<?php } ?>
		<div id="recurring-payment" style="display: none;">
		<?php echo $this->Form->input('default_to_recurring_payment',array('label' => 'Default to Recurring Payments', 'type' => 'checkbox')); ?>
		</div>
        <p>Payment Methods allowed:</p>
        <?php 
        	echo $this->Form->input('allow_cc', array('label'=>'Credit Card','type' => 'checkbox','checked'=>'checked'));
        	echo $this->Form->input('allow_bacs', array('label'=>'Bank Transfer','type' => 'checkbox','checked'=>'checked'));
        	echo $this->Form->input('allow_paypal', array('label'=>'PayPal','type' => 'checkbox'));
        	 
        ?>
    </fieldset>
	<?php echo $this->Form->end(array('label'=>'Submit','class'=>'dark-btn cursor-pointer')); ?>
</div>
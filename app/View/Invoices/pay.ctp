<?php if(isset($invoice)){?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h2 class="title">Payment for Invoice No.<?php echo $invoice['Invoice']['id']?></h2>
					</div>	
					<div class="" id="invoice-summary">
						<!--<h2>Customer:</h2>-->
						<div class="admin-table">
							<table>
								<tr>
									<td class="strong">Company:</td>
									<td><?php echo $invoice['Client']['organisation']?></td>
								</tr>
							
								<tr>
									<td class="strong">Account contact name:</td>
									<td><?php echo $invoice['Client']['firstname']?> <?php echo $invoice['Client']['lastname']?></td>
								</tr>
								
								<tr>
									<td class="strong">Account contact email:</td>
									<td><?php echo $invoice['Client']['email']?></td>
								</tr>
								
								<tr>
									<td class="strong">Total invoice amount:</td>
									<td>
									<?php
										$rate = $currencies[$invoice['Client']['default_display_currency']]['base_exchange_rate'];
										$balance = round(((floatval($invoice['Invoice']['total']) - floatval($invoice['Invoice']['credit'])) / $rate), 2);
										$total = round(((floatval($invoice['Invoice']['total'])) / $rate), 2);
										$total = number_format((float)$total, 2, '.', '');
										$balance = number_format((float)$balance, 2, '.', '');
										echo $currencies[$invoice['Client']['default_display_currency']]['symbol'].$total;
										
										$currency_sym = $currencies[$invoice['Client']['default_display_currency']]['symbol'];
										$invoice_amount = $balance;
									?>
									</td>
									
								</tr>
								
								<tr>
									<td class="strong">Invoice balance remaining:</td>
									<td><?php echo $currency_sym.$balance?></td>
								</tr>
								
								<?php 
										$pdf_path = DS . 'files' . DS . 'pdf_invoices' . DS .'STS'.$invoice['Invoice']['id'].'.pdf';
									?>
								
								<tr>
									<td class="strong">View original invoice:</td>
									<td><a href="<?php echo $pdf_path?>"><img class="pdf-icon" src="<?php echo $this->webroot?>img/pdf.png" /></a></td>
								</tr>
							</table>
						</div>
						<div id="order-container" class="">
							
								<form data-ajax="false" autocomplete="off" novalidate="novalidate" id="order-form" action="<?php echo 'https://' . env('SERVER_NAME') . $this->webroot?>invoices/payment_process/<?php echo $invoice['Invoice']['id']?>/<?php echo sha1($invoice['Invoice']['id'].$settings['application.secret_phrase_sha1_hash'])?>" method="post">
				
								<h3 class="sts-blue sts-border">1. Billing address</h3>
									<div id="billing-summary-table">
										<table>
											<tr>
												<td class="strong">Order name:</td>
												<td><?php echo $invoice['Client']['firstname']?> <?php echo $invoice['Client']['lastname']?></td>
											</tr>
											
											<tr>
												<td class="strong">Address:</td>
												<td><?php echo $invoice['Client']['address1']?>, <?php echo $invoice['Client']['address2']?>,<?php echo $invoice['Client']['city']?>, <?php echo $invoice['Client']['state']?>, <?php echo $invoice['Client']['zipcode']?></td>
											</tr>
										
											<tr>
												<td class="strong">Country:</td>
												<td><?php echo $countries[$invoice['Client']['country']]?></td> 
											</tr>
											
										</table>
									</div>
									<div class="form-field">
										<label for="">Change billing details for this order?</label>
										<input type="checkbox" id="billing-change" name="billing-change" />
									</div>
									<script>
									$('#billing-change').change(function()
									{
										var checked = $('#billing-change').attr('checked') == 'checked' ? true : false;
										$('#billing-address').slideToggle(checked);
										$('#billing-summary-table').slideToggle(!checked);
									});
									</script>
								
								
								<div id="billing-address" class="hidden">
									
									<div class="form-field">
										<label for="">Customer Name <span class="asterisk">*</span></label>
										<input type="text" id="customer-name" name="customer-name" value="<?php echo $invoice['Client']['firstname']?> <?php echo $invoice['Client']['lastname']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">Company Name <span class="asterisk">*</span></label>
										<input type="text" id="company-name" name="company-name" value="<?php echo $invoice['Client']['organisation']?>" />
									</div>
									
									<div class="form-field">
										<label for="">Address 1 <span class="asterisk">*</span></label>
										<input type="text" id="address1" name="address1" value="<?php echo $invoice['Client']['address1']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">Address 2</label>
										<input type="text" id="address2" name="address2" value="<?php echo $invoice['Client']['address2']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">City <span class="asterisk">*</span></label>
										<input type="text" id="city" name="city" value="<?php echo $invoice['Client']['city']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">County/State <span class="asterisk">*</span></label>
										<input type="text" id="state" name="state" value="<?php echo $invoice['Client']['state']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">Post/Zip code <span class="asterisk">*</span></label>
										<input type="text" id="zipcode" name="zipcode" value="<?php echo $invoice['Client']['zipcode']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">Country <span class="asterisk">*</span></label>
										<select id="country" name="country">
											<option selected="selected" value=""></option>
											<?php 
											foreach($countries as $code=>$country)
											{
											?>
											<option value="<?php echo $code?>" <?php if($code==$invoice['Client']['country']){echo 'selected="selected"';}?> ><?php echo $country?></option>
											<?php }?>
										</select>
									
									</div>
									
									<div class="form-field">
										<label for="">Telephone </label>
										<input type="text" id="phone" name="phone" value="<?php echo $invoice['Client']['phone']?>"/>
									</div>
								</div>
								
								<?php 
								if(!isset($user))
								{
								//non logged in, guest checkout
								?>
								
								<div id="payment-details">
									<h3 class="sts-blue sts-border">2. Payment details</h3>
									
									<div class="form-field">
										<label for="">Payment Method <span class="asterisk">*</span></label>
										<select id="payment-method" name="payment-method">
											<?php if($invoice['Invoice']['allow_cc'] == 1) { ?><option value="card">Credit/Debit Card</option><?php } ?>
											<?php if($invoice['Invoice']['allow_paypal'] == 1) { ?><option value="paypal">PayPal</option><?php } ?>
											<?php if($invoice['Invoice']['allow_bacs'] == 1) { ?><option value="bank">Bank Transfer</option><?php } ?>
										</select>
									</div>
									<?php if($invoice['Invoice']['recurring_invoice_id'] != null && $settings['invoice.allow_recurring_payments'] == 'true') { ?>
									<div class="form-field">
										<label for="recurring_payment">Set up recurring payment for this regular invoice (payments will be taken automatically for each invoice)</label>
										<input type="checkbox" value="1" id="recurring_payment" name="recurring_payment" <?php if($invoice['Invoice']['default_to_recurring_payment']) { ?>checked="checked"<?php }?>>
									</div>
									<?php } ?>
									<?php if($settings['invoice.payment_processor']=='_worldpay') { ?>
									<div class="credit-card method-block">
									<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=1044322"></script>
									<p>Payments will be taken in GBP(&pound;).</p>
									</div>
									<?php } ?>
									<?php $hosted_page = $settings['invoice.payment_processor']=='_barclaycard' && $settings['barclaycard.method'] == 'hosted' ? true : false;
									$hosted_page = $settings['invoice.payment_processor']=='_worldpay' ? true : $hosted_page;
									?>
									<?php if($invoice['Invoice']['allow_cc'] == 1 && !$hosted_page) { ?><div class="credit-card method-block">
									
									<div class="form-field"><label for="card-type">Card Type <span class="asterisk">*</span></label> 
										<select id="card-type" name="card-type">
											<option selected="selected" value="">--- Please select ---</option>
											<option value="Visa">Visa</option>
											<option value="Master Card Credit">Master Card Credit</option>
											<option value="Debit Master Card">Debit Master Card</option>
											<option value="American Express">American Express</option>
											<option value="JCB">JCB</option>
											<option value="Maestro">Maestro</option>
											<option value="">Other</option>
										</select>
									</div>
									
									<div class="form-field">
										<label for="">Name on card <span class="asterisk">*</span></label>
										<input type="text" id="card-name" name="card-name" />
									</div>
									
									<div class="form-field">
										<label for="">Card number<span class="asterisk">*</span></label>
										<input type="text" id="card-number" name="card-number" />
									</div>
									
									<div class="form-field small-field">
										<label for="card-start-month">Start Date (if known)</label> 
										<select id="card-start-month" name="card-start-month">
											<option selected="selected" value=""></option>
											<?php 
												for($i=1;$i<=12;$i++){
												$i = $i< 10 ? '0'.$i: $i;
											?>
											<option value="<?php echo $i?>"><?php echo $i?></option>
											<?php }?>
										</select>
										
										<select id="card-start-year" name="card-start-year">
											<option selected="selected" value=""></option>
											<?php 
												$year = date('Y');
												for($i=$year-8;$i<=$year;$i++){
												$i = $i< 10 ? '0'.$i: $i;
											?>
											<option value="<?php echo $i?>"><?php echo $i?></option>
											<?php }?>
										</select>
									</div>
									
									<div class="form-field small-field">
										<label for="card-exp-month">Expiry Date <span class="asterisk">*</span></label> 
										<select id="card-exp-month" name="card-exp-month">
											<option selected="selected" value=""></option>
											<?php 
												for($i=1;$i<=12;$i++){
												$i = $i< 10 ? '0'.$i: $i;
											?>
											<option value="<?php echo $i?>"><?php echo $i?></option>
											<?php }?>
										</select>
										
										<select id="card-exp-year" name="card-exp-year">
											<option selected="selected" value=""></option>
											<?php 
												$year = date('Y');
												for($i=$year;$i<=$year+10;$i++){
												//$i = substr($i, 1);
											?>
											<option value="<?php echo $i?>"><?php echo $i?></option>
											<?php }?>
										</select>
									</div>
									
									<div class="form-field">
										<label for="">Security Code <span class="asterisk">*</span></label>
										<input type="text" id="cvv-code" name="cvv-code" />
									</div>
									
									<div class="form-field">
										<label for="">Issue number (if known)</label>
										<input type="text" id="card-issue" name="card-issue" />
									</div>
									</div><?php }?>
									<?php if($invoice['Invoice']['allow_paypal'] == 1) { ?><div class="paypal method-block">
										<div class="form-field">
											<img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" border="0" alt="PayPal Logo" style="float: right;margin-top: 10px;">
											<p>You will be redirected to PayPal to complete this payment</p>
										</div>
									</div><?php } ?>
									<?php if($invoice['Invoice']['allow_bacs'] == 1) { ?><div class="bank method-block">
										<div class="form-field">
											<p>You have chosen to pay by BACS transfer.</p>
											<p><span class="strong">STS Invoices Account Details:</span><br/>
											Sort Code: <?php echo $sort_code ?><br/>
											Account Number: <?php echo $account_number ?></p>
											<p>Please click below to complete this form in order to confirm that you have completed the transfer and mark this invoice as paid.</p>
										</div>
									</div><?php } ?>
									
									<div class="form-field">
										<label for="">Send receipt to email <span class="asterisk">*</span></label>
										<input type="text" id="email" name="email" value="<?php echo $invoice['Client']['email']?>"/>
									</div>
									
									<div class="form-field">
										<label for="">Store details and create account?</label>
										<input type="checkbox" id="account-create" name="account-create" />
									</div>
									<script>
									$('#account-create').change(function()
									{
										var checked = $('#account-create').attr('checked') == 'checked' ? true : false;
										$('#create-account-section').slideToggle(checked);
										if(checked)
										{
											$('#account-email').val($('#email').val());
										}
										
									});
									</script>
									
								</div>
								<div id="create-account-section" class="hidden margin-top-30">
									<h3 class="sts-blue sts-border">3. Create account</h3>
							
									<div class="form-field">
										<label for="">Email address <span class="asterisk">*</span></label>
										<input type="text" id="account-email" name="account-email" />
									</div>
									<div class="form-field">
										<label for="">Password <span class="asterisk">*</span></label>
										<input type="password" id="account-password1" name="account-password1" />
									</div>
									
									<div class="form-field">
										<label for="">Password confirm <span class="asterisk">*</span></label>
										<input type="password" id="account-password2" name="account-password2" />
									</div>
								</div>
								
								<?php }else{
								//logged in user checkout
									?>
								
								<div id="payment-details" class="margin-top-30">
									<h3 class="sts-blue sts-border">2. Payment details</h3>
												
									<div class="form-field">
										<label for="">Payment amount<span class="asterisk">*</span></label>
										<input checked="checked" style="width:30px;" type="radio" id="payment-amount-full" name="payment-amount" value="full"/><label style="float:none; width:auto;" for="payment-amount-full"><?php echo $currency_sym.$invoice_amount?></label>
										<input style="width:30px;" type="radio" id="payment-amount-manual" name="payment-amount" value="manual"/><input id="partial-amount" name="partial-amount" style="width: 70px;" type="text" />
									</div>
									
									<script>
									$('#partial-amount').click(function(){
										$('#payment-amount-manual').attr('checked', 'checked');
									});
									</script>
									
									<div class="form-field">
										<label for="">Payment Method <span class="asterisk">*</span></label>
										<select id="payment-method" name="payment-method">
											<?php if($invoice['Invoice']['allow_cc'] == 1) { ?><option value="card">Credit/Debit Card</option><?php } ?>
											<?php if($invoice['Invoice']['allow_paypal'] == 1) { ?><option value="paypal">PayPal</option><?php } ?>
											<?php if($invoice['Invoice']['allow_bacs'] == 1) { ?><option value="bank">Bank Transfer</option><?php } ?>
										</select>
									</div>
									<?php if($invoice['Invoice']['recurring_invoice_id'] != null && $settings['invoice.allow_recurring_payments'] == 'true') { ?>
									<div class="form-field">
										<label for="recurring_payment">Set up recurring payment for this regular invoice (payments will be taken automatically for each invoice)</label>
										<input type="checkbox" value="1" id="recurring_payment" name="recurring_payment" <?php if($invoice['Invoice']['default_to_recurring_payment']) { ?>checked="checked"<?php }?>>
									</div>
									<script>
									$(document).ready(function() {
										if($('#recurring_payment').is(":checked")) {
											$('#payment-amount-full').attr('checked', 'checked');
											$('#payment-amount-manual,#partial-amount').hide(0);
										}

										$('#recurring_payment').change(function() {
											if($(this).is(":checked")) {
												$('#payment-amount-full').attr('checked', 'checked');
												$('#payment-amount-manual,#partial-amount').hide(0);
											}
											else {
												$('#payment-amount-manual,#partial-amount').show(0);
											}
										});
									});
									</script>
									<?php } ?>
									<?php if($settings['invoice.payment_processor']=='_worldpay') { ?>
									<div class="credit-card method-block">
									<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=1044322"></script>
									<p>Payments will be taken in GBP(&pound;).</p>
									</div>
									<?php } ?>
									<?php $hosted_page = $settings['invoice.payment_processor']=='_barclaycard' && $settings['barclaycard.method'] == 'hosted' ? true : false;
									$hosted_page = $settings['invoice.payment_processor']=='_worldpay' ? true : $hosted_page; ?>
									<?php if($invoice['Invoice']['allow_cc'] == 1 && !$hosted_page) { ?><div class="credit-card method-block">
									
										<div class="form-field">
											<label for="">Choose card <span class="asterisk">*</span></label>
											<select id="card-select" name="card-select">
											<?php foreach($cards as $card){?>
												<option value="<?php echo $card['Card']['id']?>">XXXX-XXXX-XXXX-<?php echo $card['Card']['last_four']?></option>
											<?php }?>
												<option value="new-card">Add new card...</option>
											</select>
										</div>
										
										<script>
										$(document).ready(function(){
											if($('#card-select').val()=='new-card')
											{
												$('#new-card').toggle(true);
												$('#card-default').attr('checked', 'checked');
											}
										});
										$('#card-select').change(function(){
											if($('#card-select').val()=="new-card")			
											{
												$('#new-card').slideToggle(true);
											}else
											{
												$('#new-card').toggle(false);
											}
										});
										</script>
												
										<div class="form-field">
											<label for="">Make this my default card?</label>
											<input type="checkbox" id="card-default" name="card-default" />
										</div>		
												
															
										<div id="new-card" class="hidden">
											<div class="form-field"><label for="card-type">Card Type <span class="asterisk">*</span></label> 
												<select id="card-type" name="card-type">
													<option selected="selected" value="">--- Please select ---</option>
													<option value="Visa">Visa</option>
													<option value="Master Card Credit">Master Card Credit</option>
													<option value="Debit Master Card">Debit Master Card</option>
													<option value="American Express">American Express</option>
													<option value="JCB">JCB</option>
													<option value="Maestro">Maestro</option>
													<option value="">Other</option>
												</select>
											</div>
											
											<div class="form-field">
												<label for="">Name on card <span class="asterisk">*</span></label>
												<input type="text" id="card-name" name="card-name" />
											</div>
											
											<div class="form-field">
												<label for="">Card number<span class="asterisk">*</span></label>
												<input type="text" id="card-number" name="card-number" />
											</div>
											
											<div class="form-field small-field">
												<label for="card-start-month">Start Date (if known)</label> 
												<select id="card-start-month" name="card-start-month">
													<option selected="selected" value=""></option>
													<?php 
														for($i=1;$i<=12;$i++){
														$i = $i< 10 ? '0'.$i: $i;
													?>
													<option value="<?php echo $i?>"><?php echo $i?></option>
													<?php }?>
												</select>
												
												<select id="card-start-year" name="card-start-year">
													<option selected="selected" value=""></option>
													<?php 
														$year = date('Y');
														for($i=$year-8;$i<=$year;$i++){
														$i = $i< 10 ? '0'.$i: $i;
													?>
													<option value="<?php echo $i?>"><?php echo $i?></option>
													<?php }?>
												</select>
											</div>
											
											<div class="form-field small-field">
												<label for="card-exp-month">Expiry Date <span class="asterisk">*</span></label> 
												<select id="card-exp-month" name="card-exp-month">
													<option selected="selected" value=""></option>
													<?php 
														for($i=1;$i<=12;$i++){
														$i = $i< 10 ? '0'.$i: $i;
													?>
													<option value="<?php echo $i?>"><?php echo $i?></option>
													<?php }?>
												</select>
												
												<select id="card-exp-year" name="card-exp-year">
													<option selected="selected" value=""></option>
													<?php 
														$year = date('Y');
														for($i=$year;$i<=$year+10;$i++){
														//$i = substr($i, 1);
													?>
													<option value="<?php echo $i?>"><?php echo $i?></option>
													<?php }?>
												</select>
											</div>
											
											<div class="form-field">
												<label for="">Security Code <span class="asterisk">*</span></label>
												<input type="text" id="cvv-code" name="cvv-code" />
											</div>
											
											<div class="form-field">
												<label for="">Issue number (if known)</label>
												<input type="text" id="card-issue" name="card-issue" />
											</div>
											
											
										</div>
									
									</div><?php } ?>
									<?php if($invoice['Invoice']['allow_paypal'] == 1) { ?><div class="paypal method-block">
										<div class="form-field">
											<img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" border="0" alt="PayPal Logo" style="float: right;margin-top: 10px;">
											<p>You will be redirected to PayPal to complete this payment</p>
										</div>
									</div><?php } ?>
									<?php if($invoice['Invoice']['allow_bacs'] == 1) { ?><div class="bank method-block">
										<div class="form-field">
											<p>You have chosen to pay by BACS transfer.</p>
											<p><span class="strong">STS Invoices Account Details:</span><br/>
											Sort Code: 30-67-34<br/>
											Account Number: 07020460</p>
											<p>Please click below to complete this form in order to confirm that you have completed the transfer and mark this invoice as paid.</p>
										</div>
									</div><?php } ?>
														
									<div class="form-field">
										<label for="">Send receipt to email <span class="asterisk">*</span></label>
										<input type="text" id="email" name="email" value="<?php echo $invoice['Client']['email']?>"/>
									</div>
									
									
								</div>
								
								
								
								<?php }?>
								
								<script>
									$(document).ready(function() {
										
										var defaultMethod = $('#payment-method').val();
										$('.method-block').hide(0);
										
										switch(defaultMethod)
										{
										case 'card':
											$('.credit-card').show(0);
											break;
										case 'paypal':
											$('.paypal').show(0);
											break;
										case 'bank':
											$('.bank').show(0);
											$('#submitOrderBtn').html('Mark as Paid');
											break;
										}
									});
									$('#payment-method').change(function() {
										$('#submitOrderBtn').html('Pay Now').removeAttr('style');
										if($(this).val() == 'card')
										{
											$('.method-block').hide(0);
											$('.credit-card').show(0);
										}
										else if($(this).val() == 'paypal')
										{
											$('.method-block').hide(0);
											$('.paypal').show(0);
										}
										else if($(this).val() == 'bank')
										{
											$('.method-block').hide(0);
											$('.bank').show(0);
											$('#submitOrderBtn').html('Mark as Paid');
										}
									});
								</script>
								
								<div id="card-error"></div>
								
								<div class="center-btn">
									<button id="submitOrderBtn" class="dark-btn">Pay Now</button>
								</div>
							</form>
							
						</div>
						
						
						<script>
						$("#order-form").submit(function()
						{
							if(!validatorOrder.form())
							{
								return false;
							}
						});
				
				
						$("#submitOrderBtn").click(function()
						{
							$("#order-form").submit();
						});
				
						var validatorOrder;
				
						$().ready(function() 
						{
							// validate the comment form when it is submitted
							validatorOrder = $("#order-form").validate(
							{
								rules: {
									"customer-name": {required:true},
									"company-name": {required:true},
									"address1": {required:true},
									//"address2": {required:true},
									"city": {required:true},
									"state": {required:true},
									"zipcode": {required:true},
									"country": {required:true},
									"card-type": {required:true},
									"card-name": {required:true},
									"card-number": {required:true},
									"card-exp-month": {required:true},
									"card-exp-year": {required:true},
									"cvv-code": {required:true},
									"email": {required:true, email:true},
									"account-email": {email:true},
									"partial-amount": {number :true, minlength:1, maxlength:10,}
									
								},
								messages: {
									//"signupTextEmail": "Required",
									
								}
							});
				
						});
						
						<?php 
						$payment_status = $this->Session->read('payment_status');
						$orig_response = $this->Session->read('orig_response');
						if(isset($payment_status) && !$payment_status)
						{
							//handle previous payment failure
						?>
						element_to_scroll_to = document.getElementById('payment-details');
						element_to_scroll_to.scrollIntoView();
						$('#card-error').html('The transaction was declined, please review your details and try again.');
						$('#submitOrderBtn').html('Try Again');
				
						<?php 
						if($settings['application.mode']=='development')
						{
						?>
						$('#page').append('<pre><?php echo json_encode($orig_response)?></pre>');
						<?php }?>
					<?php } ?>
				
						</script>
					
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>					
<?php }else{?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h1><strong>STS Online</strong></h1>
						<h2>You are not authorised to access this page.</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>					

<?php }?>

<?php if(isset($invoice)){?>
<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h2 class="title">Payment Processed</h2>
					</div>	
					<div class="page-intro">
						<p>Thanks for your payment, an email receipt will be sent to <?php echo $emails; ?></p>
						
						<?php if(isset($remaining_balance)){?>
						<p>There is still a remaining balance of <?php echo $curr_sym.$remaining_balance?> left to pay on this invoice, you can make full or partial payment towards this amount by following the below link.</p>
						<p><a href="<?php echo $invoice_url?>">Click here to pay more towards this invoice.</a></p>
						<?php }?>
						
						<p>You can safely close this window if you're done.</p>

						<div id="payment-success">
							<?php
								//echo $this->Html->link('Test payment success', array('controller' => 'invoices', 'action' => 'payment_success', $invoice['Invoice']['id'],'sts123456'));
							?>
						</div>
							
						<div id="payment-fail">
							<?php
								//echo $this->Html->link('Test payment fail', array('controller' => 'invoices', 'action' => 'payment_fail', $invoice['Invoice']['id']));
							?>
						</div>
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
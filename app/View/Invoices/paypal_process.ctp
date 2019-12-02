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
						<p>Thanks for your payment via PayPal, an email receipt will be sent to <?php echo $invoice['Client']['email']?></p>
						<p>You can safely close this window if you're done.</p>	
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
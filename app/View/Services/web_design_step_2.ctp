<?php
	$this->set('title_for_layout', 'Team 8: Web Design Step 2');
	$this->set('description_for_layout', 'If you are happy with our representative quote please complete the form and we will get back to you very soon.');
?>

<div id="wrapper" class="page-wrapper-<?php echo $this->request->params['action']?>">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content page-content-<?php echo $this->request->params['action']?> large-container">
					<div class="sts-intro">
						<h1><strong>Your Estimate: <span class="quote-price">&pound;<?php echo $quote_summary['amount'];?></span></strong></h1>
						<p>Please complete this form to request a formal quote based on your individual requirements.</p>
					</div>
					<div class="large-container grid">
						<h3 class="breakdown">Breakdown:</h3>
						<div class="quote-summary grid">
							<p><?php echo $quote_summary['backend'];?></p>
							<?php 
							if(isset($quote_summary['items']['features']))
							{
								foreach($quote_summary['items']['features'] as $feature) 
							{?>
								<p><?php echo $feature;?></p>
							<?php }}?>
							<?php 
							if(isset($quote_summary['items']['extras']))
							{
								foreach($quote_summary['items']['extras'] as $extra) {?>
								<p><?php echo $extra;?></p>
							<?php }}?>
							<div class="options-button">	
								<a href="javascript:history.back()">Change Options?</a>
							</div>	
						</div>	
						<form id="quote-builder-form" method="POST" action="<?php echo $this->webroot?>contact/quote">
							<input type="hidden" name="data[quote][type]" value="web design" />
							<?php echo (isset($quote_info)?$quote_info:"");?>
							<div class="design-form">
								<div class="design-column">	
									<div class="label-container">	
										<label>Your Name</label>
										<span>providing your name helps us with introductions</span>
									</div>	
									<div class="input-container">	
										<input id="fname" placeholder="Full Name" type="text" name="data[quote][name]" required>
									</div>
								</div>
								<div class="design-column">		
									<div class="label-container">	
										<label>Email Address</label>
										<span>so we can send you a more detailed quote</span>
									</div>	
									<div class="input-container">
										<input id="email" placeholder="Email" type="email" name="data[quote][email]" required>
									</div>	
								</div>
								<div class="design-wide-column">
									<div class="label-container">	
										<label>Comments</label>
										<span>any additional requirements (optional)</span>
									</div>		
									<div class="textarea-container">
										<textarea placeholder="Your Comments" cols="30" rows="6" name="data[quote][message]"></textarea>
									</div>	
								</div>	
								<input id="submit" type="submit" class="blue-btn" value="Request Quote">
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Google Code for STS Interacted with Quote Step 1 Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1038975417;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "k8ipCIzhrmUQuYO27wM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1038975417/?label=k8ipCIzhrmUQuYO27wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


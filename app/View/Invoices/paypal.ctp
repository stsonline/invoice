<html>
<head>
</head>
<body>
<form action="<?php echo $paypal_url; ?>" method="post" class="hidden" id="paypal_form" style="display: none;opacity: 0;">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
<input type="hidden" name="item_name" value="STS Invoice <?php echo $paypal_data['inv_id']; if($paypal_data['payment-amount'] == 'partial') { echo ' (partial)'; } ?>">
<input type="hidden" name="item_number" value="<?php echo $paypal_data['inv_id']; ?>">
<input type="hidden" name="currency_code" value="<?php echo $paypal_data['currency']; ?>">
<input type="hidden" name="amount" value="<?php echo $paypal_data['paying_amount']; ?>">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="notify_url" value="<?php echo $paypal_data['notify_url']; ?>">
<input type="hidden" name="return" value="<?php echo $paypal_data['return_url']; ?>">
<input type="hidden" name="cancel_return" value="<?php echo $paypal_data['payment_url']; ?>">
<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
<script>
document.getElementById("paypal_form").submit();
</script>
</body>
</html>
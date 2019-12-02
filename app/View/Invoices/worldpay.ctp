<html>
<head>
</head>
<body>
<form action="<?php echo $url; ?>" method="post" class="hidden" id="worldpay_form" style="display: none;opacity: 0;">
<?php foreach($worldpay_data as $key => $datum) { ?>
<input type="hidden" name="<?php echo $key; ?>"<?php if(isset($datum)) { ?> value="<?php echo $datum; ?>"<?php } ?>>
<?php } ?>
</form>
<script>
document.getElementById("worldpay_form").submit();
</script>
</body>
</html>
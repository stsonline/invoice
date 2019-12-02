<html>
<head>
</head>
<body>
<form action="<?php echo $url; ?>" method="post" class="hidden" id="epdq_form" style="display: none;opacity: 0;">
<?php foreach($epdq_data as $key => $datum) { ?>
<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $datum; ?>">
<?php } ?>
</form>
<script>
document.getElementById("epdq_form").submit();
</script>
</body>
</html>
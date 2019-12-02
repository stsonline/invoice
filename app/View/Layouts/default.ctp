<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $title_for_layout; ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="Team 8 Digital, online technology, custom software, ecommerce, web development, digital marketing" />
	<meta name="description" content="Welcome to Team 8 Digital: The home of web design. For custom software, ecommerce, web development, & digital marketing - Call us now 01656 513 046" />
	<!-- Favicons -->
	<link rel="icon" sizes="192x192" href="<?php echo $this->webroot?>img/favicons/droid-192x192.png">
	<link rel="apple-touch-icon" href="<?php echo $this->webroot?>img/favicons/ios-180x180.png">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo $this->webroot?>img/favicons/ios-152x152.png">
	<link rel="apple-touch-icon-precomposed" href="<?php echo $this->webroot?>img/favicons/ios-152x152.png">
	<meta name="msapplication-TileColor" content="#FFFFFF">
	<meta name="msapplication-TileImage" content="<?php echo $this->webroot?>img/favicons/droid-144x144.png">
	<meta name="msapplication-square70x70logo" content="<?php echo $this->webroot?>img/favicons/win-70x70.png">
	<meta name="msapplication-square150x150logo" content="<?php echo $this->webroot?>img/favicons/win-150x150.png">
	<meta name="msapplication-wide310x150logo" content="<?php echo $this->webroot?>img/favicons/win-310x150.png">
	<meta name="msapplication-square310x310logo" content="<?php echo $this->webroot?>img/favicons/win-310x310.png">
	<!-- Themes -->
	<meta name="theme-color" content="#479ccf">
	<meta name="msapplication-navbutton-color" content="#479ccf">

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('styles');
		echo $this->Html->css('useful');
		echo $this->Html->css('team');
		echo $this->Html->css('responsive');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Arvo:400,700');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Lato:300,700');

		echo $this->Html->script('jquery-1.7.1.min.js');
		echo $this->Html->script('jquery.validate.min.js');
		echo $this->Html->script('jquery-ui-1.10.0.custom.min.js');
		echo $this->Html->script('jquery.validate.min.js');

		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>

<body>
<div id="default-header-wrapper">
	<div class="fullscreen-menu">
		<?php echo $this->Element('small_sts');?>
		<?php echo $this->Element('fullscreen_menu');?>
	</div>
	<div class="large-container grid">
		<div id="logo">
			<a href="<?php echo $this->webroot?>"><img class="logo" src="<?php echo $this->webroot?>img/logo/logo-blue.png" alt="Team 8 Digital" /></a>
		</div>
		<div id="menu-wrapper">
			<?php echo $this->Element('menu');?>
		</div>
	</div>
</div>

<?php echo $this->fetch('content'); ?>

<div id="footer-content-wrapper">
	<div id="footer-content">
		<?php echo $this->Element('footer');?>
	</div>
</div>

<?php echo $this->Element('company');?>
<?php echo $this->Element('analytics');?>
</body>
</html>

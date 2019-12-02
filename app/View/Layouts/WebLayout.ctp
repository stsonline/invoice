<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $title_for_layout; ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php
		if(isset($description_for_layout)){ echo "<meta name='description' content='".$description_for_layout."' />"; }
    ?>
    <meta name="keywords" content="Team 8, web design, custom software, ecommerce, web development, digital marketing"></meta>
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
		echo $this->Html->css('popup');
		echo $this->Html->css('slippry');
		echo $this->Html->css('checkbox');
		echo $this->Html->css('carousel/slick');
		echo $this->Html->css('carousel/slick-theme');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Arvo:400,700');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Lato:300,700');
		echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');

		echo $this->Html->script('jquery-1.7.1.min.js');
		echo $this->Html->script('jquery-ui-1.10.0.custom.min.js');
		echo $this->Html->script('popup.min.js');
		echo $this->Html->script('slippry.min.js');
		echo $this->Html->script('checkbox/icheck.min.js');
		echo $this->Html->script('checkbox/custom.min.js');
		echo $this->Html->script('stickem/jquery.stickem.js');
		echo $this->Html->script('carousel/slick.min.js');
		echo $this->Html->script('https://maps.googleapis.com/maps/api/js');

		echo $this->fetch('css');
		echo $this->fetch('script');

		?>

		<script type="text/javascript" src="<?php echo $this->webroot?>fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="<?php echo $this->webroot?>fancybox/jquery.fancybox-1.3.4.pack.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>fancybox/jquery.fancybox-1.3.4.css" media="screen" />


</head>

<body>
<div class="page-header-wrapper page-class-<?php echo $this->request->params['action']?>">
	<div class="header-award">
		<a href="http://cssreel.com/Website/sts-online" target="_blank">
			<img src="<?php echo $this->webroot?>img/awards/reel-01.png" alt="CSS Reel Nominee" />
		</a>
	</div>
	<div class="fullscreen-menu">
		<?php echo $this->Element('small_sts');?>
		<?php echo $this->Element('fullscreen_menu');?>
	</div>
	<div class="large-container grid">
		<div id="logo">
			<?php
			$logofilename = 'logo-blue.png';
			$fileexists = file_exists(APP.'webroot/img/logo/'.'logo-'.$this->request->params['action'].'.png');

			if($this->request->params['controller']=='services' && $fileexists)
			{
				$logofilename = 'logo-'.$this->request->params['action'].'.png';
			}

			?>

			<a href="<?php echo $this->webroot?>"><img class="logo" src="<?php echo $this->webroot?>img/logo/<?php echo $logofilename;?>" alt="Team 8" /></a>
		</div>
		<div id="menu-wrapper">
			<?php echo $this->Element('menu');?>
		</div>
	</div>
	<div class="page-header page-header-<?php echo $this->request->params['action']?> large-container grid">
		<div class="page-header-intro">
			<?php echo @$this->Element('page_intro',$headings[$this->request->params['action']]);?>
			<a class="blue-btn" href="#begin">Get A Quote</a>
		</div>
	</div>
</div>

<?php echo $this->fetch('content'); ?>
<?php echo $this->Element('footer_links');?>
<?php echo $this->Element('company');?>
<?php echo $this->Element('analytics');?>
</body>
</html>

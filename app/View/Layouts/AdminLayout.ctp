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

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('styles');
		echo $this->Html->css('useful');
		echo $this->Html->css('team');
		echo $this->Html->css('responsive');
		echo $this->Html->css('popup');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Arvo:400,700');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Lato:300,700');

		echo $this->Html->script('jquery-1.7.1.min.js');
		echo $this->Html->script('jquery-ui-1.10.0.custom.min.js');
		echo $this->Html->script('popup.min.js');

		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>

<body>
<div id="admin-header-wrapper">
	<div class="large-container grid">
		<div id="logo">
			<a href="<?php echo $this->webroot?>dashboard"><img class="logo" src="<?php echo $this->webroot?>img/logo/logo-icing.png" alt="Team 8" /></a>
		</div>
		<div id="menu-wrapper">
			<?php
				if($this->request->params['controller']!='users')
				{
					//don't render the admin menu when in /users actions for security
					echo $this->element('admin_menu');
				}
			?>
		</div>
	</div>
</div>

<div id="login-info">
	<p>
		<?php
			$currentuser=$this->Session->read('Auth.User');
			if(isset($currentuser))
			{
				echo $currentuser['username']."<br/>". $this->Html->link('Logout',
															array('controller'=>'users','action' => 'logout'));
			}
			else
			{
				//echo $this->Html->link('Log In', array('controller'=>'users','action' => 'login'));
			}
		?>
	</p>
</div>
<div class="large-container admin-content">
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
</div>
<div class="admin-footer">
	<?php echo $this->Element('company');?>
</div>

</body>
</html>

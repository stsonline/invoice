<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $title_for_layout; ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php
		if(isset($description_for_layout)){ echo "<meta name='description' content='".$description_for_layout."' />"; }
    ?>
    <meta name="keywords" content="STS Online, STS Commercial, web design, custom software, ecommerce, web development, digital marketing"></meta>

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('styles');
		echo $this->Html->css('useful');
		echo $this->Html->css('team');
		echo $this->Html->css('responsive');
		echo $this->Html->css('popup');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Arvo:400,700');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Lato:300,700');

		//datatables css
		echo $this->Html->css('https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');

		//echo $this->Html->script('http://code.jquery.com/jquery-latest.min.js'); //latest min version from jquery site
		echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
		//datatables js
		//echo $this->Html->script('http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js');
		echo $this->Html->script('https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js');
		//include TableTools
		echo $this->Html->script('TableTools-2.1.5/media/js/TableTools.js');
		echo $this->Html->script('TableTools-2.1.5/media/js/ZeroClipboard.js');

		echo $this->Html->script('jquery-1.7.1.min.js');
		echo $this->Html->script('jquery-ui-1.10.0.custom.min.js');
		echo $this->Html->script('popup.min.js');

		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

</head>

<body>
<div id="admin-header-wrapper">
	<div class="large-container">
		<div id="logo">
			<a href="<?php echo $this->webroot?>dashboard"><img class="logo" src="<?php echo $this->webroot?>img/logo/logo-icing.png" alt="STS Online" /></a>
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

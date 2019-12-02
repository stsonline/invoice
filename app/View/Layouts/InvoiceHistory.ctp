<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $title_for_layout; ?></title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=600px initial-scale=0.8" /> 
	
	<?php echo isset($metas) ? $metas : '';?>
	
	<?php 
		echo $this->Html->meta('icon');
		echo $this->Html->css('default');
		echo $this->Html->css('useful');
		echo $this->Html->css('media600');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Arvo:400,700');
		echo $this->Html->css('jphotogrid');
		echo $this->Html->css('jphotogrid.ie');
		echo $this->Html->css('jquery-ui.css');
		echo $this->Html->css('mobile-view.css');
		echo $this->Html->css('idangerous-swiper.css');
		echo $this->Html->css('https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css');
		
		echo $this->Html->script('jquery-1.7.1.min.js');
		echo $this->Html->script('jquery.mobile-1.4.0.min.js');
		echo $this->Html->script('cufon-yui.js');
		echo $this->Html->script('cufon-replace.js');
		echo $this->Html->script('Shanti_400.font.js');
		echo $this->Html->script('Didact_Gothic_400.font.js');
		echo $this->Html->script('jquery.jqtransform.js');
		echo $this->Html->script('jquery.validate.min.js');
		echo $this->Html->script('names');
		echo $this->Html->script('jquery-ui-1.10.0.custom.min.js');
		echo $this->Html->script('application-common');
		//echo $this->Html->script('jpanelmenu');
		echo $this->Html->script('idangerous.swiper-2.4.min.js');
		echo $this->Html->script('https://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js');
		//include TableTools
		echo $this->Html->script('TableTools-2.1.5/media/js/TableTools.js');
		echo $this->Html->script('TableTools-2.1.5/media/js/ZeroClipboard.js');
		
		if($settings['application.fill_forms']=='true')
		{
			echo $this->Html->script('form-filler');
		}
		
		
		
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	
</head>

<body>
<script>
var WEBROOT = "<?php echo $this->webroot?>";
</script>
<div id="header-wrapper">
	<?php echo $this->Element('header');?>
</div>
<div id="menu-wrapper">
	<?php echo $this->Element('menu');?>
</div>

			<?php // echo $this->Session->flash(); ?>
<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div id="page">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
		
				</div>
			</div>
		</div>
	</div>
</div>
<div id="footer-content-wrapper">
	<div id="footer-content-bgshadow">
		<div id="footer-content">
			<?php echo $this->Element('footer');?>
		</div>
	</div>
</div>

<pre>
<?php //echo print_r($showcase_featured_items);?>
</pre>

</body>	
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title><?php echo 'title';?></title>
<style>
.button_example{
border:1px solid #b7b7b7; -webkit-border-radius: 3px; -moz-border-radius: 3px;border-radius: 3px;font-size:12px; padding: 10px 10px 10px 10px; text-decoration:none; display:inline-block;text-shadow: -1px -1px 0 rgba(0,0,0,0.3);font-weight:bold; color: #FFFFFF;
 background-color: #d3d3d3; background-image: -webkit-gradient(linear, left top, left bottom, from(#d3d3d3), to(#707070));
 background-image: -webkit-linear-gradient(top, #d3d3d3, #707070);
 background-image: -moz-linear-gradient(top, #d3d3d3, #707070);
 background-image: -ms-linear-gradient(top, #d3d3d3, #707070);
 background-image: -o-linear-gradient(top, #d3d3d3, #707070);
 background-image: linear-gradient(to bottom, #d3d3d3, #707070);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#d3d3d3, endColorstr=#707070);
	font-size: 1.8em;
}

.button_example:hover{
 border:1px solid #a0a0a0;
 background-color: #bababa; background-image: -webkit-gradient(linear, left top, left bottom, from(#bababa), to(#575757));
 background-image: -webkit-linear-gradient(top, #bababa, #575757);
 background-image: -moz-linear-gradient(top, #bababa, #575757);
 background-image: -ms-linear-gradient(top, #bababa, #575757);
 background-image: -o-linear-gradient(top, #bababa, #575757);
 background-image: linear-gradient(to bottom, #bababa, #575757);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#bababa, endColorstr=#575757);
}

</style>

</head>
<body style="color: #000000; width: 620px; margin: 0 auto;">
	<div id="header" style="background: #ffffff; height: 100%; width: 600px; text-align: center; font-family: arial; padding: 20px;">
		<div class="header-image" style="width: 150px; margin: 0 auto; clear:both;">
			<?php
			echo $this->Html->image( Router::url('/', true) . "app/webroot/img/logo-small.png", array('options'=>array('width'=>'150px')) );
			?>
		</div>
		<p>team8digital.uk</p>

		<br>
	</div>

	<?php echo $this->fetch('content');?>

	<div id="footer" style="background: #eeffee; color: #000000; width: 600px; text-align: center;">

	</div>

</body>
</html>

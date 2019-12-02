
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
<body style="color: #000000; width: 100%; margin: 0 auto;">
	<div id="header" style="background: #345c88 url(https://team8digital.uk/img/header/web-design.jpg) no-repeat fixed 50% 10%; height: 100%; width: 100%; text-align: center; font-family: arial; padding: 20px; border-bottom: 5px solid #479CCF; margin-bottom: 40px;">
		<div class="header-image">
			<?php
			//echo $this->Html->image( Router::url('/', true) . "app/webroot/img/logo/logo-blue.png", array('options'=>array('width'=>'300px')) );
			?>
			<img src="https://team8digital.uk/img/logo/logo-blue.png" width="300px" alt="Team 8 Logo"/>
		</div>
		<div style="margin: 30px 0 0px 0;">
			<a href="https://team8digital.uk" target="_blank" style="color: #ffffff;">team8digital.uk</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
			<a href="tel:+441656513046" target="_blank" style="color: #ffffff;">01656 513 046</a>
		</div>
		<br>
	</div>

	<?php echo $this->fetch('content');?>

</body>
</html>

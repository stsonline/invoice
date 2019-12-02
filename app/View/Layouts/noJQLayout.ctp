<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Sinclair Technology Solutions - eCommerce, Business Optimization, Software Development & APIs</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />

	<?php 
		echo $this->Html->css('default');
		echo $this->Html->css('http://fonts.googleapis.com/css?family=Arvo:400,700');
		echo $this->Html->css('useful');
		
		//echo $this->Html->script('jquery-1.7.1.min.js');
		//echo $this->Html->script('jquery.slidertron-1.0.js');
		//echo $this->Html->script('init.js');
		
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>

<body>
<div id="header-wrapper">
	<div id="header">
		<div  id="logo" class="corner-all border-all-thick border-color-black  margin-bottom-10">
			<img id="logo" src="app/webroot/img/logo/logo.png" alt="" />
		</div>
		<div id="social">
			<div id="social-bg">
				<div id="social-bgleft">
					<div id="social-bgright">
						<ul class="style1">
							<li><a href="http://www.facebook.com"><img src="app/webroot/img/social-icon-01.png" width="36" height="36" alt="" /></a></li>
							<li><a href="http://www.twitter.com"><img src="app/webroot/img/social-icon-02.png" width="36" height="36" alt="" /></a></li>
							<li><a href="http://www.linkedin.com"><img src="app/webroot/img/social-icon-03.png" width="36" height="36" alt="" /></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="menu-wrapper">
	<div id="menu" class="container">
		<ul>
			<li><a href="index" accesskey="1" title=""><span>Home</span></a></li>
			<li><a href="services" accesskey="2" title=""><span>Services</span></a></li>
			<li><a href="showcase" accesskey="3" title=""><span>Showcase</span></a></li>
			<li><a href="articles" accesskey="4" title=""><span>Articles</span></a></li>
			<li><a href="jobs" accesskey="5" title=""><span>Jobs</span></a></li>
			<li><a href="aboutus" accesskey="6" title=""><span>About Us</span></a></li>
			<li><a href="contactus" accesskey="7" title=""><span>Contact Us</span></a></li>
		</ul>
	</div>
</div>

			<?php echo $this->Session->flash(); ?>
		
			<?php echo $this->fetch('content'); ?>
			
<div id="footer-content-wrapper">
	<div id="footer-content-bgshadow">
		<div id="footer-content">
			<div id="fbox1">
				<h2>Services</h2>
				<ul class="style2">
					<li class="first"><a href="#">eCommerce</a></li>
					<li><a href="#">Managed eCommerce</a></li>
					<li><a href="#">Software Development</a></li>
					<li><a href="#">Affiliate and Search Engine Marketing</a></li>
					<li><a href="#">Business Optimisation</a></li>
					<li><a href="#">Graphic Design</a></li>
				</ul>
			</div>
			<div id="fbox2">
				<h2>Site Map</h2>
				<ul class="style2">
					<li class="first"><a href="index">Home</a></li>
					<li><a href="services">Services</a></li>
					<li><a href="shwocase">Showcase</a></li>
					<li><a href="articles">Articles</a></li>
					<li><a href="jobs">Jobs</a></li>
					<li><a href="aboutus">About Us</a></li>
					<li><a href="contactus">Contact Us</a></li>
				</ul>
			</div>
			<div id="fbox3">
				<iframe width="275" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.uk/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=CF31+1JF&amp;aq=&amp;sll=52.8382,-2.327815&amp;sspn=13.277812,33.815918&amp;t=m&amp;ie=UTF8&amp;hq=&amp;hnear=CF31+1JF,+United+Kingdom&amp;ll=51.507327,-3.579998&amp;spn=0.008013,0.012875&amp;z=15&amp;output=embed"></iframe>
				<p><h3>Sinclair Technology Solutions Ltd</h3><br />
					2nd Floor, 7 Dunraven Place,<br /> 
					Bridgend, Mid Glamorgan,<br />
					CF31 1JF</p>
				<p class="email"><a href="mailto:contact@sinclairtechnologysolutions.com">contact@sinclairtechnologysolutions.com</a></p>
				<p class="phone">(+44) 075-400-50389</p>
			</div>
		</div>
	</div>
</div>
</body>		
</html>
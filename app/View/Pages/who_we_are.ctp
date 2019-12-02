<?php
	$this->set('title_for_layout', 'Team 8: Who We Are');
	$this->set('description_for_layout', 'Team 8: the home of web design. We can help you expand your business potential with a friendly experienced team and flexible project schedules.');
?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h1><strong>Team 8</strong></h1>
						<h2>Your door to world wide business opportunities</h2>
					</div>
					<div class="page-intro">
						<p>At Team 8 we focus on what matters. Designing your potential – Focussing on your business as though it was our own. We transform your idea into an innovative product designed from the ground up to engage customers with the most effective method possible so you can focus on what&#39;s important.</p>
						<a href="#office" class="dark-btn">
							<span>Take the tour</span>
						</a>
					</div>
				</div>
				<div class="page-feature">
					<div class="large-container">
						<h2>The development of Team 8</h2>
						<h3>Find out more about our company</h3>
						<p>Rebranded in 2017</p>
						<p>Located in Bridgend, South Wales</p>
						<p>Clients based in the UK and US</p>
						<p>Experts in a range of ecommerce systems</p>
						<p>Offer a range of hosting solutions</p>
						<p>iOS &amp; Android app development</p>
						<p>Web Design, Hosting, Marketing and many more services</p>
						<!-- <p>Meet the team</p> -->
					</div>
				</div>
				<div class="large-container">
					<div id="office" class="office-tour">
						<h3>Our design studio</h3>
						<ul>
							<li class="first-shot">
								<a href="<?php echo $this->webroot?>img/tour/office_full.jpg" title="Design Studio">
									<img src="<?php echo $this->webroot?>img/tour/office_small.jpg" alt="Design Studio">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/tour/office2_full.jpg" title="Inspiration">
									<img src="<?php echo $this->webroot?>img/tour/office2_small.jpg" alt="Inspiration">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/tour/office3_full.jpg" title="Showcase Gallery">
									<img src="<?php echo $this->webroot?>img/tour/office3_small.jpg" alt="Showcase Gallery">
								</a>
							</li>
							<li class="last-shot">
								<a href="<?php echo $this->webroot?>img/tour/office4_full.jpg" title="Our Ethos">
									<img src="<?php echo $this->webroot?>img/tour/office4_small.jpg" alt="Our Ethos">
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="large-container">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Specialists</h2>
						<div class="introduction">
							<p>Here at Team 8 based in Bridgend Town Centre we develop high performance software applications, unique and effective websites, mobile apps tailored to your specific needs or requirements and digital products for any platform. We focus on forward thinking and react to changing market conditions and customer needs with the same quality throughout. You can talk to us for a free consultation to see how we can help you, and most importantly – implement and make your ideas a reality.</p>
							<p>Mobile responsive websites, applications and clear, bold designs are the focus on achieving a diverse traffic flow to your site. However, we will focus on your requirements and provide you with the support and maintenance that goes the extra mile. We are always available to ensure you have someone to help whenever you need it, whether that be adding more content to your site, implementing an ecommerce system or fixing something we are here. Please call us to see how we can help.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

$(document).ready(function() {
	$('.office-tour').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>Team 8</small>';
			}
		}
	});
});

</script>

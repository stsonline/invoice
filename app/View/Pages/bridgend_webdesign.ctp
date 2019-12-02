<?php
	$this->set('title_for_layout', 'Team 8: Web Design Bridgend');
	$this->set('description_for_layout', 'The home of web design in Bridgend. We can help you expand your business potential with a friendly experienced team and flexible project schedules.');
?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h1><strong>Web Design Bridgend</strong></h1>
						<h2>Team 8 - the home of bespoke web design services in Bridgend</h2>
					</div>
					<div class="page-intro">
						<p>We're situated in the heart of Bridgend Town Centre. Since 2011, we're proud to call ourselves the home of web design in Bridgend. Pop into our offices in Bridgend Town Centre to see how we can help you, and your business.</p>
						<a href="#office" class="dark-btn">
							<span>Take the tour</span>
						</a>
					</div>
				</div>
				<div class="page-feature-bridgend">
					<div class="large-container">
						<h2>Local Clients</h2>
						<h3>Take a look at some of the local clients that we've worked with recently:</h3>
						<ul class="link-tags">
							<li><a target="_blank" href="http://www.thevenuepenybont.co.uk/">The Venue Pen-Y-Bont</a></li>
							<li><a target="_blank" href="http://zianina.co.uk/">Zia Nina</a></li>
							<li><a target="_blank" href="http://www.pursuitoutdoor.co.uk/">Pursuit Outdoor</a></li>
						</ul>
					</div>
				</div>
				<div class="page-content">
					<div class="large-container custom-content clearfix">

						<div class="container-8">

							<div class="small-content">

								<h2><strong>Choosing Team 8 for your Web Design project.</strong></h2>

								<p>The digital market is moving quicker than ever before in today's market. There are plenty of web design tools and custom website builders available online for you to quickly build a decent website. However, what do you do when you're not sure how to generate leads, get started with SEO or market your business? Here at Team 8, our team of friendly, specialised people are here to help you and your business get up and running as soon as possible!</p>

								<p>We love the web, and everything digital. The web is so powerful, yet so many businesses are unsure of where to even start. We're a small, but fast growing web design company based in Bridgend, South Wales. We're here to help you and your business get up and running. We'll explain all of the details to you upfront, and give you a FREE web design quote for your project, if there's something your not sure about, get in touch with us and we'll be happy to help.</p>

								<p>Team 8 specialise in making clean, flexible and fully functional projects, see how we can help you by contacting us via our phone number or email address during our working hours.</p>

								<h3><strong>Here's some of our web design services:</strong></h3>

								<ul>
									<li>Bespoke web design</li>
									<li>SEO services</li>
									<li>Website hosting</li>
									<li>Premium UX</li>
									<li>Social media marketing</li>
								</ul>

								<p>If you're looking for affordable and bespoke web design services based in Bridgend, South Wales, get in touch with us by calling <a class="anchor-highlight" href="tel:01656513046">01656 513 046</a></p>

							</div>

						</div>

						<div class="container-4">

							<div class="custom-photos">
								<div>
									<img src="<?php echo $this->webroot?>img/design/code.jpg" alt="Code at Team 8 Bridgend" />
								</div>
								<div>
									<img src="<?php echo $this->webroot?>img/design/design.jpg" alt="Design Team 8 Bridgend" />
								</div>
								<div>
									<img src="<?php echo $this->webroot?>img/design/notebook.jpg" alt="Notebook Team 8 Bridgend" />
								</div>
							</div>

						</div>



					</div>

				</div>
				<div class="large-container">
					<div id="office" class="office-tour">
						<h3>Take a quick look at our Bridgend offices</h3>
						<ul>
							<li class="first-shot">
								<a href="<?php echo $this->webroot?>img/bridgend/town.jpg" title="Bridgend Town Centre at Team 8">
									<img src="<?php echo $this->webroot?>img/bridgend/town.jpg" alt="Bridgend Town Centre at Team 8 in Bridgend">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/bridgend/town-2.jpg" title="Entrance to the Team 8 offices">
									<img src="<?php echo $this->webroot?>img/bridgend/town-2.jpg" alt="Entrance to the Team 8 offices in Bridgend">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/bridgend/town-3.jpg" title="Street outside our offices">
									<img src="<?php echo $this->webroot?>img/bridgend/town-3.jpg" alt="Street outside our offices in Bridgend">
								</a>
							</li>
							<li class="last-shot">
								<a href="<?php echo $this->webroot?>img/bridgend/town-4.jpg" title="Reception area at Team 8">
									<img src="<?php echo $this->webroot?>img/bridgend/town-4.jpg" alt="Reception area at Team 8 in Bridgend">
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="large-container">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Bridgend</h2>
						<div class="introduction">
							<p>Team 8 have worked with many local clients over the past years in and around Bridgend, South Wales. Most of our clients have found us either by searching for Web Design Bridgend online, or via hearing about us through another happy customer of ours. You can pop into our local offices and talk to us about a great new project! We're always happy to work with new clients.</p>
							<p>We've recently worked with a number of clients, one of which - Zia Nina who is situated a few doors from the Team 8 offices. You can find out more about our recent clients by taking a look at the Our Work page. We're always happy to work with new clients, so if you've got a project, get in touch with us or pop into our Bridgend offices.</p>
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

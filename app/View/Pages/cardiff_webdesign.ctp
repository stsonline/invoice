<?php
	$this->set('title_for_layout', 'Team 8: Web Design Cardiff');
	$this->set('description_for_layout', 'The home of web design in cardiff. We can help you expand your business potential with a friendly experienced team and flexible project schedules.');
?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h1><strong>Web Design Cardiff</strong></h1>
						<h2>Team 8 - the home of bespoke web design services</h2>
					</div>
					<div class="page-intro">
						<p>Although based in Bridgend, South Wales we're reaching out to potential clients in other parts of South Wales. Are you a business based in Cardiff looking for web design services? Get in touch with us.</p>
						<a href="#explore" class="dark-btn">
							<span>Explore</span>
						</a>
					</div>
				</div>
				<div class="page-feature-cardiff">
					<div class="large-container">
						<h2>Clients based near Cardiff</h2>
						<h3>Take a look at some of the clients that we've worked with recently:</h3>
						<ul class="link-tags">
							<li><a target="_blank" href="http://valeifa.com/">The Vale IFA</a></li>
							<li><a target="_blank" href="<?php echo $this->webroot?>workshop/cwj">Welsh And Celtic Jewellery</a></li>
							<li><a target="_blank" href="http://landlordslettingcompany.co.uk/">Landlords Letting Company</a></li>
						</ul>
					</div>
				</div>
				<div class="page-content">
					<div class="large-container custom-content clearfix">

						<div class="container-8">

							<div class="small-content">

								<h2><strong>Why Team 8 are the perfect business for your project.</strong></h2>

								<p>For many years, we've been working with many web design clients. All of our clients have been really happy with the work that we have produced, and also happy with the outcome of their project. With a range of web design services available, Team 8 are the great web design company based in Bridgend, South Wales to help you get up and running with your project.</p>

								<p>We love the web, that's why here at Team 8 we're proud to announce that a lot of modern features come as standard with your web design quote, including: Responsive web design. Get in touch with us if you're interested in working with us.</p>

								<p>As a web design company, we want to ensure that we understand your business inside and out. We'll get to know everything about your business so that we can be sure that our services are right for you.</p>

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
									<img src="<?php echo $this->webroot?>img/design/road.jpg" alt="Road based in Cardiff" />
								</div>
								<div>
									<img src="<?php echo $this->webroot?>img/design/code-2.jpg" alt="Code at Team 8" />
								</div>
								<div>
									<img src="<?php echo $this->webroot?>img/design/weather.jpg" alt="Nature weather photo" />
								</div>
							</div>

						</div>



					</div>

				</div>
				<div class="large-container">
					<div id="explore" class="office-tour">
						<h3>Explore the area</h3>
						<ul>
							<li class="first-shot">
								<a href="<?php echo $this->webroot?>img/cardiff/cardiff.jpg" title="Cardiff Millennium Centre">
									<img src="<?php echo $this->webroot?>img/cardiff/cardiff.jpg" alt="Cardiff Millennium Centre">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/cardiff/cardiff-2.jpg" title="Cardiff Castle Wall">
									<img src="<?php echo $this->webroot?>img/cardiff/cardiff-2.jpg" alt="Cardiff Castle Wall">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/cardiff/cardiff-3.jpg" title="Cardiff Bay View">
									<img src="<?php echo $this->webroot?>img/cardiff/cardiff-3.jpg" alt="Cardiff Bay View">
								</a>
							</li>
							<li class="last-shot">
								<a href="<?php echo $this->webroot?>img/cardiff/cardiff-4.jpg" title="Cardiff Clear Sky">
									<img src="<?php echo $this->webroot?>img/cardiff/cardiff-4.jpg" alt="Cardiff Clear Sky">
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="large-container">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Cardiff</h2>
						<div class="introduction">
							<p>Team 8 has worked with clients who are based in Cardiff and also the surrounding area, These clients found us online by searching for web design cardiff. Some of these clients who are situated in cardiff town found us by visiting our local office in bridgend and other clients chose to use our serivces becuase they had been recommended by other buisinesses to choose Team 8 for there web design needs.</p>
							<p>We are thinking of expanding our buisiness and opening an office in the center of Cardiff in the near future, We have been thinking of this because our bridgend branch is already based close to Cardiff and it would make us so proud to be able to expand our business in to the capital of wales and potentially make more clients in Cardiff.
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

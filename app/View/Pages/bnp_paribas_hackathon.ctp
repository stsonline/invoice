<?php
	$this->set('title_for_layout', 'Team 8: BNP Paribas Hackathon');
	$this->set('description_for_layout', 'At Team 8 we were recently invited to take part in the BNP Paribas International Hackathon in London.');
?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<a href="https://international-hackathon.bnpparibas/" target="_blank">
							<img src="<?php echo $this->webroot?>img/news/hackathon.png" alt="BNP Paribas Hackathon">
						</a>
					</div>
					<div class="page-intro">
						<p>We were recently invited to take part in the BNP Paribas Hackathon in London,
						<br />having been selected from over 2,000 UK businesses.</p>
						<ul>
							<li>
								<h5>Development</h5>
							</li>
							<li>
								<h5>Design</h5>
							</li>
							<li>
								<h5>Presentation</h5>
							</li>
						</ul>
					</div>
				</div>
				<div class="page-feature hackathon-feature">
					<div class="large-container">
						<h2>Hackathon Overview</h2>
						<p>Selected from a shortlist of <strong>2,000+</strong> UK businesses</p>
						<p>Competed against <strong>7</strong> other businesses</p>
						<p>Developed a system in <strong>3</strong> days</p>
						<p>Presented the system to a panel of acclaimed judges</p>
					</div>
				</div>
				<div class="large-container">
					<div class="office-tour">
						<h3>The Event</h3>
						<ul>
							<li class="first-shot">
								<a href="<?php echo $this->webroot?>img/news/team-full.jpg" title="Team 8">
									<img src="<?php echo $this->webroot?>img/news/team-small.jpg" alt="Team 8">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/news/setup-full.jpg" title="Set up">
									<img src="<?php echo $this->webroot?>img/news/setup-small.jpg" alt="Set up">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/news/event-full.jpg" title="Hackathon">
									<img src="<?php echo $this->webroot?>img/news/event-small.jpg" alt="Hackathon">
								</a>
							</li>
							<li class="last-shot">
								<a href="<?php echo $this->webroot?>img/news/view-full.jpg" title="Level 39">
									<img src="<?php echo $this->webroot?>img/news/view-small.jpg" alt="Level 39">
								</a>
							</li>
							<li class="first-shot">
								<a href="<?php echo $this->webroot?>img/news/pappa-1-full.jpg" title="Pappa Portal">
									<img src="<?php echo $this->webroot?>img/news/pappa-1-small.jpg" alt="Pappa Portal">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/news/pappa-2-full.jpg" title="Pappa View">
									<img src="<?php echo $this->webroot?>img/news/pappa-2-small.jpg" alt="Pappa View">
								</a>
							</li>
							<li>
								<a href="<?php echo $this->webroot?>img/news/pappa-3-full.jpg" title="Pappa Notifications">
									<img src="<?php echo $this->webroot?>img/news/pappa-3-small.jpg" alt="Pappa Notifications">
								</a>
							</li>
							<li class="last-shot">
								<a href="<?php echo $this->webroot?>img/news/screen-full.jpg" title="Splash Screen">
									<img src="<?php echo $this->webroot?>img/news/screen-small.jpg" alt="Splash Screen">
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="hack-video large-container">
					<iframe src="https://www.youtube.com/embed/_Jeh5LARV58" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="large-container">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Specialists</h2>
						<div class="introduction">
							<p>The aim of the Hackathon was to develop a software solution for a specific banking use case. We decided to develop <strong>an online tool to help small businesses make better informed financial decisions</strong>. The event consisted of three key parts: development, design, and a presentation in front of a panel of acclaimed judges.</p>
							<p>This was our first Hackathon in our current team format and we were determined both to have fun and deliver real value for the end client. We were invited to level 39, Canary Wharf, London by the host bank BNP Paribas.  Our aim was to develop a system in 3 days from scratch just to show that we could. The event was an exceptional test and demonstration of the team&#39;s abilities. It was a thrilling challenge that not only brought us closer together but also gave us great insight into just what we can achieve in such a short time frame. Here&#39;s hoping for many more events like it in the future.</p>
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

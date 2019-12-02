<?php
	$this->set('title_for_layout', 'Team 8: Get In Touch');
	$this->set('description_for_layout', 'Get in touch with us to discuss the next steps for your business. Whether you need custom software, an ecommerce solution, or a new website we can help.');
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
					<div class="contact-us">
						<div class="contact-methods">
							<div class="option call-us">
								<a href="tel:01656513046">01656 513 046</a>
							</div>
							<div class="option email-us">
								<a href="mailto:hello@team8digital.uk">hello@team8digital.uk</a>
							</div>
							<div class="option find-us">
								<a href="<?php echo $this->webroot?>pdfs/directions.pdf" target="_blank">directions.pdf</a>
							</div>
						</div>
						<div class="contact-info">
							<h3>Contact Team 8</h3>
							<p>Do you have a project for us?</p>
							<div class="contact-address">
								<ul>
									<li><strong>Team 8 Digital</strong></li>
									<li>26 Dunraven Place</li>
									<li>Bridgend</li>
									<li>South Wales (GB)</li>
									<li>CF31 1JD</li>
								</ul>
							</div>
							<div class="directions">
								<h3>Find Us</h3>
								<p>Download the directions to our office</p>
								<p>
									<a href="<?php echo $this->webroot?>pdfs/directions.pdf" target="_blank" class="dark-btn">
										<span>directions.pdf</span>
									</a>
								</p>
							</div>
						</div>
						<form id="contact-form" method="POST" action="<?php echo $this->webroot?>contact">
							<fieldset>
						        <legend>Let's get started</legend>
						        <div class="label-container">
						          <label for="name">Your name</label>
						        </div>
						        <div class="input-container control-group">
						          <input id="name" name="inquiry[name]" placeholder="Type your name here" data-default-placeholder="Type your name here" size="30" type="text" required="required">
						        </div>

						        <div class="label-container">
						          <label for="email">Your contact email</label>
						        </div>

						        <div class="input-container control-group">
						          <input id="email" name="inquiry[email]" placeholder="Type your email here" data-default-placeholder="Type your email here" size="30" type="email" required="required">

						        </div>
						        <div class="label-container">
						          <label for="message">Your message</label>
						        </div>
						        <div class="textarea-container control-group">
						          <textarea cols="40" id="message" name="inquiry[message]" placeholder="How can we help?" data-default-placeholder="How can we help?" rows="10" required="required"></textarea>
						        </div>
						        <div class="label-container">
						          <div class="submit-container send-form">
						            <input class="btn-message-form dark-btn" name="commit" type="submit" value="Send message">
						          </div>
						        </div>
							</fieldset>
						</form>
						<div class="clearfix"></div>
						<div class="grid find-us-directions">
							<div class="left-block">
								<img src="<?php echo $this->webroot?>img/map/map-clock.png" alt="Visit Us">
								<h2>Opening Hours</h2>
								<!--<div id="open-marker" class=""></div>-->
								<p>Visit our office during our opening hours listed below:</p>
								<p>
									<strong>Mon - Fri:</strong>  9:00am - 5:30pm.
								</p>
								<p>
									<strong>Saturday &amp; Sunday:</strong>  Closed.
								</p>
							</div>
							<div class="left-block">
								<img src="<?php echo $this->webroot?>img/map/map-marker.png" alt="Visit Us">
								<h2>Where Are We?</h2>
								<p>Team 8 is based in Bridgend, South Wales. We are a straight walk along Wyndham Street from Bridgend train station. We are opposite the post office so you can&#39;t miss our sign and glass front door.</p>
							</div>
						</div>
					</div>
					<?php /*<div id="map"></div> */ ?>
					<div class="worldpay">
						<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId=1044322"></script>
						<a href="<?php echo $this->webroot?>terms">Terms &amp; Privacy Policy</a>
						<a href="<?php echo $this->webroot?>complaints">Complaints</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
<!--
$(document).ready(function() {
  var currentHour = new Date().getHours();
	var currentMinute = new Date().getMinutes();
  if (currentHour < 9 || (currentHour == 17 && currentMinute >= 30) || currentHour >= 18) {
				$('#open-marker').addClass('closed');
  }
  else if (currentHour >= 9 && currentHour <= 15) {
			$('#open-marker').addClass('open');
  }
  else if (currentHour == 16 || (currentHour == 17 && currentMinute < 30)) {
			$('#open-marker').addClass('closing-soon');
  }
});

getStylesheet();
-->
</script>

<script type="text/javascript">

	google.maps.event.addDomListener(window, 'load', initMap);

	function initMap() {
		// Basic options for a simple map
		var mapOptions = {
			// Set zoom level (always required)
			zoom: 17,
			//zoomControl: false,
			draggable: true,
			scrollwheel: false,
			// The latitude and longitude to center the map (always required)
			center: new google.maps.LatLng(51.506631, -3.579603), // Team 8
			// Style the map
			styles: [{
					"featureType": "administrative",
					"elementType": "labels.text.stroke",
					"stylers": [{
						"color": "#ffffff"
					}, {
						"weight": 6
					}]
				}, {
					"featureType": "administrative",
					"elementType": "labels.text.fill",
					"stylers": [{
						"color": "#479ccf"
					}]
				},

				{
					"featureType": "road",
					"stylers": [{
						"saturation": -70
					}]
				}, {
					"featureType": "transit",
					"stylers": [{
						"visibility": "on"
					}]
				}, {
					"featureType": "poi",
					"stylers": [{
						"visibility": "on"
					}]
				}, {
					"featureType": "water",
					"stylers": [{
						"visibility": "simplified"
					}, {
						"saturation": -60
					}]
				}
			]
		};

		var mapElement = document.getElementById('map');

		var map = new google.maps.Map(mapElement, mapOptions);

		// Map marker
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(51.506631, -3.579603), // Sits above label
			map: map,
			icon: 'https://<?php echo $_SERVER['HTTP_HOST'].$this->webroot?>img/map/map-marker.png',
			title: 'Team 8'
		});
	}
</script>

<div class="footer-links <?php echo $this->request->params['action']?>-links">
	<div class="large-container">
		<div class="footer-column">
			<ul>
				<li class="header header-<?php echo $this->request->params['action']?>">Team 8
					<span class="title-border"></span>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>">Home</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>who-we-are">Who We Are</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>our-work">Our Work</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>get-in-touch">Get In Touch</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>feedback">Feedback</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>standardtermsofservices.pdf">Terms Of Service</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>careers">Careers</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>blog">Blog</a>
				</li>
			</ul>
		</div>
		<div class="footer-column">
			<ul>
				<li class="header header-<?php echo $this->request->params['action']?>">Development
					<span class="title-border"></span>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/web-design">Web Design</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/ecommerce">Ecommerce</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/hosting">Hosting</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/cs-cart">CS Cart Reseller</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/app-development">App Development</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/rightmove-api">Rightmove API</a>
				</li>
			</ul>
		</div>
		<div class="footer-column">
			<ul>
				<li class="header header-<?php echo $this->request->params['action']?>">Services
					<span class="title-border"></span>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/support">Tech Support</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/social-media">Social Media</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/cyber-security">Cyber Security</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/search-marketing">Search Marketing</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>services/lead-generation">Lead Generation</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>bridgend-web-design">Bridgend Web Design</a>
				</li>
				<li>
					<a href="<?php echo $this->webroot?>cardiff-web-design">Cardiff Web Design</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="footer-dropdown">
	<div class="large-container">
		<div id="accordion">
			<h3 class="accordion-toggle">Team 8</h3>
			<div class="accordion-content">
				<ul>
					<li>
						<a href="<?php echo $this->webroot?>">Home</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>who-we-are">Who We Are</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>our-work">Our Work</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>get-in-touch">Get In Touch</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>standardtermsofservices.pdf">Terms Of Service</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>feedback">Feedback</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>careers">Careers</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>blog">Blog</a>
					</li>
				</ul>
			</div>
			<h3 class="accordion-toggle">Development</h3>
			<div class="accordion-content">
				<ul>
					<li>
						<a href="<?php echo $this->webroot?>services/web-design">Web Design</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/ecommerce">Ecommerce</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/hosting">Hosting</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/cs-cart">CS Cart Reseller</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/app-development">App Development</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/rightmove-api">Rightmove API</a>
					</li>
				</ul>
			</div>
			<h3 class="accordion-toggle">Services</h3>
			<div class="accordion-content">
				<ul>
					<li>
						<a href="<?php echo $this->webroot?>services/support">Tech Support</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/social-media">Social Media</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/cyber-security">Cyber Security</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/search-marketing">Search Marketing</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>services/lead-generation">Lead Generation</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>bridgend-web-design">Bridgend Web Design</a>
					</li>
					<li>
						<a href="<?php echo $this->webroot?>cardiff-web-design">Cardiff Web Design</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    $(document).ready(function ($) {
        $('#accordion').find('.accordion-toggle').click(function () {

            //Expand or collapse this panel
            $(this).next().slideToggle('fast');

            //Hide the other panels
            $(".accordion-content").not($(this).next()).slideUp('fast');

        });
    });

</script>

<div class="menu-button-container" id="menu-toggle">
	<span class="top"></span>
	<span class="middle"></span>
	<span class="bottom"></span>
</div>

<div class="overlay" id="menu-overlay">
	<nav class="overlay-menu">
		<ul>
			<li >
				<a href="<?php echo $this->webroot?>">Home</a>
			</li>
			<li>
				<a href="<?php echo $this->webroot?>who-we-are">Who We Are</a>
			</li>
			<li>
				<a href="<?php echo $this->webroot?>what-we-do">What We Do</a>
			</li>
			<li>
				<a href="<?php echo $this->webroot?>our-work">Our Work</a>
			</li>
			<li>
				<a href="<?php echo $this->webroot?>get-in-touch">Get In Touch</a>
			</li>
			<li>
				<a href="<?php echo $this->webroot?>services/web-design">Get A Quote</a>
			</li>
		</ul>
	</nav>
</div>

<script>

$('#menu-toggle').click(function() {
	$(this).toggleClass('active');
	$('#menu-overlay').toggleClass('open');
});

</script>

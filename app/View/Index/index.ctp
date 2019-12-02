<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="home-content large-container">
					<div class="sts-intro">
						<h1><strong>Team 8 Digital</strong></h1>
						<h2>Your door to world wide business opportunities</h2>
					</div>
					<div class="intro-award">
						<a href="http://cssreel.com/Website/sts-online" target="_blank">
							<img src="<?php echo $this->webroot?>img/awards/reel-02.png" alt="CSS Reel Nominee" />
						</a>
					</div>
					<?php echo $this->Element('services');?>
				</div>
				<div class="index-feature">
					<div class="large-container">
						<h2><a href="<?php echo $this->webroot?>our-work">Team 8 Workshop</a></h2>
						<h3>A selection of our recent projects</h3>
						<ul class="feature-list">
							<li class="feature-item">
								<div class="project-box project-hackathon">
		  							<a href="<?php echo $this->webroot?>bnp-paribas-hackathon">
		  								<div class="box-overlay">
		    								<h4 class="project-title">BNP Paribas</h4>
		    								<span class="project-info">Hackathon</span>
		    								<p class="light-btn">
												<span>See Project</span>
											</p>
		  								</div>
		  							</a>
								</div>
							</li>
							<li class="feature-item">
								<div class="project-box project-venue">
		  							<a href="<?php echo $this->webroot?>workshop/penybont">
		  								<div class="box-overlay">
		    								<h4 class="project-title">The Venue</h4>
		    								<span class="project-info">Web Design</span>
		    								<p class="light-btn">
												<span>See Project</span>
											</p>
		  								</div>
		  							</a>
								</div>
							</li>
							<li class="feature-item">
								<div class="project-box project-iw">
		  							<a href="<?php echo $this->webroot?>workshop/independent-women">
		  								<div class="box-overlay">
		    								<h4 class="project-title">Independent</h4>
		    								<span class="project-info">Web Design</span>
		    								<p class="light-btn">
												<span>See Project</span>
											</p>
		  								</div>
		  							</a>
								</div>
							</li>
							<li class="feature-item">
								<div class="project-box project-api">
		  							<a href="<?php echo $this->webroot?>services/rightmove-api">
		  								<div class="box-overlay">
		    								<h4 class="project-title">Rightmove API</h4>
		    								<span class="project-info">App Development</span>
		    								<p class="light-btn">
												<span>See Project</span>
											</p>
		  								</div>
		  							</a>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="large-container">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Specialists</h2>
						<div class="introduction">
							<p>At Team 8 we transform industries with innovative products and services. Designed from the ground up, we engage consumers in the way and matter that they want to be engaged with. It is important to us that we increase operational efficiencies for you and your business.</p>
							<p>We offer bespoke, professional and engaging online experiences for companies and organizations looking for a functional and elegant solution, whether this be through: App Development, Web Design, Hosting or Search Marketing we offer the solution that is right for you and your business.</p>
							<p>Are you struggling to find the right solution and market for your company? Let us help you succeed. At Team 8 we pride ourselves on our exceptional technical support and our specialists are always eager to help. Getting in touch with us couldn't be easier as we are only an email or phone call away.</p>
							<p>Team 8 are based in Bridgend, Wales. From web design to hosting, tech support and marketing Team 8 have the solution that is right for you. With the right knowledge and experience we can multiply your sales by one hundred and cut your operational expenses to the bare minimum.</p>
						</div>
					</div>
				</div>

				<div class="grid feedback-highlight">
					<div class="large-container">
						<div class="container-left">
							<h2>Rightmove API</h2>
							<p>Team 8 can provide Rightmove API integration for your website. Using various techniques and languages such as PHP, Java or .NET, we can work closely with you to ensure your Rightmove API meets your business needs.</p>
							<span>Paul Whyatt</span>
							<span><i><strong>Team 8</strong></i></span>
							<a href="<?php echo $this->webroot?>services/rightmove-api" class="dark-btn">View Project</a>
						</div>
						<div class="container-right">
							<a title="Learn More" href="<?php echo $this->webroot?>services/rightmove-api">
								<img alt="Physique" src="<?php echo $this->webroot?>img/homepage/right-screen.png" />
							</a>
						</div>
					</div>
				</div>

				<div class="large-container">
					<div class="blog-banner">

						<div class="element"></div>

						<div id="owl-example" class="owl-carousel">

							<?php foreach($posts as $post) { ?>
						  <div>
								<div class="index-blog-wrap">
									<h3><i class="fa fa-calendar" aria-hidden="true"></i><?php echo date("j M Y", strtotime($post['Post']['created'])); ?></h3>

									<h2><?php echo $post['Post']['title']; ?></h2>

									<p><?php echo substr(strip_tags($post['Post']['body']), 0, 400)."..."; ?></p>

									<a class="dark-btn" href="<?php echo $this->webroot."posts/view/".$post['Post']['title']; ?>">Read More</a>

								</div>
							</div>
							<?php } ?>

						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
	!function($){

	  "use strict";

	  var Typed = function(el, options){

		  // for variable scope's sake
		  self = this;

		  // chosen element to manipulate text
		  self.el = $(el);
		  // options
		  self.options = $.extend({}, $.fn.typed.defaults, options);

		  // text content of element
		  self.text = self.el.text();

		  // typing speed
		  self.typeSpeed = self.options.typeSpeed;

		  // amount of time to wait before backspacing
		  self.backDelay = self.options.backDelay;

		  // input strings of text
		  self.strings = self.options.strings;

		  // character number position of current string
		  self.strPos = 0;

		  // current array position
		  self.arrayPos = 0;

		  // current string based on current values[] array position
		  self.string = self.strings[self.arrayPos];

		  // number to stop backspacing on.
		  // default 0, can change depending on how many chars
		  // you want to remove at the time
		  self.stopNum = 0;

		  // number in which to stop going through array
		  // set to strings[] array (length - 1) to stop deleting after last string is typed
		  self.stopArray = self.strings.length;

		  // All systems go!
		  self.init();
	  }

		  Typed.prototype =  {

			  constructor: Typed

			  , init: function(){
				  // begin the loop w/ first current string (global self.string)
				  // current string will be passed as an argument each time after this
				  self.typewrite(self.string, self.strPos);
				  self.el.after("<span id=\"typed-cursor\"></span>");
			  }

			  // pass current string state to each function
			  , typewrite: function(curString, curStrPos){

				  // varying values for setTimeout during typing
				  // can't be global since number changes each time loop is executed
				  var humanize = Math.round(Math.random() * (100 - 30)) + self.typeSpeed;

				  // ------ optional ------ //
				  // custom backspace delay
				  // if (self.arrayPos == 1){
				  // 	self.backDelay = 50;
				  // }
				  // else{ self.backDelay = 500; }

				  // containg entire typing function in a timeout
				  setTimeout(function() {

					  // make sure array position is less than array length
					  if (self.arrayPos < self.strings.length){

						  // start typing each new char into existing string
						  // curString is function arg
						  self.el.text(self.text + curString.substr(0, curStrPos));

						  // check if current character number is the string's length
						  // and if the current array position is less than the stopping point
						  // if so, backspace after backDelay setting
						  if (curStrPos > curString.length && self.arrayPos < self.stopArray){
							  clearTimeout();
							  setTimeout(function(){
								  self.backspace(curString, curStrPos);
							  }, self.backDelay);
						  }

						  // else, keep typing
						  else{
							  // add characters one by one
							  curStrPos++;
							  // loop the function
							  self.typewrite(curString, curStrPos);
							  // if the array position is at the stopping position
							  // finish code, on to next task
							  // if (self.arrayPos == self.stopArray && curStrPos == curString.length){
							  // 	// animation that occurs on the last typed string
							  // 	// place any finishing code here
							  // 	self.options.callback();
							  // 	clearTimeout();
							  // }
						  }
					  }
					  else{
						  self.arrayPos = 0;
						  self.typewrite(self.string, self.strPos);
					  }

				  // humanized value for typing
				  }, humanize);

			  }

			  , backspace: function(curString, curStrPos){

				  // varying values for setTimeout during typing
				  // can't be global since number changes each time loop is executed
				  var humanize = Math.round(Math.random() * (100 - 30)) + self.typeSpeed;

				  setTimeout(function() {

						  // ----- this part is optional ----- //
						  // check string array position
						  // on the first string, only delete one word
						  // the stopNum actually represents the amount of chars to
						  // keep in the current string. In my case it's 14.
						  // if (self.arrayPos == 1){
						  //	self.stopNum = 14;
						  // }
						  //every other time, delete the whole typed string
						  // else{
						  //	self.stopNum = 0;
						  // }

					  // ----- continue important stuff ----- //
						  // replace text with current text + typed characters
						  self.el.text(self.text + curString.substr(0, curStrPos));

						  // if the number (id of character in current string) is
						  // less than the stop number, keep going
						  if (curStrPos > self.stopNum){
							  // subtract characters one by one
							  curStrPos--;
							  // loop the function
							  self.backspace(curString, curStrPos);
						  }
						  // if the stop number has been reached, increase
						  // array position to next string
						  else if (curStrPos <= self.stopNum){
							  clearTimeout();
							  self.arrayPos = self.arrayPos+1;
							  // must pass new array position in this instance
							  // instead of using global arrayPos
							  self.typewrite(self.strings[self.arrayPos], curStrPos);
						  }

				  // humanized value for typing
				  }, humanize);

			  }

		  }

	  $.fn.typed = function (option) {
		  return this.each(function () {
			var $this = $(this)
			  , data = $this.data('typed')
			  , options = typeof option == 'object' && option
			if (!data) $this.data('typed', (data = new Typed(this, options)))
			if (typeof option == 'string') data[option]()
		  });
	  }


	}(window.jQuery);


	$(function () {

			  $(".sts-typed").typed({
				  strings: ["Designing Your Potential", "We Do Web Design", "We Do App Development", "Get In Touch With Us", "Bridgend Web Design"],
				  typeSpeed: 70, // typing speed
				  backDelay: 2000, // pause before backspacing
				  callback: function () { $(this) }
			  });
	});
</script>

<script>

$("#owl-example").owlCarousel({

    // Most important owl features
    singleItem : true,

    //Basic Speeds
    slideSpeed : 800,
    paginationSpeed : 800,
    rewindSpeed : 800,

    //Autoplay
    autoPlay : true,

    // Navigation
    navigation : true,
    navigationText : ["<i class='fa fa-long-arrow-left' aria-hidden='true'></i>","<i class='fa fa-long-arrow-right' aria-hidden='true'></i>"],
    rewindNav : true,
    scrollPerPage : false,

    //Pagination
    pagination : false,

    // Responsive
    responsive: true,
    responsiveRefreshRate : 200,
    responsiveBaseWidth: window,

    // CSS Styles
    baseClass : "owl-carousel",
    theme : "owl-theme",

    //Lazy load
    lazyLoad : false,
    lazyFollow : true,
    lazyEffect : "fade",

    //Auto height
    autoHeight : false,

    //JSON
    jsonPath : false,
    jsonSuccess : false,

    //Mouse Events
    dragBeforeAnimFinish : true,
    mouseDrag : true,
    touchDrag : true,

    //Transitions
    transitionStyle : false,

    // Other
    addClassActive : false,

    //Callbacks
    beforeUpdate : false,
    afterUpdate : false,
    beforeInit: false,
    afterInit: false,
    beforeMove: false,
    afterMove: false,
    afterAction: false,
    startDragging : false,
    afterLazyLoad : false

})

</script>

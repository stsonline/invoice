<?php
	$this->set('title_for_layout', 'Team 8: Web Design Specialists');
	$this->set('description_for_layout', 'Team 8: the home of web design. First impressions definitely count and a company that has a consistent design will always make a lasting impression.');
?>

<div id="wrapper" class="page-wrapper-<?php echo $this->request->params['action']?>">
	<div class="page-content intro-button"></div>
	<div class="grid">
		<div class="left-block">
			<h3 class="key-steps">We Make Websites</h3>
			<div class="design-block">
				<h3>Affordable Web Design</h3>
				<img src="<?php echo $this->webroot?>img/design/physique.png" alt="Web Design Bridgend">
				<ul>
					<li>Websites for all budgets &amp; timescales</li>
					<li><a href="https://en.wikipedia.org/wiki/Responsive_web_design" target="_blank">Mobile ready</a> as standard</li>
					<li>Personal UK service</li>
				</ul>
			</div>
		</div>
		<div class="left-block">
			<h3 class="key-steps examples-title">Examples Of Our Work</h3>
			<div class="examples">
				<ul>
					<li>
						<div id="slide1" class="slide hifi-slide">
							<div class="slide-title">
								<h4>Life In HIFI</h4>
								<span>Design &#183; Branding &#183; Ecommerce</span>
								<p>Sleek modern design, showcasing the company&#39;s product range and encouraging users to interface with the Life In Hifi iOS app.</p>
								<a class="light-btn" href="<?php echo $this->webroot?>workshop/life-in-hifi" target="_blank">See details</a>
							</div>
						</div>
					</li>
					<li>
						<div id="slide1" class="slide laguz-slide">
							<div class="slide-title">
								<h4>Julia Laguz</h4>
								<span>Branding &#183; Design</span>
								<p>A clean and elegantly designed website that provides a platform to highlight the client&#39;s skills and knowledge.</p>
								<a class="light-btn" href="<?php echo $this->webroot?>workshop/julia-laguz" target="_blank">See details</a>
							</div>
						</div>
					</li>
					<li>
						<div id="slide2" class="slide ihunt-slide">
							<div class="slide-title">
								<h4>iHunt Calls</h4>
								<span>Branding &#183; Design &#183; App Development</span>
								<p>Designed to advertise the popular iOS &amp; Android app. The site incorporates a range of interactive features to engage visitors.</p>
								<a class="light-btn" href="<?php echo $this->webroot?>workshop/ihunt-calls" target="_blank">See details</a>
							</div>
						</div>
					</li>
					<li>
						<div id="slide3" class="slide physique-slide">
							<div class="slide-title">
								<h4>Physique</h4>
								<span>Design &#183; Lead Generation</span>
								<p>Beautiful, functional brochure site for an industry leading gym franchise. Integrated with direct debit payment system, deeply linked with the gym&#39;s Facebook and Twitter accounts, giving members access to the gym&#39;s customer service team.</p>
								<a class="light-btn" href="<?php echo $this->webroot?>workshop/physique" target="_blank">See details</a>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="large-container">
		<h3 class="key-steps step-padded">Prefer To Talk To Us?</h3>
		<div class="contact-us grid">
			<div class="contact-methods">
				<div class="option call-us">
					<a href="tel:01656513046">01656 513 046</a>
				</div>
				<div class="option email-us">
					<a href="mailto:hello@team8digital.uk">hello@team8digital.uk</a>
				</div>
			</div>
		</div>
	</div>
	<div class="large-container">
		<div id="begin" class="quote-intro">
			<h3 class="key-steps step-padded">Tailor Your Quote</h3>
			<h4> Tell us a little more about your project to view an estimate of the cost, based on other projects we&#39;ve worked on.</h4>
		</div>
		<div id="quote-builder" class="grid stickem-container">
			<form id="quote-builder-form" method="POST" action="<?php echo $this->webroot?>services/web-design-step-2">
				<input type="hidden" name="quote[type]" value="web design" />
				<input id="quote-amount" type="hidden" name="data[quote][amount]">
				<div class="quote-form skin-flat">
					<?php foreach($quoteItems as $type => $items) { ?>
					<div id="<?php echo $type; ?>-container" class="quote-group">
						<h2><?php echo $typeNames[$type]; ?></h2>
						<ul class="grid">
						<?php foreach($items as $key=>$item) { ?>
							<li>
								<input type="<?php echo $item['input']; ?>" <?php if($item['input'] == 'radio') { echo 'name="quote[items]['.$type.']"'; } else { echo 'name="quote[items]['.$type.'][]"'; } ?> id="<?php echo $type.'-'.$item['id']; ?>" value="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>" <?php if($item['input'] == 'radio') { echo 'required="required"'; } ?>/>
								<label for="<?php echo $type; ?>-<?php echo $item['id']; ?>"><?php echo $item['name']; ?></label>
								<p><?php echo $item['description']; ?></p>
							</li>
						<?php } ?>
						</ul>
					</div>
					<?php } ?>
				</div>
				<div class="quote-container">
					<h5>Prices starting from approx:</h5>
					<div class="stickem">
						<span class="estimate-title">Quote estimate:</span>
						<span id="quote-price"></span>
					</div>
					<h5>Quote Summary</h5>
					<div class="quote-choices"></div>
					<div class="quote-example">
						<p>We built this quote system to help our customers understand how different features and requirements affect the cost of their website project. The costs are representative, and we recommend getting in touch to discuss your options fully. <a href="#terms">Terms &amp; conditions</a> apply.</p>
					</div>
				</div>
				<div class="submit-section">
					<input id="submit" type="submit" class="blue-btn" value="Get Quote">
				</div>
			</form>
		</div>
	</div>
	<div class="ribbon">
		<h2>Still undecided? Keep scrolling</h2>
	</div>
	<div class="testimonials">
		<div class="large-container grid">
			<h3 class="key-steps">What Our Clients Say</h3>
			<div class="quotation">
				<img src="<?php echo $this->webroot?>img/design/quote-icon.png" alt="Client Quotes">
			</div>
			<div class="quote-carousel">
				<div class="client-block">
					<div class="client-quote">
						<p>I highly recommend this vendor due to their dilligence in completing projects in a timely manner. As a small business person being able to trust a supplier will meet their commitments, both financial (on or below budget) and quality, is critical.</p>
					</div>
					<div class="client-details">
						<h4>Gary Lemanski</h4>
						<a>President, Altus Brands LLC</a>
					</div>
				</div>
				<div class="client-block">
					<div class="client-quote">
						<p>The team are a goldmine of customer service, expertise and talent! They deliver every bit of what they promise in a timely, professional and competent manner. Overall, we have had an outstanding experience with Team 8 and would highly recommend them to anyone.</p>
					</div>
					<div class="client-details">
						<h4>Jennifer Altschuler</h4>
						<a>Pyramid Time Systems</a>
					</div>
				</div>
				<div class="client-block">
					<div class="client-quote">
						<p>The team at STS designed the website for both my businesses, and I have to say I think they have done an outstanding job, their professionalism and customer service is second to none and I will continue to do business with them. I would absolutely recommend STS to anyone.</p>
					</div>
					<div class="client-details">
						<h4>Michael Smith</h4>
						<a>Pursuit Outdoor &amp; Military Supplies Ltd</a>
					</div>
				</div>
				<div class="client-block">
					<div class="client-quote">
						<p>Excellent! Project briefing, design consultation, suggestions, site construction, deployment etc have all gone very smoothly. Their service, attention to detail, helpfulness and budget management has been outstanding. A huge relief after some difficult web design experiences in the past.</p>
					</div>
					<div class="client-details">
						<h4>Jennifer Davy</h4>
						<a>Indepedent Women</a>
					</div>
				</div>
				<div class="client-block">
					<div class="client-quote">
						<p>STS have provided me with a first class service for an affordable price. They really are hugely knowledgeable and I haven&#39;t been able to throw anything at them to which they couldn&#39;t provide an answer.</p>
					</div>
					<div class="client-details">
						<h4>Jon Horsfield</h4>
						<a>Jaydee Living</a>
					</div>
				</div>
				<div class="client-block">
					<div class="client-quote">
						<p>All round good guys and great to work with. Solved a really time sensitive and business critical problem for us very quickly and helped us every stage of the way.</p>
					</div>
					<div class="client-details">
						<h4>Gary Dickson</h4>
						<a>Scottish Federation of Housing Associations</a>
					</div>
				</div>
				<div class="client-block">
					<div class="client-quote">
						<p>Whilst working with STS I have found them to be extremely proficient in every way, I do not have words strong enough to recommend them.</p>
					</div>
					<div class="client-details">
						<h4>Joe Soiza</h4>
						<a>MDMJ Consultants Ltd</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="large-container">
		<div class="tools-list grid">
			<h3 class="key-steps">Tools We Like</h3>
			<div class="sts-tool">
				<a href="https://wordpress.org/" target="_blank">
					<img src="<?php echo $this->webroot?>img/gallery/wordpress-logo.png" alt="Wordpress">
					<h3>WordPress</h3>
				</a>
				<span>We use Wordpress for many medium budget web projects where the customer wants to manage their own content.</span>
			</div>
			<div class="sts-tool">
				<a href="https://magento.com/" target="_blank">
					<img src="<?php echo $this->webroot?>img/gallery/magento-logo.png" alt="Magento">
					<h3>Magento</h3>
				</a>
				<span>We use Magento for large scale ecommerce systems where speed and ease of development flexibility is a factor. Have a look at our <a href="<?php echo $this->webroot?>services/magento">Magento</a> page.</span>
			</div>
			<div class="sts-tool">
				<a href="https://www.joomla.org/" target="_blank">
					<img src="<?php echo $this->webroot?>img/gallery/joomla-logo.png" alt="Joomla">
					<h3>Joomla</h3>
				</a>
				<span>We use Joomla on projects where the customer wants really advanced content management features like version management.</span>
			</div>
			<div class="sts-tool">
				<a href="https://cs-cart.com" target="_blank">
					<img src="<?php echo $this->webroot?>img/gallery/cs-cart-logo.png" alt="CS Cart">
					<h3>CS Cart</h3>
				</a>
				<span>We use CS Cart for startup online shops. It has incredibly easy to use features, and is completely open source. Have a look at our <a href="<?php echo $this->webroot?>services/cs-cart">CS Cart</a> page.</span>
			</div>
			<div class="sts-tool">
				<a href="http://cakephp.org/" target="_blank">
					<img src="<?php echo $this->webroot?>img/gallery/woo-logo.png" alt="Woocommerce">
					<h3>Woocommerce</h3>
				</a>
				<span>We use WooCommerce for simple sales websites where there is no requirement for complex features. It is royalty-free making it a great choice for proof of concept projects.</span>
			</div>
		</div>
	</div>
	<div class="return">
		<a href="#begin" class="dark-btn">
			<span>Get Started</span>
		</a>
	</div>
	<div id="terms" class="large-container">
		<div class="terms-list">
			<h3 class="key-steps">Terms &amp; Conditions</h3>
			<ol>
				<li>The prices displayed are representative examples based on previous web design projects. For an accurate quote we recommend getting in touch with us to discuss your project in more detail.</li>
				<li>If you are interested in selling online we suggest arranging a consultation to discuss the features required from an ecommerce system. We haven&#39;t included a price for the ecommerce option as certain systems require a licence and may have additional add-ons.</li>
				<li>We have included a design consultation by default as it allows us to get a clearer understanding of what it is you require.</li>
			</ol>
		</div>
	</div>
	<div class="page-feature design-feature">
		<div class="other-pages">
			<section class="page-navigation">
				<div class="large-container page-intro">
					<h2>Web Design</h2>
					<p>"Design is a funny word. Some people think design means how it looks. But of course, if you dig deeper, it&#39;s really how it works."</p>
					<p class="quote">- Steve Jobs</p>
				</div>
				<div class="full-nav">
					<nav class="nav-arrows">
						<a class="prev" href="<?php echo $this->webroot?>services/cyber-security">
							<span class="icon-wrap"></span>
							<h3><span class="strong">Cyber</span> Security</h3>
						</a>
						<a class="next" href="<?php echo $this->webroot?>services/ecommerce">
							<span class="icon-wrap"></span>
							<h3><span class="strong">Ecommerce</span> Projects</h3>
						</a>
					</nav>
				</div>
			</section>
		</div>
	</div>
	<div class="small-nav">
		<div class="large-container">
			<nav class="nav-arrows">
				<a class="prev" href="<?php echo $this->webroot?>services/cyber-security">
					<span class="icon-wrap"></span>
					<h3><span class="strong">Cyber</span> Security</h3>
				</a>
				<a class="next" href="<?php echo $this->webroot?>services/ecommerce">
					<span class="icon-wrap"></span>
					<h3><span class="strong">Ecommerce</span> Projects</h3>
				</a>
			</nav>
		</div>
	</div>
</div>

<script type="text/javascript">

var callbacks_list = $('.quote-choices');

$(document).ready(function(){

	$('.examples ul').slippry({
		controls: false,
		captions: false,
		pause: 8000
	});
	$('.skin-flat input').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
      });
	$('.blue-btn').click(function(){
	    $('html, body').animate({
	        scrollTop: $( $(this).attr('href') ).offset().top
	    }, 600);
	    return false;
	});
	$('.dark-btn').click(function(){
	    $('html, body').animate({
	        scrollTop: $( $(this).attr('href') ).offset().top
	    }, 600);
	    return false;
	});

	$('.quote-carousel').slick({
		dots: true,
		infinite: true,
		speed: 500,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});

	$('#quote-builder').stickem();

	$('#quote-builder input[type=radio],#quote-builder input[type=checkbox]').on('ifChanged', function() {
		buildQuote();

        if($(this).is("input[type=radio]") && $(this).is(':checked')) {
        	$(".website-type").remove();
            $(".website-type-div").prepend("<p class='website-type'>"+$(this).parent().next().html()+"</p>")
        }

        if($(this).is("input[type=checkbox]") && $(this).is(':checked')) {
            $(".feature").remove();
            $(".feature-div").prepend("<p class='feature'>"+$(this).parent().next().html()+"</p>");
        }
   	});

	$('.quote-form input').on('ifChecked', function(event){
		allToList(this);
	});
	$('.quote-form input').on('ifUnchecked', function(event){
		$("#" + this.id + "-list").remove();
	});

	//Static Site pre-checked
	$('#backend-1').parent().addClass('checked');
	$('#backend-1').attr("checked", "checked");

	//Responsive Design pre-checked
	$('#features-8').parent().addClass('checked');
	$('#features-8').attr("checked", "checked");

	//force Responsive Design to be checked
	$('#features-8').iCheck('disable');

	//Design consultation pre-checked
	$('#extras-14').parent().addClass('checked');
	$('#extras-14').attr("checked", "checked");

	$('.quote-form input').each(function(index) {
		if($(this).is(':checked')) {
			allToList(this);
		}
	});

	buildQuote();
});

function allToList(object) {
	callbacks_list.prepend('<p id="' + object.id + '-list">' + $('label[for=' + object.id + ']').html() + '</p>');
}

function buildQuote() {
	var quoteAmount = 49.00;
	var quoteString = "";

	$('#quote-builder input[type=radio],#quote-builder input[type=checkbox]').each(function(index, value) {
		if($(this).is(':checked')) {

			quoteAmount += parseFloat($(this).attr('data-price'));
		}
	});

	if(quoteAmount > 0.00) {
		quoteString = "&pound;" + quoteAmount;
	}

	$('#quote-amount').val(quoteAmount);

	$('#quote-price').html(quoteString);
}

</script>

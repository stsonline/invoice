
<div class="category-menu float-left">
	<?php echo $this->element('about_menu');?>

	<div class="clear">&nbsp;</div>
</div>

<div id="wide-content" class="box-style1">
	<div class="page-header generic-header">
		<h2 class="title">Contact Us</h2>
		<p class="subtitle">Let us help you and your business succeed</p>
	</div>

	<div class="services-tile-view">

		<h2 class="margin-top-bottom-10">Select how you'd like to get in touch:</h2>
			<a href="<?php echo $this->webroot?>contact#quote">
			<div id="getQuote" class="quote-tile common-tile cursor-pointer">
				<h3>Request A Quotation</h3>
			</div>
			</a>
			<a href="<?php echo $this->webroot?>contact#contact">
			<div id="phoneEmail" class="contact-tile common-tile cursor-pointer">
				<h3>Phone or email us</h3>
			</div>
			</a>
			<a href="<?php echo $this->webroot?>contact#feedback">
			<div id="leaveFeedback" class="feedback-tile common-tile cursor-pointer">
				<h3>Leave feedback</h3>
			</div>
			</a>
		<div class="clear">&nbsp;</div>
	</div>



	<div class="hidden" id="quoteContainer">
		<div style="width: 480px;" class="general-form center">
			<h3 class="border-bottom">Your Details</h3>
			<form id="quote-form" action="" method="POST">
			<input type="hidden" name="requestType" value="quote-request" />

			<div class="form-field">
				<label for="">Regarding</label>
				<select id="signupOptions" name="signupOptions">
					<option selected="yes" value="">--- Please select ---</option>
					<option value="Ecommerce">Ecommerce</option>
					<option value="Magento">Magento</option>
					<option value="CS Cart">CS Cart</option>
					<option value="Hosting">Hosting</option>
					<option value="Marketing">Marketing</option>
					<option value="PPC Management">PPC Management</option>
					<option value="SEO Services">SEO Services</option>
					<option value="Affiliate Marketing">Affiliate Marketing</option>
					<option value="Analytics & Reportin">Analytics & Reporting</option>
				</select>
			</div>

			<div class="form-field"><label for="signupTextTitle">Title <span class="asterisk">*</span></label> <select id="signupTextTitle" name="signupTextTitle"><option selected="selected" value="">--- Please select ---</option><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Miss">Miss</option></select></div>

			<div class="form-field">
				<label for="">First name <span class="asterisk">*</span></label>
				<input type="text" name="firstname" id="firstname"/>
			</div>

			<div class="form-field">
				<label for="">Last name <span class="asterisk">*</span></label>
				<input type="text" name="lastname" id="lastname"/>
			</div>

			<div class="form-field">
				<label for="">Email <span class="asterisk">*</span></label>
				<input type="text" name="email" id="email"/>
			</div>

			<div class="form-field">
				<label for="">Phone number</label>
				<input type="text" name="phone" id="phone"/>
			</div>

			<div class="form-field">
				<label for="">Company name <span class="asterisk">*</span></label>
				<input type="text" name="companyname" id="companyname"/>
			</div>

			<div class="form-field">
				<label for="">Position</label>
				<input type="text" name="position" id="position"/>
			</div>

			<div class="form-field">
				<label for="referralSource">Where did you hear about us? </label>
				<select id="referralSource" name="referralSource">
					<option selected="selected" value="">--- Please select ---</option>
					<option value="Search Engine">Search Engine</option>
					<option value="Personal Referral">Personal Referral</option>
					<option value="Supplier Directory">Supplier Directory</option>
					<option value="Other">Other</option>
				</select>
			</div>
			<div class="margin-bottom-10 clear">&nbsp;</div>
			<h3 class="border-bottom">Tell us about the project</h3>


			<div class="form-field">
				<label for="">Project name <span class="asterisk">*</span></label>
				<input type="text" name="projectname" id="projectname"/>
			</div>

			<div class="form-field">
				<label for="">Brief description</label>
				<textarea name="projectdescription" id="projectdescription"></textarea>
			</div>

			<div style="width: 200px;" class="center padding-top-10">
			<span id="submitQuoteBtn" class="button_example" style="cursor:pointer;">Send Request</span>
			</form>
			</div>

		</div>
	</div>
	<div class="hidden" id="contactContainer">
		<div style="width: 220px;" class="general-form center float-left margin-left-10">
			<h3 class="border-bottom">Call Us</h3>
			<p>+44 (0)1656 714 462</p>


			<h3 class="border-bottom">Email Us</h3>
			<p>hello@team8digital.uk</p>

		</div>
		<div style="width: 340px;" class="general-form center float-right margin-right-10">
			<h3 class="border-bottom">Visit Us</h3>
			<p>26 Dunraven Place, Bidgend Town Centre, Bridgend, CF31 1JD, United Kingdom</p>
			<p><a target="_blank" href="http://goo.gl/maps/sMOTX">Map >></a></p>
			<p>Suite 5386, 17b Farnham Street, Parnell, Auckland, 1052, New Zealand</p>
			<p><a href="http://goo.gl/maps/tiv9F" target="_blank">Map >></a></p>
		</div>

	</div>
	<div class="hidden" id="feedbackContainer">
		<div style="width: 480px;" class="general-form center">
			<h3 class="border-bottom">We love feedback</h3>
			<form id="feedback-form" action="" method="POST">
			<input type="hidden" name="requestType" value="feedback-request" />
				<div class="form-field"><label for="signupTextTitle">Title <span class="asterisk">*</span></label> <select id="signupTextTitle-feedback" name="signupTextTitle-feedback"><option selected="selected" value="">--- Please select ---</option><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Miss">Miss</option></select></div>

				<div class="form-field">
					<label for="">First name <span class="asterisk">*</span></label>
					<input type="text" id="firstname-feedback" name="firstname-feedback" />
				</div>

				<div class="form-field">
					<label for="">Last name <span class="asterisk">*</span></label>
					<input type="text" id="lastname-feedback" name="lastname-feedback" />
				</div>

				<div class="form-field">
					<label for="">Email <span class="asterisk">*</span></label>
					<input type="text" id="email-feedback" name="email-feedback" />
				</div>

				<div class="form-field">
					<label for="">Comments <span class="asterisk">*</span></label>
					<textarea id="feedbackdescription" name="feedbackdescription"></textarea>
				</div>

				<div style="width: 180px;" class="center padding-top-10">
				<span id="submitFeedbackBtn" class="button_example" style="cursor:pointer;">Submit</span>
				</div>
			</form>

		</div>
	</div>

	<script>

	$('#submitQuoteBtn').click(function()
	{
		if(validatorQuote.form())
		{
			var firstname = $('#firstname').val();
			var lastname = $('#lastname').val();
			var email = $('#email').val();
			var title = $('#signupTextTitle').val();
			var phone = $('#phone').val();
			var companyname = $('#companyname').val();
			var position = $('#position').val();
			var referralSource = $('#referralSource').val();
			var projectname = $('#projectname').val();
			var projectdescription = $('#projectdescription').val();

			signup(firstname, lastname, email, title);
			result = sendForm(firstname, lastname, email, title, phone, companyname, position, referralSource, projectname, projectdescription);
			success = typeof result.success != 'undefined' ? result.success : false;

			if(success)
			{
				$('#quoteContainer div').html("<h3 class='border-bottom'>Thanks for your request</h3><p>A member of our customer support team will be in touch shortly.</p>");
			}
		}
	});


	$('#submitFeedbackBtn').click(function()
	{
		if(validatorFeedback.form())
		{
			var firstname = $('#firstname-feedback').val();
			var lastname = $('#lastname-feedback').val();
			var email = $('#email-feedback').val();
			var title = $('#signupTextTitle-feedback').val();
			var comments = $('#feedbackdescription').val();

			//signup(firstname, lastname, email, title);
			result = sendForm(firstname, lastname, email, title, comments);
			success = typeof result.success != 'undefined' ? result.success : false;

			if(success)
			{
				$('#feedbackContainer div').html("<h3 class='border-bottom'>Thanks for your feedback</h3><p>If you've asked a question a member of our customer support team will be in touch shortly.</p>");
			}
		}
	});


	/*
	*	Send all arguments to contact email address
	*/
	function sendForm()
	{
		var json;
		var jqxhr = $.ajax(
	    {
	          type: 'POST',
	          url: "<?php echo $this->webroot?>contact/send",
	          data: arguments/*{'email': email, 'first_name': firstname, 'last_name': lastname, 'title':title, 'comments':comments}*/,
	          async: false
	    })
	    .success(function(result)
	    {
	    	json = result;
	    })
	    .error(function()
	    {
	        //alert("error");
	    })
	    .complete(function() { });

		return eval('(' + json + ')');
	}


	function signup(firstname, lastname, email, title)
	{
		//alert('sending: '+ firstname + ' ' + lastname + ' ' + mobilephone + ' ' + email);
		var json;
		var jqxhr = $.ajax(
	    {
	          type: 'GET',
	          url: "http://partners.skya.co/include/image",
	          data: {'email': email, 'first_name': firstname, 'last_name': lastname, 'sub_id':'999', 'source':'stsonline', 'title':title, 'aff_id':'tlp1680'},
	          async: false
	    })
	    .success(function(result)
	    {
	          pageJSON = result;
	    })
	    .error(function()
	    {
	        //alert("error");
	    })
	    .complete(function() { });

		return eval('(' + json + ')');
	}

	var validatorFeedback, validatorQuote;

	$().ready(function()
	{
		// validate the comment form when it is submitted
		validatorFeedback = $("#feedback-form").validate(
		{
			rules: {
				"firstname-feedback": {required:true},
				"lastname-feedback": {required:true},
				"email-feedback": {required:true, email:true},
				"signupTextTitle-feedback": {required:true},

			},
			messages: {
				//"signupTextEmail": "Required",

			}
		});

		validatorQuote = $("#quote-form").validate(
		{
			rules: {
				"firstname": {required:true},
				"lastname": {required:true},
				"email": {required:true, email:true},
				"signupTextTitle": {required:true},
				"companyname": {required:true},
				"projectname": {required:true},
			},
			messages: {
				//"signupTextEmail": "Required",

			}
		});
	});

	$(document).ready(function()
	{
		var hashQueryString = document.location.hash;
		hashQueryString = hashQueryString.replace("#","");

		if(hashQueryString!='')
		{
			var op = hashQueryString.split("|");
			var op1 = typeof op[1] == 'undefined' ? "" : op[1];

			switch(op[0])
			{
				case 'quote':

					//seperate everything to right of '|'
					//var op = hashQueryString.split("|");
					//var op1 = typeof op[1] == 'undefined' ? "" : op[1];
					selectQuote(op1);
					break;

				case 'contact':
					selectContact();
					break;

				case 'feedback':
					selectFeedback();
					break;
			}
		}
	});


	$('#getQuote').click(function()
	{
		selectQuote("");
	});

	$('#phoneEmail').click(function()
	{
		selectContact();
	});

	$('#leaveFeedback').click(function()
	{
		selectFeedback();
	});


	function selectFeedback()
	{
		$('#getQuote').removeClass('quote-selected');
		$('#phoneEmail').removeClass('contact-selected');
		$('#leaveFeedback').addClass('feedback-selected');

		if($('#feedbackContainer').css('display')!='block')
		{
			$('#quoteContainer').toggle(false);
			$('#contactContainer').toggle(false);
			$('#feedbackContainer').fadeToggle();
		}
	}

	function selectContact()
	{
		$('#getQuote').removeClass('quote-selected');
		$('#phoneEmail').addClass('contact-selected');
		$('#leaveFeedback').removeClass('feedback-selected');

		if($('#contactContainer').css('display')!='block')
		{
			$('#quoteContainer').toggle(false);
			$('#contactContainer').fadeToggle();
			$('#feedbackContainer').toggle(false);
		}
	}

	function selectQuote(operation)
	{
		$('#getQuote').addClass('quote-selected');
		$('#phoneEmail').removeClass('contact-selected');
		$('#leaveFeedback').removeClass('feedback-selected');


		if($('#quoteContainer').css('display')!='block')
		{
			$('#quoteContainer').fadeToggle();
			$('#contactContainer').toggle(false);
			$('#feedbackContainer').toggle(false);
		}

		if(operation != '')
		{
			var new_value = '';

			switch(operation)
			{
				case 'seo': new_value = 'SEO Services';break;
			}

			$("#signupOptions").val(new_value);
		}



	}

	</script>



	<!--
	<p>By email, contact@team8digital.uk</p>
	<p>By phone, 01656 714 462</p>
	<p>Using this form:</p>
		<?php echo $this->Form->create('Contact', array('action' => 'send')); ?>
		<table style="border:none;">
		    <tr>
		        <td>Name</td>
		        <td><?php echo $this->Form->input('Contact.name', array('label' => false, 'maxlength' => 100, 'size' => 40)); ?></td>
		    </tr>
		    <tr>
		        <td>Company</td>
		        <td><?php echo $this->Form->input('Contact.company', array('label' => false, 'maxlength' => 100, 'size' => 40)); ?></td>
		    </tr>
		    <tr>
		        <td>E-Mail</td>
		        <td><?php echo $this->Form->input('Contact.email', array('label' => false, 'maxlength' => 100, 'size' => 40)); ?></td>
		    </tr>
		    <tr>
		        <td style="vertical-align: top;">Your question</td>
		        <td><?php echo $this->Form->input('Contact.message', array('label' => false, 'cols' => 50, 'rows' => 10)); ?></td>
		    </tr>
		    <tr>
		        <td colspan="2" align="center"><br><?php echo $this->Form->end('Submit'); ?></td>
		    </tr>
		</table>

	 -->
</div>

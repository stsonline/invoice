<div class="float-left clear" id="sidebar">
	<div id="box1" class="box-style3">
		<h2 class="title newsletter-title">Signup To Monthly Newsletter</h2>
		<div class="content newsletter">
			
			<p>Please enter your contact details to signup to our monthly newsletter. We won't share your details with any third parties, and you can unsubscribe at any time.</p>
			<form id="newsletter-form" action="" method="GET" novalidate="novalidate">
			<div class="form-field"><label for="signupTextTitle">Title </label> <select id="signupTextTitle" name="signupTextTitle"><option selected="selected" value="">--- Please select ---</option><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Miss">Miss</option></select></div>
			<div class="form-field"><label for="signupTextFirstname">Firstname </label> <input id="signupTextFirstname" name="signupTextFirstname" type="text" placeholder="First Name"/></div>
			<div class="form-field"><label for="signupTextLastname">Lastname </label> <input id="signupTextLastname" name="signupTextLastname" type="text" placeholder="Last Name" /></div>
			<div class="form-field"><label for="signupTextEmail">Email Address </label> <input id="signupTextEmail" name="signupTextEmail" type="text" placeholder="Email Address" /></div>
			</form>
			<div class="margin-bottom-10 clear"></div>
			
			<span id="signupBtn" class="button_example" style="cursor:pointer;">Sign Up</span>
			
		</div>
		<div class="bgbtm"></div>
		<script>
		$('#signupBtn').click(function()
		{
			if(validator.form())
			{
				var first = $('#signupTextFirstname').val();
				var last = $('#signupTextLastname').val();
				var email = $('#signupTextEmail').val();
				var title = $('#signupTextTitle').val();
				
				result = signup(first, last, email, title);
				$('.newsletter-form').html('<h2>Thanks for signing up!</h2>');
			}
		});


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
		
		$.validator.setDefaults({
			//submitHandler: function() { $("#ApplicationIndexForm").validate(); }
		});

		var validator;

		$().ready(function() 
				{
					// validate the comment form when it is submitted
					validator = $("#newsletter-form").validate(
							{
								rules: {
									"signupTextFirstname": {required:true},
									"signupTextLastname": {required:true},
									"signupTextEmail": {required:true, email:true},
									"signupTextTitle": {required:true},
									
								},
								messages: {
									//"signupTextEmail": "Required",
									
								}
							}
					);
				});

		</script>
	</div>
</div>
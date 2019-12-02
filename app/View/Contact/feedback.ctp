<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h2>Thank you for taking the time to provide some feedback</h2>
					</div>
					<div class="page-intro">	
						<p>We read every bit of feedback carefully, so please tell us how we can improve.</p>
					</div>	
					<div id="feedback">
						<?php echo $this->Form->create('Feedback', array('url' => array('controller' => 'contact', 'action' => 'feedback')));?>
						<div class="feedback-column center">	
							<div class="label-container">
								<label>Project Value</label>
								<span>did our fees seem reasonable?</span>
							</div>
							<div class="feedback-input">		
								<?php echo $this->Form->input('Feedback.project_value',array('label' => false, 'type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'), 'empty' => 'true', 'fieldset' => false, 'legend' => false)); ?>
							</div>
						</div>
						<div class="feedback-column center">
							<div class="label-container">
								<label>Customer Service</label>
								<span>were we polite, organised and clear communicators?</span>
							</div>
							<div class="feedback-input">		
								<?php echo $this->Form->input('Feedback.customer_service',array('label' => false, 'type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'), 'empty' => 'true', 'fieldset' => false, 'legend' => false)); ?>
							</div>
						</div>
						<div class="feedback-column center">	
							<div class="label-container">
								<label>Project Timeliness</label>
								<span>did we deliver when we said we would?</span>
							</div>
							<div class="feedback-input">		
								<?php echo $this->Form->input('Feedback.project_timeliness',array('label' => false, 'type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'), 'empty' => 'true', 'fieldset' => false, 'legend' => false)); ?>
							</div>
						</div>
						<div class="feedback-column center">
							<div class="label-container">
								<label>Overall Experience</label>
								<span>would you recommend our services?</span>
							</div>
							<div class="feedback-input">		
								<?php echo $this->Form->input('Feedback.experience',array('label' => false, 'type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'), 'empty' => 'true', 'fieldset' => false, 'legend' => false)); ?>
							</div>
						</div>
						<div>
							<div class="feedback-column">
								<div class="label-container">
									<label for="FeedbackContact">Who you dealt with</label>
									<span>who did you have the pleasure of working with?</span>
								</div>
								<div class="feedback-staff">		
									<?php echo $this->Form->input('Feedback.contact',array('label' => false, 'type' => 'select', 'options' => array('NA' => "I don't know / NA", 'Gareth' => 'Gareth', 'Will' => 'Will', 'Paul' => 'Paul', 'Andrew' => 'Andrew', 'Josh' => 'Josh', 'George' => 'George'), 'empty' => 'Please choose', 'required' => 'required')); ?>
								</div>
							</div>	
						</div>	
						<div class="feedback-column">	
							<div class="label-container">
								<label for="FeedbackName">Your Name</label>
								<span>so we know who to thank later</span>
							</div>
							<div class="input-container">	
								<?php echo $this->Form->input('Feedback.name',array('label' => false, 'type' => 'text', 'placeholder' => 'type your name here', 'required' => 'required')); ?>
							</div>
						</div>
						<div class="feedback-column">	
							<div class="label-container">
								<label for="FeedbackEmail">Email Address</label>
								<span>you can provide your email if you would like us to get in touch</span>
							</div>	
							<div class="input-container">	
								<?php echo $this->Form->input('Feedback.email',array('label' => false, 'type' => 'email', 'placeholder' => 'type your email here')); ?>
							</div>	
						</div>		
						<div class="label-container">
							<label>Comments</label>
							<span>- what did we do for you?  how did it go? how could we improve our service?</span>
						</div>
						<div class="textarea-container">	
							<?php echo $this->Form->input('Feedback.comments',array('label' => false, 'type' => 'textarea', 'placeholder' => 'type your comments here', 'required' => 'required')); ?>
						</div>	
						<div class="label-container">
							<div class="submit-container">
								<?php echo $this->Form->end(array("label"=>"Submit Feedback","class" => 'blue-btn')); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$( document ).ready(function() {
	
	$('#FeedbackExperience').barrating({
		theme: 'fontawesome-stars'
	});
	$('#FeedbackCustomerService').barrating({
		theme: 'fontawesome-stars'
	});
	$('#FeedbackProjectTimeliness').barrating({
		theme: 'fontawesome-stars'
	});
	$('#FeedbackProjectValue').barrating({
		theme: 'fontawesome-stars'
	});

	$('#FeedbackFeedbackForm').submit(function(){
		if(
				$('#FeedbackExperience').val()==''			 ||
				$('#FeedbackCustomerService').val()==''		 ||
				$('#FeedbackProjectTimeliness').val()==''	 ||
				$('#FeedbackProjectValue').val()==''
		){
			alert('Please ensure star ratings for:\n\nOverall Experience\nCustomer Service\nTimeliness\nValue\n\nAre entered and retry.');
			return false;
		}
	});

	
		
});


</script>
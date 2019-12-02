<?php
	$this->set('title_for_layout', 'Team 8: Free Website Review');
	$this->set('description_for_layout', 'We would love to give you our views on your website presence online, simply get in touch with Team 8 today.');
?>
<div id="wrapper" class="page-wrapper-<?php echo $this->request->params['action']?>">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="large-container">
					<div class="website-review">
						<div class="review-intro">
							<h2>Free Website Review</h2>
							<p>We'll give you our views on your presence online, simply get in touch below</p>
						</div>
						<?php echo $this->Form->create('website_review'); ?>
						<div class="field-two clearfix">
							<div class="field column-two">
								<?php echo $this->Form->input('name', array('label' => array("class" => "review-label", "text"=>"Name"), 'div' => "review-name", "placeholder" => "Name")); ?>
							</div>
							<div class="field column-two">
								<?php echo $this->Form->input('company', array('label' => array("class" => "review-label", "text"=>"Company"), 'div' => "review-company", "placeholder" => "Company")); ?>
							</div>
						</div>
						<div class="field-two clearfix">
							<div class="field column-two">
								<?php echo $this->Form->input('tel', array('label' => array("class" => "review-label", "text"=>"Telephone Number"), 'div' => "review-phone", "placeholder" => "Telephone Number")); ?>
							</div>
							<div class="field column-two">
								<?php echo $this->Form->input('email', array('label' => array("class" => "review-label", "text"=>"Email"), 'div' => "review-email", "placeholder" => "Email")); ?>
							</div>
						</div>
						<div class="field-one clearfix">
							<?php echo $this->Form->input('web', array('label' => array("class" => "review-label", "text"=>"Website"), 'div' => "review-website", "placeholder" => "Website Address")); ?>
						</div>
						<div class="field-one">
							<?php echo $this->Form->input('message', array('label' => array("class" => "review-label", "text"=>"Message"), 'div' => "review-message", "placeholder" => "Your Message", "type" => "textarea")); ?>
						</div>
						<div class="field-btn">
							<?php echo $this->Form->end(array('label' => 'Get Your Website Review','div' => array('class' => 'review-btn'))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>



</script>
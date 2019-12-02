<?php
	$this->set('title_for_layout', 'Team 8: Careers');
	$this->set('description_for_layout', 'Apply for a role at Team 8.');
?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="p-0 sts-intro">
						<h1><strong>Career Opportunities</strong></h1>
						<h2>Apply for a role at Team 8</h2>
					</div>
				</div>
				<div class="showcase-feature">
					<?php echo $this->Element('career_listing');?>
				</div>
				<div class="large-container">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Specialists</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

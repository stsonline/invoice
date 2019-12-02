
<div id="wide-content" class="box-style1">
	<h2 class="title">Careers</h2>
	<p class="subtitle">Innovative Software takes Innovative People</p>
	<p>At STS we have employees from a variety of technology, design and computing backgrounds. Giving us experience that encompasses over two decades across the computing and technology industry. If you are looking for a change in positions, a change in career or work experience please don't hesitate to contact us as we are always on the lookout for talented individuals to join us at our Bridgend office.</p>
	<?php
		foreach($job_items as $key=>$item)
		{
			echo '
					<p class="subtitle">'.$item['Job']['title'].'</p>
					<p class="content">'.$item['Job']['description'].'</p>
					<p class="content2"><a href="'.$this->webroot.'contact#contact">Apply</a></p>
				 ';
		}
	?>

	<p class="subtitle">Enterprise Fund</p>
	<p>Got a great idea for a product or service, but not sure where to take it? We want to hear about it!
	
	<p>Team 8 operates a startup fund specifically designed to support first time entrepreneurs getting their businesses off the ground.</p>

	<p>Funding for projects from &pound;10k to &pound;500k will be considered, please <a href='<?php echo $this->webroot?>contact#contact'>contact us</a> for more information.</p>

</div>

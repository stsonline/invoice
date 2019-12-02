<?php if($list === true){ ?>
<div class="box-style1 clear">
	<h2 class="title">Work Showcase</h2>
	<p class="subtitle">Click an image to see details of each item in our project showcase.</p>
	<div id="showcase">
		<?php
			foreach($showcase_items as $key=>$item)
			{
				echo '
				
				<a href="showcase/'.$item['Showcase']['seo_name'].'">
					<div class="showcase-block">	
					<img src="'.$this->base.'/app/webroot/img/showcase/list/'.$item['Showcase']['image_url'].'" alt="">
					
					
						<p class="showcase-title">'.$item['Showcase']['title'].'</p>
					</div>
				</a>		
				';
			}
		?>
	</ul>
</div>
<?php } else { ?>
<div class="case-study">
	<div class="header">
		<h2 class="title"><?php echo $showcase_item['Showcase']['title']?></h2>
		<p class="subtitle"><?php echo $showcase_item['Showcase']['subtitle']?></p>
		<div class="project-image-bg">
			<img id="project-image" src="<?php echo $this->base.'/app/webroot/img/showcase/'.$showcase_item['Showcase']['image_url']?>" />
		</div>	
		<?php 
	$extra_imgs = $showcase_item['ShowcaseImage'];
	
	foreach($extra_imgs as $img )
	{
		$file = $img['filename'];
	?>
	<img id="project-image" src="<?php echo $this->base.'/app/webroot/img/showcase/'.$img['filename']?>" />
	<?php }?>
	</div>
	<div class="description">
		<h2>Overview</h2>
		<p class="content"><?php echo $showcase_item['Showcase']['description']?></p>
		<br>
		<h2>Project Details</h2>
		<p class="content"><?php echo $showcase_item['Showcase']['description2']?></p>	
		<br>
		<h2>Results</h2>
		<p class="content"><?php echo $showcase_item['Showcase']['description3']?></p>
	</div>
	<div class="related">
		<h3>Related Projects</h3>
		<div class="cases">
			<p class="link-menu" style="font-size: 1em;">
				<a href="<?php echo $this->webroot?>showcase/<?php echo $rand['seo_name']; ?>">
				<img src="<?php echo $this->base.'/app/webroot/img/showcase/'.$rand['image_url']?>" />
				<?php echo $rand['title']; ?></a>
				<a href="<?php echo $this->webroot?>showcase"> << Back to showcases</a>
			</p>
		</div>	
		<h3>Testimonials</h3>
		<div class="quotes">
			<p>"<?php echo $testimonial['Testimonial']['text']?>"</p>
			<p><?php echo $testimonial['Testimonial']['from']?></p>
		</div>
		<?php if(isset($showcase_item['Showcase']['pdf_url']) && $showcase_item['Showcase']['pdf_url']!=''){?>
		<div class="pdf">
			<a target="_blank" href="<?php echo $this->webroot.'pdfs/'.$showcase_item['Showcase']['pdf_url']?>">
				<p>Download PDF Brochure</p>
				<img src="../app/webroot/img/pdf.png">
			</a>
		</div>
		<?php }?>
	</div>
</div>	
	<?php echo $this->Element('sts_help');?>
</div>
<?php } ?>
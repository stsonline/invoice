
<pre><?php //print_r ($showcase_item);?></pre>


<div class="box-style clear">
	
	<h2 class="title"><?php echo $showcase_item['Showcase']['title']?></h2>
	<p class="subtitle"><?php echo $showcase_item['Showcase']['subtitle']?></p>
	<p class="link-menu" style="font-size: 1em;">
		<a href="<?php echo $this->webroot?>showcase/view/<?php echo $rand_id?>">View related showcase "<?php echo $rand_title?>"</a>
		<a href="<?php echo $this->webroot?>showcase"> Back to showcases</a>
	</p>
	
	
	<p class="content"><?php echo $showcase_item['Showcase']['description']?></p>
	<?php echo $showcase_item['Showcase']['list']?>
	
	<p>
	<img id="projectimg" src="<?php echo $this->base.'/app/webroot/img/showcase/'.$showcase_item['Showcase']['image_url']?>" />
	</p>
	<?php 
	$extra_imgs = $showcase_item['ShowcaseImage'];
	
	foreach($extra_imgs as $img )
	{
		$file = $img['filename'];
	?>
	<p>
	<img id="projectimg" src="<?php echo $this->base.'/app/webroot/img/showcase/'.$img['filename']?>" />
	</p>
	<?php }?>
	
	<div class="contact-us">
		<p>Contact us for a quote today</p>
	</div>
	
</div>
			
	
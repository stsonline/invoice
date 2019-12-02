		<?php 
		
		$title = isset($title) ? $title : 'Showcase';
		
		?>
		
		<div id="portfolio">
			<div class="title">
				<h2><?php echo $title?></h2>
			</div>	
			<div>
				<div id="portfolio-slider"> <a href="#" class="button previous-button">&lt;</a> <a href="#" class="button next-button">&gt;</a>
					<div class="viewer">
						<div class="reel">
								<div><ul>
								<?php
									$i = 0;
									
									
									foreach($showcase_items as $key=>$item)
									{
										if ($i % 3 == 0)
										{
											echo '</ul>
												</div>';
											echo '<div class="slide">
													<ul id="">';
										}
										
										echo '
											<li>
												<a href="'.$this->base.'/showcase/'.$item['Showcase']['seo_name'].'">
													<img src="'.$this->base.'/app/webroot/img/showcase/'.$item['Showcase']['image_url'].'" alt="" width="220" height="170" />
												
												
													<p class="showcase-title-front">'.$item['Showcase']['title'].'</p>
												</a>
											</li>
						
										';
										
										if ($i % 3 == 0)
										{
											
										}
										$i++;
									}
								?>
							</ul></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
<div class="device">
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php foreach($showcase_items as $key => $item) { ?>
			<div class="swiper-slide">
				<div class="title">

					<a href="<?php echo $this->base.'/showcase/'.$item['Showcase']['seo_name'] ?>">
						<img src="<?php echo $this->base.'/app/webroot/img/showcase/'.$item['Showcase']['image_url'] ?>" alt="" width="170" height="170" />
						<p class="showcase-title-front"><?php echo $item['Showcase']['title'] ?></p>
					</a>
				
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>	
		
<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.slidertron-1.0.js"></script>
<script type="text/javascript" src="<?php echo $this->webroot?>js/init.js"></script>
<script>
$(function() {

	/*$('#banner-slider').slidertron({
		viewerSelector: '.viewer',
		reelSelector: '.viewer .reel',
		slidesSelector: '.viewer .reel .slide',
		advanceDelay: 15000,
		speed: 900,
		navPreviousSelector: '.previous-button',
		navNextSelector: '.next-button',
		indicatorSelector: '.indicator ul li',
		viewerOffset: -45
	});*/

	$('#portfolio-slider').slidertron({
		speed: 900,
		viewerSelector: '.viewer',
		reelSelector: '.viewer .reel',
		slidesSelector: '.viewer .reel .slide',
		navPreviousSelector: '.previous-button',
		navNextSelector: '.next-button'
	});

});

$(function() {
	var mySwiper = new Swiper('.swiper-container',{
		centeredSlides: true,
		slidesPerView: 3,
		watchActiveIndex: true,
		loop: true,
		calculateHeight: true,
	  });
	});

$(function(){
	  $(".showcase-title-front").each(function(i){
	    len=$(this).text().length;
	    if(len>37)
	    {
	      $(this).text($(this).text().substr(0,37)+'...');
	    }
	  });       
	});
	
</script>


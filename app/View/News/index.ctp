
<div style="width:350px;" class="news-sign-up float-right">
	<?php //echo $this->element('news_menu');?>
	<?php echo $this->element('newsletter_signup');?>
	<div class="clear">&nbsp;</div>
</div>
<div id="wide-content" class="box-style1">
	<div class="page-header generic-header">
		<h2 class="title">News</h2>
		<?php if($list === false) { ?>
		<p class="subtitle"><?php echo $news['Newsletter']['title']; ?></p>
		<p class="date"><?php echo date("jS F Y",strtotime($news['Newsletter']['date'])); ?></p>
		<?php }else{ ?>
		<p class="subtitle">Announcements about the company; from success stories <br />and achievements to completed projects and more.</p>
		<?php }?>
	</div>
	<?php if($list === false) { ?>
	<div class="newsletter-body">
		<?php echo $news['Newsletter']['content']; ?>
	</div>
	<?php } else { ?>
		<ul class="letter-list">
			<?php foreach($news as $letter) { ?>
				<li>
					<div class="letter-date">
						<?php echo date("jS M",strtotime($letter['Newsletter']['date'])); ?>
					</div>
					<div class="letter-info">
						<h2><a href="<?php echo $letter['Newsletter']['seo_name']; ?>"><?php echo $letter['Newsletter']['title']; ?></a></h2>
						<p><?php echo $letter['Newsletter']['intro']; ?></p>
					</div>
				</li>
			<?php } ?>
		</ul>
	<?php } ?>
	<div class="newsletter-600">
	<?php //echo $this->element('news_menu');?>
	<?php echo $this->element('newsletter_signup');?>
	<div class="clear">&nbsp;</div>
</div>
</div>

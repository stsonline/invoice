<div class="header-menu">
	<ul>
		<li><a href="<?php echo $this->webroot?>who-we-are">Who We Are</a></li>
		<li><a href="<?php echo $this->webroot?>what-we-do">What We Do
				<img src="<?php echo $this->webroot?>img/dropdown.png">
			</a>
			<div class="popup-menu">
				<ul>
					<li class="icon-01"><a href="<?php echo $this->webroot?>services/web-design">Web Design</a></li>
					<li class="icon-02"><a class="arrow-icon" href="<?php echo $this->webroot?>services/ecommerce">Ecommerce</a>
						<div class="sub-menu">
							<ul>
								<li class="sub-icon-01"><a href="<?php echo $this->webroot?>services/cs-cart">CS-Cart</a></li>
								<li class="sub-icon-02"><a href="<?php echo $this->webroot?>services/magento">Magento</a></li>
								<li class="sub-icon-03"><a href="<?php echo $this->webroot?>services/jshop">JShop</a></li>
							</ul>
						</div>
					</li>
					<li class="icon-03"><a href="<?php echo $this->webroot?>services/hosting">Hosting</a></li>
					<li class="icon-04"><a href="<?php echo $this->webroot?>services/support">Tech Support</a></li>
					<li class="icon-05"><a href="<?php echo $this->webroot?>services/search-marketing">Search Marketing</a></li>
					<li class="icon-06"><a href="<?php echo $this->webroot?>services/app-development">App Development</a></li>
					<li class="icon-07"><a href="<?php echo $this->webroot?>services/social-media">Social Media</a></li>
					<li class="icon-08"><a href="<?php echo $this->webroot?>services/lead-generation">Lead Generation</a></li>
					<li class="icon-09"><a href="<?php echo $this->webroot?>services/cyber-security">Cyber Security</a></li>
				</ul>
			</div>
		</li>
		<li><a href="<?php echo $this->webroot?>our-work">Our Work</a></li>
		<?php
			$currentuser=$this->Session->read('Auth.User');
			if(isset($currentuser))
			{
				//echo "Logged in as ".$currentuser['username']."<br /> ". $this->Html->link('Logout',array('controller'=>'users','action' => 'logout'));
		?>
		<?php
		//system admins need a link to the backend
		if(isset($currentuser['role_id']) && $currentuser['role_id']==2){
		?>
		<li><a href="<?php echo $this->base?>/dashboard">Dashboard</a>
			<div class="popup-menu client-options">
				<ul>
					<li><a href="<?php echo $this->base?>/users/logout">Logout <?php echo $currentuser['username']?></a>
				</ul>
			</div>
		</li>
		<?php }?>
		<?php
		}
		else
		{//non logged in
		?>
		<li><a href="<?php echo $this->webroot?>get-in-touch">Get In Touch</a>
			<div class="popup-menu feedback-menu">
				<ul>
					<li class="feedback-icon"><a href="<?php echo $this->webroot?>feedback">Feedback</a></li>
					<li class="review-icon"><a href="<?php echo $this->webroot?>services/website-review">Website Review</a></li>
				</ul>
			</div>
		</li>
		<?php
		}
		?>
	</ul>
</div>


<script type="text/javascript">

var url = window.location.href;

//Will only work if string in href matches with location
$('.header-menu a[href="'+ url +'"]').addClass('active');

//Will also work for relative and absolute hrefs
$('.header-menu a').filter(function() {
 return this.href == url;
}).addClass('also-active');

</script>

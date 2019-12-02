
<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div id="page">
					<div class="float-left" id="content">
						<div class="box-style1">
							<?php 
								foreach($article_items as $key=>$item)
								{
									echo '
											<a name="'.$item['Article']['article_link'].'" />
											<h2 class="title">'.$item['Article']['title'].'</h2>
											<img id="articleimg" src="'.$this->base.$item['Article']['image_url'].'" width=580px height=350px />
											'.$item['Article']['content'].'
											'.$item['Article']['code'].'
											<hr />
										 ';
								}
							?>
						</div>
					</div>
					<div class="float-right" id="sidebar">
						<div id="box2" class="box-style2">
							<h2 class="title">Older Articles</h2>
							<div class="content">
								<ul class="style4">
									<?php 
										foreach($article_items as $key=>$item)
										{
										echo '
											<li><a href="#'.$item['Article']['article_link'].'">'.$item['Article']['title'].'</a></li>
										';
										}
									?>
								</ul>
							</div>
							<div class="bgbtm"></div>
						</div>
						<div id="box3" class="box-style2">
							<h2 class="title">Affiliates</h2>
							<div class="content">
								<ul class="style4">
									<li><a href="http://www.cakephp.org">CakePHP</a></li>
									<li><a href="http://www.cs-cart.com">CS Cart</a></li>
								</ul>
							</div>
							<div class="bgbtm"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

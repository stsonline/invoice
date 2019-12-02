<?php
	$this->set('title_for_layout', 'Team 8: Blog');
	$this->set('description_for_layout', 'Read more about our latest news and offers at Team 8 Digital.');
?>
<div class="container-fluid">
  <div class="container">

		<div class="page-content large-container">

			<div class="sts-intro">
				<h1><strong>Team 8</strong></h1>
				<h2>Read our latest blog posts</h2>
			</div>

			<div class="blog-grid">
				<div class="blog-wrapper">
					<?php foreach ($posts as $post): ?>
						<div class="blog-lg-6">
							<div class="blog-small">
								<h2><?php echo $this->Html->link($post['Post']['title'], array('controller' => 'posts', 'action' => 'view', $post['Post']['title'])); ?></h2>
								<h5><?php echo $post['Post']['created']; ?></h5>
								<p><?php echo substr(strip_tags($post['Post']['body']), 0, 280)."..."; ?></p>
								<p class="blog-link blue-btn"><?php echo $this->Html->link("Read More", array('controller' => 'posts', 'action' => 'view', $post['Post']['title'])); ?></p>

							</div>
						</div>
					<?php endforeach; ?>
					<?php unset($post); ?>
				</div>
			</div>

		</div>

	</div>
</div>

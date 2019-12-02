<?php
	$this->set('title_for_layout', $post['Post']['title']);
	$this->set('description_for_layout', 'Team 8 blog post.');
?>
<div class="page-content large-container">

	<div class="sts-intro blog-intro">
		<h1><strong><?php echo $post['Post']['title']; ?></strong></h1>
		<h2><p class="creation"><small>Created: <?php echo $post['Post']['created']; ?></small></p></h2>
	</div>

	<div class="blog-content">

		<?php echo $post['Post']['body']; ?>

	</div>

</div>

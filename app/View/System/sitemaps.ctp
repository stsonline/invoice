<div>
<h3>Website Sitemap</h3>
<p>Sitemaps are an important way for search engines to find and index the pages/locations of your site that you nominate.</p>
<p> <a target="_blank" href="<?php echo $this->webroot?>sitemap.xml">View Current sitemap</a></p>
<form action="<?php echo $this->webroot?>system/sitemaps" method="POST">
	<button type="submit" name="operation" value="generate" class="dark-btn">Generate</button>
	<button type="submit" name="operation" value="submit" class="dark-btn">Submit to Search Engines</button>
	
	</form>
</div>

<?php if(isset($sitemapSubmission)){?>
<pre>
<?php print_r($sitemapSubmission);?>
</pre>
<?php }?>
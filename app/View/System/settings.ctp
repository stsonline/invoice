<div>
<h3>Settings</h3>
<form autocomplete="off" action="<?php echo $this->webroot?>system/settings/" method="POST">
	
	<?php 
	foreach($settings as $key=>$setting){?>
	
	<div class="input">
		<label for="<?php echo $key?>"><?php echo $key?></label>
		<input class="admin-field" id="<?php echo $key?>" name="Setting[key][<?php echo $key?>]" value="<?php echo $setting?>"/> 
	
	</div>
	
	<?php }?>
	
	<input type="submit" value="Save changes" class="dark-btn cursor-pointer" />

</form>
</div>
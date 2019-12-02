<?php if(isset($model_plural)){?>
	<div class="sts-intro admin-intro">
		<h1><?php echo $model?> Model Editor Listing</h1>
	</div>
	<table>
	
		<tr>
			<?php foreach($fieldlist['display'] as $field){?>
			
			<th>
				<?php 
				$field = ucfirst($field);
				$field = str_replace('_', ' ', $field);
				echo $field ;
				?>
			</th>
			
			<?php }?>
			<th></th>
			<th></th>
		</tr>
	
		<?php foreach($model_plural as $model_single){?>
		
		<tr>
			<?php foreach($fieldlist['display'] as $field){?>
			
			<td><?php echo $model_single[$model][$field] ?></td>
			
			<?php }?>
		
			
			<td><a href="<?php echo $this->webroot?>system/<?php echo $lowercase_model_plural.'/'.$model_single[$model]['id'] ?>">Edit</a></td>
			<td><a href="<?php echo $this->webroot?>system/<?php echo $lowercase_model_plural.'/'.$model_single[$model]['id'] ?>/delete">Delete</a></td>
		</tr>
		
		<?php }?>
	</table>
	<?php echo $this->Form->create($model,array('url' => $lowercase_model_plural.'/0/add'));?>
	<?php echo $this->Form->end(array('label'=>'Add','class'=>'dark-btn cursor-pointer')); ?>

	<pre><?php if($debug)var_dump($model_single)?></pre>

<?php }else{?>
	
	<div class="sts-intro admin-intro">
		<h3><?php echo $model?> Editor</h3>
	</div>
	<!--
	<a class="dark-btn admin-margin" href="<?php echo $this->webroot?>system/<?php echo $lowercase_model_plural?>">View all</a>
	 
	<a target="_blank" href="<?php echo $this->webroot.$lowercase_model?>/">View on website</a>
	 -->
	<?php echo $this->Form->create($model);?>
	
	<?php foreach($fieldlist['edit'] as $field)
	{
		if($field == 'email')
		{
			echo $this->Form->input($field,array('type' => 'text'));
		}
		else 
		{
			echo $this->Form->input($field);
		}
	}
	?>
	
	<?php echo $this->Form->end(array('label'=>'Save','class'=>'dark-btn cursor-pointer')); ?>
	<pre><?php if($debug)var_dump($model_single)?></pre>
<?php }?>
<div class="sts-intro admin-intro">
	<h1>Clients</h1>
</div>
<?php 
echo $this->Form->create('User', array('type' => 'get'));
echo $this->Form->input('search');
echo $this->Form->end();
?>
<div class="page-nav">
	<?php echo $this->Paginator->first(
  ' << ',
  array(),
  null,
  array('class' => 'pagination-button first disabled')
); 
echo $this->Paginator->prev(
  ' Previous ',
  array(),
  null,
  array('class' => 'pagination-button prev disabled')
); 
echo $this->Paginator->next(
  ' Next ',
  array(),
  null,
  array('class' => 'pagination-button next disabled')
); 
echo $this->Paginator->last(
  ' >> ',
  array(),
  null,
  array('class' => 'pagination-button last disabled')
); ?>
</div>
<div class="admin-table">
	<table>
	    <tr>
	        <th><?php echo $this->Paginator->sort('id','Id'); ?></th>
	        <th><?php echo $this->Paginator->sort('balance','Balance'); ?></th>
			<th><?php echo $this->Paginator->sort('lastname','Name'); ?></th>
			<th><?php echo $this->Paginator->sort('organisation','Organisation'); ?></th>
			<th><?php echo $this->Paginator->sort('email','Email'); ?></th>
	        <th>ClientActions</th>
	    </tr>
	
	    <?php
	    
	    foreach ($clients as $client)
		{?>
	    <tr>
	        <td>
	        	<?php echo $client['Client']['id']; ?>
	        </td>
	        <td <?php if(floatval($client['Client']['balance']) > 0){echo "class='unpaid-balance'";}?> >
	        	<a href="<?php echo $this->webroot?>invoices/index/<?php echo $client['Client']['id']; ?>">&pound;
	        	<?php if(isset($client['Client']['balance']))
	        	{
	        			echo $client['Client']['balance'];
	        	} ?>
	        	</a>
	        </td>
	        <td>
	        	<a href="<?php echo $this->webroot?>invoices/index/<?php echo $client['Client']['id']; ?>"><?php echo $client['Client']['firstname']; ?> <?php echo $client['Client']['lastname']; ?></a>
	        </td>
	        <td>
	        	<?php echo $client['Client']['organisation']; ?> ( <?php echo $client['Client']['org_alias']; ?> )
	        </td>
	        <td>
	        	<?php echo substr($client['Client']['email'],0,50); ?>
	        </td>
	        <td>
        		<a title="Invoices" href="<?php echo $this->webroot?>invoices/index/<?php echo $client['Client']['id']; ?>"><i class="fa fa-file-text"></i> Invoices</a> </li>
	        		<br>
				<a title="Edit" href="<?php echo $this->webroot?>system/clients/<?php echo $client['Client']['id']; ?>"><i class="fa fa-pencil-square"></i> Edit</a> </li>
				<br>
				<a class="delete-client" data-client="<?php echo $client['Client']['id']; ?>" title="Delete" href="#"><i style="color:red;" class="fa fa-minus-circle"></i> Delete</a></li>
	        </td>
	    </tr>
	
	     <?php 
		 } ?>
	     
	</table>
</div>
<div class="page-nav">
	<?php echo $this->Paginator->first(
  ' << ',
  array(),
  null,
  array('class' => 'pagination-button first disabled')
); 
echo $this->Paginator->prev(
  ' Previous ',
  array(),
  null,
  array('class' => 'pagination-button prev disabled')
); 
echo $this->Paginator->next(
  ' Next ',
  array(),
  null,
  array('class' => 'pagination-button next disabled')
); 
echo $this->Paginator->last(
  ' >> ',
  array(),
  null,
  array('class' => 'pagination-button last disabled')
); ?>	
</div>
<script>
	$(document).ready(function() {
		

		$('.delete-client').click(function(event) {
			event.preventDefault();
			var id = $(this).attr("data-client");
			var mark = confirm("Are you sure you want to delete Client #" + id + "?");
			if(mark) 
			{
				window.location = "<?php echo $this->webroot.'system/clients/"+id+"/delete/' ?>";
			}
		});

		
	});
</script>
<?php unset($client); ?>
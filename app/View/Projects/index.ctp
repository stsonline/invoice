<?php 
	//echo $this->Html->css('paginator');
		
	//echo $this->Html->css('application');
	
	echo $this->Html->script('DataTable/invoice_datatable');  //webroot/js/DataTable/datatable.js
	
	//echo $this->Html->css("http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css");
	
	echo $this->Html->css('tablehighlight');
	echo $this->Html->css("TableTools");
?>

<h1 class="projects-title">Projects</h1>
<table id="projects">
	<thead>
		<tr>
			<th>Project</th>
			<th>Description</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($projects as $project) { ?>
		<tr>
			<td><?php echo $project['Project']['name']; ?></td>
			<td><?php echo $project['Project']['description']; ?></td>
			<td><a href="<?php echo $this->base.'/projects/view/'.$project['Project']['id']; ?>">View invoices</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<script>
var WEBROOT = "<?php echo $this->webroot?>";
$(document).ready(function() {
	$('#projects').dataTable();
});
</script>
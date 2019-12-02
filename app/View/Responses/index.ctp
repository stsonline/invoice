<?php 
echo $this->Html->css('response');
echo $this->Html->script('Campaign/index');  //webroot/js/Campaign/index.js
?>

<div id="page">

	<h1>
		Invoice : Id
		<?php echo $invoice['Invoice']['id'].", Client ".$invoice['Client']['id'].' , '.$invoice['Client']['organisation']; ?>
	</h1>

	<h2>Responses</h2>

	<table>
		<tr>
			<th>Id</th>
			<th>Reminder</th>
			<th>Viewed</th>
			<th>Followed</th>
			<th>Created</th>
			<th>Modified</th>
		</tr>

		<?php

		foreach ($responses as $response)
	{ ?>
		<tr>
			<td><?php echo $response['Response']['id']; ?>
			</td>
			<td><?php echo $response['Response']['reminder_no']; ?>
			</td>
					<td
			<?php if($response['Response']['view'] != 0)
				echo ' class="view"';   	?>><?php echo $response['Response']['view'];  ?>
			</td>
			<td
			<?php  if($response['Response']['follow'] != 0)
				echo ' class="follow"';?>><?php echo $response['Response']['follow'];  ?>
			</td>
			<td><?php echo $response['Response']['created']; ?>
			</td>
			<td><?php if($response['Response']['modified'] != $response['Response']['created'])
				echo $response['Response']['modified']; ?></td>
		</tr>

		<?php 
	} ?>

	</table>


</div>
<?php unset($response); ?>
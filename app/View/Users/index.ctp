<div class="large-container">
	<div class="sts-intro">
		<h1>Users</h1>
	</div>
	<p><?php echo $this->Html->link('Add User', array('action' => 'add')); ?></p>
	<div class="admin-table">
		<table>
		    <tr>
		        <th>Id</th>
		        <th>User</th>
		        <th>Group</th>
		        <th>View Details</th>
		        <th>Edit</th>
		        <th>Created</th>
		    </tr>
		
		    <?php foreach ($users as $user): ?>
		    <tr>
		        <td><?php echo $user['User']['id']; ?></td>
		        <td><?php echo $user['User']['username']; ?></td>
		        <td><?php echo $user['User']['role_id']; ?></td>
		        <td>
		            <?php echo $this->Html->link('View', array('action' => 'view', $user['User']['id'])); ?>
		        </td>
		        <td>
		            <?php echo $this->Html->link('Edit', array('action' => 'edit', $user['User']['id'])); ?>
		        </td>
		        <td><?php echo $user['User']['created']; ?></td>
		    </tr>
		    <?php endforeach; ?>
		    <?php unset($user); ?>
		    <?php unset($currentuser); ?>
		</table>
	</div>
</div>		
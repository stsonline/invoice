<div class="sts-intro admin-intro">
	<h1>Group/Role Permissions</h1>
	<p>Every action and controller combination can be locked down, and/or forced to use secure connection to access. The system operates on an allow, deny basis; if there's no specific rule in this table, it will be allowed for all user groups by default.</p>
</div>
<div class="admin-table">
	<table>
	    <tr>
	        <th>Id</th>
	        <th>Role</th>
	        <th>Controller</th>
	        <th>Action</th>
	        <th>Allowed</th>
	        <th>Secure (SSL)</th>
	    </tr>
	
	    <?php foreach ($actions as $action): ?>
	    <tr>
	        <td><?php echo $action['Action']['id']; ?></td>
	        <td><?php echo isset($roles[$action['Action']['role_id']]) ? $roles[$action['Action']['role_id']] : $action['Action']['role_id']; ?></td>
	        <td><?php echo $action['Action']['controller_name']; ?></td>
	        <td><?php echo $action['Action']['action_name']; ?></td>
	        <td class="<?php
	            if($action['Action']['allowed'])
	            	echo 'yes';
	            else 
	            	echo 'no'; ?>">
	            <?php
	            if($action['Action']['allowed'])
	            	$permission='yes';
	            else 
	            	$permission='no';             
	            echo $this->Html->link($permission, array('action' => 'change_permission', $action['Action']['id'])); ?>
	        </td>
	        <td class="<?php
	            if($action['Action']['secure'] == 1)
	            	echo 'yes';
	            else 
	            	echo 'no'; ?>">
	            <?php
	            if($action['Action']['secure'] == 1)
	            	$secure='yes';
	            else 
	            	$secure='no';             
	            echo $this->Html->link($secure, array('action' => 'change_secure', $action['Action']['id'])); ?>
	        </td>
	    </tr>
	    </tr>
	    <?php endforeach; ?>
	    <?php unset($action); ?>
	    <?php unset($currentuser); ?>
	</table>
</div>
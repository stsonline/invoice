<?php 	
	$currentuser=$this->Session->read('Auth.User');
		if(isset($currentuser))
		{
	//Only show menu items if the user is logged in
?>

<div class="header-menu admin-header-menu">
	<ul>
		<li><i class="fa fa-file-text"></i> <a href="<?php echo $this->webroot?>invoices/list_all">Invoices</a>
		<div class="popup-menu">
			<ul>
				<li class=""><a href="<?php echo $this->webroot?>invoices/list_all"><i class="fa fa-file-text"></i> All</a></li>
				<li class=""><a href="<?php echo $this->webroot?>invoices/list_unpaid"><i class="fa fa-times"></i> Unpaid</a></li>
				<li class=""><a href="<?php echo $this->webroot?>invoices/add"><i class="fa fa-plus"></i> Raise New</a></li>
			</ul>
		</div>
		</li>
		<li><i class="fa fa-user"></i><a href="<?php echo $this->webroot?>clients">Clients</a>
		<div class="popup-menu">
			<ul>
				<li class=""><a href="<?php echo $this->webroot?>clients"><i class="fa fa-file-text"></i> All</a></li>
				<li class=""><a href="<?php echo $this->webroot?>system/clients/add"><i class="fa fa-user-plus"></i> Add Client</a></li>
			</ul>
		</div>
		</li>
		<li><i class="fa fa-credit-card"></i><a href="https://secure.worldpay.com/sso/public/auth/login.html?serviceIdentifier=merchantadmin" target="_blank">Take Payment</a></li>
		
		
		<li><i class="fa fa-caret-square-o-down"></i><a href="<?php echo $this->webroot?>dashboard">Main Menu</a>
			<div class="popup-menu">
				<ul>
					<li>INVOICING</li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/add">Raise Invoice</a></li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/list_all">Invoices</a></li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/list_unpaid">Unpaid Invoices</a></li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/list_recurring">Recurring Invoices</a></li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/pay_partial">Add Payment</a></li>
					<li class=""><a href="<?php echo $this->webroot?>clients/add">Add Clients</a></li>
					<li class=""><a href="<?php echo $this->webroot?>clients">Clients</a></li>
					<li>MODEL EDITOR</li>
					<li class=""><a href="<?php echo $this->webroot?>system/clients">Clients</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/invoices">Invoices</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/payments">Payments</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/projects">Projects</a></li>
					<!--<li class=""><a href="<?php echo $this->webroot?>system/currencies">Currencies</a></li>-->
					<li>STATIC EDITOR</li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/data_table_all">Invoices (Datatables)</a></li>
					<li class=""><a href="<?php echo $this->webroot?>clients/index">All Clients</a></li>
					<li class=""><a href="<?php echo $this->webroot?>clients/add">Add Client</a></li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/list_all">All Invoices</a></li>
					<li class=""><a href="<?php echo $this->webroot?>invoices/list_unpaid">Unpaid Invoices</a></li>
					<li class=""><a href="<?php echo $this->webroot?>payments/index">All Payments</a></li>
					<li>WEBSITE CONTENT</li>
					<li class=""><a href="<?php echo $this->webroot?>">View Website</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/newsletters">News</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/jobs">Jobs</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/showcases">Showcases</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/testimonials">Testimonials</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/pages">Pages</a></li>
					<li>SYSTEM</li>	
					<li class=""><a href="<?php echo $this->webroot?>system/users">Manage Users</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/roles">User Groups</a></li>
					<li class=""><a href="<?php echo $this->webroot?>actions">Access Controls</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/settings">Global Settings</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/sitemaps">Manage Sitemaps</a></li>
					<li class=""><a href="<?php echo $this->webroot?>system/routes">Route Mapping</a></li>
				</ul>
			</div>
		</li>
	</ul>
</div>

<?php
	}
?>

<script>

</script>

<?php
$currentuser=$this->Session->read('Auth.User');
if(isset($currentuser))
{
	//echo "Logged in as ".$currentuser['username']."<br /> ". $this->Html->link('Logout',array('controller'=>'users','action' => 'logout'));
	?>
	<div id="header">
		<div  id="logo">
			<a href='<?php echo $this->webroot?>'><img class="logo2" src="<?php echo $this->webroot?>img/logo/logo2.png" alt="Team 8" /></a>
		</div>
		<div id="contact-details">
			<div class="email">
				<a href="mailto:contact@team8digital.uk" rel="external">contact@team8digital.uk</a>
			</div>
			<div class="phone">+44 (0)1656 513 046</div>
		</div>
	</div>


<?php
}
else
{//non logged in
?>

	<div id="header">
		<div  id="logo">
			<a href='<?php echo $this->webroot?>'><img src="<?php echo $this->webroot?>img/logo/logo2.png" alt="Team 8 Ltd" /></a>
		</div>
		<div id="contact-details">
			<div class="email">
				<a href="mailto:contact@team8digital.uk" rel="external">contact@team8digital.uk</a>
			</div>
			<div class="phone">+44 (0)1656 513 046</div>
		</div>
	</div>

<?php
}
?>

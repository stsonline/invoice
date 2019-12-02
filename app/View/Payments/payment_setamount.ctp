

<div id="page">

	<div id="client-details">
		
		<h2>Client</h2>
		<p>
			<?php  
				echo $client['Client']['id'].", ".$client['Client']['firstname']." ".$client['Client']['lastname'].", "
					.$client['Client']['email'].", ".$client['Client']['organisation'];
			?>
		</p>
	</div>
	
	<h2>Unfinished Code</h2>
	
	<div id="payment-form">
	
		<div id="payment-amount">
			<?php 
				echo $this->Form->create('Payment');
			?>
		</div>
		<div>	
			<?php
				echo $this->Form->input('type',array('label'=>'Enter Credit Card No : ','default'=>'0000 0000 0000 0000'));
			?>
		</div>
		<div>	
			<?php
				echo $this->Form->input('amount',array('label'=>'Enter Payment Amount : ','default'=>'0000.00'));
			?>
		</div>
		<div>	
			<?php 
				echo $this->Form->submit('Pay Now');
			?>		
		</div>
	</div>
		
</div>
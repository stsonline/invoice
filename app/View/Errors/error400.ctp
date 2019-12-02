<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<h2><?php /* echo $name; ?></h2>
<p class="error">
	<strong><?php echo __d('cake', 'Error'); ?>: </strong>
	<?php printf(
		__d('cake', 'The requested address %s was not found on this server.'),
		"<strong>'{$url}'</strong>"
	); ?>
</p>
<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
*/?>

<div id="wrapper">
	<div id="wrapper-bgtop">
		<div id="page-wrapper">
			<div id="page-wrapper-bgtop">
				<div class="page-content large-container">
					<div class="sts-intro">
						<h1><strong>404: Page not found</strong></h1>
					</div>
					<div class="page-intro">	
						<p>"No one is exempt from the rule that learning occurs through recognition of error."</p>
						<p class="quote">Alexander Lowen, Physician</p>
						<p class="error-msg">Sorry the page you requested could not be found. From here you can go back to the <a href="<?php echo $this->webroot?>index">homepage</a> and try again.</p>
					</div>
					<div class="sts-online">
						<h2>You may be interested in:</h2>
					</div>
					
					<?php echo $this->Element('services');?>
					
				</div>
			</div>
		</div>
	</div>
</div>	
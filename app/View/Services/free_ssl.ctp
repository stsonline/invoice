<?php $this->set('title_for_layout', 'Free SSL'); 
$this->set('description_for_layout', 'Team 8: If you&#39re looking for Free SSL then look no further. Team 8 are specialists in developing an API for your website.'); 

?>

<div id="wrapper" class="page-wrapper-<?php echo $this->request->params['action']?>">
    <div id="wrapper-bgtop">
        <div id="page-wrapper">
            <div id="page-wrapper-bgtop">
                <div class="page-content page-content-<?php echo $this->request->params['action']?> large-container">
                    <div class="sts-intro">
                      <h1><strong>Do you need SSL?</strong></h1>
					</div>
						<div class="page-intro">
                            <p id="ssl-price">Open SSL Installation From Just £19.95</p>
							<p>Team 8 can provide and install your SSL certificates for your website with automatic renewal. <br>Secure your customer's data today by enabling HHTPS on your website.<br> If you’d like to discuss your needs or just need some advice then give us a call on 01656 513046 or send us a 
                            	<a href="<?php echo $this->webroot; ?>get-in-touch">message</a>.</p>
                   		<a href="<?php echo $this->webroot?>get-in-touch" class="dark-btn">
							<span>Get Started</span>
						</a>
					</div>	
				</div>
				<div class="logo-right">
					<!--<img src="<?php echo $this->webroot; ?>img/services/javalogo.png">
					<img src="<?php echo $this->webroot; ?>img/services/netlogo.png">
					<img class="php-logo" src="<?php echo $this->webroot?>img/services/phplogo.png">-->
					<img src="<?php echo $this->webroot?>img/services/letsencrypt-logo-horizontal.svg" alt="Let's Encrypt">
				</div>
				<div class="page-feature">
					<div class="other-pages">
						<section class="page-navigation">
							<div class="large-container">			
                            <h2>The key principles behind Let’s Encrypt are:</h2>
<br>
                            <p>The objective of <i>Let’s Encrypt</i> is to make it possible to set up a HTTPS server and have it automatically obtain a browser-trusted certificate, without any human intervention. <br>This is accomplished by running a certificate management agent on the web server to verify you own the domain name and have access to the server.</p>
							</div>






							<div class="full-nav">
								<nav class="nav-arrows">
									<a class="prev" href="<?php echo $this->webroot?>services/">
										<span class="icon-wrap"></span>
										<h3><span class="strong">Hosting</span> Services</h3>
									</a>
									<a class="next" href="<?php echo $this->webroot?>services/search-marketing">
										<span class="icon-wrap"></span>
										<h3><span class="strong">Search</span> Marketing</h3>
									</a>
								</nav>
							</div>	
						</section>
					</div>
				</div>
				<div class="small-nav">
					<div class="large-container">
						<nav class="nav-arrows">
							<a class="prev" href="<?php echo $this->webroot?>services/hosting">
								<span class="icon-wrap"></span>
								<h3><span class="strong">Hosting</span> Services</h3>
							</a>
							<a class="next" href="<?php echo $this->webroot?>services/search-marketing">
								<span class="icon-wrap"></span>
								<h3><span class="strong">Search</span> Marketing</h3>
							</a>
						</nav>
					</div>
				</div>
				<div class="large-container">
					<div class="our-clients">
					<div class="sts-online">
						<h2><strong>Team 8</strong>, Web Design Specialists</h2>
						<div class="introduction">
						<p>There are two steps to this process. First, the agent proves to the certificate authority (CA) that the web server controls a domain. Then, the agent can request, renew, and revoke certificates for that domain. Let’s Encrypt identifies the server administrator by public key. The first time the agent software interacts with Let’s Encrypt, it generates a new key pair and proves to the Let’s Encrypt CA that the server controls one or more domains. To kick off the process, the agent asks the Let’s Encrypt CA what it needs to do in order to prove that it controls example.com. The Let’s Encrypt CA will look at the domain name being requested and issue one or more sets of challenges. Along with the challenges, the Let’s Encrypt CA also provides a nonce that the agent must sign with its private key pair to prove that it controls the key pair.
</p>
						<p>The agent software completes one of the provided sets of challenges. Let’s say it is able to accomplish the second task above: it creates a file on a specified path on the https://example.com site. The agent also signs the provided nonce with its private key. Once the agent has completed these steps, it notifies the CA that it’s ready to complete validation. Then, it’s the CA’s job to check that the challenges have been satisfied. The CA verifies the signature on the nonce, and it attempts to download the file from the web server and make sure it has the expected content.
If the signature over the nonce is valid, and the challenges check out, then the agent identified by the public key is authorized to do certificate management for example.com. We call the key pair the agent used an “authorized key pair” for example.com. Once the agent has an authorized key pair, requesting, renewing, and revoking certificates is simple—just send certificate management messages and sign them with the authorized key pair.
</p>
						</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
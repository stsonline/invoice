<?php
Router::connect('/seo', array('controller' => 'services', 'action' => 'seo'));
Router::connect('/hosting', array('controller' => 'services', 'action' => 'hosting'));
Router::connect('/customsoftware',  array('controller' => 'services', 'action' => 'development'));
Router::connect('/ecommerce-wales', array('controller' => 'page', 'action' => 'index', 'ecommerce-wales'));
Router::connect('/ecommerce-cardiff', array('controller' => 'page', 'action' => 'index', 'ecommerce-cardiff'));
Router::connect('/web-design-wales', array('controller' => 'page', 'action' => 'index', 'web-design-wales'));
Router::connect('/custom-software-wales', array('controller' => 'page', 'action' => 'index', 'custom-software-wales'));
Router::connect('/custom-ecommerce-wales', array('controller' => 'page', 'action' => 'index', 'custom-ecommerce-wales'));
Router::connect('/web-development-wales', array('controller' => 'page', 'action' => 'index', 'web-development-wales'));
Router::connect('/web-development-cardiff', array('controller' => 'page', 'action' => 'index', 'web-development-cardiff'));
Router::connect('/web-hosting-uk', array('controller' => 'page', 'action' => 'index', 'web-hosting-uk'));
Router::connect('/custom-ecommerce-cardiff', array('controller' => 'page', 'action' => 'index', 'custom-ecommerce-cardiff'));
Router::connect('/shopping-cart-software', array('controller' => 'page', 'action' => 'index', 'shopping-cart-software'));

<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	include('additional_routes.php');

	Router::connect('/', array('controller' => 'index', 'action' => 'index'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/bnp-paribas-hackathon', array('controller' => 'pages', 'action' => 'bnp_paribas_hackathon'));
	//Router::connect('/careers', array('controller' => 'pages', 'action' => 'careers'));
	//Router::connect('/careers/php-web-developer', array('controller' => 'careers', 'action' => 'php_web_developer'));
	Router::connect('/complaints', array('controller' => 'pages', 'action' => 'complaints'));
	Router::connect('/feedback', array('controller' => 'contact', 'action' => 'feedback'));
	Router::connect('/get-in-touch', array('controller' => 'pages', 'action' => 'get_in_touch'));
	Router::connect('/what-we-do', array('controller' => 'pages', 'action' => 'what_we_do'));
	Router::connect('/miskin-it-merge', array('controller' => 'pages', 'action' => 'miskinit_merge'));
	Router::connect('/who-we-are', array('controller' => 'pages', 'action' => 'who_we_are'));
	Router::connect('/our-work', array('controller' => 'pages', 'action' => 'our_work'));
	Router::connect('/bridgend-web-design', array('controller' => 'pages', 'action' => 'bridgend_webdesign'));
	Router::connect('/cardiff-web-design', array('controller' => 'pages', 'action' => 'cardiff_webdesign'));

	Router::redirect('/careers/*', array('controller' => 'pages', 'action' => 'get_in_touch'));
	Router::redirect('/merry', array('controller' => 'pages', 'action' => 'get_in_touch'));
	Router::redirect('/products/*', array('controller' => 'pages', 'action' => 'what_we_do'));

	Router::connect('/services', array('controller' => 'pages', 'action' => 'what_we_do'));
	Router::connect('/services/app-development', array('controller' => 'services', 'action' => 'app_development'));
	Router::connect('/services/cs-cart', array('controller' => 'services', 'action' => 'cs_cart'));
	Router::connect('/services/jshop', array('controller' => 'services', 'action' => 'jshop'));
	Router::connect('/services/lead-generation', array('controller' => 'services', 'action' => 'lead_generation'));
	Router::connect('/services/cyber-security', array('controller' => 'services', 'action' => 'cyber_security'));
	Router::connect('/services/search-marketing', array('controller' => 'services', 'action' => 'search_marketing'));
	Router::connect('/services/social-media', array('controller' => 'services', 'action' => 'social_media'));
	Router::connect('/services/web-design', array('controller' => 'services', 'action' => 'web_design'));
	Router::connect('/services/web-design-step-2', array('controller' => 'services', 'action' => 'web_design_step_2'));
	Router::connect('/services/rightmove-api', array('controller' => 'services', 'action' => 'right_move_api'));
	Router::connect('/services/zoopla-api', array('controller' => 'services', 'action' => 'zoopla_api'));
	Router::connect('/services/free-ssl', array('controller' => 'services', 'action' => 'free_ssl'));
	Router::connect('/services/website-review', array('controller' => 'services', 'action' => 'website_review'));

	Router::connect('/workshop', array('controller' => 'pages', 'action' => 'our_work'));
	Router::connect('/workshop/bucket-of-water', array('controller' => 'workshop', 'action' => 'bucket_of_water'));
	Router::connect('/workshop/caring-companions', array('controller' => 'workshop', 'action' => 'caring_companions'));
	Router::connect('/workshop/cash-reserve-uk', array('controller' => 'workshop', 'action' => 'cash_reserve_uk'));
	Router::connect('/workshop/cass-creek', array('controller' => 'workshop', 'action' => 'cass_creek'));
	Router::connect('/workshop/cwj', array('controller' => 'workshop', 'action' => 'cwj'));
	Router::connect('/workshop/data-dynamo', array('controller' => 'workshop', 'action' => 'data_dynamo'));
	Router::connect('/workshop/delahunt-and-muir', array('controller' => 'workshop', 'action' => 'delahunt_and_muir'));
	Router::connect('/workshop/fabrikate', array('controller' => 'workshop', 'action' => 'fabrikate'));
	Router::connect('/workshop/housegoblin', array('controller' => 'workshop', 'action' => 'housegoblin'));
	Router::connect('/workshop/ihunt-calls', array('controller' => 'workshop', 'action' => 'ihunt_calls'));
	Router::connect('/workshop/ihunt-moose', array('controller' => 'workshop', 'action' => 'ihunt_moose'));
	Router::connect('/workshop/im-rubbish', array('controller' => 'workshop', 'action' => 'im_rubbish'));
	Router::connect('/workshop/independent-women', array('controller' => 'workshop', 'action' => 'independent_women'));
	Router::connect('/workshop/julia-laguz', array('controller' => 'workshop', 'action' => 'julia_laguz'));
	Router::connect('/workshop/joomla-hack-cleanup', array('controller' => 'workshop', 'action' => 'joomla_hack'));
	Router::connect('/workshop/lead-exchange', array('controller' => 'workshop', 'action' => 'lead_exchange'));
	Router::connect('/workshop/letting-a-property', array('controller' => 'workshop', 'action' => 'letting_a_property'));
	Router::connect('/workshop/life-in-hifi', array('controller' => 'workshop', 'action' => 'life_in_hifi'));
	Router::connect('/workshop/llc', array('controller' => 'workshop', 'action' => 'llc'));
	Router::connect('/workshop/my-personal-postcard', array('controller' => 'workshop', 'action' => 'my_personal_postcard'));
	Router::connect('/workshop/magento-hack-cleanup', array('controller' => 'workshop', 'action' => 'magento_hack'));
	Router::connect('/workshop/olive-press', array('controller' => 'workshop', 'action' => 'olive_press'));
	Router::connect('/workshop/only-stores', array('controller' => 'workshop', 'action' => 'only_stores'));
	Router::connect('/workshop/pyramid', array('controller' => 'workshop', 'action' => 'pyramid'));
	Router::connect('/workshop/pappa', array('controller' => 'workshop', 'action' => 'pappa'));
	Router::connect('/workshop/pro-ears', array('controller' => 'workshop', 'action' => 'pro_ears'));
	Router::connect('/workshop/penybont', array('controller' => 'workshop', 'action' => 'penybont'));
	Router::connect('/workshop/pursuit-24', array('controller' => 'workshop', 'action' => 'pursuit_24'));
	Router::connect('/workshop/pursuit-off-road', array('controller' => 'workshop', 'action' => 'pursuit_off_road'));
	Router::connect('/workshop/recycling-bins', array('controller' => 'workshop', 'action' => 'recycling_bins'));
	Router::connect('/workshop/samantha-jones', array('controller' => 'workshop', 'action' => 'samantha_jones'));
	Router::connect('/workshop/simon-curwood-survival', array('controller' => 'workshop', 'action' => 'simon_curwood_survival'));
	Router::connect('/workshop/simple-finance', array('controller' => 'workshop', 'action' => 'simple_finance'));
	Router::connect('/workshop/sms-sender', array('controller' => 'workshop', 'action' => 'sms_sender'));
	Router::connect('/workshop/snap-it-up', array('controller' => 'workshop', 'action' => 'snap_it_up'));
	Router::connect('/workshop/the-swa', array('controller' => 'workshop', 'action' => 'the_swa'));
	Router::connect('/workshop/tacla', array('controller' => 'workshop', 'action' => 'tacla'));
	Router::connect('/workshop/trojan-bins', array('controller' => 'workshop', 'action' => 'trojan_bins'));
	Router::connect('/workshop/uk-meds', array('controller' => 'workshop', 'action' => 'uk_meds'));
	Router::connect('/workshop/venue-cymru', array('controller' => 'workshop', 'action' => 'venue_cymru'));
	Router::connect('/workshop/vw-emissions', array('controller' => 'workshop', 'action' => 'vw_emissions'));
	Router::connect('/workshop/vale-ifa', array('controller' => 'workshop', 'action' => 'valeifa'));
	Router::connect('/workshop/zia-nina', array('controller' => 'workshop', 'action' => 'zia_nina'));

	Router::connect('/blog', array('controller' => 'posts', 'action' => 'index', 'posts'));
	Router::connect('/news', array('controller' => 'posts', 'action' => 'index', 'posts'));


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();



/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';

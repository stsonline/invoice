<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

App::import('Vendor', 'SitemapGenerator');
App::import('Vendor', 'PayPointAPI');
App::import('Vendor', 'BarclaycardAPI');
App::import('Vendor', 'JSBeautifier');
App::import('Vendor', 'ip2location_lite');
App::import('Vendor', 'ip2location');
App::import('Vendor', 'tcpdf');

App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $uses = array('Testimonial', 'Showcase', 'Setting', 'Route', 'Session', 'Action', 'Page', 'Currency');

	public $components = array(
			'Session',
			'Auth' => array(
					'loginRedirect' => array('controller' => 'index', 'action' => 'index'),
					'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
					'authorize' => array('Controller')
			),
			'ControllerList'
	);

	/**
	 *
	 * @var PaypointAPI
	 */
	public $_paypoint;
	public $_barclaycard;

	public $key;
	public $iv;
	public $bit_check;

	public $currencies;

	public $_settings;

	function beforeFilter()
	{
		CakeSession::init();
		CakeSession::start();

		$settings = $this->Setting->find('all');

		$settings = Set::combine($settings, '{n}.Setting.key', '{n}.Setting.value');

		$this->_settings = $settings;
		$this->set('settings', $settings);

		/*if($settings['ip_redirect.enabled']=='true'||false)
		{
			//ip2location_lite webservice
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$ipLite = new ip2location_lite;
			$ipLite->setKey($settings['ip_redirect.ip2location_key']);
			$ip2loc_lite = $ipLite->getCountry($ip_address)  or false;
			$user_country = $ip2loc_lite == true ? $ip2loc_lite['countryCode'] : false;

			$server_nz = $_SERVER['SERVER_NAME'] == 'stsonline.co.nz' || $_SERVER['SERVER_NAME'] == 'www.stsonline.co.nz' ? true : false;

			if($server_nz && $user_country != 'NZ')
			{
				$this->redirect('http://stsonline.uk.com'.$this->here);
			}
			else if(!$server_nz && $user_country == 'NZ')
			{
				$this->redirect('http://stsonline.co.nz'.$this->here);
			}
		}*/

		//SessionComponent::setFlash('TEST notification system ... Your account has a login associated with it, please login now to continue.');


		//setup for mcrypt base64 encryption and decryption
		$secret_phrase = $this->_settings['application.secret_phrase_sha1_hash'];
		$this->key = substr($secret_phrase, 0, 24);// 24 bit Key
		$this->iv = substr(Configure::read('Security.salt'), 0, 8);// 8 bit IV
		$this->bit_check=8;// bit amount for diff algor.

		$currencies_from_model = $this->Currency->find('all');

		$currencies = array();

		foreach($currencies_from_model as $currency)
		{
			$currencies[$currency['Currency']['code']] = array('base_exchange_rate'=>doubleval($currency['Currency']['base_exchange_rate']),'symbol'=>$currency['Currency']['symbol']);
		}

		$this->currencies = $currencies;

		$this->set('currencies',$this->currencies);

		include 'countrieslist.php';
		$this->set('countries', $countries);

		$pp = new PaypointAPI();
		$pp->setOption('secpay_user', 'sincla01');
		$pp->setOption('secpay_password', 'die98MRN');
		$pp->setOption('secpay_mode', $this->_settings['secpay.mode']);
		$this->_paypoint = $pp;

		$epdq = new BarclaycardAPI(); // TODO correct settings
		$epdq->setOption('pspid', 'epdq2204521');
		$epdq->setOption('userid', 'stspayments');
		$epdq->setOption('pswd', 'w0xOQ7Sp[/');
		$epdq->setOption('test_mode', $this->_settings['barclaycard.mode'] == 'test' ? true : false);
		$this->_barclaycard = $epdq;

		/***
		 * Add user's defined routes from db to additional_routes.php
		*/
		$routes = $this->Route->find('all', array('conditions'=>array('disabled'=>false, 'seo_name !='=>'')));

		$myFile = APP . 'Config' . DS . "additional_routes.php";
		$fh = fopen($myFile, 'w') or die("can't open file");;

		fwrite($fh, "<?php\n");

		foreach($routes as $route)
		{
			$route_params = $route['Route']['route_config'];
			fwrite($fh,  "Router::connect('".$route['Route']['seo_name']."', $route_params);\n");
		}
		fclose($fh);




		$ids = $this->Testimonial->find('all' , array('conditions'=>array('Testimonial.disabled' => false)));

		$random_id = $this->getRandomByRange(0, sizeof($ids)-1);

		$id = $ids[$random_id]['Testimonial']['id'];

		$testimonial = $this->Testimonial->find('first', array('conditions'=>array('Testimonial.disabled' => false, 'id'=>$id)));
		$this->set('testimonial',$testimonial);

		$showcase_items = $this->Showcase->find('all', array('conditions'=>array('Showcase.disabled' => false), 'order' => array('Showcase.id')));
		$this->set('showcase_items',$showcase_items);

		$showcase_featured_items = $this->Showcase->find('all', array('conditions'=>array('Showcase.disabled' => false, 'Showcase.featured' => true), 'order' => array('Showcase.id')));
		$this->set('showcase_featured_items',$showcase_featured_items);

		$this->layout = 'IndexLayout';
		parent::beforeFilter();

		$title = $this->action == 'index' ? $this->name : ucfirst($this->action);

		$title = $title == 'Index' ? 'Team 8' : $title;

		$title = str_replace('_', ' ', $title);

		$this->set('title_for_layout',$title.': Web Design Studio');

		//set application mode, useful for deving
		$app_mode = $settings['application.mode'] == 'development' ? 2 : 0;
		Configure::write('debug', $app_mode);



		/**
		 * Set heading text based on url action key, and set to the view
		 * i.e /pages/who_we_are --> who_we_are in this array
		 */
		$headings = array
		(
			'who_we_are'=>array('heading_title'=>"Who We Are",'heading_text'=>"The Welsh web design studio that can help you grow your business. We are based in Bridgend."),
			'what_we_do'=>array('heading_title'=>"What We Do",'heading_text'=>"We have the knowledge and experience to transform how you do business, increase sales and reduce running costs."),
			'miskinit_merge'=>array('heading_title'=>"Miskin IT Merge",'heading_text'=>"Team 8 and Miskin IT have merged, find out more about this below."),
			'our_work'=>array('heading_title'=>"Our Work",'heading_text'=>"Here are a few of our latest projects that you may be interested in."),
			'get_in_touch'=>array('heading_title'=>"Get In Touch",'heading_text'=>"Get in touch with us at Team 8."),
			'bnp_paribas_hackathon'=>array('heading_title'=>"BNP Paribas Hackathon",'heading_text'=>"International development event hosted in London."),
			'posts'=>array('heading_title'=>"Blog",'heading_text'=>"International development event hosted in London."),
			'careers'=>array('heading_title'=>"Careers",'heading_text'=>"We're a varied team of web designers, developers and digital experts"),
			'complaints'=>array('heading_title'=>"Complaints",'heading_text'=>"Have a complaint? Follow our complaints procedure."),
			'feedback'=>array('heading_title'=>"Team 8 Feedback",'heading_text'=>"We welcome your feedback about your experiences with Team 8 Digital's services."),
			'web_design'=>array('heading_title'=>"Web Design Studio",'heading_text'=>"Affordable web design with your business in mind, <strong>from <span>&pound;299</span></strong>"),
			'ecommerce'=>array('heading_title'=>"Ecommerce",'heading_text'=>"Work with us to develop your online store from the ground up and open your doors to a global customer base."),
			'cs_cart'=>array('heading_title'=>"CS-Cart Ecommerce Experts",'heading_text'=>"Work with us to develop your online store with CS-Cart and unlock your full potential."),
			'magento'=>array('heading_title'=>"Magento Ecommerce Systems",'heading_text'=>"Bespoke Magento based ecommerce systems and support."),
			'hosting'=>array('heading_title'=>"Hosting",'heading_text'=>"We offer dependable and scalable web hosting services for all budgets and requirements."),
			'jshop'=>array('heading_title'=>"Import from JShop to CS-Cart",'heading_text'=>"Switch today to see the benefits of using a highly flexible ecommerce system."),
			'job_1'=>array('heading_title'=>"Front End Developer",'heading_text'=>"Front End Developers create the look and feel of the website."),
			'support'=>array('heading_title'=>"Tech Support",'heading_text'=>"Here when you need us, available when you don't."),
			'search_marketing'=>array('heading_title'=>"Search Marketing",'heading_text'=>"Relevant content can prove all the difference and increase your business potential."),
			'cyber_security'=>array('heading_title'=>"Cyber Security",'heading_text'=>"Penetration Testing, Attack Prevention and Hack Cleanup"),
			'app_development'=>array('heading_title'=>"App Development",'heading_text'=>"Develop something your customers can take away and enjoy."),
			'social_media'=>array('heading_title'=>"Social Media",'heading_text'=>"We can help you develop and improve your social presence."),
			'lead_generation'=>array('heading_title'=>"Lead Generation",'heading_text'=>"we work on finding unique ways to attract people to your business."),
			'website_review'=>array('heading_title'=>"Free Website Review",'heading_text'=>"We'd love to give you our options on your online presence."),
			'bucket_of_water'=>array('heading_title'=>"Buy A Bucket Of Water",'heading_text'=>"Design concept for a charity focusing on providing clean water and aid to people and communities in need."),
			'bughat'=>array('heading_title'=>"Bughat",'heading_text'=>"CS-Cart ecommerce system for an outdoor clothing company based in Florida, US."),
			'cass_creek'=>array('heading_title'=>"Cass Creek",'heading_text'=>"CS-Cart ecommerce system for a hunting company based in the US."),
			'cwj'=>array('heading_title'=>"Welsh and Celtic Jewellery",'heading_text'=>"Welsh and Celtic Jewellery offer a range of quality jewellery all at affordable prices."),
			'caring_companions'=>array('heading_title'=>"Caring Companions",'heading_text'=>"A Wordpress themed website that displays the key objective of their business."),
			'data_dynamo'=>array('heading_title'=>"Data Dynamo",'heading_text'=>"Data Dynamo is a bespoke marketing CRM system build for a client in the financial industry."),
			'delahunt_and_muir'=>array('heading_title'=>"Delahunt & Muir",'heading_text'=>"Static website for a new business specialising in a wide selection of Scottish whisky."),
			'direct_pharmacy'=>array('heading_title'=>"Direct Pharmacy",'heading_text'=>"Direct Pharmacy is an online platform designed around Ecommerce to sell medication and treatment products to a wide range of medical problems."),
			'fabrikate'=>array('heading_title'=>"Fabrikate",'heading_text'=>"Fabrikate specialises in offering quality discounted fabrics."),
			'housegoblin'=>array('heading_title'=>"Housegoblin Hardware",'heading_text'=>"Housegoblin Hardware is an online ecommerce system that sells all types of architectural hardware and accessories."),
			'ihunt_calls'=>array('heading_title'=>"iHunt Calls",'heading_text'=>"Outdoor & hunting app for Android and iOS."),
			'ihunt_moose'=>array('heading_title'=>"iHunt Moose",'heading_text'=>"iHunt Moose is a light version of the popular iHunt Calls, outdoor and hunting app."),
			'im_rubbish'=>array('heading_title'=>"Im Rubbish",'heading_text'=>"Waste supplies ecommerce store built in Magento with a fully responsive design."),
			'independent_women'=>array('heading_title'=>"Independent Women",'heading_text'=>"Independent Women is a elegant, bold and friendly company offering financial security advice giving more choice and flexibility."),
			'julia_laguz'=>array('heading_title'=>"Julia Laguz",'heading_text'=>"Julia Laguz, a spiritual website designed to offer services to others."),
			'joomla_hack'=>array('heading_title'=>"Joomla Hack Clean Up",'heading_text'=>"Team 8 provided a hack cleanup solution for a joomla based system."),
			'lacroix'=>array('heading_title'=>"Lacroix",'heading_text'=>"Static website designed to advertise a popular dog breeder."),
			'lead_exchange'=>array('heading_title'=>"Lead Exchange",'heading_text'=>"A bespoke system for handling and brokering applications, primarily for financial products."),
			'life_in_hifi'=>array('heading_title'=>"Life In Hifi",'heading_text'=>"CS-Cart ecommerce system and social media platform for a US based company."),
			'my_personal_postcard'=>array('heading_title'=>"My Personal Postcard",'heading_text'=>"iOS app developed for a travel company."),
			'magento_hack'=>array('heading_title'=>"Magento Hack Clean Up",'heading_text'=>"Team 8 provided a hack cleanup solution for a Magento based system."),
			'olive_press'=>array('heading_title'=>"The Olive Press",'heading_text'=>"Ecommerce system for a company specialising in olive oil and gifts."),
			'only_stores'=>array('heading_title'=>"The Only Stores",'heading_text'=>"A set of CS-Cart ecommerce systems for a US based company specialising in hunting and outdoor activities."),
			'physique'=>array('heading_title'=>"Physique",'heading_text'=>"A well established local gym needed an engaging online presence to increase their membership base."),
			'pappa'=>array('heading_title'=>"PAPPA",'heading_text'=>"PAPPA was developed as part of the international BNP Paribas that Team 8 participated in."),
			'pro_ears'=>array('heading_title'=>"Pro Ears",'heading_text'=>"SEO project focused on a specific set of keywords with the aim of becoming crowd hosted."),
			'pyramid'=>array('heading_title'=>"Pyramid Time",'heading_text'=>"Pyramid Time Systems empowers you with easy-to-use intuitive time clocks. Pyramid Time clocks are reliable and efficient."),
			'penybont'=>array('heading_title'=>"The Venue at Penybont",'heading_text'=>"The Venue Penybont offers a range of room and pitch hire tailored to your needs. The Venue offer value wedding and event packages."),
			'pursuit_24'=>array('heading_title'=>"Pursuit 24",'heading_text'=>"Static website designed to advertise a bushcraft wilderness experience."),
			'pursuit_off_road'=>array('heading_title'=>"Pursuit Off-road",'heading_text'=>"Static website showcasing the offroad adventures available with Pursuit."),
			'pursuit'=>array('heading_title'=>"Pursuit Outdoor",'heading_text'=>"CS-Cart ecommerce system for a local outdoor activity business."),
			'recycling_bins'=>array('heading_title'=>"Recycling Bins",'heading_text'=>"Recyling bins and storage ecommerce store built in Magento with a fully responsive design."),
			'right_move_api'=>array('heading_title'=>"Rightmove API",'heading_text'=>"Team 8 can provide Rightmove API integration for your website. Using various techniques and languages such as PHP, Java or .NET"),
			'sentinel'=>array('heading_title'=>"Sentinel Consultancy",'heading_text'=>"Static website designed for a consultancy firm that are continuing to grow."),
			'simple_finance'=>array('heading_title'=>"Simple Finance",'heading_text'=>"A fully responsive easy to use website designed for the finance industry."),
			'simon_curwood_survival'=>array('heading_title'=>"Simon Curwood Survival",'heading_text'=>"STS designed a straightforward website to advertise outdoor survival techniques."),
			'sms_sender'=>array('heading_title'=>"Data Dynamo / SMS Sender",'heading_text'=>"Data Dynamo is a bespoke marketing CRM system build for a client in the financial industry."),
			'snap_it_up'=>array('heading_title'=>"Snap It Up",'heading_text'=>"An ecommerce store providing recyling and waste bins to businesses and individuals, built in Magento with a fully responsive design."),
			'the_swa'=>array('heading_title'=>"The SWA",'heading_text'=>"Bespoke website with members directory and custom sign up capabilities."),
			'tacla'=>array('heading_title'=>"The Actors Company LA",'heading_text'=>"The Actors Company is committed to linking talented performers with a network of industry professionals."),
			'trojan_bins'=>array('heading_title'=>"Trojan Bins",'heading_text'=>"Ecommerce store focusing on waste and recyling bins, built in Magento with a fully responsive design."),
			'uk_meds'=>array('heading_title'=>"UK Meds",'heading_text'=>"UK Meds is an online pharmacy selling professional treatments to clients."),
			'venue_cymru'=>array('heading_title'=>"Venue Cymru",'heading_text'=>"Dedicated hosting and support for a large arts and events venue in Wales."),
			'vw_emissions'=>array('heading_title'=>"VW Emissions",'heading_text'=>"A site designed to provide the latest information surrounding the recent Volkswagen emissions scandal."),
			'valeifa'=>array('heading_title'=>"Vale IFA",'heading_text'=>"Vale IFA is a firm of financial advisers which provides independent financial advice to individuals."),
			'bridgend_webdesign'=>array('heading_title'=>"Bridgend Web Design",'heading_text'=>"Are you looking for affordable, bespoke web design services in Bridgend, South Wales? Team 8 are the home of web design specialists"),
			'cardiff_webdesign'=>array('heading_title'=>"Cardiff Web Design",'heading_text'=>"At Team 8, we're proud to be the home of affordable web design services in Bridgend. We're now looking for clients in Cardiff too!"),

		);
		$this->set('headings', $headings);

		/***
		 * check actions model, and set Auth component up with the actions permissions as defined
		 */

		//default every controller to allow un logged in users to view
		$this->Auth->allow();

		$controller_name= $this->request->params['controller'];
		$action_name= $this->request->params['action'];

		$user = $this->Session->read('Auth.User');
		$role_id = isset($user) ? $user['role_id'] : 1; //role 1 is guest
        
		//look for action in database
		$an_action = $this->Action->find('first',array(	'conditions' =>
				array(	'controller_name' => $controller_name ,
						'action_name' => $action_name,
						'role_id' => $role_id	)));
		$an_action = isset($an_action['Action']) ? $an_action['Action'] : 0;

		if(($an_action)==0)//if the action isn't in the database create it and allow it for this user role
		{
			$this->Action->create();
			$this->Action->controller_name = $controller_name;
			$this->Action->action_name = $action_name;
			$this->Action->role_id = $role_id;
			$this->Action->allowed = true;
			$this->Action->secure = 0;
			$this->Action->save($this->Action);
		}

		if(isset($an_action['allowed']) && $an_action['allowed']) //if action is permitted, check ssl or not
		{
			$this->Auth->allow();
			if($an_action['secure'])
			{
				if( ! $this->request->is('ssl'))
					$this->redirect('https://' . env('SERVER_NAME') . $this->here);
			}
			else
			{
				if( $this->request->is('ssl'))
					$this->redirect('http://' . env('SERVER_NAME') . $this->here);
			}
		}else
		{
			//$this->Auth->deny();
		}
		//var_dump($user,$an_action);die();
	}

	function afterFilter()
	{
		parent::afterFilter();
	}

	public function isAuthorized($user)
	{
		// top can access every action
		if (isset($user['role_id']) && $user['role_id']==3)//is top
		{
			return true;
		}

		$controller_name= $this->request->params['controller'];
		$action_name= $this->request->params['action'];

		$val = $this->requestAction(array('controller' => 'actions', 'action' => 'permitted'),
				array('pass'=>array($controller_name,$action_name,$user['role_id']))	);


		/*if($val['allowed']) //if action is permitted, check ssl or not
		{
			if($val['secure'])
			{
				if( ! $this->request->is('ssl'))
					$this->redirect('https://' . env('SERVER_NAME') . $this->here);
			}
			else
			{
				if( $this->request->is('ssl'))
					$this->redirect('http://' . env('SERVER_NAME') . $this->here);
			}
		}*/

		return $val['allowed'];
	}

	function __url($default_port = 80)
	{
		$port = env('SERVER_PORT') == $default_port ? '' : ':'.env('SERVER_PORT');
		return env('SERVER_NAME').$port.env('REQUEST_URI');
	}

	public function getRandomByRange($min, $max)
	{

		$random_number = rand($min, $max);

		return $random_number;
	}

	public function generateSitemap()
	{
		// create object
        $sitemap = new SitemapGenerator(Router::url('/', true));

        // add urls

        $excludeFromSitemapArr = array(
        		'AdminLayoutController',
        		'ActionsController',
        		'ClientsController',
        		'DashboardController',
        		'InvoicesController',
        		'PageController',
        		'IndexController',
        		'PayController',
        		'PaymentsController',
        		'ResponsesController',
        		'SystemController',
				'UsersController',
        		'ArticlesController',
        		'AjaxController'
        );

        $controllers = $this->ControllerList->getList($excludeFromSitemapArr);

        //add home page
        $sitemap->addUrl(Router::url('/', true), date('c'),  'weekly',    '1');

        //add static routes
        $routes = $this->Route->find('all', array('conditions'=>array('disabled'=>false, 'seo_name !='=>'')));

        foreach($routes as $route)
        {
        	$url = substr($route['Route']['seo_name'],0,1) == '/' ? substr($route['Route']['seo_name'], 1) : $route['Route']['seo_name'];
        	$sitemap->addUrl(Router::url('/', true).$url, date('c'),  'weekly',    '1');
        }

        //add showcases
        $showcases = $this->Showcase->find('all', array('conditions'=>array('Showcase.disabled !=' => '1'), 'order' => array('Showcase.id')));
        foreach($showcases as $showcase)
        {
        	$sitemap->addUrl(Router::url('/', true).'showcase/'.$showcase['Showcase']['seo_name'], date('c'),  'monthly',    '0.8');
        }

        //add pages
        $pages = $this->Page->find('all', array('conditions'=>array('Page.disabled !=' => '1'), 'order' => array('Page.id')));
        foreach($pages as $page)
        {
        	$sitemap->addUrl(Router::url('/', true).'page/'.$page['Page']['seo_name'], date('c'),  'monthly',    '0.8');
        }


		//add whitelisted controllers and actions
        foreach($controllers as $controller)
        {
        	$actions = isset($controller['actions']) ? $controller['actions'] : array();

			foreach($actions as $action)
			{
				$action = $action=='index' ? '' : $action;
				$sitemap->addUrl(Router::url('/', true).strtolower($controller['name']).'/'.strtolower($action), date('c'),  'monthly',    '0.5');
			}


        }



        // create sitemap
        $sitemap->createSitemap();

        // write sitemap as file
        $sitemap->writeSitemap();

        // update robots.txt file
        $sitemap->updateRobots();

		return $sitemap;

	}

	public function submitSitemap()
	{
		$sitemap = $this->generateSitemap();
		return $sitemap->submitSitemap();
	}

	public function modelEditor( $model, $id = null, $operation = null, $fieldlist = null, $debug=false, $redirecturl = false)
	{
		Controller::loadModel($model);

		$lowercase_model = strtolower($model);
		$lowercase_model_plural = strtolower($model).'s';

		$this->set('model', $model);
		$this->set('lowercase_model', $lowercase_model);
		$this->set('lowercase_model_plural', $lowercase_model_plural);
		$this->set('fieldlist', $fieldlist);
		$this->set('debug', $debug);

		if(isset($id))
		{
			if(isset($operation)&&$operation=='delete')
			{
				//delete model
				$this->$model->delete($id);
				//$this->redirect(Router::url('/', true).'system/'.$lowercase_model_plural);
				$this->Session->setFlash("Deleted.");
				if($redirecturl)
				{
					$this->redirect(Router::url('/', true).$redirecturl);
				}else
				{
					$this->redirect($this->referer());
				}
			}
			else if(isset($operation)&&$operation=='add')
			{
				//add model
				$this->$model->create();
				$this->$model->date = date("m/d/y g:i A", time());
				$this->$model->save();
				//$this->redirect(Router::url('/', true).'system/'.$lowercase_model_plural.'/'.$this->$model->id);
				$this->Session->setFlash("Added.");

				if($redirecturl)
				{
					$this->redirect(Router::url('/', true).$redirecturl);
				}else
				{
					$this->redirect($this->referer());
				}
			}

			if (empty($this->request->data)) {
				//display a particular model
				$this->request->data = $this->$model->findById($id);
				$this->set('model_single',$this->request->data);
			} else {
				//save a particular model
				$this->request->data[$model]['id'] = $id;
				$this->$model->save($this->request->data);
				//$this->redirect(Router::url('/', true).'system/'.$lowercase_model_plural);
				$this->Session->setFlash("Saved.");
				if($redirecturl)
				{
					$this->redirect(Router::url('/', true).$redirecturl);
				}else
				{
					$this->redirect($this->referer());
				}
			}
		}else
		{
			//listing of all models
			$this->set('model_plural',$this->$model->find('all', array('order'=>$model.'.id DESC')));
		}
	}

	public function sendAdminEmail($subject, $message)
	{
		$email = new CakeEmail('stsOnline');

		// Add the content headers
		$email->setHeaders(	array('MIME-Version' => '1.0','Content-Type' => 'text/html; charset=iso-8859-1'));

		//$layoutPath = $this->layoutPath;
		//$this->layoutPath = 'Emails/html';
		$email->template('default', 'STS_layout');
		//Check client details and set email
		$email->subject($subject);
		$email_to = $this->_settings['admin_email'];
		$email_to = explode(";",$email_to);
		$email->to($email_to);
		$email->emailFormat('html'); //see below for converting html to text

		try
		{
			if(	$email->send($message) )
			{
				//$this->Session->setFlash('mail sent: '.$subject);
			}
		}catch(Exception $e)
		{

		}

		return true;
	}

	public function sendEmail($subject, $message, $to, $from = null)
	{
		$email = new CakeEmail('sinclairNew');

		// Add the content headers
		$email->setHeaders(	array('MIME-Version' => '1.0','Content-Type' => 'text/html; charset=iso-8859-1'));

		//$layoutPath = $this->layoutPath;
		//$this->layoutPath = 'Emails/html';
		$email->template('default', 'STS_layout');
		//Check client details and set email
		$email->subject($subject);
		$email->to($to);
		$email->emailFormat('html');

		if(isset($from))
		{
			//if supplied, override the default from identity
			$email->from($from);
		}
		try
		{
			if(	$email->send($message) )
			{
				//$this->Session->setFlash('mail sent: '.$subject);
			}
		}catch(Exception $e)
		{

		}

		return true;
	}


	/**
	 * got these from http://www.php.net/manual/en/mcrypt.examples.php
	 * @param unknown $text
	 * @param unknown $key
	 * @param unknown $iv
	 * @param unknown $bit_check
	 * @return string
	 */
	public function encrypt($text,$key,$iv,$bit_check)
	{
		$text_num =str_split($text,$bit_check);
		$text_num = $bit_check-strlen($text_num[count($text_num)-1]);
		for ($i=0;$i<$text_num; $i++) {$text = $text . chr($text_num);}
		$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
		mcrypt_generic_init($cipher, $key, $iv);
		$decrypted = mcrypt_generic($cipher,$text);
		mcrypt_generic_deinit($cipher);
		return base64_encode($decrypted);
	}

	public function decrypt($encrypted_text,$key,$iv,$bit_check){
		$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
		mcrypt_generic_init($cipher, $key, $iv);
		$decrypted = mdecrypt_generic($cipher,base64_decode($encrypted_text));
		mcrypt_generic_deinit($cipher);
		$last_char=substr($decrypted,-1);
		for($i=0;$i<$bit_check-1; $i++){
			if(chr($i)==$last_char){
				$decrypted=substr($decrypted,0,strlen($decrypted)-$i);
				break;
			}
		}
		return $decrypted;
	}

}

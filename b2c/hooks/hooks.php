<?php

class application {
	public $header_needle; 
	public $footer_needle; 
	public $live_username; 
	public $live_password; 
	public $flight_engine_system; 
	public $bus_engine_system; 
	public $transfer_engine_system; 
	public $external_service_system; 
	public $sightseeing_engine_system; 
	public $entity_domain_phone; 
	public $topDestFlightsBlog; 
	public $entity_title; 
	public $entity_first_name; 
	public $entity_last_name; 
	public $entity_created_datetime; 
	public $entity_language_preference; 
	public $entity_signature; 
	public $discount_coupon_origin; 
	public $car_engine_system;
	public $entity_domain_mail;
	public $application_domain_logo;
	public $application_address;
	public $topDestFlights;
	public $blog;
	public $active_domain_modules;
	public $entity_user_id;
	public $entity_domain_id;
	public $entity_uuid;
	public $entity_user_type;
	public $entity_email;
	public $enumeration ;
	public $entity_name;
	public $entity_address ;
	public $entity_phone ;
	public $entity_country_code ;
	public $entity_status ;
	public $entity_date_of_birth ;
	public $entity_image ;
	public $current_page ;
	public $test_username ;
	public $test_password ;
	public $hotel_engine_system ;
	public $flight_url ;
	public $hotel_url ;
	public $bus_url ;
	public $transferv1_url;
	public $transfer_url;
	public $sightseeing_url ;
	public $car_url ;
	public $external_service ;
	public $domain_key;
	public $master_search_data;
	public $pg;
	




	var $CI; // code igniter object
	var $userId; // user id to identify user
	var $page_configuration;
	var $skip_validation;

	/**
	 * constructor to initialize data
	 */
	function __construct() {
		$this->CI = &get_instance ();
		$this->CI->load->library ( 'provab_page_loader.php' );
		$this->CI->load->helper ( 'url' );
		if (! isset ( $this->CI->session )) {
			$this->CI->load->library ( 'session' );
		}
		$this->footer_needle = $this->header_needle = $this->CI->uri->segment ( 2 );
		$this->skip_validation = false;
		$this->CI->language_preference = 'english';
		$this->CI->lang->load ( 'form', $this->CI->language_preference );
		$this->CI->lang->load ( 'application', $this->CI->language_preference );
		$this->CI->lang->load ( 'utility', $this->CI->language_preference );
		// $this->CI->session->set_userdata(array(AUTH_USER_POINTER => 10, LOGIN_POINTER => intval(100)));
	}
	/**
	 * We need to initialize all the domain key details here
	 * Written only for provab
	 */
	function initialize_domain_key() {
		$this->CI->entityDomain = (strpos($_SERVER['HTTP_HOST'], '.in') !== false) ? 2 : 1;
		$domain_auth_id = 1;
		$domain_key = CURRENT_DOMAIN_KEY;
		$domain_details = $GLOBALS ['CI']->custom_db->single_table_records ( 'domain_list', '*', array ( 'domain_key' => CURRENT_DOMAIN_KEY ) );
		
		$module = $this->CI->uri->segment(1);
		if(in_array($module,['hotels', 'flights'])){
			$module = str_replace('s', '',$module);
		}
		$this->CI->canonicalTagContent = $module;
		$hook_banner_images = $GLOBALS ['CI']->custom_db->single_table_records('banner_images', '*', array('status' => '1','module'=>$module));
		if($hook_banner_images['status'] == SUCCESS_STATUS){
			$getSlideImages = $hook_banner_images['data'];
			$slideImageArray = array();
			
			foreach ($getSlideImages as $k) {
				$slideImageArray[] = array('image' => $GLOBALS['CI']->template->template_images() . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
			}
			$this->CI->slideImageJson = [];
			$this->CI->slideImageJson = $slideImageArray;
		}else{
			$hook_banner_images = $GLOBALS ['CI']->custom_db->single_table_records('banner_images', '*', array('status' => '1'));
			if($hook_banner_images['status'] == SUCCESS_STATUS){
				$getSlideImages = $hook_banner_images['data'];
				$slideImageArray = array();
				
				foreach ($getSlideImages as $k) {
					$slideImageArray[] = array('image' => $GLOBALS['CI']->template->template_images() . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
				}
				$this->CI->slideImageJson = [];
				$this->CI->slideImageJson = $slideImageArray;
			}
		}
		$hook_top_deals_date = date('Y-m-d');
		$hook_top_deals = $GLOBALS ['CI']->custom_db->single_table_records ( 'promo_code_list', '*',array('module' => $module,'status'=>ACTIVE,'display_home_page' => 'Yes','expiry_date >=' => $hook_top_deals_date) );
		$this->CI->hook_top_deals = [];
			if($hook_top_deals['status'] == SUCCESS_STATUS){
				$this->CI->hook_top_deals = $hook_top_deals['data'];
			}else{
				$hook_top_deals = $GLOBALS ['CI']->custom_db->single_table_records ( 'promo_code_list', '*',array('status'=>ACTIVE,'display_home_page' => 'Yes','expiry_date >=' => $hook_top_deals_date) );
				if($hook_top_deals['status'] == SUCCESS_STATUS){
				$this->CI->hook_top_deals = $hook_top_deals['data'];
			}
			}
		$blog_module1 = $GLOBALS ['CI']->uri->segment(1);
		$blog_module2 = $GLOBALS ['CI']->uri->segment(2);
		$blog_module3 = $GLOBALS ['CI']->uri->segment(3);
		if ($blog_module1=='blog' && isset($blog_module2)) {
			$seo_details['data']=$this->CI->db->query("SELECT seo_title AS title, seo_keywords AS keywords, seo_description AS description FROM blog WHERE blog_url='$blog_module2'")->result_array();
			$seo_details['status']=1;
		}else{	
			if (($blog_module1 == 'flights' || $blog_module1 == 'hotels') && isset($blog_module2)) {
				$blog_module2 = str_replace('%20', ' ', $blog_module2);
				$seo_details = $GLOBALS['CI']->custom_db->single_table_records('seo', '*', array('module' => $blog_module2));
				if ($seo_details['status'] != SUCCESS_STATUS) {
					$seo_details = $GLOBALS['CI']->custom_db->single_table_records('seo', '*', array('module' => $module));
				}
			} else {
				$seo_details = $GLOBALS['CI']->custom_db->single_table_records('seo', '*', array('module' => $module));
			}
		}
		$flightTopDest = $GLOBALS ['CI']->custom_db->single_table_records ( 'top_flight_destinations', '*',array('status' => ACTIVE),0,12,array ( 'from_airport_name' => 'ASC', 'origin' => 'ASC', ) );
		$flightTopDestBlog = $GLOBALS ['CI']->custom_db->single_table_records ( 'top_flight_destinations', '*',array('status' => ACTIVE),0,100,array ( 'from_airport_name' => 'ASC', ) );
		
		$blog = $GLOBALS ['CI']->custom_db->single_table_records ( 'blog', '*',array('status' => ACTIVE),0,12 );
		if (valid_array ( $domain_details ) == true) {
			// IF DOMAIN KEY IS NOT SET, THEN SET THE DOMANIN DETAILS
			$domain_details = $domain_details ['data'] [0];
			//$this->CI->application_default_template = $domain_details['theme_id'];
			$this->CI->application_default_template = isset($_GET['theme']) ? $_GET['theme'] : $domain_details['theme_id'];
			$this->CI->entity_domain_name = $domain_details ['domain_name'];
			$this->CI->entity_domain_website = $domain_details ['domain_webiste'];
			$this->CI->entity_domain_phone = $domain_details['phone'];
			$this->CI->entity_domain_phone_code = $domain_details['phone_code'];
			$this->CI->entity_domain_mail = $domain_details['email'];
			$this->CI->application_domain_logo = $domain_details['domain_logo'];
			$this->CI->application_address = $domain_details['address'];
			//if (intval ( $domain_auth_id ) == 0 && empty ( $domain_key ) == true and strlen ( trim ( $domain_details ['domain_name'] ) ) > 0) {
			if (strlen ( trim ( $domain_details ['domain_name'] ) ) > 0) {//CHECK THIS
				$domain_session_data = array ();
				// SETTING DOMAIN KEY
				$domain_session_data [DOMAIN_AUTH_ID] = intval ( $domain_details ['origin'] );
				// SETTING DOMAIN CONFIGURATION
				$domain_session_data [DOMAIN_KEY] = base64_encode ( trim ( $domain_details ['domain_key'] ) );
				$this->CI->session->set_userdata ( $domain_session_data );
			}
			$this->CI->topDestFlights = [];
			$this->CI->topDestFlightsBlog = [];
			if($flightTopDest['status'] == SUCCESS_STATUS){
				$this->CI->topDestFlights = $flightTopDest['data'];
				$this->CI->topDestFlightsBlog = $flightTopDestBlog['data'];
			}
			$this->CI->blog = [];
			if($blog['status'] == SUCCESS_STATUS){
				$this->CI->blog = $blog['data'];
			}
		}
		
		define('HEADER_DOMAIN_WEBSITE', $this->CI->entity_domain_website);
		define('HEADER_DOMAIN_NAME', $this->CI->entity_domain_name);
		if($seo_details['status'] == SUCCESS_STATUS){
			define('HEADER_TITLE_SUFFIX', $seo_details['data'][0]['title']); // Common Suffix For All Pages
			define('META_KEYWORDS', $seo_details['data'][0]['keyword']); // Common Suffix For All Pages
			define('META_DESCRIPTION', $seo_details['data'][0]['description']); // Common Suffix For All Pages
		}else{
			if (empty($this->CI->entity_domain_name) == false) {
				define('HEADER_TITLE_SUFFIX', ' - Welcome'.$this->CI->entity_domain_name); // Common Suffix For All Pages
			} else {
				define('HEADER_TITLE_SUFFIX', ' - Welcome Travels'); // Common Suffix For All Pages
			}
			define('META_KEYWORDS', HEADER_TITLE_SUFFIX. "Flights, Hotels, Cars, Packages, Low Cost Flights");
			define('META_DESCRIPTION', 'Travels Flight Bookings, Hotel Bookings, Car Bookings, Transfer Bookings, Activitiy Bookings and Package bookings system.');
		}
		if(empty($this->CI->session->userdata('currency'))){
			$country_code = "US";
			/* $ip = $_SERVER['REMOTE_ADDR'];
			$ch = curl_init();	
			curl_setopt($ch, CURLOPT_URL, "http://api.ipstack.com/".$ip."?access_key=gasdfasdfwerwerwerwes");	
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                                         
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);		
			$get_resulted_data = json_decode($output);
			if(!empty($get_resulted_data->country_code))
			{
				$country_code = $get_resulted_data->country_code;
			} */
			$currency_code = $this->CI->db->query('SELECT * FROM `currency` WHERE iso="'.$country_code.'"')->result();
			if(!empty($currency_code[0]->currency))
			{
				$this->CI->session->set_userdata(array('currency' => $currency_code[0]->currency));
			}
		}
		
	}
	/**
	 * Set all the active modules for doamin
	 */
	function initialize_domain_modules() {
		// set domain active modules based on auth key
		$domain_key = CURRENT_DOMAIN_KEY;
		$domain_auth_id = 1;
		// set global modules data
		$active_domain_modules = $this->CI->module_model->get_active_module_list ( $domain_auth_id, $domain_key );
		 //Sdebug($active_domain_modules);exit;
		$this->CI->active_domain_modules = $active_domain_modules;
	}
	/**
	 * Following pages will not have any validations
	 */
	function bouncer_page_validation() {
		$skip_validation_list = array ( 'forgot_password','get_hotel_list' ); // SKIP LIST
				if (in_array ( $this->header_needle, $skip_validation_list )) {
					$this->skip_validation = true;
				}
	}
	/**
	 * Handle hook for multiple page login system
	 */
	function initilize_multiple_login() {
		$this->bouncer_page_validation ();
		if ($this->skip_validation == false) {
			$auth_login_id = $this->CI->session->userdata ( AUTH_USER_POINTER );
			if (empty ( $auth_login_id ) == false) {
				$condition ['uuid'] = $auth_login_id;
				$condition ['status'] = ACTIVE;
				$condition ['user_type'] = B2C_USER;
			}
			if (isset ( $condition ) == true and is_array ( $condition ) == true and count ( $condition ) > 0) {
				$condition ['status'] = ACTIVE;
				$user_details = $this->CI->db->get_where ( 'user', $condition )->row_array ();
				if (valid_array ( $user_details ) == true) {
					$this->set_global_entity_data ( $user_details );
				}
			}
		}
	}
	function set_global_entity_data($user_details) {
		$this->CI->entity_user_id = $user_details ['user_id'];
		$this->CI->entity_domain_id = $user_details ['domain_list_fk'];
		$this->CI->entity_uuid = provab_decrypt($user_details ['uuid']);
		$this->CI->entity_user_type = $user_details ['user_type'];
		$this->CI->entity_email = provab_decrypt($user_details ['email']);
		$this->CI->entity_title = $user_details ['title'];
		$this->CI->entity_first_name = $user_details ['first_name'];
		$this->CI->entity_signature = $user_details ['signature'];
		$this->CI->entity_last_name = $user_details ['last_name'];
		$this->CI->entity_name = get_enum_list ( 'title', $user_details ['title'] ) . ' ' . ucfirst ( ($user_details['first_name'] ?? '') ) . ' ' . ucfirst ( ($user_details['last_name'] ?? '') );
		$this->CI->entity_address = $user_details ['address'];
		$this->CI->entity_phone = $user_details ['phone'];
		$this->CI->entity_country_code = $user_details ['country_code'];
		$this->CI->entity_status = $user_details ['status'];
		$this->CI->entity_date_of_birth = $user_details ['date_of_birth'];
		$this->CI->entity_image = $user_details ['image'];
		$this->CI->entity_created_datetime = $user_details ['created_datetime'];
		$this->CI->entity_language_preference = $user_details ['language_preference'];
		$this->CI->discount_coupon_origin = $user_details ['discount_coupon_origin'];
	}
	/**
	 * function to update login time and logout time details of user when user
	 * login or logout.
	 */
	function update_login_manager() {
		$loginDetails ['browser'] = $_SERVER ['HTTP_USER_AGENT'];
		$remote_ip = $_SERVER ['REMOTE_ADDR'];
		$loginDetails ['info'] = file_get_contents ( "http://ipinfo.io/" . $remote_ip . "/json" );
		$checkLogin = $this->CI->custom_db->single_table_records ( 'login_manager', '*', array (
				'user_id' => $this->CI->entity_user_id,
				'login_ip !=' => $remote_ip 
		), '0', '10', '' );
		if (empty ( $checkLogin ['data'] ) == true) {
			$checkLoginSameIP = $this->CI->custom_db->single_table_records ( 'login_manager', '*', array (
					'user_id' => $this->CI->entity_uuid,
					'login_ip' => $remote_ip 
			), '0', '10', '' );
			if (empty ( $checkLoginSameIP ['data'] ) == false) {
				$loginID ['insert_id'] = isset ( $this->CI->session->userdata [LOGIN_POINTER] ) ? $this->CI->session->userdata [LOGIN_POINTER] : $this->CI->entity_user_id;
			} else {
				$loginID = $this->CI->custom_db->insert_record ( 'login_manager', array (
						'user_type' => $this->CI->entity_user_type,
						'user_id' => $this->CI->entity_uuid,
						'login_date_time' => date ( 'Y-m-d H:i:s', time () ),
						'login_ip' => $remote_ip,
						'attributes' => mysql_real_escape_string ( json_encode ( $loginDetails ) ) 
				) );
			}
		} else {
			$this->CI->custom_db->update_record ( 'login_manager', array (
					'logout_date_time' => date ( 'Y-m-d H:i:s', time () ) 
			), array (
					'user_id' => $this->CI->entity_uuid 
			) );
			$loginID = $this->CI->custom_db->insert_record ( 'login_manager', array (
					'user_type' => $this->CI->entity_user_type,
					'user_id' => $this->CI->entity_uuid,
					'login_date_time' => date ( 'Y-m-d H:i:s', time () ),
					'login_ip' => $remote_ip,
					'attributes' => mysql_real_escape_string ( json_encode ( $loginDetails ) ) 
			) );
		}
		return $loginID ['insert_id'];
	}
	/*
	 * load current page configuration
	 */
	function load_current_page_configuration() {
		//$this->set_page_configuration ();
		$this->page_configuration ['current_page'] = $this->CI->current_page = new Provab_Page_Loader ();
	}
	/**
	 * This file specifies which systems should be loaded by default for each page.
	 *
	 * @param unknown_type $controller
	 * @param unknown_type $method
	 */
	function set_page_configuration() {
		/*$controller = $this->CI->uri->segment ( 1 );
		 $method = $this->CI->uri->segment ( 2 );
		 $temp_configuration ['general'] ['index'] = array (
		 'header_title' => 'AL001',
		 'menu' => false,
		 'page_keywords' => array (
		 'meta' => '',
		 'author' => ''
		 ),
		 'page_small_icon' => ''
		 );
		 $this->page_configuration = $temp_configuration ['general'] ['index'];*/
	}
	function set_project_configuration(){
		$api_data = $this->CI->custom_db->single_table_records ( 'api_urls_new', '*',array ('status' => 1));
		$domain_list = $this->CI->custom_db->single_table_records ( 'domain_list', '*',array ('status' => 1));
		if($api_data['status'] == true){
			$api_data = $api_data['data'][0];
			if($api_data['system'] == 'Test'){
				$system = 'test';
				$this->CI->test_username = $domain_list['data'][0]['test_username'];
				$this->CI->test_password = $domain_list['data'][0]['test_password'];
			}
			else{
				$system = 'live';
				$this->CI->live_username = $domain_list['data'][0]['live_username'];
				$this->CI->live_password = $domain_list['data'][0]['live_password'];
			}
			
			$this->CI->flight_engine_system = $system; //test/live
			$this->CI->hotel_engine_system = $system;//test/live
			$this->CI->bus_engine_system = $system; //test/live
			$this->CI->transfer_engine_system = $system; //test/live
			$this->CI->external_service_system = $system; //test/live
			$this->CI->sightseeing_engine_system = $system;
			$this->CI->car_engine_system = $system; //test/live
			$secret_iv = PROVAB_SECRET_IV;
			$md5_key = PROVAB_MD5_SECRET;
	        $encrypt_key = PROVAB_ENC_KEY;
	        $encrypt_method = "AES-256-CBC";
	        $decrypt_password = $this->CI->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
	          
	        $db_data = $decrypt_password->row();
	         
	        $secret_key = trim($db_data->decrypt_data); 
	        $key = hash('sha256', $secret_key);
	        $iv = substr(hash('sha256', $secret_iv), 0, 16);
	        $urls = openssl_decrypt(base64_decode($api_data['urls']), $encrypt_method, $key, 0, $iv);

			$urls = json_decode($urls, true);
			//api urls
			//debug($urls);exit;
			$this->CI->flight_url = $urls['flight_url'];
			$this->CI->hotel_url = $urls['hotel_url'];
			$this->CI->bus_url = $urls['bus_url'];
			$this->CI->transferv1_url = $urls['transfer_url'];
			$this->CI->transfer_url = 'http://prod.services.travelomatix.com/webservices/transfer/service/';
			$this->CI->sightseeing_url =  $urls['activity_url'];
			$this->CI->car_url = $urls['car_url'];
			$this->CI->external_service = $urls['external_service'];
			$this->CI->domain_key = $domain_list['data'][0]['domain_key'];
			
		}
		
	}
}

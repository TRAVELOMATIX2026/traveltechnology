<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author: Shaik Jani
 * @Email: shaik.jani@provabmail.com
 * @Date: 2025-03-25
 * @package: 
 * @Description: 
 * @version: 2.0
 **/
class Hotel extends MY_Controller
{
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		//we need to activate hotel api which are active for current domain and load those libraries
		$this->load->model('hotel_model');
		$this->load->library('social_network/facebook'); //Facebook Library to enable login button		
		$this->current_module = $this->config->item('current_module');
	}
	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
		//	echo number_format(0, 2, '.', '');
	}


	function load_static_tbo_city()
	{
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

		exit('remove me to continue.');

		$AuthorizationToken = "U3RhcmxlZ2VuZHM6U3RhQDEzMjE1Mzg2";

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CountryList',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Basic '.$AuthorizationToken.''
		  ),
		));

		$CountryListResponse = curl_exec($curl);

		curl_close($curl);

		$CountryListArr = json_decode($CountryListResponse,true);

		if(isset($CountryListArr['Status']['Code']) && $CountryListArr['Status']['Code'] == 200 && count($CountryListArr['CountryList'])>0)
		{

			for($i=0;$i<count($CountryListArr['CountryList']);$i++)
			{
				$CountryCode = $CountryListArr['CountryList'][$i]['Code'];
				$CountryName = $CountryListArr['CountryList'][$i]['Name'];

				$request = array("CountryCode"=>$CountryCode);

				$request_json = json_encode($request,true);

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/CityList',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>$request_json,
				  CURLOPT_HTTPHEADER => array(
				    'Content-Type: application/json',
				    'Authorization: Basic '.$AuthorizationToken.''
				  ),
				));

				$CityListResponse = curl_exec($curl);

				//debug($CityListResponse); exit();

				curl_close($curl);

				$CityListArr = json_decode($CityListResponse,true);

				if(isset($CityListArr['Status']['Code']) && $CityListArr['Status']['Code'] == 200 && isset($CityListArr['CityList']) && count($CityListArr['CityList'])>0)
				{
					for($j=0;$j<count($CityListArr['CityList']);$j++)
					{
						$CityCode = $CityListArr['CityList'][$j]['Code'];
						$CityName = $CityListArr['CityList'][$j]['Name'];

						$cityData = array(
							'cityid' => $CityCode,
							'city_name' => preg_replace("/[^a-zA-Z0-9 ]/", "", $CityName),
							'country_name' => preg_replace("/[^a-zA-Z0-9 ]/", "", $CountryName),
							'country_code' => $CountryCode
						);

						//debug($cityData); exit;

							$this->db->insert('tbo_city_list_new', $cityData);

						    if ($this->db->affected_rows() > 0) {
						        echo $j."<br>";
						    } 
						    else 
						    {
						        echo $j."<br>Insertion failed.";
						    }

					}

					echo "<br> All city inserted.";
				}
				else
				{
					echo "<br>".$CountryCode;
					echo "<br> CityList not found.";

					continue;
				}
			}
		}

		//debug($CountryListArr);

	}


	function load_static_tbo_hotel()
	{
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);

		$AuthorizationToken = "U3RhcmxlZ2VuZHM6U3RhQDEzMjE1Mzg2";


		$limit = 1000;
		$offset = 0;

		$this->db->limit($limit, $offset);
		$query = $this->db->get('tbo_city_list_new');
		$result = $query->result_array();

		
		for($i=0;$i<count($result);$i++)
		{
			$request = array(
				"CityCode" => $result[$i]['cityid'],
			    "IsDetailedResponse" => "true"
			);

			$request_json = json_encode($request);

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/TBOHotelCodeList',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS =>$request_json,
			  CURLOPT_HTTPHEADER => array(
			    'Content-Type: application/json',
			    'Authorization: Basic '.$AuthorizationToken.''
			  ),
			));

			$TBOHotelCodeListResponse = curl_exec($curl);

			curl_close($curl);


			$TBOHotelCodeListArr = json_decode($TBOHotelCodeListResponse, true);


		if(isset($TBOHotelCodeListArr['Status']['Code']) && $TBOHotelCodeListArr['Status']['Code'] == 200 && isset($TBOHotelCodeListArr['Hotels']) && count($TBOHotelCodeListArr['Hotels'])>0)
		{

			if($result[$i]['cache_hotels_count'] == count($TBOHotelCodeListArr['Hotels']))
			{
				continue;
			}

			exit('remove me to continue.');

			$this->db->where('cityid', $result[$i]['cityid']);
			$this->db->update('tbo_city_list_new', ["cache_hotels_count"=>count($TBOHotelCodeListArr['Hotels'])]);


			for($k=0;$k<count($TBOHotelCodeListArr['Hotels']);$k++)
			{

				$maps = $TBOHotelCodeListArr['Hotels'][$k]['Map'];

				$MapArr = explode("|",$maps);

				$latitude = $MapArr[0];
				$longitude = $MapArr[1];


				$Attractions = json_encode($TBOHotelCodeListArr['Hotels'][$k]['Attractions'],true);

				$HotelFacilities = json_encode($TBOHotelCodeListArr['Hotels'][$k]['HotelFacilities'],true);


		    	$data = array(
		        'booking_source'           => "PTBSID00000011",
		        'unique_code'              => $TBOHotelCodeListArr['Hotels'][$k]['HotelCode'],
		        'hotel_code'               => $TBOHotelCodeListArr['Hotels'][$k]['HotelCode'],
		        'country_name'             => $TBOHotelCodeListArr['Hotels'][$k]['CountryName'],
		        'city_name'                => $TBOHotelCodeListArr['Hotels'][$k]['CityName'],
		        'country_code'             => strtoupper($TBOHotelCodeListArr['Hotels'][$k]['CountryCode']),
		        'city_code'                => $result[$i]['cityid'],
		        'hotel_name'               => $TBOHotelCodeListArr['Hotels'][$k]['HotelName'],
		        'address'                  => $TBOHotelCodeListArr['Hotels'][$k]['Address'],
		        'pincode'                  => $TBOHotelCodeListArr['Hotels'][$k]['PinCode'],
		        'phone_number'             => $TBOHotelCodeListArr['Hotels'][$k]['PhoneNumber'],
		        'fax'                      => $TBOHotelCodeListArr['Hotels'][$k]['FaxNumber'],
		        'website'                  => $TBOHotelCodeListArr['Hotels'][$k]['HotelWebsiteUrl'],
		        'star_rating'              => $this->getStarRating($TBOHotelCodeListArr['Hotels'][$k]['HotelRating']),
		        'latitude'                 => $latitude,
		        'longitude'                => $longitude,
		        'hotel_desc'               => $TBOHotelCodeListArr['Hotels'][$k]['Description'],
		        'hotel_faci'               => $HotelFacilities,
		        'attractions'              => $Attractions,
		        'trip_adv_rating'          => $TBOHotelCodeListArr['Hotels'][$k]['TripAdvisorRating'],
		    );


		   // debug($data); exit;

		    $this->db->insert('tbo_master_hotel_details_new', $data);

		    if ($this->db->affected_rows() > 0) {
		       // echo $k."<br>";
		    } else {
		        echo $k."<br> Insertion failed.<br>";

		        continue;
		    }

	 	}

	 	echo "<br>All hotel inserted, count :".$k."<br>Last_City_Code:".$result[$i]['cityid']."<br>";
				
		}
		else
		{
			echo "<br>Hotels not found, ".$result[$i]['cityid']."<br>";
		}
	 }

	 echo "All hotel inserted";

	}



	function getStarRating($rating)
	{

	    switch ($rating) {
	        case 'OneStar':
	            return 1;
	        case 'TwoStar':
	            return 2;
	        case 'ThreeStar':
	            return 3;
	        case 'FourStar':
	            return 4;
	        case 'FiveStar':
	            return 5;
	        case 'SevenStar':
	            return 7;
	        case 'SixStar':
	            return 6;
	        default:
	            return 0; // Unknown or unrated
	    }

	}


	/**
	 *  
	 * Load Hotel Search Result
	 * @param number $search_id unique number which identifies search criteria given by user at the time of searching
	 */
	function search($search_id)
	{
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		//debug($safe_search_data); exit;
		// Get all the hotels bookings source which are active
		$active_booking_source = $this->hotel_model->active_booking_source();
		// debug($active_booking_source);exit;
		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
			$safe_search_data['data']['search_id'] = abs($search_id);
			$b2c_label = $this->custom_db->single_table_records('label', '*', array('status' => 1, 'module' => 'B2C'));
			$safe_search_data['data']['b2c_label'] = $b2c_label['data'];
			$safe_search_data['data']['left_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Left' and status=1 and (display_page='Search' OR display_page='Both') and (module='B2C' OR module='Both') and api_module = 'hotels' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
			$safe_search_data['data']['right_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Right' and status=1 and (display_page='Search' OR display_page='Both') and (module='B2C' OR module='Both') and api_module = 'hotels' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
			$safe_search_data['data']['middle_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Middle' and status=1 and (display_page='Search' OR display_page='Both') and (module='B2C' OR module='Both') and api_module = 'hotels' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
			$this->template->view('hotel/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
		} else {
			$this->template->view('general/popup_redirect');
		}
	}
	/**
	 *  Elavarasi
	 * Load hotel details based on booking source
	 */
	function hotel_details($search_id)
	{
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		ini_set('memory_limit', -1);
		$params = $this->input->get();
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);

		$safe_search_data['data']['search_id'] = abs($search_id);
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

		if (isset($params['booking_source']) == true) {
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			load_hotel_lib($params['booking_source']);
			if (
				$params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true and isset($params['op']) == true and
				$params['op'] == 'get_details' and $safe_search_data['status'] == true
			) {
				$params['ResultIndex']	= urldecode($params['ResultIndex']);

				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex']);

				if ($raw_hotel_details['status']) {
					$HotelCode = $raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['HotelCode'];

					$image_mask = $this->hotel_model->add_hotel_images($search_id, $raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'], $HotelCode);
					if ($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']) {
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'], $currency_obj, $search_id);
					}
					$imagePaht = $this->updateImagePath($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images']);
					$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'] = $imagePaht;

					$this->template->view('hotel/tmx/tmx_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					$message = $raw_hotel_details['data']['Message'];
					redirect(base_url() . 'hotel/exception?op=' . $message . '&notification=session');
				}
			} else if (
				$params['booking_source'] == TBO_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true and isset($params['op']) == true and
				$params['op'] == 'get_details' and $safe_search_data['status'] == true
			) {

				$params['ResultIndex']	= urldecode($params['ResultIndex']);
				$search_hash	= urldecode($params['search_hash']);

				//debug($params['ResultIndex']); exit;

				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex'],$search_hash, $search_id);

				if ($raw_hotel_details['status']) {

					// if(isset($raw_hotel_details['data']['HotelInfoResult']['Rooms']['HotelRoomsDetails']))
					// {
					// 		$raw_hotel_details['data']['HotelInfoResult']['Rooms'] = $this->hotel_lib->roomlist_in_preferred_currency($raw_hotel_details['data']['HotelInfoResult']['Rooms'], $currency_obj, $params['search_id']);
					// 	}

					//debug($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']);

					if ($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']) {
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'], $currency_obj, $search_id);
					}

					//debug($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']); exit;

					$this->template->view('hotel/tmx/tmx_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					$message = $raw_hotel_details['data']['Message'];
					redirect(base_url() . 'hotel/exception?op=' . $message . '&notification=session');
				}
			}
			else
			 {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	function updateImagePath($imageUrls)
	{
		if(valid_array($imageUrls) == true)
		{
			if (strpos($imageUrls[0], 'hotelbeds') !== false) {
				$updatedUrls = [];
				foreach ($imageUrls as $url) {
					// Break the URL into parts
					$parts = parse_url($url);
					// Split the path into segments
					$pathSegments = explode('/', $parts['path']);
					// Insert 'original' after 'giata'
					$index = array_search('giata', $pathSegments);
					if ($index !== false) {
						array_splice($pathSegments, $index + 1, 0, 'original');
					}
					// Rebuild the path
					$newPath = implode('/', $pathSegments);
					// Reconstruct the full URL
					$newUrl = $parts['scheme'] . '://' . $parts['host'] . $newPath;
					$updatedUrls[] = $newUrl;
				}
				// Example output
				// print_r($updatedUrls);
				return $updatedUrls;
			}
			return $imageUrls;
		}
		else
		{
			return $imageUrls;
		}
		
	}
	/**
	 *  
	 * Passenger Details page for final bookings
	 * Here we need to run booking based on api
	 */
	function booking($search_id)
	{
		$pre_booking_params = $this->input->post();
		// debug($pre_booking_params);
		// exit;
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);

		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
		if (isset($pre_booking_params['booking_source']) == true) {
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data['search_data'] = $safe_search_data['data'];
			load_hotel_lib($pre_booking_params['booking_source']);
			//Need to fill pax details by default if user has already logged in
			$this->load->model('user_model');
			$page_data['pax_details'] = $this->user_model->get_current_user_details();
			$page_data['promo_codes'] = $this->custom_db->get_custom_query("select * from  promo_code_list where status=1 and module in ('hotel') AND expiry_date >= CURDATE()");
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");

			if (
				$pre_booking_params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE || $pre_booking_params['booking_source'] == TBO_HOTEL_BOOKING_SOURCE && isset($pre_booking_params['token']) == true and
				isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'block_room' and $safe_search_data['status'] == true
			) {

				$pre_booking_params['token'] = unserialized_data($pre_booking_params['token'], $pre_booking_params['token_key']);
				if ($pre_booking_params['token'] != false) {

					$room_block_details = $this->hotel_lib->block_room($pre_booking_params);

					//debug($room_block_details); exit;

					if ($room_block_details['status'] == false) {
						redirect(base_url() . 'hotel/exception?op=' . $room_block_details['data']['msg']);
					}
					//Converting API currency data to preferred currency
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

					$room_block_details = $this->hotel_lib->roomblock_data_in_preferred_currency($room_block_details, $currency_obj, $search_id);

					//debug($room_block_details);exit;

					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
					$cancel_currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params, $cancel_currency_obj);

					/*
					 * Update Markup
					 */

					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id']);
					$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
					if ($room_block_details['status'] == SUCCESS_STATUS) {
						if (!empty($this->entity_country_code)) {
							// $mobile_code = $this->db_cache_api->get_mobile_code($this->entity_country_code);
							// $page_data['user_country_code'] = $mobile_code;
							$page_data['user_country_code'] = $this->entity_country_code;
						} else {
							$page_data['user_country_code'] = $Domain_record['data'][0]['phone_code'];
						}
						//debug($page_data);exit;
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						// debug($pre_booking_params['markup_price_summary']);
						// exit;
						$page_data['total_price']		= $this->hotel_lib->total_price($pre_booking_params['markup_price_summary']);
						$page_data['convenience_fees']  = $currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']);
						$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						//Traveller Details
						$page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
						//Get the country phone code 
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
						$page_data['active_data'] = $Domain_record['data'][0];
						$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
						$page_data['phone_code'] = $temp_record['data'];
						//debug($page_data);exit;
						$this->template->view('hotel/tmx/tmx_booking_page', $page_data);
					}
				} else {
					redirect(base_url() . 'hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
				}
			} 
			else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 *  
	 * Secure Booking of hotel
	 * 255 single adult static booking request 2310
	 * 261 double room static booking request 2308
	 */
	function pre_booking($search_id)
	{
		// error_reporting(0);
		$post_params = $this->input->post();
		// debug($post_params); 
		// exit;
		//Setting Static Data - 
		$post_params['billing_city'] = 'Bangalore';
		$post_params['billing_state'] = 'Karnataka';
		$post_params['billing_zipcode'] = '560100';
		$post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
		//Make sure token and temp token matches
		$valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
		// debug($valid_temp_token);
		//exit;
		if ($valid_temp_token != false) {
			load_hotel_lib($post_params['booking_source']);
			/****Convert Display currency to Application default currency***/
			//After converting to default currency, storing in temp_booking
			$post_params['token'] = unserialized_data($post_params['token']);
			// debug($post_params);
			$currency_obj = new Currency(array(
				'module_type' => 'hotel',
				'from' => admin_base_currency(),
				'to' => admin_base_currency()
			));
			//debug($currency_obj); exit;
			$post_params['token'] = $this->hotel_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
			// debug($post_params);exit;
			$post_params['token'] = serialized_data($post_params['token']);
			$temp_token = unserialized_data($post_params['token']);
			// debug($temp_token);exit;
			// debug($post_params);exit;
			//Insert To temp_booking and proceed
			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOTEL_BOOKING);
			$book_id = $temp_booking['book_id'];
			$book_origin = $temp_booking['temp_booking_origin'];
			$source_array = array(PROVAB_HOTEL_BOOKING_SOURCE, TBO_HOTEL_BOOKING_SOURCE);
		
			//debug($temp_token['markup_price_summary']); exit();
			if (in_array($post_params['booking_source'], $source_array)) {
				$amount	  = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
				$currency = $temp_token['default_currency'];
			}
			$total_fare_tax = $temp_token['markup_price_summary']['Tax'];
			//debug($temp_token);exit;
			/********* Convinence Fees Start ********/
			$convenience_fees = round($currency_obj->convenience_fees($amount, $search_id), 2);
			/********* Convinence Fees End ********/

			$currency_obj = new Currency(array(
				'module_type' => 'hotel',
				'from' => admin_base_currency(),
				'to' => admin_base_currency()
			));

			/********* Promocode Start ********/
			if (isset($post_params['promo_code_discount_val'])) {
				$key = provab_encrypt($post_params['key']);
				$data = $this->custom_db->single_table_records('promo_code_doscount_applied', '*', array('search_key' => $key));
				if ($data['status'] == true) {
					//$promocode_discount = $data['data'][0]['discount_value'];
					$promocode_discount = $post_params['promo_code_discount_val'];
				}
			}
			$currency_obj_promo = new Currency(array(
				'module_type' => 'hotel',
				'from' => get_application_currency_preference(),
				'to' => get_application_default_currency()
			));
			$conversion_rate = $currency_obj_promo->conversion_cache[get_application_currency_preference() . get_application_default_currency()];
			$promocode_discount = str_replace(',', '', $promocode_discount) * $conversion_rate;
			$promocode_discount = round($promocode_discount, 2);
			/********* Promocode End ********/
			//details for PGI
			$email = $post_params['billing_email'];
			$phone = $post_params['passenger_contact'];
			// debug($post_params);
			$booking_data = $this->module_model->unserialize_temp_booking_record($book_id, $book_origin);
			//debug($booking_data); exit;

			$booking_data['book_attributes']['total_fare_markup'] = $post_params['total_amount_val'];
			$booking_data['book_attributes']['total_fare_tax'] = $post_params['convenience_fee'] + $post_params['total_fare_tax'];
			$booking_data['book_attributes']['total_destination_charge'] = $post_params['total_destination_charge'];

			$book_params = $booking_data['book_attributes'];
			// debug($book_params); exit;
			$data = $this->hotel_lib->save_booking($book_id, $book_params, $currency_obj, $this->current_module);
			//debug($data); //exit;
			$amount = ($amount + $convenience_fees + $total_fare_tax) - $promocode_discount;
			//debug($amount);exit;
			$verification_amount = round($amount, 2);
			$firstname = $post_params['first_name']['0'];
			$productinfo = META_ACCOMODATION_COURSE;
			//check current balance before proceeding further
			$domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);
			if ($domain_balance_status == true) {
				switch ($post_params['payment_method']) {
					case PAY_NOW:
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
						$this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);

						// redirect(base_url().'hotel/process_booking/'.$book_id.'/'.$book_origin);

						// redirect(base_url().'hotel/review/'.$book_id.'/'.$book_origin.'/'.$search_id);	

						redirect(base_url() . 'payment_gateway/payment/' . $book_id . '/' . $book_origin);

						break;
					case PAY_AT_BANK:
						echo 'Under Construction - Remote IO Error';
						exit;
						break;
				}
			} else {
				redirect(base_url() . 'hotel/exception?op=Amount Hotel Booking&notification=insufficient_balance');
			}
		} else {
			redirect(base_url() . 'hotel/exception?op=Remote IO error @ Hotel Booking&notification=validation');
		}
	}
	/*
		process booking in backend until show loader 
	*/
	function process_booking($book_id, $temp_book_origin)
	{

		if ($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0) {
			$page_data['form_url'] = base_url() . 'hotel/secure_booking';
			$page_data['form_method'] = 'POST';
			$page_data['form_params']['book_id'] = $book_id;
			$page_data['form_params']['temp_book_origin'] = $temp_book_origin;
			$this->template->view('share/loader/booking_process_loader', $page_data);
		} else {
			redirect(base_url() . 'hotel/exception?op=Invalid request&notification=validation');
		}
	}
	/**
	 *  
	 *Do booking once payment is successfull - Payment Gateway
	 *and issue voucher
	 *HB11-152109-443266/1
	 *HB11-154107-854480/2
	 */
	function secure_booking()
	{

		//echo 'Activating Live crdetails booking will not do';exit;
		$post_data = $this->input->post();
		//debug($post_data); exit;

		if (
			valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0
		) {
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
			$this->load->model('transaction');
			$booking_status = $this->transaction->get_payment_status($book_id);
			if ($booking_status['status'] !== 'accepted') {
				redirect(base_url() . 'hotel/exception?op=Payment Not Done&notification=validation');
			}
		} else {
			redirect(base_url() . 'hotel/exception?op=InvalidBooking&notification=invalid');
		}

		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
		//debug($temp_booking);exit;
		//Delete the temp_booking record, after accessing
		// $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
		load_hotel_lib($temp_booking['booking_source']);
		//verify payment status and continue
		$total_booking_price = $this->hotel_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);
		$currency = $temp_booking['book_attributes']['token']['default_currency'];
		//also verify provab balance
		//check current balance before proceeding further
		$domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);

		//debug($domain_balance_status); exit;

		if ($domain_balance_status) {
			//lock table
			if ($temp_booking != false) {
				switch ($temp_booking['booking_source']) {
					case PROVAB_HOTEL_BOOKING_SOURCE:

						//FIXME : COntinue from here - Booking request
						$booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
						//debug($booking);exit;
						//Save booking based on booking status and book id
						break;
					case TBO_HOTEL_BOOKING_SOURCE:
					
						$booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
						break;
					default:
						exit('booking source not found !');
						break;
				}
				//debug($booking);exit;
				if ($booking['status'] == SUCCESS_STATUS) {
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
					$promo_currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));
					$booking['data']['currency_obj'] = $currency_obj;
					$booking['data']['promo_currency_obj'] = $promo_currency_obj;
			
					//Update the booking Details

					$ticket_details = @$booking['data']['ticket'];
					$ticket_details['master_booking_status'] = $booking['status'];
					
					$data = $this->hotel_lib->update_booking_details($book_id, $booking['data'], $ticket_details, $currency_obj, $this->current_module);
					//debug($data);exit;
					$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'], $data['transaction_currency'], $data['currency_conversion_rate']);
					$tokenResult = $this->custom_db->single_table_records('promocode_token', 'token', array('user_id' => $this->entity_user_id, 'status' => ACTIVE));
					if ($tokenResult['status']) {
						$token = $tokenResult['data'][0]['token'];
						$promotrack = $this->custom_db->single_table_records('new_user_promocode_track', 'promo_code_remaining_value', array('token' => $token, 'status' => INACTIVE));

						$UpdatePromoCodeData['promo_code_remaining_value'] = ($promotrack['status']) ? $promotrack['data'][0]['promo_code_remaining_value'] : 0;
						$this->custom_db->update_record('user', $UpdatePromoCodeData, array('user_id' => $this->entity_user_id));
						$this->custom_db->update_record('new_user_promocode_track', array('status' => ACTIVE), array('token' => $token));
						$this->custom_db->delete_record('promocode_token', array('user_id' => $this->entity_user_id));
					}

					redirect(base_url() . 'voucher/hotel/' . $book_id . '/' . $temp_booking['booking_source'] . '/' . $data['booking_status'] . '/show_voucher');
				} else {
					redirect(base_url() . 'hotel/exception?op=booking_exception&notification=' . $booking['data']['message']);
				}
			}
			//release table lock
		} else {
			redirect(base_url() . 'hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
		}
		//redirect(base_url().'hotel/exception?op=Remote IO error @ Hotel Secure Booking&notification=validation');
	}
	function test()
	{
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
		debug($currency_obj);
	}
	/**
	 *  
	 *Process booking on hold - pay at bank
	 */
	function booking_on_hold($book_id) {}
	/*Anitha.G
		Review passenger page for hotel
	*/
	public function review($app_reference, $temp_book_origin, $search_id)
	{

		$temp_booking = $this->module_model->unserialize_temp_booking_record($app_reference, $temp_book_origin);
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		// debug($temp_booking);exit;
		/* Booking Hotel Data*/
		$page_data['hotel_data']['HotelName'] = $temp_booking['book_attributes']['token']['HotelName'];
		$page_data['hotel_data']['HotelAddress'] = $temp_booking['book_attributes']['token']['HotelAddress'];
		$page_data['hotel_data']['HotelName'] = $temp_booking['book_attributes']['token']['HotelName'];
		$page_data['hotel_data']['RoomTypeName'] = $temp_booking['book_attributes']['token']['RoomTypeName'];
		$page_data['hotel_data']['adult_config'] = array_sum($safe_search_data['data']['adult_config']);
		$page_data['hotel_data']['child_config'] = array_sum($safe_search_data['data']['child_config']);
		// if($page_data['hotel_data']['child_config'] > 0){
		// 	$page_data['hotel_data']['child_age'] = $safe_search_data['data']['child_config'];
		// }
		$page_data['hotel_data']['room_count'] = $safe_search_data['data']['room_count'];
		$page_data['hotel_data']['checkin_date'] = $safe_search_data['data']['from_date'];
		$page_data['hotel_data']['checkout_date'] = $safe_search_data['data']['to_date'];
		/*Guest Details*/
		$page_data['guest_data']['first_name'] = $temp_booking['book_attributes']['first_name'];
		$page_data['guest_data']['last_name'] = $temp_booking['book_attributes']['last_name'];
		$page_data['guest_data']['last_name'] = $temp_booking['book_attributes']['last_name'];
		/*Price Details */
		$page_data['price_details']['total_amount_val'] = $total_amount_val = $temp_booking['book_attributes']['total_amount_val'];
		$page_data['price_details']['convenience_amount'] = $convenience_amount = $temp_booking['book_attributes']['convenience_amount'];
		$page_data['price_details']['discount'] = $discount = $temp_booking['book_attributes']['promo_code_discount_val'];
		$page_data['price_details']['grand_total'] = $total_amount_val + $convenience_amount - $discount;
		/*Lead Customer Details */
		$page_data['lead_pax']['email'] = $temp_booking['book_attributes']['billing_email'];
		$page_data['lead_pax']['contact'] = $temp_booking['book_attributes']['passenger_contact'];
		$page_data['lead_pax']['phone_country_code'] = $temp_booking['book_attributes']['phone_country_code'];
		$page_data['currency_symbol'] = $temp_booking['book_attributes']['currency_symbol'];
		$page_data['book_id'] = $app_reference;
		$page_data['book_origin'] = $temp_book_origin;
		//$page_data['phone_country_code'] = $phone_country_code;
		//debug($page_data);exit;

		/*$page_data['book_id']=$temp_booking['book_id'];
        $page_data['booking_source']=$temp_booking['booking_source'];
        $page_data['HotelAddress']=$temp_booking['book_attributes']['token']['HotelAddress'];
        $page_data['HotelName']=$temp_booking['book_attributes']['token']['HotelName'];
        $page_data['gstvalue']=$temp_booking['book_attributes']['gstvalue'];
        $page_data['gender']=$temp_booking['book_attributes']['gender'];
        $page_data['promo_code']=$temp_booking['book_attributes']['promo_code'];
        $page_data['promo_code_discount_val']=$temp_booking['book_attributes']['promo_code_discount_val'];
        $page_data['first_name']=$temp_booking['book_attributes']['first_name'];
        $page_data['middle_name']=$temp_booking['book_attributes']['middle_name'];
        $page_data['last_name']=$temp_booking['book_attributes']['last_name'];
        $page_data['billing_email']=$temp_booking['book_attributes']['billing_email'];
        $page_data['country_code']=$temp_booking['book_attributes']['country_code'];
        $page_data['currency_symbol']=$temp_booking['book_attributes']['currency_symbol'];
        $page_data['passenger_contact']=$temp_booking['book_attributes']['passenger_contact'];
        $page_data['total_amount_val']=$temp_booking['book_attributes']['total_amount_val'];
        $page_data['FromDate']=$safe_search_data['data']['from_date'];
        $page_data['ToDate']=$safe_search_data['data']['to_date'];
        $page_data['room_count']=$safe_search_data['data']['room_count'];
        $page_data['adult_config']=$safe_search_data['data']['adult_config'];
        $page_data['child_config']=$safe_search_data['data']['child_config'];
       
        
        $page_data['total_amount_val']=$temp_booking['book_attributes']['total_amount_val'];
        $page_data['currency']=$temp_booking['currency']['currency'];
        $this->load->model('transaction');
        $duplicate_pg = $this->transaction->read_payment_record($app_reference);
        $req_params = json_decode($duplicate_pg["request_params"], true);
        $page_data['tax'] = 0;
        $page_data['discount'] = 0;
        if ($req_params['convenience_amount']) {
        	$page_data['tax'] = $req_params['convenience_amount'];
        }
        if ($req_params['promocode_discount']) {
        	$page_data['discount'] = $req_params['promocode_discount'];
        }
        // debug($page_data);exit;*/
		$this->template->view('hotel/review_page', $page_data);
	}
	/**
	 * 
	 */
	function pre_cancellation($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
				$page_data['data'] = $assembled_booking_details['data'];
				$this->template->view('hotel/pre_cancellation', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/*
	 * 
	 * Process the Booking Cancellation
	 * Full Booking Cancellation
	 *
	 */
	function cancel_booking($app_reference, $booking_source)
	{
		if (empty($app_reference) == false) {
			$master_booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);
			//debug($master_booking_details); exit;
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
				load_hotel_lib($booking_source);
				switch ($booking_source) {
					case PROVAB_HOTEL_BOOKING_SOURCE:
						$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);
						break;
					case HOTELBED_BOOKING_SOURCE:
						$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);
						break;
					case HYPER_GUEST_HOTEL_BOOKING_SOURCE:
						$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);
						break;
					case Go_Global_BOOKING_SOURCE:
						$master_booking_details = $master_booking_details['data']['booking_details'][0];
						$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);
						break;
					default:
						exit('booking source not found !');
						break;
				}
				//Invoke Cancellation Methods
				if ($cancellation_details['status'] == false) {
					$query_string = '?error_msg=' . $cancellation_details['msg'];
				} else {
					$query_string = '';
				}
				redirect('hotel/cancellation_details/' . $app_reference . '/' . $booking_source . $query_string);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/**
	 * 
	 * Cancellation Details
	 * @param $app_reference
	 * @param $booking_source
	 */
	function cancellation_details($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$master_booking_details = $GLOBALS['CI']->hotel_model->get_booking_details($app_reference, $booking_source);
			//debug($master_booking_details); exit;
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$page_data = array();
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
				//	debug($master_booking_details); exit;
				$page_data['data'] = $master_booking_details['data'];
				//debug($page_data['data']); exit;
				$this->template->view('hotel/cancellation_details', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	function map()
	{
		$details = $this->input->get();
		$geo_codes['data']['latitude'] = $details['lat'];
		$geo_codes['data']['longtitude'] = $details['lon'];
		$geo_codes['data']['hotel_name'] = urldecode($details['hn']);
		$geo_codes['data']['star_rating'] = $details['sr'];
		$geo_codes['data']['city'] = urldecode($details['c']);
		$geo_codes['data']['hotel_image'] = urldecode($details['img']);
		$geo_codes['data']['price'] = $details['price'];
		echo $this->template->isolated_view('hotel/location_map', $geo_codes);
	}
	/**
	 * 
	 */
	function exception()
	{
		$module = META_ACCOMODATION_COURSE;
		$op = (empty($_GET['op']) == true ? '' : $_GET['op']);
		$notification = (empty($_GET['notification']) == true ? '' : $_GET['notification']);

		if ($op == 'Some Problem Occured. Please Search Again to continue') {
			$op = 'Some Problem Occured. ';
		}
		if ($notification == 'Invalid CommitBooking Request') {
			$message = 'Session is Expired';
		} else if ($notification == 'Some Problem Occured. Please Search Again to continue') {
			$message = 'Some Problem Occured';
		} else {
			$message = $notification;
		}
		$exception = $this->module_model->flight_log_exception($module, $op, $message);
		$exception = base64_encode(json_encode($exception));
		// debug($exception);exit;
		//set ip log session before redirection
		$this->session->set_flashdata(array('log_ip_info' => true));
		$is_session = false;

		if ($notification == 'session') {
			$is_session = true;
		}

		redirect(base_url() . 'hotel/event_logger/' . $exception . '/' . $is_session . '/' . $op);
	}
	function event_logger($exception = '', $is_session = '', $op = '')
	{

		$log_ip_info = $this->session->flashdata('log_ip_info');
		if (strtolower(urldecode($op)) == 'not available') {
			$op = '';
		}
		$this->template->view('hotel/exception', array('log_ip_info' => $log_ip_info, 'exception' => $exception, 'is_session' => $is_session, 'message' => $op));
	}
	function get_hotel_images()
	{
		$post_params['hotel_code'] = 'H!0634455';
		if ($post_params['hotel_code']) {
			switch (PROVAB_HOTEL_BOOKING_SOURCE) {
				case PROVAB_HOTEL_BOOKING_SOURCE:
					load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
					$raw_hotel_images = $this->hotel_lib->get_hotel_images($post_params['hotel_code']);
					debug($raw_hotel_images);
					exit;
					break;
			}
		}
		exit;
	}
	function image_cdn($index, $search_id, $HotelCode)
	{
		$HotelCode = base64_decode($HotelCode);
		$image_url = $this->custom_db->single_table_records('hotel_image_url', 'image_url', array('search_id' => $search_id, 'ResultIndex' => $index, 'hotel_code' => $HotelCode));
		//debug($image_url);exit;
		$image_url = $image_url['data'][0]['image_url'];

		header("Content-type: image/gif");
		echo  file_get_contents($image_url);
	}
	function image_details_cdn($HotelCode, $images_index)
	{
		$HotelCode = base64_decode($HotelCode);
		$image_url = $this->custom_db->single_table_records('hotel_image_url', 'image_url', array('hotel_code' => $HotelCode, 'ResultIndex' => $images_index));
		$image_url = $image_url['data'][0]['image_url'];
		header("Content-type: image/gif");
		echo  file_get_contents($image_url);
	}
	//Agoda BookingList
	function get_agoda_hotel_bookings()
	{
		load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
		$this->hotel_lib->get_agoda_bookings_list();
	}
	function dynamic_search($city_name)
	{
		// echo $city_name;exit;
		$city_name = urldecode($city_name);
		$hotel_data = $this->hotel_model->get_dynamic_hotel_url_by_city($city_name);
		if (empty($hotel_data)) {
			$conditions['city_name'] = $city_name;
			$columns = 'country_name, origin';
			$details = $this->custom_db->single_table_records('all_api_city_master', $columns, $conditions);
			$hotel_data = [];
			if ($details['status'] == ACTIVE) {
				$hotel_data['city'] = $city_name;
				$hotel_data['country'] = $details['data'][0]['country_name'];
				$hotel_data['hotel_destination'] = $details['data'][0]['origin'];
			}
		}
		if (!empty($hotel_data)) {
			$checkin = date('d-m-Y', strtotime('+1 day'));
			$checkout = date('d-m-Y', strtotime('+2 day'));
			// Create the search data array
			$search_data = array(
				'city' => $hotel_data['city'] . ' (' . $hotel_data['country'] . ')', // City and country combined
				'hotel_destination' => $hotel_data['hotel_destination'],
				'hotel_checkin' => $checkin, // Assuming $checkin is already set
				'hotel_checkout' => $checkout, // Assuming $checkout is already set
				'rooms' => 1, // Default room value
				'adult' => array(2), // Example adults array
				'child' => array(0), // Example children array
				'childAge_1' => array(1, 1), // Example child ages
				'location' => '',
				'radius' => 1,
				'latitude' => '',
				'longitude' => '',
				'countrycode' => '',
				'search_type' => 'city_search' // Default search type
			);

			// Convert search data to JSON format
			$search_data_json = json_encode($search_data);

			// Insert into search_history table
			$insert_id = $this->custom_db->insert_record('search_history', array(
				'search_type' => META_ACCOMODATION_COURSE, // Search type
				'search_data' => $search_data_json, // Store JSON formatted search data
				'created_datetime' => date('Y-m-d H:i:s') // Current timestamp
			));

			// Static hotel ID
			// $hotel_id = $insert_id['insert_id']; // Replace this with your desired hotel ID
			// Construct the search URL with dynamic values
			// $search_url = '/hotel/search/'.$hotel_id.'?city='.$hotel_data['city'].'+%28'.$hotel_data['country'].'%29&hotel_destination='.$hotel_data['hotel_destination'].'&hotel_checkin='.$checkin.'&hotel_checkout='.$checkout.'&rooms=1&adult%5B%5D=2&child%5B%5D=0&childAge_1%5B%5D=1&childAge_1%5B%5D=1&location=&radius=1&latitude=&longitude=&countrycode=&search_type=city_search';
			// debug($search_url);exit;
			// Redirect to the constructed search URL
			// redirect($search_url);
			$this->search($insert_id['insert_id']);
		} else {
			$this->template->view('utilities/404.php');
		}
	}
}

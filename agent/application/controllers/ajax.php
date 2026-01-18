<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-09-16
* @package: 
* @Description: 
* @version: 2.0
**/

// require_once FCPATH . '../b2c/third_party/vendor/autoload.php';
// use Xendit\Xendit;

class Ajax extends MY_Controller{
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		if (is_ajax() == false) {
			//$this->index();
		}
		ob_start();
		$this->load->model('flight_model');
		$this->load->model('car_model');  
		$this->load->model('sightseeing_model');
		$this->load->model('transferv1_model');
		$this->load->model('transfer_model');
		$this->current_module = $this->config->item('current_module');
		error_reporting(0);

	}
	/**
	 * index page of application will be loaded here
	 */
	function index()
	{

	}

	/**
	 * get city list based on country
	 * @param $country_id
	 * @param $default_select
	 */
	function get_city_list($country_id=0, $default_select=0)
	{
		if (intval($country_id) != 0) {
			$condition = array('country' => $country_id);
			$order_by = array('destination' => 'asc');
			$option_list = $this->custom_db->single_table_records('api_city_list', 'origin as k, destination as v', $condition, 0, 1000000, $order_by);
			if (valid_array($option_list['data'])) {
				echo get_compressed_output(generate_options($option_list['data'], array($default_select)));
				exit;
			}
		}
	}

	/**
	 *
	 * @param $continent_id
	 * @param $default_select
	 * @param $zone_id
	 */
	function get_country_list($continent_id=array(), $default_select=0,$zone_id=0)
	{
		$this->load->model('general_model');
		$continent_id=urldecode($continent_id);
		if (intval($continent_id) != 0) {
			$option_list = $this->general_model->get_country_list($continent_id,$zone_id);
			if (valid_array($option_list['data'])) {
				echo get_compressed_output(generate_options($option_list['data'], array($default_select)));
			}
		}
	}

	/**
	 *Get Location List
	 */
	function location_list($limit=AUTO_SUGGESTION_LIMIT)
	{
		$chars = $_GET['term'];
		$list = $this->general_model->get_location_list($chars, $limit);
		$temp_list = '';
		if (valid_array($list) == true) {
			foreach ($list as $k => $v) {
				$temp_list[] = array('id' => $k, 'label' => $v['name'], 'value' => $v['origin']);
			}
		}
		$this->output_compressed_data($temp_list);
	}

	/**
	 *Get Location List
	 */
	function city_list($limit=AUTO_SUGGESTION_LIMIT)
	{
		$chars = $_GET['term'];
		$list = $this->general_model->get_city_list($chars, $limit);
		$temp_list = '';
		if (valid_array($list) == true) {
			foreach ($list as $k => $v) {
				$temp_list[] = array('id' => $k, 'label' => $v['name'], 'value' => $v['origin']);
			}
		}
		$this->output_compressed_data($temp_list);
	}

	/**
	 * Balu A
	 * @param unknown_type $currency_origin origin of currency - default to USD
	 */
	function get_currency_value($currency_origin=0)
	{
		$data = $this->custom_db->single_table_records('currency_converter', 'value', array('id' => intval($currency_origin)));
		if (valid_array($data['data'])) {
			$response = $data['data'][0]['value'];
		} else {
			$response = 1;
		}
		header('Content-type:application/json');
		echo json_encode(array('value' => $response));
		exit;
	}

	function base64DecodeUnicode($base64) 
	{
	    // Decode from Base64 to binary
	    $binary = base64_decode($base64);
	    // Convert binary to UTF-8 string
	    return mb_convert_encoding($binary, 'UTF-8', 'UTF-8');
	}


	public function send_multi_details_mail(){
		error_reporting(0);
        $params=$this->input->post();   
        $email=$params['email'];
        if (empty($params['module']) == false) 
        {
        	$module = $params['module'];

        	$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
			// debug($domain_address);exit;
			$page_data['data']['address'] =$domain_address['data'][0]['address'];
			$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
			$page_data['data']['phone_code'] =$domain_address['data'][0]['phone_code'];
			$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
			$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];

        	switch($module)
        	{
        		case 'flight':
	        	$flight_details_str = base64_decode($params['flightdetails']);
	        	$flight_details = json_decode($flight_details_str,true);
	            $page_data['flight_details']=$flight_details;

	            //debug($flight_details); exit;
           
	             $origin  = $flight_details['SegmentSummary'][0]['OriginDetails']['CityName'];
	             $destination  = $flight_details['SegmentSummary'][0]['DestinationDetails']['CityName'];
        
				$mail_template= $this->template->isolated_view('flight/flight_details_template', $page_data);

				//debug($mail_template); exit();


				$subject = "Quotation - Flight from $origin to $destination";
				
				break;

				case 'hotel':

					$hotel_details_str = $this->base64DecodeUnicode($params['hoteldetails']);
		        	$hotel_details = json_decode($hotel_details_str,true);
		            $page_data['hotel_details']=$hotel_details;

		            //debug($hotel_details); exit;

		            $mail_template= $this->template->isolated_view('hotel/hotel_details_template', $page_data);
		            $hotel_name = $hotel_details['HotelName'];
		            $location = $hotel_details['HotelLocation'];

					$subject = 'Quotation - '.$hotel_name.','.$location;
					break;

				case 'transfer':

					$transfer_details_str = base64_decode($params['transferdetails']);
		        	$transfer_details = json_decode($transfer_details_str,true);
		            $page_data['transfer_details']=$transfer_details;

		            //debug($transfer_details); exit;

		            $mail_template= $this->template->isolated_view('transferv1/transfer_details_template', $page_data);
		            $pickup_location = "";
		            $dropoff_location = "";

					$subject = 'Quotation - Transfer Service: '.$pickup_location.' to '.$dropoff_location;
					break;

				case 'sightseeing':

					$sightseeing_details = unserialized_data($params['sightseeingdetails']);

					$page_data['currency_obj'] = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

		            $page_data['sightseeing_details']=$sightseeing_details;

		            // debug($sightseeing_details); exit;

		            $mail_template= $this->template->isolated_view('sightseeing/sightseeing_details_template', $page_data);
		            $tour_name = $sightseeing_details['ProductName'];
		            $location = $sightseeing_details['DestinationName'];

					$subject = 'Quotation - '.$tour_name.' in '.$location;
					break;

				case 'car':

					$car_details = unserialized_data($params['cardetails']);

					$page_data['currency_obj'] = new Currency(array('module_type' => 'car', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

		            $page_data['car_details']=$car_details;

		           // debug($car_details); exit;

		            $mail_template= $this->template->isolated_view('car/car_details_template', $page_data);

		            if(isset($car_details['PickULocation']))
		            {
		            	$pickup_location = $car_details['PickULocation'];
		            }
		            else
		            {
		            	$pickup_location = $car_details['DropOffLocation'];
		            }
		            
		            $dropoff_location = $car_details['DropOffLocation'];

					$subject = 'Quotation - Car Service: '.$pickup_location.' to '.$dropoff_location;
					break;

				case 'package':

					$package_details_str = base64_decode($params['packagedetails']);
		        	$package_details = json_decode($package_details_str,true);
		            $page_data['package_details'] = $package_details;

		            $package_id = $package_details['package_id'];

			        $this->load->model('Package_Model');
			        $page_data['package_itinerary'] = $this->Package_Model->getPackageItinerary($package_id);
			        $page_data['package_price_policy'] = $this->Package_Model->getPackagePricePolicy($package_id);
			        $page_data['package_cancel_policy'] = $this->Package_Model->getPackageCancelPolicy($package_id);

			        $page_data['currency_obj'] = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

		            $mail_template= $this->template->isolated_view('holiday/holiday_details_template', $page_data);
		            $tour_name = $package_details['package_name'];
		            $location = $package_details['package_location'];

					$subject = 'Quotation - '.$tour_name.' in '.$location;
					break;

				default:

				return FALSE;
				break;
			}

					$mail_contents = <<<EOD
									Dear Valued Client,<br><br>
									Please find attached the quotation details for your reference.<br><br>
									Kindly note that the information provided, including rates and itinerary or service details, is for reference purposes only. No bookings or reservations have been made or confirmed at this time.<br><br>
									Rates are subject to change without prior notice and are not guaranteed until the booking is finalized.<br><br>
									If you wish to proceed with the booking or have any questions, please feel free to contact me directly. I will be happy to assist you further.<br><br>
									Thank you for trusting us with your travel needs.
									EOD;

					//debug($subject); exit();

					$this->load->library('provab_pdf');
					$create_pdf = new Provab_Pdf();
					$pdf = $create_pdf->create_pdf($mail_template,'');

		            $this->load->library('provab_mailer');

		            //$subject = "Fligth Quotation";

		            $mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_contents,$pdf);
		             //debug($mail_status);exit;
		            echo TRUE;
            
        }
        else
        {
            echo FALSE;
        }
    }

	/*
	 *
	 * Flight(Airport) auto suggest
	 *
	 */
	function get_airport_code_list()
	{
		$term = $this->input->get('term'); //retrieve the search term that autocomplete sends
		$term = trim(strip_tags($term));
		$result = array();
		$flagpath = DOMAIN_LAG_IMAGE_DIR.'/'; 
		
		$__airports = $this->flight_model->get_airport_list($term)->result();
		if (valid_array($__airports) == false) {
			$__airports = $this->flight_model->get_airport_list('')->result();
		}
		$airports = array();
        foreach ($__airports as $airport) {
            // debug($airport);exit;
            // $county_code = $this->flight_model->get_county_code($airport->country);
            $airports['airport'] = $airport->airport_name;
            $airports['label'] = $airport->airport_city . ', ' . $airport->country . ' (' . $airport->airport_code . ')';
            $airports['value'] = $airport->airport_city . ' (' . $airport->airport_code . ')';
            $airports['id'] = $airport->origin;
            $airports['country_code'] = $flagpath . strtolower($airport->CountryCode) . '.png';
            // if (empty($airport->top_destination) == false) {
            //     $airports['category'] = 'Top cities';
            //     $airports['type'] = 'Top cities';	
            // } else {
            //     $airports['category'] = 'Search Results';
            //     $airports['type'] = 'Search Results';
            // }

			$airports['category'] = '';
                $airports['type'] = '';

            if ($airport->lat != '' && $airport->lon != '') {
                $__near_airports = $this->flight_model->get_nearBy_airport_list($airport->airport_code, $airport->lat, $airport->lon, 65)->result();

                if (!empty($__near_airports)) {
                    foreach ($__near_airports as $near_airports) {
						if ($near_airports->lat != '' && $airport->lon != '') {
                        $near__airports['airport'] = $near_airports->airport_name;
                        $near__airports['label'] = $near_airports->airport_city . ', ' . $near_airports->country . ' (' . $near_airports->airport_code . ')';
                        $near__airports['value'] = $near_airports->airport_city . ' (' . $near_airports->airport_code . ')';
                        $near__airports['id'] = $near_airports->origin;
                        $near__airports['country_code'] = $flagpath . strtolower($near_airports->CountryCode) . '.png';
                        $near__airports['distance'] = round($near_airports->distance);
                       
							$near__airports['category'] = '';
                            $near__airports['type'] = '';
						}

                        // array_push($result, $airports);
                        $airports['near_airports'][] = $near__airports;

                        //  array_push($result, $near__airports);
                    }
                } else {
                    $airports['near_airports'] = [];
                }

                // print_r($__new_airports); exit;
            } else {
                $airports['near_airports'] = [];
            }

            array_push($result, $airports);
            if (!empty($airports['near_airports'])) {
                foreach ($airports['near_airports'] as $near) {
                    array_push($result, $near);
                }
            }
        }
		$this->output_compressed_data($result);
	}
	  /*
     *
     * Car(Airport) auto suggest
     *
     */
    function get_airport_city_list(){
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $result = array();

        $__airports = $this->car_model->get_airport_list($term)->result();
        if (valid_array($__airports) == false) {
            $__airports = $this->car_model->get_airport_list('')->result();
        }
        // debug($__airports);exit;
        $airports = array();
        foreach ($__airports as $airport) {
            $airports['label'] = $airport->Airport_Name_EN.','.$airport->Country_Name_EN;
            // $airports['value'] = $airport->airport_city . ' (' . $airport->airport_code . ')';
            $airports['id'] = $airport->origin;
            $airports['airport_code'] = $airport->Airport_IATA;
            $airports['country_id'] = $airport->Country_ISO;
            $airports['category'] = 'Search Results';
            $airports['type'] = 'Search Results';
            array_push($result, $airports);
        }
              
        $city_list = $this->car_model->get_city_list($term)->result();
        if (valid_array($city_list) == false) {
            $city_list = $this->car_model->get_city_list('')->result();
        }
        foreach($city_list as $city){ //debug($city_list);exit;
            if($city->City_ID != ""){
                $city_result['label'] = $city->City_Name_EN.' City/Downtown,'.$city->Country_Name_EN;
                $city_result['id'] = $city->origin;
                $city_result['airport_code'] = $city->Airport_IATA;
                $city_result['country_id'] = $city->Country_ISO;
                if (empty($city->top_destination) == false) {
                    $city_result['category'] = 'Top cities';
                    $city_result['type'] = 'Top cities';
                } else {
                    $city_result['category'] = 'Search Results';
                    $city_result['type'] = 'Search Results';
                }
                array_push($result,$city_result);
            }
        }
        // debug($result);exit;   
        $this->output_compressed_data($result);
    }
	/*
	 *
	 * Hotels City auto suggest
	 *
	 */
	function get_hotel_city_list()
	{
		$this->load->model('hotel_model');
		$term = $this->input->get('term'); //retrieve the search term that autocomplete sends
		$term = trim(strip_tags($term));
		$data_list = $this->hotel_model->get_hotel_city_list($term);
		$hotel_list = $this->hotel_model->get_hotel_list($term);
		if (valid_array($data_list) == false) {
			$data_list = $this->hotel_model->get_hotel_city_list('');
		}
		$suggestion_list = array();
		$result = array();
		foreach($data_list as $city_list){
			$suggestion_list['label'] = $city_list['city_name'].', '.$city_list['country_name'].'';
			$suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
			$suggestion_list['id'] = $city_list['origin'];
			// added for tbo hotel API
            $suggestion_list['tbo_city_id'] = $city_list['tbo_city_id'];

			if (empty($city_list['top_destination']) == false) {
				$suggestion_list['category'] = 'Top cities';
				$suggestion_list['type'] = 'Top cities';
			} else {
				$suggestion_list['category'] = 'Search Results';
				$suggestion_list['type'] = 'Search Results';
			}
			if (intval($city_list['cache_hotels_count']) > 0) {
				$suggestion_list['count'] = $city_list['cache_hotels_count'];
			} else {
				$suggestion_list['count'] = 0;
			}
			$result[] = $suggestion_list;
		}

		if(!empty($hotel_list)){
			foreach ($hotel_list as $h_list) {
			   $suggestion_list['category'] = 'Search Results';
			   $suggestion_list['type'] = 'Search Results';
				$suggestion_list['id'] = $h_list['origin'];

				 // added for tbo hotel API
                $suggestion_list['tbo_city_id'] = $h_list['tbo_city_id'];

				$suggestion_list['label'] = $h_list['city_name'] . ', ' . $h_list['country_name'] .' '.$h_list['hotel_name'];
				// $suggestion_list['value'] = $h_list['hotel_name'];
				$suggestion_list['value'] = hotel_suggestion_value($h_list['city_name'], $h_list['country_name']).' '.$h_list['hotel_name'];
				$suggestion_list['count'] = 0;
				$result[] = $suggestion_list;
			}   
		}
		
		$this->output_compressed_data($result);
	}


	/**
	 * Auto Suggestion for bus stations
	 */
	function bus_stations()
	{
		$this->load->model('bus_model');
		$term = $this->input->get('term'); //retrieve the search term that autocomplete sends
		$term = trim(strip_tags($term));
		$data_list = $this->bus_model->get_bus_station_list($term);
		if (valid_array($data_list) == false) {
			$data_list = $this->bus_model->get_bus_station_list('');
		}
		$suggestion_list = array();
		$result = array();
		foreach($data_list as $city_list){
			$suggestion_list['label'] = $city_list['name'];
			$suggestion_list['value'] = $city_list['name'];
			$suggestion_list['id'] = $city_list['origin'];
			if (empty($city_list['top_destination']) == false) {
				$suggestion_list['category'] = 'Top cities';
				$suggestion_list['type'] = 'Top cities';
			} else {
				$suggestion_list['category'] = 'Search Results';
				$suggestion_list['type'] = 'Search Results';
			}
			$result[] = $suggestion_list;
		}
		$this->output_compressed_data($result);
	}
	/**
	 * Load hotels from different source
	 */
	function hotel_list($offset=0)
	{
		ini_set("memory_limit", "-1");
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();
		$limit = $this->config->item('hotel_per_page_limit');
		$this->load->model('hotel_model');
		if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
			load_hotel_lib($search_params['booking_source']);
			$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country','currency_class')), array('status' => ACTIVE));
			$currencyList = currencyDataFormat($app_supported_currency);//debug($currencyList);exit;
			switch($search_params['booking_source']) {
				case PROVAB_HOTEL_BOOKING_SOURCE :
					//Meaning hotels are loaded first time
					$safe_search_data = $this->hotel_model->get_safe_search_data($search_params['search_id']);

					$raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']),$search_params);
					if ($raw_hotel_list['status']) {
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
						$raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj,$search_params['search_id']);
						//Display 
						$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
						//Update currency and filter summary appended
						if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
							$filters = $search_params['filters'];
						} else {
							$filters = array();
						}

						$raw_hotel_list['data'] = $this->hotel_lib->format_search_response($raw_hotel_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
						
						$source_result_count = $raw_hotel_list['data']['source_result_count'];
						$filter_result_count = $raw_hotel_list['data']['filter_result_count'];
						if (intval($offset) == 0) {
							//Need filters only if the data is being loaded first time
							$filters = $this->hotel_lib->filter_summary($raw_hotel_list['data']);
							$response['filters'] = $filters['data'];
						}
						// $raw_hotel_list['data'] = $this->hotel_lib->get_page_data($raw_hotel_list['data'], $offset, $limit);

						$attr['search_id'] = abs($search_params['search_id']);
						//debug($raw_hotel_list);exit;

						$response['data'] = get_compressed_output(
						$this->template->isolated_view('hotel/search_result',
						array('currency_obj' => $currency_obj, 'raw_hotel_list' => $raw_hotel_list['data'],
									'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
									'attr' => $attr,
									'search_params' => $safe_search_data,
									'currencyList' => $currencyList
						)));

						$response['page_reload'] = $raw_hotel_list['page_reload'];
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
					}
					if (($raw_hotel_list['status'] == false && $raw_hotel_list['data']['refresh_flag'] == 1)) {
                        $response['status'] = FAILURE_STATUS;
                        $response['request_count'] = $raw_hotel_list['data']['refresh_flag'];
                    } else {
                        $response['status'] = SUCCESS_STATUS;
                        $response['request_count'] = $raw_hotel_list['data']['refresh_flag'];
                    }
					break;

				case TBO_HOTEL_BOOKING_SOURCE :
					//Meaning hotels are loaded first time
					$safe_search_data = $this->hotel_model->get_safe_search_data($search_params['search_id']);

					$raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']),$search_params);

					//debug($raw_hotel_list); exit;

					if ($raw_hotel_list['status']) {
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
						$raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj,$search_params['search_id']);
						//Display 
						$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
						//Update currency and filter summary appended
						if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
							$filters = $search_params['filters'];
						} else {
							$filters = array();
						}

						$raw_hotel_list['data'] = $this->hotel_lib->format_search_response($raw_hotel_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
						
						$source_result_count = $raw_hotel_list['data']['source_result_count'];
						$filter_result_count = $raw_hotel_list['data']['filter_result_count'];
						if (intval($offset) == 0) {
							//Need filters only if the data is being loaded first time
							$filters = $this->hotel_lib->filter_summary($raw_hotel_list['data']);
							$response['filters'] = $filters['data'];
						}
						// $raw_hotel_list['data'] = $this->hotel_lib->get_page_data($raw_hotel_list['data'], $offset, $limit);

						$attr['search_id'] = abs($search_params['search_id']);
						//debug($raw_hotel_list);exit;

						$response['data'] = get_compressed_output(
						$this->template->isolated_view('hotel/search_result',
						array('currency_obj' => $currency_obj, 'raw_hotel_list' => $raw_hotel_list['data'],
									'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
									'attr' => $attr,
									'search_params' => $safe_search_data,
									'currencyList' => $currencyList
						)));

						$response['page_reload'] = $raw_hotel_list['page_reload'];
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
					}
					if (($raw_hotel_list['status'] == false && $raw_hotel_list['data']['refresh_flag'] == 1)) {
                        $response['status'] = FAILURE_STATUS;
                        $response['request_count'] = $raw_hotel_list['data']['refresh_flag'];
                    } else {
                        $response['status'] = SUCCESS_STATUS;
                        $response['request_count'] = $raw_hotel_list['data']['refresh_flag'];
                    }
					break;
			}
		}
		$this->output_compressed_data($response);
	}
	   /** Anitha G
     * Load car from different source
    */
    
    
    /**
     * Load hotels from different source
     */
    function car_list($offset = 0) {
    	
        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
      
        $limit = $this->config->item('car_per_page_limit');
          // debug($search_params);exit;
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
          	load_car_lib($search_params['booking_source']);
			$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country','currency_class')), array('status' => ACTIVE));
			$currencyList = currencyDataFormat($app_supported_currency);
			switch ($search_params['booking_source']) {
                case PROVAB_CAR_BOOKING_SOURCE :
                    //getting search params from table
                    $safe_search_data = $this->car_model->get_safe_search_data($search_params['search_id']);
                    //Meaning hotels are loaded first time
                    $raw_car_list = $this->car_lib->get_car_list(abs($search_params['search_id']));
                    // debug($raw_car_list);
                    if ($raw_car_list['status']) {
                        //Converting API currency data to preferred currency
                       
                        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_car_list = $this->car_lib->search_data_in_preferred_currency($raw_car_list, $currency_obj, $search_params['search_id']);
                        
                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        // debug($currency_obj);exit;
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_car_list['data'] = $this->car_lib->format_search_response($raw_car_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
                        // debug($raw_car_list);exit;
                        $source_result_count = $raw_car_list['data']['source_result_count'];
                        $filter_result_count = $raw_car_list['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->car_lib->filter_summary($raw_car_list['data']);
                            $response['filters'] = $filters['data'];
                        }
                        // debug($raw_car_list['data']);exit;
                        $raw_car_list['data'] = $this->car_lib->get_page_data($raw_car_list['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);

                        
                        $response['data'] = get_compressed_output(
                                $this->template->isolated_view('car/car_search_result_page', array('currency_obj' => $currency_obj, 'raw_car_list' => $raw_car_list['data'],
                                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                    'attr' => $attr,
                                    'search_params' => $safe_search_data,
									'currencyList' => $currencyList
                        )));
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

	/**
	 * Compress and output data
	 * @param array $data
	 */

	private function output_compressed_data($data)
	{
		while (ob_get_level() > 0) { ob_end_clean() ; }
		ob_start("ob_gzhandler");
		header('Content-type:application/json');
		echo json_encode($data);
		ob_end_flush();
		exit;
	}
 /**
	 * Compress and output data
	 * @param array $data
	 */
	private function output_compressed_data_flight($data)
	{
	
	   while (ob_get_level() > 0) { ob_end_clean() ; }
	   ob_start("ob_gzhandler");
	   ini_set("memory_limit", "-1");set_time_limit(0);
	   header('Content-type:application/json');
           echo  json_encode($data, JSON_UNESCAPED_SLASHES);
	   ob_end_flush();
	   exit;
	}
	 /**
    * Get Sightsseeing Category List
    */
    function get_ss_category_list(){
       $get_params = $this->input->get();
        if($get_params){
             if($get_params['city_id']){          

                   load_sightseen_lib(PROVAB_SIGHTSEEN_BOOKING_SOURCE); 
                   $select_cate_id = 0;
                   if(isset($get_params['Select_cate_id'])){
                     $select_cate_id = $get_params['Select_cate_id'];
                   }else{
                    $get_params['Select_cate_id'] =0;
                   }

                   $category_list = $this->sightseeing_lib->get_category_list($get_params);
                  if($category_list['status']==SUCCESS_STATUS){

                        $cate_response = $this->sightseeing_lib->format_category_response($category_list['data']['CategoryList'],$select_cate_id);
                       
                       if($cate_response['status']==SUCCESS_STATUS){
                            echo json_encode($cate_response['data']);
                            exit;
                       }
                  }else{
                    echo "0";
                    exit;
                  }
                         
             }else{
                echo "0";
                exit;
             }

       }else{
        echo "0";
        exit;
       }
    }
     /**
    *Elavarasi Get Sightseeing product list
    */
   	 public function sightseeing_list($offset=0){      
        $search_params = $this->input->get();
        // debug($search_params);
        // exit;
        $safe_search_data = $this->sightseeing_model->get_safe_search_data($search_params['search_id'],META_SIGHTSEEING_COURSE);

        $limit = $this->config->item('sightseeing_page_limit');

        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_sightseen_lib($search_params['booking_source']);
			$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country','currency_class')), array('status' => ACTIVE));
			$currencyList = currencyDataFormat($app_supported_currency);
            switch($search_params['booking_source']) {

                case PROVAB_SIGHTSEEN_BOOKING_SOURCE :
                    if(isset($search_params['cate_id'])){
                        $category_id = $search_params['cate_id'];
                    }else{
                        // if($safe_search_data['data']['category_id']){
                        //     $category_id = $safe_search_data['data']['category_id'];        
                        // }else{
                           
                        // }
                         $category_id = 0;
                    }
                    if(isset($search_params['sub_cate'])){
                        $sub_cate_id = $search_params['sub_cate'];
                    }else{
                        $sub_cate_id = 0;
                    }
                    if(isset($search_params['price_sort'])){
                        $price_sort = $search_params['price_sort'];
                    }else{
                        $price_sort = '';
                    }
                    if(isset($search_params['tour_name'])){
                        $tour_name = $search_params['tour_name'];
                    }else{
                        $tour_name = '';
                    }
                    if(isset($search_params['action'])){
                        if($search_params['action']=='reset'){
                            $category_id=0;
                            $sub_cate_id=0;
                            $price_sort='';
                            $tour_name='';
                          //  $safe_search_data['category_id'] = 0;
                        }
                    }

                    $search_data['category_id'] = $category_id;
                    $search_data['sub_cate_id'] = $sub_cate_id;
                    $search_data['price_sort'] = $price_sort;
                    $search_data['tour_name'] = $tour_name;
                    $raw_sightseeing_result = $this->sightseeing_lib->get_sightseeing_list($safe_search_data,$search_data);

                    if ($raw_sightseeing_result['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_sightseeing_result = $this->sightseeing_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj,'b2b');
                        

                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                       
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_sightseeing_result['data'] = $this->sightseeing_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);

                        
                        $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
                        $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->sightseeing_lib->filter_summary($raw_sightseeing_result['data']);
                            $response['filters'] = $filters['data'];
                        }
                        //debug($raw_hotel_list['data']);exit;
                      
                        // $raw_sightseeing_result['data'] = $this->sightseeing_lib->get_page_data($raw_sightseeing_result['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);
                       // debug($raw_sightseeing_result);exit;
                        $response['data'] = get_compressed_output(
                                $this->template->isolated_view('sightseeing/viator/viator_search_result', array('currency_obj' => $currency_obj, 'raw_sightseeing_list' => $raw_sightseeing_result['data'],
                                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                    'attr' => $attr,
                                    'search_params' => $safe_search_data['data'],
									'currencyList' => $currencyList
                        )));
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }else{
                        $response['status'] = FAILURE_STATUS;
                        
                    }
                break;
            }
        }
        $this->output_compressed_data($response);
    }

    /**
    *Elavarasi Get Transfer product list
    */
    public function transferv1_list($offset=0){      
        $search_params = $this->input->get();
        // debug($search_params);
        // exit;
        $safe_search_data = $this->transferv1_model->get_safe_search_data($search_params['search_id'],META_TRANSFERV1_COURSE);

        $limit = $this->config->item('transferv1_page_limit');

        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_transferv1_lib($search_params['booking_source']);

            switch($search_params['booking_source']) {

                case PROVAB_TRANSFERV1_BOOKING_SOURCE :
               
                    $raw_sightseeing_result = $this->transferv1_lib->get_transfer_list($safe_search_data);

                    if ($raw_sightseeing_result['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_sightseeing_result = $this->transferv1_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj,'b2b');                       
                      	// debug($raw_sightseeing_result);
                      	// exit;
                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                        $filters = array();                       

                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_sightseeing_result['data'] = $this->transferv1_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);

                        
                        $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
                        $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->transferv1_lib->filter_summary($raw_sightseeing_result['data']);
                            $response['filters'] = $filters['data'];
                        }
                     

                        $attr['search_id'] = abs($search_params['search_id']);
                       // debug($raw_sightseeing_result);exit;
                        $response['data'] = get_compressed_output(
                                $this->template->isolated_view('transferv1/viator/viator_search_result', array('currency_obj' => $currency_obj, 'raw_sightseeing_list' => $raw_sightseeing_result['data'],
                                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                    'attr' => $attr,
                                    'search_params' => $safe_search_data['data']
                        )));
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }else{
                        $response['status'] = FAILURE_STATUS;
                        
                    }
                break;
            }
        }
        $this->output_compressed_data($response);
    }

    /* transfer Module New */

    public function transfer_list($offset=0){ 
    error_reporting(0);  

		$search_params = $this->input->get();
			//debug($search_params);
			// exit;
			$safe_search_data = $this->transfer_model->get_safe_search_data($search_params['search_id'],META_TRANSFER_COURSE);
			//debug($safe_search_data);exit;
			$limit = $this->config->item('transfer_page_limit');
	
			if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
				load_transfer_lib($search_params['booking_source']);
	
				switch($search_params['booking_source']) {
	
					case PROVAB_TRANSFER_BOOKING_SOURCE :
				   
						$raw_transfer_result = $this->transfer_lib->get_transfer_list($safe_search_data);
						//debug($raw_transfer_result);exit;
						if ($raw_transfer_result['status']) {
							//Converting API currency data to preferred currency
							$currency_obj = new Currency(array('module_type' => 'transfer', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

							$raw_transfer_result = $this->transfer_lib->search_data_in_preferred_currency($raw_transfer_result, $currency_obj,'b2b');                       
							  // exit;
							//Display 
							//debug($currency_obj);
							$currency_obj = new Currency(array('module_type' => 'transfer', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
	
							$filters = array();                       
	
							//Update currency and filter summary appended
							if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
								$filters = $search_params['filters'];
							} else {
								$filters = array();
							}
							$raw_transfer_result['data'] = $this->transfer_lib->format_search_response($raw_transfer_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);	
							//debug($raw_transfer_result);exit;
							$source_result_count = $raw_transfer_result['data']['source_result_count'];
							$filter_result_count = $raw_transfer_result['data']['filter_result_count'];
							//debug($raw_hotel_list);exit;
							if (intval($offset) == 0) {
								//Need filters only if the data is being loaded first time
								$filters = $this->transfer_lib->filter_summary($raw_transfer_result['data']);
								$response['filters'] = $filters['data'];
							}
						 
	
							$attr['search_id'] = abs($search_params['search_id']);
						   // debug($raw_sightseeing_result);exit;
							$response['data'] = get_compressed_output(
									$this->template->isolated_view('transfer/hotelbeds/hotelbeds_search_result', array('currency_obj' => $currency_obj, 'raw_transfer_list' => $raw_transfer_result['data'],
										'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
										'attr' => $attr,
										'search_params' => $safe_search_data['data']
							)));
							$response['status'] = SUCCESS_STATUS;
							$response['total_result_count'] = $source_result_count;
							$response['filter_result_count'] = $filter_result_count;
							$response['offset'] = $offset + $limit;
						}else{
							$response['status'] = FAILURE_STATUS;
							
						}
					break;
				}
			}
			$this->output_compressed_data($response);
		}



     /*

     *

     * Transfer AutoSuggest List

     *

     */

      function get_transfer_code_list() {
        $result = array();
        $this->load->model('hotel_model');
        $this->load->model('transfer_model');
        $term = $this->input->get('term');
        $term = trim(strip_tags($term));
        
        $airport_data_list = $this->transfer_model->get_airport_list($term)->result();
    
        if (valid_array($airport_data_list) == false) {
            $airport_data_list = $this->transfer_model->get_airport_list($term)->result();
        }
    
        $terminal_results = array();
        $hotel_results = array();
    
        foreach ($airport_data_list as $airport) {
            $type = "";
            if ($airport->type == "ATLAS") {
                $label = $airport->name . ', ' . $airport->city_name . ', ' . $airport->country_name . ' (Hotel)';
                $type = "ProductTransferHotel";
                $hotel_results[] = array(
                    'label' => $label,
                    'id' => $airport->code,
                    'transfer_type' => $type,
                    'category' => array(),
                    'type' => array(),
                );
            } else {
                $label = $airport->name . ' (' . $airport->code . '), ' . $airport->country_name . ' (Terminal)';
                $type = "ProductTransferTerminal";
                $terminal_results[] = array(
                    'label' => $label,
                    'id' => $airport->code,
                    'transfer_type' => $type,
                    'category' => array(),
                    'type' => array(),
                );
            }
        }

        $result = array_merge($terminal_results, $hotel_results);
        
        $this->output_compressed_data($result);
    }


     /*
     *
     * Sightseeing AutoSuggest List
     *
     */

    function get_sightseen_city_list_old() {

        $this->load->model('sightseeing_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->sightseeing_model->get_sightseen_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->sightseeing_model->get_sightseen_city_list('');
        }
        $suggestion_list = array();
        $result = array();
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['city_name'];

            $suggestion_list['value'] = $city_list['city_name'];

          //  $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
            $suggestion_list['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Location list';
                $suggestion_list['type'] = 'Location list';
            }
           
            $suggestion_list['count'] = 0;
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }

	function get_sightseen_city_list() {

        $this->load->model('sightseeing_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->sightseeing_model->get_sightseen_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->sightseeing_model->get_sightseen_city_list('');
        }
        $suggestion_list = array();
        $result = array();
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['city_name'] . ', ' . $city_list['country_name'] . '';

            $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);

          //  $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
            $suggestion_list['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Location list';
                $suggestion_list['type'] = 'Location list';
            }
           
            $suggestion_list['count'] = 0;
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }

   
	function bus_list()
	{
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();
        $this->load->model('bus_model');
        $search_data = $this->bus_model->get_search_data($search_params['search_id']);
        
        $search_data = json_decode($search_data['search_data'], true);
		if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
			load_bus_lib($search_params['booking_source']);
			
			switch($search_params['booking_source']) {
				case PROVAB_BUS_BOOKING_SOURCE :
					$raw_bus_list = $this->bus_lib->get_bus_list(abs($search_params['search_id']));
  					 // debug($raw_bus_list);exit; 
  					$from_id = @$raw_bus_list['data']['result'][0]['From'];
					$to_id = @$raw_bus_list['data']['result'][0]['To'];
					$form_data = $this->bus_model->get_bus_station_data($search_data['from_station_id']);
				        $to_data = $this->bus_model->get_bus_station_data($search_data['to_station_id']);
					$search_data_city = array('from_id' => $form_data->station_id,
											   'to_id' => $to_data->station_id);
                    
					if ($raw_bus_list['status']) {
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'bus','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
						$raw_bus_list = $this->bus_lib->search_data_in_preferred_currency($raw_bus_list, $currency_obj);
						 // debug($raw_bus_list);exit; 
						$formatted_search_data = $this->bus_lib->format_search_response($raw_bus_list, $currency_obj, $search_params['search_id'], 'bus','B2B');
						// debug($formatted_search_data);exit;
						//Display Bus List
						$currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
						$raw_bus_list = force_multple_data_format($raw_bus_list['data']['result']);
						
						//Update commission
						// $raw_bus_list = $this->bus_lib->update_bus_search_commission($raw_bus_list, $currency_obj);
						// echo 'herre';
						
						$response['data'] = get_compressed_output(
						$this->template->isolated_view('bus/travelyaari/travelyaari_search_result',
						array('currency_obj' => $currency_obj, 'raw_bus_list' => $formatted_search_data, 'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],'search_data_city' => $search_data_city))
						);
						$response['status'] = SUCCESS_STATUS;
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}
	/**
	 * Load hotels from different source
	 */
	function bus_list_old()
	{
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();
		/*$search_params['op'] = 'load';
		 $search_params['search_id'] = 2461;
		 $search_params['booking_source'] = PROVAB_BUS_BOOKING_SOURCE;*/
		if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
			load_bus_lib($search_params['booking_source']);
			switch($search_params['booking_source']) {
				case PROVAB_BUS_BOOKING_SOURCE :
					$raw_bus_list = $this->bus_lib->get_bus_list(abs($search_params['search_id']));
					if ($raw_bus_list['status']) {
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'bus','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
						$raw_bus_list = $this->bus_lib->search_data_in_preferred_currency($raw_bus_list, $currency_obj);
						//Display Bus List
						$currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
						$raw_bus_list = force_multple_data_format($raw_bus_list['data']['result']);
						//Update commission
						$raw_bus_list = $this->bus_lib->update_bus_search_commission($raw_bus_list, $currency_obj);
						$response['data'] = get_compressed_output(
						$this->template->isolated_view('bus/travelyaari/travelyaari_search_result',
						array(
									'currency_obj' => $currency_obj, 'raw_bus_list' => $raw_bus_list,
									'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source']
						)
						)
						);
						$response['status'] = SUCCESS_STATUS;
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}

	function get_bus_information()
	{
		$response['data'] = 'No Details Found';
		$response['status'] = false;
		//check params
		$params = $this->input->post();
		/*$params['booking_source'] = 'PTBSID3377337777';
		 $params['journey_date'] = '2015-08-26T23:00:00';
		 $params['route_code'] = '215-9-3-10-23:00';
		 $params['route_schedule_id'] = '22579952';
		 $params['search_id'] = '2471';*/
		if (empty($params['booking_source']) == false and empty($params['search_id']) == false and intval($params['search_id']) > 0) {
			load_bus_lib($params['booking_source']);
			switch ($params['booking_source']) {
				case PROVAB_BUS_BOOKING_SOURCE :
					$currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
					$details = $this->bus_lib->get_bus_information($params['route_schedule_id'], $params['journey_date']);
					if ($details['status'] == SUCCESS_STATUS) {
						$response['stauts'] = SUCCESS_STATUS;
						$page_data['search_id'] = $params['search_id'];
						$page_data['details'] = @$details['data']['result'];
						$page_data['currency_obj'] = $currency_obj;
						$response['data'] = get_compressed_output($this->template->isolated_view('bus/travelyaari/travelyaari_bus_info', $page_data));
						$response['status'] = SUCCESS_STATUS;
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}

	/**
	 * Get Bus Booking List
	 */
	function get_bus_details($filter_boarding_points=false)
	{
		error_reporting(0);
		$this->load->model('bus_model');
		$response['data'] = 'No Details Found !! Try Later';
		$response['status'] = false;
		//check params
		$params = $this->input->post();
		$params = explode('*', $params['route_schedule_id']);
      
        $params['booking_source'] = $params[3];
        $params['search_id'] = $params[1];
        $params['route_schedule_id'] = $params[0];
        $params['route_code'] =  $params[2];
        $search_data = $this->bus_model->get_search_data($params['search_id']);
        // debug($params);exit;
        $search_data = json_decode($search_data['search_data'], true);
        $form_data = $this->bus_model->get_bus_station_data($search_data['from_station_id']);
        $to_data = $this->bus_model->get_bus_station_data($search_data['to_station_id']);
        
		if (empty($params['booking_source']) == false and empty($params['search_id']) == false and intval($params['search_id']) > 0) {
			load_bus_lib($params['booking_source']);
			switch ($params['booking_source']) {
				case PROVAB_BUS_BOOKING_SOURCE :
                    $bus_info_data = $this->bus_lib->get_route_details($params['search_id'], $params['route_schedule_id'], $params[2]);
                    $bus_info_data['bus_data']['Form_id'] = $form_data->station_id;
                    $bus_info_data['bus_data']['To_id'] = $to_data->station_id;
                    
                    $params['journey_date'] = $bus_info_data['bus_data']['DepartureTime'];
                    $params['ResultToken'] = $bus_info_data['bus_data']['ResultToken'];
					$details = $this->bus_lib->get_bus_details($params['route_schedule_id'], $params['journey_date'],$params['route_code'],$params['ResultToken'],$params['booking_source']);

					
					if ($details['status'] == SUCCESS_STATUS) {
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array(
								'module_type' => 'bus',
								'from' => get_api_data_currency(), 
								'to' => get_application_currency_preference()
							));
						$details = $this->bus_lib->seatdetails_in_preferred_currency($details, $bus_info_data['bus_data'], $currency_obj);
						
						$formatted_seat = $this->bus_lib->seat_layout_format($details['data']['result']['result']['value'], $currency_obj,'bus','B2B');
						$details['data']['result']['result']['value'] = $formatted_seat;
						// debug($details);exit;
						//Display Bus Details
						$currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
						$response['stauts'] = SUCCESS_STATUS;
						$page_data['search_id'] = $params['search_id'];
						$page_data['ResultToken'] = $params['ResultToken'];
						
						$page_data['details'] = $details['data']['result'];
						$page_data['currency_obj'] = $currency_obj;
                        // debug($page_data);exit;                        
						if ($filter_boarding_points == false) {
							$response['data'] = get_compressed_output($this->template->isolated_view('bus/travelyaari/travelyaari_bus_details', $page_data));
						} else {
							$response['data'] = get_compressed_output($this->template->isolated_view('bus/travelyaari/travelyaari_boarding_details', $page_data));
						}
						$response['status'] = SUCCESS_STATUS;
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}


	/**
	 * Load hotels from different source
	 */
	function get_room_details()
	{
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$params = $this->input->post();

		/*$params['HotelCode'] = '1000002306';
		 $params['ResultIndex'] = 28;
		 $params['booking_source'] = PROVAB_HOTEL_BOOKING_SOURCE;
		 $params['TraceId'] = '	c064afbd-dc5b-43e0-909f-50b8d9efdd3d';
		 $params['op'] = 'get_room_details';
		 $params['search_id'] = 2290;*/

		if ($params['op'] == 'get_room_details' && intval($params['search_id']) > 0 && isset($params['booking_source']) == true) {
			$application_preferred_currency = get_application_currency_preference();
			$application_default_currency = get_application_currency_preference();
			load_hotel_lib($params['booking_source']);
			$this->hotel_lib->search_data($params['search_id']);
			$attr['search_id'] = intval($params['search_id']);
			switch($params['booking_source']) {
				case PROVAB_HOTEL_BOOKING_SOURCE :
					$raw_room_list = $this->hotel_lib->get_room_list(urldecode($params['ResultIndex']));

					$safe_search_data = $this->hotel_model->get_safe_search_data($params['search_id']);
					if ($raw_room_list['status']) 
					{
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
						$raw_room_list = $this->hotel_lib->roomlist_in_preferred_currency($raw_room_list, $currency_obj,$params['search_id'],'b2b');
						//Display
						$currency_obj = new Currency(array('module_type' => 'hotel','from' => $application_default_currency, 'to' => $application_preferred_currency));

						$uniqueRoomTypes = array();
						$uniqueRoomCategory = array();

						/*$room_list = $raw_room_list['data']['GetHotelRoomResult']['HotelRoomsDetails'];
						if(count($room_list)>0){
						foreach ($room_list as $room) {
						    if (!in_array($room['RoomTypeName'], $uniqueRoomTypesList)) {
						        $uniqueRoomTypesList[] = $room['RoomTypeName'];
						        $uniqueRoomTypes[]['RoomTypeName'] = $room['RoomTypeName'];
						   	 }
							}

							if (!in_array($room['room_only'], $uniqueRoomTypesCategoryList)) {
						        $uniqueRoomTypesCategoryList[] = $room['room_only'];
						        $uniqueRoomCategory[]['RoomTypeCategory'] = $room['room_only'];
						   	 }
					 	}*/

						$room_types_list = $uniqueRoomTypes;
						$room_types_category = $uniqueRoomCategory;

						$response['data'] = get_compressed_output($this->template->isolated_view('hotel/tmx/tmx_room_list',
						array('currency_obj' => $currency_obj,
								'params' => $params, 'raw_room_list' => $raw_room_list['data'],
								'hotel_search_params'=>$safe_search_data['data'],
								'application_preferred_currency' => $application_preferred_currency,
								'application_default_currency' => $application_default_currency,
								'attr' => $attr
						)
						)
						);
						$response['room_types_list'] = $room_types_list;
						$response['room_types_category'] = $room_types_category;
						$response['status'] = SUCCESS_STATUS;
					}
					break;
					case HOTELBED_BOOKING_SOURCE :

					//debug($params['ResultIndex']); exit;
					$raw_room_list = $this->hotel_lib->get_room_list(urldecode($params['ResultIndex']));
                    $safe_search_data = $this->hotel_model->get_safe_search_data($params['search_id']);
                    // debug($raw_room_list);exit;
                    if ($raw_room_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => HOTELBEDS_DEFAULT_CURRENCY, 'to' => get_application_currency_preference()));
                        $raw_room_list = $this->hotel_lib->roomlist_in_preferred_currency($raw_room_list, $currency_obj, $params['search_id']);
                        //Display
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $application_default_currency, 'to' => $application_preferred_currency));
                        //debug($raw_room_list);exit;
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tmx/tmx_room_list',
                                array(
                                    'currency_obj' => $currency_obj,
                                    'params' => $params,
                                    'raw_room_list' => $raw_room_list['data'],
                                    'hotel_search_params' => $safe_search_data['data'],
                                    'application_preferred_currency' => $application_preferred_currency,
                                    'application_default_currency' => $application_default_currency,
                                    'attr' => $attr
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
					break;
			}
		}
		$this->output_compressed_data($response);
	}


	/**
	 * Load Flight from different source
	 * 2339 - one way - bangalore to goa
	 * 2341 - one way bangalore to dubai
	 */
	function flight_list($search_id='')
	{
		$response = array();
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();
		$page_params['search_id'] = $search_params['search_id'];
		if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
			load_flight_lib($search_params['booking_source']);
			switch($search_params['booking_source']) {
				case PROVAB_FLIGHT_BOOKING_SOURCE :
					$raw_flight_list = $this->flight_lib->get_flight_list(abs($search_params['search_id']));
					if ($raw_flight_list['status']) {
						//View Data
						$raw_search_result = $raw_flight_list['data']['Search']['FlightDataList'];
                                                
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                                                 
						$raw_search_result = $this->flight_lib->search_data_in_preferred_currency($raw_search_result, $currency_obj);

						//Display
						$currency_obj = new Currency(array('module_type' => 'flight','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                                              
						$formatted_search_data = $this->flight_lib->format_search_response($raw_search_result, $currency_obj, $search_params['search_id'], $this->current_module, $raw_flight_list['from_cache'], $raw_flight_list['search_hash']);
                          
                           //debug($formatted_search_data);exit;

						$raw_flight_list['data'] = $formatted_search_data['data'];
						$route_count = count($raw_flight_list['data']['Flights']);
						$domestic_round_way_flight = $raw_flight_list['data']['JourneySummary']['IsDomesticRoundway'];
						if (($route_count > 0  && $domestic_round_way_flight == false) || ($route_count == 2 && $domestic_round_way_flight == true)) {
							$attr['search_id'] = abs($search_params['search_id']);
							$page_params = array(
							'raw_flight_list' => $raw_flight_list['data'],
							'search_id' => $search_params['search_id'],
							'booking_url' => $formatted_search_data['booking_url'],
							'ShoppingResponseId' => '',
							'booking_source' => $search_params['booking_source'],
							'cabin_class' => $raw_flight_list['cabin_class'],
							'trip_type' => $this->flight_lib->master_search_data['trip_type'],
							'alt_days' => '',
							'attr' => $attr,
							'route_count' => $route_count,
							'session_id' => $raw_flight_list['session_id'],
							'IsDomestic' => $raw_flight_list['data']['JourneySummary']['IsDomestic'],
                            'total_pax' => intval($this->flight_lib->master_search_data['adult_config'] + $this->flight_lib->master_search_data['child_config'] + $this->flight_lib->master_search_data['infant_config']),
							'template_image_path'=>SYSTEM_TEMPLATE_LIST.'/'. $this->application_default_template .TEMPLATE_IMAGE_DIR
							);
							$page_params['domestic_round_way_flight'] = $domestic_round_way_flight;
							$page_params['session_expiry_details']=$formatted_search_data['session_expiry_details'];
							$page_params['logo_path']=SYSTEM_IMAGE_DIR;
							$page_params['module']='b2b';
							echo json_encode($page_params);
							exit; // don't comment and remove its json format flight results
							$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country','currency_class')), array('status' => ACTIVE));
			        		$page_params['currencyList'] = currencyDataFormat($app_supported_currency);

							// $json = json_encode ( $page_params, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT );
							// file_put_contents('logs/search_result_ndc.txt',  $json);
							// echo json_encode($page_params);exit;
							$page_view_data = $this->template->isolated_view('flight/tbo/tbo_col2x_search_result', $page_params);
							$response['data'] = get_compressed_output($page_view_data);
							$response['status'] = SUCCESS_STATUS;
							$response['booking_source'] = $search_params['booking_source'];
							/*
								session expiry start time and search hash 
							*/
							$response['session_expiry_details'] = $formatted_search_data['session_expiry_details'];
						}
					}
					break;

					case TBO_FLIGHT_BOOKING_SOURCE :
					$raw_flight_list = $this->flight_lib->get_flight_list(abs($search_params['search_id']));
					//debug($raw_flight_list);exit;
					if ($raw_flight_list['status']) {
						//View Data
						$raw_search_result = $raw_flight_list['data']['Search']['FlightDataList'];
                                                
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                                                 
						$raw_search_result = $this->flight_lib->search_data_in_preferred_currency($raw_search_result, $currency_obj);

						//debug($raw_search_result); exit;

						//Display
						$currency_obj = new Currency(array('module_type' => 'flight','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                                              
						$formatted_search_data = $this->flight_lib->format_search_response($raw_search_result, $currency_obj, $search_params['search_id'], $this->current_module, $raw_flight_list['from_cache'], $raw_flight_list['search_hash']);
                          
                           //debug($formatted_search_data);exit;

						$raw_flight_list['data'] = $formatted_search_data['data'];
						$route_count = count($raw_flight_list['data']['Flights']);
						$domestic_round_way_flight = $raw_flight_list['data']['JourneySummary']['IsDomesticRoundway'];
						//if (($route_count > 0  && $domestic_round_way_flight == false) || ($route_count == 2 && $domestic_round_way_flight == true)) {
						if ($route_count > 0 ) {
							$attr['search_id'] = abs($search_params['search_id']);
							$page_params = array(
							'raw_flight_list' => $raw_flight_list['data'],
							'search_id' => $search_params['search_id'],
							'booking_url' => $formatted_search_data['booking_url'],
							'ShoppingResponseId' => '',
							'booking_source' => $search_params['booking_source'],
							'cabin_class' => $raw_flight_list['cabin_class'],
							'trip_type' => $this->flight_lib->master_search_data['trip_type'],
							'alt_days' => '',
							'attr' => $attr,
							'route_count' => $route_count,
							'session_id' => $raw_flight_list['session_id'],
							'IsDomestic' => $raw_flight_list['data']['JourneySummary']['IsDomestic'],
                            'total_pax' => intval($this->flight_lib->master_search_data['adult_config'] + $this->flight_lib->master_search_data['child_config'] + $this->flight_lib->master_search_data['infant_config']),
							'template_image_path'=>SYSTEM_TEMPLATE_LIST.'/'. $this->application_default_template .TEMPLATE_IMAGE_DIR
							);
							$page_params['domestic_round_way_flight'] = $domestic_round_way_flight;
							$page_params['session_expiry_details']=$formatted_search_data['session_expiry_details'];
							$page_params['logo_path']=SYSTEM_IMAGE_DIR;
							$page_params['module']='b2b';
							echo json_encode($page_params);exit;
							if(!$domestic_round_way_flight){
								echo json_encode($page_params);exit; // don't comment and remove its json format flight results
							}
							$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country','currency_class')), array('status' => ACTIVE));
			        		$page_params['currencyList'] = currencyDataFormat($app_supported_currency);
							$page_view_data = $this->template->isolated_view('flight/tbo/tbo_col2x_search_result', $page_params);
							$response['data'] = get_compressed_output($page_view_data);
							$response['status'] = SUCCESS_STATUS;
							$response['booking_source'] = $search_params['booking_source'];
							/*
								session expiry start time and search hash 
							*/
							$response['session_expiry_details'] = $formatted_search_data['session_expiry_details'];
						}
					}
					break;

			}
		}
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$this->output_compressed_data_flight($response);
	}

	/**
	 * Get Data For Fare Calendar
	 * @param string $booking_source
	 */
function puls_minus_days_fare_list($booking_source)
	{
		$response['data'] = array();
		$response['status'] = FAILURE_STATUS;

		$params = $this->input->get();
		load_flight_lib($booking_source);
		$search_data = $this->flight_lib->search_data(intval($params['search_id']));
		if ($search_data['status'] == SUCCESS_STATUS) {
			$date_array = array();
			$departure_date = $search_data['data']['depature'];
			$departure_date = strtotime(subtract_days_from_date(3, $departure_date));
			if (time() >= $departure_date) {
				$date_array[] = date('Y-m-d', strtotime(add_days_to_date(1)));
			} else {
				$date_array[] = date('Y-m-d', $departure_date);
			}
			$date_array[] = date('Y-m', strtotime($departure_date[0].' +1 month')).'-1';
			//Get Current Month And Next Month
			$day_fare_list = array();
			foreach ($date_array as $k => $v) {
				$search_data['data']['depature'] = $v;
				$search = $this->flight_lib->calendar_safe_search_data($search_data['data']);
				if (valid_array($search) == true) {
					switch($booking_source) {
						case PROVAB_FLIGHT_BOOKING_SOURCE :
							$raw_fare_list = $this->flight_lib->get_fare_list($search);
							if ($raw_fare_list['status']) {
								$fare_calendar_list = $this->flight_lib->format_cheap_fare_list($raw_fare_list['data']);
								if ($fare_calendar_list['status'] == SUCCESS_STATUS) {
									$response['data']['departure'] = $search['depature'];
									$calendar_events = $this->get_fare_calendar_events($fare_calendar_list['data'], $raw_fare_list['data']['TraceId']);
									$day_fare_list = array_merge($day_fare_list, $calendar_events);
									$response['status'] = SUCCESS_STATUS;
								} else {
									$response['msg'] = 'Not Available!!! Please Try Later!!!!';
								}
							}
							break;
					}
				}
			}
			$response['data']['day_fare_list'] = $day_fare_list;
		}
		$this->output_compressed_data($response);
	}

	/**
	 * get fare list for calendar search - FLIGHT
	 */
	function fare_list($booking_source)
	{
		/*$options = array('location' => 'http://192.168.0.63/soap/server1.php',
		 'uri' => 'http://192.168.0.63/soap/');
		 $api = new SoapClient(NULL, $options);
		 echo "<pre>"; print_r($api->hello()); exit;*/

		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();
		load_flight_lib($booking_source);
		$search_params = $this->flight_lib->calendar_safe_search_data($search_params);
		if (valid_array($search_params) == true) {
			switch($booking_source) {
				case PROVAB_FLIGHT_BOOKING_SOURCE :
					$raw_fare_list = $this->flight_lib->get_fare_list($search_params);
					if ($raw_fare_list['status']) {
						$fare_calendar_list = $this->flight_lib->format_cheap_fare_list($raw_fare_list['data']);
						if ($fare_calendar_list['status'] == SUCCESS_STATUS) {
							$response['data']['departure'] = $search_params['depature'];
							$calendar_events = $this->get_fare_calendar_events($fare_calendar_list['data'], $raw_fare_list['data']['GetCalendarFareResult']['SessionId']);
							$response['data']['day_fare_list'] = $calendar_events;
							$response['status'] = SUCCESS_STATUS;
						} else {
							$response['msg'] = 'Not Available!!! Please Try Later!!!!';
						}
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}

	/**
	 * Calendar Event Object
	 * @param $title
	 * @param $start
	 * @param $tip
	 * @param $href
	 * @param $event_date
	 * @param $session_id
	 * @param $add_class
	 */
	private function get_calendar_event_obj($title='', $start = '', $tip = '',$add_class = '', $href = '', $event_date = '', $session_id = '', $data_id='')
	{
		$event_obj = array();
		if (empty($data_id) == false) {
			$event_obj['data_id'] = $data_id;
		} else {
			$event_obj['data_id'] = '';
		}

		if (empty($title) == false) {
			$event_obj['title'] = $title;
		} else {
			$event_obj['title'] = '';
		}
		//start
		if (empty($start) == false) {
			$event_obj['start'] = $start;
			$event_obj['start_label'] = date('M d', strtotime($start));
		} else {
			$event_obj['start'] = '';
		}
		//tip
		if (empty($tip) == false) {
			$event_obj['tip'] = $tip;
		} else {
			$event_obj['tip'] = '';
		}
		//href
		if (empty($href) == false) {
			$event_obj['href'] = $href;
		} else {
			$event_obj['href'] = '';
		}
		//event_date
		if (empty($event_date) == false) {
			$event_obj['event_date'] = $event_date;
		}
		//session_id
		if (empty($session_id) == false) {
			$event_obj['session_id'] = $session_id;
		}
		//add_class
		if (empty($add_class) == false) {
			$event_obj['add_class'] = $add_class;
		} else {
			$event_obj['add_class'] = '';
		}
		return $event_obj;
	}

	function day_fare_list($booking_source)
	{
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();
		load_flight_lib($booking_source);
		$safe_search_params = $this->flight_lib->calendar_day_fare_safe_search_data($search_params);
		if ($safe_search_params['status'] == SUCCESS_STATUS) {
			switch($booking_source) {
				case PROVAB_FLIGHT_BOOKING_SOURCE :
					$raw_day_fare_list = $this->flight_lib->get_day_fare($search_params);
					if ($raw_day_fare_list['status']) {
						$fare_calendar_list = $this->flight_lib->format_day_fare_list($raw_day_fare_list['data']);
						if ($fare_calendar_list['status'] == SUCCESS_STATUS) {
							$calendar_events = $this->get_fare_calendar_events($fare_calendar_list['data'], '');
							$response['data']['day_fare_list'] = $calendar_events;
							$response['data']['departure'] = $search_params['depature'];
							$response['status'] = SUCCESS_STATUS;
						} else {
							$response['msg'] = 'Not Available!!! Please Try Later!!!!';
						}
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}

	private function get_fare_calendar_events($events, $session_id='')
	{
		$currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$index = 0;
		$calendar_events = array();
		foreach ($events as $k => $day_fare) {
			if (valid_array($day_fare) == true) {
				$fare_object = array('BaseFare' => $day_fare['BaseFare']);
				$BaseFare = $this->flight_lib->update_markup_currency($fare_object, $currency_obj);
				$tax = $currency_obj->get_currency($day_fare['tax'], false);
				$day_fare['price'] = floor($BaseFare['BaseFare']+$day_fare['tax']);
				$event_obj = $this->get_calendar_event_obj(
				$currency_obj->get_currency_symbol(get_application_currency_preference()).' '.$day_fare['price'],
				$k, $day_fare['airline_name'].'-'.$day_fare['airline_code'], 'search-day-fare', '', $day_fare['departure'], '',
				$day_fare['airline_code']);
				$calendar_events[$index] = $event_obj;
			} else {
				$event_obj = $this->get_calendar_event_obj('Update', $k, 'Current Cheapest Fare Not Available. Click To Get Latest Fare.' ,
				'update-day-fare', '', $k, $session_id, '');
				$calendar_events[$index] = $event_obj;
			}
			$index++;
		}
		return $calendar_events;
	}

	/**
	 * Get Fare Details
	 */
	function get_fare_details()
	{
		$response['status'] = false;
		$response['data'] = '';
		$response['msg'] = '<i class="fa fa-warning text-danger"></i> Fare Details Not Available';
		$params = $this->input->post();
		
		load_flight_lib($params['booking_source']);
		$data_access_key = $params['data_access_key'];
		$params['data_access_key'] = unserialized_data($params['data_access_key']);
		if (empty($params['data_access_key']) == false) {
			switch($params['booking_source']) {
				case PROVAB_FLIGHT_BOOKING_SOURCE :
					$params['data_access_key'] = $this->flight_lib->read_token($data_access_key);
					$data = $this->flight_lib->get_fare_details($params['data_access_key'], $params['search_access_key']);
					if ($data['status'] == SUCCESS_STATUS) {
						$response['status']	= SUCCESS_STATUS;
						$response['data']	= $this->template->isolated_view('flight/tbo/fare_details', array('fare_rules' => $data['data'],'booking_source'=>$params['booking_source']));
						$response['msg']	= 'Fare Details Available';
					}
					break;
				case CLARITYNDC_FLIGHT_BOOKING_SOURCE :
					$params['data_access_key'] = $this->flight_lib->read_token($data_access_key);
					$data = $this->flight_lib->get_fare_details($params['search_access_key'],$params['ShoppingResponseId']);
					if ($data['status'] == SUCCESS_STATUS) {
						$response['status']	= SUCCESS_STATUS;
						$response['data']	= $this->template->isolated_view('flight/tbo/fare_details', array('fare_rules' => $data['data'],'booking_source'=>$params['booking_source']));
						$response['msg']	= 'Fare Details Available';
					}
					break;	
			}
		}
		$this->output_compressed_data($response);
	}

	function get_combined_booking_from()
	{
		$response['status']	= FAILURE_STATUS;
		$response['data']	= array();
		$params = $this->input->post();
		if (empty($params['search_id']) == false && empty($params['trip_way_1']) == false && empty($params['trip_way_2']) == false) {
			$tmp_trip_way_1	= json_decode($params['trip_way_1'], true);
			$tmp_trip_way_2	= json_decode($params['trip_way_2'], true);
			$search_id	= $params['search_id'];
			foreach($tmp_trip_way_1 as $___v) {
				$trip_way_1[$___v['name']] = $___v['value'];
			}
			foreach($tmp_trip_way_2 as $___v) {
				$trip_way_2[$___v['name']] = $___v['value'];
			}
			$booking_source = $trip_way_1['booking_source'];
			switch($booking_source) {
				case PROVAB_FLIGHT_BOOKING_SOURCE : load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				$response['data']['booking_url']	= $this->flight_lib->booking_url(intval($params['search_id']));
				$response['data']['form_content']	= $this->flight_lib->get_form_content($trip_way_1, $trip_way_2);
				$response['status']					= SUCCESS_STATUS;
				break;
			}
		}
		$this->output_compressed_data($response);
	}

	/**
	 *
	 */
	function log_event_ip_info($eid)
	{
		$params = $this->input->post();
		if (empty($eid) == false) {
			$this->custom_db->update_record('exception_logger', array('client_info' => serialize($params)), array('exception_id' => $eid));
		}
	}
	//---------------------------------------------------------------- Booking Events Starts
	/**
	* Load Booking Events of all the modules
	*/
	function booking_events()
	{
		$status = true;
		$data = array();
		$calendar_events = array();
		$condition = array(array('BD.created_datetime', '>=', $this->db->escape(date('Y-m-d', strtotime(subtract_days_from_date(90))))));//of last 30 days only
		if (is_active_bus_module()) {
			$calendar_events = array_merge($calendar_events, $this->bus_booking_events($condition));
                       
		}
		if (is_active_hotel_module()) {
			$calendar_events = array_merge($calendar_events, $this->hotel_booking_events($condition));
		}
		if (is_active_airline_module()) {
			$calendar_events = array_merge($calendar_events, $this->flight_booking_events($condition));
		}

		if (is_active_sightseeing_module()) {

			$calendar_events = array_merge($calendar_events, $this->sightseeing_booking_events($condition));
		}
		if (is_active_transferv1_module()) {

			$calendar_events = array_merge($calendar_events, $this->transfers_booking_events($condition));
		}


               // debug($calendar_events);exit;
		header('content-type:application/json');
		echo json_encode(array('status' => $status, 'data' => $calendar_events));
		exit;
	}

	/**
	 * Hotel Booking Events Summary
	 * @param array $condition
	 */
	private function hotel_booking_events($condition)
	{
		$this->load->model('hotel_model');
		$data_list = $this->hotel_model->booking($condition);
		$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($data_list, 'b2b');
		$booking_details = $table_data['data']['booking_details'];
		$calendar_events = array();
		if (valid_array($booking_details) == true) {
			$key = 0;
			foreach ($booking_details as $k => $v) {
				$calendar_events[$key]['title'] = $v['app_reference'].'-'.$v['status'];
				$calendar_events[$key]['start'] = $v['created_datetime'];
				$calendar_events[$key]['tip'] = $v['app_reference'].'-PNR:'.$v['confirmation_reference'].'-From:'.$v['hotel_check_in'].', To:'.$v['hotel_check_out'].'-'.$v['status'].'- Click To View More Details';
				$calendar_events[$key]['href'] = hotel_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
				$calendar_events[$key]['add_class'] = 'hand-cursor event-hand hotel-booking';
				$key++;
			}
		}
		return $calendar_events;
	}

	/**
	 * Flight Booking Events Summary
	 * @param array $condition
	 */
	private function flight_booking_events($condition)
	{
		$this->load->model('flight_model');
		$data_list = $this->flight_model->booking($condition);
		$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_flight_booking_data($data_list, 'b2b');
		$booking_details = $table_data['data']['booking_details'];
		$calendar_events = array();
		if (valid_array($booking_details) == true) {
			$key = 0;
			foreach ($booking_details as $k => $v) {
				$calendar_events[$key]['title'] = $v['app_reference'].'-'.$v['status'];
				$calendar_events[$key]['start'] = $v['created_datetime'];
				$calendar_events[$key]['tip'] = $v['app_reference'].',From:'.$v['journey_from'].', To:'.$v['journey_to'].'-'.$v['status'].'- Click To View More Details';
				$calendar_events[$key]['href'] = flight_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
				$calendar_events[$key]['add_class'] = 'hand-cursor event-hand flight-booking';
				$key++;
			}
		}

		return $calendar_events;
	}

	/**
	 * Sightseeing Booking Events Summary
	 * @param array $condition
	 */
	private function sightseeing_booking_events($condition)
	{
		$this->load->model('sightseeing_model');
		$data_list = $this->sightseeing_model->booking($condition);
		$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($data_list, 'b2b');
		$booking_details = $table_data['data']['booking_details'];
		$calendar_events = array();
		if (valid_array($booking_details) == true) {
			$key = 0;
			foreach ($booking_details as $k => $v) {
				$calendar_events[$key]['title'] = $v['app_reference'].'-'.$v['status'];
				$calendar_events[$key]['start'] = $v['created_datetime'];
				$calendar_events[$key]['tip'] = $v['app_reference'].'-PNR:'.$v['confirmation_reference'].'-From:'.$v['destination_name'].', Travel Date:'.$v['travel_date'].'-'.$v['status'].'- Click To View More Details';
				$calendar_events[$key]['href'] = sightseeing_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
				$calendar_events[$key]['add_class'] = 'hand-cursor event-hand sightseeing-booking';
				$key++;
			}
		}

		return $calendar_events;
	}
	/**
	 * Transfers Booking Events Summary
	 * @param array $condition
	 */
	private function transfers_booking_events($condition){
		$this->load->model('transferv1_model');
		$data_list = $this->transferv1_model->booking($condition);
		$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($data_list, 'b2b');
		$booking_details = $table_data['data']['booking_details'];
		$calendar_events = array();

		if (valid_array($booking_details) == true) {
			$key = 0;
			foreach ($booking_details as $k => $v) {
				$calendar_events[$key]['title'] = $v['app_reference'].'-'.$v['status'];
				$calendar_events[$key]['start'] = $v['created_datetime'];
				$calendar_events[$key]['tip'] = $v['app_reference'].'-PNR:'.$v['confirmation_reference'].'-From:'.$v['destination_name'].', Travel Date:'.$v['travel_date'].'-'.$v['status'].'- Click To View More Details';
				$calendar_events[$key]['href'] = transfers_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
				$calendar_events[$key]['add_class'] = 'hand-cursor event-hand transfers-booking';
				//$calendar_events[$k]['prepend_element'] = '<i class="fa fa-bus"></i>';
				$key++;
			}
		}
		return $calendar_events;
	}

	/**
	 * Bus Booking Events Summary
	 * @param array $condition
	 */
	private function bus_booking_events($condition)
	{
		$this->load->model('bus_model');
		$data_list = $this->bus_model->booking($condition);
		$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_bus_booking_data($data_list, 'b2b');
		$booking_details = $table_data['data']['booking_details'];
		$calendar_events = array();
		if (valid_array($booking_details) == true) {
			$key = 0;
			foreach ($booking_details as $k => $v) {
				$calendar_events[$key]['title'] = $v['app_reference'].'-'.$v['status'];
				$calendar_events[$key]['start'] = $v['created_datetime'];
				$calendar_events[$key]['tip'] = $v['app_reference'].'-PNR:'.$v['pnr'].'-From:'.$v['departure_from'].', To:'.$v['arrival_to'].'-'.$v['status'].'- Click To View More Details';
				$calendar_events[$key]['href'] = bus_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
				$calendar_events[$key]['add_class'] = 'hand-cursor event-hand bus-booking';
				//$calendar_events[$k]['prepend_element'] = '<i class="fa fa-bus"></i>';
				$key++;
			}
		}
		return $calendar_events;
	}
	//---------------------------------------------------------------- Booking Events End
	/**
	* Balu A
	*
	*/
	function auto_suggest_booking_id()
	{
		$get_data = $this->input->get();
		$this->load->model('report_model');
		if(valid_array($get_data) == true && empty($get_data['term']) == false && empty($get_data['module']) == false) {
			
			$module = trim($get_data['module']);
			$chars = $get_data['term'];
			switch($module) {
				case PROVAB_FLIGHT_BOOKING_SOURCE:
					$list = $this->report_model->auto_suggest_flight_booking_id($chars);
					break;
				case PROVAB_HOTEL_BOOKING_SOURCE:
					$list = $this->report_model->auto_suggest_hotel_booking_id($chars);
					break;
				case PROVAB_BUS_BOOKING_SOURCE:
					$list = $this->report_model->auto_suggest_bus_booking_id($chars);
					break;
				case PROVAB_TRANSFER_BOOKING_SOURCE:
					$list = $this->report_model->auto_suggest_transfer_booking_id($chars);
					break;
			}
			$temp_list = array();
			if (valid_array ( $list ) == true) {
				foreach ( $list as $k => $v ) {
					$temp_list [] = array (
							'id' => $k,
							'label' => $v ['app_reference'],
							'value' => $v ['app_reference']
					);
				}
			}
			$this->output_compressed_data($temp_list);
		}
	}
	/**
	 * Jagnaath
	 * Get Bank Branches
	 */
	function get_bank_branches($bank_origin)
	{
		if(intval($bank_origin) > 0) {
			$data['status'] = false;
			$data['branches'] = false;
			$branch_details = $this->custom_db->single_table_records('bank_account_details', 'origin, en_branch_name, account_number', array('origin' => intval($bank_origin), 'status' => ACTIVE));
			if($branch_details['status'] == true) {
				$data['status'] = true;
				$data['branch'] = $branch_details['data'][0]['en_branch_name'];
				$data['account_number'] = $branch_details['data'][0]['account_number'];
			}
		}
		$this->output_compressed_data($data);
	}
	/**
	* Get Hotel Images by HotelCode
	*/
	function get_hotel_images(){
		$post_params = $this->input->post();
		if($post_params['hotel_code']){
			//debug($post_params['hotel_code']);exit;
			switch ($post_params['booking_source']) {

				case PROVAB_HOTEL_BOOKING_SOURCE:
					load_hotel_lib($post_params['booking_source']);
					$raw_hotel_images = $this->hotel_lib->get_hotel_images($post_params['hotel_code']);	

					//debug($raw_hotel_images);exit;
					if($raw_hotel_images['status']==true){
							$this->hotel_model->add_hotel_images($post_params['search_id'],$raw_hotel_images['data'],$post_params['hotel_code']);
							$response['data'] = get_compressed_output(
							$this->template->isolated_view('hotel/tmx/tmx_hotel_images',
							array('hotel_images'=>$raw_hotel_images,'HotelCode'=>$post_params['hotel_code'],'HotelName'=>$post_params['Hotel_name']
							)));
					}
					
					break;
			}
			 $this->output_compressed_data($response);
		
		}
		exit;
	}
		/**
	*Get Cancellation Policy based on Cancellation policy code
	*
	*/
	function get_cancellation_policy_old(){
		$get_params =$this->input->get();
		
		$application_preferred_currency = get_application_currency_preference();
		$application_default_currency = get_application_currency_preference();
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$room_price = $get_params['room_price'];
		//debug($get_params);exit;
		if(isset($get_params['booking_source'])&&!empty($get_params['booking_source'])){
			load_hotel_lib($get_params['booking_source']);

			if($get_params['today_cancel_date']==false){
				if(isset($get_params['policy_code'])&&!empty($get_params['policy_code'])){
					$safe_search_data = $this->hotel_model->get_safe_search_data($get_params['tb_search_id']);				
					$get_params['no_of_nights'] = $safe_search_data['data']['no_of_nights'];
					$get_params['room_count'] = $safe_search_data['data']['room_count'];
					$get_params['check_in'] = $safe_search_data['data']['from_date'];
	 				$cancellation_details = $this->hotel_lib->get_cancellation_details($get_params);
	 				
	 				
	 				$cancellatio_details =$cancellation_details['GetCancellationPolicy']['policy'][0]['policy'];
	 				$policy_string ='';
	 				$cancel_string='';
	 				$cancel_count = count($cancellatio_details);
	 				//$cancel_reverse = $cancellatio_details; 
	 				$cancel_reverse = $this->hotel_lib->php_arrayUnique($cancellatio_details,'Charge');				
	 				//debug($cancellatio_details);exit;
	 				$cancellatio_details = $this->hotel_lib->php_arrayUnique($cancellatio_details,'Charge');
	 					foreach ($cancellatio_details as $key => $value) {
		 					$amount = 0;
		 					$policy_string ='';	 					
			 					if($value['Charge']==0){
			 						$policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
			 					}else{
			 						if($value['Charge']!=0){
			 							 if(isset($cancel_reverse[$key+1])){
			 							 		if($value['ChargeType']==1){ 					
							 						$amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".get_converted_currency_value($currency_obj->force_currency_conversion(round($value['Charge'])));							 						
							 					}elseif($value['ChargeType']==2){
							 						$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".$room_price;
							 					}
							 					$current_date = date('Y-m-d');
												$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
												if($cancell_date >$current_date){
													//$value['FromDate'] = date('Y-m-d');
													$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
												}
							 					//$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
							                 
							             }else{
							             	if($value['ChargeType']==1){
							             		$amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".get_converted_currency_value($currency_obj->force_currency_conversion(round($value['Charge'])));	
							          		}elseif ($value['ChargeType']==2) {
							             		$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".$room_price;
							             	}
							             	$current_date = date('Y-m-d');
											$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
											if($cancell_date > $current_date){
												$value['FromDate'] =$value['FromDate']; 
											}else{
												$value['FromDate'] = date('Y-m-d');
											}
							             	$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
							             }
					 				
				 					}
			 					}		 					
			 				$cancel_string .= $policy_string.'<br/> ';
		 				}
	 				
	 				echo $cancel_string;
					//echo $cancellation_details['GetCancellationPolicy']['policy'][0];
				}else{
					$cancel_string ='';
						$cancellation_policy_details = json_decode(base64_decode($get_params['policy_details']));
						//debug($cancellation_policy_details);
						$cancel_count = count($cancellation_policy_details);					
						$cancellation_policy_details = json_decode(json_encode($cancellation_policy_details), True);
						$cancel_reverse = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details),'Charge');			
						//$cancel_reverse = array_reverse($cancellation_policy_details);
						
						//debug($cancel_reverse);						
						$cancellation_policy_details = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details),'Charge');
						
						if($cancellation_policy_details){
								//$cancellation_policy_details = array_reverse($cancellation_policy_details);
								foreach ($cancellation_policy_details as $key=>$value) {							
										$policy_string ='';								
											if($value['Charge']==0){
												$policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
											}else{
												if(isset($cancel_reverse[$key+1])){
													if($value['ChargeType']==1){
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$value['Charge'];
														
													}elseif ($value['ChargeType']==2) {
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$room_price;
													}
													$current_date = date('Y-m-d');
													$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
													if($cancell_date >$current_date){
														$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
													}
													
												}else{
													if($value['ChargeType']==1){
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$value['Charge'];
														
													}elseif ($value['ChargeType']==2) {
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$room_price;
													}
													$current_date = date('Y-m-d');
													$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
													if($cancell_date >$current_date){
														$value['FromDate'] = $value['FromDate'];
													}else{
														$value['FromDate'] = date('Y-m-d');
													}
													$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
												}
											}									
																		
										$cancel_string .= $policy_string.'<br/>';
										
								}
						}else{
							$cancel_string = 'This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
						}
						
					
					echo $cancel_string;
				}
			}else{
				echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
			}
			
		}else{
			echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
		}
		exit;
	}
	function get_cancellation_policy(){
		error_reporting(0);
		$get_params =$this->input->get();
		//debug($get_params);exit;
		$application_preferred_currency = get_application_currency_preference();
		$application_default_currency = get_application_currency_preference();
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$room_price = @$get_params['room_price'];
		//debug($get_params);exit;
		if(isset($get_params['booking_source'])&&!empty($get_params['booking_source'])){
			load_hotel_lib($get_params['booking_source']);

			if($get_params['today_cancel_date']==false){
				if(isset($get_params['policy_code'])&&!empty($get_params['policy_code'])){
					$safe_search_data = $this->hotel_model->get_safe_search_data($get_params['tb_search_id']);				
					$get_params['no_of_nights'] = $safe_search_data['data']['no_of_nights'];
					$get_params['room_count'] = $safe_search_data['data']['room_count'];
					$get_params['check_in'] = $safe_search_data['data']['from_date'];
	 				$cancellation_details = $this->hotel_lib->get_cancellation_details($get_params);
	 				$cancellatio_details =$cancellation_details['GetCancellationPolicy']['policy'][0]['policy'];
	 				
	 				$policy_string ='';
	 				$cancel_string='';
	 				$cancel_count = count($cancellatio_details);
	 				//$cancel_reverse = $cancellatio_details; 
	 				$cancel_reverse = $this->hotel_lib->php_arrayUnique($cancellatio_details,'Charge');				
	 				//debug($cancellatio_details);exit;
	 				$cancellatio_details = $this->hotel_lib->php_arrayUnique($cancellatio_details,'Charge');
	 					foreach ($cancellatio_details as $key => $value) {

	 						$value['Charge'] = $this->hotel_lib->update_cancellation_markup_currency($value['Charge'],$currency_obj,$get_params['search_id']);
		 					$amount = 0;
		 					$policy_string ='';	 					
			 					if($value['Charge']==0){
			 						$policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
			 					}else{
			 						if($value['Charge']!=0){
			 							 if(isset($cancel_reverse[$key+1])){
			 							 		if($value['ChargeType']==1){ 					
							 						$amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".round($value['Charge']);							 						
							 					}elseif($value['ChargeType']==2){
							 						$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".$room_price;
							 					}
							 					$current_date = date('Y-m-d');
												$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
												if($cancell_date >$current_date){
													//$value['FromDate'] = date('Y-m-d');
													$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
												}
							 					//$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
							                 
							             }else{
							             	if($value['ChargeType']==1){
							             		$amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".round($value['Charge']);	
							          		}elseif ($value['ChargeType']==2) {
							             		$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)." ".$room_price;
							             	}
							             	$current_date = date('Y-m-d');
											$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
											if($cancell_date > $current_date){
												$value['FromDate'] =$value['FromDate']; 
											}else{
												$value['FromDate'] = date('Y-m-d');
											}
							             	$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
							             }
					 				
				 					}
			 					}		 					
			 				$cancel_string .= $policy_string.'<br/> ';
		 				}
	 				
	 				echo $cancel_string;
					//echo $cancellation_details['GetCancellationPolicy']['policy'][0];
				}else{
					$cancel_string ='';
						$cancellation_policy_details = json_decode(base64_decode($get_params['policy_details']));
						//debug($cancellation_policy_details);
						$cancel_count = count($cancellation_policy_details);					
						$cancellation_policy_details = json_decode(json_encode($cancellation_policy_details), True);
						$cancel_reverse = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details),'Charge');			
						//$cancel_reverse = array_reverse($cancellation_policy_details);
						
						//debug($cancel_reverse);						
						$cancellation_policy_details = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details),'Charge');
						
						if($cancellation_policy_details){
								//$cancellation_policy_details = array_reverse($cancellation_policy_details);
								foreach ($cancellation_policy_details as $key=>$value) {							
										$policy_string ='';								
											if($value['Charge']==0){
												$policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
											}else{
												if(isset($cancel_reverse[$key+1])){
													if($value['ChargeType']==1){
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$value['Charge'];
														
													}elseif ($value['ChargeType']==2) {
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$room_price;
													}
													$current_date = date('Y-m-d');
													$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
													if($cancell_date >$current_date){
														$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
													}
													
												}else{
													if($value['ChargeType']==1){
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$value['Charge'];
														
													}elseif ($value['ChargeType']==2) {
														$amount = $currency_obj->get_currency_symbol($currency_obj->to_currency)."  ".$room_price;
													}
													$current_date = date('Y-m-d');
													$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
													if($cancell_date >$current_date){
														$value['FromDate'] = $value['FromDate'];
													}else{
														$value['FromDate'] = date('Y-m-d');
													}
													$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
												}
											}									
																		
										$cancel_string .= $policy_string.'<br/>';
										
								}
						}else{
							$cancel_string = 'This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
						}
						
					
					echo $cancel_string;
				}
			}else{
				echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
			}
			
		}else{
			echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
		}
		exit;
	}
	/**
	*Load hotels for map
	*/
	function get_all_hotel_list(){
		$response['data'] = '';
		$response['msg'] = '';
		$response['status'] = FAILURE_STATUS;
		$search_params = $this->input->get();		
		$limit = $this->config->item('hotel_per_page_limit');
		if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
			load_hotel_lib($search_params['booking_source']);
			switch($search_params['booking_source']) {
				case PROVAB_HOTEL_BOOKING_SOURCE :
					//getting search params from table
					$safe_search_data = $this->hotel_model->get_safe_search_data($search_params['search_id']);
					//Meaning hotels are loaded first time
					$raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']));
					//debug($raw_hotel_list);exit;
					if ($raw_hotel_list['status']) {
						//Converting API currency data to preferred currency
						$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
						$raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj);
						//Display 
						$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
						//Update currency and filter summary appended
						if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
							$filters = $search_params['filters'];
						} else {
							$filters = array();
						}															
						$attr['search_id'] = abs($search_params['search_id']);			
						$raw_hotel_search_result = array();						
						$i=0;
						$counter=0;
				    	if ($max_lat == 0) {
							$max_lat = $min_lat = 0;
						}

						if ($max_lon == 0) {
							$max_lon = $min_lon = 0;
						}  
						if($raw_hotel_list['data']['HotelSearchResult']){
							foreach ($raw_hotel_list['data']['HotelSearchResult']['HotelResults'] as $key => $value) {
								$raw_hotel_search_result[$i] =$value;
								$raw_hotel_search_result[$i]['MResultToken']=urlencode($value['ResultToken']);
								 $lat = $value['Latitude'];
								 $lon = $value['Longitude'];
								if(($lat!='')&& ($counter<1)){
									$max_lat = $min_lat = $lat;
								}
								if(($lon!='')){
									$counter++;					
									$max_lon = $min_lon = $lon;
								}

								$i++;
							}
							$raw_hotel_list['data']['HotelSearchResult']['max_lat']  = $max_lat;
							$raw_hotel_list['data']['HotelSearchResult']['max_lon']  = $max_lon;
						}
						$raw_hotel_list['data']['HotelSearchResult']['HotelResults'] =$raw_hotel_search_result; 
						//debug($raw_hotel_list);exit;
						$response['data'] =$raw_hotel_list['data'];					
						$response['status'] = SUCCESS_STATUS;
						
					}
					break;
			}
		}
		$this->output_compressed_data($response);
	}
 /**
	 * sagar 
	 * Get All Cities
	 */
 public function get_city_lists()
 {
     $country_id = $this->input->post('country_id');
          $get_resulted_data =  $this->custom_db->single_table_records('api_city_list', '*',array('country' => $country_id), 0, 100000000, array('destination' => 'asc'));
		   if(!empty($get_resulted_data['data'])){ 
		       $html = "<option value=''>Select City</option>";
		        foreach( $get_resulted_data['data'] as  $get_resulted_data_sub){
		  
		         $html= $html."<option value=".$get_resulted_data_sub['origin'].">".$get_resulted_data_sub['destination']."</option>";
		        } 
		    }else{
		         $html = "<option value=''>No City Found</option>";
		    }
		     echo $html;
		     exit;
		 }

function user_traveller_details()
	{
		$term = $this->input->get('term'); //retrieve the search term that autocomplete sends
		$term = trim($term);
		$result = array();
		$this->load->model('user_model');
		$traveller_details = $this->user_model->user_traveller_details($term)->result();
		$travllers_data = array();
		foreach($traveller_details as $traveller){
			$travllers_data['category'] = 'Travellers';
			$travllers_data['id'] = $traveller->origin;
			$travllers_data['label'] = trim($traveller->first_name.' '.$traveller->last_name);
			$travllers_data['value'] = trim($traveller->first_name);
			$travllers_data['first_name'] = trim($traveller->first_name);
			$travllers_data['last_name'] = trim($traveller->last_name);
			$travllers_data['date_of_birth'] = date('Y-m-d', strtotime(trim($traveller->date_of_birth)));
			$travllers_data['email'] = trim($traveller->email);
			$travllers_data['passport_user_name'] = trim($traveller->passport_user_name);
			$travllers_data['passport_nationality'] = trim($traveller->passport_nationality);
			$travllers_data['passport_expiry_day'] = trim($traveller->passport_expiry_day);
			$travllers_data['passport_expiry_month'] = trim($traveller->passport_expiry_month);
			$travllers_data['passport_expiry_year'] = trim($traveller->passport_expiry_year);
			$travllers_data['passport_number'] = trim($traveller->passport_number);
			$travllers_data['passport_issuing_country'] = trim($traveller->passport_issuing_country);
			array_push($result,$travllers_data);
		}
		$this->output_compressed_data($result);
	}


	function generatePaymentLink()
	{
		$postData = $this->input->post();
		$system_transaction_id = 'DEP-'.$this->entity_user_id.time();
		$data['transaction_type'] = 'ONLINE';
		$data['amount'] = $postData['amount'];
		$data['system_transaction_id'] = $system_transaction_id;
		$currency_obj = new Currency ();
		$currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
		$data['conversion_value'] = $currency_conversion_rate;
	    $data['currency'] = agent_base_currency();	
		$data['remarks'] = 'Balance request from Booking page';	
		$data['date_of_transaction'] = date('Y-m-d');
		// $data['amount']  = $postData['amount']*$currency_obj->currency_conversion_value(false, agent_base_currency(), admin_base_currency());
		$data['topup_marchantfee'] = $this->custom_db->single_table_records('convenience_fees', 'value', array('module' => 'topup'))['data'][0]['value'];
	
		$this->load->model ( 'domain_management_model' );
		$insert_id = $this->domain_management_model->save_master_transaction_details ( $data );

		$res =  $this->xenditPaymentGenerate($system_transaction_id);
		 header('Content-Type: application/json');
		echo $res; 
	}

	public function xenditPaymentGenerate($book_id)
	{
		$this->load->model('transaction');
		$PG = $this->config->item('active_payment_gateway');
		load_pg_lib($PG);
		$pg_record = $this->transaction->read_payment_record($book_id);
		
		// $currency_objd = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
		// $display_curr_symbl = $currency_objd->get_currency_symbol($pg_record['currency']);
		$display_amount = $pg_record['amount'];
		$pg_record['amount'] = roundoff_number((($display_amount * $pg_record['currency_conversion_rate'])), 2);
		// $pg_record['amount'] = roundoff_number($display_amount, 2);
		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			$params = json_decode($pg_record['request_params'], true);
			$encryptedCode = $this->generateEncryptedRandomCode(8, $params['txnid']);
			$pg_initialize_data = array(
				// 'invoice_no' => $book_origin,
				'external_id' => $params['txnid'],
				'amount' => $pg_record['amount'],
				// 'firstname' => $params['firstname'],
				'payer_email' => $params['email'],
				'currency' => 'PHP',
				// 'phone' => $params['phone'],
				'description' => $params['productinfo'],
				'failure_redirect_url' => base_url() . 'payment_gateway/cancel/'.$encryptedCode,
				'success_redirect_url' => base_url() . 'payment_gateway/success/'.$encryptedCode
			);
		} else {
			echo 'Please check payment gateway empty records';
			exit;
		}
		//defined in provab_config.php
		$payment_gateway_status = $this->config->item('enable_payment_gateway');
		if ($payment_gateway_status == true) {
			$this->transaction->update_payment_record_status_req($params['txnid'], $encryptedCode, $pg_initialize_data);
			$init = $this->pg->initialize();
			$apiKey = $init['apikey'];
			header('Content-Type: application/json');
			$response = [];
			try {
				Xendit::setApiKey($apiKey);
				$response = \Xendit\Invoice::create($pg_initialize_data);
				$this->transaction->update_payment_record_status_res($params['txnid'], $response);
			} catch (\Exception $e) {
				http_response_code($e->getCode());
				$response['message'] = $e->getMessage();
			}
			return json_encode($response);
			// header("Location: ".$response['invoice_url']); 
			// exit;
		} else {
			/*$moduleList = array(
				META_AIRLINE_COURSE => 'flight',
				META_ACCOMODATION_COURSE => 'hotel',
				META_TRANSFERV1_COURSE=>'transferv1',
				META_CAR_COURSE=>'car',
				META_SIGHTSEEING_COURSE=>'sightseeing',
				META_PACKAGE_COURSE => 'tours',
				);
			$module = $moduleList[$params['productinfo']];
			$msg = "Invalid top up request";
			redirect(base_url() .$module.'/exception?notification=' . $msg);
			*/
			return "Invalid top up request";

		}
	}

	function generateEncryptedRandomCode($length = 16, $encryptionKey = 'your-secret-key') {
			// Step 1: Generate secure random bytes
			$randomBytes = random_bytes($length);
			$randomString = bin2hex($randomBytes);

			// Step 2: Encryption setup
			$cipher = "AES-256-CBC";
			$key = hash('sha256', $encryptionKey, true); // 32 bytes key
			$iv = substr(hash('sha256', 'your-iv-key'), 0, 16); // 16 bytes IV

			// Step 3: Encrypt the random string
			$encrypted = openssl_encrypt($randomString, $cipher, $key, OPENSSL_RAW_DATA, $iv);

			// Step 4: Encode as URL-safe base64 (no / or +)
			$base64 = base64_encode($encrypted);
			$safe = rtrim(strtr($base64, '+/', '-_'), '=');

			return $safe;
	}

	function sendlink($app, $email) {
		error_reporting(E_ALL);
	ini_set('display_errors', 1);
		$this->load->library('provab_mailer');
		$this->load->model('transaction');
		$topup_marchantfee =$this->custom_db->single_table_records('master_transaction_details', 'topup_marchantfee', array('system_transaction_id' => $app))['data'][0]['topup_marchantfee'];
		$pg_record = $this->transaction->read_payment_record($app);
		$invoice_params = json_decode($pg_record['invoice_params'], true);
		$request_params = json_decode($pg_record['request_params'], true);
		$page_data['amount'] = ($pg_record['amount'] - $topup_marchantfee )* $pg_record['currency_conversion_rate'];
		$page_data['topup_marchantfee'] = $topup_marchantfee * $pg_record['currency_conversion_rate'];
		$page_data['totalamount'] = $invoice_params['amount'];;
		$page_data['link'] = $invoice_params['invoice_url'];
		$page_data['currency'] = $invoice_params['currency'];
		$page_data['created'] = month_date_year_time($invoice_params['created']);
		$page_data['merchant_name'] = $invoice_params['merchant_name'];
		$page_data['app_refrence'] = $app;
		$page_data['username'] = $request_params['firstname'];

		$mail_template = $this->template->isolated_view('management/paymentlinkmail', $page_data);
		$email_subject = 'Top-up Payment Link';
		$response = $this->provab_mailer->send_mail(trim($email), $email_subject, $mail_template);
		debug($response);
		echo TRUE;
	}

}

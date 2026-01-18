<?php
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-03-13
* @package: 
* @Description: 
* @version: 2.0
**/
Class Hotel_Model extends CI_Model
{
	private $master_search_data;
	/**
	 * return top destinations in hotel
	 */
	function hotel_top_destinations_old()
	{
		$query = 'Select CT.*, CN.country_name AS country from all_api_city_master CT, api_country_master CN where CT.country_code=CN.iso_country_code AND top_destination = '.ACTIVE;
		$data = $this->db->query($query)->result_array();
		return $data;
	}
	function hotel_top_destinations($categorys = array(),$domain=1){
		// debug( $categorys ); 
		// exit;
		$this->db->select('*');
		$this->db->from('hotel_top_destinations');
		$this->db->where('domain',$domain);
		$this->db->where('status','1');
		$query = $this->db->get();
		return $query->result_array();
		
	}
	/*
	 *
	 * Get Airport List
	 *
	 */
	 function get_hotel_list($search_chars){
		$raw_search_chars = $this->db->escape($search_chars);
		if(empty($search_chars)==false){
			$r_search_chars = $this->db->escape($search_chars.'%');
			$search_chars = $this->db->escape($search_chars.'%');
		}else{
			$r_search_chars = $this->db->escape($search_chars);
			$search_chars = $this->db->escape($search_chars);
		}
		$query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code,hb_master_data.hotel_name,cm.tbo_city_id from all_api_city_master as cm inner join hb_master_data on hb_master_data.city_code=cm.hb_city_id  
where   hotel_name LIKE '.$search_chars.'
				ORDER BY cm.cache_hotels_count desc, CASE
			WHEN	hb_master_data.hotel_name	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	hb_master_data.hotel_name	LIKE	'.$r_search_chars.'	THEN 2	
			WHEN	hb_master_data.hotel_name	LIKE	'.$search_chars.'	THEN 3
			ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 10
		';
	return $this->db->query($query)->result_array();
	}
	function get_hotel_city_list($search_chars)
	{
		$raw_search_chars = $this->db->escape($search_chars);
		if(empty($search_chars)==false){
			$r_search_chars = $this->db->escape($search_chars.'%');
			$search_chars = $this->db->escape($search_chars.'%');
		}else{
			$r_search_chars = $this->db->escape($search_chars);
			$search_chars = $this->db->escape($search_chars);
		}

		$query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.cityid as tbo_city_id from tbo_city_list as cm where  cm.city_name like '.$search_chars.' 
				ORDER BY cm.cache_hotels_count desc, CASE
			WHEN	cm.city_name	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	cm.city_name	LIKE	'.$r_search_chars.'	THEN 2	
			WHEN	cm.city_name	LIKE	'.$search_chars.'	THEN 3
			ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 30
		';
		
		/*$query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.tbo_city_id from all_api_city_master as cm where  cm.city_name like '.$search_chars.' 
				ORDER BY cm.cache_hotels_count desc, CASE
			WHEN	cm.city_name	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	cm.city_name	LIKE	'.$r_search_chars.'	THEN 2	
			WHEN	cm.city_name	LIKE	'.$search_chars.'	THEN 3
			ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 30
		';*/


		// $query = 'Select cm.country as country_name,cm.Destination as city_name,cm.origin,cm.countrycode as country_code,cm.cityid as tbo_city_id from tbo_city_list as cm where  cm.Destination like '.$search_chars.' 
		// 		ORDER BY cm.cache_hotels_count desc, CASE
		// 	WHEN	cm.Destination	LIKE	'.$raw_search_chars.'	THEN 1
		// 	WHEN	cm.Destination	LIKE	'.$r_search_chars.'	THEN 2	
		// 	WHEN	cm.Destination	LIKE	'.$search_chars.'	THEN 3
		// 	ELSE 4 END, cm.cache_hotels_count asc LIMIT 0, 30
		// ';

		// echo $query;exit;
		//Select cm.country_name,cm.city_name,cm.origin,cm.country_code from all_api_city_master as cm where  cm.city_name like '.$search_chars.' 
				//ORDER BY cm.origin ASC, CASE
		//echo $query;exit;
		return $this->db->query($query)->result_array();
	}
	function get_hotel_city_list_base($search_chars)
	{
		$raw_search_chars = $this->db->escape($search_chars);
		$r_search_chars = $this->db->escape($search_chars.'%');
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		$query = 'Select * from all_api_city_master where city_name like '.$search_chars.'
		OR country_name like '.$search_chars.' OR country_code like '.$search_chars.'
		ORDER BY top_destination DESC, CASE
			WHEN	city_name	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	country_name	LIKE	'.$raw_search_chars.'	THEN 2
			WHEN	country_code			LIKE	'.$raw_search_chars.'	THEN 3
			
			WHEN	city_name	LIKE	'.$r_search_chars.'	THEN 4
			WHEN	country_name	LIKE	'.$r_search_chars.'	THEN 5
			WHEN	country_code			LIKE	'.$r_search_chars.'	THEN 6
			
			WHEN	city_name	LIKE	'.$search_chars.'	THEN 7
			WHEN	country_name	LIKE	'.$search_chars.'	THEN 8
			WHEN	country_code			LIKE	'.$search_chars.'	THEN 9
			ELSE 10 END, 
			cache_hotels_count DESC
		LIMIT 0, 20';
		return $this->db->query($query)->result_array();
	}
	/**
	 * get all the booking source which are active for current domain
	 */
	function active_booking_source()
	{
		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape(META_ACCOMODATION_COURSE).'
		and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
		return $this->db->query($query)->result_array();
	}

	/*********** for booking count in dashboard****/

  function booking_count($condition=array(), $count=false, $offset=0, $limit=100000000000)

	{

		$condition = $this->custom_db->get_custom_condition($condition);

		

		if(isset($condition) == true)

		{

			$offset = 0;

		}else{

			$offset = $offset;

		}

		$status = "BOOKING_INPROGRESS";

		//BT, CD, ID

		if ($count) {

			 $query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' and '.'BD.status !="'.$status.'"';



			$data = $this->db->query($query)->row_array();

			return $data['total_records'];
	}
}
	
	/**
	 * return booking list
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select * from hotel_booking_details AS BD 
						WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition.'
						order by BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
	function booking_guest_user($app_reference, $booking_source, $booking_status){
		$response['status'] = SUCCESS_STATUS;
		$response['data'] = array();
		$booking_itinerary_details	= array();
		$booking_customer_details	= array();
		$cancellation_details = array();
		$bd_query = 'select * from hotel_booking_details AS BD WHERE (BD.app_reference like '.$this->db->escape($app_reference).' || BD.booking_id like '.$this->db->escape($app_reference).')';
		if (empty($booking_source) == false) {
			$bd_query .= ' AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		$booking_details = $this->db->query($bd_query)->result_array();
		$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
		if(empty($app_reference_ids) == false) {
			$id_query = 'select * from hotel_booking_itinerary_details AS ID 
						WHERE ID.app_reference IN ('.$app_reference_ids.')';
			$cd_query = 'select * from hotel_booking_pax_details AS CD 
						WHERE CD.app_reference IN ('.$app_reference_ids.')';
			$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
						WHERE HCD.app_reference IN ('.$app_reference_ids.')';
			$booking_itinerary_details	= $this->db->query($id_query)->result_array();
			$booking_customer_details	= $this->db->query($cd_query)->result_array();
			$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
		}
		$response['data']['booking_details']			= $booking_details;
		$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
		$response['data']['booking_customer_details']	= $booking_customer_details;
		$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		return $response;
	}
	/**
	 * Return Booking Details based on the app_reference passed
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	function get_booking_details($app_reference, $booking_source='', $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select * from hotel_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		$id_query = 'select * from hotel_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		$cd_query = 'select * from hotel_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		$cancellation_details_query = 'select HCD.* from hotel_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}
	/**
	 * get search data and validate it
	 */
	public function get_safe_search_data($search_id)
	{
		$search_data = $this->get_search_data($search_id);

		//debug($search_data);exit;

		$success = true;
		$clean_search = '';
		if ($search_data != false) {
			//validate
			
			$temp_search_data = json_decode($search_data['search_data'], true);
			$clean_search = $this->clean_search_data($temp_search_data);
			$success = $clean_search['status'];
			$clean_search = $clean_search['data'];
		} else {
			$success = false;
		}

		//debug($clean_search);exit;	

		return array('status' => $success, 'data' => $clean_search);
	}
	/**
	 * Clean up search data
	 */
	function clean_search_data($temp_search_data)
	{
		$success = true;
		// echo "dszfds";
		// //make sure dates are correct
		// debug($temp_search_data);exit;
		//debug($temp_search_data);
		if ((strtotime($temp_search_data['hotel_checkin']) > time() && strtotime($temp_search_data['hotel_checkout']) > time()) || date('Y-m-d', strtotime($temp_search_data['hotel_checkin'])) == date('Y-m-d')) {
			//	if (strtotime($temp_search_data['hotel_checkin']) > strtotime($temp_search_data['hotel_checkout'])) {
			//Swap dates if not correctly set
			$clean_search['from_date'] = $temp_search_data['hotel_checkin'];
			$clean_search['to_date'] = $temp_search_data['hotel_checkout'];
			/*} else {
			 $clean_search['from_date'] = $temp_search_data['hotel_checkout'];
			 $clean_search['to_date'] = $temp_search_data['hotel_checkin'];
			 }*/
			$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));
		} else {
			$success = false;
		}
		//city name and country name
		
		if (isset($temp_search_data['hotel_destination']) == true) {
			$clean_search['hotel_destination'] = $temp_search_data['hotel_destination'];
		}
		if (isset($temp_search_data['city']) == true) {
			$clean_search['location'] = $temp_search_data['city'];
			$temp_location = explode('(', $temp_search_data['city']);
			$clean_search['city_name'] = trim($temp_location[0]);
			if (isset($temp_location[1]) == true) {
				//Pop will get last element in the array since element patterns can repeat
				$clean_search['country_name'] = trim(array_pop($temp_location), '() ');
			} else {
				$clean_search['country_name'] = '';
			}
		} else {
			$success = false;
		}
		//Occupancy
		if (isset($temp_search_data['rooms']) == true) {
			$clean_search['room_count'] = abs($temp_search_data['rooms']);
		} else {
			$success = false;
		}
		if (isset($temp_search_data['adult']) == true) {
			$clean_search['adult_config'] = $temp_search_data['adult'];
		} else {
			$success = false;
		}
		if (isset($temp_search_data['child']) == true) {
			$clean_search['child_config'] = $temp_search_data['child'];
		}


		if (isset($temp_search_data['hotel_destination_location']) == true) {
			$clean_search['hotel_destination_location'] = $temp_search_data['hotel_destination_location'];
		}



		if (valid_array($temp_search_data['child'])) {
			foreach ($temp_search_data['child'] as $tc_k => $tc_v) {
				if (intval($tc_v) > 0) {
					$child_age_index = $tc_v;
					//echo $child_age_index.'<br/>';
					if($child_age_index>1){
						foreach($temp_search_data['childAge_'.($tc_k+1)] as $ic_k => $ic_v) {
							$clean_search['child_age'][] = $ic_v;
						}
					}else{
						$clean_search['child_age'][] = $temp_search_data['childAge_'.($tc_k+1)][0];
					}
					
				}
			}
		}
		if (strtolower($clean_search['country_name']) == 'india') {
			$clean_search['is_domestic'] = true;
		} else {
			$clean_search['is_domestic'] = false;
		}
		// debug($temp_search_data);
		if($temp_search_data['search_type'] == 'location_search'){
			$clean_search['location'] = $temp_search_data['location'];
			$clean_search['latitude'] = $temp_search_data['latitude'];
			$clean_search['longitude'] = $temp_search_data['longitude'];
			$clean_search['radius'] = $temp_search_data['radius'];
			$clean_search['countrycode'] = $temp_search_data['countrycode'];
		}
		$clean_search['search_type'] = $temp_search_data['search_type'];
		// echo "------";
		// debug($clean_search);exit;
		
		return array('data' => $clean_search, 'status' => $success);
	}
	/**
	 * get search data without doing any validation
	 * @param $search_id
	 */
	function get_search_data($search_id)
	{
		if (empty($this->master_search_data)) {
			$search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_ACCOMODATION_COURSE, 'origin' => $search_id));
			// debug($search_data);exit;
			if ($search_data['status'] == true) {
				$this->master_search_data = $search_data['data'][0];
			} else {
				return false;
			}
		}
		return $this->master_search_data;
	}
	/**
	 * get hotel city id of tbo from tbo hotel city list
	 * @param string $city	  city name for which id has to be searched
	 * @param string $country country name in which the city is present
	 */
		function tbo_hotel_city_id($city, $country)
{
    $response = ['status' => false, 'data' => []];
    // Sanitize and trim input parameters
    $city = trim($city);
    $country = trim($country);
    // Escape special characters for LIKE query to prevent SQL injection
    $escaped_city = $this->custom_db->escape_like_str($city);
    $escaped_country = $this->custom_db->escape_like_str($country);
    // Build the query conditions with wildcards for partial matching
    $conditions = [
        'city_name LIKE' => '%' . $escaped_city . '%',
        'country_name LIKE' => '%' . $escaped_country . '%'
    ];
    // Specify the columns to select
    $columns = 'country_code, origin';
    // Execute the query using parameter binding
    $location_details = $this->custom_db->single_table_records('all_api_city_master', $columns, $conditions);
    // Check if data is retrieved successfully
    if ($location_details['status'] && !empty($location_details['data'])) {
        $response['status'] = true;
        $response['data'] = $location_details['data'][0];
    }
    return $response;
}
	/**
	 *
	 * @param number $domain_origin
	 * @param string $status
	 * @param string $app_reference
	 * @param string $booking_source
	 * @param string $booking_id
	 * @param string $booking_reference
	 * @param string $confirmation_reference
	 * @param number $total_fare
	 * @param number $domain_markup
	 * @param number $level_one_markup
	 * @param string $currency
	 * @param string $hotel_name
	 * @param number $star_rating
	 * @param string $hotel_code
	 * @param number $phone_number
	 * @param string $alternate_number
	 * @param string $email
	 * @param string $payment_mode
	 * @param string $attributes
	 * @param number $created_by_id
	 */
	function save_booking_details($domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email, $hotel_check_in, $hotel_check_out, $payment_mode, $attributes, $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code,$total_fare_markup ,$total_fare_tax,$agentServicefee=0,$total_destination_charge=0,$total_discount_amount=0) {
		//debug($total_destination_charge); exit;
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['booking_id'] = $booking_id;
		$data['booking_reference'] = $booking_reference;
		$data['confirmation_reference'] = $confirmation_reference;
		$data['hotel_name'] = $hotel_name;
		$data['star_rating'] = $star_rating;
		$data['hotel_code'] = $hotel_code;
		$data['phone_number'] = $phone_number;
		$data['phone_code'] = $phone_code;
		$data['alternate_number'] = $alternate_number;
		$data['email'] = $email;
		$data['hotel_check_in'] = $hotel_check_in;
		$data['hotel_check_out'] = $hotel_check_out;
		$data['payment_mode'] = $payment_mode;
		$data['attributes'] = $attributes;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['currency'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;
		$data['total_fare_markup'] = $total_fare_markup;
		$data['total_fare_tax'] = $total_fare_tax;
		$data['total_destination_charge'] = $total_destination_charge;
		$data['discount'] = $total_discount_amount;
		 //debug($data);exit;
		$status = $this->custom_db->insert_record('hotel_booking_details', $data);
		return $status;
	}
	/**
	 *
	 * @param string $app_reference
	 * @param string $location
	 * @param date	 $check_in
	 * @param date	 $check_out
	 * @param string $room_type_name
	 * @param string $bed_type_code
	 * @param string $status
	 * @param string $smoking_preference
	 * @param string $attributes
	 */
	function save_booking_itinerary_details($app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code,
	$status, $smoking_preference, $total_fare, $admin_markup, $agent_markup, $currency, $attributes,
	$RoomPrice, $Tax, $ExtraGuestCharge, $ChildCharge, $OtherCharges,
	$Discount, $ServiceTax, $AgentCommission, $AgentMarkUp, $TDS, $gst )
	{
		$data['app_reference'] = $app_reference;
		$data['location'] = $location;
		$data['check_in'] = $check_in;
		$data['check_out'] = $check_out;
		$data['room_type_name'] = $room_type_name;
		$data['bed_type_code'] = $bed_type_code;
		//$data['rateKey'] = $bed_rate_key;
		$data['status'] = $status;
		$data['smoking_preference'] = $smoking_preference;
		$data['total_fare'] = $total_fare;
		$data['admin_markup'] = $admin_markup;
		$data['agent_markup'] = $agent_markup;
		$data['currency'] = $currency;
		$data['attributes'] = $attributes;
		$data['RoomPrice'] = floatval($RoomPrice);
		$data['Tax'] = floatval($Tax);
		$data['ExtraGuestCharge'] = floatval($ExtraGuestCharge);
		$data['ChildCharge'] = floatval($ChildCharge);
		$data['OtherCharges'] = floatval($OtherCharges);
		$data['Discount'] = floatval($Discount);
		$data['ServiceTax'] = floatval($ServiceTax);
		$data['AgentCommission'] = floatval($AgentCommission);
		$data['AgentMarkUp'] = floatval($AgentMarkUp);
		$data['TDS'] = floatval($TDS);
		//adding gst
		$data['gst'] = $gst;
		
		
		//debug($data);exit;
		$status = $this->custom_db->insert_record('hotel_booking_itinerary_details', $data);
		return $status;
	}
	/**
	 *
	 * @param $app_reference
	 * @param $title
	 * @param $first_name
	 * @param $middle_name
	 * @param $last_name
	 * @param $phone
	 * @param $email
	 * @param $pax_type
	 * @param $date_of_birth
	 * @param $passenger_nationality
	 * @param $passport_number
	 * @param $passport_issuing_country
	 * @param $passport_expiry_date
	 * @param $status
	 * @param $attributes
	 */
	function save_booking_pax_details($app_reference, $title, $first_name, $middle_name, $last_name,$phone, $email, $pax_type, $date_of_birth,
	$passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, $attributes)
	{
		//echo $date_of_birth;
		$data['app_reference'] = $app_reference;
		$data['title'] = $title;
		$data['first_name'] = $first_name;
		$data['middle_name'] = (empty($middle_name) == true ?  $last_name: $middle_name);
		$data['last_name'] = $last_name;
		//$data['age'] = $age;
		$data['phone'] = $phone;
		$data['email'] = $email;
		$data['pax_type'] = $pax_type;
		$data['date_of_birth'] = $date_of_birth;
		$data['passenger_nationality'] = $passenger_nationality;
		$data['passport_number'] = $passport_number;
		$data['passport_issuing_country'] = $passport_issuing_country;
		$data['passport_expiry_date'] = $passport_expiry_date;
		$data['status'] = $status;
		$data['attributes'] = $attributes;
		
		$status = $this->custom_db->insert_record('hotel_booking_pax_details', $data);
		return $status;
	}
	/**
	 *
	 */
	function get_static_response($token_id)
	{
		$static_response = $this->custom_db->single_table_records('test', '*', array('origin' => intval($token_id)));
		return json_decode($static_response['data'][0]['test'], true);
	}
	/**
	 * SAve search data for future use - Analytics
	 * @param array $params
	 */
	 function save_search_data($search_data, $type)
	 {   
		 //print_r($search_data); exit;
 
		 $data['domain_origin'] = get_domain_auth_id();
 
		 $data['search_type'] = $type;
 
		 $data['created_by_id'] = intval(@$this->entity_user_id);
 
		 $data['created_datetime'] = date('Y-m-d H:i:s');
 
 
 
		 $temp_location = explode('(', $search_data['city']);
 
		 
		 $data['city'] = trim($temp_location[0]);
 
		 if (isset($temp_location[1]) == true) {
 
			 $country_hotel = explode(')', $temp_location[1]);
 
			if(count($country_hotel)>0){
			 $data['country'] = trim($country_hotel[0], '() ');
			}else{
			 $data['country'] = '';
			}
			 
 
		 } else {
 
			 $data['country'] = '';
 
		 }
 
		 $data['check_in'] = date('Y-m-d', strtotime($search_data['hotel_checkin']));
 
		 $data['nights'] = abs(get_date_difference($search_data['hotel_checkin'], $search_data['hotel_checkout']));
 
		 $data['rooms'] = $search_data['rooms'];
 
		 $data['total_pax'] = array_sum($search_data['adult']) + array_sum($search_data['child']);
 
		 $this->custom_db->insert_record('search_hotel_history', $data);
 
	 }
	/**
	 * Balu A
	 * Update Cancellation details and Status
	 * @param $AppReference
	 * @param $cancellation_details
	 */
	public function update_cancellation_details($AppReference, $cancellation_details)
	{
		$AppReference = trim($AppReference);
		$booking_status = 'BOOKING_CANCELLED';
		//1. Add Cancellation details
		$this->update_cancellation_refund_details($AppReference, $cancellation_details);
		//2. Update Master Booking Status
		$this->custom_db->update_record('hotel_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
		//3.Update Itinerary Status
		$this->custom_db->update_record('hotel_booking_itinerary_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
	}
	/**
	 * Add Cancellation details
	 * @param unknown_type $AppReference
	 * @param unknown_type $cancellation_details
	 */
	private function update_cancellation_refund_details($AppReference, $cancellation_details)
	{
		$hotel_cancellation_details = array();
		$hotel_cancellation_details['app_reference'] = 				$AppReference;
		$hotel_cancellation_details['ChangeRequestId'] = 			$cancellation_details['ChangeRequestId'];
		$hotel_cancellation_details['ChangeRequestStatus'] = 		$cancellation_details['ChangeRequestStatus'];
		$hotel_cancellation_details['status_description'] = 		$cancellation_details['StatusDescription'];
		$hotel_cancellation_details['API_RefundedAmount'] = 		@$cancellation_details['RefundedAmount'];
		$hotel_cancellation_details['API_CancellationCharge'] = 	@$cancellation_details['CancellationCharge'];
		
		$hotel_cancellation_details['refund_date'] = 				@$cancellation_details['refund_date'];
		if($cancellation_details['ChangeRequestStatus'] == 3){
			$hotel_cancellation_details['cancellation_processed_on'] =	date('Y-m-d H:i:s');
		}
		$cancel_details_exists = $this->custom_db->single_table_records('hotel_cancellation_details', '*', array('app_reference' => $AppReference));
		if($cancel_details_exists['status'] == true) {
			//Update the Data
			unset($hotel_cancellation_details['app_reference']);
			$this->custom_db->update_record('hotel_cancellation_details', $hotel_cancellation_details, array('app_reference' => $AppReference));
		} else {
			//Insert Data
			$hotel_cancellation_details['created_by_id'] = 				(int)@$this->entity_user_id;
			$hotel_cancellation_details['created_datetime'] = 			date('Y-m-d H:i:s');
			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
			$this->custom_db->insert_record('hotel_cancellation_details',$hotel_cancellation_details);
		}
	}
	/**
	*Image masking
	*/
	function setImgDownload($imagePath){
		$image = imagecreatefromjpeg($imagePath);
	    header('Content-Type: image/jpeg');
	    imagejpeg($image);
	}
    function add_hotel_images($sid,$HotelPicture,$HotelCode) {
         
        $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('hotel_code'=>$HotelCode));            
     
        if($image_url['status']==0) {
            foreach($HotelPicture as $key=>$value) {
			$data['image_url'] = $value;
			$data['ResultIndex'] = $key;
	                $data['hotel_code'] = $HotelCode;
			$this->custom_db->insert_record('hotel_image_url', $data);
            }
        }
    }
	function getCategoryList() {
		$this->db->select('city_name');
		$this->db->from('hotel_top_destinations');
		$this->db->where('status','1');
		$this->db->group_by('city_name');
		$query = $this->db->get();
		return  $query->result_array();
		
	}
    public function get_property_ids($city_id){
    	$result = $this->db->select('hotel_id')->get_where('hg_hotels_list',array('city_origin'=>$city_id,'is_market_place'=>1))->result_array();
    	$return = array();
    	if($result){
    		$ids = array_column($result, 'hotel_id');
			$return = $ids;
    		// if($ids){
    		// 	$return = implode(',',$ids);
    		// }
    	}
    	return $return;
    }
	public function get_dynamic_hotel_url_by_city($city_name) {
        // Fetch data from dynamic_hotel_urls table where the city matches
        return $this->db->get_where('dynamic_hotel_urls', array('city' => $city_name))->row_array();
    }
}
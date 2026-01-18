<?php
require_once BASEPATH . 'libraries/hotel/Common_api_hotel_v3.php';
class Common_Hotel_v3 {
	/**
	 * Url to be used for combined hotel booking - only for domestic round way
	 *
	 * @param number $search_id        	
	 */
	static function combined_booking_url($search_id) {
		return Common_Api_Hotel::pre_booking_url ( $search_id );
	}
	
	/**
	 * Data gets saved in list so remember to use correct source value
	 *
	 * @param string $source
	 *        	source of the data - will be used as key while saving
	 * @param string $value
	 *        	value which has to be cached - pass json
	 */
	static function insert_record($key, $value) {
		$ci = & get_instance ();
		
		$index = $ci->redis_server->store_list ( $key, $value );
		return array (
				'access_key' => $key . DB_SAFE_SEPARATOR . $index . DB_SAFE_SEPARATOR . random_string () . random_string (),
				'index' => $index 
		);
	}
	
	/**
	 */
	static function read_record($key, $offset = -1, $limit = -1) 
	{
		$ci = & get_instance ();
		return $ci->redis_server->read_list ( $key, $offset, $limit );
	}
	
	/**
	 * Cache the data
	 *
	 * @param string $key        	
	 * @param value $value        	
	 * @return array[]
	 */
	static function insert_string($key, $value) {
		$ci = & get_instance ();
		$ci->redis_server->store_string ( $key, $value );
	}
	
	/**
	 * read data from cache
	 *
	 * @param string $key        	
	 * @param number $offset        	
	 * @param number $limit        	
	 */
	static function read_string($key) {
		$ci = & get_instance ();
		return $ci->redis_server->read_string ( $key );
	}
	/*
	 * save hotel details
	 */
	function save_hotel_booking($hotel_data, $passenger_details, $app_reference, $booking_source, $search_id)
	{
		$ci = & get_instance();
		$data['status'] = SUCCESS_STATUS;
		$data['message'] = array();
		// $porceed_to_save = $this->is_duplicate_hotel_booking($app_reference);
		$porceed_to_save['status'] = SUCCESS_STATUS;

		if($porceed_to_save['status'] != SUCCESS_STATUS){
			$data['status'] = $porceed_to_save['status'];
			$data['message'] = $porceed_to_save['message'];
		} else {
			$search_data = $ci->hotel_model_v3->get_safe_search_data_grn ( $search_id );
			$search_data = $search_data['data'];
			$fare_details = $hotel_data['Price'];
			$room_details = $hotel_data['RoomPriceBreakup'];
			$master_booking_status = 'BOOKING_INPROGRESS';
			
			
			//Save to Master table
			$domain_origin = get_domain_auth_id();
			$hotel_booking_status = $master_booking_status;
			$currency = domain_base_currency();
			$currency_obj = new Currency(array('module_type' => 'b2c_hotel'));
			$currency_conversion_rate = $currency_obj->get_domain_currency_conversion_rate();
			
			//Price Details
			$book_total_fare = $fare_details['total_fare'];
			$book_domain_markup = $fare_details['admin_markup'];
			$book_level_one_markup = 0;
			
			$book_gst_price = $fare_details['gst'];
			$book_hotel_markup_price = $fare_details['hotel_markup_price'];
			$book_markup_gst = $fare_details['admin_markup_gst'];

			//Hotel Details
			$hotel_name = $hotel_data['ResultToken']['HotelName'];
			$star_rating = empty($hotel_data['ResultToken']['StarRating']) ? 0 : $hotel_data['ResultToken']['StarRating'];


			$hotel_code = $hotel_data['ResultToken']['HotelCode'];
			$phone_number = $passenger_details[0]['PassengerDetails'][0]['Phoneno'];
			$alternate_number = '';
			$email = $passenger_details[0]['PassengerDetails'][0]['Email'];
			$hotel_check_in = date('Y-m-d', strtotime($search_data['from_date']));
			$hotel_check_out = date('Y-m-d', strtotime($search_data['to_date']));
			$attributes = '';
			
			$booking_id = '';
			$booking_reference = '';
			$confirmation_reference = '';
			
			$payment_mode = 'PNHB1';
			$created_by_id = 0;


			
			
			//Maaster Hotel Table
			$ci->hotel_model_v3->save_booking_details ( $domain_origin, $hotel_booking_status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $book_total_fare, $book_domain_markup, $book_level_one_markup, $currency, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email, $hotel_check_in, $hotel_check_out, $payment_mode, $attributes, $created_by_id,$currency_conversion_rate, HOTEL_VERSION_2,$book_gst_price,$book_hotel_markup_price,$book_markup_gst);
			
			//Hotel Itinerary Details
			
			foreach ($room_details as $room_k => $room_v){
				$location = $search_data['city_name'];
				$room_type_name = $room_v['RoomTypeName'];
				$bed_type_code = $room_v['RoomTypeCode'];
				$status = $master_booking_status;
				$smoking_preference = $room_v['SmokingPreference'];
				$total_fare = $room_v['Price']['PublishedPriceRoundedOff'];
				$domain_markup = ($fare_details['admin_markup'])/$search_data['room_count'];
				$level_one_markup = 0;
				$RoomPrice = $room_v['Price']['RoomPrice'];
				$Tax = $room_v['Price']['Tax'];
				$ExtraGuestCharge = $room_v['Price']['ExtraGuestCharge'];
				$ChildCharge = $room_v['Price']['ChildCharge'];
				$OtherCharges = $room_v['Price']['OtherCharges'];
				$Discount = $room_v['Price']['Discount'];
				$ServiceTax = $room_v['Price']['ServiceTax'];
				$AgentCommission = $room_v['Price']['AgentCommission'];
				$AgentMarkUp = $room_v['Price']['AgentMarkUp'];
				$TDS = $room_v['Price']['TDS'];
				$attributes = '';
				$ci->hotel_model_v3->save_booking_itinerary_details($app_reference, $location, $hotel_check_in, $hotel_check_out, $room_type_name, $bed_type_code, $status, $smoking_preference, $total_fare, $domain_markup, $level_one_markup, $currency, serialize ( $attributes ), $RoomPrice, $Tax, $ExtraGuestCharge, $ChildCharge, $OtherCharges, $Discount, $ServiceTax, $AgentCommission, $AgentMarkUp, $TDS );
			}
			// debug($passenger_details);exit;
			//Save Passenger Details
			foreach($passenger_details as $pax_k => $pax_v) {
				foreach ($pax_v['PassengerDetails'] as $passenger_k => $passenger_v){
					$title = $passenger_v['Title'];
					$first_name = $passenger_v['FirstName'];
					$middle_name = empty($passenger_v['MiddleName']) == true ? $passenger_v['LastName'] : $passenger_v['MiddleName'];
					$last_name = $passenger_v['LastName'];
					$phone = $passenger_v['Phoneno'];
					$pax_type = $passenger_v['PaxType'];
					if ($pax_type == "1") {
						$pax_type = "Adult";
					} else {
						$pax_type = "Child";
					}
					$date_of_birth = '2016-01-15'; // No Value for date of birth value are for age
					$passenger_nationality = 'India'; // Static Value
					$passport_number = '959595959'; // Static Value
					$passport_issuing_country = 'India'; // Static Value
					$passport_expiry_date = '2016-01-15'; // Static Value
					
					$ci->hotel_model_v3->save_booking_pax_details ( $app_reference, $title, $first_name, $middle_name, $last_name, $phone, $email, $pax_type, $date_of_birth, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, serialize ( $attributes ) );
				}
			}
		}

		// debug($data);die;
		return $data;
	}
	/**
	 * Get Booked Hotel Details
	 * @param unknown_type $app_reference
	 */
	public function get_hotel_booking_transaction_details($app_reference)
	{
		$ci = & get_instance();
		$data['status'] = FAILURE_STATUS;
		$data['data'] = array();
		$data['message'] = array();
		
		$foramtted_booking_details = array();
		$app_reference = trim($app_reference);
		$booking_details = $ci->custom_db->single_table_records('hotel_booking_details', '*', array('app_reference' => $app_reference));
		//below code edit by elavarasi

		//debug($booking_details); exit();
		
		if($booking_details['status'] == SUCCESS_STATUS && ($booking_details['data'][0]['status'] == 'BOOKING_CONFIRMED' || $booking_details['data'][0]['status'] == 'BOOKING_HOLD' ))
		{
			$booking_details = $booking_details['data'][0];
			//Formate the data
			$foramtted_booking_details['ConfirmationNo'] = $booking_details['confirmation_reference'];
			$foramtted_booking_details['BookingRefNo'] = $booking_details['booking_reference'];
			$foramtted_booking_details['BookingId'] = $booking_details['booking_id'];
			//FOr hotel bed
			$foramtted_booking_details['SupplierCode'] = $booking_details['hb_supplier_code'];
			$foramtted_booking_details['SupplierVatId'] = $booking_details['hb_vat_number'];
			//Below code added by ela
			$foramtted_booking_details['booking_status'] = $booking_details['status'];
			$data['status'] = SUCCESS_STATUS;
			$data['data']['BookingDetails'] = $foramtted_booking_details;
		} 
		else
		 {
			$data['message'] = 'Invalid Request';
		}
		return $data;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $app_reference
	 * @param unknown_type $sequence_number
	 */
	public function deduct_hotel_booking_amount($app_reference)
	{
		$ci = & get_instance();
		$app_reference = trim($app_reference);
		$data = $ci->db->query('select BD.* from hotel_booking_details BD
								where BD.app_reference="'.$app_reference.'"')->row_array();
		if(valid_array($data) == true && in_array($data['status'], array('BOOKING_CONFIRMED')) == true)
		{
		//Balance Deduction only on Confirmed Booking
			$agent_buying_price = ($data['total_fare']+$data['domain_markup']);
			$domain_booking_attr = array();
			$domain_booking_attr['app_reference'] = $app_reference;
			$domain_booking_attr['transaction_type'] = 'hotel';

			//Deduct Domain Balance


		//	$ci->domain_management->debit_domain_balance($agent_buying_price, hotel_v3::get_credential_type(), get_domain_auth_id(), $domain_booking_attr);//deduct the domain balance

			//Save to Transaction Log
			$domain_markup = $data['domain_markup'];
			$level_one_markup = 0;
			$agent_transaction_amount = $agent_buying_price-$domain_markup;
			$currency = $data['currency'];
			$currency_conversion_rate = $data['currency_conversion_rate'];
			$remarks = 'hotel Transaction was Successfully done';

			//$ci->domain_management_model->save_transaction_details ('hotel', $app_reference, $agent_transaction_amount, $domain_markup, $level_one_markup, $remarks, $currency, $currency_conversion_rate);
		}
	}
	/**
	 * Jaganath
	 * Checks is it a duplcaite hotel booking
	 */
	private function is_duplicate_hotel_booking($app_reference)
	{
		$ci = & get_instance();
		$data['status'] = SUCCESS_STATUS;
		$data['message'] = array();
		$hotel_booking_details = $ci->custom_db->single_table_records('hotel_booking_details', '*', array('app_reference' => trim($app_reference)));
		
		// if($hotel_booking_details['status'] == true && valid_array($hotel_booking_details['data'][0]) == true){
		// 	$hotel_booking_details = $hotel_booking_details['data'][0];
		// 	$Message = 'Duplicate Booking Not Allowed';
		// 	$data['status'] = FAILURE_STATUS;
		// 	$data['message'] = $Message;
		// }
		return $data;
	}
	/**
	 * update cache key by saving data in cache to be accessed in next page and get markup up update
	 *
	 * @param array $hotel_list        	
	 */
	public function update_markup_and_insert_cache_key_to_token($hotel_list, $carry_cache_key, $search_id, $store_full_result = true) 
	{
		$ci = & get_instance ();
		//$multiplier = $this->get_markup_multiplier($search_id);//comment by ela as per sir instruction
		//debug($multiplier);exit;
		$search_data = $ci->hotel_model_v3->get_safe_search_data_grn( $search_id );
                
		$search_data = $search_data ['data'];
		$no_of_nights = $search_data['no_of_nights'];//added by ela
		$multiplier = $no_of_nights;
		$commission_percentage = 0;
		//checking domain currency for round off the value, for usd no roundoff and INR round off to next value

	
		foreach ( $hotel_list as $j_hotel => & $j_hotel_list ) {
			$temp_token = array_values(unserialized_data($j_hotel_list['ResultToken']));
			$booking_source = $temp_token[0]['booking_source'];
			if($store_full_result == true){
				$cache_data = $j_hotel_list;
			} else {
				$cache_data['ResultToken'] = $j_hotel_list['ResultToken'];
			}
			//Cache the Data
			$access_data = Common_hotel_v3::insert_record ( $carry_cache_key, json_encode ( $cache_data ) );
			//Assiging the Cache Key
			$hotel_list[$j_hotel]['ResultToken'] = $access_data ['access_key'];
			
			//Update the Markup and Commission
			$this->update_fare_markup_commission($j_hotel_list['Price'], $multiplier, $commission_percentage, true, $booking_source,$no_of_nights);
		}
		return $hotel_list;
	}
	/**
	*Cache First Hotel Room List(GRN)
	*/
	public function update_hotel_first_room_unique($first_room_list,$carry_cache_key,$search_id){

		$temp_token = array_values(unserialized_data($first_room_list['Room_data']['RoomUniqueId']));
		$booking_source = $temp_token[0]['booking_source'];
		$access_data = Common_hotel_v3::insert_record ( $carry_cache_key, json_encode ($first_room_list['Room_data'] ) );
		
		return $access_data ['access_key'];
	}
	/**
	 * 
	 * Cache Room List
	 * @param unknown_type $room_list
	 * @param unknown_type $carry_cache_key
	 */
	public function cache_room_list($room_list, $carry_cache_key, $search_id)
	{

		//debug($room_list); exit;

		$ci = & get_instance ();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => domain_base_currency()));
		
		$search_data = $ci->hotel_model_v3->get_safe_search_data_grn( $search_id );
		$search_data = $search_data ['data'];
		$no_of_nights = $search_data['no_of_nights'];
		$multiplier = $no_of_nights;
		
		$commission_percentage = 0;
		
		if(valid_array($room_list))
		{
			foreach ($room_list as $rm_k => & $rm_v)
			{
				$temp_token = array_values(unserialized_data($rm_v['RoomUniqueId']));
				$booking_source = $temp_token[0]['booking_source'];
				$access_data = Common_hotel_v3::insert_record ( $carry_cache_key, json_encode ( $rm_v ) );

				//debug($access_data); exit;
				$room_list[$rm_k]['RoomUniqueId'] = $access_data ['access_key'];
				

				/*
				//Add markup and convert to domain currency
				$this->update_fare_markup_commission($rm_v['Price'], $multiplier, $commission_percentage, true, $booking_source);
				$this->update_fare_markup_commission_cancellation_policy($rm_v['CancellationPolicies'], $multiplier, $commission_percentage, true, $booking_source);
				*/
			}
		}

		//debug($room_list);exit('bbbbbbb');
		return $room_list;
	}
	/**
	 * 
	 * Cache Block Room Data
	 * @param unknown_type $room_list
	 * @param unknown_type $carry_cache_key
	 */
	public function cache_block_room_data($block_room_data, $carry_cache_key, $search_id)
	{
		$ci = & get_instance ();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => domain_base_currency()));
		
		$search_data = $ci->hotel_model_v3->get_safe_search_data_grn( $search_id );
		$search_data = $search_data ['data'];
		$no_of_nights = $search_data['no_of_nights'];
		$multiplier = $no_of_nights;
		
		$commission_percentage = 0;
		$domain_currency_conversion = true;
		if(valid_array($block_room_data)){
			$temp_token = array_values(unserialized_data($block_room_data['BlockRoomId']));
			$booking_source = $temp_token[0]['booking_source'];
			
			$cache_data = array();
			$cache_data['BlockRoomId'] = $block_room_data['BlockRoomId'];
			
			$access_data = Common_hotel_v3::insert_record ( $carry_cache_key, json_encode ( $cache_data ) );
			$block_room_data['BlockRoomId'] = $access_data ['access_key'];
			
			$HotelRoomsDetails = $block_room_data['HotelRoomsDetails'];
			$markup_currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );

			#echo "calloinf".'<br/>';
			$markup_price = $markup_currency_obj->get_currency($HotelRoomsDetails[0]['Price']['PublishedPrice'], true, true, false, $multiplier,'');
			$block_room_markup_plus = false;
			//if markup adding by plus means we adding markup by one time
			if(isset($markup_price['markup_type'])){
				if($markup_price['markup_type']=='plus'){
					$block_room_markup_plus = true;
				}
			}
			
			foreach ($HotelRoomsDetails as $rm_k => & $rm_v){
				//Add markup and convert to domain currency

				if($block_room_markup_plus){
					#echo "plus";
					if($rm_k == 0){
						$this->update_fare_markup_commission($rm_v['Price'], $multiplier, $commission_percentage, true, $booking_source);
					}
					else{
						$this->convert_to_domain_currency_object($rm_v['Price'], $domain_currency_conversion);
					}
				}else{
					#echo "per";
					$this->update_fare_markup_commission($rm_v['Price'], $multiplier, $commission_percentage, true, $booking_source);
				}
			
			}
		}
		//echo "sdfhsdjfsdf";
		//debug($HotelRoomsDetails);exit;
		$block_room_data['HotelRoomsDetails'] = $HotelRoomsDetails;
		return $block_room_data;
	}
	/**
	*Adding markup and commission for hotel details page first room list only for GRN
	*/
	public function update_hotel_details_markup($FareHotelDetails,$booking_source,$search_id){
		
		$ci = & get_instance ();
		$search_data = $ci->hotel_model_v3->get_safe_search_data_grn( $search_id );
		$search_data = $search_data ['data'];
		$no_of_nights = $search_data['no_of_nights'];
		$multiplier = $no_of_nights;		
		$commission_percentage = 0;		
		return  $this->update_fare_hotel_details_markup_commission($FareHotelDetails, $multiplier, $commission_percentage, true, $booking_source);
		
	}
	private function update_fare_hotel_details_markup_commission(& $FareDetails, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source,$no_of_nights=NULL)
	{
		$ci = & get_instance ();
		//Get booking_source_fk
		$booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = array();//later
		//calculating Markup and commission
		$total_fare = ($FareDetails['PublishedPrice']);			
		
		$currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier, $booking_source_fk);

		$total_markup = ($markup_price['default_value']-$total_fare);
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		if($domain_currency=='INR'){
				//Calculating GST Price for Markup
				//18% of markup
				$gst_price = 0;
				 $FareDetails['RoomPriceWoGST']			+=$total_markup;
				if($total_markup>0){
					$gst_price = round((18/100)*$total_markup);
					$total_markup = $total_markup+$gst_price;
				}else{
					$total_markup =$total_markup;
				}
				$FareDetails['RoomPrice'] += $total_markup;
				$FareDetails['RoomPrice'] = ceil($FareDetails['RoomPrice']);
				$FareDetails['PublishedPrice'] 			+=$total_markup;
				$FareDetails['PublishedPriceRoundedOff']	+=$total_markup;
				$FareDetails['PublishedPriceRoundedOff'] = ceil($FareDetails['PublishedPriceRoundedOff']);
				$FareDetails['OfferedPrice']			+=$total_markup;
				$FareDetails['OfferedPriceRoundedOff']		+=$total_markup;
				$FareDetails['OfferedPriceRoundedOff'] = ceil($FareDetails['OfferedPriceRoundedOff']);
			
			}else{
				$FareDetails['PublishedPrice'] 			+=$total_markup;
				$FareDetails['PublishedPriceRoundedOff']	+=$total_markup;
				$FareDetails['PublishedPriceRoundedOff'] = round(	$FareDetails['PublishedPriceRoundedOff'],2);
				$FareDetails['OfferedPrice']			+=$total_markup;
				$FareDetails['OfferedPriceRoundedOff']		+=$total_markup;
					$FareDetails['OfferedPriceRoundedOff'] = round($FareDetails['OfferedPriceRoundedOff'],2);
				//$FareDetails['Tax']				+=$total_markup;
		        $FareDetails['RoomPriceWoGST']			+=$total_markup;
		        $FareDetails['RoomPrice'] +=$total_markup;
		        $FareDetails['RoomPrice'] = round($FareDetails['RoomPrice'],2);
					
			}
			if(isset($FareDetails['GST'])){
				unset($FareDetails['GST']);
			}
		//debug($FareDetails);exit;
		return $this->convert_to_domain_currency_object_hotel_details($FareDetails, $domain_currency_conversion);
	
	}

	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object_hotel_details(& $FareDetails, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}
		
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$FareDetails['CurrencyCode'] = 				$domain_base_currency;
		
		$FareDetails['RoomPrice'] =					get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['RoomPrice']));
		$FareDetails['Tax'] =						get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['Tax']));
		$FareDetails['ExtraGuestCharge'] =			get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['ExtraGuestCharge']));
		$FareDetails['ChildCharge'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['ChildCharge']));
		$FareDetails['OtherCharges'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['OtherCharges']));
		$FareDetails['Discount'] =					get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['Discount']));
		$FareDetails['PublishedPrice'] =			get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['PublishedPrice']));
		$FareDetails['PublishedPriceRoundedOff'] =	get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['PublishedPriceRoundedOff']));
		$FareDetails['OfferedPrice'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['OfferedPrice']));
		$FareDetails['OfferedPriceRoundedOff'] =	get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['OfferedPriceRoundedOff']));
		$FareDetails['AgentCommission'] =			get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['AgentCommission']));
		$FareDetails['AgentMarkUp'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['AgentMarkUp']));
		$FareDetails['ServiceTax'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['ServiceTax']));
		$FareDetails['TDS'] =						get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['TDS']));
		//with GST
		$FareDetails['RoomPriceWoGST'] =					get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['RoomPriceWoGST']));
		return $FareDetails;
	}	
		/**
	*convert Cancellation Policy for Cancel hotel
	*/
	public  function update_fare_markup_commission_cancel_policy(& $CancellationCharage, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source){
		$ci = & get_instance ();
		//Get booking_source_fk
		$booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = array();//later
		//calculating Markup and commission
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		$currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		
		foreach ($CancellationCharage as $key => $value) {
			if($value['Charge']!=0){
				$total_charge = $value['Charge'];		
				$markup_price = $currency_obj->get_currency($total_charge, true, true, false, $multiplier, $booking_source_fk);
				$total_markup = ($markup_price['default_value']-$total_charge);	
				
				//Calcuating 18% of GST for markup price
				$gst_price = 0;		
				if($domain_currency=='INR'){
					if($total_markup>0){
						$gst_price = round((18/100)*$total_markup);			
					}
					$cancellation_charge = $total_markup + $gst_price;
					$CancellationCharage[$key]['Charge'] +=$cancellation_charge;
					$CancellationCharage[$key]['Charge'] = ceil($CancellationCharage[$key]['Charge']);
					
				}else{
					$CancellationCharage[$key]['Charge'] +=$total_markup;
					$CancellationCharage[$key]['Charge'] = round($CancellationCharage[$key]['Charge'],2);
				}
				 $cancel_charge = $this->convert_to_domain_currency_object_cancel($CancellationCharage[$key], $domain_currency_conversion);
				$CancellationCharage[$key]['Charge'] = $cancel_charge;
			}	
			
		}
		return $CancellationCharage;
	}
		/**
	 * Adding the Markup and Commission
	 */
	private function update_fare_markup_commission_cancellation_policy(& $CancellationCharage, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source,$no_of_nights=NULL)
	{
		$ci = & get_instance ();
		//Get booking_source_fk
		$booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = NULL;//later
		//calculating Markup and commission
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		$currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		
		foreach ($CancellationCharage as $key => $value) {
			if($value['Charge']!=0){
				$total_charge = $value['Charge'];		
				$markup_price = $currency_obj->get_currency($total_charge, true, true, false, $multiplier, $booking_source_fk);
				$total_markup = ($markup_price['default_value']-$total_charge);	
				
				//Calcuating 18% of GST for markup price
				$gst_price = 0;		
				if($domain_currency=='INR'){
					if($total_markup>0){
						$gst_price = round((18/100)*$total_markup);			
					}
					$cancellation_charge = $total_markup + $gst_price;
					$CancellationCharage[$key]['Charge'] +=$cancellation_charge;
					$CancellationCharage[$key]['Charge'] = ceil($CancellationCharage[$key]['Charge']);
					
				}else{
					$CancellationCharage[$key]['Charge'] +=$total_markup;
					$CancellationCharage[$key]['Charge'] = round($CancellationCharage[$key]['Charge'],2);
				}
				$this->convert_to_domain_currency_object_cancellation($CancellationCharage[$key], $domain_currency_conversion);
					
			}	
			
		}
		
		
	}
	/**
	*convert Cancellation Policy for Cancel hotel fro TBO
	*/
	public  function update_fare_markup_commission_cancel_policy_tbo($CancellationCharage, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source){
		$ci = & get_instance ();
		//Get booking_source_fk
		$booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = NULL;//later
		//calculating Markup and commission
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		$currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		
		foreach ($CancellationCharage as $key => $value) {
			if($value['Charge']!=0){
				$total_charge = $value['Charge'];		
				$markup_price = $currency_obj->get_currency($total_charge, true, true, false, $multiplier, $booking_source_fk);
				$total_markup = ($markup_price['default_value']-$total_charge);	
				
				//Calcuating 18% of GST for markup price
				$gst_price = 0;		
				if($domain_currency=='INR'){
					if($total_markup>0){
						$gst_price = round((18/100)*$total_markup);			
					}
					$cancellation_charge = $total_markup + $gst_price;
					$CancellationCharage[$key]['Charge'] +=$cancellation_charge;
					$CancellationCharage[$key]['Charge'] = ceil($CancellationCharage[$key]['Charge']);
					
				}else{
					$CancellationCharage[$key]['Charge'] +=$total_markup;
					$CancellationCharage[$key]['Charge'] = round($CancellationCharage[$key]['Charge'],2);
				}
				 $cancel_charge = $this->convert_to_domain_currency_object_cancel($CancellationCharage[$key], $domain_currency_conversion);
				$CancellationCharage[$key]['Charge'] = $cancel_charge;
			}	
			
		}
		return $CancellationCharage;
	}
	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object_cancel(& $CancellationCharage, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}		
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$CancellationCharage['Currency'] = $domain_base_currency;
		
		return $CancellationCharage['Charge'] =get_converted_currency_value($currency_obj->force_currency_conversion($CancellationCharage['Charge']));	
	}
	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object_cancellation(& $CancellationCharage, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}		
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$CancellationCharage['Currency'] = $domain_base_currency;
		
		$CancellationCharage['Charge'] =get_converted_currency_value($currency_obj->force_currency_conversion($CancellationCharage['Charge']));	
	}	

	/**
	 * Adding the Markup and Commission
	 */
	private function update_fare_markup_commission_old(& $FareDetails, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source,$no_of_nights=NULL)
	{
		$ci = & get_instance ();
		//Get booking_source_fk
		$booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = NULL;//later
		//calculating Markup and commission
		
		$total_fare = ($FareDetails['PublishedPrice']);			
		
		$currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier, $booking_source_fk);

		$total_markup = ($markup_price['default_value']-$total_fare);
		//Calcuating 18% of GST for markup price
		$gst_price = 0;		
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		if($no_of_nights!=NULL){
			$no_of_nights = $no_of_nights;
		}else{
			$no_of_nights = 1;
		}
		
		if($domain_currency=='INR'){
			if($total_markup>0){
				$gst_price = round((18/100)*$total_markup);			
			}
			//calculating price with gst
			$price_with_gst = $total_markup + $gst_price;		
			//divide the price per night wise		
			$night_price= $price_with_gst;
			if(isset($FareDetails['GSTPrice'])){
				$FareDetails['GSTPrice'] += $gst_price ;
				$FareDetails['GSTPrice'] = ceil($FareDetails['GSTPrice']/$no_of_nights);
			}
			
			$FareDetails['RoomPrice'] += $night_price;
			$FareDetails['RoomPrice'] = ceil($FareDetails['RoomPrice']/$no_of_nights);

			$FareDetails['PublishedPrice'] 			+=$night_price;
			$FareDetails['PublishedPrice'] = ($FareDetails['PublishedPrice']/$no_of_nights);
			$FareDetails['PublishedPriceRoundedOff']	+=$night_price;
			$FareDetails['PublishedPriceRoundedOff'] = ceil($FareDetails['PublishedPriceRoundedOff']/$no_of_nights);
			$FareDetails['OfferedPrice']			+=$night_price;
			$FareDetails['OfferedPrice'] = ($FareDetails['OfferedPrice']/$no_of_nights);
			$FareDetails['OfferedPriceRoundedOff']		+=$night_price;
			$FareDetails['OfferedPriceRoundedOff'] = ceil($FareDetails['OfferedPriceRoundedOff']/$no_of_nights);
			//$FareDetails['Tax']				+=$total_markup;
			if(isset($FareDetails['RoomPriceWoGST'])){
				$FareDetails['RoomPriceWoGST']			+=$total_markup;
	        	$FareDetails['RoomPriceWoGST']	 =ceil ($FareDetails['RoomPriceWoGST']/$no_of_nights);
			}
	       
		}else{
			
			$FareDetails['RoomPrice'] += $total_markup;
			$FareDetails['RoomPrice'] = round(($FareDetails['RoomPrice']/$no_of_nights),2);
			$FareDetails['PublishedPrice'] 			+=$total_markup;
			$FareDetails['PublishedPrice'] = ($FareDetails['PublishedPrice']/$no_of_nights);
			$FareDetails['PublishedPriceRoundedOff']	+=$total_markup;
			$FareDetails['PublishedPriceRoundedOff'] =round(($FareDetails['PublishedPriceRoundedOff']/$no_of_nights),2);
			$FareDetails['OfferedPrice']			+=$total_markup;
			$FareDetails['OfferedPrice'] = ($FareDetails['OfferedPrice']/$no_of_nights);
			$FareDetails['OfferedPriceRoundedOff']		+=$total_markup;
			$FareDetails['OfferedPriceRoundedOff'] = (	$FareDetails['OfferedPriceRoundedOff']/$no_of_nights);
			//$FareDetails['Tax']				+=$total_markup;
			if(isset($FareDetails['RoomPriceWoGST'])){
				$FareDetails['RoomPriceWoGST']			+=$total_markup;
	        	$FareDetails['RoomPriceWoGST']	 = ($FareDetails['RoomPriceWoGST']/$no_of_nights);
			}
	        
		}
		
	
		//Updating Fare Details with Markup
		/*****$FareDetails['PublishedPrice'] 			+=$total_markup;
		$FareDetails['PublishedPriceRoundedOff']	+=$total_markup;
		$FareDetails['OfferedPrice']			+=$total_markup;
		$FareDetails['OfferedPriceRoundedOff']		+=$total_markup;
		//$FareDetails['Tax']				+=$total_markup;
        $FareDetails['RoomPriceWoGST']			+=$total_markup;
		//Calculating GST Price for Markup
		//18% of markup
		$gst_price = 0;
		if($total_markup>0){
			$gst_price = round((18/100)*$total_markup);
			$FareDetails['RoomPrice'] += $total_markup+$gst_price;
		}else{
			$FareDetails['RoomPrice'] +=$total_markup;
		}******/
		/*$gst_price = round((18/100)*$total_markup);	
		$total_markup = $total_markup +$gst_price;
	    $FareDetails['RoomPriceWoGST']			+=$total_markup;*/
		//Converting Fare Object to Domain Currency
		
		$this->convert_to_domain_currency_object($FareDetails, $domain_currency_conversion);
	}
	
	/**
	 * Adding the Markup and Commission
	 */
	private function update_fare_markup_commission(& $FareDetails, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source,$no_of_nights=NULL)
	{
		$ci = & get_instance ();
		//Get booking_source_fk
		// $booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = NULL;//later
		//calculating Markup and commission
		
		$total_fare = ($FareDetails['PublishedPrice']);			
		
		$currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier, $booking_source_fk);


		//debug($markup_price); exit;

		$total_markup = ($markup_price['default_value']-$total_fare);
		//Calcuating 18% of GST for markup price
		$gst_price = 0;		
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);

		//debug($domain_currency_details); exit();
		$domain_currency = $domain_currency_details['domain_base_currency'];
		if($no_of_nights!=NULL){
			$no_of_nights = $no_of_nights;
		}else{
			$no_of_nights = 1;
		}
		
		if($domain_currency=='INR'){
			if($total_markup>0){
				$gst_price = round((18/100)*$total_markup);			
			}
			//calculating price with gst
			$price_with_gst = $total_markup + $gst_price;		
			//divide the price per night wise		
			$night_price= $price_with_gst;
			if(isset($FareDetails['GSTPrice'])){
				$FareDetails['GSTPrice'] = $gst_price ;
				//$FareDetails['GSTPrice'] = ceil($FareDetails['GSTPrice']/$no_of_nights);
			}
			
			$FareDetails['RoomPrice'] += $night_price;
			$FareDetails['RoomPrice'] = ceil($FareDetails['RoomPrice']/$no_of_nights);

			$FareDetails['PublishedPrice'] 			+=$night_price;
			$FareDetails['PublishedPrice'] = ($FareDetails['PublishedPrice']/$no_of_nights);
			$FareDetails['PublishedPriceRoundedOff']	+=$night_price;
			$FareDetails['PublishedPriceRoundedOff'] = ceil($FareDetails['PublishedPriceRoundedOff']/$no_of_nights);
			$FareDetails['OfferedPrice']			+=$night_price;
			$FareDetails['OfferedPrice'] = ($FareDetails['OfferedPrice']/$no_of_nights);
			$FareDetails['OfferedPriceRoundedOff']		+=$night_price;
			$FareDetails['OfferedPriceRoundedOff'] = ceil($FareDetails['OfferedPriceRoundedOff']/$no_of_nights);
			//$FareDetails['Tax']				+=$total_markup;
			if(isset($FareDetails['RoomPriceWoGST'])){
				$FareDetails['RoomPriceWoGST']			+=$total_markup;
	        	$FareDetails['RoomPriceWoGST']	 =ceil ($FareDetails['RoomPriceWoGST']/$no_of_nights);
			}
	       
		}else{
			
			$FareDetails['RoomPrice'] += $total_markup;
			$FareDetails['RoomPrice'] = round(($FareDetails['RoomPrice']/$no_of_nights),2);
			$FareDetails['PublishedPrice'] 			+=$total_markup;
			$FareDetails['PublishedPrice'] = ($FareDetails['PublishedPrice']/$no_of_nights);
			$FareDetails['PublishedPriceRoundedOff']	+=$total_markup;
			$FareDetails['PublishedPriceRoundedOff'] =round(($FareDetails['PublishedPriceRoundedOff']/$no_of_nights),2);
			$FareDetails['OfferedPrice']			+=$total_markup;
			$FareDetails['OfferedPrice'] = ($FareDetails['OfferedPrice']/$no_of_nights);
			$FareDetails['OfferedPriceRoundedOff']		+=$total_markup;
			$FareDetails['OfferedPriceRoundedOff'] = (	$FareDetails['OfferedPriceRoundedOff']/$no_of_nights);
			//$FareDetails['Tax']				+=$total_markup;
			if(isset($FareDetails['RoomPriceWoGST'])){
				$FareDetails['RoomPriceWoGST']			+=$total_markup;
	        	$FareDetails['RoomPriceWoGST']	 = ($FareDetails['RoomPriceWoGST']/$no_of_nights);
			}
	        
		}
		if(isset($FareDetails['GST'])){
			unset($FareDetails['GST']);	
		}
	
		//Updating Fare Details with Markup
		/*****$FareDetails['PublishedPrice'] 			+=$total_markup;
		$FareDetails['PublishedPriceRoundedOff']	+=$total_markup;
		$FareDetails['OfferedPrice']			+=$total_markup;
		$FareDetails['OfferedPriceRoundedOff']		+=$total_markup;
		//$FareDetails['Tax']				+=$total_markup;
        $FareDetails['RoomPriceWoGST']			+=$total_markup;
		//Calculating GST Price for Markup
		//18% of markup
		$gst_price = 0;
		if($total_markup>0){
			$gst_price = round((18/100)*$total_markup);
			$FareDetails['RoomPrice'] += $total_markup+$gst_price;
		}else{
			$FareDetails['RoomPrice'] +=$total_markup;
		}******/
		/*$gst_price = round((18/100)*$total_markup);	
		$total_markup = $total_markup +$gst_price;
	    $FareDetails['RoomPriceWoGST']			+=$total_markup;*/
		//Converting Fare Object to Domain Currency


		$this->convert_to_domain_currency_object($FareDetails, $domain_currency_conversion);
	}
	
	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object(& $FareDetails, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}
		
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$FareDetails['CurrencyCode'] = 				$domain_base_currency;
		
		$FareDetails['RoomPrice'] =					get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['RoomPrice']));
		$FareDetails['Tax'] =						get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['Tax']));
		$FareDetails['ExtraGuestCharge'] =			get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['ExtraGuestCharge']));
		$FareDetails['ChildCharge'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['ChildCharge']));
		$FareDetails['OtherCharges'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['OtherCharges']));
		$FareDetails['Discount'] =					get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['Discount']));
		$FareDetails['PublishedPrice'] =			get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['PublishedPrice']));
		$FareDetails['PublishedPriceRoundedOff'] =	get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['PublishedPriceRoundedOff']));
		$FareDetails['OfferedPrice'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['OfferedPrice']));
		$FareDetails['OfferedPriceRoundedOff'] =	get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['OfferedPriceRoundedOff']));
		$FareDetails['AgentCommission'] =			get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['AgentCommission']));
		$FareDetails['AgentMarkUp'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['AgentMarkUp']));
		$FareDetails['ServiceTax'] =				get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['ServiceTax']));
		$FareDetails['TDS'] =						get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['TDS']));
		//with GST
		if(isset($FareDetails['RoomPriceWoGST'])){
			$FareDetails['RoomPriceWoGST'] =					get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['RoomPriceWoGST']));
		}
		
		//$FareDetails['GSTPrice'] = get_converted_currency_value($currency_obj->force_currency_conversion($FareDetails['GSTPrice']));

		//debug($FareDetails); exit;
	}	
	/**
	 * Returns Booking Transaction Amount Details
	 * @param unknown_type $core_price_details
	 */
	public function final_booking_transaction_fare_details($core_price_details, $search_id, $booking_source)
	{
		$ci = & get_instance();
		
		$search_data = $ci->hotel_model_v3->get_safe_search_data_grn( $search_id );
		//debug($core_price_details); exit;
		$search_data = $search_data ['data'];
		$no_of_nights = $search_data['no_of_nights'];
		$multiplier = $no_of_nights;
		
		$domain_id = get_domain_auth_id();
		$commission_percentage = 0;
		$domain_currency_conversion = false;
		
		$core_total_price = 0;
		$updated_total_price = 0;
		//debug($core_price_details);
		$markup_gst_price=0;
		$updated_markup_gst_price = 0;
		$markup_currency_obj = new Currency ( array ('module_type' => 'b2c_hotel','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );

		//debug($markup_currency_obj); exit;
		$markup_price = $markup_currency_obj->get_currency($core_price_details[0]['Price']['PublishedPrice'], true, true, false, $multiplier,'');
		//debug($markup_price);exit;
		$block_room_markup_plus = false;
		if(isset($markup_price['markup_type'])){
			if($markup_price['markup_type']=='plus'){
				$block_room_markup_plus = true;
			}
		}

		//debug($core_price_details); exit;

		foreach ($core_price_details as $k => $v){			
			$core_total_price += $v['Price']['PublishedPriceRoundedOff'];
			if($block_room_markup_plus){
				if($k==0){
					$this->update_fare_markup_commission($v['Price'], $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source);
					if(isset($v['Price']['GSTPrice'])){
						$markup_gst_price += $v['Price']['GSTPrice'];	
					}			
					$updated_markup_gst_price +=$v['Price']['RoomPriceWoGST'];
				}
				
			}else{
				$this->update_fare_markup_commission($v['Price'], $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source);
				if(isset($v['Price']['GSTPrice'])){
					$markup_gst_price += $v['Price']['GSTPrice'];	
				}			
				$updated_markup_gst_price +=$v['Price']['RoomPriceWoGST'];
			}
		
			$updated_total_price += $v['Price']['PublishedPriceRoundedOff'];			
		}
		//exit;
		$admin_markup = ($updated_total_price - $core_total_price);
		//Storing GST price
		/*Start*/
		$gst_price = $markup_gst_price;
		$hotel_markup_price = ($updated_total_price - $updated_markup_gst_price);
		$admin_markup_gst = ($admin_markup - $gst_price);

		/*End*/
		//Fare Breakups
		$final_booking_transaction_fare_details['RoomPriceBreakup'] = $core_price_details;
		$final_booking_transaction_fare_details['Price'] = array();
		$final_booking_transaction_fare_details['Price']['total_fare'] = $core_total_price;
		$final_booking_transaction_fare_details['Price']['admin_markup'] = round($admin_markup, 1);
		$final_booking_transaction_fare_details['Price']['gst'] = round($gst_price, 1);
		$final_booking_transaction_fare_details['Price']['hotel_markup_price'] = round($hotel_markup_price, 1);
		$final_booking_transaction_fare_details['Price']['admin_markup_gst'] = round($admin_markup_gst, 1);
		
		//Client Buying Price
		$final_booking_transaction_fare_details['Price']['client_buying_price'] = floatval($updated_total_price);//admin markup is already included in fare
		return $final_booking_transaction_fare_details;
	}
	/**
	 * Jaganath
	 * Update Cancellation Refund details
	 * @param unknown_type $cancellation_details
	 * @param unknown_type $app_reference
	 */
	public function update_domain_cancellation_refund_details($cancellation_details, $app_reference)
	{

		//error_reporting(E_ALL); 
		$ci = & get_instance ();
		$HotelChangeRequestStatusResult = array();
		//Adding Travelomatix Cancellation Charges
		$upadted_cancellation_details = $this->add_cancellation_charge($cancellation_details);
		$RefundedAmount = floatval(@$upadted_cancellation_details['RefundedAmount']);
		$CancellationCharge = floatval(@$upadted_cancellation_details['CancellationCharge']);
		//Crdeting Refund Amount to Domain Balance
		$cancelltion_domain_attr = array();
		$cancelltion_domain_attr['app_reference'] = $app_reference;
		$cancelltion_domain_attr['transaction_type'] = 'hotel';
		
		$ci->load->library('domain_management');
		$ci->domain_management->credit_domain_balance($RefundedAmount, hotel_v3::get_credential_type(), get_domain_auth_id(), $cancelltion_domain_attr);
		//Adding Refund Details
		$domain_base_currency = domain_base_currency();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		$currency_conversion_rate = $currency_obj->get_domain_currency_conversion_rate();
		$refund_status = 'PROCESSED';
		$ci->hotel_model_v3->update_refund_details($app_reference, $refund_status, $RefundedAmount,$CancellationCharge, $domain_base_currency, $currency_conversion_rate);
		//Saving Refund details in transaction log
		$fare = -($RefundedAmount);//dont remove: converting to negative
		$domain_markup=0;
		$level_one_markup=0;
		$remarks = 'hotel Refund was Successfully done';
		$ci->domain_management_model->save_transaction_details ( 'hotel', $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $domain_base_currency, $currency_conversion_rate);
		//Converting the API Fare Currency to Domain Currency
		//Assigning the cancellation data
		$RefundedAmount = 					get_converted_currency_value($currency_obj->force_currency_conversion($RefundedAmount));
		$CancellationCharge = 					get_converted_currency_value($currency_obj->force_currency_conversion($CancellationCharge));
		$HotelChangeRequestStatusResult = $cancellation_details;
		$HotelChangeRequestStatusResult['RefundedAmount'] = $RefundedAmount;
		$HotelChangeRequestStatusResult['CancellationCharge'] = $CancellationCharge;
		return $HotelChangeRequestStatusResult;
	}
	/**
	 * Add cancellation charge
	 * TODO: add Travelomatix cancellation charges
	 */
	private function add_cancellation_charge($cancellation_details)
	{
		$upadted_cancellation_details = array();
		$upadted_cancellation_details['RefundedAmount'] =		floatval(@$cancellation_details['RefundedAmount']);
		$upadted_cancellation_details['CancellationCharge'] =	floatval(@$cancellation_details['CancellationCharge']);
		return $upadted_cancellation_details;
	}
	/**
	 * Returns Markup Multiflier for hotel
	 * @param unknown_type $search_id
	 */
	private function get_markup_multiplier($search_id)
	{
		$ci = & get_instance ();
		$search_data = $ci->hotel_model_v3->get_safe_search_data_grn( $search_id );
		$search_data = $search_data ['data'];
		
		$no_of_nights = $search_data['no_of_nights'];
		$no_of_rooms = $search_data['room_count'];
		$multiplier = ($no_of_nights * $no_of_rooms);
		return $multiplier;
	}
	
	/**
	 * calculate markup
	 *
	 * @param string $markup_type        	
	 * @param float $markup_val        	
	 * @param float $total_fare        	
	 * @param int $multiplier        	
	 */
	private function calculate_markup($markup_type, $markup_val, $total_fare, $multiplier) {
		if ($markup_type == 'percentage') {
			$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
			$markup_amount = number_format ( $markup_amount, 3, '.', '' );
		} else {
			$markup_amount = ($multiplier * floatval ( $markup_val ));
			$markup_amount = number_format ( $markup_amount, 3, '.', '' );
		}
		return $markup_amount;
	}
	/*
	 * Returns the next highest integer value by rounding up value
	 */
	static function get_round_price($price) {
		$price_val = ceil ( $price );
		return $price_val;
	}
	public function decrypt( $string, $key ) {
	  $key = base64_decode($key);
	 
	  $algorithm = MCRYPT_RIJNDAEL_128;
	  $key = md5( $key, true );
	  $iv_length = mcrypt_get_iv_size( $algorithm, MCRYPT_MODE_CBC );
	  $string = base64_decode( $string );
	  $iv = substr( $string, 0, $iv_length );
	  $encrypted = substr( $string, $iv_length );
	  $result = mcrypt_decrypt( $algorithm, $key, $encrypted, MCRYPT_MODE_CBC, $iv );
	  return $result;
	}

	public function update_tds($hotel_list)
	{
		foreach ( $hotel_list as $j_hotel => & $j_hotel_list ) {			
			//Update the Markup and Commission
			if($j_hotel_list['Price']['AgentCommission'] > 0)
			{
				if($j_hotel_list['Price']['TDS'] <= 0)
				{
					$j_hotel_list['Price']['TDS'] = $j_hotel_list['Price']['AgentCommission'] * 5 /100;
				}
			}
		}
		return $hotel_list;
	}
	
}

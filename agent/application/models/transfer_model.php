<?php
/**
 * Library which has generic functions to get data
 * @package    Provab Application
 * @subpackage Transfer Model
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
Class Transfer_Model extends CI_Model {

    public $master_search_data;

    /**
     * get search data and validate it
     */
    function get_safe_search_data($search_id) {
        $search_data = $this->get_search_data($search_id);

        $success = true;
        $clean_search = array();
        if ($search_data != false) {
            //validate
            $temp_search_data = json_decode($search_data['search_data'], true);
            //debug($temp_search_data);exit;
            $clean_search = $this->clean_search_data($temp_search_data);
            $success = $clean_search['status'];
            $clean_search = $clean_search['data'];
        } else {
            $success = false;
        }
        return array('status' => $success,
            'data' => $clean_search
        );
    }

    /**
     * Badri
     * get service tax and TDs
     * 
     */
    function get_tax() {
        $response ['data'] = array();

        $q = $this->db->query('select tds,service_tax from commission_master where module_type="transfer"')->result_array();

        $response ['data']['tds'] = $q[0]['tds'];
        $response ['data']['service_tax'] = $q[0]['service_tax'];
        return $response;
    }

    function get_cfee() {

        $result = array();
        $qry = "select * from convenience_fees where module = 'transfer'";
        $query = $this->db->query($qry);

        foreach ($query->result_array() as $row) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * get search data without doing any validation
     * @param $search_id
     */
    function get_search_data($search_id) {
        if (empty($this->master_search_data)) {
            $search_data = $this->custom_db->single_table_records('search_history', '*', array('origin' => $search_id, 'search_type' => META_TRANSFER_COURSE));
            if ($search_data['status'] == true) {
                $this->master_search_data = $search_data['data'][0];
            } else {
                return false;
            }
        }
        return $this->master_search_data;
    }

    /**
     * hotel address
     * @param $hotel_id
     */
    function get_searched_hotel_address($hotel_id) {
        $query = 'Select hotel_name, hotel_city, hotel_code,address,postal_code, origin from hb_hotel_details where hotel_code=' . $hotel_id;

        return $this->db->query($query)->result_array();
    }

    /**
     * Save search data for future use - Analytics
     * @param array $params
     */
    function save_search_data($search_data, $type) {
        $data['domain_origin'] = get_domain_auth_id();
        $data['search_type'] = $type;
        $data['created_by_id'] = intval(@$this->entity_user_id);
        $data['created_datetime'] = date('Y-m-d H:i:s');
        $data['from_terminal'] = $search_data['from_transfer_type'];
        $data['to_terminal'] = $search_data['to_transfer_type'];
        $data['from_code'] = $search_data['from_loc_id'];
        $data['to_code'] = $search_data['to_loc_id'];
        $data['from_location_name'] = $search_data['transfer_from'];
        $data['to_location_name'] = $search_data['transfer_to'];
        $data['adult'] = $search_data['adult'];
        $data['child'] = $search_data['child'];
        $data['departure_date'] = date('Y-m-d H:i', strtotime($search_data['depature']));

        if (isset($search_data['adult_ages']) && valid_array($search_data['adult_ages'])) {
            $data['adult_ages'] = json_encode($search_data['adult_ages']);
        }
        if (isset($search_data['child_ages']) && valid_array($search_data['child_ages'])) {
            $data['child_ages'] = json_encode($search_data['child_ages']);
        }
        if (isset($search_data['return'])) {
            $data['return_date'] = date('Y-m-d H:i', strtotime($search_data['return']));
        }
        $data['trip_type'] = $search_data['transfer_type'];
        $this->custom_db->insert_record('search_transfer_history', $data);
    }

    /**
     * get all the booking source which are active for current domain
     */
    function active_booking_source() {
        $query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id=' . $this->db->escape(META_TRANSFER_COURSE) . '
		and BS.booking_engine_status=' . ACTIVE . ' AND MCL.status=' . ACTIVE . ' AND ASM.status="active"';
        
        return $this->db->query($query)->result_array();
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
        $bd_query = 'select * from transfer_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
        if (empty($booking_source) == false) {
            $bd_query .= '  AND BD.booking_source = '.$this->db->escape($booking_source);
        }
        if (empty($booking_status) == false) {
            $bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
        }
        $id_query = 'select * from  transfer_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from  transfer_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
        $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
        $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();
        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
        $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
        if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }
        return $response;
    }


    function top_transfer_location($Limit) {
//		$filter = array('status'=>1);
        $filter = array();
        $result = $this->custom_db->single_table_records('transfer_location_details', '*', $filter, 0, $Limit, array('origin' => 'desc',));
        return @$result['data'];
    }

    /**
     * Clean up search data
     */
    function clean_search_data($temp_search_data) {
        $success = true;
        $clean_search['from'] = $temp_search_data['transfer_from'];
        $clean_search['to'] = $temp_search_data['transfer_to'];

        if ((strtotime($temp_search_data['depature']) > time()) || date('Y-m-d', strtotime($temp_search_data['depature'])) == date('Y-m-d')) {
            $clean_search['from_date'] = $temp_search_data['depature'];
        } else {
            $success = false;
        }
        if (isset($temp_search_data['return']) && strtotime($temp_search_data['return']) > time()) {
            $clean_search['to_date'] = $temp_search_data['return'];
        }

        $clean_search['from_code'] = $temp_search_data['from_loc_id'];
        $clean_search['to_code'] = $temp_search_data['to_loc_id'];
        $clean_search['from_transfer_type'] = $temp_search_data['from_transfer_type'];
        $clean_search['to_transfer_type'] = $temp_search_data['to_transfer_type'];
        $clean_search['adult'] = $temp_search_data['adult'];
        $clean_search['child'] = $temp_search_data['child'];
        $depature_date_time = $temp_search_data['depature'];
        $clean_search['depature'] = $depature_date_time;
        

        if (isset($temp_search_data['adult_ages']) && valid_array($temp_search_data['adult_ages'])) {
            $clean_search['adult_ages'] = $temp_search_data['adult_ages'];
        }

        if (isset($temp_search_data['child_ages']) && valid_array($temp_search_data['child_ages'])) {
            $clean_search['child_ages'] = $temp_search_data['child_ages'];
        }

        if (isset($temp_search_data['return'])) {
            $clean_search['return'] = $temp_search_data['return'];
        }
        $clean_search['trip_type'] = $temp_search_data['transfer_type']; //debug($clean_search); exit;
        return array('data' => $clean_search, 'status' => $success);
    }

    /**
     * Get airport list
     * 
     */
    function get_airport_list($search_chars) {
        $raw_search_chars = $this->db->escape($search_chars);
        $r_search_chars = $this->db->escape($search_chars . '%');
        $search_chars = $this->db->escape('%' . $search_chars . '%');

        $query = 'Select * from transfer_destinations where name like ' . $search_chars . '
		OR city_name like ' . $search_chars . ' OR country_name like ' . $search_chars . ' OR code like ' . $search_chars . '
		ORDER BY name ASC,
		CASE
			WHEN	name	LIKE	' . $raw_search_chars . '	THEN 1
			WHEN	city_name	LIKE	' . $raw_search_chars . '	THEN 2
			WHEN	country_name			LIKE	' . $raw_search_chars . '	THEN 3
            WHEN    code            LIKE    ' . $raw_search_chars . '   THEN 3

			WHEN	name	LIKE	' . $r_search_chars . '	THEN 4
			WHEN	city_name	LIKE	' . $r_search_chars . '	THEN 5
			WHEN	country_name			LIKE	' . $r_search_chars . '	THEN 6
            WHEN    code            LIKE    ' . $r_search_chars . '   THEN 3

			WHEN	name	LIKE	' . $search_chars . '	THEN 7
			WHEN	city_name	LIKE	' . $search_chars . '	THEN 8
			WHEN	country_name			LIKE	' . $search_chars . '	THEN 9
            WHEN    code            LIKE    ' . $search_chars . '   THEN 3
			ELSE 10 END';
		// LIMIT 0, 20';
        //debug($query);exit;
        return $this->db->query($query);
    }

   public  function save_booking_details( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $transfer_type, $trip_type, $vehicle_name, $vehicle_code, $category_name,$category_code,$phone_number, $alternate_number, $email, $departure_date, $departure_time, $return_date, $return_time, $payment_mode, $attributes, $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code )
    {
        $data['domain_origin'] = $domain_origin;
        $data['status'] = $status;
        $data['app_reference'] = $app_reference;
        $data['booking_source'] = $booking_source;
        $data['booking_id'] = $booking_id;
        $data['booking_reference'] = $booking_reference;
        $data['confirmation_reference'] = $confirmation_reference;
        $data['trip_type'] = $trip_type;
        $data['transfer_type'] = $transfer_type;
        $data['vehicle_name'] = $vehicle_name;
        $data['category_name'] = $category_name;
        $data['vehicle_code'] = $vehicle_code;
        $data['category_code'] = $category_code;
        $data['phone_number'] = $phone_number;
        $data['phone_code'] = $phone_code;
        $data['alternate_number'] = $alternate_number;
        $data['email'] = $email;
        $data['travel_date'] = $departure_date;
        $data['travel_time'] = $departure_time;
        $data['return_date'] = $return_date;
        $data['return_time'] = $return_time;
        $data['payment_mode'] = $payment_mode;
        $data['attributes'] = $attributes;
        $data['created_by_id'] = $created_by_id;
        $data['created_datetime'] = date('Y-m-d H:i:s');        
        $data['currency'] = $transaction_currency;
        $data['currency_conversion_rate'] = $currency_conversion_rate;       
        
        $status = $this->custom_db->insert_record('transfer_booking_details', $data);
        return $status;
    }   

   /**
     *
     * @param string $app_reference
     * @param string $location
     
     * @param date   $travel_date
     * @param string $grade_desc
     * @param string $grade_code
     * @param string $status
     * 
     * @param string $attributes
     */
    function save_booking_itinerary_details( $app_reference, $from_location, $to_location, $image, $status,$commissionable_fare,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, $attributes, $total_fare,$agent_commission,$agent_tds,$admin_commission,$admin_tds,$api_raw_fare,$agent_buying_price, $gst_value)
    {
        $data['app_reference'] = $app_reference;
        $data['from_location'] = $from_location;
        $data['to_location'] = $to_location;
        $data['image'] = $image;
        $data['status'] = $status;        
        $data['total_fare'] = $total_fare;
        $data['admin_net_markup'] = $admin_net_fare_markup;
        $data['admin_markup    '] = $admin_markup;
        $data['agent_markup'] = $agent_markup;
        $data['currency'] = $currency;
        $data['attributes'] = $attributes;
        $data['agent_commission'] = $agent_commission;
        $data['agent_tds'] = $agent_tds;
        $data['admin_commission'] = $admin_commission;
        $data['admin_tds'] = $admin_tds;
        $data['api_raw_fare'] = $api_raw_fare;
        $data['agent_buying_price'] =$agent_buying_price;
        $data['gst'] =$gst_value;
        $status = $this->custom_db->insert_record('transfer_booking_itinerary_details', $data);
        return $status;
    }
     function save_booking_pax_details($title,$app_reference,$first_name,$last_name, $phone, $email, $pax_type,$status)
    {        
        $data['app_reference'] = $app_reference;
        $data['title'] = $title;
        $data['first_name'] = $first_name;      
        $data['last_name'] = $last_name;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['pax_type'] = $pax_type;        
        $data['status'] = $status;
      
        $status = $this->custom_db->insert_record('transfer_booking_pax_details', $data);
        
        return $status;
    }
     /**
     * Return Booking Details based on the app_reference passed
     * @param $app_reference
     * @param $booking_source
     * @param $booking_status
     */

    function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
       
        $condition = $this->custom_db->get_custom_condition($condition);
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
                    where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $response['status'] = FAILURE_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from transfer_booking_pax_details AS CD 
                            WHERE CD.app_reference IN ('.$app_reference_ids.')';
                $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                            WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            // $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
            // $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();
            // $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            $response['data']['cancellation_details']   = $cancellation_details;
            if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
                $response['status'] = SUCCESS_STATUS;
            }
        }
        return $response;
    }
     /* Elavarasi
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
        $this->custom_db->update_record('transfer_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
        //3.Update Itinerary Status
        $this->custom_db->update_record('transfer_booking_pax_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
    }
     /* Add Cancellation details
     * @param unknown_type $AppReference
     * @param unknown_type $cancellation_details
     */
    private function update_cancellation_refund_details($AppReference, $cancellation_details)
    {
        $transfer_cancellation_details = array();
        $transfer_cancellation_details['app_reference'] =                $AppReference;
        $transfer_cancellation_details['ChangeRequestId'] =          $cancellation_details['ChangeRequestId'];
        $transfer_cancellation_details['ChangeRequestStatus'] =      $cancellation_details['ChangeRequestStatus'];
        $transfer_cancellation_details['status_description'] =       $cancellation_details['StatusDescription'];
        $transfer_cancellation_details['API_RefundedAmount'] =       $cancellation_details['RefundedAmount'];
        $transfer_cancellation_details['API_CancellationCharge'] =   $cancellation_details['CancellationCharge'];
        if($cancellation_details['ChangeRequestStatus'] == 3){
            $transfer_cancellation_details['cancellation_processed_on'] =    date('Y-m-d H:i:s');
            $attributes = array();
            $attributes['CreditNoteNo'] =                               @$cancellation_details['CreditNoteNo'];
            $attributes['CreditNoteCreatedOn'] =                        @$cancellation_details['CreditNoteCreatedOn'];
            $transfer_cancellation_details['attributes'] = json_encode($attributes);
        }
        $cancel_details_exists = $this->custom_db->single_table_records('transfer_cancellation_details', '*', array('app_reference' => $AppReference));
        if($cancel_details_exists['status'] == true) {
            //Update the Data
            unset($transfer_cancellation_details['app_reference']);
            $this->custom_db->update_record('transfer_cancellation_details', $transfer_cancellation_details, array('app_reference' => $AppReference));
        } else {
            //Insert Data
            $transfer_cancellation_details['created_by_id'] =                (int)@$this->entity_user_id;
            $transfer_cancellation_details['created_datetime'] =             date('Y-m-d H:i:s');
            $data['cancellation_requested_on'] = date('Y-m-d H:i:s');
            $this->custom_db->insert_record('transfer_cancellation_details',$transfer_cancellation_details);
        }
    }
     function get_monthly_booking_summary($condition=array())
    {
        //Balu A
       // $condition = $this->custom_db->get_custom_condition($condition);
        $query = 'select count(distinct(BD.app_reference)) AS total_booking, 
                sum(SBID.total_fare+SBID.admin_markup) as monthly_payment, sum(SBID.admin_markup) as monthly_earning, 
                MONTH(BD.created_datetime) as month_number 
                from transfer_booking_details AS BD
                join transfer_booking_itinerary_details AS SBID on BD.app_reference=SBID.app_reference
                where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' AND BD.created_by_id = '.$GLOBALS['CI']->entity_user_id.' AND BD.status = "BOOKING_CONFIRMED"
                GROUP BY YEAR(BD.created_datetime), 
                MONTH(BD.created_datetime)';
        return $this->db->query($query)->result_array();
    }

     function filter_booking_report($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
                    where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from transfer_booking_pax_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                $cancellation_details_query = 'select HCD.* from transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                $booking_cancellation_details = $this->db->query($cancellation_details_query)->result_array();


            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            $response['data']['cancellation_details'] = $booking_cancellation_details;
            return $response;
        }
    }

}

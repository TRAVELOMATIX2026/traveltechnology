<?php
if (! defined ( 'BASEPATH' ))
exit ( 'No direct script access allowed' );
require_once BASEPATH . 'libraries/Common_Api_Grind.php';
/**
 *
 * @package Provab
 * @subpackage API
 * @author Sunil V<sunil.provab23@gmail.com>
 * @version V1
 */
class Tboholidays extends Common_Api_Grind {
    private $ClientId;
    private $UserName;
    private $Target;
    private $Password;
    private $ServiceUrl;

    public $master_search_data;
    public $search_hash;
    public $booking_source;

    public function __construct() {
        $this->CI = &get_instance ();
        $GLOBALS ['CI']->load->library ( 'Api_Interface' );
        $GLOBALS ['CI']->load->model ( 'hotel_model' );
        $this->CI->load->library('Converter');

        $this->booking_source = TBO_HOTEL_BOOKING_SOURCE;

        $this->TokenId = $GLOBALS ['CI']->session->userdata ( 'tb_auth_token' );
        $this->set_api_credentials ();
    }
    private function get_header() {
        $hotel_engine_system = $this->CI->hotel_engine_system;
        $user_name = $this->CI->hotel_engine_system. '_username';
        $password = $this->CI->hotel_engine_system. '_password';
        $response ['UserName'] = $this->CI->$user_name;
        $response ['Password'] = $this->CI->$password;
        $response ['DomainKey'] = $this->CI->domain_key;
        $response ['system'] = $hotel_engine_system;
        // debug($response);exit;
        return $response;
    }
    private function set_api_credentials() {
        $hotel_engine_system = $this->CI->hotel_engine_system;
        $this->system = $hotel_engine_system;

        //debug($hotel_engine_system); exit;

        if($hotel_engine_system=="live")
        {
              $this->UserName = "Starlegends";
            $this->Password = "Sta@87381619";
            $this->ServiceUrl =  "https://apiwr.tboholidays.com/HotelAPI/";
        }else{
              $this->UserName = "Starlegends";
            $this->Password = "Sta@13215386";
            $this->ServiceUrl =  "http://api.tbotechnology.in/TBOHolidays_HotelAPI";
         }
    }
    function credentials($service) {
        switch ($service) {
            case 'GetHotelResult' :
                $this->service_url = $this->Url . 'Search';
                break;
            case 'GetHotelImages' :
                $this->service_url = $this->Url.'GetHotelImages';
                break;
            case 'GetHotelInfo' :
                $this->service_url = $this->Url . 'HotelDetails';
                break;
            case 'GetHotelRoom' :
                $this->service_url = $this->Url . 'RoomList';
                break;
            case 'BlockRoom' :
                $this->service_url = $this->Url . 'BlockRoom';
                break;
            case 'Book' :
                $this->service_url = $this->Url . 'CommitBooking';
                break;
            case 'GetCancellationCode':
                $this->service_url = $this->Url . 'GetCancellationPolicy';
                break;
            case 'CancelBooking' :
                $this->service_url = $this->Url . 'CancelBooking';
                break;
            case 'CancellationRefundDetails' :
                $this->service_url = $this->Url . 'CancellationRefundDetails';
                break;
            case 'UpdateHoldBooking':
              $this->service_url = $this->Url .'UpdateHoldBooking';
              break;
            case 'AgodaBookingList':
                $this->service_url = $this->Url .'AgodaBookingList';
            break;
        }
    }

    
    /**
     * Balu A
     *
     * get hotel search request details
     * 
     * @param array $search_params
     *          data to be used while searching of hotels
     */
    private function hotel_search_request($search_params, $source_id) {

        //debug($search_params); exit;

        $response ['status'] = false;
        $response ['data'] = array ();

        if (isset($search_params) && valid_array($search_params) && count($search_params) > 0 && empty($search_params['hotel_destination_location'])==false) {
            
            $PaxRooms = [];
            $childCnt = 0;

            $city_list = $this->CI->db->select('hotel_code,city_code,country_code')
                        ->from('tbo_master_hotel_details')
                        ->where('city_code', $search_params['hotel_destination_location'])
                        ->limit(2000)
                        ->get()
                        ->result_array();

            $HotelCodes = array_column($city_list, 'hotel_code');
            $CountryCodes = array_column($city_list, 'country_code');

            $HotelCodes = implode(',', $HotelCodes);

            $search_params['nationality'] = $CountryCodes[0];


            for ($i=0; $i < $search_params['room_count']; $i++) { 
                if (isset($search_params['adult_config']) && count($search_params['adult_config']) > 0) {
                    $pax['Adults'] = $search_params['adult_config'][$i];
                }
                if (isset($search_params['child_config']) && count($search_params['child_config']) > 0) {
                    $pax['Children'] = $search_params['child_config'][$i];
                    $childAges = [];
                    for ($j=0; $j < $search_params['child_config'][$i]; $j++) { 
                        $childAges[] = $search_params['child_age'][$childCnt];
                        $childCnt++;
                    }
                    $pax['ChildrenAges'] = $childAges;
                }
                array_push($PaxRooms, $pax);
            }

            $request = [
                'checkIn' => date('Y-m-d', strtotime($search_params['from_date'])),
                'checkOut' => date('Y-m-d', strtotime($search_params['to_date'])),
                'HotelCodes' => $HotelCodes,
                'GuestNationality' => $search_params['nationality'],
                'PaxRooms' => $PaxRooms,
                'ResponseTime' => 5.0,
                'IsDetailedResponse' => true,
                'Filters' => [
                    'NoOfRooms' => 0,
                    'MealType' => 'All',
                ]
            ];


         //debug($request); exit;

        $response ['status'] = true;
        $response ['data'] ['request'] = json_encode($request);
        $response['data']['method'] = 'search';
        $response ['data'] ['url'] = $this->ServiceUrl;

    }
    else
    {
        $response ['status'] = false;
    }
        
        return $response;
    }
    
    /**
     * Balu A
     *
     * Hotel Details Request
     * 
     * @param string $TraceId           
     * @param string $ResultIndex           
     * @param string $HotelCode         
     */
    private function hotel_details_request($ResultToken, $search_params) {

        $FirstHotelData = unserialized_data($ResultToken);

        $hotel_data = reset($FirstHotelData);

        //debug($hotel_data); exit;

        $HotelDetailsRequest = "";

        if(is_array($hotel_data) && is_array($search_params))
        {
            $hotel_code = $hotel_data['HotelCode'];

            $HotelDetailsRequest = array(
                'Hotelcodes'=>$hotel_code,
                'Language'=>'EN'
            );

                $response ['status'] = true;
                $response ['data'] = array ();
                $response['data']['method'] = 'Hoteldetails';
                $response ['data'] ['request'] = json_encode($HotelDetailsRequest);
                $response ['data'] ['url'] = $this->ServiceUrl;
                return $response;
            }

            $response ['status'] = false;
            $response ['data'] = array ();
            $response ['data'] ['request'] = $HotelDetailsRequest;
            $response ['data'] ['url'] = $this->ServiceUrl;
            return $response;   
    }
    
    /**
     * Balu A
     *
     * Room Details Request
     * 
     * @param string $TraceId           
     * @param string $ResultIndex           
     * @param string $HotelCode         
     */
    private function room_list_request($ResultToken) {
        $response ['status'] = true;
        $response ['data'] = array ();
        $request ['ResultToken'] = $ResultToken;
        $response ['data'] ['request'] = json_encode ( $request );
        $this->credentials ( 'GetHotelRoom' );
        $response ['data'] ['service_url'] = $this->service_url;
        return $response;
    }
    
    /**
     * Balu A
     *
     * get room block request
     * 
     * @param array $booking_parameters         
     */
    private function get_block_room_request($booking_params)
    {
        $hotel_data = unserialized_data($booking_params['token'][0]);
        $response ['status'] = false;
        $response ['data'] = array ();

        $RoomBlockRequest = "";

        // debug($hotel_data);
        // debug($booking_params); exit;

        if(is_array($hotel_data) && isset($hotel_data['booking_code']))
        {
            $RoomBlockRequest = array(
                'BookingCode' => $hotel_data['booking_code'],
                'PaymentMode'=>'Limit'
            );

                //debug($RoomBlockRequest); exit;

                $response ['status'] = true;
                $response ['data'] = array ();
                $response ['data']['method'] = 'PreBook';
                $response ['data'] ['request'] = json_encode($RoomBlockRequest);
                $response ['data'] ['url'] = $this->ServiceUrl;
        }
            
            return $response;
    }

    /**
     * Form Book Request
     */
    function get_book_request($booking_params, $booking_id)
    {   
        // error_reporting(-1);
        // ini_set('display_errors', 1);

       // debug($booking_params); exit;

        $response['status'] = false;
        $response['data'] = [];
        $BookingRequest = [];
        $room_details = [];

        $search_id = $booking_params['token']['search_id'];
        $GuestNationality = $booking_params['token']['GuestNationality'];
        $safe_search_data = $GLOBALS['CI']->hotel_model->get_search_data($search_id);
        $search_data = json_decode($safe_search_data['search_data'], true);

        $number_of_nights = get_date_difference(
            date('Y-m-d', strtotime($search_data['hotel_checkin'])),
            date('Y-m-d', strtotime($search_data['hotel_checkout']))
        );

        $NO_OF_ROOMS = $search_data['rooms'];

        $search_params = $this->search_data($search_id);
        $search_params = $search_params['data'];

        //debug($search_params); exit;

        $room_wise_passenger_info = array();

         $BlockRoomData = unserialized_data($booking_params['token']['BlockRoomId']);

        //debug($BlockRoomData); exit;
        
        $BookingCode = $BlockRoomData['RatePlanCode'];

        if(empty($BookingCode)==false && isset($BlockRoomData['Price']['RoomPrice']))
        {

            $BookingRequest['BookingCode'] = $BookingCode;

            $TotalFare = $BlockRoomData['Price']['RoomPrice']+$BlockRoomData['Price']['Tax'];

            foreach (['adult_config', 'child_config'] as $type) {
                if (!isset($search_params[$type])) {
                    $search_params[$type] = array_fill(0, $NO_OF_ROOMS, 0);
                }
            }

            //debug($TotalFare); exit;

            foreach ($booking_params['name_title'] as $bk => $bv) {
                $pax_type = trim($booking_params['passenger_type'][$bk]);

                for ($i = 0; $i < $NO_OF_ROOMS; $i++) {
                    $assigned_count = $this->get_assigned_pax_type_count(@$room_wise_passenger_info[$i]['passenger_type'], $pax_type);

                    if ((intval($pax_type) == 1 && $assigned_count < $search_params['adult_config'][$i]) ||
                        (intval($pax_type) == 2 && $assigned_count < $search_params['child_config'][$i])) {

                        foreach (['name_title', 'first_name', 'middle_name', 'last_name', 'passenger_type', 'date_of_birth'] as $field) {
                            $room_wise_passenger_info[$i][$field][] = $booking_params[$field][$bk];
                        }

                        foreach (['passenger_contact', 'billing_email'] as $field) {
                            $room_wise_passenger_info[$i][$field][] = $booking_params[$field];
                        }

                        unset($booking_params['name_title'][$bk]);
                        break;
                    }
                }
            }


                    for ($i = 0; $i < $NO_OF_ROOMS; $i++) {
                        $booking_params['token']['token'][$i]['no_of_pax'] = $search_data['adult'][$i] + $search_data['child'][$i];
                    }

                    

                    for ($i = 0; $i < $NO_OF_ROOMS; $i++) {
                        for ($j = 0; $j < $booking_params['token']['token'][$i]['no_of_pax']; $j++) {
                            $pax_list = [
                                'Title' => get_enum_list('title', $room_wise_passenger_info[$i]['name_title'][$j]),
                                'FirstName' => $room_wise_passenger_info[$i]['first_name'][$j],
                                'MiddleName' => $room_wise_passenger_info[$i]['middle_name'][$j],
                                'LastName' => $room_wise_passenger_info[$i]['last_name'][$j],
                                //'Phoneno' => $room_wise_passenger_info[$i]['passenger_contact'][$j],
                                //'Email' => $room_wise_passenger_info[$i]['billing_email'][$j],
                                'Type' => $this->getPaxType($room_wise_passenger_info[$i]['passenger_type'][$j]),
                                //'LeadPassenger' => ($j == 0),
                                //'Age' => (new DateTime($room_wise_passenger_info[$i]['date_of_birth'][$j]))->diff(new DateTime('today'))->y
                            ];

                            $BookingRequest['CustomerDetails'][$i]['CustomerNames'][$j] = $pax_list;
                        }
                    }

                    // if IsVoucherBooking == false that means hold booking
                    $BookingRequest['ClientReferenceId'] = time();
                    $BookingRequest['BookingReferenceId'] = time();
                    $BookingRequest['TotalFare'] = $TotalFare;
                    $BookingRequest['EmailId'] = $room_wise_passenger_info[0]['billing_email'][0];
                    $BookingRequest['PhoneNumber'] = $room_wise_passenger_info[0]['passenger_contact'][0];
                    $BookingRequest['BookingType'] = "Voucher";
                    $BookingRequest['PaymentMode'] = "Limit";


                    //debug($BookingRequest); exit;

                    $response['status'] = true;
                    $response['data']['method'] = "Book";
                    $response['data']['request'] = json_encode($BookingRequest);
                    $response['data']['url'] = $this->ServiceUrl;

        }
                    
                //debug($response);exit;

                return $response;

    }

    function getPaxType($pax_type) 
    {
        switch ($pax_type) {
            case 1:
                return 'Adult';
            case 2:
                return 'Child';
            case 3:
                return 'Infant';
            default:
                return 'Adult'; // Optional: handle unexpected input
    }
    }

    function getPaxTypeCode($pax_type_name) 
    {
        switch ($pax_type_name) {
            case 'ADT':
                return 1;
            case 'CHD':
                return 2;
            case 'INF':
                return 3;
            default:
                return 1; // Optional: handle unexpected input
        }
    }


    /**
     * Jagnath
     * Cancellation Request:SendChangeRequest
     */
    private function cancel_booking_request_params($ConfirmationNumber) {
        $response ['status'] = true;
        $response ['data'] = array ();
        $request ['ConfirmationNumber'] = trim ( $ConfirmationNumber );
        
        $response ['data'] ['request'] = json_encode ( $request );
        $this->credentials ( 'CancelBooking' );
        $response['data']['method'] = 'Cancel';
        $response ['data'] ['url'] = $this->ServiceUrl;
        // debug($response);
        // exit;
        return $response;
    }
    /**
     * Jagnath
     * Cancellation Refund Details
     */
    private function cancellation_refund_request_params($ChangeRequestId, $app_reference) {
        $response ['status'] = true;
        $response ['data'] = array ();
        $request ['AppReference'] = trim ( $app_reference );
        $request ['ChangeRequestId'] = $ChangeRequestId;
        $response ['data'] ['request'] = json_encode ( $request );
        $this->credentials ( 'CancellationRefundDetails' );
        $response ['data'] ['service_url'] = $this->service_url;
        return $response;
    }

    /**
     * Balu A
     * get search result from tbo
     * 
     * @param number $search_id
     *          unique id which identifies search details
     */
    function get_hotel_list($search_id = '', $search_params) {

        //debug($search_params); exit;

        // error_reporting(1);
        // error_reporting(E_ALL);

        $this->CI->load->driver ( 'cache' );
        $response ['data'] = array ();
        $search_response = array ();
        $response ['status'] = true;
        $search_data = $this->search_data ( $search_id );

        //debug($search_data); exit;

        $source_id  = $search_params['booking_source'];
        
        $cache_search = $this->CI->config->item ( 'cache_hotel_search' );

        //$cache_search = true;

        $search_hash = $this->search_hash;
        $cache_contents = '';
        if ($cache_search) {
            $cache_contents = $this->CI->cache->file->get ( $search_hash );
        }       
        if ($search_data ['status'] == true) {
            if ($cache_search === false || ($cache_search === true && empty ( $cache_contents ) == true)) {
                //echo "not-cahce";
                $search_request = $this->hotel_search_request ( $search_data ['data'], $source_id );

                //debug($search_request); exit;
            
                if ($search_request ['status']) {

                    $search_response_array = array();

                    $curl_request = $this->form_curl_params($search_request['data']['request'], $search_request['data']['url'],$search_request['data']['method']);


                    //debug($curl_request); exit;
                
                    
                    // file_put_contents('logs/hotel/SearchRQ('.$search_id.').json',$curl_request['data']['request']);
                     $search_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"Search");
                     //file_put_contents('logs/hotel/SearchRS('.$search_id.').json',$search_response);
                     
                   
                  // $search_response = file_get_contents('logs/hotel/SearchRS(454).json');
                   // debug($search_response); exit;

                    $search_response_array = json_decode($search_response,true);

                    //debug($search_response); exit;

                    if ($this->valid_hotel_result($search_response_array)) {

                        $formatted_seach_result = $this->format_search_data_response($search_response_array['HotelResult'], $search_data['data'],$search_hash);

                        //debug($formatted_seach_result); exit;
                        
                        $response ['data']['HotelSearchResult'] = $formatted_seach_result['data'];

                        if ($cache_search) {
                            $cache_exp = $this->CI->config->item ( 'cache_hotel_search_ttl' );
                            $this->CI->cache->file->save ( $search_hash, $response ['data'], $cache_exp );
                        }
                        // Log Hotels Count
                        //$this->cache_result_hotel_count ( $search_response );
                    } else {
                        $response ['status'] = false;
                    }
                } else {
                    $response ['status'] = false;
                }
            } else {
                // read from cache
                //echo "cahce";
                $response ['data'] = $cache_contents;
            }
        } else {
            $response ['status'] = false;
        }

        //debug($response); exit;
        
        return $response;
    }


    function format_search_data_response($raw_htl_res,$search_params,$search_hash)
    {
        // return array
        $response['status'] = FAILURE_STATUS;
        
        // get hotel codes and currency
        $hotel_codes = $this->tbo_htl_code($raw_htl_res);

        //debug($raw_htl_res); exit;

       // $currency = $hotel_codes['data']['currency'];

        // Currency object
       // $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $currency, 'to' => get_application_display_currency_preference()));

        if(is_array($search_params) == true && $hotel_codes['status'] == true )
        {
            $cache_list = $this->get_hotel_info($hotel_codes['data']['result']);

             //debug(json_encode($cache_list));exit;
        }

        $HotelResults = [];
        $PriceOriginal = [];
        
        if (isset ( $cache_list['info'] ) && (count($cache_list['info']) > 0) && !empty ( $hotel_codes['data']['result'] ) && (count($hotel_codes['data']['result']) > 0) ) 
        {
            // Formating raw hotel list
            foreach ($raw_htl_res as $key => $value) 
            {
                $HotelStaticData = array();

                $HtlCode = $value['HotelCode'];
                $HotelStaticData = $cache_list['info'][$HtlCode];


                $RoomsData = array();
                $TotalFare = 0;
                $TotalTax = 0;

                if (isset($HotelStaticData) && count($HotelStaticData) > 0) 
                {
                    //$facility_icon=$this->htl_facilities(array_unique($facility_pre));

                    foreach ($value['Rooms'] as $rkey => $rvalue) {

                        $childCnt = 0;
                        foreach ($rvalue['Name'] as $key1 => $value1) {
                             $RoomsData[$key1] = array(
                                'HotelCode' => $HtlCode,
                                'Name' => $rvalue['Name'],
                                'BookingCode' => $rvalue['BookingCode'],
                                'Inclusion' => $value['Inclusion'],
                                'TotalFare' => $rvalue['TotalFare'],
                                'TotalTax' => $rvalue['TotalTax'],
                                'RoomPromotion' => $rvalue['RoomPromotion'],
                                'MealType' => $rvalue['MealType'],
                                'IsRefundable' => $rvalue['IsRefundable'],
                                'WithTransfers' => $rvalue['WithTransfers'],
                                'adults' => $search_params['data']['adult_config'][$key1],
                                'children' => $search_params['data']['child_config'][$key1],
                                'childrenAges' => implode(',', $childAges)
                            );
                        }
                    }

                $room_price_arr['net_price'] = $value['Rooms'][0]['TotalFare'];
                $room_price_arr['tax_price'] = $value['Rooms'][0]['TotalTax'];

                
                $HotelResults['HotelResults'][$key] = $HotelStaticData;
                $HotelResults['HotelResults'][$key]['Price'] = $this->format_price_format($room_price_arr, '', $value['Currency']);
                $HotelResults['HotelResults'][$key]['IsRefundable'] = $value['Rooms'][0]['IsRefundable'];
                $HotelResults['HotelResults'][$key]['RoomTypeName'] = $this->format_room_names($value['Rooms'][0]['Name']);
                $HotelResults['HotelResults'][$key]['Inclusion'] = $this->format_room_names($value['Rooms'][0]['Inclusion']);

                $HotelResults['HotelResults'][$key]['Rooms'] = $value['Rooms'];
                $HotelResults['HotelResults'][$key]['ResultToken'] = serialized_data($RoomsData);
                $HotelResults['HotelResults'][$key]['search_hash'] = $search_hash;

                }
            }
        }
        
        if (isset($HotelResults) && count($HotelResults) > 0) {
            $response["status"] = SUCCESS_STATUS;
            $response["data"] = $HotelResults;
        } else {
            $response["status"] = FAILURE_STATUS;
            $response["data"] = $HotelResults;
        }

    //debug(json_encode($response));exit;

        return $response;
    }


    private function tbo_htl_code($hotel_data) 
    {
        $response['status'] = FAILURE_STATUS;
        $hotel_codes    = array();
        if(count($hotel_data) > 0)
        {
            foreach ($hotel_data as $v) {
                $hotel_codes['result'][] = "'".$v['HotelCode']."'";
                $hotel_codes['currency'] = $v['Currency'];
            }
        }

        if(valid_array($hotel_codes['result']) == true){
            $response['status']             = SUCCESS_STATUS;
            $response['data']['result']     = $hotel_codes['result'];
            $response['data']['currency']   = $hotel_codes['currency'];
        }

        return $response;
    }

    public function get_hotel_info($hotel_code)
    {
        // result data
        $response           = array();
        $response['status'] = FAILURE_STATUS;

        // define array or variable
        $query          = array();
        $query_result   = array();
        $hotel_arr      = array();

        $query  = 'SELECT *  FROM tbo_master_hotel_details WHERE hotel_code IN ('.implode(',', $hotel_code).') GROUP BY id';

        $query_result   = $this->CI->db->query($query)->result_array();

        //debug($query_result); exit;
            
            if (valid_array($query_result) == true) {
                // status success
                $response['status'] = SUCCESS_STATUS;

                    // Loop the complete hotels
                    foreach ($query_result as $k => $v) {
                    
                        /*
                        $hotel_faci     = array();
                        $hotel_faci_enc = array();
                        $hotel_faci     = $this->htl_faci($v);

                        if($hotel_faci['status'] == true && isset($hotel_faci['data']['facility'][$v['hotel_code']]) && !empty($hotel_faci['data']['facility'][$v['hotel_code']])){
                            $hotel_faci_enc = json_encode($hotel_faci['data']['facility'][$v['hotel_code']]);
                        }
                        */

                        $response['info'][$v['hotel_code']] = array(
                            "IsHotDeal" => false,
                            "ResultIndex" => $k,
                            "HotelCode" => $v['hotel_code'],
                            "OrginalHotelCode" => $v['hotel_code'],
                            "HotelName" => $v['hotel_name'],
                            "HotelDescription" => $v['hotel_desc'],
                            "StarRating" => $v['star_rating'],
                            "HotelPicture" => $v['image'],
                            "HotelAddress" => $v['address'],
                            "HotelContactNo" => $v['phone_number'],
                            "Latitude" => $v['latitude'],
                            "Longitude" => $v['longitude'],
                            "HotelCategory" => "",
                            "trip_adv_url" => "",
                            "trip_rating" => $v['trip_adv_rating'],
                            "free_cancellation" => "",
                            "HotelAmenities" => $v['hotel_faci'],
                            "HotelLocation" => $v['city_name'],
                            "HotelPromotion" => 0
                        );
                    }
                                                    
            } else {
                return $response;
            }

        //debug($response);  exit;
        
        return $response;
    }


    public function get_hotel_info_images($hotel_code)
    {
        // result data
        $response           = array();
        $response['status'] = FAILURE_STATUS;

        // define array or variable
        $query          = array();
        $query_result   = array();
        $hotel_arr      = array();

        $query = "SELECT id, hotel_code, images FROM tbo_master_hotel_details WHERE hotel_code = '$hotel_code' LIMIT 1";

        $query_result   = $this->CI->db->query($query)->result_array();

        //debug($query_result); exit;
            
            if (valid_array($query_result) == true && count($query_result)>0) {
                // status success
                        $response['status'] = SUCCESS_STATUS;  
                        $response['info'][$hotel_code]['images'] = $query_result[0]['images'];                                          
            } else {
                return $response;
            }

        //debug($response);  exit;
        
        return $response;
    }

    protected function htl_faci($hotel_arr, $cols='',$filter = '')
    {

        $response = array();
        $response['status'] = FAILURE_STATUS;

        // define variable or array
        $h_cond         = "";
        $query          = "";
        $query_result   = array();
        $arr_facilities = array();

        $h_cond = 'hf.hotel_code = '.$this->CI->db->escape($hotel_arr['hotel_code']);

        if(isset($filter) && !empty($filter)) {
            $h_cond = isset($h_cond) && !empty($h_cond) ? $h_cond . ' AND hfd.icon_class != ""' : ' hfd.icon_class != ""';
        }

        if ($cols == '') {
            $cols = 'hfd.icon_class,hfd.description as name,CONCAT("_", hfd.display_class, hf.facility_code, "_") AS cstr,hf.hotel_code AS hc';
        }

        $query = ' SELECT '.$cols.'
        FROM hb_hotel_facilities AS hf JOIN hb_hotel_facilities_description AS hfd ON hf.facility_description_id=hfd.origin
        WHERE '.$h_cond;
        
        $query_result = $this->CI->db->query($query)->result_array();
    
        //group data with hotel code index and facility and desc
        if (valid_array($query_result) == true) {
            $response['status'] = SUCCESS_STATUS;
            foreach ($query_result as $k => $v) {
                if($v['icon_class'] !=""){
                    if(!in_array($v['icon_class'].$v['hc'], $arr_facilities)){
                        $arr_facilities[] = $v['icon_class'].$v['hc'];
                        $response['data']['facility'][$v['hc']][] = $v;
                    }
                }
            }
        }
        #debug($response); exit;
        return $response;
    }

    private function htl_facilities($Facilities){
        // debug($Facilities);

        if (isset($Facilities[0])) {
         

            $getFacilitiesp = [];

            for ($i = 0; $i < count($Facilities); $i++) {
               
                $facility = $Facilities[$i];


                switch ($facility) {
                    case "Bar":
                    case "Minibar":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BAR";

                        $getFacilitiesp[$i]["name"] = "Mini bar";

                        break;

                    case "Air Conditioning":
                    // case "Air conditioning":
                    case "but comfortable. All have central air conditioning":
                    case "In-room climate control (air conditioning)":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "ACC";

                        $getFacilitiesp[$i]["name"] = "Air conditioning";

                        break;

                    case "Car Parking - Onsite Free":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "CAP";

                        $getFacilitiesp[$i]["name"] =
                            "Car parking (Payable to hotel, if applicable)";

                        break;

                    case "High Speed Internet":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "INA";

                        $getFacilitiesp[$i]["name"] = "High Speed Internet";

                        break;

                    case "Room service-limited hours":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "RSR";

                        $getFacilitiesp[$i]["name"] =
                            "Room service-limited hours";

                        break;

                    case "Tour Desk":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "TOU";

                        $getFacilitiesp[$i]["name"] = "Tour Desk";

                        break;

                    case "Free WiFi":
                    
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "WIF";

                        $getFacilitiesp[$i]["name"] =
                            "Complimentary WiFi access";

                        break;

                    case "Laundry facilities":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "LSR";

                        $getFacilitiesp[$i]["name"] = "Laundry Service";

                        break;

                    case "Safety Deposit Box":
                    case "Safe-deposit box at front desk":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BOX";

                        $getFacilitiesp[$i]["name"] = "Safety Deposit Box";

                        break;

                    case "Currency exchange":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "CUR";

                        $getFacilitiesp[$i]["name"] =
                            "Foreign Currency Exchange";

                        break;


                    case "Airport shuttle available (surcharge applies)":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "AIR";

                        $getFacilitiesp[$i]["name"] =
                            "Airport shuttle available (surcharge applies)";

                        break;

                    case "Beauty Salon":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BEA";

                        $getFacilitiesp[$i]["name"] = "Beauty Salon";

                        break;

                    case "24-hour food and beverage outlet":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "FOD";

                        $getFacilitiesp[$i]["name"] =
                            "24-hour food and beverage outlet";

                        break;

                    case "Babysitting or childcare (surcharge)":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BBS";

                        $getFacilitiesp[$i]["name"] = "Babysitting or childcare (surcharge)";

                        break;

                    case "Car Parking - Onsite Paid":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "CAP";

                        $getFacilitiesp[$i]["name"] =
                            "Car Parking - Onsite Paid";

                        break;

                    case "Car Rental Desk":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "CAR";

                        $getFacilitiesp[$i]["name"] = "Car Rental Desk";

                        break;

                    case "Doctor on call":
                    case "DOCTOR":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "DOC";

                        $getFacilitiesp[$i]["name"] = "Doctor on call";

                        break;

                    case "Multilingual staff":
                    case "Multilingual":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "STF";

                        $getFacilitiesp[$i]["name"] = "Multilingual Staff";

                        break;

                    case "Nightclub":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "NCL";

                        $getFacilitiesp[$i]["name"] = "Night Club";

                        break;

                    case "Porterage":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "POR";

                        $getFacilitiesp[$i]["name"] = "Porterage";

                        break;

                    case "Banquet hall":
                    case "Meeting/Banquet Facilities":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "HALL";

                        $getFacilitiesp[$i]["name"] = "Banquet Hall";

                        break;

                    case "Room service - 24 hours":
                    case "Room Service":
                    case "ROOM24":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "RSR";

                        $getFacilitiesp[$i]["name"] = "Room Service";

                        break;

                    case "Wheelchairs available on site":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "WCA";

                        $getFacilitiesp[$i]["name"] = "Wheelchairs available on site";

                        break;

                    case "Business center":
                    case "24-hour business center":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BUS";

                        $getFacilitiesp[$i]["name"] = "Business Centre";

                        break;

                    case "Concierge":
                    case "Concierge services":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "CONC";

                        $getFacilitiesp[$i]["name"] = "Concierge";

                        break;

                    case "Bicycle rentals":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BICY";

                        $getFacilitiesp[$i]["name"] = "Bicycle rentals";

                        break;

                    case "Free valet parking":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "VALE";

                        $getFacilitiesp[$i]["name"] = "Free valet parking";

                        break;

                    case "elevator":
                    
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "ELEV";

                        $getFacilitiesp[$i]["name"] = "Elevators";

                        break;

                    case "Library":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "LIBR";

                        $getFacilitiesp[$i]["name"] = "Library";

                        break;

                    case "Arcade/game room":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "ARCA";

                        $getFacilitiesp[$i]["name"] = "Shopping Arcade";

                        break;

                    case "Express check-out":
                    case "Express Check Out":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "EAR";

                        $getFacilitiesp[$i]["name"] = "Express check-out";

                        break;

                    case "Daily housekeeping":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "HOUS";

                        $getFacilitiesp[$i]["name"] = "Housekeeping-daily";

                        break;

                    case "Express check-in":
                    case "Express Check In":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "EAR";

                        $getFacilitiesp[$i]["name"] = "Express check-in";

                        break;

                    case "Hair Dresser":
                    case "Hair dryer":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "HDS";

                        $getFacilitiesp[$i]["name"] = "Hair Dresser";

                        break;

                    case "Halal food available":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "HALAL";

                        $getFacilitiesp[$i]["name"] = "Halal food available";

                        break;

                    case "Ramp access":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "Ramp";

                        $getFacilitiesp[$i]["name"] = "Ramp access";

                        break;

                    case "Airport transportation (surcharge)":
                    
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "AIR";

                        $getFacilitiesp[$i]["name"] = "Airport Shuttle - Free";

                        break;

                    case "Beauty Salon":
                    case "Barber/Beauty Shop":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BEA";

                        $getFacilitiesp[$i]["name"] = "Beauty Salon";

                        break;

                    case "Florist":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "FLOR";

                        $getFacilitiesp[$i]["name"] = "Florist";

                        break;

                    case "Barber shop":
                    case "Bar/lounge":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "BARB";

                        $getFacilitiesp[$i]["name"] = "Barber shop";

                        break;

                    case "Coffee shop or café":
                   
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "TCM";

                        $getFacilitiesp[$i]["name"] =
                            "Coffee/tea making facilities";

                        break;                    
                    case "Free self parking":
                  
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "CAP";

                        $getFacilitiesp[$i]["name"] = "Free self parking";

                        break;

                    case "24-hour food and beverage outlet":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "FOD";

                        $getFacilitiesp[$i]["name"] =
                            "24-hour food and beverage outlet";

                        break;

                    case "Drugstore/pharmacy":
                        $getFacilitiesp[$i]["cstr"] = $getFacilitiesp[$i][
                            "icon_class"
                        ] = "phar";

                        $getFacilitiesp[$i]["name"] = "Drugstore/pharmacy";

                        break;
                }
            }
            // debug($getFacilitiesp);


            return $getFacilitiesp;
        }
    } 

    function format_media_result_response($hotel_media_response)
    {

        //debug($hotel_media_response); exit;

        $hotel_media_response_data =  force_multple_data_format($hotel_media_response['SOAP:Envelope']['SOAP:Body']['hotel:HotelMediaLinksRsp']['hotel:HotelPropertyWithMediaItems']['common_v52_0:MediaItem']);

        //debug($hotel_media_response_data); exit();

        $formatted_data = array();

        foreach ($hotel_media_response_data as $item) {
            $attr = $item['@attributes'];

            if (isset($attr['type'], $attr['sizeCode']) && $attr['sizeCode'] == 'E' ) {
                $formatted_data[] = [
                    'type' => $attr['type'],
                    'url' => $attr['url'],
                    'sizeCode' => $attr['sizeCode'],
                ];
            }
        }

        $formatted_data = array_slice($formatted_data, 0, 20);
        $urls = array_column($formatted_data, 'url');

        return $urls;
    }


    function getHotelTransportation($transportCode)
    {

        switch ($transportCode) {
            case 1:
                $transportType = 'Bus';
                break;
            case 2:
                $transportType = 'Public';
                break;
            case 3:
                $transportType = 'Courtesy Car/Bus';
                break;
            case 4:
                $transportType = 'Limousine';
                break;
            case 5:
                $transportType = 'RentalCar';
                break;
            case 6:
                $transportType = 'SubwayRail';
                break;
            case 7:
                $transportType = 'Taxi';
                break;
            case 8:
                $transportType = 'Walk';
                break;
            case 9:
                $transportType = 'Other or alternate';
                break;
            default:
                $transportType = 'Unknown';
                break;
        }

        return $transportType;

    }


    private function format_price_format($room_price_arr, $room_count = '', $API_Currency)
    {      
        $updated_price_array = array();
         $offerPrice = 0;
         $pay_at_destination_price = 0;

        $tax = ($room_price_arr['tax_price']);
        $room_price = $room_price_arr['net_price'];

        $price_array = [];
        //calculating price per night wise
        //$room_price = roundoff_number($room_price/$no_of_nights);
        $price_array['PublishedPrice'] = $room_price;
        $price_array['PublishedPriceRoundedOff'] = roundoff_number($room_price);
        $price_array['OfferedPrice'] = $room_price;
        $price_array['OfferedPriceRoundedOff'] = roundoff_number($room_price);
        $price_array['RoomPrice'] = $room_price;
        $price_array['Tax'] = $tax;
        $price_array['DestinationTax'] = $pay_at_destination_price;
        $price_array['ExtraGuestCharge'] = 0;
        $price_array['ChildCharge'] = 0;
        $price_array['OtherCharges'] = 0;
        $price_array['Discount'] = $offerPrice;
        $price_array['AgentCommission'] = 0;
        $price_array['AgentMarkUp'] = 0;
        $price_array['ServiceTax'] = 0;
        $price_array['TDS'] = 0;
        //for gst price we are adding        
        $price_array['RoomPriceWoGST'] = $room_price;
        $price_array['GSTPrice'] = 0;


        $currency_obj = new Currency ( array (
                            'module_type' => 'hotel',
                            'from' => $API_Currency,
                            'to' => admin_base_currency() 
                    ) );

        //debug($currency_obj); exit;

        $updated_price_array = $this->preferred_currency_fare_object ($price_array, $currency_obj,admin_base_currency());

        return $updated_price_array;
    }




    /**
     * Elavarasi
     * get search result from tbo
     * 
     * @param number $search_id
     *          unique id which identifies search details
     */
    function get_hotel_image_list($city_code,$country_code) {
        
        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = true;
        //$search_data = $this->search_data ( $search_id );
        
        //$search_request = $this->hotel_search_request ( $search_data ['data'] );
        $search_request['status'] = true;
        $request_arr = [];
        $request_arr['destination_code'] = $city_code;
        $request_arr['checkin'] = '2018-02-01';
        $request_arr['checkout'] = '2018-02-02';
        
        $request_arr['client_nationality'] = $country_code;
        $request_arr['hotel_info'] = false;
        $request_arr['rooms'] =array(array('adults'=>1,'children_ages'=>array()));
        
        $search_request['data']['request'] = json_encode($request_arr);
        
        $search_request['data']['url'] = $this->Url;
        if ($search_request ['status']) {
            
            $search_response = $GLOBALS ['CI']->api_interface->get_json_image_response( $search_request ['data'] ['service_url'], $search_request ['data'] ['request'], $header ,'post');
            
            
            if (!isset($search_response['errors'])) {
                $response ['data'] = $search_response['hotels'];
                $response ['status'] = true;
                
            } else {
                $response ['status'] = false;
                $response ['data'] = $search_response['errors'];
            }
        
        }
        
        return $response;
    }
    /**
    *Get Hotel Booking Status
    */
    public function get_hotel_booking_status($app_reference){
        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = true;
        //UpdateHoldBooking
        $this->credentials('UpdateHoldBooking');
         $service_url = $this->service_url;
         if($app_reference !=''){
            $get_hold_booking_request = array('app_reference'=>$app_reference);
            $request = json_encode($get_hold_booking_request);
            $GLOBALS ['CI']->custom_db->generate_static_response ($request); // release this
            $get_hb_status = $GLOBALS['CI']->api_interface->get_json_response ( $service_url,$request, $header );
            
            $GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $get_hb_status ) );
            if($get_hb_status['Status']==true){
                
                //update booking status
                $booking_id = $get_hb_status['UpdateHoldBooking']['booking_id'];
                $update_data['status'] = 'BOOKING_CONFIRMED';
                $update_data['booking_id'] = $booking_id;
                $this->CI->custom_db->update_record('hotel_booking_details',$update_data,array('app_reference'=>$app_reference));
                $update_ite_data['status'] = 'BOOKING_CONFIRMED';
                $this->CI->custom_db->update_record('hotel_booking_itinerary_details',$update_ite_data,array('app_reference'=>$app_reference));
                $this->CI->custom_db->update_record('hotel_booking_pax_details',$update_ite_data,array('app_reference'=>$app_reference));
                $response ['data'] = array('booking_id'=>$booking_id);
                $response['status'] = true;
                
            }else{
                $response['status'] = false;
            }
         }
         return $response;
    }
    /**
     * Converts API data currency to preferred currency
     * Balu A
     * 
     * @param unknown_type $search_result           
     * @param unknown_type $currency_obj            
     */
    public function search_data_in_preferred_currency($search_result, $currency_obj,$search_id) {
        $hotels = $search_result ['data'] ['HotelSearchResult'] ['HotelResults'];
        $hotel_list = array ();
        foreach ( $hotels as $hk => $hv ) {
            $hotel_list [$hk] = $hv;
            //Update Markup price in search result          
            
            //$Price =  $this->update_search_markup_currency ($hv ['Price'], $currency_obj, $search_id, false, true );  
            $hotel_list [$hk] ['Price'] = $this->preferred_currency_fare_object ($hv ['Price'], $currency_obj );    
            
        }
        $search_result ['data'] ['HotelSearchResult'] ['PreferredCurrency'] = get_application_currency_preference ();
        $search_result ['data'] ['HotelSearchResult'] ['HotelResults'] = $hotel_list;
        
        return $search_result;
    }
    /**
     * Balu A
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     */
    private function preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '') {
        $price_details = array ();
        
        $price_details ['CurrencyCode'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();
        $price_details ['RoomPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['RoomPrice'] ) );
        $price_details ['Tax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Tax'] ) );
        $price_details ['ExtraGuestCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ExtraGuestCharge'] ) );
        $price_details ['ChildCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ChildCharge'] ) );
        $price_details ['OtherCharges'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OtherCharges'] ) );
        $price_details ['Discount'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Discount'] ) );
        $price_details ['PublishedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPrice'] ) );
        $price_details ['PublishedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPriceRoundedOff'] ) );
        $price_details ['OfferedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPrice'] ) );
        $price_details ['OfferedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPriceRoundedOff'] ) );
        $price_details ['AgentCommission'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['AgentCommission'] ) );
        $price_details ['AgentMarkUp'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['AgentMarkUp'] ) );
        $price_details ['ServiceTax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ServiceTax'] ) );
        $price_details ['TDS'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TDS'] ) );

        return $price_details;
    }
    /**
     * Balu A
     * Converts Display currency to application currency
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     * @param unknown_type $module          
     */
    public function convert_token_to_application_currency($token, $currency_obj, $module) {
        $master_token = array ();
        $price_token = array ();
        $price_summary = array ();
        $markup_price_summary = array ();
        // Price Token
        foreach ( $token ['price_token'] as $ptk => $ptv ) {
            $price_token [$ptk] = $this->preferred_currency_fare_object ( $ptv, $currency_obj, admin_base_currency () );
        }
        // Price Summary
        $price_summary = $this->preferred_currency_price_summary ( $token ['price_summary'], $currency_obj );
        // Markup Price Summary
        $markup_price_summary = $this->preferred_currency_price_summary ( $token ['markup_price_summary'], $currency_obj );
        // Assigning the Converted Data
        $master_token = $token;
        $master_token ['price_token'] = $price_token;
        $master_token ['price_summary'] = $price_summary;
        $master_token ['markup_price_summary'] = $markup_price_summary;
        $master_token ['default_currency'] = admin_base_currency ();
        $master_token ['convenience_fees'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $token ['convenience_fees'] ) ); // check this
        return $master_token;
    }
    /**
     * Balu A
     * Converts Price summary to application curency
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     */
    private function preferred_currency_price_summary($fare_details, $currency_obj) {
        $price_details = array ();
        $price_details ['RoomPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['RoomPrice'] ) );
        $price_details ['PublishedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPrice'] ) );
        $price_details ['PublishedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPriceRoundedOff'] ) );
        $price_details ['OfferedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPrice'] ) );
        $price_details ['OfferedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPriceRoundedOff'] ) );
        $price_details ['ServiceTax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ServiceTax'] ) );
        $price_details ['Tax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Tax'] ) );
        $price_details ['ExtraGuestCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ExtraGuestCharge'] ) );
        $price_details ['ChildCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ChildCharge'] ) );
        $price_details ['OtherCharges'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OtherCharges'] ) );
        $price_details ['TDS'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TDS'] ) );
        return $price_details;
    }
    function cache_result_hotel_count($response) {
        $CI = & get_instance ();
        $city_id = @$response['Search']['HotelSearchResult'] ['CityId'];
        $hotel_count = intval ( count ( @$response['Search']['HotelSearchResult'] ['HotelResults'] ) );
        if ($hotel_count > 0 && $city_id !='') {            
            $CI->custom_db->update_record ('all_api_city_master', array (
                    'cache_hotels_count' => $hotel_count 
            ), array (
                    'origin' => $city_id 
            ) );
        }
    }
    /**
    *Get cancellation details by cancellation policy code (GRN CONNECT)
    */
    public function get_cancellation_details($get_params)
    {
        $cancellation_details['status'] = false;
        $cancellation_details['data'] = array();

        if($get_params){
            
            //$request_rate_data = json_encode($get_params);

            if(!empty($get_params['policy_details']) && !empty($get_params['tb_search_id']))
            {
                $search_id = abs($get_params['tb_search_id']);
                $search_params = $this->search_data($search_id);

                $fare_rule_request_data = $this->get_fare_rule_request($get_params, $search_params);


                if ($fare_rule_request_data ['status']) {

                    $fare_rule_response_data_array = array();

                    $curl_request = $this->form_curl_params($fare_rule_request_data['data']['request'], $fare_rule_request_data['data']['url']);

                    //debug($curl_request); exit;

                    
                    file_put_contents('logs/hotel/HotelRulesReq('.$search_id.').xml',$curl_request['data']['request']);
                    $fare_rule_response = $this->CI->api_interface->get_json_response_tpt($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header']);
                    file_put_contents('logs/hotel/HotelRulesRsp('.$search_id.').xml',$fare_rule_response);
                    
                   
                    //debug($fare_rule_response); exit;
                    

                    //$fare_rule_response = file_get_contents('logs/hotel/HotelRulesRsp(6034).xml');

                    $fare_rule_response_data_array = Converter::createArray($fare_rule_response);


                     if($this->valid_hotel_rules($fare_rule_response_data_array))
                     {
                        $formatted_data = $this->format_fare_rule_response($fare_rule_response_data_array);
                     }

                     $cancel_string = "";

                     if($formatted_data['status']==true)
                     {

                        //debug($formatted_data); exit;

                        foreach ($formatted_data['data'] as $sectionTitle => $items) {

                        $cancel_string .= '<p class="policy_text"><strong>' . $sectionTitle . '</strong></p><ul>';

                        if(is_array($items)) {
                            foreach ($items as $item) {
                            $cancel_string .= "<li>" . htmlspecialchars($item) . "</li>";
                        }
                        }
                        else
                        {
                            $cancel_string .= "<li>" . htmlspecialchars($items) . "</li>";
                        }
                        
                        $cancel_string .= "</ul>";
                        }

                        $cancellation_details['data'] = $cancel_string;
                     }

                     //debug($fare_rule_request_data_array); exit;                  
                
                return $cancellation_details;
            }

        
            //echo $request_rate_data;
        }
    }
}

    function get_fare_rule_request($hotel_params, $search_params)
    {
        $hotel_data = unserialized_data($hotel_params['policy_details']);

        $FareRuleRequest = "";
        $response = array();

        // debug($hotel_params); 
        // debug($hotel_data); exit;


        if(is_array($hotel_data) && count($hotel_data)>0  && is_array($search_params))
        {
            $firstHotel = reset($hotel_data);
            $TraceId = $firstHotel['TraceId'];
            $FareRuleRequest .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                       <soapenv:Header/>
                       <soapenv:Body>
                          <hotel:HotelRulesReq AuthorizedBy="'.$this->UserName.'" TargetBranch="'.$this->Target.'"  TraceId="'.$TraceId.'" xmlns:hotel="http://www.travelport.com/schema/hotel_v50_0" xmlns:com="http://www.travelport.com/schema/common_v50_0">
                             <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>';
                            
                                $from_date = DateTime::createFromFormat('d/m/Y', $search_params['data']['from_date']);
                                $to_date = DateTime::createFromFormat('d/m/Y', $search_params['data']['to_date']);
                                $check_in = $from_date->format('Y-m-d');
                                $check_out = $to_date->format('Y-m-d');

                                foreach($hotel_data as $hd){

                             $FareRuleRequest .='<hotel:HotelRulesLookup RatePlanType="'.$hd['RatePlanType'].'" Base="'.$hd['Base'].'" Total="'.$hd['Total'].'">
                                <hotel:HotelProperty HotelChain="'.$hotel_params['rate_key'].'" HotelCode="'.$hotel_params['hotel_code'].'" >
                                </hotel:HotelProperty>';
                                 }

                            $FareRuleRequest .='<hotel:HotelStay><hotel:CheckinDate>'.$check_in.'</hotel:CheckinDate>
                           <hotel:CheckoutDate>'.$check_out.'</hotel:CheckoutDate>
                                </hotel:HotelStay>
                                <hotel:HotelRulesModifiers NumberOfAdults="'.array_sum($search_params['data']['adult_config']).'" NumberOfChildren="'.array_sum($search_params['data']['child_config']).'" />
                             </hotel:HotelRulesLookup>
                          </hotel:HotelRulesReq>
                       </soapenv:Body>
                    </soapenv:Envelope>';

                //debug($FareRuleRequest); exit;

                $response ['status'] = true;
                $response ['data'] = array ();
                $response ['data'] ['request'] = $FareRuleRequest;
                $response ['data'] ['url'] = $this->Url;
                return $response;

            }

            $response ['status'] = false;
            $response ['data'] = array ();
            $response ['data'] ['request'] = $FareRuleRequest;
            $response ['data'] ['url'] = $this->Url;
            return $response;
    }
    /**
     * Balu A
     * get Room List for selected hotel
     * 
     * @param string $TraceId           
     * @param number $ResultIndex           
     * @param string $HotelCode         
     */
    function get_room_list($ResultToken) {
        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = false;
        $hotel_room_request = $this->room_list_request ($ResultToken);
        if ($hotel_room_request ['status']) {
            // get the response for hotel details
            $hotel_room_list_response = $GLOBALS ['CI']->api_interface->get_json_response ( $hotel_room_request ['data'] ['service_url'], $hotel_room_request ['data'] ['request'], $header );
            $GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $hotel_room_list_response ) );
            // debug($hotel_room_list_response);exit;
            /*
             * $static_search_result_id = 813;//106;//68;//52;
             * $hotel_room_list_response = $GLOBALS['CI']->hotel_model->get_static_response($static_search_result_id);
             */
            if ($this->valid_room_details_details ( $hotel_room_list_response )) {
                $response ['data'] = $hotel_room_list_response['RoomList'];
                $response ['status'] = true;
            } else {
                $response ['data'] = $hotel_room_list_response;
            }
        }
        return $response;
    }
    /**
     * Balu A
     * 
     * @param unknown_type $room_list           
     * @param unknown_type $currency_obj            
     */
    public function roomlist_in_preferred_currency($room_list, $currency_obj,$search_id,$module='b2c') {
        $level_one = true;
        $current_domain = true;
        if ($module == 'b2c') {
            $level_one = false;
            $current_domain = true;
        } else if ($module == 'b2b') {
            $level_one = true;
            $current_domain = true;
        }
        $application_currency_preference = get_application_currency_preference ();
        $hotel_room_details = $room_list['HotelRoomsDetails'];
        $hotel_room_result = array ();
        foreach ( $hotel_room_details as $hr_k => $hr_v ) {
            $hotel_room_result [$hr_k] = $hr_v;
            // Price
            $API_raw_price = $hr_v ['Price'];
            
            $Price = $this->preferred_currency_fare_object ( $hr_v ['Price'], $currency_obj );
            // CancellationPolicies
            $CancellationPolicies = array ();
            foreach ( $hr_v ['CancellationPolicies'] as $ck => $cv ) {
                //add cancellation charge in markup
                
                $Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain);
                
                $CancellationPolicies [$ck] = $cv;
                $CancellationPolicies [$ck] ['Currency'] = $application_currency_preference;
                //$CancellationPolicies [$ck] ['Charge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $Charge ) );
                $CancellationPolicies [$ck] ['Charge'] = $Charge;
            }
            $hotel_room_result [$hr_k] ['API_raw_price'] = $API_raw_price;
            $hotel_room_result [$hr_k] ['Price'] = $Price;
            $hotel_room_result [$hr_k] ['CancellationPolicies'] = $CancellationPolicies;
            // CancellationPolicy:FIXME: convert the INR price to preferred currency
        }
        $room_list['HotelRoomsDetails'] = $hotel_room_result;
        return $room_list;
    }
    /**
     * Balu A
     * 
     * @param unknown_type $block_room_data         
     * @param unknown_type $currency_obj            
     */
    public function roomblock_data_in_preferred_currency($block_room_data, $currency_obj,$search_id,$module='b2c') {


        //debug($block_room_data); exit;
        //debug($block_room_data ['data'] ['response'] ['BlockRoomResult'] ['HotelRoomsDetails']); exit;

        $level_one = true;
        $current_domain = true;
        if ($module == 'b2c') {
            $level_one = false;
            $current_domain = true;
        } else if ($module == 'b2b') {
            $level_one = true;
            $current_domain = true;
        }
        $application_currency_preference = get_application_currency_preference ();
        $hotel_room_details = $block_room_data ['data'] ['response'] ['BlockRoomResult'] ['HotelRoomsDetails'];
        $hotel_room_result = array ();

        //debug($hotel_room_details); exit;

        foreach ( $hotel_room_details as $hr_k => $hr_v ) {
            $hotel_room_result [$hr_k] = $hr_v;
            
            // Price
            $API_raw_price = $hr_v ['Price'];
            $Price = $this->preferred_currency_fare_object ( $hr_v ['Price'], $currency_obj );
            // CancellationPolicies
            $CancellationPolicies = array ();
            foreach ( $hr_v['CancellationPolicies'] as $ck => $cv ) {

                if(isset($cv['Charge']))
                {
                $Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain);
                
                $CancellationPolicies [$ck] = $cv;
                $CancellationPolicies [$ck] ['Currency'] = $application_currency_preference;
                $CancellationPolicies [$ck] ['Charge'] = $Charge ;
                //$CancellationPolicies [$ck] ['Charge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $Charge ) );
                }
            }
            $hotel_room_result [$hr_k] ['API_raw_price'] = $API_raw_price;
            $hotel_room_result [$hr_k] ['Price'] = $Price;
            $hotel_room_result [$hr_k] ['CancellationPolicies'] = $CancellationPolicies;
            // CancellationPolicy:FIXME: convert the INR price to preferred currency
        }

        $block_room_data ['data'] ['response'] ['BlockRoomResult'] ['HotelRoomsDetails'] = $hotel_room_result;
        
        //debug($block_room_data); exit;

        return $block_room_data;
    }
    /**
     * Balu A
     * Load Hotel Details
     *
     * @param string $TraceId
     *          Trace ID of hotel found in search result response
     * @param number $ResultIndex
     *          Result index generated for each hotel by hotel search
     * @param string $HotelCode
     *          unique id which identifies hotel
     *          
     * @return array having status of the operation and resulting data in case if operaiton is successfull
     */
    function get_hotel_details($ResultIndex,$search_hash,$search_id) {

         // error_reporting(E_ALL); // Report all errors
         // ini_set('display_errors', 1);

        $response ['data'] = array ();
        $response ['status'] = false;

        $this->CI->load->driver('cache');

        $search_params = $this->search_data($search_id);

        $cache_contents = $this->CI->cache->file->get ( $search_hash );

        $search_data = $search_params['data'];

        $hotel_details_request = $this->hotel_details_request($ResultIndex,$search_data);

        //debug($cache_contents); exit;

        if ($hotel_details_request ['status']) 
        {
            
            $hotel_details_response_data = array();

            $curl_request = $this->form_curl_params($hotel_details_request['data']['request'],$hotel_details_request['data']['url'],$hotel_details_request['data']['method']);

            //debug($curl_request); exit;
        
            
            //file_put_contents('logs/hotel/HotelDetailsReq('.$search_id.').json',$curl_request['data']['request']);
            $hotel_details_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"HotelDetails");
            //file_put_contents('logs/hotel/HotelDetailsRsp('.$search_id.').json',$hotel_details_response);    
            //debug($hotel_details_response); exit;
            
            
             //$hotel_details_response = file_get_contents('logs/hotel/HotelDetailsRsp(320).json');

             $hotel_details_response_data = json_decode($hotel_details_response,true);

             //debug($hotel_details_response_data); exit;

            if ($this->valid_hotel_details( $hotel_details_response_data ) && empty($cache_contents) == false ) {

                $formatted_hotel_details_result = $this->format_hotel_details_response($hotel_details_response_data,$search_data,$cache_contents);

               // debug($formatted_hotel_details_result); exit;


                $response ['data'] = $formatted_hotel_details_result['data'];
                $response ['status'] = true;
            } else {
                $response ['data'] = $formatted_hotel_details_result;
            }
        }
        return $response;
    }


    private function format_hotel_details_response($hotel_details_response,$search_data, $search_result_data)
    {

        //debug($search_result_data);exit;
        $response['status'] = FAILURE_STATUS;
        
        //debug($search_data); exit;

        $raw_hotel_details_response = $hotel_details_response['HotelDetails'];

        $search_hotel_details_response = $search_result_data['HotelSearchResult']['HotelResults'];

        //debug($search_hotel_details_response); exit;


        // set currency 
        $no_of_nights = $search_params ['no_of_nights'];

        $checkin_date = $search_data['from_date'];
        $checkout_date = $search_data['to_date'];

        if(isset($hotel_details_response['HotelDetails']) && valid_array($hotel_details_response['HotelDetails']) && valid_array($search_hotel_details_response)) {

            $hotel_code = $hotel_details_response['HotelDetails'][0]['HotelCode'];

            $hotel_cache_data = array_values(array_filter($search_hotel_details_response, function($hotel) use ($hotel_code) {
                return $hotel['HotelCode'] == $hotel_code;
            }));

            //debug($hotel_cache_data); exit;

            $Images = array();
            $HotelDetailsArray = array();
            $HotelRoomsDetailArray = array();

            $cache_images_list = $this->get_hotel_info_images($hotel_code);

            if($cache_images_list['status']==true)
            {
                $Images = json_decode($cache_images_list['info'][$hotel_code]['images'],true);
            }

            // Formating raw hotel list
            foreach($raw_hotel_details_response as $key => $hotel_detail)
            {

                //debug($hotel_detail); exit;

                $hkey  = $hotel_detail['HotelCode']; 

                $HotelDetailsArray['Images'] = $Images;
                $HotelDetailsArray['checkin'] = $checkin_date;
                $HotelDetailsArray['checkout'] = $checkout_date;
                $HotelDetailsArray['HotelName'] = $hotel_detail['HotelName'];
                $HotelDetailsArray['HotelCode'] = $hkey;
                $HotelDetailsArray['StarRating'] = $hotel_detail['HotelRating'];
                $HotelDetailsArray['Address'] = $hotel_detail['Address'];
                $HotelDetailsArray['HotelFacilities'] = $hotel_detail['HotelFacilities'];
                $HotelDetailsArray['HotelFacilitiesByCategory'] = array();
                $HotelDetailsArray['Description'] = $hotel_detail['Description'];

                $Maps = $hotel_detail['Map'];
                $coordinates = explode("|",$Maps);   

                $HotelDetailsArray['Latitude'] = "";
                $HotelDetailsArray['Longitude'] = "";

                if(is_array($coordinates) && count($coordinates)>0)
                {
                    $HotelDetailsArray['Latitude'] = $coordinates[0];
                    $HotelDetailsArray['Longitude'] = $coordinates[1];
                }


                $HotelDetailsArray['Attractions'] = $hotel_detail['Attractions'];
                $HotelDetailsArray['Landmarks'] = array();
                $HotelDetailsArray['checkInTime'] = $hotel_detail['CheckInTime'];
                $HotelDetailsArray['checkOutTime'] = $hotel_detail['CheckOutTime'];
                $HotelDetailsArray['totalRooms'] = $search_data['room_count'];
                $HotelDetailsArray['totalFloors'] = 0;


                if (isset($hotel_cache_data[$key]) == true) { 

                    
                    if(isset($hotel_cache_data[$key]['Rooms']) && valid_array($hotel_cache_data[$key]['Rooms'])) { 

                        foreach($hotel_cache_data[$key]['Rooms'] as $r_key => $rooms) 
                        {   
                            //debug($rooms); exit;

                            $room   = array();

                            $room['RoomIndex'] = $r_key;

                            $room['HotelCode'] = $hotel_detail['HotelCode'];

                            // Room name,xml_currency,xml_net
                            $room['RoomTypeName']           = $this->format_room_names($rooms['Name']);
                            $room['AccommodationType']   = 'Room';
                            $room['booking_code']   = $rooms['BookingCode'];
                            $room['RateType']   = $rooms['BookingCode'];
                            $room['Inclusion']        = $TotalFare['Inclusion'];
                            $room['RoomImage']        = "";
                            $room['ChildCount']        = $child_count;

                            $room_price_arr = [
                            'net_price' => $rooms['TotalFare'],
                            'tax_price' => $rooms['TotalTax'],
                            'pay_at_destination_price' => 0
                                ];
                            $api_currency =  "USD";

                           //debug($rooms); 

                            $currency_obj = new Currency(array('module_type' => 'hotel','from' =>  $api_currency, 'to' => get_application_currency_preference()));

                            $room['Price'] = $this->format_price_format($room_price_arr, '', $api_currency);
                            
                           // debug($room['Price']);

                            // Room add markup 
                            $room['MealPlans']   = $rooms['MealType'];
                            $room['RoomPromotion']   = $rooms['RoomPromotion'];
                            $room['IsRefundable']        = $rooms['IsRefundable'];
                            $room['WithTransfers']        = $rooms['WithTransfers'];
                            $room['SmokingPreference']        = "";
                            $room['RatePlanCode']        = "";
                            $room['RoomTypeCode']        = "";
                            $room['CategoryId']        = "";
                            $room['Amenities']        = ""; 
                            $room['OtherAmennities']        = "";

                            if(isset($rooms['CancelPolicies']) && valid_array($rooms['CancelPolicies'])) { 
                                foreach($rooms['CancelPolicies'] as $cel_key => $cancel_policy) {
                                    //cancellationpolicy
                                    $cancel_policy['FromDate'] = date("Y-m-d H:i:s",strtotime($cancel_policy['FromDate']));
                                    $date_now = date("Y-m-d H:i:s");
                                    $c_date =  $cancel_policy['FromDate'];
                                 if ($date_now < $c_date) 
                                 {
                                    if ($module == 'b2c') {
                                        //Convert to default currency and add mark up
                                        $converted_cncel_price = $currency_obj->get_currency ($cancel_policy['CancellationCharge'], true, false, true, 1 ); // (ON Total PRICE ONLY)
                                    } else {
                                        // B2B Calculation 
                                        $converted_cncel_price = $currency_obj->get_currency ($cancel_policy['CancellationCharge'], true, true, true, 1 ); // (ON Total PRICE ONLY)
                                    }
                                    
                                    $room['cancellationPolicies'][$cel_key]['CancellationCharge']   = sprintf("%.2f",$converted_cncel_price['default_value']);
                                    $room['cancellationPolicies'][$cel_key]['FromDate']     = $cancel_policy['from'];
                                    $room['cancellationPolicies'][$cel_key]['FromDate']=date('D d M Y H:i:s', strtotime('-48 hours', strtotime($room['cancellationPolicies'][$cel_key]['FromDate'])));
           
                                 }
                                }
                            }



                            $room['cancellation_policy_code']        = "";
                            $room['LastCancellationDate']        = "";
                            $room['CancellationPolicy']        = "";    

                            $room['RoomUniqueId'] = serialized_data($room);

                            $HotelRoomsDetailArray[$r_key]  = $room;
                        
                        }
                    }
                }
            }
        }

        //debug($HotelRoomsDetailArray); exit;
        
        $firstRoom = $HotelRoomsDetailArray[0] ?? [];

        $HotelDetailsArray['first_room_details']['Price'] = $firstRoom['Price'] ?? '';
        $HotelDetailsArray['first_room_details']['room_name'] = $firstRoom['RoomTypeName'] ?? '';
        $HotelDetailsArray['first_room_details']['LastCancellationDate'] = $firstRoom['LastCancellationDate'] ?? '';
        $HotelDetailsArray['first_room_details']['Room_data']['RoomUniqueId'] = $firstRoom['RoomUniqueId'] ?? '';
        $HotelDetailsArray['Amenities'] = [];
        $HotelDetailsArray['trip_adv_url'] = "";
        $HotelDetailsArray['trip_rating'] = "";

        $HotelDetailsData['HotelInfoResult'] = [
            'HotelDetails' => $HotelDetailsArray,
            'HotelPolicy' => [],
            'HotelPolicyByCategory' => [],
            'Rooms' => ['HotelRoomsDetails' => $HotelRoomsDetailArray]
        ];

        $response = [
            'status' => true,
            'data' => $HotelDetailsData
        ];

        //debug($response); exit;

        return $response;
    }



    private function format_fare_rule_response($hotel_fare_rule_response)
    {
        $HotelRateDetailData = array();

        $response['status'] = false;
        $response['data'] =  array();

        $HotelRulesResponseData = $hotel_fare_rule_response['SOAP:Envelope']['SOAP:Body']['hotel:HotelRulesRsp'];

        $HotelRateDetail = $HotelRulesResponseData['hotel:HotelRateDetail'];
        $RoomRateDescription = $HotelRateDetail['hotel:RoomRateDescription'];

        //debug($HotelRateDetail); exit;

        $HotelRuleItem = force_multple_data_format($HotelRulesResponseData['hotel:HotelRuleItem']);

        $cancellation_details = array();

        //debug($HotelRuleItem); exit;


        if(is_array($HotelRuleItem)==true && count($HotelRuleItem)>0)
        {
            foreach($HotelRuleItem as $rulesData)
            {
                    $HotelRateDetailData[$rulesData['@attributes']['Name']] = $rulesData['hotel:Text'];
                
            }

            $response['status'] = true;
            $response['data'] =  $HotelRateDetailData;
        }


        return $response;
        
    }
    
    /**
     * Balu A
     * Block Room Before Going for payment and showing final booking page to user - TBO rule
     * 
     * @param array $pre_booking_params
     *          All the necessary data required in block room request - fetched from roomList and hotelDetails Request
     */
    function block_room($pre_booking_params) {

        // error_reporting(E_ALL); // Report all errors
        //  ini_set('display_errors', 1);

        $response ['status'] = false;
        $response ['data'] = array ();

        $search_id =  $pre_booking_params ['search_id'];
        $search_params = $this->search_data ($search_id);

        $run_block_room_request = true;
        $block_room_request_count = 0;

        $block_room_request = $this->get_block_room_request ($pre_booking_params );


        $application_default_currency = admin_base_currency ();
        if ($block_room_request ['status'] == ACTIVE) 
        {
            $block_room_response_data = array();
            $curl_request = $this->form_curl_params($block_room_request['data']['request'], $block_room_request['data']['url'],$block_room_request['data']['method']);

            //debug($curl_request); exit;
            
          //  file_put_contents('logs/hotel/PreBookRQ('.$search_id.').json',$curl_request['data']['request']);
            $block_room_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"PreBook(Block Room)");
          //  file_put_contents('logs/hotel/PreBookRS('.$search_id.').json',$block_room_response);
        

            //debug($block_room_response); exit;
            

            //$block_room_response = file_get_contents('logs/hotel/PreBookRS(278).json');

            $block_room_response_data = json_decode($block_room_response, true);

            //debug($block_room_response_data); exit;

            if($this->valid_hotel_result($block_room_response_data)) 
            {
                $formatted_data = $this->format_block_room_response($block_room_response_data, $search_params);
                $response ['status'] = true;
               
            }
            else{
                $formatted_data = array();
                $response ['status'] = false; // Indication for room block
                $response ['data'] ['msg'] = 'Some Problem Occured. Please Search Again to continue';
            } 

            $response ['data']['response'] = $formatted_data['data'];
        }
        //debug($response);exit;
        return $response;
    }





    private function format_block_room_response($hotel_block_room_response,$search_data)
    {
        $response = [
            'status' => false,
            'data' => [],
        ];

        $search_params = $search_data['data'];

        //debug($search_params); exit;

        $adult_count = array_sum($search_params['adult_config']);
        $child_count = array_sum($search_params['child_config']);
       

        if (isset($hotel_block_room_response['HotelResult']) && count($hotel_block_room_response['HotelResult']) > 0) {
        $HotelsData = $hotel_block_room_response['HotelResult'];
        $block_room_result = [];

        foreach ($HotelsData as $hotel_data) 
        {

            //debug($hotel_data); exit;

            $API_Currency = $hotel_data['Currency'];

            $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $API_Currency, 'to' => get_application_currency_preference()));

            if (isset($hotel_data['Rooms']) && valid_array($hotel_data['Rooms'])) 
            {

            foreach ($hotel_data['Rooms'] as $r_key => $room_data) {
                    $room = [];

                    $totalBasePrice = 0;   

                    //debug($room_data['DayRates']); exit;


                    foreach($room_data['DayRates'] as $daysRates)
                    {
                        foreach($daysRates as $Rates)
                        {
                             $totalBasePrice +=   $Rates['BasePrice'];
                        }
                    }

                    // Build room price
                    $room_price_arr = [
                        'net_price' => $totalBasePrice,
                        'tax_price' => $room_data['TotalTax'],
                        'pay_at_destination_price' => 0
                    ];


                    $price = $this->format_price_format($room_price_arr, '', $API_Currency);

                    //debug($room_data['CancelPolicies']); exit;

                    $cancellationPolicies = array();

                    // Cancellation Policy
                    if(isset($room_data['CancelPolicies']) && valid_array($room_data['CancelPolicies'])) { 
                                foreach($room_data['CancelPolicies'] as $cel_key => $cancel_policy) {
                                    //cancellationpolicy
                                    $cancel_policy['FromDate'] = date("Y-m-d H:i:s",strtotime($cancel_policy['FromDate']));
                                    $date_now = date("Y-m-d H:i:s");
                                    $c_date =  $cancel_policy['FromDate'];
                                 if ($date_now < $c_date) 
                                 {
                                    if ($module == 'b2c') {
                                        //Convert to default currency and add mark up
                                        $converted_cncel_price = $currency_obj->get_currency ($cancel_policy['CancellationCharge'], true, false, true, 1 ); // (ON Total PRICE ONLY)
                                    } else {
                                        // B2B Calculation 
                                        $converted_cncel_price = $currency_obj->get_currency ($cancel_policy['CancellationCharge'], true, true, true, 1 ); // (ON Total PRICE ONLY)
                                    }
                                    
                                    $cancellationPolicies[$cel_key]['Charge']   = sprintf("%.2f",$converted_cncel_price['default_value']);
                                    $cancellationPolicies[$cel_key]['FromDate']     = $cancel_policy['FromDate'];
                                    $cancellationPolicies[$cel_key]['ChargeType']     = $cancel_policy['ChargeType'];
                                    $cancellationPolicies[$cel_key]['FromDate']=date('D d M Y H:i:s', strtotime('-24 hours', strtotime($cancellationPolicies[$cel_key]['FromDate'])));
           
                                 }
                                }

                                $policies =$room_data['CancelPolicies'];

                                // Sort by FromDate ascending
                                usort($policies, function ($a, $b) {
                                    return strtotime($a['FromDate']) - strtotime($b['FromDate']);
                                });

                                $last_free_cancellation_date = null;

                                foreach ($policies as $index => $policy) {
                                    if ($policy['CancellationCharge'] == 0) {
                                        if (isset($policies[$index + 1])) {
                                            // subtract 1 day from next chargeable FromDate
                                            $next_date = date('d-m-Y', strtotime($policies[$index + 1]['FromDate'] . ' -1 day'));
                                            $last_free_cancellation_date = $next_date;
                                        }
                                    }
                                }

                            }


                   // debug($cancellationPolicies); exit;

                    $formatted_room = [
                        'HotelCode' => $hotel_data['HotelCode'],
                        'ChildCount' => $child_count,
                        'RoomTypeName' => $this->format_room_names($room_data['Name']),
                        'TBO_RoomTypeName' => '',
                        'Price' => $price,
                        'SmokingPreference' => false,
                        'RatePlanCode' => $room_data['BookingCode'],
                        'RoomTypeCode' => '',
                        'CategoryId' => '',
                        'Boarding_details' => [],
                        'Payment_type' => 'AT_WEB',
                        'room_only' => '',
                        'cancellation_policy_code' => '',
                        'LastCancellationDate' => $last_free_cancellation_date,
                        'RoomNames' => [],
                        'rooms' => [
                            [
                                'room_name' => $this->format_room_names($room_data['Name']),
                                'rates' => [
                                    [
                                        'rateKey' => $room_data['BookingCode'],
                                        'rooms' => $search_params['room_count'],
                                        'adult' => $adult_count,
                                        'children' => $child_count,
                                    ]
                                ]
                            ]
                        ],
                        'CancellationPolicy' => "",
                        'CancellationPolicies' => $cancellationPolicies,
                        'Rate_Description' => $hotel_data['RateConditions'],
                        'IsRefundable' => $room_data['IsRefundable'],
                        'IsPANMandatory' => '',
                        'IsPassportMandatory' => '',
                        'NoOfPANRequired' => '',
                        'rate_key' => $room_data['BookingCode'],
                        'group_code' => '',
                        'room_code' => '',
                        'HOTEL_CODE' => $hotel_data['HotelCode'],
                        'RoomIndex' => 0,
                        'TBO_RoomIndex' => 0
                    ];

                    $block_room_result = [
                        'HotelRoomsDetails' => [$formatted_room],
                        'BlockRoomId' => serialized_data($formatted_room),
                        'IsPriceChanged' => false,
                        'IsCancellationPolicyChanged' => false
                    ];
                }
            }

            $response['status'] = true;
            $response['data']['BlockRoomResult'] = $block_room_result;
        }
    }


       // debug($response); exit;
        return $response;

    }

    
    /**
     *
     * @param array $booking_params         
     */
    function process_booking($book_id, $booking_params)
    {
         //debug($booking_params);exit;

        $response ['status'] = FAILURE_STATUS;
        $response ['data'] = array ();  

        $book_request = $this->get_book_request ($booking_params, $book_id);    

        if ($book_request ['status'] == ACTIVE) 
        {
            $booking_response_data = array();
            $curl_request = $this->form_curl_params($book_request['data']['request'], $book_request['data']['url'],$book_request['data']['method']);

            //debug($curl_request); exit;
          
        
           // file_put_contents('logs/hotel/BookRQ('.$book_id.').json',$curl_request['data']['request']);
            $book_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"Book");
           // file_put_contents('logs/hotel/BookRS('.$book_id.').json',$book_response);
            

           // debug($book_response); exit;
            

           // $book_response = file_get_contents('logs/hotel/BookRS(HB04-042949-348728).json');

            $booking_response_data = json_decode($book_response,true);

           // debug($booking_response_data); exit;
        
        // validate response
        if ($this->valid_book_response ($booking_response_data)) 
        {
           // debug($booking_response_data); exit;

            $booking_details_data = $this->get_booking_details($booking_response_data,$book_id);

             //debug($booking_details_data); exit;

            if($booking_details_data['status']==true)
            {
                $formatted_booking_data = $this->format_hotel_booking_details($booking_response_data,$booking_details_data['data']);

                //debug($formatted_booking_data);
            }

            //debug($formatted_booking_data); exit;

            $response ['status'] = SUCCESS_STATUS;
            
            $room_book_data['HotelRoomsDetails'] = $formatted_booking_data['data']['RoomDetails'];

            $response ['data'] ['book_response']['BookResult'] = $formatted_booking_data['data']['BookResult'];
            $response ['data'] ['booking_params'] = $booking_params;

            // Convert Room Book Data in Application Currency

            //$room_book_data['HotelRoomsDetails'] = $this->formate_hotel_room_details($booking_params);
            
            $response ['data'] ['room_book_data'] = $this->convert_roombook_data_to_application_currency ( $room_book_data );
            }
        }
        else{
            $response ['data']['message'] = $book_response['Message'];
        }
        // debug($response);exit;
        return $response;
    }

    public function get_booking_details($booking_data,$book_id)
    {
        $response['status'] = false;
        $response['data'] = [];

        $book_details_request = $this->get_book_details_request($booking_data);

        $booking_detail_response_data = array();

        //debug($book_details_request); exit;    

        if ($book_details_request ['status'] == ACTIVE) 
        {
            $curl_request = $this->form_curl_params($book_details_request['data']['request'], $book_details_request['data']['url'],$book_details_request['data']['method']);

            //debug($curl_request); exit;
          
            
           // file_put_contents('logs/hotel/BookingDetailRQ('.$book_id.').json',$curl_request['data']['request']);
            $book_detail_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"BookingDetail");
           // file_put_contents('logs/hotel/BookingDetailRS('.$book_id.').json',$book_detail_response);
            

            //debug($book_detail_response); exit;

           // $book_detail_response = file_get_contents('logs/hotel/BookingDetailRS(HB04-042949-348728).json');

             $booking_detail_response_data = json_decode($book_detail_response,true);

             if(isset($booking_detail_response_data['Status']['Code']) && $booking_detail_response_data['Status']['Code'] == 200 && isset($booking_detail_response_data['BookingDetail']))
             {
                $response['status'] = true;
                $response['data'] = $booking_detail_response_data;
             }
            
        }

        return $response;
    }

    private function get_book_details_request($booking_data)
    {
        $response ['status'] = false;
        $response ['data'] = array ();

        $booking_details_request = "";

         // debug($booking_data);
         // exit;

        if(empty($booking_data['ClientReferenceId'])==false)
        {
            $booking_details_request = array(
                'BookingReferenceId' => $booking_data['ClientReferenceId'],
                'PaymentMode'=>'Limit'
            );

                //debug($RoomBlockRequest); exit;

                $response ['status'] = true;
                $response ['data'] = array ();
                $response ['data']['method'] = 'BookingDetail';
                $response ['data'] ['request'] = json_encode($booking_details_request);
                $response ['data'] ['url'] = $this->ServiceUrl;
        }
            
            return $response;
    }

    private function format_hotel_booking_details($booking_response_data, $booking_details_data)
    {
        $response['data'] = array();

        $BookingDetail = $booking_details_data['BookingDetail'];

        $HotelDetails = $BookingDetail['HotelDetails'];
        $Rooms = $BookingDetail['Rooms'];
        $RateConditions = $BookingDetail['RateConditions'];

        $BookingStatus = $BookingDetail['BookingStatus'];
        $VoucherStatus = $BookingDetail['VoucherStatus'];
        $ConfirmationNumber = $BookingDetail['ConfirmationNumber'];
        $ClientReferenceId = $booking_response_data['ClientReferenceId'];
        $InvoiceNumber = $booking_response_data['ConfirmationNumber'];
        $CheckIn = $BookingDetail['CheckIn'];
        $CheckOut = $BookingDetail['CheckOut'];
        $BookingDate = $BookingDetail['BookingDate'];
        $NoOfRooms = $BookingDetail['NoOfRooms'];

        $RoomDataArray = array();

        $RoomIndex = 1;

       // debug($Rooms); exit;

        foreach($Rooms as $roomKey=>$room_data)
        {
            //debug($room_data); exit;

            $currency = $room_data['Currency'];

            $RoomDataArray[$roomKey]['RoomIndex'] = $RoomIndex;
            $RoomDataArray[$roomKey]['RatePlanCode'] = "";
            $RoomDataArray[$roomKey]['RoomPromotion'] = $room_data['RoomPromotion'];
            $RoomDataArray[$roomKey]['RoomTypeCode'] = "";
            $RoomDataArray[$roomKey]['RoomTypeName'] = $this->format_room_names($room_data['Name']);
            $RoomDataArray[$roomKey]['SmokingPreference'] = "";
            $RoomDataArray[$roomKey]['Inclusion'] = $room_data['Inclusion'];

            $room_price_arr = [
                'net_price' => $room_data['TotalFare'],
                'tax_price' => $room_data['TotalTax'],
                'pay_at_destination_price' => 0
            ];

            $RoomDataArray[$roomKey]['Price'] = $this->format_price_format($room_price_arr, '', $currency);

            $traveller_array = array();

            foreach($room_data['CustomerDetails'][0]['CustomerNames'] as $key=>$traveller)
            {
                $Phoneno = "";
                $Email = "";

                $traveller_array[$key]['Title'] = $traveller['Title'];
                $traveller_array[$key]['FirstName'] = $traveller['FirstName'];
                $traveller_array[$key]['MiddleName'] = @$traveller['MiddleName'];
                $traveller_array[$key]['LastName'] = $traveller['LastName'];
                $traveller_array[$key]['Phoneno'] = $Phoneno;
                $traveller_array[$key]['Email'] = $Email;
                $traveller_array[$key]['PaxType'] = $traveller['Type'];
                $traveller_array[$key]['LeadPassenger'] = $key==0?1:0;
                $traveller_array[$key]['Age'] = "";
            }

                $RoomDataArray[$roomKey]['HotelPassenger'] = $traveller_array;
                $RoomIndex++;
        }

        $BookResultData['BookingStatus'] = $BookingStatus;
        $BookResultData['BookingId'] = $InvoiceNumber;
        $BookResultData['BookingRefNo'] = $ClientReferenceId;
        $BookResultData['ConfirmationNo'] = $ConfirmationNumber;

        //debug($RoomDataArray); exit;
        $response['data']['RoomDetails'] = $RoomDataArray;
        $response['data']['BookResult'] = $BookResultData;

        //debug($response); exit;

        return $response;

    }

    /**
     * Formates Hotel Room Details
     * @param unknown_type $booking_params
     */
    private function formate_hotel_room_details($booking_params)
    {
        // debug($booking_params);exit;
        $search_id = $booking_params ['token'] ['search_id'];
        $safe_search_data = $GLOBALS ['CI']->hotel_model->get_search_data ( $search_id );
        $search_data = json_decode ( $safe_search_data ['search_data'], true );
        $number_of_nights = get_date_difference ( date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkin'] ) ), date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkout'] ) ) );
        $NO_OF_ROOMS = $search_data ['rooms'];
        $k = 0;
    
        
        $HotelRoomsDetails = array();
        /* Counting No of adults and childs per room wise */
        for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
            $booking_params ['token'] ['token'] [$i] ['no_of_pax'] = $search_data ['adult'] [$i] + $search_data ['child'] [$i];
        }
        for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
            $room_detail = array ();
            $room_detail ['RoomIndex'] = $booking_params ['token'] ['token'] [$i] ['RoomIndex'];
            $room_detail ['RatePlanCode'] = $booking_params ['token'] ['token'] [$i] ['RatePlanCode'];
            $room_detail ['RatePlanName'] = $booking_params ['token'] ['token'] [$i] ['RatePlanName'];
            $room_detail ['RoomTypeCode'] = $booking_params ['token'] ['token'] [$i] ['RoomTypeCode'];
            $room_detail ['RoomTypeName'] = $booking_params ['token'] ['token'] [$i] ['RoomTypeName'];
            $room_detail ['SmokingPreference'] = 0;
            
            $room_detail ['Price'] ['CurrencyCode'] = $booking_params ['token'] ['token'] [$i] ['CurrencyCode'];
            $room_detail ['Price'] ['RoomPrice'] = $booking_params ['token'] ['token'] [$i] ['RoomPrice'];
            $room_detail ['Price'] ['Tax'] = $booking_params ['token'] ['token'] [$i] ['Tax'];
            $room_detail ['Price'] ['ExtraGuestCharge'] = $booking_params ['token'] ['token'] [$i] ['ExtraGuestCharge'];
            $room_detail ['Price'] ['ChildCharge'] = $booking_params ['token'] ['token'] [$i] ['ChildCharge'];
            $room_detail ['Price'] ['OtherCharges'] = $booking_params ['token'] ['token'] [$i] ['OtherCharges'];
            $room_detail ['Price'] ['Discount'] = $booking_params ['token'] ['token'] [$i] ['Discount'];
            $room_detail ['Price'] ['PublishedPrice'] = $booking_params ['token'] ['token'] [$i] ['PublishedPrice'];
            $room_detail ['Price'] ['PublishedPriceRoundedOff'] = $booking_params ['token'] ['token'] [$i] ['PublishedPriceRoundedOff'];
            $room_detail ['Price'] ['OfferedPrice'] = $booking_params ['token'] ['token'] [$i] ['OfferedPrice'];
            $room_detail ['Price'] ['OfferedPriceRoundedOff'] = $booking_params ['token'] ['token'] [$i] ['OfferedPriceRoundedOff'];
            $room_detail ['Price'] ['SmokingPreference'] = $booking_params ['token'] ['token'] [$i] ['SmokingPreference'];
            $room_detail ['Price'] ['ServiceTax'] = $booking_params ['token'] ['token'] [$i] ['ServiceTax'];
            $room_detail ['Price'] ['Tax'] = $booking_params ['token'] ['token'] [$i] ['Tax'];
            $room_detail ['Price'] ['ExtraGuestCharge'] = $booking_params ['token'] ['token'] [$i] ['ExtraGuestCharge'];
            $room_detail ['Price'] ['ChildCharge'] = $booking_params ['token'] ['token'] [$i] ['ChildCharge'];
            $room_detail ['Price'] ['OtherCharges'] = $booking_params ['token'] ['token'] [$i] ['OtherCharges'];
            $room_detail ['Price'] ['Discount'] = $booking_params ['token'] ['token'] [$i] ['Discount'];
            $room_detail ['Price'] ['AgentCommission'] = $booking_params ['token'] ['token'] [$i] ['AgentCommission'];
            $room_detail ['Price'] ['AgentMarkUp'] = $booking_params ['token'] ['token'] [$i] ['AgentMarkUp'];
            $room_detail ['Price'] ['TDS'] = $booking_params ['token'] ['token'] [$i] ['TDS'];
            $HotelRoomsDetails[$i] = $room_detail;
            
            for($j = 0; $j < $booking_params ['token'] ['token'] [$i] ['no_of_pax']; $j ++) {
                $pax_list = array (); // Reset Pax List Array
                $pax_title = get_enum_list ( 'title', $booking_params ['name_title'] [$k] );
                $pax_list ['Title'] = $pax_title;
                $pax_list ['FirstName'] = $booking_params ['first_name'] [$k];
                $pax_list ['MiddleName'] = $booking_params ['middle_name'] [$k];
                $pax_list ['LastName'] = $booking_params ['last_name'] [$k];
                $pax_list ['Phoneno'] = $booking_params ['passenger_contact'];
                $pax_list ['Email'] = $booking_params ['billing_email'];
                $pax_list ['PaxType'] = $booking_params ['passenger_type'] [$k];
                
                $pax_lead = false;
                // temp
                if ($j == 0) {
                    $pax_lead = true;
                }
                $pax_list ['LeadPassenger'] = $pax_lead;
                /* Age Calculation of Pax */
                $from = new DateTime ( $booking_params ['date_of_birth'] [$k] );
                $to = new DateTime ( 'today' );
                $pax_age = $from->diff ( $to )->y;
                $pax_list ['Age'] = $pax_age;
                $HotelRoomsDetails[$i] ['HotelPassenger'] [$j] = $pax_list;
                $k ++;
            }
        }
        return $HotelRoomsDetails;
    }


    private function format_room_names($room_name_arr) 
    {

        $room_names_arr = array_count_values($room_name_arr);

        $room_list_arr = $room_names_arr;
        $room_names = '';
        $i = 0;
        $room_count = count($room_names_arr);
        $no_of_rooms_arr = array();

        foreach ($room_names_arr as $key => $value) {
           $no_of_rooms_arr[] = $value;
        }

        foreach ($room_names_arr as $r_key => $r_value) 
        {
            if ($room_count == 1) 
            {
                if ($r_value == 1) 
                {
                    $room_names .= $no_of_rooms_arr[$i] . ' X ' . $r_key;
                } elseif ($r_value == 2) {
                    $room_names .= ' 2 X ' . $r_key;
                } elseif ($r_value == 3) {
                    $room_names .= ' 3 X ' . $r_key;
                } elseif ($r_value == 4) {
                    $room_names .= ' 4 X ' . $r_key;
                }
            } 
            else
             {
                $add_plus = '';
                if (isset($no_of_rooms_arr[$i + 1])) {
                    $add_plus = ' + ';
                }
                $room_text = '';
                $room_text = $no_of_rooms_arr[$i] . ' X ';
                $room_names .= $room_text . $r_key . $add_plus;
            }

            $i++;
        }
        return $room_names;
    }
    /**
     * Reference number generated for booking from application
     * 
     * @param
     *          $app_booking_id
     * @param
     *          $params
     */
    function save_booking($app_booking_id, $book_params, $currency_obj, $module = 'b2c'){

        //debug($book_params);exit;

        error_reporting(0);
        $response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;
        $book_total_fare = array();
        $book_domain_markup = array();
        $book_level_one_markup = array();
        $status = 'BOOKING_INPROGRESS';
        $master_search_id = $book_params['search_id'];
        $total_fare_markup = $book_params['total_fare_markup'];
        $total_fare_tax = $book_params['total_fare_tax'];
        $agentServicefee = $book_params['agentServicefee'];
        $domain_origin = get_domain_auth_id();
        $app_reference = $app_booking_id;
        $booking_source = $book_params['token']['booking_source'];
        $deduction_cur_obj = clone $currency_obj;
        $total_pax_count = count($book_params['passenger_type']);
        $pax_count = $total_pax_count;
        //debug($pax_count);exit;
        //PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
        $search_data = $this->search_data ( $master_search_id );
        $no_of_nights = intval ( $search_data ['data'] ['no_of_nights'] );
        //debug($search_data);exit;
        $HotelRoomsDetails = force_multple_data_format ( $book_params ['token']['token'] );
        $total_room_count = count ( $HotelRoomsDetails );
        //debug($total_room_count);exit;
        $book_total_fare = $book_params ['token'] ['price_token'] [0] ['OfferedPriceRoundedOff']; // (TAX+ROOM PRICE)
        $room_price = $book_params ['token'] ['price_token'] [0] ['RoomPrice'];


        // validating $total_fare_markup and $total_fare_tax amount from the frontend
        $total_fare_markup = $book_params['token']['markup_price_summary']['OfferedPriceRoundedOff'];
        $total_fare_tax = $book_params['token']['markup_price_summary']['Tax'];


        if ($module == 'b2c') {
            $markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
            $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
        } else {

            // B2B Calculation
            $markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
            $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
        }
        //debug($markup_total_fare);exit;
        $currency = $book_params ['token'] ['default_currency'];
        $hotel_name = $book_params ['token'] ['HotelName'];
        $star_rat = $book_params ['token'] ['StarRating'];
        if(isset($star_rat) && !empty($star_rat)){
            $star_rating = $book_params ['token'] ['StarRating'];
        }else{
            $star_rating = 0;
        }
        $hotel_code = '';
        $phone_number = $book_params ['passenger_contact'];
        $phone_code = @$book_params ['phone_country_code'];
        $alternate_number = 'NA';
        $email = $book_params ['billing_email'];
        //debug($email);exit;
        $hotel_check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
        $hotel_check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
        $payment_mode = $book_params ['payment_method'];
        $country_name = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
                'k' => 'origin',
                'v' => 'name' 
        ), array (
                'origin' => $book_params ['billing_country'] 
        ) );
        $booking_id = '';
        $booking_reference= '';
        $confirmation_reference='';
        $attributes = array (
                'address' => @$book_params ['billing_address_1'],
                'billing_country' => @$country_name [$book_params ['billing_country']],
                // 'billing_city' => $city_name[$params['booking_params']['billing_city']],
                'billing_city' => @$book_params ['billing_city'],
                'billing_zipcode' => @$book_params ['billing_zipcode'],
                'HotelCode' => @$book_params ['token'] ['HotelCode'],
                'search_id' => @$book_params ['token'] ['search_id'],
                'TraceId' => @$book_params ['token'] ['TraceId'],
                'HotelName' => @$book_params ['token'] ['HotelName'],
                'StarRating' => @$book_params ['token'] ['StarRating'],
                'HotelImage' => @$book_params ['token'] ['HotelImage'],
                'HotelAddress' => @$book_params ['token'] ['HotelAddress'],
                'CancellationPolicy' => @$book_params ['token'] ['CancellationPolicy'],
                'Boarding_details' => @$book_params ['token'] ['Boarding_details']
        );
        //debug($attributes);exit;
        $created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
        $GLOBALS ['CI']->hotel_model->save_booking_details ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email, $hotel_check_in, $hotel_check_out, $payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code ,$total_fare_markup ,$total_fare_tax, $agentServicefee);
        
        $check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
        $check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
        $location = $search_data ['data'] ['location'];
        foreach ( $HotelRoomsDetails as $k => $v ) {
            $room_type_name = $v ['RoomTypeName'];
            $bed_type_code = $v ['RoomTypeCode'];
            $smoking_preference = get_smoking_preference ( $v ['SmokingPreference'] );
            $smoking_preference = $smoking_preference ['label'];
            $total_fare = $v ['OfferedPriceRoundedOff'];
            $total_tax = $v ['Tax'];
            $room_price = $v ['RoomPrice'];
            $gst_value = 0;
            //debug($total_fare);exit;
            if ($module == 'b2c') {
                $markup_total_fare = $currency_obj->get_currency ( $total_fare, true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $ded_total_fare = $deduction_cur_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
                $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $total_fare );
                //adding gst
                if($admin_markup > 0 ){
                    $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
                    if($gst_details['status'] == true){
                        if($gst_details['data'][0]['gst'] > 0){
                            $gst_value = ($admin_markup/100) * $gst_details['data'][0]['gst'];
                            $gst_value  = roundoff_number($gst_value);
                        }
                    }
                }
            } else {
                // B2B Calculation - Room wise price
                            //echo 'total_fare',debug($total_fare);
                $markup_total_fare = $currency_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                                $ded_total_fare = $deduction_cur_obj->get_currency(($markup_total_fare ['default_value']), true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] -  $total_fare);
                $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $markup_total_fare ['default_value']);
                $markup = $admin_markup+$agent_markup;             
                //adding gst
                if($markup > 0 ){
                    $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
                    if($gst_details['status'] == true){
                        if($gst_details['data'][0]['gst'] > 0){
                            $gst_value = ($markup/100) * $gst_details['data'][0]['gst'];
                            $gst_value  = roundoff_number($gst_value);
                        }
                    }
                }
            }
            $total_fare_markup = round($book_total_fare+$admin_markup+$gst_value);
            //debug($total_fare_markup);exit;

            $total_fare = $total_fare + $total_tax;
            $attributes = '';
            $GLOBALS ['CI']->hotel_model->save_booking_itinerary_details ( $app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code, $status, $smoking_preference, $total_fare, $admin_markup, $agent_markup, $currency, $attributes, @$v ['RoomPrice'], @$v ['Tax'], @$v ['ExtraGuestCharge'], @$v ['ChildCharge'], @$v ['OtherCharges'], @$v ['Discount'], @$v ['ServiceTax'], @$v ['AgentCommission'], @$v ['AgentMarkUp'], @$v ['TDS'], $gst_value  );
        }
            
            $i = 0;
            //Saving Passenger Details
        for ($i = 0; $i < $total_pax_count; $i++){
                $passenger_type = $book_params['passenger_type'][$i];
                $is_lead = $book_params['lead_passenger'][$i];
                if($is_lead==''){
                    $is_lead = 0;
                }
                $title = get_enum_list('title', $book_params['name_title'][$i]);
                $first_name = $book_params['first_name'][$i];
                //debug($first_name);exit;
                $middle_name = ''; //$book_params['middle_name'][$i];
                $last_name = $book_params['last_name'][$i];
                $date_of_birth = @$book_params['date_of_birth'][$i];
                $gender = get_enum_list('gender', $book_params['gender'][$i]);
                $passenger_nationality_id = intval($book_params['passenger_nationality'][$i]);
                $passport_issuing_country_id = intval($book_params['passenger_passport_issuing_country'][$i]);
                $passenger_nationality = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passenger_nationality_id));
                $passport_issuing_country = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passport_issuing_country_id));
                $passenger_nationality = $passenger_nationality [$passenger_nationality_id];
                $passport_issuing_country = $passport_issuing_country [$passport_issuing_country_id];
                $passport_number = $book_params['passenger_passport_number'][$i];
                $passport_expiry_date = $book_params['passenger_passport_expiry_year'][$i] . '-' . $book_params['passenger_passport_expiry_month'][$i] . '-' . $book_params['passenger_passport_expiry_day'][$i];
                //$phone = 0;
                $phone = $phone_number;
                $attributes = array ();
                // SAVE Booking Pax details
                $GLOBALS ['CI']->hotel_model->save_booking_pax_details ( $app_reference, $title, $first_name, $middle_name, $last_name,$phone, $email, $passenger_type, $date_of_birth, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, serialize ( $attributes ) );
            }
        // Convinence_fees to be stored and discount
        $convinence = 0;
        $discount = 0;
        $convinence_value = 0;
        $convinence_type = 0;
        $convinence_per_pax = 0;
        if ($module == 'b2c') {
            $convinence = $currency_obj->convenience_fees ( $total_fare_markup, $master_search_id );
            $convinence_row = $currency_obj->get_convenience_fees ();
            $convinence_value = $convinence_row ['value'];
            $convinence_type = $convinence_row ['type'];
            $convinence_per_pax = $convinence_row ['per_pax']; 
            // if($book_params['booking_params']['promo_actual_value']){
            //  $discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $book_params['booking_params']['promo_actual_value']) );
            // }            
            $discount = @$book_params['promo_actual_value'];
            $promo_code = @$book_params ['promo_code'];
        } elseif ($module == 'b2b') {
            $discount = 0;
        }
        $GLOBALS ['CI']->load->model ( 'transaction' );
        // SAVE Booking convinence_discount_details details
        $GLOBALS ['CI']->transaction->update_convinence_discount_details ( 'hotel_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax );
        /**
         * ************ Update Convinence Fees And Other Details End *****************
         */
        
        $response ['fare'] = $book_total_fare;
        $response ['admin_markup'] = $admin_markup;
        $response ['agent_markup'] = $agent_markup;
        $response ['convinence'] = $convinence;
        $response ['discount'] = $discount;
        $response ['transaction_currency'] = $transaction_currency;
        $response ['currency_conversion_rate'] = $currency_conversion_rate;
        //booking_status
        $response['booking_status'] = $status;
        //debug($response);exit;
        return $response;
    }
    public function update_booking_details($book_id, $book_params, $ticket_details,$currency_obj,$module = 'b2c') {
        $app_reference = $book_id;
        $master_search_id = $book_params['booking_params']['token']['search_id'];
        //Setting Master Booking Status
        $master_transaction_status = $this->status_code_value($ticket_details['master_booking_status']);
        if (isset($ticket_details['TicketDetails']) == true && valid_array($ticket_details['TicketDetails']) == true) {
            $ticket_details = $ticket_details['TicketDetails'];
        } else {
            $ticket_details = array();
        }
        $saved_booking_data = $GLOBALS['CI']->hotel_model->get_booking_details($book_id);
        //debug($saved_booking_data);exit;
        if ($saved_booking_data['status'] == false) {
            $response['status'] = BOOKING_ERROR;
            $response['msg'] = 'No Data Found';
            return $response;
        }
        $s_master_data = $saved_booking_data['data']['booking_details'][0];
        $s_booking_itinerary_details = $saved_booking_data['data']['booking_itinerary_details'];
        $s_booking_customer_details = $saved_booking_data['data']['booking_customer_details'];
        $passenger_origins = group_array_column($s_booking_customer_details, 'origin');
        $itinerary_origins = group_array_column($s_booking_itinerary_details, 'origin');
        $hotel_master_booking_status = $master_transaction_status;
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
        $booking_id = $book_params['book_response']['BookResult']['BookingId'];
        $BookingRefNo = $book_params['book_response']['BookResult']['BookingRefNo'];
        $ConfirmationNo = $book_params['book_response']['BookResult']['ConfirmationNo'];
        $GLOBALS['CI']->custom_db->update_record('hotel_booking_details', array('status' => $master_transaction_status,'booking_id' => $booking_id,'booking_reference' => $BookingRefNo,'confirmation_reference' => $ConfirmationNo), array('app_reference' => $app_reference));
        //debug('hiiiiiiii');exit;
        $total_pax_count = count($book_params['booking_params']['passenger_type']);
        $pax_count = $total_pax_count;
        $search_data = $this->search_data ( $master_search_id );
        $no_of_nights = intval ( $search_data ['data'] ['no_of_nights'] );
        $HotelRoomsDetails = force_multple_data_format ( $book_params['booking_params'] ['token']['token'] );
        $total_room_count = count ( $HotelRoomsDetails );
        //debug($total_room_count);exit;
        $book_total_fare = $book_params['booking_params'] ['token'] ['price_token'] [0] ['OfferedPriceRoundedOff']; // (TAX+ROOM PRICE)
        $room_price = $book_params['booking_params'] ['token'] ['price_token'] [0] ['RoomPrice'];
        $deduction_cur_obj = clone $currency_obj;
        if ($module == 'b2c') {
            $markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
            $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
        } else {
            // B2B Calculation
            $markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
            $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
            $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
        }
        //debug($markup_total_fare);exit;
        foreach ( $HotelRoomsDetails as $k => $v ){
            $room_type_name = $v ['RoomTypeName'];
            $bed_type_code = $v ['RoomTypeCode'];
            $smoking_preference = get_smoking_preference ( $v ['SmokingPreference'] );
            $smoking_preference = $smoking_preference ['label'];
            $total_fare = $v ['OfferedPriceRoundedOff'];
            $room_price = $v ['RoomPrice'];
            $gst_value = 0;
            if ($module == 'b2c') {
                $markup_total_fare = $currency_obj->get_currency ( $total_fare, true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $ded_total_fare = $deduction_cur_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
                $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $total_fare );
                //adding gst
                if($admin_markup > 0 ){
                    $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
                    if($gst_details['status'] == true){
                        if($gst_details['data'][0]['gst'] > 0){
                            $gst_value = ($admin_markup/100) * $gst_details['data'][0]['gst'];
                            $gst_value  = roundoff_number($gst_value);
                        }
                    }
                }
            }else {
                // B2B Calculation - Room wise price
                            //echo 'total_fare',debug($total_fare);
                $markup_total_fare = $currency_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                                $ded_total_fare = $deduction_cur_obj->get_currency(($markup_total_fare ['default_value']), true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] -  $total_fare);
                $agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $markup_total_fare ['default_value']);
                $markup = $admin_markup+$agent_markup;             
                //adding gst
                if($markup > 0 ){
                    $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
                    if($gst_details['status'] == true){
                        if($gst_details['data'][0]['gst'] > 0){
                            $gst_value = ($markup/100) * $gst_details['data'][0]['gst'];
                            $gst_value  = roundoff_number($gst_value);
                        }
                    }
                }
            }
            $total_fare_markup = round($book_total_fare+$admin_markup+$gst_value);
            $GLOBALS['CI']->custom_db->update_record('hotel_booking_itinerary_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));
            
            $GLOBALS['CI']->custom_db->update_record('hotel_booking_pax_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));
        }
        $convinence = 0;
        $discount = 0;
        $convinence_value = 0;
        $convinence_type = 0;
        $convinence_per_pax = 0;
        if ($module == 'b2c') {
            $convinence = $currency_obj->convenience_fees ( $total_fare_markup, $master_search_id );
            $convinence_row = $currency_obj->get_convenience_fees ();
            $convinence_value = $convinence_row ['value'];
            $convinence_type = $convinence_row ['type'];
            $convinence_per_pax = $convinence_row ['per_pax']; 
            // if($book_params['booking_params']['promo_actual_value']){
            //  $discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $book_params['booking_params']['promo_actual_value']) );
            // }            
            $discount = @$book_params['promo_actual_value'];
            $discount = floatval(preg_replace('/[^\d.]/', '', $discount));
            $promo_code = @$book_params ['promo_code'];
        } elseif ($module == 'b2b') {
            $discount = 0;
        }
        $response ['fare'] = $book_total_fare;
        $response ['admin_markup'] = $admin_markup;
        $response ['agent_markup'] = $agent_markup;
        $response ['convinence'] = $convinence;
        $response ['discount'] = $discount;
        $response ['transaction_currency'] = $transaction_currency;
        $response ['currency_conversion_rate'] = $currency_conversion_rate;
        //booking_status
        $response['booking_status'] = $master_transaction_status;
        return $response;
    }
    private function status_code_value($status_code) {
        switch ($status_code) {
            case BOOKING_CONFIRMED:
            case SUCCESS_STATUS:
                $status_value = 'BOOKING_CONFIRMED';
                break;
            case BOOKING_HOLD:
                $status_value = 'BOOKING_HOLD';
                break;
            default:
                $status_value = 'BOOKING_FAILED';
        }
        return $status_value;
    }
    
    /**
     * Balu A
     * Convert Room Book Data in Application Currency
     * 
     * @param
     *          $currency_obj
     */
    private function convert_roombook_data_to_application_currency($room_book_data) {
        $application_default_currency = admin_base_currency ();
        $currency_obj = new Currency ( array (
                'module_type' => 'hotel',
                'from' => get_api_data_currency (),
                'to' => admin_base_currency () 
        ) );
        $master_room_book_data = array ();
        $HotelRoomsDetails = array ();
        foreach ( $room_book_data ['HotelRoomsDetails'] as $hrk => $hrv ) {
            $HotelRoomsDetails [$hrk] = $hrv;
            $HotelRoomsDetails [$hrk] ['Price'] = $this->preferred_currency_fare_object ( $hrv ['Price'], $currency_obj, $application_default_currency );
        }
        $master_room_book_data = $room_book_data;
        $master_room_book_data ['HotelRoomsDetails'] = $HotelRoomsDetails;
        return $master_room_book_data;
    }
    /**
     * Balu A
     * Cancel Booking
     */
    function cancel_booking($booking_details)
    {

        //debug($booking_details); exit;

        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = FAILURE_STATUS;
        $resposne ['msg'] = 'Remote IO Error';

        //debug($booking_details_data); exit;

        $BookingId = $booking_details ['booking_id'];
        $booking_reference = $booking_details ['booking_reference'];
        $app_reference = $booking_details ['app_reference'];
        $cancel_booking_request = $this->cancel_booking_request_params($BookingId);

       // debug($cancel_booking_request); exit;


        if ($cancel_booking_request ['status']) {

            $cancel_booking_response_data = array();
            $curl_request = $this->form_curl_params($cancel_booking_request['data']['request'], $cancel_booking_request['data']['url'],$cancel_booking_request['data']['method']);

            //debug($curl_request); exit;
            
           // file_put_contents('logs/hotel/CancelRQ('.$app_reference.').json',$curl_request['data']['request']);
            $cancel_booking_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"Cancellation");
            //file_put_contents('logs/hotel/CancelRS('.$app_reference.').json',$cancel_booking_response);

           // $cancel_booking_response = file_get_contents('logs/hotel/HotelCancelRS.json');
            
            $cancel_booking_response_data = json_decode($cancel_booking_response,true);

            if (valid_array ( $cancel_booking_response_data ) == true && isset($cancel_booking_response_data ['Status']['Code']) && $cancel_booking_response_data ['Status']['Code'] == 200 && empty($booking_reference)==false) 
            {
                $booking_data['ClientReferenceId'] = $booking_reference;
                $book_details_request = $this->get_book_details_request($booking_data);

                $booking_detail_response_data = array();

                //debug($book_details_request); exit;    

                if ($book_details_request ['status'] == ACTIVE) 
                {
                    $curl_request = $this->form_curl_params($book_details_request['data']['request'], $book_details_request['data']['url'],$book_details_request['data']['method']);
                    
                  //  file_put_contents('logs/hotel/BookingDetailRQ('.$app_reference.'_cancel).json',$curl_request['data']['request']);
                    $book_detail_response = $this->CI->api_interface->get_json_response_tbo($curl_request['data']['url'], $curl_request['data']['request'], $curl_request['data']['header'],"Cancellation");
                   // file_put_contents('logs/hotel/BookingDetailRS('.$app_reference.'_cancel).json',$book_detail_response);

                    $booking_detail_response_data = json_decode($book_detail_response, true);

                if(isset($booking_detail_response_data['Status']['Code']) && $booking_detail_response_data['Status']['Code'] == 200 && isset($booking_detail_response_data['BookingDetail']))
                 {

                    $booking_detail = $booking_detail_response_data['BookingDetail'];

                    $cancellation_details = array();

                    $cancellation_details['ChangeRequestId'] = $booking_reference;
                    $cancellation_details['ChangeRequestStatus'] = $booking_detail['BookingStatus'] ?? '';
                    $cancellation_details['StatusDescription'] = $booking_detail_response_data['Status']['Description'] ?? '';

                    $room = $booking_detail['Rooms'][0];
                    $cancel_policies = $room['CancelPolicies'];
                    $total_fare = $room['TotalFare'];

                    $today = new DateTime(); // Current server date
                    $effective_policy = null;

                    foreach ($cancel_policies as $policy) {
                        $policy_date = DateTime::createFromFormat('d-m-Y H:i:s', $policy['FromDate']);
                        if ($policy_date && $today >= $policy_date) {
                            // Only select policies that are already in effect
                            $effective_policy = $policy;
                        }
                    }

                    // Default values
                    $cancellation_charge = 0.00;
                    $refund_amount = $total_fare;

                    if ($effective_policy !== null) {
                        if ($effective_policy['ChargeType'] == 'Fixed') {
                            $cancellation_charge = $effective_policy['CancellationCharge'];
                        } elseif ($effective_policy['ChargeType'] == 'Percentage') {
                            $cancellation_charge = ($total_fare * $effective_policy['CancellationCharge']) / 100;
                        }
                        $refund_amount = $total_fare - $cancellation_charge;
                    }

                    // Optional: round to 2 decimals
                    $cancellation_charge = round($cancellation_charge, 2);
                    $refund_amount = round($refund_amount, 2);

                    // Add to cancellation details
                    $cancellation_details['CancellationCharge'] = $cancellation_charge;
                    $cancellation_details['RefundedAmount'] = $refund_amount;
                    $cancellation_details['refund_date'] = date('Y-m-d'); // today's date

                    //debug($cancellation_details); exit;

                // Save Cancellation Details
                $hotel_cancellation_details = $cancellation_details;
                $GLOBALS ['CI']->hotel_model->update_cancellation_details ( $app_reference, $hotel_cancellation_details );
                $response ['status'] = SUCCESS_STATUS;

                }
                    
                }
            } 
            else
            {
                $response ['msg'] = $cancel_booking_response['Message'];
            }
        }
        return $response;
    }
    /**
     * Balu A
     * Cancellation Request Status
     */
    function get_cancellation_refund_details($ChangeRequestId, $app_reference) {
        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = FAILURE_STATUS;
        $resposne ['msg'] = 'Remote IO Error';
        $api_request = $this->cancellation_refund_request_params ( $ChangeRequestId, $app_reference );
        if ($api_request ['status']) {
            $api_response = $GLOBALS ['CI']->api_interface->get_json_response ( $api_request ['data'] ['service_url'], $api_request ['data'] ['request'], $header );
            if (valid_array ( $api_response ) == true && isset ( $api_response ['Status'] ) == true && $api_response ['Status'] == SUCCESS_STATUS) {
                $response ['data'] = $api_response ['RefundDetails'];
                $response ['status'] = SUCCESS_STATUS;
            } else {
                $resposne ['msg'] = @$api_response ['Message'];
            }
        }
        return $response;
    }
    /**
     * Sawood
     * check and return status is success or not
     * 
     * @param unknown_type $response_status         
     */
    function valid_book_response($booking_response) {
        $status = false;
        if (valid_array($booking_response) == true and isset($booking_response['Status']['Code']) == true and $booking_response['Status']['Code'] == 200 and isset($booking_response['ClientReferenceId']) == true and isset($booking_response['ConfirmationNumber']) == true) 
        {
            $status = true;
        }
        return $status;
    }
    
    /**
     * Balu A
     * check and return status is success or not
     * 
     * @param unknown_type $response_status         
     */
    function valid_response($response_status) {
        $status = true;
        if ($response_status != SUCCESS_STATUS) {
            $status = false;
        }
        return $status;
    }
    
    /**
     * Balu A
     *
     * Check if the room was blocked successfully
     * 
     * @param array $block_room_response
     *          block room response
     */
    private function is_room_blocked($block_room_response) {
        $room_blocked = false;
        if (isset ( $block_room_response ['BlockRoomResult'] ) == true and $block_room_response ['BlockRoomResult'] ['IsPriceChanged'] == false and $block_room_response ['BlockRoomResult'] ['IsCancellationPolicyChanged'] == false) {
            $room_blocked = true;
        }
        
        return $room_blocked;
    }
    
    /**
     * Balu A
     * check if the room list is valid or not
     * 
     * @param
     *          $room_list
     */
    private function valid_room_details_details($room_list) {
        $status = false;
        if (valid_array ( $room_list ) == true and isset ( $room_list ['Status'] ) == true and $room_list ['Status']  == SUCCESS_STATUS) {
            $status = true;
        }
        return $status;
    }
    
    /**
     * Balu A
     * check if the hotel response which is received from server is valid or not
     * 
     * @param
     *          $hotel_details
     */
    private function valid_hotel_result($hotel_response) {

        $status = false;

         if (valid_array($hotel_response) == true and isset($hotel_response['Status']['Code']) == true and isset($hotel_response['HotelResult']) == true and $hotel_response['Status']['Code'] == 200) {
            return true;
        }
        return $status;
    }


    private function valid_hotel_details($hotel_response) {

        $status = false;

         if (valid_array($hotel_response) == true and isset($hotel_response['Status']['Code']) == true and isset($hotel_response['HotelDetails']) == true and $hotel_response['Status']['Code'] == 200) {
            return true;
        }
        return $status;
    }

    
    /**
     * Balu A
     * Update and return price details
     */
    public function update_block_details($room_details, $booking_parameters,$cancel_currency_obj) {

        //debug($room_details['HotelRoomsDetails']); exit;

        $Surcharge_total = 0;
        foreach ($room_details['HotelRoomsDetails'] as $key => $value) {
            $Surcharge_total += @$value['Surcharge_total'];
        }
        
        $booking_parameters ['BlockRoomId'] = $room_details ['BlockRoomId'];
        $room_details ['HotelRoomsDetails'] = get_room_index_list ( $room_details ['HotelRoomsDetails'] );
        //debug($room_details ['HotelRoomsDetails']);
        //echo "-----";
        $booking_parameters ['token'] = array(); // Remove all the token details
        $total_OfferedPriceRoundedOff = $Tax = '';
        
        foreach ( $room_details ['HotelRoomsDetails'] as $__rc_key => $__rc_value ) {
            
            $booking_parameters ['token'] [] = get_dynamic_booking_parameters ($__rc_key, $__rc_value, get_application_currency_preference ());
            $booking_parameters ['price_token'] [] = $__rc_value ['Price'];
            $booking_parameters['HotelCode'] = $__rc_value['HotelCode'];
        }
        
        $policy_string ='';
        $cancel_string='';

       // debug($room_details['HotelRoomsDetails']); exit;

        $last_cancellation_date = $room_details['HotelRoomsDetails'][0]['LastCancellationDate'];
        
        $cancellation_details = array_reverse($room_details['HotelRoomsDetails'][0]['CancellationPolicies']);

        //debug($cancellation_details); exit;
        
        $cancellation_rev_details =  array_reverse($room_details['HotelRoomsDetails'][0]['CancellationPolicies']);

        //debug($cancellation_rev_details); exit;

        $room_price = 0;
        foreach ($room_details['HotelRoomsDetails'] as $p_key => $p_value) {
            $room_price +=$p_value['Price']['RoomPrice'];
        }
        
        $cancel_count = count($cancellation_details);
        $cancellation_rev_details = $this->php_arrayUnique($cancellation_rev_details,'Charge');         
        $cancellation_details =  $this->php_arrayUnique($cancellation_details,'Charge');
       
        //debug($cancellation_details);exit;    

        if($cancellation_details && !empty($last_cancellation_date)){
                foreach ($cancellation_details as $key => $value) {
                    $amount = 0;
                    $policy_string ='';
                    if($value['Charge']==0){
                         $policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
                        $last_cancellation_date = $value['ToDate'];
                    }else{
                        
                        if(isset($cancellation_rev_details[$key+1])){
                            if($value['ChargeType']==1 || $value['ChargeType']=='Fixed'){
                                $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
                            }elseif($value['ChargeType']==2 || $value['ChargeType']=='Percentage'){
                                $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$room_price;
                            }
                            
                            $current_date = date('Y-m-d');
                            $cancell_date = date('Y-m-d',strtotime($value['FromDate']));
                            if($cancell_date >$current_date){
                                //$value['FromDate'] = date('Y-m-d');
                                    $policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
                            }
                            //$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
                        }else{
                            if($value['ChargeType']==1 || $value['ChargeType']=='Fixed'){
                                $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
                            }elseif ($value['ChargeType']==2 || $value['ChargeType']=='Percentage') {
                                $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$room_price;
                            }
                            
                            $current_date = date('Y-m-d');
                            $cancell_date = date('Y-m-d',strtotime($value['FromDate']));
                            if($cancell_date >$current_date){
                                $value['FromDate'] = $value['FromDate'];
                                $policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
                            }else{
                                $value['FromDate'] = date('Y-m-d');
                                $policy_string .='This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
                            }
                            
                        }
                    }
                    
                    $cancel_string .= $policy_string.'<br/>';
                    /*if($value['ChargeType']==1){
                        if($value['Charge']!=0){
                            $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
                        }else{
                            $last_cancellation_date = $value['ToDate'];
                        }
                            
                    }elseif($value['ChargeType']==2){
                        $amount = '100%';
                    }
                    $policy_string = ' '.$amount.' will be charged, If cancelled between '.$value['FromDate'].' and '.$value['ToDate'];
                    $cancel_string .= $policy_string.' #!# ';*/
                        
                }
                
        }else{
            $cancel_string ='This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
        }       
        if(isset($room_details['HotelRoomsDetails'][0]['RoomTypeName'])){
            $booking_parameters['RoomTypeName'] = $room_details['HotelRoomsDetails'][0]['RoomTypeName'];
        }
                
        if(isset($room_details['HotelRoomsDetails'][0]['Boarding_details'])){
            $booking_parameters['Boarding_details1'][] = $room_details['HotelRoomsDetails'][0]['Boarding_details']; 
        }
                
                
                
        $booking_parameters['CancellationPolicy'] = array($cancel_string);
        $booking_parameters['LastCancellationDate'] = $last_cancellation_date;
        $booking_parameters['CancellationPolicy_API'] =  array($room_details['HotelRoomsDetails'][0]['CancellationPolicy']);
        $booking_parameters['TM_Cancellation_Charge'] = $cancellation_details;
        $booking_parameters['Boarding_details'] = $room_details['HotelRoomsDetails'][0]['Boarding_details'];
        $booking_parameters['Surcharge_total'] = @$Surcharge_total;
        $booking_parameters['sur_Charge_exclude'] = @$room_details['HotelRoomsDetails'][0]['surCharge_exclude'];
        $booking_parameters['surCharge_exclude_name'] = @$room_details['HotelRoomsDetails'][0]['surCharge_exclude_name'];
        $booking_parameters ['price_summary'] = tbo_summary_room_combination ( $room_details ['HotelRoomsDetails'] );

        // debug($booking_parameters);
        // exit;
        return $booking_parameters;
    }
        /*php check array unique*/
    /**/
    function php_arrayUnique($array,$key){
         $temp_array = array(); 
            $i = 0; 
            $key_array = array(); 
            
            foreach($array as $val) { 
                if (!in_array($val[$key], $key_array)) { 
                    $key_array[$i] = $val[$key]; 
                    $temp_array[$i] = $val; 
                } 
                $i++; 
            } 
            return $temp_array; 
    }
    /**
     * parse data according to voucher needs
     * 
     * @param array $data           
     */
    function parse_voucher_data($data) {
        $response = $data;
        return $response;
    }
    
    /**
     * Balu A
     * convert search params to format
     */
    public function search_data($search_id) {
        $response ['status'] = true;
        $response ['data'] = array ();
        if (empty ( $this->master_search_data ) == true and valid_array ( $this->master_search_data ) == false) {
            $clean_search_details = $GLOBALS ['CI']->hotel_model->get_safe_search_data ( $search_id );
            
            if ($clean_search_details ['status'] == true) {
                $response ['status'] = true;
                $response ['data'] = $clean_search_details ['data'];
                // 28/12/2014 00:00:00 - date format
                $response ['data'] ['from_date'] = date ( 'd-m-Y', strtotime ( $clean_search_details ['data'] ['from_date'] ) );
                $response ['data'] ['to_date'] = date ( 'd-m-Y', strtotime ( $clean_search_details ['data'] ['to_date']));
                
                $response ['data'] ['raw_from_date'] = $clean_search_details ['data'] ['from_date'];
                $response ['data'] ['raw_to_date'] = $clean_search_details ['data'] ['to_date'];
                $response ['data'] ['location_id'] = $clean_search_details ['data'] ['hotel_destination'];
                $response ['data'] ['CityId'] =  $clean_search_details ['data'] ['hotel_destination'];

                $response ['data'] ['airport_code'] = "";

                
                    //get countrycode 
                $get_country_code = $GLOBALS['CI']->custom_db->single_table_records('tbo_city_list','*',array('origin'=>$clean_search_details ['data'] ['hotel_destination']));

                //debug($get_country_code); exit;
                     
                    if(@$clean_search_details['data']['search_type'] == 'location_search'){
                        $response ['data'] ['country_code'] = $clean_search_details['data']['countrycode'];
                    }
                    else{
                        $response ['data'] ['country_code'] = $get_country_code['data'][0]['countrycode'];
                    }

                        // $response ['data'] ['airport_code'] = $get_country_code['data'][0]['city_code'];

                        $this->master_search_data = $response ['data'];    
                        $response ['status'] = true;
                
            } else {
                $response ['status'] = false;
            }
        } else {
            $response ['data'] = $this->master_search_data;
        }
        
        $this->search_hash = md5 ( serialized_data ( $response ['data'] ) );

        //debug($response);exit;

        return $response;
    }
    
    /**
     * Markup for search result
     * 
     * @param array $price_summary          
     * @param object $currency_obj          
     * @param number $search_id         
     */
    function update_search_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
        $search_data = $this->search_data ( $search_id );
        $no_of_nights = $this->master_search_data ['no_of_nights'];
        $no_of_rooms = $this->master_search_data ['room_count'];
        //$multiplier = ($no_of_nights * $no_of_rooms);
        $multiplier = $no_of_nights;
        return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup );
    }
    /**
     * Markup for search result
     * 
     * @param array $price_summary          
     * @param object $currency_obj          
     * @param number $search_id         
     */
    function update_search_markup_currency_one_night(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
        $search_data = $this->search_data ( $search_id );
        $no_of_nights = $this->master_search_data ['no_of_nights'];
        $no_of_rooms = $this->master_search_data ['room_count'];
        //$multiplier = ($no_of_nights * $no_of_rooms);
        $multiplier = 1;
        return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup );
    }
    /**
     * Markup for Room List
     * 
     * @param array $price_summary          
     * @param object $currency_obj          
     * @param number $search_id         
     */
    function update_room_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
        
        $search_data = $this->search_data ( $search_id );
        $no_of_nights = $this->master_search_data ['no_of_nights'];
        $no_of_rooms = 1;
        //$multiplier = ($no_of_nights * $no_of_rooms);
        $multiplier = $no_of_nights;
        return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup );
    }
    
    /**
     * Markup for Booking Page List
     * 
     * @param array $price_summary          
     * @param object $currency_obj          
     * @param number $search_id         
     */
    function update_booking_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
        
        return $this->update_search_markup_currency ( $price_summary, $currency_obj, $search_id, $level_one_markup, $current_domain_markup );
    }
    /**
    *Update Markup currency for Cancellation Charge
    */
    function update_cancellation_markup_currency(&$cancel_charge,&$currency_obj,$search_id,$level_one_markup=false,$current_domain_markup=true){
        $search_data = $this->search_data ( $search_id );
        
        $no_of_nights = $this->master_search_data ['no_of_nights'];
        $temp_price = $currency_obj->get_currency ( $cancel_charge, true, $level_one_markup, $current_domain_markup, $no_of_nights );
                
        return round($temp_price['default_value']);
    }
    /**
     * update markup currency and return summary
     * $attr needed to calculate number of nights markup when its plus based markup
     */
    function update_markup_currency(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true) {
        
        $tax_service_sum = 0;
        $tax_removal_list = array ();
        $markup_list = array (
                'RoomPrice',
                'PublishedPrice',
                'PublishedPriceRoundedOff',
                'OfferedPrice',
                'OfferedPriceRoundedOff' 
        );
        $markup_summary = array ();
        foreach ( $price_summary as $__k => $__v ) {
            
            $ref_cur = $currency_obj->force_currency_conversion ( $__v ); // Passing Value By Reference so dont remove it!!!
            $price_summary [$__k] = $ref_cur ['default_value']; // If you dont understand then go and study "Passing value by reference"            
            if (in_array ( $__k, $markup_list )) {
                if ($__k == 'RoomPrice') {
                }
                $temp_price = $currency_obj->get_currency ( $__v, true, $level_one_markup, $current_domain_markup, $no_of_nights );
                
            } else {
                $temp_price = $currency_obj->force_currency_conversion ( $__v );
            }
            // echo 'herre';
            // debug($temp_price);exit;
            // adding service tax and tax to total
            if (in_array ( $__k, $tax_removal_list )) {
                $markup_summary [$__k] = round($temp_price ['default_value'] + $tax_service_sum);
            } else {
                $markup_summary [$__k] = round($temp_price ['default_value']);
            }           
        }
        
        $Markup = 0;
        if (isset($markup_summary['PublishedPrice'])) {
            $Markup = $markup_summary['PublishedPrice'] - $price_summary['PublishedPrice'];
        }
        $gst_value = 0;
        //adding gst
        if($Markup > 0 ){
            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
            if($gst_details['status'] == true){
                if($gst_details['data'][0]['gst'] > 0){
                    $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                }
            }
         }
        $markup_summary['_GST'] = $gst_value;
        $markup_summary['PublishedPrice'] =  round($markup_summary['PublishedPrice'] + $markup_summary['_GST']);
        $markup_summary['PublishedPriceRoundedOff'] =  round($markup_summary['PublishedPriceRoundedOff'] + $markup_summary['_GST']);
        $markup_summary['OfferedPrice'] =  round($markup_summary['OfferedPrice'] + $markup_summary['_GST']);
        $markup_summary['OfferedPriceRoundedOff'] =  round($markup_summary['OfferedPriceRoundedOff'] + $markup_summary['_GST']);
        $markup_summary['RoomPrice'] =  round($markup_summary['RoomPrice'] + $markup_summary['_GST']);
        $markup_summary['_Markup'] = $Markup;
       
      
        // debug($markup_summary);exit;
        return $markup_summary;
    }
    
    /**
     * Tax price is the price for which markup should not be added
     */
    function tax_service_sum($markup_price_summary, $api_price_summary) {
        // sum of tax and service ;
        //return ($api_price_summary ['ServiceTax'] + $api_price_summary ['Tax'] + ($markup_price_summary ['PublishedPrice'] - $api_price_summary ['PublishedPrice']));
        return (($api_price_summary ['Tax']+$markup_price_summary ['PublishedPrice'] - $api_price_summary ['PublishedPrice']));
    }
    
    /**
     * calculate and return total price details
     */
    function total_price($price_summary) {
        return ($price_summary ['OfferedPriceRoundedOff']);
        
    }
    function booking_url($search_id) {
        return base_url () . 'index.php/hotel/booking/' . intval ( $search_id );
    }
    /**
     * Balu A
     * 
     * @param
     *          $ChangeRequestStatus
     */
    private function ChangeRequestStatusDescription($ChangeRequestStatus) {
        $status_description = '';
        switch ($ChangeRequestStatus) {
            case 0 :
                $status_description = 'NotSet';
                break;
            case 1 :
                $status_description = 'Pending';
                break;
            case 2 :
                $status_description = 'InProgress';
                break;
            case 3 :
                $status_description = 'Processed';
                break;
            case 4 :
                $status_description = 'Rejected';
                break;
        }
        return $status_description;
    }
        
        
        function display_image($HotelPicture,$ResultIndex)
        {
                            header("Content-type: image/gif");
                            echo file_get_contents($HotelPicture);
        }
    
    /**
     * Get Filter Params - fliter_params
     */
    function format_search_response($hl, $cobj, $sid, $module = 'b2c', $fltr = array()) 
    {
            
        //  ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

        $level_one = true;
        $current_domain = true;
        if ($module == 'b2c') {
            $level_one = false;
            $current_domain = true;
        } else if ($module == 'b2b') {
            $level_one = true;
            $current_domain = true;
        }
        // debug($fltr);
        // exit;
        $h_count = 0;
        $HotelResults = array ();
        if (isset ( $fltr ['hl'] ) == true) {
            foreach ( $fltr ['hl'] as $tk => $tv ) {
                $fltr ['hl'] [urldecode ( $tk )] = strtolower ( urldecode ( $tv ) );
            }
        }
                // Creating closures to filter data
        $check_filters = function ($hd) use ($fltr) {
            $wifi_count = 0;
            if((string)$fltr['wifi']=='true'){
                if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){       
                         $wi_fi_searchparmas = 'Wi';
                         $wi_search = ucwords('wi-');
                         $wi_fi_small = 'wifi';
                        if($this->searchParams($hd['HotelAmenities'],$wi_fi_searchparmas)){
                            $wifi_count++;
                        }elseif ($this->searchParams($hd['HotelAmenities'],$wi_search)) {
                            $wifi_count++;
                        }elseif ($this->searchParams($hd['HotelAmenities'],$wi_fi_small)) {
                            $wifi_count++;
                        }    
                }
            }
            
            $break_fast_count = 0;
            if((string)$fltr['breakfast']=='true'){
                if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){       
                         $breakfast_smal = 'breakfast';                      
                         $breakfast = 'Breakfast';
                        if($this->searchParams($hd['HotelAmenities'],$breakfast_smal)){
                            $break_fast_count++;
                        }elseif ($this->searchParams($hd['HotelAmenities'],$breakfast)) {
                            $break_fast_count++;
                        } 
                }
            }
            
            $parking_count = 0;
            if((string)$fltr['parking']=='true'){
                if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){       
                         $parking = 'parking';                       
                         $park = 'park';
                        if($this->searchParams($hd['HotelAmenities'],$parking)){
                            $parking_count++;
                        }elseif ($this->searchParams($hd['HotelAmenities'],$park)) {
                            $parking_count++;
                        } 
                }
            }
            $swim_pool = 0;
            if((string)$fltr['swim_pool']=='true'){
                if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){       
                         $pool = 'pool';                         
                         $swim = 'Swim';
                        if($this->searchParams($hd['HotelAmenities'],$pool)){
                            $swim_pool++;
                        }elseif ($this->searchParams($hd['HotelAmenities'],$swim)) {
                            $swim_pool++;
                        } 
                }
            } 
            
            //echo $swim_pool.'<br/>';
            if (($wifi_count >0 || (string)$fltr['wifi']=='false')&&($break_fast_count >0 || (string)$fltr['breakfast']=='false')&&($parking_count >0 || (string)$fltr['parking']=='false')&&($swim_pool >0 || (string)$fltr['swim_pool']=='false')&&(valid_array ( @$fltr ['hl'] ) == false || (valid_array ( @$fltr ['hl'] ) == true && in_array ( strtolower ( $hd ['HotelLocation'] ), $fltr ['hl'] ))) && (valid_array ( @$fltr ['_sf'] ) == false || (valid_array ( @$fltr ['_sf'] ) == true && in_array ( $hd ['StarRating'], $fltr ['_sf'] ))) && (@$fltr ['min_price'] <= ceil ( $hd ['Price'] ['RoomPrice'] ) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= floor ( $hd ['Price'] ['RoomPrice'] ))) && (( string ) $fltr ['dealf'] == 'false' || empty ( $hd ['HotelPromotion'] ) == false)&& (( string ) $fltr ['free_cancel'] == 'false' || empty ( $hd ['Free_cancel_date'] ) == false)  && (empty ( $fltr ['hn_val'] ) == true || (empty ( $fltr ['hn_val'] ) == false && stripos ( strtolower ( $hd ['HotelName'] ), (urldecode ( $fltr ['hn_val'] )) ) > - 1))) {
                return true;
            } else {
                return false;
            }
        };
        $hc = 0;
        $frc = 0;

        $hl_tmp = force_multple_data_format($hl['HotelSearchResult']['HotelResults']);

        $hotel_codes = array();
        if (count($hl_tmp) > 0) {
        foreach ($hl_tmp as $v) {
            $hotel_codes[] = $v['HotelCode'];
        }
         }

        // Get hotel static data
        //$cache_list = $this->get_static_hotel_data($hotel_codes);

        foreach ( $hl ['HotelSearchResult'] ['HotelResults'] as $hr => $hd ) {
            $hc ++;
            // default values
            $hd ['StarRating'] = intval ( $hd ['StarRating'] );
            if (empty ( $hd ['HotelLocation'] ) == true) {
                $hd ['HotelLocation'] = 'Others';
            }
            if (isset ( $hd ['Latitude'] ) == false) {
                $hd ['Latitude'] = 0;
            }
            if (isset ( $hd ['Longitude'] ) == false) {
                $hd ['Longitude'] = 0;
            }

            $hd['HotelAmenitiesFilters'] = json_encode([
            ['icon_class'=>'WIF','name'=>'Wi-fi'],
            ['icon_class'=>'CAP','name'=>'Car park'],
            ['icon_class'=>'SWP','name'=>'Private Pool'],
            ['icon_class'=>'GYM','name'=>'Gym'],
            ['icon_class'=>'GLF','name'=>'Golf'],
            ['icon_class'=>'BFD','name'=>'Breakfast and dinner'],
            ['icon_class'=>'MBR','name'=>'Minibar'],
            ['icon_class'=>'GRD','name'=>'Garden'],
            ['icon_class'=>'LSR','name'=>'Laundry service'],
            ['icon_class'=>'LIF','name'=>'Lift access'],
            ['icon_class'=>'SPA','name'=>'Spa treatments'],
            ['icon_class'=>'DFR','name'=>'Disability-friendly rooms']
        ], true);

            // markup
            $hd ['Price'] = $this->update_search_markup_currency_one_night ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain );

            $hd['booking_source']       = $this->booking_source;
            
            // filter after initializing default data and adding markup
            if (valid_array ( $fltr ) == true && $check_filters ( $hd ) == false) {
                continue;
            }
            $HotelResults [$hr] = $hd;
            $frc ++;
            //echo 'count'.$frc;
        }
        // SORTING STARTS
        if (isset ( $fltr ['sort_item'] ) == true && empty ( $fltr ['sort_item'] ) == false && isset ( $fltr ['sort_type'] ) == true && empty ( $fltr ['sort_type'] ) == false) {
            $sort_item = array ();
            foreach ( $HotelResults as $key => $row ) {
                if ($fltr ['sort_item'] == 'price') {
                    $sort_item [$key] = floatval ( $row ['Price'] ['RoomPrice'] );
                } else if ($fltr ['sort_item'] == 'star') {
                    $sort_item [$key] = floatval ( $row ['StarRating'] );
                } else if ($fltr ['sort_item'] == 'name') {
                    $sort_item [$key] = trim ( $row ['HotelName'] );
                }
            }
            if ($fltr ['sort_type'] == 'asc') {
                $sort_type = SORT_ASC;
            } else if ($fltr ['sort_type'] == 'desc') {
                $sort_type = SORT_DESC;
            }
            if (valid_array ( $sort_item ) == true && empty ( $sort_type ) == false) {
                array_multisort ( $sort_item, $sort_type, $HotelResults );
            }
        } // SORTING ENDS
        $hl ['HotelSearchResult'] ['HotelResults'] = $HotelResults;
        $hl ['source_result_count'] = $hc;
        $hl ['filter_result_count'] = $frc;
        
        return $hl;
    }

    function get_tb_hotel_images($hotel_code)
    {
        $response['status'] = true;
        $response['data'] = array();

        $images = $this->get_static_hotel_data($hotel_code);

        $response['data'] = $images['images'][$hotel_code];
        return $response;
    }


    function get_static_hotel_data($hotel_code)
    {
        if (is_array($hotel_code)) 
        {
            $h_cond = 'hotel_code IN (\''. implode('\', \'', $hotel_code). '\')';

            $h_cond = str_replace("''","'",$h_cond);

            $query = 'SELECT hotel_code,Hotel_facilities,Images FROM tp_hotel_static_data  WHERE ' . $h_cond . '';  
        } 
        else 
        {
            $h_cond = 'hotel_code = "'.$hotel_code.'"';

            $query = 'SELECT hotel_code,Hotel_facilities,Images FROM tp_hotel_static_data  WHERE ' . $h_cond . '';  
        }

        $data = $GLOBALS['CI']->db->query($query)->result_array();

        $resp = array();

        if (valid_array($data) == true) 
        {
            foreach ($data as $k => $v) 
            {
                //  debug($v);die;
                if($v['Images'] != '') 
                {
                    $v['images'] = json_decode($v['Images'], true);
                }

                if($v['Hotel_facilities'] != '') 
                {
                    $v['Hotel_facilities'] = json_decode($v['Hotel_facilities'], true);
                }
                
                $resp['images'][$v['hotel_code']] = $v['images'];
                $resp['facilities'][$v['hotel_code']] = $v['Hotel_facilities'];
            }
        }

        //debug($resp); exit;

        return $resp;
    }

    /**
    *format Amenities search like mysql like query
    */
    private function searchParams($array,$needle){
        $search_count = 0;
        if($array){
            foreach($array as $key => $question)
            {                   
                if (strpos($question,"".$needle."" ) !== false) {
                   $search_count++;
                }elseif (strpos($question,"".$needle."" ) !== false) {
                   $search_count++;
                }elseif (strpos($question,"".$needle."" ) !== false) {
                   $search_count++;
                }         
            }
        }
        return $search_count;
    }
    /**
     * Break data into pages
     * 
     * @param
     *          $data
     * @param
     *          $offset
     * @param
     *          $limit
     */
    function get_page_data($hl, $offset, $limit) {
        $hl ['HotelSearchResult'] ['HotelResults'] = array_slice ( $hl ['HotelSearchResult'] ['HotelResults'], $offset, $limit );
        return $hl;
    }
    
    /**
     * Get Filter Summary of the data list
     * 
     * @param array $hl         
     */
    function filter_summary($hl) {
        $h_count = 0;
        $filt ['p'] ['max'] = false;
        $filt ['p'] ['min'] = false;
        $filt ['fac'] = array ();
        $filt ['loc'] = array ();
        $filt ['star'] = array ();
        $filt ['star'] = array ();
        $filt ['prop'] = array ();
        $filters = array ();
        foreach ( $hl['HotelSearchResult']['HotelResults'] as $hr => $hd ) {

           // debug($hd); exit;

            // filters
            $StarRating = intval ( @$hd ['StarRating'] );
            $HotelLocation = empty ( $hd ['HotelLocation'] ) == true ? 'Others' : $hd ['HotelLocation'];
            $HotelProperty =  $hd['accomodation_type'];

            $HotelFacilities = json_decode($hd ['HotelAmenities'], true);

            foreach($HotelFacilities as $key=>$val)
            {
                if(!empty($val))
                {
                    $filt ['fac'][$val['name']]['c'] = $key;
                    $filt ['fac'][$val['name']]['v'] = ucwords($val);
                }
            }
            
            if (isset ( $filt ['star'] [$StarRating] ) == false) {
                $filt ['star'] [$StarRating] ['c'] = 1;
                $filt ['star'] [$StarRating] ['v'] = $StarRating;
            } else {
                $filt ['star'] [$StarRating] ['c'] ++;
            }
            
            if (($filt ['p'] ['max'] != false && $filt ['p'] ['max'] < $hd ['Price'] ['RoomPrice']) || $filt ['p'] ['max'] == false) {
                $filt ['p'] ['max'] = roundoff_number ( $hd ['Price'] ['RoomPrice'] );
            }
            if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['RoomPrice']) || $filt ['p'] ['min'] == false) {
                $filt ['p'] ['min'] = roundoff_number ( $hd ['Price'] ['RoomPrice'] );
            }
            
            if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['RoomPrice']) || $filt ['p'] ['min'] == false) {
                $filt ['p'] ['min'] = $hd ['Price'] ['RoomPrice'];
            }
            $hloc = ucfirst ( strtolower ( $HotelLocation ) );
            if (isset ( $filt ['loc'] [$hloc] ) == false) {
                $filt ['loc'] [$hloc] ['c'] = 1;
                $filt ['loc'] [$hloc] ['v'] = $hloc;
            } else {
                $filt ['loc'] [$hloc] ['c'] ++;
            }

            $hprop = ucfirst ( strtolower ( $HotelProperty ) );

            if (isset ( $filt ['prop'] [$hprop] ) == false) 
            {
                $filt ['prop'][$hprop] ['c'] = 1;
                $filt ['prop'][$hprop] ['v'] = $hprop;
            } 
            else 
            {
                $filt ['prop'][$hprop] ['c'] ++;
            }
            
            $filters ['data'] = $filt;
            $h_count ++;
        }
        ksort ( $filters ['data'] ['loc'] );
        $filters ['hotel_count'] = $h_count;

        //debug($filters); exit;

        return $filters;
    }
    /**
     * Roomwise Assigned Passenger Count
     * @param unknown_type $pax_type_arr
     * @param unknown_type $pax_type
     */
    function get_assigned_pax_type_count($pax_type_arr, $pax_type)
    {
        $pax_type_count = 0;
        if(valid_array($pax_type_arr) == true){
            foreach ($pax_type_arr as $k => $v){
                if($pax_type == $v){
                    $pax_type_count++;
                }
            }
        }
        return $pax_type_count;
    }
    function get_agoda_bookings_list(){
        $header = $this->get_header ();
        $this->credentials ( 'AgodaBookingList' );
        // echo $this->service_url;exit;
        $url = $this->service_url;
        $request1['from_date'] = '2018-03-10';
        $request1['to_date'] = '2018-05-20';
        $request = json_encode($request1);
        $get_hotel_list_response = $GLOBALS ['CI']->api_interface->get_json_response ($url, $request, $header );
        debug($get_hotel_list_response);exit;
        
        return $response;
    }

    public function form_curl_params($request, $url,$method)
    {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        $Authorization = base64_encode($this->UserName .':'. $this->Password);
        $curl_data = array();

        $curl_data['request'] = $request;
        $curl_data['url'] = $url.'/'.$method;
        $curl_data['header'] = array(
            'Content-Type: application/json',
            "Authorization: Basic $Authorization",
            );
        $data['data'] = $curl_data;
        return $data;
    }
    
}   

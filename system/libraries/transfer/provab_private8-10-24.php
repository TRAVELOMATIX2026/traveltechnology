<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once BASEPATH . 'libraries/Common_Api_Grind.php';

/**
 *
 * @package Provab
 * @subpackage API
 * @author Balu A<abaluvijay@gmail.com>
 * @version V1
 */
class Provab_private extends Common_Api_Grind {

    private $ClientId;
    private $UserName;
    private $Password;
    private $service_url;
    private $Url;
    public $master_search_data;
    public $search_hash;

    public function __construct() {
        $this->CI = &get_instance();
        $GLOBALS ['CI']->load->library('Api_Interface');
        $GLOBALS ['CI']->load->model('transfer_model');
        $this->TokenId = $GLOBALS ['CI']->session->userdata('tb_auth_token');
        $this->set_api_credentials();
    }

    private function get_header() {
        $transfer_engine_system = $this->CI->transfer_engine_system;
        $user_name = $this->CI->transfer_engine_system. '_username';
        $password = $this->CI->transfer_engine_system. '_password';
        $response ['UserName'] = $this->CI->$user_name;
        $response ['Password'] = $this->CI->$password;
        $response ['DomainKey'] = $this->CI->domain_key;
        $response ['system'] = $transfer_engine_system;
        return $response;
    }

    private function set_api_credentials() {
       $transfer_engine_system = $this->CI->transfer_engine_system;
        $this->system = $transfer_engine_system;
        $user_name = $this->CI->transfer_engine_system. '_username';
        $password = $this->CI->transfer_engine_system. '_password';
        $this->UserName = $this->CI->$user_name;
        $this->Password = $this->CI->$password;
        $this->Url =  $this->CI->transferv1_url;
        $this->ClientId = $this->CI->domain_key;
    }

    function credentials($service) {

        switch ($service) {
            case 'Search' :
                $this->service_url = $this->Url . 'Search';
                break;
            case 'BlockTransfer':
                $this->service_url = $this->Url.'BlockTransfer';
                break;
            case 'Book':
                $this->service_url = $this->Url.'CommitBooking';
                break;
            case 'CancelBooking':
                $this->service_url = $this->Url.'CancelBooking';
                break;
        }
    }

    function get_transfer_list($search_id = '') {
        $this->CI->load->driver('cache');
        $response['data'] = array();
        $response['status'] = true;
        $search_data = $this->search_data($search_id);

        $header_info = $this->get_header();

        $cache_search = $this->CI->config->item('cache_flight_search');
        $search_hash = $this->search_hash;
        if ($cache_search) {
            $cache_contents = $this->CI->cache->file->get($search_hash);
        }
        if ($search_data['status'] == true) {
            if ($cache_search === false || ($cache_search === true && empty($cache_contents) == true)) {
                $search_request = $this->transfer_search_request($search_data['data']);

                if ($search_request['status']) {
                    $search_response = $this->CI->api_interface->get_json_response($search_request['data']['service_url'], $search_request['data']['request'], $header_info);

                    $GLOBALS['CI']->custom_db->generate_static_response(json_encode($search_response));
                    if ($this->valid_search_result($search_response)) {
                        $response ['data'] = $search_response['Search'];
                        if ($cache_search) {
                            $cache_exp = $this->CI->config->item('cache_transfer_search_ttl');
                            $this->CI->cache->file->save($search_hash, $response ['data'], $cache_exp);
                        }
                      
                    } else {
                        $response ['status'] = false;
                    }
                }
            } else {
                $response['data'] = $cache_contents;
                $response['search_hash'] = $search_hash;
                $response['from_cache'] = true;
            }
        } else {
            $response ['status'] = false;
        }
        return $response;
    }
    /**
     * Converts API data currency to preferred currency
     * Elavarasi
     * 
     * @param unknown_type $search_result           
     * @param unknown_type $currency_obj            
     */
    public function search_data_in_preferred_currency($search_result, $currency_obj,$module='b2c') {

        $sightseeing = $search_result ['data'] ['TransferSearchResult'] ['TransferResults'];
        $sightseeing_list = array ();
        foreach ( $sightseeing as $hk => $hv ) {
            $sightseeing_list [$hk] = $hv;
            $sightseeing_list [$hk] ['Price'] = $this->module_preferred_currency_fare_object ( $hv ['Price'], $currency_obj,'',$module );
        }
        $search_result ['data'] ['TransferSearchResult'] ['PreferredCurrency'] = get_application_currency_preference ();
        $search_result ['data'] ['TransferSearchResult'] ['TransferResults'] = $sightseeing_list;
        return $search_result;
    }
        /**
     * Elavarasi
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     */
    private function module_preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {

        #debug($fare_details);
        $admin_commission = 0;
        $admin_tdson_commission = 0;
        $agent_commission = 0;
        $agent_tdson_commission = 0;
        $org_commission = 0;
        $or_tdson_commission = 0;
        $admin_profit = 0;
        $show_net_fare = 0;

        if(isset($fare_details['PriceBreakup'])){
            $admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
            $admin_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
        }else{
            $admin_commission = $fare_details['AgentCommission'];
            $admin_tds =$fare_details['AgentTdsOnCommision'];
        }

        if($module=='b2c'){

            $net_fare = $fare_details['TotalDisplayFare']-$admin_commission+$admin_tds;     
            $agent_commission = $admin_commission;
            $agent_tdson_commission =$admin_tds;
            
            
        }else{
            //for b2b users
            //Updating Commission           
            // debug($fare_details);
            // exit;
            $agent_commission = $fare_details['PriceBreakup']['AgentCommission'];
            $agent_tdson_commission = $fare_details['PriceBreakup']['AgentTdsOnCommision'];         
            $net_fare =$fare_details['TotalDisplayFare'];
            #$admin_commission = $admin_commission;
            #$admin_tdson_commission = $agent_tds;
        }
        //echo $net_fare;
        $price_details = array ();
        $price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();

    

        $price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $net_fare) );

        $price_details['AgentCommission'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_commission) );

        $price_details['AgentTdsOnCommision'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_tdson_commission) );
        //$price_details['AdminCommProfit'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $admin_profit) );
        if($module=='b2c'){
            $show_net_fare = $price_details ['TotalDisplayFare'];
            //$price_details ['TotalDisplayFare'] = $show_net_fare;
        }else{
            $show_net_fare = ($price_details ['TotalDisplayFare']-$price_details['AgentCommission']);   
        }
        
        $price_details ['NetFare'] =$show_net_fare;

        //$price_details ['GSTPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['GSTPrice'] ) );       
        #debug($price_details);
        #exit;
        return $price_details;
    }
    /**
     * Get Filter Params - fliter_params
     */
    function format_search_response($sl, $cobj, $sid, $module = 'b2c', $fltr = array()) {

        //debug($fltr);
        
        $level_one = true;  
        $current_domain = true;
        if ($module == 'b2c') {
            $level_one = false;
            $current_domain = true;
        } else if ($module == 'b2b') {
            $level_one = true;
            $current_domain = true;
        }
        $h_count = 0;
        $tarnsferResults = array ();
        // debug($fltr);
        // exit;
        // Creating closures to filter data
        $check_filters = function ($hd) use ($fltr) {       

            //debug($hd['Cat_Ids']);
            //debug(array_intersect($fltr ['cate'], $hd ['Cat_Ids']));

            if (

                (valid_array ( @$fltr ['_category'] ) == false ||
                (valid_array ( @$fltr ['_category'] ) == true && in_array ( $hd ['categoryCode'], $fltr ['_category'] ))) &&

                ( isset($fltr['_vehicle']) == false || (valid_array ( @$fltr ['_vehicle'] ) == false || (valid_array ( @$fltr ['_vehicle'] ) == true && in_array ( $hd ['vehicleCode'], $fltr ['_vehicle'] ))) ) &&

                (@$fltr ['min_price'] <= ceil ( $hd ['Price'] ['TotalDisplayFare'] ) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= floor ( $hd ['Price'] ['TotalDisplayFare'] ))) &&

                (empty ( $fltr ['an_val'] ) == true || (empty ( $fltr ['an_val'] ) == false && stripos ( strtolower ( $hd ['transferType'] ), (urldecode ( $fltr ['an_val'] )) ) > - 1))

                

            )


            {

                return true;
            } else {
                return false;
            }
        };
        $sc = 0;
        $frc = 0;
        foreach ( $sl ['TransferSearchResult'] ['TransferResults'] as $hr => $hd ) {
            $sc ++;
            // default values
            
            $api_price_details = $hd['Price'];
            
            if($module=='b2b'){
                $this->get_commission($hd['Price'],$cobj);
                $admin_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, true, false, $module );
                    // markup
                $agent_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain, $module );

                $hd ['Price'] = $this->b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $cobj);
                

            }else{
                    // markup
                $hd ['Price'] = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain, $module );
            }
        
            // filter after initializing default data and adding markup
            if (valid_array ( $fltr ) == true && $check_filters ( $hd ) == false) {
                continue;
            }
            $tarnsferResults [$hr] = $hd;
            $frc ++;
        }
        // SORTING STARTS
        if (isset ( $fltr ['sort_item'] ) == true && empty ( $fltr ['sort_item'] ) == false && isset ( $fltr ['sort_type'] ) == true && empty ( $fltr ['sort_type'] ) == false) {
            $sort_item = array ();
            foreach ( $tarnsferResults as $key => $row ) {
                if ($fltr ['sort_item'] == 'price') {
                    $sort_item [$key] = floatval ( $row ['Price'] ['TotalDisplayFare'] );
                } else if ($fltr ['sort_item'] == 'category') {
                    $sort_item [$key] = floatval ( $row ['categoryCode'] );
                } else if ($fltr ['sort_item'] == 'name') {
                    $sort_item [$key] = trim ( $row ['transferType'] );
                }
            }
            if ($fltr ['sort_type'] == 'asc') {
                $sort_type = SORT_ASC;
            } else if ($fltr ['sort_type'] == 'desc') {
                $sort_type = SORT_DESC;
            }
            if (valid_array ( $sort_item ) == true && empty ( $sort_type ) == false) {
                array_multisort ( $sort_item, $sort_type, $tarnsferResults );
            }
        } // SORTING ENDS
        // echo "sc".$sc.'<br/>';   
        // echo "frc".$frc.'<br/>';
        // exit;
        $sl ['TransferSearchResult'] ['TransferResults'] = $tarnsferResults;
        $sl ['source_result_count'] = $sc;
        $sl ['filter_result_count'] = $frc;
        // debug($sl);
        // exit;
        return $sl;
    }
    /**
     *
     * @param array $api_price_details
     * @param array $admin_price_details
     * @param array $agent_price_details
     * @return number
     */
    function b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $currency_obj) {

        #$total_price['BaseFare'] = $api_price_details['BaseFare'];
        $total_price['_CustomerBuying'] = $agent_price_details['TotalDisplayFare'];
        $total_price['TotalDisplayFare'] =  $total_price['_CustomerBuying'] ;
        $total_price['_Commission'] = round($agent_price_details['TotalDisplayFare'] - $agent_price_details['NetFare'], 3);
        $total_price['_tdsCommission'] = $currency_obj->calculate_tds($total_price['_Commission']); //Includes TDS ON PLB AND COMMISSION


        $_AgentBuying = $admin_price_details['NetFare'];

        $total_price['NetFare'] = $_AgentBuying;

        $total_price['_AdminBuying'] = $api_price_details['NetFare'];

        $total_price['_AgentMarkup'] = $total_price['_Markup'] = $agent_price_details['NetFare'] - $admin_price_details['NetFare'];
        $total_price['_AdminMarkup'] = ($_AgentBuying - $total_price['_AdminBuying']);
        $total_price['_OrgAdminMarkup'] = $admin_price_details['_Markup'];
        $total_markup = $total_price['_OrgAdminMarkup'] + $total_price['_AgentMarkup'];
        // echo $total_markup ;exit;
        $gst_value = 0;
        if($total_markup > 0 ){
            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfer'));
            if($gst_details['status'] == true){
                if($gst_details['data'][0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details['data'][0]['gst'];
                    $gst_value  = roundoff_number($gst_value);
                }
            }
        }

        $total_price['_AgentEarning'] = $total_price['_Commission'] + $total_price['_Markup'] - $total_price['_tdsCommission'];

        $total_price['_TaxSum'] = 0;
        $total_price['_AgentBuying'] = $admin_price_details['NetFare']+$total_price['_tdsCommission']+$gst_value;
       
        $total_price['_TotalPayable'] = $total_price['_AgentBuying'];
        $total_price['_CustomerBuying'] = $agent_price_details['TotalDisplayFare']+$gst_value;
        $total_price['TotalDisplayFare'] = $total_price['_CustomerBuying'];
        $total_price['_GST'] = $gst_value;
        // debug($total_price);
        // exit;
        return $total_price;
    }
     /**
     * Get admin Commission details
     */
    function get_commission(&$fare_details, & $currency_obj) {

        
        $this->commission = $currency_obj->get_commission();      
        

        if (valid_array($this->commission) == true && intval($this->commission['admin_commission_list']['value']) > 0) {
            //update commission
            //$bus_row = array(); Preserving Row data before calculation
            $core_agent_commision = ($fare_details['TotalDisplayFare'] - $fare_details['NetFare']);

            $com = $this->calculate_commission($core_agent_commision);
           
            $this->set_b2b_comm_tag($fare_details, $com, $currency_obj);
        } else {
            //update commission
            $this->set_b2b_comm_tag($fare_details, 0, $currency_obj);
        }
    }
    /**
     * Add custom commission tag for b2b only
     * @param array     s$v
     * @param number    $b2b_com
     */
    function set_b2b_comm_tag(& $v, $b2b_com = 0, $currency_obj) {

        
        $v['ORG_AgentCommission'] = $v['AgentCommission'];
        $v['ORG_TdsOnCommission'] = $v['AgentTdsOnCommision'];
        $v['ORG_NetFare'] = $v['NetFare'];

        //$admin_com = $v['AgentCommission'] - $b2b_com;
        $core_agent_commision = ($v['TotalDisplayFare']-$v['NetFare']);
        $admin_com = $core_agent_commision - $b2b_com;
        $v['ORG_AdminCommission'] =$admin_com;
        //$v['OfferedFare'] = $v['TotalDisplayFare'] + $admin_com;
        $v['AgentCommission'] = $b2b_com;
        $v['AgentTdsOnCommision'] = $currency_obj->calculate_tds($b2b_com);
        $v['NetFare'] = $v['NetFare'] + $admin_com;

    }
     /**
     *Calculate commission
     */
    private function calculate_commission($agent_com) {
        $agent_com_row = $this->commission['admin_commission_list'];
        $b2b_comm = 0;
        if ($agent_com_row['value_type'] == 'percentage') {
            //%
            $b2b_comm = ($agent_com / 100) * $agent_com_row['value'];
        } else {
            //plus
            $b2b_comm = ($agent_com - $agent_com_row['value']);
        }       
        return number_format($b2b_comm, 2, '.', '');
    }
    /**
     * Markup for search result
     * 
     * @param array $price_summary          
     * @param object $currency_obj          
     * @param number $search_id         
     */
    function update_search_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true, $module='') {       
        $multiplier = 1;
        return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup, $module );
    }

    function transfer_search_request($search_params) {
        $response['status'] = SUCCESS_STATUS;
        $response['data'] = array();
        /** Request to be formed for search * */
        $this->credentials('Search');
        $request_params = array();


        //$request_params['FromName'] = (is_array($search_params['FromName']) ? $search_params['FromName'] : array($search_params['FromName']));
       // $request_params['ToName'] = (is_array($search_params['ToName']) ? $search_params['ToName'] : array($search_params['ToName']));
        $request_params['OriginCode'] = $search_params['OriginCode'];
        $request_params['DestinationCode'] = $search_params['DestinationCode'];
        //$request_params['FromTerminal'] = (is_array($search_params['FromTerminal']) ? $search_params['FromTerminal'] : array($search_params['FromTerminal']));
        //$request_params['ToTerminal'] = (is_array($search_params['ToTerminal']) ? $search_params['ToTerminal'] : array($search_params['ToTerminal']));

        $request_params['DepartureDate'] = $search_params['DepartureDate'];
        if ($search_params['Type'] == "circle") {
            $request_params['ReturnDate'] = $search_params['ReturnDate'];
        }

        $request_params['AdultCount'] = $search_params['Adult'];
        $request_params['ChildCount'] = $search_params['Child'];
        $request_params['ChildAge'] = $search_params['ChildAge'];
        $request_params['JourneyType'] = $search_params['Type'];


        //Converting to an array
        $response['data']['request'] = json_encode($request_params);
        $response['data']['service_url'] = $this->service_url;

        return $response;
    }

  
    public function search_data($search_id) {
        $response['status'] = true;
        $response['data'] = array();
        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            $clean_search_details = $this->CI->transfer_model->get_safe_search_data($search_id);
            if ($clean_search_details ['status'] == true) {

                $response['status'] = true;
                // $response['data'] = $clean_search_details['data'];

                switch ($clean_search_details['data']['trip_type']) {
                    case 'oneway':
                        $response['data']['Type'] = 'OneWay';
                        $response['data']['DepartureDate'] = $clean_search_details['data']['depature'];
                        break;
                    case 'circle':
                        $response['data']['Type'] = 'circle';
                        $response['data']['DepartureDate'] = $clean_search_details['data']['depature'];
                        $response['data']['ReturnDate'] = $clean_search_details['data']['depature'];
                        break;
                }
                $response['data']['FromName'] = $clean_search_details['data']['from'];
                $response['data']['ToName'] = $clean_search_details['data']['to'];
                $response['data']['OriginCode'] = $clean_search_details['data']['from_code'];
                $response['data']['DestinationCode'] = $clean_search_details['data']['to_code'];
                $response['data']['FromTerminal'] = $clean_search_details['data']['from_transfer_type'];
                $response['data']['ToTerminal'] = $clean_search_details['data']['to_transfer_type'];
                $response['data']['Adult'] = $clean_search_details['data']['adult'];
               
                $response['data']['Child'] = $clean_search_details['data']['child'];
                $child_ages = array();
                if($clean_search_details['data']['child'] > 0){
                    $child_ages = $clean_search_details['data']['child_ages'];
                }
                $response['data']['ChildAge'] = $child_ages;
            } else {
                $response ['status'] = false;
            }
        } else {
            $response ['data'] = $this->master_search_data;
        }
        $this->search_hash = md5(serialized_data($response ['data']));

        return $response;
    }

    /**
     * Get Filter Summary of the data list
     * 
     * @param array $sl         
     */
    function filter_summary($sl) {
        $s_count = 0;
        $filt ['p'] ['max'] = false;
        $filt ['p'] ['min'] = false;
        
        $filt ['categories'] = array ();
        $filt ['vehicle'] = array ();
        $filt ['suitcases'] = array ();
        $filters = array ();
        foreach ( $sl ['TransferSearchResult'] ['TransferResults'] as $hr => $hd ) {
            // filters
            $categoryCode = @$hd ['categoryCode'];
            $vehicleCode =  @$hd ['vehicleCode']; 
            $suitcases =  (int)@$hd ['permittedSuitcases'];        
            
            if (isset ( $filt ['categories'] [$categoryCode] ) == false) {
                $filt ['categories'] [$categoryCode] ['c'] = 1;
                $filt ['categories'] [$categoryCode] ['v'] = $hd['categoryName'];
            } else {
                $filt ['categories'] [$categoryCode] ['c'] ++;
            } 
            if (isset ( $filt ['vehicle'] [$vehicleCode] ) == false) {
                $filt ['vehicle'] [$vehicleCode] ['c'] = 1;
                $filt ['vehicle'] [$vehicleCode] ['v'] = $hd['vehicleName'];
            } else {
                $filt ['vehicle'] [$vehicleCode] ['c'] ++;
            }
            if (isset ( $filt ['suitcases'] [$suitcases] ) == false) {
                $filt ['suitcases'] [$suitcases] ['c'] = 1;
                $filt ['suitcases'] [$suitcases] ['v'] = $suitcases;
            } else {
                $filt ['suitcases'] [$suitcases] ['c'] ++;
            }           
            if (($filt ['p'] ['max'] != false && $filt ['p'] ['max'] < $hd ['Price'] ['TotalDisplayFare']) || $filt ['p'] ['max'] == false) {
                $filt ['p'] ['max'] = roundoff_number ( $hd ['Price'] ['TotalDisplayFare'] );
            }
            if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['TotalDisplayFare']) || $filt ['p'] ['min'] == false) {
                $filt ['p'] ['min'] = roundoff_number ( $hd ['Price'] ['TotalDisplayFare'] );
            }           
            if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['TotalDisplayFare']) || $filt ['p'] ['min'] == false) {
                $filt ['p'] ['min'] = $hd ['Price'] ['TotalDisplayFare'];
            }  

            
            $filters ['data'] = $filt;
            $s_count ++;
        }       
        $filters ['transfer_count'] = $s_count;
        // debug($filters);
        // exit;
        return $filters;
    }
    /**
     * Elavarasi
     * Block Trip Before Going for payment and showing final booking page to user - Viator Rule
     * 
     * @param array $pre_booking_params
     *          All the necessary data required in block trip request - fetched from triplist and tourgrade  Request
     */
    function block_transfer($pre_booking_params) {

        $header = $this->get_header ();
        $response ['status'] = false;
        $response ['data'] = array ();
        $block_transefer_request = $this->get_block_transfer_request ( $pre_booking_params );
        $application_default_currency = admin_base_currency ();

        if ($block_transefer_request ['status'] == ACTIVE) {
           $GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $block_transefer_request ['data'] ['request'] ) );
                $block_transfer_response = $GLOBALS ['CI']->api_interface->get_json_response ( $block_transefer_request ['data'] ['service_url'], $block_transefer_request ['data'] ['request'], $header );
                
                $GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $block_transfer_response ) );
                if ($this->valid_response ( $block_transfer_response ['Status']) == false) {
                    $response ['status'] = false; // Indication for room block
                    if(empty($block_transfer_response['Message'])==false){
                        $response ['data'] ['msg'] = $block_transfer_response['Message'];
                    }else{
                        $response ['data'] ['msg'] = 'Some Problem Occured. Please Search Again to continue';
                    }
                } else {

                    $response ['status'] = SUCCESS_STATUS;
                    $block_transfer_response = $block_transfer_response['BlockTransfer'];
                    
                }
        
            $response ['data'] ['BlockTransfer'] = $block_transfer_response;
            
        }
        
        return $response;
    }
    /**
     *
     * @param array $booking_params         
     */
    function process_booking($book_id, $booking_params,$module='b2c') {
        // debug($booking_params);exit;
        $header = $this->get_header ();
        $response ['status'] = FAILURE_STATUS;
        $response ['data'] = array ();      
        $book_request = $this->get_book_request ( $booking_params, $book_id );
        //debug($book_request);exit;
        if($book_request['data']['status']){
            $GLOBALS ['CI']->custom_db->generate_static_response ( $book_request ['data'] ['request'] ); // release this
            
            $book_response = $GLOBALS ['CI']->api_interface->get_json_response ( $book_request ['data'] ['service_url'], $book_request ['data'] ['request'], $header ); 
            $GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $book_response ) );            
            
            $api_book_response_status = $book_response['Status'];
            
            /**
             * PROVAB LOGGER *
             */
            $GLOBALS ['CI']->private_management_model->provab_xml_logger ( 'Book_Transfers', $book_id, 'transfer', $book_request ['data'] ['request'], json_encode ( $book_response ) );
            // validate response
            if ($this->valid_response ( $api_book_response_status )) {

                $book_response['BookResult'] = $book_response['CommitBooking']['BookingDetails'];
                $response ['status'] = SUCCESS_STATUS;
                $response ['data'] ['book_response'] = $book_response;
                $response ['data'] ['booking_params'] = $booking_params;
                
                // Convert Room Book Data in Application Currency
                $block_data_array = $book_request ['data'] ['request'];
                $transfer_book_data = json_decode ( $block_data_array, true );
                $response['data']['transfer_book_request'] = $transfer_book_data; 
                $response ['data'] ['transfer_book_data'] = $this->convert_tripbook_data_to_application_currency ( $booking_params,$module );
            }else{
                $response['Message'] = $book_response['Message'];
            }
        }else{
            $response['Message'] = "Invalid Client Booking Request";
        }
        
        return $response;
    }
    public function update_booking_details($book_id, $book_params, $ticket_details,$currency_obj,$module = 'b2c'){
        $app_reference = $book_id;
        $master_search_id = $book_params['booking_params']['token']['search_id'];
        $search_data = $this->search_data($master_search_id);
        $paxes[0]['type'] = "Adult";
        $paxes[0]['count'] = $search_data['data']['Adult'];
        $paxes[1]['type'] = "Child";
        $paxes[1]['count'] = $search_data['data']['Child'];
        $deduction_cur_obj = clone $currency_obj;
        //Setting Master Booking Status
        $master_transaction_status = $this->status_code_value($ticket_details['master_booking_status']);
        if (isset($ticket_details['TicketDetails']) == true && valid_array($ticket_details['TicketDetails']) == true) {
            $ticket_details = $ticket_details['TicketDetails'];
        } else {
            $ticket_details = array();
        }
        $saved_booking_data = $GLOBALS['CI']->transfer_model->get_booking_details($book_id);
        if ($saved_booking_data['status'] == false) {
            $response['status'] = BOOKING_ERROR;
            $response['msg'] = 'No Data Found';
            return $response;
        }
        $s_master_data = $saved_booking_data['data']['booking_details'][0];
        $s_booking_itinerary_details = $saved_booking_data['data']['booking_itinerary_details'];
        $s_booking_customer_details = $saved_booking_data['data']['booking_customer_details'];
        $booking_attriutes = json_decode($s_master_data['attributes'], true);
        $attributes = $book_params['book_response']['BookResult']['booking_attributes'];
        $booking_attriutes = array_merge($booking_attriutes, $attributes);
        $passenger_origins = group_array_column($s_booking_customer_details, 'origin');
        $itinerary_origins = group_array_column($s_booking_itinerary_details, 'origin');
        $transferv1_master_booking_status = $master_transaction_status;
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
        $booking_id = $book_params['book_response']['BookResult']['BookingId'];
        $BookingRefNo = $book_params['book_response']['BookResult']['BookingRefNo'];
        $ConfirmationNo = $book_params['book_response']['BookResult']['ConfirmationNo'];
        $multiplier  =1;
        $GLOBALS['CI']->custom_db->update_record('transfer_booking_details', array('status' => $master_transaction_status,'booking_id' => $booking_id,'booking_reference' => $BookingRefNo,'confirmation_reference' => $ConfirmationNo, 'attributes' =>json_encode($booking_attriutes)), array('app_reference' => $app_reference));
        $total_pax_count = count($book_params['booking_params']['passenger_type']);
        $pax_count = $total_pax_count;
        $Fare =  $book_params ['booking_params']['token']['API_Price'];
        $final_booking_price_details = $this->get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module);
        //debug($final_booking_price_details);exit;
        $book_total_fare = $commissionable_fare =$final_booking_price_details['commissionable_fare'];
        $promo_currency_obj = @$book_params['promo_currency_obj'];
        $total_fare = $trans_total_fare =$final_booking_price_details['trans_total_fare'];
        $admin_markup =  $book_domain_markup = $final_booking_price_details['admin_markup'];
        $agent_markup = $final_booking_price_details['agent_markup'];
        $admin_commission = $final_booking_price_details['admin_commission'];
        $agent_commission = $final_booking_price_details['agent_commission'];
        $admin_tds = $final_booking_price_details['admin_tds'];
        $agent_tds = $final_booking_price_details['agent_tds'];
        if($module == 'b2c'){
            $total_markup = $admin_markup;
        }
        else if($module == 'b2b'){
            $total_markup = $admin_markup+$agent_markup;
        }
        $gst_value = 0;
        if($total_markup > 0 ){
            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfer'));
            if($gst_details['status'] == true){
                if($gst_details['data'][0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details['data'][0]['gst'];
                    $gst_value  = roundoff_number($gst_value);
                }
            }
        }
        $attributes = '';
        $api_raw_fare = $book_params ['booking_params']['token']['Price']['TotalDisplayFare'];
        $agent_buying_price = $book_params['booking_params']['token']['markup_price_summary']['NetFare'];
        $admin_net_fare_markup = 0;
        $GLOBALS['CI']->custom_db->update_record('transfer_booking_itinerary_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));
        $GLOBALS['CI']->custom_db->update_record('transfer_booking_pax_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));
        /**
             * ************ Update Convinence Fees And Other Details Start *****************
             */
            // Convinence_fees to be stored and discount
        $convinence = 0;
        $discount = 0;
        $convinence_value = 0;
        $convinence_type = 0;
        $convinence_per_pax = 0;

        if ($module == 'b2c') {
            $total_transaction_amount = $trans_total_fare+$book_domain_markup+$gst_value;
                $convinence = $currency_obj->convenience_fees ($total_transaction_amount ,$paxes );
            $convinence_row = $currency_obj->get_convenience_fees ();
            $convinence_value = $convinence_row ['value'];
            $convinence_type = $convinence_row ['type'];
            $convinence_per_pax = $convinence_row ['per_pax']; 
            if(isset($book_params['promo_actual_value'])){
                    $discount = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $book_params['promo_actual_value']) );
                }
                
            //$discount = @$book_params['promo_code_discount_val'];
            $promo_code = @$book_params ['promo_code'];
        } elseif ($module == 'b2b') {
            $discount = 0;
            $promo_code='';
        }
        $response ['fare'] = $total_fare;
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
    private function convert_tripbook_data_to_application_currency($transfer_book_data,$module='b2c') {
        $application_default_currency = admin_base_currency ();
        $currency_obj = new Currency ( array (
                'module_type' => 'transfer',
                'from' => get_api_data_currency (),
                'to' => admin_base_currency () 
        ) );
        $master_trip_book_data = array ();
        $TransferTripDetails = array();
        
        $TransferTripDetails['Price'] = $this->module_preferred_currency_fare_object ( $transfer_book_data['token']['Price'], $currency_obj, $application_default_currency,$module );
        $master_trip_book_data = $transfer_book_data;
        $master_trip_book_data ['Price'] = $TransferTripDetails;
        return $master_trip_book_data;
    }

    /**
     * Elavarasi
     * Form Book Request
     */
    function get_book_request($booking_params, $booking_id) {
        $response =array();
        $request = array();
        $request['AppReference'] = trim($booking_id);
        $request['BlockTransferId'] = trim($booking_params['BlockTransferId']);
        $passenger_details =array();
        #debug($booking_params);
        foreach ($booking_params['passenger_type'] as $key => $value) {

            $passenger_details[$key]['Title'] = $booking_params['name_title'][$key];

            $passenger_details[$key]['FirstName'] = $booking_params['first_name'][$key];
            $passenger_details[$key]['LastName'] = $booking_params['last_name'][$key];
            $passenger_details[$key]['Phoneno'] = $booking_params['passenger_contact'];
            $passenger_details[$key]['Email'] = $booking_params['billing_email'];
            $passenger_details[$key]['PaxType'] = $booking_params['passenger_type'][$key];
            if($key==0){
                $passenger_details[$key]['LeadPassenger'] = 1;  
            }else{
                $passenger_details[$key]['LeadPassenger'] = 0;
            }
        }
        $request['PassengerDetails'] = $passenger_details;
        $bookingQuestions = array();
        if(isset($booking_params['question_Id'])){
            if($booking_params['question_Id']){
                foreach ($booking_params['question_Id'] as $q_key => $q_value) {
                    //debug($q_value);
                    $bookingQuestions[$q_key]['id'] = $q_value[0];
                    $bookingQuestions[$q_key]['answer'] = $booking_params['question'][$q_key][0];
                }
            }
        }
        $request['BookingQuestions'] = $bookingQuestions;
        // debug($request);
        // exit;
        $response ['data'] ['request'] = json_encode ( $request );
        $this->credentials ( 'Book' );
        $response['data']['status'] = true;
        $response ['data'] ['service_url'] = $this->service_url;

        return $response;
    }
    /**
     * Elavarasi
     * 
     * @param unknown_type $block_trip_data         
     * @param unknown_type $currency_obj            
     */
    public function transferblock_data_in_preferred_currency($block_transfer_data, $currency_obj,$module) {
        $application_currency_preference = get_application_currency_preference ();
        
        $level_one = true;  
        $current_domain = true;
        if ($module == 'b2c') {
            $level_one = false;
            $current_domain = true;
        } else if ($module == 'b2b') {
            $level_one = true;
            $current_domain = true;
        }

        $tour_transfer_details = $block_transfer_data ['data']['BlockTransfer'] ['BlockTransferResult'];
        $API_raw_price = $tour_transfer_details['Price'];//FRom APi Price
        $Converted_Price = $this->module_preferred_currency_fare_object ($API_raw_price, $currency_obj,$application_currency_preference,$module );//updatin currecny price
        $cancellation_charge = array();
       // debug($tour_transfer_details);exit;
        //Updating Cancellation policy
        foreach ($tour_transfer_details['TM_Cancellation_Charge'] as $key => $value) {
            
            if($value['ChargeType']!=2){
                $cancellation_charge [$key] = $value;
                $cancellation_charge [$key] ['Currency'] = $application_currency_preference;
                $cancellation_charge [$key] ['Charge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $value ['Charge'] ) );
            }else{
                $cancellation_charge [$key] = $value;
            }
        }
        
        $block_transfer_data ['data'] ['BlockTransfer'] ['BlockTransferResult']['Price'] = $Converted_Price;
        $block_transfer_data ['data'] ['BlockTransfer'] ['BlockTransferResult']['API_raw_price'] = $this->convert_api_preferred_currency_fare_object ($API_raw_price, $currency_obj,$application_currency_preference,$module );

        $block_transfer_data ['data'] ['BlockTransfer'] ['BlockTransferResult']['API_TM_raw_price']  = $API_raw_price;


        $block_transfer_data ['data'] ['BlockTransfer'] ['BlockTransferResult']['TM_Cancellation_Charge'] = $cancellation_charge;
        return $block_transfer_data;
    }
    /**
     * Elavarasi
     * Converts Display currency to application currency
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     * @param unknown_type $module          
     */
    public function convert_token_to_application_currency($token, $currency_obj, $module) {
    
        $master_token = array ();
        
        $price_summary = array ();
        $markup_price_summary = array ();
        
        #debug($token['Price']);
        $Price = $this->convert_api_preferred_currency_fare_object($token['Price'],$currency_obj,admin_base_currency(),$module);
        
        $API_Price = $this->convert_api_preferred_currency_fare_object($token['API_Price'],$currency_obj,admin_base_currency(),$module);

        // Price Summary
        $price_summary = $this->preferred_currency_price_summary ( $token ['price_summary'], $currency_obj );
        // Markup Price Summary
        $markup_price_summary = $this->preferred_currency_price_summary ( $token ['markup_price_summary'], $currency_obj );
        // Assigning the Converted Data
        $master_token = $token;
        $master_token ['Price'] = $Price;
        $master_token ['API_Price'] = $API_Price;

        $master_token ['price_summary'] = $price_summary;
        $master_token ['markup_price_summary'] = $markup_price_summary;
        $master_token ['default_currency'] = admin_base_currency ();
        if(isset($token ['convenience_fees'])){
            $master_token ['convenience_fees'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $token ['convenience_fees'] ) ); // check this   
        }else{
            $master_token ['convenience_fees'] = 0;
        }
        
        return $master_token;
    }
    /**
     * Elavarasi
     * Converts Price summary to application curency
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     */
    private function preferred_currency_price_summary($fare_details, $currency_obj) {
        //debug($fare_details);exit;
        $price_details = array ();
        
        $price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TotalDisplayFare'] ) );
        
        $price_details ['NetFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['NetFare'] ) );

        return $price_details;
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
        // Need to return following data as this is needed to save the booking fare in the transaction details
        // Need to return following data as this is needed to save the booking fare in the transaction details
        $response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;
        $domain_origin = get_domain_auth_id ();
        $master_search_id = $book_params['token'] ['search_id'];
        $search_data = $this->search_data ( $master_search_id );
        $paxes[0]['type'] = "Adult";
        $paxes[0]['count'] = $search_data['data']['Adult'];
        $paxes[0]['type'] = "Child";
        $paxes[0]['count'] = $search_data['data']['Child'];
        //debug($search_data);exit;
        $book_total_fare = array();
        $book_domain_markup = array();
        $book_level_one_markup = array();
        $status = 'BOOKING_INPROGRESS';
        $app_reference = $app_booking_id;
        $booking_source = $book_params['booking_source'];
        $deduction_cur_obj = clone $currency_obj;
        $total_pax_count = $search_data['data']['Adult']+$search_data['data']['Child'];
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
        $booking_id = '';
        $booking_reference = '';
        $confirmation_reference = '';
        $multiplier  =1;
        $book_net_total_fare = $book_params['token'] ['price_summary'] ['NetFare']; // (TAX+ROOM PRICE)
        $currency = $book_params ['token'] ['default_currency'];
        $transfer_type = $book_params ['token'] ['transferType'];
        $vehicle_code = $book_params ['token'] ['vehicleCode'];
        $vehicle_name = $book_params ['token'] ['vehicleName'];
        $category_name = $book_params ['token'] ['categoryName'];
        $category_code = $book_params ['token'] ['categoryCode']; //need to check
        $image = $book_params ['token'] ['image'];
        $minimum_passenger_count = $book_params ['token'] ['minPaxCapacity'];
        $maximum_passenger_count = $book_params ['token'] ['maxPaxCapacity'];
        $suitcases = $book_params ['token'] ['permittedSuitcases'];
        $estimated_time = $book_params ['token'] ['estimatedTime'];
        $pickup_instruitions = $book_params ['token'] ['pickupDescription'];
        $phone_number = $book_params ['passenger_contact'];
        $phone_code = $book_params ['phone_country_code'];
        $alternate_number = 'NA';
        $departure_date = date("Y-m-d", strtotime($book_params ['token'] ['departureDate']));
        $departure_time = $book_params ['token'] ['departureTime'];
        $return_date = "";
        $return_time = "";
        if(empty($book_params ['token'] ['returnDate']) == false){
            $return_date = $book_params ['token'] ['returnDate'];
        }
        if(empty($book_params ['token'] ['returnTime']) == false){
            $return_time = $book_params ['token'] ['returnTime'];
        }
        $trip_type = $search_data['data']['Type'];
        $from_location = $search_data['data']['FromName'];
        $to_location = $search_data['data']['ToName'];;
        $total_adult_count =  $search_data['data']['Adult'];
        $total_child_count = $search_data['data']['Child'];
        $child_ages = '';
        if($search_data['data']['Child'] > 0){
            $child_ages = implode(", ", $search_data['data']['ChildAge']);
        }
        $payment_mode = $book_params ['payment_method'];
        $email =$book_params['billing_email'];
        $booking_questions = array();
        if(isset($book_params['question'])){
            if(valid_array($book_params['question'])){
                foreach($book_params['question'] as $q_key => $question){
                    $booking_questions[$q_key]['question'] = $book_params['token']['BookingQuestions'][$q_key]['title'];
                    $booking_questions[$q_key]['answers'] = $question[0];
                }
            }

        }
        $attributes = array (
                'address' => @$book_params ['billing_address_1'],
                'billing_country' => @$book_params ['billing_country'],
                // 'billing_city' => $city_name[$params['booking_params']['billing_city']],
                'billing_city' => @$book_params ['billing_city'],
                'billing_zipcode' => @$book_params ['billing_zipcode'],
                'search_id' => @$book_params ['token'] ['search_id'],
                'TotalPax' => $total_pax_count,
                'TotalAdults' => $total_adult_count,
                'TotalChilds' => $total_child_count,
                'ChildAge' => $child_ages,
                'Cancellation_available'=>@$book_params['token']['Cancellation_available'],
                'TM_Cancellation_Charge'=>@$book_params['token']['TM_Cancellation_Charge'],
                'TM_LastCancellation_date'=>@$book_params['token']['TM_LastCancellation_date'],
                'TM_Cancellation_Policy'=>@$book_params['token']['TM_Cancellation_Policy'],
                'BookingQuestions' => $booking_questions
               
            );
            $created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
            // SAVE Booking details
            $GLOBALS ['CI']->transfer_model->save_booking_details ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $transfer_type, $trip_type, $vehicle_name, $vehicle_code, $category_name,$category_code,$phone_number, $alternate_number, $email, $departure_date, $departure_time, $return_date, $return_time, $payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code );

            $Fare =  $book_params ['token']['API_Price'];
            $final_booking_price_details = $this->get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module);
            //debug($final_booking_price_details);exit;
            $book_total_fare = $commissionable_fare =$final_booking_price_details['commissionable_fare'];

            $total_fare = $trans_total_fare =$final_booking_price_details['trans_total_fare'];
            $admin_markup =  $book_domain_markup = $final_booking_price_details['admin_markup'];
            $agent_markup = $final_booking_price_details['agent_markup'];
            $admin_commission = $final_booking_price_details['admin_commission'];
            $agent_commission = $final_booking_price_details['agent_commission'];
            $admin_tds = $final_booking_price_details['admin_tds'];
            $agent_tds = $final_booking_price_details['agent_tds'];
            if($module == 'b2c'){
                $total_markup = $admin_markup;
            }
            else if($module == 'b2b'){
               $total_markup = $admin_markup+$agent_markup;
            }
            //adding gst
            $gst_value = 0;
            if($total_markup > 0 ){
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfer'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        $gst_value = ($total_markup/100) * $gst_details['data'][0]['gst'];
                        $gst_value  = roundoff_number($gst_value);
                    }
                }
            }
            $iti_attributes = array('MinimumPassengercount' => $minimum_passenger_count,
                                    'MaximumPassengerCount' => $maximum_passenger_count,
                                    'Suitcases' => $suitcases,
                                    'EstimatedTime' => $estimated_time,
                                    'PickupDescription'=>$pickup_instruitions);

            $api_raw_fare = $book_params ['token']['Price']['TotalDisplayFare'];
            $agent_buying_price = $book_params['token']['markup_price_summary']['NetFare'];
            $admin_net_fare_markup = 0;
            $GLOBALS ['CI']->transfer_model->save_booking_itinerary_details ( $app_reference, $from_location, $to_location, $image, $status,$commissionable_fare,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, json_encode($iti_attributes), @$book_total_fare,$agent_commission,$agent_tds,$admin_commission,$admin_tds,$api_raw_fare,$agent_buying_price, $gst_value);
            $i = 0;
           
                $passenger_type = $book_params['passenger_type'][$i];
                $is_lead = $book_params['lead_passenger'][$i];
                if($is_lead==''){
                    $is_lead = 0;
                }
                $title = get_enum_list('viator_title', $book_params['name_title'][$i]);
                $first_name = $book_params['first_name'][$i];
                //debug($first_name);exit;
                $middle_name = ''; //$book_params['middle_name'][$i];
                $last_name = $book_params['last_name'][$i];
                $date_of_birth = @$book_params['date_of_birth'][$i];
                //$gender = get_enum_list('gender', $book_params['gender'][$i]);
                //$passenger_nationality_id = intval($book_params['passenger_nationality'][$i]);
                $attributes = array ();
                
                // SAVE Booking Pax details
                $GLOBALS ['CI']->transfer_model->save_booking_pax_details ( $title,$app_reference, $first_name,$last_name, $phone_number, $email, $passenger_type,$status );
            
            /**
             * ************ Update Convinence Fees And Other Details Start *****************
             */
            // Convinence_fees to be stored and discount
            $convinence = 0;
            $discount = 0;
            $convinence_value = 0;
            $convinence_type = 0;
            $convinence_per_pax = 0;

            if ($module == 'b2c') {
                $total_transaction_amount = $trans_total_fare+$book_domain_markup+$gst_value;
                
                $convinence = $currency_obj->convenience_fees ($total_transaction_amount ,$paxes );

                $convinence_row = $currency_obj->get_convenience_fees ();
                $convinence_value = $convinence_row ['value'];
                $convinence_type = $convinence_row ['type'];
                $convinence_per_pax = $convinence_row ['per_pax']; 
                if($book_params['promo_actual_value']){
                    $discount = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $book_params['promo_actual_value']) );
                    $discount = floatval(preg_replace('/[^\d.]/', '', $discount));
                }
                
                //$discount = @$book_params['promo_code_discount_val'];
                $promo_code = @$book_params ['promo_code'];
            } elseif ($module == 'b2b') {
                $discount = 0;
                $promo_code='';
            }
            $GLOBALS ['CI']->load->model ( 'transaction' );
            // SAVE Booking convinence_discount_details details
            $GLOBALS ['CI']->transaction->update_convinence_discount_details ( 'transfer_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax );
            /**
             * ************ Update Convinence Fees And Other Details End *****************
             */     
            $response ['fare'] = $total_fare;
            $response ['admin_markup'] = $admin_markup;
            $response ['agent_markup'] = $agent_markup;
            $response ['convinence'] = $convinence;
            $response ['discount'] = $discount;
            $response ['transaction_currency'] = $transaction_currency;
            $response ['currency_conversion_rate'] = $currency_conversion_rate;
            //booking_status
            $response['booking_status'] = $status;
            return $response;

    }
    /**
     * Elavarasi
     * Cancel Booking
     */
    function cancel_booking($booking_details,$cancel_params)
    {
        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = FAILURE_STATUS;
        $resposne ['msg'] = 'Remote IO Error';
        $BookingId = $booking_details ['booking_id'];
        $app_reference = $booking_details ['app_reference'];
    
        $cancel_booking_request = $this->cancel_booking_request_params($app_reference,$cancel_params['cancel_code'],$cancel_params['cancel_desc'] );
        
        if ($cancel_booking_request ['status']) {
            
            $cancel_booking_response = $GLOBALS ['CI']->api_interface->get_json_response ( $cancel_booking_request ['data'] ['service_url'], $cancel_booking_request ['data'] ['request'], $header );

            $GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $cancel_booking_response ) );
            
        
            if (valid_array ( $cancel_booking_response ) == true && $cancel_booking_response ['Status'] == SUCCESS_STATUS) {
                
                $currency_obj = new Currency(array('module_type' => 'transfer', 'from' => get_api_data_currency(), 'to' => admin_base_currency()));

                // Save Cancellation Details//Converting to application currency
                $transfer_cancellation_details = $this->convert_cancelletion_refund_details($currency_obj,$cancel_booking_response ['CancelBooking']['CancellationDetails']);
                $GLOBALS ['CI']->transfer_model->update_cancellation_details ( $app_reference, $transfer_cancellation_details );
                $response ['status'] = SUCCESS_STATUS;
                
            } else {
                $response ['msg'] = $cancel_booking_response['Message'];
            }
        }
        return $response;
    }
    /**
    *Elavarasi
    *@param currency obj TMX currency to application currency
    *@param fare_details TMX refund details
    */
    private function convert_cancelletion_refund_details($currency_obj,$refund_details){

        $price_details = array ();      
        $price_details ['ChangeRequestId'] = $refund_details['ChangeRequestId'];
        $price_details ['ChangeRequestStatus'] = $refund_details['ChangeRequestStatus'];
        $price_details ['StatusDescription'] = $refund_details['StatusDescription'];
        $price_details ['RefundedAmount'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $refund_details ['RefundedAmount'] ) );       
        $price_details ['CancellationCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $refund_details ['CancellationCharge'] ) );
        return $price_details;
    }
    /**
     * Elavarasi
     * Cancellation Request
     */
    private function cancel_booking_request_params($app_reference,$cancel_code,$cancel_description) {
        $response['status'] = true;
        $response['data'] = array ();
        $request['AppReference'] = trim ( $app_reference ); 
        $response['data']['request'] = json_encode ( $request );
        $this->credentials('CancelBooking');
        $response['data']['service_url'] = $this->service_url;

        return $response;
    }
    /**
     * Returns Final Price Details For the booking
     * @param unknown_type $Fare
     * @param unknown_type $multiplier
     * @param unknown_type $specific_markup_config
     * @param unknown_type $currency_obj
     * @param unknown_type $deduction_cur_obj
     * @param unknown_type $module
     */
    private function get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module) {
        $data = array();
        //debug($Fare);

        $core_agent_commision = ($Fare['TotalDisplayFare'] - $Fare['NetFare']);          
        $commissionable_fare = $Fare['TotalDisplayFare'];
        if ($module == 'b2c') {
            
            $trans_total_fare = $this->total_price($Fare, false, $currency_obj);          

            $markup_total_fare = $currency_obj->get_currency($trans_total_fare, true, false, true, $multiplier);
            $ded_total_fare = $deduction_cur_obj->get_currency($trans_total_fare, true, true, false, $multiplier);
            $admin_markup = roundoff_number($markup_total_fare['default_value'] - $ded_total_fare['default_value']);
            $admin_commission = $Fare['AgentCommission'];
            $agent_markup = 0;
            $agent_commission = 0;
        } else {
            //B2B Calculation
             //debug($Fare);
            $trans_total_fare = $Fare['TotalDisplayFare'];             
            $this->commission = $currency_obj->get_commission();
            //echo "commission";
            //debug($this->commission);

            $AgentCommission = $this->calculate_commission($core_agent_commision);
            //debug($AgentCommission);

            $admin_commission = roundoff_number($core_agent_commision - $AgentCommission); //calculate here
            $agent_commission = roundoff_number($AgentCommission);
            
            $admin_net_rate=($trans_total_fare-$agent_commission);
            //echo "admin_net_rate".$admin_net_rate.'<br/>';

            $markup_total_fare = $currency_obj->get_currency($admin_net_rate, true, true, false, $multiplier);
            
            //debug($markup_total_fare);

            $admin_markup = abs($markup_total_fare['default_value'] - $admin_net_rate);
            $agent_tds = $currency_obj->calculate_tds($agent_commission);
            //adding tds with net rate by ela
            $agent_net_rate=(($trans_total_fare + $admin_markup)-$agent_commission+$agent_tds);
            $ded_total_fare = $deduction_cur_obj->get_currency($agent_net_rate, true, false, true, $multiplier);
            $agent_markup = roundoff_number($ded_total_fare['default_value'] - $agent_net_rate);
          
           
        }
        //TDS Calculation
        $admin_tds = $currency_obj->calculate_tds($admin_commission);
        $agent_tds = $currency_obj->calculate_tds($agent_commission);

        $data['commissionable_fare'] = $commissionable_fare;
        $data['trans_total_fare'] = $trans_total_fare;
        $data['admin_markup'] = $admin_markup;
        $data['agent_markup'] = $agent_markup;
        $data['admin_commission'] = $admin_commission;
        $data['agent_commission'] = $agent_commission;
        $data['admin_tds'] = $admin_tds;
        $data['agent_tds'] = $agent_tds;
        //debug($data);
        //exit;
        return $data;
    }

    /**
     * Elavarasi
     * Update and return price details
     */
    public function update_block_details($transfer_details, $booking_parameters,$cancel_currency_obj,$module='b2c') {
        //debug($transfer_details);exit;
        $booking_parameters ['transferType'] = $transfer_details['BlockTransferResult'] ['transferType'];
        $booking_parameters ['vehicleName'] = $transfer_details['BlockTransferResult']['vehicleName'];
        $booking_parameters ['vehicleCode'] = $transfer_details['BlockTransferResult']['vehicleCode'];
        $booking_parameters ['categoryName'] = $transfer_details['BlockTransferResult']['categoryName'];
        $booking_parameters ['categoryCode'] = $transfer_details['BlockTransferResult']['categoryCode'];

        $booking_parameters ['image'] = $transfer_details['BlockTransferResult']['image'];
        $booking_parameters ['pickupFrom'] = $transfer_details['BlockTransferResult']['pickupFrom'];
        $booking_parameters ['BookingQuestions'] = $transfer_details['BlockTransferResult']['BookingQuestions'];
        

        $booking_parameters ['pickupTo'] = $transfer_details['BlockTransferResult']['pickupTo'];
        $booking_parameters ['departureDate'] = $transfer_details['BlockTransferResult']['departureDate'];
        $booking_parameters ['departureTime'] = $transfer_details['BlockTransferResult']['departureTime'];
        $booking_parameters ['returnDate'] = $transfer_details['BlockTransferResult']['returnDate'];
        $booking_parameters ['returnTime'] = $transfer_details['BlockTransferResult']['returnTime'];
        $booking_parameters['pickupDescription']  = $transfer_details['BlockTransferResult']['pickupDescription'];

        $booking_parameters['pickupDescription'] = $transfer_details['BlockTransferResult']['pickupDescription'];
        $booking_parameters['minPaxCapacity'] = $transfer_details['BlockTransferResult']['minPaxCapacity'];

        $booking_parameters['maxPaxCapacity'] = $transfer_details['BlockTransferResult']['maxPaxCapacity'];
        $booking_parameters['estimatedTime'] = $transfer_details['BlockTransferResult']['estimatedTime'];
        $booking_parameters['permittedSuitcases'] = $transfer_details['BlockTransferResult']['permittedSuitcases'];

        $booking_parameters['Cancellation_available'] = $transfer_details['BlockTransferResult']['cancellationAvailable'];
        $booking_parameters['TM_Cancellation_Charge'] = $transfer_details['BlockTransferResult']['TM_Cancellation_Charge'];
        $booking_parameters['TM_LastCancellation_date'] = $transfer_details['BlockTransferResult']['TM_LastCancellation_date'];
        $booking_parameters['BlockTransferId'] = $transfer_details['BlockTransferResult']['BlockTransferId'];
        
        $policy_string = '';

        $Transfer_price = $transfer_details['BlockTransferResult']['Price']['TotalDisplayFare'];

        $level_one = true;  
        $current_domain = true;
        if ($module == 'b2c') {
            $level_one = false;
            $current_domain = true;
        } else if ($module == 'b2b') {
            $level_one = true;
            $current_domain = true;
        }

        if($module=='b2b'){
            $b2b_price_summary = $this->update_commission_markup_module_wise($transfer_details['BlockTransferResult']['Price'],$cancel_currency_obj);
            $markup_cancellation_price = roundoff_number($b2b_price_summary['TotalDisplayFare']);
            
        }else{
            $markup_cancellation_price = $this->update_cancellation_markup_currency($Transfer_price,$cancel_currency_obj,$level_one,$current_domain);
        }
        
        // calculate markup for cancellation policy
        $booking_parameters['Price'] = $transfer_details['BlockTransferResult']['Price'];   
        $booking_parameters['API_Price']  = $transfer_details['BlockTransferResult']['API_raw_price'];
        $booking_parameters['API_TM_Price']  = $transfer_details['BlockTransferResult']['API_TM_raw_price'];        
        
        if($booking_parameters['TM_Cancellation_Charge']){
            foreach ($booking_parameters['TM_Cancellation_Charge']  as $key => $value) {

                if($value['Charge']==0){
                         $policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate'])).'<br/>';
                        
                }else{
                        if($value['ChargeType']!=2){
                                $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
                        }else{
                            $amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$markup_cancellation_price;
                        }
                        $current_date = date('Y-m-d');
                        $cancell_date = date('Y-m-d',strtotime($value['FromDate']));
                        if($cancell_date >$current_date){
                            $value['FromDate'] = $value['FromDate'];

                            $policy_string .=' Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
                        }else{
                            $value['FromDate'] = date('Y-m-d');
                            $policy_string  .='This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
                        }
                }

            }
        }
        $booking_parameters['TM_Cancellation_Policy'] = $policy_string;
        $booking_parameters['price_summary'] = viator_summary_trip_combination ( $transfer_details ['BlockTransferResult']['Price'] );
        return $booking_parameters; 
    }

    function update_commission_markup_module_wise($Price,$currency_obj){

        $api_price_details = $Price;
        
        //debug($Price);
        $this->get_commission($Price,$currency_obj);
        // echo "commm";
        // debug($Price);
        $admin_price_details = $this->update_booking_markup_currency($Price,$currency_obj,1,true,false);
        // echo "*******admin*******";
        // debug($admin_price_details);

        $agent_price_details = $this->update_booking_markup_currency($Price,$currency_obj,1,true,true);
        // echo "******agent******";
        // debug($agent_price_details);
        
        $Markup_Price = $this->b2b_price_details($api_price_details,$admin_price_details,$agent_price_details,$currency_obj);
        // echo "*******Final*****";
        // debug($Markup_Price);
        // exit;
        return $Markup_Price;
    }
    /**
     * Markup for Booking Page List
     * 
     * @param array $price_summary          
     * @param object $currency_obj          
     * @param number $search_id         
     */
    function update_booking_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true, $module='') {
        
        return $this->update_search_markup_currency ( $price_summary, $currency_obj, $search_id, $level_one_markup, $current_domain_markup, $module );
    }
    /**
     * update markup currency and return summary
     * $attr needed to calculate number of nights markup when its plus based markup
     */
    function update_markup_currency(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true, $module='') {
        // debug($price_summary);exit;
        $tax_service_sum = 0;
        /* $tax_service_sum = $this->tax_service_sum($price_summary); */
        // Remove Tax and Service Tax While Adding markup   
        
        $tax_removal_list = array ();
        $markup_list = array (
                'NetFare'   
                
        );
        // debug($price_summary);
        $to_convert_currency = $currency_obj->to_currency;

        //debug($price_summary);exit;

        $markup_summary = array ();
        foreach ( $price_summary as $__k => $__v ) {
            
            $ref_cur = $currency_obj->force_currency_conversion ( $__v ); // Passing Value By Reference so dont remove it!!!
            
            
            $price_summary [$__k] = $ref_cur ['default_value']; // If you dont understand then go and study "Passing value by reference"
            
            if (in_array ( $__k, $markup_list )) {
                
                // if($__k=='NetFare'){
                //  $current_domain_markup = false;
                // }
                
                $temp_price = $currency_obj->get_currency ( $__v, true, $level_one_markup, $current_domain_markup, $no_of_nights );
            } else {
                $temp_price = $currency_obj->force_currency_conversion ( $__v );
            }
            // adding service tax and tax to total
            if (in_array ( $__k, $tax_removal_list )) {
                if($to_convert_currency =='INR'){
                    $markup_summary [$__k] = round($temp_price ['default_value'] + $tax_service_sum);   
                }else{
                    $markup_summary [$__k] = ($temp_price ['default_value'] + $tax_service_sum);
                }
            } else {
                if($to_convert_currency=='INR'){
                    $markup_summary [$__k] = round($temp_price ['default_value']);  
                }else{
                    $markup_summary [$__k] = ($temp_price ['default_value']);
                }
            }
        }       
        

         //Markup
        //PublishedFare       
        $Markup = 0;
        $price_summary['_Markup'] = 0;
        if (isset($markup_summary['NetFare'])) {
            $Markup = $markup_summary['NetFare'] - $price_summary['NetFare'];
            if($to_convert_currency=='INR'){
                $markup_summary['TotalDisplayFare'] = round($markup_summary['TotalDisplayFare'] + $Markup);
            }else{
                $markup_summary['TotalDisplayFare'] = $markup_summary['TotalDisplayFare'] + $Markup;
            }
            
        }
        $gst_value = 0;
        if($module == 'b2c'){
            //adding gst
            if($Markup > 0 ){
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfer'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                    }
                }
            }
        }

        $markup_summary['_GST'] = $gst_value;
        $markup_summary['TotalDisplayFare'] = round($markup_summary['TotalDisplayFare']+$gst_value);
        $markup_summary['NetFare'] = round($markup_summary['NetFare']+$gst_value);
        $markup_summary['_Markup'] = $Markup;
        #echo "======";
         //debug($markup_summary);exit;
        return $markup_summary;
    }
        /**
    *Update Markup currency for Cancellation Charge
    */
    function update_cancellation_markup_currency(&$cancel_charge,&$currency_obj,$level_one_markup=false,$current_domain_markup=true){
        $multiplier = 1;
        $to_convert_currency = $currency_obj->to_currency;
        $temp_price = $currency_obj->get_currency ( $cancel_charge, true, $level_one_markup, $current_domain_markup, $multiplier );
        $total_markup = $temp_price['default_value']-$cancel_charge;
        $gst_value = 0;
        if($total_markup > 0 ){
            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfer'));
            if($gst_details['status'] == true){
                if($gst_details['data'][0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details['data'][0]['gst'];
                    $gst_value  = roundoff_number($gst_value);
                }
            }
        }
        if($to_convert_currency=='INR'){
            return round($temp_price['default_value']+$gst_value); 
        }else{
            return $temp_price['default_value']+$gst_value;
        }
    }
    /**
     * Elavarasi
     * 
     * @param unknown_type $fare_details            
     * @param unknown_type $currency_obj            
     */
    private function convert_api_preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {

        //debug($fare_details);
        $admin_commission = 0;
        $admin_tdson_commission = 0;
        $agent_commission = 0;
        $agent_tdson_commission = 0;
        $org_commission = 0;
        $or_tdson_commission = 0;
        $admin_profit = 0;
        $show_net_fare = 0;     
        if(isset($fare_details['PriceBreakup'])){
            $admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
            $admin_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
        }else{
            $admin_commission = $fare_details['AgentCommission'];
            $admin_tds =$fare_details['AgentTdsOnCommision'];
        }
        if($module=='b2c'){
            $net_fare = $fare_details['TotalDisplayFare'];
            $agent_commission = $admin_commission;
            $agent_tdson_commission =$admin_tds;        
            
        }else{
            //for b2b users
            //Updating Commission
            $agent_commission =$admin_commission;
            $agent_tdson_commission = $admin_tds;

            $net_fare =$fare_details['TotalDisplayFare'];       
        }
        //echo $net_fare;
        $price_details = array ();
        $price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();
        $price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $net_fare) );

        $price_details['AgentCommission'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_commission) );

        $price_details['AgentTdsOnCommision'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_tdson_commission) );        
        if($module=='b2c'){
            $show_net_fare = $price_details ['TotalDisplayFare']-$price_details['AgentCommission']+$price_details['AgentTdsOnCommision'];
            //$price_details ['TotalDisplayFare'] = $show_net_fare;
        }else{
            $show_net_fare = ($price_details ['TotalDisplayFare']-$price_details['AgentCommission']);   
        }       
        $price_details ['NetFare'] =$show_net_fare;
        return $price_details;
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
     * Elavarasi
     *
     * get trip block request
     * 
     * @param array $booking_parameters         
     */
    private function get_block_transfer_request($booking_params) {
        $response ['status'] = true;
        $response ['data'] = array ();
        $request = array();
        $request['ResultToken'] = $booking_params['result_token'];
        $response ['data'] ['request'] = json_encode ( $request );
        $this->credentials ( 'BlockTransfer' );
        $response ['data'] ['service_url'] = $this->service_url;
        return $response;
    }
    /**
     * Tax price is the price for which markup should not be added
     */
    function tax_service_sum($markup_price_summary, $api_price_summary) {
        // sum of tax and service ;
        return ($markup_price_summary ['TotalDisplayFare'] - $api_price_summary ['NetFare']);
    }


    /**
     * calculate and return total price details
     */
    function total_price($price_summary) {
        return ($price_summary ['TotalDisplayFare']);
    }

    function booking_url($search_id) {
        return base_url() . 'index.php/hotel/booking/' . intval($search_id);
    }
    private function valid_search_result($search_result) {
		if (valid_array ( $search_result ) == true and isset ( $search_result ['Status'] ) == true and $search_result ['Status']  == SUCCESS_STATUS) {
			return true;
		} else {
			return false;
		}
	}

}

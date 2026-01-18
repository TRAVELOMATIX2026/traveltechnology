<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * @author: Shaik Jani
 * @Email: shaik.jani@provabmail.com
 * @Date: 2025-03-25
 * @package: 
 * @Description: 
 * @version: 2.0
 **/
class Flight extends MY_Controller
{
    private $current_module;
    public function __construct()
    {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        $this->load->model('flight_model');
        $this->load->model('user_model'); // we need to load user model to access provab sms library
        $this->load->library('provab_sms'); // we need this provab_sms library to send sms.
        $this->load->library('social_network/facebook'); //Facebook Library to enable share button
        $this->current_module = $this->config->item('current_module');
        $this->load->library('provab_mailer');
    }
    /**
     * App Validation and reset of data
     */
    function pre_calendar_fare_search()
    {
        $params = $this->input->get();
        $safe_search_data = $this->flight_model->calendar_safe_search_data($params);
        //Need to check if its domestic travel
        $from_loc = $safe_search_data['from_loc'];
        $to_loc = $safe_search_data['to_loc'];
        $safe_search_data['is_domestic_one_way_flight'] = false;
        $safe_search_data['is_domestic_one_way_flight'] = $this->flight_model->is_domestic_flight($from_loc, $to_loc);
        if ($safe_search_data['is_domestic_one_way_flight'] == false) {
            $page_params['from'] = '';
            $page_params['to'] = '';
        } else {
            $page_params['from'] = $safe_search_data['from'];
            $page_params['to'] = $safe_search_data['to'];
        }
        $page_params['depature'] = $safe_search_data['depature'];
        $page_params['carrier'] = $safe_search_data['carrier'];
        $page_params['adult'] = $safe_search_data['adult'];
        redirect(base_url() . 'flight/calendar_fare?' . http_build_query($page_params));
    }
    /**
     * Airfare calendar
     */
    function calendar_fare()
    {
        $params = $this->input->get();
        $active_booking_source = $this->flight_model->active_booking_source();
        if (valid_array($active_booking_source) == true) {
            $safe_search_data = $this->flight_model->calendar_safe_search_data($params);
            $page_params = array(
                'flight_search_params' => $safe_search_data,
                'active_booking_source' => $active_booking_source
            );
            $page_params['from_currency'] = get_application_default_currency();
            $page_params['to_currency'] = get_application_currency_preference();
            $this->template->view('flight/calendar_fare_result', $page_params);
        }
    }

    function add_days_todate()
    {
        $get_data = $this->input->get();
        if (isset($get_data['search_id']) == true && intval($get_data['search_id']) > 0 && isset($get_data['new_date']) == true && empty($get_data['new_date']) == false) {
            $search_id = intval($get_data['search_id']);
            $new_date = trim($get_data['new_date']);
            $safe_search_data = $this->flight_model->get_safe_search_data($search_id);
            $day_diff = get_date_difference($safe_search_data['data']['depature'], $new_date);
            if (valid_array($safe_search_data) == true && $safe_search_data['status'] == true) {
                $safe_search_data = $safe_search_data['data'];
                $search_params = array();
                $search_params['trip_type'] = trim($safe_search_data['trip_type']);
                $search_params['from'] = trim($safe_search_data['from']);
                $search_params['to'] = trim($safe_search_data['to']);
                $search_params['depature'] = date('d-m-Y', strtotime($new_date)); //Adding new Date
                if (isset($safe_search_data['return'])) {
                    $search_params['return'] = add_days_to_date($day_diff, $safe_search_data['return']); //Check it
                }
                $search_params['adult'] = intval($safe_search_data['adult_config']);
                $search_params['child'] = intval($safe_search_data['child_config']);
                $search_params['infant'] = intval($safe_search_data['infant_config']);
                $search_params['search_flight'] = 'search';
                $search_params['v_class'] = trim($safe_search_data['v_class']);
                $search_params['carrier'] = $safe_search_data['carrier'];
                redirect(base_url() . 'general/pre_flight_search/?' . http_build_query($search_params));
            } else {
                $this->template->view('general/popup_redirect');
            }
        } else {
            $this->template->view('general/popup_redirect');
        }
    }
    /**
     * 
     * Search Request from Fare Calendar
     */
    function pre_fare_search_result()
    {
        $get_data = $this->input->get();
        if (
            isset($get_data['from']) == true && empty($get_data['from']) == false &&
            isset($get_data['to']) == true && empty($get_data['to']) == false &&
            isset($get_data['depature']) == true && empty($get_data['depature']) == false
        ) {
            $from = trim($get_data['from']);
            $to = trim($get_data['to']);
            $depature = trim($get_data['depature']);
            $from_loc_details = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $from));
            $to_loc_details = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $to));
            if ($from_loc_details['status'] == true && $to_loc_details['status'] == true) {
                $depature = date('Y-m-d', strtotime($depature));
                $airport_code = trim($from_loc_details['data'][0]['airport_code']);
                $airport_city = trim($from_loc_details['data'][0]['airport_city']);
                $from = $airport_city . ' (' . $airport_code . ')';
                //To
                $airport_code = trim($to_loc_details['data'][0]['airport_code']);
                $airport_city = trim($to_loc_details['data'][0]['airport_city']);
                $to = $airport_city . ' (' . $airport_code . ')';
                //Forming Search Request
                $search_params = array();
                $search_params['trip_type'] = 'oneway';
                $search_params['from'] = $from;
                $search_params['to'] = $to;
                $search_params['depature'] = $depature;
                $search_params['adult'] = 1;
                $search_params['child'] = 0;
                $search_params['infant'] = 0;
                $search_params['search_flight'] = 'search';
                $search_params['v_class'] = 'Economy';
                $search_params['carrier'] = array('');
                redirect(base_url() . 'general/pre_flight_search/?' . http_build_query($search_params));
            } else {
                $this->template->view('general/popup_redirect');
            }
        } else {
            $this->template->view('general/popup_redirect');
        }
    }
    /**
     * Search Result
     * @param number $search_id
     */
    function search($search_id)
    {
        $safe_search_data = $this->flight_model->get_safe_search_data($search_id);
        //debug($$search_id); exit;
        // Get all the FLIGHT bookings source which are active
        $active_booking_source = $this->flight_model->active_booking_source();
        if (valid_array($active_booking_source) == true and $safe_search_data['status'] == true) {
            $safe_search_data['data']['search_id'] = abs($search_id);
            $page_params = array(
                'flight_search_params' => $safe_search_data['data'],
                'active_booking_source' => $active_booking_source
            );
            $page_params['from_currency'] = get_application_default_currency();
            $page_params['to_currency'] = get_application_currency_preference();
            //Need to check if its domestic travel
            $from_loc = $safe_search_data['data']['from_loc'];
            $to_loc = $safe_search_data['data']['to_loc'];
            $page_params['is_domestic_one_way_flight'] = false;
            if ($safe_search_data['data']['trip_type'] == 'oneway') {
                $page_params['is_domestic_one_way_flight'] = $this->flight_model->is_domestic_flight($from_loc, $to_loc);
            }
            $page_params['airline_list'] = $this->db_cache_api->get_airline_code_list(); //
            $b2c_label = $this->custom_db->single_table_records('label', '*', array('status' => 1, 'module' => 'B2C'));
            if ($b2c_label['status'] == true) {
                $page_params['b2c_label'] = $b2c_label['data'];
            } else {
                $page_params['b2c_label'] = [];
            }
            $page_params['left_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Left' and status=1 and (display_page='Search' OR display_page='Both') and (module='B2C' OR module='Both') and api_module = 'flights' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
            $page_params['right_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Right' and status=1 and (display_page='Search' OR display_page='Both') and (module='B2C' OR module='Both') and api_module = 'flights' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
            $page_params['middle_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Middle' and status=1 and (display_page='Search' OR display_page='Both') and (module='B2C' OR module='Both') and api_module = 'flights' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
            // debug($page_params);die;
            $this->template->view('flight/search_result_page', $page_params);
        } else {
            if ($safe_search_data['status'] == true) {
                $this->template->view('general/popup_redirect');
            } else {
                $this->template->view('flight/exception');
            }
        }
    }
    /**
     * 
     * Passenger Details page for final bookings
     * Here we need to run farequote/booking based on api
     * View Page for booking
     */
    function booking($search_id)
    {

        $pre_booking_params = $this->input->post();

        $search_hash = $pre_booking_params['search_hash'];
        //debug($pre_booking_params);
        load_flight_lib($pre_booking_params['booking_source']);

        $safe_search_data = $this->flight_lib->search_data($search_id);

        $safe_search_data['data']['search_id'] = intval($search_id);
        $token = $this->flight_lib->unserialized_token($pre_booking_params['token'], $pre_booking_params['token_key']);


        if ($token['status'] == SUCCESS_STATUS) {
            $pre_booking_params['token'] = $token['data']['token'];
        }
        if (isset($pre_booking_params['booking_source']) == true && $safe_search_data['status'] == true) {
            // - Check Travel is Domestic or International
            $from_loc = $safe_search_data['data']['from_loc'];
            $to_loc = $safe_search_data['data']['to_loc'];
            $safe_search_data['data']['is_domestic_flight'] = $this->flight_model->is_domestic_flight($from_loc, $to_loc);
            $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
            $page_data['search_data'] = $safe_search_data['data'];

            //Need to fill pax details by default if user has already logged in
            $this->load->model('user_model');

            $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
            $page_data['active_data'] = $Domain_record['data'][0];
            // echo $this->entity_country_code;
            if (!empty($this->entity_country_code)) {
                $page_data['user_country_code'] = $this->entity_country_code;
            } else {
                $page_data['user_country_code'] = INDIA_COUNTRY_CODE;
            }



            $page_data['promo_codes'] = $this->custom_db->get_custom_query("select * from  promo_code_list where status=1 AND module in ('flight') AND expiry_date >= CURDATE()");
            //Not to show cache data in browser
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            // debug($pre_booking_params);exit;
            switch ($pre_booking_params['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    // upate fare details
                    $quote_update = $this->fare_quote_booking($pre_booking_params);
                    //debug($quote_update);exit;

                    if ($quote_update['status'] == FAILURE_STATUS) {
                        redirect(base_url() . 'flight/exception?op=Remote IO error @ Session Expiry&notification=session');
                    } else {
                        $pre_booking_params = $quote_update['data'];
                        //Get Extra Services
                        $extra_services = $this->get_extra_services($pre_booking_params);
                        if ($extra_services['status'] == SUCCESS_STATUS) {
                            $page_data['extra_services'] = $extra_services['data'];
                        } else {
                            $page_data['extra_services'] = array();
                        }
                    }
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => get_application_currency_preference()
                    ));
                    // Load View
                    // debug($pre_booking_params);exit;
                    $page_data['booking_source'] = $pre_booking_params['booking_source'];
                    $page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
                    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_code();
                    $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
                    $page_data['currency_obj'] = $currency_obj;
                    //$page_data['IsPassportMandatory'] = $pre_booking_params['token'][0]['IsPassportMandatory'];
                    //Traveller Details
                    $page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
                    //Extracting Segment Summary and Fare Details
                    //debug( $page_data ['iso_country_list']);exit;
                    $updated_flight_details = $pre_booking_params['token'];
                    $is_price_Changed = false;
                    $flight_details = array();
                    foreach ($updated_flight_details as $k => $v) {
                        //TODO: Implement this using old and new price
                        /* if($is_price_Changed == false && $v['IsPriceChanged'] == true) {
                          $is_price_Changed = true;
                          } */
                        $temp_flight_details = $this->flight_lib->extract_flight_segment_fare_details($v, $currency_obj, $search_id, $this->current_module);
                        unset($temp_flight_details[0]['BookingType']); //Not needed in Next page
                        $flight_details[$k] = $temp_flight_details[0];
                    }
                    // debug($flight_details);exit;
                    //Merge the Segment Details and Fare Details For Printing Purpose
                    $flight_pre_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                    // debug($flight_pre_booking_summary);exit;
                    $pre_booking_params['token'] = $flight_details;
                    $page_data['pre_booking_params'] = $pre_booking_params;
                    $page_data['pre_booking_summery'] = $flight_pre_booking_summary;
                    $TotalPrice = $flight_pre_booking_summary['FareDetails'][$this->current_module . '_PriceDetails']['TotalFare'];
                    $page_data['convenience_fees'] = $currency_obj->convenience_fees($TotalPrice, $search_id);
                    $page_data['is_price_Changed'] = $is_price_Changed;
                    //Get the country phone code 
                    $temp_record = $this->custom_db->get_phone_code_list();
                    $page_data['phone_code'] = $temp_record;


                    $convinence_fees_row = $this->private_management_model->get_convinence_fees('flight', $search_id);
                    // debug($convinence_fees_row);exit;
                    $page_data['org_convience_fee'] = 0;
                    if (valid_array($convinence_fees_row)) {
                        if ($convinence_fees_row['type'] == 'percentage') {
                            $page_data['org_convience_fee'] = $convinence_fees_row['value'];
                        }
                    }
                    $page_data['convenience_fees_orginal'] = $convinence_fees_row;
                    $state_list = $this->custom_db->single_table_records('state_list', '*');
                    $state_list_array = array();
                    foreach ($state_list['data'] as $key => $state) {
                        $state_list_array[$state['en_name']] = $state['en_name'];
                    }
                    $page_data['state_list'] = $state_list_array;
                    //session expiry time calculation
                    $page_data['session_expiry_details'] = $this->flight_lib->set_flight_search_session_expiry(true, $search_hash);
                    //Pusing ExtraService details to pre_booking_params array()
                    $page_data['pre_booking_params']['extra_services'] = $extra_services;
                    $temp = $this->custom_db->single_table_records('insurance');
                    $page_data['insurance'] = $temp['data'][0];
                    // $json = json_encode ( $page_data, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT );
                    // file_put_contents('logs/booking_page_tmx.txt',  $json);
                    $b2c_label = $this->custom_db->single_table_records('label', '*', array('status' => 1, 'module' => 'B2C'));
                    $page_data['b2c_label'] = $b2c_label['data'];
                    //debug($page_data['pre_booking_params']);exit;
                    $this->template->view('flight/tbo/tbo_booking_page', $page_data);
                    break;

                case TBO_FLIGHT_BOOKING_SOURCE:
                    // upate fare details
                    $quote_update = $this->fare_quote_booking($pre_booking_params);

                    // $extra_services = $this->get_extra_services($pre_booking_params);
                    // exit;
                    if ($quote_update['status'] == FAILURE_STATUS) {
                        redirect(base_url() . 'flight/exception?op=Remote IO error @ Session Expiry&notification=session');
                    } else {
                        $pre_booking_params = $quote_update['data'];
                        //Get Extra Services
                        $extra_services = $this->get_extra_services($pre_booking_params);
                        if ($extra_services['status'] == SUCCESS_STATUS) {
                            $page_data['extra_services'] = $extra_services['data'];
                        } else {
                            $page_data['extra_services'] = array();
                        }
                    }
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => get_application_currency_preference()
                    ));
                    // Load View
                    // debug($pre_booking_params);exit;
                    $page_data['booking_source'] = $pre_booking_params['booking_source'];
                    $page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
                    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_code();
                    $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
                    $page_data['currency_obj'] = $currency_obj;


                    // airline list for frequent flyer
                    $page_data['airline_list'] = $this->db_cache_api->get_airline_code_list();

                    //$page_data['IsPassportMandatory'] = $pre_booking_params['token'][0]['IsPassportMandatory'];
                    //Traveller Details
                    $page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
                    //Extracting Segment Summary and Fare Details
                    //debug( $page_data ['iso_country_list']);exit;
                    $updated_flight_details = $pre_booking_params['token'];

                    //debug($updated_flight_details[0]['fareQuoteOriginal']);exit;

                    $is_price_Changed = false;
                    $flight_details = array();
                    foreach ($updated_flight_details as $k => $v) {
                        //TODO: Implement this using old and new price
                        /* if($is_price_Changed == false && $v['IsPriceChanged'] == true) {
                          $is_price_Changed = true;
                          } */
                        $temp_flight_details = $this->flight_lib->extract_flight_segment_fare_details($v, $currency_obj, $search_id, $this->current_module);
                        unset($temp_flight_details[0]['BookingType']); //Not needed in Next page
                        $flight_details[$k] = $temp_flight_details[0];
                    }
                    // debug($flight_details);exit;
                    //Merge the Segment Details and Fare Details For Printing Purpose
                    $flight_pre_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                    // debug($flight_pre_booking_summary);exit;
                    $pre_booking_params['token'] = $flight_details;
                    $page_data['pre_booking_params'] = $pre_booking_params;
                    $page_data['fare_quote_params'] = serialized_data($updated_flight_details[0]['fareQuoteOriginal']);
                    $page_data['pre_booking_summery'] = $flight_pre_booking_summary;
                    $TotalPrice = $flight_pre_booking_summary['FareDetails'][$this->current_module . '_PriceDetails']['TotalFare'];
                    $page_data['convenience_fees'] = $currency_obj->convenience_fees($TotalPrice, $search_id);
                    $page_data['is_price_Changed'] = $is_price_Changed;
                    //Get the country phone code 
                    $temp_record = $this->custom_db->get_phone_code_list();
                    $page_data['phone_code'] = $temp_record;


                    $convinence_fees_row = $this->private_management_model->get_convinence_fees('flight', $search_id);
                    // debug($convinence_fees_row);exit;
                    $page_data['org_convience_fee'] = 0;
                    if (valid_array($convinence_fees_row)) {
                        if ($convinence_fees_row['type'] == 'percentage') {
                            $page_data['org_convience_fee'] = $convinence_fees_row['value'];
                        }
                    }
                    $page_data['convenience_fees_orginal'] = $convinence_fees_row;
                    $state_list = $this->custom_db->single_table_records('state_list', '*');
                    $state_list_array = array();
                    foreach ($state_list['data'] as $key => $state) {
                        $state_list_array[$state['en_name']] = $state['en_name'];
                    }
                    $page_data['state_list'] = $state_list_array;
                    //session expiry time calculation
                    $page_data['session_expiry_details'] = $this->flight_lib->set_flight_search_session_expiry(true, $search_hash);
                    //Pusing ExtraService details to pre_booking_params array()
                    $page_data['pre_booking_params']['extra_services'] = $extra_services;
                    $temp = $this->custom_db->single_table_records('insurance');
                    $page_data['insurance'] = $temp['data'][0];



                    $this->template->view('flight/tbo/tbo_booking_page', $page_data);
                    break;
            }
        } else {
            redirect(base_url());
        }
    }
    /**
     * Fare Quote Booking
     */
    private function fare_quote_booking($flight_booking_details, $api = '', $safe_search_data = '')
    {
        //debug($flight_booking_details);exit;
        $fare_quote_details = $this->flight_lib->fare_quote_details($flight_booking_details, $safe_search_data);
        // debug($fare_quote_details); 
        // exit;


        if ($fare_quote_details['status'] == SUCCESS_STATUS && valid_array($fare_quote_details) == true) {
            //Converting API currency data to preferred currency
            $currency_obj = new Currency(array('module_type' => 'flight', 'from' => $api == 'ndc' ? $this->flight_lib->ApiCurrency : get_api_data_currency(), 'to' => get_application_currency_preference()));
            $fare_quote_details = $this->flight_lib->farequote_data_in_preferred_currency($fare_quote_details, $currency_obj);
            // debug($fare_quote_details);//exit;
        }
        return $fare_quote_details;
    }
    /**
     * Get Extra Services
     */
    private function get_extra_services($flight_booking_details)
    {
        $extra_service_details = $this->flight_lib->get_extra_services($flight_booking_details);

        // debug($extra_service_details);
        // exit;

        return $extra_service_details;
    }
    /**
     * 
     * Secure Booking of FLIGHT
     * Process booking no view page
     */
    function pre_booking($search_id)
    {


        $post_params = $this->input->post();
        if (valid_array($post_params) == false) {
            redirect(base_url());
        }
        //Setting Static Data - 
        $post_params['billing_city'] = 'Bangalore';
        $post_params['billing_zipcode'] = '560100';
        $post_params['billing_address_1'] = 'Fortune Serene, West Avenue 9, Electronic City';
        // Make sure token and temp token matches
        $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
        // debug($valid_temp_token); exit;
        if ($valid_temp_token != false) {
            load_flight_lib($post_params['booking_source']);
            $amount = 0;
            $currency = '';
            /*             * **Convert Display currency to Application default currency** */
            //After converting to default currency, storing in temp_booking
            $post_params['token'] = unserialized_data($post_params['token']);
            $currency_obj = new Currency(array('module_type' => 'flight', 'from' => get_application_currency_preference(), 'to' => get_application_default_currency()));
            //debug(json_encode($post_params['token']['token']));
            //$post_params['token']['token'] = $this->flight_lib->convert_token_to_application_currency($post_params['token']['token'], $currency_obj, $this->current_module);
            // debug(json_encode($post_params['token']['token']));exit;
            //Convert to Extra Services to application currency
            if (isset($post_params['token']['extra_services']) == true) {
                $post_params['token']['extra_services'] = $this->flight_lib->convert_extra_services_to_application_currency($post_params['token']['extra_services'], $currency_obj);
            }
            //debug($post_params);exit;
            $post_params['token'] = serialized_data($post_params['token']);
            //debug($post_params);exit;
            //Reindex Passport Month
            $post_params['passenger_passport_expiry_month'] = $this->flight_lib->reindex_passport_expiry_month($post_params['passenger_passport_expiry_month'], $search_id);
            // debug($post_params);exit;
            $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, FLIGHT_BOOKING);
            //debug($currency_obj);
            $book_id = $temp_booking['book_id'];
            $book_origin = $temp_booking['temp_booking_origin'];
            if (in_array($post_params['booking_source'], array(PROVAB_FLIGHT_BOOKING_SOURCE, TBO_FLIGHT_BOOKING_SOURCE))) {
                // debug($currency_obj);
                $temp_token = unserialized_data($post_params['token']);
                $flight_details = $temp_token['token'];
                $flight_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                $fare_details = $flight_booking_summary['FareDetails'][$this->current_module . '_PriceDetails'];
                // debug($fare_details);exit;
                $amount = $fare_details['TotalFare'];
                //debug($amount);exit;
                $currency = $fare_details['CurrencySymbol'];
            }
            /*             * ******* Promocode Start ******* */
            if (isset($post_params['promo_code_discount_val'])) {
                $key = '';
                $key = provab_encrypt(@$post_params['key']);
                $data = $this->custom_db->single_table_records('promo_code_doscount_applied', '*', array('search_key' => $key));
                if ($data['status'] == true) {
                    //$promocode_discount = $data['data'][0]['discount_value'];
                    $promocode_discount = $post_params['promo_code_discount_val'];
                }
            }
            //// debug($post_params);
            //debug($promocode_discount);
            /*             * ******* Promocode End ******* */
            $currency_obj_promo = new Currency(array(
                'module_type' => 'flight',
                'from' => get_application_currency_preference(),
                'to' => get_application_default_currency()
            ));
            $conversion_rate = $currency_obj_promo->conversion_cache[get_application_currency_preference() . get_application_default_currency()];
            $promocode_discount = str_replace(',', '', $promocode_discount) * $conversion_rate;
            $promocode_discount = round($promocode_discount, 2);
            $email = $post_params['billing_email'];
            $phone = $post_params['passenger_contact'];
            $firstname = $post_params['first_name']['0'] . " " . $post_params['last_name']['0'];
            $book_id = $book_id;
            $productinfo = META_AIRLINE_COURSE;
            $booking_data = $this->module_model->unserialize_temp_booking_record($book_id, $book_origin);
            // debug($booking_data);exit;
            // debug($currency_obj);exit;
            $currency_obj = new Currency(array(
                'module_type' => 'flight',
                'from' => admin_base_currency(),
                'to' => admin_base_currency()
            ));


            if (isset($post_params['frequent_flyer_airline']) && isset($post_params['frequent_flyer_no'])) {
                $booking_data['book_attributes']['frequent_flyer_airline'] = $post_params['frequent_flyer_airline'];
                $booking_data['book_attributes']['frequent_flyer_no'] = $post_params['frequent_flyer_no'];
            }

            $booking_data['book_attributes']['total_fare_markup'] = $post_params['total_fare_markup'];
            $booking_data['book_attributes']['total_fare_tax'] = $post_params['total_fare_tax'];
            $book_params = $booking_data['book_attributes'];
            $data = $this->flight_lib->save_booking($book_id, $book_params, $currency_obj, $this->current_module);
            //Add Extra Service Price to Booking Amount
            $extra_services_total_price = $this->flight_model->get_extra_services_total_price($book_id);
            $amount += $extra_services_total_price;
            //debug($amount);exit;
            //debug($promocode_discount);
            /*             * ******* Convinence Fees Start ******* */
            $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
            $convenience_fees = $currency_obj->convenience_fees($amount, $search_id); //$post_params['convenience_fee']; 
            //$currency_obj->convenience_fees($amount, $search_id);
            // $convenience_fees = $pg_currency_conversion_rate * $convenience_fees;
            $convenience_fees = round($convenience_fees, 2);
            $amount = ($amount + $convenience_fees) - $promocode_discount;
            /*             * ******* Convinence Fees End ******* */
            # Get Insurance Amount
            $temp = $this->custom_db->single_table_records('insurance');
            // $insurance_data = $temp['data'][0]['amount'];
            // if (is_numeric($insurance_data)) {
            //     $insurance_amount = $insurance_data;
            // } else {
            //     $insurance_amount = 0; = 0;
            // }
            // $amount = round($amount,2);
            $verification_amount = round($amount, 2);
            //debug($verification_amount);exit;
            $insurance_amount = 0;
            switch ($post_params['payment_method']) {
                case PAY_NOW:
                    //debug(base_url().'flight/process_booking/'.$book_id.'/'.$book_origin);exit;
                    //redirect(base_url().'flight/process_booking/'.$book_id.'/'.$book_origin);	
                    $this->load->model('transaction');
                    $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                    $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $insurance_amount);
                    redirect(base_url() . 'payment_gateway/payment/' . $book_id . '/' . $book_origin);
                    // redirect(base_url().'flight/process_booking/'.$book_id.'/'.$book_origin);	
                    break;
                case PAY_AT_BANK:
                    echo 'Under Construction - Remote IO Error';
                    exit();
                    break;
            }
        }
        redirect(base_url() . 'flight/exception?op=Remote IO error @ FLIGHT Booking&notification=validation');
    }
    /* review page */
    public function review_passengers($app_reference = '', $book_origin = '')
    {
        $page_data['app_reference'] = $app_reference;
        $page_data['book_origin'] = $book_origin;
        $this->load->model('flight_model');
        $this->load->library('booking_data_formatter');
        if (empty($app_reference) == false) {
            $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
                $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');
                // debug($assembled_booking_details);die;
                $page_data['data'] = $assembled_booking_details['data'];
                $address = json_decode($booking_details['data']['booking_details']['0']['attributes'], true);
                $page_data['data']['address'] = $address['address'];
                $page_data['data']['logo'] = $assembled_booking_details['data']['booking_details']['0']['domain_logo'];
                $page_data['data']['email'] = $booking_details['data']['booking_details']['0']['email'];
                $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
                if (!empty($this->entity_country_code)) {
                    $page_data['user_country_code'] = $this->entity_country_code;
                } else {
                    $page_data['user_country_code'] = '92';
                }
                $page_data['phone_code'] = $this->custom_db->get_phone_code_list();
                $this->template->view('flight/review_passangers_details', $page_data);
            }
        }
    }
    function edit_pax()
    {
        $params = $this->input->post();
        if (count($params)) {
            $id = $params["origin"];
            $app_reference = $params["app_reference"];
            if (!$params['is_domestic']) {
                $passport_issuing_country = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $params['passenger_passport_issuing_country']));
                $params['passport_issuing_country'] = $passport_issuing_country[$params['passenger_passport_issuing_country']];
                $expiry_date = $params["date"][0] . "-" . $params["date"][1] . "-" . $params["date"][2];
                $params["passport_expiry_date"] = $expiry_date;
                $update_data['passport_expiry_date'] = $expiry_date;
                $update_data['passport_number'] = $params['passport_number'];
            }
            $update_data['origin'] = $params['origin'];
            $update_data['app_reference'] = $params['app_reference'];
            $update_data['first_name'] = $params['first_name'];
            $update_data['last_name'] = $params['last_name'];
            $update_data['date_of_birth'] = $params['date_of_birth'];
            $this->flight_model->update_pax_details($update_data, $id);
            redirect("flight/review_passengers/" . $app_reference);
        }
    }
    function edit_booking_details()
    {
        $params = $this->input->post();
        if (count($params)) {
            $id = $params["origin"];
            $app_reference = $params["app_reference"];
            $update_data["email"] = $params["email"];
            $update_data["phone"] = $params["phone"];
            $this->flight_model->update_booking_details($update_data, $id);
            redirect("flight/review_passengers/" . $app_reference);
        }
    }
    /*
      process booking in backend until show loader
     */
    function process_booking($book_id, $temp_book_origin)
    {
        if ($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0) {
            $page_data['form_url'] = base_url() . 'flight/secure_booking';
            $page_data['form_method'] = 'POST';
            $page_data['form_params']['book_id'] = $book_id;
            $page_data['form_params']['temp_book_origin'] = $temp_book_origin;
            $this->template->view('share/loader/booking_process_loader', $page_data);
        } else {
            redirect(base_url() . 'flight/exception?op=Invalid request&notification=validation');
        }
    }
    /**
     * 
     * Do booking once payment is successfull - Payment Gateway
     * and issue voucher
     */
    function secure_booking()
    {
        $post_data = $this->input->post();
        if (valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true && empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0) {
            //verify payment status and continue
            $book_id = trim($post_data['book_id']);
            $temp_book_origin = intval($post_data['temp_book_origin']);
            $this->load->model('transaction');
            $booking_status = $this->transaction->get_payment_status($book_id);
            //run booking request and do booking
            $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);

        if ($booking_status['status'] != 'accepted') {
            redirect(base_url() . 'flight/exception?op=Payment Failed&notification=Payment Failed');
        }
        } else {
            redirect(base_url() . 'flight/exception?op=InvalidBooking&notification=invalid');
        }
        //debug($temp_booking);exit;
        // load_trawelltag_lib(PROVAB_INSURANCE_BOOKING_SOURCE);
        //$response=array();
        // $response = $this->trawelltag->create_policy($temp_booking['book_attributes']);
        // debug($response);exit;
        // exit;
        //debug($temp_booking['book_attributes']);exit;
        //Delete the temp_booking record, after accessing
        // $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
        load_flight_lib($temp_booking['booking_source']);
        if (in_array($temp_booking['booking_source'], [PROVAB_FLIGHT_BOOKING_SOURCE])) {
            $currency_obj = new Currency(array(
                'module_type' => 'flight',
                'from' => admin_base_currency(),
                'to' => admin_base_currency()
            ));
            $flight_details = $temp_booking['book_attributes']['token']['token'];
            $flight_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
            $fare_details = $flight_booking_summary['FareDetails'][$this->current_module . '_PriceDetails'];
            $currency = $fare_details['Currency'];
        }
        // debug($temp_booking);exit;
        // verify payment status and continue
        if ($temp_booking != false) {
            switch ($temp_booking['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    // case CLARITYNDC_FLIGHT_BOOKING_SOURCE:
                    try {
                        $booking = $this->flight_lib->process_booking($book_id, $temp_booking['book_attributes']);
                    } catch (Exception $e) {
                        $booking['status'] = BOOKING_ERROR;
                    }
                    // Save booking based on booking status and book id
                    break;

                case TBO_FLIGHT_BOOKING_SOURCE:
                    try {
                        $booking = $this->flight_lib->process_booking($book_id, $temp_booking['book_attributes']);
                    } catch (Exception $e) {
                        $booking['status'] = BOOKING_ERROR;
                    }
                    // Save booking based on booking status and book id
                    break;
            }
            if (in_array($booking['status'], array(SUCCESS_STATUS, BOOKING_CONFIRMED, BOOKING_PENDING, BOOKING_FAILED, BOOKING_ERROR, BOOKING_HOLD, FAILURE_STATUS)) == true) {
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => admin_base_currency(),
                    'to' => admin_base_currency()
                ));
                $booking['data']['booking_params']['currency_obj'] = $currency_obj;
                //Update the booking Details
                $ticket_details = @$booking['data']['ticket'];
                $ticket_details['master_booking_status'] = $booking['status'];
                $data = $this->flight_lib->update_booking_details($book_id, $booking['data']['booking_params'], $ticket_details, $this->current_module);
                //debug($data);exit;
                //Update Transaction Details
                $this->domain_management_model->update_transaction_details('flight', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'], $data['transaction_currency'], $data['currency_conversion_rate']);
                if (in_array($data['status'], array(
                    'BOOKING_CONFIRMED',
                    'BOOKING_PENDING',
                    'BOOKING_HOLD',
                    'BOOKING_FAILED'
                ))) {
                    $tokenResult = $this->custom_db->single_table_records('promocode_token', 'token', array('user_id' => $this->entity_user_id, 'status' => ACTIVE));
                    if ($tokenResult['status']) {
                        $token = $tokenResult['data'][0]['token'];
                        $promotrack = $this->custom_db->single_table_records('new_user_promocode_track', 'promo_code_remaining_value', array('token' => $token, 'status' => INACTIVE));
                        $UpdatePromoCodeData['promo_code_remaining_value'] = ($promotrack['status']) ? $promotrack['data'][0]['promo_code_remaining_value'] : 0;
                        $this->custom_db->update_record('user', $UpdatePromoCodeData, array('user_id' => $this->entity_user_id));
                        $this->custom_db->update_record('new_user_promocode_track', array('status' => ACTIVE), array('token' => $token));
                        $this->custom_db->delete_record('promocode_token', array('user_id' => $this->entity_user_id));
                    }
                    // Sms config & Checkpoint
                    /* if (active_sms_checkpoint ( 'booking' )) {
                      $msg = "Dear " . $data ['name'] . " Thank you for Booking your ticket with us.Ticket Details will be sent to your email id";
                      $msg = urlencode ( $msg );
                      $sms_status = $this->provab_sms->send_msg ( $data ['phone'], $msg );
                      // return $sms_status;
                      } */
                    # Call Insurance API
                    //  load_trawelltag_lib(PROVAB_INSURANCE_BOOKING_SOURCE);
                    //$response=array();
                    // $response = $this->trawelltag->create_policy($temp_booking['book_attributes']);
                    //  $response['app_reference']=$book_id;
                    // $response['travel_date']='2019-04-11';
                    //$save_details = $this->flight_model->save_insurance_details($response);

                    redirect(base_url() . 'voucher/flight/' . $book_id . '/' . $temp_booking['booking_source'] . '/' . $data['status'] . '/booking_voucher');
                } else {
                    redirect(base_url() . 'flight/exception?op=booking_exception&notification=' . $booking['message']);
                }
            } else {
                redirect(base_url() . 'flight/exception?op=booking_exception&notification=' . $booking['message']);
            }
        }
    }
    /**
     * 
     * Process booking on hold - pay at bank
     * Issue Ticket Later
     */
    function booking_on_hold($book_id)
    {
        load_trawelltag_lib(PROVAB_INSURANCE_BOOKING_SOURCE);
        $response = array();
        $response = $this->trawelltag->create_policy($response);
        debug($response);
        exit;
        $response['app_reference'] = 'FB-4251-45782-24578';
        $response['travel_date'] = '2019-04-11';
        $save_details = $this->flight_model->save_insurance_details($response);
    }
    /**
     * 
     */
    function pre_cancellation($app_reference, $booking_source)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $page_data = array();
            $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
                $page_data['data'] = $assembled_booking_details['data'];
                $this->template->view('flight/pre_cancellation', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    /**
     * 
     * @param $app_reference
     */
    function cancel_booking()
    {
        $post_data = $this->input->post();
        if (
            isset($post_data['app_reference']) == true && isset($post_data['booking_source']) == true && isset($post_data['transaction_origin']) == true &&
            valid_array($post_data['transaction_origin']) == true && isset($post_data['passenger_origin']) == true && valid_array($post_data['passenger_origin']) == true
        ) {
            $app_reference = trim($post_data['app_reference']);
            $booking_source = trim($post_data['booking_source']);
            $transaction_origin = $post_data['transaction_origin'];
            $passenger_origin = $post_data['passenger_origin'];
            $booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                load_flight_lib($booking_source);
                //Formatting the Data
                $this->load->library('booking_data_formatter');
                $booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
                $booking_details = $booking_details['data'];
                //Grouping the Passenger Ticket Ids
                $grouped_passenger_ticket_details = $this->flight_lib->group_cancellation_passenger_ticket_id($booking_details, $passenger_origin);
                $passenger_origin = $grouped_passenger_ticket_details['passenger_origin'];
                $passenger_ticket_id = $grouped_passenger_ticket_details['passenger_ticket_id'];
                $cancellation_details = $this->flight_lib->cancel_booking($booking_details, $passenger_origin, $passenger_ticket_id);
                // debug($cancellation_details);exit;
                redirect('flight/cancellation_details/' . $app_reference . '/' . $booking_source . '/' . $cancellation_details['status']);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    function cancellation_details($app_reference, $booking_source, $cancellation_status)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $master_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_flight_booking_data($master_booking_details, $this->current_module);
                $page_data['data'] = $master_booking_details['data'];
                $page_data['cancellation_status'] = $cancellation_status;
                $this->template->view('flight/cancellation_details', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    /**
     * 
     */
    function exception()
    {
        $module = META_AIRLINE_COURSE;
        $op = @$_GET['op'];
        $notification = @$_GET['notification'];
        // echo $notification;exit;
        if ($notification == 'Booking is already done for the same criteria for PNR') {
            $message = 'Please add another criteria and try again';
        } else if ($notification == 'SEAT NOT AVAILABLE' || $notification == 'seat no available') {
            $message = 'Please book another flight and try again';
        } else if ($notification == 'Sell Failure') {
            $message = 'Please try again for the same criteria';
        } else if ($notification == 'The requested class of service is sold out.') {
            $message = 'Please try another booking';
        } else if ($notification == 'Supplier Interaction Failed while adding Pax Details. Reason: 18|Presentation|Fusion DSC found an exception !\n\tThe data does not match the maximum length: \n\tFor data element: freetext\n\tData length should be at least 1 and at most 70\n\tCurrent position in buffer') {
            $message = 'Please add more than 2 characters in the name field and try agian';
        } else if ($notification == 'Agency do not have enough balance.') {
            $message = 'Please add balance and try again';
        } else if ($notification == 'Invalid CommitBooking Request') {
            $message = 'Session is Expired. Please try again';
        } else if ($notification == 'session') {
            $message = 'Session is Expired. Please try again';
        } else {
            $message = $notification . ' Please try again';
        }
        // echo $message;exit;
        $exception = $this->module_model->flight_log_exception($module, $op, $message);
        $exception = base64_encode(json_encode($exception));
        // debug($exception);exit;
        // set ip log session before redirection
        $this->session->set_flashdata(array(
            'log_ip_info' => true
        ));
        redirect(base_url() . 'flight/event_logger/' . $exception);
    }
    function event_logger($exception = '')
    {
        $log_ip_info = $this->session->flashdata('log_ip_info');
        $this->template->view('flight/exception', array(
            'log_ip_info' => $log_ip_info,
            'exception' => $exception
        ));
    }
    function test_server()
    {
        $data = $this->custom_db->single_table_records('test', '*', array('origin' => 851));
        $response = json_decode($data['data'][0]['test'], true);
    }
    function mail_send_voucher($app_reference, $booking_source, $booking_status, $module)
    {
        send_email($app_reference, $booking_source, $booking_status, $module);
    }
    public function payment_response()
    {
        // $json = json_encode ( array('time'=>date("Y-m-d H:i:s"),'get'=>$this->input->get(),'post'=>$this->input->post(),'gpost'=>$_POST,'gget'=>$_GET), JSON_FORCE_OBJECT | JSON_PRETTY_PRINT );
        // file_put_contents('logs/payment_response.txt',  $json . PHP_EOL, FILE_APPEND);
    }
    public function update_booking_status()
    {
        $bookings = $this->db->select('b.status,b.app_reference,t.book_id,t.pnr,t.status as transaction_status')
            ->join('flight_booking_transaction_details t', 't.app_reference=b.app_reference')
            ->where('b.booking_source', CLARITYNDC_FLIGHT_BOOKING_SOURCE)
            ->where('b.journey_start >', date('Y-m-d H:i:s'))
            ->where_in('b.status', array('BOOKING_CONFIRMED', 'BOOKING_HOLD'))
            ->where('t.book_id !=', '')
            ->where('t.pnr !=', '')
            ->order_by('b.created_datetime', 'DESC')
            ->get('flight_booking_details b')->result_array();
        if ($bookings) {
            load_flight_lib(CLARITYNDC_FLIGHT_BOOKING_SOURCE);
            foreach ($bookings as $booking) {
                $response = $this->flight_lib->AirOrderRetreive($booking['book_id'], $booking['pnr']);
                if ($response) {
                    $app_reference = $booking['app_reference'];
                    $order = $response;
                    $OrderStatus = $order['Order'][0]['OrderStatus'];
                    $TicketStatus = $order['Order'][0]['TicketStatus'];
                    $ticket_info = !empty($order['TicketDocInfos']['TicketDocInfo']) ? $order['TicketDocInfos']['TicketDocInfo'] : '';
                    if ($ticket_info) {
                        $_status = ' | status:' . $booking['status'] . ' | transaction_status:' . $booking['transaction_status'];
                        $cd_query = 'select distinct CD.*,FPTI.api_passenger_origin,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo 
						from flight_booking_passenger_details AS CD 
						left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk 
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference =' . $this->db->escape($app_reference) . ')';
                        $pax = $this->db->query($cd_query)->result_array();
                        if ($pax && !$pax[0]['TicketNumber']) {
                            foreach ($pax as $k => $booking_customer_data) {
                                $update_ticket_info = array();
                                $update_ticket_info['TicketId'] = @$ticket_info[$k]['TicketDocument']['TicketDocNbr'];
                                $update_ticket_info['TicketNumber'] = @$ticket_info[$k]['TicketDocument']['TicketDocNbr'];
                                $this->db->where('passenger_fk', $booking_customer_data['origin']);
                                $this->db->update('flight_passenger_ticket_info', $update_ticket_info);
                                // file_put_contents(FCPATH ."logs/logs_cron_". date("Y-m-d") .".txt", date("H:i:s") . ' ticketID:' .$booking_customer_data['origin'].$_status. PHP_EOL, FILE_APPEND);
                            }
                        }
                    }
                    if ($OrderStatus == 'CANCELED' || $TicketStatus == 'VOIDED') {
                        if ($booking['transaction_status'] != 'BOOKING_CANCELLED' || $booking['status'] != 'BOOKING_CANCELLED') {
                            $this->db->where('app_reference', $app_reference);
                            $this->db->update('flight_booking_transaction_details', ['status' => 'BOOKING_CANCELLED']);
                            $this->db->where('app_reference', $app_reference);
                            $this->db->update('flight_booking_details', array('status' => 'BOOKING_CANCELLED'));
                            $this->db->where('app_reference', $app_reference);
                            $this->db->update('flight_booking_passenger_details', array('status' => 'BOOKING_CANCELLED'));
                            // file_put_contents(FCPATH ."logs/logs_cron_". date("Y-m-d") .".txt", date("H:i:s") . ' BOOKING_CANCELLED:' .$app_reference.$_status. PHP_EOL, FILE_APPEND);
                        }
                    } else if ($TicketStatus == 'TICKETED' || $OrderStatus == 'BOOKED') {
                        if ($booking['status'] == 'BOOKING_HOLD' || $booking['transaction_status'] == 'BOOKING_HOLD') {
                            $this->db->where('app_reference', $app_reference);
                            $this->db->update('flight_booking_details', array('status' => 'BOOKING_CONFIRMED'));
                            $this->db->where('app_reference', $app_reference);
                            $this->db->update('flight_booking_passenger_details', array('status' => 'BOOKING_CONFIRMED'));
                            $this->db->where('app_reference', $app_reference);
                            $this->db->update('flight_booking_transaction_details', ['status' => 'BOOKING_CONFIRMED']);
                            // file_put_contents(FCPATH ."logs/logs_cron_". date("Y-m-d") .".txt", date("H:i:s") . ' BOOKING_CONFIRMED:' .$app_reference.$_status. PHP_EOL, FILE_APPEND);
                        }
                    }
                }
            }
        }
    }
    function dynamic_search($city_name)
    {
        $location = urldecode($city_name);
        $airport_city = explode('-', $location);
        if (!empty($airport_city) && count($airport_city) == 2) {
            if (strlen($airport_city[0]) == 3) {
                $from_cnd['airport_code'] = strtoupper($airport_city[0]);
                $from_cnd['isTravellerAirport'] = 1;
                $to_cnd['airport_code'] = strtoupper($airport_city[1]);
                $to_cnd['isTravellerAirport'] = 1;
            } else {
                $from_cnd['airport_city'] = $airport_city[0];
                $from_cnd['isTravellerAirport'] = 1;
                $to_cnd['airport_city'] = $airport_city[1];
                $to_cnd['isTravellerAirport'] = 1;
            }
            $columns = 'airport_city, airport_code, origin';
            $from_airport = $this->custom_db->single_table_records('flight_airport_list', $columns, $from_cnd);
            $to_airport = $this->custom_db->single_table_records('flight_airport_list', $columns, $to_cnd);
            $from_loc_id = 0;
            if ($from_airport['status'] == ACTIVE) {
                $from = $from_airport['data'][0]['airport_city'] . ' (' . $from_airport['data'][0]['airport_code'] . ')';
                $from_loc_id = $from_airport['data'][0]['origin'];
            }
            $to_loc_id = 0;
            if ($to_airport['status'] == ACTIVE) {
                $to = $to_airport['data'][0]['airport_city'] . ' (' . $to_airport['data'][0]['airport_code'] . ')';
                $to_loc_id = $to_airport['data'][0]['origin'];
            }
            if ($from_loc_id != 0  && $to_loc_id != 0) {
                $search_data = array(
                    'trip_type' => 'oneway', // Default trip type oneway
                    'v_class' => 'Economy', // Default class Economy
                    'alt_days' => '',
                    'from' => @$from,
                    'from_loc_id' => $from_loc_id,
                    'to' => @$to,
                    'to_loc_id' => $to_loc_id,
                    'depature' => date('d-m-Y', strtotime(' +1 day')),
                    'carrier' => 1,
                    'adult' => 1,
                    'child' => 0,
                    'infant' => 0,
                    'search_flight' => 'Search Flight' // Default search type
                );
                $search_data_json = json_encode($search_data);
                $insert_id = $this->custom_db->insert_record('search_history', array('search_type' => META_AIRLINE_COURSE, 'search_data' => $search_data_json, 'created_datetime' => date('Y-m-d H:i:s')));
                $this->search($insert_id['insert_id']);
            } else {
                $this->template->view('utilities/404.php');
            }
        } else {
            $this->template->view('utilities/404.php');
        }
    }
}

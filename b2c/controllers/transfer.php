<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transfer extends MY_Controller {

    private $current_module;
    public $transfer_model;

    public function __construct() {
        parent::__construct();
        //we need to activate hotel api which are active for current domain and load those libraries
        $this->load->model('transfer_model');
        $this->load->library('social_network/facebook'); //Facebook Library to enable login button		
        $this->current_module = $this->config->item('current_module');
    }

    /**
     * index page of application will be loaded here
     */
    function index() {
        //	echo number_format(0, 2, '.', '');
    }

    /**
     *  Balu A
     * Load Hotel Search Result
     * @param number $search_id unique number which identifies search criteria given by user at the time of searching
     */
    function search($search_id) {
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);

        // Get all the hotels bookings source which are active
        $active_booking_source = $this->transfer_model->active_booking_source();
        // debug($active_booking_source);exit;
        if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
            $safe_search_data['data']['search_id'] = abs($search_id);
            $this->template->view('transfer/search_result_page', array('transfer_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
        } else {
            $this->template->view('general/popup_redirect');
        }
    }

    /**
     * Elavarasi
     */
    function pre_cancellation($app_reference, $booking_source)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $page_data = array();
            $booking_details = $this->transfer_model->get_booking_details($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_transfer_booking_data($booking_details, 'b2c');
                $page_data['data'] = $assembled_booking_details['data'];
                $this->template->view('transfer/pre_cancellation', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    /**
     *  Elavarasi
     * Passenger Details page for final bookings
     * Here we need to run booking based on api
     */
    function booking()
    {

        $pre_booking_params = $this->input->post();
  
        $search_id = $pre_booking_params['search_id'];
        $safe_search_data = $this->transfer_model->get_safe_search_data($pre_booking_params['search_id'],META_TRANSFER_COURSE);
        $paxes[0]['type'] = "Adult";
        $paxes[0]['count'] = $safe_search_data['data']['adult'];
        $paxes[1]['type'] = "Child";
        $paxes[1]['count'] = $safe_search_data['data']['child'];
        $safe_search_data['data']['search_id'] = abs($search_id);

        $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
        if (isset($pre_booking_params['booking_source']) == true) {
            
            //We will load different page for different API providers... As we have dependency on API for tourgrade details page
            $page_data['search_data'] = $safe_search_data['data'];

            load_transfer_lib($pre_booking_params['booking_source']);

            //Need to fill pax details by default if user has already logged in
            $this->load->model('user_model');
            $page_data['pax_details'] = $this->user_model->get_current_user_details();


            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            if ($pre_booking_params['booking_source'] == PROVAB_TRANSFER_BOOKING_SOURCE &&  
            isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'booking' and $safe_search_data['status'] == true)
            {
                    
                    $transfer_block_details = $this->transfer_lib->block_transfer($pre_booking_params);


                    if ($transfer_block_details['status'] == false) {
                        redirect(base_url().'index.php/transfer/exception?op='.$transfer_block_details['data']['msg']);
                    }
                    //Converting API currency data to preferred currency
                    $currency_obj = new Currency(array('module_type' => 'transfer','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                    
                    $transfer_block_details = $this->transfer_lib->transferblock_data_in_preferred_currency($transfer_block_details, $currency_obj,'b2c');

                    
                    //Display
                    $currency_obj = new Currency(array('module_type' => 'transfer', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                    

                    $cancel_currency_obj = new Currency(array('module_type' => 'transfer','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

                    $pre_booking_params = $this->transfer_lib->update_block_details($transfer_block_details['data']['BlockTransfer'], $pre_booking_params,$currency_obj,'b2c');                   
                    
                    /*
                     * Update Markup
                     */                 
                    $pre_booking_params['markup_price_summary'] = $this->transfer_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id'], false, true, 'b2c');
                    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
                    
                    // debug($pre_booking_params['markup_price_summary'] );
                    // exit;
                    if ($transfer_block_details['status'] == SUCCESS_STATUS) {
                        if(!empty($this->entity_country_code)){
                            $mobile_code = $this->db_cache_api->get_mobile_code($this->entity_country_code);
                            $page_data['user_country_code'] = $mobile_code;
                        }
                        else{
                            $page_data['user_country_code'] = $Domain_record['data'][0]['phone_code'];  
                        }
                        $page_data['booking_source'] = $pre_booking_params['booking_source'];
                        $page_data['pre_booking_params'] = $pre_booking_params;
                        $page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
                        
                        $page_data['iso_country_list']  = $this->db_cache_api->get_iso_country_list();
                        $page_data['country_list']      = $this->db_cache_api->get_country_list();
                        $page_data['currency_obj']      = $currency_obj;
                        $page_data['total_price']       = $this->transfer_lib->total_price($pre_booking_params['markup_price_summary']);
                        

                        $page_data['convenience_fees']  = $currency_obj->convenience_fees($page_data['total_price'], $paxes);
                        $page_data['tax_service_sum']   =  $this->transfer_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);

                        //Traveller Details
                        $page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
                        //Get the country phone code 
                        $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
                        $page_data['active_data'] =$Domain_record['data'][0];
                        $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
                        $page_data['phone_code'] =$temp_record['data'];
                        $page_data['promo_codes'] = $this->custom_db->get_custom_query("select * from  promo_code_list where status=1 and module in ('transfer') AND expiry_date >= CURDATE()");
                        
                        $page_data['search_id'] = $search_id;
                        //debug($page_data );exit;
                        $this->template->view('transfer/hotelbeds/hotelbeds_booking_page', $page_data);
                    }
                
            } else {

                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }
    /**
     *  Elavarasi
     * sending for booking   
     */
    function pre_booking($search_id)
    {
        //error_reporting();
        $post_params = $this->input->post();
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
        $paxes[0]['type'] = "Adult";
        $paxes[0]['count'] = $safe_search_data['data']['adult'];
        $paxes[0]['type'] = "Child";
        $paxes[0]['count'] = $safe_search_data['data']['child'];
        // debug($post_params);exit;
        $post_params['billing_city'] = 'Bangalore';
        $post_params['billing_zipcode'] = '560100';
        $post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
        

        //Make sure token and temp token matches
        $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
        //debug($valid_temp_token);exit;
        if ($valid_temp_token != false) {

            load_transfer_lib($post_params['booking_source']);
            /****Convert Display currency to Application default currency***/
            //After converting to default currency, storing in temp_booking
            $post_params['token'] = unserialized_data($post_params['token']);
            
            $currency_obj = new Currency ( array (
                        'module_type' => 'transfer',
                        'from' => get_application_currency_preference (),
                        'to' => get_application_default_currency () 
                ));

            #debug($post_params['token']);
            $post_params['token'] = $this->transfer_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
            // debug($post_params['token']);
            // exit;
            $post_params['token'] = serialized_data($post_params['token']);

            $temp_token = unserialized_data($post_params['token']);
            //Insert To temp_booking and proceed
            $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, TRANSFER_BOOKING);
            $book_id = $temp_booking['book_id'];
            $book_origin = $temp_booking['temp_booking_origin'];
            
            // debug($temp_token);
            // exit;

            if ($post_params['booking_source'] == PROVAB_TRANSFER_BOOKING_SOURCE) {
                $amount   = $this->transfer_lib->total_price($temp_token['markup_price_summary']);
                $currency = $temp_token['default_currency'];
            }
            $currency_obj = new Currency ( array (
                        'module_type' => 'transfer',
                        'from' => admin_base_currency (),
                        'to' => admin_base_currency () 
            ) );
            /********* Convinence Fees Start ********/
            
            $convenience_fees = $currency_obj->convenience_fees($amount, $paxes);
            /********* Convinence Fees End ********/
                
            /********* Promocode Start ********/
              /* if(isset($post_params['promo_code_discount_val'])) {
                $key=provab_encrypt($post_params['key']);
                $data = $this->custom_db->single_table_records('promo_code_doscount_applied', '*', array('search_key' =>$key));
                if($data['status']==true) {
                 $promocode_discount = $data['data'][0]['discount_value'];
                //$promocode_discount = $post_params['promo_code_discount_val'];
                }
                 } */ 

            $promocode_discount = round($post_params['promo_code_discount_val'], 2);
            //debug($promocode_discount);
            $currency_obj_promo = new Currency(array(
                'module_type' => 'car',
                'from' => get_application_currency_preference(),
                'to' => get_application_default_currency()
            ));
            $conversion_rate = $currency_obj_promo->conversion_cache[get_application_currency_preference() . get_application_default_currency()];
            $promocode_discount = str_replace(',', '', $promocode_discount) * $conversion_rate;
            $promocode_discount = round($promocode_discount, 2);

            /********* Promocode End ********/

            //details for PGI
            
            $email = $post_params ['billing_email'];
            $phone = $post_params ['passenger_contact'];
            $verification_amount = roundoff_number($amount+$convenience_fees-$promocode_discount);

            $booking_data = $this->module_model->unserialize_temp_booking_record($book_id, $book_origin);
            $book_params = $booking_data['book_attributes'];
            $data = $this->transfer_lib->save_booking($book_id, $book_params, $currency_obj, $this->current_module);
            //debug($data);exit;
            //$verification_amount = roundoff_number($amount);
            $firstname = $post_params ['first_name'] ['0'];
            $productinfo = META_TRANSFER_COURSE;
            //check current balance before proceeding further
            $domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);
            $insurance_amount = 0;
            $billing_city = $post_params['billing_city'];
            $billing_country = $post_params['country_code'];
            $billing_state = "Karnataka";
            $billing_address = $post_params['billing_address_1'];
            $billing_zip = $post_params['billing_zipcode'];
            $gst = 0;           
            if ($domain_balance_status == true) {
                switch($post_params['payment_method']) {
                    case PAY_NOW :
                        $this->load->model('transaction');
                        $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                        $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $insurance_amount, $gst, $billing_city, $billing_country, $billing_state, $billing_address, $billing_zip);
                        // redirect(base_url().'index.php/transfer/process_booking/'.$book_id.'/'.$book_origin);
                        redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);                      
                        break;
                    case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
                    break;
                }
            } else {
                redirect(base_url().'index.php/transfer/exception?op=Amount Transfers Booking&notification=insufficient_balance');
            }
        } else {
            redirect(base_url().'index.php/transfer/exception?op=Remote IO error @ Transfers Booking&notification=validation');
        }
    }
    /*
        process booking in backend until show loader 
    */
    function process_booking($book_id, $temp_book_origin){
        
        if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

            $page_data ['form_url'] = base_url () . 'index.php/transfer/secure_booking';
            $page_data ['form_method'] = 'POST';
            $page_data ['form_params'] ['book_id'] = $book_id;
            $page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;

            $this->template->view('share/loader/booking_process_loader', $page_data);   

        }else{
            redirect(base_url().'index.php/transfer/exception?op=Invalid request&notification=validation');
        }
        
    }
    /**
     * Elavarasi
     *Do booking once payment is successfull - Payment Gateway
     *and issue voucher
     */
    function secure_booking()
    {
        $post_data = $this->input->post();
        
        if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
            empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
            //verify payment status and continue
            $book_id = trim($post_data['book_id']);
            $temp_book_origin = intval($post_data['temp_book_origin']);
            $this->load->model('transaction');
            $booking_status = $this->transaction->get_payment_status($book_id);
             #=================================================================================#
             # Important Note :-
             # Please check this condition and It's very important, according to the payment gateway response(status should be accepted or success). if you removed this condtion it's un secure.
             #==================================================================================#
                 if($booking_status['status'] != 'accepted'){
                redirect(base_url().'index.php/transfer/exception?op=Payment Not Done&notification=validation');
                 }
        } else{
            redirect(base_url().'index.php/transfer/exception?op=InvalidBooking&notification=invalid');
        }       
        //run booking request and do booking
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
        // debug($temp_booking);exit;
        //Delete the temp_booking record, after accessing
        //$this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
        load_transfer_lib($temp_booking['booking_source']);
        //verify payment status and continue        

        $total_booking_price = $this->transfer_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);       
        // debug($temp_booking);
        // exit;
        $currency = $temp_booking['book_attributes']['token']['default_currency'];
        //also verify provab balance
        //check current balance before proceeding further
        $domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);

        if ($domain_balance_status) {
            //lock table
            if ($temp_booking != false) {
                switch ($temp_booking['booking_source']) {
                    case PROVAB_TRANSFER_BOOKING_SOURCE :
                    
                        //FIXME : COntinue from here - Booking request
                        $booking = $this->transfer_lib->process_booking($book_id, $temp_booking['book_attributes']);
                        //debug($booking);exit;
                        //Save booking based on booking status and book id
                        break;
                }

                if ($booking['status'] == SUCCESS_STATUS) {

                    $currency_obj = new Currency(array('module_type' => 'transfer', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
                    $promo_currency_obj = new Currency(array('module_type' => 'transfer', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));
                    $booking['data']['currency_obj'] = $currency_obj;
                    $booking['data']['promo_currency_obj']=$promo_currency_obj;
                    //Save booking based on booking status and book id
                    //$data = $this->transferv1_lib->save_booking($book_id, $booking['data']);


                    $ticket_details = @$booking ['data'] ['ticket'];
                    $ticket_details['master_booking_status'] = $booking ['status'];

                    $data = $this->transfer_lib->update_booking_details($book_id, $booking ['data'], $ticket_details,$currency_obj,$this->current_module);
                     //debug($data);exit;
                    $this->domain_management_model->update_transaction_details('transfer', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'] );

                    redirect(base_url().'index.php/voucher/transfer/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/booking_voucher');
                } else {
                    redirect(base_url().'index.php/transfer/exception?op=booking_exception&notification='.$booking['Message']);
                }
            }
            //release table lock
        } else {
            redirect(base_url().'index.php/transfer/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
        //redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Secure Booking&notification=validation');
    }
    /*
     * Elavarasi
     * Process the Booking Cancellation
     * Full Booking Cancellation
     *
     */
    function cancel_booking($app_reference, $booking_source)
    {
        if(empty($app_reference) == false) {
            $get_params = $this->input->get();

            $master_booking_details = $this->transfer_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_transfer_booking_data($master_booking_details, 'b2c');
                $master_booking_details = $master_booking_details['data']['booking_details'][0];
                load_transfer_lib($booking_source);
                $cancellation_details = $this->transfer_lib->cancel_booking($master_booking_details,$get_params);//Invoke Cancellation Methods
                if($cancellation_details['status'] == false) {
                    $query_string = '?error_msg='.$cancellation_details['msg'];
                } else {
                    $query_string = '';
                }
                redirect('transfer/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    /**
     * Elavarasi
     * Cancellation Details
     * @param $app_reference
     * @param $booking_source
     */
    function cancellation_details($app_reference, $booking_source)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $master_booking_details = $GLOBALS['CI']->transfer_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_transfer_booking_data($master_booking_details, 'b2c');
                $page_data['data'] = $master_booking_details['data'];
                $this->template->view('transfer/cancellation_details', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }

    }


    function test(){
        $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
        debug($currency_obj);
    }

 
    /**
     * Elavarasi
     */
    function exception($redirect=true)
    {
        $module = META_TRANSFER_COURSE;
        $op = (empty($_GET['op']) == true ? '' : $_GET['op']);
        $notification = (empty($_GET['notification']) == true ? '' : $_GET['notification']);
        if($op == 'Some Problem Occured. Please Search Again to continue'){
            $op = 'Some Problem Occured. ';
        }
        if($notification=='In Sufficiant Balance'){
        
            $notification = 'In Sufficiant Balance For Transfers';
        }

        $eid = $this->module_model->log_exception($module, $op, $notification);

        //set ip log session before redirection
        $this->session->set_flashdata(array('log_ip_info' => true));
    
        if($redirect){
            redirect(base_url().'index.php/transfer/event_logger/'.$eid);
        }
        
    }
    function event_logger($eid='')
    {
        
        $log_ip_info = $this->session->flashdata('log_ip_info');
        $exception_data  = $this->custom_db->single_table_records('exception_logger','*',array('exception_id'=>$eid),0,1);
        $exception=$exception_data['data'][0];
        $this->template->view('transfer/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid,'exception'=>$exception));
    }
}

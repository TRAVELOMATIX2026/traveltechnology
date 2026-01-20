<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function validate_user_login() {
    if (is_logged_in_user() == false) {
        redirect(base_url());
    }
}
function index_month_number($data) {
    $imn_data = array();
    if (valid_array($data)) {
        foreach ($data as $k => $v) {
            $imn_data[($v['month_number'] - 1)] = $v; //Dont Change--Balu A
            //$imn_data[$k] = $v;//In HighChart, Month starts with 0;
        }
    }
    return $imn_data;
}
/**
 * Hotel Suggestion value for input element
 */
function hotel_suggestion_value($city, $country) {
    return $city . ' (' . $country . ')';
}
// ---------------------------------------------------------- Active Module Start
/**
 * Check if current module is enabled
 */
function is_active_airline_module() {
    $status = false;
    if (in_array(META_AIRLINE_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_hotel_module() {
    $status = false;
    if (in_array(META_ACCOMODATION_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_bus_module() {
    $status = false;
    if (in_array(META_BUS_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_car_module() {
    $status = false;
   
    if (in_array(META_CAR_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_package_module() {
    $status = false;
    if (in_array(META_PACKAGE_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_sightseeing_module() {
    $status = false;
   
    if (in_array(META_SIGHTSEEING_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_transferv1_module() {
    $status = false;
   
    if (in_array(META_TRANSFERV1_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
function is_active_cruise_module() {
    $status = false;
    if (in_array(META_CRUISE_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * Check if current module is enabled
 */
function is_active_transfer_module() {
    $status = false;
    if (in_array(META_TRANSFER_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    } 
    return $status;
}

/**
 * Check if current module is enabled
 */
function is_active_flight_hotel_module() {
    $status = false;
    if (in_array(META_FLIGHT_HOTEL_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    } 
    return $status;
}

/**
 * Check if end module is enabled
 */
function is_active_module($module) {
    $cond = array(
        "module" => $module,
        "status" => ACTIVE
    );
    $var = $GLOBALS['CI']->custom_db->single_table_records('active_modules', '', $cond);
    return $var['status'];
}
function is_active_tour_module() {
    $status = false;
   
    if (in_array(META_PACKAGE_COURSE, $GLOBALS['CI']->active_domain_modules)) {
        $status = true;
    }
    return $status;
}
/**
 * checking social login status
 */
function is_active_social_login($module) {
    $CI = & get_instance();
    $social_links = $CI->db_cache_api->get_active_social_network_list();
    return (isset($social_links[$module]) ? $social_links[$module]['status'] : false);
}
function no_social() {
    $CI = & get_instance();
    $social_links = $CI->db_cache_api->get_active_social_network_list();
    if (is_array($social_links) == true) {
        foreach ($social_links as $k => $v) {
            if ($v['status'] == false) {
                return false;
                break;
            }
        }
    } else {
        return false;
    }
}
// ---------------------------------------------------------- Active Module End
// ---------------------------------------------------------- Load Library Start
/**
 * Load Hotel Lib based on hotel source
 * @param string $source
 */
function load_hotel_lib($source) { 
    $CI = &get_instance();
    switch ($source) {
        case PROVAB_HOTEL_BOOKING_SOURCE :
            $CI->load->library('hotel/provab_private', '', 'hotel_lib');
            break;
        
        case TBO_HOTEL_BOOKING_SOURCE :
    
            $CI->load->library('hotel/tboholidays', '', 'hotel_lib');
            break; 
        default : redirect(base_url());
    }
}
function multipleImagesUrlFormat($source, $data,$image_size="bigger") 
{ 
    $response = [];
    $images = [];
    switch ($source) 
    {
        case PROVAB_HOTEL_BOOKING_SOURCE :
            $response = $data;
            break;
        case HOTELBED_BOOKING_SOURCE :
            if(!empty($image_size))
            {
                $image_size = $image_size.'/';
            }
            if(is_array($data))
            {
                foreach($data as $key =>$val)
                {
                    $data[$key]['path'] = 'https://photos.hotelbeds.com/giata/'.$image_size.$val['path'];
                }
            }
            
            //debug($data);exit;
             $response = $data;
            break;
        case HYPER_GUEST_HOTEL_BOOKING_SOURCE :
            $response = array();
            if(!is_array($data)){
                $data = array($data);
            }
            foreach($data as $key =>$val)
            {
                $response[$key]['path'] = $val;
            }
             //$response = $data;
            break; 
        case Go_Global_BOOKING_SOURCE :
        
              $response = $data;
            break; 
        default :  $response = $data;
    }
    return $response;
}
function load_sightseen_lib($source) {
    $CI = &get_instance();
    switch ($source) {
        case PROVAB_SIGHTSEEN_BOOKING_SOURCE :
       
            $CI->load->library('sightseeing/provab_private', '', 'sightseeing_lib');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load Car Lib based on car source
 * @param string $source
 */
function load_car_lib($source) {
    $CI = &get_instance();
    switch ($source) {
        case PROVAB_CAR_BOOKING_SOURCE :
            $CI->load->library('car/provab_private', '', 'car_lib');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load Flight Lib based on hotel source
 * @param string $source
 */
function load_flight_lib($source) {
    $CI = &get_instance();
    switch ($source) {
        case PROVAB_FLIGHT_BOOKING_SOURCE :
            $CI->load->library('flight/provab_private', '', 'flight_lib');
            break;
        case TBO_FLIGHT_BOOKING_SOURCE :
            $CI->load->library('flight/tbo_private', '', 'flight_lib');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load Transfer Lib based on hotel source
 * @param string $source
 */
function load_transfer_lib($source) {
    
    $CI = &get_instance();
   
    switch ($source) {
   
        case HOTELBED_TRANSFER_BOOKING_SOURCE :
                
            $CI->load->library('transfer/provab_private', '', 'transfer_lib');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load Transfer Lib based on transfer source
 * @param string $source
 */
function load_transferv1_lib($source) {
    
    $CI = &get_instance();
   
    switch ($source) {
   
        case PROVAB_TRANSFERV1_BOOKING_SOURCE :
                
            $CI->load->library('transferv1/provab_private', '', 'transferv1_lib');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load PG Lib based on hotel source
 * @param string $source
 */
function load_pg_lib($source) {
    $CI = &get_instance();
//debug($source);exit;
    switch ($source) {
        case 'STRIPE' :
            $GLOBALS['CI']->load->library('payment_gateway/Stripe', '', 'pg');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load Bus Lib based on bus source
 * @param string $source
 */
function load_bus_lib($source) {
    $CI = &get_instance();
    switch ($source) {
        case PROVAB_BUS_BOOKING_SOURCE :
            $CI->load->library('bus/provab_private', '', 'bus_lib');
            break;
        case RED_BUS_BOOKING_SOURCE :
            $CI->load->library('bus/red_bus', '', 'bus_lib');
            break;
        default : redirect(base_url());
    }
}
/**
 * Load Flight Lib based on hotel source
 * @param string $source
 */
function load_trawelltag_lib($source) {
    $CI = &get_instance();
  
    switch ($source) {
        case PROVAB_INSURANCE_BOOKING_SOURCE :
            
            $GLOBALS['CI']->load->library('trawelltag/insurance', '', 'trawelltag');
            
            break;
        default : redirect(base_url());
    }
}
// ---------------------------------------------------------- Load Library END
/**
 * * Balu A
 * Get unserialized
 */
function unserialized_data($data, $data_key = false) {
    if (empty($data_key) == true || md5($data) == $data_key) {
        /* $data = base64_decode($data);
          if (empty($data) === false && is_string($data) == true) {
          $json_data = json_decode($data, true);
          if (valid_array($json_data) == true) {
          $data = $json_data;
          }
          } */
        $data = base64_decode($data);
        if ($data !== false) {
            $data = @unserialize($data);
        }
    } else {
        $data = false;
    }
    return $data;
}
/**
 * * Balu A
 * Serialized
 */
function serialized_data($data) {
    return base64_encode(serialize($data));
    /* if (is_array($data)) {
      $data = json_encode($data);
      }
      return base64_encode($data); */
}
/**
 * Check if multiple or force to be multiple
 * @param array $data
 */
function force_multple_data_format($data) {
    $mul_data = array();
    if (is_object($data) == true) {
        if (isset($data->{0}) == false) {
            $mul_data->{0} = $data;
        } else {
            $mul_data = $data;
        }
    } elseif (is_array($data) == true) {
        if (isset($data[0]) == false) {
            $mul_data[0] = $data;
        } else {
            $mul_data = $data;
        }
    }
    return $mul_data;
}
/**
 * show label based on balance request status
 * @param string $status
 */
function balance_status_label($status) {
    switch ($status) {
        case 'PENDING': $status_label = 'label-info';
            break;
        case 'ACCEPTED': $status_label = 'label-success';
            break;
        case 'REJECTED': $status_label = 'label-danger';
            break;
    }
    return $status_label;
}
/**
 * show label based on balance request status
 * @param string $status
 */
function booking_status_label($status) {
    switch ($status) {
        case 'BOOKING_PENDING':
        case 'BOOKING_HOLD':
             $status_label = 'label label-info';
            break;
        case 'BOOKING_CONFIRMED':
            $status_label = 'label label-success';
            break;
        case 'BOOKING_ERROR' :
        case 'BOOKING_INCOMPLETE' :
        case 'BOOKING_FAILED' :
        case 'BOOKING_CANCELLED':
            $status_label = 'label label-danger';
            break;
        case 'accepted':
            $status_label = 'label label-success';
            break;    
        case 'pending':
            $status_label = 'label label-info';
            break;    
        case 'declined':
            $status_label = 'label label-danger';
            break;    
        default : 
            $status_label = 'label label-primary';
    }
    return $status_label;
}
/**
 * Refund Status Label
 * @param $status
 */
function refund_status_label($status) {
    switch ($status) {
        case 'INPROGRESS':
            $status_label = 'label label-info';
            break;
        case 'PROCESSED':
            $status_label = 'label label-success';
            break;
        case 'REJECTED':
            $status_label = 'label label-danger';
            break;
            break;
        default : $status_label = 'label label-primary';
    }
    return $status_label;
}
/**
 * * Balu A
 * check if the current user has privilege to view the web page
 * @param $privilege_key unique key which identifies privilege to access each page
 * @param $auto_redirect boolean used to prevent auto redirection while checking web page access privilege
 */
function web_page_access_privilege($privilege_key, $auto_redirect = true) {
    return true;
}
/**
 * * Balu A
 */
function get_default_image_loader() {
    return '<div class="data-utility-loader" style="display:none">
			Please Wait <img src="' . $GLOBALS['CI']->template->template_images('tiny_loader_v1.gif') . '" class="img-responsive center-block"></img>
		</div>';
}
/**
 * * Balu A
 * check if the user is logged in or not
 */
function is_logged_in_user() {

    // debug($GLOBALS['CI']->entity_user_id); exit;

    if (isset($GLOBALS['CI']->entity_user_id) == true and intval($GLOBALS['CI']->entity_user_id) > 0) {
        return true;
    } else {
        return false;
    }
}
function is_app_user() {
    if (isset($GLOBALS['CI']->entity_user_type) == true and in_array(intval($GLOBALS['CI']->entity_user_type), array(B2C_USER, B2B_USER))) {
        return true;
    } else {
        return false;
    }
}
/**
 * * Balu A
 * return domain name
 */
function domain_name() {
    return $GLOBALS['CI']->entity_domain_name;
}
function domain_gst_number(){
    return $GLOBALS['CI']->entity_gst_number;
}
/**
 * check user is Provab Admin or Domain Admin
 * Balu A (22-05-2015) - 22-05-2015
 */
function is_domain_user() {
    //check this based on domain id when the user login
    $domain_id = $GLOBALS['CI']->entity_domain_id;
    if (intval($domain_id) > 0) {
        return true;
    } else {
        return false;
    }
}
/**
 * Get Domain Key
 * Balu A (22-05-2015) - 22-05-2015
 */
function get_domain_auth_id() {
    $CI = &get_instance();
    $domain_auth_id = $CI->session->userdata(DOMAIN_AUTH_ID);
    if (intval($domain_auth_id) > 0) {
        return intval($domain_auth_id);
    } else {
        return 0;
    }
}
function active_sms_checkpoint($name) {
    $status = $GLOBALS['CI']->user_model->sms_checkpoint($name);
    if ($status == ACTIVE) {
        return true;
    } else {
        return false;
    }
}
function current_application_balance() {
    $GLOBALS['CI']->load->library('Api_Interface');
    $resp = json_decode($GLOBALS['CI']->api_interface->rest_service('domain_balance'), true);
    $balance['balance'] = @$resp['balance'];
    $balance['credit_limit'] = @$resp['credit_limit'];
    $balance['due_amount'] = @$resp['due_amount'];
    $balance['face_value'] = @$resp['currency'] . ' ' . @$resp['balance'];
    return $balance;
}
/**
 * Balu A
 * Setting Domain(Travelomatix) currency as API Data Currency
 * @return string
 */
function get_api_data_currency() {
    $GLOBALS['CI']->load->library('Api_Interface');
    $domain_currency = $GLOBALS['CI']->session->userdata('domain_currency');
    // echo 'hesssrre'.$domain_currency;exit;
    if (empty($domain_currency) == true) {
        $response = json_decode($GLOBALS['CI']->api_interface->rest_service('domain_currency'), true);
        // debug($response);exit;
        if (valid_array($response) == true && $response['status'] == ACTIVE && empty($response['currency']) == false) {
            $domain_currency = trim($response['currency']);
        } else {
            // echo 'Invalid API Currency';
            // exit;
            $domain_currency = 'INR';
        }
        $GLOBALS['CI']->session->set_userdata(array('domain_currency' => $domain_currency));
    }
    return $domain_currency;
}
function get_tm_api_data_currency()
{
	$GLOBALS['CI']->load->library('Api_Interface');
	$domain_currency = $GLOBALS['CI']->session->userdata('tm_domain_currency');
	if(empty($domain_currency) == true) {
		$response = json_decode($GLOBALS['CI']->api_interface->rest_service('domain_currency'), true);
		if(valid_array($response) == true && $response['status'] == ACTIVE && empty($response['currency']) == false) {
			$domain_currency = trim($response['currency']);
		} else {
			$domain_currency = "INR";
			//echo 'Invalid API Currency';
			//exit;
			//$domain_currency = 'INR';
		}
		$GLOBALS['CI']->session->set_userdata(array('tm_domain_currency' => $domain_currency));
	} 
	return $domain_currency;
}
/**
 * Admin Base Currency
 * @return string
 */
function admin_base_currency() {
    return $GLOBALS['CI']->db_cache_api->get_admin_base_currency();
}
/**
 * Admin Base Currency
 * @return string
 */
function agent_base_currency() {
    return $GLOBALS['CI']->db_cache_api->get_agent_base_currency();
}
function agent_current_application_balance() {
    // debug($GLOBALS['CI']->db_cache_api->get_current_balance());exit;
    return $GLOBALS['CI']->db_cache_api->get_current_balance();
}
/**
 * Get Domain Config Key
 * Balu A (26-05-2015) - 26-05-2015
 */
function get_domain_key() {
    $CI = &get_instance();
    $domain_key = trim($CI->session->userdata(DOMAIN_KEY));
    if (empty($domain_key) == false) {
        return base64_decode(trim($domain_key));
    } else {
        return '';
    }
}
function user_type_access_control($user_type_heap) {
    return check_user_type($GLOBALS['CI']->entity_user_type, $user_type_heap);
}
function get_status_strip($status) {
    $strip = '';
    switch ($status) {
        case ACTIVE : $strip = 'alert-success';
            break;
        case INACTIVE : $strip = 'alert-danger';
            break;
    }
    return $strip;
}
function get_target_status_strip($target_limit, $user_target) {
    //badge red-shade-3
}
/**
 *
 */
function check_user_type($user_type = '', $user_type_heap = '') {
    if (intval($user_type) > 0) {
        //get user type related list
        $heap_data = array();
        if (is_string($user_type_heap) == true) {
            if (in_array($user_type, array_keys(get_enum_list($user_type_heap)))) {
                return true;
            }
        } elseif (valid_array($user_type_heap) == true) {
            foreach ($user_type_heap as $k => $v) {
                if (is_string($v)) {
                    if (in_array($user_type, array_keys(get_enum_list($v)))) {
                        return true;
                    }
                }
            }
        }
    }
}
function refresh() {
    redirect(uri_string() . '?' . (empty($_SERVER['REDIRECT_QUERY_STRING']) == false ? $_SERVER['REDIRECT_QUERY_STRING'] : $_SERVER['QUERY_STRING']), 'Refresh');
}
/**
 * to check if the request from client is ajax or not
 */
function is_ajax() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) == true && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $status = true;
    } else {
        $status = false;
    }
    return $status;
}
function check_default_edit_privilege($user_id = 0) {
    if ($user_id == $GLOBALS['CI']->entity_user_id) {
        return true;
    } else {
        return false;
    }
}
function super_privilege() {
    return false;
}
/**
 * compress output before sending to browser
 * @param unknown_type $input
 */
function get_compressed_output($data) {
    ini_set('memory_limit', '-1');
    $search = array(
        '/\>[^\S ]+/s',
        '/[^\S ]+\</s',
        '#(?://)?<!\[CDATA\[(.*?)(?://)?\]\]>#s' //leave CDATA alone
    );
    $replace = array(
        '>',
        '<',
        "//&lt;![CDATA[\n" . '\1' . "\n//]]>"
    );
    return preg_replace($search, $replace, $data);
}
/**
 * decode json and returns valid json
 *
 * @param object  $json  Json which has to be decoded
 * @param boolean $assoc boolean which indicates if array should be returned
 */
if (!function_exists('json_validate')) {
    function json_validate($json, $assoc = TRUE) {
        //decode the JSON data
        $result = json_decode($json, $assoc);
        // switch and check possible JSON errors
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid
                break;
            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Underflow or the modes mismatch.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            // only PHP 5.3+
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }
        if ($error !== '') {
            // throw the Exception or exit
            $status = false;
            $message = $error;
            $data = '';
            //redirect('general/index');
        } else {
            $status = true;
            $message = '';
            $data = $result;
        }
        return array('status' => $status, 'message' => $message, 'data' => $data);
    }
}
/**
 * get message returns a message with appropriate html
 *
 * @param int $message_type message boxes to be displayed
 * @param str $message to be displayed inside the box
 */
function get_message($message = "UL003", $message_type = ERROR_MESSAGE, $button_required = false, $override_app_msg = false) {
    switch ($message_type) {
        case SUCCESS_MESSAGE:
            $alert_class = 'alert-success';
            break;
        case WARNING_MESSAGE:
            $alert_class = 'alert-warning';
            break;
        case INFO_MESSAGE:
            $alert_class = 'alert-info';
            break;
         case FAILURE_MESSAGE:
            $alert_class = 'alert-danger';
            break;  
        default:
            $alert_class = 'alert-danger';
    }
    $content = '<div class="alert ' . $alert_class . ' clearfix" role="alert">';
    if ($button_required) {
        $content .= '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>';
    }
    if ($override_app_msg == false) {
        $content .= $GLOBALS['CI']->lang->line($message) . '</div>';
    } else {
        $content .= $message . '</div>';
    }
    return $content;
}
/**
 *
 * @param string $message
 * @param string $override_app_msg
 */
function extract_message($message = "UL003", $override_app_msg = false) {
    $content = '';
    if (empty($override_app_msg) == true) {
        $content .= $GLOBALS ['CI']->lang->line($message);
    } else {
        $content .= $message;
    }
    if (empty($content) == true) {
        $content = "Remote IO Error";
    }
    return $content;
}
function get_toastr_index($message_type) {
    switch ($message_type) {
        case SUCCESS_MESSAGE :
            $toastr = 'success';
            break;
        case WARNING_MESSAGE :
            $toastr = 'warning';
            break;
        case INFO_MESSAGE :
            $toastr = 'info';
            break;
        default :
            $toastr = 'error';
    }
    return $toastr;
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function get_label($msg_id) {
    $msg = $GLOBALS['CI']->lang->line('FL00' . $msg_id);
    return (empty($msg) == false ? $msg : $msg_id);
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function get_placeholder($msg_id) {
    return $GLOBALS['CI']->lang->line('FL000' . $msg_id);
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function get_help_text($msg_id) {
    return $GLOBALS['CI']->lang->line('FL0000' . $msg_id);
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function generate_help_text($ip_help_text) {
    return ' data-container="body" data-toggle="popover" data-original-title="' . get_utility_message('UL004') . '" data-placement="bottom" data-trigger="hover focus" data-content="' . $ip_help_text . '"';
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function provab_help_text($help_text) {
    return '<span data-toggle="popover" data-title="' . get_utility_message('UL004') . '" data-placement="bottom" data-html=true  class="glyphicon glyphicon-question-sign handCursor provabHelpText" data-content="' . $help_text . '">';
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function get_utility_message($msg_id) {
    return $GLOBALS['CI']->lang->line($msg_id);
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function get_app_message($msg_id) {
    return $GLOBALS['CI']->lang->line($msg_id);
}
/**
 * returns string containing message for message id
 * @param $msg_id index of message whose message has to be fetched
 */
function get_legend($msg_id) {
    return $GLOBALS['CI']->lang->line($msg_id);
}
/**
 * * returns string containing options
 * @param $option_list    list of option values
 * @param $override_order should the order be changed by sorting values
 */
function generate_options($option_list = array(), $default_value = false, $override_order = false) {
    // debug($option_list);exit;
    $options = '';
    if (valid_array($option_list) == true) {
        $array_values = array_values($option_list);
        if (valid_array($array_values[0]) == true) {
            if ($default_value) {
                foreach ($option_list as $k => $v) {
                    if (in_array($v['k'], $default_value)) {
                        $selected = ' selected="selected" ';
                    } else {
                        $selected = '';
                    }
                    $options .= '<option value="' . $v['k'] . '" ' . $selected . '>' . $v['v'] . '</option>';
                }
            } else {
                foreach ($option_list as $k => $v) {
                    $options .= '<option value="' . $v['k'] . '">' . $v['v'] . '</option>';
                }
            }
        } else {
            if ($default_value) {
                foreach ($option_list as $k => $v) {
                    if (in_array($k, $default_value)) {
                      
                        $selected = ' selected="selected" ';
                    } else {
                        $selected = '';
                    }
                    $options .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
                }
            } else {
                foreach ($option_list as $k => $v) {
                    $options .= '<option value="' . $k . '">' . $v . '</option>';
                }
            }
        }
    } else {
        $options .= '<option value="INVALIDIP">---</option>';
    }
    return $options;
}
/**
 * returns enumerated list of values
 * @param $enum name of enumeration datatype
 */
function get_enum_list($enum, $default_value = -1) {
    if ($GLOBALS['CI']->load->is_loaded('enumeration') == false) {
        $GLOBALS['CI']->load->library('enumeration');
    }
    $enumeration_list = $GLOBALS['CI']->enumeration->getEnumerationList($enum);
    //debug($enumeration_list[$default_value]); exit;
    if (intval($default_value) > -1) 
    {
        return (isset($enumeration_list[$default_value]) ? $enumeration_list[$default_value] : '');
    } else {
        return $enumeration_list;
    }
}
/**
 * returns enumerated list of values
 * @param $enum name of enumeration datatype
 */
function provab_solid_regexp($data_key) {
    if ($GLOBALS['CI']->load->is_loaded('provab_solid') == false) {
        $GLOBALS['CI']->load->library('provab_solid');
    }
    return $GLOBALS['CI']->provab_solid->provab_solid_regexp($data_key);
}
/**
 * returns image
 * @param unknown_type $image_name
 */
function get_profile_image($image_name = '') {
    return (empty($image_name) ? 'face.png' : $image_name);
}
function debug($ele = array()) {
    ini_set('memory_limit', -1);
    echo '<pre>';
    print_r($ele);
    $data=debug_backtrace();
    echo '<br>File Name => '.$data[0]['file'];
    echo '<br>Line No => '.$data[0]['line'];
}
/**
 * Generates Numeric Drop-Down with
 * the given size
 */
function numeric_dropdown($size = array('size' => 10)) {
  $options = array();
    if (valid_array($size)) {
        $arr_size = $size ['size'];
        if (isset($size['divider']) == true && $size['divider'] > 0) {
            $divider = floatval($size['divider']);
        } else {
            $divider = 1;
        }
        for ($i = 1; $i <= $size ['size']; $i ++) {
            $options [$i] = array(
                'k' => ($i / $divider),
                'v' => ($i / $divider)
            );
        }
    }
    return $options;
}
/**
 * Balu A
 * Set Insert meassge
 * @param $msg
 * @param $type
 * @param $attributes
 */
function set_insert_message($msg = 'UL0014', $type = SUCCESS_MESSAGE, $attributes = array()) {
    $error_message = array('message' => $msg, 'type' => $type);
    if (valid_array($attributes)) {
        $error_message = array_merge($error_message, $attributes);
    }
    $GLOBALS['CI']->session->set_flashdata($error_message);
}
/**
 * Balu A
 * Set Update meassge
 * @param $msg
 * @param $type
 * @param $attributes
 */
function set_update_message($msg = 'UL0013', $type = SUCCESS_MESSAGE, $attributes = array()) {
    $error_message = array('message' => $msg, 'type' => $type);
    if (valid_array($attributes)) {
        $error_message = array_merge($error_message, $attributes);
    }
    
    $GLOBALS['CI']->session->set_flashdata($error_message);
}
/**
 * Balu A
 * Set Error meassge
 * @param $msg
 * @param $type
 * @param $attributes
 */
function set_error_message($msg = 'UL0049', $type = ERROR_MESSAGE, $attributes = array()) {
    $error_message = array('message' => $msg, 'type' => $type);
    if (valid_array($attributes)) {
        $error_message = array_merge($error_message, $attributes);
    }
    $GLOBALS['CI']->session->set_flashdata($error_message);
}
function get_arrangement_icon($arrangement = '') {
    $arrangement_icon = '';
    switch ($arrangement) {
        /* Hotel */
        case META_ACCOMODATION_COURSE: $arrangement_icon = 'material-icons';
            break;
        /* Transfers */
        case META_TRANSFERS_COURSE : $arrangement_icon = 'material-icons';
            break;
        /* Airline */
        case META_AIRLINE_COURSE: $arrangement_icon = 'material-icons';
            break;
        /* Transfers */
        case META_TRANSFERV1_COURSE: $arrangement_icon = 'material-icons';
            break;
       /* Sightseeing */
        case META_SIGHTSEEING_COURSE: $arrangement_icon = 'material-icons';
            break;
        /* Car */        
        case META_CAR_COURSE: $arrangement_icon = 'material-icons';
            break;
        /* Meals */
        case 'VHCID1420973863' : $arrangement_icon = 'material-icons';
            break;
        /* Activities */
        case 'VHCID1420973899': $arrangement_icon = 'material-icons';
            break;
        /* Guide */
        case 'VHCID1420973949' : $arrangement_icon = 'material-icons';
            break;
        /* Visa */
        case 'VHCID1420973967' : $arrangement_icon = 'material-icons';
            break;
        /* Insurance */
        case 'VHCID1420973976' : $arrangement_icon = 'material-icons';
            break;
        /* Misc */
        case 'VHCID1420973998' : $arrangement_icon = 'material-icons';
            break;
        /* HANDOVER */
        case 'VHCID1430114195' : $arrangement_icon = 'material-icons';
            break;
        /* BUS */
        case META_BUS_COURSE : $arrangement_icon = 'material-icons';
            break;
        /* Package */
        case META_PACKAGE_COURSE : $arrangement_icon = 'material-icons';
            break;
        default : $arrangement_icon = 'material-icons';
            break;
    }
    return $arrangement_icon;
}

/**
 * Get Material Icon name for arrangement
 * @param string $arrangement
 * @return string Material Icon name
 */
function get_arrangement_material_icon($arrangement = '') {
    $icon_name = '';
    switch ($arrangement) {
        /* Hotel */
        case META_ACCOMODATION_COURSE: $icon_name = 'bed';
            break;
        /* Transfers */
        case META_TRANSFERS_COURSE : $icon_name = 'directions_car';
            break;
        /* Airline */
        case META_AIRLINE_COURSE: $icon_name = 'flight';
            break;
        /* Transfers */
        case META_TRANSFERV1_COURSE: $icon_name = 'local_taxi';
            break;
       /* Sightseeing */
        case META_SIGHTSEEING_COURSE: $icon_name = 'visibility';
            break;
        /* Car */        
        case META_CAR_COURSE: $icon_name = 'directions_car';
            break;
        /* Meals */
        case 'VHCID1420973863' : $icon_name = 'restaurant';
            break;
        /* Activities */
        case 'VHCID1420973899': $icon_name = 'camera_alt';
            break;
        /* Guide */
        case 'VHCID1420973949' : $icon_name = 'person';
            break;
        /* Visa */
        case 'VHCID1420973967' : $icon_name = 'credit_card';
            break;
        /* Insurance */
        case 'VHCID1420973976' : $icon_name = 'security';
            break;
        /* Misc */
        case 'VHCID1420973998' : $icon_name = 'shuffle';
            break;
        /* HANDOVER */
        case 'VHCID1430114195' : $icon_name = 'card_giftcard';
            break;
        /* BUS */
        case META_BUS_COURSE : $icon_name = 'directions_bus';
            break;
        /* Package */
        case META_PACKAGE_COURSE : $icon_name = 'work';
            break;
        default : $icon_name = 'apps';
            break;
    }
    return $icon_name;
}

/**
 * Get TMX Icon ID for arrangement
 * @param string $arrangement
 * @return string TMX Icon ID
 */
function get_arrangement_tmx_icon($arrangement = '') {
    $icon_id = '';
    switch ($arrangement) {
        /* Hotel */
        case META_ACCOMODATION_COURSE: $icon_id = 'tmx-icon-hotel';
            break;
        /* Transfers */
        case META_TRANSFERS_COURSE : $icon_id = 'tmx-icon-transfers';
            break;
        /* Airline */
        case META_AIRLINE_COURSE: $icon_id = 'tmx-icon-flight';
            break;
        /* Transfers */
        case META_TRANSFERV1_COURSE: $icon_id = 'tmx-icon-transfers';
            break;
        case META_TRANSFER_COURSE: $icon_id = 'tmx-icon-transfers';
            break;
       /* Sightseeing */
        case META_SIGHTSEEING_COURSE: $icon_id = 'tmx-icon-sighseeing';
            break;
        /* Car */        
        case META_CAR_COURSE: $icon_id = 'tmx-icon-car';
            break;
        /* BUS */
        case META_BUS_COURSE : $icon_id = 'tmx-icon-transfers';
            break;
        /* Package */
        case META_PACKAGE_COURSE : $icon_id = 'tmx-icon-tours';
            break;
        default : $icon_id = 'tmx-icon-flight';
            break;
    }
    return $icon_id;
}

function get_arrangement_color($arrangement = '') {
    $arrangement_color = '';
    switch ($arrangement) {
        /* Hotel */
        case META_ACCOMODATION_COURSE: $arrangement_color = 'hotel-l-bg';
            break;
        /* Transfers */
        case META_TRANSFERS_COURSE : $arrangement_icon = '';
            break;
        /* Airline */
        case META_AIRLINE_COURSE: $arrangement_color = 'flight-l-bg';
            break;
        /* BUS */
        case META_BUS_COURSE : $arrangement_color = 'bus-l-bg';
            break;
        /* Package */
        case META_PACKAGE_COURSE : $arrangement_color = 'package-l-bg';
            break;
        default : $arrangement_color = '';
            break;
    }
    return $arrangement_color;
}
/**
 *
 * @param unknown_type $name
 */
function module_name_to_id($name) {
    $id = '';
    switch ($name) {
        case 'flight': $id = META_AIRLINE_COURSE;
            break;
        case 'hotel': $id = META_ACCOMODATION_COURSE;
            break;
        case 'bus': $id = META_BUS_COURSE;
            break;
        case 'package': $id = META_PACKAGE_COURSE;
            break;
    }
    return $id;
}
function module_spirit_img($module_name) {
    $img = '';
    switch ($module_name) {
        case 'flight': $img = 'flihtex';
            break;
        case 'hotel': $img = 'htlex';
            break;
        case 'bus': $img = 'busex';
            break;
        case 'package': $img = 'holidytex';
            break;
    }
    return $img;
}
/**
 * Show lazy loading gif to user
 */
function get_lazy_loading_icon() {
    return '<img src="' . $GLOBALS['CI']->template->template_images('loader.gif') . '" class="center-align-image lazy-loader-image">';
}
/**
 * Show lazy loading gif to user
 */
function get_circle_ball_loading_icon() {
    return '<img src="' . $GLOBALS['CI']->template->template_images('circle-ball-ajax-loader.gif') . '" class="">';
}
/**
 * generate color code for string
 * @param $string
 */
function string_color_code($string) {
    $string = md5($string);
    $string = array(
        "R" => hexdec(substr($string, 0, 2)),
        "G" => hexdec(substr($string, 2, 2)),
        "B" => hexdec(substr($string, 4, 2))
    );
    return 'rgb(' . implode(',', $string) . ')';
}
function get_current_url() {
    $url = current_base_url();
    return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
}
function current_base_url() {
    $CI = & get_instance();
    return $CI->config->site_url($CI->uri->uri_string());
}
/**
 * default form should be visible if data is posted or if eid is present or if op is defined as add
 */
function form_visible_operation() {
    if (valid_array($_POST) == true || isset($_GET['eid']) == true || isset($_GET['origin_id']) == true || (isset($_GET['op']) == true && $_GET['op'] == 'add')) {
        return true;
    } else {
        return false;
    }
}
function login_status($time) {
    //return ((empty($time) == false and strtotime($time) == 0) ? '<i class="fa fa-circle text-success"></i>' : '<i class="fa fa-circle text-warning">'.$time.'</i>');
    return ($time === '0' ? '<i class="fa fa-circle text-success"></i>' : '<i class="fa fa-circle text-warning"></i>');
}
function last_login($time) {
    return (empty($time) == false ? get_duration_label(time() - strtotime($time)) . ' Ago(' . app_friendly_absolute_date($time) . ')' : 'Not Seen Online');
}
function member_since($time) {
    return (empty($time) == false ? '(Since:' . date('M, Y', strtotime($time)) . ')' : '');
}
/**
 * Balu A
 * Round off the number to upper value
 * @param unknown_type $number
 * @return string
 */
function roundoff_number($number) {
    if(intval($number) > 0) {
        // return ceil($number);
       return number_format($number, 2, '.', '');
    } else if(intval($number) <= 0) {
        return number_format($number, 2, '.', '');
        return floatval($number);
    }
}
function data_to_log_file($data) {
    if (is_array($data)) {
        $data = json_encode($data);
    }
    error_log($data, 3, '/opt/lampp/logs/php_error_log');
}
/**
 * Render TMX Custom Icon
 * @param string $icon_id - The symbol ID from tmx-icon.svg (e.g., 'tmx-icon-hotel', 'tmx-icon-holiday')
 * @param string $class - Additional CSS classes for styling and size control
 * @return string - HTML for SVG icon
 */
function render_tmx_icon($icon_id, $class = '') {
  $icon_url = $GLOBALS['CI']->template->template_images('icons/tmx-icon.svg');
  
  // Always include base class
  $classes = array('tmx-icon');
  
  // Add additional class if provided
  if (!empty($class)) {
    $class = trim($class);
    if ($class !== '') {
      $classes[] = $class;
    }
  }
  
  $class_attr = implode(' ', $classes);
  
  return '<svg class="' . $class_attr . '"><use href="' . $icon_url . '#' . $icon_id . '"></use></svg>';
}
/**
 * Balu A
 * Merges the Numeric Array Values
 */
function array_merge_numeric_values($data) {
    $merged_array = array();
    foreach ($data as $array) {
        foreach ($array as $key => $value) {
            if (!is_numeric($value)) {
                $merged_array[$key] = $value;
                continue;
            }
            if (!isset($merged_array[$key])) {
                $merged_array[$key] = $value;
            } else {
                $merged_array[$key] += $value;
            }
        }
    }
    return $merged_array;
}
/**
 * Groups the clumns
 * @param $input
 * @param $column_key
 * @param $index_key
 */
function group_array_column(array $input, $column_key, $index_key = null) {
    $result = array();
    foreach ($input as $k => $v) {
        $result[$k] = $v[$column_key];
    }
    return $result;
}
/*
  Sachin
  Format pdf table data
 */
function format_pdf_data($data = array(), $col = array()) 
{
    $pdf_data = array();
    $count = 0;
    if (valid_array($col)) {
        foreach ($col as $v) {
            if (is_array($v)) {
                $v = $v['title'];
            }
            $pdf_data['head'][] = $v;
        }
        foreach ($data as $v) {
            foreach ($col as $k => $cv) {
                if (is_array($cv)) {
                    $val = '';
                    $sep = isset($cv['sep']) ? $cv['sep'] : ' ';
                    foreach ($cv['cols'] as $cv1) {
                        $val .= $v[$cv1] . $sep;
                    }
                    $v[$k] = rtrim($val, $sep);
                }
                $pdf_data['body'][$count][] = $v[$k];
            }
            $count++;
        }
    } else {
        $pdf_data['body'] = $data;
    }
    return $pdf_data;
}
/**
 * compare balance with amount and generate low balance popup if balance is low
 * 
 * @param number $amount
 *          amount to be compared
 * @param string $currency          
 */
function generate_low_balance_popup($amount, $currency = COURSE_LIST_DEFAULT_CURRENCY_VALUE) {
    $ci = & get_instance ();
    
    $balance_status = $ci->domain_management_model->verify_current_balance ( $amount, $currency );
    if (empty ( $balance_status ) == true) {
        $balance = agent_current_application_balance();
        return ' <span class="balance_check hide">1</span><div id="balance-low-modal" class="modal fade" tabindex="-1" role="dialog">
       
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Low Balance</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning" role="alert">
            Your Balance Is Low ('.$balance['value'].').<a target="_blank" href="'.base_url().'index.php/management/b2b_balance_manager" class="alert-link">Click Here To Deposit Now.</a>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal --><script type="text/javascript">
    $(window).load(function(){
        $("#balance-low-modal").modal("show");
    });
</script>';
    }
}
/*AES Encryption method */
function provab_encrypt($string){
    #echo $string.'<br/>';
    $CI = & get_instance ();
    $output = false;
    $encrypt_method = "AES-256-CBC";    
    $enc_password =trim(PROVAB_ENC_KEY);// stored in config file with encryption method 
    $md5_sec_key = trim(PROVAB_MD5_SECRET);
    $decrypt_password = $CI->db->query("SELECT AES_DECRYPT($enc_password,SHA2('".$md5_sec_key."',512)) AS decrypt_data");
    $db_data = $decrypt_password->row();
     $secret_iv = trim(PROVAB_SECRET_IV);
    $secret_key = trim($db_data->decrypt_data); 
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    
    return $output;
}
/*AES Decryption method*/
function provab_decrypt($string){
    $CI = & get_instance ();
    $output = false;
    $encrypt_method = "AES-256-CBC";   
    $enc_password =trim(PROVAB_ENC_KEY);// stored in config file with encryption method 
    $md5_sec_key = trim(PROVAB_MD5_SECRET);
    $decrypt_password = $CI->db->query("SELECT AES_DECRYPT($enc_password,SHA2('".$md5_sec_key."',512)) AS decrypt_data");
    $db_data = $decrypt_password->row();
    $secret_key = trim($db_data->decrypt_data); 
    $secret_iv = trim(PROVAB_SECRET_IV);
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);       
    return $output;
}
//checking the image with MIME
function check_mime_image_type( $tmpname ) {
    /*
    Image uploading only
    */
    $imageInfo = getimagesize( $tmpname );
    // debug($imageInfo);exit;
    if ( $imageInfo['mime'] == ( "image/png" ) ||
    $imageInfo['mime'] == ( "image/jpeg" ) || 
    $imageInfo['mime'] == ( "image/gif" ) || 
    $imageInfo['mime'] == ( "image/jpg" )  ) {
        return TRUE;
    }else{
        return FALSE;
    }
}
function send_email($app_reference, $booking_source, $booking_status, $module){
        if (empty ( $app_reference ) == false) {
            $GLOBALS['CI']->load->library ( 'provab_mailer' );
            $GLOBALS['CI']->load->library ( 'booking_data_formatter' );
            if($module == 'flight'){
                $booking_details = $GLOBALS['CI']->flight_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
            }
            else if($module == 'hotel'){
                $booking_details = $GLOBALS['CI']->hotel_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
            }
            else if($module == 'car'){
                $booking_details = $GLOBALS['CI']->car_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
            }
            else if($module == 'activities'){
                $booking_details = $GLOBALS['CI']->sightseeing_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
            }
            else if($module == 'transfers'){
                $booking_details = $GLOBALS['CI']->transferv1_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
            }
            // debug($booking_details);exit;
            //$booking_details['data']['booking_customer_details'] = $GLOBALS['CI']->booking_data_formatter->add_pax_details($booking_details['data']['booking_customer_details']);
            //$assembled_booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');  
            //debug($booking_details);  die();
            $email = $booking_details['data']['booking_details'][0]['email'];
            //$email = 'elamathisidhu@gmail.com';
            if ($booking_details ['status'] == SUCCESS_STATUS) {
                if($module == 'flight'){
                    // Assemble Booking Data
                    $assembled_booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data ( $booking_details, 'b2c' );
                    
                     
                    // debug($page_data);exit;
                    $page_data ['data'] = $assembled_booking_details ['data'];
                    if(isset($assembled_booking_details['data']['booking_details'][0])){
                        //get agent address & logo for b2b voucher
                        
                        $domain_address = $GLOBALS['CI']->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
                        // debug($domain_address);exit;
                        $page_data['data']['address'] =$domain_address['data'][0]['address'];
                        $page_data['data']['phone'] =$domain_address['data'][0]['phone'];
                        $page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
                        $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                    }
                    // debug($page_data);exit;
                    $mail_template = $GLOBALS['CI']->template->isolated_view ( 'voucher/flight_voucher', $page_data );
                    // debug($mail_template);exit;
                    $subject = 'Flight Details';
                }
                else if($module == 'hotel'){
                    // Assemble Booking Data
                    $assembled_booking_details = $GLOBALS['CI']->booking_data_formatter->format_hotel_booking_data ( $booking_details, 'b2c' );
                    //debug($assembled_booking_details);exit;
                    $page_data ['data'] = $assembled_booking_details ['data'];
                    $mail_template = $GLOBALS['CI']->template->isolated_view ( 'voucher/hotel_voucher', $page_data );
                    $subject = 'Hotel Details';
                }
                else if($module == 'car'){
                    // Assemble Booking Data
                    $assembled_booking_details = $GLOBALS['CI']->booking_data_formatter->format_car_booking_datas ( $booking_details, 'b2c' );
                    //debug($assembled_booking_details);exit;
                    $page_data ['data'] = $assembled_booking_details ['data'];
                    $mail_template = $GLOBALS['CI']->template->isolated_view ( 'voucher/car_voucher', $page_data );
                    $subject = 'Car Details';
                }else if($module=='activities'){
                    // Assemble Booking Data
                    $assembled_booking_details = $GLOBALS['CI']->booking_data_formatter->format_sightseeing_booking_data ( $booking_details, 'b2c' );
                    //debug($assembled_booking_details);exit;
                    $page_data ['data'] = $assembled_booking_details ['data'];
                    $domain_address = $GLOBALS['CI']->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
                        // debug($domain_address);exit;
                        $page_data['data']['address'] =$domain_address['data'][0]['address'];
                        $page_data['data']['phone'] =$domain_address['data'][0]['phone'];
                        $page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
                        $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                    $mail_template = $GLOBALS['CI']->template->isolated_view ( 'voucher/sightseeing_voucher', $page_data );
                    $subject = 'Activities Details';
                }else if($module=='transfers'){
                    // Assemble Booking Data
                    $assembled_booking_details = $GLOBALS['CI']->booking_data_formatter->format_transferv1_booking_data ( $booking_details, 'b2c' );             
                    //debug($assembled_booking_details);exit;
                    $page_data ['data'] = $assembled_booking_details ['data'];
                    $domain_address = $GLOBALS['CI']->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
                        // debug($domain_address);exit;
                        $page_data['data']['address'] =$domain_address['data'][0]['address'];
                        $page_data['data']['phone'] =$domain_address['data'][0]['phone'];
                        $page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
                        $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                    $mail_template = $GLOBALS['CI']->template->isolated_view ( 'voucher/transferv1_voucher', $page_data );
                    $subject = 'Transfers Details';
                }
                $status = $GLOBALS['CI']->provab_mailer->send_mail ( $email, $subject, $mail_template, '' );
            }
        }
    }
function check_user_previlege($key) {
    
    $CI = & get_instance ();
    
    if ($CI->entity_user_type == ADMIN)
        return true;
        // debug($CI->active_user_previleges);exit;
    return in_array ( $key, $CI->active_user_previleges, true );
}
function domain_base_currency()
{
	return $GLOBALS['CI']->db_cache_api->get_domain_base_currency();
}
function getLowestKey($carry, $item) {
    if ($item['price'] < $carry || !$carry) {
        //$carry = $item['price'];
        $carry = $item;
        //return $item;
    }
    return $carry;
}
function get_city_name_byairport_code($airport_code)
{
	$cond=array(
			"airport_code" => $airport_code
	);
	$var = $GLOBALS['CI']->custom_db->single_table_records('flight_airport_list','airport_city,country,airport_name',$cond);
	//debug($var);exit;
	return @$var['data'][0];
}
function get_city_name_byairline_code($airline_code)
{
	$cond=array(
			"code" => $airline_code
	);
	$var = $GLOBALS['CI']->custom_db->single_table_records('airline_list','name',$cond);
	//debug($var);exit;
	return @$var['data'][0];
}
function log_custom($str){
    if(is_array($str)){$str = json_encode($str);}
    file_put_contents(FCPATH ."logs/logs_". date("Y-m-d") .".txt", date("H:i:s") . ' '. $str . PHP_EOL, FILE_APPEND);
}
function get_seat_layout($seg_seat_v){
    $columns = array();
    foreach($seg_seat_v as $row_k=>$row_v){
        foreach($row_v as $col_k=>$col_v){
            $column = preg_replace('/[^a-zA-Z]/', '', $col_v['SeatNumber']);
            $columns[$column] = array('Name'=>$column);
        }
    }
    if($columns){
        ksort($columns);
        $columns = array_values($columns);
    }
    return $columns;
}
function flight_segment_duration($departure_airport_code, $arrival_airport_code, $departure_datetime, $arrival_datetime)
	{
		// $departure_datetime = date('Y-m-d H:i:s', strtotime($departure_datetime));
		// $arrival_datetime = date('Y-m-d H:i:s', strtotime($arrival_datetime));
		//Get TimeZone of Departure and Arrival Airport
		$departure_timezone_offset = get_airport_timezone_offset($departure_airport_code, $departure_datetime); 
		$arrival_timezone_offset = get_airport_timezone_offset($arrival_airport_code, $arrival_datetime);
		$departureTimezone = new DateTimeZone($departure_timezone_offset);
		$arrivalTimezone = new DateTimeZone($arrival_timezone_offset);
		$departureDateTime = new DateTime($departure_datetime, $departureTimezone);
		$arrivalDateTime = new DateTime($arrival_datetime, $arrivalTimezone);
		$departureDateTime->setTimezone(new DateTimeZone('UTC'));
		$arrivalDateTime->setTimezone(new DateTimeZone('UTC'));
		
		$interval = $departureDateTime->diff($arrivalDateTime);
		// $segment_duration = ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
		$segment_duration = ($interval->d?$interval->d.'d ':'') . ($interval->h.'h ') . $interval->i.'m';
		return $segment_duration;
	}
	
	function get_airport_timezone_offset($airport_code,$journey_date)
	{
		//FIXME: cache the data
		$journey_month = date('m', strtotime($journey_date));
		$query = 'select FAL.airport_code,FAT.start_month,FAT.end_month,FAT.timezone_offset from flight_airport_list FAL
					join flight_airport_timezone_offset FAT on FAT.flight_airport_list_fk=FAL.origin
					where airport_code = "'.$airport_code.'" and (start_month<='.$journey_month.' or end_month>='.$journey_month.')
					order by 
					CASE
					WHEN start_month	= '.$journey_month.' THEN 1
		            WHEN end_month	= '.$journey_month.' THEN 2
					ELSE 3 END';
		$timezone_offset = $GLOBALS['CI']->db->query($query)->result_array();
		return $timezone_offset[0]['timezone_offset'];
	}
function currencyDataFormat($data=[]){
    $output = [];
    $from_currency = get_application_currency_preference();
    foreach ($data as $code => $value) {
        $temp = [];
        $currency = explode(' ',$value);

        if ($currency[0] != ''){

            $temp['symbol'] = $currency[0];

        }else{

            $temp['symbol'] = $currency[1];

        }
        if ($currency[2] != ''){
            $temp['flag'] = $currency[2];
        }
        else
        {
            $temp['flag'] = strtolower(substr($currency[1],0,2));
        }
        $currency_code = $currency[1];
        $temp['code'] = $currency_code;
        $cache_key = $from_currency . $currency_code;
        $currency_obj = new Currency([
            'module_type' => 'flight',
            'from' => $from_currency,
            'to' => $currency_code
        ]);
        $temp['rate'] = isset($currency_obj->conversion_cache[$cache_key]) ? $currency_obj->conversion_cache[$cache_key] : 1;
        $output[$code] = $temp;
    }
    return array_values($output);
}
function generateCurrencyOptions($price, $currencyList) {
    // Check if $currencyList is an array and not empty
    if (!is_array($currencyList) || count($currencyList) === 0) {
        return '';
    }

    $html = '
        <span class="mulitple_currency">
            <div class="dropdown">
                <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Multiple Currency
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu flag_images" aria-labelledby="dLabel">
    ';

    foreach ($currencyList as $item) {
        $convertedPrice = number_format($price * $item['rate'], 2);
        $html .= '
            <li>
                <span class="curncy_img sprte ' . htmlspecialchars($item['flag']) . '"></span>
                <span class="country_code">' . htmlspecialchars($item['code']) . '</span>
                <span>-</span>
                <span>' . htmlspecialchars($item['symbol']) . $convertedPrice . '</span>
            </li>
        ';
    }

    $html .= '
                </ul>
            </div>
        </span>
    ';

    return $html;
}
function formatStatusString($input) {
    $replaced = str_replace('_', ' is ', $input);
    $lowercase = strtolower($replaced);
    $words = explode(' ', $lowercase);
    foreach ($words as &$word) {
        if ($word !== 'is') {
            $word = ucfirst($word);
        }
    }
    unset($word);
    return implode(' ', $words);
}


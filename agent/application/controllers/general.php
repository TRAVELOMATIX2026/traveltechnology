<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-04-04
* @package: 
* @Description: 
* @version: 2.0
*/

class General extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->model('user_model');
		$this->load->model('Package_Model');
		$this->load->model('custom_db');
	}
	/**
	 * index page of application will be loaded here
	 */
	function index($default_view='')
	{
		if (is_logged_in_user()) {
			//$this->load->view('dashboard/reminder');
			redirect('menu/index');
		} else {
			//show login
			echo $this->template->view('general/login',$data = array());
		}
	}
	/**
	 * Set Search id in cookie
	 */
	private function save_search_cookie($module, $search_id)
	{
		$sparam = array();
		$sparam = $this->input->cookie('sparam', TRUE);
		if (empty($sparam) == false) {
			$sparam = unserialize($sparam);
		}
		$sparam[$module] = $search_id;
		$cookie = array(
			'name' => 'sparam',
			'value' => serialize($sparam),
			'expire' => '86500',
			'path' => PROJECT_COOKIE_PATH
		);
		$this->input->set_cookie($cookie);
	}
	/**
	 * Pre Search For Flight
	 */
	function pre_flight_search($search_id='')
	{
		//Global search Data
		$search_id = $this->save_pre_search(META_AIRLINE_COURSE);
		$this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
		//Analytics
		$this->load->model('flight_model');
		$search_params = $this->input->get();
		$this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
		redirect('flight/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	/**
	 * Pre Search For Hotel
	 */
	function pre_hotel_search($search_id='')
	{
		//debug($_GET);exit;
		//Global search Data
		$search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
		$this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);
		//Analytics
		$this->load->model('hotel_model');
		$search_params = $this->input->get();
		
		$this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);
		redirect('hotel/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	/**
	  * Pre Search for SightSeen
	  */
   function pre_sight_seen_search($search_id=''){
	    $search_id = $this->save_pre_search(META_SIGHTSEEING_COURSE);
	    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
	    //Analytics
	    $this->load->model('sightseeing_model');
	    $search_params = $this->input->get();
	    
	    $this->sightseeing_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
	    
	    redirect('sightseeing/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	/*
	  *Pre Transfer Search
	  */
	  function pre_transferv1_search($search_id=''){
	    $search_id = $this->save_pre_search(META_TRANSFERV1_COURSE);
	    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
	    //Analytics
	    $this->load->model('transferv1_model');
	    $search_params = $this->input->get();
	    
	    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
	    
	    redirect('transferv1/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	  }
  
	/**
	 * Pre Search For Bus
	 */
	function pre_bus_search($search_id='')
	{
		//Global search Data
		$search_id = $this->save_pre_search(META_BUS_COURSE);
		$this->save_search_cookie(META_BUS_COURSE, $search_id);
		//Analytics
		$this->load->model('bus_model');
		$search_params = $this->input->get();
		$this->bus_model->save_search_data($search_params, META_BUS_COURSE);
		redirect('bus/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	 /**
     * Pre Search For Car
     */
    function pre_car_search($search_id = '') {
        $search_params = $this->input->get();
        // debug($search_params);exit;
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);
        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
        redirect('car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
	/**
	 * Pre Search For Packages
	 */
	function pre_package_search($search_id='')
	{
		//Global search Data
		$search_id = $this->save_pre_search(META_PACKAGE_COURSE);
		redirect('tours/search'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}	

	/**
	 * Pre Search For New Transfer
	 */

	function pre_transfer_search($search_id = '') {
        //Global search Data
        //debug($this->session);exit;
        $search_id = $this->save_pre_search(META_TRANSFER_COURSE);
        $this->save_search_cookie(META_TRANSFER_COURSE, $search_id);

        //Analytics
        $this->load->model('transfer_model');
        $search_params = $this->input->get();

       $this->transfer_model->save_search_data($search_params, META_TRANSFER_COURSE);

        redirect('transfer/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
	


	/**
	 * Pre Search used to save the data
	 *
	 */
	private function save_pre_search($search_type)
	{
		//Save data
		$search_params = $this->input->get();	
		$search_data = json_encode($search_params);
		$insert_id = $this->custom_db->insert_record('search_history', array('search_type' => $search_type, 'search_data' => $search_data, 'created_datetime' => date('Y-m-d H:i:s')));
		return $insert_id['insert_id'];
	}
	/**
	 * Logout function for logout from account and unset all the session variables
	 */
	function initilize_logout() {
		if (is_logged_in_user()) {
			$this->user_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '',LOGIN_POINTER => '', DOMAIN_AUTH_ID => '', DOMAIN_KEY => ''));
			redirect('general/index');
		}
	}
	/**
	 * oops page of application will be loaded here
	 */
	public function ooops()
	{
		$this->template->view('utilities/404.php');
	}
	/*
	 *
	 *Email Subscribtion
	 *
	 */
	public function email_subscription()
	{
		$data = $this->input->get();
		$mail = $data['email'];
		$domain_key = get_domain_auth_id();
		$inserted_id = $this->user_model->email_subscribtion($mail,$domain_key);
		if(isset($inserted_id) && $inserted_id != "already")
		{
			echo "success";
		}elseif($inserted_id=="already"){
			echo "already";
		}else{
			echo "failed";
		}
	}
	/**
	 * Booking Not Allowed Popup
	 */
	function booking_not_allowed()
	{
		$this->template->view('general/booking_not_allowed');
	}
	public function test($app_reference)
	{
		$this->load->model('flight_model');
		$this->load->library('booking_data_formatter');
		$booking_data = $this->flight_model->get_booking_details($app_reference, '');
		$booking_data = $this->booking_data_formatter->format_flight_booking_data($booking_data, 'b2b');
		$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
		
	}
}
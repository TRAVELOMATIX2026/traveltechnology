<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**

 *

 * @package    Provab

 * @subpackage Hotel

 * @author     Balu A<balu.provab@gmail.com>

 * @version    V1

 */



class Hotel extends MY_Controller 

{

	private $current_module;

	public function __construct()

	{

		parent::__construct();

		//we need to activate hotel api which are active for current domain and load those libraries

		$this->index();

		$this->load->model('hotel_model');

		$this->load->model('domain_management_model');

		$this->current_module = $this->config->item('current_module');

	}



	/**

	 * index page of application will be loaded here

	 */

	function index()

	{



	}

/**

	 * Balu A

	 */

	function pre_cancellation($app_reference, $booking_source)

	{

		if (empty($app_reference) == false && empty($booking_source) == false) {

			$page_data = array();

			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);

			if ($booking_details['status'] == SUCCESS_STATUS) {

				$this->load->library('booking_data_formatter');

				//Assemble Booking Data

				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details,$this->current_module);

				$page_data['data'] = $assembled_booking_details['data'];

				$this->template->view('hotel/pre_cancellation', $page_data);

			} else {

				redirect('security/log_event?event=Invalid Details');

			}

		} else {

			redirect('security/log_event?event=Invalid Details');

		}

	}

	/*

	 * Balu A

	 * Process the Booking Cancellation

	 * Full Booking Cancellation

	 *

	 */

	function cancel_booking($app_reference, $booking_source)

	{

		if(empty($app_reference) == false) {

			$master_booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);

			if ($master_booking_details['status'] == SUCCESS_STATUS) {

				$this->load->library('booking_data_formatter');

				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
				$master_booking_details = $master_booking_details['data']['booking_details'][0];

				load_hotel_lib($booking_source);



				//Invoke Cancellation Methods



				switch ($booking_source) {

					case PROVAB_HOTEL_BOOKING_SOURCE :

						$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);

						break;

					case TBO_HOTEL_BOOKING_SOURCE :

						$master_booking_details = $master_booking_details['data']['booking_details'][0];

						$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);

						break;

					default :

					exit('booking source not found !');

					break;



				}


				//debug($cancellation_details);exit;
				//$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);



				if($cancellation_details['status'] == false) {

					$query_string = '?error_msg='.$cancellation_details['msg'];

				} else {

					$query_string = '';

				}

				redirect('hotel/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);

			} else {

				redirect('security/log_event?event=Invalid Details');

			}

		} else {

			redirect('security/log_event?event=Invalid Details');

		}

	}

	/**

	 * Balu A

	 * Cancellation Details

	 * @param $app_reference

	 * @param $booking_source

	 */

	function cancellation_details($app_reference, $booking_source)

	{

		if (empty($app_reference) == false && empty($booking_source) == false) {

			$master_booking_details = $GLOBALS['CI']->hotel_model->get_booking_details($app_reference, $booking_source);

			if ($master_booking_details['status'] == SUCCESS_STATUS) {

				$page_data = array();

				$this->load->library('booking_data_formatter');

				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');

				$page_data['data'] = $master_booking_details['data'];

				$this->template->view('hotel/cancellation_details', $page_data);

			} else {

				redirect('security/log_event?event=Invalid Details');

			}

		} else {

			redirect('security/log_event?event=Invalid Details');

		}

	}

	/**

	 * Balu A

	 * Displays Cancellation Refund Details

	 * @param unknown_type $app_reference

	 * @param unknown_type $status

	 */

	public function cancellation_refund_details()

	{

		$get_data = $this->input->get();

		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true && $get_data['status'] == 'BOOKING_CANCELLED'){

			$app_reference = trim($get_data['app_reference']);

			$booking_source = trim($get_data['booking_source']);

			$status = trim($get_data['status']);

			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);

			if($booking_details['status'] == SUCCESS_STATUS){

				$booked_user_id = intval($booking_details['data']['booking_details'][0]['created_by_id']);

				$booked_user_details = array();

				$is_agent = false;

				$user_condition[] = array('U.user_id' ,'=', $booked_user_id);

				$booked_user_details = $this->user_model->get_user_details($user_condition);

				if(valid_array($booked_user_details) == true){

					$booked_user_details = $booked_user_details[0];

					if($booked_user_details['user_type'] == B2B_USER){

						$is_agent = true;

					}

				}

				$page_data = array();

				$page_data['booking_data'] = 		$booking_details['data'];

				$page_data['booked_user_details'] =	$booked_user_details;

				$page_data['is_agent'] = 			$is_agent;

				$this->template->view('hotel/cancellation_refund_details', $page_data);

			} else {

				redirect(base_url());

			}

		} else {

			redirect(base_url());

		}

	}

	/**

	 * Updates Cancellation Refund Details

	 */

	public function update_refund_details()

	{

		$post_data = $this->input->post();

		$redirect_url_params = array();

		$this->form_validation->set_rules('app_reference', 'app_reference', 'trim|required|xss_clean');

		$this->form_validation->set_rules('status', 'passenger_status', 'trim|required|xss_clean');

		$this->form_validation->set_rules('status', 'passenger_status', 'trim|required|xss_clean');

		$this->form_validation->set_rules('refund_payment_mode', 'refund_payment_mode', 'trim|required|xss_clean');

		$this->form_validation->set_rules('refund_amount', 'refund_amount', 'trim|numeric');

		$this->form_validation->set_rules('cancellation_charge', 'cancellation_charge', 'trim|numeric');

		$this->form_validation->set_rules('refund_status', 'refund_status', 'trim|required|xss_clean');

		$this->form_validation->set_rules('refund_comments', 'refund_comments', 'trim|required');

		if ($this->form_validation->run()) {

			$app_reference = 				trim($post_data['app_reference']);

			$booking_source = 				trim($post_data['booking_source']);

			$status = 						trim($post_data['status']);

			$refund_payment_mode = 			trim($post_data['refund_payment_mode']);

			$refund_amount = 				floatval($post_data['refund_amount']);

			$cancellation_charge = 			floatval($post_data['cancellation_charge']);

			$refund_status = 				trim($post_data['refund_status']);

			$refund_comments = 				trim($post_data['refund_comments']);

			//Get Booking Details

			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);

			if($booking_details['status'] == SUCCESS_STATUS){

				$master_booking_details = $booking_details['data']['booking_details'][0];

				$booking_currency = $master_booking_details['currency'];//booking currency

				$booked_user_id = intval($master_booking_details['created_by_id']);

				$user_condition[] = array('U.user_id' ,'=', $booked_user_id);

				$booked_user_details = $this->user_model->get_user_details($user_condition);

				$is_agent = false;

				if(valid_array($booked_user_details) == true && $booked_user_details[0]['user_type'] == B2B_USER){

					$is_agent = true;

				}

				//REFUND AMOUNT TO AGENT

				$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $booking_currency));

				$currency_conversion_rate = $currency_obj->currency_conversion_value(true, get_application_default_currency(), $booking_currency);

				if($refund_status == 'PROCESSED' && floatval($refund_amount) > 0 && $is_agent == true){

					//1.Crdeit the Refund Amount to Respective Agent

					$agent_refund_amount = ($currency_conversion_rate*$refund_amount);//converting to agent currency



					//2.Add Transaction Log for the Refund

					$fare = -($refund_amount);//dont remove: converting to negative

					$domain_markup=0;

					$level_one_markup=0;

					$convinence = 0;

					$discount = 0;

					$remarks = 'hotel Refund was Successfully done';

					$this->domain_management_model->save_transaction_details('hotel', $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $convinence, $discount, $booking_currency, $currency_conversion_rate, $booked_user_id);



					// update agent balance

					$this->domain_management_model->update_agent_balance($agent_refund_amount, $booked_user_id);

				}

				//UPDATE THE REFUND DETAILS

				//Update Condition

				$update_refund_condition = array();

				$update_refund_condition['app_reference'] =	$app_reference;

				//Update Data

				$update_refund_details = array();

				$update_refund_details['refund_payment_mode'] = 			$refund_payment_mode;

				$update_refund_details['refund_amount'] =					$refund_amount;

				$update_refund_details['cancellation_charge'] = 			$cancellation_charge;

				$update_refund_details['refund_status'] = 					$refund_status;

				$update_refund_details['refund_comments'] = 				$refund_comments;

				$update_refund_details['currency'] = 						$booking_currency;

				$update_refund_details['currency_conversion_rate'] = 		$currency_conversion_rate;

				if($refund_status == 'PROCESSED'){

					$update_refund_details['refund_date'] = 				date('Y-m-d H:i:s');

				}

				$this->custom_db->update_record('hotel_cancellation_details', $update_refund_details, $update_refund_condition);

				

				$redirect_url_params['app_reference'] = $app_reference;

				$redirect_url_params['booking_source'] = $master_booking_details['booking_source'];

				$redirect_url_params['status'] = $status;

			}

		}

		redirect('hotel/cancellation_refund_details?'.http_build_query($redirect_url_params));

	}

	/**

	 * Balu A

	 * Get supplier cancellation status

	 */

	public function update_supplier_cancellation_status_details()

	{

		$get_data = $this->input->get();

		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true && $get_data['status'] == 'BOOKING_CANCELLED'){

			$app_reference = trim($get_data['app_reference']);

			$booking_source = trim($get_data['booking_source']);

			$status = trim($get_data['status']);

			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);

			if($booking_details['status'] == SUCCESS_STATUS){

				$master_booking_details = $booking_details['data']['booking_details'];

				$booking_customer_details = $booking_details['data']['booking_customer_details'][0];

				$cancellation_details = $booking_details['data']['cancellation_details'][0];

				$ChangeRequestId =		$cancellation_details['ChangeRequestId'];

				load_hotel_lib($booking_source);

				$response = $this->hotel_lib->get_cancellation_refund_details($ChangeRequestId, $app_reference);

				if($response['status'] == SUCCESS_STATUS){

					$cancellation_details = $response['data'];

					$this->hotel_model->update_cancellation_refund_details($app_reference, $cancellation_details);

				}

			}

		}

	}

	/**

	*Get Hotel HOLD Booking status (GRN)

	*/

	function get_pending_booking_status($app_reference,$booking_source,$status){

		$status = 0;	

		if($status=='BOOKING_HOLD'){

			$booking_source = $booking_source;

			$app_reference = $app_reference;

			$status = $status;

			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);

			if($booking_details['status']==1){

				$booking_reference = $booking_details['data']['booking_details'][0]['booking_reference'];

				

				load_hotel_lib($booking_source);

				$hold_booking_status = $this->hotel_lib->get_hotel_booking_status($app_reference);

				if($hold_booking_status['status']==true){

					$status = 1;

				}

			}

		}	

		echo  $status;

	}



	public function confirm_hotel_booking($app_reference,$booking_source)

	{



		if(empty($app_reference) == false) 

		{

			$master_booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);

			 //debug($master_booking_details);

			 //exit;





			if ($master_booking_details['status'] == SUCCESS_STATUS) 

			{

				

				$this->load->library('booking_data_formatter');

				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');

				$master_booking_details = $master_booking_details['data']['booking_details'][0];

				//debug($master_booking_details); //exit;

				load_hotel_lib($booking_source);

				

				switch ($booking_source) 

				{

					case HOTELBED_BOOKING_SOURCE:

						$confirm_booking_response = $this->hotel_lib->reconfirm_booking($master_booking_details);

						break;

					case HYPER_GUEST_HOTEL_BOOKING_SOURCE:

						$book_id = $master_booking_details['app_reference'];

						$temp_booking = $this->hotel_model->unserialize_temp_booking_record($book_id);

						$confirm_booking_response = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);

						//debug($confirm_booking_response);exit;

						//$confirm_booking_response = $this->hotel_lib->reconfirm_booking($master_booking_details);

						break;



				case Go_Global_BOOKING_SOURCE:

						$book_id = $master_booking_details['app_reference'];

						$temp_booking = $this->hotel_model->unserialize_temp_booking_record($book_id);



						//debug($temp_booking); exit();



						$confirm_booking_response = $this->hotel_lib->process_booking($book_id, $temp_booking);

						//debug($confirm_booking_response);exit;

						//$confirm_booking_response = $this->hotel_lib->reconfirm_booking($master_booking_details);

						break;		

					

					default:

						// code...

						break;

				}



				//debug($confirm_booking_response); exit;



				if ($confirm_booking_response['status'] == SUCCESS_STATUS) 

				{

					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => admin_base_currency()));

					$promo_currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));

					$confirm_booking_response['data']['currency_obj'] = $currency_obj;

					$confirm_booking_response['data']['promo_currency_obj'] = $promo_currency_obj;

					

	                $ticket_details = @$confirm_booking_response ['data'] ['ticket'];

	                $ticket_details['master_booking_status'] = $confirm_booking_response ['status'];



					$data = $this->hotel_lib->update_booking_details_backend($app_reference, $confirm_booking_response ['data'], $ticket_details,$currency_obj,$this->current_module);

					//debug($data);exit;



					//$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'] );



					redirect(base_url().'index.php/voucher/b2c_hotel_voucher/'.$app_reference.'/'.$booking_source.'/'.$data['booking_status'].'/show_voucher');

				} 

				else 

				{

					//debug($confirm_booking_response['msg']); exit;

					$msg = urlencode($confirm_booking_response['msg']);



					 redirect(base_url().'index.php/hotel/exception?op=booking_exception&notification='.$msg.'&app_reference='.$app_reference);

				}

			} 

			else 

			{

				redirect('security/log_event?event=Invalid Details');

			}

		} 

		else 

		{

			redirect('security/log_event?event=Invalid Details');

		}

	}





	function exception()

	{

		$module = META_ACCOMODATION_COURSE;

		$op = (empty($_GET['op']) == true ? '' : $_GET['op']);

		$notification = urldecode(empty($_GET['notification']) == true ? '' : $_GET['notification']);

	

		if($op == 'Some Problem Occured. Please Search Again to continue'){

			$op = 'Some Problem Occured. ';

		}

		if($notification == 'Invalid CommitBooking Request'){

			$message = 'Session is Expired';

		}

		else if($notification == 'Some Problem Occured. Please Search Again to continue' ){

			$message = 'Some Problem Occured';

		}

		else{

			$message = $notification;

		}



		$log_ip_info = $this->session->flashdata('log_ip_info');

		if(strtolower(urldecode($op))=='not available'){

			$op='';

		}





		$this->template->view('hotel/exception', array('log_ip_info' => $log_ip_info, 'exception' => $message,'is_session'=>$is_session ,'message'=>$op));

	}



}
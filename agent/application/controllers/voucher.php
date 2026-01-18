<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-4-21
* @package: 
* @Description: 
* @version: 2.0
**/
class Voucher extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library("provab_pdf");
		$this->load->library('provab_mailer');
		$this->load->library('booking_data_formatter');
		//we need to activate bus api which are active for current domain and load those libraries
		
	}

	/**
	 *
	 */
	function bus($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{

		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'bus'));
		
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2b');
				
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher		if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);						
						// debug($get_agent_info);exit;
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details_app'][$app_reference]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				switch ($operation) {
					case 'show_voucher' :
						$page_data['button'] = ACTIVE;
						$page_datap['image'] = ACTIVE;
						$this->template->view('voucher/bus_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/bus_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/bus_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template ,$pdf);
						break;
					
				}
			}
		}
	}
		/*For Sightseeing*/
	function activities($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$type='agent_receipt',$email=''){
		if (filter_var($type, FILTER_VALIDATE_EMAIL)) {
			$email = $type;
		}
		$this->load->model('sightseeing_model');

		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'activity'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2b');
				

				$page_data['data'] = $assembled_booking_details['data'];
                if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = @$page_data['data']['booking_details'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							

						}
				}
				$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					$page_data['receipt_type'] = $type;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/sightseeing_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$bookingData = $assembled_booking_details['data']['booking_details'][0];
						$bookingStatus = $bookingData['status'];
						$pnrNumber = $bookingData['booking_id'];
						$travelDate = date("d-m-Y", strtotime($bookingData['travel_date']));
						$airlineName = $bookingData['product_name'];
						$subject = "Your Activity ".formatStatusString($bookingStatus)." $pnrNumber - $airlineName | $travelDate";
						$this->provab_mailer->send_mail($email, $subject, $mail_template ,$pdf);
						break;
				}
			}
		}
	}
		/*For Transfers*/
	function transfers_old($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){
		$this->load->model('transferv1_model');

		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfer'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2b');
				

				$page_data['data'] = $assembled_booking_details['data'];
                if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = @$page_data['data']['booking_details_app'][$app_reference]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							

						}
				}
				$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					$page_data['receipt_type'] = $type;

				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transferv1_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transferv1_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/transferv1_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}

	/*For New Transfers*/

	function transfers($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){
		$this->load->model('transfer_model');

		if (empty($app_reference) == false) {
			$booking_details = $this->transfer_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfer'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transfer_booking_data($booking_details, 'b2b');
				

				$page_data['data'] = $assembled_booking_details['data'];
                if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['logo']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details_app'][$app_reference]['domain_logo'];
							}
							
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							

						}
				}
				$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}

				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transfer_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'booking_voucher':
						$this->template->view('voucher/transfer_voucher', $page_data);
						$email = @$booking_details['data']['booking_details'][0]['email'];
						
						if (empty($email) == false) {
							$mail_voucher = $this->template->isolated_view('voucher/transfer_voucher', $page_data);
							$pdf_mail_template = $this->template->isolated_view('voucher/transfer_pdf', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$pdf = $create_pdf->create_pdf($pdf_mail_template, '');
							$this->provab_mailer->send_mail($email, domain_name() . ' - Transfer Ticket', $mail_voucher,$pdf);
							$this->provab_mailer->send_mail($this->entity_domain_mail, domain_name() . ' - Transfer Ticket', $mail_voucher,$pdf);
						}
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_voucher = $this->template->isolated_view('voucher/transfer_voucher', $page_data);
						$mail_template = $this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_voucher ,$pdf);
						break;
				}
			}
		}
	}

	function hotel($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$type='agent_receipt',$email='')
	{ 
		if (filter_var($type, FILTER_VALIDATE_EMAIL)) {
			$email = $type;
		}
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			// echo json_encode($booking_details);exit;
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'hotel'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2b');
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);

						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = @$page_data['data']['booking_details_app'][$app_reference]['domain_logo'];
							}
							$page_data['data']['country_code'] = $get_agent_info[0]['country_code'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				
				}
				$page_data['receipt_type'] = $type;
				switch ($operation) {
					case 'show_voucher' : 
					// debug($page_data); exit;
					$this->template->view('voucher/hotel_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);//debug($get_view);exit;
						$create_pdf->create_pdf($get_view,'show');
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$bookingData = $assembled_booking_details['data']['booking_details'][0];
						$bookingStatus = $bookingData['status'];
						$pnrNumber = $bookingData['booking_id'];
						$travelDate = date("d-m-Y", strtotime($bookingData['hotel_check_in']));
						$airlineName = $bookingData['hotel_name'];
						$subject = "Your Hotel ".formatStatusString($bookingStatus)." $pnrNumber - $airlineName | $travelDate"; 
						$this->provab_mailer->send_mail($email, $subject, $mail_template ,$pdf);
						break;
							
				}
			}
		}
	}

	/**
	 *
	 */
	function flight($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$type='agent_receipt',$email='')
	{
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status); //debug($booking_details);exit;
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'flight'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2b');
				
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details_app'][$app_reference]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['country_code'] = $get_agent_info[0]['country_code'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}	
				
				}
				// debug($page_data);exit;
				//get the address
				if(isset($assembled_booking_details['data']['booking_details'][0]['created_by_id'])){
					 $get_address= $this->custom_db->single_table_records ( 'user','address',array('user_id'=>$assembled_booking_details['data']['booking_details'][0]['created_by_id']));
					 //debug($get_address);exit;
					 $page_data['data']['address'] = $get_address['data'][0]['address'];
					 
				}
               	$email= $email ? $email : $booking_details['data']['booking_details'][0]['email'];
               	$page_data['receipt_type'] = $type;
				//debug($page_data['data']['phone_code']);exit;
				// echo json_encode($page_data);exit;
				// $email_subject = domain_name().' - Flight Ticket';
				$bookingData = $assembled_booking_details['data']['booking_details'][0];
				$bookingStatus = $bookingData['status'];
				$pnrNumber = $bookingData['pnr'];
				$travelDate = date("d-m-Y", strtotime($bookingData['journey_start']));
				$airlineName = $bookingData['booking_itinerary_details'][0]['airline_name'];
				$email_subject = "Your Flight ".formatStatusString($bookingStatus)." $pnrNumber - $airlineName | $travelDate";
				switch ($operation) { 
					case 'show_voucher' : $this->template->view('voucher/flight_voucher', $page_data);
						if(empty($email)==false) {
							$mail_template = $this->template->isolated_view('voucher/flight_voucher', $page_data);
							$this->provab_mailer->send_mail($email, $email_subject, $mail_template);
						}
						break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/flight_pdf', $page_data);
                                                
						$create_pdf->create_pdf($get_view,'show');
					case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$mail_template = $this->template->isolated_view('voucher/flight_voucher', $page_data);
						$this->provab_mailer->send_mail($email, $email_subject, $mail_template ,$pdf);
						break;
				}
			}
		}
	}
	  /**
     * Car Vocuher
     */
    function car($app_reference, $booking_source = '', $booking_status = '', $operation = 'show_voucher',$type='agent_receipt', $email ='') {
        $this->load->model('car_model');
        if (empty($app_reference) == false) {
            $booking_details = $this->car_model->get_booking_details($app_reference, $booking_source, $booking_status);
            // debug($booking_details);exit;
            if ($booking_details['status'] == SUCCESS_STATUS) {
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_car_booking_datas($booking_details, 'b2b');
                // debug($assembled_booking_details);exit;
                $page_data['data'] = $assembled_booking_details['data'];
                if (isset($assembled_booking_details['data']['booking_details'][0])) {
                    //get agent address & logo for b2b voucher

                    $domain_address = $this->custom_db->single_table_records('domain_list', 'address,domain_logo', array('origin' => get_domain_auth_id()));
                    $page_data['data']['address'] = $domain_address['data'][0]['address'];
                    $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                   
                }
                	$page_data['receipt_type'] = $type;
                // debug($page_data);exit;
                switch ($operation) {
                    case 'show_voucher' : $this->template->view('voucher/car_voucher', $page_data);
                        break;
                    case 'show_pdf' :
                        $this->load->library('provab_pdf');
                        $create_pdf = new Provab_Pdf();
                        $get_view = $this->template->isolated_view('voucher/car_pdf', $page_data);//debug($get_view);exit;
                        $create_pdf->create_pdf($get_view, 'show');

                        break;
                    case 'email_voucher' :
                        $email = $this->load->library('provab_pdf');
                        $email = @$booking_details['data']['booking_details'][0]['email'];
                        $create_pdf = new Provab_Pdf();
                        $mail_template = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $pdf = $create_pdf->create_pdf($mail_template, '');
						$bookingData = $assembled_booking_details['data']['booking_details'][0];
						$bookingStatus = $bookingData['status'];
						$pnrNumber = $bookingData['booking_id'];
						$travelDate = date("d-m-Y", strtotime($bookingData['journey_datetime']));
						$airlineName = $bookingData['car_name'];
						$subject = "Your Car ".formatStatusString($bookingStatus)." $pnrNumber - $airlineName | $travelDate";
                        $this->provab_mailer->send_mail($email, $subject, $mail_template, $pdf);
                        break;
                }
            }
        }
    }
}
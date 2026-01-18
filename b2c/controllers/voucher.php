<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-04-21
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
		$this->load->model('flight_model');
		$this->load->model('hotel_model');
		$this->load->model('car_model');
		$this->load->model('transferv1_model');
		$this->load->model('transfer_model');
		$this->load->model('sightseeing_model');
		//we need to activate bus api which are active for current domain and load those libraries

		// ini_set('display_errors', '1');
		// ini_set('display_startup_errors', '1');
		// error_reporting(E_ALL);
	}
	/**
	 *
	 */
	function bus($app_reference, $booking_source='', $booking_status='', $operation='show_voucher')
	{
		error_reporting(0);
		//echo 'under working';exit;
		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'bus'));
			// debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2c');
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				
				}
			
				// echo 'herre'.$operation;exit;
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
						break;
					case 'email_voucher' : 
						$page_data['button'] = INACTIVE;
						$page_data['image'] = INACTIVE;
						$mail_template = $this->template->isolated_view('voucher/bus', $page_data);
						//$pdf = $this->provab_pdf->create_pdf($mail_template);
						$pdf = "";
						$email = $this->entity_email;
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket', $mail_template,$pdf);
					break;
				}
			}
		}
	}
	/*For Sightseeing*/
	function sightseeing($app_reference, $booking_source='', $booking_status='', $operation='show_voucher'){
		$this->load->model('sightseeing_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'activity'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c');
				
				$page_data['data'] = $assembled_booking_details['data'];
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				
				}
				switch ($operation) {
					case 'show_voucher' : 
					$this->template->view('voucher/sightseeing_voucher', $page_data);
					$email = $booking_details['data']['booking_details'][0]['email'];
					if (empty($email) == false) {
							$mail_voucher = $this->template->isolated_view('voucher/sightseeing_voucher', $page_data);
							$pdf_mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$pdf = $create_pdf->create_pdf($pdf_mail_template, '');
							$this->provab_mailer->send_mail($email, domain_name() . ' - Sightseeing Ticket', $mail_voucher,$pdf);
							$this->provab_mailer->send_mail($this->entity_domain_mail, domain_name() . ' - Sightseeing Ticket', $mail_voucher,$pdf);
						}
					break;
					case 'show_pdf' :
					error_reporting(0);
						$this->load->library('provab_pdf');
						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						//debug($get_view);exit;
						$this->provab_pdf->create_pdf($get_view,'show',$app_reference);
						break;
				}
			}
		}
	}

	/*Transfers Viator*/
	function transfer($app_reference, $booking_source='', $booking_status='', $operation='show_voucher'){
		

		if (empty($app_reference) == false) {
			$booking_details = $this->transfer_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfer'));
			//debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transfer_booking_data($booking_details, 'b2c');
				

				$page_data['data'] = $assembled_booking_details['data'];

                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					
				}
				//debug($page_data); 
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transfer_voucher', $page_data);
					break;
					case 'booking_voucher':
						$this->template->view('voucher/transfer_voucher', $page_data);
						$email = $booking_details['data']['booking_details'][0]['email'];
						
						if (empty($email) == false) {
							$mail_voucher = $this->template->isolated_view('voucher/transfer_voucher', $page_data);
							$pdf_mail_template = $this->template->isolated_view('voucher/transfer_voucher', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$pdf = $create_pdf->create_pdf($pdf_mail_template, '');
							$this->provab_mailer->send_mail($email, domain_name() . ' - Transfer Ticket', $mail_voucher,$pdf);
							$this->provab_mailer->send_mail($this->entity_domain_mail, domain_name() . ' - Transfer Ticket', $mail_voucher,$pdf);
						}
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view = $this->template->isolated_view('voucher/transfer_voucher', $page_data);
						$create_pdf->create_pdf($get_view,'show');
					break;
				}
			}
		}
	}

	/*Transfers Viator*/
	function transferv1($app_reference, $booking_source='', $booking_status='', $operation='show_voucher'){
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfer'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c');
				
				$page_data['data'] = $assembled_booking_details['data'];
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					
				}
				//debug($assembled_booking_details);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transferv1_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transferv1_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				}
			}
		}
	}
	function hotel($app_reference, $booking_source='', $booking_status='', $operation='show_voucher')
	{
		// ini_set('display_errors', 1);
		// error_reporting(E_ALL); 
		
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			// debug($booking_details);exit;
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'hotel'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
				$page_data['data'] = $assembled_booking_details['data'];
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					
				}
				// echo  json_encode($page_data);exit;
				$email=$booking_details['data']['booking_details'][0]['email'];
				//debug($email);exit;
				switch ($operation) {
					case 'show_voucher' : 
					
					if (empty($email) == false) {
							$mail_voucher = $this->template->isolated_view('voucher/hotel_voucher', $page_data);
							$pdf_mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$pdf = $create_pdf->create_pdf($pdf_mail_template, '');
							$this->provab_mailer->send_mail($email, domain_name() . ' - Hotel Ticket', $mail_voucher,$pdf);
							$this->provab_mailer->send_mail($this->entity_domain_mail, domain_name() . ' - Hotel Ticket', $mail_voucher,$pdf);
						}
					
					$this->template->view('voucher/hotel_voucher', $page_data);
					break;
					case 'show_email_voucher' : $this->template->view('voucher/hotel_email_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'show_email_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_email_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				
				}
			}
		}
	}
	/**
	 *
	 */
	function flight($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'flight'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');	
				$page_data['data'] = $assembled_booking_details['data'];
				 if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] =$domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}	
			
				}
				// $operation = 'show_pdf';
				# Get Passenger Email ID
                // $email=$booking_details['data']['booking_details'][0]['email'];

				switch ($operation) {
					case 'show_voucher' :
						/* $this->load->library('input');
						$referrer = $this->input->server('HTTP_REFERER');
						if($referrer && strpos($referrer,'process_booking') !== false){
							$mail_voucher = $this->template->isolated_view('voucher/flight_voucher', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
							$pdf = $create_pdf->create_pdf($mail_template,'');
							$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_voucher ,$pdf);
						} */
						$this->template->view('voucher/flight_voucher', $page_data);
						break;
					case 'booking_voucher':
						$this->template->view('voucher/flight_voucher', $page_data);
						$email = $booking_details['data']['booking_details'][0]['email'];
						if (empty($email) == false) {
							$mail_template = $this->template->isolated_view('voucher/flight_voucher', $page_data);
							$pdf_mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$pdf = $create_pdf->create_pdf($pdf_mail_template, '');
							$this->provab_mailer->send_mail($email, domain_name() . ' - Flight Ticket', $mail_template,$pdf);
						}
						break;
					case 'email_voucher':
						if (empty($email) == false) {
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
							$pdf = $create_pdf->create_pdf($mail_template,'');
							$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						}
						break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/flight_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				}
			}
		}
	}
	  /**
     * Car Vocuher
     */
    function car($app_reference, $booking_source = '', $booking_status = '', $operation = 'show_voucher', $email ='') {
        $this->load->model('car_model');
        if (empty($app_reference) == false) {
            $booking_details = $this->car_model->get_booking_details($app_reference, $booking_source, $booking_status);
            // debug($booking_details);exit;
            if ($booking_details['status'] == SUCCESS_STATUS) {
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_car_booking_datas($booking_details, 'b2c');
                // debug($assembled_booking_details);exit;
                $page_data['data'] = $assembled_booking_details['data'];
                if (isset($assembled_booking_details['data']['booking_details'][0])) {
                    //get agent address & logo for b2b voucher
                    $domain_address = $this->custom_db->single_table_records('domain_list', 'address,domain_logo', array('origin' => get_domain_auth_id()));
                    $page_data['data']['address'] = $domain_address['data'][0]['address'];
                    $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                   
                }
                // debug($page_data);exit;
                switch ($operation) {
                    case 'show_voucher' : 
                    	$this->template->view('voucher/car_voucher', $page_data);
                    	
						$email = @$booking_details['data']['booking_details'][0]['email'];
						if (empty($email) == false) {
							$mail_voucher = $this->template->isolated_view('voucher/car_voucher', $page_data);
							$pdf_mail_template = $this->template->isolated_view('voucher/car_pdf', $page_data);
							$this->load->library('provab_pdf');
							$create_pdf = new Provab_Pdf();
							$pdf = $create_pdf->create_pdf($pdf_mail_template, '');
							$this->provab_mailer->send_mail($email, domain_name() . ' - Car Ticket', $mail_voucher,$pdf);
							$this->provab_mailer->send_mail($this->entity_domain_mail, domain_name() . ' - Car Ticket', $mail_voucher,$pdf);
						}
                        break;
                    case 'show_pdf' :
                        $this->load->library('provab_pdf');
                        $create_pdf = new Provab_Pdf();
                        $get_view = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $create_pdf->create_pdf($get_view, 'show');
                        break;
                    case 'email_voucher' :
                        $email = $this->load->library('provab_pdf');
                        $email = @$booking_details['data']['booking_details'][0]['email'];
                        $create_pdf = new Provab_Pdf();
                        $mail_template = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $pdf = $create_pdf->create_pdf($mail_template, '');
                        $this->provab_mailer->send_mail($email, domain_name() . ' - Car Ticket', $mail_template, $pdf);
                        break;
                }
            }
        }
    }
		/*
		send email ticket 
	*/
	function email_ticket(){
		$post_params = $this->input->post ();
		//debug($post_params);exit;
		$app_reference = $post_params['app_reference'];
		$booking_source = $post_params['booking_source'];
		$booking_status = $post_params['status'];
		$module = $post_params['module'];
		//$email = $post_params['email'];
		
		if (empty ( $app_reference ) == false) {
			$this->load->library ( 'provab_mailer' );
			$this->load->library ( 'booking_data_formatter' );
			if($module == 'flight'){
				$booking_details = $this->flight_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
			}
			else if($module == 'hotel'){
				$booking_details = $this->hotel_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
			}
			else if($module == 'car'){
				$booking_details = $this->car_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
			}
			else if($module == 'activities'){
				$booking_details = $this->sightseeing_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
			}
			else if($module == 'transfers'){
				$booking_details = $this->transfer_model->get_booking_details ( $app_reference, $booking_source, $booking_status );
			}
			// debug($booking_details);exit;
			//$booking_details['data']['booking_customer_details'] = $this->booking_data_formatter->add_pax_details($booking_details['data']['booking_customer_details']);
			//$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');	
			//debug($booking_details);  die();
			$email = $booking_details['data']['booking_details'][0]['email'];
			//$email = 'elamathisidhu@gmail.com';
			if ($booking_details ['status'] == SUCCESS_STATUS) {
				if($module == 'flight'){
					// Assemble Booking Data
					$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data ( $booking_details, 'b2c' );
					
					 
					// debug($page_data);exit;
					$page_data ['data'] = $assembled_booking_details ['data'];
					if(isset($assembled_booking_details['data']['booking_details'][0])){
						//get agent address & logo for b2b voucher
						
						$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						// debug($domain_address);exit;
						$page_data['data']['address'] =$domain_address['data'][0]['address'];
						$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
						$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
						$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					}
					// debug($page_data);exit;
					$mail_template = $this->template->isolated_view ( 'voucher/flight_voucher', $page_data );
					// debug($mail_template);exit;
					$subject = 'Flight Details';
					$bookingData = $assembled_booking_details['data']['booking_details'][0];
					$bookingStatus = $bookingData['status'];
					$pnrNumber = $bookingData['pnr'];
					$travelDate = date("d-m-Y", strtotime($bookingData['journey_start']));
					$airlineName = $bookingData['booking_itinerary_details'][0]['airline_name'];
					$subject = "Your Flight ".formatStatusString($bookingStatus)." $pnrNumber – $airlineName | $travelDate";
				}
				else if($module == 'hotel'){
					// Assemble Booking Data
					$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data ( $booking_details, 'b2c' );
					//debug($assembled_booking_details);exit;
					$page_data ['data'] = $assembled_booking_details ['data'];
					if(isset($assembled_booking_details['data']['booking_details'][0])){
						//get agent address & logo for b2b voucher
					
						$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
						$page_data['data']['address'] =$domain_address['data'][0]['address'];
						$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
						$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
						$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
						$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
						$page_data['data']['terms_conditions'] = '';
						if($terms_conditions['status'] == SUCCESS_STATUS){
							$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
						}
	
						
					}
					//echo json_encode($page_data);exit;
					$mail_template = $this->template->isolated_view ( 'voucher/hotel_voucher', $page_data );
					$subject = 'Hotel Details';
					$bookingData = $assembled_booking_details['data']['booking_details'][0];
					$bookingStatus = $bookingData['status'];
					$pnrNumber = $bookingData['booking_id'];
					$travelDate = date("d-m-Y", strtotime($bookingData['hotel_check_in']));
					$airlineName = $bookingData['hotel_name'];
					$subject = "Your Hotel ".formatStatusString($bookingStatus)." $pnrNumber – $airlineName | $travelDate";
				}
				else if($module == 'car'){
					// Assemble Booking Data
					$assembled_booking_details = $this->booking_data_formatter->format_car_booking_datas ( $booking_details, 'b2c' );
					//debug($assembled_booking_details);exit;
					$page_data ['data'] = $assembled_booking_details ['data'];
					$mail_template = $this->template->isolated_view ( 'voucher/car_voucher', $page_data );
					$subject = 'Car Details';
					$bookingData = $assembled_booking_details['data']['booking_details'][0];
					$bookingStatus = $bookingData['status'];
					$pnrNumber = $bookingData['booking_id'];
					$travelDate = date("d-m-Y", strtotime($bookingData['journey_datetime']));
					$airlineName = $bookingData['car_name'];
					$subject = "Your Car ".formatStatusString($bookingStatus)." $pnrNumber – $airlineName | $travelDate";
				}else if($module=='activities'){
					// Assemble Booking Data
					$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data ( $booking_details, 'b2c' );
					//debug($assembled_booking_details);exit;
					$page_data ['data'] = $assembled_booking_details ['data'];
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						// debug($domain_address);exit;
						$page_data['data']['address'] =$domain_address['data'][0]['address'];
						$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
						$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
						$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$mail_template = $this->template->isolated_view ( 'voucher/sightseeing_voucher', $page_data );
					$subject = 'Activities Details';
					$bookingData = $assembled_booking_details['data']['booking_details'][0];
					$bookingStatus = $bookingData['status'];
					$pnrNumber = $bookingData['booking_id'];
					$travelDate = date("d-m-Y", strtotime($bookingData['travel_date']));
					$airlineName = $bookingData['product_name'];
					$subject = "Your Activity ".formatStatusString($bookingStatus)." $pnrNumber – $airlineName | $travelDate";
				}else if($module=='transfers'){
					// Assemble Booking Data
					$assembled_booking_details = $this->booking_data_formatter->format_transfer_booking_data ( $booking_details, 'b2c' );				
					//debug($assembled_booking_details);exit;
					$page_data ['data'] = $assembled_booking_details ['data'];
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						// debug($domain_address);exit;
						$page_data['data']['address'] =$domain_address['data'][0]['address'];
						$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
						$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
						$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$mail_template = $this->template->isolated_view ( 'voucher/transfer_voucher', $page_data );
					debug($mail_template);exit;
					$subject = 'Transfers Details';
				}
				$status = $this->provab_mailer->send_mail ( $email, $subject, $mail_template, '' );
				//debug($status);exit;
				$status = array (
						"STATUS" => "true" 
				);
				echo json_encode ( $status );
			}
		}else{
			$status = array (
						"STATUS" => "false" 
				);
			echo json_encode ($status);
		}
	}	
	public function send_test_mail()
	{
		error_reporting(E_ALL);
		$email = 'charishmaakula.provab@gmail.com';
		$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket', 'hhhh','');
	}
}

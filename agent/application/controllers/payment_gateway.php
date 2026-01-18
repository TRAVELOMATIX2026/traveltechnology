<?php

if (! defined ( 'BASEPATH' ))

exit ( 'No direct script access allowed' );
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-09-16
* @package: 
* @Description: 
* @version: 2.0
**/

class Payment_Gateway extends MY_Controller {

	/**

	 *

	 */

	public function __construct() {

		parent::__construct ();

		// $this->output->enable_profiler(TRUE);

		$this->load->model ( 'module_model' );

	}



	/**

	 * Redirection to payment gateway

	 * @param string $book_id		Unique string to identify every booking - app_reference

	 * @param number $book_origin	Unique origin of booking

	 */

	public function payment($book_id,$book_origin)

	{

		

		$this->load->model('transaction');

		$PG = $this->config->item('active_payment_gateway');

		load_pg_lib ( $PG );

		$pg_record = $this->transaction->read_payment_record($book_id);

		// debug($pg_record);exit;

		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (

				'book_id' => $book_id 

		) );

		$book_origin = $temp_booking ['data'] ['0'] ['id'];



		if (empty($pg_record) == false and valid_array($pg_record) == true) 
		{

			$params = json_decode($pg_record['request_params'], true);

			/*

			$pg_initialize_data = array (

				'txnid' => $params['txnid'],

				'pgi_amount' => ceil($pg_record['amount']),

				'firstname' => $params['firstname'],

				'email'=>$params['email'],

				'phone'=>$params['phone'],

				'productinfo'=> $params['productinfo']

			);*/

		} 
		else
		 {

			echo 'Under Construction :p';

			exit;

		}

		//defined in provab_config.php

		$payment_gateway_status = $this->config->item('enable_payment_gateway');

		//debug($payment_gateway_status); 
		//exit('inside payment gateway');

		if ($payment_gateway_status == true) 
		{

			/*$this->pg->initialize ( $pg_initialize_data );

			$page_data['pay_data'] = $this->pg->process_payment ();

			//Not to show cache data in browser

			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

			header("Cache-Control: post-check=0, pre-check=0", false);

			header("Pragma: no-cache");

			echo $this->template->isolated_view('payment/'.$PG.'/pay', $page_data);*/
			$this->redirect_booking($params['productinfo'], $params['txnid'], $book_origin);

		} else {

			//directly going to process booking

			$this->redirect_booking($params['productinfo'], $params['txnid'], $book_origin);

		}

	}
	public function payment_online($book_id,$book_origin)
	{
		$this->load->model('transaction');
		$PG = $this->config->item('active_payment_gateway');
		//debug($PG);exit;
		load_pg_lib ( $PG );
		//debug($PG);exit;
		$pg_record = $this->transaction->read_payment_record($book_id);
		//debug($pg_record);exit;
		$currency_objd = new Currency ( array ('module_type' => 'flight', 'from' => admin_base_currency (), 'to' => admin_base_currency ()));
		$display_curr_symbl=$currency_objd->get_currency_symbol($pg_record['currency']);
		$display_amount=$pg_record['amount'];
		$pg_record['amount'] = round((($display_amount*$pg_record['currency_conversion_rate'])*100),2);
		
		// $pg_record['amount'] = roundoff_number($pg_record['amount']*$pg_record['currency_conversion_rate']);
		//debug($pg_record);exit;
		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			$params = json_decode($pg_record['request_params'], true);
			$pg_initialize_data = array (
				'txnid' => $params['txnid'],
				'pgi_amount' => $pg_record['amount'],
				'firstname' => $params['firstname'],
				'email'=>$params['email'],
				'phone'=>$params['phone'],
				'productinfo'=> $params['productinfo']
			);
		} else {
			echo 'Under Construction :p';
			exit;
		}
		//defined in provab_config.php
		$payment_gateway_status = $this->config->item('enable_payment_gateway');
		//debug($payment_gateway_status);exit;
		if ($payment_gateway_status == true) {

			$this->pg->initialize ( $pg_initialize_data );
			// $req_res_data['req_resp_data'] = $this->pg->process_payment ();
			//$page_data['pay_data'] = $req_res_data['req_resp_data']['response'];
			// $this->transaction->update_payment_record($book_id, $req_res_data['req_resp_data']['request'], json_encode($req_res_data['req_resp_data']['response']));

			$page_data['pay_data'] = $this->pg->process_payment($book_id);

			$page_data['pay_data']['display_amount']=$display_amount;
			$page_data['pay_data']['display_curr_symbol']=$display_curr_symbl;
			$page_data['pay_data']['display_currency']=$pg_record['currency'];
			$page_data['pay_data']['domain_name']=$this->entity_domain_name;
			//debug($page_data);exit;
			//Not to show cache data in browser
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			// echo $this->template->isolated_view('payment/'.$PG.'/pay', $page_data);
			$this->template->view('payment/'.$PG.'/pay', $page_data);
		} else {
			//directly going to process booking
		//			echo 'Booking Can Not Be Done!!!';
		//			exit;
			redirect('flight/secure_booking/'.$book_id.'/'.$book_origin);
			//redirect('hotel/secure_booking/'.$book_id.'/'.$book_origin);
			//redirect('bus/secure_booking/'.$book_id.'/'.$book_origin);
		}
	}
	function validate_stripe($txnid)
	{
		$PG = $this->config->item('active_payment_gateway');
		load_pg_lib ( $PG );
		$token  = $_POST['stripeToken'];
		$email = $_POST['stripeEmail'];
		$token_type = $_POST['stripeTokenType'];
		$s_url = $_POST['surl'];
		$f_url = $_POST['furl'];
		$response = array('email'=> $email,'card'=> $token, 'type' => $token_type);
		$this->load->model('transaction');
		$txndata = $this->transaction->read_payment_record($txnid);
		$txnamt = $txndata['amount'];
		$txnamt = ($txnamt*100);
		$data = array(
			"amount" => $txnamt,
			"currency" => $txndata['currency'],
			"description" => "Payment",
			"source" => $token
			);

		$charge = $this->pg->create($data);
		//debug($charge);exit;
		$txnrequest = json_decode($txndata['request_params']);
		$productinfo = $txnrequest->productinfo;
		if($charge) {
			$response = $charge;
			$respJson = json_encode($response);
			//debug($respJson);exit;
			if($response['paid'] == true){
				$this->transaction->update_payment_record_status($txnid, ACCEPTED, $respJson);
				redirect('payment_gateway/'.$s_url.'/'.$productinfo.'/'.$txndata['app_reference'].'/success/');
			} else {
				$this->transaction->update_payment_record_status($txnid, DECLINED, $respJson);
				redirect('payment_gateway/'.$f_url.'/'.$productinfo.'/'.$txndata['app_reference'].'/failed');
			}
		} 
		else 
		{
			//exit;
			$apiError = $this->pg->api_error;
			$apiError1 = json_decode($apiError, TRUE);
			$errMsg = !empty($this->pg->api_error) ? $apiError1['error']['message']:'';
			$errMsg1 = str_replace('$','',$errMsg);
			$errMsg1 = $this->RemoveSpecialChar($errMsg1);
        	$apiError1['error']['errorMessage'] = 'Transaction has been failed! ('.$errMsg1.' )';
        	$apiError1['error']['amount'] = $txnamt;
        	$apiError1['error']['currency'] = $txndata['currency'];
        	$apiError1['error']['description'] = 'Payment';
        	$apiError1['error']['source'] = $token;
        	$apiError1['error']['app_reference'] = $txnid;
        	$apiError1 = json_encode($apiError1);
        	$respJson = $apiError1;
			$this->transaction->update_payment_record_status($txnid, DECLINED, $respJson);
			//debug('payment_gateway/'.$f_url.'/'.$productinfo.'/'.$txndata['app_reference'].'/'.$errMsg1);
			redirect('payment_gateway/'.$f_url.'/'.$productinfo.'/'.$txndata['app_reference'].'/'.$errMsg1);
		}

	}

	function RemoveSpecialChar($str) {
 
      // Using str_replace() function
      // to replace the word
      $res = str_replace( array( '\'', '"',
      ',' , ';', '<', '>' ,':' ,"'"), ' ', $str);
 
      // Returning the result
      return $res;
      }




	/**

	 *

	 */

	/*function success() {

		$this->load->model('transaction');

		$product = $_REQUEST ['productinfo'];

		$book_id = $_REQUEST ['txnid'];

		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (

				'book_id' => $book_id 

		) );

		$pg_status = $_REQUEST['status'];

		$pg_record = $this->transaction->read_payment_record($book_id);

		if ($pg_status == 'success' and empty($pg_record) == false and valid_array($pg_record) == true && valid_array ( $temp_booking ['data'] )) {

			//update payment gateway status

			$response_params = $_REQUEST;

			$this->transaction->update_payment_record_status($book_id, ACCEPTED, $response_params);

			$book_origin = $temp_booking ['data'] ['0'] ['id'];

			$this->redirect_booking($product, $book_id, $book_origin);



		}

	}
*/


	private function redirect_booking($product, $book_id, $book_origin)

	{

		switch ($product) {

			case META_AIRLINE_COURSE :

				redirect ( base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin );

				break;

			case META_BUS_COURSE :

				redirect ( base_url () . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin );

				break;

			case META_ACCOMODATION_COURSE :

				redirect ( base_url () . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin );

				break;

			case META_CAR_COURSE :

				redirect ( base_url () . 'index.php/car/process_booking/' . $book_id . '/' . $book_origin );

				break;

			default :

				redirect ( base_url().'index.php/transaction/cancel' );

				break;

		}

	}



	/**

	 *

	 */

	/*function cancel() {

		$this->load->model('transaction');

		$product = $_REQUEST ['productinfo'];

		$book_id = $_REQUEST ['txnid'];

		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (

				'book_id' => $book_id 

		) );

		$pg_record = $this->transaction->read_payment_record($book_id);

		if (empty($pg_record) == false and valid_array($pg_record) == true && valid_array ( $temp_booking ['data'] )) {

			$response_params = $_REQUEST;

			$this->transaction->update_payment_record_status($book_id, DECLINED, $response_params);

			$msg = "Payment Unsuccessful, Please try again.";

			switch ($product) {

				case META_AIRLINE_COURSE :

					redirect ( base_url () . 'index.php/flight/exception?op=booking_exception&notification=' . $msg );

					break;

				case META_BUS_COURSE :

					redirect ( base_url () . 'index.php/bus/exception?op=booking_exception&notification=' . $msg );

					break;

				case META_ACCOMODATION_COURSE :

					redirect ( base_url () . 'index.php/hotel/exception?op=booking_exception&notification=' . $msg );

					break;

			}

		}

	}
*/

	/* update Code */

	function success($product, $book_id, $pg_status) {

		// $this->custom_db->insert_record('test', array('test' => json_encode($_REQUEST)));
		$this->load->model('transaction');
		// $book_id = $_REQUEST ['referenceId'];
		// $pg_status = $_REQUEST['responseMsg'];

		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array ('book_id' => $book_id ));
		$pg_record = $this->transaction->read_payment_record($book_id);

		// $params = json_decode($pg_record['request_params'], true);
		// $product = $params['productinfo'];
		//debug($pg_record);exit;
		if ($pg_status == 'success' and empty($pg_record) == false and valid_array($pg_record) == true && valid_array ( $temp_booking ['data'] )) {
			//update payment gateway status

			// $response_params = $_REQUEST;
			// $this->transaction->update_payment_record_status($book_id, ACCEPTED, $response_params);
			$book_origin = $temp_booking ['data'] ['0'] ['id'];
			switch ($product) {
				case META_AIRLINE_COURSE :
					redirect ( base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_BUS_COURSE :
					redirect ( base_url () . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_ACCOMODATION_COURSE :
					redirect ( base_url () . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_CAR_COURSE :
					redirect ( base_url () . 'index.php/car/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_SIGHTSEEING_COURSE:
					redirect ( base_url () . 'index.php/sightseeing/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_TRANSFERV1_COURSE:
					redirect ( base_url () . 'index.php/transferv1/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_PACKAGE_COURSE:
					redirect ( base_url () . 'index.php/tours/process_booking/' . $book_id . '/' . $book_origin );
					break;
					
				default :
					redirect ( base_url().'index.php/transaction/cancel' );
					break;
			}
		}
	}

	/**
	 *
	 */
	// function cancel() {
	function cancel($product, $book_id , $msg) {
		$this->load->model('transaction');
		// $book_id = $_REQUEST ['referenceId'];
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array ('book_id' => $book_id ) );
		$pg_record = $this->transaction->read_payment_record($book_id);
		// $params = json_decode($pg_record['request_params'], true);
		// $product = $params['productinfo'];
		if (empty($pg_record) == false and valid_array($pg_record) == true && valid_array ( $temp_booking ['data'] )) {
			// $response_params = $_REQUEST;
			// $this->transaction->update_payment_record_status($book_id, DECLINED, $response_params);
			//$msg = "Payment Unsuccessful, Please try again.";
			switch ($product) {
				case META_AIRLINE_COURSE :
					redirect ( base_url () . 'index.php/flight/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_BUS_COURSE :
					redirect ( base_url () . 'index.php/bus/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_ACCOMODATION_COURSE :
					redirect ( base_url () . 'index.php/hotel/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_SIGHTSEEING_COURSE :
					redirect ( base_url () . 'index.php/sightseeing/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_TRANSFERV1_COURSE :
					redirect ( base_url () . 'index.php/transferv1/exception?op=booking_exception&notification=' . $msg );
					break;

			}
		}
	}




	function transaction_log(){

		load_pg_lib('PAYU');

		echo $this->template->isolated_view('payment/PAYU/pay');

	}

}
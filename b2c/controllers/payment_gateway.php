<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author: Shaik Jani
 * @Email: shaik.jani@provabmail.com
 * @Date: 2025-09-29
 * @package: 
 * @Description: 
 * @version: 2.0
 **/
class Payment_Gateway extends MY_Controller
{
	public $pg;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaction');
	}
	function demo_booking_blocked()
	{
		echo '<h1>Booking Not Allowed, on this Time. Go To <a href="' . base_url() . '">' . $this->entity_domain_name . '</a></h1>';
		exit;
	}
	public function payment($book_id, $book_origin)
	{
		// $this->demo_booking_blocked();
		$PG = $this->config->item('active_payment_gateway');
		load_pg_lib($PG);
		$pg_record = $this->transaction->read_payment_record($book_id);
		$currency_objd = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
		$display_curr_symbl = $currency_objd->get_currency_symbol($pg_record['currency']);
		$display_amount = $pg_record['amount'];
		// $pg_record['amount'] = roundoff_number((($display_amount * $pg_record['currency_conversion_rate'])), 2) * 100;
		$pg_record['amount'] = roundoff_number($display_amount, 2)* 100;
		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			$params = json_decode($pg_record['request_params'], true);
			$pg_initialize_data = array(
				'invoice_no' => $book_origin,
				'txnid' => $params['txnid'],
				'pgi_amount' => $pg_record['amount'],
				'firstname' => $params['firstname'],
				'email' => $params['email'],
				'phone' => $params['phone'],
				'productinfo' => $params['productinfo']
			);
		} else {
			echo 'Under Construction :p';
			exit;
		}
		//defined in provab_config.php
		$payment_gateway_status = $this->config->item('enable_payment_gateway');
		if ($payment_gateway_status == true) {
			$this->pg->initialize($pg_initialize_data);
			$this->pg->process_payment($book_id);
			$page_data['pay_data'] = $this->pg->process_payment($book_id);
			$page_data['pay_data']['display_amount'] = $display_amount;
			$page_data['pay_data']['display_curr_symbol'] = $display_curr_symbl;
			$page_data['pay_data']['display_currency'] = $pg_record['currency'];
			$page_data['pay_data']['domain_name'] = $this->entity_domain_name;
			//Not to show cache data in browser
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			$this->template->view('payment/' . $PG . '/pay', $page_data);
		} else {
			//directly going to process booking
			redirect('flight/secure_booking/' . $book_id . '/' . $book_origin);
		}
	}
	function validate_stripe($txnid)
	{
		//error_reporting(E_ALL);
		$PG = $this->config->item('active_payment_gateway');
//debug($PG);exit;
		load_pg_lib($PG);
		
		$token  = $_POST['stripeToken'];
		$email = $_POST['stripeEmail'];
		$token_type = $_POST['stripeTokenType'];
		$s_url = $_POST['surl'];
		$f_url = $_POST['furl'];
		$response = array('email' => $email, 'card' => $token, 'type' => $token_type);

		$txndata = $this->transaction->read_payment_record($txnid);
		$txnamt = $txndata['amount'];
		$txnamt = ($txnamt * 100);
		$data = array(
			"amount" => $txnamt,
			"currency" => $txndata['currency'],
			"description" => $txnid,
			"source" => $token
		);
		//debug($data);
		//debug($txndata);exit;
		$charge = $this->pg->create_payment_intent($data);
		//debug($charge);exit;
		//echo 'charge';

		
		$txnrequest = json_decode($txndata['request_params']);
		$productinfo = $txnrequest->productinfo;
		if ($charge) {
			$response = $charge;
			$respJson = json_encode($response);
			//debug($respJson);exit;
			if ($response['status'] == 'success') {
				$retrive_status = $this->pg->get_payment_intent($response['payment_intent_id']);
				//debug($retrive_status);exit;
				$respJson = json_encode($retrive_status);
				// get txn stored data
				$pg_record = $this->transaction->read_payment_record($txnid);
				//debug($response);
				//debug($retrive_status);
				$stored_amount = $pg_record['amount'];
				$response_amount = (($retrive_status['data']['amount']) / 100);
				//debug($response_amount);debug($stored_amount);exit;
				if ($retrive_status['status'] == 'success' && $response['amount'] == $retrive_status['data']['amount'] && $response_amount == $stored_amount) {
					//echo 'update';
					$this->transaction->update_payment_record_status($txnid, ACCEPTED, $respJson);
					//exit;
					redirect('payment_gateway/' . $s_url . '/' . $productinfo . '/' . $txndata['app_reference'] . '/success/');
				} else {
					//echo 'failed';
					$this->transaction->update_payment_record_status($txnid, DECLINED, $respJson);
					redirect('payment_gateway/' . $f_url . '/' . $productinfo . '/' . $txndata['app_reference'] . '/failed');
				}
			} else {
				$this->transaction->update_payment_record_status($txnid, DECLINED, $respJson);
				redirect('payment_gateway/' . $f_url . '/' . $productinfo . '/' . $txndata['app_reference'] . '/failed');
			}
		} else {
			//exit;
			$apiError = $this->pg->api_error;
			$apiError1 = json_decode($apiError, TRUE);
			$errMsg = !empty($this->pg->api_error) ? $apiError1['error']['message'] : '';
			$errMsg1 = str_replace('$', '', $errMsg);
			$errMsg1 = $this->RemoveSpecialChar($errMsg1);
			$apiError1['error']['errorMessage'] = 'Transaction has been failed! (' . $errMsg1 . ' )';
			$apiError1['error']['amount'] = $txnamt;
			$apiError1['error']['currency'] = $txndata['currency'];
			$apiError1['error']['description'] = 'Payment';
			$apiError1['error']['source'] = $token;
			$apiError1['error']['app_reference'] = $txnid;
			$apiError1 = json_encode($apiError1);
			$respJson = $apiError1;
			$this->transaction->update_payment_record_status($txnid, DECLINED, $respJson);
			//debug('payment_gateway/'.$f_url.'/'.$productinfo.'/'.$txndata['app_reference'].'/'.$errMsg1);
			redirect('payment_gateway/' . $f_url . '/' . $productinfo . '/' . $txndata['app_reference'] . '/' . $errMsg1);
		}
	}
	function RemoveSpecialChar($str)
	{
		// to replace the word
		$res = str_replace(array(
			'\'',
			'"',
			',',
			';',
			'<',
			'>',
			':',
			"'"
		), ' ', $str);
		// Returning the result
		return $res;
	}
	function success($product, $book_id, $pg_status)
	{
		// $Payment_status = $this->check_payment_status($referenceId);
		// $Payment_status = $this->check_payment_status($book_id);
		$temp_booking = $this->custom_db->single_table_records('temp_booking', '', array('book_id' => $book_id));
		//debug($temp_booking);exit;
		$pg_record = $this->transaction->read_payment_record($book_id);
		if ($pg_status == 'success' and empty($pg_record) == false and valid_array($pg_record) == true && valid_array($temp_booking['data'])) {
			//update payment gateway status
			$book_origin = $temp_booking['data']['0']['id'];
			switch ($product) {
				case META_AIRLINE_COURSE:
					redirect(base_url() . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin);
					break;
				case META_ACCOMODATION_COURSE:
					redirect(base_url() . 'hotel/process_booking/' . $book_id . '/' . $book_origin);
					break;
				case META_CAR_COURSE:
					redirect(base_url() . 'index.php/car/process_booking/' . $book_id . '/' . $book_origin);
					break;
				case META_SIGHTSEEING_COURSE:
					redirect(base_url() . 'index.php/sightseeing/process_booking/' . $book_id . '/' . $book_origin);
					break;
				case META_TRANSFERV1_COURSE:
					redirect(base_url() . 'index.php/transferv1/process_booking/' . $book_id . '/' . $book_origin);
					break;
				case META_TRANSFER_COURSE:
					redirect(base_url() . 'index.php/transfer/process_booking/' . $book_id . '/' . $book_origin);
					break;
				case META_PACKAGE_COURSE:
					redirect(base_url() . 'index.php/tours/process_booking/' . $book_id . '/' . $book_origin);
					break;
				default:
					redirect(base_url() . 'index.php/transaction/cancel');
					break;
			}
		}
	}
	function cancel($product, $book_id, $msg = '')
	{
		$temp_booking = $this->custom_db->single_table_records('temp_booking', '', array('book_id' => $book_id));
		$pg_record = $this->transaction->read_payment_record($book_id);
		if (empty($pg_record) == false and valid_array($pg_record) == true && valid_array($temp_booking['data'])) {
			//$msg = "Payment Unsuccessful, Please try again.";
			switch ($product) {
				case META_AIRLINE_COURSE:
					redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_ACCOMODATION_COURSE:
					redirect(base_url() . 'hotel/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_CAR_COURSE:
					redirect(base_url() . 'index.php/car/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_SIGHTSEEING_COURSE:
					redirect(base_url() . 'index.php/sightseeing/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_TRANSFERV1_COURSE:
					redirect(base_url() . 'index.php/transferv1/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_TRANSFER_COURSE:
					redirect(base_url() . 'index.php/transfer/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_PACKAGE_COURSE:
					redirect(base_url() . 'index.php/tours/exception?op=booking_exception&notification=' . $msg);
					break;
				default:
					redirect(base_url() . 'index.php/transaction/cancel');
					break;
			}
		}
	}
	function check_payment_status($sessionid)
	{
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions/' . $sessionid . '');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_USERPWD, 'sk_test_51SMKwIJSF00UrivHIuEbdpa2YerwLf9ElbQh4Dd6LAfIt8TJT6rjU1IM0K58pcSoaESnT8aWXIjHr4SAXdICZhto00Ss6YYdtJ' . ':' . '');
			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				//echo 'Error:' . curl_error($ch);
				$data = array('status' => false, 'error' => true, 'msg' => curl_error($ch), 'data' => array());
			}
			curl_close($ch);
			$response  = json_decode($result);
			// paid, unpaid, or no_payment_required
			// open, complete, or expired
			if ($response->status == "complete" && $response->payment_status == "paid") {
				return array(
					'status' => true,
					'amount' => roundoff_number(($response->amount_total / 100)),
					'error' => false,
					'msg' => "Payment status is " . $response->status,
					'data' => array()
				);
			} else {
				return array('status' => false, 'error' => true, 'msg' => "Payment status is " . $response->status, 'data' => array());
			}
		} catch (Exception $e) {
			$api_error = $e->getMessage();
			$data = array('status' => false, 'error' => true, 'msg' => $api_error, 'data' => array());
			return $data;
		}
	}
}

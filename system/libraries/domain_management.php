<?php
/**
 * Manages Domain
 * @package	Provab
 * @subpackage	provab
 * @category	Libraries
 * @author		Jaganath N<jaganath.provab@gmail.com>
 * @link		http://www.provab.com
 */
class Domain_Management {
	var $is_domain_user;
	public function __construct() {
		$this->CI = &get_instance ();
		$this->CI->load->model ( 'user_model' );
		$this->is_domain_user = $this->CI->config->item ( 'domain_user' );
	}
	/**
	 * Checks Domain User is valid or not
	 * 
	 * @param
	 *        	$_header
	 */
	public function is_valid_user($domain_user_details, $course) {
		$data ['status'] = FAILURE_STATUS;
		$data ['data'] = array ();
		$data ['message'] = '';
		$DomainKey = trim ( $domain_user_details ['DomainKey'] );
		$UserName = trim ( $domain_user_details ['UserName'] );
		$Password = trim ( $domain_user_details ['Password'] );
		$system = trim ( $domain_user_details ['System'] );
		$domain_login = $this->CI->user_model->domain_login ( $DomainKey, $UserName, $Password, $system );
		if ($domain_login ['status'] == SUCCESS_STATUS) {
			$data ['status'] = SUCCESS_STATUS;
			$origin = $domain_login ['data'] ['origin'];
			switch ($course) {
				case META_AIRLINE_COURSE :
					$commission = $this->get_flight_commission ( $origin ); // FIXME: plus/Percentage
					break;
			}
			$data ['data'] ['domain_origin'] = $origin;
			$data ['data'] ['commission'] = floatval ( $commission );
		} else {
			$data ['message'] = 'Invalid Domain User';
		}
		return $data;
	}
	/**
	 * Flight Commission Details
	 */
	public function get_flight_commission($domain_origin) {
		if ($this->is_domain_user == true) {
			$flight_commission_query = 'select BFCD.* from b2b_flight_commission_details as BFCD
									where ((BFCD.domain_list_fk =' . intval ( $domain_origin ) . ' and BFCD.type="specific")	OR BFCD.type="generic")
									group by BFCD.domain_list_fk
									order by BFCD.domain_list_fk desc';
			$flight_commission_details = $this->CI->db->query ( $flight_commission_query )->row_array ();
			$commission = $flight_commission_details ['value'];
			return $commission;
		} else {
			return 100; // Giving Full commission
		}
	}
	/**
	 * Verifies the Domain Balance with the Booking transaction amount and Environment
	 */
	public function verify_domain_balance($transaction_amount, $credential_type) {
		if ($this->is_domain_user == true) {
			// Converting Transaction amount to Client Base Currency
			$currency_obj = new Currency ( array (
					'from' => get_application_default_currency (),
					'to' => domain_base_currency () 
			) );
			$amount_details = $currency_obj->force_currency_conversion ( $transaction_amount );
			$transaction_amount = $amount_details ['default_value'];
			$currency = $amount_details ['default_currency'];
			$status = $this->CI->user_model->get_balance ( $transaction_amount, $credential_type, $currency );
		} else {
			$status = true;
		}
		return $status;
	}
	/**
	 * Debit the Domain's Balance
	 * 
	 * @param unknown_type $transaction_amount        	
	 * @param unknown_type $credential_type        	
	 */
	public function debit_domain_balance($transaction_amount, $credential_type) {
		$this->CI->custom_db->insert_record ( 'test', array (
				'test' => 'API Price:' . $transaction_amount 
		) );
		// Converting Transaction amount to Client Base Currency
		$currency_obj = new Currency ( array (
				'from' => get_application_default_currency (),
				'to' => domain_base_currency () 
		) );
		$amount_details = $currency_obj->force_currency_conversion ( $transaction_amount );
		$transaction_amount = $amount_details ['default_value'];
		$this->CI->custom_db->insert_record ( 'test', array (
				'test' => 'Client Price:' . $transaction_amount 
		) );
		// Add status
		if ($this->is_domain_user == true) {
			$debit_amount = - ($transaction_amount);
			$this->CI->user_model->update_balance ( $debit_amount, $credential_type );
		}
	}
}

<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author: Shaik Jani
 * @Email: shaik.jani@provabmail.com
 * @Date: 2025-03-25
 * @package: 
 * @Description: 
 * @version: 2.0
 **/
class Management extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Custom_Db');
	}
	public function promocodeOld()
	{
		error_reporting(0);
		$all_post = $this->input->post();
		$application_default_currency = admin_base_currency();
		$currency_obj = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => $all_post['currency']));
		//debug($all_post); exit();
		$condition['promo_code'] = $all_post['promocode'];
		$condition['status'] = 1;
		$promo_code_res = $this->Custom_Db->single_table_records('promo_code_list', '*', $condition);
		// debug($all_post);exit;
		if ($promo_code_res['status'] == 1) {
			$promo_code = $promo_code_res['data'][0];
			if (md5($promo_code['module']) != $all_post['moduletype']) {
				$result['status'] = 0;
				$result['error_msg'] = 'Invalid Promo Code';
			} elseif ($promo_code['expiry_date'] <= date('Y-m-d') && $promo_code['expiry_date'] != '0000-00-00') {
				$result['status'] = 0;
				$result['error_msg'] = 'Promo Code Expired';
			} else {
				// echo $this->entity_user_id; exit();
				if ($promo_code['module'] == 'car') {
					$booking_table = 'car_booking_details';
				} elseif ($promo_code['module'] == 'hotel') {
					$booking_table = 'hotel_booking_details';
				} elseif ($promo_code['module'] == 'flight') {
					$booking_table = 'flight_booking_details';
				} elseif ($promo_code['module'] == 'activities') {
					$booking_table = 'sightseeing_booking_details';
				} elseif ($promo_code['module'] == 'transfers') {
					$booking_table = 'transferv1_booking_details';
				} elseif ($promo_code['module'] == 'bus') {
					$booking_table = 'bus_booking_details';
				}
				//debug($booking_table);exit;
				###################################################################################
				if (is_logged_in_user()) {
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='" . $this->entity_user_id . "' ";
				} else {
					$email = $all_post['email'];
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='" . $email . "' and PGD.status!='pending'";
				}
				###################################################################################
				//debug($query);exit;
				$user_promocode_check = $this->Custom_Db->get_result_by_query($query);
				//debug($user_promocode_check);exit;
				$user_promocode_check = 0;
				if ($user_promocode_check > 0) {
					//if((($promo_code['use_type']=='single' && count($user_promocode_check)>0) || ($promo_code['use_type']=='multiple' && $promo_code['limitation']<=count($user_promocode_check))) && ($promo_code['use_type']=='multiple' && $promo_code['limitation']!=-1)){
					$result['status'] = 0;
					$result['error_msg'] = 'Already used';
				} else {
					$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
					//debug($promo_code);
					// debug($all_post);exit;
					$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
					if ($total_amount_val_org > $minimum_amount) {
						if ($promo_code['value_type'] == 'percentage') {
							$result['value'] = ($total_amount_val_org * round($promo_code['value'])) / 100;
							//$result['value'] = number_format($result['value'],2);
							$result['value'] = $result['value'];
							$result['actual_value'] = number_format($result['value'], 2);
						} else {
							$result['value'] = $promo_code['value'];
							$result['actual_value'] = number_format($promo_code['value'], 2);
							$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
							$result['value'] = $result['value'];
						}
						if ($result['value'] < $total_amount_val_org) {
							$total_amount_val = ($total_amount_val_org + $all_post['convenience_fee']) - $result['value'];
							if (isset($all_post['extra_baggage'])) {
								$total_amount_val += $all_post['extra_baggage'];
							}
							if (isset($all_post['extra_meal'])) {
								$total_amount_val += $all_post['extra_meal'];
							}
							if (isset($all_post['extra_seat'])) {
								$total_amount_val += $all_post['extra_seat'];
							}
							$total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
							$result['total_amount_val'] = round($total_amount_val);
							// $result['value'] = sprintf("%.2f", ceil($result['value']));
							//$result['value'] = round($result['value']).'.00';
							$result['total_amount_data'] = $all_post['currency_symbol'] . " " . roundoff_number($total_amount_val);
							$result['convenience_fee'] = $all_post['convenience_fee'];
							$result['promocode'] = $all_post['promocode'];
							$result['discount_value'] = $all_post['currency_symbol'] . " " . roundoff_number($result['value']);
							$result['module'] = $all_post['moduletype'];
							$result['status'] = 1;
							//debug($result);exit;
							$this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
						} else {
							$result['status'] = 0;
							$result['error_msg'] = 'Invalid Promo Code';
						}
					} else {
						// echo 'herree';exit;
						$result['status'] = 0;
						$result['error_msg'] = 'Invalid Promo Code';
					}
				}
			}
		} else {
			$result['status'] = $promo_code_res['status'];
			$result['error_msg'] = 'Invalid Promo Code';
		}
		echo json_encode($result);
	}
	public function promocode_flightscanne_code()
	{
		error_reporting(0);
		ini_set('display_errors', 1);

		$all_post = $this->input->post();
		$application_default_currency = admin_base_currency();
		$currency_obj = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => $all_post['currency']));
		$condition['promo_code'] = $all_post['promocode'];
		$condition['status'] = 1;
		$promo_code_res = $this->Custom_Db->single_table_records('promo_code_list', '*', $condition);
		debug($all_post);exit;
		if ($promo_code_res['status'] == 1) {
			$promo_code = $promo_code_res['data'][0];
			if ($promo_code['module'] == 'new_register') {
				// echo $this->entity_user_id;exit('sudheer');
				if (is_logged_in_user() == false) {
					$result['status'] = 0;
					$result['error_msg'] = 'You do not have access to use this Promo Code';
				} else {
					$this->db->where(array('promo_code' => $all_post['promocode'], 'user_id' => $this->entity_user_id));
					$result = $this->db->get('user')->result_array();
					if (empty($result)) {
						$result['status'] = 0;
						$result['error_msg'] = 'You do not have this Promo Code';
					} elseif ($result[0]['promo_code_expiry_date'] <= date('Y-m-d') && $result[0]['promo_code_expiry_date'] != '0000-00-00') {
						$result['status'] = 0;
						$result['error_msg'] = 'Promo Code Expired';
					} elseif ($result[0]['promo_code_remaining_value'] == 0) {
						$result['status'] = 0;
						$result['error_msg'] = 'You already used this total Promo Code value';
					} else {
						$moduleEncryptedArray = [
							'flight' => 'e325b16aa10bc2b065742595902073cb',
							'hotel' => 'e919c49d5f0cd737285367810a3394d0',
							'car' => 'e6d96502596d7e7887b76646c5f615d9',
							'activities' => '609f88983635a66fe4c8570afee066e0'
						];
						$moduleTableMapping = [
							'flight' => 'flight_booking_details',
							'hotel' => 'hotel_booking_details',
							'car' => 'car_booking_details',
							'activities' => 'sightseeing_booking_details',
							'transfers' => 'transferv1_booking_details',
							'bus' => 'bus_booking_details'
						];
						$moduleTypeEncrypted = $all_post['moduletype'];
						$moduleKey = array_search($moduleTypeEncrypted, $moduleEncryptedArray);
						if ($moduleKey && isset($moduleTableMapping[$moduleKey])) {
							$booking_table = $moduleTableMapping[$moduleKey];
						}
						// Get promocode values from user table
						$uQuery = "SELECT user_id, promo_code_total_value, promo_code_remaining_value FROM user WHERE user_id='" . $this->entity_user_id . "'";
						$uResult = $this->db->query($uQuery)->result_array();
						// debug($uResult);exit;
						$pax_count = $all_post['pax_count'];
						$uPromoCodeTotalValue = $uResult[0]['promo_code_total_value'];
						$uPromoCodeRemainingValue = $uResult[0]['promo_code_remaining_value'];
						$discount_per_pax = 10;
						$potential_discount = $pax_count * $discount_per_pax;
						// echo $uPromoCodeRemainingValue;
						$final_discount = min($potential_discount, $uPromoCodeRemainingValue);
						$updatedRemainingValue = $uPromoCodeRemainingValue - $final_discount;
						$updatedRemainingValue = max($updatedRemainingValue, 0);
						// echo $updatedRemainingValue;exit;
						// $updatedTotalValue = $uPromoCodeTotalValue - 10;
						// $percentageValue = round($promo_code['value']);
						// $percentageAmount = ($uPromoCodeTotalValue * $percentageValue) / 100;
						// echo "Original Total Value: " . $uPromoCodeTotalValue . "\n";
						// echo "Percentage Value: " . $percentageValue . "%\n";
						// echo "Percentage Amount: " . $percentageAmount . "\n";
						// echo "Updated Total Value: " . $updatedTotalValue . "\n";
						// exit;
						$promo_code_token = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 10);
						// Update user table
						/* 	$uUpdatePromoCodeData = [
							'promo_code_remaining_value' => $updatedRemainingValue
						];
						$this->db->where('user_id', $this->entity_user_id);
						$this->db->update('user', $uUpdatePromoCodeData); */
						// Creating promo code tracker
						$newUserPromoCodeTrackInsertData = [
							'user_id' => $this->entity_user_id,
							'promo_code' => $all_post['promocode'],
							'module' => $moduleKey,
							'promo_code_total_value' => $uPromoCodeTotalValue,
							'promo_code_remaining_value' => $updatedRemainingValue,
							// 'promo_code_used_value' => $percentageAmount,
							'promo_code_used_value' => $final_discount,
							'created_date_time' => date('Y-m-d H:i:s'),
							'token' => $promo_code_token,
							'status' => 0
						];
						// debug($newUserPromoCodeTrackInsertData);exit;
						$this->db->insert('new_user_promocode_track', $newUserPromoCodeTrackInsertData);
						$promocodeTokenData = [
							'user_id' => $this->entity_user_id,
							'token' => $promo_code_token,
							'status' => 1 // Set status as active
						];
						$this->db->insert('promocode_token', $promocodeTokenData);
						// echo $booking_table;exit;
						$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
						$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
						if ($total_amount_val_org > $minimum_amount) {
							if ($promo_code['value_type'] == 'percentage') {
								// $result['value']=($total_amount_val_org*round($promo_code['value']))/100;
								$result['value'] = ($total_amount_val_org * round($final_discount)) / 100;
								$result['value'] = $result['value'];
								$result['actual_value'] = number_format($result['value'], 2);
							} else {
								// $result['value']= $promo_code['value'];
								// $result['actual_value']= number_format($promo_code['value'],2);
								$result['value'] = $final_discount;
								$result['actual_value'] = number_format($final_discount, 2);
								$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
								$result['value'] = $result['value'];
							}
							if ($result['value'] < $total_amount_val_org) {
								$total_amount_val = ($total_amount_val_org + $all_post['convenience_fee']) - $result['value'];
								if (isset($all_post['extra_baggage'])) {
									$total_amount_val += $all_post['extra_baggage'];
								}
								if (isset($all_post['extra_meal'])) {
									$total_amount_val += $all_post['extra_meal'];
								}
								if (isset($all_post['extra_seat'])) {
									$total_amount_val += $all_post['extra_seat'];
								}
								$total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
								$result['total_amount_val'] = round($total_amount_val);
								$result['total_amount_data'] = $all_post['currency_symbol'] . " " . roundoff_number($total_amount_val);
								$result['convenience_fee'] = $all_post['convenience_fee'];
								$result['promocode'] = $all_post['promocode'];
								$result['discount_value'] = $all_post['currency_symbol'] . " " . roundoff_number($result['value']);
								$result['module'] = $all_post['moduletype'];
								$result['status'] = 1;
								$this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
							} else {
								$result['status'] = 0;
								$result['error_msg'] = 'Invalid Promo Code';
							}
						}
					}
				}
			} elseif (md5($promo_code['module']) != $all_post['moduletype']) {
				$result['status'] = 0;
				$result['error_msg'] = 'Invalid Promo Code';
			} elseif ($promo_code['expiry_date'] <= date('Y-m-d') && $promo_code['expiry_date'] != '0000-00-00') {
				$result['status'] = 0;
				$result['error_msg'] = 'Promo Code Expired';
			} else {
				if ($promo_code['module'] == 'car') {
					$booking_table = 'car_booking_details';
				} elseif ($promo_code['module'] == 'hotel') {
					$booking_table = 'hotel_booking_details';
				} elseif ($promo_code['module'] == 'flight') {
					$booking_table = 'flight_booking_details';
				} elseif ($promo_code['module'] == 'activities') {
					$booking_table = 'sightseeing_booking_details';
				} elseif ($promo_code['module'] == 'transfers') {
					$booking_table = 'transferv1_booking_details';
				} elseif ($promo_code['module'] == 'bus') {
					$booking_table = 'bus_booking_details';
				}
				###################################################################################
				if (is_logged_in_user()) {
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='" . $this->entity_user_id . "' ";
				} else {
					$email = $all_post['email'];
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='" . $email . "' and PGD.status!='pending'";
				}
				###################################################################################
				$user_promocode_check = $this->Custom_Db->get_result_by_query($query);
				$user_promocode_check = 0;
				if ($user_promocode_check > 0) {
					$result['status'] = 0;
					$result['error_msg'] = 'Already used';
				} else {
					$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
					$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
					if ($total_amount_val_org > $minimum_amount) {
						if ($promo_code['value_type'] == 'percentage') {
							$result['value'] = ($total_amount_val_org * round($promo_code['value'])) / 100;
							$result['value'] = $result['value'];
							$result['actual_value'] = number_format($result['value'], 2);
						} else {
							$result['value'] = $promo_code['value'];
							$result['actual_value'] = number_format($promo_code['value'], 2);
							$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
							$result['value'] = $result['value'];
						}
						if ($result['value'] < $total_amount_val_org) {
							$total_amount_val = ($total_amount_val_org + $all_post['convenience_fee']) - $result['value'];
							if (isset($all_post['extra_baggage'])&& empty($all_post['extra_baggage'])==false) {
								$total_amount_val += $all_post['extra_baggage'];
							}
							if (isset($all_post['extra_meal']) && empty($all_post['extra_meal'])==false) {
								$total_amount_val += $all_post['extra_meal'];
							}
							if (isset($all_post['extra_seat']) && empty($all_post['extra_seat'])==false) {
								$total_amount_val += $all_post['extra_seat'];
							}
							$total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
							$result['total_amount_val'] = round($total_amount_val);
							$result['total_amount_data'] = $all_post['currency_symbol'] . " " . roundoff_number($total_amount_val);
							$result['convenience_fee'] = $all_post['convenience_fee'];
							$result['promocode'] = $all_post['promocode'];
							$result['discount_value'] = $all_post['currency_symbol'] . " " . roundoff_number($result['value']);
							$result['module'] = $all_post['moduletype'];
							$result['status'] = 1;
							$this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
						} else {
							$result['status'] = 0;
							$result['error_msg'] = 'Invalid Promo Code';
						}
					} else {
						$result['status'] = 0;
						$result['error_msg'] = 'Invalid Promo Code';
					}
				}
			}
		} else {
			$result['status'] = $promo_code_res['status'];
			$result['error_msg'] = 'Invalid Promo Code';
		}
		echo json_encode($result);
	}
	public function promocode()
	{
		error_reporting(1);
		//ini_set('display_errors', 1);

		$all_post = $this->input->post();
		$application_default_currency = admin_base_currency();
		$currency_obj = new Currency(array('module_type' => 'flight', 'from' => admin_base_currency(), 'to' => $all_post['currency']));
		$condition['promo_code'] = $all_post['promocode'];
		$condition['status'] = 1;
		$promo_code_res = $this->Custom_Db->single_table_records('promo_code_list', '*', $condition);
		//debug($all_post);
		if ($promo_code_res['status'] == 1) {
			$promo_code = $promo_code_res['data'][0];

			if ($promo_code['module'] == 'new_register') {
				// echo $this->entity_user_id;exit('sudheer');
				if (is_logged_in_user() == false) {
					$result['status'] = 0;
					$result['error_msg'] = 'You do not have access to use this Promo Code';
				} else {
					$this->db->where(array('promo_code' => $all_post['promocode'], 'user_id' => $this->entity_user_id));
					$result = $this->db->get('user')->result_array();
					if (empty($result)) {
						$result['status'] = 0;
						$result['error_msg'] = 'You do not have this Promo Code';
					} elseif ($result[0]['promo_code_expiry_date'] <= date('Y-m-d') && $result[0]['promo_code_expiry_date'] != '0000-00-00') {
						$result['status'] = 0;
						$result['error_msg'] = 'Promo Code Expired';
					} elseif ($result[0]['promo_code_remaining_value'] == 0) {
						$result['status'] = 0;
						$result['error_msg'] = 'You already used this total Promo Code value';
					} else {
						$moduleEncryptedArray = [
							'flight' => 'e325b16aa10bc2b065742595902073cb',
							'hotel' => 'e919c49d5f0cd737285367810a3394d0',
							'car' => 'e6d96502596d7e7887b76646c5f615d9',
							'activities' => '609f88983635a66fe4c8570afee066e0'
						];
						$moduleTableMapping = [
							'flight' => 'flight_booking_details',
							'hotel' => 'hotel_booking_details',
							'car' => 'car_booking_details',
							'activities' => 'sightseeing_booking_details',
							'transfers' => 'transferv1_booking_details',
							'bus' => 'bus_booking_details'
						];
						$moduleTypeEncrypted = $all_post['moduletype'];
						$moduleKey = array_search($moduleTypeEncrypted, $moduleEncryptedArray);
						if ($moduleKey && isset($moduleTableMapping[$moduleKey])) {
							$booking_table = $moduleTableMapping[$moduleKey];
						}
						// Get promocode values from user table
						$uQuery = "SELECT user_id, promo_code_total_value, promo_code_remaining_value FROM user WHERE user_id='" . $this->entity_user_id . "'";
						$uResult = $this->db->query($uQuery)->result_array();
						// debug($uResult);exit;
						$pax_count = $all_post['pax_count'];
						$uPromoCodeTotalValue = $uResult[0]['promo_code_total_value'];
						$uPromoCodeRemainingValue = $uResult[0]['promo_code_remaining_value'];
						$discount_per_pax = 10;
						$potential_discount = $pax_count * $discount_per_pax;
						// echo $uPromoCodeRemainingValue;
						$final_discount = min($potential_discount, $uPromoCodeRemainingValue);
						$updatedRemainingValue = $uPromoCodeRemainingValue - $final_discount;
						$updatedRemainingValue = max($updatedRemainingValue, 0);
						// echo $updatedRemainingValue;exit;
						// $updatedTotalValue = $uPromoCodeTotalValue - 10;
						// $percentageValue = round($promo_code['value']);
						// $percentageAmount = ($uPromoCodeTotalValue * $percentageValue) / 100;
						// echo "Original Total Value: " . $uPromoCodeTotalValue . "\n";
						// echo "Percentage Value: " . $percentageValue . "%\n";
						// echo "Percentage Amount: " . $percentageAmount . "\n";
						// echo "Updated Total Value: " . $updatedTotalValue . "\n";
						// exit;
						$promo_code_token = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 10);
						// Update user table
						/* 	$uUpdatePromoCodeData = [
							'promo_code_remaining_value' => $updatedRemainingValue
						];
						$this->db->where('user_id', $this->entity_user_id);
						$this->db->update('user', $uUpdatePromoCodeData); */
						// Creating promo code tracker
						$newUserPromoCodeTrackInsertData = [
							'user_id' => $this->entity_user_id,
							'promo_code' => $all_post['promocode'],
							'module' => $moduleKey,
							'promo_code_total_value' => $uPromoCodeTotalValue,
							'promo_code_remaining_value' => $updatedRemainingValue,
							// 'promo_code_used_value' => $percentageAmount,
							'promo_code_used_value' => $final_discount,
							'created_date_time' => date('Y-m-d H:i:s'),
							'token' => $promo_code_token,
							'status' => 0
						];
						// debug($newUserPromoCodeTrackInsertData);exit;
						$this->db->insert('new_user_promocode_track', $newUserPromoCodeTrackInsertData);
						$promocodeTokenData = [
							'user_id' => $this->entity_user_id,
							'token' => $promo_code_token,
							'status' => 1 // Set status as active
						];
						$this->db->insert('promocode_token', $promocodeTokenData);
						// echo $booking_table;exit;
						$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
						$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
						if ($total_amount_val_org > $minimum_amount) {
							if ($promo_code['value_type'] == 'percentage') {
								// $result['value']=($total_amount_val_org*round($promo_code['value']))/100;
								$result['value'] = ($total_amount_val_org * round($final_discount)) / 100;
								$result['value'] = $result['value'];
								$result['actual_value'] = number_format($result['value'], 2);
							} else {
								// $result['value']= $promo_code['value'];
								// $result['actual_value']= number_format($promo_code['value'],2);
								$result['value'] = $final_discount;
								$result['actual_value'] = number_format($final_discount, 2);
								$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
								$result['value'] = $result['value'];
							}
							if ($result['value'] < $total_amount_val_org) {
								$total_amount_val = ($total_amount_val_org + $all_post['convenience_fee']) - $result['value'];
								if (isset($all_post['extra_baggage'])) {
									$total_amount_val += $all_post['extra_baggage'];
								}
								if (isset($all_post['extra_meal'])) {
									$total_amount_val += $all_post['extra_meal'];
								}
								if (isset($all_post['extra_seat'])) {
									$total_amount_val += $all_post['extra_seat'];
								}
								$total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
								$result['total_amount_val'] = round($total_amount_val);
								$result['total_amount_data'] = $all_post['currency_symbol'] . " " . roundoff_number($total_amount_val);
								$result['convenience_fee'] = $all_post['convenience_fee'];
								$result['promocode'] = $all_post['promocode'];
								$result['discount_value'] = $all_post['currency_symbol'] . " " . roundoff_number($result['value']);
								$result['module'] = $all_post['moduletype'];
								$result['status'] = 1;
								$this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
							} else {
								$result['status'] = 0;
								$result['error_msg'] = 'Invalid Promo Code';
							}
						}
					}
				}
			} elseif (md5($promo_code['module']) != $all_post['moduletype']) {
				$result['status'] = 0;
				$result['error_msg'] = $promo_code['module'].' Invalid Promo Code';
			} elseif ($promo_code['expiry_date'] <= date('Y-m-d') && $promo_code['expiry_date'] != '0000-00-00') {
				$result['status'] = 0;
				$result['error_msg'] = 'Promo Code Expired';
			} else {
				if ($promo_code['module'] == 'car') {
					$booking_table = 'car_booking_details';
				} elseif ($promo_code['module'] == 'hotel') {
					$booking_table = 'hotel_booking_details';
				} elseif ($promo_code['module'] == 'flight') {
					$booking_table = 'flight_booking_details';
				} elseif ($promo_code['module'] == 'activities') {
					$booking_table = 'sightseeing_booking_details';
				} elseif ($promo_code['module'] == 'transfers') {
					$booking_table = 'transferv1_booking_details';
				} elseif ($promo_code['module'] == 'bus') {
					$booking_table = 'bus_booking_details';
				}
				###################################################################################
				if (is_logged_in_user()) {
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.created_by_id='" . $this->entity_user_id . "' ";
				} else {
					$email = $all_post['email'];
					$query = "SELECT BD.origin FROM payment_gateway_details AS PGD RIGHT JOIN " . $booking_table . " AS BD ON PGD.app_reference = BD.app_reference WHERE BD.email='" . $email . "' and PGD.status!='pending'";
				}
				###################################################################################
				$user_promocode_check = $this->Custom_Db->get_result_by_query($query);
				$user_promocode_check = 0;
				if ($user_promocode_check > 0) {
					$result['status'] = 0;
					$result['error_msg'] = 'Already used';
				} else {
					$minimum_amount = get_converted_currency_value($currency_obj->force_currency_conversion($promo_code['minimum_amount']));
					$total_amount_val_org = str_replace(',', '', $all_post['total_amount_val']);
					//debug($minimum_amount);
					//debug($total_amount_val_org);
					//exit;
					if ($total_amount_val_org > $minimum_amount) 
					{
						if ($promo_code['value_type'] == 'percentage') {
							$result['value'] = ($total_amount_val_org * round($promo_code['value'])) / 100;
							$result['value'] = $result['value'];
							$result['actual_value'] = number_format($result['value'], 2);
						} else {
							$result['value'] = $promo_code['value'];
							$result['actual_value'] = number_format($promo_code['value'], 2);
							$result['value'] = get_converted_currency_value($currency_obj->force_currency_conversion($result['value']));
							$result['value'] = $result['value'];
						}
						if ($result['value'] < $total_amount_val_org) {
							$total_amount_val = ($total_amount_val_org + $all_post['convenience_fee']) - $result['value'];
							if (isset($all_post['extra_baggage'])&& empty($all_post['extra_baggage'])==false) {
								$total_amount_val += $all_post['extra_baggage'];
							}
							if (isset($all_post['extra_meal']) && empty($all_post['extra_meal'])==false) {
								$total_amount_val += $all_post['extra_meal'];
							}
							if (isset($all_post['extra_seat']) && empty($all_post['extra_seat'])==false) {
								$total_amount_val += $all_post['extra_seat'];
							}
							$total_amount_val = ($total_amount_val > 0) ? $total_amount_val : 0;
							$result['total_amount_val'] = round($total_amount_val);
							$result['total_amount_data'] = $all_post['currency_symbol'] . " " . roundoff_number($total_amount_val);
							$result['convenience_fee'] = $all_post['convenience_fee'];
							$result['promocode'] = $all_post['promocode'];
							$result['discount_value'] = $all_post['currency_symbol'] . " " . roundoff_number($result['value']);
							$result['module'] = $all_post['moduletype'];
							$result['status'] = 1;
							$this->custom_db->insert_record('promo_code_doscount_applied', array('discount_value' => $result['actual_value'], 'promocode' => $result['promocode'], 'module' => $result['module'], 'search_key' => provab_encrypt($all_post['booking_key']), 'created_datetime' => date('Y-m-d H:i:s')));
						} else {
							$result['status'] = 0;
							$result['error_msg'] = 'Invalid Promo Code , The Minimum Transaction Value is '.$minimum_amount;
						}
					} else {
						$result['status'] = 0;
						$result['error_msg'] = 'Invalid Promo Code ,The Minimum Transaction Value is '.$minimum_amount;
					}
				}
			}
		} else {
			$result['status'] = $promo_code_res['status'];
			$result['error_msg'] = 'Invalid Promo Code';
		}
		echo json_encode($result);
	}
}

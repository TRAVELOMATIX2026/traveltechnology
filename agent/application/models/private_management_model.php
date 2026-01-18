<?php
require_once 'abstract_management_model.php';
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Private_Management_Model extends Abstract_Management_Model
{
	private $airline_markup;
	private $hotel_markup;
	private $bus_markup;
	private $airline_commission;
	private $bus_commission;
	private $sightseeing_commission;
	private $sightseeing_markup;
	private $transfer_markup;
	private $transfer_commission;
	function __construct() {
		parent::__construct('level_3');
	}

	/**
	 * Get Convinence fees of module
	 */
	function get_convinence_fees($module_name, $search_id)
	{
		$convinence_fees = array('value' => 0, 'type' => '', 'per_pax' => true);

		return $convinence_fees;
	}

	/**
	 * Balu A
	 * Get markup based on different modules
	 * @return array('value' => 0, 'type' => '')
	 */
	function get_markup($module_name)
	{
		$markup_data = '';
		switch ($module_name) {
			case 'flight' : $markup_data = $this->airline_markup();
			break;
			case 'hotel' : $markup_data = $this->hotel_markup();
			break;
			case 'bus' : $markup_data = $this->bus_markup();
			break;
			case 'car' : $markup_data = $this->car_markup();
			break;
			case 'sightseeing' : $markup_data = $this->sightseeing_markup();
			break;
			case 'transfer' : $markup_data = $this->transfer_markup();
			break;

			default : $markup_data = array('value' => 0, 'type' => '');
			break;
		}
		return $markup_data;
	}

	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function airline_markup()
	{
		//get generic only if specific is not available
		if (empty($this->airline_markup) == true) {
			/*$response['specific_markup_list'] = $this->specific_domain_markup('b2b_flight');//FIXME:Agent-wise Markup Check the Query--Balu A
			if (valid_array($response['specific_markup_list']) == false) {
				$response['generic_markup_list'] = $this->generic_domain_markup('b2b_flight');
			}*/
			$response['specific_markup_list'] = $this->specific_airline_markup('b2b_flight');//Airline-Wise Markup
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_flight');
			$this->airline_markup = $response;
		} else {
			$response = $this->airline_markup;
		}
		return $response;
	}

	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function hotel_markup()
	{
		if (empty($this->hotel_markup) == true) {
			$response['specific_markup_list'] = $this->specific_domain_markup('b2b_hotel');
			if (valid_array($response['specific_markup_list']) == false) {
				$response['generic_markup_list'] = $this->generic_domain_markup('b2b_hotel');
			}
			$this->hotel_markup = $response;
		} else {
			$response = $this->hotel_markup;
		}
		return $response;
	}

	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function bus_markup()
	{
		if (empty($this->bus_markup) == true) {
			$response['specific_markup_list'] = $this->specific_domain_markup('b2b_bus');
			if (valid_array($response['specific_markup_list']) == false) {
				$response['generic_markup_list'] = $this->generic_domain_markup('b2b_bus');
			}
			$this->bus_markup = $response;
		} else {
			$response = $this->bus_markup;
		}
		return $response;
	}
	/**
	 * Anitha G
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function car_markup()
	{
		if (empty($this->car_markup) == true) {
			$response['specific_markup_list'] = $this->specific_domain_markup('b2b_car');
			if (valid_array($response['specific_markup_list']) == false) {
				$response['generic_markup_list'] = $this->generic_domain_markup('b2b_car');
			}
			$this->car_markup = $response;
		} else {
			$response = $this->car_markup;
		}
		return $response;
	}
	/**
	 * Elavarasi
	 * Manage domain markup for provab - Domain wise and module wise
	 */

	function sightseeing_markup(){
		if (empty($this->sightseeing_markup) == true) {
			$response['specific_markup_list'] = $this->specific_domain_markup('b2b_sightseeing');
			if (valid_array($response['specific_markup_list']) == false) {
				$response['generic_markup_list'] = $this->generic_domain_markup('b2b_sightseeing');
			}
			$this->sightseeing_markup = $response;
		} else {
			$response = $this->sightseeing_markup;
		}
		return $response;
	}
	/**
	 * Elavarasi
	 * Manage domain markup for provab - Domain wise and module wise
	 */

	function transfer_markup(){
		if (empty($this->transfer_markup) == true) {
			$response['specific_markup_list'] = $this->specific_domain_markup('b2b_transferv1');
			if (valid_array($response['specific_markup_list']) == false) {
				$response['generic_markup_list'] = $this->generic_domain_markup('b2b_transferv1');
			}
			$this->transfer_markup = $response;
		} else {
			$response = $this->transfer_markup;
		}
		return $response;
	}
	/**
	 * Balu A
	 * Get generic markup based on the module type
	 * @param $module_type
	 * @param $markup_level
	 */
	function generic_domain_markup($module_type)
	{
		$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
		FROM markup_list AS ML where ML.value != "" and ML.module_type = "'.$module_type.'" and
		ML.markup_level = "'.$this->markup_level.'" and ML.type="generic" and ML.domain_list_fk='.get_domain_auth_id();
		$generic_data_list = $this->db->query($query)->result_array();
		return $generic_data_list;
	}

	/**
	 * Balu A
	 * Get specific markup based on module type
	 * @param string $module_type	Name of the module for which the markup has to be returned
	 * @param string $markup_level	Level of markup
	 */
	function specific_domain_markup($module_type)
	{
		$query = 'SELECT
		ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
		FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
		ML.module_type = "'.$module_type.'" and ML.markup_level = "'.$this->markup_level.'" and DL.origin=ML.domain_list_fk and ML.type="specific"
		and ML.domain_list_fk != 0 and ML.reference_id='.get_domain_auth_id().' and ML.domain_list_fk = '.get_domain_auth_id().' order by DL.created_datetime DESC';
		$specific_data_list = $this->db->query($query)->result_array();
		return $specific_data_list;
	}
	/**
	 *  Balu A
	 * Get specific markup based on module type
	 * @param string $module_type	Name of the module for which the markup has to be returned
	 * @param string $markup_level	Level of markup
	 */
	function specific_airline_markup($module_type)
	{
		$markup_list = array();
		$query = 'SELECT AL.origin AS airline_origin, AL.name AS airline_name, AL.code AS airline_code,
		ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type, ML.markup_currency AS markup_currency
		FROM airline_list AS AL JOIN markup_list AS ML where ML.value != "" and
		ML.module_type = "'.$module_type.'" and ML.markup_level = "'.$this->markup_level.'" and AL.origin=ML.reference_id and ML.type="specific"
		and ML.domain_list_fk != 0  and ML.domain_list_fk='.get_domain_auth_id().' order by AL.name ASC';
		$specific_data_list = $this->db->query($query)->result_array();
		if (valid_array($specific_data_list)) {
			foreach ($specific_data_list as $__k => $__v) {
				$markup_list[$__v['airline_code']] = $__v;
			}
		}
		return $markup_list;
	}
	/**
	 * update domain balance details
	 * @param number $domain_origin	doamin unique key
	 * @param number $amount		amount to be added or deducted(-100 or +100)
	 */
	function update_domain_balance($domain_origin, $amount)
	{
		$current_balance = 0;
		$cond = array('origin' => intval($domain_origin));
		$details = $this->custom_db->single_table_records('domain_list', 'balance', $cond);
		if ($details['status'] == true) {
			$details['data'][0]['balance'] = $current_balance = ($details['data'][0]['balance'] + $amount);
			$this->custom_db->update_record('domain_list', $details['data'][0], $cond);
		}
		return $current_balance;
	}

	/**
	 * Log XML For Provab Security
	 * @param string $operation_name
	 * @param string $app_reference
	 * @param string $module
	 * @param json 	 $request
	 * @param json	 $response
	 */
	public function provab_xml_logger($operation_name, $app_reference, $module, $request, $response)
	{
		$data['operation_name'] = $operation_name;
		$data['app_reference'] = $app_reference;
		$data['module'] = $module;
		if (is_array($request)) {
			$request = json_encode($request);
		}
		if (is_array($response)) {
			$response = json_encode($response);
		}
		$data['request'] = $request;
		$data['response'] = $response;
		$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$data['created_datetime'] = date('Y-m-d H:i:s');

		$this->custom_db->insert_record('provab_xml_logger', $data);
	}


	/**
	 * Balu A
	 * Get Commission based on different modules
	 * @return array('value' => 0, 'type' => '')
	 */
	function get_commission($module_name)
	{
		
		$commission_data = '';
		switch ($module_name) {
			case 'flight' : $commission_data = $this->airline_commission();
			break;
			case 'bus' : $commission_data = $this->bus_commission();
			break;
			case 'sightseeing':$commission_data = $this->sightseeing_commission();
			break;
			case 'transfer':$commission_data = $this->transfer_commission();
			break;
		}
		return $commission_data;
	}

	/**
	 * Balu A
	 * Manage domain Commission for current domain
	 */
	function airline_commission()
	{
		if (empty($this->airline_commission) == true) {
			$response['admin_commission_list'] = $this->admin_b2b_airline_commission_list();
			$this->airline_commission = $response;
		} else {
			$response = $this->airline_commission;
		}
		return $response;
	}

	/**
	 * Balu A
	 * Manage domain Commission for current domain
	 */
	function bus_commission()
	{
		if (empty($this->bus_commission) == true) {
			$response['admin_commission_list'] = $this->admin_b2b_bus_commission_list();
			$this->bus_commission = $response;
		} else {
			$response = $this->bus_commission;
		}
		return $response;
	}
	/**
	 * Elavarasi
	 * Manage domain Commission for current domain
	 */

	function sightseeing_commission(){
		if (empty($this->sightseeing_commission) == true) {
			$response['admin_commission_list'] = $this->admin_b2b_sightseeing_commission_list();
			$this->sightseeing_commission = $response;
		} else {
			$response = $this->sightseeing_commission;
		}
		return $response;
	}
	function transfer_commission(){
		if (empty($this->transfer_commission) == true) {
			$response['admin_commission_list'] = $this->admin_b2b_transfer_commission_list();
			$this->transfer_commission = $response;
		} else {
			$response = $this->transfer_commission;
		}
		return $response;
	}
	/**
	 * Get commission list data for admin
	 */
	function admin_b2b_airline_commission_list()
	{
                $commission=array(); 
		$domain_origin = get_domain_auth_id();
		$query = 'select value,value_type, commission_currency From b2b_flight_commission_details where
		agent_fk IN (0, '.intval($this->entity_user_id).') AND domain_list_fk = '.$domain_origin.' ORDER BY type DESC';
                $com = $this->db->query($query)->row_array();
		if($com['value']==0)
		{
                   $query_gen = 'select value,value_type, commission_currency From b2b_flight_commission_details where
		   agent_fk IN (0) AND domain_list_fk = '.$domain_origin.' and type="generic" ORDER BY type DESC';
                   $com = $this->db->query($query_gen)->row_array();
		}
 

                $this->value_type_to_lower_case($com);
		return $com;
	}


	/**
	 * Get commission list data for admin
	 */
	function admin_b2b_bus_commission_list()
	{
		$domain_origin = get_domain_auth_id();
		$query = 'select value, value_type, commission_currency, commission_currency as def_currency, value as def_value From b2b_bus_commission_details where
		agent_fk IN (0, '.intval($this->entity_user_id).') AND domain_list_fk = '.$domain_origin.' ORDER BY type DESC';
		$com = $this->db->query($query)->row_array();
		$this->value_type_to_lower_case($com);
		return $com;
	}
	/**
	 * Get commission list data for admin
	 */
	function admin_b2b_sightseeing_commission_list()
	{
		$domain_origin = get_domain_auth_id();
		$query = 'select value, value_type, commission_currency, commission_currency as def_currency, value as def_value From b2b_sightseeing_commission_details where
		agent_fk IN (0, '.intval($this->entity_user_id).') AND domain_list_fk = '.$domain_origin.' ORDER BY type DESC';
		$com = $this->db->query($query)->row_array();
		$this->value_type_to_lower_case($com);
		return $com;
	}

	/**
	 * Get commission list data for admin
	 */
	function admin_b2b_transfer_commission_list()
	{
		$domain_origin = get_domain_auth_id();
		$query = 'select value, value_type, commission_currency, commission_currency as def_currency, value as def_value From b2b_transfer_commission_details where
		agent_fk IN (0, '.intval($this->entity_user_id).') AND domain_list_fk = '.$domain_origin.' ORDER BY type DESC';
		$com = $this->db->query($query)->row_array();
		$this->value_type_to_lower_case($com);
		return $com;
	}


	/**
	 * Used only to lower the value type field
	 * @param array $com
	 */
	private function value_type_to_lower_case(& $row)
	{
		if (isset($row['value_type']) == true) {
			$row['value_type'] = strtolower($row['value_type']);
		} else {
			$row['value'] = 0;
			$row['value_type'] = 'plus';
			$row['commission_currency'] = MARKUP_CURRENCY;
		}
	}

}

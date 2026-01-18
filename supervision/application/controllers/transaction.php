<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-08-17
* @package: 
* @Description: 
* @version: 2.0
**/
class Transaction extends MY_Controller
{
	public $entity_user_id;
	public $entity_domain_id;
	public $entity_uuid;
	public $entity_user_type;
	public $entity_email;
	public $enumeration;
	public $entity_name;
	public $entity_address;
	public $entity_phone;
	public $entity_country_code;
	public $entity_status;
	public $entity_date_of_birth;
	public $entity_image;
	public $entity_creation;
	public $entity_gst_number;
	public $transaction_model;
	public $domain_management_model;
	public $transferv1_model;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaction_model');
		$this->load->model('domain_management_model');
	}
	/**
	 * Show Transaction Logs to user
	 * @param number $offset
	 */
	function logs($offset = 0)
	{
		$get_data = $this->input->get();
		// $offset = $get_data['page'] ? intval($get_data['page']) : 0;
		$condition = array();
		$page_data = array();
		//From-Date and To-Date
		$from_date = trim(@$get_data['created_datetime_from'] ?? '');
		$to_date = trim(@$get_data['created_datetime_to'] ?? '');
		//Auto swipe date
		if (empty($from_date) == false && empty($to_date) == false) {
			$valid_dates = auto_swipe_dates($from_date, $to_date);
			$from_date = $valid_dates['from_date'];
			$to_date = $valid_dates['to_date'];
		}
		if (intval(@$get_data['agent_id']) > 0 && (@$get_data['agent_id'] != 'all')) {
			$condition[] = array('U.user_id', '=', intval($get_data['agent_id']));
		}
		if (empty($from_date) == false) {
			$ymd_from_date = date('Y-m-d', strtotime($from_date));
			$condition[] = array('TL.created_datetime', '>=', $this->db->escape($ymd_from_date));
		}
		if (empty($to_date) == false) {
			$ymd_to_date = date('Y-m-d', strtotime($to_date));
			$condition[] = array('TL.created_datetime', '<=', $this->db->escape($ymd_to_date));
		}
		if (@$get_data['transaction_type'] != '') {
			$condition[] = array('TL.transaction_type', '=', $this->db->escape($get_data['transaction_type']));
		}
		if (@$get_data['app_reference'] != '') {
			$condition[] = array('TL.app_reference', '=', $this->db->escape($get_data['app_reference']));
		}
		if (@$get_data['agent_id'] == 'all') {
			$condition['all'] = true;
		}
		$this->load->library('booking_data_formatter');
		$total_records = $this->transaction_model->logs($condition, true);
		$transaction_details = $this->transaction_model->logs($condition, false, $offset, RECORDS_RANGE_3);
		$transaction_details = $this->booking_data_formatter->format_recent_transactions($transaction_details, 'b2c');
		$page_data['table_data'] = $transaction_details['data']['transaction_details'];
		

		$this->load->library('pagination');
        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/transaction/logs/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $page_data['total_rows'] = $config['total_rows'] = $total_records;
        $config['per_page'] = RECORDS_RANGE_2;
        $this->pagination->initialize($config);

		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		// get active agent list
		$agent_list['data'] = $this->domain_management_model->agent_list();
		$page_data['agent_list'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_list);
		$this->template->view('transaction/logs', $page_data);
	}
	/*
	*Export logs to Excel and pdf Formats
	*/
	public function export_account_ledger($op = '')
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();
		//From-Date and To-Date
		$from_date = trim(@$get_data['created_datetime_from'] ?? '');
		$to_date = trim(@$get_data['created_datetime_to'] ?? '');
		//Auto swipe date
		if (empty($from_date) == false && empty($to_date) == false) {
			$valid_dates = auto_swipe_dates($from_date, $to_date);
			$from_date = $valid_dates['from_date'];
			$to_date = $valid_dates['to_date'];
		}
		if (intval(@$get_data['agent_id']) > 0) {
			$condition[] = array('U.user_id', '=', intval($get_data['agent_id']));
		}
		if (empty($from_date) == false) {
			$ymd_from_date = date('Y-m-d', strtotime($from_date));
			$condition[] = array('TL.created_datetime', '>=', $this->db->escape($ymd_from_date));
		}
		if (empty($to_date) == false) {
			$ymd_to_date = date('Y-m-d', strtotime($to_date));
			$condition[] = array('TL.created_datetime', '<=', $this->db->escape($ymd_to_date));
		}
		if (@$get_data['transaction_type'] != '') {
			$condition[] = array('TL.transaction_type', '=', $this->db->escape($get_data['transaction_type']));
		}
		if (@$get_data['app_reference'] != '') {
			$condition[] = array('TL.app_reference', '=', $this->db->escape($get_data['app_reference']));
		}
		if (@$get_data['agent_id'] == 'all') {
			$condition['all'] = true;
		}
		$this->load->library('booking_data_formatter');
		$transaction_details = $this->transaction_model->logs($condition, false);
		$transaction_details = $this->booking_data_formatter->format_recent_transactions($transaction_details, 'b2c');
		// debug($transaction_details);exit;
		$export_data = @$transaction_details['data']['transaction_details'];
		if ($op == 'excel') { // excel export
			$headings = array(
				'a1' => 'Sl. No.',
				'b1' => 'Agent',
				'c1' => 'Transaction Date',
				'd1' => 'Reference Number',
				'e1' => 'Transaction Type',
				'f1' => 'Amount',
				'g1' => 'Currency',
				'h1' => 'Description'
			);
			// field names in data set 
			$fields = array(
				'a' => '', // empty for sl. no.
				'b' => 'agent_name',
				'c' => 'created_datetime',
				'd' => 'app_reference',
				'e' => 'transaction_type',
				'f' => 'grand_total',
				'g' => 'currency',
				'h' => 'remarks'
			);
			$excel_sheet_properties = array(
				'title' => 'Transaction_Logs_' . date('d-M-Y'),
				'creator' => 'Provab',
				'description' => 'Transaction Logs of All Clients',
				'sheet_title' => 'Transaction Logs'
			);
			$this->load->library('provab_excel'); // we need this provab_excel library to export excel.
			$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
		} else { // pdf export 
			//debug($export_data); die();
			$col = array(
				'agent_name' => 'Agent',
				'created_datetime' => 'Transaction Date',
				'app_reference' => 'Reference Number',
				'transaction_type' => 'Transaction Type',
				'grand_total' => 'Amount',
				'currency' => 'Currency',
				'remarks' => 'Description'
			);
			$pdf_data = format_pdf_data($export_data, $col);
			$this->load->library('provab_pdf');
			$get_view = $this->template->isolated_view('report/table', $pdf_data);
			$this->provab_pdf->create_pdf($get_view, 'D', 'Account_Ledger');
			exit();
		}
	}
	/**
	 *
	 */
	function search_history()
	{
		$active_domain_modules = $this->active_domain_modules;
		/**
		 * Search History - Start
		 */
		$time_line_interval = get_month_names();
		$monthly_series_data = array();
		$page_data['year_start'] = $year_start = date('Y');
		$page_data['year_end'] = $year_end = date('Y', strtotime('+1 year'));
		$daily_time_line_interval = get_days_of_month();
		$daily_series_data = array();
		$year = date('Y');
		$month = date('m');
		if (is_active_airline_module()) {
			array_push($monthly_series_data, $this->monthly_flight_search_history_log($year_start, $year_end));
			array_push($daily_series_data, $this->daily_flight_search_history_log($year, $month));
			$page_data['flight_top_search'] = json_encode($this->flight_top_search($year_start, $year_end));
		}
		if (is_active_hotel_module()) {
			array_push($monthly_series_data, $this->monthly_hotel_search_history_log($year_start, $year_end));
			array_push($daily_series_data, $this->daily_hotel_search_history_log($year, $month));
			$page_data['hotel_top_search'] = json_encode($this->hotel_top_search($year_start, $year_end));
		}
		if (is_active_bus_module()) {
			array_push($monthly_series_data, $this->monthly_bus_search_history_log($year_start, $year_end));
			array_push($daily_series_data, $this->daily_bus_search_history_log($year, $month));
			$page_data['bus_top_search'] = json_encode($this->bus_top_search($year_start, $year_end));
		}
		if (is_active_sightseeing_module()) {
			array_push($monthly_series_data, $this->monthly_sightseeing_search_history_log($year_start, $year_end));
			array_push($daily_series_data, $this->daily_activity_search_history_log($year, $month));
			$page_data['activities_top_search'] = json_encode($this->sightseeing_top_search($year_start, $year_end));
		}
		if (is_active_transferv1_module()) {
			array_push($monthly_series_data, $this->monthly_transfer_search_history_log($year_start, $year_end));
			array_push($daily_series_data, $this->daily_transfer_search_history_log($year, $month));
			$page_data['transfers_top_search'] = json_encode($this->transfer_top_search($year_start, $year_end));
		}
		$page_data['monthly_time_line_interval'] = json_encode($time_line_interval);
		$page_data['monthly_series_data'] = json_encode($monthly_series_data);
		$page_data['daily_time_line_interval'] = json_encode($daily_time_line_interval);
		$page_data['daily_series_data'] = json_encode($daily_series_data);
		/**
		 * Search History - End
		 */
		$this->template->view('transaction/search_history', $page_data);
	}
	private function daily_flight_search_history_log($year, $month)
	{
		$this->load->model('flight_model');
		$data['name'] = 'Flight';
		$temp_data = $this->flight_model->daily_search_history($year, $month);
		$data['data'] = $this->distribute_daily_values($temp_data, $year, $month);
		// debug($data);exit;
		$data['color'] = '#0073b7';
		return $data;
	}
	private function daily_hotel_search_history_log($year, $month)
	{
		$this->load->model('hotel_model');
		$data['name'] = 'Hotel';
		$temp_data = $this->hotel_model->daily_search_history($year, $month);
		$data['data'] = $this->distribute_daily_values($temp_data, $year, $month);
		// debug($data);exit;
		$data['color'] = '#00a65a';
		return $data;
	}
	private function daily_bus_search_history_log($year, $month)
	{
		$this->load->model('bus_model');
		$data['name'] = 'Bus';
		$temp_data = $this->bus_model->daily_search_history($year, $month);
		$data['data'] = $this->distribute_daily_values($temp_data, $year, $month);
		// debug($data);exit;
		$data['color'] = '#dd4b39';
		return $data;
	}
	private function daily_activity_search_history_log($year, $month)
	{
		$this->load->model('sightseeing_model');
		$data['name'] = 'Activities';
		$temp_data = $this->sightseeing_model->daily_search_history($year, $month);
		// debug($temp_data);exit;
		$data['data'] = $this->distribute_daily_values($temp_data, $year, $month);
		// debug($data);exit;
		$data['color'] = '#ff9800';
		return $data;
	}
	private function daily_transfer_search_history_log($year, $month)
	{
		$this->load->model('transferv1_model');
		$data['name'] = 'Transfers';
		$temp_data = $this->transferv1_model->daily_search_history($year, $month);
		$data['data'] = $this->distribute_daily_values($temp_data, $year, $month);
		// debug($data);exit;
		$data['color'] = '#456F13';
		return $data;
	}
	private function distribute_daily_values($d_fill, $year, $month)
	{
		$d_fill = $this->index_day_number($d_fill);
		// debug($d_fill);exit;
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$data = array();
		for ($i = 1; $i <= $days_in_month; $i++) {
			if (isset($d_fill[$i])) {
				$data[] = intval($d_fill[$i]['total_search']);
			} else {
				$data[] = 0;
			}
		}
		// debug($data);exit;
		return $data;
	}
	function index_day_number($data)
	{
		$indexed_data = array();
		foreach ($data as $row) {
			$indexed_data[$row['day_number']] = $row;
		}
		return $indexed_data;
	}
	function top_destinations()
	{
		$active_domain_modules = $this->active_domain_modules;
		/**
		 * Search History - Start
		 */
		$page_data['year_start'] = $year_start = date('Y');
		$page_data['year_end'] = $year_end = date('Y', strtotime('+1 year'));
		if (is_active_airline_module()) {
			$page_data['flight_top_search'] = json_encode($this->flight_top_search($year_start, $year_end));
		}
		if (is_active_hotel_module()) {
			$page_data['hotel_top_search'] = json_encode($this->hotel_top_search($year_start, $year_end));
		}
		if (is_active_bus_module()) {
			$page_data['bus_top_search'] = json_encode($this->bus_top_search($year_start, $year_end));
		}
		if (is_active_sightseeing_module()) {
			$page_data['sightseeing_top_search'] = json_encode($this->sightseeing_top_search($year_start, $year_end));
		}
		if (is_active_transferv1_module()) {
			$page_data['transfer_top_search'] = json_encode($this->transfer_top_search($year_start, $year_end));
		}
		/**
		 * Search History - End
		 */
		$this->template->view('transaction/top_destinations', $page_data);
	}
	private function flight_top_search($year_start, $year_end)
	{
		$this->load->model('flight_model');
		$temp_data = $this->flight_model->top_search($year_start, $year_end);
		return $this->group_top_search_data($temp_data);
	}
	private function hotel_top_search($year_start, $year_end)
	{
		$this->load->model('hotel_model');
		$temp_data = $this->hotel_model->top_search($year_start, $year_end);
		return $this->group_top_search_data($temp_data);
	}
	private function sightseeing_top_search($year_start, $year_end)
	{
		$this->load->model('sightseeing_model');
		$temp_data = $this->sightseeing_model->top_search($year_start, $year_end);
		return $this->group_top_search_data($temp_data);
	}
	private function transfer_top_search($year_start, $year_end)
	{
		$this->load->model('transferv1_model');
		$temp_data = $this->transferv1_model->top_search($year_start, $year_end);
		return $this->group_top_search_data($temp_data);
	}
	private function bus_top_search($year_start, $year_end)
	{
		$this->load->model('bus_model');
		$temp_data = $this->bus_model->top_search($year_start, $year_end);
		return $this->group_top_search_data($temp_data);
	}
	private function monthly_flight_search_history_log($year_start, $year_end)
	{
		$this->load->model('flight_model');
		$data['name'] = 'Flight';
		$temp_data = $this->flight_model->monthly_search_history($year_start, $year_end);
		$data['data'] = $this->distribute_monthly_values($temp_data);
		$data['color'] = '#0073b7';
		return $data;
	}
	private function monthly_hotel_search_history_log($year_start, $year_end)
	{
		$this->load->model('hotel_model');
		$data['name'] = 'Hotel';
		$temp_data = $this->hotel_model->monthly_search_history($year_start, $year_end);
		$data['data'] = $this->distribute_monthly_values($temp_data);
		$data['color'] = '#00a65a';
		return $data;
	}
	private function monthly_bus_search_history_log($year_start, $year_end)
	{
		$this->load->model('bus_model');
		$data['name'] = 'Bus';
		$temp_data = $this->bus_model->monthly_search_history($year_start, $year_end);
		$data['data'] = $this->distribute_monthly_values($temp_data);
		$data['color'] = '#dd4b39';
		return $data;
	}
	private function monthly_sightseeing_search_history_log($year_start, $year_end)
	{
		$this->load->model('sightseeing_model');
		$data['name'] = 'Activities';
		$temp_data = $this->sightseeing_model->monthly_search_history($year_start, $year_end);
		$data['data'] = $this->distribute_monthly_values($temp_data);
		$data['color'] = '#ff9800';
		return $data;
	}
	private function monthly_transfer_search_history_log($year_start, $year_end)
	{
		$this->load->model('transferv1_model');
		$data['name'] = 'Transfers';
		$temp_data = $this->transferv1_model->monthly_search_history($year_start, $year_end);
		$data['data'] = $this->distribute_monthly_values($temp_data);
		$data['color'] = '#456F13';
		return $data;
	}
	private function distribute_monthly_values($m_fill)
	{
		$m_fill = index_month_number($m_fill);
		$i = 0;
		$data = array();
		for ($i = 0; $i <= 11; $i++) {
			if (isset($m_fill[$i]) == true) {
				$data[] = intval($m_fill[$i]['total_search']);
			} else {
				$data[] = 0;
			}
		}
		return $data;
	}
	private function group_top_search_data($data)
	{
		$result = array();
		if (valid_array($data)) {
			foreach ($data as $k => $v) {
				$result[] = array($v['label'], intval($v['total_search']));
			}
		}
		return $result;
	}
}

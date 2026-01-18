<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-09-16
* @package: 
* @Description: 
* @version: 2.0
**/
class Report extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		// $this->load->model('bus_model');
		$this->load->model('hotel_model');
		$this->load->model('flight_model');
		$this->load->model('car_model');
		$this->load->library('booking_data_formatter');
		$this->load->model('sightseeing_model');
		$this->load->model('transferv1_model');
		$this->load->model('transfer_model');

	}

	function monthly_booking_report()
	{
		$this->template->view('report/monthly_booking_report');
	}
	function index(){
		$this->flight($offset=0);
	}

function bus($offset=0)
{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.pnr like "%'.$filter_report_data.'%")';
			$total_records = $this->bus_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->bus_model->filter_booking_report($search_filter_condition, false, $offset, RECORDS_RANGE_2);
		} else {
			$total_records = $this->bus_model->booking($condition, true);
			$table_data = $this->bus_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/bus/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$this->template->view('report/bus', $page_data);
	}

	function hotel($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.confirmation_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->hotel_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->hotel_model->filter_booking_report($search_filter_condition, false, $offset, RECORDS_RANGE_2);
		} else {
			$total_records = $this->hotel_model->booking($condition, true);
			$table_data = $this->hotel_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}

		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/hotel/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$this->template->view('report/hotel', $page_data);
	}

	/**
	 * Flight Report
	 * @param $offset
	 */
	function flight($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(TD.app_reference like "%'.$filter_report_data.'%" OR TD.pnr like "%'.$filter_report_data.'%")';
			$total_records = $this->flight_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->flight_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->flight_model->booking($condition, true);
			$table_data = $this->flight_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/flight/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/airline', $page_data);
	}

	/************************************** CAR REPORT STARTS ***********************************/
	/**
	 * Cae Report
	 * @param $offset
	 */
	function car_old($offset=0)
	{
		validate_user_login();
		$condition = array();
		$total_records = $this->car_model->booking($condition, true);
		$table_data = $this->car_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_car_booking_datas($table_data, 'b2c');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/car/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		// debug($page_data);exit;
		$this->template->view('report/car', $page_data);
	}

	function car($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];

		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.booking_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->car_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->car_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->car_model->booking($condition, true);
			$table_data = $this->car_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		//debug($table_data);exit;
		$table_data = $this->booking_data_formatter->format_car_booking_datas($table_data, 'b2b');

		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/car/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/car', $page_data);
	}

	/**
	 * Sightseeing Report
	 * @param $offset
	 */
	function activities($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.booking_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->sightseeing_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->sightseeing_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->sightseeing_model->booking($condition, true);
			$table_data = $this->sightseeing_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, 'b2b');

		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/sightseeing/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/sightseeing', $page_data);
	}
	/**
	 * Transfers Report
	 * @param $offset
	 */
	function transfers_old($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.booking_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->transferv1_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->transferv1_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->transferv1_model->booking($condition, true);
			$table_data = $this->transferv1_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}

		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'b2b');

		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/transfers/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/transfers', $page_data);
	}

	function transfers($offset=0)
	{
		//error_reporting(E_ALL);
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = @$filter_data['from_date'];
		$page_data['to_date'] = @$filter_data['to_date'];
		$condition = @$filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.booking_reference like "%'.$filter_report_data.'%")';

			$total_records = $this->transfer_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->transfer_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->transfer_model->booking($condition, true);
			$table_data = $this->transfer_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}

		$table_data = $this->booking_data_formatter->format_transfer_booking_data($table_data, 'b2b');

		$page_data['table_data'] = $table_data['data'];
		//debug($page_data);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/transfers/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/transfers', $page_data);
	}

	function package($offset=0)
	{
		redirect(base_url());
	}
	/**
	 * Balu A
	 */
	private function format_basic_search_filters()
	{
		$get_data = $this->input->get();
		if(valid_array($get_data) == true) {
			$filter_condition = array();
			//From-Date and To-Date
			$from_date = trim(@$get_data['from_date'] ?? '');
			$to_date = trim(@$get_data['to_date'] ?? '');
			//Auto swipe date
			if(empty($from_date) == false && empty($to_date) == false)
			{
				$valid_dates = auto_swipe_dates($from_date, $to_date);
				$from_date = $valid_dates['from_date'];
				$to_date = $valid_dates['to_date'];
			}
			if(empty($from_date) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.date('Y-m-d', strtotime($from_date)).'"');
			}
			if(empty($to_date) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '<=', '"'.date('Y-m-d', strtotime($to_date)).'"');
			}
			//App reference
			if(isset($get_data['app_reference']) == true && empty($get_data['app_reference']) == false) {
				$filter_condition[] = array('BD.app_reference', '=', '"'.trim($get_data['app_reference']).'"');
			}
			//Booking-Status
			if(isset($get_data['filter_booking_status']) == true && $get_data['filter_booking_status'] == 'BOOKING_CONFIRMED') {
				//Confirmed Booking
				$filter_condition[] = array('BD.status', '=', '"BOOKING_CONFIRMED"');
			} elseif (isset($get_data['filter_booking_status']) == true && $get_data['filter_booking_status'] == 'BOOKING_PENDING') {
				//Pending Booking
				$filter_condition[] = array('BD.status', '=', '"BOOKING_PENDING"');	
			} elseif (isset($get_data['filter_booking_status']) == true && $get_data['filter_booking_status'] == 'BOOKING_CANCELLED') {
				//Cancelled Booking
				$filter_condition[] = array('BD.status', '=', '"BOOKING_CANCELLED"');
			}
			//Today's Booking Data
			if(isset($get_data['today_booking_data']) == true && empty($get_data['today_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '=', '"'.date('Y-m-d').'"');
			}
			//Last day Booking Data
			if(isset($get_data['last_day_booking_data']) == true && empty($get_data['last_day_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '=', '"'.trim($get_data['last_day_booking_data']).'"');
			}
			//Previous Booking Data: last 3 days, 7 days, 15 days, 1 month and 3 month
			if(isset($get_data['prev_booking_data']) == true && empty($get_data['prev_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.trim($get_data['prev_booking_data']).'"');
			}
			if(isset($get_data['daily_sales_report']) == true && $get_data['daily_sales_report'] == ACTIVE) {
				$from_date = date('d-m-Y', strtotime('-1 day'));
				$to_date = date('d-m-Y');
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.date('Y-m-d', strtotime($from_date)).'"');
				$filter_condition[] = array('DATE(BD.created_datetime)', '<=', '"'.date('Y-m-d', strtotime($to_date)).'"');
			}
			return array('filter_condition' => $filter_condition, 'from_date' => $from_date, 'to_date' => $to_date);
		}
	}
	public function package_enquiries(){
		$this->load->model('Package_Model');
		$data ['enquiries'] = $this->Package_Model->gerEnquiryPackages ($this->entity_user_id);
		// debug($data);exit;
		$this->template->view ( 'report/package_enquiries', $data );
	}
	function update_pnr_details($app_reference, $booking_source,$booking_status){
		load_flight_lib($booking_source);
		$response = $this->flight_lib->update_pnr_details($app_reference);
		$get_pnr_updated_status = $this->flight_model->update_pnr_details($response,$app_reference, $booking_source,$booking_status);
		echo $get_pnr_updated_status;
	}
}
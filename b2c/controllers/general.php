<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author: Shaik Jani
 * @Email: shaik.jani@provabmail.com
 * @Date: 2025-03-25
 * @package: 
 * @Description: 
 * @version: 2.0
 **/
class General extends MY_Controller
{
  public $transfer_model;
  public function __construct()
  {
    parent::__construct();
    //$this->output->enable_profiler(TRUE);
    $this->load->model('user_model');
    $this->load->model('Package_Model');
    $this->load->model('custom_db');
  }


  /**
   * index page of application will be loaded here
   */
  public function index($default_view = '')
  {
    $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
    $data['packages'] = $this->Package_Model->getAllPackages();
    $data['countries'] = $this->Package_Model->getPackageCountries_new();
    $data['package_types'] = $this->Package_Model->getPackageTypes();

      $domain_type = 1;
    /* Banner_Images */
    $domain_origin = get_domain_auth_id();
    $page_data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1'), '', '100000000', array('banner_order' => 'ASC'));
    /* Package Data */
    $page_data['default_view'] = $default_view;
    $page_data['holiday_data'] = $data; //Package Data
    //debug($page_data['holiday_data']);exit;
    if (is_active_airline_module()) {
      $this->load->model('flight_model');
      $page_data['top_destination_flight'] = $this->flight_model->flight_top_destinations($domain_type);
       //debug($page_data['top_destination_flight']);exit;
    }
    /*   if (is_active_bus_module()) {
            $this->load->model('bus_model');
        } */
    if (is_active_sightseeing_module()) {
      $this->load->model('sightseeing_model');
      $page_data['top_destination_activities'] = $this->sightseeing_model->activity_top_destinations($domain_type);
      // $page_data['top_destination_activities'] = [];
    }
    // $page_data['top_cities_routes_flight'] = $this->flight_model->flight_top_cities_routes();
    if (is_active_hotel_module()) {
      $this->load->model('hotel_model');
      // $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations_old();
      $page_data['category_list'] = $this->hotel_model->getCategoryList();
      $page_data['hotel_top_destinations'] = $this->hotel_model->hotel_top_destinations($page_data['category_list'], $domain_type);
    }
    //debug($page_data['hotel_top_destinations']);exit;
    /*  if (is_active_car_module()) {
          $this->load->model('car_model');
         } */
    if (is_active_package_module()) {
      $this->load->model('package_model');
      $top_package = $this->package_model->get_package_top_destination($domain_type);
      $page_data['top_destination_package'] = $top_package['data'];
      $page_data['total'] = $top_package['total'];
    }
    $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
    $page_data['currency_obj'] = $currency_obj;
    $getSlideImages = $page_data['banner_images']['data'];
    $slideImageArray = array();
    foreach ($getSlideImages as $k) {
      $slideImageArray[] = array('image' => $GLOBALS['CI']->template->template_images() . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
    }
    $page_data['slideImageJson'] = $slideImageArray;
    $get_promocode_list = $this->get_promocode_list();
    $page_data['promo_code_list'] = $get_promocode_list;
    //for getting the headings
    $headings = $this->custom_db->single_table_records('home_page_headings', '*', array('status' => '1'));
    $headings_array = array();
    if ($headings['status'] == true) {
      foreach ($headings['data'] as $heading) {
        $headings_array[] = $heading['title'];
      }
    }
    $page_data['headings'] = $headings_array;
    $page_data['top_airlines'] = $this->custom_db->single_table_records('top_airlines', '*', array('status' => '1'));
    // $tour_styles = $this->custom_db->single_table_records('tour_styles', '*', array('status' => '1'));
    // $page_data['tour_styles'] = $tour_styles;
    // $domain_data = $this->custom_db->single_table_records('domain_list', '*', array('status' => '1'));
    // $page_data['domain_data'] = $domain_data;
    $page_data['faq_categories'] = $this->custom_db->single_table_records('cms_faqs_categories', '*', array('status' => '1'));
    $faq_data = $this->custom_db->get_result_by_query("SELECT * FROM `cms_faqs` WHERE `status` = 1 LIMIT 3");
    $page_data['faq_data'] = json_decode(json_encode($faq_data), true);
    $page_data['why_to_choose'] = $this->custom_db->single_table_records('why_choose_us', '*', array('status' => '1'),0,4);
    $page_data['top_hotels'] = $this->custom_db->single_table_records('dynamic_hotel_urls', '*', array('status' => '1'), 0, 50);
    $page_data['from_airport_names'] = $this->custom_db->get_custom_query('select distinct from_airport_name from top_flight_destinations where status=1 and domain=' . $domain_type . ' limit 0,50');
    $page_data['airlines_names'] = $this->custom_db->get_custom_query('select distinct airlines_name from top_flight_destinations where status=1 and domain=' . $domain_type . ' limit 0,50');
    $page_data['dynamic_hotel_urls'] = $this->custom_db->get_custom_query('select distinct city from dynamic_hotel_urls where status=1 limit 0,50');
    $top_destination_category_masters = $this->custom_db->get_custom_query('select * from top_destination_categories where status=1');
    $cats = [];
    if (!empty($top_destination_category_masters)) {
      foreach ($top_destination_category_masters as $top_destination_category_master) {
        if ($top_destination_category_master['module'] == 'flight') {
          $top_destination_category_master['top_destinations'] = $this->custom_db->get_custom_query('select * from top_flight_destinations where status=1 and category_id=' . $top_destination_category_master['origin'] . ' and domain=' . $domain_type . ' limit 0,50');
        } else if ($top_destination_category_master['module'] == 'hotel') {
          $top_destination_category_master['top_destinations'] = $this->custom_db->get_custom_query('select * from hotel_top_destinations where status=1 and category_id=' . $top_destination_category_master['origin'] . ' and domain=' . $domain_type . ' limit 0,50');
        }
        $cats[] = $top_destination_category_master;
      }
    }
    $page_data['top_destination_category_master'] = $cats;
    $this->db->select('*');
    $this->db->from('blog');
    $this->db->where('status', '1');
    $query = $this->db->get();
    $blog_list = $query->result_array();
    $page_data['blog_list'] = $blog_list;
    //debug($default_view);exit;
    $this->template->view('general/index', $page_data);
  }
  public function customer_support()
  {
    $page_data['title'] = 'Customer Support';
    $this->template->view('general/support', $page_data);
  }
  public function blog()
  {
    $blog_id = $this->uri->segment(2);
    $this->db->select('*');
    $this->db->from('blog');
    // $this->db->where('origin',$blog_id);
    $this->db->where('blog_url', $blog_id);
    $query = $this->db->get();
    $blog_data = $query->result_array();
    foreach ($blog_data as $key => $value) {
      $blog_data[$key]['images'] = $this->get_blog_image_data($value['origin']);
    }
    $page_data['blog_data'] = $blog_data;
    $this->db->select('*');
    $this->db->from('blog');
    $this->db->where('status', '1');
    $query = $this->db->get();
    $blog_list = $query->result_array();
    $page_data['blog_list'] = $blog_list;
    $this->template->view('general/blog', $page_data);
  }
  function get_blog_image_data($origin)
  {
    $this->db->select('*');
    $this->db->from('blog_images');
    $this->db->where('blog_id', $origin);
    $query = $this->db->get();
    $blog_images_data = $query->result_array();
    return $blog_images_data;
  }
  public function new_blog()
  {
    $module = $this->uri->segment(3);
    $this->db->select('*');
    $this->db->from('blog');
    if ($module == '') {
      $this->db->where('status', '1');
    } else {
      $this->db->where(array('module' => $module, 'status' => 1));
    }
    // $this->db->where('status','1');
    $this->db->order_by('origin', 'DESC');
    $query = $this->db->get();
    $blog_list = $query->result_array();
    foreach ($blog_list as $key => $value) {
      $blog_list[$key]['images'] = $this->get_blog_image_data($value['origin']);
    }
    $page_data['blog_list'] = $blog_list;
    $page_data['right_ads'] = $this->custom_db->get_custom_query("select * from label where display_side='Right' and status=1 and (display_page='blogs') and api_module = 'blogs' and (date='0000-00-00' OR date='" . date('Y-m-d') . "')");
    $this->template->view('general/new_blog', $page_data);
  }
  /**
   * Set Search id in cookie
   */
  private function save_search_cookie($module, $search_id)
  {
    $sparam = array();
    $sparam = $this->input->cookie('sparam', TRUE);
    if (empty($sparam) == false) {
      $sparam = unserialize($sparam);
    }
    $sparam[$module] = $search_id;
    $cookie = array(
      'name' => 'sparam',
      'value' => serialize($sparam),
      'expire' => '86500',
      'path' => PROJECT_COOKIE_PATH
    );
    $this->input->set_cookie($cookie);
  }
  /**
   * Pre Search For Flight
   */
  function pre_flight_search($search_id = '')
  {
    $search_params = $this->input->get();
    // debug($search_params);exit;
    //Global search Data
    $search_id = $this->save_pre_search(META_AIRLINE_COURSE);
    // debug($search_id);exit;
    $this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
    //Analytics
    $this->load->model('flight_model');
    $this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
    redirect('flight/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /**
   * Pre Search For Car
   */
  function pre_car_search($search_id = '')
  {
    $search_params = $this->input->get();
    // debug($search_params);exit;
    //Global search Data
    $search_id = $this->save_pre_search(META_CAR_COURSE);
    $this->save_search_cookie(META_CAR_COURSE, $search_id);
    //Analytics
    $this->load->model('car_model');
    $this->car_model->save_search_data($search_params, META_CAR_COURSE);
    redirect('car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /**
   * Pre Search For Hotel
   */
  function pre_hotel_search($search_id = '')
  {
    //Global search Data
    //debug($this->session);exit;
    $search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
    $this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);
    //Analytics
    $this->load->model('hotel_model');
    $search_params = $this->input->get();
    // debug($search_params);
    // exit;
    $this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);
    redirect('hotel/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /**
   * Pre Search For Bus
   */
  function pre_bus_search($search_id = '')
  {
    //Global search Data
    $search_id = $this->save_pre_search(META_BUS_COURSE);
    $this->save_search_cookie(META_BUS_COURSE, $search_id);
    //Analytics
    $this->load->model('bus_model');
    $search_params = $this->input->get();
    $this->bus_model->save_search_data($search_params, META_BUS_COURSE);
    redirect('bus/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /**
   * Pre Search For Packages
   */
  function pre_package_search($search_id = '')
  {
    //Global search Data
    $search_id = $this->save_pre_search(META_PACKAGE_COURSE);
    redirect('tours/search' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /**
   * Pre Search for SightSeen
   */
  function pre_sight_seen_search($search_id = '')
  {
    $search_id = $this->save_pre_search(META_SIGHTSEEING_COURSE);
    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
    //Analytics
    $this->load->model('sightseeing_model');
    $search_params = $this->input->get();
    // debug($search_params);
    // exit;
    $this->sightseeing_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
    redirect('sightseeing/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /*
  *Pre Transfer Search
  */
  function pre_transferv1_search($search_id = '')
  {
    $search_id = $this->save_pre_search(META_TRANSFERV1_COURSE);
    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
    //Analytics
    $this->load->model('transferv1_model');
    $search_params = $this->input->get();
    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
    redirect('transferv1/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }
  /**
   * Pre Search For Transfer
   */
  function pre_transfer_search_old($search_id = '')
  {
    //Global search Data
    //debug($this->session);exit;
    $search_id = $this->save_pre_search(META_TRANSFER_COURSE);
    $this->save_search_cookie(META_TRANSFER_COURSE, $search_id);
    //Analytics
    $this->load->model('transfer_model');
    $search_params = $this->input->get();
    $this->transfer_model->save_search_data($search_params, META_TRANSFER_COURSE);
    redirect('transfer/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
  }

     /**

     * Pre Search For Transfer

     */

    function pre_transfer_search($search_id = '') {

        //Global search Data

        //debug($this->session);exit;

        $search_id = $this->save_pre_search(META_TRANSFER_COURSE);

        $this->save_search_cookie(META_TRANSFER_COURSE, $search_id);



        //Analytics

        $this->load->model('transfer_model');

        $search_params = $this->input->get();



       $this->transfer_model->save_search_data($search_params, META_TRANSFER_COURSE);



        redirect('transfer/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);

    }


  /**
   * Pre Search used to save the data
   *
   */
  private function save_pre_search($search_type)
  {
    //Save data
    $search_params = $this->input->get();
    // debug($search_params);exit;
    $search_data = json_encode($search_params);
    $insert_id = $this->custom_db->insert_record('search_history', array('search_type' => $search_type, 'search_data' => $search_data, 'created_datetime' => date('Y-m-d H:i:s')));
    return $insert_id['insert_id'];
  }
  /**
   * oops page of application will be loaded here
   */
  public function ooops()
  {
    $this->template->view('utilities/404.php');
  }
  /*
     * Activating User Account.
     * Account get activated only when the url is clicked from the account_activation_mail
     */
  function activate_account_status()
  {
    $origin = $this->input->get('origin');
    $unsecure = substr($origin, 3);
    $secure_id = base64_decode($unsecure);
    $status = ACTIVE;
    $this->user_model->activate_account_status($status, $secure_id);
    redirect(base_url() . 'index.php?verification_success');
  }
  /**
   * Email Subscribtion
   *
   */
  public function email_subscription()
  {
    $data = $this->input->post();
    $mail = $data['subEmail'];
    $domain_key = get_domain_auth_id();
    $inserted_id = $this->user_model->email_subscribtion($mail, $domain_key);
    if (isset($inserted_id) && $inserted_id != "already") {
      $this->application_logger->email_subscription($mail);
      $pdata['status'] = 1;
      echo json_encode($pdata);
    } elseif ($inserted_id == "already") {
      $pdata['status'] = 0;
      echo json_encode($pdata);
    } else {
      $pdata['status'] = 2;
      echo json_encode($pdata);
    }
  }
  function cms($page_label)
  {
    $page_position = 'Bottom';
    if (isset($page_label)) {
      $data = $this->custom_db->single_table_records('cms_pages', 'page_title,page_description,page_seo_title,page_seo_keyword,page_seo_description', array('page_label' => $page_label, 'page_position' => $page_position, 'page_status' => 1));
      $this->template->view('cms/cms', $data);
    } else {
      redirect('general/index');
    }
  }
  function offline_payment()
  {
    $params = $this->input->post();
    $gotback = $this->user_model->offline_payment_insert($params);
    $url = base_url() . 'index.php/general/offline_approve/' . $gotback['refernce_code'];
    print_r(json_encode($gotback['refernce_code']));
  }
  function offline_approve($code)
  { //apporval by mail
    $result['data'] = $this->user_model->offline_approval($code);
    $this->template->view('general/pay', $result);
  }
  /**
   * Booking Not Allowed Popup
   */
  function booking_not_allowed()
  {
    $this->template->view('general/booking_not_allowed');
  }
  function test()
  {
    echo 'test function';
  }
  function update_citylist()
  {
    $total = 80;
    for ($num = 0; $num <= $total; $num++) {
      $city_response = file_get_contents(FCPATH . "test-export-2017-2-27/destinations-" . $num . ".json");
      $city_list = json_decode($city_response, true);
      // debug($city_list);exit;
      foreach ($city_list as $key => $value) {
        $insert_list['country_code'] = $value['country'];
        $insert_list['city_name'] = html_entity_decode($value['name']);
        $insert_list['city_code'] = $value['code'];
        $insert_list['parent_code'] = $value['parent'];
        $insert_list['latitude']  = $value['latitude'];
        $insert_list['longitude'] = $value['longitude'];
        $this->custom_db->insert_record('hotelspro_citylist', $insert_list);
      }
    }
  }
  //get promocode
  private function get_promocode_list()
  {
    $promocode_arr = array();
    $date = date('Y-m-d');
    $list = $this->custom_db->single_table_records('promo_code_list', '*', array('status' => ACTIVE, 'display_home_page' => 'Yes', 'expiry_date >=' => $date));
    if ($list['status'] == true) {
      $promocode_arr = $list['data'];
    }
    return $promocode_arr;
  }
  public function insert_api_data()
  {
    // $this->dec_insert_api_data();
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $api_data = $this->custom_db->single_table_records('email_configuration', '*');
    $secret_iv = PROVAB_SECRET_IV;
    // debug($api_data);exit;
    if ($api_data['status'] == true) {
      foreach ($api_data['data'] as $data) {
        if (!empty($data['username'])) {
          $md5_key = PROVAB_MD5_SECRET;
          $encrypt_key = PROVAB_ENC_KEY;
          $decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('" . $md5_key . "',512)) AS decrypt_data");
          $db_data = $decrypt_password->row();
          $secret_key = trim($db_data->decrypt_data);
          $key = hash('sha256', $secret_key);
          $iv = substr(hash('sha256', $secret_iv), 0, 16);
          $username = openssl_encrypt($data['username'], $encrypt_method, $key, 0, $iv);
          $username = base64_encode($username);
          $password = openssl_encrypt($data['password'], $encrypt_method, $key, 0, $iv);
          $password = base64_encode($password);
          $host = openssl_encrypt($data['host'], $encrypt_method, $key, 0, $iv);
          $host = base64_encode($host);
          $cc = openssl_encrypt($data['cc'], $encrypt_method, $key, 0, $iv);
          $cc = base64_encode($cc);
          $port = openssl_encrypt($data['port'], $encrypt_method, $key, 0, $iv);
          $port = base64_encode($port);
          $bcc = openssl_encrypt($data['bcc'], $encrypt_method, $key, 0, $iv);
          $bcc = base64_encode($bcc);
          $api_config_data['from'] = $data['from'];
          $api_config_data['domain_origin'] = $data['domain_origin'];
          $api_config_data['username'] = $username;
          $api_config_data['password'] = $password;
          $api_config_data['host'] = $host;
          $api_config_data['cc'] = $cc;
          $api_config_data['port'] = $port;
          $api_config_data['bcc'] = $bcc;
          $api_config_data['status'] = $data['status'];
          // debug($api_config_data);exit;
          $this->custom_db->insert_record('email_configuration_new', $api_config_data);
        }
      }
    }
    exit;
  }
  public function insert_api_urls()
  {
    // error_reporting(E_ALL);
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $api_urls = $this->custom_db->single_table_records('api_urls', '*');
    $secret_iv = PROVAB_SECRET_IV;
    if ($api_urls['status'] == true) {
      foreach ($api_urls['data'] as $data) {
        if (!empty($data)) {
          $md5_key = PROVAB_MD5_SECRET;
          $encrypt_key = PROVAB_ENC_KEY;
          $decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('" . $md5_key . "',512)) AS decrypt_data");
          $db_data = $decrypt_password->row();
          $secret_key = trim($db_data->decrypt_data);
          $key = hash('sha256', $secret_key);
          $iv = substr(hash('sha256', $secret_iv), 0, 16);
          $api_urls_data = openssl_encrypt($data['urls'], $encrypt_method, $key, 0, $iv);
          $urls_data = base64_encode($api_urls_data);
          $api_data['system'] = $data['system'];
          $api_data['urls'] = $urls_data;
          // debug($api_data);exit;
          $this->custom_db->insert_record('api_urls_new', $api_data);
        }
      }
    }
  }
  public function decrypt_api_urls()
  {
    // error_reporting(E_ALL);
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $api_urls = $this->custom_db->single_table_records('api_urls_new', '*');
    $secret_iv = PROVAB_SECRET_IV;
    if ($api_urls['status'] == true) {
      foreach ($api_urls['data'] as $data) {
        if (!empty($data)) {
          $md5_key = PROVAB_MD5_SECRET;
          $encrypt_key = PROVAB_ENC_KEY;
          $decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('" . $md5_key . "',512)) AS decrypt_data");
          $db_data = $decrypt_password->row();
          $secret_key = trim($db_data->decrypt_data);
          $key = hash('sha256', $secret_key);
          $iv = substr(hash('sha256', $secret_iv), 0, 16);
          $urls = openssl_decrypt(base64_decode($data['urls']), $encrypt_method, $key, 0, $iv);
          // debug($urls);exit;
        }
      }
    }
  }
  public function emailTest()
  {
    $this->load->library('provab_mailer');
    $mail_template = '<h1>test email </h1>';

    $mail_status = $this->provab_mailer->send_mail('jani.provab@gmail.com', 'testmail', $mail_template);

    debug($mail_status);
    exit;
  }
  public function tour_guide()
  {
    $this->template->isolated_view('general/tour_guide');
  }
  /* public function hotel_top_data(){
    if ($this->input->is_ajax_request()) {
        $cat = $this->input->post('cat');
        // Fetch data from the database based on category
        $this->db->select('*');
        $this->db->from('all_api_city_master');
        $this->db->where(array('top_destination' => '1', 'cat' => $cat));
        $query = $this->db->get();
        $result = $query->result_array();
        // Prepare HTML response
        $html = '';
        foreach ($result as $row) {
            $html .= '<div class="nopad cityimgdiv">';
            $html .= '<div class="col-sm-12">';
            $html .= '<div class="bg-clr">';
            $html .= '<div class="imgfortophotel">';
            $html .= '<input type="hidden" class="top_des_id" value="' . $row['origin'] . '">';
            $html .= '<input type="hidden" class="top-des-val hand-cursor" value="' . hotel_suggestion_value($row['city_name'], $row['country_name']) . '">';
            $html .= '<img class="imgsizetophotel" src="/dev/extras/custom/TMX2783081694775862/images/' . $row['image'] . '" alt="Paris">';
            $html .= '</div>';
            $html .= '<div class="cptn-tophotl">';
            $html .= '<div class="top-ttle">';
            $html .= '<h3>' . $row['hotel_name'] . '</h3>';
            $html .= '<div class="topicon">';
            // Add stars or other icons
            $html .= '</div>';
            $html .= '<div class="topicon">';
            $html .= '<i class="fas fa-star"></i>';
            $html .= '<i class="fas fa-star"></i>';
            $html .= '<i class="fas fa-star"></i>';
            $html .= '<i class="fas fa-star"></i>';
            $html .= '<i class="fas fa-star"></i>';
            $html .= '</div>';
            $html .= '<div class="top_review">';
            $html .= '<div class="tophotel__score"><span>' . $row['rating'] . '</span><span>/</span><span>5</span></div>';
            $html .= '<div class="hotel-comments">' . $row['reviews'] . ' reviews</div>';
            $html .= '</div>';
            $html .= '<div class="rateinfo">';
            $html .= '<div class="hotel-item_price-box">';
            $html .= '<div class="price-top">';
            $html .= '<span class="price_from">From</span>';
            $html .= '<span class="price_content">' . $row['price'] . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
          }
        // Send JSON response back to the frontend
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'html' => $html));
    } else {
        show_404();
    }
  } */
}

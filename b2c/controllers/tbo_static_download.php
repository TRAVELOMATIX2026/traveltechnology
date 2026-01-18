<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
ob_start();

class Tbo_static_download extends MY_Controller
{
  private static $xml = null;
  private static $encoding = 'UTF-8';
  private $to;
  private $from;
  private $count;
  private $country;
  public function __construct()
  {

    parent::__construct();
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);
    $this->from = 1;
    $this->to = 1000;
    $this->count = false;
    $this->country = '';
    $CI = &get_instance();
    $CI->load->library('converter');
  }
  /*Save Static Hotel Data*/
  function get_token_id()
  {
    $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
    $api_request['ClientId'] = 'ApiIntegrationNew';
    $api_request['UserName'] = 'CWS1';
    $api_request['Password'] = 'Cws@12345';
    $api_request['EndUserIp'] = '103.39.132.70';
    $api_request1 = json_encode($api_request);
    $api_header = array('Content-Type: application/json');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_request1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $api_header);
    // Execute request, store response and HTTP response code
    $response = curl_exec($ch);


    $error = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    if ($error == '200') {
      return json_decode($response, true);
    } elseif ($error == '500') {
      $response1['status'] = "500";
      $response1['message'] = $response;
      return $response1;
    }




    $api_filter_response = array();
    if ($api_response['Status'] == 1) {
      $api_filter_response['data']['status'] = 1;
      $api_filter_response['data'] = $api_response;
    } elseif ($api_response['status'] == "500") {
      $api_filter_response['data']['status'] = "500";
      $api_filter_response['data']['message'] = $api_response['message'];
    } else {
      $api_filter_response['data']['status'] = 0;
      $api_filter_response['data']['message'] = 'Wrong Credential!! Please contact to service provider..';
    }
    return $api_filter_response;
  }
  public function static_hotel_tbo()
  {

    $tokenid = $this->get_token_id();
    $tokenid = $tokenid['TokenId'];


    $city_code = $GLOBALS['CI']->custom_db->single_table_records("tbo_city_list", "*", array("status" => 0))['data'];

    $citycode = $city_code[0]['cityid'];
    $request['CityId'] = $citycode;
    $request['ClientId'] = 'ApiIntegrationNew';
    $request['EndUserIp'] = '49.206.33.42';
    $request['TokenId'] = $tokenid;
    $request['IsCompactData'] = "true";
    $request_data = json_encode($request);

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://api.tektravels.com/SharedServices/StaticData.svc/rest/GetHotelStaticData',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $request_data,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);


    curl_close($curl);


    $res = json_decode($response, true);
    $convert = mb_convert_encoding($res['HotelData'], 'UTF-16', 'UTF-8');


    $array = json_decode(json_encode(simplexml_load_string($convert)), true);

    $array = force_multple_data_format($array['BasicPropertyInfo']);

    $count = 1;
    for ($i = 0; $i < 2000; $i++) {
      if (true) {

        foreach ($array as $id => $info) {

          $insert[$id]['HotelCode'] = $info['@attributes']['TBOHotelCode'];
          $insert[$id]['CityCode'] = $info['@attributes']['HotelCityCode'];
          $insert[$id]['HotelName'] = $info['@attributes']['HotelName'];
          $insert[$id]['LocationCatCode'] = $info['@attributes']['LocationCategoryCode'];
          $insert[$id]['IsHalal'] = $info['@attributes']['IsHalal'];
          $insert[$id]['Description'] = @$info['VendorMessages']['SubSection']['Paragraph']['Text'];
          $insert[$id]['Latitude'] = $info['Position']['@attributes']['Latitude'];
          $insert[$id]['Longitude'] = $info['Position']['@attributes']['Longitude'];
          $insert[$id]['Address'] = $info['Address']['AddressLine'][0] . ' ' . $info['Address']['AddressLine'][1];
          $insert[$id]['CityName'] = $info['Address']['CityName'];
          $insert[$id]['PostalCode'] = json_encode($info['Address']['PostalCode']);
          $insert[$id]['CountryName'] = json_encode($info['Address']['CountryName']);
          $insert[$id]['TripAdvisor'] = $info['Award']['@attributes']['Rating'];
          $insert[$id]['TripAdvUrl'] = $info['Award']['@attributes']['ReviewURL'];
          $insert[$id]['Policy'] = json_encode($info['Policy']);
          $insert[$id]['Amenities'] = json_encode($info['AmenityIds']);
          $insert[$id]['IsTopHotel'] = $info['IsTopHotel'];
          $insert[$id]['Attributes'] = json_encode($info['Attributes']);

          $status = $GLOBALS['CI']->custom_db->single_table_records("tbo_static_data", "*", array("HotelCode" => $insert[$id]['HotelCode']));

          if ($status['status'] == 0) {
            if ($insert[$id]['HotelCode'] > 0) {
              $GLOBALS['CI']->custom_db->insert_record("tbo_static_data", $insert[$id]);
            }
          }
        }
      } else {

        $insert1['HotelCode'] = $array['BasicPropertyInfo']['@attributes']['TBOHotelCode'];
        $insert1['CityCode'] = $array['BasicPropertyInfo']['@attributes']['HotelCityCode'];
        $insert1['HotelName'] = $array['BasicPropertyInfo']['@attributes']['HotelName'];
        $insert1['LocationCatCode'] = $array['BasicPropertyInfo']['@attributes']['LocationCategoryCode'];
        $insert1['IsHalal'] = $array['BasicPropertyInfo']['@attributes']['IsHalal'];
        $insert1['Description'] = @$array['BasicPropertyInfo']['VendorMessages']['SubSection']['Paragraph']['Text'];
        $insert1['Latitude'] = $array['BasicPropertyInfo']['Position']['@attributes']['Latitude'];
        $insert1['Longitude'] = $array['BasicPropertyInfo']['Position']['@attributes']['Longitude'];
        $insert1['Address'] = $array['BasicPropertyInfo']['Address']['AddressLine'][0] . ' ' . $array['BasicPropertyInfo']['Address']['AddressLine'][1];
        $insert1['CityName'] = $array['BasicPropertyInfo']['Address']['CityName'];
        $insert1['PostalCode'] = json_encode($array['BasicPropertyInfo']['Address']['PostalCode']);
        $insert1['CountryName'] = json_encode($array['BasicPropertyInfo']['Address']['CountryName']);
        $insert1['TripAdvisor'] = $array['BasicPropertyInfo']['Award']['@attributes']['Rating'];
        $insert1['TripAdvUrl'] = $array['BasicPropertyInfo']['Award']['@attributes']['ReviewURL'];
        $insert1['Policy'] = json_encode($array['BasicPropertyInfo']['Policy']);
        $insert1['Amenities'] = json_encode($array['BasicPropertyInfo']['AmenityIds']);
        $insert1['IsTopHotel'] = $array['BasicPropertyInfo']['IsTopHotel'];
        $insert1['Attributes'] = json_encode($array['BasicPropertyInfo']['Attributes']);
        $status = $GLOBALS['CI']->custom_db->single_table_records("tbo_static_data", "*", array("HotelCode" => $insert1['HotelCode']));
        if ($status['status'] == 0) {
          if ($insert1['HotelCode'] != NULL) {
            $GLOBALS['CI']->custom_db->insert_record("tbo_static_data", $insert1);
          }
        }
      }
      $count1 = $count++;
    }




    if ($count1 == 2000) {

      $GLOBALS['CI']->custom_db->update_record("tbo_city_list", array("status" => 1), array("CityId" => $citycode));
    }
  }

  function whmcsapi_xml_parser($rawxml)
  {
    $xml_parser = xml_parser_create();
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);     // stop elements being converted to UPPERCASE
    xml_parse_into_struct($xml_parser, $rawxml, $vals, $index);
    xml_parser_free($xml_parser);
    $params = array();
    $level = array();
    $alreadyused = array();
    $x = 0;

    foreach ($vals as $xml_elem) {
      if ($xml_elem['type'] == 'open') {
        if (in_array($xml_elem['tag'], $alreadyused)) {
          $x++;
          $xml_elem['tag'] = $xml_elem['tag'] . $x;
        }
        $level[$xml_elem['level']] = $xml_elem['tag'];
        $alreadyused[] = $xml_elem['tag'];
      }
      if ($xml_elem['type'] == 'complete') {
        $start_level = 1;
        $php_stmt = '$params';
        while ($start_level < $xml_elem['level']) {
          $php_stmt .= '[$level[' . $start_level . ']]';
          $start_level++;
        }
        $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
        @eval($php_stmt);
      }
    }
    return ($params);
  }

  // HB City LIst
  function get_hb_country_list()
  {

    $url = "https://api.hotelbeds.com/hotel-content-api/1.0/locations/countries?from=1&to=1000";
    //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
    //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
    echo $url;
    $service_url    = $url; //$request['data']['service_url'];
    $json_header    = array(
      'Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
      'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8' . '80fa801f58' . time()),
      'X-Originating-Ip: 14.141.47.106',
      'Content-Type: application/json',
      'Accept: application/json',
      'Accept-Encoding: gzip'
    );
    $process_type   = ""; //$request['data']['process_type'];
    // debug($json_header);die;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $service_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
    // Execute request, store response and HTTP response code
    $response = curl_exec($ch);

    $response = (strpos($service_url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);
    $i = 0;
    if ($i < $response['total']) {


      foreach ($response['countries'] as $cnt => $_val) {
        echo "<br/>Progress Data No :" . ($i + 1);
        $insert_country[$cnt]['country_code'] = $_val['code'];
        $insert_country[$cnt]['isoCode'] = $_val['isoCode'];
        $insert_country[$cnt]['countryName'] = $_val['description']['content'];
        $GLOBALS['CI']->custom_db->insert_record('hb_country_list_new', $insert_country[$cnt]);
        foreach ($_val['states'] as $id => $_stval) {
          $insert_state[$id]['country_code'] = $_val['code'];
          $insert_state[$id]['country_name'] = $_val['description']['content'];
          $insert_state[$id]['state_code'] = $_stval['code'];
          $insert_state[$id]['state_name'] = $_stval['name'];
          $GLOBALS['CI']->custom_db->insert_record('hb_state_list', $insert_state[$id]);
        }
        $i++;
      }
    } else {
      echo "Insertion Done!";
    }
  }
  // function get_hb_country_list(){

  //         $url = "https://api.hotelbeds.com/hotel-content-api/1.0/locations/countries?from=1&to=1000";
  // //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
  // //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
  // echo $url;
  //         $service_url    = $url;//$request['data']['service_url'];
  //         $json_header    = array('Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
  //                     'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8'.'80fa801f58'. time()),
  //                     'X-Originating-Ip: 14.141.47.106',
  //                     'Content-Type: application/json',
  //                     'Accept: application/json',
  //                     'Accept-Encoding: gzip'
  //                     );
  //         $process_type   = "";//$request['data']['process_type'];
  //         // debug($json_header);die;
  //         $ch = curl_init();
  //         curl_setopt($ch, CURLOPT_URL, $service_url);
  //         curl_setopt($ch, CURLOPT_HEADER, 0);
  //         curl_setopt($ch, CURLOPT_ENCODING, '');
  //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //         curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
  //         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  //         curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
  //         // Execute request, store response and HTTP response code
  //         $response = curl_exec($ch);

  //           $response = (strpos($service_url, 'bookings/') !== false)? $response : $response = json_decode ( $response, true );
  //           $i=0;
  //           if($i<$response['total']){


  //           foreach($response['countries'] as $cnt=>$_val){
  //              echo "<br/>Progress Data No :".($i+1);
  //               $insert_country[$cnt]['country_code']= $_val['code'];
  //               $insert_country[$cnt]['isoCode']= $_val['isoCode'];
  //               $insert_country[$cnt]['countryName']= $_val['description']['content'];
  //               $GLOBALS['CI']->custom_db->insert_record('hb_country_list_new', $insert_country[$cnt]);
  //               foreach($_val['states'] as $id=>$_stval){
  //                     $insert_state[$id]['country_code']= $_val['code'];
  //                     $insert_state[$id]['country_name']= $_val['description']['content'];
  //                     $insert_state[$id]['state_code']= $_stval['code'];
  //                     $insert_state[$id]['state_name']= $_stval['name'];
  //                     $GLOBALS['CI']->custom_db->insert_record('hb_state_list', $insert_state[$id]);

  //               }
  //               $i++;
  //           }
  //           }else{
  //               echo "Insertion Done!";
  //           }


  // }
  function hb_facilities()
  {
    $url = "https://api.hotelbeds.com/hotel-content-api/1.0/types/facilities?from=1&to=1000";
    //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
    //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
    echo $url;
    $service_url    = $url; //$request['data']['service_url'];
    $json_header    = array(
      'Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
      'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8' . '80fa801f58' . time()),
      'X-Originating-Ip: 14.141.47.106',
      'Content-Type: application/json',
      'Accept: application/json',
      'Accept-Encoding: gzip'
    );
    $process_type   = ""; //$request['data']['process_type'];
    // debug($json_header);die;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $service_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
    // Execute request, store response and HTTP response code
    $response = curl_exec($ch);
    $response = (strpos($service_url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);

    foreach ($response['facilities'] as $id => $_val) {
      $insert[$id]['code'] = $_val['code'];
      $insert[$id]['facilityGroupCode'] =  $_val['facilityGroupCode'];
      $insert[$id]['facilityTypologyCode'] = $_val['facilityTypologyCode'];
      $insert[$id]['description'] =  $_val['description']['content'];
      $insert[$id]['created_date'] =  date("Y:m:d H:i");
      $GLOBALS['CI']->custom_db->insert_record("hb_hotel_fac", $insert[$id]);
    }
  }

  function hb_chain_type()
  {
    $url = "https://api.hotelbeds.com/hotel-content-api/1.0/types/chains?fields=all&from=3001&to=4000";
    $json_header    = array(
      'Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
      'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8' . '80fa801f58' . time()),
      'X-Originating-Ip: 14.141.47.106',
      'Content-Type: application/json',
      'Accept: application/json',
      'Accept-Encoding: gzip'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
    // Execute request, store response and HTTP response code
    $response = curl_exec($ch);
    $response = (strpos($url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);



    foreach ($response['chains'] as $id => $_val) {
      $insert[$id]['chain_code'] = ucfirst(strtolower($_val['code']));
      $insert[$id]['chain_desc'] = ucfirst(strtolower($_val['description']['content']));

      $GLOBALS['CI']->custom_db->insert_record("hb_chain_type", $insert[$id]);
    }
  }

  function hb_accom_type()
  {
    $url = "https://api.hotelbeds.com/hotel-content-api/1.0/types/accommodations?fields=all&from=1&to=1000";
    $json_header    = array(
      'Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
      'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8' . '80fa801f58' . time()),
      'X-Originating-Ip: 14.141.47.106',
      'Content-Type: application/json',
      'Accept: application/json',
      'Accept-Encoding: gzip'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
    // Execute request, store response and HTTP response code
    $response = curl_exec($ch);
    $response = (strpos($url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);



    foreach ($response['accommodations'] as $id => $_val) {
      $insert[$id]['acom_code'] = ucfirst(strtolower($_val['code']));
      $insert[$id]['accom_desc'] = ucfirst(strtolower($_val['typeDescription']));

      $GLOBALS['CI']->custom_db->insert_record("hb_accom_type", $insert[$id]);
    }
  }


  function hb_facilities_group($from, $to)
  {
    $url = "https://api.hotelbeds.com/hotel-content-api/1.0/types/rooms?from=" . $from . "&to=" . $to;
    //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
    //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
    echo $url;
    $service_url    = $url; //$request['data']['service_url'];
    $json_header    = array(
      'Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
      'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8' . '80fa801f58' . time()),
      'X-Originating-Ip: 14.141.47.106',
      'Content-Type: application/json',
      'Accept: application/json',
      'Accept-Encoding: gzip'
    );
    $process_type   = ""; //$request['data']['process_type'];
    // debug($json_header);die;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $service_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
    // Execute request, store response and HTTP response code
    $response = curl_exec($ch);
    $response = (strpos($service_url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);
    foreach ($response['rooms'] as $id => $_val) {
      $insert[$id]['code'] = $_val['code'];
      $insert[$id]['room_type'] =  $_val['type'];
      $insert[$id]['characteristic'] = $_val['characteristic'];
      $insert[$id]['minPax'] =  $_val['minPax'];
      $insert[$id]['maxPax'] = $_val['maxPax'];
      $insert[$id]['maxAdults'] =  $_val['maxAdults'];
      $insert[$id]['maxChildren'] = $_val['maxChildren'];
      $insert[$id]['minAdults'] =  $_val['minAdults'];
      $insert[$id]['description'] = @$_val['description'];
      $insert[$id]['typeDescription'] =  $_val['typeDescription']['content'];
      $insert[$id]['created_date'] =  date("Y:m:d H:i");
      $GLOBALS['CI']->custom_db->insert_record("hb_rooms", $insert[$id]);
      $count = $id;
    }
    $too = $to - 1;
    if ($count == 999) {
      $from = $to + 1;
      $to = $to + 1000;

      redirect(base_url() . "/index.php/tbo_static_download/hb_facilities_group/" . $from . "/" . $to, 'refresh');
    }
  }
  // function get_hotel_details_city_list($id='')
  // {

  //         $country_list=$GLOBALS['CI']->custom_db->single_table_records("hb_country_list_new","*");

  //       foreach($country_list['data'] as $id=>$_cval){
  //          if($this->count== false){  
  //         $url = "https://api.hotelbeds.com/hotel-content-api/1.0/locations/destinations?from=".$this->from."&to=".$this->to."&countryCodes=".$_cval['country_code'];
  //         //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
  //         //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
  //         echo $url;
  //         $service_url    = $url;//$request['data']['service_url'];
  //         $json_header    = array('Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
  //                     'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8'.'80fa801f58'. time()),
  //                     'X-Originating-Ip: 14.141.47.106',
  //                     'Content-Type: application/json',
  //                     'Accept: application/json',
  //                     'Accept-Encoding: gzip'
  //                     );
  //         $process_type   = "";//$request['data']['process_type'];
  //         // debug($json_header);die;
  //         $ch = curl_init();
  //         curl_setopt($ch, CURLOPT_URL, $service_url);
  //         curl_setopt($ch, CURLOPT_HEADER, 0);
  //         curl_setopt($ch, CURLOPT_ENCODING, '');
  //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //         curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
  //         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  //         curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
  //         // Execute request, store response and HTTP response code
  //         $response = curl_exec($ch);

  //           $response = (strpos($service_url, 'bookings/') !== false)? $response : $response = json_decode ( $response, true );
  //             foreach($response['destinations'] as $value)
  //             {

  //               $insert_data = array(
  //                         'city_name'=> $value['name']['content'],
  //                         'country_name'=> $_cval['countryName'],
  //                         'hb_codes'=> $value['code'],
  //                         'zones' =>json_encode($value['zones']),
  //                         'group_zone'=>json_encode($value['groupZones']),
  //                         'is_top_city'=> 'NO',
  //                         'country_code'=> $value['countryCode'],
  //                         'status'=> 1
  //                     );
  //                     $this->custom_db->insert_record('hb_city_lists_new', $insert_data);
  //             }
  //                     $error = curl_getinfo($ch);
  //                     curl_close($ch);
  //                 if($response['total'] > $this->to){
  //                   $this->from = $this->to +1;
  //                   $this->to = $this->to +1000;
  //                   $this->count = true;
  //                   $this->country =$_cval['country_code'];


  //               }    

  //             }elseif($this->count ==  true){
  //           $url = "https://api.hotelbeds.com/hotel-content-api/1.0/locations/destinations?from=".$this->from."&to=".$this->to."&countryCodes=".$this->country;
  //         //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
  //         //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
  //         echo $url;
  //         $service_url    = $url;//$request['data']['service_url'];
  //         $json_header    = array('Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
  //                     'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8'.'80fa801f58'. time()),
  //                     'X-Originating-Ip: 14.141.47.106',
  //                     'Content-Type: application/json',
  //                     'Accept: application/json',
  //                     'Accept-Encoding: gzip'
  //                     );
  //         $process_type   = "";//$request['data']['process_type'];
  //         // debug($json_header);die;
  //         $ch = curl_init();
  //         curl_setopt($ch, CURLOPT_URL, $service_url);
  //         curl_setopt($ch, CURLOPT_HEADER, 0);
  //         curl_setopt($ch, CURLOPT_ENCODING, '');
  //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //         curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
  //         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  //         curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
  //         // Execute request, store response and HTTP response code
  //         $response = curl_exec($ch);

  //           $response = (strpos($service_url, 'bookings/') !== false)? $response : $response = json_decode ( $response, true );
  //             foreach($response['destinations'] as $value)
  //             {

  //               $insert_data = array(
  //                         'city_name'=> $value['name']['content'],
  //                         'country_name'=> $_cval['countryName'],
  //                         'hb_codes'=> $value['code'],
  //                         'zones' =>json_encode($value['zones']),
  //                         'group_zone'=>json_encode($value['groupZones']),
  //                         'is_top_city'=> 'NO',
  //                         'country_code'=> $value['countryCode'],
  //                         'status'=> 1
  //                     );
  //                     $this->custom_db->insert_record('hb_city_lists_new', $insert_data);
  //             }
  //                     $error = curl_getinfo($ch);
  //                     curl_close($ch);



  //              if($response['total'] > $this->to){

  //                   $this->from = $this->from +1;
  //                   $this->to = $this->to +1000;
  //                   $this->count = true;



  //               }else{
  //                   $this->from = 1;
  //                   $this->to =   1000;
  //                   $this->count = false;
  //               }


  //         }else{
  //             $this->from = 1;
  //             $this->to =   1000;
  //             $this->count = false;
  //         }


  // }
  // }

  // Hotel Mize static data
  function get_hm_country_list()
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_ReadRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2015-07-16T06:38:10.60">
        			<ReadRequests>
        				<HotelReadRequest>
        					<TPA_Extensions>
        						<RequestType>GetCountries</RequestType>
        					</TPA_Extensions>
        				</HotelReadRequest>
        			</ReadRequests>
        		</OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);
    $final = $array['OTA_ReadRS']['ReadResponse']['Countries']['Country'];

    foreach ($final as $ic => $_val) {
      $insert[$ic]['country_code'] = $_val['CountryISO'];
      $insert[$ic]['country_name'] = $_val['CountryName'];

      //   $GLOBALS['CI']->custom_db->insert_record('mize_country_list',$insert[$ic]);

      $count = ($ic + 1) . '<br/>';
      echo "Inserting....." . $count . '<br/>';
      //   $region_list = $this->get_hm_regions_list($insert[$ic]['country_code']);
      $city_list = $this->get_hm_city_list($insert[$ic]['country_code']);
    }

    if ($count == count($final)) {
      echo "Inserting Done..";
    }
  }

  function get_hm_city_list($CountryCode = 'IN')
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_ReadRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2015-07-16T06:38:10.60">
        			<ReadRequests>
        				<TPA_Extensions>
    						<RequestType>GetCities</RequestType>
    						<CountryCode>' . $CountryCode . '</CountryCode>
    					</TPA_Extensions>
        			</ReadRequests>
        		</OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);
    debug($array);
    $final = $array['OTA_ReadRS']['ReadResponse']['Cities']['City'];
    foreach ($final as $ic => $_val) {
      $insert[$ic]['country_codeIso'] = @$_val['CountryISO'];
      $insert[$ic]['country_name'] = @$_val['CountryName'];
      $insert[$ic]['city_name'] = @$_val['CityName'];
      $insert[$ic]['country_code'] = @$_val['CountryCode'];
      $insert[$ic]['top_city'] = 0;
      $insert[$ic]['city_code'] = @$_val['@attributes']['CityCode'];
      $insert[$ic]['status'] = INACTIVE;
      $GLOBALS['CI']->custom_db->insert_record('mize_city_list', $insert[$ic]);
      //   $locations = $this->get_hm_locations_list($insert[$ic]['city_code']);
      $count = ($ic + 1) . '<br/>';
    }

    if ($count == count($final)) {
      echo "Inserting City Done..";
    }
  }
  function get_hm_locations_list($city_code = 28750)
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_ReadRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2015-07-16T06:38:10.60">
        			<ReadRequests>
        				<TPA_Extensions>
    						<RequestType>GetLocations</RequestType>
    						<CityCode>' . $city_code . '</CityCode>
    					</TPA_Extensions>
        			</ReadRequests>
        		</OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);

    $response = json_encode($array['OTA_ReadRS']['ReadResponse']['Locations']);
    $this->db->where_in('city_code', $city_code);
    $this->db->update("mize_city_list", array('locations' => $response));
    return true;
  }
  function get_hm_regions_list($CountryCode = 'IN')
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_ReadRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2015-07-16T06:38:10.60">
        			<ReadRequests>
        				<HotelReadRequest>
        					<TPA_Extensions>
        						<RequestType>GetRegions</RequestType>
        						<CountryCode>' . $CountryCode . '</CountryCode>
        					</TPA_Extensions>
        				</HotelReadRequest>
        			</ReadRequests>
        		</OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);

    $array = Converter::createArray($res);

    $response = json_encode($array['OTA_ReadRS']['ReadResponse']['Regions']);
    $this->db->where_in('country_code', $CountryCode);
    $this->db->update("mize_country_list", array('regions' => $response));
    return true;
  }

  function get_hm_categories_list()
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_ReadRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2015-07-16T06:38:10.60">
        			<ReadRequests>
        				<TPA_Extensions>
    						<RequestType>GetHotelCategories</RequestType>
    					</TPA_Extensions>
        			</ReadRequests>
        		</OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);
    debug($res);
    exit;
  }



  function get_hm_mealplan_list()
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_ReadRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			xmlns="http://www.opentravel.org/OTA/2003/05" TimeStamp="2015-07-16T06:38:10.60">
        			<ReadRequests>
        				<HotelReadRequest>
        					<TPA_Extensions>
        						<RequestType>GetMealPlans</RequestType>
        					</TPA_Extensions>
        				</HotelReadRequest>
        			</ReadRequests>
        		</OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);
    debug($array);
    exit;
  }



  function get_hm_hotel_room_list($HotelCode = '1687')
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_HotelSearchRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
			        TimeStamp="1973-04-20T11:52:44.81">
        			<Criteria>
        				<Criterion>
        					<HotelRef HotelCode="' . $HotelCode . '" />
        					<TPA_Extensions>
        						<ReturnRooms>true</ReturnRooms>
        					</TPA_Extensions>
        				</Criterion>
        			</Criteria>
        		</OTA_HotelSearchRQ>';
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);
    debug($array);
    exit;
  }
  function get_hm_citywise_hotel_list($HotelCityCode = '28752')
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/staticdata/OTA2014A';
    $request = '<OTA_HotelSearchRQ xmlns:ns="http://www.opentravel.org/OTA/2003/05/common"
        			TimeStamp="1973-04-20T11:52:44.81">
        			<Criteria>
        				<Criterion>
        					<HotelRef HotelCityCode="' . $HotelCityCode . '" />
        				</Criterion>
        			</Criteria>
        		</OTA_HotelSearchRQ>';
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);
    debug($array);
    die;
  }

  function get_hm_hotel_details($HotelCode = '181833')
  {
    $url = 'http://hotels.hotelmize.com/NewAvailabilityServlet/hotelinfo/OTA2014A';
    $request = '<OTA_HotelDescriptiveInfoRQ
            			xmlns:ns="http://www.opentravel.org/OTA/2003/05/common" TimeStamp="2001-05-01T01:08:13.88">
            			<HotelDescriptiveInfos LangRequested="EN">
            				<HotelDescriptiveInfo HotelCode="' . $HotelCode . '" />
            			</HotelDescriptiveInfos>
            		</OTA_HotelDescriptiveInfoRQ>';
    $res = $this->hm_curl($url, $request);
    $array = Converter::createArray($res);
    debug($array);
    exit;
  }


  //   Hotelmize booking flow


  function get_hm_hotel_search_by_citycode($CityCode = '28752')
  {
    $url = 'http://xml.hotelmize.com/NewAvailabilityServlet/hotelavail/OTA2014Compact';
    $request = '<OTA_HotelAvailRQ xmlns="http://parsec.es/hotelapi/OTA2014Compact"  DetailLevel="2"  >
            			<HotelSearch>
            				<Currency Code="USD"/>
            				<HotelLocation CityCode="' . $CityCode . '"/>
            				<DateRange Start="2023-09-25" End="2023-09-29"/>
            				<RoomCandidates>  
            					<RoomCandidate RPH="1">
            						<Guests>
            							<Guest AgeCode="A" Count="2" />
            						</Guests>
            					</RoomCandidate>
            				</RoomCandidates>
            			</HotelSearch>
            		</OTA_HotelAvailRQ>';
    $res = $this->hm_curl($url, $request);
    debug($res);
    exit;
  }

  function get_hm_pre_booking($BookingCode = 'Hpvw6RsV5mnCrk2pt5UCa5pWO35W2kyG6fUfDSNfTpTdBJR4LZZ9cpwhuD4bnbvNyB2f+9/OJEKlUwIwWNgjOJZVv30q7ozxnTTFDvTQAvc0B9zyP9Cw+KnD0y3xvMRqRL6N7qj34pABhcpu9YTx2kCNk2ekZT5h0FFUWXz+G+nyg1jup7k0Ek5IVd/nYDL/uhA0ijcMM1zeer8Lj/iWOyzboiAViUnkmTBRhUgsDwtaCnbqOG+cd9zidlYNeDKVs6CKclx+mxXdBrHqgFr5hS96apASNMPw/LVVlSHtd+cd9vEGU6ZKFMyo6s2aOVqA##Hpvw6RsV5mnCrk2pt5UCaxgJ0KuBBaHFrNYjPf/rUliDSfN3vc5xXSGBto8WOzuHyB2f+9/OJEKlUwIwWNgjOKBaxAtlLrsUJUmTxF+UGNo0B9zyP9Cw+KnD0y3xvMRqRL6N7qj34pABhcpu9YTx2kCNk2ekZT5h0FFUWXz+G+nyg1jup7k0Ek5IVd/nYDL/uhA0ijcMM1zeer8Lj/iWOyzboiAViUnkmTBRhUgsDwskqEoBKFTItYfFXXc02OfmqCZQ1+24hNaLb6SQG88IKS96apASNMPw/LVVlSHtd+cd9vEGU6ZKFMyo6s2aOVq')
  {
    $url = 'https://hotels.hotelmize.com/NewAvailabilityServlet/hotelres/OTA2014Compact';
    $request = '<OTA_HotelResRQ xmlns="http://parsec.es/hotelapi/OTA2014Compact"
                    Transaction="PreBooking">
                        <HotelRes>
                            <Rooms>
                                <Room>
                                    <RoomRate
                                    BookingCode="' . $BookingCode . '"/>
                                </Room>
                                </Rooms>
                        </HotelRes>
                    </OTA_HotelResRQ>';
    $res = $this->hm_curl($url, $request);
    debug($res);
    exit;
  }

  function get_hm_booking($BookingCode = 'o/NIz33XH5lACheuOkwzan8wLSdKzUC+elxKGeyb9jF9KaknXCjELbrHAreHTsh1Hnd/YLpMZtHXvQZqKU1KAOqTvwoGFVd/Zqyh9dgIi7qPHb4X9myAVxs0DaTORRA31LEr02gIOrI1RQFr5kBBwa4elRBqcZFPi14DR3DNbw6DahDmrONrE5D4VzUFguJry3TixO+Ou1coEZtH0q2++7EpCya5igSHw3o5EO94shCiiepICfn4/RnpfFLnE9z8NUGoG2WMNwtiCXfjZa7I5caBeLiHRTiomYWDxHDJp3yTNOr5uZkHIbz6NDA18/607AWRJMV+ZtK8yy8kfIP7dKL7y3F6vbdjqsY3yuTfXm4=')
  {
    $url = 'https://hotels.hotelmize.com/NewAvailabilityServlet/hotelres/OTA2014Compact';
    $request = '<OTA_HotelResRQ xmlns="http://parsec.es/hotelapi/OTA2014Compact" Transaction="Booking" DetailLevel="2" RateDetails="1">
                       <UniqueID Type="ClientReference" ID="CWS1256" />
                       <HotelRes>
                          <Rooms>
                             <Room>
                                <RoomRate BookingCode="' . $BookingCode . '" />
                                <Guests>
                                   <Guest AgeCode="A" LeadGuest="1">
                                      <PersonName>
                                         <NamePrefix>Mr.</NamePrefix>
                                         <GivenName>Badri</GivenName>
                                         <Surname>Nath</Surname>
                                      </PersonName>
                                   </Guest>
                                   <Guest AgeCode="A" >
                                      <PersonName>
                                         <NamePrefix>Mr.</NamePrefix>
                                         <GivenName>Ram</GivenName>
                                         <Surname>Nath</Surname>
                                      </PersonName>
                                   </Guest>
                                </Guests>
                             </Room>
                          </Rooms>
                       </HotelRes>
                    </OTA_HotelResRQ>';
    $res = $this->hm_curl($url, $request);
    debug($res);
    exit;
  }

  function get_hm_cancel($RefID = '1057055')
  {
    $url = 'https://hotels.hotelmize.com/NewAvailabilityServlet/hotelcancel/OTA2014Compact';
    $request = '<OTA_CancelRQ xmlns="http://parsec.es/hotelapi/OTA2014Compact"
                        EchoToken="abcd-1234-efgh-5678" Transaction="Cancel">
                        <UniqueID Type="Locator" ID="' . $RefID . '"/>
                    </OTA_CancelRQ>';
    $this->log_file("cancellation_RQ_" . date('Y-m-d'), $request);
    $res = $this->hm_curl($url, $request);
    $this->log_file("cancellation_RS_" . date('Y-m-d'), $res);
    debug($res);
    exit;
  }

  function get_hm_read_booking($RefID = '1057055')
  {
    $url = 'https://hotels.hotelmize.com/NewAvailabilityServlet/reservationsread/OTA2014Compact';
    $request = '<OTA_ReadRQ xmlns="http://parsec.es/hotelapi/OTA2014Compact"
                        DetailLevel="2" RateDetails="1">
                        <UniqueID Type="Locator" ID="' . $RefID . '" />
                    </OTA_ReadRQ>';
    $res = $this->hm_curl($url, $request);
    debug($res);
    exit;
  }
  private function log_file($file_name, $data)
  {

    $path = $_SERVER['DOCUMENT_ROOT'] . '/xmls/mize/' . $file_name . '.xml';
    $fp = fopen($path, "wb");
    fwrite($fp, $data);
    fclose($fp);
  }


  private function hm_curl($url, $request)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '<soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
        	<soap-env:Header>
        		<wsse:Security
        			xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        			<wsse:Username>1070</wsse:Username>
        			<wsse:Password>Cor7502681po</wsse:Password>
        			<Context>hotelmize</Context>
        		</wsse:Security>
        	</soap-env:Header>
        	<soap-env:Body>
        		' . $request . '
        	</soap-env:Body>
        </soap-env:Envelope>',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/xml'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
  }
  //Update Hotel Count
  function hb_hotel_count()
  {
    $sql = "SELECT hb_codes  FROM hb_city_lists_new where status=1";
    $query = $this->db->query($sql);
    $ids = $query->result_array();
    foreach ($ids as $i => $_val) {
      $code[$i] = $_val['hb_codes'];
      $count = 0;
      $url = "https://api.hotelbeds.com/hotel-content-api/1.0/hotels?destinationCode=" . $code[$i] . "&from=1&to=" . $count . "";

      //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
      //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";

      $service_url    = $url; //$request['data']['service_url'];
      $json_header    = array(
        'Api-Key: ' . '6966a0f305e426b85d611d07fafe5fa8',
        'X-Signature: ' . hash("sha256", '6966a0f305e426b85d611d07fafe5fa8' . '80fa801f58' . time()),
        'X-Originating-Ip: 14.141.47.106',
        'Content-Type: application/json',
        'Accept: application/json',
        'Accept-Encoding: gzip'
      );
      $process_type   = ""; //$request['data']['process_type'];
      $response = "";
      // debug($json_header);die;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $service_url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_ENCODING, '');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
      // Execute request, store response and HTTP response code
      $response = curl_exec($ch);

      $response = (strpos($service_url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);

      $this->db->where_in('hb_codes', $code[$i]);
      $this->db->update("hb_city_lists_new", array('static_data_count' => $response['total']));
      echo "processing " . $i;
    }
  }

  //Download HotelBeds Static Data
  function get_hotel_list($id = '')
  {


    $sql = "SELECT origin,hb_codes,static_data_count,city_name,country_name  FROM hb_city_lists_new where hb_codes='DXB' and status=1 order by static_data_count DESC limit 1";
    // $sql = "SELECT origin,hb_codes,static_data_count,city_name,country_name  FROM hb_city_lists_new where status=1 order by static_data_count DESC limit 1"; Enabled when

    $query = $this->db->query($sql);
    $ids = $query->result_array();


    if (valid_array($ids) == true) {
      $code = $ids[0]['hb_codes'];
      $count = $ids[0]['static_data_count'];
      $city_name = $ids[0]['city_name'];
      $country_name = $ids[0]['country_name'];
      $origin = $ids[0]['origin'];
      /*if($code=="NYC")
  {
      $count=957;
  }*/
      $count = 10;

      $url = "https://api.hotelbeds.com/hotel-content-api/1.0/hotels?destinationCode=" . $code . "&from=1&to=" . $count . "";

      //$url="https://api.test.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";
      //$url="https://api.hotelbeds.com/hotel-content-api/1.0/hotels?fields=all&language=ENG&from=1&to=1000";

      $service_url    = $url; //$request['data']['service_url'];
      $json_header    = array(
        'Api-Key: ' . '8deda8d8196c71e8ea9236ab25f8be04',
        'X-Signature: ' . hash("sha256", '8deda8d8196c71e8ea9236ab25f8be04' . '2438fabbf3' . time()),
        'X-Originating-Ip: 14.141.47.106',
        'Content-Type: application/json',

      );
      $process_type   = ""; //$request['data']['process_type'];
      $response = "";
      // debug($json_header);die;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $service_url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_ENCODING, '');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
      // Execute request, store response and HTTP response code
      $response = curl_exec($ch);

      $response = (strpos($service_url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);
    

      if (valid_array($response['hotels']) == false) {
        $this->db->where_in('hb_codes', $code);
        $this->db->update("hb_city_lists_new", array('status' => 3));
      }

      foreach ($response['hotels'] as $value) {

        if (valid_array($value['images']) == true) {


          $hotel_code = $value['code'];

          $sql = "SELECT hotel_code  FROM hb_master_data_latest where hotel_code='" . $hotel_code . "'";

          $query = $this->db->query($sql);

          $check_code_available = $query->result_array();

          if (valid_array($check_code_available) == false) {

            $imagesPath = "";

            foreach ($value['images'] as $imvalue) {
              //$imagesPath3=$imvalue['path'];
              if ($imvalue['imageTypeCode'] == "GEN" && $imvalue['order'] == 1 && $imvalue['visualOrder'] == 1) {
                $imagesPath = $imvalue['path'];
                break;
              }
            }
            if ($imagesPath == '') {
              foreach ($value['images'] as $imvalue1) {
                if ($imvalue1['imageTypeCode'] == "GEN" && ($imvalue1['order'] == 1 || $imvalue1['visualOrder'] == 1)) {
                  $imagesPath = $imvalue1['path'];
                  break;
                }
              }
            }


            if ($imagesPath == '') {
              foreach ($value['images'] as $imvalue1) {
                if (isset($imvalue1['path'])) {
                  $imagesPath = $imvalue1['path'];
                }
              }
            }

            foreach ($value['phones'] as $im2value) {

              if ($im2value['phoneType'] == "FAXNUMBER") {
                $faxnumber = $im2value['phoneNumber'];
              }
              $contact_number = '';
              if ($im2value['phoneType'] == "PHONEBOOKING") {
                $contact_number1 = $im2value['phoneNumber'];
              }
              if ($im2value['phoneType'] == "PHONEHOTEL") {
                $contact_number2 = $im2value['phoneNumber'];
              }
              $contact_number = $contact_number1 . ',' . $contact_number2;
            }
            if (isset($value['city']['content'])) {
              $city_name1 = $value['city']['content'];
            } else {
              $city_name1 = $city_name;
            }


            $insert_data = array(
              'unique_code' => $value['code'],
              'hotel_code' => $value['code'],
              'country_name' => $country_name,
              'city_name' => $city_name1,
              'country_code' => $value['countryCode'],
              'city_code' => $value['destinationCode'],
              'state' => $value['stateCode'],
              'hotel_name' => $value['name']['content'],
              'address' => $value['address']['content'],
              'phone_number' => $contact_number,
              'fax' => $faxnumber,
              'email' => $value['email'],
              'website' => $value['web'],
              'star_rating' => str_replace("*", "", $value['S2C']),
              'latitude' => $value['coordinates']['latitude'],
              'longitude' =>  $value['coordinates']['longitude'],
              'hotel_desc' => $value['description']['content'],
              'rooms' => json_encode($value['rooms'], true),
              'hotel_faci' => json_encode($value['facilities'], true),
              'accomodation_type_code' => NULL,

              'image' => $imagesPath,
              'thumb_image' => $imagesPath,
              'images' => json_encode($value['images'], true),
              'trip_adv_rating' => NULL,
              'status' => '0',
              'image_status' => '0',
              'chain_code' => $value['chainCode'],
              'chain_desc' => NULL,
              'accommodation_code' => $value['accommodationTypeCode'],
              'near_by_location' => json_encode($value['interestPoints']),
              'license' => NULL,
              'exclusiveDeal' => NULL,
              'zoneCode' => $value['zoneCode'],
              'ranking' => $value['ranking'],
              'datetime' => date("Y-m-d H:i:s")

            );
            $this->custom_db->insert_record('hb_master_data_latest', $insert_data);
          
            if (isset($response['total']) && (isset($code))) {

              $this->db->where_in('hb_codes', $code);
              $this->db->update("hb_city_lists_new", array('hotel_count' => $response['total'], 'status' => 2));
            }
          }
        }
      }


      $error = curl_getinfo($ch);
      curl_close($ch);
    }
  }


  //Download HotelBeds Static Data
  function get_hotel_list_loop($id = '')
  {
    /* $total_records = 1200;
    $limit = 1000;
    $num_loops = ceil($total_records / $limit);
    for ($i = 0; $i < $num_loops; $i++) {
        $offset = $i * $limit;
        $start = $offset + 1;
        $end = min($offset + $limit, $total_records);
        echo "Batch " . ($i + 1);
        echo '<br/>';
        echo   $url = 'http://abc'.$start.'/to'.$end;
        echo '<br/>';
    } */

    // $sql = "SELECT origin,hb_codes,static_data_count,city_name,country_name  FROM hb_city_lists_new where hb_codes='DXB' and status=1 order by static_data_count DESC limit 1";
    $sql = "SELECT origin,hb_codes,static_data_count,city_name,country_name  FROM hb_city_lists_new where status = 3 order by static_data_count DESC limit 100";
    $query = $this->db->query($sql);
    $ids = $query->result_array();
    // debug( $ids ); exit;

    for($j=0; $j < count($ids); $j++):

      if (valid_array($ids) == true) {
        $code = $ids[$j]['hb_codes'];
        $count = $ids[$j]['static_data_count'];
        $city_name = $ids[$j]['city_name'];
        $country_name = $ids[$j]['country_name'];
        $origin = $ids[$j]['origin'];

        $total_records = $count;
        $limit = 1000;
        $num_loops = ceil($total_records / $limit);
          
        for ($i = 0; $i <= $num_loops; $i++) {
          $offset = $i * $limit;
          $start = $offset + 1;
          $end = min($offset + $limit, $total_records);

          $url = "https://api.hotelbeds.com/hotel-content-api/1.0/hotels?destinationCode=" . $code . "&from=".$start."&to=" . $end . "";
          $service_url    = $url;
          $json_header    = array(
            'Api-Key: ' . '8deda8d8196c71e8ea9236ab25f8be04',
            'X-Signature: ' . hash("sha256", '8deda8d8196c71e8ea9236ab25f8be04' . '2438fabbf3' . time()),
            'X-Originating-Ip: 14.141.47.106',
            'Content-Type: application/json',
          );
          $process_type   = "";
          $response = "";
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $service_url);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_ENCODING, '');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $json_header);
          // Execute request, store response and HTTP response code
          $response = curl_exec($ch);
          $error = curl_getinfo($ch);
          curl_close($ch);

          $response = (strpos($service_url, 'bookings/') !== false) ? $response : $response = json_decode($response, true);
          if (valid_array($response['hotels']) == false) {
            $this->db->where('origin', $origin);
            $this->db->update("hb_city_lists_new", array('status' => 3));
          }

          foreach ($response['hotels'] as $value) {

            if (valid_array($value['images']) == true) {
              $hotel_code = $value['code'];
              $sql = "SELECT hotel_code  FROM hb_master_data where hotel_code='" . $hotel_code . "'";
              $query = $this->db->query($sql);
              $check_code_available = $query->result_array();

              if (valid_array($check_code_available) == false) {
                $imagesPath = "";
                foreach ($value['images'] as $imvalue) {
                  //$imagesPath3=$imvalue['path'];
                  if ($imvalue['imageTypeCode'] == "GEN" && $imvalue['order'] == 1 && $imvalue['visualOrder'] == 1) {
                    $imagesPath = $imvalue['path'];
                    break;
                  }
                }
                if ($imagesPath == '') {
                  foreach ($value['images'] as $imvalue1) {
                    if ($imvalue1['imageTypeCode'] == "GEN" && ($imvalue1['order'] == 1 || $imvalue1['visualOrder'] == 101)) {
                      $imagesPath = $imvalue1['path'];
                      break;
                    }
                  }
                }
                if ($imagesPath == '') {
                  foreach ($value['images'] as $imvalue1) {
                    if (isset($imvalue1['path'])) {
                      $imagesPath = $imvalue1['path'];
                    }
                  }
                }
                foreach ($value['phones'] as $im2value) {

                  if ($im2value['phoneType'] == "FAXNUMBER") {
                    $faxnumber = $im2value['phoneNumber'];
                  }
                  $contact_number = '';
                  if ($im2value['phoneType'] == "PHONEBOOKING") {
                    $contact_number1 = $im2value['phoneNumber'];
                  }
                  if ($im2value['phoneType'] == "PHONEHOTEL") {
                    $contact_number2 = $im2value['phoneNumber'];
                  }
                  $contact_number = $contact_number1 . ',' . $contact_number2;
                }
                if (isset($value['city']['content'])) {
                  $city_name1 = $value['city']['content'];
                } else {
                  $city_name1 = $city_name;
                }


                $insert_data = array(
                  'unique_code' => $value['code'],
                  'hotel_code' => $value['code'],
                  'country_name' => preg_replace('/[^\x20-\x7E]/', '', $country_name),
                  'city_name' => preg_replace('/[^\x20-\x7E]/', '',$city_name1),
                  'country_code' => $value['countryCode'],
                  'city_code' => $value['destinationCode'],
                  'state' => $value['stateCode'],
                  'hotel_name' => preg_replace('/[^\x20-\x7E]/', '',$value['name']['content']),
                  'address' => preg_replace('/[^\x20-\x7E]/', '', $value['address']['content']),
                  'phone_number' => preg_replace('/[^\x20-\x7E]/', '', $contact_number),
                  'fax' => preg_replace('/[^\x20-\x7E]/', '', $faxnumber),
                  'email' => preg_replace('/[^\x20-\x7E]/', '', $value['email']),
                  'website' => preg_replace('/[^\x20-\x7E]/', '', $value['web']),
                  'star_rating' => str_replace("*", "", $value['S2C']),
                  'latitude' => $value['coordinates']['latitude'],
                  'longitude' =>  $value['coordinates']['longitude'],
                  'hotel_desc' => preg_replace('/[^\x20-\x7E]/', '', $value['description']['content']),
                  'rooms' => json_encode($value['rooms'], true),
                  'hotel_faci' => json_encode($value['facilities'], true),
                  'accomodation_type_code' => NULL,
                  'image' => $imagesPath,
                  'thumb_image' => $imagesPath,
                  'images' => json_encode($value['images'], true),
                  'trip_adv_rating' => NULL,
                  'status' => '0',
                  'image_status' => '0',
                  'chain_code' => $value['chainCode'],
                  'chain_desc' => NULL,
                  'accommodation_code' => $value['accommodationTypeCode'],
                  'near_by_location' => json_encode($value['interestPoints']),
                  'license' => NULL,
                  'exclusiveDeal' => $value['exclusiveDeal'],
                  'zoneCode' => $value['zoneCode'],
                  'ranking' => $value['ranking'],
                  'categoryCode' => $value['categoryCode'],
                  'categoryGroupCode' => $value['categoryGroupCode'],
                  'boardCodes' => json_encode($value['boardCodes']),
                  'segmentCodes' => json_encode($value['segmentCodes']),
                  'postalCode' => preg_replace('/[^\x20-\x7E]/', '', $value['postalCode']),
                  'terminals' => json_encode($value['terminals']),
                  'lastUpdate' => $value['lastUpdate'],
                  'datetime' => date("Y-m-d H:i:s")
                );
                $this->custom_db->insert_record('hb_master_data', $insert_data);
              }
            }
          } // end foreach loop

          if ( $i == $num_loops) {
            $this->db->where('origin', $origin);
            $this->db->update("hb_city_lists_new", array('status' => 2));
          //  echo  $this->db->last_query();
          }
        }
         // end for loop
      }
  endfor;
  }


  public function grant_token($scope)
  {
    $grant_requests = array();
    $url = 'https://accounts.zoho.com/oauth/v2/auth';

    $grant_requests['scope'] = $scope;
    $grant_requests['client_id'] = '1000.ZE7RM2F02YWVTOCPUVM6R7LEXJJ24B';
    $grant_requests['state'] = '';
    $grant_requests['redirect_uri'] = "https://travgenie.com/emp";
    $grant_requests['access_type'] = "offline";

    $request = json_encode($grant_requests);
    debug($request);
    die;
    // Create a cURL handle to make the REST request:

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));

    //curl execute
    $response = curl_exec($ch);
    debug($response);
    die;
    return $response;
  }
  public function generate_refresh_token()
  {

    //data passing through post
    $data = [
      'code' => '1000.324b1450ab9e3d5221faedce86f922f3.8296444865fedbe4c1b0d5f212a88375',
      'redirect_uri' => 'https://travgenie.com/index.php/tbo_static_download/generate_refresh_token',
      'client_id' => '1000.ZE7RM2F02YWVTOCPUVM6R7LEXJJ24B',
      'client_secret' => '8aa27e1e967130013d661d6cd61f75055a4f6abffa',
      'grant_type' => 'authorization_code',
    ];

    //curl setup
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));

    //curl execute
    $response = curl_exec($ch);
    $response = json_decode($response);
    debug($response);
    die;
    return $response;
  }
}

<?php

ini_set('memory_limit', '-1');

class Api_Interface {

   
    public function __construct() {
        
    }

    /**
     * Get Domain Balance for Admin
     */
    function rest_service($method, $params = array()) {
        $CI = &get_instance();
        $system = $CI->external_service_system;
        $user_name = $system . '_username';
        $password = $system . '_password';
        $username = $CI->$user_name;
        $password = $CI->$password;

        $params = array('domain_key' => get_domain_key(), 'username' => $username, 'password' => $password, 'system' => $system);
        $params['domain_id'] = @$CI->entity_domain_id;
        $url = $CI->external_service;
        // file_put_contents('logs/logs_api_request_'.$fid.'.txt', json_encode(['params'=>$params,'url'=>$url . $method]) .PHP_EOL,FILE_APPEND);
        // file_put_contents('logs/logs_api_response_'.$fid.'.txt',  $res .PHP_EOL,FILE_APPEND);
        $ch = curl_init($url . $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
     * get response from server for the request
     *
     * @param $request 	   request which has to be processed
     * @param $url	   	   url to which the request has to be sent
     * @param $soap_action
     *
     * @return xml response
     */
    public function get_json_response($url, $request = array(), $header_details = array()) {
        $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'x-Username:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'x-DomainKey:' . $header_details['DomainKey'],
            'x-system:' . $header_details['system'],
            'x-Password:' . $header_details['Password']//Remove password later, sending basic/digest auth
        );
       //debug(['url'=>$url,'header'=>$header,'request'=>$request, 'created_at'=>date('Y-m-d H:i:s')]); 
       // exit;

        // $request_header = json_encode($header);
        // $CI =   &get_instance();
        // $CI->db->insert('api_logs',['url'=>$url,'header'=>$request_header,'request'=>$request, 'created_at'=>date('Y-m-d H:i:s')]);
        // $insert_id = $CI->db->insert_id();
        // debug($insert_id);exit;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($ch);
        //debug($res);exit;
        $_api = basename(parse_url($url, PHP_URL_PATH));
        $remarks = $_api;
        $this->store_api_request($url, $request, $remarks,PROVAB_FLIGHT_BOOKING_SOURCE,$res);
        $res = json_decode($res, true);
        curl_close($ch);

        return $res;
    }

    public function get_json_image_response($url, $json_data = array(), $header_details = array(), $method = 'post') {

        $header = array(
            'api-key:07b9b13ecc82ace91324aa816496339d',
            'Content-Type:application/json',
            'Accept:application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        } elseif ($method == "delete") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        $headers = curl_getinfo($ch);

        if ($headers['http_code'] != '200') {
            // echo "<pre>";
            // print_r(curl_error($ch));
            exit;
            return false;
        } else {
            $response = json_decode($response, true);
            //echo "<pre/>";
            //print_r($response);exit;
            return $response;
        }
        curl_close($ch);
    }

    /**
     * get response from server for the request
     *
     * @param $request 	   request which has to be processed
     * @param $url	   	   url to which the request has to be sent
     * @param $soap_action
     *
     * @return xml response
     */
    public function debug_get_json_response($url, $request = array(), $header_details= array()) {
        $header = array(
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
            'x-Username:' . $header_details['UserName'], //Remove password later, sending basic/digest auth
            'x-DomainKey:' . $header_details['DomainKey'],
            'x-system:' . $header_details['system'],
            'x-Password:' . $header_details['Password']//Remove password later, sending basic/digest auth
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($ch);
        $res = json_decode($res, true);
        curl_close($ch);
        return $res;
    }

    function get_json_insurance($method, $url, $data = false) {
        
        
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($curl);
    }


    function xml_process_request($post_url, $request, $header) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // Execute request, store response and HTTP response code
        $response = curl_exec($ch);
        $error = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $response;
    }

    function get_json_response_tbo($post_url, $request, $headers,$remarks="", $process_type="POST") 
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        //debug($response); exit;

        //$remarks = "tbo";

        $this->store_api_request($post_url, $request, $remarks,TBO_HOTEL_BOOKING_SOURCE,$response);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        return $response;
    }
    public function get_json_response_tbo_flight($url,$request, $remarks = NULL)
    {


        try {
            $cs = curl_init();
            curl_setopt($cs, CURLOPT_URL, $url);
            curl_setopt($cs, CURLOPT_TIMEOUT, 180);
            curl_setopt($cs, CURLOPT_HEADER, 0);
            curl_setopt($cs, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cs, CURLOPT_POST, 1);
            curl_setopt($cs, CURLOPT_POSTFIELDS, $request);
            curl_setopt($cs, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($cs, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($cs, CURLOPT_FOLLOWLOCATION, true);

            $header = array(
                'Content-Type:application/json',
                'Accept-Encoding:gzip, deflate'
            );
            curl_setopt($cs, CURLOPT_HTTPHEADER, $header);
            curl_setopt($cs, CURLOPT_ENCODING, "gzip");
            $response = curl_exec($cs);

            //debug($response); exit;

            $error = curl_getinfo($cs);
        } catch (Exception $e) {
            $response = 'No Response Recieved From API';
        }

        $this->store_api_request($url, $request, $remarks,TBO_FLIGHT_BOOKING_SOURCE,$response);

        curl_close($cs);
        return $response;
    }

    /**
     * Get xml response from URL for the request
     * @param string $url
     * @param xml	 $request
     */
    public function get_xml_response($url, $request, $convert_to_array = true) 
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Accept-Encoding:gzip, deflate',));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$request");

        $xml = curl_exec($ch);
        if ($convert_to_array) 
        {
            $data = Converter::createArray($xml);
        } 
        else 
        {
            $data = $xml;
        }
        return $data;
    }

    public function objectToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }
        if (is_array($d)) {
            return array_map(array($this, 'objectToArray'), $d);
        } else {
            return $d;
        }
    }

    public function get_object_response($request_type, $request, $header_details) {
        $header = $header_details['header'];
        $credintials = $header_details['credintials'];
        $_header[] = new SoapHeader("http://provab.com/soap/", 'AuthenticationData', $header, "");
        $client = new SoapClient(NULL, array('location' => $credintials['URL'],
            'uri' => 'http://provab.com/soap/', 'trace' => 1, 'exceptions' => 0));
        try {
            $result = $client->provab_api($request_type, $request, $_header);
        } catch (Exception $err) {
            echo "<pre>";
            print_r($err->getMessage());
        }

        return $result;
    }

    public function xeMultiCurrency($currency){
        $response = [];
        $from = $currency['curFrom'];
        $to = $currency['curTo'];
        $amount = $currency['amount'];
        
       $url = 'https://xecdapi.xe.com/v1/convert_from.json/?from='.$from.'&to='.$to.'&amount='.$amount;
      
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic c3RhcmxlZ2VuZHNhZHZlbnR1cmVzaW5jNzU1OTE4MDI0OnBqZGllcmZubGswcjFnbzhjYWU5cTZoazly'
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function getRemoteIpInformation()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$ch = curl_init();	
		//curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/{$ip}");
		//curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/php.gp?ip=$ip");	
		curl_setopt($ch, CURLOPT_URL, "http://api.ipstack.com/".$ip."?access_key=sdfserwer");	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                                         
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);		
		return $output;
		
	}


    public function api_get_json_response($requestData=array()){
		$header = array(
            'Api-key:' . trim($requestData['APIKey']),
            'X-Signature:' . trim($requestData['signature']),
            'X-Originating-Ip: 14.141.47.106',
            'Content-Type:application/json',
            'Accept: application/json',
            'Accept-Encoding: gzip'
        );
        $request = $requestData['request'];
		$ch = curl_init($requestData['url']);
		curl_setopt($ch, CURLOPT_TIMEOUT, 180);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($requestData['method']=="post")
        {
            curl_setopt($ch, CURLOPT_POST, 1);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        }
        else{
            curl_setopt($cs, CURLOPT_HTTPGET, TRUE);
        }
		
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	
		$res = curl_exec($ch);
		// $res = json_decode($res, true);
		curl_close($ch);

		return $res;
	}

    function get_json_response_hg($method, $url, $data = false) {
        $store = false;
        $remarks = "";
        // debug($method);
        // debug($url);
        // debug($data);exit;
        if (strpos($url, 'search-api.hyperguest.io') !== false) {
            $remarks = "api search";
        }else if (strpos($url, 'hg-static.hyperguest.com') !== false) {
            $remarks = "api get hotel details";
        }else if (strpos($url, 'book-api.hyperguest.com/2.0/booking/get') !== false) {
            $remarks = "api get booking details";
        }else if (strpos($url, 'book-api.hyperguest.com/2.0/booking/cancel') !== false) {
            $remarks = "api cancel";
            $store = true;
        }else if (strpos($url, 'book-api.hyperguest.io') !== false) {
            $remarks = "api do booking";
            $store = true;
        }
        $header = array(
            "Authorization:Bearer 0db4be73dbfd439192a86e93e60130ba",
            'Content-Type:application/json',
            'Accept: application/json',
            'Accept-Encoding: deflate'
        );

        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
        $response = curl_exec($curl);
        $res = json_decode($response, true);
        if($store){
            $origin = $this->store_api_request($url, $data, $remarks,HYPER_GUEST_HOTEL_BOOKING_SOURCE, $response);
        }
        //$this->update_api_response($response, $origin);
        
        curl_close($curl);
        // debug($res);exit;
        return $res;
    }
    public function get_json_response_ndc($url, $request = array(), $header_details = array()) {
        $_api = basename(parse_url($url, PHP_URL_PATH));
        $remarks = $_api;
        $header = array(
            'Authorization:'.$header_details['Authorization'],
            'Content-Type:application/json',
            'Accept-Encoding:gzip, deflate',
        );
        if(isset($header_details['portal-origin'])){
            $header[]='portal-origin:'.$header_details['portal-origin'];
        }
        log_custom('Api:'.$_api);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($ch);  
        log_custom('ApiResponse:'.$_api);
        if(in_array($remarks,['AirOfferPrice','HoldTicket','AirOrderCreateV2','AirOrderChange','AirDocIssue','AirOrderCancel','AirTicketVoid','StoreCardDetail','AirSeatMap','AirOfferServiceList'])){
            $this->store_api_request($url, $request, $remarks,CLARITYNDC_FLIGHT_BOOKING_SOURCE,$res);
        }
        $res = json_decode($res, true); 
        if($remarks == 'storeCard'){
            file_put_contents('logs/store_card_api.txt', json_encode(['request'=>$request,'url'=>$url,'header'=>$header,'resp'=>$res]) .PHP_EOL,FILE_APPEND);
        } 
        curl_close($ch);
        return $res;
    }

    public  function store_api_request($request_type, $request, $remarks, $booking_source=NULL, $response=NULL)
    {
        if($request_type !=NULL){
            if(is_array($request)) {
                $request = json_encode($request);
            }
            $provab_api_response_history = array();
            if($booking_source!=NULL){
                $provab_api_response_history['booking_source'] = $booking_source;
            }
            $provab_api_response_history['request_type'] = $request_type;
            $provab_api_response_history['request'] = $request;
            $provab_api_response_history['remarks'] = $remarks;
            $provab_api_response_history['created_datetime'] = date('Y-m-d H:i:s');
            if($response!=NULL){
                if(is_array($response)) {
                    $response = json_encode($response);
                }
                $provab_api_response_history['response'] = $response;
            }
            //debug($provab_api_response_history);exit;
            $CI =   &get_instance();
            $CI->db->insert('provab_api_response_history',$provab_api_response_history);
            return $insert_id = $CI->db->insert_id();

        }
    }



    /**
     * Stores API Requests
     */
    public  function update_api_response($response, $origin)
    {
        if(intval($origin) > 0){
            if(is_array($response)) {
                $response = json_encode($response);
            }
            $provab_api_response_history = array();
            $provab_api_response_history['response'] = $response;
            $provab_api_response_history['response_updated_time'] = date('Y-m-d H:i:s');

            $CI =   &get_instance();
            $CI->db->where('origin',intval($origin));
            return $CI->db->update('provab_api_response_history',$provab_api_response_history);
        }
    }

}


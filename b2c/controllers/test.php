<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Test extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
	}
	public function index1(){
        $recs = $this->db->where(['is_market_place'=>1,'city_origin'=>0])->group_by(['country','city'])->order_by('country','ASC')->get('hg_hotels_list')->result();
		// debug(count($recs));//exit;
		$str = '<table>';
		foreach($recs as $k=>$rec){
			$str .= '<tr><td>'. ($k+1) .'</td><td>'. $rec->city .'</td><td>'. $rec->country .'</td></tr>';
			/*$query = $this->db->where(['TRIM(city_name)'=>trim($rec->city),'TRIM(country_code)'=>trim($rec->country)])->get('all_api_city_master');
			if($query){
				$city_recs = $query->result();
				if($city_recs){
					debug($rec);
					$city_rec = end($city_recs);
					debug($city_rec);exit;
					$this->db->where('is_market_place',1);
					$this->db->where('city_origin',0);
					$this->db->where('city',$rec->city);
					$this->db->where('country',$rec->country);
					$this->db->update('hg_hotels_list',['city_origin'=>$city_rec->origin]);
					//debug($city_recs);exit;
				}else{
					//debug($this->db->last_query());
				}
			}*/
			
		}
		$str .= '</table>';
		echo $str;
    }
	public function update_hotel1(){
		while (ob_get_level()) {
			ob_end_flush();
		}
		echo "Starting process...\n";
		flush();
		$recs = $this->db->where(['is_market_place'=>1,'images'=>''])->get('hg_hotels_list')->result();
		debug(count($recs));//exit;
		//debug($recs);
		flush();
		if($recs){
			foreach($recs as $k=>$rec){
				// if($k>5){die('done!');}
				$hotel_id = $rec->hotel_id;
				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://hg-static.hyperguest.com/'.$hotel_id.'/property-static.json',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer 0db4be73dbfd439192a86e93e60130ba'
				),
				));

				$response = curl_exec($curl);
				
				curl_close($curl);
				if(strpos($response,'Too many requests') !== false){
					debug("Error:  Too many requests ".$hotel_id);
					flush();
					sleep(180);
				}else if($response== ''){
					debug("Error:  empty response ".$hotel_id);
					flush();
				}else{
					$images = array();
					$response_decode = json_decode($response,true);
					// unset($response_decode['rooms']);
					// $response = json_encode($response_decode);
					if(!empty($response_decode['logo'])){
						$images[] = $response_decode['logo'];
						//debug($response_decode['logo']);flush();
					}
					if(!empty($response_decode['images'])){
						foreach($response_decode['images'] as $img){
							if($img['type']=='photo'){
								$images[] = $img['uri'];
							}
						}
						//debug($response_decode['images']);flush();
					}
					if($images){
						$this->db->where(['origin'=>$rec->origin]);
						$this->db->update('hg_hotels_list',['images'=>implode(',',$images)]);
						debug($images);
						flush();
					}else{
						//debug($response_decode);exit;
					}
				}
			}
		}
		echo "Process completed.\n";
		flush();
	}
	public function select_city_data1(){
		$sql = "SELECT `origin`,`name`,country,region,city,`address` FROM `hg_hotels_list` where is_market_place=1 and city_origin=0  "; //, count(origin) as cnt group by country,city having cnt>2 order by cnt desc
		$recs = $this->db->query($sql)->result_array();
		if($recs){
			debug(count($recs));
			$recs_5 = array_slice($recs, 0, 5);
			debug($recs_5);
		}
	}
	public function search_city1($country, $city){
		$city = urldecode($city);
		$sql = "SELECT id,origin,city_name,country_name,country_code FROM `all_api_city_master` where country_code='$country' and city_name like '%$city%';";
		$recs = $this->db->query($sql)->result_array();
		if($recs){
			debug($recs);
		}
	}
	public function update_city1($city_origin,$country, $city){
		// $sql = "UPDATE `hg_hotels_list` set city_origin=$city_origin WHERE `country` LIKE '$country' AND `city` LIKE '$city' AND `is_market_place` = 1;";
		// $recs = $this->db->query($sql);
		$city = urldecode($city);
		$this->db->where(['city'=>$city,'country'=>$country,'is_market_place'=>1]);
		$this->db->update('hg_hotels_list',['city_origin'=>$city_origin]);
		debug($this->db->last_query());
		$recs = $this->db->affected_rows();
		debug($recs);
	}
	public function update_city_origin1($city_origin,$origin){
		$this->db->where(['origin'=>$origin]);
		$this->db->update('hg_hotels_list',['city_origin'=>$city_origin]);
		debug($this->db->last_query());
		$recs = $this->db->affected_rows();
		debug($recs);
	}
	public function send_email(){
		require_once BASEPATH . 'libraries/PHPMailer/class.phpmailer.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host       = 'smtp.gmail.com';
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = 'tls';
		$mail->Port       = 587;
		$mail->Username   = 'sureshbabu.provab@gmail.com';
		$mail->Password   = 'pfpfxudtpbxmfvxq';
		$mail->FromName   = 'Suresh Provab';
		$mail->From   = 'sureshbabu.provab@gmail.com';
		$mail->AddAddress('sureshbabu693@gmail.com','Suresh Babu');

		//and attachment
		if (empty($attachment) == false) {
			if(valid_array($attachment)) {
				//for multple attachements
				foreach($attachment as $k => $v) 
				{
					if(empty($v) == false) 
					{
						$mail->addAttachment($v);
					}
				}
			} 
			else if(strlen($attachment) > 1){
				//for single attachements
				$mail->addAttachment($attachment);
			}
		}
		//add CC
		if(empty($cc) == false && valid_array($cc)) {
			$ccEmail = '';
			//Validating Email
			foreach($cc as $k => $v) {
				if(filter_var($v, FILTER_VALIDATE_EMAIL) == true) {
					$ccEmail[] = trim($v);							
				}
			}
			if(valid_array($ccEmail)) 
			{
				$mail->AddCC($ccEmail);
			}					
		}				
		//add BCC
		if(empty($bcc) == false && valid_array($bcc)) {
			$bccEmail = '';
			//Validating Email
			foreach($bcc as $k => $v) {
				if(filter_var($v, FILTER_VALIDATE_EMAIL) == true) {
					$bccEmail[] = trim($v);							
				}
			}
			if(valid_array($bccEmail)) 
			{
				$mail->addBCC($bccEmail);
			}
		}
		$mail->Subject = 'email Subject';
		$mail->Body    = '<p>test mail</p>';
		$mail->IsHTML(true);
		$mail->WordWrap = 500;

		if (!$mail->Send()) 
		{
			// echo false;
			$message = "Error sending: " . $mail->ErrorInfo;
			$status = false;
			// $message = $this->email->print_debugger();
			// debug($message);die;
		}else{
			$status = true;
			$message = 'Mail Sent Successfully';
			//	return array('status' => $status, 'message' => $message);
		}
		
		debug(array('status' => $status, 'message' => $message));
	}
	public function get_currency_conversion($from, $to){
		$currency_obj = new Currency ( array (
			'module_type' => 'flight',
			'from' => $from,
			'to' => $to 
		));
		debug($currency_obj);
	}
}
<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
class Test_Services extends MY_Controller 
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	function delete_b2c_cache_files()
	{
		$files = glob(realpath('b2c').'/cache/*');
		foreach($files as $file){ // iterate files
		  if(is_file($file)){
			  if(filemtime($file) < time() - 86400) {
				 unlink($file); // delete file 
			  }
			}
		}
	}
	function delete_agent_cache_files()
	{
		$files = glob(realpath('agent/application').'/cache/*');
		foreach($files as $file){ // iterate files
		  if(is_file($file)){
			  if(filemtime($file) < time() - 86400) {
				 unlink($file); // delete file 
			  }
			}
		}
	}
	function delete_temp_cache_files()
	{
		$files = glob(realpath('extras').'/custom/wKuTTCM3wqbBLoLkMHp8VIvyA/tmp/*');
		foreach($files as $file){ // iterate files
		  if(is_file($file)){
			  if(filemtime($file) < time() - 86400) {
				 unlink($file); // delete file 
			  }
			}
		}
	}
	function delete_temp_pdf_files()
	{
		$files = glob(realpath('extras').'/custom/wKuTTCM3wqbBLoLkMHp8VIvyA/temp_booking_data_pdf/*');
		foreach($files as $file){ // iterate files
		  if(is_file($file)){
			  if(filemtime($file) < time() - 86400) {
				 unlink($file); // delete file 
			  }
			}
		}
	}
	function hg_static_hotels()
	{ exit;
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://hg-static.hyperguest.com/hotels.json',
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
		$res = json_decode($response,true);
		
		debug(count($res));
		//debug($res[0]);exit;
		$array_fields = array('hotel_id','last_updated','name','country','region','city','city_Id');
		foreach($res as $key => $rec){
			$keys = array_keys($rec);
			
			$diff = array_diff($keys,$array_fields);
			if($diff){
				debug($key);
				debug($keys);
				debug($array_fields);
				debug($diff);exit;
			}

			$diff = array_diff($array_fields, $keys);
			if($diff){
				debug($key);
				debug($keys);
				debug($array_fields);
				debug($diff);exit;
			}
			$rec['name'] = utf8_encode($rec['name']);
			$rec['last_updated'] = date("Y-m-d H:i:s",strtotime($rec['last_updated']));
			$this->db->insert('hg_hotels_list',$rec);
			// $sql = $this->db->set($rec)->get_compiled_insert('hg_hotels_list');
			// echo $sql;exit;

			// $this->db->set('hotel_id',$rec['hotel_id']);
			// $this->db->set('last_updated',$rec['last_updated']);
			// $this->db->set('name',$rec['name']);
			// $this->db->set('country',$rec['country']);
			// $this->db->set('region',$rec['region']);
			// $this->db->set('city',$rec['city']);
			// $this->db->set('city_Id',$rec['city_Id']);
			// $this->db->insert('hg_hotels_list');
		}
	}
	public function test_run(){
		$sql = "select count(*) as total_updated from all_api_city_master where hb_city_id !='0' and hb_city_id !=''";
		$sql = "UPDATE all_api_city_master a,hb_hotel_details b SET a.hb_city_id = b.destination_code where a.city_name = b.hotel_city";
		//echo $this->db->query($sql)->num_rows();
		// $this->db->query($sql);
		echo $this->db->affected_rows();
	}
	public function test_hb_new(){
		
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		ini_set('max_execution_time', 0);
		for($i=0;$i<=18200;$i+=100){
			// echo $i."<br>";
			$sql = "SELECT destination_code,hotel_city FROM `hb_hotel_details` where destination_code !='' and hotel_city !='' group by hotel_city limit $i,100";
			$recs = $this->db->query($sql)->result_array();
			if($recs){
				foreach($recs as $rec){
					$city_name = trim($rec['hotel_city']);
					$sql = 'update all_api_city_master set hb_city_id="'.$rec['destination_code'].'" where city_name = "'.$city_name.'"';
					// $this->db->query($sql);
				}
			}
    		
		}
	}
}
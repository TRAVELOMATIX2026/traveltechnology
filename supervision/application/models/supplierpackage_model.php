<?php
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
class Supplierpackage_Model extends CI_Model {
	public function __construct() {
		parent::__construct ();
	}
	public function package_view_data_types() {
		return $this->db->get ( 'package_types' );
	}
	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
	public function package_type_data() {
		return $this->db->get ( 'package_types' );
	}
	public function add_new_package($newpackage) {
		$this->db->insert ( 'package', $newpackage );
		return $this->db->insert_id ();
	}
	public function update_code_package($packcode, $package) {
		$this->db->where ( 'package_id', $package );
		return $this->db->update ( 'package', $packcode );
	}
	public function itinerary($itinerary) {
		//debug($itinerary);exit;
		$this->db->insert ( 'package_itinerary', $itinerary );
		return $this->db->insert_id ();
	}
	public function que_ans($que_ans) {
		$this->db->insert ( 'package_que_ans', $que_ans );
		return $this->db->insert_id ();
	}
	public function pricing_policy($pricingpolicy) {
		$this->db->insert ( 'package_pricing_policy', $pricingpolicy );
		return $this->db->insert_id ();
	}
	public function cancellation_penality($cancellation) {
		$this->db->insert ( 'package_cancellation', $cancellation );
		return $this->db->insert_id ();
	}
	public function deals($deals) {
		$this->db->insert ( 'package_deals', $deals );
		return $this->db->insert_id ();
	}
	public function travel_images($traveller) {
		//debug($traveller);exit;
		$this->db->insert ( 'package_traveller_photos', $traveller );
		return $this->db->insert_id ();
	}
	public function without_price() {
		$this->db->where ( 'supplier_id', $this->session->userdata ( 'sup_id' ) );
		$this->db->where ( "price_includes", '0' );
		$this->db->where ( "deals", '0' );
		$q = $this->db->get ( "package" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}
	public function get_supplier() {
		$this->db->where ( 'supplier_id', $this->session->userdata ( 'sup_id' ) );
		$this->db->where ( "deals", '1' );
		$this->db->where ( "price_includes", '0' );
		$q = $this->db->get ( "package" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}
	public function update_status_answer($id, $status) {
		$data = array (
				'status' => $status 
		);
		// $where = "package_id = " . $package_id;
		$where = "id = " . $id;
		// $where = "qid = " . $qid;
		if ($this->db->update ( 'package_answers', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_status($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "package_id = " . $id;
		if ($this->db->update ( 'package', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_top_destination($id, $status) {
		$data = array (
				'top_destination' => $status 
		);
		$where = "package_id = " . $id;
		if ($this->db->update ( 'package', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_enquiry_status($id, $status) {
		$data = array (
				'enquiry_status' => $status
		);
		$where = "id = " . $id;
		if ($this->db->update ( 'package_enquiry', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_homepage_status($package, $home_page) {
		$data = array (
				'home_page' => $home_page 
		);
		
		$where = "package_id = " . $package;
		// $where = "img_id = " . $img_id;
		if ($this->db->update ( 'package', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_package_id($package_id) {
		$this->db->select ( "*" );
		$this->db->from ( "package" );
		$this->db->join ( 'package_cancellation', 'package_cancellation.package_id = package.package_id' );
		$this->db->join ( 'package_pricing_policy', 'package_pricing_policy.package_id = package.package_id' );
		$this->db->where ( 'package.package_id', $package_id );
		return $this->db->get ()->row ();
	}
	public function get_price($package_id) {
		$this->db->from ( 'package_duration' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->row ();
		}
		return false;
	}
	public function get_country_city_list() {
		$this->db->select ( '*' )->from ( 'country' );
		$query = $this->db->get ();
		
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function get_itinerary_id($package_id) {
		$this->db->from ( 'package_itinerary' );
		$this->db->where ( 'package_id', $package_id );
		$this->db->order_by ( 'day', 'asc' );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function get_que_ans($package_id) {
		$this->db->from ( 'package_que_ans' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function with_price() {
		$q = $this->db->get ( "package" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}
	public function get_country_name($id) {
		$this->db->select ( 'name' );
		$this->db->where ( 'country_id', $id );
		return $this->db->get ( 'country' )->row ();
	}
	public function get_country_name_new($id) {
    $this->db->select('name');
    if (strpos($id, ',') !== false) {
        $ids = explode(',', $id);
        $this->db->where_in('origin', $ids);
    } else {
        $this->db->where('origin', $id);
    }

    $query = $this->db->get('api_country_list');
    $result = $query->result();

    $country_names = array_map(function($row) {
        return $row->name;
    }, $result);

    return implode(', ', $country_names);
}
	public function enquiries() {
		$this->db->from ( 'package_enquiry' );
		$this->db->join ( 'package', 'package.package_id=package_enquiry.package_id' );
		$this->db->order_by ( 'id', "desc" );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			return $query->result ();
		}
		return false;
	}
	public function get_crs_city_list($value) {
		$this->db->where ( 'country', $value );
		return $this->db->get ( 'crs_city' )->result ();
	}
	public function get_tour_list($value) {
		$this->db->where ( 'package_types_id', $value );
		return $this->db->get ( 'package_types' )->result ();
	}
	public function update_edit_package($package_id, $data) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'package', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_policy($package_id, $policy) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'package_pricing_policy', $policy, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_can($package_id, $can) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'package_cancellation', $can, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_dea($package_id, $dea) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'package_deals', $dea, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_pri($package_id, $pri) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'package_duration', $pri, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_image($package_id) {
		$this->db->from ( 'package_traveller_photos' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function update_itinerary($package, $itinerary_id, $data) {
		$where = "package_id = " . $package;
		$where = "iti_id = " . $itinerary_id;
		if ($this->db->update ( 'package_itinerary', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_traveller_img($pack_id,$img_id) {
		$this->db->where ( 'package_id', $img_id );
		$this->db->where ( 'img_id', $pack_id );
		$this->db->delete ( 'package_traveller_photos' );
	}
	public function view_enqur($package_id) {
		$this->db->from ( 'package_enquiry' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	public function delete_enquiry($id) {
		$this->db->where ( 'id', $id );
		$this->db->delete ( 'package_enquiry' );
	}
	public function delete_package_type($id) {
		$this->db->where ( 'package_types_id', $id );
		$this->db->delete ( 'package_types' );
	}
	public function delete_package($id) {
		$this->db->where ( 'package_id', $id );
		$this->db->delete ( 'package' );
	}
	public function get_pack_id($id) {
		$this->db->select ( '*' );
		$this->db->where ( 'package_types_id', $id );
		return $this->db->get ( 'package_types' )->result ();
	}
	public function update_package_type($add_package_data, $id) {
		$this->db->where ( 'package_types_id', $id );
		$this->db->update ( 'package_types', $add_package_data );
	}
	public function get_tours_continent()
	{
		$query = 'select * from api_continent_list order by name'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;

		}*/
		// debug($result);exit();
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	public function continent_countries()
	{
		//debug("sfgf");exit;
		$query = "select * from api_country_list order by name"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	public function ajax_tours_continent($tours_continent)
	{
		//debug("sfgf");exit;
		$query = "select * from api_country_list where api_continent_list_fk='$tours_continent' order by name"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	public function ajax_tours_country($tours_country)
	{
		$query = "select * from api_city_list where country='$tours_country' group by destination order by destination"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	public function delete_itinerary($itinerary_id, $package_id) {
		if (!empty($itinerary_id)) {
			$this->db->where('iti_id', $itinerary_id);
			$this->db->where('package_id', $package_id);
			$query = $this->db->get('package_itinerary');
			if ($query->num_rows() > 0) {
				$this->db->where('iti_id', $itinerary_id);
				$this->db->delete('package_itinerary');
			}
		}
	}
	public function update_package_days($package_id, $days) {
		if (!empty($package_id)) {
			$this->db->where('package_id', $package_id);
			$query = $this->db->get('package');
			if ($query->num_rows() > 0) {
				$this->db->set('duration', $days);
				$this->db->where('package_id', $package_id);
				$this->db->update('package');
			}
		}
	}

	public function delete_itinerary_without_itinerary($package_id) {
		if (!empty($package_id)) {
			$this->db->where('package_id', $package_id);
			$query = $this->db->get('package_itinerary');
			if ($query->num_rows() > 0) {
				$this->db->delete('package_itinerary');
			}
		}
	}
}
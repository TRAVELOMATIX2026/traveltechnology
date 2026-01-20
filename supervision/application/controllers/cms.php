<?php

if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cms extends MY_Controller {

	

	public function __construct()

	{

		parent::__construct();

		$this->load->model('module_model');

	}

	/**

	 * Manage Hotel Top Destinations

	 */

	function hotel_top_destinations($offset = 0)

	{

		// Search Params(Country And City)

		// CMS - Image(On Home Page)

		// 		$page_data = array ();

		// 		$post_data = $this->input->post ();

		// 		// debug($post_data);exit;

		// 		if (valid_array ( $post_data ) == true) {

		// 			$city_origin = $post_data ['city'];

		// 			$category = $post_data ['category'];

		// 			$rating = $post_data ['rating'];

		// 			$price = $post_data ['price'];

		// 			$hotel_name = $post_data ['hotel_name'];

		// 			// FILE UPLOAD

		// 			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {

		// 				$config ['upload_path'] = $this->template->domain_image_upload_path ();

		// 				$temp_file_name = $_FILES ['top_destination'] ['name'];

		// 				$config ['allowed_types'] = '*';

		// 				$config ['file_name'] = 'top-dest-hotel-' . $city_origin;

		// 				$config ['max_size'] = '1000000';

		// 				$config ['max_width'] = '';

		// 				$config ['max_height'] = '';

		// 				$config ['remove_spaces'] = false;

		// 				// UPDATE

		// //debug($config);exit;

		// 				$temp_record = $this->custom_db->single_table_records ( 'all_api_city_master', 'image', array (

		// 						'origin' => $city_origin 

		// 				) );

		// 				$top_destination_image = $temp_record ['data'] [0] ['image'];

		// 				// DELETE OLD FILES

		// 				if (empty ( $top_destination_image ) == false) {

		// 					$temp_top_destination_image = $this->template->domain_image_full_path ( $top_destination_image ); // GETTING FILE PATH

		// 					if (file_exists ( $temp_top_destination_image )) {

		// 						unlink ( $temp_top_destination_image );

		// 					}

		// 				}

		// 				// UPLOAD IMAGE

		// 				$this->load->library ( 'upload', $config );

		// 				$this->upload->initialize ( $config );

		// 				if (! $this->upload->do_upload ( 'top_destination' )) {

		// 					echo $this->upload->display_errors ();

		// 				} else {

		// 					$image_data = $this->upload->data ();

		// 				}

		// 				// debug($image_data);exit;

		// 				$this->custom_db->update_record ( 'all_api_city_master', array (

		// 						'top_destination' => ACTIVE,

		// 						'image' => $image_data ['file_name'],

		// 						'cat' => $post_data ['category'],

		// 						'rating' => $post_data ['rating'],

		// 						'price' => $post_data ['price'],

		// 						'hotel_name' => $post_data ['hotel_name'],

		// 				), array (

		// 						'origin' => $city_origin 

		// 				) );

		// 				set_update_message ();

		// 			}

		// 			refresh ();

		// 		}

		// 		$filter = array (

		// 				'top_destination' => ACTIVE 

		// 		);

		// 		$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (

		// 				'country_name !=' => '' 

		// 		), 0, 1000, array (

		// 				'country_name' => 'ASC' 

		// 		) );

		// 		$data_list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', $filter, 0, 100000, array (

		// 				'top_destination' => 'DESC',

		// 				'city_name' => 'ASC' 

		// 		) );

		// 		//debug($data_list);exit;

		// 		if ($country_list ['status'] == SUCCESS_STATUS) {

		// 			$page_data ['country_list'] = magical_converter ( array (

		// 					'k' => 'iso_country_code',

		// 					'v' => 'country_name' 

		// 			), $country_list );

		// 		}

		// 		$page_data ['data_list'] = @$data_list ['data'];

		// 		$this->template->view ( 'cms/hotel_top_destinations', $page_data );
        
error_reporting(1);
ini_set("display_errors", 1);	
ini_set("display_setup_errors", 1);	
error_reporting(E_ALL);
		$page_data = array();

		$post_data = $this->input->post();

		if (valid_array($post_data) == true) {

			$country_name = $post_data['country_name'];

			$domain = 1;

			$this->db->select('country_name');

			$this->db->from('all_api_city_master');

			$this->db->where('country_code', $country_name);

			$this->db->group_by('country_code');

			$query = $this->db->get();

			$result = $query->result();

			// debug($result);exit;

			$country_name = $result[0]->country_name;

			// echo $country_name;exit;

			$city_name = $post_data['city_name'];

			$this->db->select('city_name');

			$this->db->from('tbo_city_list');

			$this->db->where('origin', $city_name);

			$query2 = $this->db->get();

			$result2 = $query2->result();

			// debug($result2);exit;

			$city_name = $result2[0]->city_name;

			// echo $city_name;exit;

			$category = $post_data['category_id'];

			$rating = $post_data['rating'];

			$price = $post_data['price'];

			$hotel_name = $post_data['hotel_name'];

			$alt_text = $post_data['alt_text'];

			// Initialize image_data to avoid undefined variable error
			$image_data = null;
			$image_file_name = '';

			if (valid_array($_FILES) == true and isset($_FILES['top_destination']) and $_FILES['top_destination']['error'] == 0 and $_FILES['top_destination']['size'] > 0) {

				$upload_path = $this->template->domain_image_upload_path();
				
				// Check if upload directory exists and is writable
				if (!is_dir($upload_path)) {
					// Try to create the directory
					if (!mkdir($upload_path, 0755, true)) {
						$this->session->set_flashdata('error', 'Upload destination folder does not exist and could not be created. Please check folder permissions.');
					}
				}
				
				if (!is_writable($upload_path)) {
					$this->session->set_flashdata('error', 'The upload destination folder is not writable. Please check folder permissions.');
				} else {
					$config['upload_path'] = $upload_path;

					$temp_file_name = $_FILES['top_destination']['name'];

					$config['allowed_types'] = 'gif|jpg|jpeg|png|webp';

					$config['file_name'] = 'top-dest-hotel-' . preg_replace('/[^a-zA-Z0-9]/', '-', $city_name) . '-' . time();

					$config['max_size'] = '1000000';

					$config['max_width'] = '';

					$config['max_height'] = '';

					$config['remove_spaces'] = false;

					$config['overwrite'] = false;

					$this->load->library('upload', $config);

					$this->upload->initialize($config);

					if (! $this->upload->do_upload('top_destination')) {

						$upload_error = $this->upload->display_errors();
						$this->session->set_flashdata('error', 'Image upload failed: ' . $upload_error);

					} else {

						$image_data = $this->upload->data();
						if (isset($image_data['file_name']) && !empty($image_data['file_name'])) {
							$image_file_name = $image_data['file_name'];
						}

					}
				}

			}

			// Check if image was uploaded, if not show error
			if (empty($image_file_name)) {
				$this->session->set_flashdata('error', 'Please upload an image for the destination.');
				redirect(base_url() . 'cms/hotel_top_destinations');
				return;
			}

			// debug($image_data);exit;

			$data['country_name'] = $country_name;

			$data['city_name'] = $city_name;

            $data['destination_id'] = $post_data['city_name'];

			$data['category_id'] = $category;

			$data['hotel_name'] = $hotel_name;

			$data['rating'] = $rating;

			$data['price'] = $price;

			$data['image'] = $image_file_name;

			$data['alt_text'] = $alt_text;

			$data['domain'] = $domain;

			$this->custom_db->insert_record('hotel_top_destinations', $data);

		}

		$filter = array(

			//'status' => ACTIVE 

		);

		$country_list = $this->custom_db->single_table_records('api_country_master', 'country_name,origin,iso_country_code', array(

			'country_name !=' => ''

		), 0, 1000, array(

			'country_name' => 'ASC'

		));

		if ($country_list['status'] == SUCCESS_STATUS) {

			$page_data['country_list'] = magical_converter(array(

				'k' => 'iso_country_code',

				'v' => 'country_name'

			), $country_list);

		}

		$data_list = $this->custom_db->single_table_records('hotel_top_destinations', '*', $filter, 0, 100000, array(

			'status' => 'DESC',

			'city_name' => 'ASC'

		));

		$filter = array(

			'status' => ACTIVE,

			'module' => 'hotel'

		);

		$category_list = $this->custom_db->single_table_records('top_destination_categories', '*', $filter, 0, 100000, array(

			'status' => 'DESC',

			'category_name' => 'ASC'

		));

		$page_data['data_list'] = @$data_list['data'];

		$page_data['category_list'] = @$category_list['data'];

		// debug($page_data);exit;

		$this->template->view('cms/hotel_top_destinations', $page_data);

	}

	function label($offset = 0)

	{

		// Search Params(Country And City)

		// CMS - Image(On Home Page)

		// 		$page_data = array ();

		// 		$post_data = $this->input->post ();

		// 		// debug($post_data);exit;

		// 		if (valid_array ( $post_data ) == true) {

		// 			$city_origin = $post_data ['city'];

		// 			$category = $post_data ['category'];

		// 			$rating = $post_data ['rating'];

		// 			$price = $post_data ['price'];

		// 			$hotel_name = $post_data ['hotel_name'];

		// 			// FILE UPLOAD

		// 			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {

		// 				$config ['upload_path'] = $this->template->domain_image_upload_path ();

		// 				$temp_file_name = $_FILES ['top_destination'] ['name'];

		// 				$config ['allowed_types'] = '*';

		// 				$config ['file_name'] = 'top-dest-hotel-' . $city_origin;

		// 				$config ['max_size'] = '1000000';

		// 				$config ['max_width'] = '';

		// 				$config ['max_height'] = '';

		// 				$config ['remove_spaces'] = false;

		// 				// UPDATE

		// //debug($config);exit;

		// 				$temp_record = $this->custom_db->single_table_records ( 'all_api_city_master', 'image', array (

		// 						'origin' => $city_origin 

		// 				) );

		// 				$top_destination_image = $temp_record ['data'] [0] ['image'];

		// 				// DELETE OLD FILES

		// 				if (empty ( $top_destination_image ) == false) {

		// 					$temp_top_destination_image = $this->template->domain_image_full_path ( $top_destination_image ); // GETTING FILE PATH

		// 					if (file_exists ( $temp_top_destination_image )) {

		// 						unlink ( $temp_top_destination_image );

		// 					}

		// 				}

		// 				// UPLOAD IMAGE

		// 				$this->load->library ( 'upload', $config );

		// 				$this->upload->initialize ( $config );

		// 				if (! $this->upload->do_upload ( 'top_destination' )) {

		// 					echo $this->upload->display_errors ();

		// 				} else {

		// 					$image_data = $this->upload->data ();

		// 				}

		// 				// debug($image_data);exit;

		// 				$this->custom_db->update_record ( 'all_api_city_master', array (

		// 						'top_destination' => ACTIVE,

		// 						'image' => $image_data ['file_name'],

		// 						'cat' => $post_data ['category'],

		// 						'rating' => $post_data ['rating'],

		// 						'price' => $post_data ['price'],

		// 						'hotel_name' => $post_data ['hotel_name'],

		// 				), array (

		// 						'origin' => $city_origin 

		// 				) );

		// 				set_update_message ();

		// 			}

		// 			refresh ();

		// 		}

		// 		$filter = array (

		// 				'top_destination' => ACTIVE 

		// 		);

		// 		$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (

		// 				'country_name !=' => '' 

		// 		), 0, 1000, array (

		// 				'country_name' => 'ASC' 

		// 		) );

		// 		$data_list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', $filter, 0, 100000, array (

		// 				'top_destination' => 'DESC',

		// 				'city_name' => 'ASC' 

		// 		) );

		// 		//debug($data_list);exit;

		// 		if ($country_list ['status'] == SUCCESS_STATUS) {

		// 			$page_data ['country_list'] = magical_converter ( array (

		// 					'k' => 'iso_country_code',

		// 					'v' => 'country_name' 

		// 			), $country_list );

		// 		}

		// 		$page_data ['data_list'] = @$data_list ['data'];

		// 		$this->template->view ( 'cms/hotel_top_destinations', $page_data );

		$page_data = array();

		$post_data = $this->input->post();

		if (valid_array($post_data) == true) {

			$label_name = $post_data['label_name'];

			$module = $post_data['module'];

			$alt_text = $post_data['alt_text'];

			if (valid_array($_FILES) == true and $_FILES['top_destination']['error'] == 0 and $_FILES['top_destination']['size'] > 0) {

				$config['upload_path'] = $this->template->domain_image_upload_path();

				$temp_file_name = $_FILES['top_destination']['name'];

				$config['allowed_types'] = '*';

				$config['file_name'] = 'top-dest-hotel-' . $label_name;

				$config['max_size'] = '1000000';

				$config['max_width'] = $post_data['img_width'];

				$config['max_height'] = $post_data['img_height'];

				$config['remove_spaces'] = false;

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (! $this->upload->do_upload('top_destination')) {

					// echo $this->upload->display_errors ();

					$this->session->set_flashdata('error', $this->upload->display_errors());

					redirect('cms/label');

				} else {

					$image_data = $this->upload->data();

				}

			}

			// debug($image_data);exit;

			$data['label_name'] = $label_name;

			$data['module'] = $module;

			$data['image'] = $image_data['file_name'];

			$data['alt_text'] = $alt_text;

			$data['api_module'] = $post_data['api_module'];

			$data['display_page'] = $post_data['display_page'];

			$data['display_side'] = $post_data['display_side'];

			$data['display'] = $post_data['display'];

			$data['count'] = $post_data['count'];

			if (!isset($post_data['date']) || $post_data['date'] == '') {

				$post_data['date'] = '0000-00-00';

			}

			$data['date'] = $post_data['date'];

			$data['message'] = $post_data['message'];

			$this->custom_db->insert_record('label', $data);

		}

		$filter = array(

			// 'status' => ACTIVE

		);

		$total_records = count($this->custom_db->single_table_records('label', '*')['data']);

		$data_list = $this->custom_db->single_table_records('label', '*', $filter, $offset, RECORDS_RANGE_1, array(

			'status' => 'DESC',

			'label_name' => 'ASC'

		));

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/label/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_1;

		$this->pagination->initialize($config);

		$page_data['data_list'] = @$data_list['data'];

		// debug($page_data);exit;

		$this->template->view('cms/label', $page_data);

	}

	function edit_label($origin)

	{

		// Fetch label data based on origin

		$data['label'] = $this->custom_db->get_data_by_id('label', $origin);

		if (!$data['label']) {

			$this->session->set_flashdata('error', 'Label not found.');

			redirect(base_url() . 'cms/label');

		}

		// Load the view with the fetched data

		$this->template->view('cms/label_edit', $data);

	}

	function update_label()

	{

		$post_data = $this->input->post();

		if (valid_array($post_data) == true) {

			$condition = array('origin' => $post_data['origin']);

			$label_name = $post_data['label_name'];

			$module = $post_data['module'];

			$alt_text = $post_data['alt_text'];

			$image_data = []; // Default empty array for image data

			if (isset($_FILES['top_destination']) && $_FILES['top_destination']['error'] == 0 && $_FILES['top_destination']['size'] > 0) {

				$config['upload_path'] = $this->template->domain_image_upload_path();

				$config['allowed_types'] = 'jpg|jpeg|png|gif';

				$config['file_name'] = 'top-dest-hotel-' . time();

				$config['max_size'] = 1024; // 1MB

				$config['max_width'] = $post_data['img_width'] ?? 1186;

				$config['max_height'] = $post_data['img_height'] ?? 131;

				$config['remove_spaces'] = true;

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (!$this->upload->do_upload('top_destination')) {

					die("Upload Error: " . $this->upload->display_errors());

				} else {

					$image_data = $this->upload->data();

				}

			}

			// Fetch existing image if no new image is uploaded

			if (empty($image_data)) {

				$existing_record = $this->custom_db->get_data_by_id('label', $post_data['origin']);

				if (!empty($existing_record) && isset($existing_record->image)) {

					$image_data['file_name'] = $existing_record->image;

				}

			}

			$data = array(

				'label_name' => $label_name,

				'module' => $module,

				'image' => isset($image_data['file_name']) ? $image_data['file_name'] : '',

				'alt_text' => $alt_text,

				'api_module' => $post_data['api_module'],

				'display_page' => $post_data['display_page'],

				'display_side' => $post_data['display_side'],

				'display' => $post_data['display'],

				'count' => $post_data['count'],

				'date' => (!isset($post_data['date']) || $post_data['date'] == '') ? '0000-00-00' : $post_data['date'],

				'message' => $post_data['message']

			);

			$status = $this->custom_db->update_record('label', $data, $condition);

			if ($status == QUERY_FAILURE) {

				$this->session->set_flashdata('error', 'Failed to update record.');

			} else {

				$this->session->set_flashdata('success', 'Record updated successfully.');

			}

		}

		redirect('cms/label');

	}

	/*

	 * Deactivate Top Destination

	 */

	function activate_top_destination($origin)

	{

		$status = ACTIVE;

		$info = $this->module_model->update_top_destination($status, $origin);

		redirect(base_url() . 'cms/hotel_top_destinations');

	}

	function deactivate_top_destination($origin)

	{

		$status = INACTIVE;

		$info = $this->module_model->update_top_destination($status, $origin);

		redirect(base_url() . 'cms/hotel_top_destinations');

	}

	function deactivate_label($origin)

	{

		$data = array(

			'status' => 0

		);

		$this->db->where('origin', $origin);

		$this->db->update('label', $data);

		redirect(base_url() . 'cms/label');

	}

	function activate_label($origin)

	{

		$data = array(

			'status' => 1

		);

		$this->db->where('origin', $origin);

		$this->db->update('label', $data);

		redirect(base_url() . 'cms/label');

	}

	function delete_label($origin)

	{

		$this->db->where('origin', $origin);

		$this->db->delete('label');

		redirect(base_url() . 'cms/label');

	}

	function activity_top_destinations($offset = 0)

	{

		// error_reporting(E_ALL);

		// Search Params(Country And City)

		// CMS - Image(On Home Page)

		$page_data = array();

		$post_data = $this->input->post();

		if (valid_array($post_data) == true) {

			$city_origin = $post_data['city'];

			$alt_text = $post_data['alt_text'];

			$domain = $post_data['domain'];

			// FILE UPLOAD

			if (valid_array($_FILES) == true and $_FILES['top_destination']['error'] == 0 and $_FILES['top_destination']['size'] > 0) {

				$config['upload_path'] = $this->template->domain_image_upload_path();

				$temp_file_name = $_FILES['top_destination']['name'];

				$config['allowed_types'] = '*';

				$config['file_name'] = 'top-dest-hotel-' . $city_origin;

				$config['max_size'] = '1000000';

				$config['max_width'] = '';

				$config['max_height'] = '';

				$config['remove_spaces'] = false;

				// UPDATE

				$temp_record = $this->custom_db->single_table_records('hb_activity_destinations', 'image', array('origin' => $city_origin));

				$top_destination_image = $temp_record['data'][0]['image'];

				// DELETE OLD FILES

				if (empty($top_destination_image) == false) {

					$temp_top_destination_image = $this->template->domain_image_upload_path($top_destination_image); // GETTING FILE PATH

					if (file_exists($temp_top_destination_image)) {

						unlink($temp_top_destination_image);

					}

				}

				// UPLOAD IMAGE

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (! $this->upload->do_upload('top_destination')) {

					echo $this->upload->display_errors();

				} else {

					$image_data = $this->upload->data();

				}

				// debug($image_data);exit;

				$this->custom_db->update_record('hb_activity_destinations', array(

					'top_destination' => ACTIVE,

					'image' => $image_data['file_name'],

					'alt_text' => $alt_text,

					'domain' => $domain

				), array(

					'origin' => $city_origin

				));

				set_update_message();

			}

			refresh();

		}

		$filter = array(

			'top_destination' => ACTIVE

		);

		$city_list = $temp_record = $this->custom_db->single_table_records('hb_activity_destinations', '*');

		$data_list = $this->custom_db->single_table_records('hb_activity_destinations', '*', $filter, 0, 100000, array(

			'top_destination' => 'DESC',

			'destination_name' => 'ASC'

		));

		$page_data['data_list'] = @$data_list['data'];

		$page_data['city_list'] = @$city_list['data'];

		//debug($page_data);exit;

		$this->template->view('cms/activity_top_destinations', $page_data);

	}

	/*

	 * Deactivate Top Destination

	 */

	function deactivate_activity_top_destination($origin)

	{

		$status = INACTIVE;

		$info = $this->module_model->update_activity_top_destination($status, $origin);

		redirect(base_url() . 'cms/activity_top_destinations');

	}

	function activity_top_destinations_old($offset = 0)

	{

		// error_reporting(E_ALL);

		// Search Params(Country And City)

		// CMS - Image(On Home Page)

		$page_data = array();

		$post_data = $this->input->post();

		if (valid_array($post_data) == true) {

			$city_origin = $post_data['city'];

			$alt_text = $post_data['alt_text'];

			// FILE UPLOAD

			if (valid_array($_FILES) == true and $_FILES['top_destination']['error'] == 0 and $_FILES['top_destination']['size'] > 0) {

				$config['upload_path'] = $this->template->domain_image_upload_path();

				$temp_file_name = $_FILES['top_destination']['name'];

				$config['allowed_types'] = '*';

				$config['file_name'] = 'top-dest-hotel-' . $city_origin;

				$config['max_size'] = '1000000';

				$config['max_width'] = '';

				$config['max_height'] = '';

				$config['remove_spaces'] = false;

				// UPDATE

				$temp_record = $this->custom_db->single_table_records('api_sightseeing_destination_list', 'image', array('origin' => $city_origin));

				$top_destination_image = $temp_record['data'][0]['image'];

				// DELETE OLD FILES

				if (empty($top_destination_image) == false) {

					$temp_top_destination_image = $this->template->domain_image_upload_path($top_destination_image); // GETTING FILE PATH

					if (file_exists($temp_top_destination_image)) {

						unlink($temp_top_destination_image);

					}

				}

				// UPLOAD IMAGE

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (! $this->upload->do_upload('top_destination')) {

					echo $this->upload->display_errors();

				} else {

					$image_data = $this->upload->data();

				}

				// debug($image_data);exit;

				$this->custom_db->update_record('api_sightseeing_destination_list', array(

					'top_destination' => ACTIVE,

					'image' => $image_data['file_name'],

					'alt_text' => $alt_text

				), array(

					'origin' => $city_origin

				));

				set_update_message();

			}

			refresh();

		}

		$filter = array(

			'top_destination' => ACTIVE

		);

		$city_list = $temp_record = $this->custom_db->single_table_records('api_sightseeing_destination_list', '*');

		$data_list = $this->custom_db->single_table_records('api_sightseeing_destination_list', '*', $filter, 0, 100000, array(

			'top_destination' => 'DESC',

			'destination_name' => 'ASC'

		));

		$page_data['data_list'] = @$data_list['data'];

		$page_data['city_list'] = @$city_list['data'];

		//debug($page_data);exit;

		$this->template->view('cms/activity_top_destinations', $page_data);

	}

	/*

	 * Deactivate Top Destination

	 */

	function deactivate_activity_top_destination_old($origin)

	{

		$status = INACTIVE;

		$info = $this->module_model->update_activity_top_destination($status, $origin);

		redirect(base_url() . 'cms/activity_top_destinations');

	}

	/**

	 * Manage Bus Top Destinations

	 */

	function bus_top_destinations($offset = 0)

	{

		// Search Params(Country And City)

		// CMS - Image(On Home Page)

		$page_data = array();

		$post_data = $this->input->post();

		if (valid_array($post_data) == true) {

			$city_origin = $post_data['city'];

			// FILE UPLOAD

			if (valid_array($_FILES) == true and $_FILES['top_destination']['error'] == 0 and $_FILES['top_destination']['size'] > 0) {

				$config['upload_path'] = $this->template->domain_image_upload_path();

				$temp_file_name = $_FILES['top_destination']['name'];

				$config['allowed_types'] = '*';

				$config['file_name'] = 'top-dest-bus-' . $city_origin;

				$config['max_size'] = '1000000';

				$config['max_width'] = '';

				$config['max_height'] = '';

				$config['remove_spaces'] = false;

				// UPDATE

				$temp_record = $this->custom_db->single_table_records('bus_stations_new', 'image', array(

					'origin' => $city_origin

				));

				$top_destination_image = $temp_record['data'][0]['image'];

				// DELETE OLD FILES

				if (empty($top_destination_image) == false) {

					$temp_top_destination_image = $this->template->domain_image_full_path($top_destination_image); // GETTING FILE PATH

					if (file_exists($temp_top_destination_image)) {

						unlink($temp_top_destination_image);

					}

				}

				// UPLOAD IMAGE

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (! $this->upload->do_upload('top_destination')) {

					echo $this->upload->display_errors();

				} else {

					$image_data = $this->upload->data();

				}

				// debug($image_data);exit;

				$this->custom_db->update_record('bus_stations_new', array(

					'top_destination' => ACTIVE,

					'image' => $image_data['file_name']

				), array(

					'origin' => $city_origin

				));

				set_update_message();

			}

			refresh();

		}

		$filter = array(

			'top_destination' => ACTIVE

		);

		$bus_list = $this->custom_db->single_table_records('bus_stations_new', 'name,origin', array(

			'name !=' => ''

		), 0, 10000, array(

			'name' => 'ASC'

		));

		// debug($bus_list);exit;

		$data_list = $this->custom_db->single_table_records('bus_stations_new', '*', $filter, 0, 100000, array(

			'top_destination' => 'DESC',

			'name' => 'ASC'

		));

		if ($bus_list['status'] == SUCCESS_STATUS) {

			$page_data['bus_list'] = magical_converter(array(

				'k' => 'origin',

				'v' => 'name'

			), $bus_list);

		}

		// echo $this->db->last_query();exit;

		$page_data['data_list'] = @$data_list['data'];

		$this->template->view('cms/bus_top_destinations', $page_data);

	}

	/**

	 * Deactivate Top Bus Destination

	 */

	function deactivate_bus_top_destination($origin)

	{

		$status = INACTIVE;

		$info = $this->module_model->update_bus_top_destination($status, $origin);

		redirect(base_url() . 'cms/bus_top_destinations');

	}

	/**

	 * Manage Flight Top Destinations

	 */

	/**

	 * Manage Flight Top Destinations

	 */

	function flight_top_destinations($offset = 0)

	{

		// Search Params(Country And City)

		// CMS - Image(On Home Page)

		$page_data = array();

		$post_data = $this->input->post();

		// debug($post_data);exit;

		if (valid_array($post_data) == true) 
		{

			$from_aiport_origin = $post_data['from_airport'];

			$to_aiport_origin = $post_data['to_airport'];

			$airlines_origin = $post_data['airlines_origin'];

			$starting_price = $post_data['starting_price'];

			if ($from_aiport_origin == $to_aiport_origin) {

				$page_data['message'] = 'From and To Airports must be different';

			} 
			else 
			{

				// FILE UPLOAD

				if (valid_array($_FILES) == true and $_FILES['top_destination']['error'] == 0 and $_FILES['top_destination']['size'] > 0) 
				{

				 	$config['upload_path'] = $this->template->domain_image_upload_path();

				 	$temp_file_name = $_FILES['top_destination']['name'];

				 	$config['allowed_types'] = '*';

				 	$config['file_name'] = 'top-dest-fight-' . $from_aiport_origin;

				 	$config['max_size'] = '1000000';

				 	$config['max_width'] = '';

				 	$config['max_height'] = '';

				 	$config['remove_spaces'] = false;

				// 	// UPDATE

				// 	$from_temp_record = $this->custom_db->single_table_records('flight_airport_list', '*', array(

				// 		'origin' => $from_aiport_origin

				// 	));

				// 	$to_temp_record = $this->custom_db->single_table_records('flight_airport_list', '*', array(

				// 		'origin' => $to_aiport_origin

				// 	));

				// 	$airlines_temp_record = $this->custom_db->single_table_records('airline_list', '*', array(

				// 		'origin' => $airlines_origin

				// 	));

				// 	// $top_destination_image = $temp_record ['data'] [0] ['image'];

				// 	$top_destination_image = '';

				// 	// DELETE OLD FILES

				// 	if (empty($top_destination_image) == false) {

				// 		$temp_top_destination_image = $this->template->domain_image_full_path($top_destination_image); // GETTING FILE PATH

				// 		if (file_exists($temp_top_destination_image)) {

				// 			unlink($temp_top_destination_image);

				// 		}

				 	//}

				// 	// UPLOAD IMAGE

				 	$this->load->library('upload', $config);

					$this->upload->initialize($config);

				 	if (! $this->upload->do_upload('top_destination')) {

						echo $this->upload->display_errors();

				 	} else {

						$image_data = $this->upload->data();

				 	}

				// 	$data['from_airport_name'] = $from_temp_record['data'][0]['airport_city'];

				// 	$data['from_airport_code'] = $from_temp_record['data'][0]['airport_code'];

				// 	$data['to_airport_code'] = $to_temp_record['data'][0]['airport_code'];

				// 	$data['to_airport_name'] = $to_temp_record['data'][0]['airport_city'];

				// 	$data['from_origin'] = $from_aiport_origin;

				// 	$data['to_origin'] = $to_aiport_origin;

				// 	$data['airlines_name'] = $airlines_temp_record['data'][0]['name'];

				// 	$data['airline_origin'] = $airlines_origin;

				// 	$data['starting_price'] = $starting_price;

				 	$data['image'] = $image_data['file_name'];

				 	$data['status'] = 1;

				// 	// debug($data);exit;

				// 	$this->custom_db->insert_record('top_flight_destinations', $data);

				// 	$insertDynamicHotelUrlsSeoData = array(

				// 		'title' => $post_data['seo_title'],

				// 		'keyword' => htmlspecialchars($post_data['seo_keywords'], ENT_QUOTES),

				// 		'description' => $post_data['seo_description'],

				// 		'module' => $from_temp_record['data'][0]['airport_city'] . '-' . $to_temp_record['data'][0]['airport_city']

				// 	);

				// 	$this->db->insert('seo', $insertDynamicHotelUrlsSeoData);

				// 	$insertDynamicHotelUrlsSeoData = array(

				// 		'title' => $post_data['seo_title'],

				// 		'keyword' => htmlspecialchars($post_data['seo_keywords'], ENT_QUOTES),

				// 		'description' => $post_data['seo_description'],

				// 		'module' => $from_temp_record['data'][0]['airport_code'] . '-' . $to_temp_record['data'][0]['airport_code']

				// 	);

				// 	$this->db->insert('seo', $insertDynamicHotelUrlsSeoData);

				// 	// debug($image_data);exit;

				// 	set_update_message();

				 }

				// UPDATE

				$from_temp_record = $this->custom_db->single_table_records('flight_airport_list', '*', array(

					'origin' => $from_aiport_origin

				));

				$to_temp_record = $this->custom_db->single_table_records('flight_airport_list', '*', array(

					'origin' => $to_aiport_origin

				));

				$airlines_temp_record = $this->custom_db->single_table_records('airline_list', '*', array(

					'origin' => $airlines_origin

				));

				 //$top_destination_image = $temp_record ['data'] [0] ['image'];

				//$top_destination_image = '';

				// DELETE OLD FILES

				$data['from_airport_name'] = $from_temp_record['data'][0]['airport_city'];

				$data['from_airport_code'] = $from_temp_record['data'][0]['airport_code'];

				$data['to_airport_code'] = $to_temp_record['data'][0]['airport_code'];

				$data['to_airport_name'] = $to_temp_record['data'][0]['airport_city'];

				$data['from_origin'] = $from_aiport_origin;

				$data['to_origin'] = $to_aiport_origin;

				$data['airlines_name'] = $airlines_temp_record['data'][0]['name'];

				$data['airline_origin'] = $airlines_origin;

				$data['starting_price'] = $starting_price;

				$data['category_id'] = 1; //$post_data['category_id'];

				$data['alt'] = $post_data['alt'];

				//$data['status'] = 1;

				 //debug($data);exit;

				$this->custom_db->insert_record('top_flight_destinations', $data);

				/*$insertDynamicHotelUrlsSeoData = array(

					'title' => $post_data['seo_title'],

					'keyword' => htmlspecialchars($post_data['seo_keywords'], ENT_QUOTES),

					'description' => $post_data['seo_description'],

					'module' => $from_temp_record['data'][0]['airport_city'] . '-' . $to_temp_record['data'][0]['airport_city']

				);

				$this->db->insert('seo', $insertDynamicHotelUrlsSeoData);

				$insertDynamicHotelUrlsSeoData = array(

					'title' => $post_data['seo_title'],

					'keyword' => htmlspecialchars($post_data['seo_keywords'], ENT_QUOTES),

					'description' => $post_data['seo_description'],

					'module' => $from_temp_record['data'][0]['airport_code'] . '-' . $to_temp_record['data'][0]['airport_code']

				);

				$this->db->insert('seo', $insertDynamicHotelUrlsSeoData);
				*/
				// debug($image_data);exit;

				//set_update_message();

			}
			//debug($post_data);exit;

		}


		$flight_list = $this->custom_db->single_table_records('flight_airport_list', 'airport_city,origin,airport_code', array(

			'airport_city !=' => ''

		), 0, 10000, array(

			'airport_city' => 'ASC'

		));

		$flight_list2 = $flight_list;

		$page_data['flight_list2'] = $flight_list2;

		// debug($flight_list);exit;

		$airlines_list = $this->custom_db->single_table_records('airline_list', 'name,origin', array(

			'name !=' => ''

		), 0, 10000, array(

			'name' => 'ASC'

		));

		$top_flights = $this->custom_db->single_table_records('top_flight_destinations', '*');

		if($top_flights['status'] == 1)
		{
			$total_records = count($top_flights['data']);
		}
		else
		{
			$total_records = 0;
		}

		$data_list = $this->custom_db->single_table_records('top_flight_destinations', '*', '', $offset, RECORDS_RANGE_1, array(

			'origin' => 'DESC',

		));

		$category_list = $this->custom_db->single_table_records('top_destination_categories', '*', ['status' => ACTIVE, 'module' => 'flight'], 0, 100000, array(

			'status' => 'DESC',

			'category_name' => 'ASC'

		));

		$page_data['category_list'] = @$category_list['data'];

		//echo $this->db->last_query();exit;

		if ($flight_list['status'] == SUCCESS_STATUS) {

			$page_data['flight_list'] = magical_converter(array(

				'k' => 'origin',

				'v' => 'airport_city'

			), $flight_list);

		}

		$page_data['airlines_list'] = magical_converter(array(

			'k' => 'origin',

			'v' => 'name'

		), $airlines_list);

		// debug($page_data);exit;

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/flight_top_destinations/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_1;

		$this->pagination->initialize($config);

		$page_data['data_list'] = @$data_list['data'];

		$page_data['offset'] = $offset;

		$this->template->view('cms/flight_top_destinations', $page_data);

	}

	/**

	 * Deactivate Top Bus Destination

	 */

	function deactivate_flight_top_destination($origin)

	{

		$status = INACTIVE;

		$info = $this->module_model->update_flight_top_destination($status, $origin);

		redirect(base_url() . 'cms/flight_top_destinations');

	}

	/**

	 * Deactivate Top Bus Destination

	 */

	function activate_flight_top_destination($origin)

	{

		$status = ACTIVE;

		$info = $this->module_model->update_flight_top_destination($status, $origin);

		redirect(base_url() . 'cms/flight_top_destinations');

	}

	/* Delete Flight Top Destinations */

	public function delete_flight_top_destination($origin, $image_name)

	{

		$image_path = $this->template->domain_image_upload_path() . $image_name;

		unlink($image_path);

		$this->custom_db->delete_record('top_flight_destinations', array('origin' => $origin));

		redirect('cms/flight_top_destinations');

	}

	/**

	 * Static Page Content

	 */

	function add_cms_page($id = 0)

	{

		$this->form_validation->set_message('required', 'Required.');

		// check for negative id

		// valid_integer($id);

		// validation rules

		$post_data = $this->input->post();

		// get data

		$cols = ' * ';

		if (valid_array($post_data) == false) {



			if (intval($id) > 0) {

				// edit data

				$tmp_data = $this->custom_db->single_table_records('cms_pages', '', array( 'page_id' => $id ));

				if (valid_array($tmp_data['data'][0])) {

					$data['page_title'] = $tmp_data['data'][0]['page_title'];

					$data['page_description'] = $tmp_data['data'][0]['page_description'];

					$data['page_seo_title'] = $tmp_data['data'][0]['page_seo_title'];

					$data['page_seo_keyword'] = $tmp_data['data'][0]['page_seo_keyword'];

					$data['page_seo_description'] = $tmp_data['data'][0]['page_seo_description'];

					$data['page_position'] = $tmp_data['data'][0]['page_position'];

				} else {

					redirect('cms/add_cms_page');

				}

			}

		} elseif (valid_array($post_data)) {

			$this->form_validation->set_rules('page_title', 'Page Title', 'required');

			$this->form_validation->set_rules('page_description', 'Page Description', 'required');

			$this->form_validation->set_rules('page_seo_title', 'Page SEO Title', 'required');

			$this->form_validation->set_rules('page_seo_keyword', 'Page SEO Keyword', 'required');

			$this->form_validation->set_rules('page_seo_description', 'Page SEO Description', 'required');

			$this->form_validation->set_rules('page_position', 'Page Position', 'required');

			$data['page_title'] = $title = $this->input->post('page_title');

			$data['page_description'] = $this->input->post('page_description');

			$data['page_seo_title'] = $this->input->post('page_seo_title');

			$data['page_seo_keyword'] = $this->input->post('page_seo_keyword');

			$data['page_seo_description'] = $this->input->post('page_seo_description');

			$data['page_position'] = $this->input->post('page_position');

			$data['page_label'] = $this->uniqueLabel(substr($title, 0, 100));

			//debug($data);exit;

			if ($this->form_validation->run()) {

				// add / update data

				if (intval($id) > 0) {

					$this->custom_db->update_record('cms_pages', $data, array(

						'page_id' => $id

					));

				} else {

					$this->custom_db->insert_record('cms_pages', $data);

				}

				redirect('cms/add_cms_page');

			}

		}

		$data['ID'] = $id;

		// get all sub admin

		$tmp_data = $this->custom_db->single_table_records('cms_pages', $cols);

		$data['sub_admin'] = '';

		$data['sub_admin'] = $tmp_data['data'];

		$this->template->view('cms/add_cms_page', $data);

	}

	/*

	Delete CMS page

	*/

	function delete_cms_page($page_id)

	{

		$this->custom_db->delete_record('cms_pages', array('page_id' => $page_id));

		redirect('cms/add_cms_page');

	}

	/**

	 * Status update of Static Page Content

	 */

	function cms_status($id = '', $status = 'D')

	{

		if ($id > 0) {

			if (strcmp($status, 'D') == 0) {

				$status = 0;

			} else {

				$status = 1;

			}

			$this->custom_db->update_record('cms_pages', array(

				'page_status' => $status

			), array(

				'page_id' => $id

			));

		}

		redirect('cms/add_cms_page');

	}

	public function uniqueLabel($string)

	{

		//Lower case everything

		$string = strtolower($string);

		//Make alphanumeric (removes all other characters)

		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

		//Clean up multiple dashes or whitespaces

		$string = preg_replace("/[\s-]+/", " ", $string);

		//Convert whitespaces and underscore to dash

		$string = preg_replace("/[\s_]/", "-", $string);

		return $string;

	}

	//Adding the Headings in Home Page

	function add_home_page_heading()

	{

		$post_data = $this->input->post();

		$get_data = $this->input->get();

		// debug($get_data);exit;

		if (valid_array($post_data)) {

			$data['title'] = ucwords($post_data['header_title']);

			$data['status'] = ACTIVE;

			$list = $this->custom_db->single_table_records('home_page_headings', '*', array(

				'origin' => $get_data['origin']

			));

			$head_data = $this->custom_db->single_table_records('home_page_headings', '*', array(

				'title' => $post_data['header_title']

			));

			if ($list['status'] == FAILURE_STATUS) {

				if ($head_data['status'] == FAILURE_STATUS) {

					$insert_id = $this->custom_db->insert_record('home_page_headings', $data);

				} else {

					redirect('cms/add_home_page_heading/Duplicate Title');

				}

			} else {

				if (!empty($get_data) && $get_data['origin'] > 0) {

					$this->custom_db->update_record('home_page_headings', array(

						'title' => $post_data['header_title']

					), array(

						'origin' => $get_data['origin']

					));

				}

			}

			redirect('cms/home_page_headings');

		} else {

			$page_data = array();

			if (valid_array($get_data)) {

				$list = $this->custom_db->single_table_records('home_page_headings', '*', array(

					'origin' => $get_data['origin']

				));

				$page_data['title'] = $list['data'][0]['title'];

			}

			$this->template->view('cms/add_home_page_heading', $page_data);

		}

	}

	//activating or deactivating the home page headers

	function home_page_headings()

	{

		$page_data = array();

		$data_list = $this->custom_db->single_table_records('home_page_headings', '*', '', 0, 100000);

		$page_data['data_list'] = @$data_list['data'];

		$this->template->view('cms/home_page_headings', $page_data);

	}

	/**

	 * Activate home page header

	 */

	function activate_heading($origin)

	{

		$info = $this->custom_db->update_record('home_page_headings', array(

			'status' => ACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	/**

	 * DeActivate home page header

	 */

	function deactivate_heading($origin)

	{

		$info = $this->custom_db->update_record('home_page_headings', array(

			'status' => INACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	/*why choose us home page*/

	function why_choose_us()

	{

		$page_data = array();

		$data_list = $this->custom_db->single_table_records('why_choose_us', '*', '', 0, 100000);

		$page_data['data_list'] = @$data_list['data'];

		// debug($page_data);exit;

		$this->template->view('cms/why_choose_us', $page_data);

	}

	function add_why_choose_us()

	{

		$post_data = $this->input->post();

		$get_data = $this->input->get();

		// debug($get_data);exit;

		if (valid_array($post_data)) {

			// debug($post_data);exit;

			$data['title'] = ucwords($post_data['header_title']);

			$data['description'] = $post_data['header_description'];

			$data['icon'] = $post_data['header_icon'];

			$data['status'] = ACTIVE;

			$list = $this->custom_db->single_table_records('why_choose_us', '*', array(

				'origin' => $get_data['origin']

			));

			$why_choose_data = $this->custom_db->single_table_records('why_choose_us', '*', array(

				'title' => $post_data['header_title'],

				'description' => $post_data['header_description'],

				'icon' => $post_data['header_icon']

			));

			if ($list['status'] == FAILURE_STATUS) {

				if ($why_choose_data['status'] == FAILURE_STATUS) {

					$insert_id = $this->custom_db->insert_record('why_choose_us', $data);

				} else {

					redirect('cms/add_why_choose_us/Duplicate Title');

				}

			} else {

				// debug($get_data);exit;

				if (!empty($get_data) && valid_array($get_data)) {

					$this->custom_db->update_record('why_choose_us', array(

						'title' => ucwords($post_data['header_title']),

						'description' => $post_data['header_description'],

						'icon' => $post_data['header_icon']

					), array(

						'origin' => $get_data['origin']

					));

				}

			}

			// debug($insert_id);exit;

			redirect('cms/why_choose_us');

		} else {

			$page_data = array();

			if (valid_array($get_data)) {

				$list = $this->custom_db->single_table_records('why_choose_us', '*', array(

					'origin' => $get_data['origin']

				));

				$page_data['title'] = $list['data'][0]['title'];

				$page_data['description'] = $list['data'][0]['description'];

				$page_data['icon'] = $list['data'][0]['icon'];

			}

			$this->template->view('cms/add_why_choose_us', $page_data);

		}

	}

	function activate_why_choose($origin)

	{

		$info = $this->custom_db->update_record('why_choose_us', array(

			'status' => ACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	function deactivate_why_choose($origin)

	{

		$info = $this->custom_db->update_record('why_choose_us', array(

			'status' => INACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	function top_airlines()

	{

		$page_data = array();

		$data_list = $this->custom_db->single_table_records('top_airlines', '*', '', 0, 100000);

		$page_data['data_list'] = @$data_list['data'];

		// debug($page_data);exit;

		$this->template->view('cms/top_airlines', $page_data);

	}

	function add_top_airlines()

	{

		$post_data = $this->input->post();

		$get_data = $this->input->get();

		// debug($_FILES);exit;

		if (valid_array($post_data)) {

			$data['airline_name'] = ucwords($post_data['airline_name']);

			$data['status'] = ACTIVE;

			if (valid_array($_FILES) == true and $_FILES['airline_logo']['error'] == 0 and $_FILES['airline_logo']['size'] > 0) {

				if (function_exists("check_mime_image_type")) {

					if (!check_mime_image_type($_FILES['airline_logo']['tmp_name'])) {

						echo "Please select the image files only (gif|jpg|png|jpeg)";

						exit;

					}

				}

				$config['upload_path'] = $this->template->domain_top_airline_upload_path();

				$temp_file_name = $_FILES['airline_logo']['name'];

				$config['allowed_types'] = 'gif|jpg|png|jpeg';

				$config['file_name'] = get_domain_key() . $temp_file_name;

				$config['max_size'] = '1000000';

				$config['max_width']  = '';

				$config['max_height']  = '';

				$config['remove_spaces']  = false;

				// echo $config['upload_path'];exit;

				//UPLOAD IMAGE

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (! $this->upload->do_upload('airline_logo')) {

					echo $this->upload->display_errors();

				} else {

					$image_data =  $this->upload->data();

					$data['logo'] = @$image_data['file_name'];

				}

				/*UPDATING IMAGE */

			}

			$list = $this->custom_db->single_table_records('top_airlines', '*', array(

				'origin' => $get_data['origin']

			));

			if ($list['status'] == FAILURE_STATUS) {

				$insert_id = $this->custom_db->insert_record('top_airlines', $data);

			} else {

				if (isset($data['logo'])) {

					$logo = $data['logo'];

				} else {

					$logo = $list['data'][0]['logo'];

				}

				// debug($get_data);exit;

				if (!empty($get_data) && valid_array($get_data)) {

					$this->custom_db->update_record('top_airlines', array(

						'airline_name' => ucwords($post_data['airline_name']),

						'logo' => $logo

					), array(

						'origin' => $get_data['origin']

					));

				}

			}

			// debug($insert_id);exit;

			redirect('cms/top_airlines');

		} else {

			if (valid_array($get_data)) {

				$list = $this->custom_db->single_table_records('top_airlines', '*', array(

					'origin' => $get_data['origin']

				));

				// debug($list);exit;

				$page_data['airline_name'] = $list['data'][0]['airline_name'];

				$page_data['logo'] = $list['data'][0]['logo'];

			}

			$page_data['airline_list'] = $this->custom_db->single_table_records('airline_list', '*', '');

			$this->template->view('cms/add_top_airlines', $page_data);

		}

	}

	function activate_top_airline($origin)

	{

		$info = $this->custom_db->update_record('top_airlines', array(

			'status' => ACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	function deactivate_top_airline($origin)

	{

		$info = $this->custom_db->update_record('top_airlines', array(

			'status' => INACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	/*Tour Styles on Home Page*/

	function tour_styles()

	{

		$page_data = array();

		$data_list = $this->custom_db->single_table_records('tour_styles', '*', '', 0, 100000);

		$page_data['data_list'] = @$data_list['data'];

		// debug($page_data);exit;

		$this->template->view('cms/tour_styles', $page_data);

	}

	function add_tour_styles()

	{

		$post_data = $this->input->post();

		$get_data = $this->input->get();

		// debug($_FILES);exit;

		if (valid_array($post_data)) {

			$destination_data = $this->custom_db->single_table_records('api_sightseeing_destination_list', '*', array(

				'origin' => $post_data['destination']

			));

			$category_data = $this->custom_db->single_table_records('activity_category_list', '*', array(

				'category_id' => $post_data['category']

			));

			$data['destination_name'] = $destination_data['data'][0]['destination_name'];

			$data['destination_id'] = $destination_data['data'][0]['destination_id'];

			$data['category_name'] = $category_data['data'][0]['category_name'];

			$data['category_id'] = $category_data['data'][0]['category_id'];

			$data['status'] = ACTIVE;

			// debug($_FILES);exit;

			if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {

				if (function_exists("check_mime_image_type")) {

					if (!check_mime_image_type($_FILES['image']['tmp_name'])) {

						echo "Please select the image files only (gif|jpg|png|jpeg)";

						exit;

					}

				}

				$config['upload_path'] = $this->template->domain_tour_style_upload_path();

				$temp_file_name = $_FILES['image']['name'];

				$config['allowed_types'] = 'gif|jpg|png|jpeg';

				$config['file_name'] = get_domain_key() . $temp_file_name;

				$config['max_size'] = '1000000';

				$config['max_width']  = '';

				$config['max_height']  = '';

				$config['remove_spaces']  = false;

				// echo $config['upload_path'];exit;

				//UPLOAD IMAGE

				$this->load->library('upload', $config);

				$this->upload->initialize($config);

				if (! $this->upload->do_upload('image')) {

					echo $this->upload->display_errors();

				} else {

					$image_data =  $this->upload->data();

					$data['image'] = @$image_data['file_name'];

				}

				/*UPDATING IMAGE */

			}

			// debug($data);exit;

			$list = $this->custom_db->single_table_records('tour_styles', '*', array(

				'origin' => $get_data['origin']

			));

			if ($list['status'] == FAILURE_STATUS) {

				$insert_id = $this->custom_db->insert_record('tour_styles', $data);

			} else {

				if (isset($data['image'])) {

					$image = $data['image'];

				} else {

					$image = $list['data'][0]['image'];

				}

				// debug($get_data);exit;

				if (!empty($get_data) && valid_array($get_data)) {

					$this->custom_db->update_record('tour_styles', array(

						'destination_name' => $data['destination_name'],

						'destination_id' => $data['destination_id'],

						'category_name' => $data['category_name'],

						'category_id' => $data['category_id'],

						'image' => $image

					), array(

						'origin' => $get_data['origin']

					));

				}

			}

			// debug($insert_id);exit;

			redirect('cms/tour_styles');

		} else {

			if (valid_array($get_data)) {

				$list = $this->custom_db->single_table_records('tour_styles', '*', array(

					'origin' => $get_data['origin']

				));

				$page_data['destination_id'] = $list['data'][0]['origin'];

				$page_data['category_id'] = $list['data'][0]['category_id'];

				$page_data['image'] = $list['data'][0]['image'];

			}

			$page_data['destination_list'] = $this->custom_db->single_table_records('api_sightseeing_destination_list', '*', '');

			$page_data['category_list'] = $this->custom_db->single_table_records('activity_category_list', '*', '');

			$this->template->view('cms/add_tour_styles', $page_data);

		}

	}

	function activate_tour_style($origin)

	{

		$info = $this->custom_db->update_record('tour_styles', array(

			'status' => ACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	function deactivate_tour_style($origin)

	{

		$info = $this->custom_db->update_record('tour_styles', array(

			'status' => INACTIVE

		), array(

			'origin' => $origin

		));

		exit;

	}

	function add_contact_address()

	{

		$post_data = $this->input->post();

		// debug($post_data);exit;

		if (valid_array($post_data)) {

			$this->custom_db->update_record('domain_list', array(

				'address' => $post_data['address'],

				'phone' => $post_data['phone'],

				'email' => $post_data['email'],

			), array(

				'origin' => $post_data['domain_id']

			));

			$this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));

			refresh();

		}

		$domain_data = $this->custom_db->single_table_records('domain_list', '*', '');

		// debug($footer_data);exit;

		$page_data['address'] = $domain_data['data'][0]['address'];

		$page_data['domain_id'] = $domain_data['data'][0]['origin'];

		$page_data['email'] = $domain_data['data'][0]['email'];

		$page_data['phone'] = $domain_data['data'][0]['phone'];

		$this->template->view('cms/add_contact_address', $page_data);

	}

	public function delete_heading($origin)

	{

		$this->custom_db->delete_record('home_page_headings', array('origin' => $origin));

		redirect('cms/home_page_headings');

	}

	public function delete_why_choose($origin)

	{
		$this->custom_db->delete_record('why_choose_us', array('origin' => $origin));

		redirect('cms/why_choose_us');

	}

	public function delete_top_airline($origin)

	{

		$this->custom_db->delete_record('why_choose_us', array('origin' => $origin));

		redirect('cms/top_airlines');

	}

	public function delete_tour_styles($origin)

	{

		$this->custom_db->delete_record('tour_styles', array('origin' => $origin));

		redirect('cms/tour_styles');

	}

	public function seo($offset = 0)

	{

		$total_records = count($this->custom_db->single_table_records('seo', '*')['data']);

		$data_list = $this->custom_db->single_table_records('seo', '*', [], $offset, RECORDS_RANGE_1);

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/seo/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_1;

		$this->pagination->initialize($config);

		$page_data['data_list'] = @$data_list;

		$this->template->view('cms/seo', $page_data);

	}

	public function edit_seo($id)

	{

		$page_data = array();

		$filter = ['id' => $id];

		$data_list = $this->custom_db->single_table_records('seo', '*', $filter, 0, 100000);

		$page_data['data_list'] = @$data_list['data'];

		$this->template->view('cms/seo_edit', $page_data);

	}

	public function update_seo_action()

	{

		$insert_data = [];

		$post_data = $this->input->post();

		// debug($post_data);exit;

		$BID = $post_data['BID'];

		if (valid_array($post_data) == true) {

			//POST DATA formating to update

			$insert_data = array('description' => $post_data['description'], 'title' => $post_data['title'], 'keyword' => $post_data['keyword']);

		}

		/*UPDATING OTHER FIELDS*/

		$this->custom_db->update_record('seo', $insert_data, array('id' => $BID));

		$this->seo();

	}

	/* Terms and conditions for all modules voucher page */

	function terms_conditions()

	{

		$data['data_list'] = $this->custom_db->single_table_records('terms_conditions');

		$this->template->view('cms/terms_conditions', $data);

	}

	public function edit_terms_conditions($origin)

	{

		$page_data = array();

		$data_list = $this->custom_db->single_table_records('terms_conditions', '*', array('origin' => $origin));

		// debug($data_list);exit;

		$page_data['data_list'] = @$data_list['data'];

		$this->template->view('cms/terms_conditions_edit', $page_data);

	}

	public function update_terms_action($id)

	{

		$post_data = $this->input->post();

		// debug($post_data);exit;

		if (valid_array($post_data) == true) {

			//POST DATA formating to update

			$insert_data = array('description' => $post_data['description']);

			// debug($insert_data);exit;

			$this->custom_db->update_record('terms_conditions', $insert_data, array('origin' => $id));

		}

		redirect('cms/terms_conditions');

	}

	function add_customer_support($id = '')

	{

		// privilege_handler('p54');

		$this->form_validation->set_message('required', 'Required.');

		// check for negative id

		valid_integer($id);

		// validation rules

		$post_data = $this->input->post();

		// debug($post_data);exit;

		// get data

		$cols = ' * ';

		if (valid_array($post_data) == false) {

			if (intval($id) > 0) {

				// edit data

				$tmp_data = $this->custom_db->single_table_records('customer_support', '', array(

					'origin' => $id

				));

				// debug($tmp_data);exit;

				if (valid_array($tmp_data['data'][0])) {

					$data['question'] = $tmp_data['data'][0]['question'];

					$data['answer'] = $tmp_data['data'][0]['answer'];

				} else {

					redirect('cms/add_customer_support');

				}

			}

		} elseif (valid_array($post_data)) {

			$this->form_validation->set_rules('question', 'Question', 'required');

			$this->form_validation->set_rules('answer', 'Answer', 'required');

			$data['question']  = $this->input->post('question');

			$data['answer'] = $this->input->post('answer');

			// debug($data);exit;

			if ($this->form_validation->run()) {

				// add / update data

				if (intval($id) > 0) {

					$this->custom_db->update_record('customer_support', $data, array(

						'origin' => $id

					));

				} else {

					$this->custom_db->insert_record('customer_support', $data);

				}

				redirect('cms/add_customer_support');

			}

		}

		$data['ID'] = $id;

		// get all sub admin

		$tmp_data = $this->custom_db->single_table_records('customer_support', $cols);

		$data['sub_admin'] = '';

		$data['sub_admin'] = $tmp_data['data'];

		// debug($data);exit;

		$this->template->view('cms/add_customer_support', $data);

	}

	function delete_customer_support($page_id)

	{

		$this->custom_db->delete_record('customer_support', array('origin' => $page_id));

		redirect('cms/add_customer_support');

	}

	function customer_support_status($id = '', $status = '')

	{

		if ($id > 0) {

			if (strcmp($status, 'D') == 0) {

				$status = 0;

			} else {

				$status = 1;

			}

			// echo $status;

			// echo $id;exit;

			$this->custom_db->update_record('customer_support', array(

				'status' => $status

			), array(

				'origin' => $id

			));

		}

		redirect('cms/add_customer_support');

	}

	function blog($id = '')

	{

		// privilege_handler('p54');

		$this->form_validation->set_message('required', 'Required.');

		// check for negative id

		valid_integer($id);

		// validation rules

		$post_data = $this->input->post();

		// debug($post_data);exit;

		// get data

		$cols = ' * ';

		if (valid_array($post_data) == false) {

			if (intval($id) > 0) {

				// edit data

				$tmp_data = $this->custom_db->single_table_records('blog', '', array(

					'origin' => $id

				));

				// debug($tmp_data);exit;

				if (valid_array($tmp_data['data'][0])) {

					$data['title'] = $tmp_data['data'][0]['title'];

					$data['sub_title'] = $tmp_data['data'][0]['sub_title'];

					$data['description'] = $tmp_data['data'][0]['description'];

				} else {

					redirect('cms/add_customer_support');

				}

			}

		} elseif (valid_array($post_data)) {

			$this->form_validation->set_rules('title', 'Title', 'required');

			$this->form_validation->set_rules('sub_title', 'Sub Title', 'required');

			$this->form_validation->set_rules('description', 'Description', 'required');

			$data['title']  = $this->input->post('title');

			$data['sub_title'] = $this->input->post('sub_title');

			$data['description'] = $this->input->post('description');

			// debug($data);exit;

			if ($this->form_validation->run()) {

				// add / update data

				if (intval($id) > 0) {

					$this->custom_db->update_record('blog', $data, array(

						'origin' => $id

					));

				} else {

					$this->custom_db->insert_record('blog', $data);

				}

				redirect('cms/blog');

			}

		}

		$data['ID'] = $id;

		// get all sub admin

		$tmp_data = $this->custom_db->single_table_records('blog', $cols);

		$data['sub_admin'] = '';

		$data['sub_admin'] = $tmp_data['data'];

		// debug($data);exit;

		$this->template->view('cms/blog', $data);

	}

	public function country_master($offset = 0)

	{

		$country_origin = $this->uri->segment(4);

		$total_records = count($this->custom_db->single_table_records('api_country_list', '*')['data']);

		$country_master_data = $this->custom_db->single_table_records('api_country_list', '*', '', $offset, RECORDS_RANGE_3);

		$continent_master_data = $this->custom_db->single_table_records('api_continent_list', '*');

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/country_master/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_3;

		$this->pagination->initialize($config);

		$page_data['total_records'] = $config['total_rows'];

		if (isset($country_origin) && !empty($country_origin)) {

			$single_country_master_data = $this->custom_db->single_table_records('api_country_list', '*', array('origin' => $country_origin));

		}

		$page_data['country_master_data'] = $country_master_data;

		$page_data['continent_master_data'] = $continent_master_data;

		$page_data['single_country_master_data'] = $single_country_master_data;

		$this->template->view('cms/country_master', $page_data);

	}

	public function add_country_master()

	{

		$post_data = $this->input->post();

		if (isset($post_data['country_hidden_origin']) && !empty($post_data['country_hidden_origin'])) {

			$update_data = [

				'api_continent_list_fk' => $post_data['continent'],

				'name' => $post_data['country_name'],

				'country_code' => $post_data['country_code'],

				'iso_country_code' => $post_data['iso_country_code'],

			];

			$this->custom_db->update_record('api_country_list', $update_data, array('origin' => $post_data['country_hidden_origin']));

			$this->session->set_flashdata('success_message', 'Country updated successfully!');

		} else {

			$count_data = $this->custom_db->single_table_records('api_country_list', '*', array('api_continent_list_fk' => $post_data['continent'], 'name' => $post_data['country_name']));

			$count = count($count_data['data']);

			if ($count == 0) {

				$insert_data = [

					'api_continent_list_fk' => $post_data['continent'],

					'name' => $post_data['country_name'],

					'country_code' => $post_data['country_code'],

					'iso_country_code' => $post_data['iso_country_code'],

				];

				$this->custom_db->insert_record('api_country_list', $insert_data);

				$this->session->set_flashdata('success_message', 'Country added successfully!');

			} else {

				$this->session->set_flashdata('success_message', 'The country already exists!');

			}

		}

		redirect('cms/country_master');

	}

	public function states_master($offset = 0)

	{

		$state_origin = $this->uri->segment(4);

		$total_records = count($this->custom_db->single_table_records('api_state_list', '*')['data']);

		$states_master_data = $this->custom_db->single_table_records('api_state_list', '*', '', $offset, RECORDS_RANGE_3);

		$country_master_data = $this->custom_db->single_table_records('api_country_list', '*');

		if (isset($state_origin) && !empty($state_origin)) {

			$single_state_master_data = $this->custom_db->single_table_records('api_state_list', '*', array('origin' => $state_origin));

		}

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/states_master/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_3;

		$this->pagination->initialize($config);

		$page_data['total_records'] = $config['total_rows'];

		$page_data['country_master_data'] = $country_master_data;

		$page_data['states_master_data'] = $states_master_data;

		$page_data['single_state_master_data'] = $single_state_master_data;

		$this->template->view('cms/states_master', $page_data);

	}

	public function add_states_master()

	{

		$post_data = $this->input->post();

		// debug($post_data);exit;

		$codes = explode('-', $post_data['country']);

		if (isset($post_data['state_hidden_origin']) && !empty($post_data['state_hidden_origin'])) {

			$update_data = [

				'state_province' => $post_data['state_name'],

				'country_code' => $codes[1],

				'country_id' => $codes[0]

			];

			$this->custom_db->update_record('api_state_list', $update_data, array('origin' => $post_data['state_hidden_origin']));

			$this->session->set_flashdata('success_message', 'Province updated successfully!');

		} else {

			// debug($codes);exit;

			$insert_data = [

				'state_province' => $post_data['state_name'],

				'country_code' => $codes[1],

				'country_id' => $codes[0]

			];

			$count_data = $this->custom_db->single_table_records('api_state_list', '*', $insert_data);

			$count = count($count_data['data']);

			// echo $count;exit;

			if ($count == 0) {

				$this->custom_db->insert_record('api_state_list', $insert_data);

				$this->session->set_flashdata('success_message', 'Province added successfully!');

			} else {

				$this->session->set_flashdata('success_message', 'The province already exists!');

			}

		}

		redirect('cms/states_master');

	}

	public function city_master()

	{

		$search_data = $this->input->post();

		if (isset($search_data)) {

			$s_codes = explode('-', $search_data['province']);

			$state_id = $s_codes[0];

			$s_array = ['country' => $search_data['country'], 'state_id' => $state_id];

			// debug($s_array);exit;

			$total_records = count($this->custom_db->single_table_records('api_city_list', '*', $s_array)['data']);

			$city_master_data = $this->custom_db->single_table_records('api_city_list', '*', $s_array, $offset, RECORDS_RANGE_1);

		} else {

			$city_master_data = $this->custom_db->single_table_records('api_city_list', '*', '', 0, 100, array('origin' => 'DESC'));

		}

		$city_origin = $this->uri->segment(4);

		$states_master_data = $this->custom_db->single_table_records('api_state_list', '*');

		$country_master_data = $this->custom_db->single_table_records('api_country_list', '*');

		if (isset($city_origin) && !empty($city_origin)) {

			$single_city_master_data = $this->custom_db->single_table_records('api_city_list', '*', array('origin' => $city_origin));

		}

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/city_master/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_3;

		$this->pagination->initialize($config);

		$page_data['total_records'] = $config['total_rows'];

		$page_data['country_master_data'] = $country_master_data;

		$page_data['states_master_data'] = $states_master_data;

		$page_data['city_master_data'] = $city_master_data;

		$page_data['single_city_master_data'] = $single_city_master_data;

		$this->template->view('cms/city_master', $page_data);

	}

	public function ajax_states_by_country_id()

	{

		$data = $this->input->post();

		$country_id = $data['country_id'];

		$country_data = $this->custom_db->single_table_records('api_state_list', '*', array('country_id' => $country_id));

		foreach ($country_data['data'] as $key => $value) {

			$options .=  '<option value="' . $value['origin'] . '-' . $value['state_province'] . '">' . $value['state_province'] . '</option>';

		}

		echo $options;

	}

	public function add_city_master()

	{

		$post_data = $this->input->post();

		// debug($post_data);exit;

		$c_codes = explode('-', $post_data['country']);

		$s_codes = explode('-', $post_data['province']);

		if (isset($post_data['city_hidden_origin']) && !empty($post_data['city_hidden_origin'])) {

			$update_data = [

				'destination' => $post_data['city_name'],

				'longitude' => $post_data['longitude'],

				'latitude' => $post_data['latitude'],

				'state_id' => $s_codes[0],

				'state_province' => $s_codes[1],

				'country' => $c_codes[0],

				'c_code' => $c_codes[1]

			];

			$this->custom_db->update_record('api_city_list', $update_data, array('origin' => $post_data['city_hidden_origin']));

			$this->session->set_flashdata('success_message', 'City updated successfully!');

		} else {

			// debug($codes);exit;

			$insert_data = [

				'destination' => $post_data['city_name'],

				'longitude' => $post_data['longitude'],

				'latitude' => $post_data['latitude'],

				'state_id' => $s_codes[0],

				'state_province' => $s_codes[1],

				'country' => $c_codes[0],

				'c_code' => $c_codes[1]

			];

			$count_data = $this->custom_db->single_table_records('api_city_list', '*', $insert_data);

			$count = count($count_data['data']);

			if ($count == 0) {

				$this->custom_db->insert_record('api_city_list', $insert_data);

				$this->session->set_flashdata('success_message', 'City added successfully!');

			} else {

				$this->session->set_flashdata('success_message', 'The city already exists!');

			}

		}

		redirect('cms/city_master');

	}

	public function add_city_master_view()

	{

		$city_origin = $this->uri->segment(3);

		if (isset($city_origin) && !empty($city_origin)) {

			$single_city_master_data = $this->custom_db->single_table_records('api_city_list', '*', array('origin' => $city_origin));

		}

		$states_master_data = $this->custom_db->single_table_records('api_state_list', '*');

		$country_master_data = $this->custom_db->single_table_records('api_country_list', '*');

		$page_data['country_master_data'] = $country_master_data;

		$page_data['states_master_data'] = $states_master_data;

		$page_data['single_city_master_data'] = $single_city_master_data;

		$this->template->view('cms/add_city_master_view', $page_data);

	}

	public function top_destination_category_master($offset = 0)

	{

		// $category_master_data = $this->custom_db->get_custom_query('top_destination_categories','*','',$offset,RECORDS_RANGE_3,array('origin'=>'DESC'));	

		$category_master_data = $this->custom_db->get_custom_query('SELECT * FROM top_destination_categories where status!=2 ORDER BY category_name ASC  LIMIT ' . RECORDS_RANGE_3 . ' OFFSET ' . $offset);

		$total_records = count($this->custom_db->get_custom_query('SELECT * FROM top_destination_categories where status!=2 LIMIT ' . RECORDS_RANGE_3 . ' OFFSET ' . $offset));

		// $total_records = count($this->custom_db->single_table_records('top_destination_categories','*',['status','!=','2'])['data']);

		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/cms/top_destination_category_master/';

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_3;

		$this->pagination->initialize($config);

		$page_data['total_records'] = $config['total_rows'];

		$page_data['category_master_data'] = $category_master_data;

		$this->template->view('cms/top_destination_category_master', $page_data);

	}

	function delete_top_destination_category_master_view($origin)

	{

		$update_data = [

			'status' => 2

		];

		$this->custom_db->update_record('top_destination_categories', $update_data, array('origin' => $origin));

		$this->session->set_flashdata('success_message', 'Category deleted successfully!');

		redirect('cms/top_destination_category_master');

	}

	public function add_top_destination_category_master()

	{

		$post_data = $this->input->post();

		// debug($post_data);exit;

		if (isset($post_data['category_hidden_origin']) && !empty($post_data['category_hidden_origin'])) {

			$update_data = [

				'category_name' => $post_data['category_name'],

				'module' => $post_data['module']

			];

			$this->custom_db->update_record('top_destination_categories', $update_data, array('origin' => $post_data['category_hidden_origin']));

			$this->session->set_flashdata('success_message', 'Category updated successfully!');

		} else {

			// debug($codes);exit;

			$insert_data = [

				'category_name' => $post_data['category_name'],

				'module' => $post_data['module']

			];

			$count_data = $this->custom_db->single_table_records('top_destination_categories', '*', $insert_data);

			$count = count($count_data['data']);

			if ($count == 0) {

				$this->custom_db->insert_record('top_destination_categories', $insert_data);

				$this->session->set_flashdata('success_message', 'Category added successfully!');

			} else {

				$this->session->set_flashdata('success_message', 'The category already exists!');

			}

		}

		redirect('cms/top_destination_category_master');

	}

	public function add_top_destination_category_master_view()

	{

		$city_origin = $this->uri->segment(3);

		if (isset($city_origin) && !empty($city_origin)) {

			$single_category_master_data = $this->custom_db->single_table_records('top_destination_categories', '*', array('origin' => $city_origin));

		}

		$page_data['single_category_master_data'] = $single_category_master_data;

		$this->template->view('cms/add_top_destination_category_master', $page_data);

	}

	public function list_testmonials_franchisees($offset = 0)

	{

		$this->load->library('pagination');

		$config['base_url'] = base_url('cms/list_testmonials_franchisees/');

		$config['per_page'] = 10;

		$config['reuse_query_string'] = TRUE;

		$config['uri_segment'] = 3;

		$total_records = $this->custom_db->count_records('testmonials_franchisees');

		$config['total_rows'] = $total_records;

		$this->pagination->initialize($config);

		$limit = $config['per_page'];

		$testmonial_franchisees = $this->custom_db->single_table_records('testmonials_franchisees', '*', [],  $offset, $limit);

		$page_data['testmonial_franchisees_list'] = $testmonial_franchisees['data'];

		$page_data['pagination_links'] = $this->pagination->create_links();

		$page_data['offset'] = $offset;

		$this->template->view('cms/list_testmonials_franchisees', $page_data);

	}

	public function list_testmonials($offset = 0)

	{

		$this->load->library('pagination');

		$config['base_url'] = base_url('cms/list_testmonials/');

		$config['per_page'] = 10;

		$config['reuse_query_string'] = TRUE;

		$config['uri_segment'] = 3;

		$total_records = $this->custom_db->count_records('testmonials');

		$config['total_rows'] = $total_records;

		$this->pagination->initialize($config);

		$limit = $config['per_page'];

		$testmonials = $this->custom_db->single_table_records('testmonials', '*', [],  $offset, $limit);

		//debug($testmonials);exit;

		$page_data['testmonial_list'] = $testmonials['data'];

		$page_data['pagination_links'] = $this->pagination->create_links();

		$page_data['offset'] = $offset;

		$this->template->view('cms/list_testmonials', $page_data);

	}

	public function add_testmonials()

	{

		$testmonial_list = $this->custom_db->single_table_records('testmonials', '*');

		$page_data['testmonial_list'] = $testmonial_list['data'];

		$this->template->view('cms/add_testmonials', $page_data);

	}

	public function save_testmonial()

	{

		$this->load->library('form_validation');

		$this->load->library('upload');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_rules('title', 'Title', 'required|trim');

			$this->form_validation->set_rules('comment', 'Comment', 'required|trim');

			$this->form_validation->set_rules('designation', 'Designation', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$upload_path = $this->template->domain_image_upload_path();

				$allowed_types = 'jpg|jpeg|png|gif';

				$max_size = 2048;

				$image = '';

				if (!empty($_FILES['image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('image')) {

						$data = $this->upload->data();

						$image = $data['file_name'];

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect('cms/add_testmonials');

					}

				}

				$insert_data = [

					'title'           => $this->input->post('title', true),

					'comment'       => $this->input->post('comment', true),

					'designation'     => $this->input->post('designation', true),

					'image'           => $image,

					'status'          => $this->input->post('status'),

					'created_at'      => date('Y-m-d H:i:s')

				];

				$insert = $this->custom_db->insert_record('testmonials', $insert_data);

				if ($insert) {

					$this->session->set_flashdata('success', 'Testimonial saved successfully.');

					redirect('cms/list_testmonials');

				} else {

					$this->session->set_flashdata('error', 'Failed to save testimonial.');

					redirect('cms/add_testmonials');

				}

			} else {

				$this->session->set_flashdata('error', validation_errors());

				redirect('cms/add_testmonials');

			}

		} else {

			show_error('Invalid request method', 405);

		}

	}

	public function edit_testmonials($id)

	{

		if (!is_numeric($id)) show_404();

		$result = $this->custom_db->single_table_records('testmonials', '*', ['id' => $id]);

		if (empty($result['data'])) show_404();

		$page_data['testimonial'] = $result['data'][0];

		$this->template->view('cms/edit_testmonials', $page_data);

	}

	public function update_testmonial($id)

	{

		$this->load->library('form_validation');

		$this->load->library('upload');

		if (!is_numeric($id)) show_404();

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_rules('title', 'Title', 'required|trim');

			$this->form_validation->set_rules('comment', 'Comment', 'required|trim');

			$this->form_validation->set_rules('designation', 'Designation', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$upload_path = $this->template->domain_image_upload_path();

				$allowed_types = 'jpg|jpeg|png|gif';

				$max_size = 2048;

				$existing = $this->custom_db->single_table_records('testmonials', '*', ['id' => $id]);

				if (empty($existing['data'])) show_404();

				$testimonial = $existing['data'][0];

				$image = $testimonial['image'];

				if (!empty($_FILES['image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('image')) {

						if (!empty($image) && file_exists($upload_path . $image)) unlink($upload_path . $image);

						$image = $this->upload->data('file_name');

						//debug($image);exit;

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect("cms/edit_testmonials/{$id}");

					}

				}

				$data = [

					'title'           => $this->input->post('title', true),

					'comment'       => $this->input->post('comment', true),

					'designation'     => $this->input->post('designation', true),

					'status'          => $this->input->post('status'),

					'image'           => $image['file_name']

				];

				$update = $this->custom_db->update_record('testmonials', $data, ['id' => $id]);

				if ($update) {

					$this->session->set_flashdata('success', 'Testimonial updated successfully.');

				} else {

					$this->session->set_flashdata('error', 'Failed to update testimonial.');

				}

				redirect('cms/list_testmonials');

			} else {

				$this->session->set_flashdata('error', validation_errors());

				redirect("cms/edit_testmonials/{$id}");

			}

		} else {

			show_error('Invalid request method', 405);

		}

	}

	public function update_testmonial_status($id, $status)

	{

		$status = ($status == 1) ? 1 : 0;

		$update_data = ['status' => $status];

		$this->custom_db->update_record('testmonials', $update_data, ['id' => $id]);

		$this->session->set_flashdata('success', 'Testimonial status updated.');

		redirect('cms/list_testmonials');

	}

	public function delete_testmonial($id)

	{

		if (!is_numeric($id)) show_404();

		$delete = $this->custom_db->delete_record('testmonials', ['id' => $id]);

		$message = $delete ? 'Testimonial deleted successfully.' : 'Failed to delete testimonial.';

		$this->session->set_flashdata($delete ? 'success' : 'error', $message);

		redirect('cms/list_testmonials');

	}

	public function add_testmonials_franchisees()

	{

		$testmonial_franchisees_list = $this->custom_db->single_table_records('testmonials_franchisees', '*');

		$page_data['testmonial_franchisees_list'] = $testmonial_franchisees_list['data'];

		$this->template->view('cms/add_testmonials_franchisees', $page_data);

	}

	public function save_testmonial_franchisees()

	{

		$this->load->library('form_validation');

		$this->load->library('upload');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_rules('title', 'Title', 'required|trim');

			$this->form_validation->set_rules('sub_title', 'Sub Title', 'required|trim');

			$this->form_validation->set_rules('designation', 'Designation', 'required|trim');

			$this->form_validation->set_rules('thumbnail_url', 'Thumbnail URL', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$upload_path = $this->template->domain_image_upload_path();

				$allowed_types = 'jpg|jpeg|png|gif';

				$max_size = 2048;

				$image = '';

				$thumbnail_image = '';

				if (!empty($_FILES['image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('image')) {

						$data = $this->upload->data();

						$image = $data['file_name'];

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect('cms/add_testmonials_franchisees');

					}

				}

				if (!empty($_FILES['thumbnail_image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['thumbnail_image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('thumbnail_image')) {

						$data = $this->upload->data();

						$thumbnail_image = $data['file_name'];

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect('cms/add_testmonials_franchisees');

					}

				}

				$insert_data = [

					'title'           => $this->input->post('title', true),

					'sub_title'       => $this->input->post('sub_title', true),

					'designation'     => $this->input->post('designation', true),

					'image'           => $image,

					'thumbnail_image' => $thumbnail_image,

					'thumbnail_url'   => $this->input->post('thumbnail_url', true),

					'status'          => $this->input->post('status'),

					'created_at'      => date('Y-m-d H:i:s')

				];

				$insert = $this->custom_db->insert_record('testmonials_franchisees', $insert_data);

				if ($insert) {

					$this->session->set_flashdata('success', 'Testimonial Franchisees saved successfully.');

					redirect('cms/list_testmonials_franchisees');

				} else {

					$this->session->set_flashdata('error', 'Failed to save testimonial franchisees.');

					redirect('cms/add_testmonials_franchisees');

				}

			} else {

				$this->session->set_flashdata('error', validation_errors());

				redirect('cms/add_testmonials_franchisees');

			}

		} else {

			show_error('Invalid request method', 405);

		}

	}

	public function edit_testmonials_franchisees($id)

	{

		if (!is_numeric($id)) show_404();

		$result = $this->custom_db->single_table_records('testmonials_franchisees', '*', ['id' => $id]);

		if (empty($result['data'])) show_404();

		$page_data['testimonial_franchisees'] = $result['data'][0];

		$this->template->view('cms/edit_testmonials_franchisees', $page_data);

	}

	public function update_testmonial_franchisees($id)

	{

		$this->load->library('form_validation');

		$this->load->library('upload');

		if (!is_numeric($id)) show_404();

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_rules('title', 'Title', 'required|trim');

			$this->form_validation->set_rules('sub_title', 'Sub Title', 'required|trim');

			$this->form_validation->set_rules('designation', 'Designation', 'required|trim');

			$this->form_validation->set_rules('thumbnail_url', 'Thumbnail URL', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$upload_path = $this->template->domain_image_upload_path();

				$allowed_types = 'jpg|jpeg|png|gif';

				$max_size = 2048;

				$existing = $this->custom_db->single_table_records('testmonials_franchisees', '*', ['id' => $id]);

				if (empty($existing['data'])) show_404();

				$testmonial_franchisees = $existing['data'][0];

				$image = $testmonial_franchisees['image'];

				$thumbnail_image = $testmonial_franchisees['thumbnail_image'];

				if (!empty($_FILES['image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('image')) {

						if (!empty($image) && file_exists($upload_path . $image)) unlink($upload_path . $image);

						$image = $this->upload->data('file_name');

						//debug($image);exit;

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect("cms/edit_testmonials_franchisees/{$id}");

					}

				}

				if (!empty($_FILES['thumbnail_image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['thumbnail_image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('thumbnail_image')) {

						if (!empty($thumbnail_image) && file_exists($upload_path . $thumbnail_image)) unlink($upload_path . $thumbnail_image);

						$thumbnail_image = $this->upload->data('file_name');

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect("cms/edit_testmonials_franchisees/{$id}");

					}

				}

				$data = [

					'title'           => $this->input->post('title', true),

					'sub_title'       => $this->input->post('sub_title', true),

					'designation'     => $this->input->post('designation', true),

					'thumbnail_url'   => $this->input->post('thumbnail_url', true),

					'status'          => $this->input->post('status'),

					'image'           => $image['file_name'],

					'thumbnail_image' => $thumbnail_image['file_name']

				];

				$update = $this->custom_db->update_record('testmonials_franchisees', $data, ['id' => $id]);

				if ($update) {

					$this->session->set_flashdata('success', 'Testimonial Franchisees updated successfully.');

				} else {

					$this->session->set_flashdata('error', 'Failed to update testimonial franchisees.');

				}

				redirect('cms/list_testmonials_franchisees');

			} else {

				$this->session->set_flashdata('error', validation_errors());

				redirect("cms/edit_testmonials_franchisees/{$id}");

			}

		} else {

			show_error('Invalid request method', 405);

		}

	}

	public function update_testmonial_franchisees_status($id, $status)

	{

		$status = ($status == 1) ? 1 : 0;

		$update_data = ['status' => $status];

		$this->custom_db->update_record('testmonials_franchisees', $update_data, ['id' => $id]);

		$this->session->set_flashdata('success', 'Testimonial Franchisees status updated.');

		redirect('cms/list_testmonials_franchisees');

	}

	public function delete_testmonial_Franchisees($id)

	{

		if (!is_numeric($id)) show_404();

		$delete = $this->custom_db->delete_record('testmonials_franchisees', ['id' => $id]);

		$message = $delete ? 'Testimonial Franchisees deleted successfully.' : 'Failed to delete testimonial franchisees.';

		$this->session->set_flashdata($delete ? 'success' : 'error', $message);

		redirect('cms/list_testmonials_franchisees');

	}

	public function list_collaborations($offset = 0)

	{

		$this->load->library('pagination');

		$config['base_url'] = base_url('cms/list_collaborations/');

		$config['per_page'] = 10;

		$config['reuse_query_string'] = TRUE;

		$config['uri_segment'] = 3;

		$total_records = $this->custom_db->count_records('collaborations');

		$config['total_rows'] = $total_records;

		$this->pagination->initialize($config);

		$limit = $config['per_page'];

		$collaboration = $this->custom_db->single_table_records('collaborations', '*', [],  $offset, $limit);

		$page_data['collaboration_list'] = $collaboration['data'];

		$page_data['pagination_links'] = $this->pagination->create_links();

		$page_data['offset'] = $offset;

		$this->template->view('cms/list_collaborations', $page_data);

	}

	public function add_collaborations()

	{

		$collaboration_list = $this->custom_db->single_table_records('collaborations', '*');

		$page_data['collaboration_list'] = $collaboration_list['data'];

		$this->template->view('cms/add_collaborations', $page_data);

	}

	public function save_collaboration()

	{

		$this->load->library('form_validation');

		$this->load->library('upload');

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_rules('title', 'Title', 'required|trim');

			$this->form_validation->set_rules('sub_title', 'Sub Title', 'required|trim');

			$this->form_validation->set_rules('designation', 'Designation', 'required|trim');

			$this->form_validation->set_rules('thumbnail_url', 'Thumbnail URL', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$upload_path = $this->template->domain_image_upload_path();

				$allowed_types = 'jpg|jpeg|png|gif';

				$max_size = 2048;

				$image = '';

				$thumbnail_image = '';

				if (!empty($_FILES['image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('image')) {

						$data = $this->upload->data();

						$image = $data['file_name'];

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect('cms/add_collaborations');

					}

				}

				if (!empty($_FILES['thumbnail_image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['thumbnail_image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('thumbnail_image')) {

						$data = $this->upload->data();

						$thumbnail_image = $data['file_name'];

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect('cms/add_collaborations');

					}

				}

				$insert_data = [

					'title'           => $this->input->post('title', true),

					'sub_title'       => $this->input->post('sub_title', true),

					'created_date' => $this->input->post('created_date', true),

					'designation'     => $this->input->post('designation', true),

					'image'           => $image,

					'thumbnail_image' => $thumbnail_image,

					'thumbnail_url'   => $this->input->post('thumbnail_url', true),

					'status'          => $this->input->post('status'),

					'created_at'      => date('Y-m-d H:i:s')

				];

				$insert = $this->custom_db->insert_record('collaborations', $insert_data);

				if ($insert) {

					$this->session->set_flashdata('success', 'Collaboration saved successfully.');

					redirect('cms/list_collaborations');

				} else {

					$this->session->set_flashdata('error', 'Failed to save collaboration.');

					redirect('cms/add_collaborations');

				}

			} else {

				$this->session->set_flashdata('error', validation_errors());

				redirect('cms/add_collaborations');

			}

		} else {

			show_error('Invalid request method', 405);

		}

	}

	public function edit_collaborations($id)

	{

		if (!is_numeric($id)) show_404();

		$result = $this->custom_db->single_table_records('collaborations', '*', ['id' => $id]);

		if (empty($result['data'])) show_404();

		$page_data['collaboration'] = $result['data'][0];

		$this->template->view('cms/edit_collaborations', $page_data);

	}

	public function update_collaboration($id)

	{

		$this->load->library('form_validation');

		$this->load->library('upload');

		if (!is_numeric($id)) show_404();

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			$this->form_validation->set_rules('title', 'Title', 'required|trim');

			$this->form_validation->set_rules('sub_title', 'Sub Title', 'required|trim');

			$this->form_validation->set_rules('designation', 'Designation', 'required|trim');

			$this->form_validation->set_rules('thumbnail_url', 'Thumbnail URL', 'required|trim');

			$this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');

			if ($this->form_validation->run()) {

				$upload_path = $this->template->domain_image_upload_path();

				$allowed_types = 'jpg|jpeg|png|gif';

				$max_size = 2048;

				$existing = $this->custom_db->single_table_records('collaborations', '*', ['id' => $id]);

				if (empty($existing['data'])) show_404();

				$testimonial = $existing['data'][0];

				$image = $testimonial['image'];

				$thumbnail_image = $testimonial['thumbnail_image'];

				if (!empty($_FILES['image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('image')) {

						if (!empty($image) && file_exists($upload_path . $image)) unlink($upload_path . $image);

						$image = $this->upload->data('file_name');

						//debug($image);exit;

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect("cms/edit_collaborations/{$id}");

					}

				}

				if (!empty($_FILES['thumbnail_image']['name'])) {

					$config = [

						'upload_path'   => $upload_path,

						'allowed_types' => $allowed_types,

						'file_name'     => rand() . '_' . $_FILES['thumbnail_image']['name'],

						'max_size'      => $max_size,

						'remove_spaces' => true

					];

					$this->upload->initialize($config);

					if ($this->upload->do_upload('thumbnail_image')) {

						if (!empty($thumbnail_image) && file_exists($upload_path . $thumbnail_image)) unlink($upload_path . $thumbnail_image);

						$thumbnail_image = $this->upload->data('file_name');

					} else {

						$this->session->set_flashdata('error', $this->upload->display_errors());

						redirect("cms/edit_collaborations/{$id}");

					}

				}

				$data = [

					'title'           => $this->input->post('title', true),

					'sub_title'       => $this->input->post('sub_title', true),

					'created_date' => $this->input->post('created_date', true),

					'designation'     => $this->input->post('designation', true),

					'thumbnail_url'   => $this->input->post('thumbnail_url', true),

					'status'          => $this->input->post('status'),

					'image'           => $image['file_name'],

					'thumbnail_image' => $thumbnail_image['file_name']

				];

				//debug($data);exit;

				$update = $this->custom_db->update_record('collaborations', $data, ['id' => $id]);

				if ($update) {

					$this->session->set_flashdata('success', 'Collaboration updated successfully.');

				} else {

					$this->session->set_flashdata('error', 'Failed to update collaboration.');

				}

				redirect('cms/list_collaborations');

			} else {

				$this->session->set_flashdata('error', validation_errors());

				redirect("cms/edit_collaborations/{$id}");

			}

		} else {

			show_error('Invalid request method', 405);

		}

	}

	public function update_collaboration_status($id, $status)

	{

		$status = ($status == 1) ? 1 : 0;

		$update_data = ['status' => $status];

		$this->custom_db->update_record('collaborations', $update_data, ['id' => $id]);

		$this->session->set_flashdata('success', 'Collaboration status updated.');

		redirect('cms/list_collaborations');

	}

	public function delete_collaboration($id)

	{

		if (!is_numeric($id)) show_404();

		$delete = $this->custom_db->delete_record('collaborations', ['id' => $id]);

		$message = $delete ? 'Collaboration deleted successfully.' : 'Failed to delete collaboration.';

		$this->session->set_flashdata($delete ? 'success' : 'error', $message);

		redirect('cms/list_collaborations');

	}

	public function faqs($offset = 0)

	{

		$this->load->library('pagination');

		$config['base_url'] = base_url('cms/faqs/');

		$config['per_page'] = 10;

		$config['reuse_query_string'] = TRUE;

		$config['uri_segment'] = 3;

		$total_records = $this->custom_db->count_records('cms_faqs');

		$config['total_rows'] = $total_records;

		$this->pagination->initialize($config);

		$limit = $config['per_page'];

		$collaboration = $this->custom_db->single_table_records('cms_faqs', '*', [],  $offset, $limit);

		$page_data['faqs_list'] = $collaboration['data'];

		$page_data['pagination_links'] = $this->pagination->create_links();

		$page_data['offset'] = $offset;

		$this->template->view('cms/faqs', $page_data);

	}

}


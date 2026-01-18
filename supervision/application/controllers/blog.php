<?php
// application/controllers/Welcome.php

class blog extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->model('blog_model');
    }

    public function index() {
        $data['blogs'] = $this->blog_model->get_all_blogs();
        $this->template->view ('cms/blog', $data);
    }

    public function add_blog() {
        $this->form_validation->set_rules('blog_name', 'Blog Name', 'required|max_length[100]');
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[100]');
        $this->form_validation->set_rules('sub_title', 'Subtitle', 'required|max_length[100]');
        $this->form_validation->set_rules('module', 'Module', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() === FALSE) {
            // Validation failed, show form with errors
            $this->template->view ('cms/blog');
        } else {
            // Validation passed, process form data

            // Insert blog data
            $blog_data = array(
                'blog_name' => $this->input->post('blog_name'),
                'title' => $this->input->post('title'),
                'sub_title' => $this->input->post('sub_title'),
                'module' => $this->input->post('module'),
                'description' => $this->input->post('description'),
                'blog_url' => $this->input->post('blog_url'),
                'seo_title' => $this->input->post('seo_title'),
                'seo_keywords' => $this->input->post('seo_keywords'),
                'seo_description' => $this->input->post('seo_description'),
                'status' => 1 // Default status
            );
            $blog_data['blog_url'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($blog_data['blog_url'])));

            // Insert blog and get last inserted ID
            $blog_id = $this->blog_model->insert_blog($blog_data);

            // Handle image uploads
            $config['upload_path'] = $this->template->domain_image_upload_path ();
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2048; // 2MB max size, adjust as needed

            $this->load->library('upload', $config);

            $files = $_FILES['image'];

            foreach ($files['name'] as $key => $image_name) {
                $_FILES['userfile']['name'] = $files['name'][$key];
                $_FILES['userfile']['type'] = $files['type'][$key];
                $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$key];
                $_FILES['userfile']['error'] = $files['error'][$key];
                $_FILES['userfile']['size'] = $files['size'][$key];

                if ($this->upload->do_upload('userfile')) {
                    $upload_data = $this->upload->data();

                    // Insert image data with blog ID
                    $image_data = array(
                        'blog_id' => $blog_id, // Use the retrieved blog ID
                        'image' => $upload_data['file_name'],
                        'image_name' => $this->input->post('image_name')[$key],
                        'image_description' => $this->input->post('image_description')[$key],
                        'status' => 1 // Default status
                    );
                    $this->blog_model->insert_blog_image($image_data);
                }
            }

            // Redirect to success page or show success message
            redirect( base_url () . 'blog/index');
        }
    }

    public function edit_blog($blog_id) {
        // Fetch the blog details from the model based on $blog_id
        $data['blog'] = $this->blog_model->get_blog_by_id($blog_id);

        // Load the edit form view with the blog data
        $this->template->view ('cms/edit_blog_form', $data);
    }

    public function update_blog($blog_id) {
        
    $blog_data = array(
        'blog_name' => $this->input->post('blog_name'),
        'title' => $this->input->post('title'),
        'sub_title' => $this->input->post('sub_title'),
        'module' => $this->input->post('module'),
        'description' => $this->input->post('description'),
        'blog_url' => $this->input->post('blog_url'),
        'seo_title' => $this->input->post('seo_title'),
        'seo_keywords' => $this->input->post('seo_keywords'),
        'seo_description' => $this->input->post('seo_description')
    );
    $blog_data['blog_url'] = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($blog_data['blog_url'])));

    // Prepare data for updating 'blog_images' table
    $images = $_FILES['image'];
    $image_names = $this->input->post('image_name');
    $image_descriptions = $this->input->post('image_description');

    $uploaded_images = array();

    // debug($images);
    
    foreach ($images['name'] as $key => $image_name) {
        // echo $images['error'][$key];
        if ($images['error'][$key] == 0) {
            $_FILES['image']['name'] = $images['name'][$key];
                $_FILES['image']['type'] = $images['type'][$key];
                $_FILES['image']['tmp_name'] = $images['tmp_name'][$key];
                $_FILES['image']['error'] = $images['error'][$key];
                $_FILES['image']['size'] = $images['size'][$key];

            $config['upload_path'] = $this->template->domain_image_upload_path ();
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 10000000; // 2MB max size
            $config['file_name'] = uniqid() . '_' . $images['name'][$key];

            $this->load->library('upload', $config);

            
            if ($this->upload->do_upload('image')) {
                $uploaded_images[] = $this->upload->data('file_name');
            } else {
                
                $error = array('error' => $this->upload->display_errors());
                
            }
        } else {

            $old_image = $this->input->post('old_image');

            $old_image_name = $old_image[$key];

            // echo $key;

            
            $uploaded_images[] = $old_image_name;
        }
            // debug($uploaded_images);exit;
    }
            

    // Update blog data and images in the database
    $blog_data['images'] = $uploaded_images;
    $blog_data['image_names'] = $image_names;
    $blog_data['image_descriptions'] = $image_descriptions;

    $this->blog_model->update_blog($blog_id, $blog_data);

    // Redirect to index or view page after update
    redirect( base_url () . 'blog/index');
}

public function delete_blog($blog_id) {
        // Check if the blog_id is provided and valid
        if (!$blog_id) {
            // Handle invalid blog_id scenario (redirect or show error)
            redirect( base_url () . 'blog/index'); // Redirect to home or appropriate page
        }

        // Call the delete function in your Blog_model
        $this->blog_model->delete_blog($blog_id);

        // Optionally, you can redirect to another page after deletion
        redirect( base_url () . 'blog/index');
    }

    public function dynamic_hotel_urls($offset=0) {
        
        $blog_data = $this->custom_db->single_table_records('dynamic_hotel_urls','*','',$offset,RECORDS_RANGE_3,array('id'=>'DESC'));	
		$total_records = count($this->custom_db->single_table_records('dynamic_hotel_urls','*')['data']);
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/blog/dynamic_hotel_urls/';
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_3;
		$this->pagination->initialize($config);
		$page_data['total_records'] = $config['total_rows'];
		$page_data['blogs']=$blog_data['data'];
        $page_data['offset']=$offset;
		$this->template->view('cms/add_dynamic_hotel_urls',$page_data);
    }

    public function get_cities() {
        // Get the search query from the request
        $query = $this->input->get('query');
        
        // Sanitize the input to prevent SQL injection
        $query = $this->db->escape_like_str($query);

        // Fetch matching cities from the database
        $this->db->like('city_name', $query); // Search in city_name
        $this->db->select('origin, city_name, country_name');
        $city_list = $this->db->get('all_api_city_master')->result_array();

        // Return the JSON response
        echo json_encode($city_list);
    }

    public function add_dynamic_hotel_url() {
        // Get the POST data
        $data = array(
            'city_name' => $this->input->post('city'), // City name from input
            'country' => $this->input->post('country'), // Country name from hidden input
            'hotel_destination' => !empty($this->input->post('origin')) ?? 0, // Hotel destination
            'status' => 1, // or 0, based on your requirements
            'title' => $this->input->post('seo_title'),
            'keyword' => $this->input->post('seo_keywords'),
            'description' => $this->input->post('seo_description'),
            'module' => $this->input->post('city')
        );

        // Insert the data into the database
        if ($this->blog_model->insert_dynamic_hotel_url($data)) {
            // Redirect or load success message
            $this->session->set_flashdata('success', 'Data inserted successfully!');
            redirect( base_url () . 'blog/dynamic_hotel_urls');
        } else {
            // Handle error
            $this->session->set_flashdata('error', 'Data insertion failed. Please try again.');
            redirect( base_url () . 'blog/dynamic_hotel_urls');
        }
    }



}

?>

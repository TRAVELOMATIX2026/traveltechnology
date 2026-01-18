<?php
// application/models/Blog_model.php

class Blog_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function insert_blog($data) {
        $this->db->insert('blog', $data);
        return $this->db->insert_id(); // Return last inserted ID
    }

    public function insert_blog_image($data) {
        return $this->db->insert('blog_images', $data);
    }

    public function get_all_blogs() {
        $this->db->select('blog.*, GROUP_CONCAT(blog_images.image) AS images, GROUP_CONCAT(blog_images.image_name) AS image_names, GROUP_CONCAT(blog_images.image_description) AS image_descriptions');
        $this->db->from('blog');
        $this->db->join('blog_images', 'blog.origin = blog_images.blog_id', 'left');
        $this->db->group_by('blog.origin');
        $query = $this->db->get();

        return $query->result_array(); // Return an array of all blog records with associated images
    }

    public function get_blog_by_id($blog_id) {
        // Fetch a single blog by its ID with associated images
        $this->db->select('b.*, GROUP_CONCAT(bi.image) AS images, GROUP_CONCAT(bi.image_name) AS image_names, GROUP_CONCAT(bi.image_description) AS image_descriptions');
        $this->db->from('blog b');
        $this->db->join('blog_images bi', 'bi.blog_id = b.origin', 'left');
        $this->db->where('b.origin', $blog_id);
        $this->db->group_by('b.origin');
        $query = $this->db->get();
        return $query->row_array();
    }

     public function update_blog($blog_id, $data) {
        $blog_data = array(
        'blog_name' => $data['blog_name'],
        'title' => $data['title'],
        'sub_title' => $data['sub_title'],
        'module' => $data['module'],
        'description' => $data['description'],
        'blog_url' => $data['blog_url'],
        'seo_title' => $data['seo_title'],
        'seo_keywords' => $data['seo_keywords'],
        'seo_description' => $data['seo_description']
    );

        $this->db->where('origin', $blog_id);
    $this->db->update('blog', $blog_data);
    // echo '<pre>';print_r($data);exit;
        if (!empty($data['images'][0])) {
            // Delete existing blog images
            $this->db->where('blog_id', $blog_id);
            $this->db->delete('blog_images');

            // Insert new blog images
            $images = $data['images'];
            $image_names = $data['image_names'];
            $image_descriptions = $data['image_descriptions'];

            foreach ($images as $key => $image) {
                // debug($image);
                if (is_array($image)) {
                    $image_value = $image['file_name'];
                }else{
                    $image_value = $image;
                }
                $image_data = array(
                    'blog_id' => $blog_id,
                    'image' => $image_value,
                    'image_name' => $image_names[$key],
                    'image_description' => $image_descriptions[$key],
                    'status' => 1  // Assuming you have a status field for images
                );
                $this->db->insert('blog_images', $image_data);
            }
        }
    }

    public function delete_blog($blog_id) {
        // Delete blog entry from 'blogs' table
        $this->db->where('origin', $blog_id);
        $this->db->delete('blog');

        // Optionally, delete associated images, comments, etc.
        // Example: $this->db->where('blog_id', $blog_id);
        //          $this->db->delete('blog_images');

        // Check if any rows were affected
        if ($this->db->affected_rows() > 0) {
            $this->db->where('blog_id', $blog_id);
            $this->db->delete('blog_images');
            return true; // Deletion successful
        } else {
            return false; // Deletion failed or no rows were affected
        }
    }

    public function insert_dynamic_hotel_url($data) {
        $insert_data = array(
            'city' => $data['city_name'],
            'country' => $data['country'],
            'hotel_destination' => $data['hotel_destination'],
            'status' => $data['status'],
            'created_at' => date('Y-m-d H:i:s') // Current timestamp
        );

        $insertDynamicHotelUrlsSeoData = array(
            'title' => $data['title'],
            'keyword' => htmlspecialchars($data['keyword'], ENT_QUOTES),
            'description' => $data['description'],
            'module' => $data['module']
        );

        $this->db->insert('seo', $insertDynamicHotelUrlsSeoData);

        // Insert the data into the dynamic_hotel_urls table
        return $this->db->insert('dynamic_hotel_urls', $insert_data);
    }

    public function get_all_dynamic_hotel_urls() {
        $this->db->select('*');
        $this->db->from('dynamic_hotel_urls');
        $query = $this->db->get();

        return $query->result_array();
    }

    // Add other methods as needed
}
?>

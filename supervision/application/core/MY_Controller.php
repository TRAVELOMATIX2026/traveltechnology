<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    // Declare all CI autoloaded libraries/helpers/models here
   
    public $facebook;
    public $converter;
    public $flight_lib;
    public $cache;
    public $session;
    public $template;
    public $form_validation;
    public $config;
    public $db;
    public $input;
    public $output;
    public $load;
    public $router;
    public $uri;
    public $benchmark;
    public $lang;
    public $email;
    public $security;
    public $hooks;
    public $encrypt;
    public $master_currency;
    public $currency;
    public $application_logger;
    public $custom_db;
    public $db_cache_api;
    public $provab_page_loader;
    public $CI;
    public $canonicalTagContent;
    public $slideImageJson;
    public $hook_top_deals;
    public $language_preference;
    public $entityDomain;
    public $application_default_template;
    public $entity_domain_name;
    public $entity_domain_website;
    public $entity_domain_phone_code;
    public $entity_domain_mail;
    public $application_domain_logo;
    public $application_address;
    public $topDestFlights;
    public $entity_domain_phone;
    public $topDestFlightsBlog;
    public $blog;
    public $active_domain_modules;
    public $current_page;
    public $live_username;
    public $live_password;
    public $flight_engine_system;
    public $hotel_engine_system;
    public $bus_engine_system;
    public $transfer_engine_system;
    public $external_service_system;
    public $sightseeing_engine_system;
    public $car_engine_system;
    public $flight_url;
    public $hotel_url;
    public $bus_url;
    public $transferv1_url;
    public $sightseeing_url;
    public $car_url;
    public $external_service;
    public $domain_key;
    public $api_interface;
    public $google;
    public $social_configuration;
    public $log;
    public $provab_solid;
    public $provab_sms;
    public $entity_user_id;
    public $entity_domain_id;
    public $entity_uuid;
    public $entity_user_type;
    public $entity_email;
    public $enumeration;
    public $entity_name;
    public $entity_address;
    public $entity_country_code;
    public $entity_status;
    public $entity_phone;
    public $entity_date_of_birth;
    public $entity_image;
    public $entity_gst_number;
    public $pagination;
    public $entity_creation;
    public $upload;
    public $provab_mailer;

    public $module_model;
    public $domain_management_model;
    public $private_management_model;
    public $user_model;
    public $Package_Model;
    public $flight_model;
    public $sightseeing_model;
    public $hotel_model;
    public $package_model;
    public $test_username   ;
    public $test_password;  
    public $car_model;  
    public $transaction_model;  
    public $transferv1_model;  
    public $agency_name;  
    public $booking_data_formatter;  
    public $active_user_previleges;  
    public $user_page;  
    public $loader;  
    public $model;  
    public $blog_model;  

    public function __construct()
    {
        parent::__construct();
        // No need to manually assign; CI already loads them
    }
}

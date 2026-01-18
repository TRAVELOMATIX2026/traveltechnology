<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* @author: Shaik Jani
* @Email: shaik.jani@provabmail.com
* @Date: 2025-03-25
* @package: 
* @Description: 
* @version: 2.0
**/

$config['master_module_list']	= array(
META_AIRLINE_COURSE => 'flight',
META_FLIGHT_HOTEL_COURSE => 'flight+hotel',
META_ACCOMODATION_COURSE => 'hotel',
META_BUS_COURSE => 'buse',
META_TRANSFER_COURSE=>'transfer',
META_SIGHTSEEING_COURSE=>'activitie',
META_CAR_COURSE=>'car',
META_PACKAGE_COURSE => 'holiday',
);
/******** Current Module ********/
$config['current_module'] = 'b2c';
$config['load_minified'] = false;
$config['verify_domain_balance'] = false;
/******** PAYMENT GATEWAY START ********/
//To enable/disable PG
$config['enable_payment_gateway'] = true;
$config['active_payment_gateway'] = 'STRIPE'; //STRIPE
$config['active_payment_system'] = 'test';
$config['payment_gateway_currency'] = 'USD'; //INR


$config['stripe_secret_key'] = 'sk_test_51SMKwIJSF00UrivHIuEbdpa2YerwLf9ElbQh4Dd6LAfIt8TJT6rjU1IM0K58pcSoaESnT8aWXIjHr4SAXdICZhto00Ss6YYdtJ';
$config['stripe_publishable_key'] = 'pk_test_51SMKwIJSF00UrivH4QJk1kJxBeiOLDYVaoxS2FcxGHWTErxslGfVAaS0UoWAqAk0IFEPO0jdcjrjYwpjksJ4Yfad00t0mA2itK';

/******** BOOKING ENGINE START ********/
$config ['flight_engine_system'] 	= 'test'; // test / live
$config ['hotel_engine_system'] 	= 'test'; // test / list
/******** PAYMENT GATEWAY END ********/
/**
 * 
 * Enable/Disable caching for search result
 */
$config['cache_hotel_search'] = true; //right now not needed
$config['cache_flight_search'] = true;
$config['cache_bus_search'] = true;
$config['cache_car_search'] = false;
$config['cache_transfer_search'] = true;
$config['cache_sightseeing_search'] = true;
$config['cache_transferv1_search'] = false;
/**
 * Number of seconds results should be cached in the system
 */
$config['cache_hotel_search_ttl'] = 900;
$config['cache_flight_search_ttl'] = 300;
$config['cache_bus_search_ttl'] = 300;
$config['cache_car_search_ttl'] = 300;
$config['cache_sightseeing_search_ttl'] = 300;
$config['cache_transferv1_search_ttl'] = 300;
$config['cache_transfer_search_ttl'] = 300;
/*$config['lazy_load_hotel_search'] = true;*/
$config['hotel_per_page_limit'] = 20;
$config['car_per_page_limit'] = 200;
$config['sightseeing_page_limit'] = 50;
$config['transferv1_page_limit'] = 50;
/*
	search session expiry period in seconds
*/
$config['flight_search_session_expiry_period'] = 600;//600
$config['flight_search_session_expiry_alert_period'] = 300;//300

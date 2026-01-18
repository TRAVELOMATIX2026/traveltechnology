<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['master_module_list']	= array(	
	META_ACCOMODATION_COURSE => 'hotel',
	META_AIRLINE_COURSE => 'flight',	
	META_BUS_COURSE => 'bus',
	META_PACKAGE_COURSE => 'package',
	META_SIGHTSEEING_COURSE=>'activities',
	META_CAR_COURSE => 'car',
	META_TRANSFER_COURSE=>'transfers'

);
/******** Current Module ********/
$config['current_module'] = 'admin';

/******** BOOKING ENGINE START ********/
$config ['flight_engine_system'] 	= 'live'; // test/live
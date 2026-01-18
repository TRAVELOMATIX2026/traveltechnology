<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 | -------------------------------------------------------------------------
 | URI ROUTING
 | -------------------------------------------------------------------------
 | This file lets you re-map URI requests to specific controller functions.
 |
 | Typically there is a one-to-one relationship between a URL string
 | and its corresponding controller class/method. The segments in a
 | URL normally follow this pattern:
 |
 |	example.com/class/method/id/
 |
 | In some instances, however, you may want to remap this relationship
 | so that a different class/function is called than the one
 | corresponding to the URL.
 |
 | Please see the user guide for complete details:
 |
 |	http://codeigniter.com/user_guide/general/routing.html
 |
 | -------------------------------------------------------------------------
 | RESERVED ROUTES
 | -------------------------------------------------------------------------
 |
 | There area two reserved routes:	
 |
 |	$route['default_controller'] = 'welcome';
 |
 | This route indicates which controller class should be loaded if the
 | URI contains no data. In the above example, the "welcome" class
 | would be loaded.
 |
 |	$route['404_override'] = 'errors/page_missing';
 |
 | This route will tell the Router what URI segments to use if those provided
 | in the URL cannot be matched to a valid route.
 |
 */
//Adding Custom Routes for CMS Url Rewriting
require_once APPPATH .'libraries/custom_router.php';
$route = Custom_Router::cms_routes();
$route = Custom_Router::cms_routes_new();
$meta_tags = Custom_Router::set_meta_tags();
$domain_key = Custom_Router::domain_details();

$route['default_controller'] = "general";
$route['404_override'] = 'general/ooops';

/* End of file routes.php */
$route['flight'] = '/general/index/VHCID1420613784';
$route['hotel'] = '/general/index/VHCID1420613748';
$route['transfer'] = '/general/index/VHCID1433496655';
$route['car'] = '/general/index/TMCAR1433491849';
$route['activitie'] = '/general/index/TMCID1524458882';
$route['holiday'] = '/general/index/VHCID1433498322';
$route['flight+hotel'] = '/general/index/VHCID1420613785';
$route['customer-support'] = '/general/customer_support';
$route['new-blog'] = '/general/new_blog';
$route['new-blog/flights'] = 'general/new_blog/flights';
$route['new-blog/hotels'] = 'general/new_blog/hotels';
$route['new-blog/holidays'] = 'general/new_blog/holidays';
$route['blog/(:any)'] = 'general/blog/$1';
$route['hotels/(:any)'] = 'hotel/dynamic_search/$1'; // Pass the city name to the dynamic_search method
$route['flights/(:any)'] = 'flight/dynamic_search/$1'; // Pass the city name to the dynamic_search method


<style>
     .searchsbmt_flight{
		width: 20%;
		position: relative;
		top: 33px;
	 }
 .trvlhdr-optn{
	position: relative;
    top: 0px;
	margin-top: 0;
 }
 .tabrow{
	padding: 24px 30px !important;
 }
</style>
<?php
foreach ($active_booking_source as $t_k => $t_v) {
	$active_source[] = $t_v['source_id'];
}
$api_count = count($active_source);
$active_source = json_encode($active_source);
if($flight_search_params['trip_type'] == 'multicity') {
	$flight_search_params['depature'] = $flight_search_params['depature'][0];
}
$is_domestic_roundway = $flight_search_params['is_domestic'] && $flight_search_params['trip_type'] == 'circle' ? 1:0;
?>
<script>
    const currencyList = <?php echo json_encode($currencyList); ?>;

var bnr_image = '<div class="lstng_banner_hrzntl hide"> <img src="<?=$GLOBALS['CI']->template->template_images('lisitng_banner_h.png')?>" > <p>Get up to Rs. 2,000* OFF on Domestic Flight bookings</p> </div>';
var bnr_image_path = '<?= $GLOBALS['CI']->template->domain_images(); ?>';
var middle_ads = <?php echo json_encode($middle_ads); ?>;
var api_result_counter = 0;
var search_session_alert_expiry = "<?php echo $GLOBALS ['CI']->config->item ( 'flight_search_session_expiry_alert_period' ); ?>";
var search_session_expiry = "<?php echo $GLOBALS ['CI']->config->item ( 'flight_search_session_expiry_period' ); ?>";
var search_hash = '';
var session_time_out_function_call = 0;

var progress_width = 0;
var api_count = "<?php echo $api_count;?>";
var lcc_gds = "<?php echo @$_GET['lcc_gds'];?>";
var conn_direct = "<?php echo @$_GET['conn_direct'];?>";
var pref_carrier = '<?php echo @$_GET['carrier'][0];?>';
var is_domestic_roundway = '<?php echo $is_domestic_roundway;?>';
if(lcc_gds == ''){
	lcc_gds = '0';
}
if(conn_direct ==''){
	conn_direct = '0';
}


var load_flights = function(bs){
	var ij=1;
	$.ajax({
		type: 'GET',
		url: app_base_url+'index.php/ajax/flight_list?booking_source='+bs+'&search_id=<?=$flight_search_params['search_id']?>&lcc_gds='+lcc_gds+'&conn_direct='+conn_direct+'&carrier='+pref_carrier+'&op=load',
		async: true,
		cache: true,
		dataType: 'json',
		success: function(res) {
			var dui;
			var r = res;
			//console.log(r);
			dui = setInterval(function(){
            if(is_domestic_roundway == '11'){
                if (typeof(process_result_update) != "undefined" && $.isFunction(process_result_update) == true) {	
                    clearInterval(dui);
                    $(".progress-bar").css("width",  "100%");
                    process_result_update(r,'<?php echo $api_count; ?>',ij);
                    // Hide skeleton loader after processing
                    setTimeout(function() {
                        $('#onwFltContainer').hide();
                    }, 500);
                }
            }else{
			if (typeof(generate_flight_list) != "undefined" && $.isFunction(generate_flight_list) == true) {
				clearInterval(dui);


                $(".progress-bar").css("width",  "100%"); //.text("100 %")
                generate_flight_list(r,'<?php echo $api_count; ?>');
				//process_result_update(r,'<?php echo $api_count; ?>',ij);
				search_hash = r.session_expiry_details.search_hash;

				//check_session_time_out(search_hash, r.session_expiry_details.session_start_time); // check session expired or not
				
				// Hide skeleton loader after flight list is generated
				setTimeout(function() {
					$('#onwFltContainer').hide();
				}, 500);
			}
            }
			
			}, 1);
			ij++;
		},
		error: function(xhr, status, error) {
			console.error('Error loading flights:', error);
			$('#onwFltContainer').hide();
			api_result_counter++;
			if(api_result_counter >= api_count) {
				$('#empty-search-result').show();
				$('#page-parent').hide();
			}
		}
	});

}
/*
	append hidden element search_hash for booking form when submiting
*/
$('body').on('submit', '.book-form-wrapper, #multi-flight-form', function(){
	$('<input />').attr('type', 'hidden')
          .attr('name', "search_hash")
          .attr('value', search_hash)
          .appendTo(this);
      return true;
});
</script>
<?php 
if(isset($active_booking_source) && valid_array($active_booking_source)) 
{
    $total_api = count($active_booking_source);
    
    foreach($active_booking_source as $act_k => $boking_src) 
    {
    $bs = trim($boking_src['source_id']);
    echo '<script> load_flights("'.$bs.'");</script>';
    }
}
?>
<span class="hide">
	<input type="hidden" id="pri_preferred_currency" value='<?=$this->currency->get_currency_symbol(get_application_currency_preference())?>'>
	<input type="hidden" id="pri_trip_type" value='<?=$is_domestic_one_way_flight?>'>
	<input type="hidden" id="pri_active_source" value='<?=$active_source?>'>
	<input type="hidden" id="pri_search_id" value='<?=$flight_search_params['search_id']?>'>
	<input type="hidden" id="pri_airline_lg_path" value='<?=SYSTEM_IMAGE_DIR.'airline_logo/'?>'>
	<input type="hidden" id="pri_search_params" value='<?=json_encode($flight_search_params)?>'>
	<input type="hidden" name="" id="pri_template_image_path" value="<?=$GLOBALS['CI']->template->template_images()?>">
	<input type="hidden" id="pri_def_curr" value='<?=$this->currency->get_currency_symbol($to_currency)?>'>
</span>
<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('flights-result.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
// Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_search'.($is_domestic_roundway?'_old':'').'.js?v=1'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_search.js?v=1'), 'defer' => 'defer');
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.jsort.0.4.min.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');
$data['trip_details'] = $flight_search_params;
$data['airline_list'] = $airline_list;//Balu A
$template_images = $GLOBALS['CI']->template->template_images();
$mini_loading_image = '<div class="text-center loader-image"><img src="'.$template_images.'loader_v3.gif" alt="Loading........"/></div>';
$loading_image = '<div class="text-center loader-image"><img src="'.$template_images.'loader_v1.gif" alt="Loading........"/></div>';
$flight_o_direction_icon = '<img src="'.$template_images.'icons/flight-search-result-up-icon.png" alt="Flight Search Result Up Icon">';
echo $GLOBALS['CI']->template->isolated_view('flight/search_panel_summary');
?>
<!-- Page Scripts -->
<button onclick="topFunction()" id="backtotop" title="Go to top"></button>
<section class="search-result onlyfrflty">
<?php if (!empty($right_ads)) { ?>

<?php foreach ($right_ads as $right_ad) { ?>

    <div class="vertcl_banner_right ">

        <img src="<?php echo $GLOBALS['CI']->template->domain_images($right_ad['image']) ?>" width="131px" height="1186px" alt="" />
        <div class="right_bannr_cntnt">
        <?php if($right_ad['message']!=null && $right_ad['message']!='null'){?> <p><?= $right_ad['message'] ?></p><?php } ?>
            <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->
        </div>
    </div>
<?php } ?>
<?php } ?>
	<div class="container-fluid"  id="page-parent">
		<?php echo $GLOBALS['CI']->template->isolated_view('share/loader/flight_result_pre_loader',$data);?>
		<div class="resultalls open">
			<div class="coleft">
				<div class="flteboxwrp">
					
					<!-- Refine Search Filters Start -->
					<div class="fltrboxin">
						<div class="celsrch">
								<div class="row_top_fltr">
									<!-- <a class="snf_btn float-start active" title="Show Net Fare">
									<span class="fas fa-tag"></span>
									<span class="tag_snf">SNF</span></a> -->
                                    <div class="hdr_flx">
                                        <h5>Filters</h5>
                                        <a class="float-end reset_filter" id="reset_filters">RESET ALL</a>
                                    </div>
                                    <div class="filtersho">
                                        <div class="avlhtls"><strong id="total_records"> </strong> flight found
                                        </div>
                                        <span class="close_fil_box"><i class="fas fa-times"></i></span>
                                    </div>
								</div>
							<div class="rangebox">
								<button data-bs-target="#collapse501" data-bs-toggle="collapse" class="collapsebtn" type="button">
								Price
								</button>
								<div id="collapse501" class="in">
									<div class="price_slider1">
									<div id="core_min_max_slider_values" class="hide">
										<input type="hiden" id="core_minimum_range_value" value="">
										<input type="hiden" id="core_maximum_range_value" value="">
									</div>
										<p id="amount" class="level"></p>
										<div id="slider-range" class="" aria-disabled="false"></div>
									</div>
								</div>
							</div>
							<div class="septor"></div>
							<div class="rangebox">
                                 <button data-bs-target="#collapselvr" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                 Layover
                                 </button>
                                 <div id="collapselvr" class="in">
                                    <div class="price_slider1">
                                       <div id="lay_min_max_slider_values" class="hide">
                                          <input type="hidden" id="lay_minimum_range_value" value="8">
                                          <input type="hidden" id="lay_maximum_range_value" value="12">
                                       </div>
                                       <p id="layover" class="level"></p>
                                       <div id="slider-range-lay" class="" aria-disabled="false"></div>
                                    </div>
                                 </div>
                              </div>
							<div class="septor"></div>
							<div class="rangebox">
								<button data-bs-target="#collapsenstp" data-bs-toggle="collapse" class="collapsebtn" type="button">
								No. of Stops
								</button>
								<div id="collapsenstp" class="collapse in">
									<div class="boxins marret stopCountWrapper" id="stopCountWrapper">
										<a class="stopone toglefil stop-wrapper">
											<input type="checkbox" class="hidecheck stopcount" value="0"></input>
											<div class="starin">
												<div class="stopbig"> 0 <span class="stopsml">stop</span></div>
												<!-- <span class="htlcount min-price">-</span> -->
											</div>
										</a>
										<a class="stopone toglefil stop-wrapper">
											<input type="checkbox" class="hidecheck stopcount" value="1"></input>
											<div class="starin">
												<div class="stopbig"> 1 <span class="stopsml">stop</span></div>
												<!-- <span class="htlcount min-price">-</span> -->
											</div>
										</a>
										<a class="stopone toglefil stop-wrapper">
											<input type="checkbox" class="stopcount hidecheck" value="2"></input>
											<div class="starin">
												<div class="stopbig"> 1+ <span class="stopsml">stop</span></div>
												<!-- <span class="htlcount min-price">-</span> -->
											</div>
										</a>
									</div>
								</div>
							</div>
                            <div class="septor"></div>
                              <div class="rangebox">
                                 <button data-bs-target="#collapsedeptT" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                 Departure Time
                                 </button>
                                 <div id="collapsedeptT" class="collapse in">
                                    <div class="marret" id="departureTimeWrapper">
                                       <div class="price_slider1">
                                         
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="1"></input>
                                             <div class="starin">
                                                <div class="flitsprt mng1"></div>
                                                <span class="htlcount">12-6AM</span>
                                             </div>
                                             <p>Morning</p>
                                          </a>
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="2"></input>
                                             <div class="starin">
                                                <div class="flitsprt mng2"></div>
                                                <span class="htlcount">6-12PM</span>
                                             </div>
                                              <p>Afternoon</p>
                                          </a>
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="3"></input>
                                             <div class="starin">
                                                <div class="flitsprt mng3"></div>
                                                <span class="htlcount">12-6PM</span>
                                             </div>
                                             <p>Evening</p>
                                          </a>
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="hidecheck time-category" value="4"></input>
                                             <div class="starin">
                                                <div class="flitsprt mng4"></div>
                                                <span class="htlcount">6-12AM</span>
                                             </div>
                                             <p>Night</p>
                                          </a>
                                       </div>
                                       
                                    </div>
                                 </div>
                              </div>
                              <div class="septor"></div>
                              <div class="rangebox">
                                 <button data-bs-target="#collapsearvT" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                 Arrival Time
                                 </button>
                                 <div id="collapsearvT" class="collapse in">
                                    <div class="marret" id="arrivalTimeWrapper">
                                       <div class="price_slider1">
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="1">
                                             <div class="starin">
                                                <div class="flitsprt mng1"></div>
                                                <span class="htlcount">12-6AM</span>
                                             </div>
                                             <p>Morning</p>
                                          </a>
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="2">
                                             <div class="starin">
                                                <div class="flitsprt mng2"></div>
                                                <span class="htlcount">6-12PM</span>
                                             </div>
                                             <p>Afternoon</p>
                                          </a>
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="3">
                                             <div class="starin">
                                                <div class="flitsprt mng3"></div>
                                                <span class="htlcount">12-6PM</span>
                                             </div>
                                             <p>Evening</p>
                                          </a>
                                          <a class="timone toglefil time-wrapper">
                                             <input type="checkbox" class="time-category hidecheck" value="4">
                                             <div class="starin">
                                                <div class="flitsprt mng4"></div>
                                                <span class="htlcount">6-12AM</span>
                                             </div>
                                             <p>Night</p>
                                          </a>
                                       
                                       </div>
                                      
                                    </div>
                                 </div>
                              </div>
							<div class="septor"></div>
							<div class="rangebox">
								<button data-bs-target="#collapse505" data-bs-toggle="collapse" class="collapsebtn" type="button">
								Airlines
								</button>
								<div id="collapse505" class="collapse in">
									<div class="boxins" id="allairlines">
										
									</div>
								</div>
							</div>
							<div class="septor"></div>
							<div class="rangebox">
                                 <button data-bs-target="#collapse507" data-bs-toggle="collapse" class="collapsebtn" type="button">Airports</button>
                                 <div id="collapse507" class="collapse in">
                                    <ul class="locationul shw_dat">
                                       <h5>Departing from</h5>
                                       <li>
                                          <div class="boxins" id="originairport">
                                          </div>
                                       </li>
                                       <h5>Arriving at</h5>
                                       <li>
                                          <div class="boxins" id="destinationairport">
                                          </div>
                                       </li>
                                       <h5>Connecting in</h5>
                                       <li>
                                          <div class="boxins" id="layovers">
                                          </div>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
							<div class="septor"></div> 
							<div class="rangebox hide">
								<button data-bs-target="#collapse506" data-bs-toggle="collapse" class="collapsebtn" type="button">
								Fare Type
								</button>
								<div id="collapse506" class="collapse in">
									<div class="boxins" id="allflighttype">
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Refine Search Filters End -->
			</div>
			<div class="colrit">
				<?php if($flight_search_params['trip_type'] != 'multicity'){ ?>
				<!-- Prev|Next Searcrh Button Starts -->
				<?php
				$core_travel_date = $flight_search_params['depature'];
				$current_date = date('Y-m-d');
				if(strtotime($core_travel_date) > strtotime($current_date)) {
					$prev_day_search_disabled = '';
					$prev_day_search_date = date('d-m-Y', strtotime('-1 day', strtotime($core_travel_date)));
					$prev_day_search_url = base_url().'index.php/flight/add_days_todate?search_id='.$flight_search_params['search_id'].'&new_date='.$prev_day_search_date;
				} else {
					$prev_day_search_url = '';
					$prev_day_search_disabled = ' disabled="disabled" ';
				}
				$next_day_search_date = date('d-m-Y', strtotime('+1 day', strtotime($core_travel_date)));
				$next_day_search_url = base_url().'index.php/flight/add_days_todate?search_id='.$flight_search_params['search_id'].'&new_date='.$next_day_search_date;
				?>
                <div class="prev_next_date hide">
	                <a <?=$prev_day_search_disabled?> href="<?=$prev_day_search_url?>" title="<?=(empty($prev_day_search_date) == false ? app_friendly_absolute_date($prev_day_search_date): 'depart is less thant current date')?>" class="btn_dates"><i class="fal fa-angle-left"></i> Prev Day</a>
                    <span class="datevery">
	                <?=app_friendly_absolute_date($core_travel_date)?>
                    </span>
	                <a href="<?=$next_day_search_url?>" title="<?=(empty($next_day_search_date) == false ? app_friendly_absolute_date($next_day_search_date): 'depart is less than current date')?>" class="btn_dates">Next Day <i class="fal fa-angle-right"></i></a>
                 </div>
               <!-- Prev|Next Searcrh Button Ends -->
               <?php } ?>
				<div class="insidebosc">
				<!-- Fare Calander -->
				<?php if ($is_domestic_one_way_flight == true) : ?>
				<div class="calandcal hide" id="fare_calendar_wrapper">
					<div class="col-12 nopad">
						<div class="farenewcal">
							<div class="matrx">
								<div id="farecal" class="owl-carousel matrixcarsl">
								</div>
								</div>
								</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php endif; // Checking if one way domestic?>
				<!-- Fare Calander End -->
					<!-- Airline Slider Start -->
					<?php /*<div class="airlinrmatrix" id="clone-list-container">
                        <div class="inside_shadow_airline">
                            <div class="linefstr hide">
                                <div class="airlineall">All Airline</div>
                            </div>
                            <div class="linescndr">
                                <div class="matrx">
                                    <!-- <div id="arlinemtrx" class="owl-carousel matrixcarsl">
                                    </div> -->
                                    <div id="fare-serch-airlin" style="padding:10px;">
                                        <div class="column title">
                                            <h3>Looking for a specific airline?</h3>
                                        </div>
                                        <div class="airline-stp-price">
                                            <div class=" num-stops">
                                                <ul>
                                                    <li><a><span>Non-Stop / <br>1 Stop</span></a></li>
                                                    <li><a><span>1+ Stop</span></a></li>
                                                </ul>
                                            </div>
                                            <div id="arlinemtrx" class="owl-carousel matrixcarsl">
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>*/?>
                    <div class="airlinrmatrix" id="clone-list-container">
						<div class="inside_shadow_airline">
							<div class="linescndr">
								
								<div data-filter-track-id="airlin" class="airline-airlin">
									<div class="airline-airlin-wrap">
										<div class="controls display-table">
											<div id="fare-serch-airlin">
												<div class="column title">
													<h3>Looking for a specific airline?</h3>
												</div>
												<div class="airline-stp-price">
													<div class=" num-stops">
														<ul>
															<li><a><span>Non-Stop / <br>1 Stop</span></a></li>
															<li><a><span>1+ Stop</span></a></li>
														</ul>
													</div>
                                                    <div class="matrx" id="arlinemtrx1">
                                                        <!-- div id="arlinemtrx" class="owl-carousel matrixcarsl arlinemtrx">
                                                        </div> -->
                                                    </div>
													<div id="arlinemtrx" class="owl-carousel matrixcarsl arlinemtrx">
												
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Airline Slider End -->
                    <div class="clearfix"></div>
                    <div class="top-filtr">
						<div class="filter_tab "><i class="fas fa-sliders-h rticon"></i>All Filter</div>
						<div class="stp">
							<button data-bs-target="#collapsestop" data-bs-toggle="collapse" class="collapsebtn stp-line" type="button">
								Stops <i class="fal fa-chevron-down down_arr1"></i>
							</button>
							<div id="collapsestop" class="fil collapse">
                                <div class="boxins marret stopCountWrapper" id="stopCountWrapper1">

                                    <a class="stopone toglefil stop-wrapper">
                                        <input type="checkbox" class="hidecheck stopcount" value="0"></input>
                                        <div class="starin">
                                            <div class="stopbig"> 0 <span class="stopsml">stop</span></div>
                                            <span class="htlcount min-price">-</span>
                                        </div>
                                    </a>

                                    <a class="stopone toglefil stop-wrapper">
                                        <input type="checkbox" class="hidecheck stopcount" value="1"></input>
                                        <div class="starin">
                                            <div class="stopbig"> 1 <span class="stopsml">stop</span></div>
                                            <span class="htlcount min-price">-</span>
                                        </div>
                                    </a>

                                    <a class="stopone toglefil stop-wrapper">
                                        <input type="checkbox" class="stopcount hidecheck" value="2"></input>
                                        <div class="starin">
                                            <div class="stopbig"> 1+ <span class="stopsml">stop</span></div>
                                            <span class="htlcount min-price">-</span>
                                        </div>
                                    </a>
                                
                                </div>
                            </div>
						</div>
					</div>
                    <div class="clearfix"></div>
					<!-- banner add -->
					  <div class="price-ofr-tab">
					    <a class="active best">BEST<strong class="price"></strong></a>
					    <a class="cheapest">CHEAPEST <strong class="price"></strong></a>
					    <a class="shortest">SHORTEST / DIRECT <strong class="price"></strong></a>
					    <a class="flexiable">FLEXIBLE <strong class="price"></strong></a>
					  </div>
                    <!--  Current Selection  -->
                    <div class="fixincrmnt hide" id="multi-flight-summary-container">
                        <div class="insidecurent">
                        <div class="col-9 nopad">
                            <div class="col-6 nopad">
                                <div class="selctarln colorretn">
                                    <div class="col-2 nopad flightimage">
                                        <div class="fligthsmll">
                                            <img class="departure-flight-icon" src="<?=$template_images?>airline.png" alt="" />
                                        </div>
                                        <div class="airlinename departure-flight-name">Please Select</div>
                                    </div>
                                    
                                    <div class="col-10 nopad listfull">
                                        <div class="sidenamedesc">
                                        
                                        
                                            <div class="celhtl width80">
                                                <div class="waymensn">
                                                    <div class="flitruo">
                                                        <div class="outbound-details">
                                                        </div>
                                                        <div class="detlnavi outbound-timing-details">
                                                            <div class="col-4 padflt widfty">
                                                                <span class="timlbl departure">---</span>
                                                            </div>
                                                            <div class="col-4 padflt nonefity">
                                                                <div class="lyovrtime">
                                                                    <span class="flect duration">---</span>
                                                                    <span class="flect stop-count">---</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-4 padflt widfty">
                                                                <span class="timlbl arrival text_algn_rit">---</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>                  
                                </div>
                            </div>
                            <div class="col-6 nopad">
                                <div class="selctarln cloroutbnd">
                                    <div class="col-2 nopad flightimage">
                                        <div class="fligthsmll">
                                            <img class="arrival-flight-icon" src="<?=$template_images?>airline.png" alt="" />
                                        </div>
                                        <div class="airlinename arrival-flight-name">---</div>
                                    </div>
                                    
                                    <div class="col-10 nopad listfull">
                                        <div class="sidenamedesc">
                                        
                                        
                                            <div class="celhtl width80">
                                                <div class="waymensn">
                                                    <div class="flitruo">
                                                        <div class="inbound-details">
                                                        </div>
                                                        <div class="detlnavi inbound-timing-details">
                                                            <div class="col-4 padflt widfty">
                                                                <span class="timlbl departure">---</span>
                                                            </div>
                                                            <div class="col-4 padflt nonefity">
                                                                <div class="lyovrtime">
                                                                    <span class="flect duration">---</span>
                                                                    <span class="flect stop-count">---</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-4 padflt widfty">
                                                                <span class="timlbl arrival text_algn_rit">---</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>                  
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-3 nopad">
                                <div class="sidepricewrp"> 
                                    <div class="col-12 nopad">             		
                                        <div class="sidepricebig">
                                        	<strong class="currency"></strong> <span class="f-p"></span>
                                        </div>
                                    </div>
                                    <div class="col-12 nopad float-end">
                                        <div class="bookbtn">
											<input type="hidden" id="flight-from-price" value="0">
											<input type="hidden" id="flight-to-price" value="0">
											<form id="multi-flight-form" action="" method="POST">
												<div class="hide" id="trip-way-wrapper"></div>
												<button class="btn-flat booknow" type="submit" id="multi-flight-booking-btn">Book</button>
											</form>
                                        </div>
                                    </div>
                                </div>
                                
                        </div>
                        </div>
                    </div>
        			<!--  Current Selection  End  -->

                	
					<div class="clearfix"></div>
                    <!-- <div class="filter_tab fas fa-filter"></div> -->
                    <?php $sorting_list = '';?>
                    <div class="filterforall addtwofilter" id="top-sort-list-wrapper">
                        <div class="topmisty" id="top-sort-list-1">
                            <div class="col-12 nopad">
                            <div class="divinsidefltr">
                                <div class="insidemyt col-12 nopad">
                                <?php 
                                    $sorting_list .= '<ul class="sortul">';
                                        $sorting_list .= '<li class="sortli hide_lines">';
                                         $sorting_list .= '<a class="sorta name-l-2-h loader asc"><i class="fas fa-plane"></i> <strong>Airline</strong></a>';
                                         $sorting_list .= '<a class="sorta name-h-2-l hide loader des"><i class="fas fa-plane"></i> <strong>Airline</strong></a>';
                                         $sorting_list .= '</li>';
                                         $sorting_list .= '<li class="sortli">';
                                         $sorting_list .= '<a class="sorta departure-l-2-h loader asc"><i class="far fa-calendar-alt"></i> <strong>Depart</strong></a>';
                                         $sorting_list .= '<a class="sorta departure-h-2-l hide loader des"><i class="far fa-calendar-alt"></i> <strong>Depart</strong></a>';
                                        $sorting_list .= '</li>';
                                          $sorting_list .= '<li class="sortli hide_lines">';
                                        	$sorting_list .= '<a class="sorta duration-l-2-h loader asc"><i class="far fa-clock"></i> <strong>Duration</strong></a>';
                                            $sorting_list .= '<a class="sorta duration-h-2-l hide loader des"><i class="far fa-clock"></i> <strong>Duration</strong></a>';
                                        $sorting_list .= '</li>';
                                        $sorting_list .= '<li class="sortli">';
                                        	$sorting_list .= '<a class="sorta arrival-l-2-h loader asc"><i class="far fa-calendar-alt"></i> <strong>Arrive</strong></a>';
                                            $sorting_list .= '<a class="sorta arrival-h-2-l hide loader des"><i class="far fa-calendar-alt"></i> <strong>Arrive</strong></a>';
                                        $sorting_list .= '</li>';
                                      
                                        $sorting_list .= '<li class="sortli">';
                                        	$sorting_list .= '<a class="sorta price-l-2-h loader asc"><i class="fas fa-tag"></i> <strong>Price</strong></a>';
                                            $sorting_list .= '<a class="sorta price-h-2-l hide loader des"><i class="fas fa-tag"></i> <strong>Price</strong></a>';
                                        $sorting_list .= '</li>';
                                    $sorting_list .= '</ul>';
                                    echo $sorting_list; ?>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="topmisty" id="top-sort-list-2">
                            <div class="col-10 nopad divinsidefltr">
                                <div class="insidemyt">
                                    <?=$sorting_list?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-- FLIGHT SEARCH RESULT START -->
					<div  class="allresult" id="flight_search_result">

					<div class="fl width100 fltRndTripWrap" id="onwFltContainer">
                            <!-- <div class="fl padTB5 width100">
                            </div> -->
                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>
                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>

                            <div class="fl width100">
                                <div class="card fl width100 marginTB10">

                                    <div class="card-block fl width100 padT20 marginT10 padB10 padLR20">
                                        <div class="col-md-2 col-sm-2 col-2 padT10">
                                            <span class="db padB10 marginR20 marginB10 col-md-8 animated-background"></span>
                                            <span class="db padT10 animated-background col-md-8 marginR20"></span>
                                        </div>
                                        <div class="col-md-7 col-sm-7 col-7 padT10 padLR0 brdRight">
                                            <div class="fl width100">
                                                <div class="col-md-3 col-sm-3 col-3">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-5">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-3 padLR0">
                                                    <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                    <span class="animated-background db padB10 marginR20"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-3 col-sm-3 fltPrice">
                                            <div class="col-md-5 col-sm-8 col-8 fr padT10">
                                                <span class="animated-background db padT10 marginB5 marginR20"></span>
                                                <span class="animated-background db padB10 marginR20"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fl width100 padTB10 animated-background"></div>
                                </div>

                            </div>
                        </div>

					</div>
					<div  class="" id="empty_flight_search_result" style="display:none">
                    
                    	<div class="noresultfnd">
                        	<div class="imagenofnd"><img src="<?=$template_images?>empty.jpg" alt="Empty" /></div>
                            <div class="lablfnd">No Result Found!!!</div>
                        </div>
                    
					</div>
					<!-- FLIGHT SEARCH RESULT END -->
				</div>
			</div>
            <?php if (!empty($left_ads)) { ?>

<?php foreach ($left_ads as $left_ad) { ?>

    <div class="vertcl_banner_left ">

        <img src="<?php echo $GLOBALS['CI']->template->domain_images($left_ad['image']) ?>" width="131px" height="1186px" alt="" />
        <div class="left_bannr_cntnt">
            <?php if($left_ad['message']!=null && $left_ad['message']!='null'){?> <p><?= $left_ad['message'] ?></p><?php } ?>
            <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->
        </div>
    </div>
<?php } ?>
<?php } ?>
		</div>
	</div>
	<div id="empty-search-result" class="jumbotron container" style="display:none">
		<h1><i class="fas fa-plane"></i> Oops!</h1>
		<p>No flights were found for this route today.</p>
		<p>
		Search results change daily based on availability.If you have an urgent requirement, please get in touch with our call center using the contact details mentioned on the home page. They will assist you to the best of their ability.
		</p>
	</div>

	<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup');?>
</section>
<script>
$(document).ready(function() {

	$(".close_fil_box").click(function(){
			$(".coleft").hide();
			$(".resultalls").removeClass("open");
      });

	//************************** **********/
	//airline price sort
	<?php
	$i=1;
	for ($i=1; $i<=2; $i++) ://Multiple Filters Need to be loaded - Balu A
	?>
	$("#top-sort-list-<?=$i?> .price-l-2-h").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .price-h-2-l').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.f-p:first',
			item: '.r-r-i',
			order: 'asc',
			is_num: true
		});
	});
	$("#top-sort-list-<?=$i?> .price-h-2-l").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .price-l-2-h').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.f-p:first',
			item: '.r-r-i',
			order: 'desc',
			is_num: true
		});
	});
	//airline name sort
	$("#top-sort-list-<?=$i?> .name-l-2-h").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .name-h-2-l').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.a-n:first',
			item: '.r-r-i',
			order: 'asc',
			is_num: false
		});
	});
	$("#top-sort-list-<?=$i?> .name-h-2-l").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .name-l-2-h').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.a-n:first',
			item: '.r-r-i',
			order: 'desc',
			is_num: false
		});
	});
	//duration sort
	$("#top-sort-list-<?=$i?> .duration-l-2-h").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .duration-h-2-l').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.f-d:first',
			item: '.r-r-i',
			order: 'asc',
			is_num: true
		});
	});
	$("#top-sort-list-<?=$i?> .duration-h-2-l").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .duration-l-2-h').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.f-d:first',
			item: '.r-r-i',
			order: 'desc',
			is_num: true
		});
	});
	//departure name sort
	$("#top-sort-list-<?=$i?> .departure-l-2-h").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .departure-h-2-l').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.fdtv:first',
			item: '.r-r-i',
			order: 'asc',
			is_num: true
		});
	});
	$("#top-sort-list-<?=$i?> .departure-h-2-l").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .departure-l-2-h').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.fdtv:first',
			item: '.r-r-i',
			order: 'desc',
			is_num: true
		});
	});
	//arrival sort
	$("#top-sort-list-<?=$i?> .arrival-l-2-h").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .arrival-h-2-l').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.fatv:first',
			item: '.r-r-i',
			order: 'asc',
			is_num: true
		});
	});
	$("#top-sort-list-<?=$i?> .arrival-h-2-l").click(function() {
		$(this).addClass('hide');
		$('#top-sort-list-<?=$i?> .arrival-l-2-h').removeClass('hide');
		$("#flight_search_result #t-w-i-<?=$i?>").jSort({
			sort_by: '.fatv:first',
			item: '.r-r-i',
			order: 'desc',
			is_num: true
		});
	});
	<?php
	endfor;
	//End Multiple Filter Looping - Balu A
	?>
    $(".cheapest").click(function() {

        $("#flight_search_result .t-w-i-1").jSort({
            sort_by: '.f-p:first',
            item: '.r-r-i',
            order: 'asc',
            is_num: true
        });
    });
    
    $(".best, .shortest, .flexiable").click(function() {
        $("#flight_search_result .t-w-i-1").jSort({
            sort_by: '.stps:first',
            item: '.r-r-i',
            order: 'asc',
            is_num: true
        });
    });
	//if ($(window).width() < 550) {
//			$(document).on("click",".madgrid",function(){
//				var search_id = $(this).data('key');
//				$( "#form-id-"+ search_id).submit();
//			});
//			
//		}	

	//show/hide net fare	
	$('.snf_btn').click(function(){
		$(this).toggleClass('active');
		$('.net-fare-tag').toggle();
		var title = 'Show Net Fare' ;
		if( $(this).hasClass('active')){
		   title = 'Show Net Fare';
		}
		else{
			title = 'Hide Net Fare';
		}
		$(this).attr('title', title);
		$('.tag_snf', this).text(function(i, text){
			return text === "SNF" ? "HNF" : "SNF";
		});
	});
});
var owl_airline = $("#arlinemtrx1");
       
   
    //    owl3.owlCarousel({      
    //        itemsCustom : [
    //            [0, 1],
    //            [450, 1],
    //            [551, 2],
    //            [700, 2],
    //            [1000, 3],
    //            [1200, 3],
    //            [1400, 3],
    //            [1600, 3]
    //        ],
    //        navigation : true,
    //        pagination:false
    //    });
</script>
<?php
echo $this->template->isolated_view('share/media/flight_search');
?>
<script>
$(document).ready(function () {
	$(document).click(function(event) {
		if (!$(event.target).closest('#collapsestop').length && !$(event.target).closest('[data-bs-toggle="collapse"]').length) {
		$('#collapsestop').collapse('hide');
		}
	});
	$('#collapsestop').click(function(event) {
		event.stopPropagation();
	});
});
</script>
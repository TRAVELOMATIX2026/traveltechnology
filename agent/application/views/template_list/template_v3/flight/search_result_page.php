

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
 .plcetogo input { height:38px !important; border:none !important; box-shadow:none !important;}

</style>

<?php

foreach ($active_booking_source as $t_k => $t_v) {

	$active_source[] = $t_v['source_id'];

}

$api_count = count($active_source);

$active_source = json_encode($active_source);

//debug($flight_search_params);exit;

$is_domestic_roundway = $flight_search_params['is_domestic'] && $flight_search_params['trip_type'] == 'circle' ? 1:0;

?>



<script>



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

var pref_carrier = "<?php echo @$_GET['carrier'];?>";

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

		url: app_base_url+'ajax/flight_list?booking_source='+bs+'&search_id=<?=$flight_search_params['search_id']?>&lcc_gds='+lcc_gds+'&conn_direct='+conn_direct+'&carrier='+pref_carrier+'&op=load',

		async: true,

		cache: true,

		dataType: 'json',

		success: function(res) {

			var dui;

			var r = res;

			console.log('Flight list response:', r);

			// Check if response has raw_flight_list

			if (!r || !r.hasOwnProperty('raw_flight_list')) {

				console.error('Response missing raw_flight_list:', r);

				$('#onwFltContainer').hide();

				$('#empty_flight_search_result').show();

				return;

			}

			dui = setInterval(function(){

			if(is_domestic_roundway == '11'){

                if (typeof(process_result_update) != "undefined" && $.isFunction(process_result_update) == true) {	

                    clearInterval(dui);

                    $(".progress-bar").css("width",  "100%");

                    process_result_update(r,'<?php echo $api_count; ?>',ij);

                }

            }else{

				if (typeof(generate_flight_list) != "undefined" && $.isFunction(generate_flight_list) == true) {	

					clearInterval(dui);





					$('body').removeClass('modal-open');

    				$('.modal-backdrop').remove();



					$(".progress-bar").css("width",  "100%"); //.text("100 %")

					generate_flight_list(r,'<?php echo $api_count; ?>');

					// process_result_update(r,'<?php echo $api_count; ?>',ij);

					// Safely access session_expiry_details

					if (r.session_expiry_details && r.session_expiry_details.search_hash) {

						search_hash = r.session_expiry_details.search_hash;

						//console.log(search_hash, r.session_expiry_details.session_start_time);

						check_session_time_out(search_hash, r.session_expiry_details.session_start_time); // check session expired or not

					} else {

						console.warn('session_expiry_details not found in response');

					} 

				} else {

					console.error('generate_flight_list function not found or not a function');

				}

			}

			}, 1);

			$('#onwFltContainer').hide();

			ij++;

		},

		error: function(xhr, status, error) {

			console.error('AJAX Error loading flights:', {

				status: status,

				error: error,

				responseText: xhr.responseText,

				statusCode: xhr.status

			});

			$('#onwFltContainer').hide();

			$('#empty_flight_search_result').show();

			alert('Error loading flight results. Please try again.');

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

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/flights-result.css'), 'media' => 'screen');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');

// Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_search'.($is_domestic_roundway?'_old':'').'.js?v=1'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_search.js?v=1'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('jquery.ui.touch-punch.min.js'),  'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.jsort.0.4.min.js', 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');

$data['trip_details'] = $flight_search_params;

$data['airline_list'] = $airline_list;//Balu A

$template_images = $GLOBALS['CI']->template->template_images();

$mini_loading_image = '<div class="text-center loader-image"><img src="'.$template_images.'loader_v3.gif" alt="Loading........"/></div>';

$loading_image = '<div class="text-center loader-image"><img src="'.$template_images.'loader_v1.gif" alt="Loading........"/></div>';

$flight_o_direction_icon = '<img src="'.$template_images.'icons/flight-search-result-up-icon.png" alt="Flight Search Result Up Icon">';

echo $GLOBALS['CI']->template->isolated_view('flight/search_panel_summary');



?>



<section class="search-result onlyfrflty">



<?php if (!empty($right_ads)) { ?>



<?php foreach ($right_ads as $right_ad) { ?>



    <div class="vertcl_banner_right ">



        <img src="<?php echo $GLOBALS['CI']->template->domain_images($right_ad['image']) ?>" width="131px" height="1186px" alt="" />

        <div class="right_bannr_cntnt">

            <p><?= $right_ad['message'] ?></p>

            <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->

        </div>

    </div>

<?php } ?>

<?php } ?>



	<div class="container nopad"  id="page-parent">

		<?php //echo $GLOBALS['CI']->template->isolated_view('share/loader/flight_result_pre_loader',$data);?>

		<div class="resultalls">

			<div class="coleft">

				<div class="flteboxwrp">

					<!-- Filter Skeleton Loader -->

					<div id="filter-skeleton-loader" class="fltrboxin">

						<div class="celsrch">

							

							<div class="rangebox">

								<div class="in">

									<div class="price_slider1" style="padding-top: var(--spacing-3);">

										<p class="level skeleton-loader" style="height: 28px; width: 100%; margin-bottom: var(--spacing-3);"></p>

										<div class="skeleton-loader" style="height: 6px; width: 100%; border-radius: 8px;"></div>

									</div>

								</div>

							</div>

							<div class="septor"></div>

							<div class="rangebox">

								<div class="in">

									<div class="price_slider1" style="padding-top: var(--spacing-3);">

										<p class="level skeleton-loader" style="height: 28px; width: 100%; margin-bottom: var(--spacing-3);"></p>

										<div class="skeleton-loader" style="height: 6px; width: 100%; border-radius: 8px;"></div>

									</div>

								</div>

							</div>

							<div class="septor"></div>

							<div class="rangebox">

								<div class="collapse in">

									<div class="boxins marret stopCountWrapper" style="display: flex; gap: var(--spacing-2); flex-wrap: wrap; padding-top: var(--spacing-3);">

										<a class="stopone toglefil stop-wrapper" style="pointer-events: none; margin: 0; flex: 1 1 calc(33.333% - 8px); min-width: 0;">

											<div class="starin skeleton-loader" style="height: 60px; width: 100%; border-radius: var(--border-radius-md);"></div>

										</a>

										<a class="stopone toglefil stop-wrapper" style="pointer-events: none; margin: 0; flex: 1 1 calc(33.333% - 8px); min-width: 0;">

											<div class="starin skeleton-loader" style="height: 60px; width: 100%; border-radius: var(--border-radius-md);"></div>

										</a>

										<a class="stopone toglefil stop-wrapper" style="pointer-events: none; margin: 0; flex: 1 1 calc(33.333% - 8px); min-width: 0;">

											<div class="starin skeleton-loader" style="height: 60px; width: 100%; border-radius: var(--border-radius-md);"></div>

										</a>

									</div>

								</div>

							</div>

							<div class="septor"></div>



							<div class="rangebox">

								<div class="collapse in">

									<div class="boxins" style="padding-top: var(--spacing-3);">

										<ul class="locationul" style="margin: 0; padding: 0; list-style: none;">

											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">

												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>

												<label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>

											</li>

											</ul>

										</div>

								</div>

							</div>





							<div class="septor"></div>

							<div class="rangebox">

								<div class="collapse in">

									<div class="boxins" style="padding-top: var(--spacing-3);">

										<ul class="locationul" style="margin: 0; padding: 0; list-style: none;">

											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">

												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>

												<label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>

											</li>

											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">

												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>

												<label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>

											</li>

											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">

												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>

												<label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>

											</li>

											<li style="margin-bottom: 0; display: flex; align-items: center; gap: var(--spacing-2);">

												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>

												<label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>

											</li>

										</ul>

									</div>

								</div>

							</div>

						</div>

					</div>

					<!-- Refine Search Filters Start -->

					<div class="fltrboxin" id="filter-content" style="display: none;">

						<div class="celsrch">

							<div class="d-flex justify-content-between align-items-center">

								<div class="hdr_flx">

									<a class="float-end" id="reset_filters">RESET ALL</a>

								</div>

								<div class="filtersho">

									<div class="avlhtls"><strong id="total_records"> </strong> <span id="flights_text">flights</span> found

									</div>

									<span class="close_fil_box"><i class="fas fa-times"></i></span>

								</div>

							</div>

							<div class="clearfix"></div>

							<div class="septor"></div>

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

                                 <button data-bs-target="#collapse502" data-bs-toggle="collapse" class="collapsebtn" type="button">

                                 Layover

                                 </button>

                                 <div id="collapse502" class="in">

                                    <div class="price_slider1">

                                       <div id="lay_min_max_slider_values" class="hide">

                                          <input type="hidden" id="lay_minimum_range_value" value="1">

                                          <input type="hidden" id="lay_maximum_range_value" value="18">

                                       </div>

                                       <p id="layover" class="level"></p>

                                       <div id="slider-range-lay" class="" aria-disabled="false"></div>

                                    </div>

                                 </div>

                              </div>

							<div class="septor"></div>

							<div class="rangebox">

								<button data-bs-target="#collapse5020" data-bs-toggle="collapse" class="collapsebtn" type="button">

								No. of Stops

								</button>

								<div id="collapse5020" class="collapse in">

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

							<!-- <div class="septor"></div> -->

							<!-- OLD DEPARTURE TIME  -->

							<div class="rangebox hide">

								<button data-bs-target="#collapse503" data-bs-toggle="collapse" class="collapsebtn" type="button">

								Departure Time

								</button>

								<div id="collapse503" class="collapse in">

									<div class="boxins marret" id="departureTimeWrapper1">

										<a class="timone toglefil time-wrapper">

										    

											<div class="starin">

											    <div class="tmxdv">

											      <input type="checkbox" class="time-category hidecheck" value="1">

           										  <label class="ckboxdv">Early Morning</label>

           								        </div>

												<div class="flitsprt mng1"></div>

												<span class="htlcount">12-6AM</span>

											</div>

											

										</a>

										<a class="timone toglefil time-wrapper">

											

											<div class="starin">

											 <div class="tmxdv">

											<input type="checkbox" class="time-category hidecheck" value="2">

											<label class="ckboxdv">Morning</label>

									         </div>	

												<div class="flitsprt mng2"></div>

												<span class="htlcount">6-12PM</span>

											</div>



										</a>

										<a class="timone toglefil time-wrapper">



											<div class="starin">

											<div class="tmxdv">

											<input type="checkbox" class="time-category hidecheck" value="3">

											<label class="ckboxdv">Mid-Day</label>

											</div>

												<div class="flitsprt mng3"></div>

												<span class="htlcount">12-6PM</span>

											</div>

										</a>

										<a class="timone toglefil time-wrapper">

										    <div class="tmxdv">

											<input type="checkbox" class="hidecheck time-category" value="4">

											<label class="ckboxdv">Evening</label>

											</div>

											<div class="starin">

												<div class="flitsprt mng4"></div>

												<span class="htlcount">6-12AM</span>

											</div>

										</a>

									</div>

								</div>

							</div>

							<!-- <div class="septor"></div> -->

							<!-- OLD ARRIVAL TIME  -->



							<div class="rangebox hide">

								<button data-bs-target="#collapse504" data-bs-toggle="collapse" class="collapsebtn" type="button">

								Arrival Time

								</button>

								<div id="collapse504" class="collapse in">

									<div class="boxins marret" id="arrivalTimeWrapper1">

										<a class="timone toglefil time-wrapper">

										   <div class="tmxdv">

											<input type="checkbox" class="time-category hidecheck" value="1">

											<label class="ckboxdv">Early Morning</label>

										   </div>

											<div class="starin">

												<div class="flitsprt mng1"></div>

												<span class="htlcount">12-6AM</span>

											</div>

										</a>

										<a class="timone toglefil time-wrapper">

										  <div class="tmxdv">

											<input type="checkbox" class="time-category hidecheck" value="2">

											<label class="ckboxdv">Morning</label>

										  </div>

											<div class="starin">

												<div class="flitsprt mng2"></div>

												<span class="htlcount">6-12PM</span>

											</div>

										</a>

										<a class="timone toglefil time-wrapper">

										  <div class="tmxdv">

											<input type="checkbox" class="time-category hidecheck" value="3">

											<label class="ckboxdv">Mid-Day</label>

										  </div>

											<div class="starin">

												<div class="flitsprt mng3"></div>

												<span class="htlcount">12-6PM</span>

											</div>

										</a>

										<a class="timone toglefil time-wrapper">

										  <div class="tmxdv">

											<input type="checkbox" class="time-category hidecheck" value="4">

											<label class="ckboxdv">Evening</label>

										  </div>

											<div class="starin">

												<div class="flitsprt mng4"></div>

												<span class="htlcount">6-12AM</span>

											</div>

										</a>

									</div>

								</div>

							</div>

							

							<div class="septor"></div>

                              <div class="rangebox">

                                 <button data-bs-target="#collapse505" data-bs-toggle="collapse" class="collapsebtn" type="button">

                                 Departure Time

                                 </button>

                                 <div id="collapse505" class="collapse in">

                                    <div class="marret" id="departureTimeWrapper">

                                       <div class="price_slider1">

                                         

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="1"></input>

                                             <div class="starin">

                                                <div class="flitsprt mng1">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <circle cx="12" cy="12" r="5" fill="#FFD700" stroke="#FFA500" stroke-width="1.5"/>

                                                      <path d="M12 2V4M12 20V22M22 12H20M4 12H2M19.07 4.93L17.66 6.34M6.34 17.66L4.93 19.07M19.07 19.07L17.66 17.66M6.34 6.34L4.93 4.93" stroke="#FFA500" stroke-width="1.5" stroke-linecap="round"/>

                                                   </svg>

                                                </div>

                                                <span class="htlcount">12-6AM</span>

                                             </div>

                                             <p>Morning</p>

                                          </a>

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="2"></input>

                                             <div class="starin">

                                                <div class="flitsprt mng2">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <circle cx="12" cy="12" r="6" fill="#FFD700" stroke="#FF8C00" stroke-width="1.5"/>

                                                      <path d="M12 1V3M12 21V23M23 12H21M3 12H1M20.66 3.34L19.24 4.76M4.76 19.24L3.34 20.66M20.66 20.66L19.24 19.24M4.76 4.76L3.34 3.34" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>

                                                   </svg>

                                                </div>

                                                <span class="htlcount">6-12PM</span>

                                             </div>

                                              <p>Afternoon</p>

                                          </a>

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="3"></input>

                                             <div class="starin">

                                                <div class="flitsprt mng3">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <circle cx="12" cy="12" r="5" fill="#FF6347" stroke="#FF4500" stroke-width="1.5"/>

                                                      <path d="M12 2V4M12 20V22M22 12H20M4 12H2" stroke="#FF4500" stroke-width="1.5" stroke-linecap="round"/>

                                                      <path d="M2 20L6 16M22 20L18 16" stroke="#FF6347" stroke-width="1.5" stroke-linecap="round"/>

                                                   </svg>

                                                </div>

                                                <span class="htlcount">12-6PM</span>

                                             </div>

                                             <p>Evening</p>

                                          </a>

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="hidecheck time-category" value="4"></input>

                                             <div class="starin">

                                                <div class="flitsprt mng4">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="#E6E6FA" stroke="#C0C0C0" stroke-width="1.5"/>

                                                      <circle cx="18" cy="6" r="1.5" fill="#C0C0C0" opacity="0.6"/>

                                                      <circle cx="19" cy="10" r="1" fill="#C0C0C0" opacity="0.5"/>

                                                      <circle cx="17" cy="14" r="0.8" fill="#C0C0C0" opacity="0.4"/>

                                                   </svg>

                                                </div>

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

                                 <button data-bs-target="#collapse506" data-bs-toggle="collapse" class="collapsebtn" type="button">

                                 Arrival Time

                                 </button>

                                 <div id="collapse506" class="collapse in">

                                    <div class="marret" id="arrivalTimeWrapper">

                                       <div class="price_slider1">

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="1">

                                             <div class="starin">

                                                <div class="flitsprt mng1">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <circle cx="12" cy="12" r="5" fill="#FFD700" stroke="#FFA500" stroke-width="1.5"/>

                                                      <path d="M12 2V4M12 20V22M22 12H20M4 12H2M19.07 4.93L17.66 6.34M6.34 17.66L4.93 19.07M19.07 19.07L17.66 17.66M6.34 6.34L4.93 4.93" stroke="#FFA500" stroke-width="1.5" stroke-linecap="round"/>

                                                   </svg>

                                                </div>

                                                <span class="htlcount">12-6AM</span>

                                             </div>

                                             <p>Morning</p>

                                          </a>

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="2">

                                             <div class="starin">

                                                <div class="flitsprt mng2">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <circle cx="12" cy="12" r="6" fill="#FFD700" stroke="#FF8C00" stroke-width="1.5"/>

                                                      <path d="M12 1V3M12 21V23M23 12H21M3 12H1M20.66 3.34L19.24 4.76M4.76 19.24L3.34 20.66M20.66 20.66L19.24 19.24M4.76 4.76L3.34 3.34" stroke="#FF8C00" stroke-width="1.5" stroke-linecap="round"/>

                                                   </svg>

                                                </div>

                                                <span class="htlcount">6-12PM</span>

                                             </div>

                                             <p>Afternoon</p>

                                          </a>

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="3">

                                             <div class="starin">

                                                <div class="flitsprt mng3">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <circle cx="12" cy="12" r="5" fill="#FF6347" stroke="#FF4500" stroke-width="1.5"/>

                                                      <path d="M12 2V4M12 20V22M22 12H20M4 12H2" stroke="#FF4500" stroke-width="1.5" stroke-linecap="round"/>

                                                      <path d="M2 20L6 16M22 20L18 16" stroke="#FF6347" stroke-width="1.5" stroke-linecap="round"/>

                                                   </svg>

                                                </div>

                                                <span class="htlcount">12-6PM</span>

                                             </div>

                                             <p>Evening</p>

                                          </a>

                                          <a class="timone toglefil time-wrapper">

                                             <input type="checkbox" class="time-category hidecheck" value="4">

                                             <div class="starin">

                                                <div class="flitsprt mng4">

                                                   <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

                                                      <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="#E6E6FA" stroke="#C0C0C0" stroke-width="1.5"/>

                                                      <circle cx="18" cy="6" r="1.5" fill="#C0C0C0" opacity="0.6"/>

                                                      <circle cx="19" cy="10" r="1" fill="#C0C0C0" opacity="0.5"/>

                                                      <circle cx="17" cy="14" r="0.8" fill="#C0C0C0" opacity="0.4"/>

                                                   </svg>

                                                </div>

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

								<button data-bs-target="#collapse5030" data-bs-toggle="collapse" class="collapsebtn" type="button">

								Airlines

								</button>

								<div id="collapse5030" class="collapse in">

									<div class="boxins" id="allairlines">

									</div>

								</div>

							</div>

                              <div class="rangebox">

                                 <button data-bs-target="#collapse507" data-bs-toggle="collapse" class="collapsebtn" type="button">Airports</button>

                                 <div id="collapse507" class="collapse in">

                                    <ul class="locationul shw_dat">

                                       <h5 id="originairportLabel"></h5>

                                       <li>

                                          <div class="boxins" id="originairport">

                                          </div>

                                       </li>

                                       <h5 id="destinationairportLabel"></h5>

                                       <li>

                                          <div class="boxins" id="destinationairport">

                                          </div>

                                       </li>

                                       <h5 id="layoversLabel"></h5>

                                       <li>

                                          <div class="boxins" id="layovers">

                                          </div>

                                       </li>

                                    </ul>

                                 </div>

                              </div>



                              <div class="rangebox hide">

								<button data-bs-target="#collapse5060" data-bs-toggle="collapse" class="collapsebtn" type="button" autocomplete="new-username">

								Fare Type

								</button>

								<div id="collapse5060" class="collapse in">

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

					<!-- Airline Matrix Skeleton Loader -->

					<div class="airlinrmatrix" id="airline-matrix-skeleton">

						<div class="inside_shadow_airline">

							<div class="linescndr">

								<div class="airline-airlin">

									<div class="airline-airlin-wrap">

										<div class="controls display-table">

											<div id="fare-serch-airlin">

												<div class="column title">

													<h3 class="skeleton-loader" style="height: 24px; width: 280px; border-radius: var(--border-radius-sm);"></h3>

												</div>

												<div class="airline-stp-price">



													<div class="matrx">

														<div id="arlinemtrx" class="owl-carousel matrixcarsl" style="display: flex; gap: 15px; overflow: hidden;">

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

															<div class="item skeleton-loader" style="min-width: 120px; height: 90px; border-radius: var(--border-radius-md); flex-shrink: 0;"></div>

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

					<!-- Airline Slider Start -->

					<div class="airlinrmatrix hide" id="clone-list-container">

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

													<!-- <div class=" num-stops">

														<ul>

															<li><a><span>Non-Stop / <br>1 Stop</span></a></li>

															<li><a><span>1+ Stop</span></a></li>

														</ul>

													</div> -->

													<div class="matrx" id="arlinemtrx1"></div>

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

  					<!-- banner add -->

					  <div class="price-ofr-tab hide">

				

					    <a class="active best">BEST<strong class="price"></strong></a>

					    <a class="cheapest">CHEAPEST <strong class="price"></strong></a>

					    <a class="shortest">SHORTEST / DIRECT <strong class="price"></strong></a>

					    <a class="flexiable">FLEXIBLE <strong class="price"></strong></a>

					  </div>

						<?php //if(file_exists($b2c_label[0]['image'])): ?>

					  <div class="price-sumry hide">

						   <div class="best-price">

						    	<img src="/dev/extras/custom/TMX2783081694775862/images/<?php //echo $b2c_label[0]['image'];?>" alt="<?php //echo $b2c_label[0]['alt_text'];?>">

						   </div>

					  </div>

					  <?php // endif ?>

					<div class="clearfix"></div>

					<!--  Current Selection  -->

					<div class="fixincrmnt hide" id="multi-flight-summary-container">

						<div class="insidecurent">

							<div class="col-10 nopad">

								<div class="col-6 nopad">

									<div class="selctarln colorretn">

										<div class="col-3 nopad flightimage">

											<div class="fligthsmll">

												<img class="departure-flight-icon" src="<?=$template_images?>airline.png" alt="" />

											</div>

											<div class="airlinename departure-flight-name">Please Select</div>

										</div>

										<div class="col-9 nopad listfull">

											<div class="sidenamedesc">

												<div class="celhtl width80">

													<div class="waymensn">

														<div class="flitruo">

															<div class="outbound-details">

															</div>

															<div class="detlnavi outbound-timing-details">

																<div class="col-4 padflt widfty">

																	<span class="timlbl departure"></span>

																</div>

																<div class="col-4 padflt nonefity">

																	<div class="lyovrtime">

																		<span class="flect duration"></span>

																		<div class='flect stop-image'></div>

																		<span class="flect stop-count"></span>

																	</div>

																</div>

																<div class="col-4 padflt widfty">

																	<span class="timlbl arrival text_algn_rit"></span>

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

										<div class="col-3 nopad flightimage">

											<div class="fligthsmll">

												<img class="arrival-flight-icon" src="<?=$template_images?>airline.png" alt="" />

											</div>

											<div class="airlinename arrival-flight-name"></div>

										</div>

										<div class="col-9 nopad listfull">

											<div class="sidenamedesc">

												<div class="celhtl width80">

													<div class="waymensn">

														<div class="flitruo">

															<div class="inbound-details">

															</div>

															<div class="detlnavi inbound-timing-details">

																<div class="col-4 padflt widfty">

																	<span class="timlbl departure"></span>

																</div>

																<div class="col-4 padflt nonefity">

																	<div class="lyovrtime">

																		<span class="flect duration"></span>

																		<div class='flect stop-image1'></div>

																		<span class="flect stop-count"></span>

																	</div>

																</div>

																<div class="col-4 padflt widfty">

																	<span class="timlbl arrival text_algn_rit"></span>

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

							<div class="col-2 nopad">

								<div class="sidepricewrp">

									<div class="col-12 nopad">

										<div class="sidepricebig">

											<strong class="currency"></strong> <span class="f-p"></span>

										</div>

									</div>

									<div class="col-12 nopad">

										<div class="bookbtn">

											<input type="hidden" id="flight-from-price" value="0">

											<input type="hidden" id="flight-to-price" value="0">

											<form id="multi-flight-form" action="" method="POST">

												<div class="hide" id="trip-way-wrapper"></div>

												<button class="btn-flat booknow" type="submit" id="multi-flight-booking-btn" onclick="showLoader1();">Book</button>

											</form>

										</div>

									</div>

								</div>

							</div>

						</div>

					</div>

					<!--  Current Selection  End  -->

					<div class="clearfix"></div>

				

					<?php $sorting_list = '';?>

					<?php

						$round_way_trip_filter='';

						if($flight_search_params['trip_type_label']=="Round Way" && $flight_search_params['is_domestic']==true){

							$round_way_trip_filter ='addtwofilter';

						}

					?>

					<div class="filterforall <?=$round_way_trip_filter?>" id="top-sort-list-wrapper">

						<div class="topmisty" id="top-sort-list-1">

							<div class="col-12 nopad">

								<div class="divinsidefltr">

									<div class="insidemyt col-12 nopad">

										<?php

											$sorting_list .= '<ul class="sortul">';

												$sorting_list .= '<li class="sortli hide_lines">';

												$sorting_list .= '<a class="sorta name-l-2-h loader asc"><i class="material-icons">flight</i> <strong>Airline</strong></a>';

												$sorting_list .= '<a class="sorta name-h-2-l hide loader des"><i class="material-icons">flight</i> <strong>Airline</strong></a>';

												$sorting_list .= '</li>';

												$sorting_list .= '<li class="sortli">';

												$sorting_list .= '<a class="sorta departure-l-2-h loader asc"><i class="material-icons">flight_takeoff</i> <strong>Depart</strong></a>';

												$sorting_list .= '<a class="sorta departure-h-2-l hide loader des"><i class="material-icons">flight_takeoff</i> <strong>Depart</strong></a>';

												$sorting_list .= '</li>';

												$sorting_list .= '<li class="sortli hide_lines">';

													$sorting_list .= '<a class="sorta duration-l-2-h loader asc"><i class="material-icons">schedule</i> <strong>Duration</strong></a>';

													$sorting_list .= '<a class="sorta duration-h-2-l hide loader des"><i class="material-icons">schedule</i> <strong>Duration</strong></a>';

												$sorting_list .= '</li>';

												$sorting_list .= '<li class="sortli">';

													$sorting_list .= '<a class="sorta arrival-l-2-h loader asc"><i class="material-icons">flight_land</i> <strong>Arrive</strong></a>';

													$sorting_list .= '<a class="sorta arrival-h-2-l hide loader des"><i class="material-icons">flight_land</i> <strong>Arrive</strong></a>';

												$sorting_list .= '</li>';

												$sorting_list .= '<li class="sortli">';

													$sorting_list .= '<a class="sorta price-l-2-h loader asc"><i class="material-icons">attach_money</i> <strong>Price</strong></a>';

													$sorting_list .= '<a class="sorta price-h-2-l hide loader des"><i class="material-icons">attach_money</i> <strong>Price</strong></a>';

												$sorting_list .= '</li>';

											$sorting_list .= '</ul>';

											echo $sorting_list;

											?>

									</div>

									

								</div>

							</div>

						</div>

                                            <?php if($flight_search_params['trip_type_label']=="Round Way" && $flight_search_params['is_domestic']==true) { ?>

						<div class="topmisty" id="top-sort-list-2">

							<div class="col-10 nopad divinsidefltr">

								<div class="insidemyt">

									<?php echo $sorting_list?>

								</div>

							</div>

						</div>

                                            <?php } ?>

					</div>

					<div class="clearfix"></div>

					<!-- FLIGHT SEARCH RESULT START -->

					<div  class="allresult" id="flight_search_result">

						<img src="<?=$template_images;?>preloader.gif" style="position: fixed;z-index: 111;display: none;margin: 2% auto;" id="bookingLoader">

						<!-- Result Loader Overlay -->

						<div id="result-loader-overlay" class="result-loader-overlay hide">

							<div class="result-loader-content">

								<div class="result-loader-graphic">

									<div class="plane-icon">

										<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">

											<path d="M21 16V14L15 9.5V7C15 6.45 14.55 6 14 6H13V4C13 3.45 12.55 3 12 3H11C10.45 3 10 3.45 10 4V6H9C8.45 6 8 6.45 8 7V9.5L2 14V16L8 13.5V19L6 20.5V22L12 21L18 22V20.5L16 19V13.5L21 16Z" fill="currentColor"/>

										</svg>

									</div>

									<div class="loader-rings">

										<div class="ring ring-1"></div>

										<div class="ring ring-2"></div>

										<div class="ring ring-3"></div>

									</div>

								</div>

								<p class="result-loader-text">Loading flights...</p>

								<div class="loader-progress-bar">

									<div class="loader-progress-fill"></div>

								</div>

							</div>

						</div>

					<!-- Result Skeleton Loader -->

					<div id="result-skeleton-loader" class="fl width100 fltRndTripWrap">

						<?php for ($i = 1; $i <= 6; $i++): ?>

							<div class="rowresult p-0" style="margin-bottom: <?php echo $i === 1 ? 'var(--spacing-3)' : 'var(--spacing-4)'; ?>;">

								<div class="madgrid">

									<div class="col-12 nopad d-flex">

									<div class="allsegments d-flex">

										<div class="quarter_wdth nopad col-3">

											<div class="text_center_airline">

												<div class="fligthsmll skeleton-loader" style="width: 48px; height: 48px; border-radius: 50%; margin: 0 auto var(--spacing-2);"></div>

												<div class="m-b-0 text-center">

													<div class="a-n airlinename skeleton-loader" style="height: 15px; width: 85px; margin: 0 auto var(--spacing-1); border-radius: var(--border-radius-sm);"></div>

													<span class="skeleton-loader" style="height: 11px; width: 65px; margin: 0 auto; display: block; border-radius: var(--border-radius-sm);"></span>

												</div>

											</div>

										</div>

										<div class="col-3 nopad quarter_wdth">

											<div class="insidesame">

												<div class="f-d-t bigtimef skeleton-loader" style="height: 22px; width: 60px; margin-bottom: var(--spacing-1); border-radius: var(--border-radius-sm);"></div>

												<span class="flt_date skeleton-loader" style="height: 11px; width: 50px; display: block; margin-bottom: var(--spacing-1); border-radius: var(--border-radius-sm);"></span>

												<div class="from-loc smalairport skeleton-loader" style="height: 13px; width: 100px; border-radius: var(--border-radius-sm);"></div>

											</div>

										</div>

										<div class="smal_udayp nopad col-3">

											<div class="insidesame">

												<div class="durtntime skeleton-loader" style="height: 16px; width: 70px; margin-bottom: var(--spacing-1); border-radius: var(--border-radius-sm);"></div>

												<div class="stop_image skeleton-loader" style="width: 32px; height: 16px; margin: 0 auto var(--spacing-1); border-radius: var(--border-radius-sm);"></div>

												<div class="stop-value skeleton-loader" style="height: 11px; width: 60px; margin: 0 auto; border-radius: var(--border-radius-sm);"></div>

											</div>

										</div>

										<div class="col-3 nopad quarter_wdth">

											<div class="insidesame">

												<div class="f-a-t bigtimef skeleton-loader" style="height: 22px; width: 60px; margin-bottom: var(--spacing-1); border-radius: var(--border-radius-sm);"></div>

												<span class="flt_date skeleton-loader" style="height: 11px; width: 50px; display: block; margin-bottom: var(--spacing-1); border-radius: var(--border-radius-sm);"></span>

												<div class="to-loc smalairport skeleton-loader" style="height: 13px; width: 100px; border-radius: var(--border-radius-sm);"></div>

											</div>

										</div>

									</div>

									<div class="wayprice" style="display: flex; flex-direction: column; align-items: flex-end; justify-content: center; gap: var(--spacing-2);">

										<div class="skeleton-loader" style="height: 18px; width: 100px; border-radius: var(--border-radius-sm);"></div>

										<div class="skeleton-loader" style="height: 34px; width: 120px; border-radius: var(--border-radius-md);"></div>

									</div>

						</div>

								</div>

							</div>

						<?php endfor; ?>

					</div>

					<div class="fl width100 fltRndTripWrap hide" id="onwFltContainer">

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

			<!-- BANNER LEFT -->



			<?php if (!empty($left_ads)) { ?>



<?php foreach ($left_ads as $left_ad) { ?>



    <div class="vertcl_banner_left ">



        <img src="<?php echo $GLOBALS['CI']->template->domain_images($left_ad['image']) ?>" width="131px" height="1186px" alt="" />

        <div class="left_bannr_cntnt">

            <p><?= $left_ad['message'] ?></p>

            <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->

        </div>

    </div>

<?php } ?>

<?php } ?>

			<!-- BANNER LEFT END -->

		</div>

	</div>

	<div id="empty-search-result" class="jumbotron container" style="display:none">

		<h1><i class="fal fa-plane"></i> Oops!</h1>

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

			

			// Show loader immediately

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			// Use requestAnimationFrame to ensure loader is visible before sorting

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.f-p:first',

					item: '.r-r-i',

					order: 'asc',

					is_num: true

				});

				

				// Hide loader after sorting completes

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		$("#top-sort-list-<?=$i?> .price-h-2-l").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .price-l-2-h').removeClass('hide');

			

			// Show loader immediately

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			// Use requestAnimationFrame to ensure loader is visible before sorting

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.f-p:first',

					item: '.r-r-i',

					order: 'desc',

					is_num: true

				});

				

				// Hide loader after sorting completes

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		//airline name sort

		$("#top-sort-list-<?=$i?> .name-l-2-h").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .name-h-2-l').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.a-n:first',

					item: '.r-r-i',

					order: 'asc',

					is_num: false

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		$("#top-sort-list-<?=$i?> .name-h-2-l").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .name-l-2-h').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.a-n:first',

					item: '.r-r-i',

					order: 'desc',

					is_num: false

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		//duration sort

		$("#top-sort-list-<?=$i?> .duration-l-2-h").click(function() {

			

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .duration-h-2-l').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.f-d:first',

					item: '.r-r-i',

					order: 'asc',

					is_num: true

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		$("#top-sort-list-<?=$i?> .duration-h-2-l").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .duration-l-2-h').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.f-d:first',

					item: '.r-r-i',

					order: 'desc',

					is_num: true

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		//departure name sort

		$("#top-sort-list-<?=$i?> .departure-l-2-h").click(function() {

			

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .departure-h-2-l').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.fdtv:first',

					item: '.r-r-i',

					order: 'asc',

					is_num: true

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		$("#top-sort-list-<?=$i?> .departure-h-2-l").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .departure-l-2-h').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.fdtv:first',

					item: '.r-r-i',

					order: 'desc',

					is_num: true

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		//arrival sort

		$("#top-sort-list-<?=$i?> .arrival-l-2-h").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .arrival-h-2-l').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.fatv:first',

					item: '.r-r-i',

					order: 'asc',

					is_num: true

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		$("#top-sort-list-<?=$i?> .arrival-h-2-l").click(function() {

			$(this).addClass('hide');

			$('#top-sort-list-<?=$i?> .arrival-l-2-h').removeClass('hide');

			

			if (typeof window.showResultLoader === 'function') {

				window.showResultLoader();

			} else if (typeof showResultLoader === 'function') {

				showResultLoader();

			}

			

			requestAnimationFrame(function() {

				$("#flight_search_result #t-w-i-<?=$i?>").jSort({

					sort_by: '.fatv:first',

					item: '.r-r-i',

					order: 'desc',

					is_num: true

				});

				

				setTimeout(function() {

					if (typeof window.hideResultLoader === 'function') {

						window.hideResultLoader();

					} else if (typeof hideResultLoader === 'function') {

						hideResultLoader();

					}

				}, 400);

			});

		});

		<?php

		endfor;

		?>

	});

function showLoader1()

{

    $( ".search-result" ).fadeTo( "slow", 0.33 );

    $( "#bookingLoader" ).show();

}

 var owl_airline = $("#arlinemtrx1");

</script>

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



$(window).scroll(function() {

	$(window).scrollTop() > 40 ? ($(".vertcl_banner_right").addClass("banner_sticky")) : ($(".vertcl_banner_right").removeClass("banner_sticky"));	

});

$(document).ready(function () {

    const $defaultTab = $('.price-ofr-tab .best');



    $('.price-ofr-tab a').removeClass('active');

    $defaultTab.addClass('active');



    // initial sort

    sortGroups("price", 'asc');

});

</script>

<script type="text/javascript"> 

	$('.durtntime').each(function () {

    console.log($('.durtntime').text());

});





function sortGroups(type, order) {

	$("#flight_search_result .r-w-g").each(function() {

		let $wrapper = $(this);

		let $items = $wrapper.children(".rowresult").detach();

		$items.sort(function(a, b) {

			let aVal = 0,

				bVal = 0; // BEST (weighted score) 

			if (type === 'best') {

				const ap = parseFloat($(a).find('.price').data('price')?.replace(',', '.')) || 0;

				const bp = parseFloat($(b).find('.price').data('price')?.replace(',', '.')) || 0;

				const as = parseInt($(a).find('.stps').data('category')) || 0;

				const bs = parseInt($(b).find('.stps').data('category')) || 0;

				const al = parseInt($(a).find('.layover-duration').data('layoverdurationhm')) || 0;

				const bl = parseInt($(b).find('.layover-duration').data('layoverdurationhm')) || 0;

				aVal = (ap * 1) + (as * 50) + (al * 10);

				bVal = (bp * 1) + (bs * 50) + (bl * 10);

			} // CHEAPEST 

			if (type === 'price') {

				aVal = parseFloat($(a).find('.price').data('price')?.replace(',', '.')) || 0;

				bVal = parseFloat($(b).find('.price').data('price')?.replace(',', '.')) || 0;

			} // SHORTEST (stops) 

			if (type === 'stops') {

				console.log(type);

				aVal = parseInt($(a).find('.stps').data('category')) || 0;

				console.log(parseInt($(a).find('.stps').data('category')));

				bVal = parseInt($(b).find('.stps').data('category')) || 0;

				console.log(parseInt($(b).find('.stps').data('category')));

			} // FLEXIBLE (layover time) 

			if (type === 'layover') {

				aVal = parseInt($(a).find('.layover-duration').data('layoverdurationhm')) || 0;

				bVal = parseInt($(b).find('.layover-duration').data('layoverdurationhm')) || 0;

			}

			return order === "asc" ? aVal - bVal : bVal - aVal;

		});

		$wrapper.append($items);

		

	});

}





</script>

</script>



<!-- Mobile Filter FAB Button and Overlay -->

<div class="filter-overlay" aria-hidden="true"></div>

<button class="filter-fab-btn" title="Open Filters" aria-label="Open Filters">

	<i class="material-icons">tune</i>

	<span class="filter-count hide" aria-hidden="true">0</span>

</button>

<!-- Mobile filter button/overlay JS moved to extras/.../javascript/page_resource/flight_search.js to avoid inline scripts -->



<?php

// Include HTML templates for JavaScript flight search

echo $GLOBALS['CI']->template->isolated_view('flight/flight_search_templates');

?>

<?=$this->template->isolated_view('share/media/flight_search');?>
<?php

//Read cookie when user has not given any search
if ((isset($flight_search_params) == false) || (isset($flight_search_params) == true && valid_array($flight_search_params) == false)) {
	//parse_str(get_cookie('flight_search'), $flight_search_params);
	$sparam = $this->input->cookie('sparam', TRUE);
	$sparam = unserialize($sparam);
	$sid = intval(@$sparam[META_AIRLINE_COURSE]);
 
	$flight_search_params = array();
	if ($sid > 0) {
		$this->load->model('flight_model');
		$flight_search_params = $this->flight_model->get_safe_search_data($sid, true);
		
		$flight_search_params = @$flight_search_params['data'];
		if ($flight_search_params['trip_type'] != 'multicity' && strtotime(@$flight_search_params['depature']) < time() ) {
			$flight_search_params['depature'] = date('d-m-Y');
			if (isset($flight_search_params['return']) == true) {
				$flight_search_params['return'] = date('d-m-Y', strtotime(add_days_to_date(1)));
			}
		}
	}
}

$onw_rndw_segment_search_params = array();
$multicity_segment_search_params = array();
if(@$flight_search_params['trip_type'] != 'multicity') {
	$onw_rndw_segment_search_params = $flight_search_params;
} else {//MultiCity
	$multicity_segment_search_params = $flight_search_params;
}

$flight_datepicker = array(array('flight_datepicker1', FUTURE_DATE_DISABLED_MONTH), array('flight_datepicker2', FUTURE_DATE_DISABLED_MONTH));
$this->current_page->set_datepicker($flight_datepicker);
$GLOBALS['CI']->current_page->auto_adjust_datepicker(array(array('flight_datepicker1', 'flight_datepicker2')));
$airline_list = $GLOBALS['CI']->db_cache_api->get_airline_code_list();
if(isset($flight_search_params['adult_config']) == false || intval($flight_search_params['adult_config']) < 1) {
	$flight_search_params['adult_config'] = 1;
}
?>

<form autocomplete="off" name="flight" id="flight_form" action="<?php echo base_url();?>index.php/general/pre_flight_search" method="get" class="activeForm oneway_frm" style="">
	<div class="trvlhdr-optn">
      <div class="flightSearchNavigation-f d-flex gap-1">
         <div class="trvlhdr nopad dropdown trip_select">
            <div class="smalway">
            <label class="wament hand-cursor <?= (isset($flight_search_params['trip_type']) == false ? 'active' : (($flight_search_params['trip_type']) == 'oneway' ? 'active' : '')) ?>">
            <input class="hide" type="radio" name="trip_type" <?= (isset($flight_search_params['trip_type']) == false ? 'checked' : (($flight_search_params['trip_type']) == 'oneway' ? 'checked="checked"' : '')) ?> id="onew-trp" value="oneway" />
            <i class="material-icons trip-icon">flight</i>
            <span>One Way</span>
            </label>
            <label class="wament hand-cursor <?= (@$flight_search_params['trip_type'] == 'circle' ? 'active' : '') ?>">
            <input class="hide" type="radio" name="trip_type" <?= (@$flight_search_params['trip_type'] == 'circle' ? 'checked="checked"' : '') ?> id="rnd-trp" value="circle" />
            <i class="material-icons trip-icon">sync_alt</i>
            <span>RoundTrip</span>
            </label>
             <label class="wament hand-cursor <?= (@$flight_search_params['trip_type'] == 'multicity' ? 'active' : '') ?>">
            <input class="hide" type="radio" name="trip_type" <?= (@$flight_search_params['trip_type'] == 'multicity' ? 'checked="checked"' : '') ?> id="multi-trp" value="multicity" />
            <i class="material-icons trip-icon">flight_takeoff</i>
            <span>Multi-City</span>
          </label>
           </div>
		</div>

		<div class="trvlhdr nopad classes_Select">
			<?php
				//debug($flight_search_params);exit;
						//Airline Class
						// $v_class = array('Economy' => 'Economy', 'PremiumEconomy' => 'Premium Economy', 'Business' => 'Business', 'PremiumBusiness' => 'Premium Business', 'First' => 'First');
						$v_class = array('Economy' => 'Economy', 'PremiumEconomy' => 'Premium Economy','Business' => 'Business', 'First' => 'First');
						$airline_classes = '';
						if(isset($flight_search_params['v_class']) == true && empty($flight_search_params['v_class']) == false) {
							$choosen_airline_class = $v_class[$flight_search_params['v_class']];
							$irline_class_value = $flight_search_params['v_class'];
							//$air_class ='';
						} else {
							$choosen_airline_class = 'Economy';
							$irline_class_value = 'Economy';
							//$air_class = 'active';
						}
						foreach($v_class as $v_class_k => $v_class_v) {
							if($v_class_v == $choosen_airline_class){
								$air_class = 'active';
							}
							else{
								$air_class ='';
							}
							$airline_classes .= '<a class="adscrla choose_airline_class '.$air_class.'" data-airline_class="'.$v_class_k.'">'.$v_class_v.'</a>';
						}
					
			?>

			<div class="alladvnce">
				<i class="material-icons dropdown-icon">airline_seat_recline_normal</i>
				<span class="remngwd" id="choosen_airline_class"><?php echo $choosen_airline_class;?> </span><i class="bi bi-chevron-down down_arr"></i>
				<!-- <span class="caret"></span> -->
				<input type="hidden" autocomplete="off" name="v_class" id="class" value="<?php echo $irline_class_value;?>" >
				<div class="advncedown spladvnce class_advance_div">
					<div class="inallsnnw">
						<div class="scroladvc">
							<?php echo $airline_classes;?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="trvlhdr nopad special_fares_Select">
			<?php
				$special_fares = array(
					'' => 'Special Fares (Optional)',
					'defence' => 'Defence Forces',
					'student' => 'Students',
					'senior' => 'Senior Citizens',
					'doctor' => 'Doctors Nurses'
				);
				$special_fares_options = '';
				$selected_special_fare = isset($flight_search_params['special_fare']) && !empty($flight_search_params['special_fare']) ? $flight_search_params['special_fare'] : '';
				$choosen_special_fare = isset($special_fares[$selected_special_fare]) ? $special_fares[$selected_special_fare] : 'Special Fares (Optional)';
				
				foreach($special_fares as $fare_key => $fare_value) {
					if($fare_key === '') continue; // Skip the placeholder option
					$fare_active = ($selected_special_fare == $fare_key) ? 'active' : '';
					$special_fares_options .= '<a class="adscrla choose_special_fare '.$fare_active.'" data-special_fare="'.$fare_key.'">'.$fare_value.'</a>';
				}
			?>

			<div class="alladvnce">
				<i class="material-icons dropdown-icon">local_offer</i>
				<span class="remngwd" id="choosen_special_fare"><?php echo $choosen_special_fare; ?></span>
				<i class="bi bi-chevron-down down_arr"></i>
				<input type="hidden" autocomplete="off" name="special_fare" id="special_fare" value="<?php echo $selected_special_fare; ?>" >
				<div class="advncedown spladvnce special_fares_advance_div">
					<div class="inallsnnw">
						<div class="scroladvc">
							<?php echo $special_fares_options; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>

	</div>
 
	<div class="tabspl">
   <div class="tabrow row m-0">
      <div id="onw_rndw_fieldset" class="col-md-10 p-0 row m-0">
         <div class="col-md-7 placerows p-0 row m-0">
            <div class="col-6 p-0">
               <div class="padfive">
               <div class="lablform mobile_label">From</div>
               <div class="plcetogo deprtures sidebord">
                  <i class="material-icons field-icon field-icon-left">flight_takeoff</i>
                  <input type="text" autocomplete="off" name="from" class="normalinput auto-focus valid_class fromflight form-control b-r-0" id="from" placeholder="From City" value="<?php echo @$onw_rndw_segment_search_params['from'] ?>" required />
                  <input class="hide loc_id_holder" id="from_loc_id" name="from_loc_id" type="hidden" value="<?= @$onw_rndw_segment_search_params['from_loc_id'] ?>">
                  <span class="airport_value" id="fromairport_span_val"><?= (@$onw_rndw_segment_search_params['fromairportloc']) ? @$onw_rndw_segment_search_params['fromairportloc'] : 'International Airport'; ?></span>
                  <input class="hide airport_loc" id="fromairportloc" name="fromairportloc" type="hidden" value="<?= @$onw_rndw_segment_search_params['fromairportloc'] ?>">
               </div>
               <div class="exportcomplete" id="export_fromflight" style="display: none;"></div>
               </div>
            </div>

            <div class="col-6">
               <div class="padfive">
               <div class="lablform mobile_label">To</div>
               <div class="plcetogo destinatios sidebord">
                  <i class="material-icons field-icon field-icon-left">flight_land</i>
                  <input type="text" autocomplete="off" name="to" class="normalinput auto-focus valid_class departflight form-control b-r-0" id="to" placeholder="To City" value="<?php echo @$onw_rndw_segment_search_params['to'] ?>" required />
                  <input class="hide loc_id_holder" id="to_loc_id" name="to_loc_id" type="hidden" value="<?= @$onw_rndw_segment_search_params['to_loc_id'] ?>">
                  <input class="hide airport_loc" id="toairportloc" name="toairportloc" type="hidden" value="<?= @$onw_rndw_segment_search_params['toairportloc'] ?>">
                  <span class="airport_value airport_value1" id="span_airport_val"><?= (@$onw_rndw_segment_search_params['toairportloc']) ? @$onw_rndw_segment_search_params['toairportloc'] : 'International Airport'; ?></span>
               </div>
               <div class="exportcomplete" id="export_toflight" style="display: none;"></div>
               </div>
            </div>
            
            <!-- Swap Button Between From and To -->
            <button type="button" class="flight-swap-btn" id="swap_flight_btn" title="Swap From and To">
               <i class="material-icons">swap_horiz</i>
            </button>
         </div>

         <div class="col-md-5 nopad secndates row m-0">
            <div class="col-6 p-0">
               <div class="padfive">
               <div class="lablform">Departure</div>
               <div class="plcetogo datemark sidebord fxheigt datepicker_new1" iditem="flight_datepicker1">
                  <i class="material-icons field-icon field-icon-left">calendar_today</i>
                  <input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0" id="flight_datepicker1" placeholder="Add Date" value="<?=(@$onw_rndw_segment_search_params['depature'] != '') ? date('d-m-Y',strtotime(@$onw_rndw_segment_search_params['depature'])):date('d-m-Y', strtotime('+1 day'));?>" name="depature" required />
                  <div class="changedate changeCheckIndate"></div>
                  <?php 
                     if(@$onw_rndw_segment_search_params['depature'] != ''){
                     	$dept = strtotime(@$onw_rndw_segment_search_params['depature']); 
                     
                     }else{
                     	$dept = strtotime(date('d-m-Y', strtotime('+1 day')));
                     } ?>
                  <div class="change_date">
                     <div class="date_s">
                        <div class="date_p checkInDateDiv"><?= (($dept) ? date('d', $dept) : date('d', strtotime('+1 day'))); ?></div>
                        <div class="year">
                           <span class="month_p checkInMonthDiv"><?= (($dept) ? date('M', $dept) : date('M', strtotime('+1 day'))); ?></span>
                           <span class="year_p checkInYearDiv"><?= (($dept) ? date('Y', $dept) : date('Y', strtotime('+1 day'))); ?></span>
                        </div>
                     </div>
                     <span id="day_name" class="day_name checkInDayDiv"><?= (($dept) ? date('l', $dept) : date('l', strtotime('+1 day'))); ?></span>
                  </div>
                  </div>
               </div>
            </div>
            <div class="col-6 date-wrapper">
               <div class="padfive return-date-field">
               <?php if(@$onw_rndw_segment_search_params['trip_type'] != 'return'): ?>
                  <div class="add-return-date-overlay">
                     <div class="add-return-date-content">
                        <i class="material-icons">add_circle_outline</i>
                        <span class="add-return-date-text">Add return date</span>
                     </div>
                  </div>
                  <?php endif; ?>
                  
               <div class="return-date-wrapper">
               <div class="lablform">Return</div>
               
               <div class="plcetogo datemark sidebord fxheigt datepicker_new2" iditem="flight_datepicker2">
               <i class="material-icons field-icons field-icon-right">event_return</i>
                  <input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0" id="flight_datepicker2" name="return" placeholder="Add Date" value="<?=(@$onw_rndw_segment_search_params['return'] != '') ? date('d-m-Y',strtotime(@$onw_rndw_segment_search_params['return'])):date('d-m-Y', strtotime('+1 day'));?>" <?= (@$onw_rndw_segment_search_params['trip_type'] != 'circle' ? 'disabled="disabled"' : '') ?> />
                  <div class="changedate changeCheckOutdate"></div>
                  <?php
                     if(@$onw_rndw_segment_search_params['return'] != ''){
                     	$ret = strtotime(@$onw_rndw_segment_search_params['return']); 
                     
                     }else{
                     	$ret = strtotime(date('d-m-Y', strtotime('+1 day')));
                     }
                      ?>
                  <div class="change_date return-date-display">                        
								<div class="date_s">                        	   
									<div class="date_p checkInDateDiv"><?=(($dept)?date('d',$dept): date('d'));?></div>
									<div class="year">                                
										<span class="month_p checkInMonthDiv"><?=(($dept)?date('M',$dept): date('M'));?></span> 
									    <span class="year_p checkInYearDiv"><?=(($dept)?date('Y',$dept): date('Y'));?></span>
								    </div>                        
								</div>    
								<span id="day_name1" class="day_name checkInDayDiv"><?=(($dept)?date('l',$dept): date('l'));?></span>                   
							</div>
                 
                        </div>
                  </div>
                        </div>
            </div>
         </div>
      </div>
      <?= $GLOBALS['CI']->template->isolated_view('share/flight_multi_way_search', array('multicity_segment_search_params' => $multicity_segment_search_params)) ?>
      <div class="col-md-2 p-0 col-12 padfive thrdtraveller">
         <div class="col-12 nopad mobile_width">
            <div class="lablform">Travelers</div>
            
            <div class="totlall">
               <i class="material-icons field-icon field-icon-left">people</i>
               <span class="remngwd"><span class="total_pax_count"></span> <span id="travel_text">Traveller</span>
               </span>
               <div class="roomcount pax_count_div">
                  <?php
                     if (isset($flight_search_params['carrier'][0]) == true && empty($flight_search_params['carrier'][0]) == false &&  $flight_search_params['carrier'][0] != 'all') {
                     	$choosen_airline_name = $airline_list[$flight_search_params['carrier'][0]];
                     } else {
                     	$choosen_airline_name = 'Preferred Airline';
                     }
                     $preferred_airlines = '';
                     $selected_airline_code = isset($flight_search_params['carrier'][0]) && !empty($flight_search_params['carrier'][0]) && $flight_search_params['carrier'][0] != 'all' ? $flight_search_params['carrier'][0] : '';
                     
                     // Add "All" option
                     $active_class = empty($selected_airline_code) ? 'active' : '';
                     $preferred_airlines .= '<a class="adscrla choose_preferred_airline ' . $active_class . '" data-airline_code="">All</a>';
                     
                     // Add airline options
                     foreach ($airline_list as $airline_list_k => $airline_list_v) {
                     	$active_class = ($selected_airline_code == $airline_list_k) ? 'active' : '';
                     	$preferred_airlines .= '<a class="adscrla choose_preferred_airline ' . $active_class . '" data-airline_code="' . $airline_list_k . '">' . $airline_list_v . '</a>';
                     }
                     ?>
                  <div class="advance_opt">
                     <div class="col-12 nopad">
                        <div class="lablform2">Preferred Airline</div>
                        <div class="alladvnce" style="width:100%;">
                           <i class="material-icons dropdown-icon">flight</i>
                           <span class="remngwd" id="choosen_preferred_airline"><?php echo $choosen_airline_name; ?></span>
                           <i class="bi bi-chevron-down down_arr"></i>
                           <input type="hidden" autocomplete="off" name="carrier[]" id="carrier" value="<?php echo isset($flight_search_params['carrier'][0]) && !empty($flight_search_params['carrier'][0]) && $flight_search_params['carrier'][0] != 'all' ? $flight_search_params['carrier'][0] : ''; ?>" >
                           <div class="advncedown spladvnce preferred_airlines_advance_div">
                              <div class="inallsnnw">
                                 <div class="airline-search-wrapper">
                                    <div class="airline-search-input-wrapper">
                                       <i class="material-icons airline-search-icon">search</i>
                                       <input type="text" 
                                              id="airline_search_filter" 
                                              class="airline-search-input" 
                                              placeholder="Search airline..." 
                                              autocomplete="off">
                                    </div>
                                 </div>
                                 <div class="scroladvc" id="airline_list_container">
                                    <?php echo $preferred_airlines; ?>
                                 </div>
                                 <div class="no-results-message" style="display: none; padding: var(--spacing-4); text-align: center; color: var(--color-text-muted); font-size: var(--font-size-sm);">
                                    No airlines found
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="inallsn">
                     <div class="oneroom fltravlr">
                        <div class="lablform2">Travellers</div>
                        <div class="clearfix"></div>
                        <div class="roomrow">
                           <div class="celroe col-7"><i class="material-icons">person</i> Adults
                              <span class="agemns">(12+)</span>
                           </div>
                           <div class="celroe col-5">
                              <div class="input-group countmore pax-count-wrapper adult_count_div"> <span class="input-group">
                                 <button type="button" class="btn btn-secondary btn-number" data-type="minus" data-field="adult"> <span class="glyphicon glyphicon-minus"></span> </button>
                                 </span>
                                 <input type="text" id="OWT_adult" name="adult" class="form-control input-number centertext valid_class pax_count_value" value="<?= isset($flight_search_params['adult_config']) && (int)$flight_search_params['adult_config'] > 0 ? (int)$flight_search_params['adult_config'] : 1 ?>" min="1" max="9" readonly>
                                 <span class="input-group">
                                 <button type="button" class="btn btn-secondary btn-number" data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="roomrow">
                           <div class="celroe col-7"><i class="material-icons">child_care</i> Children
                              <span class="agemns">(2-11)</span>
                           </div>
                           <div class="celroe col-5">
                              <div class="input-group countmore pax-count-wrapper child_count_div"> <span class="input-group">
                                 <button type="button" class="btn btn-secondary btn-number" data-type="minus" data-field="child"> <span class="glyphicon glyphicon-minus"></span> </button>
                                 </span>
                                 <input type="text" id="OWT_child" name="child" class="form-control input-number centertext pax_count_value" value="<?= (int)@$flight_search_params['child_config'] ?>" min="0" max="9" readonly>
                                 <span class="input-group">
                                 <button type="button" class="btn btn-secondary btn-number" data-type="plus" data-field="child"> <span class="glyphicon glyphicon-plus"></span> </button>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="roomrow last">
                           <div class="celroe col-7"><i class="material-icons">baby_changing_station</i> Infants
                              <span class="agemns">(0-2)</span>
                           </div>
                           <div class="celroe col-5">
                              <div class="input-group countmore pax-count-wrapper infant_count_div"> <span class="input-group">
                                 <button type="button" class="btn btn-secondary btn-number" data-type="minus" data-field="infant"> <span class="glyphicon glyphicon-minus"></span> </button>
                                 </span>
                                 <input type="text" id="OWT_infant" name="infant" class="form-control input-number centertext pax_count_value" value="<?= (int)@$flight_search_params['infant_config'] ?>" min="0" max="9" readonly>
                                 <span class="input-group">
                                 <button type="button" class="btn btn-secondary btn-number" data-type="plus" data-field="infant"> <span class="glyphicon glyphicon-plus"></span> </button>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="apply-btn-wrapper">
                           <button type="button" class="done1 apply-travelers-btn">
                              <i class="fa fa-check"></i> Apply
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="col-12 nopad">
            <button style="display:none" class="add_city_btn" id="add_city"> <span class="fa fa-plus"></span> Add City</button>
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="alert-box !d-none" id="flight-alert-box"></div>
      <div class="clearfix"></div>
   </div>

  
   <div class="col-md-7 nopad addm d-none" id="extra_service">
      <div class="col-3 nopad">
         <div class="squaredThree">
            <input type="checkbox" class="airlinecheckbox1 triptype" id="squaredThreee4" name="non_stop" value="1">
            <label for="squaredThreee4"></label>
         </div>
         <label for="squaredThreee4" class="lbllbl colwhite">Non Stop</label>
      </div>
      <div class="col-4 nopad">
         <div class="squaredThree">
            <input type="checkbox" class="airlinecheckbox2 triptype" id="squaredThreee5" name="Refundable" value="1">
            <label for="squaredThreee5"></label>
         </div>
         <label for="squaredThreee5" class="lbllbl colwhite">Refundable Flight</label>
      </div>
   </div>
   <div class="col-5 col-md-2 nopad float-end d-none">
      <button class="farhomecal" id="flight_fare_calendar"><span class="fal fa-calendar-alt"></span> Fare Calendar</button>
   </div>
</div>


<div class="search_button_container">
  <div class="searchsbmtfot flightbutton position-absolute">
   <input type="submit" name="search_flight" id="flight-form-submit" class="searchsbmt flight_search_btn" value="Search Flight">
   <i class="fas fa-search"></i>
   </div>
  </div>

	<div class="clearfix"></div>


</form>

<!-- Toast/Snackbar for Error Messages -->
<div class="toast-snackbar-wrapper d-none">
   <div role="alert" class="toast-snackbar toast-snackbar-error">
      <i class="material-icons toast-icon">error_outline</i>
      <span class="alert-content toast-message"></span>
      <button type="button" class="toast-close" aria-label="Close">
         <i class="material-icons">close</i>
      </button>
   </div>
</div>

<style>
.multi_city_container.inactive_segment {
  display: none !important;
}
</style>

<script>
$(document).ready(function() {

	var select_val ='<?php if(isset($flight_search_params['carrier'][0])){ echo $flight_search_params['carrier'][0]; } ?>';
	//alert(select_val);

      $(".flight_chnge").click(function(){
   var from = $('#from').val();
   var from_loc_id = $('#from_loc_id').val();

   var to = $('#to').val();
   var to_loc_id = $('#to_loc_id').val();


   $('#from').val(to);
   $('#to').val(from);

   $('#from_loc_id').val(to_loc_id);
   $('#to_loc_id').val(from_loc_id);

   $(".flight_chnge .fa-exchange").toggleClass('rot_arrow');
  });

  // Handle radio button active state for custom styling
  function updateRadioButtons() {
    $('.smalway .wament').removeClass('active');
    $('.smalway .wament:has(input[type="radio"]:checked)').addClass('active');
    // Fallback for browsers without :has() support
    $('.smalway input[type="radio"]:checked').closest('.wament').addClass('active');
  }
  
  // Update on page load
  updateRadioButtons();
  
  // Update on change
  $('.smalway input[type="radio"]').on('change', function() {
    updateRadioButtons();
  });

  // Handle click on "Add return date" overlay to switch to round trip
  $(document).on('click', '.add-return-date-overlay', function(e) {
    e.preventDefault();
    e.stopPropagation();

    // Switch to round trip
    $('#rnd-trp').prop('checked', true).trigger('change');

    // Trigger the trip type change function if it exists
    if (typeof t === 'function') {
      t('circle');
    }

    // Don't auto-focus to prevent datepicker from opening automatically
  });

  // Initialize trip type on page load
  var initialTripType = $('[name="trip_type"]:checked').val();
  if (typeof t === 'function' && initialTripType) {
    t(initialTripType);
  }
});

</script>

<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_suggest.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_datepicker.js'), 'defer' => 'defer');
?>


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
		if ($flight_search_params['trip_type'] != 'multicity' && strtotime(@$flight_search_params['depature']) < time()) {
			$flight_search_params['depature'] = date('d-m-Y');
			if (isset($flight_search_params['return']) == true) {
				$flight_search_params['return'] = date('d-m-Y', strtotime(add_days_to_date(1)));
			}
		}
	}
}
$onw_rndw_segment_search_params = array();
$multicity_segment_search_params = array();
if (@$flight_search_params['trip_type'] != 'multicity') {
	$onw_rndw_segment_search_params = $flight_search_params;
} else { //MultiCity
	$multicity_segment_search_params = $flight_search_params;
}
$flight_datepicker = array(array('flight_datepicker1', FUTURE_DATE_DISABLED_MONTH), array('flight_datepicker2', FUTURE_DATE_DISABLED_MONTH));
$this->current_page->set_datepicker($flight_datepicker);
$airline_list = $GLOBALS['CI']->db_cache_api->get_airline_code_list();
if (isset($flight_search_params['adult_config']) == false || intval($flight_search_params['adult_config']) < 1) {
	$flight_search_params['adult_config'] = 1;
}
?>

<style>
    /* Switch Container */
    .switchh {
      position: relative;
      display: inline-block;
      width: 42px;
      height: 20px;
    }

    /* Hide the default checkbox */
    .switchh input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* Slider style */
    .sliderr {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 34px;
    }

    /* Circle inside the switch */
    .sliderr:before {
      position: absolute;
      content: "";
      height: 13px;
      width: 13px;
      border-radius: 50%;
      left: 0;
      bottom: 4px;
      background-color: white;
      transition: 0.4s;
    }

    /* When the checkbox is checked */
    input:checked + .sliderr {
      background-color: #66bb6a;
    }

    /* Move the circle when checked */
    input:checked + .sliderr:before {
      transform: translateX(26px);
    }
  </style>
  <div class="tab_border"> 
  	<div class="srch_flts">Search for <span>Flights</span></div>                  
   </div>
  <form autocomplete="off" name="flight" id="flight_form" action="<?php echo base_url(); ?>index.php/general/pre_flight_search" method="get" class="activeForm oneway_frm" style="">
	<div class="smalway">
		<label class="wament hand-cursor">
			<input class="hide" type="radio" name="trip_type" <?= (isset($flight_search_params['trip_type']) == false ? 'checked' : (($flight_search_params['trip_type']) == 'oneway' ? 'checked="checked"' : '')) ?> id="onew-trp" value="oneway" /> One Way
		</label>
		<label class="wament hand-cursor">
			<input class="hide" type="radio" name="trip_type" <?= (@$flight_search_params['trip_type'] == 'circle' ? 'checked="checked"' : '') ?> id="rnd-trp" onchange="handleChange(this)" value="circle" /> Roundtrip<span> / Return</span>
		</label>
		<label class="wament hand-cursor">
			<input class="hide" type="radio" name="trip_type" <?= (@$flight_search_params['trip_type'] == 'multicity' ? 'checked="checked"' : '') ?> id="multi-trp" value="multicity" /> Multi-city
		</label>
	</div>
	
	<div class="tabspl">
		<div class="tabrow">
			<div id="onw_rndw_fieldset" class="col-md-9 nopad">
				<!-- Oneway/Roundway Fileds Starts-->
				<div class="col-md-7 nopad placerows">
					<div class="col-6 padfive">
						<div class="lablform mobile_label">From</div>
						<div class="plcetogo deprtures sidebord">
							<input type="text" autocomplete="off" name="from" class="normalinput auto-focus valid_class fromflight form-control b-r-0" id="from" placeholder="From City" value="<?php echo @$onw_rndw_segment_search_params['from'] ?>" required />
							<input class="hide loc_id_holder" id="from_loc_id" name="from_loc_id" type="hidden" value="<?= @$onw_rndw_segment_search_params['from_loc_id'] ?>">
							<span class="airport_value" id="fromairport_span_val"><?= (@$onw_rndw_segment_search_params['fromairportloc']) ? @$onw_rndw_segment_search_params['fromairportloc'] : 'International Airport'; ?></span>
							<input class="hide airport_loc" id="fromairportloc" name="fromairportloc" type="hidden" value="<?= @$onw_rndw_segment_search_params['fromairportloc'] ?>">

							<div class="flight_chnge"><i class="far fa-exchange rot_arrow"></i></div>

						</div>
						<div class="exportcomplete" id="export_fromflight" style="display: none;"></div>


					</div>
					<div class="col-6 padfive">
						<div class="lablform mobile_label">To</div>
						<div class="plcetogo destinatios sidebord">
							<input type="text" autocomplete="off" name="to" class="normalinput auto-focus valid_class departflight form-control b-r-0" id="to" placeholder="To City" value="<?php echo @$onw_rndw_segment_search_params['to'] ?>" required />
							<input class="hide loc_id_holder" id="to_loc_id" name="to_loc_id" type="hidden" value="<?= @$onw_rndw_segment_search_params['to_loc_id'] ?>">
							<input class="hide airport_loc" id="toairportloc" name="toairportloc" type="hidden" value="<?= @$onw_rndw_segment_search_params['toairportloc'] ?>">

							<span class="airport_value airport_value1" id="span_airport_val"><?= (@$onw_rndw_segment_search_params['toairportloc']) ? @$onw_rndw_segment_search_params['toairportloc'] : 'International Airport'; ?></span>

						</div>
						<div class="exportcomplete" id="export_toflight" style="display: none;"></div>
					</div>
				</div>
				<div class="col-md-5 nopad secndates">
					<div class="col-6 padfive">
						<div class="lablform">Departure</div>
						<div class="plcetogo datemark sidebord fxheigt datepicker_new1" iditem="flight_datepicker1">
							<input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0" id="flight_datepicker1" placeholder="Select Date" value="<?php echo @$onw_rndw_segment_search_params['depature'] ?>" name="depature" required />
							<div class="changedate changeCheckIndate"></div>
							<?php $dept = strtotime(@$onw_rndw_segment_search_params['depature'] ?? ''); ?>
							<div class="change_date">
								<div class="date_s">
									<div class="date_p checkInDateDiv"><?= (($dept) ? date('d', $dept) : date('d')); ?></div>
									<div class="year">
										<span class="month_p checkInMonthDiv"><?= (($dept) ? date('M', $dept) : date('M')); ?></span>
										<span class="year_p checkInYearDiv"><?= (($dept) ? date('Y', $dept) : date('Y')); ?></span>
									</div>
								</div>
								<span id="day_name" class="day_name checkInDayDiv"><?= (($dept) ? date('l', $dept) : date('l')); ?></span>
							</div>
							<!--<div class="dateToAppendPicker departdate_append"></div>-->
						</div>
					</div>

					<div class="col-6 padfive date-wrapper">
						<div class="lablform">Return</div>
						<div class="plcetogo datemark sidebord fxheigt datepicker_new2" iditem="flight_datepicker2">
							<input type="text" readonly class="normalinput auto-focus hand-cursor form-control b-r-0" id="flight_datepicker2" name="return" placeholder="Select Date" value="<?php echo @$onw_rndw_segment_search_params['return'] ?>" <?= (@$onw_rndw_segment_search_params['trip_type'] != 'circle' ? 'disabled="disabled"' : '') ?> />

							<div class="changedate changeCheckOutdate"></div>
							<?php $ret = strtotime(@$onw_rndw_segment_search_params['return'] ?? ''); ?>
							<div class="change_date">
								<div class="date_s">
									<div class="date_p checkOutDateDiv"><?= $day = ($ret) ? date('d', strtotime('+1 day', $ret)) : date('d', strtotime('+1 day')); ?></div>
									<div class="year">
										<span class="month_p checkOutMonthDiv"><?= (($ret) ? date('M', $ret) : date('M')); ?></span>
										<span class="year_p checkOutYearDiv"><?= (($ret) ? date('Y', $ret) : date('Y')); ?></span>
									</div>
								</div>
								<span id="day_name1" class="day_name checkOutDayDiv"><?= ($ret) ? date('l', strtotime('+1 day', $ret)) : date('l', strtotime('+1 day')); ?></span>
							</div>
							<!--<div class="dateToAppendPicker returndate_append"></div> -->
						</div>
					</div>
				</div>
			</div>
			<!-- Oneway/Roundway Fileds Ends-->


			<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_multi_way_search', array('multicity_segment_search_params' => $multicity_segment_search_params)) ?>
			<!-- Multiway-->

			<div class="col-md-3 col-12 nopad thrdtraveller">
				<div class="col-7 padfive mobile_width">
					<div class="lablform">Travelers</div>
					<div class="totlall">
						<span class="remngwd"><span class="total_pax_count"></span>
							<span id="travel_text">Traveller</span>
							<!-- <span id="class_select" class="class_name"><?php //echo $choosen_airline_class ?></span> -->
						</span>
						<div class="roomcount pax_count_div">
							<?php
								//debug($flight_search_params);exit;
								//Airline Class
								// $v_class = array('Economy' => 'Economy', 'PremiumEconomy' => 'Premium Economy', 'Business' => 'Business', 'PremiumBusiness' => 'Premium Business', 'First' => 'First');
								$v_class = array('Economy' => 'Economy', 'PremiumEconomy' => 'Premium Economy', 'Business' => 'Business', 'First' => 'First');
								$airline_classes = '';
								if (isset($flight_search_params['v_class']) == true && empty($flight_search_params['v_class']) == false) {
									$choosen_airline_class = $v_class[$flight_search_params['v_class']];
									$irline_class_value = $flight_search_params['v_class'];
									//$air_class ='';
								} else {
									$choosen_airline_class = 'Economy';
									$irline_class_value = 'Economy';
									//$air_class = 'active';
								}
								foreach ($v_class as $v_class_k => $v_class_v) {
									if ($v_class_v == $choosen_airline_class) {
										$air_class = 'active';
									} else {
										$air_class = '';
									}
									$airline_classes .= '<a class="adscrla choose_airline_class ' . $air_class . '" data-airline_class="' . $v_class_k . '">' . $v_class_v . '</a>';
								}

								?>

								<div class="advance_opt">
									<div class="lablform2">Cabin Class</div>
									<div class="alladvnce">
										<span class="remngwd" id="choosen_airline_class"><?php echo $choosen_airline_class; ?></span>
										<input type="hidden" autocomplete="off" name="v_class" id="class" value="<?php echo $irline_class_value; ?>">
										<div class="advncedown spladvnce class_advance_div">
											<div class="inallsnnw">
												<div class="scroladvc">
													<?php echo $airline_classes; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php

							//Preferred Airlines
							if (isset($flight_search_params['carrier'][0]) == true && empty($flight_search_params['carrier'][0]) == false &&  $flight_search_params['carrier'][0] != 'all') {
								$choosen_airline_name = $airline_list[$flight_search_params['carrier'][0]];
							} else {
								$choosen_airline_name = 'Preferred Airline';
							}
							$preferred_airlines = '<a class="adscrla choose_preferred_airline" data-airline_code="">All</a>';
							foreach ($airline_list as $airline_list_k => $airline_list_v) {
								$preferred_airlines .= '<a class="adscrla choose_preferred_airline" data-airline_code="' . $airline_list_k . '">' . $airline_list_v . '</a>';
							}
							?>
							<div class="advance_opt">
								<div class="col-12 nopad">
									<div class="lablform2">Preferred Airline</div>
									<div class="alladvnce" style="width:100%;">
										<select class="js-example-basic-single" name="carrier[]">
											<option value="">All</option>
											<?php

											foreach ($airline_list as $airline_list_k => $airline_list_v) { ?>

												<option value="<?php echo $airline_list_k; ?>"><?php echo $airline_list_v; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="mobile_adult_icon">Travellers<i class="fa fa-male"></i></div>

							<div class="inallsn">
								<div class="oneroom fltravlr">
									<div class="lablform2">Travellers</div>
									<div class="clearfix"></div>
									<div class="roomrow">

										<div class="celroe col-7"><i class="fal fa-male"></i> Adults
											<span class="agemns">(12+)</span>
										</div>
										<div class="celroe col-5">
											<div class="input-group countmore pax-count-wrapper adult_count_div"> <span class="input-group">
													<button type="button" class="btn btn-secondary btn-number" data-type="minus" data-field="adult"> <span class="glyphicon glyphicon-minus"></span> </button>
												</span>
												<input type="text" id="OWT_adult" name="adult" class="form-control input-number centertext valid_class pax_count_value" value="<?= (int)@$flight_search_params['adult_config'] ?>" min="1" max="9" readonly>
												<span class="input-group">
													<button type="button" class="btn btn-secondary btn-number" data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button>
												</span>
											</div>
										</div>
									</div>
									<div class="roomrow">
										<div class="celroe col-7"><i class="fal fa-child"></i> Children
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
										<div class="celroe col-7"><i class="fal fa-child"></i> Infants
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
									<!-- Infant Error Message-->
									<div class="roomrow last">
										<div class="celroe col-12">
											<div class="alert-wrapper hide">
												<div role="alert" class="alert alert-error">
													<span class="alert-content"></span>
												</div>
											</div>
										</div>
									</div>
									<a class="done1 comnbtn_room1 hide"><span class="fa fa-check"></span> Done</a>
									<!-- Infant Error Message-->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="col-md-5 col-12 padfive last_border">
					<div class="lablform mobile_label">&nbsp;</div>
					<div class="searchsbmtfot flightbutton">
						<input type="submit" name="search_flight" id="flight-form-submit" class="searchsbmt flight_search_btn" value="Search Flight" />
					</div>
				</div> -->
				<div class="clearfix"></div>
				<div class="col-12">
					<button style="display:none" class="add_city_btn" id="add_city"> <span class="fa fa-plus"></span> Add City</button>
				</div>
			</div>
			<div class="col-md-5 col-12 last_border search_cutom_btn">
					<div class="lablform mobile_label">&nbsp;</div>
					<div class="searchsbmtfot flightbutton">
						<input type="submit" name="search_flight" id="flight-form-submit" class="searchsbmt flight_search_btn" value="Search Flight" />
					</div>
				</div>

			<div class="clearfix"></div>
			<div class="alert-box" id="flight-alert-box"></div>
			<div class="clearfix"></div>


		</div>

		<div class="col-md-7 nopad addm hide" id="extra_service">

			<div class="col-3 nopad hide">
				<div class="squaredThree">
					<input type="checkbox" class="airlinecheckbox triptype" id="squaredThree4" name="non_stop" value="1">
					<label for="squaredThree4"></label>
				</div>
				<label for="squaredThree4" class="lbllbl colwhite">Non Stop</label>
			</div>
			<div class="col-4 nopad hide">
				<div class="squaredThree">
					<input type="checkbox" class="airlinecheckbox triptype" id="squaredThree5" name="Refundable" value="1">
					<label for="squaredThree5"></label>
				</div>
				<label for="squaredThree5" class="lbllbl colwhite">Refundable Flight</label>

			</div>

		</div>
		<!--<div class="col-5 col-md-2 nopad float-end">-->
		<!--		<button class="farhomecal" id="flight_fare_calendar"><span class="fal fa-calendar-alt"></span> Fare Calendar</button>-->
		<!--	</div>-->
	</div>
	<div class="clearfix"></div>
	
</form>

<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_suggest.js'), 'defer' => 'defer');
?>
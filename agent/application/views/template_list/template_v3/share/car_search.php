<?php



	//Read cookie when user has not given any search



	if ((isset($car_search_params) == false) || (isset($car_search_params) == true && valid_array($car_search_params) == false)) {



		$sparam = $this->input->cookie('sparam', TRUE);



		$sparam = unserialize($sparam);



		$sid = intval(@$sparam[META_CAR_COURSE]);



		// echo $sid;exit;



		if ($sid > 0) {



			$car_search_params = $this->car_model->get_safe_search_data($sid, true);



			$car_search_params = $car_search_params['data'];



			// debug($car_search_params);exit;



			if (strtotime(@$car_search_params['bus_date_1']) < time()) {



				$bus_search_params['bus_date_1'] = date('d-m-Y', strtotime(add_days_to_date(3)));



			}



		}



	}


	Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/car_suggest.js'), 'defer' => 'defer');
	Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/car-search-form.css'), 'media' => 'screen');
	$car_datepicker = array(array('car_datepicker1', FUTURE_DATE_DISABLED_MONTH), array('car_datepicker2', FUTURE_DATE_DISABLED_MONTH));
	$this->current_page->set_datepicker($car_datepicker);
?>

<style>
	.car_form .squaredThree2 input[type="checkbox"] { display: none !important; }
</style>

<!--  novalidate="novalidate" -->



<form id="trasfer" name="car" autocomplete="off" action="<?=base_url()?>index.php/general/pre_car_search">

<div class="tabspl forbusonly car_form" id="car_form">
		<div class="clearfix"></div>
		<div class="tabrow">
		<div class="outsideserach custom_divclass">
			<div class="clearfix"></div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-12 nopad cr_plce">
			<div class="col-sm-12 col-12 bordrt padfive mobile_width" id="pick_up">
				<div class="marginbotom10">
					<span class="lablform">Pick-Up</span>
					<div class="relativemask plcemark"> 
                    <i class="material-icons field-icon field-icon-right">location_on</i>
                    <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
						<input type="text" value="<?php echo @$car_search_params['car_from'] ?>" placeholder="From, Airport, City" name="car_from" id="car_from" class="b-r-0 hotelin normalinput fromcar ui-autocomplete-input" required="" aria-required="true" autocomplete="off">
						<input class="hide loc_id_holder" id="car_from_loc_id" name="from_loc_id" type="hidden" value="<?=@@$car_search_params['from_loc_id']?>"  >
						<input class="hide loc_code_holder" id="car_from_loc_code" name="car_from_loc_code" type="hidden" value="<?=@@$car_search_params['car_from_loc_code']?>"  >
					</div>
				</div>
			</div>
			

			<div class="col-12 bordrt mobile_width padfive" id="Drop-of" style="display: none">
				<div class="marginbotom10">
					<span class="lablform">Drop-Off</span>
					<div class="relativemask plcemark">  <i class="material-icons field-icon field-icon-right">place</i> <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
						<input type="text" value="<?php echo @$car_search_params['car_to'] ?>" placeholder="To, Airport, City" id="car_to" name="car_to" class="b-r-0 departcar hotelin normalinput ui-autocomplete-input" aria-required="true" autocomplete="off">
						<input class="hide loc_id_holder" name="to_loc_id" id="car_to_loc_id" type="hidden" value="<?=@$car_search_params['to_loc_id']?>" >
						<input class="hide loc_code_holder" id="car_to_loc_code" name="car_to_loc_code" type="hidden" value="<?=@@$car_search_params['car_to_loc_code']?>"  >
					</div>
				</div>
			</div>
			</div>
			<?php
			if(@@$car_search_params['depature']!='')
			{
				$depature = date('d-m-Y', strtotime(@@$car_search_params['depature'])) ;
				$depature_time = date('H:i', strtotime(@@$car_search_params['depature_time'])) ;
			}else
			{
				$depature = '';
				$depature_time = '09:00';
			}

			if(@@$car_search_params['return']!='')
			{
				$return = date('d-m-Y', strtotime(@@$car_search_params['return'])) ;
				$return_time = date('H:i', strtotime(@@$car_search_params['return_time'])) ;
			}else
			{
				$return = '';
				$return_time = '09:00';
			}
			// $depature = $depature_time = $return = $return_time = '';
			?>			
			<div class="col-md-6 col-12 date_time_picker">
			<div class="col-md-6 col-12 date_time_picker_item">
				<div class="col-7 bordrt padfive me-2">					
						<span class="lablform">Pick-Up Date</span>
						<div class="relativemask datemark pkupdt"> <i class="material-icons field-icon field-icon-right">calendar_today</i>
							<input type="text" readonly placeholder="Pick-Up Date" value="<?php echo @$depature; ?>" class="b-r-0 normalinput date_picker normalinput"  id="car_datepicker1" name="depature" required/>
						</div>
				</div>
				<div class="col-5 bordrt padfive me-2">					
						<span class="lablform">Time</span>
						<div class="relativemask selctmark time_mark"> <i class="material-icons field-icon field-icon-right">schedule</i>
							<select name="depature_time" id="depature_time" class="normalsel timesel padselct dep_t arimo">
								<?php
								$start = "00:00";
								$end = "23:30";
								$tStart = strtotime($start);
								$tEnd = strtotime($end);
								$tNow = $tStart;
								while($tNow <= $tEnd){
									$time=date("H:i",$tNow);
									$selected = ($depature_time ==  $time) ? 'selected="selected"' : '' ;
									
									echo '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';
									$tNow = strtotime('+30 minutes',$tNow);
								}
								?>
							</select>
						</div>
				</div>
		 	  </div>
			  <div class="col-md-6 col-12 nopad date_time_picker_item">
				<div class="col-7 bordrt padfive me-2">
					
						<span class="lablform">Drop-Off Date</span>
						<div class="relativemask datemark retdt"> <i class="material-icons field-icon field-icon-right">event</i>
							<input type="text" readonly placeholder="Drop-Off Date" value="<?php echo @$return; ?>" class="b-r-0 normalinput date_picker normalinput" id="car_datepicker2" name="return" required/>
						</div>
				</div>
				<div class="col-5 bordrt padfive me-2">					
						<span class="lablform">Time</span>
						<div class="relativemask selctmark time_mark"> <i class="material-icons field-icon field-icon-right">schedule</i>
							<select name="return_time" id="return_time" class="normalsel timesel b-r-0 padselct dep_t arimo">
								<?php
								$start = "00:00";
								$end = "23:59";
								$tStart = strtotime($start);
								$tEnd = strtotime($end);
								$tNow = $tStart;
								while($tNow <= $tEnd){
									$time=date("H:i",$tNow);
									$selected = ($return_time ==  $time) ? 'selected="selected"' : '' ;

									echo '<option value="'.$time.'" '.$selected.'>'.$time.'</option>';
									$tNow = strtotime('+30 minutes',$tNow);
								}
								?>
							</select>
						</div>
				</div>
				</div>
				<div class="clearfix"></div>


				
			</div>
			<div class="col-md-2 col-12 srch_btn">
			<div class="searchsbmtfot flightbutton car_rental_btn">
				<input type="submit" name="search_flight" id="car-form-submit" class="searchsbmt comncolor flight_search_btn" value="Search Car" />
			</div>
			</div>

            
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="car_form_extra_options">
		
		<div class="car_form_extra_options_content">
			<div class="car_form_extra_options_content_inner">
				<div class="float-start diff_loc">
					<div class="squaredThree2">
					<input type="checkbox" name="diff_loc" id='diff_loc'>
					<label for="diff_loc"></label>
					</div>
					<label for="diff_loc" class="lbllbl">Drop-Off to a different location</label>
				</div>
					
				<div class="col-md-3 col-12 nopad marginbotom10 aftremarg driver-age">
					<div class="float-start remove_age d-flex gap-2" id="remove_age">
					 <div class="squaredThree2">
						<input type="checkbox" id='driver_age' checked="checked">
						<label for="driver_age"></label>
					</div>
					
					 <label for="driver_age" class="lbllbl">Driver's age between 30 - 65</label>
					</div>
					<div id='add_age' class="float-start" style="display: none;">
					<div class="" style="width: 249px;">
					<!-- <span class="formlabel">Driver's Age</span> -->
						<input type="text" name="driver_age" id="driver_age_in" placeholder=" " value="<?php if(empty($car_search_params['driver_age']) == false){ echo @$car_search_params['driver_age']; }else{echo 35; } ?>" class="input_form numeric normalinput"  maxlength="2" minlength="2"  name="age_1" />
					</div>
					</div>
				</div>

				<div class="col-md-3 col-12 border nationality_Sec">					
							<select name="country" id="country" class="normalsel car_nation b-r-0 padselct dep_t arimo">
								<?php $countrylist = $this->custom_db->single_table_records('api_country_list', '*');//debug($car_search_params);exit;
								foreach($countrylist['data'] as $country_code => $country){
								// debug($country);exit; 
								if(empty($country['iso_country_code']) == false){
									if($country['iso_country_code'] == $car_search_params['country']){
										$select = 'selected';
									}
									else{
										if($country['iso_country_code'] == 'IN'){
											$select = 'selected';
										}
										else{
											$select = '';
										}
										
									}

								
								?>
									<option value="<?php echo $country['iso_country_code']?>" <?php echo $select; ?>><?php echo $country['name']?></option>
								<?php } }
								
								//exit;?>
								
							</select>
					
				</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>

	$(function () {

        $("#diff_loc").click(function () {

            if ($(this).is(":checked")) {

                $("#Drop-of").show();

                $("#pick_up").removeClass("col-sm-12");

                $("#pick_up").addClass("col-sm-6");

                $("#Drop-of").addClass("col-sm-6");

            } else {

                $("#Drop-of").hide();

                 $("#pick_up").addClass("col-sm-12");

                $("#pick_up").removeClass("col-sm-6");

                $("#Drop-of").removeClass("col-sm-6");

            }

        });

    });

	var age_type = "<?php echo @$car_search_params['age_type']; ?>"



	if(age_type == true){



		$('#add_age').removeClass("hide");



	}else{



		$('#remove_age').removeClass("hide");



	}



	$('#cus_numeric').bind("keyup blur change focus", function() {



		if (this.value != '' || this.value != null) {



			$(this).val($(this).val().replace(/[^-?][^0-9.]/, ''));



		}



	});



	// $("input[type=checkbox]").change(function(){



	// 	$("#cus_numeric").show();



	// 	if ($(this).is(':not(:checked)')){



	// 		$( "#remove_age" ).remove();



	// 		$("#add_age").removeClass("hide");



	// 		$("#age_type").val(1)



	// 	}



	// });



	$( "#trasfer" ).submit(function( event ) {

		var age = $('#age_type').val();



		var age_val = $('#cus_numeric').val()



		if((age == 1) && (isNaN(age_val) == false) && (age_val >= 16)){



		  }else{



		  	if(age == 1){



		  		alert("Enter age (Minimum driver's age is 16) ");



		  		event.preventDefault();



		  	}



		}	



	});



	$(function () {

        $("#driver_age").click(function () {

            if ($(this).is(":not(:checked)")) {

                $("#add_age").show();

            } else {

                $("#add_age").hide();

            }

            // $(".remove_age").hide();

        });

    });



</script>


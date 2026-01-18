<script type="text/javascript">
    $("#show_inbound").click(function () {
        $("#t-w-i-1").show();

        $("#t-w-i-2").hide();

    });

    $("#show_outbound").click(function () {
        $("#t-w-i-2").show();

        $("#t-w-i-1").hide();

    });

	

	$(".dom_tab_div a").click(function(){

		$(".dom_tab_div a").removeClass("active");

		$(this).addClass("active");

	});

</script>

<?php
// debug($cur_SegmentDetails);exit;
$template_images = $GLOBALS['CI']->template->template_images();
$journey_summary = $raw_flight_list['JourneySummary'];
$IsDomestic = $journey_summary['IsDomestic'];
$flights_data = $raw_flight_list['Flights'];
$mini_loading_image = '<div class="text-center loader-image">Please Wait</div>';
//Dividing cols
$col_parent_division = '';
$special_class = '';
$col_division = '';
$add_filter_date = $alt_days && in_array($trip_type,['oneway','circle']) ? 1:0;

if ($route_count == 1) {

	$col_division = 'rondnone';
	$special_class = "one_way_only";
	//check if not

	if ($trip_type != 'oneway') {

		$col_parent_division = 'round-trip';

		$arrowclass = 'fa fa-exchange';

	}$div_dom ='';

} elseif ($route_count == 2) {

	$col_division = 'rondnone';

	$col_parent_division = 'round-domestk';

	$div_dom =  '<div class="dom_tab"><div class="dom_tab_div"> <a href="#" class="active" id="show_inbound">'.$journey_summary["Origin"].' to '.$journey_summary["Destination"].'</a> <a href="#" id="show_outbound">'.$journey_summary["Destination"].' to '.$journey_summary["Origin"].'</a>

	             </div></div>';

}



$loc_dir_icon = '<div class="arocl fas fa-long-arrow-alt-right"></div>';

//Change booking button based on type of flight

if ($domestic_round_way_flight) {

	$booking_button = '<button class="bookallbtn mfb-btn" type="button">Book</button>';//multi flight booking

} else {

	$booking_button = '<button class="b-btn bookallbtn" type="submit">Book Now</button>';

}

$flights = '<div class="row '.$col_parent_division.'">'.$div_dom;

$__root_indicator = 0;

foreach ($flights_data as $__tirp_indicator => $__trip_flights) {

	$__root_indicator++;

	$flights .= '<div class="'.$col_division.' r-w-g nopad '.$special_class.'" id="t-w-i-'.$__root_indicator.'">';

	foreach ($__trip_flights as $__trip_flight_k => $__trip_flight_v) {

		// if($__trip_flight_v['SegmentSummary'][0]['TotalStops'] > 0){

		// 	$stop_air = $__trip_flight_v['SegmentDetails'][0][0]['DestinationDetails']['AirportCode'];

		// 	$stop_air1 ='<div class="city_code1">'.$stop_air.'</div>';

		// }

		// else{

		// 	$stop_air1 = '';

		// }

		$cur_ProvabAuthKey = $__trip_flight_v['ProvabAuthKey'];

                

		$cur_AirlineRemark = trim($__trip_flight_v['AirlineRemark']);

		$remark_separator = empty($cur_AirlineRemark) == false ? '| ' : '';

		$cur_FareDetails = $__trip_flight_v['FareDetails']['b2b_PriceDetails'];

		$cur_SegmentDetails = $__trip_flight_v['SegmentDetails'];

		$cur_SegmentSummary = $__trip_flight_v['SegmentSummary'];

		$cur_IsRefundable = $__trip_flight_v['Attr']['IsRefundable'];
		//$booking_source = $__trip_flight_v['booking_source'];
		//Reset This Everytime

		$inner_summary = $outer_summary = '';

		$cur_Origin					= $journey_summary['Origin'];

		$cur_Destination			= $journey_summary['Destination'];

		$Refundable_lab = ($cur_IsRefundable == false ? 'Non-Refundable' : 'Refundable');

		if(isset($__trip_flight_v['Attr']['FareType'])){

            if(empty($__trip_flight_v['Attr']['AirlineRemark']) == false){

                $cur_AirlineRemark = $__trip_flight_v['Attr']['AirlineRemark'];

            }

            else{

                $cur_AirlineRemark = $__trip_flight_v['Attr']['FareType'];

            }

            

        }
		$avil_seats = '';
        $moreas = '';
            if(isset($__trip_flight_v['SegmentDetails'][0][0]['Baggage']) && empty($__trip_flight_v['SegmentDetails'][0][0]['Baggage']) == false){
                $abaggage = $__trip_flight_v['SegmentDetails'][0][0]['Baggage'];
                $avil_seats .= '<div class="termnl1 flo_w"><em><i class="fa fa-suitcase bag_icon"></i>' . $abaggage . '</em></div>';
            }
            if(isset($__trip_flight_v['SegmentDetails'][0][0]['AvailableSeats']) && empty($__trip_flight_v['SegmentDetails'][0][0]['AvailableSeats']) == false){
                $aSeats = $__trip_flight_v['SegmentDetails'][0][0]['AvailableSeats'];
                $avil_seats.= '<div class="termnl1 flo_w"><em><i class="air_seat timings icseats" ></i>' . $aSeats . '</em></div>';
            }
            if(count($__trip_flight_v['SegmentDetails'][0]) > 1 && ( empty($__trip_flight_v['SegmentDetails'][0][0]['Baggage']) == false || empty($__trip_flight_v['SegmentDetails'][0][0]['AvailableSeats']) == false)){
                $moreas = '<span class="mfd"> ... More Info Click on Flight Details</span>';
            }

		//Price Details

		$cus_total_fare = $cur_FareDetails['_CustomerBuying'];

		$agent_buying_fare = $cur_FareDetails['_AgentBuying'];

		$agent_markup = $cur_FareDetails['_Markup'];

		$agent_commission = $cur_FareDetails['_Commission'];

		$tds_oncommission = $cur_FareDetails['_tdsCommission'];

		$agent_earning = $cur_FareDetails['_AgentEarning'];

		$cus_total_tax = $cur_FareDetails['_TaxSum'];

		$cus_base_fare = $cur_FareDetails['_BaseFare'];
		$price_single = $cus_total_fare;
		//$price_single = $__trip_flight_v['PassengerFareBreakdown']['ADT']['total_price_markup'];
		//$price_single = $__trip_flight_v['PassengerFareBreakdown']['ADT']['final_fare'];
		$cur_Currency				= $cur_FareDetails['CurrencySymbol'];
		$crs_array = array(PROVAB_FLIGHT_BOOKING_SOURCE=>'tmx',CLARITYNDC_FLIGHT_BOOKING_SOURCE=>'ndc');
        $crs = isset($crs_array[$__trip_flight_v['booking_source']]) ? $crs_array[$__trip_flight_v['booking_source']] : '';
		

		

		//VIEW START

		//SegmentIndicator used to identifies one way or return or multi stop

		// $inner_summary .= '<div class="propopum" id="fdp_'.$__root_indicator.$__trip_flight_k.'">';

		// $inner_summary .= '<div class="comn_close_pop closepopup">X</div>';

		// $inner_summary .= '<div class="p_i_w">';

		// $inner_summary .= '<div class="popuphed"><div class="hdngpops">'.$cur_Origin.' <span class="fas fa-exchange-alt"></span> '.$cur_Destination.' </div></div>';

		// $inner_summary .= '<div class="popconyent">';

		// $inner_summary .= '<div class="contfare">';



		$inner_summary .= '<div class="propopum" id="fdp_' . $crs.$__root_indicator . $__trip_flight_k . '">';

        $inner_summary .= '<div class="comn_close_pop closepopup">X</div>';

        $inner_summary .= '<div class="p_i_w">';

        $stop_air_layover = array();

        foreach ($cur_SegmentDetails as $__segment_k => $__segment_v) {

        	if ($__trip_flight_v['SegmentSummary'][0]['TotalStops'] > 0) {
             	
                $stop_air_layover[] = $__segment_v[0]['DestinationDetails']['AirportName'];
            }

            $segment_summary = $cur_SegmentSummary[$__segment_k];

            $inner_summary .= '<div class="popuphed"><div class="hdngpops">' . $segment_summary['OriginDetails']['AirportCode'] . ' <span class="fa fa-arrow-right"></span> ' . $segment_summary['DestinationDetails']['AirportCode'] . ' </div></div>';

        }

        $inner_summary .= '<div class="popconyent">';

        $inner_summary .= '<div class="contfare">';





		

		$inner_summary .= '

			<ul role="tablist" class="nav nav-tabs flittwifil">

				<li class="active" data-role="presentation"><a data-bs-toggle="tab" data-role="tab" href="#iti_det_'.$crs.$__root_indicator.$__trip_flight_k.'">Itinerary</a></li>

				<li data-role="presentation"><a data-bs-toggle="tab" data-form-id="form-id-'.$crs.$__root_indicator.$__trip_flight_k.'" class="iti-fare-btn" data-role="tab" href="#fare_det_'.$crs.$__root_indicator.$__trip_flight_k.'">Fare Details</a></li>

				<li data-role="presentation"><a data-bs-toggle="tab" class="iti-fare-btn" data-role="tab" href="#baggage_inclusions_'.$crs . $__root_indicator . $__trip_flight_k . '">Baggage & Inclusions</a></li>

			</ul>

		';

		

		$inner_summary .= '<div class="tab-content">';

		$inner_summary .= '<div id="fare_det_'.$crs.$__root_indicator.$__trip_flight_k.'" class="tab-pane Fare_Rules i-i-f-s-t' . add_special_class('xs-font', '', $domestic_round_way_flight) . '">';

		$inner_summary .= $mini_loading_image;

		$inner_summary .= '<div class="i-s-s-c tabmarg"></div>';

		$inner_summary .= '</div>';

		$inner_summary .= '<div id="iti_det_'.$crs.$__root_indicator.$__trip_flight_k.'" class="tab-pane active i-i-s-t ' . add_special_class('xs-font', '', $domestic_round_way_flight) . '">';

		$inner_summary .= '<div class="tabmarg">';//summary wrapper start

		$inner_summary .= '<div class="alltwobnd">';

		$inner_summary .= '<div class="col-8 nopad full_wher">';//airline summary start

		foreach ($cur_SegmentDetails as $__segment_k => $__segment_v) {

			$segment_summary = $cur_SegmentSummary[$__segment_k];

			$inner_summary .= '<div class="inboundiv seg-'.$__segment_k.'">';

				//Way Summary in one line - Start

				$inner_summary .= '<div class="hedtowr">';

				$inner_summary .= $segment_summary['OriginDetails']['CityName'].' to '.$segment_summary['DestinationDetails']['CityName'].' <strong> ('.$segment_summary['TotalDuaration'].')</strong>';

				$inner_summary .= '</div>';

			//Way Summary in one line - End

			foreach ($__segment_v as $__stop => $__segment_flight) {

				$Baggage = trim($__segment_flight['Baggage']);

				$AvailableSeats = isset($__segment_flight['AvailableSeats']) ? $__segment_flight['AvailableSeats'].' seats' : '';
				$rbd = isset($__segment_flight['RBD']) ? 'RBD: '.$__segment_flight['RBD']: '';
                $CabinType = isset($__segment_flight['CabinType']) ? 'CabinType: '.$__segment_flight['CabinType']: '';
				//Summary of Way - Start

				$inner_summary .= '<div class="flitone">';

					//airline

					$inner_summary .= '<div class="col-3 nopad5">

										<div class="imagesmflt">

										<img  alt="'.$__segment_flight['AirlineDetails']['AirlineCode'].' icon" src="'.SYSTEM_IMAGE_DIR.'airline_logo/'.$__segment_flight['AirlineDetails']['AirlineCode'].'.svg" >

										</div>

										<div class="flitsmdets">'.$__segment_flight['AirlineDetails']['AirlineName'].'<strong>'.$__segment_flight['AirlineDetails']['AirlineCode'].' '.$__segment_flight['AirlineDetails']['FlightNumber'].'</strong></div>

										</div>';

					//Between Content -----

					//depart
					$originStartDateTime = $__segment_flight['OriginDetails']['_Date'] . ' ' . date('H:i:s', strtotime($__segment_flight['OriginDetails']['_DateTime']));
					$formattedStartDateTime = date('D, d M y, H:i', strtotime($originStartDateTime));  
					$inner_summary .= '<div class="col-7 nopad5">';

					$inner_summary .= '<div class="col-5 nopad5">

										<div class="dateone">'.$formattedStartDateTime.'</div>

										<div class="termnl">'.$__segment_flight['OriginDetails']['CityName'].' ('.$__segment_flight['OriginDetails']['AirportCode'].')</div>

										</div>';

					//direction indicator

					$inner_summary .= '<div class="col-2 nopad">

					'.$loc_dir_icon.'</div>';

					//arrival
					$originEndDateTime = $__segment_flight['DestinationDetails']['_Date'] . ' ' . date('H:i:s', strtotime($__segment_flight['DestinationDetails']['_DateTime']));
					$formattedEndDateTime = date('D, d M y, H:i', strtotime($originEndDateTime));
					$inner_summary .= '<div class="col-5 nopad5">

										<div class="dateone">'.$formattedEndDateTime.'</div>

										<div class="termnl">'.$__segment_flight['DestinationDetails']['CityName'].' ('.$__segment_flight['DestinationDetails']['AirportCode'].')</div>

										</div>';

					$inner_summary .= '</div>';

					//Between Content -----

					$inner_summary .= '<div class="col-2 nopad5">

										<div class="ritstop">

										<div class="termnl">'.$__segment_flight['SegmentDuration'].'</div>

										<div class="termnl1">Stop : '.($__stop).'</div>';

					/*if(empty($Baggage) == false){

						$inner_summary .= '<div class="termnl1 flo_w"><em><i class="fas fa-suitcase bag_icon"></i>'.($Baggage).'</em></div>';

					}

					if(empty($AvailableSeats) == false){

						$inner_summary .= '<div class="termnl1 flo_w"><em><i class="air_seat timings icseats" ></i>'.$AvailableSeats.'</em></div>';

					}
					if (empty($rbd) == false) {
						$inner_summary .= '<div class="termnl1 flo_w"><em>' . $rbd . '</em></div>';
					}
					if (empty($CabinType) == false) {
						$inner_summary .= '<div class="termnl1 flo_w"><em>' . $CabinType . '</em></div>';
					}*/

					$inner_summary .= '</div>

										</div>';



				//Summary of Way - End

				$inner_summary .= '</div>';
				if(empty($Baggage) == false){

					$inner_summary .= '<div class="termnl1 flo_w"><em><i class="fas fa-suitcase bag_icon"></i>'.($Baggage).'</em></div>';

				}

				if(empty($AvailableSeats) == false){

					$inner_summary .= '<div class="termnl1 flo_w"><em><i class="air_seat timings icseats" ></i>'.$AvailableSeats.'</em></div>';

				}
				if (empty($rbd) == false) {
					$inner_summary .= '<div class="termnl1 flo_w"><em>' . $rbd . '</em></div>';
				}
				if (empty($CabinType) == false) {
					$inner_summary .= '<div class="termnl1 flo_w"><em>' . $CabinType . '</em></div>';
				}
				/*if (isset($__segment_v['WaitingTime']) == true) {

					$next_seg_info = $__segment_v[$seg_details_k+1];

					$waiting_time = $__segment_v['WaitingTime'];*/
				if (isset($__segment_flight['WaitingTime']) == true) {
                    $next_seg_info = $__segment_v[$__stop+1];
                    $waiting_time = $__segment_flight['WaitingTime'];

					$inner_summary .= '

				<div class="clearfix"></div>

				<div class="layoverdiv">

					<div class="centovr">

					<span class="fas fa-plane"></span> Plane change at '.$next_seg_info['OriginDetails']['CityName'].' | <span class="far fa-clock"></span> Waiting: '.$waiting_time.'

				</div></div>

				<div class="clearfix"></div>';

				}

			}

			$inner_summary .= '</div>';

		}

				$inner_summary .= '</div>';//airline summary end

				$inner_summary .= '<div class="col-4 nopad full_wher">';//price summary start

				$inner_summary .= '<div class="inboundiv sidefare">';



				$inner_summary .= '<h4 class="farehdng">Total Fare Breakup</h4>';



				$inner_summary .= '<div class="inboundivinr">';

				$inner_summary .= '

						<div class="rowfare"><div class="col-8 nopad">

						<span class="infolbl">Total Base Fare</span>

						</div>

						<div class="col-4 nopad">

						<span class="pricelbl">'.$cur_Currency.' '.roundoff_number($cus_base_fare).'</span>

						</div></div>';

				$inner_summary .= '

						<div class="rowfare"><div class="col-8 nopad">

						<span class="infolbl">Taxes &amp; Fees</span>

						</div>

						<div class="col-4 nopad">

						<span class="pricelbl">'.$cur_Currency.' '.roundoff_number($cus_total_tax).'</span>

						</div></div>';

				$inner_summary .= '

						<div class="rowfare grandtl"><div class="col-8 nopad">

						<span class="infolbl">Grand Total</span>

						</div>

						<div class="col-4 nopad">

						<span class="pricelbl">'.$cur_Currency.' '.roundoff_number($cus_total_fare).'</span>

						</div></div>';

				$inner_summary .= '</div>';

				$inner_summary .= '</div>';



				$inner_summary .= '</div>';//price summary end

			$inner_summary .= '</div>';//summary wrapper end

		$inner_summary .= '</div>';

		$inner_summary .= '</div>';


		$inner_summary .= '<div id="baggage_inclusions_'.$crs. $__root_indicator . $__trip_flight_k . '" class="baggage_inclusions tab-pane i-i-s-t ' . add_special_class('xs-font', '', $domestic_round_way_flight) . '">';

        $inner_summary .= '<div class="col-12 nopad full_wher">'; //baggage inclusions start
        $inner_summary .= '<div class="inboundiv sidefare">';
        
        foreach ($cur_SegmentDetails as $__segment_k => $__segment_v) {
            foreach ($__segment_v as $__stop => $__segment_flight1) {
                $Baggage1 = trim($__segment_flight1['Baggage']);
                $CabinBaggage = trim($__segment_flight1['CabinBaggage']);
                $inner_summary .= '<div class="baggage_dtls_wrapper">';
                $inner_summary .= '<div class="baggage_hdng">
										<span class="baggage_deprt_dtls">
											<span class="baggage_dprt">DEPART</span>
											<span class="baggage_dprt_date">' . date('D, d M y' ,strtotime($__segment_flight1['DestinationDetails']['_Date'])) . '</span>
										</span>
										<span class="baggage_dest">'. $__segment_flight1['OriginDetails']['AirportCode'].' <span><i class="fal fa-long-arrow-right"></i></span>'. $__segment_flight1['DestinationDetails']['AirportCode'].'</span>
                                	</div>';
                $inner_summary .= '<div class="baggage_dtls">
										<div class="baggage_img">
											<img src="'.$GLOBALS['CI']->template->template_images('trolley.png').'">
										</div>
										<div class="baggage_chckn_cbin">
											<h4>Check-in</h4>';
											if (empty($Baggage1) == false) {
												$inner_summary .= '<p>Adult: '.$Baggage1.'</p>';
											}else{
												$inner_summary .= '<p>Adult: No baggage</p>';
											}

											if (empty($CabinBaggage) == false) {
												$inner_summary .= '<h4>Cabin</h4>
															<p>Adult : '.$CabinBaggage.'</p>';
											}else{
												$inner_summary .= '<h4>Cabin</h4>
																	<p>Adult : No baggage</p>';
											}
				$inner_summary .= ' </div>
									</div>';
                $inner_summary .= '<div class="baggage_inclusn_pax">
                                        <div class="baggage_inclusn_dtls">
                                            <h6>Inclusions</h6>
                                            <p>Adult: </p>
                                        </div>
                                        <div class="baggage_per_pax">
                                            <i class="fal fa-check"></i>
                                            <p>Hand Bag</p>
                                        </div>
                                	</div>';
                $inner_summary .= '</div>';
            }
        }
        $inner_summary .= '</div>';

        $inner_summary .= '</div>'; //baggage inclusions end

        $inner_summary .= '</div>';


		$inner_summary .= '</div>';//tab-content



		$inner_summary .= '</div>';//contfare

		$inner_summary .= '</div>';//popconyent



		$inner_summary .= '<div class="popfooter"><div class="futrcnt"><button class="norpopbtn closepopup">Close</button>  </div></div>';

		$inner_summary .= '</div>';//inned wrap

		$inner_summary .= '</div>';//propopum



		//Outer Summary - START

		//$outer_summary .= '<div class="madgrid ' . add_special_class('', '', $domestic_round_way_flight) . '">';

		$outer_summary .= '<div class="madgrid" data-key="'.$__root_indicator.$__trip_flight_k.'">

		<div class="onlmob"><form method="POST" id="form-id-'.$crs.$__root_indicator.$__trip_flight_k.'" action="'.$booking_url.'" class="book-form-wrapper">

									'.$GLOBALS['CI']->flight_lib->booking_form($IsDomestic, $__trip_flight_v['Token'], $__trip_flight_v['TokenKey'], $cur_ProvabAuthKey, $booking_source, $session_id, $ShoppingResponseId).'

									'.$booking_button.'

								</form></div>';

		$outer_summary .= '<div class="f-s-d-w col-8 nopad wayeght full_same">';

			$total_stop_count = 0;

			$min_total_stop_count = [];
			$dept_date = '';
        	$segmentCount = count($cur_SegmentSummary);
			foreach ($cur_SegmentSummary as $__segment_k => $__segment_v) {
				$add_date_filter = ($add_filter_date && ($segmentCount === 1 || ($segmentCount === 2 && $__segment_k === 1))) ? 1 : 0;
				$dept_date = ($dept_date ? $dept_date .'*':'').$__segment_v['OriginDetails']['_Date'];
				$stop_air1 = '';
				$stop_air_codes = [];
				$layOvers = [];

				if ($__segment_v['TotalStops'] > 0) {
					$total_segments = count($cur_SegmentDetails[$__segment_k]);

					foreach ($cur_SegmentDetails[$__segment_k] as $index => $segment) {
						if ($index < $total_segments - 1) {
							$stop_air_codes[] = $segment['DestinationDetails']['AirportCode'] . ' - '.$cur_SegmentDetails[$__segment_k][$index+1]['AirlineDetails']['AirlineName'] . ' ' . $cur_SegmentDetails[$__segment_k][$index+1]['AirlineDetails']['AirlineCode'].' '. $cur_SegmentDetails[$__segment_k][$index+1]['AirlineDetails']['FlightNumber'].' <b>(' . $segment['WaitingTime'].')</b>';
							if (strpos($segment['WaitingTime'], 'h') == false) {
								$wTiming = '0h '.$segment['WaitingTime'];
							}else{
								$wTiming = $segment['WaitingTime'];
							}
							$wtim = str_replace(['h ', 'm', 'h'], ['.', '', ''], trim($wTiming));
                        	$wh = explode('.',$wtim)[0]; 
							$layOvers[] = '<span class="hide layover-duration" data-layoverduration="'.$wh.'" data-layoverdurationhm="' . $wtim.'"></span>';
						}
					}


					$min_total_stop_count[] = count($stop_air_codes);

					$stop_air1 = '<div class="city_code1 hide">' . implode(' <br/> ', $stop_air_codes) . '</div>';
				}else{
					$layOvers[] = '<span class="hide layover-duration" data-layoverduration="0" data-layoverdurationhm="0"></span>';
				}

				$total_segment_travel_duration = $__segment_v['TotalDuaration'];

				$dur = $total_segment_travel_duration;

				$dur = explode(' ', $dur);

				$count = count($dur);

				//print_r($count);

				if (!empty($dur[1])) {

								$h = str_replace('h', '', $dur[0]);

								$m = str_replace('m', '', $dur[1]);



								$m = $m * 1;

								$h = $h * 60;

								$d = $h + $m;

							} else {

								$h = str_replace('h', '', $dur[0]);

								$h = str_replace('m', '', $h);

								$check = (strrpos($dur[0], 'm')) ? $h * 1 : $h * 60;

								$d = $check;

							}

							

							$duration = $d;

								$__stop_count = $__segment_v['TotalStops'];

								$total_stop_count	+= $__stop_count;

								$stop_image ='';

								for ($image_name=0; $image_name <5 ; $image_name++) { 

									if($__stop_count==$image_name){

										$stop_image =$GLOBALS['CI']->template->template_images('stop_'.$image_name.'.png');

									}

							

								}

								if($__stop_count>4){

									$stop_image =$GLOBALS['CI']->template->template_images('more_stop.png');

								}



								$outer_summary .= '<div class="allsegments outer-segment-'.$__segment_k.'">';

									//airline

									$outer_summary .= '<div class="quarter_wdth nopad ' . add_special_class('col-3', 'col-3', $domestic_round_way_flight) . '">

														<div class="fligthsmll"><img class="airline-logo" alt="'.$__segment_v['AirlineDetails']['AirlineCode'].' icon" src="'.SYSTEM_IMAGE_DIR.'airline_logo/'.$__segment_v['AirlineDetails']['AirlineCode'].'.svg"></div>

														<div class="m-b-0 text-center">

															<div class="a-n airlinename" data-code="'.$__segment_v['AirlineDetails']['AirlineCode'].'">

																'.$__segment_v['AirlineDetails']['AirlineName'].'

															</div>

															<strong> '.$__segment_v['AirlineDetails']['AirlineCode'].' '.$__segment_v['AirlineDetails']['FlightNumber'].'</strong>

														</div>

													</div>';

									//depart

									$outer_summary .= '<div class="col-3 nopad quarter_wdth">

															<div class="insidesame">

																<span class="fdtv hide">'.date('Hi', strtotime($__segment_v['OriginDetails']['DateTime'])).'</span>

																<div class="f-d-t bigtimef">'.$__segment_v['OriginDetails']['_DateTime'].'</div>

                                                				<span '.($add_date_filter ? 'class="dept_dt flt_date" data-date="'.$dept_date.'"' : 'class="flt_date"').'>' . date('D, d M y', strtotime($__segment_v['OriginDetails']['_Date'])) . '</span>

																<div class="from-loc smalairport_code">'.$__segment_v['OriginDetails']['AirportCode'].'</div>

																<div class="from-loc smalairport">'.$__segment_v['OriginDetails']['CityName'].' ('.$__segment_v['OriginDetails']['AirportCode'].')</div>

																<span class="dep_dt hide" data-category="'.time_filter_category($__segment_v['OriginDetails']['DateTime']).'" data-datetime="'.(number_format((strtotime($__segment_v['OriginDetails']['DateTime'])*1000), 0, null, '')).'"></span>

															</div>

														</div>';

									//direction indicator

									//$outer_summary .= '<div class="clearfix visible-sm-block"></div>';

														if (preg_match('/(\d+)h (\d+)m/', $total_segment_travel_duration, $matches)) {
					// Match hours and minutes
					$hours = (int)$matches[1];
					$minutes = (int)$matches[2];
				} elseif (preg_match('/(\d+)h/', $total_segment_travel_duration, $matches)) {
					// Match only hours
					$hours = (int)$matches[1];
					$minutes = 0;
				} elseif (preg_match('/(\d+)m/', $total_segment_travel_duration, $matches)) {
					// Match only minutes
					$hours = 0;
					$minutes = (int)$matches[1];
				} else {
					// Default value if no match found
					$hours = 0;
					$minutes = 0;
				}

				$hm = sprintf("%d.%02d", $hours, $minutes);

				$outer_summary .= '<div class="col-md-1 p-tb-10 hide">'.$loc_dir_icon.'</div>';

				//arrival

				$outer_summary .= '<div class="smal_udayp nopad ' . add_special_class('col-3', 'col-3', $domestic_round_way_flight) . '"><span class="f-d hide">'.$duration.'</span>

								<div class="insidesame">

									<div class="durtntime">'.($total_segment_travel_duration).'</div>

									<div class="stop_image"><img src='.$stop_image.' alt="stop_0"></div>

									<div class="stop-value">Stop:'.($__stop_count).'</div>'.$stop_air1.

									'<span class="slider_stop" data-slider_stop="'.($__stop_count).'"></span>
									'.implode(' ',$layOvers) /*.'
									<!--span class="hide layover-duration" data-layoverduration="'.($hours).'" data-layoverdurationhm="'.($hm).'"></span-->*/.'
									<span class="hide org-airport" data-airport="'.($__segment_v['OriginDetails']['AirportCode']).'"></span>
									<span class="hide dst-airport" data-airport="'.($__segment_flight['DestinationDetails']['AirportCode']).'"></span>
									<span class="hide layover-airport" data-airport="'.($stop_air_layover[$__segment_k]).'"></span>
									<div class="cabinclass hide">'.($cabin_class).'</div>

								</div>

					</div>';



					$outer_summary .= '<div class="col-3 nopad quarter_wdth">

											<div class="insidesame">

												<span class="fatv hide">'.date('Hi', strtotime($__segment_v['DestinationDetails']['DateTime'])).'</span>

												<div class="f-a-t bigtimef">'.$__segment_v['DestinationDetails']['_DateTime'].'</div>

                                                <span>' . date('D, d M y', strtotime($__segment_v['DestinationDetails']['_Date'])) . '</span>

												<div class="to-loc smalairport">'.$__segment_v['DestinationDetails']['CityName'].' ('.$__segment_v['DestinationDetails']['AirportCode'].')</div>

												<div class="smalairport_code">'.$__segment_v['DestinationDetails']['AirportCode'].'</div>

												<span class="arr_dt hide" data-category="'.time_filter_category($__segment_v['DestinationDetails']['DateTime']).'" data-datetime="'.(number_format((strtotime($__segment_v['DestinationDetails']['DateTime'])*1000), 0, null, '')).'"></span>

											</div>

										</div>';

					//$outer_summary .= '<div class="clearfix visible-sm-block"></div>';				

					//$outer_summary .= '<div class="clearfix visible-sm-block"></div>';

					$outer_summary .= '</div>';

			}

		$outer_summary .= '</div>';
		$stps_val = !empty($min_total_stop_count) ? (count(array_unique($min_total_stop_count)) == 1 ? stop_filter_category(min($min_total_stop_count)) : (stop_filter_category(min($min_total_stop_count))+0.5)) : 1;
		$min_total_stop_count = (!empty($min_total_stop_count)) ? (count(array_unique($min_total_stop_count)) == 1 ? array_unique($min_total_stop_count) : []) : [0];
		//$min_total_stop_count = (!empty($min_total_stop_count)) ? $min_total_stop_count : [0];

		if($cur_IsRefundable==0){$ref_code= "nre";}else{$ref_code= "re";}
		$t_price = explode('.',$cus_total_fare);
		$ref = (intval($cur_IsRefundable) == 1 )? 'refndble': 'nre';
		$outer_summary .= '

					<div class="col-4 nopad wayfour full_same">

						<span class="hide stp stps" data-stp="'.$total_stop_count.'" data-category="'.(count(array_unique($min_total_stop_count)) == 1 ? stop_filter_category(min($min_total_stop_count))  : '').'">'.$stps_val.'</span>

						<div class="priceanbook">

							<div class="col-6 nopad wayprice">

								<div class="insidesame">

									<div class="priceflights"><strong> '.$cur_Currency.' </strong><span class="f-p">'.$price_single.'</span></div>

									<div style="display:none" class="snf_hnf net-fare-tag" title="C '.($agent_commission-$tds_oncommission).'+M '.$agent_markup.' = '.$agent_earning.'"> '.$cur_Currency.' '.$agent_buying_fare.' </div>

									<span class="hide price" data-price="'.$price_single.'" data-currency="'.$cur_Currency.'"></span>

									<div data-val="' . intval($cur_IsRefundable) . '" data-code="' .$ref_code. '" class="n-r n-r-t '.$ref.'">' . $Refundable_lab . '</div>

								</div>

							</div>

							<div class="col-6 nopad waybook">

								<div class="form-wrapper bookbtlfrt">

								<form method="POST" id="form-id-'.$__root_indicator.$__trip_flight_k.'" action="'.$booking_url.'" class="book-form-wrapper">

									'.$GLOBALS['CI']->flight_lib->booking_form($IsDomestic, $__trip_flight_v['Token'], $__trip_flight_v['TokenKey'], $cur_ProvabAuthKey, $booking_source, @$session_id, @$ShoppingResponseId).'

									'.$booking_button.'

								</form>



								</div>
							</div>
						</div>
						'.($total_pax > 1 ? '<div class="col-12 trvlrs_amt_wrappr nopad"><div class="text-center trvlrs_amt">'.$cur_Currency .' <span class="travl_amt_lrg">'. $t_price[0].'</span>.<span class="travl_amt_sml">'.$t_price[1].'</span> <span class="all_trvlrs">for all travellers</span></div></div>':'').'

					</div>';

		$outer_summary .= '<div class="clearfix"></div>';

		//Load Flight Details Button

		$outer_summary .= '<div class="mrinfrmtn">

									<a class="detailsflt iti-btn" data-id="fdp_'.$crs.$__root_indicator.$__trip_flight_k.'"> Flight Details '.$remark_separator.'</a>

									<i class="hide">'.$cur_AirlineRemark.'</i> <i class="fset-bag">' . $avil_seats . $moreas. '</i>

									<a class="detailsflt iti-btn" data-bs-toggle="modal" data-bs-target="#sendmail_multi_' .$crs.$__root_indicator.$__trip_flight_k. '" data-backdrop="static"><span class="fal fa-envelope"></span>Send Mail</a>

							</div>';

		//Outer Summary - END

		$outer_summary .= '</div>';



		$flights .= '<div class="rowresult p-0 r-r-i t-w-i-'.$__root_indicator.'">

						'.$outer_summary.'

						'.$inner_summary.'

					</div>';

	}

	$flights .= '</div>';

}

$flights .= '</div>';

echo $flights;



/**

 * Return class based on type of page

 */

function add_special_class($col_2x_class, $col_1x_class, $domestic_round_way_flight)

{

	if ($domestic_round_way_flight) {

		return $col_2x_class;

	} else {

		return $col_1x_class;

	}

}



function time_filter_category($time_value)

{

	$category = 1;

	$time_offset = intval(date('H', strtotime($time_value)));

	if ($time_offset < 6) {

		$category = 1;

	} elseif ($time_offset < 12) {

		$category = 2;

	} elseif ($time_offset < 18) {

		$category = 3;

	} else {

		$category = 4;

	}

	return $category;

}

/**

 * Generate Category For Stop

 */

function stop_filter_category($stop_count)

{

	$category = 1;

	switch (intval($stop_count)) {

		case 0 : $category = 1;

		break;

		case 1 : $category = 2;

		break;

		default : $category = 3;

		break;

	}

	return $category;

}


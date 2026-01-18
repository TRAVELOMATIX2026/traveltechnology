<?php
include_once 'process_tbo_response.php';

//debug($state_list); exit;
$template_images = $GLOBALS['CI']->template->template_images();
$FareDetails = $pre_booking_summery['FareDetails']['b2b_PriceDetails'];
$PassengerFareBreakdown = $pre_booking_summery['PassengerFareBreakdown'];
$SegmentDetails = $pre_booking_summery['SegmentDetails'];
$SegmentSummary = $pre_booking_summery['SegmentSummary'];
$hold_ticket = $pre_booking_summery['HoldTicket'];
//Total Fare
$flight_total_amount = $FareDetails['_CustomerBuying'];
$currency_symbol = $FareDetails['CurrencySymbol'];
//Segment Details
$flight_segment_details = flight_segment_details($SegmentDetails, $SegmentSummary);

$is_domestic = (bool) filter_var($pre_booking_params['is_domestic'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
if ($is_domestic != true) {
    $pass_mand = '<sup class="text-danger">*</sup>';
    $pass_req = 'required="required"';
} else {
    $pass_mand = '';
    $pass_req = '';
}
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';
//Balu A
$is_domestic_flight = $search_data['is_domestic_flight'];
if ($is_domestic_flight) {
    $temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
    $static_passport_details = array();
    $static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
    $static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
    $static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));
}
if (is_logged_in_user()) {
    $review_active_class = ' success ';
    $review_tab_details_class = '';
    $review_tab_class = ' inactive_review_tab_marker ';
    $travellers_active_class = ' active ';
    $travellers_tab_details_class = ' gohel ';
    $travellers_tab_class = ' travellers_tab_marker ';
} else {
    $review_active_class = ' active ';
    $review_tab_details_class = ' gohel ';
    $review_tab_class = ' review_tab_marker ';
    $travellers_active_class = '';
    $travellers_tab_details_class = '';
    $travellers_tab_class = ' inactive_travellers_tab_marker ';
}
//$user_country_code = '+91';
echo generate_low_balance_popup($FareDetails['_CustomerBuying'] + $FareDetails['_GST']);
?>
<style>


    #timer {
        top:-40px !important;
    }
</style>
<div class="p_loader" style="display:none;">
    <div class="fulloading result-pre-loader-wrapper">
        <div class="loadmask"></div>
        <div class="centerload cityload">
            <div class="relativetop">
                <div class="iframeContainer border d-none" id="iframeDiv"></div>
                <div class="paraload">
                    <h3>Verifying your details...</h3>
                    <img src="<?= $template_images ?>default_loading.gif" alt="Loader" />
                    <div class="clearfix"></div>
                    <small>please wait</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="fldealsec d-none">
    <div class="container">
        <div class="tabcontnue">
            <div class="col-4 nopadding">
                <div class="rondsts <?= $review_active_class ?>">
                    <a class="taba core_review_tab <?= $review_tab_class ?>" id="stepbk1">
                        <div class="iconstatus fa fa-eye"></div>
                        <div class="stausline">Review</div>
                    </a>
                </div>
            </div>
            <div class="col-4 nopadding">
                <div class="rondsts <?= $travellers_active_class ?>">
                    <a class="taba core_travellers_tab <?= $travellers_tab_class ?>" id="stepbk2">
                        <div class="iconstatus fa fa-group"></div>
                        <div class="stausline">Travellers</div>
                    </a>
                </div>
            </div>
            <div class="col-4 nopadding">
                <div class="rondsts">
                    <a class="taba" id="stepbk3">
                        <div class="iconstatus fa fa-money"></div>
                        <div class="stausline">Payments</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="alldownsectn flt_bkng_pge">
    <div class="container-fluid">
        <?php if ($is_price_Changed == true) { ?>
            <div class="farehd arimobold">
                <span class="text-danger">* Price has been changed from supplier end</span>
            </div>
        <?php } ?>
        <div class="ovrgo">
            
            <div class="bktab2 xlbox <?= $travellers_tab_details_class ?> flight_booking_desc">
              
                <div class="clearfix"></div>
                <!-- Segment Details Starts-->
                <div class="collapse splbukdets" id="fligtdetails">
                    <div class="moreflt insideagain">
                        <?php echo $flight_segment_details['segment_full_details']; ?>
                    </div>
                </div>
                <!-- Segment Details Ends-->
                <div class="clearfix"></div>
                <div class="padpaspotr d-flex">

                <div class="col-4 nopadding rit_summery">

                    
                        <?php echo get_fare_summary($FareDetails, $PassengerFareBreakdown, $convenience_fees, $insurance_amount, $insurance_status, $org_convience_fee, $ApiPriceDetails, $api_markup_details, $convenience_fees_orginal); ?>

                        <div class="insiefare mt-3 promo_sec">
                        <div class="farehd arimobold">Promo Code</div>
                        <form name="promocode" id="promocode" novalidate>
                        <div class="kindrest">
                            <div class="cartlistingbuk">
            
                                <div class="clearfix"></div>
                                <div class="cartitembuk fredivs prompform">
                                    <div class="d-flex">
                                        <div class="col-md-8 col-8 nopadding_right">
                                            <div class="cartprc">
                                                <div class="payblnhm singecartpricebuk ritaln">
                                                    <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
                                                    <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?= @$module_value; ?>" />
                                                    <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?= @$FareDetails['TotalFare']; ?>" />
                                                    <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?= ((@$convenience_fees ? $convenience_fees : 0) + $FareDetails['_GST']); ?>" />
                                                    <input type="hidden" name="extra_seat_fee" id="extra_seat_fee" class="extra_seat_fee" value="0" />
                                                    <input type="hidden" name="extra_bagage_fee" id="extra_bagage_fee" class="extra_bagage_fee" value="0" />
                                                    <input type="hidden" name="extra_meal_fee" id="extra_meal_fee" class="extra_meal_fee" value="0" />
                                                    <input type="hidden" name="org_convience_fee" id="org_convience_fee" value="<?= $org_convience_fee; ?>">
                                                    <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?= @$currency_symbol; ?>" />
                                                    <input type="hidden" name="currency" id="currency" value="<?= @$currency; ?>" />
                                                    <input type="hidden" name="pax_count" id="pax_count" value="<?= @$total_pax_count; ?>" />

                                                    <!-- <p class="error_promocode text-danger" style="font-weight:bold"></p> -->
                                                </div>
                                                
                                              </div>
                                            </div>
                                            <div class="col-md-4 col-4 nopadding_left">
                                            <input type="button" value="Apply" name="apply" id="apply" class="promosubmit">
                                            <input type="button" value="Remove" name="remove" id="remove" class="promosubmit" style="display:none;">
                                              </div>
                                                </div>

                                            <div class="col-12 nopad view_cpn">
                                            <a>View all Coupons</a>
                                        </div>
                                        <div class=" col-12 nopad promo_crd_wrapper">
                                            <?php $s = 1;
                                            if (!empty($promo_codes)) { ?>
                                                <?php foreach ($promo_codes as $promo_code) {
                                                     ?>
                                                    <div class="promo_card">
                                                        <input type="radio" class="promo<?= $s ?>" name="promo" value="<?= $promo_code['promo_code'] ?>" onchange="setPromoCode('<?= $promo_code['promo_code'] ?>')">
                                                        <label for="promo<?= $s ?>">
                                                            <h5><?= $promo_code['promo_code'] ?></h5>
                                                            <p>Use this coupon and get <?php if ($promo_code['value_type'] == 'plus') { ?><?= @$currency_symbol; ?> <?= $promo_code['value'] ?> <?php } else {
                                                                echo $promo_code['value'] . '%';
                                                                } ?> instant discount on flight bookings</p>
                                                        </label>
                                                        <a class="promo_remove<?= $s ?>" onclick="removePromoCode('<?= $promo_code['promo_code'] ?>')">Remove</a>
                                                    </div>
                                                <?php $s++; } ?>
                                            <?php } ?>
                                            <!-- <div class="promo_card">
                                                    <input type="radio" class="promo1" name="promo" value="">
                                                    <label for="promo1">
                                                        <h5>FLIGHTS10</h5>
                                                        <p>Use this coupon and get ₹ 400 instant discount on flight bookings</p>
                                                    </label>
                                                    <a class="promo_remove1">Remove</a>
                                                </div>
                                                <div class="promo_card">
                                                    <input type="radio" class="promo2" name="promo" value="">
                                                    <label for="promo2">
                                                        <h5>HOTELS10</h5>
                                                        <p>Use this coupon and get ₹ 400 instant discount on hotel bookings</p>
                                                    </label>
                                                    <a class="promo_remove2">Remove</a>
                                                </div> -->
                                        </div>
                                        </div>
                                       
                                        
                                    </form>
                                </div>
                                <div class="clearfix"></div>
                                <div class="savemessage"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8 col-12 nopadding tab_pasnger">
                        <div class="fligthsdets">
                            <?php
                            /**
                             * Collection field name 
                             */
                            //Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
                            $total_adult_count = is_array($search_data['adult_config']) ? array_sum($search_data['adult_config']) : intval($search_data['adult_config']);
                            $total_child_count = is_array($search_data['child_config']) ? array_sum($search_data['child_config']) : intval($search_data['child_config']);
                            $total_infant_count = is_array($search_data['infant_config']) ? array_sum($search_data['infant_config']) : intval($search_data['infant_config']);
                            //------------------------------ DATEPICKER START
                            $i = 1;
                            $datepicker_list = array();
                            if ($total_adult_count > 0) {
                                for ($i = 1; $i <= $total_adult_count; $i++) {
                                    $datepicker_list[] = array('adult-date-picker-' . $i, ADULT_DATE_PICKER);
                                }
                            }

                            if ($total_child_count > 0) {
                                //id should be auto picked so initialize $i to previous value of $i
                                for ($i = $i; $i <= ($total_child_count + $total_adult_count); $i++) {
                                    $datepicker_list[] = array('child-date-picker-' . $i, CHILD_DATE_PICKER);
                                }
                            }

                            if ($total_infant_count > 0) {
                                //id should be auto picked so initialize $i to previous value of $i
                                for ($i = $i; $i <= ($total_child_count + $total_adult_count + $total_infant_count); $i++) {
                                    $datepicker_list[] = array('infant-date-picker-' . $i, INFANT_DATE_PICKER);
                                }
                            }

                            $GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
                            //------------------------------ DATEPICKER END

                            $total_pax_count = $total_adult_count + $total_child_count + $total_infant_count;
                            //First Adult is Primary and and Lead Pax
                            $adult_enum = get_enum_list('title');
                            $child_enum = get_enum_list('child_title');
                            $gender_enum = get_enum_list('gender');
                            unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
                            unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
                            $adult_title_options = generate_options($adult_enum, false, true);
                            $child_title_options = generate_options($child_enum, false, true);

                            $gender_options = generate_options($gender_enum);
                            $nationality_options = generate_options($iso_country_list, array(INDIA_CODE)); //FIXME get ISO CODE --- ISO_INDIA
                            $passport_issuing_country_options = generate_options($country_list);

                            if ($search_data['trip_type'] == 'oneway') {
                                $passport_minimum_expiry_date = date('Y-m-d', strtotime($search_data['depature']));
                            } else if ($search_data['trip_type'] == 'circle') {
                                //debug($search_data);exit;
                                $passport_minimum_expiry_date = date('Y-m-d', strtotime($search_data['return']));
                            } else {
                                $passport_minimum_expiry_date = date('Y-m-d', strtotime(end($search_data['depature'])));
                            }
                            //$passport_minimum_expiry_date = date('Y-m-d', strtotime('2018-01-01'));
                            //lowest year wanted
                            $cutoff = date('Y', strtotime('+20 years', strtotime($passport_minimum_expiry_date)));
                            //current year
                            //$now = date('Y');
                            $now = date('Y', strtotime($passport_minimum_expiry_date));
                            $day_options = generate_options(get_day_numbers());
                            $month_options = generate_options(get_month_names());
                            $year_options = generate_options(get_years($now, $cutoff));

                            /**
                             * check if current print index is of adult or child by taking adult and total pax count
                             * @param number $total_pax     total pax count
                             * @param number $total_adult   total adult count
                             */
                            function pax_type($pax_index, $total_adult, $total_child, $total_infant)
                            {
                                if ($pax_index <= $total_adult) {
                                    $pax_type = 'adult';
                                } elseif ($pax_index <= ($total_adult + $total_child)) {
                                    $pax_type = 'child';
                                } else {
                                    $pax_type = 'infant';
                                }
                                return $pax_type;
                            }

                            /**
                             * check if current print index is of adult or child by taking adult and total pax count
                             * @param number $total_pax     total pax count
                             * @param number $total_adult   total adult count
                             */
                            function is_adult($pax_index, $total_adult)
                            {
                                return ($pax_index > $total_adult ? false : true);
                            }

                            function pax_type_count($pax_index, $total_adult, $total_child, $total_infant)
                            {
                                if ($pax_index <= $total_adult) {
                                    $pax_count = ($pax_index);
                                } elseif ($pax_index <= ($total_adult + $total_child)) {
                                    $pax_count = ($pax_index - $total_adult);
                                } else {
                                    $pax_count = ($pax_index - ($total_adult + $total_child));
                                }
                                return $pax_count;
                            }

                            /**
                             * check if current print index is of adult or child by taking adult and total pax count
                             * @param number $total_pax     total pax count
                             * @param number $total_adult   total adult count
                             */
                            function is_lead_pax($pax_count)
                            {
                                return ($pax_count == 1 ? true : false);
                            }
                            ?>
                            <form action="<?= base_url() . 'index.php/flight/pre_booking/' . $search_data['search_id'] ?>" method="POST" autocomplete="off" id="pre-booking-form">
                                <div class="hide">
                                    <input type="hidden" required="required" name="search_id" value="<?= $search_data['search_id']; ?>" />
                                    <?php $dynamic_params_url = serialized_data($pre_booking_params); ?>
                                    <input type="hidden" required="required" name="token" value="<?= $dynamic_params_url; ?>" />
                                    <input type="hidden" required="required" name="token_key" value="<?= md5($dynamic_params_url); ?>" />
                                    <input type="hidden" required="required" name="op" value="book_room">
                                    <input type="hidden" required="required" name="booking_source" value="<?= $booking_source ?>" readonly>
                                    <input type="hidden" id="agentServicefee" name="agentServicefee" value="0">
                                    <!--<input type="hidden" required="required" name="provab_auth_key" value="?=$ProvabAuthKey ?>" readonly>
                                    -->
                                </div>
                                <div class="flitab1">
                                    <div class="moreflt boksectn">
                                        <div class="ontyp">
                                            <div class="labltowr arimobold">Please enter names as on passport. </div>
                                            <?php
                                            $pax_index = 1;
                                            $lead_pax_details = @$pax_details[0];
                                            if (is_logged_in_user()) {
                                                $traveller_class = ' user_traveller_details ';
                                            } else {
                                                $traveller_class = '';
                                            }
                                            for ($pax_index = 1; $pax_index <= $total_pax_count; $pax_index++) { //START FOR LOOP FOR PAX DETAILS
                                                $cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();
                                                $pax_type = pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count);
                                                $pax_type_count = pax_type_count($pax_index, $total_adult_count, $total_child_count, $total_infant_count);

                                                if ($pax_type != 'infant') {
                                                    $extract_pax_name_cls = ' extract_pax_name_cls ';
                                                } else {
                                                    $extract_pax_name_cls = '';
                                                }
                                            ?>
                                                <div class="pasngr_input pasngrinput _passenger_hiiden_inputs">
                                                    <div class="hide hidden_pax_details">
                                                        <input type="hidden" name="passenger_type[]" value="<?= ucfirst($pax_type) ?>">
                                                        <input type="hidden" name="lead_passenger[]" value="<?= (is_lead_pax($pax_index) ? true : false) ?>">
                                                        <input type="hidden" name="gender[]" value="1" class="pax_gender">
                                                        <input type="hidden" required="required" name="passenger_nationality[]" id="passenger-nationality-<?= $pax_index ?>" value="92">
                                                    </div>
                                                    <div class="col-1 nopadding full_dets_aps">
                                                        <div class="adltnom"><?= ucfirst($pax_type) ?> <?= $pax_type_count ?><?= $mandatory_filed_marker ?></div>
                                                    </div>
                                                    <div class="col-11 nopadding full_dets_aps">

                                                        <div class="inptalbox">
                                                            <div class="col-2 spllty">
                                                                <div class="selectedwrap pax_selectedwrap">
                                                                <span class="formlabel">Select Title <sup class="text-danger">*</sup></span>
                                                                    <select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required>
                                                                        <?php echo (is_adult($pax_index, $total_adult_count) ? $adult_title_options : $child_title_options) ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-4 spllty">
                                                                <span class="formlabel">First Name <sup class="text-danger">*</sup></span>
                                                                <input value="<?= @$cur_pax_info['first_name'] ?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput alpha_space <?= $traveller_class ?>" maxlength="45" placeholder="Enter First Name" data-row-id="<?= ($pax_index); ?>" />
                                                            </div>
                                                            <div class="col-3 spllty">
                                                                <span class="formlabel">Last Name <sup class="text-danger">*</sup></span>
                                                                <input value="<?= @$cur_pax_info['last_name'] ?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput alpha_space" maxlength="45" placeholder="Enter Last Name" />
                                                            </div>
                                                            <?php if ($pax_type == 'infant') { //Only For Infant  
                                                            ?>
                                                                <div class="col-3 spllty infant_dob_div">
                                                                    <!-- <div class="col-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div> -->
                                                                    <div class="col-12 nopadding">
                                                                        <span class="formlabel">Date of Birth <?= $mandatory_filed_marker ?></span>
                                                                        <input placeholder="DOB" type="text" class="clainput date_picker_infant" name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            } else { //Adult/Child
                                                                if (($pax_type == 'adult')) {
                                                                ?>
                                                                    <div class="col-3 spllty infant_dob_div">
                                                                        <!-- <div class="col-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div> -->
                                                                        <div class="col-12 nopadding">
                                                                            <span class="formlabel">Date of Birth <?= $mandatory_filed_marker ?></span>
                                                                            <input placeholder="DOB" type="text" class="clainput date_picker_adult" name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>" value="<?= !empty($cur_pax_info['date_of_birth']) ? date("d-m-Y", strtotime($cur_pax_info['date_of_birth'])) : '' ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (($pax_type == 'child')) { ?>
                                                                    <div class="col-3 spllty infant_dob_div">
                                                                        <!-- <div class="col-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div> -->
                                                                        <div class="col-12 nopadding">
                                                                            <span class="formlabel">Date of Birth <?= $mandatory_filed_marker ?></span>
                                                                            <input placeholder="DOB" type="text" class="clainput date_picker_child" name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                        </div>
                                                                    </div>
                                                            <?php }
                                                            } ?>
                                                            <div class="clearfix"></div>
                                                            <!-- Passport Section Starts -->
                                                            <div class="passport_content_div w-100">
                                                                <?php //debug($IsPassportMandatory); 
                                                                if ($is_domestic_flight == false) { //if($IsPassportMandatory == 'true') { 
                                                                    $addob = date('Y-m-d', strtotime('-30 years')); //For Internatinal Travel  
                                                                ?>
                                                                    <!--  <input placeholder="DOB" type="hidden" class="clainput" name="date_of_birth[]" value="<?php echo $addob ?>"> -->
                                                                    <div class="international_passport_content_div">
                                                                        <div class="col-4 spllty">
                                                                            <span class="formlabel">Passport Number <?= $pass_mand ?></span>
                                                                            <div class="relativemask">
                                                                                <input type="text" name="passenger_passport_number[]" <?= $pass_req ?> id="passenger_passport_number_<?= $pax_index ?>" class="clainput" maxlength="10" placeholder="Passport Number" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-3 spllty">
                                                                            <span class="formlabel">Issuing Country <?= $pass_mand ?></span>
                                                                            <div class="selectedwrap">
                                                                                <select name="passenger_passport_issuing_country[]" <?= $pass_req ?> id="passenger_passport_issuing_country_<?= $pax_index ?>" class="mySelectBoxClass flyinputsnor">
                                                                                    <option value="INVALIDIP">Please Select</option>
                                                                                    <?= $passport_issuing_country_options ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-5 spllty">
                                                                            <span class="formlabel">Date of Expiry <?= $pass_mand ?></span>
                                                                            <div class="relativemask">
                                                                                <div class="col-4 splinmar">
                                                                                    <div class="selectedwrap">
                                                                                        <select name="passenger_passport_expiry_day[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_day" data-expiry-type="day" id="passenger_passport_expiry_day_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                            <option value="INVALIDIP">DD</option>
                                                                                            <?= $day_options; ?>
                                                                                        </select>
                                                                                        <input type="hidden" value="<?php echo $now; ?>" id="travel_year">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 ps-2 pe-2">
                                                                                    <div class="selectedwrap">
                                                                                        <select name="passenger_passport_expiry_month[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_month" data-expiry-type="month" id="passenger_passport_expiry_month_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                            <option value="INVALIDIP">MM</option>
                                                                                            <?= $month_options; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-4 splinmar">
                                                                                    <div class="selectedwrap">
                                                                                        <select name="passenger_passport_expiry_year[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_year" data-expiry-type="year" id="passenger_passport_expiry_year_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                            <option value="INVALIDIP">YYYY</option>
                                                                                            <?= $year_options; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="float-end text-danger hide" id="passport_error_msg_<?= $pax_index ?>"></div>
                                                                    </div>
                                                                <?php //}
                                                                } else { //For Domestic Travel, Set Static Passport Data
                                                                    $passport_number = rand(1111111111, 9999999999);
                                                                    $passport_issuing_country = 92;
                                                                ?>
                                                                    <div class="domestic_passport_content_div hide">
                                                                        <input type="hidden" name="passenger_passport_number[]" value="<?= $passport_number ?>" id="passenger_passport_number_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_issuing_country[]" value="<?= $passport_issuing_country ?>" id="passenger_passport_issuing_country_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_day[]" value="<?= $static_passport_details['passenger_passport_expiry_day'] ?>" id="passenger_passport_expiry_day_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_month[]" value="<?= $static_passport_details['passenger_passport_expiry_month'] ?>" id="passenger_passport_expiry_month_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_year[]" value="<?= $static_passport_details['passenger_passport_expiry_year'] ?>" id="passenger_passport_expiry_year_<?= $pax_index ?>">
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <!-- Passport Section Ends-->

                                                        </div><!-- inptalbox class ends -->
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <!-- Frequent Flyer Section Start-->
                                                    <?php if ($booking_source == TBO_FLIGHT_BOOKING_SOURCE) { ?>
                                                        <div class="adltnom">Frequent Flyer Details (Optional)</div>
                                                        <div class="col-1 nopadding full_dets_aps">
                                                        </div>
                                                        <div class="col-11 nopadding full_dets_aps">
                                                            <div class="inptalbox">

                                                                <div class="col-6 spllty"> <span class="formlabel">Frequent Flyer Airline</span>
                                                                    <div class="slc_dt">
                                                                        <select class="form-control" name="frequent_flyer_airline[]" id="frequent_flyer_airline_<?= $pax_index ?>">
                                                                            <option value="">Select Frequent Flyer Airline</option>
                                                                            <?php foreach ($airline_list as $code => $value) {
                                                                                echo '<option value="' . $code . '">' . $value . '</option>';
                                                                            } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 spllty">
                                                                    <span class="formlabel">Frequent Flyer No </span>
                                                                    <input type="text" name="frequent_flyer_no[]" class="form-control" id="frequent_flyer_no_<?= $pax_index ?>" placeholder="Frequent Flyer No">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- Frequent Flyer Section Ends-->
                                                </div>
                                            <?php
                                            } //END FOR LOOP FOR PAX DETAILS
                                            ?>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="clearfix"></div>
                                    <div class="contbk">
                                        <div class="contcthdngs">CONTACT DETAILS</div>
                                        <div class="col-12 nopad d-flex m-0 full_smal_forty">
                                            <div class="col-7 nopad d-flex full_smal_forty">
                                                <div class="col-4">
                                                    <div class="hide">
                                                        <input type="hidden" name="billing_country" value="92">
                                                        <input type="hidden" name="billing_city" value="test">
                                                        <input type="hidden" name="billing_zipcode" value="test">
                                                        <input type="hidden" name="billing_address_1" value="test">
                                                    </div>
                                                    <select name="phone_country_code" class="newslterinput nputbrd _numeric_only" id="after_country_code" required>
                                                        <?php echo diaplay_phonecode($phone_code, $active_data, $user_country_code); ?>
                                                    </select>
                                                </div>
                                                <div class="col-1">
                                                    <div class="sidepo">-</div>
                                                </div>
                                                <div class="col-7 nopadding">
                                                    <input value="<?= @$lead_pax_details['phone'] == 0 ? '' : @$lead_pax_details['phone']; ?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="10" required="required">
                                                </div>
                                            </div>


                                            <div class="emailperson col-5 ps-3 full_smal_forty">
                                                <input value="<?= @$lead_pax_details['email'] ?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="notese">Your mobile number will be used only for sending flight related communication.</div>
                                    </div>
                                    <div class="card-group mb0 hide" role="tablist" aria-multiselectable="true">
                                        <div class="panel card for_gst flight_special_req">
                                            <div class="card-header" role="tab" id="gst_opt">
                                                <h4 class="card-title">
                                                    <a role="button" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#gst_optnl" aria-expanded="true" aria-controls="gst_optnl">

                                                        <div class="labltowr arimobold">GST Information(Optional) <i class="more-less glyphicon glyphicon-plus"></i></div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="gst_optnl" class="collapse collapse" role="tabpanel" aria-labelledby="gst_opt">
                                                <!-- <div class="contcthdngs">GST Information(Optional)</div> -->
                                                <div class="col-12 gst_det" id="gst_form_div">
                                                    <div class="row">
                                                        <div class="col-3"> GST Number </div>
                                                        <div class="col-7">
                                                            <input type="text" class="newslterinput clainput nputbrd" id="gst_number" name="gst_number" value="">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3"> Company Name </div>
                                                        <div class="col-7">
                                                            <input type="text" class="newslterinput nputbrd" id="gst_company_name" name="gst_company_name" vaule="">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3"> Email </div>
                                                        <div class="col-7">
                                                            <input type="email" class="newslterinput nputbrd" id="gst_email" name="gst_email" value="">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3"> Phone Number </div>
                                                        <div class="col-7">
                                                            <input type="text" class="newslterinput nputbrd _numeric_only" id="gst_phone" name="gst_phone" maxlength="10" value="">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3"> Address </div>
                                                        <div class="col-7">
                                                            <input type="text" class="newslterinput nputbrd" name="gst_address" id="gst_address" value="">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3"> Province </div>
                                                        <div class="col-7">
                                                            <?php $state_list = generate_options($state_list); ?>
                                                            <select name="gst_state" class="clainput" id="gststate">
                                                                <option value="INVALIDIP">Please Select</option>
                                                                <?= $state_list ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    
                                    <div class="extras_toggle_section">
                                    <button class="extras_toggle_btn" type="button" data-bs-toggle="collapse" data-bs-target="#disclaimerCollapse" aria-expanded="false" aria-controls="disclaimerCollapse">
                                    <div class="toggle_text">
                                    <i class="material-icons">description</i>
                                    <span>Disclaimer</span>
                                    </div>
                                    <i class="material-icons arrow_icon">expand_more</i>
                                    </button>
                                    <div class="collapse" id="disclaimerCollapse">
                                    <div class="extras_content">
                                    <div class="disclaimer_content">
                                    <ul>
                                    <li>Before booking your flight, please check that you meet all visa and passport requirements for all connections and destinations included in your trip. Also, please check for the latest requirements with the airline(s) prior to travel as they can change.</li>
                                                        <li>Important: Some seats shown may no longer be available when we transmit your request(s) to the airline(s). Your seat request(s) will be transmitted to the airline once your booking is confirmed. Seat fees will be refunded if we are unable to confirm your selection(s) with the airline:</li>
                                                        <li>Review and book your trip</li>
                                                        <li>1. Please confirm that the date(s) and time(s) of flights and name(s) of travellers are accurate.</li>
                                                        <li>2. Traveller names must match government-issued photo ID exactly (Passport Matching Name only). Name changes are not allowed.</li>
                                                        <li>3. Passenger 1: Missing name [ Make changes ]</li>
                                                        <li>4. Refundable for the first 24 hours with fees.</li>
                                                        <li>5. Non-Refundable 0 hours to 24 hours.</li>
                                                        <li>6. Exchange possible for fee.</li>
                                                        <li>7. Taxes and fees are included in the total ticket cost. Prices may not include (baggage fees) or other fees charged directly by the airline. Fares are not guaranteed until ticketed. Service fees is non-refundable.</li>
                                                        <li>8. Cancelations, routing and date changes are subject to (fare rules) and our (fees) should these changes be allowed by the airline.</li>
                                                        <li>9. By selecting to complete this booking I acknowledge that I have read and accept the above fare rules and restrictions, Terms & Conditions and Privacy Policy.</li>
                                                        <li>10. The airline fee is indicative.</li>
                                                        <li>11. All refunds are subject to airline approval.</li>
                                                        <li>12. Enter your name as it is mentioned on your passport. Passport should be valid for minimum 6 months from the date of travel.</li>
                                                        <li>13. Please ensure that the Frequent Flyer No entered here is against the same passenger name otherwise the points will not be updated by the airline.</li>
                                                        <li>14. All travellers must present hard copies of their foreign visa (soft copies won’t be accepted) at the immigration counters during departure.</li>
                                                        <li>15. Kapido holds no liability with respect to visa information.</li>
                                    </ul>
                                    </div>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="clearfix"></div>
                                   
                                    <!-- Dyanamic Baggage&Meals Section Starts -->
                                    <?php
                                    if (valid_array($extra_services) == true) {
                                        if (isset($extra_services['ExtraServiceDetails']['Baggage'])) {
                                            $baggage_meal_seat_details['baggage_meal_details']['Baggage'] = $extra_services['ExtraServiceDetails']['Baggage'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['Meals'])) {
                                            $baggage_meal_seat_details['baggage_meal_details']['Meals'] = $extra_services['ExtraServiceDetails']['Meals'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['Seat'])) {
                                            $baggage_meal_seat_details['baggage_meal_details']['Seat'] = $extra_services['ExtraServiceDetails']['Seat'];
                                            if (isset($extra_services['ExtraServiceDetails']['ColumnLayOut'])) {
                                                $baggage_meal_seat_details['baggage_meal_details']['ColumnLayOut'] = $extra_services['ExtraServiceDetails']['ColumnLayOut'];
                                            }
                                        }
                                        $baggage_meal_seat_details['total_adult_count'] = $total_adult_count;
                                        $baggage_meal_seat_details['total_child_count'] = $total_child_count;
                                        $baggage_meal_seat_details['total_infant_count'] = $total_infant_count;
                                        $baggage_meal_seat_details['total_pax_count'] = $total_pax_count;
                                        $baggage_meal_seat_details['booking_source'] = $booking_source;
                                        echo $GLOBALS['CI']->template->isolated_view('flight/dynamic_baggage_meal_seat_details', $baggage_meal_seat_details);
                                    }
                                    ?>
                                    <!-- Dyanamic Baggage&Meals Section Ends -->
                                    <!-- Seats&Meals Preference Section Starts -->
                                    <?php
                                    if (valid_array($extra_services) == true) {
                                        if (isset($extra_services['ExtraServiceDetails']['MealPreference'])) {
                                            $seat_meal_preference_details['seat_meal_preference_details']['MealPreference'] = $extra_services['ExtraServiceDetails']['MealPreference'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['SeatPreference'])) {
                                            $seat_meal_preference_details['seat_meal_preference_details']['SeatPreference'] = $extra_services['ExtraServiceDetails']['SeatPreference'];
                                        }
                                        $seat_meal_preference_details['total_adult_count'] = $total_adult_count;
                                        $seat_meal_preference_details['total_child_count'] = $total_child_count;
                                        $seat_meal_preference_details['total_infant_count'] = $total_infant_count;
                                        $seat_meal_preference_details['total_pax_count'] = $total_pax_count;
                                        echo $GLOBALS['CI']->template->isolated_view('flight/seat_meal_preference_details', $seat_meal_preference_details);
                                    }
                                    ?>
                                    <!-- Seats&Meals Preference Section Ends -->
                                    <div class="clearfix"></div>
                                    <div class="loginspld">
                                        <div class="collogg">
                                            <?php
                                            //If single payment option then hide selection and select by default
                                            if (count($active_payment_options) == 1) {
                                                $payment_option_visibility = 'hide';
                                                $default_payment_option = 'checked="checked"';
                                            } else {
                                                $payment_option_visibility = 'show';
                                                $default_payment_option = '';
                                            }
                                            ?>
                                            <div class="row <?= $payment_option_visibility ?>">
                                                <?php if (in_array(PAY_NOW, $active_payment_options)) { ?>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="payment-mode-<?= PAY_NOW ?>">
                                                                <input <?= $default_payment_option ?> name="payment_method" type="radio" required="required" value="<?= PAY_NOW ?>" id="payment-mode-<?= PAY_NOW ?>" class="form-control b-r-0" placeholder="Payment Mode">
                                                                Pay Now
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if (in_array(PAY_AT_BANK, $active_payment_options)) { ?>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="payment-mode-<?= PAY_AT_BANK ?>">
                                                                <input <?= $default_payment_option ?> name="payment_method" type="radio" required="required" value="<?= PAY_AT_BANK ?>" id="payment-mode-<?= PAY_AT_BANK ?>" class="form-control b-r-0" placeholder="Payment Mode">
                                                                Pay At Bank
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                            <div class="clikdiv">
                                        <div class="squaredThree">
                                            <input id="terms_cond1" type="checkbox" name="tc" required="required">
                                            <label for="terms_cond1"></label>
                                        </div>
                                        <span class="clikagre" id="clikagre"><a href="<?= base_url('terms-conditions') ?>" target="_blank">Terms and Conditions</a></label>
                                        </span>
                                    </div>

                                            <div class="temsandcndtn">
                                                Most countries require travellers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
                                            </div>

                                            <div class="continye col-sm-4 col-6 nopad">
                                                <button type="submit" id="flip" name="flight" class="bookcont continue_booking_button">Continue</button>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="sepertr"></div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<span class="hide">
    <input type="hidden" id="pri_passport_min_exp" value="<?= $passport_minimum_expiry_date ?>">
</span>

<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup'); ?>
<?php echo $GLOBALS['CI']->template->isolated_view('share/passenger_confirm_popup'); ?>
<?php
/*
 * Balu A
 * Flight segment details
 * Outer summary and Inner Summary
 */

function flight_segment_details($SegmentDetails, $SegmentSummary)
{
    $loc_dir_icon = '<span class="fadr fa fa-long-arrow-right textcntr"></span>';
    $inner_summary = $outer_summary = '';
    //Inner Summary
    foreach ($SegmentDetails as $__segment_k => $__segment_v) {
        $segment_summary = $SegmentSummary[$__segment_k];
        //Calculate Total Duration of Onward/Return Journey
        $inner_summary .= '<div class="ontyp">';
        //Way Summary in one line - Start
        $inner_summary .= '<div class="labltowr arimobold">';
        $inner_summary .= $segment_summary['OriginDetails']['CityName'] . ' to ' . $segment_summary['DestinationDetails']['CityName'] . '<strong>(' . $segment_summary['TotalDuaration'] . ')</strong>';
        $inner_summary .= '</div>';
        //Way Summary in one line - End
        foreach ($__segment_v as $__stop => $__segment_flight) {
            //Summary of Way - Start
            $inner_summary .= '<div class="allboxflt">';
            //airline
            $inner_summary .= '<div class="col-3 nopadding width_adjst">
                                    <div class="jetimg">
                                    <img  alt="' . $__segment_flight['AirlineDetails']['AirlineCode'] . '" src="' . SYSTEM_IMAGE_DIR . 'airline_logo/' . $__segment_flight['AirlineDetails']['AirlineCode'] . '.svg" >
                                    </div>
                                    <div class="alldiscrpo">
                                    ' . $__segment_flight['AirlineDetails']['AirlineName'] . '
                                    <span class="sgsmal">' . $__segment_flight['AirlineDetails']['AirlineCode'] . ' 
                                    <br />' . $__segment_flight['AirlineDetails']['FlightNumber'] . '</span>
                                    </div>
                                  </div>';
            //depart
            $inner_summary .= '<div class="col-7 nopadding width_adjst">';
            $inner_summary .= '<div class="col-5 nopad">
                                    <span class="airlblxl">' . month_date_time($__segment_flight['OriginDetails']['DateTime']) . '</span>
                                    <span class="portnme">' . $__segment_flight['OriginDetails']['AirportName'] . '</span>
                                    </div>';
            //direction indicator
            $inner_summary .= '<div class="col-2 nopad">
                ' . $loc_dir_icon . '</div>';
            //arrival
            $inner_summary .= '<div class="col-5 nopad">
                                    <span class="airlblxl">' . month_date_time($__segment_flight['DestinationDetails']['DateTime']) . '</span>
                                    <span class="portnme">' . $__segment_flight['DestinationDetails']['AirportName'] . '</span>
                                    </div>';
            $inner_summary .= '</div>';

            //Between Content -----
            $inner_summary .= '<div class="col-2 nopadding width_adjst">
                                <span class="portnme textcntr">' . $__segment_flight['SegmentDuration'] . '</span>
                                <span class="portnme textcntr">Stop : ' . ($__stop) . '</span>
                                <div class="Baggage_block">' . (trim($__segment_flight['Baggage']) ? '
                                    <div class="termnl1 flo_w"> 
                                       <em><i class="fa fa-suitcase bag_icon"></i>' . $__segment_flight['Baggage'] . '</em>
                                    </div>' : '') . (trim($__segment_flight['AvailableSeats']) ? '
                                    <div class="termnl1 flo_w">
                                        <em><i class="air_seat timings icseats"></i>' . $__segment_flight['AvailableSeats'] . '</em>
                                    </div>' : '') . '
                                </div>
                                </div>';
            //Summary of Way - End
            $inner_summary .= '</div>';
            if (isset($__segment_v['WaitingTime']) == true) {
                $next_seg_info = $__segment_v[$__stop + 1];
                $waiting_time = $__segment_v['WaitingTime'];
                $inner_summary .= '
            <div class="clearfix"></div>
            <div class="connectnflt">
                <div class="conctncentr">
                <span class="fa fa-plane"></span>Change of planes at ' . $next_seg_info['OriginDetails']['AirportName'] . ' | <span class="fa fa-clock-o"></span> Waiting : ' . $waiting_time . '
            </div>
            </div>
            <div class="clearfix"></div>';
            }
        }
        $inner_summary .= '</div>';
    }
    //Outer Summry
    $total_stop_count = 0;
    $outer_summary .= '<div class="moreflt spltopbk">';
    foreach ($SegmentSummary as $__segment_k => $__segment_v) {
        $total_segment_travel_duration = $__segment_v['TotalDuaration'];
        $__stop_count = $__segment_v['TotalStops'];
        $total_stop_count += $__stop_count;
        $outer_summary .= '<div class="ontypsec">
                        <div class="allboxflt">';
        //airline
        $outer_summary .= '<div class="col-3 nopadding width_adjst">
                            <div class="jetimg">
                            <img class="airline-logo" alt="' . $__segment_v['AirlineDetails']['AirlineCode'] . '" src="' . SYSTEM_IMAGE_DIR . 'airline_logo/' . $__segment_v['AirlineDetails']['AirlineCode'] . '.svg">
                            </div>
                            <div class="alldiscrpo">
                                    ' . $__segment_v['AirlineDetails']['AirlineName'] . '
                                    <span class="sgsmal"> ' . $__segment_v['AirlineDetails']['AirlineCode'] . '<br />' . $__segment_v['AirlineDetails']['FlightNumber'] . '</span>
                            </div>
                          </div>';
        $outer_summary .= '<div class="col-7 nopadding width_adjst">';
        //depart
        $outer_summary .= '<div class="col-5">
                                    <span class="airlblxl">' . $__segment_v['OriginDetails']['AirportName'] . '</span>
                                    <span class="portnme">' . month_date_time($__segment_v['OriginDetails']['DateTime']) . '</span>
                            </div>';
        //direction indicator
        $outer_summary .= '<div class="col-2"><span class="fadr fa fa-long-arrow-right textcntr"></span></div>';
        //arrival
        $outer_summary .= '<div class="col-5">
                                    <span class="airlblxl">' . $__segment_v['DestinationDetails']['AirportName'] . '</span>
                                    <span class="portnme">' . month_date_time($__segment_v['DestinationDetails']['DateTime']) . '</span>
                                </div>
                                </div>';
        //Stops/Class details
        $outer_summary .= '<div class="col-2 nopadding width_adjst">
                                <span class="portnme textcntr">' . ($total_segment_travel_duration) . '</span>
                                <span class="portnme textcntr" >Stop:' . ($__stop_count) . '</span>
                                <div class="Baggage_block">' . (trim($SegmentDetails[0][0]['Baggage']) ? '
                                    <div class="termnl1 flo_w" style="padding:0 5px">
                                        <em><i class="fa fa-suitcase bag_icon"></i>' . $SegmentDetails[0][0]['Baggage'] . '</em>
                                    </div>' : '') . (trim($SegmentDetails[0][0]['AvailableSeats']) ? '
                                    <div class="termnl1 flo_w" style="padding:0 5px">
                                        <em><i class="air_seat timings icseats"></i>' . $SegmentDetails[0][0]['AvailableSeats'] . '</em>
                                    </div>' : '') . '
                                </div>
                        </div>';
        $outer_summary .= '</div></div>';
    }
    $outer_summary .= '</div>';
    return array('segment_abstract_details' => $outer_summary, 'segment_full_details' => $inner_summary);
}

function get_fare_summary($FareDetails, $PassengerFareBreakdown)
{
    // debug($FareDetails);exit;
    $total_payable = $FareDetails['_TotalPayable'] + $FareDetails['_GST'];
    $total_published_fare = $FareDetails['_CustomerBuying'];
    $rounding_amount = round($total_published_fare, 2) - $total_published_fare;
    $FareDetails['_TaxSum'] += $rounding_amount;
    $total_published_fare = round($total_published_fare, 2);
    $currency_symbol = $FareDetails['CurrencySymbol'];
    $gst_data = '';
    $gst_data1 = '';
    $i = 0;
    if ($FareDetails['_GST'] > 0) {
        $gst_data = '<div class="col-8 nopadding">
                        <div class="faresty">GST</div>
                        </div>
                    <div class="col-4 nopadding">
                        <div class="amnter arimobold">' . $currency_symbol . ' ' . $FareDetails['_GST'] . ' </div>
                    </div>
                    ';
        $gst_data1 = '<div class="reptallt">
                                        <div class="col-8 nopadding">
                                            <div class="faresty">GST</div>
                                        </div>

                                        <div class="col-4 nopadding">
                                            <div class="amnter arimobold">+' . $FareDetails['_GST'] . ' </div>
                                        </div>
                                        </div>';
    }
    $fare_summary = '<div class="insiefare">
                    <div class="farehd arimobold">Fare Summary<span id="timer">20:00</span></div>
                    <div class="fredivs">';
    $hide_show_fare_details = '<div class="kindrest">
                                            <a class="freshd show_details btn btn-sm float-start" id="hide_show_net_fare" data-bs-toggle="collapse" href="#net_fare_details" aria-expanded="false" aria-controls="net_fare_details">
                                            +SNF
                                            </a>
                                            </div>';
    $service_fee_details = '<div class="kindrest">
                                <div class="reptallt">
                                    <div class="col-8 nopadding">
                                    <div class="faresty">Your Service Fee</div>
                                    </div>
                                     <div class="col-4 nopadding">
                                        <input type="number" class="form-control" name="agent_service_fee" id="agent_service_fee" onchange="addServicefee()" value="0">
                                     </div>
                                </div>
                            </div>';
    $pax_base_fare_details = '<div class="kindrest">
                                <div class="freshd">Base Fare</div>';
    foreach ($PassengerFareBreakdown as $k => $v) {
        $pax_type = $v['PassengerType'];
        // $pax_base_fare = $v['TotalFare'];
        $pax_base_fare = $v['BaseFare'];
        $pax_count = $v['Count'];
        $pax_base_fare_details .= '<div class="reptallt">
                        <div class="col-8 nopadding">
                            <div class="faresty">' . $pax_count . ' ' . $pax_type . '(s) ‎(1 X ' . ($pax_base_fare / $pax_count) . ')</div>
                        </div>
                        <div class="col-4 nopadding">
                            <div class="amnter"><span class="base_fare_span">' . $currency_symbol . '</span> <span class="base_fare_value' . $i . '">' . $pax_base_fare . ' </span></div>
                        </div>
                    </div>';
        $i++;
    }

    $pax_tax_details = '<div class="kindrest">
                                    <!--div class="freshd">Taxes</div-->';
    $pax_tax_details .= '<div class="reptallt">
                                <div class="col-8 nopadding">
                                    <div class="faresty">Taxes & Fees</div>
                                </div>
                                <div class="col-4 nopadding">
                                    <div class="amnter arimobold">' . $currency_symbol . ' ' . ($FareDetails['_TaxSum'] - $FareDetails['_GST']) . ' </div>
                                </div>
                            </div>' . $gst_data;
    $pax_base_fare_details .= '</div>';
    $pax_tax_details .= '</div>';

    $extar_service_charge_details = '<div class="">';
    $extar_service_charge_details .= '<div class="baggagecharge-agent" id="extra_baggage_charge_label" style="display:none">
                                                        <div class="col-8 nopadding">Extra Baggage Charge</div>
                                                        <div class="col-4 nopadding text-end">' . $currency_symbol . ' 
                                                            <span class="amnter arimobold" id="extra_baggage_charge"></span>
                                                            <span class="btn btn-sm btn-secondary" id="remove_extra_baggage"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                            </div>
                                                    </div>
                                                    <div class="baggagecharge-agent" id="extra_meal_charge_label" style="display:none">
                                                        <div class="col-8 nopadding">Meal Charge</div>
                                                            <div class="col-4 nopadding text-end">' . $currency_symbol . '
                                                            <span class="amnter arimobold" id="extra_meal_charge"></span>
                                                            <span class="btn btn-sm btn-secondary" id="remove_extra_meal"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                            </div>
                                                    </div>
                                                    <div class="baggagecharge-agent" id="extra_seat_charge_label" style="display:none">
                                                        <div class="col-8 nopadding">Seat Charge</div>
                                                            <div class="col-4 nopadding text-end">' . $currency_symbol . '
                                                            <span class="amnter arimobold" id="extra_seat_charge"></span>
                                                            <span class="btn btn-sm btn-secondary" id="remove_extra_seat"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                            </div>
                                                    </div>
                                                    ';

    $extar_service_charge_details .= '</div>';

    $grand_total = '
                        <div class="clearfix"></div>
                        <div class="reptalltftr">
                            <div class="col-8 nopadding">
                                <div class="farestybig">Grand Total</div>
                            </div>
                            <div class="col-4 nopadding">
                                <div class="amnterbig arimobold">' . $currency_symbol . ' <span class="grand_total_amount">' . $total_published_fare . '</span>  </div>
                            </div>
                        </div>';
    //Net Fare Details
    $hnf_details = '<div class="collapse" id="net_fare_details">
                                    <div class="kindrest">
                                        <div class="freshd">Fare Details</div>
                                        <div class="reptallt">
                                            <div class="col-8 nopadding">
                                                <div class="faresty">Total Pub. Fare</div>
                                            </div>
                                            <div class="col-4 nopadding">
                                                <div class="amnter arimobold">' . $total_published_fare . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                            <div class="col-8 nopadding">
                                                <div class="faresty">Markup</div>
                                            </div>
                                            <div class="col-4 nopadding">
                                                <div class="amnter arimobold">+' . $FareDetails['_Markup'] . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                            <div class="col-8 nopadding">
                                                <div class="faresty">Comm. Earned</div>
                                            </div>
                                            <div class="col-4 nopadding">
                                                <div class="amnter arimobold">+' . $FareDetails['_Commission'] . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                        <div class="col-8 nopadding">
                                            <div class="faresty">TdsOnCommission</div>
                                        </div>
                                        <div class="col-4 nopadding">
                                            <div class="amnter arimobold">-' . $FareDetails['_tdsCommission'] . ' </div>
                                        </div>
                                        </div>' . $gst_data1 . '
                                        <div class="reptallt_commisn">
                                        <div class="col-6 nopadding">
                                            <div class="farestybig">Total Payable</div>
                                        </div>
                                        <div class="col-6 nopadding">
                                            <div class="amnterbig">' . $currency_symbol . ' <span id="agent_payable_amount">' . $total_payable . '</span> </div>
                                        </div>
                                        </div>
                                        <div class="reptallt_commisn">
                                        <div class="col-8 nopadding">
                                            <div class="farestybig">Total Earned</div>
                                        </div>
                                        <div class="col-4 nopadding">
                                            <div class="amnterbig ">' . $currency_symbol . ' ' . $FareDetails['_AgentEarning'] . ' </div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>';
    $fare_summary .= $hide_show_fare_details;
    $fare_summary .= $hnf_details;
    $fare_summary .= '<div id="published_fare_details">';
    $fare_summary .= $pax_base_fare_details;
    $fare_summary .= $pax_tax_details;
    $fare_summary .= $extar_service_charge_details;
    $fare_summary .= $service_fee_details;
    $fare_summary .= $grand_total;
    $fare_summary .= '</div>';
    $fare_summary .= '</div>
                </div>';

    return $fare_summary;
}

function diaplay_phonecode($phone_code, $active_data, $user_country_code)
{
    $list = '';
    foreach ($phone_code as $code) {
        if (!empty($user_country_code)) {
            if ($user_country_code == $code['origin']) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        } else {
            if ($active_data['api_country_list_fk'] == $code['origin']) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        }
        $list .= "<option value=" . $code['country_code'] . "  " . $selected . " >" . $code['name'] . " " . $code['country_code'] . "</option>";
    }
    return $list;
}
?>
<script>
    $(document).ready(function(e) {
        $('.ticket_type_cls').click(function() {
            var ticket_type = $(this).val();
            $('#ticket_method').val(ticket_type);
            $('.pay_frm input, .pay_frm select').each(function() {
                $(this).prop('required', ticket_type !== 'hold_ticket');
                if (ticket_type === 'hold_ticket') {
                    $(this).val('');
                }
            });
        });
        $('#hide_show_net_fare').click(function() {
            if ($(this).hasClass('show_details') == true) {
                $(this).removeClass('show_details').addClass('hide_details');
                $(this).empty().html('-HNF');
                $('#published_fare_details').hide();
            } else if ($(this).hasClass('hide_details') == true) {
                $(this).removeClass('hide_details').addClass('show_details');
                $(this).empty().html('+SNF');
                $('#published_fare_details').show();
            }
        });
    });
</script>
<?php
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js?v=' . time()), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_extra_services.js?v=2'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('flight_extra_services.css?v=1'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/pre-booking.css?v=1'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/flight-details.css?v=1'), 'media' => 'screen');
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>

<script type="text/javascript">
    /*
     session time out variables defined
     */
    var search_session_expiry = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_expiry_period'); ?>";
    var search_session_alert_expiry = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_expiry_alert_period'); ?>";
    var search_hash = "<?php echo $session_expiry_details['search_hash']; ?>";
    var start_time = "<?php echo $session_expiry_details['session_start_time']; ?>";
    var session_time_out_function_call = 1;
</script>
<script type="text/javascript">
    function addServicefee() {
        var grand_total_amount = $('#total_booking_amount').text();
        var agent_service_fee = $('#agent_service_fee').val();
        if (agent_service_fee > 0) {
            $('#agentServicefee').val(agent_service_fee);
            var total_amout = 0;
            total_amout = parseFloat(grand_total_amount) + parseFloat(agent_service_fee);
            total_amout = total_amout.toFixed(2);
            $('.grand_total_amount').text(total_amout);
        } else {
            var total_booking_amount = $('#total_booking_amount').text();
            $('.grand_total_amount').text(total_booking_amount);
            $('#agentServicefee').val(0)
            $('#agent_service_fee').val(0);
        }

    }
    $('#agent_pay_opt1').click(function() {
        $('#agent_pay_opt1').addClass('active');
        $('#agent_pay_opt2').removeClass('active');
        //$('#agent_booking_button').addClass('continue_booking_button');
        //$('#agent_booking_button_direct').addClass('continue_booking_button');
        $('#agent_booking_button_hold').show();


    });
    $('#agent_pay_opt2').click(function() {
        $('#agent_pay_opt2').addClass('active');
        $('#agent_pay_opt1').removeClass('active');
        //$('#agent_booking_button').removeClass('continue_booking_button');
        //$('#agent_booking_button_direct').removeClass('continue_booking_button');
        $('#agent_booking_button_hold').hide();
        //$('.balance_check').text(0);

    });

    $(document).ready(function() {
        var timeLeft = 1200; // 12 minutes in seconds
        var timerInterval = setInterval(function() {
            // Calculate minutes and seconds
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;

            // Format time as MM:SS
            $('#timer').text(
                (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds
            );

            // Decrease time by 1 second
            timeLeft--;

            // When the timer reaches 0, reload the page
            if (timeLeft < 0) {
                clearInterval(timerInterval);
                location.href = '<?= base_url() ?>';
            }
        }, 1000); // Update every second
    });
</script>

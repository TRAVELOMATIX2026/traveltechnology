
<?php
$book_login_auth_loading_image   = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="please wait"/></div>';
// debug($car_search_params);exit('booking view');
// echo $no_of_day;die;
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/animation.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/pre-booking.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/car-booking.css'), 'media' => 'screen');
// Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');
$template_images = $GLOBALS['CI']->template->template_images();
// debug( $car_rules);exit;
$car_details = $car_rules['RateRule']['CarRuleResult'][0];
if (isset($car_details) && valid_array($car_details)) {
  $module_value = md5('car');
  // debug($car_details);die();
  //echo $cancel_description;exit;
  $pickup_date_val = '';
  $return_date_val = '';
  $PickUpDateTime = $car_details['PickUpDateTime'];
  $pickup_date = explode('T', $PickUpDateTime);
  $pickup_date_val = date('d M Y', strtotime($pickup_date[0]));
  $pickup_time_val = date('H:i', strtotime($pickup_date[1]));
  $ReturnDateTime = $car_details['ReturnDateTime'];
  $return_date = explode('T', $ReturnDateTime);
  $return_date_val = date('d M Y', strtotime($return_date[0]));
  $return_time_val = date('H:i', strtotime($return_date[1]));
  $Vehicle_name = $car_details['Name'];
  $PictureURL = $car_details['PictureURL'];
  $CurrencyCode = $currency_obj->get_currency_symbol($currency_obj->to_currency);
  $default_currency = $currency_obj->to_currency;
  $exclusion = $car_details['PricedCoverage'];
  // debug($car_details);exit;
  $total_estimate_amount = $car_details['TotalCharge']['EstimatedTotalAmount'];
  $car_details['convenience_fees'] = $convenience_fees = $page_data['convenience_fees'];
  $total_amount = round($car_details['convenience_fees'] + $car_details['TotalCharge']['EstimatedTotalAmount'], 2);
  $total_amount = round($total_amount, 2);
  $TotalEstimateAmount = $total_amount;
  $onewayFee = $car_details['TotalCharge']['OneWayFee'];
  $TotalEstimateAmount += $onewayFee;
  // debug($total_amount);exit;
  // echo $TotalEstimateAmount;exit;
  $fuel_policy_code = '';
  $fuel_policy_desc = '';
  if (isset($car_details['PricedCoverage']) && !empty($car_details['PricedCoverage'])) {
    foreach ($car_details['PricedCoverage'] as $key => $pricedCoverage) {
      if ($pricedCoverage['Code'] == 'F2F') {
        $fuel_policy_code .= $pricedCoverage['Code'];
        $fuel_policy_desc .= @$pricedCoverage['Description'];
      }
    }
  }
}
if (is_logged_in_user()) {
  $travellers_tab_details_class = ' gohel ';
  $review_tab_details_class = '';
} else {
  $travellers_tab_details_class = ' ';
  $review_tab_details_class = ' gohel ';
}
$phone_code = $page_data['phone_code'];
$active_data = $page_data['active_data'];
$PricedCoverage = $car_details['PricedCoverage'];
$pax_title_enum = get_enum_list('title');
unset($pax_title_enum[MASTER_TITLE]); // Master is for child so not required
unset($pax_title_enum[MISS_TITLE]); // Master is for child so not required
unset($pax_title_enum[A_MASTER]); // Master is for child so not required
$country = array($page_data['active_data']['api_country_list_fk']);
$country_list = generate_options($page_data['country_list'], $country);
$datepicker_list = array(array('date-picker-dobs', CARADULT_DATE_PICKER));
//debug($datepicker_list);exit;
$this->current_page->set_datepicker($datepicker_list);
$pax_details = $page_data['pax_details'];
$user_country_code = $page_data['user_country_code'];
//debug($this->current_page);exit;
if (isset($pax_details[0]['title'])) {
  $pax_title[0] = $pax_details[0]['title'];
} else {
  $pax_title = false;
}
$pax_title_options = generate_options($pax_title_enum, $pax_title, true);
echo generate_low_balance_popup($total_amount);
// debug($pax_title_options);exit;
// debug($page_data['active_data']);
// debug($pax_details);
// exit;
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>

<div class="alldownsectn car_bkng_pge">
  <div class="container nopad">
    <div class="col-12 nopad">
    
      <!-- After Authentication Content Starts -->
		<div class="bktab2 xlbox <?=$travellers_tab_details_class?>">
      <div class="flex-div">
      <div class="col-md-4 col-sm-12 col-12 nopadding_right frmbl rit_summery sidebuki" id="sidebar">
        <div class="cartbukdis">
          
            <div class="insiefare">
             <div class="farehd arimobold">Purchase Summary</div>
             <div class="fredivs">
             <div class="booking_div">
             <div class="col-12 colrcelo nopad">
              <div class="bokkpricesml p-0 d-flex">
               <div class="travlrs col-md-6 nopad">
                <span class="portnmeter">Pick Up</span>
                <div class="fare_loc"><?=@$car_details['LocationDetails'][0]['@attributes']['Name'] ?></div>
                <div class="date_loc"><?php echo $pickup_date_val.' '.$pickup_time_val; ?></div>
               </div>
               <div class="travlrs col-md-6 nopad">
                <span class="portnmeter">Drop Off</span>
                <div class="fare_loc"><?=@$car_details['LocationDetails'][1]['@attributes']['Name'] ?></div>
                <div class="date_loc"><?php echo $return_date_val.' '.$return_time_val; ?></div>
               </div>
              
              </div>
             </div>
             <?php 
          
             if(isset($car_details['LocationDetails']) && valid_array($car_details['LocationDetails']))
             { 
              ?>
              <div class="col-12 colrcelo nopad mt-3">
              <span class="portnmeter">Business Hours</span>
               <div class="bokkpricesml p-0 d-flex">
                
                <?php foreach($car_details['LocationDetails'] as $location){ 
                  // debug($location);exit;
                  ?>
                 <span class="business_hour col-md-6 nopad">
                  <div class="loc_name"><?php echo $location['value']['Name']; ?> </div> 
               
                    <!-- <input type="text" name="buss_time_<?php echo $t_key?>" value="<?php echo $t_value; ?>">  -->
                   <div class="loc_time"><?php echo $location['OperationSchedules']['Start']; ?> to <?php echo $location['OperationSchedules']['End']; ?></div>
                 
                 </span>
                <?php } ?>
               </div>
               <?php if($cancellationPolicy['non_ref'] == false){ ?>
                  <span class="text-center non_ref"><a id="cancel_" class="cancel-policy-btn" data-bs-target="#roomCancelModal" data-bs-toggle="modal" data-cancel= '<?php echo $cancellationPolicy['policy_text']; ?>'>
                    <i class="material-icons">description</i>
                    <span>Cancellation Policy</span>
                  </a></span>
                <?php } else{ ?>
                  <span class="text-center non_ref">Non Refundable</span>
                <?php } ?>

              </div>
              <?php }?>
             
            </div>
           </div>
           </div>
           
           <div class="insiefare mt-3">
              <div class="farehd arimobold">Fare Summary</div>
              <div class="fredivs">
               <div class="kindrest">
                <div class="freshd">Price Breakup</div>
                <div class="reptallt">
                 <div class="col-8 nopadding">
                  <div class="faresty">Car Rental Price</div>
                 </div>
                 <div class="col-4 nopadding">
                  <div class="amnter"><span><?=$currency_obj->get_currency_symbol($currency_obj->to_currency)?></span> <span id="CarRentalPrice"><?php echo $total_estimate_amount; ?></span></div>
                 </div>
                </div>
                <?php if(isset($page_data['convenience_fees']) && $page_data['convenience_fees'] !='0'){?>
                <div class="reptallt">
                 <div class="col-8 nopadding">
                  <div class="faresty">Convenience Fee</div>
                 </div>
                 <div class="col-4 nopadding">
                  <div class="amnter arimobold"><span><?=$currency_obj->get_currency_symbol($currency_obj->to_currency)?></span> <span id="ConvienceFee"><?php echo $page_data['convenience_fees']; ?></span></div>
                 </div>
                </div>
                <?php } ?>
                <?php
                  // debug($car_details);exit;
                    $Coverage_amount = array();
                    if(isset($PricedCoverage) && valid_array($PricedCoverage)){
                        $in_key = 0;
                       foreach ($PricedCoverage as $p_key => $Coverage) {
                        
                       if($Coverage['Amount'] != 0){ 
                        $Coverage_amount[] = $Coverage['Amount']; 
                        if($in_key == 0){
                        ?>
                        <div class="kindrest">
                         <div class="freshd">Includes the following fees</div>
                        <?php } ?>
                        <div class="reptallt">
                         <div class="col-8 nopadding">
                          <div class="faresty"><?=$Coverage['CoverageType']?></div>
                         </div>
                         <div class="col-4 nopadding">
                          <div class="amnter arimobold"><?=$currency_obj->get_currency_symbol($currency_obj->to_currency).' '.$Coverage['Amount']?></div>
                         </div>
                        </div>
                       <?php $in_key++; 
                        }
                       }
                       if($in_key > 0){
                        ?>
                        </div>
                        <?php
                       }
                    }
                  ?>
                <div class="reptallt promo_code_discount hide">
                 <div class="col-8 nopadding">
                  <div class="faresty">Promo Code Discount</div>
                 </div>
                 <div class="col-4 nopadding">
                  <div class="amnter arimobold promo_discount_val"></div>
                 </div>
                </div>
               </div>
               <div class="clearfix"></div>
               <div class="reptalltftr">
                <div class="col-6 nopadding">
                 <div class="farestybig">Grand Total</div>
                </div>
                <div class="col-6 nopadding">
                 <div class="amnterbig arimobold"><span class="style_currency"><?=$currency_obj->get_currency_symbol($currency_obj->to_currency)?></span> <span class="discount_total grandtotal"><?php echo $total_amount; ?></span></div>
                </div>
               </div>
              </div>
             </div>
            <?php 
         
            if(isset($PricedCoverage) && valid_array($PricedCoverage) && !empty($Coverage_amount)){ 
            ?>
            <div class="insiefare">
             <div class="farehd arimobold">Pay On Pick Up</div>
             <div class="fredivs">
              <div class="kindrest">
               <div id="extras_holders">
                <?php foreach ($PricedCoverage as $p_key => $Coverage) {
                 if($Coverage['Amount'] != 0){
                 ?>
                 <div class="reptallt">
                  <div class="col-8 nopadding">
                   <div class="faresty"><?=$Coverage['CoverageType']?></div>
                  </div>
                  <div class="col-4 nopadding">
                   <div class="amnter arimobold"><?=$currency_obj->get_currency_symbol($currency_obj->to_currency).' '.$Coverage['Amount']?></div>
                  </div>
                 </div>
                 <?php } } ?>
               </div>
               <div id="extrass_holder_0"></div>
               <div id="extrass_holder_1"></div>
               <div id="extrass_holder_2"></div>
              </div>
             </div>
            </div>
           <?php } ?>
           
           <input type="hidden" id="pax_allowed" value="<?=$car_details['PassengerQuantity'] -2;?>">
     
        </div>
        <div class="insiefare mt-3 promo_sec">
        <div class="farehd arimobold">Promo Code</div>
        <div class="kindrest">
          <div class="cartlistingbuk">
            
            <div class="cartitembuk fredivs prompform">
              <div class="d-flex">
              <div class="col-md-8 col-8 nopadding_right">
                <div class="cartprc">
                  <div class="payblnhm singecartpricebuk ritaln">
                    <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode form-control" aria-required="true" />
                    <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
                    <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$total_estimate_amount;?>" />
                    <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
                    <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$CurrencyCode;?>" />
                    <input type="hidden" name="currency" id="currency" value="<?=@$default_currency;?>" />
                    <p class="error_promocode text-danger"></p>                     
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
							<?php $s=1; if(!empty($page_data['promo_codes'])){ ?>
                                <?php foreach($page_data['promo_codes'] as $promo_code){ ?>
                            <div class="promo_card">
                                <input type="radio" class="promo<?= $s ?>" name="promo" value="<?= $promo_code['promo_code']?>" onchange="setPromoCode('<?= $promo_code['promo_code']?>')">
                                <label for="promo<?= $s ?>">
                                    <h5><?= $promo_code['promo_code']?></h5>
                                    <p>Use this coupon and get  <?php if($promo_code['value_type']=='plus'){?><?= @$currency_symbol; ?>  <?= $promo_code['value'] ?> <?php }else{ echo $promo_code['value'].'%'; }?> instant discount on car bookings</p>
                                </label>
                                <a class="promo_remove<?= $s ?>" onclick="removePromoCode('<?= $promo_code['promo_code']?>')" style="display: none;">Remove</a>
                            </div>
                            <?php $s++; }?>
                            <?php }?>
						</div>
            </div>
            <div class="clearfix"></div>
            <div class="savemessage"></div>
          </div>
        </div>
      </div>
      </div>
		<div class="col-md-8 col-sm-12 col-12 nopad ">
          <form action="<?=base_url().'index.php/car/pre_booking/'.$page_data['search_id']?>" method="POST" autocomplete="off" id="pre-booking-form">
          <input type="hidden" required="required" name="search_id"   value="<?=$page_data['search_id'];?>" />
          <?php $dynamic_params_url = serialized_data($page_data['raw_car_rate_result']);?>
          <input type="hidden" required="required" name="token"   value="<?=$dynamic_params_url;?>" />
          <input type="hidden" required="required" name="token_key" value="<?=md5($dynamic_params_url);?>" />
          <input type="hidden" required="required" name="op"      value="book_room">
          <input type="hidden" required="required" name="booking_source"    value="<?=$active_booking_source;?>" readonly>
          <input type="hidden" required="required" name="promo_code_discount_val" id="promo_code_discount_val" value="0.00" readonly>
          <input type="hidden" required="required" name="promo_code" id="promocode_val" value="" readonly>

          <input type="hidden" name="total_fare_markup" value="<?= @$TotalEstimateAmount; ?>" />
            <input type="hidden" name="total_fare_tax" value="<?= ((@$convenience_fees) ? $convenience_fees : 0); ?>" />
          <div class="ontyp">
          <!-- <div class="labltowr arimobold" style="margin-top: 15px;">Please enter names as on passport. </div> -->
           	<div class="pasngr_input pasngrinput_secnrews pasngrinput _passenger_hiiden_inputs">
                    <div class="col-3 nopad">
                     
                    <div class="pad_psger">
                     <span class="formlabel nopad">Title *</span>
                      <div class="selectedwrap">
                        <select class="name_title flyinputsnor " required name="pax_title">
                        <?=$pax_title_options?>
                        </select>
                      </div>
                    </div>
                    </div>
                    <div class="col-5 nopad">
                     
                    <div class="pad_psger">
                    <span class="formlabel nopad">First Name *</span>
                        <input value="<?=@$pax_details[0]['first_name']; ?> " type="text" maxlength="45" id="first_name"  name="first_name" class="clainput  alpha_space" required placeholder="First Name">
                    </div>
                    </div>
                    <div class="col-4 nopad">
                     
                     <div class="pad_psger">
                     <span class="formlabel nopad">Last Name *</span>
                        <input value="<?=@$pax_details[0]['last_name']; ?>" type="text" maxlength="45" id="last_name"  name="last_name" class="clainput  alpha_space" required placeholder="Last Name">
                    </div>
                    </div>
                    <div class="col-4 nopad">
                     <div class="pad_psger">
                                  <span class="formlabel nopad">Country *</span>
                                  <div class="selectedwrap">
                                    <select name="country" id="country" class="mySelectBoxClass flyinputsnor">
                                      <option value="INVALIDIP">Please Select</option>
                                      <?=$country_list?>
                                    </select>
                                  </div>
                         </div>              
                    </div>
                    <div class="col-4 nopad">
                     <div class="pad_psger">
                      <span class="formlabel nopad">City *</span>
                        <input value="" type="text" maxlength="45" id="city_name"  name="city_name" class="clainput  alpha_space" required placeholder="City Name">
                    </div>
                    </div>
                     <div class="col-4 nopad">
                     <div class="pad_psger">
                     <span class="formlabel nopad">Province *</span>
                        <input value="" type="text" maxlength="45" id="state_name"  name="state_name" class="clainput  alpha_space" required placeholder="State Name">
                    </div>
                    </div>
                    <div class="col-4 nopad">
                     <div class="pad_psger">
                     <span class="formlabel nopad">Pincode *</span>
                        <input value="" type="text" maxlength="45" id="postal_code"  name="postal_code" class="newslterinput nputbrd _numeric_only" required placeholder="PostalCode">
                    </div>
                    </div>
                     <div class="col-4 nopad">
                     <div class="pad_psger">
                     <span class="formlabel nopad">Address *</span>
                        <input value="<?=@$pax_details[0]['address']?>" type="text" maxlength="45" id="address"  name="address" class="clainput  alpha_space" required placeholder="Address">
                    </div>
                    </div>
                    <div class="col-4 nopad">
                     <div class="pad_psger">
                              <span class="formlabel nopad">Date of Birth *</span>
                             
                                <input placeholder="DOB" type="text" class="clainput"  name="date_of_birth" id="date-picker-dobs" value ="<?=@$pax_details[0]['date_of_birth']?>" readonly required="" />
                              </div>
                            </div>
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
                        <select name="country_code" class="newslterinput nputbrd _numeric_only" id="after_country_code" required>
                      <?php echo diaplay_phonecode($phone_code,$active_data, $user_country_code); ?>
                    </select> 
                      </div>
                      <div class="col-1">
                        <div class="sidepo">-</div>
                      </div>
                      <div class="col-7">
                        <input value="<?=@$pax_details[0]['phone'] == 0 ? '' : @$pax_details[0]['phone'];?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="12" required="required">
                      </div>
                                             </div>

                      
                      <div class="emailperson col-5 ps-3 full_smal_forty">
                        <input value="<?=@$pax_details[0]['email']?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="notese">Your mobile number will be used only for sending flight related communication.</div>
                  </div>
        
          <div class="clearfix"></div>
          <div class="extras_toggle_section">
            <button class="extras_toggle_btn" type="button" data-bs-toggle="collapse" data-bs-target="#carExtrasCollapse" aria-expanded="false" aria-controls="carExtrasCollapse">
              <div class="toggle_text">
                <i class="material-icons">add_circle_outline</i>
                <span>EXTRAS</span>
              </div>
              <i class="material-icons arrow_icon">expand_more</i>
            </button>
            <div class="collapse" id="carExtrasCollapse">
              <div class="extras_content">
                <?php //debug($car_details['PricedEquip']);exit;
                if(isset($car_details['PricedEquip']) and valid_array($car_details['PricedEquip'])){
                ?>
                <div class="extras_options_list">
                  <?php foreach($car_details['PricedEquip'] as $key => $priced_equip){
                    // debug($car_details['PricedEquip']);exit;
                    $equip_array = array('7','8','9');
                    $icon = 'add_circle_outline'; // Default icon
                    if(!in_array($priced_equip['EquipType'], $equip_array)){
                      if($priced_equip['EquipType'] == 13){
                        $image = 'gps_icon.png';
                        $name = 'gps';
                        $icon = 'gps_fixed';
                      }
                      if($priced_equip['EquipType'] == 222){
                        $image = 'driver_icon.png';
                        $name = 'add_driver';
                        $icon = 'person_add';
                      }
                      if($priced_equip['EquipType'] == 413){
                        $image = 'driver_icon.png';
                        $name = 'full_prot';
                        $icon = 'security';
                      }
                      $image = $template_images.'/'.$image;
                    ?>
                    <div class="extras_option_item">
                      <div class="extras_checkbox_wrapper">
                        <div class="squaredThree">
                          <input type="checkbox" value="<?=$priced_equip['EquipType']?>" name="<?=$name?>" class="filter_airline_<?=$key?>" id="<?=$key?>" aria-required="true" onchange="set_extras(this,'<?=$priced_equip['Description']?>','<?=$priced_equip['Amount']?>','tr_<?=rand(999,9999)?>','checkbox');">
                          <label for="<?=$key?>" class="add_extras"></label>
                        </div>
                      </div>
                      <label for="<?=$key?>" class="extras_option_label">
                        <i class="material-icons"><?=$icon?></i>
                        <span class="extras_option_text">
                          <strong><?=$priced_equip['Description']?></strong>
                          <span class="extras_price"><?=$currency_obj->get_currency_symbol($currency_obj->to_currency) ?> <?=$priced_equip['Amount'] ?></span>
                        </span>
                      </label>
                    </div>
                  <?php }
                  if(in_array($priced_equip['EquipType'], $equip_array)){
                     $quantity = $car_details['PassengerQuantity'] -2;
                     if($priced_equip['EquipType'] == 7){
                        $extras_holder = 'Infant_equip_type_0';
                        $id = 'Infant';
                        $icon = 'child_care';
                     }
                     else if($priced_equip['EquipType'] == 8){
                        $extras_holder = 'Child_equip_type_1';
                        $id = 'Child';
                        $icon = 'child_friendly';
                     }
                     else{
                        $extras_holder = 'Booster_equip_type_2';
                        $id = 'Booster';
                        $icon = 'airline_seat_flat';
                     }
                    ?>
                    <div class="extras_option_item extras_select_item">
                      <div class="extras_select_wrapper">
                        <i class="material-icons"><?=$icon?></i>
                        <select name="<?=$id?>" id="<?=$id?>s" class="extras_select_input" onchange="set_extras(this.value,'<?=$priced_equip['Description']?>','<?=$priced_equip['Amount']?>','tr_<?=rand(999,9999)?>','select','<?=$extras_holder?>')">
                          <?php for($i=0; $i<=$quantity; $i++){ ?>
                          <option value="<?=$i?>"><?=$i?></option>
                          <?php }?>
                        </select>
                      </div>
                      <label class="extras_option_label">
                        <span class="extras_option_text">
                          <strong><?=$priced_equip['Description']?></strong>
                          <span class="extras_price"><?=$currency_obj->get_currency_symbol($currency_obj->to_currency) ?> <?=$priced_equip['Amount'] ?> Per One <?=$id ?></span>
                        </span>
                      </label>
                      <input type="hidden" id="<?=$id?>" value="0">
                    </div>
                  <?php }
                  } ?>
                </div>
                
                <?php } else{?>
                <div class="extras_no_options">
                  <i class="material-icons">info_outline</i>
                  <span>There are no extras available</span>
                </div>
                <?php } ?>
                
                <div class="extras_note">
                  <i class="material-icons">info</i>
                  <span>Charges for extras are payable at pickup</span>
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
                    <li>Car Rental Disclaimer – Self Driver Cars</li>
                    <li>The information on vehicles provided by Starlegends Adventure Cars is provided on an "as is" and "as available" basis and Starlegends Adventure Cars makes no representations or warranties, expressed or implied, to any actual or prospective purchaser or owner of any vehicle as to the existence, ownership, or condition of the vehicle or as to the accuracy or completeness of any information about the vehicle contained in this service.Starlegends Adventure Cars reserves the right to terminate any product or price at any time, in its sole discretion. You acknowledge that any reliance upon any such materials shall be at your sole risk.Starlegends Adventure Cars reserves the right, in its sole discretion, to correct any error or omission in any portion of the service or materials. Any and all concerns, differences, or discrepancies must be addressed with the Starlegends Adventure Cars prior to the sale of the vehicle.</li>

                    <li>While we try to make sure that all prices, information, etc. posted are accurate at all times, we cannot be responsible for typographical and other errors that may appear on the site. If the posted price, information, etc. for a vehicle or service is incorrect due to typographical or other error (e.g., data transmission), this dealership is NOT responsible. If we discover a mistake, error, etc. we will endeavor to provide the correction to you as soon as we become aware of it. We make every effort to provide you the most accurate, up-to-the-minute information, however it is your responsibility to verify with our dealership that all of the details listed are accurate. Vehicle images and descriptions posted on our website pages are representations provided for additional information purposes only and should not in any way be considered as factual and final. Please note that the actual vehicle may differ slightly from specifications and/or the pictures. Our dealership is not responsible for typographical, pricing, product information, advertising or other errors. Advertised prices, information, etc. are subject to change without notice. In the event a vehicle is listed at an incorrect price due to typographical, photographic, technical error, or other error, our dealership shall have the right to refuse or cancel any orders placed for the vehicle listed at the incorrect price.  You agree to release, hold harmless and indemnify Starlegends Adventure Cars and its employees, from any and all liability arising from incorrect or incomplete information regarding any vehicle. Starlegends Adventure Cars specifically disclaims any implied warranties, including the warranties of merchantability and fitness for a particular purpose regarding any vehicle with information provided in this service. All vehicles are subject to prior sales.</li>

                    <li>1. The elements listed below are merely informative and only represent the information, rules and obligations commonly required by car rental companies.</li>
                    <li>2. Each car rental agreement has its own particularities; therefore, it is important to read it carefully before signing it.</li>
                    <li>3. Booking Cars shall not be held responsible for any changes and/or requirements set out by the rental companies without prior notice. Any questions regarding these procedures and requirements shall be addressed beforehand, since they could affect the execution of the rental agreement.</li>
                    <li>4. Car Rental Agreement: The car rental agreement comprises a set of rules, information, rights and obligations between the car rental company and the lessee in regards to the services hired. The agreement shall be presented by the car rental company to the lessee at the time of pick-up, having the latter the responsibility to read its terms and conditions carefully. Some rental companies may provide the agreement in the lessee’s native language. It is the lessee’s responsibility to inquire about its availability before signing it.</li>
                    <li>5. Driver’s Minimum Age: 25 years old. Individuals between the ages of 21 and 24 with a driver’s license are subject to additional fees charged by the rental company. Also, there may be specific age limits for certain countries or vehicle categories. For more information, contact one of our experts.</li>
                    <li>6. Driver’s License: Drivers are required to present at the rental office the original copy of their national driving license, it must be valid throughout the duration of the rental period. Drivers shall only rent vehicles in the category allowed by their license. In some cases, an international driver’s license may also be required together with the license issued by the driver’s country of origin.</li>
                    <li>7. Passport: In order to execute the agreement, the lessee shall also present at the rental office a valid passport issued by the same country as the driver’s license.</li>
                    <li>8. Credit Card for Security Deposit: The lessee shall present at the rental office a valid international credit card, in the name of the lessee and with an available credit limit enough to cover the security deposit. Third-party credit cards, prepaid credit cards and credit cards not authorized for international transactions are not accepted. The credit card shall be accepted under the rental company’s discretion at the time of the execution of the agreement. The security deposit varies depending on the vehicle category and rental period, and it may be withheld by the rental company in case of damages on the rental vehicle or to pay for optional services hired directly at the rental office.</li>
                    <li>9. Restrictions: Car rental companies reserve the right to request more than one credit card with available credit to allow for the rental of certain vehicle categories. These restrictions apply at some locations; therefore, we suggest contacting the company for further information.</li>
                    <li>10. Booking Confirmation Document (Voucher): The voucher serves as proof of reservation of the vehicle, and shall be printed for its presentation at the rental office or to check the reservation information. The voucher shall only be accepted if the personal data (lessee’s name, confirmation number, dates, etc.) match the booking information at the rental office. Failure to present the voucher (Booking Confirmation Document) at the rental office may prevent the lessee from making use of the amount prepaid online, as card card-body as the right to take advantage of rates booked in advance.</li>
                    <li>11. Currency: The amount on the booking prepaid at your country of origin is, generally, expressed in local currency based on the exchange rate on the day of the purchase, since the rate’s original currency is in Canadian Dollars (CAD) OR as per client location.</li>
                    <li>12. Payment of Fees and Additional Optional Services: Any additional services, products and accessories booked by the lessee with the car rental company on the day the agreement is executed or during the rental period, are the lessee’s responsibility and their payment shall be made directly at the rental office. Once the agreement is signed and the rental period begins, the optional services booked shall not be cancelled or reimbursed.</li>
                    <li>13. No Smoking: It is strictly forbidden to smoke inside the rental vehicle. In the event of non-fulfilment, a fine may be imposed, which amount varies depending on the car rental company, thus it shall only be confirmed at destination.</li>
                    <li>15. Return of Vehicle: Failure to return the vehicle on the date indicated on the Booking Confirmation Document (Voucher), constitutes a misappropriation offense and shall result in the search and seizure of the vehicle, as card card-body as in other civil and criminal penalties.</li>
                    <li>16. Vehicle Pick-Up Policy: The car rental company shall guarantee the vehicle reservation until whichever occurs first: 29 minutes after the stipulated pick-up time or until the car rental office’s closing time. After this period, the reservation will be cancelled (No-Show). In the event of delays of any nature, including flight delays, rescheduling or overbooking, contact the car rental pick-up location immediately on the phone number printed on the voucher. If you have difficulty contacting the office, get in touch with us to examine the possibility of updating the pick-up time.</li>
                    <li>17. Vehicle Drop-Off Policy: The lessee shall return the vehicle to the rental office on the date, time and location indicated in the rental agreement. After said time, the car rental company reserves the right to make adjustment on the booking price adding penalties and/or fees as established at the time of pick-up. We suggest you arrive at the car rental drop-off location at least four hours before your boarding time. This will give you enough time to conclude the agreement and get to the airport.</li>

                    <li>18. Car Rental Office Opening Hours: The office’s opening hours are subject to change without prior notice. In case of picking up or dropping off the vehicle on a date, at a time or at a location other than the one indicated in the agreement, it will be the lessee’s responsibility to check availability with the car rental office or with Starlegends Adventure</li>
                    <li>19. Rental Extensions: If the lessee needs to extend the rental agreement, he/she should contact the rental office to request for authorization and, therefore, update the rental price.</li>
                    <li>20. Delivery and Shuttle Service: In order to make use of the delivery or shuttle service, lessee shall provide the flight information in advance. These services will only be available for car rental offices located outside the airport premises. Failure to provide this information shall result in the cancellation of the reservation without prior notice.</li>
                    <li>21. Optional Services and Accessories: The lessee may request optional items directly at the rental office based on the lessee’s needs at the time the vehicle is picked up. Additional drivers are subject to the same rules as the main driver and shall be present at the time the agreement is executed.</li>
                    <li>22. Vehicle group: Vehicles are booked and guaranteed by GROUP, not by make, year, model, color, license plate or configuration. Vehicle models may suffer variations without prior notice during the car rental company’s fleet renewal. Luggage capacity is for information purposes only and may be smaller depending on the number of passengers as card card-body as the number and size of the suitcases that need to be transported. If the number of passengers and/or suitcases surpasses the capacity of the vehicle rented, Starlegends Adventure shall not be held liable for the rejection of the reservation or any additional charges regarding the change of category. It is common practice to offer an upgrade for an additional cost at the time of pick-up. If the upgrade is accepted by the lessee, the prepaid amount will be taken as partial payment and the difference shall be charged directly on the lessee’s credit card.</li>
                    <li>23. One-Way Fee: It consists in a fee charged by the rental company when the pick-up location is different from the drop-off location. It is necessary to check in advance what locations have this option available. The fee shall be paid directly to the rental company. When the vehicle drop-off location is different from the one indicated in the rental agreement, in addition to the one-way fee, the rental company might add an additional charge by way of ‘breach of contract’, in which case the rental company may or may not accept the return of the vehicle at a different location without prior negotiation.</li>
                    <li>24. Border Restrictions: Rental companies reserve the right to charge fees for crossing borders between states or countries with their vehicles, or restrict this activity altogether. It is the responsibility of the lessee to inform the company about his/her itinerary.</li>
                    <li>25. Additional Expenses: The additional expenses not detailed in the voucher shall be charged directly to the lessee by the car rental company on the credit card used as security deposit. Some examples of additional expenses include: traffic tickets, damages on the vehicle not covered by insurance, fuel, tolls, optional insurance coverage, and accessories and services hired directly at the rental office. Other additional charges may be applied by the rental company.</li>
                    <li>26. Traffic Tickets: The lessee shall be held solely responsible for the civil and criminal liability as card card-body as the payment of any traffic tickets before the corresponding local authority. The car rental company may reserve the right to intervene or advance payments, which will then be charged to the lessee and, in which case, the rental company may also charge administrative fees.</li>
                    <li>27. Early Return of Vehicle: There will be no refunds for unused rental days.</li>
                    <li>28. Rate Update: The price on the booking corresponds to the information included in the voucher. This price may be modified when: a) changes in the itinerary, category, services and booking information occur; b) optional items or services are added or excluded; c) additional charges not included in the reservation or printed in the voucher are presented by the rental company; d) the fees to be paid at destination were altered by the car rental company. In case of disagreement with the recalculated amounts presented in the agreement, the lessee may challenge them directly with the rental company. The customer’s signature on the agreement will be deemed as an agreement between the parties, hence no additional charges may be imposed afterwards.</li>
                    <li>29. Vehicle Inspection: If possible, we recommend the lessee to pay close attention to the vehicle inspection at the time of pick-up. It is important to verify and point out the general conditions of cleanliness, hygiene, maintenance, damages and/or failures that may exist in the rental vehicle. The rental office may charge a maintenance fee for failures occurred during the rental period, as card card-body as a cleaning fee based on the rental company’s chart.</li>
                    <li>30. Vehicle Documents and Safety Features: When picking up the car, check for the vehicle’s documents and the presence of the following safety features: spare tire, jack, lug wrench, warning triangles and fuel gauge.</li>
                    <li>31. Fuel and Refueling: The vehicle will be received by the booking’s holder with fuel (normally a full tank) and shall be returned in the same conditions. If not, there will be a fuel surcharge based on the fuel price chart available at the rental office. Refueling exemption shall only apply when this condition is expressly indicated in the agreement or is purchased at the rental office when picking up the vehicle. We suggest you fuel up immediately before dropping off the vehicle, within a 5-km radius of the drop-off location, and you keep your receipt so that you have proof in case of discrepancy.</li>
                    <li>32. Items Not Included in Damages Coverage/Mandatory Insurance: The lessee shall be subject to the terms and conditions in the rental agreement. As a general rule, the damages coverage does not include, among others, the following items: accessories, damaged tires, suspension damage due to potholes or impacts, cracked windshield, damages caused by floods or other natural disasters, loss or damage of vehicle key and/or documentation. These elements may vary depending on the rental company and shall be confirmed at the time of pick-up. Carefully read the damages coverage details in your reservation. Lessee shall contact Starlegends Adventure if he/she is interested in purchasing damages coverage or in case of discrepancy in the conditions presented. Starlegends Adventure shall not be held liable for the vehicle’s loss of coverage if the vehicle is driven by non-authorized people, people under the influence of alcohol/drugs or who fail to comply with the existing traffic rules.</li>
                    <li>33. Co-insurance or deductible fees: When applicable, co-insurance or deductible fees shall be indicated in the rental agreement and, in the event of damages to the vehicle, the fee shall be charged directly to the customer by the rental company. In addition to this fee, the rental company may also charge the lessee for the period in which the vehicle could not be used due to maintenance. Inquire about these fees and charges when signing the rental agreement, since each company has its own particularities.</li>
                    <li>34. Traffic Accidents: In case of accident, theft or damage to the rental vehicle, the lessee shall contact the rental company immediately and file the corresponding police report. The lessee shall fill out the rental company’s accident report form within 24 hours of the incident.</li>
                    <li>35. Cancellation: The customer shall contact Starlegends Adventure to cancel the reservation before the specified pick-up date and time. Once cancelled, the booking shall no longer be used. The booking allows cancellation without charge up to 24 hours before the date and time of vehicle pickup. Cancellations made between 24 hours and 12 hours before the vehicle pick-up time will have a 50% penalty. Cancellations less than 12 hours before the vehicle pick-up time have a 100% penalty. If the booking is canceled by the rental company because the customer failed to meet the minimum requirements due to the customer’s non-compliance of the minimum rental requirements (minimum age required, driver’s license valid in the country of destination, valid passport, valid credit card for security deposit, printed booking confirmation, etc.) and/or non-use of the booking for another reason have a 100% penalty. If you do not show up to pick up the vehicle on the agreed date and time, there will be a 100% penalty.</li>
                    <li>36. Modifying a Reservation: All requested modifications will be subject to availability of vehicles and update of rates based on the current rate chart and the currency exchange rate at the time the modification is requested, which may be different from the one applied in the prepaid reservation.</li>

                    <li>37. Duplicate Reservations: The rental company may not allow an individual to sign more than one rental agreement for the same period of time. Therefore, if you need to make two bookings under the same name, we suggest you contact Starlegends Adventure for help.</li>
                    <li>38. No-Show: Holders of the reservation may be charged a No-Show penalty of 100%.</li>
                    <li>39. Non-availability of Vehicles: Starlegends Adventure does not assume any responsibility for the rental company’s failure to meet its obligations regarding the availability of vehicles. It is the lessee’s responsibility to inform Starlegends Adventure about any difficulty caused by the rental company at the time of signing the agreement or picking up the vehicle. Starlegends Adventure shall not be held liable for any losses, modifications, cancellations or delays due to force majeure, including but not limited to problems with airlines, civil conflicts and natural disasters.</li>
                    <li>40. Non-compliance with Rental Requirements: The rental company may refuse to execute a rental agreement if the requirements stipulated are not fulfilled, or if the individual is deemed as incapable of driving a vehicle.</li>
                    <li>41. Holder of the Reservation: The booking confirmation document (voucher) is personal and non-transferable.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
           <div class="clearfix"></div>
          <div class="clikdiv">
          <div class="squaredThree">
          <input id="terms_cond1" type="checkbox" name="tc" required="required">
          <label for="terms_cond1"></label>
          </div>
          <span class="clikagre" id="clikagre">
            <a href="<?= base_url('terms-conditions')?>" target="_blank">Terms and Conditions</a>
          </span>
        </div>
        
       
        
        <?php
            //If single payment option then hide selection and select by default
            if (count($page_data['active_payment_options']) == 1) {
              $payment_option_visibility = 'hide';
              $default_payment_option = 'checked="checked"';
            } else {
              $payment_option_visibility = 'show';
              $default_payment_option = '';
            }
            
            ?>
        <div class="row <?=$payment_option_visibility?>">
              <?php if (in_array(PAY_NOW, $page_data['active_payment_options'])) {?>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="payment-mode-<?=PAY_NOW?>">
                      <input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_NOW?>" id="payment-mode-<?=PAY_NOW?>" class="form-control b-r-0" placeholder="Payment Mode">
                      Pay Now
                    </label>
                  </div>
                </div>
              <?php } ?>
              <?php if (in_array(PAY_AT_BANK, $page_data['active_payment_options'])) {?>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="payment-mode-<?=PAY_AT_BANK?>">
                      <input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_AT_BANK?>" id="payment-mode-<?=PAY_AT_BANK?>" class="form-control b-r-0" placeholder="Payment Mode">
                      Pay At Bank
                    </label>
                  </div>
                </div>
              <?php } ?>
              </div>
              <div class="clearfix"></div>
         <div class="payrowsubmt">
         <div class="continye col-sm-4 col-6 nopad">
                        <button type="submit" id="flip" class="bookcont" name="car" class="">Continue</button>
                      </div>
          <div class="col-md-8 col-4 fulat500 nopad"> </div>
          <div class="clear"></div>
          <div class="lastnote"> </div>
         </div>
        
       </form>
       </div>
      </div> 
      </div>
      </div>     
     </div>
    </div>
   </div>
 <?php echo $GLOBALS['CI']->template->isolated_view('share/passenger_confirm_popup');?>  

<div class="modal fade cancellation-policy-modal" id="roomCancelModal" role="dialog" aria-hidden="false" style="display: none;">
   <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content cancellation-policy-content">
            <div class="modal-header cancellation-policy-header">
                <h4 class="modal-title cancellation-policy-title">
                    <i class="material-icons">description</i>
                    <span>Cancellation Policy</span>
                </h4>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="modal-body cancellation-policy-body">
                <p class="can-loader hide"></p>
                <div class="text-center loader-image hide">
                    <img src="/extras/system/template_list/template_v3/images/loader_v3.gif" alt="Loading........">
                </div>
                <div id="can-model">
                    <div class="policy_text"></div>
                </div>
            </div>
            <div class="modal-footer cancellation-policy-footer">
                <button type="button" class="btn cancellation-close-btn" data-bs-dismiss="modal">
                    <i class="material-icons">check</i>
                    <span>Close</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php 
function diaplay_phonecode($phone_code,$active_data, $user_country_code)
  {
    $list='';
    foreach($phone_code as $code){
      if(!empty($user_country_code)){
        if($user_country_code==$code['country_code']){
          $selected ="selected";
        }
        else {
          $selected="";
        }
      }
      else{
        
        if($active_data['api_country_list_fk']==$code['origin']){
          $selected ="selected";
        }
        else {
          $selected="";
        }
      }
      $list .="<option value=".$code['name']." ".$code['country_code']."  ".$selected." >".$code['name']." ".$code['country_code']."</option>";

    }
     return $list;
    
  }
  ?>
<script type="text/javascript">
function set_extras(status,title,price,row_id,tag_type,extra_select_box_id="not") {
  $('.payonpickup').removeClass('hide');
  
  if(tag_type == 'select')
  {
    status = status;
  }else if(tag_type == 'checkbox')
  {
    if(status.checked)
    {
      status = 1;
    }else
    {
      status = 0;
    }
  }
  if(status != 0){

      if(tag_type == 'select'){
        var extra_id = extra_select_box_id.split("_");
        price = price*status;
        var pax_allowed = $('#pax_allowed').val(); 
        $('#'+extra_id[0]).val(status);
        var total_pax = +$('#Infant').val() + +$('#Child').val() + +$('#Booster').val();
        
        var html = '<tr class="'+row_id+'"><td style="width:70%">'+title+'</td><td class="text-center " style="text-align: right"><?=$CurrencyCode?> '+price+'</td></tr>';
          if(total_pax > pax_allowed){
            $('#'+extra_id[0]).val(0);
            alert('Exceeds the Passenger Count');
          }
          else{
              $('#extras_holder_'+extra_id[3]).html(html);
              $('#extrass_holder_'+extra_id[3]).html(html);
          }
      }
      else{
        var html = '<tr class="'+row_id+'"><td style="width:70%">'+title+'</td><td class="text-center " style="text-align: right"><?=$CurrencyCode?> '+price+'</td></tr>';
        $('#extras_holder').append(html);
        $('#extras_holders').append(html);
      }
  }
  else{
    if(tag_type == 'select'){
      var extra_id = extra_select_box_id.split("_");
      $('#'+extra_id[0]).val(status);
    }
    $('.'+row_id).remove();
    $('.'+extra_select_box_id).remove();
  }
  var tbody = $("#extras_holder");
// alert(tbody.children().length);
  if (tbody.children().length == 0) {
     $('.payonpickup').addClass('hide');
  }
}

$(document).ready(function() {
    $(".cancel-policy-btn").on("click",function(){
      $("#can-model").html('');
      var policy_text = $(this).data('cancel');
      
      // Parse and format policy text with icons
      var formatted_html = formatCancellationPolicy(policy_text);
      
      $("#can-model").html(formatted_html);
      $(".can-loader").addClass('hide');
      $(".loader-image").addClass('hide');
      
    });
    
    // Function to format cancellation policy text with icons
    function formatCancellationPolicy(policyText) {
      if (!policyText) return '';
      
      var html = '';
      var policyLines = policyText.split('<br/>');
      
      policyLines.forEach(function(line) {
        if (line.trim() === '') return;
        
        // Parse the line: "$ 0 Amount would be charged between 2026-01-03 14:01 to 2026-01-28 09:00"
        var match = line.match(/([\$€£¥₹]?\s*[\d,]+\.?\d*)\s+Amount would be charged between\s+(.+?)\s+to\s+(.+)/i);
        
        if (match) {
          var amount = match[1].trim();
          var fromDate = match[2].trim();
          var toDate = match[3].trim();
          
          // Determine if it's free cancellation (amount is 0)
          var isFree = parseFloat(amount.replace(/[^\d.]/g, '')) === 0;
          var cardClass = isFree ? 'free-cancellation' : 'charged-cancellation';
          var icon = isFree ? 'check_circle' : 'warning';
          
          html += '<div class="policy-item-card ' + cardClass + '">';
          html += '<div class="policy-item-icon">';
          html += '<i class="material-icons">' + icon + '</i>';
          html += '</div>';
          html += '<div class="policy-item-content">';
          html += '<div class="policy-amount">';
          html += '<i class="material-icons">attach_money</i>';
          html += '<span>' + amount + ' Amount</span>';
          html += '</div>';
          html += '<div class="policy-date-range">';
          html += '<i class="material-icons">event</i>';
          html += '<span class="policy-date-item">';
          html += '<i class="material-icons" style="font-size: 14px;">schedule</i>';
          html += '<span>' + formatDate(fromDate) + '</span>';
          html += '</span>';
          html += '<span class="policy-separator">→</span>';
          html += '<span class="policy-date-item">';
          html += '<i class="material-icons" style="font-size: 14px;">schedule</i>';
          html += '<span>' + formatDate(toDate) + '</span>';
          html += '</span>';
          html += '</div>';
          if (isFree) {
            html += '<div class="policy-description">Free cancellation during this period</div>';
          } else {
            html += '<div class="policy-description">Cancellation charges apply</div>';
          }
          html += '</div>';
          html += '</div>';
        } else {
          // Fallback for non-matching format
          html += '<div class="policy-item-card">';
          html += '<div class="policy-item-icon">';
          html += '<i class="material-icons">info</i>';
          html += '</div>';
          html += '<div class="policy-item-content">';
          html += '<div class="policy-amount">' + line + '</div>';
          html += '</div>';
          html += '</div>';
        }
      });
      
      return html;
    }
    
    // Function to format date string
    function formatDate(dateStr) {
      if (!dateStr) return dateStr;
      
      // Try to parse and format the date
      try {
        var date = new Date(dateStr.replace(/(\d{4})-(\d{2})-(\d{2})\s+(\d{2}):(\d{2})/, '$1-$2-$3T$4:$5:00'));
        if (!isNaN(date.getTime())) {
          var options = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          };
          return date.toLocaleDateString('en-US', options);
        }
      } catch(e) {
        // If parsing fails, return original
      }
      
      return dateStr;
    }
   });

</script>

<script>
   function setPromoCode(promocode){
     $('#code').val(promocode);
     $("#apply").click();
   }
   function removePromoCode(promocode){
     $('#code').val();
     $("#remove").click();
   }
</script>
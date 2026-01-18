<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/pre-booking.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sightseeing-booking.css'), 'media' => 'screen');
$time_arr=array(
    '' => 'Please select',
    '00:00' => '12:00 AM',
    '00:30' => '12:30 AM',
    '01:00' => '1:00 AM',
    '01:30' => '1:30 AM',
    '02:00' => '2:00 AM',
    '02:30' => '2:30 AM',
    '03:00' => '3:00 AM',
    '03:30' => '3:30 AM',
    '04:00' => '4:00 AM',
    '04:30' => '4:30 AM',
    '05:00' => '5:00 AM',
    '05:30' => '5:30 AM',
    '06:00' => '6:00 AM',
    '06:30' => '6:30 AM',
    '07:00' => '7:00 AM',
    '07:30' => '7:30 AM',
    '08:00' => '8:00 AM',
    '08:30' => '8:30 AM',
    '09:00' => '9:00 AM',
    '09:30' => '9:30 AM',
    '10:00' => '10:00 AM',
    '10:30' => '10:30 AM',
    '11:00' => '11:00 AM',
    '11:30' => '11:30 AM',
    '12:00' => '12:00 PM',
    '12:30' => '12:30 PM',
    '13:00' => '1:00 PM',
    '13:30' => '1:30 PM',
    '14:00' => '2:00 PM',
    '14:30' => '2:30 PM',
    '15:00' => '3:00 PM',
    '15:30' => '3:30 PM',
    '16:00' => '4:00 PM',
    '16:30' => '4:30 PM',   
    '17:00' => '5:00 PM',
    '17:30' => '5:30 PM',
    '18:00' => '6:00 PM',
    '18:30' => '6:30 PM',
    '19:00' => '7:00 PM',
    '19:30' => '7:30 PM',
    '20:00' => '8:00 PM',
    '20:30' => '8:30 PM',
    '21:00' => '9:00 PM',
    '21:30' => '9:30 PM',
    '22:00' => '10:00 PM',
    '22:30' => '10:30 PM',
    '23:00' => '11:00 PM',
    '23:30' => '11:30 PM',
    
);
// debug($pre_booking_params['BookingQuestions']);
// exit;
$module_value = md5('activities');
// debug($pre_booking_params);
// exit;
$currency_symbol = $this->currency->get_currency_symbol($pre_booking_params['default_currency']);
//debug($pre_booking_params);exit;
$CI=&get_instance();
$template_images = $GLOBALS['CI']->template->template_images();
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';

$travel_date = sightseeing_travel_date($pre_booking_params['booking_date']);
$travel_date = explode('|', $travel_date);
if(is_logged_in_user()) {
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
$passport_issuing_country = INDIA_CODE;
$temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
$static_passport_details = array();
$static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
$static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
$static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));


$trip_total_price = roundoff_number($this->sightseeing_lib->total_price($pre_booking_params['markup_price_summary']));


/********************************* Convenience Fees *********************************/
$subtotal = $trip_total_price;

//$trip_total_price = roundoff_number($pre_booking_params['convenience_fees']+$trip_total_price);
/********************************* Convenience Fees *********************************/
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');

$book_login_auth_loading_image	 = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait"/></div>';
//debug($pre_booking_params);
$LastCancellationDate = $pre_booking_params['TM_LastCancellation_date'];
$current_date = date('Y-m-d');
if($current_date <$LastCancellationDate ){
	$LastCancellationDate = $LastCancellationDate;
}else{
	$LastCancellationDate = '';
}

//calculating price
$tax_total = 0;
$grand_total = 0;


$grand_total = $pre_booking_params['markup_price_summary']['TotalDisplayFare'];

//echo $total_room_price;exit;
$total_pax = 0;

//$search_params  = json_decode(base64_decode($pre_booking_params['search_params']),true);

//$age_bd = json_decode(base64_decode($search_params['age_band']),true);

$total_adult_count = 0;
$total_child_count = 0;
$total_infant_count =0;
$total_youth_count = 0;
$total_senior_count = 0;
$age_band_details_arr = array('1'=>'Adult','2'=>'Child','3'=>'Infant','4'=>'Youth','5'=>'Senior');
foreach ($pre_booking_params['AgeBands'] as $p_key => $p_value) {
	$total_pax += $p_value['count'];
	if($age_band_details_arr[$p_value['bandId']]=='Adult'){
		$total_adult_count +=$p_value['count'];
	}elseif($age_band_details_arr[$p_value['bandId']]=='Child'){
		$total_child_count += $p_value['count'];
	}elseif($age_band_details_arr[$p_value['bandId']]=='Infant'){
		$total_infant_count += $p_value['count'];
	}elseif($age_band_details_arr[$p_value['bandId']]=='Youth'){
		$total_youth_count += $p_value['count'];
	}elseif($age_band_details_arr[$p_value['bandId']]=='Senior'){
		$total_senior_count += $p_value['count'];
	}
}
$agent_commission = $pre_booking_params['Price']['AgentCommission'];
$agent_tdson_commission = $pre_booking_params['Price']['AgentTdsOnCommision'];
$total_profit = $pre_booking_params['markup_price_summary']['TotalDisplayFare']-$pre_booking_params['markup_price_summary']['NetFare']-$pre_booking_params['markup_price_summary']['_GST'];
$agent_payable = $pre_booking_params['markup_price_summary']['NetFare'];
$currency_symbol = $this->currency->get_currency_symbol($pre_booking_params['default_currency']);
echo generate_low_balance_popup($trip_total_price);
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>


<style>
   /* .fixed {
	position: fixed;
	top:60px;
	width: 100%;
	bottom: 0;
}*/
	.topssec::after{display:none;}
</style>

<input type="hidden" id="total_pax" value="<?=$total_pax?>">
<div class="fldealsec">
    <div class="container">
		<div class="tabcontnue">
			<div class="col-4 nopadding">
				<div class="rondsts <?=$review_active_class?>">
					<a class="taba core_review_tab <?=$review_tab_class?>" id="stepbk1">
						<div class="iconstatus fa fa-eye"></div>
						<div class="stausline">Review</div>
					</a>
				</div>
			</div>
			<div class="col-4 nopadding">
				<div class="rondsts <?=$travellers_active_class?>">
				<a class="taba core_travellers_tab <?=$travellers_tab_class?>" id="stepbk2">
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
<div class="alldownsectn">
	<div class="container">
  		<div class="ovrgo sight_book_page">
			
				<div class="bktab2 xlbox <?=$travellers_tab_details_class?>">
				<div class="flex-div">
				<div class="col-4 full_room_buk rhttbepa">
					<div class="sightseeing-summary-card">
						<div class="sightseeing-summary-content">
						<!-- Header -->
						<div class="summary-header">
							<i class="bi bi-ticket-perforated"></i>
							<h3>Activity Details</h3>
						</div>

						<!-- Content -->
						<div class="summary-content">
							
							<!-- Passenger Count -->
							<div class="summary-item">
								<div class="summary-label">
									<i class="bi bi-people-fill"></i>
									<span>No of Pax</span>
								</div>
								<div class="summary-value"><?=$total_pax?></div>
							</div>

							<!-- Cancellation Policy -->
							<?php if($LastCancellationDate):?>
							<div class="summary-item cancellation-item">
								<div class="summary-label">
									<i class="bi bi-shield-check"></i>
									<span>Free Cancellation till</span>
								</div>
								<div class="summary-value"><?=local_month_date($LastCancellationDate)?></div>
								<a href="#" class="policy-link" data-bs-target="#roomCancelModal" data-bs-toggle="modal">
									<i class="bi bi-info-circle"></i> View Policy
								</a>
							</div>
							<?php else:?>
							<div class="summary-item cancellation-item non-refundable">
								<div class="summary-label">
									<i class="bi bi-x-circle"></i>
									<span>Cancellation Policy</span>
								</div>
								<div class="summary-value">Non-Refundable</div>
								<a href="#" class="policy-link" data-bs-target="#roomCancelModal" data-bs-toggle="modal">
									<i class="bi bi-info-circle"></i> View Policy
								</a>
							</div>
							<?php endif;?>

							<!-- Price Breakdown -->
							<div class="price-divider"></div>

							<div class="summary-item">
								<div class="summary-label">
									<span>Total Price</span>
								</div>
								<div class="summary-value"><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($trip_total_price)?></div>
							</div>

							<div class="summary-item sub-item">
								<div class="summary-label">
									<span>Taxes & Service fee</span>
								</div>
								<div class="summary-value"><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$tax_total?></div>
							</div>

							<?php if($pre_booking_params['markup_price_summary']['_GST'] > 0){ ?>
							<div class="summary-item sub-item">
								<div class="summary-label">
									<span>GST</span>
								</div>
								<div class="summary-value"><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$pre_booking_params['markup_price_summary']['_GST'];?></div>
							</div>
							<?php } ?>

						</div>

						<!-- Grand Total -->
						<div class="summary-total">
							<div class="total-label">
								<i class="bi bi-cash-stack"></i>
								<span>Grand Total</span>
							</div>
							<div class="total-amount"><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($grand_total)?></div>
						</div>

					</div>
                    
		<div class="insiefare mt-3 promo_sec">
		<div class="farehd arimobold">Promo Code</div>			
			 <div class="kindrest">
				<div class="cartlistingbuk">
			
					<div class="clearfix"></div>
					<div class="cartitembuk fredivs prompform">
						<form name="promocode" id="promocode" novalidate>
					    <div class="d-flex">		
							<div class="col-md-8 col-8 nopadding_right">
								<div class="cartprc">
									<div class="payblnhm singecartpricebuk ritaln">
										<input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
										<!-- Promo Code Dropdown -->
										<!-- <select id="promo_code_dropdown" class="promocode" onchange="selectPromoCode()">
											<?php if (!empty($promo_codes)): ?>
												<option value="">Select a Promo Code</option>
												<?php foreach ($promo_codes as $promo): ?>
													<option value="<?= htmlspecialchars($promo['promo_code']); ?>">
														<?= htmlspecialchars($promo['promo_code']) . " - " . htmlspecialchars($promo['description']); ?>
													</option>
												<?php endforeach; ?>
											<?php else: ?>
												<option value="">No promo codes are available for this module</option>
											<?php endif; ?>
										</select> -->
										<input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
										<input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$subtotal;?>" />
										<input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
										<input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" />
										<input type="hidden" name="currency" id="currency" value="<?=@$pre_booking_params['default_currency'];?>" />
														
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
							<?php $s=1; if(!empty($promo_codes)){ ?>
                                <?php foreach($promo_codes as $promo_code){ ?>
                            <div class="promo_card">
                                <input type="radio" class="promo<?= $s ?>" name="promo" value="<?= $promo_code['promo_code']?>" onchange="setPromoCode('<?= $promo_code['promo_code']?>')">
                                <label for="promo<?= $s ?>">
                                    <h5><?= $promo_code['promo_code']?></h5>
                                    <p>Use this coupon and get  <?php if($promo_code['value_type']=='plus'){?><?= @$currency_symbol; ?>  <?= $promo_code['value'] ?> <?php }else{ echo $promo_code['value'].'%'; }?> instant discount on car bookings</p>
                                </label>
                                <a class="promo_remove<?= $s ?>" onclick="removePromoCode('<?= $promo_code['promo_code']?>')" style="display: none;">Remove</a>
                            </div>
                            <?php $s++; } } ?>
						</div>
						</form>
					</div>
					<div class="loading hide" id="loading"><img src="<?php echo $GLOBALS['CI']->template->template_images('loader_v3.gif')?>"></div>
					<div class="clearfix"></div>
					<div class="savemessage"></div>
				</div>
			</div>
            </div>

			</div>
		</div>

					<div class="col-md-8 col-12 nopad">
						<div class="col-12 nopad">
							<div class="col-12 topalldesc">
								<div class="col-12 nopad">
									<div class="bookcol">
										<div class="hotelistrowhtl m-0">
											<div class="col-md-4 nopad xcel">
												<div class="imagehotel">
													<?php if($pre_booking_params['ProductImage']!=''):?>

													<?php
														//$image = $base_url.'/'.base64_encode($pre_booking_params['HotelCode']).'/0';
														$image= $pre_booking_params['ProductImage'];
													?>

													<img alt="<?=$pre_booking_params['ProductName']?>" src="<?=$image?>">
													<?php else:?>
														<img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>" class="lazy h-img">
													<?php endif;?>
												</div>
											</div>
											<div class="col-md-8 padall10 xcel">
												<div class="hotelhed"><?=$pre_booking_params['ProductName']?></div>
												<div class="clearfix"></div>
												<div class="bokratinghotl rating-no hide">				   
													<?=print_star_rating($pre_booking_params['StarRating'])?>
												</div>
												<div class="clearfix"></div>
												<div class="hotelhed2"><?=$pre_booking_params['Remarks']?></div>
												<div class="clearfix"></div>
												<div class="mensionspl"> <?php
													if($pre_booking_params['DeparturePointAddress']){
														echo $pre_booking_params['DeparturePointAddress'];
													}elseif($pre_booking_params['DeparturePoint']){
														echo $pre_booking_params['DeparturePoint'];
													}else{
														echo $pre_booking_params['Destination'];
													} 


													?>
												</div>
												<div class="bokkpricesml p-0">
													<div class="hotelhed2"><span class="">Supplier Name:</span><?=$pre_booking_params['SupplierName'];?></div>
													<div class="hotelhed2"><span class="">Supplier VatNumber:</span><?=$pre_booking_params['SupplierPhoneNumber'];?></div>
													<div class="hotelhed2"><span class="">Provider Name:</span><?=$pre_booking_params['ProviderName'];?></div>
                                                 
												</div>
											</div>
										</div>
									</div>
								</div><!-- Outer Summary -->
								<div class="col-4 nopadding celtbcel colrcelo hide">
									
								</div>
							</div>
							<div class="clearfix"></div>
							
						</div>
		
						<div class="col-12 padpaspotr">
							<div class="col-12 nopadding">
								<div class="fligthsdets sightseeing-form">
									<?php
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate


$total_adult_count	= $total_adult_count;
$total_child_count	= $total_child_count;
$total_infant_count =$total_infant_count;
$total_youth_count = $total_youth_count;
$total_senior_count = $total_senior_count;


//------------------------------ DATEPICKER END
$total_pax_count	= $total_adult_count+$total_child_count+$total_infant_count+$total_youth_count+$total_senior_count;
//First Adult is Primary and and Lead Pax
$adult_enum = $child_enum = get_enum_list('viator_title');
$gender_enum = get_enum_list('gender');
// unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
// unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
// unset($adult_enum[MISS_TITLE]); // Miss is not supported in GRN list
// unset($child_enum[MISS_TITLE]);
// unset($child_enum[C_MRS_TITLE]);
// unset($adult_enum[A_MASTER]);
unset($adult_enum[MISS_TITLE]);
unset($child_enum[MASTER_TITLE]);
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);

$youth_title_options = $adult_title_options;
$senior_title_options = $adult_title_options;
$infant_title_options  = $child_title_options;

$gender_options	= generate_options($gender_enum);
$nationality_options = generate_options($iso_country_list, array(INDIA_CODE));//FIXME get ISO CODE --- ISO_INDIA
$passport_issuing_country_options = generate_options($country_list);
//lowest year wanted
$cutoff = date('Y', strtotime('+20 years'));
//current year
$now = date('Y');
$day_options	= generate_options(get_day_numbers());
$month_options	= generate_options(get_month_names());
$year_options	= generate_options(get_years($now, $cutoff));

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax		total pax count
 * @param number $total_adult	total adult count
 */
function is_lead_pax($pax_count)
{
	return ($pax_count == 1 ? true : false);
}
$lead_pax_details = @$pax_details[0];
 ?>
<form action="<?=base_url().'index.php/sightseeing/pre_booking/'.$search_id?>" method="POST" autocomplete="off">
	

		<div class="hide">
			<?php $dynamic_params_url = serialized_data($pre_booking_params);?>
			<input type="hidden" name="BlockTourId" value="<?=$pre_booking_params['BlockTourId']?>">
			<input type="hidden" required="required" name="token"		value="<?=$dynamic_params_url;?>" />
			<input type="hidden" required="required" name="token_key"	value="<?=md5($dynamic_params_url);?>" />
			<input type="hidden" required="required" name="op"			value="book_flight">
			<input type="hidden" required="required" name="booking_source"		value="<?=$booking_source?>" readonly>
			<input type="hidden" required="required" name="promo_code_discount_val" id="promo_code_discount_val" value="0.00" readonly>
			<input type="hidden" name="promo_code" id="promocode_val" value="" readonly>

			<input type="hidden" name="promo_actual_value" id="promo_actual_value" value="" readonly>

		</div>
		<div class="flitab1">
			<div class="moreflt boksectn">
				<div class="ontyp">
					<div class="labltowr arimobold">Please enter the customer names.</div>
					<div class="pasngrinput _passenger_hiiden_inputs">
						<?php
							
							if(is_logged_in_user()) {
								$traveller_class = ' user_traveller_details ';
							} else {
								$traveller_class = '';
							}
							$pax_index=1;

							//debug($pre_booking_params['AgeBands']);
							//echo $total_pax;
							foreach ($pre_booking_params['AgeBands'] as $p_key => $p_value) {
							if($p_key == 0){
							$name_index = 1;
							//for($pax_index=1; $pax_index <= $total_pax_count; $pax_index++) {//START FOR LOOP FOR PAX DETAILS
							//$cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();
								for($pax_index=1;$pax_index<=$p_value['count'];$pax_index++){
									if($pax_index == 1){
						?>
		
						<div class="hide hidden_pax_details">
							<?php 
								$passenger_type =1; //adult
								if($p_value['bandId']==1){
									$passenger_type = 1;
								}elseif ($p_value['bandId']==2) {
									$passenger_type = 2;//child
								}elseif ($p_value['bandId']==3) {
									$passenger_type = 3;//infant
								}
								elseif ($p_value['bandId']==4) {
									$passenger_type = 4;//youth
								}elseif ($p_value['bandId']==5) {
									$passenger_type = 5; //Senior
								}
								
							?>
							<input type="hidden" name="passenger_type[]" value="<?=$passenger_type?>">
							<input type="hidden" name="lead_passenger[]" value="<?=(is_lead_pax($p_key) ? true : false)?>">
							
						</div>
						<div class="col-12 col-sm-2 col-md-2 nopadding">
				
							<div class="adltnom">
								<?php
									$title_select_options = $adult_title_options;
									$passenger_title =1; //adult
									if($p_value['bandId']==1){
										$passenger_title = 'Adult';
									}elseif ($p_value['bandId']==2) {
										$passenger_title = 'Child';//child
										$title_select_options  = $child_title_options;
									}elseif ($p_value['bandId']==3) {
										$passenger_title = 'Infant';//infant
										$title_select_options  = $child_title_options;
									}
									elseif ($p_value['bandId']==4) {
										$passenger_title = 'Youth';//youth
									}elseif($p_value['bandId']==5) {
										$passenger_title = 'Senior'; //Senior
									}
									echo $passenger_title;
								?><?=(is_lead_pax($p_key+$pax_index) ? '- Lead Pax' : '')?>
							</div>
						
						</div>
						<div class="col-12 col-sm-10 col-md-10 nopadding">
							<div class="inptalbox">
							<div class="col-2 col-sm-2 col-md-2 spllty">
								<select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required>
								<?php echo $title_select_options;?>
								</select>
							</div>
							<div class="col-3 col-md-4 spllty">
								<input value="<?=@$cur_pax_info['first_name']?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?=$name_index?>" class="clainput alpha_space <?=$traveller_class?>"  minlength="2" maxlength="45" placeholder="Enter First Name" data-row-id="<?=($pax_index);?>"/>
								
							</div>
							<div class="col-3 col-md-4 spllty">
								<input value="<?=@$cur_pax_info['last_name']?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?=$name_index?>" class="clainput alpha_space last_name" minlength="2" maxlength="45" placeholder="Enter Last Name" />
							</div>
							<?php 
								// debug($pre_booking_params['BookingQuestions']);
								// exit;
							?>
				
						</div>
					</div>
				<div class="clearfix"></div>
				<?php
						$name_index++;
					}
						}//for loop
					}
					}//foreach

				//}//END FOR LOOP FOR PAX DETAILS
				?>
			</div>
		</div>	

		<!-- Start Hotel Pickup -->
		<div class="clearfix"></div>
		<?php
		//debug($pre_booking_params);exit;
		?>
<?php if($pre_booking_params['HotelPickup']):?>
	<div class="pasngrinput _passenger_hiiden_inputs" style="margin-top: 25px;">
		<div class="col-md-8 nopad">
		 	 <div class="col-md-3 nopad">
		 	 	<div class="labbkdiv">
                         <label>Hotel Pickup<span class="starclr">*</span></label>
                  </div>
		 	 </div>
		 	 <?php if($pre_booking_params['HotelList']):?>
		 	  <div class="col-md-7 nopad">
		 	  		<select class="form-control hotelPickup" name="hotelPickupId">
		 	  			<?php foreach($pre_booking_params['HotelList'] as $h_key=>$h_val):?>
		 	  				<option value="<?=$h_val['hotel_id']?>"><?=$h_val['hotel_name']?></option>
		 	  			<?php endforeach;?>
		 	  		</select>
		 	  </div>
		 	  	<div class="col-md-7 nopad hide offset-sm-3" id="hotelPickup_name" style="margin-top: 10px;">
                      <input type="text" class="form-control " name="hotelPickup_name" placeholder="Hotel Pickup">
              	</div>

		 	<?php else: ?>
		 		<div class="col-md-7 nopad" id="hotel_pickup_id">
                      <input type="text" class="form-control" name="hotelPickup_name" placeholder="Hotel Pickup">
              	</div>
		 	<?php endif;?>
		</div>
		</div>
<?php endif;?>
<input type="hidden" name="hotel_pickup_list_name" id="hotel_pickup_list_name" value="">
<div class="clearfix"></div>

<!--End -->			
<!-- Booking Question  Start-->
<div class="pasngrinputs-question _passenger_hiiden_inputs">
<div class="col-12 d-flex flex-wrap">
		 <?php if(valid_array($pre_booking_params['BookingQuestions'])):?>
                    <?php foreach($pre_booking_params['BookingQuestions'] as $q_key=>$q_value): $requierd='';?>
                    
                       
                        
                             <div class="col-md-6 col-sm-6 col-12 spllty">
                                   <div class="col-md-12 nopad">
                                       <div class="labbkdiv">
                                        <label><?=$q_value['title']?></label>
                                        <?php
                                        $class= ""; 
	                            		if($q_value['questionId'] == "ARRIVHOTEL"){
	                            			$class = "transferdeparturedate";
	                            		}
                                         if($q_value['required']==true){
                                            $requierd='required';
                                            echo '<span class="starclr">*</span>';
                                        }?>
                                            
                                      
                                       </div> 
                                   </div>                                 
                                   <div class="col-md-12 nopad">
                                    <div class="mr-inp-div">
                                        <input type="hidden" name="question_Id[<?=$q_key?>][]" value="<?=$q_value['questionId']?>">
										<input class="form-control clainput <?php echo $class;?>" type="text" id="<?php echo $q_value['title'];?>" <?=$requierd?> name="question[<?=$q_key?>][]" placeholder="<?php echo $q_value['title'];?>">
                                 
                                        <p><?=$q_value['subTitle']?></p>
                                    </div>  
                                   </div>
							</div>
                    
                    <?php endforeach;?>
            <?php endif;?>
			</div>
			</div>
<!-- End-->
<div class="clearfix"></div>

				<div class="clearfix"></div>
				<div class="contbk">
					<div class="contcthdngs">CONTACT DETAILS</div>
					<div class="hide">
					<input type="hidden" name="billing_country" value="92">
					<input type="hidden" name="billing_city" value="test">
					<input type="hidden" name="billing_zipcode" value="test">
					<input type="hidden" name="billing_address_1" value="test">
					</div>
					<div class="col-12 nopad d-flex m-0 full_smal_forty">
					<div class="col-7 nopad d-flex full_smal_forty">
					<div class="col-4">
					<select name="phone_country_code" class="newslterinput nputbrd _numeric_only " id="after_country_code" required>
											<?php echo diaplay_phonecode($phone_code,$active_data, $user_country_code); ?>
										</select> 
					</div>
					<div class="col-1"><div class="sidepo">-</div></div>
					<div class="col-7 nopadding">
					<input value="<?=@$lead_pax_details['phone'] == 0 ? '' : @$lead_pax_details['phone'];?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="10" required="required">
					</div>
					
					<div class="emailperson col-5 ps-3 full_smal_forty">
					<input value="<?=@$lead_pax_details['email']?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
					</div>
					</div>
					<div class="clearfix"></div>
					
				</div>
				<div class="notese">Your mobile number will be used only for sending activity related communication.</div>
				</div>

				
				<div class="temsandcndtn">
						Most countries require travelers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
						</div>
				
				<div class="clikdiv">
					 <div class="squaredThree">
					 <input id="terms_cond1" type="checkbox" name="tc" required="required">
					 <label for="terms_cond1"></label>
					 </div>
					 <span class="clikagre" id="clikagre">
					 	<a href="<?= base_url('terms-conditions')?>" target="_blank">Terms and Conditions</a>
					 </span>
				</div>
				<div class="clearfix"></div>
				
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
						<div class="row <?=$payment_option_visibility?>">
							<?php if (in_array(PAY_NOW, $active_payment_options)) {?>
								<div class="col-md-3">
									<div class="form-group">
										<label for="payment-mode-<?=PAY_NOW?>">
											<input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_NOW?>" id="payment-mode-<?=PAY_NOW?>" class="form-control b-r-0" placeholder="Payment Mode">
											Pay Now
										</label>
									</div>
								</div>
							<?php } ?>
							<?php if (in_array(PAY_AT_BANK, $active_payment_options)) {?>
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
						<div class="continye col-3">
							<button id="flip" class="bookcont" type="submit">Continue</button>
						</div>
						<div class="clearfix"></div>
						<div class="sepertr"></div>
						
					</div>
				</div>
			</div>
			</form>
			</div>
		</div>
	 	<!-- <?php if(is_logged_in_user() == true) { ?>
			<div class="col-12 nopadding psngr_lst">
				<div class="insiefare">
					<div class="farehd arimobold">Passenger List</div>
					<div class="fredivs">
						<div class="psngrnote">
							<?php
								if(valid_array($traveller_details)) {
									$traveller_tab_content = 'You have saved passenger details in your list,on typing, passenger details will auto populate.';
								} else {
									$traveller_tab_content = 'You do not have any passenger saved in your list, start adding passenger so that you do not have to type every time. <a href="'.base_url().'index.php/user/profile?active=traveller" target="_blank">Add Now</a>';
								}
							?>
							<?=$traveller_tab_content;?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?> -->
		</div>

		</div>
		</div>
        </div>
		
	</div>
 </div>
	</div>
</div>
<span class="hide">
	<input type="hidden" id="pri_journey_date" value='<?=date('Y-m-d',strtotime($search_data['from_date']))?>'>
</span>
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
				<div id="can-model">
					<div class="policy_text">
						<?php echo $pre_booking_params['TM_Cancellation_Policy']; ?>
					</div>
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
	
	// debug($phone_code);exit;
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
	
	
		$list .="<option value=".$code['country_code']."  ".$selected." >".$code['name']." ".$code['country_code']."</option>";
	}   
	 return $list;
	
}
?>
<script type="text/javascript">
	$(function(){		

		var start = new Date();
	    start.setFullYear(start.getFullYear() - 70);
	    var end = new Date();
	    end.setFullYear(end.getFullYear() - 1);

	    $(".datepickerbook" ).datepicker(
	      {
	        dateFormat: 'dd MM yy',
	        changeMonth: true,
	        changeYear: true,
	        yearRange: '1970:'+(new Date).getFullYear()    
	      });


	    $(".dobdatepickerbook").datepicker({

	    	dateFormat: 'dd MM yy',
	        changeMonth: true,
	        changeYear: true,
	        yearRange: start.getFullYear()+':'+end.getFullYear()  
	    });
	    $(".expiraydatepickerbook").datepicker({
	        dateFormat:'dd MM yy',
	        changeMonth:true,
	        changeYear:true,
	        minDate:0,
	        yearRange:'2000:'+((new Date).getFullYear()+10)
	    });
	     $(".transferdeparturedate").datepicker({
	        dateFormat:'dd MM yy',
	        changeMonth:true,
	        changeYear:true,
	      	minDate:0
	    });
	     $(".transferarrivaldate").datepicker({
	        dateFormat:'dd MM yy',
	        changeMonth:true,
	        changeYear:true,
	        minDate:0
	    });

	      $(".p-weight").on("change",function(){
	        
	        var product_key = $(this).data('key');
	        var selected_weight = $(this).val();
	        $(".weight_measure_"+product_key).val(selected_weight);
	    });
	    $(".p-height").on("change",function(){
	        
	        var product_key = $(this).data('key');
	        var selected_height = $(this).val();
	        $(".height_measure_"+product_key).val(selected_height);
	    });
	     

	 var selected_text  = $(".hotelPickup option:selected").text();
      $("#hotel_pickup_list_name").val(selected_text);

   $(".hotelPickup").on("change",function(){
        var selected_value = $(this).val();
        var selected_text  = $(".hotelPickup option:selected").text();
        $("#hotel_pickup_list_name").val(selected_text);
        if(selected_value =='notListed'){         
          $("#hotelPickup_name").removeClass('hide');
        }else{         
          $("#hotelPickup_name").addClass('hide');
        }

    });
	});
</script>
<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');?>

<script>
    // Function to handle promo code selection
    function selectPromoCode() {
        const dropdown = document.getElementById('promo_code_dropdown');
        const promoCodeInput = document.getElementById('code');
        
        // Set the selected promo code in the input field
        promoCodeInput.value = dropdown.value;
    }
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

   // Format cancellation policy on modal show
   $(document).ready(function() {
       $('#roomCancelModal').on('show.bs.modal', function() {
           var policyElement = document.querySelector('#can-model .policy_text');
           if (policyElement && policyElement.textContent.trim()) {
               var policyText = policyElement.innerHTML;
               var formatted_html = formatCancellationPolicy(policyText);
               document.getElementById('can-model').innerHTML = formatted_html;
           }
       });
   });

   // Function to format cancellation policy text with icons
   function formatCancellationPolicy(policyText) {
       if (!policyText) return '';
       
       var html = '';
       var policyLines = policyText.split(/<br\s*\/?>/i);
       
       policyLines.forEach(function(line) {
           if (line.trim() === '') return;
           
           // Parse the line: "No cancellation charges, if cancelled before 25 Feb 2026"
           // Or: "Cancellations made after 25 Feb 2026, or no-show, would be charged $ 7.42"
           var freeMatch = line.match(/No cancellation charges,?\s*if cancelled before\s+(.+)/i);
           var chargedMatch = line.match(/Cancellations made after\s+(.+?),\s*or no-show,\s*would be charged\s+([\$€£¥₹]?\s*[\d,]+\.?\d*)/i);
           
           if (freeMatch) {
               var beforeDate = freeMatch[1].trim();
               
               html += '<div class="policy-item-card free-cancellation">';
               html += '<div class="policy-item-icon">';
               html += '<i class="material-icons">check_circle</i>';
               html += '</div>';
               html += '<div class="policy-item-content">';
               html += '<div class="policy-amount">';
               html += '<i class="material-icons">check</i>';
               html += '<span>Free Cancellation</span>';
               html += '</div>';
               html += '<div class="policy-date-range">';
               html += '<i class="material-icons">event</i>';
               html += '<span class="policy-date-item">';
               html += '<span>If cancelled before ' + beforeDate + '</span>';
               html += '</span>';
               html += '</div>';
               html += '<div class="policy-description">No charges will be applied</div>';
               html += '</div>';
               html += '</div>';
           } else if (chargedMatch) {
               var afterDate = chargedMatch[1].trim();
               var amount = chargedMatch[2].trim();
               
               html += '<div class="policy-item-card charged-cancellation">';
               html += '<div class="policy-item-icon">';
               html += '<i class="material-icons">warning</i>';
               html += '</div>';
               html += '<div class="policy-item-content">';
               html += '<div class="policy-amount">';
               html += '<i class="material-icons">attach_money</i>';
               html += '<span>' + amount + ' Charged</span>';
               html += '</div>';
               html += '<div class="policy-date-range">';
               html += '<i class="material-icons">event</i>';
               html += '<span class="policy-date-item">';
               html += '<span>Cancellations after ' + afterDate + ' or no-show</span>';
               html += '</span>';
               html += '</div>';
               html += '<div class="policy-description">Cancellation charges apply</div>';
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
</script>
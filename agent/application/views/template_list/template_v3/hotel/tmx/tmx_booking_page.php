<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/pre-booking.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/hotel-booking.css'), 'media' => 'screen');
$CI = &get_instance();
$template_images = $GLOBALS['CI']->template->template_images();
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';
$hotel_checkin_date = hotel_check_in_out_dates($search_data['from_date']);
$hotel_checkin_date = explode('|', $hotel_checkin_date);
$hotel_checkout_date = hotel_check_in_out_dates($search_data['to_date']);
$hotel_checkout_date = explode('|', $hotel_checkout_date);


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
$passport_issuing_country = INDIA_CODE;
$temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
$static_passport_details = array();
$static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
$static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
$static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));
$hotel_total_price = roundoff_number($this->hotel_lib->total_price($pre_booking_params['markup_price_summary']));

/********************************* Convenience Fees *********************************/
$pre_booking_params['convenience_fees'] = $convenience_fees;
$hotel_total_price = roundoff_number($pre_booking_params['convenience_fees'] + $hotel_total_price);
/********************************* Convenience Fees *********************************/
$LastCancellationDate = $pre_booking_params['LastCancellationDate'];
$RoomTypeName = $pre_booking_params['RoomTypeName'];
$Boardingdetails = $pre_booking_params['Boarding_details'];

//calculating price
$token = $pre_booking_params['price_token'];


$tax_total = 0;
$grand_total = 0;


$tax_total += $pre_booking_params['convenience_fees'];
$tax_total += $tax_service_sum;


$gst = $pre_booking_params['markup_price_summary']['_GST'];
$hotel_total_price = roundoff_number($hotel_total_price);

$tax_total = roundoff_number($tax_total);
$grand_total = $hotel_total_price + $tax_total + $gst;
$grand_total = roundoff_number($grand_total);

//calculate total room price without tax
$total_room_price  = roundoff_number($pre_booking_params['markup_price_summary']['RoomPrice'] - $pre_booking_params['markup_price_summary']['_GST']);
$total_pax = array_sum($search_data['adult_config']) + array_sum($search_data['child_config']);

$base_url = base_url() . 'index.php/hotel/image_details_cdn';
$total_adult_count	= array_sum($search_data['adult_config']);
$total_child_count	= array_sum($search_data['child_config']);
$no_adult = 'No of Adults';
$no_child = 'No of Childs';
if ($total_adult_count == 1) {
	$no_adult = 'No of Adult';
}
if ($total_child_count == 1 || $total_child_count == 0) {
	$no_child = 'No of Child';
}

echo generate_low_balance_popup($hotel_total_price);
$user_country_code = '+91';
?>


<style>
	.topssec::after {
		display: none;
	}
</style>
<?php 
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>

<input type="hidden" id="total_pax" value="<?= $total_pax ?>">
<div class="fldealsec d-none">
	<div class="col-12">
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
<div class="alldownsectn pt-4">
	<div class="container">
  <div class="ovrgo">
	
	<div class="bktab2 xlbox <?=$travellers_tab_details_class?>">

	<div class="col-4 nopadding rit_summery">
        <div class="insiefare">
            <div class="farehd arimobold">Fare Summary</div>
            <div class="fredivs">
                <div class="kindrest">
                    <div class="freshd">Room Details</div>
                    <div class="reptallt">
                        <div class="col-6 nopadding">
                            <div class="faresty">Room Type</div>
                        </div>
                        <div class="col-6 nopadding">
                            <div class="amnter"><span class="base_fare_value0"><?=$RoomTypeName?></span></div>
                        </div>
                    </div>

					<div class="reptallt">
                        <div class="col-6 nopadding">
                            <div class="faresty">Board Type</div>
                        </div>
                        <div class="col-6 nopadding">
                            <div class="amnter">
							<?php if($Boardingdetails):?>			         		
		         		<?php  $am_arr = array();
		         			foreach ($Boardingdetails as $b_key => $b_value) {
		         				$am_arr[]=$b_value;
		         			}
                                              foreach($am_arr as $key_v=>$_val)
                                              {
                                                  echo 'Room'.($key_v+1).': '.$_val.'<br />';
                                              }
                                               //echo implode("<br />",$am_arr);
			         	?>
			        <?php else:?>
						<span class="base_fare_value0">Room Only</span>
						<?php endif;?>
					</div>
                        </div>
                    </div>

                    <div class="reptallt">
					<?php
				         $total_pax = array_sum($search_data['adult_config'])+array_sum($search_data['child_config']);
				        ?>
                        <div class="col-8 nopadding">
                            <div class="faresty">No of Guest</div>
                        </div>
                        <div class="col-4 nopadding">
                            <div class="amnter"><span class="base_fare_value1"><?=$total_pax?> Guests</span></div>
                        </div>
                    </div>

					<div class="reptallt">
			
                        <div class="col-8 nopadding">
                            <div class="faresty">No of Adults</div>
                        </div>
                        <div class="col-4 nopadding">
                            <div class="amnter"><span class="base_fare_value1"><?=array_sum($search_data['adult_config'])?> Adults</span></div>
                        </div>
                    </div>


					<div class="reptallt">
					
                        <div class="col-8 nopadding">
                            <div class="faresty">No of Child</div>
                        </div>
                        <div class="col-4 nopadding">
                            <div class="amnter"><span class="base_fare_value1"><?=array_sum($search_data['child_config'])?> Child</span></div>
                        </div>
                    </div>

					<?php if($LastCancellationDate):?>
						<div class="reptallt">
					     <div class="col-8 nopadding">
						<div class="faresty">Free Cancellation till:</div>
					</div>
					<div class="col-4 nopadding">
						<div class="amnter"><span class="base_fare_value1"><?=local_month_date($LastCancellationDate)?></span><br>
						<a  href="#" data-bs-target="#roomCancelModal" data-bs-toggle="modal">View Cancellation Policy</a>
					</div>
					</div>
				</div>

                <?php else:?>
					<div class="reptallt">
					
                        <div class="col-6 nopadding">
                            <div class="faresty">Cancellation Policy:</div>
                        </div>
                        <div class="col-6 nopadding">
                            <div class="amnter"><span class="base_fare_value1">Non-Refundable<br>
							<a style="color: #007bff; font-size: 14px;" href="#" data-bs-target="#roomCancelModal" data-bs-toggle="modal">View Cancellation Policy</a>
						</span></div>
                        </div>
                    </div>
				<?php endif;?>

                </div>

                <div class="kindrest">
                    <!--div class="freshd">Taxes</div-->
                    <div class="reptallt">
                        <div class="col-8 nopadding">
                            <div class="faresty">Total Room Price</div>
                        </div>
                        <div class="col-4 nopadding">
                            <div class="amnter arimobold">
							<?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($total_room_price)?>
							</div>
                        </div>
                        <div class="col-8 nopadding">
                            <div class="faresty">Taxes & Service fee</div>
                        </div>
                        <div class="col-4 nopadding">
                            <div class="amnter arimobold"><span class="tax_fees_value"><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$tax_total?> </span></div>
                        </div>
                       
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="reptalltftr">
                    <div class="col-6 nopadding">
                        <div class="farestybig">Grand Total</div>
                    </div>
                    <div class="col-6 nopadding ">
                        <div class="amnterbig arimobold"><span class="total_booking_amount grandtotal_value grandtotal"> <?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($grand_total)?></span> </div>
                       
                    </div>
                </div>
            </div>
        </div>

		<div class="ontyp mt-3">
										<div class="kindrest">
										<div class="cartlistingbuk">
													
													<div class="clearfix"></div>
													<div class="cartitembuk prompform">
														<form name="promocode" id="promocode" novalidate>
															<div class="d-flex" >
									                    <div class="col-md-8 col-6 nopadding_right">
									                      <div class="cartprc">
									                        <div class="payblnhm singecartpricebuk ritaln">
									                    	 <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
									                          <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
									                          <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$grand_total;?>" />
									                          <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
									                          <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" />
									                          <input type="hidden" name="currency" id="currency" value="<?=@$pre_booking_params['default_currency'];?>" />
									                         
									                         <p class="error_promocode text-danger" style="font-weight:bold"></p>                                          
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
                                                    <div class="promo_card" >
                                                        <input type="radio"  class="promo<?= $s ?>" name="promo" value="<?= $promo_code['promo_code'] ?>" onchange="setPromoCode('<?= $promo_code['promo_code'] ?>')">
                                                        <label for="promo<?= $s ?>">
                                                            <h5><?= $promo_code['promo_code'] ?></h5>
                                                            <p>Use this coupon and get <?php if ($promo_code['value_type'] == 'plus') { ?><?= @$currency_symbol; ?> <?= $promo_code['value'] ?> <?php } else { } ?>
                                                        </label>
                                                        <a  class="promo_remove<?= $s ?> " onclick="removePromoCode('<?= $promo_code['promo_code'] ?>')" style="display:none;">Remove</a>
                                                    </div>
                                                <?php $s++; } ?>
                                            <?php } ?>
                                            
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

	  <div class="col-12 col-md-8 nopad">
		<div class="col-12 topalldesc nopad">
			<div class="col-12 nopad">
				<div class="bookcol">
			  <div class="hotelistrowhtl">
				<div class="col-md-4 nopad xcel">
				  <div class="imagehotel">
				  		<?php if(!empty($pre_booking_params['HotelImage'])){?>
								  		<img alt="<?=$pre_booking_params['HotelName']?>" src="<?php echo $pre_booking_params['HotelImage']?>">
						  	<?php }else{?>
						  		<img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('default_hotel_img.jpg')?>" class="lazy h-img">
						  	<?php }?>
				  </div>
				</div>
				<div class="col-md-8 padall10 xcel">
				  <div class="hotelhed"><?=$pre_booking_params['HotelName']?></div>
				  <div class="clearfix"></div>
				  <div class="bokratinghotl rating-no">				   
					  <?=print_star_rating($pre_booking_params['StarRating'])?>
				  </div>
				  <div class="clearfix"></div>
				  <div class="mensionspl"> <?=$pre_booking_params['HotelAddress']?> </div>
				  <div class="sckint">
			  <div class="ffty">
				<div class="borddo brdrit"> 
				  <div class="fuldate_book"> <span class="bigdate_book"><?=$hotel_checkin_date[0]?></span>
					<div class="biginre_book"> <?=$hotel_checkin_date[1]?><br>
					  <?=$hotel_checkin_date[2]?> </div>
				  </div>
				</div>
			  </div>
			  <div class="ffty">
				<div class="borddo">
				  <div class="fuldate_book"> <span class="bigdate_book"><?=$hotel_checkout_date[0]?></span>
					<div class="biginre_book"> <?=$hotel_checkout_date[1]?><br>
					  <?=$hotel_checkout_date[2]?> </div>
				  </div>
				</div>
			  </div>
			  <div class="clearfix"></div>
			  <div class="nigthcunt">Night(s) <?=$search_data['no_of_nights']?>, Room(s) <?=$search_data['room_count']?></div>
			</div>
				</div>
			  </div>
			</div>
			</div><!-- Outer Summary -->
		
		</div>
		<div class="col-12 padpaspotr p-0">
		<div class="col-12 nopadding">
		<div class="fligthsdets p-0" style="background: none !important; border: none !important; box-shadow: none !important;">
		<?php
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
$total_adult_count	= array_sum($search_data['adult_config']);
$total_child_count	= array_sum($search_data['child_config']);

//------------------------------ DATEPICKER START
$i = 1;
$datepicker_list = array();
if ($total_adult_count > 0) {
	for ($i=1; $i<=$total_adult_count; $i++) {
		$datepicker_list[] = array('adult-date-picker-'.$i, ADULT_DATE_PICKER);
	}
}

if ($total_child_count > 0) {
	for ($i=$i; $i<=($total_child_count+$total_adult_count); $i++) {
		$datepicker_list[] = array('child-date-picker-'.$i, CHILD_DATE_PICKER);
	}
}
$GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
//------------------------------ DATEPICKER END
$total_pax_count	= $total_adult_count+$total_child_count;
//First Adult is Primary and and Lead Pax
$adult_enum = $child_enum = get_enum_list('title');
$gender_enum = get_enum_list('gender');
unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
unset($adult_enum[MISS_TITLE]); // Miss is not supported in GRN list
unset($child_enum[MISS_TITLE]);
unset($child_enum[C_MRS_TITLE]);
unset($adult_enum[A_MASTER]);
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);
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
function is_adult($total_pax, $total_adult)
{
	return ($total_pax>$total_adult ?	false : true);
}

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
		<form action="<?=base_url().'hotel/pre_booking/'.$search_data['search_id']?>" method="POST" autocomplete="off">
		<div class="hide">
			<?php $dynamic_params_url = serialized_data($pre_booking_params);?>
			<input type="hidden" required="required" name="token"		value="<?=$dynamic_params_url;?>" />
			<input type="hidden" required="required" name="token_key"	value="<?=md5($dynamic_params_url);?>" />
			<input type="hidden" required="required" name="op"			value="book_flight">
			<input type="hidden" required="required" name="booking_source"		value="<?=$booking_source?>" readonly>
			<input type="hidden" required="required" name="promo_code_discount_val" id="promo_code_discount_val" value="0.00" readonly>
			<input type="hidden" required="required" name="promo_code" id="promocode_val" value="" readonly>
			<input type="hidden" required="required" name="promo_actual_value" id="promo_actual_value" value="" readonly>
		</div>
			 <div class="flitab1">
			<div class="moreflt boksectn">
					<div class="ontyp">
						<div class="labltowr arimobold">Please enter the customer names.</div>

<?php
	$child_age = @$search_data['child_age'];
	$search_child_age = @$search_data['child_age'];
	if(is_logged_in_user()) {
		$traveller_class = ' user_traveller_details ';
	} else {
		$traveller_class = '';
	}
	$child_age_index=0;
	for($pax_index=1; $pax_index <= $total_pax_count; $pax_index++) {//START FOR LOOP FOR PAX DETAILS
	$cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();
?>
	<div class="pasngrinput _passenger_hiiden_inputs">
		<div class="hide hidden_pax_details">
		<?php
		if(is_adult($pax_index, $total_adult_count) == true) {
			 $static_date_of_birth = date('Y-m-d', strtotime('-30 years'));;
			 } else {//child
			 	$static_date_of_birth = date('Y-m-d', strtotime('-'.intval(array_shift($child_age)).' years'));;
			 	$child_age_index++;
			 }
			 $passport_number = rand(1111111111,9999999999);
		  ?>
			<input type="hidden" name="passenger_type[]" value="<?=(is_adult($pax_index, $total_adult_count) ? 1 : 2)?>">
			<input type="hidden" name="lead_passenger[]" value="<?=(is_lead_pax($pax_index) ? true : false)?>">
			<input type="hidden" name="date_of_birth[]" value="<?=$static_date_of_birth?>">
			<input type="hidden" name="gender[]" value="1" class="pax_gender">
			<input type="hidden" required="required" name="passenger_nationality[]" id="passenger-nationality-<?=$pax_index?>" value="92">
			<!-- Static Passport Details -->
			<input type="hidden" name="passenger_passport_number[]" value="<?=$passport_number?>" id="passenger_passport_number_<?=$pax_index?>">
			<input type="hidden" name="passenger_passport_issuing_country[]" value="<?=$passport_issuing_country?>" id="passenger_passport_issuing_country_<?=$pax_index?>">
			<input type="hidden" name="passenger_passport_expiry_day[]" value="<?=$static_passport_details['passenger_passport_expiry_day']?>" id="passenger_passport_expiry_day_<?=$pax_index?>">
			<input type="hidden" name="passenger_passport_expiry_month[]" value="<?=$static_passport_details['passenger_passport_expiry_month']?>" id="passenger_passport_expiry_month_<?=$pax_index?>">
			<input type="hidden" name="passenger_passport_expiry_year[]" value="<?=$static_passport_details['passenger_passport_expiry_year']?>" id="passenger_passport_expiry_year_<?=$pax_index?>">
		</div>
		<div class="col-2 nopadding">
			<?php
				if($search_child_age){
					if($child_age_index>0){
						$child_yr = '';
						if(isset($search_child_age[$child_age_index-1])){
							if($search_child_age[$child_age_index-1]>1){
								$child_yr = '<br/>'.' '.$search_child_age[$child_age_index-1].' years old';
							}else{
								$child_yr = '<br/>'.$search_child_age[$child_age_index-1].' year old';
							}
						}
					}
					
					
				}
			?>
		   <div class="adltnom"><?=(is_adult($pax_index, $total_adult_count) ? 'Adult' : 'Child' .$child_yr )?><?=(is_lead_pax($pax_index) ? '- Lead Pax' : '')?></div>
		
		 </div>
		 <div class="col-10 nopadding">
		 <div class="inptalbox">
			<div class="col-3 spllty">
			<div class="selectedwrap">
			<select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required>
			<?php echo (is_adult($pax_index, $total_adult_count) ? $adult_title_options : $child_title_options)?>
			</select>
			</div>
			</div>
			<div class="col-4 spllty">
				  <input value="<?=@$cur_pax_info['first_name']?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?=$pax_index?>" class="clainput alpha_space <?=$traveller_class?>"  minlength="2" maxlength="45" placeholder="Enter First Name" data-row-id="<?=($pax_index);?>"/>
				  <input type="hidden" class="hide"  maxlength="45" name="middle_name[]">
			</div>
			<div class="col-4 spllty">
			 	<input value="<?=@$cur_pax_info['last_name']?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?=$pax_index?>" class="clainput alpha_space last_name" minlength="2" maxlength="45" placeholder="Enter Last Name" />
			 </div>
		</div>
		</div>
	</div>
<?php
}//END FOR LOOP FOR PAX DETAILS
?>
					</div>
				</div>
				<!---Added by ela-->
				<div>
					<?php 
					   if($RateComments){
					     echo "<div class='labltowr'>RateComments </div>";
					   	 foreach ($RateComments as $r_key => $r_value) {
					   	 	echo "<p>".$r_value."</p>";
					   	 }
					   }
					?>
				</div>
				<!--End -->
				
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
					</div>
					<div class="emailperson col-5 ps-3 full_smal_forty">
					<input value="<?=@$lead_pax_details['email']?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
					</div>
					
					
					</div>
					<div class="clearfix"></div>
					<div class="notese">Your mobile number will be used only for sending hotel related communication.</div>
				</div>
				<div class="clikdiv">
					 <div class="squaredThree">
					 <input id="terms_cond1" type="checkbox" name="tc" checked="checked" required="required">
					 <label for="terms_cond1"></label>
					 </div>
					 <span class="clikagre">
					 	<a href="<?= base_url('terms-conditions')?>" target="_blank">Terms and Conditions</a>
					 </span>
				</div>
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
						
						<div class="temsandcndtn">
						Most countries require travelers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
						</div>
					</div>
				</div>
			</div>
			</form>
			</div>
		</div>
	 	<!-- <?php if(is_logged_in_user() == true) { ?>
			<div class="col-12 nopadding">
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
<span class="hide">
	<input type="hidden" id="pri_journey_date" value='<?=date('Y-m-d',strtotime($search_data['from_date']))?>'>
</span>
<div class="modal fade bs-example-modal-lg" id="roomCancelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h5 class="modal-title" id="myModalLabel">Cancellation Policy</h5>
				
				<div class="imghtltrpadv hide">
				  <img src="" id="trip_adv_img">
				</div>
			</div>
			<div class="modal-body">
				
				<p class="policy_text"><?php echo array_shift($pre_booking_params['CancellationPolicy']); ?></p>

				
			</div>
			<div class="modal-footer">
	          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        </div>
		</div>
	</div>
</div>
<script>
    function setPromoCode(promocode) {
        $('#code').val(promocode);
        $("#apply").click();
       
    }

    function removePromoCode(promocode) {
        $('#code').val();
        $("#remove").click();
       
    }
</script>
</script>
<script type="text/javascript">
	$(document).ready(function(){
    $(document).on('scroll', function(){
        if ($('#slidebarscr')[0].offsetTop < $(document).scrollTop()){
        	var top = $(document).scrollTop();
        	var height = $(window).height();
        	/*bottom height*/
        	var scrollHeight = $(document).height();
			var scrollPosition = $(window).height() + $(window).scrollTop();
			var bottom = (scrollHeight - scrollPosition) / scrollHeight;
			
			//console.log("bottom"+bottom);
			// if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
			//     // when scroll to bottom of the page
			// }
        	//alert(height);
        	//alert("bottomsssss"+bottom);
        	if((top >= 150) && (bottom >=0.25))  // || (height > 300))
        	{
        		//alert(bottom+top);
        		$("#slidebarscr").css({position: "fixed", top:0});  	
        	}else 
        	{
        		//alert('bottom '+top);
        		$("#slidebarscr").css({position: "",top:0});  	
        	} 
        	/*else if((top >= 285) && (top < 300))
        	{
        		$("#slidebarscr").css({position: "fixed", top:0});  	
        	}*/
        	                     
        }
    });  
});
</script>
<script type="text/javascript">
	$(document).ready(function(){
    $(document).on('scroll', function(){
        if ($('#nxtbarslider')[0].offsetTop < $(document).scrollTop()){
        	var top = $(document).scrollTop();
        	var height = $(window).height();
        	var scrollHeight = $(document).height();
			var scrollPosition = $(window).height() + $(window).scrollTop();
			var bottom = (scrollHeight - scrollPosition) / scrollHeight;
        	//alert(top);
        	if(((top >= 243) || (height < 300)) && (bottom >=0.2))
        	{
        		//alert(top);
        		$("#nxtbarslider").css({position: "fixed", top:0});  	
        	}else  
        	{
        		$("#nxtbarslider").css({position: "", top:0});  	
        	}         
            
        }
    });  
});
</script>

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
<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');?>
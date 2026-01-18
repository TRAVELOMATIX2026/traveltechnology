<style>
	.customteam2.nav-tabs.customteam li a {
    background: #f99a3e none repeat scroll 0 0;}
	.customteam2.nav-tabs.customteam li.active a{
		background: #f36839;
    color: #fff;
	}
	.icon_sml_mob.fa {
    color: #fff;}
	.customteam2.nav-tabs.customteam li a strong {
    color: white;}
</style>
<?php 
	$get_month_names =array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Mar',
		4 => 'Apr',
		5 => 'May',
		6 => 'Jun',
		7 => 'Jul',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec'
);
?>
<div class="content-wrapper dashboard_section">
<div class="container">
<div class="staffareadash"> 
<?php echo $GLOBALS['CI']->template->isolated_view('share/profile_navigator_tab') ?>
<div class="tab-content sidewise_tab">
<div role="tabpanel" class="tab-pane <?php echo ((isset($_GET['active']) == false || @$_GET['active'] == 'dashboard'))? 'active' : ''?>" id="dashbrd">
<div class="trvlwrap">
	<div class="seperate_shadow">
		<h3 class="welcmnotespl">Hi,
			<?=$full_name?>
		</h3>
		<div class="smlwel">All your trips booked with us will appear here and you'll be able to manage everything!</div>
			<div class="bokinstts">
				<?php if(is_active_airline_module()):?>	
				<div class="col-3 nopad">
					<div class="insidebx color1">
				<div class="ritlstxt">
					<div class="contbokd">
						<?=$booking_counts['flight_booking_count']?>
					</div>Flights booked </div>
					<span class="witbook fa fa-plane"></span> <a href="<?=base_url().'index.php/report/flights'?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>
				</div>
				<?php endif;?>
				<?php if(is_active_hotel_module()):?>
				<div class="col-3 nopad">
				<div class="insidebx color2">
				<div class="ritlstxt">
					<div class="contbokd">
						<?=$booking_counts['hotel_booking_count']?>
					</div>Hotel booked </div>
					<span class="witbook fa fa-bed"></span> <a href="<?=base_url().'index.php/report/hotels'?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>
				</div>
				<?php endif;?>
				<?php if(is_active_bus_module()):?>
				<div class="col-3 nopad">
				<div class="insidebx color3">
				<div class="ritlstxt">
					<div class="contbokd">
						<?=$booking_counts['bus_booking_count']?>
					</div>Buses booked </div>
				<span class="witbook fa fa-bus"></span> <a href="<?=base_url().'index.php/report/buses'?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>
				</div>
				<?php endif;?>
				<?php if(is_active_transferv1_module()):?>
					<div class="col-3 nopad">
				<div class="insidebx color4">
				<div class="ritlstxt">
					<div class="contbokd">
						<?=$booking_counts['transfer_booking_count']?>
					</div>Transfers booked </div>
				<span class="witbook fa fa-taxi"></span> <a href="<?=base_url().'index.php/report/transfers'?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>
				</div>
				<?php endif;?>
				<?php if(is_active_sightseeing_module()):?>
				<div class="col-3 nopad">
				<div class="insidebx color5">
				<div class="ritlstxt">
					<div class="contbokd">
						<?=$booking_counts['sightseeing_booking_count']?>
					</div>Activities booked </div>
				<span class="witbook fa fa-binoculars"></span> <a href="<?=base_url().'index.php/report/activities'?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>
				</div>
			<?php endif;?>
			<?php if(is_active_car_module()):?>
				<div class="col-3 nopad">
				<div class="insidebx color6">
				<div class="ritlstxt">
					<div class="contbokd">
						<?=$booking_counts['car_booking_count']?>
					</div>Car booked </div>
				<span class="witbook fa fa-taxi"></span> <a href="<?=base_url().'index.php/report/car'?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>
				</div>
			<?php endif;?>
			</div>
			</div>
			<div class="clearfix"></div>
			<div class="retnset">
			<div class="col-6 nopad full_nty">
				<div class="pading_spl">
				<div class="spl_box">
				<h4 class="dskrty">Recent Acivities</h4>
					<div class="backfully">
					<?php
						if(valid_array($latest_transaction) == true) {
							foreach($latest_transaction as $lt_k => $lt_v) {
								switch($lt_v['transaction_type']) {
									case 'flight':
										$icon = 'plane';
										$boking_source = PROVAB_FLIGHT_BOOKING_SOURCE;
										break;
									case 'hotel':
										$icon = 'bed';
										$boking_source = PROVAB_HOTEL_BOOKING_SOURCE;
										break;
									case 'bus':
										$icon = 'bus';
										$boking_source = PROVAB_BUS_BOOKING_SOURCE;
										break;
									case 'sightseeing':
										$icon = 'binoculars';
										$boking_source = PROVAB_SIGHTSEEN_BOOKING_SOURCE;
										break;
									case 'transferv1':
										$icon = 'taxi';
										$boking_source = PROVAB_TRANSFERV1_BOOKING_SOURCE;
										break;
									case 'car':
										$icon = 'car';
										$boking_source = PROVAB_CAR_BOOKING_SOURCE;
										break;
								}
					?>
					<a target="_blank" href="<?=base_url();?>index.php/voucher/<?=$lt_v['transaction_type']?>/<?=$lt_v['app_reference']?>/<?=$boking_source?>">
						<div class="rownotice2">
						<div class="col-2 nopad5">
						<div class="lofa2 fa fa-<?=$icon?>"></div>
						</div>
						<div class="col-7 nopad5">
							<div class="noticemsg2">
								<?=$lt_v['app_reference']?>
							<strong>
								<?=app_friendly_absolute_date($lt_v['created_datetime'])?>
							</strong> </div>
						</div>
						<div class="col-3 nopad5"> <span class="yrtogo2">
							<?=$currency_obj->get_currency_symbol($lt_v['currency'])?>
						<?=$lt_v['grand_total']?>
						</span> </div>
					</div>
					</a>
					<?php } 
											} else { ?>
					<div class="col-md-12">
						<center>
						No Activities Found
						</center>
					</div>
					<?php } ?>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="clearfix"></div>
		</div>
		</div>
		<div role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'profile')? 'active' : ''?>" id="profile">
		<div class="dashdiv">
			<div class="alldasbord">
			<div class="userfstep">
				<div class="step_head">
				<h3 class="welcmnote">Hi,
					<?=$full_name?>
				</h3>
				<a href="#edit_user_profile" data-aria-controls="home" data-role="tab" data-bs-toggle="tab" class="editpro">Edit profile</a> </div>
				<div class="clearfix"></div>
				<!-- Edit User Profile starts-->
				<div class="tab-content">
				<div role="tabpanel filldiv" class="tab-pane active" id="show_user_profile">
					<div class="colusrdash"> <img src="<?=(empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($profile_image) : $GLOBALS['CI']->template->template_images('user.png'))?>" alt="profile Image" /> </div>
					<div class="useralldets">
					<h4 class="dashuser">
						<?=$full_name?>
					</h4>
					<div class="rowother"> <span class="far fa-envelope"></span> <span class="labrti">
						<?=(empty($email) == true ? '---' : $email)?>
						</span> </div>
					<div class="rowother"> <span class="far fa-phone"></span> <span class="labrti">
						<?=(($phone == 0 || $phone == '') ? '---':$mobile_code.' '.$phone)?>
						</span> </div>
					<div class="rowother"> <span class="far fa-map-marker"></span> <span class="labrti">
						<?=(empty($address) == true ? '---' : $address)?>
						</span> </div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="edit_user_profile">
					<form action="<?=base_url().'index.php/user/profile?active=profile'?>" method="post" name="edit_user_form" id="edit_user_form" enctype="multipart/form-data" autocomplete="off">
					<div class="infowone">
						<div class="clearfix"></div>
						<div class="paspertorgn2 paspertedit">
						<div class="col-3 margpas">
							<div class="tnlepasport">
							<div class="paspolbl cellpas">Title <span class="text-danger">*</span></div>
							<div class="lablmain cellpas">
								<select name="title" class="clainput" required="required">
								<?=generate_options(get_enum_list('title'), (array)$title)?>
								</select>
							</div>
							</div>
						</div>
						<div class="col-4 margpas">
							<div class="tnlepasport">
							<div class="paspolbl cellpas">FirstName <span class="text-danger">*</span></div>
							<div class="lablmain cellpas">
								<input type="text" name="first_name" placeholder="first name" value="<?=$first_name?>" class="clainput alpha" maxlength="45" required />
							</div>
							</div>
						</div>
						<div class="col-5 margpas">
							<div class="tnlepasport">
							<div class="paspolbl cellpas">LastName <span class="text-danger">*</span></div>
							<div class="lablmain cellpas">
								<input type="text" name="last_name" placeholder="last name" value="<?=$last_name?>" class="clainput alpha" maxlength="45" required="required"/>
							</div>
							</div>
						</div>
						<div class="col-3 margpas">
							<div class="tnlepasport">
							<div class="paspolbl cellpas">CountryCode<span class="text-danger">*</span></div>
							<div class="lablmain cellpas">
								<select name="country_code" class="clainput" required="required">
								<option value = '<?=$user_country_code?>' ><?=$user_country_code?></option>
								<?=generate_options($phone_code_array, (array)$user_country_code)?>
								</select>
							</div>
							</div>
						</div>
						<div class="col-4 margpas">
							<div class="tnlepasport">
							<div class="paspolbl cellpas">MobileNumber <span class="text-danger">*</span></div>
							<div class="lablmain cellpas">
								<input type="text" name="phone" placeholder="mobile number" value="<?=(($phone == 0 || $phone == '') ? '': $phone)?>" class="clainput numeric" required="required"/ maxlength="10">
							</div>
							</div>
						</div>
						<div class="col-5 margpas">
							<div class="tnlepasport">
							<div class="paspolbl cellpas">Address <span class="text-danger">*</span></div>
							<div class="lablmain cellpas">
								<textarea name="address" placeholder="address" class="clainput" required="required"><?=$address?></textarea>
							</div>
							</div>
						</div>
						<div class="col-5 margpas">
		                    <div class="tnlepasport">
		                        <div class="paspolbl cellpas">ProfileImage</div>
		                        <div class="lablmain cellpas">
		                            <input type="file" name="image" accept="image/*"/>
		                        </div>
		                    </div>
						</div>
						<div class="clearfix"></div>
						<button type="submit" class="savepspot">Update</button>
						<a href="#show_user_profile" data-aria-controls="home" data-role="tab" data-bs-toggle="tab" class="cancelll">Cancel</a> </div>
					</div>
					</form>
				</div>
				</div>
				<!-- Edit User Profile Ends-->
			</div>
			<div class="clearfix"></div>
			</div>
		</div>
		</div>
		<div role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'coupon')? 'active' : ''?>" id="coupon">
		<div class="dashdiv">
			<div class="alldasbord">
			<div class="userfstep">
				<div class="step_head">
				<!-- <h3 class="welcmnote">Hi,
					<?=$full_name?>
				</h3> -->
				<h3 class="welcmnote">Special Discount Offer</h3>
				<!-- Edit User Profile starts-->
				<div class="tab-content">
					
					<div class="discount-offer-wrapper">
						<div class="col-md-6 col-12 nopad">
							<?php 
							// debug($promo);exit;
							if($promo['status'] == true): ?>
								<div class="discount-offer-card">
									<!-- <div class="offer-header">
										Special Discount Offer
									</div> -->
									<div class="offer-body">
										<span class="status <?= $promo['promo_code_remaining_value'] != 0 ? 'active' : 'inactive'; ?>">
										<?= $promo['status'] == 1 ? 'Active' : 'Inactive'; ?>
										</span>
										<div class="offer-image">
											<img src="<?php echo base_url()?>extras/system/template_list/template_v1/images/promocode/<?= $promo['promo_code_image']; ?>" alt="<?= $promo['alt_text']; ?>">
										</div>
										<div class="offer-details">
											<p class="offr_dscrptn">
												<strong>
												<!-- Description: -->
												<?= $promo['description']; ?>
											</strong>
											</p>
											<!-- <p><strong>Discount Value:</strong>
												<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
													<?=isset($promo['value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['value'])):0 . ' ' . ($promo['value_type'] == 'plus' ? 'Off' : '% Off'); ?>
											</p>
											<p><strong>Minimum Purchase Amount:</strong>
												<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
													<?php echo isset($promo['minimum_amount'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['minimum_amount']) ):0; ?>
											</p> -->
											<p><strong>Coupon Amount:
												<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
													<?php echo isset($promo['promo_code_total_value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['promo_code_total_value']) ):0; ?></strong>
											</p>
											<?php $remain_val = $currency_obj->get_currency_symbol($currency_obj->to_currency).( isset($promo['promo_code_remaining_value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($promo['promo_code_remaining_value']) ):0); ?>
											<p class="remng_offr">
												<!-- <strong>Remaining Value:</strong> -->
												<span>You have <?=$remain_val?> left</span>
											</p>
										</div>
									</div>
									<div class="offer-footer">
										<p class="offr_promo"><span>Promo Code:
											<?= $promo['promo_code']; ?></span>
										</p>
										<p class="offr_valdty"><strong>Valid Till:</strong>
											<?= date('d M Y', strtotime($promo['promo_code_expiry_date'])); ?>
										</p>
									</div>
								</div>
								<?php else: ?>
								<div class="no-offer">
									No discount coupon found.
								</div>
								<?php endif; ?>
						</div>
						<div class="col-md-6 col-12 nopad">
							<table class="tracker-table">
								<thead>
									<tr>
										<th>#</th>
										<th>Module</th>
										<th>Used Value</th>
										<th>Used At</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$i = 1;
									if (!empty($promocodeTrackerData)):
										foreach ($promocodeTrackerData as $value): ?>
										<tr>
											<td>
												<?= $i++; ?>
											</td>
											<td>
												<?= htmlspecialchars($value['module']); ?>
											</td>
											<td>
												<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
													<?php echo isset($value['promo_code_used_value'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($value['promo_code_used_value']) ):0; ?>
											</td>
											<td>
												<?= htmlspecialchars($value['created_date_time']); ?>
											</td>
										</tr>
										<?php endforeach; 
									else: ?>
											<tr>
												<td colspan="4" style="text-align: center;">No data found</td>
											</tr>
											<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				
				</div>
				<!-- Edit User Profile Ends-->
			</div>
			<div class="clearfix"></div>
			</div>
		</div>
		</div>
	</div>
		<div data-role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'traveller')? 'active' : ''?>" id="travellerinfo">
		<div class="trvlwrap">
			<div class="alldasbord">
			<div class="step_head">
				<h3 class="welcmnote">Travellers Details</h3>
				<a class="addbutton" data-bs-toggle="modal" data-bs-target="#add_traveller_tab">Add Traveller</a> </div>
			<!-- Add traveller Modal Starts-->
			<div class="modal fade" id="add_traveller_tab"  data-aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Traveller</h4>
					</div>
					<div class="modal-body">
					<div class="othinformtn">
						<div class="tab-content">
						<div class="tab-pane active" role="tabpanel">
							<div class="infowone">
							<form action="<?=base_url().'index.php/user/add_traveller?active=traveller'?>" method="post" name="add_traveller_form" id="add_traveller_form" autocomplete="off">
								<div class="paspertedit">
								<div class="col-4 margpas">
									<div class="tnlepasport">
									<div class="paspolbl cellpas">FirstName<span class="text-dange">*</span></div>
									<div class="lablmain cellpas">
										<input name="traveller_first_name" type="text" class="clainput alpha" placeholder="first name" maxlength="45" required="required">
									</div>
									</div>
								</div>
								<div class="col-4 margpas">
									<div class="tnlepasport">
									<div class="paspolbl cellpas">LastName<span class="text-dange">*</span></div>
									<div class="lablmain cellpas">
										<input name="traveller_last_name" type="text" class="clainput alpha" placeholder="last name" maxlength="45" required="required">
									</div>
									</div>
								</div>
								<div class="col-3 margpas">
									<div class="tnlepasport">
									<div class="paspolbl cellpas">DOB<span class="text-dange">*</span></div>
									<div class="lablmain cellpas">
										<input name="traveller_date_of_birth" id="add-travel-date-picker" type="text" class="disable-date-auto-update auto-datepicker clainput add_traveller_dob" placeholder="DOB" readonly required="required">
									</div>
									</div>
								</div>
								<div class="col-4 margpas">
									<div class="tnlepasport">
									<div class="paspolbl cellpas">Email <span class="text-dange">*</span></div>
									<div class="lablmain cellpas">
										<input name="traveller_email" type="text" class="clainput validate_email" placeholder="Email" maxlength="80" required="required">
									</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<button type="submit" id="add_traveller_btn" class="savepspot">Add</button>
								<a class="cancelll" data-bs-dismiss="modal">Cancel</a> </div>
							</form>
							</div>
						</div>
						</div>
					</div>
					</div>
				</div>
				</div>
			</div>
			<!-- Add traveller Modal Ends-->
			<div class="fulltable">
				<div class="trow tblhd">
				<div class="col-3 tblpad"> <span class="lavltr">Name</span> </div>
				<div class="col-2 tblpad"> <span class="lavltr">DOB</span> </div>
				<div class="col-3 tblpad"> <span class="lavltr">Email</span> </div>
				<div class="col-2 tblpad"> <span class="lavltr textalgn_rit">Action</span> </div>
				</div>
				<?php
							if(valid_array($traveller_details)) {
								$cutoff = date('Y', strtotime('+20 years'));
								//current year
								$now = date('Y');
								foreach($traveller_details as $traveller_k => $traveller_v) {
									extract($traveller_v);
									$passport_issuing_country_options = generate_options($country_list);
									$passport_day_options	= generate_options(get_day_numbers(), (array)$passport_expiry_day);
									$passport_month_options	= generate_options($get_month_names, (array)$passport_expiry_month);
									$passport_year_options	= generate_options(get_years($now, $cutoff), (array)$passport_expiry_year);
									if(empty($passport_expiry_day) == false) {
										$passport_expiry_date = $passport_expiry_day.'-'.$passport_expiry_month.'-'.$passport_expiry_year;
									} else {
										$passport_expiry_date = '';
									}
								?>
				<form action="<?=base_url().'index.php/user/update_traveller_details?active=traveller'?>" method="post" name="update_traveller_details_from" autocomplete="off">
				<input type="hidden" name="origin" value="<?=$origin?>">
				<div class="trow">
					<div class="col-3 tblpad"><span class="lavltr_mgc">Name</span> <span class="lavltr">
					<?=$first_name?>
					<?=$last_name?>
					</span> </div>
					<div class="col-2 tblpad"><span class="lavltr_mgc">DOB</span> <span class="lavltr">
					<?=$date_of_birth?>
					</span> </div>
					<div class="col-3 tblpad"><span class="lavltr_mgc">Email</span> <span class="lavltr">
					<?=(empty($email) == false ? $email : '---')?>
					</span> </div>
					<div class="col-2 tblpad"> <span class="lavltr float-end"> <a class="detilac" data-bs-toggle="collapse" data-bs-target="#traveller_details_row<?=$traveller_k?>" aria-expanded="true">Detail</a> </span> </div>
				</div>
				<div class="clearfix"></div>
				<div id="traveller_details_row<?=$traveller_k?>" class="collapse">
					<div class="travemore">
					<div class="othinformtn">
						<ul class="nav nav-tabs tabssyb" role="tablist">
						<li data-role="presentation" class="active"> <a href="#traveller_user_details<?=$traveller_k?>"  data-role="tab" data-bs-toggle="tab">User Information</a> </li>
						<li data-role="presentation" class=""> <a href="#traveller_passport_details<?=$traveller_k?>"  data-role="tab" data-bs-toggle="tab">Passport Information</a> </li>
						</ul>
						<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="traveller_user_details<?=$traveller_k?>">
							<div class="infowone">
							<div class="paspertorgnl">
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Name</div>
									<div class="lablmain cellpas">
									<?=$first_name?>
									<?=$last_name?>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">DOB</div>
									<div class="lablmain cellpas">
									<?=$date_of_birth?>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Email</div>
									<div class="lablmain cellpas">
									<?=(empty($email) == false ? $email : '---')?>
									</div>
								</div>
								</div>
								<div class="clearfix"></div>
								<a class="editpasport">Edit</a> </div>
							<div class="clearfix"></div>
							<div class="paspertorgnl paspertedit">
								<div class="col-4 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">FirstName<span class="text-dange">*</span></div>
									<div class="lablmain cellpas">
									<input name="traveller_first_name" type="text" value="<?=$first_name?>" class="clainput alpha" placeholder="FirstName" required="required" maxlength="45">
									</div>
								</div>
								</div>
								<div class="col-4 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">LastName<span class="text-dange">*</span></div>
									<div class="lablmain cellpas">
									<input name="traveller_last_name" type="text" value="<?=$last_name?>" class="clainput alpha" placeholder="LastName" required="required" maxlength="45">
									</div>
								</div>
								</div>
								<div class="col-4 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">DOB<span class="text-dange">*</span></span></div>
									<div class="lablmain cellpas">
									<input name="traveller_date_of_birth" id="traveller_date_of_birth<?=$traveller_k?>" type="text" value="<?=$date_of_birth?>" class="clainput traveller_dob auto-datepicker disable-date-auto-update" placeholder="DOB" readonly required="required">
									</div>
								</div>
								</div>
								<div class="col-4 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Email <span class="text-danger">*</span></div>
									<div class="lablmain cellpas">
									<input name="traveller_email" type="text" value="<?=$email?>" class="clainput validate_email" placeholder="Email" maxlength="80" required="required">
									</div>
								</div>
								</div>
								<div class="clearfix"></div>
								<button type="submit" class="savepspot">Save</button>
								<a class="cancelll">Cancel</a> </div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="traveller_passport_details<?=$traveller_k?>">
							<div class="infowone">
							<div class="paspertorgnl">
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Name</div>
									<div class="lablmain cellpas">
									<?=(empty($passport_user_name) == false ? $passport_user_name : '---')?>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Nationality</div>
									<div class="lablmain cellpas">
									<?=(isset($iso_country_list[$passport_nationality]) == true ? $iso_country_list[$passport_nationality] : '---')?>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Expiry Date</div>
									<div class="lablmain cellpas">
									<?=(empty($passport_expiry_date) == false ? $passport_expiry_date : '---')?>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Passport Number</div>
									<div class="lablmain cellpas">
									<?=(empty($passport_number) == false ? $passport_number : '---')?>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Issuing Country</div>
									<div class="lablmain cellpas">
									<?=(isset($country_list[$passport_issuing_country]) == true ? $country_list[$passport_issuing_country] : '---')?>
									</div>
								</div>
								</div>
								<div class="clearfix"></div>
								<a class="editpasport">Edit</a> </div>
							<div class="clearfix"></div>
							<div class="paspertorgnl paspertedit">
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Name</div>
									<div class="lablmain cellpas">
									<input type="text" name="passport_user_name" value="<?=$passport_user_name?>" Placeholder="Name"  class="clainput alpha" maxlength="45" />
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Nationality</div>
									<div class="lablmain cellpas">
									<select name="passport_nationality" class="clainput">
										<?=generate_options($iso_country_list, array(intval($passport_nationality) == 0 ? INDIA_CODE : $passport_nationality))?>
									</select>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Expiry Date</div>
									<div class="lablmain cellpas">
									<div class="retnmar">
										<div class="col-4 splinmar">
										<select name="passport_expiry_day" class="clainput">
											<option value="">DD</option>
											<?=$passport_day_options;?>
										</select>
										</div>
										<div class="col-4 splinmar">
										<select name="passport_expiry_month" class="clainput">
											<option value="">MM</option>
											<?=$passport_month_options;?>
										</select>
										</div>
										<div class="col-4 splinmar">
										<select name="passport_expiry_year" class="clainput">
											<option value="">YYYY</option>
											<?=$passport_year_options;?>
										</select>
										</div>
									</div>
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Passport Number</div>
									<div class="lablmain cellpas">
									<input name="passport_number" value="<?=$passport_number;?>" maxlength="10" type="text" class="clainput" />
									</div>
								</div>
								</div>
								<div class="col-6 margpas">
								<div class="tnlepasport">
									<div class="paspolbl cellpas">Issuing Country</div>
									<div class="lablmain cellpas">
									<div class="selectwrp custombord">
										<select name="passport_issuing_country" class="clainput">
										<option value="">Please Select</option>
										<?=generate_options($country_list, array($passport_issuing_country))?>
										</select>
									</div>
									</div>
								</div>
								</div>
								<div class="clearfix"></div>
								<button type="submit" class="savepspot">Save</button>
								<a class="cancelll">Cancel</a> </div>
							</div>
						</div>
						</div>
					</div>
					</div>
				</div>
				</form>
				<?php }
							} else { ?>
				<div class="col-md-12">
				<center>
					No Travellers Added
				</center>
				</div>
				<?php }?>
			</div>
			</div>
		</div>
		</div>
	</div>
	</div>
</div>
</div>
<script>
$(document).ready(function() {
	$('#add_traveller_btn').click(function(e){
		e.preventDefault();
		var _status = true;
		var _focus = '';
		var email = $(this).closest('form').find('.validate_email').val().trim();
		$('input:required', $(this).closest('form')).each(function() {
			if (this.value == '') {
				$(this).addClass('invalid-ip');
				if (_status == true) {
					_status = false;
					_focus = this;
				}
			} else if ($(this).hasClass('invalid-ip')) {
				$(this).removeClass('invalid-ip');
			}
		});
		if(email!='') {
			if(validate_email(email) == false) {
				_status = false;
				$(this).val('').addClass('invalid-ip').attr('placeholder', 'Invalid Email ID');
			}
		}
		if(_status == true) {
			$(this).closest('form').submit();
		}
	});
	$('.validate_email').change(function(){
		var email = $(this).val().trim();
		if(email!='') {
			if(validate_email(email) == false) {
				$(this).val('').addClass('invalid-ip').attr('placeholder', 'Invalid Email ID');
			} else {
				$(this).removeClass('invalid-ip');
			}
		} else {
			$(this).removeClass('invalid-ip');
		}
	});	
	$('.editpasport').click(function(){
		$(this).parent().parent('.infowone').addClass('editsave');
	});
	$('.cancelll').click(function(){
		$(this).parent().parent('.infowone').removeClass('editsave');
	});
	$('.traveller_dob').each(function(e){
		pastDatepicker($(this).attr('id'));
	});
});
</script>
<?php
$datepicker = array(array('add-travel-date-picker', PAST_DATE));
$GLOBALS['CI']->current_page->set_datepicker($datepicker);
?>

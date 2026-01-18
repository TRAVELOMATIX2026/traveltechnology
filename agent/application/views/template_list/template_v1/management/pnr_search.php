<style>
/* PNR Search Page - Beautiful Modern Design */
.pnr-search-container {
	background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
	min-height: calc(100vh - 200px);
	padding: 30px 20px;
}

.pnr-search-header {
	background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
	border: none;
	border-radius: 16px 16px 0 0;
	padding: 30px 35px;
	margin-bottom: 0;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.pnr-search-header .card-title h1 {
	font-size: 28px;
	font-weight: 700;
	color: #1a1a1a;
	margin: 0;
	background: linear-gradient(135deg, #F09814 0%, #EFCC57 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
	letter-spacing: -0.5px;
}

.pnr-search-card {
	background: linear-gradient(135deg, #FFE5CC 0%, #FFF5E6 25%, #FFFBF0 50%, #FFF5E6 75%, #FFE5CC 100%);
	border: none;
	border-radius: 0 0 16px 16px;
	box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 4px 16px rgba(0, 0, 0, 0.08);
	padding: 35px;
	position: relative;
	overflow: hidden;
}

.pnr-search-card::before {
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-image: 
		radial-gradient(circle at 10% 20%, rgba(240, 152, 20, 0.08) 0%, transparent 30%),
		radial-gradient(circle at 90% 80%, rgba(239, 204, 87, 0.08) 0%, transparent 30%),
		radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.4) 0%, transparent 40%);
	pointer-events: none;
}

.pnr-search-form {
	position: relative;
	z-index: 1;
}

.pnr-search-form .form-group {
	margin-bottom: 25px;
}

.pnr-search-form label {
	font-size: 13px;
	font-weight: 700;
	color: #5f6368;
	margin-bottom: 10px;
	display: block;
	text-transform: uppercase;
	letter-spacing: 1px;
}

.pnr-search-form .form-control {
	background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%);
	border: 2px solid #e8eaed;
	border-radius: 12px;
	padding: 14px 20px;
	font-size: 15px;
	font-weight: 500;
	color: #202124;
	transition: all 0.3s ease;
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
	height: 50px;
}

.pnr-search-form .form-control:focus {
	border-color: #F09814;
	background: #ffffff;
	box-shadow: 0 0 0 4px rgba(240, 152, 20, 0.1), 0 4px 12px rgba(240, 152, 20, 0.2);
	outline: none;
}

.pnr-search-form .form-control::placeholder {
	color: #9aa0a6;
	font-weight: 400;
}

/* Module Radio Buttons - Beautiful Design */
.module-selector {
	display: flex;
	gap: 12px;
	flex-wrap: wrap;
	margin-top: 10px;
}

.module-selector-item {
	position: relative;
	flex: 1;
	min-width: 120px;
}

.module-selector-item input[type="radio"] {
	position: absolute;
	opacity: 0;
	width: 0;
	height: 0;
}

.module-selector-item label {
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 14px 20px;
	background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%);
	border: 2px solid #e8eaed;
	border-radius: 12px;
	cursor: pointer;
	transition: all 0.3s ease;
	font-size: 14px;
	font-weight: 600;
	color: #5f6368;
	text-transform: none;
	letter-spacing: 0;
	margin: 0;
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
	position: relative;
	overflow: hidden;
}

.module-selector-item label::before {
	content: '';
	position: absolute;
	top: 0;
	left: -100%;
	width: 100%;
	height: 100%;
	background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
	transition: left 0.5s ease;
}

.module-selector-item label:hover {
	border-color: #F09814;
	color: #F09814;
	background: #fff5e6;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(240, 152, 20, 0.15);
}

.module-selector-item label:hover::before {
	left: 100%;
}

.module-selector-item input[type="radio"]:checked + label {
	background: linear-gradient(135deg, #F09814 0%, #EFCC57 100%);
	border-color: #F09814;
	color: #ffffff;
	box-shadow: 0 4px 16px rgba(240, 152, 20, 0.35);
	transform: translateY(-2px);
}

.module-selector-item input[type="radio"]:checked + label::before {
	left: 100%;
}

/* Search Button - Modern Design */
.pnr-search-btn-wrapper {
	margin-top: 10px;
	display: flex;
	align-items: flex-end;
}

.pnr-search-btn {
	background: linear-gradient(135deg, #F09814 0%, #EFCC57 100%);
	color: #ffffff;
	border: none;
	border-radius: 12px;
	padding: 14px 35px;
	font-size: 16px;
	font-weight: 700;
	letter-spacing: 0.5px;
	cursor: pointer;
	transition: all 0.3s ease;
	box-shadow: 0 4px 16px rgba(240, 152, 20, 0.35);
	height: 50px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 10px;
	position: relative;
	overflow: hidden;
	text-transform: uppercase;
}

.pnr-search-btn::before {
	content: '';
	position: absolute;
	top: 0;
	left: -100%;
	width: 100%;
	height: 100%;
	background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
	transition: left 0.5s ease;
}

.pnr-search-btn:hover {
	transform: translateY(-3px);
	box-shadow: 0 6px 20px rgba(240, 152, 20, 0.45);
	background: linear-gradient(135deg, #D88013 0%, #E5C04A 100%);
}

.pnr-search-btn:hover::before {
	left: 100%;
}

.pnr-search-btn:active {
	transform: translateY(-1px);
	box-shadow: 0 4px 12px rgba(240, 152, 20, 0.35);
}

.pnr-search-btn i {
	font-size: 18px;
}

/* Results Table Styling */
.pnr-results-table {
	margin-top: 30px;
	background: #ffffff;
	border-radius: 16px;
	overflow: hidden;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.pnr-results-table table {
	margin: 0;
}

.pnr-results-table thead th {
	background: linear-gradient(135deg, #F09814 0%, #EFCC57 100%);
	color: #ffffff;
	font-weight: 700;
	padding: 18px 15px;
	text-align: center;
	border: none;
	font-size: 14px;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.pnr-results-table tbody td {
	padding: 15px;
	border-color: #f0f0f0;
	vertical-align: middle;
	font-size: 14px;
	color: #333;
}

.pnr-results-table tbody tr:hover {
	background: #fff9f0;
	transition: background 0.2s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
	.pnr-search-container {
		padding: 20px 15px;
	}
	
	.pnr-search-card {
		padding: 25px 20px;
	}
	
	.module-selector-item {
		min-width: 100%;
	}
	
	.pnr-search-btn-wrapper {
		width: 100%;
	}
	
	.pnr-search-btn {
		width: 100%;
	}
}
</style>

<div class="bodyContent col-md-12 pnr-search-container">
	<div class="panel card clearfix" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
		<!-- PANEL WRAP START -->
		<div class="card-header pnr-search-header">
			<!-- PANEL HEAD START -->
			<div class="card-title">
				<h1>Transaction / PNR Search</h1>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="card-body pnr-search-card">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="" class="" id="fromList">
					<div class="col-md-12">
						<form autocomplete="off" name="pnr_search" id="pnr_search"
							action="" method="GET" class="pnr-search-form" style="">
							<div class="row">
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label for="pnr-number"><i class="fa fa-ticket"></i> PNR NUMBER</label>
										<input type="text"
											class="form-control auto-focus hand-cursor" id="pnr-number"
											placeholder="Enter PNR Number" value="" name="filter_report_data" required>
									</div>
								</div>
								<div class="col-md-6 col-sm-12">
									<div class="form-group">
										<label><i class="fa fa-cube"></i> MODULE</label>
										<div class="module-selector">
											<?php if(is_active_airline_module()){?>
											<div class="module-selector-item">
												<input type="radio" name="module" id="module-flight"
													value="<?php echo PROVAB_FLIGHT_BOOKING_SOURCE ?>" checked>
												<label for="module-flight"><i class="fa fa-plane"></i> Flight</label>
											</div>
											<?php } if (is_active_bus_module()){?>
											<div class="module-selector-item">
												<input type="radio" name="module" id="module-bus"
													value="<?php echo PROVAB_BUS_BOOKING_SOURCE ?>">
												<label for="module-bus"><i class="fa fa-bus"></i> Bus</label>
											</div>
											<?php } if (is_active_hotel_module()){?>
											<div class="module-selector-item">
												<input type="radio" name="module" id="module-hotel"
													value="<?php echo PROVAB_HOTEL_BOOKING_SOURCE ?>">
												<label for="module-hotel"><i class="fa fa-hotel"></i> Hotel</label>
											</div>
											<?php }?> 
											<?php if(is_active_transferv1_module()){ ?>
											<div class="module-selector-item">
												<input type="radio" name="module" id="module-transfers"
													value="<?php echo PROVAB_TRANSFERV1_BOOKING_SOURCE ?>">
												<label for="module-transfers"><i class="fa fa-car"></i> Transfers</label>
											</div>
											<?php }?>
											<?php if(is_active_sightseeing_module()){ ?>
											<div class="module-selector-item">
												<input type="radio" name="module" id="module-activities"
													value="<?php echo PROVAB_SIGHTSEEN_BOOKING_SOURCE ?>">
												<label for="module-activities"><i class="fa fa-camera"></i> Activities</label>
											</div>
											<?php }?>
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="pnr-search-btn-wrapper">
										<button type="submit" name="" id="form-submit"
											class="pnr-search-btn">
											<i class="fa fa-search"></i> Search
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php
			//Only Visible if module is Flight 
			if(@$module == 'Flight'){?>
			<div class="tab-content pnr-results-table" style="margin-top: 35px;">
				<div id="tableList" class="">
					<table class="table table-sm table-bordered">
					<thead>
						<tr><th colspan="10"><i class="fa fa-plane"></i> Flight Details</th></tr>
						<tr>
							<th>Sno</th>
							<th>Application <br>Reference</th>
							<th>Status</th>
							<th>Base Fare</th>
							<th>Markup</th>
							<th>Customer</th>
							<th>Booking</th>
							<th>Total Ticket(s)</th>
							<th>Booked On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
				if (isset ( $table_data ) == true and valid_array ( $table_data )) {
					//debug($table_data);exit;
					foreach ($table_data as $k => $v){
						$current_record = 0;
						extract ( $v );
						$action = '';
						$cancellation_btn = '';
						$voucher_btn = '';
						$status_update_btn = '';
						if (strtotime ( $journey_start ) > time () and (status == BOOKING_PENDING || $status == BOOKING_VOUCHERED || $status == BOOKING_CONFIRMED || $status == BOOKING_HOLD)) {
							// $cancellation_btn = get_accomodation_cancellation($api_code, $v['reference']);
						}
						// Status Update Button
						if (in_array ( $status, array (
								'BOOKING_CONFIRMED' 
						) ) == false) {
							switch ($booking_source) {
								case PROVAB_FLIGHT_BOOKING_SOURCE :
									$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="' . $app_reference . '"><i class="fa fa-database"></i> Update Status</button>';
									break;
							}
						}
						$voucher_btn = flight_voucher ( $app_reference, $booking_source, $status );
						$action = $voucher_btn . $status_update_btn . $cancellation_btn;
						?>
						<tr>
							<td><?=($current_record+$k+1)?></td>
							<td><?=$app_reference;?></td>
							<td><span class="<?=booking_status_label($status) ?>"><?=$status?></span></td>
							<td><?php echo $currency.':'.($total_fare+$domain_markup)?></td>
							<td><?php echo $level_one_markup?></td>
							<td><?php echo $name.'<br>Email:<br>'.$email?><br><?php echo 'P:'.$phone_number?></td>
							<td><strong><?php echo $journey_from.'</strong><br> @'.app_friendly_datetime($journey_start).' <br><strong><i> to </i><br> '.$journey_to?></strong><br><?php echo ' @'.app_friendly_datetime($journey_end)?></td>
							<td><?php echo $total_passengers?></td>
							<td><?php echo app_friendly_absolute_date($created_datetime)?></td>
							<td><div class="" role="group"><?php echo $action; ?></div></td>
						</tr>
								<?php
					}
				} else {
					echo '<tr><td colspan="12">No Data Found</td></tr>';
				}
				?>
					</table>
				</div>
			</div>
<script>
$(document).ready(function() {
	//update-source-status update status of the booking from api
	$(document).on('click', '.update-source-status', function(e) {
		e.preventDefault();
		$(this).attr('disabled', 'disabled');//disable button
		var app_ref = $(this).data('app-reference');
		$.get(app_base_url+'flight/get_booking_details/'+app_ref, function(response) {
			
		});
	});
});
</script>
<?php
function get_accomodation_cancellation($courseType, $refId) {
	return '<a href="' . base_url () . 'booking/accomodation_cancellation?courseType=' . $courseType . '&refId=' . $refId . '" class="btn btn-sm btn-danger "><i class="fa fa-exclamation-triangle"></i> Cancel</a>';
}
?>
			<?php } 
			// Only Visible if module is Bus			 
			if(@$module == 'Bus'){?>
			<div class="tab-content pnr-results-table" style="margin-top: 35px;">
				<div id="tableList" class="">
					<table class="table table-sm table-bordered">
					<thead>
						<tr><th colspan="10"><i class="fa fa-bus"></i> Bus Details</th></tr>
						<tr>
							<th>Application <br> Reference</th>
							<th>Status</th>
							<th>PNR/Ticket</th>
							<th>Total Fare</th>
							<th>Customer</th>
							<th>Booking</th>
							<th>journey</th>
							<th>Operator</th>
							<th>Booked On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
					if (isset ( $table_data ) == true and valid_array ( $table_data )) {
					foreach ( $table_data as $k => $v ) {
						// get cancel button only if check in date has not passed and api cancellation is active
						// ONLY AOT IS ACTIVE FOR CANCELLATION
						$api_code = '';
						$action = '';
						if ($v ['booking_source'] == PROVAB_BUS_BOOKING_SOURCE) {
							$api_code = PROVAB_BUS_BOOKING_SOURCE;
							if (strtotime ( $v ['departure_datetime'] ) > time () and ($v ['status'] == BOOKING_CONFIRMED || $v ['status'] == BOOKING_HOLD)) {
								// $action .= get_accomodation_cancellation($api_code, $v['reference']);
							}
						}
						if (empty ( $api_code ) == false) {
							$action .= bus_voucher ( $v ['app_reference'], $api_code, $v ['status'] );
						}
						$customer = explode ( DB_SAFE_SEPARATOR, $v ['name'] );
						?>
						<tr>
							<td><?php echo $v['app_reference'];?></td>
							<td><span
								class="<?php echo booking_status_label($v['status']) ?>"><?php echo $v['status']?></span></td>
							<td class=""><span><?php echo 'PNR:<br>'.$v['pnr']?></span><br>
							<span><?php echo 'Tick:<br>'.$v['ticket']?></span></td>
							<td><?php echo $v['currency'].':'.($v['total_fare']+$v['level_one_markup']+$v['domain_markup'])?></td>
							<td><?php echo $customer[0].'<br>Email:<br>'.$v['email']?><br><?php echo 'P:'.$v['phone_number']?><br><?php echo 'O:'.$v['alternate_number']?></td>
							<td><?php echo $v['departure_from']?><br>to<br><?php echo $v['arrival_to']?><br>(<?php echo $v['total_passengers']?> <?=(intval($v['total_passengers']) > 1 ? 'tickets' : 'ticket' )?>)</td>
							<td><?php echo app_friendly_datetime($v['departure_datetime'])?></td>
							<td><?php echo $v['operator']?></td>
							<td><?php echo app_friendly_absolute_date($v['created_datetime'])?></td>
							<td><div class="" role="group"><?php echo $action; ?></div></td>
						</tr>
								<?php
					}
				} else {
					echo '<tr><td colspan="10" style="text-align: center; padding: 40px; color: #999;"><i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.5;"></i>No Data Found</td></tr>';
				}
				?>
					</tbody>
					</table>
				</div>
			</div>
			<?php } 
			// Only Visible if module is Hotel
			if(@$module == 'Hotel') {?>
			<div class="tab-content pnr-results-table" style="margin-top: 35px;">
				<div id="tableList" class="">
					<table class="table table-sm table-bordered">
					<thead>
						<tr><th colspan="11"><i class="fa fa-hotel"></i> Hotel Details</th></tr>
						<tr>
							<th>Application <br>Reference</th>
							<th>Status</th>
							<th>Confirmation/<br>Reference</th>
							<th>Total Fare</th>
							<th>Payment Mode</th>
							<th>Customer</th>
							<th>Booking</th>
							<th>Check-In</th>
							<th>Hotel</th>
							<th>Booked <br>On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
				if (isset ( $table_data ) == true and valid_array ( $table_data )) {
					foreach ( $table_data as $k => $v ) {
						extract ( $v );
						// get cancel button only if check in date has not passed and api cancellation is active
						// ONLY AOT IS ACTIVE FOR CANCELLATION
						$action = '';
						if ($v ['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE) {
							if (strtotime ( $v ['hotel_check_in'] ) > time () and ($v ['status'] == BOOKING_PENDING || $v ['status'] == BOOKING_VOUCHERED || $v ['status'] == BOOKING_CONFIRMED || $v ['status'] == BOOKING_HOLD)) {
								// $action .= get_accomodation_cancellation($api_code, $v['reference']);
							}
						}
						$action .= hotel_voucher ( $v ['app_reference'], $booking_source, $v ['status'] );
						?>
						<tr>
							<td><?=$app_reference;?></td>
							<td><span class="<?=booking_status_label($status) ?>"><?=$status?></span></td>
							<td class=""><span><?php echo 'Conf:'.$confirmation_reference?></span><br>
							<span><?php echo 'Ref:'.$booking_reference?></span></td>
							<td><?php echo $currency.':'.($total_fare+$level_one_markup+$domain_markup)?></td>
							<td><?php echo $payment_name?></td>
							<td><?php echo $name.'<br>Email:<br>'.$email?><br><?php echo 'P:'.$phone_number?></td>
							<td><?php echo app_friendly_absolute_date($hotel_check_in)?> <br> to <br> <?php echo app_friendly_date($hotel_check_out)?></td>
							<td><?php echo $total_passengers?> Pax, <br><?php echo $v['total_rooms']?> <?=(intval($total_rooms) > 1 ? 'Rooms' : 'Room' )?></td>
							<td><?php echo $hotel_name?></td>
							<td><?php echo app_friendly_absolute_date($created_datetime)?></td>
							<td><div class="" role="group"><?php echo $action; ?></div></td>
						</tr>
								<?php
					}
				} else {
					echo '<tr><td colspan="11" style="text-align: center; padding: 40px; color: #999;"><i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.5;"></i>No Data Found</td></tr>';
				}
				?>
					</tbody>
					</table>
				</div>
			</div>
			<?php
				function get_accomodation_cancellation($courseType, $refId) {
					return '<a href="' . base_url () . 'booking/accomodation_cancellation?courseType=' . $courseType . '&refId=' . $refId . '" class="col-md-12 btn btn-sm btn-danger "><i class="fa fa-exclamation-triangle"></i> Cancel</a>';
				}
				?>
			<?php } ?>
		</div>
	</div>
</div>
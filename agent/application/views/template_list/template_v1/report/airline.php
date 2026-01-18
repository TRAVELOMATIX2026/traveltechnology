<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
<?=$GLOBALS['CI']->template->isolated_view('report/payment_popup')?>
<div class="bodyContent">
	<div class="table_outer_wrper"><!-- PANEL WRAP START -->
		<div class="panel_custom_heading"><!-- PANEL HEAD START -->
			<div class="panel_title">
				<?php echo $GLOBALS['CI']->template->isolated_view('share/report_navigator_tab') ?>
                <div class="clearfix"></div>
                <div class="search_fltr_section">
                    <form method="GET" role="search" class="search-form-container" id="auto_suggest_booking_id_form">
                        <div class="search-input-wrapper">
                            <span class="search-icon-prefix">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="hidden" id="module" value="<?=PROVAB_FLIGHT_BOOKING_SOURCE?>">
                            <input type="text" 
                                   autocomplete="off" 
                                   data-search_category="search_query" 
                                   placeholder="Search by Application Reference or PNR..." 
                                   class="form-control search-input-field" 
                                   id="auto_suggest_booking_id" 
                                   name="filter_report_data" 
                                   value="<?=@$_GET['filter_report_data']?>">
                        </div>
                        <div class="search-button-group">
                            <button title="Search Bookings" class="btn btn-search" type="submit">
                                <i class="bi bi-search"></i>
                                <span class="btn-text">Search</span>
                            </button>
                            <a title="Clear Search & Show All" class="btn btn-clear" href="<?=base_url().'index.php/report/flight'?>">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span class="btn-text">Reset</span>
                            </a>
                        </div>
                    </form>
        		</div>
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel_bdy"><!-- PANEL BODY START -->

        <div class="clearfix"></div>
			<div class="tab-content">
				<div id="tableList" class="table-responsive">
					<div class="float-end">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div>
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th>SNO</th>
								<th>Application Reference</th>
								<th>PNR</th>
								<th>Customer Name</th>
								<th>From</th>
								<th>To</th>
								<th>Trip Type</th>
								<th>Net Fare</th>
								<th>Commission</th>
								<th>Markup</th>
								<th>TDS</th>
								<th>GST</th>
								<th>Total Fare</th>
								<th>Travel Date</th>
								<th>Booked On</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        		extract($parent_v);
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';
									$status_update_btn = '';
									$booked_by = '';
									
									//Status Update Button
									if (in_array($status, array('BOOKING_CONFIRMED')) == false) {
										switch ($booking_source) {
											case PROVAB_FLIGHT_BOOKING_SOURCE :
												$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="bi bi-database"></i> Update Status</button>';
												break;
										}
									}
									$voucher_btn = flight_voucher($app_reference, $booking_source, $status);
									$pdf_btn = flight_pdf($app_reference, $booking_source, $status);
									$invoice = flight_invoice($app_reference, $booking_source, $status);
									$cancel_btn = flight_cancel($app_reference, $booking_source, $status);
									$email_btn = flight_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
									$jrny_date = date('Y-m-d', strtotime($journey_start));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
									$pay_amount = 0;
									if($booking_source == CLARITYNDC_FLIGHT_BOOKING_SOURCE){
										$trans_attr = @$parent_v['booking_transaction_details'][0]['attributes'];
										$trans_attr_data = $trans_attr ? json_decode($trans_attr,true) : [];
										$trans_amount = isset($trans_attr_data['Api_Price']['final_amount']) ? $trans_attr_data['Api_Price']['final_amount'] : (isset($trans_attr_data['Api_Price']['TotalPrice']) ? $trans_attr_data['Api_Price']['TotalPrice'] : $grand_total);
										$action .= check_run_ticket_method($parent_v['app_reference'], $parent_v['booking_source'], $status, $parent_v['is_domestic'], $parent_v['journey_start'], $currency.$trans_amount);
									}
									$action .= $voucher_btn;
									$action .= '<br />'.$pdf_btn;
									$action .=  '<br />'.$email_btn;
									if($diff > 0){
										$action .= $cancel_btn;
									}
									if ($booking_source == CLARITYNDC_FLIGHT_BOOKING_SOURCE && $status != 'BOOKING_CANCELLED') {
										if (strtotime('now') < strtotime($parent_v['journey_start'])) {
											$update_booking_details_btn = update_booking_details($app_reference, $booking_source, $status);
											$action .= '<br />' . $update_booking_details_btn;
										}
									}
									$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status'], $parent_v['booking_transaction_details']);
								?>
									<tr>
										<td data-label="SNO"><?=($current_record++)?></td>
										<td data-label="Application Reference"><?php echo $app_reference;?></td>
										<td data-label="PNR"><?=@$pnr?></td>
										<td data-label="Customer Name"><?=$booking_transaction_details[0]['booking_customer_details'][0]['title'].' '.$booking_transaction_details[0]['booking_customer_details'][0]['first_name'].' '.$booking_transaction_details[0]['booking_customer_details'][0]['last_name']?></td>
										<td data-label="From"><?=@$from_loc?></td>
										<td data-label="To"><?=@$to_loc?></td>
										<td data-label="Trip Type"><?=$trip_type=='circle'?'Roundway':ucfirst($trip_type)?></td>
										<td data-label="Net Fare"><?php echo $agent_buying_price?></td>
										<td data-label="Commission"><?php echo $agent_commission?></td>
										<td data-label="Markup"><?php echo $agent_markup?></td>
										<td data-label="TDS"><?php echo ($agent_tds)?></td>
										<td data-label="GST"><?php echo ($gst)?></td>
										<td data-label="Total Fare"><?php echo $grand_total?></td>
										<td data-label="Travel Date"><?php echo app_friendly_absolute_date($journey_start)?></td>
										<td data-label="Booked On"><?php echo $booked_date?></td>
										<td data-label="Status"><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span><?php echo (($status == 'BOOKING_HOLD' && $booking_transaction_details[0]['hold_ticket_valid_date']) ? '<br/><br/><span class="text-danger text-bold">'.$booking_transaction_details[0]['hold_ticket_valid_date'].'</span>':'' );?></td>
										<td data-label="Action">
											<div class="dropdown actn_drpdwn">
												<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
													<i class="bi bi-three-dots-vertical"></i>
												</button>
												<ul class="dropdown-menu actn_menu_drpdwn" aria-labelledby="dropdownMenu1">
													<div class="" role="group"><?php echo $action; ?></div>
												</ul>
											</div>
										</td>
									</tr>
								<?php
								}
							} else {
								?>
								<tr>
									<td colspan="17">No Data Found</td>
								</tr>
								<?php
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	//update-source-status update status of the booking from api
	$(document).on('click', '.update-source-status', function(e) {
		e.preventDefault();
		$(this).attr('disabled', 'disabled');//disable button
		var app_ref = $(this).data('app-reference');
		$.get(app_base_url+'index.php/flight/get_booking_details/'+app_ref, function(response) {
			
		});
	});

    /*
    *Sagar Wakchaure
    *send email voucher
    */
	  $('.send_email_voucher').on('click', function(e) {
			$("#mail_voucher_modal").modal('show');
			$('#mail_voucher_error_message').empty();
	        email = $(this).data('recipient_email');
			$("#voucher_recipient_email").val(email);
	        app_reference = $(this).data('app-reference');
	        book_reference = $(this).data('booking-source');
	        app_status = $(this).data('app-status');
		  $("#send_mail_btn").off('click').on('click',function(e){
			  email = $("#voucher_recipient_email").val();
			  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			  if(email != ''){
				  if(!emailReg.test(email)){
					  $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
	                     return false;    
					      }
			      
						var _opp_url = app_base_url+'index.php/voucher/flight/';
						_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/agent_receipt/'+email;
						toastr.info('Please Wait!!!');
						$.get(_opp_url, function() {
							toastr.info('Email sent  Successfully!!!');
							$("#mail_voucher_modal").modal('hide');
						});
			  }else{
				  $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
			  }
		  });
	
	});
	$('.issue_hold_ticket').on('click', function (e) {
		$("#payform").trigger('reset');
		$("#book_id").val($(this).data('app-reference'));
		$("#book_source").val($(this).data('booking-source'));
		$("#pay_amount").text($(this).data('price'));
		$("#ticket_confirm_response").html('');
		$("#payment_modal").modal('show');
	});
	$(document).on('click',"#payment_cnf_btn", function(e){
		e.preventDefault();
		var _opp_url = app_base_url + 'index.php/flight/run_ticketing_method';
		$("#payment_cnf_btn").val('Please wait...').prop('disabled', true);
		$.post(_opp_url,$("#payform").serialize(),function(res){
			$("#ticket_confirm_response").html(res.Message);
			if(res.Status==1){
				$("#payform").trigger('reset');
				setTimeout(function() {
					$("#payment_modal").modal('hide');
				}, 5000);
			}
			$("#payment_cnf_btn").val('Submit').prop('disabled', false);
		},'json');
	});
	$('.update_flight_booking_details').on('click', function (e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url + 'index.php/report/update_pnr_details/';
		_opp_url = _opp_url + $(this).data('app-reference') + '/' + $(this).data('booking-source') + '/' + $(this).data('booking-status');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function (res) {
			var message = res === '1' ? 'Updated Successfully!!!' : 'Update Failed!!!';
			toastr.info(message);
			if (res === '1') {
				location.reload();
			}
		});
	});
	
});
</script>
<?php
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="btn btn-sm btn-danger "><i class="bi bi-exclamation-triangle"></i> Cancel</a>';
}
function update_booking_details($app_reference, $booking_source, $booking_status) {
    return '<a class="btn btn-sm btn-danger update_flight_booking_details" data-app-reference="' . $app_reference . '" data-booking-source="' . $booking_source . '"data-booking-status="' . $booking_status . '"><i class="bi bi-arrow-repeat"></i> Update PNR Details</a>';
}
function flight_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-info send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="bi bi-envelope"></i> Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status, $booking_customer_details)
{
	$status = 'BOOKING_CONFIRMED';
	if($master_booking_status == 'BOOKING_CANCELLED'){
		$status = 'BOOKING_CANCELLED';
	} else{
		foreach($booking_customer_details as $tk => $tv){
			foreach($tv['booking_customer_details'] as $pk => $pv){
				if($pv['status'] == 'BOOKING_CANCELLED'){
					$status = 'BOOKING_CANCELLED';
					break;
				}
			}
		}
	}
	if($status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'index.php/flight/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="bi bi-info-circle"></i> Cancellation Details</a>';
	}
}
?>

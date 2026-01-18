<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
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
                            <input type="hidden" id="module" value="<?=PROVAB_TRANSFER_BOOKING_SOURCE?>">
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
                            <a title="Clear Search & Show All" class="btn btn-clear" href="<?=base_url().'index.php/report/transfers'?>">
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
								<th>Confirmation Reference</th>
								<th>Transfer Type</th>
								<th>Vehicle Name</th>
								<th>Category Name</th>
								<th>Customer Name</th>
								<th>Travel From Location</th>
								<th>Travel To Location</th>	
								<th>Travel Date</th>					
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
									$cancel_btn='';
									
									//Status Update Button
									if ($status=='BOOKING_HOLD'){

										$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="bi bi-database"></i> Update Status</button>';
									}
									
									$voucher_btn = transfers_voucher($app_reference, $booking_source, $status);
									$pdf_btn = transfers_pdf($app_reference, $booking_source, $status);
									
									if($status=='BOOKING_CONFIRMED'){

										$cancel_btn = cancel_transfers_booking($app_reference, $booking_source, $status);
									}
									
									$email_btn = transfer_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);

									$jrny_date = date('Y-m-d', strtotime($travel_date));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
									$action .= $voucher_btn;
									$action .= '<br />'.$pdf_btn;
									$action .=  '<br />'.$email_btn;
									$action .= '<br/>'.$status_update_btn;
									if($diff > 0){
										$action .= $cancel_btn;
									}
									$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
								?>
									<tr>
										<td data-label="SNO"><?=($current_record++)?></td>
										<td data-label="Application Reference"><?php echo $app_reference;?></td>
										<td data-label="Confirmation Reference"><?=@$confirmation_reference?></td>
										<td data-label="Transfer Type"><?=$transfer_type;?></td>
										<td data-label="Vehicle Name"><?=$vehicle_name;?></td>
										<td data-label="Category Name"><?=$category_name;?></td>
										<td data-label="Customer Name"><?=$parent_v['customer_details'][0]['first_name'].' '.$parent_v['customer_details'][0]['last_name']?></td>
										<td data-label="Travel From Location"><?=@$itinerary_details[0]['from_location'];?></td>
										<td data-label="Travel To Location"><?=@$itinerary_details[0]['to_location'];?></td>
										<td data-label="Travel Date"><?=@$travel_date.' '.$travel_time;;?></td>
										<td data-label="Net Fare"><?php echo $agent_buying_price?></td>
										<td data-label="Commission"><?php echo $agent_commission?></td>
										<td data-label="Markup"><?php echo $agent_markup?></td>
										<td data-label="TDS"><?php echo ($agent_tds)?></td>
										<td data-label="GST"><?php echo ($gst)?></td>
										<td data-label="Total Fare"><?php echo $grand_total?></td>
										<td data-label="Travel Date"><?php echo app_friendly_absolute_date($travel_date)?></td>
										<td data-label="Booked On"><?php echo $voucher_date?></td>
										<td data-label="Status"><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td data-label="Action"><div class="" role="group"><?php echo $action; ?></div></td>
									</tr>
								<?php
								}
							} else {
								?>
								<tr><td colspan="20">No Data Found</td></tr>
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
		$.get(app_base_url+'index.php/transfers/get_booking_details/'+app_ref, function(response) {
			
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
			      
						var _opp_url = app_base_url+'index.php/voucher/transfers/';
						_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/'+email;
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
	
});
</script>
<?php


function transfer_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="bi bi-envelope"></i> Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
	
	if($master_booking_status == 'BOOKING_CANCELLED'){

		return '<a target="_blank" href="'.base_url().'index.php/transfer/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="bi bi-info-circle"></i> Cancellation Details</a>';
	}

	
}
?>

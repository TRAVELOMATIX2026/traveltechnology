<?= $GLOBALS['CI']->template->isolated_view('report/email_popup') ?>

<?php

if (is_array($search_params)) {

    extract($search_params);

}

$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));

$this->current_page->set_datepicker($_datepicker);

$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));

?>

<div class="bodyContent col-md-12">
<h4 class="mb-3">B2B Booking Report</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->
    <?= $GLOBALS['CI']->template->isolated_view('report/report_tab_b2b') ?>

        <div class="card-body p-0">

            <div id="show-search">

                <form method="GET" autocomplete="off" action="<?= base_url().'report/b2b_activities_report?'?>">

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-6 col-md-3">
                            <label>
                                Agent
                            </label>
                            <select class="form-control" name="created_by_id">
                                <option>All</option>
                                <?= generate_options($agent_details, array(@$created_by_id)) ?>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                PNR
                            </label>
                            <input type="text" class="form-control" name="pnr" value="<?= @$pnr ?>" placeholder="PNR">
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                Application Reference
                            </label>
                            <input type="text" class="form-control" name="app_reference" value="<?= @$app_reference ?>" placeholder="Application Reference">
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                Status
                            </label>
			
                            <select class="form-control" name="status">
                                <option>All</option>
                                <?= generate_options($status_options, array(@$status)) ?>
                            </select>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                Booked From Date
                            </label>
                            <input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?= @$created_datetime_from ?>" placeholder="Request Date">
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <label>
                                Booked To Date
                            </label>

                            <input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?= @$created_datetime_to ?>" placeholder="Request Date">

                        </div>

                        <div class="col-sm-6 col-md-6">
                        <label>&nbsp;</label>
                        <div class="d-flex flex-wrap justify-content-end gap-3">
                        <button type="submit" class="btn btn-gradient-primary"><i class="bi bi-search"></i> Search</button> 
                        
                        <button type="reset" id="btn-reset" class="btn btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>  

                        <!-- <a href="<?= base_url() . 'report/b2b_sightseeing_report? ' ?>" id="clear-filter" class="btn btn-gradient-info"><i class="fa fa-times"></i> Clear Filter</a> -->

                    </div>
                    </div>
                </form>
            </div>
            <!-- EXCEL/PDF EXPORT STARTS -->
        </div>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center">
<div class="float-start my-3">
<?php echo $this->pagination->create_links(); ?> <span class="totl_bkngs_flt">Total <?php echo $total_rows ?> Bookings</span>
</div> 
<?php if($total_records > 0){ ?>

<div class="clearfix"></div>

<?php } ?>

<!-- EXCEL/PDF EXPORT ENDS -->
 </div>

<div class="clearfix table-responsive reprt_tble"><!-- PANEL BODY START -->

           
            <table class="table table-sm table-bordered example3" id="b2b_report_sightseeing_table">
						<thead>
							<tr>
								<th>Sno</th>
								<th>Application Reference</th>
								<th>Status</th>
								<th>Agency Name</th>
								<th>Lead Pax <br/>Details</th>
								<th>Activity Name</th>
								<th>Acitvity Location</th>	
								<th>BookedOn</th>
								<th>JourneyDate</th>
								<th>Confirmation Reference</th>
								<th>Comm.Fare</th>
								<th>Commission</th>
								<th>TDS</th>
								<th>Admin NetFare</th>
								<th>Admin Profit</th>
								<th>Admin<br/>Markup</th>
								<th>Agent<br/>Commission</th>
								<th>Agent<br/>TDS</th>
								<th>Agent <br/>Net Fare</th>
								<th>Agent<br/>Markup</th>
								<th>GST</th>
								<th>TotalFare</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Sno</th>
								<th>Application Reference</th>
								<th>Status</th>
								<th>Agency Name</th>
								<th>Lead Pax <br/>Details</th>
								<th>Activity Name</th>
								<th>Activity Location</th>								
								<th>BookedOn</th>
								<th>JourneyDate</th>
								<th>Confirmation Reference</th>
								<th>Comm.Fare</th>
								<th>Commission</th>
								<th>TDS</th>
								<th>Admin NetFare</th>
								<th>Admin Profit</th>
								<th>Admin<br/>Markup</th>
								<th>Agent <br/>Commission</th>
								<th>Agent<br/>TDS</th>
								<th>Agent <br/>Net Fare</th>
								<th>GST</th>
								<th>TotalFare</th>
								<th>Action</th>
							</tr>
						</tfoot><tbody>
						<?php
							//debug($table_data['booking_details']);exit;
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3 + 1);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        		extract($parent_v);
					        		//debug($parent_v);exit;
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';								
									$booked_by = '';
									$cancel_btn ='';

									$voucher_btn = sightseeing_voucher($app_reference, $booking_source, $status, 'b2b');
									
									if($status=='BOOKING_CONFIRMED'){
										$cancel_btn = cancel_sightseeing_booking($app_reference, $booking_source, $status,'b2b');
									}
									
									$pdf_btn= sightseeing_pdf($app_reference, $booking_source, $status,'b2b');
									$email_btn = sightseeing_voucher_email($app_reference, $booking_source,$status,$email);
									$jrny_date = date('Y-m-d', strtotime($travel_date));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
									
                                                                        
					        		$action .= $voucher_btn;
					        		$action .=  '<br />'.$pdf_btn;
					        		$action .=  '<br />'.$email_btn;
					        		$action .= activity_GST_Invoice($app_reference, $booking_source, $status, 'b2b');
				        		  	$action .='<br/>';
									if($diff > 0){
										$action .= $cancel_btn;
									}
									
									$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
									
									$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
								?>
									<tr>
										<td><?=($current_record++)?></td>
										<td><?php echo $app_reference;?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td><?php echo $agency_name;?></td>
										<td>
										<?php echo $lead_pax_name. '<br/>'.
										  $email."<br/>".
										  $parent_v['customer_details'][0]['phone'];?>
										</td>
										<td><?php echo $product_name;?></td>
										<td><?php echo $Destination?></td>
										
										<td><?php echo date('d-m-Y', strtotime($voucher_date))?></td>
										<td><?php echo date('d-m-Y', strtotime($travel_date))?></td>
										<td><?=@$confirmation_reference?></td>
										<td><?php echo $fare?></td>
										<td><?php echo $net_commission?></td>
										<td><?php echo $net_commission_tds?></td>
										<td><?php echo $net_fare?></td>
										<td><?php echo $admin_commission?></td>
										<td><?php echo $admin_markup?></td>
										<td><?php echo $agent_commission?></td>
										<td><?php echo $agent_tds?></td>
										<td><?php echo $agent_buying_price?></td>
										<td><?php echo $agent_markup?></td>
										<td><?php echo $gst?></td>
										<td><?php echo $grand_total?></td>
										<td>
											<div class="dropdown actn_drpdwn">
												<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
													<i class="bi bi-three-dots-vertical"></i>
												</button>
												<ul class="dropdown-menu actn_menu_drpdwn gap-2" aria-labelledby="dropdownMenu1">
													<div class="action_system d-flex gap-2" role="group"><?php echo $action; ?></div>
												</ul>
											</div>
										</td>
									</tr>
								<?php
								}
							} else {
								?>
								<tr>
									<td colspan="20" class="text-center no-data-found">
										<div class="empty-state">
											<i class="bi bi-inbox" aria-hidden="true"></i>
											<h4>No Data Found</h4>
											<p>No booking records match your search criteria. Please try adjusting your filters.</p>
										</div>
									</td>
								</tr>
								<?php
							}
						?>
						</tbody>
					</table>
					
				
		</div>
	</div>
</div>
<script>
$(document).ready(function() {

	//send the email voucher
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
		      
					var _opp_url = app_base_url+'index.php/voucher/sightseeing/';
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

function sightseeing_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-info send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="bi bi-envelope-fill"></i> Email Voucher</a>';
}

function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
		//echo '<pre>'; 	print_r ($master_booking_status); die;
	
	if($master_booking_status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'index.php/sightseeing/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="bi bi-info-circle-fill"></i> Cancellation Details</a>';
	}
	
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_sightseeing_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="bi bi-info-circle-fill"></i> Update Supplier Info</a>';
	}
}
?>
<script>
$(document).ready(function() {

		$(".get_sightseeing_hb_status").on("click",function(e){
  		
	 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/sightseeing/get_pending_booking_status/';
		_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status;
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			if(res==1){
				toastr.info('Status Updated Successfully!!!');	
				location.reload(); 
			}else{
				toastr.info('Status not updated');
			}
			
			$("#mail_voucher_modal").modal('hide');
		});
    });

});
</script>

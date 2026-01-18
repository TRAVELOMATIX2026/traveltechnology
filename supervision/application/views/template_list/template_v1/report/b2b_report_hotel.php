<?= $GLOBALS['CI']->template->isolated_view('report/email_popup') ?>

<?php

if (is_array($search_params)) {

    extract($search_params);

}

$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));

$this->current_page->set_datepicker($_datepicker);

$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));

?>

<div class="modal fade" id="pax_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">  

    <div class="modal-dialog" role="document" style="width: 880px;">    
        <div class="modal-content">    
            <div class="modal-header">        
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
                <h4 class="modal-title" id="myModalLabel"><i class="bi bi-people-fill"></i> 
                 Customer Details</h4> 
            </div>   

            <div class="modal-body">  

                <div id="customer_parameters">    	

                </div>   

            </div>  

        </div> 

    </div>

</div>

<div class="bodyContent col-md-12">
<h4 class="mb-3">B2B Booking Report</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->
    <?= $GLOBALS['CI']->template->isolated_view('report/report_tab_b2b') ?>

        <div class="card-body p-0">

            <div id="show-search">

                <form method="GET" autocomplete="off">

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

                        <div class="col-sm-6 col-md-3">
                            <label>
                                API Source
                            </label>
				
                            <select class="form-control" name="booking_source">
                                <option>All</option>
                                <?= generate_options($api_source_options, array(@$booking_source)) ?>
                            </select>

                        </div>

                        <div class="col-sm-6 col-md-6">
                        <label>&nbsp;</label>
                        <div class="d-flex flex-wrap justify-content-end gap-3">
                        <button type="submit" class="btn btn-gradient-primary"><i class="bi bi-search"></i> Search</button> 
                        
                        <button type="reset" class="btn btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>  

                        <!-- <a href="<?php echo base_url() . 'index.php/report/b2b_hotel_report? ' ?>" id="clear-filter" class="btn btn-gradient-info"><i class="fa fa-times"></i> Clear Filter</a> -->

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

           
            <?php echo get_table($table_data, $total_rows);?>
        </div>
<?php
function get_table($table_data, $total_rows)
{
	$report_data = '';
	$report_data .= '<table class="table table-sm table-bordered example3" id="b2b_report_hotel_table">
		<thead>
			<tr>
				<th>Sno</th>
				<th>Source</th>
				<th>Reference No</th>
				<th>Status</th>
				<th>Confirmation/<br/>Reference</th>
				<th>Agency_name</th>
				<th>Lead Pax details</th>
				<th>Hotel Name</th>
				<th>No. of rooms<br/>(Adult + Child)</th>
				<th>City</th>
				<th>CheckIn/<br/>CheckOut</th>
				<th>Fare</th>
				<th>Admin Markup</th>
				<th>Agent Markup</th>
				<th>Agent Service Fee</th>
				<th>GST</th>
				<th>Amount Deducted<br/> from Agent</th>
				<th>Total Amount</th>
				<th>BookedOn</th>
				<th>Action</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Sno</th>
				<th>Source</th>
				<th>Reference No</th>
				<th>Status</th>
				<th>Confirmation/<br/>Reference</th>
				<th>Agency_name</th>
				<th>Lead Pax details</th>
				<th>Hotel Name</th>
				<th>No. of rooms<br/>(Adult + Child)</th>
				<th>City</th>
				<th>CheckIn/<br/>CheckOut</th>
				<th>Fare</th>
				<th>Admin Markup</th>
				<th>Agent Markup</th>
				<th>Agent Service Fee</th>
				<th>GST</th>
				<th>Amount Deducted<br/> from Agent</th>
				<th>Total Amount</th>
				<th>BookedOn</th>
				<th>Action</th>
			</tr>
		</tfoot><tbody>';
		
		if (isset($table_data) == true and valid_array($table_data['booking_details']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3+1);
			$booking_details = $table_data['booking_details'];
		    foreach($booking_details as $parent_k => $parent_v) { 
		       //debug($parent_v);exit;
		    	extract($parent_v);
				$action = '';
				$email='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$hotel_check_in);
				$customer_details = customer_details($app_reference, $booking_source, $status);
				$action .= hotel_voucher($app_reference, $booking_source, $status,'b2b');
				$action.='<br/>';
				$action .= hotel_pdf($app_reference, $booking_source, $status);
				$action.='<br/>';
				$action .= hotel_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action .= '<br />' . $customer_details;
                $action .= hotel_GST_Invoice($app_reference, $booking_source, $status, 'b2b');
				$action.='<br/>';
				$booking_hold_msg = "";
                if($booking_status=='BOOKING_HOLD')
                {
                	$action .= confirm_hotel_booking($app_reference, $booking_source,$status,'b2b');
                	if($booking_source == HOTELBED_BOOKING_SOURCE)
                	{
                		$booking_hold_msg = "Confirm this booking till ".date('d-m-Y', strtotime($voucher_date))."";
                	}else
                	{
                		$booking_hold_msg = "Confirm this booking within 30 Minutes from the booking time.";
                	}
                }
		    	if($status == 'BOOKING_CONFIRMED' && $diff > 0) {
					$action .= cancel_hotel_booking($app_reference, $booking_source, $status);
				}
				//cancellation details
				$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				$email = hotel_email_voucher($app_reference, $booking_source, $status);
				$cnf_ref = ($booking_reference != '') ? $confirmation_reference.'/<br/>'.$booking_reference : '';
		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$api_source_name.'</td>
					<td>'.$app_reference.'</td>
					<td><span class="'.booking_status_label($status).'">'.$status.'</span></td>
					<td class="">'.$cnf_ref.'</span></td>
					<td>'.$agency_name.'</td>
					<td>'.$lead_pax_name. '<br/>'.
						  $lead_pax_email.'<br/>'.
						  $lead_pax_phone_number.'
					</td>
					<td>'.$hotel_name.'</td>
					<td>'.$total_rooms.'<br/>('.$adult_count.'+'.$child_count.')</td>
					<td>'.$hotel_location.'</td>
					<td>'.date('d-m-Y', strtotime($hotel_check_in)).'/<br/>'.date('d-m-Y', strtotime($hotel_check_out)).'</td>
					<td><span title="('.$total_fare_markup.'+'.$total_fare_tax.')">'.$currency.''.($total_fare_markup+$total_fare_tax).'</span></td>
					<td>'.$currency.''.$admin_markup.'</td>
					<td>'.$currency.''.$agent_markup.'</td>
					<td>'.$currency.''.$agentServicefee.'</td>
					<td>'.$currency.''.$gst.'</td>
					<td>'.$currency.''.($total_fare_markup +$gst).'</td>
					<td>'.$currency.''.roundoff_number($total_fare_markup+$total_fare_tax+$gst+$agentServicefee).'</td>
					<td>'.date('d-m-Y', strtotime($voucher_date)).'</td>
					<td>
						<div class="dropdown actn_drpdwn">
							<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<i class="bi bi-three-dots-vertical"></i>
							</button>
							<ul class="dropdown-menu actn_menu_drpdwn gap-2" aria-labelledby="dropdownMenu1">
								<div class="action_system d-flex gap-2" role="group">'.$action.'</div>
							</ul>
						</div>
					</td>
				</tr>';
			}
		} else {
			$report_data .= '<tr>
				<td colspan="20" class="text-center no-data-found">
					<div class="empty-state">
						<i class="bi bi-inbox" aria-hidden="true"></i>
						<h4>No Data Found</h4>
						<p>No booking records match your search criteria. Please try adjusting your filters.</p>
					</div>
				</td>
			</tr>';
		}
	$report_data .= '</tbody></table>';
	return $report_data;
}
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="bi bi-x-circle-fill"></i> Cancel</a>';
}
function hotel_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{
	return '<a class="btn btn-sm btn-info send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="bi bi-envelope-fill"></i> Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'hotel/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$status.'" class="col-md-12 btn btn-sm btn-info "><i class="bi bi-info-circle-fill"></i> Cancellation Details</a>';
	}
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_hotel_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="bi bi-info-circle-fill"></i> Update Supplier Info</a>';
	}
}
function customer_details($app_reference, $booking_source = '', $status = '') {
        return '<a  target="_blank" data-app-reference="' . $app_reference . '" data-booking-status="' . $status . '" data-booking-source="' . $booking_source . '" class="btn btn-sm btn-primary flight_u customer_details"><i class="bi bi-person-lines-fill"></i> Pax profile</a>';
}
?>
<script>
$(document).ready(function() {
    // $('#b2b_report_hotel_table').DataTable({
    //     // Disable initial sort 
    //     "aaSorting": []
    // });
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
				 
						var _opp_url = app_base_url+'index.php/voucher/hotel/';
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
	$(".get_hotel_hb_status").on("click",function(e){
  		
		 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/hotel/get_pending_booking_status/';
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
$(document).on('click', '.customer_details', function (e) {
            
            e.preventDefault();
            //$(this).attr('disabled', 'disabled');//disable button
            var app_ref = $(this).data('app-reference');
            var booking_src = $(this).data('booking-source');
            var status = $(this).data('booking-status');
            var module = 'hotel';
            jQuery.ajax({
                type: "GET",
                url: app_base_url + 'index.php/report/get_customer_details/' + app_ref + '/' + booking_src + '/' + status + '/' + module + '/',
                dataType: 'json',
                success: function (res) {
                    $('#customer_parameters').html(res.data);
                    $('#pax_modal').modal('show');
                }
            });
        });
</script>

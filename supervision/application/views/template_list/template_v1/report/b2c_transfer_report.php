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
<h4 class="mb-3">B2C Booking Report</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->
    <?= $GLOBALS['CI']->template->isolated_view('report/report_tab_b2c') ?>

        <div class="card-body p-0">

            <div id="show-search">

                <form method="GET" autocomplete="off">

                    <input type="hidden" name="created_by_id" value="<?= @$created_by_id ?>" >

                    <div class="form-group row gap-0 mb-0">
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
                        
                        <button type="reset" class="btn btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>  

                        <!-- <a href="<?php echo base_url() . 'index.php/report/b2c_transfers_report? ' ?>" id="clear-filter" class="btn btn-gradient-info"><i class="fa fa-times"></i> Clear Filter</a> -->

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
	$report_data .= '<table class="table table-sm table-bordered example3" id="b2c_report_transfer_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation<br/>Reference</th>
			<th>Lead Pax details</th>
			<th>Transfer Type</th>
			<th>Vehicle Name</th>
			<th>Category Name</th>
			<th>No. of Pax<br/>(Adult + Child)</th>
			<th>Journey Type</th>
			<th>From</th>
			<th>To</th>
			<th>Travel Date</th>
			<th>Currency</th>
			<th>Comm.Fare</th>
			<th>Commission</th>
			<th>TDS</th>
			<th>Admin <br/>NetFare</th>
			<th>Admin <br/>Markup</th>
			<th>Convn.Fee</th>
			<th>GST</th>
			<th>Discount</th>
			<th>Customer Paid <br/>amount</th>		
			<th>Booked On</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
		</thead><tfoot>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation<br/>Reference</th>
			<th>Lead Pax details</th>
			<th>Transfer Type</th>
			<th>Vehicle Name</th>
			<th>Category Name</th>
			<th>No. of Pax<br/>(Adult + Child)</th>
			<th>Journey Type</th>
			<th>From</th>
			<th>To</th>
			<th>Travel Date</th>
			<th>Currency</th>			
			<th>Comm.Fare</th>
			<th>Commission</th>
			<th>TDS</th>
			<th>Admin <br/>NetFare</th>
			<th>Admin <br/>Markup</th>
			<th>Convn.Fee</th>
			<th>GST</th>
			<th>Discount</th>
			<th>Customer Paid <br/>amount</th>	
			<th>Booked On</th>
			<th>Status</th>
			<th>Action</th>
		</tr>
		</tfoot><tbody>';
		
		if (isset($table_data) == true and valid_array($table_data['booking_details']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			$booking_details = $table_data['booking_details'];
			//debug($booking_details); exit;
		    foreach($booking_details as $parent_k => $parent_v) { 
		        	extract($parent_v);
		        	//debug($itinerary_details);exit;
				$action = '';
				$email='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$travel_date);
				$action .= transfers_voucher($app_reference, $booking_source, $status,'b2c');
				$action.='<br/>';
				$action .= transfers_pdf($app_reference, $booking_source, $status,'b2c');
				$action.='<br/>';
				$action .= transfer_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action.='<br/>';
				$action .= transfer_GST_Invoice($app_reference, $booking_source, $status, 'b2c');
				$action.='<br/>';
		    	if($status == 'BOOKING_CONFIRMED' && $diff > 0) {
					$action .= cancel_transfers_booking($app_reference, $booking_source, $status,'b2c');
				}
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				//$email = transfers_email_voucher($app_reference, $booking_source, $status);
			
		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$app_reference.'</td>
					<td class="">'.$confirmation_reference.'</span></td>
					<td>'.$lead_pax_name. '<br/>'.
						  $lead_pax_email.'<br/>'.
						  $lead_pax_phone_number.'
					</td>
					<td>'.$transfer_type.'</td>
					<td>'.$vehicle_name.'</td>
					<td>'.$category_name.'</td>
					<td>('.$adult_count.'+'.$child_count.')</td>
					<td>'.$trip_type.'</td>
					<td>'.$itinerary_details[0]['from_location'].'</td>
					<td>'.$itinerary_details[0]['to_location'].'</td>
					<td>'.date('d-m-Y', strtotime($travel_date)).'</td>
					<td>'.$currency.'</td>
					<td>'.($fare).'</td>
					<td>'.($itinerary_details[0]['admin_commission']).'</td>
					<td>'.($itinerary_details[0]['admin_tds']).'</td>
					<td>'.$admin_net_fare.'</td>
					<td>'.$admin_markup.'</td>
					<td>'.$convinence_amount.'</td>
					<td>'.$gst.'</td>
					<td>'.$discount.'</td>
					<td>'.roundoff_number($admin_net_fare+$admin_markup+$convinence_amount-$discount+$gst).'</td>
					<td>'.date('d-m-Y', strtotime($voucher_date)).'</td>
					<td><span class="'.booking_status_label($status).'">'.$status.'</span></td>
					<td><div class="" role="group">'.$action.'</div></td>
				</tr>';
			}
		} else {
			$report_data .= '<tr>
				<td colspan="16" class="text-center no-data-found">
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

function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_sightseeing_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="bi bi-info-circle-fill"></i>Update Supplier Info</a>';
	}
}
function transfer_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="bi bi-envelope-fill"></i> Email Voucher</a>';
}

?>
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
	$(".get_sightseeing_hb_status").on("click",function(e){
  		
	 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/transferv1/get_pending_booking_status/';
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
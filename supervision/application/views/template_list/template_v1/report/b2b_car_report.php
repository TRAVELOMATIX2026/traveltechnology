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

                <form method="GET" autocomplete="off">

                    <input type="hidden" name="created_by_id" value="<?= @$created_by_id ?>" >

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-6 col-md-3">
                            <label>
                                Agent
                            </label>
                            <select class="form-control" name="domain_origin">
                                <option>All</option>
                                <?= generate_options($domain_list, array(@$domain_origin)) ?>
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

                        <div class="col-sm-6 col-md-6">
                        <label>&nbsp;</label>
                        <div class="d-flex flex-wrap justify-content-end gap-3">
                        <button type="submit" class="btn btn-gradient-primary"><i class="bi bi-search"></i> Search</button> 
                        
                        <button type="reset" class="btn btn-gradient-warning"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>  

                        <!-- <a href="<?php echo base_url() . 'index.php/report/hotel? ' ?>" id="clear-filter" class="btn btn-gradient-info"><i class="fa fa-times"></i> Clear Filter</a> -->

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

           
            <table class="table table-sm table-bordered example3" id="b2b_report_car_table">
				<thead>					
				<tr>
					<th>Sno</th>
					<th>Domain Name</th>
					<th>App Reference</th>
					<th>Status</th>
					<th>Confirm.Number</th>
					<th>Customer</th>
					<th>Car Name</th>
					<th>Suppier Name</th>
					<th>Suppier Identifier</th>
					<th>From</th>
					<th>To</th>
					<th>PickUp DateTime</th>
					<th>Drop DateTime</th>
					<th>Booked On</th>
					<th>Comm.Fare</th>
					<th>TDS</th>
					<th>Admin <br/>Markup</th>
					<th>Convn.Fee</th>
					<th>Discount</th>
					<th>Grand Total</th>	
					<th>Action</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th>Sno</th>
					<th>Domain Name</th>	
					<th>App Reference</th>
					<th>Status</th>
					<th>Confirm.Number</th>
					<th>Customer</th>
					<th>Car Name</th>
					<th>Suppier Name</th>
					<th>Suppier Identifier</th>
					<th>From</th>
					<th>To</th>
					<th>PickUp DateTime</th>
					<th>Drop DateTime</th>
					<th>Booked On</th>
					<th>Comm.Fare</th>
					<th>TDS</th>
					<th>Admin <br/>Markup</th>
					<th>Convn.Fee</th>
					<th>Discount</th>
					<th>Grand Total</th>	
					<th>Action</th>
				</tr>
				</tfoot><tbody>
				<?php
				// debug($table_data);exit;
				if (isset($table_data) == true and valid_array($table_data) == true) {
				$segment_3 = $GLOBALS['CI']->uri->segment(3);
				$current_record = (empty($segment_3) ? 0 : $segment_3);
				$booking_details = $table_data['booking_details'];
				// debug($booking_details); exit;
				$s=0;
				$sl = 1;
			    foreach($booking_details as $parent_k => $parent_v) { 
			    	// debug($parent_v);exit;
			        	extract($parent_v);
			        	//debug($parent_v);die;
			       //$domain_markup = $admin_markup_gst;
			       	if($booking_source == 'PTBSID0000000017'){
			       		$booking_api = 'Carnet';
			       	}
			       
		        
				$action = '';
				$emails='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$car_from_date);
				$action .= car_voucher($app_reference, $booking_source, $status);
				$action.='<br/>';
				$action .= car_pdf($app_reference, $booking_source, $status);
				$action.='<br/>';
				$action .= car_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action.='<br/>';
				if($status == 'BOOKING_CONFIRMED' && $diff > 0) {
					$action .= cancel_car_booking($app_reference, $booking_source, $status);
				}
				if($status=='BOOKING_FAILED'){
					$action .=get_exception_log_button($app_reference,$booking_source,$status);
				}
				$emails = car_email_voucher($app_reference, $booking_source, $status);
				$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);

				//check hotel pending booking status

				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				
				
			
			?>
			<tr>
				<td><?php echo $sl++; //($re = $current_record+$parent_k+1)?></td>
				<td><?php echo @$domain_name; ?></td>
				<td><?php echo $app_reference;?></td>
				<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
				<td><?php echo $booking_reference;?></td>
				<td>
				<?php echo $lead_pax_name. '<br/>'.
				  $lead_pax_email."<br/>".
				  $lead_pax_phone_number;?>
				</td>									
				<td><?php echo $car_name ?></td>
				<td><?php echo $car_supplier_name ?></td>
				<td><?php echo $supplier_identifier ?></td>
				<td><?php echo $car_pickup_lcation?> </td>
				<td><?php echo $car_drop_location?> </td>
				<td><?php echo $car_from_date?> <?php echo $pickup_time; ?></td>
				<td><?php echo $car_to_date?> <?php echo $drop_time; ?></td>
				<td><?php echo date('d-m-Y H:i:s', strtotime($created_datetime))?></td>
				<td ><?php echo $total_fare; ?></td>
				<td ><?php echo @$admin_tds; ?></td>
				<td ><?php echo $admin_markup; ?></td>
				<td ><?php echo $convinence_amount; ?></td>
				<td ><?php echo $discount; ?></td>
				<td ><?php echo $grand_total; ?></td>
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
			$s++;
			if(empty($segment_3 = $GLOBALS['CI']->uri->segment(3)))
			{if($re>=20){ break; }
			}else{ if($s>=20) { break; }
			}
			}
			// die;
		}
		else {
			?>
			<tr>
				<td colspan="21" class="text-center no-data-found">
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

<?php

function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function car_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-info send_email_voucher flight_e" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}

function get_cancellation_details_button($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'car/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$status.'" class=" col-md-12 btn btn-sm btn-info flight_u"><i class="far fa-info"></i> Cancellation Details</a>';
		
		//return '<a target="_blank" href="'.base_url().'hotel/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$status.'" class=" col-md-12 btn btn-sm btn-info flight_u"><i class="far fa-info"></i> Cancellation Details</a>';
	}
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_car_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i>Update Supplier Info</a>';
	}
}

function get_exception_log_button($app_reference,$booking_source, $master_booking_status) {
    if (is_domain_user() == false) {
        return '<a data-app_reference="' . $app_reference . '" data-booking_source="' . $booking_source . '" data-status="' . $master_booking_status . '" class="error_log btn btn-sm btn-danger "><i class="far fa-exclamation"></i> <small>ErroLog</small></a>';
    }
}
?>
<!-- Exception Log Modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="exception_log_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Error Log Details - <strong><i id="exception_app_reference"></i></strong></h4>
            </div>
            <div class="modal-body" id="exception_log_container">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Exception Log Modal ends -->
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
					var _opp_url = app_base_url+'index.php/voucher/car/';
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
	  $(".get_car_status").on("click",function(e){
	  		
  		 	app_reference = $(this).data('app-reference');
	        book_reference = $(this).data('booking-source');
	        app_status = $(this).data('status');
	        var _opp_url = app_base_url+'index.php/car/get_pending_booking_status/';
			_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status;
			toastr.info('Please Wait!!!');
			$.get(_opp_url, function(res) {
				if(res==1){
					toastr.info('Status Updated Successfully!!!');	
					//location.reload(); 
				}else{
					toastr.info('Status not updated');
				}
				
				$("#mail_voucher_modal").modal('hide');
			});
	  });
	    $(document).on('click', '.error_log', function (e) {
            e.preventDefault();
            var app_reference = $(this).data('app_reference');
            var booking_source = $(this).data('booking_source');
            var status = $(this).data('status');
            $.get(app_base_url + 'index.php/car/exception_log_details?app_reference=' + app_reference + '&booking_source=' + booking_source + '&status=' + status, function (response) {
                $('#exception_app_reference').empty().text(app_reference);
                $('#exception_log_container').empty().html(response);
                $('#exception_log_modal').modal();

            });
        });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $('[data-bs-toggle="admin_net_fare"]').tooltip();   
});
</script>

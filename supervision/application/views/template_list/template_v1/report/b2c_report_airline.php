
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
                                PNR
                            </label>
                            <input type="text" class="form-control" name="pnr" value="<?= @$pnr ?>" placeholder="PNR">
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

                        <!-- <a href="<?php echo base_url() . 'index.php/report/b2c_flight_report? ' ?>" id="clear-filter" class="btn btn-gradient-info"><i class="fa fa-times"></i> Clear Filter</a> -->

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

           
            <table class="table table-sm table-bordered example3" id="b2c_report_airline_table">
                <thead>					
                    <tr>
                        <th>Sno</th>
                        <th>Source</th>
                        <th>Reference No</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>PNR</th>
                        <th>Lead Pax Details</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Type</th>
                        <th>BookedOn</th>
                        <th>Travel Date</th>
                        <th>Comm.Fare</th>
                        <th>Commission</th>
                        <th>TDS</th>
                        <th>NetFare</th>
                        <th>Admin Markup</th>
                        <th>GST</th>
                        <th>Convenience Fee</th>
                        <th>Discount</th>
                        <th> Customer Paid Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (valid_array($table_data['booking_details']) == true) {
                        $booking_details = $table_data['booking_details'];
                        $segment_3 = $GLOBALS['CI']->uri->segment(3);
                        $current_record = (empty($segment_3) ? 1 : $segment_3 +1);
                        foreach ($booking_details as $parent_k => $parent_v) {
                            extract($parent_v);
                            $action = '';
                            $cancellation_btn = '';
                            $voucher_btn = '';
                            $booked_by = '';
                            //Status Update Button
                            /* if (in_array($status, array('BOOKING_CONFIRMED')) == false) {

                              switch ($booking_source) {

                              case PROVAB_FLIGHT_BOOKING_SOURCE :

                              $status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="far fa-database"></i> Update Status</button>';

                              break;

                              }

                              } */

                            $voucher_btn = flight_voucher($app_reference, $booking_source, $status, 'b2c');

                            //$invoice = flight_invoice($app_reference, $booking_source, $status);

                             $invoice = flight_GST_Invoice($app_reference, $booking_source, $status, 'b2c');

                            $cancel_btn = flight_cancel($app_reference, $booking_source, $status);

                            $pdf_btn = flight_pdf($app_reference, $booking_source, $status, 'b2c');

// echo $pdf_btn;exit;

                            $customer_details = customer_details($app_reference, $booking_source, $status);

                            $error_details = error_details($app_reference, $booking_source, $status);

                            // $pdf_btn ='';

                            $email_btn = flight_voucher_email($app_reference, $booking_source, $status, $email);



                            $jrny_date = date('Y-m-d', strtotime($journey_start));

                            $tdy_date = date('Y-m-d');

                            $diff = get_date_difference($tdy_date, $jrny_date);

                            $action .= $voucher_btn;

                            $action .= '<br />' . $pdf_btn;

                            $action .= '<br />' . $email_btn;

                            $action .= '<br />' . $customer_details;

                            $action .= $invoice;

                            // echo $status;exit;

                           if ($status != 'BOOKING_CONFIRMED' && $status != 'BOOKING_HOLD'  && $status != 'BOOKING_CANCELLED' && $status != 'BOOKING_INPROGRESS') {

                                $action .= '<br />' . $error_details;

                             }

                          

                            if ($diff > 0) {

                                $action .= $cancel_btn;

                            }

                            //$action .= $invoice;

                            if ($status != 'BOOKING_CANCELLED') {



                                if (strtotime('now') < strtotime($parent_v['journey_start'])) {

                                    $update_booking_details_btn = update_booking_details($app_reference, $booking_source, $status, @$booking_payment_details[0]['status']);

                                    $action .= '<br />' . $update_booking_details_btn;

                                }

                            }

                            $action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status'], $parent_v['booking_transaction_details']);

                            ?>

                            <tr>
                                <td><?= ($current_record++) ?></td>
                                <td><?php echo $parent_v['api_source_name']; ?></td>
                                <td><?php echo $app_reference; ?></td>
                                <td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status ?></span></td>
                                <td><span class="<?php echo booking_status_label(@$booking_payment_details[0]['status']) ?>">
                                    <?php echo @$booking_payment_details[0]['status'] ?></span></td>
                                <td><?= $pnr ?></td>
                                <td>
                                    <?php
                                    echo $lead_pax_name . '<br/>' .
                                    $email . "<br/>" .
                                    $phone;
                                    ?>
                                </td>
                                <td><?php echo $from_loc ?></td>
                                <td><?php echo $to_loc ?></td>
                                <td><?php echo $trip_type_label ?></td>
                                <td><?php echo date('d-m-Y', strtotime($booked_date)) ?></td>
                                <td><?php echo date('d-m-Y', strtotime($journey_start)) ?></td>
                                <td><?php echo $fare ?></td>
                                <td><?php echo $net_commission ?></td>
                                <td><?php echo $net_commission_tds ?></td>
                                <td><?php echo $net_fare ?></td>										
                                <td><?php echo $admin_markup ?></td>		
                                <td><?php echo $gst; ?></td>										
                                <td><?php echo $convinence_amount ?></td>
                                <td><?php echo $discount ?></td>
                                <td><?php echo $grand_total ?></td>
                                <td>
                                    <div class="dropdown actn_drpdwn">
                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu actn_menu_drpdwn gap-2" aria-labelledby="dropdownMenu1">
                                            <div class="action_system d-flex gap-2" role="group">
                                                <?php echo $action; ?>
                                            </div>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="12" class="text-center no-data-found">
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

    $(document).ready(function () {

        /*$('#b2c_report_airline_table').DataTable({

         // Disable initial sort 

         "aaSorting": []

         });*/

        //update-source-status update status of the booking from api

        $(document).on('click', '.update-source-status', function (e) {

            e.preventDefault();

            $(this).attr('disabled', 'disabled');//disable button

            var app_ref = $(this).data('app-reference');

            $.get(app_base_url + 'index.php/flight/get_booking_details/' + app_ref, function (response) {

                console.log(response);

            });

        });

        /*$('.update_flight_booking_details').on('click', function(e) {

         e.preventDefault();

         var _user_status = this.value;

         var _opp_url = app_base_url+'index.php/report/update_flight_booking_details/';

         _opp_url = _opp_url+$(this).data('app-reference')+'/'+$(this).data('booking-source');

         toastr.info('Please Wait!!!');

         $.get(_opp_url, function() {

         toastr.info('Updated Successfully!!!');

         });

         });*/

        $('.update_flight_booking_details').on('click', function (e) 
        {
            e.preventDefault();
            var _user_status = this.value;
            var app_ref = $(this).data('app-reference');
            var booking_src = $(this).data('booking-source');
            var status = $(this).data('booking-status');
            toastr.info('Please Wait!!!');
            $.ajax({
                type: "GET",
                url: app_base_url + 'index.php/report/update_pnr_details/' + app_ref + '/' + booking_src + '/' + status,
                dataType: 'json',
                success: function (res) {
                    var message = res === '1' ? 'Updated Successfully!!!' : 'Update Failed!!!';
                    toastr.info(message);
                    if (res === '1') {
                        location.reload();
                    }
                }
            });
        });



        //send the email voucher

        $('.send_email_voucher').on('click', function (e) {

            $("#mail_voucher_modal").modal('show');

            $('#mail_voucher_error_message').empty();

            email = $(this).data('recipient_email');
            var voucher_type = 'price_receipt';

            $("#voucher_recipient_email").val(email);

            app_reference = $(this).data('app-reference');

            book_reference = $(this).data('booking-source');

            app_status = $(this).data('app-status');

            $("#send_mail_btn").off('click').on('click', function (e) {

                email = $("#voucher_recipient_email").val();
                voucher_type = $("#voucher_type").val();
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

                if (email != '') {

                    if (!emailReg.test(email)) {

                        $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');

                        return false;

                    }



                    var _opp_url = app_base_url + 'index.php/voucher/b2c_flight_voucher/';

                    _opp_url = _opp_url + app_reference + '/' + book_reference + '/' + app_status + '/email_voucher/' + email+'/'+voucher_type;
                    //alert(_opp_url);

                    toastr.info('Please Wait!!!');

                    $.get(_opp_url, function () {



                        toastr.info('Email sent  Successfully!!!');

                        $("#mail_voucher_modal").modal('hide');

                    });

                } else {

                    $('#mail_voucher_error_message').empty().text('Please Enter Email ID');

                }

            });



        });

        	$(document).on('click', '.error_log', function(e){

		e.preventDefault();

		var app_reference = $(this).data('app-reference');

		var booking_source = $(this).data('booking_source');

		var status = $(this).data('status');

		$.get(app_base_url+'index.php/flight/exception_log_details?app_reference='+app_reference+'&booking_source='+booking_source+'&status='+status, function(response){

			$('#exception_app_reference').empty().text(app_reference);

			$('#exception_log_container').empty().html(response);

			$('#exception_log_modal').modal();

			

		});

	});



    });

    

</script>

<?php



function get_accomodation_cancellation($courseType, $refId) {

    return '<a href="' . base_url() . 'index.php/booking/accomodation_cancellation?courseType=' . $courseType . '&refId=' . $refId . '" class="btn btn-sm btn-danger "><i class="bi bi-x-circle-fill"></i> Cancel</a>';

}



function update_booking_details($app_reference, $booking_source, $booking_status, $payement_status) {

    if ($payement_status == 'accepted') {

        return '<a class="btn btn-sm btn-danger update_flight_booking_details" data-app-reference="' . $app_reference . '" data-booking-source="' . $booking_source . '"data-booking-status="' . $booking_status . '"><i class="bi bi-arrow-repeat"></i> Update PNR Details</a>';

    }

}



function flight_voucher_email($app_reference, $booking_source, $status, $recipient_email) {



    return '<a class="btn btn-sm btn-info send_email_voucher" data-app-status="' . $status . '"   data-app-reference="' . $app_reference . '" data-booking-source="' . $booking_source . '"data-recipient_email="' . $recipient_email . '"><i class="bi bi-envelope-fill"></i> Email Voucher</a>';

}



function customer_details($app_reference, $booking_source = '', $status = '') {

    return '<a  target="_blank" data-app-reference="' . $app_reference . '" data-booking-status="' . $status . '" data-booking-source="' . $booking_source . '" class="btn btn-sm btn-primary flight_u customer_details"><i class="bi bi-person-lines-fill"></i> Pax profile</a>';

}

function error_details($app_reference, $booking_source = '', $status = '',$master_booking_status='') {

    return '<a data-app-reference="' . $app_reference . '" data-booking_source="' . $booking_source . '" data-status="' . $master_booking_status . '" class="error_log btn btn-sm btn-danger "><i class="bi bi-exclamation-triangle-fill"></i> <small>ErroLog</small></a>';

}

function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status, $booking_customer_details) {

    //echo '<pre>';     print_r ($master_booking_status); die;

    $status = 'BOOKING_CONFIRMED';

    if ($master_booking_status == 'BOOKING_CANCELLED') {

        $status = 'BOOKING_CANCELLED';

    } else if ($master_booking_status == 'BOOKING_FAILED') {

        foreach ($booking_customer_details as $tk => $tv) {

            foreach ($tv['booking_customer_details'] as $pk => $pv) {

                if ($pv['status'] == 'BOOKING_CANCELLED') {

                    $status = 'BOOKING_CANCELLED';

                    break;

                }

            }

        }

    }

    if ($status == 'BOOKING_CANCELLED') {

        return '<a target="_blank" href="' . base_url() . 'index.php/flight/ticket_cancellation_details?app_reference=' . $app_reference . '&booking_source=' . $booking_source . '&status=' . $master_booking_status . '" class="col-md-12 btn btn-sm btn-info "><i class="bi bi-info-circle-fill"></i> Cancellation Details</a>';

    }

}

?>

<script type="text/javascript">// Show the customer Details

    $(document).on('click', '.customer_details', function (e) {



        e.preventDefault();

        //$(this).attr('disabled', 'disabled');//disable button

        var app_ref = $(this).data('app-reference');

        var booking_src = $(this).data('booking-source');

        var status = $(this).data('booking-status');

        var module = 'flight';



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
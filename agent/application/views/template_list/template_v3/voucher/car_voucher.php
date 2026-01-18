<style type="text/css">
th, td {
	padding: 5px
}
</style>
<?php
$booking_details = $data['booking_details'][0];
// debug($booking_details);exit;
$itineray_details = $booking_details ['itinerary_details'];
$customer_details = $booking_details ['customer_details'];
$attributes = json_decode($itineray_details[0]['attributes'], true);
$priced_coverage = json_decode($itineray_details[0]['priced_coverage'], true);
$cancellation_poicy = json_decode($itineray_details[0]['cancellation_poicy'], true);
$extra_service_details = $booking_details ['extra_service_details'];
// debug($booking_details);exit;
?>
<div style="background:#ccc; width:100%; position:relative;padding: 50px;">
    <!-- <table cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:12px; font-family: 'Open Sans', sans-serif; width:850px; margin: 10px auto;background-color:#fff; border-collapse:separate; color: #000;">
        <tbody>
            <tr>
                <td class="text-center" style="background: <?=($receipt_type == 'no_price') ? '#f18604' : '#008100' ;?>;border: 2px solid #fff"> <a href="<?=base_url();?>voucher/car/<?= @$booking_details['app_reference'] ?>/<?= @$booking_details['booking_source'] ?>/<?= @$booking_details ['status'] ?>/show_voucher/no_price" target="_blank" style="font-size: 16px;font-weight: bold;color: white;"> No Price Included </a> </td>
                <td class="text-center" style="background: <?=($receipt_type == 'agent_receipt') ? '#f18604' : '#008100' ;?>;border: 2px solid #fff"><a href="<?=base_url();?>voucher/car/<?= @$booking_details['app_reference'] ?>/<?= @$booking_details['booking_source'] ?>/<?= @$booking_details ['status']?>/show_voucher/agent_receipt" target="_blank" style="font-size: 16px;font-weight: bold;color: white;"> Agent Receipt </a> </td>
            </tr>
        </tbody>
    </table> -->
<div class="table-responsive" id="tickect_car" style="width: 850px; margin: 0 auto; background-color: #fff; padding: 20px; font-family: 'Open Sans', sans-serif;">
<table style="border-collapse: collapse; background: #ffffff; font-size: 14px; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
	<!-- <tr><td style="padding: 10px;width:65%;"><span style="max-height: 56px" ><img src="<?php echo $booking_details['car_image']?>" width ="100" height="100"/></span></td></tr> -->
	<tr>
		<td style="padding:10px 20px 0px;">
			<table width="100%">
				<tr style="background: #008100;color:#fff;">
					<td colspan="2" style="padding-bottom:10px"><img style="width:170px;" src="<?= $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()) ?>" /></td>
					<td colspan="2" style="padding-bottom:10px" align="right"><img src="<?php echo $booking_details['car_image']?>" width ="100" height="100"/></td>
				</tr>
			</table>
		</td>
	</tr>
	
		<tr>
			<td style="border-collapse: collapse; padding: 10px 20px 20px">
				<table width="100%" style="border-collapse: collapse;"
					cellpadding="0" cellspacing="0" border="0">
					
					<tr>
						<td style="padding: 10px;">
							<table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
								<!-- <tr> 
									<td style="font-size: 22px; line-height: 30px; width: 100%; display: block; font-weight: 600; text-align: center;    padding-bottom: 15px;">E-Ticket</td>
								 </tr> -->
					<!-- <tr>
						<td>
							<table width="100%" style="border-collapse: collapse;"
								cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td style="padding: 10px; width: 65%;">
									<td style="padding: 10px; width: 35%;">
										<table width="100%"
											style="border-collapse: collapse; text-align: right; line-height: 15px;"
											cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td style="font-size: 14px;"><span
													style="width: 100%; float: left"></span></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr> -->
								<tr>
									<td width="50%"
										style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Reservation Ticket (<?php echo ucfirst($booking_details['car_pickup_lcation']).' To '.ucfirst($booking_details['car_drop_location']);?>)</td>
								</tr>
								<tr>
									<td style=" border: 1px solid #008100;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td width="20%"><strong>Car name</strong></td>
												<td width="30%"><?php echo $booking_details['car_name'];?></td>
												<td width="20%"><strong>Reference Number</strong></td>
												<td width="30%"><?php echo $booking_details['booking_reference'];?></td>
											</tr>
											<tr>
												<td width="20%"><strong>Supplier Name</strong></td>
												<td width="30%"><?php echo $booking_details['car_supplier_name'];?></td>
												<td width="20%"><strong>Supplier Idenfier</strong></td>
												<td width="30%"><?php echo $booking_details['supplier_identifier'];?></td>
												
											</tr>
											<tr>
												<td width="20%"><strong>Passengers</strong></td>
												<td width="30%"><?php echo $attributes['pass_quantity'];?></td>
												<td width="20%"><strong>Doors</strong></td>
												<td width="30%"><?php echo $attributes['door_count'];?></td>
											</tr>
											<tr>
												<td width="20%"><strong>Bags</strong></td>
												<td width="30%"><?php echo $attributes['bagg_quantity'];?></td>
												<td width="20%"><strong>AirConditioning</strong></td>
												<td width="30%"><?php echo $attributes['air_condition'];?></td>
											</tr>
											
											<tr>
												<td><strong>Transmission</strong></td>
												<td><?php echo $attributes['transmission_type'];?></td>
												<td><strong>Booking Status</strong></td>
												<td><strong
													class="<?php echo booking_status_label( $booking_details['status']);?>"
													style="font-size: 14px;">
											<?php
											switch ($booking_details ['status']) {
												case 'BOOKING_CONFIRMED' :
													echo 'CONFIRMED';
													break;
												case 'BOOKING_CANCELLED' :
													echo 'CANCELLED';
													break;
												case 'BOOKING_FAILED' :
													echo 'FAILED';
													break;
												case 'BOOKING_INPROGRESS' :
													echo 'INPROGRESS';
													break;
												case 'BOOKING_INCOMPLETE' :
													echo 'INCOMPLETE';
													break;
												case 'BOOKING_HOLD' :
													echo 'HOLD';
													break;
												case 'BOOKING_PENDING' :
													echo 'PENDING';
													break;
												case 'BOOKING_ERROR' :
													echo 'ERROR';
													break;
											}
											
											?>
											</strong></td>
											</tr>
											<tr>
												<td><strong>PickUp Date Time</strong></td>
												<td><?php echo $booking_details['car_from_date'];?> <?php echo $booking_details['pickup_time'];?></td>
												<td><strong>Drop Date Time</strong></td>
												<td><?php echo $booking_details['car_to_date'];?> <?php echo $booking_details['drop_time'];?></td>
											</tr>
											<tr>
												<td><strong>Pickup Location</strong></td>
												<td><?php echo $booking_details['car_pickup_address'];?></td>
												<td><strong>Drop Location</strong></td>
												<td><?php echo $booking_details['car_drop_address'];?></td>
											</tr>
											<tr>
												<td><strong>Pickup Opening Hours</strong></td>
												<td><?php echo $attributes['pickup_opening_hours'];?></td>
												<td><strong>Drop Opening Hours</strong></td>
												<td><?php echo $attributes['drop_opening_hours'];?></td>
											</tr>
											
											<tr>
												<td><strong>Booking Date</strong></td>
												<td><?php echo $booking_details['created_datetime'];?></td>
												<td><strong>Travel Date Time</strong></td>
												<td><?=@date("d M Y",strtotime($booking_details['car_from_date']))?> <?=get_time($booking_details['pickup_time']);?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td
										style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Travelers
										Information</td>
								</tr>
								<tr>
									<td style=" border: 1px solid #008100;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>FirstName</strong></td>
												<td><strong>LastName</strong></td>
												<td><strong>Phone</strong></td>
												<td><strong>Email ID</strong></td>
												<td><strong>City</strong></td>
												<td><strong>Country</strong></td>
												<td><strong>PinCode</strong></td>
											</tr>
										
                                          <tr>
												<td><?php echo $customer_details[0]['first_name'];?></td>
												<td><?php echo $customer_details[0]['last_name'];?></td>
												<td><?php echo $customer_details[0]['phone'];?></td>
												<td><?php echo $booking_details['email'];?></td>
												<td><?php echo $customer_details[0]['city'];?></td>
												<td><?php echo $customer_details[0]['country_name'];?></td>
												<td><?php echo $customer_details[0]['pincode'];?></td>
											</tr>
                            		 
									</table>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td style=" border: 1px solid #008100;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr style="font-size: 15px;">
												<td colspan="5" align="right"><strong>Total Fare</strong></td>
												<td><strong><?=@$booking_details['currency']?>  <?=@$booking_details['grand_total']?></strong></td>
											</tr>
										</table>
									</td>
									<td></td>
								</tr> 
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Rental Price Includes
										</td>
								</tr>
								<tr>
									<td style="padding: 10px; border: 1px solid #008100;">
								<?php 
								if($priced_coverage){
								foreach($priced_coverage  as $key => $coverage){
								$key = $key+1;
								if(!empty($coverage['Desscription'])){
									echo $key.". ".$coverage['CoverageType'].' - '.$coverage['Desscription'];
								}
								else{
									echo $key.". ".$coverage['CoverageType'];
								}
								echo "<br/>";
								?>
										<?php } 
										}?>
								</td>
										</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Payment Details
										</td>
								</tr>
								<tr>
									<td style="padding: 10px; border: 1px solid #008100;"><?php echo $attributes['payment_rule'];?>
										</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Cancellation Policy
										</td>
								</tr>
								<tr>
									<td
										style="padding: 10px; border: 1px solid #008100;">
										<?php if(isset($cancellation_poicy) && !empty($cancellation_poicy)){
										foreach($cancellation_poicy as $policy){
											if($policy['Amount'] == 0){
												echo 'No Cancellation Fee between ' .$policy['FromDate'].' To '.$policy['ToDate'];
											}
											else{
												echo  $booking_details['currency'].' '.$policy['Amount'].' Cancellation Fee between ' .$policy['FromDate'].' To '.$policy['ToDate'];
											}
											echo "<br/>";
										} }?>
										</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<?php 
								// debug($booking_details);exit;
								if((isset($extra_service_details) && valid_array($extra_service_details)) || ($booking_details['oneway_fee']!= 0)){ ?>
								<tr>
									<td style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Pay at Pickup
										</td>
								</tr>
								<tr>
									<td
										style="padding: 10px; border: 1px solid #008100;">
										<?php
											foreach($priced_coverage as $coverage){
												if($coverage['IncludedInRate'] != true && $coverage['Amount'] != 0) {
													// debug($coverage);exit;
													echo $coverage['CoverageType'] .' - '.$coverage['Currency'].' '.$coverage['Amount'].' '.$coverage['Desscription'];
													echo "<br/>";
												}
												
											}
									
											?>
											<?php
											foreach($extra_service_details as $details){
												echo $details['description'] .' - '.$booking_details['currency'].' '.$details['amount'];
												echo "<br/>";
											}
									
											?>
											
										</td>
								</tr>
								
								<?php } ?>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td width="50%" style="padding: 10px; border: 1px solid #008100; font-size: 14px; font-weight: bold;background: #008100;color:#fff;">Terms and Conditions</td>
								</tr>
								<tr>
									<td width="100%" style=" border: 1px solid #008100;">
										<table width="100%" cellpadding="5"
											style="padding: 10px 20px; font-size: 14px;">
											<tr>
												<td>1.Please ensure that operator PNR is filled, otherwise
													the ticket is not valid.</td>
											</tr>
											
										</table>
									</td>
								</tr>
								<tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #008100; padding-bottom:15px; font-size:12px; color:#555"><?php echo $data['terms_conditions']; ?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" align="" style="padding-top:10px">
                                       <span style="font-weight:600;"><?php echo strtoupper($this->entity_domain_name) ?></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span style="font-weight:600;">Contact No: </span>+<?=$this->entity_country_code;?> <?=$this->entity_phone;?>&nbsp;&nbsp; | &nbsp;&nbsp;<span  style="font-weight:600;">Address: </span><?php echo $this->entity_address ?>
                                    </td>
                                 </tr>
								<tr>
									<td colspan="4" align="right">
										<span style="font-size:12px;">Powered by: </span> <span style="display:inline-block;"> <img style="width:90px;" src="<?php echo $GLOBALS['CI']->template->template_images('starlegend_logo.png'); ?>" /> </span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>
 <table class="no-print" id="printOption"onclick="getPrint();" style="border-collapse: collapse;font-size: 14px; margin: 10px auto; font-family: arial;" width="70%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
		<tr>
			<td align="center"><input style="background:#008100; height:34px; padding:0px 10px; border-radius:4px; border:none; color:#fff; margin:0 2px;" type="button" value="Print" />
		</tr>
	</tbody>
</table>
</div>
</div>

<script type="text/javascript">
   function getPrint() {
    var html = "<html><head>";
      html += "<style>@media print { .no-print { display: none !important; } }</style>";
      html += "</head><body>";
      html += document.getElementById('tickect_car').innerHTML;
      html += "</body></html>";

   var printWin = window.open();
   printWin.document.write(html);
   printWin.document.close();
   printWin.focus();
   printWin.print();
}
</script>
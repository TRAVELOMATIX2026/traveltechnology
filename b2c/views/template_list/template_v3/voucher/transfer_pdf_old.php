<?php
$base_image_path = BASE_PDF_PATH . SYSTEM_IMAGE_DIR;
$booking_details = $data['booking_details'][0];
$itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
//debug($itinerary_details);

$attributes = json_decode($booking_details['attributes'], true);
$iti_attributes = json_decode($itinerary_details['attributes'], true);

if (isset($booking_details)) {
   $app_reference = $booking_details['app_reference'];
}
if (isset($booking_details)) {
   $booking_source = $booking_details['booking_source'];
}
if (isset($booking_details)) {
   $status = $booking_details['status'];
}
if (isset($booking_details)) {
   $lead_pax_email = $booking_details['lead_pax_email'];
}
$customer_details = $booking_details['customer_details'];
// debug($booking_details);
// exit;

?>

<table style="border-collapse: collapse; background: #ffffff;font-size: 12pt; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
   <tbody>
      <!-- <tr>
         <td style="border-collapse: collapse; padding:10px 20px 20px" ><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0"> -->
      <tr>
         <td style="font-size:15pt; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td>
      </tr>
      <tr>
         <td style="background-color: #99ddef;">
            <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
               <tr>
                  <td style="padding: 10px;width:65%;"><img style="width:150px;" src="<?= BASE_PDF_PATH . $GLOBALS['CI']->template->domain_images($data['logo']) ?>"></td>
                  <td style="padding: 10px;width:35%">
                     <table width="100%" style="border-collapse: collapse;text-align: right; font-size:10pt" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                           <td style="line-height:16px" align="right"><span>Travel Date: <?= date("l\, jS F Y", strtotime($booking_details['travel_date'])); ?></span><br><span>Booking Reference: <?= $booking_details['app_reference'] ?></span></td>
                        </tr>
                     </table>
                  </td>
               </tr>

            </table>
         </td>
      </tr>
      <!-- <tr><td style="line-height:6px;">&nbsp;</td></tr>     -->
      <tr>
         <td align="right" style="line-height:24px;font-size:10pt;border-top:1px solid #00a9d6;border-bottom:1px solid #00a9d6;padding: 5px;">Status: <strong class="<?php echo booking_status_label($booking_details['status']); ?>" style=" font-size:10pt;"><?php
                                                                                                                                                                                                                                                               switch ($booking_details['status']) {
                                                                                                                                                                                                                                                                  case 'BOOKING_CONFIRMED':
                                                                                                                                                                                                                                                                     echo 'CONFIRMED';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_CANCELLED':
                                                                                                                                                                                                                                                                     echo 'CANCELLED';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_FAILED':
                                                                                                                                                                                                                                                                     echo 'FAILED';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_INPROGRESS':
                                                                                                                                                                                                                                                                     echo 'INPROGRESS';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_INCOMPLETE':
                                                                                                                                                                                                                                                                     echo 'INCOMPLETE';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_HOLD':
                                                                                                                                                                                                                                                                     echo 'HOLD';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_PENDING':
                                                                                                                                                                                                                                                                     echo 'PENDING';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                                  case 'BOOKING_ERROR':
                                                                                                                                                                                                                                                                     echo 'ERROR';
                                                                                                                                                                                                                                                                     break;
                                                                                                                                                                                                                                                               }
                                                                                                                                                                                                                                                               ?></strong>
         </td>
      </tr>
      <tr>
         <td style="line-height:6px;">&nbsp;</td>
      </tr>
      <tr>
         <td style="padding:0;">
            <table width="100%" cellpadding="5" style="padding: 10px;font-size: 10pt;padding:5px;">
               <tbody>
                  <tr>
                     <?php if ($itinerary_details['image']) : ?>
                        <td width="26%" style="padding:10px 0"><img style="width:130px; height:107px;" src="<?= $itinerary_details['image'] ?>" /></td>
                     <?php else : ?>
                        <td style="padding:10px 0"><img style="width:130px; height:107px;" src="<?= BASE_PDF_PATH . $GLOBALS['CI']->template->template_images("no_image_available.jpg"); ?>" /></td>
                     <?php endif; ?>
                     <td width="40%" valign="top" style="padding:10px;line-height:15px;"><span style="font-size:11pt;color:#00a9d6;vertical-align:middle;font-weight: 600;"><?= $booking_details['transfer_type'] ?> - <?= $booking_details['vehicle_name'] ?> - <?= $booking_details['category_name'] ?></span><br><span data-v1=<?= $itinerary_details['from_location'] ?> data-v1=<?= $itinerary_details['to_location'] ?> style="display: block;line-height:22px;font-size: 13px;"><?= $itinerary_details['from_location'] ?> - <?= $itinerary_details['to_location'] ?> </span><br></td>
                     <td width="34%" style="padding:10px 0;text-align: center;line-height:25px;">
                        <table style="border:2px solid #808080;">
                           <tbody>
                              <tr>
                                 <td><span style="font-size:11pt; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><br><span style="font-size:10pt;padding-bottom: 5px;display:block;font-weight: 600;"><?= $booking_details['booking_reference'] ?></span></span></td>
                              </tr>
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>
      <tr>
         <td style="padding: 0px;">
            <table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
               <tr>
                  <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:10pt; padding:5px; line-height:normal;"><img width="12" src="<?= $base_image_path . 'hotel_v.png' ?>" /> <span style="font-size:10pt;color:#fff;line-height:12px;"> &nbsp;Activity Details</span></td>
               </tr>
               <tr>
                  <td width="100%" style="border: 1px solid #00a9d6;padding:0">
                     <table width="100%" cellpadding="5" style="padding: 0px;font-size: 9pt;">
                        <tr>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Vat No</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Source Market Emergency No</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Departure Date</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Departure Time</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Retrun Date</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Return Time</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center">Total Traveler(s)</td>
                        </tr>
                        <tr>
                           <td style="padding:5px"><span style="width:100%; float:left"> <?= @$attributes['supplier_name'] ?></span></td>
                           <td style="padding:5px"><span style="width:100%; float:left"> <?= @$attributes['supplier_vat_number'] ?></span></td>
                           <td style="padding:5px"><span style="width:100%; float:left"> <?= @$attributes['source_market_emergency_number'] ?></span></td>
                           <?php
                           $total_travel_text = $attributes['TotalAdults'] . ' Adult(s) ';
                           if ($attributes['TotalChilds'] > 0) {
                              $total_travel_text .= $attributes['TotalChilds'] . 'Children(s) Child Ages(' . $attributes['ChildAge'] . ')';
                           }
                           $total_travel_count = $booking_details['adult_count'] + $booking_details['child_count'];
                           ?>
                           <td style="padding:5px"><?= $booking_details['travel_date'] ?></td>
                           <td style="padding:5px"><?= $booking_details['travel_time'] ?></td>
                           <td style="padding:5px"><?php if ($booking_details['return_date'] != "0000-00-00") {
                                                      echo $booking_details['return_date'];
                                                   } else {
                                                      echo '--';
                                                   } ?></td>
                           <td style="padding:5px"><?php if (empty($booking_details['return_time']) == false) {
                                                      echo $booking_details['return_time'];
                                                   } else {
                                                      echo '--';
                                                   } ?></td>
                           <td style="padding:5px" align="center"><?= $total_travel_count ?></td>
                        </tr>
                        <tr>
                           <!-- <td>Phone</td> -->
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Maximum Capacity</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Minimum Capacity</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Luggage</td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Estimated Time</td>

                           <?php if (isset($attributes['BookingQuestions'])) {
                              if (valid_array($attributes['BookingQuestions'])) {
                                 foreach ($attributes['BookingQuestions'] as $question) { ?>
                                    <td style="background-color:#d9d9d9;padding:5px;color: #333333;"><?php echo ucwords(strtolower($question['question'])); ?></td>

                           <?php }
                              }
                           } ?>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;"></td>
                           <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center"></td>
                        </tr>
                        <tr>
                           <td style="padding:5px"><span style="width:100%; float:left"> <?= @$iti_attributes['MinimumPassengercount'] ?></span></td>
                           <td style="padding:5px"><span style="width:100%; float:left"> <?= @$iti_attributes['MaximumPassengerCount'] ?></span></td>
                           <td style="padding:5px"><span style="width:100%; float:left"> <?= @$iti_attributes['Suitcases'] ?></span></td>

                           <td style="padding:5px"><?= $iti_attributes['EstimatedTime'] ?></td>
                           <?php if (isset($attributes['BookingQuestions'])) {
                              if (valid_array($attributes['BookingQuestions'])) {
                                 foreach ($attributes['BookingQuestions'] as $question) { ?>
                                    <td style="padding:5px"><?= $question['answers']; ?></td>

                           <?php }
                              }
                           } ?>
                           <td style="padding:5px"></td>
                           <td style="padding:5px" align="center"></td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>

      <tr>
         <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:9pt; padding:5px;"><img width="12" style="vertical-align:middle" src="<?= $base_image_path . 'people_group.png' ?>" /> <span style="font-size:9pt;color:#fff;vertical-align:middle;"> &nbsp;Traveller(s) Details</span></td>
      </tr>
      <tr>
         <td width="100%" style="border: 1px solid #666666;padding:0">
            <table width="100%" cellpadding="5" style="padding: 0px;font-size: 9pt;">
               <tr>
                  <td style="background-color:#d9d9d9;padding:5px;color: #333333;" width="100%">Sr No.</td>
                  <td style="background-color:#d9d9d9;padding:5px;color: #333333;" width="100%">Passenger(s) Name</td>
                  <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Paxes</td>
               </tr>
               <?php $i = 1; ?>
               <?php foreach ($customer_details as $name) : ?>
                  <tr>
                     <td style="padding:5px;"><?= $i ?></td>
                     <td style="padding:5px"><?= $name['title'] . '.  ' . $name['first_name'] . ' ' . $name['last_name'] ?></td>
                     <td style="padding:5px;"><?= $total_travel_text ?></td>
                  </tr>
                  <?php $i++; ?>
               <?php endforeach; ?>
            </table>
         </td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>
      <tr>
         <td width="100%" style="padding:0">
            <table width="100%" cellpadding="0" style="padding: 0;font-size: 9pt;">
               <tbody>
                  <tr>
                     <td>
                        <table cellspacing="0" cellpadding="5" width="100%" style="font-size:9pt; padding:0;border:1px solid #9a9a9a;">
                           <tbody>
                              <tr>
                                 <td style="border-bottom:1px solid #ccc;padding:5px;" width="100%"><span style="font-size:10pt">Payment Details</span></td>
                                 <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:10pt">Amount (<?= $booking_details['currency'] ?>)</span></td>
                              </tr>

                              <tr>
                                 <td style="padding:5px"><span>Base Fare</span></td>
                                 <td style="padding:5px"><span><?php echo roundoff_number($booking_details['product_total_price']); ?></span></td>
                              </tr>
                              <tr>
                                 <td style="padding:5px"><span>Taxes</span></td>
                                 <td style="padding:5px"><span><?= $booking_details['convinence_amount'] ?></span></td>
                              </tr>
                              <?php if ($itinerary_details['gst'] > 0) { ?>
                                 <tr>
                                    <td style="padding:5px"><span>GST</span></td>
                                    <td style="padding:5px"><span><?php echo round($itinerary_details['gst']); ?></span></td>
                                 </tr>
                              <?php } ?>
                              <tr>
                                 <td style="padding:5px"><span>Discount</span></td>
                                 <td style="padding:5px"><span><?php echo roundoff_number($booking_details['discount']); ?></span></td>
                              </tr>
                              <tr>
                                 <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px">Total Fare</span></td>
                                 <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['product_total_price'] + $booking_details['convinence_amount'] - $booking_details['discount']); ?></span></td>
                              </tr>

                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>
      <tr>
         <td align="center" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:10pt; color:#555;">Customer Contact Details | E-mail : <?= $customer_details[0]['email'] ?> | Contact No : <?= $customer_details[0]['phone'] ?></span></td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>
      <tr>
         <td><span style="font-size: 11pt;line-height:16px;font-weight: 500;">Cancellation Policy</span></td>
      </tr>
      <tr>
         <td style="padding:5px 0px;">
            <span style="font-size:9pt; color:#555; line-height:16px;"><?= $attributes['TM_Cancellation_Policy'] ?></span>
         </td>
      </tr>
      <tr>
         <td><span style="font-size: 11pt;line-height:12px;font-weight: 500;">Pickup Description</span>
            <!-- <span style="font-size: 10pt;line-height:12px;font-weight: 500;color:#333">Information</span> -->
         </td>
      </tr>
      <tr>
         <td style="padding-top:5px;"><span style="font-size:9pt; color:#555; line-height:14px"><?= $iti_attributes['PickupDescription'] ?></span></td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>
      <tr>
         <td><span style="font-size: 11pt;line-height:12px;font-weight: 500;color:#333">Terms and Conditions</span></td>
      </tr>
      <tr>
         <td style="border-bottom:1px solid #999999; padding-bottom:15px;padding-top:5px; font-size:9pt; color:#555;line-height:16px"> <?php echo $data['terms_conditions']; ?></td>
      </tr>
      <tr>
         <td style="line-height:2px;">&nbsp;</td>
      </tr>
      <tr>
         <td align="right" style="padding-top:10px;font-size:9pt;line-height:16px"><?= $data['domainname'] ?><br>ContactNo : <?= $data['phone_code'] ?><?= $data['phone'] ?><br><?= $data['address'] ?></td>
      </tr>

      <!-- </table>
         </td>
      </tr> -->
   </tbody>
</table>
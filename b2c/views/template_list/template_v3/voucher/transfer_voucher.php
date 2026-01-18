<?php 
$base_image_path = BASE_PDF_PATH . SYSTEM_IMAGE_DIR;
  // exit;
 $booking_details = $data['booking_details'][0];
  $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  //debug($itinerary_details);
  $iti_attributes = json_decode($itinerary_details['attributes'], true);

  $attributes = json_decode($booking_details['attributes'],true);
//debug($booking_details);exit;

if(isset($booking_details)){
    $app_reference = $booking_details['app_reference'];
  }
  if(isset($booking_details)){
    $booking_source = $booking_details['booking_source'];
  }
  if(isset($booking_details)){
    $status = $booking_details['status'];
  }
  if(isset($booking_details)){
    $lead_pax_email = $booking_details['lead_pax_email'];
  }
  
  $customer_details = $booking_details['customer_details'];
 

?>


<!-- <div class="table-responsive" style="width:100%; position:relative;background: #ccc;padding: 20px 0;" id="tickect_hotel"> -->
   <table class="table" cellpadding="0" cellspacing="0" width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif; width:900px; margin:0px auto;background-color:#fff; padding:50px 45px;">
      <tbody>
         <tr>
            <td style="border-collapse: collapse; padding:50px 35px;">
               <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                     <tr>
                        <td style="padding: 0px;">
                           <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                              <tbody>
                                 <tr>
                                    <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color: #99ddef;padding: 10px;">
                                       <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                                          <tbody>
                                             <tr>
                                                <td style="padding: 0px;"><img style="width:200px;" src="<?= BASE_PDF_PATH.$GLOBALS['CI']->template->domain_images($data['logo'])?>"></td>
                                                <td style="padding: 0px;">
                                                   <table width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif;border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                         <tr>
                                                            <td style="line-height:20px" align="right"><span>Travel Date: <?=date("l\, jS F Y",strtotime($booking_details['travel_date']));?></span><br><span>Booking Reference: <?=$booking_details['app_reference']?></span></td>
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
                                    <td align="right" style="line-height:24px;font-size:13px;border-top:1px solid #00a9d6;border-bottom:1px solid #00a9d6;padding: 5px;">Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:14px;"><?php 
                      switch($booking_details['status']){
                        case 'BOOKING_CONFIRMED': echo 'CONFIRMED';break;
                        case 'BOOKING_CANCELLED': echo 'CANCELLED';break;
                        case 'BOOKING_FAILED': echo 'FAILED';break;
                        case 'BOOKING_INPROGRESS': echo 'INPROGRESS';break;
                        case 'BOOKING_INCOMPLETE': echo 'INCOMPLETE';break;
                        case 'BOOKING_HOLD': echo 'HOLD';break;
                        case 'BOOKING_PENDING': echo 'PENDING';break;
                        case 'BOOKING_ERROR': echo 'ERROR';break;
                        
                      }                            
                      ?></strong>                              </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                          <tbody>
                                             <tr>
                                                <td style="padding:10px 0">
                                                  
                                                  <?php if($itinerary_details['image']):?>
                                                     <img style="width:160px;height:107px;" src="<?=$itinerary_details['image']?>">
                                                  <?php else:?>
                                                      <img style="width:160px;height:107px;" src="<?=BASE_PDF_PATH.$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>">
                                                  <?php endif;?>
                                                </td>
                                                <td valign="top" style="padding:10px;">
                                                   <span style="line-height:22px;font-size:16px;color:#00a9d6;vertical-align:middle;font-weight: 600;"><?=$booking_details['transfer_type']?> - <?=$booking_details['vehicle_name']?> - <?=$booking_details['category_name']?></span>
                                                   <br>
                                                   <span style="display: block;line-height:22px;font-size: 13px;"><?=$itinerary_details['from_location']?> - <?=$itinerary_details['to_location']?> </span>
                                                </td>
                                                <td width="32%" style="padding:10px 0;text-align: center;"><span style="font-size:14px; border:2px solid #808080; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><span style="font-size:14px;line-height:35px;padding-bottom: 5px;display:block;font-weight: 600;"><?=$booking_details['booking_reference']?></span></span></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=$base_image_path.'hotel_v.png'?>"> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Activity Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #00a9d6; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                          <tbody>
                                             <tr>
                                                <!-- <td>Phone</td> -->                                   
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Vat No</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Source Market Emergency No</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Departure Date</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Departure Time</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Retrun Date</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Return Time</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center" colspan="4">Total Traveler(s)</td>
                                             </tr>
                                             <tr>
                                                <td style="padding:5px"><span style="width:100%; float:left"> <?=@$attributes['supplier_name']?></span></td>
                                                <td style="padding:5px"><span style="width:100%; float:left">   <?=@$attributes['supplier_vat_number']?></span></td>
                                                <td style="padding:5px"><span style="width:100%; float:left">   <?=@$attributes['source_market_emergency_number']?></span></td>
                                                <?php 
                                                $total_travel_text= $attributes['TotalAdults'].' Adult(s) ';
                                                if($attributes['TotalChilds'] > 0){
                                                   $total_travel_text.=$attributes['TotalChilds']. 'Children(s) Child Ages('.$attributes['ChildAge'].')';
                                                }
                $total_travel_count = $booking_details['adult_count']+$booking_details['child_count'] ;
                ?>
                                                <td style="padding:5px"><?=$booking_details['travel_date']?></td>
                                                <td style="padding:5px"><?=$booking_details['travel_time']?></td>
                                                <td style="padding:5px"><?php if($booking_details['return_date']!="0000-00-00"){
                                                   echo $booking_details['return_date'];}else{echo '--';}?></td>
                                                <td style="padding:5px"><?php if(empty($booking_details['return_time']) == false){ echo $booking_details['return_time'];}else{echo '--';}?></td>
                                                <td style="padding:5px" align="center"><?=$total_travel_count?></td>
                                             </tr>
                                              <tr>
                                                <!-- <td>Phone</td> -->                                   
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Maximum Capacity</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Minimum Capacity</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Luggage</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Estimated Time</td>
                                                <?php if(isset($attributes['BookingQuestions'])){
                                                      if(valid_array($attributes['BookingQuestions'])){
                                                      foreach($attributes['BookingQuestions'] as $question){ ?> 
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;"><?php echo ucwords(strtolower($question['question']));?></td>
                                               
                                             <?php } } } ?>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;"></td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center"></td>
                                             </tr>
                                              <tr>
                                                <td style="padding:5px"><span style="width:100%; float:left"> <?=@$iti_attributes['MinimumPassengercount']?></span></td>
                                                <td style="padding:5px"><span style="width:100%; float:left">   <?=@$iti_attributes['MaximumPassengerCount']?></span></td>
                                                <td style="padding:5px"><span style="width:100%; float:left">   <?=@$iti_attributes['Suitcases']?></span></td>
                                                
                                                <td style="padding:5px"><?=$iti_attributes['EstimatedTime']?></td>
                                                <?php if(isset($attributes['BookingQuestions'])){
                                                      if(valid_array($attributes['BookingQuestions'])){
                                                      foreach($attributes['BookingQuestions'] as $question){ ?> 
                                                <td style="padding:5px"><?=$question['answers'];?></td>
                                               
                                             <?php } } } ?>
                                                <td style="padding:5px"></td>
                                                <td style="padding:5px" align="center"></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=$base_image_path.'people_group.png'?>"> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Traveller(s) Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #666666; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                                          <tbody>
                                             <tr>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Sr No.</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passenger(s) Name</td>
                                                 <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Paxes</td>
                                             </tr>
                                             <?php $i=1;?>  
                                             <?php foreach($customer_details as $name): ?>                               
                                             <tr>
                                                <td style="padding:5px;"><?=$i?></td>
                                                <td style="padding:5px"><?=$name['title'].'.  '.$name['first_name'].' '.$name['last_name']?></td>
                                                <td style="padding:5px;"><?=$total_travel_text?></td>
                                                                          
                                             </tr>
                                              <?php $i++;?>
                                             <?php endforeach;?>
                                          </tbody>
                                       </table>
                                    </td>
                                    <td></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="padding:0;">
                                       <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">
                                          <tbody>
                                             <tr>
                                                <td width="50%" style="padding:0;">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;border:1px solid #9a9a9a;">
                                                      <tbody>
                                                         <tr>
                                                            <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:13px">Payment Details</span></td>
                                                            <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:11px">Amount (<?=$booking_details['currency']?>)</span></td>
                                                         </tr>                                                         
                                                         <tr>
                                                            <td style="padding:5px"><span>Base Fare</span></td>
                                                            <td style="padding:5px"><span><?php echo roundoff_number($booking_details['product_total_price']); ?></span></td>
                                                         </tr>
                                                         <tr>
                                                            <td style="padding:5px"><span>Taxes</span></td>
                                                            <td style="padding:5px"><span><?=$booking_details['convinence_amount']?></span></td>
                                                         </tr>
                                                        <?php if($itinerary_details['gst'] > 0){?>
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
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['product_total_price']+$itinerary_details['gst']+$booking_details['convinence_amount']-$booking_details['discount']); ?></span></td>
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
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td align="center" colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No : <?=$booking_details['phone_code']." ".$customer_details[0]['phone']?></span></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Cancellation Policy</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$attributes['TM_Cancellation_Policy']?></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Pickup Description</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; padding-bottom:15px; font-size:12px; color:#555">
                                      <?=$iti_attributes['PickupDescription']?>
                                    </td>
                                 </tr>                                 
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Terms and Conditions</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:12px; color:#555"> <?php echo $data['terms_conditions']; ?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" align="right" style="padding-top:10px;font-size:13px;line-height:20px;"><?=$data['domainname']?><br>ContactNo : <?=$data['phone_code']?><?=$data['phone']?><br><?=$data['address']?></td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
      </tbody>
   </table>
  
<!-- </div>
 -->
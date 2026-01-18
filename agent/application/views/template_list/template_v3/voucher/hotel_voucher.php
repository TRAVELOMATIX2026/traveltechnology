<?php
   $booking_details = $data['booking_details'][0];
   $itinerary_details = $booking_details['itinerary_details'][0];
   $attributes = json_decode($booking_details['attributes'],true);
   $customer_details = $booking_details['customer_details'];
   $domain_details = $booking_details;
   $lead_pax_details = $booking_details['customer_details'];
   ?>
<div style="background:#ccc; width:100%; position:relative;padding: 50px;">
   <!-- <table cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:12px; font-family: 'Open Sans', sans-serif; width:850px; margin: 10px auto;background-color:#fff; border-collapse:separate; color: #000;">
      <tbody>
         <tr>
               <td class="text-center" style="background: <?=($receipt_type == 'no_price') ? '#f18604' : '#008100' ;?>;border: 2px solid #fff"> <a href="<?=base_url();?>voucher/hotel/<?= @$booking_details['app_reference'] ?>/<?= @$booking_details['booking_source'] ?>/<?= @$booking_details['status']?>/show_voucher/no_price" target="_blank" style="font-size: 16px;font-weight: bold;color: white;"> No Price Included </a> </td>
               <td class="text-center" style="background: <?=($receipt_type == 'agent_receipt') ? '#f18604' : '#008100' ;?>;border: 2px solid #fff"><a href="<?=base_url();?>voucher/hotel/<?= @$booking_details['app_reference'] ?>/<?= @$booking_details['booking_source'] ?>/<?= @$booking_details['status'] ?>/show_voucher/agent_receipt" target="_blank" style="font-size: 16px;font-weight: bold;color: white;"> Agent Receipt </a> </td>
         </tr>
      </tbody>
   </table> -->
   <div style="width:100%; position:relative" id="tickect_hotel">
      <table cellpadding="0" cellspacing="0" width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif; width:800px; margin:0px auto;background-color:#fff; padding:50px 45px;">
         <tbody>
            <tr>
               <td style="border-collapse: collapse; padding:30px 35px;" >
                  <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                     <tr>
                        <td style="padding: 0px;">
                           <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                              <!-- <tr>
                                 <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">Hotel Voucher</td>
                              </tr> -->
                              <tr>
                                 <td style="border-bottom:1px solid #ddd;">
                                    <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                                       <tr style="background: #008100; color:#fff;">
                                          <td style="padding: 5px;"><img style="width:170px;" src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"></td>
                                          <!-- <td style="padding: 0px;">
                                             <table width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif;border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                   <td style="font-size:14px;"><span style="width:100%; float:left"><?php echo $data['address'];?></span></td>
                                                   </tr>
                                                <tr>
                                                   <td style="padding-bottom:10px;line-height:20px" align="right"><span>Booking Reference: <?php echo $booking_details['app_reference']; ?></span><br><span>Booked Date : <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></span></td>
                                                </tr>
                                             </table>
                                          </td> -->
                                          <td style="padding: 15px 10px 10px; float:right;line-height: 26px;">
                                             <span style="font-weight:600;"><img width="15px" style="margin-right:5px;filter: brightness(0) invert(1);" src="<?php echo $GLOBALS['CI']->template->template_images('call_v.png'); ?>"> + <?= $this->entity_country_code?> <?=$this->entity_phone?></span><br>
                                             <span style="font-weight:600;"><img width="18px" style="margin-right:5px;filter: brightness(0) invert(1);" src="<?php echo $GLOBALS['CI']->template->template_images('mail_v.png'); ?>"><?=$this->entity_email;?></span>
                                          </td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                              <!-- <tr>
                                 <td align="left" width="80%" style="line-height:24px;font-size:13px;border-top:1px solid #008100;border-bottom:1px solid #008100;padding: 5px;color:#FF0000">
                                 <?php if($booking_details['booking_status']=='BOOKING_HOLD'){?> Note: This is not your final voucher, Final voucher you will received shortly. <?php } ?>
                                 </td>
                                 <td align="right" width="20%" style="line-height:24px;font-size:13px;border-top:1px solid #008100;border-bottom:1px solid #008100;padding: 5px;">Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:14px;">
                                    <?php 
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
                                       ?>
                                    </strong>
                                 </td>
                              </tr> -->
                              <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <tr>
                                 <td coslpan="2"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                    <tbody>
                                       <tr>
                                          <td style="line-height:12px;">&nbsp;</td>
                                       </tr>
                                       <tr>
                                          <td style="font-weight:600; font-size:15px;">Hello <?php echo $customer_details[0]['title']. '. ' .$customer_details[0]['first_name'].' '.$customer_details[0]['last_name']?>,</td>
                                       </tr>
                                       <tr>
                                          <td style="line-height:12px;">&nbsp;</td>
                                       </tr>
                                       <tr>
                                          <td style="font-weight:600; font-size:15px; padding:0;">Your booking is<strong class="<?php echo booking_status_label( $booking_details['status']);?>" style="font-size:15px; background:none !important; color:#000 !important; padding:0;">
                                             <?php 
                                                switch($booking_details['status']){
                                                   case 'BOOKING_CONFIRMED': echo 'Confirmed';break;
                                                   case 'BOOKING_CANCELLED': echo 'Cancelled';break;
                                                   case 'BOOKING_FAILED': echo 'Failed';break;
                                                   case 'BOOKING_INPROGRESS': echo 'Inprogress';break;
                                                   case 'BOOKING_INCOMPLETE': echo 'Incomplete';break;
                                                   case 'BOOKING_HOLD': echo 'Hold';break;
                                                   case 'BOOKING_PENDING': echo 'Pending';break;
                                                   case 'BOOKING_ERROR': echo 'Error';break;
                                                   
                                                }              
                                                ?>
                                             </strong>
                                          </td>
                                       </tr>
                                       <?php if($booking_details['booking_status']=='BOOKING_HOLD'){?>
                                       <tr>
                                          <td style="line-height:12px;">&nbsp;</td>
                                       </tr>
                                       <tr>
                                          <td style="color:#FF0000;font-size:13px;">Note: This is not your final voucher, Final voucher you will received shortly.</td>
                                       </tr>
                                       <?php } ?>
                                       <tr>
                                          <td style="line-height:25px;">&nbsp;</td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td colspan="2">
                                    <img width="100%" style="border-radius:5px;" src="<?php echo $GLOBALS['CI']->template->template_images('mail_banner_1.png'); ?>">
                                 </td>
                              </tr>
                              <tr><td style="line-height:12px;">&nbsp;</td></tr>                           
                              <tr>
                                 <td colspan="2" style="background:#efefef;padding:10px;border-radius:5px;"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 14px;padding:5px;">
                                    <tbody>
                                       <tr>
                                          <td width="50%">
                                             <tr>
                                                <td style="line-height:30px;">Booking Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style="font-size:14px; background:none !important; color:#000 !important; padding:0;">
                                                   <?php 
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
                                                      ?>
                                                   </strong>
                                                </td>
                                                <td><span>Booking Reference: <span style="font-weight: 600;"><?php echo $booking_details['app_reference']; ?></span></span></td>
                                             </tr>
                                          </td>
                                          <td>
                                             <tr>
                                                <td style="line-height:30px;"><span>Booking Id: <span style="font-weight:600;"><?php echo $booking_details['booking_id']; ?></span></span></td>
                                                <td>Booking Date: <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></td>
                                             </tr>
                                          </td>
                                       </tr>
                                    </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <tr>
                                 <td style="background:#efefef; padding:10px; border-radius:5px;">
                                    <table width="100%" cellpadding="5" style="font-size: 13px;padding:5px;">
                                       <tbody>
                                          <tr style="padding-bottom: 10px;border-bottom:1px dashed #ccc;">
                                             <?php if(empty($attributes['HotelImage']) ==false)
                                                   {
                                                   ?>
                                                <td style="padding:0;width:30%;"><img style="width:200px; height:130px; object-fit:cover;" src="<?=$attributes['HotelImage'];?>" /></td>
                                             <?php 
                                                }else{?>
                                                <td style="padding:0;width:30%;"><img style="width:200px; height:130px; object-fit:cover;" src="<?=$GLOBALS['CI']->template->template_images("default_hotel_img.jpg");?>" /></td>
                                             <?php };?>
                                             <td valign="top" style="padding:10px;width:70%;">
                                                <span style="line-height:30px;font-size:20px;color:#000;vertical-align:middle;font-weight: 600;"><?php echo $booking_details['hotel_name']; ?></span><br>
                                                <span style="display: block;line-height:22px;font-size: 13px;"><img style="width:70px;" src="<?php echo $GLOBALS['CI']->template->template_images('star_rating-'.$attributes["StarRating"].'.png'); ?>" /></span>
                                                <span style="display: block;line-height:22px;font-size: 12px;"><?php echo $booking_details['hotel_address']; ?> </span>
                                                <table style="width:100%; margin-top:10px; margin-bottom: 5px;">
                                                   <tr>
                                                      <td>
                                                         <span style="line-height: normal; font-size: 14px; width:100%; display:block;">Check-In</span>
                                                         <span style="line-height: 26px; font-size: 14px; font-weight: 600; width:100%; display:block;"><?=@date("D, d M Y",strtotime($itinerary_details['check_in']))?></span>
                                                      </td>
                                                      <td>
                                                         <span style="background: #008100; padding: 4px 24px 6px; border: 1px solid #008100; color: #fff; border-radius:5px;" class="htl_nights">
                                                            <?php echo $booking_details['total_nights']; ?>
                                                            <?php if ($booking_details['total_nights'] == 1): ?>
                                                               Night
                                                            <?php else: ?>
                                                               Nights
                                                            <?php endif; ?>
                                                         </span>
                                                      </td>
                                                      <td style="text-align: right; float:left;">
                                                         <span style="line-height: normal; margin: 0; font-size: 14px; width:100%; display:block;">Check-Out</span>
                                                         <span style="line-height: 26px; margin: 0; font-size: 14px; font-weight: 600;"><?=@date("D, d M Y",strtotime($itinerary_details['check_out']))?></span>
                                                      </td>
                                                   </tr>
                                                </table>
                                             </td>
                                             <table style="width: 80%;" align="right">
                                                <tr style="color:#616161;text-align: center;">
                                                   <td style="padding-top:8px;"><img width="20px" src="<?php echo $GLOBALS['CI']->template->template_images('rooms_v.png'); ?>">
                                                      <p style="font-size:14px; margin:0;"><?php echo $booking_details['total_rooms']; ?> room</p>
                                                   </td>
                                                   <td style="padding-top:8px;"><img width="22px" src="<?php echo $GLOBALS['CI']->template->template_images('adults_v.png'); ?>">
                                                      <p style="font-size:14px; margin:0;"><?php echo $booking_details['adult_count']; ?> Adult</p>
                                                   </td>
                                                   <td style="padding-top:8px;"><img width="15px" src="<?php echo $GLOBALS['CI']->template->template_images('child_v.png'); ?>">
                                                      <p style="font-size:14px; margin:0;"><?php echo $booking_details['child_count']; ?> Child</p>
                                                   </td>
                                                </tr>
                                             </table>
                                             <!-- <td width="32%" style="padding:10px 0;text-align: center;"><span style="font-size:14px; border:2px solid #808080; display:block"><span style="color:#008100;padding:5px; display:block;text-transform:uppercase">Booking ID</span><span style="font-size:14px;line-height:35px;padding-bottom: 5px;display:block;font-weight: 600;"><?php echo $booking_details['booking_id']; ?></span></span></td> -->
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <tr><td style="line-height:12px;">&nbsp;</td></tr>      
                              <!-- <tr>
                                 <td style="background-color:#008100;border: 1px solid #008100; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'hotel_v.png'?>" /> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Hotel Details</span></td>
                              </tr> -->
                              <tr>
                                 <td  width="100%" style="background:#efefef; padding:15px;border-radius:5px;">
                                    <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                       <!-- <tr>
                                          <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Check-In</td>
                                          <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Check-Out</td>
                                          <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center">No of Room's</td>
                                          <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Room Type</td>
                                          <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center">Adult's</td>
                                          <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center">Children</td>
                                       </tr>
                                       <tr>
                                          <td style="padding:5px"><span style="width:100%; float:left"> <?=@date("d M Y",strtotime($itinerary_details['check_in']))?></span></td>
                                          <td style="padding:5px"><span style="width:100%; float:left"> 	<?=@date("d M Y",strtotime($itinerary_details['check_out']))?></span></td>
                                          <td style="padding:5px" align="center"><?php echo $booking_details['total_rooms']; ?></span></td>
                                          <td style="padding:5px"  width="13%"><?php echo $itinerary_details['room_type_name']; ?></td>
                                          <td style="padding:5px" align="center"><?php echo $booking_details['adult_count']; ?></td>
                                          <td  style="padding:5px" align="center"><?php echo $booking_details['child_count']; ?></td>
                                       </tr> -->
                                       <tr>
                                          <td style="font-weight:600;font-size:14px;width:50%;">No. of Rooms: </td>
                                          <td style="">
                                             <span><?php echo $booking_details['total_rooms']; ?> Room(s)</span><br>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td style="font-weight:600;padding:10px 0 0;font-size:14px;">Room type: </td>
                                          <td style="padding:10px 0 0;font-size:14px;">
                                             <span style=""><?php echo $itinerary_details['room_type_name']; ?></span>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td style="font-weight:600;padding:10px 0 0;font-size:14px;vertical-align: top;">Room Inclusions: </td>
                                          <?php if($attributes['Boarding_details']): ?>
                                             <?php foreach($attributes['Boarding_details'] as $b_value):?>
                                                <td style="padding:10px 0 0;display:block;width:100%;"><span><?=$b_value?></span></td>
                                             <?php endforeach;?>
                                          <?php else:?>
                                             <td style="padding:10px 0 0;display:block;width:100%;"><span>Room Only</span></td><br>
                                          <?php endif;?>
                                          <td style="display:block;width:100%;"><span style="font-size:11px; color:#666; line-height:20px;">* Room inclusions are subject to change with Hotels.</span></td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                              <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <!-- <tr>
                                 <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>" /> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Guest(s) Details</span></td>
                              </tr> -->
                              <tr>
                                 <td  width="100%" style="background:#efefef; padding:10px;border-radius:5px;">
                                    <table width="100%" cellpadding="5" style="padding: 10px;font-size: 14px;">
                                       <tr>
                                          <td style="padding:5px;color: #333333;font-weight:600;">Sr No.</td>
                                          <td style="padding:5px;color: #333333;font-weight:600;">Passenger(s) Name</td>
                                          <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Email</td>
                                          <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Contact No.</td>
                                          <td style="padding:5px;color: #333333;font-weight:600;">Type</td>
                                          <td style="padding:5px;color: #333333;font-weight:600;">Age</td>
                                       </tr>
                                       <!-- <tr>
                                          <td><?php echo $customer_details['title'].' '.$customer_details['first_name'].' '.$customer_details['middle_name'].' '.$customer_details['last_name'];?></td>
                                                                           <td><?php echo $customer_details['phone'];?></td>
                                                                           <td><?php echo $customer_details['email'];?></td>
                                                                           <td><?php echo $booking_details['cutomer_city'];?></td>
                                                                        </tr>   -->
                                       <?php
                                             $i=1;
                                          ?> 
                                       <?php foreach($customer_details as $details):?>
                                       <tr>
                                          <td style="padding:5px;"><?=$i?></td>
                                          <td style="padding:5px"><?php echo $details['title'].' '.$details['first_name'].' '.$details['last_name']?></td>
                                          <td style="padding:5px;"><?php echo $details['email']?></td>
                                          <td style="padding:5px;"><?=$booking_details['phone_code']." ".$customer_details[0]['phone']?></td>
                                          <td style="padding:5px;"><?=$details['pax_type']?></td>
                                          <?php
                                             $age = '';
                                          $current_date = date('Y-m-d');
                                             $date1 = date_create($current_date);
                                             $date2 = date_create($details['date_of_birth']);
                                             $date_obj = date_diff($date1,$date2);
                                             if($details['pax_type']=='Child'){
                                                $age = $date_obj->y;
                                             }
                                             $i++;
                                          ?>
                                          <td style="padding:5px;"><?=$age?></td>                                       
                                          <!-- <td style="padding:5px"><?php echo $details['phone'] ?></td>
                                          <td style="padding:5px"><?php echo $details['email']?></td> -->
                                       </tr>
                                       <?php endforeach;?>
                                    </table>
                                 </td>
                                 <td></td>
                              </tr>
                              <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <tr>
                                 <td colspan="4" style="padding:0;">
                                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">
                                       <tbody>
                                          <tr>
                                             <td width="100%" style="padding:10px;background:#efefef;border-radius:5px;">
                                                <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">
                                                   <tbody>
                                                      <tr>
                                                         <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:14px;font-weight:600;">Payment Details</span></td>
                                                         <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:14px;font-weight:600;">Amount (<?=$booking_details['currency']?>)</span></td>
                                                      </tr>
                                                      <?php if($receipt_type == 'agent_receipt') { ?>
                                                      <tr>
                                                         <td style="padding:5px"><span>Room Rate</span></td>
                                                         <td style="padding:5px"><span><?php echo roundoff_number($booking_details['total_fare_markup']); ?></span></td>
                                                      </tr>
                                                      <tr>
                                                         <td style="padding:5px"><span>Taxes & Fees</span></td>
                                                         <td style="padding:5px"><span><?php echo roundoff_number(($booking_details['total_fare_tax']+$booking_details['agentServicefee']),2); ?></span></td>
                                                      </tr>
                                                      <?php if($itinerary_details['gst'] > 0){?>
                                                      <tr>
                                                         <td style="padding:5px"><span>GST</span></td>
                                                         <td style="padding:5px"><span><?php echo roundoff_number($itinerary_details['gst']); ?></span></td>
                                                      </tr>
                                                   <?php } ?>
                                                      <tr>
                                                         <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:14px;font-weight:600;">Total Fare</span></td>
                                                         <!-- <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:14px;font-weight:600;"><?= roundoff_number(@$booking_details['grand_total']) ?></span></td> -->
                                                         <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:14px;font-weight:600;"><?php echo roundoff_number($booking_details['total_fare_markup']+$booking_details['total_fare_tax']+$booking_details['agentServicefee']); ?></span></td>
                                      
                                                      </tr>
                                                   <?php } else { ?>
                                                      <tr>
                                                         <td style="border-top:1px solid #ccc"><span style="font-size:13px;font-weight: bold;">Grand Total</span></td>
                                                         <td style="border-top:1px solid #ccc"><span style="font-size:13px;font-weight: bold;"><?php echo roundoff_number($booking_details['total_fare_markup']+$booking_details['total_fare_tax']+$booking_details['agentServicefee']); ?></span></td>
                                      
                                                         <!-- <td style="border-top:1px solid #ccc"><span style="font-size:13px;font-weight: bold;"> <?= roundoff_number(@$booking_details['grand_total']+$booking_details['agentServicefee']) ?></span></td> -->
                                                      </tr>
                                                   <?php } ?>
                                                   <?php if($booking_details['total_destination_charge'] > 0){?>
                                                            <tr>
                                                               <td style="border-top:1px solid #ccc;padding:5px"><span>Local Tax <small>(Pay at property)</small></span></td>
                                                               <td style="border-top:1px solid #ccc;padding:5px"><span><?php echo roundoff_number(($booking_details['total_destination_charge']),2); ?></span></td>
                                                            </tr>
                                                            <?php } ?>
                                                   </tbody>
                                                </table>
                                             </td>
                                             <!-- <td width="50%" style="padding:0;padding-left:14px; vertical-align:top">
                                                <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:12px; padding:0;">
                                                   <tbody>
                                                      <tr>
                                                         <td style="background-color:#d9d9d9;border-bottom:1px solid #ccc;padding:5px; color:#333"><span style="font-size:13px">Room Inclusions</span></td>
                                                      </tr>
                                                      <?php if($attributes['Boarding_details']): ?>
                                                         <?php foreach($attributes['Boarding_details'] as $b_value):?>
                                                            <tr>
                                                               <td style="padding:5px"><span><?=$b_value?></span></td>	
                                                            </tr>
                                                         <?php endforeach;?>
                                                      <?php else:?>
                                                            <tr>
                                                               <td style="padding:5px"><span>Room Only</span></td>  
                                                            </tr>
                                                      <?php endif;?>
                                                      <tr>
                                                         <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:10px; color:#666; line-height:20px;">* Room inclusions are subject to change with Hotels.</span></td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td> -->
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
							         <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <!-- <tr><td align="center" colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No : <?=$customer_details[0]['phone']?></span></td></tr> -->
							         <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <tr>
                                 <td colspan="4"><span style="line-height:26px;font-size: 14px;font-weight: 600;">Cancellation Policy</span></td>
                              </tr>
                              <tr>
                                 <td colspan="4" style="line-height:20px; font-size:13px; color:#555"><?=$booking_details['cancellation_policy'][0]?></td>
                              </tr>
							         <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <tr>
                                 <td colspan="4"><span style="line-height:26px;font-size: 14px;font-weight: 600;">Terms and Conditions</span></td>
                              </tr>
                              <tr>
                                 <td colspan="4" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:13px; color:#555">
                                    <ul>
                                 <?php if(isset($booking_details['rate_comments'])== true && is_array($booking_details['rate_comments'])){
                                       foreach($booking_details['rate_comments'] as $dt_key=>$rc_data){
                                          echo '<li>'.$rc_data.'</li>';
                                       }
                                 }?>
                                 </ul>
                                    <?php echo $data['terms_conditions']; ?></td>
                              </tr>
                              <!-- <tr><td style="line-height:12px;">&nbsp;</td></tr>
                              <tr>
                              <td colspan="4"><span style="line-height:26px;font-size: 14px;font-weight: 500;">Important Information</span></td></tr>
                              <tr>
                                 <td colspan="4" style="border-bottom:1px solid #999999; line-height:20px; font-size:12px; color:#555">
                                    <ul>
                                       <li>All Guests, including children and infants, must present valid identification at check-in.</li>
                                       <li>Check-in begins 2 hours prior to the flight for seat assignment and closes 45 minutes prior to the scheduled departure.</li>
                                       <li>Carriage and other services provided by the carrier are subject to conditions of carriage, which are hereby incorporated by reference. These conditions may be obtained from the issuing carrier.</li>
                                       <li>In case of cancellations less than 6 hours before departure please cancel with the airlines directly. We are not responsible for any losses if the request is received less than 6 hours before departure.</li>
                                       <li>Please contact airlines for Terminal Queries.</li>
                                       <li>Free Baggage Allowance: Checked-in Baggage = 15kgs in Economy class.</li>
                                       <li>Partial cancellations are not allowed for Round-trip Fares</li>
                                       <li>Changes to the reservation will result in the above fee plus any difference in the fare between the original fare paid and the fare for the revised booking.</li>
                                       <li>In case of cancellation of a booking, made by a Go channel partner, refund has to be collected from that respective Go Channel.</li>
                                       <li>The No Show refund should be collected within 15 days from departure date.</li>
                                       <li>If the basic fare is less than cancellation charges then only statutory taxes would be refunded.</li>
                                       <li>We are not be responsible for any Flight delay/Cancellation from airline's end.</li>
                                       <li>Kindly contact the airline at least 24 hrs before to reconfirm your flight detail giving reference of Airline PNR Number.</li>
                                       <li>We are a travel agent and all reservations made through our website are as per the terms and conditions of the concerned airlines. All modifications,cancellations and refunds of the airline tickets shall be strictly in accordance with the policy of the concerned airlines and we disclaim all liability in connection thereof.</li>
                                    </ul>
                                 </td>
                              </tr> -->
                              <!-- <tr>
                                 <td colspan="4" align="right" style="padding-top:10px;font-size:13px;line-height:20px;"><?=$data['domainname']?><br>ContactNo :<?=$data['country_code']?> <?=$data['phone']?><br><?=$data['address']?></td>
                              </tr> -->
                              <tr>
                                 <td style="line-height:10px;">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td style="background-color:#efefef;padding:15px;border-radius:5px;"><table cellspacing="0" cellpadding="5" width="100%">
                                    <tbody>
                                       <tr>
                                          <td>
                                             <span style="font-size:14px; font-weight: 700;"><?=$data['domainname']?> Support: <span style="font-size: 13px;font-weight:500;">Email Address: <span><?=$this->entity_email;?></span></span></span>
                                          </td>
                                          <td>Contact Info: <span>+<?=$this->entity_country_code;?> <?=$this->entity_phone;?></span></td>
                                       </tr>
                                       <tr>
                                          <td style="line-height:5px;">&nbsp;</td>
                                       </tr>
                                       <tr>
                                          <td colspan="4"><span style=""><?php echo $this->entity_address ?></span></td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td colspan="4" align="right" style="padding:5px 0px;">
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
      html += document.getElementById('tickect_hotel').innerHTML;
      html += "</body></html>";

   var printWin = window.open();
   printWin.document.write(html);
   printWin.document.close();
   printWin.focus();
   printWin.print();
}
</script>

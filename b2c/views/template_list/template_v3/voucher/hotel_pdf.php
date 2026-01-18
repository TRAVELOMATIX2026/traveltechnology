<?php
   $booking_details = $data['booking_details'][0];
   
   $itinerary_details = $booking_details['itinerary_details'][0];
   $room_type_name = $itinerary_details['room_type_name'];
   // foreach($booking_details['itinerary_details'] as $itinerary){
   //    $rateKey = $itinerary['rateKey'];
   //    $rateKey_data = unserialized_data($rateKey);
   //    debug($itinerary);
   //    debug($rateKey_data);
   // }
   // exit;
   $room_type_names = array_column($booking_details['itinerary_details'],'room_type_name');
   $room_type_names_unique = array_count_values($room_type_names);
   $room_type_names = array();
   if($room_type_names_unique){
      foreach($room_type_names_unique as $room_type=>$room_count){
         $room_type_names[] = "$room_count x $room_type";
      }
   }
   if($room_type_names){
      $room_type_name = implode("<br/>",$room_type_names);
   }
   $attributes = json_decode($booking_details['attributes'],true);
 /*   debug($booking_details);
    exit;*/
   $customer_details = $booking_details['customer_details'];
   
   $domain_details = $booking_details;
   $lead_pax_details = $booking_details['customer_details'];

   //debug($booking_details['grand_total']); exit;
   $grand_total = roundoff_number($booking_details['grand_total']);
   //$grand_total = $booking_details['fare']+$booking_details['admin_markup']+$booking_details['convinence_amount'];
   //$grand_total = $grand_total-$booking_details['discount'];
   $grand_total = roundoff_number($booking_details['total_fare_markup']+$booking_details['total_fare_tax']);
   $grand_total = roundoff_number($grand_total-$booking_details['discount']);
   ?>
<style type="text/css">
   .trms_n_cndtn ul p {
      font-size:13px !important;
   }
   @media(max-width:650px) {
      table {
         width: 100% !important;
         overflow: auto;
      }
      .wrapper_td {
         padding:15px !important
      }
      .htl_nights {
         padding: 4px 4px 6px !important;
      }
   }
   @media(max-width:350px) {
      .wrapper_td {
         padding: 5px !important;
      }
   }
    @media print {
      .no-print {
        display: none !important;
      }
    }
</style>

   <table cellpadding="0" cellspacing="0" width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif; width:800px; margin:0px auto;background-color:#fff; ">
      <tbody>
         <tr>
            <td style="border-collapse: collapse; padding:30px 35px;" class="wrapper_td">
               <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                     <td style="padding: 0px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                           <tr>
                              <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td>
                           </tr>
                           <tr>
                              <td colspan="2" style="border-bottom:1px solid #ddd;">
                                 <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                                    <tr style="background: #008100;">
                                       <td style="padding: 0px 0px 10px; vertical-align:middle;"><img width="170px" src="<?=BASE_PATH.$GLOBALS['CI']->template->domain_images($data['logo'])?>"></td>
                                       <td style="padding: 0px 0px 10px; float:right;line-height: 26px;color:#fff;">
                                          <span style="font-weight:600;"><img width="15px" style="margin-right:5px;filter: brightness(0) invert(1);" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("call_v.png");?>"><span><?=$data['phone_code']?><?=$data['phone']?></span></span><br>
                                          <span style="font-weight:600;"><img width="18px" style="margin-right:5px;filter: brightness(0) invert(1);" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("mail_v.png");?>"> <?= $this->entity_domain_mail ?></span>
                                       </td>
                                       <!-- <td style="padding: 0px;">
                                          <table width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif;border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0"> -->
                                             <!-- <tr>
                                                <td style="font-size:14px;"><span style="width:100%; float:left"><?php echo $data['address'];?></span></td>
                                                </tr> -->
                                             <!-- <tr>
                                                <td style="padding-bottom:10px;line-height:20px" align="right">
                                                   <span>Booking id: </span><span style="font-size:14px;font-weight: 600;"><?php echo $booking_details['booking_id']; ?></span><br>
                                                   <span>Booked on: <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></span>
                                                </td>
                                             </tr>
                                          </table>
                                       </td>-->
                                    </tr> 
                                 </table>
                              </td>
                           </tr>
                           <!-- <tr>
                              <td align="left" width="70%" style="line-height:24px;font-size:13px;border-top:1px solid #00a9d6;border-bottom:1px solid #00a9d6;padding: 5px;color:#FF0000">
                              <?php if($booking_details['booking_status']!='CONFIRMED'){?> Note: This is not your final voucher, Final voucher you will received shortly. <?php } ?>
                              </td>

                              <td align="right" width="30%" style="line-height:24px;font-size:13px;border-top:1px solid #00a9d6;border-bottom:1px solid #00a9d6;padding: 5px 0px;">Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:14px;">
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
                                       <td style="font-weight:600; font-size:15px;padding:12px 0 0;">Hello <?php echo $customer_details[0]['title']. '. ' .$customer_details[0]['first_name'].' '.$customer_details[0]['last_name']?>,</td>
                                    </tr>
                                    <tr>
                                       <td style="font-weight:600; font-size:15px; padding:12px 0 20px;">Your booking is<strong class="<?php echo booking_status_label( $booking_details['status']);?>" style="font-size:15px; background:none !important; color:#000 !important; padding:0;">
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
                                       <td style="color:#FF0000; padding:0px 0px 25px;">Note: This is not your final voucher, Final voucher you will received shortly.</td>
                                    </tr>
                                 <?php } ?>
                                 </tbody>
                              </table>
                              </td>
                           </tr>
                           <tr>
                              <td colspan="2">
                                 <img width="100%" style="border-radius:5px;" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("mail_banner_1.png");?>">
                                 <!-- <img width="100%" style="border-radius:5px;" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("mail_banner_1.png");?>"> -->
                              </td>
                           </tr>
                           <tr>
                              <td style="line-height:18px;">&nbsp;</td>
                           </tr>
                           <tr>
                              <td colspan="2" style="background:#efefef;padding:10px;border-radius:5px;"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 14px;padding:0px;">
                                 <tbody>
                                    <tr>
                                       <td style="line-height:30px;width:50%;padding:0px;">Booking Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style="font-size:14px; background:none !important; color:#000 !important; padding:0;">
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
                                       <td style="padding:0px;"><span>Booking Reference: <span style="font-weight: 600;"><?php echo $booking_details['app_reference']; ?></span></span></td>
                                    </tr>
                                    <tr>
                                       <td style="line-height:30px;width:50%;padding:0px;"><span>Booking Id: <span style="font-weight:600;"><?php echo $booking_details['booking_id']; ?></span></span></td>
                                       <td style="padding:0px;">Booking Date: <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></td>
                                    </tr>
                                 </tbody>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td style="line-height:18px;">&nbsp;</td>
                           </tr>
                           <tr>
                              <td colspan="2" style="background:#efefef; padding:10px; border-radius:5px;">
                                 <table width="100%" cellpadding="5" style="padding: 0px;font-size: 13px;">
                                    <tbody>
                                       <tr style="display:flex;align-items:stretch;padding-bottom: 10px;border-bottom:1px dashed #ccc;">
                                          <?php if(!empty($attributes['HotelImage'])){
                                             ?>
                                             <td style="width:30%;"><img style="width:200px; height:130px; object-fit:cover;" src="<?=$attributes['HotelImage'];?>" /></td>
                                          <?php }else{?>
                                             <td style="width:30%;"><img style="width:220px; height:100%; object-fit:cover;" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("default_hotel_img.jpg");?>" /></td>
                                          <?php }?>
                                          <td valign="top" style="padding:0px 10px;width:70%;">
                                             <span style="font-size:20px;color:#000;vertical-align:middle;font-weight: 600;"><?php echo $booking_details['hotel_name']; ?></span>
                                             <span style="display: block;line-height:22px;font-size: 13px;color:#000;"><img style="width:70px;" src="<?php echo BASE_PATH.$GLOBALS['CI']->template->template_images('star_rating-'.$attributes["StarRating"].'.png'); ?>" /></span><span style="display: block;line-height:22px;font-size: 12px;"><?php echo $booking_details['hotel_address']; ?> </span>
                                             <table style="width:100%; margin-top:10px; margin-bottom: 5px;color:#000;">
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
                                          
                                       </tr>
                                       <tr>
                                          <td style="padding:0px;">
                                             <table style="width: 80%;" align="right">
                                                <tr style="color:#616161;text-align: center;">
                                                   <td style="padding-top:8px;"><img width="20px" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("rooms_v.png");?>">
                                                      <p style="font-size:14px; margin:0;"><?php echo $booking_details['total_rooms']; ?> room</p>
                                                   </td>
                                                   <td style="padding-top:8px;"><img width="22px" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("adults_v.png");?>">
                                                      <p style="font-size:14px; margin:0;"><?php echo $booking_details['adult_count']; ?> Adult</p>
                                                   </td>
                                                   <td style="padding-top:8px;"><img width="15px" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("child_v.png");?>">
                                                      <p style="font-size:14px; margin:0;"><?php echo $booking_details['child_count']; ?> Child</p>
                                                   </td>
                                                </tr>
                                             </table>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           <tr><td style="line-height:12px;">&nbsp;</td></tr>      
                           <!-- <tr>
                              <td colspan="2" style="background-color:#008100;border: 1px solid #008100; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=BASE_PATH.SYSTEM_IMAGE_DIR.'hotel_v.png'?>" /> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Hotel Details</span></td>
                           </tr> -->
                           <tr>
                              <td width="100%" style="background: #efefef; padding:15px;border-radius:5px;" colspan="2">
                                 <table width="100%" cellpadding="5" style="font-size: 13px;">
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
                                       <td style="padding:5px"><span style="width:100%; float:left">  <?=@date("d M Y",strtotime($itinerary_details['check_out']))?></span></td>
                                       <td style="padding:5px" align="center"><?php echo $booking_details['total_rooms']; ?></span></td>
                                       <td style="padding:5px"  width="13%"><?=$room_type_name?></td>
                                       <td style="padding:5px" align="center"><?php echo $booking_details['adult_count']; ?></td>
                                       <td  style="padding:5px" align="center"><?php echo $booking_details['child_count']; ?></td>
                                    </tr> -->
                                    <!-- <tr style="border-bottom: 1px solid #ccc;">
                                       <td style="padding: 8px 0px;">
                                          <span style="font-size: 14px;"><i class="far fa-users"></i></span><span style="font-weight: 600;padding-left: 5px;">2 Guests</span>
                                          <span><?php echo $booking_details['adult_count']; ?> Adult(s), <?php echo $booking_details['child_count']; ?> Children</span>
                                       </td>
                                       <td>
                                          <span style="font-weight: 600;">Mr. Test User</span>
                                          <span>test@gmail.com, 911234567890</span>
                                       </td>
                                    </tr> -->
                                    <tr>
                                       <td style="font-weight:600;font-size:14px;width:50%;padding:0px;">No. of Rooms: </td>
                                       <td style="padding:0px;">
                                          <span><?php echo $booking_details['total_rooms']; ?> Room(s)</span>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="font-weight:600;padding:10px 0 0;font-size:14px;">Room type: </td>
                                       <td style="padding:10px 0 0;font-size:14px;">
                                          <span style=""><?=$room_type_name?> </span>
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
                                    </tr>
                                    <tr>
                                       <td style="padding:0px;">&nbsp;</td>
                                       <td style="display:block;width:100%;padding:0px;"><span style="font-size:11px; color:#666; line-height:20px;">* Room inclusions are subject to change with Hotels.</span></td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr><td style="line-height:12px;">&nbsp;</td></tr>
                           <!-- <tr>
                              <td colspan="2" style="background-color:#008100;border: 1px solid #008100; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=BASE_PATH.SYSTEM_IMAGE_DIR.'people_group.png'?>" /> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Guest(s) Details</span></td>
                           </tr> -->
                           <tr>
                              <td colspan="2" width="100%" style="background:#efefef; padding:10px;border-radius:5px;">
                                 <table width="100%" cellpadding="5" style="padding: 0px;font-size: 13px;">
                                    <tr>
                                       <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Sr No.</td>
                                       <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Passenger(s) Name</td>
                                       <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Email</td>
                                       <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Contact No.</td>
                                       <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Type</td>
                                       <td style="font-size: 14px;padding:5px;color: #333333; font-weight:600;">Age</td>
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
                           </tr>
                           <tr><td style="line-height:12px;">&nbsp;</td></tr>
                           <tr>
                              <td colspan="4" style="padding:0;">
                                 <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">
                                    <tbody>
                                       <tr>
                                          <td width="100%" style="display:block; width:100%;background: #efefef;padding: 10px;border-radius:5px;">
                                             <table cellspacing="0" cellpadding="5" width="100%" style="font-size:13px; padding:0;">
                                                <tbody>
                                                   <tr style="background: #efefef;color:#000;">
                                                      <td style="font-weight:600;padding:5px;"><span style="font-size:14px">Payment Details</span></td>
                                                      <td style="font-weight:600;padding:5px;"><span style="font-size:14px">Amount (<?=$booking_details['currency']?>)</span></td>
                                                   </tr>
                                                   <tr>
                                                      <td style="padding:5px"><span>Room Rate</span></td>
                                                      <td style="padding:5px"><span><?php echo roundoff_number($booking_details['total_fare_markup']); ?></span></td>
                                                   </tr>
                                                   <tr>
                                                      <td style="padding:5px"><span>Taxes & Fees</span></td>
                                                      <td style="padding:5px"><span><?php echo roundoff_number($booking_details['total_fare_tax']); ?></span></td>
                                                   </tr>
                                              
                                                   <?php if($itinerary_details['gst'] > 0){?>
                                                   <tr>
                                                      <td style="padding:5px"><span>GST</span></td>
                                                      <td style="padding:5px"><span><?php echo roundoff_number($itinerary_details['gst']); ?></span></td>
                                                   </tr>
                                                <?php } ?>
                                                   <tr>
                                                      <td style="padding:5px"><span>Discount</span></td>
                                                      <td style="padding:5px"><span><?php echo roundoff_number($booking_details['discount']); ?></span></td>
                                                   </tr>
                                                   
                                                   <tr>
                                                      <td style="border-top:1px solid #ccc;padding:5px;font-weight:600;"><span style="font-size:14px">Total Fare</span></td>
                                                      <td style="border-top:1px solid #ccc;padding:5px;font-weight:600;"><span style="font-size:14px"><?php echo roundoff_number($booking_details['total_fare_markup']+$booking_details['total_fare_tax']-$booking_details['discount']); ?></span></td>
                                                   </tr>
                                                      <?php if($booking_details['total_destination_charge'] > 0){?>
                                                      <tr>
                                                      <td style="border-top:1px solid #ccc;padding:5px;font-weight:600;"><span>Local Tax <small>(Pay at property)</small></span></td>
                                                      <td style="padding:5px"><span><?php echo roundoff_number($booking_details['total_destination_charge']); ?></span></td>
                                                   </tr>

                                                   <?php } ?>
                                                </tbody>
                                             </table>
                                          </td>
                                          <tr>
                                             <td style="line-height:12px;">&nbsp;</td>
                                          </tr>
                                          <!-- <td width="100%" style="display:block; width:100%;">
                                             <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #666;font-size:12px; padding:0;">
                                                <tbody>
                                                   <tr>
                                                      <td style="background-color:#008100;border-bottom:1px solid #ccc;padding:5px; color:#333"><span style="font-size:14px;color:#fff;">Room Inclusions</span></td>
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
                                                      <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:11px; color:#666; line-height:20px;">* Room inclusions are subject to change with Hotels.</span></td>
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
                           <!-- <tr><td align="center" colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No : <?=$booking_details['phone_code']." ".$customer_details[0]['phone']?></span></td></tr>
                           <tr><td style="line-height:12px;">&nbsp;</td></tr> -->
                           <tr>
                           <td colspan="4"><span style="line-height:26px;font-size: 14px;font-weight: 600;">Cancellation Policy</span></td></tr>
                           <tr>
                              <td colspan="4" style="line-height:20px; font-size:13px; color:#555"><?=$booking_details['cancellation_policy'][0]?></td>
                           </tr>
                           <tr><td style="line-height:12px;">&nbsp;</td></tr>
                           <tr>
                           <td colspan="4"><span style="line-height:26px;font-size: 14px;font-weight: 600;">Terms and Conditions</span></td></tr>
                           <tr>
                              <td colspan="4" class="trms_n_cndtn" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:13px; color:#555">
                                 <ul style="padding:0px;"> 
                                    <?php if(isset($booking_details['rate_comments'])== true && is_array($booking_details['rate_comments'])){
                                          foreach($booking_details['rate_comments'] as $dt_key=>$rc_data){
                                             echo '<li>'.$rc_data.'</li>';
                                          }
                                    }?>
                                 </ul>
                                 <?php echo $data['terms_conditions']; ?>
                              </td>
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
                           <tr>
                              <td style="line-height:10px;">&nbsp;</td>
                           </tr>
                           <tr>
                              <td style="background-color:#efefef;padding:15px;border-radius:5px;"><table cellspacing="0" cellpadding="5" width="100%">
                                 <tbody>
                                    <tr>
                                       <td>
                                          <span style="font-size:14px; font-weight: 700;"><?=$data['domainname']?> Support: <span style="font-size: 13px;font-weight:500;">Email Address: <span><?= $this->entity_domain_mail ?></span></span></span>
                                       </td>
                                       <td>Contact Info: <span><?=$data['phone_code']?><?=$data['phone']?></span></td>
                                    </tr>
                                    <tr>
                                       <td colspan="4" style="padding-top:5px;"><span><?=$data['address']?></span></td>
                                    </tr>
                                 </tbody>
                              </table>
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

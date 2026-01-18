<?php
$booking_details = $data['booking_details'][0];
//echo debug($booking_details);
//exit;
   $itinerary_details = $booking_details['itinerary_details'][0];
   $attributes = json_decode($booking_details['attributes'],true);
   $customer_details = $booking_details['customer_details'];

// debug($customer_details);
// exit;
$domain_details = $booking_details;
$lead_pax_details = $booking_details['customer_details'];
?>

<table style="border-collapse: collapse; background: #ffffff;font-size: 12pt; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
   <tbody>
      <!-- <tr>
         <td style="border-collapse: collapse;"><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0"> -->
			   <!-- <tr><td style="font-size:15pt; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td></tr> -->
            <tr>
               <td colspan="2" style="border-bottom:1px solid #ddd;background-color: #008100;"><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                     <td style="width:100%;" colspan="4"><img width="150" src="<?=BASE_PATH.$GLOBALS['CI']->template->domain_images($data['logo'])?>"></td>
                     <td style="padding: 10px;" colspan="2"><table width="100%" style="border-collapse: collapse;text-align: right; font-size:10pt" cellpadding="0" cellspacing="0" border="0">         		
                        <tr>
                           <!-- <td style="padding-bottom:10px;line-height:20px" align="right"><span>Booking Reference: <?php echo $booking_details['app_reference']; ?></span><br><span>Booked Date : <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></span></td> -->
                           <td style="font-size:10pt;text-align:left;padding-bottom:10px;color:#fff;"><img width="15px" style="margin-right:5px;" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("call_v.png");?>"><strong><?=$data['phone_code']?><?=$data['phone']?></strong></td>
                        </tr>
                        <tr>
                           <td style="font-size:10pt;color:#fff;"><img width="15px" style="margin-right:5px;" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("mail_v.png");?>"><strong><?= $this->entity_domain_mail?></strong></td>
                        </tr>
                     </table></td>
                  </tr>
               </table>
               </td>
            </tr>
            <tr><td style="line-height:6px;">&nbsp;</td></tr>    
            <tr>
               <td coslpan="2"><table width="100%" cellpadding="5" style="font-size: 13px;">
                  <tbody>
                  <tr>
                     <td style="font-size:11pt;"><strong>Hello <?php echo $customer_details[0]['title']. '. ' .$customer_details[0]['first_name'].' '.$customer_details[0]['last_name']?>,</strong></td>
                  </tr>
                  <tr>
                     <td style="font-size:11pt;padding-bottom:10px;"><strong>Your booking is<span class="<?php echo booking_status_label( $booking_details['status']);?>" style="font-size:11pt; background:none !important; color:#000 !important; padding:0;">
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
                        </span>
                        </strong>
                     </td>
                  </tr>

                  <?php if($booking_details['booking_status']=='BOOKING_HOLD'){?>
                     <tr>
                        <td style="font-size:9pt;color:#FF0000;padding:0px;">Note: This is not your final voucher, Final voucher you will received shortly.</td>
                     </tr>
                  <?php } ?>

                  </tbody>
               </table>
               </td>
            </tr>
            <tr>
               <td colspan="2" style="width:100%;"><img style="max-width:100%; height:100%;" src="<?=BASE_PATH.$GLOBALS['CI']->template->template_images("mail_banner_1.png");?>"></td>
            </tr>
            <tr>
               <td style="line-height:18px;">&nbsp;</td>
            </tr>
            <tr>
               <td colspan="2" style="background:#efefef;padding:10px;"><table width="100%" cellpadding="5">
                  <tbody>
                  <tr>
                     <td width="50%" style="padding:0px;">
                        <tr>
                        <td style="font-size:10pt;">Booking Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" background:none !important; color:#000 !important; padding:0;">
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
                        <td style="font-size:10pt;"><span>Booking Reference: <strong><?php echo $booking_details['app_reference']; ?></strong></span></td>
                        </tr>
                     </td>
                     <td width="50%" style="padding:0px;">
                        <tr>
                        <td style="font-size:10pt;"><span>Booking Id: <strong><?php echo $booking_details['booking_id']; ?></strong></span></td>
                        <td style="font-size:10pt;">Booking Date: <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></td>
                        </tr>
                     </td>
                  </tr>
                  </tbody>
               </table>
               </td>
            </tr>
            <tr>
               <td style="line-height:18px;">&nbsp;</td>
            </tr>
            <tr>
               <td style="padding:0;background-color:#efefef;" colspan="2"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 10pt;padding:5px;">
                  <tbody>
                     <tr style="display:flex;align-items:stretch;">
                        <?php if($attributes['HotelImage'] !='/'):?>
                           <td width="30%" colspan="2"><img style="width:200px; height:130px; object-fit:cover;" src="<?=$attributes['HotelImage'];?>" /></td>
                        <?php else:?>
                           <td width="30%" colspan="2"><img style="width:200px; height:130px; object-fit:cover;" src="<?=BASE_PATH .$GLOBALS['CI']->template->template_images("default_hotel_img.jpg");?>" /></td>
                        <?php endif;?>
                        <td width="70%" colspan="5" valign="top">
                           <strong style="font-size:14pt;vertical-align:middle;"><?php echo $booking_details['hotel_name']; ?></strong><br>
                           <span style="display: block;font-size: 10pt;"><img style="width:70px;padding:8px 0px;" src="<?php echo BASE_PATH.$GLOBALS['CI']->template->template_images('star_rating-'.$attributes["StarRating"].'.png'); ?>" /></span><br>
                           <span style="display: block;font-size: 10pt;"><?php echo $booking_details['hotel_address']; ?> </span>
                           <table style="width:100%;">
                              <tbody>
                                 <tr>
                                 <td style="width: 33.333%; font-size:10pt;line-height:15px;padding:20px 0px 0px;"><span>Check In</span>
                                    <br><strong style="font-size: 10pt;"><?=@date("D",strtotime($itinerary_details['check_in']))?>, <?= date('d M Y', strtotime($booking_details['hotel_check_in'])) ?></strong><br>
                                 </td>
                                 <td style="width: 25%; text-align: center;padding:20px 0px 0px;">
                                       <table><tr><td style="display:block;color: #fff;font-size:10pt;width:80px;background-color: #008100;text-align:center;padding:5px 0px;"><?= $booking_details['total_nights'] ?>
                                       <?= ($booking_details['total_nights'] == 1) ? 'Night' : 'Nights' ?>
                                       </td></tr></table>
                                 </td>
                                 <td style="width: 33.333%; text-align:right; font-size:10pt;line-height:15px;padding:20px 0px 0px;"><span>Check Out</span><br>     
                                    <strong style="font-size: 10pt;">
                                    <?=@date("D",strtotime($itinerary_details['check_out']))?>, <?= date('d M Y', strtotime($booking_details['hotel_check_out'])) ?>
                                    </strong><br>
                                 </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>

                        <!-- <td width="34%" style="padding:10px 0;text-align: center;line-height:25px;"><table style="border:2px solid #808080;">
                           <tbody>
                              <tr>
                                 <td><span style="font-size:11pt; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><br><span style="font-size:10pt;padding-bottom: 5px;display:block;font-weight: 600;"><?php echo $booking_details['booking_id']; ?></span></span></td>
                              </tr>
                           </tbody>
                        </table>
                        </td> -->
                     </tr>
                  </tbody>
               </table>
               </td>
            </tr>
            <tr>
               <td style="border-top: 1px dashed #ccc;padding:10px 0px 0px;background:#efefef;" align="right" width="100%"><table style="width: 100%;">
               <tbody>
                  <!-- <tr><td colspan="3" style="line-height: 4px">&nbsp;</td></tr> -->
                  <tr>
                     <td style="padding-left:210px;" width="100%">
                        &nbsp;&nbsp;<img style="width:18px;" src="<?= BASE_PATH.$GLOBALS['CI']->template->template_images('rooms_v.png'); ?>"><br><span style="font-size:10pt;"> 
                           <?php echo $booking_details['total_rooms']; ?> room
                        </span>
                     </td>
                     <td style="padding-right:70px;" width="100%">
                        &nbsp;<img style="width:22px;margin:auto;display:block; text-align:center;" src="<?= BASE_PATH. $GLOBALS['CI']->template->template_images('adults_v.png'); ?>"><br><span style="font-size:10pt;"> 
                           <?= $booking_details['adult_count'] ?> Adult
                        </span>
                     </td>
                     <td style="padding-right:20px;">
                        &nbsp;&nbsp;<img style="width:15px;" src="<?= BASE_PATH. $GLOBALS['CI']->template->template_images('child_v.png'); ?>"><br><span style="font-size:10pt;"> 
                           <?= $booking_details['child_count'] ?> Child
                        </span>
                     </td>
                  </tr>
               </tbody>
               </table></td>
            </tr>
            <tr><td style="line-height:2px;">&nbsp;</td></tr>   
				<!-- <tr>
					<td style="padding: 10px;"><table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;"> -->
                  <!-- <tr>
                     <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:10pt; padding:5px; line-height:normal;" colspan="4" width="100%"><img width="12" src="<?=BASE_PATH.SYSTEM_IMAGE_DIR.'hotel_v.png'?>" /> <span style="font-size:10pt;color:#fff;line-height:12px;"> &nbsp;Hotel Details</span></td>
                  </tr> -->
            <tr>
               <td width="100%" colspan="4" style="background:#efefef;padding:0"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 9pt;">	    
                  <!-- <tr>
                     <td width="20%" style="background-color:#d9d9d9;color: #333333;"><strong>Check-In</strong></td>
                     <td width="20%" style="background-color:#d9d9d9;color: #333333;"><strong>Check-Out</strong></td>
                     <td width="20%" align="center" style="background-color:#d9d9d9;color: #333333;"><strong>No of Room's</strong></td>
                     <td width="20%" style="background-color:#d9d9d9;color: #333333;"><strong>Room Type</strong></td>
                     <td width="20%" align="center" style="background-color:#d9d9d9;color: #333333;"><strong>Adult's</strong></td>
                     <td width="20%" align="center" style="background-color:#d9d9d9;color: #333333;"><strong>Children</strong></td>							   
                           </tr>									    
                           <tr>
                     <td width="20%"><?=@date("d M Y",strtotime($itinerary_details['check_in']))?></td>
                     <td width="20%"><?=@date("d M Y",strtotime($itinerary_details['check_out']))?></td>
                     <td width="20%" align="center"><?php echo $booking_details['total_rooms']; ?></td>
                     <td width="20%"><?php echo $itinerary_details['room_type_name']; ?></td>
                     <td width="20%" align="center"><?php echo $booking_details['adult_count']; ?></td>
                     <td width="20%" align="center"><?php echo $booking_details['child_count']; ?></td>
                  </tr> -->
                  <tr>
                     <td style="padding:10px 0 0;font-size:10pt;" width="100%" colspan="2"><strong>No. of Rooms:</strong></td>
                     <td style="padding:10px 0 0;font-size:10pt;" width="100%" colspan="2">
                        <span><?php echo $booking_details['total_rooms']; ?> Room(s)</span><br>
                     </td>
                  </tr>
                  <tr>
                     <td style="padding:10px 0 0;font-size:10pt;" colspan="2"><strong>Room type:</strong></td>
                     <td style="padding:10px 0 0;font-size:10pt;">
                        <span style=""><?php echo $itinerary_details['room_type_name']; ?></span>
                     </td>
                  </tr>
                  <tr>
                     <td style="padding:10px 0 0;font-size:10pt;vertical-align: top;" colspan="2"><strong>Room Inclusions:</strong></td>
                     <?php if($attributes['Boarding_details']): ?>
                        <?php foreach($attributes['Boarding_details'] as $b_value):?>
                           <td style="padding:10px 0 0;font-size:10pt;"><span><?=$b_value?></span></td></br>
                        <?php endforeach;?>
                     <?php else:?>
                        <td style="padding:10px 0 0;font-size:10pt;"><span>Room Only</span></td></br>
                     <?php endif;?>
                     </tr>
                     <tr>
                     <td colspan="2">&nbsp;</td>
                     <td style="width:100%;" colspan="2"><span style="font-size:11px; color:#666; line-height:20px;">* Room inclusions are subject to change with Hotels.</span></td>
                  </tr>
               </table>
               </td>
            </tr>
                  <!-- </table>
			      </td>
			   </tr> -->
            <tr><td style="line-height:2px;">&nbsp;</td></tr>

            <!-- <tr>
               <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:9pt; padding:5px;" colspan="4" width="100%"><img width="12" style="vertical-align:middle" src="<?=BASE_PATH.SYSTEM_IMAGE_DIR.'people_group.png'?>" /> <span style="font-size:9pt;color:#fff;vertical-align:middle;"> &nbsp;Guest(s) Details</span></td>
            </tr> -->
            <tr>
               <td width="100%" style="background:#efefef;padding:0" colspan="4" width="100%"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 10pt;">	 
                  <tr>
                     <td  width="16.66%" style="padding:5px;color: #333333;"><strong>Sr No.</strong></td>
                     <td  width="16.66%" style="padding:5px;color: #333333;"><strong>Passenger(s) Name</strong></td>
                     <td  width="16.66%" style="padding:5px;color: #333333;"><strong>Email</strong></td>
                     <td  width="16.66%" style="padding:5px;color: #333333;"><strong>Contact No.</strong></td>
                     <td  width="16.66%" style="padding:5px;color: #333333;"><strong>Type</strong></td>
                     <td  width="16.66%" style="padding:5px;color: #333333;"><strong>Age</strong></td>
                  </tr>
                  <?php
                        $i=1;
                     ?> 
                  <?php foreach($customer_details as $details):?>
                  <tr>
                     
                     <td width="16.66%" style="padding:5px;"><?=$i?></td>
                     <td width="16.66%" style="padding:5px"><?php echo $details['title'].' '.$details['first_name'].' '.$details['last_name']?></td>
                     <td style="padding:5px" width="16.66%"><?=$customer_details[0]['email']?></td>
                     <td style="padding:5px" width="16.66%"><?=$customer_details[0]['phone_code']?> <?=$customer_details[0]['phone']?></td>
                     <td width="16.66%" style="padding:5px;"><?=$details['pax_type']?></td>
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
                     <td width="16.66%" style="padding:5px;"><?=$age?></td>                                       
                     
                  </tr>
                  <?php endforeach;?> 
               </table>
               </td>
            </tr>
            <tr><td style="line-height:2px;">&nbsp;</td></tr>
            <tr>
               <td width="100%" style="padding:0px;background:#efefef;" colspan="2"><table width="100%" cellpadding="5" style="padding: 10;font-size: 10pt;">   
                  <tbody>
                     <!-- <tr>
                        <td colspan="4" width="100%"><table cellspacing="0" cellpadding="5" width="100%" style="font-size:9pt; padding:0;">
                           <tbody> -->
                              <tr>
                              <td style="padding:5px;" colspan="4" width="100%"><strong style="font-size:10pt">Payment Details</strong></td>
                              <td style="padding:5px;" colspan="2"><strong style="font-size:10pt">Amount (<?=COURSE_LIST_DEFAULT_CURRENCY_SYMBOL?>)</strong></td>
                              </tr>
                              <tr>
                              <td colspan="4" width="100%" style="padding:5px"><span>Room Rate</span></td>
                              <td style="padding:5px"><span><?php echo roundoff_number($booking_details['total_fare_markup']/$booking_details['currency_conversion_rate']); ?></span></td>
                              </tr>
                              <tr>
                              <td colspan="4" width="100%" style="padding:5px"><span>Taxes & Fees</span></td>
                              <td style="padding:5px"><span><?php echo roundoff_number(($booking_details['total_fare_tax']+$booking_details['agentServicefee'])/$booking_details['currency_conversion_rate']); ?></span></tdstyle=>
                              </tr>

                              <?php if($itinerary_details['gst'] > 0){?>
                              <tr>
                                 <td colspan="4" width="100%" style="padding:5px"><span>GST</span></td>
                                 <td style="padding:5px"><span><?php echo roundoff_number($itinerary_details['gst']/$booking_details['currency_conversion_rate']); ?></span></td>
                              </tr>
                              <?php } ?>
                              <tr>
                              <td colspan="4" width="100%" style="padding:5px"><span>Discount</span></td>
                              <td style="padding:5px"><span><?php echo roundoff_number($booking_details['discount']/$booking_details['currency_conversion_rate']); ?></span></td>
                              </tr>
                              
                              <tr>
                              <td colspan="4" width="100%" style="border-top:1px solid #ccc;padding:5px"><strong style="font-size:10pt">Total Fare</strong></td>
                              <td style="border-top:1px solid #ccc;padding:5px;font-size:10pt;"><strong style=""><?php echo roundoff_number(($booking_details['total_fare_markup']+$booking_details['total_fare_tax']+$booking_details['agentServicefee'])/$booking_details['currency_conversion_rate']); ?></strong></td>
                              </tr>
                              <?php if($booking_details['total_destination_charge'] > 0){?>
                              <tr>
                                 <td colspan="4" width="100%" style="border-top:1px solid #ccc;padding:5px"><span>Local Tax <small>(Pay at property)</small></span></td>
                                 <td style="border-top:1px solid #ccc;padding:5px;font-size:10pt;"><span><?php echo roundoff_number($booking_details['total_destination_charge']/$booking_details['currency_conversion_rate']); ?></span></td>
                              </tr>
                              <?php } ?>
                           <!-- </tbody>
                        </table>
                        </td> -->
                     <!-- </tr> -->
                  </tbody>
               </table>
               </td>
            </tr>
            <!-- <tr><td align="center" colspan="4" width="100%"; style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:10pt; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No :<?=$booking_details['phone_code']?> <?=$customer_details[0]['phone']?></span></td></tr> -->
            <tr><td style="line-height:2px;">&nbsp;</td></tr>
            <tr>
               <td><strong style="font-size: 10pt;line-height:12px;">Cancellation Policy</strong></td>
            </tr>
            <tr>
               <td style="font-size:10pt; color:#555; line-height:16px;padding-top:10px;"><?=$booking_details['cancellation_policy'][0]?></td>
            </tr>
            <tr><td style="line-height:2px;">&nbsp;</td></tr>
            				
			<!-- </table>
         </td>
      </tr> -->
   </tbody>
</table>
<tr>
   <td><strong style="font-size: 10pt;line-height:12px;">Terms and Conditions</strong></td>
</tr>
<div style="font-family:arial;font-size:10pt; color:#555;">
   <tr>
      <td colspan="4" width="100%"; style="border-bottom:1px solid #999999; padding:15px 0px; font-size:10pt; color:#555;line-height:16px">
      <ul>
         <?php if(isset($booking_details['rate_comments'])== true && is_array($booking_details['rate_comments'])){
                     foreach($booking_details['rate_comments'] as $dt_key=>$rc_data){
                        echo '<li>'.$rc_data.'</li>';
                     }
               }?></ul>
            <?php echo $data['terms_conditions']; ?>
      </td>
   </tr>
</div>
<table style="font-family:arial;">
   <tr><td style="line-height:2px;">&nbsp;</td></tr>
   <tr>
      <td style="background-color:#efefef;padding:10px 5px;"><table cellspacing="0" cellpadding="5" width="100%">
         <tbody>
            <tr>
               <td colspan="2" width="100%">
                  <span style="font-size:10pt;"><strong><?=$data['domainname']?> Support: </strong><span style="font-size: 10pt;">Email Address: <span><?= $this->entity_domain_mail?></span></span></span>
               </td>
               <td style="font-size: 10pt;" colspan="2" align="right">Contact Info: <span><?=$data['phone_code']?><?=$data['phone']?></span></td>
            </tr>
            <tr>
               <td colspan="4"><span style="font-size:10pt;"><?=$data['address']?></span></td>
            </tr>
         </tbody>
      </table>
      </td>
   </tr>
</table>
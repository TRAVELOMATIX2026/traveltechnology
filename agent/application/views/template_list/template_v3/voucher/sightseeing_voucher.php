<?php 
  $booking_details = $data['booking_details'][0];
 
  $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  $attributes = json_decode($booking_details['attributes'],true);
  if($attributes['Additional_info']){
      $additional_info = json_decode($attributes['Additional_info']);
  }else{
    $additional_info =array();
  }
  if($attributes['Inclusions']){
    $inclustions =json_decode($attributes['Inclusions'],true);
    $inclustions = array_reduce($inclustions, function($carry, $item) {
      $carry[$item['group_code']][] = $item['description'];
      return $carry;
  }, []);   
  }else{
    $inclustions = array();
  }
 
  if($attributes['Exclusions']){
    $exclustions = json_decode($attributes['Exclusions'],true);
    $exclustions = array_reduce($exclustions, function($carry, $item) {
      $carry[$item['group_code']][] = $item['description'];
      return $carry;
  }, []);
  }else{
    $exclustions = array();
    
    
  }
  if($attributes['Duration']){
    $duration = $attributes['Duration'];
  }else{
    $duration = '';
  }
   if($attributes['ShortDesc']){
    $desc = $attributes['ShortDesc'];
  }else{
    $desc = $attributes['ShortDesc'];
  }
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
<div style="background:#ccc; width:100%; position:relative;padding: 50px;">
    <!-- <table cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:12px; font-family: 'Open Sans', sans-serif; width:850px; margin: 10px auto;background-color:#fff; border-collapse:separate; color: #000;">
        <tbody>
            <tr>
                <td class="text-center" style="background: <?=($receipt_type == 'no_price') ? '#f18604' : '#008100' ;?>;border: 2px solid #fff"> <a href="<?=base_url();?>voucher/activities/<?= @$booking_details['app_reference'] ?>/<?= @$booking_details['booking_source'] ?>/<?= @$booking_details['status']?>/show_voucher/no_price" target="_blank" style="font-size: 16px;font-weight: bold;color: white;"> No Price Included </a> </td>
                <td class="text-center" style="background: <?=($receipt_type == 'agent_receipt') ? '#f18604' : '#008100' ;?>;border: 2px solid #fff"><a href="<?=base_url();?>voucher/activities/<?= @$booking_details['app_reference'] ?>/<?= @$booking_details['booking_source'] ?>/<?= @$booking_details['status'] ?>/show_voucher/agent_receipt" target="_blank" style="font-size: 16px;font-weight: bold;color: white;"> Agent Receipt </a> </td>
            </tr>
        </tbody>
    </table> -->
<div class="table-responsive" style="width:100%; position:relative" id="tickect_sightseeing">
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
                                 <!-- <tr>
                                    <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td>
                                 </tr> -->
                                 <tr>
                                    <td>
                                       <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                                          <tbody>
                                             <tr style="background: #008100;color:#fff;">
                                                <td style="padding: 0px;"><img style="width:170px;" src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"></td>
                                                <td style="padding: 0px;">
                                                   <table width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif;border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                         <tr>
                                                            <td style="padding-right:10px;line-height:20px" align="right"><span>Travel Date: <?=date("l\, jS F Y",strtotime($booking_details['travel_date']));?></span><br><span>Booking Reference: <?=$booking_details['app_reference']?></span><br><span>Activity Code: <?=$booking_details['product_code']?></span></td>
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
                                    <td align="right" style="line-height:24px;font-size:13px;border-top:1px solid #008100;border-bottom:1px solid #008100;padding: 5px;">Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:14px;"><?php 
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
                                                  <?php if($attributes['ProductImage']):?>
                                                    <img style="width:160px;height:107px;" src="<?=$attributes['ProductImage']?>">
                                                  <?php else: ?>
                                                    <img style="width:160px;height:107px;" src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>">
                                                  <?php endif;?>
                                                </td>
                                                <td valign="top" style="padding:10px;"><span style="line-height:22px;font-size:16px;color:#008100;vertical-align:middle;font-weight: 600;"><?=$booking_details['product_name']?></span><br><span style="display: block;line-height:22px;font-size: 13px;"><?=$attributes['Destination']?> </span>
                                                <?php if(!empty($attributes["StarRating"])){ ?>
                                                <span style="display: block;line-height:22px;font-size: 13px;">
                                                   <img style="width:70px;" src="<?php echo $GLOBALS['CI']->template->template_images('star_rating-'.$attributes["StarRating"].'.png'); ?>"></span>
                                                   <?php } ?>
                                                </td>
                                                <td width="32%" style="padding:10px 0;text-align: center;"><span style="font-size:14px; border:2px solid #008100; display:block"><span style="color:#008100;padding:5px; display:block;text-transform:uppercase">Booking ID</span><span style="font-size:14px;line-height:35px;padding-bottom: 5px;display:block;font-weight: 600;"><?=$booking_details['booking_reference']?></span></span></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#008100;border: 1px solid #008100; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'hotel_v.png'?>"> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Activity Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #008100; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                          <tbody>
                                             <tr>
                                                <!-- <td>Phone</td> -->                                   
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Phone No</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Duration</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center">Total Traveler(s)</td>
                                             </tr>
                                             <tr>
                                                <td style="padding:5px"><span style="width:100%; float:left"> <?=$attributes['SupplierName']?></span></td>
                                                <td style="padding:5px"><span style="width:100%; float:left">   <?=$attributes['SupplierPhoneNumber']?></span></td>
                                                <?php 
                $total_travell_count = $booking_details['adult_count']+$booking_details['child_count'] +$booking_details['senior_count']+$booking_details['youth_count']+$booking_details['infant_count'];
                ?>
                                                <td style="padding:5px"><?=$duration?></td>
                                                <td style="padding:5px" align="center"><?=$total_travell_count?></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#008100;border: 1px solid #008100; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>"> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Traveller(s) Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #008100; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                                          <tbody>
                                             <tr>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Sr No.</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passenger(s) Name</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Type</td>
                                             </tr>
                                             <?php $i=1;?>  
                                             <?php foreach($customer_details as $name): ?>                               
                                             <tr>
                                                <td style="padding:5px;"><?=$i?></td>
                                                <td style="padding:5px"><?=$name['title'].'.  '.$name['first_name'].' '.$name['last_name']?></td>
                                                <td style="padding:5px;"><?=$name['pax_type']?></td>                            
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
                                                <td width="50%" style="padding:0;padding-right:14px;vertical-align:top;">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;border:1px solid #008100;">
                                                      <tbody>
                                                         <tr>
                                                            <td style="border-bottom:1px solid #008100;background:#008100;color:#fff;padding:5px;"><span style="font-size:13px">Payment Details</span></td>
                                                            <td style="border-bottom:1px solid #008100;background:#008100;color:#fff;padding:5px;"><span style="font-size:11px">Amount (<?=$booking_details['currency']?>)</span></td>
                                                         </tr>
                                                      <?php if($receipt_type == 'agent_receipt') { ?>
                                                          <tr>
                                                            <td style="border-top:1px solid #008100;padding:5px"><span style="font-size:13px">Base Fare</span></td>
                                                            <td style="border-top:1px solid #008100;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['grand_total']-$booking_details['gst']); ?></span></td>
                                                         </tr> 
                                                         <?php if($booking_details['gst'] > 0){?>
                                                         <tr>
                                                            <td style="border-top:1px solid #008100;padding:5px"><span style="font-size:13px">GST</span></td>
                                                            <td style="border-top:1px solid #008100;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['gst']); ?></span></td>
                                                         </tr> 
                                                         <?php } ?>
                                                          <tr>
                                                            <td style="border-top:1px solid #008100;padding:5px"><span style="font-size:13px">Grand Total</span></td>
                                                            <td style="border-top:1px solid #008100;padding:5px"><span style="font-size:13px"><?php echo number_format(($booking_details['grand_total']+ $booking_details['agentServicefee']),2); ?></span></td>
                                                         </tr>
                                                      <?php } else { ?>
                                                          <td style="border-top:1px solid #008100"><span style="font-size:13px;font-weight: bold;">Grand Total</span></td>
                                                         <td style="border-top:1px solid #008100"><span style="font-size:13px;font-weight: bold;"> <?= number_format(@$booking_details['grand_total'] + $booking_details['agentServicefee'], 2) ?></span></td>
                                                      <?php } ?>
                                                        
                                                       
                                                         
                                                      </tbody>
                                                   </table>
                                                </td>
                                                <td width="50%" style="padding:0;padding-left:14px; vertical-align:top">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #008100;font-size:12px; padding:0;">
                                                      <tbody>
                                                         <tr>
                                                            <td style="background:#008100;color:#fff;border-bottom:1px solid #008100;padding:5px;"><span style="font-size:13px">Activity Inclusions</span></td>
                                                         </tr>
                                                         <?php if($inclustions):?>
                                                          <?php foreach($inclustions as $ky => $incl):?>
                                                         <tr>
                                                            <!-- <td style="padding:5px"><span><?=$incl?></span></td> -->
                                                            <!-- <td style="padding:5px;font-size:9pt;"><span><?=$incl['group_code'] . ' - ' . $incl['description'] ?></span></td> -->
                                                            <!-- <td style="padding:5px;font-size:9pt;"><span>drunk</span></td>
                                                            <td style="padding:5px;font-size:9pt;"><span>water</span></td>
                                                            <td style="padding:5px;font-size:9pt;"><span>water</span></td>
                                                            <td style="padding:5px;font-size:9pt;"><span>water</span></td> -->
                                                            <td><span style="font-weight:500;padding:5px;"><?=$ky?></span></td>
                                                         </tr>
                                                         <tr>
                                                            <td style="padding:5px;">
                                                               <ul>
                                                               <?php foreach($incl as $incv):?>
                                                                  <li><?=$incv?></li>
                                                               <?php endforeach;?>
                                                               </ul>
                                                            </td>
                                                         </tr>
                                                       <?php endforeach;?>
                                                     <?php else:?>
                                                      <tr>
                                                        <td style="padding:5px">Acitity Only</td>
                                                      </tr>
                                                        <?php endif;?>
                                                         
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
                                    <td align="center" colspan="4" style="border-bottom:1px solid #008100;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No : <?=$customer_details[0]['phone']?></span></td>
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
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Tour Information</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; padding-bottom:15px; font-size:12px; color:#555">
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Tour Description</span><br><?=$desc?><br>
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Information</span>
                                      <ul>
                                      <?php foreach($additional_info as $ad):?><li><?=$ad?></li><?php endforeach;?></ul>
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Exclusions</span>
                                      <ul>
                                       <?php foreach($exclustions as $eky => $exclu):?>
                                       <li><?=$eky?></li>
                                       <?php foreach($exclu as $exv):?>
                                          <li><?=$exv?></li>
                                          <?php endforeach;?>
                                       <?php endforeach;?></ul>
                                    </td>
                                 </tr>                                 
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Terms and Conditions</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #008100; padding-bottom:15px; font-size:12px; color:#555"><?php echo $data['terms_conditions']; ?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" align="" style="padding-top:10px">
                                       <span style="font-weight:600;"><?php echo strtoupper($data['domainname']) ?></span>&nbsp;&nbsp; | &nbsp;&nbsp;<span style="font-weight:600;">Contact No: </span>+<?=$this->entity_country_code;?> <?=$this->entity_phone;?>&nbsp;&nbsp; | &nbsp;&nbsp;<span  style="font-weight:600;">Address: </span><?php echo $this->entity_address ?>
                                    </td>
                                 </tr>
                                 
                                 <tr>
                                    <td colspan="4" align="right" style="padding:0px;">
                                       <span style="font-size:12px;">Powered by: </span> <span style="display:inline-block;"> <img style="width:90px;" src="<?php echo $GLOBALS['CI']->template->template_images('starlegend_logo.png'); ?>" /> </span>
                                    </td>
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
      html += document.getElementById('tickect_sightseeing').innerHTML;
      html += "</body></html>";

   var printWin = window.open();
   printWin.document.write(html);
   printWin.document.close();
   printWin.focus();
   printWin.print();
}
</script>


<div class="car_sec">
 <?php
 $template_images = $GLOBALS['CI']->template->template_images();
 $i = 0;
  // debug($raw_car_list);exit;
 foreach($raw_car_list['CarSearchResult']['CarResults'] as $car_key => $car_details) {
  // debug($car_details);exit;
  $all_coverage_type = array();
  if(isset($car_details) && valid_array($car_details)) {
      $PickUpLocationCode = $car_details['PickUpLocationCode'];
      $ReturnLocationCode = $car_details['ReturnLocationCode'];					
      $AirConditionInd = $car_details['AirConditionInd'];
      $TransmissionType = $car_details['TransmissionType'];
      $FuelType = $car_details['FuelType'];
      $DriveType = $car_details['DriveType'];
      $PassengerQuantity = $car_details['PassengerQuantity'];
      $BaggageQuantity = $car_details['BaggageQuantity'];
      $VendorCarType = $car_details['VendorCarType'];
      $Code = $car_details['Code'];
      $CodeContextType = $car_details['CodeContext'];
      $DoorCount = $car_details['DoorCount'];
      $car_type = $car_details['VehicleCategory'];	
      $car_type_name = $car_details['VehicleCategoryName'];
      $car_class = $car_details['VehClassSizeName'];	
      $car_class_id = $car_details['VehClassSize'];  			
      $car_name = $car_details['Name'];
      $car_image = $car_details['PictureURL'];
      $supplier_logo = $car_details['TPA_Extensions']['SupplierLogo'];				
      $TotalAmount = $car_details['TotalCharge']['EstimatedTotalAmount']; 
      $OnewayTotalFare =  $car_details['TotalCharge']['OneWayFee'];
      $ID_Context = @$car_details['Reference']['ID_Context'];
      $Type = @$car_details['details']['Reference']['Type'];
      $Vendor = @$car_details['Vendor'];
      $vehicle_package = @$car_details['RateComments'];
      $VendorLocation = @$car_details['VendorLocation'];					
      $DropOffLocation = @$car_details['DropOffLocation'];
      $oneway_fee ='';
      $mileagetype = 'Limited' ;
      $car_name = str_replace('or similar', '', $car_name);  
      ######################################################
      ######################################################
      $fuel_policy_code = '';
      $fuel_policy_desc ='';
      // debug($car_details['PricedCoverage']);exit;
      if(isset($car_details['PricedCoverage']) && !empty($car_details['PricedCoverage']))
      {
        $offer_Includes_html = '';
        foreach($car_details['PricedCoverage'] as $key => $pricedCoverage)
        {
          // debug($pricedCoverage);exit;
         
          if($key == 0){
            $oneway_fee .= $pricedCoverage['CoverageType'];
            $oneway_code = $pricedCoverage['Code'];
          }
          if($pricedCoverage['Code'] == 'UNL'){
            $mileagetype = 'Unlimited';
          }
          if($pricedCoverage['Code'] == 'F2F'){      
            $fuel_policy_code .= $pricedCoverage['Code'];
            $fuel_policy_desc .= @$pricedCoverage['Desscription'];
          }
          $temp_arr = array('UNL', 'F2F', 'F2E', 'CF', 'CDW', '412', $oneway_code); 
          // debug($temp_arr);exit;    
          if(!in_array($pricedCoverage['Code'], $temp_arr)){      
            $all_coverage_type[] = @$pricedCoverage['CoverageType'];
          }
          if($pricedCoverage['Amount'] != 0 && $pricedCoverage['IncludedInRate'] != true){
            $desc = 'Pay on pick-up in local currency: '.$pricedCoverage['Desscription'].' : '.$currency_obj->get_currency_symbol($currency_obj->to_currency).' '.$pricedCoverage['Amount'];
          }
          else{
            $desc = $pricedCoverage['Desscription'];
          }
          $pan = $key;
          $offer_Includes_html .= '<div class="carprc clearfix">
                                   <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                     <button type="button" class="sumtab" data-bs-toggle="collapse" id="carnect'.$car_key.'" data-bs-target="#agelmt'.$car_key.''.$pan.'"><b>'.$pricedCoverage['CoverageType'].'</b></button>
                                     <div class="prcright">
                                       <p class="car_price_new">'.$currency_obj->get_currency_symbol($currency_obj->to_currency)." ".$pricedCoverage['Amount'].'</p>
                                     </div>
                                   </div>
                                 <div id="agelmt'.$car_key.''.$pan.'" class="collapse">
                                  <p>'. $desc.'</p>
                                 </div>
                                </div>';      
        }
     }

    
               	
   ?>
  <div class="rowresult r-r-i">          
    <div class="madgrid">
     <div class="col-12 nopad">
      <div class="car_result_item">
      <div class="sidenamedesc mobile_f_i">
       <div class="celhtl width20 midlbord">
        <div class="car_image"> <img src="<?=$car_image?>" class="lazy lazy_loader h-img" onError="this.onerror=null;this.src='<?php echo $GLOBALS['CI']->template->template_images('no-img.jpg'); ?>';"/></div>
       </div>
       <div class="celhtl width60">
        <div class="waymensn">
         <div class="flitruo_hotel">
          <div class="hoteldist"> 
           <span class="supplier_name hide"><?=$Vendor;?></span>
           <span class="car_type hide"><?=@$car_type_name;?></span>	
           <span class="car_type_id hide"><?=@$car_type;?></span>  
            									
           <span class="car_name"><?=$car_name?><span> or Similar</span></span>
           <div class="clearfix"></div>
           <!-- <span class="hotel_address elipsetool"><?=$CodeContextType?></span> -->
           <div class="clearfix"></div>

           <!-- <div class="pick cr_wdt">
            <i class="material-icons">place</i> <span>Pickup Location:</span>
            <h3><?= ucwords($search_params['data']['car_from']); ?></h3>
           </div> -->
        <div class="pick-wrapper d-flex gap-2">
            <div class="pick cr_wdt">
             <span><i class="material-icons">directions_car</i> Vehicle Class:</span>
            <h3><?=ucwords(@$car_class);?></h3>
            <span class="hide vehicle_size"><?=@$car_class_id?></span>
            <span class="hide vehicle_package"><?=@$vehicle_package?></span>
           </div>

           <div class="pick">		    
            <span class="fuel_icon">Fuel:<?=$fuel_policy_code?></span>
            <h3>
            <a href="#" data-bs-toggle="tooltip" title="<?=$fuel_policy_desc?>">
              <i class="material-icons">info</i>
              <?=$FuelType; ?>
             </a>
            </h3>
           </div>
          
           <div class="pick cr_wdt">
            <span><i class="material-icons">directions_car</i>  Vehicle Type:</span>
            <h3><?=ucwords(@$car_type_name);?></h3>
           </div>

           <?php if(isset($TransmissionType) && !empty($TransmissionType)){ ?>
           <div class="pick">
            <span><i class="material-icons">settings</i> Transmission:</span>
            <h3><?=$TransmissionType?></h3>
            <span class="hide vehicle_manual"><?=$TransmissionType; ?></span>
           </div>
           <?php } ?>

           <?php if(isset($AirConditionInd) && !empty($AirConditionInd)){ ?>
           <div class="pick">
            <span><i class="material-icons">ac_unit</i> Air Conditioning:</span>
            <h3><?php if($AirConditionInd == 'true'){ echo "A/C"; }else{ echo "Non A/C"; } ?></h3>
            <span class="hide vehicle_ac"><?php if($AirConditionInd == 'true'){ echo "AC"; }else{ echo "Non-AC"; } ?></span>
           </div>
           <?php } ?>

           <?php if(isset($DriveType) && !empty($DriveType)){ ?>
           <div class="pick">
            <span><i class="material-icons">settings_input_component</i> Drive Type:</span>
            <h3><?=ucwords($DriveType);?></h3>
           </div>
           <?php } ?>
          </div>
           
           <div class="clearfix"></div>
           <div class="middleCol">
            <ul class="features">
             <li class="person tooltipv">
              <a title="Passengers" data-bs-toggle="tooltip">
                <i class="material-icons">people</i>
               <?php if(isset($PassengerQuantity) && !empty($PassengerQuantity)){ ?><strong><?=$PassengerQuantity?></strong> <span class="hide passenger_quantity"><?=$PassengerQuantity?></span> <?php } ?>
              </a>
             </li> 
             <li class="baggage tooltipv">
              <a  title="Bags" data-bs-toggle="tooltip">
                <i class="material-icons">luggage</i>
               <?php if(isset($BaggageQuantity) && !empty($BaggageQuantity)){ ?><strong><?=$BaggageQuantity?></strong> <?php } ?>
              </a>
             </li> 
             <li class="doors tooltipv">
              <a  title="Doors" data-bs-toggle="tooltip">
                <i class="material-icons">directions_car</i>
               <?php if(isset($DoorCount) && !empty($DoorCount)){ ?><strong><?=$DoorCount?></strong> <span class="hide door_count"><?=$DoorCount?></span> <?php } ?>
              </a>
             </li>    
            </ul>

             <div class="pick">
               <i class="material-icons">speed</i> <span>Mileage Allowance:</span>
               <h3><?= $mileagetype; ?></h3>
             </div>

          
            <div class="suplier_logo"> <img src="<?=$supplier_logo?>" alt=""></div>
             </div>
             <div class="clearfix"></div>		
            </div>
           </div>
          </div>
         </div>
        </div>
        <div class="width20 mobile_f_i">
         <div class="mrinfrmtn">
          <div class="sidepricewrp">

           <div class="sideprice">
             <strong><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></strong>
             <span class="vehicle_price hide"><?=$TotalAmount?></span>
               <span class="f-p" data-price="<?=$TotalAmount?>"><?php
                echo $TotalAmount;
              ?></span>
          </div>
          <?php 
          //debug($car_details['CancellationPolicy']);exit;
          $policy_text ='';
          if(isset($car_details['CancellationPolicy']) && valid_array($car_details['CancellationPolicy']) && !empty($car_details['CancellationPolicy'])){
            
            $policy_text .= '<p class="policy_text">';
            foreach($car_details['CancellationPolicy'] as $policy){
              $polic_amount[] = $policy['Amount'];
             
              $policy_text .= $currency_obj->get_currency_symbol($currency_obj->to_currency).' '.$policy['Amount'].' Amount would be charged between '.$policy['FromDate'] .' to '.$policy['ToDate']. '<br/>';

            }
            $policy_text .= '</p>';
            if(in_array(0, $polic_amount)){
              $non_ref = false;
            }
            else{
              $non_ref = true;
            }
          }
          else{
            $non_ref = true;
          }
          
         if($non_ref == false){
          ?>
          <span class="text-center non_ref" style="color:#0B9FD1; display: block;"><a id="cancel_<?=$car_key?>" class="cancel-policy-btn" data-bs-target="#roomCancelModal" data-bs-toggle="modal" data-cancel= '<?php echo $policy_text; ?>'>Cancellation Policy</a></span>
          <?php } 
          else{ ?>
             <span class="text-center non_ref" style="color:#0B9FD1; display: block;">Non Refundable</span>
         <?php } ?>
          <!-- <span class="text-center non_ref" style="color:#0B9FD1; display: block;">Cancellation Policy</span> -->

           <div class="bookbtn">
            <form method="post" action="<?php echo base_url().'index.php/car/car_details/'.($search_id)?>" name="form_transfer_">
              <input type="hidden" id="mangrid_id_<?=$car_key?>_<?=$Code?>" value="<?=$car_details['ResultToken']?>" name="ResultIndex"  data-key="<?=$car_key?>" data-hotel-code="<?=$Code?>" class="result-index">
              <input type="hidden" id="booking_source_<?=$car_key?>_<?=$Code?>" value="<?=urlencode($booking_source)?>" name="booking_source"  data-key="<?=$car_key?>" data-hotel-code="<?=$Code?>" class="booking_source">
              <input type="hidden" value="get_details" name="op" class="operation">
             <input type="submit" value="Book" class="booknow frdsk" />
            
            </form> 

           </div>

          </div> 
           
           <a class="detailsflt" data-bs-toggle="collapse" data-bs-target="#car_rental<?=$car_key?>"> More Details <span class="caret"></span>
          </a>
         </div>
        </div>
        </div>
        <?php 
      if(isset($car_details['RateRestrictions']) && !empty($car_details['RateRestrictions'])){
       $minimum_age = $car_details['RateRestrictions']['MinimumAge'];
       $maximum_age = $car_details['RateRestrictions']['MaximumAge'];
      }      
      ?>
      <div id="car_rental<?=$car_key?>" class="collapse" data-role="dialog">
       <div class="carextent">
        <div class="modal-content1">
         <div class="clearfix"></div>
         <div class="modal-body1">
          <div class="col-12 nopad">
           <div class="middleCol">
            <ul class="features1">                                      
              <li data-original-title="gear" class="transmission tooltipv">
               
               <?php if(isset($TransmissionType) && !empty($TransmissionType)){ ?> 
                 <i class="material-icons">settings</i>
                 <span class="vehicle_manual hide"><?=$TransmissionType; ?></span> 
                 <strong><?=$TransmissionType?></strong><?php } ?>
               </li>

             <li data-original-title="Air Conditioning" class="ac tooltipv">
              
              <?php if(isset($AirConditionInd) && !empty($AirConditionInd)){ ?> 
                <i class="material-icons">ac_unit</i>
                <span class="vehicle_ac hide"><?php if($AirConditionInd == 'true'){ echo "AC"; }else{ echo "Non-AC"; } ?></span>
                <strong><?php if($AirConditionInd == 'true'){ echo "A/C"; }else{ echo "Non A/C"; } ?></strong><?php } ?>
              </li>

              <?php if(!empty($oneway_fee)){?>
              <li  class="fuel tooltipv" data-bs-toggle="tooltip" title="<?=$oneway_fee?>">
               <i class="material-icons">flight</i>
               <strong> <?=$oneway_fee?></strong>
             </li>
              <?php } ?>

            </ul>                                             
           </div>
           <div class="clearfix"></div>
           <div class="rentcondition">
            <div class="hotel_detailtab">
             <div class="clearfix"></div>
             <div class="tab-content"> 
              <div class="tab-pane active" id="htldets<?=$car_key?>">
               <div class="innertabs">
                <div class="secn_pot"> 
                 <div class="includ">
                  <div class="parasub">
                   <ul class="checklist">
                    <?php 
                    // debug($all_coverage_type);exit;
                    $all_coverage_type = array_unique($all_coverage_type);
                    foreach($all_coverage_type as $all_coverage_type_k => $all_coverage_type_v)
                    {
                    
                      ?>
                      <li> <i class="material-icons">check</i><?=$all_coverage_type_v?></li>
                      <?php
                    
                    }
                    ?>
                   </ul>
                  </div>
                 </div>
                 <?php 
                 if(isset($minimum_age) || isset($maximum_age)) {
                  ?> 
                  <div class="linebrk"></div>
                  <button type="button" class="sumtab" data-bs-toggle="collapse" data-bs-target="#agelmt">Age Limit</button>
                  <div class="collapse in age_lmt" id="agelmt">
                   <div class="parasub">
                    <ul class="checklist">	                     
                     <li> <span>Minimum age: <strong><?=$minimum_age?></strong></span></li>
                     <li> <span>Maximum age: <strong><?=$maximum_age?></strong></span></li>	                      
                    </ul>
                   </div>
                  </div>
                  <div class="linebrk"></div>
                  <?php 
                 } 
                 ?>
                 <div class="clearfix"></div>
                 <p class="carhead">Offer Includes</p>
                 <div id="see_more<?=$car_key?>" class="collapse in">                   
                  <?=$offer_Includes_html;?>
                 </div>
                 
                </div>
               </div>
              </div>
              <div class="clearfix"></div>
             </div>
            </div> 	
           </div>
          </div>
         </div>
        </div>
       </div>
      </div>
       </div>		
       <?php 
      }
      ?>
      <div class="clearfix"></div>
      
     </div>
    </div>
    <?php 
    $i++; 
   }
    $mini_loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>'; 
   ?>
  </div>

<!-- pop-up -->
<div class="modal fade cancellation-policy-modal" id="roomCancelModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content cancellation-policy-content">
        <div class="modal-header cancellation-policy-header">
          <h4 class="modal-title cancellation-policy-title">
            <i class="material-icons">description</i>
            <span>Cancellation Policy</span>
          </h4>
          <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body cancellation-policy-body">
          <p class="can-loader hide"><?=$mini_loading_image?></p>
          <div class="text-center loader-image hide">
            <img src="/extras/system/template_list/template_v3/images/loader_v3.gif" alt="Loading........">
          </div>
          <div id='can-model'>
            <div class="policy_text"></div>
          </div>
        </div>
        <div class="modal-footer cancellation-policy-footer">
          <button type="button" class="btn cancellation-close-btn" data-bs-dismiss="modal">
            <i class="material-icons">check</i>
            <span>Close</span>
          </button>
        </div>
      </div>
    </div>
  </div>
<!-- end -->
  <script type="text/javascript">
   $(document).ready(function(){
    $('[data-bs-toggle="tooltip"]').tooltip();
   });
  </script>

  <script type="text/javascript">
   $(document).ready(function() {
    
 
      
    // Open filter drawer
    $(document).on('click', '.filter-fab-btn, .filter_show', function(e) {
      e.preventDefault();
      console.log('Filter button clicked!');
      $('.coleft').addClass('filter-open');
      $('.filter-overlay').addClass('active');
      $('body').css('overflow', 'hidden');
      console.log('Filter drawer opened');
    });
    
    // Close filter drawer
    $(document).on('click', '.close_fil_box, .filter-overlay', function() {
      console.log('Closing filter drawer...');
      $('.coleft').removeClass('filter-open');
      $('.filter-overlay').removeClass('active');
      $('body').css('overflow', '');
    });
    
    // Update filter count badge
    function updateFilterCount() {
      var count = $('.locationul input:checked').length + 
                 ($('.rangebox input:checked').length || 0);
      if (count > 0) {
        $('.filter-fab-btn .filter-count').removeClass('hide').text(count);
      } else {
        $('.filter-fab-btn .filter-count').addClass('hide');
      }
    }
    
    // Update count when filters change
    $(document).on('change', '.locationul input, .rangebox input', function() {
      updateFilterCount();
    });
    
    // Close drawer on reset
    $(document).on('click', '#reset_filters', function() {
      setTimeout(function() {
        updateFilterCount();
      }, 100);
    });
    
    $('.squaredThree label').bind('click',function(){
     var input = $(this).find('input');  
     if(input.prop('checked')){
      input.prop('checked',false);
      $('html, body').animate({scrollTop:0}, 1500);
     }else{
      input.prop('checked',true);
      $('html, body').animate({scrollTop:0}, 1500);
     }
    });
    $(".cancel-policy-btn").on("click",function(){
      $("#can-model").html('');
      var policy_text = $(this).data('cancel');
      
      // Parse and format policy text with icons
      var formatted_html = formatCancellationPolicy(policy_text);
      
      $("#can-model").html(formatted_html);
      $(".can-loader").addClass('hide');
      $(".loader-image").addClass('hide');
      
    });
    
    // Function to format cancellation policy text with icons
    function formatCancellationPolicy(policyText) {
      if (!policyText) return '';
      
      var html = '';
      var policyLines = policyText.split('<br/>');
      
      policyLines.forEach(function(line) {
        if (line.trim() === '') return;
        
        // Parse the line: "$ 0 Amount would be charged between 2026-01-03 14:01 to 2026-01-28 09:00"
        var match = line.match(/([\$€£¥₹]?\s*[\d,]+\.?\d*)\s+Amount would be charged between\s+(.+?)\s+to\s+(.+)/i);
        
        if (match) {
          var amount = match[1].trim();
          var fromDate = match[2].trim();
          var toDate = match[3].trim();
          
          // Determine if it's free cancellation (amount is 0)
          var isFree = parseFloat(amount.replace(/[^\d.]/g, '')) === 0;
          var cardClass = isFree ? 'free-cancellation' : 'charged-cancellation';
          var icon = isFree ? 'check_circle' : 'warning';
          
          html += '<div class="policy-item-card ' + cardClass + '">';
          html += '<div class="policy-item-icon">';
          html += '<i class="material-icons">' + icon + '</i>';
          html += '</div>';
          html += '<div class="policy-item-content">';
          html += '<div class="policy-amount">';
          html += '<i class="material-icons">attach_money</i>';
          html += '<span>' + amount + ' Amount</span>';
          html += '</div>';
          html += '<div class="policy-date-range">';
          html += '<i class="material-icons">event</i>';
          html += '<span class="policy-date-item">';
          html += '<i class="material-icons" style="font-size: 14px;">schedule</i>';
          html += '<span>' + formatDate(fromDate) + '</span>';
          html += '</span>';
          html += '<span class="policy-separator">→</span>';
          html += '<span class="policy-date-item">';
          html += '<i class="material-icons" style="font-size: 14px;">schedule</i>';
          html += '<span>' + formatDate(toDate) + '</span>';
          html += '</span>';
          html += '</div>';
          if (isFree) {
            html += '<div class="policy-description">Free cancellation during this period</div>';
          } else {
            html += '<div class="policy-description">Cancellation charges apply</div>';
          }
          html += '</div>';
          html += '</div>';
        } else {
          // Fallback for non-matching format
          html += '<div class="policy-item-card">';
          html += '<div class="policy-item-icon">';
          html += '<i class="material-icons">info</i>';
          html += '</div>';
          html += '<div class="policy-item-content">';
          html += '<div class="policy-amount">' + line + '</div>';
          html += '</div>';
          html += '</div>';
        }
      });
      
      return html;
    }
    
    // Function to format date string
    function formatDate(dateStr) {
      if (!dateStr) return dateStr;
      
      // Try to parse and format the date
      try {
        var date = new Date(dateStr.replace(/(\d{4})-(\d{2})-(\d{2})\s+(\d{2}):(\d{2})/, '$1-$2-$3T$4:$5:00'));
        if (!isNaN(date.getTime())) {
          var options = { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          };
          return date.toLocaleDateString('en-US', options);
        }
      } catch(e) {
        // If parsing fails, return original
      }
      
      return dateStr;
    }
   });
  </script>

 
<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('car-result.css'), 'media' => 'screen');
?>
<?php
//error_reporting(E_ALL); 
//debug($product_details);exit;
 //debug($product_details['available_date']);
$loading_image = '<div class="result-loader-content">
	<div class="result-loader-graphic">
		<div class="sightseeing-icon">
			<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
			</svg>
		</div>
		<div class="loader-rings">
			<div class="ring ring-1"></div>
			<div class="ring ring-2"></div>
			<div class="ring ring-3"></div>
		</div>
	</div>
	<p class="result-loader-text">Loading activities...</p>
	<div class="loader-progress-bar">
		<div class="loader-progress-fill"></div>
	</div>
</div>';
  $end_year_date =  end($product_details['Product_available_date']);
  $end_year = key($product_details['Product_available_date']);
 // $end_year_date = end($last_year_arr);  

  $start_date_picker = reset($product_details['Product_available_date']);

  $star_month_year = key($product_details['Product_available_date']);  

  //$start_date_picker = reset($start_date_arr); 

  $starting_month_date = $start_date_picker;
  $to_month_date = date('Y-m-d', strtotime($starting_month_date. ' + 1 day'));
  $end_yr_month_date=  $end_year_date;

  $end_month_date = date('Y-m-d',strtotime($end_yr_month_date));

 Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.v2.css'), 'media' => 'screen');
 Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sightseeing-details.css'), 'media' => 'screen');
 Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sightseeing-slider.css'), 'media' => 'screen');
 Js_Loader::$css[] = array('href' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', 'media' => 'screen');
 Js_Loader::$css[] = array('href' => 'https://fonts.googleapis.com/icon?family=Material+Icons', 'media' => 'screen');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer'); 
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_details_slider.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('lightbox-gallery.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer'); 

 // debug($product_details);
 // exit;
 $product_available_date =$product_details['Product_available_date'];

?>
<div class="outactdiv sghtseen">

  <div class="container p-0">

  <?php 
    $a=4;
    $b=4;
    $show_picture='style=display:none';
    $show_video ='style=display:none';
    if($product_details['Product_Video']){
       $show_video='';
       $show_picture='style=display:none';
    }else{               
       $show_picture='';
    }
    
    // Count total photos
    $total_photos = 0;
    if(isset($product_details['ProductPhotos']) && $product_details['ProductPhotos']){
      $total_photos = count($product_details['ProductPhotos']);
    }
  ?>
  
  <div class="slider-gallery-wrapper" <?=$show_picture?>>
    <div class="main-slider-section">
      <div id="actvdeo" <?=$show_video?>>
        <video width="100%" controls>
          <source src="<?=$product_details['Product_Video']?>" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      </div>
      <div class="more_pic" <?=$show_video?>><i class="bi bi-images"></i> See More Photos</div>
      
      <div id="act_sldr" <?=$show_picture?>>
        <div id="hotel_top" class="owl-carousel owl-theme act_carsl">
          <?php if(isset($product_details['ProductPhotos'])):?>
          <?php if($product_details['ProductPhotos']):?>
          <?php foreach($product_details['ProductPhotos'] as $photos):?>
          <div class="item">
            <img src="<?=$photos['photoHiResURL']?>" alt="<?=@$photos['caption']?>">
          </div>
          <?php endforeach;?>
          <?php else:?>
          <div class="item">
            <img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="Image">
          </div>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="more_vdeo" <?=$show_video?>><i class="bi bi-play-circle"></i> See Video</div>
    </div>
    
    <div class="thumbnail-gallery-section" <?=$show_picture?>>
      <?php if(isset($product_details['ProductPhotos']) && $product_details['ProductPhotos'] && $total_photos > 0):?>
        <?php 
          $thumb_count = 0;
          $max_thumbs = 8; // Increased from 4 to 8
        ?>
        <div class="thumbnail-grid">
          <?php foreach($product_details['ProductPhotos'] as $key => $photos):?>
            <?php if($thumb_count < $max_thumbs - 1):?>
              <div class="thumb-item" data-index="<?=$key?>">
                <img src="<?=$photos['photoHiResURL']?>" alt="<?=@$photos['caption']?>">
              </div>
              <?php $thumb_count++; ?>
            <?php elseif($thumb_count == $max_thumbs - 1):?>
              <div class="thumb-item thumb-more" data-index="<?=$key?>">
                <img src="<?=$photos['photoHiResURL']?>" alt="<?=@$photos['caption']?>">
                <div class="thumb-overlay">
                  <i class="bi bi-images"></i>
                  <span>+<?=($total_photos - $max_thumbs + 1)?> More</span>
                </div>
              </div>
              <?php $thumb_count++; ?>
              <?php break; ?>
            <?php endif;?>
          <?php endforeach;?>
        </div>
      <?php endif;?>
    </div>
  </div>
  
    <div class="col-12 nopad">
      
      <div class="org_row">
        
          <div class="col-md-8">
            <div class="col-md-12 nopad">
              <div class="htladdet light-border">
                <span><?=$product_details['ProductName']?></span>
                <?php
                if(empty($product_details['ReviewCount']))
                {
                  $product_details['ReviewCount'] = 0;
                }
                $current_hotel_rate = $product_details['StarRating'];
                //echo $current_hotel_rate;
              ?>
                <ul class="std d-none">
                  <li class="starrtinghotl rating-no">
                    <?php //echo print_star_rating($current_hotel_rate);?>
                  </li>
                  &nbsp;
                  <?php 
               //$product['reviewCount'] = 5;
               if(!empty($product_details['ReviewCount']))
               {
               ?>
                  <li><span class="rcount"><?=$product_details['ReviewCount']?> Reviews</span></i></li>
                  <?php }
               else { ?>
                  <!--<li><span class="rcount">0 Reviews</span></i></li>-->
                  <?php }?>
                </ul>
                <?php 
               //$product['reviewCount'] = 5;
               if(!empty($product_details['ProductCode']))
               {
               ?>
                <div class="review hide">Tour code : <?php echo $product_details['ProductCode'];?></div>
                <?php }?>
                <!--  <ul class="actstardiv">
             
               <li><i class="fa fa-star" aria-hidden="true"></i></li>
              
             </ul> -->
                <ul class="locdurdiv">
                  <li>Location: <?=$product_details['Location']?></li>
                  <?php if(($product_details['Duration']) && empty($product_details['Duration']) == false):?>
                  <li> Duration:<span><?=$product_details['Duration']?></span>(approx.)</li>
                  <?php endif;?>
                </ul>
              </div>
              <div class="clearfix"></div>
              <div class="masthead-wrapper act_wrp">
                <div class="col-md-12 actimgdiv">
                  <div id="holidaySliderone" class="holiySlide hide">
                    <?php if(isset($product_details['ProductPhotos'])):?>
                    <?php foreach($product_details['ProductPhotos'] as $photos):?>
                    <div class="item">
                      <img src="<?=$photos['photoHiResURL']?>" alt="<?=@$photos['caption']?>" />
                    </div>
                    <?php endforeach;?>
                    <?php endif; ?>
                  </div>
                
                  <?php 
          //debug($product_details);
          //exit
        ?>
                  <div class="clearfix"></div>
                  <div class="basic_info">
                    <h4>Important Information</h4>
                    <ul class="list-inline">
                      <?php if($product_details['Cancellation_available']):?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$product_details['Cancellation_day']?> days prior</li>
                      <?php else:?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('non-refundable.svg'); ?>" alt="icon" />Non Refundable</li>
                      <?php endif;?>
                      <?php if($product_details['VoucherOption']):?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('show_mobile.svg'); ?>" alt="icon" /> <?=$product_details['VoucherOption']?></li>
                      <?php endif;?>
                      <?php if($product_details['Duration']):?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('duration.svg'); ?>" alt="icon" /><?php echo $product_details['Duration']?> Duration</li>
                      <?php endif;?>
                      <?php if($product_details['Product_Tourgrade'][0]['langServices']):?>
                      <?php if(valid_array($product_details['Product_Tourgrade'])):?>
                      <?php foreach($product_details['Product_Tourgrade'][0]['langServices'] as $lan_val):?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('language.svg'); ?>" alt="icon" /><?=$lan_val?></li>
                      <?php endforeach;?>
                      <?php endif;?>
                      <?php endif;?>
                      <?php if($product_details['MaxTravellerCount']):?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('join_group.svg'); ?>" alt="icon" /> Maximum Traveller - <b style="color:#0096ce"><?=$product_details['MaxTravellerCount']?></b></li>
                      <?php endif;?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('meet_up.svg'); ?>" alt="icon" /> Meet Up At Location</li>
                      </li>
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                  <form id="check_tourgrade" action="">
                    <div id="prc_rght">
                      <h5>Select participants:</h5>
                      <div class="org_row">
                       
                        <input type="hidden" name="productID" value="PRODUCT" id="productID">
                        <input type="hidden" name="product_code" value="<?=$product_details['ProductCode']?>">
                        <input type="hidden" name="ResultToken" value="<?=$product_details['ResultToken']?>">
                        <input type="hidden" name="search_id" value="<?=$search_id?>">
                        <input type="hidden" name="booking_engine" value="<?=$product_details['BookingEngineId']?>">
                        <input type="hidden" name="get_date" value="<?=date('d',strtotime($starting_month_date))?>" id="get_date">
                        <input type="hidden" name="get_month" value="<?=date('m',strtotime($starting_month_date))?>" id="get_month">
                        <input type="hidden" name="get_year" value="<?=date('Y',strtotime($starting_month_date))?>" id="get_year">
                        <input type="hidden" name="op" value="check_tourgrade">
                        <input type="hidden" name="age_band" value="<?=base64_encode(json_encode($product_details['Product_AgeBands']))?>">
                        <input type="hidden" name="booking_source" value="<?=$active_booking_source?>">
                        <?php 
                   ?>
                        <input type="hidden" name="additional_info" value="<?=base64_encode(json_encode($product_details['AdditionalInfo']))?>">
                        <input type="hidden" name="inclusions" value="<?=base64_encode(json_encode($product_details['Inclusions']))
                   ?>">
                        <input type="hidden" name="voucher_req" value="<?=base64_encode($product_details['Voucher_req']);?>">
                        <input type="hidden" name="exclusions" value="<?=base64_encode(json_encode($product_details['Exclusions']))?>">
                        <input type="hidden" name="short_desc" value="<?=base64_encode($product_details['ShortDescription'])?>">
                      
                        <div class="adudiv hide">
                          
                          <div class="travel-title">
                            <p>Travelllers</p>
                          </div>
                        </div>
                        <div class="col-12 col-md-7">
                          <div class="trvlr">
                            <input type="hidden" name="" id="total_age_band" value="<?=count($product_details['Product_AgeBands']);?>">
                            <div class="totlall">
                              <span class="remngwd">
                                <input type="hidden" name="total_pax_count" id="total_pax_count">
                                <span id="travel_text">Total Traveller</span><span id="total_pax">1</span></span>
                              <div class="roomcount pax_count_div">
                                <div class="inallsn">
                                  <div class="oneroom fltravlr">
                                    <div class="mobile_adult_icon">Travellers<i class="material-icons">person</i></div>
                                    <div class="clearfix"></div>
                                    <?php 
                    // debug($product_details['Product_AgeBands']);
                    // exit;
                  ?>
                                    <?php foreach($product_details['Product_AgeBands'] as $key=>$age_band):?>
                                    <div class="roomrow">
                                      <?php
                            $fa_class = 'person';
                            $min_count = 0;
                            $adult_count=0;
                            if($age_band['description']=='Adult'){
                              $min_count = 1;
                              $adult_count=1;
                              $class = 'activities-btn-number';
                            }
                            if($age_band['description']=='Infant' || $age_band['description']=='Child'){
                                $fa_class ='child_care';
                                $class = 'activity-btn-number';

                            }
                          ?>
                                      <input type="hidden" name="<?=$age_band['description']?>_Band_ID" value="<?=$age_band['bandId']?>">
                                      <input type="hidden" name="<?=$age_band['description']?>_count" value="<?=$age_band['count']?>">
                                      <input type="hidden" name="<?=$age_band['description']?>_treat" value="<?=$age_band['treatAsAdult']?>">
                                      <div class="celroe col-7"><i class="material-icons"><?=$fa_class?></i> <?=$age_band['description']?>
                                        <span class="agemns">(<span>Age <?=$age_band['ageFrom']?> to <?=$age_band['ageTo']?></span>)</span>
                                      </div>
                                      <div class="celroe col-5">
                                        <div class="input-group countmore pax-count-wrapper <?=$age_band['description']?>_count_div">
                                          <span class="input-group">
                                            <button type="button" class="btn btn-secondary <?php echo $class;?>" data-type="minus" data-field="no_of_<?=$age_band['description']?>"> <i class="bi bi-dash"></i>
                                            </button>
                                          </span>
                                          <input type="text" id="gradeageband_<?=$key?>" name="no_of_<?=$age_band['description']?>" class="form-control input-number centertext valid_class pax_count_value" value="<?=$adult_count?>" min="<?=$min_count?>" max="<?=$product_details['MaxTravellerCount']?>" readonly>
                                          <span class="input-group">
                                            <button type="button" class="btn btn-secondary <?php echo $class;?>" data-type="plus" data-field="no_of_<?=$age_band['description']?>"> <i class="bi bi-plus"></i> </button>
                                          </span>
                                        </div>
                                      </div>
                                    </div>
                                    <?php if($class == "activity-btn-number"){?>
                                    <div class="age_input">
                                      <div class="main-div">
                                        <div class="pax-age hide">
                                          <select name="age[]" required class="normalsel padselctsmal arimo" value="">
                                            <?php for($i=0;$i<18;$i++){?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                            <?php } ?>
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <?php } ?>
                                    <?php endforeach;?>
                                    <a class="done1 comnbtn_room1" id="pax_done_btn"><span class="fa fa-check"></span> Done</a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-5 smpad">
                          <div class="chk_avl">
                            <button type="button" class="patencls" id="check-avil-btn">Check Availability</button>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        
                      </div>
                      <div class="col-12">
                          <span class="ac_note hide"><strong>Note:</strong> Active calendar date shows availability of the Package.</span>
                          <div class="alert-box hide" id="activity-alert-box">You have selected extra travellers for this activity. Maximum allowed travellers for this activity is <strong><?=$product_details['MaxTravellerCount']?></strong>.</div>
                        </div>
                    </div>
                  </form>
                  <div class="clearfix"></div>
                  <div id="myDiv">
                    <div id="tourloader" class="hide">
                      <?php echo $loading_image; ?>
                    </div>
                    <div id="actitivity-list">
                    </div>
                  </div>
                  <div class="col-md-12 col-12 nopad">
                    <div class="ovrimpdiv">
                      <ul class="nav nav-pills hide">
                        <li class="active full_mobile"><a data-bs-toggle="pill" href="#home"><i class="fal fa-list-alt"></i> Overview</a></li>
                        <li class="full_mobile"><a data-bs-toggle="pill" href="#menu1"><i class="fal fa-info-circle"></i> Important Info</a></li>
                        <!--<li class="full_mobile"><a data-bs-toggle="pill" href="#menu2"><i class="fal fa-smile"></i> Review</a></li>-->
                      </ul>
                      <div class="tab-content">
                        <div id="home" class="tab-pane active" style="display:none !important;">
                          <div class="innertabs">
                            <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Overview</h3>
                            <div class="clearfix"></div>
                            <div class="lettrfty short-text"><?=$product_details['Description']?></div>
                            <div class="show-more">
                              <a href="#">Show More +</a>
                            </div>
                          </div>
                        </div>
                        <div id="menu1" class="tab-pane">
                          <div class="innertabs">
                            <h3 class="mobile_view_header"><i class="bi bi-info-circle"></i> Details</h3>
                            <div class="clearfix"></div>
                            <div class="lettrfty">
                              <div class="cms-content mtm">
                                <p>Description : <?=$product_details['ShortDescription']?></p>
                                <?php if(empty($product_details['MaxTravellerCount']) == false){?>
                                <div class="mvm">
                                  <div class="strong">
                                    <p>Maximum Traveller : <?=$product_details['MaxTravellerCount']?></p>
                                  </div>
                                </div>
                                <?php } ?>
                                <div class="mvm">
                                  <div class="strong">Voucher Information</div>
                                  <p><?=$product_details['Voucher_req'];?></p>
                                </div>
                                <?php if(isset($product_details['Itinerary'])):?>
                                <?php if($product_details['Itinerary']):?>
                                <div>
                                  <div class="strong">Itinerary</div>
                                  <p><?=$product_details['Itinerary']?></p>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <?php if(isset($product_details['Inclusions'])): ?>
                                <?php if($product_details['Inclusions']):?>
                                <div class="mvm">
                                  <div class="strong">Inclusions</div>
                                  <ul>
                                    <?php foreach($product_details['Inclusions'] as $include):?> <li><?=$include['description'];?></li>
                                    <?php endforeach;?>
                                  </ul>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <?php if(isset($product_details['Exclusions'])): ?>
                                <?php if($product_details['Exclusions']):?>
                                <div class="mvm">
                                  <div class="strong">Exclusions</div>
                                  <ul>
                                    <?php foreach($product_details['Exclusions'] as $exclude):?> <li><?=$exclude['description']?></li>
                                    <?php endforeach;?>
                                  </ul>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <?php if(isset($product_details['SalesPoints'])): ?>
                                <?php if($product_details['SalesPoints']): ?>
                                <div class="mvm">
                                  <div class="strong">HighLights</div>
                                  <ul>
                                    <?php foreach($product_details['SalesPoints'] as $sales):?> <li><?=$sales?></li>
                                    <?php endforeach;?>
                                  </ul>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <?php if(isset($product_details['TermsAndConditions'])): ?>
                                <?php if($product_details['TermsAndConditions']):?>
                                <div class="mvm">
                                  <div class="strong">TermsAndConditions</div>
                                  <ul>
                                    <?php foreach($product_details['TermsAndConditions'] as $terms):?> <li><?=$terms?></li>
                                    <?php endforeach;?>
                                  </ul>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <?php if(isset($product_details['Highlights'])): ?>
                                <?php if($product_details['Highlights']):?>
                                <div class="mvm">
                                  <div class="strong">Highlights</div>
                                  <ul>
                                    <?php foreach($product_details['Highlights'] as $hight):?> <li><?=$hight?></li>
                                    <?php endforeach;?>
                                  </ul>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                                <?php if(isset($product_details['AdditionalInfo'])): ?>
                                <?php if($product_details['AdditionalInfo']): ?>
                                <div class="mvm">
                                  <div class="strong">AdditionalInfo</div>
                                  <ul>
                                    <?php foreach($product_details['AdditionalInfo'] as $info):?> <li><?=$info?></li>
                                    <?php endforeach;?>
                                  </ul>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                              </div>
                              <div class="clearfix"></div>
                              <h3 class="">
                                <i class="bi bi-shield-check"></i>&nbsp;Cancellation Policy
                                <a href="#" class="policy-link ms-3" data-bs-target="#cancelPolicyModal" data-bs-toggle="modal" style="font-size: var(--font-size-base); font-weight: var(--font-weight-normal);">
                                  <i class="bi bi-info-circle"></i> View Policy
                                </a>
                              </h3>
                              <h3><i class="fa fa-pencil-square" aria-hidden="true"></i>&nbsp;Meeting Point</h3>
                              <div class="cms-content mtm">
                                <div class="mvm">
                                  <?php if($product_details['DeparturePoint']):?>
                                  <p>Departure Point : <?=$product_details['DeparturePoint']?> </p>
                                  <?php endif;?>
                                  <?php if($product_details['DepartureTime']):?>
                                  <p>Departure time : <?=$product_details['DepartureTime']?></p>
                                  <?php endif;?>
                                  <?php if($product_details['DepartureTimeComments']):?>
                                  <p>DepartureTimeComments : <?=$product_details['DepartureTimeComments']?> </p>
                                  <?php endif;?>
                                  <?php if($product_details['Duration']):?>
                                  <p>Duration : <?=$product_details['Duration']?></p>
                                  <?php endif; ?>
                                  <?php if($product_details['ReturnDetails']):?>
                                  <p>Return Details : <?=$product_details['ReturnDetails']?></p>
                                  <?php endif;?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                            </div>
                            <!-- <div class="show-more">
                    <a href="#">Show More +</a>
                </div> -->
                          </div>
                        </div>
                        <div id="menu2" class="tab-pane" style="display: none !important;">
                          <div class="innertabs">
                            <h3 class="mobile_view_header"><i class="fal fa-smile"></i>&nbsp;Customer Reviews</h3>
                            <div class="clearfix"></div>
                            <?php
                                  $add_class="";
                                 if(!empty($product_details['Product_Reviews'])) {
                                 if(sizeof($product_details['Product_Reviews'])>3){
                                  $add_class="short-text";
                                 }
                               }
                             ?>
                            <div class="pad15 lettrfty <?=$add_class?>">
                              <?php if($product_details['Product_Reviews']):?>
                              <?php foreach($product_details['Product_Reviews'] as $review):?>
                              <div class="revoutdiv">
                                <div class="reviewdv">
                                  <?php if(isset($review['UserImage'])):?>
                                  <img src="<?=$review['UserImage']?>" alt="Avatar" />
                                  <?php else:?>
                                  <img src="<?=$GLOBALS['CI']->template->template_images('sadimg.png')?>" alt="Avatar" />
                                  <?php endif;?>
                                </div>
                                <div class="contdivrew1">
                                  <h5><?=$review['UserName']?></h5>
                                  <div class="rewstar">
                                    <ul class="rewstardv">
                                      <?php for($r=1;$r<=$review['Rating'];$r++):?>
                                      <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                      <?php endfor;?>
                                    </ul>
                                    <span class="xsmall"><?php echo date('l\, jS F Y',strtotime($review['Published_Date']));?></span>
                                  </div>
                                  <div class="cms-content mtm">
                                    <p><?=$review['Review']?></p>
                                  </div>
                                </div>
                              </div>
                              <?php endforeach;?>
                              <?php else:?>
                              <p>No Reviews</p>
                              <?php endif;?>
                            </div>
                            <?php if($add_class!=''):?>
                            <div class="show-more">
                              <a href="#">Show More +</a>
                            </div>
                            <?php endif;?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 ps-3">
            <div class="frmdiv">
              <div class="booking-price">
                <span class="price-from">Best Price From </span>
                <span class="price-amount price-amount-l">
                  <span class="currency-sign"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></span>
                  <span class="currency-amount"><?=$product_details['Price']['TotalDisplayFare']?></span>
                </span>
              </div>
              <div class="datediv">
                <div class="clearfix"></div>
                <input type="hidden" name="max_count" value="<?=$product_details['MaxTravellerCount']?>">
                <input type="hidden" name="all_passenger_name_req" value="<?=$product_details['AllTravellerNamesRequired']?>">
                <div class="clearfix"></div>
                <div class="spc_list">
                  <ul>
                    <?php if($product_details['Product_available_date']):?>
                    <li><i class="fas fa-clock"></i> Earliest available date: <?=date('d M Y',strtotime($starting_month_date))?></li>
                    <?php endif;?>
                    <li><i class="fas fa-thumbs-up"></i> Instant Confirmation</li>
                    <?php if($product_details['Cancellation_available']):?>
                    <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$product_details['Cancellation_day']?> days prior</li>
                    <?php else:?>
                    <li><img src="<?php echo $GLOBALS['CI']->template->template_images('non-refundable.svg'); ?>" alt="icon" />Non Refundable</li>
                    <?php endif;?>
                    <!--   <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$product_details['Cancellation_day']?> days prior</li>  -->
                    <?php if($product_details['Promotion']):?>
                    <li><i class="fas fa-gift"></i> Special Offer: <span><?=$product_details['Promotion']?></span></li>
                    <?php endif;?>
                  </ul>
                </div>
                <div class="patendiv">
                  <button type="button" class="patencls1" id="bk_now">Book Now</button>
                </div>
              </div>
            </div>
          </div>
      </div>
      
    </div>
  </div>
</div>
<?php
 
  $selected_date_str =  base64_encode(json_encode($product_available_date,JSON_FORCE_OBJECT));
  
  $calendar_available_date = $product_details['Calendar_available_date'];

?>
<script>
  var js_arr  = [<?php echo '"'.implode('","',  $calendar_available_date ).'"' ?>];
  var enableDays = js_arr;
  
     function enableAllTheseDays(date) {
          var sdate = $.datepicker.formatDate( 'd-m-yy', date)
          if($.inArray(sdate, enableDays) != -1) {
              return [true];
          }
          return [false];
    } 
    function enableAllTheseToDays(date) {
          var sdate = $.datepicker.formatDate( 'd-m-yy', date)
          if($.inArray(sdate, enableDays) != -1) {
              return [true];
          }
          return [false];
    } 
    $( function() {
       var product_available_date_obj = "<?php echo $selected_date_str?>";
  
      var selecteddte = "<?=$star_month_year?>"+'-'+"<?=$start_date_picker?>";
      var enddate = "<?=$end_month_date?>";
        
       var start = new Date(selecteddte);
      start.setFullYear(start.getFullYear());
      var end = new Date(enddate);
      end.setFullYear(end.getFullYear());
        
      $( "#activitydate" ).datepicker({
          dateFormat: 'dd-mm-yy',
          minDate: start,
          maxDate: end,
          beforeShowDay:enableAllTheseDays,
          yearRange: start.getFullYear() + ':' + end.getFullYear(),
          onClose: function( selectedDate ) {
           // var date1 = $('#activitydate').datepicker('getDate');
             var date = $(this).datepicker('getDate'),
             day  = date.getDate();
             month = date.getMonth();       
             two_digit_month = ("0" + (date.getMonth() + 1)).slice(-2);      
             year =  date.getFullYear();
             
              $("#get_date").val(day);
              $("#get_month").val(two_digit_month);
              $("#get_year").val(year);
             var options = '';
             var monthNames = ["Jan", "Feb", "Mar", "April", "May", "June",
                "July", "Aug", "Sep", "Oct", "Nov", "Dec"
              ];
  
             for (var i = day; i <=31; i++) {
               options +='<option value='+i+'>'+i+'</option>';
             }
            var end_year = end.getFullYear();
             var year_option = '';
             for(var j=year ; j<=end_year;j++){
                
                for (var k =month ; k<12; k++) {
                    var two_d_month = k+1;
                    if(two_d_month>=10){
                      two_d_month = (k+1);
                    }else{
                      two_d_month = '0'+(k+1);
                    }
                    year_option +='<option value='+j+'-'+two_d_month+'>'+monthNames[k]+' '+j+'</option>'  
                }
                month=1;
                
              
             }
             $("#sel2").html(year_option);
             $("#sel1").html(options);
  
          } 
  
      });
  
  
      $("#sel1").change(function(){
        $("#get_date").val('');
      });
      $("#sel2").change(function(){
          $("#get_month").val('');
          $("#get_year").val('');
          var selected_month_value = $("#sel2 option:selected").val();
          var product = "<?=$product_details['ProductCode']?>";
          $.ajax({
              
              type:'post',
              url:"<?php echo base_url()?>"+'index.php/sightseeing/select_date',
              data:{
                selected_date:selected_month_value,available_date:product_available_date_obj
              },
              success:function(res){
                if(res){
                  $("#sel1").html(res);
                }
              },
              error:function(res){
                console.log("Selected Month Ajax Error");
              }
          });
          
      });
      $("#check-avil-btn").click(function(){
  
        
         var total_adult = $("input[name=no_of_Adult]").val();
          var age_band_count = $("#total_age_band").val();
          var max_traveller = "<?php echo $product_details['MaxTravellerCount']?>";
         // alert(total_adult);
         //alert(age_band_count);
         //alert(max_traveller);
          var total_count = 0;
          for (var i = 0; i < age_band_count; i++) {
              total_count = parseInt(total_count) + parseInt($("#gradeageband_"+i).val());
          }
          //alert(total_count);
  
          if(total_count==0){
            alert('Please select Passenger');
            return false;  
          }else if(total_adult == 0){
            alert("Minimum One Adult is required for this trip.Please select Adult count");
            return false;
          }
          else if(total_count>max_traveller){
            $("#activity-alert-box").removeClass('hide');
            // Modern snackbar replaces old alert popup
            return false;
          }
          $("#tourloader").removeClass('hide');
          $("#actitivity-list").empty();
           $("#activity-alert-box").addClass('hide');
          $.ajax({
              type:'post',
              url:"<?php echo base_url()?>"+'index.php/sightseeing/select_tourgrade',
              data:$('#check_tourgrade').serialize(),
              success:function(response){
                if (response.hasOwnProperty('status') == true && response.status == true) {
                     $("#tourloader").addClass('hide');
                    $('#actitivity-list').html(response.data);
                }else{
                  var html = '';
                   $("#tourloader").addClass('hide');
                  html +='<div class="alert-box text-center"><h4>Activity not available for this date.Kindly change the date and check.</h4></div>';
                  $('#actitivity-list').html(html);
                }
              },
              error:function(){
                console.log("TRIP LIST AJAX ERROR");
              }
          });
          // return false;
      });
    });
   
  $(document).ready(function(){
    //$('.main-div').addClass('hide'); 
      $("#total_pax_count").val(1);
       var total_pax =$("#total_pax_count").val();
      var text_str = 'Traveller';
      if(parseInt(total_pax)>1){
          text_str = 'Travellers';
      }
      //$("#travel_text").text("Total "+text_str+" "+total_pax);
  
      $(".more_vdeo").hide();
      $(".more_pic").click(function(){
          $("#act_sldr").show();
          $(".more_vdeo").show();
          $("#actvdeo").hide();
          $(".more_pic").show();
          //alert();
      });
      $(".more_vdeo").click(function(){
          $("#actvdeo").show();
          $(".more_pic").show();
          $("#act_sldr").hide();
          $(".more_vdeo").hide();
      });
  
      $("#pax_done_btn").click(function(e){
        e.preventDefault();
        var total_pax =$("#total_pax_count").val();
        var text_str = 'Traveller';
        if(parseInt(total_pax)>1){
          text_str = 'Travellers';
        }
  
       // $("#travel_text").text("Total "+text_str+" "+total_pax);
      });
  
      $("#bk_now").click(function() {
          $('html, body').animate({
              scrollTop: $("#prc_rght").offset().top
          }, 700);
          $("#prc_rght").addClass('opc');
          //$("#activitydate").datepicker('show');
      });
      $(".show-more a").on("click", function() {
      var $link = $(this);
      var $content = $link.parent().prev("div.lettrfty");
      var linkText = $link.text();
      $content.toggleClass("short-text, full-text");
      $link.text(getShowLinkText(linkText));
      return false;
      });
      function getShowLinkText(currentText) {
          var newText = '';
          if (currentText.toUpperCase() === "SHOW MORE +") {
              newText = "Show Less -";
          } else {
              newText = "Show More +";
          }
          return newText;
      }
  });
  $('.activity-btn-number').click(function(e) {
                  e.preventDefault();
                  fieldName = $(this).attr('data-field');
                  type = $(this).attr('data-type');
                  var current_pax_count_wrapper = $(this).closest('.pax-count-wrapper');        
                  var input = $("input[name='" + fieldName + "']", current_pax_count_wrapper);
                  var currentVal = parseInt(input.val()); 
                  var age = $('.pax-age').first().clone();
                   
                  if (!isNaN(currentVal)) {
                      if (type == 'minus') {
                          if (currentVal > input.attr('min')) {
                              input.val(currentVal - 1).change()
                              $('.no_pax').text(currentVal - 1);
                              if(currentVal == 1){
                                 $('.pax-age').addClass('hide');
                              } 
                              else{
                                $('.main-div').children().last().remove(); 
                              }
                          }
  
                          if (parseInt(input.val()) == input.attr('min')) {}
                      } else if (type == 'plus') {
                          if (currentVal < input.attr('max')) {
                              input.val(currentVal + 1).change()
                              $('.no_pax').text(currentVal + 1);
                              age.find('.ageofpax').text(currentVal + 1); 
                              if(currentVal+1 == 1){
                                  $('.pax-age').removeClass('hide');
                              }
                              else{
                                  age.appendTo('.main-div'); 
  
                              }
                              age.show();
                          }
                          
  
                          if (parseInt(input.val()) == input.attr('max')) { }
                      }
                 
                      
                  
                  } else {
                      input.val(0)
                  }
                   var form_id = 'check_tourgrade';     
                  activites_total_pax_count(form_id)
              });

              // Snackbar/Toast notification handler
              (function() {
                  var snackbar = document.getElementById('activity-alert-box');
                  
                  if (snackbar) {
                      // Close button functionality
                      snackbar.addEventListener('click', function(e) {
                          // Check if click is near the close button (::after pseudo-element)
                          var rect = snackbar.getBoundingClientRect();
                          var closeButtonArea = {
                              left: rect.right - 40,
                              top: rect.top + 10,
                              right: rect.right - 10,
                              bottom: rect.top + 40
                          };
                          
                          if (e.clientX >= closeButtonArea.left && 
                              e.clientX <= closeButtonArea.right &&
                              e.clientY >= closeButtonArea.top && 
                              e.clientY <= closeButtonArea.bottom) {
                              snackbar.classList.add('hide');
                          }
                      });
                      
                      // Auto-hide after 8 seconds when shown
                      var observer = new MutationObserver(function(mutations) {
                          mutations.forEach(function(mutation) {
                              if (mutation.attributeName === 'class') {
                                  if (!snackbar.classList.contains('hide')) {
                                      setTimeout(function() {
                                          snackbar.classList.add('hide');
                                      }, 8000); // Auto-hide after 8 seconds
                                  }
                              }
                          });
                      });
                      
                      observer.observe(snackbar, { attributes: true });
                  }
              })();
  
</script>

<!-- Cancellation Policy Modal -->
<div class="modal fade cancellation-policy-modal" id="cancelPolicyModal" role="dialog" aria-hidden="false" style="display: none;">
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
				<div id="can-model">
					<div class="policy_text">
						<?php echo $product_details['Cancellation_Policy']; ?>
					</div>
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

<?php
   // echo '<script>';
   // $script = 'console.log($("#sel2 option:selected").val())';
   // echo $script;
   // echo '</script>';
?>
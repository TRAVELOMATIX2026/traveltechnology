
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_details_slider.js'), 'defer' => 'defer');

$booking_url = $GLOBALS['CI']->hotel_lib->booking_url($hotel_search_params['search_id']);

$mini_loading_image = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="Loading........"/></div>';

$loading_image = '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div>';

$_HotelDetails = $hotel_details['HotelInfoResult']['HotelDetails'];

// debug($_HotelDetails);exit;

$sanitized_data['HotelCode'] = $_HotelDetails['HotelCode'];

$sanitized_data['HotelName'] = $_HotelDetails['HotelName'];

$sanitized_data['StarRating'] = $_HotelDetails['StarRating'];

$sanitized_data['Description'] = $_HotelDetails['Description'];

$sanitized_data['Attractions'] = (isset($_HotelDetails['Attractions']) ? $_HotelDetails['Attractions'] : false);

$sanitized_data['HotelFacilities'] = (isset($_HotelDetails['HotelFacilities']) ? $_HotelDetails['HotelFacilities'] : false);

$sanitized_data['HotelPolicy'] = (isset($_HotelDetails['HotelPolicy']) ? $_HotelDetails['HotelPolicy'] : false);

$sanitized_data['SpecialInstructions'] = (isset($_HotelDetails['SpecialInstructions']) ? $_HotelDetails['SpecialInstructions'] : false);

$sanitized_data['Address'] = (isset($_HotelDetails['Address']) ? $_HotelDetails['Address'] : false);

$sanitized_data['PinCode'] = (isset($_HotelDetails['PinCode']) ? $_HotelDetails['PinCode'] : false);

$sanitized_data['HotelContactNo'] = (isset($_HotelDetails['HotelContactNo']) ? $_HotelDetails['HotelContactNo'] : false);

$sanitized_data['Latitude'] = (isset($_HotelDetails['Latitude']) ? $_HotelDetails['Latitude'] : 0);

$sanitized_data['Longitude'] = (isset($_HotelDetails['Longitude']) ? $_HotelDetails['Longitude'] : 0);

$sanitized_data['RoomFacilities'] = (isset($_HotelDetails['RoomFacilities']) ? $_HotelDetails['RoomFacilities'] : false);

$sanitized_data['Images'] = $_HotelDetails['Images'];



if ($sanitized_data['Images']) 
{

    $sanitized_data['Images'] = $sanitized_data['Images'];
} else {

    $sanitized_data['Images'] = $GLOBALS['CI']->template->template_images('default_hotel_img.jpg');
}

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');



$base_url_image = base_url() . 'index.php/hotel/image_details_cdn';



//debug($_HotelDetails);exit;

?>



<?php

/**

 * Application VIEW

 */

// echo $GLOBALS['CI']->template->isolated_view('hotel/search_panel_summary');

?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY"></script>

<script type="text/javascript">
    /** Google Maps **/

    var myCenter = new google.maps.LatLng(<?= floatval($sanitized_data['Latitude']) ?>, <?= floatval($sanitized_data['Longitude']); ?>);

    var image_url = "<?php echo $GLOBALS['CI']->template->template_images() ?>";

    var hotel_name = "<?php echo $sanitized_data['HotelName'] ?>";

    function initialize()
     {

     var mapProp = {

     center:myCenter,

     zoom:10,

     mapTypeId:google.maps.MapTypeId.ROADMAP
     
     };

     var map = new google.maps.Map(document.getElementById("Map"), mapProp);
     var marker = new google.maps.Marker({
     position:myCenter,

     });

     

     marker.setMap(map);

     

     var infowindow = new google.maps.InfoWindow({

     content:hotel_name

     });

     

     google.maps.event.addListener(marker, "click", function() {

     infowindow.open(map, marker);

     });

     }

     google.maps.event.addDomListener(window, "load", initialize);
</script>

<div class="clearfix"></div>

<input type="hidden" id="latitude" value="<?= $sanitized_data['Latitude'] ?>">

<input type="hidden" id="longitude" value="<?= $sanitized_data['Longitude'] ?>">

<input type="hidden" id="api_base_url" value="<?= $GLOBALS['CI']->template->template_images('marker/green_hotel_map_marker.png') ?>">

<input type="hidden" id="hotel_name" value="<?php echo $sanitized_data['HotelName'] ?>">

<div class="search-result">
    <div class="container-fluid mobilepad">
        <div class="htl_dtls_cont htldetailspage galary_pop">
            <div class="rowfstep">
                <!-- slider -->
                <div class="col-md-12 col-sm-12 col-12 nopad">
                    <div class="col-md-12 nopad">
                        <div class="htladdet hide">
                            <span><?php echo strtoupper($sanitized_data['HotelName']) ?></span>
                            <div class="marhtldet">

                                <span class="locadres"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;<?php echo $sanitized_data['Address'] ?></span>

                                <ul class="htlratpz">

                                    <div class="stardetshtl"><span class="rating-no"><span class="hide" id="h-sr"><?= $sanitized_data['StarRating'] ?></span><?php echo print_star_rating($sanitized_data['StarRating']); ?></span>

                                    </div>
                                    <!-- <li><i class="fa fa-star" aria-hidden="true"></i></li> -->
                                </ul>

                                <?php if ($_HotelDetails['trip_adv_url']) : ?>

                                    <div class="triexcimg mobile_advisor">

                                        <a href="#"><img src="<?= $_HotelDetails['trip_adv_url'] ?>"></a>

                                    </div>

                                <?php endif; ?>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-12 nopad">

                        <div class="htldtdv">
                            <!-- New Gallry -->

                            <div class="htldtdv">
                                <div class="glry">
					              <div class="row">
                                    <?php 

                                    if(valid_array($sanitized_data['Images']) == true) 
                                    { 
                                        //debug($sanitized_data['Images']); exit;
                                        $sanitized_data_gallary = array_slice($sanitized_data['Images'],0,5);
                                        //debug($sanitized_data_gallary); exit;
                                        ?>

                                    <?php 
                                    $image_counts = 1;
                                        foreach ($sanitized_data_gallary as $i_k => $i_v) 
                                        {

                                        ?>
                                        <div class="column">
                                        <img src="<?= $i_v ?>" onclick="openModal();currentSlide(<?=$image_counts?>)" class="hover-shadow cursor" alt="<?=$sanitized_data['HotelName']?>">
                                        </div>

                                    <?php 
                                            $image_counts++;
                                        } 

                                    } 
                                    else 
                                    { ?>

                                    <?= '<img src="' . $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') . '" alt="' . $sanitized_data['HotelName'] . '"/>' ?>
                                <?php 
                                    } 
                                    
                                ?>
					                </div>
					              </div> 
					          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <!-- slider end -->
         
        </div>

    </div>

    <div class="clearfix"></div>

    <div class="fulldowny">

        <div class="container-fluid mobilepad">

            <div class="fuldownsct">
                 <!-- New Book Now Div start -->

                 <div class="col-md-4 float-end">
                    <div class="col-12 booknow-div">
                        <div class="col-4 img-dv nopad">
                           <?php if(!empty($sanitized_data['Images'][0]))
                              {?>
                           <img src="<?= $sanitized_data['Images'][0] ?>">
                          <?php }
                                else{?>
                            <img src="<?=$GLOBALS['CI']->template->template_images('default_hotel_img.jpg') ?>">
                         <?php } ?>
                        </div>
                        <div class="col-8 img-sumry nopad">
                            <span>Recommended Room </span>
                            <h3><?php echo ucwords($_HotelDetails['first_room_details']['room_name'])." - ".$_HotelDetails['first_room_details']['Benefits']?></h3>
                        </div>
                        <div class="cancel-policy col-md-12 nopad">
                            <ul>
                                <li class="text-dn">Cancellation charge apply</li>
                                <li>Free WiFi</li>

                            </ul>
                        </div>
                        <div class="col-md-12 nopad">
                             <div class="cheoutdv">
                        <div class="chkdatetacell">
                            <span class="chkin">Check-in</span>
                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkin'])); ?></span>
                        </div>
                        <div class="arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="chkdatetacell" style="text-align: right;">
                            <span class="chkin">Check-out</span>
                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkout'])); ?></span>
                        </div>
                        
                    </div>
                        </div>
                        <?php
                            $RoomPrice = $_HotelDetails['first_room_details']['Price']['RoomPrice'];
                            $Tax = $_HotelDetails['first_room_details']['Price']['Tax'];
                            //echo $RoomPrice;
                            $no_of_nights = $hotel_search_params['no_of_nights'];
                            $per_night_price = roundoff_number($RoomPrice / $no_of_nights);
                          
                            $night_str = 'Night';
                            if ($no_of_nights > 1) 
                            {
                                $night_str = 'Nights';
                            }
                            ?>

                        <div class="txfre col-md-12 nopad">
                            <strong><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?= roundoff_number($RoomPrice)?> </strong>+<?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?= roundoff_number($Tax)?> taxes & fees 
                        </div>
                        <div class="booknow-btn col-md-12">
                             <form method="POST" action="<?= $booking_url ?>">
                                <?php
                                    $HotelImage = "";
                                    if(!empty($sanitized_data['Images'][0]))
                                    {
                                        $HotelImage = $sanitized_data['Images'][0];
                                    }
                                    $common_params_url = '';
                                    $common_params_url .= '<input type="hidden" name="CancellationPolicy[]" value="Cancellation">'; //Balu A
                                    $common_params_url .= '<input type="hidden" name="booking_source"   value="' . $params['booking_source'] . '">';
                                    $common_params_url .= '<input type="hidden" name="search_id"        value="' . $hotel_search_params['search_id'] . '">';
                                    $common_params_url .= '<input type="hidden" name="ResultIndex"      value="' . $params['ResultIndex'] . '">';
                                    $common_params_url .= '<input type="hidden" name="RateIndex"      value="' . $_HotelDetails['first_room_details']['Room_data']['RateIndex'] . '">';
                                    $common_params_url .= '<input type="hidden" name="op"               value="block_room">';
                                    $common_params_url .= '<input type="hidden" name="GuestNationality" value="'. ISO_INDIA .'" >';
                                    $common_params_url .= '<input type="hidden" name="HotelName"        value="'.$_HotelDetails['HotelName'].'" >';
                                    $common_params_url .= '<input type="hidden" name="StarRating"       value="'.$_HotelDetails['StarRating'].'">';
                                    $common_params_url .= '<input type="hidden" name="HotelImage"       value="'.$HotelImage.'">'; //Balu A
                                    $common_params_url .= '<input type="hidden" name="HotelAddress"     value="'.$_HotelDetails['Address'].'">'; //

                                    $dynamic_params_url[] = $_HotelDetails['first_room_details']['Room_data']['RoomUniqueId'];

                                    $dynamic_params_url = serialized_data($dynamic_params_url);

                                    $temp_dynamic_params_url = '';
                                    $temp_dynamic_params_url .= '<input type="hidden" name="token" value="' . $dynamic_params_url . '">';
                                    $temp_dynamic_params_url .= '<input type="hidden" name="token_key" value="' . md5($dynamic_params_url) . '">';
                                    ?>
                                
                            <?php
                            echo $common_params_url . $temp_dynamic_params_url;
                            ?>
                            <button class="bookallbtn htlbkftsz" type="submit" id="selectroom">Book Now</button>
                        </form>
                        </div>
                    </div>
                 </div>

                <!-- New Book Now Div End -->
            <div class="col-md-4 col-sm-12 col-12 resmagfix float-end hide">
                    <?php if ($_HotelDetails['trip_rating']) : ?>
                        <div class="tridtls">
                            <div class="trirat">
                                <span class="trpratclr"><?= $_HotelDetails['trip_rating'] ?></span>
                                <?php
                                $star_rating = $_HotelDetails['trip_rating'];
                                $trip_text = '';
                                if ($star_rating == 3 || $star_rating == 3.5) {
                                    $trip_text = 'Average';
                                } elseif ($star_rating == 4) {
                                    $trip_text = 'Good';
                                } elseif ($star_rating == 4.5 || $star_rating == 5) {
                                    $trip_text = 'Excellent';
                                } elseif ($star_rating == 2 || $star_rating == 2.5) {
                                    $trip_text = 'Bad';
                                } elseif ($star_rating < 2) {
                                    $trip_text = 'Very Bad';
                                }
                                ?>
                                <span class="triexcer"><?= $trip_text ?></span>
                            </div>

                            <?php if ($_HotelDetails['trip_adv_url']) : ?>
                                <div class="triexcimg">
                                    <span class="trptrvrat">TripAdvisor Traveler Rating</span>
                                    <a href="#"><img src="<?= $_HotelDetails['trip_adv_url'] ?>"></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                    <?php if (isset($_HotelDetails['first_room_details']) && empty($_HotelDetails['first_room_details']) == false) : ?>
                        <div class="htlfull_dtls">
                            <div class="htlamtnyt">
                                <?php
                                //debug($_HotelDetails);exit;
                                //calculating room price per nights
                                $RoomPrice = $_HotelDetails['first_room_details']['Price']['RoomPrice'];
                                //echo $RoomPrice;
                                $no_of_nights = $hotel_search_params['no_of_nights'];
                                $per_night_price = roundoff_number($RoomPrice / $no_of_nights);
                                //echo "per_night_price".$per_night_price;
                                $night_str = 'Night';
                                if ($no_of_nights > 1) {
                                    $night_str = 'Nights';
                                }
                                //echo get_converted_currency_value($currency_obj->force_currency_conversion($RoomPrice));
                                ?>
                                <h2 class="amthtlrs"><strong> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong></i>&nbsp;<?= roundoff_number($per_night_price) ?><span class="pernyt">/ Per Night</span></h2>

                                <div class="stdrmac">
                                    <span class="stdnonaclt"><?= $_HotelDetails['first_room_details']['room_name'] ?></span>
                                    <!-- <span class="bedndimg"><i class="fa fa-bed" aria-hidden="true"></i>&nbsp;Double Bed</span> -->
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="htlamtnytstd">
                                <h4 class="amthtlrsstd"><i class="" aria-hidden="true"></i><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>&nbsp;<?= $RoomPrice ?><span class="pernytdet">( <?= $no_of_nights ?> <?= $night_str ?> )</span></h4>

                                <?php if ($_HotelDetails['first_rm_cancel_date']) : ?>
                                    <div class="stdrmac">
                                        <span class="stdnonacltfre">Free Cancellation</span>
                                        <span class="untdate">till <?php echo date('d M Y', strtotime($_HotelDetails['first_rm_cancel_date'])); ?></span>
                                    </div>
                                <?php else : ?>
                                    <div class="stdrmac">
                                        <span class="stdnonacltfre">Cancellation Policy</span>
                                        <span class="untdate">Non-Refundable</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                    <div class="cheoutdv">
                        <div class="chkdatetacell">
                            <span class="chkin">Check-in</span>
                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkin'])); ?></span>
                        </div>
                        <div class="chkdatetacell">
                            <span class="chkin">Check-out</span>
                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkout'])); ?></span>
                        </div>
                        <div class="chkdatetacell">
                            <span class="chkin">Room Guests</span>
                            <?php
                            $adult_count = 0;
                            $child_count = 0;
                            foreach ($hotel_search_params['adult_config'] as $a_value) {
                                $adult_count += $a_value;
                            }
                            foreach ($hotel_search_params['child_config'] as $c_value) {
                                $child_count += $c_value;
                            }
                            $adult_str = 'adult';
                            if ($adult_count > 1) {
                                $adult_str = 'adults';
                            }
                            $child_str = 'child';
                            if ($child_count > 1) {
                                $child_str = 'childrens';
                            }
                            ?>
                            <span class="chkdate"><?= $hotel_search_params['room_count'] ?> room & <?= $adult_count ?> <?= $adult_str ?> <?php if ($child_count) : ?> & <?= $child_count ?> <?= $child_str ?> <?php endif; ?></span>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <div class="htlbkbtn">
                        <form method="POST" action="<?= $booking_url ?>">
                            <?php
                            echo $common_params_url . $temp_dynamic_params_url;
                            ?>
                            <button class="bookallbtn htlbkftsz" type="submit" id="selectroom">Book Now</button>
                        </form>

                    </div>
                </div>

                <div class="col-8 nopad tab_htl_detail">

                    <div class="detailtab  shdoww">
                      
                        <ul class="nav nav-tabs responsive-tabs">

                            <!-- <li class=""><a href="#htldets" data-bs-toggle="tab">Hotel Details</a></li> -->

                            <!-- <li class="active roomstab hide" ><a href="#rooms" data-bs-toggle="tab">Rooms</a></li> -->

                            <!-- <li><a href="#facility" data-bs-toggle="tab">Facilities</a></li> -->

                            <!-- <li><a href="#htlpolicy" data-bs-toggle="tab">Hotel Policy</a></li> -->

                        </ul>

                        <div class="tab-content">

                            <!-- Hotel Detail-->

                            <div class="tab-pane" id="htldets">
                              
                            </div>


                            <div class="innertabs hide">

                                <h3 class="mobile_view_header">Description</h3>

                                <!-- <div class="htldesdv">Hotel Description</div> -->

                                <div id="hotel-additional-info" class="padinnerntb">

                                    <div class="lettrfty short-text"><?php echo $sanitized_data['Description'] ?></div>

                                    <div class="show-more">

                                        <a href="#">Show More +</a>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="sticky-nav-tabs">
                            <div class="sticky-nav-tabs-container"> 
                                <a class="sticky-nav-tab active" href="#overview">Overview</a> 
                                <a class="sticky-nav-tab roomslist" href="#rooms">Rooms</a> 
                                <a class="sticky-nav-tab" href="#facility">Facilities</a> 
                                <a class="sticky-nav-tab" href="#location">Location</a> 
                                <a class="sticky-nav-tab" href="#polices">Policies</a> 
                                <a class="sticky-nav-tab" href="#about-property">About Property</a> 
                                <a class="sticky-nav-tab" href="#reviews">Reviews</a>
                                <span class="sticky-nav-tab-slider"></span>
                            </div>
                        </div>
                                

                        <div class="spa-main">
                            <div class="spa-slide" id="overview">
                                <div class="secdv-content">
                                    <div class="tab-pane fade in active">
                                        <h3><?php echo strtoupper($sanitized_data['HotelName']) ?></h3>
                                        <div class="manu1">
                                            <div class="rating-dtlpage">
                                                <span><?= number_format(floatval($sanitized_data['StarRating']),1) ?></span>
                                                <div class="rating-div">
                                                    <?php
                                                        $star_rating = $sanitized_data['StarRating'];
                                                        $trip_text = '';
                                                        if ($star_rating == 3 || $star_rating == 3.5) {
                                                            $trip_text = 'Average';
                                                        } elseif ($star_rating == 4) {
                                                            $trip_text = 'Good';
                                                        } elseif ($star_rating == 4.5 || $star_rating == 5) {
                                                            $trip_text = 'Excellent';
                                                        } elseif ($star_rating == 2 || $star_rating == 2.5) {
                                                            $trip_text = 'Bad';
                                                        } elseif ($star_rating < 2) {
                                                            $trip_text = 'Very Bad';
                                                        }
                                                    ?>
                                                    <p><strong><?= $trip_text ?></strong></p>
                                                    <small>190 Ratings</small>
                                                </div>
                                            </div>
                                            <div class="rating-dtlpage">
                                                <span class="fas fa-map-marker-alt"></span>
                                                <div class="rating-div">
                                                    <p><?php echo $sanitized_data['Address'] ?></p>
                                                    <a class="afnt" href="#">View on Map</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="spa-slide">
                                <h3 class="mt-p">Select your room</h3>
                                <div class="col-md-10 room-typ">
                                    <div class="cheoutdv">
                                        <div class="chkdatetacell">
                                            <span class="chkin">Check-in</span>
                                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkin'])); ?></span>
                                        </div>
                                        <div class="chkdatetacell">
                                            <span class="chkin">Check-out</span>
                                            <span class="chkdate"><?= date('d M Y', strtotime($_HotelDetails['checkout'])); ?></span>
                                        </div>
                                        <div class="chkdatetacell">
                                            <span class="chkin">Room Guests</span>
                                            <?php
                                            $adult_count = 0;
                                            $child_count = 0;
                                            foreach ($hotel_search_params['adult_config'] as $a_value) {
                                                $adult_count += $a_value;
                                            }
                                            foreach ($hotel_search_params['child_config'] as $c_value) {
                                                $child_count += $c_value;
                                            }
                                            $adult_str = 'adult';
                                            if ($adult_count > 1) {
                                                $adult_str = 'adults';
                                            }
                                            $child_str = 'child';
                                            if ($child_count > 1) {
                                                $child_str = 'childrens';
                                            }
                                            ?>
                                            <span class="chkdate"><?= $hotel_search_params['room_count'] ?> room & <?= $adult_count ?> <?= $adult_str ?> <?php if ($child_count) : ?> & <?= $child_count ?> <?= $child_str ?> <?php endif; ?></span>
                                        </div>

                                    </div>
                                </div>
					
                                
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12 nopad">
                    <div class="innertabs" id="rooms">
                        <h3 class="mobile_view_header">Rooms</h3>
                        <div class="">
                            <div class="room_dropdown_wrapper">
                                <select name="" id="">
                                    <option value="">Room type</option>
                                    <option value="">1 x studio premium</option>
                                    <option value="">1 x apartment with kitchenette</option>
                                    <option value="">1 x apartment premium one bedroom</option>
                                </select>
                                <select name="" id="">
                                    <option value="">Categories</option>
                                    <option value="">Bed and Breakfast</option>
                                    <option value="">Self Catering</option>
                                </select>
                                <select name="" id="">
                                    <option value="">Cancellation Type</option>
                                    <option value="">Refundable</option>
                                    <option value="">Non-refundable</option>
                                </select>
                            </div>
                            <div id="room-list" class="room-list romlistnh short-text1">
                                
                                <?php echo $loading_image;?>
                            </div>
                            
                            <div class="show-rooms">
                                <a href="#" id="show-more-link" class="hide">Show More Rooms +</a>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide col-12 nopad" id="facility">
            
                        <div class="innertabs">
                            <h3 class="mobile_view_header">Facilities</h3>
                            <div class="padinnerntb htlfac_lity short-text1">
                                <?php
                                if (valid_array($sanitized_data['HotelFacilities']) == true) {
                                    //:p Did this for random color generation
                                    //$color_code = string_color_code('Balu A');
                                    $color_code = '#00a0e0';
                                    ?>
                                    <!-- <div class="hedinerflty">
                                        Hotel Facilities
                                    </div> -->
                                    <?php
                                    //-- List group -->
                                    if($sanitized_data['HotelFacilities']){
                                        
                                    
                                    foreach ($sanitized_data['HotelFacilities'] as $ak => $av) {?>
                                        <div class="col-4 nopad">
                                        <div class="facltyid">
                                        <span class="glyphicon glyphicon-check" style="color:<?php echo $color_code?>"></span> <?php echo $av; ?></div></div>
                                    <?php }
                                    }?>

                                    
                                <?php
                                }
                                ?>
                                <?php
                                if (valid_array($sanitized_data['Attractions']) == true) {
                                    //:p Did this for random color generation
                                    //$color_code = string_color_code('Balu A');
                                    $color_code = '#00a0e0';
                                    ?>
                                    <div class="subfty">
                                        <div class="hedinerflty">
                                            Attractions
                                        </div>
                                        <?php
                                        //-- List group -->
                                        foreach ($sanitized_data['Attractions'] as $ak => $av) {?>
                                            <div class="col-4 nopad">
                                                <div class="facltyid">
                                                    <span class="glyphicon glyphicon-check" style="color:<?php echo $color_code?>"></span> <?php echo $av['Value']; ?>
                                                </div>
                                            </div>
                                        <?php
                                        }?>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="show-faclts">
                                <a href="#" id="" class="">Show More +</a>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide" id="location">
                        <div class="innertabss">
                            <h3 class="mobile_view_header">Location :</h3>
                            <div class="padinnerntb htllctn">
                                <p><?= $sanitized_data['Description'] ?></p>
                            </div>

                            <div class="map_sec nopad">
                            <p><i class="fas fa-map-marker-alt"></i><?= $sanitized_data['Address']?></p>

                            <div class="item " id="map_viewsld">
                                <div class="map_mobile_dets">
                                    <div id="Map" class="col-md-12" style="height:363px; width:100%;max-height: 363px;">Map</div>
                                </div>
                            </div>


                            <!-- <img src="/dev/extras/system/template_list/template_v3/images/staticmap.png"> -->
                            </div>
                        </div>
                    
                    </div>
                    <div class="spa-slide" id="polices">
                        <div class="innertabs hote_plcys">
                            <h3 class="mobile_view_header">Policies :</h3>
                            <div class="padinnerntb">
                                <div class="plicy col-12">
                                    <div class="col-6 policy1">
                                        <p><i class="far fa-clock"></i> Check-in/ Check-out</p>
                                    </div>
                                    <div class="col-6 policy1">
                                    <p>- Check-in 12:00 PM</p>
                                        <p>- Check-out 12:00 PM</p>                                            
                                    </div>
                                </div>
                                <div class="plicy col-12">
                                    <div class="col-6 policy1">
                                        <p><i class="fas fa-credit-card"></i>Credit cards accepted by the hotel</p>
                                    </div>
                                    <div class="col-6 policy1">
                                    <p>- American Express, Visa, MasterCard</p>                                            
                                    </div>
                                </div>
                                <div class="plicy col-12">
                                    <div class="col-6 policy1">
                                        <p> <img src="/dev/extras/system/template_list/template_v3/images/bell-img.png">  Rates and additional costs</p>
                                    </div>
                                    <div class="col-6 policy1">
                                    <p>- extra baby : Free (On request)</p>
                                        <p>- extra child : Free (On request)</p>                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide" id="about-property">
                        <div class="innertabs hote_plcys">
                            <h3 class="mobile_view_header">About this property :</h3>
                            <div class="padinnerntb">
                            <div class="property-high">
                                    <h3>Property highlights:</h3>
                                <span><strong>HYATT BANGALORE MG ROAD </strong>provides everything that you need. Guests can connect to free in-room WiFi.</span>
                                <p>You'll also enjoy perks such as:</p>
                                <p>Free self-parking</p>
                                <p>A 24-hour front desk, concierge services and luggage storage.</p>
                            </div>
                            <div class="sec-div">
                                <h3>Room features : </h3>
                                    <p>All 68 rooms offer amenities such as free WiFi.</p>
                                    <p>More conveniences in all rooms include:</p>
                                    <p>Bathrooms with baths or showers</p>
                                    <p>Cable channels, limited housekeeping and desks</p>
                            </div>
                            <div class="thrd-dv">
                                <h3>Languages : </h3>
                                <p style="line-height: 0;"><strong>English, Hindi</strong></p>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide" id="#reviews">
                        <div class="innertabs hote_plcys">
                            <h3 class="mobile_view_header">Ratings & Review :</h3>
                            <div class="padinnerntb">
                                <div class="rev_flx">
                                    <span class="num_rvw"><?php echo number_format(floatval($star_rating),1);?></span>
                                    <span>
                                        <h5><?php echo $trip_text;?></h5>
                                        <p>371 verified reviews</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

    </div>

</div>


<!-- model galary popup -->
 <div id="myModal" class="modal glry_popup_pg">
    <span class="close cursor" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <?php 
            $image_index = 1;
            $total_gallery_images = count($sanitized_data['Images']);
            foreach ($sanitized_data['Images'] as $i_k => $i_v) 
            {
        ?>
        <div class="mySlides">
          <div class="numbertext"><?=$image_index++?>/<?= $total_gallery_images?></div>
          <img src="<?= $i_v ?>">
        </div>
    <?php 
             }
        ?>
     
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>

      <div class="caption-container">
        <p id="caption"></p>
      </div>
      <div class="gallry_slidr_wrapper">
            <div class="owl-carousel owl-theme gallry_modl_slidr">
                <?php 
                        $image_count_index = 1;
                        foreach ($sanitized_data['Images'] as $i_k => $i_v) 
                        {
                    ?>
                        <div class="column">
                        <img class="demo cursor" src="<?= $i_v ?>" style="width:100%" onclick="currentSlide(<?= $image_count_index?>)" alt="<?=$sanitized_data['HotelName']?>">
                        </div>
                    <?php 
                    $image_counts++;
                    }
                ?>
            </div>
        </div>
      
    </div>
  </div> 

  <!-- end -->

<?php
/**
 * This is used only for sending hotel room request - AJAX
 */
$hotel_room_params['ResultIndex'] = $params['ResultIndex'];
$hotel_room_params['booking_source'] = $params['booking_source'];
$hotel_room_params['search_id'] = $hotel_search_params['search_id'];
$hotel_room_params['op'] = 'get_room_details';
?>


<script type="text/javascript">
    $(function() {
      const lightbox = GLightbox({
  touchNavigation: true,
  loop: true,
  width: "90vw",
  height: "90vh"
});
});

</script>
<script>
    $(document).ready(function() {

        $('.movetop').click(function() {

            $('html, body').animate({
                scrollTop: $('.fulldowny').offset().top - 60
            }, 'slow');

        });

        $(".close_fil_box").click(function() {

            $(".coleft").hide();

            $(".resultalls").removeClass("open");

        });

        var owl = $('.gallry_modl_slidr').owlCarousel({
            itemsCustom: [
                [0, 2],
                [551, 4],
                [767, 5],
                [1000, 7],
                [1200, 7],
                [1600, 7]
            ],
            navigation: true,
            autoPlay:true,
            loop:true,
            // rewindNav:false,
        });
    });
    
</script>
<script>
class StickyNavigation {
  
  constructor() {
    this.currentId = null;
    this.currentTab = null;
    this.tabContainerHeight = 70;
    this.lastScroll = 0;
    let self = this;
    $('.sticky-nav-tab').click(function() { 
      self.onTabClick(event, $(this)); 
    });
    $(window).scroll(() => { this.onScroll(); });
    $(window).resize(() => { this.onResize(); });
  }
  
  onTabClick(event, element) {
    event.preventDefault();
    let scrollTop = $(element.attr('href')).offset().top - this.tabContainerHeight + 1;
    $('html, body').animate({ scrollTop: scrollTop }, 600);
  }
  
  onScroll() {
    this.checkHeaderPosition();
    this.findCurrentTabSelector();
    this.lastScroll = $(window).scrollTop();
  }
  
  onResize() {
    if(this.currentId) {
      this.setSliderCss();
    }
  }
  
  checkHeaderPosition() {
    const headerHeight = 75;
    if($(window).scrollTop() > headerHeight) {
      $('.spa-header').addClass('spa-header--scrolled');
    } else {
      $('.spa-header').removeClass('spa-header--scrolled');
    }
    let offset = ($('.sticky-nav-tabs').offset().top + $('.sticky-nav-tabs').height() - this.tabContainerHeight) - headerHeight;
    if($(window).scrollTop() > this.lastScroll && $(window).scrollTop() > offset) {
      $('.spa-header').addClass('spa-header--move-up');
      $('.sticky-nav-tabs-container').removeClass('sticky-nav-tabs-container--top-first');
      $('.sticky-nav-tabs-container').addClass('sticky-nav-tabs-container--top-second');
    } 
    else if($(window).scrollTop() < this.lastScroll && $(window).scrollTop() > offset) {
      $('.spa-header').removeClass('spa-header--move-up');
      $('.sticky-nav-tabs-container').removeClass('sticky-nav-tabs-container--top-second');
      $('.sticky-nav-tabs-container').addClass('sticky-nav-tabs-container--top-first');
    }
    else {
      $('.spa-header').removeClass('spa-header--move-up');
      $('.sticky-nav-tabs-container').removeClass('sticky-nav-tabs-container--top-first');
      $('.sticky-nav-tabs-container').removeClass('sticky-nav-tabs-container--top-second');
    }
  }
  
  findCurrentTabSelector(element) {
    let newCurrentId;
    let newCurrentTab;
    let self = this;
    $('.sticky-nav-tab').each(function() {
      let id = $(this).attr('href');
      let offsetTop = $(id).offset().top - self.tabContainerHeight;
      let offsetBottom = $(id).offset().top + $(id).height() - self.tabContainerHeight;
      if($(window).scrollTop() > offsetTop && $(window).scrollTop() < offsetBottom) {
        newCurrentId = id;
        newCurrentTab = $(this);
      }
    });
    if(this.currentId != newCurrentId || this.currentId === null) {
      this.currentId = newCurrentId;
      this.currentTab = newCurrentTab;
      this.setSliderCss();
    }
  }
  
  setSliderCss() {
    let width = 0;
    let left = 0;
    if(this.currentTab) {
      width = this.currentTab.css('width');
      left = this.currentTab.offset().left;
    }
    $('.sticky-nav-tab-slider').css('width', width);
    $('.sticky-nav-tab-slider').css('left', left);
  }
  
}

new StickyNavigation();


</script>

<script>
  $(document).ready(function() {
$(".sticky-nav-tab").click(function () {
    $(".sticky-nav-tab").removeClass("active");
   
    $(this).addClass("active");   
});
});

  
</script>


<script>
    function openModal() {
        document.getElementById("myModal").style.display = "block";
        document.getElementsByTagName("body")[0].style = "overflow:hidden !important";
    }
    function closeModal() {
        document.getElementById("myModal").style.display = "none";
        document.getElementsByTagName("body")[0].style = "overflow:auto !important";
    }

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
      showSlides(slideIndex += n);
    }

    function currentSlide(n) {
      showSlides(slideIndex = n);
    }

    function showSlides(n) {
      var i;
      var slides = document.getElementsByClassName("mySlides");
      var dots = document.getElementsByClassName("demo");
      var captionText = document.getElementById("caption");
      if (n > slides.length) {
        slideIndex = 1
      }
      if (n < 1) {
        slideIndex = slides.length
      }
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].className += " active";
      captionText.innerHTML = dots[slideIndex - 1].alt;
    }
  </script>

  <script>
$(document).ready(function() {

    $('.spinner, #rooms').hide();

$('.roomslist').on('click', function(e) {
    $('.spinner, #rooms').show();

	//Load hotel Room Details
	var ResultIndex = '';
	var HotelCode = '';
	var TraceId = '';
	var booking_source = '';
	var op = 'get_room_details';
	// function load_hotel_room_details()
	// {
		var _q_params = <?php echo json_encode($hotel_room_params)?>;
		if (booking_source) { _q_params.booking_source = booking_source; }
		if (ResultIndex) { _q_params.ResultIndex = ResultIndex; }
		$.post(app_base_url+"index.php/ajax/get_room_details", _q_params, function(response) {
			if (response.hasOwnProperty('status') == true && response.status == true) {
				$('.spinner').hide();
				$('#room-list').html(response.data);
				var _hotel_name = "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['HotelName']);//Hotel Name comes from hotel info response ?>";
				var _hotel_star_rating = <?php echo abs($sanitized_data['StarRating'])?>;
				var _hotel_image = "<?php echo $sanitized_data['Images'][0];?>";
				var _hotel_address = "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['Address']);?>";
				$('[name="HotelName"]').val(_hotel_name);
				$('[name="StarRating"]').val(_hotel_star_rating);
				$('[name="HotelImage"]').val(_hotel_image);//Balu A
				$('[name="HotelAddress"]').val(_hotel_address);//Balu A
			}
		});
	// }
	// load_hotel_room_details();
	});
	
});

</script>


<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');

?>
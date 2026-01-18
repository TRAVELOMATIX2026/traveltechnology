
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<?php

$booking_url = $GLOBALS['CI']->hotel_lib->booking_url($hotel_search_params['search_id']);

$mini_loading_image = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="Loading........"/></div>';

$loading_image = '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div>';

$_HotelDetails = $hotel_details['HotelInfoResult']['HotelDetails'];

$_RoomsDetail = array();
if(isset($hotel_details['HotelInfoResult']['Rooms']['HotelRoomsDetails']) && count($hotel_details['HotelInfoResult']['Rooms']['HotelRoomsDetails'])>0)
{
    $_RoomsDetail['GetHotelRoomResult']['HotelRoomsDetails'] = $hotel_details['HotelInfoResult']['Rooms']['HotelRoomsDetails'];
}
//debug($hotel_details);exit;
//debug($_HotelDetails);exit;

$sanitized_data['HotelCode'] = $_HotelDetails['HotelCode'];

$sanitized_data['HotelName'] = $_HotelDetails['HotelName'];

$sanitized_data['StarRating'] = $_HotelDetails['StarRating'];

$sanitized_data['Description'] = $_HotelDetails['Description'];

$sanitized_data['HotelFacilitiesByCategory'] = $_HotelDetails['HotelFacilitiesByCategory'];

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

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.v2.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/hotel-details.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sightseeing-slider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', 'media' => 'screen');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('lightbox-gallery.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_details_slider.js'), 'defer' => 'defer');

$reviews = number_format(rand(1111,9999));


$base_url_image = base_url() . 'index.php/hotel/image_details_cdn';



//debug($_HotelDetails);exit;

?>


<script type="text/javascript">
function customInitMap() {
    var myCenter = new google.maps.LatLng(<?= floatval($sanitized_data['Latitude']) ?>, <?= floatval($sanitized_data['Longitude']); ?>);

    var image_url = "<?php echo $GLOBALS['CI']->template->template_images() ?>";
    var hotel_name = "<?php echo $sanitized_data['HotelName'] ?>";

    var mapProp = {
        center: myCenter,
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("Map"), mapProp);

    var marker = new google.maps.Marker({
        position: myCenter,
        map: map
    });

    var infowindow = new google.maps.InfoWindow({
        content: hotel_name
    });

    marker.addListener("click", function () {
        infowindow.open(map, marker);
    });
}
</script>


<script>
window.addEventListener("load", function () {
    // Wait a little to make sure API has loaded and initMap is called
    if (typeof google !== "undefined" && google.maps) {
        customInitMap();
    } else {
        // Retry after a short delay if needed
        setTimeout(function () {
            if (typeof google !== "undefined" && google.maps) {
                customInitMap();
            }
        }, 1000);
    }
});
</script>


<div class="clearfix"></div>

<input type="hidden" id="latitude" value="<?= $sanitized_data['Latitude'] ?>">

<input type="hidden" id="longitude" value="<?= $sanitized_data['Longitude'] ?>">

<input type="hidden" id="api_base_url" value="<?= $GLOBALS['CI']->template->template_images('marker/green_hotel_map_marker.png') ?>">

<input type="hidden" id="hotel_name" value="<?php echo $sanitized_data['HotelName'] ?>">

<div class="search-result">
    <div class="container p-0">
        <div class="htl_dtls_cont htldetailspage galary_pop">
            <div class="rowfstep">
                <!-- slider -->
                <div class="col-md-12 col-sm-12 col-12 nopad">
                    <div class="col-md-12 nopad">
                        <div class="htladdet" style="background: none !important; border: none !important; box-shadow: none !important;">
                            <span><?php echo strtoupper($sanitized_data['HotelName']) ?></span>
                            <div class="marhtldet">

                                <span class="locadres"><i class="bi bi-geo-alt" aria-hidden="true"></i>&nbsp;<?php echo $sanitized_data['Address'] ?></span>

                                <ul class="htlratpz">

                                    <div class="stardetshtl"><span class="rating-no"><span class="hide" id="h-sr"><?= $sanitized_data['StarRating'] ?></span><?php echo print_star_rating($sanitized_data['StarRating']); ?></span>

                                    </div>
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
                        <?php 
                        // Count total photos
                        $total_photos = 0;
                        if(isset($sanitized_data['Images']) && valid_array($sanitized_data['Images'])) {
                            $total_photos = count($sanitized_data['Images']);
                        }
                        ?>
                        
                        <div class="slider-gallery-wrapper">
                            <div class="main-slider-section">
                                <div id="act_sldr">
                                    <div id="hotel_top" class="owl-carousel owl-theme act_carsl">
                                        <?php if(valid_array($sanitized_data['Images'])): ?>
                                            <?php foreach($sanitized_data['Images'] as $index => $image): ?>
                                            <div class="item" data-index="<?= $index ?>">
                                                <img src="<?= $image ?>" alt="<?= $sanitized_data['HotelName'] ?>" class="gallery-image">
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="item">
                                                <img src="<?= $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') ?>" alt="<?= $sanitized_data['HotelName'] ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($total_photos > 1): ?>
                            <div class="thumbnail-gallery-section">
                                <?php 
                                $thumb_count = 0;
                                $max_thumbs = 8;
                                ?>
                                <div class="thumbnail-grid">
                                    <?php foreach($sanitized_data['Images'] as $key => $image): ?>
                                        <?php if($thumb_count < $max_thumbs - 1): ?>
                                            <div class="thumb-item" data-index="<?= $key ?>">
                                                <img src="<?= $image ?>" alt="<?= $sanitized_data['HotelName'] ?>">
                                            </div>
                                            <?php $thumb_count++; ?>
                                        <?php elseif($thumb_count == $max_thumbs - 1): ?>
                                            <div class="thumb-item thumb-more" data-index="<?= $key ?>">
                                                <img src="<?= $image ?>" alt="<?= $sanitized_data['HotelName'] ?>">
                                                <div class="thumb-overlay">
                                                    <i class="bi bi-images"></i>
                                                    <span>+<?= ($total_photos - $max_thumbs + 1) ?> More</span>
                                                </div>
                                            </div>
                                            <?php $thumb_count++; ?>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <!-- slider end -->
         
        </div>

    </div>


    <div class="fulldowny">

        <div class="container mobilepad">

                        <!-- New Book Now Div End -->

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
                        <div class="col-12 img-sumry">
                            <span>Recommended Room </span>
                            <h3><?php echo ucwords($_HotelDetails['first_room_details']['room_name'])." - ".$_HotelDetails['first_room_details']['Benefits']?></h3>
                        </div>
                        <div class="cancel-policy col-md-12">
                            <ul>
                                <li class="text-dn"><a id="cancel" class="shwrmsdv cancel-policy-btn" data-hotel-code="<?=$sanitized_data['HotelCode']?>" data-key="0" data-bs-target="#roomCancelModal" data-bs-toggle="modal" data-cancellation-policy="<?=$_HotelDetails['first_room_details']['Room_data']['RoomUniqueId']?>" data-policy-code="" data-rate-key="" data-search-id="" data-room-price="<?=$RoomPrice?>" data-booking-source="<?=$params['booking_source']?>" data-tb-search-id="<?=$hotel_search_params['search_id']?>" data-non-refundable="0" data-today-cancel-date="1">View Cancellation Policy</a></li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                             <div class="cheoutdv ps-4 pe-4">
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
                            
                            $no_of_nights = $hotel_search_params['no_of_nights'];
                          
                            $night_str = 'Night';
                            if ($no_of_nights > 1) 
                            {
                                $night_str = 'Nights';
                            }
                            ?>

                        <div class="txfre col-md-12 nopad">
                            <strong><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> <?= roundoff_number($RoomPrice)?> </strong>includes taxes & fees 
                        </div>
                        <div class="booknow-btn col-md-12">
                             <form method="POST" action="<?= $booking_url ?>">
                                <?php
                                    $HotelImage = "";
                                    if(!empty($sanitized_data['Images'][0]))
                                    {
                                        $HotelImage = $sanitized_data['Images'][0];
                                    }

                                    $HotelName = preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['HotelName']);
                                    $HotelCode = $sanitized_data['HotelCode'];
                                     $StarRating   = abs($sanitized_data['StarRating']);
                                     $HotelAddress   = preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['Address']);

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


            
                <div class="col-8 nopad tab_htl_detail">

                    <div class="detailtab  shdoww">
        

                        <div class="tab-content">


                            <div class="tab-pane" id="htldets">
                              
                            </div>


                            <div class="innertabs">

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
        

                        <div class="spa-main">
                            

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
                
                </div>


                <div class="col-12 nopad">
                    <div class="innertabs" id="rooms">
                        <h3 class="mobile_view_header">Rooms</h3>
                        <div class="">
                            
                            <div id="room-list" class="room-list romlistnh short-text1">
                              <?php 

                                         if(isset($_RoomsDetail['GetHotelRoomResult']['HotelRoomsDetails']))
                                         {
                                             echo $GLOBALS['CI']->template->isolated_view('hotel/tmx/tbo_room_list',array(
                                                'currency_obj' => $currency_obj,
                                                'params' => $params,
                                                'raw_room_list' => $_RoomsDetail,
                                                'HotelName' => $HotelName,
                                                'StarRating' => $StarRating,
                                                'HotelImage' => $HotelImage,
                                                'HotelAddress' => $HotelAddress,
                                                'hotel_search_params' => $hotel_search_params
                                            ));
                                         }
                                         else{
                                            echo $loading_image;
                                         }

                                          ?>
                            </div>
                            
                            <div class="show-rooms d-none">
                                <a href="#" id="show-more-link" class="hide">Show More Rooms +</a>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide col-12 nopad" id="facility">
                        <?php
                            if (valid_array($sanitized_data['HotelFacilities']) == true) {
                                //:p Did this for random color generation
                                //$color_code = string_color_code('Balu A');
                                $color_code = '#008100';
                                ?>
                        <div class="innertabs">
                            <h3 class="mobile_view_header">Facilities</h3>
                            <div class="padinnerntb htlfac_lity short-text1">
                                
                                    <!-- <div class="hedinerflty">
                                        Hotel Facilities
                                    </div> -->
                                    <?php
                                    //-- List group -->
                                    if($sanitized_data['HotelFacilities']){
                                        // Function to get icon for facility
                                        function get_facility_icon($facility_name) {
                                            $facility_lower = strtolower($facility_name);
                                            $icon_map = array(
                                                'wifi' => array('class' => 'bi bi-wifi', 'color' => '#3b82f6'),
                                                'parking' => array('class' => 'bi bi-p-circle', 'color' => '#3b82f6'),
                                                'pool' => array('class' => 'bi bi-water', 'color' => '#3b82f6'),
                                                'gym' => array('class' => 'bi bi-activity', 'color' => '#3b82f6'),
                                                'restaurant' => array('class' => 'bi bi-egg-fried', 'color' => '#3b82f6'),
                                                'bar' => array('class' => 'bi bi-cup-straw', 'color' => '#3b82f6'),
                                                'spa' => array('class' => 'bi bi-flower1', 'color' => '#3b82f6'),
                                                'elevator' => array('class' => 'bi bi-arrow-up-square', 'color' => '#3b82f6'),
                                                'laundry' => array('class' => 'bi bi-bucket', 'color' => '#3b82f6'),
                                                'air conditioning' => array('class' => 'bi bi-snow', 'color' => '#3b82f6'),
                                                'ac' => array('class' => 'bi bi-snow', 'color' => '#3b82f6'),
                                                'beach' => array('class' => 'bi bi-water', 'color' => '#3b82f6'),
                                                'golf' => array('class' => 'bi bi-circle', 'color' => '#3b82f6'),
                                                'wheelchair' => array('class' => 'bi bi-person-wheelchair', 'color' => '#3b82f6'),
                                                'accessible' => array('class' => 'bi bi-person-wheelchair', 'color' => '#3b82f6'),
                                                'bathroom' => array('class' => 'bi bi-droplet', 'color' => '#3b82f6'),
                                                'room' => array('class' => 'bi bi-door-open', 'color' => '#3b82f6'),
                                                'hotel' => array('class' => 'bi bi-building', 'color' => '#3b82f6'),
                                                'city' => array('class' => 'bi bi-geo-alt', 'color' => '#3b82f6'),
                                                'station' => array('class' => 'bi bi-train-front', 'color' => '#3b82f6'),
                                                'bus' => array('class' => 'bi bi-bus-front', 'color' => '#3b82f6'),
                                                'metro' => array('class' => 'bi bi-train-front', 'color' => '#3b82f6'),
                                                'entertainment' => array('class' => 'bi bi-music-note-beamed', 'color' => '#3b82f6'),
                                                'card' => array('class' => 'bi bi-credit-card', 'color' => '#3b82f6'),
                                                'visa' => array('class' => 'bi bi-credit-card', 'color' => '#3b82f6'),
                                                'mastercard' => array('class' => 'bi bi-credit-card', 'color' => '#3b82f6'),
                                                'american express' => array('class' => 'bi bi-credit-card', 'color' => '#3b82f6'),
                                                'construction' => array('class' => 'bi bi-hammer', 'color' => '#3b82f6'),
                                                'floor' => array('class' => 'bi bi-layers', 'color' => '#3b82f6'),
                                                'suite' => array('class' => 'bi bi-door-open', 'color' => '#3b82f6'),
                                            );
                                            
                                            foreach ($icon_map as $key => $icon) {
                                                if (strpos($facility_lower, $key) !== false) {
                                                    return $icon;
                                                }
                                            }
                                            
                                            // Default icon
                                            return array('class' => 'bi bi-check-circle', 'color' => '#3b82f6');
                                        }
                                        
                                        // Split facilities into two columns
                                        $chunk_size = ceil(count($sanitized_data['HotelFacilities']) / 2);
                                        $facility_chunks = array_chunk($sanitized_data['HotelFacilities'], $chunk_size);
                                        $facilities_first_half = $facility_chunks[0];
                                        $facilities_second_half = isset($facility_chunks[1]) ? $facility_chunks[1] : array();
                                    ?>
                                    <div class="facilities-grid-wrapper">
                                        <div class="facilities-column facilities-col-left">
                                            <?php foreach ($facilities_first_half as $ak => $av) { 
                                                $facility_icon = get_facility_icon($av);
                                            ?>
                                                <div class="facltyid">
                                                    <i class="<?php echo $facility_icon['class']; ?>" style="color:<?php echo $facility_icon['color']; ?>"></i>
                                                    <span class="facility-name"><?php echo $av; ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="facilities-column facilities-col-right">
                                            <?php foreach ($facilities_second_half as $ak => $av) { 
                                                $facility_icon = get_facility_icon($av);
                                            ?>
                                                <div class="facltyid">
                                                    <i class="<?php echo $facility_icon['class']; ?>" style="color:<?php echo $facility_icon['color']; ?>"></i>
                                                    <span class="facility-name"><?php echo $av; ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    
                               
                                
                            </div>
                         
                        </div>
                         <?php
                                }
                                ?>


                        <?php
                                if (valid_array($sanitized_data['Attractions']) == true) 
                                {
                                    $color_code = '#008100';
                                    ?>
                        <div class="innertabs">
                            <h3 class="mobile_view_header">Attractions</h3>
                            <div class="padinnerntb htlfac_lity short-text1">
                                <?php
                                foreach ($sanitized_data['Attractions'] as $ak => $av) {?>
                                    <div class="col-4 nopad">
                                        <div class="facltyid">
                                            <?php echo $av; ?>
                                        </div>
                                    </div>
                                <?php
                                }?>
                            </div>
                        </div>
                         <?php
                                }
                                ?>
                    </div>



                    <div class="spa-slide mt-4" id="location">
                        <div class="innertabss">
                            <div class="map_sec nopad">
                            <h3 class="mobile_view_header">Location :</h3>

                            <div class="item " id="map_viewsld">
                                <div class="map_mobile_dets">
                                    <div id="Map" class="col-md-12" style="height:363px; width:100%;max-height: 363px;">Map</div>
                                </div>
                            </div>


                            <!-- <img src="/dev/extras/system/template_list/template_v3/images/staticmap.png"> -->
                            </div>
                        </div>
                    
                    </div>
                    <div class="spa-slide mt-4" id="polices">
                        <div class="innertabs hote_plcys p-0">
                            <h3 class="mobile_view_header p-0">Policies :</h3>
                            <div class="padinnerntb p-0 mt-3">
                                <div class="plicy col-12">
                                    <div class="col-6 policy-label">
                                        <p><i class="bi bi-clock"></i> Check-in/ Check-out</p>
                                    </div>
                                    <div class="col-6 policy-details">
                                        <p class="policy-item"><i class="bi bi-arrow-right-circle"></i> Check-in <?= empty($_HotelDetails['checkInTime'])==false?$_HotelDetails['checkInTime'] :'12:00 PM' ?></p>
                                        <p class="policy-item"><i class="bi bi-arrow-left-circle"></i> Check-out <?= empty($_HotelDetails['checkOutTime'])==false?$_HotelDetails['checkOutTime'] :'12:00 PM' ?></p>                                            
                                    </div>
                                </div>
                                
                                <div class="plicy col-12">
                                    <div class="col-6 policy-label">
                                        <p><i class="bi bi-credit-card"></i> Credit cards accepted by the hotel</p>
                                    </div>
                                    <div class="col-6 policy-details">
                                        <p class="policy-item"><i class="bi bi-check-circle"></i> Visa, Mastercard</p>                                            
                                    </div>
                                </div>
                                <div class="plicy col-12">
                                    <div class="col-6 policy-label">
                                        <p><i class="bi bi-cash-coin"></i> Rates and additional costs</p>
                                    </div>
                                    <div class="col-6 policy-details">
                                        <p class="policy-item"><i class="bi bi-baby"></i> Extra baby : Free (On request)</p>
                                        <p class="policy-item"><i class="bi bi-emoji-smile"></i> Extra child : Free (On request)</p>                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide mt-3" id="about-property">
                        <div class="innertabs hote_plcys">
                            <h3 class="mobile_view_header p-0">About this property :</h3>
                            <div class="padinnerntb mt-3 p-0 box-shadow-none">
                            <div class="property-high">
                                    <h3 class="mb-0">Property highlights:</h3>
                                     
                                    <div class="padinnerntb p-0">
                                        <span><?= $sanitized_data['Description'] ?></span>
                                    </div>
                            </div>
                            <?php if(isset($sanitized_data['HotelFacilitiesByCategory']) && count($sanitized_data['HotelFacilitiesByCategory'])>0){?>
                            <div class="sec-div">
                                <h3 class="mb-0">Room features : </h3>
                                <?php foreach($sanitized_data['HotelFacilitiesByCategory'] as $fac_category){
                                   echo '<p><strong>'.$fac_category['category'].':</strong> '.$fac_category['name'].'</p>';
                                     } ?>
                            </div>
                        <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="spa-slide mt-3" id="reviews">
                        <div class="innertabs hote_plcys">
                            <h3 class="mobile_view_header p-0">Ratings & Review :</h3>
                            <div class="padinnerntb mt-3 p-0 box-shadow-none">
                                <div class="rev_flx">
                                    <span class="num_rvw"><?php echo number_format(floatval($star_rating),1);?></span>
                                    <span>
                                        <h5><?php echo $trip_text;?></h5>
                                        <p><?= $reviews ?> verified reviews</p>
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
      // Check if GLightbox is available before initializing
      if (typeof GLightbox !== 'undefined') {
        const lightbox = GLightbox({
          touchNavigation: true,
          loop: true,
          width: "90vw",
          height: "90vh"
        });
      }
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

        jQuery(document.documentElement).keyup(function (event) {    
            var owl = jQuery(".owl-carousel.gallry_modl_slidr");
            if (event.keyCode == 37) {
                owl.trigger('owl.prev');
            } else if (event.keyCode == 39) {
                owl.trigger('owl.next');
            }
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

    //$('.spinner, #rooms').hide();

// $('.roomslist').on('click', function(e) {
     $('.spinner, #rooms').show();

    //Load hotel Room Details
    var ResultIndex = '';
    var HotelCode = '';
    var TraceId = '';
    var booking_source = '';
    var op = 'get_room_details';
    // function load_hotel_room_details()
    // {
    <?php
         if(empty($_RoomsDetail['GetHotelRoomResult']['HotelRoomsDetails']))
            {?>

        var _q_params = <?php echo json_encode($hotel_room_params)?>;
        if (booking_source) { _q_params.booking_source = booking_source; }
        if (ResultIndex) { _q_params.ResultIndex = ResultIndex; }
        $.post(app_base_url+"index.php/ajax/get_room_details", _q_params, function(response) {
            if (response.hasOwnProperty('status') == true && response.status == true) {
                $('.spinner').hide();
                $('#room-list').html(response.data);
                // Ensure short-text1 class is applied to show only first 5 rooms
                $('#room-list .romlistnh, #room-list .romsoutdv').addClass('short-text1').removeClass('full-text');
                // Check room count and show/hide button
                setTimeout(function() {
                    if (typeof window.checkRoomCount === 'function') {
                        window.checkRoomCount();
                    }
                }, 100);
                var _hotel_name = "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['HotelName']);//Hotel Name comes from hotel info response ?>";
                var _hotel_star_rating = <?php echo abs($sanitized_data['StarRating'])?>;
                var _hotel_image = "<?php echo $sanitized_data['Images'][0];?>";
                var _hotel_address = "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['Address']);?>";
                $('[name="HotelName"]').val(_hotel_name);
                $('[name="StarRating"]').val(_hotel_star_rating);
                $('[name="HotelImage"]').val(_hotel_image);//Balu A
                $('[name="HotelAddress"]').val(_hotel_address);//Balu A

                $.each(response.room_types_list, function (index, room) {
                    $('#unique_room_types').append(
                        $('<option>', {
                            value: room.RoomTypeName,
                            text: room.RoomTypeName
                        })
                            );
                        });

                $.each(response.room_types_category, function (index, room) {
                    $('#room_types_category').append(
                        $('<option>', {
                            value: room.RoomTypeCategory,
                            text: room.RoomTypeCategory
                        })
                            );
                        });

            }
        });
         <?php }else{?>
             $('.spinner').hide();
            <?php
         } ?>
    // }
    // load_hotel_room_details();
    //});
    
});

</script>
<script>
    $(document).on('click', '.afnt', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $('#map_viewsld').offset().top
        }, 600);
    });
</script>

<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');

?>
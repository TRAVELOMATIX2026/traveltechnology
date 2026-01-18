<style type="text/css">
     .sticky { 
            position: sticky; 
            top: 20px; 
            background-color:#fff; 
            padding: 10px; 
        } 
        .afnt{
            font-family: Poppins;
font-size: 14px;
font-weight: 400;
line-height: 21px;
text-align: left;
        }
</style>

<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_details_slider.js'), 'defer' => 'defer');

$booking_url = $GLOBALS['CI']->hotel_lib->booking_url($hotel_search_params['search_id']);





$mini_loading_image = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="Loading........"/></div>';

$loading_image = '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div>';

$_HotelDetails = $hotel_details['HotelInfoResult']['HotelDetails'];

//debug($_HotelDetails);exit;

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



if ($sanitized_data['Images']) {

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

    /*function initialize()

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

     google.maps.event.addDomListener(window, "load", initialize);*/
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js">
<div class="clearfix"></div>

<input type="hidden" id="latitude" value="<?= $sanitized_data['Latitude'] ?>">

<input type="hidden" id="longitude" value="<?= $sanitized_data['Longitude'] ?>">

<input type="hidden" id="api_base_url" value="<?= $GLOBALS['CI']->template->template_images('marker/green_hotel_map_marker.png') ?>">

<input type="hidden" id="hotel_name" value="<?php echo $sanitized_data['HotelName'] ?>">

<div class="search-result">

    <div class="container mobilepad">

        <div class="htl_dtls_cont htldetailspage">

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
                            <div id="gallery" class="photos-grid-container gallery">
                                  <div class="main-photo img-box">
                                    <a href="/dev/extras/system/template_list/template_v3/images/Rectangle 3.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 3.png" alt="image" /></a>
                                  </div>
                                  <div>
                                    <div class="sub">
                                      <div class="img-box"><a href="/dev/extras/system/template_list/template_v3/images/Rectangle 4.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 4.png" alt="image" /></a></div>
                                      <div class="img-box"><a href="/dev/extras/system/template_list/template_v3/images/Rectangle 5.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 5.png" alt="image" /></a></div>
                                      <div class="img-box"><a href="/dev/extras/system/template_list/template_v3/images/Rectangle 6.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 6.png" alt="image" /></a></div>
                                      <div id="multi-link" class="img-box">
                                        <a href="/dev/extras/system/template_list/template_v3/images/Rectangle 7.png" class="glightbox" data-glightbox="type: image">
                                          <img src="/dev/extras/system/template_list/template_v3/images/Rectangle 7.png" alt="image" />
                                          <div class="transparent-box">
                                            <div class="caption">
                                            View all photos
                                            </div>
                                          </div>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                                  <div id="more-img" class="extra-images-container hide-element">
                                    <div class="owl-carousel owl-theme" id="innr-slide">
                                        <a href="/dev/extras/system/template_list/template_v3/images/Rectangle 3.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 3.png" alt="image" /></a>
                                        <a href="/dev/extras/system/template_list/template_v3/images/Rectangle 4.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 4.png" alt="image" /></a>
                                        <a href="/dev/extras/system/template_list/template_v3/images/Rectangle 5.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 5.png" alt="image" /></a>
                                        <a href="/dev/extras/system/template_list/template_v3/images/Rectangle 6.png" class="glightbox" data-glightbox="type: image"><img src="/dev/extras/system/template_list/template_v3/images/Rectangle 6.png" alt="image" /></a>

                                    </div>
                                  </div>
                                </div>


                            <!-- ENd new gallry -->
                            <div id="hotel_top " class="owl-carousel owl-theme hide">

                                <?php if (valid_array($sanitized_data['Images']) == true) { ?>

                                    <?php foreach ($sanitized_data['Images'] as $i_k => $i_v) { ?>



                                        <div class="item" style="background: url(<?= $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') ?>) no-repeat 100% 100%;background-size: 100% 100%;">

                                            <?= '<img src="' . $base_url_image . '/' . base64_encode($sanitized_data['HotelCode']) . '/' . $i_k . '" />' ?>

                                        </div>



                                    <?php } ?>

                                <?php } else { ?>

                                    <?= '<img src="' . $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') . '" alt="' . $sanitized_data['HotelName'] . '"/>' ?>

                                <?php } ?>

                            </div>

                            <div class="item hide" id="map_viewsld">

                                <div class="map_mobile_dets">

                                    <div id="Map" class="col-md-12" style="height:363px; width:100%;max-height: 363px;">Map</div>

                                </div>

                            </div>

                            <div id="hotel_bottom" class="owl-carousel owl-theme hide">

                                <?php //debug($sanitized_data['Images']); exit;



                                if (valid_array($sanitized_data['Images']) == true) { ?>

                                    <?php foreach ($sanitized_data['Images'] as $i_k => $i_v) { ?>

                                        <?php

                                        //check if image exists in that url not

                                        //$file_header = @get_headers($i_v);

                                        $image_found = 1;

                                        // if(!$file_header  || $file_header [0] =='HTTP/1.1 404 Not Found'){

                                        //  $image_found=0;

                                        // } 

                                        ?>

                                        <?php if ($image_found) : ?>

                                            <div class="item" style="background: url(<?= $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') ?>) no-repeat 100% 100%;background-size: 100% 100%;">

                                                <?= '<img src="' . $base_url_image . '/' . base64_encode($sanitized_data['HotelCode']) . '/' . $i_k . '" />' ?>



                                            </div>

                                        <?php endif; ?>

                                    <?php } ?>

                                <?php } else { ?>

                                    <?= '<img src="' . $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') . '" alt="' . $sanitized_data['HotelName'] . '"/>' ?>

                                <?php } ?>

                                <!-- <div class="item hide">

                                  <div class="map_mobile_dets">

                                             <div id="Map" class="col-md-12" style="height:200px; width:100%">Map</div>

                                      </div> 

                               </div> -->

                            </div>

                            <div class="htlmapdtls hide" id="maphtlmapdtls">

                                <i class="fa fa-map-marker" aria-hidden="true"></i>

                            </div>

                            <div class="htlmapdtls hide" id="maphtlmapimages">

                                <i class="fas fa-images"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <!-- slider end -->

               





                <!-- <div class="col-sm-4 col-6 nopad ">

                                        <div id="carousel-example-generic" class="carousel slide" data-bs-ride="carousel">

                                                <div class="carousel-inner" role="listbox">

                                <?php

                                //loop images

                                if (valid_array($sanitized_data['Images']) == true) {

                                    $visible = 'active';

                                    foreach ($sanitized_data['Images'] as $i_k => $i_v) {

                                ?>

                                                                        <div class="item <?php echo $visible;

                                                                                            $visible = ''; ?> ">

                                                                                <img src=<?php echo $i_v ?> alt="<?php echo $i_k ?>" class="img-fluid" style="width:100%; height:200px">

                                                                                <div class="carousel-caption">

                                                                                        <p><?php echo $sanitized_data['HotelName'] ?></p>

                                                                                </div>

                                                                        </div>

                                                            <?php

                                                            }
                                                        }

                                                            ?>

                                                </div>

                                                

                                                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-bs-slide="prev">

                                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>

                                                        <span class="sr-only">Previous</span>

                                                </a>

                                                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-bs-slide="next">

                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>

                                                        <span class="sr-only">Next</span>

                                                </a>

                                        </div>

                                </div>

                                <div class="col-sm-4 col-6 nopad">

                                        <div class="innerdetspad">

                                                <div class="hoteldetsname"><?php echo strtoupper($sanitized_data['HotelName']); ?></div>

                                                <div class="stardetshtl"><span class="rating-no"><span class="hide" id="h-sr"><?= $sanitized_data['StarRating'] ?></span><?php echo print_star_rating($sanitized_data['StarRating']); ?></span></div>

                                                <div class="adrshtlo"><?php echo $sanitized_data['Address'] ?></div>

                                                <div class="butnbigs">

                                                        <a class="tonavtorum movetop">Select Rooms</a>

                                                </div>

                                        </div>

                                </div> -->

                <!-- <div class="col-sm-4 nopad map_mobile_dets">

                        <div id="Map" class="col-md-12" style="height:200px; width:100%">Map</div>

                </div>  -->

         
        </div>

    </div>

    <div class="clearfix"></div>

    <div class="fulldowny">

        <div class="container mobilepad">

            <div class="fuldownsct">
                 <!-- New Book Now Div start -->

                 <div class="col-md-4 float-end">
                    <div class="col-12 booknow-div">
                        <div class="col-4 img-dv nopad">
                           <img src="/dev/extras/system/template_list/template_v3/images/Rectangle23848.png">
                        </div>
                        <div class="col-8 img-sumry nopad">
                            <span>Recommended Room </span>
                            <h3>Deluxe Room - Holiday Selection View</h3>
                        </div>
                        <div class="cancel-policy col-md-12 nopad">
                            <ul>
                                <li>Cancellation charge apply</li>
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
                        <div class="txfre col-md-12 nopad">
                            <strong>$ 42 </strong>+$3 taxes & fees 
                        </div>
                        <div class="booknow-btn col-md-12">
                            <?php
                                $common_params_url = '';
                                $common_params_url .= '<input type="hidden" name="CancellationPolicy[]" value="Cancellation">'; //Balu A
                                $common_params_url .= '<input type="hidden" name="booking_source"   value="' . $params['booking_source'] . '">';
                                $common_params_url .= '<input type="hidden" name="search_id"        value="' . $hotel_search_params['search_id'] . '">';
                                $common_params_url .= '<input type="hidden" name="ResultIndex"      value="' . $params['ResultIndex'] . '">';
                                $common_params_url .= '<input type="hidden" name="RateIndex"      value="' . $_HotelDetails['first_room_details']['Room_data']['RateIndex'] . '">';
                                $common_params_url .= '<input type="hidden" name="op"               value="block_room">';
                                $common_params_url .= '<input type="hidden" name="GuestNationality" value="' . ISO_INDIA . '" >';
                                $common_params_url .= '<input type="hidden" name="HotelName"        value="' . $_HotelDetails['HotelName'] . '" >';
                                $common_params_url .= '<input type="hidden" name="StarRating"       value="' . $_HotelDetails['StarRating'] . '">';
                                $common_params_url .= '<input type="hidden" name="HotelImage"       value="">'; //Balu A
                                $common_params_url .= '<input type="hidden" name="HotelAddress"     value="' . $_HotelDetails['Address'] . '">'; //

                                $dynamic_params_url[] = $_HotelDetails['first_room_details']['Room_data']['RoomUniqueId'];

                                $dynamic_params_url = serialized_data($dynamic_params_url);

                                $temp_dynamic_params_url = '';
                                $temp_dynamic_params_url .= '<input type="hidden" name="token" value="' . $dynamic_params_url . '">';
                                $temp_dynamic_params_url .= '<input type="hidden" name="token_key" value="' . md5($dynamic_params_url) . '">';
                                ?>
                             <form method="POST" action="<?= $booking_url ?>">
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

                            <!-- Hotel Detail End-->
                            <div class="sticky">
                              <ul class="custm-tab">
                                    <li class="active"><a  href="#overview">Overview</a></li>
                                    <li><a href="#room1">Rooms</a></li>
                                    <li><a href="#facilities">Facilities</a></li>
                                    <li><a href="#location">Location</a></li>
                                    <li><a href="#polices">Policies</a></li>
                                    <li><a href="#about-property">About Property</a></li>
                                    <li><a href="#reviews">Reviews</a></li>

                                </ul>
                            </div>
                                  <div class="secdv-content">
                                    <div class="tab-pane fade in active">
                                      <h3>Ibis Bengaluru Layout Road - An Accor Brand</h3>
                                      <div class="manu1">
                                          <div class="rating-dtlpage">
                                              <span>8.1</span>
                                              <div class="rating-div">
                                                  <p><strong>Excellent</strong></p>
                                                  <small>190 Ratings</small>
                                              </div>
                                          </div>
                                          <div class="rating-dtlpage">
                                              <span class="fas fa-map-marker-alt"></span>
                                              <div class="rating-div">
                                                  <p>542, 60 Feet Rd, B Block, AECS Layout, Marathahalli, Bengaluru, Karnataka 560037</p>
                                                  <a class="afnt" href="#">View on Map</a>
                                              </div>
                                          </div>
                                      </div>
                                    </div>
                                    
                                  </div>
                            <!-- Rooms-->



<div class="Select-room-dv" id="room1">
<h3>Select your room</h3>

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

                   <div class="tab-room active" id="rooms00">



                                <div class="innertabs nopad">
                                    <div class="new-room-dtl">
                                        <div class="col-4 nopad border-dv">
                                           <div class="heading-dv border-radus-left">
                                                <h3>Room Types</h3>
                                           </div>
                                           
                                    </div>
                                         <div class="col-5 nopad">
                                            <div class="heading-dv">
                                                <h3>Benefits</h3>
                                            </div>
                                        </div>
                                         <div class="col-3 nopad border-dv" >
                                            <div class="heading-dv border-radus-right">
                                            <h3 style="text-align: center;">Per Night Price </h3>
                                           </div>
                                        </div>
                                        <div class="col-12 nopad">
                                        <div class="img-dscptbn-dv col-md-4 nopad">
                                            <!-- <div class="hotel-name">
                                                <h3>Deluxe Room - Holidays Selection</h3>
                                            </div> -->
                                               <div class="hotl-img">
                                                   <img src="/dev/extras/system/template_list/template_v3/images/Italy.png">
                                               <div class="dscrptn">
                                                   <h3>Family Suite With Balcony</h3>
                                                   <ul>
                                                       <li>1 Queen Bed</li>
                                                       <li>Free WiFi</li>
                                                       <li>Free self parking</li>
                                                       <li>721 ft</li>
                                                       <li>Sleeps 3</li>
                                                       
                                                   </ul>
                                               </div>
                                           </div>
                                        </div>
                                      <div class="sc-dscptn-div col-md-5 nopad">
                                            <div class="dscrpt-benifit">
                                                <h3>Room Only</h3>
                                                <span>Free cancellation before 11 July 2024</span>
                                                <ul>
                                                    <li> Pay in advance</li>
                                                </ul>
                                            </div>
                                            <div class="dscrpt-1">
                                                <h3>2 meals - Breakfast & Lunch or Dinner</h3>
                                                <ul>
                                                    <li>Bed Complimentary Breakfast is available.</li>
                                                    <li>Complimentary Lunch Or Dinner is available.</li>
                                                </ul>
                                            </div>
                                        </div>
                                      
                                            <div class="col-md-3 nopad">
                                                    <div class="price-div">
                                               <span>$29
                                                        </span>
                                                        <small>per night</small>
                                                <button class="btn">Select Room</button>
                                           </div>
                                            <div class="price-div">
                                               <span>$29
                                                        </span>
                                                        <small>per night</small>
                                                <button class="btn">Select Room</button>
                                           </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <!-- <h3 class="mobile_view_header">Rooms</h3> -->
                                    <div class="">
                                        <div id="room-list" class="room-list romlistnh short-text1">

                                            <?php echo $loading_image; ?>

                                        </div>
                                        <div class="show-rooms">

                                            <a href="#" id="show-more-link" class="hide">Show More Rooms +</a>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- Rooms End-->

                            <!-- Facilities-->

                            <div class="tab-facility" id="facilities">



                                <div class="innertabs">

                                    <h3 class="mobile_view_header">Facilities :</h3>
                                    <div class="facility-list col-md-6">
                                        <div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/mdi_pool.png">
                                            Outdoor pool
                                        </div>
                                        <div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/mdi_pool.png">
                                            Indoor pool
                                        </div>
                                        <div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/material-symbols_spa-rounded.png">
                                           Spa and card card-bodyness center
                                        </div><div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/ic_round-restaurant.png">
                                            Restaurant
                                        </div><div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/material-symbols_room-service-rounded.png">
                                            Room service
                                        </div>
                                    </div>
                                    <div class="facility-list col-md-6">
                                        <div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/maki_fitness-centre.png">
                                            Fitness center
                                        </div>
                                        <div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/ion_wine.png">
                                            Bar/Lounge
                                        </div>
                                        <div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/Wifi.png">
                                            Free Wi-Fi
                                        </div><div class="list1">
                                            <img src="/dev/extras/system/template_list/template_v3/images/Breakfast.png">
                                           Tea/coffee machine
                                        </div><div class="viw">
                                           + View All
                                        </div>
                                    </div>
                                    <div class="padinnerntb htlfac_lity hide">

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

                                            if ($sanitized_data['HotelFacilities']) {





                                                foreach ($sanitized_data['HotelFacilities'] as $ak => $av) {

                                            ?>

                                                    <div class="col-4 col-md-3 nopad">

                                                        <div class="facltyid">

                                                            <img src="/dev/extras/system/template_list/template_v3/images/Wifi.svg"><?php echo $av; ?>
                                                        </div>
                                                    </div>

                                            <?php }
                                            }

                                            ?>





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

                                                foreach ($sanitized_data['Attractions'] as $ak => $av) {

                                                ?>

                                                    <div class="col-4 nopad">
                                                        <div class="facltyid"><span class="glyphicon glyphicon-check" style="color:<?php echo $color_code ?>"></span> <?php echo $av['Value']; ?></div>
                                                    </div>

                                                <?php }

                                                ?>

                                            </div>

                                        <?php

                                        }

                                        ?>

                                    </div>

                                </div>

                            </div>

                            <!-- Facilities End-->

                            <div class="tab-locatin" id="location">
                                <div class="innertabs">
                                    <h3 class="mobile_view_header">Location :</h3>
                                    <div class="padinnerntb htllctn">
                                        <p>With a stay at Hyatt Centric MG Road , you'll be centrally located in Bengaluru, within a 5-minute drive of M.G. Road and Cubbon Park. This luxury hotel is 4.5 mi (7.2 km) from Bangalore Palace and 7.2 mi (11.5 km) from Manyata Tech Park.</p>

                                        <p style="font-weight: 500;"><i class="fas fa-map-marker-alt"></i>  1/542, 60 Feet Rd, B Block, AECS Layout, Marathahalli, Bengaluru, Karnataka 560037</p>

                                        <img src="/dev/extras/system/template_list/template_v3/images/staticmap.png">
                                    </div>
                                </div>
                            </div>

                            <!-- Policy-->

                            <div class="tab-policy" id="polices">

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

                            <!-- Policy End-->


                            <div class="tab-abut" id="about-property">
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

                            <div class="tab-rvw" id="reviews">
                                <div class="innertabs hote_plcys">
                                    <h3 class="mobile_view_header">Ratings & Review :</h3>
                                    <div class="padinnerntb">
                                        <div class="rev_flx">
                                            <span class="num_rvw">4.2</span>
                                            <span>
                                                <h5>Very good</h5>
                                                <p>371 verified reviews</p>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>

                    </div>

                </div>

                <div class="col-4 hide">

                    <div class="innertabs">

                        <?php if (valid_array($sanitized_data['HotelFacilities']) == true) { ?>

                            <div class="hedinerflty">

                                Hotel Facilities

                            </div>

                        <?php } ?>



                        <div class="padinnerntb htlfac_lity">

                            <?php

                            if (valid_array($sanitized_data['HotelFacilities']) == true) {

                                //:p Did this for random color generation

                                //$color_code = string_color_code('Balu A');

                                $color_code = '#00a0e0';

                            ?>



                                <?php

                                //-- List group -->

                                foreach ($sanitized_data['HotelFacilities'] as $ak => $av) {

                                ?>

                                    <div class="col-12 nopad">

                                        <div class="facltyid">

                                            <span class="glyphicon glyphicon-check" style="color:<?php echo $color_code ?>"></span> <?php echo $av; ?>
                                        </div>
                                    </div>

                                <?php }

                                ?>





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



                                    <?php

                                    //-- List group -->

                                    foreach ($sanitized_data['Attractions'] as $ak => $av) {

                                    ?>

                                        <div class="col-4 nopad">
                                            <div class="facltyid"><span class="glyphicon glyphicon-check" style="color:<?php echo $color_code ?>"></span> <?php echo $av['Value']; ?></div>
                                        </div>

                                    <?php }

                                    ?>

                                </div>

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

        //Load hotel Room Details

        var ResultIndex = '';

        var HotelCode = '';

        var TraceId = '';

        var booking_source = '';

        var op = 'get_room_details';
        /*
        function load_hotel_room_details()

        {

            var _q_params = <?php echo json_encode($hotel_room_params) ?>;

            if (booking_source) {

                _q_params.booking_source = booking_source;

            }

            if (ResultIndex) {

                _q_params.ResultIndex = ResultIndex;

            }

            $.post(app_base_url + "index.php/ajax/get_room_details", _q_params, function(response) {

                if (response.hasOwnProperty('status') == true && response.status == true) {

                    $('#room-list').html(response.data);

                    var _hotel_name = "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['HotelName']); //Hotel Name comes from hotel info response  
                                        ?>";

                    var _hotel_star_rating = <?php echo abs($sanitized_data['StarRating']) ?>;

                    var _hotel_image = "<?php echo $sanitized_data['Images'][0]; ?>";

                    var _hotel_address = "<?php echo preg_replace('/^\s+|\n|\r|\s+$/m', '', $sanitized_data['Address']); ?>";

                    $('[name="HotelName"]').val(_hotel_name);

                    $('[name="StarRating"]').val(_hotel_star_rating);

                    $('[name="HotelImage"]').val(_hotel_image); //Balu A

                    $('[name="HotelAddress"]').val(_hotel_address); //Balu A

                }

            });

        }

        load_hotel_room_details();*/

        $('.hotel_search_form').on('click', function(e) {

            e.preventDefault();

            $('#hotel_search_form').slideToggle(500);

        });





        $('.movetop').click(function() {

            $('html, body').animate({
                scrollTop: $('.fulldowny').offset().top - 60
            }, 'slow');

        });

        $(".close_fil_box").click(function() {

            $(".coleft").hide();

            $(".resultalls").removeClass("open");

        });





    });

</script>



<?php

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');

?>
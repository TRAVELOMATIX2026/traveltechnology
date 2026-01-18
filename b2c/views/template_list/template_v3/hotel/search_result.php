<?php
// Safely get hotel results with error handling
$all_hotels = array();
if (isset($raw_hotel_list['HotelSearchResult']['HotelResults']) && is_array($raw_hotel_list['HotelSearchResult']['HotelResults'])) {
    $all_hotels = $raw_hotel_list['HotelSearchResult']['HotelResults'];
} elseif (isset($raw_hotel_list['HotelResults']) && is_array($raw_hotel_list['HotelResults'])) {
    // Alternative structure
    $all_hotels = $raw_hotel_list['HotelResults'];
} elseif (isset($raw_hotel_list['data']['HotelResults']) && is_array($raw_hotel_list['data']['HotelResults'])) {
    // Another alternative structure
    $all_hotels = $raw_hotel_list['data']['HotelResults'];
}

// debug( $all_hotels ); 
// exit;
$all_hotels  = array_slice($all_hotels,0,count($all_hotels));

$mini_loading_image = '<div class="text-center loader-image">Please Wait</div>';
$room_count         = $search_params['data']['room_count'];
?>
<!-- Container for hotels -->
<div id="t-w-i-1">

</div> 

<script type="text/javascript">

    var hotel_core_response = hotel_core_response || {};

    var all_hotels = <?= json_encode($all_hotels); ?>;
    
    // Debug: Log if all_hotels is empty
    if (!all_hotels || all_hotels.length === 0) {
        console.warn('Warning: all_hotels is empty. Raw data structure:', <?= json_encode($raw_hotel_list); ?>);
    } else {
        console.log('all_hotels loaded successfully. Count:', all_hotels.length);
    }

    var IsRefunableAvailable = false;
    var IsNonRefunableAvailable = false;
    


    var booking_source_new = '<?= $booking_source ?>';

     if (!hotel_core_response[booking_source_new]) {
                hotel_core_response[booking_source_new] = all_hotels || [];
            }

    var merged_results = [];
    for (var source in hotel_core_response) {

        if (hotel_core_response.hasOwnProperty(source)) {

            merged_results = merged_results.concat(hotel_core_response[source]);
        }
    }


    final_merged_results = removeDuplicateHotels(merged_results);

   all_hotels = sortingFirstResponse(final_merged_results);

    var sorted_hotels = all_hotels.slice();


    // We'll load in chunks of 20:
    var chunkSize = 20;
    var hotelOffset = 0;

    function printStarRating(starRating = 0) {
    const maxStarRate = 5;
    let rating = '';
    const currentRate = parseInt(starRating, 10) || 0;

    for (let i = 1; i <= maxStarRate; i++) {
        const isActive = currentRate >= i;
        const starClass = isActive ? 'bi-star-fill' : 'bi-star';
        rating += `<i class="bi ${starClass}"></i>`;
    }

    return rating;
    }


    function removeDuplicateHotels(sortedHotels) {
        const hotelMap = new Map();

        sortedHotels.forEach(hotel => {
            const name = hotel.HotelName;
            const price = parseFloat(hotel.Price.RoomPrice);

            if (!hotelMap.has(name) || hotelMap.get(name).Price.RoomPrice > price) {
                hotelMap.set(name, hotel);
            }
        });

        return Array.from(hotelMap.values());
    }


function generateHotelHtml(hd, hd_key) {

    //console.log(hd); 

    // Convert numeric codes if needed
    var hotelCode = String(hd.HotelCode || "").replace(/[^a-zA-Z0-9]/g, "");

    // Recalculate RoomPrice, TotalRoomPrice, etc. if needed:
    var booking_source   = hd.booking_source;
    var no_of_nights     = <?= $search_params['data']['no_of_nights'] ?>;
    var room_count       = <?= json_encode($room_count) ?>;
    var currency_symbol  = <?= json_encode($currency_obj->get_currency_symbol($currency_obj->to_currency)) ?>;
    var default_img      = "<?= $GLOBALS['CI']->template->template_images('default_hotel_img.jpg') ?>";

    // Minimal price logic in JS for demonstration:
    var Roomtax = Math.round(((hd.Price?.Tax || 0) + (hd.Price?.DestinationTax || 0)) * 100)/100;
    var RoomPrice, TotalRoomPrice;
    /* if(booking_source === "<?php //echo HOTELBED_BOOKING_SOURCE ?>") {
        RoomPrice      = Math.round((hd.Price?.RoomPrice || 0) * 100)/100;
        TotalRoomPrice = Math.round(((hd.Price?.RoomPrice || 0) * no_of_nights + Roomtax)*100)/100;
        
    } else { */
        // Non-hotelbeds
        var total = (hd.Price?.RoomPrice || 0);
        RoomPrice      = Math.round((total / no_of_nights)*100)/100;
        TotalRoomPrice = Math.round((total + Roomtax)*100)/100;
       // console.log("TotalRoomPrice1 "+TotalRoomPrice);
    // }

    // Get star rating early
    var starRating = hd.StarRating || 0;
    
    // Calculate price per person
    var pricePerPerson = (TotalRoomPrice / (room_count.adults || 1)).toFixed(2);
    
    // Best Value indicator (if price is below average)
    var isBestValue = false;
    if(RoomPrice < 100){ // Adjust threshold as needed
        isBestValue = true;
    }
    
    // Popular Choice indicator (based on star rating and price)
    var isPopularChoice = false;
    if(starRating >= 4 && RoomPrice < 150){
        isPopularChoice = true;
    }
    
    // Promotion badge (if available) - prepare for image container
    var promoBadgeHtml = '';
    if(hd.HotelPromotion && parseInt(hd.HotelPromotion) > 0){
        promoBadgeHtml = '<div class="hotel-promo-badge">';
        promoBadgeHtml += '<span class="promo-badge-text"><i class="material-icons">local_offer</i> '+ hd.HotelPromotion +'% Off</span>';
        promoBadgeHtml += '</div>';
    }
    
    // Best Value Badge
    if(isBestValue){
        promoBadgeHtml += '<div class="hotel-best-value-badge">';
        promoBadgeHtml += '<span class="best-value-text"><i class="material-icons">savings</i> Best Value</span>';
        promoBadgeHtml += '</div>';
    }
    
    // Popular Choice Badge - will be placed near hotel name (not in image)
    var popularChoiceHtml = '';
    if(isPopularChoice){
        popularChoiceHtml = '<div class="hotel-popular-badge-inline">';
        popularChoiceHtml += '<span class="popular-text-inline"><i class="material-icons">trending_up</i> Popular Choice</span>';
        popularChoiceHtml += '</div>';
    }

    // Start building the HTML string
    var html = '';
    html += '<div class="rowresult r-r-i item" data-price="'+ RoomPrice +'" data-hotel-code="'+ hotelCode +'" data-key="'+ hd_key +'" data-key-val="'+ hd_key +'" data-name="'+ (hd.HotelName||"").toLowerCase() +'">';
    html += '  <div class="madgrid forhtlpopover shapcs" id="result_'+ hd_key +'" data-key="'+ hd_key +'" data-hotel-code="'+ hotelCode +'" data-access-key="'+ (hd.Latitude||"") + '_' + (hd.Longitude||"") +'">';

    // left images
    html += '    <div class="col-4 nopad listimage full_mobile" id="sectn_img">';
    html += '      <div class="imagehtldis" style="background:url('+ default_img +') no-repeat 100% 100%;">';
    html += promoBadgeHtml;
    html += '        <div id="htl_img_listing_'+ hotelCode +'" class="owl-carousel owl-theme htl_img_listing">';
    if(hd.HotelPicture && Array.isArray(hd.HotelPicture)) {
        hd.HotelPicture.forEach(function(p){
            html += '<div class="item">'
                 +  '<img alt="Hotel Image" src="'+ p.path +'" class="lazy h-img load-image hide">'
                 +  '</div>';
        });
    }else if(hd.HotelPicture){
        html += '<img alt="Hotel Image" src="'+ hd.HotelPicture +'" class="lazy h-img">';
    }
    else {
        html += '<img alt="Hotel Image" src="'+ default_img +'" class="lazy h-img">';
    }
    html += '        </div>';
    // single mob image
    html += '        <div class="mob_sngle_img">';
    if(hd.HotelPicture && hd.HotelPicture[0] && hd.HotelPicture[0].path){
        html += '<img src="'+ hd.HotelPicture[0].path +'">';
    }else if(hd.HotelPicture){
        html += '<img alt="Hotel Image" src="'+ hd.HotelPicture +'" class="lazy h-img">';
    } else {
        html += '<img src="" alt="Hotel Image" data-src="'+ default_img +'" class="lazy h-img">';
    }
    html += '        </div>';
    html += '        <a class="hotel_location" data-lat="'+ (hd.Latitude||"") +'" data-lon="'+ (hd.Longitude||"") +'"></a>';
    html += '      </div>';

    // // mob_htl_imgs
    // html += '      <div class="mob_htl_imgs">';
    // for(var i=1; i<=2; i++){
    //     if(hd.HotelPicture && hd.HotelPicture[i] && hd.HotelPicture[i].path){
    //         html += '<img src="'+ hd.HotelPicture[i].path +'">';
    //     } else {
    //         html += '<img src="" alt="Hotel Image" data-src="'+ default_img +'" class="lazy h-img">';
    //     }
    // }
    // html += '      </div>';
    html += '    </div>';

    // Right content
    html += '    <div class="col-8 listfull full_mobile" id="sectn_cntnt">';
    html += '      <div class="sidenamedesc">';
    html += '        <div class="celhtl width70">';
    html += '          <div class="innd">';
    html += '            <div class="imptpldz">';

    // shtlnamehotl
    var detailsLink = '<?= base_url() ?>hotel/hotel_details/<?= $search_id ?>'
                   + '?ResultIndex=' + encodeURIComponent(hd.ResultToken||"")
                   + '&booking_source=' + encodeURIComponent(booking_source||"")
                   + '&search_hash=' + encodeURIComponent(hd.search_hash||"")
                   + '&op=get_details';
    html += '              <div class="shtlnamehotl">';
    html += '                <a class="hoteldetailsLink" target="_blank" href="'+ detailsLink +'">';
    html += '                  <span class="h-name">'+ (hd.HotelName||"") +'</span>';
    html += '                </a>';
    if(starRating > 0){
        html += '                <div class="star-badge-inline">';
        html += '                </div>';
    }
    html += popularChoiceHtml;
    html += '              </div>';

    // h-location, near_by, etc.
    html += '              <div class="adreshotle h-location hide" data-bs-toggle="tooltip" data-placement="top" data-location="'+ (hd.HotelLocation||"").toLowerCase() +'">'+ (hd.HotelLocation||"") +'</div>';
    html += '              <div class="clearfix"></div>';
    html += '              <div class="htl_lstng_nearby"><p><span><i class="bi bi-geo-alt-fill"></i></span>';
    if(hd.near_by_location && hd.near_by_location[0]){
        var distance = (hd.near_by_location[0].distance/1000).toFixed(1);
        html += ' '+ distance +' km from '+ (hd.near_by_location[0].poiName||"");
    } else {
        html += ' '+ (hd.HotelAddress.substring(0,110)||"")+' ...';
    }
    html += '              </p></div>';
    
    // Price per person (if multiple adults)
    if(room_count.adults > 1){
        html += '              <div class="price-per-person-badge">';
        html += '                <i class="material-icons">person</i>';
        html += '                <span>'+ currency_symbol + pricePerPerson +' per person</span>';
        html += '              </div>';
    }
    html += '              <div class="clearfix"></div>';
    html += '              <span class="result_token hide">'+ encodeURIComponent(hd.ResultToken||"") +'</span>';
    // Room Type (if available)
    if(hd.RoomTypeName && hd.RoomTypeName !== "N/A" && hd.RoomTypeName !== ""){
        html += '              <div class="room-type-badge">';
        html += '                <i class="material-icons">bed</i>';
        html += '                <span>'+ hd.RoomTypeName +'</span>';
        html += '              </div>';
    }
    
    // Room Inclusions (if available)
    if(hd.Inclusion && hd.Inclusion !== "N/A" && hd.Inclusion !== ""){
        html += '              <div class="room-inclusion-badge">';
        html += '                <i class="material-icons">restaurant</i>';
        html += '                <span>'+ hd.Inclusion +'</span>';
        html += '              </div>';
    }
    
    // Free_cancel with better styling
    html += '              <div class="preclsdv">';
    if(typeof hd.Free_cancel_date !== "undefined") 
    {
        if(hd.Free_cancel_date){
            html += ' <span class="canplyto"><i class="material-icons">event_available</i> Free Cancellation till: <b>'+ hd.Free_cancel_date +'</b></span>';
            html += ' <input type="hidden" class="free_cancel" value="1" data-free-cancel="1">';
        } else {
            html += ' <input type="hidden" class="free_cancel" value="0" data-free-cancel="0">';
        }
    } else {
        html += ' <input type="hidden" class="free_cancel" value="0" data-free-cancel="0">';
    }
    html += '              </div>';
    
    // Total nights display
    if(no_of_nights > 1){
        html += '              <div class="nights-badge">';
        html += '                <i class="material-icons">nights_stay</i>';
        html += '                <span>'+ no_of_nights +' nights stay</span>';
        html += '              </div>';
    }

    // Amenities
    html += '              <div class="bothicntri">';
    html += '                <div class="mwifdiv">';
    html += '                  <ul class="htl_spr hotel_fac">';


    if (hd.HotelAmenitiesFilters && hd.HotelAmenities) {

            //console.log(typeof(hd.HotelAmenitiesFilters));
            try {
                var HotelAmenitiesList = JSON.parse(hd.HotelAmenitiesFilters);
                
                if (Array.isArray(hd.HotelAmenities)) {

                 var HotelAmenitiesAll = hd.HotelAmenities;
            }else
            {
                var HotelAmenitiesAll = JSON.parse(hd.HotelAmenities);
            }


               var AmenityNames = HotelAmenitiesAll.map(function(a) {
                    return (a || "").toString().toLowerCase().replace("’", "").trim();
                });

                HotelAmenitiesAll.forEach(function(a) {
                    var aname = (a || "").toString().replace("’", "").toLowerCase().trim();
                    html += '<li class="hide h-f" data-hotel-amenties="' + aname + '">' + aname + '</li>';
                });



               // console.log(HotelAmenitiesList);

                // Map icon_class codes to Material Icons
                var iconMapping = {
                    'WIF': 'wifi',
                    'CAP': 'local_parking',
                    'SWP': 'pool',
                    'GYM': 'fitness_center',
                    'GLF': 'golf_course',
                    'BFD': 'restaurant',
                    'MBR': 'local_bar',
                    'GRD': 'yard',
                    'LSR': 'local_laundry_service',
                    'LIF': 'elevator',
                    'SPA': 'spa',
                    'DFR': 'accessible',
                    'BAR': 'local_bar',
                    'ACC': 'ac_unit',
                    'INA': 'wifi',
                    'BARB': 'outdoor_grill'
                };

                for (var ak in HotelAmenitiesList) {
                    if (HotelAmenitiesList.hasOwnProperty(ak)) {
                        var aval = HotelAmenitiesList[ak];
                        var lname = (aval.name || "").toLowerCase().replace("'", "").trim();

                        if (aval.icon_class && AmenityNames.includes(lname)) {
                            var iconName = iconMapping[aval.icon_class] || 'star';
                            html += '<li data-cstr="_' + ak + '_" data-class="' + aval.icon_class + '" class="tooltipv" title="' + aval.name + '" data-bs-toggle="tooltip" data-placement="bottom"><i class="material-icons">' + iconName + '</i></li>';
                        }
                    }
                }

                html += '<input type="hidden" name="" class="m-f-l">';
            } catch (e) {
                console.error("Amenity processing error:", e);
            }
        } else {
        html += '<li class="hide" title="NA"></li>';
    }


    html += '                  </ul>';
    html += '                </div>';
    if(hd.trip_adv_url){
        html += ' <div class="tripad"><a href="#"><img src="'+ hd.trip_adv_url +'"></a><span>Rating '+ (hd.trip_rating||"") +'</span></div>';
    }
    html += '              </div>';
    html += '              <div class="clearfix"></div>';
    html += '            </div>'; 
    html += '            <div class="clearfix"></div>';
    // star rating
    html += '            <div class="reviws_flex">';
    html += '              <span class="reviws_flex_ratng">'+ parseFloat(starRating).toFixed(1) +'</span>';
    html += '              <span class="grid-tmp"><div class="starrtinghotl rating-no">';
    html += '                <span class="h-sr hide">'+ starRating +'</span>';
    html += printStarRating(starRating);
    html += '              </div></span>';
    html += '            </div>';
    // map
    var mapUrl = '<?= base_url() ?>hotel/map?lat='+(hd.Latitude||"")
               + '&lon=' + (hd.Longitude||"")
               + '&hn=' + encodeURIComponent(hd.HotelName||"")
               + '&sr=' + parseInt(starRating)
               + '&c='  + encodeURIComponent(hd.HotelLocation||"")
               + '&price='+ RoomPrice
               + '&img=' + encodeURIComponent(JSON.stringify(hd.HotelPicture||""));
    html += '            <div class="maprew"><div class="hoteloctnf">';
    html += '              <a href="'+ mapUrl +'" class="location-map" target="map_box_frame" data-key="'+ hd_key +'" data-hotel-code="'+ hotelCode +'" data-star-rating="'+ starRating +'" data-hotel-name="'+ (hd.HotelName||"") +'" id="location_'+ hotelCode +'_'+ hd_key +'" data-bs-toggle="tooltip" data-placement="top" data-original-title="View Map"><i class="bi bi-map"></i></a>';
    html += '            </div></div>';

    html += '          </div>'; 
    html += '        </div>'; 

    // price block
    html += '        <div class="celhtl width30"><div class="sidepricewrp">';
    if(hd.Price && hd.Price.Discount < 0){
        html += '  <div class="deal-badge-wrapper">';
        html += '    <span class="desl_ds"><i class="material-icons">flash_on</i> DEAL OF THE DAY</span>';
        html += '  </div>';
    }
    html += '          <div class="priceflights">';
    html += '            <span class="pricecls">';
    if(hd.Price && hd.Price.Discount < 0 && hd.Price.NetPrice){
        var originalPrice = parseFloat(hd.Price.NetPrice / no_of_nights).toFixed(2);
        var discountPercent = Math.round(((originalPrice - RoomPrice) / originalPrice) * 100);
        html += '              <div class="original-price-wrapper">';
        html += '                <span class="original-price">'+ currency_symbol + originalPrice +'</span>';
        html += '                <span class="discount-badge">-'+ discountPercent +'%</span>';
        html += '              </div>';
    }
    html += '              <p class="current-price">'+ currency_symbol +' <span class="price h-p">'+ RoomPrice +'</span></p>';
    html += '            </span>';
    html += '            <div class="prcstrtingt">per night</div>';
    html += '            <div class="prcstrtingt">';
    html += '              <p class="hotl_per_nyt"><i class="material-icons">info</i> includes taxes & fees</p>';
    html += '            </div>';
    // Total price for stay
    if(no_of_nights > 1){
        html += '            <div class="total-price-wrapper">';
        html += '              <div class="total-price-label">Total for '+ no_of_nights +' nights:</div>';
        html += '              <div class="total-price-amount">'+ currency_symbol + TotalRoomPrice.toFixed(2) +'</div>';
        html += '            </div>';
    }
    if(hd.Price && hd.Price.Discount == 0){
        html += ' <div class="no_deal_wrapper"><p class="no_deal"></p></div>';
    }
    html += '          </div>';
    html += '          <form id="f-sub-'+ hd_key +'" action="<?= base_url() ?>hotel/hotel_details/<?= $search_id ?>" target="_blank">';

    html += '            <input type="hidden" id="mangrid_id_'+ hd_key +'_'+ hotelCode +'" value="'+ encodeURIComponent(hd.ResultToken||"") +'" name="ResultIndex" data-key="'+ hd_key +'" data-hotel-code="'+ hotelCode +'" class="result-index">';

    html += '            <input type="hidden" value="'+ encodeURIComponent(hd.search_hash||"") +'" name="search_hash">';

    html += '            <input type="hidden" id="booking_source_'+ hd_key +'_'+ hotelCode +'" value="'+ encodeURIComponent(booking_source||"") +'" name="booking_source" data-key="'+ hd_key +'" data-hotel-code="'+ hotelCode +'" class="booking_source">';
    html += '            <input type="hidden" value="get_details" name="op" class="operation">';
    html += '            <button class="confirmBTN b-btn bookallbtn splhotltoy"  id="confirmBTN'+hotelCode+'" type="submit">Book</button>';
    html += '            <div class="clearfix"></div>';
    html += '            <div class="refnd_sts_lstng">';
    var isRefundable = false;
    var cancellationDate = '';
    if(typeof hd.IsRefundable !== "undefined"){
        if(hd.IsRefundable === true){
            isRefundable = true;
            IsRefunableAvailable = true;
            if(hd.Free_cancel_date){
                cancellationDate = hd.Free_cancel_date;
            }
        } else {
            IsNonRefunableAvailable = true;
        }
    } else if(hd.free_cancellation === true){
        isRefundable = true;
        IsRefunableAvailable = true;
        if(hd.Free_cancel_date){
            cancellationDate = hd.Free_cancel_date;
        }
    } else {
        IsNonRefunableAvailable = true;
    }
    
    if(isRefundable){
        html += ' <div class="refundable-badge-wrapper">';
        html += '   <div class="refundable-badge">';
        html += '     <i class="material-icons">verified</i>';
        html += '     <div class="refundable-content">';
        html += '       <span class="refundable-title">Refundable</span>';
        if(cancellationDate){
            html += '       <span class="refundable-date">Cancel by '+ cancellationDate +'</span>';
        } else {
            html += '       <span class="refundable-date">Free cancellation available</span>';
        }
        html += '     </div>';
        html += '   </div>';
        html += ' </div>';
    } else {
        html += ' <div class="non-refundable-badge-wrapper">';
        html += '   <div class="non-refundable-badge">';
        html += '     <i class="material-icons">block</i>';
        html += '     <div class="non-refundable-content">';
        html += '       <span class="non-refundable-title">Non-Refundable</span>';
        html += '       <span class="non-refundable-note">No cancellation allowed</span>';
        html += '     </div>';
        html += '   </div>';
        html += ' </div>';
    }
    html += '            </div>';
    html += '          </form>';
    html += '          <div class="viewhotlrmtgle hide"><button class="vwrums room-btn" type="button">View Rooms</button></div>';
    html += '        </div></div>'; // sidepricewrp

    html += '      </div>'; // /sidenamedesc
    html += '    </div>'; // /col-8
    html += '    <div class="clearfix"></div>';
    html += '    <div class="room-list" style="display:none"><div class="room-summ romlistnh"><?= $mini_loading_image ?></div></div>';

    // board_name, promotion
    if(hd.board_name){
        html += ' <span class="meals-data hide" data-meals="'+ (hd.board_name||"").toLowerCase() +'"></span>';
    }
    if(hd.HotelPromotion){
        html += ' <div class="gift-tag"><span class="offdiv deal-status" data-deal="<?= ACTIVE ?>">'+ hd.HotelPromotion +'% Off</span></div>';
    } else {
        html += ' <span class="deal-status hide" data-deal="<?= INACTIVE ?>"></span>';
    }

    // close madgrid / rowresult
    html += '  </div>';
    html += '</div>';

    //Add Owl Carousel init
    html += '<script type="text/javascript">';
    html += '$("#htl_img_listing_'+ hotelCode +'").owlCarousel({ itemsCustom:[[0,1],[551,1],[1000,1],[1200,1],[1600,1]], navigation:true, loop:true });';
    html += '</'+'script>';

    return html;
}

// function adjustImageHeights() {
//     $('.madgrid').each(function() {
//         var contentHeight = $(this).find('#sectn_cntnt').outerHeight();
//         $(this).find('#sectn_img').css('height', contentHeight);
//     });
// }

/**
 * Appends next chunk of 20 hotels from remaining_hotels into #t-w-i-1
 */
function loadNextHotels() {

  $('#total_records').text(sorted_hotels.length);
  if (sorted_hotels.length<=0) {
    console.log("No more hotels to load.");
    $('#hotel_search_result').hide();
    $('#empty_hotel_search_result').show();
    return;
  }
  
  // Hide empty state and show results when hotels are available
  $('#empty_hotel_search_result').hide();
  $('#hotel_search_result').show();
  
  var sliceEnd   = hotelOffset + chunkSize;
  if(sorted_hotels.length<sliceEnd){
    sliceEnd=sorted_hotels.length;
  }

  var nextHotels = sorted_hotels.slice(hotelOffset, sliceEnd);
  hotelOffset    = sliceEnd;

  // 1) Build up a single DOM chunk
  var htmlToAppend = '';

  nextHotels.forEach(function(hd, idx) {

    var newKey = (hotelOffset - chunkSize) + idx;

    htmlToAppend += generateHotelHtml(hd, newKey);
  });

  // 2) Convert the string to jQuery, then append it
  var $chunk = $(htmlToAppend);
  $('#t-w-i-1').append($chunk);

  // 3) Re-init Owl for only the newly appended chunk
  initOwlCarousels($chunk);

  // 4) Re-init tooltip
  $('[data-bs-toggle="tooltip"]').tooltip();

  // 5) Fix image heights
 // adjustImageHeights();

  // 6) Show images (remove "hide") or re-run lazy load
  $chunk.find('.load-image').removeClass('hide');
  if(IsRefunableAvailable == true)
  {
   $('#refundlist').show();
  }
  else{
    $('#refundlist').hide();
  }

  if(IsNonRefunableAvailable == true)
  {
    $('#Nonrefundlist').show();
  }
  else{
     $('#Nonrefundlist').hide();
  }
  
  setTimeout(function() {
    if (sorted_hotels.length <= 0) {
        console.log("No more hotels to load.");
        $('#hotel_search_result').hide();
        $('#empty_hotel_search_result').show();
        return;
    } else {
        $('#empty_hotel_search_result').hide();
        $('#hotel_search_result').show();
    }
}, 1000);
}


function sortingFirstResponse(arr) {
    // Step 1: Filter the array for star_rating = 5 and deal = 1
    const filteredArray = arr.filter(item => item.StarRating === 5 && item.Price.Discount < 0);

    // Step 2: Sort the filtered array by price in ascending order
    filteredArray.sort((a, b) => a.Price.RoomPrice - b.Price.RoomPrice);

    // Step 3: Get remaining items by removing filtered items from the original array
    const remainingArray = arr.filter(item => {
        return !filteredArray.some(filteredItem => item.HotelCode === filteredItem.HotelCode);
    });

    // Step 4: Sort the remaining array by star_rating in descending order
    remainingArray.sort((a, b) => b.StarRating - a.StarRating);

    // Step 5: Merge both arrays
    const mergedArray = [...filteredArray, ...remainingArray];

    return mergedArray;
}



function initOwlCarousels($parent) {
  // Find every Owl Carousel container under $parent
  $parent.find('.owl-carousel').owlCarousel({
    itemsCustom: [[0,1],[551,1],[1000,1],[1200,1],[1600,1]],
    navigation: true,
    loop: true
  });
}



/**
 * Checks if user scrolled near bottom; if so, load next 20
 */
function checkScrollForMore() {
    var scrollPos = $(window).scrollTop();
    var windowHeight = $(window).height();
    var docHeight = $(document).height();
    if (scrollPos + windowHeight >= docHeight - 200) {
        loadNextHotels();
    }
}

// Function to initialize hotel loading
function initializeHotelLoading() {
    // Ensure all_hotels is populated
    if (typeof all_hotels !== 'undefined' && Array.isArray(all_hotels) && all_hotels.length > 0) {
        // Reset sorted_hotels if needed
        if (typeof sorted_hotels === 'undefined' || !Array.isArray(sorted_hotels)) {
            sorted_hotels = all_hotels.slice();
        }
        // Reset hotelOffset
        if (typeof hotelOffset !== 'undefined') {
            hotelOffset = 0;
        }
        // Clear container and load hotels
        $('#t-w-i-1').empty();
        loadNextHotels();
        
        // On scroll, check
        $(window).off('scroll.hotelScroll').on('scroll.hotelScroll', function() {
            checkScrollForMore();
        });  

        // In case page is short, auto-load more
        setTimeout(checkScrollForMore, 100);
    } else {
        console.error('Cannot load hotels: all_hotels is empty or not defined');
        $('#hotel_search_result').hide();
        $('#empty_hotel_search_result').show();
    }
}

// Execute immediately if document is ready, or wait for ready
if (document.readyState === 'loading') {
    $(document).ready(function() {
        initializeHotelLoading();
    });
} else {
    // Document already ready, execute immediately
    setTimeout(initializeHotelLoading, 50);
}
</script>

<script>


$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();

    // 2) Use it as before
    // if ($(window).width() < 551) {
    //     adjustImageHeights();
    // }

    // $(window).resize(function() {
    //     if ($(window).width() < 551) {
    //         adjustImageHeights();
    //     } else {
    //         $('#sectn_img').css('height', 'auto');
    //     }
    // });
});
</script>
<strong class="currency_symbol hide"><?= $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></strong>
<script type="text/javascript">
$(document).ready(function(){
    setTimeout(function(){
        $(".load-image").removeClass('hide');
        $(".loader-image").addClass('hide');
    }, 3000);
});
</script>

<style>
    .form-control[readonly]{
        background:none;
    }
    .tabspl.forhotelonly{
            padding: 23px;
    width: 97%;
    margin: 12px 0;
    margin-left: 17px;
    border-radius: 30px;
    }
</style>
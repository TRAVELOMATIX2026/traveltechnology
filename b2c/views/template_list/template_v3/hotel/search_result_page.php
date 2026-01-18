<style type="text/css">
    .flightbutton {
    position: relative !important;
    bottom: 0 !important;
}
 #hotelDropdown {
            list-style-type: none;
            margin: 0;
            padding: 0;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
        }

        #hotelDropdown li {
            padding: 8px;
            cursor: pointer;
        }

        #hotelDropdown li:hover {
            background-color: #f1f1f1;
        }
        #modify .forhotelonly{
                margin: 20px 0 !important;
        }
        .tabrow{
            margin: 20px 0 !important;
        }
        
</style>
<script>
    var booking_source_count = <?= count($active_booking_source)?>;
</script>
 <?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_search.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/hotels-result.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR . 'jquery.jsort.0.4.min.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('jquery.nicescroll.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_search_opt.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/marker_cluster.js'), 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sweet_alert.min.js'), 'defer' => 'defer');

echo $this->template->isolated_view('share/js/lazy_loader');
?>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY&libraries=places&callback=initMap" async defer></script> -->
<script>
      var source_wise_count_obj = {}; 
    var load_hotels = function(loader, booking_source_k, offset, filters) {
        offset = offset || 0;
        var url_filters = '';
        if ($.isEmptyObject(filters) == false) {
            url_filters = '&' + ($.param({'filters': filters}));
        }
        _lazy_content = $.ajax({
            type: 'GET',
            url: app_base_url + 'ajax/hotel_list/' + offset + '?booking_source='+booking_source_k+'&search_id=<?= $hotel_search_params['search_id'] ?>&op=load' + url_filters,
            async: true,
            cache: true,
            dataType: 'json',
            success: function (res) {
                loader(res);
                $('#onwFltContainer').hide();
            }
        });
    }


    var interval_load = function (res) {
        var dui;
        var r = res;
        dui = setInterval(function () 
        {
            if (typeof (process_result_update) != "undefined" && $.isFunction(process_result_update) == true) {
                clearInterval(dui);

                process_result_update(r);

                ini_result_update(r);

                console.log('ssss');

                <?php 
                if(count($active_booking_source)>1)
                {
                    ?>
                //  call_full_ajax_list();
                <?php  
                    }
                ?>
            }
        }, 1);
    };

    function call_full_ajax_list()
    {
    
    $( "#reset_filters" ).trigger( "click" );
    
}
var booking_source_k = "";
var booking_source_arr = [];
    <?php
    foreach ($active_booking_source as $t_k => $t_v) 
    {
        //  $t_v['source_id'] = 'PTBSID0000000013';
        $active_source[] = $t_v['source_id'];
        ?>
         booking_source_k = '<?=$t_v['source_id']?>';
         booking_source_arr.push(booking_source_k);
        <?php
         //break;
        }

$active_source = json_encode($active_source);
?>

// Wait for scripts to load before calling load_hotels
var hotelLoadInitialized = false;

function initHotelLoad() {
    // Prevent multiple initializations
    if (hotelLoadInitialized) {
        return;
    }
    
    var maxAttempts = 200; // Maximum 10 seconds (200 * 50ms)
    var attempts = 0;
    
    function checkAndLoad() {
        attempts++;
        
        // Check if jQuery and required functions are available
        if (typeof jQuery === 'undefined' || typeof app_base_url === 'undefined') {
            if (attempts < maxAttempts) {
                setTimeout(checkAndLoad, 50);
            } else {
                console.error('jQuery or app_base_url not loaded after timeout');
            }
            return;
        }
        
        // Wait for hotel_search.js to load (check for process_result_update)
        if (typeof process_result_update === 'undefined') {
            if (attempts < maxAttempts) {
                setTimeout(checkAndLoad, 50);
            } else {
                console.error('process_result_update function not loaded after timeout');
            }
            return;
        }
        
        // Mark as initialized
        hotelLoadInitialized = true;
        
        // All scripts loaded, now load hotels
        <?php
        foreach ($active_booking_source as $t_k => $t_v) 
        {
            ?>
             booking_source_k = '<?=$t_v['source_id']?>';
             load_hotels(interval_load, booking_source_k);
            <?php
        }
        ?>
    }
    
    // Start checking
    checkAndLoad();
}

// Try on DOMContentLoaded first (scripts with defer should be loaded by then)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initHotelLoad, 100); // Small delay to ensure deferred scripts executed
    });
} else {
    // DOM already loaded
    setTimeout(initHotelLoad, 100);
}

// Fallback: Also try on window.load (after all resources loaded)
window.addEventListener('load', function() {
    if (!hotelLoadInitialized) {
        initHotelLoad();
    }
});

//console.log(booking_source_arr);
    
</script>

<span class="hide">
    <input type="hidden" id="pri_search_id" value='<?= $hotel_search_params['search_id'] ?>'>
    <input type="hidden" id="pri_active_source" value='<?= $active_source ?>'>
    <input type="hidden" id="pri_app_pref_currency" value='<?= $this->currency->get_currency_symbol(get_application_currency_preference()) ?>'>
    <input type="hidden" id="api_base_url" value="<?= $GLOBALS['CI']->template->template_images() ?>">
    <input type="hidden" id="api_booking_source" value="<?= $active_booking_source[0]['source_id'] ?>">
    <input type="hidden" id="default_loader" value="<?= $GLOBALS['CI']->template->template_images('image_loader.gif') ?>">
</span>
<?php
$data['result'] = $hotel_search_params;
$mini_loading_image = '<div class="text-center !d-none loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="Loading........"/></div>';
$loading_image = '<div class="text-center loader-image" style="display:none !important;"><img src="' . $GLOBALS['CI']->template->template_images('loader_v1.gif') . '" alt="Loading........"/></div>';
$template_images = $GLOBALS['CI']->template->template_images();
echo $GLOBALS['CI']->template->isolated_view('hotel/search_panel_summary');

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
?>
<input type="hidden" id="pagination_loader" value="<?= $GLOBALS['CI']->template->template_images('loader_v1.gif') ?>">
<section class="search-result hotel_search_results onlyfrflty">
<?php if (!empty($hotel_search_params['right_ads'])) { ?>

<?php foreach ($hotel_search_params['right_ads'] as $right_ad) { ?>

    <div class="vertcl_banner_right ">
 
        <img src="<?php echo $GLOBALS['CI']->template->domain_images($right_ad['image']) ?>" width="131px" height="1186px" alt="" />
        <div class="right_bannr_cntnt">
            <p><?= $right_ad['message'] ?></p>
            <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->
        </div>
    </div>
<?php } ?>
<?php } ?>
    <div class="container nopad"  id="page-parent">
        <?php //echo $GLOBALS['CI']->template->isolated_view('share/loader/hotel_result_pre_loader', $data); ?>
        <div class="resultalls">

            <div class="coleft" id="coleftid">
                <div class="htl_map">
                    <div class="map_wrapper">
                            <div id="sidemap_view" style="width: 100%;height: 150px;display: block;"></div>
                            <a onclick="showhide()" class="map_click">
                            <span>View in a Map</span>
                        </a>
                    </div>
                </div>
                <div class="flteboxwrp">
                    <!-- Filter Skeleton Loader -->
                    <div id="filter-skeleton-loader" class="fltrboxin">
                        <div class="celsrch">
                            
                            <div class="rangebox">
                                <div class="in">
                                    <div class="price_slider1" style="padding-top: var(--spacing-3);">
                                        <p class="level skeleton-loader" style="height: 28px; width: 100%; margin-bottom: var(--spacing-3);"></p>
                                        <div class="skeleton-loader" style="height: 6px; width: 100%; border-radius: 8px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox">
                                <div class="collapse in">
                                    <div class="boxins marret stopCountWrapper" style="display: flex; gap: var(--spacing-2); flex-wrap: wrap; padding-top: var(--spacing-3);">
                                        <a class="stopone toglefil stop-wrapper" style="pointer-events: none; margin: 0; flex: 1 1 calc(33.333% - 8px); min-width: 0;">
                                            <div class="starin skeleton-loader" style="height: 60px; width: 100%; border-radius: var(--border-radius-md);"></div>
                                        </a>
                                        <a class="stopone toglefil stop-wrapper" style="pointer-events: none; margin: 0; flex: 1 1 calc(33.333% - 8px); min-width: 0;">
                                            <div class="starin skeleton-loader" style="height: 60px; width: 100%; border-radius: var(--border-radius-md);"></div>
                                        </a>
                                        <a class="stopone toglefil stop-wrapper" style="pointer-events: none; margin: 0; flex: 1 1 calc(33.333% - 8px); min-width: 0;">
                                            <div class="starin skeleton-loader" style="height: 60px; width: 100%; border-radius: var(--border-radius-md);"></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>

                            <div class="rangebox">
                                <div class="collapse in">
                                    <div class="boxins" style="padding-top: var(--spacing-3);">
                                        <ul class="locationul" style="margin: 0; padding: 0; list-style: none;">
                                            <li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
                                                <div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>
                                                <label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <div class="septor"></div>
                            <div class="rangebox">
                                <div class="collapse in">
                                    <div class="boxins" style="padding-top: var(--spacing-3);">
                                        <ul class="locationul" style="margin: 0; padding: 0; list-style: none;">
                                            <li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
                                                <div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>
                                                <label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>
                                            </li>
                                            <li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
                                                <div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>
                                                <label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>
                                            </li>
                                            <li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
                                                <div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>
                                                <label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>
                                            </li>
                                            <li style="margin-bottom: 0; display: flex; align-items: center; gap: var(--spacing-2);">
                                                <div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0;"></div>
                                                <label class="lbllbl skeleton-loader" style="height: 16px; width: 130px; flex: 1;"></label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Refine Search Filters Start -->
                    <div class="fltrboxin" id="filter-content" style="display: none;">
                        <div class="celsrch">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="hdr_flx">
                                    <a class="float-end" id="reset_filters">RESET ALL</a>
                                </div>
                                <div class="filtersho">
                                    <div class="avlhtls"><strong id="total_records"> </strong> <span id="hotels_text">hotels</span> found
                                    </div>
                                    <span class="close_fil_box"><i class="fas fa-times"></i></span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="septor"></div>
                            <div class="rangebox">
                                <button data-bs-target="#collapseOne" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Price
                                </button>
                                <div id="collapseOne" class="in pt-0">
                                    <?php echo $mini_loading_image ?>
                                    <div id="price-refine" class="in pt-0">
                                        <div class="price_slider1">
                                            <div id="core_min_max_slider_values" class="hide">
                                                <input type="hidden" id="core_minimum_range_value" value="">
                                                <input type="hidden" id="core_maximum_range_value" value="">
                                            </div>
                                            <p id="hotel-price" class="level"></p>
                                            <div id="price-range" class="" aria-disabled="false"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rangebox hide">
                                <button data-bs-target="#collapseDeal" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Deal of the day
                                </button>
                                <div id="collapseDeal" class="collapse in">
                                    <div class="boxins">
                                        <ul class="locationul">
                                            <li>
                                                <div class="squaredThree">
                                                    <input type="checkbox" id="deal-status-filter" value="filter" class="deal-status-filter" name="deal">
                                                    <label for="deal-status-filter"></label>
                                                </div>
                                                <label class="lbllbl" for="deal-status-filter">All Deals</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox">
                                <button data-bs-target="#collapseHotelName" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Hotel Name
                                </button>
                                <div id="collapseHotelName" class="collapse in">
                                        <div class="boxins p-0">
                                                <input type="text" class="srchhtl" placeholder="Search hotels..." id="hotel-name" onkeyup="searchHotel()" />
                                                <ul id="hotelDropdown"></ul>
                                        </div>
                                </div>
                            </div>
                            <div class="rangebox hide">
                                <button data-bs-target="#collapseTen" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Property type
                                </button>
                                <div id="collapseTen" class="collapse in">
                                    <div class="boxins">
                                        <ul class="locationul" id="hotel-property-wrapper">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="rangebox hide">
                                <button data-bs-target="#collapseNive" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Meal Plans
                                </button>
                                <div id="collapseNive" class="collapse in">
                                    <div class="boxins">
                                        <ul class="locationul" id="hotel-meals-wrapper">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox">
                                <button data-bs-target="#collapseTwo" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Star Categories
                                </button>
                                <div id="collapseTwo" class="collapse in">
                                    <div class="boxins marret" id="starCountWrapper">
                                        <a class="starone toglefil star-wrapper">
                                            <input class="hidecheck star-filter" type="checkbox" value="1">
                                            <div class="starin">
                                                <span class="rststrne">1</span> 
                                                <span class="starfa"></span>
                                            </div>
                                        </a>
                                        <a class="starone toglefil star-wrapper">
                                            <input class="hidecheck star-filter" type="checkbox" value="2">
                                            <div class="starin">
                                                <span class="rststrne">2</span>
                                                <span class="starfa"></span>
                                            </div>
                                        </a>
                                        <a class="starone toglefil star-wrapper">
                                            <input class="hidecheck star-filter" type="checkbox" value="3">
                                            <div class="starin">
                                                <span class="rststrne">3</span>
                                                <span class="starfa"></span>
                                            </div>
                                        </a>
                                        <a class="starone toglefil star-wrapper">
                                            <input class="hidecheck star-filter" type="checkbox" value="4">
                                            <div class="starin">
                                                <span class="rststrne">4</span>
                                                <span class="starfa"></span>
                                            </div>
                                        </a>
                                        <a class="starone toglefil star-wrapper">
                                            <input class="hidecheck star-filter" type="checkbox" value="5">
                                            <div class="starin">
                                                <span class="rststrne">5</span>
                                                <span class="starfa"></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox">
                                <button data-bs-target="#collapseSix" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Amenities
                                </button>
                                <div id="collapseSix" class="collapse in amenitie">
                                    <div class="boxins">
                                        <ul class="locationul" id="hotel-amenitie-wrapper">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox">
                                <button data-bs-target="#collapseFour" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Location
                                </button>
                                <div id="collapseFour" class="collapse in">
                                    <div class="boxins">
                                        <ul class="locationul" id="hotel-location-wrapper">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox" id="free_cancel_div">
                                <button data-bs-target="#collapseSeven" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Cancellation
                                </button>
                                <div id="collapseSeven" class="collapse in">
                                    <div class="boxins">
                                        <ul class="locationul">
                                            <li id="refundlist">
                                                <div class="squaredThree">
                                                    <input type="checkbox" id="refund" value="refundable" class="freecancel-hotels-view" name="free_cancel[]">
                                                    <label for="refund"></label>
                                                </div>
                                                <label class="lbllbl" for="refund">Refundable</label>
                                            </li>
                                            <li id="Nonrefundlist">
                                                <div class="squaredThree">
                                                    <input type="checkbox" id="nonrefund" value="nonrefundable" class="freecancel-hotels-view" name="free_cancel[]">
                                                    <label for="nonrefund"></label>
                                                </div>
                                                <label class="lbllbl" for="nonrefund">Non-Refundable</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="septor"></div>
                            <div class="rangebox hide">
                                <button data-bs-target="#collapseFive" data-bs-toggle="collapse" class="collapsebtn" type="button">
                                Accessbility
                                </button>
                                <div id="collapseFive" class="collapse in">
                                    <div class="boxins">
                                        <ul class="locationul" id="hotel-accessbility-wrapper">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Refine Search Filters End -->

			<?php if (!empty($hotel_search_params['left_ads'])) { ?>

                    <?php foreach ($hotel_search_params['left_ads'] as $left_ad) { ?>

                        <div class="vertcl_banner_left ">

                            <img src="<?php echo $GLOBALS['CI']->template->domain_images($left_ad['image']) ?>" width="131px" height="1186px" alt="" />
                            <div class="left_bannr_cntnt">
                                <p><?= $left_ad['message'] ?></p>
                                <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->
                            </div>
                        </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>

            <div class="colrit">
                <div class="insidebosc">
                    <div class="resultall">
                        <div class="filt_map">
                            <div class="filter_tab"><i class="fal fa-filter"></i></div>
                        </div>
                        
                        <div class="vluendsort">
                            
                            <div class="col-12 mobile_width nopad htl_top_right">
                                <div class="filterforallnty" id="top-sort-list-wrapper">
                                    <div class="topmistyhtl" id="top-sort-list-1">
                                        <div class="col-12 nopad">
                                            <div class="insidemyt">
                                              

                                                <div class="dropdown select_sort">
                                                    <button id="dLabel" type="button" class="select_sort_btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="material-icons sort-icon">sort</i>
                                                        <span class="sort_by_text">Sort by: Recommended</span>
                                                        <i class="material-icons chevron-icon">keyboard_arrow_down</i>
                                                    </button>
                                                    <ul class="dropdown-menu sortul" aria-labelledby="dLabel">
                                                        <li class="sortli threonly">
                                                            <a class="sorta star-h-2-l recommended des active dropdown-item" href="javascript:void(0);" data-order="desc">
                                                                <i class="material-icons sort-option-icon">star</i>
                                                                <span><strong>Recommended</strong></span>
                                                                <i class="material-icons active-check">check_circle</i>
                                                            </a>
                                                        </li>

                                                        <li class="sortli threonly" data-sort="p">
                                                            <a class="sorta price-l-2-h asc dropdown-item" href="javascript:void(0);" data-order="asc">
                                                                <i class="material-icons sort-option-icon">arrow_upward</i>
                                                                <span><strong>Price: Low to High</strong></span>
                                                            </a>
                                                        </li>
                                                        <li class="sortli threonly" data-sort="p">
                                                            <a class="sorta price-h-2-l des dropdown-item" href="javascript:void(0);" data-order="desc">
                                                                <i class="material-icons sort-option-icon">arrow_downward</i>
                                                                <span><strong>Price: High to Low</strong></span>
                                                            </a>
                                                        </li>

                                                        <li class="sortli threonly" data-sort="hn">
                                                            <a class="sorta name-l-2-h asc dropdown-item" href="javascript:void(0);" data-order="asc">
                                                                <i class="material-icons sort-option-icon">trending_up</i>
                                                                <span><strong>Popular: Low to High</strong></span>
                                                            </a>
                                                        </li>
                                                        <li class="sortli threonly" data-sort="hn">
                                                            <a class="sorta name-h-2-l des dropdown-item" href="javascript:void(0);" data-order="desc">
                                                                <i class="material-icons sort-option-icon">trending_down</i>
                                                                <span><strong>Popular: High to Low</strong></span>
                                                            </a>
                                                        </li>

                                                        <li class="sortli threonly" data-sort="sr">
                                                            <a class="sorta star-l-2-h asc dropdown-item" href="javascript:void(0);" data-order="asc">
                                                                <i class="material-icons sort-option-icon">star_border</i>
                                                                <span><strong>User Ratings: Low to High</strong></span>
                                                            </a>
                                                        </li>
                                                        <li class="sortli threonly" data-sort="sr">
                                                            <a class="sorta star-h-2-l des dropdown-item" href="javascript:void(0);" data-order="desc">
                                                                <i class="material-icons sort-option-icon">star</i>
                                                                <span><strong>User Ratings: High to Low</strong></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="mapviw noviews">
                                    <div class="view-toggle-group">
                                        <button type="button" class="view-toggle-btn list_click active" id="list_clickid" onclick="showhide(event)" aria-label="List View">
                                            <i class="material-icons">view_list</i>
                                            <span class="view-toggle-label">List</span>
                                        </button>
                                        <button type="button" class="view-toggle-btn map_click map_click1" id="map_clickid" onclick="showhide(event)" aria-label="Map View">
                                            <i class="material-icons">map</i>
                                            <span class="view-toggle-label">Map</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-3 mobile_none nopad">
                                
                            </div> -->
                        </div>

                    </div>		
                    <div class="allresult">
                            
                        <!-- Hotel List Skeleton Loader -->
                        <div id="hotel-list-skeleton-loader" class="hotel-list-skeleton">
                            <?php for($i = 0; $i < 5; $i++) { ?>
                            <div class="skeleton-hotel-card">
                                <div class="skeleton-image"></div>
                                <div class="skeleton-content">
                                    <div class="skeleton-header">
                                        <div class="skeleton-title"></div>
                                        <div class="skeleton-badge"></div>
                                    </div>
                                    <div class="skeleton-location"></div>
                                    <div class="skeleton-badges">
                                        <div class="skeleton-small-badge"></div>
                                        <div class="skeleton-small-badge"></div>
                                    </div>
                                    <div class="skeleton-amenities">
                                        <?php for($j = 0; $j < 5; $j++) { ?>
                                        <div class="skeleton-amenity-icon"></div>
                                        <?php } ?>
                                    </div>
                                    <div class="skeleton-rating">
                                        <div class="skeleton-rating-number"></div>
                                        <div class="skeleton-stars"></div>
                                    </div>
                                </div>
                                <div class="skeleton-price">
                                    <div class="skeleton-price-amount"></div>
                                    <div class="skeleton-price-label"></div>
                                    <div class="skeleton-book-button"></div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div id="hotel_search_result" class="hotel-search-result-panel result_srch_htl owl-carousel" style="display:none;">

                        </div>
                        
                        <!-- Main Map Container -->
                        <div id="map" class="hotel_map" style="display:none; border-radius: var(--border-radius-lg); overflow: hidden;"></div>
                        
                        <div id="npl_img" class="text-center hide" loaded="true">
                            <?= '<img src="' . $GLOBALS['CI']->template->template_images('loader_v1.gif') . '" alt="Please Wait" />' ?>
                            <!-- <div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div> -->
                        </div>
                        <div id="empty_hotel_search_result" class="empty-state-container" style="display:none">
                            <div class="empty-state-content">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="100" cy="100" r="80" fill="url(#emptyHotelGradient)" opacity="0.1"/>
                                        <!-- Hotel Building -->
                                        <rect x="60" y="80" width="80" height="70" rx="4" fill="url(#emptyHotelGradient)" opacity="0.2"/>
                                        <rect x="60" y="80" width="80" height="70" rx="4" stroke="url(#emptyHotelGradient)" stroke-width="2" opacity="0.4"/>
                                        
                                        <!-- Hotel Roof -->
                                        <path d="M50 80 L100 50 L150 80 Z" fill="url(#emptyHotelGradient)" opacity="0.3"/>
                                        <path d="M50 80 L100 50 L150 80" stroke="url(#emptyHotelGradient)" stroke-width="2.5" stroke-linecap="round" opacity="0.5"/>
                                        
                                        <!-- Windows -->
                                        <rect x="70" y="95" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        <rect x="90" y="95" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        <rect x="110" y="95" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        <rect x="130" y="95" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        
                                        <rect x="70" y="115" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        <rect x="90" y="115" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        <rect x="110" y="115" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        <rect x="130" y="115" width="12" height="12" rx="2" fill="url(#emptyHotelGradient)" opacity="0.6"/>
                                        
                                        <!-- Door -->
                                        <rect x="88" y="130" width="24" height="20" rx="2" fill="url(#emptyHotelGradient)" opacity="0.7"/>
                                        <circle cx="108" cy="140" r="2" fill="url(#emptyHotelGradient)" opacity="0.8"/>
                                        
                                        <!-- Decorative Elements -->
                                        <circle cx="100" cy="45" r="4" fill="url(#emptyHotelGradient)" opacity="0.5"/>
                                        <path d="M60 40L50 50M140 40L150 50M60 160L50 150M140 160L150 150" stroke="url(#emptyHotelGradient)" stroke-width="2" stroke-linecap="round" opacity="0.3"/>
                                        
                                        <defs>
                                            <linearGradient id="emptyHotelGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" style="stop-color:var(--color-primary-primary);stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:var(--color-primary-obsidian);stop-opacity:1" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <div class="empty-state-title">No Hotels Found</div>
                                <div class="empty-state-message">
                                    <p>We couldn't find any hotels matching your search criteria.</p>
                                    <p class="empty-state-suggestions">Try adjusting your filters or search for a different location.</p>
                                </div>
                                <div class="empty-state-actions">
                                    <button type="button" class="btn-reset-filters" onclick="$('#reset_filters').click();">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr class="hr-10">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="empty-search-result" class="jumbotron container" style="display:none">
    <h1><i class="fa fa-bed"></i> Oops!</h1>
    <p>No hotels were found in this location today.</p>
    <p>
        Search results change daily based on availability.If you have an urgent requirement, please get in touch with our call center using the contact details mentioned on the home page. They will assist you to the best of their ability.
    </p>
</div>
</div>

<!-- Mobile Filter FAB Button and Overlay -->
<div class="filter-overlay" aria-hidden="true"></div>
<button class="filter-fab-btn d-none" title="Open Filters" aria-label="Open Filters">
	<i class="material-icons">tune</i>
	<span class="filter-count hide" aria-hidden="true">0</span>
</button>
<!-- Mobile filter button/overlay JS moved to extras/.../javascript/page_resource/hotel_search.js to avoid inline scripts -->

</section>

<div class="modal fade bs-example-modal-lg" id="hotel-img-gal-box-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id="myModalLabel">Hotel Images</h5>
                <div class="htlimgprz">
                    <strong id="modal-price-symbol"></strong>&nbsp;
                    <span class="h-p" id="modal-price"></span>
                    <a href="" class="confirmBTN b-btn bookallbtn splhotltoy" id="modal-submit">Book</a>
                 
                </div>
                <ul class="htmimgstr">					
                </ul>
                <div class="imghtltrpadv hide">
                    <img src="" id="trip_adv_img">
                </div>
            </div>
            <div class="modal-body">
                <div class="spinner" id="spinnerload">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                    <div class="bounce4"></div>
                    <div class="bounce5"></div>
                </div>
                <div id="hotel-images" class="hotel-images">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="map-box-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabelMap"></h4>
                <ul class="htmimgstr">					
                </ul>

            </div>
            <div class="modal-body">			
                <iframe src="" id="map-box-frame" name="map_box_frame" style="height: 500px;width: 850px;">
                </iframe>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Add minus icon for collapse element which is open by default
        $(".collapse.in").each(function () {
            $(this).siblings("#accordion .card-header").find(".glyphicon").addClass("glyphicon-chevron-up").removeClass("glyphicon-chevron-down");
        });

        // Toggle plus minus icon on show hide of collapse element
        $("#accordion .collapse").on('show.bs.collapse', function () {
            $(this).parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
        }).on('hide.bs.collapse', function () {
            $(this).parent().find(".glyphicon").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
        });
    });
</script>

<?php
echo $this->template->isolated_view('share/media/hotel_search');
?>
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY" type="text/javascript"></script> -->

<script>
function searchHotel() {
    const inputEl = document.getElementById('hotel-name');
    const input = inputEl.value.toLowerCase();
    const dropdown = document.getElementById('hotelDropdown');

    dropdown.innerHTML = '';

    if (input.length === 0) {
        dropdown.style.display = 'none';
        sorted_hotels = all_hotels.slice();
        $('#t-w-i-1').empty();
        chunkSize = 20;
        hotelOffset = 0;
        loadNextHotels();
        return;
    }

    // Filter hotels
    sorted_hotels = all_hotels.filter(hotel =>
        hotel.HotelName.toLowerCase().includes(input)
    );

    if (sorted_hotels.length > 0) {
        dropdown.style.display = 'block';

        sorted_hotels.forEach(hotel => {
            let listItem = document.createElement('li');
            listItem.textContent = hotel.HotelName;

            listItem.onclick = function () {
                // ✅ SET VALUE INTO TEXTBOX
                inputEl.value = hotel.HotelName;

                // ✅ HIDE DROPDOWN
                dropdown.style.display = 'none';

                // OPTIONAL: trigger reload based on selected hotel
                sorted_hotels = [hotel];
                $('#t-w-i-1').empty();
                hotelOffset = 0;
                loadNextHotels();
            };

            dropdown.appendChild(listItem);
        });
    } else {
        dropdown.style.display = 'none';
    }
}

</script>

<script type="text/javascript">
    $(document).ready(function () {
        if ($(window).width() < 550) {
            //alert("hiiiii");
            $(document).on("click", ".madgrid", function () {
                var result_key = $(this).data('key');
                var hotel_code = $(this).data('hotel-code');
                //var result_key = $(".result-index").data('key');

                //var hotel_code = $(".result-index").data('hotel-code');

                var result_token = $("#mangrid_id_" + result_key + '_' + hotel_code).val();
                var booking_source = $("#booking_source_" + result_key + '_' + hotel_code).val();
                var operation_details = $(".operation").val();
                window.location = '<?php echo base_url() . 'hotel/hotel_details/' . ($hotel_search_params['search_id']) ?>' + '?ResultIndex=' + result_token + '&booking_source=' + booking_source + '&op=' + operation_details + '';
            });


        }
        $(".close_fil_box").click(function () {
            $(".coleft").hide();
            $(".resultalls").removeClass("open");
        });

        // Initialize Bootstrap dropdown
        var dropdownElement = document.getElementById('dLabel');
        if (dropdownElement && typeof bootstrap !== 'undefined') {
            var dropdown = new bootstrap.Dropdown(dropdownElement);
        }
        
        // Update dropdown button text when sort option is selected
        $(document).on('click', ".dropdown-menu.sortul a", function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var selectedText = $(this).find('span strong').text() || $(this).find('strong').text() || $(this).text().trim();
            $(".select_sort .sort_by_text").text('Sort by: ' + selectedText);
            
            // Update active state
            $('.dropdown-menu.sortul a').removeClass('active');
            $(this).addClass('active');
            
            // Close dropdown
            if (typeof bootstrap !== 'undefined' && dropdownElement) {
                var dropdownInstance = bootstrap.Dropdown.getInstance(dropdownElement);
                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
            }
        });

    });
    // functions that return icons.  Make or find your own markers.

</script>
<script type="text/javascript">
    $(document).ready(function () {
       
        window.setInterval(function () {
            swal({
                text: 'Your session has expired,Please search again!!!!',
                type: 'info'

            }).then(function () {
                //window.location.href = "<?php echo base_url(); ?>";
                window.location.reload(true);
            });
            //alert('Session Expired!!!');
            //window.location.href="<?php //echo base_url(); ?>";
        }, 900000);
        
        // Initialize star icons for star category buttons
        function initStarIcons() {
            $('#starCountWrapper .starone').each(function() {
                var $starBtn = $(this);
                var rating = parseInt($starBtn.find('input.star-filter').val()) || 0;
                var $starfa = $starBtn.find('.starfa');
                
                // Clear existing content
                $starfa.html('');
                
                // Add Bootstrap Icons stars based on rating (1-5 stars)
                for (var i = 0; i < rating; i++) {
                    $starfa.append('<i class="bi bi-star-fill"></i>');
                }
            });
        }
        
        // Initialize on page load
        setTimeout(function() {
            initStarIcons();
        }, 100);
        
        // Re-initialize when content changes
        var observer = new MutationObserver(function(mutations) {
            setTimeout(function() {
                initStarIcons();
            }, 50);
        });
        
        var starWrapper = document.getElementById('starCountWrapper');
        if (starWrapper) {
            observer.observe(starWrapper, { childList: true, subtree: true });
        }
    });

</script>
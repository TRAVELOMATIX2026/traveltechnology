
<?php
$default_active_tab = $default_view;
$module = $this->uri->segment(1);
$h1_content = $module . 's';

function set_default_active_tab($module_name, &$default_active_tab)
{
  if (empty($default_active_tab) == true || $module_name == $default_active_tab) {
    if (empty($default_active_tab) == true) {
      $default_active_tab = $module_name; // Set default module as current active module
    }
    return 'active';
  }
  return ''; // Return empty string if not active
}

Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
// Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('materialize.min.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/hero-slider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/home_style.css'), 'media' => 'screen');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('hero-slider.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('materialize.min.js'), 'defer' => 'defer');
?>


<style type="text/css">
  .searcharea {
    float: left;
    width: 100%;
    padding: 0px 0px 0 0px;
    position: relative;
  }

</style>

<!-- Hero Slider Section -->
<div class="hero-slider-wrapper">
  <div class="hero-slider" id="heroSlider">
    <?php if (!empty($this->slideImageJson)): ?>
      <?php foreach ($this->slideImageJson as $index => $slide): ?>
        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $slide['image']; ?>');">
          <div class="hero-overlay"></div>
          <?php if (!empty($slide['video'])): ?>
            <video autoplay muted loop playsinline>
              <source src="<?php echo $slide['video']; ?>" type="video/mp4">
            </video>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <!-- Default slide if no images -->
      <div class="hero-slide active" style="background-image: url('<?php echo $GLOBALS['CI']->template->template_images('default-hero.jpg'); ?>');">
        <div class="hero-overlay"></div>
      </div>
    <?php endif; ?>
  </div>
  
  <!-- Hero Navigation Arrows -->
  <div class="hero-nav-arrows">
    <button class="hero-arrow hero-arrow-prev" id="heroPrev">
      <i class="bi bi-chevron-left"></i>
    </button>
    <div class="hero-progress">
      <div class="hero-progress-bar" id="heroProgress"></div>
    </div>
    <button class="hero-arrow hero-arrow-next" id="heroNext">
      <i class="bi bi-chevron-right"></i>
    </button>
  </div>
  
  <!-- Hero Text Content (synced with slides) -->
  <div class="hero-text-content">
    <div class="container nopad position-relative">
      <div class="captngrp">
        <!-- <a href="#search-form" class="hero-discovery-btn">Discovery the World</a> -->
        <div id="big0" class="smalcaptn2 hero-text-item">Unleash Your Wanderlust
        Book Your Next Journey</div>
        <div id="desc" class="smalcaptn hero-text-item">Crafting Exceptional Journeys: Your Global Escape Planner. Unleash Your Wanderlust:
        Seamless Travel, Extraordinary Adventures</div>
      </div>

      <!-- Right Side Image Previews -->
  <div class="hero-side-previews">
    <?php if (!empty($this->slideImageJson)): ?>
      <?php 
      $previewCount = min(count($this->slideImageJson), 3); // Show up to 3 previews
      for ($i = 0; $i < $previewCount; $i++): 
      ?>
        <div class="preview-item <?php echo $i === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $i; ?>">
          <div class="preview-image-wrapper">
            <img src="<?php echo $this->slideImageJson[$i]['image']; ?>" alt="Preview <?php echo $i + 1; ?>">
          </div>
        </div>
      <?php endfor; ?>
    <?php endif; ?>
  </div>
</div>
    </div>
    
  </div>
  
  

<div class="searcharea" id="search-form">
  <div class="srchinarea">
    <div class="allformst">
      <div class="container inspad nopad">
        <div class="secndblak">

          <!-- Tab Navigation -->
          <div class="commonNavTabCls">
            <?php 
            // Determine active classes for each tab first
            $flight_active = set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab);
            $flight_hotel_active = set_default_active_tab(META_FLIGHT_HOTEL_COURSE, $default_active_tab);
            $hotel_active = set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab);
            $car_active = set_default_active_tab(META_CAR_COURSE, $default_active_tab);
            $sightseeing_active = set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab);
            $transfer_active = set_default_active_tab(META_TRANSFER_COURSE, $default_active_tab);
            $holiday_active = set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab);
            
            // Determine active tab and label for dropdown button
            $active_tab_label = 'Select Service';
            $active_tab_icon = 'menu';
            
            if (is_active_airline_module() && ($flight_active == 'active')) {
              $active_tab_label = 'Flights';
              $active_tab_icon = 'flight';
            } elseif (is_active_flight_hotel_module() && ($flight_hotel_active == 'active')) {
              $active_tab_label = 'Flight + Hotels';
              $active_tab_icon = 'flight';
            } elseif (is_active_hotel_module() && ($hotel_active == 'active')) {
              $active_tab_label = 'Hotels';
              $active_tab_icon = 'hotel';
            } elseif (is_active_car_module() && ($car_active == 'active')) {
              $active_tab_label = 'Car Rental';
              $active_tab_icon = 'directions_car';
            } elseif (is_active_sightseeing_module() && ($sightseeing_active == 'active')) {
              $active_tab_label = 'Sight Seeing';
              $active_tab_icon = 'camera_alt';
            } elseif (is_active_transfer_module() && ($transfer_active == 'active')) {
              $active_tab_label = 'Transfers';
              $active_tab_icon = 'transfer_within_a_station';
            } elseif (is_active_package_module() && ($holiday_active == 'active')) {
              $active_tab_label = 'Holiday';
              $active_tab_icon = 'beach_access';
            }
            ?>
            
            <!-- Mobile Bootstrap Dropdown -->
            <div class="dropdown mobile-tab-dropdown">
              <button class="btn mobile-tab-selector dropdown-toggle" type="button" id="mobileTabDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="material-icons"><?php echo $active_tab_icon; ?></i>
                <span class="dropdown-label"><?php echo $active_tab_label; ?></span>
                <i class="material-icons dropdown-arrow">keyboard_arrow_down</i>
              </button>
              <ul class="dropdown-menu mobile-tab-dropdown-menu" aria-labelledby="mobileTabDropdown">
                <?php if (is_active_airline_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($flight_active == 'active') ? 'active' : ''; ?>" href="#flight" data-tab-target="flight" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">flight</i>
                      <span>Flights</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_flight_hotel_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($flight_hotel_active == 'active') ? 'active' : ''; ?>" href="#flightHotel" data-tab-target="flightHotel" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">flight</i>
                      <span>Flight + Hotels</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_hotel_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($hotel_active == 'active') ? 'active' : ''; ?>" href="#hotel" data-tab-target="hotel" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">hotel</i>
                      <span>Hotels</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_car_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($car_active == 'active') ? 'active' : ''; ?>" href="#car" data-tab-target="car" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">directions_car</i>
                      <span>Car Rental</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_sightseeing_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($sightseeing_active == 'active') ? 'active' : ''; ?>" href="#sightseeing" data-tab-target="sightseeing" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">camera_alt</i>
                      <span>Sight Seeing</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_transfer_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($transfer_active == 'active') ? 'active' : ''; ?>" href="#transfer" data-tab-target="transfer" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">transfer_within_a_station</i>
                      <span>Transfers</span>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_package_module()) { ?>
                  <li>
                    <a class="dropdown-item mobile-tab-item <?php echo ($holiday_active == 'active') ? 'active' : ''; ?>" href="#holiday" data-tab-target="holiday" role="tab" data-bs-toggle="tab">
                      <i class="material-icons">beach_access</i>
                      <span>Holiday</span>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
            
            <ul class="nav nav-tabs tabstab d-flex justify-content-center" role="tablist">
              <?php if (is_active_airline_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $flight_active; ?>" href="#flight" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <i class="material-icons" size="18">flight</i>
                    </span><label>Flights</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_flight_hotel_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $flight_hotel_active; ?>" href="#flightHotel" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <i class="material-icons" size="18">flight</i>
                    </span>
                    <label>Flight + Hotels</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_hotel_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $hotel_active; ?>" href="#hotel" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn"><i class="material-icons" size="18">hotel</i></span><label>Hotels</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_car_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $car_active; ?>" href="#car" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn"><i class="material-icons" size="18">directions_car</i></span><label>Car Rental</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_sightseeing_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $sightseeing_active; ?>" href="#sightseeing" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn"><i class="material-icons" size="18">camera_alt</i></span><label> Sight Seeing</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_transfer_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $transfer_active; ?>" href="#transfer" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn"><i class="material-icons" size="18">transfer_within_a_station</i></span><label> Transfers</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_package_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $holiday_active; ?>" href="#holiday" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn"><i class="material-icons" size="18">beach_access</i></span><label> Holiday</label>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </div>

          <!-- Tab Content -->
          <div class="tab-content custmtab">
            <?php if (is_active_airline_module()) { 
              $show_class = ($flight_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $flight_active . $show_class; ?>" id="flight" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_hotel_module()) { 
              $show_class = ($hotel_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $hotel_active . $show_class; ?>" id="hotel" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_flight_hotel_module()) { 
              $show_class = ($flight_hotel_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $flight_hotel_active . $show_class; ?>" id="flightHotel" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_hotel_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_bus_module()) { 
              $bus_active = set_default_active_tab(META_BUS_COURSE, $default_active_tab);
              $show_class = ($bus_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $bus_active . $show_class; ?>" id="bus" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_transfer_module()) {  
              $show_class = ($transfer_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $transfer_active . $show_class; ?>" id="transfer" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/transfer_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_car_module()) { 
              $show_class = ($car_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $car_active . $show_class; ?>" id="car" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/car_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_package_module()) { 
              $show_class = ($holiday_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $holiday_active . $show_class; ?>" id="holiday" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search', $holiday_data) ?>
              </div>
            <?php } ?>
            <?php if (is_active_sightseeing_module()) { 
              $show_class = ($sightseeing_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $sightseeing_active . $show_class; ?>" id="sightseeing" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/sightseeing_search', $holiday_data) ?>
              </div>
            <?php } ?>
            <?php if (is_active_cruise_module()) { 
              $cruise_active = set_default_active_tab(META_CRUISE_COURSE, $default_active_tab);
              $show_class = ($cruise_active == 'active') ? ' show' : '';
            ?>
              <div class="tab-pane fade <?php echo $cruise_active . $show_class; ?>" id="cruise" role="tabpanel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/cruise_search', $holiday_data) ?>
              </div>
            <?php } ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<div class="clearfix"></div>
<!-- 23-11-2025 -->

<div class="top_flight-sec d-none">
  <div class="offers_sec">
    <div class="container nopad mobile_pad">

      <!-- Swiper Slider -->
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">

          <!-- Slide 1 -->
           <?php foreach ($this->hook_top_deals as $ky => $value) { ?>
          <div class="swiper-slide">
            <div class="offer-card offer1">
              <div class="offer-bg"></div>
              <img src="<?php echo $GLOBALS['CI']->template->template_images('promocode/'.$value['promo_code_image']); ?>" class="offer-img">
              <div class="offer-content">
                <p>Upto</p>
               <h3 class="discount"><?php echo (($value['value_type'] == 'plus')? $currency_obj->get_currency_symbol($currency_obj->to_currency).' '. get_converted_currency_value($currency_obj->force_currency_conversion($value['value'])) : $value['value'].' %') ?></span> OFF</h3>
                <p><?php echo $value['description']?></p>
              </div>
            </div>
          </div>
          <?php } ?>

          

        </div>

        <!-- Pagination (dots) -->
        <div class="swiper-pagination"></div>

      </div>

    </div>
  </div>
</div>


  <section class="popular-hotels-section">
    <div class="container">
    <div class="popular-header">
      <div>
        <h2>Top Hotel Destinations around the World</h2>
        <span class="subtitle">Explore Top Stays</span>
      </div>
    </div>

    <!-- Desktop Grid View -->
    <div class="row hotel-cards-grid" >
      <!-- Hotel 1 -->
       <?php foreach(array_slice($hotel_top_destinations,0,6) as $index => $value) { 
         $hotel_city_id = $value['destination_id'];?>
      <div class="col-sm-6 col-md-12">
        <div class="hotel-card htd-wrap">
          <div class="hotel-image">
            <img src="<?php echo $GLOBALS['CI']->template->domain_images($value['image']); ?>" alt="<?php echo $value['hotel_name']; ?>">
            <!-- Badge -->
            <?php 
              $badge_types = ['badge-top-rated', 'badge-best-sale', 'badge-discount'];
              $badge_labels = ['Top Rated', 'Best Sale', '25% Off'];
              $badge_index = $index % 3;
            ?>
            <span class="trending-badge hotel-badge <?php echo $badge_types[$badge_index]; ?>"><?php echo $badge_labels[$badge_index]; ?></span>
            <!-- Favorite Icon -->
            <div class="hotel-favorite">
              <i class="material-icons">favorite_border</i>
            </div>
            <!-- Rating Overlay -->
            <div class="hotel-rating-overlay">
              <i class="material-icons star-icon">star</i>
              <span class="rating-text"><?php echo isset($value['rating']) ? number_format($value['rating'] + 0.96, 2) : '4.96'; ?></span>
              <span class="review-count">(<?php echo isset($value['review_count']) ? $value['review_count'] : rand(500, 1000); ?> reviews)</span>
            </div>
          </div>

          <!-- Hotel Info Box -->
          <div class="hotel-info-box">
            <h4><?php echo $value['hotel_name']; ?></h4>
            <div class="hotel-location"><?php echo $value['city_name'];?></div>
            <div class="star-rating">
              <?php 
                $rating = isset($value['rating']) ? $value['rating'] : 5;
                for($i = 0; $i < 5; $i++) {
                  if($i < $rating) {
                    echo '<i class="material-icons">star</i>';
                  } else {
                    echo '<i class="material-icons">star_border</i>';
                  }
                }
              ?>
            </div>
          </div>


          <!-- Hotel Details (Duration & Guests) -->
          <div class="hotel-details">
            <div class="hotel-detail-item">
              <i class="material-icons">schedule</i>
              <span>2 days 3 nights</span>
            </div>
            <div class="hotel-detail-item">
              <i class="material-icons">people</i>
              <span>4-6 guest</span>
            </div>
          </div>

          <!-- Hotel Footer -->
          <div class="hotel-footer">
            <input type="hidden" class="top_des_id" value="<?php echo $hotel_city_id ?>">
            <input type="hidden" class="top-des-val hand-cursor" value="<?php echo hotel_suggestion_value($value['city_name'], $value['country_name']) ?>">
            <div class="price">
              <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?php echo isset($value['price'])?get_converted_currency_value ( $currency_obj->force_currency_conversion ($value['price']) ):0; ?>
              <small>/ person</small>
            </div>
            <a href="<?=base_url().'hotel'?>" class="btn-details hotel-book-btn">Book Now</a>
          </div>
        </div>
      </div>
    <?php } ?>
    </div>
  </div>
  </section>

  <!-- Popular Destinations Section -->
  <section class="popular-destinations-section">
    <div class="container">
      <div class="popular-destinations-header">
        <h2>Popular Destinations</h2>
        <p class="destinations-subtitle">Favorite destinations based on customer reviews</p>
      </div>

      <div class="destinations-grid">
        <?php 
        // Use hotel_top_destinations for popular destinations (first 7)
        $popular_destinations = array_slice($hotel_top_destinations, 0, 7);
        foreach($popular_destinations as $index => $destination) {
          $dest_city_id = $destination['destination_id'];
          $hotel_count = isset($destination['cache_hotels_count']) ? $destination['cache_hotels_count'] : 356;
        ?>
        <div class="destination-card-wrapper">
          <div class="destination-card">
            <div class="destination-image">
              <img src="<?php echo $GLOBALS['CI']->template->domain_images($destination['image']); ?>" alt="<?php echo $destination['city_name']; ?>">
            </div>
            <div class="destination-content">
              <h3 class="destination-name"><?php echo $destination['city_name']; ?></h3>
              <p class="destination-hotels"><?php echo $hotel_count; ?> Hotels</p>
            </div>
            <a href="<?=base_url().'hotel'?>" class="destination-link">
              <input type="hidden" class="top_des_id" value="<?php echo $dest_city_id ?>">
              <input type="hidden" class="top-des-val hand-cursor" value="<?php echo hotel_suggestion_value($destination['city_name'], $destination['country_name']) ?>">
              <i class="material-icons">arrow_forward</i>
            </a>
          </div>
        </div>
        <?php } ?>
        
        <!-- Call to Action Card -->
        <div class="destination-card-wrapper">
          <div class="destination-cta-card">
            <h3 class="cta-title">Crafting Your Perfect Travel Experience</h3>
            <a href="<?=base_url().'hotel'?>" class="cta-button">
              Browse All destinations
              <i class="material-icons">arrow_forward</i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="clearfix"></div>


  <!-- Why Travel With Us Section -->
  <section class="why-travel-section">
    <div class="container">
      <div class="why-travel-header">
        <h2 class="why-travel-title">Why Travel With Us?</h2>
        <p class="why-travel-subtitle">The best booking platform you can trust</p>
      </div>
      
      <div class="why-travel-cards">
        <div class="why-travel-card card-best-price">
          <div class="why-travel-icon-wrapper">
            <div class="why-travel-icon">
              <i class="material-icons">verified</i>
            </div>
          </div>
          <h3 class="why-travel-card-title">Best Price</h3>
          <p class="why-travel-card-desc">Demonstrates commitment to user data security through encryption and secure payment practices</p>
          <a href="#" class="why-travel-link">Learn More <i class="material-icons">arrow_forward</i></a>
        </div>
        
        <div class="why-travel-card card-24-7">
          <div class="why-travel-icon-wrapper">
            <div class="why-travel-icon">
              <i class="material-icons">support_agent</i>
            </div>
          </div>
          <h3 class="why-travel-card-title">24/7 Booking</h3>
          <p class="why-travel-card-desc">Demonstrates commitment to user data security through encryption and secure payment practices</p>
          <a href="#" class="why-travel-link">Learn More <i class="material-icons">arrow_forward</i></a>
        </div>
        
        <div class="why-travel-card card-satisfaction">
          <div class="why-travel-icon-wrapper">
            <div class="why-travel-icon">
              <i class="material-icons">handshake</i>
            </div>
          </div>
          <h3 class="why-travel-card-title">Customer Satisfaction</h3>
          <p class="why-travel-card-desc">Demonstrates commitment to user data security through encryption and secure payment practices</p>
          <a href="#" class="why-travel-link">Learn More <i class="material-icons">arrow_forward</i></a>
        </div>
        
        <div class="why-travel-card card-easy-booking">
          <div class="why-travel-icon-wrapper">
            <div class="why-travel-icon">
              <i class="material-icons">check_circle</i>
            </div>
          </div>
          <h3 class="why-travel-card-title">Easy Booking</h3>
          <p class="why-travel-card-desc">Demonstrates commitment to user data security through encryption and secure payment practices</p>
          <a href="#" class="why-travel-link">Learn More <i class="material-icons">arrow_forward</i></a>
        </div>
      </div>
    </div>
  </section>


  <!-- Top Flight Routes Section -->
  <section class="topFlightRoutes">
    <div class="container">
      <div class="route-sec-header">
        <h2 class="route-sec-title">Top Flight Routes</h2>
        <p class="route-sec-subtitle">Popular flight destinations for your next adventure</p>
      </div>
      
      <div class="flight-routes-grid">
        <?php 
        foreach(array_slice($top_destination_flight, 0, 6) as $top_flights) { 
        ?>
        <div class="route-card-wrapper">
          <a href="#" class="flight_deals_search route-card-link">
            <div class="route-card-grid">
              <div class="route-departure">
                <div class="route-city"><?= $top_flights['from_airport_name']; ?></div>
                <div class="route-airport"><?= $top_flights['from_airport_name']; ?> Airport</div>
              </div>
              <div class="route-connection">
                <div class="route-line"></div>
                <div class="route-icon-wrapper">
                  <i class="material-icons route-airplane-icon">flight</i>
                </div>
              </div>
              <div class="route-arrival">
                <div class="route-city"><?= $top_flights['to_airport_name']; ?></div>
                <div class="route-airport"><?= $top_flights['to_airport_name']; ?> Airport</div>
              </div>
              <input type="hidden" class="flight_top_from_id" value="<?= $top_flights['from_origin']; ?>">
              <input type="hidden" class="flight_top_to_id" value="<?= $top_flights['to_origin']; ?>">
              <input type="hidden" class="flight_top_from_value" value="<?= $top_flights['from_airport_name']; ?> (<?= $top_flights['from_airport_code']; ?>)">
              <input type="hidden" class="flight_top_to_value" value="<?= $top_flights['to_airport_name']; ?> (<?= $top_flights['to_airport_code']; ?>)">
              <input type="hidden" class="flight_top_depature" value="<?= (new DateTime('+1 day'))->format('d-m-Y');?>">
              <input type="hidden" class="flight_trip_type" value="oneway">
            </div>
          </a>
        </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <div class="clearfix"></div>


  <!-- Trending Holiday Destinations Section -->
  <section class="trending-holiday-section">
    <div class="container">
      <div class="trending-holiday-header">
        <h2>Trending Holiday Destinations</h2>
        <p class="holiday-subtitle">Discover amazing holiday packages and travel experiences</p>
      </div>

      <div class="holiday-packages-slider swiper" id="holiday_packages_slider">
        <div class="swiper-wrapper">
          <?php 
          $holiday_packages = array_slice($holiday_data['packages'], 0, 6);
          foreach($holiday_packages as $index => $holiday) { 
            $package_name = isset($holiday->package_name) ? $holiday->package_name : (isset($holiday->name) ? $holiday->name : 'Holiday Package');
            $package_country = isset($holiday->country_name) ? $holiday->country_name : (isset($holiday->country) ? $holiday->country : '');
            $package_city = isset($holiday->city_name) ? $holiday->city_name : (isset($holiday->city) ? $holiday->city : '');
            $package_price = isset($holiday->price) ? $holiday->price : '';
            $package_duration = isset($holiday->duration) ? $holiday->duration : '';
          ?>
          <div class="swiper-slide">
            <div class="holiday-card-wrapper">
              <div class="holiday-card">
                <div class="holiday-image">
                  <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($holiday->image); ?>" alt="<?php echo $package_name; ?>">
                  <div class="holiday-overlay">
                    <div class="holiday-badge">Trending</div>
                  </div>
                </div>
                <div class="holiday-content">

                  <h3 class="holiday-title"><?php echo $package_name; ?></h3>
                  <?php if ($package_duration): ?>
                  <div class="holiday-duration">
                    <i class="material-icons">schedule</i>
                    <span><?php echo $package_duration; ?> Days</span>
                  </div>
                  <?php endif; ?>
                  <div class="holiday-footer">
                    <?php if ($package_price): ?>
                    <div class="holiday-price">
                      <span class="price-label">From</span>
                      <span class="price-amount"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?><?php echo get_converted_currency_value($currency_obj->force_currency_conversion($package_price)); ?></span>
                    </div>
                    <?php endif; ?>
                    <a href="<?= base_url().'tours/search?country=&package_type=&duration=&budget='?>" class="holiday-view-btn">
                      View Details
                      <i class="material-icons">arrow_forward</i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
        <div class="swiper-button-next holiday-swiper-next">
          <i class="material-icons">chevron_right</i>
        </div>
        <div class="swiper-button-prev holiday-swiper-prev">
          <i class="material-icons">chevron_left</i>
        </div>
      </div>
    </div>
  </section>


  <?php if ($top_airlines['status'] == SUCCESS_STATUS): ?>
  <!-- Top Airline Partner Section -->
  <section class="airline-partner-section">
    <div class="container">
      <div class="airline-partner-header">
        <h2 class="airline-partner-title">Top Airline Partner</h2>
        <p class="airline-partner-subtitle">
          We partner with the world's leading airlines to bring you the best travel experience.
        </p>
      </div>
      
      <div class="airline-partner-slider swiper" id="airline_partner_slider">
        <div class="swiper-wrapper">
          <?php if (!empty($top_airlines['data'])) : ?>
            <?php foreach ($top_airlines['data'] as $partner) : ?>
              <div class="swiper-slide">
                <div class="airline-partner-card">
                  <div class="airline-logo-wrapper">
                    <img src="<?php echo $GLOBALS['CI']->template->domain_top_airline_images($partner['logo']); ?>" 
                         alt="<?php echo isset($partner['airline_name']) ? $partner['airline_name'] : (isset($partner['for_b2b_b2c']) ? $partner['for_b2b_b2c'] : 'Airline Partner'); ?>" 
                         class="airline-logo">
                  </div>
                  <span class="airline-name"><?php echo isset($partner['airline_name']) ? $partner['airline_name'] : (isset($partner['for_b2b_b2c']) ? $partner['for_b2b_b2c'] : 'Airline Partner'); ?></span>
                </div>
              </div>
              <!--  <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline2.png'); ?>"
                    alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline3.png'); ?>"
                    alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('ANAsvg.png'); ?>"
                    alt="ANA"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('AirFrancesvg.png'); ?>"
                    alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('BritishAirwayssvg.png'); ?>" alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline4.png'); ?>"
                    alt="Air Canada"><span>Singapore Airlines</span>
            </div>
             <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('g12.png'); ?>" alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline2.png'); ?>"
                    alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline3.png'); ?>"
                    alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('ANAsvg.png'); ?>"
                    alt="ANA"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('AirFrancesvg.png'); ?>"
                    alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('BritishAirwayssvg.png'); ?>" alt="Singapore Airlines"><span>Singapore Airlines</span>
            </div>
            <div class="partner">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline4.png'); ?>"
                    alt="Air Canada"><span>Singapore Airlines</span>
            </div> -->

            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="swiper-button-next airline-swiper-next">
          <i class="material-icons">chevron_right</i>
        </div>
        <div class="swiper-button-prev airline-swiper-prev">
          <i class="material-icons">chevron_left</i>
        </div>
      </div>
    </div>
  </section>

  

  <!-- Frequently Asked Questions Section -->
  <section class="faq-section">
    <div class="container">
      <div class="faq-header">
        <h2 class="faq-title">Frequently Asked Questions</h2>
        <p class="faq-subtitle">You need to come at least once in your life.</p>
      </div>
      
      <div class="faq-categories">
        <button class="faq-category-btn active" data-category="hotels">
          <i class="material-icons">hotel</i>
          <span>Hotels Booking</span>
        </button>
        <button class="faq-category-btn" data-category="bus">
          <i class="material-icons">directions_bus</i>
          <span>Bus</span>
        </button>
        <button class="faq-category-btn" data-category="tickets">
          <i class="material-icons">confirmation_number</i>
          <span>Tickets Booking</span>
        </button>
      </div>
      
      <div class="faq-content-card">
        <div class="faq-item active">
          <div class="faq-item-header">
            <span class="faq-number">01</span>
            <h3 class="faq-question">How do I make a reservation on your website?</h3>
            <button class="faq-toggle">
              <i class="material-icons">close</i>
            </button>
          </div>
          <div class="faq-answer">
            <p>Provide a step-by-step guide on how users can browse and book travel services on your platform. Include information on searching for destinations, selecting dates, choosing accommodation, and completing the booking process. Mention any special features or tools that can help users find the best deals.</p>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-item-header">
            <span class="faq-number">02</span>
            <h3 class="faq-question">What documents do I need for my trip, and how do I obtain them?</h3>
            <button class="faq-toggle">
              <i class="material-icons">add</i>
            </button>
          </div>
          <div class="faq-answer">
            <p>List all required travel documents such as passports, visas, travel insurance, and any specific permits. Explain the application process, processing times, and where to obtain each document. Include links to official resources and any assistance your platform provides.</p>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-item-header">
            <span class="faq-number">03</span>
            <h3 class="faq-question">In the event that I need to modify or cancel my reservation, what are the policies in place?</h3>
            <button class="faq-toggle">
              <i class="material-icons">add</i>
            </button>
          </div>
          <div class="faq-answer">
            <p>Clearly outline your cancellation and modification policies, including timeframes, fees, and refund processes. Explain the difference between refundable and non-refundable bookings, and provide step-by-step instructions for making changes or cancellations through your platform.</p>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-item-header">
            <span class="faq-number">04</span>
            <h3 class="faq-question">Can you specify the types of credit/debit cards, digital wallets, or other online payment methods accepted?</h3>
            <button class="faq-toggle">
              <i class="material-icons">add</i>
            </button>
          </div>
          <div class="faq-answer">
            <p>List all accepted payment methods including major credit cards (Visa, Mastercard, American Express), debit cards, digital wallets (PayPal, Apple Pay, Google Pay), bank transfers, and any other payment options. Include information about currency conversion, security measures, and payment processing times.</p>
          </div>
        </div>
        
        <div class="faq-item">
          <div class="faq-item-header">
            <span class="faq-number">05</span>
            <h3 class="faq-question">What are the working hours, and what can I expect in terms of response times?</h3>
            <button class="faq-toggle">
              <i class="material-icons">add</i>
            </button>
          </div>
          <div class="faq-answer">
            <p>Provide detailed information about customer support availability, including business hours, time zones, and contact methods. Specify response times for different types of inquiries (email, phone, chat) and any 24/7 support options. Include information about peak times and expected delays.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

      </div>
    </section>
  <?php endif; ?>

<div class="clearfix"></div>
  

</div>
<div class="clearfix"></div>

<?= $this->template->isolated_view('share/js/lazy_loader') ?>
<script>
  $(document).ready(function() {

    $("#airline_slider").owlCarousel({
      itemsCustom: [
        [0, 3],
        [450, 4],
        [551, 4],
        [700, 1],
        [1000, 6],
        [1200, 6],
        [1600, 6]
      ],
      loop: true,
      autoPlay: 2000,
      navigation: true,
      pagination: false,
    });

    var owl3 = $("#packages_list");
    var owl6 = $("#activities_list");
    var owl9 = $("#all_deal_1");

    owl3.owlCarousel({
      itemsCustom: [
        [0, 1],
        [450, 1],
        [551, 2],
        [700, 2],
        [1000, 3],
        [1200, 3],
        [1400, 3],
        [1600, 3]
      ],
      navigation: true,
      pagination: false

    });

    $("#packages_list .owl-prev").html('<i class="fa fa-chevron-left"></i>');
    $("#packages_list .owl-next").html('<i class="fa fa-chevron-right"></i>');

    owl6.owlCarousel({
      itemsCustom: [
        [0, 1],
        [450, 1],
        [551, 2],
        [700, 2],
        [1000, 3],
        [1200, 3],
        [1400, 3],
        [1600, 3]
      ],
      navigation: true,
      pagination: false
    });

    $("#activities_list .owl-prev").html('<i class="fa fa-chevron-left"></i>');
    $("#activities_list .owl-next").html('<i class="fa fa-chevron-right"></i>');

    owl9.owlCarousel({
      itemsCustom: [
        [0, 1],
        [450, 1],
        [551, 1],
        [700, 2],
        [1000, 3],
        [1200, 3],
        [1400, 3],
        [1600, 3]
      ],
      loop: true,
      autoplay: true,
      navigation: true,
      pagination: false,
    });

    $("#all_deal_1 .owl-prev").html('<i class="fa fa-chevron-left"></i>');
    $("#all_deal_1 .owl-next").html('<i class="fa fa-chevron-right"></i>');

    $('.hotel_carousel').each(function() {
      $(this).owlCarousel({
        itemsCustom: [
          [0, 1],
          [450, 1],
          [551, 1],
          [700, 2],
          [1000, 3],
          [1200, 3],
          [1400, 3],
          [1600, 3]
        ],
        loop: true,
        autoplay: true,
        navigation: true,
        pagination: false,
      });
      $(".hotel_carousel .owl-prev").html('<i class="fa fa-chevron-left"></i>');
      $(".hotel_carousel .owl-next").html('<i class="fa fa-chevron-right"></i>');
    });

    // Holiday Packages Slider - Swiper.js
    function initHolidaySlider() {
      if (typeof Swiper !== 'undefined' && document.getElementById('holiday_packages_slider')) {
        new Swiper('#holiday_packages_slider', {
          slidesPerView: 1,
          spaceBetween: 20,
          loop: true,
          autoplay: {
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          },
          navigation: {
            nextEl: '.holiday-swiper-next',
            prevEl: '.holiday-swiper-prev',
          },
          breakpoints: {
            450: {
              slidesPerView: 1,
              spaceBetween: 20,
            },
            551: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            768: {
              slidesPerView: 2,
              spaceBetween: 24,
            },
            1024: {
              slidesPerView: 3,
              spaceBetween: 24,
            },
            1400: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
          },
          speed: 600,
          effect: 'slide',
          grabCursor: true,
        });
      } else if (document.getElementById('holiday_packages_slider')) {
        setTimeout(initHolidaySlider, 100);
      }
    }
    
    // Initialize holiday slider
    initHolidaySlider();


    // Airline Partner Slider - Swiper.js
    function initAirlinePartnerSlider() {
      if (typeof Swiper !== 'undefined' && document.getElementById('airline_partner_slider')) {
        new Swiper('#airline_partner_slider', {
          slidesPerView: 2,
          spaceBetween: 20,
          loop: true,
          autoplay: {
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          },
          navigation: {
            nextEl: '.airline-swiper-next',
            prevEl: '.airline-swiper-prev',
          },
          breakpoints: {
            450: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            640: {
              slidesPerView: 3,
              spaceBetween: 24,
            },
            768: {
              slidesPerView: 4,
              spaceBetween: 24,
            },
            1024: {
              slidesPerView: 5,
              spaceBetween: 30,
            },
            1280: {
              slidesPerView: 6,
              spaceBetween: 30,
            },
          },
          speed: 600,
          effect: 'slide',
          grabCursor: true,
        });
      } else if (document.getElementById('airline_partner_slider')) {
        setTimeout(initAirlinePartnerSlider, 100);
      }
    }
    
    // Initialize airline partner slider
    initAirlinePartnerSlider();

    // FAQ Accordion Functionality
    $('.faq-toggle').on('click', function(e) {
      e.stopPropagation();
      var $faqItem = $(this).closest('.faq-item');
      var $icon = $(this).find('i');
      
      if ($faqItem.hasClass('active')) {
        $faqItem.removeClass('active');
        $icon.text('add');
      } else {
        $('.faq-item').removeClass('active');
        $('.faq-toggle i').text('add');
        $faqItem.addClass('active');
        $icon.text('close');
      }
    });

    // FAQ Category Filter
    $('.faq-category-btn').on('click', function() {
      $('.faq-category-btn').removeClass('active');
      $(this).addClass('active');
      // Category filtering logic can be added here
    });
  });
</script>

<!-- 28-04-2025 -->

<script>
  $(function() {
    $("#date1").datepicker();
  });
  $(function() {
    $("#date2").datepicker();
  });
</script>
<!-- end -->
<script type="text/javascript">
  $(document).ready(function() {

    $("#popular_flightdestinations").owlCarousel({
      itemsCustom: [
        [0, 1],
        [450, 2],
        [551, 2],
        [700, 3],
        [1000, 4],
        [1200, 4],
        [1400, 4],
        [1600, 4]
      ],

      navigation: true,
      pagination: false,
      loop: true,
      rewindNav: false,
    });
  })
</script>

<style>
  .top_flight-sec .owl-controls {}
</style>
<script>
  let instance;
  document.addEventListener('DOMContentLoaded', function() {
    const elem = document.querySelector('.carousel');
    //  if (!elem) {
    //    console.error('Carousel element not found!');
    //    return;
    //  }
    instance = M.Carousel.init(elem, {
      dist: -50,
      shift: 0,
      padding: 20,
      numVisible: 5,
      indicators: false
    });

    document.getElementById('next').addEventListener('click', () => {
      instance.next();
    });

    document.getElementById('prev').addEventListener('click', () => {
      instance.prev();
    });
  });
</script>

<style>
  .partner_wrapper img {
    width: 100px;
    height: auto;
    display: inline-block;
    margin: 0 10px;
  }
</style>

<script>
  $(document).ready(function() {
    $('.partner_wrapper').owlCarousel({
      loop: true,
      margin: 3,
      autoplay: true,
      autoplayTimeout: 1000,
      autoplayHoverPause: true,
      responsive: {
        0: {
          items: 3
        },
        600: {
          items: 8
        },
        1000: {
          items: 9
        },
        1200: {
          items: 10
        },
        1400: {
          items: 12
        }
      },
      mouseDrag: true,
      touchDrag: true
    });
  });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 15,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },

    // Responsive breakpoints
    breakpoints: {
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
    }
  });
</script>

<style>
  .mySwiper {
    height: 150px !important;        /* Custom height */
  }

  .mySwiper .swiper-slide {
    height: 150px !important;        /* Ensure slides follow same height */
  }

  .offer-card {
    height: 100% !important;         /* Make your card fit the slide height */
  }

  .offer-img {
    height: 100%;
    object-fit: cover;
  }
  .swiper-horizontal>.swiper-pagination-bullets, .swiper-pagination-bullets.swiper-pagination-horizontal, .swiper-pagination-custom, .swiper-pagination-fraction{
    display: none;
  }
</style>


<?php
if (isset($login) == false || is_object($login) == false) {
  $login = new Provab_Page_Loader('login');
}
$login_auth_loading_image  = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="please wait"/></div>';
?>
<!-- Icon Libraries -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('agent_index.css'); ?>" rel="stylesheet" defer>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css'); ?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
<link href="https://fonts.googleapis.com/css?family=Lato|Source+Sans+Pro" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'); ?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'); ?>"></script>
<div class="agent_page_wrapper">
  <div class="header">
    <div class="container">
      <div class="d-flex justify-content-between">
      <a class="navbar-brand" href="<?= base_url() ?>">
        <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="" />
      </a>
        

          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

          <li class="nav-item">
        <?php 
                                        $support_no = $this->entity_domain_phone_code . '-' . trim($this->entity_domain_phone);
                                        $support_no_tel = $this->entity_domain_phone_code . trim($this->entity_domain_phone);
                                        $support_email = !empty($this->entity_domain_mail) ? $this->entity_domain_mail : 'support@travelomatix.com';
                                        ?>
                                        <div class="customer-support-dropdown">
                                            <a class="phnumr customer-support-link dropdown-toggle" href="javascript:void(0);" role="button" id="supportDropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                                <div class="customer-support-icon">
                                                    <div class="support-icon-wrapper">
                                                        <i class="material-icons support-headphone-icon">headset_mic</i>
                                                    </div>
                                                </div>
                                                <div class="customer-support-text">
                                                    <span class="support-label">
                                                        Customer Support
                                                    </span>
                                                </div>
                                                <i class="bi bi-chevron-down support-chevron"></i>
                                            </a>
                                            <ul class="dropdown-menu support-dropdown-menu" aria-labelledby="supportDropdown" role="menu">
                                                <li class="support-dropdown-item">
                                                    <div class="support-item-header">
                                                        <i class="material-icons support-item-icon">phone</i>
                                                        <span class="support-item-label">Call Support</span>
                                                    </div>
                                                    <a href="tel:<?= $support_no_tel ?>" class="support-item-link">
                                                        Tel : <?= $support_no ?>
                                                    </a>
                                                </li>
                                                <li class="support-dropdown-item">
                                                    <div class="support-item-header">
                                                        <i class="material-icons support-item-icon">email</i>
                                                        <span class="support-item-label">Mail Support</span>
                                                    </div>
                                                    <a href="mailto:<?= $this->entity_domain_mail ?>" class="support-item-link">
                                                    <?= $this->entity_domain_mail ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>  
            
          </li>
          <!-- Back to Home Link -->
          <li class="nav-item">
            <a class="nav-link" href="<?= str_replace('agent/', '', base_url()) ?>">
              <i class="material-icons">home</i> <span class="d-none d-md-inline">Home</span>
            </a>
          </li>

           <!-- Help Link -->
           <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>help" target="_blank">
              <i class="material-icons">help_outline</i> <span class="d-none d-md-inline">Help</span>
            </a>
          </li>

          <!-- Sign Up Button -->
          <li class="nav-item">
            <a class="nav-link nav-link-signup" href="<?= base_url() . 'index.php/user/agentRegister' ?>">
              <i class="material-icons">person_add</i> <span>Sign Up</span>
            </a>
          </li>

          
        </ul>
      </div>
    </div>
  </div>
  <div class="topform_main">
    <!-- Animated Travel Icons - Floating -->
    <i class="material-icons travel-icon plane-1">flight</i>
    <i class="material-icons travel-icon plane-2">flight_takeoff</i>
    <i class="material-icons travel-icon suitcase-1">luggage</i>
    <i class="material-icons travel-icon suitcase-2">work</i>
    <i class="material-icons travel-icon globe-1">public</i>
    <i class="material-icons travel-icon hotel-1">hotel</i>
    <i class="material-icons travel-icon map-1">map</i>
    <i class="material-icons travel-icon passport-1">badge</i>
    
    <!-- Animated Travel Icons - Moving Left to Right -->
    <i class="material-icons travel-icon-move move-1">flight</i>
    <i class="material-icons travel-icon-move move-2">flight_takeoff</i>
    <i class="material-icons travel-icon-move move-3">luggage</i>
    <i class="material-icons travel-icon-move move-4">work</i>
    <i class="material-icons travel-icon-move move-5">beach_access</i>
    <i class="material-icons travel-icon-move move-6">directions_car</i>
    <i class="material-icons travel-icon-move move-7">train</i>
    <i class="material-icons travel-icon-move move-8">directions_boat</i>
    <i class="material-icons travel-icon-move move-9">restaurant</i>
    <i class="material-icons travel-icon-move move-10">camera_alt</i>
    <i class="material-icons travel-icon-move move-11">explore</i>
    <i class="material-icons travel-icon-move move-12">card_travel</i>
    <i class="material-icons travel-icon-move move-13">local_airport</i>
    <i class="material-icons travel-icon-move move-14">terrain</i>
    <i class="material-icons travel-icon-move move-15">landscape</i>
    
    <div class="topform">
      <div class="clearfix"></div>
      <div class="container p-0">
        <div class="banner-layout">
          <!-- Left Side: Text Slider -->
          <div class="banner-left-content">
            <div class="content-slider-wrapper">
              <div class="content-slider">
                <div class="slider-item active">
                  <div class="slider-icon">
                    <i class="material-icons">dashboard</i>
                  </div>
                  <h2 class="slider-title">Powerful Agent Dashboard</h2>
                  <p class="slider-description">Manage all your bookings, markups, and commissions from one centralized dashboard. Real-time updates and comprehensive reporting at your fingertips.</p>
                </div>
                <div class="slider-item">
                  <div class="slider-icon">
                    <i class="material-icons">flight</i>
                  </div>
                  <h2 class="slider-title">Global Travel Inventory</h2>
                  <p class="slider-description">Access flights, hotels, transfers, activities, and more from verified suppliers worldwide. Get the best prices for your customers.</p>
                </div>
                <div class="slider-item">
                  <div class="slider-icon">
                    <i class="material-icons">settings</i>
                  </div>
                  <h2 class="slider-title">Real-time API Integrations</h2>
                  <p class="slider-description">Fast, reliable, and secure integrations with leading travel providers. Instant booking confirmations and seamless transactions.</p>
                </div>
                <div class="slider-item">
                  <div class="slider-icon">
                    <i class="material-icons">support_agent</i>
                  </div>
                  <h2 class="slider-title">Dedicated Partner Support</h2>
                  <p class="slider-description">Personalized service with 24/7 assistance. Our expert team is always ready to help you grow your travel business.</p>
                </div>
                <div class="slider-item">
                  <div class="slider-icon">
                    <i class="material-icons">trending_up</i>
                  </div>
                  <h2 class="slider-title">Maximize Your Profits</h2>
                  <p class="slider-description">Competitive commission structures and flexible markup options. Build a profitable travel business with our B2B platform.</p>
                </div>
              </div>
              <!-- Slider Navigation Dots -->
              <div class="slider-dots">
                <span class="dot active" data-slide="0"></span>
                <span class="dot" data-slide="1"></span>
                <span class="dot" data-slide="2"></span>
                <span class="dot" data-slide="3"></span>
                <span class="dot" data-slide="4"></span>
              </div>
            </div>
          </div>

          <!-- Right Side: Login Form -->
          <div class="banner-right-content">
            <div class="loginbox">
         
            <?php
            $class = '';
            $otp_class = 'hide';
            $OTP_status = $this->session->userdata('OTP_status');
            if (isset($OTP_status) && $OTP_status == 'not verified') {
              $class = 'hide';
              $otp_class = '';
            }
            //echo $this->session->userdata('OTP_status');exit;
            ?>
            <div class="innersecing <?php echo $class; ?>">
              <div class="signhes"><span class="sign-in-text">Sign In</span> to <span class="domain-name"><?= domain_name(); ?></span></div>
              <?php $name = 'login' ?>
              <form name="<?= $name ?>" autocomplete="off" action="<?php echo base_url(); ?>index.php/general/index" method="POST" enctype="multipart/form-data" id="login" role="form" class="row">
                <?php $FID = $GLOBALS['CI']->encrypt->encode($name); ?>
                <input type="hidden" name="FID" value="<?= $FID ?>">
                <div class="inputsing">
                  <i class="material-icons input-icon-left">person</i>
                  <input value="" name="email" dt="PROVAB_SOLID_V80" required="" type="email" placeholder="Username" class="mylogbox login-ip email _guest_validate_field" id="email" data-container="body" data-bs-toggle="popover" data-original-title="" data-placement="bottom" data-trigger="hover focus" data-content="Username Ex: john@bookingsdaily.com">
                </div>
                <div class="inputsing">
                  <i class="material-icons input-icon-left">lock</i>
                  <input value="" name="password" dt="PROVAB_SOLID_V45" required="" type="password" placeholder="Password" class="login-ip password mylogbox _guest_validate_field" id="password" data-container="body" data-bs-toggle="popover" data-original-title="" data-placement="bottom" data-trigger="hover focus" data-content="Password Ex: A3#FD*3377^*">
                </div>
                <!-- <button class="logbtn">Login</button> -->
                <button id="login_submit" class="logbtn">Login</button>
                <div id="login_auth_loading_image" style="display: none">
                  <?= $login_auth_loading_image ?>
                </div>
                <div id="login-status-wrapper" class="alert alert-danger" style="display: none"></div>
              </form>
              <div class="signhes"> Don’t have an account ? <a href="<?= base_url() . 'index.php/user/agentRegister' ?>">Sign up</a></div>
              <div class="signhes"><?php echo $GLOBALS['CI']->template->isolated_view('general/forgot-password'); ?></div>
            </div>
            <div class="innersecing <?php echo $otp_class; ?>">
              <?php $name = 'otp' ?>
              <form name="<?= $name ?>" autocomplete="off" action="" method="POST" enctype="multipart/form-data" id="login" role="form" class="row">
                <div class="inputsing">
                  <i class="material-icons input-icon-left">vpn_key</i>
                  <input value="" name="opt" required="" type="text" placeholder="Enter OTP" class="login-ip mylogbox _guest_validate_field" id="otp">
                </div>
                <button id="opt_submit" class="logbtn">Login</button>
                <div id="login-otp-wrapper" class="alert alert-danger" style="display: none"></div>
              </form>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    </div>
  </div>
  <!-- Travel Partners -->
  <div class="clearfix"></div>
  <section class="container-fluid travel-section" id="solutions">
    <div class="container">
      <div class="travel-section-wrapper">
        <div class="travel-title-section">
          <h2 class="travel-title">
            Why Travel Partners<br>
            Choose <span class="sub-title"><?= $this->entity_domain_name ?></span>
          </h2>
        </div>
        <div class="travel-features-grid">
          <div class="travel-feature-card">
            <div class="travel-feature-icon">
              <i class="material-icons">luggage</i>
            </div>
            <h4 class="travel-sub-title">Global Travel Inventory</h4>
            <p class="para">- Flights, Hotels, Transfers, Activities & more</p>
          </div>
          <div class="travel-feature-card">
            <div class="travel-feature-icon">
              <i class="material-icons">settings</i>
            </div>
            <h4 class="travel-sub-title">Real-time API Integrations</h4>
            <p class="para">- Fast, reliable & secure</p>
          </div>
          <div class="travel-feature-card">
            <div class="travel-feature-icon">
              <i class="material-icons">dashboard</i>
            </div>
            <h4 class="travel-sub-title">B2B Agent Dashboard</h4>
            <p class="para">- Manage bookings, markups & commissions</p>
          </div>
          <div class="travel-feature-card">
            <div class="travel-feature-icon">
              <i class="material-icons">support_agent</i>
            </div>
            <h4 class="travel-sub-title">Dedicated Partner Support</h4>
            <p class="para">- Personalized service, 24/7 assistance</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- business-section  -->
  <section class="section-business d-none" id="whychoose">
    <div class="container">
      <div class="business-stats-wrapper">
        <h2 class="business-stats-title">Tailored for Every Travel Business</h2>
        <div class="business-stats-grid">
          <div class="business-stat-card">
            <div class="stat-icon">
              <i class="material-icons">people</i>
            </div>
            <div class="stat-number">10,000+</div>
            <div class="stat-label">Travelers Served</div>
            <div class="stat-description">Satisfied Passengers</div>
          </div>
          
          <div class="business-stat-card">
            <div class="stat-icon">
              <i class="material-icons">flight</i>
            </div>
            <div class="stat-number">20,000+</div>
            <div class="stat-label">Bookings Completed</div>
            <div class="stat-description">Flights, hotels, cabs & packages</div>
          </div>
          
          <div class="business-stat-card">
            <div class="stat-icon">
              <i class="material-icons">handshake</i>
            </div>
            <div class="stat-number">5,000+</div>
            <div class="stat-label">Agents Connected</div>
            <div class="stat-description">Travel agents booking with <?= $this->entity_domain_name ?></div>
          </div>
        </div>
        <div class="btn-wrapper">
          <a href="<?= base_url() . 'index.php/user/agentRegister' ?>" class="btn-become">
            <span>Become a Partner</span>
            <i class="material-icons">arrow_forward</i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Top Partners Section -->
  <section class="partners-section py-5" id="partners">
    <div class="container">
      <div class="partners-wrapper">
        <div class="partners-title-section">
          <h2 class="partners-title">
            Our Top <span class="partners-title-accent">Partners</span>
          </h2>
          <p class="partners-subtitle">Trusted by leading airlines and hotels worldwide</p>
        </div>
        
        <!-- Airlines Section -->
        <div class="partners-category">
        
          <div class="partners-grid">
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline2.png'); ?>" alt="Emirates" class="partner-logo">
              </div>
              <span class="partner-name">Emirates</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline3.png'); ?>" alt="Qatar Airways" class="partner-logo">
              </div>
              <span class="partner-name">Qatar Airways</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('ANAsvg.png'); ?>" alt="Singapore Airlines" class="partner-logo">
              </div>
              <span class="partner-name">Singapore Airlines</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('AirFrancesvg.png'); ?>" alt="Turkish Airlines" class="partner-logo">
              </div>
              <span class="partner-name">Turkish Airlines</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('BritishAirwayssvg.png'); ?>" alt="Etihad Airways" class="partner-logo">
              </div>
              <span class="partner-name">Etihad Airways</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline4.png'); ?>" alt="Lufthansa" class="partner-logo">
              </div>
              <span class="partner-name">Lufthansa</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('g12.png'); ?>" alt="British Airways" class="partner-logo">
              </div>
              <span class="partner-name">British Airways</span>
            </div>
            <div class="partner-item">
              <div class="partner-logo-wrapper">
                <img src="<?php echo $GLOBALS['CI']->template->template_images('Airline2.png'); ?>" alt="Air France" class="partner-logo">
              </div>
              <span class="partner-name">Air France</span>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>
  
  <!-- Profitability Section -->
  <section class="profitability-section" id="profitability">
    <div class="world-map-overlay"></div>
    <div class="container">
      <div class="profitability-wrapper">
        <h2 class="profitability-title">
          Ensuring Profitability <span class="title-divider">|</span><br>
          in Travel Business
        </h2>
        <p class="profitability-description">
          Get access to B2B prices for flights, hotels, cabs, tickets, experiences and lot more directly from on ground verified suppliers. Select your domain to view details.
        </p>
        <div class="profitability-actions">
          <a href="<?= base_url() . 'index.php/user/agentRegister' ?>" class="btn-request-demo">
            <span>Request a Demo</span>
            <i class="material-icons">arrow_forward</i>
          </a>
         
        </div>
      </div>
    </div>
  </section>
  
  <footer>
    <div class="fstfooter">
      <div class="container p-0">
        <div class="col-md-3 col-12 nopad">
          <div class="col-12 col-sm-12 fulnine color_bg nopad">
            <div class="col-md-12 nopad mycnt">
              <a href="#">
                <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>" alt="Logo">
              </a>
              <div class="prgh">
                <p>Building and strengthening online communities for the business partners that promote learning as a culture that helps improve mindset and way-of life.</p>
              </div>
              <div class="frtbest1">
                <ul class="signupfm socialli">
                  <?php
                  $temp = $this->custom_db->single_table_records('social_links');
                  if ($temp['data']['0']['status'] == ACTIVE) {
                  ?>
                    <li><a href="<?php echo $temp['data']['0']['url_link']; ?>" target="_blank">
                        <i class="faftrsoc bi bi-facebook"></i>
                      </a></li>
                  <?php } ?>
                  <?php if ($temp['data']['1']['status'] == ACTIVE) { ?>
                    <li><a href="<?php echo $temp['data']['1']['url_link']; ?>" target="_blank">
                        <i class="faftrsoc bi bi-twitter"></i>
                      </a></li>
                  <?php } ?>
                  <?php if ($temp['data']['1']['status'] == ACTIVE) { ?>
                    <li><a href="<?php echo $temp['data']['2']['url_link']; ?>" target="_blank">
                        <i class="faftrsoc bi bi-music-note-beamed"></i>
                      </a></li>
                  <?php } ?>
                </ul>
              </div>

            </div>
          </div>
        </div>
        <div class="col-9 reftr">
          <div class="nopad mobile_pad">
            <div class="col-6  col-sm-3 col-md-3 nopad pop_dest">
              <div class="frtbest2">
                <h4 class="ftrhd arimo">Popular Destination</h4>
                <ul class="accordionftr">
                  <?php
                  $data = $this->db->query('SELECT * FROM hotel_top_destinations WHERE status=1 GROUP BY city_name LIMIT 5')->result_array();
                  foreach ($data as $key => $value) {
                  ?>
                    <li class="frteli">
                      <a href="<?= str_replace('agent/', '', base_url() . 'hotels/' . $value['city_name']) ?>"><?php echo $value['city_name'] ?></a>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <div class="col-md-3 col-6 nopad col-sm-3">
              <div class="frtbest">
                <h4 class="ftrhd arimo">Discover</h4>
                <ul class="submenuftr1">
                  <li class="frteli"><a href="#">Flight finder</a></li>
                  <li class="frteli"><a href="#">Hotel booking</a></li>
                  <li class="frteli"><a href="#">Holiday deals</a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-3 col-6 nopad col-sm-3">
              <div class="frtbest">
                <h4 class="ftrhd arimo">Terms and settings</h4>
                <ul class="submenuftr1">
                  <li class="frteli"><a href="#">Privacy & Cookies</a></li>
                  <li class="frteli"><a href="#">Terms</a></li>
                </ul>
              </div>
            </div>
            <div class="col-6  col-sm-3 col-md-3 nopad">
              <div class="frtbest">
                <h4 class="ftrhd arimo">About</h4>
                <ul class="submenuftr1">
                  <?php
                  $cond = array(
                    'page_status' => ACTIVE
                  );
                  $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                  foreach ($cms_data['data'] as $keys => $values) {
                    echo '<li class="frteli"><a href="' . str_replace('agent/', '', base_url() . $values['page_label']) . '">' . $values['page_title'] . '</a></li>';
                  }
                  ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="copyrit">Copyright © <?= date('Y') ?> <strong><?= domain_name(); ?></strong>. All rights reserved.</div>
  </footer>
</div>
<style type="text/css">
  .invalid-ip {
    border: 1px solid #bf7070 !important;
  }
  .alert-danger {
    background-color: #dd4b39 !important;
  }
</style>
<script>
  $(document).ready(function() {
    $('.carousel').carousel({
      interval: 8000
    })
    $('#opt_submit').on('click', function(e) {
      e.preventDefault();
      var _otp = $('#otp').val();
      if (_otp == '') {
        $('#login-otp-wrapper').text('Please Enter Username And Password To Continue!!!').show();
      } else {
        $.post(app_base_url + "index.php/auth/check_otp", {
          otp: _otp
        }, function(response) {
          if (response.status) {
            window.location.reload();
          } else {
            $('#login-otp-wrapper').text(response.data).show();
          }
        });
      }
    });

    // Content Slider Functionality
    var currentSlide = 0;
    var slides = $('.slider-item');
    var dots = $('.slider-dots .dot');
    var totalSlides = slides.length;
    var slideInterval;

    function showSlide(index) {
      // Remove active class from all slides and dots
      slides.removeClass('active');
      dots.removeClass('active');
      
      // Add active class to current slide and dot
      $(slides[index]).addClass('active');
      $(dots[index]).addClass('active');
      
      currentSlide = index;
    }

    function nextSlide() {
      var next = (currentSlide + 1) % totalSlides;
      showSlide(next);
    }

    function startSlider() {
      slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    function stopSlider() {
      clearInterval(slideInterval);
    }

    // Dot click handler
    dots.on('click', function() {
      var slideIndex = $(this).data('slide');
      showSlide(slideIndex);
      stopSlider();
      startSlider(); // Restart auto-slide
    });

    // Pause on hover
    $('.content-slider-wrapper').on('mouseenter', function() {
      stopSlider();
    }).on('mouseleave', function() {
      startSlider();
    });

    // Initialize slider
    if (totalSlides > 0) {
      showSlide(0);
      startSlider();
    }
  });
</script>
<?php
// debug($package);exit;
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.v2.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('lightbox-gallery.js'), 'defer' => 'defer');
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/jquery.scrolling-tabs.css') ?>" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/tour-result.css') ?>" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/tour-details.css') ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.provabpopup.js') ?>" type="text/javascript"></script>

<?php 
// Collect all tour images
$tour_photos = array();

// Add main package image
if(!empty($package->image)){
    $tour_photos[] = array(
        'image' => $package->image,
        'caption' => isset($package->package_name) ? $package->package_name : 'Tour Package'
    );
}

// Add traveller photos if available
if(!empty($package_traveller_photos)){
    foreach($package_traveller_photos as $photo){
        if(!empty($photo->traveller_image)){
            $tour_photos[] = array(
                'image' => $photo->traveller_image,
                'caption' => isset($photo->caption) ? $photo->caption : 'Tour Photo'
            );
        }
    }
}

// Count total photos
$total_photos = count($tour_photos);
?>

<div class="tour-details-container">
    <div class="container tour-details-wrapper">
        <!-- Main Content Area -->
        <div class="tour-main-content">
            <!-- Slider & Gallery Wrapper -->
            <div class="slider-gallery-wrapper">
                <div class="main-slider-section">
                    <div id="tour_slider_section">
                        <div id="tour_main_slider" class="owl-carousel owl-theme tour-main-carousel">
                            <?php if(!empty($tour_photos)): ?>
                                <?php foreach($tour_photos as $index => $photo): ?>
                                <div class="item" data-index="<?php echo $index; ?>">
                                    <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($photo['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($photo['caption']); ?>" 
                                         class="gallery-image"/>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="item">
                                    <img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="No Image" />
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if($total_photos > 1): ?>
                <div class="thumbnail-gallery-section">
                    <div class="thumbnail-grid">
                        <?php 
                        $thumb_count = 0;
                        $max_thumbs = 8;
                        foreach($tour_photos as $index => $photo): 
                            if($thumb_count < $max_thumbs - 1): 
                        ?>
                            <div class="thumb-item" data-index="<?php echo $index; ?>">
                                <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($photo['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($photo['caption']); ?>">
                            </div>
                        <?php 
                                $thumb_count++;
                            elseif($thumb_count == $max_thumbs - 1):
                        ?>
                            <div class="thumb-item thumb-more" data-index="<?php echo $index; ?>">
                                <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($photo['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($photo['caption']); ?>">
                                <div class="thumb-overlay">
                                    <i class="bi bi-images"></i>
                                    <span>+<?php echo ($total_photos - $max_thumbs + 1); ?> More</span>
                                </div>
                            </div>
                        <?php 
                                $thumb_count++;
                                break;
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?> 
            </div>
            
            <!-- Package Header Section -->
            <div class="package-header-section">
                <div class="package-header-content">
                    <div class="package-title-wrapper">
                        <h1 class="package-title"><?php echo isset($package->package_name) ? $package->package_name : 'Tour Package'; ?></h1>
                        <div class="package-meta">
                            <?php if(isset($package->duration) && $package->duration > 0): ?>
                            <div class="package-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span><?php echo ($package->duration - 1); ?> Nights / <?php echo $package->duration; ?> Days</span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(isset($package->rating) && $package->rating > 0): ?>
                            <div class="package-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <span><?php echo number_format($package->rating, 1); ?> Rating</span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="package-meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span><?php echo $package->location; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="package-price-badge">
                        <div class="price-badge-label">Starting From</div>
                        <div class="price-badge-amount">
                            <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>
                            <?php echo isset($package->price) ? number_format(get_converted_currency_value($currency_obj->force_currency_conversion($package->price)), 2) : '0.00'; ?>
                        </div>
                        <div class="price-badge-note">Per Person</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Wrapper with Grid Layout -->
        <div class="tour-content-wrapper">
            <!-- Left Column -->
            <div class="tour-left-column">
                <!-- Tabs Content Card -->
                <div class="tour-content-card">
                <ul class="nav nav-tabs">
                    <li><a href="#ovrvw" data-bs-toggle="tab">Overview</a></li>
                    <li class="active"><a href="#itnery" data-bs-toggle="tab">Detailed Itinerary</a></li>
                    <li><a href="#tandc" data-bs-toggle="tab">Terms &amp; Conditions</a></li>
                    <!-- <li><a href="#gallery" data-bs-toggle="tab">Gallery</a></li> -->
                    <li><a href="#rating" data-bs-toggle="tab">Rating</a></li>
                </ul>

                <div class="tab-content5">
                    <!-- Overview Tab -->
                    <div class="tab-pane" id="ovrvw">
                        <div class="overview-content">
                            <?php echo !empty($package->package_description) ? $package->package_description : "<p>No description available for this package.</p>"; ?>
                        </div>
                    </div>

                    <!-- Itinerary Tab -->
                    <div class="tab-pane active" id="itnery">
                        <?php 
                        $i = 1;
                        if(!empty($package_itinerary)){
                            foreach($package_itinerary as $pi){ 
                        ?>
                        <div class="itinerary-item">
                            <div class="day-badge">
                                <span class="day-badge-label">Day</span>
                                <span class="day-badge-number"><?php echo $i; ?></span>
                            </div>
                            <div class="itinerary-content">
                                <h3 class="itinerary-place"><?php echo $pi->place; ?></h3>
                                <div class="itinerary-description"><?php echo $pi->itinerary_description; ?></div>
                                <?php if(!empty($pi->itinerary_image)): ?>
                                <div class="itinerary-image">
                                    <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($pi->itinerary_image)); ?>" alt="<?php echo $pi->place; ?>">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php 
                                $i++;
                            }
                        } else {
                            echo "<p>No itinerary available for this package.</p>";
                        }
                        ?>
                    </div>

                    <!-- Terms & Conditions Tab -->
                    <div class="tab-pane" id="tandc">
                        <?php if(!empty($package_price_policy->price_includes)): ?>
                        <div class="terms-section">
                            <h3 class="terms-heading">Price Includes</h3>
                            <ul class="terms-list">
                                <li><?php echo $package_price_policy->price_includes; ?></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($package_price_policy->price_excludes)): ?>
                        <div class="terms-section">
                            <h3 class="terms-heading">Price Excludes</h3>
                            <ul class="terms-list">
                                <li><?php echo $package_price_policy->price_excludes; ?></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($package_cancel_policy->cancellation_advance)): ?>
                        <div class="terms-section">
                            <h3 class="terms-heading">Cancellation Advance</h3>
                            <ul class="terms-list">
                                <li><?php echo $package_cancel_policy->cancellation_advance; ?></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($package_cancel_policy->cancellation_penality)): ?>
                        <div class="terms-section">
                            <h3 class="terms-heading">Cancellation Penalty</h3>
                            <ul class="terms-list">
                                <li><?php echo $package_cancel_policy->cancellation_penality; ?></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Gallery Tab -->
                    <!-- <div class="tab-pane" id="gallery">
                        <div class="gallery-carousel">
                            <div id="owl-demobaner1" class="owl-carousel">
                                <?php 
                                if(!empty($package_traveller_photos)){ 
                                    foreach($package_traveller_photos as $ptp){ 
                                ?>
                                <div class="item">
                                    <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($ptp->traveller_image); ?>" alt="Gallery Image" />
                                </div>
                                <?php 
                                    }
                                } else {
                                    echo "<p>No gallery images available for this package.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div> -->

                    <!-- Rating Tab -->
                    <div class="tab-pane" id="rating">
                        <div class="rating-display">
                            <div class="rating-score"><?php echo isset($package->rating) ? number_format($package->rating, 1) : 'N/A'; ?></div>
                            <div class="rating-stars">
                                <?php 
                                $rating = isset($package->rating) ? intval($package->rating) : 0;
                                for($i = 1; $i <= 5; $i++){
                                    echo $i <= $rating ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <div class="rating-text">User Rating</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="tour-sidebar">
            <div class="booking-card">
                <div class="price-section">
                    <div class="price-label">Starting From</div>
                    <div class="price-amount">
                        <span class="price-currency"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></span>
                        <?php echo isset($package->price) ? number_format(get_converted_currency_value($currency_obj->force_currency_conversion($package->price)), 2) : '0.00'; ?>
                    </div>
                    <div class="price-note">*Per Person</div>
                    
                    <div class="duration-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="duration-text">
                            <?php echo isset($package->duration) ? ($package->duration - 1) : 0; ?> Nights / 
                            <?php echo isset($package->duration) ? $package->duration : 0; ?> Days
                        </span>
                    </div>
                </div>

                <button type="button" class="send-query-btn" id="sendquery">Send Query</button>

                <div class="rating-card">
                    <div class="rating-label">Rate this package</div>
                    <div class="rating mypacksy">
                        <span class="star"><input type="radio" name="rating" id="str5" value="5"><label for="str5"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str4" value="4"><label for="str4"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str3" value="3"><label for="str3"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str2" value="2"><label for="str2"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str1" value="1"><label for="str1"></label></span>
                    </div>
                    <input type="hidden" id="pkg_id" value="<?php echo $package->package_id; ?>">
                    <div id="msg_pak"></div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Enquiry Modal -->
<div id="subtqry" class="card card-bodyme minwnwidth" style="display: none;">
    <div class="enquiry-modal-content">
        <button class="modal-close-btn closequery" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        
        <div class="popuperror" style="display:none;"></div>
        
        <div class="enquiry-header">
            <div class="enquiry-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <h3 class="enquiry-title">Send Your Enquiry</h3>
            <p class="enquiry-subtitle"><?php echo $package->package_name; ?></p>
        </div>
        
        <div class="enquiry-form-wrapper">
            <form action="" method="post" id="tourenquiry">
                <input type="hidden" id="package_id" name="package_id" value="<?php echo $package->package_id; ?>"/>
                <div class="success_msg"></div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Full Name<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-input alpha" id="first_name" name="first_name" required placeholder="Enter your full name"/>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Contact Number<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-input numeric" maxlength="13" id="phone" name="phone" required placeholder="+1 234 567 8900"/>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            Email Address<span class="required-star">*</span>
                        </label>
                        <input type="email" class="form-input" id="email" name="email" required placeholder="your.email@example.com"/>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            Departure Place<span class="required-star">*</span>
                        </label>
                        <input type="text" class="form-input" id="place" name="place" required placeholder="City or location"/>
                    </div>
                </div>

                <div class="form-group form-group-full">
                    <label class="form-label">
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Your Message<span class="required-star">*</span>
                    </label>
                    <textarea class="form-input form-textarea" id="message" name="message" required placeholder="Tell us about your travel plans, preferences, or any questions..." rows="4"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary closequery">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" id="startCancelProc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Send Enquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('page_resource/jquery.scrolling-tabs.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
    // Initialize tour main slider
    if($("#tour_main_slider").length) {
        var $tourSlider = $("#tour_main_slider").owlCarousel({
            items: 1,
            loop: true,
            margin: 0,
            nav: true,
            navText: ["‹", "›"],
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 450,
            animateOut: 'fadeOut',
            responsive: {
                0: { items: 1 },
                600: { items: 1 },
                1000: { items: 1 }
            }
        });
        
        // Thumbnail click handler
        $('.thumb-item').on('click', function() {
            var index = $(this).data('index');
            $tourSlider.trigger('to.owl.carousel', [index, 450]);
        });
        
        // Gallery image click for lightbox
        $('.gallery-image').on('click', function() {
            var index = $(this).closest('.item').data('index');
            if(typeof openLightbox === 'function') {
                openLightbox(index);
            }
        });
        
        // Thumbnail more button for lightbox
        $('.thumb-more').on('click', function() {
            var index = $(this).data('index');
            if(typeof openLightbox === 'function') {
                openLightbox(index);
            }
        });
    }

    // Send Query Button
    $('#sendquery').on('click', function(e) {
        e.preventDefault();
        
        // Clear previous messages
        $('.success_msg').html('');
        $('#tourenquiry')[0].reset();
        
        $('#subtqry').provabPopup({
            modalClose: true,
            closeClass: 'closequery',
            zIndex: 100005
        });
    });
    
    // Close button handler
    $('.closequery').on('click', function() {
        $('.success_msg').html('');
        $('#tourenquiry')[0].reset();
    });

    // Tab functionality
    $('.nav-tabs a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        // Remove active class from all tabs
        $('.nav-tabs li').removeClass('active');
        $('.nav-tabs a').removeClass('active');
        $('.tab-pane').removeClass('active');
        
        // Add active class to clicked tab
        $(this).parent().addClass('active');
        $(this).addClass('active');
        $(target).addClass('active');
    });

    // Rating functionality
    $(".rating input:radio").attr("checked", false);
    $('.rating input').click(function() {
        $(".rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });

    $('input:radio').change(function() {
        var userRating = this.value;
        var pkg_id = $('#pkg_id').val();
        var str = pkg_id + ',' + userRating;
        
        $.ajax({
            url: app_base_url + 'tours/package_user_rating',
            type: 'POST',
            data: 'rate=' + str,
            success: function(msg) {
                $('#msg_pak').show();
                $('#msg_pak').text('Thank you for rating this package').css('color', '#48bb78').fadeOut(3000);
            },
            error: function() {
                $('#msg_pak').show();
                $('#msg_pak').text('Error submitting rating').css('color', '#e53e3e').fadeOut(3000);
            }
        });
    });
    
    // Enquiry form submission
    $('#tourenquiry').on('submit', function(e) {
        e.preventDefault();
        
        var $submitBtn = $('#startCancelProc');
        var originalText = $submitBtn.html();
        
        // Add loading state
        $submitBtn.addClass('loading').prop('disabled', true);
        $submitBtn.html('<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle></svg> Sending...');
        
        $.ajax({
            url: app_base_url + 'index.php/tours/enquiry',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(msg) {
                $submitBtn.removeClass('loading').prop('disabled', false).html(originalText);
                
                if(msg.status == true) {
                    $('.success_msg').html('<div class="alert alert-success">✓ ' + msg.message + '</div>');
                    $('#tourenquiry')[0].reset();
                    
                    // Auto close after 2 seconds
                    setTimeout(function() {
                        $('.closequery').click();
                        $('.success_msg').html('');
                    }, 2500);
                } else {
                    $('.success_msg').html('<div class="alert alert-danger">✕ ' + (msg.message || 'Error submitting enquiry') + '</div>');
                }
            },
            error: function() {
                $submitBtn.removeClass('loading').prop('disabled', false).html(originalText);
                $('.success_msg').html('<div class="alert alert-danger">✕ Error submitting enquiry. Please try again.</div>');
            }
        });
    });

    // Input validation
    $('.alpha').on('keypress', function(e) {
        var regex = /^[a-zA-Z\s]+$/;
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (!regex.test(str)) {
            e.preventDefault();
            return false;
        }
    });

    $('.numeric').on('keypress', function(e) {
        var regex = /^[0-9+\-\s()]+$/;
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (!regex.test(str)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
</body>
</html>

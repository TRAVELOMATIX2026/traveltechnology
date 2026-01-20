
<?php
extract($_REQUEST);
$___favicon_ico = $GLOBALS['CI']->template->domain_images('favicon/favicon.ico');
$active_domain_modules = $GLOBALS['CI']->active_domain_modules;

$active_domain_modules_first = array(
    'VHCID1420613784' => 'flight',
    'VHCID1420613785' => 'flight+hotel',
    'VHCID1420613748' => 'hotel',
    'TMCAR1433491849' => 'car'
);

$active_domain_modules_second = array(
    'TMCID1524458882' => 'activitie',
    'VHCID1433496655' => 'transfer',
    'VHCID1433498322' => 'holiday'
);

$master_module_list = $GLOBALS['CI']->config->item('master_module_list');

if (empty($default_view)) {
    $default_view = $GLOBALS['CI']->uri->segment(1);
}

$verify_status = @$verification_success;
$default_active_tab = $default_view;
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta id="id" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">
    <meta name="keywords" content="<?= META_KEYWORDS ?>">
    <meta name="description" content="<?= META_DESCRIPTION ?>">
    <meta name="author" content="travelomatix">
    <link rel="shortcut icon" href="<?= $___favicon_ico ?>" type="image/x-icon">
    <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon">
    <title><?php echo HEADER_TITLE_SUFFIX; ?></title>
    <link rel="canonical" href="<?php echo base_url() . $this->canonicalTagContent; ?>">

    <meta itemprop="name" content="<?php echo HEADER_TITLE_SUFFIX; ?>">
    <meta itemprop="description" content="Discover unbeatable travel deals with <?php echo $this->entity_domain_name ?>! We offer flights, hotels, car rentals, transfers, activities, and tailored travel packages to suit your needs. Book now for the best prices and unforgettable experiences!">
    <meta itemprop="image" content="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo HEADER_TITLE_SUFFIX; ?>">
    <meta name="twitter:description" content="<?php echo $this->entity_domain_name ?> offers flights, hotels, transfers, activities, car rentals, and packages. and cheap, lowcost flights and hotel.">
    <meta name="twitter:site" content="@<?php echo $this->entity_domain_name ?>">
    <meta name="twitter:creator" content="@<?php echo $this->entity_domain_name ?>">
    <meta name="twitter:image:src" content="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>">

    <meta name="og:title" content="<?php echo HEADER_TITLE_SUFFIX; ?>">
    <meta name="og:description" content="<?php echo $this->entity_domain_name ?> offering flights, hotels, transfers, activities, car rentals, and comprehensive packages.">
    <meta name="og:image" content="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>">
    <meta name="og:url" content="<?php echo base_url() ?>">
    <meta name="og:site_name" content="<?php echo $this->entity_domain_name ?>">
    <meta name="og:type" content="website">

    <meta property="og:title" content="<?php echo HEADER_TITLE_SUFFIX; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo base_url() ?>">
    <meta property="og:image" content="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>">
    <meta property="og:site_name" content="<?php echo $this->entity_domain_name ?>">
    <meta property="og:description" content="Discover the world with <?php echo $this->entity_domain_name ?>! We provide a comprehensive range of services including flights, hotels, transfers, activities, car rentals, and tailored travel packages for both individual travelers and businesses globally.">

    <?php
    $GLOBALS['CI']->current_page->header_css_resource();
    Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('front_end.css'), 'media' => 'screen');
    $GLOBALS['CI']->current_page->header_js_resource();
    echo $GLOBALS['CI']->current_page->css();
    ?>
    <!-- Google Fonts - Manrope (Default) and Plus Jakarta Sans (Heading) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('theme_style.css?v=' . time()); ?>" rel="stylesheet" type="text/css" id="your_stylesheet">
    <!-- <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('media.css'); ?>" rel="stylesheet"> -->

    <script>
        var app_base_url = "<?= base_url() ?>";
        var tmpl_img_url = '<?= $GLOBALS['CI']->template->template_images(); ?>';
        <?php if (!empty($this->slideImageJson)) { ?>
            var slideImageJson = '<?php echo base64_encode(json_encode($this->slideImageJson)); ?>';
            var tmpl_imgs = JSON.parse(atob(slideImageJson));
        <?php } ?>
        var _lazy_content;
    </script>
    <?php if (isset($verify_status)) { ?>
        <script type="text/javascript">
            $(window).on('load', function() {
                $('#VerifyStatus').modal('show');
            });
        </script>
    <?php } ?>

    <script>
        function initMap() {
            var bounds = new google.maps.LatLngBounds();
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY&callback=initMap" type="text/javascript"></script>
</head>

<body class="<?php echo (isset($body_class) == false ? 'index_page' : $body_class) ?>">
    <!-- TMX Custom Icons Sprite -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <?php 
      // Get the full path to the SVG file
      $svg_file_path = SYSTEM_TEMPLATE_LIST_RELATIVE_PATH . '/template_v3/images/icons/tmx-icon.svg';
      if (file_exists($svg_file_path)) {
        $svg_content = file_get_contents($svg_file_path);
        // Extract only the symbol elements from the SVG
        if (preg_match('/<svg[^>]*>(.*?)<\/svg>/s', $svg_content, $matches)) {
          echo $matches[1];
        }
      }
      ?>
    </svg>
    
    <div id="show_log" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <?= $GLOBALS['CI']->template->isolated_view('general/login') ?>
                </div>
            </div>
        </div>
    </div>

    <div id="VerifyStatus" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.href = '<?= base_url(); ?>'"></button>
                </div>
                <div class="modal-body">
                    <h3 style="color:green;text-align: center;">Email Verification is Successfully Completed</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="allpagewrp">
        <header>
            <div class="topssec">
                <div class="container nopad">
                    <div class="space-between">
                        <a class="logo" href="<?= base_url() ?>">
                            <!-- <img class="ful_logo" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="Logo"> -->
                            <img class="ful_logo" src="<?php echo $GLOBALS['CI']->template->template_images('logo.png'); ?>" alt="Logo">
                            <img class="mobile_logo" src="<?php echo $GLOBALS['CI']->template->template_images('logo.png'); ?>" alt="Logo">
                        </a>
                        
                        <nav class="header-nav">
                            <ul class="nav-links">
                                <?php
                                // Flight link
                                if (in_array('VHCID1420613784', $active_domain_modules)) {
                                    $is_active = ($default_view == 'flight' || $default_view == 'VHCID1420613784');
                                    $link_class = $is_active ? 'nav-link active nav-link-blue' : 'nav-link nav-link-blue';
                                    ?>
                                    <li>
                                        <a href="<?= base_url() ?>flight" class="<?= $link_class ?>">Flight</a>
                                    </li>
                                    <?php
                                }
                                
                                // Hotel link
                                if (in_array('VHCID1420613748', $active_domain_modules)) {
                                    $is_active = ($default_view == 'hotel' || $default_view == 'VHCID1420613748');
                                    $link_class = $is_active ? 'nav-link active' : 'nav-link';
                                    ?>
                                    <li>
                                        <a href="<?= base_url() ?>hotel" class="<?= $link_class ?>">Hotel</a>
                                    </li>
                                    <?php
                                }

                                // Car Rental link
                                if (in_array('TMCAR1433491849', $active_domain_modules)) {
                                    $is_active = ($default_view == 'car' || $default_view == 'TMCAR1433491849');
                                    $link_class = $is_active ? 'nav-link active' : 'nav-link';
                                    ?>
                                    <li>
                                        <a href="<?= base_url() ?>car" class="<?= $link_class ?>">Car Rental</a>
                                    </li>
                                    <?php
                                }

                                // Sight Seeing link
                                if (in_array('TMCID1524458882', $active_domain_modules)) {
                                    $is_active = ($default_view == 'sightseeing' || $default_view == 'TMCID1524458882');
                                    $link_class = $is_active ? 'nav-link active' : 'nav-link';
                                    ?>
                                    <li>
                                        <a href="<?= base_url() ?>sightseeing" class="<?= $link_class ?>">Sight Seeing</a>
                                    </li>
                                    <?php
                                }

                                // Transfer link
                                if (in_array('VHCID1433496655', $active_domain_modules)) {
                                    $is_active = ($default_view == 'transfer' || $default_view == 'VHCID1433496655');
                                    $link_class = $is_active ? 'nav-link active' : 'nav-link';
                                    ?>
                                    <li>
                                        <a href="<?= base_url() ?>transfer" class="<?= $link_class ?>">Transfer</a>
                                    </li>
                                    <?php
                                }

                                // Holiday link
                                if (in_array('VHCID1433498322', $active_domain_modules)) {
                                    $is_active = ($default_view == 'holiday' || $default_view == 'VHCID1433498322');
                                    $link_class = $is_active ? 'nav-link active' : 'nav-link';
                                    ?>
                                    <li>
                                        <a href="<?= base_url() ?>holiday" class="<?= $link_class ?>">Holiday</a>
                                    </li>
                                    <?php
                                }
                                ?>
    
                            </ul>
                        </nav>
                        
                        <div class="ritsude">
                            <div class="right_menu">
                                <div class="main_sec">
                                    <div class="sectns customer-support-section">
                                        <?php 
                                        $support_no = $this->entity_domain_phone_code . '-' . trim($this->entity_domain_phone);
                                        $support_no_tel = $this->entity_domain_phone_code . trim($this->entity_domain_phone);
                                        $support_email = !empty($this->entity_domain_mail) ? $this->entity_domain_mail : 'support@travelomatix.com';
                                        ?>
                                        <div class="customer-support-dropdown">
                                            <a class="phnumr customer-support-link dropdown-toggle" href="javascript:void(0);" role="button" id="supportDropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                                <div class="customer-support-icon">
                                                    <div class="support-icon-wrapper">
                                                    <?php echo render_tmx_icon('tmx-icon-phone-header', 'tmx-icon-md'); ?>
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
                                                        <?php echo render_tmx_icon('tmx-icon-call-header', 'tmx-icon-md'); ?>
                                                        <span class="support-item-label">Call Support</span>
                                                    </div>
                                                    <a href="tel:<?= $support_no_tel ?>" class="support-item-link">
                                                        Tel : <?= $support_no ?>
                                                    </a>
                                                </li>
                                                <li class="support-dropdown-item">
                                                    <div class="support-item-header">
                                                        <?php echo render_tmx_icon('tmx-icon-mail-header', 'tmx-icon-md'); ?>
                                                        <span class="support-item-label">Mail Support</span>
                                                    </div>
                                                    <a href="mailto:<?= $support_email ?>" class="support-item-link">
                                                        <?= $support_email ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="sidebtn flagss currency-selector currency-dropdown-wrapper">
                                        <a class="topa dropdown-toggle currency-dropdown-toggle" href="javascript:void(0);" role="button" id="currencyDropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                            <div class="reglognorml">
                                                <div class="flag_images">
                                                    <?php
                                                    $curr = get_application_currency_preference();
                                                    echo '<span class="curncy_img sprte curr_flagd ' . strtolower(substr($curr, 0, 2)) . ' ' . strtolower($curr) . '"></span>';
                                                    ?>
                                                </div>
                                                <div class="flags">
                                                    <?php echo $curr; ?>
                                                </div>
                                                <i class="material-icons currency-arrow-icon">expand_more</i>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu exploreul explorecntry logdowndiv flag_images currency-dropdown-menu" aria-labelledby="currencyDropdown" role="menu">
                                            <?= $this->template->isolated_view('utilities/multi_currency') ?>
                                        </ul>
                                    </div>

                                    <div class="sidebtn language-selector language-dropdown-wrapper">
                                        <?php
                                        $current_lang = $this->session->userdata('lang') ? $this->session->userdata('lang') : 'en';
                                        $lang_name = ($current_lang == 'ar') ? 'العربية' : 'English';
                                        $lang_code = ($current_lang == 'ar') ? 'AR' : 'EN';
                                        ?>
                                        <a class="topa language-dropdown-toggle" href="javascript:void(0);" role="button" id="languageDropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                            <div class="reglognorml">
                                                <div class="language-icon">
                                                    <?php echo render_tmx_icon('tmx-icon-language', 'tmx-icon-sm'); ?>
                                                </div>
                                                <div class="language-code">
                                                    <?php echo $lang_code; ?>
                                                </div>
                                                <i class="material-icons language-arrow-icon">keyboard_arrow_down</i>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu language-dropdown-menu" aria-labelledby="languageDropdown" role="menu">
                                            <li class="language-dropdown-item">
                                                <a href="javascript:void(0);" class="language-item-link" data-lang="en">
                                                    <div class="language-item-icon">
                                                        <span class="language-flag">EN</span>
                                                    </div>
                                                    <div class="language-item-content">
                                                        <span class="language-item-label">English</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="language-dropdown-item">
                                                <a href="javascript:void(0);" class="language-item-link" data-lang="ar">
                                                    <div class="language-item-icon">
                                                        <span class="language-flag">AR</span>
                                                    </div>
                                                    <div class="language-item-content">
                                                        <span class="language-item-label">العربية</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="sidebtn theme-toggle-wrapper">
                                        <div class="theme-icons" id="theme-toggle-icons" role="button" aria-label="Toggle theme">
                                            <i class="bi bi-sun theme-icon-sun"></i>
                                            <i class="bi bi-moon theme-icon-moon"></i>
                                        </div>
                                    </div>

                                    <?php if (is_logged_in_user() == false) { ?>
                                        <!-- <div class="sidebtn register-btn-wrapper">
                                            <a class="topa register-btn" href="<?= base_url() ?>auth/register">
                                                <div class="reglog register-button">
                                                    <div class="userorlogin register-text">Register</div>
                                                </div>
                                            </a>
                                        </div> -->
                                        <div class="sidebtn login_border signin-btn-wrapper signin-dropdown-wrapper">
                                            <a class="topa logindown signin-dropdown-toggle" href="javascript:void(0);" role="button" id="signinDropdown">
                                                <div class="reglog signin-button">
                                                    <div class="userorlogin signin-text">Login or Signup</div>
                                                </div>
                                            </a>
                                            <ul class="dropdown-menu signin-dropdown-menu" aria-labelledby="signinDropdown" role="menu">
                                                <li class="signin-dropdown-item">
                                                    <a href="javascript:void(0);" class="signin-item-link" data-bs-toggle="modal" data-bs-target="#show_log">
                                                        <div class="signin-item-icon">
                                                            <i class="material-icons">person</i>
                                                        </div>
                                                        <div class="signin-item-content">
                                                            <span class="signin-item-label">Customer Login</span>
                                                            <span class="signin-item-subtext">Login & check bookings</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="signin-dropdown-item">
                                                    <a href="<?= base_url() ?>" class="signin-item-link">
                                                        <div class="signin-item-icon">
                                                            <i class="material-icons">assignment</i>
                                                        </div>
                                                        <div class="signin-item-content">
                                                            <span class="signin-item-label">Manage Booking</span>
                                                            <span class="signin-item-subtext">View and manage your bookings</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="signin-dropdown-item">
                                                    <a href="<?= base_url() ?>agent" class="signin-item-link">
                                                        <div class="signin-item-icon">
                                                            <i class="material-icons">headset_mic</i>
                                                        </div>
                                                        <div class="signin-item-content">
                                                            <span class="signin-item-label">Agent Login</span>
                                                            <span class="signin-item-subtext">Login your agent account</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="signin-dropdown-item">
                                                    <a href="<?= base_url() ?>user/my_booking" class="signin-item-link">
                                                        <div class="signin-item-icon">
                                                            <i class="material-icons">luggage</i>
                                                        </div>
                                                        <div class="signin-item-content">
                                                            <span class="signin-item-label">My Booking</span>
                                                            <span class="signin-item-subtext">Manage your bookings here</span>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } else { ?>
                                        <div class="sidebtn login_border">
                                            <a class="topa logindown dropdown-toggle" data-bs-toggle="dropdown">
                                                <div class="reglog">
                                                    <div class="userimage">
                                                        <?php
                                                        if (is_logged_in_user() == true) {
                                                            if (empty($GLOBALS['CI']->entity_image) == false) {
                                                                $profile_image = $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image);
                                                            } else {
                                                                $profile_image = $GLOBALS['CI']->template->domain_images('user.png');
                                                            }
                                                            ?>
                                                            <img src="<?php echo $profile_image; ?>" alt="image">
                                                        <?php
                                                        } else {
                                                            echo '<style> .userimage { background: linear-gradient(90deg, rgba(42,37,207,1) 0%, rgba(25,77,172,1) 35%, rgba(0,212,255,1) 100%) !important; } </style>';
                                                            echo '<b class="userimgName">' . substr($this->entity_first_name, 0, 1) . '</b>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php if (is_logged_in_user() == false) { ?>
                                                        <div class="userorlogin login">My Account</div>
                                                    <?php } else { ?>
                                                        <div class="userorlogin login">
                                                            <?php echo 'Hi ' . $this->entity_first_name; ?>
                                                            <b class="caret cartdown"></b>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu mysign exploreul logdowndiv">
                                                <div class="signdiv">
                                                    <div class="clearfix">
                                                        <ul>
                                                            <li><a href="<?= base_url() ?>user/profile/<?= @$GLOBALS['CI']->name ?>">My Account</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="<?= base_url() . 'auth/change_password' ?>">Change Password</a></li>
                                                            <li class="divider"></li>
                                                            <li><a class="user_logout_buttonn" href="javascript:;" onclick="do_logout()">Logout</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <!-- Burger Menu Button -->
                            <button class="burger-menu-btn" id="burgerMenuBtn" aria-label="Toggle menu">
                                <span class="burger-line"></span>
                                <span class="burger-line"></span>
                                <span class="burger-line"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Mobile Slide-In Menu -->
        <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
        <div class="mobile-menu-panel" id="mobileMenuPanel">
            <div class="mobile-menu-header">
                <a class="mobile-menu-logo" href="<?= base_url() ?>">
                    <img class="ful_logo" src="<?php echo $GLOBALS['CI']->template->template_images('logo.png'); ?>" alt="Logo">
                    <img class="mobile_logo" src="<?php echo $GLOBALS['CI']->template->template_images('logo.png'); ?>" alt="Logo">
                </a>
                <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Close menu">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <nav class="mobile-menu-nav">
                <ul class="mobile-nav-links">
                    <?php
                    // Flight link
                    if (in_array('VHCID1420613784', $active_domain_modules)) {
                        $is_active = ($default_view == 'flight' || $default_view == 'VHCID1420613784');
                        $link_class = $is_active ? 'mobile-nav-link active' : 'mobile-nav-link';
                        ?>
                        <li>
                            <a href="<?= base_url() ?>flight" class="<?= $link_class ?>">
                                <i class="material-icons">flight</i>
                                <span>Flight</span>
                            </a>
                        </li>
                        <?php
                    }
                    
                    // Hotel link
                    if (in_array('VHCID1420613748', $active_domain_modules)) {
                        $is_active = ($default_view == 'hotel' || $default_view == 'VHCID1420613748');
                        $link_class = $is_active ? 'mobile-nav-link active' : 'mobile-nav-link';
                        ?>
                        <li>
                            <a href="<?= base_url() ?>hotel" class="<?= $link_class ?>">
                                <i class="material-icons">hotel</i>
                                <span>Hotel</span>
                            </a>
                        </li>
                        <?php
                    }

                    // Car Rental link
                    if (in_array('TMCAR1433491849', $active_domain_modules)) {
                        $is_active = ($default_view == 'car' || $default_view == 'TMCAR1433491849');
                        $link_class = $is_active ? 'mobile-nav-link active' : 'mobile-nav-link';
                        ?>
                        <li>
                            <a href="<?= base_url() ?>car" class="<?= $link_class ?>">
                                <i class="material-icons">directions_car</i>
                                <span>Car Rental</span>
                            </a>
                        </li>
                        <?php
                    }

                    // Sight Seeing link
                    if (in_array('TMCID1524458882', $active_domain_modules)) {
                        $is_active = ($default_view == 'sightseeing' || $default_view == 'TMCID1524458882');
                        $link_class = $is_active ? 'mobile-nav-link active' : 'mobile-nav-link';
                        ?>
                        <li>
                            <a href="<?= base_url() ?>sightseeing" class="<?= $link_class ?>">
                                <i class="material-icons">camera_alt</i>
                                <span>Sight Seeing</span>
                            </a>
                        </li>
                        <?php
                    }

                    // Transfer link
                    if (in_array('VHCID1433496655', $active_domain_modules)) {
                        $is_active = ($default_view == 'transfer' || $default_view == 'VHCID1433496655');
                        $link_class = $is_active ? 'mobile-nav-link active' : 'mobile-nav-link';
                        ?>
                        <li>
                            <a href="<?= base_url() ?>transfer" class="<?= $link_class ?>">
                                <i class="material-icons">airport_shuttle</i>
                                <span>Transfer</span>
                            </a>
                        </li>
                        <?php
                    }

                    // Holiday link
                    if (in_array('VHCID1433498322', $active_domain_modules)) {
                        $is_active = ($default_view == 'holiday' || $default_view == 'VHCID1433498322');
                        $link_class = $is_active ? 'mobile-nav-link active' : 'mobile-nav-link';
                        ?>
                        <li>
                            <a href="<?= base_url() ?>holiday" class="<?= $link_class ?>">
                                <i class="material-icons">beach_access</i>
                                <span>Holiday</span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                
                <!-- Mobile Menu Actions: Theme Toggle, Register, Signin -->
                <div class="mobile-menu-actions">
                    <!-- Theme Toggle -->
                    <div class="mobile-menu-action-item" id="mobile-theme-toggle-icons" role="button" aria-label="Toggle theme">
                        <div class="theme-icons mobile-theme-toggle">
                            <i class="bi bi-sun theme-icon-sun"></i>
                            <i class="bi bi-moon theme-icon-moon"></i>
                        </div>
                        <span>Theme</span>
                    </div>
                    
                    <?php if (is_logged_in_user() == false) { ?>
                        <!-- Register Button -->
                        <a href="<?= base_url() ?>auth/register" class="mobile-menu-action-item mobile-register-btn">
                            <i class="material-icons">person_add</i>
                            <span>Register</span>
                        </a>
                        
                        <!-- Signin Button -->
                        <a href="javascript:void(0);" class="mobile-menu-action-item mobile-signin-btn" data-bs-toggle="modal" data-bs-target="#show_log">
                            <i class="material-icons">login</i>
                            <span>Signin</span>
                        </a>
                    <?php } else { ?>
                        <!-- User Account (if logged in) -->
                        <a href="javascript:void(0);" class="mobile-menu-action-item mobile-user-account" data-bs-toggle="dropdown">
                            <i class="material-icons">account_circle</i>
                            <span>My Account</span>
                        </a>
                    <?php } ?>
                </div>
            </nav>
        </div>

        <div class="clearfix"></div>

        <div class="container-fluid utility-nav clearfix">
            <?php
            if ($this->session->flashdata('message') != "") {
                $message = $this->session->flashdata('message');
                $msg_type = $this->session->flashdata('type');
                $show_btn = TRUE;
                $override_app_msg = ($this->session->flashdata('override_app_msg') != "") ? $this->session->flashdata('override_app_msg') : FALSE;
                echo get_message($message, $msg_type, $show_btn, $override_app_msg);
            }
            ?>
        </div>

        <div class="fromtopmargin">
            <?= $body ?>
        </div>

        <div class="clearfix"></div>

        <footer class="modern-footer">
            <!-- Top Section -->
            <div class="footer-top-section">
                <div class="container">
                    <div class="row footer-top-row">
                        <!-- Contact Information -->
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-contact-col">
                            <div class="footer-contact-info">
                                <div class="footer-contact-item">
                                    <i class="material-icons">location_on</i>
                                    <div class="contact-text">
                                        <span class="contact-label">Address:</span>
                                        <p><?= $this->application_address ?></p>
                                    </div>
                                </div>
                                <div class="footer-contact-item">
                                    <i class="material-icons">email</i>
                                    <div class="contact-text">
                                        <a href="mailto:<?= $this->entity_domain_mail ?>" class="contact-link">
                                            <?= $this->entity_domain_mail ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="footer-contact-item">
                                    <i class="material-icons">phone</i>
                                    <div class="contact-text">
                                        <?php 
                                        $support_no = $this->entity_domain_phone_code . trim($this->entity_domain_phone);
                                        ?>
                                        <a href="tel:<?= $support_no ?>" class="contact-link">
                                            <?= $support_no ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="col-lg-2 col-md-6 col-sm-12 footer-services-col">
                            <h4 class="footer-section-title">Services</h4>
                            <ul class="footer-services-list">
                                <?php
                                // Combine both module mappings
                                $all_module_mappings = array_merge($active_domain_modules_first, $active_domain_modules_second);
                                
                                // Service labels mapping
                                $service_labels = array(
                                    'flight' => 'Flights',
                                    'flight+hotel' => 'Flight + Hotel',
                                    'hotel' => 'Hotels',
                                    'car' => 'Car Rental',
                                    'activitie' => 'Sight Seeing',
                                    'transfer' => 'Transfers',
                                    'holiday' => 'Holidays'
                                );
                                
                                // Build services list from active modules
                                foreach ($active_domain_modules as $module_id) {
                                    if (isset($all_module_mappings[$module_id])) {
                                        $module_key = $all_module_mappings[$module_id];
                                        $module_url = ($module_key == 'activitie') ? 'sightseeing' : $module_key;
                                        $module_label = isset($service_labels[$module_key]) ? $service_labels[$module_key] : ucfirst($module_key);
                                        echo '<li><a href="' . base_url() . $module_url . '">' . $module_label . '</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Quick Links -->
                        <div class="col-lg-2 col-md-6 col-sm-12 footer-quicklinks-col">
                            <h4 class="footer-section-title">Quick Links</h4>
                            <ul class="footer-services-list">
                                <li><a href="<?= base_url() ?>">Home</a></li>
                                <?php
                                $cond = array('page_status' => ACTIVE);
                                $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                $quick_links = ['about-us' => 'About Us', 'faq' => 'FAQ', 'contact-us' => 'Contact Us', 'blog' => 'Blog'];
                                $quick_link_count = 0;
                                foreach ($cms_data['data'] as $values) {
                                    if (isset($quick_links[$values['page_label']]) && $quick_link_count < 4) {
                                        echo '<li><a href="' . base_url() . $values['page_label'] . '">' . $quick_links[$values['page_label']] . '</a></li>';
                                        $quick_link_count++;
                                    }
                                }
                                if ($quick_link_count == 0) {
                                    echo '<li><a href="' . base_url() . '">About Us</a></li>';
                                    echo '<li><a href="' . base_url() . '">FAQ</a></li>';
                                    echo '<li><a href="' . base_url() . '">Contact</a></li>';
                                    echo '<li><a href="' . base_url() . '">Blog</a></li>';
                                }
                                ?>
                            </ul>
                        </div>

                        <!-- Newsletter Subscription -->
                        <div class="col-lg-5 col-md-6 col-sm-12 footer-newsletter-col">
                            <h4 class="footer-section-title">Subscribe For Newsletter</h4>
                            <div class="newsletter-form-wrapper">
                                <div class="newsletter-input-group">
                                    <i class="material-icons newsletter-icon">email</i>
                                    <input type="email" id="exampleInputEmail1" class="newsletter-input" placeholder="Enter your email">
                                    <button class="newsletter-subscribe-btn" onclick="check_newsletter()" type="button">Subscribe</button>
                                </div>
                                <p class="newsletter-disclaimer">No ads. No trails. No commitments.</p>
                            </div>
                            <span class="msgNewsLetterSubsc13" style="font-size: 13px; color: #f00; display: none;"><b>You must accept the terms and conditions before subscribing.</b></span>
                            <span class="msgNewsLetterSubsc12" style="font-size: 13px; color: #f00; display: none;"><b>Please Provide Valid Email ID</b></span>
                            <span class="succNewsLetterSubsc" style="font-size: 13px; color: #0f8811; display: none;"><b>Thank you for subscribe.We will be in touch with newsletter.</b></span>
                            <span class="msgNewsLetterSubsc" style="font-size: 13px; color: #f00; display: none;"><b>You are already subscribed to Newsletter feed.</b></span>
                            <span class="msgNewsLetterSubsc1" style="font-size: 13px; color: #0f9784; display: none;"><b>Activated to Newsletter feed.Thank you</b></span>
                            
                            <!-- Download Mobile App -->
                            <div class="footer-mobile-app">
                                <h5 class="mobile-app-title">Download Our Mobile App</h5>
                                <p class="mobile-app-description">Book on the go with our mobile app. Get exclusive deals and manage your bookings anytime, anywhere.</p>
                                <div class="mobile-app-buttons">
                                    <a href="#" class="app-store-btn" target="_blank" title="Download on App Store">
                                        <i class="bi bi-apple"></i>
                                        <div class="app-btn-text">
                                            <span class="app-btn-small">Download on the</span>
                                            <span class="app-btn-large">App Store</span>
                                        </div>
                                    </a>
                                    <a href="#" class="play-store-btn" target="_blank" title="Get it on Google Play">
                                        <i class="bi bi-google-play"></i>
                                        <div class="app-btn-text">
                                            <span class="app-btn-small">Get it on</span>
                                            <span class="app-btn-large">Google Play</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Section -->
            <div class="footer-middle-section">
                <div class="container">
                    <div class="row footer-middle-row">
                        <!-- Follow Us -->
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-social-col">
                            <h4 class="footer-section-title">Follow us</h4>
                            <div class="footer-social-icons">
                                <?php
                                $temp = $this->custom_db->single_table_records('social_links');
                                // Instagram
                                if (isset($temp['data']['4']) && $temp['data']['4']['status'] == ACTIVE) { ?>
                                    <a href="<?php echo $temp['data']['4']['url_link']; ?>" target="_blank" class="social-icon">
                                        <i class="material-icons">camera_alt</i>
                                    </a>
                                <?php }
                                // Facebook
                                if (isset($temp['data'][0]) && $temp['data'][0]['status'] == ACTIVE) { ?>
                                    <a href="<?php echo $temp['data'][0]['url_link']; ?>" target="_blank" class="social-icon">
                                        <i class="material-icons">facebook</i>
                                    </a>
                                <?php }
                                // Twitter/X
                                if (isset($temp['data']['3']) && $temp['data']['3']['status'] == ACTIVE) { ?>
                                    <a href="<?php echo $temp['data']['3']['url_link']; ?>" target="_blank" class="social-icon">
                                        <i class="material-icons">alternate_email</i>
                                    </a>
                                <?php }
                                // YouTube
                                if (isset($temp['data'][1]) && $temp['data'][1]['status'] == ACTIVE) { ?>
                                    <a href="<?php echo $temp['data'][1]['url_link']; ?>" target="_blank" class="social-icon">
                                        <i class="material-icons">play_circle</i>
                                    </a>
                                <?php } ?>
                            </div>
                            <p class="footer-social-text">Stay connected with us on social media for the latest updates and travel tips.</p>
                        </div>

                        <!-- Need Help? Call Us -->
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-phone-col">
                            <h4 class="footer-section-title">Need Help? Call Us</h4>
                            <div class="footer-phone-info">
                                <i class="material-icons">phone</i>
                                <div class="phone-text">
                                    <span class="phone-label">24/7 Customer Support</span>
                                    <?php 
                                    $support_no = $this->entity_domain_phone_code . trim($this->entity_domain_phone);
                                    ?>
                                    <a href="tel:<?= $support_no ?>" class="phone-number"><?= $support_no ?></a>
                                </div>
                            </div>
                           
                        </div>

                       
                        <!-- Payments -->
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-payments-col">
                            <h4 class="footer-section-title">Secure Payments</h4>
                            <div class="footer-payment-logos">
                                <div class="payment-logo"><img src="<?php echo $GLOBALS['CI']->template->template_images('paypal.svg'); ?>" alt="PayPal"> <span>PayPal</span></div>
                                <div class="payment-logo"><img src="<?php echo $GLOBALS['CI']->template->template_images('stripe.svg'); ?>" alt="Stripe"> <span>Stripe</span></div>
                                <div class="payment-logo"><img src="<?php echo $GLOBALS['CI']->template->template_images('visa.svg'); ?>" alt="Visa"> <span>Visa</span></div>
                                <div class="payment-logo"><img src="<?php echo $GLOBALS['CI']->template->template_images('mc.svg'); ?>" alt="Mastercard"> <span>Mastercard</span></div>
                                <div class="payment-logo"><img src="<?php echo $GLOBALS['CI']->template->template_images('amex.svg'); ?>" alt="Amex"> <span>Amex</span></div>
                               
                            </div>
                      
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="footer-bottom-section">
                <div class="container">
                    <div class="footer-bottom-content">
                        <div class="footer-copyright">
                            ©<?= date('Y') ?> <?= $this->entity_domain_name ?>. All rights reserved.
                        </div>
                        <div class="footer-legal-links">
                            <?php
                            $cond = array('page_status' => ACTIVE);
                            $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                            $legal_pages = ['terms-conditions' => 'Terms', 'privacy-policy' => 'Privacy policy', 'legal-notice' => 'Legal notice', 'accessibility' => 'Accessibility'];
                            $links = [];
                            foreach ($cms_data['data'] as $values) {
                                if (isset($legal_pages[$values['page_label']])) {
                                    $links[] = '<a href="' . base_url() . $values['page_label'] . '">' . $legal_pages[$values['page_label']] . '</a>';
                                }
                            }
                            // Add default links if CMS pages don't exist
                            if (empty($links)) {
                                $links = [
                                    '<a href="' . base_url() . 'terms-conditions">Terms</a>',
                                    '<a href="' . base_url() . 'privacy-policy">Privacy policy</a>',
                                    '<a href="' . base_url() . 'legal-notice">Legal notice</a>',
                                    '<a href="' . base_url() . 'accessibility">Accessibility</a>'
                                ];
                            }
                            echo implode(' ', $links);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <?php
    Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/datepicker.js'), 'defer' => 'defer');
    Provab_Page_Loader::load_core_resource_files();
    $GLOBALS['CI']->current_page->footer_js_resource();
    echo $GLOBALS['CI']->current_page->js();
    ?>
    <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('modernizr.custom.js'); ?>" defer></script>
    <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('dark-mode.js'); ?>" defer></script>
    <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('template_v3.js'); ?>"></script>
</body>
</html>

<?php
error_reporting(0);
$___favicon_ico = $GLOBALS['CI']->template->domain_images('favicon.ico');

$active_domain_modules = $GLOBALS['CI']->active_domain_modules;
$master_module_list = $GLOBALS['CI']->config->item('master_module_list');
if (empty($default_view)) {
	$default_view = $GLOBALS['CI']->uri->segment(1);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?=$___favicon_ico?>" type="image/x-icon">
	<link rel="icon" href="<?=$___favicon_ico?>" type="image/x-icon">
    <title><?php echo get_app_message( 'AL001'). ' '.HEADER_TITLE_SUFFIX; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="https://fonts.googleapis.com/css?family=Lato|Source+Sans+Pro" rel="stylesheet">
    <link href="<?php echo $this->template->template_css_dir('AdminLTE.min.css')?>" rel="stylesheet" type="text/css" />


<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo $this->template->template_css_dir('skins-common.css')?>" rel="stylesheet" type="text/css" />

    <!-- Ultra Modern Header Styles -->
    <link href="<?php echo $this->template->template_css_dir('header.css')?>?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     
     <!-- Ultra Modern Dropdown Styles -->
     <link href="<?php echo $this->template->template_css_dir('dropdown.css')?>?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     
     <!-- Beautiful Dashboard Styles -->
     <link href="<?php echo $this->template->template_css_dir('dashboard.css')?>?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('uicons-regular-rounded.css'); ?>" rel="stylesheet" />
    
    <?php //Loading Common CSS and JS
			$this->current_page->header_css_resource();
			$this->current_page->header_js_resource();
			echo $GLOBALS ['CI']->current_page->css ();
		?>
	<script>
	var app_base_url = "<?=base_url()?>";
	var tmpl_img_url = '<?=$GLOBALS['CI']->template->template_images(); ?>';
	var _lazy_content;
	</script>
</head>

<style>
  .squaredThree input[type="checkbox"] {display: none;}
  .squaredThree label { margin-left: 0px !important;}
  .splmodify{padding: 0px !important; background:none !important;}
  .content-wrapper{ height: 100% !important; min-height:inherit !important;}
</style>

  <body class="skin-black-light sidebar-collapse">
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
    
  <noscript><img src="<?php echo $GLOBALS['CI']->template->template_images('default_loading.gif'); ?>"
			class="img-fluid mx-auto d-block"></img></noscript>


	<!-- HEADER starts -->	
	<?php 
	if (is_logged_in_user()) {
	?>

  <div class="wrapper d-flex">

  			<!-- MENU starts -->
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
   
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <?php echo $GLOBALS['CI']->template->isolated_view('menu/magical_menu.php');?>
        </section>
        <!-- /.sidebar -->
      </aside>
			<!-- MENU ends -->
      <div class="content-wrapper" style="min-height: 100vh;">
      <header class="main-header">
      <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-bs-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
        
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
              
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav agent_menu">
            <?php
			foreach ($master_module_list as $k => $v) {
				if (in_array($k, $active_domain_modules)) {
				?>
				<li class="normal_srchreali <?=((@$default_view == $k || $default_view == $v) ? 'bg-blue' : '')?>"><a href="<?php echo base_url()?>menu/dashboard/<?php echo ($v)?>?default_view=<?php echo $k?>"><?php echo render_tmx_icon(get_arrangement_tmx_icon($k), 'tmx-icon-md'); ?> <span class="none_lables"><?php echo ucfirst($v)?></span></a></li>
				<?php
				}
    }
			?>
      </ul>

      <span class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" id="get_event_notification">
                      <i class="material-icons">notifications</i>
                      <span class="badge bg-warning" id="active_notifications_count"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li>
                      <?php 
                      $notification_loading_image	 = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>';
                      ?>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu" id="notification_dropdown"><?=$notification_loading_image?></ul>
                      </li>
                      <li class="footer hide" id="view_all_notification"><a href="<?=base_url()?>index.php/utilities/notification_list">View more</a></li>
                    </ul>
                  </span>

      <?php

              if (is_domain_user()) {
              // debug(agent_current_application_balance());exit; ?>
                <div class="balane_msgs">
                  
                  <!-- Active Notification Ends -->
                  <a href="<?php echo base_url()?>management/b2b_balance_manager">
                  <strong>
                  <span>Balance</span>
                  <span class="crncy"><?php $balance = agent_current_application_balance(); echo agent_base_currency().' '.number_format($balance['value'], '2');?></span>
                  </strong>
                  <strong>
                  <span>Credit Limit</span>
                  <span class="crncy"> <?php echo agent_base_currency().' '.number_format($balance['credit_limit'], '2');?></span>
                  </strong>
                  <strong>
                  <span>Due Amount</span>
                  <span class="crncy"> <?php echo agent_base_currency().' '.number_format($balance['due_amount'], '2');?></span>
                  </strong></a>
                </div>
              <?php
                 }
              ?>

           
      <ul class="nav navbar-nav">
              <li class="dropdown user user-menu profile-dropdown">
                <a href="#" class="dropdown-toggle profile-toggle" data-bs-toggle="dropdown">
                  <img src="<?=(empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image) : $GLOBALS['CI']->template->template_images('face.png'))?>" class="user-image" alt="User Image"/>
                  <div class="user-name-container">
                    <span class="user-agency-name"><?php echo $GLOBALS['CI']->agency_name?></span>
                    <span class="user-id-badge"><?php echo $GLOBALS['CI']->entity_uuid?></span>
                  </div>
                  <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>
                <ul class="dropdown-menu profile-dropdown-menu">
                  <!-- User Profile Header -->
                  <li class="user-header profile-header">
                    <div class="profile-header-content d-flex align-items-center gap-2">
                      <img src="<?=(empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image) : $GLOBALS['CI']->template->template_images('face.png'))?>" class="profile-avatar" alt="User Image" />
                      <div class="profile-info">
                        <h5 class="profile-name"><?=$GLOBALS['CI']->entity_name ?></h5>
                      </div>
                    </div>
                  </li>
                  
                  <!-- Contact Information -->
                  <li class="user-body profile-contacts">
                    <div class="contact-item">
                      <i class="bi bi-telephone-fill"></i>
                      <div class="contact-details">
                        <span class="contact-value"><?=@$GLOBALS['CI']->entity_domain_phone?></span>
                      </div>
                    </div>
                    <div class="contact-item">
                      <i class="bi bi-envelope-fill"></i>
                      <div class="contact-details">
                        <span class="contact-value"><?=@$GLOBALS['CI']->entity_domain_mail?></span>
                      </div>
                    </div>
                  </li>
                  
                  <!-- Menu Actions -->
                  <li class="profile-actions">
                    <a href="<?php echo base_url().'user/account?uid='.intval($GLOBALS['CI']->entity_user_id); ?>" class="profile-action-btn">
                      <i class="bi bi-person-circle"></i>
                      <span>Profile</span>
                    </a>
                    <a href="<?php echo base_url().'user/change_password?uid='.intval($GLOBALS['CI']->entity_user_id);?>" class="profile-action-btn">
                      <i class="bi bi-shield-lock"></i>
                      <span>Change Password</span>
                    </a>
                    <a class="profile-action-btn profile-logout" href="javascript:;" onclick="do_logout()">
                      <i class="bi bi-box-arrow-right"></i>
                      <span>Logout</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
            </ul>
          </div>

        </nav>
      </header>
			<!-- HEADER ends -->
	  <div class="clearfix"></div>

	
			<!-- BODY CONTENT starts -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper p-4" style="min-height: 100vh;">
        <!-- Main content -->
        <section class="content">
        <!-- UTILITY NAV -->
		<div class="container-fluid utility-nav clearfix">
			<!-- ROW --> <?php 
				if($this->session->flashdata('message')!="") {
					$message=$this->session->flashdata('message');
					$msg_type=$this->session->flashdata('type');
					$show_btn=TRUE;
					if($this->session->flashdata('override_app_msg')!="") {
						$override_app_msg = $this->session->flashdata('override_app_msg');
					} else {
						$override_app_msg = FALSE;
					}
				echo get_message($message, $msg_type, $show_btn, $override_app_msg);
				} ?> <!-- /ROW -->
		</div>
          <!-- Info boxes -->
          <div class="row_container">
          	<?php echo $body ?>
          </div><!-- /.row -->
        </section><!-- /.content -->

        	<!-- FOOTER starts -->
      <footer class="main-footer">
        <div class="float-end hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; <?php echo date('Y')?></strong> All rights reserved.
      </footer>
		<!-- FOOTER ends -->
      </div><!-- /.content-wrapper -->
			<!-- BODY CONTENT ends -->

		
     </div>
	

    <?php
		//END IF - PAGE After LOGIN
		} else {
			//Page without LOGIN
			echo $body;
		}
		?>
	<?php
	Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/datepicker.js'), 'defer' => 'defer');
  Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('select2.min.js'), 'defer' => 'defer');

	Provab_Page_Loader::load_core_resource_files();
  
		// Loading Common CSS and JS
		$GLOBALS ['CI']->current_page->footer_js_resource ();
		echo $GLOBALS ['CI']->current_page->js ();
		?>
	<!-- Menu Dropdown Fix Script -->
	<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('menu-dropdown-fix.js'); ?>?v=<?php echo time(); ?>"></script>
    <script>
        function initMap() {
            var bounds = new google.maps.LatLngBounds();
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY&callback=initMap" type="text/javascript"></script>
	<script src='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fastclick/fastclick.min.js' defer></script>
    <!-- Sparkline -->
    <script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/sparkline/jquery.sparkline.min.js" type="text/javascript" defer></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="<?php echo SYSTEM_RESOURCE_LIBRARY;?>/slimScroll/jquery.slimscroll.min.js" type="text/javascript" defer></script>

<script>
    $(document).ready(function() {
    $(window).scroll(function() {
        $(window).scrollTop() > 40 ? ($("header").addClass("fixed"), $(".content-wrapper").addClass("set_up")) : ($("header").removeClass("fixed"), $(".content-wrapper").removeClass("set_up"))
    })
});
    function do_logout(){
    $.get(app_base_url + "index.php/auth/ajax_logout", function(e) {
            location.href = '<?=base_url();?>';
        })
    }
  </script>

  <script>
      
 


    // Theme Toggle Functionality
    $(document).ready(function() {
      // Get current theme from localStorage or default to 'light'
      const currentTheme = localStorage.getItem('theme') || 'light';
      const body = $('body');
      
      // Apply saved theme
      if (currentTheme === 'dark') {
        body.addClass('dark-theme');
        body.removeClass('light-theme');
        $('#themeToggle .theme-icon-light').hide();
        $('#themeToggle .theme-icon-dark').show();
        $('#themeToggle .theme-toggle-text').text('Light Mode');
      } else {
        body.addClass('light-theme');
        body.removeClass('dark-theme');
        $('#themeToggle .theme-icon-light').show();
        $('#themeToggle .theme-icon-dark').hide();
        $('#themeToggle .theme-toggle-text').text('Dark Mode');
      }
      
      // Theme toggle button click handler
      $('#themeToggle').on('click', function() {
        body.toggleClass('dark-theme');
        body.toggleClass('light-theme');
        
        const isDark = body.hasClass('dark-theme');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        
        if (isDark) {
          $('#themeToggle .theme-icon-light').hide();
          $('#themeToggle .theme-icon-dark').show();
          $('#themeToggle .theme-toggle-text').text('Light Mode');
        } else {
          $('#themeToggle .theme-icon-light').show();
          $('#themeToggle .theme-icon-dark').hide();
          $('#themeToggle .theme-toggle-text').text('Dark Mode');
        }
      });
    });
      </script>

  </body>
</html>

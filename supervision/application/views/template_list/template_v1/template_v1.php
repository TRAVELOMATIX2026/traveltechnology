<?php
$___favicon_ico = $GLOBALS['CI']->template->domain_images('favicon.ico');
?>
<?php
  $mini_loading_image  = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>';

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="UTF-8">
    <meta name="keywords" content="<?= META_KEYWORDS ?>">
    <meta name="description" content="<?= META_DESCRIPTION ?>">
    <link rel="shortcut icon" href="<?=$___favicon_ico?>" type="image/x-icon">
  <link rel="icon" href="<?=$___favicon_ico?>" type="image/x-icon">
    <title><?php echo get_app_message( 'AL001'). ' '.HEADER_TITLE_SUFFIX; ?></title>
    <?php //Loading Common CSS and JS
      $this->current_page->header_css_resource();
      $this->current_page->header_js_resource();
      echo $GLOBALS ['CI']->current_page->css ();
    ?>
    
    <!-- Immediate tooltip fix right after jQuery loads -->
    <script>
      // Disable tooltip immediately
      if (typeof jQuery !== 'undefined' && typeof jQuery.fn !== 'undefined') {
        jQuery.fn.tooltip = function() { return this; };
        jQuery.fn.popover = function() { return this; };
      }
      if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined') {
        $.fn.tooltip = function() { return this; };
        $.fn.popover = function() { return this; };
      }
    </script>
    
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap2-toggle.min.css');?>" rel="stylesheet" defer>
    <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
    <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap2-toggle.min.js'); ?>"></script>
    <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('select2.min.css');?>" rel="stylesheet" />
    <script src="<?php echo $GLOBALS['CI']->template->template_js_dir("select2.min.js"); ?>"></script>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <!-- Theme style -->
    <link href="<?php echo $this->template->template_css_dir('AdminLTE.min.css')?>" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo $this->template->template_css_dir('skins-common.css')?>" rel="stylesheet" type="text/css" />
    
    <!-- Bootstrap Icons ONLY -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Material Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
     <!-- Ultra Modern Header Styles -->
     <link href="<?php echo $this->template->template_css_dir('header.css')?>?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     
     <!-- Ultra Modern Dropdown Styles -->
     <link href="<?php echo $this->template->template_css_dir('dropdown.css')?>?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
     
     <!-- Beautiful Dashboard Styles -->
     <link href="<?php echo $this->template->template_css_dir('dashboard.css')?>?v=<?php echo time(); ?>" rel="stylesheet" type="text/css" />

     <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

  <script>
  var app_base_url = "<?=base_url()?>";
  var tmpl_img_url = '<?=$GLOBALS['CI']->template->template_images(); ?>';
  
  // Fix tooltip error BEFORE any other scripts run
  (function() {
    // Wait for jQuery to be loaded
    function fixTooltip() {
      if (typeof jQuery !== 'undefined') {
        // Create a no-op tooltip function
        if (typeof jQuery.fn.tooltip === 'undefined') {
          jQuery.fn.tooltip = function() { return this; };
        }
        
        // Override the original ready function to remove tooltips
        var originalReady = jQuery.fn.ready;
        jQuery.fn.ready = function(fn) {
          return originalReady.call(this, function() {
            // Remove tooltip attributes before calling original function
            jQuery('[data-toggle="tooltip"], [data-bs-toggle="tooltip"]')
              .removeAttr('data-toggle data-bs-toggle title');
            
            // Call original ready function
            if (typeof fn === 'function') {
              try {
                fn.call(this);
              } catch(e) {
                // Suppress tooltip errors
                if (!e.message || e.message.indexOf('tooltip') === -1) {
                  throw e;
                }
              }
            }
          });
        };
      }
    }
    
    // Try immediately and also after a short delay
    fixTooltip();
    setTimeout(fixTooltip, 10);
  })();
  </script>
  
  <!-- Dark Mode Toggle Script -->
  <script src="<?php echo $this->template->template_js_dir('dark-mode.js'); ?>"></script>
  
  <!-- Menu Dropdown Fix Script -->
  <script src="<?php echo $this->template->template_js_dir('menu-dropdown-fix.js'); ?>?v=<?php echo time(); ?>"></script>
  </head>
  <body class="skin-black-light sidebar-mini">
  <noscript><img src="<?php echo $GLOBALS['CI']->template->template_images('default_loading.gif'); ?>"
      class="img-fluid mx-auto d-block"></img></noscript>
    
  <?php 
  //check if the user is loggedin and load respective data
  //START IF - PAGE After LOGIN
  if (is_logged_in_user()) {
  ?>
    <div class="wrapper d-flex">
      <!-- MENU starts -->
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
                   <?php include_once 'menu/magical_menu.php';?>
        </section>
        <!-- /.sidebar -->
      </aside>
      <!-- MENU ends -->
  
      <div class="content-wrapper" style="min-height: 100vh;">
      <!-- HEADER starts -->
      <header class="main-header">
        

        <!-- Header Navbar - Right Side -->
        <nav class="navbar navbar-static-top" role="navigation">
       
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-bs-toggle="offcanvas" role="button">
          
          </a>

      
          
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Theme Toggle Button -->
              <li>
                <div id="theme-toggle-icons" role="button" aria-label="Toggle theme">
                  <i class="bi bi-sun theme-icon-sun"></i>
                  <i class="bi bi-moon theme-icon-moon"></i>
                </div>
              </li>
              
              <!-- Messages: style can be found in dropdown.less-->
              <?php
                 if (is_domain_user()) { if(check_user_previlege('p114')):?>
                  <li class="dash-bal-btn">
                      <div class="loader-image">
                          <button data-bs-toggle="collapse" class="btn btn-primary btn-sm show-bal-btn" data-bs-target="#show-balance">
                              API Balance <i class="bi bi-chevron-up" aria-hidden="true"></i>
                          </button> 
                      </div>                        
                  </li>               
                  <!-- <li id="show-balance" class="collapse d-none" style="position: absolute; top: 100%; right: 0; background: #fff; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; min-width: 200px;">
                  </li>    -->
              <?php
       endif; }
              ?>
              <li class="dropdown messages-menu hide">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                  <i class="bi bi-envelope"></i>
                  <span class="badge bg-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#" class="d-flex justify-content-between align-items-center">
                          
                          <h4>
                            Support Team
                            <small><i class="bi bi-clock"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                      <li>
                        <a href="#">
                          <div class="float-start">
                            <img src="dist/img/user3-128x128.jpg" class="rounded-circle" alt="user image"/>
                          </div>
                          <h4>
                            AdminLTE Design Team
                            <small><i class="bi bi-clock"></i> 2 hours</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="float-start">
                            <img src="dist/img/user4-128x128.jpg" class="rounded-circle" alt="user image"/>
                          </div>
                          <h4>
                            Developers
                            <small><i class="bi bi-clock"></i> Today</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="float-start">
                            <img src="dist/img/user3-128x128.jpg" class="rounded-circle" alt="user image"/>
                          </div>
                          <h4>
                            Sales Department
                            <small><i class="fal fa-clock"></i> Yesterday</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="float-start">
                            <img src="dist/img/user4-128x128.jpg" class="rounded-circle" alt="user image"/>
                          </div>
                          <h4>
                            Reviewers
                            <small><i class="fal fa-clock"></i> 2 days</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              <?php if(check_user_previlege('p115')): ?>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" id="get_event_notification">
                  <i class="bi bi-bell"></i>
                  <span class="badge bg-warning" id="active_notifications_count"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                  <?php 
                  $notification_loading_image  = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>';
                  ?>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu" id="notification_dropdown"><?=$notification_loading_image?></ul>
                  </li>
                  <li class="footer hide" id="view_all_notification"><a href="<?=base_url()?>index.php/utilities/notification_list">View more</a></li>
                </ul>
              </li>
            <?php endif; ?>
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu ">
                <a href="#" class="dropdown-toggle hide" data-bs-toggle="dropdown">
                  <i class="bi bi-flag"></i>
                  <span class="badge bg-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#" class="d-flex justify-content-between align-items-center">
                          <h3>
                            Design some buttons
                            <small class="float-end">20%</small>
                          </h3>
                          
                        </a>
                        
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                      <a href="#" class="d-flex justify-content-between align-items-center">
                          <h3>
                            Design some buttons
                            <small class="float-end">20%</small>
                          </h3>
                          
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                      <a href="#" class="d-flex justify-content-between align-items-center">
                          <h3>
                            Design some buttons
                            <small class="float-end">20%</small>
                          </h3>
                          
                        </a>
                      </li><!-- end task item -->
                      <li><!-- Task item -->
                      <a href="#" class="d-flex justify-content-between align-items-center">
                          <h3>
                            Design some buttons
                            <small class="float-end">20%</small>
                          </h3>
                          
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                  <img src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->entity_image)?>" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo $GLOBALS['CI']->entity_name?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>" class="rounded-circle" alt="User Image" />
                    <p>
                      <?=$GLOBALS['CI']->entity_name.' - '.app_friendly_absolute_date($GLOBALS['CI']->entity_date_of_birth)?>
                      <small>Active since <?=app_friendly_absolute_date($GLOBALS['CI']->entity_creation)?></small>
                    </p>
                  </li>
                  <!-- Menu Body - Hidden -->
                  <li class="user-body" style="display: none !important;">
                  </li>
                  <!-- Menu Items with Icons -->
                  <ul class="menu">
                    <li>
                      <a href="<?php echo base_url().'index.php/user/account?uid='.intval($GLOBALS['CI']->entity_user_id); ?>">
                        <i class="bi bi-person"></i>
                        <span>Edit profile</span>
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo base_url().'user/change_password?uid='.intval($GLOBALS['CI']->entity_user_id);?>">
                        <i class="bi bi-gear"></i>
                        <span>Account settings</span>
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo base_url().'index.php/general/initilize_logout'?>">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sign out</span>
                      </a>
                    </li>
                  </ul>
                  <!-- Menu Footer - Hidden -->
                  <li class="user-footer" style="display: none !important;">
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- HEADER ends -->

      <!-- BODY CONTENT starts -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper p-4" style="min-height: 100vh;">
        <!-- UTILITY NAV -->
        <div class="container-fluid utility-nav clearfix">
          <!-- ROW -->
          <?php 
            if($this->session->flashdata('message')!="") {
              $message = $this->session->flashdata('message');
              $msg_type = $this->session->flashdata('type');
              $_override_app_msg = $this->session->flashdata('override_app_msg');
              if(empty($_override_app_msg) == false) {
                $override_app_msg = true;
              } else {
                $override_app_msg = false;
              }
            
              $toastr_msg = extract_message($message, $override_app_msg);
              $toastr = get_toastr_index($msg_type);
              ?>
              <script>
                toastr.<?=$toastr;?>("<?=$toastr_msg?>");
              </script>
            <?php
            }
          ?>
          <!-- /ROW -->
        </div>
        
        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
          <div class="">
            <?php echo $body ?>
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!-- BODY CONTENT ends -->



     <!-- FOOTER starts -->
      <!-- <div class="clearfix"></div> -->
      <footer class="main-footer">
        <div class="float-end hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; <?php echo date('Y')?> <a target="_blank" href="<?= HEADER_DOMAIN_WEBSITE ?>"><?=HEADER_DOMAIN_NAME?> </strong></a> All rights reserved.
      </footer>
      <!-- FOOTER ends -->
      
      <?php include_once 'menu/support_privilege_helper.php'; ?>
      </div><!-- /.content-wrapper -->
    </div><!-- ./wrapper -->
    
    <?php
    //END IF - PAGE After LOGIN
    } else {
      //Page without LOGIN - Show only the content without sidebar/header/footer
      echo $body;
    }
    ?>

  <?php
  Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/datepicker.js'), 'defer' => 'defer');
  Provab_Page_Loader::load_core_resource_files();
  echo $GLOBALS ['CI']->current_page->js ();
  ?>
  <script src='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fastclick/fastclick.min.js'></script>
    <!-- Sparkline -->
    <script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->

    <script>
      $(document).ready(function(){
        $('.select2-drop-down').select2();
          //Get Admin balance 
          $(".show-bal-btn").click(function(){          
           
              var bal_url = app_base_url + 'index.php/management/get_travelomatix_balance';           
              var div_length = $("#show-balance").children().length;
              //show loader image
              $("#show-balance").html("");
              if($(this).children("i").hasClass("fa-angle-up")){                
                  $(this).children("i").removeClass("fa-angle-up");
                  $(this).children("i").addClass("fa-angle-down");
                  $("#img-load").removeClass("hidden");
                  $("#img-load").addClass("visible");
                  $.get(bal_url, function(response) {  
                    //hide loader image
                    $("#img-load").removeClass("visible");
                    $("#img-load").addClass("hidden");
                    var html= '';
                  html +="<p><span>Bal</span> : <strong> <span>"+response.face_value+"</span></strong> \n\
                              <span>CL</span> : <strong> <span>"+response.credit_limit+"</span></strong> \n\
                              <span>Due</span>: <strong> <span>"+response.due_amount+"</span></strong></p>";                    
                    $("#show-balance").html(html); 
                },"json"); 

              }else if($(this).children("i").hasClass("fa-angle-down")){
                  $(this).children("i").removeClass("fa-angle-down");
                  $(this).children("i").addClass("fa-angle-up");
              }
            
          });

        //highlight current menu
  		var loc = window.location.toString();
  		var menu_wrap = $('#magical-menu');
  		var menu_item = $("a[href='"+loc+"']", menu_wrap);
  		//console.log(menu_item);
  		if (menu_item.length > 0) {
  			menu_item.addClass('bg-green');
  			var menu_parent = $(menu_item.closest('li'), menu_wrap);
  			menu_parent.addClass('active text-success');
  			
  			var parent_ul = $(menu_parent.closest('ul'), menu_wrap);
  			parent_ul.trigger('click');
  			var traverse_tree = true;
  			while (traverse_tree) {
  				parent_li = $(parent_ul).closest('li');
  				parent_li.addClass('active');
  				//console.log(parent_li);
  				parent_ul = $(parent_li).closest('ul');
  				parent_ul.addClass('menu-open');
  				//console.log(parent_ul);
  				if (parent_li.length == 0 || parent_ul.length == 0) {
  					traverse_tree = false;
  					//parent_ul.trigger('click');
  				}
  			}
  		}
      });
    </script>
    <script>
      function applySelect2() {
      $('select').each(function() {
        /// Add attribute data-enable-select2="false" to disable select2 on specific fields
        // Skip if attribute exists and equals false
        if ($(this).data('enable-select2') === false) {
          // If Select2 already applied, destroy it
          if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).select2('destroy');
          }
          return; // Do NOT apply select2
        }

        // Apply select2 normally only to allowed fields
        if (!$(this).hasClass('select2-hidden-accessible')) {
          $(this).select2();
        }

      });
    }

    $(document).ready(function() {
      applySelect2();

      $(document).ajaxComplete(function() {
        applySelect2();
      });
    });
      </script>

    </body>
</html>

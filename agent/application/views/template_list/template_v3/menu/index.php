<?php
$active_domain_modules = $this->active_domain_modules;
$default_active_tab = $default_view;

// Define valid module constants for validation
$valid_modules = array(
	META_AIRLINE_COURSE,
	META_FLIGHT_HOTEL_COURSE,
	META_ACCOMODATION_COURSE,
	META_CAR_COURSE,
	META_SIGHTSEEING_COURSE,
	META_TRANSFER_COURSE,
	META_PACKAGE_COURSE
);

// Determine the default tab based on priority: Flights ALWAYS first if active
// Priority order: Flights > Flight+Hotels > Hotels > Car > Transfers > Package > Sightseeing
if (empty($default_active_tab) || !in_array($default_active_tab, $valid_modules)) {
	// No valid default_view specified, use priority order (Flights first if available)
	if (is_active_airline_module()) {
		$default_active_tab = META_AIRLINE_COURSE;
	} elseif (is_active_flight_hotel_module()) {
		$default_active_tab = META_FLIGHT_HOTEL_COURSE;
	} elseif (is_active_hotel_module()) {
		$default_active_tab = META_ACCOMODATION_COURSE;
	} elseif (is_active_car_module()) {
		$default_active_tab = META_CAR_COURSE;
	} elseif (is_active_transfer_module()) {
		$default_active_tab = META_TRANSFER_COURSE;
	} elseif (is_active_package_module()) {
		$default_active_tab = META_PACKAGE_COURSE;
	} elseif (is_active_sightseeing_module()) {
		$default_active_tab = META_SIGHTSEEING_COURSE;
	}
}
// If a valid default_active_tab was provided (via $default_view), it will be used as-is

// echo "<pre>"; print_r($holiday_data);exit;

/**
 * set default active tab - ensures only ONE tab gets active class
 * But allows corresponding tab-pane to also get active class
 *
 * @param string $module_name
 *        	name of current module being output
 * @param string $default_active_tab
 *        	default tab name if already its selected otherwise its empty
 * @param bool $is_tab_pane
 *        	whether this is for a tab-pane (needs 'show' class for Bootstrap 5)
 * @return string 'active' or 'active show' if this tab should be active, empty string otherwise
 */
function set_default_active_tab($module_name, &$default_active_tab, $is_tab_pane = false) {
	static $active_tab_set = false;
	
	// Only return 'active' if this module matches the default_active_tab
	if ($module_name == $default_active_tab) {
		// For tab links (not tab-panes), only allow the FIRST matching tab to get active
		if (!$is_tab_pane) {
			if (!$active_tab_set) {
				$active_tab_set = true;
				return 'active';
			}
			// Already set an active tab link, don't set another one
			return '';
		} else {
			// For tab-panes, always allow if it matches (tab-pane can always match its corresponding tab)
			return 'active show';
		}
	}
	return '';
}
?>
<style>
	.form-control,
   input[type="text"],
   input[type="email"],
   input[type="date"],
   select,
   .input-field {
       background: var(--color-bg-primary) !important;
       border: none !important;
       border-radius: var(--border-radius-md) !important;
       padding: var(--spacing-0) var(--spacing-0) !important;
       font-size: var(--font-size-md) !important;
       height: 38px !important;
       font-weight: var(--font-weight-  ) !important;
       color: var(--color-text-primary) !important;
       transition: var(--transition-all) !important;
       width: 100% !important;
       box-shadow: none !important;
   }

   
   .form-control:hover,
   input[type="text"]:hover,
   input[type="email"]:hover,
   input[type="date"]:hover,
   select:hover {
       border-color: none !important;
       background: none !important;
       box-shadow: none !important;
   }
   
   .form-control:focus,
   input[type="text"]:focus,
   input[type="email"]:focus,
   input[type="date"]:focus,
   select:focus {
       border-color:none !important;
       background: none !important;
       box-shadow: none !important;
       outline: none !important;
   }
   .classes_Select .alladvnce {    padding: var(--spacing-2) var(--spacing-3) !important ;
  background: var(--color-bg-secondary) !important;}
   
   /* Form Labels - Modern Typography - Design System */
   .form-label,
   label {
       font-size: var(--font-size-md) !important;
       font-weight: var(--font-weight-bold) !important;
       color: var(--color-text-primary) !important;
       margin-bottom: var(--spacing-2) !important;
       display: block !important;
       text-transform: uppercase;
       letter-spacing: 0.5px;
   }
   
   /* Beautiful Agent Module Tabs */
   .agent-module-tabs {
       display: flex !important;
       justify-content: center !important;
       align-items: center !important;
       gap: 8px !important;
       margin-bottom: 25px !important;
       padding: 0 15px !important;
       border: none !important;
       background: transparent !important;
   }
   
   .agent-module-tabs .nav-item {
       margin: 0 !important;
       border: none !important;
   }
   
   .agent-module-tabs .nav-link {
       display: flex !important;
       flex-direction: column !important;
       align-items: center !important;
       justify-content: center !important;
       padding: 16px 20px !important;
       margin: 0 !important;
       border: 2px solid #e0e0e0 !important;
       border-radius: 12px !important;
       background: #ffffff !important;
       color: #333 !important;
       text-decoration: none !important;
       transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
       cursor: pointer !important;
       min-width: 120px !important;
       box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
   }
   
   .agent-module-tabs .nav-link:hover {
       background: #f8f9fa !important;
       border-color: #d0d0d0 !important;
       transform: translateY(-2px) !important;
       box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
   }
   
   .agent-module-tabs .nav-item.active .nav-link,
   .agent-module-tabs .nav-link:focus,
   .agent-module-tabs .nav-link.active {
       background: linear-gradient(135deg, #f18604 0%, #ff9800 100%) !important;
       border-color: #f18604 !important;
       color: #ffffff !important;
       transform: translateY(-2px) !important;
       box-shadow: 0 6px 20px rgba(241, 134, 4, 0.4) !important;
   }
   
   .agent-module-tabs .nav-link .iconcmn {
       display: flex !important;
       align-items: center !important;
       justify-content: center !important;
       margin-bottom: 8px !important;
       width: 32px !important;
       height: 32px !important;
   }
   
   .agent-module-tabs .nav-link .iconcmn .material-icons,
   .agent-module-tabs .nav-link .iconcmn .tmx-icon {
       font-size: 24px !important;
       width: 24px !important;
       height: 24px !important;
       color: inherit !important;
   }
   
   .agent-module-tabs .nav-item.active .nav-link .iconcmn .material-icons,
   .agent-module-tabs .nav-item.active .nav-link .iconcmn .tmx-icon {
       color: #ffffff !important;
   }
   
   .agent-module-tabs .nav-link label {
       font-size: 11px !important;
       font-weight: 700 !important;
       letter-spacing: 0.8px !important;
       margin: 0 !important;
       text-transform: uppercase !important;
       color: inherit !important;
       white-space: nowrap !important;
   }
   
   .agent-module-tabs .nav-item.active .nav-link label {
       color: #ffffff !important;
   }
   
   /* Responsive adjustments */
   @media (max-width: 768px) {
       .agent-module-tabs {
           flex-wrap: wrap !important;
           gap: 8px !important;
       }
       
       .agent-module-tabs .nav-link {
           min-width: 100px !important;
           padding: 12px 16px !important;
       }
       
       .agent-module-tabs .nav-link label {
           font-size: 10px !important;
       }
   }
</style>

<div class="searcharea">
   <?php if(check_user_previlege('p2')):?>
   <div class="srchinarea">
    
      <div class="allformst">
          <div class="container-fluid inspad nopad">

          <!-- Tab panes -->
          <div class="tab-content custmtab">
            <div class="col-12 p-0">
            <ul class="nav nav-tabs tabstab d-flex justify-content-center" role="tablist">
              <?php if (is_active_airline_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $flight_active; ?>" href="#flight" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <?php echo render_tmx_icon('tmx-icon-flight', 'tmx-icon-xl'); ?>
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
                    <span class=" iconcmn">
                      <?php echo render_tmx_icon('tmx-icon-hotel', 'tmx-icon-xl'); ?>
                    </span>
                    <label>Hotels</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_car_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $car_active; ?>" href="#car" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <?php echo render_tmx_icon('tmx-icon-car', 'tmx-icon-xl'); ?>
                    </span>
                      <label>Car Rental</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_sightseeing_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $sightseeing_active; ?>" href="#sightseeing" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <?php echo render_tmx_icon('tmx-icon-sighseeing', 'tmx-icon-xl'); ?>
                    </span>
                    <label> Sight Seeing</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_transfer_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $transfer_active; ?>" href="#transfer" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <?php echo render_tmx_icon('tmx-icon-transfers', 'tmx-icon-xl'); ?>
                    </span><label> Transfers</label>
                  </a>
                </li>
              <?php } ?>
              <?php if (is_active_package_module()) { ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo $holiday_active; ?>" href="#holiday" role="tab" data-bs-toggle="tab">
                    <span class=" iconcmn">
                      <?php echo render_tmx_icon('tmx-icon-tours', 'tmx-icon-xl'); ?>
                    </span>
                    <label> Holiday</label>
                  </a>
                </li>
              <?php } ?>
            </ul>
              
            </div>


            <?php if (is_active_airline_module()) { ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab, true) ?>"
                id="flight">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_hotel_module()) { ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab, true) ?>"
                id="hotel">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_bus_module()) { ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab, true) ?>"
                id="bus">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_transfer_module()) {  ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_TRANSFER_COURSE, $default_active_tab, true) ?>"
                id="transfer">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/transfer_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_car_module()) { ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab, true) ?>"
                id="car">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/car_search') ?>
              </div>
            <?php } ?>
            <?php if (is_active_package_module()) { ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab, true) ?>"
                id="holiday">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search', $holiday_data) ?>
              </div>
            <?php } ?>
            <?php if (is_active_sightseeing_module()) { ?>
              <div class="tab-pane <?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab, true) ?>"
                id="sightseeing">
                <?php echo $GLOBALS['CI']->template->isolated_view('share/sightseeing_search', $holiday_data) ?>
              </div>
            <?php } ?>
            <div class="tab-pane <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab, true) ?>"
              id="packages">
              <?php echo $GLOBALS['CI']->template->isolated_view('share/packages_search', $holiday_data) ?>
            </div>

          </div>
        </div>

      </div>
         
      </div>
   </div>
<?php endif;?>
<script type="text/javascript">
	$(document).ready(function($){
		// Fix tab selection - ensure only ONE tab is active at a time
		function ensureSingleActiveTab() {
			var activeTabs = $('.nav-tabs.tabstab > li.active');
			
			// If multiple tabs are active, keep only the FIRST one
			if (activeTabs.length > 1) {
				// Remove active class from all except the first
				activeTabs.slice(1).removeClass('active');
			}
			
			// Get the first active tab
			var activeTab = $('.nav-tabs.tabstab > li.active > a').first();
			
			if (activeTab.length > 0) {
				var activeTabId = activeTab.attr('href');
				if (activeTabId) {
					// Remove active/show from all tab panes
					$('.tab-content .tab-pane').removeClass('active show');
					// Add active/show to the correct tab pane
					$(activeTabId).addClass('active show');
				}
			} else {
				// If no tab is active, activate Flights tab as default
				var flightsTab = $('.nav-tabs.tabstab > li > a[href="#flight"]');
				if (flightsTab.length > 0) {
					// Remove active from all tabs and panes
					$('.nav-tabs.tabstab > li').removeClass('active');
					$('.tab-content .tab-pane').removeClass('active show');
					// Activate Flights tab and pane
					flightsTab.closest('li').addClass('active');
					$('#flight').addClass('active show');
				}
			}
		}
		
		// Run on page load after a small delay to ensure DOM is fully loaded
		setTimeout(ensureSingleActiveTab, 100);
		
		// Handle tab clicks - ensure only one tab is active
		$('.nav-tabs.tabstab > li > a[data-bs-toggle="tab"], .nav-tabs.tabstab > li > a[href^="#"]').on('click', function(e) {
			e.preventDefault();
			var targetTabId = $(this).attr('href');
			if (targetTabId && targetTabId.indexOf('#') === 0) {
				// Remove active from all tabs
				$('.nav-tabs.tabstab > li').removeClass('active');
				// Add active to clicked tab
				$(this).closest('li').addClass('active');
				// Remove active/show from all panes
				$('.tab-content .tab-pane').removeClass('active show');
				// Add active/show to target pane
				$(targetTabId).addClass('active show');
				
				// Trigger Bootstrap tab show if available
				if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
					var tab = new bootstrap.Tab(this);
					tab.show();
				}
			}
		});
		
		//Top Destination Functionality
		$('.htd-wrap').on('click', function(e) {
			e.preventDefault();
			var curr_destination = $('.top-des-val', this).val();
			var check_in = "<?=add_days_to_date(7)?>";
			var check_out = "<?=add_days_to_date(10)?>";

			$('#hotel_destination_search_name').val(curr_destination);
			$('#hotel_checkin').val(check_in);
			$('#hotel_checkout').val(check_out);
			$('#hotel_search').submit();
		});
	});
	//homepage slide show end
</script>
<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
echo $this->template->isolated_view('share/js/lazy_loader');
?>
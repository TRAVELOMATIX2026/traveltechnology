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
</style>

<div class="searcharea">
   <?php if(check_user_previlege('p2')):?>
   <div class="srchinarea">
    
      <div class="allformst">
          <div class="container-fluid inspad nopad">

          <!-- Tab panes -->
          <div class="tab-content custmtab">
            <div class="col-12 p-0">
              <ul class="nav nav-tabs tabstab">
                <?php if (is_active_airline_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab) ?>">
                    <a href="#flight" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">flight</i>
                      </span>
                      <label>Flights</label>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_flight_hotel_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_FLIGHT_HOTEL_COURSE, $default_active_tab) ?>">
                    <a href="#flightHotel" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">connecting_airports</i>
                      </span>
                      <label>Flight + Hotels</label>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_hotel_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab) ?>">
                    <a href="#hotel" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">hotel</i>
                      </span>
                      <label>Hotels</label>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_car_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_CAR_COURSE, $default_active_tab) ?>">
                    <a href="#car" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">directions_car</i>
                      </span>
                      <label>Car Rental</label>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_sightseeing_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_SIGHTSEEING_COURSE, $default_active_tab) ?>">
                    <a href="#sightseeing" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">camera_alt</i>
                      </span>
                      <label>Sight Seeing</label>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_transfer_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_TRANSFER_COURSE, $default_active_tab) ?>">
                    <a href="#transfer" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">transfer_within_a_station</i>
                      </span>
                      <label>Transfers</label>
                    </a>
                  </li>
                <?php } ?>
                <?php if (is_active_package_module()) { ?>
                  <li class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab) ?>">
                    <a href="#holiday" role="tab" data-bs-toggle="tab">
                      <span class="iconcmn">
                        <i class="material-icons">beach_access</i>
                      </span>
                      <label>Holiday</label>
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
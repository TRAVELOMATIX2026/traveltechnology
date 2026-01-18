<?php
$active_domain_modules = $this->active_domain_modules;

$menu_list = array();
if (count($active_domain_modules) > 0) {
   $any_domain_module = true;
} else {
   $any_domain_module = false;
}
$airline_module = is_active_airline_module();
$accomodation_module = is_active_hotel_module();
$bus_module = is_active_bus_module();
$package_module = is_active_package_module();
$sightseen_module = is_active_sightseeing_module();
$car_module = is_active_car_module();
$transferv1_module = is_active_transfer_module();
$tour_module = is_active_tour_module();
$bb = 'b2b';
$bc = 'b2c';
$visibility = false;
$b2b = is_active_module($bb);
$b2c = is_active_module($bc);
$social_login = 'facebook';
$social = is_active_social_login($social_login);
?>

<nav>

       <!-- Logo - Left Side -->
       <a href="<?php echo base_url()?>" class="logo bg-white">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src="<?php echo $GLOBALS['CI']->template->domain_images('mini_'.$GLOBALS['CI']->template->get_domain_logo())?>" alt="logo"	class="img-fluid mx-auto d-block"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>" alt="logo"	class="img-fluid mx-auto d-block"></span>
        </a>

	<!-- Menu Group -->
	<div class="menu-group">
		<h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
	
			<svg
				class="menu-group-icon mx-auto fill-current"
				width="24"
				height="24"
				viewBox="0 0 24 24"
				fill="none"
				xmlns="http://www.w3.org/2000/svg"
			>
				<path
					fill-rule="evenodd"
					clip-rule="evenodd"
					d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
					fill="currentColor"
				/>
			</svg>
		</h3>

		<ul class="sidebar-menu mb-6 flex flex-col gap-4" id="magical-menu">
   <?php if (check_user_previlege('p1')): ?>
      <li class="treeview">
         <a href="<?php echo base_url() ?>" class="menu-item menu-item-active group">
            <i class="bi bi-speedometer2"></i>
            <span class="menu-item-text">Dashboard</span>
         </a>
      </li>
   <?php endif; ?>
   <li class="treeview">
      <a href="#" class="menu-item menu-item-inactive group">
         <i class="bi bi-speedometer2"></i>
         <span class="menu-item-text">Dashboard-2</span>
         <svg
            class="menu-item-arrow menu-item-arrow-inactive"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
         >
            <path
               d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
               stroke=""
               stroke-width="1.5"
               stroke-linecap="round"
               stroke-linejoin="round"
            />
         </svg>
      </a>
      <!-- Dropdown Menu Start -->
      <div class="translate transform overflow-hidden">
         <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
            <li>
               <a href="<?php echo base_url() . 'index.php/transaction/search_history' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">
                  Search History
               </a>
            </li>
            <li>
               <a href="<?php echo base_url() . 'index.php/transaction/top_destinations' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">
                  Top Destinations
               </a>
            </li>
         </ul>
      </div>
      <!-- Dropdown Menu End -->
   </li>
   <?php if (is_domain_user() == false) { // ACCESS TO ONLY PROVAB ADMIN 
   ?>
      <li class="treeview">
         <a href="#" class="menu-item menu-item-inactive group">
            <i class="bi bi-wrench"></i>
            <span class="menu-item-text">Management</span>
            <svg
               class="menu-item-arrow menu-item-arrow-inactive"
               width="20"
               height="20"
               viewBox="0 0 20 20"
               fill="none"
               xmlns="http://www.w3.org/2000/svg"
            >
               <path
                  d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                  stroke=""
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
               />
            </svg>
         </a>
         <!-- Dropdown Menu Start -->
         <div class="translate transform overflow-hidden">
            <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
               <li><a href="<?php echo base_url() . 'index.php/user/user_management' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">User</a></li>
               <li><a href="<?php echo base_url() . 'index.php/user/domain_management' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Domain</a></li>
               <li><a href="<?php echo base_url() . 'index.php/module/module_management' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Master Module</a></li>
            </ul>
         </div>
         <!-- Dropdown Menu End -->
      </li>
      <?php if ($any_domain_module) { ?>
         <li class="treeview">
            <a href="#" class="menu-item menu-item-inactive group">
               <i class="bi bi-person"></i>
               <span class="menu-item-text">Markup</span>
               <svg
                  class="menu-item-arrow menu-item-arrow-inactive"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
               >
                  <path
                     d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                     stroke=""
                     stroke-width="1.5"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                  />
               </svg>
            </a>
            <!-- Dropdown Menu Start -->
            <div class="translate transform overflow-hidden">
               <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                  <?php if ($airline_module) { ?>
                     <li><a href="<?php echo base_url() . 'index.php/private_management/airline_domain_markup' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight</a></li>
                  <?php } ?>
                  <?php if ($accomodation_module) { ?>
                     <li><a href="<?php echo base_url() . 'index.php/private_management/hotel_domain_markup' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotel</a></li>
                  <?php } ?>
                  <?php if ($bus_module) { ?>
                     <li><a href="<?php echo base_url() . 'index.php/private_management/bus_domain_markup' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Bus</a></li>
                  <?php } ?>
                  <?php if ($transferv1_module) { ?>
                     <li><a href="<?php echo base_url() . 'index.php/private_management/transfer_domain_markup' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Transfers</a></li>
                  <?php } ?>
                  <?php if ($sightseen_module) { ?>
                     <li><a href="<?php echo base_url() . 'index.php/private_management/sightseeing_domain_markup' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Activities</a></li>
                  <?php } ?>
               </ul>
            </div>
            <!-- Dropdown Menu End -->
         </li>
      <?php } ?>
      <li class="treeview">
         <a href="<?php echo base_url() . 'index.php/private_management/process_balance_manager' ?>" class="menu-item menu-item-inactive group">
            <i class="bi bi-wallet2"></i>
            <span class="menu-item-text">Master Balance Manager</span>
         </a>
      </li>
      <li class="treeview">
         <a href="<?php echo base_url() . 'index.php/private_management/event_logs' ?>" class="menu-item menu-item-inactive group">
            <i class="bi bi-shield"></i>
            <span class="menu-item-text">Event Logs</span>
         </a>
      </li>
   <?php
   } else if ((is_domain_user() == true)) {
      // ACCESS TO ONLY DOMAIN ADMIN
   ?>
      <!-- USER ACCOUNT MANAGEMENT -->
      <?php if (check_user_previlege('p2')): ?>
         <li class="treeview">
            <a href="#" class="menu-item menu-item-inactive group">
               <i class="bi bi-person"></i>
               <span class="menu-item-text">Users</span>
               <svg
                  class="menu-item-arrow menu-item-arrow-inactive"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
               >
                  <path
                     d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                     stroke=""
                     stroke-width="1.5"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                  />
               </svg>
            </a>
            <!-- Dropdown Menu Start -->
            <div class="translate transform overflow-hidden">
               <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
               <!-- USER TYPES -->
               <?php if ($b2c) {
                  if (check_user_previlege('p17')): ?>
                     <li>
                        <a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">B2C</a>
                        <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                           <li><a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER . '&user_status=' . ACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Active</a></li>
                           <li><a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER . '&user_status=' . INACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">InActive</a></li>
                           <li><a href="<?php echo base_url() . 'index.php/user/get_logged_in_users?filter=user_type&q=' . B2C_USER; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Logged In User</a></li>
                        </ul>
                     </li>
               <?php endif;
               } ?>
               <?php if ($b2b) {
                  if (check_user_previlege('p24')): ?>
                     <li>
                        <a href="<?php echo base_url() . 'index.php/user/b2b_user?filter=user_type&q=' . B2B_USER ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">B2B</a>
                        <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                           <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?user_status=' . ACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Active</a></li>
                           <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?user_status=' . INACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">InActive</a></li>
                           <li><a href="<?php echo base_url() . 'index.php/user/get_logged_in_users?filter=user_type&q=' . B2B_USER; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Logged In User</a></li>
                        </ul>
                     </li>
                  <?php endif;
               }
               if (check_user_previlege('p73') && $visibility): ?>
                  <li><a href="<?php echo base_url() . 'index.php/user/user_management?filter=user_type&q=' . SUB_ADMIN ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Sub Admin</a>
                     <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                        <li><a href="<?php echo base_url() . 'index.php/user/user_management?filter=user_type&q=' . SUB_ADMIN . '&user_status=' . ACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Active</a></li>
                        <li><a href="<?php echo base_url() . 'index.php/user/user_management?filter=user_type&q=' . SUB_ADMIN . '&user_status=' . INACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">InActive</a></li>
                        <li><a href="<?php echo base_url() . 'index.php/user/get_logged_in_users?filter=user_type&q=' . SUB_ADMIN; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Logged In User</a></li>
                     </ul>
                  </li>
               <?php endif; ?>
               </ul>
            </div>
            <!-- Dropdown Menu End -->
         </li>
         <?php endif;
      if ($any_domain_module) {
         if (check_user_previlege('p3') && $visibility): ?>
            <li class="treeview">
               <a href="#" class="menu-item menu-item-inactive group">
                  <i class="bi bi-shield-fill"></i>
                  <span class="menu-item-text">Queues</span>
                  <svg
                     class="menu-item-arrow menu-item-arrow-inactive"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                        d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                        stroke=""
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                     />
                  </svg>
               </a>
               <?php if (check_user_previlege('p71')): ?>
                  <!-- Dropdown Menu Start -->
                  <div class="translate transform overflow-hidden">
                     <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                        <li><a href="<?php echo base_url() . 'index.php/report/cancellation_queue/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight Cancellation</a></li>
                     </ul>
                  </div>
                  <!-- Dropdown Menu End -->
               <?php endif; ?>
            </li>
         <?php endif;
         if (check_user_previlege('p4')): ?>
            <li class="treeview">
               <a href="#" class="menu-item menu-item-inactive group">
                  <i class="bi bi-bar-chart-fill"></i>
                  <span class="menu-item-text">Reports</span>
                  <svg
                     class="menu-item-arrow menu-item-arrow-inactive"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                        d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                        stroke=""
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                     />
                  </svg>
               </a>
               <!-- Dropdown Menu Start -->
               <div class="translate transform overflow-hidden">
                  <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                  <!-- USER TYPES -->
                  <?php if ($b2c) {
                     if (check_user_previlege('p74')): ?>
                        <li>
                           <a href="#" class="menu-dropdown-item menu-dropdown-item-inactive group">B2C</a>
                           <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                              <?php if ($airline_module) {
                                 if (check_user_previlege('p18')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2c_flight_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($accomodation_module) {
                                 if (check_user_previlege('p19')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2c_hotel_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotel</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($bus_module) {
                                 if (check_user_previlege('p20')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2c_bus_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Bus</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($transferv1_module) {
                                 if (check_user_previlege('p21')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2c_transfers_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Transfer</a></li>
                              <?php endif;
                              }
                              ?>
                              <?php if ($sightseen_module) {
                                 if (check_user_previlege('p22')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2c_activities_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Activities</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($car_module) {
                                 if (check_user_previlege('p23')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2c_car_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Car</a></li>
                              <?php endif;
                              } ?>
                           </ul>
                        </li>
                     <?php endif;
                  }
                  if ($b2b) {
                     if (check_user_previlege('p75')): ?>
                        <li>
                           <a href="#" class="menu-dropdown-item menu-dropdown-item-inactive group">B2B</a>
                           <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                              <?php if ($airline_module) {
                                 if (check_user_previlege('p25')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2b_flight_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($accomodation_module) {
                                 if (check_user_previlege('p26')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2b_hotel_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotel</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($bus_module) {
                                 if (check_user_previlege('p27')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2b_bus_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Bus</a></li>
                                 <?php endif;
                              }
                              if ($transferv1_module) {
                                 if (check_user_previlege('p28')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2b_transfers_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Transfers</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($sightseen_module) {
                                 if (check_user_previlege('p29')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2b_activities_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Activities</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($car_module) {
                                 if (check_user_previlege('p30')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/report/b2b_car_report/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Car</a></li>
                              <?php endif;
                              } ?>
                           </ul>
                        </li>
                  <?php endif;
                  } ?>
                  <li>
                     <a href="<?php echo base_url() . 'index.php/transaction/logs' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">
                        Transaction Logs
                     </a>
                  </li>
                  <?php if ($visibility): ?>
                  <li>
                     <a href="<?php echo base_url() . 'index.php/management/account_ledger' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">
                        Account Ledger
                     </a>
                  </li>
                  <?php endif; ?>
                  </ul>
               </div>
               <!-- Dropdown Menu End -->
            </li>
         <?php endif;
         if (check_user_previlege('p5') && $visibility): ?>
            <li class="treeview">
               <a href="#" class="menu-item menu-item-inactive group">
                  <i class="bi bi-cash"></i>
                  <span class="menu-item-text">Account</span>
                  <svg
                     class="menu-item-arrow menu-item-arrow-inactive"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                        d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                        stroke=""
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                     />
                  </svg>
               </a>
               <!-- Dropdown Menu Start -->
               <div class="translate transform overflow-hidden">
                  <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                     <?php if (check_user_previlege('p31')): ?>
                        <li><a href="<?php echo base_url() . 'private_management/credit_balance' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Credit Balance</a></li>
                     <?php endif;
                     if (check_user_previlege('p32')): ?>
                        <li><a href="<?php echo base_url() . 'private_management/debit_balance' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Debit Balance</a></li>
                     <?php endif; ?>
                  </ul>
               </div>
               <!-- Dropdown Menu End -->
            </li>
            <?php endif;
         if ($b2b) {
            if (check_user_previlege('p6')): ?>
               <li class="treeview">
                  <a href="#" class="menu-item menu-item-inactive group">
                     <i class="bi bi-briefcase"></i>
                     <span class="menu-item-text">Commission</span>
                     <svg
                        class="menu-item-arrow menu-item-arrow-inactive"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                     >
                        <path
                           d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                           stroke=""
                           stroke-width="1.5"
                           stroke-linecap="round"
                           stroke-linejoin="round"
                        />
                     </svg>
                  </a>
                  <!-- Dropdown Menu Start -->
                  <div class="translate transform overflow-hidden">
                     <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                        <?php if (check_user_previlege('p33')): ?>
                           <li><a href="<?php echo base_url() . 'index.php/management/agent_commission?default_commission=' . ACTIVE; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Default Commission</a></li>
                        <?php endif;
                        if (check_user_previlege('p34')): ?>
                           <li><a href="<?php echo base_url() . 'index.php/management/agent_commission' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Agent's Commission</a></li>
                        <?php endif; ?>
                     </ul>
                  </div>
                  <!-- Dropdown Menu End -->
               </li>
            <?php endif;
         }
         if (check_user_previlege('p7')): ?>
            <li class="treeview">
               <a href="#" class="menu-item menu-item-inactive group">
                  <i class="bi bi-plus-square"></i>
                  <span class="menu-item-text">Markup</span>
                  <svg
                     class="menu-item-arrow menu-item-arrow-inactive"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                        d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                        stroke=""
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                     />
                  </svg>
               </a>
               <!-- Dropdown Menu Start -->
               <div class="translate transform overflow-hidden">
                  <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                  <!-- Markup TYPES -->
                  <?php if ($b2c) {
                     if (check_user_previlege('p35')): ?>
                        <li>
                           <a href="#" class="menu-dropdown-item menu-dropdown-item-inactive group">B2C</a>
                           <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                              <?php if ($airline_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2c_airline_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($accomodation_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2c_hotel_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotel</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($bus_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2c_bus_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Bus</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($transferv1_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2c_transfer_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Transfers</a></li>
                              <?php endif;
                              }
                              ?>
                              <?php if ($sightseen_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2c_sightseeing_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Activities</a></li>
                              <?php endif;
                              }
                              ?>
                              <?php if ($car_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2c_car_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Car</a></li>
                              <?php endif;
                              }
                              ?>
                           </ul>
                        </li>
                     <?php endif;
                  }
                  if ($b2b) {
                     if (check_user_previlege('p36')):
                     ?>
                        <li>
                           <a href="#" class="menu-dropdown-item menu-dropdown-item-inactive group">B2B</a>
                           <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                              <?php if ($airline_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2b_airline_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($accomodation_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2b_hotel_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotel</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($bus_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2b_bus_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Bus</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($transferv1_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2b_transfer_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Transfers</a></li>
                              <?php endif;
                              } ?>
                              <?php if ($sightseen_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2b_sightseeing_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Activities</a></li>
                              <?php endif;
                              }
                              ?>
                              <?php if ($car_module) {
                                 if (check_user_previlege('p35')): ?>
                                    <li><a href="<?php echo base_url() . 'index.php/management/b2b_car_markup/'; ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Car</a></li>
                              <?php endif;
                              }
                              ?>
                           </ul>
                        </li>
                  <?php endif;
                  } ?>
                  </ul>
               </div>
               <!-- Dropdown Menu End -->
            </li>
      <?php endif;
      }  ?>
      
      <?php if ($b2b) {
         if (check_user_previlege('p9')): ?>
            <li class="treeview">
               <a href="#" class="menu-item menu-item-inactive group">
                  <i class="bi bi-cash"></i>
                  <span class="menu-item-text">Master Balance Manager</span>
                  <svg
                     class="menu-item-arrow menu-item-arrow-inactive"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                        d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                        stroke=""
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                     />
                  </svg>
               </a>
               <!-- Dropdown Menu Start -->
               <div class="translate transform overflow-hidden">
                  <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                     <?php if (check_user_previlege('p37')): ?>
                        <li class="hide"><a href="<?php echo base_url() . 'index.php/management/master_balance_manager' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">API</a></li>
                        <li><a href="<?php echo base_url() . 'index.php/management/b2b_balance_manager' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">B2B Wallet Top-Up Requests</a></li>
                     <?php endif;
                     if (check_user_previlege('p38')): ?>
                        <li><a href="<?php echo base_url() . 'index.php/management/b2b_credit_request' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">B2B Credit Limt Requests</a></li>
                     <?php endif; ?>
                  </ul>
               </div>
               <!-- Dropdown Menu End -->
            </li>
         <?php endif;
      }
      if ($package_module) {
         if (check_user_previlege('p10')): ?>
            <li class="treeview">
               <a href="#" class="menu-item menu-item-inactive group">
                  <i class="bi bi-plus-square"></i>
                  <span class="menu-item-text">Package Management</span>
                  <svg
                     class="menu-item-arrow menu-item-arrow-inactive"
                     width="20"
                     height="20"
                     viewBox="0 0 20 20"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                  >
                     <path
                        d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                        stroke=""
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                     />
                  </svg>
               </a>
               <!-- Dropdown Menu Start -->
               <div class="translate transform overflow-hidden">
                  <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                     <?php if (check_user_previlege('p39')): ?>
                        <li>
                           <a href="<?php echo base_url() . 'index.php/supplier/view_packages_types' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">View Package Types</a>
                        </li>
                     <?php endif;
                     if (check_user_previlege('p40')): ?>
                        <li>
                           <a href="<?php echo base_url() . 'index.php/supplier/add_with_price' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Add New Package</a>
                        </li>
                     <?php endif;
                     if (check_user_previlege('p41')): ?>
                        <li><a href="<?php echo base_url() . 'index.php/supplier/view_with_price' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">View Packages</a></li>
                     <?php endif;
                     if (check_user_previlege('p42')): ?>
                        <li><a href="<?php echo base_url() . 'index.php/supplier/enquiries' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">View Packages Enquiries</a></li>
                     <?php endif; ?>
                  </ul>
               </div>
               <!-- Dropdown Menu End -->
            </li>
         <?php endif;
      }
      if (check_user_previlege('p11')): ?>
         <li class="treeview">
            <a href="#" class="menu-item menu-item-inactive group">
               <i class="bi bi-envelope"></i>
               <span class="menu-item-text">Email Subscriptions</span>
               <svg
                  class="menu-item-arrow menu-item-arrow-inactive"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
               >
                  <path
                     d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                     stroke=""
                     stroke-width="1.5"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                  />
               </svg>
            </a>
            <!-- Dropdown Menu Start -->
            <div class="translate transform overflow-hidden">
               <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                  <li><a href="<?php echo base_url() . 'index.php/general/view_subscribed_emails' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">View Emails</a></li>
               </ul>
            </div>
            <!-- Dropdown Menu End -->
         </li>
      <?php endif;
   }
   if ($b2c) {
      if (check_user_previlege('p13')): ?>
         <li class="treeview">
            <a href="#" class="menu-item menu-item-inactive group">
               <i class="bi bi-laptop"></i>
               <span class="menu-item-text">CMS</span>
               <svg
                  class="menu-item-arrow menu-item-arrow-inactive"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
               >
                  <path
                     d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                     stroke=""
                     stroke-width="1.5"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                  />
               </svg>
            </a>
            <!-- Dropdown Menu Start -->
            <div class="translate transform overflow-hidden">
               <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
               <?php if (check_user_previlege('p44')): ?>
                  <li><a href="<?php echo base_url() . 'index.php/user/banner_images' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Homepage Banner Image</a></li>
               <?php endif;
               if (check_user_previlege('p45')): ?>
                  <li><a href="<?php echo base_url() . 'index.php/cms/add_cms_page' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Static Page content</a></li>
               <?php endif; ?>
               <?php if ($accomodation_module) {
                  if (check_user_previlege('p47')): ?>
                     <li><a href="<?php echo base_url() . 'index.php/cms/hotel_top_destinations' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotel Top Destinations</a></li>
               <?php endif;
               } ?>
               <?php if ($airline_module) {
                  if (check_user_previlege('p46')): ?>
                     <li><a href="<?php echo base_url() . 'index.php/cms/flight_top_destinations' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Flight Top Destinations</a></li>
               <?php endif;
               } ?>
               <?php if ($sightseen_module) {
                  if (check_user_previlege('p46')): ?>
                     <li><a href="<?php echo base_url() . 'index.php/cms/activity_top_destinations' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Top Activities</a></li>
               <?php endif;
               } ?>
               <li><a href="<?php echo base_url() . 'index.php/cms/why_choose_us' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Why Choose Us</a></li>

               <?php if(check_user_previlege('p54')):?>
                  <li><a href="<?php echo base_url() . 'index.php/cms/top_airlines' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Top Airlines</a></li>
               <?php endif;?>
               <li><a href="<?php echo base_url() . 'index.php/cms/add_contact_address' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Contact Address</a></li>
               <?php if (check_user_previlege('p57')): ?>
                  <li><a href="<?php echo base_url() . 'index.php/cms/terms_conditions' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Voucher Terms & Conditions</a></li>
               <?php endif; ?>
               <!-- <li><a href="<?php echo base_url() . 'index.php/cms/add_customer_support' ?>"></i> <span>Customer Support</span></a></li> -->
               </ul>
            </div>
            <!-- Dropdown Menu End -->
         </li>
     
        <?php endif;
            if (check_user_previlege('p61') && $visibility): ?>
      <li class="treeview">
         <a href="#" class="menu-item menu-item-inactive group">
            <i class="far fa-retweet"></i>
            <span class="menu-item-text">Masters</span>
            <svg
               class="menu-item-arrow menu-item-arrow-inactive"
               width="20"
               height="20"
               viewBox="0 0 20 20"
               fill="none"
               xmlns="http://www.w3.org/2000/svg"
            >
               <path
                  d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                  stroke=""
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
               />
            </svg>
         </a>
         <!-- Dropdown Menu Start -->
         <div class="translate transform overflow-hidden">
            <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
               <li><a href="<?php echo base_url() . 'index.php/utilities/country_list_master' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Countrys</a></li>
               <li><a href="<?php echo base_url() . 'index.php/utilities/cities_list_master' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Cities</a></li>
            </ul>
         </div>
         <!-- Dropdown Menu End -->
      </li>
       <?php endif; ?>
      <?php if (check_user_previlege('p12')): ?>
         <li class="treeview">
            <a href="#" class="menu-item menu-item-inactive group">
               <i class="bi bi-globe"></i>
               <span class="menu-item-text">SEO</span>
               <svg
                  class="menu-item-arrow menu-item-arrow-inactive"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
               >
                  <path
                     d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                     stroke=""
                     stroke-width="1.5"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                  />
               </svg>
            </a>
            <!-- Dropdown Menu Start -->
            <div class="translate transform overflow-hidden">
               <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
                  <li><a href="<?php echo base_url() . 'index.php/cms/seo' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Seo Keywords</a></li>
                  <li><a href="<?php echo base_url() . 'index.php/blog/dynamic_hotel_urls' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Hotels</a></li>
               </ul>
            </div>
            <!-- Dropdown Menu End -->
         </li>
      <?php endif;
   }
   if (check_user_previlege('p15') && $visibility): ?>
      <li class="treeview">
         <a href="<?php echo base_url() . 'index.php/management/bank_account_details' ?>" class="menu-item menu-item-inactive group">
            <i class="far fa-university"></i>
            <span class="menu-item-text">Bank Account Details</span>
         </a>
      </li>
   <?php endif;
   if (check_user_previlege('p15')): ?>
      <li class="treeview">
         <a href="<?php echo base_url() . 'index.php/general/email_configuration' ?>" class="menu-item menu-item-inactive group">
            <i class="bi bi-envelope"></i>
            <span class="menu-item-text">Email Configuration</span>
         </a>
      </li>
   <?php endif; ?>
   <?php if (check_user_previlege('p16')): ?>
      <li class="treeview">
         <a href="#" class="menu-item menu-item-inactive group">
            <i class="bi bi-gear"></i>
            <span class="menu-item-text">Settings</span>
            <svg
               class="menu-item-arrow menu-item-arrow-inactive"
               width="20"
               height="20"
               viewBox="0 0 20 20"
               fill="none"
               xmlns="http://www.w3.org/2000/svg"
            >
               <path
                  d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585"
                  stroke=""
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
               />
            </svg>
         </a>
         <!-- Dropdown Menu Start -->
         <div class="translate transform overflow-hidden">
            <ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
            <?php if (check_user_previlege('p69')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/user/manage_domain' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Manage Domain</a>
               </li>
            <?php endif; ?>
            <?php if (check_user_previlege('p59')): ?>
               <li> <a href="<?php echo base_url() . 'index.php/utilities/country_access' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Access Countrys</a> </li>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/convenience_fees' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Convenience Fees</a>
               </li>
               <li>
                  <a href="<?php echo base_url() . 'index.php/management/airport_list_master' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Manage Airline list</a>
               </li>
            <?php endif;
            if (check_user_previlege('p60')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/manage_promo_code' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Promo Code</a>
               </li>
            <?php endif;
            if (check_user_previlege('p61')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/manage_source' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Manage API</a>
               </li>
            <?php endif;
            if (check_user_previlege('p61') && $visibility): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/sms_checkpoint' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Manage SMS</a>
               </li>
            <?php endif;
            if (check_user_previlege('p62')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/currency_converter' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Currency Conversion</a>
               </li>
            <?php endif;
            if (check_user_previlege('p65')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/management/event_logs' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Event Logs</a>
               </li>
            <?php endif;
            if (check_user_previlege('p66')): ?>
               <!-- <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/app_settings' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Appearance</a>
               </li> -->
            <?php endif;
            if (check_user_previlege('p67')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/social_network' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Social Networks</a>
               </li>
            <?php endif;
            if (check_user_previlege('p68')): ?>
               <li>
                  <a href="<?php echo base_url() . 'index.php/utilities/social_login' ?>" class="menu-dropdown-item menu-dropdown-item-inactive group">Social Login</a>
               </li>
            <?php endif;
            if (check_user_previlege('p70')): ?>
               <!-- <li>
                  <a href="<?php echo base_url() ?>index.php/utilities/timeline" class="menu-dropdown-item menu-dropdown-item-inactive group">Live Events</a>
               </li> -->
            <?php endif; ?>
            </ul>
         </div>
         <!-- Dropdown Menu End -->
      </li>
   <?php endif; ?>
		</ul>
	</div>
	<!-- Menu Group End -->
</nav>
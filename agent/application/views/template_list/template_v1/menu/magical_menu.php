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
$sightseeing_module = is_active_sightseeing_module();
$car_module = is_active_car_module();
$transfer_module =is_active_transfer_module();
?>
<nav>

<!-- Logo -->
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
		<ul class="sidebar-menu mb-6 flex flex-col gap-4">
	<?php if(check_user_previlege('p1')):?>
	<li class="active treeview">
		<a href="<?php echo base_url()?>" class="menu-item group menu-item-active">
			<i class="material-icons">dashboard</i> <span class="menu-item-text">Dashboard</span>
		</a>
	</li>
	<?php endif; ?>
	<!-- USER ACCOUNT MANAGEMENT -->
	<?php if(check_user_previlege('p2')):?>
	<li class="treeview">
		<a href="#" class="menu-item group menu-item-inactive">
			<i class="material-icons">search</i><span class="menu-item-text"> Search </span><i class="material-icons menu-item-arrow menu-item-arrow-inactive float-end">chevron_left</i></a>
		<div class="translate transform overflow-hidden">
			<ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
		<!-- USER TYPES -->
		<?php if(check_user_previlege('p9')):?>
			<?php if ($airline_module) { ?>
			<li><a href="<?=base_url().'menu/index/flight/?default_view='.META_AIRLINE_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?> text-blue"><?=get_arrangement_material_icon(META_AIRLINE_COURSE)?></i> <span class="hidden-xs">Flight</span></a></li>
			<?php } ?>
			<?php endif; ?>
			<?php if(check_user_previlege('p10')):?>
			<?php if ($accomodation_module) { ?>
			<li><a href="<?=base_url().'menu/index/hotel/?default_view='.META_ACCOMODATION_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?> text-green"><?=get_arrangement_material_icon(META_ACCOMODATION_COURSE)?></i> <span class="hidden-xs">Hotel</span></a></li>
			<?php } ?>
			<?php endif; ?>
			<?php if ($bus_module) { ?>
			<li><a href="<?=base_url().'menu/index/bus/?default_view='.META_BUS_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?> text-red"><?=get_arrangement_material_icon(META_BUS_COURSE)?></i> <span class="hidden-xs">Bus</span></a></li>
			<?php } ?>
			<?php if($transfer_module){?>
				<li><a href="<?=base_url().'menu/index/transfers/?default_view='.META_TRANSFER_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?> text-red"><?=get_arrangement_material_icon(META_TRANSFERV1_COURSE)?></i> <span class="hidden-xs">Transfers</span></a></li>
			<?php }?>
			<?php if(check_user_previlege('p11')):?>
			<?php if($sightseeing_module){?>
				<li><a href="<?=base_url().'menu/index/sightseeing/?default_view='.META_SIGHTSEEING_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?> text-red"><?=get_arrangement_material_icon(META_SIGHTSEEING_COURSE)?></i> <span class="hidden-xs">Activities</span></a></li>
			<?php }?>
			<?php endif; ?>
			<?php if(check_user_previlege('p12')):?>
			<?php if($car_module){?>
				<li><a href="<?=base_url().'menu/index/car/?default_view='.META_CAR_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?> text-red"><?=get_arrangement_material_icon(META_CAR_COURSE)?></i> <span class="hidden-xs">Car</span></a></li>
			<?php }?>
			<?php endif; ?>
			<?php if(check_user_previlege('p13')):?>
			<?php if ($package_module) { ?>
			<li><a href="<?=base_url().'menu/index/package/?default_view='.META_PACKAGE_COURSE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_PACKAGE_COURSE)?> text-yellow"><?=get_arrangement_material_icon(META_PACKAGE_COURSE)?></i> <span class="hidden-xs">Holiday</span></a></li>
			<?php } ?>
			<?php endif; ?>
			</ul>
		</div>
	</li>
	<?php endif; ?>
	<?php 
	// if ($any_domain_module) {
		?>
		<?php if(check_user_previlege('p3')): ?>
	<li class="treeview">
		<a href="#" class="menu-item group menu-item-inactive">
			<i class="material-icons">bar_chart</i> 
			<span class="menu-item-text"> Reports </span><i class="material-icons menu-item-arrow menu-item-arrow-inactive float-end">chevron_left</i>
		</a>
		<div class="translate transform overflow-hidden">
			<ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
		<!-- USER TYPES -->
		<?php if(check_user_previlege('p14')): ?>
			<li><a href="#" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">book</i> Booking Details</a>
				<div class="translate transform overflow-hidden">
					<ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'report/flight/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"><?=get_arrangement_material_icon(META_AIRLINE_COURSE)?></i> Flight</a></li>
				<?php } ?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'report/hotel/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"><?=get_arrangement_material_icon(META_ACCOMODATION_COURSE)?></i> Hotel</a></li>
				<?php } ?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'report/bus/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"><?=get_arrangement_material_icon(META_BUS_COURSE)?></i> Bus</a></li>
				<?php } ?>
				<?php if($transfer_module){?>
					<li><a href="<?php echo base_url().'report/transfers/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"><?=get_arrangement_material_icon(META_TRANSFERV1_COURSE)?></i>Transfers</a></li>
				<?php }?>
				<?php if($sightseeing_module):?>
					<li><a href="<?php echo base_url().'report/activities/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"><?=get_arrangement_material_icon(META_SIGHTSEEING_COURSE)?></i>Activities</a></li>
				<?php endif;?>
				<?php if($car_module):?>
					<li><a href="<?php echo base_url().'report/car/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"><?=get_arrangement_material_icon(META_CAR_COURSE)?></i>Car</a></li>
				<?php endif;?>
					</ul>
				</div>
			</li>
			<?php endif;?>
			<?php if(check_user_previlege('p15')): ?>
			<li><a href="<?php echo base_url().'management/pnr_search'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">search</i> <span>PNR Search</span></a></li>
			<?php endif;?>
			<?php if(check_user_previlege('p16')): ?>
			<li><a href="<?php echo base_url().'report/flight?filter_booking_status=BOOKING_PENDING'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">confirmation_number</i> <span>Pending Ticket</span></a></li>
			<?php endif;?>
			<?php if(check_user_previlege('p17')): ?>
			<li><a href="<?php echo base_url().'report/flight?daily_sales_report='.ACTIVE?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">trending_up</i> <span>Daily Sales Report</span></a></li>
			<?php endif;?>
			<?php if(check_user_previlege('p18')): ?>
			<li><a href="<?php echo base_url().'management/account_ledger'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">calculate</i> <span>Account Ledger</span></a></li>
			<?php endif;?>
			<?php if(check_user_previlege('p19')): ?>
			<li class="treeview"><a href="<?php echo base_url().'index.php/transaction/logs'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">list_alt</i> <span> Transaction Logs </span></a></li>
			<?php endif;?>
			</ul>
		</div>
	</li>
	<?php endif;?>
	<?php
	// if($airline_module || $bus_module || $sightseeing_module) {

	?>
	<?php if(check_user_previlege('p4')): ?>
	<li class="treeview">
		<a href="#" class="menu-item group menu-item-inactive">
			<i class="material-icons">business_center</i> 
			<span class="menu-item-text"> My Commission </span><i class="material-icons menu-item-arrow menu-item-arrow-inactive float-end">chevron_left</i>
		</a>
		<div class="translate transform overflow-hidden">
			<ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
			<?php if(check_user_previlege('p20')): ?>
			<?php if ($airline_module) { ?>
			<li><a href="<?=base_url().'management/flight_commission';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"><?=get_arrangement_material_icon(META_AIRLINE_COURSE)?></i> Flight</a></li>
			<?php } ?>
			<?php endif;?>
			<?php if ($bus_module) { ?>
			<li><a href="<?=base_url().'management/bus_commission';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"><?=get_arrangement_material_icon(META_BUS_COURSE)?></i> Bus</a></li>
			<?php } ?>
			<?php if($transfer_module):?>
				<li><a href="<?=base_url().'management/transfer_commission';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"><?=get_arrangement_material_icon(META_TRANSFERV1_COURSE)?></i>Transfers</a></li>

			<?php endif;?>
			<?php if(check_user_previlege('p21')): ?>
			<?php if($sightseeing_module){?>
				<li><a href="<?=base_url().'management/sightseeing_commission';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"><?=get_arrangement_material_icon(META_SIGHTSEEING_COURSE)?></i>Activities</a></li>
			<?php }?>
			<?php endif;?>
		</ul>
	</li>
<?php endif;?>
	<?php 
// }
 ?>
	<?php if(check_user_previlege('p5')): ?>
	<li class="treeview">
		<a href="#" class="menu-item group menu-item-inactive">
			<i class="material-icons">add_circle</i> 
			<span class="menu-item-text"> My Markup </span><i class="material-icons menu-item-arrow menu-item-arrow-inactive float-end">chevron_left</i>
		</a>
		<div class="translate transform overflow-hidden">
			<ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
		<!-- USER TYPES -->
			<?php if(check_user_previlege('p22')): ?>
				<?php if ($airline_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_airline_markup/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"><?=get_arrangement_material_icon(META_AIRLINE_COURSE)?></i> Flight</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if(check_user_previlege('p23')): ?>
				<?php if ($accomodation_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_hotel_markup/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"><?=get_arrangement_material_icon(META_ACCOMODATION_COURSE)?></i> Hotel</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if ($bus_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_bus_markup/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_BUS_COURSE)?>"><?=get_arrangement_material_icon(META_BUS_COURSE)?></i> Bus</a></li>
				<?php } ?>

				<?php if ($transfer_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_transfer_markup/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_TRANSFERV1_COURSE)?>"><?=get_arrangement_material_icon(META_TRANSFERV1_COURSE)?></i>Transfers</a></li>
				<?php } ?>
				<?php if(check_user_previlege('p24')): ?>
				<?php if ($sightseeing_module) { ?>
				<li><a href="<?php echo base_url().'management/b2b_sightseeing_markup/';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_SIGHTSEEING_COURSE)?>"><?=get_arrangement_material_icon(META_SIGHTSEEING_COURSE)?></i>Activities</a></li>
				<?php } ?>
				<?php endif;?>
				<?php if(check_user_previlege('p25')): ?>
				<?php if($car_module){?>
				<li><a href="<?=base_url().'management/b2b_car_markup';?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="<?=get_arrangement_icon(META_CAR_COURSE)?>"><?=get_arrangement_material_icon(META_CAR_COURSE)?></i>Car</a></li>
			<?php }?>
			<?php endif;?>

		</ul>
	</li>
<?php endif;?>
	<?php 
// }
 ?>
	<?php if(check_user_previlege('p6')): ?>
	<li class="treeview">
		<a href="#" class="menu-item group menu-item-inactive">
			<i class="material-icons">account_balance_wallet</i> 
			<span class="menu-item-text"> Payment </span><i class="material-icons menu-item-arrow menu-item-arrow-inactive float-end">chevron_left</i>
		</a>
		<div class="translate transform overflow-hidden">
			<ul class="menu-dropdown mt-2 flex flex-col gap-1 pl-9">
		<!-- USER TYPES -->
		<?php if(check_user_previlege('p26')): ?>
			<li><a href="<?php echo base_url().'management/b2b_balance_manager'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">account_balance</i> Update Balance</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('p27')): ?>
			<li><a href="<?php echo base_url().'management/b2b_credit_limit'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">credit_card</i> Update Credit Limit</a></li>
			<?php endif;?>
			<?php if(check_user_previlege('p28')): ?>
			<li><a href="<?php echo base_url().'index.php/management/bank_account_details'?>" class="menu-dropdown-item group menu-dropdown-item-inactive"><i class="material-icons">account_balance</i> Bank Account Details</a></li>
			<?php endif;?>
			</ul>
		</div>
	</li>
	<?php endif;?>
	<?php if(check_user_previlege('p7')): ?>
	<li><a href="<?php echo base_url().'management/set_balance_alert'?>" class="menu-item group menu-item-inactive"><i class="material-icons">notifications</i> <span class="menu-item-text">Set Balance Alert</span></a></li>
<?php endif;?>
<?php if(check_user_previlege('p8')): ?>
	<li><a href="<?php echo base_url().'user/domain_logo'?>" class="menu-item group menu-item-inactive"><i class="material-icons">image</i> <span class="menu-item-text">Logo</span></a></li>
	<?php endif;?>
		</ul>
	</div>
	<!-- Menu Group End -->
</nav>
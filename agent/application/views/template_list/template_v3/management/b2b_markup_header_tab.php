<?php
$default_view = $GLOBALS ['CI']->uri->segment ( 2 );
?>
<ul class="nav nav-tabs  b2b_navul" role="tablist" id="myTab">
	<?php if (is_active_airline_module()) { ?>
	<li class="<?=(($default_view == 'b2b_airline_markup') ? 'active' : '')?>">
		 <a href="<?php echo base_url();?>management/b2b_airline_markup"><i class="bi bi-airplane"></i> Flight</a>
	</li>
	<?php } ?>
	<?php if (is_active_hotel_module()) { ?>
	<li class="<?=(($default_view == 'b2b_hotel_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_hotel_markup"><i class="bi bi-building"></i> Hotel</a>
	</li>
	<?php } ?>

	<?php if (is_active_bus_module()) { ?>
	<li class="<?=(($default_view == 'b2b_bus_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_bus_markup"><i class="bi bi-bus-front"></i> Bus</a>
	</li>
	<?php } ?>


	<?php if (is_active_transfer_module()) { ?>
	<li class="<?=(($default_view == 'b2b_transfer_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_transfer_markup"><i class="bi bi-taxi-front"></i> Transfers</a>
	</li>
	<?php } ?>


	<?php if (is_active_sightseeing_module()) { ?>
	<li class="<?=(($default_view == 'b2b_sightseeing_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_sightseeing_markup"><i class="bi bi-camera"></i> Activities</a>
	</li>
	<?php } ?>

	<?php if (is_active_car_module()) { ?>
	<li class="<?=(($default_view == 'b2b_car_markup') ? 'active' : '')?>">
		<a href="<?php echo base_url();?>management/b2b_car_markup"><i class="bi bi-car-front"></i> Car</a>
	</li>
	<?php } ?>
	
</ul>

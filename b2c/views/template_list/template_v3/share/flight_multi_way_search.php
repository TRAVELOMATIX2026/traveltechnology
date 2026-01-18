
<?php
$max_multicity_segments = 5;
$multicity_segment_count = ((!empty(@$multicity_segment_search_params['from_loc']) > 0 ) ? count($multicity_segment_search_params['from_loc']) : 2);
$multi_flight_datepicker = array();
for($i=1;$i<=$max_multicity_segments; $i++){
	$multi_flight_datepicker[$i] = array('m_flight_datepicker'.$i, FUTURE_DATE_DISABLED_MONTH);
}
$this->current_page->set_datepicker($multi_flight_datepicker);
?>
<span class="hide">
	<input type="hidden" id="max_multicity_segments" value="<?=$max_multicity_segments?>">
	<input type="hidden" id="multicity_segment_count" value="<?=$multicity_segment_count?>">
</span>

<div id="multi_way_fieldset" class="col-md-10 nopad" style="display: none; position: relative;">

	<?php for($multi_k=1;$multi_k<=$max_multicity_segments; $multi_k++){ 
			if (intval($multi_k) > intval($multicity_segment_count)) {
				$segment_visibility = 'display:none !important';
				$segment_class = 'inactive_segment';
				$flex_class = 'row';
			} else {
				$segment_visibility = '';
				$segment_class = '';
				$flex_class = 'row';
			}
	?>
			<div class="multi_city_container m-0 !mb-2 <?=$flex_class?> bdr-btm <?=$segment_class?>" id="multi_city_container_<?=$multi_k?>" style="<?=$segment_visibility?>">
				<div class="col-md-8 col-9 nopad placerows mfulwdth row m-0">
				<div class="col-6 bordrt padfive p-0"> 
				
					<div class="lablform mobile_label">From</div>
					<div class="plcetogo deprtures sidebord">
						<input type="text" autocomplete="off" name="from[]" class="m_depcity normalinput auto-focus valid_class fromflight form-control b-r-0" id="m_from<?=$multi_k?>" placeholder="Type Departure City" value="<?php echo @$multicity_segment_search_params['from'][$multi_k-1]?>" required />
						<input class="hide loc_id_holder" name="from_loc_id[]" type="hidden" value="<?=@$multicity_segment_search_params['from_loc_id'][$multi_k-1]?>" >
						<input class="hide airport_loc" id="fromairportloc<?=$multi_k?>" name="fromairportloc[]" type="hidden" value="<?=@$multicity_segment_search_params['fromairportloc'][$multi_k-1]?>" >
						<span class="airport_value" id="fromairport_span_val"><?=(@$multicity_segment_search_params['fromairportloc'][$multi_k-1])? @$multicity_segment_search_params['fromairportloc'][$multi_k-1] :'International Airport';?></span>
			
					</div>
					<div class="exportcomplete" id="multi_expotfflight" style="display: none;"></div>
				</div>
				<div class="col-6 pe-0">
					<div class="padfive">
					<div class="lablform mobile_label">To</div>
					<div class="plcetogo destinatios sidebord">
						<input type="text" autocomplete="off" name="to[]"  class="m_arrcity normalinput auto-focus valid_class departflight form-control b-r-0" id="m_to<?=$multi_k?>" placeholder="Type Destination City" value="<?php echo @$multicity_segment_search_params['to'][$multi_k-1] ?>" required/>
						<input class="hide loc_id_holder" name="to_loc_id[]" type="hidden" value="<?=@$multicity_segment_search_params['to_loc_id'][$multi_k-1]?>" >
						<input class="hide airport_loc" id="toairportloc<?=$multi_k?>" name="toairportloc[]" type="hidden" value="<?=@$multicity_segment_search_params['toairportloc'][$multi_k-1]?>" >
						<span class="airport_value" id="span_airport_val"><?=(@$multicity_segment_search_params['toairportloc'][$multi_k-1])? @$multicity_segment_search_params['toairportloc'][$multi_k-1] :'International Airport';?></span>
					</div>
					</div>
				</div>
				<div class="exportcomplete" id="multi_expottoflight" style="display: none;"></div>
				</div>
				<div class="col-md-4 col-3 secndates  mfulwdth">
					<div class="col-12 bordrt padfive mpad bdr-btm">
							<div class="lablform mobile_label">Departure</div>
						<div class="plcetogo datemark sidebord">
							<input type="text" name="depature[]" class="m_depature_date normalinput auto-focus hand-cursor form-control b-r-0 disable-date-auto-update" id="m_flight_datepicker<?=$multi_k?>" placeholder="Select Date" value="<?php echo @$multicity_segment_search_params['depature'][$multi_k-1] ?>" readonly required/>
							<span id="day_names" class="day_name checkInDayDiv"></span> 
						</div>
					</div>
                    <?php if($multi_k > 2) { ?>
	                    <button class="city_close_btn remove_city"> <span class="material-icons">close</span></button>
					<?php } ?>
                    
				</div>


                
			</div>



	<?php }?>

	<!-- <button style="display:none;" class="add_city_btn" id="add_city"> <span class="fa fa-plus"></span> Add City</button> -->
</div>




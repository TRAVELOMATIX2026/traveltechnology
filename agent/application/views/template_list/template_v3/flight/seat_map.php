<!-- SEAT SELECTION -->
<div class="seat_selection_wrapper">
      <div class="seat-summary"> </div>
      
      <div class="seat_selection_header">
      	<div class="seat_legend_container">
      		<div class="seat_legend_title">
      			<i class="material-icons">info</i>
      			<span>Seat Status</span>
      		</div>
      		<div class="seat_legend_items">
	               	<div class="seat_legend_item">
	                     <span class="seat_legend_icon seat_legend_available">
	                     	<i class="material-icons">event_seat</i>
	                     </span>
	                     <span class="seat_legend_text">Available</span>
	                </div>
	                <div class="seat_legend_item">
	                     <span class="seat_legend_icon seat_legend_selected">
	                     	<i class="material-icons">event_seat</i>
	                     </span>
	                     <span class="seat_legend_text">Selected</span>
	                </div>
	                <div class="seat_legend_item">
	                     <span class="seat_legend_icon seat_legend_occupied">
	                     	<i class="material-icons">event_seat</i>
	                     </span>
	                     <span class="seat_legend_text">Occupied</span>
	                </div>
	                <div class="seat_legend_item">
	                     <span class="seat_legend_icon seat_legend_not_exist">
	                     	<i class="material-icons">block</i>
	                     </span>
	                     <span class="seat_legend_text">Not Exist</span>
	                </div>
	         </div>
         </div>
         <button type="button" class="seat_remove_btn" id="remove-extra-seat" style="display: none;">
         	<i class="material-icons">close</i>
         	<span>Remove Seat</span>
         </button>
      </div>
      
      <div class="seat_selection_content">
      	<div class="seat_map_container">
         <div class="seat_segment_tabs">
         <?php foreach ($seat_data as $seg_seat_k => $seg_seat_v){?>
         		<?php
         		$flight_segment_label = $seg_seat_v[0][0]['Origin'].' → '.$seg_seat_v[0][0]['Destination'].' ('.$seg_seat_v[0][0]['AirlineCode'].', '.$seg_seat_v[0][0]['FlightNumber'].')';
         		if($seg_seat_k == 0){
		         	$active_tab_fade_cls = 'active';
		        } else {
		         	$active_tab_fade_cls = '';
		        }
         		?>
		         <button type="button" class="seat_segment_tab <?=$active_tab_fade_cls?> seat_segment_map" data-bs-toggle="tab" data-bs-target="#seat_map<?=$seg_seat_k?>" role="tab">
		         	<i class="material-icons">flight</i>
		         	<span><?=$flight_segment_label?></span>
		         </button>
         <?php } ?>
         </div>
         <div class="tab-content seat_map_tabs_content">
         
         <?php foreach ($seat_data as $seg_seat_k => $seg_seat_v){ ?>
		         <?php
		         	if($seg_seat_k == 0){
		         		$active_tab_fade_cls = 'show active';
		         	} else {
		         		$active_tab_fade_cls = '';
		         	}
					if($booking_source == PROVAB_FLIGHT_BOOKING_SOURCE){
						$ColumnLayOut[$seg_seat_k] = get_seat_layout($seg_seat_v);
					}
					$seat_columns = $ColumnLayOut ? count($ColumnLayOut[$seg_seat_k]) : 0;
		         ?>
		         <!-- Flight Seat Map Starts -->
		            <div id="seat_map<?=$seg_seat_k?>" class="tab-pane fade <?=$active_tab_fade_cls?>" role="tabpanel">
		               <div class="seat_map_card">
		                  <div class="seat_map_table_wrapper">
		                     <table class="seat_map_table">
		                        <thead>
		                           <tr class="seat_map_header_row">
		                              <th class="seat_row_number_header"></th>
		                              <?php if($ColumnLayOut){ ?>
		                              	<?php foreach($ColumnLayOut[$seg_seat_k] as $Column){ ?>
		                              		<th class="seat_column_header"><?=$Column['Name'] == 'GAP' ? '':$Column['Name']?></th>
		                              	<?php } ?>
		                              <?php }else{ ?>
		                              	<th class="seat_column_header">A</th>
		                              	<th class="seat_column_header">B</th>
		                              	<th class="seat_column_header">C</th>
		                              	<th class="seat_column_header seat_aisle"></th>
		                              	<th class="seat_column_header">D</th>
		                              	<th class="seat_column_header">E</th>
		                              	<th class="seat_column_header">F</th>
		                              <?php } ?>
		                           </tr>
		                        </thead>
		                        <tbody>
		                              <?php foreach($seg_seat_v as $seat_row_k => $seat_row_v){ ?>
		                              	<tr class="seat_map_row">
		                              		<td class="seat_row_number"><?=$seat_row_v[0]['RowNumber']?></td>
		                              		<?php
		                              			foreach ($seat_row_v as $seat_index => $seat_value) {
												if(intval($seat_value['AvailablityType']) === 1){
													$seat_image = SYSTEM_IMAGE_DIR.'available.png';
													$seat_availability_class = 'choose_seat';
													$seat_data_attributes = 'title="'.$seat_value['SeatNumber'].', '.$seat_value['Price'].'"
														data-seat_number="'.$seat_value['SeatNumber'].'"
														data-seat_price="'.$seat_value['Price'].'"
														data-seat_id="'.$seat_value['SeatId'].'"';
												} else {
													$seat_image = SYSTEM_IMAGE_DIR.'occupied.png';
													$seat_availability_class = '';
													$seat_data_attributes = '';
												}
											?>
												<td class="seat_cell">
													<div class="seat_icon_wrapper <?=$seat_availability_class?>" data-bs-toggle="tooltip" <?=$seat_data_attributes?>>
														<?php if(intval($seat_value['AvailablityType']) === 1) { ?>
															<i class="material-icons seat_icon_available">event_seat</i>
															<span class="seat_number_label"><?=$seat_value['SeatNumber']?></span>
														<?php } else { ?>
															<i class="material-icons seat_icon_occupied">event_seat</i>
														<?php } ?>
													</div>
												</td>
											<?php } ?>
				                      	</tr>
		                              <?php } ?>
		                        </tbody>
		                     </table>
		                  </div>
		               </div>
		            </div>
		            <!-- Flight Seat Map Ends -->
            
            <?php }?>
            
         </div>
      	</div>
      <!-- Showing Selected Seats and Price Details -- STARTS -->
      <div class="seat_passenger_details_container">
      	<div class="seat_passenger_details_header">
      		<i class="material-icons">people</i>
      		<span>Passenger Seat Details</span>
      	</div>
      	
      	<div class="seat_passenger_segments">
	      	<?php foreach ($seat_data as $seg_seat_k => $seg_seat_v){ ?>
	         	<?php
	         	$flight_segment_label = $seg_seat_v[0][0]['Origin'].' → '.$seg_seat_v[0][0]['Destination'].' ('.$seg_seat_v[0][0]['AirlineCode'].', '.$seg_seat_v[0][0]['FlightNumber'].')';
	         	?>
	         	<div class="seat_passenger_segment_card seat_segment_pax">
	         		<div class="seat_segment_header">
	         			<i class="material-icons">flight</i>
	         			<span class="seat_segment_pax_label"><?=$flight_segment_label?></span>
	         		</div>
	         		
	         		<div class="seat_passenger_table_wrapper">
		         		<table class="seat_passenger_table">
		         			<thead>
		         				<tr>
		         					<th class="seat_pax_col">Passenger</th>
		         					<th class="seat_number_col">Seat</th>
		         					<th class="seat_price_col">Price</th>
		         				</tr>
		         			</thead>
		         			<tbody>
		         				<?php
		         				for($ex_seat_pax_index=1; $ex_seat_pax_index <= $total_pax_count; $ex_seat_pax_index++) {
		         					$pax_type = pax_type($ex_seat_pax_index, $total_adult_count, $total_child_count, $total_infant_count);
		         					$pax_type_count = pax_type_count($ex_seat_pax_index, $total_adult_count, $total_child_count, $total_infant_count);
		         					if($pax_type != 'infant'){
		         						$pax_icon = ($pax_type == 'adult') ? 'person' : 'child_care';
		         				?>
		         					<tr class="seat_segment_pax_tr">
		         						<input type="hidden" name="seat_<?=$seg_seat_k?>[]" class="choosen_seat" data-seat_price="">
		         						<td class="seat_pax_name">
		         							<div class="seat_pax_name_wrapper">
		         								<i class="material-icons"><?=$pax_icon?></i>
		         								<span><?=ucfirst($pax_type)?> <?=($pax_type_count)?></span>
		         							</div>
		         						</td>
		         						<td class="seat_pax_number">
		         							<span class="seat_number_display">-</span>
		         						</td>
		         						<td class="seat_pax_price">
		         							<span class="seat_price_display">-</span>
		         						</td>
		         					</tr>
		         				<?php }
		         				}
		         				?>
		         			</tbody>
		         		</table>
		         	</div>
	         	</div>
         	<?php } ?>
         </div>
      </div>
      <!-- Showing Selected Seats and Price Details -- ENDS -->
   <!-- <div class="mybtnc">
   		<button name="flight" type="submit" class="btn btn-lg btn-warning continue_booking_button">Continue</button>
   	</div> -->
</div>
<script>
var system_image_dir_url = '<?=SYSTEM_IMAGE_DIR?>';
</script>
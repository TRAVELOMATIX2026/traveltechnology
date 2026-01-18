<?php if (
	isset($baggage_meal_details) == true && (isset($baggage_meal_details['Baggage']) == true && valid_array($baggage_meal_details['Baggage']) == true)
	or (isset($baggage_meal_details['Meals']) == true && valid_array($baggage_meal_details['Meals']) == true)
	or (isset($baggage_meal_details['Seat']) == true && valid_array($baggage_meal_details['Seat']) == true)
) {

	$Baggage = @$baggage_meal_details['Baggage'];
	$Meals = @$baggage_meal_details['Meals'];
	$Seat = @$baggage_meal_details['Seat'];
	$ColumnLayOut = @$baggage_meal_details['ColumnLayOut'];
?>
	<div class="extras_toggle_section">
		<button class="extras_toggle_btn" type="button" data-bs-toggle="collapse" data-bs-target="#serviceRequestsCollapse" aria-expanded="true" aria-controls="serviceRequestsCollapse">
			<div class="toggle_text">
				<i class="material-icons">description</i>
				<span>Service Requests (Optional)</span>
			</div>
			<i class="material-icons arrow_icon">expand_more</i>
		</button>
		<div class="collapse show" id="serviceRequestsCollapse">
			<div class="extras_content">
					<div class="baggage_meal_details">
						<div class="service_tabs_container">
							<div class="service_tabs_wrapper" role="tablist">
								<?php if (valid_array($Baggage) == true) { ?>
									<button class="service_tab_btn active" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#extra_services_tab_baggage" aria-controls="extra_services_tab_baggage" aria-selected="true">
										<div class="service_tab_icon">
											<i class="material-icons">luggage</i>
										</div>
										<div class="service_tab_content">
											<span class="service_tab_title">Add Baggage</span>
											<span class="service_tab_subtitle">Extra luggage</span>
										</div>
										<i class="material-icons service_tab_arrow">add_circle</i>
									</button>
								<?php }
								if (valid_array($Meals) == true) { ?>
									<button class="service_tab_btn <?= !valid_array($Baggage) ? 'active' : '' ?>" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#extra_services_tab_meal" aria-controls="extra_services_tab_meal" aria-selected="<?= !valid_array($Baggage) ? 'true' : 'false' ?>">
										<div class="service_tab_icon">
											<i class="material-icons">restaurant</i>
										</div>
										<div class="service_tab_content">
											<span class="service_tab_title">Add Meal</span>
											<span class="service_tab_subtitle">In-flight meals</span>
										</div>
										<i class="material-icons service_tab_arrow">add_circle</i>
									</button>
								<?php } ?>
								<?php
								if (valid_array($Seat) == true) { ?>
									<button class="service_tab_btn <?= (!valid_array($Baggage) && !valid_array($Meals)) ? 'active' : '' ?>" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#extra_services_tab_seat" aria-controls="extra_services_tab_seat" aria-selected="<?= (!valid_array($Baggage) && !valid_array($Meals)) ? 'true' : 'false' ?>">
										<div class="service_tab_icon">
											<i class="material-icons">event_seat</i>
										</div>
										<div class="service_tab_content">
											<span class="service_tab_title">Seat Selection</span>
											<span class="service_tab_subtitle">Choose your seat</span>
										</div>
										<i class="material-icons service_tab_arrow">arrow_forward</i>
									</button>
								<?php } ?>
							</div>
						</div>
						<div class="tab-content service_tab_content_wrapper">
							<!-- Baggage Starts -->
							<div role="tabpanel" class="pasngrinput tab-pane fade other_inp <?= valid_array($Baggage) ? 'show active' : '' ?>" id="extra_services_tab_baggage">
								<?php
								//Baggage
								if (valid_array($Baggage) == true) { ?>
									<div class="service_content_wrapper">
										<div class="service_header">
											<div class="service_header_title">
												<i class="material-icons">luggage</i>
												<span>Choose Extra Baggage</span>
											</div>
											<button type="button" class="service_remove_btn" id="remove-extra-baggage" style="display: none;">
												<i class="material-icons">close</i>
												<span>Remove Baggage</span>
											</button>
										</div>
										
										<?php
										$bag_input_counter = 0;
										foreach ($Baggage as $bag_ok => $bag_ov) {
											$baggage_label = $bag_ov[0]['Origin'] . ' → ' . $bag_ov[0]['Destination'];
										?>
											<div class="service_segment_card">
												<div class="service_segment_header">
													<i class="material-icons">flight</i>
													<span class="service_segment_route"><?= $baggage_label ?></span>
												</div>
												
												<div class="service_passengers_list">
													<?php
													for ($ex_pax_index = 1; $ex_pax_index <= $total_pax_count; $ex_pax_index++) {
														$pax_type = pax_type($ex_pax_index, $total_adult_count, $total_child_count, $total_infant_count);
														$pax_type_count = pax_type_count($ex_pax_index, $total_adult_count, $total_child_count, $total_infant_count);
														if ($pax_type != 'infant') {
															$pax_icon = ($pax_type == 'adult') ? 'person' : 'child_care';
													?>
															<div class="service_passenger_item">
																<div class="service_passenger_info">
																	<div class="service_passenger_icon">
																		<i class="material-icons"><?= $pax_icon ?></i>
																	</div>
																	<div class="service_passenger_details">
																		<span class="service_passenger_name"><?= ucfirst($pax_type) ?> <?= $pax_type_count ?></span>
																	</div>
																</div>
																<div class="service_passenger_input">
																	<select name="baggage_<?= $bag_input_counter ?>[]" class="add_extra_service choosen_baggage modern_select">
																		<option value="">Select Baggage</option>
																		<?php foreach ($bag_ov as $bag_k => $bag_v) { ?>
																			<option data-choosen-baggage-price="<?= round($bag_v['Price']) ?>" value="<?= $bag_v['BaggageId'] ?>">
																				<?= $bag_v['Weight'] . ' - ' . round($bag_v['Price']) . ' ' . get_application_currency_preference() ?>
																			</option>
																		<?php } ?>
																	</select>
																	<i class="material-icons select_arrow">keyboard_arrow_down</i>
																</div>
															</div>
													<?php }
													}
													$bag_input_counter++;
													?>
												</div>
											</div>
										<?php } ?>
									</div>
								<?php } ?>

							</div>
							<!-- Baggage Ends -->

							<!-- Meal Starts -->
							<div role="tabpanel" class="tab-pane fade pasngrinput other_inp <?= (!valid_array($Baggage) && valid_array($Meals)) ? 'show active' : '' ?>" id="extra_services_tab_meal">
								<?php
								//Meals
								if (valid_array($Meals) == true) { ?>
									<div class="service_content_wrapper">
										<div class="service_header">
											<div class="service_header_title">
												<i class="material-icons">restaurant</i>
												<span>Choose Your Meal</span>
											</div>
											<button type="button" class="service_remove_btn" id="remove-extra-meal">
												<i class="material-icons">close</i>
												<span>Remove Meal</span>
											</button>
										</div>
										
										<?php
										$meal_input_counter = 0;
										foreach ($Meals as $meal_ok => $meal_ov) {
											$meal_label = $meal_ov[0]['Origin'] . ' → ' . $meal_ov[0]['Destination'];
										?>
											<div class="service_segment_card">
												<div class="service_segment_header">
													<i class="material-icons">flight</i>
													<span class="service_segment_route"><?= $meal_label ?></span>
												</div>
												
												<div class="service_passengers_list">
													<?php
													for ($ex_pax_index = 1; $ex_pax_index <= $total_pax_count; $ex_pax_index++) {
														$pax_type = pax_type($ex_pax_index, $total_adult_count, $total_child_count, $total_infant_count);
														$pax_type_count = pax_type_count($ex_pax_index, $total_adult_count, $total_child_count, $total_infant_count);
														if ($pax_type != 'infant') {
															$pax_icon = ($pax_type == 'adult') ? 'person' : 'child_care';
													?>
															<div class="service_passenger_item">
																<div class="service_passenger_info">
																	<div class="service_passenger_icon">
																		<i class="material-icons"><?= $pax_icon ?></i>
																	</div>
																	<div class="service_passenger_details">
																		<span class="service_passenger_name"><?= ucfirst($pax_type) ?> <?= $pax_type_count ?></span>
																	</div>
																</div>
																<div class="service_passenger_input">
																	<select name="meal_<?= $meal_input_counter ?>[]" class="add_extra_service choosen_meal modern_select">
																		<option value="">Select Meal</option>
																		<?php foreach ($meal_ov as $meal_k => $meal_v) { ?>
																			<option data-choosen-meal-price="<?= round($meal_v['Price']) ?>" value="<?= $meal_v['MealId'] ?>">
																				<?= $meal_v['Description'] . ' - ' . round($meal_v['Price']) . ' ' . get_application_currency_preference() ?>
																			</option>
																		<?php } ?>
																	</select>
																	<i class="material-icons select_arrow">keyboard_arrow_down</i>
																</div>
															</div>
													<?php }
													}
													$meal_input_counter++;
													?>
												</div>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<!-- Meal Ends -->


							<!-- Seat Starts -->
							<div role="tabpanel" class="tab-pane fade pasngrinput other_inp <?= (!valid_array($Baggage) && !valid_array($Meals) && valid_array($Seat)) ? 'show active' : '' ?>" id="extra_services_tab_seat">
								<?php
								//Seat
								if (valid_array($Seat) == true) {
									$seat_map_data['seat_data'] = $Seat;
									$seat_map_data['ColumnLayOut'] = @$ColumnLayOut;
									$seat_map_data['total_adult_count'] = $total_adult_count;
									$seat_map_data['total_child_count'] = $total_child_count;
									$seat_map_data['total_infant_count'] = $total_infant_count;
									$seat_map_data['total_pax_count'] = $total_pax_count;
									$seat_map_data['booking_source'] = $booking_source;
									echo $GLOBALS['CI']->template->isolated_view('flight/seat_map', $seat_map_data);
								} ?>
							</div>
							<!-- Seat Ends -->

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<script type="text/javascript">
	// Handle service tab active states
	$(document).ready(function() {
		// Initialize first tab as active if not already set
		var $firstTab = $('.service_tab_btn').first();
		if ($firstTab.length && !$firstTab.hasClass('active')) {
			$firstTab.addClass('active').attr('aria-selected', 'true');
		}
		
		// Handle tab click events
		$('.service_tab_btn').on('click', function() {
			// Remove active class from all tabs
			$('.service_tab_btn').removeClass('active').attr('aria-selected', 'false');
			// Add active class to clicked tab
			$(this).addClass('active').attr('aria-selected', 'true');
		});
		
		// Handle Bootstrap tab events for proper synchronization
		$('.service_tab_btn[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
			$('.service_tab_btn').removeClass('active').attr('aria-selected', 'false');
			$(this).addClass('active').attr('aria-selected', 'true');
		});
	});
</script>


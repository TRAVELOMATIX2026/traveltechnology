<!-- HTML BEGIN -->

<div class="bodyContent">
	<div class="table_outer_wrper"><!-- PANEL WRAP START -->
		<div class="panel_custom_heading"><!-- PANEL HEAD START -->
        
        <div class="panel_title">
        	<?php include 'b2b_markup_header_tab.php';?>
        </div>
        
			<div class="set_wraper">
            <div class="panel_title_bak">
            <div class="float-start">
				<i class="fa fa-edit"></i> Manage Flight Markup
            </div>
				<span class="float-end">Note : Application Default Currency - 
                <strong><?=get_application_default_currency()?></strong>			
                </span>
		
			</div>
            </div>
            
		</div><!-- PANEL HEAD START -->
		<div class="panel_bdy mb-3"><!-- Add Airline Starts-->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0"><i class="fa fa-plane"></i> Add Airline  <i class=" fa fa-plus"></i></h5>
				</div>
				<div class="card-body">
					<form action="" method="POST" autocomplete="off">
						<input type="hidden" name="form_values_origin" value="add_airline" />
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="airline_code" class="form-label">Airlines<span class="text-danger">*</span></label>
									<select class="form-control" id="airline_code" name="airline_code" required="required">
										<option value="">Please Select</option>
										<?php echo generate_options($airline_list);?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="form-label">Markup Type<span class="text-danger">*</span></label>
									<div class="d-flex gap-3">
										<label for="airline_value_type_plus" class="form-check-label">
											<input checked="checked" type="radio" value="plus" id="airline_value_type_plus" name="value_type" class="form-check-input value_type_plus radioIp" required=""> Plus(+ <?=get_application_default_currency()?>)
										</label>
										<label for="airline_value_type_percent" class="form-check-label">
											<input type="radio" value="percentage" id="airline_value_type_percent" name="value_type" class="form-check-input value_type_percent radioIp" required=""> Percentage(%)
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="new_airline_value" class="form-label">Markup Value</label>
									<input type="text" id="new_airline_value" name="specific_value" class="form-control generic_value numeric" placeholder="Markup Value" value="" />
								</div>
							</div>
						</div>
						<div class="form-group mt-3">
							<button class="btn btn-sm btn-success" id="add-airline-submit-btn" type="submit">Add</button>
							<button class="btn btn-sm btn-warning" id="add-airline-reset-btn" type="reset">Reset</button>
						</div>
					</form>
				</div>
			</div>
		</div><!-- Add Airline Ends-->
		<div class="panel_bdy mb-3">
		<!-- PANEL BODY START -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0"><i class="fa fa-plane"></i> Flight - General Markup</h5>
				</div>
				<div class="card-body">
					<form action="" method="POST" autocomplete="off">
						<input type="hidden" name="form_values_origin" value="generic" />
						<input type="hidden" name="markup_origin" value="<?=@$generic_markup_list[0]['markup_origin']?>" />
						<?php
						$default_percentage_status = $default_plus_status = '';
						if (isset($generic_markup_list[0]) == false || $generic_markup_list[0]['value_type'] == 'percentage') {
							$default_percentage_status = 'checked="checked"';
						} else {
							$default_plus_status = 'checked="checked"';
						}
						?>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label">Markup Type<span class="text-danger">*</span></label>
									<div class="d-flex gap-3">
										<label for="value_type_plus" class="form-check-label">
											<input <?=$default_plus_status?> type="radio" value="plus" id="value_type_plus" name="value_type" class="form-check-input value_type_plus radioIp" required=""> Plus(+ <?=get_application_default_currency()?>)
										</label>
										<label for="value_type_percent" class="form-check-label">
											<input <?=$default_percentage_status?> type="radio" value="percentage" id="value_type_percent" name="value_type" class="form-check-input value_type_percent radioIp" required=""> Percentage(%)
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="generic_value" class="form-label">Markup Value<span class="text-danger">*</span></label>
									<input type="text" id="generic_value" name="generic_value" class="form-control generic_value numeric" placeholder="Markup Value" required="" value="<?=@$generic_markup_list[0]['value']?>" />
								</div>
							</div>
						</div>
						<div class="form-group mt-3">
							<button class="btn btn-sm btn-success" id="general-markup-submit-btn" type="submit">Save</button>
							<button class="btn btn-sm btn-warning" id="general-markup-reset-btn" type="reset">Reset</button>
						</div>
					</form>
				</div>
			</div>
		</div><!-- PANEL BODY END -->
		<?php if (valid_array($specific_markup_list)) {//Check if airline list is present -Start IF ?>
		<div class="panel_bdy mb-3"><!-- PANEL BODY START -->
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0"><i class="fa fa-plane"></i> Flight - Specific Airline Markup</h5>
				</div>
				<div class="card-body">
					<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" autocomplete="off">
						<input type="hidden" name="form_values_origin" value="specific" />
						<?php foreach ($specific_markup_list as $__airline_index => $__airline_record) {
							$default_percentage_status = $default_plus_status = '';
							if (empty($__airline_record['value_type']) == true || $__airline_record['value_type'] == 'percentage') {
								$default_percentage_status = 'checked="checked"';
							} else {
								$default_plus_status = 'checked="checked"';
							}
						?>
							<input type="hidden" name="airline_origin[]" value="<?=$__airline_record['airline_origin']?>" />
							<input type="hidden" name="markup_origin[]" value="<?=$__airline_record['markup_origin']?>" />
							<div class="row mb-3">
								<div class="col-md-2">
									<div class="d-flex align-items-center gap-2">
										<span><?=($__airline_index+1);?></span>
										<img src="<?=SYSTEM_IMAGE_DIR?>airline_logo/<?=$__airline_record['airline_code']?>.svg" alt="<?=$__airline_record['airline_name']?>" class="img-fluid" style="max-height: 30px;">
									</div>
								</div>
								<div class="col-md-2 d-flex align-items-center">
									<span><?=$__airline_record['airline_name']?></span>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="form-label">Markup Type<span class="text-danger">*</span></label>
										<div class="d-flex gap-3">
											<label for="value-type-plus-<?=$__airline_index?>" class="form-check-label">
												<input <?=$default_plus_status?> type="radio" value="plus" id="value-type-plus-<?=$__airline_index?>" name="value_type_<?=$__airline_record['airline_origin']?>" class="form-check-input value-type-plus radioIp" required=""> Plus(+ <?=get_application_default_currency()?>)
											</label>
											<label for="value-type-percent-<?=$__airline_index?>" class="form-check-label">
												<input <?=$default_percentage_status?> type="radio" value="percentage" id="value-type-percent-<?=$__airline_index?>" name="value_type_<?=$__airline_record['airline_origin']?>" class="form-check-input value-type-percent radioIp" required=""> Percentage(%)
											</label>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="specific-value-<?=$__airline_index?>" class="form-label">Value</label>
										<input type="text" id="specific-value-<?=$__airline_index?>" name="specific_value[]" class="form-control specific-value numeric" placeholder="Markup Value" value="<?=$__airline_record['value']?>" />
									</div>
								</div>
							</div>
							<?php if ($__airline_index < count($specific_markup_list) - 1) { ?>
							<hr class="my-3">
							<?php } ?>
						<?php } ?>
						<div class="form-group mt-3">
							<button class="btn btn-sm btn-success" type="submit">Save</button>
							<button class="btn btn-sm btn-warning" type="reset">Reset</button>
						</div>
					</form>
				</div>
			</div>
		</div><!-- PANEL BODY END -->
		<?php } //check if airline list is present - End IF?>
	</div><!-- PANEL WRAP END -->
</div>
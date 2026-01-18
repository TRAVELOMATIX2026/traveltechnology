<!-- HTML BEGIN -->
<div class="bodyContent col-md-12">
<h4 class="mb-2">Manage Application Markup</h4>
<p class="text-muted small mb-3">Note : Application Default Currency - <strong><?= get_application_default_currency() ?></strong></p>
<div class="panel <?= PANEL_WRAPPER ?> card clearfix"><!-- PANEL WRAP START -->
<div class="card-body p-4"><!-- Add Airline Starts-->
<fieldset><legend class="form_legend"><i class="bi bi-airplane"></i> Add Airline <i class="bi bi-plus-circle"></i></legend>
                <form action="" class="row" method="POST" autocomplete="off">
                    <input type="hidden" name="form_values_origin" value="add_airline" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="new_airline_value" class="col-sm-3 form-label">Airlines<span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control" name="airline_code" required="required">
                                        <option value="">Please Select</option>
                                        <?php echo generate_options($airline_list); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="radio">
                                <label for="value_type" class="col-sm-4 form-label nopad text-center">Markup Type<span class="text-danger">*</span></label>
                                <label for="airline_value_type_plus" class="form-check form-check-inline pt-0">
                                    <input checked="checked" type="radio" value="plus" id="airline_value_type_plus" name="value_type" class=" value_type_plus radioIp" checked="checked" required=""> Plus(+ <?= get_application_default_currency() ?>)
                                </label>
                                <label for="airline_value_type_percent" class="form-check form-check-inline pt-0">
                                    <input type="radio" value="percentage" id="airline_value_type_percent" name="value_type" class=" value_type_percent radioIp" required=""> Percentage(%)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group pt-5">
                                <label for="new_airline_value" class="col-sm-4 form-label pt-3">Markup Value</label>
                                <input type="text" id="new_airline_value" name="specific_value" class=" generic_value numeric" placeholder="Markup Value" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-sm btn-gradient-success" id="add-airline-submit-btn" type="submit"><i class="bi bi-plus-circle"></i> Add</button>
                            <button class="btn btn-sm btn-gradient-warning" id="add-airline-reset-btn" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>
                </form>
            </fieldset>
        </div><!-- Add Airline Ends-->
        <div class="card-body p-4"><!-- PANEL BODY START /General Markup Starts-->
            <fieldset><legend class="form_legend"><i class="bi bi-airplane"></i> Flight - General Markup</legend>
                <form action="" class="row" method="POST" autocomplete="off">
                    <div class="hide">
                        <input type="hidden" name="form_values_origin" value="generic" />
                        <input type="hidden" name="markup_origin" value="<?= @$generic_markup_list[0]['markup_origin'] ?>" />
                    </div>
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
                            <div class="radio">
                                <label for="value_type" class="col-sm-4 form-label pt-0">Markup Type<span class="text-danger">*</span></label>
                                <label for="value_type_plus" class="form-check form-check-inline pt-0">
                                    <input <?= $default_plus_status ?> type="radio" value="plus" id="value_type_plus" name="value_type" class=" value_type_plus radioIp" checked="checked" required=""> Plus(+ <?= get_application_default_currency() ?>)
                                </label>
                                <label for="value_type_percent" class="form-check form-check-inline pt-0">
                                    <input <?= $default_percentage_status ?> type="radio" value="percentage" id="value_type_percent" name="value_type" class=" value_type_percent radioIp" required=""> Percentage(%)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pt-5">
                                <label for="generic_value" class="col-sm-4 form-label pt-3">Markup Value<span class="text-danger">*</span></label>
                                <input type="text" id="generic_value" name="generic_value" class="generic_value numeric" placeholder="Markup Value" required="" value="<?= @$generic_markup_list[0]['value'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-sm btn-gradient-success" id="general-markup-submit-btn" type="submit"><i class="bi bi-check-circle"></i> Save</button>
                            <button class="btn btn-sm btn-gradient-warning" id="general-markup-reset-btn" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                        </div>
                </form>
            </fieldset>
        </div><!-- PANEL BODY END /General Markup Ends-->
        <?php if (valid_array($specific_markup_list) == true) {//Check if airline list is present -Start IF ?>
            <div class="card-body p-4"><!-- PANEL BODY START -->
                <fieldset><legend class="form_legend"><i class="bi bi-airplane"></i> Flight - Specific Airline Markup</legend>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" class="row" method="POST" autocomplete="off">
                        <input type="hidden" name="form_values_origin" value="specific" />
                        <?php
                        foreach ($specific_markup_list as $__airline_index => $__airline_record) {
                            $default_percentage_status = $default_plus_status = '';
                            if (empty($__airline_record['value_type']) == true || $__airline_record['value_type'] == 'percentage') {
                                $default_percentage_status = 'checked="checked"';
                            } else {
                                $default_plus_status = 'checked="checked"';
                            }
                            ?>
                            <div class="hide">
                                <input type="hidden" name="airline_origin[]" value="<?= $__airline_record['airline_origin'] ?>" />
                                <input type="hidden" name="markup_origin[]" value="<?= $__airline_record['markup_origin'] ?>" />
                            </div>
                            <div class="row">
                                <div class="col-md-2">
        <?= ($__airline_index + 1); ?>
                                    <img src="<?= SYSTEM_IMAGE_DIR ?>airline_logo/<?= $__airline_record['airline_code'] ?>.svg" alt="<?= $__airline_record['airline_name'] ?>">
                                </div>
                                <div class="col-md-2">
        <?= $__airline_record['airline_name'] ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="radio">
                                        <label class="hide col-sm-4 form-label">Markup Type<span class="text-danger">*</span></label>
                                        <label for="value-type-plus-<?= $__airline_index ?>" class="form-check form-check-inline">
                                            <input <?= $default_plus_status ?> type="radio" value="plus" id="value-type-plus-<?= $__airline_index ?>" name="value_type_<?= $__airline_record['airline_origin'] ?>" class=" value-type-plus radioIp" checked="checked" required=""> Plus(+ <?= get_application_default_currency() ?>)
                                        </label>
                                        <label for="value-type-percent-<?= $__airline_index ?>" class="form-check form-check-inline">
                                            <input <?= $default_percentage_status ?> type="radio" value="percentage" id="value-type-percent-<?= $__airline_index ?>" name="value_type_<?= $__airline_record['airline_origin'] ?>" class=" value-type-percent radioIp" required=""> Percentage(%)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="specific-value-<?= $__airline_index ?>" class="col-sm-4 form-label">Value</label>
                                        <input type="text" id="specific-value-<?= $__airline_index ?>" name="specific_value[]" class=" specific-value numeric" placeholder="Markup Value" value="<?= $__airline_record['value'] ?>" />
                                    </div>
                                </div>
                            </div>
                            <hr>
    <?php } ?>
                        <div class="d-flex gap-2 mt-3">
                                <button class="btn btn-sm btn-gradient-success" type="submit"><i class="bi bi-check-circle"></i> Save</button>
                                <button class="btn btn-sm btn-gradient-warning" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                            </div>
                    </form>
                </fieldset>
            </div><!-- PANEL BODY END -->
<?php } //check if airline list is present - End IF ?>
</div><!-- PANEL WRAP END -->
</div>
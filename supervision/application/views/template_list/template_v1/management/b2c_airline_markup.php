<!-- HTML BEGIN -->
<div class="bodyContent col-md-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manage Application Markup</h4>
            <p class="text-muted small mb-0">Note: Application Default Currency - <strong><?= get_application_default_currency() ?></strong></p>
        </div>
    </div>

    <!-- Add Airline Section -->
    <div class="panel <?= PANEL_WRAPPER ?> card mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-airplane-fill text-dark"></i> Add Airline</h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST" autocomplete="off">
                <input type="hidden" name="form_values_origin" value="add_airline" />
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="airline_code" class="form-label fw-semibold mb-2">Airlines <span class="text-danger">*</span></label>
                        <select class="form-select" id="airline_code" name="airline_code" required>
                            <option value="">Please Select</option>
                            <?php echo generate_options($airline_list); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2">Markup Type <span class="text-danger">*</span></label>
                        <div class="btn-group w-100 gap-3" role="group" aria-label="Markup type">
                            <input type="radio" class="btn-check d-none" value="plus" id="airline_value_type_plus" name="value_type" checked required>
                            <label class="btn-outline-primary" for="airline_value_type_plus">Plus (+ <?= get_application_default_currency() ?>)</label>
                            <input type="radio" class="btn-check d-none" value="percentage" id="airline_value_type_percent" name="value_type" required>
                            <label class="btn-outline-primary" for="airline_value_type_percent">Percentage (%)</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="new_airline_value" class="form-label fw-semibold mb-2">Markup Value</label>
                        <input type="text" class="form-control numeric" id="new_airline_value" name="specific_value" placeholder="Enter markup value" />
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-gradient-success" id="add-airline-submit-btn" type="submit">
                        <i class="bi bi-plus-circle"></i> Add Airline
                    </button>
                    <button class="btn btn-outline-secondary" id="add-airline-reset-btn" type="reset">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- General Markup Section -->
    <div class="panel <?= PANEL_WRAPPER ?> card mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-airplane-fill text-dark"></i> Flight - General Markup</h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST" autocomplete="off">
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
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">Markup Type <span class="text-danger">*</span></label>
                        <div class="btn-group w-100 gap-3" role="group" aria-label="Markup type">
                            <input type="radio" class="btn-check d-none" value="plus" id="value_type_plus" name="value_type" <?= $default_plus_status ?> required>
                            <label class="btn-outline-primary" for="value_type_plus">Plus (+ <?= get_application_default_currency() ?>)</label>
                            <input type="radio" class="btn-check d-none" value="percentage" id="value_type_percent" name="value_type" <?= $default_percentage_status ?> required>
                            <label class="btn-outline-primary" for="value_type_percent">Percentage (%)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="generic_value" class="form-label fw-semibold mb-2">Markup Value <span class="text-danger">*</span></label>
                        <input type="text" class="form-control numeric" id="generic_value" name="generic_value" placeholder="Enter markup value" required value="<?= @$generic_markup_list[0]['value'] ?>" />
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-gradient-success" id="general-markup-submit-btn" type="submit">
                        <i class="bi bi-check-circle"></i> Save Changes
                    </button>
                    <button class="btn btn-outline-secondary" id="general-markup-reset-btn" type="reset">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Specific Airline Markup Section -->
    <?php if (valid_array($specific_markup_list) == true) { ?>
        <div class="panel <?= PANEL_WRAPPER ?> card ">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-airplane-fill text-dark"></i> Flight - Specific Airline Markup</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
                    <input type="hidden" name="form_values_origin" value="specific" />
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Airline</th>
                                    <th width="35%">Markup Type</th>
                                    <th width="25%">Markup Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($specific_markup_list as $__airline_index => $__airline_record) {
                                    $default_percentage_status = $default_plus_status = '';
                                    if (empty($__airline_record['value_type']) == true || $__airline_record['value_type'] == 'percentage') {
                                        $default_percentage_status = 'checked="checked"';
                                    } else {
                                        $default_plus_status = 'checked="checked"';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="hide">
                                                <input type="hidden" name="airline_origin[]" value="<?= $__airline_record['airline_origin'] ?>" />
                                                <input type="hidden" name="markup_origin[]" value="<?= $__airline_record['markup_origin'] ?>" />
                                            </div>
                                            <span class="badge bg-secondary"><?= ($__airline_index + 1) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= SYSTEM_IMAGE_DIR ?>airline_logo/<?= $__airline_record['airline_code'] ?>.svg" 
                                                     alt="<?= $__airline_record['airline_name'] ?>" 
                                                     style="width: 40px; height: 40px; object-fit: contain;" 
                                                     onerror="this.style.display='none'">
                                                <span class="fw-medium"><?= $__airline_record['airline_name'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm gap-3" role="group">
                                                <input type="radio" class="btn-check d-none" value="plus" id="value-type-plus-<?= $__airline_index ?>" 
                                                       name="value_type_<?= $__airline_record['airline_origin'] ?>" <?= $default_plus_status ?> required>
                                                <label class="btn-outline-primary" for="value-type-plus-<?= $__airline_index ?>">
                                                    Plus (+ <?= get_application_default_currency() ?>)
                                                </label>
                                                <input type="radio" class="btn-check d-none" value="percentage" id="value-type-percent-<?= $__airline_index ?>" 
                                                       name="value_type_<?= $__airline_record['airline_origin'] ?>" <?= $default_percentage_status ?> required>
                                                <label class="btn-outline-primary" for="value-type-percent-<?= $__airline_index ?>">
                                                    Percentage (%)
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm numeric" 
                                                   id="specific-value-<?= $__airline_index ?>" 
                                                   name="specific_value[]" 
                                                   placeholder="Markup Value" 
                                                   value="<?= $__airline_record['value'] ?>" />
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-gradient-success" type="submit">
                            <i class="bi bi-check-circle"></i> Save All Changes
                        </button>
                        <button class="btn btn-outline-secondary" type="reset">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>
</div>
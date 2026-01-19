<!-- HTML BEGIN -->
<div class="bodyContent col-md-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Manage B2B Application Markup</h4>
            <p class="text-muted small mb-0">Note: Application Default Currency - <strong><?= get_application_default_currency() ?></strong></p>
        </div>
    </div>

    <!-- General Markup Section -->
    <div class="panel <?= PANEL_WRAPPER ?> card mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-binoculars-fill text-dark"></i> Activities - General Markup</h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST" autocomplete="off">
                <div class="hide">
                    <input type="hidden" name="domain_origin" value="<?= get_domain_auth_id() ?>" />
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

    <!-- Specific Domain Markup Section -->
    <?php if (valid_array($specific_markup_list)) { ?>
        <div class="panel <?= PANEL_WRAPPER ?> card ">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-binoculars-fill text-dark"></i> Activities - Specific Domain Markup</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
                    <input type="hidden" name="form_values_origin" value="specific" />
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Domain Name</th>
                                    <th width="35%">Markup Type</th>
                                    <th width="25%">Markup Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($specific_markup_list as $__doamin_index => $__doamin_record) {
                                    $default_percentage_status = $default_plus_status = '';
                                    if (empty($__doamin_record['value_type']) == true || $__doamin_record['value_type'] == 'percentage') {
                                        $default_percentage_status = 'checked="checked"';
                                    } else {
                                        $default_plus_status = 'checked="checked"';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="hide">
                                                <input type="hidden" name="domain_origin[]" value="<?= $__doamin_record['domain_origin'] ?>" />
                                                <input type="hidden" name="markup_origin[]" value="<?= $__doamin_record['markup_origin'] ?>" />
                                            </div>
                                            <span class="badge bg-secondary"><?= ($__doamin_index + 1) ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-medium"><?= $__doamin_record['domain_name'] ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm gap-3" role="group">
                                                <input type="radio" class="btn-check d-none" value="plus" id="value-type-plus-<?= $__doamin_index ?>" 
                                                       name="value_type_<?= $__doamin_record['domain_origin'] ?>" <?= $default_plus_status ?> required>
                                                <label class="btn-outline-primary" for="value-type-plus-<?= $__doamin_index ?>">
                                                    Plus (+ <?= get_application_default_currency() ?>)
                                                </label>
                                                <input type="radio" class="btn-check d-none" value="percentage" id="value-type-percent-<?= $__doamin_index ?>" 
                                                       name="value_type_<?= $__doamin_record['domain_origin'] ?>" <?= $default_percentage_status ?> required>
                                                <label class="btn-outline-primary" for="value-type-percent-<?= $__doamin_index ?>">
                                                    Percentage (%)
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm numeric" 
                                                   id="specific-value-<?= $__doamin_index ?>" 
                                                   name="specific_value[]" 
                                                   placeholder="Markup Value" 
                                                   value="<?= $__doamin_record['value'] ?>" />
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
<?php
$airline_module = is_active_airline_module();
$accomodation_module = is_active_hotel_module();
$bus_module = is_active_bus_module();
$package_module = is_active_package_module();
$sightseen_module = is_active_sightseeing_module();
$car_module = is_active_car_module();
$transferv1_module = is_active_transferv1_module();
$tour_module = is_active_tour_module();
?>

<div class="panel <?= PANEL_WRAPPER ?>"><!-- PANEL WRAP START -->
    <div class="card-header"><!-- PANEL HEAD START -->
        <div class="card-title">
            <i class="fa fa-edit"></i>Edit Label
        </div>
    </div><!-- PANEL HEAD START -->
    <div class="card-body"><!-- PANEL BODY START -->
        <!-- <fieldset><legend><i class="fa fa-hotel"></i> City List</legend> -->
        <div class="container">
            <form action="<?= base_url() .'index.php/cms/update_label' ?>" enctype="multipart/form-data" class="row"
                method="POST" autocomplete="off">
                <input type="hidden" name="origin" value="<?= $label->origin ?>">
                <div class="form-group">
                    <label for="label_name" class="col-sm-3 form-label">Banner Title<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" required name="label_name"
                            value="<?= isset($label) ? $label->label_name : '' ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="module" class="col-sm-3 form-label">Module<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <?php $selectedModule = isset($label->module) ? $label->module : ''; ?>

                        <label class="form-check form-check-inline">
                            <input type="radio" name="module" value="B2C" required <?= ($selectedModule == 'B2C') ? 'checked' : ''; ?>> B2C
                        </label>
                        <label class="form-check form-check-inline">
                            <input type="radio" name="module" value="B2B" required <?= ($selectedModule == 'B2B') ? 'checked' : ''; ?>> B2B
                        </label>
                        <label class="form-check form-check-inline">
                            <input type="radio" name="module" value="Both" required <?= ($selectedModule == 'Both') ? 'checked' : ''; ?>> Both
                        </label>
                    </div>
                </div>



                <div class="form-group">
                    <label for="api_module" class="col-sm-3 form-label">API Module<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select name="api_module" class="form-control" required>
                            <option value="">Please Select</option>

                            <?php
                            $selectedModule = isset($label->api_module) ? $label->api_module : '';
                            ?>

                            <?php if ($airline_module) { ?>
                                <option value="flights" <?= ($selectedModule == 'flights') ? 'selected' : ''; ?>>Flights
                                </option>
                            <?php } ?>

                            <?php if ($accomodation_module) { ?>
                                <option value="hotels" <?= ($selectedModule == 'hotels') ? 'selected' : ''; ?>>Hotels</option>
                            <?php } ?>

                            <?php if ($bus_module) { ?>
                                <option value="bus" <?= ($selectedModule == 'bus') ? 'selected' : ''; ?>>Bus</option>
                            <?php } ?>

                            <?php if ($transferv1_module) { ?>
                                <option value="transfers" <?= ($selectedModule == 'transfers') ? 'selected' : ''; ?>>Transfers
                                </option>
                            <?php } ?>

                            <?php if ($sightseen_module) { ?>
                                <option value="activities" <?= ($selectedModule == 'activities') ? 'selected' : ''; ?>>
                                    Activities</option>
                            <?php } ?>

                            <?php if ($car_module) { ?>
                                <option value="cars" <?= ($selectedModule == 'cars') ? 'selected' : ''; ?>>Cars</option>
                            <?php } ?>

                            <option value="blogs" <?= ($selectedModule == 'blogs') ? 'selected' : ''; ?>>Blogs</option>

                            <?php //if ($tour_module) { ?>
                            <!-- <option value="holidays" <?= ($selectedModule == 'holidays') ? 'selected' : ''; ?>>Holidays</option> -->
                            <?php //} ?>
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <label for="display_page" class="col-sm-3 form-label">Display Page<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select name="display_page" class="form-control" required>
                            <?php
                            $selectedPage = isset($label->display_page) ? $label->display_page : '';
                            ?>

                            <option value="Search" <?= ($selectedPage == 'Search') ? 'selected' : ''; ?>>Search</option>
                            <!-- <option value="Results" <?= ($selectedPage == 'Results') ? 'selected' : ''; ?>>Results</option> -->
                            <option value="Booking" <?= ($selectedPage == 'Booking') ? 'selected' : ''; ?>>Booking</option>
                            <option value="Both" <?= ($selectedPage == 'Both') ? 'selected' : ''; ?>>Both</option>
                            <option value="blogs" <?= ($selectedPage == 'blogs') ? 'selected' : ''; ?>>Blogs</option>
                        </select>
                    </div>
                </div>

                <!-- <div class="form-group">
                        <label for="count" class="col-sm-3 form-label">Repeat Count<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" required name="count" value="1">
                        </div>
                    </div> -->

                <input type="hidden" class="form-control" required name="count" value="5">

                <div class="form-group">
                    <label for="display_side" class="col-sm-3 form-label">Display Position<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <select name="display_side" class="form-control" required id="display_side">
                            <?php
                            $selectedSide = isset($label->display_side) ? $label->display_side : '';
                            ?>

                            <option value="">Please Select</option>
                            <!-- <option value="Top" <?= ($selectedSide == 'Top') ? 'selected' : ''; ?>>Top</option> -->
                            <option value="Right" <?= ($selectedSide == 'Right') ? 'selected' : ''; ?>>Right</option>
                            <!-- <option value="Bottom" <?= ($selectedSide == 'Bottom') ? 'selected' : ''; ?>>Bottom</option> -->
                            <option value="Left" <?= ($selectedSide == 'Left') ? 'selected' : ''; ?>>Left</option>
                            <option value="Middle" <?= ($selectedSide == 'Middle') ? 'selected' : ''; ?>>Middle</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="date" class="col-sm-3 form-label">Date</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" name="date" id="date"
                            value="<?= isset($label->date) ? date('Y-m-d', strtotime($label->date)) : ''; ?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="top_destination" class="col-sm-3 form-label">Image<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <?php if (!empty($label->image)) { ?>
                            <img src="<?php echo $GLOBALS['CI']->template->domain_images($label->image) ?>" id="image_preview" 
                                class="img-thumbnail" style="max-width: 200px; display: block; margin-bottom: 10px; min-height: 200px;">
                        <?php } else { ?>
                            <img id="image_preview" class="img-thumbnail" style="max-width: 200px; display: none; margin-bottom: 10px;">
                        <?php } ?>

                        <input type="file" class="form-control" accept="image/*" name="top_destination" id="top_destination">
                        <?php
                            if (isset($label) && is_object($label) && !empty($label->display_side)) {
                                if (in_array($label->display_side, ['Top', 'Bottom', 'Middle'])) {
                                    $imageWidth = 1186;
                                    $imageHeight = 131;
                                    echo '<small id="image_note" class="text-info">Image size should be 1186px width and 131px height</small>';
                                } elseif (in_array($label->display_side, ['Right', 'Left'])) {
                                    $imageWidth = 131;
                                    $imageHeight = 1186;
                                    echo '<small id="image_note" class="text-info">Image size should be 1186px height and 131px width</small>';
                                } else {
                                    echo '<small class="text-warning">Invalid display side specified.</small>';
                                }
                            } else {
                                echo '<small class="text-danger">Error: Label data is missing or incorrect.</small>';
                            }
                        ?>
                        <!-- <small id="image_note" class="text-info"></small> -->
                        <input type="hidden" name="img_width" value="<?= $imageWidth ?>" id="img_width">
                        <input type="hidden" name="img_height" value="<?= $imageHeight ?>" id="img_height">
                    </div>
                </div>

                <div class="form-group">
                    <label for="alt_text" class="col-sm-3 form-label">Banner Message<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" name="message" id="message"  value="<?= isset($label->message) ? htmlspecialchars($label->message, ENT_QUOTES, 'UTF-8') : ''; ?>">

                    </div>
                </div>

                <div class="form-group">
                    <label for="alt_text" class="col-sm-3 form-label">Alt<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" required name="alt_text"  value="<?= isset($label->alt_text) ? htmlspecialchars($label->alt_text, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="display" class="col-sm-3 form-label">Status<span class="text-danger">*</span></label>
                    <div class="col-sm-6">
                        <?php 
                        $selectedStatus = isset($label->display) ? $label->display : ''; 
                        ?>

                        <label class="form-check form-check-inline">
                            <input type="radio" name="display" value="Active" required 
                                <?= ($selectedStatus == 'Active') ? 'checked' : ''; ?>> Active
                        </label>
                        <label class="form-check form-check-inline">
                            <input type="radio" name="display" value="Inactive" required 
                                <?= ($selectedStatus == 'Inactive') ? 'checked' : ''; ?>> Inactive
                        </label>
                    </div>
                </div>


                <div class="form-group">
                    <div class="offset-sm-3 col-sm-2">
                        <button class="btn btn-success btn-block" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>

        </fieldset>
    </div><!-- PANEL BODY END -->
    <script type="text/javascript">
    $(document).ready(function() {
        $('#display_side').on('change', function() {
            $val = $(this).val();
            if ($val == 'Top' || $val == 'Bottom' || $val == 'Middle') {
                $('#img_width').val('1186');
                $('#img_height').val('131');
                $('#image_note').text('Image size should be 1186px width and 131px height').css('color', 'red');
            }else if ($val == 'Right' || $val == 'Left') {
                $('#img_width').val('131');
                $('#img_height').val('1186');
                $('#image_note').text('Image size should be 1186px height and 131px width').css('color', 'red');
            }
        });
    })
</script>
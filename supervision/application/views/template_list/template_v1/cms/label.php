<!-- HTML BEGIN -->
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
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>

<div class="bodyContent">
    <div class="card <?= PANEL_WRAPPER ?>"><!-- CARD WRAP START -->
        <div class="card-header"><!-- CARD HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Label
            </div>
        </div><!-- CARD HEAD START -->
        <div class="card-body"><!-- CARD BODY START -->
            <!-- <fieldset><legend><i class="fa fa-hotel"></i> City List</legend> -->
            <div class="container">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" class="row" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label for="label_name" class="col-sm-3 form-label">Banner Title<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" required name="label_name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="module" class="col-sm-3 form-label">Module<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <label class="form-check form-check-inline">
                                <input type="radio" name="module" value="B2C" required> B2C
                            </label>
                            <label class="form-check form-check-inline">
                                <input type="radio" name="module" value="B2B" required> B2B
                            </label>
                            <label class="form-check form-check-inline">
                                <input type="radio" name="module" value="Both" required> Both
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="api_module" class="col-sm-3 form-label">API Module<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select name="api_module" class="form-control" required>
                                <option value="">Please Select</option>
                                <?php if ($airline_module) { ?>
                                    <option value="flights">Flights</option>
                                <?php } ?>
                                <?php if ($accomodation_module) { ?>
                                    <option value="hotels">Hotels</option>
                                <?php } ?>
                                <?php if ($bus_module) { ?>
                                    <option value="bus">Bus</option>
                                <?php } ?>
                                <?php if ($transferv1_module) { ?>
                                    <option value="transfers">Transfers</option>
                                <?php } ?>
                                <?php if ($sightseen_module) { ?>
                                    <option value="activities">Activities</option>
                                <?php } ?>
                                <?php if ($car_module) { ?>
                                    <option value="cars">Cars</option>
                                <?php } ?>

                                <option value="blogs">Blogs</option>

                                <?php //if ($tour_module) { 
                                ?>
                                <!-- <option value="holidays">Holidays</option> -->
                                <?php //} 
                                ?>


                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="display_page" class="col-sm-3 form-label">Display Page<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select name="display_page" class="form-control" required>
                                <option value="Search">Search</option>
                                <!-- <option value="Results">Results</option> -->
                                <option value="Booking">Booking</option>
                                <option value="Both">Both</option>
                                <option value="blogs">Blogs</option>
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
                        <label for="display_side" class="col-sm-3 form-label">Display Position<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <select name="display_side" class="form-control" required id="display_side">
                                <option value="">Please Select</option>
                                <!-- <option value="Top">Top</option> -->
                                <option value="Right">Right</option>
                                <!-- <option value="Bottom">Bottom</option> -->
                                <option value="Left">Left</option>
                                <option value="Middle">Middle</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="display_side" class="col-sm-3 form-label">Date</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="date" id="date" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="top_destination" class="col-sm-3 form-label">Image<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" accept="image/*" required name="top_destination">
                            <small id="image_note" class="text-info"></small>
                            <input type="hidden" name="img_width" id="img_width">
                            <input type="hidden" name="img_height" id="img_height">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alt_text" class="col-sm-3 form-label">Banner Message<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="message" id="message">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alt_text" class="col-sm-3 form-label">Alt<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" required name="alt_text">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="display" class="col-sm-3 form-label">Status<span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <label class="form-check form-check-inline">
                                <input type="radio" name="display" value="Active" required> Active
                            </label>
                            <label class="form-check form-check-inline">
                                <input type="radio" name="display" value="Inactive" required> Inactive
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="offset-sm-3 col-sm-2">
                            <button class="btn btn-success btn-block" type="submit">Add</button>
                        </div>
                    </div>
                </form>
            </div>

            </fieldset>
        </div><!-- CARD BODY END -->
        <div class="card-body">
          
            <div class="card-body">
                <table id="blogTable" class="table table-bordered">
                    <tr>
                        <th>Sno</th>
                        <th>Label Name</th>
                        <th>Module</th>
                        <th>API Module</th>
                        <th>Display Page</th>
                        <th>Display Side</th>
                        <th>Display</th>
                        <!-- <th>Count</th> -->
                        <th>Date</th>
                        <th>Message</th>
                        <th>Image</th>
                        <th>Alt</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    //debug($data_list);exit;
                    if (valid_array($data_list) == true) {
                        foreach ($data_list as $k => $v) :
                    ?>
                            <tr>
                                <td><?= ($k + 1) ?></td>
                                <td><?= $v['label_name'] ?></td>
                                <td><?= $v['module'] ?></td>
                                <td><?= $v['api_module'] ?></td>
                                <td><?= $v['display_page'] ?></td>
                                <td><?= $v['display_side'] ?></td>
                                <td><?= $v['display'] ?></td>
                                <!-- <td><?= $v['count'] ?></td> -->
                                <td><?= $v['date'] ?></td>
                                <td><?= $v['message'] ?></td>
                                <td><img src="<?php echo $GLOBALS['CI']->template->domain_images($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
                                <td><?= $v['alt_text'] ?></td>
                                <td><?php echo get_status_label($v['status']); ?></td>
                                <td>
                                    <?php  echo get_status_toggle_button($v['status'], $v['origin']); ?>
                                    <a href="<?= base_url() . 'index.php/cms/edit_label/' . $v['origin'] ?>"> Edit</a>
                                    <a role="button" href="<?= base_url() . 'index.php/cms/delete_label/' . $v['origin'] ?>" class="text-danger">Delete</a>
                            </td>
                            </tr>
                    <?php
                        endforeach;
                    } else {
                        echo '<tr><td>No Data Found</td></tr>';
                    }
                    ?>
                </table>
                <div class="pagination-container">
                    <?php echo $pagination; ?>
                </div>
            </div>
            <div class="">
                <?php echo $this->pagination->create_links(); ?> <span class="" style="vertical-align:top;">Total <?php echo $total_rows ?> Records</span>
            </div>
        </div>
    </div><!-- CARD WRAP END -->
</div>

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

<?php
function get_status_label($status)
{
    if (intval($status) == ACTIVE) {
        return '<span class="badge bg-success"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', ACTIVE) . '</span>
    <a role="button" href="" class="hide">' . get_app_message('AL0021') . '</a>';
    } else {
        return '<span class="badge bg-success"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', INACTIVE) . '</span>
        <a role="button" href="" class="hide">' . get_app_message('AL0021') . '</a>';
    }
}

function get_status_toggle_button($status, $origin)
{
    if (intval($status) == ACTIVE) {
        return '<a role="button" href="' . base_url() . 'index.php/cms/deactivate_label/' . $origin . '" class="text-danger">Deactivate</a>';
    } else {
        return '<a role="button" href="' . base_url() . 'index.php/cms/activate_label/' . $origin . '" class="text-danger">Activate</a>';
    }
}

?>
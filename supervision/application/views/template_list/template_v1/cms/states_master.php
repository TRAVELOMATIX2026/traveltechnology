<?php if ($this->session->flashdata('success_message')): ?>
    <div class="alert alert-success" id="success-alert">
        <?php echo $this->session->flashdata('success_message'); ?>
    </div>
    <script type="text/javascript">
        // Use JavaScript to hide the success message after 5 seconds
        setTimeout(function() {
            document.getElementById('success-alert').style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
<?php endif; ?>
<div class="bodyContent">
    <div class="panel <?=PANEL_WRAPPER?>">
        <!-- PANEL WRAP START -->
        <div class="card-header">
            <!-- PANEL HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Province Master
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <form method="POST" action="<?php echo base_url()?>index.php/cms/add_states_master">
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Country</label>
                    <div class="col-sm-6">
                        <select name="country" class="form-control" required>
                            <option value="">Please Select</option>
                            <?php
                            foreach($country_master_data['data'] as $country_key => $country_value) {
                                $selected = ($country_value['origin'] == $single_state_master_data['data'][0]['country_id']) ? 'selected' : '';
                                
                                echo '<option value="'.$country_value['origin'].'-'.$country_value['iso_country_code'].'" '.$selected.'>'.$country_value['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Province Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="state_name" class="form-control" placeholder="Province Name (Hyderabad)" required value="<?php echo $single_state_master_data['data'][0]['state_province'];?>">
                        <input type="hidden" name="state_hidden_origin" value="<?php echo $single_state_master_data['data'][0]['origin'];?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-6">
                        <input type="submit" name="submit" value="Submit" class="btn btn-success">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="bodyContent">
    <div class="panel <?=PANEL_WRAPPER?>">
        <!-- PANEL WRAP START -->
        <div class="card-header">
            <!-- PANEL HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Province List
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <div class="">
                <?php echo $this->pagination->create_links();?> <span class="" style="vertical-align:top;">Total <?php echo $total_rows ?> Records</span>
            </div>
            <div class="card-body">
                <table id="blogTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Country</th>
                            <th>Province</th>
                            <th>Country Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;foreach ($states_master_data['data'] as $states): ?>
                            <tr>
                                <td>
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <?php 
                                    $country_name=$this->custom_db->single_table_records('api_country_list','name',array('origin'=>$states['country_id']));
                                    echo $country_name['data'][0]['name'];
                                    ?>
                                </td>
                                <td>
                                    <?php echo $states['state_province']; ?>
                                </td>
                                <td>
                                    <?php echo $states['country_code']; ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('cms/states_master/state_id/' . $states['origin']); ?>" class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>

</html>
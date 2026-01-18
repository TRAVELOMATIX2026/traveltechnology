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
                <i class="fa fa-edit"></i> Country Master
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <form method="POST" action="<?php echo base_url()?>index.php/cms/add_country_master">
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Continent</label>
                    <div class="col-sm-6">
                        <select name="continent" class="form-control" required>
                            <option value="">Please Select</option>
                            <?php
                            foreach($continent_master_data['data'] as $continent_key => $continent_value) {
                                $selected = ($continent_value['origin'] == $single_country_master_data['data'][0]['api_continent_list_fk']) ? 'selected' : '';
                                
                                echo '<option value="'.$continent_value['origin'].'" '.$selected.'>'.$continent_value['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Country Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="country_name" class="form-control" placeholder="Country Name (India)" required value="<?php echo $single_country_master_data['data'][0]['name'];?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Country Code</label>
                    <div class="col-sm-6">
                        <input type="text" name="country_code" class="form-control" placeholder="Country Code (+91)" required value="<?php echo $single_country_master_data['data'][0]['country_code'];?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">ISO Country Code</label>
                    <div class="col-sm-6">
                        <input type="text" name="iso_country_code" class="form-control" placeholder="ISO Country Code (IN)" required value="<?php echo $single_country_master_data['data'][0]['iso_country_code'];?>">
                        <input type="hidden" name="country_hidden_origin" value="<?php echo $single_country_master_data['data'][0]['origin'];?>">
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
                <i class="fa fa-edit"></i> Country List
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
                            <th>Continent</th>
                            <th>Country</th>
                            <th>Country Code</th>
                            <th>ISO Country Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;foreach ($country_master_data['data'] as $country): ?>
                            <tr>
                                <td>
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <?php 
                                    $continent_name=$this->custom_db->single_table_records('api_continent_list','name',array('origin'=>$country['api_continent_list_fk']));
                                    echo $continent_name['data'][0]['name'];
                                    ?>
                                </td>
                                <td>
                                    <?php echo $country['name']; ?>
                                </td>
                                <td>
                                    <?php echo $country['country_code']; ?>
                                </td>
                                <td>
                                    <?php echo $country['iso_country_code']; ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('cms/country_master/country_id/' . $country['origin']); ?>" class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="pagination-container">
    <?php echo $pagination; ?>
</div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
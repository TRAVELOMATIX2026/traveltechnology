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
                <i class="fa fa-edit"></i> City Master
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <form method="POST" action="<?php echo base_url()?>index.php/cms/add_city_master">
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Country</label>
                    <div class="col-sm-6">
                        <select name="country" class="form-control" required id="country">
                            <option value="">Please Select</option>
                            <?php
                            foreach($country_master_data['data'] as $country_key => $country_value) {
                                $selected = ($country_value['origin'] == $single_city_master_data['data'][0]['country']) ? 'selected' : '';
                                
                                echo '<option value="'.$country_value['origin'].'-'.$country_value['iso_country_code'].'" '.$selected.'>'.$country_value['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Province</label>
                    <div class="col-sm-6">
                        <select name="province" class="form-control" required id="province">
                            <option value="">Please Select</option>
                            <?php
                            if (isset($single_city_master_data['data'][0]['state_id']) && !empty($single_city_master_data['data'][0]['state_id'])) {
                                foreach($states_master_data['data'] as $state_key => $state_value) {
                                    $selected = ($state_value['origin'] == $single_city_master_data['data'][0]['state_id']) ? 'selected' : '';
                                    
                                    echo '<option value="'.$state_value['origin'].'-'.$state_value['state_province'].'" '.$selected.'>'.$state_value['state_province'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">City Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="city_name" class="form-control" placeholder="City Name (Prakasam)" required value="<?php echo $single_city_master_data['data'][0]['destination'];?>">
                        <input type="hidden" name="city_hidden_origin" value="<?php echo $single_city_master_data['data'][0]['origin'];?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Longitude</label>
                    <div class="col-sm-6">
                        <input type="text" name="longitude" class="form-control" placeholder="Longitude" required value="<?php echo $single_city_master_data['data'][0]['longitude'];?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Latitude</label>
                    <div class="col-sm-6">
                        <input type="text" name="latitude" class="form-control" placeholder="Latitude" required value="<?php echo $single_city_master_data['data'][0]['latitude'];?>">
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
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $('#country').on('change', function() {             
        country = $('#country').val();
        country_id=country.split('-');
        // alert(country_id[0]);
        main_country_id=country_id[0];
        if(main_country_id!='NA'){
            $.post('<?=base_url();?>index.php/cms/ajax_states_by_country_id',{'country_id':main_country_id},function(data){
                $('#province').html(data);
            });
        }else{
            $('#province').html('');
        }
        });

        $('#search_country').on('change', function() {             
        country = $('#search_country').val();
        country_id=country.split('-');
        // alert(country_id[0]);
        main_country_id=country_id[0];
        if(main_country_id!='NA'){
            $.post('<?=base_url();?>index.php/cms/ajax_states_by_country_id',{'country_id':main_country_id},function(data){
                $('#search_province').html(data);
            });
        }else{
            $('#search_province').html('');
        }
        });
    })
</script>
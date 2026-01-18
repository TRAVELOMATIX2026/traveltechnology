<div id="package_types" class="bodyContent col-md-12">
   <div class="panel card">
      <!-- PANEL WRAP START -->
      <div class="card-header">
         <!-- PANEL HEAD START -->
         <div class="card-title">
            <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
               <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
               <li role="presentation" class="active">
                  <a href="#fromList"
                     aria-controls="home" role="tab" data-bs-toggle="tab">
                     <h1>Edit Package </h1>
                  </a>
               </li>
               <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
            </ul>
         </div>
      </div>
      <!-- PANEL HEAD START -->
      <div class="card-body">
         <!-- PANEL BODY START -->
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="fromList">
               <div class="col-md-12">
                  <div class='row'>
                     <div class='row'>
                        <div class='col-sm-12'>
                           <div class='' style='margin-bottom:0;'>
                              <div class='box-content'>
                                 <form class='form row validate-form' style='margin-bottom: 0;' action="<?php echo base_url(); ?>index.php/supplier/update_package/<?php echo $packdata->package_id;?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="w_wo_d" value="w">
                                    <div class="form-group hide">
                                          <label class="form-label col-sm-3" for="validation_name">Domain</label>
                                          <div class="col-sm-6 controls">
                                             <select class="form-control" data-rule-required="true"
                                                name="domain" id="domain" required="" tabindex="-1" aria-hidden="true">

                                                <option value="1" <?php if($packdata->domain==1){ echo 'selected';}?>>COM</option>
                                                <option value="2" <?php if($packdata->domain==2){ echo 'selected';}?>>IN</option>
                                             </select>
                                             <span id="domain" style="color: #F00; display: none;">validate</span>
                                          </div>
                                       </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_current'>Package Name
                                       </label>
                                       <div class='col-sm-6 controls'>
                                          <div class="controls">
                                             <input type="text" name="name" id="name" value ="<?php echo $packdata->package_name;?>" data-rule-required='true' class='form-control'>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">No of Pax </label>
                                       <div class="col-sm-6 controls"><input type="number" name="no_of_pax" id="no_of_pax" placeholder="Enter Number of Pax"  class="form-control add_pckg_elements" value="<?php echo $packdata->pax;?>"></div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_number'>Price
                                       </label>
                                       <div class='col-sm-6 controls'>
                                          <input type="text"  name="p_price" id="p_price" data-rule-number='true' data-rule-required='true' class='form-control' value ="<?php echo $packdata->price;?>">
                                       </div>
                                    </div>
                                    <!-- <div class="form-group">
                                       <label class='form-label col-sm-3' for='validation_current'>Duration</label>
                                       <?php if($packdata->duration_status==1){?>
                                          Enabled
                                          <?php }else{?>
                                             Disabled
                                          <?php }?>
                                       </div> -->
                                       
                                       <div class="form-group">
                                       <label class='form-label col-sm-3' for='validation_current'>Duration</label>
                                          <input type="radio" name="duration_status" value="1" <?php if($packdata->duration_status==1){?> checked  <?php }?> onclick="enable_duration(1)"/> With Duration &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                          <input type="radio" name="duration_status" value="0" onclick="enable_duration(0)" <?php if($packdata->duration_status==0){?> checked  <?php }?>> Without Duration
                                       </div>
                                    <div class='form-group' id="duration_status" <?php if($packdata->duration_status==0){?> style="display:none;" <?php } ?>>
                                       <label class='form-label col-sm-3' for='validation_current'>Choose Duration</label>
                                       <div class='col-sm-6 col-md-6 controls'>
                                          <!-- <select class='select2 form-control' name='duration' id="duration" data-rule-required='true' required disabled>          
                                          <?php
                                             // for($dno = 0; $dno <= 30; $dno++) {
                                             //     // Format the duration
                                             //     if($dno==0) { 
                                             //       $DayNight = ($dno+1).' Day | '.($dno).' Nights';
                                             //    }elseif($dno==1) { 
                                             //         $DayNight = ($dno + 1).' Days | '.($dno).' Night';
                                             //     } else {
                                             //         $DayNight = ($dno + 1).' Days | '.($dno).' Nights';
                                             //     }
                                             //     $selected = ($dno == $packdata->duration) ? 'selected' : '';
                                             //     echo '<option value="'.$dno.'" '.$selected.'>'.$DayNight.'</option>';
                                             // }
                                             ?>
                                          </select> -->
                                          <select class='select2 form-control' name='duration' id="duration" data-rule-required='true' required>
                                             <!-- <?php $dy = $packdata->duration;
                                                   $ndy = $dy - 1;
                                              ?>          
                                         <option value="<?=$dy?>"><?=$dy.(($dy > 1)?' Days':' Day')?> | <?=$ndy.(($ndy > 1)?' Nights': ' Night') ?></option> -->
                                         
                                         
                                         <option value="0" <?php if($packdata->duration==0){?> selected  <?php }?>>Please Select</option>       
                                         <?php
                                             for ($dno = 0; $dno <= 30; $dno++) {
                                                if ($dno == 0) { 
                                                   $DayNight = ($dno + 1) . ' Day | ' . ($dno) . ' Nights';
                                                } elseif ($dno == 1) { 
                                                   $DayNight = ($dno + 1) . ' Days | ' . ($dno) . ' Night';
                                                } else {
                                                   $DayNight = ($dno + 1) . ' Days | ' . ($dno) . ' Nights';
                                                }

                                                // Check if the option should be selected
                                                $selected = ($dno + 1 == $packdata->duration) ? 'selected' : '';

                                                echo '<option value="' . ($dno + 1) . '" ' . $selected . '>' . $DayNight . '</option>';
                                             }
                                             ?>
                                       


                                       </select>       
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_current'>Choose Region</label>
                                       <div class='col-sm-6 col-md-6 controls'>
                                          <select class='select2 form-control' data-rule-required='true' name='tours_continent' id="tours_continent" data-rule-required='true' required>
                                             <option value="NA">Select Region</option>
                                             <?php
                                                foreach($tours_continent as $tours_continent_key => $tours_continent_value) {
                                                    // Check if the option should be selected based on the saved value
                                                    $selected = ($tours_continent_value['origin'] == $packdata->continent) ? 'selected' : '';
                                                    
                                                    // Output the option with the selected attribute if it matches the saved value
                                                    echo '<option value="'.$tours_continent_value['origin'].'" '.$selected.'>'.$tours_continent_value['name'].'</option>';
                                                }
                                                ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_current'>Choose Country
                                       </label>
                                       <div class='col-sm-6 col-md-6 controls'>
                                          <select class='select2 form-control' data-rule-required='true' name='tours_country[]' id="tours_country" multiple data-rule-required='true' style="width:775px;" required>
                                          <?php
                                             if (isset($packdata->package_country) && !empty($packdata->package_country)) {
                                                 $selected_countries = explode(',', $packdata->package_country);
                                             
                                                 foreach ($continent_countries as $country_name) {
                                                     $selected = in_array($country_name->country_id, $selected_countries) ? 'selected' : '';
                                                     echo "<option value='{$country_name->country_id}' {$selected}>{$country_name->name}</option>";
                                                 }
                                             } else {
                                                 foreach ($continent_countries as $country_name) {
                                                     echo "<option value='{$country_name->country_id}'>{$country_name->name}</option>";
                                                 }
                                             }
                                             ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_current'>Choose City
                                       </label>
                                       <div class='col-sm-3 col-md-3 controls'>
                                          <select class='select2 form-control' data-enable-select2="false" data-rule-required='true' name='tours_city[]' id="tours_city" multiple data-rule-required='true'>
                                          </select>
                                       </div>
                                       <div class='col-sm-1 col-md-1 controls' style="text-align: center;">
                                          >><br>
                                          <<  
                                       </div>
                                       <div class='col-sm-3 col-md-3 controls'>
                                          <select id="second" class="form-control" data-enable-select2="false" name="tours_city_new[]" multiple>
                                          </select>
                                       </div>
                                    </div>
                                    <!-- <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_country'>Country</label>
                                       <div class='col-sm-4 controls'>
                                         <select class='select2 form-control' data-rule-required='true' name='country' id="validation_country" value ="<?php echo $packdata->package_country;?>">
                                         <input type="text" name="country" id="country" data-rule-required='true' class='form-control'> -->
                                    <?php 
                                       // foreach ($countries as $country) {?>
                                    <option  value='<?php echo $country->country_id;?>'<?php if($country->country_id == $packdata->package_country) { echo "selected=selected"; } ?>><?php echo $country->name;?></option>
                                    <?php 
                                       // }
                                       ?>
                                    <!-- </select>
                                       </div>
                                       </div> -->
                                    <!-- <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_current'>City
                                       </label>
                                         <div class='col-sm-4 controls'>
                                       
                                        <div class="controls">
                                       <input type="text" name="city" id="country" value ="<?php echo $packdata->package_city;?>" data-rule-required='true' class='form-control' disabled>
                                       </div>
                                       
                                       </div>
                                       </div> -->
                                    <!-- <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_current'>Location
                                       </label>
                                         <div class='col-sm-4 controls'>
                                       
                                       <input type="text" name="location" id="location" data-rule-required='true' class='form-control' value ="<?php echo $packdata->package_location;?>">
                                       
                                       </div>
                                       </div> -->
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_company'>Package Main Image</label>
                                       <div class='col-sm-3 controls'>
                                          <!--  <input type="hidden" value="<?php echo $packdata->image; ?>" name="photo"> -->
                                          <input type="file" title='Image to add to add' class=''   id='photo' name='photo'>                                 
                                          <input type="hidden" name='hidephoto' value="<?php echo $packdata->image; ?>">
                                          <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($packdata->image); ?>" width="100" name="photo">
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_name'>Description</label>
                                       <div class='col-sm-8 controls'>
                                          <textarea name="Description" data-rule-required='true' class="form-control summernote"  placeholder="Description" required><?php echo $packdata->package_description;?></textarea>
                                          <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                      <label class='form-label col-sm-3' for='validation_current'>Vendor
                                        Name </label>
                                      <div class='col-sm-6 controls'>
                                        <div class="controls">
                                          <input type="text" name="vendor_name" id="vendor_name"
                                            data-rule-minlength='2' data-rule-required='true' placeholder="Enter vendor Name"
                                            class='form-control add_pckg_elements' required value="<?php echo $packdata->vendor_name;?>">
                                        </div>
                                      </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_rating' >Rating  </label>
                                       <div class="col-sm-4 controls" >
                                          <select class='form-control select2-drop-down' data-rule-required='true' name='rating' id="rating" value ="" >
                                             <option><?php echo $packdata->rating;?></option>
                                             <option value="0">0</option>
                                             <option value="1">1</option>
                                             <option value="2">2</option>
                                             <option value="3">3</option>
                                             <option value="4">4</option>
                                             <option value="5">5</option>
                                          </select>
                                       </div>
                                    </div>
                                       <hr>

                                       <div class='form-group'>
                                       <div class='form-group' id="addMultiCity"> </div>
                                       <div id="addCityButton" class="col-lg-2" style="display:none">
                                          <input type="button" class="srchbutn comncolor" id="addCityInput" value="Add Month" style="padding:3px 10px;">
                                          <input type="hidden" value="1" id="multiCityNo" name="no_of_days">
                                       </div>
                                       <div id="removeCityButton" class="col-lg-2" style="display:none;">
                                          <input type="button" class="srchbutn comncolor" id="removeCityInput" value="Remove One Month" style="padding:3px 10px;">
                                       </div>
                                    </div>
                                    
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_includes'>Price Includes
                                       </label>
                                       <div class='col-sm-8 controls'>
                                          <!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
                                          <textarea name="includes" data-rule-required='true' class="form-control summernote" placeholder="Price Includes" required><?php echo $packdata->price_includes;?></textarea>
                                          <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_excludes'>Price Excludes
                                       </label>
                                       <div class='col-sm-8 controls'>
                                          <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                                          <textarea name="excludes" data-rule-required='true' class="form-control summernote" placeholder="Price Excludes" required><?php echo $packdata->price_excludes;?></textarea>
                                       </div>
                                    </div>
                                    <div class='box-header blue-background'>
                                       <!-- <div class=''><h4>Cancellation & Refund Policy</h4></div> -->
                                    </div>
                                    <div>
                                       <h1></h1>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_advance'>Cancellation In Advance
                                       </label>
                                       <div class='col-sm-8 controls'>
                                          <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                                          <textarea name="advance" class="form-control summernote" data-rule-required="true"  cols="70" rows="3" placeholder="Cancellation In Advance"><?php echo $packdata->cancellation_advance;?></textarea>
                                       </div>
                                    </div>
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_excludes'>Cancellation Penalty
                                       </label>
                                       <div class='col-sm-8 controls'>
                                          <!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
                                          <textarea name="penality" class="form-control summernote" data-rule-required="true"  cols="70" rows="3" placeholder="Cancellation Penalty"><?php echo $packdata->cancellation_penality;?></textarea>
                                       </div>
                                    </div>
                              </div>
                           </div>
                        </div>
                        <div class='form-actions' style='margin-bottom:0'>
                        <div class='row'>
                        <div class='col-sm-9 offset-sm-3'>
                        <a href="<?php echo base_url(); ?>supplier/view_with_price">
                        <button class='btn btn-primary' type='button'>
                        <i class='icon-reply'></i>
                        Go Back
                        </button></a>
                        <button class='btn btn-primary' type='submit'>
                        <i class='icon-save'></i>
                        Update
                        </button>
                        </div>
                        </div>
                        </div>
                     </div>
                  </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- PANEL BODY END -->
</div>
<!-- PANEL WRAP END -->
</div>
<link rel="stylesheet" href="<?php echo SYSTEM_RESOURCE_LIBRARY?>/summernote/dist/summernote.css" />
<script type="text/javascript" src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/summernote/dist/summernote.min.js"></script>
<script type="text/javascript">

function enable_duration(status){

if(status==1){
   $('#duration_status').show();
}else{
   $('#duration').val(0);
   $('#duration_status').hide();
}

}
   $(document).ready(function () {

      $('.summernote').summernote({
			height: 250, // set editor height
			minHeight: null, // set minimum height of editor
			maxHeight: null, // set maximum height of editor
			focus: true // set focus to editable area after initializing summernote
		});

     let tours_continent = $('#tours_continent').val();
     let selectedCountries = '<?= isset($packdata->package_country) ? $packdata->package_country : '' ?>';
   
     if (tours_continent !== 'NA') {
         $.post(
             '<?= base_url(); ?>index.php/supplier/ajax_tours_continent',
             { tours_continent: tours_continent },
             function (data) {
                 $('#tours_country').html(data);
   
                 if (selectedCountries) {
                     const countryArray = selectedCountries.split(',');
                     $('#tours_country option').each(function () {
                         if (countryArray.includes($(this).val())) {
                             $(this).prop('selected', true);
                         }
                     });
                 }
   
                 $('#tours_country').bootstrapDualListbox('refresh', true);
             }
         );
     }
   
     $('#tours_continent').on('change', function () {
         tours_continent = $(this).val();
         if (tours_continent !== 'NA') {
             $.post(
                 '<?= base_url(); ?>index.php/supplier/ajax_tours_continent',
                 { tours_continent: tours_continent },
                 function (data) {
                     $('#tours_country').html(data);
                     $('#tours_city').html('');
   
                     $('#tours_country').bootstrapDualListbox('refresh', true);
                 }
             );
         } else {
             $('#tours_country').html('');
             $('#tours_city').html('');
         }
     });
   });
   
</script>
<script type="text/javascript">
   $(document).ready(function() {
     let selectedCities = '<?= isset($packdata->package_city) ? $packdata->package_city : '' ?>';
     let selectedCountries = $('#tours_country').val();
   
     if (selectedCities) {
         selectedCities = selectedCities.split(',');
     }
   
     $('#tours_country').on('change', function() { 
         var tours_countries = $('#tours_country').val();
   
         if (tours_countries == null || tours_countries.length == 0) {
             $('#tours_city').html('');
         }
         
         if (tours_countries.length > 0) {
             var tours_country_list = tours_countries;
         } else {
             var tours_country_list = tours_countries.split(',');
         }
   
         $.each(tours_country_list, function(index, item) {
             $.post('<?= base_url(); ?>index.php/supplier/ajax_tours_country', {'tours_country': item}, function(data) {
                 if (index > 0) {
                     $('#tours_city').append(data);
                 } else {
                     $('#tours_city').html(data);
                 }
                 $('#tours_city').bootstrapDualListbox('refresh', true);
             });
         });
     });
   
     if (selectedCities.length > 0) {
         $.each(selectedCities, function(index, city) {
             $('#second').append(`<option value="${city}" selected>${city}</option>`);
         });
     }
   
     $('#tours_city').on('change', function() {
         var options = $("#tours_city").find(':selected').clone();
         $('#second').append(options);
         getSelectMultiple();
     });
   
     $('#second').on('change', function() {
         $("#second").find(':selected').remove();
         getSelectMultiple();
     });
   
     function getSelectMultiple() {
         $("#second option").prop('selected', true);
     }
   });
   
</script>
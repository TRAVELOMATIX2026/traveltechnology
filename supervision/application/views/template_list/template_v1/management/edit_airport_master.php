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
                     <h1>Edit Airport </h1>
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
                                 <form class='form row validate-form' style='margin-bottom: 0;' action="<?php echo base_url(); ?>index.php/management/update_airport_master/<?php echo $flight_airport_list['data'][0]['origin'];?>" method="post" enctype="multipart/form-data">
                                
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3'  for='validation_current'>Airport Code
                                       </label>
                                       <div class='col-sm-6 controls'>
                                          <div class="controls">
                                             <input type="text" name="airport_code" id="airport_code" value ="<?php echo $flight_airport_list['data'][0]['airport_code'];?>" data-rule-required='true' class='form-control' required>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Airport Name</label>
                                       <div class="col-sm-6 controls"><input type="text" name="airport_name" id="airport_name" placeholder="Airport Name"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['airport_name'];?>" required></div>
                                    </div>
                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Airport City</label>
                                       <div class="col-sm-6 controls"><input type="text" name="airport_city" id="airport_city" placeholder="Airport City"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['airport_city'];?>" required></div>
                                    </div>
                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Airport Country</label>
                                       <div class="col-sm-6 controls"><input type="text" name="country" id="country" placeholder="Airport Country"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['country'];?>" required></div>
                                    </div>
                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Country Code</label>
                                       <div class="col-sm-6 controls"><input type="text" name="CountryCode" id="CountryCode" placeholder="Country Code"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['CountryCode'];?>" required></div>
                                    </div>
                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Latitude</label>
                                       <div class="col-sm-6 controls"><input type="text" name="lat" id="lat" placeholder="Latitude"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['lat'];?>" required></div>
                                    </div>

                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Longitude</label>
                                       <div class="col-sm-6 controls"><input type="text" name="lon" id="lon" placeholder="Longitude"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['lon'];?>" required></div>
                                    </div>

                                    <div class="form-group">
                                       <label class="form-label col-sm-3" for="validation_current">Timezone</label>
                                       <div class="col-sm-6 controls"><input type="text" name="timezonename" id="timezonename" placeholder="Longitude"  class="form-control" value="<?php echo $flight_airport_list['data'][0]['timezonename'];?>" required></div>
                                    </div>
                                 
                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_current'>Is International</label>
                                       <div class='col-sm-6 col-md-6 controls'>
                                         <input type="checkbox" name="IsInternational" id="IsInternational" value="1" <?php if($flight_airport_list['data'][0]['IsInternational']){ echo 'checked';}?>/>   
                                       </div>
                                    </div>

                                    <div class='form-group'>
                                       <label class='form-label col-sm-3' for='validation_current'>Is Traveller</label>
                                       <div class='col-sm-6 col-md-6 controls'>
                                         <input type="checkbox" name="isTravellerAirport" id="isTravellerAirport" value="1" <?php if($flight_airport_list['data'][0]['isTravellerAirport']){ echo 'checked';}?>/>   
                                       </div>
                                    </div>
                                
                              </div>
                           </div>
                        </div>
                        <div class='form-actions' style='margin-bottom:0'>
                        <div class='row'>
                        <div class='col-sm-9 offset-sm-3'>
                        <a href="<?php echo base_url(); ?>management/airport_list_master">
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
   
     $('#tours_city').on('click', function() {
         var options = $("#tours_city").find(':selected').clone();
         $('#second').append(options);
         getSelectMultiple();
     });
   
     $('#second').on('click', function() {
         $("#second").find(':selected').remove();
         getSelectMultiple();
     });
   
     function getSelectMultiple() {
         $("#second option").prop('selected', true);
     }
   });
   
</script>
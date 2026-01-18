<div id="Package" class="bodyContent col-md-12">
	<div class="panel card">
		<!-- PANEL WRAP START -->
		<div class="card-header">
			<!-- PANEL HEAD START -->
			<div class="card-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active" id="add_package_li">
						<a href="#add_package" aria-controls="home" role="tab" data-bs-toggle="tab">Add Package </a>
					</li>
					<!-- <li role="presentation" class="" id="itenary_li">
						<a href="#itenary" aria-controls="home" role="tab" data-bs-toggle="">Add Itenary </a>
					</li>
					<li role="presentation" class="" id="gallery_li">
						<a href="#gallery" aria-controls="home" role="tab" data-bs-toggle="">Photo Gallery </a>
					</li>
					<li role="presentation" class="" id="rate_card_li">
						<a href="#rate_card" aria-controls="home" role="tab" data-bs-toggle="">Rate Card </a>
					</li> -->
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="card-body">
			<!-- PANEL BODY START -->
			<form action="<?php echo base_url(); ?>index.php/supplier/add_package_new" method="post" enctype="multipart/form-data" class='form row validate-form'>
			<div class="tab-content">
				<!-- Add Package Starts -->
				<div role="tabpanel" class="tab-pane active" id="add_package">
					<div class="col-md-12">
						
							<input type="hidden" name="a_wo_p" value="a_w"> <input
								type="hidden" name="deal" value="0">
								<div class="form-group hide">
									<label class="form-label col-sm-3" for="validation_name">Domain</label>
									<div class="col-sm-6 controls">
										<select class="form-control" data-rule-required="true" name="domain" id="domain" required="" tabindex="-1" aria-hidden="true">
											                                  
											<option value="1">COM</option>  
											<option value="2">IN</option>                         
											 </select>
											 <span id="domain" style="color: #F00; display: none;">validate</span>
											</div>
										</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_name'>Package
									type</label>
								<div class='col-sm-6 controls'>
									<select class='select2 form-control add_pckg_elements select2-drop-down'
										data-rule-required='true' name='disn' id="disn"
										required>
										<option value=''>Select Package Type</option>
                        <?php
							for($l = 0; $l < count ( $package_type_data ); $l ++) {
						?>
                        <option value='<?php echo $package_type_data[$l]->package_types_id; ?>'> <?php echo $package_type_data[$l]->package_types_name; ?>  </option>
                        <?php
							}
						?>
                      </select> <span id="distination"
										style="color: #F00; display: none;">validate</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>Package
									Name </label>
								<div class='col-sm-6 controls'>
									<div class="controls">
										<input type="text" name="name" id="name"
											data-rule-minlength='2' data-rule-required='true' placeholder="Enter Package Name"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
							<div class="form-group"><label class="form-label col-sm-3" for="validation_current">No of Pax </label><div class="col-sm-6 controls"><input type="number" name="no_of_pax" id="no_of_pax" placeholder="Enter Number of Pax"  class="form-control add_pckg_elements"></div></div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='adult'>Price</label>
								<div class='col-sm-6 controls'>
									<input type="text" name="p_price" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Price"
										class='form-control add_pckg_elements numeric' maxlength='10' minlength='3'required>
								</div>
							</div>
							<!-- <div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>Duration - No of Days
								</label>
								<div class="col-sm-6 controls">
									<input type="text" name="duration" data-rule-number='true'
										data-rule-required='true'
										class="form-control add_pckg_elements numeric" maxlength='2' minlength='1' id="duration"
										onblur="show_duration_info(this.value)" size="40"
										placeholder="Enter Number Between 1-10" required>
								</div>
							</div> -->
							<div class="form-group">
							<label class='form-label col-sm-3' for='validation_current'>Duration</label>
								<input type="radio" name="duration_status" value="1" checked onclick="enable_duration(1)"/> With Duration &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								<input type="radio" name="duration_status" value="0" onclick="enable_duration(0)"/> Without Duration
							</div>
							<div class='form-group' id="duration_status">
								<label class='form-label col-sm-3' for='validation_current'>Choose Duration
								</label>
								<div class='col-sm-6 col-md-6 controls'>
						        <select class='select2 form-control select2-drop-down' data-rule-required='true' name='duration' id="duration" data-rule-required='true'>   
								<option value="0">Please Select</option>       
						          <?php
						          for($dno=0;$dno<=30;$dno++)
						          {
						           if($dno==0) { 
						            $DayNight = ($dno+1).' Day | '.($dno).' Nights';
						           }elseif($dno==1) { 
						            $DayNight = ($dno+1).' Days | '.($dno).' Night';
						           }else 
						           {
						            $DayNight = ($dno+1).' Days | '.($dno).' Nights';
						           }
						           echo '<option value="'.($dno+1).'">'.$DayNight.'</option>';
						          }
						          ?>
								</select>				
								</div>
							</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>Choose Region
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tours_continent' id="tours_continent" data-rule-required='true' required>
                               <option value="NA">Select Region </option>
                                <?php
                                foreach($tours_continent as $tours_continent_key => $tours_continent_value)
                                {
                                	echo '<option value="'.$tours_continent_value['origin'].'">'.$tours_continent_value['name'].' </option>';
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
                              
                               
								</select>				
								</div>
							</div>

							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>Choose City
								</label>
								<div class='col-sm-3 col-md-3 controls'>
								
								<select class='select2 form-control' data-enable-select2="false" data-rule-required='true' name='tours_city[]' id="tours_city" multiple data-rule-required='true' required>
                                                            
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
								<div class='col-sm-8 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='country' id="country" required>
										
										<option value="">Select Location</option>
                        <?php foreach ($country as $coun) {?>
                        <option value='<?php echo $coun->country_id; ?>'><?php echo $coun->name; ?></option>
                        <?php }?>
                      </select>
								</div>
							</div> -->
							<!-- <div class='form-group' id="city_name_div">
								<label class='form-label col-sm-3' for='validation_current'>City
								</label>
								<div class='col-sm-8 controls'>
									<select class='select2 form-control add_pckg_elements'
										name='cityname_old' id="cityname" multiple="multiple" required>
										<option value=''>Select city</option>
									</select>
								</div>
							</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>City List
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" class="normal" id="textbox" name="cityname" style="width:775px;" required></td>
								</div>
							</div> -->
							
							<!-- <div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>Location
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="location" id="location" placeholder="Enter Location"
										data-rule-required='true'
										class='form-control add_pckg_elements' required>
								</div>
							</div> -->
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_company'>Package
									Display Image</label>
								<div class='col-sm-8 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Package Image</span>
								</div>
							</div>
							<!-- <div class='form-group'>
								<label class='form-label col-sm-3' for='validation_current'>Display on HomePage
								</label>
								<div class='col-sm-8 controls'>
									<select class='form-control'
										data-rule-required='true' name='top_destination' id="top_destination" required>
										<option value="0">0</option>
										<option value="1">1</option>
										
									</select>
								</div>
							</div> -->
							<div class='form-group' id="ckeditordiv">
								<label class='form-label col-sm-3' for='validation_name'>Description</label>
								<div class='col-sm-8 controls'>
									<textarea name="Description" data-rule-required='true' class="form-control summernote"  placeholder="Description" required></textarea>                             <!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->

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
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_rating'>Rating
								</label>
								<div class="col-sm-4 controls">
									<select class='form-control add_pckg_elements select2-drop-down' data-rule-required='true' name='rating' id="rating" required>
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="form-label col-sm-3" for="top_destination">Status<span class="text-danger">*</span></label>
								<div class="col-sm-8 controls">
									<label class="form-check form-check-inline">
										<input type="radio" name="top_destination" value="1" required checked> Active
									</label>
									<label class="form-check form-check-inline">
										<input type="radio" name="top_destination" value="0" required> Inactive
									</label>
								</div>
							</div>
							<div class='form-group'>
								<div id="addCityButton" class="col-lg-2" style="display: none;">
									<input type="button" class="srchbutn comncolor"
										id="addCityInput" value="Add Peroid"
										style="padding: 3px 10px;">
								</div>
								<div id="removeCityButton" class="col-lg-2"
									style="display: none;">
									<input type="button" class="srchbutn comncolor"
										id="removeCityInput" value="Remove Peroid"
										style="padding: 3px 10px;">
								</div>
							</div>
							<!-- <div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 offset-sm-3'>
										<a class='btn btn-primary' id="add_package_button"> submit &
											continue</a>&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>supplier/view_with_price"> Cancel</a>
									</div>
								</div>
							</div> -->
					</div>
					<div class="col-md-12">
						<div class="duration_info_class clearfix" id="duration_info">
						<div class='form-group clearfix'>
								<label class='form-label col-sm-3' for='validation_name'>Days </label>
								<div class='col-sm-4 controls'>
									<input type="text" name="days[]" id="days"
										data-rule-required='true' placeholder="Days"
										class='form-control itenary_elements' required>
								</div>
							</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_name'>Place </label>
								<div class='col-sm-6 controls'>
									<input type="text" name="place[]" id="Place"
										data-rule-required='true' placeholder="Place"
										class='form-control itenary_elements' required>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='form-label col-sm-3' for='validation_company'>Itinerary Image</label>
								<div class='col-sm-3 controls'>
									<input type="file" title='Image to add'
										class='itenary_elements' data-rule-required='true' id='image'
										name='image[]' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Itinerary
										Image</span>
								</div>
							</div>
							
							<div class='form-group clearfix'>
								<label class='form-label col-sm-3' for='validation_desc'>Itinerary Description </label>
								<div class='col-sm-8 controls'>
									<textarea name="desc[]" class="form-control itenary_elements summernote"
										data-rule-required="true"
										placeholder="Description" required></textarea>
								</div>
							</div>
						</div>
						<!-- <div class='form-actions' style='margin-bottom: 0'>
							<div class='row'>
								<div class='col-sm-9 offset-sm-3'>
									<a class='btn btn-primary' id="itenary_button">submit &
										continue</a>
								</div>
							</div>
						</div> -->
					</div>
					<div class="col-md-12">
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_company'>Add Images</label>
							<div class='col-sm-8 controls'>
								<input type="file" title='upload Photos'
									class='gallery_elements' data-rule-required='true'
									value="upload photo" id='traveller' name='traveller[]' multiple
									required> <span id="travel" style="color: #F00; display: none">
									Upload Image</span>
							</div>
						</div>
						<!-- <div class='form-actions' style='margin-bottom: 0'>
							<div class='row'>
								<div class='col-sm-9 offset-sm-3'>
									<a class='btn btn-primary' id="gallery_button">submit & continue</a>
								</div>
							</div>
						</div> -->
					</div>
					<div class="col-md-12">
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_includes'>Price Includes </label>
							<div class='col-sm-8 controls'>
								<!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
								<textarea name="includes" class="form-control rate_card_elements summernote"  data-rule-required="true" placeholder="Price Includes" required></textarea>
							</div>
						</div>
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_excludes'>Price Excludes </label>
							<div class='col-sm-8 controls'>
								<textarea name="excludes" class="form-control rate_card_elements summernote" data-rule-required="true" placeholder="Price Excludes" required></textarea>
							</div>
						</div>
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_advance'>Cancellation In Advance </label>
							<div class='col-sm-8 controls'>
								<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
								<textarea name="advance" class="form-control rate_card_elements summernote" data-rule-required="true" placeholder="Cancellation In Advance" required></textarea>
							</div>
						</div>
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_excludes'>Cancellation Penalty </label>
							<div class='col-sm-8 controls'>
								<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
								<textarea name="penality" class="form-control rate_card_elements summernote" data-rule-required="true" placeholder="Cancellation Penalty" required></textarea>
							</div>
						</div>
						<div class='form-actions' style='margin-bottom: 0'>
							<div class='row'>
								<div class='col-sm-9 offset-sm-3'>
									<button class='btn btn-primary'  type='submit'>submit</button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<!-- Add PAckage Ends -->

				<!-- Itenary Starts -->
				<!-- <div role="tabpanel" class="tab-pane" id="itenary">
					<div class="col-md-12">
						<div class="duration_info_class clearfix" id="duration_info">
						<div class='form-group clearfix'>
								<label class='form-label col-sm-3' for='validation_name'>Days </label>
								<div class='col-sm-4 controls'>
									<input type="text" name="days[]" id="days"
										data-rule-required='true' placeholder="Days"
										class='form-control itenary_elements' required>
								</div>
							</div>
							<div class='form-group'>
								<label class='form-label col-sm-3' for='validation_name'>Place </label>
								<div class='col-sm-6 controls'>
									<input type="text" name="place[]" id="Place"
										data-rule-required='true' placeholder="Place"
										class='form-control itenary_elements' required>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='form-label col-sm-3' for='validation_company'>Itinerary Image</label>
								<div class='col-sm-3 controls'>
									<input type="file" title='Image to add'
										class='itenary_elements' data-rule-required='true' id='image'
										name='image[]' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Itinerary
										Image</span>
								</div>
							</div>
							
							<div class='form-group clearfix'>
								<label class='form-label col-sm-3' for='validation_desc'>Itinerary Description </label>
								<div class='col-sm-8 controls'>
									<textarea name="desc[]" class="form-control itenary_elements summernote"
										data-rule-required="true"
										placeholder="Description" required></textarea>
								</div>
							</div>
						</div>
						<div class='form-actions' style='margin-bottom: 0'>
							<div class='row'>
								<div class='col-sm-9 offset-sm-3'>
									<a class='btn btn-primary' id="itenary_button">submit &
										continue</a>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				<!-- Itenary Ends -->

				<!-- Photo Gallery Starts -->
				<!-- <div role="tabpanel" class="tab-pane" id="gallery">
					<div class="col-md-12">
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_company'>Add Images</label>
							<div class='col-sm-8 controls'>
								<input type="file" title='upload Photos'
									class='gallery_elements' data-rule-required='true'
									value="upload photo" id='traveller' name='traveller[]' multiple
									required> <span id="travel" style="color: #F00; display: none">
									Upload Image</span>
							</div>
						</div>
						<div class='form-actions' style='margin-bottom: 0'>
							<div class='row'>
								<div class='col-sm-9 offset-sm-3'>
									<a class='btn btn-primary' id="gallery_button">submit & continue</a>
								</div>
							</div>
						</div>
					</div>
				</div> -->
				<!-- Photo Gallery Ends -->

				<!-- Rate card Starts -->
				<!-- <div role="tabpanel" class="tab-pane" id="rate_card">
					<div class="col-md-12">
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_includes'>Price Includes </label>
							<div class='col-sm-8 controls'>
								<textarea name="includes" class="form-control rate_card_elements summernote"  data-rule-required="true" placeholder="Price Includes" required></textarea>
							</div>
						</div>
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_excludes'>Price Excludes </label>
							<div class='col-sm-8 controls'>
								<textarea name="excludes" class="form-control rate_card_elements summernote" data-rule-required="true" placeholder="Price Excludes" required></textarea>
							</div>
						</div>
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_advance'>Cancellation In Advance </label>
							<div class='col-sm-8 controls'>
								<textarea name="advance" class="form-control rate_card_elements summernote" data-rule-required="true" placeholder="Cancellation In Advance" required></textarea>
							</div>
						</div>
						<div class='form-group clearfix'>
							<label class='form-label col-sm-3' for='validation_excludes'>Cancellation Penalty </label>
							<div class='col-sm-8 controls'>
								<textarea name="penality" class="form-control rate_card_elements summernote" data-rule-required="true" placeholder="Cancellation Penalty" required></textarea>
							</div>
						</div>
						<div class='form-actions' style='margin-bottom: 0'>
							<div class='row'>
								<div class='col-sm-9 offset-sm-3'>
									<button class='btn btn-primary'  type='submit'>submit</button>
								</div>
							</div>
						</div>
						
					</div>
				</div> -->
				<!-- Rate card Ends -->

			</div>
			</form>
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
		 $('#duration_info').show();
		}else{
		$('#duration').val(0);
		$('#duration_info').hide();
		$('#duration_status').hide();
		}

	}
     $(document).ready(function(){
		$('.summernote').summernote({
			height: 250, // set editor height
			minHeight: null, // set minimum height of editor
			maxHeight: null, // set maximum height of editor
			focus: true // set focus to editable area after initializing summernote
		});


         $('#country').on('change', function() {
           $.ajax({
           url: 'get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
           	if(json.result=='<option value="">Select City</option>'){
           		$('#city_name_div').addClass('hide');
           	}
           	else{
           		$('select[name=\'cityname_old\']').html(json.result);
           	}
           }
       	});
         });
        $("#cityname").on('click',function(){
        	var dropdownVal=$(this).val();

        	$("#textbox").val(dropdownVal); 
		
    	});

     });
  
	$('#duration').on('select2:select', function(e) {
		let duration = $(this).val();
		if (duration == '') {
			duration = 0;
		}

		if (window.XMLHttpRequest) {
			// For modern browsers
			xmlhttp = new XMLHttpRequest();
		} else {
			// For older IE versions
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function()
	       {
	       	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	       {
	      	 document.getElementById("duration_info").innerHTML=xmlhttp.responseText;
			   	$('#duration_info .iteditor').each(function() {
					$(this).summernote({
						height: 250, // set editor height
						minHeight: null, // set minimum height of editor
						maxHeight: null, // set maximum height of editor
						focus: true // set focus to editable area after initializing summernote
					});
				});
	       }
	       }

		xmlhttp.open("GET", "itinerary_loop/" + duration, true);
		xmlhttp.send();
	});
     $("#addanother").click(function(){
     var addin = '<input type="text" name="ancountry" value="" placeholder="country" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="anstate" placeholder="state" value="" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="ancity" placeholder="city" value="" class="ma_pro_txt" style="margin:2px;"/><div onclick="removeinput()" style="font-weight:bold;cursor:pointer;">Remove</div><br/>';
     $("#addmorefields").html(addin);
  });
  
  function removeinput(){
   $("#addmorefields").html('');
  }
  
       function activate(that) { window.location.href = that; }
  var a;
  $(document).ready(function(){ 
  
  $('#addCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
    //alert(cityNo);
    var duration = $('#duration').val();
   var cityNo = cityNo+1;
    var cit = cityNo-1;
   var allCity = '';
   var i = cityNo;
   var s = i-1;
    
   allCity += "<div id='bothCityInputs"+i+"'><div class='form-group'><label class='form-label col-sm-2' for='validation_company'>From Date</label><div class='input-group col-sm-3' ><input class='fromd datepicker2 b2b-txtbox form-control' placeholder='MM/DD/YYYY' id='deptDate"+i+"'  myid='"+i+"' name='sd[]'' type='text'><span class='input-group-text'><i class='icon-calendar'></i></span></div><label class='form-label col-sm-2' for='validation_name'>To Date</label><div class='input-group col-sm-3' ><input class='form-control b2b-txtbox' placeholder='MM/DD/YYYY' id='too"+i+"' name='ed[]'' type='text' readonly><span class='input-group-text'><i class='icon-calendar'></i></span><span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span><br></div><br></div>";
  
   allCity += "<div class='form-group clearfix'><label class='form-label col-sm-2' for='adult'>Adult Price</label><div class='input-group col-sm-3' ><input type='text' name='adult[]' id='adult"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-text'><i class='icon-usd'></i></span></div><label class='form-label col-sm-2' for='child'>Child Price</label><div class='input-group col-sm-3' ><input type='text' name='child[]' id='child"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-text'><i class='icon-usd'></i></span></div></div><hr>";
  allCity += '<script>var d1 = $("#deptDate'+cit+'").datepicker("getDate");'+
                 //'var dd = d1.getDate() + 1;var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'd1.setDate(d1.getDate() + parseInt(1));'+
                  'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 //'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 'alert(to_date);'+
                  'var duration = $("#duration").val();'+
                  '$("#deptDate'+i+'").datepicker({'+
                  'dateFormat: "mm/dd/yy",'+
                  'minDate: to_date,'+
                   'onSelect: function(dateStr) {'+
                    'var d1 = $(this).datepicker("getDate");'+  
                    
  
                    'd1.setDate(d1.getDate() + parseInt(duration));'+
                   
                     'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                     'var to_date = (mm) + "/" + dd + "/" + yy;'+
                     '$("#too'+i+'").val(to_date);'+
                       '}'+
                    '});'+
                       '<\/script>'+
                       '</div>';
   //$("#addMultiCity").append("<label class='form-label col-sm-2' for='validation_company'>From</label><div class='col-sm-3 controls'><input name='sd' id='' type='text' class='datepicker2 b2b-txtbox form-control'     />   <span id='dorigin_error6' style='color:#F00;'></span><br></div><label class='form-label col-sm-3' for='validation_name'>To</label><div class='col-sm-3 controls'><input name='ed' id='' type='text' class='datepicker3 b2b-txtbox form-control'   />  <span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span></div>");
                         
   $("#addMultiCity").append(allCity);
   if(cityNo>1){
     $("#removeCityButton").show();
   }
   $('#multiCityNo').val(cityNo);
     });
  $('#removeCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
   
   var allCity = '';
   if(cityNo >1){
     $("#bothCityInputs"+cityNo).remove();
     var cityNo = cityNo-1;
     if(cityNo>1){
       $("#removeCityButton").show();
   }
   }
   else
      {
     $("#removeCityButton").hide();
      }
   $('#multiCityNo').val(cityNo);
  });  

$('#add_package_button').click(function(){
	var error_free = true;
    $( ".add_pckg_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
    // if(value == ''){
    // 	 error_free = false;
    //       $( this ).closest( ".form-group" ).addClass( "has-error" );
    // }
      if(error_free)
      {
      	  $("#add_package_li").removeClass("active");
      	  $("#add_package").removeClass("active");
      	  $("#itenary_li").addClass("active");
      	  $("#itenary").addClass("active");
      }
    

    ///  alert(value);
	  });
$('#itenary_button').click(function(){
	var error_free = true;
    $( ".itenary_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#itenary_li").removeClass("active");
	  $("#itenary").removeClass("active");
	  $("#gallery_li").addClass("active");
	  $("#gallery").addClass("active");
      }
	  });
$('#gallery_button').click(function(){
	var error_free = true;
    $( ".gallery_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#gallery_li").removeClass("active");
	  $("#gallery").removeClass("active");
	  $("#rate_card_li").addClass("active");
	  $("#rate_card").addClass("active");
      }
	  });
  });
    $(document).ready(function(){ 
  
  $(document).on("change",".fromd",function(){ 
     current_date = $(this).val();
     
   current_id = $(this).attr('id');
   // alert(current_id);
  $(".fromd").each(function(){ 
     previous_dates = $(this).val();
      //alert(previous_dates);
     currenr_id=$(this).attr('id');
  
      
     if(current_date == previous_dates && current_id != currenr_id){
   myid=$("input[type='text']#"+current_id).attr('myid');
     alert("Already Same Date Selected");
     $("#"+current_id).val(" ");
    // alert(myid);
      $("#to"+myid).val(" ");
        $("#too"+myid).val(" ");
  }
   });
  });
  });
  
    $('#validation_country').on('change', function(){
        var country=$(this).val();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>supplier/get_cities/"+country,
            data:{country:country},
            success:function(wcity)
            {
            //	alert(wcity);
              $('#city').html(wcity);
            }
          });  
      });

    $('#tours_continent').on('change', function() { 
        	// alert('hi');
            
        tours_continent = $('#tours_continent').val();
 //alert(tours_continent);
        if(tours_continent!='NA'){
        	$.post('<?=base_url();?>index.php/supplier/ajax_tours_continent',{'tours_continent':tours_continent},function(data){
          	  //alert(data);
              $('#tours_country').html(data);
              $('#tours_city').html('');
              tours_country.bootstrapDualListbox('refresh', true);
         	});
        }else{
        	$('#tours_country').html('');
            $('#tours_city').html('');
        }
        });

    $('#tours_country').on('change', function() { 
           
         	var tours_countries = $('#tours_country').val();
         
         	if(tours_countries==null){
         		$('#tours_city').html('');
         	}
         	if(tours_countries.length > 0 ){
				var tours_country_list = tours_countries;
         	}else{
	         	var tours_country_list = tours_countries.split(',');
	         }

         	$.each(tours_country_list, function(index, item) {

			    // do something with `item` (or `this` is also `item` if you like)

		        $.post('<?=base_url();?>index.php/supplier/ajax_tours_country',{'tours_country':item},function(data)
		        {
		        	if(index>0){
			            $('#tours_city').append(data);
			            $('#tours_city').bootstrapDualListbox('refresh', true);
			        }else{			        	
			            $('#tours_city').html(data);
			            $('#tours_city').bootstrapDualListbox('refresh', true);
			        }
		        });
	        });
		});

    $('#tours_city').on('change', function() {
		    var options = $("#tours_city").find(':selected').clone();
		    $('#second').append(options);
		    getSelectMultiple();
		});

		$('#second').on('change', function() {
		   $("#second").find(':selected').remove();
		   getSelectMultiple();
		});

		function getSelectMultiple(){
			$("#second option").prop('selected', true);
		}
</script>
<?php //debug($data); die; ?>
<div class="bodyContent col-md-12">
<h4 class="mb-3">Manage Domain</h4>
	<div class="panel card clearfix"><!-- PANEL WRAP START -->
		<div class="p-0">
			<div class="col-md-12 text-center domain_logo_align p-4">
				<?php echo get_domain_logo($data['domain_logo']);?>
			</div>
			<div class="col-md-12 p-4 pt-0">
				<form role="form" id="domain_logo" enctype="multipart/form-data" method="POST" action="" autocomplete="off" name="domain_logo">
					<input type="hidden" value="<?php echo get_domain_auth_id();?>" required="" class="origin hiddenIp" id="origin" name="origin">
					<div class="form-group row gap-0 mb-0">
						<div class="col-sm-12 col-md-6">
							<label>Domain Name <span class="text-danger">*</span></label>
							<input type="text" id="domain_name" class="form-control alpha" placeholder="Enter your Domain Name" required name="domain_name" value="<?php echo $data['domain_name'];?>">
						</div>
						<div class="col-sm-12 col-md-6">
							<label>Domain Website <span class="text-danger">*</span></label>
							<input type="text" id="domain_website" class="form-control alpha" placeholder="Enter your Domain Address" required name="domain_website" value="<?php echo $data['domain_webiste'];?>">
						</div>
						<div class="col-sm-12 col-md-6">
							<label>Email Id</label>
							<input type="email" id="email_id" class="form-control" placeholder="Enter your Email" name="email" value="<?php echo $data['email'];?>">
						</div>
						<div class="col-sm-12 col-md-6">
							<label>Phone Number</label>
							<input type="text" id="phone_number" class="form-control" placeholder="Enter Your Phone Number" name="phone" maxlength="10" value="<?php echo $data['phone'];?>">
						</div>
						<div class="col-sm-12">
							<label>Address <span class="text-danger">*</span></label>
							<input type="text" id="address" class="form-control" placeholder="Enter your Address" required name="address" value="<?php echo $data['address'];?>">
						</div>
						<div class="col-sm-12 col-md-6">
							<label>Country <span class="text-danger">*</span></label>
							<select id="country_code" required onchange="get_state_list()" name="country" class="form-control">
								<?php foreach($country_list as $country) { ?>
								<option value="<?php echo $country['origin']; ?>" <?php if($data['api_country_list_fk']==$country['origin']){ echo "selected"; }?>><?php echo $country['name'];?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-sm-12 col-md-6">
							<label>State <span class="text-danger">*</span></label>
							<select name="state" id="state_list" required onchange="get_city_list()" class="form-control">
								<option value="">Select State</option>
								<?php foreach($state_list as $city) { if(!empty($data['api_state_list_fk'])) {?>
								<option value="<?php echo $city['origin'];?>" <?php if($data['api_state_list_fk'] == $city['origin']) { echo "selected"; }?>><?php echo $city['state_province']; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="col-sm-12 col-md-6">
							<label>City <span class="text-danger">*</span></label>
							<select name="city" id="city_list" required class="form-control">
								<?php foreach($city_list as $city) { if(!empty($data['api_city_list_fk'])) {?>
								<option value="<?php echo $city['origin'];?>" <?php if($data['api_city_list_fk'] == $city['origin']) { echo "selected"; }?>><?php echo $city['destination']; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="col-sm-12 col-md-6">
							<label>Change Logo <span class="text-danger">*</span></label>
							<input type="file" id="domain_logo" class="form-control domain_logo" accept="image/*" name="domain_logo" <?php if(empty($data['domain_logo'])){ ?>required<?php } ?>>
						</div>
						<div class="col-sm-12">
							<div class="d-flex justify-content-end gap-3 mt-3">
								<button class="btn btn-gradient-success" id="domain_logo_submit" type="submit"><i class="bi bi-check-circle"></i> Submit</button>
								<button class="btn btn-gradient-warning" id="domain_logo_reset" type="reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
 function get_domain_logo($domain_logo) {
	// if (empty($domain_logo) == false && file_exists($GLOBALS['CI']->template->domain_image_full_path($domain_logo))) {
		return '<img src="'.$GLOBALS['CI']->template->domain_images($domain_logo).'" height="350px" width="350px" class="img-thumbnail">';
	// }
 }
?>
<script>
function get_state_list() {
	$('#city_list').html('<option value="">Select City</option>');
    country_code = $("#country_code").val();
 	country_code.trim();
   	$.ajax({
		type: 'POST',
		url: app_base_url+'index.php/user/get_state_list',
		data: { country_id: country_code},
		dataType: 'html',
		success: function(result) {
			$('#state_list').html(result);
		}
	});
} 
function get_city_list() {
    var country_code = $("#country_code").val();
 	country_code.trim();
    var state_id = $("#state_list").val();
	state_id.trim();
   	$.ajax({
		type: 'POST',
		url: app_base_url+'index.php/user/get_city_list',
		data: { country_id: country_code, state_id:state_id},
		dataType: 'html',
		success: function(result) {
			$('#city_list').html(result);
		}
	});
} 
</script>

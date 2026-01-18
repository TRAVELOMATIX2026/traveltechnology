<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
	<div class="table_outer_wrper"><!-- PANEL WRAP START -->
		<div class="panel_custom_heading"><!-- PANEL HEAD START -->
			<div class="panel_title">
				<ul class="nav nav-tabs b2b_navul" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active">
						<a id="fromListHead" href="#fromList" aria-controls="home" role="tab" data-bs-toggle="tab">
							<i class="bi bi-image"></i>
							<?php echo get_app_message('AL00314');?> Add Logo
						</a>
					</li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div><!-- PANEL HEAD START -->
		
		<div class="panel_bdy mb-3"><!-- PANEL BODY START -->
			<?php if(!empty($domain_logo) && file_exists($GLOBALS['CI']->template->domain_image_full_path($domain_logo))) { ?>
			<div class="card mb-3">
				<div class="card-header">
					<h5 class="card-title mb-0"><i class="bi bi-image"></i> Current Logo</h5>
				</div>
				<div class="card-body">
					<div class="domain_logo_align text-center">
						<?php echo get_domain_logo($domain_logo);?>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0"><i class="bi bi-upload"></i> Upload Logo</h5>
				</div>
				<div class="card-body">
					<form role="form" id="domain_logo" enctype="multipart/form-data" method="POST" action="" autocomplete="off" name="domain_logo">
						<input type="hidden" value="<?php echo get_domain_auth_id();?>" required="" class="origin hiddenIp" id="origin" name="origin">
						
						<div class="form-group">
							<label for="domain_logo" class="form-label">Change Logo<span class="text-danger">*</span></label>
							<input type="file" id="domain_logo" class="form-control" placeholder="" required="" accept="image/*" name="domain_logo" value="">
							<small class="text-danger d-block mt-2"><i class="bi bi-info-circle"></i> Size: 180*40</small>
						</div>
						
						<div class="form-group mt-3">
							<div class="d-flex gap-3">
								<button class="btn btn-success" id="domain_logo_submit" type="submit">
									<i class="bi bi-check-circle"></i> Submit
								</button>
								<button class="btn btn-warning" id="domain_logo_reset" type="reset">
									<i class="bi bi-arrow-counterclockwise"></i> Reset
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
</div>
<!-- HTML END -->
<?php 
function get_domain_logo($domain_logo) {
	if (empty($domain_logo) == false && file_exists($GLOBALS['CI']->template->domain_image_full_path($domain_logo))) {
		return '<img src="'.$GLOBALS['CI']->template->domain_images($domain_logo).'" height="350px" width="350px" class="img-thumbnail">';
	}
}
?>

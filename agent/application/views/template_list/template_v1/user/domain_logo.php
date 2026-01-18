<div class="bodyContent col-md-12">
	<div class="panel card clearfix"><!-- PANEL WRAP START -->
		<div class="card-header"><!-- PANEL HEAD START -->
			<div class="card-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class=""><a href="#fromList" aria-controls="home" role="tab" data-bs-toggle="tab"><?php echo get_app_message('AL00314');?> <span class="fa fa-image"></span> &nbsp;Add Logo</a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="card-body"><!-- PANEL BODY START -->
			<div class="tab-content">
				  <div role="tabpanel" class="tab-pane active clearfix" id="fromList">
						<div class="col-md-12">
							<div class="panel card border-info clearfix maxwdt">
							<div class="col-md-12 domain_logo_align">
							<?php echo get_domain_logo($domain_logo);?>
							</div>
							<div class="col-md-12">
								<form class="row" role="form" id="domain_logo" enctype="multipart/form-data" method="POST" action="" autocomplete="off" name="domain_logo">        
									<input type="hidden" value="<?php echo get_domain_auth_id();?>" required="" class=" origin hiddenIp" id="origin" name="origin">
								        <div class="form-group"></div>
								        <div class="form-group">
								            <label form="domain_logo" for="domain_logo" class="col-sm-4 form-label">Change Logo<span class="text-danger">*</span></label>
								            <div class="col-sm-8">
								                <input type="file" id="domain_logo" class=" domain_logo domain_logo" placeholder="" required="" accept="image/*" name="domain_logo" value="">
								                <br>
											<font color="Red"> Size: 180*40 </font>
								            </div>
								        </div>
								    <div class="form-group">
								        <div class="col-sm-8 offset-sm-4">
								            <button class=" btn btn-success " id="domain_logo_submit" type="submit">Submit</button>
								            <button class=" btn btn-warning " id="domain_logo_reset" type="reset">Reset</button>
								        </div>
								    </div>
								</form>
								</div>
							</div>
						</div>
				  </div>
			</div>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
</div>
<?php 
 function get_domain_logo($domain_logo) {
	if (empty($domain_logo) == false && file_exists($GLOBALS['CI']->template->domain_image_full_path($domain_logo))) {
		return '<img src="'.$GLOBALS['CI']->template->domain_images($domain_logo).'" height="350px" width="350px" class="img-thumbnail">';
	}
 }
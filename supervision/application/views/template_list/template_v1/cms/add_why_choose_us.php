<?php
   $tab1 = 'active';
   $get_data = $this->input->get();
   // debug($get_data);exit;
 	if(isset($get_data) && valid_array($get_data)){
 		$action = base_url().'index.php/cms/add_why_choose_us?&origin='.$get_data['origin'];
 	}
 	else{
 		$action = base_url().'index.php/cms/add_why_choose_us';
 	}
 	// echo $action;exit;
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel card"><!-- PANEL WRAP START -->
<div class="card-header"><!-- PANEL HEAD START -->
<div class="card-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-bs-toggle="tab">Manage Why Choose Us</a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="card-body"><!-- PANEL BODY START -->
<span class="error_msg"><?php $msg = $this->uri->segment(3); if(isset($msg)){ echo urldecode($msg); }?></span>
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?=$tab1?>" id="fromList">
<div class="card-body">


<div class="tab-content">
   <div id="fromList" class="clearfix tab-pane  active " role="tabpanel">
      <div class="card-body">
         <form class="row" role="form" enctype="multipart/form-data" method="POST" action="<?= $action; ?>" autocomplete="off" name="home_page_heading">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Add Why Choose Us</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 form-label">Title</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="header_title" class="form-control" placeholder="Title" name="header_title" value="<?php echo @$title?>" required>
                  	</div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_description" class="col-sm-3 form-label">Description</label>
                     <div class="col-sm-6">
                        <input type="text"  id="header_description" class="form-control" placeholder="Description" name="header_description" value="<?php echo @$description?>" required>
                     </div>
               </div>
              	<div class="form-group">
                  <label form="promo_codes_form_edit" for="header_icon" class="col-sm-3 form-label">Icon</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="header_icon" class="form-control" placeholder="Icon" name="header_icon" value="<?php echo @$icon?>" required >
                  	</div>
               </div>
              </fieldset>
            <div class="form-group">
            <?php if(isset($title)){
                  $button = 'Update';
               }else{
                  $button = 'Save';
               }
               ?>
               <div class="col-sm-8 offset-sm-4"> <button class="btn btn-success" type="submit"><?php echo $button; ?></button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
            </div>
         </form>
      </div>
   </div>
</div>


</div>
</div>

</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->


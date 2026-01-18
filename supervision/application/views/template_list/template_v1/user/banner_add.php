<?php
   $tab1 = 'active';
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel card"><!-- PANEL WRAP START -->
<div class="card-header"><!-- PANEL HEAD START -->
<div class="card-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-bs-toggle="tab">Manage Banners</a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="card-body"><!-- PANEL BODY START -->
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?=$tab1?>" id="fromList">
<div class="card-body">


<div class="tab-content">
   <div id="fromList" class="clearfix tab-pane  active " role="tabpanel">
      <div class="card-body">
         <form class="row" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'user/add_banner_action'?>" autocomplete="off" name="promo_codes_form_edit">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Add Banners</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 form-label">Title</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="banner_title" class="form-control" placeholder="Title" name="banner_title" value="">
                  	</div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 form-label">Description</label>
                  <div class="col-sm-6">
                  	<textarea class=" description form-control" rows="3" id="banner_description" name="banner_description" dt=""  data-original-title="" title=""></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 form-label">Banner Image</label>
                  <div class="col-sm-6">
                    <input type="file" name="banner_image" required="required">
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 form-label">Alt</label>
                     <div class="col-sm-6">
                        <input type="text"  id="alt" class="form-control" placeholder="ALT" name="alt" value="">
                     </div>
               </div>
               
               <div class="radio">
               	<label form="promo_codes_form_edit" for="status" class="col-sm-3 form-label">Status<span class="text-danger">*</span></label>
               		<label for="promo_codes_form_editstatus0" class="form-check form-check-inline">  
               			<input type="radio" value="0" id="promo_codes_form_editstatus0"  name="status" class=" status radioIp" dt="" required="">Inactive
               		</label>
               		<label for="promo_codes_form_editstatus1" class="form-check form-check-inline"> 
               		 <input type="radio" value="1" id="promo_codes_form_editstatus1" name="status" class=" status radioIp" checked="checked"  required="">Active
               		 </label>
               </div>
               <div class="form-group" style="margin-top:5px;">
                  <label form="promo_codes_form_edit" for="banner_order" class="col-sm-3 form-label">Order</label>
                  	<div class="col-sm-6">
                  		<input type="number" min="1" max="5"  id="banner_order" class="form-control" placeholder="Order" name="banner_order" value="0">
                  	</div>
               </div>
               <div class="form-group">
                        <label form="promo_codes_form_edit" for="module" class="col-sm-3 form-label">Module</label>
                        <div class="col-sm-6">
                        <select name="module" required class="form-control">
                            <option value=" ">Please Select</option>
                            <option value="flight">Flights</option>
                            <option value="hotel">Hotels</option>
                            <option value="transfer">Transfers</option>
                            <option value="activity">Activities</option>
                            <option value="car">Cars</option>
                            <option value="package">Holidays</option>
                        </select>
                     </div>
                    </div>
            </fieldset>
            <div class="form-group">
               <div class="col-sm-8 offset-sm-4"> <button class=" btn btn-success " id="promo_codes_form_edit_submit" type="submit">Save</button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
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


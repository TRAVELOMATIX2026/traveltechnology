<?php
$page_package_id = 0;
$package_days = 0;
?>

<div id="package_types" class="bodyContent col-md-12">
   <div class="panel card">
      <!-- PANEL WRAP START -->
      <div class="card-header">
         <!-- PANEL HEAD START -->
         <!-- <div class="card-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-bs-toggle="tab">
							<h1>Update Package Itinerary</h1>
					</a></li>
				</ul>
				<button id="addItinerary" class="btn btn-success">Add Itinerary</button>
			</div> -->
         <div class="card-title">
            <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab" style="margin: 0;">
               <li role="presentation" class="active">
                  <a href="#fromList" aria-controls="home" role="tab" data-bs-toggle="tab">
                     <h1 style="margin: 0;">Update Package Itinerary</h1>
                  </a>
               </li>
            </ul>
            <button id="addItinerary" class="btn btn-success" style="
    float: right;
    margin: 1%;
">Add Itinerary</button>
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
                           <div class='' style='margin-bottom: 0;'>
                              <form class='form row validate-form'
                                 style='margin-bottom: 0;'
                                 action="<?php echo base_url(); ?>supplier/update_itinerary/"
                                 method="post" enctype="multipart/form-data">
                                 <input type="hidden" id="deleted_itinerary_ids" name="deleted_itinerary_ids" value="">
                                 <?php $i = 0;
                                 foreach ($pack_data as $packdata) {
                                    $page_package_id = $packdata->package_id;
                                 ?>
                                    <div>
                                       <input type="hidden" id="itinerary_id_<?php echo $packdata->iti_id; ?>" name="itinerary_id[]"
                                          value="<?php echo $packdata->iti_id; ?>">
                                       <input
                                          type="hidden" name="package_id"
                                          value="<?php echo $packdata->package_id; ?>">

                                       <div class="duration_info" id="duration_info">
                                          <button type="button" class="btn btn-danger deleteItinerary" style="
    float: right;
">
                                             <i class="fas fa-trash-alt"></i>
                                          </button>
                                          <div class='form-group'>
                                             <?php $package_days = $packdata->day; ?>
                                             <label class='form-label col-sm-3' for='validation_name'>Days
                                             </label>
                                             <div class='col-sm-6 controls'>
                                                <input type="text" name="days[]" id="days" readonly
                                                   value="<?php echo $packdata->day; ?>"
                                                   data-rule-required='true' class='form-control'>
                                             </div>
                                          </div>
                                          <div class='form-group'>
                                             <label class='form-label col-sm-3' for='validation_name'>Place
                                             </label>
                                             <div class='col-sm-6 controls'>
                                                <input type="text" name="place[]" id="Place"
                                                   value="<?php echo $packdata->place; ?>"
                                                   data-rule-required='true' class='form-control'>
                                             </div>
                                          </div>
                                          <div class='form-group'>
                                             <label class='form-label col-sm-3'
                                                for='validation_company'>Itinerary Image</label>
                                             <div class='col-sm-8 controls'>
                                                <input type="file" title='Image to add' class=''
                                                   id='image' name='imagelable<?php echo $i; ?>'> <span
                                                   id="pacmimg" style="color: #F00; display: none">Please
                                                   Upload Itinerary Image</span> <img
                                                   src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images(basename($packdata->itinerary_image)); ?>"
                                                   width="100"> <input type="hidden" name="hiddenimage[]"
                                                   value="<?php echo $packdata->itinerary_image; ?>">
                                             </div>
                                          </div>
                                          <div class='form-group'>
                                             <label class='form-label col-sm-3' for='validation_desc'>Itinerary
                                                Description </label>
                                             <div class='col-sm-8 controls'>
                                                <textarea name="desc[]" class="form-control summernote"
                                                   data-rule-required="true" value="" cols="70" rows="3"
                                                   placeholder="Description"><?php echo $packdata->itinerary_description; ?></textarea>
                                             </div>
                                          </div>



                                          <hr>

                                       </div>
                                    </div>

                                 <?php $i++;
                                 } ?>
                                 <div class='form-actions'
                                    style='margin-bottom: 0'>
                                    <div class='row'>
                                       <div class='col-sm-9 offset-sm-3'>
                                          <a
                                             href="<?php echo base_url(); ?>supplier/view_with_price">
                                             <button class='btn btn-primary' type='button'>
                                                <i class='icon-reply'></i> Go Back
                                             </button>
                                          </a>&nbsp;&nbsp;
                                          <button class='btn btn-primary' type='submit'>
                                             <i class='icon-save'></i> Update
                                          </button>
                                       </div>
                                    </div>
                                 </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               </form>
               </section>
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

<div id="itineraryTemplate" style="display: none">
   <input type="hidden" id="itinerary_id_0" name="itinerary_id[]"
      value="0">
   <input
      type="hidden" name="package_id"
      value="<?php echo $page_package_id; ?>">
   <div class="duration_info">
      <button type="button" class="btn btn-danger deleteItinerary" style="
			float: right;
		">
         <i class="fas fa-trash-alt"></i>
      </button>
      <div class='form-group'>
         <label class='form-label col-sm-3' for='validation_name'>Days</label>
         <div class='col-sm-6 controls'>
            <input type="text" name="days[]" readonly value="" data-rule-required='true' class='form-control' required>
         </div>
      </div>
      <div class='form-group'>
         <label class='form-label col-sm-3' for='validation_name'>Place</label>
         <div class='col-sm-6 controls'>
            <input type="text" name="place[]" value="" data-rule-required='true' class='form-control' required>
         </div>
      </div>

      <div class='form-group'>
         <label class='form-label col-sm-3' for='validation_company'>Itinerary Image</label>
         <div class='col-sm-8 controls'>
            <input type="file" title='Image to add' name='imagelable[]' required>
            <span id="pacmimg" style="color: #F00; display: none">Please Upload Itinerary Image</span>
            <img src="" width="100" style="display:none;"> <!-- Placeholder for the image -->
            <input type="hidden" name="hiddenimage[]">
         </div>
      </div>

      <div class='form-group'>
         <label class='form-label col-sm-3' for='validation_desc'>Itinerary Description</label>
         <div class='col-sm-8 controls'>
            <textarea name="desc[]" class="form-control" id="summernote_id_0"  data-rule-required="true" value="" cols="70" rows="3" placeholder="Description"></textarea>
         </div>
      </div>


      <hr>
   </div>

</div>

<script>
   let counter = 0;

   document.getElementById('addItinerary').addEventListener('click', function() {
      var currentCount = document.querySelectorAll('.duration_info').length;

      var template = document.getElementById('itineraryTemplate').innerHTML;

      template = template.replace(/id="itinerary_id_\d+"/g, 'id="itinerary_id_new_' + currentCount + '"');
      template = template.replace(/id="summernote_id_\d+"/g, 'id="summernote_id_new_' + currentCount + '"');
      
      template = template.replace(/(name="itinerary_id\[\]"\s+value=")(\d+)(")/g, '$1new_' + currentCount + '$3');

      var container = document.createElement('div');
      container.innerHTML = template;

      var daysInput = container.querySelector('input[name="days[]"]');
      daysInput.value = currentCount + 1;

      var deleteButton = container.querySelector('.deleteItinerary');
      deleteButton.addEventListener('click', function() {
         container.remove();
         updateDays();
      });

      // Insert the new container into the form
      document.querySelector('form').insertBefore(container, document.querySelector('.form-actions'));

      // Update all 'Days' input fields to maintain incrementing order
      updateDays();
      console.log("'itinerary_id_new_" + currentCount + "'");

      window.scrollTo({
         top: document.body.scrollHeight,
         behavior: 'smooth'
      });
  
      $('#summernote_id_new_' + currentCount).summernote({
         height: 250, // set editor height
         minHeight: null, // set minimum height of editor
         maxHeight: null, // set maximum height of editor
         focus: true // set focus to editable area after initializing summernote
      });
   });

   $(document).on('click', '.deleteItinerary', function() {

      let inputValue1 = $(this).closest('form').find('div input[name="itinerary_id[]"]').val();

      if (inputValue1) {

         let deletedIds = $('#deleted_itinerary_ids').val();

         deletedIds = deletedIds ? deletedIds + ',' + inputValue1 : inputValue1;

         $('#deleted_itinerary_ids').val(deletedIds);

         $('#itinerary_id_' + inputValue1).remove();

         $(this).closest('.duration_info').remove();

         updateDays();
      }
   });

   function updateDays() {
      let dayInputs = document.querySelectorAll('input[name="days[]"]');
      dayInputs.forEach(function(input, index) {
         input.value = index + 1;
      });

      let itineraryElements = document.querySelectorAll('.duration_info');

      itineraryElements.forEach((element, index) => {
         const imageInput = element.querySelector('input[name^="imagelable"]');
         if (imageInput) {
            imageInput.name = 'imagelable' + index;
         }
      });
   }
</script>

<link rel="stylesheet" href="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/summernote/dist/summernote.css" />
<script type="text/javascript" src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/summernote/dist/summernote.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
      $('.summernote').summernote({
         height: 250, // set editor height
         minHeight: null, // set minimum height of editor
         maxHeight: null, // set maximum height of editor
         focus: true // set focus to editable area after initializing summernote
      });
   });
</script>
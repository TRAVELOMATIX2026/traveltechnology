<div id="package_types" class="bodyContent col-md-12">
	<div class="panel card mb-4">
		<div class="card-header bg-white border-bottom">
			<h5 class="mb-0"><i class="bi bi-box-seam-fill text-dark"></i> <?php echo (isset($pack_data[0])) ? 'Edit' : 'Add'; ?> Package Type</h5>
		</div>
		<div class="card-body p-4">
			<form action="<?php echo base_url(); ?>index.php/supplier/save_packages_type"
				  method="post" name="frm1" enctype="multipart/form-data" class="validate-form">
				<input type="hidden" name="package_types_id" value="<?=@$pack_data[0]->package_types_id;?>">
				<div class="row g-3 align-items-end">
					<div class="col-md-6">
						<label for="pname" class="form-label fw-semibold mb-2">Package Type <span class="text-danger">*</span></label>
						<input type="text" 
							   class="form-control" 
							   id="pname" 
							   name="name" 
							   value="<?=@$pack_data[0]->package_types_name;?>" 
							   placeholder="Enter package type name" 
							   data-rule-minlength="2"
							   data-rule-required="true"
							   required>
						<span id="pacname" class="text-danger" style="display: none;">Please Select Package Type</span>
					</div>
				</div>
				<div class="d-flex gap-2 mt-4">
					<a href="<?php echo base_url(); ?>index.php/supplier/view_packages_types" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left"></i> Back
					</a>
					<button class="btn btn-gradient-success" type="submit">
						<i class="bi bi-check-circle"></i> <?php echo (isset($pack_data[0])) ? 'Update' : 'Submit'; ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
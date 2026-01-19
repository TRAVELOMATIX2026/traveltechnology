<div id="package_types" class="bodyContent col-md-12">
  <div class="panel card mb-4">
    <div class="card-header bg-white border-bottom">
      <div class="d-flex w-100 justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-box-seam-fill text-dark"></i> View Packages</h5>
        <a href="<?php echo base_url(); ?>supplier/add_with_price" class="btn btn-gradient-success">
          <i class="bi bi-plus-circle"></i> Add Package
        </a>
      </div>
    </div>
    <div class="card-body p-4">
      <?php if(isset($status)){echo $status;}?>
      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" id="srch_inpt" class="form-control" onkeyup="srchFunctn()" placeholder="Search packages...">
        </div>
      </div>
      <div class="table-responsive" id="srch_table">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th width="5%">S.no</th>
              <th width="20%">Package Name</th>
              <th width="15%">Location</th>
              <th width="10%">Image</th>
              <th width="10%">Status</th>
              <th width="15%">Display on HomePage</th>
              <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if (! empty ( $newpackage )) {
              	$count = 1;
              	foreach ( $newpackage as $key => $package ) {
              		?>
            <tr>
              <td><span class="badge bg-secondary"><?php echo $count; ?></span></td>
              <td><span class="fw-medium"><?php echo $package->package_name; ?></span></td>
              <td><?php echo $package->package_city; ?>, <?php $country = $this->Supplierpackage_Model->get_country_name_new($package->package_country); echo $country->name; ?></td>
              <td>
                <a data-lightbox='flatty' href='<?php echo $package->image; ?>'>
                  <img width="70" height="60" class="img-thumbnail" 
                       title="<?= $package->package_name; ?>"
                       alt="<?= $package->package_name; ?>"
                       src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>">
                </a>
              </td>
              <td>
                <select class="form-select form-select-sm" onchange="activate(this.value);">
                  <?php if ($package->status == '1') { ?>
                  <option value="<?php echo base_url(); ?>supplier/update_status/<?php echo $package->package_id; ?>/1" selected>Active</option>
                  <option value="<?php echo base_url(); ?>supplier/update_status/<?php echo $package->package_id; ?>/0">In-Active</option>
                  <?php } else { ?>
                  <option value="<?php echo base_url(); ?>supplier/update_status/<?php echo $package->package_id; ?>/1">Active</option>
                  <option value="<?php echo base_url(); ?>supplier/update_status/<?php echo $package->package_id; ?>/0" selected>In-Active</option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <select class="form-select form-select-sm" onchange="activate(this.value);">
                  <?php if ($package->top_destination == '1') { ?>
                  <option value="<?php echo base_url(); ?>supplier/update_top_destination/<?php echo $package->package_id; ?>/1" selected>Active</option>
                  <option value="<?php echo base_url(); ?>supplier/update_top_destination/<?php echo $package->package_id; ?>/0">In-Active</option>
                  <?php } else { ?>
                  <option value="<?php echo base_url(); ?>supplier/update_top_destination/<?php echo $package->package_id; ?>/1">Active</option>
                  <option value="<?php echo base_url(); ?>supplier/update_top_destination/<?php echo $package->package_id; ?>/0" selected>In-Active</option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu<?php echo $package->package_id; ?>" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i> Actions
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu<?php echo $package->package_id; ?>">
                    <li>
                      <a class="dropdown-item" href="<?php echo base_url(); ?>supplier/edit_with_price/<?php echo $package->package_id; ?>">
                        <i class="bi bi-pencil"></i> Edit Package
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?php echo base_url(); ?>supplier/edit_itinerary/<?php echo $package->package_id; ?>">
                        <i class="bi bi-list-ul"></i> Edit Itinerary
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href='<?php echo base_url(); ?>supplier/images/<?=$package->package_id;?>/w'>
                        <i class="bi bi-images"></i> Edit Images
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?php echo base_url(); ?>supplier/view_enquiries/<?php echo $package->package_id; ?>/w">
                        <i class="bi bi-envelope"></i> View Enquiries
                      </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <a class="dropdown-item text-danger" href="<?php echo base_url(); ?>supplier/delete_package/<?php echo $package->package_id; ?>" 
                         onclick="return confirm('Do you want delete this record');">
                        <i class="bi bi-trash"></i> Delete Package
                      </a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <?php $count++; } } else { ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No packages found. <a href="<?php echo base_url(); ?>supplier/add_with_price">Add your first package</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function activate(that) { window.location.href = that; }
</script>
<style>
.external > tbody > tr > td, 
.external > tbody > tr > th, 
.external > tfoot > tr > td, 
.external > tfoot > tr > th, 
.external > thead > tr > td, 
.external > thead > tr > th {
padding: 6px;
}
</style>
<script>
  function srchFunctn() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("srch_inpt");
    filter = input.value.toUpperCase();
    table = document.getElementById("srch_table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td");
      if (td.length > 0) {
        var rowMatches = false;
        for (j = 0; j < td.length; j++) {
          if (td[j]) {
            txtValue = td[j].textContent || td[j].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              rowMatches = true;
              break;
            }
          }
        }
        if (rowMatches) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  }
</script>
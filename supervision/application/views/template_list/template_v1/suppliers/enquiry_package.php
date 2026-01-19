<div id="package_types" class="bodyContent col-md-12">
  <div class="panel card mb-4">
    <div class="card-header bg-white border-bottom">
      <div class="d-flex w-100 justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-envelope-fill text-dark"></i> View Enquiries</h5>
        <a href="<?php echo base_url(); ?>supplier/view_with_price" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Go Back
        </a>
      </div>
    </div>
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th width="5%">S.No</th>
              <th width="20%">Name</th>
              <th width="25%">Email</th>
              <th width="15%">Contact</th>
              <th width="15%">Date</th>
              <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php  
            if(!empty($enquiries)) { 
              $count = 1; 
              foreach($enquiries as $key => $package) { 
            ?>
            <tr>
              <td><span class="badge bg-secondary"><?php echo $count; ?></span></td>
              <td><span class="fw-medium"><?php echo $package->first_name; ?></span></td>
              <td><a href="mailto:<?php echo $package->email; ?>" class="text-decoration-none"><?php echo $package->email; ?></a></td>
              <td><a href="tel:<?php echo $package->phone; ?>" class="text-decoration-none"><?php echo $package->phone; ?></a></td>
              <td><?php echo $package->date; ?></td>
              <td>
                <a href="<?php echo base_url(); ?>supplier/delete_enquiry/<?php echo $package->id; ?>/<?php echo $package->package_id; ?>"
                   data-original-title="Delete"
                   onclick="return confirm('Do you want delete this record');"
                   class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i> Delete
                </a>
              </td>
            </tr>   
            <?php 
              $count++; 
              } 
            } else {
            ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                No enquiries found.
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
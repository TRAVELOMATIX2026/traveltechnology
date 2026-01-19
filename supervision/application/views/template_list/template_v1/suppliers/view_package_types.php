<div id="package_types" class="bodyContent col-md-12">
	<div class="panel card mb-4">
		<div class="card-header bg-white border-bottom">
			<div class="d-flex w-100 justify-content-between align-items-center">
				<h5 class="mb-0"><i class="bi bi-box-seam-fill text-dark"></i> Package Types</h5>
				<a href="<?php echo base_url(); ?>index.php/supplier/add_package_type" class="btn btn-gradient-success">
					<i class="bi bi-plus-circle"></i> Add Tour Types
				</a>
			</div>
		</div>
		<div class="card-body p-4">
			<div class="mb-3">
				<div class="input-group">
					<span class="input-group-text"><i class="bi bi-search"></i></span>
					<input type="text" id="srch_inpt" class="form-control" onkeyup="srchFunctn()" placeholder="Search package types...">
				</div>
			</div>
			<div class="table-responsive" id="srch_table">
				<table class="table table-hover align-middle">
					<thead class="table-light">
						<tr>
							<th width="10%">S.No</th>
							<th>Package Type</th>
							<th width="20%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){?>
						<tr>
							<td><span class="badge bg-secondary"><?=$c;?></span></td>
							<td><span class="fw-medium"><?=$k->package_types_name;?></span></td>
							<td>
								<div class="d-flex gap-2">
									<a class="btn btn-sm btn-outline-primary" 
									   href="<?php echo base_url(); ?>index.php/supplier/add_package_type/<?php echo $k->package_types_id; ?>"
									   title="Edit">
										<i class="bi bi-pencil"></i> Edit
									</a>
									<a class='btn btn-sm btn-outline-danger'
									   onclick="return confirm('Are you sure, do you want to delete this record?');"
									   href='<?php echo base_url(); ?>index.php/supplier/delete_package_type/<?=$k->package_types_id;?>'
									   title="Delete">
										<i class="bi bi-trash"></i> Delete
									</a>
								</div>
							</td>
						</tr>
						<?php $c++;}} else { ?>
						<tr>
							<td colspan="3" class="text-center text-muted py-4">
								<i class="bi bi-inbox fs-1 d-block mb-2"></i>
								No package types found. <a href="<?php echo base_url(); ?>index.php/supplier/add_package_type">Add your first package type</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$.validator.addMethod("buga", (function(value) {
  return value === "buga";
}), "Please enter \"buga\"!");

$.validator.methods.equal = function(value, element, param) {
  return value === param;
};


$(function () {
  $('#datetimepicker2').datetimepicker({
      startDate: new Date()
  });

  $('#datetimepicker1').datetimepicker({
      startDate: new Date()
  });
});


    </script>
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
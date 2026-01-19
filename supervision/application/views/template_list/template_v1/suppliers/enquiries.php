<div id="enquiries" class="bodyContent col-md-12">
	<div class="panel card mb-4">
		<div class="card-header bg-white border-bottom">
			<div class="d-flex w-100 justify-content-between align-items-center">
				<h5 class="mb-0"><i class="bi bi-envelope-fill text-dark"></i> View Enquiries</h5>
			</div>
		</div>
		<div class="card-body p-4">
			<div class="mb-3">
				<div class="input-group">
					<span class="input-group-text"><i class="bi bi-search"></i></span>
					<input type="text" id="srch_inpt" class="form-control" onkeyup="srchFunctn()" placeholder="Search enquiries...">
				</div>
			</div>
			<div class="table-responsive" id="srch_table">
				<table class="table table-hover align-middle">
					<thead class="table-light">
						<tr>
							<th width="5%">S.No</th>
							<th width="15%">Package Name</th>
							<th width="10%">Name</th>
							<th width="15%">Email</th>
							<th width="10%">Contact</th>
							<th width="25%">Message</th>
							<th width="10%">Date</th>
							<th width="10%">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($enquiries)) {
							$count = 1; 
							foreach($enquiries as $key => $package) { 
								// strip tags to avoid breaking any html
								$string = strip_tags($package->message);
								if (strlen($string) > 200) {
									// truncate string
									$stringCut = substr($string, 0, 200);
									// make sure it ends in a word so assassinate doesn't become ass... 
									$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a href="javascript:void(0);" class="openPopup text-primary text-decoration-none" data-package-id="'.$package->id.'">Read More</a>'; 
								}
						?>
						<tr>
							<td><span class="badge bg-secondary"><?php echo $count; ?></span></td>
							<td><span class="fw-medium"><?php echo $package->package_name; ?></span></td>
							<td><?php echo $package->first_name; ?></td>
							<td><a href="mailto:<?php echo $package->email; ?>" class="text-decoration-none"><?php echo $package->email; ?></a></td>
							<td><a href="tel:<?php echo $package->phone; ?>" class="text-decoration-none"><?php echo $package->phone; ?></a></td>
							<td><?php echo $string; ?></td>
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
							<td colspan="8" class="text-center text-muted py-4">
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
<!-- Modal -->
<?php if(!empty($enquiries)) { 
	foreach($enquiries as $key => $package) { 
?>
<div class="modal fade" id="myModal<?php echo $package->id; ?>" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="bi bi-envelope"></i> Package Enquiry - <?php echo $package->package_name; ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<strong>From:</strong> <?php echo $package->first_name; ?> (<?php echo $package->email; ?>)<br>
					<strong>Contact:</strong> <?php echo $package->phone; ?><br>
					<strong>Date:</strong> <?php echo $package->date; ?>
				</div>
				<hr>
				<div class="message-content">
					<?php echo nl2br(htmlspecialchars($package->message)); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php 
	}
} 
?>
<script type="text/javascript">
$(document).ready(function(){
	$('.openPopup').on('click',function(){
		var packageId = $(this).attr('data-package-id');
		$('#myModal' + packageId).modal('show');
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
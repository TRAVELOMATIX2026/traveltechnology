<div id="package_types" class="bodyContent col-md-12">
	<div class="panel card">
		<!-- PANEL WRAP START -->
		<div class="card-header">
			<!-- PANEL HEAD START -->
			<div class="card-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-bs-toggle="tab"><h1>Package Types</h1></a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="card-body">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12">
						<div class='row'>
							<div class=''
								style='margin-bottom: 0;'>
								<div class=''>
									<div class='actions'>
										<a
											href="<?php echo base_url(); ?>index.php/supplier/add_package_type">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Tour Types
											</button>
										</a> <a class="btn box-collapse btn-xs btn-link" href="#"><i></i></a>
										<input type="text" id="srch_inpt" onkeyup="srchFunctn()" placeholder="Search...">
									</div>
								</div>
								<div class=''>
									<div class='responsive-table' id="srch_table">
										<div class='scrollable-area'>
											<table
												class='data-table-column-filter table table-bordered table-striped'
												style='margin-bottom: 0;'>
												<thead>
													<tr>
														<th>S.No</th>
														<th>Package Type</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
                                      <?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){?>
                                      <tr>
														<td><?=$c;?></td>
														<td><?=$k->package_types_name;?></td>
														<td>
															<div class=''>
																<a class="btn btn-primary btn-xs has-tooltip"
																	data-placement="top" title=""
																	href="<?php echo base_url(); ?>index.php/supplier/add_package_type/<?php echo $k->package_types_id; ?>"
																	data-original-title="Edit Tour"> <i class="icon-edit"></i>Edit
																</a> &nbsp;<a class='btn btn-danger btn-xs has-tooltip'
																	data-placement='top' title='Delete'
																	onclick="return confirm('Are you sure, do you want to delete this record?');"
																	href='<?php echo base_url(); ?>index.php/supplier/delete_package_type/<?=$k->package_types_id;?>'>
																	<i class='icon-remove'></i>Delete
																</a>
															</div>
														</td>
													</tr>
                                      <?php $c++;}}?>
                                      </tbody>
											</table>
										</div>
									</div>
								</div>
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
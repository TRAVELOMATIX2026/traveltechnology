<div id="enquiries" class="bodyContent col-md-12">
	<div class="panel card">
		<!-- PANEL WRAP START -->
		<div class="card-header">
			<!-- PANEL HEAD START -->
			<div class="card-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-bs-toggle="tab"><h1>View Enquiries </h1></a></li>
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
						
						<div class='row'>
                <input type="text" id="srch_inpt" onkeyup="srchFunctn()" placeholder="Search..." style=" margin-bottom: 10px;">
                <div class='col-sm-14'>
                  <div class='' style='margin-bottom:0;'>
                    <div class=''>
                      <div class='responsive-table' id="srch_table">
                        <div class='scrollable-area'>
                          <table class='data-table-column-filter table table-bordered table-striped' style='margin-bottom:0;'>
                            <thead>
                            <tr>
                            <th>S.No</th>
                            <th>Package Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Message</th>
                            <!--<th>Status</th>-->
                            <th>Date</th>
                            <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                          <?php 
                        //debug($enquiries);exit;
                          $count = 1; 
                        foreach($enquiries as $key => $package) { 
                           
                           // debug($enquiries);exit;
                          if(!empty($enquiries)) {
                            // strip tags to avoid breaking any html
                            $string = strip_tags($package->message);
                            if (strlen($string) > 200) {
                                // truncate string
                                $stringCut = substr($string, 0, 200);

                                // make sure it ends in a word so assassinate doesn't become ass... 
                                $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a href="javascript:void(0);" class="openPopup">Read More</a>'; 
                            }
                           
                          ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $package->package_name; ?></td>
                        <td><?php echo $package->first_name; ?></td>
                        <td><?php echo $package->email; ?></td>
                        <td><?php echo $package->phone; ?></td>
                        <td><?php echo $string; ?></td>
                       <!--<td><?php 
							/*if($package->status == 1){
							echo '<span class="badge bg-success"><i class="fa fa-check"></i> Read</span>';
							}else{
							echo '<span class="badge bg-danger">Unread</span>     <a role="button" href="'.base_url().'index.php/supplier/read_enquiry/'.$package->id.'" >Read</a>';
							}*/
                       ?></td>-->
                        <td><?php echo $package->date; ?></td> 
                         <td class="center">                       
                                   <!--  <a class="btn btn-primary btn-xs has-tooltip" data-placement="top" title=""  href="<?php echo base_url(); ?>supplier/send_enq_mail/<?php echo $package->id; ?>"  data-original-title="Send mail">
                                      <i class="icon-envelope"></i>
                                    </a> -->
                                   <a href="<?php echo base_url(); ?>supplier/delete_enquiry/<?php echo $package->id; ?>/<?php echo $package->package_id; ?>"  data-original-title="Delete"  onclick="return confirm('Do you want delete this record');" class="btn btn-danger btn-xs has-tooltip" data-original-title="Delete"> 
                                  <i class="icon-remove">Delete</i>
                                   </a>
                        </td>
                      </tr>   
                  <?php  } 
                  $count++; 
                  } ?>  
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
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>
<!-- Modal -->
   <?php 
                        
                        foreach($enquiries as $key => $package) { ?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title">Package enquiry</h4>
            </div>
            <div class="modal-body">
  <?php echo $package->message; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.openPopup').on('click',function(){
        var dataURL = $(this).attr('data-href');
        
            $('#myModal').modal({show:true});
       
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
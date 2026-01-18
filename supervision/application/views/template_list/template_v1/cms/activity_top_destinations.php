<?php
 //debug($country_list);exit;
?>
<div class="bodyContent col-md-12">
<h4 class="mb-3">Top Destinations In Activities</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="card-body p-0">

            <form action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="POST" autocomplete="off" class="p-0">

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label>
                                City <span class="text-danger">*</span>
                            </label>
                            <select id="city" class="form-control" name="city" required="">
                                <option value="">Please Select</option>
                                <?php foreach ($city_list as $key => $val) { ?>
                                <option value="<?=$val['origin'];?>"><?=$val['destination_name'];?></option>
                            <?php } ?>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Image <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" accept="image/*" required="required" name="top_destination">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Alt <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" required="required" name="alt_text">
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <button class="btn btn-gradient-success" type="submit"><i class="bi bi-plus-circle"></i> Add</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>

    </div>
</div>

<div class="bodyContent col-md-12">
<h4 class="mb-3">Activities Top Destinations List</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive reprt_tble">

                <table class="table table-sm table-bordered example3" id="activity_top_destinations_table">
                <thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sno</th>
					<th><i class="bi bi-geo-alt"></i> City</th>
					<th><i class="bi bi-image"></i> Image</th>
					<th><i class="bi bi-text"></i> Alt</th>
					<th><i class="bi bi-globe2"></i> Domain</th>
					<th><i class="bi bi-gear"></i> Action</th>
				</tr>
                </thead>
                <tbody>
				<?php
				//debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?= ($k+1) ?></td>
						<td><?= $v['destination_name'] ?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?= $v['alt_text'] ?></td>
						<td><?php if($v['domain']==1){echo 'COM';}else if($v['domain']==2){ echo 'IN';}?></td>
						<td>
							<div class="d-flex gap-2">
								<?php 
								$status_label = (intval($v['top_destination']) == ACTIVE) ? '<span class="badge bg-success">ACTIVE</span>' : '<span class="badge bg-danger">INACTIVE</span>';
								echo $status_label;
								if (intval($v['top_destination']) == ACTIVE) {
									echo '<a role="button" href="'.base_url().'index.php/cms/deactivate_activity_top_destination/'.$v['origin'].'" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Deactivate</a>';
								} else {
									echo '<a role="button" href="'.base_url().'index.php/cms/activate_activity_top_destination/'.$v['origin'].'" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Activate</a>';
								}
								?>
							</div>
						</td>
					</tr>
				<?php
					endforeach;
				} else {
					?>
					<tr>
						<td colspan="6" class="text-center no-data-found">
							<div class="empty-state">
								<i class="bi bi-inbox" aria-hidden="true"></i>
								<h4>No Data Found</h4>
								<p>No activity top destinations found. Add your first destination using the form above.</p>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			</table>
        </div>
        </div>

    </div>
</div>
<script>
	$('#country').on('change', function() {
		var _country = this.value;
		if (_country != 'INVALIDIP') {
			//load city for country
			$.get(app_base_url+'index.php/ajax/get_city_list/'+_country, function(resp) {
				$('#city').html(resp.data);
			});
		}
	});
</script>

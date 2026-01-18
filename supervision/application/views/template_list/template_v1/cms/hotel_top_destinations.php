<?php
 //debug($country_list);exit;
?>
<div class="bodyContent col-md-12">
<h4 class="mb-3">Top Destinations In Hotel</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="card-body p-0">

            <form action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="POST" autocomplete="off" class="p-0">

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label>
                                Category <span class="text-danger">*</span>
                            </label>
                            <select id="country_cat" class="form-control" name="category_id" required="">
                                <option value="">Please Select</option>
                                <?php if(!empty($category_list)){?>
                                <?php foreach($category_list as $list){?>
                                    <option value="<?= $list['origin']?>"><?= $list['category_name']?></option>
                                <?php } } ?>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Country <span class="text-danger">*</span>
                            </label>
                            <select id="country" class="form-control" name="country_name" required="">
                                <option value="INVALIDIP">Please Select</option>
                                <?= generate_options($country_list) ?>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                City/Category <span class="text-danger">*</span>
                            </label>
                            <select id="city" class="form-control" name="city_name" required="">
                                <option value="INVALIDIP">Please Select</option>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Hotel Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" required="required" name="hotel_name">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Rating <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" required="required" name="rating">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Price <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" required="required" name="price">
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
<h4 class="mb-3">Hotel Top Destinations List</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive reprt_tble">

                <table class="table table-sm table-bordered example3" id="hotel_top_destinations_table">
                <thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sno</th>
					<th><i class="bi bi-globe"></i> Country</th>
					<th><i class="bi bi-geo-alt"></i> City</th>
					<th><i class="bi bi-tag"></i> Category</th>
					<th><i class="bi bi-building"></i> Hotel Name</th>
					<th><i class="bi bi-star"></i> Rating</th>
					<th><i class="bi bi-currency-dollar"></i> Price</th>
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

						$category = $this->custom_db->single_table_records ( 'top_destination_categories', '*', ['origin'=>$v['category_id']], 0, 100000);
						
				?>
					<tr>
						<td><?= ($k+1) ?></td>
						<td><?= $v['country_name'] ?></td>
						<td><?= $v['city_name'] ?></td>
						<td><?php if($category['status']==1){ echo $category['data'][0]['category_name']; }?></td>
						<td><?= $v['hotel_name'] ?></td>
						<td><?= $v['rating'] ?></td>
						<td><?= $v['price'] ?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?= $v['alt_text'] ?></td>
						<td><?php if($v['domain']==1){echo 'COM';}else if($v['domain']==2){ echo 'IN';}?></td>
						<td>
							<div class="d-flex gap-2">
								<?php 
								$status_label = (intval($v['status']) == ACTIVE) ? '<span class="badge bg-success">ACTIVE</span>' : '<span class="badge bg-danger">INACTIVE</span>';
								echo $status_label;
								if (intval($v['status']) == ACTIVE) {
									echo '<a role="button" href="'.base_url().'index.php/cms/deactivate_top_destination/'.$v['origin'].'" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Deactivate</a>';
								} else {
									echo '<a role="button" href="'.base_url().'index.php/cms/activate_top_destination/'.$v['origin'].'" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Activate</a>';
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
						<td colspan="11" class="text-center no-data-found">
							<div class="empty-state">
								<i class="bi bi-inbox" aria-hidden="true"></i>
								<h4>No Data Found</h4>
								<p>No hotel top destinations found. Add your first destination using the form above.</p>
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
<?php 

?>

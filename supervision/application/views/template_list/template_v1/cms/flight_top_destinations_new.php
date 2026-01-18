<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel <?= PANEL_WRAPPER ?>"><!-- PANEL WRAP START -->
		<div class="card-header"><!-- PANEL HEAD START -->

			<div class="card-title">
				<i class="fa fa-edit"></i> Top Destinations In Flight
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="card-body"><!-- PANEL BODY START -->
			<fieldset>
				<legend><i class="fa fa-plane"></i> Airport List</legend>
				<span style="color:#dd4b39"><?php if (isset($message)) {
												echo $message;
											} ?></span>
				<form action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" class="row" method="POST" autocomplete="off">
				<div class="form-group">
						<label form="user" for="title" class="col-sm-3 form-label">Category<span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<select id="country" class="form-control" name="category_id" required="">
								<option value="">Please Select</option>
								<?php if(!empty($category_list)){?>
								<?php foreach($category_list as $list){?>
									<option value="<?= $list['origin']?>"><?= $list['category_name']?></option>
									<?php } } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 form-label">From Airport : <span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<select name="from_airport" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?php
								// debug($flight_list2);exit;
								foreach ($flight_list2['data'] as $flight) {
									// $flight['origin'] will be used as the value in the dropdown
									// $flight['airport_city'] and $flight['airport_code'] will be used as the text to display
									echo '<option value="' . htmlspecialchars($flight['origin']) . '">'
										. htmlspecialchars($flight['airport_city']) . ' (' . htmlspecialchars($flight['airport_code']) . ')'
										. '</option>';
								}
								?>
							</select>

						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 form-label">To Airport : <span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<select name="to_airport" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?php
								// debug($flight_list2);exit;
								foreach ($flight_list2['data'] as $flight) {
									// $flight['origin'] will be used as the value in the dropdown
									// $flight['airport_city'] and $flight['airport_code'] will be used as the text to display
									echo '<option value="' . htmlspecialchars($flight['origin']) . '">'
										. htmlspecialchars($flight['airport_city']) . ' (' . htmlspecialchars($flight['airport_code']) . ')'
										. '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 form-label">Airlines : <span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<select name="airlines_origin" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?= generate_options($airlines_list) ?>
							</select>
						</div>
					</div>
					<div class="form-group" style="display: none;">
						<label form="user" for="title" class="col-sm-3 form-label">Starting Price : <span class="text-danger">*</span></label>
						<div class="col-sm-5">
							<input type="text" name="starting_price" class="form-control" value="0">
						</div>
					</div>
					<div class="form-group" style="display: none;">
						<label form="user" for="title" class="col-sm-3 form-label">Image : <span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<input type="file" class="" accept="image/*" name="top_destination">
						</div>
					</div>
					<div class="form-group">
						<label for="seoTitle" class="col-sm-3 form-label">SEO Title</label>
						<div class="col-sm-5">
							<input type="text" name="seo_title" id="seoTitle" class="form-control" placeholder="SEO Title">
						</div>
					</div>
					<div class="form-group">
						<label for="seoKeywords" class="col-sm-3 form-label">SEO Keywords</label>
						<div class="col-sm-5">
							<input type="text" name="seo_keywords" id="seoKeywords" class="form-control" placeholder="SEO Keywords" autocorrect="off" autocomplete="off" autocapitalize="off">
						</div>
					</div>
					<div class="form-group">
						<label for="seoDescription" class="col-sm-3 form-label">SEO Description</label>
						<div class="col-sm-5">
							<textarea name="seo_description" id="seoDescription" class="form-control" placeholder="SEO Description" rows="4"></textarea>
						</div>
					</div>

					<div class="card card-body card card-body p-2">
						<div class="clearfix offset-md-8">
							<button class=" btn btn-sm btn-success " type="submit">Add</button>
						</div>
					</div>
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
		<div class="card-body">

		<div class="">
                <?php echo $this->pagination->create_links(); ?> <span class="" style="vertical-align:top;">Total <?php echo $total_rows ?> Records</span>
            </div>
          
		  <div class="card-body">
			<table id="blogTable" class="table table-bordered">
				<tr>
					<th>Sno</th>
					<th>Category</th>
					<th>From City</th>
					<th>To City</th>
					<th>Airline</th>
					<!-- <th>Starting Price</th>
					<th>Image</th> -->
					<th>Status</th>
					<th>Action</th>
				</tr>
				<?php
				//debug($data_list);exit;
				$offset++;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
						$category = $this->custom_db->single_table_records ( 'top_destination_categories', '*', ['origin'=>$v['category_id']], 0, 100000);
		
				?>
						<tr>
							<td><?= ($offset++) ?></td>
							<td><?php if($category['status']==1){ echo $category['data'][0]['category_name']; }?></td>
							<td><?= $v['from_airport_name'] ?></td>
							<td><?= $v['to_airport_name'] ?></td>
							<td><?= $v['airlines_name'] ?></td>
							<!-- <td><?= $v['starting_price'] ?></td>
							<td><img src="<?php echo $GLOBALS['CI']->template->domain_images($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
							 -->
							<td><?php echo get_status_label($v['status']) . get_status_toggle_button($v['status'], $v['origin']) ?></td>
							<td><a href="<?php echo base_url(); ?>cms/delete_flight_top_destination/<?php echo $v['origin']; ?>/<?php echo $v['image']; ?>" data-original-title="Delete" onclick="return confirm('Do you want delete this record');" class="btn btn-danger btn-xs has-tooltip" data-original-title="Delete">
									<i class="icon-remove">Delete</i></a></td>
						</tr>
				<?php
					endforeach;
				} else {
					echo '<tr><td>No Data Found</td></tr>';
				}
				?>
			</table>
			<div class="pagination-container"> <?php //echo $pagination; ?> </div>
		</div>
		
		</div>
	</div><!-- PANEL WRAP END -->
</div>
<?php
function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="badge bg-success"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', ACTIVE) . '</span>
	<a role="button" href="" class="hide">' . get_app_message('AL0021') . '</a>';
	} else {
		return '<span class="bg-red-active"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', INACTIVE) . '</span>
		<a role="button" href="" class="hide">Deactivate</a>';
	}
}

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="' . base_url() . 'index.php/cms/deactivate_flight_top_destination/' . $origin . '" class="text-danger">Deactivate</a>';
	} else {
		return '<a role="button" href="' . base_url() . 'index.php/cms/activate_flight_top_destination/' . $origin . '" class="text-success">Activate</a>';
	}
}

?>
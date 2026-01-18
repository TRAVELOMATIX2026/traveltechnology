<div class="bodyContent col-md-12">
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0">Banners</h4>
<a href="<?= base_url().'index.php/user/add_banner' ?>">
    <button class="btn btn-gradient-primary"><i class="bi bi-plus-circle"></i> Add Banner</button>
</a>
</div>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive mb-0">

                <table class="table table-sm table-bordered example3" id="banner_images_table">
                <thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sl no</th>
					<th><i class="bi bi-type"></i> Title</th>
					<th><i class="bi bi-text-paragraph"></i> Description</th>
					<th><i class="bi bi-image"></i> Image</th>
					<th><i class="bi bi-sort-numeric-down"></i> Order</th>
					<th><i class="bi bi-toggle-on"></i> Status</th>
					<th><i class="bi bi-gear"></i> Action</th>
				</tr>
                </thead>
                <tbody>
				<?php
				// debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
						$action = '<a role="button" href="'.base_url().'index.php/user/edit_banner/'.$v['origin'].'" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Edit</a>';
						if (intval($v['status']) == 1) {
							$status_label = '<span class="badge bg-success">ACTIVE</span>';
						} else {
							$status_label = '<span class="badge bg-danger">INACTIVE</span>';
						} 
						$status_button = '<a role="button" href="'.base_url().'index.php/user/banner_delete/'.$v['origin'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this banner?\');"><i class="bi bi-trash"></i> Delete</a>'; 
				?>
					<tr>
						<td><?= ($k+1) ?></td>
						<td><?= $v['title'] ?></td>
						<td><?= $v['subtitle'] ?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_ban_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?= $v['banner_order'] ?></td>
						<td><?= $status_label ?></td>
						<td>
							<div class="d-flex gap-2">
								<?= $action ?>
								<?= $status_button ?>
							</div>
						</td>
					</tr>
				<?php
					endforeach;
				} else {
					?>
					<tr>
						<td colspan="7" class="text-center no-data-found">
							<div class="empty-state">
								<i class="bi bi-inbox" aria-hidden="true"></i>
								<h4>No Data Found</h4>
								<p>No banner records found. Click "Add Banner" to create your first banner.</p>
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
<?php 
function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="badge bg-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '';
	}
}

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_flight_top_destination/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '';		
	}
}

?>

<div class="bodyContent col-md-12">
<div class="d-flex justify-content-between align-items-center mb-3">
<h4 class="mb-0">Headings</h4>
<a href="<?= base_url().'index.php/cms/add_why_choose_us' ?>">
    <button class="btn btn-gradient-primary"><i class="bi bi-plus-circle"></i> Add Feature</button>
</a>
</div>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive reprt_tble">

                <table class="table table-sm table-bordered example3" id="why_choose_us_table">
                <thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sl no</th>
					<th><i class="bi bi-type"></i> Title</th>
					<th><i class="bi bi-text-paragraph"></i> Description</th>
					<th><i class="bi bi-image"></i> Icon</th>
					<th><i class="bi bi-toggle-on"></i> Status</th>
					<th><i class="bi bi-gear"></i> Action</th>
				</tr>
                </thead>
                <tbody>
				<?php
				// debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
					
				?>
					<tr>
						<td><?= ($k+1) ?></td>
						<td><?= $v['title'] ?></td>
						<td><?= $v['description'] ?></td>
						<td><?= $v['icon'] ?></td>
						<td>
							<?php 
							$status_options = get_enum_list('status');
							echo '<select class="toggle-user-status form-control form-control-sm" data-origin="'.$v['origin'].'">'.generate_options($status_options, array($v['status'])).'</select>';
							?>
						</td>
						<td>
							<div class="d-flex gap-2">
								<?= get_edit_button($v['origin']) ?>
								<a href="<?php echo base_url(); ?>index.php/cms/delete_why_choose/<?php echo $v['origin']; ?>" onclick="return confirm('Do you want delete this record');" class="btn btn-sm btn-danger">
									<i class="bi bi-trash"></i> Delete
								</a>
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
								<p>No headings found. Click "Add Feature" to create your first heading.</p>
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
function get_edit_button($origin)
{
	return '<a role="button" href="'.base_url().'index.php/cms/add_why_choose_us?'.$_SERVER['QUERY_STRING'].'&origin='.$origin.'" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Edit</a>';
}
?>
<script>
$(document).ready(function() {
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/cms/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_why_choose/';
		} else {
			_opp_url = _opp_url+'deactivate_why_choose/';
		}
		_opp_url = _opp_url+$(this).data('origin');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});
});
</script>

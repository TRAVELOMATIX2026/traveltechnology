<div class="bodyContent col-md-12">
<h4 class="mb-3">Manage Social Links</h4>
	<div class="panel card clearfix soc_ntwk">
		<div class="p-0">
			<div class="clearfix table-responsive reprt_tble">
				<table class="table table-sm table-bordered example3">
					<thead>
					<tr>
						<th><i class="bi bi-share"></i> Social</th>
						<th><i class="bi bi-link-45deg"></i> URL</th>
						<th><i class="bi bi-gear"></i> Action</th>
					</tr>
					</thead>
					<tbody>
			<?php
			// debug($social_links);exit;
		if (valid_array($social_links)) {
			foreach($social_links as $key => $value) {
				$update = '<input class="updateButton btn btn-primary btn-sm" type="submit" name="submit" value="Update">';
				echo '<tr>
				<td>'.$value['social'].'</td>
				<td>
					<form action='.base_url().'index.php/utilities/edit_social_url id="update_social_login" method="POST">
				<table class="table table-striped">
				<tr>
					<td><input type="text" autocomplete="off" name="social_url" id="social_url" class="form-control" value="'.$value['url_link'].'" /></td>
					<td>'.$update.'</td>
				</tr>
				</table>
				<input type="hidden" name="origin" id="origin" value="'.$value['origin'].'" /> 
 					</form>
				
				</td>
				<td class="d-flex gap-2 align-items-center flex-wrap">'.get_status_label($value['status']).' '.get_status_toggle_button($value['status'], $value['origin']).'</td>
			</tr>';
			}
		} else {
			echo '<tr><td colspan="3" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Data Found</h4><p>No social links configured.</p></div></td></tr>';
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
		return '<span class="badge bg-success"><i class="far fa-circle"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="badge bg-danger"><i class="far fa-circle"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
	}
}

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/utilities/activate_social_link/'.$origin.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'index.php/utilities/deactivate_social_link/'.$origin.'" class="text-danger">Deactivate</a>';
	}
}

?>
<script>
$(document).ready(function() {
	$(document).on('click', '.location-map', function() {
		$('#map-box-modal').modal();
	});
	$("#update_social_login").submit(function(event) {
		var thisRef = this;
      /* stop form from submitting normally */
      event.preventDefault();

      /* get the action attribute from the <form action=""> element */
      var $form = $( this ),
          url = $form.attr( 'action' );

      /* Send the data using post with element id name and name2*/
      var posting = $.post( url, { origin: $('#origin').val(), social_url:$('#social_url').val()} );

      /* Alerts the results */
      posting.done(function( data ) {

			toastr.success('Data Updated');
      });
});

});
</script>
<div class="modal fade bs-example-modal-lg" id="map-box-modal"
	tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Location Map</h4>
			</div>
			<div class="modal-body">
				<iframe src="" id="map-box-frame" name="map_box_frame"
					style="height: 500px; width: 850px;"> </iframe>
			</div>
		</div>
	</div>
</div>
</div>
<div class="bodyContent col-md-12">
<h4 class="mb-3">Exception Event Logs</h4>
	<div class="clearfix">
		<div class="p-0">
			<div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
				<div>
					<?php echo $this->pagination->create_links();?> <span class="totl_bkngs_flt">Total <?php echo $total_rows ?> Records</span>
				</div>
			</div>
			<div class="clearfix table-responsive reprt_tble">
			<table class="table table-sm table-bordered example3">
				<thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sno</th>
					<th><i class="bi bi-upc"></i> Reference ID</th>
					<th><i class="bi bi-gear"></i> Operation</th>
					<th><i class="bi bi-chat-text"></i> Message</th>
					<th><i class="bi bi-person"></i> User Info</th>
					<th><i class="bi bi-browser-chrome"></i> Browser</th>
					<th><i class="bi bi-hdd-network"></i> IP</th>
					<th><i class="bi bi-calendar3"></i> Occured On</th>
				</tr>
				</thead>
				<tbody>
			<?php
			if (valid_array($table_data)) {
				foreach ($table_data as $k => $v) {
					$data = $v['data'] ?? '';
					$client_info = @unserialize($data);
					if ($client_info === false && $data !== 'b:0;') {
						// corrupted
						$client_info =  [];
					}
					// $client_info = unserialize($v['client_info']);
					$user_info = '';
					$user_info .= '<ul class="list-group">';
					$user_info .= '<li class="list-group-item">IP:'.@$client_info['query'].'</li>';
					$user_info .= '<li class="list-group-item">Country:'.@$client_info['country'].'</li>';
					$user_info .= '<li class="list-group-item">Time-Zone:'.@$client_info['timezone'].'</li>';
					if (empty($client_info['lat']) == false and empty($client_info['lon']) == false) {
						$user_info .= '<li class="list-group-item"><a target="map_box_frame" href="'.base_url().'index.php/general/event_location_map?latitude='.$client_info['lat'].'&longtitude='.$client_info['lon'].'&ip='.$client_info['query'].'" class="location-map"><i class="fa fa-globe"></i>Click to view Location</li>';
					}
					$user_info .= '</ul>';
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['exception_id']?></td>
						<td><?=$v['op']?></td>
						<td><?=$v['notification']?></td>
						<td><?=$user_info?></td>
						<td><?=$v['user_agent']?></td>
						<td><?=$v['user_ip']?></td>
						<td><?=app_friendly_date($v['created_datetime'])?></td>
					</tr>
				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="8" class="text-center no-data-found">
						<div class="empty-state">
							<i class="bi bi-inbox" aria-hidden="true"></i>
							<h4>No Data Found</h4>
							<p>No exception event logs found.</p>
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
$(document).ready(function() {
	$(document).on('click', '.location-map', function() {
		$('#map-box-modal').modal();
	});
});
</script>
<div class="modal fade bs-example-modal-lg" id="map-box-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Location Map</h4>
			</div>
			<div class="modal-body">
				<iframe src="" id="map-box-frame" name="map_box_frame" style="height: 500px;width: 850px;">
				</iframe>
				</div>
			</div>
		</div>
	</div>
</div>
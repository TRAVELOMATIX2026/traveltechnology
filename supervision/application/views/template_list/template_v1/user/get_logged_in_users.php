<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent col-md-12">
	<h4 class="mb-3"><?php echo get_app_message('AL00312');?></h4>
	<div class="panel card clearfix"><!-- PANEL WRAP START -->
		<div class="p-0"><!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="clearfix tab-pane active" id="tableList">
					<div class="panel card mb-4">
						<div class="card-header border-bottom bg-transparent"><?php echo get_utility_message('UL0095');?> <strong>(<?php echo $online_total_users;?>)</strong></div>
						<div class="card-body p-0">
							<?php
							/************************ GET ONLINE USERS ************************/
							echo get_table($online_users);
							?>
						</div>
					</div>
					<div class="panel card">
						<div class="card-header border-bottom bg-transparent"><?php echo get_utility_message('UL0096');?> <?php echo date('d-m-Y');?> <strong>(<?php echo $logged_total_users;?>)</strong></div>
						<div class="card-body p-0">
							<?php
							/************************ GET LOGGED USERS ************************/
							echo get_table($logged_users, true);
							?>
						</div>
					</div>
				</div>
			</div>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
</div>
<!-- HTML END -->
<?php 
function get_table($table_data='', $log_out = false)
{
	$colspan = $log_out ? 8 : 7;
	$table = '<div class="clearfix table-responsive reprt_tble"><table class="table table-sm table-bordered example3">';
	$table .= '<thead><tr>
   <th><i class="bi bi-hash"></i> '.get_app_message('AL006').'</th>
   <th><i class="bi bi-image"></i> '.get_app_message('AL0011').'</th>
   <th><i class="bi bi-person"></i> '.get_app_message('AL007').'</th>
   <th><i class="bi bi-telephone"></i> '.get_app_message('AL008').'</th>
   <th><i class="bi bi-people"></i> '.get_app_message('AL009').'</th>
   <th><i class="bi bi-globe"></i> '.get_app_message('AL00308').'</th>';
	if($log_out) {
		$table .= '<th><i class="bi bi-box-arrow-in-right"></i> '.get_app_message('AL00310').'</th>
			<th><i class="bi bi-box-arrow-right"></i> '.get_app_message('AL00311').'</th>';
	} else {
		$table .= '<th><i class="bi bi-clock-history"></i> '.get_app_message('AL00309').'</th>';
	}
	$table .= '</tr></thead><tbody>';
	
	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td class="lgdin_usr_img">'.get_profile_icon($v['image']).'</td>
			<td>'.get_enum_list('title', $v['title']).' '.$v['first_name'].' '.$v['last_name'].'</td>
			<td>'.$v['phone'].'-'.provab_decrypt($v['email']).'</td>
			<td>'.$v['user_type'].'</td>
			<td>'.$v['login_ip'].'</td>';
			if($log_out) {
				$table .= '<td>'.app_friendly_datetime($v['login_time']).'</td>
					<td>'.app_friendly_datetime($v['logout_time']).'</td>';
			} else {
				$table .= '<td>'.app_friendly_datetime($v['login_time']).'</td>';
			}
			$table .= '</tr>';
		}
	} else {
		$table .= '<tr><td colspan="'.$colspan.'" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Users</h4><p>'.get_app_message('AL00313').'</p></div></td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}
?>

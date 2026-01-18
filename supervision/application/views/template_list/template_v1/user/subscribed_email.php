<?php 

if($domain_admin_exists == true ) {

    $domain_admin_exists = true;

} else {

    $domain_admin_exists = false;

}

?>

<div id="general_user" class="bodyContent col-md-12">
<h4 class="mb-3">Email Subscriptions</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="card-body p-0">

            <div id="show-search">

                <?php
                /************************ GENERATE CURRENT PAGE TABLE ************************/
                echo get_table(@$subscriber_list);
                /************************ GENERATE CURRENT PAGE TABLE ************************/
                ?>

            </div>
        </div>

    </div>
</div>

<div class="d-flex justify-content-end align-items-center mb-3">
    <div class="dropdown">
        <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="bi bi-file-earmark-excel" aria-hidden="true"></i> Excel
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
            <li>
                <a href="<?php echo base_url(); ?>index.php/general/export_subscribed_emails_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Subscribed Email Report</a>
            </li>
        </ul>
    </div>
</div>
<?php 
function get_table($subscriber_list='')
{
	
	if(is_array($_GET) && !empty($_GET)){
	$email = $_GET['email'];	
	$url = base_url().'index.php/general/view_subscribed_emails';
	$url_data = '<div class="mb-3"><a href="'.$url.'" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Back</a></div>';
	}
	else{
		$url_data ='';
	}
	
	
	$table = $url_data.'
   <form method="GET" role="search" class="mb-3">
      <div class="form-group row gap-0 mb-0">
         <div class="col-sm-6 col-md-4">
            <input type="text" placeholder="'.get_app_message('AL004').'" class="form-control" name="email" value="'.@$_GET['email'].'">
         </div>
         <div class="col-sm-6 col-md-2">
            <button type="submit" class="btn btn-gradient-primary"><i class="bi bi-search"></i> Search</button>
         </div>
      </div>
   </form>';
   
	$table .= '<div class="clearfix table-responsive reprt_tble">
   <table class="table table-sm table-bordered example3" id="subscribed_emails_table">';
      $table .= '<thead><tr>
   <th><i class="bi bi-hash"></i> '.get_app_message('AL006').'</th>
   <th><i class="bi bi-envelope"></i> '.get_app_message('AL00315').'</th>
   <th><i class="bi bi-toggle-on"></i> '.get_app_message('AL0019').'</th>
   <th><i class="bi bi-trash"></i> '.get_app_message('AL0012').'</th>
   </tr></thead><tbody>';
	
	if (valid_array($subscriber_list) == true) {
		//$segment_3 = $GLOBALS['CI']->uri->segment(3);
		//$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($subscriber_list as $k => $v) {			
			$table .= '<tr>
			<td>'.($k+1).'</td>			
			<td>'.$v->email_id.'</td>
			<td>'.get_status_toggle_button($v->status, $v->id).'</td>
			<td>'.delete_subscription_mail($v->id).'</td>
</tr>';
		}
	} else {
		$table .= '<tr>
			<td colspan="4" class="text-center no-data-found">
				<div class="empty-state">
					<i class="bi bi-inbox" aria-hidden="true"></i>
					<h4>No Data Found</h4>
					<p>No subscribed emails match your search criteria. Please try adjusting your filters.</p>
				</div>
			</td>
		</tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}

function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="badge bg-success"><i class="fa fa-hand-o-right"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="badge bg-danger"><i class="fa fa-hand-o-right"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
	}
}

function get_edit_button($id)
{
		return '<a role="button" href="'.base_url().'index.php/user/user_management?'.$_SERVER['QUERY_STRING'].'&	eid='.$id.'" class="btn btn-secondary btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
		/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}

function delete_subscription_mail($id)
{
		return '<a role="button" href="'.base_url().'index.php/general/email_delete/'.$id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this email subscription?\');"><i class="bi bi-trash"></i> Delete</a>';
}
function get_status_toggle_button($status, $id)
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-user-id="'.$id.'">'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}
?>
<script>
$(document).ready(function() {
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/general/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'active_emails/';
		} else {
			_opp_url = _opp_url+'deactive_emails/';
		}
		_opp_url = _opp_url+$(this).data('user-id');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});
});
</script>
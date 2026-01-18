<div class="bodyContent col-md-12">
<h4 class="mb-3">Country Access</h4>
<div class="panel card clearfix crncy_cnvrt">
	<div class="p-0">
		<div class="clearfix table-responsive reprt_tble">
		<table class="table table-sm table-bordered example3">
			<thead>
			<tr>
				<th><i class="bi bi-hash"></i> SNO</th>
				<th><i class="bi bi-currency-exchange"></i> CURRENCY</th>
				<th><i class="bi bi-globe"></i> COUNTRY NAME</th>
				<th><i class="bi bi-toggle-on"></i> STATUS</th>
			</tr>
			</thead>
			<tbody>
		<?php
		if (valid_array($converter)) {
			$status_options = get_enum_list('status');
			foreach($converter as $key => $value) {
				echo '<tr>
				<td>'.($key+1).'</td>
				<td><label for="'.$value['origin'].'">'.$value['country'].'</label></td>
				<td>'.$value['country_name'].'</td>
				<td><select autocomplete="off" class="currency-status-toggle form-control form-control-sm" id="'.$value['origin'].'">'.generate_options($status_options, array(intval($value['access']))).'</select></td>
			</tr>';
			}
		} else {
			echo '<tr><td colspan="4" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Data Found</h4><p>No country access records found.</p></div></td></tr>';
		}
		?>
			</tbody>
		</table>
		</div>
	</div>
</div>
</div>

<script>
$(document).ready (function () {
	$('.updateButton').on('click', function () {
		var thisRef = this;
		$.post(app_base_url+'index.php/utilities/country_access/'+parseFloat($(this).closest('td').siblings().children('[name="value"]').val())+'/'+$(this).closest('td').siblings().children('[name="value"]').attr('id'), function (response) {
			$(thisRef).removeClass('btn-warning');
			toastr.success('Data Updated');
		});
	});

	$('.currency-status-toggle').on('change', function () {
		var thisRef = this;
		$.get(app_base_url+'index.php/utilities/country_access_status_toggle/'+parseInt($(this).attr('id'))+'/'+$(this).val(), function (response) {
			toastr.success('Data Updated');
		});
	});
	
	$('[name="value"]').on('change, keyup', function() {
		$(this).closest('td').siblings().children('.updateButton').addClass('btn-warning');
	});

	$('#roe-auto-update').on('click', function() {
		$(this).text('Please Wait!!!! This Might Take Few Minutes!!!!!!!!!!!!!');
		setTimeout(function() {
			$('body').css('opacity', '.1');
		}, 2000);
		
	});
});
</script>

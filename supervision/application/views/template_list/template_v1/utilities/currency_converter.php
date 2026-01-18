<div class="bodyContent col-md-12">
<h4 class="mb-3">Currency Converter</h4>
<div class="panel card clearfix crncy_cnvrt">
	<div class="p-0">
		<div class="clearfix table-responsive reprt_tble">
		<table class="table table-sm table-bordered example3">
			<thead>
			<tr>
				<th><i class="bi bi-hash"></i> SNO</th>
				<th><i class="bi bi-currency-exchange"></i> CURRENCY</th>
				<th><i class="bi bi-toggle-on"></i> STATUS</th>
				<th>ROE With <?=COURSE_LIST_DEFAULT_CURRENCY_VALUE?> as Base</th>
				<th><i class="bi bi-gear"></i> ACTION</th>
			</tr>
			</thead>
			<tbody>
		<?php
		if (valid_array($converter)) {
			$status_options = get_enum_list('status');
			foreach($converter as $key => $value) {
				$update = '';
				if ($value['id'] != COURSE_LIST_DEFAULT_CURRENCY) {
					$update = '<button class="updateButton btn btn-info btn-sm"><i class="bi bi-arrow-repeat"></i> Update</button>';
				} else {
					$update = '<abbr title="Please Contact Admin To Change This">Default Currency</abbr>';
				}
				echo '<tr>
				<td>'.($key+1).'</td>
				<td><label for="'.$value['id'].'">'.$value['country'].'</label></td>
				<td><select autocomplete="off" class="currency-status-toggle form-control form-control-sm" id="'.$value['id'].'">'.generate_options($status_options, array(intval($value['status']))).'</select></td>
				<td><input type="text" autocomplete="off" name="value" id="'.$value['id'].'" disabled class="form-control form-control-sm" value="'.$value['value'].'" /></td>
				<td>'.$update.'</td>
			</tr>';
			}
		} else {
			echo '<tr><td colspan="5" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Data Found</h4><p>No currency records found.</p></div></td></tr>';
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
		$.post(app_base_url+'index.php/utilities/currency_converter/'+parseFloat($(this).closest('td').siblings().children('[name="value"]').val())+'/'+$(this).closest('td').siblings().children('[name="value"]').attr('id'), function (response) {
			$(thisRef).removeClass('btn-warning');
			toastr.success('Data Updated');
		});
	});

	$('.currency-status-toggle').on('change', function () {
		var thisRef = this;
		$.get(app_base_url+'index.php/utilities/currency_status_toggle/'+parseInt($(this).attr('id'))+'/'+$(this).val(), function (response) {
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

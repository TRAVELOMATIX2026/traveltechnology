<div class="bodyContent col-md-12">
<h4 class="mb-3">Convenience Fees</h4>
	<div class="panel card clearfix"><!-- PANEL WRAP START -->
		<div class="p-0">
			<div class="clearfix table-responsive reprt_tble" id="checkbox_div">
			<form action="" method="POST" autocomplete="off">
				<table class="table table-sm table-bordered example3">
					<thead>
					<tr>
						<th><i class="bi bi-hash"></i> Sl.No</th>
						<th><i class="bi bi-grid"></i> Module</th>
						<th><i class="bi bi-percent"></i> Fees Type</th>
						<th><i class="bi bi-currency-dollar"></i> Fees</th>
						<th><i class="bi bi-person"></i> Added Per Pax</th>
					</tr>
					</thead>
					<tbody>
				<?php
				// debug($convenience_fees);exit;
				if (isset($convenience_fees) and valid_array($convenience_fees)) {

					foreach($convenience_fees as $key => $raw_row) {

						if($raw_row['module']=='SIGHTSEEING'){
							
							$raw_row['module'] ='ACTIVITIES';
						}
						

						$table_row = get_table_row($raw_row);
						$sno = ($key+1);
						extract($table_row);

					?>
					<tr>
						<td><?=($sno).($row_origin)?></td>
						<td><?=$module?></td>
						<td><?=$fees_type?></td>
						<td><?=$fees?></td>
						<?php if($raw_row['module'] != 'TOPUP'){ ?>
						<?php if($raw_row['value_type'] =='plus'){?>
							<td class="perpax<?php echo $raw_row['origin'];?>"><?=$per_pax?></td>
						<?php } else {?>
							<td class="perpax<?php echo $raw_row['origin'];?> hide"><?=$per_pax?></td>
						<?php } ?>
						<?php }else{ ?>
							<td></td>
						<?php } ?>
					</tr>
					<?php
					}//End Of Data Print
					?>
					<tr>
					<td colspan="5" class="bg-light">
						<div class="d-flex gap-2 py-2">
							<button type="submit" class="btn btn-gradient-primary btn-sm"><i class="bi bi-check-circle"></i> Update Convenience Fees</button>
							<button type="reset" class="btn btn-gradient-warning btn-sm"><i class="bi bi-arrow-clockwise"></i> Reset</button>
						</div>
					</td>
					</tr>
				<?php
				} else {
					echo '<tr><td colspan="5" class="text-center no-data-found"><div class="empty-state"><i class="bi bi-inbox" aria-hidden="true"></i><h4>No Data Found</h4><p>No convenience fees configured.</p></div></td></tr>';
				}
				?>
				</tbody>
				</table>
			</form>
			</div>
		</div>
	</div>
</div>

<!-- Page Ends Here -->
<?php
function get_table_row($raw_row)
{
	$data['module'] = '<strong>'.$raw_row['fees'].'</strong> '.$raw_row['module'];
	$data['row_origin'] = '<input type="hidden" value="'.$raw_row['origin'].'" name="origin[]">';
	$data['fees'] = '<input type="text" class="numeric" name="value[]" value="'.($raw_row['value']).'" maxlength="4">';
	if (empty($raw_row['per_pax']) == true) {
		$per_pax_no = 'checked="checked"';
		$per_pax_yes = '';
	} else {
		$per_pax_no = '';
		$per_pax_yes = 'checked="checked"';
	}
	$data['per_pax'] = '';
	$data['per_pax'] .= '<label><input type="radio" name="per_pax_'.$raw_row['origin'].'" '.$per_pax_yes.' value="1" > Yes</label>';
	$data['per_pax'] .= '<label><input type="radio" name="per_pax_'.$raw_row['origin'].'" '.$per_pax_no.' value="0" > No</label>';
	
	$data['fees_type'] = '';
	if ($raw_row['value_type'] == 'plus') {
		$perc = '';
		$plus = 'checked="checked"';
	} else {
		$perc = 'checked="checked"';
		$plus = '';
	}

	if($raw_row['module'] != 'TOPUP'){
		$data['fees_type'] .= '<label class="form-label"><input type="radio" '.$perc.' class="'.$raw_row['origin'].'" name="value_type_'.$raw_row['origin'].'" value="percentage"> perc% </label>';
	}
	$data['fees_type'] .= '<label><input type="radio" '.$plus.' class="'.$raw_row['origin'].'" name="value_type_'.$raw_row['origin'].'" value="plus"> plus+ </label>';
	return $data;
}
?>
<script type="text/javascript">
$(document).ready(function() {
	 $("#checkbox_div input:radio").click(function() {
		var value = $(this).val();
		var id_value = $(this).attr('class');
			if(value == 'percentage'){
				
				$('.perpax'+id_value).addClass('hide');
			}
			else{
				
				$('.perpax'+id_value).removeClass('hide');
			}
		});
	
	
});
</script>
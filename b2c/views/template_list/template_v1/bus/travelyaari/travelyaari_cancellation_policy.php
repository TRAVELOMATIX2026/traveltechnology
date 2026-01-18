<div class="panel card border-danger">
	<div class="card-header">
		<div class="card-title">
			Cancellation Policy
		</div>
	</div>
	<div class="card-body">
		<table class="table table-sm table-bordered table-striped">
			<tr>
				<th>Cancellation Time</th>
				<th>Cancellation Charges</th>
			</tr>
		<?php
		if (valid_array($CUR_CancellationCharges) == true) {
			foreach ($CUR_CancellationCharges as $__ck => $__cv) { ?>
				<tr>
					<td><?=$__cv['MinsBeforeDeparture']?> mins Before Departure Time</td>
					<td><?=(empty($__cv['ChargeFixed']) == false ? $__cv['ChargeFixed'] : $__cv['ChargePercentage'].'%')?></td>
				</tr>
			<?php
			}
		} else {
		?>
			<tr>
				<td colspan="2">Not Available</td>
			</tr>
		<?php
		}
		?>
		</table>
	</div>
</div>
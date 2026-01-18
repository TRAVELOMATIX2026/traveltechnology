<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-sm table-bordered" id="b2c_report_car_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>App Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>lead_pax_name</th>
			<th>lead_pax_email</th>
			<th>lead_pax_phone_number</th>
			<th>Car Name</th>
			<th>Supplier Identifier</th>
			<th>From</th>
			<th>To</th>
			<th>Pickup From Date</th>
			<th>Pickup To Date</th>
			<th>Pickup Time</th>
			<th>Drop Time</th>
			<th>Agency Markup</th>	
			<th>Admin Markup</th>
			<th>Fare</th>
			<th>grand_total</th>
			<?php
				if($record_type=="all"){

			?>
			<th>Status</th>
			<?php
				}
			?>
			<th>booked_on</th>
		</tr>
		</thead>
		<tbody>
			<?php

				// debug($export_data);exit;
				if(!empty($export_data))
				{
					$j=1;

					foreach($export_data as $key => $v) {
						// debug($v);
					
					?>
					<tr>
							 <td><?=$j;?></td>
							<td><?=$v['app_reference']?></td>
							<td><?=$v['booking_reference']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['car_name']?></td>
							<td><?=$v['supplier_identifier']?></td>
							<td><?=$v['car_pickup_lcation']?></td>
							<td><?=$v['car_drop_location']?></td>
							<td><?=$v['car_from_date']?></td>
							<td><?=$v['car_to_date']?></td>
							<td><?=$v['pickup_time']?></td>
							<td><?=$v['drop_time']?></td>
							<td><?=$v['agent_markup']?></td>
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['total_fare']?></td>
							<td><?=$v['grand_total']?></td>
							<?php
								if($record_type=="all"){

							?>
							<td><?=$v['status']?></td>
							<?php
								}
							?>
							<td><?=date('d-m-Y', strtotime($v['voucher_date']))?></td>
					</tr>
					<?php
					$j++;
						}
					}
					?>

		</tbody></table>
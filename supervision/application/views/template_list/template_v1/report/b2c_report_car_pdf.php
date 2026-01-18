<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-sm table-bordered" id="b2c_report_car_table">
		<thead>
		<tr>
			<th>Sl. No.</th>
			<th>Appreference</th>
			<th>Lead Pax Name</th>
			<th>lead_pax_email</th>
			<th>lead_pax_phone_number</th>
			<th>Confirm Number</th>
			<th>Car Name</th>
			<th>Supplier Name</th>
			<th>Supplier Identifier</th>
			<th>From</th>
			<th>To</th>
			<th>Pickup DateTime</th>
			<th>Drop DateTime</th>
			<th>BookedOn</th>	
			<th>Admin NetFare</th>
			<th>Admin Markup</th>
			<th>Convn Fee</th>
			<th>Discount</th>
			<th>Grand Total</th>
			<th>Status</th>
		</tr>
		</thead>
		<tbody>
			<?php

				// debug($export_data);exit;
				if(!empty($export_data))
				{
					$i=1;

					foreach($export_data as $key => $v) {
						// debug($v);
					
					?>
					<tr>
							 <td><?=$i?></td>
							<td><?=$v['app_reference']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['booking_reference']?></td>
							<td><?=$v['car_name'] ?></td>
							<td><?=$v['car_supplier_name']?></td>
							<td><?=$v['supplier_identifier']?></td>
							<td><?=$v['car_pickup_lcation']?></td>
							<td><?=$v['car_drop_location']?></td>
							<td><?=$v['car_from_date']." ".$v['pickup_time']?></td>
							<td><?=$v['car_to_date']." ".$v['drop_time']?></td>
							<td><?=date('d-m-Y', strtotime($v['created_datetime']))?></td>
							<td><?=$v['total_fare']?></td>
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['convinence_amount']?></td>
							<td><?=$v['discount']?></td>
							<td><?=$v['grand_total']?></td>
							<td><?=$v['status']?></td>
					</tr>
					<?php
					$i++;
						}
					}
					?>

		</tbody></table>
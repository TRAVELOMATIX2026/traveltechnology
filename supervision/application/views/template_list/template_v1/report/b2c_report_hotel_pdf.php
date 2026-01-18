<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-sm table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>lead_pax_name</th>
			<th>lead_pax_email</th>
			<th>lead_pax_phone_number</th>
			<th>Hotel Name</th>
			<th>No.of rooms</th>
			<th>No.of Adult</th>
			<th>No.of Child</th>
			<th>city</th>
			<th>check_in</th>
			<th>check_out</th>
			<th>Fare</th>
			<!-- <th>Agency Markup</th> -->
			<th>Admin Markup</th>
			<th>Convn.Fee</th>
			<th>GST</th>	
			<th>Discount</th>
			<th>Amount Deducted From Agent</th>
			<th>grand_total</th>
			<th>Status</th>
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
						//debug($v);exit;
					
					?>
					<tr>
							 <td><?=$j;?></td>
							<td><?=$v['app_reference']?></td>
							<td><?=$v['confirmation_reference']." / ".$v['booking_reference']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['hotel_name']?></td>
							<td><?=$v['total_rooms']?></td>
							<td><?=$v['adult_count']?></td>
							<td><?=$v['child_count']?></td>
							<td><?=$v['hotel_location']?></td>
							<td><?=$v['hotel_check_in']?></td>
							<td><?=$v['hotel_check_out']?></td>
							<td><?=$v['fare']?></td>
							<!-- <td><?=$v['agent_markup']?></td> -->
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['convinence_value']?></td>
							<td><?=$v['gst']?></td>
							<td><?=$v['discount']?></td>
							<td><?=$v['fare'] + $v['admin_markup']?></td>
							<td><?=$v['grand_total']?></td>
							<td><?=$v['status']?></td>
							<td><?=date('d-m-Y', strtotime($v['voucher_date']))?></td>
					</tr>
					<?php
					$j++;
						}
					}
					?>

		</tbody></table>
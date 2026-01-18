<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-sm table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sl. No.</th>
			<th>Appreference</th>
			<th>Lead Pax Name</th>
			<th>lead_pax_email</th>
			<th>lead_pax_phone_number</th>
			<th>GDS PNR</th>
			<th>Airline PNR</th>
			<th>Airline Code</th>
			<th>From</th>
			<th>To</th>
			<th>Form Date</th>
			<th>To Date</th>
			<th>Commission Fare</th>
			<th>Commission</th>	
			<th>TDS</th>
			<th>Admin Markup</th>
			<th>Net Fare</th>
			<th>GST</th>
			<th>Convinence Amount</th>
			<th>Discount</th>
			<th>TotalFare</th>
			<th>Booked Date</th>
		</tr>
		</thead>
		<tbody>
			<?php

				// debug($export_data);exit;
				if(!empty($export_data))
				{
					$i=1;

					foreach($export_data as $key => $v) {
						//debug($v);exit;
					
					?>
					<tr>
							 <td><?=$i?></td>
							<td><?=$v['app_reference']?></td>
							<td><?=$v['lead_pax_name']?></td>
							<td><?=$v['lead_pax_email']?></td>
							<td><?=$v['lead_pax_phone_number']?></td>
							<td><?=$v['pnr']?></td>
							<td><?=$v['booking_itinerary_details'][0]['airline_pnr']?></td>
							<td><?=$v['booking_itinerary_details'][0]['airline_code']?></td>
							<td><?=$v['journey_from']?></td>
							<td><?=$v['journey_to']?></td>
							<td><?=$v['journey_start']?></td>
							<td><?=$v['journey_end']?></td>
							<td><?=$v['fare']?></td>
							<td><?=$v['net_commission']?></td>
							<td><?=$v['net_commission_tds']?></td>
							<td><?=$v['admin_markup']?></td>
							<td><?=$v['net_fare']?></td>
							<td><?=$v['gst']?></td>
							<td><?=$v['convinence_amount']?></td>
							<td><?=$v['discount']?></td>
							<td><?=$v['grand_total']?></td>
							<td><?=date('d-m-Y', strtotime($v['booked_date']))?></td>
					</tr>
					<?php
					$i++;
						}
					}
					?>

		</tbody></table>
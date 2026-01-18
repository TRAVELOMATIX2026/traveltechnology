<?php
if($booking_source == CLARITYNDC_FLIGHT_BOOKING_SOURCE){
	$flight_segment_fare=$fare_rules['Rules'][0]['Rule'];
	$rules = '';
	$rules .= '<div class="col-12 nopad">';
		$rules .= '<div class="inboundiv splfares">';
		$rules .= '<h4 class="farehdng">Fare Rules</h4>';
		$Origin = '';
		$Destination = '';
	foreach ($flight_segment_fare as $__fare_key => $__fare_rules) {
		if($__fare_rules['Category']=="Departure"){
			$Origin = $__fare_rules['Text'];
		}else if($__fare_rules['Category']=="Arrival"){
			$Destination = $__fare_rules['Text'];
		}else if($__fare_rules['Category']=="Data"){
				$rules .= '<div class="flight-fare-rules rowfare">';
					$rules .= '<div class="lablfare">';
						$rules .= $Origin.' <span class="fa fa-long-arrow-right"></span> '.$Destination;
					$rules .= '</div>';
					/*$rules .= '<div class="feenotes"><ul>';
					$text_data = array();
					if(isset($__fare_rules['Text']['RULE APPLICATION AND OTHER CONDITIONS'])){
						$text_data = $__fare_rules['Text']['RULE APPLICATION AND OTHER CONDITIONS'];
					}else if(isset($__fare_rules['Text']['RULE APPLICATION'])){
						$text_data = $__fare_rules['Text']['RULE APPLICATION'];
					}
					foreach($text_data as $value) {
						$rules .= '<li>'.$value.'</li>';
					}
					$rules .= '</ul></div>';*/
					if(!empty($__fare_rules['Text'])){
						foreach($__fare_rules['Text'] as $rule_k=>$rule_v){
							if(!empty($rule_v)){
								$rules .='<label>'.$rule_k.':</label>';
								$rules .= '<div class="feenotes"><ul>';
								foreach($rule_v as $value){
									$rules .= '<li>'.$value.'</li>';
								}
								$rules .= '</ul></div><hr>';
							}
						}
					}
				$rules .= '</div>';
		} 

	}
		$rules .= '</div>';
	$rules .= '</div>';
	echo $rules;
}else{
	$flight_segment_fare = force_multple_data_format($fare_rules);
	$rules = '';
	$rules .= '<div class="col-12 nopad">';
		$rules .= '<div class="inboundiv splfares">';
		$rules .= '<h4 class="farehdng">Fare Rules</h4>';
	foreach ($flight_segment_fare as $__fare_key => $__fare_rules) {
		$rules .= '<div class="flight-fare-rules rowfare">';
			$rules .= '<div class="lablfare">';
				$rules .= $__fare_rules['Origin'].' <span class="fa fa-long-arrow-right"></span> '.$__fare_rules['Destination'];
			$rules .= '</div>';
			$rules .= '<div class="feenotes">';
			$rules .= (isset($__fare_rules['FareRules']) == true ? $__fare_rules['FareRules'] : 'Not Available.');
			$rules .= '</div>';
		$rules .= '</div>';
	}
		$rules .= '</div>';
	$rules .= '</div>';
	echo $rules;
}
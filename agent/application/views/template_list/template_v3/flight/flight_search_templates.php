<?php
/**
 * Flight Search HTML Templates for JavaScript
 * 
 * This file contains HTML template strings that are output as JavaScript variables.
 * Pattern matches tbo_col2x_search_result.php - direct PHP string concatenation.
 */

$template_images = $GLOBALS['CI']->template->template_images();

// Fare Breakup Template
$fare_breakup_template = '<div id="fare_break_{ID}" class="tab-pane fade i-i-s-t">';
$fare_breakup_template .= '<div class="col-12 nopad full_wher">';
$fare_breakup_template .= '<div class="inboundiv sidefare">';
$fare_breakup_template .= '<h4 class="farehdng">Total Fare Breakup</h4>';
$fare_breakup_template .= '<div class="inboundivinr fare-breakup-table">';
$fare_breakup_template .= '<div class="rowfare fare-row"><div class="fare-label"><span class="infolbl">Total Base Fare</span></div><div class="fare-value"><span class="pricelbl">{CURRENCY_SYMBOL} {BASE_FARE}</span></div></div>';
$fare_breakup_template .= '<div class="rowfare fare-row"><div class="fare-label"><span class="infolbl">Taxes &amp; Fees</span></div><div class="fare-value"><span class="pricelbl">{CURRENCY_SYMBOL} {TOTAL_TAX}</span></div></div>';
$fare_breakup_template .= '<div class="rowfare grandtl fare-row-grand"><div class="fare-label"><span class="infolbl">Grand Total</span></div><div class="fare-value"><span class="pricelbl">{CURRENCY_SYMBOL} {TOTAL_FARE}</span></div></div>';
$fare_breakup_template .= '</div></div></div></div>';

// Baggage Details Template
$baggage_details_template = '<div class="baggage_dtls">';
$baggage_details_template .= '<div class="baggage_img"><img src="' . $template_images . 'trolley.png" alt="Trolley"></div>';
$baggage_details_template .= '<div class="baggage_chckn_cbin"><h4>Check-in</h4><p>Adult: {BAGGAGE}</p><h4>Cabin</h4><p>Adult: {CABIN_BAGGAGE}</p></div>';
$baggage_details_template .= '</div>';

// Baggage Inclusions Template
$baggage_inclusions_template = '<div class="baggage_inclusn_pax">';
$baggage_inclusions_template .= '<div class="baggage_inclusn_dtls"><h6>Inclusions</h6><p>Adult: </p></div>';
$baggage_inclusions_template .= '<div class="baggage_per_pax"><i class="fal fa-check"></i><p>Hand Bag</p></div>';
$baggage_inclusions_template .= '</div>';

// Baggage Wrapper Template
$baggage_wrapper_template = '<div class="baggage_dtls_wrapper">';
$baggage_wrapper_template .= '<div class="baggage_hdng"><span class="baggage_deprt_dtls"><span class="baggage_dprt">DEPART</span><span class="baggage_dprt_date">{DEPARTURE_DATE}</span></span>';
$baggage_wrapper_template .= '<span class="baggage_dest">{ORIGIN_CODE} <span><i class="fal fa-long-arrow-right"></i></span> {DESTINATION_CODE}</span></div>';
$baggage_wrapper_template .= '{BAGGAGE_DETAILS}{BAGGAGE_INCLUSIONS}</div>';

// Baggage Inclusions Container Template
$baggage_inclusions_container_template = '<div id="baggage_inclusions_{ID}" class="baggage_inclusions tab-pane fade i-i-s-t">';
$baggage_inclusions_container_template .= '<div class="col-12 nopad full_wher"><div class="inboundiv sidefare">{BAGGAGE_WRAPPERS}</div></div></div>';

// Flight Detail Item Template
$flight_detail_item_template = '<div class="flitone">';
$flight_detail_item_template .= '<div class="col-12 col-md-2"><div class="imagesmflt"><img alt="{AIRLINE_CODE} icon" src="{AIRLINE_LOGO_PATH}"></div>';
$flight_detail_item_template .= '<div class="flitsmdets">{AIRLINE_NAME}<strong>{AIRLINE_CODE} {FLIGHT_NUMBER}</strong></div></div>';
$flight_detail_item_template .= '<div class="col-8 nopad5 d-flex"><div class="col-5 text-center nopad"><div class="termnl">{ORIGIN_CITY} ({ORIGIN_CODE})<br><span class="dateone">{ORIGIN_DATETIME}</span></div></div>';
$flight_detail_item_template .= '<div class="col-2 nopad align-self-center justify-content-center d-flex align-items-center"><div class="arocl bi bi-arrow-right"></div></div>';
$flight_detail_item_template .= '<div class="col-5 text-center nopad"><div class="termnl">{DESTINATION_CITY} ({DESTINATION_CODE})<br><span class="dateone">{DESTINATION_DATETIME}</span></div></div></div>';
$flight_detail_item_template .= '<div class="col-4 col-md-2 nopad5"><div class="ritstop"><div class="termnl tot_dur">{SEGMENT_DURATION}</div><div class="termnl1">Stop : {STOP_KEY}</div></div></div>';
$flight_detail_item_template .= '</div> <div class="Baggage_block">{BAGGAGE_INFO}{AVAILABLE_SEATS}</div>{LAYOVER_INFO}';

// Layover Info Template
$layover_info_template = '<div class="clearfix"></div><div class="layoverdiv"><div class="centovr">';
$layover_info_template .= '<span class="fa fa-plane"></span>Plane change at {LAYOVER_CITY} | <span class="fa fa-clock-o"></span> Waiting: {WAITING_TIME}</div></div><div class="clearfix"></div>';

// Send Mail Modal Template
$send_mail_modal_template = '<div id="sendmail_multi_{GEN_ID}" class="modal fade" role="dialog" data-id="{GEN_ID}">';
$send_mail_modal_template .= '<div class="modal-dialog" style="margin: 200px auto;"><div class="modal-content">';
$send_mail_modal_template .= '<div class="modal-header"><button type="button" class="close" data-bs-dismiss="modal" id="close_modal_multi_{GEN_ID}">&times;</button>';
$send_mail_modal_template .= '<h4 class="modal-title mdltitl">Send Flight Information</h4></div>';
$send_mail_modal_template .= '<div class="modal-body"><div class="form-group">';
$send_mail_modal_template .= '<input type="email" class="form-control mfc" id="email_multi_{GEN_ID}" placeholder="Enter Email ID" name="email" />';
$send_mail_modal_template .= '<input type="hidden" class="form-control mfc" id="flightdetails_multi_{GEN_ID}" placeholder="Enter email" name="email" value="" />';
$send_mail_modal_template .= '<span id="errormsg_multi_{GEN_ID}"></span>';
$send_mail_modal_template .= '<div id="send_email_loading_image_multi_{GEN_ID}" style="display: none;">';
$send_mail_modal_template .= '<div class="text-center loader-image" style="display: none;"><img src="' . $template_images . 'loader_v3.gif" alt="please wait" /></div></div></div>';
$send_mail_modal_template .= '<button type="button" id="send_email_btn_not_multi_{GEN_ID}" class="btn btn-secondary flteml" onclick="sendflightdetails_multi(\'{GEN_ID}\')">Send</button>';
$send_mail_modal_template .= '</div></div></div></div>';

// Flight Details Collapse Template
$flight_details_popup_template = '<div class="collapse flight-details-collapse" id="fdp_{GEN_ID}">';
$flight_details_popup_template .= '<div class="p_i_w">';
$flight_details_popup_template .= '<div class="popuphed"><div class="hdngpops">{ORIGIN} <span class="{ARROW_CLASS}"></span> {DESTINATION}</div></div>';
$flight_details_popup_template .= '<div class="popconyent"><div class="contfare">';
$flight_details_popup_template .= '<ul role="tablist" class="nav nav-tabs flittwifil" id="flight-tabs-{GEN_ID}">';
$flight_details_popup_template .= '<li class="nav-item" role="presentation"><a class="nav-link active" data-bs-toggle="tab" data-bs-target="#iti_det_{GEN_ID}" role="tab" aria-selected="true" aria-controls="iti_det_{GEN_ID}">Itinerary</a></li>';
$flight_details_popup_template .= '<li class="nav-item" role="presentation"><a class="nav-link iti-fare-btn" data-bs-toggle="tab" data-bs-target="#fare_det_{GEN_ID}" role="tab" data-form-id="form-id-{GEN_ID}" aria-selected="false" aria-controls="fare_det_{GEN_ID}">Fare Details</a></li>';
$flight_details_popup_template .= '<li class="nav-item" role="presentation"><a class="nav-link iti-fare-btn" data-bs-toggle="tab" data-bs-target="#fare_break_{GEN_ID}" role="tab" aria-selected="false" aria-controls="fare_break_{GEN_ID}">Fare Breakup</a></li>';
$flight_details_popup_template .= '<li class="nav-item" role="presentation"><a class="nav-link iti-fare-btn" data-bs-toggle="tab" data-bs-target="#baggage_inclusions_{GEN_ID}" role="tab" aria-selected="false" aria-controls="baggage_inclusions_{GEN_ID}">Baggage & Inclusions</a></li>';
$flight_details_popup_template .= '</ul><div class="tab-content" id="flight-tab-content-{GEN_ID}">';
$flight_details_popup_template .= '<div id="iti_det_{GEN_ID}" class="tab-pane fade show active i-i-s-t" role="tabpanel" aria-labelledby="iti-tab-{GEN_ID}">';
$flight_details_popup_template .= '<div class="tabmarg"><div class="alltwobnd"><div class="col-12 nopad full_wher">{ITINERARY_CONTENT}</div></div></div></div>';
$flight_details_popup_template .= '<div id="fare_det_{GEN_ID}" class="tab-pane fade Fare_Rules i-i-f-s-t" role="tabpanel" aria-labelledby="fare-tab-{GEN_ID}">';
$flight_details_popup_template .= '<div class="fare-details-loader"><div class="fare-loader-content"><div class="fare-loader-graphic"><div class="fare-loader-spinner"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 16V14L15 9.5V7C15 6.45 14.55 6 14 6H13V4C13 3.45 12.55 3 12 3H11C10.45 3 10 3.45 10 4V6H9C8.45 6 8 6.45 8 7V9.5L2 14V16L8 13.5V19L6 20.5V22L12 21L18 22V20.5L16 19V13.5L21 16Z" fill="currentColor"/></svg></div><div class="loader-rings"><div class="ring ring-1"></div><div class="ring ring-2"></div><div class="ring ring-3"></div></div></div><p class="fare-loader-text">Loading fare details...</p></div></div><div class="i-s-s-c tabmarg"></div></div>';
$flight_details_popup_template .= '{FARE_BREAKUP}{BAGGAGE_INCLUSIONS}</div></div></div></div></div>';

// Flight Segment Template
$flight_segment_template = '<div class="allsegments outer-segment-{SEGMENT_INDEX} d-flex">';
$flight_segment_template .= '<div class="quarter_wdth nopad col-3"><div class="text_center_airline">';
$flight_segment_template .= '<div class="fligthsmll"><img class="airline-logo" alt="{AIRLINE_CODE} icon" src="{AIRLINE_LOGO_PATH}"></div>';
$flight_segment_template .= '<div class="m-b-0 text-center"><div class="a-n airlinename" data-code="{AIRLINE_CODE}">{AIRLINE_NAME}</div>';
$flight_segment_template .= '<span style="display:block;">{AIRLINE_CODE} {FLIGHT_NUMBER}</span></div></div></div>';
$flight_segment_template .= '<div class="col-3 nopad quarter_wdth"><div class="insidesame">';
$flight_segment_template .= '<span class="fdtv hide" data-val="{DEPARTURE_DATETIME}">{DEPARTURE_TIME}</span>';
$flight_segment_template .= '<div class="f-d-t bigtimef">{DEPARTURE_DATETIME_FORMATTED}</div>';
$flight_segment_template .= '<span class="flt_date {DEPARTURE_DATE_CLASS}" {DEPARTURE_DATE_ATTR}>{DEPARTURE_DATE}</span>';
$flight_segment_template .= '<span class="dep_dt hide" data-category="{DEPARTURE_CATEGORY}"></span>';
$flight_segment_template .= '<div class="from-loc smalairport">{ORIGIN_CITY} ({ORIGIN_CODE})</div></div></div>';
$flight_segment_template .= '<div class="col-md-1 p-tb-10 hide"><div class="arocl fa fa-long-arrow-right"></div></div>';
$flight_segment_template .= '<div class="smal_udayp nopad col-3"><span class="f-d hide">{DURATION_MINUTES}</span>';
$flight_segment_template .= '<div class="insidesame" data-stop-tooltip="{LAYOVER_TOOLTIP}"><div class="durtntime">{DURATION}</div>';
$flight_segment_template .= '<div class="stop_image stop_{STOP_COUNT}"></div>';
$flight_segment_template .= '<div class="stop-value">Stop: {STOP_COUNT}</div>';
$flight_segment_template .= '<span class="slider_stop" data-slider_stop="{STOP_COUNT}"></span>{LAYOVER_SPANS}';
$flight_segment_template .= '<span class="hide org-airport" data-airport="{ORIGIN_CODE}"></span>';
$flight_segment_template .= '<span class="hide dst-airport" data-airport="{DESTINATION_CODE}"></span>';
$flight_segment_template .= '<span class="hide layover-airport" data-airport="{LAYOVER_AIRPORT}"></span>';
$flight_segment_template .= '<div class="cabinclass hide">{CABIN_CLASS}</div></div>{STOP_AIR_CODES}</div>';
$flight_segment_template .= '<div class="col-3 nopad quarter_wdth"><div class="insidesame">';
$flight_segment_template .= '<span class="fatv hide">{ARRIVAL_TIME}</span>';
$flight_segment_template .= '<div class="f-a-t bigtimef">{ARRIVAL_DATETIME_FORMATTED}</div>';
$flight_segment_template .= '<span class="flt_date">{ARRIVAL_DATE}</span>';
$flight_segment_template .= '<span class="arr_dt hide" data-category="{ARRIVAL_CATEGORY}"></span>';
$flight_segment_template .= '<div class="to-loc smalairport">{DESTINATION_CITY} ({DESTINATION_CODE})</div></div></div></div>';

// Fare Calendar Item Template
$fare_calendar_item_template = '<div class="item" title="{TIP}">';
$fare_calendar_item_template .= '<a class="pricedates add_days_todate" data-journey-date="{START_DATE}">';
$fare_calendar_item_template .= '<div class="imgemtrx_plusmin"><img alt="Flight" src="{AIRLINE_LOGO_PATH}"></div>';
$fare_calendar_item_template .= '<div class="alsmtrx"><strong>{START_LABEL}</strong><span class="mtrxprice">{PRICE_TITLE}</span></div>';
$fare_calendar_item_template .= '</a></div>';
?>

<script>
/**
 * HTML Templates for Flight Search - Generated by PHP
 * These templates are used by JavaScript with simple string replacement
 */

// Fare Breakup Template
var fare_breakup_template = '<?php echo addslashes($fare_breakup_template); ?>';

// Baggage Details Template
var baggage_details_template = '<?php echo addslashes($baggage_details_template); ?>';

// Baggage Inclusions Template
var baggage_inclusions_template = '<?php echo addslashes($baggage_inclusions_template); ?>';

// Baggage Wrapper Template
var baggage_wrapper_template = '<?php echo addslashes($baggage_wrapper_template); ?>';

// Baggage Inclusions Container Template
var baggage_inclusions_container_template = '<?php echo addslashes($baggage_inclusions_container_template); ?>';

// Flight Detail Item Template
var flight_detail_item_template = '<?php echo addslashes($flight_detail_item_template); ?>';

// Layover Info Template
var layover_info_template = '<?php echo addslashes($layover_info_template); ?>';

// Send Mail Modal Template
var send_mail_modal_template = '<?php echo addslashes($send_mail_modal_template); ?>';

// Flight Details Popup Template
var flight_details_popup_template = '<?php echo addslashes($flight_details_popup_template); ?>';

// Flight Segment Template
var flight_segment_template = '<?php echo addslashes($flight_segment_template); ?>';

// Fare Calendar Item Template
var fare_calendar_item_template = '<?php echo addslashes($fare_calendar_item_template); ?>';

// Helper function to replace placeholders in templates
function replaceTemplate(template, data) {
	var result = template;
	for (var key in data) {
		if (data.hasOwnProperty(key)) {
			var regex = new RegExp('\\{' + key + '\\}', 'g');
			result = result.replace(regex, data[key] !== null && data[key] !== undefined ? String(data[key]) : '');
		}
	}
	return result;
}
</script>

$(document).ready(function () {

	pre_load_audio();

	show_result_pre_loader();

	var _active_booking_source = JSON.parse(document.getElementById('pri_active_source').value);

	var _search_id = document.getElementById('pri_search_id').value;

	var _airline_lg_path = document.getElementById('pri_airline_lg_path').value;

	var template_image_path = document.getElementById('pri_template_image_path').value;

	var _search_params = JSON.parse(document.getElementById('pri_search_params').value);

	var default_currency = document.getElementById('pri_def_curr').value;

	var sliderCurrency = default_currency;

	// ===== GLOBAL DATA =====
		var flightList = [];
		var flightListFiltered = [];
		var FlightsRaw = [];
		var tripType = 'oneway';

		var currentSortType = 'best';
		var currentSortOrder = 'asc';

	/** PAGE FUNCTIONALITY STARTS HERE **/

	var application_preference_currency = document.getElementById('pri_preferred_currency').value;

	if (document.getElementById('pri_trip_type').value == true) {

		function fare_carousel() {

			var item_length = $('#farecal .item').length;

			if (item_length > 6) {

				item_length = 6;

			} else {

				item_length = item_length - 1;

			}

			$("#farecal").owlCarousel({

				items: item_length,

				itemsDesktop: [1200, 4],

				itemsDesktopSmall: [991, 3],

				itemsTablet: [600, 2],

				itemsMobile: [479, 1],

				navigation: true,

				pagination: false,

				autoPlay: false,

				autoplayTimeout: 3000,

				autoplayHoverPause: true

			});

		}

		/**

		 *Load Flight - Source Trigger

		 */

		function load_flight_fare_calendar(date) {

			$.each(_active_booking_source, function (k, booking_source_name) {

				core_flight_fare_loader(booking_source_name, date);

			});

		}

		/**

		*Update fare day wise 

		*/

		function load_flight_day_fare(current_date, session_id, thisRef) {

			$.each(_active_booking_source, function (k, booking_source_name) {

				core_flight_day_fare_loader(booking_source_name, current_date, session_id, thisRef);

			});

		}



		/**

		 *Load Flight Fare Core Ajax

		 */

		function core_flight_fare_loader(booking_source_id) {

			var search_params = {};

			search_params['search_id'] = _search_id;

			$.ajax({

				type: 'GET',

				url: app_base_url + 'ajax/puls_minus_days_fare_list/' + booking_source_id + '?' + jQuery.param(search_params),

				async: true,

				cache: true,

				dataType: 'json',

				success: function (result) {

					if (result.hasOwnProperty('status') == true && result.status == true) {

						activate_fare_carousel(result.data.day_fare_list);

					} else {

						//Fare Calendar not available

						$('#fare_calendar_wrapper').remove();

					}

				}

			});

		}

		//load carousel data for fare calendar

		function activate_fare_carousel(list) {

			var imgPath = _airline_lg_path;

			var data_list = '';

			$.each(list, function (k, v) {



				data_list += '<div class="item" title="' + v.tip + '">';

				data_list += '<a class="pricedates add_days_todate" data-journey-date="' + v.start + '">';

				data_list += '<div class="imgemtrx_plusmin"><img  alt="Flight" src="' + imgPath + v.data_id + '.svg"></div>';

				data_list += '<div class="alsmtrx">';

				data_list += '<strong>' + v.start_label + '</strong>';

				data_list += '<span class="mtrxprice">' + v.title + '</span>';

				data_list += '</div>';

				data_list += '</a>';

				data_list += '</div>';

			});

			$('#farecal').html(data_list);

			fare_carousel();

		}

		function core_flight_day_fare_loader(booking_source_id, departure, session, thisRef) {

			if (session != '') {

				$('.result-pre-loader').show();

				var search_params = _search_params;

				search_params['session_id'] = session;

				search_params['depature'] = departure || search_params['depature'];

				departure = search_params['depature'];

				$.ajax({

					type: 'POST',

					url: app_base_url + 'ajax/day_fare_list/' + booking_source_id + '?' + jQuery.param(search_params),

					async: false,

					cache: true,

					dataType: 'json',

					success: function (result) {

						$('.result-pre-loader').hide();

						if (result.hasOwnProperty('status') == true && result.status == true) {

							fare_cache[result.data.departure] = result.data.day_fare_list;

							fare_session[result.data.departure] = result.next_search;

							update_fare_calendar_events(result.data.departure);

						} else {

							//No Result Found

							alert(result.msg);

						}

						hide_result_pre_loader();

					}

				});

			}

		}



		//Balu A

		$(document).on('click', '.add_days_todate', function () {

			var new_date = $(this).data('journey-date');

			var search_id = _search_id;

			window.location.href = app_base_url + 'flight/add_days_todate?search_id=' + search_id + '&new_date=' + new_date;

		});

		load_flight_fare_calendar();

	}



function get_time(){
	var now = new Date(); // Get current date and time
	var hours = String(now.getHours()).padStart(2, '0'); // Get hours and pad with zeros
	var minutes = String(now.getMinutes()).padStart(2, '0'); // Get minutes and pad with zeros
	var seconds = String(now.getSeconds()).padStart(2, '0'); // Get seconds and pad with zeros

	var currentTime = `${hours}:${minutes}:${seconds}`;
	return currentTime;
}

function add_unpublished_strip(){
	//console.log('cliccc');
	$('.lstng_banner_hrzntl').remove();
	if (middle_ads.length > 0) {
		//console.log(middle_ads, "middle_ads");
	setTimeout(function() {
		// var total_items = $('.t-w-i-1:visible').length;
		// console.log("total_items",total_items);
		// console.log(bnr_image);
		// var call_us_private = banner_image;
		/*var sel_no = 3;
		for (i=1; i<total_items; i +=3){
			if(sel_no==3){
				sel_no=2;
			}
			if(sel_no < total_items){
				$('#t-w-i-1 .t-w-i-1:eq('+sel_no+')').after();
				sel_no = sel_no+3;
			}
		}*/
	
			  $(".t-w-i-1:visible").each(function (index) {
				var randomIndex = Math.floor(Math.random() * middle_ads.length);
				//console.log(middle_ads[randomIndex], "middle_ads ddddd");
				if(index==0){
					template =
					'<div class="lstng_banner_hrzntl "> <img src="' +
					bnr_image_path +
					middle_ads[randomIndex].image +
					'"  width="1186px" height="131px">';
					if(middle_ads[randomIndex].message!=null && middle_ads[randomIndex].message!='null'){
					template +=	'<p>' +middle_ads[randomIndex].message + '</p>';
					}
					 template +='</div>';
				  $('#flight_search_result').before(template);
				}
				randomIndex = Math.floor(Math.random() * middle_ads.length);
				
				//   const newElement = $(bnr_image);
				if (index!=0 && ((index + 1) % 5 === 0)) {
				  template =
					'<div class="lstng_banner_hrzntl "> <img src="' +
					bnr_image_path +
					middle_ads[randomIndex].image +
					'"  width="1186px" height="131px">';
					if(middle_ads[randomIndex].message!=null && middle_ads[randomIndex].message!='null'){
					template +=	'<p>' +middle_ads[randomIndex].message + '</p>';
					}
					 template +='</div>';
				  $(this).append(template);
				}
			  });

		
			// for (i = 0; i < middle_ads.length; i++) {
			// console.log(middle_ads[i], "middle_ads ddddd");
			//   $(".t-w-i-1:visible").each(function (index) {
			// 	if(index==0){
			// 		template =
			// 		'<div class="lstng_banner_hrzntl"> <img src="' +
			// 		bnr_image_path +
			// 		middle_ads[i].image +
			// 		'"  width="1186px" height="131px"> <p>' +middle_ads[i].message + "</p> </div>";
			// 	  $('#flight_search_result').before(template);
			// 	}
			// 	//   const newElement = $(bnr_image);
			// 	if (index!=0 && ((index + 1) % 5 === 0)) {
			// 	  template =
			// 		'<div class="lstng_banner_hrzntl "> <img src="' +
			// 		bnr_image_path +
			// 		middle_ads[i].image +
			// 		'"  width="1186px" height="131px"> <p>' +middle_ads[i].message + "</p> </div>";
			// 	  $(this).append(template);
			// 	}
			//   });
			// }
		

		// $('.t-w-i-1:visible').each(function(index) {
		// 	const newElement = $(bnr_image);
		// 	if ((index + 1) % 3 === 0) {
		// 		$(this).append(newElement);
		// 	}
		// });
	}, 2000);
 }
}
function check_empty_search_result() {

		if ($('.r-r-i:first').index() == -1) {

			$('#empty-search-result').show();

			$('#page-parent').hide();

		}

	}


	var minDefaultPrice = maxDefaultPrice = '';

	var min_amt = 0;
	var max_amt = 0;
	function loadPriceRangeSelector(minPrice, maxPrice, original_minPrice = '', original_maxPrice = '') {
		minPrice = parseFloat(minPrice);
		maxPrice = parseFloat(maxPrice);
		min_amt = minPrice;
		max_amt = maxPrice;
		minDefaultPrice = minPrice;
		maxDefaultPrice = maxPrice;
		$("#slider-range").slider({
			range: true,
			min: minPrice,
			max: maxPrice,
			animate: "slow",
			values: [minPrice, maxPrice],
			slide: function (event, ui) {
				set_slider_label(ui.values[0], ui.values[1]);
			},
			change: function (event, ui) {
				if (parseFloat(ui.values[0]) == min_amt) {
					if (parseFloat(ui.values[1]) > max_amt) {
						visibility = ':hidden';
					} else {
						visibility = ':visible';
					}
				} else {
					if (parseFloat(ui.values[0]) < min_amt) {
						visibility = ':hidden';
					} else {
						visibility = ':visible';
					}
				}
				if (!filterReset) {
					apply_filters();
				}
			}
		});
		set_slider_label(minPrice, maxPrice, original_minPrice, original_maxPrice);
	}

	var minDefaultLay = maxDefaultLay = '';
	var min_lay = 0;
	var max_lay = 0;
	function loadLayRangeSelector(minLay, maxLay) {
		min_lay = minLay;
		max_lay = maxLay;
		minDefaultLay = minLay;
		maxDefaultLay = maxLay;
		$("#slider-range-lay").slider({
			range: true,
			min: minLay,
			max: maxLay,
			animate: "slow",
			values: [minLay, maxLay],
			slide: function (event, ui) {
				set_slider_lay_label(ui.values[0], ui.values[1]);
			},
			change: function (event, ui) { 
				if (parseFloat(ui.values[0]) == min_lay) {
					if (parseFloat(ui.values[1]) > max_lay) {
						visibility = ':hidden';
					} else {
						visibility = ':visible';
					}
				} else {
					if (parseFloat(ui.values[0]) < min_lay) {
						visibility = ':hidden';
					} else {
						visibility = ':visible';
					}
				}
				if (!filterReset) {
					apply_filters();
				}
			}
		});
		set_slider_lay_label(minLay, maxLay);
	}

	function set_slider_lay_label(val1, val2) {
		if (val2 == "-Infinity") {
			$("#layover").text("Direct flights only");
		} else {
			$("#layover").text(val1 + " Hours - " + val2 + " Hours");
		}
	}
	function set_slider_label(val1, val2, original_minPrice = '', original_maxPrice = '') {
		$("#amount").html("<span class='filt-currency' >" + sliderCurrency + " </span>" + "<span class='filt-min_price' >" + formatIndianNumber(val1) + " </span>" + "<span class='filt-min_price hide' data-min_price='" + original_minPrice + "' >" + val1.toFixed(2) + " </span>" + " - " + "<span class='filt-currency' >" + sliderCurrency + " </span>" + "<span class='filt-max_price' >" + formatIndianNumber(val2) + " </span>" + "<span class='filt-max_price hide' data-max_price='" + original_maxPrice + "' >" + val2.toFixed(2) + " </span>");
	}
	function carousel() {
		$("#arlinemtrx.owl-carousel").owlCarousel({
			items: 5,
			itemsDesktop: [1200, 3],
			itemsDesktopSmall: [991, 3],
			itemsTablet: [600, 2],
			itemsMobile: [479, 1],
			navigation: true,
			pagination: false,
			navigationText: [''],
			autoPlay: true,
			autoplayTimeout: 3000,
			autoplayHoverPause: true,
		});
	}
	
	var filterReset = false;
	$(document).on('click', '#reset_filters', function () {
		filterReset = true;
		loader();

		var minPrice = $('#core_minimum_range_value', '#core_min_max_slider_values').val();
		var maxPrice = $('#core_maximum_range_value', '#core_min_max_slider_values').val();
		var minLay = $('#lay_minimum_range_value', '#lay_min_max_slider_values').val();
		var maxLay = $('#lay_maximum_range_value', '#lay_min_max_slider_values').val();

		$("#slider-range").slider("option", "values", [minPrice, maxPrice]);
		$("#slider-range-lay").slider("option", "values", [minLay, maxLay]);
		filterReset = false;

		//Reset the No. of stops, departure time,arrival time, and airlines
		$('input.airlinecheckbox, input.stopcount, input.time-category,input.flighttypecheckbox,input.originairportcheckbox,input.dstairportcheckbox,input.layovercheckbox').prop('checked', false);

		//remove active classes
		$('.enabled', '#departureTimeWrapper').removeClass('active');
		$('.enabled', '#arrivalTimeWrapper').removeClass('active');
		$('.enabled', '.stopCountWrapper').removeClass('active');

		//Reset the carousel
		$('.owl-item', '.owl-wrapper').each(function () {
			if ($(this).find('.item').hasClass('active') == true) {
				$(this).find('.item').removeClass('active');
				$(this).find('.airline-slider:checked').prop('checked', false);
			}
		});

		set_slider_label(min_amt, max_amt);
		set_slider_lay_label(min_lay, max_lay);
		apply_filters();
	});

	function loader(selector) {
		selector = selector || '#flight_search_result';
		$(selector).animate({
			'opacity': '.1'
		});
		setTimeout(function () {
			$(selector).animate({
				'opacity': '1'
			}, 'slow');
		}, 1000);
	}

	function update_total_count_summary() {
			$('#flight_search_result').show();
			var _visible_records = parseInt($('.r-r-i:visible').length);
			var _total_records = $('.r-r-i').length;
			// alert(_total_records);
			if (isNaN(_visible_records) == true || _visible_records == 0) {
				_visible_records = 0;
				//display warning
				$('#flight_search_result').hide();
				$('#empty_flight_search_result').show();
			} else {
				$('#flight_search_result').show();
				$('#empty_flight_search_result').hide();
			}
			$('#total_records').text(_visible_records);
			if(_visible_records == 1){
				$('#flights_text').text('Flight');
			}
			$('.visible-row-record-count').text(_visible_records);
			$('.total-row-record-count').text(_total_records);
		}


	/**

	* Toggle active class to highlight current applied sorting

	**/

	$(document).on('click', '.sorta', function (e) {

		e.preventDefault();

		$(this).closest('.sortul').find('.active').removeClass('active');

		//Add to sibling

		$(this).siblings().addClass('active');

	});



	$('.loader').on('click', function (e) {

		e.preventDefault();

		loader();

	});

	$(document).on('click', '.reset-page-loader', function (e) {

		e.preventDefault();

		loader();

		location.reload();

	});



	//Handle col2x selector

	$(document).on('click', '.mfb-btn', function (e) {

		e.preventDefault();

		loader('#multi-flight-summary-container');

		loader();

		update_col2x_flight($(this).closest('.r-r-i'), $(this).closest('.r-w-g').attr('id'));

		$('#multi-flight-summary-container').effect('bounce', 'slow');

	});



	/**

	 *Update Selected flight details and highlight selected flight

	 */

	function update_col2x_flight(segment, trip_way_indicator) {

		$(segment).closest('.r-w-g').find('.r-r-i.active').removeClass('active');

		$(segment).addClass('active');

		//update flight details

		var _flight_icon = $('.airline-logo:first', segment).attr('src');

		var _flight_name = $('.a-n:first', segment).text();

		var _flight_from_price = $('#flight-from-price').val();

		var _flight_to_price = $('#flight-to-price').val();



		var _location_details_html = '<div class="topnavi">';

		_location_details_html += '<div class="col-xs-4 padflt widftysing">';

		_location_details_html += '<span class="flitrlbl elipsetool">' + $('.from-loc:first', segment).text() + '</span>';

		_location_details_html += '</div>';

		_location_details_html += '<div class="col-xs-4 padflt nonefitysing">';

		//_location_details_html += '<span class="arofa fa fa-long-arrow-right"></span>';

		_location_details_html += '</div>';

		_location_details_html += '<div class="col-xs-4 padflt widftysing">';

		_location_details_html += '<span class="flitrlbl elipsetool text_algn_rit">' + $('.to-loc:first', segment).text() + '</span>';

		_location_details_html += '</div></div>';

		var _location_details = _location_details_html;



		var _departure = $('.f-d-t:first', segment).text();

		var _arrival = $('.f-a-t:first', segment).text();

		var _duration = $('.durtntime:first', segment).text();

		var _stop_count = $('.stop-value:first', segment).text();

		var stop_image = '';



		var flight_stop_arr = _stop_count.split(':');

		var flight_stop_count = parseInt(flight_stop_arr[1]);



		for (var i = 0; i < 5; i++) {

			if (flight_stop_count == i) {

				stop_image = '<img src=' + template_image_path + 'stop_' + i + '.png alt="stop_image">';

			}



		}

		// console.log("flight_stop_count" + flight_stop_count);

		//console.log(typeof flight_stop_count);

		// if(flight_stop_count=="0"){

		// 	stop_image = '<img src='+template_image_path+'stop_0.png alt="stop_image_0">';	

		// }else if(flight_stop_count=="1"){

		// 	stop_image = '<img src='+template_image_path+'stop_1.png alt="stop_image_1">';	

		// }else if(flight_stop_count=="2"){

		// 	stop_image = '<img src='+template_image_path+'stop_2.png alt="stop_image_2">';	

		// }else if(flight_stop_count=="3"){

		// 	stop_image = '<img src='+template_image_path+'stop_3.png alt="stop_image_3">';	

		// }else if(flight_stop_count=="4"){

		// 	stop_image = '<img src='+template_image_path+'stop_4.png alt="stop_image_4">';	

		// }else if(flight_stop_count >"4"){

		// 	stop_image = '<img src='+template_image_path+'more_stop.png alt="more_stop">';	

		// }

		// console.log("template_image_path" + stop_image);

		if (trip_way_indicator == 't-w-i-1') {

			//t-w-i-1

			$('#multi-flight-summary-container .departure-flight-icon').attr('src', _flight_icon);

			$('#multi-flight-summary-container .departure-flight-name').text(_flight_name);

			$('#multi-flight-summary-container .outbound-details').html(_location_details);

			//_flight_from_price = $('.f-p:first', segment).text();

			_flight_from_price = $('.price:first', segment).data('price');//Balu A

			$('#multi-flight-summary-container .outbound-timing-details .departure').text(_departure);

			$('#multi-flight-summary-container .outbound-timing-details .arrival').text(_arrival);



			$('#multi-flight-summary-container .outbound-timing-details .duration').text(_duration);

			$('#multi-flight-summary-container .outbound-details .nonefitysing').html(stop_image);

			$('#multi-flight-summary-container .outbound-timing-details .stop-count').text(_stop_count);

			//$('#multi-flight-summary-container .inbound-timing-details .stop-count').text(10);

		} else if (trip_way_indicator == 't-w-i-2') {

			//t-w-i-2

			$('#multi-flight-summary-container .arrival-flight-icon').attr('src', _flight_icon);

			$('#multi-flight-summary-container .arrival-flight-name').text(_flight_name);

			$('#multi-flight-summary-container .inbound-details').html(_location_details);

			$('#multi-flight-summary-container .inbound-timing-details .departure').text(_departure);

			$('#multi-flight-summary-container .inbound-timing-details .arrival').text(_arrival);



			$('#multi-flight-summary-container .inbound-timing-details .duration').text(_duration);

			$('#multi-flight-summary-container .inbound-details .nonefitysing').html(stop_image);

			$('#multi-flight-summary-container .inbound-timing-details .stop-count').text(_stop_count);

			//$('#multi-flight-summary-container .inbound-timing-details .stop-count').text(10);

			//_flight_to_price = $('.f-p:first', segment).text();

			_flight_to_price = $('.price:first', segment).data('price');//Balu A

		}

		//update flight-price

		$('#flight-from-price').val(_flight_from_price);

		$('#flight-to-price').val(_flight_to_price);
		
		var totalPrice = parseFloat(_flight_from_price) + parseFloat(_flight_to_price);
		totalPrice = formatIndianNumber(totalPrice);
		$('#multi-flight-summary-container .f-p').text(totalPrice);

		$('#multi-flight-summary-container .currency').text(default_currency);

	}


	/**

	 *Get Booking Form Contents

	 */

	function get_booking_form_contents() {

		//run ajax and get update

		var _trip_way_1 = $('#t-w-i-1 .r-r-i.active:first form.book-form-wrapper').serializeArray();

		var _trip_way_2 = $('#t-w-i-2 .r-r-i.active:first form.book-form-wrapper').serializeArray();

		if (jQuery.isEmptyObject(_trip_way_1) == false && jQuery.isEmptyObject(_trip_way_2) == false) {

			return get_combined_booking_from(JSON.stringify(_trip_way_1), JSON.stringify(_trip_way_2));

		} else {

			location.reload();

		}

	}



	/**

	 *Combined booking form to be loaded via Ajax

	 */

	function get_combined_booking_from(trip_way_1, trip_way_2) {

		var _result = {};

		var params = {

			trip_way_1: trip_way_1,

			trip_way_2: trip_way_2,

			search_id: _search_id

		};

		$.ajax({

			type: 'POST',

			url: app_base_url + 'ajax/get_combined_booking_from',

			async: false,

			cache: false,

			data: $.param(params),

			dataType: 'json',

			success: function (result) {

				if (result.status == true) {

					_result = result.data;

				} else {

					location.reload();

				}

			}

		});

		return _result;

	}



	/**

	 *update default first row as selected

	 */

	function default_col2x() {



		var col_count = $('.r-w-g').length;



		if (parseInt(col_count) == 2) {

			$('#clone-list-container').addClass('hide');

			$('#multi-flight-summary-container').removeClass('hide');

			update_col2x_flight($('#t-w-i-1 .r-r-i:first'), 't-w-i-1');

			update_col2x_flight($('#t-w-i-2 .r-r-i:first'), 't-w-i-2');

		} else {

			//remove double filter

			$('#top-sort-list-wrapper #top-sort-list-2').remove();

			$('#top-sort-list-wrapper').removeClass('addtwofilter');

		}

	}



	/**

	 *Create Booking Form on click on book button

	 */

	$(document).on('click', '#multi-flight-booking-btn', function (e) {

		e.preventDefault();

		//update booking form

		$(this).attr('disabled', true);

		loader('body');

		var _form_contents = get_booking_form_contents();

		$('#trip-way-wrapper').empty().html(_form_contents['form_content']);

		$('#multi-flight-form').attr('action', _form_contents['booking_url']);

		//submit form

		$('#multi-flight-form').submit();

		$(this).removeAttr('disabled');

	});



	$('.toglefil').click(function () {

		$(this).toggleClass('active');

	});

	/** PAGE FUNCTIONALITY ENDS HERE **/



	/** TOBIN Wrote this **/

	/*selection and filter fixed- result*/

	var flterffect = $('.fixincrmnt').offset().top + 60;



	$(window).scroll(function () {

		var yPos = $(window).scrollTop();

		if (yPos > flterffect) {

			$('.fixincrmnt, .addtwofilter').addClass('fixed');

		}

		else {

			$('.fixincrmnt, .addtwofilter').removeClass('fixed');

		}

	});

	/*selection fixed- result end*/



	/*  Mobile Filter  */

	$('.filter_tab').click(function () {

		$('.resultalls').stop(true, true).toggleClass('open');

		$('.coleft').stop(true, true).slideToggle(500);

	});



	var widowwidth = $(window).width();

	if (widowwidth < 651) {

		$('.filterforall').removeClass('addtwofilter');

	}

	if (widowwidth < 991) {



		$('.resultalls.open .allresult').on('click', function () {

			$('.resultalls').removeClass('open');

			$('.coleft').slideUp(500);

		});

	}

	
	
	function fare_breakup(id,currencySymbol,baseFare,totalTax,totalFare){
		return `<div id="fare_break_${id}" class="tab-pane fade i-i-s-t">
					<div class="col-xs-12 nopad full_wher">
						<div class="inboundiv sidefare">
							<h4 class="farehdng">Total Fare Breakup</h4>
							<div class="inboundivinr">
								<div class="rowfare">
									<div class="col-xs-8 nopad">
										<span class="infolbl">Total Base Fare</span>
									</div>
									<div class="col-xs-4 nopad">
										<span class="pricelbl">${currencySymbol} ${formatIndianNumber(baseFare.toFixed(2))}</span>
									</div>
								</div>
								<div class="rowfare">
									<div class="col-xs-8 nopad">
										<span class="infolbl">Taxes &amp; Fees</span>
									</div>
									<div class="col-xs-4 nopad">
										<span class="pricelbl">${currencySymbol} ${formatIndianNumber(totalTax.toFixed(2))}</span>
									</div>
								</div>
								<div class="rowfare grandtl">
									<div class="col-xs-8 nopad">
										<span class="infolbl">Grand Total</span>
									</div>
									<div class="col-xs-4 nopad">
										<span class="pricelbl">${currencySymbol} ${formatIndianNumber(totalFare)}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>`;
	}
	function baggage_inclusions(id, curSegmentDetails) {
		// Helper function to generate baggage details
		function generateBaggageDetails(SegmentDetails) {
			const Baggage1 = $.trim(SegmentDetails.Baggage) || 'No baggage';
			const CabinBaggage = $.trim(SegmentDetails.CabinBaggage) || 'No baggage';
			return `
				<div class="baggage_dtls">
					<div class="baggage_img">
						<img src="${resultSummery.template_image_path}trolley.png" alt="Trolley">
					</div>
					<div class="baggage_chckn_cbin">
						<h4>Check-in</h4>
						<p>Adult: ${Baggage1}</p>
						<h4>Cabin</h4>
						<p>Adult: ${CabinBaggage}</p>
					</div>
				</div>
			`;
		}
	
		// Helper function to generate baggage inclusion
		function generateInclusions() {
			return `
				<div class="baggage_inclusn_pax">
					<div class="baggage_inclusn_dtls">
						<h6>Inclusions</h6>
						<p>Adult: </p>
					</div>
					<div class="baggage_per_pax">
						<i class="fal fa-check"></i>
						<p>Hand Bag</p>
					</div>
				</div>
			`;
		}
	
		// Start the outer div structure
		const htmlParts = [
			`<div id="baggage_inclusions_${id}" class="baggage_inclusions tab-pane fade i-i-s-t">
				<div class="col-xs-12 nopad full_wher">
					<div class="inboundiv sidefare">`
		];
	
		// Loop through curSegmentDetails
		curSegmentDetails.forEach(curSegmentDetail => {
			curSegmentDetail.forEach(SegmentDetails => {
				const departureDate = formatDateToWeekday(SegmentDetails.DestinationDetails.DateTime);
				const departureAirport = SegmentDetails.OriginDetails.AirportCode;
				const destinationAirport = SegmentDetails.DestinationDetails.AirportCode;
	
				htmlParts.push(`
					<div class="baggage_dtls_wrapper">
						<div class="baggage_hdng">
							<span class="baggage_deprt_dtls">
								<span class="baggage_dprt">DEPART</span>
								<span class="baggage_dprt_date">${departureDate}</span>
							</span>
							<span class="baggage_dest">${departureAirport} <span><i class="fal fa-long-arrow-right"></i></span>${destinationAirport}</span>
						</div>
						${generateBaggageDetails(SegmentDetails)}
						${generateInclusions()}
					</div>
				`);
			});
		});
	
		// Close all open divs
		htmlParts.push(`
				</div>
			</div>
		</div>`);
	
		return htmlParts.join(''); // Join and return the final HTML string
	}
	function flight_detail(segmentDetail, next_seg_info, segKey) {
		// Initialize an array to accumulate HTML parts
		const htmlParts = [];
	
		// Airline section with image and flight info
		htmlParts.push(`
			<div class="flitone">
				<div class="col-xs-12 col-md-2">
					<div class="imagesmflt">
						<img alt="${segmentDetail.AirlineDetails.AirlineCode} icon" 
							 src="${airline_logo_path + segmentDetail.AirlineDetails.AirlineCode + '.svg'}">
					</div>
					<div class="flitsmdets">
						${segmentDetail.AirlineDetails.AirlineName}
						<strong>${segmentDetail.AirlineDetails.AirlineCode} ${segmentDetail.AirlineDetails.FlightNumber}</strong>
					</div>
				</div>
		`);
	
		// Origin and Destination details
		htmlParts.push(`
			<div class="col-xs-8 nopad5">
				<div class="col-xs-5 text-center nopad">
					<div class="termnl">${segmentDetail.OriginDetails.CityName} (${segmentDetail.OriginDetails.AirportCode})<br>
						<span class="dateone">${formatDate(segmentDetail.OriginDetails.DateTime)}</span>
					</div>
				</div>
				<div class="col-xs-2 nopad">${loc_dir_icon}</div>
				<div class="col-xs-5 text-center nopad">
					<div class="termnl">${segmentDetail.DestinationDetails.CityName} (${segmentDetail.DestinationDetails.AirportCode})<br>
						<span class="dateone">${formatDate(segmentDetail.DestinationDetails.DateTime)}</span>
					</div>
				</div>
			</div>
		`);
	
		// Duration and Stopover details
		htmlParts.push(`
			<div class="col-xs-4 col-md-2 nopad5">
				<div class="ritstop">
					<div class="termnl tot_dur">${segmentDetail.SegmentDuration}</div>
					<div class="termnl1">Stop : ${segKey}</div>
				</div>
			</div>
		`);
	
		// Baggage and available seats information
		htmlParts.push('<div class="Baggage_block">');
		
		if (segmentDetail.Baggage) {
			var baggageInfo = formatLabel(segmentDetail.Baggage);
		}else{
			var baggageInfo = '0 kG';

		}
			htmlParts.push(`<div class="termnl1 flo_w"><em><i class="fa fa-suitcase bag_icon"></i>${baggageInfo}</em></div>`);
	
		if (segmentDetail.AvailableSeats) {
			htmlParts.push(`<div class="termnl1 flo_w"><em><i class="air_seat timings icseats"></i>${segmentDetail.AvailableSeats} seats</em></div>`);
		}
	
		if (segmentDetail.RBD) {
			//htmlParts.push(`<div class="termnl1 flo_w"><em>RBD: ${segmentDetail.RBD}</em></div>`);
		}

		if (segmentDetail.CabinType) {
			//htmlParts.push(`<div class="termnl1 flo_w"><em>CabinType: ${segmentDetail.CabinType}</em></div>`);
		}
	
		htmlParts.push('</div>'); // End baggage block
		htmlParts.push('</div>'); // End flight details wrapper
		if (next_seg_info) {
			htmlParts.push(`<div class="clearfix"></div>
							<div class="layoverdiv">
							<div class="centovr"><span class="fa fa-plane"></span>Plane change at ${next_seg_info.OriginDetails.CityName} | <span class="fa fa-clock-o"></span> Waiting: ${segmentDetail.WaitingTime}</div></div>
							<div class="clearfix"></div>`);
		}
		// Return the joined HTML string
		return htmlParts.join('');
	}
	var loc_dir_icon = '<div class="arocl fa fa-long-arrow-right"></div>';

	var emailcount = 0;

	function generate_flight(flightDetails, flightKey){

		//console.log(flightKey);

		var tripType = resultSummery.trip_type;
		var addFilterDate = (resultSummery.alt_days && ['oneway', 'circle'].includes(tripType)) ? true : false;

		//console.log(resultSummery); 

		

		var isDomestic = journeySummary.IsDomestic;
		var curProvabAuthKey = flightDetails.ProvabAuthKey;
		var curAirlineRemark = flightDetails.AirlineRemark.trim();
		var remarkSeparator = (curAirlineRemark != '') ? '| ' : '';
		var fare_key = module+'_PriceDetails';
		var curFareDetails = flightDetails.FareDetails[fare_key];
		var curSegmentDetails = flightDetails.SegmentDetails;
		var curSegmentSummary = flightDetails.SegmentSummary;
		var curIsRefundable = (flightDetails.Attr.IsRefundable == true) ? 1 : 0;
		var bookingSource = flightDetails.booking_source;
		//console.log(curIsRefundable); 
		var deptCat = flightDetails.deptCat;
		var arrCat = flightDetails.arrCat;

		var innerSummary = '';
		var outerSummary = '';
		var curOrigin = journeySummary.Origin;
		var curDestination = journeySummary.Destination;
		if (flightDetails.Attr && flightDetails.Attr.FareType) {
			if (flightDetails.Attr.AirlineRemark && flightDetails.Attr.AirlineRemark !== '') {
				curAirlineRemark = flightDetails.Attr.AirlineRemark;
			} else {
				curAirlineRemark = flightDetails.Attr.FareType;
			}
		}
		var avilSeats = '';
		var moreAs = '';

		// Baggage and available seats details
		if (curSegmentDetails[0][0].Baggage) {
			var bgInfo = formatLabel(curSegmentDetails[0][0].Baggage);
		}else{
			var bgInfo = '0 kG';

		}
			avilSeats += '<div class="termnl1 flo_w"><em><i class="fa fa-suitcase bag_icon"></i>' + bgInfo + '</em></div>';
		if (curSegmentDetails[0][0].AvailableSeats) {
			avilSeats += '<div class="termnl1 flo_w"><em><i class="air_seat timings icseats"></i>' + curSegmentDetails[0][0].AvailableSeats + '</em></div>';
		}
		const bsource = '';//`<div class="termnl1 flo_w text-success"><em>Source: ${bookingSource.slice(-2)=='17'?'VZT':'TMX'}</em></div>`;

		// More Info link
		if (curSegmentDetails[0].length > 1 && (curSegmentDetails[0][0].Baggage || curSegmentDetails[0][0].AvailableSeats)) {
			moreAs = '<span class="mfd"> ... More Info Click on Flight Details</span>';
		}

		var refundableLabel = (curIsRefundable) ? 'Refundable' : 'Non-Refundable';

		// Price details
		var baseFare = parseFloat(curFareDetails.BaseFare);
		var currencySymbol = curFareDetails.CurrencySymbol;
		var totalTax = curFareDetails.TotalTax;
		var totalFare = baseFare + totalTax;
		totalFare = totalFare.toFixed(2);

		var priceSingle = flightDetails.PassengerFareBreakdown.ADT.total_price_markup;
		var crs = bookingSource.substr(-2) +'_';
		var gen_id = String(crs) + String(rootIndicator) + String(flightKey)+ '_' + Date.now();;


		if (typeof currencyList !== 'undefined' && Array.isArray(currencyList) && currencyList.length > 0) {
			innerSummary += `<div id="sendmail_multi_${emailcount}${gen_id}" class="modal fade" role="dialog" data-id="${gen_id}">
            <div class="modal-dialog" style="margin: 200px auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" id="close_modal_multi${emailcount}${gen_id}">&times;</button>
                        <h4 class="modal-title mdltitl">Send Flight Information</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="email" class="form-control mfc" id="email_multi_${emailcount}${gen_id}" placeholder="Enter Email ID" name="email" />
                            <input type="hidden" class="form-control mfc" id="flightdetails_multi_${emailcount}${gen_id}" placeholder="Enter email" name="email" value="${base64EncodeUnicode(JSON.stringify(flightDetails))}" />
                            <span id="errormsg_multi_${emailcount}${gen_id}"></span>
                            <div id="send_email_loading_image_multi_${emailcount}${gen_id}" style="display: none;">
                                <div class="text-center loader-image" style="display: none;"><img src="${template_image_path}loader_v3.gif" alt="please wait" /></div>
                            </div>
                        </div>
                        <button type="button" id="send_email_btn_not_multi${emailcount}${gen_id}" class="btn btn-default flteml" onclick="sendflightdetails_multi('${emailcount}${gen_id}')">Send</button>
                    </div>
                </div>
            </div>
        </div>`;
		}

		//inner summery
		

			innerSummary += `<div class="propopum" id="fdp_${gen_id}">
				<div class="comn_close_pop closepopup">X</div>
				<div class="p_i_w">
					<div class="popuphed"><div class="hdngpops">${curOrigin} <span class="${arrowClass}"></span>${curDestination}</div></div>
					<div class="popconyent">
						<div class="contfare">
							<ul role="tablist" class="nav nav-tabs flittwifil">
								<li class="active" data-role="presentation"><a data-toggle="tab" datrole="tab" href="#iti_det_${gen_id}">Itinerary</a></li>
								<li data-role="presentation"><a data-toggle="tab" class="iti-fare-btn" role="tab" href="#fare_det${gen_id}" data-form-id="form-id-${gen_id}">Fare Details</a></li>
								<li data-role="presentation"><a data-toggle="tab" class="iti-fare-btn" role="tab" href="#fare_break_${gen_id}">Fare Breakup</a></li>
								<li data-role="presentation"><a data-toggle="tab" class="iti-fare-btn" role="tab" href="#baggage_inclusions_${gen_id}">Baggage & Inclusions</a></li>
							</ul>
							<div class="tab-content">
								<div id="iti_det_${gen_id}" class="tab-pane fade in active i-i-s-t">
									<div class="tabmarg">
										<div class="alltwobnd">
											<div class="col-xs-12 nopad full_wher">`;
												var stop_air_layover = [];

												$.each(curSegmentDetails, function(__segment_k, curSegmentDetail) {
													if (flightDetails.SegmentSummary[0].totalStops > 0) {


														//console.log(curSegmentDetail[0]);

														stop_air_layover.push(curSegmentDetail[0].DestinationDetails.AirportCode);
													}
													var segment_summary = curSegmentSummary[__segment_k];
													innerSummary += '<div class="inboundiv seg-' + __segment_k + '">';
														innerSummary += '<div class="hedtowr">'+ segment_summary.OriginDetails.CityName + ' to ' + segment_summary.DestinationDetails.CityName + ' <strong>(' + segment_summary.TotalDuaration + ')</strong></div>';

														$.each(curSegmentDetail, function(segKey, segmentDetail) { //console.log('segmentDetail:',segmentDetail);
															//var operating_airline_tag = segmentDetail.AirlineDetails.Operatedbyairline ? `<strong>${segmentDetail.AirlineDetails.Operatedbyairlinename}</strong>`:'';
															// Start building flight details
															var next_seg_info = segmentDetail.WaitingTime ? curSegmentDetail[segKey + 1]:'';
															innerSummary += flight_detail(segmentDetail, next_seg_info, segKey);
															
														});

													innerSummary += '</div>'; // End of segment wrapper
												});
											innerSummary +=`</div>
										</div>
									</div>
								</div>
								<div id="fare_det${gen_id}" class="tab-pane fade Fare_Rules i-i-f-s-t">
									<div class="text-center loader-image">Please Wait</div>
									<div class="i-s-s-c tabmarg"></div>
								</div>
								
								${fare_breakup(gen_id,currencySymbol,baseFare,totalTax,totalFare)}
								${baggage_inclusions(gen_id,curSegmentDetails)}
							</div>
						</div>
					</div>
				<div>
			<div>
		`;
		//outer summery
		outerSummary += '<div class="madgrid" data-key="' + rootIndicator + flightKey + '">';
			outerSummary += '<div class="f-s-d-w col-xs-8 nopad wayeght full_same">';
				// var total_stop_count = 0;
				// var min_total_stop_count = [];
				var segmentCount = curSegmentSummary.length;
				var deptDate = '';
				// var tStops=[];
				curSegmentSummary.forEach(function(segment, segmentIndex) {
					var add_date_filter = (addFilterDate && (segmentCount === 1 || (segmentCount === 2 && segmentIndex === 1))) ? true : false;
					var deptDate = (deptDate ? deptDate+'*':'')+segment.OriginDetails._Date;
					var totalStopCount = segment.totalStops;
					var stopImageName = totalStopCount > 4 ? 'more_stop.png':'stop_' + totalStopCount + '.png';
					var stopImage = resultSummery.template_image_path + stopImageName;
					var stop_air1 = '';
					var stop_air_codes = [];
					var layOvers = [];

					if (segment.totalStops > 0) {
						var total_segments = curSegmentDetails[segmentIndex].length;

						$.each(curSegmentDetails[segmentIndex], function(index, segmentD) {
							// Check if not the last segment
							if (index < total_segments - 1) {
								var stopCode = '<span data-toggle="tooltip" data-placement="bottom" title="' + segmentD['DestinationDetails']['CityName'] + '">' + segmentD['DestinationDetails']['AirportCode'] + '</span> - ' +
									curSegmentDetails[segmentIndex][index + 1]['AirlineDetails']['AirlineName'] + ' ' + 
									curSegmentDetails[segmentIndex][index + 1]['AirlineDetails']['AirlineCode'] + ' ' + 
									curSegmentDetails[segmentIndex][index + 1]['AirlineDetails']['FlightNumber'] + ' <b>(' + segmentD['WaitingTime'] + ')</b>';

								stop_air_codes.push(stopCode);
								layOvers.push('<span class="hide layover-duration" data-layoverduration="'+ segmentD.WaitingTime +'" data-layoverdurationhm="' + Math.floor(segmentD.WaitingTimeHM) +'"></span>');
							}
						});
						stop_air1 = '<div class="city_code1">' + stop_air_codes.join(' <br/> ') + '</div>';
					} else {
						layOvers.push('<span class="hide layover-duration" data-layoverduration="0" data-layoverdurationhm="0"></span>');
					}
					//min_total_stop_count = stop_air_codes.length;
					// tStops[segmentIndex]=stop_air_codes.length;
					outerSummary += `
						<div class="allsegments outer-segment-${segmentIndex}">
							<div class="quarter_wdth nopad col-xs-3">
								<div class="text_center_airline">
									<div class="fligthsmll">
										<img class="airline-logo" alt="${segment.AirlineDetails.AirlineCode + ' icon'}" src="${airline_logo_path + segment.AirlineDetails.AirlineCode + '.svg'}">
									</div>
									<div class="m-b-0 text-center">
										<div class="a-n airlinename" data-code="${segment.AirlineDetails.AirlineCode}">${segment.AirlineDetails.AirlineName}</div>
										<span style="display:block;">${segment.AirlineDetails.AirlineCode + ' ' + segment.AirlineDetails.FlightNumber}</span>
									</div>
								</div>
							</div>
							<div class="col-xs-3 nopad quarter_wdth">
								<div class="insidesame">
									<span class="fdtv hide" data-val="${segment.OriginDetails.DateTime}">${formatDateToTime(segment.OriginDetails.DateTime)}</span>
									<div class="f-d-t bigtimef">${segment.OriginDetails._DateTime}</div>
									<span ${(add_date_filter ? 'class="dept_dt flt_date" data-date="' + deptDate + '"' : 'class="flt_date"')}>
										${formatDateToWeekday(segment.OriginDetails.DateTime)}
									</span>
									<span class="dep_dt hide" data-category="${deptCat}"></span>
									<div class="from-loc smalairport_code">${segment.OriginDetails.AirportCode}</div>
									<div class="from-loc smalairport">${segment.OriginDetails.CityName + ' (' + segment.OriginDetails.AirportCode + ')'}</div>
								</div>
							</div>
							<div class="col-md-1 p-tb-10 hide">${loc_dir_icon}</div>
							<div class="smal_udayp nopad col-xs-3">
								<span class="f-d hide">${segment.TotalDuarationMinutes}</span>
								<div class="insidesame">
									<div class="durtntime">${segment.TotalDuaration}</div>
									<div class="stop_image"><img src="${stopImage}" alt="stop_0"></div>
									<div class="stop-value">Stop: ${totalStopCount}</div>
									<span class="slider_stop" data-slider_stop="${totalStopCount}"></span>
									${layOvers.join(' ')}
									<span class="hide org-airport" data-airport="${segment.OriginDetails.AirportCode}"></span>
									<span class="hide dst-airport" data-airport="${segment.DestinationDetails.AirportCode}"></span>
									<span class="hide layover-airport" data-airport="${stop_air_layover[0]}"></span>
									<div class="cabinclass hide">${resultSummery.cabin_class}</div>
								</div>
								${stop_air1}
							</div>
							<div class="col-xs-3 nopad quarter_wdth">
								<div class="insidesame">
									<span class="fatv hide">${formatDateToTime(segment.DestinationDetails.DateTime)}</span>
									<div class="f-a-t bigtimef">${segment.DestinationDetails._DateTime}</div>
									<span class="flt_date">${formatDateToWeekday(segment.DestinationDetails.DateTime)}</span>
									<span class="arr_dt hide" data-category="${arrCat}"></span>
									<div class="to-loc smalairport">${segment.DestinationDetails.CityName} (${segment.DestinationDetails.AirportCode})</div>
									<div class="smalairport_code">${segment.DestinationDetails.AirportCode}</div>
								</div>
							</div>
						</div>
					`;
				});
			outerSummary +='</div>';
			var single_price = priceSingle.split('.');
			var t_price = totalFare.split('.');
			var ref = (parseInt(curIsRefundable) === 1) ? 'refndble' : 'nre';
			const sessID = resultSummery?.session_id || '';
			const ShoppingResponseId = resultSummery?.ShoppingResponseId || '';
			outerSummary += `
				<div class="col-xs-4 nopad wayfour full_same">
					<span class="hide stp stps" data-category="${flightDetails.totalStops}">${flightDetails.totalStops}</span>
					<div class="priceanbook">
						<div class="col-xs-6 nopad wayprice">
							<div class="insidesame">
								<div class="priceflights"><strong>${currencySymbol}</strong><span class="f-p">${formatIndianNumber(t_price[0])}<small>.${t_price[1]}</small></span></div>
								<span class="hide price" data-price="${t_price}" data-currency="${currencySymbol}"></span>
								<div data-val="${parseInt(curIsRefundable)}" data-code="${refundableLabel}" class="n-r n-r-t ${ref} hide">${refundableLabel}</div>
							</div>
						</div>
						<div class="col-xs-6 nopad waybook">
							<div class="form-wrapper bookbtlfrt">
								<form method="POST" target="_blank" id="form-id-${gen_id}" action="${resultSummery.booking_url}" class="book-form-wrapper">
									${booking_form(isDomestic, flightDetails.Token, flightDetails.TokenKey, curProvabAuthKey, bookingSource, sessID, ShoppingResponseId)}
									${IsDomesticRoundway? '<button class="bookallbtn mfb-btn" type="button">Select</button>'
						                : '<button class="b-btn bookallbtn" type="submit" onclick="showLoader();">Book Now </button>'
						        	}
								</form>
							</div>
						</div>
					</div>
					${resultSummery.total_pax > 1 ? `<div class="col-xs-12 trvlrs_amt_wrappr nopad"><div class="text-center trvlrs_amt">${currencySymbol} <span class="travl_amt_lrg">${formatIndianNumber(t_price[0])}</span>.<span class="travl_amt_sml">${t_price[1]}</span> <span class="all_trvlrs">for all travellers</span></div></div>` : ''}
				</div>
			`;

			var sendmail = '';

			if (typeof currencyList !== 'undefined' && Array.isArray(currencyList) && currencyList.length > 0) {
				sendmail += `<a class="detailsflt iti-btn" data-toggle="modal" data-target="#sendmail_multi_${emailcount}${gen_id}" data-backdrop="static"><span class="fal fa-envelope"></span> Send Mail</a>`;
			}



			outerSummary += `
				<div class="clearfix"></div>
				<div class="mrinfrmtn">
					<a class="detailsflt iti-btn" data-id="fdp_${gen_id}"><span class="badge"></span><span class="fal fa-info-circle fldetail" data-toggle="tooltip" title="" data-original-title="Flight Itinerary"></span>Flight Details ${remarkSeparator}</a>
					<i class="hide">${curAirlineRemark}</i> <i class="fset-bag">${avilSeats}${moreAs}${bsource}</i>
					${sendmail}					 
				</div>
			`;
		outerSummary +='</div>';

		//console.log(second_parent);

		var flightHTML = `<div class="rowresult p-0 r-r-i t-w-i-${rootIndicator}">${outerSummary + innerSummary}</div>`;
		second_parent.append(flightHTML);
		flightKey == 0 && hide_result_pre_loader();

		emailcount++;
		
	}

	let currentPage = 1;
	const recordsPerPage = 20;
	var second_parent;
	var journeySummary;
	var rootIndicator;
	var resultSummery;
	var airline_logo_path='';
	var airlinesData=[];
	var totalPages=0;
	var IsDomesticRoundway = false;

	var module;
	window.addEventListener('scroll', function() {
		if(flightListFiltered.length === 0) return false;
		if(totalPages <= currentPage) return false;
		// Check if the user has scrolled to the bottom
		var scrollPos = $(window).scrollTop();
		var windowHeight = $(window).height();
		var docHeight = $(document).height(); //console.log('cp:',currentPage,'--tp:',totalPages,'--tl:',flightListFiltered.length);
		if (scrollPos + windowHeight >= docHeight - 200) {
			currentPage++;

			//console.log(IsDomesticRoundway);
			// Pagination not available for Domestic Round Trip
			if(!IsDomesticRoundway)
			{
				//console.log('scroll');
				loadFlights(currentPage);
			}
			
		}
	});
	function loadFlights(page) {

		//console.log(flightListFiltered);

		if(page===1){
			totalPages = Math.ceil(flightListFiltered.length / recordsPerPage);
		}
		const startIndex = (page - 1) * recordsPerPage;//console.log('startIndex:',startIndex);
		const endIndex = page * recordsPerPage;//console.log('endIndex:',endIndex);


		if(IsDomesticRoundway)
		{
			//console.log('loadFlights');

			const flightsToDisplay = flightListFiltered;
			flightsToDisplay.forEach(function(flightDetails, flightKey)
			{
					generate_flight(flightDetails, startIndex+flightKey);
			});
		}
		else
		{
			const flightsToDisplay = flightListFiltered.slice(startIndex, endIndex);
			 //console.log("cPage:",page,' | startIndex:',startIndex,' | endIndex:',endIndex, ' | count:',flightsToDisplay.length);
			flightsToDisplay.forEach(function(flightDetails, flightKey) {
			  	 setTimeout(function() {
					generate_flight(flightDetails, startIndex+flightKey);
				}, 0);
			});
		}
		
		//add_unpublished_strip();

	}

	function filter_row_origin_marker() {
			loader();
			visibility = '';
			//get all the search criteria
			var stopCountList = getSelectedStops();
			var airlineList = $('input.airlinecheckbox:checked:not(:disabled)', '#allairlines').map(function() {
				return this.value;
			}).get();
			var deptimeList = $('.time-category:checked:not(:disabled)', '#departureTimeWrapper').map(function() {
				return parseInt(this.value);
			}).get();

			var originAirportList = $('input.originairportcheckbox:checked:not(:disabled)', '#originairport').map(function () {
			return this.value;
			}).get();

			var destinationAirportList = $('input.dstairportcheckbox:checked:not(:disabled)', '#destinationairport').map(function () {
			return this.value;
			}).get();

			var layoverAirportList = $('input.layovercheckbox:checked:not(:disabled)', '#layovers').map(function () {
			return this.value;
			}).get();

			// alert(deptimeList);
			var arrtimeList = $('.time-category:checked:not(:disabled)', '#arrivalTimeWrapper').map(function() {
				return parseInt(this.value);
			}).get();
			var min_price = parseFloat($("#slider-range").slider("values")[0]);
			var max_price = parseFloat($("#slider-range").slider("values")[1]);

			var min_lay = parseFloat($("#slider-range-lay").slider("values")[0]);
			var max_lay = parseFloat($("#slider-range-lay").slider("values")[1]);

			//console.log(min_lay);
			//console.log(max_lay);


			// var minLay = parseFloat($('#lay_minimum_range_value', '#lay_min_max_slider_values').val());
			// var maxLay = parseFloat($('#lay_maximum_range_value', '#lay_min_max_slider_values').val());

			// const layoverCheck = min_lay !== minLay || max_lay !== maxLay;



			//(parseFloat($('.layover-waiting', this).data('waiting')) >= min_lay && parseFloat($('.layover-waiting', this).data('waiting')) <= max_lay)&&

			

			$('.r-r-i' + visibility).each(function(key, value) {
				if (((airlineList.length == 0) || ($.inArray($('.a-n:first', this).data('code'), airlineList) != -1)) &&
					((stopCountList.length == 0) || ($.inArray(parseInt($('.stp:first', this).data('category')), stopCountList) != -1)) &&
				
					((originAirportList.length == 0) || ($.inArray($('.org-airport:first', this).data('airport'), originAirportList) != -1)) &&
					((destinationAirportList.length == 0) || ($.inArray($('.dst-airport:first', this).data('airport'), destinationAirportList) != -1)) &&
					((layoverAirportList.length == 0) || ($.inArray($('.layover-airport:first', this).data('airport'), layoverAirportList) != -1)) &&
					((deptimeList.length == 0) || ($.inArray(parseInt($('.dep_dt:first', this).data('category')), deptimeList) != -1)) &&
					((arrtimeList.length == 0) || ($.inArray(parseInt($('.arr_dt:first', this).data('category')), arrtimeList) != -1)) &&
	
					(parseFloat($('.layover-duration:first', this).data('layoverdurationhm')) >= min_lay && parseFloat($('.layover-duration:first', this).data('layoverdurationhm')) <= max_lay) &&
					(parseFloat($('.price:first', this).data('price')) >= min_price && parseFloat($('.price:first', this).data('price')) <= max_price)
				) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
			update_total_count_summary();
		}

	function apply_filters()
	{
	 	console.log('apply_filters');

		if(IsDomesticRoundway)
		{
			filter_row_origin_marker();
		}
		else
		{	

			loader();
			visibility = '';
			var allFlights = flightList;
			//get all the search criteria
			var stopCountList = getSelectedStops(); //console.log('stopCountList',stopCountList);
			var airlineList = $('input.airlinecheckbox:checked:not(:disabled)', '#allairlines').map(function () {
				return this.value;
			}).get();//console.log('selairlineList',airlineList);
			var flighttypelist = $('input.flighttypecheckbox:checked:not(:disabled)', '#allflighttype').map(function () {
				return this.value === "true" ? true : this.value === "false" ? false : this.value;
			}).get();
			var deptimeList = $('.time-category:checked:not(:disabled)', '#departureTimeWrapper').map(function () {
				return parseInt(this.value);
			}).get();
			var arrtimeList = $('.time-category:checked:not(:disabled)', '#arrivalTimeWrapper').map(function () {
				return parseInt(this.value);
			}).get();
			var originAirportList = $('input.originairportcheckbox:checked:not(:disabled)', '#originairport').map(function () {
				return this.value;
			}).get();
			var destinationAirportList = $('input.dstairportcheckbox:checked:not(:disabled)', '#destinationairport').map(function () {
				return this.value;
			}).get();
			var LayoverAirportList = $('input.layovercheckbox:checked:not(:disabled)', '#layovers').map(function () {
				return this.value;
			}).get();

			var sel_dept_date = $('.date-filter.active').length > 0 ? $('.date-filter.active').data('category') : '';

			var min_price = Math.floor($("#slider-range").slider("values")[0]);
			var max_price = Math.ceil($("#slider-range").slider("values")[1]);

			var min_lay = parseFloat($("#slider-range-lay").slider("values")[0]);
			var max_lay = parseFloat($("#slider-range-lay").slider("values")[1]);

			var minLay = parseFloat($('#lay_minimum_range_value', '#lay_min_max_slider_values').val());
			var maxLay = parseFloat($('#lay_maximum_range_value', '#lay_min_max_slider_values').val());

			const layoverCheck = min_lay !== minLay || max_lay !== maxLay;
			//console.log('sel lover:',min_lay,'--',max_lay,':',minLay,'--',maxLay,'--check:',layoverCheck);
			allFlights = allFlights.filter(function(item) {
				//const currentPrice = parseFloat(item.PassengerFareBreakdown.ADT.total_price_markup); 
				var fare_key = module+'_PriceDetails';
        		const currentPrice = parseFloat(item.FareDetails[fare_key].TotalFare).toFixed(2); 

				var lovers = item.SegmentSummary[0].Stopdetails.map(itm => itm && itm.AirportCode).filter(Boolean);


				// console.log(stopCountList);
				// console.log(item);

				if(layoverCheck){
					const loversDurations = item.SegmentDetails.flatMap(segments => segments.map(segment => segment.hasOwnProperty("WaitingTimeHM")?segment.WaitingTimeHM :(segments.length==1?0:''))).filter(waitingTime => waitingTime !== '');
					// const loversDurations = item.SegmentDetails[0].map(segment => segment.hasOwnProperty("WaitingTimeHM")?segment.WaitingTimeHM :0).filter(waitingTime => waitingTime !== 0);
					//console.log('loversDurations',loversDurations,item.SegmentDetails);
					if(loversDurations.length == 0){
						return false;
					}
					const isValidDuration = loversDurations.every(duration => duration >= min_lay && duration <= max_lay);
					//const isValidDuration = loversDurations.some(duration => duration >= min_lay && duration <= max_lay);
					if(!isValidDuration){
						return false;
					}
				}

				//console.log(layoverAirportList.some(tem => lovers.includes(tem)),(layoverAirportList.length > 0 && !layoverAirportList.some(tem => lovers.includes(tem))));

				// Combine all filters into a single early-exit check
				if (
					!(currentPrice >= min_price && currentPrice <= max_price) ||
					(airlineList.length > 0 && !airlineList.includes(item.SegmentSummary[0].AirlineDetails.AirlineCode)) ||
					(stopCountList.length > 0 && !stopCountList.includes(item.totalStops)) ||
					(deptimeList.length > 0 && !deptimeList.includes(item.deptCat)) ||
					(arrtimeList.length > 0 && !arrtimeList.includes(item.arrCat)) ||
					(flighttypelist.length > 0 && !flighttypelist.includes(item.Attr.IsRefundable)) ||
					(originAirportList.length > 0 && !originAirportList.includes(item.SegmentSummary[0].OriginDetails.AirportCode)) ||
					(destinationAirportList.length > 0 && !destinationAirportList.includes(item.SegmentSummary[0].DestinationDetails.AirportCode)) ||
					(LayoverAirportList.length > 0 && !LayoverAirportList.some(tem => lovers.includes(tem))) ||
					(sel_dept_date && sel_dept_date !== item.dateHash)
				) {
					return false; // Filter out the flight if any condition fails
				}
				return true;
			});
			 //console.log("fallFlights",allFlights);
			flightListFiltered = allFlights;
			second_parent.empty();
			$('#total_records').text(flightListFiltered.length);
			$('#flights_text').text('Flight'+(flightListFiltered.length !=1 ?'s':''));
			if(flightListFiltered.length > 0){
				$('#flight_search_result').show();
				$('#empty_flight_search_result').hide();
			}else{
				$('#flight_search_result').hide();
				$('#empty_flight_search_result').show();
			}
			currentPage=1;
			loadedList=0;
			loadFlights(1);
		}

	}

	var total_api_count=0;
	var api_result_counter=0;
	

	/*
	window.generate_flight_list = function(result,api_count){
		total_api_count = api_count;
		api_result_counter++;
		if(api_result_counter == api_count && FlightsRaw.length == 0 && !result.hasOwnProperty('raw_flight_list')){
			$('#empty-search-result').show();
			$('#page-parent').hide();
			return false;
		}
		if(!result.hasOwnProperty('raw_flight_list')){
			return false;
		}
		//console.log('res type:',typeof result, result);
		var flightData = result.raw_flight_list || []; //console.log('res type flightData:',typeof flightData);
		// Assuming flightData has been passed to JS from PHP
		journeySummary = flightData.JourneySummary;
		var isDomestic = journeySummary.IsDomestic;
		if(FlightsRaw.length>0){
			var max_len = Math.max(FlightsRaw.length, flightData.Flights.length);
			let merged = [];
			for (let i = 0; i < max_len; i++) {
				// Use ternary operator to safely access the elements in case the index is out of bounds
				let flightsRawElement = FlightsRaw[i] || [];  // Default to an empty array if out of bounds
				let secondsApiElement = flightData.Flights[i] || [];  // Default to an empty array if out of bounds
				
				// Merge the arrays
				let mergedParent = [...flightsRawElement, ...secondsApiElement];

				// Sort the merged array by 'price' in ascending order
  				mergedParent.sort((a, b) => parseFloat(a.PassengerFareBreakdown.ADT.total_price_markup) - parseFloat(b.PassengerFareBreakdown.ADT.total_price_markup));

				merged.push(mergedParent);
			}
			FlightsRaw = merged;
		}else{
			FlightsRaw = flightData.Flights;
		}
		if(api_result_counter != api_count){
			//return false; // wait for all api responses
		}
		var flightsData = FlightsRaw;
		*/

	window.generate_flight_list = function(result, api_count) {
    total_api_count = api_count;
    api_result_counter++;

    if (api_result_counter == api_count && FlightsRaw.length == 0 && !result.hasOwnProperty('raw_flight_list')) {
        $('#empty-search-result').show();
        $('#page-parent').hide();
        return false;
    }

    if (!result.hasOwnProperty('raw_flight_list')) {
        return false;
    }

    var flightData = result.raw_flight_list || {};
     journeySummary = flightData.JourneySummary || {};
    var isDomestic = journeySummary.IsDomestic || false;
     IsDomesticRoundway = journeySummary.IsDomesticRoundway || false;

    //console.log(journeySummary);

    // Loop through all indexes inside Flights (0, 1, etc.)
    if (flightData.Flights && Array.isArray(flightData.Flights)) {
        // Initialize if empty
        if (FlightsRaw.length === 0) FlightsRaw = [];

        flightData.Flights.forEach((flightGroup, index) => {
            if (!Array.isArray(flightGroup)) return; // skip invalid

            // If FlightsRaw already has data for this index, merge and sort
            if (FlightsRaw[index] && Array.isArray(FlightsRaw[index])) {
                let merged = [...FlightsRaw[index], ...flightGroup];
                /*merged.sort((a, b) => 
                    parseFloat(a.PassengerFareBreakdown.ADT.total_price_markup) - 
                    parseFloat(b.PassengerFareBreakdown.ADT.total_price_markup)
                );*/
                var fare_key = module + '_PriceDetails';
                merged.sort((a, b) => 
                    parseFloat(a.FareDetails[fare_key].TotalFare) - 
                    parseFloat(b.FareDetails[fare_key].TotalFare)
                );
                FlightsRaw[index] = merged;
            } else {
                // Otherwise, just assign directly
                FlightsRaw[index] = flightGroup;
            }
        });
    }

    if (api_result_counter != api_count) {
        //return false; // wait for all api responses
    }

    var flightsData = FlightsRaw;

   

    //console.log(flightsData);


		//console.log(template_image_path);
		
		var routeCount = result.route_count; //console.log('routeCount:',routeCount); 
		tripType = result.trip_type; //console.log('tripType:',tripType);  // e.g. 'oneway', 'circle'
		var domesticRoundWayFlight = flightData.domesticRoundWayFlight;
		var bookingSource = flightData.bookingSource;
		airline_logo_path = result.logo_path+ 'airline_logo/';
		module = result.module

		// Dividing cols
		var colParentDivision = '';
		var specialClass = '';
		var colDivision = '';

		//var addFilterDate = (result.alt_days && ['oneway', 'circle'].includes(tripType)) ? true : false;
	
		if (routeCount == 1) {
			colDivision = 'rondnone';
			specialClass = 'one_way_only';
	
			if (tripType != 'oneway') {
				colParentDivision = 'round-trip';
				arrowClass = 'fa fa-exchange';
			} else {
				arrowClass = 'fa fa-long-arrow-right';
			}
			var divDom = '';
		} else if (routeCount == 2) {
			colDivision = 'rondnone';
			colParentDivision = 'round-domestk';
			arrowClass = 'fa fa-long-arrow-right';
	
			divDom = '<div class="dom_tab"><div class="dom_tab_div"> <a class="active" href="#" id="show_inbound">' + journeySummary.Origin + ' to ' + journeySummary.Destination + '</a> <a href="#" id="show_outbound">' + journeySummary.Destination + ' to ' + journeySummary.Origin + '</a></div></div>';
			divDom += '<div class="fixincrmnt" id="multi-flight-summary-container"><div class="insidecurent"><div class="col-xs-10 nopad"><div class="col-xs-6 nopad"><div class="selctarln colorretn"><div class="col-xs-3 nopad flightimage"><div class="fligthsmll"><img class="departure-flight-icon" src="/extras/system/library/images/airline_logo/6E.svg" alt=""></div><div class="airlinename departure-flight-name">IndiGo</div></div><div class="col-xs-9 nopad listfull"><div class="sidenamedesc"><div class="celhtl width80"><div class="waymensn"><div class="flitruo"><div class="outbound-details"><div class="topnavi"><div class="col-xs-4 padflt widftysing"><span class="flitrlbl elipsetool">DEL</span></div><div class="col-xs-4 padflt nonefitysing"><img src="/extras/system/template_list/template_v3/images/stop_0.png" alt="stop_image"></div><div class="col-xs-4 padflt widftysing"><span class="flitrlbl elipsetool text_algn_rit">Bengaluru (BLR)</span></div></div></div><div class="detlnavi outbound-timing-details"><div class="col-xs-4 padflt widfty"><span class="timlbl departure">05:30</span></div><div class="col-xs-4 padflt nonefity"><div class="lyovrtime"><span class="flect duration">2h 55m </span><div class="flect stop-image"></div><span class="flect stop-count">Stop:0</span></div></div><div class="col-xs-4 padflt widfty"><span class="timlbl arrival text_algn_rit">08:25</span></div></div></div></div></div></div></div></div></div><div class="col-xs-6 nopad"><div class="selctarln cloroutbnd"><div class="col-xs-3 nopad flightimage"><div class="fligthsmll"><img class="arrival-flight-icon" src="/extras/system/library/images/airline_logo/SG.svg" alt=""></div><div class="airlinename arrival-flight-name">SpiceJet</div></div><div class="col-xs-9 nopad listfull"><div class="sidenamedesc"><div class="celhtl width80"><div class="waymensn"><div class="flitruo"><div class="inbound-details"><div class="topnavi"><div class="col-xs-4 padflt widftysing"><span class="flitrlbl elipsetool">BLR</span></div><div class="col-xs-4 padflt nonefitysing"><img src="/extras/system/template_list/template_v3/images/stop_0.png" alt="stop_image"></div><div class="col-xs-4 padflt widftysing"><span class="flitrlbl elipsetool text_algn_rit">Delhi (DEL)</span></div></div></div><div class="detlnavi inbound-timing-details"><div class="col-xs-4 padflt widfty"><span class="timlbl departure">05:55</span></div><div class="col-xs-4 padflt nonefity"><div class="lyovrtime"><span class="flect duration">2h 50m </span><div class="flect stop-image1"></div><span class="flect stop-count">Stop:0</span></div></div><div class="col-xs-4 padflt widfty"><span class="timlbl arrival text_algn_rit">08:45</span></div></div></div></div></div></div></div></div></div></div><div class="col-xs-2 nopad"><div class="sidepricewrp"><div class="col-xs-12 nopad"><div class="sidepricebig"><strong class="currency">$</strong> <span class="f-p">232.25</span></div></div><div class="col-xs-12 nopad"><div class="bookbtn"><input type="hidden" id="flight-from-price" value="116.25"><input type="hidden" id="flight-to-price" value="116.00"><form id="multi-flight-form" action="" method="POST"><div class="hide" id="trip-way-wrapper"></div><button class="btn-flat booknow" type="submit" id="multi-flight-booking-btn" onclick="showLoader1();">Book</button></form></div></div></div></div></div></div>';
		}
	
		// Change booking button based on type of flight
		var bookingButton;
		if (IsDomesticRoundway) {
			bookingButton = '<button class="bookallbtn mfb-btn" type="button">Select</button>';  // Multi flight booking
		} else {
			bookingButton = '<button class="b-btn bookallbtn" type="submit" id="flight_book_now" onclick="showLoader();">Book Now </button>';
		}
		//var loc_dir_icon = '<div class="arocl fa fa-long-arrow-right"></div>';
		var first_parent = $('<div></div>', {class: 'row ' + colParentDivision});
		$('#flight_search_result').empty().append(first_parent);
		var totalTasks = 0;
		var completedTasks = 0;

		if(flightsData.length == 2)
		{


			    // --- Filters and Data Collection ---
		    var airlineList = new Set();
		    var airlineData = new Set();
		    var airlineCList = new Set();
		    var airlineStops = new Set();
		    var depCatList = new Set();
		    var arrCatList = new Set();
		    var refundTypes = new Set();
		    var originList = new Set();
		    var destinationList = new Set();
		    var layoverList = new Set();
		    var prices = new Set();
		    var bestPrice = null;
		    var directPrice = null;
		    let altdays = $('.date-filter-tab').length > 0;
		    var dateFilters = {};
		    if (altdays) {
		        dateFilters = $('a.date-filter').map(function () {
		            return { [$(this).data('category')]: null };
		        }).get();
		    }
		    let cheapestPrice = '';

			
			//flightsData = flightsDataNew;
			flightsData.forEach(function(flight, tripIndicator) 
			{


				 flightList = [];
				 flightListFiltered = [];
				 resultSummery = [];
   			 rootIndicator = tripIndicator + 1;
    		const containerId = 't-w-i-' + rootIndicator;

    		//console.log(rootIndicator);

    	// ✅ Check if container exists; if not, create dynamically
    	 second_parent = $('#' + containerId);



	     //if (second_parent.length === 0) {
        second_parent = $('<div></div>', {
            class: 'rondnone r-w-g nopad',
            id: containerId
        });
        $('.round-domestk').append(second_parent); // append dynamically
    	//}

    	//first_parent.append(second_parent);

    // Preserve your existing logic
    totalTasks = flight.length;
    flightList = flight;
    flightListFiltered = flight;
    resultSummery = result;
    //delete resultSummery.raw_flight_list;

    $('#total_records').text(flightList.length);
    $('#flights_text').text('Flight' + (flightList.length !== 1 ? 's' : ''));


    flightList.forEach(function(item) {
        const segmentSummary = item.SegmentSummary[0];
        const airlineDetails = segmentSummary.AirlineDetails;
        const currentPrice = parseFloat(item.PassengerFareBreakdown.ADT.total_price_markup).toFixed(2);
        //added for display on seach and booking price same on 19-12-2025
        var fare_key = module+'_PriceDetails';
        const currentPrice_1 = parseFloat(item.FareDetails[fare_key].TotalFare).toFixed(2); 
        const currentStops = segmentSummary.totalStops;
        const totalDuarationMinutes = segmentSummary.TotalDuarationMinutes;

        airlineList.add(airlineDetails.AirlineName);
        airlineCList.add(airlineDetails.AirlineCode);
        airlineStops.add(currentStops);
        depCatList.add(item.deptCat);
        arrCatList.add(item.arrCat);
        refundTypes.add(item.Attr.IsRefundable);

        originList.add(JSON.stringify({
            AirportCode: segmentSummary.OriginDetails.AirportCode,
            CityName: segmentSummary.OriginDetails.CityName,
            AirportName: segmentSummary.OriginDetails.AirportName
        }));

        destinationList.add(JSON.stringify({
            AirportCode: segmentSummary.DestinationDetails.AirportCode,
            CityName: segmentSummary.DestinationDetails.CityName,
            AirportName: segmentSummary.DestinationDetails.AirportName
        }));

        segmentSummary.Stopdetails.forEach(function(stopDetail) {
            layoverList.add(JSON.stringify(stopDetail));
        });

        airlineData.add({
            name: airlineDetails.AirlineName,
            code: airlineDetails.AirlineCode,
            price: currentPrice_1,
            stops: currentStops,
            cat: currentStops > 1 ? 2 : 1
        });

        prices.add(currentPrice_1);

        if (!bestPrice || currentStops < bestPrice.stops || (currentStops === bestPrice.stops && currentPrice_1 < bestPrice.price)) {
            bestPrice = { price: currentPrice_1, stops: currentStops };
        }
        if (!directPrice || currentStops < directPrice.stops || (currentStops === directPrice.stops && totalDuarationMinutes < directPrice.duration)) {
            directPrice = { price: currentPrice_1, stops: currentStops, duration: totalDuarationMinutes };
        }

        if (altdays) {
            const dateHash = item.dateHash;
            if (!(dateHash in dateFilters) || currentPrice_1 < dateFilters[dateHash].price) {
                let stops_str = 'D: ' + currentStops + ' Stop' + (currentStops !== 1 ? 's' : '');
                if (item.SegmentSummary.length === 2) {
                    let arrivalStops = item.SegmentSummary[1].totalStops;
                    stops_str += ' | A: ' + arrivalStops + ' Stop' + (arrivalStops !== 1 ? 's' : '');
                }
                dateFilters[dateHash] = { price: currentPrice_1, stops: stops_str };
                if (!cheapestPrice || currentPrice_1 < cheapestPrice) {
                    cheapestPrice = currentPrice_1;
                }
            }
        }
    });

    // --- Convert Sets to Arrays ---
    var airlineListArr = Array.from(airlineList).sort();
    var airlineCListArr = Array.from(airlineCList);
    var airlineDataArr = Array.from(airlineData);
    var airlineStopsArr = Array.from(airlineStops);
    var depCatListArr = Array.from(depCatList);
    var arrCatListArr = Array.from(arrCatList);
    var refundTypesArr = Array.from(refundTypes);
    var originListArr = Array.from(originList).map(JSON.parse).sort((a, b) => a.CityName.localeCompare(b.CityName));
    var destinationListArr = Array.from(destinationList).map(JSON.parse).sort((a, b) => a.CityName.localeCompare(b.CityName));
    var layoverListArr = Array.from(layoverList).map(JSON.parse).sort((a, b) => a.CityName.localeCompare(b.CityName));
    var pricesArr = Array.from(prices);
//console.log(pricesArr);
    // --- Load filters & UI updates ---
    var airlinesData = getMinPriceByCodeAndCat(airlineDataArr);

    cloneSliderr(airlinesData);
    loadAirlineFilter(airlinesData);
    loadStopFilterr(airlineStopsArr);
    loadTimeRangeSelectorr(depCatListArr, arrCatListArr);
    loadFlighttypeFilterr(refundTypesArr);
    LoadLayoverAirportListt(layoverListArr);
    LoadOriginAirportListt(originListArr);
    LoadDestinationAirportListt(destinationListArr);

    //console.log('#' + containerId);

    // --- Load Flights for this section ---
    // Pass container context dynamically so flights render inside each trip section
    loadFlights(currentPage);

    // --- Price Filters ---
    var minPrice = Math.min.apply(null, pricesArr).toFixed(2);
    var maxPrice = Math.max.apply(null, pricesArr).toFixed(2);
    loadPriceRangeSelector(Math.floor(minPrice), Math.ceil(maxPrice));

    // --- Layover Filters ---
    var waitingTimes = [...new Set(
        flightList.flatMap(flight =>
            flight.SegmentDetails.flatMap(segment =>
                segment.filter(singleAirline => singleAirline.WaitingTimeHM)
                       .map(singleAirline => singleAirline.WaitingTimeHM)
            )
        )
    )];

    var minLay = Math.min.apply(null, waitingTimes);
    var maxLay = Math.max.apply(null, waitingTimes);
    loadLayRangeSelector(0, Math.ceil(maxLay));

    // --- Update price UI ---
    $('#core_minimum_range_value', '#core_min_max_slider_values').val(minPrice);
    $('#core_maximum_range_value', '#core_min_max_slider_values').val(maxPrice);
    $('#lay_minimum_range_value', '#lay_min_max_slider_values').val(minLay);
    $('#lay_maximum_range_value', '#lay_min_max_slider_values').val(maxLay);

    $('.cheapest .price').html(`${sliderCurrency} ${formatIndianNumber(minPrice)}`);
    $('.best .price').html(`${sliderCurrency} ${formatIndianNumber(minPrice)}`);
    $('.shortest .price').html(`${sliderCurrency} ${formatIndianNumber(directPrice.price)}`);
    $('.flexiable .price').html(`${sliderCurrency} ${formatIndianNumber(minPrice)}`);

    // --- Update alternate dates ---
    if (altdays && Object.keys(dateFilters).length > 0) {
        for (var dateHash in dateFilters) {
            var $dateFilter = $('.date-filter[data-category="' + dateHash + '"]');
            if (dateFilters.hasOwnProperty(dateHash) && $dateFilter.length > 0) {
                var price = dateFilters[dateHash].price;
                var stops = dateFilters[dateHash].stops;
                var priceParts = price.toString().split('.');
                $dateFilter.find('.price').html(sliderCurrency + ' ' + priceParts[0] + '<small>.' + (priceParts[1] || '00') + '</small>');
                $dateFilter.find('.price').after('<span class="stops">' + stops + '</span>');
                if (price == cheapestPrice) {
                    $dateFilter.addClass('cheapest-price');
                }
            }
        }
    }
});


		}
		else
		{
			//flightsData = flightsDataNew[0];

			 flightList = [];
			 flightListFiltered = [];
			 resultSummery = [];

			flightsData.forEach(function(flight, tripIndicator) {
			rootIndicator = tripIndicator + 1;
			second_parent = $('<div></div>', {class: colDivision + ' r-w-g nopad ' + specialClass});
			first_parent.append(second_parent);
			//flight = flightt.slice(0, 2);console.log('flight:',flight);
			totalTasks = flight.length;
			flightList = flight;
			flightListFiltered = flight;
			resultSummery = result;
			delete resultSummery.raw_flight_list;
			
			$('#total_records').text(flightList.length);
			$('#flights_text').text('Flight'+(flightList.length !=1 ?'s':''));
			
			//airline list
			var airlineList = new Set();
			var airlineData = new Set();
			var airlineCList = new Set();
			var airlineStops = new Set();
			var depCatList = new Set();
			var arrCatList = new Set();
			var refundTypes = new Set();
			var originList = new Set();
			var destinationList = new Set();
			var layoverList = new Set();
			var prices = new Set();
			var bestPrice = null;
			var directPrice = null;
			let altdays = $('.date-filter-tab').length > 0;
			var dateFilters = {};
			if(altdays){
				var dateFilters = $('a.date-filter').map(function () {
					return {[$(this).data('category')] : null};
				}).get();
				// console.log('date-filters:',$('a.date-filter').length,dateFilters);
			}
			let datesDataMap = new Map();
			let cheapestPrice = '';
			// Iterate over the data and collect unique airline names
			flightList.forEach(function(item) {
				const airlineDetails = item.SegmentSummary[0].AirlineDetails;
    			const segmentSummary = item.SegmentSummary[0];
				//const currentPrice = parseFloat(item.PassengerFareBreakdown.ADT.total_price_markup).toFixed(2);
				var fare_key = module+'_PriceDetails';
        		const currentPrice = parseFloat(item.FareDetails[fare_key].TotalFare).toFixed(2); 
    			const currentStops = segmentSummary.totalStops;
				const totalDuarationMinutes = segmentSummary.TotalDuarationMinutes;
				airlineList.add(airlineDetails.AirlineName);
				airlineCList.add(airlineDetails.AirlineCode);
				airlineStops.add(currentStops);
				depCatList.add(item.deptCat);
				arrCatList.add(item.arrCat);
				refundTypes.add(item.Attr.IsRefundable);
				originList.add(JSON.stringify({
					AirportCode:segmentSummary.OriginDetails.AirportCode,
					CityName:segmentSummary.OriginDetails.CityName,
					AirportName:segmentSummary.OriginDetails.AirportName
				}));
				destinationList.add(JSON.stringify({
					AirportCode:segmentSummary.DestinationDetails.AirportCode,
					CityName:segmentSummary.DestinationDetails.CityName,
					AirportName:segmentSummary.DestinationDetails.AirportName
				}));
				segmentSummary.Stopdetails.forEach(function(stopDetail) {
					layoverList.add(JSON.stringify(stopDetail));
				});
				airlineData.add({
					name: airlineDetails.AirlineName,
					code: airlineDetails.AirlineCode,
					price: parseFloat(item.PassengerFareBreakdown.ADT.total_price_markup).toFixed(2),
					stops: currentStops,
					cat:currentStops > 1 ? 2:1
				});
				//console.log(currentPrice);
				prices.add(currentPrice);
				if (!bestPrice || currentStops < bestPrice.stops || (currentStops === bestPrice.stops && currentPrice < bestPrice.price)) {
					bestPrice = { price: currentPrice, stops: currentStops };
				}
				if (!directPrice || currentStops < directPrice.stops || (currentStops === directPrice.stops && totalDuarationMinutes < directPrice.duration)) {
					directPrice = { price: currentPrice, stops: currentStops, duration:totalDuarationMinutes };
				}
				if(altdays){
					const dateHash = item.dateHash;
					if (!(dateHash in dateFilters) || currentPrice < dateFilters[dateHash].price) {
						let airlineStop = currentStops;
						let stops_str = 'D: '+airlineStop+' Stop'+(airlineStop !='1' ? 's':'');
						if (item.SegmentSummary.length === 2) {
							let arrivalStops = item.SegmentSummary[1].totalStops;
							stops_str += ' | A: ' + arrivalStops + ' Stop' + (arrivalStops !== '1' ? 's' : '');
						}
						dateFilters[dateHash] = {price:currentPrice,stops:stops_str};
						if(!cheapestPrice || currentPrice < cheapestPrice){
							cheapestPrice = currentPrice;
						}
					}
				}
			});
			// console.log("originList",originList);
			// console.log("destinationList",destinationList);
			// Convert the Set back to an array if needed
			var airlineList = Array.from(airlineList).sort();
			var airlineCList = Array.from(airlineCList);
			var airlineData = Array.from(airlineData);
			var airlineStops = Array.from(airlineStops);
			var depCatList = Array.from(depCatList);
			var arrCatList = Array.from(arrCatList);
			var refundTypes = Array.from(refundTypes);//console.log('refundTypes:',refundTypes);
			var originList = Array.from(originList).map(function(item) {
				return JSON.parse(item);  // Parse the string back into an object
			});



			originList.sort((a, b) => (a.CityName || "").localeCompare(b.CityName || ""));
			var destinationList = Array.from(destinationList).map(function(item) {
				return JSON.parse(item);  // Parse the string back into an object
			});
			destinationList.sort((a, b) => (a.CityName || "").localeCompare(b.CityName || ""));
			var layoverList = Array.from(layoverList).map(function(item) {
				return JSON.parse(item);  // Parse the string back into an object
			});
			layoverList.sort((a, b) => (a.CityName || "").localeCompare(b.CityName || ""));
			var prices = Array.from(prices); //console.log('pricess',prices);
			// console.log("originList",originList);
			// console.log("destinationList",destinationList);
			// console.log("layoverList",layoverList);
			//console.log('ba',airlineData);
			airlinesData = getMinPriceByCodeAndCat(airlineData);
			cloneSliderr(airlinesData);
			// console.log('bb',airlineCList);
			// console.log('bab',airlinesData);
			// console.log('airlineStops',airlineStops);
			loadAirlineFilter(airlinesData);
			loadStopFilterr(airlineStops);
			loadTimeRangeSelectorr(depCatList, arrCatList);
			loadFlighttypeFilterr(refundTypes);
			LoadLayoverAirportListt(layoverList);
			LoadOriginAirportListt(originList);
			LoadDestinationAirportListt(destinationList);
			loadFlights(currentPage);
			//price range filter
			var minPrice = Math.min.apply(null, prices).toFixed(2);
			var maxPrice = Math.max.apply(null, prices).toFixed(2);
			loadPriceRangeSelector(Math.floor(minPrice), Math.ceil(maxPrice));
			
			//layover range filter
			var waitingTimes = [...new Set(
				flightList.flatMap(flight =>  
					flight.SegmentDetails.flatMap(segment =>
						segment.filter(singleAirline => singleAirline.WaitingTimeHM) // Filter out objects that don't have 'waitingTime'
						.map(singleAirline => singleAirline.WaitingTimeHM)   // Extract the waitingTime values
						//.map(time => Math.ceil(time)) // Extract hours and convert to integer
					)
				)
			)];
			//console.log("waitingTimes",waitingTimes);
			var minLay = Math.min.apply(null, waitingTimes);
			var maxLay = Math.max.apply(null, waitingTimes);
			//loadLayRangeSelector(Math.floor(minLay), Math.ceil(maxLay));
			loadLayRangeSelector(0, Math.ceil(maxLay));
			// $('#core_minimum_range_value', '#core_min_max_slider_values').val(Math.floor(minPrice));
			// $('#core_maximum_range_value', '#core_min_max_slider_values').val(Math.ceil(maxPrice));
			// $('#lay_minimum_range_value', '#lay_min_max_slider_values').val(Math.floor(minLay));
			// $('#lay_maximum_range_value', '#lay_min_max_slider_values').val(Math.ceil(maxLay));
			$('#core_minimum_range_value', '#core_min_max_slider_values').val(minPrice);
			$('#core_maximum_range_value', '#core_min_max_slider_values').val(maxPrice);
			$('#lay_minimum_range_value', '#lay_min_max_slider_values').val(minLay);
			$('#lay_maximum_range_value', '#lay_min_max_slider_values').val(maxLay);
			$('.cheapest .price').html(`${sliderCurrency} ${formatIndianNumber(minPrice)}`);
			$('.best .price').html(`${sliderCurrency} ${formatIndianNumber(minPrice)}`);
			$('.shortest .price').html(`${sliderCurrency} ${formatIndianNumber(directPrice.price)}`);
			$('.flexiable .price').html(`${sliderCurrency} ${formatIndianNumber(minPrice)}`);
			if(altdays && Object.keys(dateFilters).length > 0){
				for (var dateHash in dateFilters) {
					var $dateFilter = $('.date-filter[data-category="' + dateHash + '"]');
					if (dateFilters.hasOwnProperty(dateHash) && $dateFilter.length > 0) {
						var price = dateFilters[dateHash].price;
						var stops = dateFilters[dateHash].stops;
						var priceParts = price.toString().split('.');
						$dateFilter.find('.price').html(sliderCurrency + ' ' + priceParts[0] +'<small>.' + (priceParts.length > 1 ? priceParts[1] :'00')+ '</small>' );
						$dateFilter.find('.price').after('<span class="stops">' + stops + '</span>');
						if(price ==cheapestPrice){
							$dateFilter.addClass('cheapest-price');
						}
					}
				}
			}
			});
		}

		//add_unpublished_strip();

		if(IsDomesticRoundway)
		{
			default_col2x();
		}
		apply_sort('best');
		
	}
	function apply_sort(type) {
	//console.log(flightList.length, flightListFiltered.length);
		console.log(tripType);
		var fare_key = module+'_PriceDetails';

    if (!flightList || flightList.length === 0) return;

    // toggle order
    if (currentSortType === type) {
        currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortType = type;
        currentSortOrder = 'asc';
    }

    flightListFiltered = flightList.slice().sort(function (a, b) {

        const priceA = parseFloat(a.FareDetails[fare_key].TotalFare);
        const priceB = parseFloat(b.FareDetails[fare_key].TotalFare);

        const durationA = parseInt(a.SegmentSummary[0].TotalDuarationMinutes);
        const durationB = parseInt(b.SegmentSummary[0].TotalDuarationMinutes);

        const stopsA = (tripType === 'circle' || tripType === 'round') ? a.SegmentSummary.reduce((s, seg) => s + seg.totalStops, 0)
        : a.totalStops;
		const stopsB = (tripType === 'circle' || tripType === 'round') ? b.SegmentSummary.reduce((s, seg) => s + seg.totalStops, 0)
		        : b.totalStops;

		        console.log(stopsA+'-'+stopsB);

        const refundableA = a.Attr.IsRefundable ? 1 : 0;
        const refundableB = b.Attr.IsRefundable ? 1 : 0;

        let result = 0;

        switch (type) {

            case 'cheapest':
                result = priceA - priceB;
                break;

            case 'shortest':
                // ✅ LEAST STOPS → SHORTEST → CHEAPEST
                result =
                    (stopsA - stopsB) ||
                    (durationA - durationB) ||
                    (priceA - priceB);
                break;

            case 'flexiable':
                result = priceA - priceB;
                break;

            case 'best':
            default:
                result =
                    (priceA - priceB) ||
                    (durationA - durationB) ||
                    (stopsA - stopsB);
        }

        return currentSortOrder === 'asc' ? result : -result;
    });

    // 🔁 RENDER
    second_parent.empty();
    currentPage = 1;
    loadFlights(1);
}

$(document).on("click", ".price-ofr-tab a", function (e) {
    e.preventDefault();

    $(".price-ofr-tab a").removeClass("active");
    $(this).addClass("active");

    if ($(this).hasClass("best")) apply_sort("best");
    else if ($(this).hasClass("cheapest")) apply_sort("cheapest");
    else if ($(this).hasClass("shortest")) apply_sort("shortest");
    else if ($(this).hasClass("flexiable")) apply_sort("flexiable");
});
	function formatDateToTime(dateTime) { //console.log('d1:',dateTime);
		var date = new Date(dateTimeToIsoDateTime(dateTime));//console.log('d2:',date);console.log('d3:',('0' + date.getHours()).slice(-2) + ('0' + date.getMinutes()).slice(-2));
		return ('0' + date.getHours()).slice(-2) + ('0' + date.getMinutes()).slice(-2);
	}
	function formatDateToWeekday(date) {
		var dateObj = new Date(dateTimeToIsoDateTime(date));
		var options = { weekday: 'short', day: '2-digit', month: 'short', year: '2-digit' };
		return dateObj.toLocaleDateString('en-US', options);
	}
	function dateTimeToIsoDateTime(date_time){
		var dateParts = date_time.split(' ');
		var dayMonthYear = dateParts[0].split('-');
		if(dayMonthYear[0].length === 4){
			var isoDate = date_time.replace(' ', 'T');
		}else{
			var isoDate = dayMonthYear[2] + '-' + dayMonthYear[1] + '-' + dayMonthYear[0] + 'T' + dateParts[1];
		}
		return isoDate;
	}
	function formatDate(dateStr) {
		//var date = new Date(dateStr.split('-').reverse().join('-') + "T" + dateStr.split(' ')[1]);
		var date = new Date(dateTimeToIsoDateTime(dateStr));
		return date.toLocaleString('en-US', { weekday: 'short', day: '2-digit', month: 'short', year: '2-digit', hour: '2-digit', minute: '2-digit' });
	}
	function booking_form(IsDomestic, Token, TokenKey, cur_ProvabAuthKey, booking_source, session_id, ShoppingResponseId) {
		// This should return the appropriate HTML form part
		var inputs = '';
		inputs += '<input type="hidden" name="is_domestic" value="' + IsDomestic + '" />';
		inputs += '<input type="hidden" name="token[]" class="token data-access-key" value="' + Token + '" />';
		inputs += '<input type="hidden" name="token_key[]" value="' + TokenKey + '" />';
		inputs += '<input type="hidden" name="search_access_key[]"  class="search-access-key" value="' + cur_ProvabAuthKey + '" />';
		inputs += '<input type="hidden" name="booking_source" class="booking-source" value="' + booking_source + '" />';
		inputs += '<input type="hidden" name="session_id" value="' + session_id + '" />';
		inputs += '<input type="hidden" name="ShoppingResponseId" class="ShoppingResponseId" value="' + ShoppingResponseId + '" />';
		return inputs;
	}
	function loadAirlineFilter(airlineData) {
		// Empty the previous filter list and create a new container for the list
		const $allAirlines = $('#allairlines').empty();
		const $ul = $('<ul>', { class: 'locationul' }).appendTo($allAirlines);
	
		// Sort the airline data by the name of the first category (cat: 1)
		const sortedAirlines = Object.entries(airlineData)
			.sort(([, a], [, b]) => {
				// Compare the name of the first category (or fallback to the second category)
				
				const nameA = a["1"]?.name || a["2"]?.name || "";
      			const nameB = b["1"]?.name || b["2"]?.name || "";
				return nameA.localeCompare(nameB);
			});
	
		// Build the filter list for each sorted airline
		const airlineListHTML = sortedAirlines.map(([item, data], index) => {
			const airlineName = data[1] ? data[1].name : data[2].name;
			return `
				<li>
					<div class="squaredThree">
						<input type="checkbox" value="${item}" name="check" class="airlinecheckbox" id="squaredThree${index + 1}">
						<label for="squaredThree${index + 1}"></label>
					</div>
					<label for="squaredThree${index + 1}" class="lbllbl">${airlineName}</label>
				</li>
			`;
		}).join('');  // Join the array of HTML strings into one single string
	
		// Append the generated HTML to the <ul> element
		$ul.html(airlineListHTML);
	
		// If no airlines are present, ensure an empty <li> is rendered (this case is rare as empty airlinesData should not occur)
		if (sortedAirlines.length === 0) {
			$ul.append('<li></li>');
		}
	}
	function cloneSliderr(airlineData) {
		const list = [];

		for (let item in airlineData) {
			const airlineInfo = airlineData[item];
			const $stop01Currency = airlineInfo[1] ? airlineInfo[1].price : '--';
			const $stop1PlusCurrency = airlineInfo[2] ? airlineInfo[2].price : '--';
			const airlineName = airlineInfo[1] ? airlineInfo[1].name : airlineInfo[2].name;
			
			list.push(`
				<div class="item hand-cursor">
					<div class="airlinesd">
						<input type="checkbox" class="airline-slider" value="${item}">
						<div class="imgemtrx">
							<img alt="Flight" src="${airline_logo_path}${item}.svg">
							<strong title="${airlineName}">${airlineName}</strong>
						</div>
						<div class="alsmtrx">
							<span class="mtrxprice">
								<span class="clone_side_org_price hide">pric</span>
								<span class="clone_side_curr">${$stop01Currency === '--' ? '--' : sliderCurrency}</span>
								<span class="clone_side_price">${formatIndianNumber($stop01Currency)}</span>
							</span>
						</div>
						<div class="one-stop-alsmtrx">
							<span class="mtrxprice">
								<span class="clone_side_org_price hide">99</span>
								<span class="clone_side_curr">${$stop1PlusCurrency === '--' ? '--' : sliderCurrency}</span>
								<span class="clone_side_price">${formatIndianNumber($stop1PlusCurrency)}</span>
							</span>
						</div>
					</div>
				</div>
			`);
		}
	
		// Efficient DOM manipulation: empty first, then set the entire HTML
		const $carousel = $('#arlinemtrx1');
		$carousel.empty(); // Remove all children
		$carousel.html(`<div id="arlinemtrx" class="owl-carousel matrixcarsl">${list.join('')}</div>`);
	
		// Initialize carousel
		carousel();
	}
	function getMinPriceByCodeAndCat(data) {
		const result = {};
	
		// Group the data by code and find the min price for each cat
		data.forEach(item => {
			const { code, price, cat, name } = item;
			
			if (!result[code]) {
				result[code] = {}; // Initialize if this code doesn't exist yet
			}
	
			// If the category is not in the code group or we found a lower price, update it
			if (!result[code][cat] || parseFloat(price) < result[code][cat]) {
				result[code][cat] = {price:price,name:name};
			}
		});

		// Sort the airlines by the price of their first category (cat: 1)
		/* const sortedDataByPrice = Object.keys(result)
			.sort((a, b) => {
				// Get the price of the first category (cat: 1) for sorting
				const priceA = parseFloat(result[a]["1"]?.price || result[a]["2"].price);  // Access price from category 1 (or 0 if not present)
				const priceB = parseFloat(result[b]["1"]?.price || result[b]["2"].price);  // Parse as float to sort numerically
				return priceA - priceB;  // Sort in ascending order by price
			})
			.reduce((sorted, key) => {
				sorted[key] = result[key];  // Rebuild the object with sorted keys
				return sorted;
			}, {}); */

			
		return result;
	}
	function loadStopFilterr(stopCountList) 
	{

	    const enabledStops = new Set(stopCountList);

	    // Handle BOTH wrappers separately
	    $('#stopCountWrapper, #stopCountWrapper1').each(function () {

	        const wrapper = $(this);

	        wrapper.find('.stopcount').each(function () {

	            const stopCat = parseInt(this.value, 10);
	            const stopWrapper = $(this).closest('.stop-wrapper');

	            if (enabledStops.has(stopCat)) {
	                // ENABLE
	                stopWrapper
	                    .removeClass('disabled')
	                    .addClass('enabled');

	                // ❌ DO NOT disable input
	                // $(this).prop('disabled', false);

	            } else {
	                // DISABLE (UI only)
	                stopWrapper
	                    .removeClass('enabled')
	                    .addClass('disabled');

	                // ❌ DO NOT do this
	                // $(this).prop('disabled', true);
	            }
	        });
	    });
	}

	function loadTimeRangeSelectorr(depCatList, arrCatList) {
		// console.log('timecats:', depCatList, arrCatList);
	
		// Convert arrays to Sets for O(1) average time complexity lookups
		const depCatSet = new Set(depCatList);
		const arrCatSet = new Set(arrCatList);
	
		// Combine both loops into one by using a shared function to avoid duplication
		const handleTimeCategory = (timeWrapperSelector, catSet) => {
			$(timeWrapperSelector).each(function () {
				const cat = parseInt($(this).val());
				const $timeWrapper = $(this).closest('.time-wrapper');
	
				// Disable or enable the time wrapper based on the Set lookup
				if (catSet.has(cat)) {
					$timeWrapper.addClass('enabled').removeClass('disabled');
					$(this).prop('disabled', false);
				} else {
					$timeWrapper.addClass('disabled').removeClass('enabled');
					$(this).prop('disabled', true);
				}
			});
		};
	
		// Handle both departure and arrival categories using the shared function
		handleTimeCategory('#departureTimeWrapper .time-category', depCatSet);
		handleTimeCategory('#arrivalTimeWrapper .time-category', arrCatSet);
	}
	function loadFlighttypeFilterr(refundTypes) {
		if (refundTypes.length === 0) {
			$('#allflighttype').html('<ul class="locationul"><li></li></ul>');
			return;
		}
		const filterList = refundTypes.map((value, index) => {
			return `
				<li>
					<div class="squaredThreeflighttype">
						<input type="checkbox" value="${value}" name="check" class="flighttypecheckbox" id="squaredThreeflighttype${index + 1}">
						<label for="squaredThreeflighttype${index + 1}"></label>
					</div>
					<label for="squaredThreeflighttype${index + 1}" class="lbllbl" style="margin-left:20px;margin-top:-8px;">
						${value ? 'Refundable' : 'Non-Refundable'}
					</label>
				</li>`;
		});
	
		// Insert the constructed HTML in one go
		$('#allflighttype').html(`<ul class="locationul">${filterList.join('')}</ul>`);

	}
	function LoadLayoverAirportListt(airportlist) {
		if (airportlist.length === 0) {
			
			$('#layovers').html('<ul class="locationul"><li></li></ul>');
			return;
		}
		const filterList = airportlist.map((airport, key) => {
			return `
				<li>
					<div class="squaredThree airlinesrt">
						<input type="checkbox" value="${airport.AirportCode}" name="check" class="layovercheckbox" id="squaredThree2lay${key}">
						<label for="squaredThree2lay${key}"></label>
					</div>
					<label for="squaredThree2lay${key}" class="lbllbl">
						<span class="airline_namesrch">
							${airport.CityName}<span class="airline_smldts">(${airport.AirportCode})</span>
						</span>
					</label>
				</li>`;
		});
		$('#layoversLabel').html('Connecting in');
		$('#layovers').html(`<ul class="locationul">${filterList.join('')}</ul>`);
	}

	function LoadOriginAirportListt(airportlist) {
		if (airportlist.length === 0) {
			$('#originairport').html('<ul class="locationul"><li></li></ul>');
			return;
		}
		const filterList = airportlist.map((airport, key) => {
			return `
				<li>
					<div class="squaredThree airlinesrt">
						<input type="checkbox" value="${airport.AirportCode}" name="check" class="originairportcheckbox" id="squaredThree2org${key}">
						<label for="squaredThree2org${key}"></label>
					</div>
					<label for="squaredThree2org${key}" class="lbllbl">
						<span class="airline_namesrch">
							${airport.CityName}<span class="airline_smldts">(${airport.AirportCode})</span>
						</span>
					</label>
				</li>`;
		});
		$('#originairportLabel').html('Departing from');
		$('#originairport').html(`<ul class="locationul">${filterList.join('')}</ul>`);
	}

	function LoadDestinationAirportListt(airportlist) {
		if (airportlist.length === 0) {
			$('#destinationairport').html('<ul class="locationul"><li></li></ul>');
			return;
		}
	
		// Using an array to collect list items
		const filterList = airportlist.map((airport, key) => {
			return `
				<li>
					<div class="squaredThree airlinesrt">
						<input type="checkbox" value="${airport.AirportCode}" name="check" class="dstairportcheckbox" id="squaredThree2dsta${key}">
						<label for="squaredThree2dsta${key}"></label>
					</div>
					<label for="squaredThree2dsta${key}" class="lbllbl">
						<span class="airline_namesrch">
							${airport.CityName}<span class="airline_smldts">(${airport.AirportCode})</span>
						</span>
					</label>
				</li>`;
		});
	
		// Update the DOM once
		$('#destinationairportLabel').html('Arriving at');
		$('#destinationairport').html(`<ul class="locationul">${filterList.join('')}</ul>`);
	}
	function debounce(func, delay) {
		let timeout;
		return function() {
			clearTimeout(timeout);
			timeout = setTimeout(func, delay);
		};
	}
	$(document).on('click', '.timone', function(e) {
	    e.preventDefault(); // Prevent default <a> click behavior

	    var checkbox = $(this).find('input.time-category');

	    // Toggle checked state manually
	    checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
	});

	
	// Layour CLICK
	$('#stopCountWrapper').on('click', '.stopone', function (e) {
	    e.preventDefault();
	    e.stopPropagation(); // IMPORTANT for dropdown

	    var checkbox = $(this).find('input.stopcount');

	    checkbox.prop('checked', !checkbox.prop('checked'));

	    //console.log('Layover stop clicked:', checkbox.val());

	    apply_filters();
	});

	// dropdown CLICK
	$('#stopCountWrapper1').on('click', '.stopone', function (e) {
	    e.preventDefault();

	    var checkbox = $(this).find('input.stopcount');

	    checkbox.prop('checked', !checkbox.prop('checked'));

	    //console.log(' Dropdown stop clicked:', checkbox.val());

	    apply_filters();
	});


	/*$(document).on('change','#stopCountWrapper .stopcount, #stopCountWrapper1 .stopcount',
	    debounce(function () {
	        apply_filters();
	    }, 300)
	);*/

	function getSelectedStops() {
	    var stops = [];

	    // Desktop stop filter
	    if ($('#stopCountWrapper').is(':visible')) {
	        stops = $('#stopCountWrapper input.stopcount:checked:not(:disabled)')
	            .map(function () {
	                return parseInt(this.value);
	            })
	            .get();
	    }

	    // Mobile stop filter
	    if ($('#stopCountWrapper1').is(':visible')) {
	        stops = $('#stopCountWrapper1 input.stopcount:checked:not(:disabled)')
	            .map(function () {
	                return parseInt(this.value);
	            })
	            .get();
	    }

	    return stops;
	}



	$(document).on('change', '#departureTimeWrapper .time-wrapper.enabled .time-category, #arrivalTimeWrapper .time-wrapper.enabled .time-category', debounce(function () {
		apply_filters();
	}, 300));
	$(document).on('change', '#originairport .originairportcheckbox, #destinationairport .dstairportcheckbox, #layovers .layovercheckbox', debounce(function () {
		apply_filters();
	}, 300));
	$(document).on('click', '.date-filter', function () {
		// $('.date-filter-tab a').removeClass('active');
		$(this).toggleClass('active');
		apply_filters();
	});
	$(document).on('click', 'input.airline-slider, input.airlinecheckbox', function () {
		$('.airline-slider[value="' + $(this).val() + '"]').closest('.item').toggleClass('active');
		toggle_airline_select($(this).val(), $(this).is(':checked'));
		apply_filters();
	});
	$(document).on('click', 'input.flighttypecheckbox', function () {
		apply_filters();
	});
	function toggle_airline_select(airline_code, checked) {
		if (checked == true) {
			$('.airline-slider[value="' + airline_code + '"], .airlinecheckbox[value="' + airline_code + '"]').prop('checked', 'checked');
		} else {
			$('.airline-slider[value="' + airline_code + '"], .airlinecheckbox[value="' + airline_code + '"]').prop('checked', false);
		}
	}
	$(document).on('click', '.iti-btn', function (e) {
		e.preventDefault();
		var tab_id = $(this).data('id');
		$('#' + tab_id).provabPopup({
			modalClose: true,
			zIndex: 10000005,
			closeClass: 'closepopup'
		});
	});
	var fare_details_cache = {};
	$(document).on('click', ".iti-fare-btn", function (e) {
		e.preventDefault();
		var _tmp_result_row_index = $(this).closest('.propopum');
		var _data_access_key = $('.data-access-key', _tmp_result_form_index).val();
		$('.loader-image', _tmp_result_row_index).show();
		if ((_data_access_key in fare_details_cache) == false) {
			var _tmp_result_form_index = $('#' + $(this).data('form-id'));
			var _booking_source = $('.booking-source', _tmp_result_form_index).val();
			var _search_access_key = $('.search-access-key', _tmp_result_form_index).val();
			var _provab_auth_key = $('.provab-auth-key', _tmp_result_form_index).val();
			var _ShoppingResponseId = $('.ShoppingResponseId', _tmp_result_form_index).val();
			set_fare_details(_data_access_key, _booking_source, _search_access_key, _provab_auth_key, _tmp_result_row_index, _ShoppingResponseId);
		} else {
			show_fare_result(_tmp_result_row_index, fare_details_cache[_data_access_key]);
		}
	});
	

	function set_fare_details(data_access_key, source, search_access_key, provab_auth_key, _tmp_result_row_index, ShoppingResponseId) {
		var params = {
			data_access_key: data_access_key,
			booking_source: source,
			search_access_key: search_access_key,
			provab_auth_key: provab_auth_key,
			ShoppingResponseId: ShoppingResponseId
		};
		$.ajax({
			type: 'POST',
			url: app_base_url + 'ajax/get_fare_details',
			async: true,
			cache: false,
			data: $.param(params),
			dataType: 'json',
			success: function (result) {
				if (result.hasOwnProperty('status') == true && result.status == true) {
					fare_details_cache[data_access_key] = result.data;
				} else {
					fare_details_cache[data_access_key] = result.msg;
				}
				show_fare_result(_tmp_result_row_index, fare_details_cache[data_access_key]);
			}
		});
	}
	function show_fare_result(result_row_index, data) {
		$('.i-s-s-c', result_row_index).html(data);
		$('.loader-image', result_row_index).hide();
	}


	window.sendflightdetails_multi = function(data){
	 //alert(data);

	
	$("#errormsg_multi_"+data).show();
	$("#errormsg_multi_"+data).text('');
	var input_text=$("#email_multi_"+data).val();

	var flightdetails=$("#flightdetails_multi_"+data).val();

			var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
			// var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			
			if(!mailformat)
			{
				$("#errormsg_multi_"+data).css({"color": "red"});
				$("#errormsg_multi_"+data).text("Enter Valid email address!");
			   
			   return false;
			}
			

		$('#send_email_loading_image_multi_'+data).show();
		$('.loader-image').show();
		$(document.body).off('click');
		document.getElementById("send_email_btn_not_multi"+data).disabled = true;
		//document.getElementById("close_modal_multi"+data).disabled = true;
		// document.getElementsByClassName("close").disabled = true;
		$.ajax({
			url:app_base_url+'index.php/ajax/send_multi_details_mail/',
			type:'POST',
			data:{'email':input_text,'flightdetails':flightdetails,'module':'flight'},
			success:function(msg){
				//console.log(msg);
				$(document.body).on('click');
				if(msg == true){
				   $("#errormsg_multi_"+data).css({"color": "green"});
				   $("#errormsg_multi_"+data).text("Email Sent Successfully");
				   $("#email_multi_"+data).val("");
				   setTimeout( function(){$("#errormsg_multi_"+data).hide();$("#sendmail_multi_"+data).modal('hide');} , 3000);
				   
					
				   // $('.result-pre-loader').hide();
				  $('#send_email_loading_image_multi_'+data).hide();
					$('.loader-image').hide();
					document.getElementById("send_email_btn_not_multi"+data).disabled = false;
					document.getElementById("close_modal_multi"+data).disabled = false;
					// document.getElementsByClassName("close").disabled = false;
					// setTimeout( function(){} , 3000);
				}
				else
				{
					$("#errormsg_multi_"+data).css({"color": "red"});
					   $("#errormsg_multi_"+data).text("Try again");
					   // $('.result-pre-loader').hide();
					   $('#send_email_loading_image_multi').hide();
					$('.loader-image').hide();
					document.getElementById("send_email_btn_not_multi").disabled = false;
					   location.reload();

				}
			},
			error:function(){
			}
		 }) ;
}

	function generateCurrencyOptions(price) {
		if (typeof currencyList === 'undefined' || !Array.isArray(currencyList) || currencyList.length === 0) {
			return ''; // Safe fallback if not defined, not an array, or empty
		}
		let html = `
			<span class="mulitple_currency">
				<div class="dropdown">
					<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Multiple Currency
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu flag_images" aria-labelledby="dLabel">
		`;

		currencyList.forEach(item => {
			const convertedPrice = (price * item.rate).toFixed(2);
			html += `
				<li>
					<span class="curncy_img sprte ${item.flag}"></span>
					<span class="country_code">${item.code}</span>
					<span>-</span>
					<span>${item.symbol}${convertedPrice}</span>
				</li>
			`;
		});

		html += `
					</ul>
				</div>
			</span>
		`;

		return html;
	}


});

function formatLabel(value) {
    value = $.trim(value);

    // KG normalization
    var kgMatch = value.match(/(\d+)\s*(Kg|KG|Kilogram|Kilograms)/i);
    if (kgMatch) {
        return parseInt(kgMatch[1]) + " KG";
    }

    // PC normalization
    var pcMatch = value.match(/(\d+)\s*(Piece|Pieces|Pcs|PC)/i);
    if (pcMatch) {
        return parseInt(pcMatch[1]) + " PC";
    }

    // Default
    return value;
}


function formatIndianNumber(amount) {
  if (isNaN(amount)) return amount;
  return new Intl.NumberFormat('en-IN').format(amount);
}

function base64EncodeUnicode(str) {
    return btoa(
        encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
            function (match, p1) {
                return String.fromCharCode('0x' + p1);
            }
        )
    );
}
function timeToMinutes(timeStr) {
	
    let totalMinutes = 0;

    const hoursMatch = timeStr.match(/(\d+)\s*h/i);
    const minutesMatch = timeStr.match(/(\d+)\s*m/i);

    if (hoursMatch) {
        totalMinutes += parseInt(hoursMatch[1], 10) * 60;
    }

    if (minutesMatch) {
        totalMinutes += parseInt(minutesMatch[1], 10);
    }
    return totalMinutes;
}


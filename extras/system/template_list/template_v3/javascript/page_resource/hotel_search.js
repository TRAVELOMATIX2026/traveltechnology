var owl;

$(document).ready(function() {
	// alert('test');
	$city = 'London';
	 
	// $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city";
	// $json_data = file_get_contents($url);
	// $result = json_decode($json_data, TRUE);
	// $latitude = $result['results'][0]['geometry']['location']['lat'];
	// $longitude = $result['results'][0]['geometry']['location']['lng'];
	// alert($latitude);
	enable_sort();
	//var bounds = new google.maps.LatLngBounds();
	$('.loader-image').show();
	pre_load_audio();
	var _search_id = document.getElementById('pri_search_id').value;
	var api_url = document.getElementById('api_base_url').value;
	var active_booking_source = document.getElementById('api_booking_source').value;
	var pagination_loader = document.getElementById('pagination_loader').value;
	//console.log("api_url"+api_url);
	var _fltr_r_cnt = 0; //Filter Result count
	var _total_r_cnt = 0;//Total Result count
	var _offset = 0;//Offset to load results
	var dynamic_page_data = false;
	//var default_loader = document.getElementById('default_loader').value;
	var default_loader = api_url+'image_loader.gif';

	

	
	var processLazyLoad = function() {
		//check if your div is visible to user
		//CODE ONLY CHECKS VISIBILITY FROM TOP OF THE PAGE
		if (_next_page == true && $('#npl_img').get(0) && $('#npl_img').get(0).scrollHeight != 0) {
			//console.log('Lazy loaded flexible');
			if ($(window).scrollTop() + $(window).height() >= $('#npl_img').get(0).scrollHeight) {
				if(!$('#npl_img').attr('loaded')) {
					_next_page == false;
					//not in ajax.success due to multiple sroll events
					$('#npl_img').attr('loaded', true);
					
					//ajax goes here
					//load_hotels(process_result_update, _offset, _ini_fil);

					reset_ini_map_view();
					google.maps.event.trigger(map, 'resize');
					resizeMap();
				}
			}
		}
	}
	/**
	 * Offset and total records needed for pagination
	 */
	var _next_page = false;
	function ini_pagination()
	{
		//fixme - here
		//console.log("offset"+_offset+"fltr_count"+_fltr_r_cnt+"total_count"+_total_r_cnt);
		if (_offset >= _fltr_r_cnt && _fltr_r_cnt < _total_r_cnt) {
			_next_page = false;
			//console.log('Filters are applied and all the results are loaded');
			$('#npl_img').hide();
		} else if (_offset < _fltr_r_cnt && _fltr_r_cnt < _total_r_cnt) {
			_next_page = true;
			//console.log('Filters are applied and all the results are not loaded');
			$('#npl_img').show();
		} else if (_offset > _total_r_cnt && _fltr_r_cnt == _total_r_cnt && dynamic_page_data == true) {
			_next_page = false;
			//all data loaded, remove scroll event handler as we no longer need lazy loading of data
			//console.log('No More Records so can activate JS filter and disable pagination');
			$(window).unbind('scroll', window, processLazyLoad);
			$('#npl_img').remove();
			dynamic_page_data = false;
			
			//enable sorting via javascript
			enable_sort();
		} else if (_offset < _fltr_r_cnt) {
			_next_page = true;
			
			if(parseInt($("#npl_img").length)==0){				
				var html ='';
				html +='<div id="npl_img" class="text-center" loaded="true">';
				html+='<img src="'+pagination_loader+'" alt="please wait"/>';
				html +='</div>';				
				$(".allresult").append(html);
			}			
			//console.log('More Records are available to load');
			$('#npl_img').show();
		}else{
			$(window).unbind('scroll', window, processLazyLoad);
			$('#npl_img').remove();
			dynamic_page_data = false;
			enable_sort();
		}
	}
	window.process_result_update = function(result) {
		var ___data_r = Date.now();
		$('.loader-image').hide();
		hide_result_pre_loader();
		// Hide skeleton loader when results start loading
		$('#hotel-list-skeleton-loader').hide();
		//console.log("pre_loader");
		if (result.hasOwnProperty('status') == true && result.status == true) {
			set_total_summary(result.total_result_count, result.filter_result_count, result.offset);
			update_total_count_summary();
			$('#npl_img').removeAttr('loaded');
			if (_offset == 0) {
				//console.log('Data loaded with offset 0');
				$('#hotel_search_result').html(result.data);
				$("#npl_img").hide();
				$('#hotel-list-skeleton-loader').hide();
				
				// Give scripts time to execute after HTML insertion
				setTimeout(function() {
					// Check if hotels were loaded, if not show error
					if (typeof all_hotels === 'undefined' || !Array.isArray(all_hotels) || all_hotels.length === 0) {
						console.error('Hotel data not loaded. API response:', result);
						if ($('#t-w-i-1').length > 0 && $('#t-w-i-1').children().length === 0) {
							$('#empty_hotel_search_result').show();
							$('#hotel_search_result').hide();
						}
					} else {
						console.log('Hotels loaded successfully. Count:', all_hotels.length);
					}
				}, 200);
				//No Result Found
			} else {
				if(_offset == 20){
                        $('#hotel_search_result').html(result.data);
                        $('#hotel-list-skeleton-loader').hide();
                }else{
                    $('#hotel_search_result').append(result.data);
                }
				//console.log('Pagination data loaded with offset');
				//$('#hotel_search_result').append(result.data);
			}
			ini_pagination();
			//Set time out to lazy load images
			lazy_img();

			/****** sidebar map view load here ******/
			// Wait for Google Maps API to be ready
			if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
				setTimeout(function() {
					ini_side_map_view();
					if (typeof sidemap !== 'undefined' && sidemap) {
						google.maps.event.trigger(sidemap, 'resize');
						resizingSidebarMap();
					}
				}, 500);
			} else {
				// Retry after a delay if Google Maps API isn't loaded yet
				setTimeout(function() {
					if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
						ini_side_map_view();
						if (typeof sidemap !== 'undefined' && sidemap) {
							google.maps.event.trigger(sidemap, 'resize');
							resizingSidebarMap();
						}
					}
				}, 2000);
			}
		} else {
			// Handle error case
			console.error('Hotel API response status is false:', result);
			$('#empty_hotel_search_result').show();
			$('#hotel_search_result').hide();
		}
	}
	
	window.ini_result_update = function(result) {
		post_load_audio();
		//console.log(result);
		if (result.hasOwnProperty('status') == true && result.status == true) {
			update_range_slider(parseInt(result.filters.p.min), parseInt(result.filters.p.max));
			enable_location_selector(result.filters.loc);
			enable_hotel_facilities_selector(result.filters.fac);
			enable_star_wrapper(result.filters.star);
			
			inif();
			$(window).on('scroll', processLazyLoad);
			
			// Hide filter skeleton loader and show filter content after filters are initialized
			var filterSkeletonHidden = false;
			var maxRetries = 50; // Maximum 5 seconds (50 * 100ms)
			var retryCount = 0;
			
			function hideFilterSkeletonAndShowContent() {
				if (filterSkeletonHidden) return;
				
				retryCount++;
				var $skeleton = $('#filter-skeleton-loader');
				var $content = $('#filter-content');
				var $slider = $('#price-range');
				
				// Check if slider is initialized (jQuery UI slider adds ui-slider class)
				var sliderReady = false;
				if ($slider.length) {
					// Check multiple ways slider might be initialized
					try {
						sliderReady = $slider.hasClass('ui-slider') || 
									  ($slider.data && $slider.data('ui-slider') !== undefined);
					} catch(e) {
						// If error checking, assume not ready
						sliderReady = false;
					}
				}
				
				// Hide skeleton and show content if slider is ready OR max retries reached
				if (sliderReady || $slider.length === 0 || retryCount >= maxRetries) {
					if ($skeleton.length && !$skeleton.hasClass('hide')) {
						$skeleton.addClass('hide').css({
							'display': 'none',
							'visibility': 'hidden',
							'opacity': '0'
						});
					}
					
					if ($content.length) {
						$content.removeClass('hide').css({
							'display': 'block',
							'visibility': 'visible',
							'opacity': '1'
						});
					}
					
					filterSkeletonHidden = true;
				} else {
					// Slider not ready yet, retry after short delay
					setTimeout(hideFilterSkeletonAndShowContent, 100);
				}
			}
			
			// Start checking after filters are initialized
			setTimeout(hideFilterSkeletonAndShowContent, 500);
		}
		else
		{
			check_empty_search_result();
		}
		
	}
	/**
	 * Set total result summary
	 */
	function set_total_summary(total_count, fltr_count, offset)
	{
		_fltr_r_cnt = parseInt(fltr_count);//visible 
		_total_r_cnt = parseInt(total_count);//total
		_offset = parseInt(offset);
	}
	
	function lazy_img()
	{
		$("img.lazy").lazy();
	}
	function check_empty_search_result() {
		if ($('.r-r-i:first').index() == -1) {
			$('#empty-search-result').show();
			$('#page-parent').remove();
		}
	}
	var room_list_cache = {};
	$(document).on('click', ".room-btn", function(e) {
		e.preventDefault();
		var _cur_row_index = $(this).closest('.r-r-i');
		var _hotel_room_list = $(".room-list", _cur_row_index);
		var _result_index = $('[name="ResultIndex"]:first', _cur_row_index).val();
		if (_hotel_room_list.is(':visible') == false) {
			_hotel_room_list.show();
			if ((_result_index in room_list_cache) == false) {
				var _hotel_name = $('.h-name', _cur_row_index).text();
				var _hotel_star_rating = $('.h-sr', _cur_row_index).text();
				var _hotel_image = $('.h-img', _cur_row_index).attr('src');
				var _hotel_address = $('.h-adr', _cur_row_index).text();
				$('.loader-image', _cur_row_index).show();
				$.post(app_base_url + "index.php/ajax/get_room_details", $('.room-form', _cur_row_index).serializeArray(), function(response) {
					if (response.hasOwnProperty('status') == true && response.status == true) {
						room_list_cache[_result_index] = true;
						$('.loader-image', _cur_row_index).hide();
						$(".room-summ", _cur_row_index).html(response.data);
						//update star rating and hotel name
						$('[name="HotelName"]', _cur_row_index).val(_hotel_name);
						$('[name="StarRating"]', _cur_row_index).val(_hotel_star_rating);
						$('[name="HotelImage"]').val(_hotel_image); //Balu A
						$('[name="HotelAddress"]').val(_hotel_address); //Balu A
					}
				});
			} else {
				$(".room-summ", _cur_row_index).show();
			}
		} else {
			_hotel_room_list.hide();
		}
	});
 	//getting hotel images dynamically
	$(document).on('click','.view-photo-btn',function(e){
		var id = $(this).data('id');	
		//alert(id);	
		var Hotel_code = $("#map_id_"+id).data('hotel-code');
		//alert( Hotel_code);
		var Booking_source = $("#map_id_"+id).data('booking-source');
		var hotel_name = $("#map_id_"+id).data('hotel-name');
		//alert(hotel_name);
		var hotel_star =parseInt($("#map_id_"+id).data('star-rating'));
		var price = $("#map_id_"+id).data('price');
		var result_token = $("#map_id_"+id).data('result-token');
		var trip_url = $("#map_id_"+id).data('trip-url');
		var trip_rating= $("#map_id_"+id).data('trip-rating');
		$("#hotel-images").html('');
		$("#modal-price-symbol").html('');
		$("#modal-price").html();
		
		if(trip_url!=''){
			$(".imghtltrpadv").removeClass('hide');
		}else{
			$(".imghtltrpadv").addClass('hide');
		}
		$("#trip_adv_img").attr('src','');
		$("#myModalLabel").html(hotel_name);
		var price_text = $('#pri_app_pref_currency').val();
		$("#modal-price-symbol").html(price_text);
		$("#modal-price").html(price);
		$("#trip_adv_img").attr('src',trip_url);
		$(".spinner").show();
		$(".hotel-images").hide();
		var star_str='';
		for (var i = 0; i < hotel_star; i++) {
			star_str +='<li><i class="fa fa-star" aria-hidden="true"></i></li>';
		}
		$(".htmimgstr").html(star_str);
		var booking_url =app_base_url+'index.php/hotel/hotel_details/'+
        _search_id+'?ResultIndex='+result_token+'&booking_source='+Booking_source
        +'&op=get_details';
		$("#modal-submit").attr('href',booking_url);
		$.post(app_base_url + "index.php/ajax/get_hotel_images",{hotel_code:Hotel_code,booking_source:Booking_source,search_id:_search_id,Hotel_name:hotel_name}, function(response) {
			//console.log("dasjkjdasd");
			//$("#myModalLabel").html(hotel_name);
			$(".spinner").hide();
			$(".hotel-images").show();
		//	console.log(response);
			if(response){
				$("#hotel-images").html(response.data);	
			}
			
		});
	});
	//Load hotels from active source
	show_result_pre_loader();
	$(document).on('click', '.location-map', function() {
		var data_key = $(this).data('key');
		var hotel_code = $(this).data('hotel-code');
		var star_rating = $("#location_"+hotel_code+"_"+data_key).data('star-rating');
		var hotel_name = $("#location_"+hotel_code+"_"+data_key).data('hotel-name');
		$(".htmimgstr").html("");
		$("#myModalLabelMap").html("");
		var star_str='';
		for (var i = 0; i < star_rating; i++) {
			star_str +='<li><i class="fa fa-star" aria-hidden="true"></i></li>';
		}
		$(".htmimgstr").html(star_str);
		$("#myModalLabelMap").html(hotel_name);
		$('#map-box-modal').modal();
	});
	$(document).on('click','.hotel-image-gal',function(){
		$("#hotel-img-gal-box-modal").modal();
	});
	/**
	 * Toggle active class to highlight current applied sorting
	 **/
	$(document).on('click', '.sorta', function(e) {
		$('.loader-image').show();
		e.preventDefault();
		$(this).closest('.sortul').find('.active').removeClass('active');
		//Add to sibling
		$(this).siblings().addClass('active');
	});
	//************************** **********/
	$('.toglefil').click(function() {
		$(this).toggleClass('active');
	});
	/*  Mobile Filter  */
	// $('.filter_tab').click(function() {
	// 	$('.resultalls').stop(true, true).toggleClass('open');
	// 	$('.coleft').stop(true, true).slideToggle(500);
	// });
	var widowwidth = $(window).width();
	if (widowwidth < 991) {
		$('.coleft').hide();
		$('.resultalls.open #hotel_search_result').on('click', function() {
			$('.resultalls').removeClass('open');
			$('.coleft').slideUp(500);
		});
	}
	var application_preference_currency = document.getElementById('pri_app_pref_currency').value;
	
	/** -------------------------SORT LIST DATA---------------------- **/
	/**
	 * Unsetting the sorter array
	 */
	function unset_sorters_array()
	{
		_ini_fil['sort_item'] = undefined;
		_ini_fil['sort_type'] = undefined;
	}
function enable_sort() 
{
    const $hotelSearchResult = $("#hotel_search_result");

    // Debounce function (unchanged)
    function debounce(func, delay) {
        let debounceTimer;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // ------- BIND CLICK EVENTS (unchanged in structure, only changed the callback) -------

    $(".price-l-2-h").click(debounce(function () {
        $(this).addClass('hide');
        $(".price-h-2-l, .name-l-2-h, .name-h-2-l, .star-l-2-h, .star-h-2-l").removeClass('hide');
       // performSort('.h-p', 'asc', true);
        _ini_fil['sort'] = {class:'.h-p',order:'asc'};
        applyAllFiltersAndSorting();
    }, 200));

    $(".price-h-2-l").click(debounce(function () {
        $(this).addClass('hide');
        $(".price-l-2-h, .name-l-2-h, .name-h-2-l, .star-l-2-h, .star-h-2-l").removeClass('hide');
       // performSort('.h-p', 'desc', true);
       _ini_fil['sort'] = {class:'.h-p',order:'desc'};
       applyAllFiltersAndSorting();
    }, 200));

    $(".name-l-2-h").click(debounce(function () {
        $(this).addClass('hide');
        $(".price-l-2-h, .price-h-2-l, .name-h-2-l, .star-l-2-h, .star-h-2-l").removeClass('hide');
       // performSort('.h-name', 'asc', false);
        _ini_fil['sort'] = {class:'.h-name',order:'asc'};
        applyAllFiltersAndSorting();
    }, 200));

    $(".name-h-2-l").click(debounce(function () {
        $(this).addClass('hide');
        $(".price-l-2-h, .price-h-2-l, .name-l-2-h, .star-l-2-h, .star-h-2-l").removeClass('hide');
       // performSort('.h-name', 'desc', false);
        _ini_fil['sort'] = {class:'.h-name',order:'desc'};
        applyAllFiltersAndSorting();
    }, 200));

    $(".star-l-2-h").click(debounce(function () {
        $(this).addClass('hide');
        $(".price-l-2-h, .price-h-2-l, .name-l-2-h, .name-h-2-l, .star-h-2-l").removeClass('hide');
        //performSort('.h-sr', 'asc', true);
        _ini_fil['sort'] = {class:'.h-sr',order:'asc'};
        applyAllFiltersAndSorting();
    }, 200));

    $(".star-h-2-l").click(debounce(function () {
        $(this).addClass('hide');
        $(".price-l-2-h, .price-h-2-l, .name-l-2-h, .name-h-2-l, .star-l-2-h").removeClass('hide');
       // performSort('.h-sr', 'desc', true);
       _ini_fil['sort'] = {class:'.h-sr',order:'desc'};
       applyAllFiltersAndSorting();
    }, 200));
}


function applyAllFiltersAndSorting(minPrice,maxPrice) 
{
  
    sorted_hotels = all_hotels;
    // Apply all filters sequentially

    // Filter by star rating
    let starRatingFilter = Array.isArray(_ini_fil['_sf']) && _ini_fil['_sf'].length > 0 ? _ini_fil['_sf'].filter(Boolean).map(Number) : [];
    if (starRatingFilter.length > 0) {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => starRatingFilter.includes(hotel.StarRating));
    }

    // Filter by deal
    if (_ini_fil['dealf']) {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => hotel.IsHotDeal);
    }

    // Filter by location
    let locationFilter = Array.isArray(_ini_fil['hl']) && _ini_fil['hl'].length > 0 ? _ini_fil['hl'].map(location => location.toLowerCase()) : [];
    if (locationFilter.length > 0) {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => locationFilter.includes(hotel.HotelLocation.toLowerCase()));
    }

    // Filter by meals
    let mealFilter = Array.isArray(_ini_fil['meals']) && _ini_fil['meals'].length > 0 ? _ini_fil['meals'].map(meal => meal.toLowerCase()) : [];
    if (mealFilter.length > 0) {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => mealFilter.includes(hotel.board_name.toLowerCase()));
    }

    // Filter by facilities
    let facilitiesFilter = Array.isArray(_ini_fil['hf']) && _ini_fil['hf'].length > 0 ? _ini_fil['hf'].map(facility => facility.toLowerCase()) : [];

    if (facilitiesFilter.length > 0) {
    sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => {
        let amenities = [];

	try {
	    if (Array.isArray(hotel.HotelAmenities)) {
	        amenities = hotel.HotelAmenities;
	    } else if (typeof hotel.HotelAmenities === 'string' && hotel.HotelAmenities.trim() !== '') {
	        amenities = JSON.parse(hotel.HotelAmenities);
	    }

	    amenities = amenities.map(a => typeof a === 'string' ? a.toLowerCase() : '');

	} 
	catch (err) 
	{
	    amenities = [];
	}

        return facilitiesFilter.some(facility => amenities.includes(facility));
    });
}


    // Filter by property type

     let propertyFilter = Array.isArray(_ini_fil['prop']) && _ini_fil['prop'].length > 0 ? _ini_fil['prop'].map(property => property.toLowerCase()) : [];
    
        if (propertyFilter.length > 0) { 
            sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => propertyFilter.includes(hotel.HotelProperty.toLowerCase()));
        }

      // console.log(_ini_fil['refundable_value']);
      // console.log(all_hotels);

    // Filter by free cancellation
    /*if (_ini_fil['refundable_value'] === true && _ini_fil['nonrefundable_value'] === false) 
    {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => hotel.IsRefundable==true||hotel.free_cancellation==true);
    } else if (_ini_fil['refundable_value'] === false && _ini_fil['nonrefundable_value'] === true) {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => hotel.IsRefundable==false || hotel.free_cancellation==false);
    }*/

    // Filter by free cancellation

    updateRefundOptions(all_hotels);

	if (_ini_fil['refundable_value'] === true && _ini_fil['nonrefundable_value'] === false) {

	    sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels)
	        .filter(hotel => hotel.IsRefundable == true || hotel.free_cancellation == true);

	} 
	else if (_ini_fil['refundable_value'] === false && _ini_fil['nonrefundable_value'] === true) {

	    sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels)
	        .filter(hotel => hotel.IsRefundable == false || hotel.free_cancellation == false);
	}

	// Update checkbox enable / disable
	updateRefundOptions(sorted_hotels.length ? sorted_hotels : all_hotels);

   
    if (minPrice !== undefined && maxPrice !== undefined) {
        sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(hotel => {
            const price = parseFloat(hotel.Price.RoomPrice) || 0;
            return price >= minPrice && price <= maxPrice;
        });
    }

    // Apply sorting if any
    const $hotelSearchResult = $("#hotel_search_result");

    function getProp(obj, propString) {
        const props = propString.split('.');
        let val = obj;
        for (let i = 0; i < props.length; i++) {
            if (val == null) return 0;
            val = val[props[i]];
        }
        return val;
    }

    const sortCriteria = _ini_fil['sort'] || {}; // Sort criteria from UI 

    if (sortCriteria && sortCriteria.class && sortCriteria.order) {
        let propertyPath;
        switch (sortCriteria.class) 
        {
            case '.h-p':
                propertyPath = 'Price.RoomPrice';
                break;
            case '.h-name':
                propertyPath = 'HotelName';
                break;
            case '.h-sr':
                propertyPath = 'StarRating';
                break;
            default:
                propertyPath = 'HotelName';
                break;
        }

        const isNum = ['.h-p', '.h-sr'].includes(sortCriteria.class);

        sorted_hotels.sort((a, b) => {
            let valA = getProp(a, propertyPath);
            let valB = getProp(b, propertyPath);

            if (isNum) {
                valA = parseFloat(valA) || 0;
                valB = parseFloat(valB) || 0;
                return sortCriteria.order === 'asc' ? (valA - valB) : (valB - valA);
            } else {
                valA = (valA || '').toString().toLowerCase();
                valB = (valB || '').toString().toLowerCase();
                return sortCriteria.order === 'asc'
                    ? valA.localeCompare(valB)
                    : valB.localeCompare(valA);
            }
        });
    }

    // Render filtered and sorted hotels
    $('#t-w-i-1').empty();
    $('#npl_img').hide();
    chunkSize = 20;
    hotelOffset = 0;

    //console.log(sorted_hotels);

    loadNextHotels();
}
	/** -------------------------SORT LIST DATA---------------------- **/
	/**
	 * Toggle active class to highlight current applied sorting
	 **/
	$(document).on('click', '.sorta', function(e) {
		$('.loader-image').show();
		e.preventDefault();
		loader();
		$(this).closest('.sortul').find('.active').removeClass('active');
		//Add to sibling
		$(this).siblings().addClass('active');
	});
	$('.loader').on('click', function(e) {
		$('.loader-image').show();
		e.preventDefault();
		loader();
	});
	/* $('#hotel-name-search-btn').on('click', function(e) {
		$('.loader-image').show();
		e.preventDefault();
		ini_hotel_namef();
		filter_rom();
	}); */
	

	// $(document).on('change', 'input.hotel-location', function(e) {
	// 	$('.loader-image').show();
	// 	loader();
	// 	ini_hotel_locf();
	// 	filter_rom();
	// });

	$(document).on('change', 'input.hotel-location', function (e) {
        loader();
        ini_hotel_locf();
        applyAllFiltersAndSorting();
    });

    $(document).on('change', 'input.hotel-facilities', function (e) {
        loader();
        ini_hotel_fac();
        applyAllFiltersAndSorting();
    });



	$('.deal-status-filter').on('change', function(e) {
		$('.loader-image').show();
		loader();
		ini_dealf();
		applyAllFiltersAndSorting();
	});
	$('.freecancel-hotels-view').on('change',function(e){
		$('.loader-image').show();
		loader();
		ini_free_cancel();
		applyAllFiltersAndSorting();
		
	});
	$('.wifi-hotels-view').on('change',function(e){
		$('.loader-image').show();
		loader();
		ini_wifi();
		filter_rom();
		
	});
	$('.break-hotels-view').on('change',function(e){
		$('.loader-image').show();
		loader();
		ini_breakfast();
		filter_rom();
		
	});
	$('.parking-hotels-view').on('change',function(e){
		$('.loader-image').show();
		loader();
		ini_parking();
		filter_rom();
		
	});
	$('.pool-hotels-view').on('change',function(e){
		$('.loader-image').show();
		loader();
		ini_swim_pool();
		filter_rom();
		
	});
	/*
	$(document).on('change', '.star-filter', function(e) {
		$('.loader-image').show();
		loader();
		var thisEle = this;
		var _filter = '';
		var attr = {};
		attr['checked'] = $(thisEle).is(':checked');
		ini_starf();
		//console.log(_filter);
		//console.log(attr);
		filter_rom('star-filter', _filter, attr);
	});
	*/

	// When clicking anywhere on the star element
	$(document).on('click', '.starone', function(e) {
	    e.preventDefault(); // Prevent default <a> click behavior

	    var checkbox = $(this).find('input.star-filter');

	    // Toggle checked state manually
	    checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
	});
	
	$(document).on('change', 'input.star-filter', function (e)
     {
        loader();

        var thisEle = this;
        var _filter = '';
        var attr = {};
        attr['checked'] = $(thisEle).is(':checked');

        ini_starf();
        applyAllFiltersAndSorting();
        //filter_star('star-filter', _filter, attr);

        //filter_rom('star-filter', _filter, attr);
    });

	//Balu A - Setting minimum and maximum price for slider range
	function update_range_slider(minPrice, maxPrice) {
		$('#core_minimum_range_value', '#core_min_max_slider_values').val(minPrice);
		$('#core_maximum_range_value', '#core_min_max_slider_values').val(maxPrice);
		//price-refine
		enable_price_range_slider(minPrice, maxPrice);
	}
	//Reset the filters -- Balu A
	$(document).on('click', '#reset_filters,#reset_all_filters', function () 
    {
        loader();
        $('#npl_img').show();
        //Reset the Star,and Location Filters
        $('#starCountWrapper .enabled').each(function () {
            $(this).removeClass('active');
            $('.star-filter', this).prop('checked', false);
        });
        $('input.hotel-location').prop('checked', false);
        $(".freecancel-hotels-view").prop('checked', false);
        $('.wifi-hotels-view').prop('checked', false);
        $('.pool-hotels-view').prop('checked', false);
        $('.parking-hotels-view').prop('checked', false);
        $('.break-hotels-view').prop('checked', false);
        $('.hotel-facilities').prop('checked', false);
        $('.hotel-location').prop('checked', false);
        $('.hotel-meals').prop('checked', false);
        $('.hotel-property').prop('checked', false);
        $('.deal-status-filter').prop('checked', false);

           $('.distance1').prop('checked', false);
           $('.distance2').prop('checked', false);
           $('.distance3').prop('checked', false);
           $('.distance4').prop('checked', false);
        //HotelName
        $('#hotel-name').val(''); //Hotel Name

        $(".select_sort").closest('.sortul').find('.active').removeClass('active');
        $(".select_sort button").text("Recommended");
        $('.recommended').addClass('active');
        
        var minPrice = $('#core_minimum_range_value', '#core_min_max_slider_values').val();
        var maxPrice = $('#core_maximum_range_value', '#core_min_max_slider_values').val();

        set_slider_label(minPrice, maxPrice);

        $("#price-range").slider("option", "values", [minPrice, maxPrice]);
        inif();
        
        $('.deal-status-filter:checked').val('all');
        $('.freecancel-hotels-view:checked').val('all');
        sorted_hotels = sortingFirstResponse(all_hotels);

        //console.log(sorted_hotels);

        applyAllFiltersAndSorting();
    });
	/**
	 * Show loader images
	 */
	function loader() {
		$('.container').css({
			'opacity': '.1'
		});
		setTimeout(function() {
			$('.container').css({
				'opacity': '1'
			}, 'slow');
		}, 1000);
	}
	//------ INI F
	var _ini_fil = {};
	_ini_fil['_sf'] = [];
	function ini_starf()
	{
		_ini_fil['_sf'] = $('input.star-filter:checked').map(function() {
			return this.value;
		}).get();
	}
	
	_ini_fil['hl'] = [];
	function ini_hotel_locf()
	{
		_ini_fil['hl'] = $('.hotel-location:checked', '#hotel-location-wrapper').map(function() {
			return this.value;
		}).get();
		//console.log(_ini_fil['hl']);
	}

	_ini_fil['hf'] = [];

	function ini_hotel_fac()
    {
         _ini_fil['hf'] = $('.hotel-facilities:checked', '#hotel-amenitie-wrapper').map(function () {
            return this.value.trim().toLowerCase();
        }).get();
    }
	
	_ini_fil['min_price'] = 0;
	_ini_fil['max_price'] = 0;
	function ini_pricef()
	{
		_ini_fil['min_price'] = parseFloat($("#price-range").slider("values")[0]);
		_ini_fil['max_price'] = parseFloat($("#price-range").slider("values")[1]);
	}
	
	_ini_fil['hn_val'] = '';
	function ini_hotel_namef()
	{
		_ini_fil['hn_val'] = $('#hotel-name').val().trim().toLowerCase();
	}
	
	_ini_fil['dealf'] = false;
	function ini_dealf()
	{
		if ($('.deal-status-filter:checked').val() == 'filter') {
			_ini_fil['dealf'] = true;
		} else {
			_ini_fil['dealf'] = false;
		}
	}
	
	 _ini_fil['refundable_value'] = false;
    _ini_fil['nonrefundable_value'] = false;
    function ini_free_cancel()
    {
        if ($('#refund:checked').val() == 'refundable') {
            _ini_fil['refundable_value'] = true;
        } else {
            _ini_fil['refundable_value'] = false;
        }

        if ($('#nonrefund:checked').val() == 'nonrefundable') {
            _ini_fil['nonrefundable_value'] = true;
        } else {
            _ini_fil['nonrefundable_value'] = false;
        }

    }
    
	_ini_fil['wifi'] = false;
	function ini_wifi()
	{	
		if($('.wifi-hotels-view:checked').val()=='filter'){
			_ini_fil['wifi'] = true;
		}else{
			_ini_fil['wifi'] = false;
		}		
	}
	_ini_fil['breakfast'] = false;
	function ini_breakfast()
	{	
		if($('.break-hotels-view:checked').val()=='filter'){
			_ini_fil['breakfast'] = true;
		}else{
			_ini_fil['breakfast'] = false;
		}		
	}
	_ini_fil['parking'] = false;
	function ini_parking()
	{	
		if($('.parking-hotels-view:checked').val()=='filter'){
			_ini_fil['parking'] = true;
		}else{
			_ini_fil['parking'] = false;
		}		
	}
	_ini_fil['swim_pool'] = false;
	function ini_swim_pool()
	{	
		if($('.pool-hotels-view:checked').val()=='filter'){
			_ini_fil['swim_pool'] = true;
		}else{
			_ini_fil['swim_pool'] = false;
		}		
	}
	function inif()
	{
		ini_starf();
		ini_hotel_locf();
		ini_hotel_fac();
		ini_pricef();
		ini_hotel_namef();
		ini_dealf();
		ini_free_cancel();
		ini_wifi();
		ini_breakfast();
		ini_parking();
		ini_swim_pool();
	}
	//------ INI F
	/**
	 * _filter_trigger	==> element which caused fliter to be triggered
	 * _filter			==> default filter settings received from filter trigger
	 */
	function filter_rom(_filter_trigger, _filter, attr) {
		inif();
		//if (_fltr_r_cnt == _total_r_cnt && _offset >= _total_r_cnt) {
		
		if (dynamic_page_data == false) {
			if (_filter_trigger == 'star-filter') {
				if ((attr['checked'] == false && _ini_fil['_sf'].length > 0) || (attr['checked'] == true && _ini_fil['_sf'].length == 1)) {
					_filter = ':visible';
				} else {
					_filter = ':hidden';
				}
			} else {
				_filter = '';
			}
		
			_fltr_r_cnt = 0;
			// console.log(_ini_fil['min_price']);
			// console.log('----------');
			// console.log(_ini_fil['max_price']);
			
			//$('.r-r-i' + _filter) : FIXME
			$('.r-r-i').each(function(key, value) {
				
				var _rmp = parseInt($('.h-p', this).text());
				var _rhn = $('.h-name', this).text().trim().toLowerCase();
				var _rhl = $('.h-loc', this).text();
				var _rde = $('.deal-status', this).data('deal');
				var _free = $('.free_cancel',this).data('free-cancel');
				var _wifi = $('.wifi',this).data('wifi');
				var _breakfast = $('.breakfast',this).data('breakfast');
				var _parking = $('.parking',this).data('parking');
				var _swim_pool = $('.pool',this).data('pool');
				
				if (
					((_ini_fil['_sf'].length == 0) || ($.inArray(($('.h-sr', this).text()), _ini_fil['_sf']) != -1)) &&
					(_rmp >= _ini_fil['min_price'] && _rmp <= _ini_fil['max_price']) &&
					((_rhn == "" || _ini_fil['hn_val'] == "") || (_rhn.search(_ini_fil['hn_val']) > -1)) &&
					((_rhl == "" || _ini_fil['hl'].length == 0) || ($.inArray((_rhl), _ini_fil['hl']) != -1)) &&
					(_ini_fil['dealf'] == false || (_ini_fil['dealf'] == true && _rde == true)) && 
					(_ini_fil['free_cancel'] == 0 || (_ini_fil['free_cancel'] == 1 && _free == 1))&&
					(_ini_fil['wifi'] == 0 || (_ini_fil['wifi'] == 1 && _wifi == 1))&&
					(_ini_fil['breakfast'] == 0 || (_ini_fil['breakfast'] == 1 && _breakfast == 1))&&
					(_ini_fil['parking'] == 0 || (_ini_fil['parking'] == 1 && _parking == 1))&&
					(_ini_fil['swim_pool'] == 0 || (_ini_fil['swim_pool'] == 1 && _swim_pool == 1))
					) {
					++_fltr_r_cnt;
					$(this).removeClass('hide');
				} else {
					$(this).addClass('hide');
				}
			});
			
		} else {
			
			//filter from backend
			dynamic_filter_rom();
		}
		//update_total_count_summary();
	}
	
	function dynamic_filter_rom()
	{
		//console.log("Elavarasi");
		//-- empty results and show loader
		show_result_pre_loader();
		$('#hotel_search_result').empty();
		_offset = 0;
		//_ini_fil['image_order'] =true; 
		load_hotels(process_result_update, _offset, _ini_fil);
	}
	//Balu A
	function dynamic_sorter_rom()
	{
		//-- empty results and show loader
		show_result_pre_loader();
		$('#hotel_search_result').empty();
		_offset = 0;
		
		load_hotels(process_result_update, _offset, _ini_fil);
	}
	/**
	 *Update Hotel Count Details
	 */
	function update_total_count_summary() {
        $('#hotel_search_result').show();

        //console.log(_fltr_r_cnt);

        if (isNaN(_fltr_r_cnt) == true || _fltr_r_cnt < 1) {
            _fltr_r_cnt = 0;

            //display warning

            if(booking_source_arr.length>0)
            {
                $('#hotel_search_result').show();
                $('#empty_hotel_search_result').hide();
            }
            else
            {
                $('#hotel_search_result').hide();
                $('#empty_hotel_search_result').show();
            }

        } else {
            $('#hotel_search_result').show();
            $('#empty_hotel_search_result').hide();
        }

        //$('#total_records').text(_total_r_cnt);
        $('.total-row-record-count').text(_total_r_cnt);
        $('#filter_records').text(_fltr_r_cnt);
    }
	var sliderCurrency = application_preference_currency;
	//application_preference_currency
	var min_amt = 0;
	var max_amt = 0;
	
function enable_price_range_slider(minStar, maxStar) 
{
    // Clear any existing slider
    $("#price-range").empty();

    $("#price-range").slider({
        range: true,
        min: minStar,
        max: maxStar,
        values: [minStar, maxStar],
        slide: function (event, ui) {
            set_slider_label(ui.values[0], ui.values[1]);
        },
        change: function (e, ui) {
            if ('originalEvent' in e) {
                loader();

                // Get the price range values
                var minPrice = ui.values[0];
                var maxPrice = ui.values[1];

                // console.log(minPrice);
                // console.log(maxPrice);

                // Filter and sort hotels by price within the selected range

                sorted_hotels = (sorted_hotels.length ? sorted_hotels : all_hotels).filter(function (hotel) {
                    var price = parseFloat(hotel.Price.RoomPrice) || 0;
                    return price >= minPrice && price <= maxPrice;
                });

                // Reset the offset for infinite scroll
                hotelOffset = 0;

                // Clear the existing hotels container
                $("#t-w-i-1").empty();
                applyAllFiltersAndSorting(minPrice,maxPrice);
            }
        }
    });

    set_slider_label(minStar, maxStar);
}

	function set_slider_label(val1, val2) {
		$("#hotel-price").text(sliderCurrency + val1 + " - " + sliderCurrency + val2);
	}
	function enable_location_selector(locs) {
		var _location_option_list = '';
		var i = 0;
		$.each(locs, function(k, v) {
			_location_option_list += '<li>';
			_location_option_list += '<div class="squaredThree">';
			_location_option_list += '<input id="locSquaredThree' + i + '" class="hotel-location" type="checkbox" name="check" value="' + v['v'] + '">';
			_location_option_list += '<label for="locSquaredThree' + i + '"></label>';
			_location_option_list += '</div>';
			_location_option_list += '<label class="lbllbl" for="locSquaredThree' + i + '">' + v['v'] + '('+v['c']+')</label>';
			_location_option_list += '</li>';
			i++;
		});
		$('#hotelLoclabel').html('<span class="glyphicon glyphicon-chevron-down" ></span>Hotel Location');
		$('#hotel-location-wrapper').html(_location_option_list);
	}


	function enable_hotel_facilities_selector(facilities) 
    {
        var _facilities_option_list = '';
        var i = 0;

        $.each(facilities, function (k, v) 
        {
            
            _facilities_option_list += '<li>';
            _facilities_option_list += '<div class="squaredThree">';
            _facilities_option_list += '<input type="checkbox" id="facSquaredThree' + i + '" value="' + v['v'].trim().toLowerCase() + '" class="hotel-facilities" name="amenitie[]">';
            _facilities_option_list += '<label for="facSquaredThree' + i + '"></label>';
            _facilities_option_list += '</div>';
            _facilities_option_list += '<label class="lbllbl" for="facSquaredThree' + i + '">' + v['v'] +'</label>';
            _facilities_option_list += '</li>';
            i++;
        });
        	if(_facilities_option_list!="")
        	{
        		$('#hotel-amenitie-wrapper').html(_facilities_option_list); 
        	}        
    }

    function updateRefundOptions(hotels) 
    {

	    let hasRefundable = false;
	    let hasNonRefundable = false;

	    $.each(hotels, function (i, hotel) {
	        if (hotel.IsRefundable == true || hotel.free_cancellation == true) {
	            hasRefundable = true;
	        }
	        if (hotel.IsRefundable == false || hotel.free_cancellation == false) {
	            hasNonRefundable = true;
	        }
	    });

	    // Refundable checkbox
	    if (hasRefundable) {
	        $('#refund').prop('disabled', false);
	    } else {
	        $('#refund').prop('disabled', true).prop('checked', false);
	        _ini_fil['refundable_value'] = false;
	    }

	    // Non-Refundable checkbox
	    if (hasNonRefundable) {
	        $('#nonrefund').prop('disabled', false);
	    } else {
	        $('#nonrefund').prop('disabled', true).prop('checked', false);
	        _ini_fil['nonrefundable_value'] = false;
	    }
	}





	function enable_star_wrapper(star_sum) {
		loadStarFilter(star_sum);
	}
	function loadStarFilter(star_count_array) {
		var starCat = 0;
		$('#starCountWrapper .star-filter').each(function(key, value) {
			starCat = parseInt($(this).val());
			if ($.isEmptyObject(star_count_array[starCat]) == true) {
				//disabled
				$(this).attr('disabled', 'disabled');
				$(this).closest('.star-wrapper').addClass('disabled');
			} else {
				$(this).closest('.star-wrapper').addClass('enabled');
			}
		});
	}
	function unique_array_values(array_values) {
		var _unique_array_values = [];
		$.each(array_values, function(k, v) {
			if (_unique_array_values.indexOf(v) == -1) {
				_unique_array_values.push(v);
			}
		});
		return _unique_array_values;
	}
	function get_location_list() {
		return $('.h-loc').map(function() {
			return $(this).text();
		});
	}
	$('.vlulike').click(function() {
		$('.vlulike').removeClass('active');
		$(this).addClass('active');
	});
	//MAP
    /*Map view click function*/
    // Flag to prevent multiple simultaneous map initializations
    var mapInitializing = false;
    
    $('.map_click').click(function(e) {
        // Prevent multiple simultaneous clicks
        if ($(this).data('processing')) {
            return false;
        }
        $(this).data('processing', true);
        
        // Reset flag after a delay
        var self = this;
        setTimeout(function() {
            $(self).data('processing', false);
        }, 1000);

		$('.rowresult.r-r-i').removeClass('marker_highlight');
        
        //window.location.reload();
      //  console.log("_offset"+_offset);
        //load_hotels(process_result_update,120, _ini_fil);
        //reset_ini_map_view();//set up master map markers
        $('.allresult').addClass('map_open');
        $('.view-toggle-btn').removeClass('active');
        // Ensure the map button gets active class
        $('#map_clickid').addClass('active');
        $(this).addClass('active');
        $(".resultalls").addClass("fulview");
         $('.rowresult').removeClass("col-4");
         $('#hotel_search_result .item').removeClass('grid-group-item');
        $(".coleft").hide();
        $('#hotel_search_result').show();
        
        // Show map containers first
        $('.hotel_map').show();
        $('#map').show().css({
            'display': 'block',
            'width': '100%',
            'height': '100%',
            'min-height': '650px'
        });
        
        // Initialize map if not already initialized
        if (mapInitializing) {
            return false;
        }
        
        setTimeout(function() {
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                var mapContainer = document.getElementById("map");
                if (mapContainer) {
                    // Ensure container is visible and has dimensions
                    if ($(mapContainer).is(':hidden')) {
                        $(mapContainer).show();
                    }
                    
                    // Ensure container has proper dimensions
                    var containerHeight = $(mapContainer).height();
                    var containerWidth = $(mapContainer).width();
                    if (containerHeight === 0 || containerWidth === 0) {
                        $(mapContainer).css({
                            'width': '100%',
                            'height': '650px',
                            'min-height': '650px'
                        });
                    }
                    
                    if (typeof map === 'undefined' || !map) {
                        // Map not initialized, create it
                        mapInitializing = true;
                        
                        // Get lat/lon from page data or use defaults
                        var mapLat = (typeof lat !== 'undefined' && lat && !isNaN(lat)) ? parseFloat(lat) : 25.2048;
                        var mapLon = (typeof lon !== 'undefined' && lon && !isNaN(lon)) ? parseFloat(lon) : 55.2708;
                        
                        // Try to get from data attributes if not set
                        if ((!lat || isNaN(lat)) && $('.hotel_location_city').length) {
                            var dataLat = $('.hotel_location_city').data('lat');
                            var dataLon = $('.hotel_location_city').data('lon');
                            if (dataLat && !isNaN(dataLat)) mapLat = parseFloat(dataLat);
                            if (dataLon && !isNaN(dataLon)) mapLon = parseFloat(dataLon);
                        }
                        
                        var myCenter = new google.maps.LatLng(mapLat, mapLon);
                        
                        var mapProp = {
                            center: myCenter, 
                            zoom: 10,
                            draggable: true,
                            scrollwheel: true,
                            styles: (typeof styles !== 'undefined' ? styles : []),
                            gestureHandling: 'auto',
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        
                        try {
                            map = new google.maps.Map(mapContainer, mapProp);
                            
                            // Wait for map to fully render, then set up markers (only once)
                            var markersSetup = false;
                            google.maps.event.addListenerOnce(map, 'idle', function() {
                                if (!markersSetup) {
                                    markersSetup = true;
                                    reset_ini_map_view();
                                }
                                mapInitializing = false;
                            });
                            
                            // Fallback timeout in case 'idle' event doesn't fire
                            setTimeout(function() {
                                if (map && !markersSetup) {
                                    markersSetup = true;
                                    reset_ini_map_view();
                                    mapInitializing = false;
                                }
                            }, 1000);
                        } catch (e) {
                            console.error('Error initializing map:', e);
                            mapInitializing = false;
                        }
                    } else {
                        // Map exists, resize and refresh (only if not already initializing)
                        if (!mapInitializing) {
                            setTimeout(function() {
                                google.maps.event.trigger(map, 'resize');
                                setTimeout(function() {
                                    resizeMap();
                                    reset_ini_map_view();
                                }, 200);
                            }, 200);
                        }
                    }
                } else {
                    console.error('Map container not found');
                }
            } else {
                console.error('Google Maps API not loaded');
            }
        }, 300);
		
		/* $(document).on("mouseover",".madgrid",function(){
			alert("hii");
		});
		$(document).on("mouseout",".madgrid",function(){
			alert("hello");
		});*/
    //reset_ini_map_view();
    });
	
	
	
    function map_point_load(){
    	 resizeMap();
        reset_ini_map_view();//set up master map markers
        //window.location.reload();
        //reset_ini_map_view();//set up master map markers
        $('.allresult').addClass('map_open');
        $('.view_type').removeClass('active');
        $(this).addClass('active');
        $(".allresult").niceScroll({
            styler: "fb",
            cursorcolor: "#4ECDC4",
            cursorwidth: '3',
            cursorborderradius: '10px',
            background: '#404040',
            spacebarenabled: false,
            cursorborder: ''
        });
        setTimeout(function() {
            google.maps.event.trigger(map, 'resize');
        }, 500);
		
    }
	
	
    $('.list_click').click(function() {
        $(".resultalls").removeClass("fulview");
        $('#hotel_search_result .item').removeClass('grid-group-item');
        $('.hotel_map').hide();
        $('#map').hide();
        $('.rowresult').removeClass("col-4");
        $(".coleft").show();
        $('.view-toggle-btn').removeClass('active');
        // Ensure the list button gets active class
        $('#list_clickid').addClass('active');
        $(this).addClass("active");
        $('.allresult').removeClass('map_open');
    });
    // DEFINE YOUR MAP AND MARKER 
    var map;
    var lat = $('.hotel_location_city').data('lat');
    var lon = $('.hotel_location_city').data('lon');
    // alert(lat);
    // alert(lon);
    lat = 53;
    lon =-1.33;
    var styles = [
    {
     featureType: "landscape",
      "elementType": "labels.text.stroke",
     stylers: [
      { color: '#cad6db' }
     ]
     
    },
     {
        "featureType": "all",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#cad6db"
            }   
        ],
   "stylers1": [
            {
                "color": "#cad6db"
            }   
        ]
    },
   
    
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers1": [
            {
               
    "color": "#cad6db"
            },
            
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                
    "color": "#cad6db"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                
    "color": "#cad6db"
            },
            
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
            {
               
    "color": "#cad6db"
            },
           
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.stroke",
        "stylers": [
            
            {
                
    "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "invert_lightness": true
            },
            {
                "saturation": 60
            },
            {
                "lightness": 60
            },
            {
                "gamma": 1
            },
            {
                "hue": "#11b6df"
            }
   
        ]
    }
    
   ];
   
  	var myCenter=new google.maps.LatLng(lat, lon);
	var marker=new google.maps.Marker({
	    position:myCenter
	});
	var infowindow;
	var currentOpenInfoWindow = null; // Track currently open info window
    (function() {
        google.maps.Map.prototype.markers = new Array();
        google.maps.Map.prototype.addMarker = function(marker) {
            this.markers[this.markers.length] = marker;
        };
        google.maps.Map.prototype.getMarkers = function() {
            return this.markers
        };
        google.maps.Map.prototype.clearMarkers = function() {
            if (infowindow) {
                infowindow.close();
            }
            for (var i = 0; i < this.markers.length; i++) {
                this.markers[i].set_map(null);
            }
        };
    })();
    function initialize() {
        var mapProp = {
            center: myCenter, 
            zoom: 10,
		    draggable: true,
		    scrollwheel: false,
		    styles: styles,
		    gestureHandling: 'none',
          	//zoomControl: false,
		   
		    mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map"), mapProp);
        marker.setMap(map);
       // console.log("elaasjajsdjsdsdf");
        infoWindow = new google.maps.InfoWindow();
        google.maps.event.addListener(marker, 'click', function() {
            //infowindow.setContent(infowindow);
            infowindow.open(map, marker);
        });
    };

    google.maps.event.addDomListener(window, 'load', initialize);
    google.maps.event.addDomListener(window, "resize", resizingMap());
    $('#map_view_hotel').on('show.bs.modal', function() {
        //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)alert("xx");
        resizeMap();
    })
    function resizeMap() {
        if (typeof map == "undefined") return;
        setTimeout(function() {
            resizingMap();
        }, 600);

        /*********** resize map for siderbar map view *******************/
         if (typeof sidemap == "undefined") return;
        setTimeout(function() {
            resizingSidebarMap();
        }, 600);
    }
    function resizingMap() {
        if (typeof map == "undefined") return;
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
    }

    function resizingSidebarMap() {
        if (typeof sidemap == "undefined") return;
        var center = sidemap.getCenter();
        google.maps.event.trigger(sidemap, "resize");
        sidemap.setCenter(center);
    }
	
    /**
	 * Set value are data on map
	 */
    var marker;
    //var markers = [];
    var min_lat = 0;
    var max_lat = 0;
    var min_lon = 0;
    var max_lon = 0;
    //initialize master map object
    function reset_ini_map_view()
    {
		
        ini_map_view();
    }
	
    function ini_map_view() {		
     	var counter=0;
    	if (max_lat == 0) {
			max_lat = min_lat = 0;
		}
		if (max_lon == 0) {
			max_lon = min_lon = 0;
		} 
		
        $('#hotel_search_result .r-r-i').each(function() {        	
            set_marker_visibility(this);           
        	var lat = parseFloat($(this).find('.hotel_location').data('lat'));
			var lon = parseFloat($(this).find('.hotel_location').data('lon'));
			if((typeof(lat)!='undefined') && (lat!='') && !isNaN(lat) && (counter<1)){
				max_lat = min_lat = lat;
			}
			if((typeof(lon)!='undefined') && (lon!='') && !isNaN(lon)){
				counter++;					
				max_lon = min_lon = lon;
			}
        });
        $(".spinner").hide();
    	$(".map_hotel").removeClass('hide');
		 //max_lat = $('.hotel_location').data('lat')
		 //max_lon = $('.hotel_location').data('lon')
		
		if(typeof map=='object'){
			map.setCenter(new google.maps.LatLng(max_lat, max_lon));	
		}
		
		// Close info windows when clicking on map
		google.maps.event.addListener(map, 'click', function(e) {
			if (currentOpenInfoWindow) {
				currentOpenInfoWindow.close();
				currentOpenInfoWindow = null;
			}
			// Reset all marker icons
			for (var key in markers) {
				if (markers[key] && markers[key].hoverTimeout) {
					clearTimeout(markers[key].hoverTimeout);
					markers[key].hoverTimeout = null;
				}
				if (markers[key] && markers[key].setIcon) {
					markers[key].setIcon(normalIcon());
				}
			}
		});
      	
    }
    

    function ini_side_map_view()
    {
        // Check if Google Maps API is loaded
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            console.error('Google Maps API is not loaded');
            return;
        }
        
        // Check if map container exists
        var mapContainer = document.getElementById("sidemap_view");
        if (!mapContainer) {
            console.error('Map container #sidemap_view not found');
            return;
        }

    /*********** Initialize map for siderbar map view *******************/
        
        // Get lat/lon - use existing lat/lon variables if available, otherwise use defaults
        var sideLat = (typeof lat !== 'undefined' && lat && !isNaN(lat)) ? lat : 25.2048; // Dubai default
        var sideLon = (typeof lon !== 'undefined' && lon && !isNaN(lon)) ? lon : 55.2708; // Dubai default
        
        // Initialize myCenter for side map
        var sideMyCenter = new google.maps.LatLng(sideLat, sideLon);
        
        // Initialize styles if not already set
        if (typeof styles === 'undefined') {
            styles = [];
        }

    	var mapPropSide = {
            center: sideMyCenter, 
            zoom: 10,
		    draggable: true,
		    scrollwheel: false,
		    styles: (typeof styles !== 'undefined' ? styles : []),
		    gestureHandling: 'none',
          	//zoomControl: false,
		   
		    mapTypeId:google.maps.MapTypeId.ROADMAP
        };

        try {
            sidemap = new google.maps.Map(mapContainer, mapPropSide);
        } catch (e) {
            console.error('Error initializing side map:', e);
            return;
        }

    	//console.log(sidemap);

    	var counter=0;
    	if (max_lat == 0) {
			max_lat = min_lat = 0;
		}
		if (max_lon == 0) {
			max_lon = min_lon = 0;
		} 
		
        $('#hotel_search_result .r-r-i').each(function() {        	
            var object = {};
	        object['lat'] = parseFloat($('.hotel_location', this).data('lat'));
	        object['lon'] = parseFloat($('.hotel_location', this).data('lon'));
	        object['name'] = $('.h-name', this).text();
	        object['star'] = $('.h-sr', this).text();

	        //console.log(object);

	        create_sidemap_marker(object);

        	var lat = parseFloat($(this).find('.hotel_location').data('lat'));
			var lon = parseFloat($(this).find('.hotel_location').data('lon'));
			if((typeof(lat)!='undefined') && (lat!='') && !isNaN(lat) && (counter<1)){
				max_lat = min_lat = lat;
			}
			if((typeof(lon)!='undefined') && (lon!='') && !isNaN(lon)){
				counter++;					
				max_lon = min_lon = lon;
			}
        });
		
		if(typeof sidemap=='object' && max_lat != 0 && max_lon != 0){
			sidemap.setCenter(new google.maps.LatLng(max_lat, max_lon));
		} else if(typeof sidemap=='object'){
			// Use default center if no hotel coordinates found
			sidemap.setCenter(sideMyCenter);
		}
    }
	
    //function show_on_map()
	
    function get_map_attr_obj(thisRef, lat, lon, name, star)
    {
        var object = {};
        object['lat'] = parseFloat(lat) || parseFloat($('.hotel_location', thisRef).data('lat'));
        object['lon'] = parseFloat(lon) || parseFloat($('.hotel_location', thisRef).data('lon'));
        object['name'] = name || $('.h-name', thisRef).text();
        object['star'] = star || $('.h-sr', thisRef).text();
       // console.log("star"+object['star']);
 	 var hoteldetailsLink = $(".hoteldetailsLink",thisRef).attr('href');
	 var booking_url = hoteldetailsLink;
        object['details_url'] = booking_url;
        object['img'] = $('.h-img', thisRef).data('src');

        object['acc_key'] = '1_'+marker_access_key(object['lat'], object['lon']);
        if (object['img'] == '' || object['img'] == undefined) {
           object['img'] = $('.h-img', thisRef).attr('src');
        }
        var price_text = $('#pri_app_pref_currency').val();
        //object['curr'] = $('.currency_symbol', thisRef).text();
        object['curr'] =  price_text;
        object['price'] = $('.h-p', thisRef).text();
        return object;
    }
	function get_map_attr_obj_new(thisRef)
    {
        var object = {};
        object['lat'] = parseFloat(thisRef.Latitude);
        object['lon'] = parseFloat(thisRef.Longitude);
        object['name'] = thisRef.HotelName;
        object['star'] = thisRef.StarRating;    	
        var booking_url = '';
        booking_url = app_base_url+'index.php/hotel/hotel_details/'+
        _search_id+'?ResultIndex='+thisRef.MResultToken+'&booking_source='+active_booking_source
        +'&op=get_details';
        object['details_url'] = booking_url;
        if(thisRef.HotelPicture){
        	object['img'] = thisRef.HotelPicture;	
        }else{
        	object['img'] = api_url+'default_hotel_img.jpg';
        }
        
        object['acc_key'] = marker_access_key(object['lat'], object['lon']);
        // if (object['img'] == '') {
        //     $('.h-img', thisRef).attr('src');
        // }
        object['curr'] = $('.currency_symbol').text();
         var price = 0;
        var price = thisRef.Price.RoomPrice;
        object['price'] = price;
        return object;
    }
    function marker_access_key(lat, lon)
    {
        return lat+'_'+lon;
    }
	
    function set_marker_visibility(thisRef, lat, lon, name, star)
    {
    	if(typeof thisRef !='undefined'){
	        var lat = parseFloat($('.hotel_location', thisRef).data('lat'));
	        var lon = parseFloat($('.hotel_location', thisRef).data('lon'));
	       //console.log("lat"+lat);
	       //console.log("lon"+lon);
	        var access_key = marker_access_key(lat, lon);
	        var visibility = true;
	        if ($(thisRef).is(':visible') == false) {
	            visibility = false;
	        //console.log('data is hidden');
	        }
	        if ($.isEmptyObject(markers[access_key]) == true) {
	            //console.log('Access key not found '+access_key);
	            $(thisRef).attr('access_key', access_key);
	            var object = get_map_attr_obj(thisRef, lat, lon, name, star);
	            create_marker(object, visibility);
	            
	        } else {
	            //toggle visibility
	            //console.log('already present so setting visibility');
	            markers[access_key].setVisible(visibility);
	            markers[access_key].setIcon(normalIcon());
	            console.log("called2");
	        }
	    }
        
    }

    function create_sidemap_marker(obj)
    {
        var marker = new google.maps.Marker({
        	
            map: sidemap,
            draggable: false,
            position: {
                lat: obj['lat'], 
                lng: obj['lon']
                },
            title: obj['name'],
            visible: true,
            icon:api_url+'marker/hotel_map_marker.png'     
        });       
    }

    /**
	 * Add marker
	 */
    function create_marker(obj, visibility)
    {
        max_lon = (max_lon < obj['lon'] ? obj['lon'] : max_lon);
        min_lon = (min_lon < obj['lon'] ? obj['lon'] : min_lon);
        max_lat = (max_lat < obj['lat'] ? obj['lat'] : max_lat);
        min_lat = (min_lat < obj['lat'] ? obj['lat'] : min_lat);
         var star_rating = '';
        for (var i =1; i <= obj['star']; i++) {
        	star_rating +='<span class="fa fa-star"></span>';
        }
        // Enhanced Info Window Content with Beautiful UI
        var contentString =
        '<div class="hotel-map-info-window">'+
        '<div class="hotel-map-info-image">'+
        '<img src="'+obj['img']+'" alt="'+obj['name']+'" onerror="this.src=\''+api_url+'default_hotel_img.jpg\'">'+
        '<div class="hotel-map-info-price-badge">'+
        '<span class="hotel-map-currency">'+obj['curr']+'</span>'+
        '<span class="hotel-map-price">'+obj['price']+'</span>'+
        '</div>'+
        '</div>'+
        '<div class="hotel-map-info-content">'+
        '<h3 class="hotel-map-info-name">'+obj['name']+'</h3>'+
        '<div class="hotel-map-info-rating" data-star="'+obj['star']+'">'+star_rating+'</div>'+
        '<a href="'+obj['details_url']+'" class="hotel-map-info-book-btn">View Details & Book</a>'+
        '</div>'+
        '</div>';
        
        // Create custom marker icon with pin shape
        var markerIcon = normalIcon();
        
        var marker = new google.maps.Marker({
            map: map,
            draggable: false,
            position: {
                lat: obj['lat'], 
                lng: obj['lon']
            },
            title: obj['name'],
            visible: visibility,
            icon: markerIcon,
            animation: google.maps.Animation.DROP,
            optimized: false
        });
        
        // Create info window instance for this marker
        var markerInfoWindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 320,
            pixelOffset: new google.maps.Size(0, -10)
        });
        
        // Store info window reference on marker
        marker.infoWindow = markerInfoWindow;
        marker.hotelData = obj;
        
        // Store hover timeout on marker
        marker.hoverTimeout = null;
        
        // Single mouseover listener - show info window
        marker.addListener('mouseover', function() {
            // Clear any pending timeout for this marker
            if (marker.hoverTimeout) {
                clearTimeout(marker.hoverTimeout);
                marker.hoverTimeout = null;
            }
            
            // Close any currently open info window first
            if (currentOpenInfoWindow && currentOpenInfoWindow !== markerInfoWindow) {
                currentOpenInfoWindow.close();
            }
            if (typeof infowindow !== 'undefined' && infowindow && infowindow !== markerInfoWindow) {
                infowindow.close();
            }
            
            // Change marker appearance on hover
            marker.setIcon(highlightedIcon());
            
            // Show info window
            markerInfoWindow.open(map, marker);
            currentOpenInfoWindow = markerInfoWindow;
        });
        
        // Mouse out event - close info window and reset marker after delay
        marker.addListener('mouseout', function() {
            // Reset marker icon immediately
            marker.setIcon(markerIcon);
            
            // Close info window after a short delay (allows moving to info window)
            marker.hoverTimeout = setTimeout(function() {
                if (currentOpenInfoWindow === markerInfoWindow) {
                    markerInfoWindow.close();
                    currentOpenInfoWindow = null;
                }
            }, 300);
        });
        
        // Click event - toggle info window
        marker.addListener('click', function() {
            // Clear hover timeout
            if (marker.hoverTimeout) {
                clearTimeout(marker.hoverTimeout);
                marker.hoverTimeout = null;
            }
            
            // Close any other open info windows
            if (currentOpenInfoWindow && currentOpenInfoWindow !== markerInfoWindow) {
                currentOpenInfoWindow.close();
            }
            if(infowindow && infowindow !== markerInfoWindow) {
                infowindow.close();
            }
            
            // Toggle current info window
            if (currentOpenInfoWindow === markerInfoWindow && markerInfoWindow.getMap()) {
                markerInfoWindow.close();
                currentOpenInfoWindow = null;
            } else {
                markerInfoWindow.open(map, marker);
                currentOpenInfoWindow = markerInfoWindow;
            }
            infowindow = markerInfoWindow;
        });
        
        // Listen for info window close event (when user clicks close button)
        markerInfoWindow.addListener('closeclick', function() {
            currentOpenInfoWindow = null;
            // Reset marker icon
            marker.setIcon(markerIcon);
        });
       // console.log('Access key created for '+obj['acc_key']);
      	//markers.push(marker);
        markers[obj['acc_key']] = marker;
       
        //map.fitBounds(bounds);
        //var markerCluster = new MarkerClusterer(map, markers);
    }

	
	if ($(window).width() < 550) {
            $(".result_srch_htl").removeClass("owl-carousel");
			$(".map_click").click(function(){
				$('.rowresult.r-r-i').removeClass('marker_highlight');
				$(".result_srch_htl").addClass("owl-carousel");
				 owl = $(".owl-carousel").owlCarousel({
						center: true,
						items:1,
						loop:false,
						addClassActive:true,
						margin:10,
							responsive:{
								550:{
								items:1
								}
							},
					});
				 owl.on('changed.owl.carousel', function(e) {
    				//alert("test");
    				
    				var index_div = $(".owl-item.active",this).index();
    				var index = (index_div+1);
    				var access_key = $('.rowresult.r-r-i')[index].getAttribute('access_key');
    			 	$('.rowresult.r-r-i').removeClass('marker_highlight');
			    
			    
			      $('.rowresult.r-r-i:eq('+index+')').addClass('marker_highlight');
			      	
			      	//highlight current marker another disable
			      	$('.rowresult.r-r-i').not($('.rowresult.r-r-i.marker_highlight')).each(function(e_index,value){
			      		var normal_marker = $('.rowresult.r-r-i')[e_index].getAttribute('access_key');
			      		if(normal_marker!=null){
			      			markers[normal_marker].setIcon(normalIcon());
			      		}
			      	});
			      	
			      	if(access_key!=null){
			      		var arr = access_key.split('_');
			      		var lat = arr[0];
			      		var lon = arr[1];
			  			var pt = new google.maps.LatLng(lat, lon);	
			  			bounds.extend(pt);
			  			map.fitBounds(bounds);      		
			      		map.setCenter(new google.maps.LatLng(lat, lon));
			      		markers[access_key].setIcon(highlightedIcon());
			      	}
  				});	                       
			});	
          
          $(".list_tab").click(function(){
					$(".result_srch_htl").removeClass("owl-carousel");
					$(".result_srch_htl").removeClass("owl-loaded");
					$(".result_srch_htl").removeClass("owl-drag");
					$(".owl-stage-outer").removeClass("owl-stage-outer");
					 
			});
			
			// Handle click on hotel cards in map view sidebar
			$(document).on('click', '.allresult.map_open .result_srch_htl .rowresult', function(e) {
				// Don't trigger if clicking on links or buttons
				if ($(e.target).is('a') || $(e.target).closest('a').length > 0) {
					return;
				}
				
				var $hotelCard = $(this);
				var access_key = $hotelCard.attr('access_key');
				
				// If no access_key, try to get it from lat/lon
				if (!access_key || access_key === '') {
					var lat = parseFloat($hotelCard.find('.hotel_location').data('lat'));
					var lon = parseFloat($hotelCard.find('.hotel_location').data('lon'));
					if (lat && lon && !isNaN(lat) && !isNaN(lon)) {
						access_key = marker_access_key(lat, lon);
					}
				}
				
				// Try different access_key formats
				var foundMarker = null;
				if (access_key && access_key !== '') {
					// Try direct access_key
					if (markers[access_key]) {
						foundMarker = markers[access_key];
					}
					// Try with '1_' prefix
					else if (markers['1_' + access_key]) {
						foundMarker = markers['1_' + access_key];
					}
				}
				
				if (!foundMarker) {
					console.log('Marker not found for access_key:', access_key);
					return;
				}
				
				var marker = foundMarker;
				
				// Remove highlight from all hotel cards
				$('.allresult.map_open .result_srch_htl .rowresult').removeClass('marker_highlight');
				
				// Add highlight to clicked card
				$hotelCard.addClass('marker_highlight');
				
				// Reset all markers to normal icon
				for (var key in markers) {
					if (markers.hasOwnProperty(key) && markers[key] && markers[key].setIcon) {
						markers[key].setIcon(normalIcon());
						// Close any open info windows
						if (markers[key].infoWindow) {
							markers[key].infoWindow.close();
						}
					}
				}
				
				// Highlight the clicked marker
				marker.setIcon(highlightedIcon());
				
				// Show info window
				if (marker.infoWindow) {
					marker.infoWindow.open(map, marker);
					currentOpenInfoWindow = marker.infoWindow;
				}
				
				// Center map on marker with smooth animation
				if (marker.getPosition()) {
					map.setCenter(marker.getPosition());
					map.setZoom(Math.max(map.getZoom(), 15)); // Ensure zoom level is at least 15
				}
				
				// Scroll the clicked card into view in the sidebar
				var $sidebar = $('.allresult.map_open .result_srch_htl');
				if ($sidebar.length > 0) {
					var cardOffset = $hotelCard.position().top;
					var sidebarScroll = $sidebar.scrollTop();
					var scrollPosition = sidebarScroll + cardOffset - 20; // 20px padding from top
					
					$sidebar.animate({
						scrollTop: scrollPosition
					}, 300);
				}
			});
			
		}
});
var markers = {};
function normalIcon() {
  // Create a beautiful modern pin marker with hotel icon style
  // Using a teardrop pin shape - rounded top, pointed bottom
  // The path creates a pin that points to the location
  return {
    path: 'M12 0C5.373 0 0 5.373 0 12c0 8 12 24 12 24s12-16 12-24C24 5.373 18.627 0 12 0zm0 7c2.761 0 5 2.239 5 5s-2.239 5-5 5-5-2.239-5-5 2.239-5 5-5z',
    fillColor: '#3b82f6',
    fillOpacity: 0.95,
    strokeColor: '#ffffff',
    strokeWeight: 3.5,
    scale: 1.4,
    anchor: new google.maps.Point(12, 36),
    rotation: 0
  };
}

function highlightedIcon() {
  // Create a larger, more prominent highlighted marker with enhanced styling
  // Same pin shape but larger and with golden color for emphasis
  return {
    path: 'M12 0C5.373 0 0 5.373 0 12c0 8 12 24 12 24s12-16 12-24C24 5.373 18.627 0 12 0zm0 7c2.761 0 5 2.239 5 5s-2.239 5-5 5-5-2.239-5-5 2.239-5 5-5z',
    fillColor: '#f59e0b',
    fillOpacity: 1,
    strokeColor: '#ffffff',
    strokeWeight: 5,
    scale: 1.9,
    anchor: new google.maps.Point(12, 36),
    rotation: 0
  };
}

function sendhoteldetails_multi(data){
	// alert(data);
	$("#errormsg_multi_"+data).show();
	$("#errormsg_multi_"+data).text('');
	var input_text=$("#email_multi_"+data).val();
	var hoteldetails=$("#hoteldetails_multi_"+data).val();

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
			data:{'email':input_text,'hoteldetails':hoteldetails,'module':'hotel'},
			success:function(msg){
				$(document.body).on('click');
				if(msg == true){
				   $("#errormsg_multi_"+data).css({"color": "green"});
				   $("#errormsg_multi_"+data).text("Email Sent Successfully");
				   $("#email_multi_"+data).val("");
				   setTimeout( function(){$("#errormsg_multi_"+data).hide();$("#sendmail_multi_"+data).modal('hide');} , 3000);
				   
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

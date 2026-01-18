$(document).ready(function() {
	enable_sort();
	//$('.loader-image').show();
	//pre_load_audio();
	var _search_id = document.getElementById('pri_search_id').value;
	var _fltr_r_cnt = 0; //Filter Result count
	var _total_r_cnt = 0;//Total Result count
	var _offset = 0;//Offset to load results
	var dynamic_page_data = false;

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
					load_products(process_result_update, _offset, _ini_fil);
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
		//console.log(_offset+' offset : '+_fltr_r_cnt+ ' Filter Count '+ _total_r_cnt+ ' Total Count');
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
			//command by ela
			//$(window).unbind('scroll', window, processLazyLoad);
			$('#npl_img').remove();
			dynamic_page_data = false;
			//enable sorting via javascript
			enable_sort();
		} else if (_offset < _fltr_r_cnt) {
			_next_page = true;
			//console.log('More Records are available to load');
			$('#npl_img').show();
		}
	}

	window.process_result_update = function(result) {
		var ___data_r = Date.now();
		$('.loader-image').hide();
		hide_result_pre_loader();
		
		// Hide result loader overlay when results are loaded
		if (typeof window.hideResultLoader === 'function') {
			window.hideResultLoader();
		}
		
		//alert(result.hasOwnProperty('status'));
		if(result){
			if (result.hasOwnProperty('status') == true && result.status == true) {
				set_total_summary(result.total_result_count, result.filter_result_count, result.offset);
				update_total_count_summary();
				$('#npl_img').removeAttr('loaded');
			if (_offset == 0) {
				//console.log('Data loaded with offset 0');
				// Hide skeleton loader and show results
				$('#result-skeleton-loader').addClass('hide');
				$('#tour_search_result').removeClass('hide');
				$('#tour_search_result').html(result.data);
				//No Result Found
			} else {
				//console.log('Pagination data loaded with offset');
				//$('#tour_search_result').append(result.data);
				// Hide skeleton loader and show results
				$('#result-skeleton-loader').addClass('hide');
				$('#tour_search_result').removeClass('hide');
				$('#tour_search_result').html(result.data);
			}
				if($(".grid_click").hasClass('active')){
                   $('#tour_search_result .item').removeClass('list-group-item');
                    $('#tour_search_result .item').addClass('grid-group-item');
                    $('.rowresult').addClass("col-4");
                    
                }else if($(".list_click").hasClass('active')){
                	$('#tour_search_result .item').addClass('list-group-item');
                    $('#tour_search_result .item').removeClass('grid-group-item');
                    $('.rowresult').removeClass("col-4");
                }

				ini_pagination();
				//Set time out to lazy load images
				lazy_img();
			}else{
				//alert("falsessee");
			}
		}
		
	}
	
	// Helper function to hide filter skeleton loader
	var filterSkeletonHidden = false;
	function hideFilterSkeletonLoader() {
		if (filterSkeletonHidden) return; // Prevent multiple calls
		
		var $skeleton = $('#filter-skeleton-loader');
		var $content = $('#filter-content');
		
		if ($skeleton.length) {
			$skeleton.addClass('hide').css({
				'display': 'none',
				'visibility': 'hidden',
				'opacity': '0'
			});
			filterSkeletonHidden = true;
		}
		if ($content.length) {
			$content.removeClass('hide').css({
				'display': 'block',
				'visibility': 'visible',
				'opacity': '1'
			});
		}
	}

	// Show result loader overlay (for filtering and sorting)
	window.showResultLoader = function() {
		var $container = $('#tour_search_result').parent();
		if (!$container.length) {
			$container = $('.allresult');
		}
		if (!$container.length) {
			console.warn('Sightseeing search result container not found');
			return;
		}
		
		var $loader = $('#result-loader-overlay');
		
		// Create loader if it doesn't exist
		if (!$loader.length) {
			$loader = $('<div id="result-loader-overlay" class="result-loader-overlay hide">' +
				'<div class="result-loader-content">' +
				'<div class="result-loader-graphic">' +
				'<div class="sightseeing-icon">' +
				'<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
				'<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>' +
				'</svg>' +
				'</div>' +
				'<div class="loader-rings">' +
				'<div class="ring ring-1"></div>' +
				'<div class="ring ring-2"></div>' +
				'<div class="ring ring-3"></div>' +
				'</div>' +
				'</div>' +
				'<p class="result-loader-text">Loading activities...</p>' +
				'<div class="loader-progress-bar">' +
				'<div class="loader-progress-fill"></div>' +
				'</div>' +
				'</div>' +
				'</div>');
			$container.prepend($loader);
		}
		
		// Force show immediately
		$loader.removeClass('hide');
		$loader.css({
			'display': 'flex',
			'opacity': '1',
			'visibility': 'visible',
			'z-index': '1000'
		});
		// Force a reflow to ensure the display change takes effect
		if ($loader[0]) {
			$loader[0].offsetHeight;
		}
	}

	// Hide result loader overlay
	window.hideResultLoader = function() {
		var $loader = $('#result-loader-overlay');
		if ($loader.length) {
			$loader.css({
				'opacity': '0',
				'visibility': 'hidden'
			});
			setTimeout(function() {
				$loader.addClass('hide').css('display', 'none');
			}, 300);
		}
		// Don't remove the element, just hide it so it can be reused
	}

	window.ini_result_update = function(result) {
		//post_load_audio();

		if (result.hasOwnProperty('status') == true && result.status == true) {
				if(result.total_result_count >=1){
					update_range_slider(parseInt(result.filters.p.min), parseInt(result.filters.p.max));
				}
				enable_discount_selector();
				//console.log(result.filters.categories);
				enable_categories_selector(result.filters.categories);
				enable_duration_selector(result.filters.duration);
				enable_recommended_selector(result.filters.recommended);

				//enable_star_wrapper(result.filters.star);
				inif();
				
				// Hide filter skeleton loader after all filters are loaded
				setTimeout(function() {
					hideFilterSkeletonLoader();
				}, 300);
			
			//command by ela
			//$(window).on('scroll', processLazyLoad);
		}
		
		check_empty_search_result();
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
		//console.log("MAthi");
		e.preventDefault();
		var _cur_row_index = $(this).closest('.r-r-i');
		console.log("_cur_row_index"+_cur_row_index);
		var _hotel_room_list = $(".room-list", _cur_row_index);
		var _result_index = $('[name="ResultIndex"]:first', _cur_row_index).val();
		if (_hotel_room_list.is(':visible') == false) {
			//console.log("Show Mathi...");
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
				//console.log("show else mathi");
				$(".room-summ", _cur_row_index).show();
			}
		} else {
			//console.log("Else..Mathi..");
			_hotel_room_list.hide();
		}
	});

	//Load hotels from active source
	show_result_pre_loader();
	$(document).on('click', '.location-map', function() {
		$('#map-box-modal').modal();
	});


	/**
	 * Toggle active class to highlight current applied sorting
	 **/
	$(document).on('click', '.sorta', function(e) {
		e.preventDefault();
		console.log("Elavarasi");
		$(this).closest('.sortul').find('.active').removeClass('active');
		//Add to sibling
		$(this).siblings().addClass('active');
	});

	//************************** **********/

	$('.toglefil').click(function() {
		$(this).toggleClass('active');
	});


	/*  Mobile Filter  */
	$('.filter_tab').click(function() {
		$('.resultalls').stop(true, true).toggleClass('open');
		$('.coleft').stop(true, true).slideToggle(500);
	});

	var widowwidth = $(window).width();
	if (widowwidth < 991) {
		$('.resultalls.open #tour_search_result').on('click', function() {
			$('.resultalls').removeClass('open');
			$('.coleft').slideUp(500);
		});
	}


	//var application_preference_currency = document.getElementById('pri_app_pref_currency').value;
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
		/*$(".price-l-2-h").click(function() {
			$(this).addClass('hide');
			$('.price-h-2-l').removeClass('hide');
			$("#tour_search_result").jSort({
				sort_by: '.h-p',
				item: '.r-r-i',
				order: 'asc',
				is_num: true
			});
		});

		$(".price-h-2-l").click(function() {
			$(this).addClass('hide');
			$('.price-l-2-h').removeClass('hide');
			$("#tour_search_result").jSort({
				sort_by: '.h-p',
				item: '.r-r-i',
				order: 'desc',
				is_num: true
			});
		});*/
		$(".price-l-2-h").click(function(){
				$('.loader-image').show();
			//if(dynamic_page_data == true) {
				unset_sorters_array();
				_ini_fil['sort_item'] = 'price';
				_ini_fil['sort_type'] = 'asc';
				dynamic_sorter_rom();
				if($('.price-l-2-h').hasClass('hide') == false) {
					$('.price-l-2-h').addClass('hide');
				}
				if($('.price-h-2-l').hasClass('hide') == true) {
					$('.price-h-2-l').removeClass('hide');
				}
			//}
		});
		$(".price-h-2-l").click(function(){
				$('.loader-image').show();
			//if(dynamic_page_data == true) {
				unset_sorters_array();
				_ini_fil['sort_item'] = 'price';
				_ini_fil['sort_type'] = 'desc';
				dynamic_sorter_rom();
				if($('.price-h-2-l').hasClass('hide') == false) {
					$('.price-h-2-l').addClass('hide');
				}
				if($('.price-l-2-h').hasClass('hide') == true) {
					$('.price-l-2-h').removeClass('hide');
				}
			//}
		});

		$(".name-l-2-h").click(function() {
				$('.loader-image').show();
			unset_sorters_array();
			_ini_fil['sort_item'] = 'name';
			_ini_fil['sort_type'] = 'asc';
			dynamic_sorter_rom();
			if($('.name-l-2-h').hasClass('hide') == false) {
				$('.name-l-2-h').addClass('hide');
			}
			if($('.name-h-2-l').hasClass('hide') == true) {
				$('.name-h-2-l').removeClass('hide');
			}
		});

		$(".name-h-2-l").click(function() {
				$('.loader-image').show();
			unset_sorters_array();
			_ini_fil['sort_item'] = 'name';
			_ini_fil['sort_type'] = 'desc';
			dynamic_sorter_rom();
			if($('.name-h-2-l').hasClass('hide') == false) {
				$('.name-h-2-l').addClass('hide');
			}
			if($('.name-l-2-h').hasClass('hide') == true) {
				$('.name-l-2-h').removeClass('hide');
			}
		});
		$(".star-l-2-h").click(function(){
				$('.loader-image').show();
			unset_sorters_array();
			_ini_fil['sort_item'] = 'star';
			_ini_fil['sort_type'] = 'asc';
			dynamic_sorter_rom();
			if($('.star-l-2-h').hasClass('hide') == false) {
				$('.star-l-2-h').addClass('hide');
			}
			if($('.star-h-2-l').hasClass('hide') == true) {
				$('.star-h-2-l').removeClass('hide');
			}
		});
		$(".star-h-2-l").click(function(){
			$('.loader-image').show();
			unset_sorters_array();
			_ini_fil['sort_item'] = 'star';
			_ini_fil['sort_type'] = 'desc';
			dynamic_sorter_rom();
			if($('.star-h-2-l').hasClass('hide') == false) {
				$('.star-h-2-l').addClass('hide');
			}
			if($('.star-l-2-h').hasClass('hide') == true) {
				$('.star-l-2-h').removeClass('hide');
			}
		});
	}
	/** -------------------------SORT LIST DATA---------------------- **/

	/**
	 * Toggle active class to highlight current applied sorting
	 **/
	$(document).on('click', '.sorta', function(e) {
		e.preventDefault();
		//console.log("mathi");
		loader();
		$(this).closest('.sortul').find('.active').removeClass('active');
		//Add to sibling
		$(this).siblings().addClass('active');
	});

	$('.loader').on('click', function(e) {
		e.preventDefault();
		loader();
	});

	$('#tour-search-btn').on('click', function(e) {
		//alert('rout');
		loader();
		e.preventDefault();
		ini_activity_namef();
		filter_tour();
	});

	$(document).on('change', 'input.discount-filter', function(e) {
		//console.log("calllleerere");
		//alert("calee");
		//$('.loader-image').show();
		loader();
		ini_discount_filt();
		filter_tour();
		//console.log("caleed");

	});
	$(document).on('change', 'input.categorycheckbox', function(e) {
		loader();
		ini_activity_categoryf();
		filter_tour();
	});
	$(document).on('change', 'input.durationcheckbox', function(e) {
		loader();
		ini_activity_durationf();
		filter_tour();
	});
	$(document).on('change', 'input.recommendedcheckbox', function(e) {
		loader();
		ini_activity_recommenadtionf();
		filter_tour();
	});
	
	$('.deal-status-filter').on('change', function(e) {
		loader();
		ini_dealf();
		filter_tour();
	});

	$(document).on('change', '.star-filter', function(e) {
		loader();
		var thisEle = this;
		var _filter = '';
		var attr = {};
		attr['checked'] = $(thisEle).is(':checked');
		ini_starf();
		filter_tour('star-filter', _filter, attr);
	});



	//Balu A - Setting minimum and maximum price for slider range
	function update_range_slider(minPrice, maxPrice) {
		$('#core_minimum_range_value', '#core_min_max_slider_values').val(minPrice);
		$('#core_maximum_range_value', '#core_min_max_slider_values').val(maxPrice);
		//price-refine
		enable_price_range_slider(minPrice, maxPrice);
	}
	//Reset the filters -- Elavarasi
	$(document).on('click', '#reset_filters', function() {
		//comment by ela
		//$('.loader-image').show();
		loader();	
		//Reset the Star,and Location Filters
		// $('#starCountWrapper .enabled').each(function() {
		// 	$(this).removeClass('active');
		// 	$('.star-filter', this).prop('checked', false);
		// });
		$('#tour-name').val('');
		//$('#tour_search_result').empty();
		$('input.activity-cate').prop('checked', false);
		$('input.discount-filter').prop('checked',false);
		set_slider_label(min_amt, max_amt);
		var minPrice = $('#core_minimum_range_value', '#core_min_max_slider_values').val();
		var maxPrice = $('#core_maximum_range_value', '#core_min_max_slider_values').val();
		$("#price-range").slider("option", "values", [minPrice, maxPrice]);
		//$('.discount-filter:checked').val('all');
		inif();
		
		filter_tour();

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
	
	_ini_fil['dis'] = false;
	function ini_discount_filt()
	{
		if($('.discount-filter:checked').val()=='filter'){
		//	alert('checked');
			_ini_fil['dis'] = true;
		} else {
			//alert('not_checked');
			_ini_fil['dis'] = false;
		}

		// _ini_fil['dis'] = $('.discount-filter:checked', '#discount-filter-wrapper').map(function() {
		// 	return this.value;
		// }).get();

		//alert(_ini_fil);
		//console.log(_ini_fil['hl'])
		//alert(_ini_fil['hl']);
	}
	
	_ini_fil['min_price'] = 0;
	_ini_fil['max_price'] = 0;
	function ini_pricef()
	{
		_ini_fil['min_price'] = parseFloat($("#price-range").slider("values")[0]);
		_ini_fil['max_price'] = parseFloat($("#price-range").slider("values")[1]);
	}
	
	_ini_fil['an_val'] = '';
	function ini_activity_namef()
	{
		//_ini_fil['hn_val'] = $('#hotel-name').val().trim().toLowerCase();
		_ini_fil['an_val'] = $('#tour-name').val();
		
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
	_ini_fil['cat'] = [];
	function ini_activity_categoryf()
	{
		_ini_fil['cat'] = $('.categorycheckbox:checked', '#activity-categories-wrapper').map(function() {
			return this.value;
		}).get();
		console.log(_ini_fil['cat']);
	}
	_ini_fil['dur'] = [];
	function ini_activity_durationf()
	{
		_ini_fil['dur'] = $('.durationcheckbox:checked', '#activity-duration-wrapper').map(function() {
			return this.value;
		}).get();
		//alert(_ini_fil['hl']);
	}
	_ini_fil['recom'] = [];
	function ini_activity_recommenadtionf()
	{
		_ini_fil['recom'] = $('.recommendedcheckbox:checked', '#activity-recommended-wrapper').map(function() {
			return this.value;
		}).get();
		//alert(_ini_fil['hl']);
	}
	_ini_fil['cate'] = [];
	function ini_activity_cate()
	{
		_ini_fil['cate'] = $('.activity-cate:checked', '#activity-cate-wrapper').map(function() {
			return this.value;
		}).get();
		//alert(_ini_fil['cate']);
	}

	$(document).on('change', 'input.activity-cate', function(e) {
		loader();
		ini_activity_cate();
		filter_tour();
	});

	function inif()
	{
		//ini_starf();
		ini_discount_filt();
		ini_pricef();
		ini_activity_namef();
		ini_activity_categoryf();
		//ini_dealf();
		ini_activity_cate();

	}
	//------ INI F

	/**
	 * _filter_trigger	==> element which caused fliter to be triggered
	 * _filter			==> default filter settings received from filter trigger
	 */
	function filter_tour(_filter_trigger, _filter, attr) {
		//console.log("called");
		inif();
		//console.log(_offset+' offset : '+_fltr_r_cnt+ ' Filter Count '+ _total_r_cnt+ ' Total Count');
		//if (_fltr_r_cnt == _total_r_cnt && _offset >= _total_r_cnt) {
		if (dynamic_page_data == false) {
			//alert("filterroom");
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
			//console.log(_ini_fil['cate']);
			//alert(_ini_fil['cate']);
			//console.log(_ini_fil);
			//$('.r-r-i' + _filter) : FIXME
			$('.r-r-i').each(function(key, value) {
				var _rmp = parseInt($('.h-p', this).text());
				var _discount = parseInt($('.special-offer',this).text());	
				var _ran = $('.h-name', this).text().trim().toLowerCase();	
				var _category = $('.category_list', this).val();
				var _duration = $('.duration_list', this).val();
				var _recommendation = $('.recommendation_list', this).val();
				var _acate = $('.activity-cate',this).text().trim();
				_acate = _acate.split(',');
				//alert(_acate);
				//console.log(_category);
				if (					
					(_rmp >= _ini_fil['min_price'] && _rmp <= _ini_fil['max_price']) &&
					(_ini_fil['dis'] == false || (_ini_fil['dis'] == true && _discount == true)) && 
					((_ran == "" || _ini_fil['an_val'] == "") || (_ran.search(_ini_fil['an_val'].trim().toLowerCase()) > -1)) &&
					((_ini_fil['cate'].length == 0) || (has_all_cate(_acate))==true) 
					 && ((_ini_fil['cat'].length == 0) || (has_all_category(_category) == true))
					 && ((_ini_fil['dur'].length == 0) || (has_all_duration(_duration) == true))
					 && ((_ini_fil['recom'].length == 0) || (has_all_recommendation(_recommendation) == true))
					) {
					++_fltr_r_cnt;
					//console.log("removeClass");
					$(this).removeClass('hide');
				} else {
					//console.log("addClass");
					$(this).addClass('hide');
				}
				//console.log("filterscalleedssss");
				//set_marker_visibility(this, lat, lon, _rhn, star,price)
			});
		} else {
			//alert("backend filter");
			//filter from backend
			dynamic_filter_rom();
		}
		update_total_count_summary();
	}
	/*function has_all_category(_category) {
		var has = false;
		$.each(_ini_fil['cat'], function(k, v) {
			console.log(_ini_fil['cat']);
			var str_lnth = parseInt(_category.search(v));
			if (str_lnth > 0) {
				has = true;
				
			}else{
				has = false;
				
			}
		});
		return has;
	}*/

	function has_all_category(_category) {

	    // No category filter → show all
	    if (!_ini_fil['cat'] || _ini_fil['cat'].length === 0) {
	        return true;
	    }

	    if (!_category) {
	        return false;
	    }

	    // Example: "_3" or "_3_9"
	    // Convert to ["3"] or ["3","9"]
	    var rowCats = _category
	        .split('_')
	        .filter(Boolean)
	        .map(String);

	    // OR condition: match ANY selected category
	    return _ini_fil['cat'].some(function (selectedCat) {
	        return rowCats.includes(String(selectedCat));
	    });
	}


	function has_all_duration(_duration) {

	    // No duration filter → show all
	    if (!_ini_fil['dur'] || _ini_fil['dur'].length === 0) {
	        return true;
	    }

	    if (!_duration) {
	        return false;
	    }

	    // "_966_967" → ["966","967"]
	    var rowDurations = _duration
	        .split('_')
	        .filter(Boolean)
	        .map(String);

	    // OR logic: any selected duration matches
	    return _ini_fil['dur'].some(function (selectedDur) {
	        return rowDurations.includes(String(selectedDur));
	    });
	}

	function has_all_recommendation(_recommendation) {

	    // No recommendation filter → show all
	    if (!_ini_fil['recom'] || _ini_fil['recom'].length === 0) {
	        return true;
	    }

	    if (!_recommendation) {
	        return false;
	    }

	    // "_1_2" → ["1","2"]
	    var rowRecom = _recommendation
	        .split('_')
	        .filter(Boolean)
	        .map(String);

	    // OR logic: any selected recommendation matches
	    return _ini_fil['recom'].some(function (selectedRec) {
	        return rowRecom.includes(String(selectedRec));
	    });
	}




	/*function has_all_duration(_duration) {
		var has = false;
		$.each(_ini_fil['dur'], function(k, v) {
			var str_lnth = parseInt(_duration.search(v));
			if (str_lnth > 0) {
				has = true;
				
			}else{
				has = false;
				
			}
		});
		return has;
	}
	function has_all_recommendation(_recommendation) {
		var has = false;
		$.each(_ini_fil['recom'], function(k, v) {
			var str_lnth = parseInt(_recommendation.search(v));
			if (str_lnth > 0) {
				has = true;
				
			}else{
				has = false;
				
			}
		});
		return has;
	}*/
	
	 function has_all_cate(cate) {
        
        //alert("cate"+cate);
        var has = false;
       	var found_array =new Array();
       	for (var i = 0; i <_ini_fil['cate'] .length; i++) {
       		
       		var cat_found = parseInt($.inArray(_ini_fil['cate'][i],cate));
       		if(cat_found >-1){
       			found_array.push(1);
       		}else{
       			found_array.push(0);
       		}
       	}
       	//console.log(found_array);
       	if($.inArray(1,found_array) >-1){
       		has = true;
       	}else{
       		has =false;
       		//console.log("notfound");
       	}
       	//console.log(found_array);
        /*$.each(_ini_fil['cate'], function(k, v) {
            //console.log("v"+v);
           // var str_lnth = parseInt(cate.search(v));
          	var cat_found = parseInt($.inArray(v,cate));
          	console.log("cat_found"+cat_found);
            if (cat_found > -1) {
                //_fclty.search(set_null);
              
                has = true;
                //return false
            }else{
                
                has = false;
                return false;
            }
        });*/
        return has;
    }
	function dynamic_filter_rom()
	{
		//-- empty results and show loader
		show_result_pre_loader();
		// Show result loader overlay when filtering
		if (typeof window.showResultLoader === 'function') {
			window.showResultLoader();
		}
		// Show skeleton loader when filtering
		$('#result-skeleton-loader').removeClass('hide');
		$('#tour_search_result').addClass('hide');
		$('#tour_search_result').empty();
		_offset = 0;
		load_products(process_result_update, _offset, _ini_fil);
	}
	//Balu A
	function dynamic_sorter_rom()
	{
		//-- empty results and show loader
		show_result_pre_loader();
		// Show result loader overlay when sorting
		if (typeof window.showResultLoader === 'function') {
			window.showResultLoader();
		}
		// Show skeleton loader when sorting
		$('#result-skeleton-loader').removeClass('hide');
		$('#tour_search_result').addClass('hide');
		$('#tour_search_result').empty();
		_offset = 0;
		load_products(process_result_update, _offset, _ini_fil);
	}
	/**
	 *Update Hotel Count Details
	 */
	function update_total_count_summary() {
		$('#tour_search_result').show();
		if (isNaN(_fltr_r_cnt) == true || _fltr_r_cnt < 1) {
			_fltr_r_cnt = 0;
			//display warning
			$('#tour_search_result').hide();
			$('#empty_tour_search_result').show();
		} else {
			$('#tour_search_result').show();
			$('#empty_tour_search_result').hide();
		}
		$('#total_records').text(_total_r_cnt);
		$('.total-row-record-count').text(_total_r_cnt);
		$('#filter_records').text(_fltr_r_cnt);
	}
	var sliderCurrency = application_preference_currency;
	//application_preference_currency
	var min_amt = 0;
	var max_amt = 0;

	function enable_price_range_slider(minPrice, maxPrice) {
		min_amt = minPrice;
		max_amt = maxPrice;
		$("#price-range").empty();
		/**** PRICE SLIDER START ****/
		$("#price-range").slider({
			range: true,
			min: minPrice,
			max: maxPrice,
			values: [minPrice, maxPrice],
			slide: function(event, ui) {
				set_slider_label(ui.values[0], ui.values[1]);
			},
			change: function(e) {
				if ('originalEvent' in e) {
					loader();
					ini_pricef();
					filter_tour();
				}
			}
		});
		set_slider_label(minPrice, maxPrice);
		/**** PRICE SLIDER END ****/
	}

	function set_slider_label(val1, val2) {
		$("#hotel-price").text(sliderCurrency +' '+ val1 + " - " + sliderCurrency+' '+ val2);
	}

	function enable_discount_selector() {
		//alert(locs);
			
		
		var _location_option_list = '';
	
		_location_option_list += '<li>';
			_location_option_list += '<div class="squaredThree">';
			_location_option_list += '<input id="locSquaredThreedisc' + 0+ '" class="discount-filter" type="checkbox" name="check" value="filter">';
			_location_option_list += '<label for="locSquaredThreedisc' + 0 + '"></label>';
			_location_option_list += '</div>';
			_location_option_list += '<label class="lbllbl" for="locSquaredThreedisc' + 0 + '">Discount & Special Offer</label>';
			_location_option_list += '</li>';
			//i++;
		$('#discount-filter-wrapper').html(_location_option_list);
	}
	function enable_categories_selector(category_list) {

	    // Convert object to array if needed
	    var categoryArray = [];

	    $.each(category_list, function (key, value) {
	        categoryArray.push(value);
	    });

	    // Sort ascending by v (1,2,3...)
	    categoryArray.sort(function (a, b) {
	        return parseInt(a.v) - parseInt(b.v);
	    });

	    var filterList = '';
	    var index = 1;

	    $.each(categoryArray, function (key, value) {
	        filterList += '<li>';
	        filterList += '<div class="squaredThree">';
	        filterList += '<input type="checkbox" value="' + value.code + '" class="categorycheckbox" id="squaredCategory' + index + '">';
	        filterList += '<label for="squaredCategory' + index + '"></label>';
	        filterList += '</div>';
	        filterList += '<label for="squaredCategory' + index + '" class="lbllbl">';
	        filterList += value.v + ' (' + value.c + ')';
	        filterList += '</label>';
	        filterList += '</li>';
	        index++;
	    });

	    if (filterList) {
	        $('#activity-categories-wrapper').html('<ul class="locationul">' + filterList + '</ul>');
	    } else {
	        $('#categories_div').addClass('hide');
	    }
	    
	    // Hide filter skeleton loader after categories are loaded
	    hideFilterSkeletonLoader();
	}


	function enable_duration_selector(duration){
		var filterList = '';
	    var index = 1;
	    $.each(duration, function(key, value) {
	                    filterList += '<li>';
	                    filterList += '<div class="squaredThree">';
	                    filterList += '<input type="checkbox" value="' + value['code'] + '" name="check" class="durationcheckbox" id="squaredDuration'+index+'">';
	                    filterList += '<label for="squaredDuration'+index+'"></label>';
	                    filterList += '</div>';
	                    filterList += '<label for="squaredDuration'+index+'" class="lbllbl">' + value['v'] + ' ('+value['c']+')</label>';
	                    filterList += '</li>';
	                    index++;
	                });
	        if(filterList){
	            $('#activity-duration-wrapper').html('<ul class="locationul">' + filterList + '</ul>');
	        }
	        else{
	         	$('#duration_div').addClass('hide');
       		}
       		
       		// Hide filter skeleton loader after duration is loaded
       		hideFilterSkeletonLoader();
	}
	function enable_recommended_selector(recommended){
		var filterList = '';
	    var index = 1;
	    $.each(recommended, function(key, value) {
	                    filterList += '<li>';
	                    filterList += '<div class="squaredThree">';
	                    filterList += '<input type="checkbox" value="' + value['code'] + '" name="check" class="recommendedcheckbox" id="squaredRecom'+index+'">';
	                    filterList += '<label for="squaredRecom'+index+'"></label>';
	                    filterList += '</div>';
	                    filterList += '<label for="squaredRecom'+index+'" class="lbllbl">' + value['v'] + ' ('+value['c']+')</label>';
	                    filterList += '</li>';
	                    index++;
	                });
	        if(filterList){
	            $('#activity-recommended-wrapper').html('<ul class="locationul">' + filterList + '</ul>');
	        }
	        else{
	         	$('#recommended_div').addClass('hide');
       		}
       		
       		// Hide filter skeleton loader after recommended is loaded
       		hideFilterSkeletonLoader();
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
	/************MAP**********/

    //MAP
    /*Map view click function*/
    $('.map_click').click(function() { 
        reset_ini_map_view();//set up master map markers
        //window.location.reload();
        //reset_ini_map_view();//set up master map markers
        $('.allresult').addClass('map_open');
        $('.view_type').removeClass('active');
        $(this).addClass('active');
        $(".result_srch_htl").niceScroll({
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
		
		
    //reset_ini_map_view();
    });
	
	
	
    function map_point_load(){
        reset_ini_map_view();//set up master map markers
        //window.location.reload();
        //reset_ini_map_view();//set up master map markers
        $('.allresult').addClass('map_open');
        $('.view_type').removeClass('active');
        $(this).addClass('active');
        $(".result_srch_htl").niceScroll({
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

	
	
/*    $('.list_click').click(function() { 
        $('.allresult').removeClass('map_open');
        $('.view_type').removeClass('active');
        $(this).addClass('active');
    });*/
        $('.list_click').click(function() {
        // $('.allresult').removeClass('map_open');
        // $('.view_type').removeClass('active');
        // $(this).addClass('active');
        //$(".resultalls").removeClass("fulview");
        $('#tour_search_result .item').removeClass('grid-group-item');
        $('.rowresult').removeClass("col-4");
        $(this).addClass("active");
        $('.grid_click').removeClass('active');
    });
    $('.grid_click').click(function() {
		$('#tour_search_result .item').removeClass('list-group-item');
    	$('#tour_search_result .item').addClass('grid-group-item');
    	$('.rowresult').addClass("col-4");
    	$('#tour_search_result .item').show();
    	$('.view_type').removeClass("active");
    	$('.grid_click').addClass("active");
	});


	window.sendSightseeingdetails_multi = function(data){
	 //alert(data);

	$("#errormsg_multi_"+data).show();
	$("#errormsg_multi_"+data).text('');
	var input_text=$("#email_multi_"+data).val();
	var sightseeingdetails=$("#sightseeingdetails_multi_"+data).val();

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
		document.getElementById("close_modal_multi"+data).disabled = true;
		// document.getElementsByClassName("close").disabled = true;
		$.ajax({
			url:app_base_url+'index.php/ajax/send_multi_details_mail/',
			type:'POST',
			data:{'email':input_text,'sightseeingdetails':sightseeingdetails,'module':'sightseeing'},
			success:function(msg){
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

    // DEFINE YOUR MAP AND MARKER 
    //google.maps.event.addDomListener(window, 'load', initialize);

    //google.maps.event.addDomListener(window, "resize", resizingMap());
});
 
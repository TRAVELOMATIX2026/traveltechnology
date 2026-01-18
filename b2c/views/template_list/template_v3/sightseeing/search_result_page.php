<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('page_resource/sightseeing-result.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_search.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => JAVASCRIPT_LIBRARY_DIR.'jquery.jsort.0.4.min.js', 'defer' => 'defer');

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('jquery.nicescroll.js'), 'defer' => 'defer');
echo $this->template->isolated_view('share/js/lazy_loader');
foreach ($active_booking_source as $t_k => $t_v) {
	$active_source[] = $t_v['source_id'];
}
$active_source = json_encode($active_source);
?>
<script>
var load_products = function(loader, offset, filters){
	//console.log(filters);
	offset = offset || 0;
	var url_filters = '';
	if ($.isEmptyObject(filters) == false) {
		url_filters = '&'+($.param({'filters':filters}));
	}
	_lazy_content = $.ajax({
		type: 'GET',
		url: app_base_url+'index.php/ajax/sightseeing_list/'+offset+'?booking_source=<?=$active_booking_source[0]['source_id']?>&search_id=<?=$sight_seen_search_params['search_id']?>&op=load'+url_filters,
		async: true,
		cache: true,
		dataType: 'json',
		success: function(res) {
			loader(res);
		}
	});
}

var interval_load = function (res) {
						var dui;
						var r = res;
						dui = setInterval(function(){
									if (typeof(process_result_update) != "undefined" && $.isFunction(process_result_update) == true) {
										clearInterval(dui);
										process_result_update(r);
										ini_result_update(r);
										
										// Fallback: Hide filter skeleton loader after a delay if still visible
										setTimeout(function() {
											var $skeleton = $('#filter-skeleton-loader');
											var $content = $('#filter-content');
											
											if ($skeleton.length && !$skeleton.hasClass('hide')) {
												$skeleton.addClass('hide').hide();
											}
											if ($content.length && $content.hasClass('hide')) {
												$content.removeClass('hide').show();
											}
										}, 600);
									}
							}, 1);
					};
load_products(interval_load);
</script>


<span class="hide">
	<input type="hidden" id="pri_search_id" value='<?=$sight_seen_search_params['search_id']?>'>
	<input type="hidden" id="pri_active_source" value='<?=$active_source?>'>
	<input type="hidden" id="pri_app_pref_currency" value='<?=$this->currency->get_currency_symbol(get_application_currency_preference())?>'>
	
</span>
<?php
	$data['result'] = $sight_seen_search_params;
	$mini_loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>';
	$loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v1.gif').'" alt="Loading........"/></div>';
	$template_images = $GLOBALS['CI']->template->template_images();
	function get_sorter_set()
	{
		return '<div class="filterforallnty" id="top-sort-list-wrapper">
	                        <div class="topmistyhtl" id="top-sort-list-1">
	                            <div class="col-12 nopad">
	                                <div class="insidemyt">
										<ul class="sortul">
											
											<li class="sortli threonly"><a class="sorta name-l-2-h loader asc"><i class="fa fa-sort-alpha-asc"></i> <strong>Name</strong></a><a
												class="sorta name-h-2-l hide loader des"><i class="fa fa-sort-alpha-desc"></i> <strong>Name</strong></a></li>
											
											<li class="sortli threonly"><a class="sorta star-l-2-h loader asc"><i class="fa fa-star"></i> <strong>Rating</strong></a><a
												class="sorta star-h-2-l hide loader des"><i class="fa fa-star"></i> <strong>Rating</strong></a></li>
																					
											<li class="sortli threonly"><a class="sorta price-l-2-h loader asc"><i class="fa fa-tag"></i> <strong>Price</strong></a><a
												class="sorta price-h-2-l hide loader des"><i class="fa fa-tag"></i> <strong>Price</strong></a></li>
										</ul>
									</div>
	                            </div>
	                        </div>
	                    </div>';
	}
	echo $GLOBALS['CI']->template->isolated_view('sightseeing/search_panel_summary');
	?>

<section class="search-result tour_search_results sghtseen">
	<div class="container-fluid"  id="page-parent">
		<?php //echo $GLOBALS['CI']->template->isolated_view('share/loader/sight_seen_pre_loader',$data);?>
		<div class="container p-0">
		<div class="resultalls open">
			<div class="coleft">
				<div class="flteboxwrp">
					<!-- Filter Skeleton Loader -->
					<div id="filter-skeleton-loader" class="fltrboxin">
						<div class="celsrch">
							<div class="row_top_fltr d-flex justify-content-between align-items-center">
								<div class="hdr_flx">
									<div class="skeleton-loader" style="height: 20px; width: 80px; margin-bottom: var(--spacing-2);"></div>
								</div>
								<div class="filtersho">
									<div class="skeleton-loader" style="height: 20px; width: 150px;"></div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="septor"></div>
							
							<!-- Price Filter Skeleton -->
							<div class="rangebox">
								<div class="skeleton-loader" style="height: 40px; width: 100%; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-md);"></div>
								<div class="in">
									<div class="price_slider1" style="padding-top: var(--spacing-3);">
										<p class="level skeleton-loader" style="height: 28px; width: 100%; margin-bottom: var(--spacing-3);"></p>
										<div class="skeleton-loader" style="height: 6px; width: 100%; border-radius: 8px;"></div>
									</div>
								</div>
							</div>
							<div class="septor"></div>
							
							<!-- Search Activities Skeleton -->
							<div class="rangebox">
								<div class="skeleton-loader" style="height: 40px; width: 100%; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-md);"></div>
								<div class="in">
									<div class="boxins" style="padding-top: var(--spacing-3);">
										<div class="skeleton-loader" style="height: 40px; width: 100%; border-radius: var(--border-radius-md);"></div>
									</div>
								</div>
							</div>
							<div class="septor"></div>
							
							<!-- Categories Filter Skeleton -->
							<div class="rangebox">
								<div class="skeleton-loader" style="height: 40px; width: 100%; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-md);"></div>
								<div class="in">
									<div class="boxins" style="padding-top: var(--spacing-3);">
										<ul class="locationul" style="margin: 0; padding: 0; list-style: none;">
											<?php for ($j = 1; $j <= 5; $j++): ?>
											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0; border-radius: 4px;"></div>
												<label class="lbllbl skeleton-loader" style="height: 16px; width: <?php echo 100 + ($j * 10); ?>px; flex: 1;"></label>
											</li>
											<?php endfor; ?>
										</ul>
									</div>
								</div>
							</div>
							<div class="septor"></div>
							
							<!-- Duration Filter Skeleton -->
							<div class="rangebox">
								<div class="skeleton-loader" style="height: 40px; width: 100%; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-md);"></div>
								<div class="in">
									<div class="boxins" style="padding-top: var(--spacing-3);">
										<ul class="locationul" style="margin: 0; padding: 0; list-style: none;">
											<?php for ($j = 1; $j <= 4; $j++): ?>
											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0; border-radius: 4px;"></div>
												<label class="lbllbl skeleton-loader" style="height: 16px; width: <?php echo 80 + ($j * 15); ?>px; flex: 1;"></label>
											</li>
											<?php endfor; ?>
										</ul>
									</div>
								</div>
							</div>
							<div class="septor"></div>
							
							<!-- Recommended For Filter Skeleton -->
							<div class="rangebox">
								<div class="skeleton-loader" style="height: 40px; width: 100%; margin-bottom: var(--spacing-2); border-radius: var(--border-radius-md);"></div>
								<div class="in">
									<div class="boxins" style="padding-top: var(--spacing-3);">
										<ul class="locationul" style="margin: 0; padding: 0; list-style: none;">
											<?php for ($j = 1; $j <= 4; $j++): ?>
											<li style="margin-bottom: var(--spacing-2); display: flex; align-items: center; gap: var(--spacing-2);">
												<div class="squaredThree skeleton-loader" style="height: 16px; width: 16px; flex-shrink: 0; border-radius: 4px;"></div>
												<label class="lbllbl skeleton-loader" style="height: 16px; width: <?php echo 90 + ($j * 10); ?>px; flex: 1;"></label>
											</li>
											<?php endfor; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Actual Filter Content -->
					<div class="fltrboxin hide" id="filter-content">
						<form autocomplete="off">
							<div class="celsrch refine">
								<div class="row_top_fltr d-flex justify-content-between align-items-center">
									<div class="hdr_flx">
										<a class="float-end reset_filter" id="reset_filters" style="font-size: 12px;">RESET ALL</a>
									</div>
									<div class="filtersho">
										<div class="avlhtls"><strong id="filter_records"></strong> <span class="hide"> of <strong id="total_records"><?php echo $mini_loading_image?></strong> </span> Activities found
										</div><span class="close_fil_box"><i class="fas fa-times"></i></span>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="septor"></div>
								<div class="rangebox">
									<button data-bs-target="#price-refine" data-bs-toggle="collapse" class="collapsebtn refine-header" type="button">
									Price
									</button>
									<?php echo $mini_loading_image?>
									<div id="price-refine" class="in">
										<div class="price_slider1">
											<div id="core_min_max_slider_values" class="hide">
												<input type="hiden" id="core_minimum_range_value" value="">
												<input type="hiden" id="core_maximum_range_value" value="">
											</div>
											<p id="hotel-price" class="level"></p>
											<div id="price-range" class="" aria-disabled="false"></div>
										</div>
									</div>
								</div>
								<div class="septor"></div>
									
								<div class="rangebox">
									<button data-bs-target="#hotelsearch-refine" data-bs-toggle="collapse" class="collapsebtn refine-header" type="button">
									Search Activities
									</button>
									<div id="hotelsearch-refine" class="in">
										<div class="boxins">
											<div class="relinput">
												<input type="text" class="srchhtl form-control" placeholder="Search activities by name..." id="tour-name" />
												<button type="button" class="srchsmall" id="tour-search-btn" aria-label="Search activities">
													<i class="bi bi-search"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
								<div class="septor"></div>

								<div class="rangebox" id="categories_div">
									<button data-bs-target="#sight_seen_type" data-bs-toggle="collapse" class="collapsebtn" type="button">
									Categories
									</button>
									<div id="sight_seen_type" class="in">
										<div class="boxins sight_seen_types" id="activity-categories-wrapper">
											
										</div>
									</div>
								</div>
								<div class="rangebox" id="duration_div">
									<button data-bs-target="#sight_duration" data-bs-toggle="collapse" class="collapsebtn" type="button">
									Duration
									</button>
									<div id="sight_duration" class="in">
										<div class="boxins sight_duration" id="activity-duration-wrapper">
											
										</div>
									</div>
								</div>
								<div class="rangebox" id="recommended_div">
									<button data-bs-target="#sight_recommended" data-bs-toggle="collapse" class="collapsebtn" type="button">
									Recommended For
									</button>
									<div id="sight_recommended" class="in">
										<div class="boxins sight_recommended" id="activity-recommended-wrapper">
											
										</div>
									</div>
								</div>
								<div class="rangebox hide">
									<button data-bs-target="#collapse2" data-bs-toggle="collapse" class="collapsebtn" type="button">
									Star Rating
									</button>
									<div id="collapse2" class="in">
										<div class="boxins marret" id="starCountWrapper">
											<a class="starone toglefil star-wrapper">
												<input class="hidecheck star-filter" type="checkbox" value="1">
												<div class="starin">
													1 <span class="starfa fa fa-star"></span>
													<span class="htlcount">-</span>
												</div>
											</a>
											<a class="starone toglefil star-wrapper">
												<input class="hidecheck star-filter" type="checkbox" value="2">
												<div class="starin">
													2 <span class="starfa fa fa-star"></span>
													<span class="htlcount">-</span>
												</div>
											</a>
											<a class="starone toglefil star-wrapper">
												<input class="hidecheck star-filter" type="checkbox" value="3">
												<div class="starin">
													3 <span class="starfa fa fa-star"></span>
													<span class="htlcount">-</span>
												</div>
											</a>
											<a class="starone toglefil star-wrapper">
												<input class="hidecheck star-filter" type="checkbox" value="4">
												<div class="starin">
													4 <span class="starfa fa fa-star"></span>
													<span class="htlcount">-</span>
												</div>
											</a>
											<a class="starone toglefil star-wrapper">
												<input class="hidecheck star-filter" type="checkbox" value="5">
												<div class="starin">
													5 <span class="starfa fa fa-star"></span>
													<span class="htlcount">-</span>
												</div>
											</a>
										</div>
									</div>
								</div>
								
							

								<div class="septor"></div>
								<div class="rangebox hide">
									<button data-bs-target="#collapse22" data-bs-toggle="collapse" class="collapsebtn" type="button">
									Promotion & discount Offers
									</button>
									<div id="collapse22" class="in">
										<div class="boxins">
											<ul class="locationul" id="discount-filter-wrapper">
											</ul>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			
				<!-- Prev|Next Searcrh Button Ends -->
				<div class="insideactivity">
					<div class="resultall">
						<div class="vluendsort">
							<div class="insidemyt col-12 nopad">

								<div class="col-9 mfulwdth nopad">
									<div class="filterforallnty" id="top-sort-list-wrapper">
										<div class="topmistyhtl" id="top-sort-list-1">
											<div class="col-12 nopad">
												<div class="insidemyt">
													<ul class="sortul">
														<li class="sortli threonly" data-sort="hn">
															<a class="sorta name-l-2-h asc" data-order="asc"><i class="fa fa-sort-alpha-asc"></i> <strong>Name</strong></a>
															<a class="sorta name-h-2-l hide des" data-order="desc"><i class="fa fa-sort-alpha-desc"></i> <strong>Name</strong></a>
														</li>
														<li class="sortli threonly" data-sort="sr">
															<a class="sorta star-l-2-h asc" data-order="asc"><i class="fa fa-star"></i> <strong>Rating</strong></a>
															<a class="sorta star-h-2-l hide  des" data-order="desc"><i class="fa fa-star"></i> <strong>Rating</strong></a>
														</li>
														<li class="sortli threonly" data-sort="p">
															<a class="sorta price-l-2-h asc" data-order="asc"><i class="fa fa-tag"></i> <strong>Price</strong></a>
															<a class="sorta price-h-2-l hide  des" data-order="desc"><i class="fa fa-tag"></i> <strong>Price</strong></a>
														</li>
												
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>						
                            <div class="col-3 mobile_none nopad">
                                <div class="mapviw noviews">
                                    <div class="mapviwhtl nopad noviews reswd">
                                           <div class="rit_view">
                                                   <a class="view_type grid_click active"><span class="bi bi-grid-3x3-gap"></span></a> 
                                           </div>
                                         </div>
                                    <div class="mapviwlist nopad noviews reswd">
                                        <div class="rit_view">
                                            <a class="view_type list_click" id="list_clickid"><span class="bi bi-list"></span></a> 
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
						</div>
						</div>

					
					<div class="allresult">
						<?php echo $loading_image;?>
					
						<!-- Result Skeleton Loader -->
						<div id="result-skeleton-loader" class="result_srch_htl">
							<?php for ($i = 1; $i <= 6; $i++): ?>
								<div class="rowresult r-r-i" style="margin-bottom: <?php echo $i === 1 ? 'var(--spacing-3)' : 'var(--spacing-4)'; ?>;">
									<div class="madgrid forhtlpopover">
										<div class="sightseeing-result-item">
											<div class="col-12 col-md-4 nopad listimage">
												<div class="imagehtldis">
													<div class="skeleton-loader skeleton-image"></div>
												</div>
											</div>
											<div class="col-12 col-md-8 nopad listfull">
												<div class="sidenamedesc">
													<div class="celhtl width70">
														<div class="innd acttbosrch">
															<div class="skeleton-loader skeleton-title"></div>
															<div class="skeleton-loader skeleton-category" style="width: 40%; margin-top: var(--spacing-2);"></div>
															<div class="strrat" style="margin-top: var(--spacing-2);">
																<div style="display: flex; gap: var(--spacing-2); flex-wrap: wrap;">
																	<div class="skeleton-loader skeleton-tags"></div>
																	<div class="skeleton-loader skeleton-tags" style="width: 80px;"></div>
																</div>
															</div>
															<div class="adreshotle h-adr" style="margin-top: var(--spacing-2);">
																<div class="skeleton-loader skeleton-location"></div>
															</div>
															<div style="display: flex; gap: var(--spacing-2); margin-top: var(--spacing-2); flex-wrap: wrap;">
																<div class="loc_see">
																	<span class="skeleton-loader skeleton-duration"></span>
																</div>
																<div class="loc_see refund">
																	<span class="skeleton-loader skeleton-duration" style="width: 100px;"></span>
																</div>
															</div>
														</div>
													</div>
													<div class="celhtl width30">
														<div class="sidepricewrp">
															<div class="skeleton-loader skeleton-price" style="margin-bottom: var(--spacing-3);"></div>
															<div class="skeleton-loader skeleton-button"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					
						<div id="tour_search_result" class="hotel-search-result-panel result_srch_htl hide">
						</div>
						
						<script>
						// Show skeleton loaders on page load
						$(document).ready(function() {
							$('#result-skeleton-loader').removeClass('hide');
							$('#filter-skeleton-loader').removeClass('hide');
						});
						</script>
							<!-- <div class="hotel_map">
									                        <div class="map_hotel" id="location_map"></div>
									                    </div> -->

							<div id="npl_img" class="text-center hide" loaded="true">
								<?='<img src="'.$GLOBALS['CI']->template->template_images('loader_v1.gif').'" alt="Please Wait"/>'?>
							</div>
							<div id="empty_tour_search_result" class="empty-state-container" style="display:none">
								<div class="empty-state-content">
									<div class="empty-state-icon">
										<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
											<circle cx="100" cy="100" r="80" fill="url(#emptyGradient)" opacity="0.1"/>
											<path d="M100 60C80.67 60 65 75.67 65 95C65 120 100 140 100 140C100 140 135 120 135 95C135 75.67 119.33 60 100 60Z" fill="url(#emptyGradient)" opacity="0.2"/>
											<path d="M100 85C95.58 85 92 88.58 92 93C92 97.42 95.58 101 100 101C104.42 101 108 97.42 108 93C108 88.58 104.42 85 100 85Z" fill="url(#emptyGradient)"/>
											<path d="M70 50L50 70M130 50L150 70M70 150L50 130M130 150L150 130" stroke="url(#emptyGradient)" stroke-width="2" stroke-linecap="round" opacity="0.3"/>
											<defs>
												<linearGradient id="emptyGradient" x1="0%" y1="0%" x2="100%" y2="100%">
													<stop offset="0%" style="stop-color:var(--color-primary);stop-opacity:1" />
													<stop offset="100%" style="stop-color:var(--color-primary-dark);stop-opacity:1" />
												</linearGradient>
											</defs>
										</svg>
									</div>
									<div class="empty-state-title">No Activities Found</div>
									<div class="empty-state-message">
										<p>We couldn't find any activities matching your search criteria.</p>
										<p class="empty-state-suggestions">Try adjusting your filters or search for a different location.</p>
									</div>
									<div class="empty-state-actions">
										<button type="button" class="btn-reset-filters" onclick="$('#reset_filters').click();">
											<i class="fas fa-redo"></i> Reset Filters
										</button>
									</div>
								</div>
							</div>
						<hr class="hr-10">
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
	
	<div id="empty-search-result" class="empty-search-page-container" style="display:none">
		<div class="empty-search-page-content">
			<div class="empty-search-page-icon">
				<svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="100" cy="100" r="80" fill="url(#emptySearchGradient)" opacity="0.1"/>
					<path d="M100 50C70.67 50 50 70.67 50 100C50 140 100 150 100 150C100 150 150 140 150 100C150 70.67 129.33 50 100 50Z" fill="url(#emptySearchGradient)" opacity="0.2"/>
					<path d="M100 80C92.27 80 86 86.27 86 94C86 101.73 92.27 108 100 108C107.73 108 114 101.73 114 94C114 86.27 107.73 80 100 80Z" fill="url(#emptySearchGradient)"/>
					<path d="M60 40L40 60M140 40L160 60M60 160L40 140M140 160L160 140" stroke="url(#emptySearchGradient)" stroke-width="2.5" stroke-linecap="round" opacity="0.3"/>
					<path d="M100 120L100 130M90 120L90 130M110 120L110 130" stroke="url(#emptySearchGradient)" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
					<defs>
						<linearGradient id="emptySearchGradient" x1="0%" y1="0%" x2="100%" y2="100%">
							<stop offset="0%" style="stop-color:var(--color-primary-primary);stop-opacity:1" />
							<stop offset="100%" style="stop-color:var(--color-primary-obsidian);stop-opacity:1" />
						</linearGradient>
					</defs>
				</svg>
			</div>
			<div class="empty-search-page-title">
				<i class="fas fa-map-marked-alt"></i> Oops!
			</div>
			<div class="empty-search-page-message">
				<p class="main-message">No Sightseeing places were found in this location today.</p>
				<p class="sub-message">
					Search results change daily based on availability. If you have an urgent requirement, please get in touch with our call center using the contact details mentioned on the home page. They will assist you to the best of their ability.
				</p>
			</div>
			<div class="empty-search-page-actions">
				<a href="<?php echo base_url(); ?>" class="btn-go-home">
					<i class="fas fa-home"></i> Go to Homepage
				</a>
				<button type="button" class="btn-try-again" onclick="window.location.reload();">
					<i class="fas fa-redo"></i> Try Again
				</button>
			</div>
		</div>
	</div>
</section>
 
<div class="modal fade bs-example-modal-lg" id="map-box-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Hotel Location Map</h4>
			</div>
			<div class="modal-body">
				<iframe src="" id="map-box-frame" name="map_box_frame" style="height: 500px;width: 850px;">
				</iframe>
			</div>
		</div>
	</div>
</div>
</div>
<input type="hidden" name="" id="selected_cate" value="">
<input type="hidden" name="" id="selected_sub_cate" value=""> 
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANXPM-4Tdxq9kMnI8OpL-M6kGsFFWreIY&callback=initialize" type="text/javascript"></script> -->
<?php
//echo $this->template->isolated_view('share/media/hotel_search');
?>
<script type="text/javascript">
	$(function(){
			$("#collapse3 .collapse ul li button.cate-btn-click").on("click",function(){
				//alert("hiiii");
			});
			 $(document).on("click", ".madgrid", function () {
               	
               	var search_id = "<?php echo $sight_seen_search_params['search_id']?>";
               	var booking_source = "<?=$active_booking_source[0]['source_id']?>";
               	var product_code =$(this).data('product-code');
               	var result_token = $(this).data('result-token');
                window.location ='<?php echo base_url()?>index.php/sightseeing/sightseeing_details?op=get_details&search_id='+search_id+'&booking_source='+booking_source+'&product_code='+product_code+'&result_token='+result_token;

              	//alert(url_text);


            });
	});
    $(document).ready(function () {	
        $(".close_fil_box").click(function () {
            $(".coleft").hide();
            $(".resultalls").removeClass("open");
        });
    });

</script>

<!-- Mobile Filter FAB Button and Overlay -->
<div class="filter-overlay" aria-hidden="true"></div>
<button class="filter-fab-btn" title="Open Filters" aria-label="Open Filters">
	<!-- Use Material Icons (same pattern as car filter) -->
	<i class="material-icons" aria-hidden="true">tune</i>
	<span class="filter-count hide" aria-hidden="true">0</span>
</button>

<script type="text/javascript">
$(document).ready(function(){
	// Open/Close sightseeing filter panel (mobile FAB)
	// Toggle behavior to match other templates (for example car-result) so clicking the FAB again closes the panel
	$(document).on('click', '.filter-fab-btn, .filter_show', function(e) {
		e && e.preventDefault();
		var isOpen = $('.coleft').hasClass('filter-open') || $('.resultalls').hasClass('open');
		if (isOpen) {
			// close
			$('.coleft').removeClass('filter-open');
			$('.resultalls').removeClass('open');
			$('.filter-overlay').removeClass('active');
			$('body').removeClass('filter-drawer-open');
			$('#filter-content').addClass('hide').hide();
		} else {
			// open
			$('.coleft').addClass('filter-open');
			$('.resultalls').addClass('open');
			$('#filter-content').removeClass('hide').show();
			$('#filter-skeleton-loader').addClass('hide').hide();
			$('.filter-overlay').addClass('active');
			$('body').addClass('filter-drawer-open');
		}
	});

	// Close filter panel
	$(document).on('click', '.close_fil_box, .filter-overlay', function() {
		$('.coleft').removeClass('filter-open');
		$('.resultalls').removeClass('open');
		$('.filter-overlay').removeClass('active');
		$('body').removeClass('filter-drawer-open');
	});

	// Update filter count badge
	function updateFilterCount() {
		var count = $('#activity-categories-wrapper input:checked').length || 0;
		// also include generic locationul or rangebox if present
		count += $('.locationul input:checked').length || 0;
		count += ($('.rangebox input:checked').length || 0);
		if (count > 0) {
			$('.filter-fab-btn .filter-count').removeClass('hide').text(count).attr('aria-hidden','false');
		} else {
			$('.filter-fab-btn .filter-count').addClass('hide').attr('aria-hidden','true');
		}
	}

	// Update count on filter input change
	$(document).on('change', '#activity-categories-wrapper input, .locationul input, .rangebox input', function() {
		updateFilterCount();
	});

	// Reset handler
	$(document).on('click', '#reset_filters', function() {
		setTimeout(updateFilterCount, 100);
	});

	// Initialize
	updateFilterCount();
});
</script>

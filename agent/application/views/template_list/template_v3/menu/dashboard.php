<style>
    .fc-day-number {
        font-size: inherit;
        font-weight: inherit;
        padding-right: 10px;
    }
    .placerows { padding: 0px !important; }
</style>
<?php
$active_domain_modules = $this->active_domain_modules;
$tiny_loader = $GLOBALS['CI']->template->template_images('tiny_loader_v1.gif');
$tiny_loader_img = '<img src="' . $tiny_loader . '" class="loader-img" alt="Loading">';
$booking_summary = array();
?>
<?php if(check_user_previlege('p14')):?>
<div class="bookng_engne_wrpper">
    <div class="container-fluid nopad">
        <div class="dashboard-container p-0">
            <div class="row dashboard-row">
            <?php if(check_user_previlege('p9')):?>
            <?php if (is_active_airline_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon flight-l-bg">
                                <i class="bi bi-airplane"></i>
                            </div>
                            <div class="stats-card-title">Flight Booking<div class="stats-card-number"><?= $flight_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a href="<?= base_url() ?>index.php/report/flight" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php endif;?>
            <?php if(check_user_previlege('p10')):?>
            <?php if (is_active_hotel_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon hotel-l-bg">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="stats-card-title">Hotel Booking<div class="stats-card-number"><?= $hotel_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a href="<?= base_url() ?>index.php/report/hotel" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php endif;?>
            <?php if (is_active_bus_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon bus-l-bg">
                                <i class="bi bi-bus-front"></i>
                            </div>
                            <div class="stats-card-title">Bus Booking<div class="stats-card-number"><?= $bus_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a href="<?= base_url() ?>index.php/report/bus" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if (is_active_transferv1_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon transfers-l-bg">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <div class="stats-card-title">Transfer Booking<div class="stats-card-number"><?= @$transfer_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a target="_blank" href="<?= base_url() ?>index.php/report/transfersv1" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
             <?php if (is_active_transfer_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon transfers-l-bg">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <div class="stats-card-title">Transfer Booking<div class="stats-card-number"><?= @$transfer_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a target="_blank" href="<?= base_url() ?>index.php/report/transfers" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if(check_user_previlege('p11')):?>
            <?php if (is_active_sightseeing_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon activities-l-bg">
                                <i class="bi bi-pin-map"></i>
                            </div>
                            <div class="stats-card-title">Activities Booking<div class="stats-card-number"><?= $sightseeing_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a target="_blank" href="<?= base_url() ?>index.php/report/activities" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php endif;?>
            <?php if(check_user_previlege('p13')):?>
            <?php if (is_active_package_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon holiday-l-bg">
                                <i class="bi bi-envelope-paper"></i>
                            </div>
                            <div class="stats-card-title">Holiday Enquiry<div class="stats-card-number">-</div></div>
                        </div>
                        <div class="stats-card-links">
                            <a href="<?= base_url() ?>index.php/report/package_enquiries" class="report-btn report-btn-primary"><i class="bi bi-envelope-open"></i> Enquiries</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php endif;?>
            <?php if(check_user_previlege('p12')):?>
            <?php if (is_active_car_module()) { ?>
                <div class="col-md-3 col-sm-6 col-12 mb-3">
                    <div class="stats-card">
                        <div class="stats-card-header">
                            <div class="stats-card-icon car-l-bg">
                                <i class="bi bi-taxi-front"></i>
                            </div>
                            <div class="stats-card-title">Car Booking<div class="stats-card-number"><?= $car_booking_count ?></div></div>
                        </div>
                        <div class="stats-card-links">
                            <a href="<?= base_url() ?>index.php/report/car" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> View Details</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php endif;?>

            </div>
        </div>
    </div>
    <?php endif;?>
    <!-- Search Engine Start -->
    <hr>
    <div class="clearfix"></div>
    <?php echo $search_engine;
     ?>
    </div>
    <div class="clearfix"></div>
    <hr>
    <!-- Search Engine End -->
</div>
    <!-- Charts Section -->
    <div class="row dashboard-row">
        <div class="col-md-7 mb-3">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-calendar-fill"></i> Booking Calendar</div>
                <div id='booking-calendar'></div>
            </div>
        </div>

       
        <div class="col-md-5">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-cash-coin-fill"></i> Booking Summary</div>
                <div id='booking-summary' style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>

    <div class="row dashboard-row">
        <div class="col-md-12">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-graph-up-fill"></i> Booking Timeline</div>
                <div id='booking-timeline' style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
    

<hr>
<div class="border-0">
    <div class="card-body p-0">
        <div class="row">
            <div class="col-md-6">
                <?php
                    $notification_list = '';
                    //$pagination = '<div>'. $GLOBALS['CI']->pagination->create_links().'</div>';
                    //$notification_list .= $pagination;
                    $notification_list .= '<ul class="list-group">';
                    if(valid_array($notification_list_details) == true) {
                        $event_date = '';
                        $event_loader_attr = '';
                        $segment_3 = $GLOBALS['CI']->uri->segment(3);
                        $current_record = (empty($segment_3) ? 0 : $segment_3);
                        foreach ($notification_list_details as $t_k => $t_v) {
                            $current_event_date = date('dS M Y', strtotime($t_v['created_datetime']));
                            $t_head = '<strong class="text-blue">'.$t_v['event_title'].'</strong>';
                            $t_body = $t_v['event_description'];
                            $t_foot = false;
                            $action_query_string = json_decode($t_v['action_query_string'], true);
                            $action_query_string = http_build_query($action_query_string['q_params']);
                            $notification_action_url = '';
                            switch ($t_v['event_origin']) {
                                case 'EID001' : //User Registration
                                    break;
                                case 'EID002' : //Profile Update
                                    break;
                                case 'EID003' : //Change Password
                                    break;
                                case 'EID004' : //Email Subscription
                                    break;
                                case 'EID005' : //Login - no body
                                    $t_head = $t_body;
                                    $t_body = false;
                                    break;
                                case 'EID006' : //Logout - no body
                                    $t_head = $t_body;
                                    $t_body = false;
                                    break;
                                case 'EID007' : //Balance Status
                                    break;
                                case 'EID008' : //Transaction
                                    break;
                                case 'EID009' : //Account Status
                                    break;
                                case 'EID010' : //Account Status
                                    break;
                                case 'EID011' : //Account Status
                                    $notification_action_url = base_url().'index.php/management/b2b_balance_manager/?'.$action_query_string;
                                    break;
                                case 'EID012' : //Credit Limit
                                $notification_action_url = base_url().'index.php/management/b2b_credit_limit/?'.$action_query_string;
                                break;
                            }
                            if($action_query_params['app_reference']!="")
                            {
                                $notification_action_url=base_url().'index.php/report/offline_flight_report?created_by_id=&app_reference='.$action_query_params['app_reference'].'&first_name=&last_name=&email=&mobile_number=&created_datetime_from=&created_datetime_to=&booking_status=';
                            }
                            $view_details_button = '<button class="btn btn-sm btn-primary float-end fontsize14" style="background: var(--color-primary-primary); border-color: var(--color-primary-primary); color: var(--color-white); padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 500;">View Details</button>';
                            $event_time_label = timeline_day_count($t_v['created_datetime']);
                            $notification_list .= '<li class="list-notices">
                                            <a href="'.$notification_action_url.'">
                                            <div class="col-sm-10">
                                              <div class="colslno">'.(++$current_record).'. </div>
                                              <i class="material-icons noticetext">account_balance_wallet</i>  <div class="noticewrp"><span class="noticemsgagent">'.$t_body.'</span>
                                              <span class="timenotice">     <i class="material-icons" style="font-size: 12px;">schedule</i> <span class="even-time-moments">'.$event_time_label.' ago</span></span>
                                              </div>
                                            </div>
                                            <div class="col-sm-2">
                                              '.$view_details_button.'
                                            </div>
                                        </li>';
                        }
                    }else {
                        $notification_list .= '<li class="list-group-item">No Notification Found !!</li>';
                    }
                    $notification_list .= '</ul>';
                    //echo $notification_list;
                ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Notification!!!</h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <?= $notification_list ?>
                        </ul>
                    </div>
                    <div class="box-footer text-center">
                        <a class="uppercase" href="<?= base_url() . 'index.php/utilities/notification_list' ?>">View All Notifications</a>
                    </div>
                </div>
            </div>
            <?php
            $latest_trans_list = '';
            $latest_trans_summary = '';
            if (valid_array($latest_transaction)) {
                //debug($latest_transaction);exit;
                foreach ($latest_transaction as $k => $v) {
                    //debug($v);exit;
                    $latest_trans_list .= '<li class="item">';
                    $latest_trans_list .= '<div class="product-img image"><i class="material-icons ' . get_arrangement_icon(module_name_to_id($v['transaction_type'])) . '">' . get_arrangement_material_icon(module_name_to_id($v['transaction_type'])) . '</i></div>';
                    $latest_trans_list .= '<div class="product-info">
									<a class="product-title" href="' . base_url() . 'index.php/transaction/logs?app_reference=' . trim($v['app_reference']) . '">
										' . $v['app_reference'] . ' -' . app_friendly_day($v['created_datetime']) . ' <span class="badge bg-primary float-end"><i class="material-icons" style="font-size: 12px;">currency_rupee</i> ' . ($v['grand_total']) . '</span>
									</a>
									<span class="product-description">
										' . $v['remarks'] . '
									</span>
								</div>';
                    $latest_trans_list .= '</li>';
                }
            }
            ?>
            <?php if(check_user_previlege('p19')): ?>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent Booking Transactions</h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <?= $latest_trans_list ?>
                        </ul>
                    </div>
                    <div class="box-footer text-center">
                        <a class="uppercase" href="<?= base_url() . 'index.php/transaction/logs' ?>">View All Transactions</a>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<?php
Js_Loader::$css[] = array('href' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/fullcalendar.min.css', 'media' => 'screen');
Js_Loader::$css[] = array('href' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/fullcalendar.print.css', 'media' => 'print');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('calendar.css') . '?v=' . time(), 'media' => 'screen');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/fullcalendar.min.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/amcharts5/amcharts5.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/amcharts5/xy.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/amcharts5/percent.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/amcharts5/themes.js', 'defer' => 'defer');
?>
<script>
    $(function () {
    // Wait for amCharts 5 to load
    function initBookingTimelineChart() {
    if (typeof am5 === 'undefined') {
    setTimeout(initBookingTimelineChart, 100);
    return;
    }
    
    // Create root element
    var root = am5.Root.new("booking-timeline");
    root._logo.dispose();
    
    // Create chart container with title
    var container = root.container.children.push(am5.Container.new(root, {
    width: am5.percent(100),
    height: am5.percent(100),
    layout: root.verticalLayout
    }));
    
    // Add title
    var title = container.children.push(am5.Label.new(root, {
    text: "Booking Details",
    fontSize: 22,
    fontWeight: "600",
    textAlign: "left",
    x: 0,
    fill: am5.color("#202124"),
    paddingTop: 0,
    paddingBottom: 5
    }));
    
    // Add subtitle
    var subtitle = container.children.push(am5.Label.new(root, {
    text: "Monthly booking trends and statistics",
    fontSize: 13,
    fill: am5.color("#5f6368"),
    textAlign: "left",
    x: 0,
    paddingTop: 0,
    paddingBottom: 20
    }));
    
    // Create chart
    var chart = container.children.push(am5xy.XYChart.new(root, {
    panX: false,
    panY: false,
    wheelX: "none",
    wheelY: "none",
    paddingLeft: 0,
    paddingRight: 0,
    paddingTop: 10,
    paddingBottom: 0
    }));
    
    // Create axes
    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
    categoryField: "month",
    renderer: am5xy.AxisRendererX.new(root, {
    minGridDistance: 30,
    cellStartLocation: 0.1,
    cellEndLocation: 0.9
    })
    }));
    
    var xRenderer = xAxis.get("renderer");
    if (xRenderer && xRenderer.labels && xRenderer.labels.template) {
    xRenderer.labels.template.setAll({
    fontSize: 12,
    fill: am5.color("#5f6368"),
    fontWeight: "500"
    });
    }
    
    if (xRenderer && xRenderer.grid && xRenderer.grid.template) {
    xRenderer.grid.template.setAll({
    stroke: am5.color("#f1f3f4"),
    strokeWidth: 1
    });
    }
    
    if (xRenderer && xRenderer.axisLine) {
    xRenderer.axisLine.setAll({
    stroke: am5.color("#e8eaed"),
    strokeWidth: 1
    });
    }
    
    xAxis.data.setAll(<?= json_encode(array_map(function($month) { return ['month' => $month]; }, $time_line_interval)); ?>);
    
    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    min: 0,
    max: <?php echo $max_count; ?>,
    renderer: am5xy.AxisRendererY.new(root, {})
    }));
    
    var yRenderer = yAxis.get("renderer");
    if (yRenderer && yRenderer.labels && yRenderer.labels.template) {
    yRenderer.labels.template.setAll({
    fontSize: 12,
    fill: am5.color("#5f6368"),
    fontWeight: "500"
    });
    }
    
    if (yRenderer && yRenderer.grid && yRenderer.grid.template) {
    yRenderer.grid.template.setAll({
    stroke: am5.color("#f1f3f4"),
    strokeWidth: 1
    });
    }
    
    if (yRenderer && yRenderer.axisLine) {
    yRenderer.axisLine.setAll({
    stroke: am5.color("#e8eaed"),
    strokeWidth: 1
    });
    }
    
    // Scrollbar disabled
    
    // Create stacked area series
    var timeLineData = <?= json_encode(array_values($time_line_report)); ?>;
    var colors = ['#F09814', '#EFCC57', '#d68410', '#48AC39', '#176FF4'];
    var months = <?= json_encode($time_line_interval); ?>;
    
    if (timeLineData && Array.isArray(timeLineData)) {
    // Prepare all data first
    var allSeriesData = [];
    timeLineData.forEach(function(seriesInfo, index) {
    if (seriesInfo && seriesInfo.data && Array.isArray(seriesInfo.data)) {
    var seriesName = seriesInfo.name || 'Series ' + index;
    var seriesColor = seriesInfo.color || colors[index % colors.length];
    
    // Prepare data - combine months with values
    var seriesData = [];
    months.forEach(function(month, i) {
    seriesData.push({
    month: month,
    value: seriesInfo.data[i] || 0
    });
    });
    
    allSeriesData.push({
    name: seriesName,
    color: seriesColor,
    data: seriesData
    });
    }
    });
    
    // Calculate cumulative values for stacking
    var cumulativeData = [];
    months.forEach(function(month, monthIndex) {
    var monthData = { month: month };
    var cumulative = 0;
    
    allSeriesData.forEach(function(seriesInfo, seriesIndex) {
    var value = seriesInfo.data[monthIndex].value || 0;
    var fieldName = "value" + seriesIndex;
    monthData[fieldName] = value;
    cumulative += value;
    monthData["stack" + seriesIndex] = cumulative - value; // Base for stacking
    });
    
    cumulativeData.push(monthData);
    });
    
    // Create stacked area series
    allSeriesData.forEach(function(seriesInfo, index) {
    var series = chart.series.push(am5xy.SmoothedXLineSeries.new(root, {
    name: seriesInfo.name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "value" + index,
    categoryXField: "month",
    fill: am5.color(seriesInfo.color),
    stroke: am5.color(seriesInfo.color),
    baseValueField: "stack" + index
    }));
    
    series.fills.template.setAll({
    fillOpacity: 0.6,
    visible: true
    });
    
    series.strokes.template.setAll({
    strokeWidth: 2,
    strokeOpacity: 0.8
    });
    
    // Tooltip
    series.set("tooltip", am5.Tooltip.new(root, {
    labelText: "{name}: {valueY} bookings",
    labelFill: am5.color("#202124"),
    fontSize: 13,
    fontWeight: "500"
    }));
    
    series.data.setAll(cumulativeData);
    series.appear(1000, 100);
    });
    }
    
    // Add legend
    var legend = container.children.push(am5.Legend.new(root, {
    centerX: am5.percent(50),
    x: am5.percent(50),
    marginTop: 20,
    marginBottom: 0
    }));
    
    legend.labels.template.setAll({
    fontSize: 13,
    fill: am5.color("#202124"),
    fontWeight: "500"
    });
    
    legend.markers.template.setAll({
    width: 12,
    height: 12
    });
    
    legend.data.setAll(chart.series.values);
    
    // Cursor disabled - no zoom
    
    // Make stuff animate on load
    chart.appear(1000, 100);
    }
    
    initBookingTimelineChart();
    // Beautiful Monthly Recap Chart with amCharts 5
    function initBookingSummaryChart() {
    if (typeof am5 === 'undefined') {
    setTimeout(initBookingSummaryChart, 100);
    return;
    }
    
    // Create root element
    var root = am5.Root.new("booking-summary");
    root._logo.dispose();
    
    // Create main container with title
    var mainContainer = root.container.children.push(am5.Container.new(root, {
    width: am5.percent(100),
    height: am5.percent(100),
    layout: root.verticalLayout
    }));
    
    // Add title
    var title = mainContainer.children.push(am5.Label.new(root, {
    text: "Monthly Recap Report",
    fontSize: 22,
    fontWeight: "600",
    textAlign: "left",
    x: 0,
    fill: am5.color("#202124"),
    paddingTop: 0,
    paddingBottom: 5
    }));
    
    // Add subtitle
    var subtitle = mainContainer.children.push(am5.Label.new(root, {
    text: "Profit analysis across different modules",
    fontSize: 13,
    fill: am5.color("#5f6368"),
    textAlign: "left",
    x: 0,
    paddingTop: 0,
    paddingBottom: 20
    }));
    
    // Create main chart container
    var container = mainContainer.children.push(am5.Container.new(root, {
    width: am5.percent(100),
    height: am5.percent(100),
    layout: root.horizontalLayout
    }));
    
    // Left side - Column Chart
    var chartContainer = container.children.push(am5.Container.new(root, {
    width: am5.percent(70),
    height: am5.percent(100)
    }));
    
    var chart = chartContainer.children.push(am5xy.XYChart.new(root, {
    panX: false,
    panY: false,
    wheelX: "none",
    wheelY: "none",
    paddingLeft: 0,
    paddingRight: 0,
    paddingTop: 10,
    paddingBottom: 0
    }));
    
    // Create axes
    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
    categoryField: "month",
    renderer: am5xy.AxisRendererX.new(root, {
    minGridDistance: 30,
    cellStartLocation: 0.1,
    cellEndLocation: 0.9
    })
    }));
    
    var xRenderer = xAxis.get("renderer");
    if (xRenderer && xRenderer.labels && xRenderer.labels.template) {
    xRenderer.labels.template.setAll({
    fontSize: 12,
    fill: am5.color("#5f6368"),
    fontWeight: "500"
    });
    }
    
    if (xRenderer && xRenderer.grid && xRenderer.grid.template) {
    xRenderer.grid.template.setAll({
    stroke: am5.color("#f1f3f4"),
    strokeWidth: 1
    });
    }
    
    if (xRenderer && xRenderer.axisLine) {
    xRenderer.axisLine.setAll({
    stroke: am5.color("#e8eaed"),
    strokeWidth: 1
    });
    }
    
    xAxis.data.setAll(<?= json_encode(array_map(function($month) { return ['month' => $month]; }, $time_line_interval)); ?>);
    
    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererY.new(root, {})
    }));
    
    var yRenderer = yAxis.get("renderer");
    if (yRenderer && yRenderer.labels && yRenderer.labels.template) {
    yRenderer.labels.template.setAll({
    fontSize: 12,
    fill: am5.color("#5f6368"),
    fontWeight: "500"
    });
    }
    
    if (yRenderer && yRenderer.grid && yRenderer.grid.template) {
    yRenderer.grid.template.setAll({
    stroke: am5.color("#f1f3f4"),
    strokeWidth: 1
    });
    }
    
    if (yRenderer && yRenderer.axisLine) {
    yRenderer.axisLine.setAll({
    stroke: am5.color("#e8eaed"),
    strokeWidth: 1
    });
    }
    
    // Add series
    var groupTimeLineData = <?= json_encode($group_time_line_report); ?>;
    var months = <?= json_encode($time_line_interval); ?>;
    var colors = ['#F09814', '#EFCC57', '#48AC39', '#176FF4', '#d68410', '#E91E63'];
    
    if (groupTimeLineData && Array.isArray(groupTimeLineData)) {
    groupTimeLineData.forEach(function(seriesData, index) {
    if (seriesData && seriesData.data && Array.isArray(seriesData.data) && seriesData.type !== 'pie') {
    var seriesColor = seriesData.color || colors[index % colors.length];
    
    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: seriesData.name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "value",
    categoryXField: "month",
    fill: am5.color(seriesColor),
    stroke: am5.color(seriesColor)
    }));
    
    var data = [];
    seriesData.data.forEach(function(value, i) {
    data.push({
    month: months[i],
    value: value || 0
    });
    });
    
    series.data.setAll(data);
    series.columns.template.setAll({
    cornerRadiusTL: 6,
    cornerRadiusTR: 6,
    strokeOpacity: 0
    });
    
    // Tooltip
    series.set("tooltip", am5.Tooltip.new(root, {
    labelText: "{name}: {valueY}",
    labelFill: am5.color("#202124"),
    fontSize: 13,
    fontWeight: "500"
    }));
    
    // Add data labels
    series.bullets.push(function() {
    return am5.Bullet.new(root, {
    locationY: 0.5,
    sprite: am5.Label.new(root, {
    text: "{valueY}",
    fill: am5.color("#202124"),
    centerY: am5.p50,
    centerX: am5.p50,
    populateText: true,
    fontSize: 11,
    fontWeight: "600"
    })
    });
    });
    
    series.appear(1000, 100);
    }
    });
    }
    
    // Add legend
    var legend = mainContainer.children.push(am5.Legend.new(root, {
    centerX: am5.percent(50),
    x: am5.percent(50),
    marginTop: 20,
    marginBottom: 0
    }));
    
    legend.labels.template.setAll({
    fontSize: 13,
    fill: am5.color("#202124"),
    fontWeight: "500"
    });
    
    legend.markers.template.setAll({
    width: 12,
    height: 12
    });
    
    legend.data.setAll(chart.series.values);
    
    // Right side - Pie Chart
    var pieContainer = container.children.push(am5.Container.new(root, {
    width: am5.percent(30),
    height: am5.percent(100),
    layout: root.verticalLayout
    }));
    
    var pieChart = pieContainer.children.push(am5percent.PieChart.new(root, {
    innerRadius: am5.percent(50)
    }));
    
    var pieSeries = pieChart.series.push(am5percent.PieSeries.new(root, {
    valueField: "value",
    categoryField: "category",
    alignLabels: false
    }));
    
    pieSeries.labels.template.setAll({
    textType: "circular",
    centerX: 0,
    centerY: 0,
    fontSize: 11,
    fontWeight: "600"
    });
    
    // Convert Highcharts format to amCharts format
    var pieData = <?= json_encode($module_total_earning); ?>;
    var convertedPieData = [];
    if (pieData && Array.isArray(pieData)) {
    pieData.forEach(function(item) {
    if (item && item.name && item.y !== undefined) {
    convertedPieData.push({
    category: item.name,
    value: item.y
    });
    }
    });
    }
    
    pieSeries.data.setAll(convertedPieData);
    
    // Set colors
    pieSeries.get("colors").set("colors", [
    am5.color("#F09814"),
    am5.color("#EFCC57"),
    am5.color("#48AC39"),
    am5.color("#176FF4"),
    am5.color("#d68410"),
    am5.color("#E91E63")
    ]);
    
    pieSeries.slices.template.setAll({
    stroke: am5.color("#ffffff"),
    strokeWidth: 3
    });
    
    pieSeries.set("tooltip", am5.Tooltip.new(root, {
    labelText: "{category}: {value}",
    labelFill: am5.color("#202124"),
    fontSize: 13,
    fontWeight: "500"
    }));
    
    pieSeries.appear(1000, 100);
    chart.appear(1000, 100);
    }
    
    initBookingSummaryChart();
    });
    // Wait for FullCalendar to be loaded
    function initCalendar() {
    if (typeof FullCalendar === 'undefined' || !FullCalendar.Calendar) {
    // Retry after a short delay if FullCalendar isn't loaded yet
    setTimeout(initCalendar, 100);
    return;
    }
    
    var calendar;
    var event_list = {};
    
    function enable_default_calendar_view()
    {
    load_calendar('');
    get_event_list();
    set_event_list();
    $('[data-bs-toggle="tooltip"]').tooltip();
    }
    
    function reset_calendar()
    {
    if (calendar) {
    calendar.removeAllEvents();
    get_event_list();
    set_event_list();
    }
    }
    
    //Reload Events
    setInterval(function(){
    reset_calendar();
    $('[data-bs-toggle="tooltip"]').tooltip();
    }, <?php echo SCHEDULER_RELOAD_TIME_LIMIT; ?>);
    
    enable_default_calendar_view();
    
    //sets all the events
    function get_event_list()
    {
    set_booking_event_list();
    }
    
    //loads all the loaded events
    function set_event_list()
    {
    if (calendar && event_list.booking_event_list) {
    calendar.removeAllEvents();
    // Transform events to FullCalendar v6 format
    var transformedEvents = event_list.booking_event_list.map(function(event) {
    return {
    title: event.title || '',
    start: event.start || '',
    end: event.end || null,
    extendedProps: {
    tip: event.tip || '',
    optid: event.optid || event.data_id || '',
    add_class: event.add_class || '',
    href: event.href || ''
    },
    className: event.add_class || '',
    url: event.href || null
    };
    });
    calendar.addEventSource(transformedEvents);
    }
    }

    //getting the value of arrangment details
    function set_booking_event_list()
    {
    $.ajax({
    url:app_base_url + "index.php/ajax/booking_events",
            async:false,
            success:function(response){
            event_list.booking_event_list = response.data;
            }
    });
    }

    //load default calendar with scheduled query - FullCalendar v6 API
    function load_calendar(event_list)
    {
    var calendarEl = document.getElementById('booking-calendar');
    if (!calendarEl) {
    console.error('Calendar element not found');
    return;
    }
    
    // Check if FullCalendar is loaded
    if (typeof FullCalendar === 'undefined' || !FullCalendar.Calendar) {
    console.error('FullCalendar is not loaded');
    return;
    }
    
    try {
    calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    views: {
    dayGridMonth: {
    buttonText: 'month'
    },
    timeGridWeek: {
    buttonText: 'week'
    },
    timeGridDay: {
    buttonText: 'day'
    },
    listWeek: {
    buttonText: 'list'
    }
    },
    editable: false,
    dayMaxEvents: true,
    events: event_list || [],
    eventDidMount: function(info) {
    // Custom event rendering
    if (info.event.extendedProps.tip) {
    info.el.setAttribute('title', info.event.extendedProps.tip);
    info.el.setAttribute('data-bs-toggle', 'tooltip');
    info.el.setAttribute('data-placement', 'bottom');
    }
    if (info.event.extendedProps.optid) {
    info.el.setAttribute('id', info.event.extendedProps.optid);
    }
    if (info.event.extendedProps.add_class) {
    info.el.classList.add(info.event.extendedProps.add_class);
    }
    if (info.event.extendedProps.href) {
    info.el.setAttribute('href', info.event.extendedProps.href);
    info.el.setAttribute('target', '_blank');
    }
    // Hide time if present
    var timeEl = info.el.querySelector('.fc-event-time');
    if (timeEl) timeEl.style.display = 'none';
    
    // Apply custom styles
    info.el.style.fontSize = '0.75rem';
    info.el.style.padding = '0.375rem 0.75rem';
    info.el.style.borderRadius = '8px';
    info.el.style.fontWeight = '600';
    info.el.style.margin = '0.25rem 0.5rem';
    },
    eventClick: function(info) {
    if (info.event.extendedProps.href) {
    window.open(info.event.extendedProps.href, '_blank');
    }
    }
    });
    
    calendar.render();
    } catch (error) {
    console.error('Error initializing calendar:', error);
    }
    }
    
    function focus_date(date)
    {
    if (calendar) {
    calendar.gotoDate(date);
    }
    }
    }
    
    // Initialize when DOM and FullCalendar are ready
    $(document).ready(function() {
    initCalendar();
    });
</script>


<?php
$active_domain_modules = $this->active_domain_modules;
$tiny_loader = $GLOBALS['CI']->template->template_images('tiny_loader_v1.gif');
$tiny_loader_img = '<img src="' . $tiny_loader . '" class="loader-img" alt="Loading">';
$booking_summary = array();
$b2c = is_active_module('b2c');
$b2b = is_active_module('b2b');
?>
<div class="dashboard-container p-0">
    <div class="row dashboard-row">
        <?php if (is_active_airline_module()) { ?>
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-icon flight-l-bg">
                        <i class="bi bi-airplane"></i>
                    </div>
                    <div class="stats-card-title">Flight Booking<div class="stats-card-number"><?= $flight_booking_count ?></div>   </div>
                    
                    </div>
                    <div class="stats-card-links">
                        <?php if ($b2c): ?>
                            <a href="<?= base_url() ?>index.php/report/b2c_flight_report" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> B2C Report</a>
                        <?php endif; ?>
                        <?php if ($b2b): ?>
                            <a href="<?= base_url() ?>index.php/report/b2b_flight_report" class="report-btn report-btn-secondary"><i class="bi bi-people"></i> Agent Report</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
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
                        <?php if ($b2c): ?>
                            <a href="<?= base_url() ?>index.php/report/b2c_hotel_report" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> B2C Report</a>
                        <?php endif; ?>
                        <?php if ($b2b): ?>
                            <a href="<?= base_url() ?>index.php/report/b2b_hotel_report" class="report-btn report-btn-secondary"><i class="bi bi-people"></i> Agent Report</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
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
                        <?php if ($b2c): ?>
                            <a href="<?= base_url() ?>index.php/report/b2c_bus_report" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> B2C Report</a>
                        <?php endif; ?>
                        <?php if ($b2b): ?>
                            <a href="<?= base_url() ?>index.php/report/b2b_bus_report" class="report-btn report-btn-secondary"><i class="bi bi-people"></i> Agent Report</a>
                        <?php endif; ?>
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
                    <div class="stats-card-title">Transfer Booking<div class="stats-card-number"><?= @$transfers_booking_count ?></div></div>
                    
                    </div>
                    <div class="stats-card-links">
                        <?php if ($b2c): ?>
                            <a href="<?= base_url() ?>index.php/report/b2c_transfers_report" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> B2C Report</a>
                        <?php endif; ?>
                        <?php if ($b2b): ?>
                            <a href="<?= base_url() ?>index.php/report/b2b_transfers_report" class="report-btn report-btn-secondary"><i class="bi bi-people"></i> Agent Report</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>



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
                        <?php if ($b2c): ?>
                            <a href="<?= base_url() ?>index.php/report/b2c_activities_report" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> B2C Report</a>
                        <?php endif; ?>
                        <?php if ($b2b): ?>
                            <a href="<?= base_url() ?>index.php/report/b2b_activities_report" class="report-btn report-btn-secondary"><i class="bi bi-people"></i> Agent Report</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (is_active_car_module()) { ?>
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="stats-card">
                <div class="stats-card-header">
                    <div class="stats-card-icon car-l-bg">
                        <i class="bi bi-taxi-front"></i>
                    </div>
                    <div class="stats-card-title">Car Booking <div class="stats-card-number"><?= $car_booking_count ?></div></div>
                    
                    </div>
                    <div class="stats-card-links">
                        <?php if ($b2c): ?>
                            <a href="<?= base_url() ?>index.php/report/b2c_car_report" class="report-btn report-btn-primary"><i class="bi bi-file-earmark-text"></i> B2C Report</a>
                        <?php endif; ?>
                        <?php if ($b2b): ?>
                            <a href="<?= base_url() ?>index.php/report/b2b_car_report" class="report-btn report-btn-secondary"><i class="bi bi-people"></i> Agent Report</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>


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
                        <a href="<?= base_url() ?>index.php/supplier/enquiries" class="report-btn report-btn-primary"><i class="bi bi-envelope-open"></i> Enquiries</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Charts Section -->
    <div class="row dashboard-row">
        <div class="col-md-7 mb-3">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-calendar-fill"></i> Booking Calendar</div>
                <div id='booking-calendar' style="width: 100%; min-height: 500px;"></div>
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
    
<?php
$latest_user_list = '';
$latest_user_summary = array();
if (valid_array($latest_user)) {
    $face_icon = $GLOBALS['CI']->template->template_images('face.png');
    foreach ($latest_user as $k => $v) {
        if (isset($latest_user_summary[$v['user_type']]) == false) {
            $latest_user_summary[$v['user_type']] = 0;
        }
        $latest_user_summary[$v['user_type']]++;
        if (empty($v['image']) == false) {
            $u_image = $GLOBALS['CI']->template->domain_images($v['image']);
        } else {
            $u_image = $face_icon;
        }
        $latest_user_list .= '<li>';
        $latest_user_list .= '<img alt="User Image" src="' . $u_image . '" class="">';
        $latest_user_list .= '<a href="#" class="users-list-name">' . login_status($v['logout_date_time']) . ' ' . $v['first_name'] . ' ' . $v['last_name'] . '</a>';
        $latest_user_list .= '<span class="users-list-date">' . member_since($v['created_datetime']) . '</span>';
        $latest_user_list .= '</li>';
    }
}
?>

    <!-- Users and Transactions Section -->
    <div class="row dashboard-row">
        <div class="col-md-6">
            <div class="user-list-card">
                <div class="box-header">
                    <h3 class="box-title"><i class="bi bi-people"></i> Latest Members</h3>
                    <div class="box-tools float-end">
                            <?php
                            if (valid_array($latest_user_summary) == true) {
                                foreach ($latest_user_summary as $k => $v) {
                                    echo '<span class="badge bg-success">' . $v . ' ' . $k . '</span>';
                                }
                            }
                            ?>
                        </div>
                        
                    </div>
                    <div class="box-body">
                    <ul class="users-list">
                        <?= $latest_user_list ?>
                    </ul>
                </div>
                <div class="box-footer text-center" style="padding-top: 16px; border-top: 2px solid #f0f0f0;">
                    <a href="<?= base_url() . 'index.php/user/user_management' ?>" style="color: #495057; font-weight: 600; text-decoration: none;">View All Users</a>
                </div>
                </div>
                
                
            </div>
       
            <?php
            //debug($latest_transaction);exit;
            $latest_trans_list = '';
            $latest_trans_summary = '';
            if (valid_array($latest_transaction)) {
                foreach ($latest_transaction as $k => $v) {
                    $latest_trans_list .= '<li class="item">';
                    $latest_trans_list .= '<div class="product-img image"><i class="' . get_arrangement_icon(module_name_to_id($v['transaction_type'])) . '"></i></div>';
                    $latest_trans_list .= '<div class="product-info">';
                    $latest_trans_list .= /*'<a class="product-title" href="#" style="cursor:auto;">'*/
                        $v['app_reference'] . ' -' . app_friendly_day($v['created_datetime']) . ' <span class="badge bg-primary float-end"><i class="fa fa-inr"></i> ' . ($v['grand_total']) . '</span>';
                    $latest_trans_list .= /*</a> */
                        '<span class="product-description">
										' . $v['remarks'] . '
									</span>
								</div>';
                    $latest_trans_list .= '</li>';
                }
            }
            if (check_user_previlege('p118')): ?>
        <div class="col-md-6">
            <div class="user-list-card">
                <div class="box-header">
                    <h3 class="box-title"><i class="bi bi-file-earmark-text"></i> Recent Transactions</h3>
                </div>
                <div class="box-body">
                    <ul class="transaction-list">
                        <?= $latest_trans_list ?>
                    </ul>
                </div>
                <div class="box-footer text-center" style="padding-top: 16px; border-top: 2px solid #f0f0f0;">
                    <a href="<?= base_url() . 'index.php/transaction/logs' ?>" style="color: #495057; font-weight: 600; text-decoration: none;">View All Transactions</a>
                </div>
            </div>
        </div>
            <?php endif; ?>
    </div>
</div>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/amcharts5/amcharts5.js" defer></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/amcharts5/xy.js" defer></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/amcharts5/percent.js" defer></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/amcharts5/themes.js" defer></script>
<script>
    <?php
    //debug($time_line_interval);exit;
    ?>
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
    var groupTimeLineData = [
    <?= (isset($group_time_line_report[0]) ? json_encode($group_time_line_report[0]) . ',' : ''); ?>
    <?= (isset($group_time_line_report[1]) ? json_encode($group_time_line_report[1]) . ',' : ''); ?>
    <?= (isset($group_time_line_report[2]) ? json_encode($group_time_line_report[2]) . ',' : ''); ?>
    <?= (isset($group_time_line_report[3]) ? json_encode($group_time_line_report[3]) . ',' : ''); ?>
    <?= (isset($group_time_line_report[4]) ? json_encode($group_time_line_report[4]) . ',' : ''); ?>
    <?= (isset($group_time_line_report[5]) ? json_encode($group_time_line_report[5]) . ',' : ''); ?>
    <?= (isset($group_time_line_report[6]) ? json_encode($group_time_line_report[6]) . ',' : ''); ?>
    ];
    
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
    });</script>
<script>
    // Global calendar variable
    var calendar;
    var event_list = {};
    
    // Wait for FullCalendar to be loaded
    function initCalendar() {
    if (typeof FullCalendar === 'undefined' || !FullCalendar.Calendar) {
    setTimeout(initCalendar, 100);
    return;
    }
    
    function enable_default_calendar_view() {
    load_calendar('');
    get_event_list();
    set_event_list();
    $('[data-bs-toggle="tooltip"]').tooltip();
    }
    
    function reset_calendar() {
    if (calendar) {
    calendar.removeAllEvents();
    get_event_list();
    set_event_list();
    }
    }
    
    //sets all the events
    function get_event_list() {
    set_booking_event_list();
    }
    
    //loads all the loaded events
    function set_event_list() {
    if (calendar && event_list.booking_event_list) {
    calendar.addEventSource(event_list.booking_event_list);
    if ("booking_event_list" in event_list && event_list.booking_event_list.length > 0) {
    //focus_date(event_list.booking_event_list[0]['start']);
    }
    }
    }
    
    //getting the value of arrangment details
    function set_booking_event_list() {
    $.ajax({
    url: app_base_url + "index.php/ajax/booking_events",
    async: false,
    success: function (response) {
    event_list.booking_event_list = response.data;
    }
    });
    }
    
    //load default calendar with scheduled query
    function load_calendar(event_list_data) {
        var calendarEl = document.getElementById('booking-calendar');
        if (!calendarEl) {
        console.error('Calendar element not found');
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
        editable: false,
        dayMaxEvents: true,
        events: event_list_data || [],
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
        } catch (e) {
        console.error("Error initializing FullCalendar:", e);
        }
        }
        
    function focus_date(date) {
    if (calendar) {
    calendar.gotoDate(date);
    }
    }
    
    //Reload Events
    setInterval(function () {
    reset_calendar();
    $('[data-bs-toggle="tooltip"]').tooltip();
    }, <?php echo SCHEDULER_RELOAD_TIME_LIMIT; ?>);
    
    enable_default_calendar_view();
    
    $(document).on('click', '.event-hand', function () {
    
    });
    }
    
    // Start initialization when DOM and FullCalendar are ready
    $(document).ready(function() {
    // Wait for FullCalendar to load, then initialize
    function checkAndInit() {
    if (typeof FullCalendar === 'undefined' || !FullCalendar.Calendar) {
    setTimeout(checkAndInit, 100);
    return;
    }
    var calendarEl = document.getElementById('booking-calendar');
    if (!calendarEl) {
    setTimeout(checkAndInit, 100);
    return;
    }
    initCalendar();
    }
    checkAndInit();
    });</script>
<style>
    .fc-day-number {
        font-size: inherit;
        font-weight: inherit;
        padding-right: 10px;
    }
</style>
<link href='<?php echo SYSTEM_TEMPLATE_LIST; ?>/template_v3/css/calendar.css' rel='stylesheet' />
<link href='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
<script src='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fullcalendar/index.global.min.js' defer></script>
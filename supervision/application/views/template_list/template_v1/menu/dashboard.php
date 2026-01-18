
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
        <div class="col-md-12 mb-3">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-calendar-fill"></i> Booking Calendar</div>
                <div id='booking-calendar'></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-graph-up-fill"></i> Booking Timeline</div>
                <div id='booking-timeline'></div>
            </div>
        </div>
    </div>
    
    <div class="row dashboard-row">
        <div class="col-md-12">
            <div class="chart-card">
                <div class="chart-card-title"><i class="bi bi-cash-coin-fill"></i> Booking Summary</div>
                <div id='booking-summary'></div>
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
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/Highcharts/js/highcharts.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/Highcharts/js/modules/exporting.js"></script>
<script>
    <?php
    //debug($time_line_interval);exit;
    ?>
    $(function () {
        //LEAD REPORT -line graph
        $('#booking-timeline').highcharts({
            credits: {
                enabled: false
            },
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Booking Details',
                x: - 20 //center
            },
            subtitle: {
                text: '',
                x: - 20
            },
            xAxis: {
                categories: <?php echo json_encode($time_line_interval); ?>,
                tickPixelInterval: 0
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                max: <?php echo $max_count; ?>,
                title: {
                    text: '<?php echo 'No Of Booking'; ?>'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                title: {
                    text: 'No Of Booking'
                },
                subtitle: {
                    text: 'count'
                },
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0,
                labelFormatter: function () {
                    var total = 0;
                    var total_face_value = this.userOptions.total_earned || 0;
                    for (var i = this.yData.length; i--;) {
                        total += this.yData[i];
                    };
                    return this.name + '(' + total + ')';
                }

            },
            series: <?php echo json_encode(array_values($time_line_report)); ?>,
            navigation: {
                buttonOptions: {
                    align: 'right',
                    verticalAlign: 'top',
                    x: 0,
                    y: 0
                }
            }

        });
        $('#booking-summary').highcharts({
            title: {
                text: 'Monthly Recap Report'
            },
            xAxis: {
                categories: <?php echo json_encode($time_line_interval); ?>
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Profit In <?php echo COURSE_LIST_DEFAULT_CURRENCY_VALUE ?>'
                }
            },
            labels: {
                items: [{
                    html: 'Total Profit Earned in <?php echo get_application_default_currency() ?>',
                    style: {
                        left: '50px',
                        top: '18px',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                    }
                }]
            },
            series: [
                <?= (isset($group_time_line_report[0]) ? json_encode($group_time_line_report[0]) . ',' : ''); ?>
<?= (isset($group_time_line_report[1]) ? json_encode($group_time_line_report[1]) . ',' : ''); ?>
<?= (isset($group_time_line_report[2]) ? json_encode($group_time_line_report[2]) . ',' : ''); ?>
<?= (isset($group_time_line_report[3]) ? json_encode($group_time_line_report[3]) . ',' : ''); ?>
<?= (isset($group_time_line_report[4]) ? json_encode($group_time_line_report[4]) . ',' : ''); ?>
<?= (isset($group_time_line_report[5]) ? json_encode($group_time_line_report[5]) . ',' : ''); ?>
<?= (isset($group_time_line_report[6]) ? json_encode($group_time_line_report[6]) . ',' : ''); ?>



            {
                    type: 'pie',
                    name: 'Total Earning',
                    data: <?php echo json_encode($module_total_earning) ?>,
                    center: [100, 80],
                    size: 100,
                    showInLegend: false,
                    dataLabels: {
                        enabled: false
                    }
                }]
        });
    });</script>
<script>
    $(document).ready(function () {
        var event_list = {};
        function enable_default_calendar_view() {
            load_calendar('');
            get_event_list();
            set_event_list();
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
        function reset_calendar() {
            $("#booking-calendar").fullCalendar('removeEvents');
            get_event_list();
            set_event_list();
        }
        //Reload Events
        setInterval(function () {
            reset_calendar();
            $('[data-bs-toggle="tooltip"]').tooltip();
        }, <?php echo SCHEDULER_RELOAD_TIME_LIMIT; ?>);
        enable_default_calendar_view();
        //sets all the events
        function get_event_list() {
            set_booking_event_list();
        }
        //loads all the loaded events
        function set_event_list() {
            $("#booking-calendar").fullCalendar('addEventSource', event_list.booking_event_list);
            if ("booking_event_list" in event_list && event_list.booking_event_list.hasOwnProperty(0)) {
                //focus_date(event_list.booking_event_list[0]['start']);
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
        function load_calendar(event_list) {
            $('#booking-calendar').fullCalendar({
                header: {
                    center: 'title'
                },
                //defaultDate: '2014-11-12', 
                editable: false,
                eventLimit: false, // allow "more" link when too many events
                events: event_list,
                eventRender: function (event, element) {
                    element.attr('data-bs-toggle', 'tooltip');
                    element.attr('data-placement', 'bottom');
                    element.attr('title', event.tip);
                    element.attr('id', event.optid);
                    element.find('.fc-time').attr('class', "hide");
                    element.attr('class', event.add_class + ' fc-day-grid-event fc-event fc-start fc-end');
                    element.attr('href', event.href);
                    element.attr('target', '_blank');
                    element.css({ 'font-size': '10px', 'padding': '1px' });
                    if (event.prepend_element) {
                        element.prepend(event.prepend_element);
                    }
                },
                eventDrop: function (event, delta) {
                    event.end = event.end || event.start;
                    if (event.start && event.end) {
                        update_event_list(event.optid, event.start.format(), event.end.format());
                        focus_date(event.start.format());
                    } else {
                        reset_calendar();
                    }
                }
            });
        }
        function focus_date(date) {
            $('#booking-calendar').fullCalendar('gotoDate', date);
        }

        $(document).on('click', '.event-hand', function () {

        });
    });</script>
<style>
    .fc-day-number {
        font-size: inherit;
        font-weight: inherit;
        padding-right: 10px;
    }
</style>
<link defer href='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link defer href='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fullcalendar/fullcalendar.print.css' rel='stylesheet'
    media='print' />
<script defer src='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fullcalendar/lib/moment.min.js'></script>
<script defer src='<?php echo SYSTEM_RESOURCE_LIBRARY; ?>/fullcalendar/fullcalendar.min.js'></script>
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
Js_Loader::$css[] = array('href' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/fullcalendar.css', 'media' => 'screen');
Js_Loader::$css[] = array('href' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/fullcalendar.print.css', 'media' => 'print');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/lib/moment.min.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/fullcalendar/fullcalendar.min.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/Highcharts/js/highcharts.js', 'defer' => 'defer');
Js_Loader::$js[] = array('src' => SYSTEM_RESOURCE_LIBRARY . '/Highcharts/js/modules/exporting.js', 'defer' => 'defer');
?>
<script>
    $(function () {
    // Beautiful Booking Chart
    $('#booking-timeline').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            type: 'areaspline',
            backgroundColor: 'transparent',
            style: {
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            spacingTop: 20,
            spacingBottom: 20,
            spacingLeft: 20,
            spacingRight: 20
        },
        title: {
            text: 'Booking Details',
            align: 'left',
            style: {
                fontSize: '22px',
                fontWeight: '600',
                color: '#202124',
                letterSpacing: '-0.5px'
            },
            x: 20,
            y: 25
        },
        subtitle: {
            text: 'Monthly booking trends and statistics',
            align: 'left',
            style: {
                fontSize: '13px',
                color: '#5f6368',
                fontWeight: '400'
            },
            x: 20,
            y: 50
        },
        xAxis: {
            categories: <?= json_encode($time_line_interval); ?>,
            tickPixelInterval: 0,
            lineColor: '#e8eaed',
            tickColor: '#e8eaed',
            labels: {
                style: {
                    fontSize: '12px',
                    color: '#5f6368',
                    fontWeight: '500'
                }
            },
            gridLineColor: '#f1f3f4',
            gridLineWidth: 1
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            max: <?php echo $max_count; ?>,
            title: {
                text: 'Number of Bookings',
                style: {
                    fontSize: '13px',
                    color: '#5f6368',
                    fontWeight: '600'
                }
            },
            gridLineColor: '#f1f3f4',
            gridLineWidth: 1,
            labels: {
                style: {
                    fontSize: '12px',
                    color: '#5f6368',
                    fontWeight: '500'
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.98)',
            borderWidth: 1,
            borderColor: '#e8eaed',
            borderRadius: 8,
            shadow: {
                color: 'rgba(0, 0, 0, 0.1)',
                offsetX: 0,
                offsetY: 4,
                opacity: 0.15,
                width: 8
            },
            style: {
                fontSize: '13px',
                fontWeight: '500',
                color: '#202124'
            },
            useHTML: true,
            headerFormat: '<div style="font-size: 12px; font-weight: 600; color: #5f6368; margin-bottom: 6px;">{point.key}</div>',
            pointFormat: '<div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: {series.color}; display: inline-block;"></span><span style="font-weight: 600;">{series.name}:</span> <span style="color: #F09814; font-weight: 700;">{point.y}</span> bookings</div>',
            footerFormat: '',
            shared: true,
            padding: 12
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0,
            itemStyle: {
                fontSize: '13px',
                color: '#202124',
                fontWeight: '500'
            },
            itemHoverStyle: {
                color: '#F09814'
            },
            itemDistance: 20,
            symbolRadius: 6,
            symbolHeight: 12,
            symbolWidth: 12,
            symbolPadding: 8,
            labelFormatter: function() {
                var total = 0;
                for (var i = this.yData.length; i--; ) {
                    total += this.yData[i];
                }
                return '<span style="font-weight: 600;">' + this.name + '</span> <span style="color: #5f6368; font-weight: 400;">(' + total + ')</span>';
            }
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.15,
                lineWidth: 3,
                marker: {
                    enabled: true,
                    radius: 5,
                    lineWidth: 2,
                    lineColor: '#ffffff',
                    symbol: 'circle',
                    states: {
                        hover: {
                            enabled: true,
                            radius: 7,
                            lineWidth: 3
                        }
                    }
                },
                states: {
                    hover: {
                        lineWidth: 4
                    }
                }
            }
        },
        colors: ['#F09814', '#EFCC57', '#d68410', '#48AC39', '#176FF4'],
        series: <?= json_encode(array_values($time_line_report)); ?>,
        navigation: {
            buttonOptions: {
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 10,
                theme: {
                    fill: '#ffffff',
                    stroke: '#e8eaed',
                    'stroke-width': 1,
                    r: 6,
                    style: {
                        color: '#5f6368'
                    },
                    states: {
                        hover: {
                            fill: '#fff5e6',
                            stroke: '#F09814'
                        },
                        select: {
                            fill: '#F09814',
                            stroke: '#F09814',
                            style: {
                                color: '#ffffff'
                            }
                        }
                    }
                }
            }
        }
    });
    // Beautiful Monthly Recap Chart
    $('#booking-summary').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            backgroundColor: 'transparent',
            style: {
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            spacingTop: 20,
            spacingBottom: 20,
            spacingLeft: 20,
            spacingRight: 20
        },
        title: {
            text: 'Monthly Recap Report',
            align: 'left',
            style: {
                fontSize: '22px',
                fontWeight: '600',
                color: '#202124',
                letterSpacing: '-0.5px'
            },
            x: 20,
            y: 25
        },
        subtitle: {
            text: 'Profit analysis across different modules',
            align: 'left',
            style: {
                fontSize: '13px',
                color: '#5f6368',
                fontWeight: '400'
            },
            x: 20,
            y: 50
        },
        xAxis: {
            categories: <?= json_encode($time_line_interval); ?>,
            lineColor: '#e8eaed',
            tickColor: '#e8eaed',
            labels: {
                style: {
                    fontSize: '12px',
                    color: '#5f6368',
                    fontWeight: '500'
                }
            },
            gridLineColor: '#f1f3f4',
            gridLineWidth: 1
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Profit In <?= COURSE_LIST_DEFAULT_CURRENCY_VALUE ?>',
                style: {
                    fontSize: '13px',
                    color: '#5f6368',
                    fontWeight: '600'
                }
            },
            gridLineColor: '#f1f3f4',
            gridLineWidth: 1,
            labels: {
                style: {
                    fontSize: '12px',
                    color: '#5f6368',
                    fontWeight: '500'
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(255, 255, 255, 0.98)',
            borderWidth: 1,
            borderColor: '#e8eaed',
            borderRadius: 8,
            shadow: {
                color: 'rgba(0, 0, 0, 0.1)',
                offsetX: 0,
                offsetY: 4,
                opacity: 0.15,
                width: 8
            },
            style: {
                fontSize: '13px',
                fontWeight: '500',
                color: '#202124'
            },
            shared: true,
            padding: 12
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom',
            borderWidth: 0,
            itemStyle: {
                fontSize: '13px',
                color: '#202124',
                fontWeight: '500'
            },
            itemHoverStyle: {
                color: '#F09814'
            },
            itemDistance: 20,
            symbolRadius: 6,
            symbolHeight: 12,
            symbolWidth: 12,
            symbolPadding: 8
        },
        plotOptions: {
            column: {
                borderRadius: 6,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '11px',
                        fontWeight: '600',
                        color: '#202124',
                        textOutline: 'none'
                    }
                }
            },
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                    style: {
                        fontSize: '11px',
                        fontWeight: '600',
                        color: '#202124',
                        textOutline: 'none'
                    }
                },
                borderWidth: 3,
                borderColor: '#ffffff',
                shadow: {
                    color: 'rgba(0, 0, 0, 0.1)',
                    offsetX: 0,
                    offsetY: 2,
                    width: 4
                }
            }
        },
        colors: ['#F09814', '#EFCC57', '#48AC39', '#176FF4', '#d68410', '#E91E63'],
        series: [<?= (isset($group_time_line_report[0]) ? json_encode($group_time_line_report[0]) . ',' : ''); ?>
<?= (isset($group_time_line_report[1]) ? json_encode($group_time_line_report[1]) . ',' : ''); ?>
<?= (isset($group_time_line_report[2]) ? json_encode($group_time_line_report[2]) . ',' : ''); ?>
<?= (isset($group_time_line_report[3]) ? json_encode($group_time_line_report[3]) . ',' : ''); ?>
<?= (isset($group_time_line_report[4]) ? json_encode($group_time_line_report[4]) . ',' : ''); ?>
<?= (isset($group_time_line_report[5]) ? json_encode($group_time_line_report[5]) . ',' : ''); ?>

            {
                type: 'pie',
                name: 'Total Earning',
                data: <?= json_encode($module_total_earning) ?>,
                center: ['75%', '30%'],
                size: 140,
                showInLegend: false,
                dataLabels: {
                    enabled: true,
                    distance: 10
                }
            }],
        navigation: {
            buttonOptions: {
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 10,
                theme: {
                    fill: '#ffffff',
                    stroke: '#e8eaed',
                    'stroke-width': 1,
                    r: 6,
                    style: {
                        color: '#5f6368'
                    },
                    states: {
                        hover: {
                            fill: '#fff5e6',
                            stroke: '#F09814'
                        }
                    }
                }
            }
        }
    });
    });
    $(document).ready(function() {

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
    $("#booking-calendar").fullCalendar('removeEvents');
    get_event_list();
    set_event_list();
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
    $("#booking-calendar").fullCalendar('addEventSource', event_list.booking_event_list);
    // if ("booking_event_list" in event_list && event_list.booking_event_list.hasOwnProperty(0)) {
    // //focus_date(event_list.booking_event_list[0]['start']);
    // }
    }

    //getting the value of arrangment details
    function set_booking_event_list()
    {
    $.ajax({
    url:app_base_url + "index.php/ajax/booking_events",
            async:false,
            success:function(response){
            //console.log(response)
            event_list.booking_event_list = response.data;
            }
    });
    }

    //load default calendar with scheduled query
    function load_calendar(event_list)
    {
    $('#booking-calendar').fullCalendar({
    header: {
    center: 'title'
    },
            //defaultDate: '2014-11-12', 
            editable: false,
            eventLimit: false, // allow "more" link when too many events
            events: event_list,
            eventRender: function(event, element) {
            element.attr('data-bs-toggle', 'tooltip');
            element.attr('data-placement', 'bottom');
            element.attr('title', event.tip);
            element.attr('id', event.optid);
            element.find('.fc-time').attr('class', "hide");
            element.attr('class', event.add_class + ' fc-day-grid-event fc-event fc-start fc-end');
            element.attr('href', event.href);
            element.attr('target', '_blank');
            element.css({'font-size':'10px', 'padding':'1px'});
            if (event.prepend_element) {
            element.prepend(event.prepend_element);
            }
            },
            eventDrop : function (event, delta) {
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
    function focus_date(date)
    {
    $('#booking-calendar').fullCalendar('gotoDate', date);
    }
    });
</script>

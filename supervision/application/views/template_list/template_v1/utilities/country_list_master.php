<div id="package_types" class="bodyContent col-md-12">
    <div class="panel card">
        <!-- PANEL WRAP START -->
        <div class="card-header">
            <!-- PANEL HEAD START -->
            <div class="card-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="active">
                        <a href="#fromList"
                            aria-controls="home" role="tab" data-bs-toggle="tab">
                            <h1>View
                                Airport lists
                            </h1>
                        </a>
                    </li>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="fromList">
                    <div class="col-md-12 nopad">
                        <div class='row'>
                            <div class='col-sm-12 nopad'>
                                <div class='' style='margin-bottom: 0;'>
                                    <div class=' '>
                                        <div class='actions'>
                                            <a href="<?php echo base_url(); ?>management/add_airport_master">
                                                <button class='btn btn-primary' style='margin-bottom: 5px'>
                                                    + Add Airport
                                                </button>
                                            </a> <a href="#"><i> &nbsp</i></a>
                                            <input type="text" id="srch_inpt" onkeyup="srchFunctn()" placeholder="Search...">
                                        </div>
                                    </div>
                                    <?php if (isset($status)) {
                                        echo $status;
                                    } ?>

                                    <div class='responsive-table' id="srch_table">
                                        <div class=''>
                                            <div class='scrollable-area'>
                                                <table
                                                    class=' table-striped external'
                                                    style='margin-bottom: 0; width:100%;'>
                                                    <thead>
                                                        <tr>
                                                            <th>S.no</th>
                                                            <th>Code</th>
                                                            <th>Name</th>
                                                            <th>City</th>
                                                            <th>Country</th>
                                                            <th>lat & long</th>
                                                            <th>TimeZone</th>
                                                            <th>International</th>
                                                            <th>Is Traveller</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ($flight_airport_lists['status']) {
                                                            $count = 1;
                                                        ?>

                                                            <?php foreach ($flight_airport_lists['data'] as $flight_airport_list) { ?>
                                                                <tr>
                                                                    <td><?php echo $count; ?></td>
                                                                    <td><?= $flight_airport_list['airport_code'] ?></td>
                                                                    <td><?= $flight_airport_list['airport_name'] ?></td>
                                                                    <td><?= $flight_airport_list['airport_city'] ?></td>
                                                                    <td><?= $flight_airport_list['country'] ?> (<?= $flight_airport_list['CountryCode'] ?>)</td>
                                                                    <td><?= $flight_airport_list['lat'] . ' , ' . $flight_airport_list['lon'] ?></td>
                                                                    <td><?= $flight_airport_list['timezonename'] ?></td>
                                                                    <td>
                                                                        <select
                                                                            onchange="activate(this.value);">

                                                                            <option
                                                                                value="<?php echo base_url(); ?>management/update_airport_international_status/<?php
                                                                                                                                                                echo $flight_airport_list['origin'];
                                                                                                                                                                ?>/1"
                                                                                <?php if ($flight_airport_list['IsInternational'] == 1) { ?>selected <?php }  ?>>Yes</option>
                                                                            <option
                                                                                value="<?php echo base_url(); ?>management/update_airport_international_status/<?php
                                                                                                                                                                echo $flight_airport_list['origin'];
                                                                                                                                                                ?>/0" <?php if ($flight_airport_list['IsInternational'] == 0) { ?>selected <?php }  ?>>No</option>

                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select
                                                                            onchange="activate(this.value);">

                                                                            <option
                                                                                value="<?php echo base_url(); ?>management/update_airport_traveller_status/<?php
                                                                                                                                                            echo $flight_airport_list['origin'];
                                                                                                                                                            ?>/1"
                                                                                <?php if ($flight_airport_list['isTravellerAirport'] == 1) { ?>selected <?php }  ?>>Yes</option>
                                                                            <option
                                                                                value="<?php echo base_url(); ?>management/update_airport_traveller_status/<?php
                                                                                                                                                            echo $flight_airport_list['origin'];
                                                                                                                                                            ?>/0" <?php if ($flight_airport_list['isTravellerAirport'] == 0) { ?>selected <?php }  ?>>No</option>

                                                                        </select>
                                                                    </td>

                                                                    <td>
                                                                        <select
                                                                            onchange="activate(this.value);">

                                                                            <option
                                                                                value="<?php echo base_url(); ?>management/update_airport_status/<?php
                                                                                                                                                    echo $flight_airport_list['origin'];
                                                                                                                                                    ?>/1"
                                                                                <?php if ($flight_airport_list['status'] == 1) { ?>selected <?php }  ?>>Active</option>
                                                                            <option
                                                                                value="<?php echo base_url(); ?>management/update_airport_status/<?php
                                                                                                                                                    echo $flight_airport_list['origin'];
                                                                                                                                                    ?>/0" <?php if ($flight_airport_list['status'] == 0) { ?>selected <?php }  ?>>Inactive</option>

                                                                        </select>
                                                                    </td>


                                                                    <td>
                                                                        <div class="dropdown actn_drpdwn">
                                                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                            </button>
                                                                            <ul class="dropdown-menu actn_menu_drpdwn" aria-labelledby="dropdownMenu1">
                                                                                <a class="btn-success" data-placement="top" title=""
                                                                                    href="<?php echo base_url(); ?>management/edit_airport_master/<?php echo $flight_airport_list['origin']; ?>"
                                                                                    data-original-title="Edit Airport">
                                                                                    <i class="glyphicon glyphicon-pencil"></i> Edit Airport
                                                                                </a>
                                                                            </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php $count++;
                                                            } ?>
                                                        <?php } ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="">
                                                <?php echo $this->pagination->create_links(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PANEL BODY END -->
</div>
<!-- PANEL WRAP END -->
</div>
<script type="text/javascript">
    function activate(that) {
        window.location.href = that;
    }
</script>
<style>
    .external>tbody>tr>td,
    .external>tbody>tr>th,
    .external>tfoot>tr>td,
    .external>tfoot>tr>th,
    .external>thead>tr>td,
    .external>thead>tr>th {
        padding: 6px;
    }
</style>
<script>
    function srchFunctn() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("srch_inpt");
        filter = input.value.toUpperCase();
        table = document.getElementById("srch_table");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            if (td.length > 0) {
                var rowMatches = false;
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            rowMatches = true;
                            break;
                        }
                    }
                }
                if (rowMatches) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
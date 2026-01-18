<div id="package_types" class="bodyContent col-md-12">
<h4 class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    View Airport Lists
    <div class="d-flex gap-2 align-items-center">
        <a href="<?php echo base_url(); ?>management/add_airport_master" class="btn btn-gradient-primary btn-sm"><i class="bi bi-plus-circle"></i> Add Airport</a>
        <input type="text" id="srch_inpt" class="form-control form-control-sm" onkeyup="srchFunctn()" placeholder="Search..." style="max-width: 200px;">
    </div>
</h4>
    <div class="panel card clearfix">
        <div class="p-0">
            <?php if (isset($status)) { echo $status; } ?>
            <div class="clearfix table-responsive reprt_tble" id="srch_table">
                <table class="table table-sm table-bordered example3 external">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash"></i> S.no</th>
                            <th><i class="bi bi-upc"></i> Code</th>
                            <th><i class="bi bi-building"></i> Name</th>
                            <th><i class="bi bi-geo-alt"></i> City</th>
                            <th><i class="bi bi-globe"></i> Country</th>
                            <th><i class="bi bi-geo"></i> lat & long</th>
                            <th><i class="bi bi-clock"></i> TimeZone</th>
                            <th><i class="bi bi-airplane"></i> International</th>
                            <th><i class="bi bi-person"></i> Is Traveller</th>
                            <th><i class="bi bi-toggle-on"></i> Status</th>
                            <th><i class="bi bi-gear"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($flight_airport_lists['status'] && valid_array($flight_airport_lists['data'])) {
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
                                                                        <a href="<?php echo base_url(); ?>management/edit_airport_master/<?php echo $flight_airport_list['origin']; ?>" class="btn btn-sm btn-info" title="Edit Airport"><i class="bi bi-pencil"></i> Edit</a>
                                                                    </td>
                                                                </tr>
                                                            <?php $count++;
                                                            } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="11" class="text-center no-data-found">
                                                                    <div class="empty-state">
                                                                        <i class="bi bi-inbox" aria-hidden="true"></i>
                                                                        <h4>No Data Found</h4>
                                                                        <p>No airport records found.</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3">
                <div><?php echo $this->pagination->create_links(); ?></div>
            </div>
        </div>
    </div>
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
<?php if ($this->session->flashdata('success_message')): ?>
    <div class="alert alert-success" id="success-alert">
        <?php echo $this->session->flashdata('success_message'); ?>
    </div>
    <script type="text/javascript">
        // Use JavaScript to hide the success message after 5 seconds
        setTimeout(function() {
            document.getElementById('success-alert').style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
<?php endif; ?>


<div class="bodyContent">
    <div class="panel <?= PANEL_WRAPPER ?>">
        <!-- PANEL WRAP START -->
        <div class="card-header">
            <!-- PANEL HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Top Destination Category Master List
                <a href="<?php echo base_url() ?>index.php/cms/add_top_destination_category_master_view"><button type="button" class="btn btn-warning" style="float: right;">Add Destination Category Master</button></a>
            </div>
        </div>
        <style type="text/css">
            .search-bar {
                background-color: #f9f9f9;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
                text-align: center;
                /* Center-align content */
            }

            .search-bar h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 20px;
                color: #333;
            }

            .search-bar .form-inline .form-group {
                margin-bottom: 15px;
                display: inline-block;
                vertical-align: top;
                text-align: left;
                /* Align labels to the left */
                margin-right: 15px;
            }

            .search-bar .form-group label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
                text-align: center;
            }

            .search-bar .form-control {
                width: 250px;
                display: block;
                margin: 0 auto;
            }

            .search-bar .btn {
                width: 100%;
                padding: 5px;
                margin-top: 5px;
            }
        </style>
     
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <div class="">
                <?php echo $this->pagination->create_links(); ?> <span class="" style="vertical-align:top;">Total <?php echo $total_rows ?> Records</span>
            </div>
            <div class="card-body">
                <table id="blogTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Module</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($category_master_data as $category): ?>
                            <tr>
                                <td>
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <?php
                                       echo $category['category_name'];
                                    ?>
                                </td>
                                <td>
                                    <?php echo $category['module']; ?>
                                </td>
                                
                                <td>
                                    <a href="<?php echo site_url('cms/add_top_destination_category_master_view/' . $category['origin']); ?>" class="btn btn-primary">Edit</a>
                                    <a href="<?php echo site_url('cms/delete_top_destination_category_master_view/' . $category['origin']); ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>

</html>

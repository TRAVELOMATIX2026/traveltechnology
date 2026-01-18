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
    <div class="panel <?=PANEL_WRAPPER?>">
        <!-- PANEL WRAP START -->
        <div class="card-header">
            <!-- PANEL HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Category Master
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="card-body">
            <!-- PANEL BODY START -->
            <form method="POST" action="<?php echo base_url()?>index.php/cms/add_top_destination_category_master">
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Module</label>
                    <div class="col-sm-6">
                        <select name="module" class="form-control" required id="module">
                            <option value="">Please Select</option>
                            <?php
                                $selected = ('flight' == $single_category_master_data['data'][0]['module']) ? 'selected' : '';
                            ?>
                               <option value="flight" <?= $selected ?>>Flight</option>
                               <?php
                                $selected = ('hotel' == $single_category_master_data['data'][0]['module']) ? 'selected' : '';
                            ?>
                               <option value="hotel" <?= $selected ?>>Hotel</option>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
           
                <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <label class="col-sm-2 col-form-label text-end">Category Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="category_name" class="form-control" placeholder="Category Name" required value="<?php echo $single_category_master_data['data'][0]['category_name'];?>">
                        <input type="hidden" name="category_hidden_origin" value="<?php echo $single_category_master_data['data'][0]['origin'];?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
             
                <div class="form-group row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-6">
                        <input type="submit" name="submit" value="Submit" class="btn btn-success">
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </form>
        </div>

    </div>
</div>
</body>
</html>

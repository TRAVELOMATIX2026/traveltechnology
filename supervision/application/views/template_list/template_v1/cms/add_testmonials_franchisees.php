<div class="bodyContent col-md-12">
    <div class="panel card clearfix">
        <div class="card-header">
            <div class="card-title">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="">Add Testmonials Franchisees</li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="tab-content">
                <div role="tabpanel" class="clearfix tab-pane active" id="fromList">
                    <div class="col-md-12">
                        <div class="panel card border-info clearfix">
                            <div class="col-md-12">
                                <form class="row" role="form" enctype="multipart/form-data" method="POST" action="<?= base_url(); ?>cms/save_testmonial_franchisees" autocomplete="off">
                                    
                                    <div class="form-group"></div>
                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Title <span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="title" class="form-control" required placeholder="Enter title">
                                        </div>
                                    </div>  

                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Sub Title <span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="sub_title" class="form-control" required placeholder="Enter Sub title">
                                        </div>
                                    </div>      
                                    
                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Designation<span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="designation" class="form-control" required placeholder="Enter Designation">
                                        </div>
                                    </div>   

                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Image</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Thumbnail Image</label>
                                        <div class="col-sm-4">
                                            <input type="file" name="thumbnail_image" class="form-control" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Thumbnail URL<span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="thumbnail_url" class="form-control" required placeholder="Enter Thumbnail URL">
                                        </div>
                                    </div>  

                                    <?php
                                    $status = isset($form_data['status']) ? $form_data['status'] : 0;
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 form-label">Status <span class="text-danger">*</span></label>
                                        <div class="col-sm-4">
                                            <label class="form-check form-check-inline">
                                                <input required type="radio" name="status" value="1" <?= ($status == 1) ? 'checked' : '' ?>> Active
                                            </label>
                                            <label class="form-check form-check-inline">
                                                <input required type="radio" name="status" value="0" <?= ($status == 0) ? 'checked' : '' ?>> Inactive
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-8 offset-sm-4">
                                            <button class="btn btn-success" type="submit">Submit</button>
                                            <button class="btn btn-warning" type="reset">Reset</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

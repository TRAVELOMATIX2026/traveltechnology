<div class="bodyContent col-md-12">
    <div class="panel card mb-2">
        <div class="card-header">
            <div class="card-title">
                <h4>
                    <?= ($ID > 0) ? 'Edit FAQ' : 'Add FAQ'; ?>
                </h4>
            </div>
        </div>
        <div class="card-body">
            <div class="container mt-4">


                <form method="post" action="<?= base_url('index.php/cms/faqs'); ?>">

                    <!-- CATEGORY DROPDOWN -->
                    <div class="form-group row mb-2">
                        <label class="col-md-2 col-form-label text-end">FAQ Category : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select name="module_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php if (!empty($categories)) { ?>
                                <?php foreach ($categories as $cat) { ?>
                                <option value="<?= $cat['origin']; ?>" <?=(isset($module_id) &&
                                    $module_id==$cat['origin']) ? 'selected' : '' ; ?>>
                                    <?= $cat['name']; ?>
                                </option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <?= form_error('module_id'); ?>
                    </div>

                    <!-- QUESTION -->
                    <div class="form-group row mb-2">
                        <label class="col-md-2 col-form-label text-end">Question : <span
                                class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <textarea name="faq" class="form-control" rows="3"
                                required><?= isset($faq) ? $faq : ''; ?></textarea>
                        </div>
                        <?= form_error('faq'); ?>
                    </div>

                    <!-- ANSWER -->
                    <div class="form-group row mb-2">
                        <label class="col-md-2 col-form-label text-end">Answer :<span
                                class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <textarea name="solution" class="form-control" rows="5"
                                required><?= isset($solution) ? $solution : ''; ?></textarea>
                        </div>
                        <?= form_error('solution'); ?>

                    </div>

                    <!-- STATUS -->
                    <div class="form-group row mb-2">
                        <label class="col-md-2 col-form-label text-end">Status :</label>
                        <div class="col-md-9">
                            <select name="status" class="form-control">
                                <option value="1" <?=(isset($status) && $status==1) ? 'selected' : '' ; ?>>Active
                                </option>
                                <option value="0" <?=(isset($status) && $status==0) ? 'selected' : '' ; ?>>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div class="form-group row mb-2">
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary float-end">
                                <?= ($ID > 0) ? 'Update FAQ' : 'Add FAQ'; ?>
                            </button>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
    <div class="panel card">
        <div class="card-header">
            <div class="card-title">
                <h4>FAQs List</h4>
            </div>
        </div>
        <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive" id="srch_table" style="margin-top: 40px;">
                            <table class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Module</th>
                                        <th>FAQ</th>
                                        <th>Solution</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($faq_list)):
                                                $count = $offset + 1;
                                                foreach ($faq_list as $row): ?>
                                    <tr>
                                        <td>
                                            <?= $count++; ?>
                                        </td>
                                        <td>
                                            <?= ($row['category_name']); ?>
                                        </td>
                                        <td>
                                            <?= ($row['faq']); ?>
                                        </td>
                                        <td>
                                            <?= ($row['solution']); ?>
                                        </td>

                                        <td>
                                            <select onchange="changeStatus(this.value)">
                                                <option value="<?= base_url("
                                                    cms/update_collaboration_status/{$row['id']}/1") ?>"
                                                    <?= ($row['status'] == 1) ? 'selected' : '' ?>>Active
                                                </option>
                                                <option value="<?= base_url("
                                                    cms/update_collaboration_status/{$row['id']}/0") ?>"
                                                    <?= ($row['status'] == 0) ? 'selected' : '' ?>>Inactive
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="dropdown actn_drpdwn">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu actn_menu_drpdwn">
                                                    <li>
                                                        <a href="<?= base_url(" cms/edit_faq/{$row['id']}"); ?>"
                                                            class="btn-success" title="View/Edit">
                                                            <i class="glyphicon glyphicon-pencil"></i> View/Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?= base_url(" cms/delete_faq/{$row['id']}"); ?>"
                                                            class="btn-danger" title="Delete" onclick="return
                                                            confirm('Are
                                                            you sure you want to delete this FAQ?');">
                                                            <i class="glyphicon glyphicon-trash"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No records found.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <?php if (!empty($pagination_links)): ?>
                            <div class="text-center" style="margin-top: 20px;">
                                <?= $pagination_links; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
        </div>
    </div>
</div>

<script>
    function changeStatus(url) {
        if (url) {
            window.location.href = url;
        }
    }
</script>
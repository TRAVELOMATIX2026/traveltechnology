<div class="bodyContent col-md-12">
    <div class="panel card clearfix">
        <div class="card-header">
            <div class="card-title">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="">Collaborations List</li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div role="tabpanel" class="clearfix tab-pane active" id="fromList">
                    <div class="col-md-12">
                        <div class="panel card border-info clearfix">
                            <div class="col-md-12">
                                <div class="responsive-table" id="srch_table" style="margin-top: 40px;">
                                    <table class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Sub Title</th>
                                                <th>Designation</th>
                                                <th>Thumbnail Image</th>
                                                <th>Thumbnail URL</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($collaboration_list)):
                                                $count = $offset + 1;
                                                foreach ($collaboration_list as $collaboration): ?>
                                                <tr>
                                                    <td><?= $count++; ?></td>
                                                    <td>
                                                        <?php if (!empty($collaboration['image'])): ?>
                                                            <img src="<?= $GLOBALS['CI']->template->domain_images($collaboration['image']); ?>" alt="Image" style="height: 50px; width: 50px;" class="img-thumbnail">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= ($collaboration['title']); ?></td>
                                                    <td><?= ($collaboration['sub_title']); ?></td>
                                                    <td><?= ($collaboration['designation']); ?></td>
                                                    <td>
                                                        <?php if (!empty($collaboration['thumbnail_image'])): ?>
                                                            <img src="<?= $GLOBALS['CI']->template->domain_images($collaboration['thumbnail_image']); ?>" alt="Thumbnail" style="height: 50px; width: 50px;" class="img-thumbnail">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= ($collaboration['thumbnail_url']); ?></td>
                                                    <td>
                                                        <select onchange="changeStatus(this.value)">
                                                            <option value="<?= base_url("cms/update_collaboration_status/{$collaboration['id']}/1") ?>" <?= ($collaboration['status'] == 1) ? 'selected' : '' ?>>Active</option>
                                                            <option value="<?= base_url("cms/update_collaboration_status/{$collaboration['id']}/0") ?>" <?= ($collaboration['status'] == 0) ? 'selected' : '' ?>>Inactive</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown actn_drpdwn">
                                                            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu actn_menu_drpdwn">
                                                                <li>
                                                                    <a href="<?= base_url("cms/edit_collaborations/{$collaboration['id']}"); ?>" class="btn-success" title="View/Edit">
                                                                        <i class="glyphicon glyphicon-pencil"></i> View/Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="<?= base_url("cms/delete_collaboration/{$collaboration['id']}"); ?>" class="btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this Collaboration?');">
                                                                        <i class="glyphicon glyphicon-trash"></i> Delete
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                                <tr><td colspan="9" class="text-center">No records found.</td></tr>
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
            </div>
        </div>
    </div>
</div>

<script>
function changeStatus(url) {
    if(url) {
        window.location.href = url;
    }
}
</script>

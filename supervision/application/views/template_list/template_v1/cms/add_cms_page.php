<!-- <script src="<?php //echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script> -->
<!--Include the JS & CSS-->
<link rel="stylesheet" href="<?php echo SYSTEM_RESOURCE_LIBRARY?>/summernote/dist/summernote.css" />
<script type="text/javascript" src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/summernote/dist/summernote.min.js"></script>
<?php
//get sub admin table
$table = '<table class="table table-sm table-bordered example3" id="cms_pages_table">
				<thead>
				<tr>
					<th><i class="bi bi-type"></i> Page Title</th>
					<th><i class="bi bi-tag"></i> Page SEO Title</th>
					<th><i class="bi bi-key"></i> Page SEO Keyword</th>
					<th><i class="bi bi-text-paragraph"></i> Page SEO Description</th>
					<th><i class="bi bi-arrow-up-down"></i> Page Position</th>
					<th><i class="bi bi-toggle-on"></i> Status</th>
					<th><i class="bi bi-gear"></i> Action</th>
				</tr>
				</thead>
				<tbody>';
	if (valid_array($sub_admin) == true) {
		foreach ($sub_admin as $key => $value) {
			$action = '<a role="button" href="'.base_url().'index.php/cms/add_cms_page/'.$value['page_id'].'" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Edit</a>';
			$delete_action = '<a role="button" href="'.base_url().'index.php/cms/delete_cms_page/'.$value['page_id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this CMS page?\');"><i class="bi bi-trash"></i> Delete</a>';
			if (intval($value['page_status']) == 1) {
				$status_label = '<span class="badge bg-success">ACTIVE</span>';
				$status_button = '<a role="button" href="'.base_url().'index.php/cms/cms_status/'.$value['page_id'].'/D" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Deactivate</a>'; 
			} else {
				$status_label = '<span class="badge bg-danger">INACTIVE</span>';
				$status_button = '<a role="button" href="'.base_url().'index.php/cms/cms_status/'.$value['page_id'].'/A" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Activate</a>';
			} 
			$table .= '<tr>
				<td>'.$value['page_title'].'</td>
				<td>'.$value['page_seo_title'].'</td>
				<td>'.$value['page_seo_keyword'].'</td>
				<td>'.$value['page_seo_description'].'</td>
				<td>'.$value['page_position'].'</td>
				<td>'.$status_label.'</td>
				<td>
					<div class="d-flex gap-2">
						'.$action.'
						'.$delete_action.'
						'.$status_button.'
					</div>
				</td>
			</tr>';
		}
	} else {
		$table .= '<tr>
			<td colspan="7" class="text-center no-data-found">
				<div class="empty-state">
					<i class="bi bi-inbox" aria-hidden="true"></i>
					<h4>No Data Found</h4>
					<p>No CMS pages found in the system. Create your first CMS page using the form above.</p>
				</div>
			</td>
		</tr>';
	}
$table .= '</tbody></table>';
//end of table section
?>
<div class="bodyContent col-md-12">
<h4 class="mb-3">Static Page Content</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="card-body p-0">

            <form method="post" autocomplete="off" action="<?php echo base_url();?>index.php/cms/add_cms_page/<?php echo $ID;?>" id="profile_form" class="p-4">

<div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label>
                                Page Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="page_title" value="<?php echo isset($page_title) ? $page_title : '';?>">
                            <font color="red" ><?= @form_error('page_title') ?> </font>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Page SEO Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="page_seo_title" value="<?php echo isset($page_seo_title) ? $page_seo_title : '';?>">
                            <font color="red" ><?= @form_error('page_seo_title') ?> </font>
                        </div>

                        <div class="col-sm-12">
                            <label>
                                Page Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="summernote form-control" name="page_description" rows="10" cols="80"><?php echo isset($page_description) ? $page_description : '';?></textarea>
                            <font color="red" ><?= @form_error('page_description') ?> </font>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Page SEO Keyword <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="page_seo_keyword" value="<?php echo isset($page_seo_keyword) ? $page_seo_keyword : '';?>">
                            <font color="red" ><?= @form_error('page_seo_keyword') ?> </font>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Page SEO Description <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="page_seo_description" value="<?php echo isset($page_seo_description) ? $page_seo_description : '';?>">
                            <font color="red" ><?= @form_error('page_seo_description') ?> </font>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Page Position <span class="text-danger">*</span>
                            </label>
                            <select name="page_position" class="form-control">
                                <option value="">Select</option>
                                <option value="Top" <?php if(isset($page_position)){ if($page_position == 'Top'){ echo 'selected="selected"'; }}?>>Top</option>
                                <option value="Bottom" <?php if(isset($page_position)){ if($page_position == 'Bottom'){ echo 'selected="selected"'; }}?>>Bottom</option>
                                <option value="Both" <?php if(isset($page_position)){ if($page_position == 'Both'){ echo 'selected="selected"'; }}?>>Both</option>
                            </select>
                            <font color="red" ><?= @form_error('page_position') ?> </font>
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <button type="submit" class="btn btn-gradient-success"><i class="bi bi-check-circle"></i> Submit</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>

    </div>
</div>
<div class="bodyContent col-md-12">
<h4 class="mb-3">CMS Page List</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive reprt_tble">

                <?php 
                echo $table;
                ?>

            </div>
        </div>

    </div>
</div>
<script>
	$(document).ready(function(){
		$('.summernote').summernote({
			height: 250, // set editor height
			minHeight: null, // set minimum height of editor
			maxHeight: null, // set maximum height of editor
			focus: true // set focus to editable area after initializing summernote
		});
	});
</script>
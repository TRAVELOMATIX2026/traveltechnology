<div class="bodyContent col-md-12">
<h4 class="mb-3">SEO</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive reprt_tble">

                <table class="table table-sm table-bordered example3" id="seo_table">
                <thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sl no</th>
					<th><i class="bi bi-type"></i> Title</th>
					<th><i class="bi bi-key"></i> Keyword</th>
					<th><i class="bi bi-text-paragraph"></i> Description</th>
					<th><i class="bi bi-grid"></i> Module</th>
					<th><i class="bi bi-gear"></i> Action</th>
				</tr>
                </thead>
                <tbody>
				<?php
				$data_list = $data_list['data'];
				// debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
						$action = '<a role="button" href="'.base_url().'index.php/cms/edit_seo/'.$v['id'].'"><button class="btn btn-sm">Edit</button></a>';
				?>
					<tr>
						<td><?= ($k+1) ?></td>
						<td><?= $v['title'] ?></td>
						<td><?= $v['keyword'] ?></td>
						<td><?= $v['description'] ?></td>
						<td><?= $v['module'] ?></td>
						<td>
							<a role="button" href="<?= base_url().'index.php/cms/edit_seo/'.$v['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Edit</a>
						</td>
					</tr>
				<?php
					endforeach;
				} else {
					?>
					<tr>
						<td colspan="6" class="text-center no-data-found">
							<div class="empty-state">
								<i class="bi bi-inbox" aria-hidden="true"></i>
								<h4>No Data Found</h4>
								<p>No SEO records found in the system.</p>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			</table>
        </div>
        </div>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center">
<div class="float-start my-3">
<?php echo $this->pagination->create_links(); ?> <span class="totl_bkngs_flt">Total <?php echo $total_rows ?> Records</span>
</div>
</div>
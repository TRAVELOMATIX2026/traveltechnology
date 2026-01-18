<div class="bodyContent col-md-12">
<h4 class="mb-3">Top Destinations In Flight</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="card-body p-0">

            <form action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="POST" autocomplete="off" class="p-0">
            <span style="color:#dd4b39"><?php if(isset($message)){ echo $message; } ?></span>

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label>
                                From Airport <span class="text-danger">*</span>
                            </label>
                            <select name="from_airport" class="form-control" required="">
                                <option value="INVALIDIP">Please Select</option>
                                <?php
                                foreach ($flight_list2['data'] as $flight) {
                                    echo '<option value="' . htmlspecialchars($flight['origin']) . '">'
                                        . htmlspecialchars($flight['airport_city']) . ' (' . htmlspecialchars($flight['airport_code']) . ')'
                                        . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                To Airport <span class="text-danger">*</span>
                            </label>
                            <select name="to_airport" class="form-control" required="">
                                <option value="INVALIDIP">Please Select</option>
                                <?php
                                foreach ($flight_list2['data'] as $flight) {
                                    echo '<option value="' . htmlspecialchars($flight['origin']) . '">'
                                        . htmlspecialchars($flight['airport_city']) . ' (' . htmlspecialchars($flight['airport_code']) . ')'
                                        . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Image <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control" accept="image/*" required="required" name="top_destination">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label>
                                Description <span class="text-danger">*</span>
                            </label>
                            <textarea required="required" name="alt" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <button class="btn btn-gradient-success" type="submit"><i class="bi bi-plus-circle"></i> Add</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>

    </div>
</div>

<div class="bodyContent col-md-12">
<h4 class="mb-3">Flight Top Destinations List</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="clearfix table-responsive reprt_tble">

                <table class="table table-sm table-bordered example3" id="flight_top_destinations_table">
                <thead>
				<tr>
					<th><i class="bi bi-hash"></i> Sno</th>
					<th><i class="bi bi-geo-alt"></i> From City</th>
					<th><i class="bi bi-geo-alt-fill"></i> To City</th>
                    <th><i class="bi bi-text-paragraph"></i> Description</th>
					<th><i class="bi bi-image"></i> Image</th>
					<th><i class="bi bi-gear"></i> Action</th>
				</tr>
                </thead>
                <tbody>
				<?php
				// debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?= ($k+1) ?></td>
						<td><?= $v['from_airport_name'] ?></td>
						<td><?= $v['to_airport_name'] ?></td>
                        <td><?= $v['alt'] ?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td>
							<div class="d-flex gap-2">
								<?php 
								$status_label = (intval($v['status']) == ACTIVE) ? '<span class="badge bg-success">ACTIVE</span>' : '<span class="badge bg-danger">INACTIVE</span>';
								echo $status_label;
								if (intval($v['status']) == ACTIVE) {
									echo '<a role="button" href="'.base_url().'index.php/cms/deactivate_flight_top_destination/'.$v['origin'].'" class="btn btn-sm btn-warning"><i class="bi bi-x-circle"></i> Deactivate</a>';
								} else {
									echo '<a role="button" href="'.base_url().'index.php/cms/activate_flight_top_destination/'.$v['origin'].'" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Activate</a>';
								}
								echo '<a role="button" href="'.base_url().'index.php/cms/delete_flight_top_destination/'.$v['origin'].'/'.$v['image'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this destination?\');"><i class="bi bi-trash"></i> Delete</a>';
								?>
							</div>
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
								<p>No flight top destinations found. Add your first destination using the form above.</p>
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

<script src="/share_test/extras/system/library/javascript/jquery-ui.min.js"></script>
<script type="text/javascript">
    $.widget("custom.catcomplete", $.ui.autocomplete, {
    _create: function() { this._super(), this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)") },
    _renderMenu: function(t, e) {
        var r = this,
            a = "";
        $.each(e, function(e, o) {
            var n;
           o.category != a && (t.append("<li class='ui-autocomplete-category'>" + o.category + "</li>"), a = o.category), n = r._renderItemData(t, o), o.category && n.attr("aria-label", o.category + " : " + o.label)
        })
    }
});
    var cache = {};
    var from_airport = $('#from').val();
    var to_airport = $('#to').val();
 $(".fromflight, .departflight").catcomplete({
        open: function(event, ui) {
        $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
    },
        source: function(request, response) {
            var term = request.term;
            if (term in cache) {
                response(cache[term]);
                return
            } else {
                $.getJSON(app_base_url + "index.php/flight/get_airport_code_list", request, function(data, status, xhr) {
                    if ($.isEmptyObject(data) == true && $.isEmptyObject(cache[""]) == false) {
                        data = cache[""]
                    } else {
                        cache[term] = data;
                        response(cache[term])
                    }
                })
            }
        },
        minLength: 0,
        autoFocus: false,
        select: function(event, ui) {
            var label = ui.item.label;
            var category = ui.item.category;
            if (this.id == 'to') {
                to_airport = ui.item.value
            } else if (this.id == 'from') {
                from_airport = ui.item.value
            }
            $(this).siblings('.loc_id_holder').val(ui.item.id);
            auto_focus_input(this.id)
            //For Multicity-To autofill the next departure city
            if($(this).hasClass('m_arrcity') == true && ui.item.value !='') {
            	var next_depcity_id = $(this).closest('.multi_city_container').next('.multi_city_container').find('.m_depcity').attr('id');
            	if($('#'+next_depcity_id).val() == '') {
	            	$('#'+next_depcity_id).val(ui.item.value);
	            	$('#'+next_depcity_id).siblings('.loc_id_holder').val(ui.item.id);
            	}
            }
        },
        change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
    }).bind('focus', function() {
        $(this).catcomplete("search")
    }).catcomplete("instance")._renderItem = function(ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        var top = 'Top Searches';
        return $("<li class='custom-auto-complete'>").append('<a><img class="flag_image" src="' + '">' + auto_suggest_value + '</a>').appendTo(ul)
    };
</script>

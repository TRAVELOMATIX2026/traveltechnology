<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<div class="bodyContent col-md-12">
<h4 class="mb-3">Dynamic Hotel URL's</h4>
    <div class="panel card clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <form method="POST" action="<?php echo base_url() ?>index.php/blog/add_dynamic_hotel_url" enctype="multipart/form-data" class="p-4">

                    <div class="form-group row gap-0 mb-0">
                        <div class="col-sm-12 col-md-6">
                            <label for="cityInput">City</label>
                            <input type="text" id="cityInput" name="city" class="form-control" placeholder="Type to search for a city" required>
                            <input type="hidden" id="cityOrigin" name="origin" />
                            <input type="hidden" id="cityCountry" name="country" />
                            <div id="citySuggestions" class="suggestions" style="position: absolute; background: white; border: 1px solid #ccc; display: none; z-index: 1000;"></div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label for="seoTitle">SEO Title</label>
                            <input type="text" name="seo_title" id="seoTitle" class="form-control" placeholder="SEO Title">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label for="seoKeywords">SEO Keywords</label>
                            <input type="text" name="seo_keywords" id="seoKeywords" class="form-control" placeholder="SEO Keywords" autocorrect="off" autocomplete="off" autocapitalize="off">
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <label for="seoDescription">SEO Description</label>
                            <textarea name="seo_description" id="seoDescription" class="form-control" placeholder="SEO Description" rows="4"></textarea>
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex justify-content-end gap-3 mt-3">
                                <button type="submit" name="submit" class="btn btn-gradient-success"><i class="bi bi-check-circle"></i> Submit</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>

    </div>
</div>

<div class="bodyContent col-md-12">
<h4 class="mb-3">Dynamic Hotel URL's List</h4>
    <div class="clearfix"><!-- PANEL WRAP START -->

        <div class="p-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="float-start">
                    <?php echo $this->pagination->create_links(); ?> <span class="totl_bkngs_flt">Total <?php echo $total_rows ?> Records</span>
                </div>
            </div>

            <div class="clearfix table-responsive reprt_tble">

                <table id="blogTable" class="table table-sm table-bordered example3">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash"></i> Sno</th>
                            <th><i class="bi bi-geo-alt"></i> City</th>
                            <th><i class="bi bi-globe"></i> Country</th>
                            <th><i class="bi bi-building"></i> Hotel Destination</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (valid_array($blogs)) {
                            $offset++; 
                            foreach ($blogs as $blog): 
                        ?>
                            <tr>
                                <td><?= ($offset++) ?></td>
                                <td><?php echo $blog['city']; ?></td>
                                <td><?php echo $blog['country']; ?></td>
                                <td><?php echo $blog['hotel_destination']; ?></td>
                            </tr>
                        <?php 
                            endforeach; 
                        } else {
                            ?>
                            <tr>
                                <td colspan="4" class="text-center no-data-found">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox" aria-hidden="true"></i>
                                        <h4>No Data Found</h4>
                                        <p>No dynamic hotel URLs found. Add your first URL using the form above.</p>
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

<script>
    const cityInput = document.getElementById('cityInput');
    const cityOriginInput = document.getElementById('cityOrigin'); // Hidden input for origin
    const cityCountryInput = document.getElementById('cityCountry'); // Hidden input for country
    const suggestionsBox = document.getElementById('citySuggestions');

    cityInput.addEventListener('input', function() {
        const query = this.value;
        if (query.length < 1) {
            suggestionsBox.style.display = 'none'; // Hide suggestions if input is empty
            return;
        }

        // Make an AJAX call to fetch matching cities
        fetch(`<?php echo base_url(); ?>index.php/blog/get_cities?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = ''; // Clear previous suggestions
                if (data.length > 0) {
                    data.forEach(city => {
                        const option = document.createElement('div');
                        option.textContent = `${city.city_name} (${city.country_name})`;
                        option.setAttribute('data-origin', city.origin); // Store origin in a data attribute
                        option.setAttribute('data-country', city.country_name); // Store country in a data attribute
                        option.className = 'suggestion-item';
                        option.onclick = function() {
                            cityInput.value = city.city_name; // Set input value to selected city
                            cityOriginInput.value = city.origin; // Set hidden input value to origin
                            cityCountryInput.value = city.country_name; // Set hidden input value to country
                            suggestionsBox.style.display = 'none'; // Hide suggestions
                        };
                        suggestionsBox.appendChild(option);
                    });
                    suggestionsBox.style.display = 'block'; // Show suggestions
                } else {
                    suggestionsBox.style.display = 'none'; // Hide if no matches
                }
            })
            .catch(error => console.error('Error fetching cities:', error));
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!suggestionsBox.contains(event.target) && event.target !== cityInput) {
            suggestionsBox.style.display = 'none';
        }
    });

    document.getElementById('seoKeywords').addEventListener('input', function() {
    // Strip out hidden characters or any special formatting
    this.value = this.value.replace(/[^\x20-\x7E]/g, ''); // Keeps only visible characters
});
</script>




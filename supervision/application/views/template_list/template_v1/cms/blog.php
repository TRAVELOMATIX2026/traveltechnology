<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
    <style>
        .image_input {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .remove_image {
            margin-left: 10px;
        }
        .preview {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }
        .image_info {
            flex: 1;
            margin-right: 10px;
        }
        .image_info input,
        .image_info textarea {
            width: 100%;
            margin-bottom: 10px;
        }
         .thumbnail-container {
            display: flex;
            flex-wrap: nowrap; /* Ensure images stay in a single row */
            overflow-x: auto; /* Enable horizontal scrolling */
            gap: 10px; /* Space between images */
            padding-bottom: 10px; /* Bottom padding for space */
        }
        .thumbnail {
            flex: 0 0 auto; /* Prevent images from shrinking */
            max-width: 100px; /* Maximum width of each thumbnail */
            overflow: hidden; /* Hide overflow */
            position: relative; /* Positioning for caption */
        }
        .thumbnail img {
            width: 100%; /* Ensure image fills container */
            height: auto; /* Maintain aspect ratio */
            display: block; /* Remove extra space below images */
        }
        .thumbnail .caption {
            position: absolute; /* Positioning for caption */
            bottom: 0; /* Align caption at the bottom */
            left: 0; /* Align caption at the left */
            width: 100%; /* Full width for caption */
            background-color: rgba(0, 0, 0, 0.5); /* Background color for caption */
            color: #fff; /* Text color for caption */
            padding: 5px; /* Padding for caption content */
            text-align: center; /* Center align text */
            display: none; /* Hide caption by default */
        }
        .thumbnail:hover .caption {
            display: block; /* Show caption on hover */
        }
    </style>
<div class="bodyContent">
    <div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
        <div class="card-header"><!-- PANEL HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Blog
            </div>
        </div><!-- PANEL HEAD START -->
        <div class="card-body"><!-- PANEL BODY START -->
                <form method="POST" action="<?php echo base_url()?>index.php/blog/add_blog" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Blog Name</label><br/>
                        <input type="text" name="blog_name" class="" id="" placeholder="Blog Name" required>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control ckeditor" id="editor" placeholder="Title" required>
                    </div>
                    <div class="form-group">
                        <label>Sub Title</label>
                        <input type="text" name="sub_title" class="form-control" placeholder="Subtitle" required>
                    </div>
                    <div class="form-group">
                        <label>Module</label>
                        <select name="module" required>
                            <option value=" ">Please Select</option>
                            <option value="flights">Flights</option>
                            <option value="hotels">Hotels</option>
                            <option value="transfers">Transfers</option>
                            <option value="activities">Activities</option>
                            <option value="cars">Cars</option>
                            <option value="holidays">Holidays</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control ckeditor"  id="editor" placeholder="Description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Blog URL</label>
                        <input type="text" name="blog_url" class="form-control"  placeholder="Blog URL">
                    </div>
                    <div class="form-group">
                        <label>SEO Title</label>
                        <input type="text" name="seo_title" class="form-control ckeditor"  placeholder="SEO Title">
                    </div>
                    <div class="form-group">
                        <label>SEO Keywords</label>
                        <input type="text" name="seo_keywords" class="form-control" placeholder="SEO Keywords">
                    </div>
                    <div class="form-group">
                        <label>SEO Description</label>
                        <textarea name="seo_description" class="form-control ckeditor"   placeholder="SEO Description" rows="4"></textarea>
                    </div>
                    <div id="image_inputs">
                        <div class="image_input" id="image_input_0">
                            <label>Image Upload:</label>
                            <input type="file" name="image[]" multiple class="form-control-file" required>
                            <div class="image_info">
                                <input type="text" name="image_name[]" class="form-control" placeholder="Image Name">
                                <textarea name="image_description[]" class="form-control" placeholder="Image Description"></textarea>
                            </div>
                            <button type="button" class="btn btn-danger remove_image" disabled>Remove</button>
                            <div class="preview-area"></div>
                        </div>
                    </div>
                    <button type="button" id="add_image" class="btn btn-primary">Add Image</button>
                    <input type="submit" name="submit" value="Submit" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>

    <div class="bodyContent">
        <div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
        <div class="card-header"><!-- PANEL HEAD START -->
            <div class="card-title">
                <i class="fa fa-edit"></i> Blog List
            </div>
        </div><!-- PANEL HEAD START -->
        <div class="card-body"><!-- PANEL BODY START -->
<div class="card-body">
                <table id="blogTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Description</th>
                            <th>Images</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $blog): ?>
                            <tr>
                                <td><?php echo $blog['title']; ?></td>
                                <td><?php echo $blog['sub_title']; ?></td>
                                <td><?php echo htmlspecialchars(substr($blog['description'],0,100)); ?></td>
                                <td>
                                    <div class="thumbnail-container">
                                        <?php 
                                            $images = explode(',', $blog['images']);
                                            $imageNames = explode(',', $blog['image_names']);
                                            $imageDescriptions = explode(',', $blog['image_descriptions']);
                                            for ($i = 0; $i < count($images); $i++) {
                                                if (!empty($images[$i])) {
                                                    echo '<div class="thumbnail">';
                                                    echo '<img src="' . $GLOBALS ['CI']->template->domain_images ($images[$i]) . '" alt="' . $imageNames[$i] . '">';
                                                    echo '<div class="caption">';
                                                    echo '<h5>' . $imageNames[$i] . '</h5>';
                                                    echo '<p>' . $imageDescriptions[$i] . '</p>';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('blog/edit_blog/' . $blog['origin']); ?>" class="btn btn-primary">Edit</a>
                                    <a href="<?php echo site_url('blog/delete_blog/' . $blog['origin']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <script>
        var imageCount = 1;  // Counter for dynamic IDs
        
        document.getElementById("add_image").addEventListener("click", function() {
            var imageInputsDiv = document.getElementById("image_inputs");
            var newImageInput = document.createElement("div");
            newImageInput.classList.add("image_input");
            newImageInput.id = "image_input_" + imageCount;
            newImageInput.innerHTML = `
                <label>Image Upload:</label>
                <input type="file" name="image[]" multiple class="form-control-file" required>
                <div class="image_info">
                    <input type="text" name="image_name[]" class="form-control" placeholder="Image Name">
                    <textarea name="image_description[]" class="form-control" placeholder="Image Description"></textarea>
                </div>
                <button type="button" class="btn btn-danger remove_image">Remove</button>
                <div class="preview-area"></div>
            `;
            imageInputsDiv.appendChild(newImageInput);
            imageCount++;

            // Enable remove button for all existing image inputs
            var removeButtons = document.querySelectorAll(".remove_image");
            removeButtons.forEach(function(button) {
                button.disabled = false;
            });

            // Disable remove button for the first image input
            if (imageCount === 1) {
                document.getElementById("image_input_0").querySelector(".remove_image").disabled = true;
            }
        });

        // Event delegation for remove button
        document.getElementById("image_inputs").addEventListener("click", function(e) {
            if (e.target && e.target.classList.contains("remove_image")) {
                var imageInput = e.target.parentNode;
                imageInput.remove();

                // Enable remove button for all remaining image inputs
                var removeButtons = document.querySelectorAll(".remove_image");
                removeButtons.forEach(function(button) {
                    button.disabled = false;
                });

                // Disable remove button if only one image input remains
                if (document.querySelectorAll(".image_input").length === 1) {
                    document.querySelector(".image_input .remove_image").disabled = true;
                }
            }
        });

        // Preview image on selection
        document.getElementById("image_inputs").addEventListener("change", function(e) {
            if (e.target && e.target.tagName.toLowerCase() === "input" && e.target.type === "file") {
                var files = e.target.files;
                var previewArea = e.target.parentNode.querySelector(".preview-area");
                previewArea.innerHTML = "";
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (file.type.match('image.*')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var img = document.createElement("img");
                            img.src = e.target.result;
                            img.classList.add("preview");
                            previewArea.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    </script>
</body>
</html>

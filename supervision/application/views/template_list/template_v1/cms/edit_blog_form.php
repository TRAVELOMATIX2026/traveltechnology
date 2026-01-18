<!-- application/views/edit_blog_form.php -->
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>

    <style>
        .image-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .image-container img {
            max-width: 100px;
            height: auto;
            margin-right: 10px;
        }
        .image-details {
            flex: 1;
            margin-left: 10px;
        }
        .image-details input,
        .image-details textarea {
            width: 100%;
            margin-bottom: 10px;
        }
        .form-group label {
            margin-bottom: 5px;
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
                <form method="POST" action="<?php echo site_url('blog/update_blog/' . $blog['origin']); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Blog Name</label>
                        <input type="text" name="blog_name" value="<?php echo $blog['blog_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo $blog['title']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="sub_title" class="form-control" value="<?php echo $blog['sub_title']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Module</label>
                        <select name="module" required>
                            <option value=" ">Please Select</option>
                            <option value="">Please Select</option>
                            <option value="flights" <?php echo ($blog['module'] == 'flights') ? 'selected' : ''; ?>>Flights</option>
                            <option value="hotels" <?php echo ($blog['module'] == 'hotels') ? 'selected' : ''; ?>>Hotels</option>
                            <option value="transfers" <?php echo ($blog['module'] == 'transfers') ? 'selected' : ''; ?>>Transfers</option>
                            <option value="activities" <?php echo ($blog['module'] == 'activities') ? 'selected' : ''; ?>>Activities</option>
                            <option value="cars" <?php echo ($blog['module'] == 'cars') ? 'selected' : ''; ?>>Cars</option>
                            <option value="holidays" <?php echo ($blog['module'] == 'holidays') ? 'selected' : ''; ?>>Holidays</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control ckeditor"  id="editor" rows="4" required><?php echo $blog['description']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Blog URL</label>
                        <input type="text" name="blog_url" class="form-control" placeholder="Blog URL" value="<?php echo $blog['blog_url']; ?>">
                    </div>
                    <div class="form-group">
                        <label>SEO Title</label>
                        <input type="text" name="seo_title" class="form-control ckeditor" id="editor" placeholder="SEO Title" value="<?php echo $blog['seo_title']; ?>">
                    </div>
                    <div class="form-group">
                        <label>SEO Keywords</label>
                        <input type="text" name="seo_keywords" class="form-control" placeholder="SEO Keywords" value="<?php echo $blog['seo_keywords']; ?>">
                    </div>
                    <div class="form-group">
                        <label>SEO Description</label>
                        <textarea name="seo_description" class="form-control ckeditor"  id="editor" placeholder="SEO Description" rows="4"><?php echo $blog['seo_description']; ?></textarea>
                    </div>
                    <div id="image_inputs">
                        <?php 
                            $images = explode(',', $blog['images']);
                            $imageNames = explode(',', $blog['image_names']);
                            $imageDescriptions = explode(',', $blog['image_descriptions']);
                            for ($i = 0; $i < count($images); $i++) {
                                if (!empty($images[$i])) {
                                    echo '<div class="image-container">';
                                    echo '<img src="' . $GLOBALS ['CI']->template->domain_images ($images[$i]) . '" alt="' . $imageNames[$i] . '" class="img-thumbnail">';
                                    echo '<div class="image-details">';
                                    echo '<div class="form-group">';
                                    echo '<label>New Image:</label>';
                                    echo '<input type="file" name="image[' . $i . ']" class="form-control-file" multiple>';
                                    echo '</div>';
                                    echo '<input type="hidden" name="old_image[' . $i . ']" value="' . $images[$i] . '">';
                                    echo '<div class="form-group">';
                                    echo '<label>Image Name:</label>';
                                    echo '<input type="text" name="image_name[' . $i . ']" class="form-control" value="' . $imageNames[$i] . '">';
                                    echo '</div>';
                                    echo '<div class="form-group">';
                                    echo '<label>Image Description:</label>';
                                    echo '<textarea name="image_description[' . $i . ']" class="form-control">' . $imageDescriptions[$i] . '</textarea>';
                                    echo '</div>';
                                    echo '<button type="button" class="btn btn-danger remove-image">Remove Image</button>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                        ?>
                    </div>
                    <button type="button" id="add_image" class="btn btn-primary">Add Image</button>
                    <input type="submit" name="submit" value="Update" class="btn btn-success">
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle adding new image input fields
            $('#add_image').click(function() {
                var imageInputs = $('#image_inputs');
                var imageCount = imageInputs.children('.image-container').length;

                var newImageInput = `
                    <div class="image-container">
                        <img src="" alt="Preview" class="img-thumbnail">
                        <input type="file" name="image[${imageCount}]" class="form-control-file" multiple>
                        <div class="image-details">
                            <div class="form-group">
                                <label>Image Name:</label>
                                <input type="text" name="image_name[${imageCount}]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Image Description:</label>
                                <textarea name="image_description[${imageCount}]" class="form-control"></textarea>
                            </div>
                            <button type="button" class="btn btn-danger remove-image">Remove Image</button>
                        </div>
                    </div>
                `;
                imageInputs.append(newImageInput);
            });

            // Handle removing image input fields
            $('#image_inputs').on('click', '.remove-image', function() {
                $(this).closest('.image-container').remove();
            });

            // Handle image selection and preview
            $('#image_inputs').on('change', 'input[type="file"]', function() {
                var fileInput = $(this);
                var imageContainer = fileInput.closest('.image-container');
                var preview = imageContainer.find('img');

                // Read selected file and display in the image preview
                var file = fileInput.get(0).files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            });

            // Trigger change event for existing image inputs to show previews
            $('#image_inputs').find('input[type="file"]').trigger('change');
        });
    </script>
</body>
</html>

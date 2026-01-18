<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapido Blog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .content-image {
            /*background-color: #f8f8f8;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;*/
        }

        .content-image img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            width: 380px;
        }

        .ads {
            background-color: #f8f8f8;
            min-height: 468px;
            /* Adjust as needed */
            display: flex;
            align-items: center;
            position: relative;
            justify-content: center;
            margin-bottom: 20px;
        }

        .ads .vertcl_banner_right {
            display: block;
            position: absolute;
            width: 100%;
            right: 0px;
            top: 0;
        }

        .content-section {
            margin-bottom: 40px;
        }

        .blog-container {
            padding: 28px;
            border-radius: 4px;
        }

        p {
            font-size: 13px;
        }

        .active a {
            color: #337ab7 !important;
        }

        @media (max-width: 767px) {
            .navbar-light .navbar-nav {
                display: flex;
                align-items: center;
                flex-wrap: nowrap;
                position: relative;
                top: -63px;
                margin-left: 127px;
            }

            .ads .vertcl_banner_right {
                display: none !important;
            }

            .navbar-header,
            .navbar-nav>li {
                display: inline-block;
            }

            .navbar-brand {
                margin-right: 20px;
            }

            .navbar-nav>li>a {
                padding-left: 10px;
                padding-right: 10px;
            }

            .navbar-light {
                height: 20px;
            }

        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="container">
        <nav class="navbar navbar-light">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo base_url() ?>new-blog">Kapido Blog</a>
            </div>
            <ul class="nav navbar-nav">
                <?php
                $current_menu_item = $this->uri->segment(3);
                // echo $current_menu_item;exit;
                ?>
                <li class="<?php echo ($current_menu_item == 'flights') ? 'active' : ''; ?>"><a
                        href="<?php echo base_url() ?>new-blog/flights">Flight</a></li>
                <li class="<?php echo ($current_menu_item == 'hotels') ? 'active' : ''; ?>"><a
                        href="<?php echo base_url() ?>new-blog/hotels">Hotel</a></li>
                <!-- <li><a href="<?php echo base_url() ?>index.php/general/new_blog/transfers">Transfer</a></li>
                <li><a href="<?php echo base_url() ?>index.php/general/new_blog/activities">Activities</a></li>
                <li><a href="<?php echo base_url() ?>index.php/general/new_blog/cars">Cars</a></li> -->
                <li class="<?php echo ($current_menu_item == 'holidays') ? 'active' : ''; ?>"><a
                        href="<?php echo base_url() ?>new-blog/holidays">Holidays</a></li>
            </ul>
        </nav>
    </div>

    <!-- Content -->
    <div class="container blog-container">
        <!-- Content Section 1 -->
        <!-- <h1 class="hide">Kapido</h1> -->
        <div class="row">
            <div class="col-sm-10">

                <?php
                foreach ($blog_list as $key => $value) {
                    // debug($blog_list);exit;
                    ?>
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-sm-5 content-image">
                            <a href="<?php echo base_url() ?>blog/<?php echo $value['blog_url']; ?>">
                                <img src="<?= $GLOBALS['CI']->template->domain_images($value['images'][0]['image']); ?>"
                                    alt="<?php echo $value['images'][0]['image_name'] ?>">
                            </a>

                        </div>
                        <div class="col-sm-7">
                            <h3><a
                                    href="<?php echo base_url() ?>blog/<?php echo $value['blog_url']; ?>"><?php echo ucwords($value['title']); ?></a>
                            </h3>
                            <p style="color:#777;"><?php
                            $timestamp = strtotime($value['added_on']);
                            $formatted_date = date('F j, Y H:i:s', $timestamp);
                            echo $formatted_date;
                            ?></p>
                            <p><?php echo substr($value['description'], 0, 300); ?></p>
                            <a href="<?php echo base_url() ?>blog/<?php echo $value['blog_url']; ?>">View More</a>
                        </div>

                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-2 ads">
                <!-- <? print_r($right_ads); ?> -->
                <!-- <p>Ads or other content</p> -->
                <?php if (!empty($right_ads)) { ?>

                    <?php foreach ($right_ads as $right_ad) { ?>

                        <div class="vertcl_banner_right " style="display:block">

                            <img src="<?php echo $GLOBALS['CI']->template->domain_images($right_ad['image']) ?>" width="131px"
                                height="1186px" alt="" />
                            <div class="right_bannr_cntnt" style="display:block">
                                <p><?= $right_ad['message'] ?></p>
                                <!-- <p>For Worry-free Travel: Grab Up to 30% OFF*</p> -->
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

        </div>
    </div>
    <style type="text/css">
        #cookieConsent {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #222;
            color: #fff;
            text-align: center;
            padding: 10px;
            display: none;
            /* Initially hidden */
            z-index: 1000;
        }

        #cookieConsent .cookie-banner {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        #cookieConsent p {
            margin: 0;
        }

        #cookieConsent button {
            background-color: #f1c40f;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            color: #000;
        }

        #cookieConsent button:hover {
            background-color: #e1b40e;
        }
    </style>
    <div id="cookieConsent">
        <div class="cookie-banner">
            <p>This website uses cookies to ensure you get the best experience on our website.
                <a href="https://www.kapido.com/privacy-policy" target="_blank">Read our Privacy Policy</a>
            </p>
            <button id="acceptCookies">Accept</button>
            <button id="rejectCookies">Reject</button>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            // Check if consent is already stored in localStorage
            if (!localStorage.getItem("cookieConsent")) {
                document.getElementById("cookieConsent").style.display = "block";
            }

            document.getElementById("acceptCookies").addEventListener("click", function () {
                localStorage.setItem("cookieConsent", "accepted");
                document.getElementById("cookieConsent").style.display = "none";
                // Enable non-essential cookies here
            });

            document.getElementById("rejectCookies").addEventListener("click", function () {
                localStorage.setItem("cookieConsent", "rejected");
                document.getElementById("cookieConsent").style.display = "none";
                // Disable non-essential cookies here
            });
        });

    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>
<style type="text/css">
    .mySlides img {
    height: 100%;
    max-height: 450px;
}

div#myModal {
    background: #00000082;
}
    .modal-custm{
        margin: 8% auto !important;
    }
.left-img .prev{
    position: absolute;
    top: 50%;
    color: #fff;
    font-size: 50px;
    font-weight: 400;
}
.left-img .next{
    position: absolute;
    top: 50%;
    color: #fff;
    right: 0;
    font-size: 50px;
    font-weight: 400;
}

.gallery-container {
    background-color: #fff;
    color: #35373a;
    min-height: 100vh;
    padding: 30px 50px;
}

.gallery-container h1 {
    text-align: center;
    margin-top: 50px;
    font-family: 'Droid Sans', sans-serif;
    font-weight: bold;
}

.gallery-container p.page-description {
    text-align: center;
    margin: 25px auto;
    font-size: 18px;
    color: #999;
}

.tz-gallery {
    padding: 40px;
}

/* Override bootstrap column paddings */
.tz-gallery .row > div {
    padding: 2px;
}

.tz-gallery .lightbox img {
    width: 100%;
    border-radius: 0;
    position: relative;
}

.tz-gallery .lightbox:before {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -13px;
    margin-left: -13px;
    opacity: 0;
    color: #fff;
    font-size: 26px;
    font-family: 'Glyphicons Halflings';
    content: '\e003';
    pointer-events: none;
    z-index: 9000;
    transition: 0.4s;
}


.tz-gallery .lightbox:after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    background-color: rgba(46, 132, 206, 0.7);
    content: '';
    transition: 0.4s;
}

.tz-gallery .lightbox:hover:after,
.tz-gallery .lightbox:hover:before {
    opacity: 1;
}

.baguetteBox-button {
    background-color: transparent !important;
}

@media(max-width: 768px) {
    body {
        padding: 0;
    }
}
.left-img-sec {
    margin-bottom: 25px;
    padding: 0;
    background: #fff;
    font-size: 16px;
    width: 40%;
    font-weight: 600;
}

.blog-img-title {
        margin: 00 5px;
    padding: 10px 0 20px;
    font-size: 20px;
    font-weight: 600;
    color: #444;

}

ul.img-ul {
    display: flex;
    display: -webkit-flex;
    flex-direction: row;
    -webkit-flex-direction: row;
    justify-content: flex-start;
    -webkit-justify-content: flex-start;
    align-items: flex-start;
    -webkit-align-items: flex-start;
    flex-wrap: wrap;
    -webkit-flex-wrap: wrap;
}

ul.img-ul li {
    margin: 0 15px 10px 0;
    flex: 0 0 48%;
    -webkit-flex: 0 0 46%;
}
ul.img-ul li a {
    color: #555;
    transition: all .3s;
    border-bottom: 1px dashed #fff;
        font-size: 13px;
    font-weight: 400;
}
section.blog-page {
    padding:15px 0;
    font: 400 13px / 20px 'arial', sans-serif;
    color: #333;
    background-color: #fff;
    -webkit-text-size-adjust: none;
    -webkit-font-smoothing: antialiased;
}
ul.img-ul li img {
    margin-bottom: 3px;
    display: block;
    width: 100%;
    border-radius: 6px;
    height: 100%;
    min-height: 150px;
}
.blg-title {
   
    padding: 0;
    background: #fff;
    overflow: hidden;
}
.blg-title h2, .dlt1 h2 {
    margin: 0;
    padding: 15px;
    font-size: 16px;
    line-height: 20px;
    font-weight: 700;
    color: #444;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    border: 1px solid #dcdcdc;
}.right-content {
    padding: 48px 10px;
    width: 100%;
    display: table-cell;
    vertical-align: top;
/*    width: 60%;*/
    float: right;
}
.blog-inner-content {
    padding: 15px;
    font-size: 15px;
    line-height: 22px;
    background: #fff;
    border-right: 1px solid #dadada;
    border-bottom: 1px solid #dadada;
    border-left: 1px solid #dadada;
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
}
.blog-inner-content p {
    margin: 0 0 15px;
    font-size: 14px;
}
.main-div-blog{
    width: 100%;
    display: flex;

}
.dtl-inner {
    padding: 15px;
    font-size: 15px;
    line-height: 22px;
    background: #fff;
    border-right: 1px solid #dadada;
    border-bottom: 1px solid #dadada;
    border-left: 1px solid #dadada;
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
}
ul.dtl-list {
    display: flex;
    display: -webkit-flex;
    flex-direction: row;
    -webkit-flex-direction: row;
    justify-content: space-between;
    -webkit-justify-content: space-between;
    align-items: flex-start;
    -webkit-align-items: flex-start;
    flex-wrap: wrap;
    -webkit-flex-wrap: wrap;
}
ul.dtl-list li {
    padding: 0 0 10px;
    flex: 0 0 30%;
    -webkit-flex: 0 0 30%;
    color: #888;
    background-image: none;
        max-width: 200px;
}
.dtl-list a {
    padding-bottom: 3px;
    border-bottom: 1px dashed #2cb8e2;
    transition: all .3s;
    font-size: 13px;
}
.dtl-list a:hover {
    color: #444;
    border-bottom: 1px dashed #444;
}
.blog-img-title strong{
    color: #0080BD;
}
.travel-breadcrumb {
    padding: 15px 0;
    font-size: 15px;
    border-bottom: 1px solid #dadada;
}
@media (max-width:550px){
    .left-img-sec {
    width: 100%;
}

.right-content {
    width: 100%;
}

.main-div-blog {
    display: block;
}

ul.img-ul {
    display: block;
}

.travel-breadcrumb {
    padding: 15px;
}
}
.blog-image-section {
            position: relative;
            background-image: url('<?php echo base_url()?>/extras/custom/TMX2565771716449443/images/<?php echo htmlspecialchars($blog_data[0]['images'][0]['image']);?>');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 400px;
            align-items: center;
            justify-content: center;
            display: flex;
        }

.blog-image-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Adjust color and opacity */
    z-index: 1;
}

.blog-image-section .content {
    position: relative;
    z-index: 2;
    color: white; /* Adjust text color as needed */
    padding: 20px;
}
#description li {
    list-style-type: inherit;
}
#description h3{
    font-size: 20px;
}
</style>

<div class="blog-image-section">
    <div class="content">
        <h1><?php echo $blog_data[0]['title']?></h1>
        <p>
            <?php echo $blog_data[0]['sub_title']?>
        </p>
    </div>
</div>
<div class="container">
    <nav class="navbar navbar-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo base_url()?>new-blog">Kapido Blog</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="<?php echo base_url()?>new-blog/flights">Flight</a></li>
            <li><a href="<?php echo base_url()?>new-blog/hotels">Hotel</a></li>
            <!-- <li><a href="<?php echo base_url()?>new_blog/transfers">Transfer</a></li>
                <li><a href="<?php echo base_url()?>new_blog/activities">Activities</a></li>
                <li><a href="<?php echo base_url()?>new_blog/cars">Cars</a></li> -->
            <li><a href="<?php echo base_url()?>new-blog/holidays">Holidays</a></li>
        </ul>
    </nav>
</div>
<section class="blog-page">
    <div class="container">
        <div class="travel-breadcrumb hide">
            <a href="<?php echo base_url();?>">Home Page</a> &nbsp;
            <span class="fa fa-angle-right"></span>&nbsp;
            <a href="<?php echo base_url();?>">Blog</a> &nbsp;
            <span class="fa fa-angle-right"></span>&nbsp;
            <strong><?php echo $blog_data[0]['blog_name']?></strong>


        </div>
        <div class="main-div-blog">
            
            <div class="right-content ">
                <p style="color:#777;font-size: 13px;">
                    <?php
                    $time_stamp = strtotime($blog_data[0]['added_on']);
                    $formatted_date = date('F j, Y H:i:s',$time_stamp);
                    echo $formatted_date;
                ?>
                </p>
                <div class="blog-img-title">
                    <?php echo $blog_data[0]['title']?>
                </div>
                <div class="blg-title">
                    <h2><?php echo $blog_data[0]['sub_title']?></h2>
                </div>
                <div class="blog-inner-content" id="description">
                    <?php echo $blog_data[0]['description']?>
                </div>
            </div>

        </div>
        <div class="all-dtls col-md-12 nopad">
            <div class="dlt1">
                <h2>Top Destinations</h2>
                <div class="dtl-inner">
                    <ul class="dtl-list">
                    <?php  

                        foreach ($this->topDestFlightsBlog as $tdf => $tdfval) {
                            // debug($tdfval);exit;
                                            ?>
                            <!-- <a href="<?= base_url().'flights/'.$tdfval['from_airport_name'].'-'.$tdfval['to_airport_name']?>" class="flight_deals_search">
                                <div class="flight_route_dest_foot" style="color: black;">
                                    <?=$tdfval['from_airport_name'];?>
                                        <img src="<?=$GLOBALS['CI']->template->template_images('ic_outline-flight.svg');?>" alt="image">
                                        <?=$tdfval['to_airport_name'];?>
                                </div>
                                <input type="hidden" class="flight_top_from_id" value="<?=$tdfval['from_origin'];?>">
                                <input type="hidden" class="flight_top_to_id" value="<?=$tdfval['to_origin'];?>">
                                <input type="hidden" class="flight_top_from_value" value="<?=$tdfval['from_airport_name'];?>(<?=$tdfval['from_airport_code'];?>)">
                                <input type="hidden" class="flight_top_to_value" value="<?=$tdfval['to_airport_name'];?> (<?=$tdfval['to_airport_code'];?>)">
                                <input type="hidden" class="flight_top_depature" value="<?=date('d-m-Y', strtotime(' +1 day'));?>">
                                <input type="hidden" class="flight_trip_type" value="oneway">
                            </a> -->

                            <a href="<?= base_url().'flights/'.$tdfval['from_airport_name'].'-'.$tdfval['to_airport_name']?>" class="">
                                                <div class="">
                                                    <?=$tdfval['from_airport_name'];?>
                                                        <img src="<?=$GLOBALS['CI']->template->template_images('ic_outline-flight.svg');?>" alt="image">
                                                    <?=$tdfval['to_airport_name'];?>
                                                </div>
                                            </a>
                         <?php } ?>

                                <li>
                                    <a href="<?= base_url().'hotels/toronto'?>"> Toronto Hotels </a>
                                </li>
                    </ul>
                    <ul class="dtl-list hide">
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>

                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                        <li>
                            <a href="#"> Flights to Sydney </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
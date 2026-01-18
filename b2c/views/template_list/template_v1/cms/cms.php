<?php $page_title = $data[0]['page_title']; ?>
<div class="cms_content_header">
    <div class="container">
        <div class="col-12">
            <h1><?php echo $data[0]['page_title'];?></h1>
            <ol class="breadcrumb cms_breadcrumb">
                <li><a href="<?=base_url()?>">Home</a></li>
                <li class="crumb_disable"><a href="<?=base_url().$data[0]['page_label']?>"><?=$page_title?></a></li>
            </ol>
        </div>
    </div>
</div>
<div class="container">

<div class="col-md-12 col-12">

<!-- <div class="lblbluebold16px"><h1><?php echo $data[0]['page_title'];?></h1></div> -->
<?php if($page_title != 'Contact Us'){ ?>
<div class="lblfont12px"><p><?php echo $data[0]['page_description'];?></p></div>
<?php } ?>
<!-- <div class="lblfont12px"><p><?php echo $data[0]['page_description'];?></p></div> -->
<?php //if($page_title == 'Company') { include('team.php'); } ?>
<?php if($page_title == 'Contact Us') { include('contact.php'); } ?>

</div></div>
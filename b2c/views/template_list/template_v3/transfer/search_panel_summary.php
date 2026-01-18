<?php 
//debug($transfer_search_params);
if($transfer_search_params['trip_type']=='oneway') {
    $disable_return_date_label = ' style="opacity:0.4" ';
} else {
    $disable_return_date_label = '';
}

?>

<div class="modfictions">
    
    <div class="splmodify">
        <div class="container">
            <div id="modify" class="araeinner">
                <div class="insplarea">
                    <?php echo $GLOBALS['CI']->template->isolated_view('share/transfer_search') ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.modifysrch').click(function () {
            $(this).stop(true, true).toggleClass('up');
            $('.search-result').stop(true, true).toggleClass('flightresltpage');
            $('.modfictions').stop(true, true).toggleClass('fixd');
        });
    });
</script>
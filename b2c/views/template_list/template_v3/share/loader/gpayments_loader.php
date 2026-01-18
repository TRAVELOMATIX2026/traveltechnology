
<?php
$template_images = $GLOBALS['CI']->template->template_images();
?>
<div class="fulloading result-pre-loader-wrapper">
  <div class="loadmask"></div>
  <div class="centerload cityload">
    <div class="relativetop">
      <div class="paraload">
        <h3>Processing your booking...</h3>
        <img src="<?=$template_images?>default_loading.gif" alt="" />
        <div class="clearfix"></div>
        <small>please wait</small>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="notifyCallback" value="<?=$callbackFn?>" />
<input type="hidden" id="reqTransId" value="<?=$reqTransId?>" />
<script src="<?php echo JAVASCRIPT_LIBRARY_DIR; ?>jquery-2.1.1.min.js"></script>
<script type="text/javascript">
  $('.fulloading').show();
  var callbackFn = parent[$('#notifyCallback').val()];
  if (typeof callbackFn === 'function') {
    callbackFn($('#reqTransId').val());
  }
</script>
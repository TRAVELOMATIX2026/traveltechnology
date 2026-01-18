<style type="text/css">
  .topssec .container {
  padding: 0;
  }
  .price_htlin {
  border: 1px solid #ddd;
  display: block;
  overflow: hidden;
  padding: 10px 20px;
  float: left;
  width: 100%;
  margin: 10px 0;
  }
  .fullcard {
  float: left;
  width: 100%;
  }
  .price_htlin {
  border: 1px solid #c1e6fa;
  display: block;
  overflow: hidden;
  padding: 10px 20px;
  float: left;
  width: 100%;
  margin: 10px 0;
  background: #e6f4f8;
  }
  .strip_img {
  float: left;
  width: 100%;
  overflow: hidden;
  display: block;
  }
  .strip_img span {
  font-size: 25px;
  margin: 10px auto;
  display: table;
  font-weight: bold;
  color: #0bafde;
  }
  .strip_img img {
  /*width: 30%;*/
  height: 30%;
  margin: 0 auto;
  display: table;
  }
  .price_htlin .baseli.price_cet {
  border: none;
  }
  .baseli {
  border-bottom: 1px solid #f1f1f1;
  font-size: 14px;
  }
  .baseli, .baselicenter {
  color: #555;
  padding: 10px;
  width: 100%;
  float: left;
  }
  .colrdark {
  color: #333;
  }
  .bigtext {
  font-size: 20px;
  line-height: 30px;
  width: 100%;
  display: table;
  margin: 0px auto;
  text-align: center;
  }
  .grdtol {
  float: none;
  text-align: center;
  margin: 0px auto;
  display: table;
  }
  .priceflights {
  color: #F47721;
  font-size: 25px;
  overflow: hidden;
  font-weight: 600;
  padding: 0px 20px;
  display: inline-block;
  text-align: center;
  }
  .priceflights strong {
  font-weight: 400;
  margin-right: 3px;
  }
  .pwc .stripe-button-el {
  margin: 0 auto;
  display: table;
  }
/*  .stripe-button-el{
    display: none !important;
  }*/
  .payments_logo{
    margin: 25px 0;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .payments_logo li{
    display: inline-block;
  }
</style>


<?php

error_reporting(E_ALL);
 //debug($pay_data); exit();
$key =  $pay_data['apikey'];
$txnid = empty($pay_data['txnid']) ?  rand(000000,99999999999) : $pay_data['txnid'];
$amount = $pay_data['amount'];
$product_info = $pay_data['productinfo'];
$firstname = $pay_data['firstname'];
$phone = empty($pay_data['phone']) ? '' : $pay_data['phone'];
$email = empty($pay_data['email']) ? '' : $pay_data['email'];
$client_id = $pay_data['client_id']; 
$udf1 = empty($pay_data['udf1']) ? "name" : $pay_data['udf1'];
$furl = $pay_data['furl'];
$surl = $pay_data['surl'];
$action = $pay_data['pay_target_url']."?response_type=code&client_id=".$client_id."&scope=read_only";
?>
<html>
<head>

   <script>
    function submitPayuForm() {
      var payForm  = document.forms["payment"];
      payForm.submit();      
    } //onload="submitPayuForm()"
  </script> 
</head>
<body>


<div class="container">
  <div class="col-12 nopad pamentotur">

  <div class="col-8 nopad fullcard full_room_buk">
    <div class="price_htlin">
      <div class="strip_img">
       <span class=""><?php echo 'Pay Securely';?></span>
       <img class="stripe_payment_img" src="<?php echo $GLOBALS['CI']->template->domain_images('logo.png'); ?>" alt="" />

       
      </div>
      <div class="clearfix"></div>
      <ul>
        <li class="baseli hedli hide">
          <ul>
            <li class="wid10 hide">Room</li>
            <li class="wid20">Guest</li>
            <li class="wid20">Price/Night</li>
            <li class="wid20">Extras</li>
            <li class="wid10 hide">Night(s)</li>
            <li class="textrit">Total Price</li>
          </ul>
        </li>
        <li class="baseli secf hide">
          <ul class="responsive_li">
            <li class="wid10 hide"><span class="res_op">Room</span>1</li>
            <li class="wid20">
              <div class="plusic">
                <div class="left adultic fa fa-male"></div>
                <div class="left cunt"><span class="res_op">Guest</span><span class="resrgtfld"><?php //echo $guest_count; ?></span></div>
              </div>
            </li>
            <li class="wid20">
              <span class="res_op">Price/Night</span><span class="resrgtfld"><?php //echo $session['token_data']['default_currency'].'&nbsp;'.$sub_total; ?></span>
            </li>
            <li class="wid20 cacletooltip">
            <span class="res_op">Extras</span>
              <a class="resrgtfld" data-bs-toggle="tooltip" data-placement="top" title="<?php //echo $session['token_data']['cancellation']; ?>">Cancellation Policy</a>
            </li>
            <li class="wid10 hide"><span class="res_op">Night(s)</span>1</li>
            <li class=" textrit"><span class="res_op">Total Price</span></li>
          </ul>
        </li>
        <li class="baselicenter hide">
          <div class="wid80 left textrit txtresponfld">Tax</div>
          <div class="wid20 left textrit"></div>
        </li> 
        <li class="baselicenter hide">
          <div class="wid80 left textrit txtresponfld">Convenience fee</div>
          <div class="wid20 left textrit"><?php //echo $session['token_data']['default_currency'].'&nbsp;'.$conven_fees; ?></div>
        </li>
        <li class="baselicenter hide"></li>
        <li class="baseli price_cet ">
          <div class="bigtext colrdark">
            <span class="grdtol"><?php //echo $this->lang->line('visa_grandtotal');?></span>
            <div class="priceflights">
             <strong> <?php //echo $pay_data['display_curr_symbol']; ?> </strong>
              <span class="h-p"><?php //echo number_format($pay_data['display_amount'],2); ?></span>
              /-
            </div>
            
          </div>
          <!-- <div class="price_ours">
            
          </div> -->
        </li>
        <li class="pwc">
            <form action="<?php echo base_url(); ?>index.php/payment_gateway/validate_stripe/<?php echo $txnid ?>" method="POST" name="payment">

            <input type="hidden" name="surl" value="<?=$surl?>">
            <input type="hidden" name="furl" value="<?=$furl?>">
              <script
                src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
                data-key="<?php echo $key; ?>"
                data-amount="<?php echo $amount; ?>"
                data-name="<?php echo $firstname; ?>"
                data-description="<?php echo $txnid; ?>"
                data-email="<?php echo $email; ?>"
                data-shipping-address ="Electronic city, Bangalore",
                data-currency="<?php echo $pay_data['to_currency']; ?>"
                data-image="<?php echo base_url(); ?><?php echo "extras/custom/".get_domain_key()."/images/icon-logo.png" ?>">

              </script>
            </form>
        </li>
      </ul>
      <ul class="payments_logo">
         <li><img src="<?php echo $GLOBALS['CI']->template->template_images('visa1.png'); ?>" alt="" /></li>
         <li><img src="<?php echo $GLOBALS['CI']->template->template_images('mastercard.png'); ?>" alt="" /></li>
         <li><img src="<?php echo $GLOBALS['CI']->template->template_images('dis.png'); ?>" alt="" /></li>
         <li><img src="<?php echo $GLOBALS['CI']->template->template_images('aex.png'); ?>" alt="" /></li>
       </ul>  
    </div>
  </div>
  <!-- <div class="col-12 nopad">
    <form action="<?php echo base_url(); ?>index.php/payment_gateway/validate_stripe/<?php echo $txnid ?>" method="POST" name="payment">
  <script
    src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
    data-key="<?php echo $key; ?>"
    data-amount="<?php echo $amount; ?>"
    data-name="Ga Tours GmbH"
    data-description="<?php echo $product_info; ?>"
    data-email="<?php echo $email; ?>"
    data-currency="AUD"
    data-image="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>">
  </script>
</form>
  </div>
   -->
</div>

</div>
<script type="text/javascript">
  $("document").ready(function() {
    $("button.stripe-button-el").trigger('click');
});
</script>


</body>
</html>

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
  width: 30%;
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
  color: #333;
  /*color: #F47721;*/
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
  .stripe-button-el{
    display: none !important;
  }

  .priceflights a {
  color: #F47721;
  }
</style>


<?php

error_reporting(0);
// debug($pay_data); exit();
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
$display_amount = $pay_data['display_amount'];
$display_curr_symbol = $pay_data['display_curr_symbol'];
$display_currency = $pay_data['display_currency'];
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
       <span class=""><?php echo 'Pay With';?></span>
       <img class="" src="<?php echo $GLOBALS['CI']->template->template_images('social.png'); ?>" alt="" />
      </div>
      <div class="clearfix"></div>
      <ul>
        <li class="baseli price_cet ">
          <div class="bigtext colrdark">
            <span class="grdtol"><?php echo $this->lang->line('visa_grandtotal');?></span>
            <div class="priceflights">
             <strong> <?php echo $pay_data['display_curr_symbol']; ?> </strong>
              <span class="h-p"><?php echo $pay_data['display_amount']; ?></span> /-
            </div>
          </div>
        </li>
        <li class="baseli price_cet ">
          <div class="bigtext colrdark">
            <div class="priceflights"><a href="<?=base_url()?>">Cancel Transaction</a></div>
          </div>
        </li>
        <li class="pwc">
            <form action="<?php echo base_url(); ?>index.php/payment_gateway/validate_stripe/<?php echo $txnid ?>" method="POST" name="payment">
            <input type="hidden" name="surl" value="<?=$surl?>">
            <input type="hidden" name="furl" value="<?=$furl?>">
              <script
                src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
                data-key="<?php echo $key; ?>"
                data-amount="<?php echo $amount; ?>"
                data-name="<?php echo $pay_data['domain_name']; ?>"
                data-description="<?php echo $product_info; ?>"
                data-email="<?php echo $email; ?>"
                data-currency="<?php echo $display_currency;?>"
                data-image="">
              </script>
            </form>
        </li>
      </ul>
    </div>
  </div>
</div>

</div>
<script type="text/javascript">
  $("document").ready(function() {
    $("button.stripe-button-el").trigger('click');
});
</script>


</body>
</html>

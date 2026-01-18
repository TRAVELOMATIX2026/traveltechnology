<?php

$app_supported_currency = $this->db_cache_api->get_currency(array('k' => 'country', 'v' => array('currency_symbol', 'country','currency_class')), array('status' => ACTIVE));

$application_preferred_currency = get_application_currency_preference();

//debug($application_preferred_currency);exit;
//debug($app_supported_currency);exit;
foreach ($app_supported_currency as $cur_key => $cur_val) {
	

	$currency = explode(' ',$cur_val);
	//debug($currency);

	if ($currency[0] != ''){

		$symbol = $currency[0];

	}else{

		$symbol = $currency[1];

	}
	if ($currency[2] != ''){
		$symbol_class = $currency[2];
	}
	else
	{
		$symbol_class = strtolower(substr($currency[1],0,2));
	}

	if ($application_preferred_currency == $cur_key) {

		$selected_currency = 'active';

	} else {

		$selected_currency = '';

	}

	/*echo '<li class="currency_li flag '.$symbol_class.' '.$selected_currency.'">*/
	echo '<li class="currency_li  '.$selected_currency.'">

<a href="'.base_url().'index.php/utilities/set_preferred_currency/'.$cur_key.'" class="app-preferred-currency" data-currency="'.$cur_key.'">

	<span class="curncy_img sprte curr_flagd '.$symbol_class.' '.strtolower($cur_key).'"></span>

	<span class="name_currency"> '.$cur_key .'</span>

	<span class="side_curency">'.$symbol.'</span>

</a>

		</li>';

}

?>

<script>

$(document).ready(function($)
{
  /* closed default currency */
        
    $('.app-preferred-currency').on('click',function(e)
    {
        e.preventDefault();
        var _update_currency_url=$(this).attr('href');
        $.get(_update_currency_url,function(response)
        {
            if(response.status==true)
            {
                $('.wrapper').css('opacity','.1');
                location.reload()
                
            }
        });
    });
});

</script>
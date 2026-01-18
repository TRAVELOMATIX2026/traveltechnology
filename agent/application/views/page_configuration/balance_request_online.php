<?php

$form_configuration['inputs'] = array(
	'origin' 					=> array('type' => 'hidden',	'label_line_code' => -1, 'mandatory' => false),
	'transaction_type'			=> array('type' => 'hidden',	'label_line_code' => -1),
	'amount'					=> array('type' => 'number',	'label_line_code' => 208, 'class' => array('numeric')),
	'currency_converter_origin'	=> array('type' => 'hidden', 	'label_line_code' => -1),
	'conversion_value'			=> array('type' => 'hidden',	'label_line_code' => -1),
	'remarks'					=> array('type' => 'textarea',	'label_line_code' => 211, 'mandatory' => false),
	'payment_link'				=> array('type' => 'checkbox',	'label_line_code' => 216, 'source' => 'enum', 'source_id' => 'agree_paymentlink', 'mandatory' => false)

);

/**
 * Add FORM
 */
$form_configuration['form']['request_form'] = array(
'form_header' => '',
		'sections' => array(
			array('elements' => array(
					'origin', 'transaction_type', 'amount', 'currency_converter_origin', 'conversion_value', 'remarks', 'payment_link'
				),
				'fieldset' => 'FFL0048'
			)
		),
		'form_footer' => array('submit', 'reset')
	);
/*** Form End ***/
/**
 * FORM VALIDATION SETTINGS
 */
$auto_validator['origin'] = 'trim|numeric';
$auto_validator['transaction_type'] = 'trim|required';
$auto_validator['amount'] = 'trim|required|numeric';
$auto_validator['currency_converter_origin'] = 'trim|required|numeric';
$auto_validator['conversion_value'] = 'trim|required';
$auto_validator['remarks'] = 'trim';

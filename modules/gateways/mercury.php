<?php

require_once(dirname(__FILE__) . '/Mercury/Mercury.php');

use Mercury\Mercury;

function mercury_config() {

    // When loading plugin setup page, run custom JS
    add_hook('AdminAreaFooterOutput', 1, function($vars) {
        try {
            // Detect module name from filename.
            $gatewayModuleName = basename(__FILE__, '.php');
            // Fetch gateway configuration parameters.
            $gatewayParams = getGatewayVariables($gatewayModuleName);
        }
        catch (exception $e) {
            return;
        }

        $mercury = new Mercury();
        require($mercury->getLangFilePath());
        $systemUrl = $mercury->getSystemUrl();


        return <<<HTML
		<script type="text/javascript">
			/**
			 * Disable callback url editing
			 */
			/**
			 * Padding for config labels
			 */
			var inputLabels = document.getElementsByClassName('fieldlabel');

			for(var i = 0; i < inputLabels.length; i++) {
				inputLabels[i].style.paddingRight = '20px';
			}

			/**
			 * Set available values for margin setting
			 */
			var USDbtcmin = document.getElementsByName('field[USDbtcmin]');
			USDbtcmin.forEach(function(element) {
				element.type = 'number';
				element.min = 10;
				element.max = 100000;
				element.step = 1;
			});
			var USDethmin = document.getElementsByName('field[USDethmin]');
			USDethmin.forEach(function(element) {
				element.type = 'number';
				element.min = 1;
				element.max = 1000;
				element.step = 1;
			});
			var USDdashmin = document.getElementsByName('field[USDdashmin]');
			USDdashmin.forEach(function(element) {
				element.type = 'number';
				element.min = 1;
				element.max = 1000;
				element.step = 1;
			});
             var EURbtcmin = document.getElementsByName('field[EURbtcmin]');
			 EURbtcmin.forEach(function(element) {
			 	element.type = 'number';
			 	element.min = 10;
			 	element.max = 100000;
			 	element.step = 1;
			 });
			 var EURethmin = document.getElementsByName('field[EURethmin]');
			 EURethmin.forEach(function(element) {
			 	element.type = 'number';
			 	element.min = 1;
			 	element.max = 1000;
			 	element.step = 1;
			 });
			 var EURdashmin = document.getElementsByName('field[EURdashmin]');
			 EURdashmin.forEach(function(element) {
			 	element.type = 'number';
			 	element.min = 1;
			 	element.max = 1000;
			 	element.step = 1;
			 });

			/**
			 * Generate Test Setup button and setup result field
			 */
			var settingsTable = document.getElementById("Payment-Gateway-Config-mercury");

			var testSetupBtnRow = settingsTable.insertRow(settingsTable.rows.length - 1);
			var testSetupLabelCell = testSetupBtnRow.insertCell(0);
			var testSetupBtnCell = testSetupBtnRow.insertCell(1);
			testSetupBtnCell.className = "fieldarea";

			var newBtn = document.createElement('BUTTON');
			newBtn.className = "btn btn-primary";

			var t = document.createTextNode("Test Setup");
			newBtn.appendChild(t);

			testSetupBtnCell.appendChild(newBtn);

			newBtn.onclick = function() {
                    let ajaxUrl = "$systemUrl" + 'mpayment.php'

			    	$.ajax({
                        type: 'POST',
                        url: ajaxUrl,
                        data: {
                             'uuid': Math.random() * 10000000,
                            'ajax_check_transaction' : 1,
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.errorCode == 400 || data.errorCode == 404){
                                 newBtn.className = "btn btn-success";
                            }else{
                                alert('You entered an invalid API key');
                            }
                        }
                    });
                   
				return false;
			}
		</script>
HTML;

    });

	$mercury = new Mercury();
	require($mercury->getLangFilePath());


	$settings_array = array(
		'FriendlyName' => array(
			'Type'       => 'System',
			'Value'      => 'Mercury'
		),
		array(
			'FriendlyName' => '<span style="color:grey;">'.$_MERCURYLANG['version']['title'].'</span>',
			'Description'  => '<span style="color:grey;">'.$mercury->getVersion().'</span>'
		)
	);
	$settings_array['ApiKey'] = array(
		'FriendlyName' => 'Public Key',
		'Description'  => $_MERCURYLANG['publicKey']['description'],
		'Type'         => 'text'
	);

    $settings_array['secretKey'] = array(
        'FriendlyName' => 'Secret Key',
        'Description'  => '',
        'Type'         => 'password'
    );

    if ( !empty($mercury->getSystemCurrenciesUSD() ) ){
        $mercury_currencies = $mercury->getDefaultForCurrencies('USD');
        foreach ($mercury_currencies as $code => $currency) {
            $settings_array[ 'USD'.$code.'min' ] = array(
                'FriendlyName' => 'USD to '.strtoupper($code).' '. $_MERCURYLANG['minimum']['title'],
                'Type' => 'text',
                'Description' => $_MERCURYLANG['minimum']['description'].' '.$currency['default']
            );
        }
    }
    if ( !empty($mercury->getSystemCurrenciesEUR() ) ){
        $mercury_currencies = $mercury->getDefaultForCurrencies('EUR');
        foreach ($mercury_currencies as $code => $currency) {
            $settings_array[ 'EUR'.$code.'min' ] = array(
                'FriendlyName' => 'EUR to '.strtoupper($code).' '. $_MERCURYLANG['minimum']['title'],
                'Type' => 'text',
                'Description' => $_MERCURYLANG['minimum']['description'].' '.$currency['default']
            );
        }
    }


        // a password field type allows for masked text input
    $settings_array['testMode'] = array(
        'FriendlyName' => 'Test mode on',
        'Description'  => 'Enable test mode',
        'Type' => 'yesno',
        'Default' => '',
    );
        // a text field type allows for single line text input
    $settings_array[   'publicKeyTest' ]= array(
        'FriendlyName' => 'Test' . $_MERCURYLANG['publicKey']['title'],
        'Description'  => $_MERCURYLANG['publicKey']['description'] . ' for Test',
        'Type' => 'text',
        'Default' => '',
    );
        // a password field type allows for masked text input
    $settings_array[  'secretKeyTest'] = array(
        'FriendlyName' => 'Test' .  $_MERCURYLANG['secretKey']['title'],
        'Description'  => $_MERCURYLANG['secretKey']['description']. ' for Test',
        'Type' => 'password',
        'Default' => '',
    );
	
	return $settings_array;
}

function mercury_link($params) {


	if (false === isset($params) || true === empty($params)) {
		die('[ERROR] In modules/gateways/Mercury.php::mercury_link() function: Missing or invalid $params data.');
	}

	$mercury = new Mercury();
    require($mercury->getLangFilePath());

    $system_url = $mercury->getSystemUrl();
    $form_url = $system_url . 'mpayment.php';

    if (!$mercury->isCurrencySupported($params['currency'])){
        //You currency is not supported
        return $_MERCURYLANG['error']['currency']['notsupported'];
    }

    $form = '<form action="' . $form_url . '" method="GET">';

    $form .= '<input type="hidden" name="invoiceid" value="' . $params['invoiceid']. '"/>';

    $form .= '<input type="hidden" name="invoice_currency" value="' . $params['currency'] . '"/>';
    $form .= '<input type="hidden" name="amount" value="' . $params['amount'] . '"/>';

    $form .= '<input type="hidden" name="email" value="' . $params['clientdetails']['email'] . '"/>';

    $form .= '<input type="submit" value="' . $params['langpaynow'] . '"/>';
    $form .= '</form>';

    return $form;
}

<?php
if (!defined("WHMCS")) die("This file cannot be accessed directly");

// WHMCS Module name
$_MERCURYLANG['module']['title'] = 'Mercury';
// WHMCS Module version
$_MERCURYLANG['version']['title'] = 'Module version';

// Config page
$_MERCURYLANG['publicKey']['title'] = "Public Key";
$_MERCURYLANG['publicKey']['description'] = 'Enter your public key here';

$_MERCURYLANG['secretKey']['title'] = "Secret Key";
$_MERCURYLANG['secretKey']['description'] = 'Enter secret key here';

$_MERCURYLANG['enabled']['title'] = " Enable";
$_MERCURYLANG['enabled']['description'] = "";
$_MERCURYLANG['minimum']['title'] = " min";
$_MERCURYLANG['minimum']['description'] = "set min for ";
$_MERCURYLANG['minimum']['description'] = "set minimum for transaction, default is ";


// Checkout page
$_MERCURYLANG['orderId'] = "Order #";

//errors
$_MERCURYLANG['error']['api']['title'] = "Could not connect to Mercury API";
// error message about test setup - maybe add test btn at the end
$_MERCURYLANG['error']['api']['message'] = "Note to webmaster: Please login to admin and go to Setup > Payments > Payment Gateways > Manage Existing Gateways and use the Test Setup button to diagnose the error.";
$_MERCURYLANG['error']['currency']['notsupported'] = "You currency is not supported.";
$_MERCURYLANG['error']['user']['auth'] = "There is not an authenticated User.";

//Invoice
$_MERCURYLANG['invoiceNote']['paid'] = "Paid to";
$_MERCURYLANG['invoiceNote']['address'] = "address";
$_MERCURYLANG['invoiceNote']['notpaid'] = "Didn't pay to";
$_MERCURYLANG['invoiceNote']['error'] = "Something get wrong. Please contact administrator";



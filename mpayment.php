<?php

require_once(dirname(__FILE__) . '/modules/gateways/Mercury/Mercury.php');

use WHMCS\ClientArea;
use stdClass;
use WHMCS\Database\Capsule;
use WHMCS\Authentication\CurrentUser;
use Mercury\Mercury;

define('CLIENTAREA', true);
require 'init.php';

// Init Mercury class
$mercury = new Mercury();
require($mercury->getLangFilePath(isset($_REQUEST['language']) ? $_REQUEST['language'] : ""));

$ca = new ClientArea();

$ca->setPageTitle('Mercury Payment');

$ca->addToBreadCrumb('index.php', Lang::trans('globalsystemname'));
$ca->addToBreadCrumb('mpayment.php', 'Mercury Payment');

$ca->initPage();

/*
 * SET POST PARAMETERS TO VARIABLES AND CHECK IF THEY EXIST
 */
$get_order = htmlspecialchars(isset($_REQUEST['get_order']) ? $_REQUEST['get_order'] : "");
$finishOrder = htmlspecialchars(isset($_REQUEST['finish_order']) ? $_REQUEST['finish_order'] : "");

$system_url = $mercury->getSystemUrl();
$ca->assign('system_url', $system_url);


/// AJAX flags
$ajaxCreateTransaction = htmlspecialchars(isset($_GET['ajax_create_transaction']) ? $_GET['ajax_create_transaction'] : "");
$ajaxCheckTransaction = htmlspecialchars(isset($_GET['ajax_check_transaction']) ? $_GET['ajax_check_transaction'] : "");

$invoiceid  = htmlspecialchars(isset($_POST['invoiceid']) ? $_POST['invoiceid'] : "");
$orderAmount = htmlspecialchars(isset($_POST['amount']) ? $_POST['amount'] : "");
$billingEmail = htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : "");
$currency = htmlspecialchars(isset($_POST['invoice_currency']) ? $_POST['invoice_currency'] : "");

$ca->assign('amount', $orderAmount);
$ca->assign('email', $billingEmail);


$currentUser = new CurrentUser;
$user = $currentUser->user();
if (!$user) {
	//add to language file
	echo "There is not an authenticated User.";
	exit();
}

$ca->assign('currency', $currency);


if ($ajaxCreateTransaction){
	$email = htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : false);
	$crypto = htmlspecialchars(isset($_POST['crypto']) ? $_POST['crypto'] : false);
	$currency = htmlspecialchars(isset($_POST['currency']) ? $_POST['currency'] : false);
	$price = htmlspecialchars(isset($_POST['price']) ? $_POST['price'] : false);

	if (!($email && $crypto && $currency && $price)){
	   exit(json_encode(['data.data.error'=>'Something wrong with your order']));
	}

	try {
	    $transactionData = $mercury->createTransaction($email,$crypto,$currency,$price);

	}catch (Exception $exception){
		header("Content-Type: application/json");
		//'status' => 'failed','error'=>'Cant create transaction',
		exit(json_encode(['data.data.error'=>$exception->getMessage()]));
	}

	$postData = array(
		'status' => 'success',
		'data' => $transactionData,
	);

	header("Content-Type: application/json");
	exit(json_encode($postData));
}

// test ajax
if ($ajaxCheckTransaction){
	$uuid = htmlspecialchars(isset($_POST['uuid']) ? $_POST['uuid'] : "");

	try {
		$transactionData = $mercury->checkStatus($uuid);
	}catch (Exception $exception){
		header("Content-Type: application/json");
		exit(json_encode(['data.data.error'=>$exception->getMessage()]));
	}

	$postData = array(
		'status' => 'success',
		'data' => $transactionData,
	);

	header("Content-Type: application/json");
	exit(json_encode($postData));
}

 if($finishOrder){
	$invoiceId = $finishOrder;
	$transactionData =[];
	$transactionData['currencyCode'] = htmlspecialchars(isset($_REQUEST['currencyCode']) ? $_REQUEST['currencyCode'] : "");
	$transactionData['paymentAmount'] = htmlspecialchars(isset($_REQUEST['paymentAmount']) ? $_REQUEST['paymentAmount'] : "");
	$transactionData['uuid'] = htmlspecialchars(isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : "");
	$transactionData['address'] = htmlspecialchars(isset($_REQUEST['address']) ? $_REQUEST['address'] : "");
	$transactionData['crypto'] = htmlspecialchars(isset($_REQUEST['crypto']) ? $_REQUEST['crypto'] : "");


	if ($mercury->payInvoiceProcessing($invoiceId,$transactionData)){
		//address where paid add to notes
		$invoiceNote = $_MERCURYLANG['invoiceNote']['paid'].' '.$transactionData['crypto'].' '.$_MERCURYLANG['invoiceNote']['address'].' '.$transactionData['address'];
		$mercury->updateInvoiceNote($invoiceId, $invoiceNote);
		$finishUrl = $system_url . 'viewinvoice.php?id=' . $finishOrder . '&paymentsuccess=true';
	}else{
		$invoiceNote = $_MERCURYLANG['invoiceNote']['notpaid'].' '.$transactionData['crypto'].' '.$_MERCURYLANG['invoiceNote']['address'].' '.$transactionData['address'].' '.$_MERCURYLANG['invoiceNote']['error'];
		$mercury->updateInvoiceNote($invoiceId, $invoiceNote);
		$finishUrl = $system_url . 'viewinvoice.php?id=' . $finishOrder;
	}
	header("Location: $finishUrl");
    exit();
}else if(!$invoiceid) {
	echo "<b>Error: Failed to fetch order data.</b> <br>
				Note to admin: Please check that your System URL is configured correctly.
				If you are using SSL, verify that System URL is set to use HTTPS and not HTTP. <br>
				To configure System URL, please go to WHMCS admin > Setup > General Settings > General";
	exit;
}

$order_id = $invoiceid;
$ca->assign('order_id', $order_id);
$ca->assign('_MERCURYLANG', $_MERCURYLANG);
$ca->assign('checkStatusInterval', $mercury->getCheckStatusInterval() );


$active_crypto_currencies = $mercury->get_currency($currency,$orderAmount);
if ($active_crypto_currencies) {
//	$ca->assign('active_crypto_currencies', json_encode($active_crypto_currencies));
	$ca->assign('minbtc', $mercury->getCryptoMinAmount('btc',$currency));
	$ca->assign('mindash', $mercury->getCryptoMinAmount('dash',$currency));
	$ca->assign('mineth', $mercury->getCryptoMinAmount('eth',$currency));

}else{
	echo "<b>Error: No active mercury currencies.</b> <br>
					You currency is not supported";
	exit;
}


# Define the template filename to be used without the .tpl extension
$ca->setTemplate('../mercury/payment');

$ca->output();

?>

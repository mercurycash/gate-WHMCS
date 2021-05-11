<?php

require_once(dirname(__FILE__) . '/modules/gateways/Mercury/Mercury.php');

use WHMCS\ClientArea;
use stdClass;
use WHMCS\Database\Capsule;
use WHMCS\Authentication\CurrentUser;
use Mercury\Mercury;

define('CLIENTAREA', true);
require_once 'init.php';

// Init Mercury class
$mercury = new Mercury();

$lang_file_path =$mercury->getLangFilePath(isset($_REQUEST['language']) ? $_REQUEST['language'] : "");
require_once($lang_file_path);

$ca = new ClientArea();

$ca->setPageTitle('Mercury Payment');

$ca->addToBreadCrumb('index.php', Lang::trans('globalsystemname'));
$ca->addToBreadCrumb('mpayment.php', 'Mercury Payment');

$ca->initPage();

/*
 * SET POST PARAMETERS TO VARIABLES AND CHECK IF THEY EXIST
 */
$finishOrder = htmlspecialchars(isset($_GET['finishOrder']) ? $_GET['finishOrder'] : "");

$system_url = $mercury->getSystemUrl();
$ca->assign('system_url', $system_url);

// test ajax request with Random UUID only for admin
$ajaxTestTransaction = isset($_POST['ajax_test_transaction']) ;

if ($ajaxTestTransaction){

    $currentUser = new CurrentUser;
    $user = $currentUser->user();
    if (!$user) {
        //add to language file
        echo $_MERCURYLANG['error']['user']['auth'];
        exit();
    }
    $uuid = htmlspecialchars(isset($_POST['uuid']) ? $_POST['uuid'] : "");

    try {
        $transactionData = $mercury->checkStatus($uuid);
    }catch (\Exception $exception){
        header("Content-Type: application/json");
        exit(json_encode(['status' => 'failed','errorCode'=>$exception->getCode(),'errorMessage'=>$exception->getMessage()]));
    }

    $postData = array(
        'status' => 'success',
        'data' => $transactionData,
    );

    header("Content-Type: application/json");
    exit(json_encode($postData));
}

//For users
$ca->requireLogin(); // Go to login page if not authenticate

/// AJAX flags
$ajaxCreateTransaction = isset($_GET['ajax_create_transaction']) ;
$ajaxCheckTransaction = isset($_GET['ajax_check_transaction']) ;

$invoiceId = isset($_POST['invoiceid']) ? filter_input(INPUT_POST,'invoiceid',FILTER_SANITIZE_NUMBER_INT) : "";
$orderAmount = isset($_POST['amount']) ? filter_input(INPUT_POST,'amount',FILTER_SANITIZE_STRING) : "";
$email = isset($_POST['email']) ? filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL) : "";
$currency = isset($_POST['currency']) ? filter_input(INPUT_POST,'currency',FILTER_SANITIZE_STRING) : "";

$ca->assign('amount', $orderAmount);
$ca->assign('email', $email);
$ca->assign('currency', $currency);


if ($ajaxCreateTransaction){
    $crypto = isset($_POST['crypto']) ? filter_input(INPUT_POST,'crypto',FILTER_SANITIZE_STRING) : "";
    $price = isset($_POST['price']) ? filter_input(INPUT_POST,'price',FILTER_SANITIZE_STRING) : "";

	$postData = array(
		'status' => 'error',
		'data' => array(),
	);

	if (!($email && $crypto && $currency && $price)){
		$postData['data']['error'] = "Something wrong with your order";
		exit( json_encode($postData) );
	}

	try {
	    $transactionData = $mercury->createTransaction($email,$crypto,$currency,$price);
	}catch (Exception $exception){
		header("Content-Type: application/json");
		$postData['data']['error'] =  $exception->getMessage();
		exit( json_encode($postData) );
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
    $uuid = isset($_POST['uuid']) ? filter_input(INPUT_POST,'uuid',FILTER_SANITIZE_STRING) : "";

	$postData = array(
		'status' => 'error',
		'data' => array(),
	);

	try {
		$transactionData = $mercury->checkStatus($uuid);
	}catch (Exception $exception){
		header("Content-Type: application/json");
		$postData['data']['error'] =  $exception->getMessage();
		exit( json_encode($postData) );
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
	$transactionData['currencyCode'] = isset($_POST['currencyCode']) ?  filter_input(INPUT_POST,'currencyCode',FILTER_SANITIZE_STRING) : "";
    $transactionData['paymentAmount'] = isset($_POST['paymentAmount']) ?  filter_input(INPUT_POST,'paymentAmount',FILTER_SANITIZE_STRING) : "";
    $transactionData['uuid'] = isset($_POST['uuid']) ?  filter_input(INPUT_POST,'uuid',FILTER_SANITIZE_STRING) : "";
    $transactionData['address'] = isset($_POST['address']) ?  filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING) : "";
    $transactionData['crypto'] = isset($_POST['crypto']) ?  filter_input(INPUT_POST,'crypto',FILTER_SANITIZE_STRING) : "";


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
}else if(!$invoiceId) {
	echo "<b>Error: Failed to fetch order data.</b> <br>
				Note to admin: Please check that your System URL is configured correctly.
				If you are using SSL, verify that System URL is set to use HTTPS and not HTTP. <br>
				To configure System URL, please go to WHMCS admin > Setup > General Settings > General";
	exit;
}

$ca->assign('order_id', $invoiceId);
$ca->assign('_MERCURYLANG', $_MERCURYLANG);
$ca->assign('checkStatusInterval', $mercury->getCheckStatusInterval() );


$active_crypto_currencies = $mercury->get_currency($currency,$orderAmount);
if ($active_crypto_currencies) {
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
<?php

namespace Mercury;

use WHMCS\Database\Capsule;
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';
require_once __DIR__ . '/gate-plugins-sdk/vendor/autoload.php';

use MercuryCash\SDK\Adapter;
use MercuryCash\SDK\Auth\APIKey;
use MercuryCash\SDK\Endpoints\Transaction;

/**
 * Class Mercury
 * @package Mercury
 */
class Mercury {

    const APPROVED = 'TRANSACTION_APROVED';

    private  $version = '1.0';
    protected $isTestMode = null;

    protected $baseApiUrl = 'https://api-way.mercury.cash';
    protected $testApiUrl = 'https://api-way.mercurydev.tk';
    protected $priceUrl = 'https://prices.mercury.cash/prices';
    protected $testPriceUrl = 'https://api.mercury.cash/api/price';

    protected $currenciesList;
    protected $availableFiat = ['USD','EUR'];
    protected $defCheckInterval = 2000;


    protected $minForCurrencies = array(
        'USD' => array(
            'btc' => array(
                'name' => 'Bitcoin',
                'default' =>'200',
            ),
            'eth' => array(
                'name' => 'ETH',
                'default' =>'20',
            ),
            'dash' => array(
                'name' => 'DASH',
                'default' =>'2',
            )
        ),
        'EUR' => array(
            'btc' => array(
                'name' => 'Bitcoin',
                'uri' => 'bitcoin',
                'default' =>'40',
            ),
            'eth' => array(
                'name' => 'ETH',
                'default' =>'5',
            ),
            'dash' => array(
                'name' => 'DASH',
                'default' =>'2',
            )
        )
    );

    public $qrCryptoNames = [
        'ETH' => 'ethereum',
        'BTC' => 'bitcoin',
        'DASH' => 'dash'
    ];
    /*
     * Get user configured API key from database
     */
    public function getPublicKey() {
        $gatewayParams = getGatewayVariables('mercury');
        return ($this->isTestMode())?$gatewayParams['publicKeyTest']:$gatewayParams['ApiKey'];
    }

    /*
     * Get user configured API key from database
     */
    public function getSecretKey() {
        $gatewayParams = getGatewayVariables('mercury');

        return ($this->isTestMode()) ? $gatewayParams['secretKeyTest'] : $gatewayParams['secretKey'];
    }
    public function isTestMode(){
        if ($this->isTestMode !== null){
            return $this->isTestMode;
        }
        $gatewayParams = getGatewayVariables('mercury');
        $this->isTestMode = ( $gatewayParams['testMode']) ? true : false;

        return $this->isTestMode;
    }

    public function getBaseUrl(){
        if ($this->isTestMode()){
            return $this->testApiUrl;
        }
        return $this->baseApiUrl;
    }

    /** Get minimum value for Crypto Currency
     * @param $crypto
     * @param $currency
     * @return integer
     */
    public function getCryptoMinAmount ($crypto,$currency){
        $gatewayParams = getGatewayVariables('mercury');
        $minSetting = $this->getDefaultForCurrencies($currency);
        return (integer)(!empty($gatewayParams[$currency.$crypto.'min'])) ? $gatewayParams[$currency.$crypto.'min']: $minSetting[$crypto]['default'];
    }

    /** get checkStatusInterval
     * @return integer
     */
    public function getCheckStatusInterval (){
        $gatewayParams = getGatewayVariables('mercury');
        return (integer)( $gatewayParams['checkStatusInterval']) ? $gatewayParams['checkStatusInterval'] : $this->defCheckInterval;
    }

    /**
     * Create Mercury Transaction
     * @param $mail
     * @param $crypto
     * @param $fiat_currency
     * @param $amount
     * @return array
     */
    public function createTransaction($mail,$crypto,$fiat_currency,$amount){

        $api_key = new APIKey($this->getPublicKey(), $this->getSecretKey());
        $adapter = new Adapter($api_key, $this->getBaseUrl());
        $endpoint = new Transaction($adapter);

        $transaction = $endpoint->create([
            'email' => $mail,
            'crypto' => $crypto,
            'fiat' => $fiat_currency,
            'amount' => (float) $amount,
            'tip' => 0,
        ]);

        $endpoint->process($transaction->getUuid());
        $qrCodeText = "";
        $address = $transaction->getAddress();
        $amount = $transaction->getCryptoAmount();

        $qrCodeText .= $this->qrCryptoNames[$crypto] . ":" . $address . "?";
        $qrCodeText .= "amount=" . $amount . "&";
        $qrCodeText .= "cryptoCurrency=" . $crypto;

        return  [
            'uuid' => $transaction->getUuid(),
            'cryptoAmount' => $transaction->getCryptoAmount(),
            'fiatIsoCode' => $transaction->getFiatIsoCode(),
            'fiatAmount' => $transaction->getFiatAmount(),
            'address' => $transaction->getAddress(),
            'networkFee' => $transaction->getFee(),
            'exchangeRate' => $transaction->getRate(),
            'cryptoCurrency'=>$crypto,
            'qrCodeText' => $qrCodeText,
        ];

    }

    /**
     * Check Status Mercury Transaction
     * @param $uuid
     * @return array
     */
    public function checkStatus($uuid){

        $api_key = new APIKey($this->getPublicKey(), $this->getSecretKey());
        $adapter = new Adapter($api_key, $this->getBaseUrl());
        $endpoint = new Transaction($adapter);

        $status = $endpoint->status($uuid);

        return [
            'status' => $status->getStatus(),
            'confirmations' => $status->getConfirmations(),

        ];
    }

    /**
     * Check
     *
     * @param $invoiceId
     * @param $transactionData
     * @return bool
     */
    public function payInvoiceProcessing($invoiceId,$transactionData){
        if (!$this->isTestMode()){
            $status = $this->checkStatus($transactionData['uuid']);
            if ($status['status'] != self::APPROVED ) {
                return false;
            }
        }

        $this->payInvoice($invoiceId,$transactionData);

        return true;

    }

    public function payInvoice($invoiceId,$transactionData){
        $paymentFee = 1;
        $currencyCode = $transactionData['currencyCode'];
        $paymentAmount = $transactionData['paymentAmount'];
        $txid = $transactionData['uuid'];
        $gatewayModuleName = 'mercury';

        logTransaction($gatewayModuleName, $transactionData, "Successful");
        /**
         * Add Invoice Payment.
         *
         * Applies a payment transaction entry to the given invoice ID.
         *
         * @param int $invoiceId         Invoice ID
         * @param string $transactionId  Transaction ID
         * @param float $paymentAmount   Amount paid (defaults to full balance)
         * @param float $paymentFee      Payment fee (optional)
         * @param string $gatewayModule  Gateway module name
         */
        addInvoicePayment(
            $invoiceId,
            $currencyCode ." - ". $txid,
            $paymentAmount,
            $paymentFee,
            $gatewayModuleName
        );
    }
    /*
     * Get the mercury version
     */
    public function getVersion() {
        return $this->version;
    }

    public function getSystemCurrenciesUSD(){
        return Capsule::table('tblcurrencies')
            ->where('code', 'USD')
            ->value('code');
    }
    public function getSystemCurrenciesEUR(){
        return Capsule::table('tblcurrencies')
            ->where('code', 'EUR')
            ->value('code');
    }


    /**
     * @param $currency
     * @return bool
     */
    public function isCurrencySupported($currency){
        return (bool)(in_array($currency,$this->availableFiat));
    }

	/*
	 * Get list of crypto currencies supported by Mercury
	 */
	public function getDefaultForCurrencies( $currency = null) {
	    if ($currency){
	        return $this->minForCurrencies[$currency];
        }
        return $this->minForCurrencies;

	}


    /**currency list prepared for frontend
     *
     * @param $currency
     * @param $orderAmount
     * @return array|mixed
     */
    public function get_currency($currency,$orderAmount){
        if (!in_array($currency,$this->availableFiat)){
            //return empty array if currency is not supported
            return [];
        }

        // invoice amount in payment file
        $data = $this->getMercuryCurrenceList();
        $data =  $data[$currency];

        foreach ($data as $key) {
            if ($key == 'exchange') continue;
            $data[$key]['cart_amount'] = (float) $orderAmount;
            $data[$key]['minprice'] = $this->getCryptoMinAmount(strtolower($key),$currency);
            $data[$key]['shop_currency'] = $currency;
        }
        return $data;
    }

    /**
     * Get Mercury Currencies Available List
     * @return mixed
     */
    public function getMercuryCurrenceList(){

        $priceUrl = ($this->isTestMode()) ? $this->testPriceUrl: $this->priceUrl;

        if(empty($this->currenciesList)) {
            $ch = curl_init();
            if ($this->isTestMode()){

            }
            curl_setopt($ch, CURLOPT_URL, $priceUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);


            $body = json_decode($response, true);

            $this->currenciesList = $body['data'];
        }
        return $this->currenciesList;
    }

    /**
     * Update invoice note
     * @param $invoiceid
     * @param $note
     */
	public function updateInvoiceNote($invoiceid, $note) {
		Capsule::table('tblinvoices')
			->where('id', $invoiceid)
			->update(['notes' => $note]);
	}


    /*
     * Get URL of the WHMCS installation
     */
    public function getSystemUrl() {
        return Capsule::table('tblconfiguration')
            ->where('setting', 'SystemURL')
            ->value('value');
    }


    public function getLangFilePath($language=false)	{
        if ($language && file_exists(dirname(__FILE__) . '/lang/'.$language.'.php')) {
            $langFilePath = dirname(__FILE__) . '/lang/'.$language.'.php';
        }else {
            global $CONFIG;
            $language = isset($CONFIG['Language']) ? $CONFIG['Language'] : '';
            $langFilePath = dirname(__FILE__) . '/lang/'.$language.'.php';
            if (!file_exists($langFilePath)) {
                $langFilePath = dirname(__FILE__) . '/lang/english.php';
            }
        }
        return $langFilePath;
    }
}

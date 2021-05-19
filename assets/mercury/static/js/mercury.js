$("document").ready(function(){
    const systemUrl = $("#system-url").data("url");
    const staticPath = systemUrl + "assets/mercury";
    const ajaxUrl = systemUrl + "mpayment.php";
    const createTransactionUrl = ajaxUrl + "?ajax_create_transaction=true";
    const checkTransactionUrl = ajaxUrl + "?ajax_check_transaction=true";


    const mail = $("#email").data("email"),
        amount = $("#amount").data("amount"),
        orderId = $("#order_id").data("order_id"),
        currency = $("#currency").data("currency"),
        minbtc = $("#minbtc").data("minbtc"),
        mindash = $("#mindash").data("mindash"),
        mineth = $("#mineth").data("mineth"),
        checkStatusIntervalVar = $("#checkStatusInterval").data("interval");


    function finishOrderUrl(paymentdata) {
        let params = {};

        params.finishOrder = orderId;
        params.currencyCode = currency;
        params.paymentAmount = amount;


        params.uuid = paymentdata.uuid;
        params.address = paymentdata.address;
        params.crypto = paymentdata.cryptoCurrency;

        params.confirmations = paymentdata.confirmations;
        params.cryptoAmount = paymentdata.cryptoAmount;
        params.status = paymentdata.status;


        let url = window.location.pathname;
        let serializedParams = $.param( params );
        if (serializedParams.length > 0) {
            url += ((url.indexOf("?") === -1) ? "?" : "&") + serializedParams;
        }
        return url;
    }

    function successCallback(obj) {
        window.location = finishOrderUrl(obj);
    }

    var sdk = new MercurySDK({
        checkoutUrl: createTransactionUrl,
        statusUrl: checkTransactionUrl,
        checkStatusInterval: checkStatusIntervalVar,
        mount: "#mercury-cash",
        staticUrl: staticPath,
        lang: "en",
        limits: {
            BTC: minbtc,
            ETH: mineth,
            DASH: mindash,
        }
    });

    sdk.checkout(amount, currency, mail);

    sdk.on("close", (obj) => {
        if(obj.status && (obj.status === "TRANSACTION_APROVED" )) {
            successCallback(obj);
        }else{
             window.history.go(-1);
        }
    });
});


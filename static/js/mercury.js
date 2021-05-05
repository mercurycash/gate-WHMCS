$("document").ready(function(){
    var systemUrl = $("#system-url").data("url");
    var ajaxUrl = systemUrl + "mpayment.php";
    var createTransactionUrl = ajaxUrl + "?ajax_create_transaction=true";
    var checkTransactionUrl = ajaxUrl + "?ajax_check_transaction=true";


    let mail = $("#email").data("email"),
        amount = $("#amount").data("amount"),
        order_id = $("#order_id").data("order_id"),
        currency = $("#currency").data("currency"),
        minbtc = $("#minbtc").data("minbtc"),
        mindash = $("#mindash").data("mindash"),
        mineth = $("#mineth").data("mineth"),
        checkStatusInterval = $("#checkStatusInterval").data("interval");


    function successCallback(obj) {
        window.location = finishOrderUrl(obj);
    }
    function finishOrderUrl(paymentdata) {
        var params = {};

        params.finish_order = order_id;
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

    var sdk = new MercurySDK({
        checkoutUrl: createTransactionUrl,
        statusUrl: checkTransactionUrl,
        checkStatusInterval: checkStatusInterval,
        mount: "#mercury-cash",
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


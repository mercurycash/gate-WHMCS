var names = {
    'ETH' : 'ethereum',
    'BTC' : 'bitcoin',
    'DASH' : 'dash'
};

var status = false,
    stTimer,
    timerId,
    custom;

function successCallback(paymentdata) {
    console.log('Invoice paid');
    //go to success payment page
    window.location = finish_order_url(paymentdata);
}
function finish_order_url(paymentdata) {
    var params = {};
    params.finish_order = paymentdata.order_id;
    params.currencyCode = paymentdata.currencyCode;
    params.paymentAmount = paymentdata.paymentAmount;
    params.uuid = paymentdata.uuid;
    params.address = paymentdata.address;
    params.crypto = paymentdata.crypto;


    let url = window.location.pathname;
    let serializedParams = $.param( params );
    if (serializedParams.length > 0) {
        url += ((url.indexOf('?') === -1) ? '?' : '&') + serializedParams;
    }
    return url;
}

function return_to_invoice(){
    window.location = finish_order_url(paymentdata);
}

function errorCallback(data) {

}

function startTimer() {
    var presentTime = jQuery('#timer').text();
    var timeArray = presentTime.split(/[:]+/);
    var m = timeArray[0];
    var s = checkSecond((timeArray[1] - 1));
    if(s==59){m=m-1}
    jQuery('#timer').html(m + ":" + s);
    clearTimeout(stTimer);
    stTimer = setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
    if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
    if (sec < 0) {sec = "59"};
    return sec;
}



//working ok but need to rewrite get_currency method
function createList(data, closeBtn) {
    let list = '<ul class="mercury-btn-list">';
    jQuery.each( data, function(key,value){
        if(!names.hasOwnProperty(key)){
            return;
        }
        let itemClass = "";
        let note = "";
        if(value.cart_amount < value.minprice){
            itemClass = " disabled";
            note = "<span>The minimum amount that you can checkout is " + value.minprice + value.shop_currency + "</span>";
        }
        list += '<li class="' + names[key] + itemClass +'" data-name="' + names[key] + '" data-type="' + key +'">' + names[key] +
            note + '</li>';
    });
    list += '</ul>';
    jQuery('.mercury-qr-place').append(closeBtn);
    jQuery('.mercury-qr-place-body').html(list);
}

function createQr(data, crypto) {
    let cryptoAmount = data.data['cryptoAmount'],
        fiatIsoCode = data.data['fiatIsoCode'],
        fiatAmount = data.data['fiatAmount'],
        rate = data.data['rate'],
        adres = data.data['address'],
        fee = data.data['fee'];

    let header = '<div class="mercuryqpb-tit">Amount to be Paid<span>' + cryptoAmount + " " + crypto +'</span></div>',
        list = '<ul class="mercuryqpb-list">';

    list += '<li><span>Amount to be paid ' + fiatIsoCode + ':</span><span>' + fiatAmount + " " + fiatIsoCode + '</span></li>';
    list += '<li><span>Exchange rate:</span><span>' + rate +  " " + fiatIsoCode + '</span></li>';
    list += '<li><span>Total:</span><span>' + cryptoAmount + " " + crypto + '</span></li>';

    let suggest = '<ul class="mercuryqpb-list">' +
        '<li><span>Suggested network coast</span><span>' + fee + " " + crypto + '</span></li>' +
        '</ul>';

    let qrStr = "";
    qrStr += names[crypto] + ":" + adres + "?";
    qrStr += "amount=" + cryptoAmount + "&";
    qrStr += "cryptoCurrency=" + crypto;

    let img = '<div class="mercury-qr-img"></div>';

    let adres_button = '<span class="mercury-qr-adres-button">Show Wallet Address</span>';

    let closeBtn = '<div class="mercury-preloader-close mercury-preloader-close-alert">✕</div>';
    let timer = '<div class="mercury-qr-adres-timer"><span class="mercury-qr-adres-timer-name">The transaction will expire in:</span><span id="timer"></span></div>';

    jQuery('.mercury-qr-place-body').html(header);
    jQuery('.mercury-qr-place-body').append(closeBtn);
    jQuery('.mercury-qr-place-body').append(list);
    jQuery('.mercury-qr-place-body').append(img);
    jQuery('.mercury-qr-place-body').append(adres_button);
    jQuery('.mercury-qr-place-body').append(suggest);
    jQuery('.mercury-qr-place-body').append(timer);

    jQuery('#timer').html(017 + ":" + 00);
    startTimer();


    function startTimer() {
        var presentTime = jQuery('#timer').html();
        var timeArray = presentTime.split(/[:]+/);
        var m = timeArray[0];
        var s = checkSecond((timeArray[1] - 1));
        if(s==59){m=m-1}
        jQuery('#timer').html(m + ":" + s);
        setTimeout(startTimer, 1000);
    }

    function checkSecond(sec) {
        if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
        if (sec < 0) {sec = "59"};
        return sec;
    }

    function showQr(){
        jQuery('.mercury-qr-img').kjua({
            text: qrStr,
            render: 'svg',
            crisp: true,
            ecLevel: 'H',
            size: 250,
            fill: '#000',
            rounded: 50,
            mode: 'image',
            mSize: 25,
            mPosX: 50,
            mPosY: 50,
            image: "%0A%3Csvg width='56' height='58' viewBox='0 0 56 58' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M23.0866 0.734375H30.8874V3.94661C43.5782 5.83128 53.3148 16.7723 53.3148 29.9875C53.3148 33.4324 52.6531 36.7228 51.45 39.7385H55.265V57.2904H30.8874V56.0284C29.6147 56.2174 28.3123 56.3153 26.987 56.3153C12.4465 56.3153 0.65918 44.5279 0.65918 29.9875C0.65918 16.7723 10.3957 5.83128 23.0866 3.94661V0.734375Z' fill='white'/%3E%3Cpath d='M24.6582 13.9493H19.8947L16.2702 20.2946L15.7731 19.7347L15.421 19.4029L19.0455 13.182C19.0851 13.0786 19.1567 12.9906 19.2499 12.9309C19.3431 12.8712 19.4529 12.8429 19.5633 12.8502H25.176C25.0878 12.9886 25.0116 13.1343 24.9482 13.2857L24.7411 13.7004C24.7222 13.786 24.6944 13.8694 24.6582 13.9493ZM18.4449 22.7207L18.5691 22.8659L18.7141 23.0318L19.2319 23.5916L22.38 18.2209H22.8771L23.0635 17.7647L23.312 17.1841H22.0486C21.9675 17.1909 21.8886 17.2147 21.8172 17.254C21.7459 17.2933 21.6836 17.3472 21.6344 17.4122L18.4449 22.7207ZM11.2788 17.2878L16.5187 8.10162H39.9638L42.035 12.8502H30.2503C30.3682 12.9915 30.4595 13.1531 30.5195 13.3272L30.6231 13.576L30.7888 13.9493H41.8486L40.026 17.1426H32.135L32.3835 17.7233L32.5699 18.1795H40.2745C40.3733 18.1816 40.4706 18.1554 40.5551 18.104C40.6395 18.0526 40.7074 17.978 40.7509 17.8892L43.1948 13.7419C43.2063 13.7012 43.2063 13.6581 43.1948 13.6175C43.2358 13.4964 43.2358 13.3652 43.1948 13.2442L40.8544 7.33437C40.8037 7.22596 40.7208 7.13589 40.617 7.0765C40.5132 7.01711 40.3935 6.99129 40.2745 7.00259H16.2081C16.1126 7.0048 16.0192 7.03057 15.9361 7.07759C15.853 7.12462 15.7828 7.19145 15.7317 7.27216L9.35264 18.3246C9.8355 17.831 10.4444 17.4795 11.1131 17.3085L11.2788 17.2878ZM22.0486 41.9018H35.6973C35.7846 41.9318 35.8793 41.9318 35.9666 41.9018L35.4695 40.6991V40.865H30.1053C29.7435 41.1924 29.3214 41.446 28.8626 41.6115C28.5036 41.7388 28.1251 41.802 27.7442 41.7981H27.4335C27.1446 41.7788 26.8593 41.7231 26.5844 41.6322C26.1043 41.4866 25.6646 41.231 25.3003 40.8857H22.38L20.7852 38.1485C20.7725 38.4081 20.7309 38.6655 20.661 38.9158C20.661 39.1646 20.516 39.4342 20.4331 39.7245L21.5723 41.7981C21.6387 41.8503 21.7161 41.8867 21.7986 41.9047C21.8811 41.9226 21.9666 41.9216 22.0486 41.9018ZM18.8591 43.3119L18.5484 44.0376C18.485 44.2023 18.4089 44.3618 18.3206 44.5146L19.1076 45.9039C19.1489 45.9944 19.2164 46.0705 19.3013 46.1222C19.3862 46.174 19.4847 46.1991 19.584 46.1942H38.2241C38.3234 46.1991 38.4219 46.174 38.5068 46.1222C38.5917 46.0705 38.6591 45.9944 38.7005 45.9039L38.8869 45.5721C38.5977 45.5069 38.3236 45.3873 38.0791 45.2196L37.872 45.0745H19.8947L18.8591 43.3119ZM54.7724 29.6881L54.586 29.9992L42.0143 51.7515C41.9621 51.8297 41.8911 51.8935 41.8079 51.937C41.7246 51.9805 41.6318 52.0023 41.5379 52.0004H16.2081C16.1142 52.0023 16.0213 51.9805 15.9381 51.937C15.8548 51.8935 15.7838 51.8297 15.7317 51.7515L2.99429 29.7711C2.94309 29.6867 2.91602 29.5898 2.91602 29.4911C2.91602 29.3924 2.94309 29.2956 2.99429 29.2112L8.37921 20.025C8.22398 20.5882 8.22398 21.1829 8.37921 21.7461L8.48277 22.0986L4.50621 28.9623H9.87042L10.1397 30.3517L9.95327 29.9784H4.50621L16.5187 50.8391H41.2065L53.2397 29.9784H29.7739L31.845 33.8146H44.6032C44.6921 33.7978 44.7834 33.7978 44.8724 33.8146C44.9393 33.8511 44.9981 33.9009 45.0451 33.961C45.0921 34.021 45.1264 34.0901 45.1458 34.1639C45.1652 34.2377 45.1693 34.3147 45.1579 34.3901C45.1465 34.4656 45.1198 34.5379 45.0795 34.6026L43.9197 36.5726L44.2718 34.8722H43.7333C43.7333 34.9966 43.7333 35.1418 43.7333 35.2454L43.609 35.8261L43.1741 37.8997C42.8427 39.6001 42.4906 41.3212 42.1385 43.0216C42.0486 43.5668 41.7799 44.0666 41.3749 44.4421C40.9699 44.8175 40.4516 45.0473 39.9017 45.0952C39.3141 45.2059 38.7064 45.0872 38.2034 44.7634C38.0258 44.6567 37.8652 44.5239 37.727 44.3694C37.4992 44.1434 37.3165 43.8759 37.1885 43.5814C36.7536 42.4824 36.2772 41.4041 35.8216 40.3051L35.5523 39.683C35.2961 39.1162 35.1636 38.5011 35.1636 37.879C35.1636 37.2568 35.2961 36.6417 35.5523 36.0749C35.6771 35.8148 35.8226 35.5652 35.9873 35.3284L36.1323 35.1003H34.6411L33.7298 36.0749C32.5906 37.3605 31.4308 38.6047 30.2917 39.8696C29.8399 40.4076 29.2641 40.8275 28.6141 41.0931C28.222 41.2291 27.8051 41.2786 27.3921 41.2382C27.1401 41.2099 26.8908 41.1614 26.6465 41.0931C26.16 40.9323 25.7243 40.6463 25.3831 40.2636L16.0009 29.7296L17.1401 31.8032L19.5633 35.2247C19.9211 35.7177 20.166 36.2836 20.2805 36.8822C20.3951 37.4807 20.3765 38.0972 20.226 38.6877C20.1757 38.9369 20.0993 39.1802 19.9982 39.4134C19.439 40.8028 18.8384 42.1714 18.2585 43.5607C18.2119 43.6888 18.1566 43.8135 18.0928 43.934C18.0289 44.0589 17.9526 44.177 17.865 44.2865C17.6111 44.6408 17.2694 44.9228 16.8735 45.1046C16.4776 45.2864 16.0413 45.3618 15.6074 45.3233C15.0391 45.2553 14.5093 45.0007 14.1008 44.5993C13.6924 44.1978 13.4283 43.6722 13.3499 43.1045C12.9771 41.1553 12.5629 39.2268 12.1694 37.2983C11.8794 35.8053 11.5687 34.3123 11.2581 32.84L10.8853 30.9945L10.3882 28.6928C10.3882 28.4025 10.2639 28.1122 10.2225 27.8218C9.93256 26.3288 9.62189 24.8358 9.31122 23.3428C9.18695 22.7622 9.0834 22.1401 8.91771 21.5387C8.74349 20.7283 8.89301 19.8818 9.33427 19.1803C9.77554 18.4789 10.4735 17.9782 11.2788 17.7855C11.8237 17.642 12.3988 17.6618 12.9326 17.8424C13.4664 18.023 13.9356 18.3565 14.2819 18.8016C14.5304 19.0504 14.7583 19.2992 14.9861 19.5688L15.421 20.025C16.2909 20.9581 17.1608 21.9327 18.0306 22.9073C18.178 23.037 18.31 23.1831 18.4242 23.3428L21.8415 27.0961L26.4601 32.1765V32.3009L26.2116 31.8862C24.8861 29.9577 23.5813 28.0085 22.2764 26.08C21.8164 25.4494 21.535 24.7061 21.4621 23.9287C21.3892 23.1512 21.5274 22.3684 21.8622 21.6632C22.3593 20.4605 22.8771 19.2163 23.3949 18.0136C23.3949 17.8062 23.5813 17.5988 23.6641 17.4122C24.1405 16.2302 24.6375 15.069 25.1346 13.887L25.3003 13.4931C25.3614 13.3644 25.4305 13.2397 25.5074 13.1198C25.7373 12.7819 26.0461 12.5054 26.4071 12.3143C26.7681 12.1233 27.1702 12.0234 27.5785 12.0234C27.9868 12.0234 28.389 12.1233 28.7499 12.3143C29.1109 12.5054 29.4198 12.7819 29.6496 13.1198C29.7056 13.1981 29.7541 13.2814 29.7946 13.3686L30.0432 13.9285C30.4367 14.7994 30.7888 15.6704 31.1409 16.5413C31.2651 16.8109 31.3894 17.1219 31.5137 17.4122C31.6379 17.7025 31.6793 17.8062 31.7829 18.0136C32.3007 19.2163 32.777 20.4605 33.3155 21.6632C33.6434 22.4123 33.7581 23.2376 33.6469 24.0478H36.3394C36.5465 23.8405 36.7536 23.6124 36.94 23.3843L40.6473 19.237C40.9558 18.9075 41.2957 18.6088 41.6622 18.3454C42.2099 17.9346 42.8882 17.7375 43.5705 17.7909C44.2527 17.8444 44.8922 18.1446 45.3695 18.6357C45.8327 18.9981 46.1837 19.4848 46.3817 20.0391C46.5797 20.5933 46.6165 21.1925 46.4879 21.7668C46.3222 22.5134 46.1772 23.2599 46.0115 24.0064H51.5207C51.6221 24.0051 51.7218 24.0315 51.8094 24.0826C51.897 24.1337 51.969 24.2077 52.0178 24.2967L54.7724 29.2319C54.8031 29.304 54.819 29.3816 54.819 29.46C54.819 29.5384 54.8031 29.616 54.7724 29.6881ZM15.6489 29.3771C15.6489 29.3771 15.6489 29.3771 15.5453 29.3771H15.6489ZM53.3019 28.9623L51.2308 25.1054H31.99L29.9189 28.9623H53.3019Z' fill='url(%23paint0_linear)'/%3E%3Cpath opacity='0.25' d='M31.5325 35.0384C31.4371 35.0362 31.3437 35.0104 31.2606 34.9634C31.1775 34.9163 31.1073 34.8495 31.0562 34.7688L28.4051 29.7921C28.3534 29.7117 28.3259 29.6181 28.3259 29.5225C28.3259 29.4269 28.3534 29.3333 28.4051 29.2529L30.8283 25.002H30.497C30.3956 25.0007 30.2958 25.0271 30.2083 25.0782C30.1207 25.1294 30.0487 25.2034 29.9999 25.2923L27.1417 30.2483C27.09 30.3287 27.0625 30.4222 27.0625 30.5179C27.0625 30.6135 27.09 30.7071 27.1417 30.7874L29.917 35.7434C29.9682 35.8241 30.0384 35.891 30.1215 35.938C30.2046 35.985 30.298 36.0108 30.3934 36.013H33.8936L34.8049 35.0384H31.5325ZM43.69 35.0384H36.1304L35.9854 35.2665C35.8207 35.5033 35.6753 35.7529 35.5505 36.013H43.4622L43.5865 35.4324C43.5865 35.3287 43.5865 35.1835 43.5865 35.0591L43.69 35.0384Z' fill='black' fill-opacity='0.16'/%3E%3Cdefs%3E%3ClinearGradient id='paint0_linear' x1='33.0257' y1='-3.55243' x2='17.4559' y2='80.4229' gradientUnits='userSpaceOnUse'%3E%3Cstop stop-color='%233AD1BF'/%3E%3Cstop offset='1' stop-color='%23119BD2'/%3E%3C/linearGradient%3E%3C/defs%3E%3C/svg%3E%0A"
        });
    }

    showQr();

    jQuery('.mercury-qr-adres-button').on('click', function(){
        if(jQuery(this).hasClass('active')) {
            jQuery('.mercury-qr-img').html("");
            showQr();
            jQuery(this).removeClass('active');
            jQuery(this).text("Show Wallet Address");
        } else {
            jQuery('.mercury-qr-img').html(adres);
            jQuery(this).addClass('active');
            jQuery(this).text("Show QR");
        }
    });
}

// var qrRequest = function() {
function qrRequest(){
    jQuery(document).ready(function($) {
        //$( 'html, body' ).stop();

        let closeBtn = '<div class="mercury-preloader-close">✕</div>';
        let img = '<span class="span_loading"></span>';

        //check data attr (now working well but check it again later)
        let currenciesResult = $('#active_crypto_currencies').data('active_crypto_currencies');

        createList(currenciesResult, closeBtn);

        $(document).on('click', '.mercury-btn-list li:not(.disabled)', function() {
            let crypto = $(this).data('type');
            let mail = $('#email').data('email');;
            let amount = $('#amount').data('amount');
            let currency = $('#currency').data('currency');
            let systemUrl = $('#system-url').data('url');
            let ajaxUrl = systemUrl + 'mpayment.php'
            let order_id = $('#order_id').data('order_id');

            if (!crypto && !mail && !amount && !currency && !systemUrl && !ajaxUrl) {
                alert('error');
                return;
            }
            let request = $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    'order_id':order_id,
                    'crypto': crypto,
                    "mail": mail,
                    "amount":amount,
                    "currency":currency,
                    'ajax_create_transaction' : 1,
                },
                dataType: 'json',
                beforeSend: function (xhr) {
                    $('.mercury-qr-place-body').html(img);
                    $('.mercury-preloader-close').detach();
                },
            });

            request.fail(function(xhr, status, error){
                    $('.mercury-qr-place').append(closeBtn);
                    $('.mercury-qr-place-body').html("Try again latter");
                });

            request.done(function (result, textStatus, jqXHR){
                // Log a message to the console
                createQr(result, crypto);
                let paymentdata = {};

                let delay = 3000;
                let counter = 0;
                let uuid = result.data['uuid'];
                paymentdata.uuid = uuid;
                paymentdata.address = result.data['address'];
                paymentdata.crypto = crypto;

                let timerId = setTimeout(function request() {
                    $.ajax({
                        type: 'POST',
                        url: ajaxUrl,
                        data: {
                            'uuid': uuid,
                            'ajax_check_transaction' : 1,
                        },
                        dataType: 'json',
                        success: function(data) {
                            status = data.data['status'];
                        }
                    });
                    paymentdata.order_id = order_id;
                    paymentdata.paymentAmount = amount;
                    paymentdata.currencyCode = currency;

                    //TRANSACTION_PENDING
                    counter += delay;

                    if(status === 'TRANSACTION_APROVED') {
                        successCallback(paymentdata);
                    } else if (status === 'TRANSACTION_RECIEVED') {
                        clearTimeout(stTimer);
                        jQuery('.mercury-qr-adres-timer-name').addClass('.mercury-qr-adres-timer-name-agree').html("Payment received, please wait while it’s confirmed in the blockchain. Do not close this window until the payment is completed");
                        jQuery('#timer').html();
                        timerId = setTimeout(request, delay);
                    } else {
                        if(counter < 60000 * 15) {
                            timerId = setTimeout(request, delay);
                        } else {
                            jQuery('.mercury-qr-adres-timer-name').html("Transaction Expired, Try Again");
                            jQuery('#timer').html('00' + " : " + '00');
                            jQuery('.mercury-preloader-close').removeClass('mercury-preloader-close-alert');
                            clearTimeout(stTimer);
                            clearTimeut(timerId);
                        }
                    }

                    if (status === 'TRANSACTION_TIMEOUT'){
                        console.log(status);
                        if($('.mercury-preloader-close').hasClass('mercury-preloader-close-alert')){
                            $('.mercury-preloader-close').removeClass('mercury-preloader-close-alert');
                        }
                        $('.mercury-preloader-close').click();
                    }

                }, delay);
            });
            //
        });

    });

    return false;
}

jQuery(function(jQuery){
    var checkout_form = jQuery('form.whmcs-checkout');


    jQuery(document).on('click', '.mercury-preloader-close', function(){
        if(jQuery(this).hasClass('mercury-preloader-close-alert')){
            let isBoss = confirm("Please confirm if you want to cancel this transaction, by doing this you will need to start a new transaction.");
            if(isBoss){
                jQuery(this).parents('.mercury-preloader').fadeOut(300).detach();
                window.history.go(-1)
                // qrRequest();
            }
        } else {
            jQuery(this).parents('.mercury-preloader').fadeOut(300).detach();
            window.history.go(-1)
        }
    });

    // set here check for minimum value

     qrRequest();
});



<!DOCTYPE html>
<html>
<head>
    <title>Swedbank Pay Checkout</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body>

<div id="checkin"></div>
<div id="payment-menu"></div>

<script>
function init() {
    payex.hostedView.consumer({
        container: 'checkin',
        onConsumerIdentified: async (consumerIdentifiedEvent) => {
            let url = await axios.get(`{{ $payment_url }}/${consumerIdentifiedEvent.consumerProfileRef}`);
            let script = document.createElement('script');

            console.log(url);

            script.setAttribute('src', url.data);
            script.onload = function () {
                payex.hostedView.paymentMenu({
                    container: 'payment-menu',
                    culture: 'sv-SE'
                }).open();
            };

            let head = document.getElementsByTagName('head')[0];
            head.appendChild(script);
        },
    }).open();
}
</script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ $checkin }}" onload="init()"></script>

</body>
</html>

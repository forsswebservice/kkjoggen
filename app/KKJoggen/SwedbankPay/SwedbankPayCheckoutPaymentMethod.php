<?php

namespace App\KKJoggen\SwedbankPay;

use App\Models\Competitor;

class SwedbankPayCheckoutPaymentMethod extends SwedbankPayPaymentMethod
{
    protected $paymentType = 'Checkout';

    protected $intent = 'paymentOrder';

    public function url()
    {
        return '/payments/swedbank';
    }

    public function checkin(Competitor $competitor)
    {
        try {
            $result = $this->client->post('/psp/consumers', [
                'operation' => 'initiate-consumer-session',
                'language' => 'sv-SE',
                'requireShippingAddress' => false,
            ]);

            $competitor->setPaymentData('consumer', json_decode($result->getResponseBody()));
            $competitor->save();

            return $this->getOperationByRel(json_decode($result->getResponseBody(), true), 'view-consumer-identification');
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return null;
    }

    public function paymentOrder(Competitor $competitor, $payerReference)
    {
            $this->competitor = $competitor;

            $result = $this->client->post('/psp/paymentorders', $this->payload([
                'payer' => [
                    'firstname' => $competitor->firstname,
                    'lastname' => $competitor->lastname,
                    'email' => $competitor->email,
                    'msisdn' => $this->formatPhoneNumber($competitor->phone),
                    'payerReference' => $payerReference,
                ],
            ]));

            $competitor->setPaymentData('paymentorder', json_decode($result->getResponseBody()));
            $competitor->save();

            return $this->getOperationByRel(json_decode($result->getResponseBody(), true), 'view-paymentorder');

        return null;
    }

    public function formatPhoneNumber($number) {
        if(substr($number, 0, 1) != "+") {
            if(substr($number, 0, 2) == "00") {
                str_replace("00", "+", $number);
            } else if(substr($number, 0, 1) == "0") {
                $number = "+46" . substr($number, 1);
            }
        }

        return $number;
    }

    public function complete(Competitor $competitor)
    {
        if (! $competitor->getPaymentData('paymentorder') || ! $competitor->getPaymentData('paymentorder')['paymentOrder']['id']) {
            throw new \Exception('Missing payment data');
        }

        try {
            $result = $this->client->get("{$competitor->getPaymentData('paymentorder')['paymentOrder']['currentPayment']['id']}");
            $result = json_decode($result->getResponseBody(), true);

            $competitor->setPaymentData('current-payment', $result);
            $competitor->save();

            if ($result['payment']['instrument'] == 'Swish') {
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }

        return $this->validCaptureResponse($competitor, $this->capture($competitor));
    }

    private function capture(Competitor $competitor)
    {
        try {
            $result = $this->client->get("{$competitor->getPaymentData('paymentorder')['paymentOrder']['id']}");
            $result = json_decode($result->getResponseBody(), true);

            $competitor->setPaymentData('pre-capture', $result);
            $competitor->save();

            $result = $this->client->post($this->stripBaseUrl($this->getOperationByRel($result, 'create-paymentorder-capture')), $this->capturePayload($competitor));
            $result = json_decode($result->getResponseBody(), true);

            $competitor->setPaymentData('capture', $result);
            $competitor->save();

            return $result;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function reversal(Competitor $competitor)
    {
        try {
            $result = $this->client->get("{$competitor->getPaymentData('paymentorder')['paymentOrder']['id']}");
            $result = json_decode($result->getResponseBody(), true);

            $competitor->setPaymentData('pre-refund', $result);
            $competitor->save();

            $reversal_url = $this->getOperationByRel($result, 'create-paymentorder-reversal') ?: $this->getOperationByRel($result, 'create-reversal');

            $result = $this->client->post($this->stripBaseUrl($reversal_url), $this->reversalPayload($competitor));
            $result = json_decode($result->getResponseBody(), true);

            $competitor->setPaymentData('refund', $result);
            $competitor->save();

            return $result;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

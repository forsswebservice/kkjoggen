<?php

namespace App\KKJoggen\SwedbankPay;

use App\Models\Competitor;
use App\PurchaseItem;
use Illuminate\Support\Facades\URL;
use SwedbankPay\Api\Client\Client;

class SwedbankPayPaymentMethod
{
    protected $paymentType;

    protected $intent = 'paymentOrder';

    protected $client;

    public function __construct()
    {
        $this->client = new Client();

        $this->client->setAccessToken(config('services.swedbank.token'));
        $this->client->setPayeeId(config('services.swedbank.payee_id'));

        $this->client->setMode(config('services.swedbank.test_mode') ? Client::MODE_TEST : Client::MODE_PRODUCTION);
    }

    public function url()
    {
        return '/payments/swedbank';
    }


    public function complete(Competitor $competitor)
    {
        (new SwedbankPayCheckoutPaymentMethod())->complete($competitor);
    }

    protected function validResponse($operation, Competitor $competitor, $result)
    {
        try {
            return $result[$operation]['transaction']['state'] == 'Completed' || $result[$operation]['transaction']['state'] == 'AwaitingActivity';
        } catch (\Throwable $e) {
            throw new \Exception("Invalid response for purchase #{$purchase->id}");
        }
    }

    protected function validCaptureResponse(Competitor $competitor, $result)
    {
        return $this->validResponse('capture', $competitor, $result);
    }

    protected function validReversalResponse(Competitor $competitor, $result)
    {
        if (! $result) {
            return false;
        }

        return $this->validResponse('reversal', $competitor, $result);
    }

    public function callback(Competitor $competitor)
    {
        info(request()->all());

        $result = $this->client->get("{$competitor->getPaymentData('paymentorder')['paymentOrder']['id']}");
        $result = json_decode($result->getResponseBody(), true);

        $competitor->setPaymentData('callback', $result);
        $competitor->save();

        dd($result);
    }

    public function payload($additional_data = [])
    {
        return [
            'paymentorder' => $additional_data + [
                'operation' => 'Purchase',
                'currency' => 'SEK',
                'amount' => round($this->competitor->price * 100),
                'vatAmount' => round(($this->competitor->price - ($this->competitor->price * 0.8)) * 100),
                'description' => "Order #{$this->competitor->id}",
                'payerReference' => $this->competitor->id,
                'userAgent' => request()->header('User-Agent'),
                'language' => 'sv-SE',
                'productName' => 'Checkout3',
                'implementation' => 'PaymentsOnly',
                'orderItems' => $this->getPurchaseItemsPayload(),
                'urls' => [
                    'hostUrls' => [url('/')],
                    'completeUrl' => URL::signedRoute('swedbank.complete', $this->competitor),
                    'cancelUrl' => URL::signedRoute('swedbank.cancel', $this->competitor),
                    'callbackUrl' => URL::signedRoute('swedbank.callback', $this->competitor),
                    'termsOfServiceUrl' => url('/villkor'),
                ],
                'payeeInfo' => [
                    'payeeId' => config('services.swedbank.payee_id'),
                    'payeeReference' => substr(str_replace('-', '', $this->competitor->payee_reference), 0, 30),
                ],
            ],
        ];
    }

    public function capturePayload(competitor $competitor)
    {
        return [
            'transaction' => [
                'amount' => round($competitor->price * 100),
                'vatAmount' => round(($competitor->price - ($competitor->price * 0.8)) * 100),
                'description' =>  "Löpnummer #{$competitor->id}",
                'payeeReference' => substr(str_replace('-', '', $competitor->newPayeeReference()), 0, 30),
                'receiptReference' => substr(str_replace('-', '', $competitor->newPayeeReference()), 0, 30),
                'orderItems' => $this->getPurchaseItemsPayload($competitor),
            ],
        ];
    }

    public function getPurchaseItemsPayload(Competitor $competitor = null)
    {
        $competitor = $competitor ?? $this->competitor;

        $items = [];

        foreach ($competitor->children as $children) {
            $items[] = [
                'reference' => $children->id,
                'name' => "{$children->firstname} {$children->lastname}",
                'type' => 'SERVICE',
                'class' => 'fee',
                'quantity' => 1,
                'quantityUnit' => 'st',
                'unitPrice' => round($children->price * 100),
                'vatPercent' => 25,
                'amount' => round($children->price * 100),
                'vatAmount' => round(($children->price - ($children->price * 0.8)) * 100),
            ];
        }

        return $items;
    }

    public function refund(Competitor $competitor)
    {
        if (! $competitor->getPaymentData(strtolower($this->intent)) || ! $competitor->getPaymentData(strtolower($this->intent))['paymentOrder']['id']) {
            throw new \Exception('Missing payment data, the purchase may not be reversed');
        }

        return $this->validReversalResponse($competitor, $this->reversal($competitor));
    }

    public function getOperationByRel(array $data, $rel, $single = true)
    {
        if (! isset($data['operations'])) {
            return false;
        }

        $operations = $data['operations'];
        $operation = array_filter($operations, function ($value) use ($rel) {
            return is_array($value) && $value['rel'] === $rel;
        }, ARRAY_FILTER_USE_BOTH);

        if (count($operation) > 0) {
            $operation = array_shift($operation);

            return $single ? $operation['href'] : $operation;
        }

        return false;
    }

    public function reversalPayload(Competitor $competitor, $payeeReference = null)
    {
        if ($payeeReference === null) {
            $payeeReference = substr(str_replace('-', '', $competitor->newPayeeReference()), 0, 30);
        }

        return [
            'transaction' => [
                'amount' => round($competitor->price * 100),
                'vatAmount' => round(($competitor->price - ($competitor->price * 0.8)) * 100),
                'description' =>  "Kreditering köp #{$competitor->id}",
                'payeeReference' => $payeeReference,
                'receiptReference' => $payeeReference,
                'orderItems' => [
                    [
                        'reference' => $competitor->id,
                        'name' => "{$competitor->firstname} {$competitor->lastname}",
                        'type' => 'SERVICE',
                        'class' => 'fee',
                        'quantity' => 1,
                        'quantityUnit' => 'st',
                        'unitPrice' => round($competitor->price * 100),
                        'vatPercent' => 25,
                        'amount' => round($competitor->price * 100),
                        'vatAmount' => round(($competitor->price - ($competitor->price * 0.8)) * 100),
                    ],
                ],
            ],
        ];
    }

    public function stripBaseUrl($url)
    {
        return str_replace(config('services.swedbank.test_mode') ? Client::MODE_TEST_URL : Client::MODE_PRODUCTION_URL, '', $url);
    }
}

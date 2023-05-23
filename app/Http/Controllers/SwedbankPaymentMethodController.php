<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\KKJoggen\SwedbankPay\SwedbankPayCheckoutPaymentMethod;
use App\Models\Competitor;

class SwedbankPaymentMethodController extends Controller
{

    /*
    public function index(Competitor $competitor)
    {
        if($competitor->settled_at) {
            return redirect("/betala/{$competitor->id}");
        }

        $checkin = (new SwedbankPayCheckoutPaymentMethod())->checkin($competitor);

        return view('customer.paymentmethod.swedbankpaymentmethod.index', [
            'competitor' => $competitor,
            'checkin' => $checkin,
            'payment_url' => url()->route('swedbank.payment_url', ['competitor' => $competitor, 'payerReference' => '']),
        ]);
    }
    */

    public function index(Competitor $competitor)
    {
        if($competitor->settled_at) {
            return redirect("/betala/{$competitor->id}");
        }

        return redirect($this->paymentUrl($competitor, $competitor->newPayeeReference()));
    }

    public function paymentUrl(Competitor $competitor, $payerReference)
    {
        return (new SwedbankPayCheckoutPaymentMethod())->paymentOrder($competitor, $payerReference);
    }

    public function complete(Competitor $competitor)
    {
        try {
            if ((new SwedbankPayCheckoutPaymentMethod())->complete($competitor)) {
                $competitor->update(['settled_at' => now()]);
            } else {
                $competitor->update(['canceled_at' => now()]);

                throw new \Exception("An error occured when completing your purchase #{$competitor->id}.");
            }
        } catch (\Throwable $e) {
            flash()->error($e->getMessage());

            return redirect("/betala/{$competitor->id}");
        }

        return redirect("/bekraftelse/{$competitor->id}");
    }

    public function cancel(Competitor $competitor)
    {
        try {
            $competitor->update(['canceled_at' => now()]);

            flash()->info(transEdit('Ditt köp har avbrutits.'));
        } catch (\Throwable $e) {
            flash()->error($e->getMessage());
        }

        return redirect("/betala/{$competitor->id}");
    }

    public function callback(Competitor $competitor)
    {
        if (! request()->hasValidSignature()) {
            abort(401);
        }

        try {
            (new SwedbankPayCheckoutPaymentMethod())->callback($competitor);
        } catch (\Throwable $e) {
        }

        return 'OK';
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\KKJoggen\SwedbankPay\SwedbankPayCheckoutPaymentMethod;
use App\Models\Competitor;
use Illuminate\Support\Facades\Log;

class SwedbankPaymentMethodController extends Controller
{
    public function index(Competitor $competitor)
    {
        if(!request()->hasValidSignature()) {
            abort(401);
        }

        if($competitor->settled_at) {
            return redirect($competitor->getConfirmationURL());
        }

        return redirect($this->paymentUrl($competitor, $competitor->newPayeeReference()));
    }

    public function paymentUrl(Competitor $competitor, $payerReference)
    {
        return (new SwedbankPayCheckoutPaymentMethod())->paymentOrder($competitor, $payerReference);
    }

    public function complete(Competitor $competitor)
    {
        if(!request()->hasValidSignature()) {
            abort(401);
        }

        try {
            if ((new SwedbankPayCheckoutPaymentMethod())->complete($competitor)) {
                $competitor->sendConfirmations();

                $competitor->update(['settled_at' => now()]);
                $competitor->children->each(fn($c) => $c->update(['settled_at' => now()]));
            } else {
                $competitor->update(['canceled_at' => now()]);
                $competitor->children->each(fn($c) => $c->update(['canceled_at' => now()]));

                throw new \Exception("Ett fel uppstod vid slutförandet av din betalning #{$competitor->id}.");
            }
        } catch (\Throwable $e) {
            flash()->error($e->getMessage());

            return redirect($competitor->getPaymentURL());
        }

        return redirect($competitor->getConfirmationURL());
    }

    public function cancel(Competitor $competitor)
    {
        if(!request()->hasValidSignature()) {
            abort(401);
        }

        try {
            $competitor->update(['canceled_at' => now()]);

            flash()->info('Ditt köp har avbrutits.');
        } catch (\Throwable $e) {
            flash()->error($e->getMessage());
        }

        return redirect($competitor->getPaymentURL());
    }

    public function callback(Competitor $competitor)
    {
        if (! request()->hasValidSignature() && !app()->isLocal()) {
            abort(401);
        }

        try {
            if(!(new SwedbankPayCheckoutPaymentMethod())->callback($competitor)) {
                return 'FAILED';
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }

        return 'OK';
    }
}

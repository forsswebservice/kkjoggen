<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Competitor $competitor)
    {
        if(!request()->hasValidSignature()) {
            abort(401);
        }

        if($competitor->settled_at) {
            return view('payment.already-payed', ['competitor' => $competitor]);
        }

        return view('payment.create', ['competitor' => $competitor]);
    }
}

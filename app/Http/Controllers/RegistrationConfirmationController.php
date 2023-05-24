<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;

class RegistrationConfirmationController extends Controller
{
    public function show(Competitor $competitor)
    {
        if(!request()->hasValidSignature()) {
            abort(401);
        }

        if(!$competitor->settled_at) {
            return view('payment.not-payed', ['competitor' => $competitor]);
        }

        return view('payment.confirmation', ['competitor' => $competitor]);
    }
}

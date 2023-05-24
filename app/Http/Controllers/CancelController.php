<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;

class CancelController extends Controller
{
    public function create(Competitor $competitor)
    {
        if(!request()->hasValidSignature()) {
            abort(401);
        }

        if($competitor->settled_at) {
            return view('payment.already-payed', ['competitor' => $competitor]);
        }

        if(!$competitor->canceled_at) {
            $competitor->update(['canceled_at' => now()]);
        }

        flash()->info('Din anmälan har avbrutits.');

        return redirect('/');
    }
}

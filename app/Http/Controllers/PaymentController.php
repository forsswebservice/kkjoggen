<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Competitor $competitor)
    {
        if($competitor->settled_at) {
            return redirect('payment.already_settled');
        }

        return view('payment.create', ['competitor' => $competitor]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CompetitionYear;
use App\Models\Competitor;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function create()
    {
        $year = CompetitionYear::current()->first();

        if(!$year || !$year->is_open) {
            return view('registration.closed', ['year' => $year]);
        }

        return view('registration.create', [
            'year' => $year,
            'free_when_local_classes' => $year->competitionClasses->where('is_free_when_local'),
        ]);
    }

    public function store()
    {
        $year = CompetitionYear::current()->first();

        if(!$year) {
            return redirect()->route('registration.closed');
        }

        $validation = [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'accepts' => 'accepted',
        ];

        $validation_messages = [
            'firstname' => 'Förnamn på betalare är obligatoriskt.',
            'lastname' => 'Efternamn på betalare är obligatoriskt',
            'address' => 'Adress till betalare är obligatoriskt.',
            'zip_code' => 'Postnummer till betalare är obligatoriskt.',
            'city' => 'Ort till betalare är obligatoriskt.',
            'phone' => 'Telefonnummer till betalare är obligatoriskt.',
            'accepts' => 'Du måste acceptera villkoren för att göra en anmälan.',
        ];

        for($i = 1; $i <= (int) request()->num_competitors; $i++) {
            $validation += [
                "firstname{$i}" => 'required',
                "lastname{$i}" => 'required',
                "born{$i}" => 'required',
                "team{$i}" => 'required',
                "competition_class_id{$i}" => 'required',
                "shirt_size{$i}" => 'nullable',
                "shirt_name{$i}" => 'nullable',
                "previous_starts{$i}" => 'nullable',
                "time_10k{$i}" => 'nullable',
                "is_local{$i}" => 'nullable',
            ];

            $validation_messages += [
                "firstname{$i}" => "Förnamn på löpare #{$i} är obligatoriskt.",
                "lastname{$i}" => "Efternamn på löpare #{$i} är obligatoriskt.",
                "born{$i}" => "Födelseår på löpare #{$i} är obligatoriskt.",
                "team{$i}" => "Företag/förening/hemort på löpare #{$i} är obligatoriskt.",
                "competition_class_id{$i}" => "Löparklass på löpare #{$i} är obligatoriskt.",
            ];
        }

        $validator = Validator::make(request()->all(), $validation, $validation_messages);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $payer = $year->competitors()
            ->save(new Competitor($validator
                ->safe()
                ->only([
                    'firstname',
                    'lastname',
                    'address',
                    'zip_code',
                    'city',
                    'phone',
                    'email',
                ])
            ));

        for($i = 1; $i <= (int) request()->num_competitors; $i++) {
            $payer->children()->save($year
                ->competitors()
                ->save(new Competitor([
                    'firstname' => request()->input("firstname{$i}"),
                    'lastname' => request()->input("lastname{$i}"),
                    'born' => request()->input("born{$i}"),
                    'team' => request()->input("team{$i}"),
                    'competition_class_id' => request()->input("competition_class_id{$i}"),
                    'shirt_size' => request()->input("shirt_size{$i}"),
                    'shirt_name' => request()->input("shirt_name{$i}"),
                    'previous_starts' => request()->input("previous_starts{$i}") ?? 0,
                    'time_10k' => request()->input("time_10k{$i}"),
                    'is_local' => (int) request()->input("is_local{$i}"),
                ])
            ));
        }

        $payer->calculatePrices();

        if($payer->price == 0) {
            $payer->update(['settled_at' => now()]);
            $payer->sendConfirmations();

            return redirect($payer->getConfirmationURL());
        }

        return redirect($payer->getPaymentURL());
    }
}

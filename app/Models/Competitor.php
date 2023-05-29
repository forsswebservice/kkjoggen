<?php

namespace App\Models;

use App\Mail\RegistrationConfirmation;
use App\Traits\StoresSumsInCents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Ramsey\Uuid\Uuid;

class Competitor extends Model
{
    use HasFactory, SoftDeletes, StoresSumsInCents;

    protected $guarded = [];

    protected $casts = [
        'payment_data' => 'array',
        'settled_at' => 'datetime',
        'canceled_at' => 'datetime',
        'is_local' => 'boolean',
    ];

    protected $sums = [
        'price',
        'rebate',
    ];

    public function competitionClass()
    {
        return $this->belongsTo(CompetitionClass::class)->withTrashed();
    }

    public function competitionYear()
    {
        return $this->belongsTo(CompetitionYear::class)->withTrashed();
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id')->withTrashed();
    }

    public function calculatePrices()
    {
        if($this->parent_id) {
            return false;
        }

        $total_price = 0;
        $total_rebate = 0;
        $children_grouped_by_class = $this->children->groupBy('competition_class_id');
        $children_count = $this->children->count();

        foreach($this->children as $child) {
            $rebate = 0;

            if($child->is_local && $child->competitionClass->is_free_when_local) {
                $price = 0;
            } elseif(count($children_grouped_by_class[$child->competitionClass->id]) > 1) {
                $price = $child->competitionClass->price_multiple;
            } else {
                $price = $child->competitionClass->price;
            }

            if($child->competitionYear->late_registration_at && $child->competitionYear->late_registration_at->lt(now())) {
                $price += $child->competitionClass->price_late;
            }

            if($child->competitionYear->rebate_from > 0 && $children_count >= $child->competitionYear->rebate_from) {
                $rebate = round($price * ($child->competitionYear->rebate_percent / 100), 2);
                $total_rebate += $rebate;
                $price -= $rebate;
            }

            $child->update([
                'price' => $price,
                'rebate' => $rebate,
            ]);

            $total_price += $price;
        }

        $this->update([
            'price' => $total_price,
            'rebate' => $total_rebate,
        ]);

        return $total_price;
    }

    public function newPayeeReference()
    {
        // Payex has a limit of max 35 characters in the payee reference
        $this->update(['payee_reference' => substr(Uuid::uuid4()->toString(), 0, 35)]);

        return $this->payee_reference;
    }

    public function setPaymentData($key, $value)
    {
        $paymentData = $this->payment_data;

        if (! is_array($paymentData)) {
            $paymentData = [];
        }

        $paymentData[$key] = $value;

        $this->payment_data = $paymentData;
    }

    public function getPaymentData($key)
    {
        if (! is_array($this->payment_data) || ! array_key_exists($key, $this->payment_data)) {
            return;
        }

        return $this->payment_data[$key];
    }

    public function removePaymentData($key)
    {
        if (array_key_exists($key, $this->payment_data)) {
            return $this->payment_data[$key];
        }

        return false;
    }

    public function getConfirmationURL()
    {
        return URL::signedRoute('confirmation', ['competitor' => $this->id]);
    }

    public function getPaymentURL()
    {
        return URL::signedRoute('payment', ['competitor' => $this->id]);
    }

    public function getCancelURL()
    {
        return URL::signedRoute('cancel', ['competitor' => $this->id]);
    }

    public function getStartPaymentURL()
    {
        return URL::signedRoute('swedbank.index', ['competitor' => $this->id]);
    }

    public function sendConfirmations()
    {
        if(!$this->email) {
            return;
        }

        try {
            Mail::to($this)->queue(new RegistrationConfirmation($this));
        } catch(\Throwable $e) {
            Log::error($e);
        }
    }

    public function getStatus()
    {
        if($this->parent) {
            return null;
        }

        if($this->canceled_at) {
            return 'Avbruten';
        }

        if($this->settled_at) {
            return 'Betald';
        }

        return 'Ej betald';
    }
}

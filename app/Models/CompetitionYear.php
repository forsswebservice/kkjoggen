<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetitionYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'late_registration_at' => 'datetime',
        'is_current' => 'boolean',
    ];

    protected $appends = [
        'is_open',
    ];

    public function competitionClasses()
    {
        return $this->hasMany(CompetitionClass::class);
    }

    public function competitors()
    {
        return $this->hasMany(Competitor::class);
    }

    public function scopeCurrent(Builder $query)
    {
        return $query->where('is_current', true);
    }

    public function getIsOpenAttribute()
    {
        if(!$this->opens_at) {
            return false;
        }

        if($this->opens_at->gt(now())) {
            return false;
        }

        if($this->closes_at && $this->closes_at->lt(now())) {
            return false;
        }

        return true;
    }
}

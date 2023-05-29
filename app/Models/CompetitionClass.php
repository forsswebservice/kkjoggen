<?php

namespace App\Models;

use App\Traits\StoresSumsInCents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetitionClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function competitionYear()
    {
        return $this->belongsTo(CompetitionYear::class)->withTrashed();
    }

    public function competitors()
    {
        return $this->hasMany(Competitor::class);
    }
}

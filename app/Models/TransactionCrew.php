<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCrew extends Model
{
    protected $fillable = [
        'tx_id',
        'crew_id'
    ];

    public function crew() {
        return $this->belongsTo(Crew::class, 'crew_id');
    }
}

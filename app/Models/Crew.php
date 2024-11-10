<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_crew', 'crew_id', 'tx_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'client_id',
        'date',
        'check_in',
        'check_out',
        'created_user',
        'before',
        'after',
        'done',
    ];

    public function client() {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function crews() {
        return $this->belongsToMany(Crew::class, 'transaction_crews', 'tx_id', 'crew_id');
    }

    public function cr() {
        return $this->hasMany(TransactionCrew::class, 'tx_id');
    }

    public function services() {
        return $this->hasMany(Service::class, 'tx_id');
    }

    public function payment() {
        return $this->hasOne(Payment::class, 'tx_id');
    }
}

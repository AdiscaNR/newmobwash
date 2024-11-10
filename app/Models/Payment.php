<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tx_id',
        'payment_type',
        'total',
        'image'
    ];

    public function type() {
        return $this->belongsTo(PaymentType::class, 'payment_type');
    }
}

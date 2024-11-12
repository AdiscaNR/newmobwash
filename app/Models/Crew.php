<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crew extends Model
{
    use HasFactory;
    use SoftDeletes; // Mengaktifkan soft delete

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name'
    ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_crew', 'crew_id', 'tx_id');
    }
}

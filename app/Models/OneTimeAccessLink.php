<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OneTimeAccessLink extends Model
{
    protected $fillable = [
        'customer_id',
        'order_id',
        'token',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

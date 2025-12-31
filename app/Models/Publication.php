<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Publication extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'name',
        'status'
    ];

    public function customers()
    {
        return $this->belongsToMany(\App\Models\Customer::class, 'customer_publication')
            ->withPivot('attachment_count')
            ->withTimestamps();
    }

    /* ===============================
       CREATE
    =============================== */
    public static function createPublication($data)
    {
        return DB::table('publications')->insert([
            'name'       => $data['name'],
            'status'     => $data['status'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ...existing code for other methods...
}

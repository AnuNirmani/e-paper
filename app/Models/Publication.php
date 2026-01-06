<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Publication extends Model
{
    protected $table = 'publications';

    protected $fillable = [
        'name',
        'status',
        'deleted_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
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
            'deleted_at' => null,
        ]);
    }

    /* ===============================
       GET ACTIVE PUBLICATIONS
    =============================== */
    public static function getActivePublications()
    {
        return DB::table('publications')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
    }

    public static function softDeletePublication($id)
    {
        return DB::table('publications')
            ->where('id', $id)
            ->update([
                'status' => -1,
                'deleted_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }
}

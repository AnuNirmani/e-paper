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

    /* ===============================
       READ
    =============================== */

    // Get all (except deleted)
    public static function getAllPublications()
    {
        return DB::table('publications')
            ->where('status', '!=', -1)
            ->orderBy('id', 'desc')
            ->get();
    }

    // Get only active
    public static function getActivePublications()
    {
        return DB::table('publications')
            ->where('status', 1)
            ->orderBy('name')
            ->get();
    }

    // Get single publication
    public static function getPublicationById($id)
    {
        return DB::table('publications')
            ->where('id', $id)
            ->where('status', '!=', -1)
            ->first();
    }

    /* ===============================
       UPDATE
    =============================== */
    public static function updatePublication($id, $data)
    {
        return DB::table('publications')
            ->where('id', $id)
            ->update([
                'name'       => $data['name'],
                'status'     => $data['status'],
                'updated_at' => now(),
            ]);
    }

    /* ===============================
       SOFT DELETE (status = -1)
    =============================== */
    public static function deletePublication($id)
    {
        return DB::table('publications')
            ->where('id', $id)
            ->update([
                'status'     => -1,
                'updated_at' => now(),
            ]);
    }

    /* ===============================
       STATUS TOGGLE
    =============================== */
    public static function changeStatus($id, $status)
    {
        return DB::table('publications')
            ->where('id', $id)
            ->update([
                'status'     => $status,
                'updated_at' => now(),
            ]);
    }
}

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
        // Show all except deleted (-1)
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
        // Allow fetching inactive (0) and active (1), but not deleted (-1)
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
        // Only allow status 0 (inactive) or 1 (active) for update, never -1 (deleted)
        $status = ($data['status'] == 1) ? 1 : 0;
        return DB::table('publications')
            ->where('id', $id)
            ->update([
                'name'       => $data['name'],
                'status'     => $status,
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

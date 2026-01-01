<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Copy extends Model
{
    protected $table = 'copies';

    protected $fillable = [
        'customer_id',
        'publication_id',
        'message'
    ];

    /* =========================
        RELATIONSHIPS
    ========================== */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /* =========================
        CRUD QUERIES
    ========================== */

    public static function createCopy($data)
    {
        return DB::table('copies')->insert([
            'customer_id'    => $data['customer_id'],
            'publication_id' => $data['publication_id'],
            'message'        => $data['message'] ?? null,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public static function getAllCopies()
    {
        return DB::table('copies')
            ->join('customers', 'copies.customer_id', '=', 'customers.id')
            ->join('publications', 'copies.publication_id', '=', 'publications.id')
            ->select(
                'copies.*',
                'customers.first_name as customer_first_name',
                'publications.name as publication_name'
            )
            ->orderBy('copies.id', 'desc')
            ->get();
    }

    public static function getCopyById($id)
    {
        return DB::table('copies')
            ->join('customers', 'copies.customer_id', '=', 'customers.id')
            ->join('publications', 'copies.publication_id', '=', 'publications.id')
            ->select(
                'copies.*',
                'customers.first_name as customer_first_name',
                'publications.name as publication_name'
            )
            ->where('copies.id', $id)
            ->first();
    }

    public static function deleteCopy($id)
    {
        return DB::table('copies')
            ->where('id', $id)
            ->delete();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Customer extends Model
{
    public function publications()
    {
        return $this->belongsToMany(Publication::class, 'customer_publication')
            ->withPivot('attachment_count')
            ->withTimestamps();
    }
    protected $table = 'customers';

    protected $casts = [
        'starting_date' => 'datetime',
        'ending_date' => 'datetime',
        'status' => 'integer',
        'payment_receipt' => 'boolean',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'city',
        'province',
        'zip_code',
        'country',
        'phone',
        'whatsapp_number',
        'email',
        'starting_date',
        'ending_date',
        'duration',
        'status',
        'payment_method',
        'payment_amount',
        'payment_receipt',
    ];

    /* =========================
       QUERY METHODS
       ========================= */

    // Scope: Search customers
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    // Get active customer count
    public static function getActiveCount()
    {
        return self::where('status', 1)
            ->whereNull('deleted_at')
            ->count();
    }

    // Activate all customers
    public static function activateAll()
    {
        return self::where('status', '!=', 1)->update(['status' => 1]);
    }

    // Deactivate all customers
    public static function deactivateAll()
    {
        return self::where('status', '!=', 0)->update(['status' => 0]);
    }

    // Get all customers
    public static function getAllCustomers()
    {
        return self::orderBy('id', 'desc')->get();
    }

    // Get only active customers
    public static function getActiveCustomers()
    {
        return self::where('status', 1)
            ->whereNull('deleted_at')
            ->get();
        return DB::table('customers')
        ->where('status', 1)
        ->select('id', 'first_name')
        ->get();
    }

    // Get single customer by ID
    public static function getCustomerById($id)
    {
        return self::where('id', $id)->first();
    }

    // Store new customer
    public static function storeCustomer($data)
    {
        return self::create([
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'address'         => $data['address'] ?? null,
            'city'            => $data['city'] ?? null,
            'province'        => $data['province'] ?? null,
            'zip_code'        => $data['zip_code'] ?? null,
            'country'         => $data['country'] ?? null,
            'phone'           => $data['phone'] ?? null,
            'whatsapp_number' => $data['whatsapp_number'] ?? null,
            'email'           => $data['email'] ?? null,
            'starting_date'   => $data['starting_date'] ?? null,
            'ending_date'     => $data['ending_date'] ?? null,
            'duration'        => $data['duration'] ?? 0,
            'status'          => $data['status'] ?? 1,
            'payment_method'  => $data['payment_method'] ?? null,
            'payment_amount'  => $data['payment_amount'] ?? null,
            'payment_receipt' => $data['payment_receipt'] ?? false,
        ]);
    }

    // Update customer
    public static function updateCustomer($id, $data)
    {
        return self::where('id', $id)->update([
            'first_name'      => $data['first_name'],
            'last_name'       => $data['last_name'],
            'address'         => $data['address'] ?? null,
            'city'            => $data['city'] ?? null,
            'province'        => $data['province'] ?? null,
            'zip_code'        => $data['zip_code'] ?? null,
            'country'         => $data['country'] ?? null,
            'phone'           => $data['phone'] ?? null,
            'whatsapp_number' => $data['whatsapp_number'] ?? null,
            'email'           => $data['email'] ?? null,
            'starting_date'   => $data['starting_date'] ?? null,
            'ending_date'     => $data['ending_date'] ?? null,
            'duration'        => $data['duration'] ?? 0,
            'status'          => $data['status'],
            'payment_method'  => $data['payment_method'] ?? null,
            'payment_amount'  => $data['payment_amount'] ?? null,
            'payment_receipt' => $data['payment_receipt'] ?? false,
        ]);
    }

    // Delete customer
    public static function deleteCustomer($id)
    {
        return self::where('id', $id)->update([
            'status' => -1,
            'deleted_at' => Carbon::now(),
        ]);
    }

    // Change customer status
    public static function changeStatus($id, $status)
    {
        return self::where('id', $id)->update([
            'status' => $status
        ]);
    }


}

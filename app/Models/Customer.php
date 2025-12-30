<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'city',
        'province',
        'zip_code',
        'country',
        'phone',
        'email',
        'starting_date',
        'ending_date',
        'status',
    ];

    /* =========================
       QUERY METHODS
       ========================= */

    // Get all customers
    public static function getAllCustomers()
    {
        return self::orderBy('id', 'desc')->get();
    }

    // Get only active customers
    public static function getActiveCustomers()
    {
        return self::where('status', 1)->get();
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
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'address'        => $data['address'] ?? null,
            'city'           => $data['city'] ?? null,
            'province'       => $data['province'] ?? null,
            'zip_code'       => $data['zip_code'] ?? null,
            'country'        => $data['country'] ?? null,
            'phone'          => $data['phone'] ?? null,
            'email'          => $data['email'] ?? null,
            'starting_date'  => $data['starting_date'] ?? null,
            'ending_date'    => $data['ending_date'] ?? null,
            'status'         => $data['status'] ?? 1,
        ]);
    }

    // Update customer
    public static function updateCustomer($id, $data)
    {
        return self::where('id', $id)->update([
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'address'        => $data['address'] ?? null,
            'city'           => $data['city'] ?? null,
            'province'       => $data['province'] ?? null,
            'zip_code'       => $data['zip_code'] ?? null,
            'country'        => $data['country'] ?? null,
            'phone'          => $data['phone'] ?? null,
            'email'          => $data['email'] ?? null,
            'starting_date'  => $data['starting_date'] ?? null,
            'ending_date'    => $data['ending_date'] ?? null,
            'status'         => $data['status'],
        ]);
    }

    // Delete customer
    public static function deleteCustomer($id)
    {
        return self::where('id', $id)->delete();
    }

    // Change customer status
    public static function changeStatus($id, $status)
    {
        return self::where('id', $id)->update([
            'status' => $status
        ]);
    }
}

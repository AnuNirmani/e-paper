<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::getAllCustomers();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:255',
            'email'         => 'required|email',
            'starting_date' => 'required|date',
            'ending_date'   => 'required|date|after_or_equal:starting_date',
            'country'       => 'required|string|max:255',
            'status'        => 'required|in:1,0',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'province'      => 'nullable|string|max:255',
            'zip_code'      => 'nullable|string|max:255',
        ]);
        Customer::storeCustomer($validated);
        return redirect()->route('customers.index')
            ->with('success', 'Customer added');
    }

    public function edit($id)
    {
        $customer = Customer::getCustomerById($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:255',
            'email'         => 'required|email',
            'starting_date' => 'required|date',
            'ending_date'   => 'required|date|after_or_equal:starting_date',
            'country'       => 'required|string|max:255',
            'status'        => 'required|in:1,0',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'province'      => 'nullable|string|max:255',
            'zip_code'      => 'nullable|string|max:255',
        ]);
        Customer::updateCustomer($id, $validated);
        return redirect()->route('customers.index')
            ->with('success', 'Customer updated');
    }

    public function destroy($id)
    {
        Customer::deleteCustomer($id);
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted');
    }
}


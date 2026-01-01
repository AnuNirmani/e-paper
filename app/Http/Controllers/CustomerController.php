<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $query = Customer::query();
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('whatsapp_number', 'like', "%$search%");
            });
        }
        $customers = $query->orderBy('id', 'desc')->get();
        $activeCount = Customer::where('status', 1)->count();

        // Get all publications and their active account counts
        $publicationStats = [];
        $publications = \App\Models\Publication::orderBy('name')->get();
        foreach ($publications as $publication) {
            $activeAccounts = $publication->customers()->where('status', 1)->count();
            $publicationStats[] = [
                'name' => $publication->name,
                'active_accounts' => $activeAccounts
            ];
        }

        return view('customers.index', compact('customers', 'activeCount', 'publicationStats'));
    }

    public function create()
    {
        $publications = \App\Models\Publication::where('status', 1)->orderBy('name')->get();
        return view('customers.create', compact('publications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'email'           => 'required|email',
            'starting_date'   => 'required|date',
            'ending_date'     => 'required|date|after_or_equal:starting_date',
            'country'         => 'required|string|max:255',
            'status'          => 'required|in:1,0',
            'address'         => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:255',
            'province'        => 'nullable|string|max:255',
            'zip_code'        => 'nullable|string|max:255',
            'duration'        => 'required|integer|min:0',
            'payment_method'  => 'required|in:online,bank_transfer',
            'payment_amount'  => 'required|numeric|min:0',
            'payment_receipt' => 'required|boolean',
        ]);
        $customer = Customer::storeCustomer($validated);
        // Handle publications and attachment counts
        $pubData = [];
        if ($request->has('publications')) {
            foreach ($request->input('publications') as $pubId => $pub) {
                if (isset($pub['selected'])) {
                    $pubData[$pubId] = ['attachment_count' => $pub['attachment_count'] ?? 1];
                }
            }
        }
        if (!empty($pubData)) {
            $customer->publications()->sync($pubData);
        }
        return redirect()->route('customers.index')
            ->with('success', 'Customer added');
    }


    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $publications = \App\Models\Publication::where('status', 1)->orderBy('name')->get();
        // Get attached publications and their attachment counts
        $customerPublications = $customer->publications()->pluck('attachment_count', 'publication_id')->toArray();
        return view('customers.edit', compact('customer', 'publications', 'customerPublications'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:255',
            'email'           => 'required|email',
            'starting_date'   => 'required|date',
            'ending_date'     => 'required|date|after_or_equal:starting_date',
            'country'         => 'required|string|max:255',
            'status'          => 'required|in:1,0',
            'address'         => 'nullable|string|max:255',
            'city'            => 'nullable|string|max:255',
            'province'        => 'nullable|string|max:255',
            'zip_code'        => 'nullable|string|max:255',
            'duration'        => 'required|integer|min:0',
            'payment_method'  => 'required|in:online,bank_transfer',
            'payment_amount'  => 'required|numeric|min:0',
            'payment_receipt' => 'required|boolean',
        ]);
        Customer::updateCustomer($id, $validated);
        $customer = Customer::find($id);
        // Handle publications and attachment counts
        $pubData = [];
        if ($request->has('publications')) {
            foreach ($request->input('publications') as $pubId => $pub) {
                if (isset($pub['selected'])) {
                    $pubData[$pubId] = ['attachment_count' => $pub['attachment_count'] ?? 1];
                }
            }
        }
        $customer->publications()->sync($pubData);
        return redirect()->route('customers.index')
            ->with('success', 'Customer updated');
    }

    public function destroy($id)
    {
        Customer::deleteCustomer($id);
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted');
    }
    public function activateAll()
    {
        Customer::where('status', '!=', 1)->update(['status' => 1]);
        return redirect()->route('customers.index')->with('success', 'All customers activated.');
    }

    public function deactivateAll()
    {
        Customer::where('status', '!=', 0)->update(['status' => 0]);
        return redirect()->route('customers.index')->with('success', 'All customers deactivated.');
    }
}


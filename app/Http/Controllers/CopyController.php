<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Copy;
use App\Models\Customer;
use App\Models\Publication;

class CopyController extends Controller
{
    public function index()
    {
        $copies = Copy::getAllCopies();
        return view('copies.index', compact('copies'));
    }

    public function create()
    {
        $customers = Customer::getActiveCustomers();
        $publications = Publication::getActivePublications();

        return view('copies.create', compact('customers', 'publications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required',
            'publication_id' => 'required',
            'message'        => 'nullable'
        ]);

        Copy::createCopy($request->all());

        return redirect()->route('copies.index')
                         ->with('success', 'Copy added successfully');
    }
}

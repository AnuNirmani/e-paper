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
        $customers = Customer::where('status', 1)->orderBy('first_name')->get();
        $publications = Publication::where('status', 1)->orderBy('name')->get();

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

    /* ===============================
       UPLOAD FUNCTIONALITY
    =============================== */
    public function upload()
    {
        $customers = Customer::where('status', 1)->orderBy('first_name')->get();
        $publications = Publication::getActivePublications();
        return view('copies.upload', compact('customers', 'publications'));
    }

    public function uploadStore(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required',
            'publication_id' => 'required',
            'file'           => 'required|file',
            'message'        => 'nullable|string'
        ]);

        /* FILE UPLOAD */
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        $path = $request->has('watermark')
            ? 'uploads/with_watermark'
            : 'uploads/without_watermark';

        $file->move(public_path($path), $filename);

        /* SAVE COPY RECORD */
        Copy::createCopy([
            'customer_id'    => $request->customer_id,
            'publication_id' => $request->publication_id,
            'message'        => $request->message
        ]);

        return redirect()->route('copies.index')
                         ->with('success', 'File uploaded successfully');
    }
}

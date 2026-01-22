<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::where('status', '!=', -1)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('publications.index', compact('publications'));
    }

    public function create()
    {
        return view('publications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:publications,name',
        ]);

        Publication::create([
            'name' => $request->name,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('publications.index')
            ->with('success', 'Publication created successfully');
    }

    public function edit($id)
    {
        $publication = Publication::findOrFail($id);

        return view('publications.edit', compact('publication'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:publications,name,' . $id,
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('publications.index')
                ->withErrors($validator)
                ->withInput()
                ->with('edit_error_id', $id);
        }

        $publication = Publication::findOrFail($id);
        $publication->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('publications.index')
            ->with('success', 'Publication updated successfully');
    }

    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);
        Publication::softDeletePublication($publication->id);

        return redirect()->route('publications.index')
            ->with('success', 'Publication deleted successfully');
    }

    public function toggleStatus($id)
    {
        $publication = Publication::findOrFail($id);
        $publication->update(['status' => $publication->status == 1 ? 0 : 1]);
        
        return response()->json([
            'success' => true,
            'status' => $publication->status,
            'message' => $publication->status == 1 ? 'Publication activated' : 'Publication deactivated'
        ]);
    }
}

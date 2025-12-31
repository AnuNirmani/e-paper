<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::getAllPublications();
        return view('publications.index', compact('publications'));
    }

    public function create()
    {
        return view('publications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Publication::createPublication($request->all());

        return redirect()->route('publications.index')
            ->with('success', 'Publication created successfully');
    }

    public function edit($id)
    {
        $publication = Publication::getPublicationById($id);

        if (!$publication) {
            abort(404);
        }

        return view('publications.edit', compact('publication'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('publications.index')
                ->withErrors($validator)
                ->withInput()
                ->with('edit_error_id', $id);
        }

        Publication::updatePublication($id, $request->all());

        return redirect()->route('publications.index')
            ->with('success', 'Publication updated successfully');
    }

    public function destroy($id)
    {
        Publication::deletePublication($id);

        return redirect()->route('publications.index')
            ->with('success', 'Publication deleted successfully');
    }
}

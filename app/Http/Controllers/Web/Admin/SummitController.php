<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Summit;
use Illuminate\Http\Request;

class SummitController extends Controller
{
    public function index()
    {
        $summits = Summit::orderBy('created_at', 'desc')->get();
        return view('admin.summits.index', compact('summits'));
    }

    public function create()
    {
        return view('admin.summits.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Summit::create($validated);

        return redirect()->route('admin.summits.index')
            ->with('success', 'Summit created successfully!');
    }

    public function edit(Summit $summit)
    {
        return view('admin.summits.form', compact('summit'));
    }

    public function update(Request $request, Summit $summit)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zone' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $summit->update($validated);

        return redirect()->route('admin.summits.index')
            ->with('success', 'Summit updated successfully!');
    }

    public function destroy(Summit $summit)
    {
        $summit->delete();

        return redirect()->route('admin.summits.index')
            ->with('success', 'Summit deleted successfully!');
    }
}

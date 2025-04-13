<?php

namespace App\Http\Controllers;

use App\Models\emppositions;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all positions from the database
        $positions = emppositions::all();

        // Pass the positions to the view
        return view('main.position', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new position

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        // Create a new position
        emppositions::create([
            'PositionName' => $request->input('PositionName'),
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'PositionName' => 'required|string|max:255',
    ]);

    $position = emppositions::findOrFail($id);
    $position->update([
        'PositionName' => $request->PositionName,
    ]);

    return redirect()->route('positions.index')->with('success', 'Position updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $position = emppositions::findOrFail($id);
    $position->delete();
    return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }
}
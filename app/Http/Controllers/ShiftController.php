<?php

namespace App\Http\Controllers;

use App\Models\shifts;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all shifts from the database
        $shifts = shifts::all();

        // Return the view with the shifts data
        return view('main.shift', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'ShiftName' => 'string|max:255',
            'StartTime' => 'date_format:H:i',
            'EndTime' => 'required|date_format:H:i|after:StartTime',
        ]);

        // Create a new shift
        shifts::create([
            'ShiftName' => $request->input('ShiftName'),
            'StartTime' => $request->input('StartTime'),
            'EndTime' => $request->input('EndTime'),
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('shifts.index')->with('success', 'Shift added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'ShiftName' => 'string|max:255',
            'StartTime' => 'date_format:H:i',
            'EndTime' => 'required|date_format:H:i|after:StartTime',
        ]);

        // Find the shift by ID
        $shift = shifts::findOrFail($id);

        // Update the shift
        $shift->update([
            'ShiftName' => $request->input('ShiftName'),
            'StartTime' => $request->input('StartTime'),
            'EndTime' => $request->input('EndTime'),
        ]);

        // Redirect to the index page with a success message
        return redirect()->route('shifts.index')->with('success', 'Shift updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the shift by ID
        $shift = shifts::findOrFail($id);

        // Delete the shift
        $shift->delete();

        // Redirect to the index page with a success message
        return redirect()->route('shifts.index')->with('success', 'Shift deleted successfully!');
    }
}

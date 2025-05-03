<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleavebalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getLeaveCredits($daysWorked)
{
    $leaveCredits = [
        1 => 0.042, 2 => 0.083, 3 => 0.125, 4 => 0.167, 5 => 0.208,
        6 => 0.250, 7 => 0.292, 8 => 0.333, 9 => 0.375, 10 => 0.417,
        11 => 0.458, 12 => 0.500, 13 => 0.542, 14 => 0.583, 15 => 0.625,
        16 => 0.667, 17 => 0.708, 18 => 0.750, 19 => 0.792, 20 => 0.833,
        21 => 0.875, 22 => 0.917, 23 => 0.958, 24 => 1.000, 25 => 1.042,
        26 => 1.083, 27 => 1.125, 28 => 1.167, 29 => 1.208, 30 => 1.250,
    ];

    // Clamp to 30 if days exceed
    $days = min($daysWorked, 30);

    $vacationLeave = $leaveCredits[$days];
    $sickLeave = $leaveCredits[$days];

    return [
        'VacationLeave' => $vacationLeave,
        'SickLeave' => $sickLeave,
    ];
}
}
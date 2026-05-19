<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $myTrips = $user->trips()->latest()->paginate(5);
        $sharedTrips = $user->sharedTrips()->latest()->paginate(5);
        return view('trips.index', compact('myTrips', 'sharedTrips'));
    }

    public function create()
    {
        return view('trips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_budget' => 'required|numeric|min:0',
            'visibility' => 'required|in:private,public',
        ]);

        $trip = Auth::user()->trips()->create($validated);
        return redirect()->route('trips.index')->with('success', 'Trip created successfully.');
    }

    public function show(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) abort(403);

        $destinations = $trip->destinations()->orderBy('order')->get();
        $budgets = $trip->budgets()->orderBy('expense_date', 'desc')->get();
        
        // SAFE NUMERIC CALCULATIONS
        $totalSpent = $budgets->sum('amount');
        // Ensure budget is numeric
        $tripBudget = is_numeric($trip->total_budget) ? (float) $trip->total_budget : 0;
        $totalSpent = is_numeric($totalSpent) ? (float) $totalSpent : 0;
        $remainingBudget = $tripBudget - $totalSpent;

        // Determine edit permission
        $canEdit = ($trip->user_id === Auth::id() 
            || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists());

        return view('trips.show', compact('trip', 'destinations', 'budgets', 'totalSpent', 'remainingBudget', 'canEdit'));
    }

    public function edit(Trip $trip)
    {
        $this->authorizeEdit($trip);
        return view('trips.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        $this->authorizeEdit($trip);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_budget' => 'required|numeric|min:0',
            'visibility' => 'required|in:private,public',
        ]);
        $trip->update($validated);
        return redirect()->route('trips.index')->with('success', 'Trip updated.');
    }

    public function destroy(Trip $trip)
    {
        $this->authorizeEdit($trip);
        $trip->delete();
        return redirect()->route('trips.index')->with('success', 'Trip deleted.');
    }

    private function authorizeEdit($trip)
    {
        $canEdit = ($trip->user_id === Auth::id() 
            || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists());
        if (!$canEdit) abort(403, 'You do not have permission to edit this trip.');
    }
}
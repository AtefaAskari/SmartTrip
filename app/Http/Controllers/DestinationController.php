<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    /**
     * Store a newly created destination.
     */
    public function store(Request $request, Trip $trip)
    {
        // Authorization: only trip owner can add destinations
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'Only the trip owner can add destinations.');
        }

        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'arrival_date' => 'required|date|after_or_equal:' . $trip->start_date,
            'departure_date' => 'required|date|after:arrival_date|before_or_equal:' . $trip->end_date,
            'notes' => 'nullable|string',
        ]);

        // Get the highest order number and add +1
        $maxOrder = $trip->destinations()->max('order') ?? 0;
        $validated['order'] = $maxOrder + 1;
        $validated['trip_id'] = $trip->id;

        Destination::create($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Destination added successfully!');
    }

    /**
     * Show form to edit destination.
     */
    public function edit(Trip $trip, Destination $destination)
    {
        // Verify destination belongs to trip
        if ($destination->trip_id !== $trip->id) {
            abort(404);
        }

        // Authorization: only trip owner can edit
        if ($trip->user_id !== Auth::id()) {
            abort(403);
        }

        return view('destinations.edit', compact('trip', 'destination'));
    }

    /**
     * Update the specified destination.
     */
    public function update(Request $request, Trip $trip, Destination $destination)
    {
        if ($destination->trip_id !== $trip->id) {
            abort(404);
        }

        if ($trip->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'arrival_date' => 'required|date|after_or_equal:' . $trip->start_date,
            'departure_date' => 'required|date|after:arrival_date|before_or_equal:' . $trip->end_date,
            'notes' => 'nullable|string',
        ]);

        $destination->update($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Destination updated successfully!');
    }

    /**
     * Remove the specified destination.
     */
    public function destroy(Trip $trip, Destination $destination)
    {
        if ($destination->trip_id !== $trip->id) {
            abort(404);
        }

        if ($trip->user_id !== Auth::id()) {
            abort(403);
        }

        $destination->delete();

        // Reorder remaining destinations (optional: reset order)
        $remaining = $trip->destinations()->orderBy('order')->get();
        foreach ($remaining as $index => $dest) {
            $dest->order = $index + 1;
            $dest->save();
        }

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Destination deleted successfully!');
    }
}
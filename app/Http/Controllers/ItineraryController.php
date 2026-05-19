<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItineraryController extends Controller
{
    public function index(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) abort(403);
        $itineraries = $trip->itineraries()->orderBy('day_number')->orderBy('start_time')->get();
        $days = $itineraries->groupBy('day_number');
        return view('itineraries.index', compact('trip', 'itineraries', 'days'));
    }

    public function create(Trip $trip)
    {
        $this->authorizeEdit($trip);
        return view('itineraries.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $this->authorizeEdit($trip);
        $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric',
        ]);
        $startDate = $trip->start_date;
        $request->merge(['day_date' => $startDate->copy()->addDays($request->day_number - 1)]);
        $trip->itineraries()->create($request->all());
        return redirect()->route('itineraries.index', $trip)->with('success', 'Activity added.');
    }

    public function edit(Trip $trip, Itinerary $itinerary)
    {
        $this->authorizeEdit($trip);
        return view('itineraries.edit', compact('trip', 'itinerary'));
    }

    public function update(Request $request, Trip $trip, Itinerary $itinerary)
    {
        $this->authorizeEdit($trip);
        $request->validate([
            'day_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'location' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric',
        ]);
        $startDate = $trip->start_date;
        $request->merge(['day_date' => $startDate->copy()->addDays($request->day_number - 1)]);
        $itinerary->update($request->all());
        return redirect()->route('itineraries.index', $trip)->with('success', 'Activity updated.');
    }

    public function destroy(Trip $trip, Itinerary $itinerary)
    {
        $this->authorizeEdit($trip);
        $itinerary->delete();
        return redirect()->route('itineraries.index', $trip)->with('success', 'Activity deleted.');
    }

    private function authorizeEdit($trip)
    {
        $canEdit = ($trip->user_id === Auth::id() || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists());
        if (!$canEdit) abort(403);
    }
}
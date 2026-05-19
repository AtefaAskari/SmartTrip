<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Destination;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function store(Request $request, Trip $trip, Destination $destination)
    {
        if ($destination->trip_id != $trip->id) abort(404);
        $request->validate(['vote_type' => 'required|in:up,down']);
        $vote = Vote::updateOrCreate(
            ['trip_id' => $trip->id, 'user_id' => Auth::id(), 'destination_id' => $destination->id],
            ['vote_type' => $request->vote_type]
        );
        return redirect()->back()->with('success', 'Vote recorded.');
    }

    public function destroy(Trip $trip, Destination $destination)
    {
        Vote::where('trip_id', $trip->id)->where('user_id', Auth::id())->where('destination_id', $destination->id)->delete();
        return redirect()->back()->with('success', 'Vote removed.');
    }
}
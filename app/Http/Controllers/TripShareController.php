<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripShareController extends Controller
{
    /**
     * Show form to share a trip.
     */
    public function create(Trip $trip)
{
    if ($trip->user_id !== Auth::id()) abort(403);
    $alreadyShared = $trip->sharedWithUsers->pluck('email')->toArray();
    
    // Calculate budget summary for the view
    $budgets = $trip->budgets;
    $totalSpent = $budgets->sum('amount');
    $remainingBudget = $trip->total_budget - $totalSpent;
    
    return view('trips.share', compact('trip', 'alreadyShared', 'totalSpent', 'remainingBudget'));
}

    /**
     * Store a new share invitation.
     */
    public function store(Request $request, Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'permission' => 'required|in:view,edit',
        ]);

        $userToShareWith = User::where('email', $request->email)->first();

        // Don't share with owner
        if ($userToShareWith->id === $trip->user_id) {
            return back()->with('error', 'You cannot share a trip with yourself.');
        }

        // Check if already shared
        if ($trip->sharedWithUsers->contains($userToShareWith->id)) {
            return back()->with('error', 'Trip already shared with this user.');
        }

        // Attach share
        $trip->sharedWithUsers()->attach($userToShareWith->id, [
            'permission' => $request->permission,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('trips.show', $trip)
            ->with('success', "Trip shared with {$userToShareWith->name} ({$request->permission} permission).");
    }

    /**
     * Remove a share.
     */
    public function destroy(Trip $trip, User $user)
    {
        if ($trip->user_id !== Auth::id()) {
            abort(403);
        }

        $trip->sharedWithUsers()->detach($user->id);

        return redirect()->route('trips.show', $trip)
            ->with('success', "Removed sharing with {$user->name}.");
    }
}
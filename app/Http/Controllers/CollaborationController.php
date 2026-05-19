<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Collaborator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    public function index(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);
        $collaborators = $trip->collaborators()->with('user')->get();
        return view('collaborations.index', compact('trip', 'collaborators'));
    }

    public function create(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);
        return view('collaborations.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);
        $request->validate(['email' => 'required|email|exists:users,email', 'role' => 'required|in:viewer,editor,admin']);
        $user = User::where('email', $request->email)->first();
        if ($user->id == $trip->user_id) return back()->with('error', 'Cannot invite yourself.');
        $trip->collaborators()->updateOrCreate(
            ['user_id' => $user->id],
            ['role' => $request->role, 'status' => 'pending']
        );
        return redirect()->route('collaborations.index', $trip)->with('success', 'Invitation sent.');
    }

    public function accept(Trip $trip, Collaborator $collaborator)
    {
        if ($collaborator->user_id !== Auth::id()) abort(403);
        $collaborator->update(['status' => 'accepted']);
        return redirect()->route('trips.show', $trip)->with('success', 'Invitation accepted.');
    }

    public function reject(Trip $trip, Collaborator $collaborator)
    {
        if ($collaborator->user_id !== Auth::id()) abort(403);
        $collaborator->delete();
        return redirect()->route('dashboard')->with('info', 'Invitation rejected.');
    }

    public function destroy(Trip $trip, Collaborator $collaborator)
    {
        if ($trip->user_id !== Auth::id()) abort(403);
        $collaborator->delete();
        return redirect()->route('collaborations.index', $trip)->with('success', 'Collaborator removed.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) abort(403);
        $images = $trip->galleries()->latest()->get();
        return view('galleries.index', compact('trip', 'images'));
    }

    public function create(Trip $trip)
    {
        $this->authorizeEdit($trip);
        return view('galleries.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $this->authorizeEdit($trip);
        $request->validate([
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string|max:255',
            'review' => 'nullable|string',
        ]);
        $path = $request->file('image')->store('gallery', 'public');
        $trip->galleries()->create([
            'user_id' => Auth::id(),
            'image_path' => $path,
            'caption' => $request->caption,
            'review' => $request->review,
        ]);
        return redirect()->route('galleries.index', $trip)->with('success', 'Photo uploaded.');
    }

    public function destroy(Trip $trip, Gallery $gallery)
    {
        $this->authorizeEdit($trip);
        Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();
        return redirect()->route('galleries.index', $trip)->with('success', 'Photo deleted.');
    }

    private function authorizeEdit($trip)
    {
        $canEdit = ($trip->user_id === Auth::id() || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists());
        if (!$canEdit) abort(403);
    }
}
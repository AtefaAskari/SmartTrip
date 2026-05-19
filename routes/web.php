<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TripShareController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trips (full CRUD)
    Route::resource('trips', TripController::class);

    // Destinations (nested)
    Route::post('/trips/{trip}/destinations', [DestinationController::class, 'store'])->name('destinations.store');
    Route::get('/trips/{trip}/destinations/{destination}/edit', [DestinationController::class, 'edit'])->name('destinations.edit');
    Route::put('/trips/{trip}/destinations/{destination}', [DestinationController::class, 'update'])->name('destinations.update');
    Route::delete('/trips/{trip}/destinations/{destination}', [DestinationController::class, 'destroy'])->name('destinations.destroy');

    // Budgets (expenses)
    Route::post('/trips/{trip}/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/trips/{trip}/budgets/{budget}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/trips/{trip}/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/trips/{trip}/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    // Trip Sharing (existing)
    Route::get('/trips/{trip}/share', [TripShareController::class, 'create'])->name('trips.share.create');
    Route::post('/trips/{trip}/share', [TripShareController::class, 'store'])->name('trips.share.store');
    Route::delete('/trips/{trip}/share/{user}', [TripShareController::class, 'destroy'])->name('trips.share.destroy');

    // Itinerary (daily schedule)
    Route::get('/trips/{trip}/itineraries', [ItineraryController::class, 'index'])->name('itineraries.index');
    Route::get('/trips/{trip}/itineraries/create', [ItineraryController::class, 'create'])->name('itineraries.create');
    Route::post('/trips/{trip}/itineraries', [ItineraryController::class, 'store'])->name('itineraries.store');
    Route::get('/trips/{trip}/itineraries/{itinerary}/edit', [ItineraryController::class, 'edit'])->name('itineraries.edit');
    Route::put('/trips/{trip}/itineraries/{itinerary}', [ItineraryController::class, 'update'])->name('itineraries.update');
    Route::delete('/trips/{trip}/itineraries/{itinerary}', [ItineraryController::class, 'destroy'])->name('itineraries.destroy');

    // Recommendations (AI)
    Route::resource('recommendations', RecommendationController::class)->except(['edit', 'update']);

    // Collaboration
    Route::get('/trips/{trip}/collaborations', [CollaborationController::class, 'index'])->name('collaborations.index');
    Route::get('/trips/{trip}/collaborations/create', [CollaborationController::class, 'create'])->name('collaborations.create');
    Route::post('/trips/{trip}/collaborations', [CollaborationController::class, 'store'])->name('collaborations.store');
    Route::post('/trips/{trip}/collaborations/{collaborator}/accept', [CollaborationController::class, 'accept'])->name('collaborations.accept');
    Route::post('/trips/{trip}/collaborations/{collaborator}/reject', [CollaborationController::class, 'reject'])->name('collaborations.reject');
    Route::delete('/trips/{trip}/collaborations/{collaborator}', [CollaborationController::class, 'destroy'])->name('collaborations.destroy');

    // Voting
    Route::post('/trips/{trip}/destinations/{destination}/vote', [VoteController::class, 'store'])->name('votes.store');
    Route::delete('/trips/{trip}/destinations/{destination}/vote', [VoteController::class, 'destroy'])->name('votes.destroy');

    // Gallery
    Route::get('/trips/{trip}/galleries', [GalleryController::class, 'index'])->name('galleries.index');
    Route::get('/trips/{trip}/galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
    Route::post('/trips/{trip}/galleries', [GalleryController::class, 'store'])->name('galleries.store');
    Route::delete('/trips/{trip}/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Weather
    Route::get('/trips/{trip}/weather', [WeatherController::class, 'index'])->name('weather.index');

    // Reports
    Route::get('/trips/{trip}/reports', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/trips/{trip}/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');
});

require __DIR__.'/auth.php';
<?php

use Inertia\Inertia;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffUserController;
use App\Http\Controllers\TransportationController;
use App\Http\Controllers\PendingRequestController;
use App\Http\Controllers\VenueController;
use App\Models\Transportation;
use App\Models\Venue;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'users' => StaffUser::all(),
        'transportations' => Transportation::with('staff_user')->get(),
        'venues' => Venue::with('staff_user')->get(),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/staff-users', [StaffUserController::class, 'index']);

// Transportation Routes
Route::get('/transportation-requests', [TransportationController::class, 'index'])->name('transportations.index');
//Route::get('/pending-requests', [TransportationController::class, 'pending'])->name('transportations.pending');
Route::get('/transportation', function () {
    return Inertia::render('TransportationForm');
})->name('transportation.create');

Route::post('/transportation', [TransportationController::class, 'store'])->name('transportations.store');
Route::delete('/transportation/{id}', [TransportationController::class, 'destroy'])->name('transportations.destroy');

// Venue Routes
Route::get('/venue-reservations', [VenueController::class, 'index'])->name('venues.index');
//Route::get('/venue-pending', [VenueController::class, 'pending'])->name('venues.pending');
Route::get('/venue-form', function () {
    return Inertia::render('VenueForm');
})->name('venue.create');



Route::post('/venue-form', [VenueController::class, 'store'])->name('venues.store');
Route::delete('/venue/{id}', [VenueController::class, 'destroy'])->name('venues.destroy');

//Route::get('/requests', [PendingRequestController::class, 'pendingRequests'])->name('requests.pending');


// Combined Pending Requests
Route::get('/requests', [PendingRequestController::class, 'pendingRequests'])->name('requests.pending');
Route::get('/requests/{id}/{type}/view', [PendingRequestController::class, 'viewRequest'])->name('requests.view');
Route::get('/requests/{id}/{type}/edit', [PendingRequestController::class, 'editRequest'])->name('requests.edit');
Route::delete('/requests/{id}/{type}', [PendingRequestController::class, 'deleteRequest'])->name('requests.delete');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
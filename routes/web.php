<?php

use Inertia\Inertia;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffUserController;
use App\Http\Controllers\TransportationController;
use App\Http\Controllers\ConferenceRoomController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'users' => StaffUser::all(),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/staff-users', [StaffUserController::class, 'index']);

// Transportation Routes
Route::get('/transportation', function () {
    return Inertia::render('TransportationForm');
})->name('transportation.create');

Route::post('/transportation', [TransportationController::class, 'store'])->name('transportations.store');

// Conference Room Routes
Route::get('/conference-room', function () {
    return Inertia::render('ConferenceRoomForm');
})->name('conference-room.create');

Route::post('/conference-room', [ConferenceRoomController::class, 'store'])->name('venues.store');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
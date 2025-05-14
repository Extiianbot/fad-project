<?php

use Inertia\Inertia;
use App\Models\StaffUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffUserController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'users' => StaffUser::all(),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/staff-users', [StaffUserController::class, 'index']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
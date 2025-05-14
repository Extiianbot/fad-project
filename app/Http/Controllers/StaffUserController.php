<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffUserController extends Controller
{
    public function index()
    {
        $users = StaffUser::all();  // Fetching all users from the database
        return Inertia::render('Home', [
            'users' => $users,
        ]);
    }
}

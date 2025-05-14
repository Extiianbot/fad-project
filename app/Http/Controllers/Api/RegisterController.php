<?php

namespace App\Http\Controllers\Api;

use App\Models\StaffUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff_users',
            'password' => 'required|string|min:6|confirmed',
            'position' => 'required|string',
            'division' => 'required|string',
        ]);

        // Create new staff user
        $user = StaffUser::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Explicitly hash the password
            'position' => $request->position,
            'division' => $request->division,
        ]);

        // Return response
        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }
}
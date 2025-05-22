<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffUser;



class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff_users',
            'password' => 'required|string|min:6',
            'position' => 'required|string',
            'division' => 'required|string',
        ]);

        try {
            $user = StaffUser::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'position' => $request->position,
                'division' => $request->division,
            ]);

            \Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username
            ]);

            return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }
}
    


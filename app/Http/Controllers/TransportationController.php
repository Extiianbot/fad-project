<?php

namespace App\Http\Controllers;

use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'number_of_passengers' => 'required|integer|min:1',
            'purpose' => 'required|string',
            'date_time_needed' => 'required|date',
            'date_time_returned' => 'required|date|after:date_time_needed',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);

        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('transportation-attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Add the staff_user_id from the authenticated user
        $validated['staff_user_id'] = Auth::id();
        
        // Set initial status
        $validated['status'] = false;
        $validated['is_approved_by'] = false;

        $transportation = Transportation::create($validated);

        return redirect()->back()->with('success', 'Transportation request submitted successfully!');
    }
} 
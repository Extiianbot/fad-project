<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConferenceRoomController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_of_event' => 'required|string|max:255',
            'conferen_type' => 'required|string|max:255',
            'number_of_participants' => 'required|integer|min:1',
            'date_of_event' => 'required|date',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);

        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('venue-attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Add the staff_user_id from the authenticated user
        $validated['staff_user_id'] = Auth::id();

        $venue = Venue::create($validated);

        return redirect()->back()->with('success', 'Conference room booking submitted successfully!');
    }
} 
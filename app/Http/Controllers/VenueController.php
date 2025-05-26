<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::with('staff_user:id,username')
            ->where('staff_user_id', auth()->id())
            ->get()
            ->map(function ($venue) {
                if (!$venue->staff_user) {
                    $venue->staff_user = new \stdClass();
                    $venue->staff_user->username = 'Unknown User';
                }
                return $venue;
            });

        return Inertia::render('VenueRequest', [
            'venues' => $venues
        ]);
    }

    public function pending()
    {
        $pendingVenues = Venue::with('staff_user:id,username')
            ->where('is_approved_by', false)
            ->where('status', false)
            ->get()
            ->map(function ($venue) {
                if (!$venue->staff_user) {
                    $venue->staff_user = new \stdClass();
                    $venue->staff_user->username = 'Unknown User';
                }
                return $venue;
            });

        return Inertia::render('VenuePending', [
            'pendingVenues' => $pendingVenues,
            'auth' => auth()->user()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_of_event' => 'required|string|max:255',
            'conference_type' => 'required|string|in:seminar,workshop',
            'number_of_participants' => 'required|integer|min:1',
            'date_of_event' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'attachment' => 'nullable|file|max:10240', // max 10MB
        ]);

        // Combine date with time for datetime
        $validated['time_start'] = $validated['date_of_event'] . ' ' . $validated['time_start'];
        $validated['time_end'] = $validated['date_of_event'] . ' ' . $validated['time_end'];

        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('venue-attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Add the staff_user_id from the authenticated user
        $validated['staff_user_id'] = Auth::id();
        
        // Set initial status
        $validated['status'] = false;
        $validated['is_approved_by'] = false;

        Venue::create($validated);

        return redirect()->back()->with('success', 'Conference room reservation submitted successfully!');
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);

        // Delete the attachment if it exists
        if ($venue->attachment) {
            $path = storage_path('app/public/' . $venue->attachment);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $venue->delete();

        return redirect()->back()->with('success', 'Reservation deleted successfully');
    }
}

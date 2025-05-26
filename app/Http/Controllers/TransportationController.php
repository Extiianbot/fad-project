<?php

namespace App\Http\Controllers;

use App\Models\Transportation;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransportationController extends Controller
{
    public function index()
    {
        $transportationRequests = Transportation::with('staff_user:id,username')
            ->get()
            ->map(function ($request) {
                // Ensure we have the staff user data
                if (!$request->staff_user) {
                    $request->staff_user = new \stdClass();
                    $request->staff_user->username = 'Unknown User';
                }
                return $request;
            });

        return Inertia::render('TransportationRequest', [
            'transportationRequests' => $transportationRequests
        ]);
    }

    public function pending()
    {
        $pendingRequests = Transportation::with('staff_user:id,username')
            ->where('is_approved_by', false)
            ->where('status', false)
            ->get()
            ->map(function ($request) {
                if (!$request->staff_user) {
                    $request->staff_user = new \stdClass();
                    $request->staff_user->username = 'Unknown User';
                }
                return $request;
            });

        return Inertia::render('PendingRequest', [
            'pendingRequests' => $pendingRequests,
            'auth' => auth()->user()
        ]);
    }

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

    public function destroy($id)
    {
        $request = Transportation::findOrFail($id);

        // Delete the attachment if it exists
        if ($request->attachment) {
            $path = storage_path('app/public/' . $request->attachment);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $request->delete();

        return redirect()->back()->with('success', 'Request deleted successfully');
    }
} 
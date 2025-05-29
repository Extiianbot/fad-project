<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PendingRequestController extends Controller
{
    public function index()
    {
        // Get all venues with their details
        $venues = Venue::with('staffUser')
            ->get()
            ->map(function ($venue) {
                return [
                    'id' => $venue->id,
                    'type' => 'venue',
                    'date' => $venue->date_of_event,
                    'created_at' => $venue->created_at,
                    'status' => $venue->status,
                    'is_approved_by' => $venue->is_approved_by,
                    'venue' => [
                        'conference_type' => $venue->conference_type,
                        'number_of_participants' => $venue->number_of_participants,
                        'title_of_event' => $venue->title_of_event,
                        'date_of_event' => $venue->date_of_event,
                        'time_start' => $venue->time_start,
                        'time_end' => $venue->time_end,
                        'attachment' => $venue->attachment
                    ],
                    'staff_user_id' => $venue->staff_user_id
                ];
            });

        // Get all transportations with their details
        $transportations = Transportation::with('staffUser')
            ->get()
            ->map(function ($transportation) {
                return [
                    'id' => $transportation->id,
                    'type' => 'transportation',
                    'date' => $transportation->date_time_needed,
                    'created_at' => $transportation->created_at,
                    'status' => $transportation->status,
                    'is_approved_by' => $transportation->is_approved_by,
                    'transportation' => [
                        'destination' => $transportation->destination,
                        'number_of_passengers' => $transportation->number_of_passengers,
                        'purpose' => $transportation->purpose,
                        'date_time_needed' => $transportation->date_time_needed,
                        'date_time_returned' => $transportation->date_time_returned,
                        'attachment' => $transportation->attachment
                    ],
                    'staff_user_id' => $transportation->staff_user_id
                ];
            });

        // Combine both collections
        $requests = $venues->merge($transportations)
            ->sortBy('created_at')
            ->values();

        return Inertia::render('PendingRequestTable', [
            'requests' => $requests,
            'auth' => Auth::user(),
        ]);
    }

    public function pendingRequests()
    {
        // Get venue requests with timestamps
        $venues = Venue::with('staff_user:id,username')
            ->where('status', 'pending')
            ->where('is_approved_by', false)
            ->get()
            ->map(function ($venue) {
                return [
                    'id' => $venue->id,
                    'type' => 'venue',
                    'title' => $venue->event_title,
                    'date' => $venue->date_of_event,
                    'created_at' => $venue->created_at,
                    'status' => $venue->status,
                    'is_approved_by' => $venue->is_approved_by,
                ];
            });

        // Get transportation requests with timestamps
        $transportations = Transportation::with('staff_user:id,username')
            ->where('status', 'pending')
            ->where('is_approved_by', false)
            ->get()
            ->map(function ($transportation) {
                return [
                    'id' => $transportation->id,
                    'type' => 'transportation',
                    'date' => $transportation->date_time_needed,
                    'created_at' => $transportation->created_at,
                    'status' => $transportation->status,
                    'is_approved_by' => $transportation->is_approved_by,
                ];
            });

        // Combine both collections
        $requests = $venues->merge($transportations)
            ->sortBy('created_at') // Sort by creation date in ascending order
            ->values();

        return Inertia::render('PendingRequestTable', [
            'requests' => $requests,
            'auth' => Auth::user(),
        ]);
    }

    public function viewRequest($id, $type)
    {
        if ($type === 'venue') 
        {
            $venue = Venue::with('staff_user')->find($id);
           
            return Inertia::render('PendingRequestTable', [
            'requests' => [[
            'type' => 'venue',
            'venue' => $venue,
            'id' => $venue->id,
            'conference' => $venue->conference_type,
            'status' => $venue->status,
            'created_at' => $venue->created_at,
            
    ]],

                'auth' => Auth::user(),
            ]);
        }
        
        
        
        //$transportation = Transportation::with('staff_user')->find($id);
        $transportation = Transportation::with('staff_user')->find($id);
        return Inertia::render('PendingRequestTable', [
            'requests' => [$transportation],
            'type' => 'transportation',
            'destination' => $transportation->destination,
            'number_of_passengers' => $transportation->number_of_passengers,
            'purpose' => $transportation->purpose,
            'date_time_needed' => $transportation->date_time_needed,
            'date_time_returned' => $transportation->date_time_returned,
            'auth' => Auth::user(),
        ]);
        
    }

    public function editRequest($id, $type)
    {
        if ($type === 'venue') {
            return redirect()->route('venues.edit', $id);
        }
        return redirect()->route('transportations.edit', $id);
    }

    public function deleteRequest($id, $type)
    {
        try {
            if ($type === 'venue') {
                $venue = Venue::findOrFail($id);
                if ($venue->attachment) {
                    $path = storage_path('app/public/' . $venue->attachment);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                $venue->delete();
            } else {
                $transportation = Transportation::findOrFail($id);
                if ($transportation->attachment) {
                    $path = storage_path('app/public/' . $transportation->attachment);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                $transportation->delete();
            }
            
            return redirect()->back()->with('success', 'Request deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete request: ' . $e->getMessage());
        }
    }



    public function updateRequest(Request $request, $id, $type)
    {
        try {
            if ($type === 'venue') {
                $venue = Venue::findOrFail($id);
                $validated = $request->validate([
                    'conference_type' => 'required|string|in:Mini Conference,Conference Hall',
                    'title_of_event' => 'required|string|max:255',
                    'number_of_participants' => 'required|integer|min:1',
                    'date_of_event' => 'required|date',
                    'time_start' => 'required|date_format:H:i',
                    'time_end' => 'required|date_format:H:i',
                    'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx'
                ]);
    
                if ($request->hasFile('attachment')) {
                    if ($venue->attachment) {
                        $path = storage_path('app/public/' . $venue->attachment);
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
                }
    
                $venue->update($validated);
                return redirect()->route('requests.pending');
            } else {
                $transportation = Transportation::findOrFail($id);
                $validated = $request->validate([
                    'destination' => 'required|string|max:255',
                    'number_of_passengers' => 'required|integer|min:1',
                    'purpose' => 'required|string|max:255',
                    'date_time_needed' => 'required|date',
                    'date_time_returned' => 'required|date',
                    'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx'
                ]);
    
                if ($request->hasFile('attachment')) {
                    if ($transportation->attachment) {
                        $path = storage_path('app/public/' . $transportation->attachment);
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                    $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
                }
    
                $transportation->update($validated);
                return redirect()->route('requests.pending');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update request: ' . $e->getMessage());
        }
    }

    public function getRequestDetails($id, $type)
{
    if ($type === 'venue') {
        $venue = Venue::with('staff_user')->find($id);
        return response()->json([
            'type' => 'venue',
            'venue' => $venue,
            'id' => $venue->id,
            'conference' => $venue->conference_type,
            'status' => $venue->status,
            'created_at' => $venue->created_at,
        ]);
    } else {
        $transportation = Transportation::with('staff_user')->find($id);
        return response()->json([
            'type' => 'transportation',
            'transportation' => [
                'destination' => $transportation->destination,
                'number_of_passengers' => $transportation->number_of_passengers,
                'purpose' => $transportation->purpose,
                'date_time_needed' => $transportation->date_time_needed,
                'date_time_returned' => $transportation->date_time_returned,
                'attachment' => $transportation->attachment,
                'staff_user' => $transportation->staff_user
            ],
            'id' => $transportation->id,
            'status' => $transportation->status,
            'is_approved_by' => $transportation->is_approved_by,
            'created_at' => $transportation->created_at
        ]);
    }
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PendingRequestController extends Controller
{
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
        if ($type === 'venue') {
            return redirect()->route('venues.show', $id);
        }
        return redirect()->route('transportations.show', $id);
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
}
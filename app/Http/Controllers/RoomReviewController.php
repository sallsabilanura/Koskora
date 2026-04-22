<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomReview;
use App\Models\Rental;
use Illuminate\Http\Request;

class RoomReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        // Only tenants with an active or terminated rental can review
        if (!$tenant) {
            return back()->with('error', 'Hanya penyewa yang dapat memberikan ulasan.');
        }

        $validated = $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'rating'       => 'required|integer|min:1|max:5',
            'comment'      => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|boolean',
        ]);

        // Verify the user has rented this room
        $hasRental = Rental::where('tenant_id', $tenant->id)
            ->where('room_id', $validated['room_id'])
            ->exists();

        if (!$hasRental) {
            return back()->with('error', 'Anda hanya dapat mengulas kamar yang pernah Anda sewa.');
        }

        // Prevent duplicate reviews from the same user for the same room
        $existingReview = RoomReview::where('user_id', $user->id)
            ->where('room_id', $validated['room_id'])
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating'       => $validated['rating'],
                'comment'      => $validated['comment'] ?? null,
                'is_anonymous' => $validated['is_anonymous'] ?? false,
            ]);
        } else {
            // Create new review
            RoomReview::create([
                'room_id'      => $validated['room_id'],
                'user_id'      => $user->id,
                'rating'       => $validated['rating'],
                'comment'      => $validated['comment'] ?? null,
                'is_anonymous' => $validated['is_anonymous'] ?? false,
            ]);
        }

        return back()->with('success', 'Ulasan Anda berhasil disimpan. Terima kasih!');
    }
}

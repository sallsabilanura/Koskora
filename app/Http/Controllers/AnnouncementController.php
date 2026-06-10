<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    /**
     * Display a listing for Admin management.
     */
    public function index()
    {
        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Display a listing for Users/Tenants/Partners.
     */
    public function userIndex()
    {
        $role = auth()->user()->role;
        
        $announcements = Announcement::where('is_active', true)
            ->where(function($query) use ($role) {
                $query->where('target_role', 'all')
                      ->orWhere('target_role', $role);
            })
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('user.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,update,warning,danger',
            'target_role' => 'required|in:all,user,laundry,cleaner',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'target_role' => $request->target_role,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
            'user_id' => auth()->id(),
        ]);

        // Send Email Notifications
        if ($announcement->is_active) {
            $targetRole = $request->target_role;
            $usersQuery = \App\Models\User::query();
            
            if ($targetRole !== 'all') {
                $usersQuery->where('role', $targetRole);
            }
            
            $users = $usersQuery->get();

            foreach ($users as $user) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->queue(new \App\Mail\AnnouncementMail($announcement, $user->name));
                } catch (\Exception $e) {
                    // Fail silently or log error if mail server not configured
                    \Log::error("Failed to send announcement email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diterbitkan dan dikirim ke email target!');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,update,warning,danger',
            'target_role' => 'required|in:all,user,laundry,cleaner',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'target_role' => $request->target_role,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of all regional admins.
     * Only accessible by superadmin.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->get();

        return view('admin.regional_admins.index', compact('admins'));
    }

    /**
     * Store a newly created regional admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'district' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => 'admin',
            'district'          => $request->district,
        ]);

        event(new Registered($user));

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin wilayah ' . $request->district . ' berhasil ditambahkan.');
    }

    /**
     * Remove the specified regional admin.
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Akun ini bukan admin wilayah.');
        }

        $districtName = $user->district;
        $user->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', "Admin wilayah {$districtName} berhasil dihapus.");
    }
}

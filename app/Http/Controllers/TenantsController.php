<?php

namespace App\Http\Controllers;

use App\Models\Tenants;
use App\Models\User;
use Illuminate\Http\Request;

class TenantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenants::latest()->get();
        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('tenants.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nik' => 'required|string|max:255|unique:tenants,nik',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Tenants::create($validatedData);

        return redirect()->route('tenants.index')->with('success', 'Data penyewa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenants $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenants $tenant)
    {
        $users = User::orderBy('name')->get();
        return view('tenants.edit', compact('tenant', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenants $tenant)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nik' => 'required|string|max:255|unique:tenants,nik,' . $tenant->id,
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $tenant->update($validatedData);

        return redirect()->route('tenants.index')->with('success', 'Data penyewa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenants $tenant)
    {
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Data penyewa berhasil dihapus.');
    }
}

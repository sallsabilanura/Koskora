<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'user') {
            $tenant = $user->tenant ?: new \App\Models\Tenants(['user_id' => $user->id, 'status' => 'active']);

            $tenantFields = [
                'nama_lengkap', 'nama_panggilan', 'nik', 'jenis_kelamin',
                'tempat_lahir', 'tanggal_lahir', 'nomor_whatsapp', 'alamat_ktp',
                'address', 'rt', 'rw', 'province', 'city', 'district', 'village',
                'occupation', 'emergency_contact'
            ];

            foreach ($tenantFields as $field) {
                if ($request->has($field)) {
                    $tenant->$field = $request->input($field);
                }
            }

            if ($request->hasFile('foto_ktp')) {
                $tenant->foto_ktp = $request->file('foto_ktp')->store('tenants/ktp', 'public');
            }

            if ($request->hasFile('foto_diri')) {
                $tenant->foto_diri = $request->file('foto_diri')->store('tenants/self', 'public');
            }

            $tenant->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

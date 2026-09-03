<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'province_id' => ['nullable', 'string'],
            'province' => ['nullable', 'string'],
            'city_id' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'district_id' => ['nullable', 'string'],
            'district' => ['nullable', 'string'],
            'village_id' => ['nullable', 'string'],
            'village' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ];

        if ($this->user()->isUser()) {
            $rules = array_merge($rules, [
                'nama_lengkap' => ['nullable', 'string', 'max:255'],
                'nama_panggilan' => ['nullable', 'string', 'max:100'],
                'nik' => [
                    'nullable',
                    'string',
                    'size:16',
                    Rule::unique('tenants', 'nik')->ignore($this->user()->tenant?->id)
                ],
                'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
                'tempat_lahir' => ['nullable', 'string', 'max:100'],
                'tanggal_lahir' => ['nullable', 'date'],
                'nomor_whatsapp' => ['nullable', 'string', 'max:20'],
                'alamat_ktp' => ['nullable', 'string'],
                'rt' => ['nullable', 'string', 'max:5'],
                'rw' => ['nullable', 'string', 'max:5'],
                'province' => ['nullable', 'string', 'max:100'],
                'city' => ['nullable', 'string', 'max:100'],
                'district' => ['nullable', 'string', 'max:100'],
                'village' => ['nullable', 'string', 'max:100'],
                'occupation' => ['nullable', 'string', 'max:100'],
                'emergency_contact' => ['nullable', 'string', 'max:20'],
                'foto_ktp' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
                'foto_diri' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);
        }

        return $rules;
    }
}

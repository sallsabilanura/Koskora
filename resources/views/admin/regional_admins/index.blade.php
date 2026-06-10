<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <h1 class="page-title">Kelola Admin Daerah</h1>
                <p class="page-subtitle">Buat dan kelola akun Admin Wilayah KosKora</p>
            </div>
        </div>
    </x-slot>

    <style>
        .sa-hero {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .sa-hero-icon {
            width: 50px;
            height: 50px;
            background: #eff2ff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #4f6ef7;
            flex-shrink: 0;
        }
        .sa-hero-info h2 {
            color: #111827;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0 0 0.2rem;
        }
        .sa-hero-info p {
            color: #6b7280;
            margin: 0;
            font-size: 0.86rem;
        }
        .sa-badge {
            margin-left: auto;
            background: #eff2ff;
            border: 1px solid #c7d2fe;
            color: #4f6ef7;
            padding: 0.35rem 0.9rem;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Create Form Card */
        .sa-form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.75rem 2rem;
            margin-bottom: 1.75rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .sa-form-card h3 {
            color: #111827;
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .sa-form-card h3 i {
            color: #4f6ef7;
        }
        .sa-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .sa-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .sa-form-group.full {
            grid-column: 1 / -1;
        }
        .sa-form-group label {
            color: #374151;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .sa-form-group input {
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            padding: 0.6rem 0.9rem;
            color: #111827;
            font-size: 0.88rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .sa-form-group input:focus {
            border-color: #4f6ef7;
            box-shadow: 0 0 0 3px rgba(79,110,247,0.1);
            background: #fff;
        }
        .sa-form-group input::placeholder {
            color: #9ca3af;
        }
        .sa-form-divider {
            height: 1px;
            background: #f3f4f6;
            margin: 1.25rem 0;
        }
        .sa-submit-btn {
            background: #4f6ef7;
            color: #fff;
            border: none;
            padding: 0.65rem 1.75rem;
            border-radius: 9px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px rgba(79,110,247,0.25);
        }
        .sa-submit-btn:hover {
            background: #3b5de8;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79,110,247,0.35);
        }

        /* Admin List Card */
        .sa-list-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .sa-list-header {
            padding: 1.1rem 1.75rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }
        .sa-list-header h3 {
            color: #111827;
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin: 0;
        }
        .sa-list-header h3 i { color: #4f6ef7; }
        .count-badge {
            background: #eff2ff;
            color: #4f6ef7;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.2rem 0.7rem;
            border-radius: 30px;
            border: 1px solid #c7d2fe;
        }
        .sa-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sa-table thead th {
            padding: 0.75rem 1.75rem;
            font-size: 0.71rem;
            font-weight: 700;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            color: #9ca3af;
            text-align: left;
            background: #fafafa;
            border-bottom: 1px solid #f3f4f6;
        }
        .sa-table tbody tr {
            border-bottom: 1px solid #f9fafb;
            transition: background 0.12s;
        }
        .sa-table tbody tr:hover {
            background: #f9fafb;
        }
        .sa-table tbody tr:last-child {
            border-bottom: none;
        }
        .sa-table td {
            padding: 0.95rem 1.75rem;
            vertical-align: middle;
        }
        .admin-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4f6ef7, #818cf8);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .admin-name-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .admin-name {
            color: #111827;
            font-weight: 600;
            font-size: 0.88rem;
            line-height: 1.3;
        }
        .admin-email {
            color: #9ca3af;
            font-size: 0.78rem;
        }
        .district-pill {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 0.28rem 0.75rem;
            border-radius: 30px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .role-pill {
            background: #eff2ff;
            border: 1px solid #c7d2fe;
            color: #4f6ef7;
            padding: 0.25rem 0.7rem;
            border-radius: 30px;
            font-size: 0.73rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .delete-btn {
            background: #fff;
            border: 1px solid #fecaca;
            color: #ef4444;
            padding: 0.38rem 0.85rem;
            border-radius: 8px;
            font-size: 0.77rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, transform 0.12s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .delete-btn:hover {
            background: #fef2f2;
            border-color: #ef4444;
            transform: translateY(-1px);
        }
        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
        }
        .empty-state i {
            font-size: 2.5rem;
            color: #e5e7eb;
            margin-bottom: 0.75rem;
            display: block;
        }
        .empty-state p {
            color: #9ca3af;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        /* Alert */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 0.8rem 1.1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.88rem;
            font-weight: 500;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.8rem 1.1rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.88rem;
            font-weight: 500;
        }
        .error-text {
            color: #ef4444;
            font-size: 0.78rem;
            margin-top: 0.15rem;
        }

        @media (max-width: 640px) {
            .sa-form-grid { grid-template-columns: 1fr; }
            .sa-form-group.full { grid-column: 1; }
            .sa-badge { display: none; }
        }
    </style>

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Hero Section --}}
        <div class="sa-hero">
            <div class="sa-hero-icon"><i class="fas fa-user-shield"></i></div>
            <div class="sa-hero-info">
                <h2>Manajemen Admin Wilayah</h2>
                <p>Daftarkan Admin Wilayah baru dan kelola akses berdasarkan daerah KosKora</p>
            </div>
            <div class="sa-badge"><i class="fas fa-crown"></i> Super Admin</div>
        </div>

        {{-- Create Form --}}
        <div class="sa-form-card">
            <h3><i class="fas fa-user-plus"></i> Tambah Admin Wilayah Baru</h3>
            <form method="POST" action="{{ route('superadmin.admins.store') }}">
                @csrf
                <div class="sa-form-grid">
                    <div class="sa-form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="sa-form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="admin.wilayah@koskora.com" value="{{ old('email') }}" required>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="sa-form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                        @error('password')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="sa-form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                    <div class="sa-form-group full">
                        <label>Nama Wilayah / Daerah</label>
                        <input type="text" name="district" placeholder="Contoh: Pasar Minggu, Cilandak, Kebayoran Baru" value="{{ old('district') }}" required>
                        @error('district')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="sa-form-divider"></div>
                <button type="submit" class="sa-submit-btn">
                    <i class="fas fa-plus-circle"></i> Daftarkan Admin Wilayah
                </button>
            </form>
        </div>

        {{-- Admin List --}}
        <div class="sa-list-card">
            <div class="sa-list-header">
                <h3><i class="fas fa-users"></i> Daftar Admin Wilayah</h3>
                <span class="count-badge">{{ $admins->count() }} admin</span>
            </div>

            @if($admins->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <p>Belum ada admin wilayah yang terdaftar.<br>Tambahkan admin wilayah menggunakan form di atas.</p>
                </div>
            @else
                <table class="sa-table">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Wilayah</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>
                                    <div class="admin-name-cell">
                                        <div class="admin-avatar">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                                        <div>
                                            <div class="admin-name">{{ $admin->name }}</div>
                                            <div class="admin-email">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="district-pill">
                                        <i class="fas fa-map-marker-alt" style="font-size:0.65rem;"></i>
                                        {{ $admin->district ?? 'Belum diatur' }}
                                    </span>
                                </td>
                                <td><span class="role-pill">Admin Wilayah</span></td>
                                <td style="color:#9ca3af; font-size:0.82rem;">{{ $admin->created_at->format('d M Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('superadmin.admins.destroy', $admin) }}"
                                        onsubmit="return confirm('Hapus akun admin {{ $admin->name }} ({{ $admin->district }})?\nTindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>

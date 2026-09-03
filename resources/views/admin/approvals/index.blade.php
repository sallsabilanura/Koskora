<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <h1 class="page-title">Persetujuan Akun</h1>
                <p class="page-subtitle">Kelola pendaftaran akun Pencari Kos & Admin Kos yang menunggu verifikasi</p>
            </div>
        </div>
    </x-slot>

    <style>
        .ap-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; }
        .ap-tab {
            padding: 0.5rem 1.25rem;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: 1.5px solid #e5e7eb;
            color: #6b7280;
            background: #fff;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }
        .ap-tab:hover { border-color: #4f6ef7; color: #4f6ef7; }
        .ap-tab.active { background: #eff2ff; border-color: #4f6ef7; color: #4f6ef7; }
        .ap-tab .tab-count {
            background: #4f6ef7;
            color: #fff;
            font-size: 0.68rem;
            padding: 0.1rem 0.5rem;
            border-radius: 20px;
            font-weight: 700;
        }
        .ap-tab.tab-rejected.active { background: #fef2f2; border-color: #ef4444; color: #ef4444; }
        .ap-tab.tab-rejected .tab-count { background: #ef4444; }

        .ap-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .ap-card-header {
            padding: 1.1rem 1.75rem;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .ap-card-header h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }
        .ap-card-header h3 i { color: #4f6ef7; }
        .ap-table { width: 100%; border-collapse: collapse; }
        .ap-table thead th {
            padding: 0.75rem 1.5rem;
            font-size: 0.71rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #9ca3af;
            text-align: left;
            background: #fafafa;
            border-bottom: 1px solid #f3f4f6;
        }
        .ap-table tbody tr {
            border-bottom: 1px solid #f9fafb;
            transition: background 0.12s;
        }
        .ap-table tbody tr:hover { background: #f9fafb; }
        .ap-table tbody tr:last-child { border-bottom: none; }
        .ap-table td { padding: 1rem 1.5rem; vertical-align: middle; }
        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .user-avatar.user-role { background: linear-gradient(135deg, #0ea5e9, #3b82f6); }
        .user-name-cell { display: flex; align-items: center; gap: 0.75rem; }
        .user-name { color: #111827; font-weight: 600; font-size: 0.88rem; }
        .user-email { color: #9ca3af; font-size: 0.78rem; }
        .role-badge {
            padding: 0.25rem 0.7rem;
            border-radius: 20px;
            font-size: 0.73rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .role-badge.user { background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; }
        .role-badge.admin { background: #f5f3ff; border: 1px solid #ddd6fe; color: #7c3aed; }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.73rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .status-badge.pending { background: #fffbeb; border: 1px solid #fde68a; color: #d97706; }
        .status-badge.rejected { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .action-approve {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #16a34a;
            padding: 0.38rem 0.9rem;
            border-radius: 8px;
            font-size: 0.77rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .action-approve:hover { background: #dcfce7; border-color: #4ade80; transform: translateY(-1px); }
        .action-reject {
            background: #fff;
            border: 1px solid #fecaca;
            color: #ef4444;
            padding: 0.38rem 0.9rem;
            border-radius: 8px;
            font-size: 0.77rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .action-reject:hover { background: #fef2f2; border-color: #ef4444; transform: translateY(-1px); }
        .actions-cell { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }
        .empty-state { text-align: center; padding: 3.5rem 1rem; }
        .empty-state i { font-size: 2.5rem; color: #e5e7eb; margin-bottom: 0.75rem; display: block; }
        .empty-state p { color: #9ca3af; font-size: 0.88rem; }
        .alert-success {
            background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d;
            padding: 0.8rem 1.1rem; border-radius: 10px; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 0.55rem; font-size: 0.88rem; font-weight: 500;
        }
        .date-text { color: #9ca3af; font-size: 0.82rem; }
    </style>

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats --}}
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
                <div style="width:44px;height:44px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#d97706;font-size:1.2rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#111827;">{{ $counts['pending'] }}</div>
                    <div style="font-size:0.77rem;font-weight:600;color:#6b7280;">Menunggu Approval</div>
                </div>
            </div>
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1rem; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
                <div style="width:44px;height:44px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:1.2rem;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#111827;">{{ $counts['rejected'] }}</div>
                    <div style="font-size:0.77rem;font-weight:600;color:#6b7280;">Ditolak</div>
                </div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="ap-tabs">
            <a href="{{ route('superadmin.approvals.index', ['filter' => 'pending']) }}"
               class="ap-tab {{ $filter === 'pending' ? 'active' : '' }}">
                <i class="fas fa-hourglass-half"></i>
                Menunggu
                @if($counts['pending'] > 0)
                    <span class="tab-count">{{ $counts['pending'] }}</span>
                @endif
            </a>
            <a href="{{ route('superadmin.approvals.index', ['filter' => 'rejected']) }}"
               class="ap-tab tab-rejected {{ $filter === 'rejected' ? 'active' : '' }}">
                <i class="fas fa-times-circle"></i>
                Ditolak
            </a>
            <a href="{{ route('superadmin.approvals.index', ['filter' => 'all']) }}"
               class="ap-tab {{ $filter === 'all' ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                Semua
            </a>
        </div>

        {{-- Table --}}
        <div class="ap-card">
            <div class="ap-card-header">
                <h3>
                    <i class="fas fa-user-check"></i>
                    Daftar Pendaftar
                </h3>
                <span style="background:#eff2ff;color:#4f6ef7;border:1px solid #c7d2fe;padding:0.2rem 0.7rem;border-radius:20px;font-size:0.78rem;font-weight:600;">
                    {{ $pendingUsers->count() }} akun
                </span>
            </div>

            @if($pendingUsers->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-check-double"></i>
                    <p>Tidak ada pendaftar yang perlu ditinjau saat ini.<br>Semua akun sudah diproses.</p>
                </div>
            @else
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $pendingUser)
                            <tr>
                                <td>
                                    <div class="user-name-cell">
                                        <div class="user-avatar {{ $pendingUser->role === 'user' ? 'user-role' : '' }}">
                                            {{ strtoupper(substr($pendingUser->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $pendingUser->name }}</div>
                                            <div class="user-email">{{ $pendingUser->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($pendingUser->role === 'user')
                                        <span class="role-badge user">
                                            <i class="fas fa-search-location" style="font-size:0.65rem;"></i>
                                            Pencari Kos
                                        </span>
                                    @else
                                        <span class="role-badge admin">
                                            <i class="fas fa-hotel" style="font-size:0.65rem;"></i>
                                            Admin Kos
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($pendingUser->status === 'pending')
                                        <span class="status-badge pending">
                                            <i class="fas fa-hourglass-half" style="font-size:0.65rem;"></i>
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="status-badge rejected">
                                            <i class="fas fa-times" style="font-size:0.65rem;"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="date-text">{{ $pendingUser->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        {{-- Approve Button --}}
                                        <form method="POST" action="{{ route('superadmin.approvals.approve', $pendingUser) }}"
                                              onsubmit="return confirm('Setujui akun {{ $pendingUser->name }}?');">
                                            @csrf
                                            <button type="submit" class="action-approve">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>

                                        {{-- Reject Button (only for pending) --}}
                                        @if($pendingUser->status === 'pending')
                                            <form method="POST" action="{{ route('superadmin.approvals.reject', $pendingUser) }}"
                                                  onsubmit="return confirm('Tolak akun {{ $pendingUser->name }}?');">
                                                @csrf
                                                <button type="submit" class="action-reject">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>

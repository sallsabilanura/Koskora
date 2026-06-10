<div class="sidebar shadow-xl" id="sidebarPanel">
    {{-- Brand Logo Area --}}
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="flex items-center no-underline">
            <img src="{{ asset('koskora.png') }}" alt="KosKora" class="h-9 w-auto">
        </a>
    </div>

    {{-- Navigation Menu --}}
    <div class="sidebar-menu">
        @php
            $isAdmin  = auth()->user()->isAnyAdmin();
            $userRole = auth()->user()->role;
        @endphp

        {{-- Overview --}}
        <div class="sidebar-section-label">Ringkasan</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'sidebar-item active' : 'sidebar-item' }}">
            <i class="fas fa-home-alt"></i>
            <span>Dashboard</span>
        </a>

        @if($isAdmin)
            {{-- Properti Section --}}
            <div class="sidebar-section-label">Manajemen</div>

            @if(auth()->user()->isSuperAdmin())
                {{-- Super Admin: Kelola Admin Daerah --}}
                <a href="{{ route('superadmin.admins.index') }}" class="{{ request()->routeIs('superadmin.admins.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-user-shield"></i><span>Kelola Admin Daerah</span>
                </a>
            @endif

            <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-door-open"></i><span>Daftar Kamar</span>
            </a>
            <a href="{{ route('tenants.index') }}" class="{{ request()->routeIs('tenants.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-user-friends"></i><span>Data Penyewa</span>
            </a>
            <a href="{{ route('rentals.index') }}" class="{{ request()->routeIs('rentals.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-file-contract"></i><span>Kontrak Sewa</span>
            </a>

            <div class="sidebar-section-label">Finansial</div>
            <a href="{{ route('rent-payments.index') }}" class="{{ request()->routeIs('rent-payments.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-credit-card"></i><span>Pembayaran</span>
            </a>
            <a href="{{ route('admin.withdrawals.index') }}" class="{{ request()->routeIs('admin.withdrawals.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-exchange-alt"></i><span>Pencairan Dana</span>
            </a>

            <div class="sidebar-section-label">Layanan</div>
            <a href="{{ route('admin.announcements.index') }}" class="{{ request()->routeIs('admin.announcements.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-bullhorn"></i><span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.laundries.index') }}" class="{{ request()->routeIs('admin.laundries.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-tshirt"></i><span>Laundry Hub</span>
            </a>
            <a href="{{ route('admin.cleaning.index') }}" class="{{ request()->routeIs('admin.cleaning.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-broom"></i><span>Cleaning Service</span>
            </a>
            <a href="{{ route('admin.security.index') }}" class="{{ request()->routeIs('admin.security.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-shield-alt"></i><span>Keamanan</span>
            </a>
        @endif

        @if($userRole === 'user')
            <div class="sidebar-section-label">Layanan Saya</div>
            <a href="{{ route('rent-payments.my-payments') }}" class="{{ request()->routeIs('rent-payments.my-payments') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-file-invoice-dollar"></i><span>Tagihan Sewa</span>
            </a>
            <a href="{{ route('user.announcements.index') }}" class="{{ request()->routeIs('user.announcements.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-info-circle"></i><span>Info & Broadcast</span>
            </a>
            <a href="{{ route('user.laundry.index') }}" class="{{ request()->routeIs('user.laundry.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-soap"></i><span>Pesan Laundry</span>
            </a>
            <a href="{{ route('user.cleaning.index') }}" class="{{ request()->routeIs('user.cleaning.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                <i class="fas fa-broom"></i><span>Pesan Cleaning</span>
            </a>
        @endif

        @if(in_array($userRole, ['laundry', 'cleaner', 'security']))
            <div class="sidebar-section-label">Panel Mitra</div>
            @if($userRole === 'laundry')
                <a href="{{ route('laundry.orders.index') }}" class="{{ request()->routeIs('laundry.orders.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-clipboard-list"></i><span>Pesanan Masuk</span>
                </a>
                <a href="{{ route('laundry.services.index') }}" class="{{ request()->routeIs('laundry.services.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-tags"></i><span>Katalog Layanan</span>
                </a>
                <a href="{{ route('laundry.withdrawals.index') }}" class="{{ request()->routeIs('laundry.withdrawals.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-wallet"></i><span>Keuangan</span>
                </a>
            @endif
            @if($userRole === 'cleaner')
                <a href="{{ route('cleaner.orders.index') }}" class="{{ request()->routeIs('cleaner.orders.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-tasks"></i><span>Daftar Tugas</span>
                </a>
                <a href="{{ route('cleaner.withdrawals.index') }}" class="{{ request()->routeIs('cleaner.withdrawals.*') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-wallet"></i><span>Keuangan</span>
                </a>
            @endif
            @if($userRole === 'security')
                <a href="{{ route('security.dashboard') }}" class="{{ request()->routeIs('security.dashboard') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-tachometer-alt"></i><span>Staff Dashboard</span>
                </a>
                <a href="{{ route('security.report') }}" class="{{ request()->routeIs('security.report') ? 'sidebar-item active' : 'sidebar-item' }}">
                    <i class="fas fa-exclamation-triangle"></i><span>Lapor Kejadian</span>
                </a>
            @endif
        @endif
    </div>

    {{-- Sidebar Bottom Action --}}
    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="user-card group">
            <div class="user-avatar shadow-md group-hover:scale-105 transition-transform">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="flex-1 min-w-0">
                <div class="user-name truncate">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
            <i class="fas fa-chevron-right text-[10px] text-slate-300 group-hover:text-brand transition-colors"></i>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn group">
                <i class="fas fa-power-off transition-transform group-hover:rotate-12"></i>
                <span>Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</div>


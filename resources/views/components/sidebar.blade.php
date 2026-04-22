<div class="sidebar bg-white border-r border-slate-100" id="sidebarPanel">
    <!-- Close button (mobile only) -->
    <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close menu">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <div class="sidebar-header flex items-center px-8 py-8 md:py-10">
        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-10 md:h-12 w-auto">
    </div>

    <div class="sidebar-menu flex-1 px-3 md:px-4 space-y-1 overflow-y-auto scrollbar-hide">
        @php
            $isAdmin = auth()->user()->isAdmin();
            $hasActiveRental = auth()->user()->tenant && auth()->user()->tenant->rentals()->where('status', 'active')->exists();
        @endphp

        <!-- Essential -->
        <div class="px-4 py-3 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Menu Utama</div>
        
        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-sm font-bold truncate">Dashboard</span>
        </a>

        @if($isAdmin)
            <div class="px-4 py-4 md:py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Manajemen</div>
            
            <a href="{{ route('rooms.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rooms.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('rooms.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="text-sm font-bold truncate">Rooms</span>
            </a>

            <a href="{{ route('tenants.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('tenants.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('tenants.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="text-sm font-bold truncate">Tenants</span>
            </a>

            <a href="{{ route('rentals.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rentals.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('rentals.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-bold truncate">Rentals</span>
            </a>

            <a href="{{ route('admin.laundries.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.laundries.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.laundries.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-sm font-bold truncate">Laundry Hub</span>
            </a>

            <a href="{{ route('admin.announcements.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.announcements.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.announcements.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span class="text-sm font-bold truncate">Pengumuman</span>
            </a>

            <a href="{{ route('admin.cleaning.cleaners') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.cleaning.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.cleaning.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-sm font-bold truncate">Cleaning Service</span>
            </a>
        @endif

        @if(auth()->user()->role === 'laundry')
            <a href="{{ route('laundry.orders.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('laundry.orders.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('laundry.orders.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="text-sm font-bold truncate">Daftar Pesanan</span>
            </a>

            <a href="{{ route('laundry.services.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('laundry.services.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('laundry.services.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="text-sm font-bold truncate">Kelola Layanan</span>
            </a>
        @endif

        @if(auth()->user()->role === 'cleaner')
            <a href="{{ route('cleaner.orders.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('cleaner.orders.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('cleaner.orders.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold truncate">Daftar Tugas</span>
            </a>
        @endif

        @if(auth()->user()->role === 'user')
            <div class="px-4 py-4 md:py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Layanan Tenant</div>
            
            <a href="{{ route('user.announcements.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.announcements.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('user.announcements.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold truncate">Pengumuman</span>
            </a>

            <a href="{{ url('/') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-slate-50 hover:text-brand-blue" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 text-slate-300 group-hover:text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span class="text-sm font-bold truncate">Cari Kos</span>
            </a>

            <a href="{{ route('rent-payments.my-payments') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rent-payments.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('rent-payments.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="text-sm font-bold truncate">Tagihan Saya</span>
            </a>

            <a href="{{ route('user.laundry.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.laundry.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('user.laundry.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold truncate">Laundry</span>
            </a>

            <a href="{{ route('user.cleaning.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.cleaning.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('user.cleaning.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 0112 3a9 9 0 017.879 14.804M15 10h.01M9 10h.01M12 21v-6"></path></svg>
                <span class="text-sm font-bold truncate">Cleaning</span>
            </a>
        @endif

        <div class="px-4 py-4 md:py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Akun</div>

        <a href="{{ route('profile.edit') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all mb-2 {{ request()->routeIs('profile.*') ? 'bg-brand-blue text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}" onclick="closeSidebar()">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-bold truncate">My Profile</span>
        </a>

        <!-- User info (mobile) -->
        <div class="flex items-center gap-3 px-4 py-3 mb-2 md:hidden">
            <div class="w-9 h-9 rounded-full bg-brand-blue/10 flex items-center justify-center text-brand-blue font-black text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-bold text-slate-700 truncate">{{ auth()->user()->name }}</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ auth()->user()->role }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 rounded-xl text-brand-red font-bold text-sm hover:bg-rose-50 transition-all group btn-touch">
                <svg class="w-5 h-5 mr-3 text-brand-red group-hover:translate-x-1 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</div>

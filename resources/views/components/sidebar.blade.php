<div class="sidebar bg-white border-r border-slate-100 flex flex-col h-screen sticky top-0">
    <div class="sidebar-header flex items-center px-8 py-10">
        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-12 w-auto">
    </div>

    <div class="sidebar-menu flex-1 px-4 space-y-1 overflow-y-auto scrollbar-hide">
        @php
            $isAdmin = auth()->user()->isAdmin();
            $hasActiveRental = auth()->user()->tenant && auth()->user()->tenant->rentals()->where('status', 'active')->exists();
        @endphp

        <!-- Essential -->
        <div class="px-4 py-3 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Menu Utama</div>
        
        <a href="{{ route('dashboard') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-sm font-bold">Dashboard</span>
        </a>

        @if($isAdmin)
            <div class="px-4 py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Manajemen</div>
            
            <a href="{{ route('rooms.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rooms.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('rooms.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <span class="text-sm font-bold">Rooms</span>
            </a>

            <a href="{{ route('tenants.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('tenants.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tenants.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="text-sm font-bold">Tenants</span>
            </a>

            <a href="{{ route('rentals.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rentals.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('rentals.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span class="text-sm font-bold">Rentals</span>
            </a>

            <a href="{{ route('rent-payments.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rent-payments.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('rent-payments.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">Payments</span>
            </a>

            <div class="px-4 py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Services</div>

            <a href="{{ route('admin.laundries.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.laundries.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.laundries.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-sm font-bold">Laundry Hub</span>
            </a>

            <a href="{{ route('admin.cleaning.cleaners') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.cleaning.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.cleaning.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-sm font-bold">Cleaning Service</span>
            </a>
        @elseif(auth()->user()->role === 'laundry')
            <div class="px-4 py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Laundry Dashboard</div>
            
            <a href="{{ route('laundry.orders.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('laundry.orders.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('laundry.orders.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="text-sm font-bold">Daftar Pesanan</span>
            </a>
        @elseif(auth()->user()->role === 'cleaner')
            <div class="px-4 py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Cleaner Tasks</div>
            <a href="{{ route('cleaner.orders.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('cleaner.orders.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('cleaner.orders.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">Daftar Tugas</span>
            </a>
        @else
            <!-- User specific menu -->
            <div class="px-4 py-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">My Services</div>
            @if(!$hasActiveRental)
                <a href="{{ url('/') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all text-slate-500 hover:bg-slate-50 hover:text-brand-blue">
                    <svg class="w-5 h-5 mr-3 text-slate-300 group-hover:text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span class="text-sm font-bold">Cari Kos</span>
                </a>
            @endif

            <a href="{{ route('rent-payments.my-payments') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('rent-payments.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('rent-payments.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="text-sm font-bold">Tagihan Saya</span>
            </a>

            <a href="{{ route('user.laundry.index') }}" class="group flex items-center px-4 py-3 rounded-xl transition-all {{ request()->routeIs('user.laundry.*') ? 'bg-brand-blue text-white shadow-lg shadow-blue-100' : 'text-slate-500 hover:bg-slate-50 hover:text-brand-blue' }}">
                <svg class="w-5 h-5 mr-3 {{ request()->routeIs('user.laundry.*') ? 'text-white' : 'text-slate-300 group-hover:text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">Laundry</span>
            </a>
        @endif
    </div>
    
    <div class="p-6 border-t border-slate-50 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 rounded-xl text-brand-red font-bold text-sm hover:bg-rose-50 transition-all group">
                <svg class="w-5 h-5 mr-3 text-brand-red group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</div>

@php
    $currentRoute = Route::currentRouteName();
    $tabs = [
        ['route' => 'security.dashboard',  'label' => 'Terminal',        'icon' => 'fa-shield-alt'],
        ['route' => 'security.report',     'label' => 'Laporan',         'icon' => 'fa-exclamation-triangle'],
        ['route' => 'security.attendance', 'label' => 'Absensi',         'icon' => 'fa-camera'],
        ['route' => 'security.shifts',     'label' => 'Jadwal Shift',    'icon' => 'fa-calendar-alt'],
        ['route' => 'security.scan',       'label' => 'Cek Tiket',       'icon' => 'fa-qrcode'],
    ];
@endphp

<div class="mb-6 overflow-x-auto no-scrollbar">
    <div class="flex gap-2 pb-1">
        @foreach($tabs as $tab)
            @php
                $isActive = $currentRoute === $tab['route'];
            @endphp
            <a href="{{ route($tab['route']) }}"
               class="flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200
                      {{ $isActive
                          ? 'bg-brand text-white shadow-lg shadow-brand/20'
                          : 'bg-white border border-slate-100 text-slate-500 hover:text-brand hover:border-brand shadow-sm' }}">
                <i class="fas {{ $tab['icon'] }} text-[10px]"></i>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>

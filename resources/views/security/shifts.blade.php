<x-app-layout>
    @section('header_title', 'Shift Schedule')

    <div class="max-w-2xl mx-auto py-4 space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex items-center gap-3 px-1">
            <span class="w-1.5 h-6 bg-brand rounded-full"></span>
            <div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight uppercase">Jadwal Penugasan</h3>
                @if($shifts->count() > 0)
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                        Periode: {{ \Carbon\Carbon::parse($shifts->first()->date)->format('d M') }} - {{ \Carbon\Carbon::parse($shifts->last()->date)->format('d M Y') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- ===== SHIFTS LIST ===== --}}
        <div class="space-y-4">
            @forelse($shifts as $shift)
                @php $isToday = \Carbon\Carbon::parse($shift->date)->isToday(); @endphp
                <div class="stat-card !p-5 flex items-center justify-between group {{ $isToday ? '!border-brand !bg-brand-light/20' : '' }}">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-xl flex flex-col items-center justify-center group-hover:bg-brand-light transition-colors {{ $isToday ? '!bg-brand !border-brand text-white' : '' }}">
                            <span class="text-[9px] font-bold uppercase leading-none mb-1 {{ $isToday ? 'text-white/70' : 'text-slate-400' }}">{{ \Carbon\Carbon::parse($shift->date)->format('M') }}</span>
                            <span class="text-xl font-bold leading-none">{{ \Carbon\Carbon::parse($shift->date)->format('d') }}</span>
                        </div>
                        <div>
                            <h4 class="text-[15px] font-bold text-slate-900 tracking-tight">{{ $shift->location }}</h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tight">
                                    {{ \Carbon\Carbon::parse($shift->date)->translatedFormat('l') }} • {{ $shift->start_time }} - {{ $shift->end_time }}
                                </span>
                                <span class="badge badge-purple !text-[9px] !px-1.5">{{ \Carbon\Carbon::parse($shift->start_time)->diffInHours(\Carbon\Carbon::parse($shift->end_time)) }} Jam</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($isToday)
                        <div class="hidden sm:block">
                            <span class="badge badge-green !bg-emerald-500 !text-white shadow-lg shadow-emerald-200">HARI INI</span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="far fa-calendar-times"></i></div>
                    <p>Belum ada jadwal shift yang terdaftar untuk Anda.</p>
                </div>
            @endforelse
        </div>

        {{-- ===== FOOTER INFO ===== --}}
        <div class="stat-card !bg-slate-900 !border-none text-white relative overflow-hidden">
            <div class="relative z-10 space-y-2">
                <div class="flex items-center gap-2 text-brand-light">
                    <i class="fas fa-info-circle text-xs"></i>
                    <h4 class="text-[11px] font-bold uppercase tracking-widest">Informasi Operasional</h4>
                </div>
                <p class="text-xs font-medium text-slate-400 leading-relaxed italic">
                    Jika terdapat kendala atau ketidakhadiran, harap lapor ke Supervisor minimal 24 jam sebelum shift dimulai.
                </p>
            </div>
            <div class="absolute -bottom-6 -right-6 w-20 h-20 bg-brand/20 rounded-full blur-2xl"></div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    @section('header_title', 'Staff Dashboard')

    <div class="max-w-2xl mx-auto space-y-8 animate-fade-in pb-20">
        {{-- ===== SHIFT OVERVIEW ===== --}}
        <div class="container-card !bg-slate-900 !text-white !border-none shadow-2xl relative overflow-hidden group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Petugas Aktif</span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight">Selamat Bertugas,<br>{{ auth()->user()->name }}</h2>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <i class="fas fa-map-marker-alt text-brand"></i>
                            {{ $todayShift->location ?? 'Area KosKora' }}
                        </div>
                        <div class="flex items-center gap-2 text-slate-400 text-xs">
                            <i class="fas fa-clock text-brand"></i>
                            {{ $todayShift->start_time ?? '--:--' }} - {{ $todayShift->end_time ?? '--:--' }}
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Waktu Lokal</p>
                    <h3 id="live-clock" class="text-3xl font-black tabular-nums tracking-tighter">{{ now()->format('H:i') }}</h3>
                </div>
            </div>
            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-brand/10 rounded-full blur-3xl"></div>
        </div>

        {{-- ===== QUICK ACTIONS ===== --}}
        <div class="grid grid-cols-2 gap-6">
            <a href="{{ route('security.attendance') }}" class="container-card flex flex-col items-center gap-4 text-center hover:border-brand transition-all group">
                <div class="w-14 h-14 {{ $nextType === 'in' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500' }} rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas {{ $nextType === 'in' ? 'fa-fingerprint' : 'fa-sign-out-alt' }}"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Absensi {{ $nextType === 'in' ? 'Masuk' : 'Pulang' }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Catat kehadiran shift hari ini</p>
                </div>
            </a>
            <a href="{{ route('security.report') }}" class="container-card flex flex-col items-center gap-4 text-center hover:border-brand transition-all group">
                <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-shield-exclamation"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Lapor Kejadian</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Catat temuan atau insiden</p>
                </div>
            </a>
        </div>

        {{-- ===== RECENT LOGS ===== --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Aktivitas Hari Ini</h3>
                <span class="text-[11px] text-slate-400 font-medium">{{ now()->translatedFormat('d F Y') }}</span>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <tbody>
                        @forelse($myAttendances as $att)
                            <tr class="group">
                                <td class="!pl-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 group-hover:scale-105 transition-transform">
                                            <img src="{{ asset('storage/' . $att->image) }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full {{ $att->type === 'in' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                <span class="text-xs font-bold text-slate-900">Absen {{ $att->type === 'in' ? 'Masuk' : 'Pulang' }}</span>
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-0.5 uppercase tracking-wider font-medium">{{ $att->location }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-sm font-bold text-slate-900">{{ $att->created_at->format('H:i') }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">WIB</div>
                                </td>
                                <td class="text-right !pr-6">
                                    @php
                                        $statusLabel = 'Ontime'; $badge = 'badge-green';
                                        if($todayShift) {
                                            if($att->type === 'in' && $att->created_at->format('H:i:s') > $todayShift->start_time) {
                                                $statusLabel = 'Late'; $badge = 'badge-amber';
                                            } elseif($att->type === 'out' && $att->created_at->format('H:i:s') < $todayShift->end_time) {
                                                $statusLabel = 'Early'; $badge = 'badge-red';
                                            }
                                        }
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $statusLabel }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-fingerprint"></i></div>
                                        <p class="text-slate-500">Belum ada aktivitas kehadiran tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    @if (session('success'))
        <div id="successModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md animate-fade-in">
            <div class="bg-white rounded-[2rem] w-full max-w-sm p-10 text-center space-y-6 shadow-2xl">
                <div class="w-20 h-20 bg-emerald-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Aksi Berhasil</h3>
                    <p class="text-sm text-slate-500 mt-2">{{ session('success') }}</p>
                </div>
                <button onclick="document.getElementById('successModal').classList.add('hidden')" class="w-full btn btn-primary">Lanjutkan</button>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('live-clock');
            if (clock) {
                clock.innerText = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
            }
        }
        setInterval(updateClock, 30000);
    </script>
    @endpush
</x-app-layout>

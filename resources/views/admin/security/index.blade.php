<x-app-layout>
    @section('header_title', 'Security Management')

    <div class="space-y-6 animate-fade-in">
        @if ($message = Session::get('success'))
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100 mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        @endif

        @if ($errors->any())
            <div class="badge badge-red w-full justify-start p-3 rounded-xl border border-red-100 mb-4 flex-col items-start gap-1">
                <div class="flex items-center font-bold">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Terdapat Kesalahan:
                </div>
                <ul class="list-disc list-inside text-[11px] ml-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===== TAB NAVIGATION ===== --}}
        <div class="flex overflow-x-auto gap-2 pb-1 no-scrollbar">
            @php
                $tabs = [
                    ['id' => 'data-security', 'label' => 'Data Satpam', 'icon' => 'fa-shield-alt'],
                    ['id' => 'laporan', 'label' => 'Laporan Kejadian', 'icon' => 'fa-exclamation-triangle'],
                    ['id' => 'absensi', 'label' => 'Log Absensi', 'icon' => 'fa-camera'],
                    ['id' => 'shift', 'label' => 'Jadwal Shift', 'icon' => 'fa-calendar-alt'],
                ];
            @endphp
            @foreach($tabs as $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" id="tab-btn-{{ $tab['id'] }}"
                    class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                    <i class="fas {{ $tab['icon'] }} text-[10px]"></i>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>


        {{-- ===== TAB: DATA SECURITY ===== --}}
        <div id="tab-data-security" class="tab-panel space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total
                                Personil</p>
                            <h3 class="text-2xl font-bold text-slate-800">{{ $securityStaff->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-brand-light text-brand rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Laporan
                                Pending</p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $allReports->where('status', 'pending')->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button onclick="toggleStaffModal()"
                        class="btn btn-primary flex-1 !h-full flex flex-col items-center justify-center gap-2 py-4">
                        <i class="fas fa-user-plus text-lg"></i>
                        <span class="text-[10px]">Tambah Personil</span>
                    </button>
                    <button onclick="toggleShiftModal()"
                        class="btn btn-ghost flex-1 !h-full flex flex-col items-center justify-center gap-2 py-4 bg-white border-slate-200">
                        <i class="fas fa-calendar-alt text-lg"></i>
                        <span class="text-[10px]">Atur Shift</span>
                    </button>
                </div>
            </div>

            <div class="table-wrap">
                <div class="p-4 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Daftar Personil Aktif</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>Email Akun</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($securityStaff as $staff)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $staff->name }}</span>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[12px] font-medium">{{ $staff->email }}</td>
                                <td class="text-center"><span class="badge badge-green">Aktif</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== TAB: LAPORAN ===== --}}
        <div id="tab-laporan" class="tab-panel hidden">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Reporter & Lokasi</th>
                            <th>Judul Kejadian</th>
                            <th>Waktu Kejadian</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allReports as $report)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-800">{{ $report->user->name }}</div>
                                    <div class="text-[10px] text-brand font-bold uppercase">{{ $report->location }}</div>
                                </td>
                                <td class="text-[12px] text-slate-600 font-medium">{{ $report->title }}</td>
                                <td class="text-slate-400 text-[11px] font-medium">
                                    {{ $report->incident_date->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $report->status == 'resolved' ? 'badge-green' : 'badge-amber' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="tab-absensi" class="tab-panel hidden space-y-4">
            {{-- ===== FILTERS ===== --}}
            <div class="stat-card !p-4">
                <form action="{{ route('admin.security.index') }}" method="GET"
                    class="flex flex-col md:flex-row items-center gap-4">
                    <input type="hidden" name="tab" value="absensi">
                    <div class="relative flex-1 w-full">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama personil..."
                            class="w-full bg-slate-50 border-slate-100 rounded-xl text-[12px] font-bold py-3 pl-10 pr-4 focus:ring-brand focus:border-brand transition-all outline-none">
                    </div>
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-48">
                            <i
                                class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                                class="w-full bg-slate-50 border-slate-100 rounded-xl text-[12px] font-bold py-3 pl-10 pr-4 focus:ring-brand focus:border-brand transition-all outline-none">
                        </div>
                        <button type="submit"
                            class="btn btn-primary !h-11 !px-6 shadow-lg shadow-brand/20">Filter</button>
                        @if(request()->has('search') || request()->has('month'))
                            <a href="{{ route('admin.security.index', ['tab' => 'absensi']) }}"
                                class="btn btn-ghost !h-11 !px-4 bg-slate-100 text-slate-400 hover:text-rose-500"
                                title="Reset Filter">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Personil</th>
                            <th>Tanggal</th>
                            <th>Jam Datang</th>
                            <th>Jam Pulang</th>
                            <th>Lokasi</th>
                            <th class="text-center">Shift Hari Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allAttendances as $log)
                            @php
                                $shift = \App\Models\SecurityShift::where('user_id', $log['user']->id)
                                    ->whereDate('date', $log['date'])
                                    ->first();
                            @endphp
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($log['user']->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-800 text-[12px]">{{ $log['user']->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-[11px] font-bold text-slate-500 uppercase">
                                        {{ \Carbon\Carbon::parse($log['date'])->format('d M Y') }}</div>
                                </td>
                                <td>
                                    @if($log['in'])
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="font-black text-slate-900 text-[12px]">
                                                    {{ $log['in']->created_at->format('H:i') }}</div>
                                                @php
                                                    $inStatus = 'Tepat Waktu';
                                                    $inBadge = 'badge-green';
                                                    if ($shift && $log['in']->created_at->format('H:i:s') > $shift->start_time) {
                                                        $inStatus = 'Terlambat';
                                                        $inBadge = 'badge-amber';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $inBadge }} !text-[8px] !px-1.5 !py-0">{{ $inStatus }}</span>
                                            </div>
                                            <button onclick="viewPhoto('{{ asset('storage/' . $log['in']->image) }}')"
                                                class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-brand">
                                                <i class="fas fa-camera text-[10px]"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-300">Belum Absen</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log['out'])
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="font-black text-slate-900 text-[12px]">
                                                    {{ $log['out']->created_at->format('H:i') }}</div>
                                                @php
                                                    $outStatus = 'Sesuai';
                                                    $outBadge = 'badge-green';
                                                    if ($shift && $log['out']->created_at->format('H:i:s') < $shift->end_time) {
                                                        $outStatus = 'Pulang Cepat';
                                                        $outBadge = 'badge-rose';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $outBadge }} !text-[8px] !px-1.5 !py-0">{{ $outStatus }}</span>
                                            </div>
                                            <button onclick="viewPhoto('{{ asset('storage/' . $log['out']->image) }}')"
                                                class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-brand">
                                                <i class="fas fa-camera text-[10px]"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-300">Belum Absen</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="text-[11px] font-bold text-slate-700 uppercase">{{ $log['location'] }}</span>
                                </td>
                                <td class="text-center">
                                    @if($shift)
                                        <span
                                            class="text-[10px] font-black text-slate-400">{{ substr($shift->start_time, 0, 5) }}
                                            - {{ substr($shift->end_time, 0, 5) }}</span>
                                    @else
                                        <span class="text-[10px] text-slate-300 italic">No Shift</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== TAB: SHIFT ===== --}}
        <div id="tab-shift" class="tab-panel hidden">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Personil</th>
                            <th>Lokasi</th>
                            <th>Jam Kerja</th>
                            <th>Periode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compactShifts as $shift)
                            <tr>
                                <td class="font-semibold text-slate-800">{{ $shift['user']->name }}</td>
                                <td><span class="badge badge-purple">{{ $shift['location'] }}</span></td>
                                <td class="text-[11px] font-bold text-slate-600">{{ $shift['start_time'] }} -
                                    {{ $shift['end_time'] }}</td>
                                <td class="text-slate-500 text-[11px] font-medium">
                                    {{ \Carbon\Carbon::parse($shift['start_date'])->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ===== MODALS ===== --}}
    {{-- Photo Preview Modal --}}
    <div id="photoModal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md"
        onclick="this.classList.add('hidden')">
        <div class="relative bg-white p-2 rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden"
            onclick="event.stopPropagation()">
            <img id="modalPhoto" src="" class="w-full h-auto rounded-2xl shadow-inner">
            <button onclick="document.getElementById('photoModal').classList.add('hidden')"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/50 text-white flex items-center justify-center hover:bg-black transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    {{-- Shift Modal --}}
    <div id="shiftModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
        style="display: none;">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-modal-up">
            <form action="{{ route('admin.security.shift.store') }}" method="POST" class="p-10 space-y-8">
                @csrf
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Atur Jadwal Shift</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Alokasi tugas
                            personil</p>
                    </div>
                    <button type="button" onclick="toggleShiftModal()"
                        class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all flex items-center justify-center">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Personil
                            Satpam</label>
                        <select name="user_id" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none appearance-none">
                            @foreach($securityStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Lokasi
                            Penugasan</label>
                        <select name="location" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none appearance-none">
                            <option value="">Pilih Properti</option>
                            @foreach($properties as $property)
                                <option value="{{ $property }}">{{ $property }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Mulai
                                Tanggal</label>
                            <input type="date" name="start_date" required
                                class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Sampai
                                Tanggal</label>
                            <input type="date" name="end_date" required
                                class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Jam
                                Mulai</label>
                            <input type="time" name="start_time" required
                                class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Jam
                                Selesai</label>
                            <input type="time" name="end_time" required
                                class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                        </div>
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-5 bg-brand text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-brand/20 hover:bg-brand-dark transition-all transform active:scale-95">Simpan
                    Jadwal Shift</button>
            </form>
        </div>
    </div>

    {{-- Staff Modal --}}
    <div id="staffModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
        style="display: none;">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden animate-modal-up">
            <form action="{{ route('admin.security.staff.store') }}" method="POST" class="p-10 space-y-8">
                @csrf
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Tambah Personil</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Daftarkan satpam
                            baru</p>
                    </div>
                    <button type="button" onclick="toggleStaffModal()"
                        class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all flex items-center justify-center">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Nama
                            Lengkap</label>
                        <input type="text" name="name" placeholder="Budi Santoso" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Email
                            Username</label>
                        <input type="email" name="email" placeholder="budi@koskora.com" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand focus:border-brand transition-all outline-none">
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl active:scale-95 transition-all">Daftarkan
                    Satpam</button>
            </form>
        </div>
    </div>

    <script>
        const ACTIVE_TAB_CLASSES = ['!bg-brand', '!text-white', '!border-brand', 'shadow-brand/20'];
        const INACTIVE_TAB_CLASSES = ['bg-white', 'text-slate-500', 'border-slate-100'];

        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove(...ACTIVE_TAB_CLASSES);
                btn.classList.add(...INACTIVE_TAB_CLASSES);
            });

            document.getElementById('tab-' + tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            activeBtn.classList.remove(...INACTIVE_TAB_CLASSES);
            activeBtn.classList.add(...ACTIVE_TAB_CLASSES);
            localStorage.setItem('securityAdminTab', tabId);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        const savedTab = tabParam || localStorage.getItem('securityAdminTab') || 'data-security';
        switchTab(savedTab);

        function toggleShiftModal() {
            const el = document.getElementById('shiftModal');
            el.classList.toggle('hidden');
            el.style.display = el.classList.contains('hidden') ? 'none' : 'flex';
        }
        function toggleStaffModal() {
            const el = document.getElementById('staffModal');
            el.classList.toggle('hidden');
            el.style.display = el.classList.contains('hidden') ? 'none' : 'flex';
        }
        function viewPhoto(url) {
            const modal = document.getElementById('photoModal');
            const img = document.getElementById('modalPhoto');
            img.src = url;
            modal.classList.remove('hidden');
        }
    </script>
</x-app-layout>
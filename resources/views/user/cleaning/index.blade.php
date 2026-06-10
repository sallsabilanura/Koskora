<x-app-layout>
    @section('header_title', 'Cleaning')

    <div class="space-y-8 animate-fade-in pb-20">
        {{-- ===== HERO BANNER ===== --}}
        <div class="p-8 md:p-12 bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Cleaning Service</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-slate-800 tracking-tighter leading-none">Bebersih Kamar</h2>
                <p class="text-slate-400 text-xs md:text-sm font-bold max-w-xl leading-relaxed uppercase tracking-widest">
                    Jadwalkan pembersihan kamar Anda dengan petugas profesional kami.
                </p>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm text-xs font-bold">
                <i class="fas fa-check-circle mr-3"></i>
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- MAIN BOOKING --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- STEP 1: CLEANER --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-1.5 h-6 bg-slate-900 rounded-full"></div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Petugas</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($cleaners as $cleaner)
                            <div onclick="selectCleaner({{ $cleaner->id }}, '{{ $cleaner->user->name }}')" 
                                 class="cleaner-card group cursor-pointer bg-white rounded-[2.5rem] border border-slate-100 p-6 hover:border-brand hover:shadow-xl transition-all relative overflow-hidden" 
                                 id="cleaner-{{ $cleaner->id }}">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 border border-slate-50 shadow-sm flex-shrink-0">
                                        @if($cleaner->photo)
                                            <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <i class="fas fa-user-shield text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-black text-slate-800 tracking-tight truncate uppercase leading-none mb-2">{{ $cleaner->user->name }}</h4>
                                        <div class="flex items-center text-amber-400 gap-0.5 mb-2">
                                            @for($i=0; $i<5; $i++) <i class="fas fa-star text-[8px]"></i> @endfor
                                            <span class="text-[9px] font-black text-slate-400 ml-1">4.9</span>
                                        </div>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest line-clamp-1 italic">Pro Cleaner</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4 w-6 h-6 bg-brand rounded-full hidden border-2 border-white shadow-md items-center justify-center selection-indicator">
                                    <i class="fas fa-check text-[10px] text-white"></i>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 p-10 bg-slate-50 rounded-[2.5rem] text-center text-slate-300 text-[10px] font-black uppercase tracking-widest">Petugas tidak tersedia</div>
                        @endforelse
                    </div>
                </div>

                {{-- STEP 2: PACKAGE --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-1.5 h-6 bg-brand rounded-full"></div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Paket</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($packages as $package)
                            <div onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})" 
                                 class="package-card group cursor-pointer bg-white rounded-[2.5rem] border border-slate-100 p-8 hover:border-brand hover:shadow-xl transition-all relative overflow-hidden" 
                                 id="package-{{ $package->id }}">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-3 py-1 bg-slate-900 text-white text-[8px] font-black rounded-full uppercase tracking-widest">{{ $package->name }}</span>
                                        <div class="text-right">
                                            <div class="text-lg font-black text-brand tracking-tighter italic">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 leading-relaxed uppercase tracking-widest">{{ Str::limit($package->description, 60) }}</p>
                                </div>
                                <div class="absolute top-4 right-4 w-6 h-6 bg-brand rounded-full hidden border-2 border-white shadow-md items-center justify-center selection-indicator">
                                    <i class="fas fa-check text-[10px] text-white"></i>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 p-10 bg-slate-50 rounded-[2.5rem] text-center text-slate-300 text-[10px] font-black uppercase tracking-widest">Paket tidak tersedia</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- SUMMARY SIDEBAR --}}
            <div class="space-y-8">
                <div class="sticky top-24">
                    <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10 space-y-8">
                            <h4 class="text-lg font-black tracking-tight uppercase">Ringkasan</h4>
                            
                            <form action="{{ route('user.cleaning.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="cleaner_id" id="formCleanerId" required>
                                <input type="hidden" name="package_id" id="formPackageId" required>

                                <div class="space-y-4">
                                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Petugas</span>
                                        <span id="summaryCleaner" class="text-[10px] font-black uppercase tracking-tighter">Pilih Petugas</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Layanan</span>
                                        <span id="summaryPackage" class="text-[10px] font-black uppercase tracking-tighter">Pilih Paket</span>
                                    </div>
                                    
                                    <div class="space-y-2 pt-2">
                                        <label class="block text-[9px] font-black text-brand uppercase tracking-widest">Waktu Kedatangan</label>
                                        <input type="datetime-local" name="scheduled_at" required class="w-full bg-white/5 border-white/10 rounded-xl text-xs py-4 px-5 text-white focus:ring-brand focus:border-brand transition-all outline-none">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-[9px] font-black text-brand uppercase tracking-widest">Catatan</label>
                                        <textarea name="notes" rows="2" placeholder="Tulis instruksi tambahan..." class="w-full bg-white/5 border-white/10 rounded-2xl text-xs p-5 text-white placeholder-white/20 focus:ring-brand focus:border-brand transition-all outline-none"></textarea>
                                    </div>
                                </div>

                                <div class="pt-4 space-y-6">
                                    <div class="flex justify-between items-end border-t border-white/10 pt-6">
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Total Tagihan</span>
                                        <span class="text-2xl font-black tracking-tighter italic" id="summaryTotal">Rp 0</span>
                                    </div>
                                    <button type="submit" class="w-full py-5 bg-brand text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-brand/20 transition-all hover:scale-105 active:scale-95 disabled:opacity-20 disabled:pointer-events-none" id="submitBtn" disabled>
                                        PESAN SEKARANG
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand opacity-10 blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
                    </div>

                    {{-- HISTORY PREVIEW --}}
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center gap-2 px-2">
                            <div class="w-1.5 h-4 bg-slate-300 rounded-full"></div>
                            <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pesanan Terakhir</h4>
                        </div>
                        <div class="space-y-3">
                            @foreach($myOrders->take(3) as $order)
                                <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm flex items-center justify-between">
                                    <div class="min-w-0">
                                        <div class="text-[10px] font-black text-slate-800 uppercase tracking-tight truncate leading-none mb-1">{{ $order->package->name }}</div>
                                        <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $order->scheduled_at->format('d M, H:i') }}</div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[7px] font-black uppercase tracking-widest border {{ $order->status == 'done' ? 'bg-emerald-50 text-emerald-500 border-emerald-100' : 'bg-blue-50 text-blue-500 border-blue-100' }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cleaner-card.selected, .package-card.selected { border-color: #2563eb; background-color: #f0f7ff; }
        .cleaner-card.selected .selection-indicator, .package-card.selected .selection-indicator { display: flex; }
    </style>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        let selectedCleanerID = null;
        let selectedPackageID = null;

        function selectCleaner(id, name) {
            selectedCleanerID = id;
            document.getElementById('formCleanerId').value = id;
            document.getElementById('summaryCleaner').innerText = name;
            document.querySelectorAll('.cleaner-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('cleaner-' + id).classList.add('selected');
            checkSubmit();
        }

        function selectPackage(id, name, price) {
            selectedPackageID = id;
            document.getElementById('formPackageId').value = id;
            document.getElementById('summaryPackage').innerText = name;
            document.getElementById('summaryTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            document.querySelectorAll('.package-card').forEach(p => p.classList.remove('selected'));
            document.getElementById('package-' + id).classList.add('selected');
            checkSubmit();
        }

        function checkSubmit() {
            document.getElementById('submitBtn').disabled = !(selectedCleanerID && selectedPackageID);
        }
    </script>
</x-app-layout>

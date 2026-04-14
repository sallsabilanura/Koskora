<x-app-layout>
    @section('header_title', 'Bebersih Kamar')

    <div class="space-y-10 pb-20">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 italic tracking-tight">Layanan Bebersih Kamar</h2>
                <p class="text-slate-500 font-medium opacity-80">Pilih pembersih favorit Anda dan jadwalkan pembersihan sekarang.</p>
            </div>
            <div class="px-6 py-3 bg-blue-50 border border-blue-100 rounded-2xl flex items-center space-x-3">
                <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-black text-blue-700 uppercase tracking-widest">Layanan Siaga 24/7</span>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm animate-pulse-subtle">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Booking Area -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Step 1: Choose Cleaner -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-rose-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight uppercase">Jasa Kami (Pilih Petugas)</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" id="cleanerContainer">
                        @forelse($cleaners as $cleaner)
                            <div onclick="selectCleaner({{ $cleaner->id }}, '{{ $cleaner->user->name }}')" class="cleaner-card group cursor-pointer bg-white rounded-[2.5rem] border-2 border-slate-100 p-6 hover:border-blue-500 hover:shadow-2xl transition-all duration-500 relative overflow-hidden" id="cleaner-{{ $cleaner->id }}">
                                <div class="flex items-center space-x-5">
                                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-slate-50 group-hover:border-blue-100 transition-all flex-shrink-0 shadow-inner bg-slate-100">
                                        @if($cleaner->photo)
                                            <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-black text-slate-800 tracking-tight leading-none mb-1">{{ $cleaner->user->name }}</h4>
                                        <div class="flex items-center text-amber-500 mb-2">
                                            @for($i=0; $i<5; $i++)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                            <span class="text-[10px] font-black text-slate-400 ml-1">4.9</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-slate-400 leading-relaxed line-clamp-2 italic">"{{ $cleaner->bio ?: 'Siap membantu membersihkan kamar Anda dengan teliti.' }}"</p>
                                    </div>
                                </div>
                                <!-- Selection Indicator -->
                                <div class="absolute top-4 right-4 w-6 h-6 bg-blue-600 rounded-full hidden border-4 border-white shadow-lg items-center justify-center selection-indicator">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-10 bg-slate-50 rounded-3xl text-center text-slate-400 font-bold italic">Maaf, belum ada petugas yang tersedia saat ini.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Step 2: Choose Package -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight uppercase">Pilih Paket Kebersihan</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @forelse($packages as $package)
                            <div onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})" class="package-card group cursor-pointer bg-white rounded-[2.5rem] border-2 border-slate-100 p-8 hover:border-blue-500 hover:shadow-2xl transition-all duration-500 relative overflow-hidden" id="package-{{ $package->id }}">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-4 py-1.5 {{ strtolower($package->name) == 'max' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }} text-[10px] font-black rounded-full uppercase tracking-widest">{{ $package->name }}</span>
                                        <div class="text-right">
                                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mulai Dari</div>
                                            <div class="text-xl font-black text-slate-800">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <p class="text-xs font-medium text-slate-500 leading-relaxed">{{ $package->description }}</p>
                                    <ul class="space-y-2 pt-2">
                                        @php
                                            $features = strtolower($package->name) == 'max' ? ['Pembersihan Kamar Mandi', 'Debu & Jendela', 'Merapikan Tempat Tidur', 'Vakum Kasur'] : ['Menyapu & Mengepel', 'Membuang Sampah', 'Pengaturan Meja'];
                                        @endphp
                                        @foreach($features as $f)
                                            <li class="flex items-center text-[10px] font-bold text-slate-600">
                                                <svg class="w-3.5 h-3.5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                {{ $f }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- Selection Indicator -->
                                <div class="absolute top-4 right-4 w-6 h-6 bg-blue-600 rounded-full hidden border-4 border-white shadow-lg items-center justify-center selection-indicator">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-10 bg-slate-50 rounded-3xl text-center text-slate-400 font-bold italic">Maaf, paket layanan belum tersedia.</div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Booking Summary -->
            <div class="space-y-8">
                <div class="sticky top-24">
                    <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden border-t-4 border-rose-600">
                        <div class="relative z-10 space-y-8">
                            <h4 class="text-xl font-black italic tracking-tighter">Ringkasan Pesanan</h4>
                            
                            <form action="{{ route('user.cleaning.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="cleaner_id" id="formCleanerId" required>
                                <input type="hidden" name="package_id" id="formPackageId" required>

                                <!-- Summary Display -->
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center text-xs opacity-60 font-bold">
                                        <span>Petugas:</span>
                                        <span id="summaryCleaner" class="text-white opacity-100">Belum dipilih</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs opacity-60 font-bold">
                                        <span>Paket:</span>
                                        <span id="summaryPackage" class="text-white opacity-100">Belum dipilih</span>
                                    </div>
                                    <hr class="border-white/10">
                                    
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest">Pilih Jadwal Datang</label>
                                        <input type="datetime-local" name="scheduled_at" required class="w-full bg-white/5 border-white/10 rounded-xl text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-blue-300 uppercase tracking-widest">Catatan Tambahan</label>
                                        <textarea name="notes" rows="2" placeholder="Misal: Tolong bersihkan balkon juga..." class="w-full bg-white/5 border-white/10 rounded-xl text-white text-xs placeholder-white/20 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>

                                <div class="pt-4 space-y-4">
                                    <div class="flex justify-between items-end">
                                        <span class="text-xs font-black opacity-60 uppercase tracking-widest">Total Bayar</span>
                                        <span class="text-2xl font-black tracking-tighter" id="summaryTotal">Rp 0</span>
                                    </div>
                                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-rose-600 text-white rounded-2xl font-black text-xs shadow-xl transition-all transform hover:-translate-y-1 active:scale-95 disabled:opacity-50 disabled:pointer-events-none" id="submitBtn" disabled>
                                        PESAN SEKARANG
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500 rounded-full opacity-10"></div>
                    </div>

                    <!-- Order History Link -->
                    <div class="mt-8">
                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Pesanan Terakhir</h4>
                        <div class="bg-white rounded-3xl border border-slate-100 divide-y divide-slate-50 overflow-hidden">
                            @forelse($myOrders->take(3) as $order)
                                <div class="p-4 flex items-center justify-between">
                                    <div>
                                        <div class="text-[10px] font-black text-slate-800 leading-none mb-1">{{ $order->package->name }} ({{ $order->cleaner->user->name }})</div>
                                        <div class="text-[9px] font-bold text-slate-400">{{ $order->scheduled_at->format('d M, H:i') }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase {{ $order->status == 'done' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            @empty
                                <div class="p-8 text-center text-[10px] font-bold text-slate-300 italic">Belum ada pesanan aktif.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cleaner-card.selected, .package-card.selected {
            border-color: #2563eb;
            background-color: #f0f7ff;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .cleaner-card.selected .selection-indicator, .package-card.selected .selection-indicator {
            display: flex;
        }
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.95; transform: scale(1.002); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 4s infinite ease-in-out;
        }
    </style>

    <script>
        let selectedCleanerID = null;
        let selectedPackageID = null;

        function selectCleaner(id, name) {
            selectedCleanerID = id;
            document.getElementById('formCleanerId').value = id;
            document.getElementById('summaryCleaner').innerText = name;
            
            // UI visual feedback
            document.querySelectorAll('.cleaner-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('cleaner-' + id).classList.add('selected');
            
            checkSubmit();
        }

        function selectPackage(id, name, price) {
            selectedPackageID = id;
            document.getElementById('formPackageId').value = id;
            document.getElementById('summaryPackage').innerText = name;
            document.getElementById('summaryTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
            
            // UI visual feedback
            document.querySelectorAll('.package-card').forEach(p => p.classList.remove('selected'));
            document.getElementById('package-' + id).classList.add('selected');
            
            checkSubmit();
        }

        function checkSubmit() {
            const btn = document.getElementById('submitBtn');
            if (selectedCleanerID && selectedPackageID) {
                btn.disabled = false;
            } else {
                btn.disabled = true;
            }
        }
    </script>
</x-app-layout>

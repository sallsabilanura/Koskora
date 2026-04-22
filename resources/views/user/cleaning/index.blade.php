<x-app-layout>
    @section('header_title', 'Bebersih Kamar')

    <div class="space-y-10 pb-20">
        <!-- Header Section -->
        <div class="p-6 md:p-10 bg-white border border-slate-200 rounded-3xl shadow-sm relative overflow-hidden group">
            <div class="relative z-10 space-y-2 md:space-y-3">
                <div class="inline-flex items-center space-x-2 bg-brand-blue/10 px-3 py-1 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-blue opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-blue"></span>
                    </span>
                    <span class="text-[10px] font-black text-brand-blue uppercase tracking-[0.2em]">Pusat Layanan</span>
                </div>
                <h2 class="text-2xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Bebersih Kamar</h2>
                <p class="text-slate-500 text-sm md:text-base font-medium max-w-2xl leading-relaxed">
                    Pilih petugas favorit Anda dan jadwalkan pembersihan untuk kenyamanan maksimal Anda.
                </p>
            </div>
            <!-- Decorative accent -->
            <div class="absolute top-0 right-0 w-1 h-full bg-brand-blue"></div>
            <div class="absolute -right-16 -bottom-16 w-32 h-32 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">{{ $message }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Booking Area -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Step 1: Choose Cleaner -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                        <h3 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight uppercase">Pilih Petugas</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6" id="cleanerContainer">
                        @forelse($cleaners as $cleaner)
                            <div onclick="selectCleaner({{ $cleaner->id }}, '{{ $cleaner->user->name }}')" class="cleaner-card group cursor-pointer bg-white rounded-3xl border border-slate-200 p-6 hover:border-brand-blue hover:shadow-xl transition-all duration-300 relative overflow-hidden" id="cleaner-{{ $cleaner->id }}">
                                <div class="flex items-center space-x-5">
                                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl overflow-hidden border-2 border-slate-50 group-hover:border-blue-100 transition-all flex-shrink-0 shadow-sm bg-slate-100">
                                        @if($cleaner->photo)
                                            <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-8 h-8 md:w-10 md:h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base md:text-lg font-extrabold text-slate-800 tracking-tight leading-none mb-1 truncate">{{ $cleaner->user->name }}</h4>
                                        <div class="flex items-center text-amber-400 mb-2">
                                            @for($i=0; $i<5; $i++)
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                            <span class="text-[9px] font-black text-slate-400 ml-1">4.9</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-slate-400 leading-relaxed line-clamp-2">{{ $cleaner->bio ?: 'Siap membantu membersihkan kamar Anda.' }}</p>
                                    </div>
                                </div>
                                <!-- Selection Indicator -->
                                <div class="absolute top-3 right-3 w-6 h-6 bg-brand-blue rounded-full hidden border-2 border-white shadow-md items-center justify-center selection-indicator">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-10 bg-slate-50 rounded-none text-center text-slate-400 font-bold italic">Maaf, belum ada petugas yang tersedia saat ini.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Step 2: Choose Package -->
                <div class="space-y-6 pt-10">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                        <h3 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight uppercase">Pilih Paket</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @forelse($packages as $package)
                            <div onclick="selectPackage({{ $package->id }}, '{{ $package->name }}', {{ $package->price }})" class="package-card group cursor-pointer bg-white rounded-3xl border border-slate-200 p-8 hover:border-brand-blue hover:shadow-xl transition-all duration-300 relative overflow-hidden" id="package-{{ $package->id }}">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <span class="px-3 py-1 {{ strtolower($package->name) == 'max' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-blue-50 text-blue-600 border-blue-100' }} text-[9px] font-black rounded-full uppercase tracking-widest border">{{ $package->name }}</span>
                                        <div class="text-right">
                                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Harga</div>
                                            <div class="text-xl font-extrabold text-slate-800 tracking-tight">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <p class="text-[11px] font-medium text-slate-500 leading-relaxed opacity-80">{{ $package->description }}</p>
                                    <ul class="space-y-2 pt-2">
                                        @php
                                            $features = strtolower($package->name) == 'max' ? ['Kamar Mandi', 'Debu & Jendela', 'Tempat Tidur', 'Vakum Kasur'] : ['Sapu & Pel', 'Membuang Sampah', 'Atur Meja'];
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
                                <div class="absolute top-3 right-3 w-6 h-6 bg-brand-blue rounded-full hidden border-2 border-white shadow-md items-center justify-center selection-indicator">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-10 bg-slate-50 border border-slate-200 border-dashed rounded-3xl text-center text-slate-400 font-bold italic">Maaf, paket layanan belum tersedia.</div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Booking Summary -->
            <div class="space-y-8">
                <div class="sticky top-24">
                    <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden group/summary">
                        <div class="relative z-10 space-y-8">
                            <h4 class="text-xl font-extrabold tracking-tight">Ringkasan Pesanan</h4>
                            
                            <form action="{{ route('user.cleaning.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="cleaner_id" id="formCleanerId" required>
                                <input type="hidden" name="package_id" id="formPackageId" required>
                                <input type="hidden" id="package_price" value="0">
                                <input type="hidden" id="cleaner_rate" value="0">

                                <!-- Summary Display -->
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        <span>Petugas</span>
                                        <span id="summaryCleaner" class="text-white">Tentukan Petugas</span>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                                        <span>Paket Jasa</span>
                                        <span id="summaryPackage" class="text-white">Pilih Paket</span>
                                    </div>
                                    <hr class="border-white/10">
                                    
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Jadwal Kedatangan</label>
                                        <input type="datetime-local" name="scheduled_at" required class="w-full bg-white/5 border-white/10 rounded-xl text-xs py-3 px-4 text-white focus:ring-brand-blue focus:border-brand-blue transition-all">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Catatan (Opsional)</label>
                                        <textarea name="notes" rows="2" placeholder="Misal: Tolong bersihkan balkon juga..." class="w-full bg-white/5 border-white/10 rounded-xl text-xs p-4 text-white placeholder-white/20 focus:ring-brand-blue focus:border-brand-blue transition-all"></textarea>
                                    </div>
                                </div>

                                <div class="pt-4 space-y-6">
                                    <div class="flex justify-between items-end border-t border-white/10 pt-6">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Estimasi</span>
                                        <span class="text-3xl font-extrabold tracking-tighter" id="summaryTotal">Rp 0</span>
                                    </div>
                                    <button type="submit" class="w-full py-4 bg-brand-blue hover:bg-brand-red text-white rounded-xl font-black text-xs uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 active:scale-95 disabled:opacity-30 disabled:pointer-events-none" id="submitBtn" disabled>
                                        Konfirmasi Pesanan
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand-blue opacity-10 blur-3xl group-hover/summary:scale-110 transition-transform duration-700"></div>
                    </div>

                    <!-- Order History Link -->
                    <div class="mt-8 pt-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-1 h-4 bg-slate-300 rounded-full"></div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pesanan Terakhir</h4>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 divide-y divide-slate-100 overflow-hidden shadow-sm">
                            @forelse($myOrders->take(5) as $order)
                                <div class="p-4 space-y-3 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0">
                                            <div class="text-[10px] font-extrabold text-slate-800 leading-none mb-1 truncate uppercase tracking-tight">{{ $order->package->name }} ({{ $order->cleaner->user->name }})</div>
                                            <div class="text-[9px] font-bold text-slate-400">{{ $order->scheduled_at->format('d M, H:i') }}</div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest border {{ $order->status == 'done' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-1 border-t border-slate-50">
                                        <div class="flex flex-col">
                                            <span class="text-[7px] font-black text-slate-300 uppercase tracking-widest">Tagihan</span>
                                            <span class="text-xs font-extrabold text-brand-blue tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        @php 
                                            $pStatus = $order->payment_status ?? 'unpaid';
                                            $pLabel = $pStatus == 'unpaid' ? 'Belum Bayar' : ($pStatus == 'pending' ? 'Verifikasi' : 'Lunas');
                                            $pColor = $pStatus == 'unpaid' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($pStatus == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100');
                                        @endphp
                                        
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 {{ $pColor }} text-[7px] font-black rounded uppercase tracking-widest border">{{ $pLabel }}</span>
                                            @if($pStatus == 'unpaid')
                                                <button onclick="openPaymentModal({{ json_encode($order) }}, {{ json_encode($order->cleaner) }})" class="px-3 py-1 bg-brand-navy text-white text-[7px] font-black uppercase tracking-widest rounded-lg hover:bg-brand-red transition-all active:scale-95">
                                                    Bayar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-[10px] font-bold text-slate-300 italic uppercase tracking-widest">Belum ada pesanan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal (Like Laundry) -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePaymentModal()"></div>
            <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
                <form id="paymentForm" action="" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 space-y-8">
                    @csrf
                    <div class="text-center space-y-2">
                        <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none">Konfirmasi Pembayaran</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Silakan transfer ke rekening berikut</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-6 md:p-8 space-y-4 border border-slate-100">
                        <div class="flex justify-between items-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <span>Partner Bank</span>
                            <span id="bankNameDisplay" class="text-brand-blue uppercase"></span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Rekening</span>
                            <span id="bankAccountDisplay" class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight"></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <span>Atas Nama</span>
                            <span id="bankUserDisplay" class="text-slate-800 uppercase"></span>
                        </div>
                        <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal Transfer</span>
                            <span id="totalPriceDisplay" class="text-2xl font-extrabold text-brand-red tracking-tighter"></span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-1 h-3 bg-brand-blue rounded-full"></div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Upload Bukti Transfer</label>
                        </div>
                        <input type="file" name="payment_proof" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-slate-100 file:text-slate-600 hover:file:bg-slate-200 transition-all cursor-pointer" required>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="button" onclick="closePaymentModal()" class="flex-1 py-3.5 bg-slate-50 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Kembali
                        </button>
                        <button type="submit" class="flex-1 py-3.5 bg-brand-blue text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                            Konfirmasi Bukti
                        </button>
                    </div>
                </form>
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

        function openPaymentModal(order, cleaner) {
            const form = document.getElementById('paymentForm');
            form.action = `/cleaning/order/${order.id}/payment`;
            
            document.getElementById('bankNameDisplay').innerText = (cleaner.bank_name || 'BCA (Pusat)').toUpperCase();
            document.getElementById('bankAccountDisplay').innerText = cleaner.account_number || '123-456-7890';
            document.getElementById('bankUserDisplay').innerText = (cleaner.account_name || 'KosKora Management').toUpperCase();
            document.getElementById('totalPriceDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(order.total_price);
            
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>

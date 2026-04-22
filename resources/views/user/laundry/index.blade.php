<x-app-layout>
    @section('header_title', 'Pesan Laundry')

    <div class="space-y-8">
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
                <h2 class="text-2xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Layanan Laundry</h2>
                <p class="text-slate-500 text-sm md:text-base font-medium max-w-2xl leading-relaxed">
                    Bekerja sama dengan partner lokal profesional untuk kemudahan mencuci harian Anda.
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Partner Selection -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                    <h3 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight uppercase">Partner Laundry</h3>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($laundries as $laundry)
                        <div class="group bg-white rounded-3xl border border-slate-200 p-6 md:p-8 hover:shadow-xl hover:border-brand-blue transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4">
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded-full uppercase tracking-widest border border-emerald-100">Buka</span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                 <div class="min-w-0">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h4 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight truncate">{{ $laundry->name }}</h4>
                                        <div class="flex items-center bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100 flex-shrink-0">
                                            <svg class="w-3 h-3 text-amber-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <span class="text-[10px] font-black text-amber-700">{{ number_format($laundry->avg_rating, 1) }}</span>
                                        </div>
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-400 mb-4 flex items-center truncate">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $laundry->address }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[8px] font-black rounded-full uppercase tracking-widest border border-slate-100">Satuan & Kiloan</span>
                                        <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[8px] font-black rounded-full uppercase tracking-widest border border-slate-100">{{ $laundry->phone ? 'Admin Online' : 'Check Store' }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('user.laundry.order', $laundry->id) }}" class="p-5 md:p-6 bg-slate-50 group-hover:bg-brand-blue group-hover:text-white text-slate-400 rounded-2xl transition-all shadow-sm flex items-center justify-center flex-shrink-0 active:scale-95">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center text-slate-300 font-bold italic">Belum ada partner laundry tersedia.</div>
                    @endforelse
                </div>
            </div>

            <!-- Order History -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                    <h3 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight uppercase">Riwayat Pesanan</h3>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                    <div class="divide-y divide-slate-100">
                        @forelse($myOrders as $order)
                            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="space-y-2 min-w-0">
                                    <div class="text-sm font-extrabold text-slate-800 tracking-tight uppercase">{{ $order->laundry->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 flex items-center space-x-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach($order->items as $item)
                                            <span class="text-[9px] font-extrabold bg-slate-50 text-slate-500 px-2 py-0.5 rounded-lg border border-slate-200 uppercase tracking-tight">{{ $item['qty'] }}x {{ $item['item'] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-right flex flex-col items-end gap-3 flex-shrink-0">
                                    <div class="text-base font-extrabold text-brand-blue tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                    <div class="flex items-center gap-2">
                                        @php 
                                            $statusLabel = $order->payment_status == 'unpaid' ? 'Blm Bayar' : ($order->payment_status == 'pending' ? 'Verifikasi' : 'Lunas');
                                            $statusColor = $order->payment_status == 'unpaid' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($order->payment_status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100');
                                        @endphp
                                        <span class="px-2 py-0.5 {{ $statusColor }} text-[8px] font-black rounded uppercase tracking-widest border">{{ $statusLabel }}</span>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[8px] font-black rounded uppercase tracking-widest border border-slate-200">{{ strtoupper($order->status) }}</span>
                                    </div>
                                    @if($order->payment_status == 'unpaid')
                                        <button onclick="openPaymentModal({{ json_encode($order) }}, {{ json_encode($order->laundry) }})" class="w-full py-2 bg-brand-navy text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-brand-red transition-all active:scale-95">
                                            Lanjutkan Bayar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center flex flex-col items-center opacity-30">
                                <svg class="w-12 h-12 text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <div class="text-[10px] font-black uppercase tracking-widest">Belum ada pesanan laundry.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <!-- Payment Modal -->
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
                            <span id="bankNameDisplay" class="text-brand-blue"></span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Rekening</span>
                            <span id="bankAccountDisplay" class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight"></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <span>Atas Nama</span>
                            <span id="bankUserDisplay" class="text-slate-800"></span>
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

    <!-- Review Modal -->
    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeReviewModal()"></div>
            <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
                <form action="{{ route('user.laundry.review.store') }}" method="POST" class="p-6 md:p-10 space-y-8">
                    @csrf
                    <input type="hidden" name="order_id" id="modalOrderId">
                    
                    <div class="text-center space-y-2">
                        <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none" id="modalLaundryName">Rating Layanan</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Berikan ulasan singkat pengalaman Anda</p>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-8 flex flex-col items-center space-y-4 border border-slate-200">
                        <div class="flex items-center space-x-2" id="starContainer">
                            @for($i=1; $i<=5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="star-btn p-1 text-slate-200 hover:text-amber-400 hover:scale-110 transition-all duration-200" data-value="{{ $i }}">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="modalRatingValue" required>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-1 h-3 bg-brand-blue rounded-full"></div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Komentar Singkat</label>
                        </div>
                        <textarea name="comment" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium text-slate-600 placeholder-slate-300 focus:ring-brand-blue focus:border-brand-blue transition-all" placeholder="Ada kritik atau saran untuk partner?"></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="closeReviewModal()" class="flex-1 py-3.5 bg-slate-50 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all">
                            Kembali
                        </button>
                        <button type="submit" class="flex-1 py-3.5 bg-brand-blue text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openReviewModal(orderId, laundryName) {
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('modalLaundryName').innerText = 'Rating ' + laundryName;
            document.getElementById('reviewModal').classList.remove('hidden');
            setRating(0); // Reset
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        function setRating(value) {
            document.getElementById('modalRatingValue').value = value;
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.remove('text-slate-200');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-slate-200');
                }
            });
        }

        function openPaymentModal(order, laundry) {
            const form = document.getElementById('paymentForm');
            form.action = `/laundry/order/${order.id}/payment`;
            
            document.getElementById('bankNameDisplay').innerText = (laundry.bank_name || 'Alun-alun Laundry').toUpperCase();
            document.getElementById('bankAccountDisplay').innerText = laundry.account_number || '000-000-000';
            document.getElementById('bankUserDisplay').innerText = (laundry.account_name || laundry.name).toUpperCase();
            document.getElementById('totalPriceDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(order.total_price);
            
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>

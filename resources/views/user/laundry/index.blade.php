<x-app-layout>
    @section('header_title', 'Pesan Laundry')

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Layanan Laundry Kos</h2>
                <p class="text-slate-500 font-medium">Bekerja sama dengan partner local untuk kenyamanan Anda.</p>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Partner Selection -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Pilih Partner Laundry</h3>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($laundries as $laundry)
                        <div class="group bg-white rounded-[2.5rem] border border-slate-200 p-8 hover:shadow-2xl hover:shadow-blue-100 hover:border-blue-400 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase">Open Now</span>
                            </div>
                            <div class="flex items-center justify-between">
                                 <div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 class="text-xl font-black text-slate-800 italic tracking-tight">{{ $laundry->name }}</h4>
                                        <div class="flex items-center bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">
                                            <svg class="w-3.5 h-3.5 text-amber-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <span class="text-[10px] font-black text-amber-700">{{ number_format($laundry->avg_rating, 1) }}</span>
                                            <span class="text-[8px] font-bold text-slate-400 ml-1">({{ $laundry->reviews_count }})</span>
                                        </div>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 mb-4 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $laundry->address }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-full uppercase tracking-widest">Bisa Satuan</span>
                                        <span class="px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-black rounded-full uppercase tracking-widest">{{ $laundry->phone ?: 'WhatsApp Aktif' }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('user.laundry.order', $laundry->id) }}" class="p-5 bg-slate-50 group-hover:bg-blue-600 group-hover:text-white text-slate-400 rounded-3xl transition-all shadow-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
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
                    <div class="w-1.5 h-6 bg-slate-800 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Riwayat Pesanan</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                    <div class="divide-y divide-slate-100">
                        @forelse($myOrders as $order)
                            <div class="p-6 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                                <div class="space-y-1">
                                    <div class="text-sm font-black text-slate-800 italic">{{ $order->laundry->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                    <div class="flex items-center flex-wrap gap-1 mt-2">
                                        @foreach($order->items as $item)
                                            <span class="text-[10px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-lg">{{ $item['qty'] }}x {{ $item['item'] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-blue-600 mb-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'picked_up' => 'bg-blue-100 text-blue-700',
                                            'in_progress' => 'bg-indigo-100 text-indigo-700',
                                            'ready' => 'bg-emerald-100 text-emerald-700',
                                            'delivered' => 'bg-slate-100 text-slate-700',
                                            'done' => 'bg-slate-200 text-slate-300',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'picked_up' => 'Dijemput',
                                            'in_progress' => 'Dicuci',
                                            'ready' => 'Siap',
                                            'delivered' => 'Diantar',
                                            'done' => 'Selesai',
                                        ];
                                    @endphp
                                    <div class="flex flex-col items-end space-y-2">
                                        <div class="flex items-center space-x-2">
                                            @if($order->payment_status == 'unpaid')
                                                <span class="px-2.5 py-1 bg-rose-100 text-rose-700 text-[9px] font-black uppercase tracking-widest rounded-full">Belum Bayar</span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase tracking-widest rounded-full">Menunggu Verifikasi</span>
                                            @else
                                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-full">Sudah Bayar</span>
                                            @endif

                                            <span class="px-2.5 py-1 {{ $statusClasses[$order->status] }} text-[9px] font-black uppercase tracking-widest rounded-full">
                                                {{ $statusLabels[$order->status] }}
                                            </span>
                                        </div>
                                        
                                        @if($order->payment_status == 'unpaid')
                                            <button onclick="openPaymentModal({{ json_encode($order) }}, {{ json_encode($order->laundry) }})" class="w-full py-2.5 bg-[#1e1b9b] text-white text-[10px] font-bold uppercase rounded-xl shadow-lg hover:scale-[1.02] transition-all">
                                                Bayar Sekarang
                                            </button>
                                        @endif
                                        
                                        @if($order->status == 'done')
                                            @if($order->review)
                                                <div class="flex items-center space-x-1">
                                                    @for($i=1; $i<=5; $i++)
                                                        <svg class="w-3 h-3 {{ $i <= $order->review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    @endfor
                                                </div>
                                            @else
                                                <button onclick="openReviewModal({{ $order->id }}, '{{ $order->laundry->name }}')" class="text-[10px] font-black text-blue-600 underline hover:text-blue-800 transition-all">
                                                    Beri Rating
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center text-slate-300 font-bold italic">Belum ada riwayat pesanan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closePaymentModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form id="paymentForm" action="" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                    @csrf
                    <div class="text-center space-y-2">
                        <h3 class="text-2xl font-black text-slate-800 italic tracking-tighter">Konfirmasi Pembayaran</h3>
                        <p class="text-sm font-medium text-slate-500">Silakan transfer ke rekening partner berikut.</p>
                    </div>

                    <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-black text-slate-400 uppercase tracking-widest">Bank</span>
                            <span id="bankNameDisplay" class="font-black text-[#1e1b9b]"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">No. Rekening</span>
                            <span id="bankAccountDisplay" class="text-xl font-black text-slate-800 tracking-tighter"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-black text-slate-400 uppercase tracking-widest">Atas Nama</span>
                            <span id="bankUserDisplay" class="font-black text-slate-800"></span>
                        </div>
                        <div class="pt-6 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Bayar</span>
                            <span id="totalPriceDisplay" class="text-2xl font-black text-[#1e1b9b] italic tracking-tighter"></span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic tracking-widest">Upload Bukti Transfer</label>
                        <input type="file" name="payment_proof" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer" required>
                    </div>

                    <div class="flex space-x-4">
                        <button type="button" onclick="closePaymentModal()" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs hover:bg-slate-200 transition-all">
                            BATAL
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-[#1e1b9b] text-white rounded-2xl font-black text-xs shadow-lg shadow-blue-100 hover:bg-opacity-90 transition-all">
                            KIRIM BUKTI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeReviewModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('user.laundry.review.store') }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    <input type="hidden" name="order_id" id="modalOrderId">
                    
                    <div class="text-center space-y-2">
                        <h3 class="text-2xl font-black text-slate-800 italic tracking-tighter" id="modalLaundryName">Rating Laundry</h3>
                        <p class="text-sm font-medium text-slate-500">Bagaimana pengalaman cuci Anda?</p>
                    </div>

                    <div class="flex flex-col items-center space-y-4">
                        <div class="flex items-center space-x-2" id="starContainer">
                            @for($i=1; $i<=5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="star-btn p-1 text-slate-200 hover:text-amber-400 transition-all duration-200" data-value="{{ $i }}">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="modalRatingValue" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Komentar (Opsional)</label>
                        <textarea name="comment" rows="3" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600 placeholder-slate-300" placeholder="Tuliskan ulasan Anda mengenai kecepatan, kebersihan, atau pelayanan..."></textarea>
                    </div>

                    <div class="flex space-x-4 pt-4">
                        <button type="button" onclick="closeReviewModal()" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-xs hover:bg-slate-200 transition-all">
                            BATAL
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black text-xs shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                            KIRIM RATING
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

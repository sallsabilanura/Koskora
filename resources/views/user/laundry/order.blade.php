<x-app-layout>
    @section('header_title', 'Form Pesanan Laundry')

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.laundry.index') }}" class="p-3 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-all text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-800 italic">Pesan di {{ $laundry->name }}</h2>
                <p class="text-slate-500 font-medium">Lengkapi rincian jenis pakaian yang ingin dicuci.</p>
            </div>
        </div>

        <form action="{{ route('user.laundry.store', $laundry->id) }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-8 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Layanan Satuan</h3>
                    <span class="px-4 py-1.5 bg-blue-100 text-blue-600 text-[10px] font-black uppercase rounded-full">Automated Calculation</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($laundry->services as $service)
                        <div class="p-8 flex items-center justify-between group hover:bg-slate-50/50 transition-all">
                            <div class="flex-1">
                                <div class="text-base font-black text-slate-800 italic mb-1">{{ $service->item_name }}</div>
                                <div class="text-xs font-bold text-blue-500 italic">Rp {{ number_format($service->price, 0, ',', '.') }} <span class="text-slate-400 font-normal">/ pcs</span></div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center bg-slate-100 rounded-2xl p-1 border border-slate-200">
                                    <button type="button" onclick="decrement({{ $service->id }})" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    <input type="number" 
                                           name="items[{{ $service->id }}]" 
                                           id="qty-{{ $service->id }}" 
                                           value="0" 
                                           min="0"
                                           class="w-16 bg-transparent border-none text-center font-black text-slate-800 focus:ring-0 qty-input"
                                           data-price="{{ $service->price }}"
                                           oninput="calculateTotal()">
                                    <button type="button" onclick="increment({{ $service->id }})" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                                <div class="w-32 text-right">
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Subtotal</div>
                                    <div id="subtotal-{{ $service->id }}" class="text-sm font-black text-slate-800 italic">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-300 font-bold italic">Layanan belum ditambahkan oleh partner.</div>
                    @endforelse
                </div>

                <div class="p-8 bg-slate-50 space-y-4">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1 italic">Catatan Tambahan (Opsi)</label>
                    <textarea name="notes" rows="2" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600 italic" placeholder="Contoh: Tolong baju putih dipisah, jemput jam 5 sore..."></textarea>
                </div>
            </div>

            <!-- Sticky Bottom Summary -->
            <div class="bg-blue-600 rounded-[2.5rem] p-10 text-white flex items-center justify-between shadow-2xl shadow-blue-100">
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Total Estimasi</div>
                    <div class="text-4xl font-black italic tracking-tighter" id="grandTotal">Rp 0</div>
                </div>
                <button type="submit" class="px-10 py-5 bg-white text-blue-600 rounded-3xl font-black text-sm hover:scale-105 transition-all shadow-xl">
                    KIRIM PESANAN
                </button>
            </div>
        </form>
    </div>

    <script>
        function increment(id) {
            const input = document.getElementById('qty-' + id);
            input.value = parseInt(input.value) + 1;
            calculateTotal();
        }

        function decrement(id) {
            const input = document.getElementById('qty-' + id);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                calculateTotal();
            }
        }

        function calculateTotal() {
            let total = 0;
            const inputs = document.querySelectorAll('.qty-input');
            
            inputs.forEach(input => {
                const id = input.id.split('-')[1];
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price);
                const sub = qty * price;
                
                document.getElementById('subtotal-' + id).innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(sub);
                total += sub;
            });

            document.getElementById('grandTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        // Initialize
        calculateTotal();
    </script>
</x-app-layout>

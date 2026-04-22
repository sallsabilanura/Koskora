<x-app-layout>
    @section('header_title', 'Form Pesanan Laundry')

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center space-x-5">
            <a href="{{ route('user.laundry.index') }}" class="p-3 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 transition-all text-slate-400 shadow-sm active:scale-95">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight leading-none mb-1">{{ $laundry->name }}</h2>
                <p class="text-slate-500 text-sm md:text-base font-medium opacity-80">Lengkapi rincian cucian untuk partner kami.</p>
            </div>
        </div>


        <form action="{{ route('user.laundry.store', $laundry->id) }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="p-6 md:p-8 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-5 bg-brand-blue rounded-full"></div>
                        <h3 class="text-base md:text-lg font-extrabold text-slate-800 tracking-tight uppercase">Daftar Layanan</h3>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-[8px] font-black rounded-full uppercase tracking-widest border border-blue-100">SATUAN</span>
                </div>


                <div class="divide-y divide-slate-100">
                    @forelse($laundry->services as $service)
                        <div class="p-6 md:p-8 flex items-center justify-between group hover:bg-slate-50 transition-colors">
                            <div class="flex-1 min-w-0 pr-4">
                                <div class="text-base md:text-lg font-extrabold text-slate-800 tracking-tight mb-0.5 truncate uppercase">{{ $service->item_name }}</div>
                                <div class="text-[10px] font-bold text-brand-blue uppercase tracking-widest">Rp {{ number_format($service->price, 0, ',', '.') }} / pcs</div>
                            </div>
                            
                            <div class="flex items-center gap-6 md:gap-10">
                                <div class="flex items-center bg-slate-50 rounded-xl p-1.5 border border-slate-200 shadow-inner">
                                    <button type="button" onclick="decrement({{ $service->id }})" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-brand-red transition-all active:scale-75">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"></path></svg>
                                    </button>
                                    <input type="number" 
                                           name="items[{{ $service->id }}]" 
                                           id="qty-{{ $service->id }}" 
                                           value="0" 
                                           min="0"
                                           class="w-14 bg-transparent border-none text-center font-extrabold text-slate-800 focus:ring-0 qty-input text-lg"
                                           data-price="{{ $service->price }}"
                                           oninput="calculateTotal()">
                                    <button type="button" onclick="increment({{ $service->id }})" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-brand-blue transition-all active:scale-75">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                                <div class="w-24 md:w-32 text-right hidden sm:block">
                                    <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1 leading-none">Subtotal</div>
                                    <div id="subtotal-{{ $service->id }}" class="text-sm md:text-base font-extrabold text-slate-800 tracking-tight leading-none uppercase">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center flex flex-col items-center opacity-30">
                             <svg class="w-12 h-12 text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <div class="text-[10px] font-black uppercase tracking-widest">Layanan Belum Tersedia</div>
                        </div>
                    @endforelse
                </div>

                <div class="p-6 md:p-8 bg-slate-50 border-t border-slate-100">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-1 h-3 bg-brand-blue rounded-full"></div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Catatan Tambahan (Opsional)</label>
                    </div>
                    <textarea name="notes" rows="3" class="w-full bg-white border border-slate-200 rounded-2xl p-4 text-sm font-medium text-slate-600 placeholder-slate-300 focus:ring-brand-blue focus:border-brand-blue transition-all" placeholder="Contoh: Jemput jam 5 sore, pisah baju putih ya..."></textarea>
                </div>
            </div>


            <!-- Sticky Bottom Summary -->
            <!-- Sticky Bottom Summary -->
            <div class="bg-brand-navy rounded-3xl p-6 md:p-10 text-white flex items-center justify-between shadow-xl relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 leading-none">Estimasi Total</div>
                    <div class="text-3xl md:text-5xl font-black tracking-tighter leading-none" id="grandTotal">Rp 0</div>
                </div>
                <button type="submit" class="relative z-10 px-6 md:px-10 py-4 md:py-5 bg-brand-blue text-white rounded-xl font-black text-xs md:text-sm uppercase tracking-widest hover:bg-brand-red transition-all shadow-xl hover:-translate-y-1 active:scale-95 flex items-center gap-2 group/btn">
                    <span>SELESAI PESAN</span>
                    <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
                <!-- Decorative -->
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand-blue opacity-10 blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
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

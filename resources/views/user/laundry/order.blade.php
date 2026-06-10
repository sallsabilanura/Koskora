<x-app-layout>
    @section('header_title', 'Laundry Order')

    <div class="max-w-2xl mx-auto space-y-8 animate-fade-in pb-20">
        {{-- ===== HEADER ===== --}}
        <div class="flex items-center space-x-5 px-2">
            <a href="{{ route('user.laundry.index') }}" class="w-10 h-10 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 hover:text-brand transition-all shadow-sm active:scale-90">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none mb-1">{{ $laundry->name }}</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Detail Pesanan Anda</p>
            </div>
        </div>

        <form action="{{ route('user.laundry.store', $laundry->id) }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-brand rounded-full"></div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Layanan</h3>
                    </div>
                    <span class="px-3 py-1 bg-brand/10 text-brand text-[9px] font-black rounded-full uppercase tracking-widest border border-brand/20">SATUAN</span>
                </div>

                <div class="divide-y divide-slate-50">
                    @forelse($laundry->services as $service)
                        <div class="p-8 flex items-center justify-between group hover:bg-slate-50/50 transition-all">
                            <div class="flex-1 min-w-0 pr-4">
                                <div class="text-sm font-black text-slate-800 tracking-tight mb-1 truncate uppercase">{{ $service->item_name }}</div>
                                <div class="text-[10px] font-black text-brand italic tracking-tighter">Rp {{ number_format($service->price, 0, ',', '.') }} <span class="text-slate-300 font-bold not-italic ml-1">/ PCS</span></div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex items-center bg-slate-50 rounded-2xl p-1 border border-slate-100 shadow-inner">
                                    <button type="button" onclick="decrement({{ $service->id }})" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-all active:scale-75">
                                        <i class="fas fa-minus text-[10px]"></i>
                                    </button>
                                    <input type="number" 
                                           name="items[{{ $service->id }}]" 
                                           id="qty-{{ $service->id }}" 
                                           value="0" 
                                           min="0"
                                           class="w-12 bg-transparent border-none text-center font-black text-slate-800 focus:ring-0 qty-input text-sm"
                                           data-price="{{ $service->price }}"
                                           oninput="calculateTotal()">
                                    <button type="button" onclick="increment({{ $service->id }})" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-brand transition-all active:scale-75">
                                        <i class="fas fa-plus text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center flex flex-col items-center opacity-20">
                            <i class="fas fa-box-open text-4xl mb-4 text-slate-800"></i>
                            <div class="text-[10px] font-black uppercase tracking-widest">Layanan belum tersedia</div>
                        </div>
                    @endforelse
                </div>

                <div class="p-8 bg-slate-50/50 border-t border-slate-50">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1 h-3 bg-slate-300 rounded-full"></div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Instruksi Khusus (Opsional)</label>
                    </div>
                    <textarea name="notes" rows="3" class="w-full bg-white border border-slate-100 rounded-2xl p-5 text-xs font-bold text-slate-600 placeholder-slate-300 focus:ring-brand focus:border-brand transition-all outline-none" placeholder="Contoh: Baju putih tolong dipisah..."></textarea>
                </div>
            </div>

            <!-- Sticky Bottom Summary -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-10 text-white flex items-center justify-between shadow-2xl relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 leading-none">Estimasi Total</div>
                    <div class="text-3xl font-black tracking-tighter leading-none text-white italic" id="grandTotal">Rp 0</div>
                </div>
                <button type="submit" class="relative z-10 px-8 py-5 bg-brand text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-brand/20 active:scale-95 flex items-center gap-2">
                    <span>KONFIRMASI</span>
                    <i class="fas fa-paper-plane text-[9px]"></i>
                </button>
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-brand opacity-10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
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
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price);
                total += qty * price;
            });
            document.getElementById('grandTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        calculateTotal();
    </script>
</x-app-layout>

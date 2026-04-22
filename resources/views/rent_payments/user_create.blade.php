<x-app-layout>
    @section('header_title', 'Confirm Payment')

    <div class="max-w-4xl mx-auto py-12 px-6">
        <!-- Header Section -->
        <div class="text-center mb-10 space-y-3">
            <div class="inline-flex items-center space-x-2 bg-blue-50 px-3 py-1 rounded-full">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                <span class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Konfirmasi Pembayaran</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-800 tracking-tight leading-none">Kirim Bukti Bayar</h2>
            <p class="text-slate-500 text-sm md:text-base font-medium max-w-xl mx-auto leading-relaxed">
                Silakan unggah bukti transfer Anda untuk memproses verifikasi sewa bulan ini.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span class="text-xs font-black uppercase tracking-widest">Terjadi Kesalahan</span>
                </div>
                <ul class="list-disc list-inside text-[11px] font-bold space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 md:p-10">
            <form action="{{ route('rent-payments.user-store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="rental_id" value="{{ $rental->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <!-- Form Details -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Bulan Tagihan</label>
                            <select name="month" class="w-full h-14 pl-4 pr-10 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-blue-500 transition-all appearance-none">
                                @foreach($months as $m)
                                    <option value="{{ $m }}" {{ old('month') == $m || (old('month') == '' && $m == date('F Y')) ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nominal Pembayaran</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 font-extrabold text-slate-400">Rp</span>
                                <input type="number" name="amount" value="{{ old('amount', $rental->monthly_price ?? $rental->room->price) }}" class="w-full pl-12 pr-4 h-14 bg-slate-100 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-500 focus:ring-0" readonly>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 pl-1 uppercase tracking-widest leading-none">Automated Billing Price</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Tgl Bayar</label>
                                <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full h-14 px-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-blue-500 transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Metode</label>
                                <select name="method" class="w-full h-14 px-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-blue-500 transition-all appearance-none text-sm">
                                    <option value="Transfer Bank">Transfer Bank</option>
                                    <option value="E-Wallet">E-Wallet</option>
                                    <option value="Cash">Tunai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Upload -->
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 text-center block">Bukti Transfer (Image/Photo)</label>
                        <div class="relative group">
                            <input type="file" name="payment_proof" id="payment_proof" class="hidden" accept="image/*" onchange="previewImage(event)" required>
                            <label for="payment_proof" class="cursor-pointer block aspect-square md:aspect-[4/5] lg:aspect-auto lg:h-[300px] rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-blue-500 transition-all flex flex-col items-center justify-center p-6 text-center overflow-hidden shadow-inner">
                                <div id="preview-placeholder">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4 mx-auto text-slate-300 group-hover:text-blue-500 transition-colors">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Klik untuk pilih gambar</span>
                                </div>
                                <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-3xl group-hover:scale-105 transition-transform duration-500">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-slate-100 text-center">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-blue-600 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <span>Konfirmasi & Kirim Bukti</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <p class="text-[9px] font-bold text-slate-400 mt-6 uppercase tracking-widest leading-relaxed max-w-sm mx-auto">
                        Admin akan memproses verifikasi maksimal 1x24 jam setelah bukti dikirimkan.
                    </p>
                </div>
           </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('preview-placeholder');
                preview.src = reader.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>

<x-app-layout>
    @section('header_title', 'Confirm Payment')

    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kirim Bukti Pembayaran</h2>
            <p class="text-slate-500 mt-2">Silakan unggah bukti transfer/bayar Anda untuk bulan ini.</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 md:p-12">
            <form action="{{ route('rent-payments.user-store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="rental_id" value="{{ $rental->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bulan Pembayaran</label>
                            <select name="month" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold">
                                @foreach($months as $m)
                                    <option value="{{ $m }}" {{ old('month') == $m || (old('month') == '' && $m == date('F Y')) ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Bayar (Rp)</label>
                            <input type="number" name="amount" value="{{ old('amount', $rental->room->price) }}" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold" readonly>
                            <p class="text-xs text-slate-400 mt-1 italic">Sesuai dengan harga sewa kamar Anda.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Bayar</label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Metode Pembayaran</label>
                            <select name="method" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="E-Wallet">E-Wallet (OVO/Gopay/Dana)</option>
                                <option value="Cash">Tunai ke Pengelola</option>
                            </select>
                        </div>
                    </div>

                    <!-- Receipt Upload -->
                    <div class="flex flex-col justify-center">
                        <label class="block text-sm font-bold text-slate-700 mb-4 text-center">Upload Bukti Transfer / Resi</label>
                        <div class="relative group">
                            <input type="file" name="payment_proof" id="payment_proof" class="hidden" accept="image/*" onchange="previewImage(event)">
                            <label for="payment_proof" class="cursor-pointer block aspect-[3/4] rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-blue-400 transition-all flex flex-col items-center justify-center p-6 text-center overflow-hidden">
                                <div id="preview-placeholder">
                                    <svg class="w-12 h-12 text-slate-300 mb-4 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-bold text-slate-400 group-hover:text-blue-600">Klik untuk pilih gambar bukti bayar</span>
                                </div>
                                <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-3xl">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 shadow-2xl shadow-blue-100 transition-all flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Kirim Bukti Pembayaran</span>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-6">Admin akan memproses verifikasi maksimal 1x24 jam setelah bukti dikirim.</p>
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

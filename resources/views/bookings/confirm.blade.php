<x-app-layout>
    @section('header_title', 'Rental Confirmation')

    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-black uppercase tracking-widest text-xs">Langkah Terakhir</span>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mt-2">Konfirmasi Penyewaan</h2>
            <p class="text-slate-500 mt-2">Silakan tinjau kembali data penyewaan Anda sebelum menekan tombol konfirmasi.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="p-10 space-y-10">
                <!-- Summary Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Unit Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Unit Kamar</span>
                        </div>
                        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex items-center justify-between">
                            <div>
                                <div class="text-xl font-black text-blue-900">Kamar {{ $room->room_number }}</div>
                                <div class="text-sm text-blue-700 font-medium">{{ $room->room_type }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-blue-900 font-black">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                                <div class="text-xs text-blue-600 font-medium">per bulan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Period Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Periode Awal</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            <div class="text-lg font-bold text-slate-800">{{ now()->format('d M Y') }}</div>
                            <div class="text-sm text-slate-500">Estimasi sampai {{ now()->addMonth()->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Profile Review -->
                <div class="px-8 py-6 rounded-2xl bg-slate-50/50 border border-slate-100 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profil Penyewa</div>
                        <a href="{{ route('bookings.complete-profile', ['room_id' => $room->id]) }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Ubah Data</a>
                    </div>
                    
                    <div class="flex items-start gap-8 flex-wrap">
                        <!-- Photo Thumbnails -->
                        <div class="flex gap-3">
                            @if($user->tenant->foto_diri)
                                <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-white shadow-sm">
                                    <img src="{{ asset('storage/' . $user->tenant->foto_diri) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            @if($user->tenant->foto_ktp)
                                <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-white shadow-sm">
                                    <img src="{{ asset('storage/' . $user->tenant->foto_ktp) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-8">
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Nama Lengkap</div>
                                <div class="text-sm font-black text-slate-800">{{ $user->tenant->nama_lengkap }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">NIK (KTP)</div>
                                <div class="text-sm font-black text-slate-800 font-mono tracking-tighter">{{ $user->tenant->nik }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">WhatsApp</div>
                                <div class="text-sm font-black text-slate-800">{{ $user->tenant->nomor_whatsapp }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Jenis Kelamin</div>
                                <div class="text-sm font-black text-slate-800">{{ $user->tenant->jenis_kelamin }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Pekerjaan</div>
                                <div class="text-sm font-black text-slate-800">{{ $user->tenant->occupation }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">TTL</div>
                                <div class="text-sm font-black text-slate-800">{{ $user->tenant->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tenant->tanggal_lahir)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Button & Duration Type -->
                <div class="pt-10 border-t border-slate-100">
                    <form action="{{ route('bookings.store', $room->id) }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <!-- Duration Choice -->
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pilih Paket Durasi Sewa</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bulanan Card -->
                                <label class="relative flex flex-col p-6 bg-white border-2 border-blue-600 rounded-2xl cursor-pointer hover:shadow-md transition-all duration-200" id="label-monthly">
                                    <input type="radio" name="duration_type" value="monthly" checked class="sr-only" onchange="updateDurationSelection('monthly')">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-extrabold text-slate-800">Sewa Bulanan</span>
                                        <div class="w-4 h-4 rounded-full border-2 border-blue-600 flex items-center justify-center bg-blue-600" id="radio-dot-monthly">
                                            <div class="w-2 h-2 rounded-full bg-white"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1">Fleksibel, bayar & perpanjang tiap bulan</span>
                                    <span class="text-xl font-black text-blue-600 mt-4">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-xs font-medium text-slate-400"> / bulan</span></span>
                                </label>
                                
                                <!-- Tahunan Card -->
                                <label class="relative flex flex-col p-6 bg-white border-2 border-slate-200 rounded-2xl cursor-pointer hover:shadow-md transition-all duration-200" id="label-yearly">
                                    <input type="radio" name="duration_type" value="yearly" class="sr-only" onchange="updateDurationSelection('yearly')">
                                    <span class="absolute -top-3 right-4 bg-emerald-500 text-white text-[9px] font-black uppercase px-2.5 py-1 rounded-full tracking-wider shadow-sm">Hemat 10%</span>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-extrabold text-slate-800">Sewa Tahunan</span>
                                        <div class="w-4 h-4 rounded-full border-2 border-slate-300 flex items-center justify-center" id="radio-dot-yearly">
                                            <div class="w-2 h-2 rounded-full bg-white hidden"></div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-1">Komitmen 12 bulan (Tarif diskon)</span>
                                    <div class="flex flex-col mt-4">
                                        <span class="text-xs text-slate-400 line-through">Rp {{ number_format($room->price, 0, ',', '.') }} / bulan</span>
                                        <span class="text-xl font-black text-slate-800 mt-0.5" id="yearly-price-val">Rp {{ number_format($room->price * 0.9, 0, ',', '.') }}<span class="text-xs font-medium text-slate-400"> / bulan</span></span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex items-center justify-between mt-6">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-blue-500">Estimasi Tarif Awal</span>
                                <div class="text-xl font-black text-blue-900 mt-1" id="total-estimation-text">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold uppercase tracking-wider text-blue-500">Estimasi Keluar</span>
                                <div class="text-sm font-extrabold text-blue-900 mt-1" id="end-date-text">{{ now()->addMonth()->format('d M Y') }}</div>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-5 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 shadow-2xl shadow-emerald-100 transition-all flex items-center justify-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Konfirmasi & Sewa Sekarang</span>
                        </button>
                    </form>
                    <p class="text-center text-xs text-slate-400 mt-6 leading-relaxed">
                        Dengan mengklik tombol di atas, Anda menyetujui <span class="font-extrabold text-slate-500">syarat & ketentuan</span> penyewaan.<br>
                        <span class="text-rose-500 font-extrabold">* PENTING: Seluruh pembayaran sewa yang telah lunas TIDAK DAPAT DI-REFUND (dikembalikan) dengan alasan apapun.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDurationSelection(type) {
            const monthlyLabel = document.getElementById('label-monthly');
            const yearlyLabel = document.getElementById('label-yearly');
            
            const monthlyDot = document.querySelector('#radio-dot-monthly div');
            const yearlyDot = document.querySelector('#radio-dot-yearly div');
            const monthlyDotContainer = document.getElementById('radio-dot-monthly');
            const yearlyDotContainer = document.getElementById('radio-dot-yearly');

            const totalText = document.getElementById('total-estimation-text');
            const endDateText = document.getElementById('end-date-text');

            const priceNum = {{ $room->price }};
            
            if (type === 'monthly') {
                monthlyLabel.classList.add('border-blue-600');
                monthlyLabel.classList.remove('border-slate-200');
                yearlyLabel.classList.remove('border-blue-600');
                yearlyLabel.classList.add('border-slate-200');

                monthlyDot.classList.remove('hidden');
                monthlyDotContainer.classList.add('bg-blue-600', 'border-blue-600');
                monthlyDotContainer.classList.remove('border-slate-300');

                yearlyDot.classList.add('hidden');
                yearlyDotContainer.classList.remove('bg-blue-600', 'border-blue-600');
                yearlyDotContainer.classList.add('border-slate-300');

                totalText.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(priceNum) + " / bulan";
                
                // Add 1 month
                const d = new Date();
                d.setMonth(d.getMonth() + 1);
                endDateText.innerText = formatDate(d);
            } else {
                yearlyLabel.classList.add('border-blue-600');
                yearlyLabel.classList.remove('border-slate-200');
                monthlyLabel.classList.remove('border-blue-600');
                monthlyLabel.classList.add('border-slate-200');

                yearlyDot.classList.remove('hidden');
                yearlyDotContainer.classList.add('bg-blue-600', 'border-blue-600');
                yearlyDotContainer.classList.remove('border-slate-300');

                monthlyDot.classList.add('hidden');
                monthlyDotContainer.classList.remove('bg-blue-600', 'border-blue-600');
                monthlyDotContainer.classList.add('border-slate-300');

                totalText.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(priceNum * 0.9) + " / bulan";

                // Add 1 year
                const d = new Date();
                d.setFullYear(d.getFullYear() + 1);
                endDateText.innerText = formatDate(d);
            }
        }

        function formatDate(date) {
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            return String(date.getDate()).padStart(2, '0') + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
        }
    </script>
</x-app-layout>

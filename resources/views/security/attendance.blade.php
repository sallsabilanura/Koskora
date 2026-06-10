<x-app-layout>
    @section('header_title', 'Terminal Keamanan')

    <div class="max-w-xl mx-auto py-4 space-y-6 animate-fade-in pb-20 px-2">
        {{-- ===== HEADER SECTION ===== --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden relative group">
            <div class="p-8 text-center space-y-4">
                <div class="w-20 h-20 bg-slate-900 text-white rounded-[2rem] flex items-center justify-center mx-auto shadow-xl shadow-slate-900/10 mb-4 group-hover:scale-105 transition-transform">
                    <i class="fas fa-id-card-alt text-3xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">Verifikasi Kehadiran</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Sektor: {{ $todayShift->location }}</p>
                </div>
            </div>

            {{-- Info Bar --}}
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand/10 text-brand rounded-xl flex items-center justify-center">
                        <i class="far fa-clock text-xs"></i>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Shift Aktif</p>
                        <p class="text-[10px] font-black text-slate-800 uppercase">{{ $todayShift->start_time }} - {{ $todayShift->end_time }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-brand text-white text-[8px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-brand/20">
                    {{ $nextType === 'in' ? 'Check-In' : 'Check-Out' }}
                </span>
            </div>
        </div>

        <form action="{{ route('security.attendance.store') }}" method="POST" id="attendance-form" class="space-y-6">
            @csrf
            <input type="hidden" name="location" value="{{ $todayShift->location }}">
            <input type="hidden" name="image_data" id="image_data">
            <input type="hidden" name="type" value="{{ $nextType }}">

            {{-- LIVE CAMERA SECTION --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-6 shadow-2xl shadow-slate-200/50 space-y-6">
                <div class="flex items-center justify-between px-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                        <label class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Live Terminal Camera</label>
                    </div>
                    <span id="camera-status" class="text-[8px] font-black text-amber-500 uppercase tracking-widest">Inisialisasi...</span>
                </div>

                <div class="relative rounded-[2rem] overflow-hidden bg-slate-900 border-4 border-white shadow-2xl aspect-square" id="camera-container">
                    <video id="video" class="w-full h-full object-cover" autoplay playsinline muted></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <img id="photo-preview" class="hidden w-full h-full object-cover animate-fade-in">

                    {{-- Target Box Overlay --}}
                    <div id="scanning-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-56 h-56 border-2 border-white/20 rounded-[2.5rem] relative">
                            <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-2xl"></div>
                            <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-2xl"></div>
                            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-2xl"></div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-2xl"></div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="button" id="capture-btn" class="flex-1 py-5 bg-brand text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-brand/20 transition-all hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-camera text-sm"></i> Ambil Foto
                    </button>
                    <button type="button" id="retake-btn" class="hidden flex-1 py-5 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all hover:bg-slate-200 active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-redo"></i> Ulangi
                    </button>
                </div>
            </div>

            {{-- ADDITIONAL INFO --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-2xl shadow-slate-200/50 space-y-6">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Laporan Singkat (Opsional)</label>
                    <textarea name="note" rows="2" placeholder="Tulis catatan jika diperlukan..." class="w-full bg-slate-50 border-transparent rounded-2xl text-xs font-black p-5 focus:ring-brand focus:border-brand transition-all outline-none resize-none"></textarea>
                </div>

                <div class="flex flex-col gap-4 pt-4">
                    <button type="submit" id="submit-btn" disabled class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/10 opacity-20 cursor-not-allowed transition-all">
                        Kirim Laporan Kehadiran
                    </button>
                    <a href="{{ route('security.dashboard') }}" class="text-center py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand transition-colors">Kembali ke Dashboard</a>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const submitBtn = document.getElementById('submit-btn');
        const imageDataInput = document.getElementById('image_data');
        const cameraStatus = document.getElementById('camera-status');

        async function startCamera() {
            const constraints = { 
                video: { facingMode: "user", width: { ideal: 720 }, height: { ideal: 720 } }, 
                audio: false 
            };

            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error("Kamera tidak tersedia.");
                }
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    cameraStatus.innerText = "ONLINE";
                    cameraStatus.classList.remove('text-amber-500');
                    cameraStatus.classList.add('text-emerald-500');
                    video.play();
                };
            } catch (err) {
                cameraStatus.innerText = "OFFLINE";
                cameraStatus.classList.add('text-rose-500');
            }
        }

        captureBtn.addEventListener('click', () => {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);
                const data = canvas.toDataURL('image/jpeg', 0.8);
                imageDataInput.value = data;
                photoPreview.src = data;
                video.classList.add('hidden');
                photoPreview.classList.remove('hidden');
                captureBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-20', 'cursor-not-allowed');
                document.getElementById('scanning-overlay').classList.add('hidden');
            }
        });

        retakeBtn.addEventListener('click', () => {
            imageDataInput.value = "";
            video.classList.remove('hidden');
            photoPreview.classList.add('hidden');
            captureBtn.classList.remove('hidden');
            retakeBtn.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-20', 'cursor-not-allowed');
            document.getElementById('scanning-overlay').classList.remove('hidden');
        });

        window.addEventListener('load', startCamera);
    </script>
    @endpush
</x-app-layout>

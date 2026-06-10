<x-guest-layout>
    <div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-50/50">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Brand Identity -->
            <div class="flex justify-center mb-6">
                <a href="/">
                    <img src="{{ asset('koskora.png') }}" alt="KosKora" class="h-12 w-auto">
                </a>
            </div>
            <div class="flex justify-center mb-8">
                <div class="h-16 w-16 rounded-3xl shadow-xl flex items-center justify-center transform hover:scale-110 transition-all duration-300" style="background-color: #1e1b9b; box-shadow: 0 10px 25px -5px rgba(30, 27, 155, 0.3);">
                    <i class="fas fa-envelope-open-text text-white text-3xl"></i>
                </div>
            </div>
            
            <h2 class="text-center text-3xl font-extrabold tracking-tight" style="color: #1e1b9b;">
                {{ __('Verifikasi Email Anda') }}
            </h2>
            <p class="mt-2 text-center text-sm text-slate-500">
                {{ __('Amankan akun KosKora Anda untuk memulai') }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-2xl shadow-slate-200/60 sm:rounded-3xl sm:px-10" style="border: 1px solid #e2e8f0;">
                
                <div class="mb-6 text-sm text-slate-600 leading-relaxed text-center">
                    {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan yang baru.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-3 text-lg"></i>
                        <span class="text-sm font-medium text-emerald-800">
                            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </span>
                    </div>
                @endif

                <div class="mt-6 space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 active:scale-95" style="background-color: #1e1b9b; box-shadow: 0 8px 20px -4px rgba(30, 27, 155, 0.3);" onmouseover="this.style.backgroundColor='#d42e2e'" onmouseout="this.style.backgroundColor='#1e1b9b'">
                            <i class="fas fa-paper-plane mr-2"></i>
                            {{ __('Kirim Ulang Email Verifikasi') }}
                        </button>
                    </form>

                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-slate-400 font-medium lowercase italic">{{ __('atau') }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="flex justify-center">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 transition-colors duration-200 flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            {{ __('Keluar') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Trust Badge -->
            <div class="mt-8 flex justify-center space-x-6 text-slate-400">
                <div class="flex items-center text-xs font-medium uppercase tracking-widest">
                    <i class="fas fa-shield-alt mr-2"></i>
                    {{ __('Terenkripsi') }}
                </div>
                <div class="flex items-center text-xs font-medium uppercase tracking-widest">
                    <i class="fas fa-lock mr-2"></i>
                    {{ __('Keamanan Utama') }}
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>

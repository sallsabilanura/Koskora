<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-brand-blue mb-2">Log <span class="text-brand-red">In</span></h2>
        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Akses Layanan Premium KosKora</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Email Akun</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@email.com" class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-0 transition-all font-bold text-slate-700 placeholder:text-slate-300">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[9px] font-black text-slate-300 hover:text-brand-red uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                        Lupa?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required placeholder="••••••••" class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-0 transition-all font-bold text-slate-700 placeholder:text-slate-300">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-2 border-slate-200 text-brand-blue shadow-sm focus:ring-0 w-4 h-4 transition-all" name="remember">
                <span class="ms-3 text-xs font-bold text-slate-400 group-hover:text-brand-blue transition-colors">Ingat saya</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-4 bg-brand-red text-white rounded-[0.875rem] font-black text-xs uppercase tracking-widest hover:bg-brand-blue transition-all transform active:scale-[0.98]">
                Masuk Sekarang
            </button>
        </div>

        <div class="text-center pt-8 border-t border-slate-100 mt-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Belum terdaftar? 
                <a href="{{ route('register') }}" class="text-brand-blue hover:text-brand-red transition-colors ml-1">Buat Akun Pro</a>
            </p>
        </div>
    </form>
</x-guest-layout>

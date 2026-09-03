<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-extrabold text-brand-blue mb-2">Regis<span class="text-brand-red">ter</span></h2>
        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Bergabung dengan KosKora Pro</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso" class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-0 transition-all font-bold text-slate-700 placeholder:text-slate-300">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@email.com" class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-0 transition-all font-bold text-slate-700 placeholder:text-slate-300">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Register As (Role Selection) -->
        <div class="space-y-3">
            <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Daftar Sebagai</label>
            <div class="grid grid-cols-2 gap-4">
                <!-- Pencari Kos -->
                <label class="relative flex flex-col items-center p-5 bg-white border-2 border-brand-blue rounded-xl cursor-pointer hover:bg-white transition-all group" id="label-role-user">
                    <input type="radio" name="role" value="user" checked class="sr-only" onchange="updateRegisterRole('user')">
                    <i class="fas fa-search-location text-2xl text-brand-blue group-hover:scale-105 transition-all mb-2" id="icon-role-user"></i>
                    <span class="text-xs font-black text-slate-700">Pencari Kos</span>
                    <span class="text-[9px] font-bold text-slate-400 text-center mt-1">Saya ingin mencari kamar kos</span>
                </label>
                
                <!-- Admin Kos -->
                <label class="relative flex flex-col items-center p-5 bg-slate-50 border-2 border-slate-100 rounded-xl cursor-pointer hover:bg-white transition-all group" id="label-role-admin">
                    <input type="radio" name="role" value="admin" class="sr-only" onchange="updateRegisterRole('admin')">
                    <i class="fas fa-hotel text-2xl text-slate-400 group-hover:scale-105 transition-all mb-2" id="icon-role-admin"></i>
                    <span class="text-xs font-black text-slate-700">Admin Kos</span>
                    <span class="text-[9px] font-bold text-slate-400 text-center mt-1">Saya ingin mengelola kos</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Password</label>
            <input id="password" type="password" name="password" required placeholder="••••••••" class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-0 transition-all font-bold text-slate-700 placeholder:text-slate-300">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-[10px] font-black text-brand-blue uppercase tracking-widest">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••" class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-brand-blue focus:ring-0 transition-all font-bold text-slate-700 placeholder:text-slate-300">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full py-4 bg-brand-red text-white rounded-[0.875rem] font-black text-xs uppercase tracking-widest hover:bg-brand-blue transition-all transform active:scale-[0.98]">
                Daftar Sekarang
            </button>
        </div>

        <div class="text-center pt-8 border-t border-slate-100 mt-6">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-brand-blue hover:text-brand-red transition-colors ml-1">Masuk di sini</a>
            </p>
        </div>
    </form>

    <script>
        function updateRegisterRole(role) {
            const userLabel = document.getElementById('label-role-user');
            const adminLabel = document.getElementById('label-role-admin');
            const userIcon = document.getElementById('icon-role-user');
            const adminIcon = document.getElementById('icon-role-admin');
            
            if (role === 'user') {
                userLabel.classList.add('border-brand-blue', 'bg-white');
                userLabel.classList.remove('border-slate-100', 'bg-slate-50');
                userIcon.classList.add('text-brand-blue');
                userIcon.classList.remove('text-slate-400');
                
                adminLabel.classList.remove('border-brand-blue', 'bg-white');
                adminLabel.classList.add('border-slate-100', 'bg-slate-50');
                adminIcon.classList.remove('text-brand-blue');
                adminIcon.classList.add('text-slate-400');
            } else {
                adminLabel.classList.add('border-brand-blue', 'bg-white');
                adminLabel.classList.remove('border-slate-100', 'bg-slate-50');
                adminIcon.classList.add('text-brand-blue');
                adminIcon.classList.remove('text-slate-400');
                
                userLabel.classList.remove('border-brand-blue', 'bg-white');
                userLabel.classList.add('border-slate-100', 'bg-slate-50');
                userIcon.classList.remove('text-brand-blue');
                userIcon.classList.add('text-slate-400');
            }
        }
        // Run once on load to make sure initial selection matches
        document.addEventListener('DOMContentLoaded', () => {
            const selectedRole = document.querySelector('input[name="role"]:checked')?.value || 'user';
            updateRegisterRole(selectedRole);
        });
    </script>
</x-guest-layout>
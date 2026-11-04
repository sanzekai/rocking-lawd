<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-xl font-black uppercase tracking-wider text-white">Masuk</h2>
        <p class="text-xs text-slate-400 mt-1">Akses tiket & show lu di Rocking Lawd.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-950 text-red-600 shadow-sm focus:ring-red-600" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-400">{{ __('Ingat gue') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-slate-400 hover:text-white transition" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-2 text-xs text-slate-400">
            Belum punya akun? <a href="{{ route('register') }}" class="text-red-500 hover:text-red-400 font-bold">Daftar sekarang</a>
        </div>
    </form>
</x-guest-layout>

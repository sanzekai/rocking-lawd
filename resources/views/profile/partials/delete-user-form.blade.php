<section class="space-y-6">
    <header>
        <h2 class="text-base font-black uppercase tracking-wider text-white">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-xs text-slate-400">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data tiket akan dihapus secara permanen.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black uppercase tracking-wider text-white">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="text-xs text-slate-400">
                {{ __('Setelah akun Anda dihapus, seluruh data tiket, pesanan, dan riwayat akan hilang selamanya. Masukkan password Anda untuk konfirmasi.') }}
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full sm:w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-800">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Hapus Akun Sekarang') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

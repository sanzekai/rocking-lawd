<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
                {{ __('Tambah Artist Baru') }}
            </h2>
            <a href="{{ route('admin.artists.index') }}" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl rounded-xl">
                <form action="{{ route('admin.artists.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nama Artist *')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name')" required autofocus placeholder="e.g. Kendrick Lamar / Saykoji" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="genre" :value="__('Genre / Sub-Genre')" />
                        <x-text-input id="genre" name="genre" type="text" class="mt-1.5 block w-full" :value="old('genre', 'Hip-Hop')" placeholder="e.g. Boom Bap, Trap, West Coast" />
                        <x-input-error class="mt-2" :messages="$errors->get('genre')" />
                    </div>

                    <div x-data="{ photoPreview: null }">
                        <x-input-label for="photo" :value="__('Foto Profil Artist (Maks 2MB)')" />
                        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" class="mt-1.5 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }" />
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />

                        <div x-show="photoPreview" class="mt-4" style="display: none;">
                            <span class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Preview Foto:</span>
                            <img :src="photoPreview" class="h-28 w-28 rounded-xl object-cover border-2 border-red-600 shadow-md">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="bio" :value="__('Biografi Singkat')" />
                        <textarea id="bio" name="bio" rows="4" class="mt-1.5 block w-full bg-slate-900 border-slate-700 text-white placeholder-slate-500 focus:border-red-600 focus:ring-red-600 rounded-lg shadow-sm text-sm" placeholder="Profil karir, karya populer, dan biografi artist...">{{ old('bio') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-slate-800 pt-5">
                        <a href="{{ route('admin.artists.index') }}" class="inline-flex items-center px-4 py-2.5 border border-slate-700 rounded-lg font-bold text-xs uppercase tracking-wider text-slate-300 hover:bg-slate-800 hover:text-white transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Artist') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

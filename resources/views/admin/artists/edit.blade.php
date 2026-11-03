<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Artist: ') }} {{ $artist->name }}
            </h2>
            <a href="{{ route('admin.artists.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.artists.update', $artist) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nama Artist *')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $artist->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="genre" :value="__('Genre / Sub-Genre')" />
                        <x-text-input id="genre" name="genre" type="text" class="mt-1 block w-full" :value="old('genre', $artist->genre)" />
                        <x-input-error class="mt-2" :messages="$errors->get('genre')" />
                    </div>

                    <div x-data="{ photoPreview: null }">
                        <x-input-label for="photo" :value="__('Ganti Foto Profil Artist (Maks 2MB)')" />

                        @if ($artist->photo_path)
                            <div class="mt-2 mb-3 flex items-center space-x-3" x-show="!photoPreview">
                                <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-20 w-20 rounded-full object-cover border border-gray-200 shadow-sm">
                                <span class="text-xs text-gray-500">Foto profil saat ini</span>
                            </div>
                        @endif

                        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }" />
                        <x-input-error class="mt-2" :messages="$errors->get('photo')" />

                        <div x-show="photoPreview" class="mt-4" style="display: none;">
                            <span class="block text-sm font-medium text-gray-700 mb-2">Preview Foto Baru:</span>
                            <img :src="photoPreview" class="h-28 w-28 rounded-full object-cover border-2 border-indigo-500 shadow-md">
                        </div>
                    </div>

                    <div>
                        <x-input-label for="bio" :value="__('Biografi Singkat')" />
                        <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $artist->bio) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.artists.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Update Artist') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

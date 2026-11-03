<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Buat Event Baru') }}
            </h2>
            <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                            <p class="font-semibold mb-1">Periksa kesalahan pada form berikut:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Informasi Utama Event</h3>

                        <div>
                            <x-input-label for="title" :value="__('Judul Event *')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus placeholder="e.g. Rocking Lawd Fest 2026" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Event *')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Deskripsi lengkap tentang festival, aturan, dan lineup...">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="venue" :value="__('Lokasi / Venue *')" />
                                <x-text-input id="venue" name="venue" type="text" class="mt-1 block w-full" :value="old('venue')" required placeholder="e.g. Lapangan Pussenif, Bandung" />
                                <x-input-error class="mt-2" :messages="$errors->get('venue')" />
                            </div>

                            <div>
                                <x-input-label for="event_date" :value="__('Tanggal & Jam Mulai *')" />
                                <x-text-input id="event_date" name="event_date" type="datetime-local" class="mt-1 block w-full" :value="old('event_date')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('event_date')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="total_quota" :value="__('Total Kuota Tiket *')" />
                                <x-text-input id="total_quota" name="total_quota" type="number" min="1" class="mt-1 block w-full" :value="old('total_quota', 100)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('total_quota')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Harga Tiket (IDR) *')" />
                                <x-text-input id="price" name="price" type="number" min="0" step="1000" class="mt-1 block w-full" :value="old('price', 0)" required placeholder="0 untuk tiket gratis" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>
                        </div>

                        <div x-data="{ bannerPreview: null }">
                            <x-input-label for="banner" :value="__('Banner Poster Event (Maks 3MB)')" />
                            <input id="banner" name="banner" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { bannerPreview = e.target.result; }; reader.readAsDataURL(file); }" />
                            <x-input-error class="mt-2" :messages="$errors->get('banner')" />

                            <div x-show="bannerPreview" class="mt-4" style="display: none;">
                                <span class="block text-sm font-medium text-gray-700 mb-2">Preview Poster:</span>
                                <img :src="bannerPreview" class="max-h-56 rounded-lg object-cover border border-gray-300 shadow">
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Artist Lineup Selection with Alpine.js -->
                    <div class="space-y-4 pt-4 border-t border-gray-200"
                        x-data="{
                            selectedArtists: {{ json_encode(old('artist_ids', [])) }},
                            stages: {{ json_encode(old('performance_stages', [])) }},
                            times: {{ json_encode(old('performance_times', [])) }},
                            toggleArtist(id) {
                                id = String(id);
                                const idx = this.selectedArtists.indexOf(id);
                                if (idx > -1) {
                                    this.selectedArtists.splice(idx, 1);
                                } else {
                                    this.selectedArtists.push(id);
                                }
                            },
                            isSelected(id) {
                                return this.selectedArtists.includes(String(id));
                            },
                            getIndex(id) {
                                return this.selectedArtists.indexOf(String(id));
                            }
                        }">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Multi-Artist Lineup *</h3>
                            <p class="text-sm text-gray-500">Pilih artis yang akan tampil di event ini dan atur jadwal tampil beserta panggungnya.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('artist_ids')" />
                        </div>

                        @if ($artists->isEmpty())
                            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg text-sm">
                                Belum ada artist terdaftar. <a href="{{ route('admin.artists.create') }}" class="font-bold underline">Tambah Artist sekarang</a> terlebih dahulu.
                            </div>
                        @else
                            <!-- Checkbox Grid Artist -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @foreach ($artists as $artist)
                                    <div @click="toggleArtist('{{ $artist->id }}')"
                                        :class="isSelected('{{ $artist->id }}') ? 'border-indigo-600 bg-indigo-50/50 shadow-sm ring-2 ring-indigo-500' : 'border-gray-200 bg-white hover:border-gray-300'"
                                        class="cursor-pointer border rounded-lg p-3 transition flex items-center space-x-3 select-none">
                                        <input type="checkbox"
                                            :checked="isSelected('{{ $artist->id }}')"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 pointer-events-none">
                                        <div class="truncate">
                                            <div class="font-medium text-sm text-gray-900 truncate">{{ $artist->name }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $artist->genre ?? 'Hip-Hop' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Dynamic Inputs for Selected Artists -->
                            <div class="mt-6 space-y-3" x-show="selectedArtists.length > 0">
                                <h4 class="text-sm font-semibold text-gray-700">Detail Jadwal & Panggung Artis Terpilih:</h4>
                                <div class="space-y-3">
                                    @foreach ($artists as $artist)
                                        <div x-show="isSelected('{{ $artist->id }}')" class="p-4 bg-gray-50 border border-gray-200 rounded-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <!-- Hidden actual array input for artist_ids -->
                                            <input type="hidden" name="artist_ids[]" :value="'{{ $artist->id }}'" :disabled="!isSelected('{{ $artist->id }}')">

                                            <div class="flex items-center space-x-3">
                                                @if ($artist->photo_path)
                                                    <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-10 w-10 rounded-full object-cover border">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center font-bold text-gray-600 text-sm">
                                                        {{ strtoupper(substr($artist->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-sm text-gray-900">{{ $artist->name }}</div>
                                                    <span class="text-xs text-gray-500">{{ $artist->genre ?? 'Hip-Hop' }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1 md:max-w-md">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600">Panggung (Stage)</label>
                                                    <input type="text" name="performance_stages[]"
                                                        placeholder="e.g. Main Stage / Cypher Ring"
                                                        value="{{ old('performance_stages.' . $loop->index, 'Main Stage') }}"
                                                        :disabled="!isSelected('{{ $artist->id }}')"
                                                        class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600">Jam Tampil</label>
                                                    <input type="time" name="performance_times[]"
                                                        value="{{ old('performance_times.' . $loop->index) }}"
                                                        :disabled="!isSelected('{{ $artist->id }}')"
                                                        class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-4">
                        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan & Terbitkan Event') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

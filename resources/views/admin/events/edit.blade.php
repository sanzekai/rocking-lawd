<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
                {{ __('Edit Show: ') }} {{ $event->title }}
            </h2>
            <a href="{{ route('admin.events.index') }}" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                &larr; Balik ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl">
                <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="p-4 bg-red-950/80 border border-red-800 text-red-300">
                            <p class="font-bold text-xs uppercase tracking-wider mb-1">Ada yang salah, cek lagi:</p>
                            <ul class="list-disc list-inside text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-5">
                        <h3 class="text-base font-black uppercase tracking-wider text-white border-b border-slate-800 pb-3">Info Utama Show</h3>

                        <div>
                            <x-input-label for="title" :value="__('Judul Event *')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1.5 block w-full" :value="old('title', $event->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Event *')" />
                            <textarea id="description" name="description" rows="4" class="mt-1.5 block w-full bg-slate-900 border-slate-700 text-white placeholder-slate-500 focus:border-red-600 focus:ring-red-600 rounded-lg shadow-sm text-sm" required>{{ old('description', $event->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="venue" :value="__('Lokasi / Venue *')" />
                                <x-text-input id="venue" name="venue" type="text" class="mt-1.5 block w-full" :value="old('venue', $event->venue)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('venue')" />
                            </div>

                            <div>
                                <x-input-label for="event_date" :value="__('Tanggal & Jam Mulai *')" />
                                <x-text-input id="event_date" name="event_date" type="datetime-local" class="mt-1.5 block w-full [color-scheme:dark]" :value="old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('event_date')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="total_quota" :value="__('Total Kuota Tiket *')" />
                                <x-text-input id="total_quota" name="total_quota" type="number" min="1" class="mt-1.5 block w-full" :value="old('total_quota', $event->total_quota)" required />
                                <span class="text-xs text-slate-400 mt-1 block">Sisa kuota saat ini: <strong class="text-white">{{ $event->remaining_quota }}</strong> tiket</span>
                                <x-input-error class="mt-2" :messages="$errors->get('total_quota')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Harga Tiket (IDR) *')" />
                                <x-text-input id="price" name="price" type="number" min="0" step="1000" class="mt-1.5 block w-full" :value="old('price', $event->price)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>
                        </div>

                        <div x-data="{ bannerPreview: null }">
                            <x-input-label for="banner" :value="__('Ganti Banner Poster Event (Maks 3MB)')" />

                            @if ($event->banner_path)
                                <div class="mt-2 mb-3" x-show="!bannerPreview">
                                    <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="max-h-48 rounded-lg object-cover border border-slate-700 shadow-sm">
                                    <span class="text-xs text-slate-400 mt-1 block">Poster saat ini</span>
                                </div>
                            @endif

                            <input id="banner" name="banner" type="file" accept="image/jpeg,image/png,image/webp,image/jpg" class="mt-1.5 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer"
                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { bannerPreview = e.target.result; }; reader.readAsDataURL(file); }" />
                            <x-input-error class="mt-2" :messages="$errors->get('banner')" />

                            <div x-show="bannerPreview" class="mt-4" style="display: none;">
                                <span class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Preview Poster Baru:</span>
                                <img :src="bannerPreview" class="max-h-56 rounded-lg object-cover border border-slate-700 shadow">
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 pt-2">
                            <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $event->is_published) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-red-600 focus:ring-red-600">
                            <label for="is_published" class="text-xs font-bold uppercase tracking-wider text-slate-300">Publish Show (Aktif buat beli tiket)</label>
                        </div>
                    </div>

                    <!-- Multi-Artist Lineup Selection with Alpine.js -->
                    <div class="space-y-4 pt-5 border-t border-slate-800"
                        x-data="{
                            selectedArtists: {{ json_encode(old('artist_ids', $selectedArtistIds ?? [])) }},
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
                            }
                        }">
                        <div>
                            <h3 class="text-base font-black uppercase tracking-wider text-white">Multi-Artist Lineup *</h3>
                            <p class="text-xs text-slate-400">Pilih artis yang akan tampil di event ini dan atur jadwal tampil beserta panggungnya.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('artist_ids')" />
                        </div>

                        @if ($artists->isEmpty())
                            <div class="p-4 bg-red-950/50 border border-red-900 text-red-300 rounded-lg text-xs font-bold">
                                Belum ada artist terdaftar.
                            </div>
                        @else
                            <!-- Checkbox Grid Artist -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                @foreach ($artists as $artist)
                                    <div @click="toggleArtist('{{ $artist->id }}')"
                                        :class="isSelected('{{ $artist->id }}') ? 'border-red-600 bg-red-950/40 shadow-sm ring-1 ring-red-600' : 'border-slate-800 bg-slate-900 hover:border-slate-700'"
                                        class="cursor-pointer border rounded-lg p-3 transition flex items-center space-x-3 select-none">
                                        <input type="checkbox"
                                            :checked="isSelected('{{ $artist->id }}')"
                                            class="rounded border-slate-700 bg-slate-950 text-red-600 focus:ring-red-600 pointer-events-none">
                                        <div class="truncate">
                                            <div class="font-bold text-sm text-white truncate">{{ $artist->name }}</div>
                                            <div class="text-xs text-slate-400 truncate">{{ $artist->genre ?? 'Hip-Hop' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Dynamic Inputs for Selected Artists -->
                            <div class="mt-6 space-y-3" x-show="selectedArtists.length > 0">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300">Detail Jadwal & Panggung Artis Terpilih:</h4>
                                <div class="space-y-3">
                                    @foreach ($artists as $artist)
                                        @php
                                            $currentPivot = $event->artists->firstWhere('id', $artist->id)?->pivot;
                                            $defaultStage = $currentPivot ? $currentPivot->performance_stage : 'Main Stage';
                                            $defaultTime = $currentPivot && $currentPivot->performance_time ? \Carbon\Carbon::parse($currentPivot->performance_time)->format('H:i') : '';
                                        @endphp
                                        <div x-show="isSelected('{{ $artist->id }}')" class="p-4 bg-slate-900 border border-slate-800 rounded-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <input type="hidden" name="artist_ids[]" :value="'{{ $artist->id }}'" :disabled="!isSelected('{{ $artist->id }}')">

                                            <div class="flex items-center space-x-3">
                                                @if ($artist->photo_path)
                                                    <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-10 w-10 rounded-lg object-cover border border-slate-700">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-slate-400 text-sm">
                                                        {{ strtoupper(substr($artist->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-sm text-white">{{ $artist->name }}</div>
                                                    <span class="text-xs text-slate-400">{{ $artist->genre ?? 'Hip-Hop' }}</span>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1 md:max-w-md">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Panggung (Stage)</label>
                                                    <input type="text" name="performance_stages[]"
                                                        placeholder="e.g. Main Stage / Cypher Ring"
                                                        value="{{ old('performance_stages.' . $loop->index, $defaultStage) }}"
                                                        :disabled="!isSelected('{{ $artist->id }}')"
                                                        class="mt-1 block w-full text-xs bg-slate-950 border-slate-700 text-white rounded-lg shadow-sm focus:border-red-600 focus:ring-red-600">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Jam Tampil</label>
                                                    <input type="time" name="performance_times[]"
                                                        value="{{ old('performance_times.' . $loop->index, $defaultTime) }}"
                                                        :disabled="!isSelected('{{ $artist->id }}')"
                                                        class="mt-1 block w-full text-xs bg-slate-950 border-slate-700 text-white rounded-lg shadow-sm focus:border-red-600 focus:ring-red-600 [color-scheme:dark]">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-slate-800 pt-5">
                        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center px-4 py-2.5 border border-slate-700 rounded-lg font-bold text-xs uppercase tracking-wider text-slate-300 hover:bg-slate-800 hover:text-white transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Update Event') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

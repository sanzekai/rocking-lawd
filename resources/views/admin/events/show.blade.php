<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Event: ') }} {{ $event->title }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit Event
                </a>
                <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($event->banner_path)
                    <div class="w-full h-72 overflow-hidden bg-black flex items-center justify-center">
                        <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 border-b pb-4">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900">{{ $event->title }}</h1>
                            <div class="text-sm text-gray-500 mt-1">
                                Diselenggarakan oleh: <span class="font-semibold text-gray-700">{{ $event->user?->name ?? 'Organizer' }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $event->is_published ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $event->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg">
                        <div>
                            <span class="block text-xs font-medium text-gray-500">WAKTU & TANGGAL</span>
                            <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y - H:i') }} WIB</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500">LOKASI / VENUE</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $event->venue }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-medium text-gray-500">HARGA TIKET & KUOTA</span>
                            <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            <span class="text-xs text-gray-500 block">Sisa {{ $event->remaining_quota }} dari {{ $event->total_quota }} tiket</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 text-base mb-2">Deskripsi</h4>
                        <div class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">
                            {{ $event->description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lineup Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Lineup Artist Hip-Hop</h3>

                @if ($event->artists->isEmpty())
                    <p class="text-sm text-gray-500">Belum ada artis yang terhubung ke event ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($event->artists as $artist)
                            <div class="border border-gray-200 rounded-lg p-4 flex items-center space-x-4 bg-gray-50">
                                @if ($artist->photo_path)
                                    <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-16 w-16 rounded-full object-cover border shadow-sm">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 text-xl">
                                        {{ strtoupper(substr($artist->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-gray-900 truncate">{{ $artist->name }}</h4>
                                    <p class="text-xs text-purple-700 font-semibold">{{ $artist->genre ?? 'Hip-Hop' }}</p>
                                    <div class="mt-2 text-xs text-gray-600">
                                        <div><span class="font-medium">Stage:</span> {{ $artist->pivot->performance_stage ?? 'Main Stage' }}</div>
                                        <div><span class="font-medium">Jam:</span> {{ $artist->pivot->performance_time ? \Carbon\Carbon::parse($artist->pivot->performance_time)->format('H:i') : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
                {{ __('Detail Event: ') }} {{ $event->title }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs uppercase tracking-wider text-white hover:bg-red-700 transition">
                    Edit Event
                </a>
                <a href="{{ route('admin.events.index') }}" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl rounded-xl">
                @if ($event->banner_path)
                    <div class="w-full h-80 overflow-hidden bg-slate-950 flex items-center justify-center">
                        <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 sm:p-8 space-y-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-800 pb-5">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-black uppercase tracking-wider text-white">{{ $event->title }}</h1>
                            <div class="text-xs text-slate-400 mt-1">
                                Diselenggarakan oleh: <span class="font-bold text-slate-200">{{ $event->user?->name ?? 'Organizer' }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $event->is_published ? 'bg-red-950 text-red-400 border border-red-800' : 'bg-slate-900 text-slate-400 border border-slate-700' }}">
                                {{ $event->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-900 p-5 rounded-xl border border-slate-800">
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">WAKTU & TANGGAL</span>
                            <span class="text-sm font-bold text-white">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y - H:i') }} WIB</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">LOKASI / VENUE</span>
                            <span class="text-sm font-bold text-white">{{ $event->venue }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">HARGA TIKET & KUOTA</span>
                            <span class="text-sm font-black text-red-500">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            <span class="text-xs text-slate-400 block mt-0.5">Sisa {{ $event->remaining_quota }} dari {{ $event->total_quota }} tiket</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-black uppercase tracking-wider text-white text-sm mb-2">Deskripsi</h4>
                        <div class="text-slate-300 text-sm whitespace-pre-line leading-relaxed">
                            {{ $event->description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lineup Section -->
            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl rounded-xl p-6 sm:p-8">
                <h3 class="text-base font-black uppercase tracking-wider text-white mb-5">Lineup Artist Hip-Hop</h3>

                @if ($event->artists->isEmpty())
                    <p class="text-xs text-slate-500">Belum ada artis yang terhubung ke event ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($event->artists as $artist)
                            <div class="border border-slate-800 rounded-xl p-4 flex items-center space-x-4 bg-slate-900">
                                @if ($artist->photo_path)
                                    <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-14 w-14 rounded-lg object-cover border border-slate-700 shadow-sm">
                                @else
                                    <div class="h-14 w-14 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center font-black text-slate-400 text-lg">
                                        {{ strtoupper(substr($artist->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-white truncate text-sm">{{ $artist->name }}</h4>
                                    <p class="text-xs text-red-400 font-bold uppercase tracking-wider">{{ $artist->genre ?? 'Hip-Hop' }}</p>
                                    <div class="mt-2 text-[10px] text-slate-400 space-y-0.5">
                                        <div><span class="font-semibold text-slate-300">Stage:</span> {{ $artist->pivot->performance_stage ?? 'Main Stage' }}</div>
                                        <div><span class="font-semibold text-slate-300">Jam:</span> {{ $artist->pivot->performance_time ? \Carbon\Carbon::parse($artist->pivot->performance_time)->format('H:i') : '-' }}</div>
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

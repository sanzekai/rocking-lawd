<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
                {{ __('Detail Artist: ') }} {{ $artist->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.artists.edit', $artist) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs uppercase tracking-wider text-white hover:bg-red-700 transition">
                    Edit Artist
                </a>
                <a href="{{ route('admin.artists.index') }}" class="text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl rounded-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-6">
                    @if ($artist->photo_path)
                        <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-32 w-32 rounded-xl object-cover border border-slate-700 shadow-md">
                    @else
                        <div class="h-32 w-32 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 font-black text-3xl">
                            {{ strtoupper(substr($artist->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="space-y-2 flex-1">
                        <h3 class="text-2xl font-black uppercase tracking-wider text-white">{{ $artist->name }}</h3>
                        <p class="text-xs text-slate-400">Slug: <code class="bg-slate-900 border border-slate-800 px-2 py-0.5 rounded text-red-400 font-mono">{{ $artist->slug }}</code></p>
                        <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-lg bg-red-950 text-red-400 border border-red-800">
                            {{ $artist->genre ?? 'Hip-Hop' }}
                        </span>
                        <div class="mt-4 text-slate-300 text-sm whitespace-pre-line leading-relaxed">
                            {{ $artist->bio ?: 'Belum ada biografi untuk artist ini.' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl rounded-xl p-6 sm:p-8">
                <h4 class="font-black uppercase tracking-wider text-white text-base mb-4">Event yang Diikuti (Lineup)</h4>
                @if ($artist->events->isEmpty())
                    <p class="text-xs text-slate-500">Artist ini belum terdaftar di event manapun.</p>
                @else
                    <div class="divide-y divide-slate-800">
                        @foreach ($artist->events as $event)
                            <div class="py-3.5 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $event->title }}</div>
                                    <div class="text-xs text-slate-400">
                                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y, H:i') }} | Venue: {{ $event->venue }}
                                    </div>
                                </div>
                                <div class="text-right text-xs">
                                    <span class="font-bold text-red-500">{{ $event->pivot->performance_stage ?? 'Main Stage' }}</span>
                                    <div class="text-slate-400 font-medium">{{ $event->pivot->performance_time ? \Carbon\Carbon::parse($event->pivot->performance_time)->format('H:i') : '-' }} WIB</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Artist: ') }} {{ $artist->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.artists.edit', $artist) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit Artist
                </a>
                <a href="{{ route('admin.artists.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-start space-x-6">
                    @if ($artist->photo_path)
                        <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-32 w-32 rounded-lg object-cover border shadow">
                    @else
                        <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-3xl">
                            {{ strtoupper(substr($artist->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="space-y-2">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $artist->name }}</h3>
                        <p class="text-sm text-gray-500">Slug: <code class="bg-gray-100 px-2 py-0.5 rounded">{{ $artist->slug }}</code></p>
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $artist->genre ?? 'Hip-Hop' }}
                        </span>
                        <div class="mt-4 text-gray-700 text-sm whitespace-pre-line">
                            {{ $artist->bio ?: 'Belum ada biografi untuk artist ini.' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold text-lg text-gray-800 mb-4">Event yang Diikuti (Lineup)</h4>
                @if ($artist->events->isEmpty())
                    <p class="text-sm text-gray-500">Artist ini belum terdaftar di event manapun.</p>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach ($artist->events as $event)
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $event->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y, H:i') }} | Venue: {{ $event->venue }}
                                    </div>
                                </div>
                                <div class="text-right text-xs">
                                    <span class="font-medium text-indigo-600">{{ $event->pivot->performance_stage ?? 'Main Stage' }}</span>
                                    <div class="text-gray-400">{{ $event->pivot->performance_time ? \Carbon\Carbon::parse($event->pivot->performance_time)->format('H:i') : '-' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

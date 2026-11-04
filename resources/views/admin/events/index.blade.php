<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
                {{ __('Manage Show') }}
            </h2>
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs uppercase tracking-wider text-white hover:bg-red-700 active:bg-red-800 transition duration-150 shadow-sm">
                + Drop Show Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-red-950/80 border border-red-800 text-red-300 text-sm font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl">
                <div class="p-6 text-slate-100">
                    <div class="overflow-x-auto">
                        @php
                            $sortUrl = fn($col) => request()->fullUrlWithQuery(['sort_by' => $col, 'sort_dir' => ($sortBy === $col && $sortDir === 'asc') ? 'desc' : 'asc', 'page' => 1]);
                            $sortIcon = fn($col) => $sortBy === $col ? ($sortDir === 'asc' ? '↑' : '↓') : '↕';
                        @endphp
                        <table class="min-w-full divide-y divide-slate-800 text-left">
                            <thead class="bg-slate-900 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Banner</th>
                                    <th scope="col" class="px-6 py-3.5">
                                        <a href="{{ $sortUrl('title') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                                            Info Show <span class="{{ $sortBy === 'title' ? 'text-red-400' : '' }}">{{ $sortIcon('title') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3.5">Lineup</th>
                                    <th scope="col" class="px-6 py-3.5">
                                        <a href="{{ $sortUrl('event_date') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                                            Waktu & Tempat <span class="{{ $sortBy === 'event_date' ? 'text-red-400' : '' }}">{{ $sortIcon('event_date') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3.5">
                                        <a href="{{ $sortUrl('tickets_sold_count') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                                            Tiket & Kuota <span class="{{ $sortBy === 'tickets_sold_count' ? 'text-red-400' : '' }}">{{ $sortIcon('tickets_sold_count') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse ($events as $event)
                                    <tr class="hover:bg-slate-800/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($event->banner_path)
                                                <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="h-14 w-24 object-cover border border-slate-700 shadow-sm">
                                            @else
                                                <div class="h-14 w-24 bg-slate-900 border border-slate-800 flex items-center justify-center text-xs text-slate-500 font-bold">
                                                    No Banner
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white text-base">{{ $event->title }}</div>
                                            <div class="text-xs text-slate-400">By: {{ $event->user?->name ?? 'Admin' }}</div>
                                            <div class="mt-1">
                                                @if ($event->is_published)
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold uppercase tracking-wider bg-red-950 text-red-400 border border-red-800">Live</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold uppercase tracking-wider bg-slate-900 text-slate-400 border border-slate-700">Draft</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                                @forelse ($event->artists as $artist)
                                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-slate-900 text-slate-300 border border-slate-700">
                                                        {{ $artist->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-500">Belum ada lineup</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-300">
                                            <div class="font-bold text-white">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M Y, H:i') }}</div>
                                            <div class="text-slate-400 mt-0.5">📍 {{ $event->venue }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            <div class="font-black text-white text-sm">Rp {{ number_format($event->price, 0, ',', '.') }}</div>
                                            <div class="text-slate-400 mt-0.5">Spot: <span class="font-bold text-red-500">{{ $event->remaining_quota }}</span> / {{ $event->total_quota }}</div>
                                            <div class="mt-0.5">Terjual: <span class="font-bold text-red-400">{{ $event->tickets_sold_count }}</span></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold uppercase tracking-wider space-x-2">
                                            <a href="{{ route('admin.events.show', $event) }}" class="text-slate-400 hover:text-white transition">Lihat</a>
                                            <a href="{{ route('admin.events.edit', $event) }}" class="text-white hover:text-red-400 transition">Edit</a>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin mau hapus show ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 transition">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                            Belum ada show. Drop yang pertama sekarang.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

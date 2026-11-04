<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
                {{ __('Master Data Artist Hip-Hop') }}
            </h2>
            <a href="{{ route('admin.artists.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs uppercase tracking-wider text-white hover:bg-red-700 active:bg-red-800 transition duration-150 shadow-sm">
                + Tambah Artist
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-red-950/80 border border-red-800 text-red-300 rounded-xl text-sm font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-slate-800/90 border border-slate-800 overflow-hidden shadow-xl rounded-xl">
                <div class="p-6 text-slate-100">
                    <div class="overflow-x-auto">
                        @php
                            $sortUrl = fn($col) => request()->fullUrlWithQuery(['sort_by' => $col, 'sort_dir' => ($sortBy === $col && $sortDir === 'asc') ? 'desc' : 'asc', 'page' => 1]);
                            $sortIcon = fn($col) => $sortBy === $col ? ($sortDir === 'asc' ? '↑' : '↓') : '↕';
                        @endphp
                        <table class="min-w-full divide-y divide-slate-800 text-left">
                            <thead class="bg-slate-900 text-[10px] uppercase font-bold tracking-wider text-slate-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5">Foto</th>
                                    <th scope="col" class="px-6 py-3.5">
                                        <a href="{{ $sortUrl('name') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                                            Nama Artist <span class="{{ $sortBy === 'name' ? 'text-red-400' : '' }}">{{ $sortIcon('name') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3.5">Genre</th>
                                    <th scope="col" class="px-6 py-3.5">
                                        <a href="{{ $sortUrl('events_count') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                                            Jumlah Tampil <span class="{{ $sortBy === 'events_count' ? 'text-red-400' : '' }}">{{ $sortIcon('events_count') }}</span>
                                        </a>
                                    </th>
                                    <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @forelse ($artists as $artist)
                                    <tr class="hover:bg-slate-800/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($artist->photo_path)
                                                <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="h-12 w-12 rounded-lg object-cover border border-slate-700 shadow-sm">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 font-black text-base">
                                                    {{ strtoupper(substr($artist->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-white text-base">{{ $artist->name }}</div>
                                            <div class="text-xs text-slate-400 font-mono">slug: {{ $artist->slug }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-lg bg-red-950 text-red-400 border border-red-800">
                                                {{ $artist->genre ?? 'Hip-Hop' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300 font-bold">
                                            {{ $artist->events_count }} Event
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold uppercase tracking-wider space-x-2">
                                            <a href="{{ route('admin.artists.show', $artist) }}" class="text-slate-400 hover:text-white transition">Lihat</a>
                                            <a href="{{ route('admin.artists.edit', $artist) }}" class="text-white hover:text-red-400 transition">Edit</a>
                                            <form action="{{ route('admin.artists.destroy', $artist) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artist ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 transition">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                            Belum ada data artist. Silakan tambahkan artist pertama Anda!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $artists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

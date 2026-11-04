<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
            {{ __('Organizer Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Cards Ringkasan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-slate-800/90 p-6 shadow-lg border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block mb-2">Total Show</span>
                    <span class="text-3xl font-black text-white">{{ $totalEvents }}</span>
                </div>
                <div class="bg-slate-800/90 p-6 shadow-lg border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block mb-2">Tiket Terjual</span>
                    <span class="text-3xl font-black text-red-500">{{ number_format($totalTicketsSold) }}</span>
                </div>
                <div class="bg-slate-800/90 p-6 shadow-lg border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block mb-2">Total Revenue</span>
                    <span class="text-3xl font-black text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="bg-slate-800/90 p-6 shadow-lg border border-slate-800">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block mb-2">Total Masuk Gate</span>
                    <span class="text-3xl font-black text-slate-200">{{ number_format($totalCheckedIn) }}</span>
                </div>
            </div>

            <!-- Tabel Monitoring Check-in Event -->
            <div class="bg-slate-800/90 shadow-lg border border-slate-800 overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                    <h3 class="font-black uppercase tracking-wider text-white text-base">Statistik Show & Gate</h3>
                    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition duration-150">
                        + Drop Show Baru
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-900 text-[10px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-4">Nama Show</th>
                                <th class="px-6 py-4">Spot Tersisa / Total</th>
                                <th class="px-6 py-4">Terjual</th>
                                <th class="px-6 py-4">Check-In Rate</th>
                                <th class="px-6 py-4 text-right">Gate Scanner</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($recentEvents as $event)
                                <tr class="hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 font-bold text-white">{{ $event->title }}</td>
                                    <td class="px-6 py-4 text-slate-300 font-medium">{{ $event->remaining_quota }} / {{ $event->total_quota }}</td>
                                    <td class="px-6 py-4 font-bold text-red-500">{{ $event->tickets_count }} Tiket</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider bg-red-950 text-red-400 border border-red-800">
                                            {{ $event->tickets_count > 0 ? round(($event->checked_in_tickets_count / $event->tickets_count) * 100) : 0 }}% 
                                            ({{ $event->checked_in_tickets_count }}/{{ $event->tickets_count }})
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.scanner.index', $event->id) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-900 border border-slate-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-slate-800 hover:border-slate-600 transition">
                                            📷 Buka Gate
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                        Belum ada show aktif. Drop show baru dulu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

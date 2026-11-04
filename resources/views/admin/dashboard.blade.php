<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organizer Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Cards Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Total Event</span>
                    <span class="text-3xl font-black text-gray-900">{{ $totalEvents }}</span>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Tiket Terjual</span>
                    <span class="text-3xl font-black text-indigo-600">{{ number_format($totalTicketsSold) }}</span>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Total Revenue</span>
                    <span class="text-3xl font-black text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Total Check-In On-Site</span>
                    <span class="text-3xl font-black text-purple-600">{{ number_format($totalCheckedIn) }}</span>
                </div>
            </div>

            <!-- Tabel Monitoring Check-in Event -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Statistik Event & Scanner Gate</h3>
                </div>
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-400 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Event</th>
                            <th class="px-6 py-4">Sisa / Total Kuota</th>
                            <th class="px-6 py-4">Terjual</th>
                            <th class="px-6 py-4">Check-In Rate</th>
                            <th class="px-6 py-4 text-right">Aksi Gate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recentEvents as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $event->title }}</td>
                                <td class="px-6 py-4">{{ $event->remaining_quota }} / {{ $event->total_quota }}</td>
                                <td class="px-6 py-4 font-semibold text-indigo-600">{{ $event->tickets_count }} Tiket</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800">
                                        {{ $event->tickets_count > 0 ? round(($event->checked_in_tickets_count / $event->tickets_count) * 100) : 0 }}% 
                                        ({{ $event->checked_in_tickets_count }}/{{ $event->tickets_count }})
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.scanner.index', $event->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-gray-800">
                                        📷 Open Scanner Gate
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>

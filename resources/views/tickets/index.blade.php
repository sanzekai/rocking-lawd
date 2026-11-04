<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tiket Saya - Rocking Lawd') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($tickets as $ticket)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col justify-between">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-xs font-mono text-gray-400 block">KODE TIKET</span>
                                    <span class="text-lg font-black text-gray-900 font-mono">{{ $ticket->ticket_code }}</span>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $ticket->status === 'checked_in' ? 'bg-gray-200 text-gray-700' : 'bg-green-100 text-green-800' }}">
                                    {{ strtoupper($ticket->status) }}
                                </span>
                            </div>

                            <h3 class="font-bold text-gray-800 text-base mb-1">{{ $ticket->event->title }}</h3>
                            <p class="text-xs text-gray-500 mb-6">📍 {{ $ticket->event->venue }} • {{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y, H:i') }} WIB</p>

                            <!-- QR Code Generator -->
                            <div class="bg-gray-50 p-4 rounded-xl flex flex-col items-center justify-center border border-gray-200">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($ticket->ticket_code) !!}
                                <span class="text-xs text-gray-400 mt-2 font-mono">Tunjukkan QR Code ini ke Panitia</span>
                            </div>
                        </div>

                        <div class="bg-gray-900 px-6 py-3 text-xs text-gray-400 flex justify-between items-center">
                            <span>Invoice: {{ $ticket->order->order_number }}</span>
                            <span class="text-white font-bold">Rp {{ number_format($ticket->event->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 text-center text-gray-500 rounded-lg shadow-sm">
                        Kamu belum memiliki tiket event. Silakan jelajahi event aktif di landing page!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

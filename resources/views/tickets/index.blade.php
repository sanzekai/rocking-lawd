<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
            {{ __('Tiket Gue') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-red-950/80 border border-red-800 text-red-300 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($tickets as $ticket)
                    <div class="bg-slate-800/90 shadow-xl border border-slate-800 overflow-hidden flex flex-col justify-between">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 block">KODE TIKET</span>
                                    <span class="text-lg font-black text-white font-mono tracking-wider">{{ $ticket->ticket_code }}</span>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider {{ $ticket->status === 'checked_in' ? 'bg-slate-900 text-slate-400 border border-slate-700' : 'bg-red-950 text-red-400 border border-red-800' }}">
                                    {{ $ticket->status === 'checked_in' ? 'CHECKED-IN' : 'VALID' }}
                                </span>
                            </div>

                            <h3 class="font-black uppercase tracking-wider text-white text-base mb-1">{{ $ticket->event->title }}</h3>
                            <p class="text-xs text-slate-400 mb-6 font-medium">📍 {{ $ticket->event->venue }} • {{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y, H:i') }} WIB</p>

                            <!-- QR Code Generator -->
                            <div class="bg-white p-4 flex flex-col items-center justify-center border border-slate-700 shadow-inner">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($ticket->ticket_code) !!}
                                <span class="text-[10px] font-bold text-slate-600 mt-2 font-mono uppercase">Tunjukkin ke Gate Scanner</span>
                            </div>
                        </div>

                        <div class="bg-slate-900 border-t border-slate-800 px-6 py-3.5 text-xs text-slate-400 flex justify-between items-center">
                            <span class="font-mono">Inv: {{ $ticket->order->order_number }}</span>
                            <span class="text-white font-black">Rp {{ number_format($ticket->event->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-900 border border-slate-800 p-12 text-center text-slate-400">
                        <span class="text-4xl block mb-3">🎫</span>
                        <h4 class="text-white font-bold text-base mb-1">Belum ada tiket.</h4>
                        <p class="text-xs text-slate-400 mb-4">Sikat show yang lagi available sekarang sebelum sold out.</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition">
                            Cek Show &rarr;
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

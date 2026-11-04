<x-app-layout>
    <div class="py-12 bg-slate-950 text-slate-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-950 border border-red-800 text-red-300 text-sm font-bold flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-slate-800/90 overflow-hidden shadow-2xl border border-slate-800">
                @if ($event->banner_path)
                    <div class="w-full h-80 sm:h-96 overflow-hidden bg-slate-950">
                        <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                        <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-lg bg-red-950 text-red-400 border border-red-800">
                            {{ $event->remaining_quota > 0 ? 'Tiket Tersedia' : 'Sold Out' }}
                        </span>
                        <span class="text-xs font-mono text-slate-400">
                            ID: #{{ $event->id }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-wider text-white mb-2">{{ $event->title }}</h1>
                    <p class="text-sm font-medium text-slate-400 mb-6">📍 {{ $event->venue }} • {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }} WIB</p>

                    <!-- Lineup Artist -->
                    <div class="mb-8 border-t border-b border-slate-800 py-6">
                        <h3 class="text-xs uppercase tracking-widest text-red-500 font-black mb-4">LINEUP ARTIST</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @forelse ($event->artists as $artist)
                                <div class="bg-slate-900/90 p-3.5 rounded-xl flex items-center space-x-3 border border-slate-800">
                                    <div class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center font-black text-sm overflow-hidden flex-shrink-0 text-white">
                                        @if ($artist->photo_path)
                                            <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                                        @else
                                            🎤
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-sm text-white truncate">{{ $artist->name }}</h4>
                                        <span class="text-xs text-slate-400 block truncate font-medium">{{ $artist->pivot->performance_stage ?? 'Main Stage' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-sm text-slate-500 italic">
                                    Lineup artist belum diumumkan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Detail & Form Checkout -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                        <div class="md:col-span-2">
                            <h3 class="text-xs uppercase tracking-widest text-slate-400 font-bold mb-3">Tentang Event</h3>
                            <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">{{ $event->description }}</p>
                        </div>

                        <div class="bg-slate-900 p-6 border border-slate-800 shadow-xl">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block mb-1">Harga Tiket</span>
                            <span class="text-3xl font-black text-white mb-4 block">
                                {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'GRATIS' }}
                            </span>
                            
                            <div class="flex justify-between items-center text-xs text-slate-400 mb-5 pb-4 border-b border-slate-800">
                                <span>Spot Tersisa:</span>
                                <span class="font-bold text-white"><strong class="text-red-500">{{ $event->remaining_quota }}</strong> / {{ $event->total_quota }}</span>
                            </div>

                            @auth
                                @if ($event->remaining_quota > 0)
                                    <form action="{{ route('orders.checkout', $event->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Jumlah Tiket</label>
                                            <input type="number" name="quantity" value="1" min="1" max="5" class="w-full bg-slate-950 text-white border-slate-700 focus:border-red-600 focus:ring-red-600 font-bold">
                                        </div>
                                        <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition duration-150 uppercase tracking-wider text-xs shadow-sm">
                                            Amankan Tiket Lu
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full py-3 bg-red-950/80 border border-red-800 text-red-400 font-bold rounded-lg cursor-not-allowed uppercase tracking-wider text-xs">
                                        SOLD OUT — Tiket Habis
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block text-center w-full py-3 border border-slate-700 hover:bg-slate-800 text-white font-bold rounded-lg uppercase tracking-wider text-xs transition duration-150">
                                    Masuk Dulu Buat Beli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

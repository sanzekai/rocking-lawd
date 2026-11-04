<x-app-layout>
    <div class="py-12 bg-gray-900 text-white min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-600 text-white rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
                @if ($event->banner_path)
                    <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-80 object-cover">
                @endif

                <div class="p-8">
                    <h1 class="text-3xl font-black tracking-tight text-white mb-2">{{ $event->title }}</h1>
                    <p class="text-sm text-gray-400 mb-6">📍 {{ $event->venue }} • {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }} WIB</p>

                    <!-- Lineup Artist -->
                    <div class="mb-8 border-t border-b border-gray-700 py-6">
                        <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-4">LINEUP ARTIST</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @forelse ($event->artists as $artist)
                                <div class="bg-gray-900 p-3 rounded-xl flex items-center space-x-3 border border-gray-700">
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-sm overflow-hidden flex-shrink-0">
                                        @if ($artist->photo_path)
                                            <img src="{{ asset('storage/' . $artist->photo_path) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover">
                                        @else
                                            🎤
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-sm text-white truncate">{{ $artist->name }}</h4>
                                        <span class="text-xs text-gray-400 block truncate">{{ $artist->pivot->performance_stage ?? 'Main Stage' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-sm text-gray-400 italic">
                                    Belum ada lineup artist yang diumumkan.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Detail & Form Checkout -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                        <div class="md:col-span-2">
                            <h3 class="text-sm uppercase tracking-widest text-gray-400 font-bold mb-2">Tentang Event</h3>
                            <p class="text-gray-300 text-sm leading-relaxed">{{ $event->description }}</p>
                        </div>

                        <div class="bg-gray-900 p-6 rounded-xl border border-gray-700">
                            <span class="text-xs text-gray-400 block mb-1">Harga Tiket</span>
                            <span class="text-2xl font-extrabold text-green-400 mb-4 block">
                                {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'GRATIS' }}
                            </span>
                            
                            <span class="text-xs text-gray-400 block mb-3">Sisa Kuota: <strong class="text-white">{{ $event->remaining_quota }}</strong> / {{ $event->total_quota }}</span>

                            @auth
                                @if ($event->remaining_quota > 0)
                                    <form action="{{ route('orders.checkout', $event->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="text-xs text-gray-400 block mb-1">Jumlah Tiket</label>
                                            <input type="number" name="quantity" value="1" min="1" max="5" class="w-full bg-gray-800 text-white border-gray-700 rounded-lg focus:ring-green-500">
                                        </div>
                                        <button type="submit" class="w-full py-3 bg-green-500 hover:bg-green-600 text-gray-950 font-black rounded-lg transition duration-200 uppercase tracking-wider text-sm">
                                            Beli Tiket Sekarang
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full py-3 bg-red-900 text-red-300 font-bold rounded-lg cursor-not-allowed uppercase text-sm">
                                        Kuota Tiket Habis
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block text-center w-full py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg uppercase text-sm">
                                    Login Untuk Membeli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

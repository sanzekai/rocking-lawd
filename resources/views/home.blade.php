<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rocking Lawd - Portal Event Musik</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="border-b border-gray-800 bg-gray-900/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <span class="text-2xl">⚡</span>
                <span class="font-black text-xl tracking-wider text-white">ROCKING <span class="text-green-400">LAWD</span></span>
            </a>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('my-tickets.index') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition">Tiket Saya</a>
                    @role('organizer|admin')
                        <a href="{{ route('admin.events.index') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition">Kelola Event</a>
                    @endrole
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-sm font-semibold rounded-lg transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-gray-950 text-sm font-bold rounded-lg transition">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative py-16 sm:py-24 bg-gradient-to-b from-gray-900 to-gray-950 border-b border-gray-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3 mb-4 rounded-full text-xs font-bold uppercase tracking-widest bg-green-500/10 text-green-400 border border-green-500/30">
                Official Music Festival & Concert Portal
            </span>
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-white mb-4">
                Rasakan Energi Panggung <br class="hidden sm:inline" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-300">Live Musik Terbaik</span>
            </h1>
            <p class="max-w-2xl mx-auto text-base sm:text-lg text-gray-400">
                Pesan tiket konser dan festival favoritmu dengan cepat, aman, dan tanpa calo. Garansi verifikasi instan dengan QR Code.
            </p>
        </div>
    </header>

    <!-- Catalog Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black text-white">Event Musik Aktif</h2>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">Jelajahi konser pilihan yang siap mengguncang harimu</p>
            </div>
            <span class="text-xs text-gray-400 bg-gray-900 px-3 py-1.5 rounded-full border border-gray-800">
                {{ $events->count() }} Event Tersedia
            </span>
        </div>

        @if ($events->isEmpty())
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-12 text-center">
                <span class="text-4xl block mb-3">🎸</span>
                <h3 class="text-lg font-bold text-white mb-1">Belum Ada Event Aktif</h3>
                <p class="text-sm text-gray-400">Nantikan informasi jadwal konser mendatang di portal ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <div class="bg-gray-900 border border-gray-800 hover:border-gray-700 rounded-2xl overflow-hidden shadow-lg transition duration-200 flex flex-col justify-between group">
                        <div>
                            <div class="relative h-48 bg-gray-800 overflow-hidden">
                                @if ($event->banner_path)
                                    <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-gray-800 text-gray-600">
                                        🎵
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $event->remaining_quota > 0 ? 'bg-green-500/90 text-gray-950' : 'bg-red-500 text-white' }}">
                                        {{ $event->remaining_quota > 0 ? 'Sisa ' . $event->remaining_quota . ' Tiket' : 'Sold Out' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <span class="text-xs text-green-400 font-semibold uppercase tracking-wider block mb-1">
                                    📍 {{ $event->venue }}
                                </span>
                                <h3 class="text-xl font-bold text-white group-hover:text-green-400 transition mb-2">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-xs text-gray-400 mb-4">
                                    📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }} WIB
                                </p>

                                @if ($event->artists->isNotEmpty())
                                    <div class="border-t border-gray-800/80 pt-3">
                                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block mb-2">Lineup Artist:</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($event->artists->take(3) as $artist)
                                                <span class="text-xs bg-gray-800 text-gray-300 px-2 py-0.5 rounded-md border border-gray-700/60">
                                                    {{ $artist->name }}
                                                </span>
                                            @endforeach
                                            @if ($event->artists->count() > 3)
                                                <span class="text-xs text-gray-400 self-center">
                                                    +{{ $event->artists->count() - 3 }} lainnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 pt-0 border-t border-gray-800/80 mt-4 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-gray-400 block">Harga</span>
                                <span class="text-lg font-black text-white">
                                    {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'GRATIS' }}
                                </span>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-gray-950 text-xs font-black uppercase tracking-wider rounded-lg transition duration-150">
                                Detail Event &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="border-t border-gray-800 bg-gray-900/60 py-6 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Rocking Lawd. All rights reserved. Live music ticketing platform.
    </footer>
</body>
</html>

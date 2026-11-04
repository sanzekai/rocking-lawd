<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rocking Lawd - Portal Event Musik</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased min-h-screen flex flex-col selection:bg-red-600 selection:text-white">
    <!-- Navbar -->
    <nav class="border-b border-red-600/30 bg-slate-950/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Rocking Lawd Logo" class="h-10 w-auto">
            </a>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('my-tickets.index') }}" class="text-xs font-bold uppercase tracking-wider text-slate-300 hover:text-white transition">Tiket Saya</a>
                    @role('organizer|admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold uppercase tracking-wider text-red-500 hover:text-red-400 transition">Organizer Portal</a>
                    @endrole
                    <a href="{{ route('dashboard') }}" class="px-3.5 py-2 border border-slate-700 bg-slate-900 hover:bg-slate-800 text-xs font-bold uppercase tracking-wider rounded-lg text-white transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-slate-300 hover:text-white transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition duration-150">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative py-16 sm:py-24 bg-slate-950 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Rocking Lawd" class="h-20 w-auto">
            </div>
            <span class="inline-block py-1 px-3 mb-4 text-xs font-black uppercase tracking-widest bg-red-950 text-red-400 border border-red-800">
                Official Music Festival & Concert Portal
            </span>
            <h1 class="text-4xl sm:text-6xl font-black uppercase tracking-wider text-white mb-4">
                Lineup Panas. <br class="hidden sm:inline" />
                <span class="text-red-600">Crowd Pecah.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-400">
                Cek jadwal show & amankan tiket sebelum habis. No calo, no ribet — QR Code langsung di tangan lu.
            </p>
        </div>
    </header>

    <!-- Catalog Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-wider text-white">Show Aktif</h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">Cek jadwal show & amankan tiket sebelum habis.</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-900 px-3 py-1.5 border border-slate-800">
                {{ $events->count() }} Show Tersedia
            </span>
        </div>

        @if ($events->isEmpty())
            <div class="bg-slate-900 border border-slate-800 p-12 text-center">
                <span class="text-4xl block mb-3">🎸</span>
                <h3 class="text-lg font-black uppercase tracking-wider text-white mb-1">Belum Ada Show Aktif</h3>
                <p class="text-sm text-slate-400">Pantau terus — jadwal show berikutnya bakal drop soon.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($events as $event)
                    <div class="bg-slate-800/90 border border-slate-800 hover:border-slate-700 overflow-hidden shadow-lg transition duration-200 flex flex-col justify-between group">
                        <div>
                            <div class="relative h-48 bg-slate-900 overflow-hidden">
                                @if ($event->banner_path)
                                    <img src="{{ asset('storage/' . $event->banner_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-slate-900 text-slate-700">
                                        🎵
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider {{ $event->remaining_quota > 0 ? 'bg-red-950 text-red-400 border border-red-800' : 'bg-slate-900 text-slate-400 border border-slate-800' }}">
                                        {{ $event->remaining_quota > 0 ? 'Sisa ' . $event->remaining_quota . ' Tiket' : 'SOLD OUT' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <span class="text-xs text-red-500 font-bold uppercase tracking-wider block mb-1">
                                    📍 {{ $event->venue }}
                                </span>
                                <h3 class="text-xl font-black uppercase tracking-wider text-white group-hover:text-red-500 transition mb-2">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-xs text-slate-400 mb-4 font-medium">
                                    📅 {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }} WIB
                                </p>

                                @if ($event->artists->isNotEmpty())
                                    <div class="border-t border-slate-800 pt-3">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-2">Lineup:</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($event->artists->take(3) as $artist)
                                                <span class="text-xs bg-slate-900 text-slate-300 px-2 py-0.5 border border-slate-800 font-medium">
                                                    {{ $artist->name }}
                                                </span>
                                            @endforeach
                                            @if ($event->artists->count() > 3)
                                                <span class="text-xs text-slate-500 self-center font-bold">
                                                    +{{ $event->artists->count() - 3 }} lagi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 pt-0 border-t border-slate-800/80 mt-4 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider font-bold text-slate-500 block">Harga</span>
                                <span class="text-lg font-black text-white">
                                    {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'GRATIS' }}
                                </span>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition duration-150">
                                Cek Detail &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer class="border-t border-red-600/30 bg-slate-950 py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} Rocking Lawd. No calo. No bullshit. Pure live music.
    </footer>
</body>
</html>
